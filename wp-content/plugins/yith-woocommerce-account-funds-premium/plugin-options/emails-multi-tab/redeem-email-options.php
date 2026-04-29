<?php
/**
 * Plugin Options : Redeem vendor funds email options
 *
 * @package YITH\AccountFunds\PluginOptions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$desc              = sprintf(/* translators: %s is the list of placeholders */
	__( 'Available placeholders: %s', 'yith-woocommerce-account-funds' ),
	'<code>' . implode( '</code>, <code>', array( '{site_title}', '{site_address}', '{date}' ) ) . '</code>'
);
$feature_available = defined( 'YITH_WPV_PREMIUM' ) && defined( 'YITH_WPV_VERSION' ) && version_compare( YITH_WPV_VERSION, '3.7.0', '>=' );

$settings = array(
	'emails-multi-tab-redeem-email' => array(
		'redeem_email_section_start' => array(
			'type' => 'title',
			'name' => __( 'Funds redeemed', 'yith-woocommerce-account-funds' ),
		),
		'redeem_email_description'   => $feature_available ? array() : array(
			'type'             => 'yith-field',
			'yith-type'        => 'html',
			'yith-display-row' => true,
			'html'             => sprintf(/* translators: %s is the text */
				'<p class="info-box">%s</p>',
				__( 'These features are available only with YITH WooCommerce MultiVendor Premium 3.7.0 or greater', 'yith-woocommerce-account-funds' )
			),
		),
		'redeem_email_enabled'       => array(
			'name'      => __( 'Enable/Disable', 'yith-woocommerce-account-funds' ),
			'id'        => 'ywf_redeem_email_enabled',
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
			'desc'      => __( 'If enabled, send an email to admin when one or more vendor redeem funds', 'yith-woocommerce-account-funds' ),
		),
		'redeem_email_recipient'     => array(
			'name'      => __( 'Recipient(s)', 'yith-woocommerce-account-funds' ),
			'id'        => 'ywf_redeem_email_recipient',
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => get_option( 'admin_email' ),
			'desc'      => sprintf(/* translators: %s are the default emails */
				__( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'yith-woocommerce-account-funds' ),
				'<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>'
			),
		),
		'redeem_email_subject'       => array(
			'name'      => __( 'Subject', 'yith-woocommerce-account-funds' ),
			'id'        => 'ywf_redeem_email_subject',
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( '[{site_title}] New Redeems', 'yith-woocommerce-account-funds' ),
			'desc'      => $desc,
		),
		'redeem_email_heading'       => array(
			'name'      => __( 'Email heading', 'yith-woocommerce-account-funds' ),
			'id'        => 'ywf_redeem_email_heading',
			'type'      => 'yith-field',
			'yith-type' => 'text',
			'default'   => __( 'New Redeems', 'yith-woocommerce-account-funds' ),
			'desc'      => $desc,
		),
		'redeem_email_type'          => array(
			'name'      => __( 'Email type', 'yith-woocommerce-account-funds' ),
			'type'      => 'yith-field',
			'yith-type' => 'select',
			'class'     => 'email_type wc-enhanced-select',
			'options'   => array(
				'html'  => __( 'HTML', 'yith-woocommerce-account-funds' ),
				'plain' => __( 'Plain text', 'yith-woocommerce-account-funds' ),
			),
			'desc'      => __( 'Select if the email is HTML or plain', 'yith-woocommerce-account-funds' ),
			'default'   => 'html',
			'id'        => 'ywf_redeem_email_type',
		),
		'redeem_email_section_end'   => array(
			'type' => 'sectionend',
		),
	),
);

return $settings;
