<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage all compatibilities
 *
 * @package YITH\AccountFunds\Classes\Compatibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_FUNDS_Compatibility' ) ) {
	/**
	 * Class YITH_FUNDS_Compatibility
	 */
	class YITH_FUNDS_Compatibility {
		/**
		 * The instance
		 *
		 * @var YITH_FUNDS_Compatibility
		 */
		protected static $instance;

		/**
		 * YITH_FUNDS_Compatibility constructor.
		 */
		public function __construct() {
			// YITH PDF INVOICE.
			if ( defined( 'YITH_YWPI_INIT' ) ) {
				require_once 'class.yith-funds-yith-pdf-invoice-compatibility.php';
				YITH_Funds_YITH_PDF_Invoice_Compatibility();
			}

			// YITH CUSTOMIZE MY ACCOUNT.
			if ( defined( 'YITH_WCMAP_PREMIUM' ) ) {
				require_once 'class.yith-funds-yith-customize-account.php';
				YITH_Funds_YITH_Customize_Account_Compatibility();
			}

			/** Aelia Currency Switcher */
			if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
				require_once 'class.yith-funds-aelia-currency-switcher.php';
			}

			/**WooCommerce MultiLingual*/
			if ( class_exists( 'woocommerce_wpml' ) ) {
				require_once 'class.yith-funds-wcml.php';
			}
			/**YITH MultiVendor*/
			if ( defined( 'YITH_WPV_PREMIUM' ) ) {
				require_once 'yith-woocommerce-multi-vendor/class.yith-funds-multi-vendor.php';
			}
		}

		/**
		 * The unique access of the class
		 *
		 * @return YITH_FUNDS_Compatibility
		 * @since 1.0.0
		 * @author YITH <plugins@yithemes.com>
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

/**
 * Return the class
 *
 * @return YITH_FUNDS_Compatibility
 */
function YITH_FUNDS_Compatibility() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

	return YITH_FUNDS_Compatibility::get_instance();
}
