<style>
	.pokmv-couriers input {
		width: auto !important;
	}
	.pokmv-couriers label {
		display: block;
		margin-bottom: 0;
	}
	.pokmv-couriers.pro {
		column-count: 3;
	}
</style>
<h1><?php esc_html_e( 'Shipping Setup', 'pokmv' ); ?></h1>
<form method="post" class="dokan-seller-setup-form">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="store_ppp"><?php esc_html_e( 'Shipping Origin', 'pok' ); ?></label></th>
			<td>
				<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="store_ppp"><?php esc_html_e( 'Couriers', 'pok' ); ?></label></th>
			<td>
				<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
			</td>
		</tr>
	</table>
	<p class="wc-setup-actions step">
		<input type="submit" class="button-primary button button-large button-next store-step-continue" value="<?php esc_attr_e( 'Continue', 'pokmv' ); ?>" name="save_step" />
		<a href="<?php echo esc_url( add_query_arg( 'step', 'payment' ) ); ?>" class="button button-large button-next store-step-skip-btn"><?php esc_html_e( 'Skip this step', 'pokmv' ); ?></a>
		<?php wp_nonce_field( 'dokan-seller-setup' ); ?>
	</p>
</form>
<script>
	(function($) {

		$('.select2-ajax').each(function() {
			var action 	= $(this).data('action');
			var phrase	= $(this).val();
			var nonce 	= $(this).data('nonce');
			$(this).select2({
				ajax: {
					url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
					dataType: 'json',
					data: function( params ) {
						return {
							pok_action: nonce,
							action: action,
							q: params.term
						}
					},
					processResults: function (data, params) {
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 3,
				placeholder: $(this).attr('placeholder')
			});
		});

		$('.init-select2').each(function() {
			$(this).select2();
		});

	})(jQuery);
</script>
