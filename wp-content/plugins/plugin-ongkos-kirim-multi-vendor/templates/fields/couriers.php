<?php
	$couriers = $core->get_courier( '', '' );
?>
<div class="pokmv-couriers <?php echo 8 < count( $couriers ) ? 'pro' : ''; ?>">
	<?php foreach ( $couriers as $courier ) : ?>
		<label>
			<input type="checkbox" name="pokmv_vendor[courier][]" value="<?php echo esc_attr( $courier ); ?>" <?php echo in_array( $courier, $vendor_setting['courier'], true ) ? 'checked' : ''; ?>>
			<?php echo esc_html( $helper->get_courier_name( $courier ) ); ?>
		</label>
	<?php endforeach; ?>
</div>
