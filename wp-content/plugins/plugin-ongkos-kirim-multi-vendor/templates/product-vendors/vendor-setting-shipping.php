<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">

	<h2><?php esc_html_e( 'Shipping Settings', 'pokmv' ); ?></h2>
	
	<form id="wcpv-vendor-settings" action="" method="post">
		<input type="hidden" name="page" value="wcpv-vendor-settings-shipping"/>

		<table class="form-table">
			<tbody>
				
				<tr class="form-field">
					<th scope="row" valign="top"><label for="pok-vendor-origin"><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></label></th>
					
					<td>
						<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
					</td>
				</tr>

				<tr class="form-field">
					<th scope="row" valign="top"><label><?php esc_html_e( 'Shipping couriers', 'pokmv' ); ?></label></th>
					
					<td>
						<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
					</td>
				</tr>

			</tbody>
		</table>
		
		<?php wp_nonce_field( 'wcpv_save_vendor_settings', 'wcpv_save_vendor_settings_nonce' ); ?>
		<?php submit_button( __( 'Update', 'woocommerce-product-vendors' ), 'primary', 'submit' ); ?>
	</form>
</div>
		
