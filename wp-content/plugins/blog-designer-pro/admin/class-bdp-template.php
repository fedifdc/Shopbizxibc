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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Display plugin buttons via action call
 *
 * @param     string       $where         where to display
 * @param     mixed        $user_query    WP_Query parameters
 */

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Template
 * @version 1.0.0
 */
class Bdp_Template {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'create_sample_layout' ) );
		add_action( 'admin_init', array( $this, 'create_layout_using_blog_designer' ) );
	}
	/**
	 * Column layout template class
	 *
	 * @since 1.6
	 * @param array $bdp_settings settings.
	 * @global object $pagenow;
	 */
	public static function column_class( $bdp_settings ) {
		$column_class = '';
		$total_col    = ( isset( $bdp_settings['template_columns'] ) && '' != $bdp_settings['template_columns'] ) ? $bdp_settings['template_columns'] : 2;
		if ( 1 == $total_col ) {
			$col_class = 'one_column';
		}
		if ( 2 == $total_col ) {
			$col_class = 'two_column';
		}
		if ( 3 == $total_col ) {
			$col_class = 'three_column';
		}
		if ( 4 == $total_col ) {
			$col_class = 'four_column';
		}
		$total_col_ipad = ( isset( $bdp_settings['template_columns_ipad'] ) && '' != $bdp_settings['template_columns_ipad'] ) ? $bdp_settings['template_columns_ipad'] : 1;
		if ( 1 == $total_col_ipad ) {
			$col_class_ipad = 'one_column_ipad';
		}
		if ( 2 == $total_col_ipad ) {
			$col_class_ipad = 'two_column_ipad';
		}
		if ( 3 == $total_col_ipad ) {
			$col_class_ipad = 'three_column_ipad';
		}
		if ( 4 == $total_col_ipad ) {
			$col_class_ipad = 'four_column_ipad';
		}
		$total_col_tablet = ( isset( $bdp_settings['template_columns_tablet'] ) && '' != $bdp_settings['template_columns_tablet'] ) ? $bdp_settings['template_columns_tablet'] : 1;
		if ( 1 == $total_col_tablet ) {
			$col_class_tablet = 'one_column_tablet';
		}
		if ( 2 == $total_col_tablet ) {
			$col_class_tablet = 'two_column_tablet';
		}
		if ( 3 == $total_col_tablet ) {
			$col_class_tablet = 'three_column_tablet';
		}
		if ( 4 == $total_col_tablet ) {
			$col_class_tablet = 'four_column_tablet';
		}
		$total_col_mobile = ( isset( $bdp_settings['template_columns_mobile'] ) && '' != $bdp_settings['template_columns_mobile'] ) ? $bdp_settings['template_columns_mobile'] : 1;
		if ( 1 == $total_col_mobile ) {
			$col_class_mobile = 'one_column_mobile';
		}
		if ( 2 == $total_col_mobile ) {
			$col_class_mobile = 'two_column_mobile';
		}
		if ( 3 == $total_col_mobile ) {
			$col_class_mobile = 'three_column_mobile';
		}
		if ( 4 == $total_col_mobile ) {
			$col_class_mobile = 'four_column_mobile';
		}
		$column_class = $col_class . ' ' . $col_class_ipad . ' ' . $col_class_tablet . ' ' . $col_class_mobile;
		return $column_class;
	}
	/**
	 * Get setting from database from shortcode id
	 *
	 * @param int $shortcode_id id.
	 * @global object $wpdb
	 * @return boolean, null or array
	 */
	public static function get_shortcode_settings( $shortcode_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		if ( is_numeric( $shortcode_id ) ) {
			$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $shortcode_id ), ARRAY_A );
		}
		if ( ! $settings_val ) {
			return;
		}
		$allsettings = $settings_val[0]['bdsettings'];
		if ( is_serialized( $allsettings ) ) {
			$bdp_settings = maybe_unserialize( $allsettings );
			return $bdp_settings;
		}
		return false;
	}
	/**
	 * Add notice at admin side for create sample blog layout
	 *
	 * @since 1.5
	 * @global object $pagenow;
	 * @return void
	 */
	public static function sample_layout_notice() {
		/* Check that the user hasn't already clicked to ignore the message */
		if ( isset( $_GET['page'] ) && current_user_can( 'manage_options' ) && ( 'layouts' === $_GET['page'] || 'add_shortcode' === $_GET['page'] ) ) {
			global $wpdb;
			$count_layout = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
			if ( $count_layout < 1 ) {
				echo '<div class="updated notice is-dismissible bdp-admin-notice-pro-layouts"><p>';
				?>
				<strong><?php esc_html_e( 'Create New Sample Blog layout with Blog Designer PRO Plugin', 'blog-designer-pro' ); ?></strong>&nbsp;&nbsp;&nbsp;
				<a class="bdp-create-layout button-primary" href="<?php echo esc_url( add_query_arg( 'sample-blog-layout', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"><?php esc_html_e( 'Create Layout', 'blog-designer-pro' ); ?></a>
				<button class="notice-dismiss bdp-sample-blog-layout-notice-dismiss" type="button">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'blog-designer-pro' ); ?></span>
				</button>
				<?php
				echo '</p></div>';
			}
		}
	}
	/**
	 * Create sample blog layout
	 *
	 * @since 1.5
	 * @global type $wpdb
	 */
	public function create_sample_layout() {
		if ( isset( $_GET['sample-blog-layout'] ) && 'new' === $_GET['sample-blog-layout'] ) {
			global $wpdb;
			$count_layout = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
			$page_id      = '';
			$blog_page_id = wp_insert_post(
				array(
					'post_title'   => esc_html__( 'Sample Blog', 'blog-designer-pro' ),
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_content' => '',
				)
			);
			if ( $blog_page_id ) {
				$page_id = $blog_page_id;
			}
			/* Array for sample blog layout create */
			$sample_blog_settings = array(
				'template_name'                            => 'classical',
				'bdp_color_preset'                         => 'classical_default',
				'unique_shortcode_name'                    => 'Sample Blog Layout',
				'bdp_timeline_layout'                      => '',
				'custom_post_type'                         => 'post',
				'blog_page_display'                        => $page_id,
				'blog_time_period'                         => 'all',
				'between_two_date_from'                    => '',
				'between_two_date_to'                      => '',
				'bdp_time_period_day'                      => '15',
				'posts_per_page'                           => '5',
				'bdp_blog_order_by'                        => 'date',
				'bdp_blog_order'                           => 'ASC',
				'timeline_display_option'                  => '',
				'displaydate_backcolor'                    => '#414a54',
				'pagination_type'                          => 'paged',
				'pagination_text_color'                    => '#ffffff',
				'pagination_background_color'              => '#777777',
				'pagination_text_hover_color'              => '',
				'pagination_background_hover_color'        => '',
				'pagination_text_active_color'             => '',
				'pagination_active_background_color'       => '',
				'pagination_border_color'                  => '#b2b2b2',
				'pagination_active_border_color'           => '#007acc',
				'display_category'                         => '1',
				'display_tag'                              => '1',
				'display_author'                           => '1',
				'display_story_year'                       => '1',
				'display_date'                             => '1',
				'display_comment_count'                    => '1',
				'display_postlike'                         => '0',
				'display_filter_by'                        => 'category',
				'display_filter'                           => '0',
				'bdp_filter_with'                          => '0',
				'bdp_post_taxonomy'                        => 'category',
				'custom_css'                               => '',
				'display_timeline_bar'                     => '0',
				'timeline_start_from'                      => '28/01/2017',
				'template_easing'                          => 'easeOutSine',
				'item_width'                               => '400',
				'item_height'                              => '570',
				'template_post_margin'                     => '28',
				'enable_autoslide'                         => '0',
				'scroll_speed'                             => '1000',
				'unique_design_option'                     => 'first_post',
				'template_columns'                         => '2',
				'template_grid_height'                     => '300',
				'template_grid_skin'                       => 'default',
				'grid_col_space'                           => '10',
				'grid_hoverback_color'                     => '#000000',
				'template_color'                           => '#ffffff',
				'template_alternative_color'               => '#c34376',
				'story_startup_border_color'               => '#ffffff',
				'template_bgcolor'                         => '#ffffff',
				'blog_background_image_style'              => '1',
				'template_bghovercolor'                    => '#eeeeee',
				'template_alternativebackground'           => '0',
				'template_alterbgcolor'                    => '#ffffff',
				'story_startup_text'                       => 'STARTUP',
				'story_startup_background'                 => '#ade175',
				'story_startup_text_color'                 => '#333',
				'story_ending_text'                        => 'Ending',
				'story_ending_link'                        => '',
				'story_ending_background'                  => '#ade175',
				'story_ending_text_color'                  => '#333',
				'post_loop_alignment'                      => 'default',
				'template_ftcolor'                         => '#007acc',
				'template_fthovercolor'                    => '#666666',
				'deport_dashcolor'                         => '',
				'winter_category_color'                    => '',
				'image_corner_selection'                   => '0',
				'bdp_hide_hover_post'                      => '1',
				'bdp_post_title_link'                      => '1',
				'template_title_alignment'                 => 'left',
				'template_titlecolor'                      => '#007acc',
				'template_titlehovercolor'                 => '#666666',
				'template_titlebackcolor'                  => '',
				'template_titlefontface_font_type'         => '',
				'template_titlefontface'                   => '',
				'template_titlefontsize'                   => '30',
				'template_title_font_weight'               => 'normal',
				'template_title_font_line_height'          => '1.2',
				'template_title_font_text_transform'       => 'none',
				'template_title_font_text_decoration'      => 'none',
				'template_title_font_letter_spacing'       => '0',
				'rss_use_excerpt'                          => '1',
				'template_post_content_from'               => 'from_content',
				'display_html_tags'                        => '1',
				'firstletter_fontsize'                     => '28',
				'firstletter_font_family_font_type'        => '',
				'firstletter_font_family'                  => '',
				'firstletter_contentcolor'                 => '#777777',
				'txtExcerptlength'                         => '80',
				'bdp_post_offset'                          => '0',
				'content_font_family_font_type'            => '',
				'content_font_family'                      => '',
				'content_fontsize'                         => '14',
				'content_font_weight'                      => 'normal',
				'content_font_line_height'                 => '1.5',
				'content_font_text_transform'              => 'none',
				'content_font_text_decoration'             => 'none',
				'content_font_letter_spacing'              => '0',
				'template_contentcolor'                    => '#777777',
				'template_content_hovercolor'              => '#ed4b1f',
				'txtReadmoretext'                          => 'Read More',
				'read_more_on'                             => '2',
				'read_more_button_hover_border_style'      => 'solid',
				'readmore_button_hover_border_radius'      => '0',
				'bdp_readmore_button_hover_borderleft'     => '0',
				'bdp_readmore_button_hover_borderleftcolor' => '',
				'bdp_readmore_button_hover_borderright'    => '0',
				'bdp_readmore_button_hover_borderrightcolor' => '',
				'bdp_readmore_button_hover_bordertop'      => '0',
				'bdp_readmore_button_hover_bordertopcolor' => '',
				'bdp_readmore_button_hover_borderbottom'   => '0',
				'bdp_readmore_button_hover_borderbottomcolor' => '',
				'readmore_font_family_font_type'           => '',
				'readmore_font_family'                     => '',
				'readmore_fontsize'                        => '14',
				'readmore_font_weight'                     => 'normal',
				'readmore_font_line_height'                => '1.5',
				'readmore_font_text_transform'             => 'none',
				'readmore_font_text_decoration'            => 'none',
				'readmore_font_letter_spacing'             => '0',
				'template_readmorecolor'                   => '#007acc',
				'template_readmorehovercolor'              => '#2376ad',
				'template_readmorebackcolor'               => '#f1f1f1',
				'readmore_button_border_radius'            => '0',
				'readmore_button_alignment'                => 'left',
				'readmore_button_paddingleft'              => '10',
				'readmore_button_paddingright'             => '10',
				'readmore_button_paddingtop'               => '3',
				'readmore_button_paddingbottom'            => '3',
				'readmore_button_marginleft'               => '0',
				'readmore_button_marginright'              => '0',
				'readmore_button_margintop'                => '0',
				'readmore_button_marginbottom'             => '0',
				'read_more_button_border_style'            => 'solid',
				'bdp_readmore_button_borderleft'           => '0',
				'bdp_readmore_button_borderleftcolor'      => '',
				'bdp_readmore_button_borderright'          => '0',
				'bdp_readmore_button_borderrightcolor'     => '',
				'bdp_readmore_button_bordertop'            => '0',
				'bdp_readmore_button_bordertopcolor'       => '',
				'bdp_readmore_button_borderbottom'         => '0',
				'bdp_readmore_button_borderbottomcolor'    => '',
				'display_feature_image'                    => '0',
				'easy_timeline_effect'                     => 'flip-effect',
				'thumbnail_skin'                           => '0',
				'bdp_post_image_link'                      => '1',
				'bdp_default_image_id'                     => '',
				'bdp_default_image_src'                    => '',
				'bdp_media_size'                           => 'full',
				'media_custom_width'                       => '800',
				'media_custom_height'                      => '320',
				'template_slider_columns'                  => '2',
				'template_slider_effect'                   => 'slide',
				'template_slider_scroll'                   => '1',
				'display_slider_navigation'                => '1',
				'navigation_style_hidden'                  => 'navigation3',
				'display_slider_controls'                  => '1',
				'arrow_style_hidden'                       => 'arrow1',
				'slider_autoplay'                          => '1',
				'slider_autoplay_intervals'                => '3000',
				'slider_speed'                             => '300',
				'enable_nav_to_scroll'                     => '1',
				'enable_lazy_load'                         => '0',
				'enable_lazy_load_blur_image'              => '0',
				'template_lazy_load_color'                 => '#fff',
				'enable_print_page'                        => '1',
				'enable_disbale_customread_more'           => '1',
				'display_customread_more'                  => '1',
				'beforeloop_Readmoretext'                  => '',
				'beforeloop_Readmoretextlink'              => '',
				'open_customlink'                          => '1',
				'beforeloop_readmorecolor'                 => '#ffffff',
				'beforeloop_readmorebackcolor'             => '#333333',
				'beforeloop_readmorehovercolor'            => '#333333',
				'beforeloop_readmorehoverbackcolor'        => '#f1f1f1',
				'beforeloop_titlefontface_font_type'       => '',
				'beforeloop_titlefontface'                 => '',
				'beforeloop_titlefontsize'                 => '14',
				'beforeloop_title_font_weight'             => 'normal',
				'beforeloop_title_font_line_height'        => '1.5',
				'beforeloop_title_font_text_transform'     => 'none',
				'beforeloop_title_font_text_decoration'    => 'none',
				'beforeloop_title_font_letter_spacing'     => '0',
				'display_sale_tag'                         => '0',
				'bdp_sale_tagtext_alignment'               => 'left-top',
				'bdp_sale_tagtext_marginleft'              => '5',
				'bdp_sale_tagtext_marginright'             => '5',
				'bdp_sale_tagtext_margintop'               => '5',
				'bdp_sale_tagtext_marginbottom'            => '5',
				'bdp_sale_tagtext_paddingleft'             => '5',
				'bdp_sale_tagtext_paddingright'            => '5',
				'bdp_sale_tagtext_paddingtop'              => '5',
				'bdp_sale_tagtext_paddingbottom'           => '5',
				'bdp_sale_tagtextcolor'                    => '#ffffff',
				'bdp_sale_tagbgcolor'                      => '#777777',
				'bdp_sale_tag_angle'                       => '0',
				'bdp_sale_tag_border_radius'               => '0',
				'bdp_sale_tagfontface'                     => '',
				'bdp_sale_tagfontsize'                     => '18',
				'bdp_sale_tag_font_weight'                 => '700',
				'bdp_sale_tag_font_line_height'            => '1.5',
				'bdp_sale_tag_font_italic'                 => '0',
				'bdp_sale_tag_font_text_transform'         => 'none',
				'bdp_sale_tag_font_text_decoration'        => 'none',
				'display_product_price'                    => '0',
				'display_product_rating'                   => '0',
				'bdp_star_rating_bg_color'                 => '#000000',
				'bdp_star_rating_color'                    => '#d3ced2',
				'bdp_star_rating_alignment'                => 'left',
				'bdp_star_rating_paddingleft'              => '5',
				'bdp_star_rating_paddingright'             => '5',
				'bdp_star_rating_paddingtop'               => '5',
				'bdp_star_rating_paddingbottom'            => '5',
				'bdp_star_rating_marginleft'               => '5',
				'bdp_star_rating_marginright'              => '5',
				'bdp_star_rating_margintop'                => '5',
				'bdp_star_rating_marginbottom'             => '5',
				'bdp_pricetext_alignment'                  => 'left',
				'bdp_pricetext_paddingleft'                => '5',
				'bdp_pricetext_paddingright'               => '5',
				'bdp_pricetext_paddingtop'                 => '5',
				'bdp_pricetext_paddingbottom'              => '5',
				'bdp_pricetext_marginleft'                 => '5',
				'bdp_pricetext_marginright'                => '5',
				'bdp_pricetext_margintop'                  => '5',
				'bdp_pricetext_marginbottom'               => '5',
				'bdp_pricetextcolor'                       => '#444444',
				'bdp_pricefontface_font_type'              => '',
				'bdp_pricefontface'                        => '',
				'bdp_pricefontsize'                        => '18',
				'bdp_price_font_weight'                    => '700',
				'bdp_price_font_line_height'               => '1.5',
				'bdp_price_font_italic'                    => '0',
				'bdp_price_font_text_transform'            => 'none',
				'bdp_price_font_text_decoration'           => 'none',
				'bdp_addtocart_button_font_text_transform' => 'none',
				'bdp_addtocart_button_font_text_decoration' => 'none',
				'bdp_addtowishlist_button_font_text_transform' => 'none',
				'bdp_addtowishlist_button_font_text_decoration' => 'none',
				'bdp_price_font_letter_spacing'            => '0',
				'display_addtocart_button'                 => '0',
				'bdp_addtocart_button_fontface_font_type'  => '',
				'bdp_addtocart_button_fontface'            => '',
				'bdp_addtocart_button_fontsize'            => '14',
				'bdp_addtocart_button_font_weight'         => 'normal',
				'bdp_addtocart_button_font_italic'         => '0',
				'bdp_addtocart_button_letter_spacing'      => '0',
				'display_addtocart_button_line_height'     => '1.5',
				'bdp_addtowishlist_button_fontface_font_type' => '',
				'bdp_addtowishlist_button_fontface'        => '',
				'bdp_addtowishlist_button_fontsize'        => '14',
				'bdp_addtowishlist_button_font_weight'     => 'normal',
				'bdp_addtowishlist_button_font_italic'     => '0',
				'bdp_addtowishlist_button_letter_spacing'  => '0',
				'display_wishlist_button_line_height'      => '1.5',
				'bdp_addtocart_textcolor'                  => '#ffffff',
				'bdp_addtocart_backgroundcolor'            => '#777777',
				'bdp_addtocart_text_hover_color'           => '#ffffff',
				'bdp_addtocart_hover_backgroundcolor'      => '#333333',
				'bdp_addtocartbutton_borderleft'           => '0',
				'bdp_addtocartbutton_borderleftcolor'      => '',
				'bdp_addtocartbutton_borderright'          => '0',
				'bdp_addtocartbutton_borderrightcolor'     => '',
				'bdp_addtocartbutton_bordertop'            => '0',
				'bdp_addtocartbutton_bordertopcolor'       => '',
				'bdp_addtocartbutton_borderbottom'         => '0',
				'bdp_addtocartbutton_borderbottomcolor'    => '',
				'bdp_addtocartbutton_hover_borderleft'     => '0',
				'bdp_addtocartbutton_hover_borderleftcolor' => '',
				'bdp_addtocartbutton_hover_borderright'    => '0',
				'bdp_addtocartbutton_hover_borderrightcolor' => '',
				'bdp_addtocartbutton_hover_bordertop'      => '0',
				'bdp_addtocartbutton_hover_bordertopcolor' => '',
				'bdp_addtocartbutton_hover_borderbottom'   => '0',
				'bdp_addtocartbutton_hover_borderbottomcolor' => '',
				'display_addtocart_button_border_hover_radius' => '0',
				'bdp_addtocartbutton_padding_leftright'    => '10',
				'bdp_addtocartbutton_padding_topbottom'    => '10',
				'bdp_addtocartbutton_margin_leftright'     => '15',
				'bdp_addtocartbutton_margin_topbottom'     => '10',
				'bdp_addtocartbutton_alignment'            => 'left',
				'display_addtocart_button_border_radius'   => '0',
				'bdp_addtocart_button_left_box_shadow'     => '0',
				'bdp_addtocart_button_right_box_shadow'    => '0',
				'bdp_addtocart_button_top_box_shadow'      => '0',
				'bdp_addtocart_button_bottom_box_shadow'   => '0',
				'bdp_addtocart_button_box_shadow_color'    => '',
				'bdp_addtocart_button_hover_left_box_shadow' => '0',
				'bdp_addtocart_button_hover_right_box_shadow' => '0',
				'bdp_addtocart_button_hover_top_box_shadow' => '0',
				'bdp_addtocart_button_hover_bottom_box_shadow' => '0',
				'bdp_addtocart_button_hover_box_shadow_color' => '',
				'display_addtowishlist_button'             => '0',
				'bdp_wishlistbutton_alignment'             => 'left',
				'bdp_cart_wishlistbutton_alignment'        => 'left',
				'bdp_wishlistbutton_on'                    => '1',
				'bdp_wishlist_textcolor'                   => '#ffffff',
				'bdp_wishlist_text_hover_color'            => '#ffffff',
				'bdp_wishlist_backgroundcolor'             => '#777777',
				'bdp_wishlist_hover_backgroundcolor'       => '#333333',
				'display_wishlist_button_border_radius'    => '0',
				'bdp_wishlistbutton_borderleft'            => '0',
				'bdp_wishlistbutton_borderleftcolor'       => '',
				'bdp_wishlistbutton_borderright'           => '0',
				'bdp_wishlistbutton_borderrightcolor'      => '',
				'bdp_wishlistbutton_bordertop'             => '0',
				'bdp_wishlistbutton_bordertopcolor'        => '',
				'bdp_wishlistbutton_borderbuttom'          => '0',
				'bdp_wishlistbutton_borderbottomcolor'     => '',
				'display_wishlist_button_border_hover_radius' => '0',
				'bdp_wishlistbutton_hover_borderleft'      => '0',
				'bdp_wishlistbutton_hover_borderleftcolor' => '',
				'bdp_wishlistbutton_hover_borderright'     => '0',
				'bdp_wishlistbutton_hover_borderrightcolor' => '',
				'bdp_wishlistbutton_hover_bordertop'       => '0',
				'bdp_wishlistbutton_hover_bordertopcolor'  => '',
				'bdp_wishlistbutton_hover_borderbuttom'    => '0',
				'bdp_wishlistbutton_hover_borderbottomcolor' => '',
				'bdp_wishlistbutton_padding_leftright'     => '10',
				'bdp_wishlistbutton_padding_topbottom'     => '10',
				'bdp_wishlistbutton_margin_leftright'      => '10',
				'bdp_wishlistbutton_margin_topbottom'      => '10',
				'bdp_bg_image_id'                          => '',
				'social_style'                             => '1',
				'social_icon_style'                        => '1',
				'social_icon_size'                         => '1',
				'default_icon_theme'                       => '1',
				'facebook_link'                            => '1',
				'facebook_link_with_count'                 => '1',
				'linkedin_link'                            => '1',
				'pinterest_link'                           => '1',
				'pinterest_link_with_count'                => '1',
				'twitter_link'                             => '1',
				'pocket_link'                              => '0',
				'telegram_link'                            => '0',
				'email_link'                               => '1',
				'whatsapp_link'                            => '0',
				'social_count_position'                    => 'right',
				'savedata'                                 => 'Save Changes',
				'display_acf_field'                        => '0',
				'bdp_acf_field'                            => '',
				'display_download_price'                   => '0',
				'bdp_edd_price_alignment'                  => 'left',
				'bdp_edd_price_paddingleft'                => '5',
				'bdp_edd_price_paddingright'               => '5',
				'bdp_edd_price_paddingtop'                 => '5',
				'bdp_edd_price_paddingbottom'              => '5',
				'bdp_edd_price_color'                      => '#444444',
				'bdp_edd_pricefontface_font_type'          => '',
				'bdp_edd_pricefontface'                    => '',
				'bdp_edd_pricefontsize'                    => '18',
				'bdp_edd_price_font_weight'                => '700',
				'bdp_edd_price_font_line_height'           => '1.5',
				'bdp_edd_price_font_italic'                => '0',
				'bdp_edd_price_font_letter_spacing'        => '0',
				'bdp_edd_price_font_text_decoration'       => 'none',
			);
			$table_name           = $wpdb->prefix . 'blog_designer_pro_shortcodes';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) == $table_name ) {
				$insert_shortcode = $wpdb->insert(
					$table_name,
					array(
						'shortcode_name' => esc_html__( 'Sample Blog Layout', 'blog-designer-pro' ),
						'bdsettings'     => maybe_serialize( $sample_blog_settings ),
					)
				);
				if ( false == $insert_shortcode ) {
					wp_die( esc_html__( 'Sample Blog Layout not created.', 'blog-designer-pro' ) );
				} else {
					$layout_id       = $wpdb->insert_id;
					$blog_args       = array(
						'ID'           => $page_id,
						'post_content' => '[wp_blog_designer id="' . $layout_id . '"]',
					);
					$layout_inserted = wp_update_post( $blog_args );
					Bdp_Ajax_Actions::bdp_admin_notice_pro_layouts_dismiss();
					Bdp_Ajax_Actions::bdp_create_layout_from_blog_designer_dismiss();
					if ( $layout_inserted ) {
						$blog_url = get_permalink( $page_id );
						$edit_url = admin_url() . 'admin.php?page=add_shortcode&action=edit&id=' . $layout_id . '&create=sample';
						echo "<script type=\"text/javascript\">window.open('" . esc_url( $blog_url ) . "l', '_blank');window.open('" . esc_url( $edit_url ) . "', '_self');</script>";
					}
				}
			} else {
				wp_die( esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' ) );
			}
		}
	}
	/**
	 * Include Blog template
	 *
	 * @param string $bdp_theme theme.
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class class.
	 * @param string $prev_year year.
	 * @param string $paged page.
	 * @param string $count_sticky sticky.
	 * @param string $alter_val alter.
	 * @param string $tabbed_post_style style.
	 * @return html
	 */
	public static function get_blog_template( $bdp_theme, $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style ) {
		ob_start();
		$allow_bdp_theme = ['blog/accordion.php', 'blog/banner.php', 'blog/blog_carousel.php', 'blog/blog_grid_box.php', 'blog/boxy.php', 'blog/boxy-clean.php', 'blog/brit_co.php', 'blog/brite.php', 'blog/chapter.php', 'blog/classical.php', 'blog/clicky.php', 'blog/colorful_sliding.php', 'blog/cool_horizontal.php', 'blog/cover.php', 'blog/crayon_slider.php', 'blog/deport.php', 'blog/easy_timeline.php', 'blog/elina.php', 'blog/evolution.php', 'blog/explore.php', 'blog/fairy.php', 'blog/famous.php', 'blog/flip_book_3d.php', 'blog/foodbox.php', 'blog/glamour.php', 'blog/glossary.php', 'blog/hoverbic.php', 'blog/hub.php', 'blog/index.php', 'blog/invert-grid.php', 'blog/lightbreeze.php', 'blog/leest.php', 'blog/masonry_timeline.php', 'blog/media-grid.php', 'blog/minimal.php', 'blog/miracle.php', 'blog/my_diary.php', 'blog/navia.php', 'blog/neaty_block.php', 'blog/news.php', 'blog/nicy.php', 'blog/offer_blog.php', 'blog/overlay_horizontal.php', 'blog/pedal.php', 'blog/pretty.php', 'blog/quci.php', 'blog/region.php', 'blog/roctangle.php', 'blog/sallet_slider.php', 'blog/schedule.php', 'blog/sharpen.php', 'blog/soft_block.php', 'blog/spektrum.php', 'blog/steps.php', 'blog/story.php', 'blog/sunshiny_slider.php', 'blog/tabbed.php', 'blog/tagly.php', 'blog/threed_carousel.php', 'blog/ticker.php', 'blog/timeline.php', 'blog/winter.php', 'blog/wise_block.php', 'archive/accordion.php', 'archive/archive.php', 'archive/banner.php', 'archive/blog_carousel.php', 'archive/blog_grid_box.php', 'archive/boxy.php', 'archive/boxy-clean.php', 'archive/brit_co.php', 'archive/brite.php', 'archive/chapter.php', 'archive/classical.php', 'archive/clicky.php', 'archive/colorful_sliding.php', 'archive/cool_horizontal.php', 'archive/cover.php', 'archive/crayon_slider.php', 'archive/deport.php', 'archive/easy_timeline.php', 'archive/elina.php', 'archive/evolution.php', 'archive/explore.php', 'archive/fairy.php', 'archive/famous.php', 'archive/flip_book_3d.php', 'archive/foodbox.php', 'archive/glamour.php', 'archive/glossary.php', 'archive/hoverbic.php', 'archive/hub.php', 'archive/invert-grid.php', 'archive/lightbreeze.php', 'archive/leest.php', 'archive/masonry_timeline.php', 'archive/media-grid.php', 'archive/minimal.php', 'archive/miracle.php', 'archive/my_diary.php', 'archive/navia.php', 'archive/neaty_block.php', 'archive/news.php', 'archive/nicy.php', 'archive/offer_blog.php', 'archive/overlay_horizontal.php', 'archive/pedal.php', 'archive/pretty.php', 'archive/quci.php', 'archive/region.php', 'archive/roctangle.php', 'archive/sallet_slider.php', 'archive/schedule.php', 'archive/sharpen.php', 'archive/soft_block.php', 'archive/spektrum.php', 'archive/steps.php', 'archive/story.php', 'archive/sunshiny_slider.php', 'archive/tabbed.php', 'archive/tagly.php', 'archive/threed_carousel.php', 'archive/ticker.php', 'archive/timeline.php', 'archive/winter.php', 'archive/wise_block.php', 'single/boxy.php', 'single/boxy-clean.php', 'single/brit_co.php', 'single/brite.php', 'single/chapter.php', 'single/classical.php', 'single/clicky.php', 'single/cool_horizontal.php', 'single/cover.php', 'single/deport.php', 'single/easy_timeline.php', 'single/elina.php', 'single/evolution.php', 'single/explore.php', 'single/fairy.php', 'single/famous.php', 'single/foodbox.php', 'single/glamour.php', 'single/glossary.php', 'single/hub.php', 'single/index.php', 'single/invert-grid.php', 'single/lightbreeze.php', 'single/masonry_timeline.php', 'single/media-grid.php', 'single/minimal.php', 'single/miracle.php', 'single/my_diary.php', 'single/navia.php', 'single/neaty_block.php', 'single/news.php', 'single/nicy.php', 'single/offer_blog.php', 'single/overlay_horizontal.php', 'single/pedal.php', 'single/pretty.php', 'single/quci.php', 'single/region.php', 'single/roctangle.php', 'single/schedule.php', 'single/sharpen.php', 'single/single.php', 'single/soft_block.php', 'single/spektrum.php', 'single/steps.php', 'single/story.php', 'single/tagly.php', 'single/timeline.php', 'single/winter.php', 'single/wise_block.php'];		
		if(in_array($bdp_theme,$allow_bdp_theme)){
			$theme_path = get_stylesheet_directory() . '/bdp_templates/' . $bdp_theme;
			if ( ! file_exists( $theme_path ) ) {
				$theme_path = BLOGDESIGNERPRO_DIR . 'bdp_templates/' . $bdp_theme;
			}
			if ( file_exists( $theme_path ) ) {
				include $theme_path;
			}
		}
		return ob_get_clean();
	}
	/**
	 * Include Archive template
	 *
	 * @param string $bdp_theme theme.
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class class.
	 * @param string $prev_year year.
	 * @param string $paged page.
	 * @param string $count_sticky sticky.
	 * @param string $alter_val alter.
	 * @param string $tabbed_post_style style.
	 * @return html
	 */
	public static function get_archive_template( $bdp_theme, $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style ) {
		ob_start();
		$theme_path = get_stylesheet_directory() . '/bdp_templates/' . $bdp_theme;
		if ( ! file_exists( $theme_path ) ) {
			$theme_path = BLOGDESIGNERPRO_DIR . 'bdp_templates/' . $bdp_theme;
		}
		if ( file_exists( $theme_path ) ) {
			include $theme_path;
		}
		return ob_get_clean();
	}
	/**
	 * Include Blog load more template
	 *
	 * @param string $bdp_theme theme.
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class alter.
	 * @param string $prev_year prev_year.
	 * @param string $paged paged.
	 * @param string $count_sticky count_sticky.
	 */
	public static function get_blog_loadmore_template( $bdp_theme, $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky ) {
		$allow_bdp_theme = ['blog/accordion.php', 'blog/banner.php', 'blog/blog_carousel.php', 'blog/blog_grid_box.php', 'blog/boxy.php', 'blog/boxy-clean.php', 'blog/brit_co.php', 'blog/brite.php', 'blog/chapter.php', 'blog/classical.php', 'blog/clicky.php', 'blog/colorful_sliding.php', 'blog/cool_horizontal.php', 'blog/cover.php', 'blog/crayon_slider.php', 'blog/deport.php', 'blog/easy_timeline.php', 'blog/elina.php', 'blog/evolution.php', 'blog/explore.php', 'blog/fairy.php', 'blog/famous.php', 'blog/flip_book_3d.php', 'blog/foodbox.php', 'blog/glamour.php', 'blog/glossary.php', 'blog/hoverbic.php', 'blog/hub.php', 'blog/index.php', 'blog/invert-grid.php', 'blog/lightbreeze.php', 'blog/leest.php', 'blog/masonry_timeline.php', 'blog/media-grid.php', 'blog/minimal.php', 'blog/miracle.php', 'blog/my_diary.php', 'blog/navia.php', 'blog/neaty_block.php', 'blog/news.php', 'blog/nicy.php', 'blog/offer_blog.php', 'blog/overlay_horizontal.php', 'blog/pedal.php', 'blog/pretty.php', 'blog/quci.php', 'blog/region.php', 'blog/roctangle.php', 'blog/sallet_slider.php', 'blog/schedule.php', 'blog/sharpen.php', 'blog/soft_block.php', 'blog/spektrum.php', 'blog/steps.php', 'blog/story.php', 'blog/sunshiny_slider.php', 'blog/tabbed.php', 'blog/tagly.php', 'blog/threed_carousel.php', 'blog/ticker.php', 'blog/timeline.php', 'blog/winter.php', 'blog/wise_block.php', 'archive/accordion.php', 'archive/archive.php', 'archive/banner.php', 'archive/blog_carousel.php', 'archive/blog_grid_box.php', 'archive/boxy.php', 'archive/boxy-clean.php', 'archive/brit_co.php', 'archive/brite.php', 'archive/chapter.php', 'archive/classical.php', 'archive/clicky.php', 'archive/colorful_sliding.php', 'archive/cool_horizontal.php', 'archive/cover.php', 'archive/crayon_slider.php', 'archive/deport.php', 'archive/easy_timeline.php', 'archive/elina.php', 'archive/evolution.php', 'archive/explore.php', 'archive/fairy.php', 'archive/famous.php', 'archive/flip_book_3d.php', 'archive/foodbox.php', 'archive/glamour.php', 'archive/glossary.php', 'archive/hoverbic.php', 'archive/hub.php', 'archive/invert-grid.php', 'archive/lightbreeze.php', 'archive/leest.php', 'archive/masonry_timeline.php', 'archive/media-grid.php', 'archive/minimal.php', 'archive/miracle.php', 'archive/my_diary.php', 'archive/navia.php', 'archive/neaty_block.php', 'archive/news.php', 'archive/nicy.php', 'archive/offer_blog.php', 'archive/overlay_horizontal.php', 'archive/pedal.php', 'archive/pretty.php', 'archive/quci.php', 'archive/region.php', 'archive/roctangle.php', 'archive/sallet_slider.php', 'archive/schedule.php', 'archive/sharpen.php', 'archive/soft_block.php', 'archive/spektrum.php', 'archive/steps.php', 'archive/story.php', 'archive/sunshiny_slider.php', 'archive/tabbed.php', 'archive/tagly.php', 'archive/threed_carousel.php', 'archive/ticker.php', 'archive/timeline.php', 'archive/winter.php', 'archive/wise_block.php', 'single/boxy.php', 'single/boxy-clean.php', 'single/brit_co.php', 'single/brite.php', 'single/chapter.php', 'single/classical.php', 'single/clicky.php', 'single/cool_horizontal.php', 'single/cover.php', 'single/deport.php', 'single/easy_timeline.php', 'single/elina.php', 'single/evolution.php', 'single/explore.php', 'single/fairy.php', 'single/famous.php', 'single/foodbox.php', 'single/glamour.php', 'single/glossary.php', 'single/hub.php', 'single/index.php', 'single/invert-grid.php', 'single/lightbreeze.php', 'single/masonry_timeline.php', 'single/media-grid.php', 'single/minimal.php', 'single/miracle.php', 'single/my_diary.php', 'single/navia.php', 'single/neaty_block.php', 'single/news.php', 'single/nicy.php', 'single/offer_blog.php', 'single/overlay_horizontal.php', 'single/pedal.php', 'single/pretty.php', 'single/quci.php', 'single/region.php', 'single/roctangle.php', 'single/schedule.php', 'single/sharpen.php', 'single/single.php', 'single/soft_block.php', 'single/spektrum.php', 'single/steps.php', 'single/story.php', 'single/tagly.php', 'single/timeline.php', 'single/winter.php', 'single/wise_block.php'];
		if(in_array($bdp_theme,$allow_bdp_theme)){
			$theme_path = get_stylesheet_directory() . '/bdp_templates/' . $bdp_theme;
			if ( ! file_exists( $theme_path ) ) {
				$theme_path = BLOGDESIGNERPRO_DIR . 'bdp_templates/' . $bdp_theme;
			}
			if ( file_exists( $theme_path ) ) {
				include $theme_path;
			}
		}
	}
	/**
	 * Include selected template
	 *
	 * @param string $bdp_theme theme.
	 * @return void.
	 */
	public static function get_template( $bdp_theme ) {
		$allow_bdp_theme = ['blog/accordion.php', 'blog/banner.php', 'blog/blog_carousel.php', 'blog/blog_grid_box.php', 'blog/boxy.php', 'blog/boxy-clean.php', 'blog/brit_co.php', 'blog/brite.php', 'blog/chapter.php', 'blog/classical.php', 'blog/clicky.php', 'blog/colorful_sliding.php', 'blog/cool_horizontal.php', 'blog/cover.php', 'blog/crayon_slider.php', 'blog/deport.php', 'blog/easy_timeline.php', 'blog/elina.php', 'blog/evolution.php', 'blog/explore.php', 'blog/fairy.php', 'blog/famous.php', 'blog/flip_book_3d.php', 'blog/foodbox.php', 'blog/glamour.php', 'blog/glossary.php', 'blog/hoverbic.php', 'blog/hub.php', 'blog/index.php', 'blog/invert-grid.php', 'blog/lightbreeze.php', 'blog/leest.php', 'blog/masonry_timeline.php', 'blog/media-grid.php', 'blog/minimal.php', 'blog/miracle.php', 'blog/my_diary.php', 'blog/navia.php', 'blog/neaty_block.php', 'blog/news.php', 'blog/nicy.php', 'blog/offer_blog.php', 'blog/overlay_horizontal.php', 'blog/pedal.php', 'blog/pretty.php', 'blog/quci.php', 'blog/region.php', 'blog/roctangle.php', 'blog/sallet_slider.php', 'blog/schedule.php', 'blog/sharpen.php', 'blog/soft_block.php', 'blog/spektrum.php', 'blog/steps.php', 'blog/story.php', 'blog/sunshiny_slider.php', 'blog/tabbed.php', 'blog/tagly.php', 'blog/threed_carousel.php', 'blog/ticker.php', 'blog/timeline.php', 'blog/winter.php', 'blog/wise_block.php', 'archive/accordion.php', 'archive/archive.php', 'archive/banner.php', 'archive/blog_carousel.php', 'archive/blog_grid_box.php', 'archive/boxy.php', 'archive/boxy-clean.php', 'archive/brit_co.php', 'archive/brite.php', 'archive/chapter.php', 'archive/classical.php', 'archive/clicky.php', 'archive/colorful_sliding.php', 'archive/cool_horizontal.php', 'archive/cover.php', 'archive/crayon_slider.php', 'archive/deport.php', 'archive/easy_timeline.php', 'archive/elina.php', 'archive/evolution.php', 'archive/explore.php', 'archive/fairy.php', 'archive/famous.php', 'archive/flip_book_3d.php', 'archive/foodbox.php', 'archive/glamour.php', 'archive/glossary.php', 'archive/hoverbic.php', 'archive/hub.php', 'archive/invert-grid.php', 'archive/lightbreeze.php', 'archive/leest.php', 'archive/masonry_timeline.php', 'archive/media-grid.php', 'archive/minimal.php', 'archive/miracle.php', 'archive/my_diary.php', 'archive/navia.php', 'archive/neaty_block.php', 'archive/news.php', 'archive/nicy.php', 'archive/offer_blog.php', 'archive/overlay_horizontal.php', 'archive/pedal.php', 'archive/pretty.php', 'archive/quci.php', 'archive/region.php', 'archive/roctangle.php', 'archive/sallet_slider.php', 'archive/schedule.php', 'archive/sharpen.php', 'archive/soft_block.php', 'archive/spektrum.php', 'archive/steps.php', 'archive/story.php', 'archive/sunshiny_slider.php', 'archive/tabbed.php', 'archive/tagly.php', 'archive/threed_carousel.php', 'archive/ticker.php', 'archive/timeline.php', 'archive/winter.php', 'archive/wise_block.php', 'single/boxy.php', 'single/boxy-clean.php', 'single/brit_co.php', 'single/brite.php', 'single/chapter.php', 'single/classical.php', 'single/clicky.php', 'single/cool_horizontal.php', 'single/cover.php', 'single/deport.php', 'single/easy_timeline.php', 'single/elina.php', 'single/evolution.php', 'single/explore.php', 'single/fairy.php', 'single/famous.php', 'single/foodbox.php', 'single/glamour.php', 'single/glossary.php', 'single/hub.php', 'single/index.php', 'single/invert-grid.php', 'single/lightbreeze.php', 'single/masonry_timeline.php', 'single/media-grid.php', 'single/minimal.php', 'single/miracle.php', 'single/my_diary.php', 'single/navia.php', 'single/neaty_block.php', 'single/news.php', 'single/nicy.php', 'single/offer_blog.php', 'single/overlay_horizontal.php', 'single/pedal.php', 'single/pretty.php', 'single/quci.php', 'single/region.php', 'single/roctangle.php', 'single/schedule.php', 'single/sharpen.php', 'single/single.php', 'single/soft_block.php', 'single/spektrum.php', 'single/steps.php', 'single/story.php', 'single/tagly.php', 'single/timeline.php', 'single/winter.php', 'single/wise_block.php'];	
		if(in_array($bdp_theme,$allow_bdp_theme)){
			$theme_path = get_stylesheet_directory() . '/bdp_templates/' . $bdp_theme;
			if ( ! file_exists( $theme_path ) ) {
				$theme_path = BLOGDESIGNERPRO_DIR . 'bdp_templates/' . $bdp_theme;
			}
			if ( file_exists( $theme_path ) ) {
				include $theme_path;
			}
		}
	}
	/**
	 * Insert layout
	 *
	 * @param string $layout_name layout.
	 * @param array  $bdp_settings settings.
	 * @global object $wpdb
	 * @return int layout id
	 */
	public static function insert_layout( $layout_name, $bdp_settings ) {
		global $wpdb;
		$bdp_table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
			foreach ( $bdp_settings as $single_key => $single_val ) {
				if ( is_array( $single_val ) ) {
					foreach ( $single_val as $s_key => $s_val ) {
						$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
					}
				} elseif ( 'custom_css' === $single_key ) {
						$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
				} elseif ( 'mail_content' === $single_key ) {
					$bdp_settings[ $single_key ] = wp_kses( $single_val, Bdp_Admin_Functions::args_kses() );
				} else {
					$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
				}
			}
		}
		$insert = $wpdb->insert(
			$bdp_table_name,
			array(
				'shortcode_name' => sanitize_text_field( $layout_name ),
				'bdsettings'     => maybe_serialize( $bdp_settings ),
			),
			array( '%s', '%s' )
		);
		if ( false == $insert ) {
			return;
		} else {
			return $wpdb->insert_id;
		}
	}

	/**
	 * Get html of layout from layout id
	 *
	 * @param it    $layout_id layout.
	 * @param array $bdp_settings settings.
	 * @return html Blog Layout design
	 */
	public static function layout_view_portion( $layout_id, $bdp_settings ) {
		wp_reset_query();
		global $wp_query;
		$posts                     = Bdp_Posts::get_wp_query( $bdp_settings );
		$temp_query                = $wp_query;
		$loop                      = new WP_Query( $posts );
		$wp_query                  = $loop;
		$max_num_pages             = $wp_query->max_num_pages;
		$found_posts               = $wp_query->found_posts;
		$terms                     = $wp_query->terms;
		$sticky_posts              = get_option( 'sticky_posts' );
		$alter                     = 1;
		$class                     = '';
		$alter_class               = '';
		$prev_year                 = null;
		$bdp_theme                 = $bdp_settings['template_name'];
		$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
		if ( 1 == $bdp_template_name_changed ) {
			if ( 'classical' === $bdp_theme ) {
				$bdp_theme = 'nicy';
			} elseif ( 'lightbreeze' === $bdp_theme ) {
				$bdp_theme = 'sharpen';
			} elseif ( 'spektrum' === $bdp_theme ) {
				$bdp_theme = 'hub';
			}
		} else {
			update_option( 'bdp_template_name_changed', 0 );
		}
		$posts_per_page           = $bdp_settings['posts_per_page'];
		$bdp_post_offset          = ( isset( $bdp_settings['bdp_post_offset'] ) && ! empty( $bdp_settings['bdp_post_offset'] ) ) ? $bdp_settings['bdp_post_offset'] : '0';
		$unique_design_option     = isset( $bdp_settings['unique_design_option'] ) ? $bdp_settings['unique_design_option'] : '';
		$display_filter_by        = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
		$display_tabbed_filter_by = 'category';
		if ( isset( $bdp_settings['display_tabbed_filter_by'] ) ) {
			$display_tabbed_filter_by = $bdp_settings['display_tabbed_filter_by'];
		}
		$category          = '';
		$category_detail   = get_terms(
			array(
				'taxonomy'   => $display_filter_by,
				'hide_empty' => false,
			)
		);
		$same_height_class = '';
		$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0';
		if ( 1 == $apply_same_height ) {
			$same_height_class = 'same_height_all';
		}
		if ( isset( $bdp_settings['blog_unique_design'] ) && '' != $bdp_settings['blog_unique_design'] ) {
			$blog_unique_design = $bdp_settings['blog_unique_design'];
		} else {
			$blog_unique_design = 0;
		}
		if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
			$orderby = $bdp_settings['bdp_blog_order_by'];
		}
		$main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : '';

		$template = '';
		if ( $max_num_pages > 1 && 'load_more_btn' === $bdp_settings['pagination_type'] ) {
			$template .= "<div class='bdp-load-more-pre'>";
		}
		if ( $max_num_pages > 1 && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) {
			$template .= "<div class='bdp-load-onscroll-pre' id='bdp-load-onscroll-pre'>";
		}
		if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
			$template .= "<div class='bdp-row $bdp_theme $same_height_class'>";
		}
		if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
			$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . $bdp_settings['column_setting'] : 'column_layout_2';
			$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . $bdp_settings['column_setting_ipad'] : 'column_layout_ipad_2';
			$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . $bdp_settings['column_setting_tablet'] : 'column_layout_tablet_1';
			$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . $bdp_settings['column_setting_mobile'] : 'column_layout_mobile_1';
			$column_class          = $column_setting . ' ' . $column_setting_ipad . ' ' . $column_setting_tablet . ' ' . $column_setting_mobile;
			if ( 'roctangle' === $bdp_theme ) {
				$template .= "<div class='bdp-row masonry $column_class $bdp_theme'>";
			} else {
				$template .= "<div class='bdp-row $column_class $bdp_theme $same_height_class'>";
			}
		}
		if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme ) {

			$template .= '<div class="bdp-js-masonry masonry bdp_' . $bdp_theme . ' ' . $same_height_class . ' ' . $bdp_theme . '">';
		}
		if ( 'boxy-clean' === $bdp_theme ) {
			$template .= '<div class="blog_template boxy-clean"><ul>';
		}
		if ( 'accordion' === $bdp_theme ) {
			$template_icon_alignment    = ( isset( $bdp_settings['template_icon_alignment'] ) && '' != $bdp_settings['template_icon_alignment'] ) ? $bdp_settings['template_icon_alignment'] : 'icon-left';
			$bdp_accordion_layout_class = ( isset( $bdp_settings['accordion_template'] ) && '' != $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1';

			$template .= '<div class="blog_template accordion accordion_wrapper ' . $bdp_accordion_layout_class . ' ' . $template_icon_alignment . '">';
		}
		$slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation3';
		$cd_design_type    = isset( $bdp_settings['cd_design_type'] ) ? $bdp_settings['cd_design_type'] : 'design1';
		if ( 'crayon_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'blog_carousel' === $bdp_theme ) {
			$unique_id = wp_rand();

			if ( 'crayon_slider' === $bdp_theme && 'design2' === $cd_design_type ) {
				$template .= '<div class="blog_template slider_template ' . $bdp_theme . ' ' . $slider_navigation . ' slider_' . $unique_id . '"><ul class="slides ' . $cd_design_type . '">';
			} else {
				$template .= '<div class="blog_template slider_template ' . $bdp_theme . ' ' . $slider_navigation . ' slider_' . $unique_id . '"><ul class="slides">';
			}
		}
		if ( 'threed_carousel' === $bdp_theme ) {
			$unique_id = wp_rand();
			$template .= '<div class="bdp-3d-container blog_template slider_template  slider_' . $unique_id . ' "><ul class=" slides slider_gallery ">';
		}

		if ( 'flip_book_3d' === $bdp_theme ) {
			$unique_id = wp_rand();
			$template .= '<div class="bb-custom-wrapper blog_template slider_template  slider_' . $unique_id . ' "><div class=" bdp_flip_book_3d bb-bookblock">';
		}
		if ( 'story' === $bdp_theme ) {
			$template .= '<div class="bdp_template story story_wrapper">';
		}
		if ( 'brit_co' === $bdp_theme ) {
			$template .= '<div class="brit_co bdp_brit_co">';
		}
		if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
			$template .= '<div class="logbook flatLine flatNav flatButton">';
		}
		if ( 'my_diary' === $bdp_theme ) {
			$template .= '<div class="my_diary_wrapper">';
		}
		if ( 'elina' === $bdp_theme ) {
			$template .= '<div class="elina_wrapper">';
		}
		if ( 'masonry_timeline' === $bdp_theme ) {
			$template .= '<div class="masonry_timeline_wrapper">';
		}
		if ( 'brite' === $bdp_theme ) {
			$template .= '<div class="brite-wrapp">';
		}
		if ( 'foodbox' === $bdp_theme ) {
			$template .= '<div class="foodbox-blog-wrapp">';
		}
		if ( 'neaty_block' === $bdp_theme ) {
			$template .= '<div class="neaty_block_blog_wrapp">';
		}
		if ( 'wise_block' === $bdp_theme ) {
			$template .= '<div class="blog_template wise_block_wrapper ' . $same_height_class . ' ' . $bdp_theme . '">';
		}
		if ( 'soft_block' === $bdp_theme ) {
			$template .= '<div class="blog_template soft_block_wrapper">';
		}
		if ( 'schedule' === $bdp_theme ) {
			$template .= '<div class="blog_template schedule_wrapper">';
		}
		$prev_year    = null;
		$prev_year1   = null;
		$prev_month   = null;
		$count_sticky = 0;
		$alter_val    = 1;
		$tabbed_slug  = array();
		if ( $loop->have_posts() ) {
			if ( 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
				$template .= '<div class="blog_template bdp-grid-row">';
			}
			if ( 'media-grid' === $bdp_theme ) {
				$prev_year = 0;
			}
			if ( 'timeline' === $bdp_theme ) {
				$timeline_design = isset( $bdp_settings['timeline_design'] ) ? $bdp_settings['timeline_design'] : 'design1';
				if ( 'design2' === $timeline_design ) {
					$template .= '<div class="main-timeline-class ' . $timeline_design . '">';
				} else {
					$template .= '<div class="main-timeline-class">';
				}
				if ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'left_side' === $bdp_settings['bdp_timeline_layout'] ) {
					if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
						$template .= '<div class="timeline_bg_wrap left_side with_year"><div class="timeline_back clearfix">';
					} else {
						$template .= '<div class="timeline_bg_wrap left_side"><div class="timeline_back clearfix">';
					}
				} elseif ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'right_side' === $bdp_settings['bdp_timeline_layout'] ) {
					if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
						$template .= '<div class="timeline_bg_wrap right_side with_year"><div class="timeline_back clearfix">';
					} else {
						$template .= '<div class="timeline_bg_wrap right_side"><div class="timeline_back clearfix">';
					}
				} elseif ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'center' === $bdp_settings['bdp_timeline_layout'] ) {
					if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
						$template .= '<div class="timeline_bg_wrap center with_year"><div class="timeline_back clearfix">';
					} else {
						$template .= '<div class="timeline_bg_wrap center"><div class="timeline_back clearfix">';
					}
				} elseif ( 'date' === $orderby || 'modified' === $orderby ) {
						$template .= '<div class="timeline_bg_wrap date_order"><div class="timeline_back clearfix">';
				} else {
					$template .= '<div class="timeline_bg_wrap"><div class="timeline_back clearfix">';
				}
			}
			if ( 'easy_timeline' === $bdp_theme ) {
				$template .= '<div class="blog_template bdp_blog_template easy-timeline-wrapper"><ul class="easy-timeline" data-effect="' . $bdp_settings['easy_timeline_effect'] . '">';
			}
			if ( 'leest' === $bdp_theme ) {
				global $post;
				$leest_design = isset( $bdp_settings['leest_design'] ) ? $bdp_settings['leest_design'] : 'top-bottom';
				if ( 'top' === $leest_design ) {
					$template .= '<div class="main-leest-class ' . $leest_design . '">';
				} elseif ( 'bottom' === $leest_design ) {
					$template .= '<div class="main-leest-class ' . $leest_design . '">';
				} else {
					$template .= '<div class="main-leest-class ' . $leest_design . '">';
				}
				$col_class = self::column_class( $bdp_settings );

				$class_name = 'blog_template bdp_blog_template leest blog_masonry_item';
				if ( '' != $col_class ) {
					$class_name .= ' ' . $col_class;
				}
				$image_hover_effect = '';
				if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
					$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
				}
				$display_filter_by = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
				$category          = '';
				if ( ! empty( $display_filter_by ) ) {
					$category_detail = wp_get_post_terms( $post->ID, $display_filter_by );
					if ( ! empty( $category_detail ) ) {
						foreach ( $category_detail as $cd ) {
							$category .= $cd->slug . ' ';
						}
					}
				}
				$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
				?>
				<div class="<?php echo esc_attr( $class_name ); ?>  bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
				<?php

				$template .= '<div class="leest-wapper ' . $bdp_theme . ' ' . $slider_navigation . '"><div class="slick-slider list-slick-slider">';
			}
			if ( 'steps' === $bdp_theme ) {
				$template .= '<div class="blog_template bdp_blog_template steps-wrapper"><ul class="steps" data-effect="' . $bdp_settings['easy_timeline_effect'] . '">';
			}
			$ajax_preious_year  = '';
			$ajax_preious_month = '';
			$paged              = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$i                  = 1;
			$tabbed_slug        = array();
			$tabbed_post_style  = 0;
			if ( 'tabbed' === $bdp_theme ) {
				$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
				$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
				if ( ! empty( $terms ) ) {
					foreach ( $taxonomy_names as $taxonomy_name ) {
						if ( $taxonomy_name->name == $display_tabbed_filter_by ) {
							if ( isset( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] ) ) {
								$tax_count = count( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] );
								foreach ( $terms as $term ) {
									for ( $i = 0; $i < $tax_count; $i++ ) {
										if ( $term->name == $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ][ $i ] ) {
											$tabbed_slug[ $term->name ] = $term->slug;
										}
									}
								}
							}
						}
					}
					array_unique( $tabbed_slug );
				}
				if ( empty( $tabbed_slug ) ) {
					$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
					$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
					foreach ( $taxonomy_names as $taxonomy_name ) {
						foreach ( $terms as $term ) {
							$tabbed_slug[ $term->name ] = $term->slug;
						}
					}
					array_unique( $tabbed_slug );
				}
			} else {
				while ( $loop->have_posts() ) :
					$loop->the_post();
					if ( $bdp_theme ) {
						if ( 'timeline' === $bdp_theme ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = 'even_class';
							} else {
								$alter_class = 'odd_class';
							}
							if ( 'date' === $orderby || 'modified' === $orderby ) {
								if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
									$this_year = get_the_date( 'Y' );
									if ( $prev_year != $this_year ) {
										$prev_year = $this_year;
										$template .= '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . $prev_year . '</span></span></p>';
									}
								} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
									$this_year  = get_the_date( 'Y' );
									$this_month = get_the_time( 'M' );
									$prev_year  = $this_year;
									if ( $prev_month != $this_month ) {
										$prev_month = $this_month;
										$template  .= '<p class="timeline_year"><span class="year_wrap"><span class="year">' . $this_year . '</span><span class="month">' . $prev_month . '</span></span></p>';
									}
								}
								$ajax_preious_year  = get_the_date( 'Y' );
								$ajax_preious_month = get_the_time( 'M' );
							}
						}
						if ( 'story' === $bdp_theme ) {
							if ( 'date' === $orderby || 'modified' === $orderby ) {
								$this_year = get_the_date( 'Y' );
								if ( $prev_year1 != $this_year ) {
									$prev_year1 = $this_year;
									$prev_year  = 0;
								} elseif ( $prev_year1 == $this_year ) {
									$prev_year = 1;
								}
							} else {
								$prev_year = get_the_date( 'Y' );
							}
						}
						if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = ' alternative-back ';
							} else {
								$alter_class = '';
							}
						}
						if ( 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'story' === $bdp_theme || 'fairy' === $bdp_theme || 'clicky' === $bdp_theme ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = 'even_class';
							} else {
								$alter_class = 'odd_class';
							}
						}
						if ( 'leest' === $bdp_theme ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = 'text-top';
							} else {
								$alter_class = 'text-bottom';
							}
						}
						if ( 'timeline' === $bdp_theme ) {
							// if( 'design2' == $bdp_settings['timeline_design'] && 'left' == $bdp_settings['bdp_timeline_layout'] ) {
							// if ( 'even_class' === $alter_class ) {
							// $alter_class = 'odd_class';
							// $alter++;
							// }
							// }.
							if ( 0 == $alter % 2 ) {
								if ( isset( $bdp_settings['timeline_design'] ) && 'design2' == $bdp_settings['timeline_design'] && isset( $bdp_settings['bdp_timeline_layout'] ) && ( 'right_side' == $bdp_settings['bdp_timeline_layout'] || 'center' == $bdp_settings['bdp_timeline_layout'] ) ) {
									if ( 'even_class' === $alter_class ) {
										$alter_class = 'odd_class';
										++$alter;
									}
								} else {
									$alter_class = 'even_class';
								}
							} elseif ( 0 != $alter % 2 && isset( $bdp_settings['timeline_design'] ) && 'design2' == $bdp_settings['timeline_design'] && isset( $bdp_settings['bdp_timeline_layout'] ) && 'left_side' == $bdp_settings['bdp_timeline_layout'] ) {
								$alter_class = 'even_class';
								++$alter;
							} else {
								$alter_class = 'odd_class';
							}
						}
						if ( 'media-grid' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
							$alter_val = $alter; // are we on page one?
						}
						if ( 1 == $blog_unique_design ) {
							if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'clicky' === $bdp_theme ) {
								$alter_val = $alter; // are we on page one?
								if ( 'first_post' === $unique_design_option ) {
									if ( 1 == $paged ) {
										if ( 1 == $alter ) {
											$prev_year = 0;
										} else {
											$prev_year = 1;
										}
									} else {
										$prev_year = 1;
									}
								} elseif ( 'featured_posts' === $unique_design_option ) {
									if ( 1 == $paged ) {
										if ( in_array( get_the_ID(), $sticky_posts ) ) {
											$count_sticky = count( $sticky_posts );
											$prev_year    = 0;
										} else {
											$count_sticky = count( $sticky_posts );
											$prev_year    = 1;
										}
									} else {
										$prev_year = 1;
									}
								}
							}
							if ( 'media-grid' === $bdp_theme ) {
								$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
								$alter_val      = $alter; // are we on page one?
								if ( 'first_post' === $unique_design_option ) {
									if ( $column_setting >= 2 && $alter <= 2 ) {
										$prev_year = 0;
									} elseif ( 1 == $paged ) {
										if ( 1 == $alter ) {
											$prev_year = 0;
										} else {
											$prev_year = 1;
										}
									} else {
										$prev_year = 1;
									}
								} elseif ( 'featured_posts' === $unique_design_option ) {
									if ( 1 == $paged ) {
										if ( in_array( get_the_ID(), $sticky_posts ) ) {
											$count_sticky = count( $sticky_posts );
											$prev_year    = 0;
										} else {
											$count_sticky = count( $sticky_posts );
											$prev_year    = 1;
										}
									} else {
										$prev_year = 1;
									}
								}
							}
						}
						if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
							$alter_class = $alter;
						}

						$template .= self::get_blog_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style );
						++$alter;
					}
					$template .= apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged );
					++$i;
				endwhile;
			}
			if ( 'timeline' === $bdp_theme ) {
				$template .= '</div></div></div>';
			}
			if ( 'easy_timeline' === $bdp_theme || 'steps' === $bdp_theme ) {
				$template .= '</ul></div>';
			}
			if ( 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
				$template .= '</div>';
			}
		} else {
			$template .= '<div class="bdp_no_post_fond">' . esc_html__( 'No posts found.', 'blog-designer-pro' ) . '</div>';
		}
		if ( 1 != $alter % 2 && ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme ) ) {
			do_action( 'bdp_separator_after_post' );
			$template .= '</div>';
		} elseif ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'integer' === $bdp_theme || 'advice' === $bdp_theme || 'minimal' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'famous' === $bdp_theme ) {
			?>
			<script>
				jQuery(window).on('load', function() {
					bdp_get_famous_height_<?php echo esc_attr( $layout_id ); ?>();
				});
				jQuery(document).ready(function() {
					bdp_get_famous_height_<?php echo esc_attr( $layout_id ); ?>();
				});

				function bdp_get_famous_height_<?php echo esc_attr( $layout_id ); ?>() {
					var famous_content_heights = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .famous-grid .post-body-div").map(function() {
							return jQuery(this).height();
						}).get(),
						famous_content_heights = Math.max.apply(null, famous_content_heights);
					jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .bdp-row.famous').each(function() {
						var famous_total_height = famous_content_heights;
						jQuery(this).find('.famous-grid').height(famous_total_height);
					});
				}
			</script>
			<?php
		}

		if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'blog_carousel' === $bdp_theme ) {
			$template .= '</ul></div>';
			if ( isset( $bdp_settings['post_slider_thumb'] ) && 1 == $bdp_settings['post_slider_thumb'] ) {
				global $post;
				$template .= '<div class="post_slider_thumbnail slider_template ' . $slider_navigation . ' slider_' . $unique_id . '"><ul class="slides">';
				while ( have_posts() ) :
					the_post();

					$post_thumbnail = 'full';
					$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
					$template      .= '<li>' . $thumbnail . '</li>';
				endwhile;
				$template .= '</ul></div>';
			}
		}
		if ( 'threed_carousel' === $bdp_theme ) {
			$template .= '</ul></div>';
		}
		if ( 'leest' === $bdp_theme ) {
			$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
			if ( 1 == $display_slider_controls ) {
				$pre  = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'slick-prev';
				$next = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'slick-next';
			}
		}
		if ( 'leest' === $bdp_theme ) {
			$template .= '</div></div>';
		}
		if ( 'flip_book_3d' === $bdp_theme ) {
			$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
			if ( 1 == $display_slider_controls ) {
				$pre  = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
				$next = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
			}

			$template .= '<nav><span id="bb-nav-prev" aria-label="Previous" tabindex="0" role="button"  data-role="none"  class="bb-custom-icon bb-custom-icon-arrow-left bd-arrows left bd-left-arrow  ' . $pre . ' "></span> <span id="bb-nav-next" aria-label="Previous" tabindex="0" role="button" data-role="none" class="bb-custom-icon bb-custom-icon-arrow-right bd-arrows right bd-right-arrow  ' . $next . '" ></span></nav></div></div>';
			// if ( isset( $bdp_settings['post_slider_thumb'] ) && 1 == $bdp_settings['post_slider_thumb'] ) {
			// $template .= '<div class="post_slider_thumbnail slider_template '. $slider_navigation .' slider_'. $unique_id.'"><ul class="slides">';
			// while ( have_posts() ) :
			// the_post();.

			// $post_thumbnail = 'full';
			// $thumbnail = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), get_the_ID() );
			// $template .= '<li>'. $thumbnail. '</li>';
			// endwhile;
			// $template .= '</ul></div>';
			// }.
		}

		if ( 'accordion' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme || 'story' === $bdp_theme ) ) {
			$template .= '</div>';
		}
		if ( 'brit_co' === $bdp_theme ) {
			$template .= '</div>';
			?>
			<script>
				jQuery(window).on('load', function() {
					bdp_get_brit_co_height_<?php echo esc_attr( $layout_id ); ?>();
				});
				jQuery(document).ready(function() {
					bdp_get_brit_co_height_<?php echo esc_attr( $layout_id ); ?>();
				});

				function bdp_get_brit_co_height_<?php echo esc_attr( $layout_id ); ?>() {
					var heights = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .image_wrapper").map(function() {
							return jQuery(this).height()
						}).get(),
						maxHeight = Math.max.apply(null, heights);
					var content_heights = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .content_wrapper").map(function() {
							return jQuery(this).height();
						}).get(),
						content_height = Math.max.apply(null, content_heights);
					jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .britco').each(function() {
						var total_height = maxHeight + content_height + 5;
						jQuery(this).find('.bdp_blog_wraper').height(total_height);
					});
				}
			</script>
			<?php
		}
		if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'my_diary' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'elina' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'masonry_timeline' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'brite' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'foodbox' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'neaty_block' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'wise_block' === $bdp_theme || 'soft_block' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'schedule' === $bdp_theme ) {
			$template .= '</div>';
		}
		if ( 'wise_block' === $bdp_theme ) {
			?>
			<script>
				jQuery(window).on('load', function() {
					bdp_get_wise_block_height_<?php echo esc_attr( $layout_id ); ?>()
				});
				jQuery(document).ready(function() {
					bdp_get_wise_block_height_<?php echo esc_attr( $layout_id ); ?>()
				});

				function bdp_get_wise_block_height_<?php echo esc_attr( $layout_id ); ?>() {
					var wise_block_heights = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .wise_block_blog").map(function() {
							return jQuery(this).height()
						}).get(),
						wise_block_heights = Math.max.apply(null, wise_block_heights);
					jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .wise_block_wrapper').each(function() {
						var wise_block_total_height = wise_block_heights;
						// jQuery(this).find('.wise_block_blog').height(wise_block_total_height)
					})
				}
			</script>
			<?php
		}
		if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme ) {
			$template .= '</ul></div>';
		}
		$slider_array   = array( 'leest', 'cool_horizontal', 'overlay_horizontal', 'crayon_slider', 'sunshiny_slider', 'sallet_slider', 'colorful_sliding', 'blog_carousel', 'threed_carousel', 'flip_book_3d' );
		$display_filter = isset( $bdp_settings['display_filter'] ) ? $bdp_settings['display_filter'] : '0';
		if ( ! in_array( $bdp_theme, $slider_array ) && 'no_pagination' != $bdp_settings['pagination_type'] ) {
			if ( $max_num_pages > 1 && 'load_more_btn' === $bdp_settings['pagination_type'] ) {
				$template       .= '</div>';
				$is_loadmore_btn = '';
				if ( $max_num_pages > 1 ) {
					$is_loadmore_btn = '';
				} else {
					$is_loadmore_btn = '1';
				}
				if ( is_front_page() ) {
					$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
				} else {
					$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				}
				$template .= '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';
				$template .= '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
				$template .= '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . $posts_per_page . '" />';
				$template .= '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . $max_num_pages . '" />';
				$template .= '<input type="hidden" name="blog_template" id="blog_template" value="' . $bdp_theme . '" />';
				$template .= '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
				$template .= '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . $layout_id . '" />';
				if ( 'timeline' === $bdp_theme ) {
					$template .= '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . $ajax_preious_year . '" />';
					$template .= '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . $ajax_preious_month . '" />';
				}
				$template .= Bdp_Utility::get_loader( $bdp_settings );
				$template .= '</form>';
				if ( '' == $is_loadmore_btn ) {
					$class     = isset( $bdp_settings['load_more_button_template'] ) ? $bdp_settings['load_more_button_template'] : 'template-1';
					$template .= '<div class="bdp-load-more text-center" style="float:left;width:100%">';
					$template .= '<a href="javascript:void(0);" class="button bdp-load-more-btn ' . $class . '">';
					if ( 'template-3' === $class ) {
						$template .= '<span class="bdp-lmb-top"></span>';
					}
					$template .= ( isset( $bdp_settings['loadmore_button_text'] ) && '' != $bdp_settings['loadmore_button_text'] ) ? $bdp_settings['loadmore_button_text'] : esc_html__( 'Load More', 'blog-designer-pro' );
					if ( 'template-3' === $class ) {
						$template .= '<span class="bdp-lmb-bottom"></span>';
					}
					$template .= '</a>';
					$template .= '</div>';
				}
			} elseif ( $max_num_pages > 1 && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) {
				$template            .= '</div>';
				$is_load_onscroll_btn = '';
				if ( $max_num_pages > 1 ) {
					$is_load_onscroll_btn = '';
				} else {
					$is_load_onscroll_btn = '1';
				}
				if ( is_front_page() ) {
					$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
				} else {
					$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				}
				$template .= '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';

				$template .= '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
				if ( 'story' === $bdp_theme ) {
					$template .= '<input type="hidden" name="this_year" id="this_year" value="' . $this_year . '" />';
				}
				$template .= '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . $posts_per_page . '" />';
				$template .= '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . $max_num_pages . '" />';
				$template .= '<input type="hidden" name="blog_template" id="blog_template" value="' . $bdp_theme . '" />';
				$template .= '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
				$template .= '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . $layout_id . '" />';
				if ( 'timeline' === $bdp_theme ) {
					$template .= '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . $ajax_preious_year . '" />';
					$template .= '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . $ajax_preious_month . '" />';
				}
				$template .= Bdp_Utility::get_loader( $bdp_settings );

				$template .= '</form>';
				if ( '' == $is_load_onscroll_btn ) {
					$class     = '';
					$template .= '<div class="bdp-load-onscroll text-center">';
					$template .= '<a href="javascript:void(0);" class="button bdp-load-onscroll-btn ' . $class . '">';
					$template .= esc_html__( 'Loading Posts', 'blog-designer-pro' ) . '</a>';
					$template .= '</div>';
				}
			}
			if ( 'paged' === $bdp_settings['pagination_type'] ) {
				$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				if ( isset( $bdp_settings['template_category'] ) ) {
					$filter_terms = is_array( $bdp_settings['template_category'] ) ? implode( ',', $bdp_settings['template_category'] ) : $filter_terms;
				} elseif ( isset( $bdp_settings['template_tags'] ) ) {
					$filter_terms = is_array( $bdp_settings['template_tags'] ) ? implode( ',', $bdp_settings['template_tags'] ) : $bdp_settings['template_tags'];
				}
				$filter_terms = isset( $filter_terms ) ? $filter_terms : '';
				$template    .= '<form class="ddd" name="bdp-paged-hidden" id="bdp-paged-hidden">';
				$template    .= '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
				$template    .= '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . $posts_per_page . '" />';
				$template    .= '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . $max_num_pages . '" />';
				$template    .= '<input type="hidden" name="found_posts" id="found_posts" value="' . $found_posts . '" />';
				$template    .= '<input type="hidden" name="blog_template" id="blog_template" value="' . $bdp_theme . '" />';
				$template    .= '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
				$template    .= '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . $layout_id . '" />';
				$template    .= '<input type="hidden" name="terms" id="terms" value="' . $filter_terms . '" />';
				if ( 1 == $display_filter ) {
					$template .= '<input type="hidden" name="filter_type" id="filter_type" value="' . $display_filter_by . '" />';
					$template .= '<input type="hidden" name="filter_category" id="filter_category" value="" />';
					$template .= '<input type="hidden" name="cat_tag_count" id="cat_tag_count" value="" />';
					$template .= '<input type="hidden" name="filter_tag" id="filter_tag" value="" />';
				}
				$template           .= '</form>';
				$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
				$template           .= '<div class="wl_pagination_box ' . $pagination_template . '">';
				$template           .= wp_kses( Bdp_Posts::standard_paging_nav( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
				$template           .= '</div>';
			}
		}
		if ( $loop->have_posts() ) {
			if ( 0 == $display_filter ) {
				if ( ( ( isset( $bdp_settings['filter_category'] ) && 1 == $bdp_settings['filter_category'] ) ) || ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) || ( isset( $bdp_settings['filter_tags'] ) && 1 == $bdp_settings['filter_tags'] ) ) {
					if ( ! wp_style_is( 'choosen-handle-css' ) ) {
						wp_enqueue_style( 'choosen-handle-css' );
					}
					if ( ! wp_script_is( 'choosen-handle-script' ) ) {
						wp_enqueue_script( 'choosen-handle-script' );
					}
					$filter_array = array( 'boxy', 'boxy-clean', 'cool_horizontal', 'overlay_horizontal', 'news', 'invert-grid', 'brit_co', 'media-grid' );
					if ( in_array( $bdp_theme, $filter_array ) ) {
						?>
						<form name="bdp-filer-change" id="bdp-filer-change">
							<?php
							echo '<div class="bdp_filter_option">';
							if ( 'post' === $bdp_settings['custom_post_type'] ) {
								esc_html_e( 'Choose from below options to filter your posts', 'blog-designer-pro' );
								echo '<br/>';
								if ( isset( $bdp_settings['filter_category'] ) && 1 == $bdp_settings['filter_category'] ) {
									$categories = get_categories();
									if ( isset( $bdp_settings['template_category'] ) ) {
										if ( isset( $bdp_settings['exclude_category_list'] ) ) {
											?>
											<select id="filter_cat" name="filter_cat[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose category', 'blog-designer-pro' ); ?> " multiple="multiple">
												<?php
												foreach ( $categories as $category ) {
													if ( false == in_array( $category->term_id, $bdp_settings['template_category'] ) ) {
														?>
														<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
														<?php
													}
												}
												?>
											</select>
											<?php
										} else {
											?>
											<select id="filter_cat" name="filter_cat[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> category" multiple="multiple">
												<?php
												foreach ( $categories as $category ) {
													if ( in_array( $category->term_id, $bdp_settings['template_category'] ) ) {
														?>
														<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
														<?php
													}
												}
												?>
											</select>
											<?php
										}
									} else {
										?>
										<select id="filter_cat" name="filter_cat[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> category" multiple="multiple">
											<?php
											foreach ( $categories as $category ) {
												?>
												<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
												<?php
											}
											?>
										</select>
										<?php
									}
								}
								if ( isset( $bdp_settings['filter_tags'] ) && 1 == $bdp_settings['filter_tags'] ) {
									$tags = get_terms( 'post_tag' );
									if ( isset( $bdp_settings['template_tags'] ) ) {
										if ( isset( $bdp_settings['exclude_tag_list'] ) ) {
											?>
											<select id="filter_tag" name="filter_tag[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> tag" multiple="multiple">
												<?php
												foreach ( $tags as $tag ) {
													if ( false == in_array( $tag->term_id, $bdp_settings['template_tags'] ) ) {
														?>
														<option value="<?php echo esc_attr( $tag->term_id ); ?>"><?php echo esc_html( $tag->name ); ?></option>
														<?php
													}
												}
												?>
											</select>
											<?php
										} else {
											?>
											<select id="filter_tag" name="filter_tag[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> tag" multiple="multiple">
												<?php
												foreach ( $tags as $tag ) {
													if ( in_array( $tag->term_id, $bdp_settings['template_tags'] ) ) {
														?>
														<option value="<?php echo esc_attr( $tag->term_id ); ?>"><?php echo esc_html( $tag->name ); ?></option>
														<?php
													}
												}
												?>
											</select>
											<?php
										}
									} else {
										?>
										<select id="filter_tag" name="filter_tag[]" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> tag" multiple="multiple">
											<?php
											foreach ( $tags as $tag ) {
												?>
												<option value="<?php echo esc_attr( $tag->term_id ); ?>"><?php echo esc_html( $tag->name ); ?></option>
												<?php
											}
											?>
										</select>
										<?php
									}
								}
							} else {
								$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'] );
								esc_html_e( 'Choose from below options to filter your posts', 'blog-designer-pro' );
								echo '<br/>';
								foreach ( $taxonomy_names as $taxonomy ) {
									if ( isset( $taxonomy ) ) {
										if ( isset( $bdp_settings[ 'filter_' . $taxonomy ] ) && 1 == $bdp_settings[ 'filter_' . $taxonomy ] ) {
											$terms_list  = get_terms( $taxonomy );
											$select_name = 'filter_' . $taxonomy;
											?>
											<select name="<?php echo esc_attr( $select_name ); ?>[]" id="<?php echo esc_attr( $select_name ); ?>" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> <?php echo esc_attr( $taxonomy ); ?>" multiple="multiple">
												<?php
												foreach ( $terms_list as $term_list ) {
													?>
													<option value="<?php echo esc_attr( $term_list->name ); ?>"><?php echo esc_html( $term_list->name ); ?></option>
													<?php
												}
												?>
											</select>
											<?php
										}
									}
								}
							}
							if ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) {
								while ( have_posts() ) :
									the_post();
									$dates[ get_the_time( 'Y-m' ) ] = get_the_time( 'F Y' );
								endwhile;
								?>
								<select name="filter_date[]" id="filter_date" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> date" multiple="multiple">
									<?php
									krsort( $dates );
									foreach ( $dates as $key => $value ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php
									}
									?>
								</select>
								<?php
							}
							echo '</div>';
							?>
							<input type="hidden" name="blog_template" id="blog_template" value="<?php echo esc_attr( $bdp_theme ); ?>" />
							<input type="hidden" name="url" id="url" value="<?php echo esc_attr( get_pagenum_link() ); ?>" />
							<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="<?php echo esc_attr( $layout_id ); ?>" />
							<input type="hidden" name="blog_itemMargin" id="blog_itemMargin" value="
						<?php
						if ( isset( $bdp_settings['template_post_margin'] ) ) {
							echo esc_attr( $bdp_settings['template_post_margin'] );
						}
						?>
						" />
							<input type="hidden" name="blog_itemWidth" id="blog_itemWidth" value="
						<?php
						if ( isset( $bdp_settings['item_width'] ) ) {
							echo esc_attr( $bdp_settings['item_width'] );
						}
						?>
						" />
							<input type="hidden" name="blog_itemHeight" id="blog_itemHeight" value="
						<?php
						if ( isset( $bdp_settings['item_height'] ) ) {
							echo esc_attr( $bdp_settings['item_height'] );
						}
						?>
						" />
							<input type="hidden" name="blog_easing" id="blog_easing" value="
						<?php
						if ( isset( $bdp_settings['template_easing'] ) ) {
							echo esc_attr( $bdp_settings['template_easing'] );
						}
						?>
						" />
							<input type="hidden" name="blog_startFrom" id="blog_startFrom" value="
						<?php
						if ( isset( $bdp_settings['timeline_start_from'] ) ) {
							echo esc_attr( $bdp_settings['timeline_start_from'] );
						}
						?>
						" />
							<input type="hidden" name="blog_hideLogbook" id="blog_hideLogbook" value="
						<?php
						if ( isset( $bdp_settings['display_timeline_bar'] ) ) {
							echo esc_attr( $bdp_settings['display_timeline_bar'] );
						}
						?>
						" />
							<input type="hidden" name="blog_autoplay" id="blog_autoplay" value="
						<?php
						if ( isset( $bdp_settings['enable_autoslide'] ) ) {
							echo esc_html( $bdp_settings['enable_autoslide'] );
						}
						?>
						" />
							<input type="hidden" name="blog_scrollSpeed" id="blog_scrollSpeed" value="
						<?php
						if ( isset( $bdp_settings['scroll_speed'] ) ) {
							echo esc_html( $bdp_settings['scroll_speed'] );
						}
						?>
						" />
							<input type="hidden" name="blog_page_number" id="blog_page_number" value="<?php echo esc_attr( $paged ); ?>" />
						</form>
						<?php
					}
				}
			}
		}
		wp_reset_query();
		$wp_query         = null;
		$wp_query         = $temp_query;
		$template_wrapper = '';
		if ( 'cool_horizontal' !== $bdp_theme || 'overlay_horizontal' !== $bdp_theme || 'crayon_slider' !== $bdp_theme || 'sallet_slider' !== $bdp_theme || 'colorful_sliding' !== $bdp_theme || 'sunshiny_slider' !== $bdp_theme || 'timeline' !== $bdp_theme || 'steps' !== $bdp_theme || 'story' !== $bdp_theme || 'easy_timeline' !== $bdp_theme || 'tabbed' !== $bdp_theme || '' !== $bdp_theme ) {
			if ( 0 != $display_filter ) {
				$layout_filter_id     = 'bdp_filter_class layout_filter_id_' . $layout_id;
				$template_wrapper    .= '<div class="' . $layout_filter_id . '">';
				$template_wrapper    .= '<div class="bdp_filter_layout">';
				$template_wrapper    .= '<ul id="bdp_filter_post_ul" class="bdp_filter_post_ul">';
				$template_wrapper    .= ' <li id="bdp_post_menu_Showall" data-filter="*" class="show_all"><a class="bdp_post_selected" href="javascript:void(0)">' . esc_html__( 'Show All', 'blog-designer-pro' ) . '</a></li>';
				$display_filter_by    = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
				$bdp_filter_post      = ( isset( $bdp_settings['bdp_filter_post'] ) && ! empty( $bdp_settings['bdp_filter_post'] ) ) ? $bdp_settings['bdp_filter_post'] : '';
				$custom_posttype      = ( isset( $bdp_settings['custom_post_type'] ) && ! empty( $bdp_settings['custom_post_type'] ) ) ? $bdp_settings['custom_post_type'] : '';
				$filter_template      = '';
				$display_filter_count = isset( $bdp_settings['display_filter_count'] ) ? $bdp_settings['display_filter_count'] : '0';
				if ( '1' == $display_filter_count ) {
					$filter_template = ( isset( $bdp_settings['filter_template'] ) && ! empty( $bdp_settings['filter_template'] ) ) ? $bdp_settings['filter_template'] : 'template-1';
				}
				if ( 'post' === $bdp_settings['custom_post_type'] && 'category' == $display_filter_by ) {
					$template_filter_by = ( isset( $bdp_settings['template_category'] ) && ! empty( $bdp_settings['template_category'] ) ) ? $bdp_settings['template_category'] : array();
				} elseif ( 'post' === $bdp_settings['custom_post_type'] && 'post_tag' === $display_filter_by && isset( $bdp_settings['exclude_tag_list'] ) && 0 == $bdp_settings['exclude_tag_list'] ) {
					$template_filter_by = ( isset( $bdp_settings['template_tags'] ) && ! empty( $bdp_settings['template_tags'] ) ) ? $bdp_settings['template_tags'] : array();
				}
				if ( isset( $bdp_settings['custom_post_type'] ) && 'post' !== $bdp_settings['custom_post_type'] ) {
					$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
					if ( ! empty( $taxonomy_names ) ) {
						foreach ( $taxonomy_names as $taxonomy_name ) {
							if ( isset( $bdp_settings[ $taxonomy_name->name . '_terms' ] ) && isset( $bdp_settings[ 'exclude_"' . $taxonomy_name->name . '"_list' ] ) && 0 == $bdp_settings[ 'exclude_"' . $taxonomy_name->name . '"_list' ] ) {
								if ( ! empty( $bdp_settings[ $taxonomy_name->name . '_terms' ] ) ) {
									$template_filter_by = $bdp_settings[ $taxonomy_name->name . '_terms' ];
								}
							}
						}
					}
				}
				if ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) {
					// if ( isset( $bdp_settings['pagination_type'] ) && 'paged' == $bdp_settings['pagination_type'] ) {.
						$posts['posts_per_page'] = -1;
					// }.
					$the_query = new WP_Query( $posts );
					$wp_query  = $the_query;
					if ( $the_query->have_posts() ) {
						$cat_filter_array = array();
						$post_terms       = array();
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$terms = wp_get_post_terms(
								get_the_ID(),
								$bdp_settings['display_filter_by'],
								array(
									'parent'     => 0,
									'hide_empty' => 0,
								)
							);
							foreach ( $terms as $term ) {
								if ( isset( $cat_filter_array[ $term->name ] ) ) {
									$cat_filter_array[ $term->name ] = $cat_filter_array[ $term->name ] + 1;
								} else {
									$cat_filter_array[ $term->name ] = 1;
								}
								if ( isset( $bdp_settings['custom_post_type'] ) && 'post' !== $bdp_settings['custom_post_type'] ) {
									if ( ! empty( $template_filter_by ) && in_array( $term->name, $template_filter_by ) ) {
										$post_terms[ $term->slug ] = $term->name;
									}
									if ( empty( $template_filter_by ) ) {
										$post_terms[ $term->slug ] = $term->name;
									}
								} else {
									if ( ! empty( $template_filter_by ) && in_array( $term->term_id, $template_filter_by ) ) {
										$post_terms[ $term->slug ] = $term->name . ',' . $term->term_id;
									}
									if ( empty( $template_filter_by ) ) {
										$post_terms[ $term->slug ] = $term->name . ',' . $term->term_id;
									}
								}
							}
						}
						if ( ! empty( $post_terms ) ) {
							ksort( $post_terms );
							foreach ( $post_terms as $slug => $name ) {
								$name_ids          = explode( ',', $name );
								$filter_count      = $cat_filter_array[ $name_ids[0] ];
								$template_wrapper .= '<li data-filter=".' . $slug . '" class="' . $slug . '" data-id="' . $name_ids[1] . '" data-count="' . $filter_count . '">';
								$template_wrapper .= '<a href="javascript:void(0)">';
								if ( 'template-2' === $filter_template ) {
									$template_wrapper .= '<span>' . $filter_count . '</span>';
								}
								$template_wrapper .= $name_ids[0];
								if ( 'template-1' === $filter_template ) {
									$template_wrapper .= '(' . $filter_count . ')';
								}
								$template_wrapper .= '</a>';
								$template_wrapper .= '</li>';
							}
						}
					}
					wp_reset_query();
				}
				$template_wrapper .= '</ul>';
			}
		}
		if ( isset( $bdp_settings['bdp_display_sort_by'] ) && 1 == $bdp_settings['bdp_display_sort_by'] ) {
			$bdp_display_front_sortby = array();
			if ( isset( $bdp_settings['bdp_display_front_sortby'] ) && ! empty( $bdp_settings['bdp_display_front_sortby'] ) ) {
				$bdp_display_front_sortby = $bdp_settings['bdp_display_front_sortby'];
			}
			$template_sort_by_wrapper  = '';
			$template_sort_by_wrapper .= '<div class="bdp_sortby_wrap">';
			$template_sort_by_wrapper .= '<div class="bdp_sort_by_wrapper">';
			$template_sort_by_wrapper .= '<select name="bdp_sort_by" id="bdp_sort_by">';
			$template_sort_by_wrapper .= '<option value="">' . esc_html__( 'Default Sort', 'blog-designer-pro' ) . '</option>';
			$getsortby                 = '';
			if ( ! empty( $bdp_display_front_sortby ) ) {
				if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
					$getsortby = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
				}
				foreach ( $bdp_display_front_sortby as $sortby ) {
					$if_selected = '';
					if ( $sortby == $getsortby ) {
						$if_selected = "selected='selected'";
					}
					if ( 'rand' === $sortby ) {
						$sortbylabel = 'Random';
					} elseif ( 'ID' === $sortby ) {
						$sortbylabel = 'Post ID';
					} elseif ( 'author' === $sortby ) {
						$sortbylabel = 'Author';
					} elseif ( 'title' === $sortby ) {
						$sortbylabel = 'Post Title';
					} elseif ( 'name' === $sortby ) {
						$sortbylabel = 'Post Slug';
					} elseif ( 'date' === $sortby ) {
						$sortbylabel = 'Publish Date';
					} elseif ( 'modified' === $sortby ) {
						$sortbylabel = 'Modified Date';
					} elseif ( 'meta_value_num' === $sortby ) {
						$sortbylabel = 'Post Likes';
					}
					$template_sort_by_wrapper .= '<option value="' . $sortby . '" ' . $if_selected . '>' . $sortbylabel . '</option>';
				}
			}
			$template_sort_by_wrapper .= '</select>';
			$template_sort_by_wrapper .= '</div>';
			$template_sort_by_wrapper .= '</div>';
		}
		// if(isset($template_sort_by_wrapper)){.
		// if ( 'wise_block' != $bdp_theme ){.
		// $template_wrapper .= $template_sort_by_wrapper;.
		// }.
		// }.
		$url = '';
		if ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ) {
			$url = 'https://';
		} else {
			$url = 'http://';
		}
		$url              .= isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$url              .= isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$urlcheck          = $url;
		$enable_print_page = isset( $bdp_settings['enable_print_page'] ) ? $bdp_settings['enable_print_page'] : 1;
		if ( 1 == $enable_print_page ) {
			$txt_print_page = isset( $bdp_settings['txt_print_page'] ) ? $bdp_settings['txt_print_page'] : '';
			if ( isset( $bdp_settings['txt_print_page'] ) && '' != $bdp_settings['txt_print_page'] ) {
				$template_wrapper .= '<div  style="text-align:right; padding:30px;" class="printbtn ' . $bdp_theme . '">';

				$template_wrapper .= '<a id="btn_print" class="print_click" href="' . $urlcheck . ' "  >';

				$template_wrapper .= '<span class="pdftitle"> ' . $txt_print_page . ' </span>';
				$template_wrapper .= '</a>';
				$template_wrapper .= '</div>';
			}
		}

		if ( 'blog_carousel' == $bdp_theme || 'sallet_slider' == $bdp_theme || 'colorful_sliding' == $bdp_theme || 'sunshiny_slider' == $bdp_theme ) {
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('#flexslider_script').remove();
						$("div").removeClass("flex-viewport");
						$(".flex-viewport").removeAttr("style");
						$("ul").removeAttr("style");
						$("li").removeAttr("style");
						$('.sunshiny_slider').css("margin-top", "30px");
						$('.sunshiny_slider').css("width", "203.333px");
						$(".post_hentry_1").removeAttr("style");
						$('.print_click').hide();
						window.print();
					});


				});
			</script>
		<?php } elseif ( 'brit_co' == $bdp_theme ) { ?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$("div").removeClass("content_wrapper");
						$(".bdp_blog_wraper").removeAttr("style");
						$(".content_bottom_wrapper").css("display", "block");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
			<?php
		} elseif ( 'cool_horizontal' == $bdp_theme || 'overlay_horizontal' == $bdp_theme ) {
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('.logbook_script').remove();
						$('.lb-items').removeAttr("style");
						$('.lb-items').css("display", "block");
						$('.horizontal').css("margin-top", "30px");
						$('.overlay_horizontal').css("margin-top", "30px");
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
			<?php
		} elseif ( 'crayon_slider' == $bdp_theme ) {
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('#flexslider_script').remove();
						$("div").removeClass("flex-viewport");
						$(".flex-viewport").removeAttr("style");
						$("ul").removeAttr("style");
						$("li").removeAttr("style");
						$('crayon_slider').css("margin-top", "30px");
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
			<?php
		} elseif ( 'explore' == $bdp_theme ) {
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('.post_hentry').each(function() {
							$('.grid-overlay').css('visibility', 'visible');
							$('.grid-overlay').css('transform', 'unset');
							$('.grid-overlay').css('-webkit-transform', 'unset');
							//$('.blog_header').css('position','absolute');
							$('.blog_header').css('transition','unset');
							$('.blog_header').css('top','15%');
						});

						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
		<?php } elseif ( 'hoverbic' == $bdp_theme ) { ?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('.post_hentry').each(function() {
							$('.blog_header').css('backface-visibility', 'visible');
							$('.blog_header').css('opacity', '1');
							$('.blog_header').css('transform', 'rotateY(0deg)');
							$('.blog_template.hoverbic').css('height', '390px');
						});
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
		<?php } elseif ( 'roctangle' == $bdp_theme ) { ?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {

						$(".roctangle-post-wrapper").removeAttr("style");
						$('.post-image').css('transform', 'matrix(1, -0.14, 0, 1, 0, 20);');
						$('.roctangle img').css('height', '300px');
						$('.roctangle img').css('width', '300px');
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
		<?php } elseif ( 'threed_carousel' == $bdp_theme ) { ?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('#carousel_script').remove();
						$(".bdp-3d-container").removeAttr("style");
						$("ul").removeAttr("style");
						$("li").removeAttr("style");
						$('.threed_carousel').css("margin-top", "30px");

						$('.post_hentry_1').each(function() {
							//$('.blog_header').removeAttr("style");
							$('.blog_header').css('top', '50%');
							$('.blog_header').css('width', '100%');
							$('.blog_header').css('overflow', 'unset');
							$('.blog_header').css('opacity', '0.7');
						});

						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
		<?php } elseif ( 'flip_book_3d' == $bdp_theme ) { ?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('#bookblock-thumb-style').remove();
						$('#bookblock-script').remove();
						$(".bdp_flip_book_3d.bb-bookblock").removeAttr("style");
						$(".bdp_flip_book_3d").removeAttr("style");
						$(".bb-item").removeAttr("style");
						$('.blog_template.flip_book_3d').css("margin-top", "30px");

						$('.bb-item').each(function() {
							$(".flip_book_3d.bb-item").css("display", "block");
							$(".flip_book_3d.bb-item").css('position','unset');
							$('.blog_header').css('width', '100%');
							$('.blog_header').css('overflow', 'unset');
							$('.blog_header').css('opacity', '0.7');
							$('.blog_header').css('transform', 'unset');
							$('.post_hentry_1 img').css('vertical-align', 'unset');
						});
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$('.print_click').hide();
						window.print();
					});
				});
			</script>
			<?php
		} elseif ( 'leest' == $bdp_theme ) {
			$slider_speed              = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : 600;
			$slider_autoplay           = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : 1;
			$slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : 100;
			$template_slider_scroll    = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : 1;
			$display_slider_controls   = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 1;
			$display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : 1;

			$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
			$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
			$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;

			if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
				$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
				$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
				$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
				$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;

				$template_slider_effect = isset( $bdp_settings['template_slider_effect'] ) ? $bdp_settings['template_slider_effect'] : 1;

			} else {
				$slider_column          = 1;
				$slider_column_ipad     = 1;
				$slider_column_tablet   = 1;
				$slider_column_mobile   = 1;
				$template_slider_effect = 1;
			}
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$('#flexslider_script').remove();
						$("div").removeClass("flex-viewport");
						$(".flex-viewport").removeAttr("style");
						$("ul").removeAttr("style");
						$("li").removeAttr("style");
						$('.sunshiny_slider').css("margin-top", "30px");
						$('.sunshiny_slider').css("width", "203.333px");
						$(".post_hentry_1").removeAttr("style");
						$('.print_click').hide();
						window.print();
					});


				});
			</script>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$("img").removeAttr("style");
						$('.famous-grid').css("margin-top", "30px");
						$('.blog_header').removeClass('hover');
						$('.print_click').hide();
						window.print();
					});

					$(".slick-slider").slick({
						nextArrow: '<i class="fa fa-arrow-right"></i>',
						prevArrow: '<i class="fa fa-arrow-left"></i>',
						slidesToShow: <?php echo esc_attr( $slider_column ); ?>,
						infinite: false,
						slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
						<?php echo ( 1 == $template_slider_effect ) ? 'fade: true,' : 'fade: false,'; ?>
						<?php echo ( $slider_speed ) ? 'speed:' . esc_attr( $slider_speed ) . ',' : ''; ?>
						<?php echo ( 1 == $slider_autoplay ) ? 'autoplay: true,' : 'autoplay: false,'; ?>
						<?php echo ( 1 == $slider_autoplay ) ? 'autoplaySpeed:' . esc_attr( $slider_autoplay_intervals ) . ',' : ''; ?>									
						<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true,' : 'dots: false,'; ?>
						<?php echo ( 1 == $display_slider_controls ) ? 'arrows: true,' : 'arrows: false,'; ?>
						responsive: [
						{
							breakpoint: 980,
							settings: {
							slidesToShow: <?php echo esc_attr( $slider_column_ipad ); ?>,
							slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
							infinite: true,
							<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
							}
						},
						{
							breakpoint: 720,
							settings: {
							slidesToShow: <?php echo esc_attr( $slider_column_tablet ); ?>,
							slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
							infinite: true,
							<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
							}
						},
						{
							breakpoint: 480,
							settings: {
							slidesToShow: <?php echo esc_attr( $slider_column_mobile ); ?>,
							slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
							infinite: true,
							<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
							}
						}
						]
					});
					jQuery('.leest-wapper .slick-slider').each(function(){
						var mainheight = 0;
						var mainparent = jQuery(this);
						mainparent.find('.card-box-img').each(function(){
							var newheight = jQuery(this).innerHeight();
							if( newheight > mainheight){ mainheight = newheight; }
						});
						mainparent.find('.card-box-img').css('min-height', mainheight);
						var height = 0;
						mainparent.find('.card-box-text').each(function(){
							var newheight = jQuery(this).innerHeight();
							if( newheight > height){ height = newheight; }
						});
						mainparent.find('.card-box-text').css('min-height', height);
					});


				});
			</script>
			<?php
		} else {
			?>
			<script>
				$ = jQuery;
				jQuery(document).ready(function() {
					jQuery('.print_click').on("click", function(e) {
						$("img").removeClass(" lazyloaded");
						$("img").removeClass("lazyload");
						$("img").removeAttr("style");
						$('.famous-grid').css("margin-top", "30px");
						$('.blog_header').removeClass('hover');
						$('.print_click').hide();
						window.print();
					});


				});
			</script>
			<?php
		}

		$template_wrapper .= '<div id="content" class="bdp_wrapper bdp_post_list  ' . $bdp_theme . ' ' . $same_height_class . ' ' . $bdp_theme . '_cover layout_id_' . $layout_id . '">';
		if ( isset( $template_sort_by_wrapper ) ) {
			// if ( 'wise_block' == $bdp_theme ){.
				$template_wrapper .= $template_sort_by_wrapper;
			// }.
		}
		if ( ( ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme || 'crayon_slider' != $bdp_theme ) && ( 'no_pagination' === $bdp_settings['pagination_type'] ) ) && isset( $bdp_settings['enable_disbale_customread_more'] ) && 1 == $bdp_settings['enable_disbale_customread_more'] ) {
			if ( isset( $bdp_settings['display_customread_more'] ) && 0 == $bdp_settings['display_customread_more'] ) {
				if ( isset( $bdp_settings['beforeloop_Readmoretext'] ) && '' != $bdp_settings['beforeloop_Readmoretext'] ) {
					$custom_read_more_href = isset( $bdp_settings['beforeloop_Readmoretextlink'] ) && '' != $bdp_settings['beforeloop_Readmoretextlink'] ? $bdp_settings['beforeloop_Readmoretextlink'] : '#';
					$open_customlink       = isset( $bdp_settings['open_customlink'] ) ? $bdp_settings['open_customlink'] : '';
					$custom_link_target    = '';
					if ( 0 == $open_customlink ) {
						$custom_link_target = "target = '_blank'";
					}
					$template_wrapper .= '<div class="custom_read_more before_loop"><a href="' . esc_url( $custom_read_more_href ) . '" ' . $custom_link_target . ' >' . $bdp_settings['beforeloop_Readmoretext'] . '</a></div>';
				}
			}
		}
		if ( 'ticker' === $bdp_theme ) {
			?>
			<?php
			$ticker_effect            = isset( $bdp_settings['ticker_effect'] ) ? $bdp_settings['ticker_effect'] : 'fade';
			$ticker_autoplay_interval = isset( $bdp_settings['ticker_autoplay_interval'] ) ? $bdp_settings['ticker_autoplay_interval'] : '3000';
			$ticker_autoplay          = ( isset( $bdp_settings['ticker_autoplay'] ) && ( '1' == $bdp_settings['ticker_autoplay'] ) ) ? 'true' : 'false';
			$ticker_label_text        = isset( $bdp_settings['ticker_label_text'] ) ? $bdp_settings['ticker_label_text'] : esc_html_e( 'Latest Post', 'blog-designer-pro' );
			$template_wrapper        .= '<div class="blog-ticker-wrapper" id="blog-ticker-style-' . $ticker_effect . '" data-conf="{&quot;ticker_effect&quot;:&quot;' . $ticker_effect . '&quot;,&quot;autoplay&quot;:&quot;' . $ticker_autoplay . '&quot;,&quot;speed&quot;:' . $ticker_autoplay_interval . ',&quot;font_style&quot;:&quot;normal&quot;,&quot;scroll_speed&quot;:1}">';
			$template_wrapper        .= '<div class="ticker-title">
			<div class="ticker-style-title">' . $ticker_label_text . '</div>
			<span></span>
			</div>';
			$template_wrapper        .= '<div class="blog-ticker-controls">
			<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-prev"></span></div>
			<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-next"></span></div>
			</div>';
			$template_wrapper        .= '<div class="blog-tickers">
			<ul>';
		}
		if ( 'banner' === $bdp_theme ) {
			$template_wrapper .= '<div class="banner-container"><div class="banner-row">';
		}

		if ( '' != $main_container_class ) {
			$template_wrapper .= '<div class="' . $main_container_class . '">';
		}
		if ( 'tabbed' !== $bdp_theme ) {
			$template_wrapper .= $template;
		}
		if ( 'boxy-clean' == $bdp_theme ) {
			?>
			<script>
				jQuery(window).resize(function() {
					bdp_get_boxy_clean_height_<?php echo esc_attr( $layout_id ); ?>();
				});
				jQuery(window).on('load', function() {
					bdp_get_boxy_clean_height_<?php echo esc_attr( $layout_id ); ?>();
				});

				function bdp_get_boxy_clean_height_<?php echo esc_attr( $layout_id ); ?>() {
					if (jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper').hasClass('boxy-clean_cover')) {

						var divs = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .boxy-clean li.blog_wrap").not('.first_post');
						if (jQuery(window).width() > 980) {
							var column = 4;
							if (divs.hasClass('three_column')) {
								column = 3;
							} else if (divs.hasClass('two_column')) {
								column = 2;
							} else if (divs.hasClass('one_column')) {
								column = 1;
							}
						} else if (jQuery(window).width() <= 980 && jQuery(window).width() > 720) {
							var column = 4;
							if (divs.hasClass('three_column_ipad')) {
								column = 3;
							} else if (divs.hasClass('two_column_ipad')) {
								column = 2;
							} else if (divs.hasClass('one_column_ipad')) {
								column = 1;
							}
						} else if (jQuery(window).width() <= 720 && jQuery(window).width() > 480) {
							var column = 4;
							if (divs.hasClass('three_column_tablet')) {
								column = 3;
							} else if (divs.hasClass('two_column_tablet')) {
								column = 2;
							} else if (divs.hasClass('one_column_tablet')) {
								column = 1;
							}
						} else if (jQuery(window).width() <= 480) {
							var column = 4;
							if (divs.hasClass('one_column_mobile')) {
								column = 3;
							} else if (divs.hasClass('two_column_mobile')) {
								column = 2;
							} else if (divs.hasClass('three_column_mobile')) {
								column = 1;
							}
						}
						jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .boxy-clean li.blog_wrap").removeAttr('style');

						jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .boxy-clean li.blog_wrap').imagesLoaded(function() {

							for (var i = 0; i < divs.length; i += column) {
								// console.log(jQuery(".layout_id_<?php // echo esc_attr( $layout_id );. ?> .boxy-clean li.blog_wrap").not('.layout_id_<?php // echo esc_attr( $layout_id );. ?> .first_post').slice(i, i + column));
								var heights = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .boxy-clean li.blog_wrap").not('.layout_id_<?php echo esc_attr( $layout_id ); ?> .first_post').slice(i, i + column).map(function() {
									return jQuery(this).height();
								}).get();
								var maxHeight = Math.max.apply(null, heights);
								if (screen.width > 640) {
									jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .boxy-clean li.blog_wrap").not('.layout_id_<?php echo esc_attr( $layout_id ); ?> .first_post').slice(i, i + column).css('height', maxHeight);
								}
							}
						});

					}
				}
			</script>
			<?php
		}
		if ( 'accordion' === $bdp_theme ) {
			?>
			<script>
				jQuery(document).ready(function() {
					jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .blog_template.accordion.accordion_wrapper').accordion({
						collapsible: true,
						active: 0,
						heightStyle: "content",
						classes: {
							"ui-accordion": "highlight"
						},
						header: "h3.accodine-title-wrap",
						icons: {
							"header": jQuery(this).find(".layout_id_<?php echo esc_attr( $layout_id ); ?> .accordion-icon-header").attr('data-accordion-header'),
							"activeHeader": jQuery(this).find(".layout_id_<?php echo esc_attr( $layout_id ); ?> .accordion-icon-header").attr('data-accordion-active-header')
						}
					});
				});
			</script>
			<?php
		}
		if ( 'tabbed' === $bdp_theme ) {
			wp_enqueue_script( 'jquery' );
			?>
			<script>
				jQuery(document).ready(function() {
					var $tabs = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs").tabs({
						activate: function(event, ui) {
							var active = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').tabs('option', 'active');
							var active_content = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs ul>li a").eq(active).attr("href");
							var leftContent = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').find(active_content + " .left-side.bdp-tabbed-all-post-content").outerHeight();
							var rightContent = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').find(active_content + " .right-side.bdp-tabbed-all-post-content").outerHeight();
							if (rightContent > leftContent) {
								jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', 'scroll');
								jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('max-height', leftContent);
							} else {
								jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', '')
							}
						}
					});
					jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .ui-tabs-panel").each(function(i) {
						var totalSize = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .ui-tabs-panel").size();
						if (i != totalSize) {
							next = i + 1;
							jQuery(this).append("<a href='#' class='next-tab mover' rel='" + next + "'><i class='fa fa-angle-right' aria-hidden='true'></i></a>");
						}
						if (i != totalSize) {
							prev = i - 1;
							jQuery(this).append("<a href='#' class='prev-tab mover' rel='" + prev + "'><i class='fa fa-angle-left' aria-hidden='true'></i></a>");
						}
					});
					jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .next-tab, .layout_id_<?php echo esc_attr( $layout_id ); ?> .prev-tab').on('click', function() {
						$tabs.tabs('option', 'active', jQuery(this).attr("rel"));
						return false
					});
				});
			</script>
			<?php

			$template_wrapper .= '<div id="tabs">';
			if ( ! empty( $tabbed_slug ) ) {
				$template_wrapper .= '<ul class="tabs">';
				foreach ( $tabbed_slug as $key => $val ) {
					$template_wrapper .= '<li><a href="#' . $val . '">' . $val . '</a></li>';
				}
				$template_wrapper .= '</ul>';
				$tabbi             = 1;
				$post_type         = 'post';
				$bdp_tabbed_layout = 'left_side';
				if ( isset( $bdp_settings['bdp_tabbed_layout'] ) ) {
					$bdp_tabbed_layout = $bdp_settings['bdp_tabbed_layout'];
				}
				if ( isset( $bdp_settings['custom_post_type'] ) ) {
					$post_type = $bdp_settings['custom_post_type'];
				}
				foreach ( $tabbed_slug as $key => $val ) {
					$tabbed_posts              = Bdp_Posts::get_wp_query( $bdp_settings );
					$tabbed_posts['tax_query'] = array(
						array(
							'taxonomy' => $display_tabbed_filter_by,
							'field'    => 'slug',
							'terms'    => $val,
						),
					);
					$tabbed                    = new WP_Query( $tabbed_posts );
					$post_count                = $tabbed->post_count;
					if ( $tabbed->have_posts() ) {
						$ti                = 1;
						$classes           = '';
						$tabbed_post_style = 0;
						$alter_class       = '';
						$prev_year         = '';
						$paged             = 0;
						$count_sticky      = '';
						$alter_val         = '';
						$template_wrapper .= '<div id="' . $val . '" class="' . $classes . '">';
						while ( $tabbed->have_posts() ) :
							$tabbed->the_post();
							if ( 1 == $ti ) {
								$template_wrapper .= '<div class="left-side bdp-tabbed-all-post-content ' . $bdp_tabbed_layout . '">';
							}

							if ( $ti > 1 ) {
								$tabbed_post_style = 1;
							}
							if ( 2 == $ti ) {
								$template_wrapper .= '<div class="right-side bdp-tabbed-all-post-content ' . $bdp_tabbed_layout . '">';
							}
							$template_wrapper .= self::get_blog_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style );
							if ( 1 == $ti ) {
								$template_wrapper .= '</div>';
							}
							++$ti;
						endwhile;
						if ( $post_count > 1 ) {
							$template_wrapper .= '</div>';
						}
						$template_wrapper .= apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $tabbi, $bdp_theme, $paged );
						$template_wrapper .= '</div>';
					}
					wp_reset_query();
					++$tabbi;
				}
			}
			$template_wrapper .= '</div>';
		}
		if ( 'banner' === $bdp_theme ) {
			?>
		<script>
			jQuery(document).ready(function() {
				jQuery('.banner-img-wrapper').each(function(){
					var height = jQuery(this).outerHeight();
					var style = "max-height:calc("+height+"px -  40px);overflow: hidden;padding: 0px 11px 0px 11px;max-width: 90%;";
					jQuery(this).parent().find('.category-link').attr('style',style);
				});
			});
		</script>
			<?php
		}
		if ( 'ticker' === $bdp_theme ) {
			$template_wrapper .= '</ul></div></div>';
		}
		if ( 'banner' === $bdp_theme ) {
			$template_wrapper .= '</div></div>';
		}
		if ( '' != $main_container_class ) {
			$template_wrapper .= '</div>';
		}
		if ( ( 'cool_horizontal' !== $bdp_theme && 'overlay_horizontal' !== $bdp_theme && 'crayon_slider' !== $bdp_theme && 'sallet_slider' !== $bdp_theme && 'colorful_sliding' !== $bdp_theme && 'blog_carousel' !== $bdp_theme && 'ticker' !== $bdp_theme && 'threed_carousel' !== $bdp_theme && 'flip_book_3d' !== $bdp_theme && 'leest' !== $bdp_theme ) ) {
			if ( 'no_pagination' === $bdp_settings['pagination_type'] && isset( $bdp_settings['enable_disbale_customread_more'] ) && 1 == $bdp_settings['enable_disbale_customread_more'] ) {
				if ( isset( $bdp_settings['display_customread_more'] ) && 1 == $bdp_settings['display_customread_more'] ) {
					if ( isset( $bdp_settings['beforeloop_Readmoretext'] ) && '' != $bdp_settings['beforeloop_Readmoretext'] ) {
						$custom_read_more_href = isset( $bdp_settings['beforeloop_Readmoretextlink'] ) && '' != $bdp_settings['beforeloop_Readmoretextlink'] ? $bdp_settings['beforeloop_Readmoretextlink'] : '#';
						$open_customlink       = isset( $bdp_settings['open_customlink'] ) ? $bdp_settings['open_customlink'] : '';
						$custom_link_target    = '';
						if ( 0 == $open_customlink ) {
							$custom_link_target = "target = '_blank'";
						}
						$template_wrapper .= '<div class="custom_read_more after_loop"><a href="' . esc_url( $custom_read_more_href ) . '" ' . $custom_link_target . '>' . $bdp_settings['beforeloop_Readmoretext'] . '</a></div>';
					}
				}
			}
		}
		$template_wrapper .= '</div>';
		if ( 'leest' !== $bdp_theme || 'cool_horizontal' !== $bdp_theme || 'overlay_horizontal' !== $bdp_theme || 'crayon_slider' !== $bdp_theme || 'sallet_slider' !== $bdp_theme || 'colorful_sliding' !== $bdp_theme || 'sunshiny_slider' !== $bdp_theme || 'timeline' !== $bdp_theme || 'steps' !== $bdp_theme || 'story' !== $bdp_theme || 'easy_timeline' !== $bdp_theme || 'blog_carousel' !== $bdp_theme || 'threed_carousel' !== $bdp_theme || 'flip_book_3d' !== $bdp_theme || 'ticker' !== $bdp_theme ) {
			if ( 0 != $display_filter ) {
				$template_wrapper .= '</div>';
				$template_wrapper .= '</div>';
			}
		}
		return $template_wrapper;
	}

	/**
	 * Get all archive list
	 *
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	public static function get_archive_list() {
		global $wpdb;
		$archive_array = array();
		$archives      = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives ORDER BY id DESC' );
		if ( $archives ) {
			foreach ( $archives as $archive ) {
				$archive_array[ $archive->id ] = $archive->archive_template;
			}
		}
		return $archive_array;
	}
	/**
	 * Get all Single list (This function not in use)
	 *
	 * @global object $wpdb
	 * @return Array List of single tempalte list
	 */
	public static function get_single_list() {
		global $wpdb;
		$single_array = array();
		$singles      = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_single_layouts ORDER BY id DESC' );
		if ( $singles ) {
			foreach ( $singles as $single ) {
				$single_array[] = $single->single_template;
			}
		}
		return $single_array;
	}
	/**
	 * Get date archive template settings
	 *
	 * @global object $wpdb
	 * @return array Date Template settings
	 */
	public static function get_date_template_settings() {
		global $wpdb;
		$date_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "date_template"' );
		return $date_settings;
	}
	/**
	 * Get Search template settings
	 *
	 * @global object $wpdb
	 * @return array Search Template settings
	 */
	public static function get_search_template_settings() {
		global $wpdb;
		$search_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "search_template"' );
		return $search_settings;
	}
	/**
	 * Get single post template settings
	 *
	 * @global object $wpdb
	 * @return array All Post Single Template settings
	 */
	public static function get_all_single_template_settings() {
		global $wpdb;
		$all_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "all"' );
		return $all_settings;
	}
	/**
	 * Get single Product template settings
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return array All Product Single Template settings
	 */
	public static function get_all_single_product_template_settings() {
		global $wpdb;
		$all_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "all"' );
		return $all_settings;
	}
	/**
	 * Get single download template settings
	 *
	 * @since 2.7
	 * @global object $wpdb
	 * @return array All download Single Template settings
	 */
	public static function get_all_single_download_template_settings() {
		global $wpdb;
		$all_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "all"' );
		return $all_settings;
	}
	/**
	 * Get single post template settings
	 *
	 * @global object $wpdb
	 * @param int $cat_ids cat ids.
	 * @param int $tag_ids tag.
	 * @return array Get bdp settings
	 */
	public static function get_single_template_settings( $cat_ids, $tag_ids ) {
		global $wpdb;
		$single_data              = '';
		$all_single_settings      = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "all"' );
		$single_post_template     = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE find_in_set( %d, single_post_id) <> 0", get_the_ID() ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'category' AND find_in_set( %d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					$single_category_template = true;
					break;
				}
			}
			$category_template_blank = '';
			if ( $single_category_template ) {
				$category_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "category" AND sub_categories = "" AND single_post_id = ""' );
			}
		}
		if ( $tag_ids ) {
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id->term_id ) ) {
					$tag_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'tag' AND find_in_set( %d, sub_categories) <> 0", $tag_id->term_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					$single_tag_template = true;
					break;
				}
			}
			$tag_template_blank = '';
			if ( $single_tag_template ) {
				$tag_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "tag" AND sub_categories = "" AND single_post_id = ""' );
			}
		}
		if ( $single_post_template ) {
			if ( isset( $single_post_template->settings ) ) {
				$single_data = $single_post_template->settings;
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$single_data = isset( $category_template_blank->settings ) ? $category_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'category' AND find_in_set( %d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					if ( isset( $category_template->settings ) ) {
						$single_data_cat       = $category_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_cat );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $category_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $tag_ids && $single_tag_template ) {
			if ( $tag_template_blank ) {
				$single_data = isset( $tag_template_blank->settings ) ? $tag_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				$tag_template = '';
				if ( is_numeric( $tag_id->term_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'tag' AND find_in_set( %d, sub_categories) <> 0", $tag_id->term_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					if ( isset( $tag_template->settings ) ) {
						$single_data_settings  = $tag_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $tag_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $all_single_settings ) {
			if ( isset( $all_single_settings->settings ) ) {
				$single_data = $all_single_settings->settings;
			}
		}
		return $single_data;
	}
	/**
	 * Get single Product template settings
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @param int $cat_ids cat ids.
	 * @param int $tag_ids tag.
	 * @return array Get bdp settings
	 */
	public static function get_single_prodcut_template_settings( $cat_ids, $tag_ids ) {
		global $wpdb;
		$single_data              = '';
		$all_single_settings      = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "all"' );
		$single_post_template     = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE find_in_set( %d,single_product_id) <> 0", get_the_ID() ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'category' AND find_in_set(%d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					$single_category_template = true;
					break;
				}
			}
			$category_template_blank = '';
			if ( $single_category_template ) {
				$category_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "category" AND sub_categories = "" AND single_product_id = ""' );
			}
		}
		if ( $tag_ids ) {
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'tag' AND find_in_set(%d, sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					$single_tag_template = true;
					break;
				}
			}
			$tag_template_blank = '';
			if ( $single_tag_template ) {
				$tag_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "tag" AND sub_categories = "" AND single_product_id = ""' );
			}
		}
		if ( $single_post_template ) {
			if ( isset( $single_post_template->settings ) ) {
				$single_data = $single_post_template->settings;
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$single_data = isset( $category_template_blank->settings ) ? $category_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'category' AND find_in_set(%d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					if ( isset( $category_template->settings ) ) {
						$single_data_cat       = $category_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_cat );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $category_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $tag_ids && $single_tag_template ) {
			if ( $tag_template_blank ) {
				$single_data = isset( $tag_template_blank->settings ) ? $tag_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				$tag_template = '';
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'tag' AND find_in_set(%d, sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					if ( isset( $tag_template->settings ) ) {
						$single_data_settings  = $tag_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $tag_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $all_single_settings ) {
			if ( isset( $all_single_settings->settings ) ) {
				$single_data = $all_single_settings->settings;
			}
		}
		return $single_data;
	}
	/**
	 * Get single Download template settings
	 *
	 * @since 2.7
	 * @global object $wpdb
	 * @param int $cat_ids cat ids.
	 * @param int $tag_ids tag ids.
	 * @return array Get bdp settings
	 */
	public static function get_single_download_template_settings( $cat_ids, $tag_ids ) {
		global $wpdb;
		$single_data              = '';
		$all_single_settings      = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "all"' );
		$single_post_template     = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE find_in_set(%d,single_download_id) <> 0", get_the_ID() ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'category' AND find_in_set(%d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					$single_category_template = true;
					break;
				}
			}
			$category_template_blank = '';
			if ( $single_category_template ) {
				$category_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "category" AND sub_categories = "" AND single_download_id = ""' );
			}
		}
		if ( $tag_ids ) {
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'tag' AND find_in_set(%d, sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					$single_tag_template = true;
					break;
				}
			}
			$tag_template_blank = '';
			if ( $single_tag_template ) {
				$tag_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "tag" AND sub_categories = "" AND single_download_id = ""' );
			}
		}

		if ( $single_post_template ) {
			if ( isset( $single_post_template->settings ) ) {
				$single_data = $single_post_template->settings;
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$single_data = isset( $category_template_blank->settings ) ? $category_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'category' AND find_in_set( %d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					if ( isset( $category_template->settings ) ) {
						$single_data_cat       = $category_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_cat );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $category_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $tag_ids && $single_tag_template ) {
			if ( $tag_template_blank ) {
				$single_data = isset( $tag_template_blank->settings ) ? $tag_template_blank->settings : '';
			} else {
				$single_data = isset( $all_single_settings->settings ) ? $all_single_settings->settings : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				$tag_template = '';
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'tag' AND find_in_set(%d, sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					if ( isset( $tag_template->settings ) ) {
						$single_data_settings  = $tag_template->settings;
						$serialize_single_data = maybe_unserialize( $single_data_settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$single_data = $tag_template->settings;
							break;
						}
					}
				}
			}
		} elseif ( $all_single_settings ) {
			if ( isset( $all_single_settings->settings ) ) {
				$single_data = $all_single_settings->settings;
			}
		}
		return $single_data;
	}
	/**
	 * Display Front side Single Post Blog Designer Layout
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	public static function get_single_template_setting_front_end() {
		global $post, $wpdb;
		$post_id                  = $post->ID;
		$cat_ids                  = wp_get_post_categories( $post_id );
		$tag_ids                  = wp_get_post_tags( $post_id );
		$all_single_settings      = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "all"' );
		$single_template          = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE find_in_set(%d, single_post_id) <> 0", $post_id ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'category' AND find_in_set(%d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					$single_category_template = true;
					break;
				}
			}
			$category_template_blank = '';
			if ( $single_category_template ) {
				$category_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "category" AND sub_categories = "" AND single_post_id = ""' );
			}
		}
		if ( $tag_ids ) {
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id->term_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'tag' AND find_in_set(%d, sub_categories) <> 0", $tag_id->term_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					$single_tag_template = true;
					break;
				}
			}
			$tag_template_blank = '';
			if ( $single_tag_template ) {
				$tag_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_layouts WHERE single_template = "tag" AND sub_categories = "" AND single_post_id = ""' );
			}
		}
		if ( $single_template ) {
			if ( isset( $single_template->settings ) && is_serialized( $single_template->settings ) ) {
				$bdp_settings = maybe_unserialize( $single_template->settings );
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$bdp_settings = isset( $category_template_blank->settings ) ? maybe_unserialize( $category_template_blank->settings ) : '';
			} else {
				$bdp_settings = isset( $all_single_settings->settings ) ? maybe_unserialize( $all_single_settings->settings ) : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'category' AND find_in_set(%d, sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					if ( isset( $category_template->settings ) && is_serialized( $category_template->settings ) ) {
						$serialize_single_data = maybe_unserialize( $category_template->settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$bdp_settings = maybe_unserialize( $category_template->settings );
							break;
						}
					}
				}
			}
		} elseif ( $tag_ids && $single_tag_template ) {
			if ( $tag_template_blank ) {
				$bdp_settings = isset( $tag_template_blank->settings ) ? maybe_unserialize( $tag_template_blank->settings ) : '';
			} else {
				$bdp_settings = isset( $all_single_settings->settings ) ? maybe_unserialize( $all_single_settings->settings ) : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id->term_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = 'tag' AND find_in_set( %d, sub_categories) <> 0", $tag_id->term_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					if ( isset( $tag_template->settings ) && is_serialized( $tag_template->settings ) ) {
						$serialize_single_data = maybe_unserialize( $tag_template->settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$bdp_settings = maybe_unserialize( $tag_template->settings );
							break;
						}
					}
				}
			}
		} elseif ( $all_single_settings ) {
			if ( isset( $all_single_settings->settings ) && is_serialized( $all_single_settings->settings ) ) {
				$bdp_settings = maybe_unserialize( $all_single_settings->settings );
			}
		} else {
			$bdp_settings = array();
		}
		return $bdp_settings;
	}
	/**
	 * Display Front side Single Download Blog Designer Layout
	 *
	 * @since 2.7
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	public static function get_single_download_template_setting_front_end() {
		global $post, $wpdb;
		$post_id                  = $post->ID;
		$cat_ids                  = wp_get_post_terms( $post_id, 'download_category', array( 'fields' => 'ids' ) );
		$tag_ids                  = wp_get_post_terms( $post_id, 'download_tag', array( 'fields' => 'ids' ) );
		$all_single_settings      = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "all"' );
		$single_template          = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE find_in_set( %d,single_download_id) <> 0", $post_id ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'category' AND find_in_set(%d,sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					$single_category_template = true;
					break;
				}
			}
			$category_template_blank = '';
			if ( $single_category_template ) {
				$category_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "category" AND sub_categories = "" AND single_download_id = ""' );
			}
		}
		if ( $tag_ids ) {
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'tag' AND find_in_set(%d,sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					$single_tag_template = true;
					break;
				}
			}
			$tag_template_blank = '';
			if ( $single_tag_template ) {
				$tag_template_blank = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_ed_download WHERE single_download_template = "tag" AND sub_categories = "" AND single_download_id = ""' );
			}
		}
		if ( $single_template ) {
			if ( isset( $single_template->settings ) && is_serialized( $single_template->settings ) ) {
				$bdp_settings = maybe_unserialize( $single_template->settings );
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$bdp_settings = isset( $category_template_blank->settings ) ? maybe_unserialize( $category_template_blank->settings ) : '';
			} else {
				$bdp_settings = isset( $all_single_settings->settings ) ? maybe_unserialize( $all_single_settings->settings ) : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'category' AND find_in_set(%d,sub_categories) <> 0", $cat_id ) );
				}
				if ( isset( $category_template ) && $category_template ) {
					if ( isset( $category_template->settings ) && is_serialized( $category_template->settings ) ) {
						$serialize_single_data = maybe_unserialize( $category_template->settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$bdp_settings = maybe_unserialize( $category_template->settings );
							break;
						}
					}
				}
			}
		} elseif ( $tag_ids && $single_tag_template ) {
			if ( $tag_template_blank ) {
				$bdp_settings = isset( $tag_template_blank->settings ) ? maybe_unserialize( $tag_template_blank->settings ) : '';
			} else {
				$bdp_settings = isset( $all_single_settings->settings ) ? maybe_unserialize( $all_single_settings->settings ) : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = 'tag' AND find_in_set(%d,sub_categories) <> 0", $tag_id ) );
				}
				if ( isset( $tag_template ) && $tag_template ) {
					if ( isset( $tag_template->settings ) && is_serialized( $tag_template->settings ) ) {
						$serialize_single_data = maybe_unserialize( $tag_template->settings );
						$template_posts        = isset( $serialize_single_data['template_posts'] ) ? $serialize_single_data['template_posts'] : array();
						if ( empty( $template_posts ) ) {
							$bdp_settings = maybe_unserialize( $tag_template->settings );
							break;
						}
					}
				}
			}
		} elseif ( $all_single_settings ) {
			if ( isset( $all_single_settings->settings ) && is_serialized( $all_single_settings->settings ) ) {
				$bdp_settings = maybe_unserialize( $all_single_settings->settings );
			}
		} else {
			$bdp_settings = array();
		}
		return $bdp_settings;
	}
	/**
	 * Get tag template settings
	 *
	 * @param int   $tag_id tag id.
	 * @param array $archive_list archive.
	 * @return array Tag Template settings
	 */
	public static function get_tag_template_settings( $tag_id, $archive_list ) {
		$bdp_tag_data  = array();
		$bdp_settings  = array();
		$bdp_layout_id = '';
		if ( $archive_list ) {
			foreach ( $archive_list as $archive ) {
				if ( 'tag_template' === $archive ) {
					global $wpdb;
					$tag_template = '';
					if ( is_numeric( $tag_id ) ) {
						$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id, settings FROM {$wpdb->prefix}bdp_archives WHERE archive_template = 'tag_template' AND find_in_set( %d, sub_categories) <> 0", $tag_id ) );
					}
					if ( ! empty( $tag_template ) ) {
						$bdp_layout_id = $tag_template->id;
						$allsettings   = $tag_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$tag_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "tag_template" AND sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $tag_template ) ) {
							$bdp_layout_id = $tag_template->id;
							$allsettings   = $tag_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_tag_data['id']           = $bdp_layout_id;
		$bdp_tag_data['bdp_settings'] = $bdp_settings;
		return $bdp_tag_data;
	}

	/**
	 * Get product tag template settings
	 *
	 * @since 2.6
	 * @param int   $tag_id tag id.
	 * @param array $product_archive_list product arvhie.
	 * @return Array Tag Template settings
	 */
	public static function get_product_tag_template_settings( $tag_id, $product_archive_list ) {
		$bdp_category_data = array();
		$bdp_settings      = array();
		$bdp_layout_id     = '';
		if ( $product_archive_list ) {
			foreach ( $product_archive_list as $archive ) {
				if ( 'tag_template' === $archive ) {
					global $wpdb;
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id, settings FROM {$wpdb->prefix}bdp_product_archives WHERE product_archive_template = 'tag_template' AND find_in_set( %d, product_sub_categories) <> 0", $tag_id ) );
					if ( ! empty( $tag_template ) ) {
						$bdp_layout_id = $tag_template->id;
						$allsettings   = $tag_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$tag_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE  product_archive_template = "tag_template" AND  product_sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $tag_template ) ) {
							$bdp_layout_id = $tag_template->id;
							$allsettings   = $tag_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_category_data['id']           = $bdp_layout_id;
		$bdp_category_data['bdp_settings'] = $bdp_settings;
		return $bdp_category_data;
	}
	/**
	 * Get download tag template settings
	 *
	 * @since 2.7
	 * @param int   $tag_id tag id.
	 * @param array $product_archive_list product archive.
	 * @return Array Tag Template settings
	 */
	public static function get_download_tag_template_settings( $tag_id, $product_archive_list ) {
		$bdp_category_data = array();
		$bdp_settings      = array();
		$bdp_layout_id     = '';
		if ( $product_archive_list ) {
			foreach ( $product_archive_list as $archive ) {
				if ( 'tag_template' === $archive ) {
					global $wpdb;
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id, settings FROM {$wpdb->prefix}bdp_edd_archives WHERE download_archive_template = 'tag_template' AND find_in_set( %d, download_sub_categories) <> 0", $tag_id ) );
					if ( ! empty( $tag_template ) ) {
						$bdp_layout_id = $tag_template->id;
						$allsettings   = $tag_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$tag_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_edd_archives WHERE  download_archive_template = "tag_template" AND  download_sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $tag_template ) ) {
							$bdp_layout_id = $tag_template->id;
							$allsettings   = $tag_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_category_data['id']           = $bdp_layout_id;
		$bdp_category_data['bdp_settings'] = $bdp_settings;
		return $bdp_category_data;
	}

	/**
	 * Get category template settings
	 *
	 * @param int   $category_id category.
	 * @param array $archive_list archive.
	 * @return Array Category Template settings
	 */
	public static function get_category_template_settings( $category_id, $archive_list ) {
		$bdp_category_data = array();
		$bdp_settings      = array();
		$bdp_layout_id     = '';
		if ( $archive_list ) {
			foreach ( $archive_list as $archive ) {
				if ( 'category_template' === $archive ) {
					global $wpdb;
					$category_template = '';
					if ( is_numeric( $category_id ) ) {
						$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT id, settings FROM {$wpdb->prefix}bdp_archives WHERE archive_template = 'category_template' AND find_in_set( %d ,sub_categories) <> 0", $category_id ) );
					}
					if ( ! empty( $category_template ) ) {
						$bdp_layout_id = $category_template->id;
						$allsettings   = $category_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$category_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "category_template" AND sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $category_template ) ) {
							$bdp_layout_id = $category_template->id;
							$allsettings   = $category_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_category_data['id']           = $bdp_layout_id;
		$bdp_category_data['bdp_settings'] = $bdp_settings;
		return $bdp_category_data;
	}
	/**
	 * Get product category template settings
	 *
	 * @since 2.6
	 * @param int   $category_id category id.
	 * @param array $product_archive_list product list.
	 * @return Array Category Template settings
	 */
	public static function get_product_category_template_settings( $category_id, $product_archive_list ) {
		$bdp_category_data = array();
		$bdp_settings      = array();
		$bdp_layout_id     = '';
		if ( $product_archive_list ) {
			foreach ( $product_archive_list as $archive ) {
				if ( 'category_template' === $archive ) {
					global $wpdb;
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT id, settings FROM {$wpdb->prefix}bdp_product_archives WHERE product_archive_template = 'category_template' AND find_in_set( %d, product_sub_categories) <> 0", $category_id ) );
					if ( ! empty( $category_template ) ) {
						$bdp_layout_id = $category_template->id;
						$allsettings   = $category_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$category_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE  product_archive_template = "category_template" AND  product_sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $category_template ) ) {
							$bdp_layout_id = $category_template->id;
							$allsettings   = $category_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_category_data['id']           = $bdp_layout_id;
		$bdp_category_data['bdp_settings'] = $bdp_settings;
		return $bdp_category_data;
	}
	/**
	 * Check if current template is date archive or not (function not in use)
	 *
	 * @param array $item item.
	 * @return boolean
	 */
	public static function check_archive_date_template( $item ) {
		return 'date_template' === $item['archive_template'];
	}
	/**
	 * Check if current template is author archive or not (function not in use )
	 *
	 * @param array $item item.
	 * @return boolean
	 */
	public static function check_archive_author_template( $item ) {
		return 'author_template' === $item['archive_template'];
	}
	/**
	 * Check if current template is author archive or not (function not in use )
	 *
	 * @param array $item item.
	 * @return boolean
	 */
	public static function check_single_all_template( $item ) {
		return 'all' === $item['single_template'];
	}
	/**
	 * Get if current theme have template file
	 *
	 * @param string $template template.
	 * @return filepath
	 */
	public static function get_theme_template_file( $template ) {
		return get_stylesheet_directory() . '/' . apply_filters( 'bdp_template_directory', 'bdp_templates', $template ) . '/' . $template;
	}
	/**
	 * Move template action.
	 *
	 * @param string $template template.
	 * @return void
	 */
	public static function singlefile_move_template_action( $template ) {
		if ( ! empty( $template ) && 'single/single.php' === $template ) {
			$template   = 'single/single.php';
			$theme_file = self::get_theme_template_file( $template );
			if ( wp_mkdir_p( dirname( $theme_file ) ) && ! file_exists( $theme_file ) ) {
				// Locate template file.
				$template_name = BLOGDESIGNERPRO_DIR . 'bdp_templates/single/single.php';
				$template_file = apply_filters( 'bdp_locate_core_template', $template_name, $template );
				// Copy template file.
				copy( $template_file, $theme_file );
				/**
				 * Bdp_copy_single_template action hook.
				 *
				 * @param string $template The copied template type
				 */
				do_action( 'bdp_copy_single_template', $template );
				echo '<div class="updated"><p>' . esc_html__( 'Template file copied to theme.', 'blog-designer-pro' ) . '</p></div>';
			}
		}
	}
	/**
	 * Delete template action.
	 *
	 * @param string $template template.
	 * @return void
	 */
	public static function singlefile_delete_template_action( $template ) {
		if ( ! empty( $template ) && 'single/single.php' === $template ) {
			$theme_file = self::get_theme_template_file( $template );
			if ( file_exists( $theme_file ) ) {
				unlink( $theme_file );
				/**
				 * Bdp_delete_single_template action hook.
				 *
				 * @param string $template The deleted template type
				 * @param string $email The email object
				 */
				do_action( 'bdp_delete_single_template', $template );
				echo '<div class="updated"><p>' . esc_html__( 'Template file deleted from theme.', 'blog-designer-pro' ) . '</p></div>';
			}
		}
	}
	/**
	 * Save the single templates.
	 *
	 * @param string $template_code code.
	 * @param string $template_path path.
	 * @return void
	 */
	public static function singlefile_save_template( $template_code, $template_path ) {
		if ( current_user_can( 'edit_themes' ) && ! empty( $template_code ) && ! empty( $template_path ) ) {
			$saved = false;
			$file  = get_stylesheet_directory() . '/' . $template_path;
			$code  = stripslashes( $template_code );
			WP_Filesystem();
			global $wp_filesystem;
			if ( is_writeable( $file ) ) {
				$f = $wp_filesystem->get_contents( $file );
				if ( false != $f ) {
					$wp_filesystem->put_contents( $f, $code );
					$saved = true;
				}
			}
			if ( ! $saved ) {
				$redirect = add_query_arg( 'bdp_errors', rawurlencode( esc_html__( 'Could not write to template file.', 'blog-designer-pro' ) ) );
				wp_safe_redirect( $redirect );
				exit;
			}
		}
	}
	/**
	 * Add notice at admin side for transfer blog designer data to Blog Designer PRO
	 *
	 * @since 1.6
	 * @global object $pagenow;
	 */
	public static function create_layout_from_blog_designer_notice() {
		/* Check that the user hasn't already clicked to ignore the message */
		if ( isset( $_GET['page'] ) && current_user_can( 'manage_options' ) && ( 'layouts' === $_GET['page'] || 'add_shortcode' === $_GET['page'] ) ) {
			global $wpdb;
			$count_layout = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
			if ( $count_layout < 1 ) {
				echo '<div class="updated notice is-dismissible bdp-create-layout-using-blog-designer-div"><p>';
				?>
				<strong><?php esc_html_e( 'Create Blog Layout using Blog Designer (free plugin) Data', 'blog-designer-pro' ); ?></strong>&nbsp;&nbsp;&nbsp;
				<a class="bdp-create-layout-using-blog-designer button-primary" href="<?php echo esc_url( add_query_arg( 'create-layout-using-blog-designer', 'new', admin_url( 'admin.php?page=layouts' ) ) ); ?>"><?php esc_html_e( 'Create Layout', 'blog-designer-pro' ); ?></a>
				<button class="notice-dismiss bdp-create-layout-using-blog-designer-notice-dismiss" type="button">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'blog-designer-pro' ); ?></span>
				</button>
				<?php
				echo '</p></div>';
			}
		}
	}
	/**
	 * Create blog layout using Blog Designer Data
	 *
	 * @since 1.6
	 * @global type $wpdb
	 * @return void
	 */
	public function create_layout_using_blog_designer() {
		if ( isset( $_GET['create-layout-using-blog-designer'] ) && 'new' === $_GET['create-layout-using-blog-designer'] ) {
			global $wpdb;
			$count_layout          = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
			$blog_designer_setting = get_option( 'wp_blog_designer_settings' );
			$page_id               = isset( $blog_designer_setting['blog_page_display'] ) ? $blog_designer_setting['blog_page_display'] : '';
			/* Array for sample blog layout create */
			$sample_blog_settings = array(
				'template_name'                            => $blog_designer_setting['template_name'],
				'bdp_color_preset'                         => $blog_designer_setting['template_name'] . '_default',
				'unique_shortcode_name'                    => 'Blog Designer Light Layout',
				'bdp_timeline_layout'                      => '',
				'custom_post_type'                         => 'post',
				'blog_page_display'                        => $page_id,
				'blog_time_period'                         => 'all',
				'between_two_date_from'                    => '',
				'between_two_date_to'                      => '',
				'bdp_time_period_day'                      => '15',
				'posts_per_page'                           => isset( $blog_designer_setting['posts_per_page'] ) ? $blog_designer_setting['posts_per_page'] : '',
				'bdp_blog_order_by'                        => 'date',
				'bdp_blog_order'                           => 'ASC',
				'timeline_display_option'                  => '',
				'displaydate_backcolor'                    => '#414a54',
				'pagination_type'                          => 'paged',
				'pagination_text_color'                    => '#ffffff',
				'pagination_background_color'              => '#777777',
				'pagination_text_hover_color'              => '',
				'pagination_background_hover_color'        => '',
				'pagination_text_active_color'             => '',
				'pagination_active_background_color'       => '',
				'pagination_border_color'                  => '#b2b2b2',
				'pagination_active_border_color'           => '#007acc',
				'display_sticky'                           => isset( $blog_designer_setting['display_sticky'] ) ? $blog_designer_setting['display_sticky'] : '',
				'display_category'                         => isset( $blog_designer_setting['display_category'] ) ? $blog_designer_setting['display_category'] : '',
				'display_tag'                              => isset( $blog_designer_setting['display_tag'] ) ? $blog_designer_setting['display_tag'] : '',
				'display_author'                           => isset( $blog_designer_setting['display_author'] ) ? $blog_designer_setting['display_author'] : '',
				'display_story_year'                       => '1',
				'display_date'                             => '1',
				'display_comment_count'                    => '1',
				'display_postlike'                         => '0',
				'custom_css'                               => '',
				'display_timeline_bar'                     => '0',
				'timeline_start_from'                      => '28/01/2017',
				'template_easing'                          => 'easeOutSine',
				'item_width'                               => '400',
				'item_height'                              => '570',
				'template_post_margin'                     => '28',
				'enable_autoslide'                         => '0',
				'scroll_speed'                             => '1000',
				'unique_design_option'                     => 'first_post',
				'template_columns'                         => '2',
				'template_grid_height'                     => '300',
				'template_grid_skin'                       => 'default',
				'grid_col_space'                           => '10',
				'grid_hoverback_color'                     => '#000000',
				'template_color'                           => '#ffffff',
				'template_alternative_color'               => '#c34376',
				'story_startup_border_color'               => '#ffffff',
				'template_bgcolor'                         => isset( $blog_designer_setting['template_bgcolor'] ) ? $blog_designer_setting['template_bgcolor'] : '',
				'blog_background_image_style'              => '1',
				'template_bghovercolor'                    => '#eeeeee',
				'template_alternativebackground'           => '0',
				'template_alterbgcolor'                    => isset( $blog_designer_setting['template_alternativebackground'] ) ? $blog_designer_setting['template_alternativebackground'] : '',
				'story_startup_text'                       => 'STARTUP',
				'story_startup_background'                 => '#ade175',
				'story_startup_text_color'                 => '#333',
				'story_ending_text'                        => 'Ending',
				'story_ending_link'                        => '',
				'story_ending_background'                  => '#ade175',
				'story_ending_text_color'                  => '#333',
				'post_loop_alignment'                      => 'default',
				'template_ftcolor'                         => isset( $blog_designer_setting['template_ftcolor'] ) ? $blog_designer_setting['template_ftcolor'] : '',
				'template_fthovercolor'                    => '#666666',
				'deport_dashcolor'                         => '',
				'winter_category_color'                    => '',
				'image_corner_selection'                   => '0',
				'bdp_hide_hover_post'                      => '1',
				'bdp_post_title_link'                      => '1',
				'template_title_alignment'                 => 'left',
				'template_titlecolor'                      => isset( $blog_designer_setting['template_titlecolor'] ) ? $blog_designer_setting['template_titlecolor'] : '',
				'template_titlehovercolor'                 => '#666666',
				'template_titlebackcolor'                  => isset( $blog_designer_setting['template_titlebackcolor'] ) ? $blog_designer_setting['template_titlebackcolor'] : '',
				'template_titlefontface_font_type'         => '',
				'template_titlefontface'                   => '',
				'template_titlefontsize'                   => isset( $blog_designer_setting['template_titlefontsize'] ) ? $blog_designer_setting['template_titlefontsize'] : '',
				'template_title_font_weight'               => 'normal',
				'template_title_font_line_height'          => '1.2',
				'template_title_font_text_transform'       => 'none',
				'template_title_font_text_decoration'      => 'none',
				'template_title_font_letter_spacing'       => '0',
				'rss_use_excerpt'                          => isset( $blog_designer_setting['rss_use_excerpt'] ) ? $blog_designer_setting['rss_use_excerpt'] : '',
				'template_post_content_from'               => 'from_excerpt',
				'display_html_tags'                        => '1',
				'firstletter_fontsize'                     => '28',
				'firstletter_font_family_font_type'        => '',
				'firstletter_font_family'                  => '',
				'firstletter_contentcolor'                 => '#777777',
				'txtExcerptlength'                         => isset( $blog_designer_setting['txtExcerptlength'] ) ? $blog_designer_setting['txtExcerptlength'] : '',
				'content_font_family_font_type'            => '',
				'content_font_family'                      => '',
				'content_fontsize'                         => isset( $blog_designer_setting['content_fontsize'] ) ? $blog_designer_setting['content_fontsize'] : '',
				'content_font_weight'                      => 'normal',
				'content_font_line_height'                 => '1.5',
				'content_font_text_transform'              => 'none',
				'content_font_text_decoration'             => 'none',
				'content_font_letter_spacing'              => '0',
				'template_contentcolor'                    => isset( $blog_designer_setting['template_contentcolor'] ) ? $blog_designer_setting['template_contentcolor'] : '',
				'template_content_hovercolor'              => '#ed4b1f',
				'read_more_on'                             => '2',
				'read_more_button_hover_border_style'      => 'solid',
				'readmore_button_hover_border_radius'      => '0',
				'bdp_readmore_button_hover_borderleft'     => '0',
				'bdp_readmore_button_hover_borderleftcolor' => '',
				'bdp_readmore_button_hover_borderright'    => '0',
				'bdp_readmore_button_hover_borderrightcolor' => '',
				'bdp_readmore_button_hover_bordertop'      => '0',
				'bdp_readmore_button_hover_bordertopcolor' => '',
				'bdp_readmore_button_hover_borderbottom'   => '0',
				'bdp_readmore_button_hover_borderbottomcolor' => '',
				'readmore_button_border_radius'            => '0',
				'readmore_button_alignment'                => 'left',
				'readmore_button_paddingleft'              => '10',
				'readmore_button_paddingright'             => '10',
				'readmore_button_paddingtop'               => '3',
				'readmore_button_paddingbottom'            => '3',
				'readmore_button_marginleft'               => '0',
				'readmore_button_marginright'              => '0',
				'readmore_button_margintop'                => '0',
				'readmore_button_marginbottom'             => '0',
				'read_more_button_border_style'            => 'solid',
				'bdp_readmore_button_borderleft'           => '0',
				'bdp_readmore_button_borderleftcolor'      => '',
				'bdp_readmore_button_borderright'          => '0',
				'bdp_readmore_button_borderrightcolor'     => '',
				'bdp_readmore_button_bordertop'            => '0',
				'bdp_readmore_button_bordertopcolor'       => '',
				'bdp_readmore_button_borderbottom'         => '0',
				'bdp_readmore_button_borderbottomcolor'    => '',
				'txtReadmoretext'                          => isset( $blog_designer_setting['txtReadmoretext'] ) ? $blog_designer_setting['txtReadmoretext'] : '',
				'template_readmorecolor'                   => isset( $blog_designer_setting['template_readmorecolor'] ) ? $blog_designer_setting['template_readmorecolor'] : '',
				'template_readmorehovercolor'              => '#2376ad',
				'template_readmorebackcolor'               => isset( $blog_designer_setting['template_readmorebackcolor'] ) ? $blog_designer_setting['template_readmorebackcolor'] : '',
				'readmore_font_family_font_type'           => '',
				'readmore_font_family'                     => '',
				'readmore_fontsize'                        => '14',
				'readmore_font_weight'                     => 'normal',
				'readmore_font_line_height'                => '1.5',
				'readmore_font_text_transform'             => 'none',
				'readmore_font_text_decoration'            => 'none',
				'readmore_font_letter_spacing'             => '0',
				'display_feature_image'                    => '0',
				'easy_timeline_effect'                     => 'flip-effect',
				'thumbnail_skin'                           => '0',
				'bdp_post_image_link'                      => '1',
				'bdp_default_image_id'                     => '',
				'bdp_default_image_src'                    => '',
				'bdp_media_size'                           => 'full',
				'media_custom_width'                       => '800',
				'media_custom_height'                      => '320',
				'template_slider_columns'                  => '2',
				'template_slider_effect'                   => 'slide',
				'template_slider_scroll'                   => '1',
				'display_slider_navigation'                => '1',
				'navigation_style_hidden'                  => 'navigation3',
				'display_slider_controls'                  => '1',
				'arrow_style_hidden'                       => 'arrow1',
				'slider_autoplay'                          => '1',
				'slider_autoplay_intervals'                => '3000',
				'slider_speed'                             => '300',
				'enable_nav_to_scroll'                     => '1',
				'enable_lazy_load'                         => '0',
				'enable_lazy_load_blur_image'              => '0',
				'template_lazy_load_color'                 => isset( $blog_designer_setting['template_lazy_load_color'] ) ? $blog_designer_setting['template_lazy_load_color'] : '',
				'enable_print_page'                        => '1',
				'enable_disbale_customread_more'           => '1',
				'display_customread_more'                  => '1',
				'beforeloop_Readmoretext'                  => '',
				'beforeloop_Readmoretextlink'              => '',
				'open_customlink'                          => '1',
				'display_sale_tag'                         => '0',
				'bdp_sale_tagtext_alignment'               => 'left-top',
				'bdp_sale_tagtext_marginleft'              => '5',
				'bdp_sale_tagtext_marginright'             => '5',
				'bdp_sale_tagtext_margintop'               => '5',
				'bdp_sale_tagtext_marginbottom'            => '5',
				'bdp_sale_tagtext_paddingleft'             => '5',
				'bdp_sale_tagtext_paddingright'            => '5',
				'bdp_sale_tagtext_paddingtop'              => '5',
				'bdp_sale_tagtext_paddingbottom'           => '5',
				'bdp_sale_tagtextcolor'                    => '#ffffff',
				'bdp_sale_tagbgcolor'                      => '#777777',
				'bdp_sale_tag_angle'                       => '0',
				'bdp_sale_tag_border_radius'               => '0',
				'bdp_sale_tagfontface'                     => '',
				'bdp_sale_tagfontsize'                     => '18',
				'bdp_sale_tag_font_weight'                 => '700',
				'bdp_sale_tag_font_line_height'            => '1.5',
				'bdp_sale_tag_font_italic'                 => '0',
				'bdp_sale_tag_font_text_transform'         => 'none',
				'bdp_sale_tag_font_text_decoration'        => 'none',
				'display_product_rating'                   => '0',
				'bdp_star_rating_bg_color'                 => '#000000',
				'bdp_star_rating_color'                    => '#d3ced2',
				'bdp_star_rating_alignment'                => 'left',
				'bdp_star_rating_paddingleft'              => '5',
				'bdp_star_rating_paddingright'             => '5',
				'bdp_star_rating_paddingtop'               => '5',
				'bdp_star_rating_paddingbottom'            => '5',
				'bdp_star_rating_marginleft'               => '5',
				'bdp_star_rating_marginright'              => '5',
				'bdp_star_rating_margintop'                => '5',
				'bdp_star_rating_marginbottom'             => '5',
				'display_product_price'                    => '0',
				'bdp_pricetext_alignment'                  => 'left',
				'bdp_pricetext_paddingleft'                => '5',
				'bdp_pricetext_paddingright'               => '5',
				'bdp_pricetext_paddingtop'                 => '5',
				'bdp_pricetext_paddingbottom'              => '5',
				'bdp_pricetext_marginleft'                 => '5',
				'bdp_pricetext_marginright'                => '5',
				'bdp_pricetext_margintop'                  => '5',
				'bdp_pricetext_marginbottom'               => '5',
				'bdp_pricetextcolor'                       => '#444444',
				'bdp_pricefontface_font_type'              => '',
				'bdp_pricefontface'                        => '',
				'bdp_pricefontsize'                        => '18',
				'bdp_price_font_weight'                    => '700',
				'bdp_price_font_line_height'               => '1.5',
				'bdp_price_font_italic'                    => '0',
				'bdp_price_font_text_transform'            => 'none',
				'bdp_price_font_text_decoration'           => 'none',
				'bdp_addtocart_button_font_text_transform' => 'none',
				'bdp_addtocart_button_font_text_decoration' => 'none',
				'bdp_addtowishlist_button_font_text_transform' => 'none',
				'bdp_addtowishlist_button_font_text_decoration' => 'none',
				'bdp_price_font_letter_spacing'            => '0',
				'display_addtocart_button'                 => '0',
				'bdp_addtocart_button_fontface_font_type'  => '',
				'bdp_addtocart_button_fontface'            => '',
				'bdp_addtocart_button_fontsize'            => '14',
				'bdp_addtocart_button_font_weight'         => 'normal',
				'bdp_addtocart_button_font_italic'         => '0',
				'bdp_addtocart_button_letter_spacing'      => '0',
				'display_addtocart_button_line_height'     => '1.5',
				'bdp_addtowishlist_button_fontface_font_type' => '',
				'bdp_addtowishlist_button_fontface'        => '',
				'bdp_addtowishlist_button_fontsize'        => '14',
				'bdp_addtowishlist_button_font_weight'     => 'normal',
				'bdp_addtowishlist_button_font_italic'     => '0',
				'bdp_addtowishlist_button_letter_spacing'  => '0',
				'display_wishlist_button_line_height'      => '1.5',
				'bdp_addtocart_textcolor'                  => '#ffffff',
				'bdp_addtocart_backgroundcolor'            => '#777777',
				'bdp_addtocart_text_hover_color'           => '#ffffff',
				'bdp_addtocart_hover_backgroundcolor'      => '#333333',
				'bdp_addtocartbutton_borderleft'           => '0',
				'bdp_addtocartbutton_borderleftcolor'      => '',
				'bdp_addtocartbutton_borderright'          => '0',
				'bdp_addtocartbutton_borderrightcolor'     => '',
				'bdp_addtocartbutton_bordertop'            => '0',
				'bdp_addtocartbutton_bordertopcolor'       => '',
				'bdp_addtocartbutton_borderbottom'         => '0',
				'bdp_addtocartbutton_borderbottomcolor'    => '',
				'bdp_addtocartbutton_hover_borderleft'     => '0',
				'bdp_addtocartbutton_hover_borderleftcolor' => '',
				'bdp_addtocartbutton_hover_borderright'    => '0',
				'bdp_addtocartbutton_hover_borderrightcolor' => '',
				'bdp_addtocartbutton_hover_bordertop'      => '0',
				'bdp_addtocartbutton_hover_bordertopcolor' => '',
				'bdp_addtocartbutton_hover_borderbottom'   => '0',
				'bdp_addtocartbutton_hover_borderbottomcolor' => '',
				'display_addtocart_button_border_hover_radius' => '0',
				'bdp_addtocartbutton_padding_leftright'    => '10',
				'bdp_addtocartbutton_padding_topbottom'    => '10',
				'bdp_addtocartbutton_margin_leftright'     => '15',
				'bdp_addtocartbutton_margin_topbottom'     => '10',
				'bdp_addtocartbutton_alignment'            => 'left',
				'display_addtocart_button_border_radius'   => '0',
				'bdp_addtocart_button_left_box_shadow'     => '0',
				'bdp_addtocart_button_right_box_shadow'    => '0',
				'bdp_addtocart_button_top_box_shadow'      => '0',
				'bdp_addtocart_button_bottom_box_shadow'   => '0',
				'bdp_addtocart_button_box_shadow_color'    => '',
				'bdp_addtocart_button_hover_left_box_shadow' => '0',
				'bdp_addtocart_button_hover_right_box_shadow' => '0',
				'bdp_addtocart_button_hover_top_box_shadow' => '0',
				'bdp_addtocart_button_hover_bottom_box_shadow' => '0',
				'bdp_addtocart_button_hover_box_shadow_color' => '',
				'display_addtowishlist_button'             => '0',
				'bdp_wishlistbutton_alignment'             => 'left',
				'bdp_cart_wishlistbutton_alignment'        => 'left',
				'bdp_wishlistbutton_on'                    => '1',
				'bdp_wishlist_textcolor'                   => '#ffffff',
				'bdp_wishlist_text_hover_color'            => '#ffffff',
				'bdp_wishlist_backgroundcolor'             => '#777777',
				'bdp_wishlist_hover_backgroundcolor'       => '#333333',
				'display_wishlist_button_border_radius'    => '0',
				'bdp_wishlistbutton_borderleft'            => '0',
				'bdp_wishlistbutton_borderleftcolor'       => '',
				'bdp_wishlistbutton_borderright'           => '0',
				'bdp_wishlistbutton_borderrightcolor'      => '',
				'bdp_wishlistbutton_bordertop'             => '0',
				'bdp_wishlistbutton_bordertopcolor'        => '',
				'bdp_wishlistbutton_borderbuttom'          => '0',
				'bdp_wishlistbutton_borderbottomcolor'     => '',
				'display_wishlist_button_border_hover_radius' => '0',
				'bdp_wishlistbutton_hover_borderleft'      => '0',
				'bdp_wishlistbutton_hover_borderleftcolor' => '',
				'bdp_wishlistbutton_hover_borderright'     => '0',
				'bdp_wishlistbutton_hover_borderrightcolor' => '',
				'bdp_wishlistbutton_hover_bordertop'       => '0',
				'bdp_wishlistbutton_hover_bordertopcolor'  => '',
				'bdp_wishlistbutton_hover_borderbuttom'    => '0',
				'bdp_wishlistbutton_hover_borderbottomcolor' => '',
				'bdp_wishlistbutton_padding_leftright'     => '10',
				'bdp_wishlistbutton_padding_topbottom'     => '10',
				'bdp_wishlistbutton_margin_leftright'      => '10',
				'bdp_wishlistbutton_margin_topbottom'      => '10',
				'beforeloop_readmorecolor'                 => '#ffffff',
				'beforeloop_readmorebackcolor'             => '#333333',
				'beforeloop_readmorehovercolor'            => '#333333',
				'beforeloop_readmorehoverbackcolor'        => '#f1f1f1',
				'beforeloop_titlefontface_font_type'       => '',
				'beforeloop_titlefontface'                 => '',
				'beforeloop_titlefontsize'                 => '14',
				'beforeloop_title_font_weight'             => 'normal',
				'beforeloop_title_font_line_height'        => '1.5',
				'beforeloop_title_font_text_transform'     => 'none',
				'beforeloop_title_font_text_decoration'    => 'none',
				'beforeloop_title_font_letter_spacing'     => '0',
				'social_style'                             => '0',
				'social_icon_style'                        => isset( $blog_designer_setting['social_icon_style'] ) ? $blog_designer_setting['social_icon_style'] : '',
				'social_icon_size'                         => '1',
				'default_icon_theme'                       => '1',
				'facebook_link'                            => isset( $blog_designer_setting['facebook_link'] ) ? $blog_designer_setting['facebook_link'] : '',
				'facebook_link_with_count'                 => '1',
				'linkedin_link'                            => isset( $blog_designer_setting['linkedin_link'] ) ? $blog_designer_setting['linkedin_link'] : '',
				'pinterest_link'                           => '1',
				'pinterest_link_with_count'                => '1',
				'twitter_link'                             => isset( $blog_designer_setting['twitter_link'] ) ? $blog_designer_setting['twitter_link'] : '',
				'pocket_link'                              => isset( $blog_designer_setting['pinterest_link'] ) ? $blog_designer_setting['pinterest_link'] : '',
				'telegram_link'                            => '0',
				'email_link'                               => isset( $blog_designer_setting['email_link'] ) ? $blog_designer_setting['email_link'] : '',
				'whatsapp_link'                            => '0',
				'social_count_position'                    => 'right',
				'custom_css'                               => isset( $blog_designer_setting['custom_css'] ) ? $blog_designer_setting['custom_css'] : '',
				'savedata'                                 => '',
				'display_acf_field'                        => '0',
				'bdp_acf_field'                            => '',
				'display_download_price'                   => '0',
				'bdp_edd_price_alignment'                  => 'left',
				'bdp_edd_price_paddingleft'                => '5',
				'bdp_edd_price_paddingright'               => '5',
				'bdp_edd_price_paddingtop'                 => '5',
				'bdp_edd_price_paddingbottom'              => '5',
				'bdp_edd_price_color'                      => '#444444',
				'bdp_edd_pricefontface_font_type'          => '',
				'bdp_edd_pricefontface'                    => '',
				'bdp_edd_pricefontsize'                    => '18',
				'bdp_edd_price_font_weight'                => '700',
				'bdp_edd_price_font_line_height'           => '1.5',
				'bdp_edd_price_font_italic'                => '0',
				'bdp_edd_price_font_letter_spacing'        => '0',
				'bdp_edd_price_font_text_decoration'       => 'none',
			);
			$table_name           = $wpdb->prefix . 'blog_designer_pro_shortcodes';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) == $table_name ) {
				$insert_shortcode = $wpdb->insert(
					$table_name,
					array(
						'shortcode_name' => esc_html__( 'Sample Blog Layout', 'blog-designer-pro' ),
						'bdsettings'     => maybe_serialize( $sample_blog_settings ),
					)
				);
				if ( false == $insert_shortcode ) {
					wp_die( esc_html__( 'Sample Blog Layout not created.', 'blog-designer-pro' ) );
				} else {
					$layout_id       = $wpdb->insert_id;
					$blog_args       = array(
						'ID'           => $page_id,
						'post_content' => '[wp_blog_designer id="' . $layout_id . '"]',
					);
					$layout_inserted = wp_update_post( $blog_args );
					Bdp_Ajax_Actions::bdp_admin_notice_pro_layouts_dismiss();
					Bdp_Ajax_Actions::bdp_create_layout_from_blog_designer_dismiss();
					if ( $layout_inserted ) {
						$blog_url = get_permalink( $page_id );
						echo "<script type=\"text/javascript\">window.open('" . esc_url( $blog_url ) . "', '_blank');</script>";
					}
				}
			} else {
				wp_die( esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' ) );
			}
		}
	}
	/**
	 * Get blog template list
	 *
	 * @since 1.6
	 */
	public static function blog_template_list() {
		$tempate_list = array(
			'boxy'               => array(
				'template_name' => esc_html__( 'Boxy Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'boxy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-boxy-blog-template/' ),
			),
			'boxy-clean'         => array(
				'template_name' => esc_html__( 'Boxy Clean Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'boxy-clean.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-boxy-clean-blog-template/' ),
			),
			'brit_co'            => array(
				'template_name' => esc_html__( 'Brit Co Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'brit_co.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-brit-co-blog-template/' ),
			),
			'classical'          => array(
				'template_name' => esc_html__( 'Classical Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'classical.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-classical-blog-template/' ),
			),
			'cool_horizontal'    => array(
				'template_name' => esc_html__( 'Cool Horizontal Template', 'blog-designer-pro' ),
				'class'         => 'timeline slider',
				'image_name'    => 'cool_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-cool-horizontal-timeline-blog-template/' ),
			),
			'cover'              => array(
				'template_name' => esc_html__( 'Cover Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'cover.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-cover-blog-template/' ),
			),
			'clicky'             => array(
				'template_name' => esc_html__( 'Clicky Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'clicky.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-clicky-blog-template/' ),
			),
			'deport'             => array(
				'template_name' => esc_html__( 'Deport Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'deport.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-deport-blog-template/' ),
			),
			'easy_timeline'      => array(
				'template_name' => esc_html__( 'Easy Timeline', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'easy_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-easy-timeline-blog-template/' ),
			),
			'elina'              => array(
				'template_name' => esc_html__( 'Elina Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'elina.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-elina-blog-template/' ),
			),
			'evolution'          => array(
				'template_name' => esc_html__( 'Evolution Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'evolution.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-evolution-blog-template/' ),
			),
			'fairy'              => array(
				'template_name' => esc_html__( 'Fairy Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'fairy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-fairy-blog-template/' ),
			),
			'famous'             => array(
				'template_name' => esc_html__( 'Famous Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'famous.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-famous-blog-template/' ),
			),
			'glamour'            => array(
				'template_name' => esc_html__( 'Glamour Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'glamour.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-glamour-blog-template/' ),
			),
			'glossary'           => array(
				'template_name' => esc_html__( 'Glossary Template', 'blog-designer-pro' ),
				'class'         => 'masonry',
				'image_name'    => 'glossary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-glossary-blog-template/' ),
			),
			'explore'            => array(
				'template_name' => esc_html__( 'Explore Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'explore.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-explore-blog-template/' ),
			),
			'hoverbic'           => array(
				'template_name' => esc_html__( 'Hoverbic Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'hoverbic.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-hoverbic-blog-template/' ),
			),
			'hub'                => array(
				'template_name' => esc_html__( 'Hub Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'hub.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-hub-blog-template/' ),
			),
			'minimal'            => array(
				'template_name' => esc_html__( 'Minimal Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'minimal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-minimal-grid-blog-template/' ),
			),
			'masonry_timeline'   => array(
				'template_name' => esc_html__( 'Masonry Timeline', 'blog-designer-pro' ),
				'class'         => 'magazine timeline',
				'image_name'    => 'masonry_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-masonry-timeline-blog-template/' ),
			),
			'invert-grid'        => array(
				'template_name' => esc_html__( 'Invert Grid Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'invert-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-invert-grid-blog-template/' ),
			),
			'lightbreeze'        => array(
				'template_name' => esc_html__( 'Lightbreeze Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'lightbreeze.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-light-breeze-blog-template/' ),
			),
			// New Layout which is pending to add strucure.
			'leest'               => array(
				'template_name' => esc_html__( 'Leest Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'data'          => 'NEW',
				'image_name'    => 'leest.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-light-breeze-blog-template/' ),
			),
			'media-grid'         => array(
				'template_name' => esc_html__( 'Media Grid Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'media-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-media-grid-blog-template/' ),
			),
			'my_diary'           => array(
				'template_name' => esc_html__( 'My Diary Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'my_diary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-my-diary-blog-template/' ),
			),
			'navia'              => array(
				'template_name' => esc_html__( 'Navia Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'navia.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-navia-blog-template/' ),
			),
			'news'               => array(
				'template_name' => esc_html__( 'News Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'news.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-news-blog-template/' ),
			),
			'offer_blog'         => array(
				'template_name' => esc_html__( 'Offer Blog Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'offer_blog.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-offer-blog-template/' ),
			),
			'overlay_horizontal' => array(
				'template_name' => esc_html__( 'Overlay Horizontal Template', 'blog-designer-pro' ),
				'class'         => 'timeline slider',
				'image_name'    => 'overlay_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-overlay-horizontal-timeline-blog-template/' ),
			),
			'accordion'          => array(
				'template_name' => esc_html__( 'Accordion Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'accordion.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-accordion-blog-template/' ),
			),
			'nicy'               => array(
				'template_name' => esc_html__( 'Nicy Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'nicy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-nicy-blog-template/' ),
			),
			'region'             => array(
				'template_name' => esc_html__( 'Region Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'region.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-region-blog-template/' ),
			),
			'roctangle'          => array(
				'template_name' => esc_html__( 'Roctangle Template', 'blog-designer-pro' ),
				'class'         => 'masonry',
				'image_name'    => 'roctangle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-roctangle-blog-template/' ),
			),
			'sharpen'            => array(
				'template_name' => esc_html__( 'Sharpen Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'sharpen.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sharpen-blog-template/' ),
			),
			'spektrum'           => array(
				'template_name' => esc_html__( 'Spektrum Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'spektrum.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-spektrum-blog-template/' ),
			),
			'story'              => array(
				'template_name' => esc_html__( 'Story Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'story.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-story-timeline-blog-template/' ),
			),
			'timeline'           => array(
				'template_name' => esc_html__( 'Timeline Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-timeline-blog-template/' ),
			),
			'tabbed'             => array(
				'template_name' => esc_html__( 'Tabbed Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'tabbed.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/tabbed-blog-template/' ),
			),
			'winter'             => array(
				'template_name' => esc_html__( 'Winter Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'winter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-winter-blog-template/' ),
			),
			'crayon_slider'      => array(
				'template_name' => esc_html__( 'Crayon Slider Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'image_name'    => 'crayon_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-crayon-slider-blog-template/' ),
			),
			'sallet_slider'      => array(
				'template_name' => esc_html__( 'Sallet Slider Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'image_name'    => 'sallet_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sallet-slider-blog-template/' ),
			),
			'colorful_sliding'   => array(
				'template_name' => esc_html__( 'Colorful Sliding Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'image_name'    => 'colorful_sliding.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-colorful-sliding-blog-template/' ),
			),
			'sunshiny_slider'    => array(
				'template_name' => esc_html__( 'Sunshiny Slider Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'image_name'    => 'sunshiny_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sunshiny-slider-blog-template/' ),
			),
			'pretty'             => array(
				'template_name' => esc_html__( 'Pretty Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'pretty.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-pretty-blog-template/' ),
			),
			'tagly'              => array(
				'template_name' => esc_html__( 'Tagly Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'tagly.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-tagly-blog-template/' ),
			),
			'brite'              => array(
				'template_name' => esc_html__( 'Brite Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'brite.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-brite-blog-template/' ),
			),
			'chapter'            => array(
				'template_name' => esc_html__( 'Chapter Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'chapter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-chapter-blog-template/' ),
			),
			'steps'              => array(
				'template_name' => esc_html__( 'Steps Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'steps.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-steps-timeline-blog-template/' ),
			),
			'miracle'            => array(
				'template_name' => esc_html__( 'Miracle Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'miracle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-miracle-blog-template/' ),
			),
			'foodbox'            => array(
				'template_name' => esc_html__( 'Food Box Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'foodbox.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-foodbox-blog-template/' ),
			),
			'neaty_block'        => array(
				'template_name' => esc_html__( 'Neaty Block Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'neaty_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-neaty-block-blog-template/' ),
			),
			'wise_block'         => array(
				'template_name' => esc_html__( 'Wise Block Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'data'          => 'NEW',
				'image_name'    => 'wise_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-wise-block-blog-template/' ),
			),
			'soft_block'         => array(
				'template_name' => esc_html__( 'Soft Block Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'soft_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-soft-block-blog-template/' ),
			),
			'schedule'           => array(
				'template_name' => esc_html__( 'Schedule Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'schedule.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-schedule-blog-template/' ),
			),
			'quci'               => array(
				'template_name' => esc_html__( 'Quci Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'data'          => 'NEW',
				'image_name'    => 'quci.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-quci-blog-template/' ),
			),
			'pedal'              => array(
				'template_name' => esc_html__( 'Pedal Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'pedal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-pedal-blog-template/' ),
			),
			'ticker'             => array(
				'template_name' => esc_html__( 'Ticker Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'ticker.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-ticker-template/' ),
			),
			'blog_grid_box'      => array(
				'template_name' => esc_html__( 'Blog Grid Box Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'blog_grid_box.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-grid-box-template/' ),
			),
			'blog_carousel'      => array(
				'template_name' => esc_html__( 'Blog Carousel Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'data'          => 'NEW',
				'image_name'    => 'blog_carousel.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-carousel-blog-template/' ),
			),
			'threed_carousel'    => array(
				'template_name' => esc_html__( '3D Carousel Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'data'          => 'NEW',
				'image_name'    => 'threed_carousel.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/3d-carousel-blog-template/' ),
			),
			'flip_book_3d'       => array(
				'template_name' => esc_html__( ' 3D Flip Book Template', 'blog-designer-pro' ),
				'class'         => 'slider',
				'data'          => 'NEW',
				'image_name'    => 'flip_book_3d.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/3d-flipbook-slider-blog-template/' ),
			),
			'banner'             => array(
				'template_name' => esc_html__( 'Banner Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'banner.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/banner-blog-template/' ),
			),
		);
		ksort( $tempate_list );
		return $tempate_list;
	}
	/**
	 * Get single blog template list
	 *
	 * @since 1.6
	 */
	public static function single_blog_template_list() {
		$tempate_list = array(
			'boxy'               => array(
				'template_name' => esc_html__( 'Boxy Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'boxy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=boxy' ),
			),
			'boxy-clean'         => array(
				'template_name' => esc_html__( 'Boxy Clean Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'boxy-clean.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=boxy-clean' ),
			),
			'brit_co'            => array(
				'template_name' => esc_html__( 'Brit Co Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'brit_co.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=brit_co' ),
			),
			'brite'              => array(
				'template_name' => esc_html__( 'Brite Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'brite.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=brite' ),
			),
			'chapter'            => array(
				'template_name' => esc_html__( 'Chapter Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'chapter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=chapter' ),
			),
			'classical'          => array(
				'template_name' => esc_html__( 'Classical Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'classical.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=classical' ),
			),
			'cool_horizontal'    => array(
				'template_name' => esc_html__( 'Cool Horizontal Template', 'blog-designer-pro' ),
				'class'         => 'timeline slider',
				'image_name'    => 'cool_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=cool_horizontal' ),
			),
			'deport'             => array(
				'template_name' => esc_html__( 'Deport Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'deport.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=deport' ),
			),
			'easy_timeline'      => array(
				'template_name' => esc_html__( 'Easy Timeline Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'easy_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=easy_timeline' ),
			),
			'elina'              => array(
				'template_name' => esc_html__( 'Elina Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'elina.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=elina' ),
			),
			'evolution'          => array(
				'template_name' => esc_html__( 'Evolution Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'evolution.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=evolution' ),
			),
			'hub'                => array(
				'template_name' => esc_html__( 'Hub Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'hub.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=hub' ),
			),
			'glossary'           => array(
				'template_name' => esc_html__( 'Glossary Template', 'blog-designer-pro' ),
				'class'         => 'masonry',
				'image_name'    => 'glossary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=glossary' ),
			),
			'explore'            => array(
				'template_name' => esc_html__( 'Explore Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'explore.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=explore' ),
			),
			'masonry_timeline'   => array(
				'template_name' => esc_html__( 'Masonry Timeline', 'blog-designer-pro' ),
				'class'         => 'magazine timeline',
				'image_name'    => 'masonry_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=masonry_timeline' ),
			),
			'nicy'               => array(
				'template_name' => esc_html__( 'Nicy Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'nicy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=nicy' ),
			),
			'invert-grid'        => array(
				'template_name' => esc_html__( 'Invert Grid Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'invert-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=invert-grid' ),
			),
			'lightbreeze'        => array(
				'template_name' => esc_html__( 'Lightbreeze Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'lightbreeze.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=lightbreeze' ),
			),
			'media-grid'         => array(
				'template_name' => esc_html__( 'Media Grid Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'media-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=media-grid' ),
			),
			'my_diary'           => array(
				'template_name' => esc_html__( 'My Diary Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'my_diary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=my_diary' ),
			),
			'navia'              => array(
				'template_name' => esc_html__( 'Navia Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'navia.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=navia' ),
			),
			'news'               => array(
				'template_name' => esc_html__( 'News Template', 'blog-designer-pro' ),
				'class'         => 'magazine',
				'image_name'    => 'news.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=news' ),
			),
			'offer_blog'         => array(
				'template_name' => esc_html__( 'Offer Blog Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'offer_blog.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=offer_blog' ),
			),
			'overlay_horizontal' => array(
				'template_name' => esc_html__( 'Overlay Horizontal Template', 'blog-designer-pro' ),
				'class'         => 'timeline slider',
				'image_name'    => 'overlay_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=overlay_horizontal' ),
			),
			'region'             => array(
				'template_name' => esc_html__( 'Region Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'region.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=region' ),
			),
			'roctangle'          => array(
				'template_name' => esc_html__( 'Roctangle Template', 'blog-designer-pro' ),
				'class'         => 'masonry',
				'image_name'    => 'roctangle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=roctangle' ),
			),
			'spektrum'           => array(
				'template_name' => esc_html__( 'Spektrum Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'spektrum.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=spektrum' ),
			),
			'sharpen'            => array(
				'template_name' => esc_html__( 'Sharpen Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'sharpen.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=sharpen' ),
			),
			'story'              => array(
				'template_name' => esc_html__( 'Story Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'story.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=story_timeline' ),
			),
			'tagly'              => array(
				'template_name' => esc_html__( 'Tagly Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'tagly.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=tagly' ),
			),
			'timeline'           => array(
				'template_name' => esc_html__( 'Timeline Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=timeline' ),
			),
			'winter'             => array(
				'template_name' => esc_html__( 'Winter Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'winter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=winter' ),
			),
			'pretty'             => array(
				'template_name' => esc_html__( 'Pretty Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'pretty.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=pretty' ),
			),
			'minimal'            => array(
				'template_name' => esc_html__( 'Minimal Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'minimal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=minimal' ),
			),
			'glamour'            => array(
				'template_name' => esc_html__( 'Glamour Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'glamour.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=glamour' ),
			),
			'famous'             => array(
				'template_name' => esc_html__( 'Famous Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'famous.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=famous' ),
			),
			'fairy'              => array(
				'template_name' => esc_html__( 'Fairy Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'fairy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=fairy' ),
			),
			'clicky'             => array(
				'template_name' => esc_html__( 'Clicky Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'clicky.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=clicky' ),
			),
			'cover'              => array(
				'template_name' => esc_html__( 'Cover Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'image_name'    => 'cover.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=cover' ),
			),
			'steps'              => array(
				'template_name' => esc_html__( 'Steps Template', 'blog-designer-pro' ),
				'class'         => 'timeline',
				'image_name'    => 'steps.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=steps' ),
			),
			'miracle'            => array(
				'template_name' => esc_html__( 'Miracle Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'image_name'    => 'miracle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=miracle' ),
			),
			'foodbox'            => array(
				'template_name' => esc_html__( 'Food Box Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'foodbox.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=foodbox' ),
			),
			'neaty_block'        => array(
				'template_name' => esc_html__( 'Neaty Block Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'neaty_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=neaty_block' ),
			),
			'wise_block'         => array(
				'template_name' => esc_html__( 'Wise Block Template', 'blog-designer-pro' ),
				'class'         => 'grid',
				'data'          => 'NEW',
				'image_name'    => 'wise_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=wise_block' ),
			),
			'soft_block'         => array(
				'template_name' => esc_html__( 'Soft Block Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'soft_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=soft_block' ),
			),
			'schedule'           => array(
				'template_name' => esc_html__( 'Schedule Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'schedule.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=schedule' ),
			),
			'quci'               => array(
				'template_name' => esc_html__( 'Quci Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'quci.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=quci' ),
			),
			'pedal'              => array(
				'template_name' => esc_html__( 'Pedal Template', 'blog-designer-pro' ),
				'class'         => 'full-width',
				'data'          => 'NEW',
				'image_name'    => 'pedal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/blog-designer-plugin-instead-of-replacing-wordpress-theme-simultaneously/?template=pedal' ),
			),
		);
		ksort( $tempate_list );
		return $tempate_list;
	}
}
new Bdp_Template();
