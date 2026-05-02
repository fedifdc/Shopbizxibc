<?php
/**
 * Plugin Name: YITH WooCommerce Account Funds Premium
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-account-funds/
 * Description: The plugin <code><strong>YITH WooCommerce Account Funds Premium</strong></code> gives your customers the possibility to deposit funds in your online store now and use them later at any time to proceed with the checkout more quickly. <a href="https://yithemes.com">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.35.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-account-funds
 * Domain Path: /languages/
 * WC requires at least: 8.5
 * WC tested up to: 8.7
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds
 * @version 1.32.1
 */

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'yith_funds_premium_install_woocommerce_admin_notice' ) ) {
	/**
	 * Show a notice if WooCommerce isn't enabled
	 *
	 * @since 1.0.0
	 */
	function yith_funds_premium_install_woocommerce_admin_notice() {
		?>
        <div class="error">
            <p><?php esc_html_e( 'YITH WooCommerce Account Funds Premium is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-account-funds' ); ?></p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! function_exists( 'yith_plugin_onboarding_registration_hook' ) ) {
	include_once 'plugin-upgrade/functions-yith-licence.php';
}
register_activation_hook( __FILE__, 'yith_plugin_onboarding_registration_hook' );

// endregion.

if ( ! defined( 'YITH_FUNDS_VERSION' ) ) {
	define( 'YITH_FUNDS_VERSION', '1.35.0' );
}
if ( ! defined( 'YITH_FUNDS_PREMIUM' ) ) {
	define( 'YITH_FUNDS_PREMIUM', '1' );
}
if ( ! defined( 'YITH_FUNDS_INIT' ) ) {
	define( 'YITH_FUNDS_INIT', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'YITH_FUNDS_FILE' ) ) {
	define( 'YITH_FUNDS_FILE', __FILE__ );
}

if ( ! defined( 'YITH_FUNDS_DIR' ) ) {
	define( 'YITH_FUNDS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_FUNDS_URL' ) ) {
	define( 'YITH_FUNDS_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YITH_FUNDS_ASSETS_URL' ) ) {
	define( 'YITH_FUNDS_ASSETS_URL', YITH_FUNDS_URL . 'assets/' );
}

if ( ! defined( 'YITH_FUNDS_TEMPLATE_PATH' ) ) {
	define( 'YITH_FUNDS_TEMPLATE_PATH', YITH_FUNDS_DIR . 'templates/' );
}

if ( ! defined( 'YITH_FUNDS_INC' ) ) {
	define( 'YITH_FUNDS_INC', YITH_FUNDS_DIR . 'includes/' );
}


if ( ! defined( 'YITH_FUNDS_SLUG' ) ) {
	define( 'YITH_FUNDS_SLUG', 'yith-woocommerce-account-funds' );
}
if ( ! defined( 'YITH_FUNDS_SECRET_KEY' ) ) {
	define( 'YITH_FUNDS_SECRET_KEY', 'GR9hNWa33oxCPASjpAdp' );
}

if ( ! defined( 'YITH_FUNDS_DB_VERSION' ) ) {
	define( 'YITH_FUNDS_DB_VERSION', '1.0.1' );
}

// endregion.

// create log user funds table.

if ( ! class_exists( 'YITH_YWF_Log_Manager' ) ) {
	require_once YITH_FUNDS_INC . '/class.yith-ywf-log-manager.php';
	$log_manager = YWF_Log();
}
register_activation_hook( __FILE__, array( $log_manager, 'install' ) );

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_FUNDS_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_FUNDS_DIR . 'plugin-fw/init.php';
}

yit_maybe_plugin_fw_loader( YITH_FUNDS_DIR );

if ( ! function_exists( 'yith_funds_install' ) ) {
	/**
	 * Install the plugin
	 *
	 * @since 1.0.0
	 */
	function yith_funds_install() {

		if ( ! function_exists( 'WC' ) ) {

			add_action( 'admin_notices', 'yith_funds_premium_install_woocommerce_admin_notice' );
		} else {
			require_once YITH_FUNDS_INC . '/functions.yith-ywf-wc-blocks.php';
			add_action( 'before_woocommerce_init', 'yith_funds_add_support_hpos_system' );
			do_action( 'yith_funds_init' );
		}
	}
}
add_action( 'plugins_loaded', 'yith_funds_install', 9 );

if ( ! function_exists( 'yith_funds_add_support_hpos_system' ) ) {
    function yith_funds_add_support_hpos_system() {
	    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {

		    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_FUNDS_INIT );
		    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', YITH_FUNDS_INIT );
		    if ( ! get_option( 'ywf_has_custom_order_tables' ) ) {
			    update_option( 'ywf_has_custom_order_tables', 'yes' );
		    }
	    }
    }
}
if ( ! function_exists( 'yith_funds_init_plugin' ) ) {
	/**
	 * Init the plugin
	 *
	 * @since 1.0.
	 */
	function yith_funds_init_plugin() {

		load_plugin_textdomain( 'yith-woocommerce-account-funds', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once YITH_FUNDS_DIR . '/class.yith-funds.php';
		require_once YITH_FUNDS_INC . '/functions.yith-ywf-functions.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-customer.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-cart-process.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-deposit-fund-checkout.php';
		require_once YITH_FUNDS_INC . '/class.wc-gateway-yith-funds.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-order.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-product-deposit.php';
		require_once YITH_FUNDS_INC . '/shortcodes/class.yith-ywf-shortcodes.php';
		require_once YITH_FUNDS_INC . '/class.yith-ywf-reports.php';
		require_once YITH_FUNDS_INC . '/compatibility/yith-woocommerce-multi-vendor/class.yith-funds-redeem-payouts-gateway.php';
		require_once YITH_FUNDS_INC . '/compatibility/yith-woocommerce-multi-vendor/class.yith-funds-redeem-stripe-connect-gateway.php';
		require_once YITH_FUNDS_INC . '/compatibility/class.yith-funds-compatibility.php';
		require_once YITH_FUNDS_INC . '/class.yith-funds-endpoints.php';

		global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

		$YITH_FUNDS = YITH_Funds::get_instance(); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
	}
}
add_action( 'yith_funds_init', 'yith_funds_init_plugin' );

if ( ! function_exists( 'yith_plugin_fw_wc_is_using_block_template_in_checkout' ) && file_exists( YITH_FUNDS_DIR . 'plugin-fw/yit-woocommerce-compatibility.php' ) ){
	require_once YITH_FUNDS_DIR . 'plugin-fw/yit-woocommerce-compatibility.php';
}