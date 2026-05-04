<?php
/**
 * Admin Page TPC
 *
 * @package tpc
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TPC_Admin' ) ) {

	/**
	 *
	 * Class for add admin page on wp-admin
	 */
	class TPC_Admin {

		/**
		 * Confirmation Page ID
		 * 
		 * @var integer
		 */
		protected $page_id = 0;

		/**
		 * Tonjoo License Handler
		 * 
		 * @var Tonjoo_License_Handler_3
		 */
		protected $license_handler;

		/**
		 * Confirmation Fields
		 * 
		 * @var TPC_Fields
		 */
		protected $fields;

		/**
		 * Setting object
		 * 
		 * @var TPC_Setting
		 */
		protected $setting;

		/**
		 * Constructor
		 */
		public function __construct( $license_handler ) {
			$this->license_handler = $license_handler;
			$this->fields          = new TPC_Fields();
			$this->setting         = new TPC_Setting();

			if ( $page = $this->setting->get_confirmation_page() ) {
				$this->page_id = $page->ID;
			}

			// Inits.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

			// Settings.
			add_action( 'admin_init', array( $this, 'handle_actions' ) );
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );

			// Ajaxs.
			add_action( 'wp_ajax_tpc_generate_confirmation_page', array( $this, 'ajax_generate_confirmation_page' ) );
			add_action( 'wp_ajax_tpc_update_data', array( $this, 'ajax_update_confirmation' ) );
			add_action( 'wp_ajax_tpc_confirm_payment', array( $this, 'ajax_confirm_payment' ) );
			add_action( 'wp_ajax_tpc_reject_payment', array( $this, 'ajax_reject_payment' ) );
			add_action( 'wp_ajax_tpc_handle_payment', array( $this, 'ajax_handle_payment' ) );

			// Orders.
			add_filter( 'manage_edit-shop_order_columns' , array( $this, 'add_header_columns_payment_confirm' ) );
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_content_columns_payment_confirm' ) );
			add_filter( 'bulk_actions-edit-shop_order', array( $this, 'define_bulk_actions' ) );
			add_filter( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk_actions' ), 10, 3 );
			add_action( 'woocommerce_admin_order_preview_start', array( $this, 'order_preview_confirmation' ) );
			add_filter( 'woocommerce_admin_order_preview_get_order_details', array( $this, 'order_preview_confirmation_data' ), 10, 2 );
			add_filter( 'woocommerce_admin_order_preview_actions', array( $this, 'order_preview_actions' ), 10, 2 );
			add_action( 'add_meta_boxes', array( $this, 'meta_box_payment_confirmation' ), 10, 2 );
			add_action( 'tpc_rejected_payment', array( $this, 'reset_limit' ), 10, 2 );

			// Let 3rd parties unhook the above via this hook.
			do_action( 'tpc_hooks_admin', $this );
		}

		/**
		 * Register admin menu
		 */
		public function admin_menu() {
			$need_confirm = $this->get_total_waiting_orders();
			if ( ! empty( $need_confirm ) ) {
				$count = ' <span class="update-plugins count-' . esc_attr( $need_confirm ) . '"><span class="plugin-count">' . esc_html( $need_confirm ) . '</span></span>';
			} else {
				$count = '';
			}
			add_menu_page( __( 'Confirmations', 'tpc' ) . $count, __( 'Confirmations', 'tpc' ) . $count, 'manage_woocommerce', 'tonjoo_payment_confirmation', null, TPC_URL . '/assets/img/icon.png', 57 );
			add_submenu_page( 'tonjoo_payment_confirmation', __( 'Orders', 'tpc' ), __( 'Orders', 'tpc' ), 'manage_woocommerce', 'tpc_order', '__return_empty_string' );
			// add_submenu_page( 'tonjoo_payment_confirmation', __( 'Report', 'tpc' ), __( 'Report', 'tpc' ), 'manage_woocommerce', 'tpc_report', '__return_empty_string' );
			add_submenu_page( 'tonjoo_payment_confirmation', __( 'Settings', 'tpc' ), __( 'Settings', 'tpc' ), 'manage_options', 'tpc_setting', array( $this, 'render_page_setting' ) );
			remove_submenu_page( 'tonjoo_payment_confirmation', 'tonjoo_payment_confirmation' );
		}

		/**
		 * Render Settings page
		 */
		public function render_page_setting() {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				$tabs = apply_filters(
					'tpc_setting_tabs', array(
						'general'   => array(
							'label'     => __( 'General', 'tpc' ),
							'callback'  => array( $this, 'render_subpage_setting_general' ),
						),
						'form'   => array(
							'label'     => __( 'Form', 'tpc' ),
							'callback'  => array( $this, 'render_subpage_setting_form' ),
						),
						'messages'  => array(
							'label'     => __( 'Messages', 'tpc' ),
							'callback'  => array( $this, 'render_subpage_setting_messages' ),
						),
						'email'  => array(
							'label'     => __( 'Email', 'tpc' ),
							'callback'  => array( $this, 'render_subpage_setting_email' ),
						),
					)
				);
				include_once TPC_PATH . 'views/admin/setting.php';
			} else {
				include_once TPC_PATH . 'views/admin/setting-no-wc.php';
			}
		}

		/**
		 * Render settings page - section general
		 */
		public function render_subpage_setting_general() {
			$settings 	= $this->setting->get_all();
			include_once TPC_PATH . 'views/admin/setting-general.php';
		}

		/**
		 * Render settings page - section form
		 */
		public function render_subpage_setting_form() {
			$fields 	= $this->fields->get_all();
			$settings 	= $this->setting->get_all();
			include_once TPC_PATH . 'views/admin/setting-form.php';
		}

		/**
		 * Render settings page - section notification
		 */
		public function render_subpage_setting_messages() {
			$settings 	= $this->setting->get_all();
			include_once TPC_PATH . 'views/admin/setting-messages.php';
		}

		/**
		 * Render settings page - section email
		 */
		public function render_subpage_setting_email() {
			$settings 	= $this->setting->get_all();
			include_once TPC_PATH . 'views/admin/setting-email.php';
		}

		/**
		 * Handle admin actions
		 */
		public function handle_actions() {
			if ( isset( $_REQUEST['tpc_action'] ) ) { // Input var okay.

				// update setting.
				if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['tpc_action'] ) ), 'update_setting' ) ) { // Input var okay.
					$new_setting = array();
					$old_setting = $this->setting->get_all();
					if ( isset( $_POST['tpc_setting'] ) && is_array( $_POST['tpc_setting'] ) ) { // Input var okay.
						foreach ( $old_setting as $key => $old_value ) { // WPCS: Input var okay, CSRF ok, sanitization ok.
							if ( isset( $_POST['tpc_setting'][ $key ] ) ) {
								$value = $_POST['tpc_setting'][ $key ];
							} else {
								if ( false !== stripos( $key, '_required' ) || 'must_login' === $key ) {
									$value = '';
								} else {
									$value = $old_value;
								}
							}
							if ( ! is_array( $value ) ) {
								if ( ! in_array( $key, array( 'form_html', 'form_css', 'thankyou_content', 'onhold_email_content' ) ) ) {
									$new_setting[ $key ] = sanitize_text_field( wp_unslash( $value ) );
								} else {
									$new_setting[ $key ] = wp_unslash( $value );
								}
							} else {
								$new_setting[ $key ] = array_map( 'sanitize_text_field', wp_unslash( $value ) );
							}
						}
					}

					$this->setting->save( $new_setting );
					$this->add_notice( __( 'Settings saved', 'tpc' ) );

				}

				do_action( 'tpc_admin_handle_action', sanitize_text_field( wp_unslash( $_REQUEST['tpc_action'] ) ), $this ); // Input var okay.
			} else {
				// redirects.
				global $pagenow;
				if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'tpc_order' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
					wp_redirect( admin_url( '/admin.php?post_status=wc-waiting-confirm&page=wc-orders'), 301 );
					exit;
				} elseif ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'tpc_report' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
					wp_redirect( admin_url( '/admin.php?page=wc-reports&tab=confirmation'), 301 );
					exit;
				}
			}
		}

		/**
		 * Add notice
		 *
		 * @param string  $message Message.
		 * @param string  $type    Type.
		 * @param boolean $p       Using paragraph?.
		 */
		public function add_notice( $message = '', $type = 'success', $p = true ) {
			$old_notice = get_option( 'tpc_notices', array() );
			$old_notice[] = array(
				'type'      => $type,
				'message'   => $p ? '<p>' . $message . '</p>' : $message,
			);
			update_option( 'tpc_notices', $old_notice, false );
		}

		/**
		 * Show admin notices
		 */
		public function admin_notice() {
			$screen = get_current_screen();
			$notices = get_option( 'tpc_notices', array() );
			foreach ( $notices as $notice ) {
				echo '
					<div class="notice is-dismissible notice-' . esc_attr( $notice['type'] ) . '">
						' . wp_kses_post( $notice['message'] ) . '
					</div>';
			}
			update_option( 'tpc_notices', array() );
		}

		/**
		 * AJAX Generate confirmation page
		 */
		public function ajax_generate_confirmation_page() {
			check_ajax_referer( 'generate_page', 'tpc_action' );
			if ( empty( $this->page_id ) ) {
				$this->page_id = wp_insert_post( array(
					'post_type'		=> 'page',
					'post_title'	=> __( 'Confirmation', 'tpc' ),
					'post_status'	=> 'publish',
					'post_content'	=> '<!-- wp:shortcode -->
[payment-confirmation]
<!-- /wp:shortcode -->'
				) );
				?>
				<div style="margin-bottom: 5px;">
					<strong><?php esc_html_e( 'Confirmation', 'tpc' ) ?></strong> (<code><?php echo esc_url( get_permalink( $this->page_id ) ) ?></code>)
				</div>
				<a class="button button-small" href="<?php echo esc_url( admin_url( 'post.php?post=' . $this->page_id . '&action=edit' ) ) ?>"><?php esc_html_e( 'Edit Page', 'tpc' ) ?></a>
				<a class="button button-small" href="<?php echo esc_url( get_permalink( $this->page_id ) ) ?>" target="_blank"><?php esc_html_e( 'View Page', 'tpc' ) ?></a>
				<?php
			}
			exit;
		}

		/**
		 * AJAX Update confirmation data
		 */
		public function ajax_update_confirmation() {
			check_ajax_referer( 'update_data', 'tpc_action' );

			$settings 	= $this->setting->get_all();
			$fields 	= $this->fields->get_all();
			$errors 	= '';
			$save_data 	= array();

			foreach ( $fields as $key => $field ) {
				if ( isset( $_POST[ $key ] ) ) {
					$save_data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // Input var okay.
				}
			}

			// check order id.
			$order_id = $save_data['order_id'];
			$order    = wc_get_order( $order_id );

			if ( ! $order ) {
				wp_send_json( array(
					'error'		=> true,
					'message'	=> __( 'Order ID not found', 'tpc' )
				) );
				exit;
			}

			// validate payment date.
			if ( isset( $save_data['transfer_date'] ) ) {
				if ( ! empty( $save_data['transfer_date'] ) ) {
					$year  = str_replace( '-', '', substr( $save_data['transfer_date'], 6, 4 ) );
					$month = str_replace( '-', '', substr( $save_data['transfer_date'], 3, 2 ) );
					$day   = str_replace( '-', '', substr( $save_data['transfer_date'], 0, 2 ) );

					if ( ! wp_checkdate( $month, $day, $year, $save_data['transfer_date'] ) ) {
						wp_send_json( array(
							'error'		=> true,
							'message'	=> __( 'Transfer date format not valid', 'tpc' )
						) );
						exit;
					}
				}
			}

			$save_data['submit_date'] = date( 'Y-m-d H:i:s' );

			// before submit new confirmation.
			$save_data = apply_filters( 'tpc_before_submit_confirmation', $save_data );

			$order->update_meta_data( 'tpc_data', $save_data );
			$order->save();

			ob_start();
			$confirm = $save_data;
			include TPC_PATH . 'views/admin/metabox-order-view.php';
			$html = ob_get_clean();

			wp_send_json( array(
				'error'	=> false,
				'html'	=> $html
			) );
			exit;
		}

		/**
		 * Confirm the payment
		 * 
		 * @param object $order Order.
		 */
		protected function confirm_payment( $order ) {
			if ( ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order );
			}

			// log confirmation date.
			$order->update_meta_data( 'tpc_confirmed', current_time( 'Y-m-d H:i:s' ) );

			// Change order status.
			$status = str_replace( 'wc-', '', $this->setting->get('confirmed_status') );
			$order->update_status( $status, __( 'The payment has been confirmed.', 'tpc' ), true );
			$order->save();

			/**
			 * @hooked TPC_Email_Confirmed_Payment::trigger() Sends notification email to customer.
			 */
			do_action( 'tpc_confirmed_payment', $order->get_id(), $order );
		}

		/**
		 * Reject the payment
		 * 
		 * @param object $order  Order.
		 * @param string $reason Rejection reason.
		 */
		protected function reject_payment( $order, $reason = '' ) {
			if ( ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order );
			}

			// log rejection date.
			$order->update_meta_data( 'tpc_rejected', current_time( 'Y-m-d H:i:s' ) );
			$order->update_meta_data( 'tpc_reject_reason', $reason );

			// Change order status.
			$status = str_replace( 'wc-', '', $this->setting->get('rejected_status') );
			$order->update_status( $status, __( 'The payment has been rejected.', 'tpc' ), true );
			$order->save();

			/**
			 * @hooked TPC_Email_Rejected_Payment::trigger() Sends notification email to customer.
			 * @hooked TPC_Admin::reset_limit() Reset submission limit.
			 */
			do_action( 'tpc_rejected_payment', $order->get_id(), $order );
		}

		/**
		 * AJAX Confirm the payment
		 */
		public function ajax_confirm_payment() {
			check_ajax_referer( 'respond_payment', 'tpc_action' );
			$order_id = isset( $_POST['order_id'] ) ? intval( $_POST['order_id'] ) : 0;
			$order = wc_get_order( $order_id );
			if ( $order ) {

				$this->confirm_payment( $order );
				$this->add_notice( sprintf( __( 'Payment confirmed. Order status for %s has changed to %s.', 'tpc' ), '#' . $order->get_id(), wc_get_order_status_name( str_replace( 'wc-', '', $this->setting->get('confirmed_status') ) ) ) );

				wp_send_json( array(
					'error'		=> false,
					'message'	=> __( 'Success', 'tpc' )
				) );
				exit;
			} else {
				wp_send_json( array(
					'error'		=> true,
					'message'	=> __( 'Order not found', 'tpc' )
				) );
				exit;
			}
		}

		/**
		 * AJAX Reject the payment
		 */
		public function ajax_reject_payment() {
			check_ajax_referer( 'respond_payment', 'tpc_action' );

			$order_id 	= isset( $_POST['order_id'] ) ? intval( $_POST['order_id'] ) : 0;
			$reason 	= isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '';
			$order 		= wc_get_order( $order_id );

			if ( $order ) {

				$this->reject_payment( $order, $reason );
				$this->add_notice( sprintf( __( 'Payment rejected. Order status for %s has changed to %s.', 'tpc' ), '#' . $order->get_id(), wc_get_order_status_name( str_replace( 'wc-', '', $this->setting->get('rejected_status') ) ) ) );

				wp_send_json( array(
					'error'		=> false,
					'message'	=> __( 'Success', 'tpc' )
				) );
				exit;
			} else {
				wp_send_json( array(
					'error'		=> true,
					'message'	=> __( 'Order not found', 'tpc' )
				) );
				exit;
			}
		}

		/**
		 * AJAX handle the payment from order preview
		 */
		public function ajax_handle_payment() {
			if ( check_admin_referer( 'tpc-handle-payment' ) && isset( $_GET['status'], $_GET['order_id'] ) ) {
				$status = sanitize_text_field( wp_unslash( $_GET['status'] ) );
				$order  = wc_get_order( absint( wp_unslash( $_GET['order_id'] ) ) );

				if ( $order && 'confirm' === $status ) {
					$this->confirm_payment( $order );
					$this->add_notice( sprintf( __( 'Payment confirmed. Order status for %s has changed to %s.', 'tpc' ), '#' . $order->get_id(), wc_get_order_status_name( str_replace( 'wc-', '', $this->setting->get('confirmed_status') ) ) ) );
				} elseif ( $order && 'reject' === $status ) {
					$this->reject_payment( $order );
					$this->add_notice( sprintf( __( 'Payment rejected. Order status for %s has changed to %s.', 'tpc' ), '#' . $order->get_id(), wc_get_order_status_name( str_replace( 'wc-', '', $this->setting->get('rejected_status') ) ) ) );
				}
			}

			wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_status=wc-waiting-confirm&post_type=shop_order' ) );
			exit;
		}

		/**
		 * Add New Header Columns payment confirmation to table order
		 *
		 * @param array $columns Columns.
		 * @return array $new_columns
		 */
		public function add_header_columns_payment_confirm( $columns ) {
			if ( isset( $_GET['post_status'] ) && 'wc-waiting-confirm' === $_GET['post_status'] ) {
				$columns['confirm'] = __( 'Confirmation', 'tpc' );
				if ( isset( $columns[ 'shipping_address' ] ) ) {
					unset( $columns[ 'shipping_address' ] );
				}
			}
			return $columns;
		}

		/**
		 * Add New Content Columns payment confirmation to table order
		 *
		 * @param   array $column     Columns.
		 * @global  object  $post       post
		 */
		public function add_content_columns_payment_confirm( $column ) {
			global $post;

			if ( 'confirm' === $column ) {
				?>
				<button class="button order-preview tpc-order-preview" type="button" data-order-id="<?php echo esc_attr( $post->ID ) ?>"><?php esc_html_e( 'Quick View', 'tpc' ) ?></button>
				<?php
			}
		}

		/**
		 * Define bulk actions on orders page
		 * 
		 * @param  array $bulk_actions Bulk actions.
		 * @return array               Bulk actions.
		 */
		public function define_bulk_actions( $bulk_actions ) {
			if ( isset( $_GET['post_status'] ) && 'wc-waiting-confirm' === $_GET['post_status'] ) {
				$bulk_actions['payment_confirm'] = __( 'Confirm payment', 'tpc' );
				$bulk_actions['payment_reject'] = __( 'Reject payment', 'tpc' );
			}
			return $bulk_actions;
		}

		/**
		 * Handle bulk actions
		 * 
		 * @param  string $redirect_to Redirect URL.
		 * @param  string $doaction    Action.
		 * @param  array  $post_ids    Selected post IDs.
		 * @return string              Redirect URL.
		 */
		public function handle_bulk_actions( $redirect_to, $doaction, $post_ids ) {
			if ( 'payment_confirm' === $doaction ) {
				foreach ( $post_ids as $order_id ) {
					$this->confirm_payment( $order_id );
				}
				$this->add_notice( __( 'Orders updated', 'tpc' ) );
			} elseif ( 'payment_reject' === $doaction ) {
				foreach ( $post_ids as $order_id ) {
					$this->reject_payment( $order_id );
				}
				$this->add_notice( __( 'Orders updated', 'tpc' ) );
			}
			return $redirect_to;
		}

		/**
		 * Add confirmation data to order preview
		 */
		public function order_preview_confirmation() {
			$fields = $this->fields->get_all();
			?>
				<# if ( data.payment_confirmation ) { #>
					<div class="wc-order-preview-confirmation">
						<h2><?php esc_html_e( 'Payment confirmation', 'tpc' ); ?></h2>
						<# if ( data.payment_confirmation.file ) { #>
							<div class="panel">
								<img src="{{ data.payment_confirmation.file }}" alt="Proof" class="tpc-image">
							</div>
							<div class="panel">
								<?php foreach ( $fields as $key => $field ) : ?>
									<?php if ( ! $field['required'] && 'file' !== $key ) : ?>
										<# if ( data.payment_confirmation.<?php echo esc_attr( $key ); ?> ) { #>
											<strong><?php echo esc_html( $field['name'] ) ?></strong>
											{{ data.payment_confirmation.<?php echo esc_attr( $key ); ?> }}
										<# } #>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<# } #>
					</div>
				<# } #>
			<?php
		}

		/**
		 * Generate payment data for order preview
		 * 
		 * @param  array  $data  Data that will be displayed on order preview.
		 * @param  object $order WC_Order.
		 * @return array         Data that will be displayed on order preview.
		 */
		public function order_preview_confirmation_data( $data, $order ) {
			$data['payment_confirmation'] = $this->get_confirmation_data( $order->get_id() );
			if ( isset( $data['payment_confirmation']['file'] ) && 0 !== intval( $data['payment_confirmation']['file'] ) ) {
				$data['payment_confirmation']['file'] = wp_get_attachment_image_url( $data['payment_confirmation']['file'], 'medium' );
			}
			if ( isset( $data['payment_confirmation']['amount'] ) ) {
				$data['payment_confirmation']['amount'] = str_replace( '&nbsp;', ' ', strip_tags( wc_price( $data['payment_confirmation']['amount'] ) ) );
			}
			return $data;
		}

		/**
		 * Order preview actions
		 * 
		 * @param  array  $actions Order actions.
		 * @param  object $order   WC_Order.
		 * @return array           Order actions.
		 */
		public function order_preview_actions( $actions, $order ) {
			if ( $order->has_status( array( 'waiting-confirm' ) ) ) {
				$actions['payment'] = array(
					'group'   => __( 'Confirm payment: ', 'tpc' ),
					'actions' => array(
						'confirm'	=> array(
							'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=tpc_handle_payment&status=confirm&order_id=' . $order->get_id() ), 'tpc-handle-payment' ),
							'name'   => __( 'Confirm Payment', 'tpc' ),
							'title'  => __( 'Confirm the payment', 'tpc' ),
							'action' => 'confirm',
						),
						'reject'	=> array(
							'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=tpc_handle_payment&status=reject&order_id=' . $order->get_id() ), 'tpc-handle-payment' ),
							'name'   => __( 'Reject Payment', 'tpc' ),
							'title'  => __( 'Reject the payment', 'tpc' ),
							'action' => 'reject',
						)
					),
				);
			}
			return $actions;
		}

		/**
		 * Add a post display state for special WC pages in the page list table.
		 *
		 * @param array   $post_states An array of post display states.
		 * @param WP_Post $post        The current post object.
		 */
		public function add_display_post_states( $post_states, $post ) {
			if ( $this->page_id === $post->ID ) {
				$post_states['wc_page_for_payment_confirmation'] = __( 'Payment Confirmation Page', 'tpc' );
			}

			return $post_states;
		}

		/**
		 * Enqueue BE scripts and styles.
		 *
		 * @global string $post_type
		 */
		public function admin_enqueue_scripts() {
			$screen = get_current_screen();
			if ( ! is_null( $screen ) && ( in_array( $screen->id, array( 'shop_order', 'edit-shop_order', 'woocommerce_page_wc-orders', 'woocommerce_page_wc-reports' ), true ) || ( false !== stripos( $screen->id, 'page_tpc_setting' ) ) ) ) {
				if ( in_array( $screen->id, array( 'shop_order', 'edit-shop_order' ), true ) ) {
					global $post;
					wp_enqueue_media( array( 'post' => $post->ID ) );
				} else if ( 'woocommerce_page_wc-orders' === $screen->id && isset( $_GET['id'] ) ) {
					wp_enqueue_media( array( 'post' => absint( $_GET['id'] ) ) );
				}
				if ( 'woocommerce_page_wc-reports' === $screen->id ) {
					wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
				}
				wp_enqueue_style( 'tpc-admin', TPC_URL . '/assets/css/admin.css', array() );
				wp_enqueue_script( 'tpc-admin', TPC_URL . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-accordion' ) );

				$localization = array(
					'confirm' 				=> __( 'Are you sure ?', 'tpc' ),
					'ask_reason' 			=> __( 'Please tell the reason why you reject this payment. We will notify the customer about it.', 'tpc' ),
					'fields'  				=> array_keys( $this->fields->get_all() ),
					'error_required_field'	=> __( 'Order ID and Customer Email must be in the form', 'tpc' ),
					'nonce_generate_page'	=> wp_create_nonce( 'generate_page' ),
					'nonce_update_data'		=> wp_create_nonce( 'update_data' ),
					'nonce_respond_payment'	=> wp_create_nonce( 'respond_payment' ),
				);
				wp_localize_script( 'tpc-admin', 'loc', $localization );
			}
		}

		/**
		 * Create Metabox Payment Confirmation to Edit Order Details
		 *
		 * @param  string $post_type Post type.
		 * @param  object $post      Post object.
		 */
		public function meta_box_payment_confirmation( $post_type, $post ) {
			add_meta_box(
				'payment_confirmation',
				__( 'Payment Confirmation', 'tpc' ),
				array( $this, 'list_payment_confirmation' ),
				[
					'shop_order',
					'woocommerce_page_wc-orders'
				],
				'side',
				'core'
			);
		}

		/**
		 * Callback to display list of payment confirmation
		 *
		 * @param  object $post Post object.
		 */
		public function list_payment_confirmation( $post ) {
			$order 		= wc_get_order( $post->ID );
			$confirm 	= $this->get_confirmation_data( $post->ID );
			$fields 	= $this->fields->get_all();
			include TPC_PATH . 'views/admin/metabox-order.php';
		}

		/**
		 * Get confirmation data
		 * 
		 * @param  integer $order_id Order ID.
		 * @return array             Confirmation data.
		 */
		protected function get_confirmation_data( $order_id ) {
			$order   = wc_get_order( $order_id );
			$confirm = $order->get_meta( 'tpc_data', true );
			// backward compatibility.
			if ( empty( $confirm ) && ( $legacy = $order->get_meta( 'tpc_confirmation', true ) ) ) {
				$confirm = array(
					'order_id' 			=> isset( $legacy['order_id'] ) ? $legacy['order_id'] : '',
					'customer_email'	=> isset( $legacy['email'] ) ? $legacy['email'] : '',
					'amount'			=> isset( $legacy['amount'] ) ? $legacy['amount'] : '',
					'transfer_date'		=> isset( $legacy['date'] ) ? $legacy['date'] : '',
					'bank'				=> isset( $legacy['bank_destination'] ) ? $legacy['bank_destination'] : '',
					'customer_bank'		=> isset( $legacy['bank_from'] ) ? $legacy['bank_from'] : '',
					'customer_account' 	=> isset( $legacy['bank_from_name'] ) ? $legacy['bank_from_name'] : '',
					'file'				=> isset( $legacy['file'] ) ? $legacy['file'] : '',
					'note'				=> isset( $legacy['note'] ) ? $legacy['note'] : '',
				);
			}
			return $confirm;
		}

		/**
		 * Reset submission limit
		 * 
		 * @param  integer $order_id Order ID.
		 * @param  object  $order    WC_Order.
		 */
		public function reset_limit( $order_id, $order ) {
			$meta = $order->get_meta( 'tpc_data', true );
			$meta['submission'] = 0;
			$order->update_meta_data( 'tpc_data', $meta );
			$order->save();
		}

		/**
		 * Get total waiting confirmation orders
		 *
		 * @return int Total order
		 */
		public function get_total_waiting_orders() {
			$post_count = wp_count_posts( 'shop_order' );
			return isset( $post_count->{'wc-waiting-confirm'} ) ? $post_count->{'wc-waiting-confirm'} : 0;
		}

	}

}
