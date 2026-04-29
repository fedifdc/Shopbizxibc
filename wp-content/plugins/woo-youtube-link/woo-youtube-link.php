<?php
/**
 * Plugin Name: Woo Marketing Link
 * Description: Tambahkan input link Marketing di produk WooCommerce dan Dokan.
 * Version: 1.0.0
 * Author: Ruli Dev
 * Text Domain: woo-youtube-link
 */

if (!defined('ABSPATH')) {
    exit;
}

// Autoload semua class
require_once plugin_dir_path(__FILE__) . 'includes/class-init.php';

// Jalankan plugin
add_action('plugins_loaded', function() {
    \WooYoutubeLink\Init::instance();
});
