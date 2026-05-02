<div id="tpc-data">
	<div class="view">
		
		<?php include TPC_PATH . 'views/admin/metabox-order-view.php'; ?>

	</div>
	<div class="edit">
		<?php foreach ( $fields as $key => $field ) : ?>
			<?php if ( ! $field['required'] && 'file' !== $key ) : ?>
				<label>
					<?php echo esc_html( $field['name'] ); ?>
					<?php if ( 'note' === $key ) : ?>
						<textarea name="tpc_field[<?php echo esc_attr( $key ) ?>]"><?php echo isset( $confirm[ $key ] ) ? esc_textarea( $confirm[ $key ] ) : ''; ?></textarea>
					<?php elseif ( 'bank' === $key ) : ?>
						<?php
							$options = $this->setting->get('field_bank_options');
							$options = array_map( 'trim', explode( ',', $options ) );
						?>
						<select name="tpc_field[<?php echo esc_attr( $key ) ?>]">
							<option value=""><?php echo esc_html( '- Not Set -', 'tpc' ); ?></option>
							<?php foreach ( $options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php echo isset( $confirm[ $key ] ) && $confirm[ $key ] === $option ? 'selected' : ''; ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'transfer_date' === $key ) : ?>
						<input type="text" class="tpc-datepicker" name="tpc_field[<?php echo esc_attr( $key ) ?>]" value="<?php echo isset( $confirm[ $key ] ) ? esc_attr( $confirm[ $key ] ) : ''; ?>">
					<?php elseif ( 'amount' === $key ) : ?>
						<input type="number" name="tpc_field[<?php echo esc_attr( $key ) ?>]" value="<?php echo isset( $confirm[ $key ] ) ? esc_attr( $confirm[ $key ] ) : ''; ?>">
					<?php else: ?>
						<input type="text" name="tpc_field[<?php echo esc_attr( $key ) ?>]" value="<?php echo isset( $confirm[ $key ] ) ? esc_attr( $confirm[ $key ] ) : ''; ?>">
					<?php endif; ?>
				</label>
			<?php endif; ?>
		<?php endforeach; ?>
		<label>
			<?php esc_html_e( 'Attached photo', 'tpc' ); ?>
			<input type="text" class="tpc-upload-image" readonly value="<?php echo isset( $confirm['file'] ) && ! empty( $confirm['file'] ) ? basename( get_attached_file( $confirm['file'] ) ) : ''; ?>">
		</label>
		<input type="hidden" name="tpc_field[file]" value="<?php echo isset( $confirm['file'] ) ? esc_attr( $confirm['file'] ) : ''; ?>">
		<input type="hidden" name="tpc_field[order_id]" value="<?php echo esc_attr( $order->get_id() ); ?>">
		<input type="hidden" name="tpc_field[customer_email]" value="<?php echo esc_attr( $order->get_billing_email() ); ?>">

		<?php do_action( 'tpc_metabox_order_edit', $confirm, $order ); ?>

		<table class="actions">
			<tr>
				<td><button class="button button-primary tpc-update" type="button"><?php esc_html_e( 'Update', 'tpc' ) ?></button></td>
				<td><button class="button toggle-edit" type="button"><?php esc_html_e( 'Cancel', 'tpc' ) ?></button></td>
			</tr>
		</table>
	</div>
</div>