<div id="pokmv-shipping-origin">
	<p style="margin-bottom:5px;">
		<b><?php esc_html_e( 'Shipping Origin', 'pokmv' ); ?></b>
		<br/>
		<?php esc_html_e( 'Origin city of the shipping.', 'pokmv' ); ?>
	</p>

	<p>
		<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
	</p>
</div>
<div id="pokmv-shipping-courier">
	<p style="margin-bottom:5px;">
		<b><?php esc_html_e( 'Shipping Couriers', 'pokmv' ); ?></b>
	</p>

	<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
</div>
