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
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Posts
 * @version 1.0.0
 */
class Bdp_Posts {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'next_post_link', array( $this, 'post_link_attributes' ), 1 );
		add_filter( 'previous_post_link', array( $this, 'post_link_attributes' ), 1 );
		add_action( 'bdp_social_share_text', array( $this, 'display_social_share_text' ), 5, 1 );
		add_action( 'wp_ajax_nopriv_get_bdp_posts', array( $this, 'blogdesign_get_bdp_posts' ) );
		add_action( 'wp_ajax_get_bdp_posts', array( $this, 'blogdesign_get_bdp_posts' ) );
		add_action( 'wp_ajax_nopriv_get_display_taxonomy_filter_list', array( $this, 'bdp_get_display_taxonomy_filter_list' ) );
		add_action( 'wp_ajax_get_display_taxonomy_filter_list', array( $this, 'bdp_get_display_taxonomy_filter_list' ) );
		add_action( 'wp_ajax_nopriv_bdp_get_tabbed_display_taxonomy_filter_list', array( $this, 'bdp_get_tabbed_display_taxonomy_filter_list' ) );
		add_action( 'wp_ajax_bdp_get_tabbed_display_taxonomy_filter_list', array( $this, 'bdp_get_tabbed_display_taxonomy_filter_list' ) );
		add_action( 'wp_ajax_nopriv_bdp_get_tabbed_display_taxonomy_list', array( $this, 'bdp_get_tabbed_display_taxonomy_list' ) );
		add_action( 'wp_ajax_bdp_get_tabbed_display_taxonomy_list', array( $this, 'bdp_get_tabbed_display_taxonomy_list' ) );
		add_action( 'wp_ajax_nopriv_bdp_get_posts_archive', array( $this, 'bdp_get_posts_archive' ) );
		add_action( 'wp_ajax_bdp_get_posts_archive', array( $this, 'bdp_get_posts_archive' ) );
		add_action( 'wp_ajax_nopriv_bdp_get_all_post_lists', array( $this, 'bdp_get_all_post_lists' ), 12 );
		add_action( 'wp_ajax_bdp_get_all_post_lists', array( $this, 'bdp_get_all_post_lists' ), 12 );
		add_action( 'wp_ajax_nopriv_bdp_get_all_users_lists', array( $this, 'bdp_get_all_users_lists' ), 12 );
		add_action( 'wp_ajax_bdp_get_all_users_lists', array( $this, 'bdp_get_all_users_lists' ), 12 );
		add_action( 'wp_ajax_nopriv_bdp_get_all_timeline_post_lists', array( $this, 'bdp_get_all_timeline_post_lists' ), 12 );
		add_action( 'wp_ajax_bdp_get_all_timeline_post_lists', array( $this, 'bdp_get_all_timeline_post_lists' ), 12 );
		add_action( 'wp_ajax_nopriv_bdp_archive_get_all_users_lists', array( $this, 'bdp_archive_get_all_users_lists' ), 12 );
		add_action( 'wp_ajax_bdp_archive_get_all_users_lists', array( $this, 'bdp_archive_get_all_users_lists' ), 12 );
		add_action( 'bdp_single_post_content_data', array( $this, 'single_post_content' ), 10, 2 );
		add_filter( 'bdp_hide_taxonomies', array( $this, 'bdp_hide_taxonomies' ), 10 );
	}
	/**
	 * Add pagination for shortcode layout
	 *
	 * @since 2.6
	 * @param array $bdp_settings settings.
	 * @return pagination
	 */
	public static function shortcode_standard_paging_nav( $bdp_settings ) {
		// Don't print empty markup if there's only one page.
		$max_num_pages = isset( $_POST['max_num_pages'] ) ? sanitize_text_field( wp_unslash( $_POST['max_num_pages'] ) ) : $GLOBALS['wp_query']->max_num_pages;
		if ( ! empty( $_POST['cat_tag_count'] ) ) {
			$found_posts = isset( $_POST['cat_tag_count'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['cat_tag_count'] ) ) ) : '';
		} else {
			$found_posts = isset( $_POST['found_posts'] ) ? sanitize_text_field( wp_unslash( $_POST['found_posts'] ) ) : $GLOBALS['wp_query']->found_posts;
		}
		if ( $max_num_pages < 2 ) {
			return;
		}
		if ( 'cool_horizontal' === $bdp_settings['template_name'] || 'overlay_horizontal' === $bdp_settings['template_name'] ) {
			$posts_per_page = -1;
		} else {
			$posts_per_page = $bdp_settings['posts_per_page'];
		}
		$bdp_post_offset = ( isset( $bdp_settings['bdp_post_offset'] ) && ! empty( $bdp_settings['bdp_post_offset'] ) ) ? $bdp_settings['bdp_post_offset'] : '0';
		$page_url        = isset( $bdp_settings['url'] ) ? $bdp_settings['url'] : get_pagenum_link();
		$offset_start    = $bdp_post_offset;
		$total_rows      = max( 0, $found_posts - $offset_start );
		$total_pages     = ceil( $total_rows / $posts_per_page );
		$navigation      = '';
		$paged           = isset( $_POST['paged'] ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : intval( get_query_var( 'paged' ) );
		if ( 0 == $paged ) {
			$paged = 1;
		}
		$pagenum_link = html_entity_decode( $page_url );
		$query_args   = array();
		$url          = isset( $url ) ? $url : '#';
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

		$filter_array = array( 'boxy', 'boxy-clean', 'cool_horizontal', 'overlay_horizontal', 'news', 'invert-grid', 'brit_co', 'media-grid' );
		if ( in_array( $bdp_settings['template_name'], $filter_array ) ) {
			if ( isset( $bdp_settings['filter_category'] ) || isset( $bdp_settings['filter_tags'] ) || isset( $bdp_settings['filter_date'] ) ) {
				$cat_ids_url_string = isset( $_REQUEST['cate_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cate_id'] ) ) : $bdp_settings['template_category'];
				if ( ! empty( $_REQUEST['cate_id'] ) ) {
					$cat_ids_url_array = explode( ',', $cat_ids_url_string );
					$cat_ids           = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : $cat_ids_url_array;
				} else {
					$cat_ids = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : $cat_ids_url_string;
				}
				if ( ! empty( $cat_ids ) ) {
					$cat_ids = implode( ',', $cat_ids );
				}

				$tag_ids_url_string = isset( $_REQUEST['tags_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['tags_id'] ) ) : $bdp_settings['template_tags'];
				if ( ! empty( $_REQUEST['tags_id'] ) ) {
					$tag_ids_url_array = explode( ',', $tag_ids_url_string );
					$tag_ids           = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : $tag_ids_url_array;
				} else {
					$tag_ids = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : $tag_ids_url_string;
				}
				if ( ! empty( $tag_ids ) ) {
					$tag_ids = implode( ',', $tag_ids );
				}

				$date_url_string = isset( $_REQUEST['filter_date'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['filter_date'] ) ) : '';
				if ( ! empty( $date_url_string ) ) {
					$dates = $date_url_string;
					$dates = implode( ',', $dates );
				}

				if ( ! empty( $cat_ids ) && ! empty( $tag_ids ) && ! empty( $dates ) ) {
					$url .= '?cate_id=' . $cat_ids . '&tags_id=' . $tag_ids . '&dates=' . $dates;
				}
				if ( ! empty( $cat_ids ) && ! empty( $tag_ids ) ) {
					$url .= '?cate_id=' . $cat_ids . '&tags_id=' . $tag_ids;
				}
				if ( ! empty( $tag_ids ) && ! empty( $dates ) ) {
					$url .= '?tags_id=' . $tag_ids . '&dates=' . $dates;
				}
				if ( ! empty( $cat_ids ) && ! empty( $dates ) ) {
					$url .= '?cate_id=' . $cat_ids . '&dates=' . $dates;
				}
				if ( ! empty( $cat_ids ) && empty( $tag_ids ) && empty( $dates ) ) {
					$url .= '?cate_id=' . $cat_ids;
				}
				if ( ! empty( $tag_ids ) && empty( $cat_ids ) && empty( $dates ) ) {
					$url .= '?tags_id=' . $tag_ids;
				}
				if ( ! empty( $dates ) && empty( $cat_ids ) && empty( $tag_ids ) ) {
					$url .= '?dates=' . $dates;
				}
			}
		}
		// Set up paginated links.
		$links = paginate_links(
			array(
				'base'      => $pagenum_link . $url,
				'format'    => $format,
				'total'     => $total_pages,
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
	 * Add pagination
	 *
	 * @param string $bdp_settings Settings.
	 */
	public static function standard_paging_nav( $bdp_settings ) {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
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
				'total'     => $GLOBALS['wp_query']->max_num_pages,
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
	 * Postbox Classes
	 *
	 * @param int    $id id.
	 * @param string $page page.
	 * @return array $classes
	 */
	public static function postbox_classes( $id, $page ) {
		$closed = get_user_option( 'bdpclosedbdpboxes_' . $page );
		if ( $closed ) {
			if ( ! is_array( $closed ) ) {
				$classes = array( '' );
			} else {
				$classes = in_array( $id, $closed ) ? array( 'closed' ) : array( '' );
			}
		} else {
			$classes = array( '' );
		}

		return implode( ' ', $classes );
	}
	/**
	 * Add class in a tag
	 *
	 * @param string $output output.
	 * @return string anchor tag of with class
	 */
	public function post_link_attributes( $output ) {
		$code = 'class="styled-button"';
		return str_replace( '<a href=', '<a ' . $code . ' href=', $output );
	}
	/**
	 * Admin actions.
	 *
	 * @return void
	 */
	public static function admin_singlefile_actions() {
		// Handle any actions.
		if ( ( ! empty( $_GET['move_template'] ) || ! empty( $_GET['delete_template'] ) ) && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			if ( empty( $_GET['_bdp_single_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_bdp_single_nonce'] ) ), 'bdp_single_template_nonce' ) ) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'blog-designer-pro' ) );
			}
			if ( ! current_user_can( 'edit_themes' ) ) {
				wp_die( esc_html__( 'Cheatin', 'blog-designer-pro' ) . '&#8217; ' . esc_html__( 'huh?', 'blog-designer-pro' ) );
			}
			if ( ! empty( $_GET['move_template'] ) ) {
				Bdp_Template::singlefile_move_template_action( sanitize_text_field( wp_unslash( $_GET['move_template'] ) ) );
			}
			if ( ! empty( $_GET['delete_template'] ) ) {
				Bdp_Template::singlefile_delete_template_action( sanitize_text_field( wp_unslash( $_GET['delete_template'] ) ) );
			}
		}
	}
	/**
	 * Display social share text in a single post.
	 *
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	public function display_social_share_text( $bdp_settings ) {
		if ( ! empty( $bdp_settings['txtSocialIcon'] ) || ! empty( $bdp_settings['txtSocialText'] ) ) {
			?>
			<div class="share-this">
				<?php if ( ! empty( $bdp_settings['txtSocialIcon'] ) ) { ?>
					<i class="<?php echo esc_attr( $bdp_settings['txtSocialIcon'] ); ?>"></i>
					<?php
				}
				if ( ! empty( $bdp_settings['txtSocialText'] ) ) {
					?>
					<span> <?php echo esc_html( $bdp_settings['txtSocialText'] ); ?></span>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
	/**
	 * Change in exceprt content
	 *
	 * @param string  $bdp_post_id post id.
	 * @param string  $bdp_settings settings.
	 * @param boolean $rss_use_excerpt rss use.
	 * @param string  $excerpt_length excerpt.
	 * @return content or excerpt
	 */
	public static function get_content( $bdp_post_id, $bdp_settings, $rss_use_excerpt = 0, $excerpt_length = 20 ) {
		add_filter( 'the_content_more_link', 'bdp_remove_more_link', 999 );
		if ( '' != $excerpt_length && $excerpt_length < 1 ) {
			return;
		}
		remove_all_filters( 'excerpt_more' );
		if ( 0 == $rss_use_excerpt ) :
			$content = apply_filters( 'the_content', get_the_content( $bdp_post_id ) );
			$content = apply_filters( 'bdp_content_change', $content, $bdp_post_id );
			if ( isset( $bdp_settings['firstletter_big'] ) && 1 == $bdp_settings['firstletter_big'] ) {
				return Bdp_Utility::add_first_letter_structure( $content );
			} else {
				return $content;
			}
		else :
			$template_post_content_from = 'from_content';
			if ( isset( $bdp_settings['template_post_content_from'] ) ) {
				$template_post_content_from = $bdp_settings['template_post_content_from'];
			}
			if ( 'from_excerpt' === $template_post_content_from ) {
				if ( '' != get_the_excerpt( $bdp_post_id ) ) {
					$excerpt = get_the_excerpt( $bdp_post_id );
					$excerpt = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $excerpt );
					$excerpt = strip_shortcodes( $excerpt );
				} else {
					$excerpt = get_the_content( $bdp_post_id );
					$excerpt = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $excerpt );
					// commneted for fusion builder support.
					$excerpt = apply_filters( 'bdp_content_filter', $excerpt );
					$excerpt = apply_filters( 'the_content', $excerpt );
					$excerpt = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $excerpt );
				}
			} else {
				$excerpt = get_the_content( $bdp_post_id );
				$excerpt = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $excerpt );
				// commneted for fusion builder support.
				// $excerpt = strip_shortcodes($excerpt);.
				$excerpt = apply_filters( 'bdp_content_filter', $excerpt );
				$excerpt = preg_replace( '#\[[^\]]+\]#', '', $excerpt );
				$excerpt = apply_filters( 'the_content', $excerpt );
				$excerpt = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $excerpt );
			}
			if ( isset( $bdp_settings['display_html_tags'] ) && 1 == $bdp_settings['display_html_tags'] ) {
				$text = $excerpt;
				if ( 0 === strpos( _x( 'Words', 'Word count type. Do not translate!', 'blog-designer-pro' ), 'characters' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
					$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
					preg_match_all( '/./u', $text, $words_array );
					$words_array = array_slice( $words_array[0], 0, $excerpt_length + 1 );
					$sep         = '';
				} else {
					$words_array = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
					$sep         = ' ';
				}
				if ( count( $words_array ) > $excerpt_length ) {
					array_pop( $words_array );
					$text             = implode( $sep, $words_array );
					$bdp_excerpt_data = $text;
				} else {
					$bdp_excerpt_data = implode( $sep, $words_array );
				}
				$first_letter = $bdp_excerpt_data;
				if ( isset( $bdp_settings['firstletter_big'] ) && 1 == $bdp_settings['firstletter_big'] ) {
					if ( preg_match( '#(>|]|^)(([A-Z]|[a-z]|[0-9]|[\p{L}])(.*\R)*(\R)*.*)#m', $first_letter, $matches ) ) {
						$top_content             = str_replace( $matches[2], '', $first_letter );
						$content_change          = ltrim( $matches[2] );
						$bp_content_first_letter = mb_substr( $content_change, 0, 1 );
						if ( ' ' === mb_substr( $content_change, 1, 1 ) ) {
							$bp_remaining_letter = ' ' . mb_substr( $content_change, 2 );
						} else {
							$bp_remaining_letter = mb_substr( $content_change, 1 );
						}
						$spanned_first_letter = '<span class="bp-first-letter">' . $bp_content_first_letter . '</span>';
						$bottom_content       = $spanned_first_letter . $bp_remaining_letter;
						$bdp_excerpt_data     = $top_content . $bottom_content;
					}
				}
				$bdp_excerpt_data = self::advance_contens( $bdp_settings, $bdp_excerpt_data );
				$bdp_excerpt_data = self::close_tags( $bdp_excerpt_data );
				if ( 'from_excerpt' === $template_post_content_from && '' != get_the_excerpt( $bdp_post_id ) ) {
					$bdp_excerpt_data = apply_filters( 'the_excerpt', $bdp_excerpt_data );
				} else {
					$bdp_excerpt_data = apply_filters( 'the_content', $bdp_excerpt_data );
				}
				return html_entity_decode( $bdp_excerpt_data );
			} else {
				$text = str_replace( ']]>', ']]&gt;', $excerpt );
				$text = wp_strip_all_tags( $text );
				if ( 0 === strpos( _x( 'words', 'Word count type. Do not translate!', 'blog-designer-pro' ), 'characters' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
					$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
					preg_match_all( '/./u', $text, $words_array );
					$words_array = array_slice( $words_array[0], 0, $excerpt_length + 1 );
					$sep         = '';
				} else {
					if ( ! $excerpt_length ) {
						$excerpt_length = 22;
					}
					$words_array = preg_split( "/[\n\r\t ]+/", $text, @$excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
					$sep         = ' ';
				}
				if ( count( $words_array ) > $excerpt_length ) {
					array_pop( $words_array );
					$text             = implode( $sep, $words_array );
					$bdp_excerpt_data = $text;
				} else {
					$bdp_excerpt_data = implode( $sep, $words_array );
				}
				$bdp_excerpt_data = self::advance_contens( $bdp_settings, $bdp_excerpt_data );

				$bdp_excerpt_data = wp_trim_words( $bdp_excerpt_data, $excerpt_length, '' );
				$bdp_excerpt_data = apply_filters( 'bdp_excerpt_change', $bdp_excerpt_data, $bdp_post_id );
				return $bdp_excerpt_data;
			}
		endif;
	}
	/**
	 * Get the advance contents
	 *
	 * @since 2.0
	 * @param array  $bdp_settings settings.
	 * @param string $bdp_excerpt_data excerpt.
	 * @return $bdp_excerpt_data
	 */
	public static function advance_contens( $bdp_settings, $bdp_excerpt_data = '' ) {
		if ( '' == $bdp_excerpt_data ) {
			return $bdp_excerpt_data;
		}
		if ( isset( $bdp_settings['advance_contents'] ) && 1 == $bdp_settings['advance_contents'] ) {
			$stopage_from = isset( $bdp_settings['contents_stopage_from'] ) ? $bdp_settings['contents_stopage_from'] : 'paragraph';
			if ( isset( $bdp_settings['display_html_tags'] ) && 1 == $bdp_settings['display_html_tags'] ) {
				$stopage_from = 'paragraph';
			}
			if ( 'paragraph' === $stopage_from ) {
				$stopage_characters = array( '</p>', '</div>', '<br' );
				foreach ( $stopage_characters as $stopage_character ) {
					$strpose[] = strrpos( $bdp_excerpt_data, $stopage_character );
				}
				if ( '' != substr( $bdp_excerpt_data, 0, max( $strpose ) ) ) {
					$bdp_excerpt_data = substr( $bdp_excerpt_data, 0, max( $strpose ) );
				}
			} elseif ( 'character' === $stopage_from ) {
				$stopage_characters = isset( $bdp_settings['contents_stopage_character'] ) ? $bdp_settings['contents_stopage_character'] : array( '.' );
				foreach ( $stopage_characters as $stopage_character ) {
					$strpose[] = strrpos( $bdp_excerpt_data, $stopage_character );
				}
				if ( '' != substr( $bdp_excerpt_data, 0, max( $strpose ) + 1 ) && isset( $strpose[0] ) && ! empty( $strpose[0] ) ) {
					$bdp_excerpt_data = substr( $bdp_excerpt_data, 0, max( $strpose ) + 1 );
				}
			}
		}
		return $bdp_excerpt_data;
	}
	/**
	 * Close HTML tags
	 *
	 * @since 2.0
	 * @param string $html html.
	 * @return $html
	 */
	public static function close_tags( $html = '' ) {
		if ( '' == $html ) { //phpcs:ignore
			return;
		}

		$attributeeopenlist  = array( '="', "='" );
		$attributeecloselist = array( '">', "'>" );
		if ( ( substr( $html, -2 ) != '="' && substr( $html, -1 ) == '"' ) || substr( $html, -2 ) != '="' && substr( $html, -1 ) == "'" ) {
			$html .= '>';
		}

		foreach ( $attributeeopenlist as $key => $attribute ) {
			$openpos = strrpos( $html, $attribute );
			if ( false !== $openpos ) {
				$closepos = strrpos( $html, $attributeecloselist[ $key ] );
				if ( false == $closepos || $closepos < $openpos ) {
					$html .= $attributeecloselist[ $key ];
					break;
				}
			}
		}

		preg_match_all( '#<([a-z]+)( .*)?(?!/)>#iU', $html, $result );
		$openedtags = $result[1];

		preg_match_all( '#</([a-z]+)>#iU', $html, $result );
		$closedtags = $result[1];
		$len_opened = count( $openedtags );
		$len_closed = count( $closedtags );

		if ( $len_closed == $len_opened ) { //phpcs:ignore
			return $html;
		}

		$opentasgposition = array();
		$opentasgchecked  = array();
		foreach ( $openedtags as $tagindex => $tag ) {
			$startpostion = 0;
			$openpos      = strpos( $html, $tag, $startpostion );
			if ( ! empty( $opentasgchecked ) && key_exists( $tag, $opentasgchecked ) ) {
				$openpos = strpos( $html, '<' . $tag, $opentasgchecked[ $tag ] + 1 );
			}
			$opentasgchecked[ $tag ]       = $openpos;
			$opentasgposition[ $tagindex ] = $openpos;
		}
		$opentagwithpoition = array();
		foreach ( $openedtags as $tagindex => $tag ) {
			$opentagwithpoition[ $opentasgposition[ $tagindex ] ] = $tag;
		}
		ksort( $opentagwithpoition );
		$closetasgposition = array();
		$closetasgchecked  = array();
		foreach ( $closedtags as $tagindex => $tag ) {
			$startpostion = 0;
			$closepos     = strpos( $html, $tag, $startpostion );
			if ( ! empty( $closetasgchecked ) && key_exists( $tag, $closetasgchecked ) ) {
				$closepos = strpos( $html, '</' . $tag, $closetasgchecked[ $tag ] + 1 );
			}
			$closetasgchecked[ $tag ]       = $closepos;
			$closetasgposition[ $tagindex ] = $closepos;
		}
		$closetagwithpoition = array();
		foreach ( $closedtags as $tagindex => $tag ) {
			$closetagwithpoition[ $closetasgposition[ $tagindex ] ] = $tag;
		}
		krsort( $closetagwithpoition );
		foreach ( $closetagwithpoition as $closetag ) {
			unset( $opentagwithpoition[ array_search( $closetag , $opentagwithpoition ) ] ); //phpcs:ignore
		}
		krsort( $opentagwithpoition );
		foreach ( $opentagwithpoition as $opentag ) {
			$html .= '</' . $opentag . '>';
		}

		return $html;
	}
	/**
	 * Get default image
	 *
	 * @param int    $bdp_post_id post id.
	 * @param string $template_name template name.
	 * @return html image
	 */
	public static function get_sample_image( $bdp_post_id, $template_name = '' ) {
		if ( 'boxy-clean' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_image_boxy_clean.png" />';
		} elseif ( 'deport' === $template_name || 'masonry_timeline' === $template_name || 'my_diary' === $template_name || 'fairy' === $template_name || 'integer' === $template_name || 'clicky' === $template_name || 'roctangle' === $template_name || 'glamour' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" src="' . BLOGDESIGNERPRO_URL . '/public/images/No_available_deport.gif" />';
		} elseif ( 'navia' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/No_available_deport.gif" />';
		} elseif ( 'invert-grid' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_available_image_640_320.png" />';
		} elseif ( 'brit_co' === $template_name || 'minimal' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_available_image_580_255.png" />';
		} elseif ( 'media-grid' === $template_name ) {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_available_image_640_320.png" />';
		} elseif ( 'leest' === $template_name ) {
			$sample_img = '<img width="380" height="500" alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_available_image_640_320.png" />';
		} elseif ( 'brit_co' === $template_name ) {
			$sample_img = '<img width="500" height="500" alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/No_available_image.png" />';
		} elseif ( 'elina' === $template_name || 'chapter' === $template_name || 'brite' === $template_name || 'advice' === $template_name ) {
			$sample_img = '<img width="900" height="400" alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" class="attachment-full size-full wp-post-image" src="' . BLOGDESIGNERPRO_URL . '/public/images/no_available_image_900.gif" />';
		} else {
			$sample_img = '<img alt="' . esc_attr__( 'Feature image not available', 'blog-designer-pro' ) . '" src="' . BLOGDESIGNERPRO_URL . '/public/images/No_available_image.png" />';
		}
		$sample_img = apply_filters( 'bdp_sample_img', $sample_img, $template_name, $bdp_post_id );
		return $sample_img;
	}
	/**
	 * Get default image for related posts
	 *
	 * @param int $bdp_post_id post id.
	 * @return html image
	 */
	public static function get_related_post_sample_image( $bdp_post_id ) {
		$sample_img = '<img alt="' . esc_attr__( 'No image available', 'blog-designer-pro' ) . '" title="' . esc_attr__( 'No image available', 'blog-designer-pro' ) . '" src="' . BLOGDESIGNERPRO_URL . '/public/images/related_post_no_available_image.png" />';
		return $sample_img;
	}
	/**
	 * Get parameter array for posts query
	 *
	 * @param array $bdp_settings setttings.
	 * @return array parameters for posts query
	 */
	public static function get_wp_query( $bdp_settings ) {
		global $wp_bdp_setting;
		$wp_bdp_setting = $bdp_settings;
		$taxonomy       = '';
		$terms          = '';
		$tags           = '';
		$cats           = '';
		$author         = '';
		$orderby        = 'date';
		$order          = 'DESC';
		$post_type      = '';
		if ( isset( $bdp_settings['custom_post_type'] ) ) {
			$post_type = $bdp_settings['custom_post_type'];
		}

		if ( isset( $bdp_settings['display_filter'] ) ) {
			$display_filter = $bdp_settings['display_filter'];
		} else {
			$display_filter = 0;
		}
		if ( isset( $bdp_settings['bdp_filter_post'] ) ) {
			$bdp_filter_post = $bdp_settings['bdp_filter_post'];
		} else {
			$bdp_filter_post = '';
		}
		if ( empty( $bdp_post_categories ) ) {
			$bdp_post_categories = '';
		}
		if ( isset( $bdp_settings['template_category'] ) ) {
			$cat = $bdp_settings['template_category'];
		}
		if ( isset( $_REQUEST['cate_id'] ) ) {
			$bdp_settings['template_category'] = sanitize_text_field( wp_unslash( $_REQUEST['cate_id'] ) );
			$cat                               = $bdp_settings['template_category'];
			$cat                               = explode( ',', $cat );
		}
		if ( isset( $_REQUEST['tags_id'] ) ) {
			$bdp_settings['template_tags'] = sanitize_text_field( wp_unslash( $_REQUEST['tags_id'] ) );
			$tag                           = $bdp_settings['template_tags'];
			$tag                           = explode( ',', $tag );
		}
		if ( isset( $bdp_settings['template_tags'] ) ) {
			$tag = $bdp_settings['template_tags'];
		}
		if ( isset( $bdp_settings['template_authors'] ) ) {
			$author = $bdp_settings['template_authors'];
		}
		if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
			$orderby = $bdp_settings['bdp_blog_order_by'];
		}
		if ( isset( $bdp_settings['bdp_blog_order'] ) ) {
			$order = $bdp_settings['bdp_blog_order'];
		}
		if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
			$orderby = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
		}
		$taxo = get_object_taxonomies( $post_type );
		if ( empty( $cat ) ) {
			$cat = '';
		}
		if ( empty( $tag ) ) {
			$tag = '';
		}
		if ( isset( $bdp_settings['exclude_category_list'] ) && 1 == $bdp_settings['exclude_category_list'] ) {
			$exlude_category = 'NOT IN';
		} else {
			$exlude_category = 'IN';
		}
		if ( isset( $bdp_settings['exclude_tag_list'] ) && 1 == $bdp_settings['exclude_tag_list'] ) {
			$exlude_tag = 'NOT IN';
		} else {
			$exlude_tag = 'IN';
		}
		if ( isset( $bdp_settings['exclude_post_list'] ) && 1 == $bdp_settings['exclude_post_list'] ) {
			$exlude_post = 'post__not_in';
		} else {
			$exlude_post = 'post__in';
		}
		if ( isset( $bdp_settings['exclude_author_list'] ) && 1 == $bdp_settings['exclude_post_list'] ) {
			$exlude_author = 'author__not_in';
		} else {
			$exlude_author = 'author__in';
		}
		$advance_filter = ( isset( $bdp_settings['advance_filter'] ) ) ? $bdp_settings['advance_filter'] : 0;
		$relation       = 'OR';
		if ( 1 == $advance_filter ) {
			if ( isset( $bdp_settings['tax_filter_with'] ) && 1 == $bdp_settings['tax_filter_with'] ) {
				$relation = 'AND';
			}
			if ( isset( $bdp_settings['author_filter_with'] ) && 1 != $bdp_settings['author_filter_with'] ) {
				add_filter( 'posts_where', array( 'Bdp_Author', 'author_filter_func' ) );
			}
		}
		$tax_query = array();
		if ( '' != $cat && '' != $tag ) {
			$tax_query = array(
				'relation' => $relation,
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => $exlude_category,
				),
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => $exlude_tag,
				),
			);
		} elseif ( '' != $cat ) {
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => $exlude_category,
				),
			);
		} elseif ( '' != $tag ) {
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => $exlude_tag,
				),
			);
		}
		if ( 'cool_horizontal' === $bdp_settings['template_name'] || 'overlay_horizontal' === $bdp_settings['template_name'] ) {
			$posts_per_page = -1;
		} else {
			$posts_per_page = $bdp_settings['posts_per_page'];
		}
		if ( isset( $bdp_settings['paged'] ) ) {
			$paged = $bdp_settings['paged'];
		} else {
			$paged = Bdp_Utility::paged();
		}
		$post_status     = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );
		$bdp_post_offset = ( isset( $bdp_settings['bdp_post_offset'] ) && ! empty( $bdp_settings['bdp_post_offset'] ) ) ? $bdp_settings['bdp_post_offset'] : '0';
		if ( 'post' === $post_type ) {
			$current_page = (int) $paged;
			$current_page = max( 1, $current_page );
			$offset_start = $bdp_post_offset;
			$offset       = (int) $bdp_post_offset + ( ( (int) $current_page - 1 ) * (int) $posts_per_page );
			if ( 'meta_value_num' === $orderby ) {
				$orderby_str = $orderby . ' date';
			} else {
				$orderby_str = $orderby;
			}
			if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
				$orderby_str = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
				$order       = 'ASC';
			}
			if ( 'no_pagination' == $bdp_settings['pagination_type'] && 1 == $display_filter ) {
				$posts_per_page = -1;
			} else {
				$posts_per_page = $bdp_settings['posts_per_page'];
			}
			if ( '' != $bdp_filter_post && 1 == $display_filter ) {

				$posts = array(
					$exlude_author   => $author,
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_post     => $bdp_filter_post,
					'posts_per_page' => -1,
					'offset'         => $offset,
				);
			} elseif ( '' == $bdp_filter_post && 1 == $display_filter ) {
					$posts = array(
						$exlude_author   => $author,
						'post_status'    => $post_status,
						'post_type'      => $post_type,
						'paged'          => $paged,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'tax_query'      => $tax_query,
						'posts_per_page' => $posts_per_page,
						'offset'         => $offset,
					);
			} elseif ( '' != $bdp_filter_post && 0 == $display_filter ) {
				$posts = array(
					$exlude_author   => $author,
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_post     => $bdp_filter_post,
					'posts_per_page' => $posts_per_page,
					'offset'         => $offset,
				);
			} elseif ( '' == $bdp_filter_post && 0 == $display_filter ) {
				$posts = array(
					$exlude_author   => $author,
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					'tax_query'      => $tax_query,
					'posts_per_page' => $posts_per_page,
					'offset'         => $offset,
				);
			} else {
				$posts = array(
					$exlude_author   => $author,
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					'tax_query'      => $tax_query,
					'offset'         => $offset,
				);
			}
			if ( 'meta_value_num' === $orderby ) {
				$posts['meta_query'] = array(
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
			if ( isset( $bdp_settings['paged'] ) ) {
				$posts['post_status'] = $post_status;
			}
			if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'timeline' === $bdp_settings['template_name'] || 'story' === $bdp_settings['template_name'] ) ) {
				$posts['ignore_sticky_posts'] = 1;
			}
			if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
				$posts['ignore_sticky_posts'] = 1;
			}
			if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
				$posts['ignore_sticky_posts'] = 0;
			} else {
				$posts['ignore_sticky_posts'] = 1;
			}

			/**
			 * Time Period Coding
			 */
			if ( isset( $_REQUEST['dates'] ) ) {
				$dates = explode( ',', sanitize_text_field( wp_unslash( $_REQUEST['dates'] ) ) );
				if ( isset( $dates ) && ! empty( $dates ) ) {
					$date_query             = array();
					$date_query['relation'] = 'OR';
					foreach ( $dates as $fdate ) {
						$date         = explode( '-', $fdate );
						$date_query[] = array(
							'year'  => $date[0],
							'month' => $date[1],
						);
					}
					$posts['date_query'] = $date_query;
				}
			}
			if ( isset( $bdp_settings['blog_time_period'] ) ) {
				$blog_time_period = $bdp_settings['blog_time_period'];
				if ( 'today' === $blog_time_period ) {
					$today               = getdate();
					$posts['date_query'] = array(
						array(
							'year'  => $today['year'],
							'month' => $today['mon'],
							'day'   => $today['mday'],
						),
					);
				}
				if ( 'tomorrow' === $blog_time_period ) {
					$twodayslater         = getdate( current_time( 'timestamp' ) + 1 * DAY_IN_SECONDS );
					$posts['date_query']  = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
							'day'   => $twodayslater['mday'],
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'this_week' === $blog_time_period ) {
					$week                = gmdate( 'W' );
					$year                = gmdate( 'Y' );
					$posts['date_query'] = array(
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
					$posts['date_query'] = array(
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
					$posts['date_query']  = array(
						array(
							'year' => $year,
							'week' => $lastweek,
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'this_month' === $blog_time_period ) {
					$today               = getdate();
					$posts['date_query'] = array(
						array(
							'year'  => $today['year'],
							'month' => $today['mon'],
						),
					);
				}
				if ( 'last_month' === $blog_time_period ) {
					$twodayslater        = getdate( current_time( 'timestamp' ) - 1 * MONTH_IN_SECONDS );
					$posts['date_query'] = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
						),
					);
				}
				if ( 'next_month' === $blog_time_period ) {
					$twodayslater         = getdate( current_time( 'timestamp' ) + 1 * MONTH_IN_SECONDS );
					$posts['date_query']  = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'last_n_days' === $blog_time_period ) {
					if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
						$last_n_days         = $bdp_settings['bdp_time_period_day'] . ' days ago';
						$posts['date_query'] = array(
							array(
								'after'     => $last_n_days,
								'inclusive' => true,
							),
						);
					}
				}
				if ( 'next_n_days' === $blog_time_period ) {
					if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
						$next_n_days          = '+' . $bdp_settings['bdp_time_period_day'] . ' days';
						$posts['date_query']  = array(
							array(
								'before'    => gmdate( 'Y-m-d', strtotime( $next_n_days ) ),
								'inclusive' => true,
							),
						);
						$posts['post_status'] = array( 'future' );
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
					$posts['date_query'] = array(
						array(
							'after'     => $after,
							'before'    => $before,
							'inclusive' => true,
						),
					);
				}
			}
		} else {
			$tax_query = array( 'relation' => 'OR' );
			if ( isset( $bdp_settings['relation'] ) && ! empty( $bdp_settings['relation'] ) ) {
				$tax_query = $bdp_settings['relation'];
			}
			foreach ( $taxo as $taxonom ) {
				$custom_taxonom = '';
				if ( isset( $bdp_settings[ $taxonom . '_terms' ] ) ) {
					if ( ! empty( $bdp_settings[ $taxonom . '_terms' ] ) ) {
						$custom_taxonom = $bdp_settings[ $taxonom . '_terms' ];
					}
					if ( isset( $bdp_settings[ 'exclude_' . $taxonom . '_list' ] ) ) {
						$operator_value = 'NOT IN';
					} else {
						$operator_value = 'IN';
					}
					$tax_query[] = array(
						'taxonomy' => $taxonom,
						'field'    => 'name',
						'terms'    => $custom_taxonom,
						'operator' => $operator_value,
					);
				}
			}
			if ( 'meta_value_num' === $orderby ) {
				$orderby_str = $orderby . ' date';
			} else {
				$orderby_str = $orderby;
			}
			if ( isset( $_GET['sortby'] ) && '' != $_GET['sortby'] ) {
				$orderby_str = sanitize_text_field( wp_unslash( $_GET['sortby'] ) );
				$order       = 'ASC';
			}
			$current_page = $paged;
			$current_page = max( 1, $current_page );
			$offset_start = $bdp_post_offset;
			$offset       = $bdp_post_offset + ( ( (int) $current_page - 1 ) * $posts_per_page );
			if ( '' == $bdp_filter_post && 0 == $display_filter ) {
				$posts = array(
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_author   => $author,
					'offset'         => $offset,
					'tax_query'      => $tax_query,

				);
			} elseif ( '' != $bdp_filter_post && 0 == $display_filter ) {
				$posts = array(
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_author   => $author,
					$exlude_post     => $bdp_filter_post,
					'offset'         => $offset,
					'tax_query'      => $tax_query,
				);
			} elseif ( '' == $bdp_filter_post && 1 == $display_filter ) {
				$posts = array(
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_author   => $author,
					'tax_query'      => $tax_query,
				);
			} elseif ( '' != $bdp_filter_post && 1 == $display_filter ) {
				$posts = array(
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_author   => $author,
					$exlude_post     => $bdp_filter_post,
					'tax_query'      => $tax_query,
				);
			} else {
				$posts = array(
					'post_status'    => $post_status,
					'post_type'      => $post_type,
					'tax_query'      => $tax_query,
					'posts_per_page' => $posts_per_page,
					'paged'          => $paged,
					'orderby'        => $orderby_str,
					'order'          => $order,
					$exlude_author   => $author,
					'offset'         => $offset,
				);
			}
			if ( 'meta_value_num' === $orderby ) {
				$posts['meta_query'] = array(
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
			if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'timeline' === $bdp_settings['template_name'] || 'story' === $bdp_settings['template_name'] ) ) {
				$posts['ignore_sticky_posts'] = 1;
			}
			if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
				$posts['ignore_sticky_posts'] = 1;
			}
			if ( isset( $bdp_settings['display_sticky'] ) && 1 == $bdp_settings['display_sticky'] ) {
				$posts['ignore_sticky_posts'] = 0;
			} else {
				$posts['ignore_sticky_posts'] = 1;
			}
			/**
			 * Time Period Coding
			 */
			if ( isset( $bdp_settings['blog_time_period'] ) ) {
				$blog_time_period = $bdp_settings['blog_time_period'];
				if ( 'today' === $blog_time_period ) {
					$today               = getdate();
					$posts['date_query'] = array(
						array(
							'year'  => $today['year'],
							'month' => $today['mon'],
							'day'   => $today['mday'],
						),
					);
				}
				if ( 'tomorrow' === $blog_time_period ) {
					$twodayslater         = getdate( current_time( 'timestamp' ) + 1 * DAY_IN_SECONDS );
					$posts['date_query']  = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
							'day'   => $twodayslater['mday'],
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'this_week' === $blog_time_period ) {
					$week                = gmdate( 'W' );
					$year                = gmdate( 'Y' );
					$posts['date_query'] = array(
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
					$posts['date_query'] = array(
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
					$posts['date_query']  = array(
						array(
							'year' => $year,
							'week' => $lastweek,
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'this_month' === $blog_time_period ) {
					$today               = getdate();
					$posts['date_query'] = array(
						array(
							'year'  => $today['year'],
							'month' => $today['mon'],
						),
					);
				}
				if ( 'last_month' === $blog_time_period ) {
					$twodayslater        = getdate( current_time( 'timestamp' ) - 1 * MONTH_IN_SECONDS );
					$posts['date_query'] = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
						),
					);
				}
				if ( 'next_month' === $blog_time_period ) {
					$twodayslater         = getdate( current_time( 'timestamp' ) + 1 * MONTH_IN_SECONDS );
					$posts['date_query']  = array(
						array(
							'year'  => $twodayslater['year'],
							'month' => $twodayslater['mon'],
						),
					);
					$posts['post_status'] = array( 'future' );
				}
				if ( 'last_n_days' === $blog_time_period ) {
					if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
						$last_n_days         = $bdp_settings['bdp_time_period_day'] . ' days ago';
						$posts['date_query'] = array(
							array(
								'after'     => $last_n_days,
								'inclusive' => true,
							),
						);
					}
				}
				if ( 'next_n_days' === $blog_time_period ) {
					if ( isset( $bdp_settings['bdp_time_period_day'] ) && $bdp_settings['bdp_time_period_day'] ) {
						$next_n_days          = '+' . $bdp_settings['bdp_time_period_day'] . ' days';
						$posts['date_query']  = array(
							array(
								'before'    => gmdate( 'Y-m-d', strtotime( $next_n_days ) ),
								'inclusive' => true,
							),
						);
						$posts['post_status'] = array( 'future' );
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
					$posts['date_query'] = array(
						array(
							'after'     => $after,
							'before'    => $before,
							'inclusive' => true,
						),
					);
				}
			}
		}
		return $posts;
	}


	/**
	 * Update calculated post count
	 *
	 * @param int $post_id post id.
	 * @return void
	 */
	public static function set_post_views( $post_id ) {
		// retrieve the current IP address of the visitor.
		$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key     = $user_ip . 'x' . $post_id; // combine post ID & IP to form unique key.
		$value   = array( $user_ip, $post_id ); // store post ID & IP as separate values (see note).
		$visited = get_transient( $key ); // get transient and store in variable.
		// check to see if the Post ID/IP ($key) address is currently stored as a transient.
		if ( false == ( $visited ) ) {
			// store the unique key, Post ID & IP address for 24 hours if it does not exist.
			set_transient( $key, $value, 60 * 60 * 24 );
			// now run post views function.
			$count_key        = '_bdp_post_views_count';
			$daily_count_key  = '_bdp_post_daily_count';
			$daily_count_date = '_bdp_daily_view_date';
			$count            = get_post_meta( $post_id, $count_key, true );
			if ( '' == $count ) {
				$count = 1;
			} else {
				++$count;
			}
			update_post_meta( $post_id, $count_key, $count );
			$viewed_count_daily = get_post_meta( $post_id, $daily_count_key, true );
			$daily_date         = get_post_meta( $post_id, $daily_count_date, true );
			if ( gmdate( 'Y-m-d' ) == $daily_date ) {
				update_post_meta( $post_id, $daily_count_key, $viewed_count_daily + 1 );
			} else {
				update_post_meta( $post_id, $daily_count_key, '1' );
			}
			update_post_meta( $post_id, $daily_count_date, gmdate( 'Y-m-d' ) );
		}
	}
	/**
	 * Get comments count
	 *
	 * @param boolean $comment_link comment link.
	 * @return void
	 */
	public static function comment_count( $comment_link = true ) {
		$id             = get_the_ID();
		$num_comments   = get_comments_number( $id );
		$write_comments = '';
		if ( comments_open() ) {
			if ( 0 == $num_comments ) {
				$comments = esc_html__( 'No Comments', 'blog-designer-pro' );
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . ' ' . esc_html__( 'Comments', 'blog-designer-pro' );
			} else {
				$comments = '1 ' . esc_html__( 'Comment', 'blog-designer-pro' );
			}
			if ( $comment_link ) {
				$write_comments = '<a href="' . get_comments_link() . '">' . $comments . '</a>';
			} else {
				$write_comments = $comments;
			}
		} else {
			$write_comments = esc_html__( 'Comments are closed', 'blog-designer-pro' );
		}
		echo wp_kses( $write_comments, Bdp_Admin_Functions::args_kses() );
	}
	/**
	 * Get parameter array for archive posts query
	 *
	 * @since 2.0
	 * @param array $bdp_settings settings.
	 * @return array parameters for posts query
	 */
	public static function get_archive_wp_query( $bdp_settings ) {
		global $wp_query;
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
			$cat = $wp_query->get_queried_object_id();

			$arg_posts = array(
				'post_type'      => 'post',
				'posts_per_page' => $posts_per_page,
				'orderby'        => $orderby_str,
				'order'          => $order,
				'paged'          => $paged,
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
		} elseif ( isset( $bdp_settings['custom_archive_type'] ) && 'tag_template' === $bdp_settings['custom_archive_type'] ) {

			$tag = $wp_query->get_queried_object_id();
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
		} elseif ( isset( $bdp_settings['custom_archive_type'] ) && 'date_template' === $bdp_settings['custom_archive_type'] ) {
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
		if ( isset( $bdp_settings['custom_archive_type'] ) && 'author_template' === $bdp_settings['custom_archive_type'] ) {
			$arg_posts['author__in'] = $wp_query->query_vars['author'];
		}
		if ( isset( $bdp_settings['custom_archive_type'] ) && 'search_template' === $bdp_settings['custom_archive_type'] ) {
			$arg_posts['s'] = isset( $_GET['s'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) : '';
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
	 *  Get all post
	 *
	 * @since 2.6
	 * @return void
	 */
	public function blogdesign_get_bdp_posts() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			?>
			<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Posts', 'blog-designer-pro' ); ?></span></div>
			<div class="bdp-right">
				<?php $custom_post_type = isset( $_POST['custom_post_type'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['custom_post_type'] ) ) ) : ''; ?>
					<select id="bdp_filter_post" name="bdp_filter_post[]" class="chosen-select"  data-placeholder="Choose Posts" multiple="multiple">
						<?php
						$args      = array(
							'post_type'      => $custom_post_type,
							'posts_per_page' => -1,
							'parent'         => 0,
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								?>
								<option value="<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></option>
								<?php
							}
						}
						?>
					</select>
				<div class="bdp-setting-description bdp-note">
					<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
					<?php esc_html_e( 'If select post, then displays selected post. Leave blank to display all posts.', 'blog-designer-pro' ); ?>
				</div>
			</div>
			<?php
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 *  Get taxonomy filter list
	 *
	 * @since 2.6
	 * @return void
	 */
	public function bdp_get_display_taxonomy_filter_list() {
		ob_start();
		$custom_posttype = isset( $_POST['posttype'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) ) : '';
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			?>
			<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Display Isotop Filter by', 'blog-designer-pro' ); ?></span></div>
			<div class="bdp-right">
				<select id="display_filter_by" name="display_filter_by">
					<?php
					$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
					$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
					if ( ! empty( $taxonomy_names ) ) {
						foreach ( $taxonomy_names as $taxonomy_name ) {
							$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
							if ( ! empty( $terms ) ) {
								?>
								<option value="<?php echo esc_html( $taxonomy_name->name ); ?>"><?php echo esc_html( $taxonomy_name->label ); ?></option>
								<?php
							}
						}
					}
					?>
				</select>
			</div>
			<?php
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get tabbed taxonomy filter list
	 *
	 * @since 2.6
	 * @return void
	 */
	public function bdp_get_tabbed_display_taxonomy_filter_list() {
		ob_start();
		$custom_posttype = isset( $_POST['posttype'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) ) : '';
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			?>
			<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Display Tabbed layout Filter by', 'blog-designer-pro' ); ?></span></div>
			<div class="bdp-right">        
				<select id="display_tabbed_filter_by" name="display_tabbed_filter_by">
					<?php
					$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
					$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
					if ( ! empty( $taxonomy_names ) ) {
						foreach ( $taxonomy_names as $taxonomy_name ) {
							$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
							if ( ! empty( $terms ) ) {
								?>
								<option value="<?php echo esc_html( $taxonomy_name->name ); ?>" ><?php echo esc_html( $taxonomy_name->label ); ?></option>
								<?php
							}
						}
					}
					?>
				</select>
			</div>
			<?php
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get tabbed display taxonomy list
	 *
	 * @since 2.6
	 * @return void
	 */
	public function bdp_get_tabbed_display_taxonomy_list() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$custom_posttype          = isset( $_POST['posttype'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) ) : '';
			$display_tabbed_filter_by = isset( $_POST['display_tabbed_filter_by'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['display_tabbed_filter_by'] ) ) ) : '';
			$terms                    = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => false ) );
			$taxonomy_names           = get_object_taxonomies( $custom_posttype, 'objects' );
			$taxonomy_names           = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
			if ( ! empty( $terms ) ) {
				foreach ( $taxonomy_names as $taxonomy_name ) {
					if ( $taxonomy_name->name == $display_tabbed_filter_by ) {
						?>
						<div class="bdp-left"><span class="bdp-key-title"><?php echo esc_html__( 'Select tabbed layout', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ); ?></span></div>
						<div class="bdp-right">
							<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_tabbed_terms[]" id="terms_tabbed_<?php echo esc_attr( $taxonomy_name->name ); ?>" data-name="<?php echo esc_attr( $taxonomy_name->name ); ?>">
							<?php foreach ( $terms as $term ) { ?>
									<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
							<?php } ?>
							</select>
						</div>
						<?php
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get post on change archive template
	 *
	 * @since 2.6
	 */
	public function bdp_get_posts_archive() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			?>
			<?php
			if ( isset( $_POST['tax'] ) && 'author_template' === $_POST['tax'] ) {
					$taxonomy = 'user';
			} elseif ( isset( $_POST['tax'] ) && 'category_template' === $_POST['tax'] ) {
				if ( isset( $_POST['post_type'] ) && 'post' === $_POST['post_type'] ) {
					$taxonomy = 'category';
				} elseif ( isset( $_POST['post_type'] ) && 'download' === $_POST['post_type'] ) {
					$taxonomy = 'download_category';
				} else {
					$taxonomy = 'product_cat';
				}
			} elseif ( isset( $_POST['tax'] ) && 'tag_template' === $_POST['tax'] ) {
				if ( 'post' === $_POST['post_type'] ) {
					$taxonomy = 'post_tag';

				} elseif ( isset( $_POST['post_type'] ) && 'download' === $_POST['post_type'] ) {
					$taxonomy = 'download_tag';
				} else {
					$taxonomy = 'product_tag';
				}
			}
			$args = array(
				'cache_results' => false,
				'no_found_rows' => true,
				'fields'        => 'ids',
				'showposts'     => '-1',
				'post_status'   => 'publish',
				'post_type'     => isset( $_POST['post_type'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) : '',
			);
			if ( isset( $_POST['tax_ids'] ) && '' != $_POST['tax_ids'] ) {
				if ( isset( $_POST['tax'] ) && 'category_template' === $_POST['tax'] || 'tag_template' === $_POST['tax'] ) {
					$tax_ids           = isset( $_POST['tax_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['tax_ids'] ) ) : array();
					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => (array) $tax_ids,
						),
					);
				}
				if ( isset( $_POST['tax'] ) && 'author_template' === $_POST['tax'] ) {
					$args['author__in'] = (array) $tax_ids;
				}
			}
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				echo '<select name="timeline_start_from" id="timeline_start_from" class="chosen-select">';
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$post__id = get_the_ID();
					?>
					<option value="<?php echo esc_attr( get_the_date( 'd/m/Y', $post__id ) ); ?>" ><?php echo esc_html( get_the_title( $post__id ) ); ?></option>
					<?php
				}
				wp_reset_postdata();
				echo '</select>';
			} else {
				echo '<p>' . esc_html__( 'No Post Found', 'blog-designer-pro' ) . '</p>';
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get all post list
	 *
	 * @since 2.6
	 */
	public function bdp_get_all_post_lists() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$args          = array(
				'post_type'      => isset( $_POST['custom_post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_post_type'] ) ) : '',
				'posts_per_page' => -1,
			);
			$selected_post = isset( $_POST['selected_post'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_post'] ) ) : '';
			$count_options = isset( $_POST['count_options'] ) ? sanitize_text_field( wp_unslash( $_POST['count_options'] ) ) : '';
			if ( ! empty( $selected_post ) ) {
				$selected_post = explode( ',', $selected_post );
			}
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					if ( ! empty( $selected_post ) && ! in_array( get_the_ID(), $selected_post ) && 1 == $count_options ) {
						?>
						<option value="<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></option>
						<?php
					}
					if ( empty( $selected_post ) && 0 == $count_options ) {
						?>
						<option value="<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></option>
						<?php
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get all users lists
	 *
	 * @since 2.7
	 * @return void
	 */
	public function bdp_get_all_users_lists() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$selected_user = isset( $_POST['selected_author'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_author'] ) ) : '';
			$count_options = isset( $_POST['count_options'] ) ? sanitize_text_field( wp_unslash( $_POST['count_options'] ) ) : '';
			if ( ! empty( $selected_user ) ) {
				$selected_user = explode( ',', $selected_user );
			}
			$blogusers = get_users( 'orderby=nicename&order=asc' );
			foreach ( $blogusers as $user ) {
				if ( ! empty( $selected_user ) && ! in_array( $user->ID, $selected_user ) && 1 == $count_options ) {
					?>
					<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
					<?php
				}
				if ( empty( $selected_user ) && 0 == $count_options ) {
					?>
					<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
					<?php
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get all timeline post lists
	 *
	 * @since 2.7
	 * @return void
	 */
	public function bdp_get_all_timeline_post_lists() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$args          = array(
				'post_type'      => isset( $_POST['custom_post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_post_type'] ) ) : '',
				'posts_per_page' => -1,
			);
			$selected_post = isset( $_POST['selected_post'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_post'] ) ) : '';
			$the_query     = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					if ( get_the_ID() != $selected_post ) {
						?>
						<option value="<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_attr( get_the_title() ); ?></option>
						<?php
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Archive get all user lists
	 *
	 * @since 2.7
	 * @return void
	 */
	public function bdp_archive_get_all_users_lists() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$selected_user = isset( $_POST['selected_author'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_author'] ) ) : array();
			if ( ! empty( $selected_user ) ) {
				$selected_user = explode( ',', $selected_user );
			}
			$final_author_hidden = isset( $_POST['final_author_hidden'] ) ? sanitize_text_field( wp_unslash( $_POST['final_author_hidden'] ) ) : array();
			if ( ! empty( $final_author_hidden ) ) {
				$final_author_hidden = explode( ',', $final_author_hidden );
			}
			$author_hidden              = array_merge( explode( ',', $final_author_hidden ), explode( ',', $selected_user ) );
			$bdp_multi_author_selection = get_option( 'bdp_multi_author_selection', 1 );
			$blogusers                  = get_users( 'orderby=nicename&order=asc' );
			foreach ( $blogusers as $user ) {
				if ( 1 == $bdp_multi_author_selection ) {
					?>
					<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
					<?php
				} elseif ( is_array( $final_author_hidden ) && ! empty( $final_author_hidden ) ) {
					if ( ! in_array( $user->ID, $author_hidden ) ) {
						?>
						<option value="<?php echo esc_attr( $user->ID ); ?>" 
							<?php
							if ( in_array( $user->ID, $final_author_hidden ) ) {
								echo 'disabled="disabled"';
							}
							?>
							>
							<?php echo esc_html( $user->display_name ); ?>
						</option>
						<?php
					}
				} elseif ( ! in_array( $user->ID, $author_hidden ) ) {
					?>
						<option value="<?php echo esc_attr( $user->ID ); ?>" 
							<?php
							if ( in_array( $user->ID, $author_hidden ) ) {
								echo 'disabled="disabled"';
							}
							?>
							>
							<?php echo esc_html( $user->display_name ); ?>
						</option>
						<?php

				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Display Single post content
	 *
	 * @since 2.7
	 * @param array $bdp_settings settings.
	 * @param int   $post_id id.
	 * @return void
	 */
	public function single_post_content( $bdp_settings, $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( 'product' === $post_type && isset( $bdp_settings['template_post_content_from'] ) ) {
			$product_details = wc_get_product( $post_id );
			if ( 'from_excerpt' === $bdp_settings['template_post_content_from'] && ! empty( $product_details->get_short_description() ) ) {
				$content = $product_details->get_short_description();
			} else {
				$content = $product_details->get_description();
			}
			if ( isset( $bdp_settings['firstletter_big'] ) && 1 == $bdp_settings['firstletter_big'] ) {
				$content = Bdp_Utility::add_first_letter_structure( $content );
				$content = apply_filters( 'bdp_woocommerce_short_description', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
				echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
			} else {
				echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
			}
		} elseif ( isset( $bdp_settings['firstletter_big'] ) && 1 == $bdp_settings['firstletter_big'] ) {
				$content = get_the_content();
				$content = Bdp_Utility::add_first_letter_structure( $content );
			if ( 'download' !== $post_type ) {
				$content = apply_filters( 'the_content', $content );
			}
				$content = str_replace( ']]>', ']]&gt;', $content );
				echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
		} elseif ( 'download' === $post_type ) {
				$content = get_the_content();
				echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
		} else {
			the_content();
		}
	}
	/**
	 * Hide custom taxonomy
	 *
	 * @param string $taxonomy_names tax name.
	 * @return $taxonomy_names
	 */
	public function bdp_hide_taxonomies( $taxonomy_names ) {
		foreach ( $taxonomy_names as $taxonomy_i => $taxonomy_name ) {
			if ( ! empty( $taxonomy_name ) ) {
				if ( '1' != $taxonomy_name->show_ui ) {
					unset( $taxonomy_names[ $taxonomy_i ] );
				}
			}
		}
		return $taxonomy_names;
	}

	/**
	 * Display counter with view ie. 999 Views
	 *
	 * @param int   $post_id post id.
	 * @param array $bdp_settings settings.
	 * @return string $count_data
	 */
	public static function get_post_views( $post_id, $bdp_settings ) {
		$count_key       = '_bdp_post_views_count';
		$daily_count_key = '_bdp_post_daily_count';
		$count           = get_post_meta( $post_id, $count_key, true );
		$daily_count     = get_post_meta( $post_id, $daily_count_key, true );
		if ( '' == $count ) {
			$count = 1;
			update_post_meta( $post_id, $count_key, $count );
		}
		if ( '' == $daily_count ) {
			$daily_count = 1;
			update_post_meta( $post_id, $daily_count_key, $daily_count );
		}
		$sep        = ', ';
		$count_data = '';
		if ( isset( $bdp_settings['display_post_views'] ) && 1 == $bdp_settings['display_post_views'] ) {
			if ( $daily_count > 1 ) {
				$count_data .= '<p>' . $daily_count . ' ' . esc_html__( 'Visits today', 'blog-designer-pro' ) . '</p>';
			} else {
				$count_data .= '<p>' . $daily_count . ' ' . esc_html__( 'Visit today', 'blog-designer-pro' ) . '</p>';
			}
		}
		if ( isset( $bdp_settings['display_post_views'] ) && 2 == $bdp_settings['display_post_views'] ) {
			if ( $count > 1 ) {
				$count_data .= esc_html__( 'Visited', 'blog-designer-pro' ) . ' ' . $count . ' ' . esc_html__( 'Times', 'blog-designer-pro' );
			} else {
				$count_data .= esc_html__( 'Visited', 'blog-designer-pro' ) . ' ' . $count . ' ' . esc_html__( 'Time', 'blog-designer-pro' );
			}
			if ( $daily_count > 1 ) {
				$count_data .= $sep . $daily_count . ' ' . esc_html__( 'Visits today', 'blog-designer-pro' );
			} else {
				$count_data .= $sep . $daily_count . ' ' . esc_html__( 'Visit today', 'blog-designer-pro' );
			}
		}
		return $count_data;
	}

	/**
	 * Get default image
	 *
	 * @param array $bdp_settings settings.
	 * @param array $post_thumbnail thumbnail.
	 * @param int   $post_thumbnail_id id.
	 * @param int   $bdp_post_id post id.
	 * @return html image
	 */
	public static function get_the_thumbnail( $bdp_settings, $post_thumbnail, $post_thumbnail_id, $bdp_post_id ) {

		$thumbnail = '';
		if ( '' == $post_thumbnail ) {
			$post_thumbnail = 'full';
		}
		if ( has_post_thumbnail( $bdp_post_id ) ) {
			if ( isset( $bdp_settings['bdp_media_size'] ) ) {
				if ( 'custom' === $bdp_settings['bdp_media_size'] ) {

					$url    = wp_get_attachment_url( $post_thumbnail_id );
					$width  = isset( $bdp_settings['media_custom_width'] ) ? $bdp_settings['media_custom_width'] : 560;
					$height = isset( $bdp_settings['media_custom_height'] ) ? $bdp_settings['media_custom_height'] : 350;

					$lazyload_class = '';
					if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
						$lazyload_class = 'lazyload';
					}
					$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, $post_thumbnail_id );

					if ( isset( $resized_image['url'] ) ) {
						$thumbnail = '<img class="' . $lazyload_class . '" src="' . $resized_image['url'] . '" data-src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" title="' . get_the_title( $bdp_post_id ) . '" alt="' . get_the_title( $bdp_post_id ) . '"  />';
					} elseif ( in_array( $bdp_settings['template_name'], array( 'boxy-clean', 'brit_co', 'deport', 'elina', 'invert-grid', 'media-grid', 'masonry_timeline', 'my_diary', 'navia', 'brite', 'chapter', 'fairy', 'integer', 'advice', 'minimal', 'clicky', 'roctangle', 'glamour', 'blog_carousel', 'blog_grid_box', 'sallet_slider', 'colorful_sliding', 'threed_carousel', 'flip_book_3d', 'banner', 'leest' ) ) ) {
							$thumbnail = self::get_sample_image( $bdp_post_id, $bdp_settings['template_name'] );
					}
				} else {
					$post_thumbnail = $bdp_settings['bdp_media_size'];
					$thumbnail      = get_the_post_thumbnail( $bdp_post_id, $post_thumbnail );
				}
			} else {
				$thumbnail = get_the_post_thumbnail( $bdp_post_id, $post_thumbnail );
			}
		} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) {

			if ( isset( $bdp_settings['bdp_media_size'] ) ) {
				if ( 'custom' === $bdp_settings['bdp_media_size'] ) {

					$post_thumbnail_id = $bdp_settings['bdp_default_image_id'];
					$url               = wp_get_attachment_url( $post_thumbnail_id );
					$width             = isset( $bdp_settings['media_custom_width'] ) ? $bdp_settings['media_custom_width'] : 560;
					$height            = isset( $bdp_settings['media_custom_height'] ) ? $bdp_settings['media_custom_height'] : 350;
					$resized_image     = Bdp_Utility::image_resize( $width, $height, $url, true, $post_thumbnail_id );
					$thumbnail         = '<img src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" title="' . get_the_title( $bdp_post_id ) . '" alt="' . get_the_title( $bdp_post_id ) . '"  />';
				} else {
					$post_thumbnail = $bdp_settings['bdp_media_size'];
					$thumbnail      = wp_get_attachment_image( $bdp_settings['bdp_default_image_id'], $post_thumbnail );
				}
			} else {
				$thumbnail = wp_get_attachment_image( $bdp_settings['bdp_default_image_id'], $post_thumbnail );
			}
		} elseif ( 'tabbed' == $bdp_settings ) {
				$thumbnail = self::get_sample_image( $bdp_post_id, $bdp_settings );
		} elseif ( in_array( $bdp_settings['template_name'], array( 'boxy-clean', 'brit_co', 'deport', 'elina', 'invert-grid', 'media-grid', 'masonry_timeline', 'my_diary', 'navia', 'brite', 'chapter', 'fairy', 'integer', 'advice', 'minimal', 'clicky', 'roctangle', 'glamour', 'blog_carousel', 'blog_grid_box', 'sallet_slider', 'colorful_sliding', 'threed_carousel', 'news', 'flip_book_3d', 'banner', 'leest' ) ) ) {
				$thumbnail = self::get_sample_image( $bdp_post_id, $bdp_settings['template_name'] );
		}
		return $thumbnail;
	}
	/**
	 * Get the single post thumbnail
	 *
	 * @param array $bdp_settings settings.
	 * @param int   $post_thumbnail_id id.
	 * @param int   $bdp_post_id post id.
	 * @return $thumbnail
	 */
	public static function get_the_single_post_thumbnail( $bdp_settings, $post_thumbnail_id, $bdp_post_id ) {
		$thumbnail      = '';
		$post_thumbnail = 'full';
		if ( has_post_thumbnail() ) {
			if ( isset( $bdp_settings['bdp_media_size'] ) ) {
				if ( 'custom' === $bdp_settings['bdp_media_size'] ) {
					$url           = wp_get_attachment_url( $post_thumbnail_id );
					$width         = isset( $bdp_settings['media_custom_width'] ) ? $bdp_settings['media_custom_width'] : 560;
					$height        = isset( $bdp_settings['media_custom_height'] ) ? $bdp_settings['media_custom_height'] : 350;
					$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, $post_thumbnail_id );
					$thumbnail     = '<img src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" title="' . get_the_title( $bdp_post_id ) . '" alt="' . get_the_title( $bdp_post_id ) . '" />';
				} else {
					$post_thumbnail = $bdp_settings['bdp_media_size'];
					$thumbnail      = get_the_post_thumbnail( $bdp_post_id, $post_thumbnail );
				}
			} else {
				$thumbnail = get_the_post_thumbnail( $bdp_post_id, $post_thumbnail );
			}
		}
		return $thumbnail;
	}
	/**
	 * Related Post Display title
	 *
	 * @param string $template template.
	 * @param int    $post_perpage perpage.
	 * @param string $related_post_by related.
	 * @param string $title title.
	 * @return void
	 */
	public static function related_post_title( $template, $post_perpage, $related_post_by, $title ) {
		global $post;
		$post_type = get_post_type( $post->ID );
		if ( 'product' === $post_type ) {
			?>
			<h3>
			<?php
			if ( '' != $title ) {
				echo esc_html( $title );
			} else {
				esc_html_e( 'Related Product', 'blog-designer-pro' );
			}
			?>
				</h3>
			<?php
		} elseif ( 'download' === $post_type ) {
			?>
			<h3>
				<?php
				if ( '' != $title ) {
					echo esc_html( $title );
				} else {
					esc_html_e( 'Related Product', 'blog-designer-pro' );
				}
				?>
			</h3>
			<?php
		} else {
			?>
			<h3>
			<?php
			if ( '' != $title ) {
				echo esc_html( $title );
			} else {
				esc_html_e( 'Related Posts', 'blog-designer-pro' );
			}
			?>
				</h3>
			<?php
		}
	}
	/**
	 * Function for display related post items
	 *
	 * @param string $template template.
	 * @param int    $post_perpage perpage.
	 * @param string $related_post_by replated post.
	 * @param string $title title.
	 * @param string $orderby orderby.
	 * @param string $order order.
	 * @param int    $related_post_content_length related.
	 * @param int    $related_post_content_from related from.
	 * @param int    $bdp_settings settings.
	 * @return void
	 */
	public static function related_post_item( $template, $post_perpage, $related_post_by, $title, $orderby, $order, $related_post_content_length, $related_post_content_from = 'from_content', $bdp_settings = array() ) {
		$related_postcontent = isset( $bdp_settings['related_post_column'] ) ? $bdp_settings['related_post_column'] : 3;
		if ( 2 == $related_postcontent ) {
			$col_class = 'two_post';
		} elseif ( 3 == $related_postcontent ) {
			$col_class = 'three_post';
		} elseif ( 4 == $related_postcontent ) {
			$col_class = 'four_post';
		} else {
			$col_class = '';
		}
		global $post;
		$post_type = get_post_type( $post->ID );
		?>
		<div class="related_post_div <?php echo esc_attr( $col_class ); ?>">
			<div class="relatedposts">
				<?php
				$args = array();
				if ( 'product' === $post_type ) {
					if ( 'category' === $related_post_by ) {
						$categories = get_the_terms( $post->ID, 'product_cat' );
						if ( $categories ) {
							$category_ids = array();
							foreach ( $categories as $individual_category ) {
								$category_ids[] = $individual_category->term_id;
							}
							$args              = array(
								'post_type'           => 'product',
								'post__not_in'        => array( $post->ID ),
								'posts_per_page'      => $post_perpage, // Number of related posts that will be displayed.
								'ignore_sticky_posts' => 1,
								'orderby'             => $orderby,
								'order'               => $order,
							);
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'term_id',
									'terms'    => $category_ids,
								),
							);
						}
					} elseif ( 'tag' === $related_post_by ) {
						$tags = get_the_terms( $post->ID, 'product_tag' );
						if ( $tags ) {
							$tag_ids = array();
							foreach ( $tags as $individual_tag ) {
								$tag_ids[] = $individual_tag->term_id;
							}
							$args              = array(
								'post_type'           => 'product',
								'post__not_in'        => array( $post->ID ),
								'posts_per_page'      => $post_perpage, // Number of related posts that will be displayed.
								'ignore_sticky_posts' => 1,
								'orderby'             => $orderby,
								'order'               => $order,
							);
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'product_tag',
									'field'    => 'term_id',
									'terms'    => $tag_ids,
								),
							);
						}
					}
				} elseif ( 'download' === $post_type ) {
					if ( 'category' === $related_post_by ) {
						$categories = get_the_terms( $post->ID, 'download_category' );
						if ( $categories ) {
							$category_ids = array();
							foreach ( $categories as $individual_category ) {
								$category_ids[] = $individual_category->term_id;
							}
							$args              = array(
								'post_type'           => 'download',
								'post__not_in'        => array( $post->ID ),
								'posts_per_page'      => $post_perpage, // Number of related posts that will be displayed.
								'ignore_sticky_posts' => 1,
								'orderby'             => $orderby,
								'order'               => $order,
							);
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'download_category',
									'field'    => 'term_id',
									'terms'    => $category_ids,
								),
							);
						}
					} elseif ( 'tag' === $related_post_by ) {
						$tags = get_the_terms( $post->ID, 'download_tag' );
						if ( $tags ) {
							$tag_ids = array();
							foreach ( $tags as $individual_tag ) {
								$tag_ids[] = $individual_tag->term_id;
							}
							$args              = array(
								'post_type'           => 'download',
								'post__not_in'        => array( $post->ID ),
								'posts_per_page'      => $post_perpage, // Number of related posts that will be displayed.
								'ignore_sticky_posts' => 1,
								'orderby'             => $orderby,
								'order'               => $order,
							);
							$args['tax_query'] = array(
								array(
									'taxonomy' => 'download_tag',
									'field'    => 'term_id',
									'terms'    => $tag_ids,
								),
							);
						}
					}
				} elseif ( 'category' === $related_post_by ) {
						$categories = get_the_category( $post->ID );
					if ( $categories ) {
						$category_ids = array();
						foreach ( $categories as $individual_category ) {
							$category_ids[] = $individual_category->term_id;
						}
						$args = array(
							'category__in'        => $category_ids,
							'post__not_in'        => array( $post->ID ),
							'posts_per_page'      => $post_perpage, // Number of related posts that will be displayed.
							'ignore_sticky_posts' => 1,
							'orderby'             => $orderby,
							'order'               => $order,
						);
					}
				} elseif ( 'tag' === $related_post_by ) {
					$tags = wp_get_post_tags( $post->ID );
					if ( $tags ) {
						$tag_ids = array();
						foreach ( $tags as $individual_tag ) {
							$tag_ids[] = $individual_tag->term_id;
						}
						$args = array(
							'tag__in'        => $tag_ids,
							'post__not_in'   => array( $post->ID ),
							'posts_per_page' => $post_perpage, // Number of related posts to display.
							'orderby'        => $orderby,
							'order'          => $order,
						);
					}
				}
				$bdp_display_related_postcontent = isset( $bdp_settings['bdp_display_related_postcontent'] ) ? $bdp_settings['bdp_display_related_postcontent'] : 'bottom';
				$my_query                        = new wp_query( $args );
				if ( $my_query->have_posts() ) {
					while ( $my_query->have_posts() ) {
						$my_query->the_post();
						$display_related_post_date    = isset( $bdp_settings['display_related_post_date'] ) ? $bdp_settings['display_related_post_date'] : 0;
						$display_related_post_comment = isset( $bdp_settings['display_related_post_comment'] ) ? $bdp_settings['display_related_post_comment'] : 0;
						?>
						<div class="relatedthumb <?php echo esc_attr( $bdp_display_related_postcontent ); ?>">
							<?php
							if ( 'right' === $bdp_display_related_postcontent || 'left' === $bdp_display_related_postcontent || 'bottom' === $bdp_display_related_postcontent ) {
								?>
								<div class="relatedthumb_image_wrap">
									<a rel="external" href="<?php the_permalink(); ?>" class="external">
										<?php
										if ( has_post_thumbnail() ) {
											$bdp_related_post_media_size = isset( $bdp_settings['bdp_related_post_media_size'] ) ? $bdp_settings['bdp_related_post_media_size'] : 'medium';
											if ( 'custom' === $bdp_related_post_media_size ) {
												$url           = wp_get_attachment_url( get_post_thumbnail_id() );
												$width         = isset( $bdp_settings['related_post_media_custom_width'] ) ? $bdp_settings['related_post_media_custom_width'] : 640;
												$height        = isset( $bdp_settings['related_post_media_custom_height'] ) ? $bdp_settings['related_post_media_custom_height'] : 300;
												$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, get_post_thumbnail_id() );
												$thumbnail     = '<img src="' . $resized_image['url'] . '" width="' . $resized_image['width'] . '" height="' . $resized_image['height'] . '" title="' . get_the_title( $post->ID ) . '" alt="' . get_the_title( $post->ID ) . '" />';
											} elseif ( 'product' === get_post_type( $post->ID ) ) {
												if ( isset( $bdp_settings['related_product_sale_tag'] ) && 1 == $bdp_settings['related_product_sale_tag'] ) {
													echo '<div class="bdp_related_woocommerce_sale_wrap">';
													do_action( 'bdp_woocommerce_sale_tag' );
													echo '</div>';
												}
													$thumbnail = wp_get_attachment_image( get_post_thumbnail_id(), $bdp_related_post_media_size );
											} else {
												$thumbnail = get_the_post_thumbnail( $post->ID, $bdp_related_post_media_size );
											}
										} else {
											$thumbnail = self::get_related_post_sample_image( $post->ID );
										}
										echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
										?>
									</a>
								</div>
								<div class="relatedthumb_content_wrap">    
									<?php
									if ( '' != get_the_title() ) {
										?>
										<a rel="external" href="<?php the_permalink(); ?>" class="external related_post_title"><div class="relatedpost_title"><?php the_title(); ?></div></a>
										<?php
									}
									if ( 'product' === get_post_type( $post->ID ) ) {
										if ( isset( $bdp_settings['related_product_price'] ) && 1 == $bdp_settings['related_product_price'] ) {
											echo '<div class="bdp_related_woocommerce_price_wrap">';
											do_action( 'bdp_woocommerce_price' );
											echo '</div>';
										}
										if ( isset( $bdp_settings['related_product_cart_button'] ) && 1 == $bdp_settings['related_product_cart_button'] ) {
											echo '<div class="bdp_related_product_woocommerce_add_to_cart_wrap">';
											do_action( 'bdp_woocommerce_add_to_cart' );
											echo '</div>';
										}
									}
									if ( 'download' === get_post_type( $post->ID ) ) {
										if ( isset( $bdp_settings['related_download_price'] ) && 1 == $bdp_settings['related_download_price'] ) {
											echo '<div class="bdp_related_woocommerce_price_wrap">';
											do_action( 'bdp_edd_single_download_price', $post->ID );
											echo '</div>';
										}
										if ( isset( $bdp_settings['related_download_cart_button'] ) && 1 == $bdp_settings['related_download_cart_button'] ) {
											echo '<div class="bdp_edd_download_buy_button">';
											if ( ! edd_has_variable_prices( $post->ID ) ) {
												$button_behavior = edd_get_download_button_behavior( $post->ID );
												echo wp_kses(
													edd_get_purchase_link(
														array(
															'download_id' => $post->ID,
															'price' => 'no',
														)
													),
													'post'
												);
											} else {
												$viewdetails = esc_html__( 'View Details', 'blog-designer-pro' );
												echo '<a class="button bdp_edd_view_button" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( $viewdetails ) . '</a>';
											}
											echo '</div>';
										}
									}
									if ( 1 == $display_related_post_date || 1 == $display_related_post_comment ) {
										echo '<div class="bdp-related-post-meta">';
										if ( 1 == $display_related_post_date ) {
											$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
											$bdp_date    = apply_filters( 'bdp_date_format', get_the_time( $date_format, get_the_ID() ), get_the_ID() );
											echo '<span class="bdp-related-date">' . esc_html( $bdp_date ) . '</span>';
										}
										if ( 1 == $display_related_post_comment ) {
											$related_date_class = '';
											if ( 0 == $display_related_post_date ) {
												$related_date_class = 'bdp-disable-date';
											}
											echo '<span class="bdp-related-comment ' . esc_attr( $related_date_class ) . '"><i class="fas fa-comment"></i>&nbsp;';
											comments_number( esc_html__( 'No comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
											echo '</span>';
										}
										echo '</div>';
									}
									if ( 0 != $related_post_content_length && '' != $related_post_content_length ) {
										?>
										<?php
										if ( 'from_description' === $related_post_content_from && '' != get_the_excerpt( get_the_ID() ) ) {
											$excerpt = get_the_excerpt( get_the_ID() );
										} else {
											$excerpt = get_the_content( get_the_ID() );
										}
										$bdp_excerpt_data = wp_trim_words( $excerpt, $related_post_content_length, ' ...' );
										$bdp_excerpt_data = apply_filters( 'bdp_related_post_content_change', $bdp_excerpt_data, get_the_ID() );
										if ( ! empty( $bdp_excerpt_data ) ) {
											echo '<div class="related_post_content">' . esc_html( $bdp_excerpt_data ) . '</div>';
										}
										?>
										<?php
									}
									?>
								</div>
							<?php } ?>
							<?php if ( 'top' === $bdp_display_related_postcontent ) { ?>
								<div class="relatedthumb_content_wrap">
									<?php
									if ( '' != get_the_title() ) {
										?>
										<a rel="external" href="<?php the_permalink(); ?>" class="external related_post_title"><div class="relatedpost_title"><?php the_title(); ?></div></a>
										<?php
									}
									if ( 'product' === get_post_type( $post->ID ) ) {
										if ( isset( $bdp_settings['related_product_price'] ) && 1 == $bdp_settings['related_product_price'] ) {
											echo '<div class="bdp_related_woocommerce_price_wrap">';
											do_action( 'bdp_woocommerce_price' );
											echo '</div>';
										}
										if ( isset( $bdp_settings['related_product_cart_button'] ) && 1 == $bdp_settings['related_product_cart_button'] ) {
											echo '<div class="bdp_related_product_woocommerce_add_to_cart_wrap">';
											do_action( 'bdp_woocommerce_add_to_cart' );
											echo '</div>';
										}
									}
									if ( 'download' === get_post_type( $post->ID ) ) {
										if ( isset( $bdp_settings['related_download_price'] ) && 1 == $bdp_settings['related_download_price'] ) {
											echo '<div class="bdp_related_woocommerce_price_wrap">';
											do_action( 'bdp_edd_single_download_price', $post->ID );
											echo '</div>';
										}
										if ( isset( $bdp_settings['related_download_cart_button'] ) && 1 == $bdp_settings['related_download_cart_button'] ) {
											echo '<div class="bdp_edd_download_buy_button">';
											if ( ! edd_has_variable_prices( $post->ID ) ) {
												$button_behavior = edd_get_download_button_behavior( $post->ID );
												echo wp_kses(
													edd_get_purchase_link(
														array(
															'download_id' => $post->ID,
															'price' => 'no',
														)
													),
													'post'
												);
											} else {
												$viewdetails = esc_html__( 'View Details', 'blog-designer-pro' );
												echo '<a class="button bdp_edd_view_button" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( $viewdetails ) . '</a>';
											}
											echo '</div>';
										}
									}
									if ( 1 == $display_related_post_date || 1 == $display_related_post_comment ) {
										echo '<div class="bdp-related-post-meta">';
										if ( 1 == $display_related_post_date ) {
											$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
											$bdp_date    = apply_filters( 'bdp_date_format', get_the_time( $date_format, get_the_ID() ), get_the_ID() );
											echo '<span class="bdp-related-date">' . esc_html( $bdp_date ) . '</span>';
										}
										if ( 1 == $display_related_post_comment ) {
											$related_date_class = '';
											if ( 0 == $display_related_post_date ) {
												$related_date_class = 'bdp-disable-date';
											}
											echo '<span class="bdp-related-comment ' . esc_attr( $related_date_class ) . '"><i class="fas fa-comment"></i>&nbsp;';
											comments_number( esc_html__( 'No comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
											echo '</span>';
										}
										echo '</div>';
									}
									if ( 0 != $related_post_content_length && '' != $related_post_content_length ) {
										?>
										<?php
										if ( 'from_description' === $related_post_content_from && '' != get_the_excerpt( get_the_ID() ) ) {
											$excerpt = get_the_excerpt( get_the_ID() );
										} else {
											$excerpt = get_the_content( get_the_ID() );
										}
										$bdp_excerpt_data = wp_trim_words( $excerpt, $related_post_content_length, ' ...' );
										$bdp_excerpt_data = apply_filters( 'bdp_related_post_content_change', $bdp_excerpt_data, get_the_ID() );
										if ( ! empty( $bdp_excerpt_data ) ) {
											echo '<div class="related_post_content">' . esc_html( $bdp_excerpt_data ) . '</div>';
										}
										?>
										<?php
									}
									?>
								</div>
								<div class="relatedthumb_image_wrap">
									<a rel="external" href="<?php the_permalink(); ?>" class="external">
										<?php
										if ( has_post_thumbnail() ) {
											$bdp_related_post_media_size = isset( $bdp_settings['bdp_related_post_media_size'] ) ? $bdp_settings['bdp_related_post_media_size'] : 'medium';
											if ( 'custom' === $bdp_related_post_media_size ) {
												$url           = wp_get_attachment_url( get_post_thumbnail_id() );
												$width         = isset( $bdp_settings['related_post_media_custom_width'] ) ? $bdp_settings['related_post_media_custom_width'] : 640;
												$height        = isset( $bdp_settings['related_post_media_custom_height'] ) ? $bdp_settings['related_post_media_custom_height'] : 300;
												$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, get_post_thumbnail_id() );
												$thumbnail     = '<img src="' . esc_url( $resized_image['url'] ) . '" width="' . esc_attr( $resized_image['width'] ) . '" height="' . esc_attr( $resized_image['height'] ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" alt="' . esc_attr( get_the_title( $post->ID ) ) . '" />';
											} elseif ( 'product' === get_post_type( $post->ID ) ) {
												if ( isset( $bdp_settings['related_product_sale_tag'] ) && 1 == $bdp_settings['related_product_sale_tag'] ) {
													echo '<div class="bdp_related_woocommerce_sale_wrap">';
													do_action( 'bdp_woocommerce_sale_tag' );
													echo '</div>';
												}
													$thumbnail = wp_get_attachment_image( get_post_thumbnail_id(), $bdp_related_post_media_size );
											} else {
												$thumbnail = get_the_post_thumbnail( $post->ID, $bdp_related_post_media_size );
											}
										} else {
											$thumbnail = self::get_related_post_sample_image( $post->ID );
										}
										echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
										?>
									</a>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
				} else {
					echo '<span class="bdp-no-post-found">';
					esc_html_e( 'No posts found.', 'blog-designer-pro' );
					echo '</span>';
				}
				wp_reset_query();
				?>
			</div>
		</div>
		<?php
	}
}
new Bdp_Posts();
