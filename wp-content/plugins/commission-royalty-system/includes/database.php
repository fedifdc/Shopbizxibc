<?php
/**
 * Database helpers for commission-royalty-system.
 */

defined('ABSPATH') || exit;

/**
 * Get all uncredited orders that need royalty processing.
 */
function crs_get_uncredited_orders() {
    global $wpdb;
    $table_orders = $wpdb->prefix . 'wc_orders';

    // Get completed orders from today that don't have royalty assignments
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';

    return $wpdb->get_results(
        "SELECT o.id, o.customer_id
         FROM $table_orders o
         WHERE o.status = 'wc-completed'
           AND o.date_created >= CURDATE()
           AND o.id NOT IN (SELECT DISTINCT order_id FROM $table_assign)
         ORDER BY o.date_created ASC",
        ARRAY_A
    );
}

/**
 * Get total royalti for a specific user (all time).
 */
function crs_get_user_total_royalty($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_royalty_assignments';

    return $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(commission_amount), 0) FROM $table
         WHERE recipient_id = %d AND status = 'credited'",
        $user_id
    ));
}

/**
 * Get royalti history for a specific user.
 */
function crs_get_user_royalty_history($user_id, $limit = 20) {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_royalty_assignments';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE recipient_id = %d
         ORDER BY created_at DESC LIMIT %d",
        $user_id, $limit
    ), ARRAY_A);
}

/**
 * Get today's summary stats.
 */
function crs_get_today_stats() {
    $today = date('Y-m-d');
    $cache_key = 'crs_daily_totals_' . str_replace('-', '', $today);

    $stats = get_transient($cache_key);
    if ($stats !== false) {
        return $stats;
    }

    // Calculate if not cached
    global $wpdb;
    $table = $wpdb->prefix . 'crs_royalty_assignments';

    $stats = [
        'total'   => $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(SUM(commission_amount), 0) FROM $table WHERE DATE(created_at) = %s AND status = 'credited'",
            $today
        )),
        'count'   => $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE DATE(created_at) = %s AND status = 'credited'",
            $today
        )),
        'pending' => $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'pending'"),
    ];

    set_transient($cache_key, $stats, HOUR_IN_SECONDS);

    return $stats;
}
