<?php
/**
 * default-options.php
 */
$plugin_resi_options = get_option( 'plugin_resi_options' );

for ( $i = 1; $i <= 5; $i++ ) {
	if ( ! isset( $plugin_resi_options[ 'extra_courier_name_' . $i ] ) ) {
		$plugin_resi_options[ 'extra_courier_name_' . $i ] = '';
	}

	if ( ! isset( $plugin_resi_options[ 'extra_courier_website_' . $i ] ) ) {
		$plugin_resi_options[ 'extra_courier_website_' . $i ] = '';
	}
}

if ( ! isset( $plugin_resi_options['email_to_admin'] ) ) {
	$plugin_resi_options['email_to_admin'] = 'yes';
}

if ( ! isset( $plugin_resi_options['email_subject'] ) ) {
	$plugin_resi_options['email_subject'] = '';
}

if ( ! isset( $plugin_resi_options['email_body'] ) ) {
	$plugin_resi_options['email_body'] = '';
}
