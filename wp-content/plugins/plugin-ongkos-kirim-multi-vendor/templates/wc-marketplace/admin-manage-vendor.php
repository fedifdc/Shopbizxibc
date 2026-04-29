<fieldset class="shipping-origin_wrapper">
	<p class="shipping-origin">
		<strong><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></strong>
	</p>
	<label class="screen-reader-text" for="pok-vendor-origin"><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></label>
	<label class="hint-container"></label>
	<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
</fieldset>
<fieldset class="shipping-couriers_wrapper">
	<p class="shipping-couriers">
		<strong><?php esc_html_e( 'Shipping couriers', 'pokmv' ); ?></strong>
	</p>
	<label class="screen-reader-text"><?php esc_html_e( 'Shipping couriers', 'pokmv' ); ?></label>
	<label class="hint-container"></label>
	<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
</fieldset>
