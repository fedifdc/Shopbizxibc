<?php
/**
 * List of files inclusion and functions
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'THEME_NAME', 'Cepatlakoo' );
define( 'THEME_SLUG', 'cepatlakoo' );
define( 'THEME_VERSION', '2.7.1' );

// Check if is_plugin_active() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

// Include theme functions
require_once( get_template_directory() . '/functions/theme-functions/theme-widgets.php' ); // Load widgets
require_once( get_template_directory() . '/functions/theme-functions/theme-setup.php' ); // Load theme setup
require_once( get_template_directory() . '/functions/theme-functions/theme-functions.php' ); // Load custom functions
require_once( get_template_directory() . '/functions/theme-functions/theme-post-type.php' ); // Load custom post type functions
require_once( get_template_directory() . '/functions/theme-functions/theme-shortcode.php' ); // Load custom shortcode functions
require_once( get_template_directory() . '/functions/theme-functions/theme-migrations.php' ); // Load custom shortcode functions
require_once( get_template_directory() . '/functions/theme-functions/theme-styles.php' ); // Load JavaScript, CSS & comment list layout
require_once( get_template_directory() . '/includes/elementor-functions.php' ); // Load custom elementor functions
require_once( get_template_directory() . '/functions/theme-functions/theme-styles.php' ); // Load JavaScript, CSS & comment list layout

require_once( get_template_directory() . '/functions/class-tgm-plugin-activation.php' ); // Load TGM-Plugin-Activation

if( !is_plugin_active('advanced-custom-fields/acf.php') && !is_plugin_active('advanced-custom-fields-pro/acf.php') ){
	define( 'ACF_PATH', get_template_directory() . '/includes/acf-pro/' );
	define( 'ACF_URL', get_template_directory_uri() . '/includes/acf-pro/' );
	
	// Include the ACF plugin.
	require_once( ACF_PATH . 'acf.php' );
	add_filter('acf/settings/show_admin', 'cepatlakoo_acf_settings_show_admin');
}

// Hide the ACF admin menu item
function cepatlakoo_acf_settings_show_admin( $show_admin ) {
    return false;
}

// Load MerlinWP files
require_once( get_template_directory() . '/includes/merlinwp/vendor/autoload.php' );
require_once( get_template_directory() . '/includes/merlinwp/class-merlin.php' );

/**
 * Init merlin WP
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.5.0
 */
if ( ! function_exists(' cepatlakoo_merlin_init ') ) {
	function cepatlakoo_merlin_init() {
		if ( false === get_option( 'merlin_cepatlakoo_completed' ) ) {
			require_once( get_template_directory() . '/functions/merlin-config.php' );
			require_once( get_template_directory() . '/functions/merlin-filters.php' );
		}
	}
}
add_action( 'init', 'cepatlakoo_merlin_init' );

if ( !class_exists( 'Mobile_Detect' ) ) {
	require_once( get_template_directory() . '/includes/Mobile_Detect.php' );
}

// Load custom woocommerce functions
if ( class_exists( 'WooCommerce' ) ) {
	require_once( get_template_directory() . '/functions/theme-functions/theme-woocommerce.php' );
	require_once( get_template_directory() . '/includes/bank-payments.php' ); // Load basic custom CL payment gateway
	require_once( get_template_directory() . '/includes/sms-gateway.php' ); // Load sms gateway library
	require_once( get_template_directory() . '/includes/cl-checkout.php' ); // Load sms gateway library
}

/**
 * Check TitanFramework Before Include Metabox Option
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

require_once( get_template_directory() . '/functions/theme-functions/theme-metabox.php' ); // Load custom metabox functions

/**
 * After setup theme
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists(' cepatlakoo_theme_init ') ) {
	function cepatlakoo_theme_init() {
		add_action( 'widgets_init', 'cepatlakoo_register_sidebars' );
	}
}
add_action( 'after_setup_theme', 'cepatlakoo_theme_init' );

/**
 * Loads the Options Panel
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( file_exists( get_template_directory() . '/functions/theme-functions/theme-options.php' ) ) {
	require_once( get_template_directory() . '/functions/theme-functions/theme-options.php' );
}

/**
 * Required & recommended plugins
 * *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists(' cepatlakoo_required_plugins ') ) {
	function cepatlakoo_required_plugins() {
		$plugins = array(
			array(
				'name'			=> esc_html__( 'WooCommerce - excelling eCommerce', 'cepatlakoo' ),
				'slug'			=> 'woocommerce',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Classic Widgets', 'cepatlakoo' ),
				'slug'			=> 'classic-widgets',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Fluent Forms', 'cepatlakoo' ),
				'slug'			=> 'fluentform',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Cepatlakoo - Ongkir', 'cepatlakoo' ),
				'version' 		=> '1.8.5',
				'slug'			=> 'cepatlakoo-ongkir',
				'source'		=> get_template_directory() . '/plugins/cepatlakoo-ongkir.zip',
				'external_url'	=> '',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Cepatlakoo - Input Resi', 'cepatlakoo' ),
				'slug'			=> 'cepatlakoo-input-resi',
				'version' 		=> '1.2.1',
				'source'		=> get_template_directory() . '/plugins/cepatlakoo-input-resi.zip',
				'external_url'	=> '',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Cepatlakoo - Kode Pembayaran', 'cepatlakoo' ),
				'slug'			=> 'cepatlakoo-kode-pembayaran',
				'version' 		=> '1.1.7',
				'source'		=> get_template_directory() . '/plugins/cepatlakoo-kode-pembayaran.zip',
				'external_url'	=> '',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Cepatlakoo - Sales Booster for WooCommerce', 'cepatlakoo' ),
				'slug'			=> 'cl-sales-booster',
				'version' 		=> '1.2.11',
				'source'		=> get_template_directory() . '/plugins/cl-sales-booster.zip',
				'external_url'	=> '',
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Redux Framework', 'cepatlakoo' ),
				'slug'			=> 'redux-framework',
				'force_activation' => false,
				'required'		=> true,
			),
			array(
				'name'			=> esc_html__( 'Elementor Page Builder', 'cepatlakoo' ),
				'slug'			=> 'elementor',
				'required'		=> true,
			),
		);

		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}
}
add_action( 'tgmpa_register', 'cepatlakoo_required_plugins' );

/**
 * Load theme updater functions.
 * Action is used so that child themes can easily disable.
 */

/**
 * Function to theme updater
 * *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists(' cepatlakoo_theme_updater ') ) {
	function cepatlakoo_theme_updater() {
		require( get_template_directory() . '/updater/theme-updater.php' );
	}
}
add_action( 'after_setup_theme', 'cepatlakoo_theme_updater' );

