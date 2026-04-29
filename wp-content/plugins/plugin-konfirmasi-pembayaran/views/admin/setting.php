<div class="wrap tpc-wrapper" id="tpc-setting">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Payment Confirmation Settings', 'tpc' ) ?></h1>
	<hr class="wp-header-end">
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
		<?php foreach ( $tabs as $key => $value ) : ?>
			<a href="#tab-<?php echo esc_attr( $key ) ?>" class="nav-tab nav-tab-<?php echo esc_attr( $key ) ?>"><?php echo esc_html( $value['label'] ); ?></a>
		<?php endforeach; ?>
		<div class="additional-tab">
			<a href="http://pustaka.tonjoostudio.com/plugins/tonjoo-payment-confirmation-id/" target="_blank"><span class="dashicons dashicons-book"></span> <?php esc_html_e( 'Documentation', 'tpc' ) ?></a>
			<a href="https://forum.tonjoostudio.com/thread-category/plugin-konfirmasi-pembayaran/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Support Forum', 'tpc' ) ?></a>
		</div>
	</nav>
	<div class="tpc-setting-content">
		<form action="" method="post" id="tpc-setting-form">
			<div class="tpc-tab-contents">
				<?php foreach ( $tabs as $key => $value ) : ?>
					<div class="tpc-tab-content" id="tab-<?php echo esc_attr( $key ) ?>">
						<?php call_user_func( $value['callback'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="clear"></div>
			<?php wp_nonce_field( 'update_setting', 'tpc_action' ); ?>
			<input type="submit" value="<?php esc_attr_e( 'Save Setting', 'tpc' ); ?>" class="button button-primary">
		</form>
	</div>
</div>
