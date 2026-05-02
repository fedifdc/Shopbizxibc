<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the class that manage gateway
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Gateway_YITH_Funds' ) ) {
	/**
	 * Class WC_Gateway_YITH_Funds
	 */
	class WC_Gateway_YITH_Funds extends WC_Payment_Gateway {
		/**
		 * WC_Gateway_YITH_Funds constructor.
		 */
		public function __construct() {
			$this->id                 = 'yith_funds';
			$this->icon               = '';
			$this->has_fields         = false;
			$this->method_title       = __( 'YITH Funds', 'yith-woocommerce-account-funds' );
			$this->method_description = __( 'Allow payments with credits (use the available funds)', 'yith-woocommerce-account-funds' );
			$this->supports           = array(
				'refunds',
				'products',
			);
			$this->order_button_text  = $this->get_order_button_text();
			$this->init_form_fields();
			$this->init_settings();

			$this->title = $this->get_option( 'title' );

			$user_id = get_current_user_id();

			if ( $user_id ) {
				$customer = new YITH_YWF_Customer( $user_id );

				$funds = apply_filters( 'yith_show_available_funds', $customer->get_funds() );

				$message = sprintf( '%s %s.', __( 'Available funds', 'yith-woocommerce-account-funds' ), wc_price( $funds ) );

				if ( ywf_enable_discount() ) {

					$discount_type  = ywf_get_discount_type();
					$discount_value = apply_filters( 'yith_discount_value', ywf_get_discount_value(), $discount_type );

					if ( 'fixed_cart' === $discount_type ) {
						$discount_value = wc_price( $discount_value );
					} else {
						$discount_value = wc_format_localized_decimal( $discount_value );

						$discount_value .= '%';
					}
					$message .= '<br/>';
					/* translators: %s is the amount of discount */
					$message .= sprintf( _x( 'If you choose to pay through your available funds and funds are enough to cover the whole order amount, you can get a %s discount', 'If you choose to pay through your available funds and funds are enough to cover the whole order amount, you can get a 30% discount', 'yith-woocommerce-account-funds' ), $discount_value );
				}
				$this->description = apply_filters( 'ywf_get_gateway_description', $message );
			}

			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				array(
					$this,
					'process_admin_options',
				)
			);
		}

		/**
		 * Process payment
		 *
		 * @param int $order_id The order id.
		 *
		 * @return array
		 */
		public function process_payment( $order_id ) {

			if ( ! is_user_logged_in() ) {
				wc_add_notice( __( 'Payment error:', 'yith-woocommerce-account-funds' ) . ' ' . __( 'You must be logged in to use this payment method', 'yith-woocommerce-account-funds' ), 'error' );

				return;
			}
			if ( YITH_YWF_Deposit_Fund_Checkout()->is_deposit_in_cart() ) {
				wc_add_notice( __( 'Payment error:', 'yith-woocommerce-account-funds' ) ) . ' ' . __( 'You can\'t purchase Funds with your wallet', 'yith-woocommmerce-account-funds' );
				WC()->cart->empty_cart( true );
				yith_account_funds_clear_session();

				return;
			}
			$order       = wc_get_order( $order_id );
			$user_id     = $order->get_user_id();
			$customer    = new YITH_YWF_Customer( $user_id );
			$funds       = apply_filters( 'yith_show_available_funds', $customer->get_funds() );
			$order_total = $order->get_total( 'edit' );

			if ( $funds < $order_total ) {
				wc_add_notice( __( 'Payment error:', 'yith-woocommerce-account-funds' ) . ' ' . __( 'Insufficient account balance', 'yith-woocommerce-account-funds' ), 'error' );

				return;
			} else {

				$order_total_base_currency = apply_filters( 'yith_admin_order_total', $order_total, $order_id );
				$meta_data_update          = array(
					'_order_funds'        => $order_total_base_currency,
					'_order_fund_removed' => 'no',
				);

				foreach ( $meta_data_update as $meta_key => $meta_value ) {
					$order->update_meta_data( $meta_key, $meta_value );
				}

				$order->set_total( 0 );
				$order->save();

				$order->payment_complete();

				WC()->cart->empty_cart();

				// Return thankyou redirect.
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}
		}

		/**
		 * Process the refund
		 *
		 * @param int        $order_id The order id.
		 * @param null|float $amount The amount to refund.
		 * @param string     $reason The reason.
		 *
		 * @return bool
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {

			try {

				$order          = wc_get_order( $order_id );
				$funds_used     = $order->get_meta( '_order_funds' );
				$funds_refunded = $order->get_meta( '_order_funds_refunded' );
				$funds_refunded = empty( $funds_refunded ) ? 0 : $funds_refunded;

				$customer             = new YITH_YWF_Customer( $order->get_user_id() );
				$amount_base_currency = apply_filters( 'yith_refund_amount_base_currency', $amount, $order_id );

				$customer->add_funds( $amount_base_currency );

				$funds_refunded = wc_format_decimal( $funds_refunded + $amount_base_currency, wc_get_price_decimals() );
				$order->update_meta_data( '_order_funds_refunded', $funds_refunded );
				$order->save();
				/* translators: %1$s is the the amount funds, %2$s is the customer id */
				$order_note = sprintf( __( 'Add %1$s funds to customer #%2$s', 'yith-woocommerce-account-funds' ), wc_price( $amount, array( 'currency' => get_post_meta( $order_id, '_order_currency', true ) ) ), $order->get_user_id() );
				$order->add_order_note( $order_note );

				$default = array(
					'user_id'        => $order->get_user_id(),
					'order_id'       => $order_id,
					'fund_user'      => $amount_base_currency,
					'type_operation' => 'restore',
					'description'    => $reason,
				);
				do_action( 'ywf_add_user_log', $default );

				return true;

			} catch ( Exception $e ) {
				new WP_Error( 'refund_order_funds', $e->getMessage() );
			}
		}

		/**
		 * Check if this gateway is available
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_available() {

			$is_available = false;
			$user_id      = get_current_user_id();

			if ( $user_id ) {
				$is_available = ( 'yes' === $this->enabled );
				$customer     = new YITH_YWF_Customer( $user_id );
				$funds        = apply_filters( 'yith_show_available_funds', $customer->get_funds() );

				if ( $funds <= 0 ) {
					$is_available = false;
				}

				$cart_condition = false;

				if ( ! is_null( WC()->cart ) ) {
					$is_available = $funds < WC()->cart->get_total( 'edit' ) ? false : true;
				}
				$pay_order_condition = false;
				if ( 0 < get_query_var( 'order-pay' ) ) {
					$order               = wc_get_order( get_query_var( 'order-pay' ) );
					$pay_order_condition = $funds < $order->get_total();
				}
				if ( $cart_condition || $pay_order_condition ) {
					$is_available = false;
				}

				if ( ! is_null( WC()->session ) && 'yes' === WC()->session->get( 'ywf_partial_payment', 'no' ) ) {
					$is_available = false;
				}

				$is_available = apply_filters( 'ywf_is_available_fund_gateway', $is_available, $funds, $user_id );

			}

			return $is_available;
		}

		/**
		 * Init the form fields
		 *
		 * @since 1.0.0
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'yith-woocommerce-account-funds' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable customers to use their funds as a payment gateway', 'yith-woocommerce-account-funds' ),
					'default' => 'yes',
				),
				'title'   => array(
					'title'       => __( 'Title', 'woocommerce' ),
					'type'        => 'text',
					'description' => __( 'This is the title that users see during the checkout.', 'yith-woocommerce-account-funds' ),
					'default'     => __( 'Funds', 'yith-woocommerce-account-funds' ),
					'desc_tip'    => true,
				),

			);
		}

		/**
		 * Get the button text
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function get_order_button_text() {

			$user_id  = get_current_user_id();
			$customer = new YITH_YWF_Customer( $user_id );
			$funds    = $customer->get_funds();
			if ( ywf_partial_payment_enabled() && ! is_null( WC()->cart ) && $funds < WC()->cart->total ) {

				return apply_filters( 'ywf_order_button_text', __( 'Use your funds', 'yith-woocommerce-account-funds' ) );
			}

			return '';
		}


	}
}
