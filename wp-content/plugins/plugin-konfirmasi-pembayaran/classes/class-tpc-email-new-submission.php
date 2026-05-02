<?php
/**
 * Admin Emailer Classes
 *
 * @package tpc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class TPC Email for New Submission
 */
class TPC_Email_New_Submission extends WC_Email {

	/**
	 * Constructor, set default email setting
	 */
	public function __construct() {
		$this->id             = 'tpc_new_submission';
		$this->title          = __( 'New payment confirmation', 'tpc' );
		$this->description    = __( 'Email sent to the admin when a customer submits a new payment confirmation.', 'tpc' );
		$this->template_html  = 'emails/admin-new-payment-confirmation.php';
		$this->email_type     = 'html';
		$this->template_base  = TPC_PATH . 'views/';
		$this->placeholders   = array(
			'{order_date}'   => '',
			'{order_number}' => '',
		);

		// Triggers for this email.
		add_action( 'tpc_submit_confirmation_notification', array( $this, 'trigger' ), 10, 2 );

		// Call parent constructor.
		parent::__construct();

		// Other settings.
		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );

		add_action( 'init', array( $this, 'prefix_remove_bank_details' ), 100 );
	}

	/**
	 * Get email subject.
	 *
	 * @return string
	 */
	public function get_default_subject() {
		return __( '[{site_title}]: Payment received for #{order_number}', 'tpc' );
	}

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'Payment received for #{order_number}', 'tpc' );
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param int            $order_id The order ID.
	 * @param WC_Order|false $order Order object.
	 */
	public function trigger( $order_id, $order = false ) {
		$this->setup_locale();

		if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
			$order = wc_get_order( $order_id );
		}

		$this->email_type = 'html';

		if ( is_a( $order, 'WC_Order' ) ) {
			$this->object                         = $order;
			$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
			$this->placeholders['{order_number}'] = $this->object->get_order_number();
		}

		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		$this->restore_locale();
	}

	/**
	 * Get content html.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			array(
				'order'              => $this->object,
				'email_heading'      => $this->get_heading(),
				'additional_content' => $this->get_additional_content(),
				'sent_to_admin'      => true,
				'plain_text'         => false,
				'email'              => $this,
			),
			'',
			TPC_PATH . 'views/'
		);
	}

	/**
	 * Default content to show below main email content.
	 *
	 * @since 3.7.0
	 * @return string
	 */
	public function get_default_additional_content() {
		return __( 'Go visit your site dashboard to check the details.', 'tpc' );
	}

	/**
	 * Initialise settings form fields.
	 */
	public function init_form_fields() {
		/* translators: %s: list of placeholders */
		$placeholder_text  = sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );
		$this->form_fields = array(
			'enabled'            => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'woocommerce' ),
				'default' => 'yes',
			),
			'recipient'          => array(
				'title'       => __( 'Recipient(s)', 'woocommerce' ),
				'type'        => 'text',
				/* translators: %s: WP admin email */
				'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'woocommerce' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
				'placeholder' => '',
				'default'     => '',
				'desc_tip'    => true,
			),
			'subject'            => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_subject(),
				'default'     => '',
			),
			'heading'            => array(
				'title'       => __( 'Email heading', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $placeholder_text,
				'placeholder' => $this->get_default_heading(),
				'default'     => '',
			),
			'additional_content' => array(
				'title'       => __( 'Additional content', 'woocommerce' ),
				'description' => __( 'Text to appear below the main email content.', 'woocommerce' ) . ' ' . $placeholder_text,
				'css'         => 'width:400px; height: 75px;',
				'placeholder' => __( 'N/A', 'woocommerce' ),
				'type'        => 'textarea',
				'default'     => $this->get_default_additional_content(),
				'desc_tip'    => true,
			)
		);
	}

	/**
	 * Remove Bank Account Details for mail (payment_confirmation)
	 *
	 * @return [type] [description]
	 */
	public function prefix_remove_bank_details() {

		// Do nothing, if WC_Payment_Gateways does not exist.
		if ( ! class_exists( 'WC_Payment_Gateways' ) ) {
			return;
		}

		// Get the gateways instance.
		$gateways = WC_Payment_Gateways::instance();

		// Get all available gateways, [id] => Object.
		$available_gateways = $gateways->get_available_payment_gateways();

		if ( isset( $available_gateways['bacs'] ) ) {
			// If the gateway is available, remove the action hook.
			remove_action( 'woocommerce_email_before_order_table', array( $available_gateways['bacs'], 'email_instructions' ), 10, 3 );
		}
	}

}

return new TPC_Email_New_Submission();