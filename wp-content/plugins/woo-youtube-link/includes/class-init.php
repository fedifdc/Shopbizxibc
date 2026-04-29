<?php
namespace WooYoutubeLink;

if (!defined('ABSPATH')) {
    exit;
}

class Init {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->includes();
        $this->hooks();
    }

    private function includes() {
        require_once plugin_dir_path(__FILE__) . 'class-admin.php';
        require_once plugin_dir_path(__FILE__) . 'class-dokan.php';
        require_once plugin_dir_path(__FILE__) . 'class-frontend.php';
        require_once plugin_dir_path(__FILE__) . 'class-assets.php';
    }

    private function hooks() {
        Admin::instance();
        Dokan::instance();
        Frontend::instance();
        Assets::instance();
    }
}
