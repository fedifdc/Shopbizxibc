<?php
/**
 * Form Confirmation
 *
 * @package tpc
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TPC_Form' ) ) {

	/**
	 * Class for create form payment confirmation frontend
	 */
	class TPC_Form {

		/**
		 * Error message
		 *
		 * @var array
		 */
		public $error_msg = array();

		/**
		 * Confirmation fields
		 *
		 * @var array
		 */
		public $fields = array();

		protected $setting;

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->setting 	= new TPC_Setting();
			$this->fields 	= new TPC_Fields();
			
			add_action( 'init', array( $this, 'init_form' ) );
			add_action( 'template_redirect', array( $this, 'maybe_redirect' ) );
			add_filter( 'woocommerce_login_redirect', array( $this, 'set_redirect_url' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_action( 'wp_ajax_tpc_submit_confirmation', array( $this, 'submit_confirmation' ) );
			add_action( 'wp_ajax_nopriv_tpc_submit_confirmation', array( $this, 'submit_confirmation' ) );
			add_action( 'tpc_submit_confirmation', array( $this, 'set_order_status' ), 10 );

			// Let 3rd parties unhook the above via this hook.
			do_action( 'tpc_hooks_form', $this );
		}

		/**
		 * set init form
		 */
		public function init_form() {
			add_shortcode( 'payment-confirmation', array( $this, 'form_payment_confirmation' ) );
		}

		/**
		 * Redirect user to login page if the confirmation page is set restricted to non logged in user
		 */
		public function maybe_redirect() {
			$page = $this->setting->get_confirmation_page();
			if ( $page && is_page( $page->ID ) && ! is_user_logged_in() && $this->setting->get('must_login') ) {
				$url = get_permalink( $page->ID );
				if ( isset( $_GET['order_id'] ) && 0 !== intval( $_GET['order_id'] ) ) {
					$url = add_query_arg( 'order_id', intval( $_GET['order_id'] ), $url );
				}
				wp_redirect( add_query_arg( 'redirect_to', rawurlencode( $url ), wc_get_page_permalink( 'myaccount' ) ) ); // phpcs:ignore
				exit;
			}
		}

		/**
		 * Set redirection URL
		 * 
		 * @param string $url Redirection URL.
		 */
		public function set_redirect_url( $url ) {
			if ( isset( $_GET['redirect_to'] ) ) {
				$page = $this->setting->get_confirmation_page();
				if ( false !== stripos( urldecode( $_GET['redirect_to'] ), get_permalink( $page->ID ) ) ) {
					$url = urldecode( $_GET['redirect_to'] );
				}
			}
			return $url;
		}

		/**
		 * Enqueue FE scripts and styles.
		 */
		public function wp_enqueue_scripts() {
			global $post;
			if ( is_checkout() || ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'payment-confirmation' ) ) ) {
				// CSS.
				wp_register_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
				wp_enqueue_style( 'tpc-main-style', TPC_URL . '/assets/css/front.css', array( 'jquery-ui' ) );

				// JS.
				wp_enqueue_script( 'tpc-front-script', TPC_URL . '/assets/js/front.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ) );
				$localization = array(
					'submit_error'	=> $this->setting->get('mes_error'),
					'redirect'		=> '',
					'ajaxurl'		=> admin_url( 'admin-ajax.php' )
				);
				if ( $redirect = $this->setting->get('redirection') ) {
					if ( false !== filter_var( $redirect, FILTER_VALIDATE_URL ) ) {
						$localization['redirect'] = $redirect;
					}
				}
				wp_localize_script( 'tpc-front-script', 'tpc', $localization );
			}
		}

		/**
		 * Render Form Payment Confirmation
		 */
		public function form_payment_confirmation() {
			if ( ! is_admin() && ! wp_doing_ajax() ) {
				ob_start();
				$css 	= $this->setting->get('form_css');
				$html 	= $this->setting->get('form_html');
				include apply_filters( 'tpc_confirmation_form_path', TPC_PATH . 'views/front/form-payment-confirmation.php' );
				return ob_get_clean();
			}
		}

		/**
		 * Handle the submission
		 */
		public function submit_confirmation() {
			check_ajax_referer( 'send_confirmation', 'tpc_action' );

			$settings 	= $this->setting->get_all();
			$fields 	= $this->fields->get_all();
			$active_fields = $this->fields->get_used_fields();
			$errors 	= '';
			$save_data 	= array();

			foreach ( $active_fields as $key ) {

				// skip file & recaptcha validation for later.
				if ( 'file' === $key || 'recaptcha' === $key ) {
					continue;
				}

				// check required fields.
				if ( $settings[ 'field_' . $key . '_required' ] && ! isset( $_POST[ $key ] ) ) { // Input var okay.
					$errors = $settings['mes_error_required'];
					continue;
				}

				// check email.
				if ( 'customer_email' === $key && ! is_email( sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ) ) { // Input var okay.
					$errors = $settings['mes_error_email'];
					continue;
				}

				// save to new array.
				if ( isset( $_POST[ $key ] ) ) {
					$save_data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // Input var okay.
				}
			}

			if ( ! empty( $errors ) ) {
				$this->throw_error( $errors );
				exit;
			}

			// check order id.
			$order_id = $save_data['order_id'];
			$order    = wc_get_order( $order_id );

			if ( ! $order ) {
				$errors = $settings['mes_error_order_id'];
				$this->throw_error( $errors );
				exit;
			}

			// validate email.
			if ( isset( $save_data['customer_email'] ) && $save_data['customer_email'] !== $order->get_billing_email() ) {
				$errors = $settings['mes_error_order_email'];
				$this->throw_error( $errors );
				exit;
			}

			$current = $order->get_meta( 'tpc_data', true );
			if ( ! empty( $current ) && isset( $current['submission'] ) ) {
				$save_data['submission'] = intval( $current['submission'] ) + 1;
			} else {
				$save_data['submission'] = 1;
			}

			// validate limit.
			if ( ! empty( $settings['submission_limit'] ) && $save_data['submission'] > intval( $settings['submission_limit'] ) ) {
				$errors = $settings['mes_error_limit'];
				$this->throw_error( $errors );
				exit;
			}

			// validate order method & status.
			$accept_status = apply_filters( 'tpc_accepted_order_status', array( 'on-hold', 'pending', 'waiting-confirm' ) );
			if ( ! in_array( $order->get_payment_method(), $settings['payment_methods'], true ) || ! in_array( $order->get_status(), $accept_status, true ) ) {
				$errors = $settings['mes_error_order_status'];
			}

			// validate payment date.
			if ( isset( $save_data['transfer_date'] ) ) {
				if ( ! empty( $save_data['transfer_date'] ) ) {
					$year  = str_replace( '-', '', substr( $save_data['transfer_date'], 6, 4 ) );
					$month = str_replace( '-', '', substr( $save_data['transfer_date'], 3, 2 ) );
					$day   = str_replace( '-', '', substr( $save_data['transfer_date'], 0, 2 ) );

					if ( ! wp_checkdate( $month, $day, $year, $save_data['transfer_date'] ) ) {
						$errors = $settings['mes_error_date'];
					}
				}
			}

			// validate captcha.
			if ( in_array( 'recaptcha', $active_fields ) ) {
				if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
					$errors = $settings['mes_error_recaptcha_required'];
				} else {
					$response = wp_remote_post( "https://www.google.com/recaptcha/api/siteverify", array(
						'method'		=> 'POST',
						'timeout'		=> 60,
						'redirection'	=> 5,
						'httpversion'	=> '1.0',
						'blocking'		=> true,
						'headers'		=> array(),
						'body'			=> array(
							'secret'	=> $settings['field_recaptcha_secretkey'],
							'response'	=> sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) )
						),
						'cookies'		=> array()
					) );
					if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
						$body = wp_remote_retrieve_body( $response );
						$body = json_decode( $body );
						if ( ! isset( $body->success ) || ! $body->success ) {
							$errors = $settings['mes_error_recaptcha_failed'];
						}
					} else {
						$errors = $settings['mes_error_recaptcha_failed'];
					}
				}
			}

			// handle file.
			if ( in_array( 'file', $active_fields, true ) ) {
				if ( isset( $_FILES['file'] ) ) {
					$upload     = $_FILES['file'];
					$ext_types 	= wp_get_ext_types();

					if ( 0 < $upload['size'] ) {
						if ( ! in_array( wp_check_filetype( $upload['name'] )['ext'], $ext_types['image'], true ) ) {
							$errors = $settings['mes_error_file_type'];
						} elseif ( ( intval( $settings['field_file_maxsize'] ) * 1024 ) < $upload['size'] ) {
							$errors = $settings['mes_error_file_size'];
						} elseif ( UPLOAD_ERR_OK !== $upload['error'] ) {
							$errors = $settings['mes_error_file_upload'];
						} else {
							require_once ABSPATH . 'wp-admin/includes/image.php';
							require_once ABSPATH . 'wp-admin/includes/file.php';
							require_once ABSPATH . 'wp-admin/includes/media.php';
							$attach_id = media_handle_upload( 'file', $order_id );
							if ( is_wp_error( $attach_id ) ) {
								$errors = $settings['mes_error_file_upload'];
							} else {
								$save_data['file'] = $attach_id;
							}
						}
					} elseif ( $settings[ 'field_file_required' ] ) {
						$errors = $settings['mes_error_file_required'];
					}
				} elseif ( $settings[ 'field_file_required' ] ) {
					$errors = $settings['mes_error_file_required'];
				}
			}

			if ( ! empty( $errors ) ) {
				$this->throw_error( $errors );
				exit;
			} else {
				$mailer = WC()->mailer();

				$save_data['submit_date'] = current_time( 'Y-m-d H:i:s' );

				// before submit new confirmation.
				$save_data = apply_filters( 'tpc_before_submit_confirmation', $save_data );

				$order->update_meta_data( 'tpc_data', $save_data );
				$order->save();

				/*
				 * @hooked TPC_Email_Accepted_Payment::trigger() Sends notification email to admin.
				 * @hooked TPC_Form::set_order_status() Set order status to Waiting Confirmation.
				 */
				do_action( 'tpc_submit_confirmation', $order_id, $save_data );

				wp_send_json( array(
					'error'		=> false,
					'message'	=> '<div class="success">' . esc_html( $settings['mes_success'] ) . '</div>'
				) );
				exit;
			}
		}

		/**
		 * Throw error to user
		 * 
		 * @param  array $errors Errors.
		 */
		private function throw_error( $errors ) {
			wp_send_json( array(
				'error'		=> true,
				'message'	=> '<div class="error">' . esc_html( $errors ) . '</div>'
			) );
		}

		/**
		 * Update order status.
		 *
		 * @param  int $order_id Order ID.
		 */
		public function set_order_status( $order_id ) {
			$order = wc_get_order( $order_id );
			$order->update_status( 'waiting-confirm', __( 'The customer sends the payment confirmation.', 'tpc' ) );
		}

	}

}
