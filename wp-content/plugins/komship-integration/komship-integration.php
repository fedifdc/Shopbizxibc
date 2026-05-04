<?php
/**
 * Plugin Name: Komship Integration
 * Description: A minimalist WooCommerce integration with Komship for order shipment and tracking.
 * Version: 1.0
 * Author: Novan.NP
 * Text Domain: komship-integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('KOMSHIP_INTEGRATION_VERSION', '1.0.0');
define('KOMSHIP_INTEGRATION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('KOMSHIP_INTEGRATION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once KOMSHIP_INTEGRATION_PLUGIN_PATH . 'includes/class-komship-settings.php';
require_once KOMSHIP_INTEGRATION_PLUGIN_PATH . 'includes/class-komship-api.php';
require_once KOMSHIP_INTEGRATION_PLUGIN_PATH . 'includes/class-komship-order-expedition.php';

// Initialize plugin components
class Komship_Integration_Plugin {
    private static $instance = null;
    private $settings;
    private $api;
    private $order_expedition;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_init', array($this, 'check_woocommerce_dependency'));
        
        // Only initialize if WooCommerce is active
        if ($this->is_woocommerce_active()) {
            $this->init();
        }
    }

    private function init() {
        // Initialize Settings
        $this->settings = new Komship_Settings();
        add_action('admin_menu', array($this->settings, 'add_settings_page'));
        add_action('admin_init', array($this->settings, 'register_settings'));

        // Initialize API
        $this->api = new Komship_API();
        error_log("PLUGIN KOMSHIP INITIALIZED");
        // Initialize Order Expedition
        $this->order_expedition = new Komship_Order_Expedition();
        
        // Register assets
        add_action('admin_enqueue_scripts', array($this, 'register_assets'));
    }

    public function register_assets() {
        // Register CSS
        wp_register_style(
            'komship-admin',
            KOMSHIP_INTEGRATION_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            KOMSHIP_INTEGRATION_VERSION
        );

        // Register JavaScript
        wp_register_script(
            'komship-admin',
            KOMSHIP_INTEGRATION_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'jquery-ui-autocomplete'),
            KOMSHIP_INTEGRATION_VERSION,
            true
        );

        // Localize script
        wp_localize_script('komship-admin', 'komshipData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('komship_nonce'),
        ));
    }

    public function is_woocommerce_active() {
        return in_array(
            'woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins'))
        );
    }

    public function check_woocommerce_dependency() {
        if (!$this->is_woocommerce_active()) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
        }
    }

    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('Komship Integration requires WooCommerce to be installed and active.', 'komship-integration'); ?></p>
        </div>
        <?php
    }
}

// Initialize the plugin
function komship_integration() {
    return Komship_Integration_Plugin::get_instance();
// 	new Komship_Integration_Plugin();
}

// Start the plugin
add_action('plugins_loaded', 'komship_integration');