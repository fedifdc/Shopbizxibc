<?php
namespace WooYoutubeLink;

if (!defined('ABSPATH')) {
    exit;
}

class Assets {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets']);
    }

    public function frontend_assets() {
        wp_enqueue_style('woo-youtube-style', plugin_dir_url(__FILE__) . '../assets/css/style.css');
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
    }
}
