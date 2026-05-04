<tr class="pokmv-shipping-origin">
	<th><?php esc_html_e( 'Shipping Origin', 'pokmv' ); ?></th>
	<td>
		<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
		<p class="description"><?php esc_html_e( 'Origin city of the shipping.', 'pokmv' ); ?></p>
	</td>
</tr>
<tr class="pokmv-wcvendor-shipping-couriers">
	<th><?php esc_html_e( 'Shipping Couriers', 'pokmv' ); ?></th>
	<td>
		<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
		<p class="description"><?php esc_html_e( 'Your shipping couriers', 'pokmv' ); ?></p>
	</td>
</tr>
