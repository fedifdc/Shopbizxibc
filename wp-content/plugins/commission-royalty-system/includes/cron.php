<?php
/**
 * Cron: Nightly validation, credit execution, and rank upgrade.
 */

defined('ABSPATH') || exit;

function crs_register_cron() {
    $cron_time = get_option('crs_cron_time', '23:00');
    $parts = explode(':', $cron_time);
    $hour = isset($parts[0]) ? (int)$parts[0] : 23;
    $minute = isset($parts[1]) ? (int)$parts[1] : 0;

    if (!wp_next_scheduled('crs_nightly_cron')) {
        wp_schedule_event(mktime($hour, $minute, 0, date('n'), date('j'), date('Y')), 'daily', 'crs_nightly_cron');
    }
}

add_action('crs_nightly_cron', 'crs_nightly_cron_handler');

function crs_nightly_cron_handler() {
    crs_log("Nightly cron started.");

    // Increase memory limit
    $memory_mb = get_option('crs_memory_limit_mb', 256);
    @ini_set('memory_limit', $memory_mb . 'M');

    // Step 1: Accumulate total_omset for ALL members
    // This must run BEFORE royalty qualification to ensure pre-computed data is fresh
    crs_accumulate_all_omset();

    // Step 2: Process pending royalty assignments
    crs_process_pending_royalties();

    // Step 3: Calculate daily totals
    crs_calculate_daily_totals();

    crs_log("Nightly cron completed.");
}

/**
 * Process all pending royalty assignments.
 * Credits MyCRED, adds voucher to recipient, logs to cb_laporan.
 * Matching instant-sponsor-commission logic: commission increases voucher.
 */
function crs_process_pending_royalties() {
    global $wpdb;
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $table_member = $wpdb->prefix . 'member';
    $table_laporan = 'cb_laporan';

    $pending = $wpdb->get_results(
        "SELECT * FROM $table_assign WHERE status = 'pending' ORDER BY order_id, tier_number ASC",
        ARRAY_A
    );

    if (empty($pending)) {
        crs_log("No pending royalties to process.");
        return;
    }

    $processed = 0;
    $failed = 0;

    // Use transaction instead of table locks (LOCK TABLES blocks MyCRED/usermeta/options)
    $wpdb->query('START TRANSACTION');

    $has_error = false;

    foreach ($pending as $assignment) {
        $recipient_id = $assignment['recipient_id'];
        $amount = intval($assignment['commission_amount']);
        $order_id = $assignment['order_id'];
        $tier = $assignment['tier_number'];
        $rate = $assignment['rate_percent'];

        // Get recipient's wp_member record
        $recipient_member = crs_get_member($recipient_id);
        if (!$recipient_member) {
            crs_log("Assignment {$assignment['id']}: Recipient member not found. Marked as failed.");
            continue;
        }

        // Credit MyCRED — add commission points to recipient
        if (function_exists('mycred')) {
            try {
                $mycred = mycred();
                $mycred->add_creds(
                    'crs_royalty',
                    $recipient_id,
                    $amount,
                    sprintf('Royalty Tier %d (%.1f%%) - Order #%d', $tier, $rate, $order_id),
                    $order_id,
                    '',
                    'mycred_default'
                );
            } catch (Exception $e) {
                crs_log("Assignment {$assignment['id']}: MyCRED credit failed — " . $e->getMessage());
                $failed++;
                continue;
            }
        }

        // Add voucher to recipient — matching instant-sponsor-commission logic
        $wpdb->query($wpdb->prepare(
            "UPDATE $table_member
             SET jml_voucher = jml_voucher + %d,
                 sisa_voucher = sisa_voucher + %d
             WHERE idwp = %d",
            $amount, $amount, $recipient_id
        ));

        // Log to cb_laporan — matching instant-sponsor-commission format
        $wpdb->query($wpdb->prepare(
            "INSERT INTO $table_laporan
             (tanggal, transaksi, debet, kredit, komisi, keterangan, id_user, id_sponsor, id_order)
             VALUES (NOW(), %s, 0, %d, %d, 'royalty', %d, %d, %d)",
            sprintf('Royalty Tier %d (%.1f%%) - Order #%d', $tier, $rate, $order_id),
            $amount,
            $amount,
            $recipient_member['id_user'],
            $assignment['buyer_id'],
            $order_id
        ));

        // Mark assignment as credited FIRST
        $wpdb->update($table_assign,
            ['status' => 'credited', 'credited_at' => current_time('mysql')],
            ['id' => $assignment['id']]
        );

        // Only log to wp_commission_logs after confirmed credited
        if (function_exists('insert_commission_log')) {
            insert_commission_log(
                $recipient_id,  // idwp = WordPress user ID
                'crs_royalty',
                'Royalty Komisi',
                sprintf('Royalty Tier %d (%.1f%%) - Order #%d', $tier, $rate, $order_id),
                $amount,
                'uang',
                'success'
            );
        }

        $processed++;
    }

    if ($has_error) {
        $wpdb->query('ROLLBACK');
        crs_log("Transaction rolled back due to errors.");
    } else {
        $wpdb->query('COMMIT');
    }

    crs_log("Processed: $processed, Failed: $failed");
}

/**
 * Calculate and cache daily totals for dashboard.
 */
function crs_calculate_daily_totals() {
    global $wpdb;
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';

    $today = date('Y-m-d');

    $today_total = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(commission_amount), 0) FROM $table_assign
         WHERE DATE(created_at) = %s AND status = 'credited'",
        $today
    ));

    $today_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_assign WHERE DATE(created_at) = %s AND status = 'credited'",
        $today
    ));

    $pending_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_assign WHERE status = 'pending'");

    set_transient('crs_daily_totals_' . str_replace('-', '', $today), [
        'total'       => $today_total,
        'count'       => $today_count,
        'pending'     => $pending_count,
    ], DAY_IN_SECONDS);
}
