<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage the email send to customer when the funds are under a threshold
 *
 * @package YITH\AccountFunds\Classes\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Customer_Email' ) ) {
	/**
	 * Class YITH_YWF_Customer_Email
	 */
	class YITH_YWF_Customer_Email extends WC_Email {

		/**
		 * The email content
		 *
		 * @var string
		 */
		protected $email_content;

		/**
		 * YITH_YWF_Customer_Email constructor.
		 */
		public function __construct() {
			$this->id             = 'yith_user_funds_email';
			$this->customer_email = true;
			$this->title          = __( 'Funds email', 'yith-woocommerce-account-funds' );
			$this->description    = __( 'This email is sent to customers when the administrator changes their available fund amount', 'yith-woocommerce-account-funds' );

			$this->heading = $this->get_option( 'ywf_user_heading' );
			$this->subject = $this->get_option( 'ywf_mail_subject' );

			$this->template_base  = YITH_FUNDS_TEMPLATE_PATH;
			$this->template_html  = 'emails/email-user-funds.php';
			$this->template_plain = 'emails/plain/email-user-funds.php';
			$this->placeholders   = array(
				'{site_title}'      => '',
				'{user_funds}'      => '',
				'{customer_email}'  => '',
				'{customer_name}'   => '',
				'{button_charging}' => '',
			);

			// Triggers for this email.
			add_action( 'ywf_send_user_fund_email_notification', array( $this, 'trigger' ), 10, 1 );
			parent::__construct();
		}

		/**
		 * Send email
		 *
		 * @param int $user_id The user id.
		 *
		 * @since 1.0.0
		 * @author YITH <plugins@yithemes.com>
		 */
		public function trigger( $user_id ) {
			$this->setup_locale();
			$email_is_sent = get_user_meta( $user_id, '_user_mail_send', true );

			if ( ( empty( $email_is_sent ) || 'no' === $email_is_sent ) && apply_filters( 'ywf_send_email', true, $user_id ) ) {

				$user         = get_user_by( 'id', $user_id );
				$this->object = $user;

				$user_email = $user->user_email;
				$user_name  = $user->display_name;

				$customer        = new YITH_YWF_Customer( $user_id );
				$this->recipient = $user_email;
				$email_content   = $this->get_option( 'ywf_mail_content' );

				$order_id = ywf_get_user_currency( $user_id );
				$currency = get_post_meta( $order_id, '_order_currency', true );

				$funds = apply_filters( 'yith_fund_into_customer_email', $customer->get_funds(), $currency );

				$endpoint = yith_account_funds_get_endpoint_slug( 'make-a-deposit' );
				$url      = esc_url( wc_get_page_permalink( 'myaccount' ) . $endpoint );
				$link     = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $url );

				$this->placeholders['{site_title}']      = $this->get_blogname();
				$this->placeholders['{customer_name}']   = $user_name;
				$this->placeholders['{customer_email}']  = $user_email;
				$this->placeholders['{user_funds}']      = wc_price( $funds, array( 'currency' => $currency ) );
				$this->placeholders['{button_charging}'] = $link;
				$this->email_content                     = nl2br( $this->format_string( $email_content ) );

				if ( $this->is_enabled() && $this->get_recipient() ) {

					$send = $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

					$meta_value = $send ? 'yes' : 'no';

					update_user_meta( $user_id, '_user_mail_send', $meta_value );
				}
			}
			$this->restore_locale();
		}

		/**
		 * Get the email type
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function get_email_type() {
			return $this->get_option( 'ywf_mail_type' );
		}


		/**
		 * Get HTML content for the mail
		 *
		 * @return string HTML content of the mail
		 * @since  1.0
		 */
		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'email_heading' => $this->get_heading(),
					'email_content' => $this->email_content,
					'sent_to_admin' => false,
					'plain_text'    => false,
					'email'         => $this,
				),
				false,
				$this->template_base
			);
		}

		/**
		 * Get_headers function.
		 *
		 * @return string
		 */
		public function get_headers() {

			$headers = 'Content-Type: ' . $this->get_content_type() . "\r\n";

			return apply_filters( 'woocommerce_email_headers', $headers, $this->id, $this->object, $this );
		}


		/**
		 * Get plain content for the mail
		 *
		 * @return string plain content of the mail
		 * @since  1.0
		 */
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_plain,
				array(
					'email_heading' => $this->get_heading(),
					'email_content' => $this->email_content,
					'sent_to_admin' => false,
					'plain_text'    => true,
					'email'         => $this,
				),
				false,
				YITH_FUNDS_TEMPLATE_PATH
			);
		}

		/**
		 * Check if this email is enabled
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_enabled() {
			$enabled = $this->get_option( 'ywf_mail_enabled' );

			return apply_filters( 'woocommerce_email_enabled_' . $this->id, 'yes' === $enabled, $this->object, $this );
		}

		/**
		 * Get email subject.
		 *
		 * @return  string
		 * @since   1.4.0
		 */
		public function get_subject() {
			return apply_filters( 'woocommerce_email_subject_' . $this->id, $this->format_string( $this->get_option( 'ywf_mail_subject', $this->get_default_subject() ) ), $this->object, $this );
		}

		/**
		 * Get heading
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function get_heading() {

			return apply_filters( 'woocommerce_email_heading_' . $this->id, $this->format_string( $this->get_option( 'ywf_user_heading', $this->get_default_heading() ) ), $this->object, $this );

		}

		/**
		 * Admin Panel Options Processing - Saves the options to the DB
		 *
		 * @return  void
		 * @since   1.6.0
		 */
		public function process_admin_options() {
			woocommerce_update_options( $this->form_fields );
		}

		/**
		 * Override option key.
		 *
		 * @param string $key The option key.
		 *
		 * @return  string
		 * @since   1.6.0
		 *
		 */
		public function get_field_key( $key ) {
			return $key;
		}

		/**
		 * Get plugin option.
		 *
		 * @param string $key The option key.
		 * @param mixed  $empty_value The value.
		 *
		 * @return  mixed
		 * @since   1.6.0
		 */
		public function get_option( $key, $empty_value = null ) {

			$setting = get_option( $key );

			// Get option default if unset.
			if ( ! $setting ) {
				$form_fields = $this->get_form_fields();
				$setting     = isset( $form_fields[ $key ] ) ? $this->get_field_default( $form_fields[ $key ] ) : '';
			}

			return $setting;

		}

		/**
		 * Initialise Settings Form Fields
		 *
		 * @return  void
		 * @since   1.0.0
		 */
		public function init_form_fields() {
			$options = include YITH_FUNDS_DIR . '/plugin-options/emails-multi-tab/deposit-email-options.php';
			$options = $options['emails-multi-tab-deposit-email'];

			foreach ( $options as $key => $option ) {

				if ( isset( $option['type'] ) && ! in_array( $option['type'], array( 'title', 'sectionend' ), true ) ) {

					$new_option = $option;

					$new_option['type']        = 'onoff' === $option['yith-type'] ? 'checkbox' : $option['yith-type'];
					$new_option['title']       = $option['name'];
					$new_option['description'] = ! empty( $option['desc'] ) ? $option['desc'] : '';

					if ( 'number' === $new_option['type'] ) {

						$new_option['custom_attributes'] = array(
							'min'  => $new_option['min'],
							'step' => $new_option['step'],
						);
					}

					unset( $new_option['yith-type'] );
					unset( $new_option['name'] );
					unset( $new_option['desc'] );
					$this->form_fields[ $new_option['id'] ] = $new_option;
				}
			}
		}

	}
}

return new YITH_YWF_Customer_Email();
