<?php

require YITH_FUNDS_INC . '/wc-blocks/src/Payments/YithFunds.php';

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;

if ( ! function_exists( 'yith_funds_add_extension_woocommerce_blocks_support' ) ) {
	/**
	 * Extend payment method to WC Checkout Blocks
	 *
	 * @return void
	 * @throws Exception
	 */
	function yith_funds_add_extension_woocommerce_blocks_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function ( PaymentMethodRegistry $payment_method_registry ) {
					$container = Package::container();
					// registers as shared instance.
					$container->register(
						YithFunds::class,
						function () {
							return new YithFunds();
						}
					);
					$payment_method_registry->register(
						$container->get( YithFunds::class )
					);
				}
			);
		}
	}
}

add_action( 'woocommerce_blocks_loaded', 'yith_funds_add_extension_woocommerce_blocks_support' );