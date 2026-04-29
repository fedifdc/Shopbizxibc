<?php
/**
 * HTML Template Email Funds
 *
 * @package YITH\AccountFunds\Templates\Emails
 * @since   1.0.0
 * @author YITH <plugins@yithemes.com>
 */

do_action( 'woocommerce_email_header', $email_heading, $email );
echo wp_kses_post( wpautop( wptexturize( get_option( 'ywf_mail_change_fund_content' ) ) ) );
do_action( 'woocommerce_email_footer', $email );
