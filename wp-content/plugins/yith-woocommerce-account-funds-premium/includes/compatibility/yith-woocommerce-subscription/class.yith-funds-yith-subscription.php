<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage the compatibility with YITH Subscription Plugin
 *
 * @package YITH\AccountFunds\Classes\Compatibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Compatibility class for WooCommerce Gateway Stripe.
 *
 * @extends YITH_Funds_YITH_Subscription
 */
class YITH_Funds_YITH_Subscription extends WC_Gateway_YITH_Funds {

	/**
	 * Instance of YITH_WC_Subscription_WC_Stripe
	 *
	 * @var YITH_Funds_YITH_Subscription
	 */
	protected static $instance;

	/**
	 * Stripe gateway id
	 *
	 * @var string ID of specific gateway
	 * @since 1.0
	 */
	public static $gateway_id = 'yith_funds';

	/**
	 * Return the instance of Gateway
	 *
	 * @return YITH_Funds_YITH_Subscription
	 * @author YITH <plugins@yithemes.com>
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this::$gateway_id = $this->id;

		$this->supports = array(
			'products',
			'yith_subscriptions',
			'yith_subscriptions_scheduling',
			'yith_subscriptions_pause',
			'yith_subscriptions_multiple',
			'yith_subscriptions_payment_date',
			'yith_subscriptions_recurring_amount',
		);

		add_action( 'ywsbs_subscription_payment_complete', array( $this, 'add_payment_meta_data_to_subscription' ), 10, 2 );
		// Pay the renew orders.
		add_action( 'ywsbs_pay_renew_order_with_' . $this->id, array( $this, 'pay_renew_order' ), 10, 2 );
		add_filter( 'ywf_is_available_fund_gateway', array( $this, 'check_if_gateway_is_available' ), 10, 3 );
	}


	/**
	 * Register the payment information on subscription meta.
	 *
	 * @param YWSBS_Subscription $subscription The subscription.
	 * @param WC_Order           $order The order.
	 */
	public function add_payment_meta_data_to_subscription( $subscription, $order ) {

		if ( ! $subscription || ! $order || intval( $subscription->get( 'order_id' ) ) !== intval( $order->get_id() ) || $subscription->get_payment_method() !== $this->id ) {
			return;
		}
	}

	/**
	 * Pay the renew order.
	 *
	 * It is triggered by ywsbs_pay_renew_order_with_{gateway_id} action
	 *
	 * @param WC_Order $order The order.
	 * @param bool     $manually Check if the renew is manually.
	 *
	 * @return array|bool|WP_Error
	 * @throws Error\Api The error.
	 * @since 1.1.0
	 */
	public function pay_renew_order( $order = null, $manually = false ) {

		if ( is_null( $order ) ) {
			return false;
		}

		$user_id     = $order->get_user_id();
		$customer    = new YITH_YWF_Customer( $user_id );
		$funds       = apply_filters( 'yith_show_available_funds', $customer->get_funds() );
		$order_total = $order->get_total();

		$subscriptions = $order->get_meta( 'subscriptions' );

		if ( ! $subscriptions ) {
			$message = __( 'Payment error:', 'yith-woocommerce-account-funds' ) . ' ' . __( 'Subscriptions not found.', 'yith-woocommerce-account-funds' );

			return false;
		}

		$subscription_id = $subscriptions ? $subscriptions[0] : false;

		if ( $funds < $order_total ) {
			$message = __( 'Payment error:', 'yith-woocommerce-account-funds' ) . ' ' . __( 'Insufficient account balance', 'yith-woocommerce-account-funds' );
			ywsbs_register_failed_payment( $order, $message );

			return false;
		}

		$order_total_base_currency = apply_filters( 'yith_admin_order_total', $order_total, $order->get_id() );
		$meta_data_update          = array(
			'_order_funds'        => $order_total_base_currency,
			'_order_fund_removed' => 'no',
		);

		foreach ( $meta_data_update as $meta_key => $meta_value ) {
			$order->update_meta_data( $meta_key, $meta_value );
		}
		$order->save();
		$order->payment_complete();

	}

	/**
	 * Check if fund gateway is available
	 *
	 * @param bool  $is_available Is available.
	 * @param float $funds The user funds.
	 * @param int   $user_id The user id.
	 *
	 * @return bool
	 */
	public function check_if_gateway_is_available( $is_available, $funds, $user_id ) {

		if ( $is_available && ! is_null( WC()->cart ) && ( is_callable( 'YWSBS_Subscription_Cart::cart_has_subscriptions' ) ? YWSBS_Subscription_Cart::cart_has_subscriptions() : YITH_WC_Subscription()->cart_has_subscriptions() ) && $funds < WC()->cart->total ) {
			$is_available = false;
		}

		return $is_available;
	}
}
