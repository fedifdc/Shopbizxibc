<?php
/**
 * Plugin Name: MyCred Custom Submenu
 * Description: Menambahkan submenu kustom ke dalam plugin MyCred.
 * Version: 1.0
 * Author: Nama Anda
 */

// Pastikan WordPress memuat plugin ini dalam konteks yang benar
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add Submenu Page
add_action( 'mycred_add_menu', 'mycredpro_add_sub_menu_page' );
function mycredpro_add_sub_menu_page( $mycred ) {

	add_submenu_page(
		MYCRED_SLUG,
		'My Custom Page',
		'My Custom Page',
		$mycred->edit_plugin_cap(),
		'my-custom-screen',
		'my_custom_screen_function'
	);

}

// Fungsi untuk menampilkan konten halaman submenu
function my_custom_screen_function() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'My Custom Page', 'your-text-domain' ); ?></h1>
        <p><?php esc_html_e( 'This is a custom page inside the MyCred plugin.', 'your-text-domain' ); ?></p>
    </div>
    <?php
}
