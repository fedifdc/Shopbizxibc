<div class="setting-row">
	<div class="setting-index">
		<label for="pok-enable-multi-vendor"><?php esc_html_e( 'Enable Multi Vendor Shipping', 'pokmv' ); ?></label>
		<p class="helper"><?php esc_html_e( 'If disabled, shipping costs will be calculated from main store location', 'pokmv' ); ?></p>
	</div>
	<div class="setting-option">
		<div class="toggle">
			<input type="radio" name="pok_setting[enable_multi_vendor]" id="pok-enable-multi-vendor-no" <?php echo 'no' === $settings['enable_multi_vendor'] ? 'checked' : ''; ?> value="no">
			<label for="pok-enable-multi-vendor-no"><?php esc_html_e( 'No', 'pokmv' ); ?></label>
			<input type="radio" name="pok_setting[enable_multi_vendor]" id="pok-enable-multi-vendor-yes" <?php echo 'yes' === $settings['enable_multi_vendor'] ? 'checked' : ''; ?> value="yes">
			<label for="pok-enable-multi-vendor-yes"><?php esc_html_e( 'Yes', 'pokmv' ); ?></label>
		</div>
		<div class="setting-sub-option show">
			<p><?php esc_html_e( 'Detected multi vendor platform:', 'pokmv' ); ?></p>
			<h4 class="pokmv-status">
				<?php
				$platform = pokmv_get_active_multivendor_plugin();
				switch ( $platform ) {
					case 'dokan':
						echo 'Dokan';
						break;
					case 'wc-marketplace':
						echo 'WC Marketplace';
						break;
					case 'yith-vendors':
						echo 'YITH WooCommerce Multi Vendor';
						break;
					case 'wc-vendors':
						echo 'WC Vendors Marketplace';
						break;
					case 'product-vendors':
						echo 'WooCommerce Product Vendors';
						break;
					case 'wcfm':
						echo 'WCFM - WooCommerce Multivendor Marketplace';
						break;
					default:
						echo 'Plugin Ongkos Kirim Default';
						break;
				}
				?>
			</h4>
			<?php if ( 'pokmv' === $platform ) : ?>
				<p class="helper"><?php esc_html_e( 'No third-party multi vendor platform are detected, vendor management are using vendor taxonomy on Products.', 'pokmv' ); ?></p>
				<p class="helper"><?php esc_html_e( 'Supported third-party multi vendor platform:', 'pokmv' ); ?> Dokan, WC Marketplace, WC Vendors Marketplace, WooCommerce Product Vendors, WooCommerce Multivendor Marketplace</p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if ( 'yes' === $this->setting->get('enable_multi_vendor') ) : ?>
	<div class="setting-row">
		<div class="setting-index">
			<label for="pok-allowed-vendor-setting"><?php esc_html_e( 'Vendor Shipping Setting', 'pokmv' ); ?></label>
			<p class="helper"><?php esc_html_e( "Set vendor's shipping origin and shipping couriers.", 'pokmv' ); ?></p>
		</div>
		<div class="setting-option">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=pok_setting&tab=vendor' ) ) ?>" class="button"><?php esc_html_e( 'Manage Vendors', 'pokmv' ) ?></a>
		</div>
	</div>
<?php endif; ?>