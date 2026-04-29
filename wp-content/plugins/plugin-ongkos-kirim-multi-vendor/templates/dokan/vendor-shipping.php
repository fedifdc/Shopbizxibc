<?php if ( pokmv_is_dokan_pro_active() ) : ?>
	<hr>
	<h3><?php esc_html_e( 'Shipping setting for Plugin Ongkos Kirim', 'pokmv' ) ?></h3>
<?php endif; ?>

<form method="post" id="shipping-form" action="" class="dokan-form-horizontal">

	<?php wp_nonce_field( 'dokan_shipping_settings_nonce' ); ?>

	<div class="dokan-form-group">
		<label class="dokan-w3 dokan-control-label" for="pok-vendor-origin"><?php esc_html_e( 'Shipping Origin', 'pokmv' ); ?></label>
		<div class="dokan-w9 dokan-text-left">
			<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
		</div>
	</div>

	<div class="dokan-form-group">
		<label class="dokan-w3 dokan-control-label" for="pok-vendor-courier"><?php esc_html_e( 'Couriers', 'pokmv' ); ?></label>
		<div class="dokan-w9 dokan-text-left">
			<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
		</div>
	</div>

	<div class="dokan-form-group">
		<div class="dokan-w4 ajax_prev dokan-text-left" style="margin-left:24%;">
			<input type="submit" name="dokan_update_shipping_settings" class="dokan-btn dokan-btn-danger dokan-btn-theme" value="<?php esc_attr_e( 'Update Settings', 'pokmv' ); ?>">
		</div>
	</div>

</form>