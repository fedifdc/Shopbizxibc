<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.solwininfotech.com/
 * @since             1.0.0
 * @package           Blog_Designer_PRO
 *
 * @wordpress-plugin
 * Plugin Name:       Blog Designer PRO
 * Plugin URI:        https://www.solwininfotech.com/product/wordpress-plugins/blog-designer-pro/
 * Description:       Blog Designer PRO is a step ahead WordPress plugin that allows you to modify blog page, single page and archive page layouts and design.
 * Version:           3.4.8
 * Author:            Solwin Infotech
 * Author URI:        https://www.solwininfotech.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.4
 * Tested up to:      6.6.1
 * Text Domain:       blog-designer-pro
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'BLOGDESIGNERPRO_TEXTDOMAIN', 'blog-designer-pro' );
define( 'BLOGDESIGNERPRO_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLOGDESIGNERPRO_URL', plugins_url() . '/blog-designer-pro' );

register_activation_hook( __FILE__, 'bdp_plugin_active' );

require_once 'admin/class-bdp-admin-functions.php';
require_once 'admin/class-bdp-utility.php';
require_once 'admin/class-bdp-posts.php';
require_once 'admin/class-bdp-author.php';
require_once 'admin/class-bdp-woocommerce.php';
require_once 'admin/class-bdp-edd.php';
require_once 'admin/class-bdp-template-acf.php';
require_once 'admin/class-bdp-template.php';
require_once 'admin/class-bdp-ajax-actions.php';
require_once 'admin/class-bdp-support.php';
require_once 'admin/class-blog-designer-pro-widget.php';
require_once 'admin/class-bdp-widget-recent-post.php';
require_once 'admin/class-bdp-scroll-widget.php';
require_once 'public/css/single/single_page_dynamic_style.php';

$bdp_admin_page  = false;
$bdp_admin_pages = array( 'layouts', 'archive_layouts', 'add_shortcode', 'single_post', 'bdp_add_archive_layout', 'bdp_add_product_archive_layout', 'single_product', 'bdp_export', 'single_layouts', 'bdp_getting_started', 'designer_welcome_page', 'product_archive_layouts', 'single_product_layouts', 'single_edd_download', 'single_edd_layouts', 'edd_archive_layouts', 'add_edd_archive' );
if ( isset( $_GET['page'] ) && ( in_array( $_GET['page'], $bdp_admin_pages ) ) ) {
	$bdp_admin_page = true;
}
if ( $bdp_admin_page ) {
	add_action( 'admin_notices', array( 'Bdp_Utility', 'admin_notice' ) );
}
$blog_designer_setting                   = get_option( 'wp_blog_designer_settings' );
$create_layout_from_blog_designer_notice = get_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss', false );
if ( false == $create_layout_from_blog_designer_notice && '' != $blog_designer_setting ) {
	if ( $bdp_admin_page ) {
		add_action( 'admin_notices', array( 'Bdp_Template', 'create_layout_from_blog_designer_notice' ) );
	}
} else {
	$sample_layout_notice = get_option( 'bdp_admin_notice_pro_layouts_dismiss', false );
	if ( false == $sample_layout_notice ) {
		if ( $bdp_admin_page ) {
			add_action( 'admin_notices', array( 'Bdp_Template', 'sample_layout_notice' ) );
		}
	}
}
require_once 'public/class-bdp-front-functions.php';
add_action( 'admin_init', 'bdp_activate_au' );

if ( ! function_exists( 'bdp_activate_au' ) ) {
	/**
	 * Add auto update
	 */
	function bdp_activate_au() {
		include_once 'admin/assets/class-bdp-wp-auto-update.php';
		new Bdp_Wp_Auto_Update();

		// add data.
		$bdp_stored_data    = get_option( 'bdp_stored_data' );
		$bdp_stored_website = get_option( 'bdp_stored_website' );
		$site_url           = get_site_url();
		$site_url           = str_replace( 'www.', '', str_replace( 'http://', '', str_replace( 'https://', '', $site_url ) ) );

		if ( 'yes' != $bdp_stored_data || $site_url != $bdp_stored_website ) {
			$stored_data_object = new Bdp_Wp_Auto_Update();
			$store_data         = $stored_data_object->store_data();
		}
	}
}
if ( ! function_exists( 'bdp_remove_more_link' ) ) {
	/**
	 * Remove More Link
	 *
	 * @param string $link Link.
	 */
	function bdp_remove_more_link( $link ) {
		$link = '';
		return $link;
	}
}
require_once 'public/class-bdp-like.php';
require_once 'public/patch-function.php';


