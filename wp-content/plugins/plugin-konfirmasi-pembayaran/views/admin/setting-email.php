<div class="panels">
	<div class="left-panel">
		<table class="tpc-general-settings">
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Additional on-hold order email content', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<textarea name="tpc_setting[onhold_email_content]" class="tpc-field" rows="4"><?php echo esc_textarea( $settings['onhold_email_content'] ); ?></textarea>
					<p class="helper"><?php esc_html_e( 'Additional messages will be attached to the email sent to the customer after the order is placed. The placeholder [confirmation_url] will generate the confirmation page url.', 'tpc' ); ?></p>
				</td>
			</tr>
		</table>
		<div class="tpc-setting-info"><?php printf( __( 'There are also 3 email notifications for payment confirmation. An email will be sent to the admin when the customer submits a new payment confirmation, and 2 emails will be sent to the customers when the admin confirms or rejects the payment. You can change these emails setting in %s.', 'tpc' ), '<a href="'. admin_url( 'admin.php?page=wc-settings&tab=email' ) . '">WooCommerce Email Setting</a>' ) ?></div>
	</div>
</div>
<div class="clear"></div>