<?php
/**
 * Available filters for extending Merlin WP.
 *
 * @package   Merlin WP
 * @version   @@pkg.version
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 * *
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.5.0
 */

/**
 * Execute custom code after the whole import has finished.
 */
function cl_merlin_after_import_setup() {
	if ( ! function_exists(' wc_update_product_lookup_tables ') ) {
		wc_update_product_lookup_tables();
	}

	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', 'Menu Utama', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Menu Footer', 'nav_menu' );
	
	set_theme_mod(
		'nav_menu_locations', array(
			'cepatlakoo-left-navigation' => $main_menu->term_id,
			'cepatlakoo-footer-navigation' => $footer_menu->term_id,
		)
	);

	// Setup WooCommerce pages & settings
	if ( class_exists('WooCommerce') ) {
		update_option( 'woocommerce_currency', 'IDR' );
		update_option( 'woocommerce_default_country', 'ID:JK' );
		update_option( 'woocommerce_weight_unit', 'g' );
		
		// Setup cart page
		$cart = ( get_page_by_path('cart') );

	    if ( $cart ) {
			update_option( 'woocommerce_cart_page_id', $cart->ID );
	    }
		
		// Setup checkout page
		$checkout = ( get_page_by_path('checkout') );

	    if ( $checkout ) {
			update_option( 'woocommerce_checkout_page_id', $checkout->ID );
	    }
		
		$shop = ( get_page_by_path('shop') );

	    if ( $shop ) {
			update_option( 'woocommerce_shop_page_id', $shop->ID );
	    }
		
		// Setup my account page
		$myaccount = ( get_page_by_path('my-account') );

	    if ( $myaccount ) {
			update_option( 'woocommerce_myaccount_page_id', $myaccount->ID );
		}
		
		// Import Caldera Forms json files
		if ( class_exists('Caldera_Forms_Forms') ) {
			// Form konfirmasi pembayaran
			$location = 'https://files.cepatlakoo.com/import-data/demo-1/caldera-forms-konfirmasi-pembayaran.json';
			$get = wp_remote_get( $location );
			$get = wp_remote_retrieve_body( $get );
			$data = json_decode( $get, true  );

			$data[ 'name' ] = 'Konfirmasi';
	
			$trusted = true;
			$new_form_id = Caldera_Forms_Forms::import_form( $data, $trusted );

			// Form kontak
			$location = 'https://files.cepatlakoo.com/import-data/demo-1/caldera-forms-kontak.json';
			$get = wp_remote_get( $location );
			$get = wp_remote_retrieve_body( $get );
			$data = json_decode( $get, true  );
			$data[ 'name' ] = 'Kontak';
	
			$trusted = true;
			$new_form_id = Caldera_Forms_Forms::import_form( $data, $trusted );
		}
 	}
}
add_action( 'merlin_after_all_import', 'cl_merlin_after_import_setup' );

/**
 * Define the demo import files (local files).
 *
 * You have to use the same filter as in above example,
 * but with a slightly different array keys: local_*.
 * The values have to be absolute paths (not URLs) to your import files.
 * To use local import files, that reside in your theme folder,
 * please use the below code.
 * Note: make sure your import files are readable!
 * *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.5.0
 */
function cl_merlin_local_import_files() {
	return array(
		array(
			'import_file_name'           => 'Demo Import 1',
			'import_file_url'            => 'https://files.cepatlakoo.com/import-data/demo-1/cepatlakoo-demo-data.xml',
			'import_widget_file_url'     => 'https://files.cepatlakoo.com/import-data/demo-1/cepatlakoo-widgets.wie',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://files.cepatlakoo.com/import-data/demo-1/cepatlakoo-redux.json',
					'option_name' => 'cl_options',
				),
			),
			'import_preview_image_url'   => 'https://files.cepatlakoo.com/import-data/demo-1/cepatlakoo-preview.png',
			'import_notice'              => __( 'Demo 1', 'cepatlakoo' ),
			'preview_url'                => 'https://demo.cepatlakoo.com/cl/',
		),
	);
}
add_filter( 'merlin_import_files', 'cl_merlin_local_import_files' );

/**
 * Function to theme updater
 * *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
function remove_admin_login_header() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');

/**
* Functions to remove caldera warning message
*
* @package WordPress
* @subpackage CepatLakoo
 * @since CepatLakoo 1.5.0
*/
function cl_remove_action_caldera() {
	if ( is_plugin_active( 'caldera-forms/caldera-core.php' ) && get_current_screen() === NULL && class_exists('Caldera_Forms_Admin') ) {
		remove_action( 'admin_footer', array( Caldera_Forms_Admin::get_instance(), 'add_shortcode_inserter'));
	}
}
add_action('admin_footer', 'cl_remove_action_caldera', 9);