<?php
/**
 * Plugin Name: Commission Royalty System
 * Description: Sistem komisi royalti dengan linear scan naik ke atas, rank qualification, dan rank cap.
 * Version: 1.0.0
 * Author: Bizmart / Atto
 * Requires PHP: 8.0
 * Text Domain: commission-royalty-system
 */

defined('ABSPATH') || exit;

define('CRS_VERSION', '1.0.0');
define('CRS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CRS_PLUGIN_URL', plugin_dir_url(__FILE__));

// ============================================================
// BOOT
// ============================================================
require_once CRS_PLUGIN_DIR . 'includes/core.php';
require_once CRS_PLUGIN_DIR . 'includes/rank.php';
require_once CRS_PLUGIN_DIR . 'includes/hooks.php';
require_once CRS_PLUGIN_DIR . 'includes/admin.php';
require_once CRS_PLUGIN_DIR . 'includes/cron.php';
require_once CRS_PLUGIN_DIR . 'includes/dashboard.php';
require_once CRS_PLUGIN_DIR . 'includes/database.php';

// ============================================================
// ACTIVATION
// ============================================================
register_activation_hook(__FILE__, 'crs_activate');
function crs_activate() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Table: crs_tiers — konfigurasi tier rate
    $table_tiers = $wpdb->prefix . 'crs_tiers';
    $sql_tiers = "CREATE TABLE $table_tiers (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        tier_number int(11) NOT NULL,
        rate_percent decimal(10,2) NOT NULL DEFAULT 0.00,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY tier_number (tier_number)
    ) $charset_collate;";

    // Table: crs_rank_caps — konfigurasi max tier per rank
    $table_caps = $wpdb->prefix . 'crs_rank_caps';
    $sql_caps = "CREATE TABLE $table_caps (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        rank_key varchar(50) NOT NULL,
        rank_label varchar(100) NOT NULL DEFAULT '',
        max_tier int(11) NOT NULL DEFAULT 0,
        min_direct_dl int(11) NOT NULL DEFAULT 0,
        min_total_omset decimal(15,2) NOT NULL DEFAULT 0.00,
        min_dl_rank varchar(50) NOT NULL DEFAULT 'free',
        min_dl_rank_count int(11) NOT NULL DEFAULT 0,
        alt_requirements text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY rank_key (rank_key)
    ) $charset_collate;";

    // Table: crs_royalty_assignments — hasil perhitungan royalti per order
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $sql_assign = "CREATE TABLE $table_assign (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_id bigint(20) NOT NULL,
        buyer_id bigint(20) NOT NULL,
        recipient_id bigint(20) NOT NULL,
        tier_number int(11) NOT NULL,
        rate_percent decimal(10,2) NOT NULL DEFAULT 0.00,
        commission_amount decimal(15,2) NOT NULL DEFAULT 0.00,
        status enum('pending','credited','failed') DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        credited_at datetime DEFAULT NULL,
        PRIMARY KEY (id),
        KEY order_id (order_id),
        KEY recipient_id (recipient_id),
        KEY status (status),
        KEY created_at (created_at)
    ) $charset_collate;";


    // Table: crs_tier_rates — mirror tier rate config with active toggle
    $table_tier_rates = $wpdb->prefix . 'crs_tier_rates';
    $sql_tier_rates = "CREATE TABLE $table_tier_rates (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        tier_num int(11) NOT NULL,
        rate_percent decimal(10,2) NOT NULL DEFAULT 0.00,
        active tinyint(1) NOT NULL DEFAULT 1,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY tier_num (tier_num)
    ) $charset_collate;";

    // Table: crs_transactions — commission transaction log
    $table_trans = $wpdb->prefix . 'crs_transactions';
    $sql_trans = "CREATE TABLE $table_trans (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_id bigint(20) NOT NULL,
        buyer_id bigint(20) NOT NULL,
        recipient_id bigint(20) NOT NULL,
        tier_number int(11) NOT NULL,
        rate_percent decimal(10,2) NOT NULL DEFAULT 0.00,
        commission_amount decimal(15,2) NOT NULL DEFAULT 0.00,
        base_amount decimal(15,2) NOT NULL DEFAULT 0.00,
        status enum('pending','credited','failed') DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        credited_at datetime DEFAULT NULL,
        PRIMARY KEY (id),
        KEY order_id (order_id),
        KEY recipient_id (recipient_id),
        KEY status (status),
        KEY created_at (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql_tiers);
    dbDelta($sql_caps);
    dbDelta($sql_assign);
    dbDelta($sql_tier_rates);
    dbDelta($sql_trans);

    // Fallback: ALTER TABLE to add missing columns if table already exists
    $existing_cols = $wpdb->get_col("SHOW COLUMNS FROM $table_caps", 0);
    $alter_cols = [
        'min_direct_dl'    => "ALTER TABLE $table_caps ADD COLUMN min_direct_dl int(11) NOT NULL DEFAULT 0 AFTER max_tier",
        'min_total_omset'  => "ALTER TABLE $table_caps ADD COLUMN min_total_omset decimal(15,2) NOT NULL DEFAULT 0.00 AFTER min_direct_dl",
        'min_dl_rank'      => "ALTER TABLE $table_caps ADD COLUMN min_dl_rank varchar(50) NOT NULL DEFAULT 'free' AFTER min_total_omset",
        'min_dl_rank_count' => "ALTER TABLE $table_caps ADD COLUMN min_dl_rank_count int(11) NOT NULL DEFAULT 0 AFTER min_dl_rank",
        'alt_requirements' => "ALTER TABLE $table_caps ADD COLUMN alt_requirements text DEFAULT NULL AFTER min_dl_rank_count",
    ];
    foreach ($alter_cols as $col_name => $alter_sql) {
        if (!in_array($col_name, $existing_cols)) {
            $wpdb->query($alter_sql);
        }
    }

    // Default tier rates
    $existing_tier_rates = $wpdb->get_var("SELECT COUNT(*) FROM $table_tier_rates");
    if ($existing_tier_rates == 0) {
        $default_tier_rates = [
            ['tier_num' => 1, 'rate_percent' => 10.00, 'active' => 1],
            ['tier_num' => 2, 'rate_percent' => 5.00,  'active' => 1],
            ['tier_num' => 3, 'rate_percent' => 5.00,  'active' => 1],
            ['tier_num' => 4, 'rate_percent' => 2.50,  'active' => 1],
            ['tier_num' => 5, 'rate_percent' => 2.50,  'active' => 1],
        ];
        foreach ($default_tier_rates as $tr) {
            $wpdb->insert($table_tier_rates, $tr);
        }
    }

    // Default tiers
    $existing_tiers = $wpdb->get_var("SELECT COUNT(*) FROM $table_tiers");
    if ($existing_tiers == 0) {
        $default_tiers = [
            ['tier_number' => 1, 'rate_percent' => 10.00],
            ['tier_number' => 2, 'rate_percent' => 5.00],
            ['tier_number' => 3, 'rate_percent' => 5.00],
            ['tier_number' => 4, 'rate_percent' => 2.50],
            ['tier_number' => 5, 'rate_percent' => 2.50],
        ];
        foreach ($default_tiers as $t) {
            $wpdb->insert($table_tiers, $t);
        }
    }

    // Default rank caps
    $existing_caps = $wpdb->get_var("SELECT COUNT(*) FROM $table_caps");
    if ($existing_caps == 0) {
        $default_caps = [
            ['rank_key' => 'free',     'rank_label' => 'Free',     'max_tier' => 0],
            ['rank_key' => 'starter',  'rank_label' => 'Starter',  'max_tier' => 2],
            ['rank_key' => 'basic',    'rank_label' => 'Basic',    'max_tier' => 2],
            ['rank_key' => 'premium',  'rank_label' => 'Premium',  'max_tier' => 3],
            ['rank_key' => 'prestige', 'rank_label' => 'Prestige', 'max_tier' => 4],
        ];
        foreach ($default_caps as $c) {
            $wpdb->insert($table_caps, $c);
        }
    }



    // Default options
    if (!get_option('crs_max_scan_depth')) {
        update_option('crs_max_scan_depth', 200);
    }
    if (!get_option('crs_memory_limit_mb')) {
        update_option('crs_memory_limit_mb', 256);
    }
    if (!get_option('crs_cron_time')) {
        update_option('crs_cron_time', '23:00');
    }

    crs_register_cron();
}

