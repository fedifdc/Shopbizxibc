<tr class="form-field">
	<th scope="row" valign="top">
		<label for="pok-vendor-origin"><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></label>
	</th>
	<td>
		<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php esc_html_e( 'Shipping couriers', 'pokmv' ); ?></label>
	</th>
	<td>
		<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
	</td>
</tr>
