<?php
/**
 * Plugin Name: WhatsApp Settings
 * Description: Adds a WhatsApp API key field to the settings and integrates OTP verification with registration.
 * Version: 1.0
 * Author: Novan NP
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Autoload classes
require_once plugin_dir_path(__FILE__) . 'includes/class-settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-registration.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-otp-verification.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-login.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-user-wp.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cepatlakoo-add-ons.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-withdrawl-request.php';
require_once plugin_dir_path(__FILE__) . 'functions/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-whatsapp-registration-api.php';

// Activation hook to create a custom table
// Activation hook to create a custom table
register_activation_hook(__FILE__, 'whatsapp_create_custom_table');

function whatsapp_create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'whatsapp_verification'; // Table name
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT(20) UNSIGNED NULL,
        `phone_number` VARCHAR(15) NOT NULL,
        `otp` VARCHAR(255) NOT NULL,
        `status` ENUM('pending', 'verified') DEFAULT 'pending',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `expires_at` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// Initialize plugin
add_action('plugins_loaded', function () {
    new WhatsApp_Settings_Page();
    new WhatsApp_Registration();
  	new WhatsApp_Login();
    new WhatsApp_OTP_Verification();
  	new WhatsApp_User_WP();
    $api = new WhatsApp_Registration_API();
    $api->run();
});