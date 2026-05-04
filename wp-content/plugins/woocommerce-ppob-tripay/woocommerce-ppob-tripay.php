<?php
/*
Plugin Name: WooCommerce PPOB Tripay
Description: Integrasi layanan PPOB dengan Tripay di WooCommerce.
Version: 1.0
Author: Novan
*/
if (!defined('ABSPATH')) exit;
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ppob-tripay-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ppob-tripay-cart.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ppob-tripay-shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ppob-tripay-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ppob-tripay-checkout.php';
require_once plugin_dir_path(__FILE__) . 'functions/functions.php';


function ppob_tripay_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_references = $wpdb->prefix . 'tripay_references';

    $sql = "
    CREATE TABLE IF NOT EXISTS $table_references (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        product_id INT UNSIGNED NOT NULL,
        category_id BIGINT UNSIGNED NOT NULL,
        operator_id BIGINT UNSIGNED NOT NULL,
        code VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
        name VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
        price INT UNSIGNED NOT NULL,
        markup INT NOT NULL DEFAULT '0',
        point_level INT NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB $charset_collate;
    ";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $wpdb->query($sql);
}

function ppob_tripay_copy_assets()
{
    $source = plugin_dir_path(__FILE__) . 'assets/ppob-tripay';
    $destination = wp_upload_dir()['basedir'] . '/ppob-tripay';

    if (!file_exists($destination)) {
        wp_mkdir_p($destination);
    }

    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file !== '.' && $file !== '..') {
            copy("$source/$file", "$destination/$file");
        }
    }
    closedir($dir);
}

register_activation_hook(__FILE__, 'ppob_tripay_create_tables');
register_activation_hook(__FILE__, 'ppob_tripay_copy_assets');

register_activation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

add_action('plugins_loaded', function () {

    $tripay_api = new PPOB_Tripay_API();
    new PPOB_Tripay_Shortcodes();
    new PPOB_Tripay_Admin($tripay_api);
});

add_action('init', function () {
    // Add endpoint for categories
    add_rewrite_rule('^ppob-categories/?$', 'index.php?ppob-categories=1', 'top');

    // Fix PPOB detail rewrite rule
    add_rewrite_rule('^ppob-detail/?$', 'index.php?ppob-detail=1', 'top');

    flush_rewrite_rules(); // Ensure rules are registered
});

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'ppob-categories';
    $query_vars[] = 'ppob-detail';
    $query_vars[] = 'category_id'; // Ensure category_id is still registered
    $query_vars[] = 'type';
    return $query_vars;
});

add_action('template_redirect', function () {
    global $wp_query;
    $is_member = get_user_meta(get_current_user_id(), 'shopbiz_user_id', true);
    if (isset($wp_query->query_vars['ppob-categories']) && !is_null($wp_query->query_vars['ppob-categories'])) {
        
         if($is_member){
            include plugin_dir_path(__FILE__) . 'templates/ppob-categories.php';
            exit;
         }else{
             //go to home
            wp_redirect(home_url());
            exit();
         }
        
    }

    if (isset($wp_query->query_vars['ppob-detail']) && !is_null($wp_query->query_vars['ppob-detail'])) {
         if($is_member){
            include plugin_dir_path(__FILE__) . 'templates/ppob-detail.php';
            exit;
         }else{
             //go to home
            wp_redirect(home_url());
             exit();
         }
        
       
    }
});
