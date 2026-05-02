<?php
/**
 * The EDD functionality of the plugin.
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
 * @class   Bdp_Utility
 * @version 1.0.0
 */
class Bdp_Edd {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'bdp_easy_digital_download_product_details_function', array( $this, 'easy_digital_download_product_details' ), 10, 2 );
		add_action( 'bdp_edd_single_download_price', array( $this, 'display_edd_single_download_price' ), 10, 1 );
		add_action( 'bdp_edd_single_download_cart_button', array( $this, 'display_edd_single_download_cart_button' ), 10, 1 );
		add_action( 'wp_ajax_nopriv_bdp_get_single_download', array( $this, 'get_single_download' ) );
		add_action( 'wp_ajax_bdp_get_single_download', array( $this, 'get_single_download' ) );
	}
	/**
	 * Display Download product details on front side
	 *
	 * @param array $bdp_settings settings.
	 * @param id    $post_id post id.
	 * @since 2.7
	 */
	public function easy_digital_download_product_details( $bdp_settings, $post_id ) {
		if ( isset( $bdp_settings['display_download_price'] ) && 1 == $bdp_settings['display_download_price'] ) {
			?>
			<div class="bdp_edd_price_wrapper">
				<div itemprop="price" class="edd_price">
					<?php
					if ( edd_has_variable_prices( $post_id ) ) {
						echo wp_kses( edd_price_range( $post_id ), Bdp_Admin_Functions::args_kses() );
					} else {
						edd_price( $post_id, true );
					}
					?>
				</div>
			</div>
			<?php
		}
		if ( isset( $bdp_settings['display_edd_addtocart_button'] ) && 1 == $bdp_settings['display_edd_addtocart_button'] ) {
			echo '<div class="bdp_edd_download_buy_button">';
			?>
			<div class="edd_download_buy_button"> 
				<?php echo wp_kses( edd_get_purchase_link( array( 'download_id' => get_the_ID() ) ), array() ); ?>
			</div>
			<?php
			echo '</div>';
		}
	}
	/**
	 * Display Single download product price
	 *
	 * @param int $post_id post id.
	 * @since 2.7
	 */
	public function display_edd_single_download_price( $post_id ) {
		?>
		<div class="bdp_easy_digital_download_wrap">
			<div class="bdp_edd_price_wrapper">
				<div itemprop="price" class="edd_price">
					<?php
					if ( edd_has_variable_prices( $post_id ) ) {
						echo wp_kses( edd_price_range( $post_id ), Bdp_Admin_Functions::args_kses() );
					} else {
						edd_price( $post_id, true );
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Display Single download product cart button
	 *
	 * @param int $post_id post id.
	 * @since 2.7
	 */
	public function display_edd_single_download_cart_button( $post_id ) {
		echo '<div class="bdp_edd_download_buy_button">';
		$button_behavior = edd_get_download_button_behavior( $post_id );
		echo wp_kses(
			edd_get_purchase_link(
				array(
					'download_id' => $post_id,
					'price'       => 'no',
				)
			),
			array()
		);
		echo '</div>';
	}
	/**
	 * Get download category template settings
	 *
	 * @since 2.7
	 * @param int   $category_id category id.
	 * @param array $product_archive_list proudct archvie list.
	 * @return array Category Template settings
	 */
	public static function get_download_category_template_settings( $category_id, $product_archive_list ) {
		$bdp_category_data = array();
		$bdp_settings      = array();
		$bdp_layout_id     = '';
		if ( $product_archive_list ) {
			foreach ( $product_archive_list as $archive ) {
				if ( 'category_template' === $archive ) {
					global $wpdb;
					$category_template = $wpdb->get_row(
						$wpdb->prepare(
							'SELECT id, settings FROM %s WHERE download_archive_template = %s AND find_in_set(%s, download_sub_categories) <> 0',
							$wpdb->prefix . 'bdp_edd_archives',
							'category_template',
							$category_id
						)
					);
					if ( ! empty( $category_template ) ) {
						$bdp_layout_id = $category_template->id;
						$allsettings   = $category_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$category_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_edd_archives WHERE  download_archive_template = "category_template" AND  download_sub_categories = "" ORDER BY id DESC' );
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
	 * Get all archive list
	 *
	 * @global object $wpdb
	 * @return Array List of archive table
	 */
	public static function get_download_archive_list() {
		global $wpdb;
		$archive_array = array();
		$archives      = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_edd_archives ORDER BY id DESC' );
		if ( $archives ) {
			foreach ( $archives as $archive ) {
				$archive_array[ $archive->id ] = $archive->download_archive_template;
			}
		}
		return $archive_array;
	}
	/**
	 * Get parameter array for archive downloads query
	 *
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 * @since 2.7
	 */
	public static function get_download_archive_wp_query( $bdp_settings ) {
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
		$tax_query   = array();
		$allcat      = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		$cat         = $allcat->term_id;
		if ( isset( $bdp_settings['custom_archive_type'] ) && 'category_template' === $bdp_settings['custom_archive_type'] ) {
			$taxonomy = 'download_category';
		}
		if ( isset( $bdp_settings['custom_archive_type'] ) && 'tag_template' === $bdp_settings['custom_archive_type'] ) {
			$taxonomy = 'download_tag';
		}
		$tax_query = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $cat,
				'operator' => 'IN',
			),
		);
		if ( 'meta_value_num' === $orderby ) {
			$orderby_str = $orderby . ' date';
		} else {
			$orderby_str = $orderby;
		}
		$arg_posts = array(
			'post_type'      => 'download',
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
	/**
	 * Get all download posts on change
	 *
	 * @since 2.7
	 */
	public function get_single_download() {
		ob_start();
		?>
		<?php
		$args = array(
			'cache_results' => false,
			'no_found_rows' => true,
			'fields'        => 'ids',
			'showposts'     => '-1',
			'post_status'   => 'publish',
			'post_type'     => 'download',
		);
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['tax_ids'] ) && '' != $_POST['tax_ids'] ) {
				if ( isset( $_POST['tax'] ) && 'download_category' === $_POST['tax'] || 'download_tag' === $_POST['tax'] ) {
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
			<select name="template_posts[]" id="template_posts" class="chosen-select" multiple data-placeholder="<?php esc_attr_e( 'Choose Products', 'blog-designer-pro' ); ?>">';
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$post__id = get_the_ID();
				?>
				<option value="<?php echo esc_attr( $post__id ); ?>" ><?php echo esc_html( get_the_title( $post__id ) ); ?></option>
				<?php
			}
			wp_reset_postdata();
			echo '</select>';
		} else {
			echo '<p>' . esc_html__( 'No Product Found', 'blog-designer-pro' ) . '</p>';
		}
		$data = ob_get_clean();
		echo $data;
		// echo wp_kses( $data, array() );.
		die();
	}
	/**
	 * Redirect to a preferred template.
	 *
	 * @since 2.7
	 * @param string $default_template default template.
	 * @return string $default_template
	 */
	public static function custom_single_download_template( $default_template ) {
		global $post;
		$post_type = $post->post_type;
		if ( 'download' === $post_type ) {
			$post_id     = $post->ID;
			$cat_ids     = wp_get_post_terms( $post_id, 'download_category', array( 'fields' => 'ids' ) );
			$tag_ids     = wp_get_post_terms( $post_id, 'download_tag', array( 'fields' => 'ids' ) );
			$single_data = Bdp_Template::get_single_download_template_settings( $cat_ids, $tag_ids );
			if ( ! $single_data ) {
				return $default_template;
			}
			if ( $single_data && is_serialized( $single_data ) ) {
				$single_data_setting = maybe_unserialize( $single_data );
			}
			if ( ! isset( $single_data_setting['template_name'] ) || ( isset( $single_data_setting['template_name'] ) && '' === $single_data_setting['template_name'] ) ) {
				return $default_template;
			}
			if ( isset( $single_data_setting['override_single'] ) && 1 == $single_data_setting['override_single'] ) {
				$default_template = get_stylesheet_directory() . '/bdp_templates/edd_templates/single/single-download.php';
				if ( ! file_exists( $default_template ) ) {
					$default_template = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/single/single-download.php';
				}
			}
		}
		return $default_template;
	}
}
new Bdp_Edd();
