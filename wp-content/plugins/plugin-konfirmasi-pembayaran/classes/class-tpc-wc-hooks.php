<?php
/**
 * Register
 *
 * @package tpc
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TPC_WC_Hooks' ) ) {

	/**
	 * Class for register
	 */
	class TPC_WC_Hooks {

		/**
		 * Setting
		 *
		 * @var object
		 */
		protected $setting;

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->setting = new TPC_Setting();

			// Core.
			add_action( 'init', array( $this, 'waiting_confirmation_status' ) );
			add_filter( 'wc_order_statuses', array( $this, 'add_waiting_confirmation_order_statuses' ) );
			add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_email' ) );
			add_filter( 'woocommerce_email_actions', array( $this, 'register_email_actions' ) );

			// Admin.
			add_action( 'woocommerce_order_status_waiting-confirm', array( $this, 'order_waiting_confirm' ), 10, 2 );
			// add_filter( 'woocommerce_admin_reports', array( $this, 'custom_report_tab' ) );

			// Email.
			add_action( 'woocommerce_email_customer_details', array( $this, 'onhold_email_content' ), 99, 4 );

			// Front.
			add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'add_order_action' ), 20, 2 );
			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'add_confirm_button_orders' ) );
			foreach ( $this->setting->get('payment_methods') as $method ) {
				add_action( 'woocommerce_thankyou_' . $method, array( $this, 'thankyou_page' ), 20 );
			}

			// Let 3rd parties unhook the above via this hook.
			do_action( 'tpc_hooks_woocommerce', $this );
		}

		/**
		 * Register new status `waiting confirmation` for order
		 */
		public function waiting_confirmation_status() {
			register_post_status(
				'wc-waiting-confirm', array(
					'label'                     => __( 'Waiting Confirmation', 'tpc' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'label_count'               => _n_noop( __( 'Waiting Confirmation', 'tpc' ) . ' <span class="count">(%s)</span>', __( 'Waiting Confirmation', 'tpc' ) . ' <span class="count">(%s)</span>' ),
				)
			);
		}

		/**
		 * Add status waiting confirmation to list of WC Order status
		 *
		 * @param array $order_statuses Order Statuses.
		 */
		public function add_waiting_confirmation_order_statuses( $order_statuses ) {
			$order_statuses['wc-waiting-confirm'] = __( 'Waiting Confirmation', 'tpc' );
			return $order_statuses;
		}

		/**
		 * Register Custom Email woocommerce for payment confirmation
		 *
		 * @param array $email_classes Email classes.
		 */
		public function add_woocommerce_email( $email_classes ) {
			$email_classes['TPC_Email_New_Submission']		= include TPC_PATH . 'classes/class-tpc-email-new-submission.php';
			$email_classes['TPC_Email_Confirmed_Payment']	= include TPC_PATH . 'classes/class-tpc-email-confirmed-payment.php';
			$email_classes['TPC_Email_Rejected_Payment']	= include TPC_PATH . 'classes/class-tpc-email-rejected-payment.php';

			return $email_classes;
		}

		/**
		 * Register email actions
		 * 
		 * @param  array $actions Actions.
		 * @return array          Actions.
		 */
		public function register_email_actions( $actions ) {
			$actions[] = 'tpc_submit_confirmation';
			$actions[] = 'tpc_confirmed_payment';
			$actions[] = 'tpc_rejected_payment';
			return $actions;
		}

		/**
		 * Get confirmation page url
		 *
		 * @return string Confirmation page url.
		 */
		public function get_confirmation_page_url() {
			if ( $page = $this->setting->get_confirmation_page() ) {
				return get_permalink( $page->ID );
			}
			return false;
		}

		/**
		 * Add order actions for customer
		 *
		 * @param array  $actions Order actions.
		 * @param object $order   Order object.
		 */
		public function add_order_action( $actions, $order ) {
			$accept_status = apply_filters( 'tpc_accepted_order_status', array( 'on-hold', 'pending', 'waiting-confirm' ) );
			if ( in_array( $order->get_payment_method(), $this->setting->get('payment_methods'), true ) && in_array( $order->get_status(), $accept_status, true ) ) {
				// check if confirmation page exists.
				if ( $this->get_confirmation_page_url() ) {
					$actions['confirm'] = array(
						'url'  => $this->get_confirmation_page_url() . '?order_id=' . esc_attr( $order->get_id() ),
						'name' => __( 'Confirm', 'tpc' ),
					);
				}
			}
			return $actions;
		}

		/**
		 * Add confirmation page link on customer's order detail
		 * 
		 * @param object $order   Order object.
		 */
		public function add_confirm_button_orders( $order ) {
			$accept_status = apply_filters( 'tpc_accepted_order_status', array( 'on-hold', 'pending', 'waiting-confirm' ) );
			if ( ! $order || ! $order->has_status( $accept_status ) || ! in_array( $order->get_payment_method(), $this->setting->get('payment_methods'), true ) || ! is_user_logged_in() || ! is_wc_endpoint_url( 'view-order' ) ) {
				return;
			}
			if ( $this->get_confirmation_page_url() ) {
				if ( $order->has_status( 'waiting-confirm' ) ) {
					echo '<p><a href="' . $this->get_confirmation_page_url() . '?order_id=' . esc_attr( $order->get_id() ) . '" class="button">' . esc_html__( 'Edit Payment Confirmation', 'tpc' ) . '</a></p>';
				} else {
					echo '<p><a href="' . $this->get_confirmation_page_url() . '?order_id=' . esc_attr( $order->get_id() ) . '" class="button">' . esc_html__( 'Confirm Payment', 'tpc' ) . '</a></p>';
				}
			}
		}

		/**
		 * Thankyou page content
		 * 
		 * @param integer $order_id Order ID.
		 */
		public function thankyou_page( $order_id ) {
			$page 		= $this->setting->get_confirmation_page();
			$order = wc_get_order( $order_id );
			$is_ads_product = 'no';
			$is_affiliate_product = 'no';
			foreach ( $order->get_items() as $item ) {
				$is_affiliate_product =	get_post_meta( $product->id, '_is_affiliate_product', true );
            	$is_ads_product = get_post_meta( $product->id, '_is_ads_product', true );
				if ($is_affiliate_product == 'yes' ) {
					break;
				}
				if ($is_ads_product == 'yes' ) {
					break;
				}
			}
			if ($is_affiliate_product == 'no' && $is_ads_product == 'no') {
				$content 	= $this->setting->get('thankyou_content');
				$content = str_replace( '[confirmation_url]', add_query_arg( array( 'order_id' => $order_id ), get_permalink( $page->ID ) ), $content );
			} 
			?>
			<div class="tpc-message">
				<?php echo wp_kses_post( $content ); ?>
			</div>
			<?php
		}

		/**
		 * Additional on-hold email content
		 * 
		 * @param  object  $order         WC_Order.
		 * @param  boolean $sent_to_admin Is sent to admin.
		 * @param  string  $plain_text    Text.
		 * @param  object  $email         Email object.
		 */
		public function onhold_email_content( $order, $sent_to_admin, $plain_text, $email ) {
			if ( $email && 'customer_on_hold_order' === $email->id ) {
				$page 		= $this->setting->get_confirmation_page();
				$content 	= $this->setting->get('onhold_email_content');
				$content 	= str_replace( '[confirmation_url]', add_query_arg( array( 'order_id' => $order->get_id() ), get_permalink( $page->ID ) ), $content );
				?>
				<div class="tpc-message">
					<?php echo wp_kses_post( $content ); ?>
				</div>
				<?php
			}
		}

		/**
		 * Reset confirmation date.
		 * 
		 * @param  integer $order_id Order ID.
		 * @param  object  $order    WC_Order.
		 */
		public function order_waiting_confirm( $order_id, $order ) {
			$order = wc_get_order( $order_id );
			$order->delete_meta_data( 'tpc_confirmed' );
			$order->delete_meta_data( 'tpc_rejected' );
			$order->delete_meta_data( 'tpc_reject_reason' );
		}

		/**
		 * Register report tab
		 *
		 * @param  array $tabs Report tabs.
		 * @return array       Report tabs.
		 */
		public function custom_report_tab( $tabs ) {
			$tabs['confirmation'] = array(
				'title'   => __( 'Payment Confirmations', 'tpc' ),
				'reports' => array(
					'all' => array(
						'title'       => __( 'All Status', 'tpc' ),
						'description' => '',
						'hide_title'  => true,
						'callback'    => array( $this, 'get_report' ),
					)
				),
			);
			return $tabs;
		}

		/**
		 * Display report
		 *
		 * @param  string $status Confirmation status.
		 */
		public function get_report( $status ) {
			$report = new TPC_Report_Table( $status );
			$report->output_report();
		}


	}

}
