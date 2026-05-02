<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage the compatibility with YITH Customize My Account page
 *
 * @package YITH\AccountFunds\Classes\Compatibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'YITH_Funds_YITH_Customize_Account_Compatibility' ) ) {
	/**
	 * Class YITH_Funds_YITH_Customize_Account_Compatibility
	 */
	class YITH_Funds_YITH_Customize_Account_Compatibility {
		/**
		 * The instance of the class
		 *
		 * @var YITH_Funds_YITH_Customize_Account_Compatibility
		 */
		protected static $instance;

		/**
		 * YITH_Funds_YITH_Customize_Account_Compatibility constructor.
		 */
		public function __construct() {

			global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			remove_action( 'init', array( $YITH_FUNDS, 'add_funds_endpoints' ), 10 ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_action( 'init', array( $YITH_FUNDS, 'rewrite_rules' ), 20 );// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_action( 'template_redirect', array( $YITH_FUNDS, 'show_funds_endpoint' ), 100 );// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_filter( 'the_content', array( $YITH_FUNDS, 'show_endpoints_content' ), 20 );// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_action( 'woocommerce_before_my_account', array( $YITH_FUNDS, 'show_customer_funds' ) );// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_action( 'woocommerce_before_my_account', array( $YITH_FUNDS, 'show_customer_make_deposit_form' ), 20 );// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			remove_action( 'woocommerce_before_my_account', array( $YITH_FUNDS, 'show_customer_recent_history' ), 30 );// phpcs:ignore WordPress.NamingConventions.ValidVariableName

			add_filter( 'ywf_get_endpoint_url', array( $this, 'get_endpoint_url' ), 10, 3 );
			add_filter( 'ywf_make_deposit_slug', array( $this, 'get_make_deposit_slug' ) );
		}

		/**
		 * Get current endpoint url
		 *
		 * @param string $url The endpoint url.
		 * @param string $type The endpoint type.
		 * @param array  $args The arguments.
		 *
		 * @return string
		 * @author YITH <plugins@yithemes.com>
		 * @since 1.0.6
		 */
		public function get_endpoint_url( $url, $type, $args ) {

			$id = 'make_a_deposit' === $type ? 'make_a_deposit' : 'view_history';

			$slug = get_option( 'woocommerce_myaccount_' . $id . '_endpoint' );

			if ( count( $args ) > 0 ) {
				$url = esc_url( add_query_arg( $args, wc_get_page_permalink( 'myaccount' ) . $slug ) );
			} else {
				$url = esc_url( wc_get_page_permalink( 'myaccount' ) . $slug );
			}

			return $url;
		}

		/**
		 * Create or return the instance
		 *
		 * @return YITH_Funds_YITH_Customize_Account_Compatibility
		 * @since 1.0.6
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get make a deposit slug
		 *
		 * @param string $slug The endpoint slug.
		 *
		 * @return string
		 * @since 1.0.6
		 */
		public function get_make_deposit_slug( $slug ) {

			$slug = get_option( 'woocommerce_myaccount_make_a_deposit_endpoint', $slug );

			return $slug;
		}
	}
}
/**
 * Return the instance
 *
 * @return YITH_Funds_YITH_Customize_Account_Compatibility
 */
function YITH_Funds_YITH_Customize_Account_Compatibility() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return YITH_Funds_YITH_Customize_Account_Compatibility::get_instance();
}
