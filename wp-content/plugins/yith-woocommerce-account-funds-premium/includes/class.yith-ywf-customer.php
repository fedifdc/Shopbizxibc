<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the class that manage customer actions
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Customer' ) ) {
	/**
	 * Class YITH_YWF_Customer
	 */
	class YITH_YWF_Customer {

		/**
		 * The customer id
		 *
		 * @var int
		 */
		protected $customer_id;
		/**
		 * The meta key
		 *
		 * @var string
		 */
		protected $meta_key;

		/**
		 * YITH_YWF_Customer constructor.
		 *
		 * @param int $customer_id The user id.
		 */
		public function __construct( $customer_id ) {
			$this->customer_id = $customer_id;
			$this->meta_key    = '_customer_fund';
		}

		/**
		 * Static function for get customer fund
		 *
		 * @since  1.0.0
		 * @return float
		 */
		public function get_funds() {
			$funds = get_user_meta( $this->customer_id, $this->meta_key, true );
			return empty( $funds ) ? 0 : floatval( $funds );
		}

		/**
		 * Set a new funds for customer
		 *
		 * @since  1.0.0
		 * @param float $funds the funds.
		 *
		 * @return boolean True on success, false otherwise.
		 */
		public function set_funds( $funds ) {

			if ( ! update_user_meta( $this->customer_id, $this->meta_key, $funds ) ) {
				return false;
			}

			$user_funds_limiter = wc_format_decimal( get_option( 'ywf_email_limit' ) );

			if ( $funds <= $user_funds_limiter ) {

				WC()->mailer();
				do_action( 'ywf_send_user_fund_email_notification', $this->customer_id );
			} else {
				update_user_meta( $this->customer_id, '_user_mail_send', 'no' );
			}

			return true;
		}

		/**
		 * Add the transaction in the log
		 *
		 * @since  1.0.0
		 * @param float $new_funds The funds.
		 * @param array $args_log  The log args.
		 */
		public function add_funds_with_log( $new_funds, $args_log = array() ) {

			$this->add_funds( $new_funds );

			$fund_log_args = array(
				'user_id'        => $this->customer_id,
				'fund_user'      => $new_funds,
				'type_operation' => '',
				'description'    => '',
				'order_id'       => '',
			);

			$fund_log_args = wp_parse_args( $args_log, $fund_log_args );
			do_action( 'ywf_add_user_log', $fund_log_args );
		}

		/**
		 * Increase the customer funds
		 *
		 * @since  1.0.0
		 * @param float $new_funds The new funds.
		 *
		 * @return boolean True on success, false otherwise or if the value is the same of the old one.
		 */
		public function add_funds( $new_funds ) {
			$old_funds  = $this->get_funds();
			$old_funds += floatval( $new_funds );

			return $this->set_funds( $old_funds );
		}

		/**
		 * Decrement the customer funds
		 *
		 * @since  1.0.0
		 * @param float $new_funds The new funds.
		 *
		 * @return boolean True on success, false otherwise or if the value is the same of the old one.
		 */
		public function decrement_funds( $new_funds ) {
			$old_funds  = $this->get_funds();
			$old_funds -= floatval( $new_funds );

			return $this->set_funds( $old_funds );
		}

	}
}
