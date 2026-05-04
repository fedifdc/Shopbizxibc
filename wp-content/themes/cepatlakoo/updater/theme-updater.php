<?php
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://cepatlakoo.com', // Site where EDD is hosted
		'item_name'      => THEME_NAME, // Name of theme
		'theme_slug'     => THEME_SLUG, // Theme slug
		'version'        => THEME_VERSION, // The current version of this theme
		'author'         => 'Cepatlakoo', // The author of this theme
		'download_id'    => '126', // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
		'beta'           => false, // Optional, set to true to opt into beta versions
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Lisensi Tema' ),
		'enter-key'                 => __( 'Harap masukkan kode lisensi tema Anda agar mendapatkan notifikasi ketika ada versi tema baru.' ),
		'license-key'               => __( 'Kode Lisensi' ),
		'license-action'            => __( 'License Action' ),
		'deactivate-license'        => __( 'Non-aktifkan Lisensi' ),
		'activate-license'          => __( 'Aktifkan Lisensi' ),
		'status-unknown'            => __( 'License status is unknown.', 'cepatlakoo' ),
		'renew'                     => __( 'Perpanjang Lisensi?' ),
		'unlimited'                 => __( 'unlimited', 'cepatlakoo' ),
		'license-key-is-active'     => __( 'Lisensi aktif.' ),
		'expires%s'                 => __( 'Kadaluarsa akan pada %s.' ),
		'expires-never'             => __( 'Lifetime License.', 'cepatlakoo' ),
		'%1$s/%2$-sites'            => __( 'Anda telah mengaktifkan lisensi pada <span class="active-sites">%1$s / %2$s</span> situs.' ),
		'license-key-expired-%s'    => __( 'Lisensi <span class="expired">telah kadaluarsa</span> pada %s.' ),
		'license-key-expired'       => __( 'Lisensi telah kadaluarsa.' ),
		'license-keys-do-not-match' => __( 'Kode lisensi tidak cocok.' ),
		'license-is-inactive'       => __( 'License is inactive.', 'cepatlakoo' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'cepatlakoo' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'cepatlakoo' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'cepatlakoo' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'cepatlakoo' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'cepatlakoo' ),
		'cl-sites-limited'    		=> __( 'Lisensi Anda telah digunakan hingga batas maksimal batas penggunaan situs.' ),
	)

);
add_filter( 'edd_sl_api_request_verify_ssl', '__return_false' );
