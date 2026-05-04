<div class="pok-report pokmv-vendors pok-setting">
	<form action="" method="post" class="pok-setting-form">

		<?php do_action( 'pokmv_admin_edit_vendor_before', $vendor ); ?>

		<div class="setting-section" id="basic">
			<a class="back" href="<?php echo esc_url( admin_url( 'admin.php?page=pok_setting&tab=vendor' ) ); ?>"><span class="dashicons dashicons-arrow-left"></span><?php esc_html_e( 'Back to Vendor List', 'pokmv' ); ?></a>
			<h4 class="section-title">
				<?php esc_html_e( 'Shipping setting for : ', 'pokmv' ); ?>
				<?php echo esc_html( $vendor->name ); ?>
				<?php
					$store_name = pokmv_get_vendor_store_name( $vendor->id );
				if ( $store_name !== $vendor->name ) {
					echo '(' . esc_html( $store_name ) . ')';
				}
				?>
			</h4>
			<div class="setting-table">
				<div class="setting-row">
					<div class="setting-index">
						<label for="pokmv-store_location"><?php esc_html_e( 'Vendor Location', 'pokmv' ); ?></label>
						<p class="helper"><?php esc_html_e( 'Location of the vendor', 'pokmv' ); ?></p>
					</div>
					<div class="setting-option">
						<?php if ( 'rajaongkir' === $settings['base_api'] ) : ?>
							<select name="pokmv_vendor[origin]" id="pokmv-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Select city', 'pokmv' ); ?>">
								<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
								<?php foreach ( $cities as $city ) : ?>
									<option value="<?php echo esc_attr( $city->city_id ); ?>" <?php echo $vendor_settings['origin'] === $city->city_id ? 'selected' : ''; ?>><?php echo esc_html( ( 'Kabupaten' === $city->type ? 'Kab. ' : 'Kota ' ) . $city->city_name . ', ' . $city->province ); ?></option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<select name="pokmv_vendor[origin]" id="pokmv-origin" class="select2-ajax" data-action="pok_search_city" data-nonce="<?php echo esc_attr( wp_create_nonce( 'search_city' ) ); ?>" placeholder="<?php esc_attr_e( 'Input city name...', 'pokmv' ); ?>">
								<?php
								if ( ! empty( $vendor_settings['origin'] ) ) {
									?>
									<option selected value="<?php echo esc_attr( $vendor_settings['origin'] ); ?>"><?php echo esc_html( $this->core->get_single_city( $vendor_settings['origin'] ) ); ?></option>
									<?php
								}
								?>
							</select>
						<?php endif; ?>
					</div>
				</div>
				<div class="setting-row">
					<div class="setting-index">
						<label for="pokmv-couriers"><?php esc_html_e( 'Couriers', 'pokmv' ); ?></label>
						<p class="helper">
							<?php esc_html_e( 'Select couriers that used by vendor', 'pokmv' ); ?>
						</p>
					</div>
					<div class="setting-option">
						<div class="courier-options pro">
							<?php
							foreach ( $all_couriers as $courier ) {
								if ( in_array( $courier, $couriers, true ) ) {
								?>
								<input type="checkbox" value="<?php echo esc_attr( $courier ); ?>" name="pokmv_vendor[courier][]" id="setting-cour-<?php echo esc_attr( $courier ); ?>" <?php echo in_array( $courier, $couriers, true ) && in_array( $courier, $vendor_settings['courier'], true ) ? 'checked' : ''; ?>>
								<label for="setting-cour-<?php echo esc_attr( $courier ); ?>"><?php echo esc_html( $this->helper->get_courier_name( $courier ) ); ?></label>
								<?php
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="setting-row" id="section-specific-services">
					<div class="setting-index">
						<label><?php esc_html_e( 'Filter Courier Services', 'pokmv' ); ?></label>
						<p class="helper"><?php esc_html_e( 'Use specific services for each courier', 'pokmv' ); ?></p>
					</div>
					<div class="setting-option">
						<div class="toggle">
							<input type="radio" name="pokmv_vendor[specific_service]" id="pokmv-specific_service-no" <?php echo 'no' === $vendor_settings['specific_service'] ? 'checked' : ''; ?> value="no">
							<label for="pokmv-specific_service-no"><?php esc_html_e( 'No', 'pokmv' ); ?></label>
							<input type="radio" name="pokmv_vendor[specific_service]" id="pokmv-specific_service-yes" <?php echo 'yes' === $vendor_settings['specific_service'] ? 'checked' : ''; ?> value="yes">
							<label for="pokmv-specific_service-yes"><?php esc_html_e( 'Yes', 'pokmv' ); ?></label>
						</div>
						<div class="setting-sub-option options-specific-service <?php echo 'yes' === $vendor_settings['specific_service'] ? 'show' : ''; ?>">
							<?php foreach ( $services as $courier => $courier_services ) : ?>
								<?php asort( $courier_services ); ?>
								<div class="options-specific-service-<?php echo esc_attr( $courier ); ?> courier-options">
									<p><?php echo esc_html( $this->helper->get_courier_name( $courier ) ); ?></p>
									<div class="courier-service-options">
										<?php
										foreach ( $courier_services as $key => $service ) {
											?>
											<input type="checkbox" value="<?php echo esc_attr( $courier . '-' . $key ); ?>" name="pokmv_vendor[specific_service_option][]" id="setting-service-<?php echo esc_attr( $courier . '-' . $key ); ?>" <?php echo in_array( $courier . '-' . $key, $vendor_settings['specific_service_option'], true ) ? 'checked' : ''; ?>>
											<label for="setting-service-<?php echo esc_attr( $courier . '-' . $key ); ?>"><?php echo esc_html( $service['long'] ); ?></label>
											<?php
										}
										?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php do_action( 'pokmv_admin_edit_vendor_before', $vendor ); ?>

		<?php wp_nonce_field( 'update_vendor_setting', 'pok_action' ); ?>
		<input type="submit" value="<?php esc_attr_e( 'Save Setting', 'pokmv' ); ?>" class="button button-primary">

	</form>
</div>
