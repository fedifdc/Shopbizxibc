<?php
/**
 * admin menu
 */
add_action( 'admin_menu', 'plugin_resi_menu' );
function plugin_resi_menu() {
	add_menu_page(
		'Plugin Resi',
		'Plugin Resi',
		'manage_woocommerce',
		'plugin-resi',
		'plugin_resi_admin_page',
		'dashicons-products',
		58
	);
}
function plugin_resi_admin_page() {
	 require_once  plugin_dir_path( __FILE__ ) . 'plugin-option-page.php' ;
}

/**
 * Init plugin options to white list our options
 */
add_action( 'admin_init', 'plugin_resi_admin_init' );
function plugin_resi_admin_init() {
	register_setting( 'plugin_resi_options', 'plugin_resi_options' );
}
