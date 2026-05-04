<div class="panels">
	<div class="left-panel">
		<table class="tpc-general-settings">

			<?php do_action( 'tpc_general_setting_start', $this->setting ); ?>

			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Confirmation Page', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<?php if ( $page = $this->setting->get_confirmation_page() ) : ?>
						<?php if ( ! $this->license_handler->is_license_active() ) : ?>
							<div style="margin-bottom: 5px;">
								<span class="warning"><?php esc_html_e( 'Confirmation Page are unavailable until the license is activated. Please activate the license.', 'tpc' ); ?></span><br>
							</div>
						<?php endif; ?>
						<div style="margin-bottom: 5px;">
							<strong><?php echo esc_html( $page->post_title ) ?></strong> (<code><?php echo esc_url( get_permalink( $page ) ) ?></code>)
						</div>
						<a class="button button-small" href="<?php echo esc_url( admin_url( 'post.php?post=' . $page->ID . '&action=edit' ) ) ?>"><?php esc_html_e( 'Edit Page', 'tpc' ) ?></a>
						<a class="button button-small" href="<?php echo esc_url( get_permalink( $page ) ) ?>" target="_blank"><?php esc_html_e( 'View Page', 'tpc' ) ?></a>
					<?php else: ?>
						<div style="margin-bottom: 5px;">
							<p style="margin-top: 0; font-weight: 700; color: #dc3232;"><?php esc_html_e( 'Confirmation page is not found.', 'tpc' ); ?></p>
							<p><?php printf( __( 'Create the page with clicking <strong>Generate Page</strong> or create a page with following shortcode: %s', 'tpc' ), '<code>[payment-confirmation]</code>' ) ?></p>
						</div>
						<button type="button" class="button" id="tpc-generate-page"><?php esc_html_e( 'Generate Page', 'tpc' ) ?></button>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Payment Methods', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<?php $gateways = WC()->payment_gateways->get_available_payment_gateways(); ?>
					<div class="tpc-checks">
						<?php foreach ( $gateways as $key => $gateway ) : ?>
							<label><input type="checkbox" name="tpc_setting[payment_methods][]" value="<?php echo esc_attr( $key ); ?>" <?php echo in_array( $key, $settings['payment_methods'] ) ? 'checked' : ''; ?>> <?php echo esc_html( $gateway->title ); ?></label>
						<?php endforeach; ?>
					</div>
					<p class="helper"><?php esc_html_e( 'Give a check on the payment method that needs confirmation.', 'tpc' ) ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Confirmed Order Status', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<?php $statuses = wc_get_order_statuses(); ?>
					<select name="tpc_setting[confirmed_status]" class="tpc-field">
						<?php foreach ( $statuses as $key => $status ) : ?>
							<option value="<?php echo esc_attr( $key ) ?>" <?php echo $key === $settings['confirmed_status'] ? 'selected' : ''; ?>><?php echo esc_html( $status ) ?></option>
						<?php endforeach; ?>
					</select>
					<p class="helper"><?php esc_html_e( 'Set order status after admin confirm the payment.', 'tpc' ) ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Rejected Order Status', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<?php $statuses = wc_get_order_statuses(); ?>
					<select name="tpc_setting[rejected_status]" class="tpc-field">
						<?php foreach ( $statuses as $key => $status ) : ?>
							<option value="<?php echo esc_attr( $key ) ?>" <?php echo $key === $settings['rejected_status'] ? 'selected' : ''; ?>><?php echo esc_html( $status ) ?></option>
						<?php endforeach; ?>
					</select>
					<p class="helper"><?php esc_html_e( 'Set order status after admin reject the payment.', 'tpc' ) ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Make The Form Only Accessible to Logged In Users', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<div class="tpc-toggle">
						<input <?php echo $settings['must_login'] ? 'checked' : ''; ?> type="checkbox" name="tpc_setting[must_login]" id="toggle-must_login" value="1">
						<label for="toggle-must_login"></label>
					</div>
					<p class="helper"><?php esc_html_e( 'This feature will prevent spams from unauthorized users.', 'tpc' ) ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Submission Limit', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="number" min="0" name="tpc_setting[submission_limit]" class="tpc-field" value="<?php echo esc_attr( $settings['submission_limit'] ) ?>">
					<p class="helper"><?php esc_html_e( 'Limit how many times the customer can send the payment confirmation for each order. The limits will prevent spam on the form. Leave it empty if you dont want to limit the submission.', 'tpc' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Redirect After Submission', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="url" name="tpc_setting[redirection]" class="tpc-field" value="<?php echo esc_attr( $settings['redirection'] ) ?>">
					<p class="helper"><?php printf( __( 'Fill this field with your custom URL if you want redirect user after successful form submission. You can also use %s on the URL to generate the Order ID.', 'tpc' ), '<code>[order_id]</code>' ); ?></p>
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Thankyou Page Content', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<textarea name="tpc_setting[thankyou_content]" class="tpc-field" rows="4"><?php echo esc_textarea( $settings['thankyou_content'] ); ?></textarea>
					<p class="helper"><?php esc_html_e( 'Message that will appear on the thankyou page. The placeholder [confirmation_url] will generate the confirmation page url.', 'tpc' ); ?></p>
				</td>
			</tr>
			<!-- <tr>
				<td class="index">
					<label><?php esc_html_e( 'Report Columns', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<div class="tpc-checks">
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="order_date" <?php echo in_array( 'order_date', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Order Date', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="order_total" <?php echo in_array( 'order_total', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Order Total', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="payment_amount" <?php echo in_array( 'payment_amount', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Transfer Amount', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="payment_date" <?php echo in_array( 'payment_date', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Transfer Date', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="bank" <?php echo in_array( 'bank', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Bank', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="file" <?php echo in_array( 'file', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Photo', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="order_status" <?php echo in_array( 'order_status', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Order Status', 'tpc' ) ?></label>
						<label><input type="checkbox" name="tpc_setting[report_columns][]" value="payment_status" <?php echo in_array( 'payment_status', $settings['report_columns'] ) ? 'checked' : ''; ?>> <?php esc_html_e( 'Payment Status', 'tpc' ) ?></label>
					</div>
					<p class="helper"><?php esc_html_e( 'Select columns you want to display in the report page.', 'tpc' ); ?></p>
				</td>
			</tr> -->

			<?php do_action( 'tpc_general_setting_end', $this->setting ); ?>

		</table>
	</div>
</div>
<div class="clear"></div>