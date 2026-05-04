<?php
/**
 * Plugin Name: Plugin Resi
 * Plugin URI: https://tonjoostudio.com/product/plugin-kirim-resi/
 * Description: Plugin untuk mengirim nomor resi pada pelanggan.
 * Version: 1.3.1
 * Author: Tonjoo Studio
 * Author URI: https://tonjoostudio.com/
 * License: GPLv2
 * WC requires at least: 3.0.0
 * WC tested up to: 9.0
 */

define( 'PLUGIN_RESI', 'plugin-resi' );
define( 'PLUGIN_RESI_PATH', plugin_dir_path( __FILE__ ) );


/**
 * Tonjoo License
 */
require_once plugin_dir_path( __FILE__ ) . 'libs/tonjoo-license/tonjoo-license-manager.php';

global $tonjoo_license_manager;
$license = $tonjoo_license_manager->register_plugin( 'plugin-resi', __FILE__ );
$option = get_option( 'plugin_resi_options' );
if ( isset( $option['license_key'] ) ) {
	$license_api = new Tonjoo_License_API( 'plugin-resi' );
	if ( ! empty( $option['license_key'] ) ) { // Input var okay.
		$activation = $license_api->activate( sanitize_text_field( wp_unslash( $option['license_key'] ) ) ); // Input var okay.
		if ( true === $activation['status'] ) {
			$status = $license_api->status( sanitize_text_field( wp_unslash( $option['license_key'] ) ) ); // Input var okay.
			if ( true === $status['status'] ) {
				if ( strtotime( $status['data']->validUntil ) < time() ) { // expired.
					$args = array(
						'active'        => false,
						'last_check'    => time(),
					);
				} else { // active.
					$args = array(
						'active'        => true,
						'key'           => sanitize_text_field( wp_unslash( $option['license_key'] ) ), // Input var okay.
						'type'          => $status['data']->licenseType,
						'expiry'        => strtotime( $status['data']->validUntil ),
						'last_check'    => time(),
						'check_attempt' => 0,
						'email'         => isset( $activation['activation_data']->email ) ? $activation['activation_data']->email : '',
						'activation_date' => time(),
					);
				}
			} else {
				$license_api->deactivate( sanitize_text_field( wp_unslash( $option['license_key'] ) ) ); // Input var okay.
				$args = array(
					'last_check' => time(),
				);
			}
		} else {
			$args = array(
				'last_check' => time(),
			);
		}
	} else {
		$args = array(
			'last_check' => time(),
		);
	}
	$license->set_status( $args );
	unset( $option['license_key'] );
	unset( $option['license_status'] );
	unset( $option['license_action'] );
	update_option( 'plugin_resi_options', $option );
}

add_action('before_woocommerce_init', function(){
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
});

require_once  'functions.php';

