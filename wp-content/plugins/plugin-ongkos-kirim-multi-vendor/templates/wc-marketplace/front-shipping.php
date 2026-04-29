<div class="panel panel-default panel-pading pannel-outer-heading pokmv-vendor-shipping">
	<div class="panel-heading">
		<h3>Plugin Ongkos Kirim</h3>
	</div>
	<div class="panel-body panel-content-padding">
		<div class="form-group">
			<label class="control-label col-sm-3 col-md-3"><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></label>
			<div class="col-md-6 col-sm-9">
				<?php pokmv_generate_field( 'origin', $vendor_id ); ?>
			</div>  
		</div>
		<div class="form-group">
			<label class="control-label col-sm-3 col-md-3"><?php esc_html_e( 'Couriers', 'pokmv' ); ?></label>
			<div class="col-md-6 col-sm-9">
				<?php pokmv_generate_field( 'couriers', $vendor_id ); ?>
			</div>  
		</div>
	</div>
</div>
<script>
	jQuery(function($) {
		$(".pokmv-vendor-shipping").prependTo(".wcmp_shipping_form");
	});
</script>
