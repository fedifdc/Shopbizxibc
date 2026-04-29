<?php
/**
 * Hooks: WooCommerce triggers & order lifecycle integration.
 *
 * Instant commission calculation on order completion.
 * Saves to both wp_crs_royalty_assignments (legacy) and wp_crs_transactions (new).
 */

defined('ABSPATH') || exit;

/**
 * WooCommerce Order Completed — trigger royalty calculation INSTANT.
 *
 * Fires when WC order transitions to 'completed' status.
 * Calculates commission via linear upward scan and saves results.
 *
 * Duplicate-safe: skips if order_id already has assignments.
 * Guest-safe: skips if no WP user.
 * Eligibility-safe: skips if all products explicitly excluded.
 */
add_action('woocommerce_order_status_completed', 'crs_on_order_completed', 10, 2);
function crs_on_order_completed($order_id, $order = null) {
    if (!$order) {
        $order = wc_get_order($order_id);
    }
    if (!$order) {
        return;
    }

    // Get buyer (customer) from order
    $buyer_user_id = $order->get_user_id();
    if (!$buyer_user_id) {
        crs_log("Order $order_id: Guest order, skipping royalty calculation.");
        return;
    }

    // Duplicate check — skip if already processed in crs_transactions
    global $wpdb;
    $table_trans = $wpdb->prefix . 'crs_transactions';
    $already_processed = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_trans WHERE order_id = %d",
        $order_id
    ));
    if ($already_processed > 0) {
        crs_log("Order $order_id: Already processed (transactions), skipping.");
        return;
    }

    // Also check legacy table
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $already_legacy = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_assign WHERE order_id = %d",
        $order_id
    ));
    if ($already_legacy > 0) {
        crs_log("Order $order_id: Already processed (legacy), skipping.");
        return;
    }

    // Check product eligibility
    $has_eligible = false;
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $is_eligible = get_post_meta($product_id, '_crs_royalty_eligible', true);
        if ($is_eligible !== 'no') {
            $has_eligible = true;
            break;
        }
    }
    if (!$has_eligible) {
        crs_log("Order $order_id: No royalty-eligible products, skipping.");
        return;
    }

    // Calculate point_level earned from this order
    $point_earned = crs_get_order_point_level($order);
    if ($point_earned <= 0) {
        crs_log("Order $order_id: No point_level earned, skipping.");
        return;
    }

    // === CORE ALGORITHM: Linear upward scan via crs_calculate_commission() ===
    $commissions = crs_calculate_commission($order_id);
    if (empty($commissions)) {
        crs_log("Order $order_id: No qualified uplines found for buyer $buyer_user_id.");
        return;
    }

    // Save to wp_crs_transactions (new table)
    crs_save_commissions($commissions, 'pending');

    // Also save to wp_crs_royalty_assignments (legacy compatibility)
    foreach ($commissions as $c) {
        $wpdb->insert($table_assign, [
            'order_id'          => $order_id,
            'buyer_id'          => $buyer_user_id,
            'recipient_id'      => $c['user_id'],
            'tier_number'       => $c['tier'],
            'rate_percent'      => $c['rate_percent'],
            'commission_amount' => $c['amount'],
            'status'            => 'pending',
        ]);
    }

    crs_log("Order $order_id: Commission calculated — " . count($commissions) . " entries (base=$point_earned)");
}

/**
 * WooCommerce Thankyou page fallback — catch orders that complete before the status hook fires.
 */
add_action('woocommerce_thankyou', 'crs_on_thankyou', 10, 1);
function crs_on_thankyou($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    if ($order->get_status() === 'completed') {
        crs_on_order_completed($order_id, $order);
    }
}

/**
 * Manual recalculation hook — for admin use.
 * Delete existing entries for order_id then recalculate.
 *
 * Usage: do_action('crs_recalculate_order', $order_id);
 */
add_action('crs_recalculate_order', 'crs_on_recalculate', 10, 1);
function crs_on_recalculate($order_id) {
    global $wpdb;
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $table_trans = $wpdb->prefix . 'crs_transactions';

    // Delete existing entries
    $wpdb->delete($table_assign, ['order_id' => $order_id], ['%d']);
    $wpdb->delete($table_trans, ['order_id' => $order_id], ['%d']);

    crs_log("Order $order_id: Deleted existing commissions for recalculation.");

    // Recalculate
    $order = wc_get_order($order_id);
    if ($order) {
        crs_on_order_completed($order_id, $order);
    }
}
