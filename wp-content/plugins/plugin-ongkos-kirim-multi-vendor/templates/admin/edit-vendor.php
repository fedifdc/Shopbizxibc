<div class="pok-report pokmv-vendors pok-setting">
	<form action="" method="post" class="pok-setting-form">
		<div class="pokmv-vendor-edit">

			<div class="pokmv-vendor-edit-side">
				<h5><?php esc_html_e( 'Shipping setting for', 'pokmv' ); ?></h5>
				<h4>
					<?php echo esc_html( $vendor->name ); ?>
					<?php
						$store_name = pokmv_get_vendor_store_name( $vendor->id );
					if ( $store_name !== $vendor->name ) {
						echo '(' . esc_html( $store_name ) . ')';
					}
					?>
				</h4>
			</div>

			<div class="sections-container">
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
							<select name="pokmv_vendor[origin]" id="pokmv-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Input city name...', 'pokmv' ); ?>">
								<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
								<?php foreach ( $origins as $city ) : ?>
									<option value="<?php echo esc_attr( $city->id ); ?>" <?php echo ! empty( $vendor_settings['origin'] ) && $vendor_settings['origin'] === $city->id ? 'selected' : ''; ?>><?php echo esc_html( $city->name ); ?></option>
								<?php endforeach; ?>
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
								<label for="setting-cour-<?php echo esc_attr( $courier ); ?>"><?php echo esc_html( $this->helper->get_courier_name( $courier ) ); ?>
									<img src="<?php echo esc_url( POK_PLUGIN_URL . '/assets/img/logo-' . $courier . '.png' ) ?>" alt="<?php echo esc_attr( $this->helper->get_courier_name( $courier ) ); ?>" title="<?php echo esc_attr( $this->helper->get_courier_name( $courier ) ); ?>">
								</label>
								<?php
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="setting-row">
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
						<div class="setting-sub-option options-specific-vendor-service <?php echo 'yes' === $vendor_settings['specific_service'] ? 'show' : ''; ?>">
							<?php foreach ( $services as $courier => $courier_services ) : ?>
								<div class="options-specific-vendor-service-<?php echo esc_attr( $courier ); ?> service-options">
									<h5><?php echo esc_html( $this->helper->get_courier_name( $courier ) ); ?></h5>
									<p>
										<?php
											$selected = array();
											foreach ( $courier_services as $key => $service ) {
												if ( in_array( $courier . '-' . $key, $vendor_settings['specific_service_option'], true ) ) {
													$selected[] = $service['name'];
												}
											}
											if ( ! empty( $selected ) ) {
												echo implode( ", ", $selected );
											} else {
												esc_html_e( "No service selected, click here to select services", "pok" );
											}
										?>
									</p>
									<div class="courier-service-options">
										<?php
										foreach ( $courier_services as $key => $service ) {
											?>
											<input type="checkbox" value="<?php echo esc_attr( $courier . '-' . $key ); ?>" data-short="<?php echo esc_attr( $service['name'] ); ?>" name="pokmv_vendor[specific_service_option][]" id="setting-service-<?php echo esc_attr( $courier . '-' . $key ); ?>" <?php echo in_array( $courier . '-' . $key, $vendor_settings['specific_service_option'], true ) ? 'checked' : ''; ?>>
											<label for="setting-service-<?php echo esc_attr( $courier . '-' . $key ); ?>"><?php echo esc_html( $service['name'] ); ?><?php echo ! empty( $service['desc'] ) ? ' - ' . esc_html( $service['desc'] ) : ''; ?></label>
											<?php
										}
										?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<?php do_action( 'pokmv_setting_vendor', $vendor ); ?>
			</div>

		</div>

		<div class="pok-setting-actions">
			<?php wp_nonce_field( 'update_vendor_setting', 'pok_action' ); ?>
			<input type="submit" value="<?php esc_attr_e( 'Save Setting', 'pokmv' ); ?>" class="button button-primary"> 
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=pok_setting&tab=vendor' ) ); ?>"><?php esc_html_e( 'Back to Vendor List', 'pokmv' ); ?></a>
		</div>

	</form>
</div>
