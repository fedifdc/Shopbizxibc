<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage the deposit product
 *
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Product_YWF_Deposit' ) ) {
	/**
	 * Class WC_Product_YWF_Deposit
	 */
	class WC_Product_YWF_Deposit extends WC_Product {
		/**
		 * WC_Product_YWF_Deposit constructor.
		 *
		 * @param int|WC_Product $product The id or the object.
		 */
		public function __construct( $product ) {
			parent::__construct( $product );
			$this->product_type = 'ywf_deposit';

		}

		/**
		 * Return the product type
		 *
		 * @return string
		 * @since 1.0.0
		 * @author YITH <plugins@yithemes.com>
		 */
		public function get_type() {
			return 'ywf_deposit';
		}

		/**
		 * The product isn't visible
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_visible() {
			return false;
		}

		/**
		 * The product is downloadable
		 *
		 * @return bool
		 * @since 1.0.1
		 */
		public function is_downloadable() {
			return apply_filters( 'ywdpd_deposit_is_downloable', true, $this );
		}

		/**
		 * The product is virtual
		 *
		 * @return bool
		 * @since 1.0.1
		 */
		public function is_virtual() {
			return apply_filters( 'ywdpd_deposit_is_virtual', true, $this );

		}

		/**
		 * The product isn't taxable
		 *
		 * @param string $context The context.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function get_tax_class( $context = 'view' ) {
			return '';
		}

		/**
		 * The product is always purchasable
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_purchasable() {
			return apply_filters( 'yith_fund_product_is_purchasable', true, $this );
		}

		/**
		 * The product exists
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function exists() {
			return true;
		}

		/**
		 * The add to cart text
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function single_add_to_cart_text() {

			return __( 'Add funds', 'yith-woocommerce-account-funds' );
		}

		/**
		 * Get the product title
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function get_title() {
			return __( 'Deposit funds', 'yith-woocommerce-account-funds' );
		}

		/**
		 * The product isn't taxable
		 *
		 * @return bool
		 */
		public function is_taxable() {
			return false;
		}

		/**
		 * The product is sold individually
		 *
		 * @return bool
		 */
		public function is_sold_individually() {
			return true;
		}

		/**
		 * Get taxt status
		 *
		 * @param string $context The context.
		 *
		 * @return string
		 */
		public function get_tax_status( $context = 'view' ) {

			return '';
		}

	}
}
