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
 * Integration with YITH WooCommerce Subscription
 */
class YITH_Funds_YITH_Subscription_Integration {

	/**
	 * Instance of class
	 *
	 * @var YITH_Funds_YITH_Subscription_Integration
	 */
	protected static $instance;

	/**
	 * Return the instance of class
	 *
	 * @return YITH_Funds_YITH_Subscription_Integration
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
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_funds_integration_gateway' ), 10 );
		add_filter( 'ywsbs_max_failed_attempts_list', array( $this, 'add_failed_attempts' ) );
		add_filter( 'ywsbs_get_num_of_days_between_attemps', array( $this, 'add_num_of_days_between_attempts' ) );
		add_filter( 'ywsbs_from_list', array( $this, 'add_from_list' ) );
		add_filter( 'ywf_operation_type', array( $this, 'add_operation_type' ) );
		add_filter( 'ywf_add_fund_order_log_arguments', array( $this, 'change_add_fund_order_log_arguments' ) );
		add_filter( 'ywf_negative_type_operation', array( $this, 'add_negative_type_operation' ) );

	}

	/**
	 * Add this gateway in the list of maximum number of attempts to do.
	 *
	 * @param array $list The supported gateway list.
	 *
	 * @return array
	 */
	public function add_failed_attempts( $list ) {
		$list[ YITH_Funds_YITH_Subscription::$gateway_id ] = 4;

		return $list;
	}

	/**
	 * Add this gateway in the list of maximum number of attempts to do.
	 *
	 * @param array $list The supported gateway list.
	 *
	 * @return array
	 */
	public function add_num_of_days_between_attempts( $list ) {
		$list[ YITH_Funds_YITH_Subscription::$gateway_id ] = 5;

		return $list;
	}

	/**
	 * Add this gateway in the list "from" to understand from where the
	 * update status is requested.
	 *
	 * @param array $list The supported gateway list.
	 *
	 * @return mixed
	 */
	public function add_from_list( $list ) {
		$list[] = YITH_Funds_YITH_Subscription::instance()->get_method_title();

		return $list;
	}

	/**
	 * Replace the main gateway with the sources gateway.
	 *
	 * @param array $methods All gateways.
	 *
	 * @return array
	 */
	public function add_funds_integration_gateway( $methods ) {

		foreach ( $methods as $key => $method ) {
			if ( 'WC_Gateway_YITH_Funds' === $method ) {
				$methods[ $key ] = 'YITH_Funds_YITH_Subscription';
			}
		}

		return $methods;
	}

	/**
	 * Add the renew operation type in list
	 *
	 * @param array $list The operations.
	 *
	 * @return array
	 */
	public function add_operation_type( $list ) {
		$list['renew'] = __( 'Renewed a subscription with Funds', 'yith-woocommerce-account-funds' );

		return $list;
	}

	/**
	 * Filter the log argument when an order is a renew.
	 *
	 * @param array $arguments The arguments.
	 *
	 * @return array
	 */
	public function change_add_fund_order_log_arguments( $arguments ) {

		if ( isset( $arguments['order_id'] ) ) {
			$order = wc_get_order( $arguments['order_id'] );
			if ( $order ) {
				$is_a_renew = $order->get_meta( 'is_a_renew' );
				if ( 'yes' === $is_a_renew ) {
					$subscription                = ywsbs_get_subscription_by_order( $arguments['order_id'] );
					$arguments['type_operation'] = 'renew';
					/* translators: %1$1 is the order id %2$s is the subscription id */
					$arguments['description'] = sprintf( __( 'Renewed order #%1$s for subscription #%2$s', 'yith-woocommerce-account-funds' ), $arguments['order_id'], $subscription->id );
				}
			}
		}

		return $arguments;
	}

	/**
	 * Add the renew in the list
	 *
	 * @param array $list The list.
	 *
	 * @return array
	 */
	public function add_negative_type_operation( $list ) {
		$list[] = 'renew';

		return $list;
	}
}

/**
 * Return the instance of the class
 *
 * @return YITH_Funds_YITH_Subscription_Integration
 */
function YITH_Funds_YITH_Subscription_Integration() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return YITH_Funds_YITH_Subscription_Integration::instance();
}
