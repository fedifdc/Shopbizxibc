<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the class that manage the checkout process
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Deposit_Fund_Checkout' ) ) {
	/**
	 * Class YITH_YWF_Deposit_Fund_Checkout
	 */
	class YITH_YWF_Deposit_Fund_Checkout {
		/**
		 * The static instance
		 *
		 * @var YITH_YWF_Deposit_Fund_Checkout
		 */
		protected static $instance;

		/**
		 * YITH_YWF_Deposit_Fund_Checkout constructor.
		 */
		public function __construct() {

			add_action( 'wp_loaded', array( $this, 'add_deposit_to_cart' ), 20 );
			add_filter( 'woocommerce_add_cart_item', array( $this, 'set_price_deposit' ), 20, 2 );
			add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'set_price_deposit_from_session' ), 20, 3 );

			add_action( 'woocommerce_restore_cart_item', array( $this, 'set_price_restore_cart_item' ), 20, 2 );

			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'valid_add_to_cart' ), 20, 5 );

			add_filter( 'woocommerce_coupons_enabled', array( $this, 'disable_coupons_for_deposit' ), 99, 1 );

			add_action( 'woocommerce_remove_cart_item', array( $this, 'clear_deposit_session' ), 20, 2 );

			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'available_payment_gateways' ), 20 );
			// check user profile.
			add_action( 'before_make_a_deposit_form', array( $this, 'display_available_user_funds' ), 10 );
			add_action( 'woocommerce_customer_save_address', array( $this, 'redirect_to_make_a_deposit' ) );

			add_filter( 'yith_payouts_register_new_payout', array( $this, 'exclude_funds_deposits' ), 99, 2 );

		}

		/**
		 * Create or return the instance of the class
		 *
		 * @return YITH_YWF_Deposit_Fund_Checkout unique access
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Check if amount is right and set the session
		 *
		 * @since 1.0.0
		 */
		public function validate_amount() {
			$amount = isset( $_REQUEST['amount_deposit'] ) ? wc_format_decimal( wp_unslash( $_REQUEST['amount_deposit'] ) ) : '';   // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( '' === $amount || ! is_numeric( $amount ) ) {
				wc_add_notice( __( 'Enter a price', 'yith-woocommerce-account-funds' ), 'error' );

				return false;
			}
			$amount = floatval( $amount );
			$min    = floatval( wc_format_decimal( ywf_get_min_fund_rechargeable() ) );
			$max    = ywf_get_max_fund_rechargeable();

			if ( $amount < $min ) {
				wc_add_notice( sprintf( '%s %s', __( 'Minimum deposit amount is', 'yith-woocommerce-account-funds' ), wc_price( $min ) ), 'error' );

				return false;
			}

			if ( '' !== $max ) {
				$max = floatval( wc_format_decimal( $max ) );

				if ( $amount > $max ) {
					wc_add_notice( sprintf( '%s %s', __( 'Maximum deposit amount is', 'yith-woocommerce-account-funds' ), wc_price( $max ) ), 'error' );

					return false;
				}
			}

			return $amount;
		}


		/**
		 * Show the user funds
		 *
		 * @since 1.0.0
		 */
		public function display_available_user_funds() {

			echo do_shortcode( '[yith_ywf_show_user_fund]' ); //phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Redirect to a make a deposit endpoint
		 *
		 * @since 1.0.0
		 */
		public function redirect_to_make_a_deposit() {

			$make_deposit_endpoint = apply_filters( 'ywf_make_deposit_slug', 'make-a-deposit' );
			if ( isset( $_GET['return_to'] ) && $make_deposit_endpoint === $_GET['return_to'] ) {  // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$url          = wc_get_page_permalink( 'myaccount' );
				$endpoint_url = esc_url( wc_get_endpoint_url( $make_deposit_endpoint, '', $url ) );

				wp_safe_redirect( $endpoint_url );
				exit;
			}
		}


		/**
		 * Add the fund gateway in array
		 *
		 * @param array $gateways The wc gateways.
		 *
		 * @return array
		 * @since 1.0.8
		 */
		public function available_payment_gateways( $gateways ) {

			if ( ! is_null( WC()->session ) ) {

				if ( false !== WC()->session->get( 'deposit_amount', false ) && ! $this->is_deposit_in_cart() ) {
					WC()->session->set( 'deposit_amount', false );
				}

				if ( WC()->session->get( 'deposit_amount', false ) ) {

					$deposit_payments = get_option( 'ywf_select_gateway' );
					unset( $gateways['yith_funds'] );
					if ( ! empty( $deposit_payments ) ) {

						foreach ( $gateways as $key => $gateway ) {
							if ( ! in_array( $key, $deposit_payments ) ) { //phpcs:ignore
								unset( $gateways[ $key ] );
							}
						}
					}
				}
			}

			return $gateways;
		}

		/**
		 * Add the deposit in cart
		 *
		 * @throws Exception The exception.
		 */
		public function add_deposit_to_cart() {

			if ( isset( $_POST['deposit_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['deposit_nonce'] ) ), 'add_deposit' ) ) {

				$product_id = get_option( '_ywf_deposit_id' );

				WC()->cart->empty_cart( true );

				$amount = $this->validate_amount();

				if ( ! $amount ) {

					wp_safe_redirect( remove_query_arg( array( 'amount_deposit', 'deposit_nonce' ) ) );
					exit;
				}

				$cart_item_data = apply_filters( 'yith_account_funds_deposit_item_data', array( 'amount_deposit' => $amount ) );
				WC()->cart->add_to_cart( $product_id, 1, 0, array(), $cart_item_data );

				WC()->session->set( 'deposit_amount', $amount );
				wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
				exit;
			}
		}

		/**
		 * Validate the add to cart action
		 *
		 * @param bool         $is_valid Is valid.
		 * @param int          $product_id The product id.
		 * @param int          $quantity The quantity.
		 * @param string|int   $variation_id The variation id.
		 * @param string|array $variations The variations array.
		 *
		 * @return bool
		 */
		public function valid_add_to_cart( $is_valid, $product_id, $quantity, $variation_id = '', $variations = '' ) {

			if ( $this->is_deposit_in_cart() ) {

				$product = wc_get_product( $product_id );
				/* translators: %s is the product name */
				$error_message = sprintf( __( 'You cannot add &quot;%s&quot; to the cart because you are depositing funds', 'yith-woocommerce-account-funds' ), $product->get_name() );
				wc_add_notice( $error_message, 'error' );
				$is_valid = false;
			}

			return $is_valid;
		}

		/**
		 * Check if the deposit product is in the cart
		 *
		 * @return bool
		 * @since 1.1.0
		 */
		public function is_deposit_in_cart() {

			if ( did_action('wp_loaded') && isset( WC()->cart ) && ! WC()->cart->is_empty() ) {

				foreach ( WC()->cart->cart_contents as $cart_item ) {

					$product = $cart_item['data'];

					if ( 'ywf_deposit' === $product->get_type() ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Set the price for the deposit product
		 *
		 * @param array  $cart_item_data The cart item data.
		 * @param string $cart_item_key The cart item key.
		 *
		 * @return array
		 */
		public function set_price_deposit( $cart_item_data, $cart_item_key ) {

			if ( isset( $cart_item_data['amount_deposit'] ) ) {

				$deposit_amount = apply_filters( 'yith_fund_deposit_amount_for_session', $cart_item_data['amount_deposit'], $cart_item_data );
				$cart_item_data['data']->set_price( $deposit_amount );
			}

			return $cart_item_data;
		}

		/**
		 * Set the price from session
		 *
		 * @param array  $session_data The session data.
		 * @param array  $values The values.
		 * @param string $key The cart item key.
		 *
		 * @return array
		 */
		public function set_price_deposit_from_session( $session_data, $values, $key ) {

			if ( isset( $session_data['amount_deposit'] ) ) {

				$deposit_amount = apply_filters( 'yith_fund_deposit_amount_for_session', $session_data['amount_deposit'], $session_data );

				$session_data['data']->set_price( $deposit_amount );
			}

			return $session_data;
		}

		/**
		 * Set the price for a product restored
		 *
		 * @param string  $cart_item_key The cart item key.
		 * @param WC_Cart $cart The cart.
		 */
		public function set_price_restore_cart_item( $cart_item_key, $cart ) {

			if ( isset( $cart->cart_contents[ $cart_item_key ]['amount_deposit'] ) ) {
				$amount   = $cart->cart_contents[ $cart_item_key ]['amount_deposit'];
				$currency = get_woocommerce_currency();
				$amount   = apply_filters( 'yith_fund_deposit_amount', $amount, $currency );
				$cart->cart_contents[ $cart_item_key ]['data']->set_price( $amount );

			}
		}

		/**
		 * Delete the session for deposit
		 *
		 * @param string  $cart_item_key The cart item key.
		 * @param WC_Cart $cart The cart.
		 */
		public function clear_deposit_session( $cart_item_key, $cart ) {

			$cart_item = WC()->cart->get_cart_item( $cart_item_key );

			/**
			 * The product
			 *
			 * @var WC_Product $product
			 */
			$product = $cart_item['data'];

			if ( $product instanceof WC_Product && 'ywf_deposit' === $product->get_type() ) {

				WC()->session->set( 'deposit_amount', false );
			}
		}


		/**
		 * Disable the coupon when a deposit product is added in the cart.
		 *
		 * @param bool $is_enabled Is enabled.
		 *
		 * @return bool
		 */
		public function disable_coupons_for_deposit( $is_enabled ) {

			if ( isset( WC()->session ) && WC()->session->get( 'deposit_amount', false ) ) {

				$is_enabled = 'yes' === get_option( 'yith_funds_enable_coupon', 'no' );
			}

			return $is_enabled;
		}

		/**
		 * Integration with Payouts, exclude deposit products
		 *
		 * @param bool $register Register payouts.
		 * @param int  $order_id The order id.
		 *
		 * @return bool
		 */
		public function exclude_funds_deposits( $register, $order_id ) {

			$order = wc_get_order( $order_id );

			if ( 1 === intval( $order->get_item_count() ) ) {
				/**
				 * The item
				 *
				 * @var WC_Order_Item
				 */
				foreach ( $order->get_items() as $item ) {
					$product_id = wc_get_order_item_meta( $item->get_id(), '_product_id', true );

					$product = wc_get_product( $product_id );

					if ( $product->is_type( 'ywf_deposit' ) ) {

						$register = false;
						break;
					}
				}
			}

			return $register;
		}


	}
}
/**
 * Return the instance of class
 *
 * @return YITH_YWF_Deposit_Fund_Checkout
 */
function YITH_YWF_Deposit_Fund_Checkout() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

	return YITH_YWF_Deposit_Fund_Checkout::get_instance();
}
