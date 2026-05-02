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
 * @class   Bdp_Utility
 * @version 1.0.0
 */
class Bdp_Utility {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'display_post_states', array( $this, 'add_post_states' ), 10, 2 );
		add_action( 'current_screen', array( $this, 'bdp_footer' ) );
		add_action( 'admin_init', array( $this, 'detail_ignore' ) );
		add_action( 'admin_footer', array( $this, 'print_js' ), 25 );
	}
	/**
	 * Utility function to format the button count,
	 * appending "K" if one thousand or greater,
	 * "M" if one million or greater,
	 * and "B" if one billion or greater (unlikely).
	 * $precision = how many decimal points to display (1.25K)
	 *
	 * @param int $number number.
	 * @return string $formatted
	 */
	public static function format_count( $number ) {
		$precision = 2;
		if ( $number >= 1000 && $number < 1000000 ) {
			$formatted = number_format( $number / 1000, $precision ) . 'K';
		} elseif ( $number >= 1000000 && $number < 1000000000 ) {
			$formatted = number_format( $number / 1000000, $precision ) . 'M';
		} elseif ( $number >= 1000000000 ) {
			$formatted = number_format( $number / 1000000000, $precision ) . 'B';
		} else {
			$formatted = $number; // Number is less than 1000.
		}
		$formatted = str_replace( '.00', '', $formatted );
		return $formatted;
	}

	/**
	 * Give rgba() color
	 *
	 * @param string  $color color.
	 * @param boolean $opacity opacity.
	 * @return $output
	 */
	public static function hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';
		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}
		// Sanitize $color if "#" is provided.
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}
		// Check if color has 6 or 3 characters and get values.
		if ( 6 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}
		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );
		// Check if opacity is set(rgba or rgb).
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}
		// Return rgb(a) color string.
		return $output;
	}
	/**
	 * Resize Images
	 *
	 * @param int     $width width.
	 * @param int     $height height.
	 * @param string  $img_url image url.
	 * @param boolean $crop crop.
	 * @param int     $thumbnail_id thumbanil id.
	 * @return $bdp_images
	 */
	public static function image_resize( $width, $height, $img_url = null, $crop = false, $thumbnail_id = 0 ) {
		$file_path = '';
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr' );
		}
		// this is an attachment, so we have the ID.
		if ( $img_url ) {
			$file_path = wp_parse_url( $img_url );
			$doc_root  = isset( $_SERVER['DOCUMENT_ROOT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['DOCUMENT_ROOT'] ) ) : '';
			$file_path = $doc_root . $file_path['path'];
			// Look for Multisite Path.
			if ( is_multisite() ) {
				$file_path = get_attached_file( $thumbnail_id, false );
			}
			if ( ! file_exists( $file_path ) ) {
				return;
			}
			$orig_size    = getimagesize( $file_path );
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}
		if ( ! $file_path ) {
			return;
		}
		$file_info = pathinfo( $file_path );
		if ( ! isset( $file_info['dirname'] ) || ! isset( $file_info['filename'] ) || ! isset( $file_info['extension'] ) ) {
			return;
		}
		// check if file exists.
		$base_file = $file_info['dirname'] . '/' . $file_info['filename'] . '.' . $file_info['extension'];
		if ( ! file_exists( $base_file ) ) {
			return;
		}
		if ( ! file_exists( $base_file ) ) {
			return;
		}
		$extension = '.' . $file_info['extension'];
		// the image path without the extension.
		$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
		$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
		// checking if the file size is larger than the target size.
		// if it is smaller or the same size, stop right here and return.
		if ( $image_src[1] > $width ) {
			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match).
			if ( file_exists( $cropped_img_path ) ) {
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
				$bdp_images      = array(
					'url'    => $cropped_img_url,
					'width'  => $width,
					'height' => $height,
				);
				return $bdp_images;
			}
			// $crop = false or no height set.
			if ( false == $crop || ! $height ) {
				// calculate the size proportionaly.
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
				// checking if the file already exists.
				if ( file_exists( $resized_img_path ) ) {
					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
					$bdp_images      = array(
						'url'    => $resized_img_url,
						'width'  => $proportional_size[0],
						'height' => $proportional_size[1],
					);
					return $bdp_images;
				}
			}
			// check if image width is smaller than set width.
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) {
				$width = $img_size[0];
			}
			// Check if GD Library installed.
			if ( ! function_exists( 'imagecreatetruecolor' ) ) {
				echo esc_html__( 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library', 'blog-designer-pro' );
				return;
			}
			// no cache files - let's finally resize it.
			$image = wp_get_image_editor( $file_path );
			if ( ! is_wp_error( $image ) ) {
				$new_file_name = $file_info['filename'] . '-' . $width . 'x' . $height . '.' . $file_info['extension'];
				$image->resize( $width, $height, $crop );
				$image->save( $file_info['dirname'] . '/' . $new_file_name );
			}
			$new_img_path = $file_info['dirname'] . '/' . $new_file_name;
			$new_img_size = getimagesize( $new_img_path );
			$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
			// resized output.
			$bdp_images = array(
				'url'    => $new_img,
				'width'  => $new_img_size[0],
				'height' => $new_img_size[1],
			);
			return $bdp_images;
		}
		// default output - without resizing.
		$bdp_images = array(
			'url'    => $image_src[0],
			'width'  => $width,
			'height' => $height,
		);
		return $bdp_images;
	}
	/**
	 * Add archive DB Structure ( Function not in use ) : Create new table for archive templates
	 *
	 * @global type $wpdb
	 * @global type $bdp_db_version
	 * @return void
	 */
	public static function add_archive_db_structure() {
		global $wpdb, $bdp_db_version;
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$installed_version = get_option( 'bdp_db_version' );
		$archive_table     = $wpdb->prefix . 'bdp_archives';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		// Create archive table.
		if ( $installed_version != $bdp_db_version ) {
			$archive_sql = "CREATE TABLE $archive_table ( id int(9) NOT NULL AUTO_INCREMENT, archive_name tinytext NOT NULL, archive_template tinytext NOT NULL, sub_categories text NOT NULL, settings text NOT NULL, UNIQUE KEY ID (ID) ) $charset_collate;";
			dbDelta( $archive_sql );
			update_option( 'bdp_db_version', $bdp_db_version );
		}
	}
	/**
	 * Add Product archive DB Structure (Function not in use ) : Create new table for archive templates
	 *
	 * @since 2.6
	 * @global type $wpdb
	 * @global type $bdp_db_version
	 * @return void
	 */
	public static function add_product_archive_db_structure() {
		global $wpdb, $bdp_db_version;
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$installed_version     = get_option( 'bdp_db_version' );
		$product_archive_table = $wpdb->prefix . 'bdp_product_archives';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		// Create archive table.
		if ( $installed_version != $bdp_db_version ) {
			$archive_sql = "CREATE TABLE $product_archive_table ( id int(9) NOT NULL AUTO_INCREMENT, product_archive_name tinytext NOT NULL, product_archive_template tinytext NOT NULL, product_sub_categories text NOT NULL, settings text NOT NULL, UNIQUE KEY ID (ID) ) $charset_collate;";
			dbDelta( $archive_sql );
			update_option( 'bdp_db_version', $bdp_db_version );
		}
	}
	/**
	 * Add Single DB Structure:Create new table for single post templates
	 *
	 * @global type $wpdb
	 * @global type $bdp_db_version
	 * @return void
	 */
	public static function add_single_db_structure() {
		global $wpdb, $bdp_db_version;
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$installed_version = get_option( 'bdp_db_version' );
		$single_table      = $wpdb->prefix . 'bdp_single_layouts';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		// Create archive table.
		if ( $installed_version != $bdp_db_version ) {
			$single_sql = "CREATE TABLE $single_table ( id int(9) NOT NULL AUTO_INCREMENT, single_name tinytext NOT NULL, single_template tinytext NOT NULL, sub_categories text NOT NULL, single_post_id text NOT NULL, settings text NOT NULL, UNIQUE KEY ID (ID) ) $charset_collate;";
			dbDelta( $single_sql );
			update_option( 'bdp_db_version', $bdp_db_version );
		}
	}
	/**
	 * Initialise an array of all recognized font faces.
	 *
	 * @return array $default
	 */
	public static function default_recognized_font_faces() {
		$default = array(
			// Serif Fonts.
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Serif Fonts', 'blog-designer-pro' ),
				'label'   => 'Georgia, serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Times New Roman", Times, serif',
			),
			// Sans-Serif Fonts.
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => 'Arial, Helvetica, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Arial Black", Gadget, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Comic Sans MS", cursive, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => 'Impact, Charcoal, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => 'Tahoma, Geneva, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => '"Trebuchet MS", Helvetica, sans-serif',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Sans-Serif Fonts', 'blog-designer-pro' ),
				'label'   => 'Verdana, Geneva, sans-serif',
			),
			// Monospace Fonts.
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Monospace Fonts', 'blog-designer-pro' ),
				'label'   => '"Courier New", Courier, monospace',
			),
			array(
				'type'    => 'websafe',
				'version' => esc_html__( 'Monospace Fonts', 'blog-designer-pro' ),
				'label'   => '"Lucida Console", Monaco, monospace',
			),
		);
		include_once 'assets/google-fonts-collection.php';
		foreach ( $google_fonts_arr as $f => $val ) {
			$default[] = array(
				'type'     => 'googlefont',
				'version'  => esc_html__( 'Google Fonts', 'blog-designer-pro' ),
				'label'    => $f,
				'variants' => $val['variants'],
				'subsets'  => $val['subsets'],
			);
		}
		return $default;
	}
	/**
	 * Check file version
	 *
	 * @since 2.0
	 * @param string $template_path path url.
	 * @return $version
	 */
	public static function check_file_version( $template_path ) {
		if ( ! file_exists( $template_path ) ) {
			return;
		}
		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $template_path, 'r' );
		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 );
		// PHP will close file handle, but we are good citizens.
		fclose( $fp );
		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
		$version   = '';
		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
			$version = _cleanup_header_comment( $match[1] );
		}
		return $version;
	}
	/**
	 * Display outdated files notice
	 *
	 * @since 2.0
	 * @return void
	 */
	public static function template_outdated_notice() {
		if ( ! isset( $_GET['page'] ) ) {
			return;
		}
		$bdp_layout = esc_html__( 'Layouts', 'blog-designer-pro' );
		if ( 'layouts' === $_GET['page'] || 'add_shortcode' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Blog Layouts', 'blog-designer-pro' );
		}
		if ( 'archive_layouts' === $_GET['page'] || 'bdp_add_archive_layout' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Post Archive Layouts', 'blog-designer-pro' );
		}
		if ( 'single_layouts' === $_GET['page'] || 'single_post' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Single Post Layouts', 'blog-designer-pro' );
		}
		if ( 'product_archive_layouts' === $_GET['page'] || 'bdp_add_product_archive_layout' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Product Archive Layouts', 'blog-designer-pro' );
		}
		if ( 'single_product_layouts' === $_GET['page'] || 'single_product' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Single Product Layouts', 'blog-designer-pro' );
		}
		if ( 'edd_archive_layouts' === $_GET['page'] || 'add_edd_archive' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Single Post Layouts', 'blog-designer-pro' );
		}
		if ( 'single_edd_layouts' === $_GET['page'] || 'single_edd_download' === $_GET['page'] ) {
			$bdp_layout = esc_html__( 'Single Post Layouts', 'blog-designer-pro' );
		}
		$active_theme      = wp_get_theme();
		$active_theme_name = $active_theme->get( 'Name' );
		echo '';
		?>
		<div class="updated notice is-dismissible bdp-admin-notice-template-outdated" data-page="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ); ?>"><p>
		<strong><?php echo esc_html__( 'Your theme', 'blog-designer-pro' ) . ' (' . esc_html( $active_theme_name ) . ') ' . esc_html__( 'not compatible or contains outdated copies of some Blog Designer template files', 'blog-designer-pro' ); ?></strong>.&nbsp;&nbsp;&nbsp;
		<p> <?php echo esc_html__( 'These files may required to design your', 'blog-designer-pro' ) . ' "' . esc_html( $bdp_layout ) . '" ' . esc_html__( 'with the current version of Blog Designer PRO. You can see which files are required or outdated from the theme', 'blog-designer-pro' ) . '. <a href="' . esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=system_status#bdp_templates_status' ) ) . '"> ' . esc_html__( 'Click here', 'blog-designer-pro' ) . '</a>'; ?></p>
		<p> 
		<?php
		esc_html_e( 'If you have an any query, feel free to create a support ticket on our', 'blog-designer-pro' );
		echo ' ';
		?>
		<a href="<?php echo esc_url( 'http://support.solwininfotech.com/' ); ?>" target="_blank"> <?php esc_html_e( 'support portal', 'blog-designer-pro' ); ?> </a> </p>
		<button class="notice-dismiss bdp-outdated-template-notice-dismiss" type="button"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'blog-designer-pro' ); ?></span></button>
		</p></div>
		<?php
	}
	/**
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @since 2.0
	 * @param string $size size.
	 * @return int $ret
	 */
	public static function let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024; /* if p. */
			case 'T':
				$ret *= 1024; /* if t. */
			case 'G':
				$ret *= 1024; /* if g. */
			case 'M':
				$ret *= 1024; /* if m. */
			case 'K':
				$ret *= 1024; /* if k. */
		}
		return $ret;
	}
	/**
	 * This function get the theme details.
	 *
	 * @since 2.0
	 * @return $acive_plugins
	 */
	public static function get_theme_info() {
		$active_theme = wp_get_theme();
		if ( is_child_theme() ) {
			$parent_theme      = wp_get_theme( $active_theme->Template );
			$parent_theme_info = array(
				'parent_name'       => $parent_theme->Name,
				'parent_version'    => $parent_theme->Version,
				'parent_author_url' => $parent_theme->{'Author URI'},
			);
		} else {
			$parent_theme_info = array(
				'parent_name'           => '',
				'parent_version'        => '',
				'parent_version_latest' => '',
				'parent_author_url'     => '',
			);
		}
		$active_theme_info = array(
			'name'           => $active_theme->Name,
			'version'        => $active_theme->Version,
			'author_url'     => esc_url_raw( $active_theme->{'Author URI'} ),
			'is_child_theme' => is_child_theme(),
		);
		return array_merge( $active_theme_info, $parent_theme_info );
	}
	/**
	 * Funtion to display color preset
	 *
	 * @since 2.0
	 * @param array $display_color display color.
	 * @return void
	 */
	public static function admin_color_preset( $display_color ) {
		$color_value = explode( ',', $display_color );
		$fcolor      = $color_value[0];
		$scolor      = $color_value[1];
		$tcolor      = $color_value[2];
		$fourthcolor = $color_value[3];
		?>
		<div class="color-palette">
		<span style="background-color:<?php echo esc_attr( $fcolor ); ?>"></span>
		<span style="background-color:<?php echo esc_attr( $scolor ); ?>"></span>
		<span style="background-color:<?php echo esc_attr( $tcolor ); ?>"></span>
		<span style="background-color:<?php echo esc_attr( $fourthcolor ); ?>"></span>
		</div>
		<?php
	}
	/**
	 * Add page state
	 *
	 * @param string $post_states states.
	 * @param array  $post post.
	 * @return string $post_states
	 */
	public static function add_post_states( $post_states, $post ) {
		$bdp_data = self::get_blog_data();
		if ( '' != $bdp_data ) {
			if ( array_key_exists( $post->ID, $bdp_data ) ) {
				$bdp_page                 = $post->ID;
				$post_states[ $bdp_page ] = $bdp_data[ $post->ID ]['name'];
			}
		}
		return $post_states;
	}
	/**
	 * Get Blog layout data
	 *
	 * @global $wpdb
	 */
	public static function get_blog_data() {
		global $wpdb;
		$bdp_data   = array();
		$shortcodes = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' );
		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				$allsettings = $shortcode->bdsettings;
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings = maybe_unserialize( $allsettings );
				}
				$bdp_page = ( isset( $bdp_settings['blog_page_display'] ) && $bdp_settings['blog_page_display'] > 0 ) ? $bdp_settings['blog_page_display'] : -1;
				$name     = ( '' != $shortcode->shortcode_name ) ? $shortcode->shortcode_name : esc_html__( 'Blog Layout', 'blog-designer-pro' );
				if ( $bdp_page > 0 ) {
					$bdp_data[ $bdp_page ] = array(
						'name' => $name,
					);
				}
			}
		}
		return $bdp_data;
	}
	/**
	 * This function get the active plugins details.
	 *
	 * @since 2.0
	 * @return $acive_plugins
	 */
	public static function get_active_plugins() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/update.php';
		if ( ! function_exists( 'get_plugin_updates' ) ) {
			return array();
		}
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
			$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
		}
		$active_plugins_data = array();
		$available_updates   = get_plugin_updates();
		foreach ( $active_plugins as $plugin ) {
			$version_latest = '';
			$data           = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			if ( isset( $available_updates[ $plugin ]->update->new_version ) ) {
				$version_latest = $available_updates[ $plugin ]->update->new_version;
			}
			$active_plugins_data[] = array(
				'plugin'            => $plugin,
				'name'              => $data['Name'],
				'version'           => $data['Version'],
				'version_latest'    => $version_latest,
				'url'               => $data['PluginURI'],
				'author_name'       => $data['AuthorName'],
				'author_url'        => esc_url_raw( $data['AuthorURI'] ),
				'network_activated' => $data['Network'],
			);
		}
		return $active_plugins_data;
	}
	/**
	 * Add Footer link
	 *
	 * @since 1.0
	 */
	public function bdp_footer() {
		$screen = get_current_screen();
		if ( 'blog-designer_page_add_shortcode' === $screen->id || 'blog-designer_page_bdp_add_product_archive_layout' === $screen->id || 'blog-designer_page_single_post' === $screen->id || 'toplevel_page_layouts' === $screen->id || 'blog-designer_page_product_archive_layouts' === $screen->id || 'blog-designer_page_archive_layouts' === $screen->id || 'blog-designer_page_bdp_add_archive_layout' === $screen->id || 'blog-designer_page_bdp_export' === $screen->id || 'blog-designer_page_bdp_getting_started' === $screen->id || 'blog-designer_page_single_product_layouts' === $screen->id || 'blog-designer_page_single_product' === $screen->id || 'blog-designer_page_edd_archive_layouts' === $screen->id || 'blog-designer_page_add_edd_archive' === $screen->id || 'blog-designer_page_single_edd_layouts' === $screen->id || 'blog-designer_page_single_edd_download' === $screen->id ) {
			add_filter( 'admin_footer_text', array( $this, 'remove_footer_admin' ) ); // change admin footer text.
		}
	}
	/**
	 * Add rating html at footer of admin
	 *
	 * @since 1.0
	 * @return html rating
	 */
	public function remove_footer_admin() {
		ob_start();
		?>
		<p id="footer-left" class="alignleft">
			<?php
			esc_html_e( 'If you like', 'blog-designer-pro' );
			echo ' ';
			?>
			<a href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/blog-designer-pro/' ); ?>" target="_blank"><strong><?php esc_html_e( 'Blog Designer PRO', 'blog-designer-pro' ); ?></strong></a>
			<?php esc_html_e( 'please leave us a', 'blog-designer-pro' ); ?>
			<a class="bdp-rating-link" data-rated="Thanks :)" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/reviews/17069678?utf8=%E2%9C%93&reviews_controls%5Bsort%5D=ratings_descending&ref=solwin' ); ?>">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</a>
			<?php esc_html_e( 'rating. A huge thank you from Solwin Infotech in advance!', 'blog-designer-pro' ); ?>
		</p>
		<?php
		return ob_get_clean();
	}
	/**
	 * Add user meta for ignore notice
	 *
	 * @global object $current_user
	 */
	public static function detail_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['bdp_notice_ignore'] ) && '0' === $_GET['bdp_notice_ignore'] ) {
			add_user_meta( $user_id, 'bdp_notice_ignore', 'true', true );
		}
	}
	/**
	 * Queue some JavaScript code to be output in the footer.
	 *
	 * @param string $code code.
	 * @return void
	 */
	public static function enqueue_js( $code ) {
		global $bdp_queued_js;
		if ( empty( $bdp_queued_js ) ) {
			$bdp_queued_js = '';
		}
		$bdp_queued_js .= "\n" . $code . "\n";
	}
	/**
	 * Output any queued javascript code in the footer.
	 *
	 * @return void
	 */
	public static function print_js() {
		global $bdp_queued_js;
		if ( ! empty( $bdp_queued_js ) ) {
			// Sanitize.
			$bdp_queued_js = wp_check_invalid_utf8( $bdp_queued_js );
			$bdp_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $bdp_queued_js );
			$bdp_queued_js = str_replace( "\r", '', $bdp_queued_js );
			$js            = "<!-- Bdp JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) { $bdp_queued_js });\n</script>\n";
			/**
			 * Bdp_queued_js filter.
			 *
			 * @param string $js JavaScript code.
			 */
			echo wp_kses( apply_filters( 'bdp_queued_js', $js ), Bdp_Admin_Functions::args_kses() );
			unset( $bdp_queued_js );
		}
	}
	/**
	 * Current page sql
	 *
	 * @return int $paged
	 */
	public static function paged() {
		$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		if ( strstr( $req_uri, 'paged' ) || strstr( $req_uri, 'page' ) ) {
			if ( isset( $_REQUEST['paged'] ) ) {
				$paged = intval( $_REQUEST['paged'] );
			} else {
				$uri = explode( '/', $req_uri );
				$uri = array_reverse( $uri );
				if ( '' == $uri[0] ) {
					$pagged_uri = $uri[1];
				} else {
					$pagged_uri = $uri[0];
				}
				if ( isset( $_GET['sortby'] ) ) {
					$pagged_uri = 1;}
				if ( in_array( 'page', $uri ) ) {
					$pagged_uri = next( $uri );
				}
				$paged = $pagged_uri;
			}
		} else {
			$paged = 1;
		}
		return $paged;
	}
	/**
	 * Get first media
	 *
	 * @param int   $post_id post id.
	 * @param array $bdp_settings settings.
	 * @return video, audio or gallery
	 */
	public static function get_first_embed_media( $post_id, $bdp_settings = '' ) {
		$post        = get_post( $post_id );
		$content     = $post->post_content;
		$audio_video = new WP_Embed();
		$content     = $audio_video->run_shortcode( $content );
		$content     = $audio_video->autoembed( $content );
		$content     = wpautop( $content );
		$embeds      = get_media_embedded_in_content( $content );
		$post_format = get_post_format( $post_id );
		if ( 'gallery' === $post_format ) {
			$gallery_images = get_post_gallery( $post_id, false );
			ob_start();
			if ( $gallery_images ) {
				if ( ! wp_script_is( 'bdp-galleryimage-script', $list = 'enqueued' ) ) {
					wp_enqueue_script( 'bdp-galleryimage-script' );
				}
				?>
				<div class="bdp-flexslider flexslider" style="margin:0">
					<ul class="bdp-slides slides">
						<?php
						if ( isset( $gallery_images['ids'] ) ) {
							$gallery_images_ids = $gallery_images['ids'];
							$gallery_images_ids = explode( ',', $gallery_images_ids );
							if ( isset( $bdp_settings['bdp_media_size'] ) && 'custom' === $bdp_settings['bdp_media_size'] ) {
								foreach ( $gallery_images_ids as $gallery_images_id ) {
									$url           = wp_get_attachment_url( $gallery_images_id );
									$width         = isset( $bdp_settings['media_custom_width'] ) ? $bdp_settings['media_custom_width'] : 560;
									$height        = isset( $bdp_settings['media_custom_height'] ) ? $bdp_settings['media_custom_height'] : 350;
									$resized_image = self::image_resize( $width, $height, $url, true, $gallery_images_id );
									echo '<li style="margin:0"><img src="' . esc_url( $resized_image['url'] ) . '" width="' . esc_attr( $resized_image['width'] ) . '" height="' . esc_attr( $resized_image['height'] ) . '" /></li>';
								}
							} elseif ( isset( $bdp_settings['bdp_media_size'] ) && 'full' !== $bdp_settings['bdp_media_size'] ) {
								$post_thumbnail = $bdp_settings['bdp_media_size'];
								foreach ( $gallery_images_ids as $gallery_images_id ) {
									echo '<li style="margin:0">';
									echo wp_get_attachment_image( $gallery_images_id, $post_thumbnail );
									echo '</li>';
								}
							} else {
								foreach ( $gallery_images['src'] as $gallery_images ) {
									echo '<li style="margin:0"><img src="' . esc_url( $gallery_images ) . '" /></li>';
								}
							}
						} else {
							foreach ( $gallery_images['src'] as $gallery_images ) {
								echo '<li style="margin:0"><img src="' . esc_url( $gallery_images ) . '" /></li>';
							}
						}
						?>
					</ul>
				</div>
				<?php
			}
			$gallery_img = ob_get_clean();
			return $gallery_img;
		} elseif ( 'video' === $post_format ) {
			$pattern = get_shortcode_regex();
			if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'video', $matches[2] ) ) {
				return do_shortcode( $matches[0][0] );
			}
			if ( ! empty( $embeds ) && isset( $embeds[0] ) ) {
				return $embeds[0];
			}
		} elseif ( 'audio' === $post_format ) {
			?>
			<script>
				jQuery(".bdp-post-image").addClass("post-audio");
			</script>
			<?php
			$pattern = get_shortcode_regex();
			if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'audio', $matches[2] ) ) {
				if ( isset( $matches[0][0] ) ) {
					return do_shortcode( $matches[0][0] );
				}
			}
			if ( preg_match( '/https:\/\/[\"soundcloud.com]+\.[a-zA-Z0-9]{2,3}(\/\S*)?/', $post->post_content, $result ) ) {
				if ( isset( $result[0] ) && wp_oembed_get( $result[0] ) ) {
					return wp_oembed_get( $result[0] );
				}
			}
			if ( preg_match_all( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $post->post_content, $matches ) ) {
				if ( $matches && isset( $matches[1] ) ) {
					$iframe_round = 0;
					foreach ( $matches[1] as $single_match ) {
						if ( strpos( $single_match, 'w.soundcloud.com/player/' ) ) {
							return $matches[0][ $iframe_round ];
						}
						++$iframe_round;
					}
				}
			}
		}
		return false;
	}
	/**
	 * Add First Letter with Custom HTML
	 *
	 * @param type $content content.
	 * @return content Add a class in first letter
	 */
	public static function add_first_letter_structure( $content ) {
		if ( preg_match( '#(>|]|^)(([A-Z]|[a-z]|[0-9]|[\p{L}])(.*\R)*(\R)*.*)#m', $content, $matches ) ) {
			$top_content              = str_replace( $matches[2], '', $content );
			$content_change           = ltrim( $matches[2] );
			$bdp_content_first_letter = mb_substr( $content_change, 0, 1 );
			if ( ' ' === mb_substr( $content_change, 1, 1 ) ) {
				$bdp_remaining_letter = ' ' . mb_substr( $content_change, 2 );
			} else {
				$bdp_remaining_letter = mb_substr( $content_change, 1 );
			}
			$spanned_first_letter = '<span class="bdp-first-letter">' . $bdp_content_first_letter . '</span>';
			$bottom_content       = $spanned_first_letter . $bdp_remaining_letter;
			return $top_content . $bottom_content;
		}
		return $content;
	}

	/**
	 *  Add social share icons
	 *
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	public static function get_social_icons( $bdp_settings ) {
		global $post;
		$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true;
		if ( $social_share ) {
			if ( ( isset( $bdp_settings['facebook_link'] ) && 1 == $bdp_settings['facebook_link'] ) ||
					( isset( $bdp_settings['twitter_link'] ) && 1 == $bdp_settings['twitter_link'] ) ||
					( isset( $bdp_settings['linkedin_link'] ) && 1 == $bdp_settings['linkedin_link'] ) ||
					( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) ||
					( isset( $bdp_settings['pinterest_link'] ) && 1 == $bdp_settings['pinterest_link'] ) ||
					( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) ||
					( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) ||
					( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) ||
					( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) ||
					( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) ||
					( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) ||
					( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) ||
					( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) ||
					( isset( $bdp_settings['whatsapp_link'] ) && 1 == $bdp_settings['whatsapp_link'] ) ) {
				if ( ! wp_script_is( 'bdp-socialShare-script', $list = 'enqueued' ) ) {
					wp_enqueue_script( 'bdp-socialShare-script' );
				}
				$social_theme = ' default_social_style_1 ';
				if ( isset( $bdp_settings['default_icon_theme'] ) && isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
					$social_theme = ' default_social_style_' . $bdp_settings['default_icon_theme'] . ' ';
				}
				$social_share_position = ( isset( $bdp_settings['social_share_position'] ) && '' != $bdp_settings['social_share_position'] ) ? $bdp_settings['social_share_position'] : '';
				$social_style          = ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) ? 'bdp-social-style-defult' : 'bdp-social-style-custom';
				?>
				<div class="bdp_social_share_postion <?php echo esc_attr( $social_share_position ) . '_position'; ?>">
					<div class="social-component
					<?php
					echo esc_attr( $social_theme ) . ' ' . esc_attr( $social_style );
					echo ' bdp_social_count_' . get_the_ID();
					if ( isset( $bdp_settings['social_style'] ) && 0 == $bdp_settings['social_style'] ) {
						if ( isset( $bdp_settings['social_icon_style'] ) && 0 == $bdp_settings['social_icon_style'] ) {
							echo ' circle';
						} elseif ( isset( $bdp_settings['social_icon_style'] ) && 1 == $bdp_settings['social_icon_style'] ) {
							echo ' square';
						}
					}
					if ( isset( $bdp_settings['social_style'] ) && 0 == $bdp_settings['social_style'] ) {
						if ( isset( $bdp_settings['social_icon_size'] ) && 0 == $bdp_settings['social_icon_size'] ) {
							echo ' large';
						} elseif ( isset( $bdp_settings['social_icon_size'] ) && 2 == $bdp_settings['social_icon_size'] ) {
							echo ' extra_small';
						}
					}
					if ( isset( $bdp_settings['social_count_position'] ) ) {
						echo ' ' . esc_attr( $bdp_settings['social_count_position'] );
					}
					?>
					">
					<?php
					if ( 1 == $bdp_settings['facebook_link'] ) {
						$facebook_token = '';
						if ( isset( $bdp_settings['facebook_token'] ) ) {
							$facebook_token = $bdp_settings['facebook_token'];
						}
						?>
						<input type="hidden" value="<?php echo esc_attr( $facebook_token ); ?>" name="fb_token" id ="fb_token">
						<?php
						if ( isset( $bdp_settings['facebook_link_with_count'] ) && 1 == $bdp_settings['facebook_link_with_count'] ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
								<div class="social-share"><a data-share="facebook" href="https://www.facebook.com/sharer/sharer.php" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-facebook-share social-share-default bdp-social-share"></a></div>
							<?php } else { ?>
								<div class="social-share"><a data-share="facebook" href="https://www.facebook.com/sharer/sharer.php" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-facebook-share facebook-share social-share-custom bdp-social-share"><i class="fab fa-facebook-f"></i></a></div>
							<?php } ?>
						<?php } else { ?>
							<div class="social-share">
								<?php
								if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
									if ( isset( $bdp_settings['social_count_position'] ) && 'top' === $bdp_settings['social_count_position'] ) {
										?>
										<div class="count c_facebook facebook-count">0</div><?php } ?>
									<a data-share="facebook" href="https://www.facebook.com/sharer/sharer.php" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-facebook-share social-share-default bdp-social-share"></a>
									<?php
								} else {
									if ( isset( $bdp_settings['social_count_position'] ) && 'top' === $bdp_settings['social_count_position'] ) {
										?>
										<div class="count c_facebook facebook-count">0</div>
										<?php
									}
									?>
									<a data-share="facebook" href="https://www.facebook.com/sharer/sharer.php" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-facebook-share facebook-share social-share-custom bdp-social-share"><i class="fab fa-facebook-f"></i></a>
									<?php
								}
								if ( ( isset( $bdp_settings['social_count_position'] ) && 'bottom' === $bdp_settings['social_count_position'] ) || ( isset( $bdp_settings['social_count_position'] ) && 'right' === $bdp_settings['social_count_position'] ) ) {
									?>
									<div class="count c_facebook facebook-count">0</div><?php } ?>
							</div>
							<?php
						}
					}
					if ( 1 == $bdp_settings['twitter_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="twitter" href="https://twitter.com/share" data-href="https://twitter.com/share" data-text="<?php echo esc_html( $post->post_title ); ?>" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-twitter-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="twitter" href="https://twitter.com/share" data-href="https://twitter.com/share" data-text="<?php echo esc_html( $post->post_title ); ?>" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-twitter-share social-share-custom bdp-social-share"><i class="fab fa-x-twitter"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( 1 == $bdp_settings['linkedin_link'] ) {
						?>
						<div class="social-share">
							<?php
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
								<a data-share="linkedin" href="https://www.linkedin.com/shareArticle" data-href="https://www.linkedin.com/shareArticle" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-linkedin-share social-share-default bdp-social-share"></a>
								<?php
							} else {
								?>
								<a data-share="linkedin" href="https://www.linkedin.com/shareArticle" data-href="https://www.linkedin.com/shareArticle" data-url="<?php echo esc_url( get_the_permalink() ); ?>" class="bdp-linkedin-share social-share-custom bdp-social-share"><i class="fab fa-linkedin-in"></i></a>
								<?php
							}
							?>
						</div>
						<?php
					}
					if ( 1 == $bdp_settings['pinterest_link'] ) {
						$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						if ( is_array( $pinterestimage ) ) {
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						} elseif ( ! is_array( $pinterestimage ) ) {
							$pinterestimage   = array();
							$pinterestimage[] = BLOGDESIGNERPRO_URL . '/public/images/No_available_image.png';
						}
						if ( isset( $bdp_settings['pinterest_link_with_count'] ) && 1 == $bdp_settings['pinterest_link_with_count'] ) {
							?>
							<div class="social-share">
								<?php
								if ( is_array( $pinterestimage ) ) {
									if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
										if ( isset( $pinterestimage[0] ) ) {
											?>
										<a data-share="pinterest" href="https://pinterest.com/pin/create/button/" data-href="https://pinterest.com/pin/create/button/" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-media="<?php echo esc_url( $pinterestimage[0] ); ?>" data-description="<?php echo esc_html( $post->post_title ); ?>" class="bdp-pinterest-share social-share-default bdp-social-share"></a>
											<?php
										}
									} elseif ( isset( $pinterestimage[0] ) ) {
										?>
										<a data-share="pinterest" href="https://pinterest.com/pin/create/button/" data-href="https://pinterest.com/pin/create/button/" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-media="<?php echo esc_url( $pinterestimage[0] ); ?>" data-description="<?php echo esc_html( $post->post_title ); ?>" class="bdp-pinterest-share social-share-custom bdp-social-share"><i class="fab fa-pinterest"></i></a>
											<?php

									}
								}
								?>
							</div>
							<?php
						} else {
							?>
							<div class="social-share">
								<?php
								if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
									if ( isset( $bdp_settings['social_count_position'] ) && 'top' === $bdp_settings['social_count_position'] ) {
										?>
										<div class="count c_pinterest pinterest-count">0</div>
										<?php
									}
									if ( isset( $pinterestimage[0] ) ) {
										?>
									<a data-share="pinterest" href="https://pinterest.com/pin/create/button/" data-href="https://pinterest.com/pin/create/button/" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-media="<?php echo esc_url( $pinterestimage[0] ); ?>" data-description="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-pinterest-share social-share-default bdp-social-share"></a>
										<?php
									}
								} else {
									if ( isset( $bdp_settings['social_count_position'] ) && 'top' === $bdp_settings['social_count_position'] ) {
										?>
										<div class="count c_pinterest pinterest-count">0</div> 
										<?php
									}
									if ( isset( $pinterestimage[0] ) ) {
										?>
									<a data-share="pinterest" href="https://pinterest.com/pin/create/button/" data-href="https://pinterest.com/pin/create/button/" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-media="<?php echo esc_url( $pinterestimage[0] ); ?>" data-description="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-pinterest-share social-share-custom bdp-social-share"><i class="fab fa-pinterest"></i></a>
										<?php
									}
								}
								if ( ( isset( $bdp_settings['social_count_position'] ) && 'bottom' === $bdp_settings['social_count_position'] ) || ( isset( $bdp_settings['social_count_position'] ) && 'right' === $bdp_settings['social_count_position'] ) ) {
									?>
									<div class="count c_pinterest pinterest-count">0</div>
								<?php } ?>
							</div>
							<?php
						}
					}
					if ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="skype" href="https://web.skype.com/share" data-href="https://web.skype.com/share" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-text="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-skype-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="skype" href="https://web.skype.com/share" data-href="https://web.skype.com/share" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-text="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-telegram-share social-share-custom bdp-social-share"><i class="fab fa-skype"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="telegram" href="https://telegram.me/share/url"  data-href="https://telegram.me/share/url" data-url="<?php echo esc_url( urldecode( get_the_permalink() ) ); ?>" data-text="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-telegram-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="telegram" href="https://telegram.me/share/url" data-href="https://telegram.me/share/url" data-url="<?php echo esc_url( urldecode( get_the_permalink() ) ); ?>" data-text="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-telegram-share social-share-custom bdp-social-share"><i class="fab fa-telegram-plane"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="pocket" href="https://getpocket.com/save" data-href="https://getpocket.com/save" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-pocket-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="pocket" href="https://getpocket.com/save" data-href="https://getpocket.com/save" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-pocket-share social-share-custom bdp-social-share"><i class="fab fa-get-pocket"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="reddit" href="http://www.reddit.com/submit" data-href="http://www.reddit.com/submit" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-reddit-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="reddit" href="http://www.reddit.com/submit" data-href="http://www.reddit.com/submit" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-reddit-share social-share-custom bdp-social-share"><i class="fab fa-reddit-alien"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="digg" href="http://digg.com/submit" data-href="http://digg.com/submit" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-digg-share social-share-default bdp-social-share"></a>
							<?php } else { ?>
								<a data-share="digg" href="http://digg.com/submit" data-href="http://digg.com/submit" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-digg-share social-share-custom bdp-social-share"><i class="fab fa-digg"></i></a>
								<?php
							}
							echo '</div>';
					}
					if ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="tumblr" href="http://tumblr.com/widgets/share/tool" data-href="http://tumblr.com/widgets/share/tool" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-tumblr-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="tumblr" href="http://tumblr.com/widgets/share/tool" data-href="http://tumblr.com/widgets/share/tool" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" class="bdp-tumblr-share social-share-custom bdp-social-share"><i class="fab fa-tumblr"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) {
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a data-share="wordpress" href="http://wordpress.com/press-this.php" data-href="http://wordpress.com/press-this.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" data-image="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" class="bdp-wordpress-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a data-share="wordpress" href="http://wordpress.com/press-this.php" data-href="http://wordpress.com/press-this.php" data-url="<?php echo esc_url( get_the_permalink() ); ?>" data-title="<?php echo esc_attr( $post->post_title ); ?>" data-image="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>" class="bdp-wordpress-share social-share-custom bdp-social-share"><i class="fab fa-wordpress"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) {
						$shortcode_id = Bdp_Front_Functions::$shortcode_id;
						if ( is_array( $shortcode_id ) && ! empty( $shortcode_id ) ) {
							$shortcode_id = $shortcode_id[0];
						}
						echo '<div class="social-share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>" data-href="<?php echo esc_url( get_the_permalink() ); ?>" data-shortcode-id="<?php echo ( ! empty( $shortcode_id ) ) ? esc_attr( $shortcode_id ) : ''; ?>" data-id="<?php echo esc_attr( get_the_ID() ); ?>" href="javascript:void(0)" class="bdp-email-share social-share-default bdp-social-share"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>" data-href="<?php echo esc_url( get_the_permalink() ); ?>" data-shortcode-id="<?php echo ( ! empty( $shortcode_id ) ) ? esc_attr( $shortcode_id ) : ''; ?>" data-id="<?php echo esc_attr( get_the_ID() ); ?>" href="javascript:void(0)" class="bdp-email-share social-share-custom bdp-social-share"><i class="far fa-envelope-open"></i></a>
							<?php
						}
						echo '</div>';
					}
					if ( isset( $bdp_settings['whatsapp_link'] ) && 1 == $bdp_settings['whatsapp_link'] ) {
						echo '<div class="social-share whatsapp_share">';
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
								<a href="<?php echo 'https://wa.me/send?text=' . esc_html( $post->post_title ) . ' ' . esc_url( get_the_permalink() ) . '&url=' . esc_url( rawurlencode( get_the_permalink() ) ); ?>" data-href="<?php echo 'https://wa.me/send?text=' . esc_html( $post->post_title ) . ' ' . esc_url( get_the_permalink() ) . '&url=' . esc_url( rawurlencode( get_the_permalink() ) ); ?>" target="_blank" class="bdp-whatsapp-share social-share-default"></a>
							<?php } else { ?>
								<a href="<?php echo 'https://wa.me/send?text=' . esc_html( $post->post_title ) . ' ' . esc_url( get_the_permalink() ) . '&url=' . esc_url( rawurlencode( get_the_permalink() ) ); ?>" data-href="<?php echo 'https://wa.me/send?text=' . esc_html( $post->post_title ) . ' ' . esc_url( get_the_permalink() ) . '&url=' . esc_url( rawurlencode( get_the_permalink() ) ); ?>" data-action="share/whatsapp/share" target="_blank" class="bdp-whatsapp-share social-share-custom"><i class="fab fa-whatsapp"></i></a>								
								<?php
							}
							echo '</div>';
					}
					if ( ( ! isset( $bdp_settings['pinterest_link_with_count'] ) ) || ( ! isset( $bdp_settings['facebook_link_with_count'] ) ) ) {
						if ( '' != get_the_title() ) {
							?>
							<script type="text/javascript">
								jQuery(document).ready(function(){(function($){
									$('.<?php echo 'bdp_social_count_' . esc_attr( get_the_ID() ); ?> .count').ShareCounter({url: '<?php echo esc_url( get_the_permalink() ); ?>'});
								}(jQuery))});
							</script>
							<?php
						}
					}
					?>
					</div>
				</div>
				<?php
			}
		}
	}
	/**
	 * Add pinterest button on image
	 *
	 * @param int $bdp_post_id post id.
	 * @return html pinterest image
	 */
	public static function pinterest( $bdp_post_id ) {
		global $post;
		ob_start();
		?>
		<div class="bdp-pinterest-share-image">
			<?php
			$img_url = wp_get_attachment_url( get_post_thumbnail_id( $bdp_post_id ) );
			apply_filters( 'bdp_pinterest_img_url', $img_url, $bdp_post_id );
			?>
			<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_url( get_permalink( $bdp_post_id ) ) . '&media=' . esc_attr( $img_url ) . '&description =' . esc_attr( $post->post_title ); ?>"></a>
		</div>
		<?php
		$pintrest = ob_get_clean();
		return $pintrest;
	}
	/**
	 * Get loader image
	 *
	 * @since 2.0
	 * @param array $bdp_settings settings.
	 * @global $bdp_settings
	 */
	public static function get_loader( $bdp_settings ) {
		$loader  = '';
		$loaders = array(
			'circularG'               => '<div class="bdp-circularG-wrapper"><div class="bdp-circularG bdp-circularG_1"></div><div class="bdp-circularG bdp-circularG_2"></div><div class="bdp-circularG bdp-circularG_3"></div><div class="bdp-circularG bdp-circularG_4"></div><div class="bdp-circularG bdp-circularG_5"></div><div class="bdp-circularG bdp-circularG_6"></div><div class="bdp-circularG bdp-circularG_7"></div><div class="bdp-circularG bdp-circularG_8"></div></div>',
			'floatingCirclesG'        => '<div class="bdp-floatingCirclesG"><div class="bdp-f_circleG bdp-frotateG_01"></div><div class="bdp-f_circleG bdp-frotateG_02"></div><div class="bdp-f_circleG bdp-frotateG_03"></div><div class="bdp-f_circleG bdp-frotateG_04"></div><div class="bdp-f_circleG bdp-frotateG_05"></div><div class="bdp-f_circleG bdp-frotateG_06"></div><div class="bdp-frotateG_07 bdp-f_circleG"></div><div class="bdp-frotateG_08 bdp-f_circleG"></div></div>',
			'spinloader'              => '<div class="bdp-spinloader"></div>',
			'doublecircle'            => '<div class="bdp-doublec-container"><ul class="bdp-doublec-flex-container"><li><span class="bdp-doublec-loading"></span></li></ul></div>',
			'wBall'                   => '<div class="bdp-windows8"><div class="bdp-wBall bdp-wBall_1"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_2"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_3"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall bdp-wBall_4"><div class="bdp-wInnerBall"></div></div><div class="bdp-wBall_5 bdp-wBall"><div class="bdp-wInnerBall"></div></div></div>',
			'cssanim'                 => '<div class="bdp-cssload-aim"></div>',
			'thecube'                 => '<div class="bdp-cssload-thecube"><div class="bdp-cssload-cube bdp-cssload-c1"></div><div class="bdp-cssload-cube bdp-cssload-c2"></div><div class="bdp-cssload-cube bdp-cssload-c4"></div><div class="bdp-cssload-cube bdp-cssload-c3"></div></div>',
			'ballloader'              => '<div class="bdp-ballloader"><div class="bdp-loader-inner bdp-ball-grid-pulse"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
			'squareloader'            => '<div class="bdp-squareloader"><div class="bdp-square"></div><div class="bdp-square"></div><div class="bdp-square last"></div><div class="bdp-square clear"></div><div class="bdp-square"></div><div class="bdp-square last"></div><div class="bdp-square clear"></div><div class="bdp-square"></div><div class="bdp-square last"></div></div>',
			'loadFacebookG'           => '<div class="bdp-loadFacebookG"><div class="bdp-blockG_1 bdp-facebook_blockG"></div><div class="bdp-blockG_2 bdp-facebook_blockG"></div><div class="bdp-facebook_blockG bdp-blockG_3"></div></div>',
			'floatBarsG'              => '<div class="bdp-floatBarsG-wrapper"><div class="bdp-floatBarsG_1 bdp-floatBarsG"></div><div class="bdp-floatBarsG_2 bdp-floatBarsG"></div><div class="bdp-floatBarsG_3 bdp-floatBarsG"></div><div class="bdp-floatBarsG_4 bdp-floatBarsG"></div><div class="bdp-floatBarsG_5 bdp-floatBarsG"></div><div class="bdp-floatBarsG_6 bdp-floatBarsG"></div><div class="bdp-floatBarsG_7 bdp-floatBarsG"></div><div class="bdp-floatBarsG_8 bdp-floatBarsG"></div></div>',
			'movingBallG'             => '<div class="bdp-movingBallG-wrapper"><div class="bdp-movingBallLineG"></div><div class="bdp-movingBallG_1 bdp-movingBallG"></div></div>',
			'ballsWaveG'              => '<div class="bdp-ballsWaveG-wrapper"><div class="bdp-ballsWaveG_1 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_2 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_3 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_4 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_5 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_6 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_7 bdp-ballsWaveG"></div><div class="bdp-ballsWaveG_8 bdp-ballsWaveG"></div></div>',
			'fountainG'               => '<div class="fountainG-wrapper"><div class="bdp-fountainG_1 bdp-fountainG"></div><div class="bdp-fountainG_2 bdp-fountainG"></div><div class="bdp-fountainG_3 bdp-fountainG"></div><div class="bdp-fountainG_4 bdp-fountainG"></div><div class="bdp-fountainG_5 bdp-fountainG"></div><div class="bdp-fountainG_6 bdp-fountainG"></div><div class="bdp-fountainG_7 bdp-fountainG"></div><div class="bdp-fountainG_8 bdp-fountainG"></div></div>',
			'audio_wave'              => '<div class="bdp-audio_wave"><span></span><span></span><span></span><span></span><span></span></div>',
			'warningGradientBarLineG' => '<div class="bdp-warningGradientOuterBarG"><div class="bdp-warningGradientFrontBarG bdp-warningGradientAnimationG"><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div><div class="bdp-warningGradientBarLineG"></div></div></div>',
			'floatingBarsG'           => '<div class="bdp-floatingBarsG"><div class="bdp-rotateG_01 bdp-blockG"></div><div class="bdp-rotateG_02 bdp-blockG"></div><div class="bdp-rotateG_03 bdp-blockG"></div><div class="bdp-rotateG_04 bdp-blockG"></div><div class="bdp-rotateG_05 bdp-blockG"></div><div class="bdp-rotateG_06 bdp-blockG"></div><div class="bdp-rotateG_07 bdp-blockG"></div><div class="bdp-rotateG_08 bdp-blockG"></div></div>',
			'rotatecircle'            => '<div class="bdp-cssload-loader"><div class="bdp-cssload-inner bdp-cssload-one"></div><div class="bdp-cssload-inner bdp-cssload-two"></div><div class="bdp-cssload-inner bdp-cssload-three"></div></div>',
			'overlay-loader'          => '<div class="bdp-overlay-loader"><div class="bdp-loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
			'circlewave'              => '<div class="bdp-circlewave"></div>',
			'cssload-ball'            => '<div class="bdp-cssload-ball"></div>',
			'cssheart'                => '<div class="bdp-cssload-main"><div class="bdp-cssload-heart"><span class="bdp-cssload-heartL"></span><span class="bdp-cssload-heartR"></span><span class="bdp-cssload-square"></span></div><div class="bdp-cssload-shadow"></div></div>',
			'spinload'                => '<div class="bdp-spinload-loading"><i></i><i></i><i></i></div>',
			'bigball'                 => '<div class="bdp-bigball-container"><div class="bdp-bigball-loading"><i></i><i></i><i></i></div></div>',
			'bubblec'                 => '<div class="bdp-bubble-container"><div class="bdp-bubble-loading"><i></i><i></i></div></div>',
			'csball'                  => '<div class="bdp-csball-container"><div class="bdp-csball-loading"><i></i><i></i><i></i><i></i></div></div>',
			'ccball'                  => '<div class="bdp-ccball-container"><div class="bdp-ccball-loading"><i></i><i></i></div></div>',
			'circulardot'             => '<div class="bdp-cssload-wrap"><div class="bdp-circulardot-container"><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span><span class="bdp-cssload-dots"></span></div></div>',
		);
		if ( isset( $bdp_settings['loader_type'] ) ) {
			if ( 1 == $bdp_settings['loader_type'] ) {
				$loading = ( isset( $bdp_settings['bdp_loader_image_src'] ) && '' != $bdp_settings['bdp_loader_image_src'] ) ? $bdp_settings['bdp_loader_image_src'] : BLOGDESIGNERPRO_URL . '/public/images/loading.gif';
				$loader  = '<img src="' . $loading . '" alt="' . esc_attr__( 'Loading Image', 'blog-designer-pro' ) . '" style="display: none" class="loading-image" />';
			} else {
				$loader_style_hidden = isset( $bdp_settings['loader_style_hidden'] ) ? $bdp_settings['loader_style_hidden'] : 'circularG';
				$loading             = $loaders[ $loader_style_hidden ];
				$loader              = '<div style="display: none" class="loading-image" >' . $loading . '</div>';
			}
		} else {
			$loader = '<img src="' . BLOGDESIGNERPRO_URL . '/public/images/loading.gif" alt="' . esc_attr__( 'Loading Image', 'blog-designer-pro' ) . '" style="display: none" class="loading-image" />';
		}
		return $loader;
	}

	/**
	 * Function to replace old 3 templates
	 *
	 * @param string $bdp_theme theme.
	 * @return $bdp_theme
	 */
	public static function from_lite_to_pro( $bdp_theme = '' ) {
		if ( 'classical' === $bdp_theme ) {
			$bdp_theme = 'nicy';
		} elseif ( 'lightbreeze' === $bdp_theme ) {
			$bdp_theme = 'sharpen';
		} elseif ( 'spektrum' === $bdp_theme ) {
			$bdp_theme = 'hub';
		}
		return $bdp_theme;
	}
	/**
	 * Remove read more link from content
	 *
	 * @since 2.2
	 * @param string $link link.
	 * @return string $link
	 */
	public static function remove_more_link( $link ) {
		$link = '';
		return $link;
	}

	/**
	 * Add notice at admin side
	 *
	 * @global object $current_user
	 */
	public static function admin_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		/* Check that the user hasn't already clicked to ignore the message */

		if ( ! get_user_meta( $user_id, 'bdp_notice_ignore' ) && current_user_can( 'manage_options' ) ) {
			echo '<div class="updated notice is-dismissible bdp-admin-notice-pro-layouts"><p>';
			?>
			<strong><?php esc_html_e( 'Blog Designer PRO is a best blog layout builders plugin of your blog, archive and single post pages. 45+ pre-defined templates will beautify your blog section in just 5 minutes!', 'blog-designer-pro' ); ?></strong>
			<p> <a href="<?php echo esc_url( 'https://wpblogdesigner.net/' ); ?>" target="_blank"><strong><?php esc_html_e( 'Live Preview ', 'blog-designer-pro' ); ?></strong> </a> | <a href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ); ?>" target="_blank"><strong><?php esc_html_e( 'See Details ', 'blog-designer-pro' ); ?></strong></a> | <a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer-pro/#quick_guide' ); ?>" target="_blank"><strong><?php esc_html_e( 'Detailed Documentation', 'blog-designer-pro' ); ?></strong></a></p>
			<button class="notice-dismiss" type="button">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'blog-designer-pro' ); ?></span>
			</button>
			<?php
			echo '</p></div>';
		}
	}
	/**
	 * Utility to retrieve IP address
	 *
	 * @return string $ip
	 */
	public static function get_ip() {
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = isset( $_SERVER['HTTP_CLIENT_IP'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) : '';
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) : '';
		} else {
			$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
		}
		$ip = filter_var( $ip, FILTER_VALIDATE_IP );
		$ip = ( false == $ip ) ? '0.0.0.0' : $ip;
		return $ip;
	}
}
new Bdp_Utility();
