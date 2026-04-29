<?php
/**
 * The WooCommerce functionality of the plugin.
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
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Woocommerce
 * @version 1.0.0
 */
class Bdp_Woocommerce {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'is_woocommerce_plugin' ) );
		add_action( 'bdp_woocommerce_price', array( $this, 'woocommerce_price' ) );
		add_action( 'bdp_woocommerce_product_rating', array( $this, 'woocommerce_product_rating' ) );
		add_action( 'bdp_woocommerce_add_to_cart', array( $this, 'woocommerce_add_to_cart' ) );
		add_action( 'bdp_woocommerce_add_to_wishlist', array( $this, 'woocommerce_add_to_wishlist' ) );
		add_action( 'bdp_woocommerce_sale_tag', array( $this, 'woocommerce_sale_tag' ) );
		add_action( 'bdp_woocommerce_show_product_images', array( $this, 'woocommerce_product_images' ), 10, 2 );
		add_action( 'bdp_woocommerce_meta_data', array( $this, 'woocommerce_meta_data_box' ), 10, 2 );
		add_action( 'do_woocommerce_after_single_product_summary', array( $this, 'woocommerce_template_product_reviews' ), 50 );
		add_action( 'bdp_woocommerce_product_details_function', array( $this, 'woocommerce_product_details' ), 10, 2 );
		add_action( 'wp_ajax_nopriv_bdp_get_single_product', array( $this, 'get_single_product_ajax' ) );
		add_action( 'wp_ajax_bdp_get_single_product', array( $this, 'get_single_product_ajax' ) );
	}
	/**
	 * Check woocommerce plugin active
	 *
	 * @since 2.6
	 */
	public static function is_woocommerce_plugin() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Display woocommerce product price
	 *
	 * @since 2.6
	 */
	public function woocommerce_price() {
		if ( self::is_woocommerce_plugin() ) {
			woocommerce_template_loop_price();
		}
	}
	/**
	 * Display woocommerce product rating
	 *
	 * @since 2.6
	 */
	public function woocommerce_product_rating() {
		if ( self::is_woocommerce_plugin() ) {
			woocommerce_template_loop_rating();
		}
	}
	/**
	 * Add Woocommerce Add To Cart Button Display
	 *
	 * @since 2.6
	 */
	public function woocommerce_add_to_cart() {
		if ( self::is_woocommerce_plugin() ) {
			woocommerce_template_loop_add_to_cart();
		}
	}
	/**
	 * Add Woocommerce Add To Wishlist Button Display
	 *
	 * @since 2.6
	 */
	public function woocommerce_add_to_wishlist() {
		if ( self::is_woocommerce_plugin() ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
	}
	/**
	 * Display Woocommerce Sale tag
	 *
	 * @since 2.6
	 */
	public function woocommerce_sale_tag() {
		if ( self::is_woocommerce_plugin() ) {
			woocommerce_show_product_loop_sale_flash();
		}
	}
	/**
	 * Display woocommerce product images
	 *
	 * @param array $bdp_settings settings.
	 * @param int   $bdp_post_id id.
	 * @since 2.6
	 */
	public function woocommerce_product_images( $bdp_settings, $bdp_post_id ) {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
		/* This code is commented may be use later add_action('woocommerce_before_single_product_summary', 'bdp_woocommerce_sale_tag', 10 );. */
		$post_type = get_post_type( $bdp_post_id );
		if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
			echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_settings['bdp_sale_tagtext_alignment'] ) . '">';
			do_action( 'bdp_woocommerce_sale_tag' );
			echo '</div>';
		}
		do_action( 'woocommerce_before_single_product_summary' );
	}
	/**
	 * Display woocommerce meta
	 *
	 * @param array $bdp_settings settings.
	 * @param array $bdp_post_id post id.
	 * @since 2.7
	 */
	public function woocommerce_meta_data_box( $bdp_settings, $bdp_post_id ) {
		if ( 1 == isset( $bdp_settings['display_product_price'] ) || 1 == isset( $bdp_settings['display_addtocart_button'] ) || class_exists( 'YITH_WCWL' ) || 1 == isset( $bdp_settings['display_product_rating'] ) ) {
			?>
			<div class="bdp_woocommerce_meta_box">
				<?php
				$_product = wc_get_product( $bdp_post_id );
				if ( isset( $bdp_settings['display_product_price'] ) && 1 == $bdp_settings['display_product_price'] ) {
					echo '<div class="bdp_woocommerce_price_wrap">';
					do_action( 'bdp_woocommerce_price' );
					echo '</div>';
				}
				if ( isset( $bdp_settings['display_product_rating'] ) && 1 == $bdp_settings['display_product_rating'] && get_comments_number( $bdp_post_id ) > 0 ) {
					echo '<div class="bdp_woocommerce_star_wrap">';
					do_action( 'bdp_woocommerce_product_rating' );
					echo '</div>';
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && isset( $bdp_settings['bdp_wishlistbutton_on'] ) && 1 == $bdp_settings['display_addtowishlist_button'] && 1 == $bdp_settings['bdp_wishlistbutton_on'] ) {
						$bdp_cart_wishlistbutton_alignment = ( isset( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) && ! empty( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) ) ? $bdp_settings['bdp_cart_wishlistbutton_alignment'] : '0';
						$bdp_cartwishlist_wrapp            = '';
						if ( '' != $bdp_cart_wishlistbutton_alignment ) {
							$bdp_cartwishlist_wrapp = 'bdp_cartwishlist_wrapp';
						}
						echo '<div class="bdp_wishlistbutton_on_same_line ' . esc_attr( $bdp_cartwishlist_wrapp ) . '">';
					}
				}
				if ( isset( $bdp_settings['display_addtocart_button'] ) && 1 == $bdp_settings['display_addtocart_button'] ) {
					echo '<div class="bdp_woocommerce_add_to_cart_wrap">';
					if ( $_product->is_type( 'simple' ) ) {
						do_action( 'woocommerce_simple_add_to_cart' );
					} elseif ( $_product->is_type( 'variable' ) ) {
						do_action( 'woocommerce_variable_add_to_cart' );
					} elseif ( $_product->is_type( 'grouped' ) ) {
						do_action( 'woocommerce_grouped_add_to_cart' );
					} elseif ( $_product->is_type( 'external' ) ) {
						do_action( 'woocommerce_external_add_to_cart' );
					} else {
						do_action( 'woocommerce_single_variation' );
					}
					echo '</div>';
				}
				if ( isset( $bdp_settings['display_sku_product'] ) && 1 == $bdp_settings['display_sku_product'] ) {
					if ( wc_product_sku_enabled() && ( $_product->get_sku() || $_product->is_type( 'variable' ) ) ) :
						$sku = $_product->get_sku();
						if ( ! $sku ) {
							$sku = esc_html__( 'N/A', 'blog-designer-pro' );
						}
						?>
						<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'blog-designer-pro' ); ?> <span class="sku"><?php echo esc_html( $sku ); ?></span></span>
						<?php
					endif;
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && 1 == $bdp_settings['display_addtowishlist_button'] ) {
						echo '<div class="bdp_woocommerce_add_to_wishlist_wrap">';
						do_action( 'bdp_woocommerce_add_to_wishlist' );
						echo '</div>';
					}
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && isset( $bdp_settings['bdp_wishlistbutton_on'] ) && 1 == $bdp_settings['display_addtowishlist_button'] && 1 == $bdp_settings['bdp_wishlistbutton_on'] ) {
						echo '</div>';
					}
				}
				?>
			</div>
			<?php
		}
	}
	/**
	 * Display woocommerce product rating
	 *
	 * @since 2.6
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	public function woocommerce_template_product_reviews( $bdp_settings ) {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			echo '<div id="comments" class="comments-area">';
			wc_get_template( 'single-product-reviews.php' );
			echo '</div>';
		}
	}
	/**
	 * Redirect to a preferred template.
	 *
	 * @since 2.6
	 * @param string $default_template template.
	 * @return string $default_template
	 */
	public static function custom_single_product_template( $default_template ) {
		global $post;
		$post_type = $post->post_type;
		if ( 'product' === $post_type ) {
			$post_id     = $post->ID;
			$cat_ids     = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
			$tag_ids     = wp_get_post_terms( $post_id, 'product_tag', array( 'fields' => 'ids' ) );
			$single_data = Bdp_Template::get_single_prodcut_template_settings( $cat_ids, $tag_ids );
			if ( ! $single_data ) {
				return $default_template;
			}
			if ( $single_data && is_serialized( $single_data ) ) {
				$single_data_setting = maybe_unserialize( $single_data );
			}
			if ( ! isset( $single_data_setting['template_name'] ) || ( isset( $single_data_setting['template_name'] ) && '' == $single_data_setting['template_name'] ) ) {
				return $default_template;
			}
			if ( isset( $single_data_setting['override_single'] ) && 1 == $single_data_setting['override_single'] ) {
				$default_template = get_stylesheet_directory() . '/bdp_templates/woocommerce/single/single-product.php';
				if ( ! file_exists( $default_template ) ) {
					$default_template = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/single/single-product.php';
				}
				$default_template = apply_filters( 'custom_single_product_template', $default_template, $single_data_setting );
			}
		}
		return $default_template;
	}
	/**
	 * Display woocommerce product details on front side
	 *
	 * @param array $bdp_settings settings.
	 * @param int   $post_id post id.
	 * @since 2.7
	 */
	public function woocommerce_product_details( $bdp_settings, $post_id ) {
		if ( 1 == isset( $bdp_settings['display_product_price'] ) || 1 == isset( $bdp_settings['display_addtocart_button'] ) || class_exists( 'YITH_WCWL' ) || 1 == isset( $bdp_settings['display_product_rating'] ) ) {
			?>
			<div class="bdp_woocommerce_meta_box">
				<?php
				if ( isset( $bdp_settings['display_product_price'] ) && 1 == $bdp_settings['display_product_price'] ) {
					echo '<div class="bdp_woocommerce_price_wrap">';
					do_action( 'bdp_woocommerce_price' );
					echo '</div>';
				}
				if ( isset( $bdp_settings['display_product_rating'] ) && 1 == $bdp_settings['display_product_rating'] ) {
					echo '<div class="bdp_woocommerce_star_wrap">';
					do_action( 'bdp_woocommerce_product_rating' );
					echo '</div>';
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && isset( $bdp_settings['bdp_wishlistbutton_on'] ) && 1 == $bdp_settings['display_addtowishlist_button'] && 1 == $bdp_settings['bdp_wishlistbutton_on'] ) {
						$bdp_cart_wishlistbutton_alignment = ( isset( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) && ! empty( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) ) ? $bdp_settings['bdp_cart_wishlistbutton_alignment'] : '0';
						$bdp_cartwishlist_wrapp            = '';
						if ( '' != $bdp_cart_wishlistbutton_alignment ) {
							$bdp_cartwishlist_wrapp = 'bdp_cartwishlist_wrapp';
						}
						echo '<div class="bdp_wishlistbutton_on_same_line ' . esc_attr( $bdp_cartwishlist_wrapp ) . '">';
					}
				}
				if ( isset( $bdp_settings['display_addtocart_button'] ) && 1 == $bdp_settings['display_addtocart_button'] ) {
					echo '<div class="bdp_woocommerce_add_to_cart_wrap">';
					do_action( 'bdp_woocommerce_add_to_cart' );
					echo '</div>';
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && 1 == $bdp_settings['display_addtowishlist_button'] ) {
						echo '<div class="bdp_woocommerce_add_to_wishlist_wrap">';
						do_action( 'bdp_woocommerce_add_to_wishlist' );
						echo '</div>';
					}
				}
				if ( class_exists( 'YITH_WCWL' ) ) {
					if ( isset( $bdp_settings['display_addtowishlist_button'] ) && isset( $bdp_settings['bdp_wishlistbutton_on'] ) && 1 == $bdp_settings['display_addtowishlist_button'] && 1 == $bdp_settings['bdp_wishlistbutton_on'] ) {
						echo '</div>';
					}
				}
				?>
			</div>
			<?php
		}
	}
	/**
	 * Display Front side Single Product Blog Designer Layout
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return array Get bdp settings
	 */
	public static function get_single_product_template_settings() {
		global $post, $wpdb;
		$post_id                  = $post->ID;
		$cat_ids                  = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
		$tag_ids                  = wp_get_post_terms( $post_id, 'product_tag', array( 'fields' => 'ids' ) );
		$all_settings             = $wpdb->get_row( 'SELECT settings FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "all"' );
		$single_template          = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE find_in_set(%d,single_product_id) <> 0", $post_id ) );
		$single_category_template = '';
		$single_tag_template      = '';
		if ( $cat_ids ) {
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'category' AND find_in_set(%d,sub_categories) <> 0", $cat_id ) );
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
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'tag' AND find_in_set(%d,sub_categories) <> 0", $tag_id ) );
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
		if ( $single_template ) {
			if ( isset( $single_template->settings ) && is_serialized( $single_template->settings ) ) {
				$bdp_settings = maybe_unserialize( $single_template->settings );
			}
		} elseif ( $cat_ids && $single_category_template ) {
			if ( $category_template_blank ) {
				$bdp_settings = isset( $category_template_blank->settings ) ? maybe_unserialize( $category_template_blank->settings ) : '';
			} else {
				$bdp_settings = isset( $all_settings->settings ) ? maybe_unserialize( $all_settings->settings ) : '';
			}
			foreach ( $cat_ids as $cat_id ) {
				if ( is_numeric( $cat_id ) ) {
					$category_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'category' AND find_in_set(%d,sub_categories) <> 0", $cat_id ) );
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
				$bdp_settings = isset( $all_settings->settings ) ? maybe_unserialize( $all_settings->settings ) : '';
			}
			foreach ( $tag_ids as $tag_id ) {
				if ( is_numeric( $tag_id ) ) {
					$tag_template = $wpdb->get_row( $wpdb->prepare( "SELECT settings FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = 'tag' AND find_in_set(%d,sub_categories) <> 0", $tag_id ) );
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
		} elseif ( $all_settings ) {
			if ( isset( $all_settings->settings ) && is_serialized( $all_settings->settings ) ) {
				$bdp_settings = maybe_unserialize( $all_settings->settings );
			}
		} else {
			$bdp_settings = array();
		}
		return $bdp_settings;
	}
	/**
	 * Get all product on change
	 *
	 * @since 2.6
	 */
	public function get_single_product_ajax() {
		ob_start();
		?>
		<?php
		$args = array(
			'cache_results' => false,
			'no_found_rows' => true,
			'fields'        => 'ids',
			'showposts'     => '-1',
			'post_status'   => 'publish',
			'post_type'     => 'product',
		);
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['tax_ids'] ) && '' != $_POST['tax_ids'] ) {
				if ( isset( $_POST['tax'] ) && 'product_cat' === $_POST['tax'] || 'product_tag' === $_POST['tax'] ) {
					$tax_ids           = isset( $_POST['tax_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['tax_ids'] ) ) : '';
					$args['tax_query'] = array(
						array(
							'taxonomy' => esc_attr( sanitize_text_field( wp_unslash( $_POST['tax'] ) ) ),
							'field'    => 'term_id',
							'terms'    => (array) $tax_ids,
						),
					);
				}
			}
		}
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			?>
			<?php
			echo '<select name="template_posts[]" id="template_posts" class="chosen-select" multiple  data-placeholder="' . esc_attr__( 'Choose Products', 'blog-designer-pro' ) . '">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post__id = get_the_ID();
				?>
				<option value="<?php echo esc_attr( $post__id ); ?>"><?php echo esc_html( get_the_title( $post__id ) ); ?></option>
				<?php
			}
			wp_reset_postdata();
			echo '</select>';
		} else {
			echo '<p>' . esc_html__( 'No Product Found', 'blog-designer-pro' ) . '</p>';
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get all archive list
	 *
	 * @since 2.6
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	public static function get_product_archive_list() {
		global $wpdb;
		$archive_array = array();
		$archives      = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_product_archives ORDER BY id DESC' );
		if ( $archives ) {
			foreach ( $archives as $archive ) {
				$archive_array[ $archive->id ] = $archive->product_archive_template;
			}
		}
		return $archive_array;
	}
	/**
	 * Add pagination for product layout
	 *
	 * @since 2.6
	 * @param string $bdp_settings Settings.
	 */
	public static function standard_product_paging_nav( $bdp_settings ) {
		if ( $GLOBALS['wp_product_query']->max_num_pages < 2 ) {
			return;
		}
		$navigation   = '';
		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link         = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link         = trailingslashit( $pagenum_link ) . '%_%';
		$next_button_text     = isset( $bdp_settings['next_button_text'] ) ? $bdp_settings['next_button_text'] : '';
		$previous_button_text = isset( $bdp_settings['previous_button_text'] ) ? $bdp_settings['previous_button_text'] : '';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links(
			array(
				'base'      => $pagenum_link,
				'format'    => $format,
				'total'     => $GLOBALS['wp_product_query']->max_num_pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'add_args'  => array_map( 'urlencode', $query_args ),
				'prev_text' => '&larr; ' . $previous_button_text,
				'next_text' => $next_button_text . ' &rarr;',
				'type'      => 'list',
			)
		);

		if ( $links ) :
			$navigation .= '<nav class="navigation paging-navigation" role="navigation">';
			$navigation .= $links;
			$navigation .= '</nav>';
		endif;
		return $navigation;
	}
	/**
	 * Get parameter array for archive products query
	 *
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 * @since 2.6
	 */
	public static function get_product_archive_wp_query( $bdp_settings ) {
		$posts_per_page = isset( $bdp_settings['posts_per_page'] ) ? $bdp_settings['posts_per_page'] : 5;
		$orderby        = 'date';
		$order          = 'DESC';
		if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
			$orderby = $bdp_settings['bdp_blog_order_by'];
		}
		if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
			$order = $bdp_settings['bdp_blog_order'];
		}
		$paged       = Bdp_Utility::paged();
		$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
		$post_author = isset( $bdp_settings['template_author'] ) ? $bdp_settings['template_author'] : array();

		if ( isset( $bdp_settings['custom_archive_type'] ) && 'category_template' === $bdp_settings['custom_archive_type'] ) {
			if ( 'meta_value_num' === $orderby ) {
				$orderby_str = $orderby . ' date';
			} else {
				$orderby_str = $orderby;
			}
			$allcat    = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$cat       = $allcat->term_id;
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
		if ( isset( $bdp_settings['custom_archive_type'] ) && 'tag_template' === $bdp_settings['custom_archive_type'] ) {
			if ( 'meta_value_num' === $orderby ) {
				$orderby_str = $orderby . ' date';
			} else {
				$orderby_str = $orderby;
			}
			$allcat    = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$cat       = $allcat->term_id;
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
		return $arg_posts;
	}
}
new Bdp_Woocommerce();