if ( ! function_exists( 'bdp_lazyload_images_modify_post_thumbnail_attr' ) ) {
	/**
	 * Lazyload Images Modify Post Thumbnail
	 *
	 * @param string $attr Attributes.
	 * @param string $attachment Attachments.
	 * @param string $size Size.
	 */
	function bdp_lazyload_images_modify_post_thumbnail_attr( $attr, $attachment, $size ) {

		if ( is_feed() ) {
			return $attr;
		}
		if ( isset( $attr['sizes'] ) ) {
			$data_sizes = $attr['sizes'];
			unset( $attr['sizes'] );
			$attr['data-sizes'] = $data_sizes;
		}
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset']   = $attr['srcset'];
			$attr['data-noscript'] = $attr['src'];
			$attr['data-src']      = $attr['src'];
			$attr['srcset']        = '';
		}
		$attr['class'] .= ' lazyload';
		return $attr;
	}
}

/**
 * Create table 'blog_designer_pro_shortcodes' when plugin activated
 *
 * @global object $wpdb
 */
function bdp_plugin_active() {

	// Deactive lite version plugin when pro is actived.
	if ( is_plugin_active( 'blog-designer/blog-designer.php' ) ) {
		deactivate_plugins( '/blog-designer/blog-designer.php' );
	}
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb, $bdp_db_version;
	// Creare Table.
	$table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	}
	if ( ! empty( $wpdb->collate ) ) {
		$charset_collate .= " COLLATE $wpdb->collate";
	}
	$sql = "CREATE TABLE $table_name (
		bdid int(9) NOT NULL AUTO_INCREMENT,
		shortcode_name tinytext NOT NULL,
		bdsettings text NOT NULL,
		UNIQUE KEY bdid (bdid)
	) $charset_collate;";
	// reference to upgrade.php file.
	dbDelta( $sql );
	wp_reset_postdata();
	$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
	$count_layout              = 0;
	$count_archive             = 0;
	$count_single              = 0;
	if ( 1 == $bdp_template_name_changed ) {
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "blog_designer_pro_shortcodes'" ) == $wpdb->prefix . 'blog_designer_pro_shortcodes' ) {
			$count_layout = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
		}
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_archives'" ) == $wpdb->prefix . 'bdp_archives' ) {
			$count_archive = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'bdp_archives' );
		}
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_single_layouts'" ) == $wpdb->prefix . 'bdp_single_layouts' ) {
			$count_single = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'bdp_single_layouts' );
		}
		if ( $count_layout > 0 || $count_archive > 0 || $count_single > 0 ) {
			update_option( 'bdp_template_name_changed', 1 );
		} else {
			update_option( 'bdp_template_name_changed', 0 );
		}
	}
	add_option( 'bdp_plugin_do_activation_redirect', true );

}

remove_filter( 'woocommerce_product_get_rating_html', 'bdp_get_product_rating_html', 99 );

if ( ! function_exists( 'bdp_get_product_rating_html' ) ) {
	/**
	 * Get Product Rating
	 *
	 * @param string $rating_html Rating HTML.
	 * @param string $rating Rating.
	 */
	function bdp_get_product_rating_html( $rating_html, $rating ) {
		global $product;
		$rating_html = '';

		if ( ! $product ) {
			return $rating_html;
		}

		if ( $rating >= 0 ) {
			/* translators: %s: search term */
			$rating_html  = '<div class="star-rating" title="' . sprintf( __( 'Rated %s out of 5', 'yith-woocommerce-advanced-reviews' ), $rating ) . '">';
			$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"></span>';
			$rating_html .= '</div>';
			/* translators: %s: search term */
			$rating_html .= '<span class="blog-start-rating-text">' . sprintf( __( 'Rated %s out of 5', 'yith-woocommerce-advanced-reviews' ), $rating ) . '</span>';
		}

		return $rating_html;
	}

	add_filter( 'woocommerce_product_get_rating_html', 'bdp_get_product_rating_html', 10, 2 );
}
