<?php if ( 'rajaongkir' === $pok_setting['base_api'] ) : ?>
	<select name="pokmv_vendor[origin]" id="pok-vendor-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Select city', 'pokmv' ); ?>">
		<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
		<?php foreach ( $cities as $city ) : ?>
			<option value="<?php echo esc_attr( $city->city_id ); ?>" <?php echo ! empty( $vendor_setting['origin'] ) && intval( $vendor_setting['origin'] ) === intval( $city->city_id ) ? 'selected' : ''; ?>><?php echo esc_html( ( 'Kabupaten' === $city->type ? 'Kab. ' : 'Kota ' ) . $city->city_name . ', ' . $city->province ); ?></option>
		<?php endforeach; ?>
	</select>
<?php else : ?>
	<select name="pokmv_vendor[origin]" id="pok-vendor-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Select city', 'pokmv' ); ?>">
		<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
		<?php foreach ( $origins as $city ) : ?>
			<option value="<?php echo esc_attr( $city->id ); ?>" <?php echo ! empty( $vendor_setting['origin'] ) && intval( $vendor_setting['origin'] ) === intval( $city->id ) ? 'selected' : ''; ?>><?php echo esc_html( $city->name ); ?></option>
		<?php endforeach; ?>
	</select>
<?php endif; ?>
