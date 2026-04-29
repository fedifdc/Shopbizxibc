<?php
/**
 * The admin-facing functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! function_exists( 'bdp_get_archive_list' ) ) {
	/**
	 * Get all archive list
	 *
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	function bdp_get_archive_list() {
		return Bdp_Template::get_archive_list();
	}
}

if ( ! function_exists( 'bdp_get_date_template_settings' ) ) {
	/**
	 * Get date archive template settings
	 *
	 * @global object $wpdb
	 * @return array Date Template settings
	 */
	function bdp_get_date_template_settings() {
		return Bdp_Template::get_date_template_settings();
	}
}
if ( ! function_exists( 'bdp_get_author_template_settings' ) ) {
	/**
	 * Get author template settings
	 *
	 * @param int   $author_id author id.
	 * @param array $archive_list archive list.
	 * @return Array Category Template settings
	 */
	function bdp_get_author_template_settings( $author_id, $archive_list ) {
		return Bdp_Author::get_author_template_settings( $author_id, $archive_list );
	}
}

if ( ! function_exists( 'bdp_get_category_template_settings' ) ) {
	/**
	 * Get category template settings
	 *
	 * @param int   $category_id category.
	 * @param array $archive_list archive.
	 * @return Array Category Template settings
	 */
	function bdp_get_category_template_settings( $category_id, $archive_list ) {
		return Bdp_Template::get_category_template_settings( $category_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_tag_template_settings' ) ) {
	/**
	 * Get tag template settings
	 *
	 * @param int   $tag_id tag id.
	 * @param array $archive_list archive.
	 * @return array Tag Template settings
	 */
	function bdp_get_tag_template_settings( $tag_id, $archive_list ) {
		return Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_search_template_settings' ) ) {
	/**
	 * Get Search template settings
	 *
	 * @global object $wpdb
	 * @return array Search Template settings
	 */
	function bdp_get_search_template_settings() {
		return Bdp_Template::get_search_template_settings();
	}
}
if ( ! function_exists( 'bdp_paged' ) ) {
	/**
	 * Current page sql
	 *
	 * @return int $paged
	 */
	function bdp_paged() {
		return Bdp_Utility::paged();
	}
}
if ( ! function_exists( 'bdp_get_archive_wp_query' ) ) {
	/**
	 * Get parameter array for archive posts query
	 *
	 * @since 2.0
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 */
	function bdp_get_archive_wp_query( $bdp_settings ) {
		return Bdp_Posts::get_archive_wp_query( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_get_template' ) ) {
	/**
	 * Include selected template
	 *
	 * @param string $bdp_theme theme.
	 * @return void.
	 */
	function bdp_get_template( $bdp_theme ) {
		Bdp_Template::get_template( $bdp_theme );
	}
}
if ( ! function_exists( 'bdp_get_loader' ) ) {
	/**
	 * Get loader image
	 *
	 * @since 2.0
	 * @param array $bdp_settings settings.
	 * @global $bdp_settings
	 */
	function bdp_get_loader( $bdp_settings ) {
		return Bdp_Utility::get_loader( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_standard_paging_nav' ) ) {
	/**
	 * Add pagination
	 *
	 * @return pagination
	 */
	function bdp_standard_paging_nav() {
		return Bdp_Posts::standard_paging_nav( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_get_download_archive_list' ) ) {
	/**
	 * Get all easy digital download archive list
	 *
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	function bdp_get_download_archive_list() {
		return Bdp_Edd::get_download_archive_list();
	}
}
if ( ! function_exists( 'bdp_get_download_category_template_settings' ) ) {
	/**
	 * Get download category template settings
	 *
	 * @since 2.7
	 * @param int   $category_id category id.
	 * @param array $archive_list proudct archvie list.
	 * @return array Category Template settings
	 */
	function bdp_get_download_category_template_settings( $category_id, $archive_list ) {
		return Bdp_Edd::get_download_category_template_settings( $category_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_download_tag_template_settings' ) ) {
	/**
	 * Get download tag template settings
	 *
	 * @since 2.7
	 * @param int   $tag_id tag id.
	 * @param array $archive_list product archive.
	 * @return Array Tag Template settings
	 */
	function bdp_get_download_tag_template_settings( $tag_id, $archive_list ) {
		return Bdp_Template::get_download_tag_template_settings( $tag_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_download_archive_wp_query' ) ) {
	/**
	 * Get parameter array for archive downloads query
	 *
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 * @since 2.7
	 */
	function bdp_get_download_archive_wp_query( $bdp_settings ) {
		return Bdp_Edd::get_download_archive_wp_query( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_standard_product_paging_nav' ) ) {
	/**
	 * Add pagination for product layout
	 *
	 * @since 2.6
	 * @return pagination
	 */
	function bdp_standard_product_paging_nav() {
		return Bdp_Woocommerce::standard_product_paging_nav( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_set_post_views' ) ) {
	/**
	 * Update calculated post count
	 *
	 * @param int $post_id post id.
	 */
	function bdp_set_post_views( $post_id ) {
		return Bdp_Posts::set_post_views( $post_id );
	}
}
if ( ! function_exists( 'bdp_get_single_download_template_setting_front_end' ) ) {
	/**
	 * Display Front side Single Download Blog Designer Layout
	 *
	 * @since 2.7
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	function bdp_get_single_download_template_setting_front_end() {
		return Bdp_Template::get_single_download_template_setting_front_end();
	}
}
if ( ! function_exists( 'bdp_get_product_archive_list' ) ) {
	/**
	 * Get all archive list
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	function bdp_get_product_archive_list() {
		return Bdp_Woocommerce::get_product_archive_list();
	}
}
if ( ! function_exists( 'bdp_get_product_category_template_settings' ) ) {
	/**
	 * Get product category template settings
	 *
	 * @since 2.6
	 * @param int   $category_id category id.
	 * @param array $archive_list product list.
	 * @return Array Category Template settings
	 */
	function bdp_get_product_category_template_settings( $category_id, $archive_list ) {
		return Bdp_Template::get_product_category_template_settings( $category_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_product_tag_template_settings' ) ) {
	/**
	 * Get product tag template settings
	 *
	 * @since 2.6
	 * @param int   $tag_id tag id.
	 * @param array $archive_list product arvhie.
	 * @return Array Tag Template settings
	 */
	function bdp_get_product_tag_template_settings( $tag_id, $archive_list ) {
		return Bdp_Template::get_product_tag_template_settings( $tag_id, $archive_list );
	}
}
if ( ! function_exists( 'bdp_get_product_archive_wp_query' ) ) {
	/**
	 * Get parameter array for archive products query
	 *
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 * @since 2.6
	 */
	function bdp_get_product_archive_wp_query( $bdp_settings ) {
		return Bdp_Woocommerce::get_product_archive_wp_query( $bdp_settings );
	}
}
if ( ! function_exists( 'bdp_get_single_product_template_setting_front_end' ) ) {
	/**
	 * Display Front side Single Product Blog Designer Layout
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	function bdp_get_single_product_template_setting_front_end() {
		return Bdp_Woocommerce::get_single_product_template_settings();
	}
}
if ( ! function_exists( 'bdp_get_single_template_setting_front_end' ) ) {
	/**
	 * Display Front side Single Post Blog Designer Layout
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	function bdp_get_single_template_setting_front_end() {
		return Bdp_Template::get_single_template_setting_front_end();
	}
}

if ( ! function_exists( 'bdp_column_class' ) ) {
	/**
	 * Column layout template class
	 *
	 * @since 1.6
	 * @param array $bdp_settings settings.
	 * @global object $pagenow;
	 */
	function bdp_column_class( $bdp_settings ) {
		return Bdp_Template::column_class( $bdp_settings );
	}
}


if ( ! function_exists( 'bdp_get_the_thumbnail' ) ) {
	/**
	 * Get default image
	 *
	 * @param array $bdp_settings settings.
	 * @param array $post_thumbnail thumbnail.
	 * @param int   $post_thumbnail_id id.
	 * @param int   $bdp_post_id post id.
	 * @return html image
	 */
	function bdp_get_the_thumbnail( $bdp_settings, $post_thumbnail, $post_thumbnail_id, $bdp_post_id ) {
		return Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, $post_thumbnail_id, $bdp_post_id );
	}
}

if ( ! function_exists( 'bdp_get_first_embed_media' ) ) {
	/**
	 * Get first media
	 *
	 * @param int   $post_id post id.
	 * @param array $bdp_settings settings.
	 * @return video, audio or gallery
	 */
	function bdp_get_first_embed_media( $post_id, $bdp_settings ) {
		return Bdp_Utility::get_first_embed_media( $post_id, $bdp_settings );
	}
}

if ( ! function_exists( 'bdp_get_post_auhtors' ) ) {
	/**
	 * Get post author
	 *
	 * @since 2.0
	 * @param int   $post_id post id.
	 * @param array $bdp_settings settings data.
	 * @return array $authors post authors
	 */
	function bdp_get_post_auhtors( $post_id, $bdp_settings ) {
		return Bdp_Author::get_post_auhtors( $post_id, $bdp_settings );
	}
}

if ( ! function_exists( 'bdp_get_content' ) ) {
	/**
	 * Change in exceprt content
	 *
	 * @param int     $bdp_post_id post id.
	 * @param boolean $rss_use_excerpt rss use.
	 * @param int     $excerpt_length excerpt.
	 * @param int     $bdp_settings settings.
	 * @return content or excerpt
	 */
	function bdp_get_content( $bdp_post_id, $rss_use_excerpt, $excerpt_length, $bdp_settings ) {
		return Bdp_Posts::get_content( $bdp_post_id, $rss_use_excerpt, $excerpt_length, $bdp_settings );
	}
}

if ( ! function_exists( 'bdp_acf_plugin' ) ) {
	/**
	 * Check Advance custom field plugin active
	 *
	 * @since 2.5.1
	 */
	function bdp_acf_plugin() {
		return Bdp_Template_Acf::is_acf_plugin();
	}
}

if ( ! function_exists( 'bdp_get_social_icons' ) ) {
	/**
	 *  Add social share icons
	 *
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_get_social_icons( $bdp_settings ) {
		Bdp_Utility::get_social_icons( $bdp_settings );
	}
}

if ( ! function_exists( 'bdp_pinterest' ) ) {
	/**
	 * Add pinterest button on image
	 *
	 * @param int $bdp_post_id Post ID.
	 * @return html pinterest image
	 */
	function bdp_pinterest( $bdp_post_id ) {
		return Bdp_Utility::pinterest( $bdp_post_id );
	}
}
if ( ! function_exists( 'bdp_pinterest' ) ) {
	/**
	 * Add pinterest button on image
	 *
	 * @param int $bdp_post_id Post ID.
	 * @return html pinterest image
	 */
	function bdp_pinterest( $bdp_post_id ) {
		return Bdp_Utility::pinterest( $bdp_post_id );
	}
}

if ( ! function_exists( 'bdp_resize' ) ) {
	/**
	 * Resize Images
	 *
	 * @param string $img_url Image URL.
	 * @param string $width Width.
	 * @param string $height Height.
	 * @param bool   $crop Cropped.
	 * @param int    $thumbnail_id Thumbnail ID.
	 */
	function bdp_resize( $width, $height,  $img_url = null, $crop = false, $thumbnail_id = 0 ) {
		return Bdp_Utility::image_resize( $width, $height, $img_url, $crop, $thumbnail_id );
	}
}

if ( ! function_exists( 'bdp_comment_count' ) ) {
	/**
	 * Get comments count
	 *
	 * @param bool $comment_link Comment Links.
	 */
	function bdp_comment_count( $comment_link = true ) {
		Bdp_Posts::comment_count( $comment_link );
	}
}

if ( ! function_exists( 'bdp_hex2rgba' ) ) {
	/**
	 * Give rgba() color
	 *
	 * @param string $color Color.
	 * @param bool   $opacity Opacity.
	 */
	function bdp_hex2rgba( $color, $opacity = false ) {
		return Bdp_Utility::hex2rgba( $color, $opacity );
	}
}


if ( ! function_exists( 'bdp_get_the_single_post_thumbnail' ) ) {

	/**
	 * Get the single post thumbnail
	 *
	 * @param int $bdp_settings Settings.
	 * @param int $post_thumbnail_id Post Thumbnail ID.
	 * @param int $bdp_post_id Post ID.
	 */
	function bdp_get_the_single_post_thumbnail( $bdp_settings, $post_thumbnail_id, $bdp_post_id ) {
		return Bdp_Posts::get_the_single_post_thumbnail( $bdp_settings, $post_thumbnail_id, $bdp_post_id );
	}
}

if ( ! function_exists( 'bdp_get_post_views' ) ) {

	/**
	 * Display counter with view ie. 999 Views
	 *
	 * @param int $post_id Post ID.
	 * @param int $bdp_settings Settings.
	 */
	function bdp_get_post_views( $post_id, $bdp_settings ) {
		return Bdp_Posts::get_post_views( $post_id, $bdp_settings );
	}
}
