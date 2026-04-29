<div class="panels">
	<div class="left-panel">
		<table class="tpc-messages-settings">

			<?php do_action( 'tpc_messages_setting_start', $this->setting ); ?>

			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Successful Submission', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_success]" value="<?php echo esc_attr( $settings['mes_success'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Failed Submission', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error]" value="<?php echo esc_attr( $settings['mes_error'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Required Fields', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_required]" value="<?php echo esc_attr( $settings['mes_error_required'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Email Format', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_email]" value="<?php echo esc_attr( $settings['mes_error_email'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Order Not Found', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_order_id]" value="<?php echo esc_attr( $settings['mes_error_order_id'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Order & Email Not Found', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_order_email]" value="<?php echo esc_attr( $settings['mes_error_order_email'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Pending Order & Email Not Found', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_order_status]" value="<?php echo esc_attr( $settings['mes_error_order_status'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Date Format', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_date]" value="<?php echo esc_attr( $settings['mes_error_date'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Required File', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_file_required]" value="<?php echo esc_attr( $settings['mes_error_file_required'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - File Size', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_file_size]" value="<?php echo esc_attr( $settings['mes_error_file_size'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - File Type', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_file_type]" value="<?php echo esc_attr( $settings['mes_error_file_type'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Failed to Upload', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_file_upload]" value="<?php echo esc_attr( $settings['mes_error_file_upload'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Recaptcha Required', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_recaptcha_required]" value="<?php echo esc_attr( $settings['mes_error_recaptcha_required'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Invalid Recaptcha', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_recaptcha_failed]" value="<?php echo esc_attr( $settings['mes_error_recaptcha_failed'] ); ?>">
				</td>
			</tr>
			<tr>
				<td class="index">
					<label><?php esc_html_e( 'Error - Submission Limit Reached', 'tpc' ); ?></label>
				</td>
				<td class="value">
					<input type="text" class="tpc-field" name="tpc_setting[mes_error_limit]" value="<?php echo esc_attr( $settings['mes_error_limit'] ); ?>">
				</td>
			</tr>

			<?php do_action( 'tpc_messages_setting_end', $this->setting ); ?>

		</table>
	</div>
</div>
<div class="clear"></div>