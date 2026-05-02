<div class="form-field">
	<label for="pok-vendor-origin"><?php esc_html_e( 'Shipping origin: ', 'pokmv' ); ?></label>
	<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
</div>

<div class="form-field">
	<label><?php esc_html_e( 'Shipping couriers: ', 'pokmv' ); ?></label>
	<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
</div>
