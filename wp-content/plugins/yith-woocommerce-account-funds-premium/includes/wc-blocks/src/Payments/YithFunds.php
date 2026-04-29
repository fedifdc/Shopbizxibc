<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;


final class YithFunds extends AbstractPaymentMethodType {
	/**
	 * Payment method name/id/slug (matches id in WC_Gateway_BACS in core).
	 *
	 * @var string
	 */
	protected $name = 'yith_funds';

	/**
	 * The gateway instance.
	 *
	 * @var WC_Gateway_YITH_Funds
	 */
	private $gateway;


	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->gateway                 = new WC_Gateway_YITH_Funds();
		$this->settings                = get_option( 'woocommerce_yith_funds_settings', [] );
		$this->settings['description'] = $this->gateway->description;
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return filter_var( $this->get_setting( 'enabled', false ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$scripts = array();

			$deps    = include YITH_FUNDS_DIR . 'assets/js/wc-blocks/payment-method/index.asset.php';
			$scripts = array(
				'yith-gateway-funds-block'
			);

			wp_register_script(
				'yith-gateway-funds-block',
				YITH_FUNDS_ASSETS_URL . 'js/wc-blocks/payment-method/index.js',
				$deps['dependencies'],
				$deps['version'],
				true
			);

			if ( ywf_enable_discount() ) {
				$gateway_args = array(
					'coupon_code'  => YITH_YWF_Cart_Process()->generate_coupon_code(),
					'coupon_note'  => __( 'Discount applied for having used your account funds!', 'yith-woocommerce-account-funds' ),
					'coupon_label' => __( 'Discount for funds used', 'yith-woocommerce-account-funds' )
				);
				wp_localize_script( 'yith-gateway-funds-block', 'yith_gateway_funds_args', $gateway_args );
			}
			$partial_payments_args = $this->get_add_partial_payments_args();
			if ( $partial_payments_args ) {

				$scripts[] = 'yith-partial-payments-block';
				$deps      = include YITH_FUNDS_DIR . 'assets/js/wc-blocks/partial-payments/index.asset.php';
				wp_register_script(
					'yith-partial-payments-block',
					YITH_FUNDS_ASSETS_URL . 'js/wc-blocks/partial-payments/index.js',
					$deps['dependencies'],
					$deps['version'],
					true
				);

				wp_localize_script( 'yith-partial-payments-block', 'ywf_partial_payments', $partial_payments_args );
				wp_enqueue_script( 'yith-partial-payments-block' );
			}

			$remove_partial_payments_args = $this->get_remove_partial_payments_args();

			if ( $remove_partial_payments_args ) {
				$scripts[] = 'yith-remove-partial-payments-block';
				$deps      = include YITH_FUNDS_DIR . 'assets/js/wc-blocks/remove-partial-payments/index.asset.php';
				wp_register_script(
					'yith-remove-partial-payments-block',
					YITH_FUNDS_ASSETS_URL . 'js/wc-blocks/remove-partial-payments/index.js',
					$deps['dependencies'],
					$deps['version'],
					true
				);

				wp_localize_script( 'yith-remove-partial-payments-block', 'ywf_remove_partial_payments', $remove_partial_payments_args );
				wp_enqueue_script( 'yith-remove-partial-payments-block' );
			}
		return $scripts;
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return [
			'title'       => $this->get_setting( 'title' ),
			'description' => $this->get_setting( 'description' ),
			'supports'    => $this->get_supported_features(),
		];
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {

		$features = array_filter( $this->gateway->supports, array( $this->gateway, 'supports' ) );

		/**
		 * Filter to control what features are available for each payment gateway.
		 *
		 * @param array $features List of supported features.
		 * @param string $name Gateway name.
		 *
		 * @return array Updated list of supported features.
		 * @example See docs/examples/payment-gateways-features-list.md
		 *
		 * @since 4.4.0
		 *
		 */
		return apply_filters( '__experimental_woocommerce_blocks_payment_gateway_features_list', $features, $this->get_name() );
	}

	/**
	 * Return if is enabled, the partials payments script args
	 *
	 * @return array|false
	 */
	public function get_add_partial_payments_args() {
		$result = false;

		if ( ywf_partial_payment_enabled() && is_user_logged_in() ) {
			$customer_id = get_current_user_id();

			$customer_fund = new YITH_YWF_Customer( $customer_id );
			$funds         = apply_filters( 'yith_show_available_funds', $customer_fund->get_funds() );
			$has_partial   = ! is_null( WC()->session ) ? WC()->session->get( 'ywf_partial_payment', 'no' ) : false;

			if ( $funds > 0 && ! yith_plugin_fw_is_true( $has_partial ) ) {
				/* translators: %s is the amount of funds */
				$order_button_text = apply_filters( 'ywf_order_button_text', _x( 'or use your funds (%s)', 'Use your funds (20$) or Place Order', 'yith-woocommerce-account-funds' ), $funds );
				$order_button_text = sprintf( $order_button_text, wc_price( $funds ) );

				$checkout_url = wc_get_checkout_url();
				$args         = array(
					'partial_payment' => wp_create_nonce( 'ywf-apply-partial-payment' ),
				);
				$checkout_url = esc_url( add_query_arg( $args, $checkout_url ) );
				$result       = array(
					'button_text'        => $order_button_text,
					'callback_url'       => $checkout_url,
					'funds'              => $funds,
					'deposit_product_id' => get_option( '_ywf_deposit_id' ),
				);
			}
		}

		return $result;
	}

	/**
	 * Return if is enabled, the remove partial payments script args
	 *
	 * @return array|false
	 */
	public function get_remove_partial_payments_args() {
		$args      = false;
		$fund_used = ! is_null( WC()->session ) ? WC()->session->get( 'ywf_fund_used', false ) : false;
		if ( ywf_partial_payment_enabled() && is_user_logged_in() && false !== $fund_used ) {
			$callback_url = esc_url( add_query_arg( 'remove_yith_funds', true, get_permalink( is_cart() ? wc_get_page_id( 'cart' ) : wc_get_page_id( 'checkout' ) ) ) );
			$args         = array(
				'label'        => __( 'User funds', 'yith-woocommerce-account-funds' ),
				'funds_used'   => wc_price( $fund_used ),
				'callback_url' => $callback_url,
			);
		}

		return $args;
	}
}
