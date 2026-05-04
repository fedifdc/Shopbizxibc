<table class="form-table plugin-resi-extra-courier">
	<tr>
		<th style="width:30px;"></th>
		<th><?php _e("Name",PLUGIN_RESI)  ?></th>
		<th><?php _e("Website",PLUGIN_RESI)  ?></th>
	</tr>
	<?php for ($i=1;$i<=5;$i++) : ?>
		<tr>
			<td><?php echo $i ?></td>
			<td><input type="text" name='plugin_resi_options[extra_courier_name_<?php echo $i ?>]' value='<?php echo $plugin_resi_options['extra_courier_name_'.$i] ?>' ></td>
			<td><input type="text" name='plugin_resi_options[extra_courier_website_<?php echo $i ?>]' value='<?php echo $plugin_resi_options['extra_courier_website_'.$i] ?>'></td>
		</tr>
	<?php endfor; ?>
</table>

<table class="form-table">
	<tbody>
	<!-- Test Email -->
	<tr><td colspan="3"><h3 class="meta-subtitle"><?php _e("Test Email",PLUGIN_RESI)  ?></h3></td></tr>
	<tr>
		<td><?php _e("Select Order", PLUGIN_RESI) ?></td>
		<td><?php plugin_resi_lib__post_select('send_test_order', 0, 10) ?></td>
	</tr>
	<tr>
		<td><?php _e("Send To", PLUGIN_RESI) ?></td>
		<td>
			<?php $current_user = wp_get_current_user(); ?>
			<input type="text" id="send_test_email" value="<?php echo $current_user->user_email ?>">
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<button id='send_test_submit' class="button">Send Email</button>
			<span id="send_test_notification"></span>
		</td>
	</tr>

	<!-- Other Settings -->
	<tr><td colspan="3"><h3 class="meta-subtitle"><?php _e("Other Settings",PLUGIN_RESI)  ?></h3></td></tr>
	<tr>
		<td><?php _e("Also Send Email to Admin", PLUGIN_RESI) ?></td>
		<td>
		<?php 
			$arr_data = array(
				0 => array(
					'label' => 'Yes',
					'value' => 'yes',
				),
				1 => array(
					'label' => 'No',
					'value' => 'no',
				)
			);

			plugin_resi_lib__print_select($arr_data, "plugin_resi_options[email_to_admin]", $plugin_resi_options['email_to_admin']);
		?></td>
	</tr>

	<!-- Email Template -->
	<tr><td colspan="3"><h3 class="meta-subtitle"><?php _e("Email Template",PLUGIN_RESI)  ?></h3></td></tr>
	<tr>
		<td><?php esc_html_e( 'Email Subject', 'plugin-resi' ); ?></td>
		<td><input type="text" name="plugin_resi_options[email_subject]" value="<?php echo esc_attr( $plugin_resi_options['email_subject'] ); ?>"></td>
	</tr>
	<tr>
		<td colspan="3">
			<?php esc_html_e( 'Email Body', 'plugin-resi' ); ?>
			<?php
			$settings = array(
				'editor_height' => 300,
				'media_buttons' => false,
				'textarea_name' => 'plugin_resi_options[email_body]',
			);
			wp_editor( html_entity_decode( $plugin_resi_options['email_body'] ), 'email_body', $settings );
			?>
		</td>
	</tr>
	<tr><td colspan="3">
		<p><?php esc_html_e( 'If Email Body is empty, these template files will be used:', 'plugin-resi' ); ?></p>
		<br>
		<ol>
			<li>plugin-resi/templates/email/order-shipped.php</li>
			<li><code><?php _e("your theme",PLUGIN_RESI)  ?></code>/templates/email/order-shipped.php</li>
		</ol>			
	</td></tr>
	</tbody>
</table>