// ============================================================
// DEACTIVATION
// ============================================================
register_deactivation_hook(__FILE__, 'crs_deactivate');
function crs_deactivate() {
    wp_clear_scheduled_hook('crs_nightly_cron');
}

// ============================================================
// INIT — Hooks
// ============================================================
add_action('init', 'crs_init');
function crs_init() {
    // Load text domain
    load_plugin_textdomain('commission-royalty-system', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// ============================================================
// WooCommerce hooks → includes/hooks.php
// ============================================================

// ============================================================
// Order helpers — used by hooks.php
// ============================================================

/**
 * Get point_level earned from an order.
 * Matches instant-sponsor-commission's get_user_points() logic.
 */
function crs_get_order_point_level($order) {
    $point_type = 'point_level';
    $payout = 0;

    foreach ($order->get_items() as $item) {
        $product_id = absint($item->get_product_id());
        $variation_id = absint($item->get_variation_id());
        $qty = $item->get_quantity();

        // Check if affiliate product
        $is_affiliate_product = get_post_meta(
            $variation_id ? $variation_id : $product_id,
            '_is_affiliate_product',
            true
        );

        if ($is_affiliate_product === 'yes') {
            $order_id = $order->get_id();
            $affiliate_reward_serialized = get_post_meta($order_id, 'poin_level_affiliate', true);
            $affiliate_reward = maybe_unserialize($affiliate_reward_serialized);

            if (is_array($affiliate_reward)) {
                $payout = array_sum($affiliate_reward);
            } else {
                $payout = $affiliate_reward;
            }
            break;
        } else {
            // Get MyCRED reward for product
            $reward_amount = crs_get_product_point_reward($product_id, $variation_id, $point_type);
            if (is_numeric($reward_amount) && $reward_amount > 0) {
                $payout += $reward_amount * $qty;
            }
        }
    }

    return $payout;
}

/**
 * Get MyCRED point reward for a product.
 * Matches mycred_get_woo_product_reward() from existing plugins.
 */
function crs_get_product_point_reward($product_id, $variation_id, $point_type) {
    $pid = $variation_id ? $variation_id : $product_id;

    // Method 1: Check post meta 'mycred_reward'
    $mycred_reward = get_post_meta($pid, 'mycred_reward', true);
    if (is_array($mycred_reward) && isset($mycred_reward[$point_type])) {
        return (float)$mycred_reward[$point_type];
    }

    // Method 2: Check individual meta key
    $meta_key = 'mycred_reward_' . $point_type;
    $reward = get_post_meta($pid, $meta_key, true);
    if (is_numeric($reward)) {
        return (float)$reward;
    }

    // Method 3: Use MyCRED's built-in function if available
    if (function_exists('mycred_get_woo_product_reward')) {
        $reward = mycred_get_woo_product_reward($product_id, $variation_id, $point_type);
        if (is_numeric($reward)) {
            return (float)$reward;
        }
    }

    return 0;
}

// ============================================================
// Logging helper
// ============================================================
function crs_log($message) {
    error_log('[CRS] ' . $message);
}
