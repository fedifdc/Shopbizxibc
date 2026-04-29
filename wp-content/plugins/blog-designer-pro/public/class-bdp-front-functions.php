<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/public
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

/**
 * Main Blog Designer PRO Front Function Class.
 *
 * @class   Bdp_Front_Functions
 * @version 1.0.0
 */
class Bdp_Front_Functions {
	/**
	 * Templat Name
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $template_name template name.
	 */
	public static $template_name = array();
	/**
	 * Shortcode ID
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $shortcode_id shortcode.
	 */
	public static $shortcode_id = array();
	/**
	 * Templat Stylesheet
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    int $template_stylesheet_added Templat Stylesheet.
	 */
	public static $template_stylesheet_added = 0;
	/**
	 * Template Dynamic Stylesheet
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    int $template_dynamic_stylesheet_added Template Dynamic Stylesheet.
	 */
	public static $template_dynamic_stylesheet_added = 0;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb, $bdp_db_version;
		$wp_version     = get_bloginfo( 'version' );
		$bdp_db_version = '2.7.6';
		/** When activated plugins have loaded */

		add_action( 'plugins_loaded', array( &$this, 'bdp_load_language_files' ) );
		add_action( 'plugins_loaded', array( &$this, 'bdp_update_database_structure' ) );
		add_action( 'plugins_loaded', array( &$this, 'bdp_latest_news_solwin_feed' ) );
		add_action( 'plugins_loaded', array( &$this, 'bdp_most_viewed_posts' ) );
		add_action( 'upgrader_process_complete', array( &$this, 'bdp_plugin_update_complete' ) );
		if ( function_exists( 'add_avartan_dashboard_widgets' ) ) {
			remove_action( 'wp_dashboard_setup', 'add_avartan_dashboard_widgets' );
		}
		/* Ajax URL */
		add_action( 'wp_head', array( &$this, 'bdp_ajaxurl' ), 5 );
		/* Register style and script */
		add_action( 'init', array( &$this, 'bdp_front_stylesheet' ), 20 );
		add_action( 'init', array( &$this, 'bdp_front_script' ), 2 );
		add_action( 'init', array( &$this, 'wp_blog_designer_pro_redirection' ), 1 );
		add_action( 'init', array( &$this, 'bdp_initialize_images' ), 10 );
		/* Style for shortcode added from admin side content */
		add_action( 'wp_enqueue_scripts', array( &$this, 'bdp_add_template_style' ), 10 );
		add_action( 'wp_head', array( &$this, 'bdp_template_dynamic_css' ), 20 );
		/* Style for shortcode added in php code */
		add_action( 'wp_footer', array( &$this, 'bdp_add_template_style' ), 10 );
		add_action( 'wp_footer', array( &$this, 'bdp_template_dynamic_css' ), 20 );
		/** Modify an existing WP_Query */
		add_action( 'pre_get_posts', array( &$this, 'bdp_change_author_date_pagination' ), 20 );
		/** Ajax actions */
		add_action( 'wp_ajax_nopriv_get_loadmore_blog', array( &$this, 'bdp_loadmore_blog' ), 12 );
		add_action( 'wp_ajax_get_loadmore_blog', array( &$this, 'bdp_loadmore_blog' ), 12 );
		add_action( 'wp_ajax_nopriv_get_load_onscroll_blog', array( &$this, 'bdp_load_onscroll_blog' ), 12 );
		add_action( 'wp_ajax_get_load_onscroll_blog', array( &$this, 'bdp_load_onscroll_blog' ), 12 );
		add_action( 'wp_ajax_nopriv_filter_change', array( &$this, 'filter_change' ), 12 );
		add_action( 'wp_ajax_filter_change', array( &$this, 'filter_change' ), 12 );
		add_action( 'wp_ajax_nopriv_get_bdp_process_posts_like', array( &$this, 'bdp_process_posts_like' ), 15 );
		add_action( 'wp_ajax_get_bdp_process_posts_like', array( &$this, 'bdp_process_posts_like' ), 15 );
		add_action( 'wp_ajax_nopriv_get_post_type_post_list', array( &$this, 'bdp_get_post_type_post_list' ), 16 );
		add_action( 'wp_ajax_get_post_type_post_list', array( &$this, 'bdp_get_post_type_post_list' ), 16 );
		add_action( 'wp_ajax_nopriv_bdp_layouts_notice_dismissible', array( &$this, 'bdp_layouts_notice_dismissible' ), 20 );
		add_action( 'wp_ajax_bdp_layouts_notice_dismissible', array( &$this, 'bdp_layouts_notice_dismissible' ), 20 );
		add_action( 'wp_ajax_nopriv_bdp_email_share_form', array( &$this, 'bdp_email_share_form' ), 40 );
		add_action( 'wp_ajax_bdp_email_share_form', array( &$this, 'bdp_email_share_form' ), 40 );
		add_action( 'wp_ajax_nopriv_ajax_filter_posts', array( &$this, 'ajax_filter_posts' ), 12 );
		add_action( 'wp_ajax_ajax_filter_posts', array( &$this, 'ajax_filter_posts' ), 12 );
		/** Override Single and archive template file */
		add_filter( 'single_template', array( &$this, 'bdp_custom_single_template' ), 99, 10 );
		add_filter( 'template_include', array( &$this, 'bdp_get_custom_archive_template' ), 99 );
		/** Add wp_blog_designer shortcode */
		add_shortcode( 'wp_blog_designer', array( &$this, 'bdp_shortcode_function' ), 10 );
		/** Add WPBakery Page Builder Support */
		add_action( 'vc_before_init', array( &$this, 'bdp_add_vc_support' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'bdp_plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( &$this, 'bdp_plugin_row_meta' ), 10, 2 );
		// add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 10, 3 );.
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'bdp_front_stylesheet' ), 20 );
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'bdp_front_script' ), 20 );
		}
	}

	/**
	 * Add image sizes
	 *
	 * @return void
	 */
	public function bdp_initialize_images() {
		add_image_size( 'news-thumb', 300, 300, true );
		add_image_size( 'related-post-thumb', 640, 300, true );
		add_image_size( 'invert-grid-thumb', 640, 320, true );
		add_image_size( 'deport-thumb', 640, 520, true );
		add_image_size( 'deport-thumbnail', 640, 640, true );
		add_image_size( 'brit_co_img', 580, 255, true );
		add_image_size( 'easy_timeline_img', 500, 300, true );
		add_image_size( 'cover_thumb', 320, 480, true );
		add_image_size( 'tabbed_thumb', 50, 50, false );
		add_image_size( 'blog_grid_box_thumb', 100, 100, false );
	}
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param string $links links.
	 * @return string $links
	 */
	public function bdp_plugin_action_links( $links ) {
		$action_links       = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=layouts' ) . '" title="' . esc_attr( esc_html__( 'View Blog Designer Settings', 'blog-designer-pro' ) ) . '">' . esc_html__( 'Layouts', 'blog-designer-pro' ) . '</a>',
		);
		$links              = array_merge( $action_links, $links );
		$links['documents'] = '<a target="_blank" href="' . esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#quick_guide' ) . '">' . esc_html__( 'Documentation', 'blog-designer-pro' ) . '</a>';
		return $links;
	}
	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param string $links links.
	 * @param string $file file.
	 * @return array $links
	 */
	public function bdp_plugin_row_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			$row_meta = array(
				'support' => '<a href="' . esc_url( 'http://support.solwininfotech.com/' ) . '" title="' . esc_html__( 'Visit Premium Customer Support Forum', 'blog-designer-pro' ) . '" target="_blank">' . esc_html__( 'Premium Support', 'blog-designer-pro' ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	/**
	 * Add support to WPBakery Page Builder plugin
	 *
	 * @return void
	 */
	public function bdp_add_vc_support() {
		global $wpdb;
		$bdp_table_name  = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		$bdp_bdids       = $wpdb->get_results( $wpdb->prepare( "SELECT bdid,shortcode_name FROM {$wpdb->prefix}blog_designer_pro_shortcodes" ) );
		$bdp_bdipd_array = array( 'Select Layout Id' );
		if ( ! empty( $bdp_bdids ) && is_array( $bdp_bdids ) ) {
			foreach ( $bdp_bdids as $bdp_bdid ) {
				$bdp_bdipd_array[ $bdp_bdid->shortcode_name ] = $bdp_bdid->bdid;
			}
		}
		vc_map(
			array(
				'name'              => esc_html__( 'Blog Designer', 'blog-designer-pro' ),
				'base'              => 'wp_blog_designer',
				'class'             => 'blog_designer_pro_section',
				'category'          => esc_html__( 'Content' ),
				'icon'              => 'blog_designer_icon',
				'admin_enqueue_css' => array( BLOGDESIGNERPRO_URL . '/admin/css/vc_style.css' ),
				'description'       => esc_html__( 'Custom Blog Layouts', 'blog-designer-pro' ),
				'params'            => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Select Blog Designer Layout Id', 'blog-designer-pro' ),
						'param_name'  => 'id',
						'value'       => $bdp_bdipd_array,
						'admin_label' => true,
					),
				),
			)
		);
	}
	/**
	 * Load Language Files
	 *
	 * @return void
	 */
	public function bdp_load_language_files() {
		load_plugin_textdomain( 'blog-designer-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	/**
	 * Change db structure
	 */
	public function bdp_update_database_structure() {
		global $bdp_db_version;
		if ( get_option( 'bdp_db_version' ) != $bdp_db_version ) {
			Bdp_Utility::add_single_db_structure();
		}
	}
	/**
	 * Register the new dashboard widget with the 'wp_dashboard_setup' action.
	 *
	 * @return void
	 */
	public function bdp_latest_news_solwin_feed() {
		add_action( 'wp_dashboard_setup', 'solwin_latest_news_with_product_details' );
		if ( ! function_exists( 'solwin_latest_news_with_product_details' ) ) {
			/**
			 * Latest News with Product Details
			 *
			 * @return void
			 */
			function solwin_latest_news_with_product_details() {
				add_screen_option(
					'layout_columns',
					array(
						'max'     => 3,
						'default' => 2,
					)
				);
				add_meta_box( 'bdp_dashboard_widget', esc_html__( 'News From Solwin Infotech', 'blog-designer-pro' ), 'bdp_dashboard_widget_news', 'dashboard', 'normal', 'high' );
			}
		}
		if ( ! function_exists( 'bdp_dashboard_widget_news' ) ) {
			/**
			 * Dashboard Widget News
			 *
			 * @return void
			 */
			function bdp_dashboard_widget_news() {
				echo '<div class="rss-widget"><div class="solwin-news"><p><strong>' . esc_html__( 'Solwin Infotech News', 'blog-designer-pro' ) . '</strong></p>';
				wp_widget_rss_output(
					array(
						'url'          => esc_url( 'https://www.solwininfotech.com/feed/' ),
						'title'        => esc_html__( 'News From Solwin Infotech', 'blog-designer-pro' ),
						'items'        => 5,
						'show_summary' => 0,
						'show_author'  => 0,
						'show_date'    => 1,
					)
				);
				echo '</div>';
				$title     = '';
				$link      = '';
				$thumbnail = '';
				// get Latest product detail from xml file.
				$file = esc_url( 'https://www.solwininfotech.com/documents/assets/latest_product.xml' );
				define( 'BLOGDESIGNERPRO_LATEST_PRODUCT_FILE', $file );
				echo '<div class="display-product">'
					. '<div class="product-detail"><p><strong>' . esc_html__( 'Latest Product', 'blog-designer-pro' ) . '</strong></p>';
				$response = wp_remote_get( BLOGDESIGNERPRO_LATEST_PRODUCT_FILE, array( 'sslverify' => false ) );
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo '<p>' . esc_html__( 'Something went wrong', 'blog-designer-pro' ) . ': ' . esc_html( $error_message ) . '</p>';
				} else {
					$body                = wp_remote_retrieve_body( $response );
					$xml                 = simplexml_load_string( $body );
					$title               = $xml->item->name;
					$thumbnail           = $xml->item->img;
					$link                = $xml->item->link;
					$all_product_text    = $xml->item->viewalltext;
					$all_product_link    = $xml->item->viewalllink;
					$moretext            = $xml->item->moretext;
					$needsupporttext     = $xml->item->needsupporttext;
					$needsupportlink     = $xml->item->needsupportlink;
					$customservicetext   = $xml->item->customservicetext;
					$customservicelink   = $xml->item->customservicelink;
					$joinproductclubtext = $xml->item->joinproductclubtext;
					$joinproductclublink = $xml->item->joinproductclublink;
					echo '<div class="product-name"><a href="' . esc_url( $link ) . '" target="_blank">'
						. '<img alt="' . esc_attr( $title ) . '" src="' . esc_attr( $thumbnail ) . '"> </a>'
						. '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $title ) . '</a>'
						. '<p><a href="' . esc_url( $all_product_link ) . '" target="_blank" class="button button-default">' . esc_html( $all_product_text ) . ' &RightArrow;</a></p>'
						. '<hr>'
						. '<p><strong>' . esc_html( $moretext ) . '</strong></p>'
						. '<ul>'
						. '<li><a href="' . esc_url( $needsupportlink ) . '" target="_blank">' . esc_html( $needsupporttext ) . '</a></li>'
						. '<li><a href="' . esc_url( $customservicelink ) . '" target="_blank">' . esc_html( $customservicetext ) . '</a></li>'
						. '<li><a href="' . esc_url( $joinproductclublink ) . '" target="_blank">' . esc_html( $joinproductclubtext ) . '</a></li>'
						. '</ul>'
						. '</div>';
				}
				echo '</div></div><div class="clear"></div></div>';
			}
		}
	}
	/**
	 * Most Views Post widget in dashbord
	 *
	 * @return void
	 */
	public function bdp_most_viewed_posts() {
		add_action( 'wp_dashboard_setup', 'bdp_most_viewed_posts_details' );
		if ( ! function_exists( 'bdp_most_viewed_posts_details' ) ) {
			/**
			 * Most viewsd posts details
			 *
			 * @return void
			 */
			function bdp_most_viewed_posts_details() {
				add_screen_option(
					'layout_columns',
					array(
						'max'     => 3,
						'default' => 2,
					)
				);
				add_meta_box( 'bdp_dashboard_widget', esc_html__( 'Most Viewed Posts', 'blog-designer-pro' ), 'bdp_dashboard_widget_most_veiwed_post', 'dashboard', 'side', 'high' );
			}
		}
		if ( ! function_exists( 'bdp_dashboard_widget_most_veiwed_post' ) ) {
			/**
			 * Dashboard widget most vieied post
			 *
			 * @return void
			 */
			function bdp_dashboard_widget_most_veiwed_post() {
				$args      = array(
					'post_type'           => 'any',
					'ignore_sticky_posts' => 0,
					'orderby'             => 'meta_value_num',
					'meta_key'            => '_bdp_post_views_count',
					'posts_per_page'      => '10',
				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					echo '<ul class="bdp-most-viewed-posts-list">';
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$count = get_post_meta( get_the_ID(), '_bdp_post_views_count', true );
						echo '<li>';
						echo '<span class="bdp-post-view-count"> ' . esc_html( $count ) . ' ' . esc_html__( 'hits', 'blog-designer-pro' ) . ' </span>';
						echo '<div class="bdp-post-view-title"> <a href="' . esc_url( get_edit_post_link() ) . '" target="_blank">' . esc_html( get_the_title() ) . ' </a></div>';
						echo '</li>';
					}
					echo '</ul>';
				}
				wp_reset_postdata();
			}
		}
	}
	/**
	 * After Plugin Update
	 *
	 * @return void
	 */
	public function bdp_plugin_update_complete() {
		update_option( 'bdp_template_outdated', 0 );
	}
	/**
	 * Set ajaxurl
	 *
	 * @return void
	 */
	public function bdp_ajaxurl() {
		?>
		<script type="text/javascript">
			var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
		</script>
		<?php
	}

	/**
	 * Front Stylesheet
	 *
	 * @return void
	 */
	public function bdp_front_stylesheet() {
		$bdp_disable_fa_css = get_option( 'bdp_disable_fa_css', 0 );
		$page_edit_id       = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : 0;
		$elementor_page     = ! empty( $page_edit_id ) ? get_post_meta( $page_edit_id, '_elementor_edit_mode', true ) : '';
		if ( ! is_admin() || ! empty( $elementor_page ) ) {
			$fontawesome_icon_url   = BLOGDESIGNERPRO_URL . '/public/css/font-awesome.min.css';
			$bdp_gallery_slider_url = BLOGDESIGNERPRO_URL . '/public/css/flexslider.css';
			$fontawesomeicon        = __DIR__ . '/css/font-awesome.min.css';
			$bdp_gallery_slider     = __DIR__ . '/css/flexslider.css';
			if ( file_exists( $fontawesomeicon ) && 0 == $bdp_disable_fa_css ) {
				wp_register_style( 'bdp-fontawesome-stylesheets', $fontawesome_icon_url, null, '6.5.1' );
			}
			if ( file_exists( $bdp_gallery_slider ) ) {
				wp_register_style( 'bdp-galleryslider-stylesheets', $bdp_gallery_slider_url, null, '1.0' );
			}
			wp_register_style( 'bdp-recent-widget-css', plugins_url( 'css/recent_widget.css', __FILE__ ), null, '1.0' );
			if ( is_rtl() ) {
				wp_register_style( 'bdp-recent-widget-rtl-css', plugins_url( 'css/recent_widget_rtl.css', __FILE__ ), null, '1.0' );
			}
			wp_enqueue_style( 'bdp-bookblock-css', plugins_url( 'css/bookblock.css', __FILE__ ), null, '1.0' );
			wp_enqueue_style( 'slick_admin_css', plugins_url( 'css/slick.css', __FILE__ ), null, '1.4.1' );
			// Register Blog & Archive Layouts css files.
			wp_register_style( 'bdp-classical-template-css', plugins_url( 'css/layouts/classical.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-accordion-template-css', plugins_url( 'css/layouts/accordion.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-nicy-template-css', plugins_url( 'css/layouts/nicy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-lightbreeze-template-css', plugins_url( 'css/layouts/lightbreeze.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-sharpen-template-css', plugins_url( 'css/layouts/sharpen.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-spektrum-template-css', plugins_url( 'css/layouts/spektrum.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-hub-template-css', plugins_url( 'css/layouts/hub.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-evolution-template-css', plugins_url( 'css/layouts/evolution.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-offer_blog-template-css', plugins_url( 'css/layouts/offer_blog.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-news-template-css', plugins_url( 'css/layouts/news.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-winter-template-css', plugins_url( 'css/layouts/winter.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-region-template-css', plugins_url( 'css/layouts/region.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-roctangle-template-css', plugins_url( 'css/layouts/roctangle.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-glossary-template-css', plugins_url( 'css/layouts/glossary.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-quci-template-css', plugins_url( 'css/layouts/quci.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-leest-template-css', plugins_url( 'css/layouts/leest.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-pedal-template-css', plugins_url( 'css/layouts/pedal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-deport-template-css', plugins_url( 'css/layouts/deport.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-navia-template-css', plugins_url( 'css/layouts/navia.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-boxy-template-css', plugins_url( 'css/layouts/boxy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-boxy-clean-template-css', plugins_url( 'css/layouts/boxy-clean.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-invert-grid-template-css', plugins_url( 'css/layouts/invert-grid.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-brit_co-template-css', plugins_url( 'css/layouts/brit_co.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-media-grid-template-css', plugins_url( 'css/layouts/media-grid.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-timeline-template-css', plugins_url( 'css/layouts/timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-tabbed-template-css', plugins_url( 'css/layouts/tabbed.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-cool_horizontal-template-css', plugins_url( 'css/layouts/cool_horizontal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-overlay_horizontal-template-css', plugins_url( 'css/layouts/overlay_horizontal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-easy_timeline-template-css', plugins_url( 'css/layouts/easy_timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-story-template-css', plugins_url( 'css/layouts/story.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-logbook-css', plugins_url( 'css/logbook.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-basic-tools', BLOGDESIGNERPRO_URL . '/admin/css/basic-tools-min.css', null, '1.0' );
			wp_register_style( 'bdp-front-css', plugins_url( 'css/front.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-jquery-ui-css', plugins_url( 'css/jquery-ui-custom.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-front-rtl-css', plugins_url( 'css/front-rtl.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-explore-template-css', plugins_url( 'css/layouts/explore.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-hoverbic-template-css', plugins_url( 'css/layouts/hoverbic.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-my_diary-template-css', plugins_url( 'css/layouts/my_diary.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-elina-template-css', plugins_url( 'css/layouts/elina.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-masonry_timeline-template-css', plugins_url( 'css/layouts/masonry_timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-crayon_slider-template-css', plugins_url( 'css/layouts/crayon_slider.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-sallet_slider-template-css', plugins_url( 'css/layouts/sallet_slider.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-colorful_sliding-template-css', plugins_url( 'css/layouts/colorful_sliding.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-sunshiny_slider-template-css', plugins_url( 'css/layouts/sunshiny_slider.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-pretty-template-css', plugins_url( 'css/layouts/pretty.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-tagly-template-css', plugins_url( 'css/layouts/tagly.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-brite-template-css', plugins_url( 'css/layouts/brite.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-chapter-template-css', plugins_url( 'css/layouts/chapter.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-glamour-template-css', plugins_url( 'css/layouts/glamour.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-fairy-template-css', plugins_url( 'css/layouts/fairy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-famous-template-css', plugins_url( 'css/layouts/famous.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-cover-template-css', plugins_url( 'css/layouts/cover.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-steps-template-css', plugins_url( 'css/layouts/steps.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-clicky-template-css', plugins_url( 'css/layouts/clicky.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-minimal-template-css', plugins_url( 'css/layouts/minimal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-miracle-template-css', plugins_url( 'css/layouts/miracle.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-foodbox-template-css', plugins_url( 'css/layouts/foodbox.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-neaty_block-template-css', plugins_url( 'css/layouts/neaty_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-wise_block-template-css', plugins_url( 'css/layouts/wise_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-soft_block-template-css', plugins_url( 'css/layouts/soft_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-schedule-template-css', plugins_url( 'css/layouts/schedule.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-ticker-template-css', plugins_url( 'css/layouts/ticker.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-blog_grid_box-template-css', plugins_url( 'css/layouts/blog_grid_box.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-blog_carousel-template-css', plugins_url( 'css/layouts/blog_carousel.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-threed_carousel-template-css', plugins_url( 'css/layouts/threed_carousel.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-flip_book_3d-template-css', plugins_url( 'css/layouts/flip_book_3d.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-banner-template-css', plugins_url( 'css/layouts/banner.css', __FILE__ ), null, '1.0' );

			// Register Single Layouts css files.
			wp_register_style( 'bdp-single-boxy-clean-template-css', plugins_url( 'css/single/boxy-clean.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-boxy-template-css', plugins_url( 'css/single/boxy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-pedal-template-css', plugins_url( 'css/single/pedal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-quci-template-css', plugins_url( 'css/single/quci.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-leest-template-css', plugins_url( 'css/layouts/leest.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-winter-template-css', plugins_url( 'css/single/winter.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-classical-template-css', plugins_url( 'css/single/classical.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-nicy-template-css', plugins_url( 'css/single/nicy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-sharpen-template-css', plugins_url( 'css/single/sharpen.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-hub-template-css', plugins_url( 'css/single/hub.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-lightbreeze-template-css', plugins_url( 'css/single/lightbreeze.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-spektrum-template-css', plugins_url( 'css/single/spektrum.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-evolution-template-css', plugins_url( 'css/single/evolution.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-news-template-css', plugins_url( 'css/single/news.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-media-grid-template-css', plugins_url( 'css/single/media-grid.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-deport-template-css', plugins_url( 'css/single/deport.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-navia-template-css', plugins_url( 'css/single/navia.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-region-template-css', plugins_url( 'css/single/region.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-roctangle-template-css', plugins_url( 'css/single/roctangle.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-brit_co-template-css', plugins_url( 'css/single/brit_co.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-glossary-template-css', plugins_url( 'css/single/glossary.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-offer_blog-template-css', plugins_url( 'css/single/offer_blog.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-timeline-template-css', plugins_url( 'css/single/timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-invert-grid-template-css', plugins_url( 'css/single/invert-grid.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-story-template-css', plugins_url( 'css/single/story.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-easy_timeline-template-css', plugins_url( 'css/single/easy_timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-cool_horizontal-template-css', plugins_url( 'css/single/cool_horizontal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-overlay_horizontal-template-css', plugins_url( 'css/single/overlay_horizontal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-explore-template-css', plugins_url( 'css/single/explore.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-hoverbic-template-css', plugins_url( 'css/single/hoverbic.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-my_diary-template-css', plugins_url( 'css/single/my_diary.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-elina-template-css', plugins_url( 'css/single/elina.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-masonry_timeline-template-css', plugins_url( 'css/single/masonry_timeline.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-tagly-template-css', plugins_url( 'css/single/tagly.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-brite-template-css', plugins_url( 'css/single/brite.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-chapter-template-css', plugins_url( 'css/single/chapter.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-pretty-template-css', plugins_url( 'css/single/pretty.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-minimal-template-css', plugins_url( 'css/single/minimal.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-glamour-template-css', plugins_url( 'css/single/glamour.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-famous-template-css', plugins_url( 'css/single/famous.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-fairy-template-css', plugins_url( 'css/single/fairy.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-clicky-template-css', plugins_url( 'css/single/clicky.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-cover-template-css', plugins_url( 'css/single/cover.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-steps-template-css', plugins_url( 'css/single/steps.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-miracle-template-css', plugins_url( 'css/single/miracle.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-foodbox-template-css', plugins_url( 'css/single/foodbox.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-neaty_block-template-css', plugins_url( 'css/single/neaty_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-wise_block-template-css', plugins_url( 'css/single/wise_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-soft_block-template-css', plugins_url( 'css/single/soft_block.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-schedule-template-css', plugins_url( 'css/single/schedule.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'bdp-single-banner-template-css', plugins_url( 'css/single/banner.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'choosen-handle-css', BLOGDESIGNERPRO_URL . '/admin/css/chosen.min.css', null, '1.0' );
			wp_register_style( 'single-style-css', plugins_url( 'css/single/single_style.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'single-rtl-style-css', plugins_url( 'css/single/single_rtl_style.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'jquery_top_css', plugins_url( 'css/jquery-top.css', __FILE__ ), null, '1.0' );
		}
	}

	/**
	 * Front Script
	 *
	 * @return void
	 */
	public function bdp_front_script() {

		$page_edit_id   = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : 0;
		$elementor_page = ! empty( $page_edit_id ) ? get_post_meta( $page_edit_id, '_elementor_edit_mode', true ) : '';
		if ( ! is_admin() || ! empty( $elementor_page ) ) {

			wp_enqueue_script( 'jquery-masonry', array( 'jquery' ) );
			wp_enqueue_script( 'bdp-imagesloaded', plugins_url( 'js/imagesloaded.pkgd.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'bdp_isotope_script', plugins_url( 'js/isotope.pkgd.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
			wp_register_script( 'bdp-galleryimage-script', plugins_url( 'js/jquery.flexslider-min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
			wp_register_script( 'bdp-mousewheel-script', plugins_url( 'js/jquery.mousewheel.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			wp_register_script( 'bdp-logbook-script', plugins_url( 'js/logbook.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			wp_register_script( 'bdp-easing-script', plugins_url( 'js/jquery.easing.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			wp_register_script( 'choosen-handle-script', BLOGDESIGNERPRO_URL . '/admin/js/chosen.jquery.js', array( 'jquery', 'jquery-masonry' ), '1.0', false );
			wp_enqueue_script( 'bdp-ticker', plugins_url( 'js/ticker.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			$ajax_url = plugins_url( 'js/ajax.js', __FILE__ );
			wp_register_script( 'bdp-ajax-script', $ajax_url, array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion' ), '1.0', false );
			wp_localize_script(
				'bdp-ajax-script',
				'ajax_object',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'like'          => esc_html__( 'Like', 'blog-designer-pro' ),
					'no_post_found' => esc_html__( 'No Post Found', 'blog-designer-pro' ),
					'unlike'        => esc_html__( 'Unlike', 'blog-designer-pro' ),
					'is_rtl'        => ( is_rtl() ) ? 1 : 0,
					'ajax_nonce'    => wp_create_nonce( 'ajax-nonce' ),
				)
			);
			$social_share_url = plugins_url( 'js/SocialShare.js', __FILE__ );
			wp_register_script( 'bdp-socialShare-script', $social_share_url, null, '1.0', false );
			wp_enqueue_script( 'lazysize_load', plugins_url( 'js/lazysizes.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'slick_admin', plugins_url( 'js/slick.min.js', __FILE__ ), array( 'jquery' ), '1.4.1', true );
			wp_enqueue_script( 'Carousel_js', plugins_url( 'js/Carousel.js', __FILE__ ), array( 'jquery' ), '1.0', true );
			// wp_enqueue_script('jquery_js', plugins_url('js/jquery.js', __FILE__), array('jquery'), '1.11.3', false);.
			wp_enqueue_script( 'bdp-modernize-custom-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array( 'jquery' ), '2.6.2', true );

			wp_enqueue_script( 'bookblock-script', plugins_url( 'js/jquery.bookblock.js', __FILE__ ), array( 'jquery' ), '2.0.1', true );

			wp_enqueue_script( 'bdp-jspdf', plugins_url( 'js/jspdf.min.js', __FILE__ ), array( 'jquery' ), '1.5.3', true );
		}
		wp_enqueue_script( 'bdp-modernize-custom-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array( 'jquery' ), '2.6.2', true );

		wp_enqueue_script( 'bookblock-script', plugins_url( 'js/jquery.bookblock.js', __FILE__ ), array( 'jquery' ), '2.0.1', true );
	}




	/**
	 * Add template style
	 *
	 * @return void
	 */
	public function bdp_add_template_style() {
		global $post, $wpdb;
		$bdp_themes                = self::$template_name;
		$template_stylesheet_added = self::$template_stylesheet_added;
		$current_id                = 0;
		$current_page              = 'shortcode';
		$bdp_theme_array           = array();
		if ( 0 == $template_stylesheet_added ) {
			if ( class_exists( 'woocommerce' ) && ( is_product_category() || is_product_tag() ) ) {
				$archive_list = Bdp_Woocommerce::get_product_archive_list();
			} elseif ( is_tax( 'download_category' ) || is_tax( 'download_tag' ) ) {
				$archive_list = Bdp_Edd::get_download_archive_list();
			} else {
				$archive_list = Bdp_Template::get_archive_list();
			}

			if ( is_array( $bdp_themes ) && count( $bdp_themes ) > 0 ) {
				foreach ( $bdp_themes as $bdp_theme ) {
					$bdp_theme_array[] = $bdp_theme;
				}
			} elseif ( is_archive() || ( class_exists( 'woocommerce' ) && ( is_product_category() || is_product_tag() ) ) || ( is_tax( 'download_category' ) || is_tax( 'download_tag' ) ) ) {

				$current_page = 'archive';
				if ( is_date() && in_array( 'date_template', $archive_list ) ) {
					$date         = Bdp_Template::get_date_template_settings();
					$all_setting  = $date->settings;
					$current_page = 'date';
					if ( is_serialized( $all_setting ) ) {
						$bdp_settings = maybe_unserialize( $all_setting );
						if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
							$bdp_theme_array[] = $bdp_settings['template_name'];
						}
					}
				} elseif ( is_author() && in_array( 'author_template', $archive_list ) ) {
					$author_id       = get_query_var( 'author' );
					$bdp_author_data = Bdp_Author::get_author_template_settings( $author_id, $archive_list );
					$current_page    = 'author';
					if ( $bdp_author_data ) {
						$archive_id   = $bdp_author_data['id'];
						$bdp_settings = $bdp_author_data['bdp_settings'];
						if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
							$bdp_theme_array[] = $bdp_settings['template_name'];
						}
					}
				} elseif ( ( is_category() || is_tax( 'download_category' ) || ( class_exists( 'woocommerce' ) && is_product_category() ) ) && in_array( 'category_template', $archive_list ) ) {
					if ( class_exists( 'woocommerce' ) && is_product_category() ) {
						$product_archive_list = Bdp_Woocommerce::get_product_archive_list();
						$categories           = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						$category_id          = $categories->term_id;
						$current_id           = $category_id;
						$current_page         = 'category';
						$bdp_category_data    = Bdp_Template::get_product_category_template_settings( $category_id, $product_archive_list );
						if ( is_array( $bdp_category_data ) && ! empty( $bdp_category_data ) ) {
							$archive_id   = $bdp_category_data['id'];
							$bdp_settings = $bdp_category_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					} elseif ( is_tax( 'download_category' ) ) {
						$product_archive_list = Bdp_Edd::get_download_archive_list();
						$categories           = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						$category_id          = $categories->term_id;
						$current_id           = $category_id;
						$current_page         = 'category';
						$bdp_category_data    = Bdp_Edd::get_download_category_template_settings( $category_id, $product_archive_list );
						if ( is_array( $bdp_category_data ) && ! empty( $bdp_category_data ) ) {
							$archive_id   = $bdp_category_data['id'];
							$bdp_settings = $bdp_category_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					} elseif ( is_category() ) {
						$categories        = get_category( get_query_var( 'cat' ) );
						$category_id       = $categories->cat_ID;
						$current_id        = $category_id;
						$current_page      = 'category';
						$bdp_category_data = Bdp_Template::get_category_template_settings( $category_id, $archive_list );
						if ( is_array( $bdp_category_data ) && ! empty( $bdp_category_data ) ) {
							$archive_id   = $bdp_category_data['id'];
							$bdp_settings = $bdp_category_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					}
				} elseif ( ( is_tag() || is_tax( 'download_tag' ) || ( class_exists( 'woocommerce' ) && is_product_tag() ) ) && in_array( 'tag_template', $archive_list ) ) {
					if ( class_exists( 'woocommerce' ) && is_product_tag() ) {
						$product_archive_list = Bdp_Woocommerce::get_product_archive_list();
						$tags                 = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						$tag_id               = $tags->term_id;
						$current_id           = $tag_id;
						$current_page         = 'tag';
						$bdp_category_data    = Bdp_Template::get_product_tag_template_settings( $tag_id, $product_archive_list );
						if ( is_array( $bdp_category_data ) && ! empty( $bdp_category_data ) ) {
							$archive_id   = $bdp_category_data['id'];
							$bdp_settings = $bdp_category_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					} elseif ( is_tax( 'download_tag' ) ) {
						$product_archive_list = Bdp_Edd::get_download_archive_list();
						$tags                 = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						$tag_id               = $tags->term_id;
						$current_id           = $tag_id;
						$current_page         = 'tag';
						$bdp_category_data    = Bdp_Template::get_download_tag_template_settings( $tag_id, $product_archive_list );
						if ( is_array( $bdp_category_data ) && ! empty( $bdp_category_data ) ) {
							$archive_id   = $bdp_category_data['id'];
							$bdp_settings = $bdp_category_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					} elseif ( is_tag() ) {
						$tag_id       = get_query_var( 'tag_id' );
						$current_id   = $tag_id;
						$current_page = 'tag';
						$bdp_tag_data = Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
						if ( is_array( $bdp_tag_data ) && ! empty( $bdp_tag_data ) ) {
							$archive_id   = $bdp_tag_data['id'];
							$bdp_settings = $bdp_tag_data['bdp_settings'];
							$bdp_settings = maybe_unserialize( $bdp_settings );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								$bdp_theme_array[] = $bdp_settings['template_name'];
							}
						}
					}
				}
			} elseif ( is_search() && in_array( 'search_template', $archive_list ) ) {
				$search_settings = Bdp_Template::get_search_template_settings();
				$current_page    = 'search';
				$allsettings     = $search_settings->settings;
				$bdp_settings    = maybe_unserialize( $allsettings );
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					$bdp_theme_array[] = $bdp_settings['template_name'];
				}
			} elseif ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'wp_blog_designer' ) ) {
				$pattern = get_shortcode_regex();
				if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
					foreach ( $matches[3] as $block ) {
						$attr = shortcode_parse_atts( $block );
						if ( isset( $attr['id'] ) ) {
							$shortcode_id = $attr['id'];
							if ( '' != $shortcode_id ) {
								$bdp_settings = Bdp_Template::get_shortcode_settings( $shortcode_id );
								$bdp_settings = maybe_unserialize( $bdp_settings );
								if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
									$bdp_theme_array[] = $bdp_settings['template_name'];
								}
							}
						}
					}
				}
			} elseif ( is_single() ) {
				self::$template_stylesheet_added = 1;

				if ( isset( $post ) ) {
					$post_id      = $post->ID;
					$current_id   = $post_id;
					$current_page = 'single';
					if ( ( Bdp_Woocommerce::is_woocommerce_plugin() || class_exists( 'woocommerce' ) ) && is_product() ) {
						$cat_ids      = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
						$tag_ids      = wp_get_post_terms( $post_id, 'product_tag', array( 'fields' => 'ids' ) );
						$bdp_settings = Bdp_Template::get_single_prodcut_template_settings( $cat_ids, $tag_ids );
						$bdp_settings = maybe_unserialize( $bdp_settings );
						if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
							$bdp_theme_array[] = $bdp_settings['template_name'];
						}
					} elseif ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) && 'download' === $post->post_type ) {
						$cat_ids      = wp_get_post_terms( $post_id, 'download_category', array( 'fields' => 'ids' ) );
						$tag_ids      = wp_get_post_terms( $post_id, 'download_tag', array( 'fields' => 'ids' ) );
						$bdp_settings = Bdp_Template::get_single_download_template_settings( $cat_ids, $tag_ids );
						$bdp_settings = maybe_unserialize( $bdp_settings );
						if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
							$bdp_theme_array[] = $bdp_settings['template_name'];
						}
					} else {
						$cat_ids      = wp_get_post_categories( $post_id );
						$tag_ids      = wp_get_post_tags( $post_id );
						$bdp_settings = Bdp_Template::get_single_template_settings( $cat_ids, $tag_ids );
						$bdp_settings = maybe_unserialize( $bdp_settings );
						if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
							$bdp_theme_array[] = $bdp_settings['template_name'];
						}
					}
				}
			}

			if ( isset( $bdp_theme_array ) && ! empty( $bdp_theme_array ) ) {
				self::$template_stylesheet_added = 1;
				foreach ( $bdp_theme_array as $bdp_theme ) {
					$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
					if ( 1 == $bdp_template_name_changed ) {
						$bdp_theme = Bdp_Utility::from_lite_to_pro( $bdp_theme );
					} else {
						update_option( 'bdp_template_name_changed', 0 );
					}
					if ( ( is_archive() || class_exists( 'woocommerce' ) && ( is_product_category() || is_product_tag() ) ) || ( is_tax( 'download_category' ) || is_tax( 'download_tag' ) ) ) {
						$style_name = 'bdp-' . $bdp_theme . '-template-css';
					} elseif ( is_single() && ( isset( $bdp_settings['originalpage'] ) && 'add_shortcode' !== $bdp_settings['originalpage'] ) ) {
						if ( isset( $_GET['template'] ) && '' != $_GET['template'] ) {
							$bdp_theme = sanitize_text_field( wp_unslash( $_GET['template'] ) );
						}
						$style_name = 'bdp-single-' . $bdp_theme . '-template-css';
						wp_enqueue_style( 'single-style-css' );
						if ( is_rtl() ) {
							wp_enqueue_style( 'single-rtl-style-css' );
						}
					} else {
						$style_name = 'bdp-' . $bdp_theme . '-template-css';
					}
					wp_enqueue_style( $style_name );
					$page_edit_id   = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : 0;
					$elementor_page = ! empty( $page_edit_id ) ? get_post_meta( $page_edit_id, '_elementor_edit_mode', true ) : '';
					if ( ! empty( $elementor_page ) ) {
						echo '<link rel="stylesheet" id="bdp-template-main-css" type="text/css" href="' . esc_url( plugins_url( 'css/front.css', __FILE__ ) ) . '">';
						echo '<link rel="stylesheet" id="bdp-template-' . esc_attr( $bdp_theme ) . '-css" type="text/css" href="' . esc_url( plugins_url( 'css/layouts/' . $bdp_theme . '.css', __FILE__ ) ) . '">';
						if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
							echo '<link rel="stylesheet" id="bdp-logbook-css-css" type="text/css" href="' . esc_url( plugins_url( 'css/logbook.css', __FILE__ ) ) . '">';
						}
					}
					add_action( 'wp_footer', array( &$this, 'bdp_email_share' ), 30 );
					if ( ! wp_style_is( 'bdp-fontawesome-stylesheets' ) ) {
						$bdp_disable_fa_css = get_option( 'bdp_disable_fa_css', 0 );
						if ( 0 == $bdp_disable_fa_css ) {
							wp_enqueue_style( 'bdp-fontawesome-stylesheets' );
						}
					}
					if ( ! wp_script_is( 'bdp-ajax-script', $list = 'enqueued' ) ) {
						wp_enqueue_script( 'bdp-ajax-script' );
					}
					if ( ! wp_style_is( 'bdp-galleryslider-stylesheets' ) ) {
						wp_enqueue_style( 'bdp-galleryslider-stylesheets' );
						echo '<link rel="stylesheet" id="bdp-template-main-css" type="text/css" href="' . esc_url( BLOGDESIGNERPRO_URL ) . '/public/css/flexslider.css">';

					}
					if ( ! wp_script_is( 'bdp-galleryimage-script', $list = 'enqueued' ) ) {
						wp_enqueue_script( 'bdp-galleryimage-script' );
					}
					if ( 'accordion' === $bdp_theme ) {
						wp_enqueue_style( 'bdp-jquery-ui-css' );
					}
					if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
						wp_enqueue_script( 'bdp-mousewheel-script' );
						wp_enqueue_script( 'bdp-logbook-script' );
						wp_enqueue_script( 'bdp-easing-script' );
						wp_enqueue_style( 'bdp-logbook-css' );
						add_action( 'wp_footer', array( &$this, 'bdp_template_dynamic_script' ), 30 );
					}
					if ( 'crayon_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'blog_carousel' === $bdp_theme || 'threed_carousel' === $bdp_theme || 'flip_book_3d' === $bdp_theme ) {
						add_action( 'wp_footer', array( &$this, 'bdp_template_dynamic_script' ), 30 );
					}
					if ( ! wp_style_is( 'bdp-basic-tools' ) ) {
						wp_enqueue_style( 'bdp-basic-tools' );
					}
					if ( ! wp_style_is( 'bdp-front-css' ) ) {
						wp_enqueue_style( 'bdp-front-css' );
					}
					if ( is_rtl() ) {
						if ( ! wp_style_is( 'bdp-front-rtl-css' ) ) {
							wp_enqueue_style( 'bdp-front-rtl-css' );
						}
					}
				}
			}
		}
		// It will be used in social share via email.
		wp_localize_script(
			'bdp-ajax-script',
			'page_object',
			array(
				'current_page' => $current_page,
				'current_id'   => $current_id,
			)
		);
	}
	/**
	 * Template Dynamic CSS
	 *
	 * @return void
	 */
	public function bdp_template_dynamic_css() {
		global $post, $wpdb;
		$shortcode_id_array                = array();
		$bdp_settings_array                = array();
		$template_dynamic_stylesheet_added = self::$template_dynamic_stylesheet_added;
		if ( 0 == $template_dynamic_stylesheet_added ) {
			if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'wp_blog_designer' ) ) {
				$shortcode_id = '';
				$pattern      = self::shortcode_regex();
				if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
					foreach ( $matches[3] as $block ) {
						$attr = shortcode_parse_atts( $block );
						if ( isset( $attr['id'] ) ) {
							$shortcode_id = $attr['id'];
						}
						if ( '' != $shortcode_id ) {
							$shortcode_id_array[]                = $shortcode_id;
							$bdp_settings                        = Bdp_Template::get_shortcode_settings( $shortcode_id );
							$bdp_settings_array[ $shortcode_id ] = $bdp_settings;
						}
					}
				}
			} else {
				$bdp_shortcode_ids = self::$shortcode_id;
				if ( is_array( $bdp_shortcode_ids ) && count( $bdp_shortcode_ids ) > 0 ) {
					foreach ( $bdp_shortcode_ids as $bdp_shortcode_id ) {
						if ( '' != $bdp_shortcode_id ) {
							$shortcode_id_array[]                    = $bdp_shortcode_id;
							$bdp_settings                            = Bdp_Template::get_shortcode_settings( $bdp_shortcode_id );
							$bdp_settings_array[ $bdp_shortcode_id ] = $bdp_settings;
						}
					}
				}
			}
			$archive_list = Bdp_Template::get_archive_list();
			$archive_id   = '';
			if ( is_date() && in_array( 'date_template', $archive_list ) ) {
				$date_settings = Bdp_Template::get_date_template_settings();
				$array         = array_keys( $archive_list, 'date_template' );
				$archive_id    = $array[0];
				$allsettings   = $date_settings->settings;
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings                      = maybe_unserialize( $allsettings );
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
			} elseif ( is_author() && in_array( 'author_template', $archive_list ) ) {
				$author_id                         = get_query_var( 'author' );
				$bdp_author_data                   = Bdp_Author::get_author_template_settings( $author_id, $archive_list );
				$archive_id                        = $bdp_author_data['id'];
				$bdp_settings                      = $bdp_author_data['bdp_settings'];
				$bdp_settings_array[ $archive_id ] = $bdp_settings;
			} elseif ( is_category() && in_array( 'category_template', $archive_list ) ) {
				$categories                        = get_category( get_query_var( 'cat' ) );
				$category_id                       = $categories->cat_ID;
				$bdp_category_data                 = Bdp_Template::get_category_template_settings( $category_id, $archive_list );
				$archive_id                        = $bdp_category_data['id'];
				$bdp_settings                      = $bdp_category_data['bdp_settings'];
				$bdp_settings_array[ $archive_id ] = $bdp_settings;
			} elseif ( is_tag() && in_array( 'tag_template', $archive_list ) ) {
				$tag_id                            = get_query_var( 'tag_id' );
				$bdp_tag_data                      = Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
				$archive_id                        = $bdp_tag_data['id'];
				$bdp_settings                      = $bdp_tag_data['bdp_settings'];
				$bdp_settings_array[ $archive_id ] = $bdp_settings;
			} elseif ( is_search() && in_array( 'search_template', $archive_list ) ) {
				$search_settings = Bdp_Template::get_search_template_settings();
				$array           = array_keys( $archive_list, 'search_template' );
				$archive_id      = $array[0];
				$allsettings     = $search_settings->settings;
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings                      = maybe_unserialize( $allsettings );
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
			}
			/**
			 * Get Woocommerce category and tag page setting Data
			 *
			 * @since 2.6
			 */
			if ( Bdp_Woocommerce::is_woocommerce_plugin() ) {
				$archive_list = Bdp_Woocommerce::get_product_archive_list();
				$archive_id   = '';
				$bdp_settings = array();
				if ( is_product_category() && in_array( 'category_template', $archive_list ) ) {
					$categories                        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id                       = $categories->term_id;
					$bdp_category_data                 = Bdp_Template::get_product_category_template_settings( $category_id, $archive_list );
					$archive_id                        = $bdp_category_data['id'];
					$bdp_settings                      = $bdp_category_data['bdp_settings'];
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
				if ( is_product_tag() && in_array( 'tag_template', $archive_list ) ) {
					$tags                              = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$tag_id                            = $tags->term_id;
					$bdp_category_data                 = Bdp_Template::get_product_tag_template_settings( $tag_id, $archive_list );
					$archive_id                        = $bdp_category_data['id'];
					$bdp_settings                      = $bdp_category_data['bdp_settings'];
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
			}
			/**
			 * Get Download category and tag page data
			 *
			 * @since 2.7
			 */
			if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
				$archive_list = Bdp_Edd::get_download_archive_list();
				$archive_id   = '';
				$bdp_settings = array();
				if ( is_tax( 'download_category' ) && in_array( 'category_template', $archive_list ) ) {
					$categories                        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id                       = $categories->term_id;
					$bdp_category_data                 = Bdp_Edd::get_download_category_template_settings( $category_id, $archive_list );
					$archive_id                        = $bdp_category_data['id'];
					$bdp_settings                      = $bdp_category_data['bdp_settings'];
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
				if ( is_tax( 'download_tag' ) && in_array( 'tag_template', $archive_list ) ) {
					$categories                        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id                       = $categories->term_id;
					$bdp_category_data                 = Bdp_Template::get_download_tag_template_settings( $category_id, $archive_list );
					$archive_id                        = $bdp_category_data['id'];
					$bdp_settings                      = $bdp_category_data['bdp_settings'];
					$bdp_settings_array[ $archive_id ] = $bdp_settings;
				}
			}
			if ( isset( $bdp_settings_array ) && is_array( $bdp_settings_array ) && ! empty( $bdp_settings_array ) ) {
				self::$template_dynamic_stylesheet_added = 1;
				foreach ( $bdp_settings_array as $bd_shortcode_id => $bdp_settings ) {
					$shortcode_id           = $bd_shortcode_id;
					$bdp_theme              = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : '';
					$bdp_theme              = apply_filters( 'bdp_filter_template', $bdp_theme );
					$template_titlefontface = ( isset( $bdp_settings['template_titlefontface'] ) && '' != $bdp_settings['template_titlefontface'] ) ? $bdp_settings['template_titlefontface'] : '';
					$load_goog_font_blog    = array();
					if ( isset( $bdp_settings['template_titlefontface_font_type'] ) && 'Google Fonts' === $bdp_settings['template_titlefontface_font_type'] ) {
						$load_goog_font_blog[] = $template_titlefontface;
					}
					$column_setting                              = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
					$background                                  = ( isset( $bdp_settings['template_bgcolor'] ) && '' != $bdp_settings['template_bgcolor'] ) ? $bdp_settings['template_bgcolor'] : '';
					$background_wrap                             = ( isset( $bdp_settings['template_bgcolor_wrap'] ) && '' != $bdp_settings['template_bgcolor_wrap'] ) ? $bdp_settings['template_bgcolor_wrap'] : '';
					$background1                                 = ( isset( $bdp_settings['template_bgcolor1'] ) && '' != $bdp_settings['template_bgcolor1'] ) ? $bdp_settings['template_bgcolor1'] : '';
					$background2                                 = ( isset( $bdp_settings['template_bgcolor2'] ) && '' != $bdp_settings['template_bgcolor2'] ) ? $bdp_settings['template_bgcolor2'] : '';
					$background3                                 = ( isset( $bdp_settings['template_bgcolor3'] ) && '' != $bdp_settings['template_bgcolor3'] ) ? $bdp_settings['template_bgcolor3'] : '';
					$background4                                 = ( isset( $bdp_settings['template_bgcolor4'] ) && '' != $bdp_settings['template_bgcolor4'] ) ? $bdp_settings['template_bgcolor4'] : '';
					$background5                                 = ( isset( $bdp_settings['template_bgcolor5'] ) && '' != $bdp_settings['template_bgcolor5'] ) ? $bdp_settings['template_bgcolor5'] : '';
					$background6                                 = ( isset( $bdp_settings['template_bgcolor6'] ) && '' != $bdp_settings['template_bgcolor6'] ) ? $bdp_settings['template_bgcolor6'] : '';
					$template_bghovercolor                       = ( isset( $bdp_settings['template_bghovercolor'] ) && '' != $bdp_settings['template_bghovercolor'] ) ? $bdp_settings['template_bghovercolor'] : '';
					$templatecolor                               = ( isset( $bdp_settings['template_color'] ) && '' != $bdp_settings['template_color'] ) ? $bdp_settings['template_color'] : '';
					$templatecolor1                              = ( isset( $bdp_settings['template_color1'] ) && '' != $bdp_settings['template_color1'] ) ? $bdp_settings['template_color1'] : '';
					$templatecolor2                              = ( isset( $bdp_settings['template_color2'] ) && '' != $bdp_settings['template_color2'] ) ? $bdp_settings['template_color2'] : '';
					$templatecolor3                              = ( isset( $bdp_settings['template_color3'] ) && '' != $bdp_settings['template_color3'] ) ? $bdp_settings['template_color3'] : '';
					$templatecolor4                              = ( isset( $bdp_settings['template_color4'] ) && '' != $bdp_settings['template_color4'] ) ? $bdp_settings['template_color4'] : '';
					$templatecolor5                              = ( isset( $bdp_settings['template_color5'] ) && '' != $bdp_settings['template_color5'] ) ? $bdp_settings['template_color5'] : '';
					$templatecolor6                              = ( isset( $bdp_settings['template_color6'] ) && '' != $bdp_settings['template_color6'] ) ? $bdp_settings['template_color6'] : '';
					$displaydate_backcolor                       = ( isset( $bdp_settings['displaydate_backcolor'] ) && '' != $bdp_settings['displaydate_backcolor'] ) ? $bdp_settings['displaydate_backcolor'] : '';
					$color                                       = ( isset( $bdp_settings['template_ftcolor'] ) && '' != $bdp_settings['template_ftcolor'] ) ? $bdp_settings['template_ftcolor'] : '';
					$grid_hoverback_color                        = ( isset( $bdp_settings['grid_hoverback_color'] ) && '' != $bdp_settings['grid_hoverback_color'] ) ? $bdp_settings['grid_hoverback_color'] : '';
					$linkhovercolor                              = ( isset( $bdp_settings['template_fthovercolor'] ) && '' != $bdp_settings['template_fthovercolor'] ) ? $bdp_settings['template_fthovercolor'] : '';
					$loader_color                                = ( isset( $bdp_settings['loader_color'] ) && '' != $bdp_settings['loader_color'] ) ? $bdp_settings['loader_color'] : 'inherit';
					$pagination_type                             = ( isset( $bdp_settings['pagination_type'] ) && '' != $bdp_settings['pagination_type'] ) ? $bdp_settings['pagination_type'] : 'no_pagination';
					$loadmore_button_color                       = ( isset( $bdp_settings['loadmore_button_color'] ) && '' != $bdp_settings['loadmore_button_color'] ) ? $bdp_settings['loadmore_button_color'] : '#ffffff';
					$loadmore_button_bg_color                    = ( isset( $bdp_settings['loadmore_button_bg_color'] ) && '' != $bdp_settings['loadmore_button_bg_color'] ) ? $bdp_settings['loadmore_button_bg_color'] : '#444444';
					$title_alignment                             = ( isset( $bdp_settings['template_title_alignment'] ) && '' != $bdp_settings['template_title_alignment'] ) ? $bdp_settings['template_title_alignment'] : '';
					$titlecolor                                  = ( isset( $bdp_settings['template_titlecolor'] ) && '' != $bdp_settings['template_titlecolor'] ) ? $bdp_settings['template_titlecolor'] : '';
					$titlehovercolor                             = ( isset( $bdp_settings['template_titlehovercolor'] ) && '' != $bdp_settings['template_titlehovercolor'] ) ? $bdp_settings['template_titlehovercolor'] : '';
					$contentcolor                                = ( isset( $bdp_settings['template_contentcolor'] ) && '' != $bdp_settings['template_contentcolor'] ) ? $bdp_settings['template_contentcolor'] : '';
					$readmorecolor                               = ( isset( $bdp_settings['template_readmorecolor'] ) && '' != $bdp_settings['template_readmorecolor'] ) ? $bdp_settings['template_readmorecolor'] : '';
					$readmorehovercolor                          = ( isset( $bdp_settings['template_readmorehovercolor'] ) && '' != $bdp_settings['template_readmorehovercolor'] ) ? $bdp_settings['template_readmorehovercolor'] : '';
					$readmorebackcolor                           = ( isset( $bdp_settings['template_readmorebackcolor'] ) && '' != $bdp_settings['template_readmorebackcolor'] ) ? $bdp_settings['template_readmorebackcolor'] : '';
					$readmorebutton_on                           = ( isset( $bdp_settings['read_more_on'] ) && '' != $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
					$read_more_link                              = ( isset( $bdp_settings['read_more_link'] ) && '' != $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
					$bdp_hide_hover_post                         = ( isset( $bdp_settings['bdp_hide_hover_post'] ) && '' != $bdp_settings['bdp_hide_hover_post'] ) ? $bdp_settings['bdp_hide_hover_post'] : 1;
					$readmorehoverbackcolor                      = ( isset( $bdp_settings['template_readmore_hover_backcolor'] ) && '' != $bdp_settings['template_readmore_hover_backcolor'] ) ? $bdp_settings['template_readmore_hover_backcolor'] : '';
					$readmorebuttonborderradius                  = ( isset( $bdp_settings['readmore_button_border_radius'] ) && '' != $bdp_settings['readmore_button_border_radius'] ) ? $bdp_settings['readmore_button_border_radius'] : '0';
					$readmorebuttonalignment                     = ( isset( $bdp_settings['readmore_button_alignment'] ) && '' != $bdp_settings['readmore_button_alignment'] ) ? $bdp_settings['readmore_button_alignment'] : '';
					$readmore_button_paddingleft                 = ( isset( $bdp_settings['readmore_button_paddingleft'] ) && '' != $bdp_settings['readmore_button_paddingleft'] ) ? $bdp_settings['readmore_button_paddingleft'] : '10';
					$readmore_button_paddingright                = ( isset( $bdp_settings['readmore_button_paddingright'] ) && '' != $bdp_settings['readmore_button_paddingright'] ) ? $bdp_settings['readmore_button_paddingright'] : '10';
					$readmore_button_paddingtop                  = ( isset( $bdp_settings['readmore_button_paddingtop'] ) && '' != $bdp_settings['readmore_button_paddingtop'] ) ? $bdp_settings['readmore_button_paddingtop'] : '10';
					$readmore_button_paddingbottom               = ( isset( $bdp_settings['readmore_button_paddingbottom'] ) && '' != $bdp_settings['readmore_button_paddingbottom'] ) ? $bdp_settings['readmore_button_paddingbottom'] : '10';
					$readmore_button_marginleft                  = ( isset( $bdp_settings['readmore_button_marginleft'] ) && '' != $bdp_settings['readmore_button_marginleft'] ) ? $bdp_settings['readmore_button_marginleft'] : '';
					$readmore_button_marginright                 = ( isset( $bdp_settings['readmore_button_marginright'] ) && '' != $bdp_settings['readmore_button_marginright'] ) ? $bdp_settings['readmore_button_marginright'] : '';
					$readmore_button_margintop                   = ( isset( $bdp_settings['readmore_button_margintop'] ) && '' != $bdp_settings['readmore_button_margintop'] ) ? $bdp_settings['readmore_button_margintop'] : '';
					$readmore_button_marginbottom                = ( isset( $bdp_settings['readmore_button_marginbottom'] ) && '' != $bdp_settings['readmore_button_marginbottom'] ) ? $bdp_settings['readmore_button_marginbottom'] : '';
					$read_more_button_border_style               = ( isset( $bdp_settings['read_more_button_border_style'] ) && '' != $bdp_settings['read_more_button_border_style'] ) ? $bdp_settings['read_more_button_border_style'] : '';
					$bdp_readmore_button_borderleft              = ( isset( $bdp_settings['bdp_readmore_button_borderleft'] ) && '' != $bdp_settings['bdp_readmore_button_borderleft'] ) ? $bdp_settings['bdp_readmore_button_borderleft'] : '0';
					$bdp_readmore_button_borderright             = ( isset( $bdp_settings['bdp_readmore_button_borderright'] ) && '' != $bdp_settings['bdp_readmore_button_borderright'] ) ? $bdp_settings['bdp_readmore_button_borderright'] : '0';
					$bdp_readmore_button_bordertop               = ( isset( $bdp_settings['bdp_readmore_button_bordertop'] ) && '' != $bdp_settings['bdp_readmore_button_bordertop'] ) ? $bdp_settings['bdp_readmore_button_bordertop'] : '0';
					$readmore_button_hover_border_radius         = ( isset( $bdp_settings['readmore_button_hover_border_radius'] ) && '' != $bdp_settings['readmore_button_hover_border_radius'] ) ? $bdp_settings['readmore_button_hover_border_radius'] : '0';
					$read_more_button_hover_border_style         = ( isset( $bdp_settings['read_more_button_hover_border_style'] ) && '' != $bdp_settings['read_more_button_hover_border_style'] ) ? $bdp_settings['read_more_button_hover_border_style'] : '';
					$bdp_readmore_button_hover_borderleft        = ( isset( $bdp_settings['bdp_readmore_button_hover_borderleft'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderleft'] ) ? $bdp_settings['bdp_readmore_button_hover_borderleft'] : '0';
					$bdp_readmore_button_hover_borderright       = ( isset( $bdp_settings['bdp_readmore_button_hover_borderright'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderright'] ) ? $bdp_settings['bdp_readmore_button_hover_borderright'] : '0';
					$bdp_readmore_button_hover_bordertop         = ( isset( $bdp_settings['bdp_readmore_button_hover_bordertop'] ) && '' != $bdp_settings['bdp_readmore_button_hover_bordertop'] ) ? $bdp_settings['bdp_readmore_button_hover_bordertop'] : '0';
					$bdp_readmore_button_hover_borderbottom      = ( isset( $bdp_settings['bdp_readmore_button_hover_borderbottom'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderbottom'] ) ? $bdp_settings['bdp_readmore_button_hover_borderbottom'] : '0';
					$bdp_readmore_button_hover_borderleftcolor   = ( isset( $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] : '';
					$bdp_readmore_button_hover_borderrightcolor  = ( isset( $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] : '';
					$bdp_readmore_button_hover_bordertopcolor    = ( isset( $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] ) && '' != $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] : '';
					$bdp_readmore_button_hover_borderbottomcolor = ( isset( $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] ) && '' != $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] : '';
					$bdp_readmore_button_borderbottom            = ( isset( $bdp_settings['bdp_readmore_button_borderbottom'] ) && '' != $bdp_settings['bdp_readmore_button_borderbottom'] ) ? $bdp_settings['bdp_readmore_button_borderbottom'] : '';
					$bdp_readmore_button_borderleftcolor         = ( isset( $bdp_settings['bdp_readmore_button_borderleftcolor'] ) && '' != $bdp_settings['bdp_readmore_button_borderleftcolor'] ) ? $bdp_settings['bdp_readmore_button_borderleftcolor'] : '';
					$bdp_readmore_button_borderrightcolor        = ( isset( $bdp_settings['bdp_readmore_button_borderrightcolor'] ) && '' != $bdp_settings['bdp_readmore_button_borderrightcolor'] ) ? $bdp_settings['bdp_readmore_button_borderrightcolor'] : '';
					$bdp_readmore_button_bordertopcolor          = ( isset( $bdp_settings['bdp_readmore_button_bordertopcolor'] ) && '' != $bdp_settings['bdp_readmore_button_bordertopcolor'] ) ? $bdp_settings['bdp_readmore_button_bordertopcolor'] : '';
					$bdp_readmore_button_borderbottomcolor       = ( isset( $bdp_settings['bdp_readmore_button_borderbottomcolor'] ) && '' != $bdp_settings['bdp_readmore_button_borderbottomcolor'] ) ? $bdp_settings['bdp_readmore_button_borderbottomcolor'] : '';
					$alterbackground                             = ( isset( $bdp_settings['template_alterbgcolor'] ) && '' != $bdp_settings['template_alterbgcolor'] ) ? $bdp_settings['template_alterbgcolor'] : '';
					$titlebackcolor                              = ( isset( $bdp_settings['template_titlebackcolor'] ) && '' != $bdp_settings['template_titlebackcolor'] ) ? $bdp_settings['template_titlebackcolor'] : '';
					$social_icon_style                           = ( isset( $bdp_settings['social_icon_style'] ) && '' != $bdp_settings['social_icon_style'] ) ? $bdp_settings['social_icon_style'] : 0;
					$social_style                                = ( isset( $bdp_settings['social_style'] ) && '' != $bdp_settings['social_style'] ) ? $bdp_settings['social_style'] : '';
					$firstletter_fontsize                        = ( isset( $bdp_settings['firstletter_fontsize'] ) && '' != $bdp_settings['firstletter_fontsize'] ) ? $bdp_settings['firstletter_fontsize'] : 'inherit';
					$firstletter_font_family                     = ( isset( $bdp_settings['firstletter_font_family'] ) && '' != $bdp_settings['firstletter_font_family'] ) ? $bdp_settings['firstletter_font_family'] : 'inherit';
					if ( isset( $bdp_settings['firstletter_font_family_font_type'] ) && 'Google Fonts' === $bdp_settings['firstletter_font_family_font_type'] ) {
						$load_goog_font_blog[] = $firstletter_font_family;
					}
					$firstletter_contentcolor                  = ( isset( $bdp_settings['firstletter_contentcolor'] ) && '' != $bdp_settings['firstletter_contentcolor'] ) ? $bdp_settings['firstletter_contentcolor'] : 'inherit';
					$firstletter_big                           = ( isset( $bdp_settings['firstletter_big'] ) && '' != $bdp_settings['firstletter_big'] ) ? $bdp_settings['firstletter_big'] : '';
					$template_alternativebackground            = ( isset( $bdp_settings['template_alternativebackground'] ) && '' != $bdp_settings['template_alternativebackground'] ) ? $bdp_settings['template_alternativebackground'] : 0;
					$template_titlefontsize                    = ( isset( $bdp_settings['template_titlefontsize'] ) && '' != $bdp_settings['template_titlefontsize'] ) ? $bdp_settings['template_titlefontsize'] : 'inherit';
					$extra_titlecolor                          = ( isset( $bdp_settings['template_extra_titlecolor'] ) && '' != $bdp_settings['template_extra_titlecolor'] ) ? $bdp_settings['template_extra_titlecolor'] : '';
					$extra_titlehovercolor                     = ( isset( $bdp_settings['template_extra_titlehovercolor'] ) && '' != $bdp_settings['template_extra_titlehovercolor'] ) ? $bdp_settings['template_extra_titlehovercolor'] : '';
					$template_extratitlefontsize               = ( isset( $bdp_settings['template_extratitlefontsize'] ) && '' != $bdp_settings['template_extratitlefontsize'] ) ? $bdp_settings['template_extratitlefontsize'] : 'inherit';
					$template_extra_title_font_weight          = isset( $bdp_settings['template_extra_title_font_weight'] ) ? $bdp_settings['template_extra_title_font_weight'] : '';
					$template_extra_title_font_line_height     = isset( $bdp_settings['template_extra_title_font_line_height'] ) ? $bdp_settings['template_extra_title_font_line_height'] : '';
					$template_extra_title_font_italic          = isset( $bdp_settings['template_extra_title_font_italic'] ) ? $bdp_settings['template_extra_title_font_italic'] : '';
					$template_extra_title_font_text_transform  = isset( $bdp_settings['template_extra_title_font_text_transform'] ) ? $bdp_settings['template_extra_title_font_text_transform'] : 'none';
					$template_extra_title_font_text_decoration = isset( $bdp_settings['template_extra_title_font_text_decoration'] ) ? $bdp_settings['template_extra_title_font_text_decoration'] : 'none';
					$template_extra_title_font_letter_spacing  = isset( $bdp_settings['template_extra_title_font_letter_spacing'] ) ? $bdp_settings['template_extra_title_font_letter_spacing'] : '0';
					$content_font_family                       = ( isset( $bdp_settings['content_font_family'] ) && '' != $bdp_settings['content_font_family'] ) ? $bdp_settings['content_font_family'] : '';
					if ( isset( $bdp_settings['content_font_family_font_type'] ) && 'Google Fonts' === $bdp_settings['content_font_family_font_type'] ) {
						$load_goog_font_blog[] = $content_font_family;
					}
					$template_alternative_color = ( isset( $bdp_settings['template_alternative_color'] ) && '' != $bdp_settings['template_alternative_color'] ) ? $bdp_settings['template_alternative_color'] : 'inherit';
					$grid_col_space             = ( isset( $bdp_settings['grid_col_space'] ) && '' != $bdp_settings['grid_col_space'] ) ? $bdp_settings['grid_col_space'] : 10;
					$content_fontsize           = ( isset( $bdp_settings['content_fontsize'] ) && '' != $bdp_settings['content_fontsize'] ) ? $bdp_settings['content_fontsize'] : '14';
					$story_startup_background   = ( isset( $bdp_settings['story_startup_background'] ) && '' != $bdp_settings['story_startup_background'] ) ? $bdp_settings['story_startup_background'] : '';
					$story_startup_text_color   = ( isset( $bdp_settings['story_startup_text_color'] ) && '' != $bdp_settings['story_startup_text_color'] ) ? $bdp_settings['story_startup_text_color'] : '';
					$story_ending_background    = ( isset( $bdp_settings['story_ending_background'] ) && '' != $bdp_settings['story_ending_background'] ) ? $bdp_settings['story_ending_background'] : '';
					$story_ending_text_color    = ( isset( $bdp_settings['story_ending_text_color'] ) && '' != $bdp_settings['story_ending_text_color'] ) ? $bdp_settings['story_ending_text_color'] : '';
					$story_startup_border_color = ( isset( $bdp_settings['story_startup_border_color'] ) && '' != $bdp_settings['story_ending_text_color'] ) ? $bdp_settings['story_startup_border_color'] : '';
					/**
					 * Font style Setting
					 */
					$template_title_font_weight          = isset( $bdp_settings['template_title_font_weight'] ) ? $bdp_settings['template_title_font_weight'] : '';
					$template_title_font_line_height     = isset( $bdp_settings['template_title_font_line_height'] ) ? $bdp_settings['template_title_font_line_height'] : '';
					$template_title_font_italic          = isset( $bdp_settings['template_title_font_italic'] ) ? $bdp_settings['template_title_font_italic'] : '';
					$template_title_font_text_transform  = isset( $bdp_settings['template_title_font_text_transform'] ) ? $bdp_settings['template_title_font_text_transform'] : 'none';
					$template_title_font_text_decoration = isset( $bdp_settings['template_title_font_text_decoration'] ) ? $bdp_settings['template_title_font_text_decoration'] : 'none';
					$template_title_font_letter_spacing  = isset( $bdp_settings['template_title_font_letter_spacing'] ) ? $bdp_settings['template_title_font_letter_spacing'] : '0';
					/**
					 * Content Font style Setting
					 */
					$content_font_weight          = isset( $bdp_settings['content_font_weight'] ) ? $bdp_settings['content_font_weight'] : '';
					$content_font_line_height     = isset( $bdp_settings['content_font_line_height'] ) ? $bdp_settings['content_font_line_height'] : '';
					$content_font_italic          = isset( $bdp_settings['content_font_italic'] ) ? $bdp_settings['content_font_italic'] : 0;
					$content_font_text_transform  = isset( $bdp_settings['content_font_text_transform'] ) ? $bdp_settings['content_font_text_transform'] : 'none';
					$content_font_text_decoration = isset( $bdp_settings['content_font_text_decoration'] ) ? $bdp_settings['content_font_text_decoration'] : 'none';
					$content_font_letter_spacing  = isset( $bdp_settings['content_font_letter_spacing'] ) ? $bdp_settings['content_font_letter_spacing'] : '0';
					/**
					 * Author Title Setting
					 */
					$author_titlecolor = ( isset( $bdp_settings['author_titlecolor'] ) && '' != $bdp_settings['author_titlecolor'] ) ? $bdp_settings['author_titlecolor'] : 'inherit';
					$author_title_size = ( isset( $bdp_settings['author_title_fontsize'] ) && '' != $bdp_settings['author_title_fontsize'] ) ? $bdp_settings['author_title_fontsize'] : 'inherit';
					$author_title_face = ( isset( $bdp_settings['author_title_fontface'] ) && '' != $bdp_settings['author_title_fontface'] ) ? $bdp_settings['author_title_fontface'] : 'inherit';
					if ( isset( $bdp_settings['author_title_fontface_font_type'] ) && 'Google Fonts' === $bdp_settings['author_title_fontface_font_type'] ) {
						$load_goog_font_blog[] = $author_title_face;
					}
					$author_title_font_weight          = isset( $bdp_settings['author_title_font_weight'] ) ? $bdp_settings['author_title_font_weight'] : '';
					$author_title_font_line_height     = isset( $bdp_settings['author_title_font_line_height'] ) ? $bdp_settings['author_title_font_line_height'] : '';
					$auhtor_title_font_italic          = isset( $bdp_settings['auhtor_title_font_italic'] ) ? $bdp_settings['auhtor_title_font_italic'] : 0;
					$author_title_font_text_transform  = isset( $bdp_settings['author_title_font_text_transform'] ) ? $bdp_settings['author_title_font_text_transform'] : 'none';
					$author_title_font_text_decoration = isset( $bdp_settings['author_title_font_text_decoration'] ) ? $bdp_settings['author_title_font_text_decoration'] : 'none';
					$author_title_font_letter_spacing  = isset( $bdp_settings['auhtor_title_font_letter_spacing'] ) ? $bdp_settings['auhtor_title_font_letter_spacing'] : '0';
					$author_bgcolor                    = ( isset( $bdp_settings['author_bgcolor'] ) && '' != $bdp_settings['author_bgcolor'] ) ? $bdp_settings['author_bgcolor'] : 'inherit';
					/**
					 * Author Content Font style Setting
					 */
					$author_content_color    = ( isset( $bdp_settings['author_content_color'] ) && '' != $bdp_settings['author_content_color'] ) ? $bdp_settings['author_content_color'] : 'inherit';
					$author_content_fontsize = ( isset( $bdp_settings['author_content_fontsize'] ) && '' != $bdp_settings['author_content_fontsize'] ) ? $bdp_settings['author_content_fontsize'] : 'inherit';
					$author_content_fontface = ( isset( $bdp_settings['author_content_fontface'] ) && '' != $bdp_settings['author_content_fontface'] ) ? $bdp_settings['author_content_fontface'] : '';
					if ( isset( $bdp_settings['author_content_fontface_font_type'] ) && 'Google Fonts' === $bdp_settings['author_content_fontface_font_type'] ) {
						$load_goog_font_blog[] = $author_content_fontface;
					}
					$author_content_font_weight          = isset( $bdp_settings['author_content_font_weight'] ) ? $bdp_settings['author_content_font_weight'] : '';
					$author_content_font_line_height     = isset( $bdp_settings['author_content_font_line_height'] ) ? $bdp_settings['author_content_font_line_height'] : '';
					$auhtor_content_font_italic          = isset( $bdp_settings['auhtor_content_font_italic'] ) ? $bdp_settings['auhtor_content_font_italic'] : 0;
					$author_content_font_text_transform  = isset( $bdp_settings['author_content_font_text_transform'] ) ? $bdp_settings['author_content_font_text_transform'] : 'none';
					$author_content_font_text_decoration = isset( $bdp_settings['author_content_font_text_decoration'] ) ? $bdp_settings['author_content_font_text_decoration'] : 'none';
					$auhtor_content_font_letter_spacing  = isset( $bdp_settings['auhtor_title_font_letterauhtor_content_font_letter_spacing_spacing'] ) ? $bdp_settings['auhtor_content_font_letter_spacing'] : '0';
					/**
					 *  Custom Read More Setting
					 */
					$beforeloop_readmoretext           = isset( $bdp_settings['beforeloop_Readmoretext'] ) ? $bdp_settings['beforeloop_Readmoretext'] : '';
					$beforeloop_readmorecolor          = isset( $bdp_settings['beforeloop_readmorecolor'] ) ? $bdp_settings['beforeloop_readmorecolor'] : '';
					$beforeloop_readmorebackcolor      = isset( $bdp_settings['beforeloop_readmorebackcolor'] ) ? $bdp_settings['beforeloop_readmorebackcolor'] : '';
					$beforeloop_readmorehovercolor     = isset( $bdp_settings['beforeloop_readmorehovercolor'] ) ? $bdp_settings['beforeloop_readmorehovercolor'] : '';
					$beforeloop_readmorehoverbackcolor = isset( $bdp_settings['beforeloop_readmorehoverbackcolor'] ) ? $bdp_settings['beforeloop_readmorehoverbackcolor'] : '';
					$beforeloop_titlefontface          = ( isset( $bdp_settings['beforeloop_titlefontface'] ) && '' != $bdp_settings['beforeloop_titlefontface'] ) ? $bdp_settings['beforeloop_titlefontface'] : '';
					if ( isset( $bdp_settings['beforeloop_titlefontface_font_type'] ) && 'Google Fonts' === $bdp_settings['beforeloop_titlefontface_font_type'] ) {
						$load_goog_font_blog[] = $beforeloop_titlefontface;
					}
					$beforeloop_titlefontsize              = ( isset( $bdp_settings['beforeloop_titlefontsize'] ) && '' != $bdp_settings['beforeloop_titlefontsize'] ) ? $bdp_settings['beforeloop_titlefontsize'] : 'inherit';
					$beforeloop_title_font_weight          = isset( $bdp_settings['beforeloop_title_font_weight'] ) ? $bdp_settings['beforeloop_title_font_weight'] : '';
					$beforeloop_title_font_line_height     = isset( $bdp_settings['beforeloop_title_font_line_height'] ) ? $bdp_settings['beforeloop_title_font_line_height'] : '';
					$beforeloop_title_font_italic          = isset( $bdp_settings['beforeloop_title_font_italic'] ) ? $bdp_settings['beforeloop_title_font_italic'] : '';
					$beforeloop_title_font_text_transform  = isset( $bdp_settings['beforeloop_title_font_text_transform'] ) ? $bdp_settings['beforeloop_title_font_text_transform'] : 'none';
					$beforeloop_title_font_text_decoration = isset( $bdp_settings['beforeloop_title_font_text_decoration'] ) ? $bdp_settings['beforeloop_title_font_text_decoration'] : 'none';
					$beforeloop_title_font_letter_spacing  = isset( $bdp_settings['beforeloop_title_font_letter_spacing'] ) ? $bdp_settings['beforeloop_title_font_letter_spacing'] : '0';
					/**
					 * Read more button font style setting.
					 */
					$readmore_font_family = ( isset( $bdp_settings['readmore_font_family'] ) && '' != $bdp_settings['readmore_font_family'] ) ? $bdp_settings['readmore_font_family'] : '';
					if ( isset( $bdp_settings['readmore_font_family_font_type'] ) && 'Google Fonts' === $bdp_settings['readmore_font_family_font_type'] ) {
						$load_goog_font_blog[] = $readmore_font_family;
					}
					$readmore_fontsize                  = ( isset( $bdp_settings['readmore_fontsize'] ) && '' != $bdp_settings['readmore_fontsize'] ) ? $bdp_settings['readmore_fontsize'] : 16;
					$readmore_font_weight               = isset( $bdp_settings['readmore_font_weight'] ) ? $bdp_settings['readmore_font_weight'] : '';
					$readmore_font_line_height          = isset( $bdp_settings['readmore_font_line_height'] ) ? $bdp_settings['readmore_font_line_height'] : '';
					$readmore_font_italic               = isset( $bdp_settings['readmore_font_italic'] ) ? $bdp_settings['readmore_font_italic'] : 0;
					$readmore_font_text_transform       = isset( $bdp_settings['readmore_font_text_transform'] ) ? $bdp_settings['readmore_font_text_transform'] : 'none';
					$readmore_font_text_decoration      = isset( $bdp_settings['readmore_font_text_decoration'] ) ? $bdp_settings['readmore_font_text_decoration'] : 'none';
					$readmore_font_letter_spacing       = isset( $bdp_settings['readmore_font_letter_spacing'] ) ? $bdp_settings['readmore_font_letter_spacing'] : 0;
					$pagination_text_color              = isset( $bdp_settings['pagination_text_color'] ) ? $bdp_settings['pagination_text_color'] : '#fff';
					$pagination_background_color        = isset( $bdp_settings['pagination_background_color'] ) ? $bdp_settings['pagination_background_color'] : '#777';
					$pagination_text_hover_color        = isset( $bdp_settings['pagination_text_hover_color'] ) ? $bdp_settings['pagination_text_hover_color'] : '';
					$pagination_background_hover_color  = isset( $bdp_settings['pagination_background_hover_color'] ) ? $bdp_settings['pagination_background_hover_color'] : '';
					$pagination_text_active_color       = isset( $bdp_settings['pagination_text_active_color'] ) ? $bdp_settings['pagination_text_active_color'] : '';
					$pagination_active_background_color = isset( $bdp_settings['pagination_active_background_color'] ) ? $bdp_settings['pagination_active_background_color'] : '';
					$pagination_border_color            = isset( $bdp_settings['pagination_border_color'] ) ? $bdp_settings['pagination_border_color'] : '#b2b2b2';
					$pagination_active_border_color     = isset( $bdp_settings['pagination_active_border_color'] ) ? $bdp_settings['pagination_active_border_color'] : '#007acc';
					/**
					 * Slider Image height
					 */
					$slider_image_height = isset( $bdp_settings['media_custom_height'] ) ? $bdp_settings['media_custom_height'] : '';
					/**
					 * Filter settings
					 */
					$filter_template                    = isset( $bdp_settings['filter_template'] ) ? $bdp_settings['filter_template'] : 'template-1';
					$display_filter_count               = isset( $bdp_settings['display_filter_count'] ) ? $bdp_settings['display_filter_count'] : '0';
					$filter_paddingleft                 = isset( $bdp_settings['filter_paddingleft'] ) ? $bdp_settings['filter_paddingleft'] : '10';
					$filter_paddingright                = isset( $bdp_settings['filter_paddingright'] ) ? $bdp_settings['filter_paddingright'] : '10';
					$filter_paddingtop                  = isset( $bdp_settings['filter_paddingtop'] ) ? $bdp_settings['filter_paddingtop'] : '10';
					$filter_paddingbottom               = isset( $bdp_settings['filter_paddingbottom'] ) ? $bdp_settings['filter_paddingbottom'] : '10';
					$filter_marginleft                  = isset( $bdp_settings['filter_marginleft'] ) ? $bdp_settings['filter_marginleft'] : '3';
					$filter_marginright                 = isset( $bdp_settings['filter_marginright'] ) ? $bdp_settings['filter_marginright'] : '3';
					$filter_margintop                   = isset( $bdp_settings['filter_margintop'] ) ? $bdp_settings['filter_margintop'] : '3';
					$filter_marginbottom                = isset( $bdp_settings['filter_marginbottom'] ) ? $bdp_settings['filter_marginbottom'] : '3';
					$filter_background_color            = isset( $bdp_settings['filter_background_color'] ) ? $bdp_settings['filter_background_color'] : '#fff';
					$filter_color                       = isset( $bdp_settings['filter_color'] ) ? $bdp_settings['filter_color'] : '#222';
					$bdp_filter_borderleft              = isset( $bdp_settings['bdp_filter_borderleft'] ) ? $bdp_settings['bdp_filter_borderleft'] : '1';
					$bdp_filter_borderleftcolor         = isset( $bdp_settings['bdp_filter_borderleftcolor'] ) ? $bdp_settings['bdp_filter_borderleftcolor'] : '#222';
					$bdp_filter_borderleftstyle         = isset( $bdp_settings['bdp_filter_borderleftstyle'] ) ? $bdp_settings['bdp_filter_borderleftstyle'] : 'solid';
					$bdp_filter_borderright             = isset( $bdp_settings['bdp_filter_borderright'] ) ? $bdp_settings['bdp_filter_borderright'] : '1';
					$bdp_filter_borderrightcolor        = isset( $bdp_settings['bdp_filter_borderrightcolor'] ) ? $bdp_settings['bdp_filter_borderrightcolor'] : '#222';
					$bdp_filter_borderrightstyle        = isset( $bdp_settings['bdp_filter_borderrightstyle'] ) ? $bdp_settings['bdp_filter_borderrightstyle'] : 'solid';
					$bdp_filter_bordertop               = isset( $bdp_settings['bdp_filter_bordertop'] ) ? $bdp_settings['bdp_filter_bordertop'] : '1';
					$bdp_filter_bordertopcolor          = isset( $bdp_settings['bdp_filter_bordertopcolor'] ) ? $bdp_settings['bdp_filter_bordertopcolor'] : '#222';
					$bdp_filter_bordertopstyle          = isset( $bdp_settings['bdp_filter_bordertopstyle'] ) ? $bdp_settings['bdp_filter_bordertopstyle'] : 'solid';
					$bdp_filter_borderbottom            = isset( $bdp_settings['bdp_filter_borderbottom'] ) ? $bdp_settings['bdp_filter_borderbottom'] : '1';
					$bdp_filter_borderbottomcolor       = isset( $bdp_settings['bdp_filter_borderbottomcolor'] ) ? $bdp_settings['bdp_filter_borderbottomcolor'] : '#222';
					$bdp_filter_borderbottomstyle       = isset( $bdp_settings['bdp_filter_borderbottomstyle'] ) ? $bdp_settings['bdp_filter_borderbottomstyle'] : 'solid';
					$filter_background_hover_color      = isset( $bdp_settings['filter_background_hover_color'] ) ? $bdp_settings['filter_background_hover_color'] : '#222';
					$filter_hover_color                 = isset( $bdp_settings['filter_hover_color'] ) ? $bdp_settings['filter_hover_color'] : '#fff';
					$bdp_filter_hover_borderleft        = isset( $bdp_settings['bdp_filter_hover_borderleft'] ) ? $bdp_settings['bdp_filter_hover_borderleft'] : '1';
					$bdp_filter_hover_borderleftcolor   = isset( $bdp_settings['bdp_filter_hover_borderleftcolor'] ) ? $bdp_settings['bdp_filter_hover_borderleftcolor'] : '#fff';
					$bdp_filter_hover_borderleftstyle   = isset( $bdp_settings['bdp_filter_hover_borderleftstyle'] ) ? $bdp_settings['bdp_filter_hover_borderleftstyle'] : 'solid';
					$bdp_filter_hover_borderright       = isset( $bdp_settings['bdp_filter_hover_borderright'] ) ? $bdp_settings['bdp_filter_hover_borderright'] : '1';
					$bdp_filter_hover_borderrightcolor  = isset( $bdp_settings['bdp_filter_hover_borderrightcolor'] ) ? $bdp_settings['bdp_filter_hover_borderrightcolor'] : '#fff';
					$bdp_filter_hover_borderrightstyle  = isset( $bdp_settings['bdp_filter_hover_borderrightstyle'] ) ? $bdp_settings['bdp_filter_hover_borderrightstyle'] : 'solid';
					$bdp_filter_hover_bordertop         = isset( $bdp_settings['bdp_filter_hover_bordertop'] ) ? $bdp_settings['bdp_filter_hover_bordertop'] : '1';
					$bdp_filter_hover_bordertopcolor    = isset( $bdp_settings['bdp_filter_hover_bordertopcolor'] ) ? $bdp_settings['bdp_filter_hover_bordertopcolor'] : '#fff';
					$bdp_filter_hover_bordertopstyle    = isset( $bdp_settings['bdp_filter_hover_bordertopstyle'] ) ? $bdp_settings['bdp_filter_hover_bordertopstyle'] : 'solid';
					$bdp_filter_hover_borderbottom      = isset( $bdp_settings['bdp_filter_hover_borderbottom'] ) ? $bdp_settings['bdp_filter_hover_borderbottom'] : '1';
					$bdp_filter_hover_borderbottomcolor = isset( $bdp_settings['bdp_filter_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_filter_hover_borderbottomcolor'] : '#fff';
					$bdp_filter_hover_borderbottomstyle = isset( $bdp_settings['bdp_filter_hover_borderbottomstyle'] ) ? $bdp_settings['bdp_filter_hover_borderbottomstyle'] : 'solid';
					/**
					 *  Woocommerce sale tag
					 */
					$bdp_sale_tagtextcolor          = isset( $bdp_settings['bdp_sale_tagtextcolor'] ) ? $bdp_settings['bdp_sale_tagtextcolor'] : '';
					$bdp_sale_tagbgcolor            = isset( $bdp_settings['bdp_sale_tagbgcolor'] ) ? $bdp_settings['bdp_sale_tagbgcolor'] : '';
					$bdp_sale_tag_angle             = isset( $bdp_settings['bdp_sale_tag_angle'] ) ? $bdp_settings['bdp_sale_tag_angle'] : '';
					$bdp_sale_tag_border_radius     = isset( $bdp_settings['bdp_sale_tag_border_radius'] ) ? $bdp_settings['bdp_sale_tag_border_radius'] : '';
					$bdp_sale_tagtext_alignment     = isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
					$bdp_sale_tagtext_marginleft    = isset( $bdp_settings['bdp_sale_tagtext_marginleft'] ) ? $bdp_settings['bdp_sale_tagtext_marginleft'] : '5';
					$bdp_sale_tagtext_marginright   = isset( $bdp_settings['bdp_sale_tagtext_marginright'] ) ? $bdp_settings['bdp_sale_tagtext_marginright'] : '5';
					$bdp_sale_tagtext_margintop     = isset( $bdp_settings['bdp_sale_tagtext_margintop'] ) ? $bdp_settings['bdp_sale_tagtext_margintop'] : '5';
					$bdp_sale_tagtext_marginbottom  = isset( $bdp_settings['bdp_sale_tagtext_marginbottom'] ) ? $bdp_settings['bdp_sale_tagtext_marginbottom'] : '5';
					$bdp_sale_tagtext_paddingleft   = isset( $bdp_settings['bdp_sale_tagtext_paddingleft'] ) ? $bdp_settings['bdp_sale_tagtext_paddingleft'] : '5';
					$bdp_sale_tagtext_paddingright  = isset( $bdp_settings['bdp_sale_tagtext_paddingright'] ) ? $bdp_settings['bdp_sale_tagtext_paddingright'] : '5';
					$bdp_sale_tagtext_paddingtop    = isset( $bdp_settings['bdp_sale_tagtext_paddingtop'] ) ? $bdp_settings['bdp_sale_tagtext_paddingtop'] : '5';
					$bdp_sale_tagtext_paddingbottom = isset( $bdp_settings['bdp_sale_tagtext_paddingbottom'] ) ? $bdp_settings['bdp_sale_tagtext_paddingbottom'] : '5';
					$bdp_sale_tagfontface           = ( isset( $bdp_settings['bdp_sale_tagfontface'] ) && '' != $bdp_settings['bdp_sale_tagfontface'] ) ? $bdp_settings['bdp_sale_tagfontface'] : '';
					if ( isset( $bdp_settings['bdp_sale_tagfontface_font_type'] ) && 'Google Fonts' == $bdp_settings['bdp_sale_tagfontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_sale_tagfontface;
					}
					$bdp_sale_tagfontsize              = ( isset( $bdp_settings['bdp_sale_tagfontsize'] ) && '' != $bdp_settings['bdp_sale_tagfontsize'] ) ? $bdp_settings['bdp_sale_tagfontsize'] : 'inherit';
					$bdp_sale_tag_font_weight          = isset( $bdp_settings['bdp_sale_tag_font_weight'] ) ? $bdp_settings['bdp_sale_tag_font_weight'] : '';
					$bdp_sale_tag_font_line_height     = isset( $bdp_settings['bdp_sale_tag_font_line_height'] ) ? $bdp_settings['bdp_sale_tag_font_line_height'] : '';
					$bdp_sale_tag_font_italic          = isset( $bdp_settings['bdp_sale_tag_font_italic'] ) ? $bdp_settings['bdp_sale_tag_font_italic'] : '';
					$bdp_sale_tag_font_text_transform  = isset( $bdp_settings['bdp_sale_tag_font_text_transform'] ) ? $bdp_settings['bdp_sale_tag_font_text_transform'] : 'none';
					$bdp_sale_tag_font_text_decoration = isset( $bdp_settings['bdp_sale_tag_font_text_decoration'] ) ? $bdp_settings['bdp_sale_tag_font_text_decoration'] : 'none';
					$bdp_sale_tag_font_letter_spacing  = isset( $bdp_settings['bdp_sale_tag_font_letter_spacing'] ) ? $bdp_settings['bdp_sale_tag_font_letter_spacing'] : '0';
					/**
					 *  Woocommerce price text
					 */
					$bdp_pricetextcolor          = isset( $bdp_settings['bdp_pricetextcolor'] ) ? $bdp_settings['bdp_pricetextcolor'] : '#444444';
					$bdp_pricetext_alignment     = isset( $bdp_settings['bdp_pricetext_alignment'] ) ? $bdp_settings['bdp_pricetext_alignment'] : 'left';
					$bdp_pricetext_paddingleft   = isset( $bdp_settings['bdp_pricetext_paddingleft'] ) ? $bdp_settings['bdp_pricetext_paddingleft'] : '10';
					$bdp_pricetext_paddingright  = isset( $bdp_settings['bdp_pricetext_paddingright'] ) ? $bdp_settings['bdp_pricetext_paddingright'] : '10';
					$bdp_pricetext_paddingtop    = isset( $bdp_settings['bdp_pricetext_paddingtop'] ) ? $bdp_settings['bdp_pricetext_paddingtop'] : '10';
					$bdp_pricetext_paddingbottom = isset( $bdp_settings['bdp_pricetext_paddingbottom'] ) ? $bdp_settings['bdp_pricetext_paddingbottom'] : '10';
					$bdp_pricetext_marginleft    = isset( $bdp_settings['bdp_pricetext_marginleft'] ) ? $bdp_settings['bdp_pricetext_marginleft'] : '10';
					$bdp_pricetext_marginright   = isset( $bdp_settings['bdp_pricetext_marginright'] ) ? $bdp_settings['bdp_pricetext_marginright'] : '10';
					$bdp_pricetext_margintop     = isset( $bdp_settings['bdp_pricetext_margintop'] ) ? $bdp_settings['bdp_pricetext_margintop'] : '10';
					$bdp_pricetext_marginbottom  = isset( $bdp_settings['bdp_pricetext_marginbottom'] ) ? $bdp_settings['bdp_pricetext_marginbottom'] : '10';
					$bdp_pricefontface           = ( isset( $bdp_settings['bdp_pricefontface'] ) && '' != $bdp_settings['bdp_pricefontface'] ) ? $bdp_settings['bdp_pricefontface'] : '';
					if ( isset( $bdp_settings['bdp_pricefontface_font_type'] ) && 'Google Fonts' === $bdp_settings['bdp_pricefontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_pricefontface;
					}
					$bdp_pricefontsize              = ( isset( $bdp_settings['bdp_pricefontsize'] ) && '' != $bdp_settings['bdp_pricefontsize'] ) ? $bdp_settings['bdp_pricefontsize'] : 'inherit';
					$bdp_price_font_weight          = isset( $bdp_settings['bdp_price_font_weight'] ) ? $bdp_settings['bdp_price_font_weight'] : '';
					$bdp_price_font_line_height     = isset( $bdp_settings['bdp_price_font_line_height'] ) ? $bdp_settings['bdp_price_font_line_height'] : '';
					$bdp_price_font_italic          = isset( $bdp_settings['bdp_price_font_italic'] ) ? $bdp_settings['bdp_price_font_italic'] : '';
					$bdp_price_font_letter_spacing  = isset( $bdp_settings['bdp_price_font_letter_spacing'] ) ? $bdp_settings['bdp_price_font_letter_spacing'] : '0';
					$bdp_price_font_text_transform  = isset( $bdp_settings['bdp_price_font_text_transform'] ) ? $bdp_settings['bdp_price_font_text_transform'] : 'none';
					$bdp_price_font_text_decoration = isset( $bdp_settings['bdp_price_font_text_decoration'] ) ? $bdp_settings['bdp_price_font_text_decoration'] : 'none';
					/**
					 * Add To Cart Button
					 */
					$bdp_addtocart_textcolor                      = isset( $bdp_settings['bdp_addtocart_textcolor'] ) ? $bdp_settings['bdp_addtocart_textcolor'] : '';
					$bdp_addtocart_backgroundcolor                = isset( $bdp_settings['bdp_addtocart_backgroundcolor'] ) ? $bdp_settings['bdp_addtocart_backgroundcolor'] : '';
					$bdp_addtocart_text_hover_color               = isset( $bdp_settings['bdp_addtocart_text_hover_color'] ) ? $bdp_settings['bdp_addtocart_text_hover_color'] : '';
					$bdp_addtocart_hover_backgroundcolor          = isset( $bdp_settings['bdp_addtocart_hover_backgroundcolor'] ) ? $bdp_settings['bdp_addtocart_hover_backgroundcolor'] : '';
					$bdp_addtocartbutton_borderleft               = isset( $bdp_settings['bdp_addtocartbutton_borderleft'] ) ? $bdp_settings['bdp_addtocartbutton_borderleft'] : '';
					$bdp_addtocartbutton_borderleftcolor          = isset( $bdp_settings['bdp_addtocartbutton_borderleftcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderleftcolor'] : '';
					$bdp_addtocartbutton_borderright              = isset( $bdp_settings['bdp_addtocartbutton_borderright'] ) ? $bdp_settings['bdp_addtocartbutton_borderright'] : '';
					$bdp_addtocartbutton_borderrightcolor         = isset( $bdp_settings['bdp_addtocartbutton_borderrightcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderrightcolor'] : '';
					$bdp_addtocartbutton_bordertop                = isset( $bdp_settings['bdp_addtocartbutton_bordertop'] ) ? $bdp_settings['bdp_addtocartbutton_bordertop'] : '';
					$bdp_addtocartbutton_bordertopcolor           = isset( $bdp_settings['bdp_addtocartbutton_bordertopcolor'] ) ? $bdp_settings['bdp_addtocartbutton_bordertopcolor'] : '';
					$bdp_addtocartbutton_borderbuttom             = isset( $bdp_settings['bdp_addtocartbutton_borderbuttom'] ) ? $bdp_settings['bdp_addtocartbutton_borderbuttom'] : '';
					$bdp_addtocartbutton_borderbottomcolor        = isset( $bdp_settings['bdp_addtocartbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderbottomcolor'] : '';
					$display_addtocart_button_border              = isset( $bdp_settings['display_addtocart_button_border'] ) ? $bdp_settings['display_addtocart_button_border'] : '0';
					$display_addtocart_button_border_radius       = isset( $bdp_settings['display_addtocart_button_border_radius'] ) ? $bdp_settings['display_addtocart_button_border_radius'] : '';
					$bdp_addtocartbutton_padding_leftright        = isset( $bdp_settings['bdp_addtocartbutton_padding_leftright'] ) ? $bdp_settings['bdp_addtocartbutton_padding_leftright'] : '10';
					$bdp_addtocartbutton_padding_topbottom        = isset( $bdp_settings['bdp_addtocartbutton_padding_topbottom'] ) ? $bdp_settings['bdp_addtocartbutton_padding_topbottom'] : '10';
					$bdp_addtocartbutton_margin_leftright         = isset( $bdp_settings['bdp_addtocartbutton_margin_leftright'] ) ? $bdp_settings['bdp_addtocartbutton_margin_leftright'] : '';
					$bdp_addtocartbutton_margin_topbottom         = isset( $bdp_settings['bdp_addtocartbutton_margin_topbottom'] ) ? $bdp_settings['bdp_addtocartbutton_margin_topbottom'] : '';
					$bdp_addtocartbutton_alignment                = isset( $bdp_settings['bdp_addtocartbutton_alignment'] ) ? $bdp_settings['bdp_addtocartbutton_alignment'] : 'left';
					$bdp_addtocartbutton_hover_borderleft         = isset( $bdp_settings['bdp_addtocartbutton_hover_borderleft'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderleft'] : '';
					$bdp_addtocartbutton_hover_borderleftcolor    = isset( $bdp_settings['bdp_addtocartbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderleftcolor'] : '';
					$bdp_addtocartbutton_hover_borderright        = isset( $bdp_settings['bdp_addtocartbutton_hover_borderright'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderright'] : '';
					$bdp_addtocartbutton_hover_borderrightcolor   = isset( $bdp_settings['bdp_addtocartbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderrightcolor'] : '';
					$bdp_addtocartbutton_hover_bordertop          = isset( $bdp_settings['bdp_addtocartbutton_hover_bordertop'] ) ? $bdp_settings['bdp_addtocartbutton_hover_bordertop'] : '';
					$bdp_addtocartbutton_hover_bordertopcolor     = isset( $bdp_settings['bdp_addtocartbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_bordertopcolor'] : '';
					$bdp_addtocartbutton_hover_borderbuttom       = isset( $bdp_settings['bdp_addtocartbutton_hover_borderbuttom'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderbuttom'] : '';
					$bdp_addtocartbutton_hover_borderbottomcolor  = isset( $bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor'] : '';
					$display_addtocart_button_border_hover_radius = isset( $bdp_settings['display_addtocart_button_border_hover_radius'] ) ? $bdp_settings['display_addtocart_button_border_hover_radius'] : '0';
					$bdp_addtocart_button_top_box_shadow          = isset( $bdp_settings['bdp_addtocart_button_top_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_top_box_shadow'] : '';
					$bdp_addtocart_button_right_box_shadow        = isset( $bdp_settings['bdp_addtocart_button_right_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_right_box_shadow'] : '';
					$bdp_addtocart_button_bottom_box_shadow       = isset( $bdp_settings['bdp_addtocart_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_bottom_box_shadow'] : '';
					$bdp_addtocart_button_left_box_shadow         = isset( $bdp_settings['bdp_addtocart_button_left_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_left_box_shadow'] : '';
					$bdp_addtocart_button_box_shadow_color        = isset( $bdp_settings['bdp_addtocart_button_box_shadow_color'] ) ? $bdp_settings['bdp_addtocart_button_box_shadow_color'] : '';
					$bdp_addtocart_button_hover_top_box_shadow    = isset( $bdp_settings['bdp_addtocart_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_top_box_shadow'] : '';
					$bdp_addtocart_button_hover_right_box_shadow  = isset( $bdp_settings['bdp_addtocart_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_right_box_shadow'] : '';
					$bdp_addtocart_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow'] : '';
					$bdp_addtocart_button_hover_left_box_shadow   = isset( $bdp_settings['bdp_addtocart_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_left_box_shadow'] : '';
					$bdp_addtocart_button_hover_box_shadow_color  = isset( $bdp_settings['bdp_addtocart_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_addtocart_button_hover_box_shadow_color'] : '';
					$bdp_addtocart_button_fontface                = ( isset( $bdp_settings['bdp_addtocart_button_fontface'] ) && '' != $bdp_settings['bdp_addtocart_button_fontface'] ) ? $bdp_settings['bdp_addtocart_button_fontface'] : '';
					if ( isset( $bdp_settings['bdp_addtocart_button_fontface_font_type'] ) && 'Google Fonts' === $bdp_settings['bdp_addtocart_button_fontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_addtocart_button_fontface;
					}
					$bdp_addtocart_button_fontsize             = ( isset( $bdp_settings['bdp_addtocart_button_fontsize'] ) && '' != $bdp_settings['bdp_addtocart_button_fontsize'] ) ? $bdp_settings['bdp_addtocart_button_fontsize'] : 'inherit';
					$bdp_addtocart_button_font_weight          = isset( $bdp_settings['bdp_addtocart_button_font_weight'] ) ? $bdp_settings['bdp_addtocart_button_font_weight'] : '';
					$bdp_addtocart_button_font_italic          = isset( $bdp_settings['bdp_addtocart_button_font_italic'] ) ? $bdp_settings['bdp_addtocart_button_font_italic'] : '';
					$bdp_addtocart_button_letter_spacing       = isset( $bdp_settings['bdp_addtocart_button_letter_spacing'] ) ? $bdp_settings['bdp_addtocart_button_letter_spacing'] : '0';
					$display_addtocart_button_line_height      = isset( $bdp_settings['display_addtocart_button_line_height'] ) ? $bdp_settings['display_addtocart_button_line_height'] : '1.5';
					$bdp_addtocart_button_font_text_transform  = isset( $bdp_settings['bdp_addtocart_button_font_text_transform'] ) ? $bdp_settings['bdp_addtocart_button_font_text_transform'] : 'none';
					$bdp_addtocart_button_font_text_decoration = isset( $bdp_settings['bdp_addtocart_button_font_text_decoration'] ) ? $bdp_settings['bdp_addtocart_button_font_text_decoration'] : 'none';
					/**
					 *  Woocommerce Star Rating
					 */
					$bdp_star_rating_bg_color      = isset( $bdp_settings['bdp_star_rating_bg_color'] ) ? $bdp_settings['bdp_star_rating_bg_color'] : '';
					$bdp_star_rating_color         = isset( $bdp_settings['bdp_star_rating_color'] ) ? $bdp_settings['bdp_star_rating_color'] : '';
					$bdp_star_rating_alignment     = isset( $bdp_settings['bdp_star_rating_alignment'] ) ? $bdp_settings['bdp_star_rating_alignment'] : 'left';
					$bdp_star_rating_paddingleft   = isset( $bdp_settings['bdp_star_rating_paddingleft'] ) ? $bdp_settings['bdp_star_rating_paddingleft'] : '10';
					$bdp_star_rating_paddingright  = isset( $bdp_settings['bdp_star_rating_paddingright'] ) ? $bdp_settings['bdp_star_rating_paddingright'] : '10';
					$bdp_star_rating_paddingtop    = isset( $bdp_settings['bdp_star_rating_paddingtop'] ) ? $bdp_settings['bdp_star_rating_paddingtop'] : '10';
					$bdp_star_rating_paddingbottom = isset( $bdp_settings['bdp_star_rating_paddingbottom'] ) ? $bdp_settings['bdp_star_rating_paddingbottom'] : '10';
					$bdp_star_rating_marginleft    = isset( $bdp_settings['bdp_star_rating_marginleft'] ) ? $bdp_settings['bdp_star_rating_marginleft'] : '10';
					$bdp_star_rating_marginright   = isset( $bdp_settings['bdp_star_rating_marginright'] ) ? $bdp_settings['bdp_star_rating_marginright'] : '10';
					$bdp_star_rating_margintop     = isset( $bdp_settings['bdp_star_rating_margintop'] ) ? $bdp_settings['bdp_star_rating_margintop'] : '10';
					$bdp_star_rating_marginbottom  = isset( $bdp_settings['bdp_star_rating_marginbottom'] ) ? $bdp_settings['bdp_star_rating_marginbottom'] : '10';
					/**
					 * Add To Whishlist Button
					 */
					$bdp_wishlist_textcolor                      = isset( $bdp_settings['bdp_wishlist_textcolor'] ) ? $bdp_settings['bdp_wishlist_textcolor'] : '';
					$bdp_wishlist_backgroundcolor                = isset( $bdp_settings['bdp_wishlist_backgroundcolor'] ) ? $bdp_settings['bdp_wishlist_backgroundcolor'] : '';
					$bdp_wishlist_text_hover_color               = isset( $bdp_settings['bdp_wishlist_text_hover_color'] ) ? $bdp_settings['bdp_wishlist_text_hover_color'] : '';
					$bdp_wishlist_hover_backgroundcolor          = isset( $bdp_settings['bdp_wishlist_hover_backgroundcolor'] ) ? $bdp_settings['bdp_wishlist_hover_backgroundcolor'] : '';
					$bdp_wishlistbutton_borderleft               = isset( $bdp_settings['bdp_wishlistbutton_borderleft'] ) ? $bdp_settings['bdp_wishlistbutton_borderleft'] : '';
					$bdp_wishlistbutton_borderleftcolor          = isset( $bdp_settings['bdp_wishlistbutton_borderleftcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderleftcolor'] : '';
					$bdp_wishlistbutton_borderright              = isset( $bdp_settings['bdp_wishlistbutton_borderright'] ) ? $bdp_settings['bdp_wishlistbutton_borderright'] : '';
					$bdp_wishlistbutton_borderrightcolor         = isset( $bdp_settings['bdp_wishlistbutton_borderrightcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderrightcolor'] : '';
					$bdp_wishlistbutton_bordertop                = isset( $bdp_settings['bdp_wishlistbutton_bordertop'] ) ? $bdp_settings['bdp_wishlistbutton_bordertop'] : '';
					$bdp_wishlistbutton_bordertopcolor           = isset( $bdp_settings['bdp_wishlistbutton_bordertopcolor'] ) ? $bdp_settings['bdp_wishlistbutton_bordertopcolor'] : '';
					$bdp_wishlistbutton_borderbuttom             = isset( $bdp_settings['bdp_wishlistbutton_borderbuttom'] ) ? $bdp_settings['bdp_wishlistbutton_borderbuttom'] : '';
					$bdp_wishlistbutton_borderbottomcolor        = isset( $bdp_settings['bdp_wishlistbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderbottomcolor'] : '';
					$display_wishlist_button_border_radius       = isset( $bdp_settings['display_wishlist_button_border_radius'] ) ? $bdp_settings['display_wishlist_button_border_radius'] : '';
					$bdp_wishlistbutton_padding_leftright        = isset( $bdp_settings['bdp_wishlistbutton_padding_leftright'] ) ? $bdp_settings['bdp_wishlistbutton_padding_leftright'] : '';
					$bdp_wishlistbutton_padding_topbottom        = isset( $bdp_settings['bdp_wishlistbutton_padding_topbottom'] ) ? $bdp_settings['bdp_wishlistbutton_padding_topbottom'] : '';
					$bdp_wishlistbutton_margin_leftright         = isset( $bdp_settings['bdp_wishlistbutton_margin_leftright'] ) ? $bdp_settings['bdp_wishlistbutton_margin_leftright'] : '';
					$bdp_wishlistbutton_margin_topbottom         = isset( $bdp_settings['bdp_wishlistbutton_margin_topbottom'] ) ? $bdp_settings['bdp_wishlistbutton_margin_topbottom'] : '';
					$bdp_cart_wishlistbutton_alignment           = isset( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) ? $bdp_settings['bdp_cart_wishlistbutton_alignment'] : 'left';
					$bdp_wishlistbutton_alignment                = isset( $bdp_settings['bdp_wishlistbutton_alignment'] ) ? $bdp_settings['bdp_wishlistbutton_alignment'] : 'left';
					$bdp_wishlistbutton_hover_borderleft         = isset( $bdp_settings['bdp_wishlistbutton_hover_borderleft'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderleft'] : '';
					$bdp_wishlistbutton_hover_borderleftcolor    = isset( $bdp_settings['bdp_wishlistbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderleftcolor'] : '';
					$bdp_wishlistbutton_hover_borderright        = isset( $bdp_settings['bdp_wishlistbutton_hover_borderright'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderright'] : '';
					$bdp_wishlistbutton_hover_borderrightcolor   = isset( $bdp_settings['bdp_wishlistbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderrightcolor'] : '';
					$bdp_wishlistbutton_hover_bordertop          = isset( $bdp_settings['bdp_wishlistbutton_hover_bordertop'] ) ? $bdp_settings['bdp_wishlistbutton_hover_bordertop'] : '';
					$bdp_wishlistbutton_hover_bordertopcolor     = isset( $bdp_settings['bdp_wishlistbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_bordertopcolor'] : '';
					$bdp_wishlistbutton_hover_borderbuttom       = isset( $bdp_settings['bdp_wishlistbutton_hover_borderbuttom'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderbuttom'] : '';
					$bdp_wishlistbutton_hover_borderbottomcolor  = isset( $bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor'] : '';
					$display_wishlist_button_border_hover_radius = isset( $bdp_settings['display_wishlist_button_border_hover_radius'] ) ? $bdp_settings['display_wishlist_button_border_hover_radius'] : '0';
					$bdp_wishlist_button_top_box_shadow          = isset( $bdp_settings['bdp_wishlist_button_top_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_top_box_shadow'] : '';
					$bdp_wishlist_button_right_box_shadow        = isset( $bdp_settings['bdp_wishlist_button_right_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_right_box_shadow'] : '';
					$bdp_wishlist_button_bottom_box_shadow       = isset( $bdp_settings['bdp_wishlist_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_bottom_box_shadow'] : '';
					$bdp_wishlist_button_left_box_shadow         = isset( $bdp_settings['bdp_wishlist_button_left_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_left_box_shadow'] : '';
					$bdp_wishlist_button_box_shadow_color        = isset( $bdp_settings['bdp_wishlist_button_box_shadow_color'] ) ? $bdp_settings['bdp_wishlist_button_box_shadow_color'] : '';
					$bdp_wishlist_button_hover_top_box_shadow    = isset( $bdp_settings['bdp_wishlist_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_top_box_shadow'] : '';
					$bdp_wishlist_button_hover_right_box_shadow  = isset( $bdp_settings['bdp_wishlist_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_right_box_shadow'] : '';
					$bdp_wishlist_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow'] : '';
					$bdp_wishlist_button_hover_left_box_shadow   = isset( $bdp_settings['bdp_wishlist_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_left_box_shadow'] : '';
					$bdp_wishlist_button_hover_box_shadow_color  = isset( $bdp_settings['bdp_wishlist_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_wishlist_button_hover_box_shadow_color'] : '';
					$bdp_wishlistbutton_on                       = isset( $bdp_settings['bdp_wishlistbutton_on'] ) ? $bdp_settings['bdp_wishlistbutton_on'] : '1';
					$bdp_addtowishlist_button_fontface           = ( isset( $bdp_settings['bdp_addtowishlist_button_fontface'] ) && '' != $bdp_settings['bdp_addtowishlist_button_fontface'] ) ? $bdp_settings['bdp_addtowishlist_button_fontface'] : '';
					if ( isset( $bdp_settings['bdp_addtowishlist_button_fontface_font_type'] ) && 'Google Fonts' === $bdp_settings['bdp_addtowishlist_button_fontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_addtowishlist_button_fontface;
					}
					$bdp_addtowishlist_button_fontsize             = ( isset( $bdp_settings['bdp_addtowishlist_button_fontsize'] ) && '' != $bdp_settings['bdp_addtowishlist_button_fontsize'] ) ? $bdp_settings['bdp_addtowishlist_button_fontsize'] : 'inherit';
					$bdp_addtowishlist_button_font_weight          = isset( $bdp_settings['bdp_addtowishlist_button_font_weight'] ) ? $bdp_settings['bdp_addtowishlist_button_font_weight'] : '';
					$bdp_addtowishlist_button_font_italic          = isset( $bdp_settings['bdp_addtowishlist_button_font_italic'] ) ? $bdp_settings['bdp_addtowishlist_button_font_italic'] : '';
					$bdp_addtowishlist_button_letter_spacing       = isset( $bdp_settings['bdp_addtowishlist_button_letter_spacing'] ) ? $bdp_settings['bdp_addtowishlist_button_letter_spacing'] : '0';
					$display_wishlist_button_line_height           = isset( $bdp_settings['display_wishlist_button_line_height'] ) ? $bdp_settings['display_wishlist_button_line_height'] : '1.5';
					$bdp_addtowishlist_button_font_text_transform  = isset( $bdp_settings['bdp_addtowishlist_button_font_text_transform'] ) ? $bdp_settings['bdp_addtowishlist_button_font_text_transform'] : 'none';
					$bdp_addtowishlist_button_font_text_decoration = isset( $bdp_settings['bdp_addtowishlist_button_font_text_decoration'] ) ? $bdp_settings['bdp_addtowishlist_button_font_text_decoration'] : 'none';
					/**
					 * Easy Digital Download Price Text
					 */
					$bdp_edd_price_color         = isset( $bdp_settings['bdp_edd_price_color'] ) ? $bdp_settings['bdp_edd_price_color'] : '#444444';
					$bdp_edd_price_alignment     = isset( $bdp_settings['bdp_edd_price_alignment'] ) ? $bdp_settings['bdp_edd_price_alignment'] : 'left';
					$bdp_edd_price_paddingleft   = isset( $bdp_settings['bdp_edd_price_paddingleft'] ) ? $bdp_settings['bdp_edd_price_paddingleft'] : '10';
					$bdp_edd_price_paddingright  = isset( $bdp_settings['bdp_edd_price_paddingright'] ) ? $bdp_settings['bdp_edd_price_paddingright'] : '10';
					$bdp_edd_price_paddingtop    = isset( $bdp_settings['bdp_edd_price_paddingtop'] ) ? $bdp_settings['bdp_edd_price_paddingtop'] : '10';
					$bdp_edd_price_paddingbottom = isset( $bdp_settings['bdp_edd_price_paddingbottom'] ) ? $bdp_settings['bdp_edd_price_paddingbottom'] : '10';
					$bdp_edd_pricefontface       = ( isset( $bdp_settings['bdp_edd_pricefontface'] ) && '' != $bdp_settings['bdp_edd_pricefontface'] ) ? $bdp_settings['bdp_edd_pricefontface'] : '';
					if ( isset( $bdp_settings['bdp_edd_pricefontface_font_type'] ) && 'Google Fonts' === $bdp_settings['bdp_edd_pricefontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_edd_pricefontface;
					}
					$bdp_edd_pricefontsize              = ( isset( $bdp_settings['bdp_edd_pricefontsize'] ) && '' != $bdp_settings['bdp_edd_pricefontsize'] ) ? $bdp_settings['bdp_edd_pricefontsize'] : '18';
					$bdp_edd_price_font_weight          = isset( $bdp_settings['bdp_edd_price_font_weight'] ) ? $bdp_settings['bdp_edd_price_font_weight'] : '';
					$bdp_edd_price_font_line_height     = isset( $bdp_settings['bdp_edd_price_font_line_height'] ) ? $bdp_settings['bdp_edd_price_font_line_height'] : '';
					$bdp_edd_price_font_italic          = isset( $bdp_settings['bdp_edd_price_font_italic'] ) ? $bdp_settings['bdp_edd_price_font_italic'] : '';
					$bdp_edd_price_font_text_decoration = isset( $bdp_settings['bdp_edd_price_font_text_decoration'] ) ? $bdp_settings['bdp_edd_price_font_text_decoration'] : 'none';
					$bdp_edd_price_font_letter_spacing  = isset( $bdp_settings['bdp_edd_price_font_letter_spacing'] ) ? $bdp_settings['bdp_edd_price_font_letter_spacing'] : '0';
					/**
					 * Edd Add To Cart Button
					 */
					$bdp_edd_addtocart_textcolor                      = isset( $bdp_settings['bdp_edd_addtocart_textcolor'] ) ? $bdp_settings['bdp_edd_addtocart_textcolor'] : '';
					$bdp_edd_addtocart_backgroundcolor                = isset( $bdp_settings['bdp_edd_addtocart_backgroundcolor'] ) ? $bdp_settings['bdp_edd_addtocart_backgroundcolor'] : '';
					$bdp_edd_addtocart_text_hover_color               = isset( $bdp_settings['bdp_edd_addtocart_text_hover_color'] ) ? $bdp_settings['bdp_edd_addtocart_text_hover_color'] : '';
					$bdp_edd_addtocart_hover_backgroundcolor          = isset( $bdp_settings['bdp_edd_addtocart_hover_backgroundcolor'] ) ? $bdp_settings['bdp_edd_addtocart_hover_backgroundcolor'] : '';
					$bdp_edd_addtocartbutton_borderleft               = isset( $bdp_settings['bdp_edd_addtocartbutton_borderleft'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderleft'] : '';
					$bdp_edd_addtocartbutton_borderleftcolor          = isset( $bdp_settings['bdp_edd_addtocartbutton_borderleftcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderleftcolor'] : '';
					$bdp_edd_addtocartbutton_borderright              = isset( $bdp_settings['bdp_edd_addtocartbutton_borderright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderright'] : '';
					$bdp_edd_addtocartbutton_borderrightcolor         = isset( $bdp_settings['bdp_edd_addtocartbutton_borderrightcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderrightcolor'] : '';
					$bdp_edd_addtocartbutton_bordertop                = isset( $bdp_settings['bdp_edd_addtocartbutton_bordertop'] ) ? $bdp_settings['bdp_edd_addtocartbutton_bordertop'] : '';
					$bdp_edd_addtocartbutton_bordertopcolor           = isset( $bdp_settings['bdp_edd_addtocartbutton_bordertopcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_bordertopcolor'] : '';
					$bdp_edd_addtocartbutton_borderbuttom             = isset( $bdp_settings['bdp_edd_addtocartbutton_borderbuttom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderbuttom'] : '';
					$bdp_edd_addtocartbutton_borderbottomcolor        = isset( $bdp_settings['bdp_edd_addtocartbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_borderbottomcolor'] : '';
					$display_edd_addtocart_button_border_radius       = isset( $bdp_settings['display_edd_addtocart_button_border_radius'] ) ? $bdp_settings['display_edd_addtocart_button_border_radius'] : '';
					$bdp_edd_addtocartbutton_padding_leftright        = isset( $bdp_settings['bdp_edd_addtocartbutton_padding_leftright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_padding_leftright'] : '10';
					$bdp_edd_addtocartbutton_padding_topbottom        = isset( $bdp_settings['bdp_edd_addtocartbutton_padding_topbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_padding_topbottom'] : '10';
					$bdp_edd_addtocartbutton_margin_leftright         = isset( $bdp_settings['bdp_edd_addtocartbutton_margin_leftright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_margin_leftright'] : '10';
					$bdp_edd_addtocartbutton_margin_topbottom         = isset( $bdp_settings['bdp_edd_addtocartbutton_margin_topbottom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_margin_topbottom'] : '10';
					$bdp_edd_addtocartbutton_alignment                = isset( $bdp_settings['bdp_edd_addtocartbutton_alignment'] ) ? $bdp_settings['bdp_edd_addtocartbutton_alignment'] : 'left';
					$bdp_edd_addtocartbutton_hover_borderleft         = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderleft'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderleft'] : '';
					$bdp_edd_addtocartbutton_hover_borderleftcolor    = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderleftcolor'] : '';
					$bdp_edd_addtocartbutton_hover_borderright        = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderright'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderright'] : '';
					$bdp_edd_addtocartbutton_hover_borderrightcolor   = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderrightcolor'] : '';
					$bdp_edd_addtocartbutton_hover_bordertop          = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_bordertop'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_bordertop'] : '';
					$bdp_edd_addtocartbutton_hover_bordertopcolor     = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_bordertopcolor'] : '';
					$bdp_edd_addtocartbutton_hover_borderbuttom       = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderbuttom'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderbuttom'] : '';
					$bdp_edd_addtocartbutton_hover_borderbottomcolor  = isset( $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_edd_addtocartbutton_hover_borderbottomcolor'] : '';
					$display_edd_addtocart_button_border_hover_radius = isset( $bdp_settings['display_edd_addtocart_button_border_hover_radius'] ) ? $bdp_settings['display_edd_addtocart_button_border_hover_radius'] : '0';
					$bdp_edd_addtocart_button_top_box_shadow          = isset( $bdp_settings['bdp_edd_addtocart_button_top_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_top_box_shadow'] : '';
					$bdp_edd_addtocart_button_right_box_shadow        = isset( $bdp_settings['bdp_edd_addtocart_button_right_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_right_box_shadow'] : '';
					$bdp_edd_addtocart_button_bottom_box_shadow       = isset( $bdp_settings['bdp_edd_addtocart_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_bottom_box_shadow'] : '';
					$bdp_edd_addtocart_button_left_box_shadow         = isset( $bdp_settings['bdp_edd_addtocart_button_left_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_left_box_shadow'] : '';
					$bdp_edd_addtocart_button_box_shadow_color        = isset( $bdp_settings['bdp_edd_addtocart_button_box_shadow_color'] ) ? $bdp_settings['bdp_edd_addtocart_button_box_shadow_color'] : '';
					$bdp_edd_addtocart_button_hover_top_box_shadow    = isset( $bdp_settings['bdp_edd_addtocart_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_top_box_shadow'] : '';
					$bdp_edd_addtocart_button_hover_right_box_shadow  = isset( $bdp_settings['bdp_edd_addtocart_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_right_box_shadow'] : '';
					$bdp_edd_addtocart_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_edd_addtocart_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_bottom_box_shadow'] : '';
					$bdp_edd_addtocart_button_hover_left_box_shadow   = isset( $bdp_settings['bdp_edd_addtocart_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_left_box_shadow'] : '';
					$bdp_edd_addtocart_button_hover_box_shadow_color  = isset( $bdp_settings['bdp_edd_addtocart_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_edd_addtocart_button_hover_box_shadow_color'] : '';
					$bdp_edd_addtocart_button_fontface                = ( isset( $bdp_settings['bdp_edd_addtocart_button_fontface'] ) && '' != $bdp_settings['bdp_edd_addtocart_button_fontface'] ) ? $bdp_settings['bdp_edd_addtocart_button_fontface'] : '';
					if ( isset( $bdp_settings['bdp_edd_addtocart_button_fontface_font_type'] ) && 'Google Fonts' === $bdp_settings['bdp_edd_addtocart_button_fontface_font_type'] ) {
						$load_goog_font_blog[] = $bdp_edd_addtocart_button_fontface;
					}
					$bdp_edd_addtocart_button_fontsize             = ( isset( $bdp_settings['bdp_edd_addtocart_button_fontsize'] ) && '' != $bdp_settings['bdp_edd_addtocart_button_fontsize'] ) ? $bdp_settings['bdp_edd_addtocart_button_fontsize'] : 'inherit';
					$bdp_edd_addtocart_button_font_weight          = isset( $bdp_settings['bdp_edd_addtocart_button_font_weight'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_weight'] : '';
					$bdp_edd_addtocart_button_font_italic          = isset( $bdp_settings['bdp_edd_addtocart_button_font_italic'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_italic'] : '';
					$bdp_edd_addtocart_button_letter_spacing       = isset( $bdp_settings['bdp_edd_addtocart_button_letter_spacing'] ) ? $bdp_settings['bdp_edd_addtocart_button_letter_spacing'] : '0';
					$display_edd_addtocart_button_line_height      = isset( $bdp_settings['display_edd_addtocart_button_line_height'] ) ? $bdp_settings['display_edd_addtocart_button_line_height'] : '1.5';
					$bdp_edd_addtocart_button_font_text_transform  = isset( $bdp_settings['bdp_edd_addtocart_button_font_text_transform'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_text_transform'] : 'none';
					$bdp_edd_addtocart_button_font_text_decoration = isset( $bdp_settings['bdp_edd_addtocart_button_font_text_decoration'] ) ? $bdp_settings['bdp_edd_addtocart_button_font_text_decoration'] : 'none';

					$bdp_title_top_box_shadow    = isset( $bdp_settings['bdp_title_top_box_shadow'] ) ? $bdp_settings['bdp_title_top_box_shadow'] . 'px' : '0';
					$bdp_title_right_box_shadow  = isset( $bdp_settings['bdp_title_right_box_shadow'] ) ? $bdp_settings['bdp_title_right_box_shadow'] . 'px' : '0';
					$bdp_title_bottom_box_shadow = isset( $bdp_settings['bdp_title_bottom_box_shadow'] ) ? $bdp_settings['bdp_title_bottom_box_shadow'] . 'px' : '0';
					$bdp_title_box_shadow_color  = isset( $bdp_settings['bdp_title_box_shadow_color'] ) ? $bdp_settings['bdp_title_box_shadow_color'] : '';

					$bdp_content_top_box_shadow    = isset( $bdp_settings['bdp_content_top_box_shadow'] ) ? $bdp_settings['bdp_content_top_box_shadow'] . 'px' : '0';
					$bdp_content_right_box_shadow  = isset( $bdp_settings['bdp_content_right_box_shadow'] ) ? $bdp_settings['bdp_content_right_box_shadow'] . 'px' : '0';
					$bdp_content_bottom_box_shadow = isset( $bdp_settings['bdp_content_bottom_box_shadow'] ) ? $bdp_settings['bdp_content_bottom_box_shadow'] . 'px' : '0';
					$bdp_content_box_shadow_color  = isset( $bdp_settings['bdp_content_box_shadow_color'] ) ? $bdp_settings['bdp_content_box_shadow_color'] : '';

					$backgroundbg_image_src = isset( $bdp_settings['bdp_bg_image_src'] ) ? $bdp_settings['bdp_bg_image_src'] : '';

					include 'css/layout-dynamic-style.php';
					if ( '' != get_option( 'bdp_custom_google_fonts' ) ) {
						$sidebar = explode( ',', get_option( 'bdp_custom_google_fonts' ) );
						foreach ( $sidebar as $key => $value ) {
							$what_i_want           = substr( $value, strpos( $value, '=' ) + 1 );
							$load_goog_font_blog[] = $what_i_want;
						}
					}
					if ( ! empty( $load_goog_font_blog ) ) {
						$load_font_arr = array_values( array_unique( $load_goog_font_blog ) );
						foreach ( $load_font_arr as $font_family ) {
							if ( '' != $font_family ) {
								$set_base  = ( is_ssl() ) ? 'https://' : 'http://';
								$font_href = $set_base . 'fonts.googleapis.com/css?family=' . esc_html( $font_family ) . ':light,bold,100,200,300,400,500,600,700,800,900';
								wp_enqueue_style( 'bdp-google-fonts-' . $font_family, $font_href, false );
								?>
								<script type="text/javascript">
									var gfont = document.createElement("link"),
										before = document.getElementsByTagName("link")[0],
										loadHref = true;
									jQuery('head').find('*').each(function() {
										if (jQuery(this).attr('href') == '<?php echo esc_attr( $font_href ); ?>') {
											loadHref = false
										}
									});
									if (loadHref) {
										gfont.href = '<?php echo esc_attr( $font_href ); ?>';
										gfont.rel = 'stylesheet';
										gfont.type = 'text/css';
										gfont.media = 'all';
										before.parentNode.insertBefore(gfont, before);
									}
								</script>
								<?php
							}
						}
					}
				}
			}
		}
	}
	/**
	 * Email Share
	 *
	 * @return void
	 */
	public function bdp_email_share() {
		?>
		<div id="bdp_email_share" class="bdp_email_share" style="display: none;">
			<div class="bdp-close"><i class="fas fa-times"></i></div>
			<div class="bdp_email_form">
				<form method="post" id="frmEmailShare">
					<input type="hidden" value="" name="txtShortcodeId" id="txtShortcodeId" />
					<input type="hidden" value="" name="txtPostId" id="txtPostId" />
					<input type="hidden" name="action" value="bdp_email_share_form" />
					<div>
						<label for="txtToEmail"><?php esc_html_e( 'Send to Email Address', 'blog-designer-pro' ); ?></label>
						<input id="txtToEmail" name="txtToEmail" type="email">
					</div>
					<div>
						<label for="txtYourName"><?php esc_html_e( 'Your Name', 'blog-designer-pro' ); ?></label>
						<input id="txtYourName" name="txtYourName" type="text">
					</div>
					<div>
						<label for="txtYourEmail"><?php esc_html_e( 'Your Email Address', 'blog-designer-pro' ); ?></label>
						<input id="txtYourEmail" name="txtYourEmail" type="email">
					</div>
					<div>
						<input class="bdp-mail_submit_button" type="submit" name="sbtEmailShare" value="<?php esc_html_e( 'Send Email', 'blog-designer-pro' ); ?>" />
						<div class="bdp-close_button">Close</div>
					</div>
				</form>
			</div>
			<div class="bdp_email_sucess"></div>
		</div>
		<?php
	}
	/**
	 * Template Dynaimc Script
	 *
	 * @return void
	 */
	public function bdp_template_dynamic_script() {
		global $post;
		$archive_list       = Bdp_Template::get_archive_list();
		$bdp_settings_array = array();

		if ( is_archive() ) {

			if ( is_date() && in_array( 'date_template', $archive_list ) ) {
				$date                               = Bdp_Template::get_date_template_settings();
				$all_setting                        = $date->settings;
				$bdp_settings                       = maybe_unserialize( $all_setting );
				$bdp_settings_array['no_shortcode'] = $bdp_settings;
			} elseif ( is_author() && in_array( 'author_template', $archive_list ) ) {
				$author_id                          = get_query_var( 'author' );
				$bdp_author_data                    = Bdp_Author::get_author_template_settings( $author_id, $archive_list );
				$archive_id                         = $bdp_author_data['id'];
				$bdp_settings                       = $bdp_author_data['bdp_settings'];
				$bdp_settings_array['no_shortcode'] = $bdp_settings;
			} elseif ( is_category() && in_array( 'category_template', $archive_list ) ) {
				$categories                         = get_category( get_query_var( 'cat' ) );
				$category_id                        = $categories->cat_ID;
				$bdp_category_data                  = Bdp_Template::get_category_template_settings( $category_id, $archive_list );
				$archive_id                         = $bdp_category_data['id'];
				$bdp_settings                       = $bdp_category_data['bdp_settings'];
				$bdp_settings_array['no_shortcode'] = $bdp_settings;
			} elseif ( is_tag() && in_array( 'tag_template', $archive_list ) ) {
				$tag_id                             = get_query_var( 'tag_id' );
				$bdp_tag_data                       = Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
				$archive_id                         = $bdp_tag_data['id'];
				$bdp_settings                       = $bdp_tag_data['bdp_settings'];
				$bdp_settings_array['no_shortcode'] = $bdp_settings;
			}
			/**
			 * Get Woocommerce category and tag page setting data
			 *
			 * @since 2.6
			 */
			if ( ( Bdp_Woocommerce::is_woocommerce_plugin() || class_exists( 'woocommerce' ) ) && ( is_product_category() || is_product_tag() ) ) {
				$archive_list = Bdp_Woocommerce::get_product_archive_list();
				if ( is_product_category() && in_array( 'category_template', $archive_list ) ) {
					$categories        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id       = $categories->term_id;
					$bdp_category_data = Bdp_Template::get_product_category_template_settings( $category_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];

					$bdp_settings_array['no_shortcode'] = $bdp_settings;
				} elseif ( is_product_tag() && in_array( 'tag_template', $archive_list ) ) {
					$tags         = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$tag_id       = $tags->term_id;
					$bdp_tag_data = Bdp_Template::get_product_tag_template_settings( $tag_id, $archive_list );
					$archive_id   = $bdp_tag_data['id'];

					$bdp_settings                       = $bdp_tag_data['bdp_settings'];
					$bdp_settings_array['no_shortcode'] = $bdp_settings;
				}
			}

			/**
			 * Get Woocommerce category and tag setting Data
			 *
			 * @since 2.7
			 */
			if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
				$archive_list = Bdp_Edd::get_download_archive_list();
				if ( is_tax( 'download_category' ) && in_array( 'category_template', $archive_list ) ) {
					$categories                         = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id                        = $categories->term_id;
					$bdp_category_data                  = Bdp_Edd::get_download_category_template_settings( $category_id, $archive_list );
					$archive_id                         = $bdp_category_data['id'];
					$bdp_settings                       = $bdp_category_data['bdp_settings'];
					$bdp_settings_array['no_shortcode'] = $bdp_settings;
				}
				if ( is_tax( 'download_tag' ) && in_array( 'tag_template', $archive_list ) ) {
					$categories                         = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id                        = $categories->term_id;
					$bdp_category_data                  = Bdp_Template::get_download_tag_template_settings( $category_id, $archive_list );
					$archive_id                         = $bdp_category_data['id'];
					$bdp_settings                       = $bdp_category_data['bdp_settings'];
					$bdp_settings_array['no_shortcode'] = $bdp_settings;
				}
			}
		} elseif ( is_search() && in_array( 'search_template', $archive_list ) ) {
			$search_settings                    = Bdp_Template::get_search_template_settings();
			$allsettings                        = $search_settings->settings;
			$bdp_settings                       = maybe_unserialize( $allsettings );
			$bdp_settings_array['no_shortcode'] = $bdp_settings;
		} else {
			$bdp_themes    = self::$template_name;
			$shortcode_ids = self::$shortcode_id;
			if ( is_array( $shortcode_ids ) && count( $shortcode_ids ) > 0 ) {
				$dyn_script = 0;
				foreach ( $shortcode_ids as $shortcode_id ) {
					$bdp_theme                           = $bdp_themes[ $dyn_script ];
					$bdp_settings                        = Bdp_Template::get_shortcode_settings( $shortcode_id );
					$bdp_settings_array[ $shortcode_id ] = $bdp_settings;
					++$dyn_script;
				}
			}
		}
		if ( isset( $bdp_settings_array ) && is_array( $bdp_settings_array ) && ! empty( $bdp_settings_array ) ) {
			$dyn_script = 0;
			foreach ( $bdp_settings_array as $shortcode_id => $bdp_settings ) {
				$page_edit_id   = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : 0;
				$elementor_page = ! empty( $page_edit_id ) ? get_post_meta( $page_edit_id, '_elementor_edit_mode', true ) : '';
				if ( ! empty( $elementor_page ) ) {
					if ( 'accordion' === $bdp_settings['template_name'] ) {
						echo '<script src="' . esc_url( includes_url( '/js/jquery/ui/accordion.min.js' ) ) . '"></script>';
						echo '<script src="' . esc_url( plugins_url( 'js/ajax.js', __FILE__ ) ) . '" id="bdp-ajax-script-js"></script>';
					}
					if ( 'cool_horizontal' === $bdp_settings['template_name'] || 'overlay_horizontal' === $bdp_settings['template_name'] ) {
						echo '<script src="' . esc_url( plugins_url( 'js/logbook.js', __FILE__ ) ) . '" id="bdp-logbook-script-js"></script>';
						echo '<script src="' . esc_url( plugins_url( 'js/jquery.easing.js', __FILE__ ) ) . '" id="bdp-easing-script-js"></script>';

					}
				}

				if ( 'crayon_slider' === $bdp_settings['template_name'] || 'sunshiny_slider' === $bdp_settings['template_name'] || 'sallet_slider' === $bdp_settings['template_name'] || 'colorful_sliding' === $bdp_settings['template_name'] || 'blog_carousel' === $bdp_settings['template_name'] || 'threed_carousel' === $bdp_settings['template_name'] || 'flip_book_3d' === $bdp_settings['template_name'] ) {
					$templatename = $bdp_settings['template_name'];
					$list         = 'enqueued';
					if ( ! wp_script_is( 'bdp-galleryimage-script', $list ) ) {
						wp_enqueue_script( 'bdp-galleryimage-script' );
					}
					if ( ! empty( $elementor_page ) ) {
						echo '<script src="' . esc_url( plugins_url( 'js/jquery.flexslider-min.js', __FILE__ ) ) . '" id="bdp-galleryimage-script"></script>';
					}
					$post_number               = isset( $bdp_settings['bdp_number_post'] ) ? $bdp_settings['bdp_number_post'] : 5;
					$width                     = isset( $bdp_settings['slider_custom_width'] ) ? $bdp_settings['slider_custom_width'] : 400;
					$height                    = isset( $bdp_settings['slider_custom_height'] ) ? $bdp_settings['slider_custom_height'] : 400;
					$distance                  = isset( $bdp_settings['slider_distance'] ) ? $bdp_settings['slider_distance'] : 150;
					$template_slider_scroll    = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : 1;
					$display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : 1;
					$display_slider_controls   = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 1;
					$slider_autoplay           = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : 1;
					$slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : 7000;
					$slider_speed              = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : 600;
					$thumbnail_slider_speed    = isset( $bdp_settings['thumbnail_slider_speed'] ) ? $bdp_settings['thumbnail_slider_speed'] : 600;
					$template_slider_effect    = isset( $bdp_settings['template_slider_effect'] ) ? $bdp_settings['template_slider_effect'] : 'slide';
					$total_noofthumb           = isset( $bdp_settings['total_noofthumb'] ) ? $bdp_settings['total_noofthumb'] : 6;
					$template_lazy_load_color  = isset( $bdp_settings['template_lazy_load_color'] ) ? $bdp_settings['template_lazy_load_color'] : '';

					if ( is_rtl() ) {
						$template_slider_effect = 'fade';
					}
					$slider_column = 1;
					if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
						$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
						$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
						$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
						$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;
					} else {
						$slider_column        = 1;
						$slider_column_ipad   = 1;
						$slider_column_tablet = 1;
						$slider_column_mobile = 1;
					}
					$slider_arrow = isset( $bdp_settings['arrow_style_hidden'] ) ? $bdp_settings['arrow_style_hidden'] : 'arrow1';
					if ( '' == $slider_arrow ) {
						$prev = "<i class='fas fa-chevron-left'></i>";
						$next = "<i class='fas fa-chevron-right'></i>";
					} else {
						$prev = "<div class=' prev-arrow " . $slider_arrow . "'></div>";
						$next = "<div class=' next-arrow " . $slider_arrow . "'></div>";
					}

					if ( 'leest' === $bdp_settings['template_name'] ) {

						if ( 'leest' == isset( $bdp_settings['template_name'] ) ) {
							$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
							if ( 1 == $display_slider_controls ) {
								?>
								<span data-role="none" class="slick-prev <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>
								<span data-role="none" class="slick-next <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>
								<?php
							}
						}
						?>
						<script type="text/javascript" id="slick_script">								
								$ = jQuery;
								$(document).ready(function() {
									$(".slick-slider").slick({
										slidesToShow: 3,
										infinite: false,
										slidesToScroll: 1,
										autoplay: true,
										autoplaySpeed: 2000,
										dots: true,
										arrows: true,
										responsive: [
											{
												breakpoint: 991,
												settings: {
													slidesToShow: 2,
													slidesToScroll: 1,
													infinite: true,
													dots: true
												}
											},
											{
												breakpoint: 575,
												settings: {
													slidesToShow: 1,
													slidesToScroll: 2
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
					} elseif ( 'threed_carousel' === $bdp_settings['template_name'] ) {
						if ( 'threed_carousel' == isset( $bdp_settings['template_name'] ) ) {
							$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
							if ( 1 == $display_slider_controls ) {
								?>
								<span data-role="none" class="pd-arrows left pd-left-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>
								<span data-role="none" class="pd-arrows right pd-right-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>
								<?php
							}
						}
						?>
						<script type="text/javascript" id="carousel_script">
							$ = jQuery;
							jQuery(document).ready(function() {
								var bdp_asssigned_slider = $('.slider_template');
								if ('<?php echo esc_attr( $shortcode_id ); ?>' != 'no_shortcode') {
									bdp_asssigned_slider = $('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .slider_template.<?php echo esc_attr( $templatename ); ?>')
								}
								jQuery('.threed_carousel .bdp-3d-container').carousel({
									num: <?php echo esc_attr( $post_number ); ?>,
									maxWidth: <?php echo esc_attr( $width ); ?>,
									maxHeight: <?php echo esc_attr( $height ); ?>,
									distance: <?php echo esc_attr( $distance ); ?>,
									scale: 0.8,

									<?php echo ( 1 == $slider_autoplay ) ? 'autoPlay: true,' : 'autoPlay: false,'; ?>
									<?php echo ( 1 == $slider_autoplay ) ? 'showTime:' . esc_attr( $slider_autoplay_intervals ) . ',' : ''; ?>
									<?php echo ( $slider_speed ) ? 'animationTime:' . esc_attr( $slider_speed ) . ',' : ''; ?>
									prevArrow: '<span data-role="none" class="bd-arrows bd-left-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>',


									nextArrow: '<span data-role="none" class="bd-arrows bd-right-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>',
								});

							});
						</script>
						<?php
					} elseif ( isset( $bdp_settings['template_name'] ) && 'flip_book_3d' === $bdp_settings['template_name'] ) {

						wp_enqueue_script( 'bdp-modernize-custom-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array( 'jquery' ), '2.6.2', true );

						wp_enqueue_script( 'bookblock-script', plugins_url( 'js/jquery.bookblock.js', __FILE__ ), array( 'jquery' ), '2.0.1', true );
						// global $post;
						// while ($the_query->have_posts()) {
						// $the_query->the_post();
						// $id = get_the_ID();
						// $flipbook_count = 1;
						// if( 1 == $flipbook_count % 2){
						// echo "<div class='post_hentry_1  .bb-custom-side '>";
						// }
						// if(0 == $flipbook_count % 2 || ($the_query->current_post +1) == ($the_query->post_count) ){
						// echo '</div>';
						// }
						// $flipbook_count ++;
						// }.
						?>
						<script type="text/javascript" id="bookblock-thumb-style">
							jQuery(document).ready(function($) {
								(function($) {
									var $maxItems = 1;
									var $thumbnail_display = <?php echo esc_attr( $total_noofthumb ); ?>;
									if ($(window).width() > 980) {
										$maxItems = <?php echo esc_attr( $slider_column ); ?>;
									} else if ($(window).width() <= 980 && $(window).width() > 720) {
										$maxItems = <?php echo esc_attr( $slider_column_ipad ); ?>;
									} else if ($(window).width() <= 720 && $(window).width() > 480) {
										$maxItems = <?php echo esc_attr( $slider_column_tablet ); ?>;
									} else if ($(window).width() <= 480) {
										$maxItems = <?php echo esc_attr( $slider_column_mobile ); ?>;
									}
									var bdp_asssigned_slider = $('.slider_template');

									if ('<?php echo esc_attr( $shortcode_id ); ?>' != 'no_shortcode') {
										bdp_asssigned_slider = $('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .slider_template.<?php echo esc_attr( $templatename ); ?>')
									}

									$('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .post_slider_thumbnail').flexslider({
										animation: '<?php echo esc_attr( $template_slider_effect ); ?>',
										itemWidth: 210,
										itemMargin: 15,
										maxItems: $thumbnail_display,
										controlNav: false,
										animationLoop: false,
										prevText: "<?php echo wp_kses( $prev, Bdp_Admin_Functions::args_kses() ); ?>",
										nextText: "<?php echo wp_kses( $next, Bdp_Admin_Functions::args_kses() ); ?>",
										directionNav: true,
										<?php echo ( $thumbnail_slider_speed ) ? 'animationSpeed:' . esc_attr( $thumbnail_slider_speed ) . ',' : ''; ?>
										asNavFor: bdp_asssigned_slider,
									});

								}(jQuery))

							});
						</script>
						<script type="text/javascript" id="bookblock-script">
							jQuery(document).ready(function($) {
								var Page = (function() {
									var config = {
											$bookBlock: $('.bdp_flip_book_3d'),
											$navNext: $('#bb-nav-next'),
											$navPrev: $('#bb-nav-prev'),
											$navFirst: $('#bb-nav-first'),
											$navLast: $('#bb-nav-last')
										},

										init = function() {
											config.$bookBlock.bookblock({
												speed: 3000,
												shadowSides: 0.8,
												shadowFlip: 0.7
											});
											initEvents();
										},
										initEvents = function() {

											var $slides = config.$bookBlock.children();

											// add navigation events
											config.$navNext.on('click touchstart', function() {
												config.$bookBlock.bookblock('next');
												return false;
											});

											config.$navPrev.on('click touchstart', function() {
												config.$bookBlock.bookblock('prev');
												return false;
											});

											config.$navFirst.on('click touchstart', function() {
												config.$bookBlock.bookblock('first');
												return false;
											});

											config.$navLast.on('click touchstart', function() {
												config.$bookBlock.bookblock('last');
												return false;
											});

											// add swipe events
											$slides.on({
												'swipeleft': function(event) {
													config.$bookBlock.bookblock('next');
													return false;
												},
												'swiperight': function(event) {
													config.$bookBlock.bookblock('prev');
													return false;
												}
											});

											// add keyboard events
											$(document).keydown(function(e) {
												var keyCode = e.keyCode || e.which,
													arrow = {
														left: 37,
														up: 38,
														right: 39,
														down: 40
													};

												switch (keyCode) {
													case arrow.left:
														config.$bookBlock.bookblock('prev');
														break;
													case arrow.right:
														config.$bookBlock.bookblock('next');
														break;
												}
											});
										};

									return {
										init: init
									};
								})(jQuery);
								Page.init();
							});
						</script>



						<?php
					} else {

						?>


						<script type="text/javascript" id="flexslider_script">
							jQuery(document).ready(function() {

								(function($) {
									var $maxItems = 1;
									var $thumbnail_display = <?php echo esc_attr( $total_noofthumb ); ?>;
									if ($(window).width() > 980) {
										$maxItems = <?php echo esc_attr( $slider_column ); ?>;
									} else if ($(window).width() <= 980 && $(window).width() > 720) {
										$maxItems = <?php echo esc_attr( $slider_column_ipad ); ?>;
									} else if ($(window).width() <= 720 && $(window).width() > 480) {
										$maxItems = <?php echo esc_attr( $slider_column_tablet ); ?>;
									} else if ($(window).width() <= 480) {
										$maxItems = <?php echo esc_attr( $slider_column_mobile ); ?>;
									}
									var bdp_asssigned_slider = $('.slider_template');

									if ('<?php echo esc_attr( $shortcode_id ); ?>' != 'no_shortcode') {
										bdp_asssigned_slider = $('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .slider_template.<?php echo esc_attr( $templatename ); ?>')
									}

									$('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .post_slider_thumbnail').flexslider({
										animation: '<?php echo esc_attr( $template_slider_effect ); ?>',
										itemWidth: 210,
										itemMargin: 15,
										maxItems: $thumbnail_display,
										controlNav: false,
										animationLoop: false,
										prevText: "<?php echo wp_kses( $prev, Bdp_Admin_Functions::args_kses() ); ?>",
										nextText: "<?php echo wp_kses( $next, Bdp_Admin_Functions::args_kses() ); ?>",
										directionNav: true,
										<?php echo ( $thumbnail_slider_speed ) ? 'animationSpeed:' . esc_attr( $thumbnail_slider_speed ) . ',' : ''; ?>
										asNavFor: bdp_asssigned_slider,
									});
									bdp_asssigned_slider.flexslider({
										move: <?php echo esc_attr( $template_slider_scroll ); ?>,
										animation: '<?php echo esc_attr( $template_slider_effect ); ?>',
										itemWidth: 10,
										itemMargin: 15,
										minItems: 1,
										maxItems: $maxItems,
										<?php echo ( 1 == $display_slider_controls ) ? 'directionNav: true,' : 'directionNav: false,'; ?>
										<?php echo ( 1 == $display_slider_navigation ) ? 'controlNav: true,' : 'controlNav: false,'; ?>
										<?php echo ( 1 == $slider_autoplay ) ? 'slideshow: true,' : 'slideshow: false,'; ?>
										<?php echo ( 1 == $slider_autoplay ) ? 'slideshowSpeed:' . esc_attr( $slider_autoplay_intervals ) . ',' : ''; ?>
										<?php echo ( $slider_speed ) ? 'animationSpeed:' . esc_attr( $slider_speed ) . ',' : ''; ?>
										prevText: "<?php echo wp_kses( $prev, Bdp_Admin_Functions::args_kses() ); ?>",
										nextText: "<?php echo wp_kses( $next, Bdp_Admin_Functions::args_kses() ); ?>",
										sync: '.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .post_slider_thumbnail',
										rtl: 
										<?php
										if ( is_rtl() ) {
											echo 1;
										} else {
											echo 0;
										}
										?>
									});
								}(jQuery))
							});
						</script>
						<?php
					}
				}

				if ( 'cool_horizontal' === $bdp_settings['template_name'] || 'overlay_horizontal' === $bdp_settings['template_name'] ) {
					?>
					<script class="logbook_script">
						(function($) {
							var bdp_asssigned_slider = $('.logbook');
							if ('<?php echo esc_attr( $shortcode_id ); ?>' != 'no_shortcode') {
								bdp_asssigned_slider = $('.layout_id_<?php echo esc_attr( $shortcode_id ); ?> .logbook')
							}
							bdp_asssigned_slider.logbook({
								levels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
								showYears: 10,
								del: 130,
								vertical: false,
								isPostLink: false,
								isYears: false,
								triggerWidth: 800,
								itemMargin: <?php echo ( isset( $bdp_settings['template_post_margin'] ) ) ? esc_attr( $bdp_settings['template_post_margin'] ) : 20; ?>,
								customSize: {
									"sheet": {
										"itemWidth": <?php echo ( isset( $bdp_settings['item_width'] ) ? esc_attr( $bdp_settings['item_width'] ) : 400 ); ?>,
										"itemHeight": "<?php echo ( isset( $bdp_settings['item_height'] ) ? esc_attr( $bdp_settings['item_height'] ) : 570 ); ?>",
										"margin": "<?php echo ( isset( $bdp_settings['template_post_margin'] ) && $bdp_settings['template_post_margin'] ) ? esc_attr( $bdp_settings['template_post_margin'] ) : 20; ?>"
									},
									"active": {
										"itemWidth": <?php echo ( isset( $bdp_settings['item_width'] ) ? esc_attr( $bdp_settings['item_width'] ) : 400 ); ?>,
										"itemHeight": "<?php echo ( isset( $bdp_settings['item_height'] ) ? esc_attr( $bdp_settings['item_height'] ) : 570 ); ?>",
										"imageHeight": "150"
									}
								},
								id: 10,
								easing: '<?php echo esc_attr( $bdp_settings['template_easing'] ); ?>',
								enableSwipe: true,
								startFrom: '<?php echo ( isset( $bdp_settings['timeline_start_from'] ) ) ? esc_attr( $bdp_settings['timeline_start_from'] ) : 'last'; ?>',
								enableYears: true,
								class: {
									readMore: '.lb-read-more',
								},
								hideLogbook: <?php echo ( 1 == $bdp_settings['display_timeline_bar'] ) ? 'true' : 'false'; ?>,
								hideArrows: false,
								closeItemOnTransition: false,
								autoplay: <?php echo ( 1 == $bdp_settings['enable_autoslide'] ) ? 'true' : 'false'; ?>,
								scrollSpeed: <?php echo isset( $bdp_settings['scroll_speed'] ) ? esc_attr( $bdp_settings['scroll_speed'] ) : 1000; ?>,
							});
						})(jQuery);
					</script>
					<?php
				}
				++$dyn_script;
			}
		}
	}

	/**
	 * Add shortcode
	 *
	 * @param array $atts atts.
	 * @return html
	 */
	public function bdp_shortcode_function( $atts ) {
		global $wpdb;
		ob_start();
		if ( ! isset( $atts['id'] ) || empty( $atts['id'] ) ) {
			return '<b style="color:#ff0000">' . esc_html__( 'Error', 'blog-designer-pro' ) . ' : </b>' . esc_html__( 'Blog Designer shortcode not found. Please cross check your Layout selection id.', 'blog-designer-pro' ) . '';
		}
		$table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		if ( is_numeric( $atts['id'] ) ) {
			$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $atts['id'] ), ARRAY_A );
		}
		if ( ! $settings_val ) {
			return '[wp_blog_designer] ' . esc_html__( 'Invalid shortcode', 'blog-designer-pro' ) . '';
		}
		$allsettings = $settings_val[0]['bdsettings'];
		if ( is_serialized( $allsettings ) ) {
			$bdp_settings = maybe_unserialize( $allsettings );
		}
		if ( ! isset( $bdp_settings['template_name'] ) || empty( $bdp_settings['template_name'] ) ) {
			return '[wp_blog_designer] ' . esc_html__( 'Invalid shortcode', 'blog-designer-pro' ) . '';
		}
		self::$template_name[] = $bdp_settings['template_name'];
		self::$shortcode_id[]  = $atts['id'];
		echo Bdp_Template::layout_view_portion( $atts['id'], $bdp_settings );
		$output_string = ob_get_contents();
		wp_reset_query();
		ob_end_clean();
		return $output_string;
	}
	/**
	 * Custom Single Template
	 *
	 *  @param string $single_template single temlate.
	 *  @return int $single_template custom template
	 */
	public function bdp_custom_single_template( $single_template ) {
		global $post;
		$post_type = $post->post_type;
		if ( 'post' === $post_type ) {
			$post_id     = $post->ID;
			$cat_ids     = wp_get_post_categories( $post_id );
			$tag_ids     = wp_get_post_tags( $post_id );
			$single_data = Bdp_Template::get_single_template_settings( $cat_ids, $tag_ids );
			if ( ! $single_data ) {
				return $single_template;
			}
			if ( $single_data && is_serialized( $single_data ) ) {
				$single_data_setting = maybe_unserialize( $single_data );
			}
			if ( ! isset( $single_data_setting['template_name'] ) || ( isset( $single_data_setting['template_name'] ) && '' == $single_data_setting['template_name'] ) ) {
				return $single_template;
			}
			if ( isset( $single_data_setting['override_single'] ) && 1 == $single_data_setting['override_single'] ) {
				if ( 'post' === $post_type ) {
					$single_template = get_stylesheet_directory() . '/bdp_templates/single/single.php';
					if ( ! file_exists( $single_template ) ) {
						$single_template = BLOGDESIGNERPRO_DIR . 'bdp_templates/single/single.php';
					}
					$single_template = apply_filters( 'custom_single_post_template', $single_template, $single_data_setting );
				}
			}
		} elseif ( 'product' === $post_type ) {
			/**
			 * Apply bdp single product layout file
			 *
			 * @since 2.6
			 */
			$post_id     = $post->ID;
			$cat_ids     = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
			$tag_ids     = wp_get_post_terms( $post_id, 'product_tag', array( 'fields' => 'ids' ) );
			$single_data = Bdp_Template::get_single_prodcut_template_settings( $cat_ids, $tag_ids );
			if ( ! $single_data ) {
				return $single_template;
			}
			if ( $single_data && is_serialized( $single_data ) ) {
				$single_data_setting = maybe_unserialize( $single_data );
			}
			if ( ! isset( $single_data_setting['template_name'] ) || ( isset( $single_data_setting['template_name'] ) && '' == $single_data_setting['template_name'] ) ) {
				return $single_template;
			}
			if ( isset( $single_data_setting['override_single'] ) && 1 == $single_data_setting['override_single'] ) {
				$single_template = get_stylesheet_directory() . '/bdp_templates/woocommerce/single/single-product.php';
				if ( ! file_exists( $single_template ) ) {
					$single_template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/single/single-product.php';
				}
				add_filter( 'template_include', array( 'Bdp_Woocommerce', 'custom_single_product_template' ), 999 );
			}
		} elseif ( 'download' === $post_type ) {
			/**
			 * Apply bdp single product layout file
			 *
			 * @since 2.7
			 */
			$post_id     = $post->ID;
			$cat_ids     = wp_get_post_terms( $post_id, 'download_category', array( 'fields' => 'ids' ) );
			$tag_ids     = wp_get_post_terms( $post_id, 'download_tag', array( 'fields' => 'ids' ) );
			$single_data = Bdp_Template::get_single_download_template_settings( $cat_ids, $tag_ids );
			if ( ! $single_data ) {
				return $single_template;
			}
			if ( $single_data && is_serialized( $single_data ) ) {
				$single_data_setting = maybe_unserialize( $single_data );
			}
			if ( ! isset( $single_data_setting['template_name'] ) || ( isset( $single_data_setting['template_name'] ) && '' == $single_data_setting['template_name'] ) ) {
				return $single_template;
			}
			if ( isset( $single_data_setting['override_single'] ) && 1 == $single_data_setting['override_single'] ) {
				$single_template = get_stylesheet_directory() . '/bdp_templates/edd_templates/single/single-download.php';
				if ( ! file_exists( $single_template ) ) {
					$single_template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/single/single-download.php';
				}
				add_filter( 'template_include', array( 'Bdp_Edd', 'custom_single_download_template' ), 999 );
			}
		}
		return $single_template;
	}
	/**
	 * Get custom archive template
	 *
	 * @global object $wpdb
	 * @global object $wpdb
	 * @param type $template template.
	 * @return Archive template
	 */
	public function bdp_get_custom_archive_template( $template ) {

		$archive_list = Bdp_Template::get_archive_list();
		if ( is_search() && in_array( 'search_template', $archive_list ) ) {
			$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
			if ( ! file_exists( $template ) ) {
				$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
			}
			$template = apply_filters( 'bdp_archive_template', $template );
		} else {

			if ( ( is_date() && in_array( 'date_template', $archive_list ) ) ) {
				$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
				if ( ! file_exists( $template ) ) {
					$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
				}
			} elseif ( is_author() && in_array( 'author_template', $archive_list ) ) {
				$author_id = get_query_var( 'author' );
				foreach ( $archive_list as $archive ) {
					global $wpdb;
					$author_template = '';
					if ( is_numeric( $author_id ) ) {
						$author_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE archive_template = 'author_template' AND find_in_set(%d,sub_categories) <> 0", $author_id ) );
					}
					if ( ! empty( $author_template ) ) {
						$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
						if ( ! file_exists( $template ) ) {
							$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
						}
					} else {
						$author_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "author_template" AND sub_categories = ""' );
						if ( ! empty( $author_template ) ) {
							$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
							if ( ! file_exists( $template ) ) {
								$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
							}
						}
					}
				}
			} elseif ( is_category() && in_array( 'category_template', $archive_list ) ) {
				$categories  = get_category( get_query_var( 'cat' ) );
				$category_id = $categories->cat_ID;
				foreach ( $archive_list as $archive ) {
					if ( 'category_template' === $archive ) {
						global $wpdb;
						$category_template = '';
						if ( is_numeric( $category_id ) ) {
							$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE archive_template = 'category_template' AND find_in_set(%d,sub_categories) <> 0", $category_id ) );
						}
						if ( ! empty( $category_template ) ) {
							$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
							if ( ! file_exists( $template ) ) {
								$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
							}
						} else {
							$category_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "category_template" AND sub_categories = ""' );
							if ( ! empty( $category_template ) ) {
								$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
								}
							}
						}
					}
				}
			} elseif ( is_tag() && in_array( 'tag_template', $archive_list ) ) {
				$tag_id = get_query_var( 'tag_id' );
				foreach ( $archive_list as $archive ) {
					if ( 'tag_template' === $archive ) {
						global $wpdb;
						if ( is_numeric( $tag_id ) ) {
							$tag_templates = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE archive_template = 'tag_template' AND find_in_set(%d,sub_categories) <> 0", $tag_id ) );
						}
						if ( isset( $tag_templates ) && $tag_templates ) {
							$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
							if ( ! file_exists( $template ) ) {
								$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
							}
						} else {
							$tag_templates = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "tag_template" AND sub_categories = ""' );
							if ( $tag_templates ) {
								$template = get_stylesheet_directory() . '/bdp_templates/archive/archive.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/archive.php';
								}
							}
						}
					}
				}
			}

			/**
			 * Applay product post type archive layout
			 *
			 * @since 2.6
			 */
			if ( Bdp_Woocommerce::is_woocommerce_plugin() && ( is_product_category() || is_product_tag() ) ) {
				$product_archive_list = Bdp_Woocommerce::get_product_archive_list();
				if ( is_product_category() && in_array( 'category_template', $product_archive_list ) ) {
					$categories  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id = $categories->term_id;
					foreach ( $product_archive_list as $archive ) {
						if ( 'category_template' === $archive ) {
							global $wpdb;
							if ( is_numeric( $category_id ) ) {
								$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives WHERE product_archive_template = 'category_template' AND find_in_set(%d, product_sub_categories) <> 0", $category_id ) );
							}
							if ( ! empty( $category_template ) ) {
								$template = get_stylesheet_directory() . '/bdp_templates/woocommerce/archive/archive-product.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/archive-product.php';
								}
							} else {
								$category_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE product_archive_template = "category_template" AND product_sub_categories = ""' );
								if ( ! empty( $category_template ) ) {
									$template = get_stylesheet_directory() . '/bdp_templates/woocommerce/archive/archive-product.php';
									if ( ! file_exists( $template ) ) {
										$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/archive-product.php';
									}
								}
							}
						}
					}
				} elseif ( is_product_tag() && in_array( 'tag_template', $product_archive_list ) ) {
					$tags   = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$tag_id = $tags->term_id;
					foreach ( $product_archive_list as $archive ) {
						if ( 'tag_template' === $archive ) {
							global $wpdb;
							if ( is_numeric( $tag_id ) ) {
								$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives WHERE product_archive_template = 'tag_template' AND find_in_set(%d, product_sub_categories) <> 0", $tag_id ) );
							}
							if ( ! empty( $tag_template ) ) {
								$template = get_stylesheet_directory() . '/bdp_templates/woocommerce/archive/archive-product.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/archive-product.php';
								}
							} else {
								$tag_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_product_archives WHERE product_archive_template = "tag_template" AND product_sub_categories = ""' );
								if ( ! empty( $tag_template ) ) {
									$template = get_stylesheet_directory() . '/bdp_templates/woocommerce/archive/archive-product.php';
									if ( ! file_exists( $template ) ) {
										$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/archive-product.php';
									}
								}
							}
						}
					}
				}
			}
			/**
			 * Applay download post type archive layout
			 *
			 * @since 2.7
			 */
			if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
				$download_archive_list = Bdp_Edd::get_download_archive_list();
				if ( is_tax( 'download_category' ) && in_array( 'category_template', $download_archive_list ) ) {
					$categories  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id = $categories->term_id;

					foreach ( $download_archive_list as $archive ) {
						if ( 'category_template' === $archive ) {
							global $wpdb;
							if ( is_numeric( $category_id ) ) {
								$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_edd_archives WHERE download_archive_template = 'category_template' AND find_in_set(%d, download_sub_categories) <> 0", $category_id ) );
							}
							if ( ! empty( $category_template ) ) {
								$template = get_stylesheet_directory() . '/bdp_templates/edd_templates/archive/archive-download.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/archive/archive-download.php';
								}
							} else {
								$category_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_edd_archives WHERE download_archive_template = "category_template" AND download_sub_categories = ""' );
								if ( ! empty( $category_template ) ) {
									$template = get_stylesheet_directory() . '/bdp_templates/edd_templates/archive/archive-download.php';
									if ( ! file_exists( $template ) ) {
										$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/archive/archive-download.php';
									}
								}
							}
						}
					}
				}
				if ( is_tax( 'download_tag' ) && in_array( 'tag_template', $download_archive_list ) ) {
					$categories  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id = $categories->term_id;
					foreach ( $download_archive_list as $archive ) {
						if ( 'tag_template' === $archive ) {
							global $wpdb;
							if ( is_numeric( $category_id ) ) {
								$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_edd_archives WHERE download_archive_template = 'tag_template' AND find_in_set(%d, download_sub_categories) <> 0", $category_id ) );
							}
							if ( ! empty( $category_template ) ) {
								$template = get_stylesheet_directory() . '/bdp_templates/edd_templates/archive/archive-download.php';
								if ( ! file_exists( $template ) ) {
									$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/archive/archive-download.php';
								}
							} else {
								$category_template = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_edd_archives WHERE download_archive_template = "tag_template" AND download_sub_categories = ""' );
								if ( ! empty( $category_template ) ) {
									$template = get_stylesheet_directory() . '/bdp_templates/edd_templates/archive/archive-download.php';
									if ( ! file_exists( $template ) ) {
										$template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/archive/archive-download.php';
									}
								}
							}
						}
					}
				}
			}
			$template = apply_filters( 'bdp_archive_template', $template );
		}
		return $template;
	}
	/**
	 * To get posts when load more pagination is on
	 *
	 * @return void
	 */
	public function bdp_loadmore_blog() {
		global $wpdb;
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$layout = isset( $_POST['blog_layout'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['blog_layout'] ) ) ) : '';
			if ( 'blog_layout' === $layout ) {
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) ) : '';
				$table_name        = $wpdb->prefix . 'blog_designer_pro_shortcodes';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['bdsettings'];
				}
				if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$post_type            = $bdp_settings['custom_post_type'];
				$unique_design_option = isset( $bdp_settings['unique_design_option'] ) ? $bdp_settings['unique_design_option'] : '';
				$posts_per_page       = $bdp_settings['posts_per_page'];
				$blog_unique_design   = 0;
				if ( isset( $bdp_settings['blog_unique_design'] ) && '' != $bdp_settings['blog_unique_design'] ) {
					$blog_unique_design = $bdp_settings['blog_unique_design'];
				}
				$paged     = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset    = ( $paged - 1 ) * $posts_per_page;
				$bdp_theme = isset( $_POST['blog_template'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) ) : '';
				$tags      = '';
				$cats      = '';
				$author    = '';
				$order     = 'DESC';
				$orderby   = 'date';
				if ( isset( $_POST['filter_cat'] ) ) {
					$bdp_settings['template_category'] = sanitize_text_field( wp_unslash( $_POST['filter_cat'] ) );
				}
				if ( isset( $_POST['filter_tag'] ) ) {
					$bdp_settings['template_tags'] = sanitize_text_field( wp_unslash( $_POST['filter_tag'] ) );
				}
				if ( isset( $_POST['filter_category'] ) ) {
					$bdp_settings['template_category'] = str_replace( '.', '', sanitize_text_field( wp_unslash( $_POST['filter_category'] ) ) );
				}
				if ( isset( $_POST['filter_tag'] ) ) {
					$bdp_settings['template_tags'] = str_replace( '.', '', sanitize_text_field( wp_unslash( $_POST['filter_tag'] ) ) );
				}
				if ( isset( $bdp_settings['template_category'] ) ) {
					$cat = $bdp_settings['template_category'];
					if ( ! is_array( $cat ) ) {
						$cat = explode( ',', $cat );
					}
				}
				if ( isset( $bdp_settings['template_tags'] ) ) {
					$tag = $bdp_settings['template_tags'];
					if ( ! is_array( $tag ) ) {
						$tag = explode( ',', $tag );
					}
				}
				if ( isset( $bdp_settings['template_authors'] ) ) {
					$author = $bdp_settings['template_authors'];
				}
				if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
					$orderby = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
				} elseif ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				if ( empty( $cat ) ) {
					$cat = '';
				}
				if ( empty( $tag ) ) {
					$tag = '';
				}
				if ( isset( $bdp_settings['exclude_category_list'] ) ) {
					$exlude_category = 'category__not_in';
				} else {
					$exlude_category = 'category__in';
				}
				if ( isset( $bdp_settings['exclude_tag_list'] ) ) {
					$exlude_tag = 'tag__not_in';
				} else {
					$exlude_tag = 'tag__in';
				}
				if ( isset( $bdp_settings['exclude_author_list'] ) ) {
					$exlude_author = 'author__not_in';
				} else {
					$exlude_author = 'author__in';
				}
				/**
				 * Time Period
				 */
				$date_query  = array();
				$post_status = array();
				if ( isset( $bdp_settings['blog_time_period'] ) ) {
					$blog_time_period = $bdp_settings['blog_time_period'];
					if ( 'today' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
								'day'   => $today['mday'],
							),
						);
					}
					if ( 'tomorrow' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * DAY_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
								'day'   => $twodayslater['mday'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'this_week' === $blog_time_period ) {
						$week       = gmdate( 'W' );
						$year       = gmdate( 'Y' );
						$date_query = array(
							array(
								'year' => $year,
								'week' => $week,
							),
						);
					}
					if ( 'last_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 1 != $thisweek ) :
							$lastweek = $thisweek - 1;
						else :
							$lastweek = 52;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) - 1;
						endif;
						$date_query = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
					}
					if ( 'next_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 52 != $thisweek ) :
							$lastweek = $thisweek + 1;
						else :
							$lastweek = 1;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) + 1;
						endif;
						$date_query  = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
						$post_status = array( 'future' );
					}
					if ( 'this_month' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
							),
						);
					}
					if ( 'last_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) - 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
					}
					if ( 'next_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'last_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$last_n_days = $bdp_settings['bdp_time_period_day'] . ' days ago';
							$date_query  = array(
								array(
									'after'     => $last_n_days,
									'inclusive' => true,
								),
							);
						}
					}
					if ( 'next_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$next_n_days = '+' . $bdp_settings['bdp_time_period_day'] . ' days';
							$date_query  = array(
								array(
									'before'    => gmdate( 'Y-m-d', strtotime( $next_n_days ) ),
									'inclusive' => true,
								),
							);
							$post_status = array( 'future' );
						}
					}
					if ( 'between_two_date' === $blog_time_period ) {
						$between_two_date_from = isset( $bdp_settings['between_two_date_from'] ) ? $bdp_settings['between_two_date_from'] : '';
						$between_two_date_to   = isset( $bdp_settings['between_two_date_to'] ) ? $bdp_settings['between_two_date_to'] : '';
						$from_format           = array();
						$after                 = array();
						if ( $between_two_date_from ) {
							$unixtime  = strtotime( $between_two_date_from );
							$from_time = gmdate( 'm-d-Y', $unixtime );
							if ( $from_time ) {
								$from_format = explode( '-', $from_time );
								$after       = array(
									'year'  => isset( $from_format[2] ) ? $from_format[2] : '',
									'month' => isset( $from_format[0] ) ? $from_format[0] : '',
									'day'   => isset( $from_format[1] ) ? $from_format[1] : '',
								);
							}
						}
						$to_format = array();
						$before    = array();
						if ( $between_two_date_to ) {
							$unixtime = strtotime( $between_two_date_to );
							$to_time  = gmdate( 'm-d-Y', $unixtime );
							if ( $to_time ) {
								$to_format = explode( '-', $to_time );
								$before    = array(
									'year'  => isset( $to_format[2] ) ? $to_format[2] : '',
									'month' => isset( $to_format[0] ) ? $to_format[0] : '',
									'day'   => isset( $to_format[1] ) ? $to_format[1] : '',
								);
							}
						}
						$date_query = array(
							array(
								'after'     => $after,
								'before'    => $before,
								'inclusive' => true,
							),
						);
					}
				} else {
					$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				}
				if ( 'post' === $post_type ) {
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								$exlude_category => $cat,
								$exlude_tag      => $tag,
								$exlude_author   => $author,
								'offset'         => $offset,
								'post_type'      => $post_type,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby . ' date',
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								'meta_query'     => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					} elseif ( 1 == $bdp_settings['display_filter'] ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									'tax_query'      => $tax_query,
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
					} else {
						$tax_query = array();
						if ( '' != $cat && '' != $tag ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} elseif ( '' != $cat || '' != $tag ) {
							if ( 0 == $bdp_settings['display_filter'] ) {
								$cat = implode( ',', $cat );
							}
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} else {
							$more_posts = get_posts(
								array(
									$exlude_category => $cat,
									$exlude_tag      => $tag,
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						}
					}
				} else {
					$taxo           = get_object_taxonomies( $post_type );
					$tax_query      = array( 'relation' => 'OR' );
					$custom_taxonom = '';
					foreach ( $taxo as $taxonom ) {
						if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) ) {
							if ( ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
								$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
							}
							if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) && ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
								$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
							}
							$tax_query[] = array(
								'taxonomy' => $taxonom,
								'field'    => 'name',
								'terms'    => $custom_taxonom,
							);
						}
					}
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								'post_type'      => $post_type,
								'tax_query'      => $tax_query,
								'offset'         => $offset,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby . ' date',
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								$exlude_author   => $author,
								'meta_query'     => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					} else {
						$more_posts = get_posts(
							array(
								'post_type'      => $post_type,
								'tax_query'      => $tax_query,
								'offset'         => $offset,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby,
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								$exlude_author   => $author,
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					}
				}
				$sticky_posts = get_option( 'sticky_posts' );
				$sticky_count = count( $sticky_posts );
				$alter_class  = '';
				$alter        = $offset + 1;
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$alter = $alter + $sticky_count;
				}
				$prev_year    = isset( $_POST['timeline_previous_year'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['timeline_previous_year'] ) ) ) : '';
				$prev_year1   = null;
				$prev_month   = isset( $_POST['timeline_previous_month'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['timeline_previous_month'] ) ) ) : '';
				$count_sticky = 0;
				if ( $more_posts ) {
					global $post;
					$i = 1;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
							if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = ' alternative-back ';
								} else {
									$alter_class = '';
								}
							}
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
											if ( 'even_class' === $alter_class ) {
												$alter_class = 'odd_class';
												++$alter;
											}
											echo '<div class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></div></span>';
										}
									} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
										$this_year  = get_the_date( 'Y' );
										$this_month = get_the_time( 'M' );
										$prev_year  = $this_year;
										if ( $prev_month != $this_month ) {
											$prev_month = $this_month;
											if ( 'even_class' === $alter_class ) {
												$alter_class = 'odd_class';
												++$alter;
											}
											echo '<div class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></div>';
										}
									}
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
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( 1 == $blog_unique_design ) {
								if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'clicky' === $bdp_theme ) {
									$alter_class = $alter;
									// are we on page one?
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
							}
							Bdp_Template::get_blog_loadmore_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky );
							++$alter;
						}
						echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
						++$i;
					endforeach;
					if ( 1 != $alter % 2 && ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme ) ) {
						echo '</div>';
					}
				}
			} elseif ( 'archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? intval( $_POST['blog_shortcode_id'] ) : '';
				$table_name        = $wpdb->prefix . 'bdp_archives';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['settings'];
				}
				if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$bdp_theme      = isset( $_POST['blog_template'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) ) : '';
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}

				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}

					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'cat'            => $cat,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $_POST['term_id'] ) ) {
						$tag = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$tag = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tag_id'         => $tag,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'date_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'year'           => isset( $_POST['year_value'] ) ? sanitize_text_field( wp_unslash( $_POST['year_value'] ) ) : '',
						'monthnum'       => isset( $_POST['month_value'] ) ? sanitize_text_field( wp_unslash( $_POST['month_value'] ) ) : '',
						'day'            => isset( $_POST['date_value'] ) ? sanitize_text_field( wp_unslash( $_POST['date_value'] ) ) : '',
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} else {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'author_template' === $bdp_settings['custom_archive_type'] ) {
					if ( ! empty( $post_author ) ) {
						$arg_posts['author__in'] = $post_author;
					}
				}
				if ( 'search_template' === $bdp_settings['custom_archive_type'] ) {
					$arg_posts['s'] = isset( $_POST['search_string'] ) ? sanitize_text_field( wp_unslash( $_POST['search_string'] ) ) : '';
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$more_posts  = get_posts( $arg_posts );
				$prev_year1  = null;
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					$i = 1;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
													$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
						echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
						++$i;
					endforeach;
				}
			} elseif ( 'product_archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'bdp_product_archives';
				$settings_val      = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_product_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
				$allsettings       = $settings_val[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$bdp_theme      = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'product',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'product',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}

				$more_posts  = get_posts( $arg_posts );
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
					endforeach;
				}
			} elseif ( 'download_archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'bdp_edd_archives';
				$settings_val      = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_edd_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
				$allsettings       = $settings_val[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$bdp_theme      = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
				$posts_per_page = $bdp_settings['posts_per_page'];

				$orderby = 'date';
				$order   = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'download_category',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'download',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'download_tag',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'download',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$more_posts  = get_posts( $arg_posts );
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
					endforeach;
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Change Filter
	 *
	 * @return void
	 */
	public function filter_change() {
		global $wpdb;
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
			if ( isset( $_POST['filter_cat'] ) ) {
				$filter_catdata = array_map( 'sanitize_text_field', wp_unslash( $_POST['filter_cat'] ) );
			}
			if ( isset( $_POST['filter_tag'] ) ) {
				$filter_tagdata = array_map( 'sanitize_text_field', wp_unslash( $_POST['filter_tag'] ) );
			}
			if ( isset( $_POST['filter_date'] ) ) {
				$filter_date = array_map( 'sanitize_text_field', wp_unslash( $_POST['filter_date'] ) );
			}
			$bdp_theme  = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
			$table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
			if ( is_numeric( $blog_shortcode_id ) ) {
				$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $blog_shortcode_id ), ARRAY_A );
				$allsettings  = $settings_val[0]['bdsettings'];
			}
			if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
				$bdp_settings = maybe_unserialize( $allsettings );
			}
			$post_per_page = $bdp_settings['posts_per_page'];
			if ( 'post' === $bdp_settings['custom_post_type'] ) {
				if ( isset( $filter_catdata ) && ! empty( $filter_catdata ) ) {
					$bdp_settings['template_category'] = $filter_catdata;
				}
				if ( isset( $_POST['url'] ) ) {
					$bdp_settings['url'] = isset( $_POST['url'] ) ? sanitize_text_field( wp_unslash( $_POST['url'] ) ) : '';
				}
				if ( isset( $filter_tagdata ) && ! empty( $filter_tagdata ) ) {
					$bdp_settings['template_tags'] = $filter_tagdata;
				}
			} else {
				$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'] );
				foreach ( $taxonomy_names as $taxonomy ) {
					if ( isset( $taxonomy ) ) {
						if ( isset( $bdp_settings[ 'filter_' . $taxonomy ] ) && 1 == $bdp_settings[ 'filter_' . $taxonomy ] ) {
							if ( ! empty( $_POST[ "filter_$taxonomy" ] ) ) {
								$bdp_settings[ $taxonomy . '_terms' ] = array();
								$bdp_settings[ $taxonomy . '_terms' ] = array_map( 'sanitize_text_field', wp_unslash( $_POST[ "filter_$taxonomy" ] ) );
							}
						}
						$bdp_settings['relation'] = array( 'relation' => 'AND' );
					}
				}
			}
			if ( ! isset( $_POST['filter_cat'] ) ) {
				$bdp_settings['posts_per_page'] = $post_per_page;
			}
			$bdp_settings['exclude_category_list'] = 0;
			$bdp_settings['exclude_tag_list']      = 0;
			$bdp_settings['paged']                 = isset( $_POST['blog_page_number'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_page_number'] ) ) : '';
			$posts                                 = Bdp_Posts::get_wp_query( $bdp_settings );
			$date                                  = array();
			if ( isset( $filter_date ) && ! empty( $filter_date ) ) {
				$date_query             = array();
				$date_query['relation'] = 'OR';
				foreach ( $filter_date as $fdate ) {
					$date         = explode( '-', $fdate );
					$date_query[] = array(
						'year'  => $date[0],
						'month' => $date[1],
					);
				}
				$posts['date_query'] = $date_query;
			}
			global $wp_query;
			$temp_query        = $wp_query;
			$loop              = new WP_Query( $posts );
			$wp_query          = $loop;
			$max_num_pages     = $wp_query->max_num_pages;
			$prev_year         = null;
			$prev_year1        = null;
			$prev_month        = null;
			$count_sticky      = 0;
			$alter             = 1;
			$alter_val         = 1;
			$alter_class       = '';
			$tabbed_post_style = 0;
			if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'news' === $bdp_theme || 'brit_co' === $bdp_theme || 'boxy' === $bdp_theme || 'boxy-clean' === $bdp_theme ) {
				if ( $loop->have_posts() ) {
					if ( 'media-grid' === $bdp_theme ) {
						$prev_year = 0;
					}
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					while ( have_posts() ) :
						the_post();
						echo wp_kses( Bdp_Template::get_blog_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style ), Bdp_Admin_Functions::args_kses() );
					endwhile;
				} else {
					echo 'No Posts Found.';
				}
			} elseif ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
				echo '<div class="my_logbook">';
				if ( $loop->have_posts() ) {
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					while ( have_posts() ) :
						the_post();
						echo wp_kses( Bdp_Template::get_blog_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style ), Bdp_Admin_Functions::args_kses() );
					endwhile;
				} else {
					echo 'No Posts Found.';
				}
				echo '</div>';
			}
			if ( 'paged' === $bdp_settings['pagination_type'] ) {
				$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
				echo '<div class="filter wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
				echo wp_kses( Bdp_Posts::shortcode_standard_paging_nav( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
				echo '</div>';
			}
			if ( 'load_more_btn' === $bdp_settings['pagination_type'] || 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) {
				$filter_cats = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : '';
				if ( ! empty( $filter_cats ) ) {
					$filter_cats = implode( ',', $filter_cats );
				}
				$filter_tags = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : '';
				if ( ! empty( $filter_tags ) ) {
					$filter_tags = implode( ',', $filter_tags );
				}
				echo '<form name="bdp-load-more-hidden" class="bdp-load-more-hidden" id="bdp-load-more-hidden">';
				echo '<input type="hidden" name="filter_cat" id="filter_cat" value="' . esc_attr( $filter_cats ) . '" />';
				echo '<input type="hidden" name="filter_tag" id="filter_tag" value="' . esc_attr( $filter_tags ) . '" />';
				echo '</form>';
			}
		}
		wp_reset_query();
		$wp_query = null;
		$wp_query = $temp_query;
		$data     = ob_get_clean();
		echo $data;
		die();
	}

	/**
	 * To get posts when load more pagination is on
	 *
	 * @return void
	 */
	public function bdp_load_onscroll_blog() {
		global $wpdb;
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$layout = isset( $_POST['blog_layout'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_layout'] ) ) : '';
			if ( 'blog_layout' === $layout ) {
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'blog_designer_pro_shortcodes';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['bdsettings'];
				}
				$count_sticky = 0;
				if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$post_type      = $bdp_settings['custom_post_type'];
				$posts_per_page = isset( $_POST['posts_per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['posts_per_page'] ) ) : '';
				$paged          = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset         = ( $paged - 1 ) * $posts_per_page;
				$bdp_theme      = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
				$tags           = '';
				$cats           = '';
				$author         = '';
				$order          = 'DESC';
				$orderby        = 'date';
				if ( isset( $_POST['filter_cat'] ) ) {
					$bdp_settings['template_category'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['filter_cat'] ) );
				}
				if ( isset( $_POST['filter_tag'] ) ) {
					$bdp_settings['template_tags'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['filter_tag'] ) );
				}
				if ( isset( $bdp_settings['template_category'] ) ) {
					$cat = $bdp_settings['template_category'];
					if ( ! is_array( $cat ) ) {
						$cat = explode( ',', $cat );
					}
				}
				if ( isset( $bdp_settings['template_tags'] ) ) {
					$tag = $bdp_settings['template_tags'];
					if ( ! is_array( $tag ) ) {
						$tag = explode( ',', $tag );
					}
				}
				if ( isset( $bdp_settings['template_authors'] ) ) {
					$author = $bdp_settings['template_authors'];
				}
				if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
					$orderby = isset( $_GET['sortby'] ) ? sanitize_text_field( wp_unslash( $_GET['sortby'] ) ) : '';
				} elseif ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				if ( empty( $cat ) ) {
					$cat = '';
				}
				if ( empty( $tag ) ) {
					$tag = '';
				}
				if ( isset( $bdp_settings['exclude_category_list'] ) ) {
					$exlude_category = 'category__not_in';
				} else {
					$exlude_category = 'category__in';
				}
				if ( isset( $bdp_settings['exclude_tag_list'] ) ) {
					$exlude_tag = 'tag__not_in';
				} else {
					$exlude_tag = 'tag__in';
				}
				if ( isset( $bdp_settings['exclude_author_list'] ) ) {
					$exlude_author = 'author__not_in';
				} else {
					$exlude_author = 'author__in';
				}
				/**
				 * Time Period
				 */
				$date_query  = array();
				$post_status = array();
				if ( isset( $bdp_settings['blog_time_period'] ) ) {
					$blog_time_period = $bdp_settings['blog_time_period'];
					if ( 'today' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
								'day'   => $today['mday'],
							),
						);
					}
					if ( 'tomorrow' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * DAY_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
								'day'   => $twodayslater['mday'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'this_week' === $blog_time_period ) {
						$week       = gmdate( 'W' );
						$year       = gmdate( 'Y' );
						$date_query = array(
							array(
								'year' => $year,
								'week' => $week,
							),
						);
					}
					if ( 'last_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 1 != $thisweek ) :
							$lastweek = $thisweek - 1;
						else :
							$lastweek = 52;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) - 1;
						endif;

						$date_query = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
					}
					if ( 'next_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 52 != $thisweek ) :
							$lastweek = $thisweek + 1;
						else :
							$lastweek = 1;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) + 1;
						endif;
						$date_query  = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
						$post_status = array( 'future' );
					}
					if ( 'this_month' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
							),
						);
					}
					if ( 'last_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) - 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
					}
					if ( 'next_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'last_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$last_n_days = $bdp_settings['bdp_time_period_day'] . ' days ago';
							$date_query  = array(
								array(
									'after'     => $last_n_days,
									'inclusive' => true,
								),
							);
						}
					}
					if ( 'next_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$next_n_days = '+' . $bdp_settings['bdp_time_period_day'] . ' days';
							$date_query  = array(
								array(
									'before'    => gmdate( 'Y-m-d', strtotime( $next_n_days ) ),
									'inclusive' => true,
								),
							);
							$post_status = array( 'future' );
						}
					}
					if ( 'between_two_date' === $blog_time_period ) {
						$between_two_date_from = isset( $bdp_settings['between_two_date_from'] ) ? $bdp_settings['between_two_date_from'] : '';
						$between_two_date_to   = isset( $bdp_settings['between_two_date_to'] ) ? $bdp_settings['between_two_date_to'] : '';
						$from_format           = array();
						$after                 = array();
						if ( $between_two_date_from ) {
							$unixtime  = strtotime( $between_two_date_from );
							$from_time = gmdate( 'm-d-Y', $unixtime );
							if ( $from_time ) {
								$from_format = explode( '-', $from_time );
								$after       = array(
									'year'  => isset( $from_format[2] ) ? $from_format[2] : '',
									'month' => isset( $from_format[0] ) ? $from_format[0] : '',
									'day'   => isset( $from_format[1] ) ? $from_format[1] : '',
								);
							}
						}
						$to_format = array();
						$before    = array();
						if ( $between_two_date_to ) {
							$unixtime = strtotime( $between_two_date_to );
							$to_time  = gmdate( 'm-d-Y', $unixtime );
							if ( $to_time ) {
								$to_format = explode( '-', $to_time );
								$before    = array(
									'year'  => isset( $to_format[2] ) ? $to_format[2] : '',
									'month' => isset( $to_format[0] ) ? $to_format[0] : '',
									'day'   => isset( $to_format[1] ) ? $to_format[1] : '',
								);
							}
						}
						$date_query = array(
							array(
								'after'     => $after,
								'before'    => $before,
								'inclusive' => true,
							),
						);
					}
				}
				if ( 'post' === $post_type ) {
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								$exlude_category   => $cat,
								$exlude_tag        => $tag,
								$exlude_author     => $author,
								'offset'           => $offset,
								'post_type'        => $post_type,
								'posts_per_page'   => $posts_per_page,
								'paged'            => $paged,
								'orderby'          => $orderby . ' date',
								'order'            => $order,
								'post__not_in'     => get_option( 'sticky_posts' ),
								'meta_query'       => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'       => $date_query,
								'post_status'      => $post_status,
								'suppress_filters' => false,
							)
						);
					} else {
						$tax_query = array();
						if ( '' != $cat && '' != $tag ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} elseif ( '' != $cat || '' != $tag ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									// 'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => -1,
									// 'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} else {
							$more_posts = get_posts(
								array(
									$exlude_category => $cat,
									$exlude_tag      => $tag,
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						}
					}
				} else {
					$taxo           = get_object_taxonomies( $post_type );
					$tax_query      = array( 'relation' => 'OR' );
					$custom_taxonom = '';
					foreach ( $taxo as $taxonom ) {
						if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) ) {
							if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) && ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
								$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
							}
							$tax_query[] = array(
								'taxonomy' => $taxonom,
								'field'    => 'name',
								'terms'    => $custom_taxonom,
							);
						}
					}
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								'post_type'        => $post_type,
								'tax_query'        => $tax_query,
								'offset'           => $offset,
								'posts_per_page'   => $posts_per_page,
								'paged'            => $paged,
								'orderby'          => $orderby . ' date',
								'order'            => $order,
								'post__not_in'     => get_option( 'sticky_posts' ),
								$exlude_author     => $author,
								'meta_query'       => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'       => $date_query,
								'post_status'      => $post_status,
								'suppress_filters' => false,
							)
						);
					} else {
						$more_posts = get_posts(
							array(
								'post_type'        => $post_type,
								'tax_query'        => $tax_query,
								'offset'           => $offset,
								'posts_per_page'   => $posts_per_page,
								'paged'            => $paged,
								'orderby'          => $orderby,
								'order'            => $order,
								'post__not_in'     => get_option( 'sticky_posts' ),
								$exlude_author     => $author,
								'date_query'       => $date_query,
								'post_status'      => $post_status,
								'suppress_filters' => false,
							)
						);
					}
				}
				$alter_class  = '';
				$sticky_posts = get_option( 'sticky_posts' );
				$sticky_count = count( $sticky_posts );
				$alter_class  = '';
				$alter        = $offset + 1;
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$alter = $alter + $sticky_count;
				}
				$unique_design_option = isset( $bdp_settings['unique_design_option'] ) ? $bdp_settings['unique_design_option'] : '';
				$prev_year            = isset( $_POST['timeline_previous_year'] ) ? sanitize_text_field( wp_unslash( $_POST['timeline_previous_year'] ) ) : '';
				$prev_year1           = null;
				$prev_month           = isset( $_POST['timeline_previous_month'] ) ? sanitize_text_field( wp_unslash( $_POST['timeline_previous_month'] ) ) : '';
				$inc_time             = 1;
				if ( $more_posts ) {
					global $post;
					$blog_unique_design = 0;
					if ( isset( $bdp_settings['blog_unique_design'] ) && '' != $bdp_settings['blog_unique_design'] ) {
						$blog_unique_design = $bdp_settings['blog_unique_design'];
					}
					$i = 1;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
							if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = ' alternative-back';
								} else {
									$alter_class = '';
								}
							}
							if ( 'timeline' === $bdp_theme ) {
								if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
									if ( 0 != $alter % 2 && 1 == $inc_time ) {
										++$alter;
										++$inc_time;
									}
								}
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
											if ( 'even_class' === $alter_class ) {
												$alter_class = 'odd_class';
												++$alter;
											}
											echo '<div class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></div></span>';
										}
									} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
										$this_year  = get_the_date( 'Y' );
										$this_month = get_the_time( 'M' );
										$prev_year  = $this_year;
										if ( $prev_month != $this_month ) {
											$prev_month = $this_month;
											if ( 'even_class' === $alter_class ) {
												$alter_class = 'odd_class';
												++$alter;
											}
											echo '<div class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></div>';
										}
									}
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
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( 1 == $blog_unique_design ) {
								if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
									$alter_class = $alter;
									// are we on page one?.
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
							}
							Bdp_Template::get_blog_loadmore_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky );
							++$alter;
						}
						echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
						++$i;
					endforeach;
					if ( 1 != $alter % 2 && ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme ) ) {
						echo '</div>';
					}
				}
			} elseif ( 'archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'bdp_archives';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['settings'];
				}
				if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				$bdp_theme      = sanitize_text_field( wp_unslash( $_POST['blog_template'] ) );
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();

				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'cat'            => $cat,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $_POST['term_id'] ) ) {
						$tag = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$tag = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tag_id'         => $tag,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'date_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'year'           => get_query_var( 'year' ),
						'monthnum'       => get_query_var( 'monthnum' ),
						'day'            => get_query_var( 'day' ),
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} else {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'author_template' === $bdp_settings['custom_archive_type'] ) {
					if ( ! empty( $post_author ) ) {
						$arg_posts['author__in'] = $post_author;
					}
				}
				if ( 'search_template' === $bdp_settings['custom_archive_type'] ) {
					$arg_posts['s'] = isset( $_POST['search_string'] ) ? sanitize_text_field( wp_unslash( $_POST['search_string'] ) ) : '';
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$more_posts  = get_posts( $arg_posts );
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					$i = 1;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
						echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
						++$i;
					endforeach;
				}
			} elseif ( 'product_archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'bdp_product_archives';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_product_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['settings'];
					if ( is_serialized( $allsettings ) ) {
						$bdp_settings = maybe_unserialize( $allsettings );
					}
				}
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				$bdp_theme      = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'product',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'product_tag',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'product',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$more_posts  = get_posts( $arg_posts );
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
													$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
					endforeach;
				}
			} elseif ( 'download_archive_layout' === $layout ) {
				global $wp_query;
				$bdp_theme         = '';
				$bdp_settings      = array();
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) : '';
				$table_name        = $wpdb->prefix . 'bdp_edd_archives';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_edd_archives WHERE id = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['settings'];
					if ( is_serialized( $allsettings ) ) {
						$bdp_settings = maybe_unserialize( $allsettings );
					}
				}
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				$bdp_theme      = isset( $_POST['blog_template'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) : '';
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
				$offset      = ( $paged - 1 ) * $posts_per_page;
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();
				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'download_category',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'download',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					if ( isset( $_POST['term_id'] ) ) {
						$cat = sanitize_text_field( wp_unslash( $_POST['term_id'] ) );
					} else {
						$cat = '';
					}
					$tax_query = array();
					$tax_query = array(
						array(
							'taxonomy' => 'download_tag',
							'field'    => 'term_id',
							'terms'    => $cat,
							'operator' => 'IN',
						),
					);
					$arg_posts = array(
						'post_type'      => 'download',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'offset'         => $offset,
						'post_status'    => $post_status,
						'tax_query'      => $tax_query,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$arg_posts['ignore_sticky_posts'] = 0;
				} else {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$more_posts  = get_posts( $arg_posts );
				$alter_class = '';
				$prev_year   = '';
				$alter       = $offset + 1;
				$alter_val   = null;
				if ( $more_posts ) {
					global $post;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
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
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
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
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						}
					endforeach;
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Change author date pagination
	 *
	 * @param type $query query.
	 */
	public function bdp_change_author_date_pagination( $query ) {
		global $wp_query;
		if ( $query->is_main_query() && ! is_admin() ) {
			$archive_list = Bdp_Template::get_archive_list();
			if ( is_date() && in_array( 'date_template', $archive_list ) ) {
				if ( is_date() ) {
					$da_settings = Bdp_Template::get_date_template_settings();
				}
				$bdp_settings = $da_settings->settings;
			} elseif ( is_author() && in_array( 'author_template', $archive_list ) ) {
				$author_detail   = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
				$author_id       = isset( $author_detail->ID ) ? $author_detail->ID : 1;
				$bdp_author_data = Bdp_Author::get_author_template_settings( $author_id, $archive_list );
				$archive_id      = $bdp_author_data['id'];
				$bdp_settings    = $bdp_author_data['bdp_settings'];
			} elseif ( is_category() && in_array( 'category_template', $archive_list ) ) {
				$category_obj      = get_term_by( 'slug', $query->query_vars['category_name'], 'category' );
				$category_id       = $category_obj->term_id;
				$bdp_category_data = Bdp_Template::get_category_template_settings( $category_id, $archive_list );
				$archive_id        = $bdp_category_data['id'];
				$bdp_settings      = $bdp_category_data['bdp_settings'];
			} elseif ( is_tag() && in_array( 'tag_template', $archive_list ) ) {
				$tag_obj      = get_term_by( 'slug', $query->query['tag'], 'post_tag' );
				$tag_id       = $tag_obj->term_id;
				$bdp_tag_data = Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
				$archive_id   = $bdp_tag_data['id'];
				$bdp_settings = $bdp_tag_data['bdp_settings'];
			} elseif ( is_search() && in_array( 'search_template', $archive_list ) ) {
				$da_settings  = Bdp_Template::get_search_template_settings();
				$bdp_settings = $da_settings->settings;
			}
			if ( Bdp_Woocommerce::is_woocommerce_plugin() ) {
				$bdp_settings = array();
				$archive_list = array();
				$archive_list = Bdp_Woocommerce::get_product_archive_list();
				if ( is_product_category() && in_array( 'category_template', $archive_list ) ) {
					$category_obj      = get_term_by( 'slug', $query->query_vars['product_cat'], 'product_cat' );
					$category_id       = $category_obj->term_id;
					$bdp_category_data = Bdp_Template::get_product_category_template_settings( $category_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
				} elseif ( is_product_tag() && in_array( 'tag_template', $archive_list ) ) {
					$tag_obj           = get_term_by( 'slug', $query->query['product_tag'], 'product_tag' );
					$tag_id            = $tag_obj->term_id;
					$bdp_category_data = Bdp_Template::get_product_tag_template_settings( $tag_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
				}
			}
			if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
				$archive_list = Bdp_Edd::get_download_archive_list();
				if ( is_tax( 'download_category' ) && in_array( 'category_template', $archive_list ) ) {
					$category_obj      = get_term_by( 'slug', $query->query_vars['download_category'], 'download_category' );
					$category_id       = $category_obj->term_id;
					$bdp_category_data = Bdp_Edd::get_download_category_template_settings( $category_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
				}
				if ( is_tax( 'download_tag' ) && in_array( 'tag_template', $archive_list ) ) {
					$tag_obj           = get_term_by( 'slug', $query->query['download_tag'], 'download_tag' );
					$tag_id            = $tag_obj->term_id;
					$bdp_category_data = Bdp_Template::get_download_tag_template_settings( $tag_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
				}
			}
			if ( isset( $bdp_settings ) && '' != $bdp_settings && ! empty( $bdp_settings ) ) {
				$bdp_settings   = maybe_unserialize( $bdp_settings );
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ? $bdp_settings['bdp_blog_order_by'] : 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = isset( $bdp_settings['bdp_blog_order'] ) ? $bdp_settings['bdp_blog_order'] : 'DESC';
				}
				if ( 'meta_value_num' === $orderby ) {
					$orderby_str = $orderby . ' date';
				} else {
					$orderby_str = $orderby;
				}
				$query->set( 'posts_per_page', $posts_per_page );
				$query->set( 'orderby', $orderby_str );
				$query->set( 'order', $order );
				if ( 'meta_value_num' === $orderby ) {
					$query->set(
						'meta_query',
						array(
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						)
					);
				}
			}
		}
	}

	/**
	 * Redirection
	 *
	 * @since 2.6
	 * @return void
	 */
	public function wp_blog_designer_pro_redirection() {
		if ( get_option( 'bdp_plugin_do_activation_redirect', false ) ) {
			delete_option( 'bdp_plugin_do_activation_redirect' );
			if ( ! isset( $_GET['activate-multi'] ) ) {
				exit( wp_redirect( admin_url( 'admin.php?page=bdp_getting_started' ) ) );
			}
		}
	}
	/**
	 * Create Shortcode table
	 *
	 * @since 2.6
	 * @return void
	 */
	public static function bdp_create_shortcodes_table() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		$charset_collate = '';
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
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
	/**
	 * Create archive table
	 *
	 * @since 2.6
	 * @return void
	 */
	public static function set_archive_table() {
		global $wpdb;
		$archive_table = $wpdb->prefix . 'bdp_archives';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$archive_sql = "CREATE TABLE $archive_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            archive_name tinytext NOT NULL,
            archive_template tinytext NOT NULL,
            sub_categories text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
            ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $archive_sql );
	}
	/**
	 * Create archive table
	 *
	 * @since 2.6
	 * @return void
	 */
	public static function set_product_archive_table() {
		global $wpdb;
		$product_archive_table = $wpdb->prefix . 'bdp_product_archives';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$product_archive_sql = "CREATE TABLE $product_archive_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            product_archive_name tinytext NOT NULL,
            product_archive_template tinytext NOT NULL,
            product_sub_categories text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
            ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $product_archive_sql );
	}
	/**
	 * Create single table
	 *
	 * @since 2.6
	 * @return void
	 */
	public static function set_single_table() {
		global $wpdb;
		$single_table = $wpdb->prefix . 'bdp_single_layouts';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$single_sql = "CREATE TABLE $single_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            single_name tinytext NOT NULL,
            single_template tinytext NOT NULL,
            sub_categories text NOT NULL,
            single_post_id text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
        ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $single_sql );
	}
	/**
	 * Create single table
	 *
	 * @since 2.6
	 * @return void
	 */
	public static function set_single_product_table() {
		global $wpdb;
		$single_table = $wpdb->prefix . 'bdp_single_product';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$single_sql = "CREATE TABLE $single_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            single_product_name tinytext NOT NULL,
            single_product_template tinytext NOT NULL,
            sub_categories text NOT NULL,
            single_product_id text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
        ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $single_sql );
	}

	/**
	 * Create single table download
	 *
	 * @since 2.7
	 * @return void
	 */
	public static function set_single_edd_table() {
		global $wpdb;
		$single_table = $wpdb->prefix . 'bdp_single_ed_download';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$single_sql = "CREATE TABLE $single_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            single_download_name tinytext NOT NULL,
            single_download_template tinytext NOT NULL,
            sub_categories text NOT NULL,
            single_download_id text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
        ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $single_sql );
	}

	/**
	 * Create Easy digital download archive table
	 *
	 * @since 2.7
	 * @return void
	 */
	public static function set_edd_archive_table() {
		global $wpdb;
		$edd_archive_table = $wpdb->prefix . 'bdp_edd_archives';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		$edd_archive_sql = "CREATE TABLE $edd_archive_table (
            id int(9) NOT NULL AUTO_INCREMENT,
            download_archive_name tinytext NOT NULL,
            download_archive_template tinytext NOT NULL,
            download_sub_categories text NOT NULL,
            settings text NOT NULL,
            UNIQUE KEY ID (ID)
            ) $charset_collate;";
		// reference to upgrade.php file.
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $edd_archive_sql );
	}
	/**
	 * Processes like/unlike
	 *
	 * @return void
	 */
	public function bdp_process_posts_like() {
		// Security.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;
		if ( ! wp_verify_nonce( $nonce, 'bdp-simple-likes-nonce' ) ) {
			exit( esc_html__( 'Not permitted', 'blog-designer-pro' ) );
		}
		// Base variables.
		$post_id    = ( isset( $_POST['post_id'] ) && is_numeric( $_POST['post_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		$response   = array();
		$post_users = null;
		$like_count = 0;
		// Get plugin options.
		if ( '' != $post_id ) {
			$count = get_post_meta( $post_id, '_post_like_count', true );
			$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
			if ( ! Bdp_Like::already_liked( $post_id ) ) {
				if ( is_user_logged_in() ) {
					$user_id    = get_current_user_id();
					$post_users = Bdp_Like::post_user_likes( $user_id, $post_id );
					// Update User & Post.
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count = ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, '_user_like_count', ++$user_like_count );
					if ( ! empty( $post_users ) ) {
						update_post_meta( $post_id, 'like_users', $post_users );
					} else {
						update_post_meta( $post_id, 'like_users', $user_id );
					}
				} else { // user is anonymous.
					$user_ip    = Bdp_Utility::get_ip();
					$post_users = Bdp_Like::post_ip_likes( $user_ip, $post_id );
					// Update Post.
					if ( $post_users ) {
						update_post_meta( $post_id, 'like_ipaddresses', $post_users );
					}
				}
				$like_count         = ++$count;
				$response['status'] = 'liked';
				$response['icon']   = Bdp_Like::get_liked_icon();
			} else { // Unlike the post.
				if ( is_user_logged_in() ) {
					$user_id    = get_current_user_id();
					$post_users = Bdp_Like::post_user_likes( $user_id, $post_id );
					// Update User.
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count = ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
					// Update Post.
					if ( ! empty( $post_users ) ) {
						$uid_key = array_search( $user_id, $post_users, true );
						unset( $post_users[ $uid_key ] );
						update_post_meta( $post_id, 'like_users', $post_users );
					} else {
						update_post_meta( $post_id, 'like_users', $user_id );
					}
				} else { // user is anonymous.
					$user_ip    = Bdp_Utility::get_ip();
					$post_users = Bdp_Like::post_ip_likes( $user_ip, $post_id );
					// Update Post.
					if ( $post_users ) {
						$uip_key = array_search( $user_ip, $post_users, true );
						unset( $post_users[ $uip_key ] );
						update_post_meta( $post_id, 'like_ipaddresses', $post_users );
					}
				}
				$like_count         = ( $count > 0 ) ? --$count : 0; // Prevent negative number.
				$response['status'] = 'unliked';
				$response['icon']   = Bdp_Like::get_unliked_icon();
			}
			update_post_meta( $post_id, '_post_like_count', $like_count );
			update_post_meta( $post_id, '_post_like_modified', gmdate( 'Y-m-d H:i:s' ) );
			$response['count'] = Bdp_Like::get_like_count( $like_count );
			wp_send_json( $response );
		}
		exit();
	}
	/**
	 * Layouts Notice Dismissible
	 *
	 * @return void
	 */
	public function bdp_get_post_type_post_list() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) {
				$posttype = sanitize_text_field( wp_unslash( $_POST['posttype'] ) );
			} else {
				$posttype = 'post';
			}
			?>
			<?php
			query_posts( 'showposts=-1&post_status=publish&post_type=' . $posttype );
			if ( have_posts() ) {
				echo '<select name="timeline_start_from" id="timeline_start_from">';
				while ( have_posts() ) {
					the_post();
					?>
					<option value="<?php echo get_the_date( 'd/m/Y' ); ?>"><?php echo esc_html( get_the_title() ); ?></option>
					<?php
				}
				echo '</select>';
			} else {
				echo '<p>';
				esc_html_e( 'No posts found.', 'blog-designer-pro' );
				echo '</p>';
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Layouts Notice Dismissible
	 *
	 * @return void
	 */
	public function bdp_layouts_notice_dismissible() {
		global $current_user;
		$user_id = $current_user->ID;
		update_user_meta( $user_id, 'bdp_notice_ignore', 1 );
		exit();
	}
	/**
	 * Email Share Form
	 *
	 * @return void
	 */
	public function bdp_email_share_form() {
		wp_reset_postdata();
		global $wpdb, $post;
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$cur_page = isset( $_POST['cur_page'] ) ? sanitize_text_field( wp_unslash( $_POST['cur_page'] ) ) : 0;
			$cur_id   = isset( $_POST['cur_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cur_id'] ) ) : 0;
			if ( 'date' === $cur_page || 'author' === $cur_page || 'category' === $cur_page || 'tag' === $cur_page ) {
				$archive_list = Bdp_Template::get_archive_list();
				if ( 'date' === $cur_page && in_array( 'date_template', $archive_list, true ) ) {
					$date        = Bdp_Template::get_date_template_settings();
					$all_setting = $date->settings;
					if ( is_serialized( $all_setting ) ) {
						$bdp_settings = maybe_unserialize( $all_setting );
					}
				} elseif ( 'author' === $cur_page && in_array( 'author_template', $archive_list, true ) ) {
					$author_id       = $cur_id;
					$bdp_author_data = Bdp_Author::get_author_template_settings( $author_id, $archive_list );
					$archive_id      = $bdp_author_data['id'];
					$bdp_settings    = $bdp_author_data['bdp_settings'];
				} elseif ( 'category' === $cur_page && in_array( 'category_template', $archive_list, true ) ) {
					$category_id       = $cur_id;
					$bdp_category_data = Bdp_Template::get_category_template_settings( $category_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
				} elseif ( 'tag' === $cur_page && in_array( 'tag_template', $archive_list, true ) ) {
					$tag_id       = $cur_id;
					$bdp_tag_data = Bdp_Template::get_tag_template_settings( $tag_id, $archive_list );
					$archive_id   = $bdp_tag_data['id'];
					$bdp_settings = $bdp_tag_data['bdp_settings'];
				}
			} elseif ( 'search' === $cur_page && in_array( 'search_template', $archive_list, true ) ) {
				$search_settings = Bdp_Template::get_search_template_settings();
				$allsettings     = $search_settings->settings;
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
			} elseif ( 'single' === $cur_page ) {
				if ( ( Bdp_Woocommerce::is_woocommerce_plugin() || class_exists( 'woocommerce' ) ) && is_product() ) {
					$post_id      = $post->ID; // This will not work in case ajax.
					$post_id      = $cur_id;
					$cat_ids      = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
					$tag_ids      = wp_get_post_terms( $post_id, 'product_tag', array( 'fields' => 'ids' ) );
					$bdp_settings = Bdp_Template::get_single_prodcut_template_settings( $cat_ids, $tag_ids );
					$bdp_settings = maybe_unserialize( $bdp_settings );
				} elseif ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
					$post_id      = $post->ID; // This will not work in case ajax.
					$post_id      = $cur_id;
					$cat_ids      = wp_get_post_terms( $post_id, 'download_category', array( 'fields' => 'ids' ) && 'download' === $post->post_type );
					$tag_ids      = wp_get_post_terms( $post_id, 'download_tag', array( 'fields' => 'ids' ) );
					$bdp_settings = Bdp_Template::get_single_download_template_settings( $cat_ids, $tag_ids );
					$bdp_settings = maybe_unserialize( $bdp_settings );
				} else {
					$post_id      = $post->ID; // This will not work in case ajax.
					$post_id      = $cur_id;
					$cat_ids      = wp_get_post_categories( $post_id );
					$tag_ids      = wp_get_post_tags( $post_id );
					$bdp_settings = Bdp_Template::get_single_template_settings( $cat_ids, $tag_ids );
					$bdp_settings = maybe_unserialize( $bdp_settings );
				}
			} else {
				$table_name       = $wpdb->prefix . 'blog_designer_pro_shortcodes';
				$txt_shortcode_id = isset( $_POST['txtShortcodeId'] ) ? sanitize_text_field( wp_unslash( $_POST['txtShortcodeId'] ) ) : 0;
				$settings_val     = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $txt_shortcode_id ), ARRAY_A );
				if ( $settings_val ) {
					$bdp_settings = $settings_val[0]['bdsettings'];
				}
				if ( is_serialized( $bdp_settings ) ) {
					$bdp_settings = maybe_unserialize( $bdp_settings );
				}
			}
			$txt_post_id = isset( $_POST['txtPostId'] ) ? sanitize_text_field( wp_unslash( $_POST['txtPostId'] ) ) : 0;
			$post        = get_post( $txt_post_id, 'OBJECT' );
			setup_postdata( $post );
			$mail_subject = ( isset( $bdp_settings['mail_subject'] ) && '' != $bdp_settings['mail_subject'] ) ? $bdp_settings['mail_subject'] : '[post_title]';
			$mail_subject = str_replace( '[post_title]', get_the_title(), $mail_subject );
			if ( isset( $bdp_settings['mail_content'] ) && '' != $bdp_settings['mail_content'] ) {
				$contents = html_entity_decode( $bdp_settings['mail_content'] );
			} else {
				$contents = esc_html__( 'My Dear friends', 'blog-designer-pro' ) . '<br/><br/>' . esc_html__( 'I read one good blog link and I would like to share that same link for you. That might useful for you', 'blog-designer-pro' ) . '<br/><br/>[post_link]<br/><br/>' . esc_html__( 'Best Regards', 'blog-designer-pro' ) . ',<br/>' . esc_html__( 'Blog Designer', 'blog-designer-pro' );
			}
			$reply_to_mail = isset( $bdp_settings['reply_to_mail'] ) ? $bdp_settings['reply_to_mail'] : 0;
			$your_name     = isset( $_POST['txtYourName'] ) ? sanitize_text_field( wp_unslash( $_POST['txtYourName'] ) ) : 0;
			$to_email      = isset( $_POST['txtToEmail'] ) ? sanitize_text_field( wp_unslash( $_POST['txtToEmail'] ) ) : 0;
			$your_email    = isset( $_POST['txtYourEmail'] ) ? sanitize_text_field( wp_unslash( $_POST['txtYourEmail'] ) ) : 0;
			$contents      = apply_filters( 'the_content', $contents );
			$content       = str_replace( '[post_link]', get_the_permalink(), $contents );
			$content       = str_replace( '[post_title]', get_the_title(), $content );
			$content       = str_replace( '[sender_name]', $your_name, $content );
			$content       = str_replace( '[sender_email]', $your_email, $content );
			$content       = str_replace( '[post_thumbnail]', '<br/><img src="' . get_the_post_thumbnail_url( get_the_ID(), 'full' ) . '" /> <br/><br/>', $content );
			$content       = html_entity_decode( $content );
			$bdp_to        = $to_email;
			$bdp_name      = $your_name;
			$bdp_reply_to  = $your_email;
			$bdp_from      = get_option( 'admin_email' );
			$headers       = "MIME-Version: 1.0;\r\n";
			$headers      .= "From: $bdp_name <$bdp_from>\r\n";
			if ( isset( $reply_to_mail ) && 0 == $reply_to_mail ) {
				$headers .= "reply-to: $bdp_name <$bdp_reply_to>\r\n";
			}
			$headers .= "Content-Type: text/html; charset: utf-8;\r\n";
			$headers .= "X-Priority: 3\r\n";
			$headers .= 'X-Mailer: PHP' . phpversion() . "\r\n";
			$body     = '';
			ob_start();
			?>
			<div>
			<?php
			echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
			?>
			</div>
			<?php
			$body      = ob_get_clean();
			$mail_sent = wp_mail( $bdp_to, stripslashes_deep( html_entity_decode( $mail_subject, ENT_QUOTES, 'UTF-8' ) ), $body, $headers );
			if ( $mail_sent ) {
				echo 'sent';
			} else {
				echo 'not_sent';
			}
		}
		wp_reset_postdata();
		exit();
	}
	/**
	 * Get Blog Designer Shortode
	 */
	public function shortcode_regex() {
		// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
		// Also, see shortcode_unautop() and shortcode.js.
		return '\\['                   // Opening bracket.
			. '(\\[?)'              // 1: Optional second opening bracket for escaping shortcodes: [[tag]].
			. '(wp_blog_designer)'  // 2: Shortcode name.
			. '(?![\\w-])'          // Not followed by word character or hyphen.
			. '('                   // 3: Unroll the loop: Inside the opening shortcode tag.
			. '[^\\]\\/]*'          // Not a closing bracket or forward slash.
			. '(?:'
			. '\\/(?!\\])'          // A forward slash not followed by a closing bracket.
			. '[^\\]\\/]*'          // Not a closing bracket or forward slash.
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)'               // 4: Self closing tag ...
			. '\\]'                 // ... and closing bracket.
			. '|'
			. '\\]'                 // Closing bracket.
			. '(?:'
			. '('                   // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags.
			. '[^\\[]*+'            // Not an opening bracket.
			. '(?:'
			. '\\[(?!\\/\\2\\])'    // An opening bracket not followed by the closing shortcode tag.
			. '[^\\[]*+'            // Not an opening bracket.
			. ')*+'
			. ')'
			. '\\[\\/\\2\\]'        // Closing shortcode tag.
			. ')?'
			. ')'
			. '(\\]?)';             // 6: Optional second closing brocket for escaping shortcodes: [[tag]].
	}
	/**
	 * Pagination with Isotop Filter
	 */
	public function ajax_filter_posts() {
		global $wpdb;
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$layout = isset( $_POST['blog_layout'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['blog_layout'] ) ) ) : '';
			if ( 'blog_layout' === $layout ) {
				$blog_shortcode_id = isset( $_POST['blog_shortcode_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['blog_shortcode_id'] ) ) ) : '';
				$max_num_pages     = isset( $_POST['max_num_pages'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['max_num_pages'] ) ) ) : '';
				if ( ! empty( $_POST['cat_tag_count'] ) ) {
					$found_posts = isset( $_POST['cat_tag_count'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['cat_tag_count'] ) ) ) : '';
				} else {
					$found_posts = isset( $_POST['found_posts'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['found_posts'] ) ) ) : '';
				}
				$table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
				if ( is_numeric( $blog_shortcode_id ) ) {
					$settings_val = $wpdb->get_results( $wpdb->prepare( "SELECT bdsettings FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %d", $blog_shortcode_id ), ARRAY_A );
					$allsettings  = $settings_val[0]['bdsettings'];
				}
				if ( isset( $allsettings ) && is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$post_type            = $bdp_settings['custom_post_type'];
				$unique_design_option = isset( $bdp_settings['unique_design_option'] ) ? $bdp_settings['unique_design_option'] : '';
				$posts_per_page       = $bdp_settings['posts_per_page'];
				$blog_unique_design   = 0;
				if ( isset( $bdp_settings['blog_unique_design'] ) && '' != $bdp_settings['blog_unique_design'] ) {
					$blog_unique_design = $bdp_settings['blog_unique_design'];
				}
				$paged     = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) : '';
				$offset    = ( $paged - 1 ) * $posts_per_page;
				$bdp_theme = isset( $_POST['blog_template'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['blog_template'] ) ) ) : '';
				$tags      = '';
				$cats      = '';
				$author    = '';
				$order     = 'DESC';
				$orderby   = 'date';

				if ( isset( $bdp_settings['template_category'] ) ) {
					$bdp_settings['template_category'] = implode( ',', $bdp_settings['template_category'] );
				}
				if ( isset( $_POST['filter_cat'] ) ) {
					$bdp_settings['template_category'] = sanitize_text_field( wp_unslash( $_POST['filter_cat'] ) );
				}
				if ( isset( $_POST['filter_tag'] ) ) {
					$bdp_settings['template_tags'] = sanitize_text_field( wp_unslash( $_POST['filter_tag'] ) );
				}
				if ( isset( $_POST['filter_category'] ) ) {
					$bdp_settings['template_category'] = str_replace( '.', '', sanitize_text_field( wp_unslash( $_POST['filter_category'] ) ) );
				}
				if ( isset( $_POST['filter_tag'] ) ) {
					$bdp_settings['template_tags'] = str_replace( '.', '', sanitize_text_field( wp_unslash( $_POST['filter_tag'] ) ) );
				}
				if ( isset( $bdp_settings['template_category'] ) ) {
					if ( isset( $_POST['terms'] ) && isset( $_POST['filter_type'] ) && 'category' == $_POST['filter_type'] ) {
						if ( isset( $_POST['filter_category'] ) ) {
							$cat = sanitize_text_field( wp_unslash( $_POST['filter_category'] ) );
						}
						if ( empty( $_POST['filter_category'] ) ) {
							$bdp_settings['template_category'] = sanitize_text_field( wp_unslash( $_POST['terms'] ) );
							$cat                               = $bdp_settings['template_category'];
							if ( ! is_array( $cat ) ) {
								$cat = explode( ',', $cat );
							}
						}
					} else {
						$cat = $bdp_settings['template_category'];
						if ( ! is_array( $cat ) ) {
							$cat = explode( ',', $cat );
						}
					}
				}
				if ( isset( $bdp_settings['template_tags'] ) ) {
					if ( isset( $_POST['terms'] ) && isset( $_POST['filter_type'] ) && 'post_tag' == $_POST['filter_type'] ) {
						if ( isset( $_POST['filter_tag'] ) ) {
							$tag = sanitize_text_field( wp_unslash( $_POST['filter_tag'] ) );
						}
						if ( empty( $_POST['filter_tag'] ) ) {
							$bdp_settings['template_tags'] = sanitize_text_field( wp_unslash( $_POST['terms'] ) );
							$tag                           = $bdp_settings['template_tags'];
							if ( ! is_array( $tag ) ) {
								$tag = explode( ',', $tag );
							}
						}
					} else {
						$tag = $bdp_settings['template_tags'];
						if ( ! is_array( $tag ) ) {
							$tag = explode( ',', $tag );
						}
					}
				}
				if ( isset( $bdp_settings['template_authors'] ) ) {
					$author = $bdp_settings['template_authors'];
				}
				if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
					$orderby = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
				} elseif ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
					$order = $bdp_settings['bdp_blog_order'];
				}
				if ( empty( $cat ) ) {
					$cat = '';
				}
				if ( empty( $tag ) ) {
					$tag = '';
				}
				if ( isset( $bdp_settings['exclude_category_list'] ) ) {
					$exlude_category = 'category__not_in';
				} else {
					$exlude_category = 'category__in';
				}
				if ( isset( $bdp_settings['exclude_tag_list'] ) ) {
					$exlude_tag = 'tag__not_in';
				} else {
					$exlude_tag = 'tag__in';
				}
				if ( isset( $bdp_settings['exclude_author_list'] ) ) {
					$exlude_author = 'author__not_in';
				} else {
					$exlude_author = 'author__in';
				}
				/**
				 * Time Period
				 */
				$date_query  = array();
				$post_status = array();
				if ( isset( $bdp_settings['blog_time_period'] ) ) {
					$blog_time_period = $bdp_settings['blog_time_period'];
					if ( 'today' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
								'day'   => $today['mday'],
							),
						);
					}
					if ( 'tomorrow' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * DAY_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
								'day'   => $twodayslater['mday'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'this_week' === $blog_time_period ) {
						$week       = gmdate( 'W' );
						$year       = gmdate( 'Y' );
						$date_query = array(
							array(
								'year' => $year,
								'week' => $week,
							),
						);
					}
					if ( 'last_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 1 != $thisweek ) :
							$lastweek = $thisweek - 1;
						else :
							$lastweek = 52;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) - 1;
						endif;
						$date_query = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
					}
					if ( 'next_week' === $blog_time_period ) {
						$thisweek = gmdate( 'W' );
						if ( 52 != $thisweek ) :
							$lastweek = $thisweek + 1;
						else :
							$lastweek = 1;
						endif;
						$year = gmdate( 'Y' );
						if ( 52 != $lastweek ) :
							$year = gmdate( 'Y' );
						else :
							$year = gmdate( 'Y' ) + 1;
						endif;
						$date_query  = array(
							array(
								'year' => $year,
								'week' => $lastweek,
							),
						);
						$post_status = array( 'future' );
					}
					if ( 'this_month' === $blog_time_period ) {
						$today      = getdate();
						$date_query = array(
							array(
								'year'  => $today['year'],
								'month' => $today['mon'],
							),
						);
					}
					if ( 'last_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) - 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
					}
					if ( 'next_month' === $blog_time_period ) {
						$twodayslater = getdate( current_time( 'timestamp' ) + 1 * MONTH_IN_SECONDS );
						$date_query   = array(
							array(
								'year'  => $twodayslater['year'],
								'month' => $twodayslater['mon'],
							),
						);
						$post_status  = array( 'future' );
					}
					if ( 'last_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$last_n_days = $bdp_settings['bdp_time_period_day'] . ' days ago';
							$date_query  = array(
								array(
									'after'     => $last_n_days,
									'inclusive' => true,
								),
							);
						}
					}
					if ( 'next_n_days' === $blog_time_period ) {
						if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
							$next_n_days = '+' . $bdp_settings['bdp_time_period_day'] . ' days';
							$date_query  = array(
								array(
									'before'    => gmdate( 'Y-m-d', strtotime( $next_n_days ) ),
									'inclusive' => true,
								),
							);
							$post_status = array( 'future' );
						}
					}
					if ( 'between_two_date' === $blog_time_period ) {
						$between_two_date_from = isset( $bdp_settings['between_two_date_from'] ) ? $bdp_settings['between_two_date_from'] : '';
						$between_two_date_to   = isset( $bdp_settings['between_two_date_to'] ) ? $bdp_settings['between_two_date_to'] : '';
						$from_format           = array();
						$after                 = array();
						if ( $between_two_date_from ) {
							$unixtime  = strtotime( $between_two_date_from );
							$from_time = gmdate( 'm-d-Y', $unixtime );
							if ( $from_time ) {
								$from_format = explode( '-', $from_time );
								$after       = array(
									'year'  => isset( $from_format[2] ) ? $from_format[2] : '',
									'month' => isset( $from_format[0] ) ? $from_format[0] : '',
									'day'   => isset( $from_format[1] ) ? $from_format[1] : '',
								);
							}
						}
						$to_format = array();
						$before    = array();
						if ( $between_two_date_to ) {
							$unixtime = strtotime( $between_two_date_to );
							$to_time  = gmdate( 'm-d-Y', $unixtime );
							if ( $to_time ) {
								$to_format = explode( '-', $to_time );
								$before    = array(
									'year'  => isset( $to_format[2] ) ? $to_format[2] : '',
									'month' => isset( $to_format[0] ) ? $to_format[0] : '',
									'day'   => isset( $to_format[1] ) ? $to_format[1] : '',
								);
							}
						}
						$date_query = array(
							array(
								'after'     => $after,
								'before'    => $before,
								'inclusive' => true,
							),
						);
					}
				} else {
					$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
				}
				if ( 'post' === $post_type ) {
					$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								$exlude_category => $cat,
								$exlude_tag      => $tag,
								$exlude_author   => $author,
								'offset'         => $offset,
								'post_type'      => $post_type,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby . ' date',
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								'meta_query'     => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					} elseif ( 1 == $bdp_settings['display_filter'] ) {
						if ( '' != $cat || '' != $tag ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									'tax_query'      => $tax_query,
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} else {
							$more_posts = get_posts(
								array(
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						}
					} else {
						$tax_query = array();
						if ( '' != $cat && '' != $tag ) {
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} elseif ( '' != $cat || '' != $tag ) {
							if ( 0 == $bdp_settings['display_filter'] ) {
								$cat = implode( ',', $cat );
								$tag = implode( ',', $tag );
							}
							$tax_query  = array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'category',
									'field'    => 'term_id',
									'terms'    => $cat,
								),
								array(
									'taxonomy' => 'post_tag',
									'field'    => 'term_id',
									'terms'    => $tag,
								),
							);
							$more_posts = get_posts(
								array(
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'tax_query'      => $tax_query,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						} else {
							$more_posts = get_posts(
								array(
									$exlude_category => $cat,
									$exlude_tag      => $tag,
									$exlude_author   => $author,
									'offset'         => $offset,
									'post_type'      => $post_type,
									'posts_per_page' => $posts_per_page,
									'paged'          => $paged,
									'orderby'        => $orderby,
									'order'          => $order,
									'post__not_in'   => get_option( 'sticky_posts' ),
									'date_query'     => $date_query,
									'post_status'    => $post_status,
								)
							);
						}
					}
				} else {
					$taxo           = get_object_taxonomies( $post_type );
					$tax_query      = array( 'relation' => 'OR' );
					$custom_taxonom = '';
					foreach ( $taxo as $taxonom ) {
						if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) ) {
							if ( ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
								$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
							}
							if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) && ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
								$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
							}
							$tax_query[] = array(
								'taxonomy' => $taxonom,
								'field'    => 'name',
								'terms'    => $custom_taxonom,
							);
						}
					}
					if ( 'meta_value_num' === $orderby ) {
						$more_posts = get_posts(
							array(
								'post_type'      => $post_type,
								'tax_query'      => $tax_query,
								'offset'         => $offset,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby . ' date',
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								$exlude_author   => $author,
								'meta_query'     => array(
									'relation' => 'OR',
									array(
										'key'     => '_post_like_count',
										'compare' => 'NOT EXISTS',
									),
									array(
										'key'     => '_post_like_count',
										'compare' => 'EXISTS',
									),
								),
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					} else {
						$more_posts = get_posts(
							array(
								'post_type'      => $post_type,
								'tax_query'      => $tax_query,
								'offset'         => $offset,
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'orderby'        => $orderby,
								'order'          => $order,
								'post__not_in'   => get_option( 'sticky_posts' ),
								$exlude_author   => $author,
								'date_query'     => $date_query,
								'post_status'    => $post_status,
							)
						);
					}
				}
				$sticky_posts = get_option( 'sticky_posts' );
				$sticky_count = count( $sticky_posts );
				$alter_class  = '';
				$alter        = $offset + 1;
				if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
					$alter = $alter + $sticky_count;
				}
				$prev_year         = isset( $_POST['timeline_previous_year'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['timeline_previous_year'] ) ) ) : '';
				$prev_year1        = null;
				$prev_month        = isset( $_POST['timeline_previous_month'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['timeline_previous_month'] ) ) ) : '';
				$count_sticky      = 0;
				$same_height_class = '';
				$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0';
				if ( 1 == $apply_same_height ) {
					$same_height_class = 'same_height_all';
				}
				if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
					echo "<div class='bdp-row " . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . "'>";
				}
				if ( 'brit_co' === $bdp_theme ) {
					echo '<div class="brit_co bdp_brit_co">';
				}
				if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
					$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . $bdp_settings['column_setting'] : 'column_layout_2';
					$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . $bdp_settings['column_setting_ipad'] : 'column_layout_ipad_2';
					$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . $bdp_settings['column_setting_tablet'] : 'column_layout_tablet_1';
					$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . $bdp_settings['column_setting_mobile'] : 'column_layout_mobile_1';
					$column_class          = $column_setting . ' ' . $column_setting_ipad . ' ' . $column_setting_tablet . ' ' . $column_setting_mobile;
					if ( 'roctangle' === $bdp_theme ) {
						echo '<div class="bdp-row masonry ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
					} else {
						echo '<div class="bdp-row ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . '">';
					}
				}
				if ( 'wise_block' === $bdp_theme ) {
					echo '<div class="blog_template wise_block_wrapper ' . esc_attr( $same_height_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
				}
				if ( 'soft_block' === $bdp_theme ) {
					echo '<div class="blog_template soft_block_wrapper">';
				}
				if ( 'schedule' === $bdp_theme ) {
					echo '<div class="blog_template schedule_wrapper">';
				}
				if ( 'my_diary' === $bdp_theme ) {
					echo '<div class="my_diary_wrapper">';
				}
				if ( 'foodbox' === $bdp_theme ) {
					echo '<div class="foodbox-blog-wrapp">';
				}
				if ( $more_posts ) {
					global $post;
					$i = 1;
					foreach ( $more_posts as $post ) :
						setup_postdata( $post );
						if ( $bdp_theme ) {
							if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = ' alternative-back ';
								} else {
									$alter_class = '';
								}
							}
							if ( 1 == $blog_unique_design ) {
								if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'clicky' === $bdp_theme ) {
									$alter_class = $alter;
									// are we on page one?
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
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							Bdp_Template::get_blog_loadmore_template( 'blog/' . $bdp_theme . '.php', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky );
							++$alter;
						}
						echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $i, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
						++$i;
					endforeach;
				}
				if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'brit_co' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'wise_block' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'soft_block' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'schedule' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'my_diary' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'foodbox' === $bdp_theme ) {
					echo '</div>';
				}
				if ( is_front_page() ) {
					$bdppaged = isset( $_POST['paged'] ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : get_query_var( 'page' );
				} else {
					$bdppaged = isset( $_POST['paged'] ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : get_query_var( 'paged' );
				}
				if ( isset( $bdp_settings['template_category'] ) || isset( $_POST['terms'] ) ) {
					$bdp_settings['template_category'] = sanitize_text_field( wp_unslash( $_POST['terms'] ) );
					$filter_terms                      = explode( ',', $bdp_settings['template_category'] );
					$filter_terms                      = implode( ',', $filter_terms );
				} elseif ( isset( $bdp_settings['template_tags'] ) || isset( $_POST['terms'] ) ) {
					$bdp_settings['template_tags'] = sanitize_text_field( wp_unslash( $_POST['terms'] ) );
					$filter_terms                  = explode( ',', $bdp_settings['template_tags'] );
					$filter_terms                  = implode( ',', $filter_terms );
				}
				echo '<form name="bdp-paged-hidden" id="bdp-paged-hidden">';
				echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
				echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
				echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
				echo '<input type="hidden" name="found_posts" id="found_posts" value="' . esc_attr( $found_posts ) . '" />';
				echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
				echo '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
				echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $blog_shortcode_id ) . '" />';
				echo '<input type="hidden" name="terms" id="terms" value="' . esc_attr( $filter_terms ) . '" />';
				if ( 1 == $bdp_settings['display_filter'] ) {
					echo '<input type="hidden" name="filter_type" id="filter_type" value="' . esc_attr( $bdp_settings['display_filter_by'] ) . '" />';
					echo '<input type="hidden" name="filter_category" id="filter_category" value="" />';
					echo '<input type="hidden" name="cat_tag_count" id="cat_tag_count" value="" />';
					echo '<input type="hidden" name="filter_tag" id="filter_tag" value="" />';
				}
				echo '</form>';
				$cat_tag_count = isset( $_POST['cat_tag_count'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_tag_count'] ) ) : '';
				if ( empty( $cat_tag_count ) || $cat_tag_count > $posts_per_page ) {
					$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
					echo '<div class="filter wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
					echo wp_kses( Bdp_Posts::shortcode_standard_paging_nav( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
					echo '</div>';
				}
			}
		}
		wp_reset_postdata();
		$data = ob_get_clean();
		echo $data;
		die();
	}
}

new Bdp_Front_Functions();
