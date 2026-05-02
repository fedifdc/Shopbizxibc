<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link              https://www.solwininfotech.com/
 * @since             1.0.0
 * @package           Blog_Designer_PRO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* If uninstall not called from WordPress, then exit. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$bdp_delete_data = get_option( 'bdp_delete_data', 0 );
if ( 1 == $bdp_delete_data ) {
	delete_option( 'bdp_template_outdated' );
	delete_option( 'bdp_template_name_changed' );
	delete_option( 'bdp_db_version' );
	delete_option( 'bdp_multi_author_selection' );
	delete_option( 'bdp_version' );
	delete_option( 'bdp_single_file_template' );
	delete_option( 'bdp_admin_notice_pro_layouts_dismiss' );
	delete_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss' );
	delete_option( 'bdp_username' );
	delete_option( 'bdp_purchase_code' );
	delete_option( 'bdp_custom_google_fonts' );
	delete_option( 'wp_blog_designer_settings' );
	/* Delete database table */
	global $wpdb;
	/** Delets blog_designer_pro_shortcodes table */
	$blog_designer_pro_shortcodes = $wpdb->prefix . 'blog_designer_pro_shortcodes';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}blog_designer_pro_shortcodes" );
	/** Delets bdp_archives table */
	$bdp_archives = $wpdb->prefix . 'bdp_archives';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_archives" );
	/** Delets bdp_single_layouts table */
	$bdp_single_layouts = $wpdb->prefix . 'bdp_single_layouts';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_single_layouts" );
	/** Delets bdp_product_archives table */
	$bdp_product_archives = $wpdb->prefix . 'bdp_product_archives';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_product_archives" );
	/** Delets bdp_single_product table */
	$bdp_single_product = $wpdb->prefix . 'bdp_single_product';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_single_product" );
	/** Delets bdp_edd_archives table */
	$bdp_edd_archives = $wpdb->prefix . 'bdp_edd_archives';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_edd_archives" );
	/** Delets bdp_single_ed_download table */
	$bdp_single_ed_download = $wpdb->prefix . 'bdp_single_ed_download';
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bdp_single_ed_download" );
	delete_option( 'bdp_delete_data' );
}
