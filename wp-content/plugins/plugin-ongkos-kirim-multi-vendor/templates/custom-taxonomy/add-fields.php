<div class="form-fields pokmv-fields">
	<div class="form-field">
	    <label for="pok-vendor-origin"><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></label>
		<?php pokmv_generate_field( 'origin' ); ?>
	</div>

	<div class="form-field">
		<label><?php esc_html_e( 'Shipping couriers', 'pokmv' ); ?></label>
		<?php pokmv_generate_field( 'couriers' ); ?>
	</div>
</div>
