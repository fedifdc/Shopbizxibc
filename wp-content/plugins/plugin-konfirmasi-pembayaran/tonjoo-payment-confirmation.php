<?php
/**
 * Plugin Name:  Plugin Konfirmasi Pembayaran
 * Description:  Plugin for payment confirmation woocommerce.
 * Version:      2.1.0
 * Author:       Tonjoo Studio
 * Author URI:   http://www.tonjoostudio.com/
 * Plugin URI:   http://www.tonjoostudio.com/product/plugin-konfirmasi-pembayaran
 * License:      GPL
 * Text Domain:  tpc
 * Domain Path:  /languages
 * WC requires at least: 3.0
 * WC tested up to: 8.8
 *
 * @package tpc
 **/

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tonjoo_Payment_Confirmation' ) ) {

	/**
	 * Main Class Tonjoo_Payment_Confirmation
	 */
	class Tonjoo_Payment_Confirmation {

		/**
		 * License handler
		 *
		 * @var object
		 */
		private $license_handler;

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {

			$this->define_constants();
			$this->includes();

			global $tonjoo_license_manager;
			$this->license_handler 	= $tonjoo_license_manager->register_plugin( 'payment-confirmation', __FILE__ );

			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				new TPC_WC_Hooks();
				if ( $this->license_handler->is_license_active() ) {
					new TPC_Form();
				}
			}
			new TPC_Admin( $this->license_handler );

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_add_settings_link' ) );
			add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
			add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility' ) );
		}

		/**
		 * Setup plugin constants.
		 */
		private function define_constants() {
			define( 'TPC_VERSION', '2.1.0' );
			define( 'TPC_URL', plugins_url( '', __FILE__ ) );
			define( 'TPC_PATH', plugin_dir_path( __FILE__ ) );
			define( 'TPC_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );
		}

		/**
		 * Include required files.
		 */
		private function includes() {
			include_once TPC_PATH . 'libs/class-tonjoo-plugins-upsell.php';
			require_once TPC_PATH . 'libs/tonjoo-license/tonjoo-license-manager.php';
			include_once TPC_PATH . 'classes/class-tpc-setting.php';
			include_once TPC_PATH . 'classes/class-tpc-fields.php';
			include_once TPC_PATH . 'classes/class-tpc-form.php';
			// include_once TPC_PATH . 'classes/class-tpc-report-table.php';
			include_once TPC_PATH . 'classes/class-tpc-wc-hooks.php';
			include_once TPC_PATH . 'classes/class-tpc-admin.php';
		}

		/**
		 * Add setting quick link to the plugins list
		 *
		 * @param  array $links Links.
		 * @return array        Links
		 */
		public function plugin_add_settings_link( $links ) {
			$settings_link = '<a href="' . admin_url( 'admin.php?page=tpc_setting' ) . '">' . __( 'Settings', 'tpc' ) . '</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		/**
		 * Load translation
		 */
		public function on_plugins_loaded() {
			load_plugin_textdomain( 'tpc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			$installed_version = get_option( 'tpc_version', '1.1.0' );
			if ( version_compare( $installed_version, TPC_VERSION, '<' ) ) {
				$setting = new TPC_Setting();
				$setting->setting_migration();
			}
			update_option( 'tpc_version', TPC_VERSION, true );
		}

		/**
		 * Declare compatibility with WooCommerce features.
		 */
		public function declare_compatibility() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		    }
		}

	}

	new Tonjoo_Payment_Confirmation();

}
