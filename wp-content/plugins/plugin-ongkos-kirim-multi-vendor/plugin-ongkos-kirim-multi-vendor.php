<?php
/**
 * Plugin Name:     Plugin Ongkos Kirim - Multi Vendor Addon
 * Plugin URI:      https://tonjoostudio.com/addons/woo-ongkir-multi-vendor/
 * Description:     Add multiple vendor capability for Plugin Ongkos Kirim
 * Version:         1.3.0
 * Author:          Tonjoo Studio
 * Author URI:      https://tonjoostudio.com
 * License:         GPL
 * Text Domain:     pokmv
 * Domain Path:     /languages
 * WC requires at least: 3.0
 * WC tested up to: 9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// constants.
define( 'POKMV_VERSION', '1.3.0' );
define( 'POKMV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'POKMV_PLUGIN_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );

// load files.
require_once POKMV_PLUGIN_PATH . 'functions.php';
require_once POKMV_PLUGIN_PATH . 'libs/tonjoo-license/tonjoo-license-manager.php';
require_once POKMV_PLUGIN_PATH . 'classes/class-pokmv-vendor-setting.php';
require_once POKMV_PLUGIN_PATH . 'classes/class-pokmv-shipping.php';
require_once POKMV_PLUGIN_PATH . 'classes/class-pokmv-admin.php';

if ( ! class_exists( 'Plugin_Ongkos_Kirim_Multi_Vendor' ) ) {
	/**
	 * Main class
	 */
	class Plugin_Ongkos_Kirim_Multi_Vendor {

		/**
		 * Tonjoo License
		 */
		protected $license;

		/**
		 * Constructor
		 */
		public function __construct() {
			global $tonjoo_license_manager;
			$this->license = $tonjoo_license_manager->register_plugin( 'pok-multi-vendor', __FILE__ );
			add_action( 'init', array( $this, 'on_init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
			add_action( 'before_woocommerce_init', array( $this, 'define_compatibility' ) );
		}

		/**
		 * On plugins loaded
		 */
		public function on_init() {
			load_plugin_textdomain( 'pokmv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			if ( $this->license->is_license_active() ) {
				global $pok_helper;
				if ( pokmv_is_pok_compatible() ) {
					$setting = new POK_Setting();
					if ( 'yes' === $setting->get('enable_multi_vendor') ) { // only enable multi vendor if option activated.
						if ( pokmv_is_multi_vendor_active() ) {
							$platform = pokmv_get_active_multivendor_plugin();
							if ( 'dokan' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-dokan.php';
								new POKMV_Dokan();
							} elseif ( 'wc-marketplace' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-wc-marketplace.php';
								new POKMV_WC_Marketplace();
							} elseif ( 'yith-vendors' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-yith-vendors.php';
								new POKMV_YITH_Vendors();
							} elseif ( 'wc-vendors' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-wc-vendors.php';
								new POKMV_WC_Vendors();
							} elseif ( 'product-vendors' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-product-vendors.php';
								new POKMV_Product_Vendors();
							} elseif ( 'wcfm' === $platform ) {
								require_once POKMV_PLUGIN_PATH . 'classes/integrations/class-pokmv-wcfm.php';
								new POKMV_WCFM();
							}
						} else {
							require_once POKMV_PLUGIN_PATH . 'classes/class-pokmv-taxonomy.php';
							new POKMV_Taxonomy();
						}
						new POKMV_Shipping();
					}
					new POKMV_Admin();
				} else {
					if ( defined( 'POK_VERSION' ) && version_compare( POK_VERSION, '3.8.7', '<' ) ) {
						if ( $pok_helper->is_plugin_active() ) {
							add_action( 'admin_notices', array( $this, 'notice_pok_not_compatible' ) );
						}
					} else {
						add_action( 'admin_notices', array( $this, 'notice_pok_required' ) );
					}
				}
			}
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_scripts() {
			$screen = get_current_screen();
			$allowed_screens = array(
				'user-edit',
			);
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			if ( ! is_null( $screen ) && in_array( $screen->id, $allowed_screens, true ) ) {
				wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array( 'select2' ), POKMV_VERSION );
				wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			}
		}

		/**
		 * Render notice POK not compatible
		 */
		public function notice_pok_not_compatible() {
			?>
			<div class="notice notice-error">
				<p><?php printf( __( 'Multi Vendor addon requires Plugin Ongkos Kirim at least version %s, please update your Plugin Ongkos Kirim before using this addon.', 'pokmv' ), '3.8.7' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Render notice POK required
		 */
		public function notice_pok_required() {
			?>
			<div class="notice notice-error">
				<p><?php esc_html_e( 'Multi Vendor addon requires Plugin Ongkos Kirim to be installed and active.', 'pokmv' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Define compatibility/incompatibility with WooCommerce features.
		 */
		public function define_compatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
		        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		    }
		}

	}
	new Plugin_Ongkos_Kirim_Multi_Vendor();
}
