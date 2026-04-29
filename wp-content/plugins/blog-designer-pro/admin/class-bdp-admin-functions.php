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

define( BLOGDESIGNERPRO_TEXTDOMAIN, 'blog-designer-pro' );
define( BLOGDESIGNERPRO_DIR, plugin_dir_path( __FILE__ ) );
define( BLOGDESIGNERPRO_URL, plugins_url() . '/blog-designer-pro' );

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Admin_Functions
 * @version 1.0.0
 */
class Bdp_Admin_Functions {
	/**
	 * Errors
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $bdp_errors bdp_errors.
	 */
	public $bdp_errors;
	/**
	 * Settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $bdp_settings bdp_settings.
	 */
	public $bdp_settings;
	/**
	 * Table Name
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $bdp_table_name bdp_table_name.
	 */
	public $bdp_table_name;
	/**
	 * Product Archive Table
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $product_archive_table product_archive_table.
	 */
	public $product_archive_table;
	/**
	 * BDP Errors
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $download_archive_table download_archive_table.
	 */
	public $download_archive_table;
	/**
	 * Archive Table
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $archive_table archive_table.
	 */
	public $archive_table;
	/**
	 * Success
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array $bdp_success bdp_success.
	 */
	public $bdp_success;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @var    array $bdp_version bdp_success.
	 */
	public $bdp_version;
	/**
	 * Plugin version
	 *
	 * @since 1.0.0
	 * @var    array $bdp_version bdp_success.
	 */
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb, $bdp_version, $bdp_table_name, $archive_table, $bdp_errors, $import_success, $font_success, $template_base, $pagenow, $bdp_current_version, $bdp_old_version, $product_archive_table, $download_archive_table;
		$bdp_version     = '3.4.8';
		$bdp_admin_page  = false;
		$bdp_admin_pages = array( 'layouts', 'archive_layouts', 'add_shortcode', 'single_post', 'bdp_add_archive_layout', 'bdp_add_product_archive_layout', 'single_product', 'bdp_export', 'single_layouts', 'bdp_getting_started', 'designer_welcome_page', 'product_archive_layouts', 'single_product_layouts', 'single_edd_download', 'single_edd_layouts', 'edd_archive_layouts', 'add_edd_archive' );
		if ( isset( $_GET['page'] ) && ( in_array( $_GET['page'], $bdp_admin_pages ) ) ) {
			$bdp_admin_page = true;
		}

		// action for single post setting.
		add_action( 'add_meta_boxes', array( &$this, 'bdp_single_add_post_meta_box' ) );
		add_action( 'save_post', array( &$this, 'bdp_save_single_meta_data' ), 10, 3 );
		// actions for admin side.
		add_action( 'admin_menu', array( &$this, 'bdp_add_menu' ) );
		add_action( 'admin_init', array( &$this, 'bdp_default_settings_function' ), 1 );
		add_action( 'admin_init', array( &$this, 'bdp_table_status' ), 3 );
		/** Save Blog and single Layout */
		add_action( 'admin_init', array( &$this, 'bdp_save_admin_template' ), 4 );
		/** Save Archive Layout */
		add_action( 'admin_init', array( &$this, 'bdp_save_admin_archive_template' ), 5 );
		/** Single delete Layout */
		add_action( 'admin_init', array( &$this, 'bdp_delete_admin_template' ), 6 );
		/** Multiple delete Layout */
		add_action( 'admin_init', array( &$this, 'bdp_multiple_delete_admin_template' ), 7 );
		/** Export Layout */
		add_action( 'admin_init', array( &$this, 'bdp_multiple_export_admin_template' ), 8 );
		add_action( 'admin_init', array( &$this, 'bdp_admin_stylesheet_js' ), 9 );
		/** Duplicate Layout */
		add_action( 'admin_init', array( &$this, 'bdp_duplicate_layout' ), 10 );
		add_action( 'admin_init', array( &$this, 'bdp_upload_import_file' ), 11 );
		add_action( 'admin_enqueue_scripts', array( &$this, 'bdp_admin_scripts' ), 3 );
		add_action( 'admin_enqueue_scripts', array( &$this, 'bdp_admin_front_scripts' ), 4 );
		add_action( 'admin_footer', array( &$this, 'bdp_admin_footer' ), 2 );
		add_action( 'admin_head', array( &$this, 'bdp_admin_notice_dismiss' ), 15 );
		add_action( 'wp_ajax_get_unique_posts_list', 'bdp_get_unique_posts_list' );
		add_action( 'admin_head', array( &$this, 'bdp_plugin_path_js' ), 10 );
		add_filter( 'get_avatar', array( &$this, 'bdp_replace_content' ) );
		if ( isset( $pagenow ) && 'plugins.php' === $pagenow ) {
			add_action( 'admin_notices', array( &$this, 'bdp_insert_plugin_row' ) );
		}
		add_action( 'admin_notices', array( &$this, 'bdp_single_template_run_the_updater' ) );
		$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
		if ( 1 == $bdp_template_name_changed && $bdp_admin_page ) {
			$count_layout           = 0;
			$count_archive          = 0;
			$count_single           = 0;
			$count_archive_product  = 0;
			$count_archive_download = 0;
			$count_single_product   = 0;
			$count_single_download  = 0;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "blog_designer_pro_shortcodes'" ) == $wpdb->prefix . 'blog_designer_pro_shortcodes' && $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_archives'" ) == $wpdb->prefix . 'bdp_archives' && $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_single_layouts'" ) == $wpdb->prefix . 'bdp_single_layouts' ) {
				$count_layout  = $wpdb->get_var( 'SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes' );
				$count_archive = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_archives' );
				$count_single  = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_single_layouts' );
			}
			if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_product_archives'" ) == $wpdb->prefix . 'bdp_product_archives' && $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_single_product'" ) == $wpdb->prefix . 'bdp_single_product' ) {
				$count_archive_product = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_product_archives' );
				$count_single_product  = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_single_product' );
			}
			if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_single_ed_download'" ) == $wpdb->prefix . 'bdp_single_ed_download' && $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_edd_archives'" ) == $wpdb->prefix . 'bdp_edd_archives' ) {
				$count_single_download  = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_single_ed_download' );
				$count_archive_download = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_edd_archives' );
			}
			if ( $count_layout > 0 || $count_archive > 0 || $count_single > 0 || $count_archive_product > 0 || $count_single_product > 0 || $count_single_download > 0 || $count_archive_download > 0 ) {
				add_action( 'admin_notices', array( &$this, 'bdp_template_name_changed_updater' ) );
			} else {
				update_option( 'bdp_template_name_changed', 0 );
			}
		}

		$bdp_multi_author_selection = get_option( 'bdp_multi_author_selection', 1 );
		if ( 1 == $bdp_multi_author_selection ) {
			if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->prefix . "bdp_archives'" ) == $wpdb->prefix . 'bdp_archives' ) {
				$count_author_template = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "author_template"' );
				if ( $count_author_template <= 0 ) {
					update_option( 'bdp_multi_author_selection', 0 );
				}
			}
		}

		// filter for admin side.
		add_filter( 'media_buttons', array( &$this, 'bdp_insert_button' ) );
		if ( isset( $_GET['page'] ) && ( 'layouts' === $_GET['page'] || 'archive_layouts' === $_GET['page'] ) ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option' ), 10, 3 );
		}
		if ( isset( $_GET['page'] ) && 'single_layouts' === $_GET['page'] ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option_single' ), 10, 3 );
		}
		if ( isset( $_GET['page'] ) && 'archive_product_layouts' === $_GET['page'] ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option_archive_product' ), 10, 3 );
		}
		if ( isset( $_GET['page'] ) && 'edd_archive_layouts' === $_GET['page'] ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option_archive_download' ), 10, 3 );
		}
		if ( isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option_single_download' ), 10, 3 );
		}
		if ( isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] ) {
			add_filter( 'set-screen-option', array( &$this, 'bdp_set_screen_option_single_product' ), 10, 3 );
		}
		$bdp_table_name         = $wpdb->prefix . 'blog_designer_pro_shortcodes';
		$archive_table          = $wpdb->prefix . 'bdp_archives';
		$product_archive_table  = $wpdb->prefix . 'bdp_product_archives';
		$download_archive_table = $wpdb->prefix . 'bdp_edd_archives';
		$single_product_table   = $wpdb->prefix . 'bdp_single_product';
		$single_download_table  = $wpdb->prefix . 'bdp_single_ed_download';
	}

	/**
	 * Set style path, home page path and plugin path for js use
	 */
	public function bdp_plugin_path_js() {
		?>
		<script type="text/javascript">
			var plugin_path = '<?php echo esc_attr( BLOGDESIGNERPRO_URL ); ?>';
			var style_path = '<?php echo esc_attr( bloginfo( 'stylesheet_url' ) ); ?>';
			var home_path = '<?php echo esc_attr( get_home_url() ); ?>';
		</script>
		<?php
	}

	/**
	 * Run the updater for single post template
	 */
	public function bdp_single_template_run_the_updater() {
		if ( get_option( 'bdp_single_template' ) ) {
			$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			?>
			<div class="updated">
				<p>
					<strong>
						<?php esc_html_e( 'Blog Designer PRO Data Update', 'blog-designer-pro' ); ?>
					</strong> &#8211; <?php esc_html_e( 'We need to update your single post design data according to the latest version', 'blog-designer-pro' ); ?>.
				</p>
				<p class="submit">
					<a href="<?php echo esc_url( add_query_arg( 'do_update_bdp_single_template', 'do', $req_uri ) ); ?>" class="bdp-update-now button-primary">
						<?php esc_html_e( 'Run the updater', 'blog-designer-pro' ); ?>
					</a>
				</p>
			</div>
			<script type="text/javascript">
				jQuery('.bdp-update-now').on('click','click', function () {
					return window.confirm('<?php echo esc_js( esc_html__( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'blog-designer-pro' ) ); ?>');
				});
			</script>
			<?php
		}
	}

	/**
	 * Insert rows after plugin
	 */
	public function bdp_insert_plugin_row() {
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_id => $plugin ) {
			$slug = dirname( $plugin_id );
			if ( empty( $slug ) ) {
				continue;
			}
			if ( 'blog-designer-pro' !== $slug ) {
				continue;
			}
			// check version, latest updates and if registered or not.
			$bdp_wp_auto_update = new Bdp_Wp_Auto_Update();
			$bdp_latestv        = $bdp_wp_auto_update->get_remote_version();
			$bdp_checkversion   = $bdp_wp_auto_update->get_remote_license();
			if ( 'correct' !== $bdp_checkversion ) { // activate for updates and support.
				add_action( 'after_plugin_row_' . $plugin_id, array( &$this, 'bdp_purchase_notice' ), 10, 3 );
			}
			if ( version_compare( $bdp_latestv, $plugin['Version'], '>' ) && 'correct' !== $bdp_checkversion ) {
				add_action( 'after_plugin_row_' . $plugin_id, array( &$this, 'bdp_update_notice' ), 10, 3 );
			}
			if ( version_compare( 2.8, $plugin['Version'], '>' ) ) {
				add_action( 'after_plugin_row_' . $plugin_id, array( &$this, 'bdp_before_update_notice' ), 10, 3 );
			}
		}
	}

	/** After plugin row name display message */
	public function bdp_purchase_notice() {
		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
		?>
		<tr class="plugin-update-tr">
			<td colspan="<?php echo esc_attr( $wp_list_table->get_column_count() ); ?>" class="plugin-update colspanchange">
				<div class="update-message">
					<?php echo esc_html__( 'Hola! Would you like to receive automatic updates and unlock premium support? Please', 'blog-designer-pro' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=bdp_getting_started&tab=register_product' ) ) . '">' . esc_html__( 'activate', 'blog-designer-pro' ) . '</a> ' . esc_html__( 'your copy of', 'blog-designer-pro' ) . ' <b>' . esc_html__( 'Blog Designer Pro', 'blog-designer-pro' ) . '</b>'; ?>
				</div>
			</td>
		</tr>
		<?php
	}

	/** Display 'plugin new version update' message after plugin row */
	public function bdp_update_notice() {
		$wp_list_table      = _get_list_table( 'WP_Plugins_List_Table' );
		$bdp_wp_auto_update = new Bdp_Wp_Auto_Update();
		$bdp_latestv        = $bdp_wp_auto_update->get_remote_version();
		?>
		<tr class="plugin-update-tr">
			<td colspan="<?php echo esc_attr( $wp_list_table->get_column_count() ); ?>" class="plugin-update colspanchange">
				<div class="update-message">
					<p>
					<?php
					esc_html_e( 'A new version', 'blog-designer-pro' );
					echo ' ' . esc_html( $bdp_latestv ) . ' ';
					esc_html_e( 'of Blog Designer Pro is available', 'blog-designer-pro' );
					?>
					.</p>
				</div>
			</td>
		</tr>
		<?php
	}

	/** Display 'backup' message after plugin row */
	public function bdp_before_update_notice() {
		$bdp_wp_auto_update = new Bdp_Wp_Auto_Update();
		$bdp_get_notice     = $bdp_wp_auto_update->get_remote_notice();
		if ( '' != $bdp_get_notice ) {
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
			?>
			<tr class="plugin-update-tr">
				<td colspan="<?php echo esc_attr( $wp_list_table->get_column_count() ); ?>" class="plugin-update colspanchange">
					<div class="update-message">
						<p>
						<?php
						echo esc_html( $bdp_get_notice );
						?>
						</p>
					</div>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Add menu at admin panel
	 *
	 * @global string $bdp_screen_option_page
	 * @global string $bdp_screen_option_archive_page
	 * @global string $bdp_screen_edd_archive
	 * @global string $bdp_single_screen
	 * @global string $bdp_screen_product_archive
	 * @global string $bdp_screen_single_product
	 * @global string $bdp_screen_single_edd
	 * @return void
	 */
	public function bdp_add_menu() {
		global $bdp_screen_option_page, $bdp_screen_option_archive_page, $bdp_single_screen, $bdp_screen_product_archive, $bdp_screen_single_product, $bdp_screen_single_edd, $bdp_screen_edd_archive;
		$manage_blog_designs    = $this->bdp_manage_blog_design_pro();
		$bdp_screen_option_page = add_menu_page( esc_html__( 'Blog Designer', 'blog-designer-pro' ), esc_html__( 'Blog Designer', 'blog-designer-pro' ), $manage_blog_designs, 'layouts', array( $this, 'bdp_display_shortcode_list' ), BLOGDESIGNERPRO_URL . '/public/images/blog-designer-pro.png' );
		add_action( "load-$bdp_screen_option_page", array( $this, 'bdp_screen_options' ) );
		add_submenu_page( 'layouts', esc_html__( 'Blog Layouts', 'blog-designer-pro' ), esc_html__( 'Blog Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'layouts', array( $this, 'bdp_display_shortcode_list' ) );
		add_submenu_page( '', esc_html__( 'Blog Layout Settings', 'blog-designer-pro' ), esc_html__( 'Add Blog Layout', 'blog-designer-pro' ), $manage_blog_designs, 'add_shortcode', array( $this, 'bdp_display_shortcode_edit_list' ) );
		$bdp_screen_option_archive_page = add_submenu_page( 'layouts', esc_html__( 'Archive Layouts', 'blog-designer-pro' ), esc_html__( 'Archive Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'archive_layouts', array( $this, 'bdp_display_archive_list' ) );
		add_action( "load-$bdp_screen_option_archive_page", array( $this, 'bdp_screen_options_archive' ) );
		add_submenu_page( '', esc_html__( 'Archive Settings', 'blog-designer-pro' ), esc_html__( 'Add Archive Layout', 'blog-designer-pro' ), $manage_blog_designs, 'bdp_add_archive_layout', array( $this, 'bdp_display_archive_edit_list' ) );
		$bdp_single_screen = add_submenu_page( 'layouts', esc_html__( 'Single Layouts', 'blog-designer-pro' ), esc_html__( 'Single Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'single_layouts', array( $this, 'bdp_display_single_list' ) );
		add_action( "load-$bdp_single_screen", array( $this, 'bdp_screen_options_single' ) );
		add_submenu_page( '', esc_html__( 'Single Post Settings', 'blog-designer-pro' ), esc_html__( 'Add Single Layout', 'blog-designer-pro' ), $manage_blog_designs, 'single_post', array( $this, 'bdp_display_post_edit_list' ) );
		if ( Bdp_Woocommerce::is_woocommerce_plugin() ) {
			$bdp_screen_product_archive = add_submenu_page( 'layouts', esc_html__( 'Product Archive Layouts', 'blog-designer-pro' ), esc_html__( 'Product Archive Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'product_archive_layouts', array( $this, 'bdp_display_product_archive_list' ) );
			add_action( "load-$bdp_screen_product_archive", array( $this, 'bdp_screen_options_product_archive' ) );
			add_submenu_page( '', esc_html__( 'Product Archive Settings', 'blog-designer-pro' ), esc_html__( 'Add Product Archive Layout', 'blog-designer-pro' ), $manage_blog_designs, 'bdp_add_product_archive_layout', array( $this, 'bdp_display_product_archive_edit_list' ) );
			$bdp_screen_single_product = add_submenu_page( 'layouts', esc_html__( 'Single Product Layouts', 'blog-designer-pro' ), esc_html__( 'Single Product Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'single_product_layouts', array( $this, 'bdp_display_single_product_list' ) );
			add_action( "load-$bdp_screen_single_product", array( $this, 'bdp_screen_options_single_product' ) );
			add_submenu_page( '', esc_html__( 'Single Product Settings', 'blog-designer-pro' ), esc_html__( 'Add Single Product Layout', 'blog-designer-pro' ), $manage_blog_designs, 'single_product', array( $this, 'bdp_display_single_product_edit_list' ) );
		}
		if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) ) {
			$bdp_screen_edd_archive = add_submenu_page( 'layouts', esc_html__( 'Download Product Archive Layouts', 'blog-designer-pro' ), esc_html__( 'Download Product Archive Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'edd_archive_layouts', array( $this, 'bdp_display_archive_edd_list' ) );
			add_action( "load-$bdp_screen_edd_archive", array( $this, 'bdp_screen_options_edd_archive' ) );
			add_submenu_page( 'layouts', esc_html__( 'Download Product Archive Settings', 'blog-designer-pro' ), esc_html__( 'Add Download Product Archive Layout', 'blog-designer-pro' ), $manage_blog_designs, 'add_edd_archive', array( $this, 'bdp_display_download_archive_edit_list' ) );
			$bdp_screen_single_edd = add_submenu_page( 'layouts', esc_html__( 'Single Download Product Layouts', 'blog-designer-pro' ), esc_html__( 'Single Download Product Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'single_edd_layouts', array( $this, 'bdp_display_single_edd_list' ) );
			add_action( "load-$bdp_screen_single_edd", array( $this, 'bdp_screen_options_single_download' ) );
			add_submenu_page( 'layouts', esc_html__( 'Single Download Product Settings', 'blog-designer-pro' ), esc_html__( 'Add Single Download Product Layout', 'blog-designer-pro' ), $manage_blog_designs, 'single_edd_download', array( $this, 'bdp_display_single_download_edit_list' ) );
		}
		add_submenu_page( 'layouts', esc_html__( 'Import Layouts', 'blog-designer-pro' ), esc_html__( 'Import Layouts', 'blog-designer-pro' ), $manage_blog_designs, 'bdp_export', array( $this, 'bdp_import_blog_layouts' ) );
		add_submenu_page( 'layouts', esc_html__( 'Getting Started', 'blog-designer-pro' ), esc_html__( 'Getting Started', 'blog-designer-pro' ), $manage_blog_designs, 'bdp_getting_started', array( $this, 'bdp_getting_started_page' ) );
	}

	/** Include admin Blog Layout list page */
	public static function bdp_display_shortcode_list() {
		include_once 'assets/admin-shortcode-list.php';
	}

	/** Include admin Single Post Type list page */
	public static function bdp_display_single_list() {
		include_once 'assets/admin-single-list.php';
	}

	/** Include admin Post Archive list page */
	public static function bdp_display_archive_list() {
		include_once 'assets/admin-archive-list.php';
	}

	/** Include admin Woocommerce archive product list page
	 *
	 * @since 2.6
	 */
	public static function bdp_display_product_archive_list() {
		include_once 'assets/admin-product-archive-list.php';
	}

	/** Include admin Easy Digital Download Single archive Product list page
	 *
	 * @since 2.7
	 */
	public static function bdp_display_archive_edd_list() {
		include_once 'assets/admin-download-archive-list.php';
	}

	/** Include admin Easy Digital Download Single Download list page
	 *
	 * @since 2.7
	 */
	public static function bdp_display_single_edd_list() {
		include_once 'assets/admin-single-download-list.php';
	}

	/** Include admin Woocommerce Single Product list page
	 *
	 * @since 2.6
	 */
	public static function bdp_display_single_product_list() {
		include_once 'assets/admin-single-product-list.php';
	}

	/** Include admin edit form */
	public static function bdp_display_shortcode_edit_list() {
		include_once 'assets/admin-edit-form.php';
	}

	/** Include single post form */
	public static function bdp_display_post_edit_list() {
		include_once 'assets/admin-single-edit-form.php';
	}

	/** Include archive layout form */
	public static function bdp_display_archive_edit_list() {
		include_once 'assets/admin-archive-edi-form.php';
	}

	/** Include Woocommerce Archive Product layout form
	 *
	 * @since 2.6
	 */
	public static function bdp_display_product_archive_edit_list() {
		include_once 'assets/admin-product-archive-edit-form.php';
	}

	/** Include Easy Digital Download Archive layout form
	 *
	 * @since 2.7
	 */
	public static function bdp_display_download_archive_edit_list() {
		include_once 'assets/admin-download-archive-edit-form.php';
	}


	/** Include Woocommerce Single Product layout form
	 *
	 * @since 2.6
	 */
	public static function bdp_display_single_product_edit_list() {
		include_once 'assets/admin-single-product-edit-form.php';
	}

	/** Include Download archive layout edit form */
	public static function bdp_display_single_download_edit_list() {
		include_once 'assets/admin-single-download-edit-form.php';
	}

	/** Include Import data form Page */
	public static function bdp_import_blog_layouts() {
		include_once 'assets/admin-import-form.php';
	}

	/** Include bdp getting started page */
	public static function bdp_getting_started_page() {
		include_once 'assets/bdp-getting-started.php';
	}

	/**
	 *
	 * Enqueue admin panel required css and js
	 */
	public function bdp_admin_stylesheet_js() {
		global $bdp_version;
		$bdp_admin_pages = array( 'layouts', 'archive_layouts', 'add_shortcode', 'single_post', 'bdp_add_archive_layout', 'bdp_add_product_archive_layout', 'single_product', 'bdp_export', 'single_layouts', 'bdp_getting_started', 'designer_welcome_page', 'product_archive_layouts', 'single_product_layouts', 'single_edd_download', 'single_edd_layouts', 'edd_archive_layouts', 'add_edd_archive', 'bd-ads-license' );
		if ( isset( $_GET['page'] ) && ( in_array( $_GET['page'], $bdp_admin_pages ) ) ) {
			$admin_stylesheet_url     = plugins_url( 'css/admin.css', __FILE__ );
			$adminstylesheet          = __DIR__ . '/css/admin.css';
			$admin_rtl_stylesheet_url = plugins_url( 'css/admin-rtl.css', __FILE__ );
			if ( file_exists( $adminstylesheet ) ) {
				wp_register_style( 'bdp-admin-stylesheets', $admin_stylesheet_url, null, $bdp_version, false );
				wp_enqueue_style( 'bdp-admin-stylesheets' );
			}
			if ( is_rtl() ) {
				wp_register_style( 'bdp-admin-rtl-stylesheets', $admin_rtl_stylesheet_url, null, $bdp_version, false );
				wp_enqueue_style( 'bdp-admin-rtl-stylesheets' );
			}
			wp_enqueue_script( 'jquery' );
			if ( function_exists( 'wp_enqueue_code_editor' ) ) {
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
			}
			wp_register_style( 'bdp-admin-arsto', BLOGDESIGNERPRO_URL . '/admin/css/aristo.css', null, '1.0', false );
			wp_enqueue_style( 'bdp-admin-arsto' );
			wp_register_style( 'bdp-basic-tools-min', BLOGDESIGNERPRO_URL . '/admin/css/basic-tools-min.css', null, '1.0', false );
			wp_enqueue_style( 'bdp-basic-tools-min' );
		}
	}

	/**
	 *
	 * Set default value
	 *
	 * @global array $bdp_settings
	 */
	public function bdp_default_settings_function() {
		global $bdp_settings, $wpdb;
		if ( empty( $bdp_settings ) ) {
			$bdp_settings = array(
				'pagination_type'                          => 'paged',
				'pagination_text_color'                    => '#ffffff',
				'pagination_background_color'              => '#777777',
				'pagination_text_hover_color'              => '',
				'pagination_background_hover_color'        => '',
				'pagination_text_active_color'             => '',
				'pagination_active_background_color'       => '',
				'pagination_border_color'                  => '#b2b2b2',
				'pagination_active_border_color'           => '#007acc',
				'display_category'                         => '0',
				'display_tag'                              => '0',
				'display_author'                           => '0',
				'display_author_data'                      => '0',
				'display_author_biography'                 => '0',
				'display_date'                             => '0',
				'display_story_year'                       => '1',
				'display_postlike'                         => '0',
				'display_thumbnail'                        => '0',
				'display_comment_count'                    => '0',
				'display_comment'                          => '0',
				'display_navigation'                       => '0',
				'template_name'                            => 'classical',
				'template_alternativebackground'           => '0',
				'rss_use_excerpt'                          => '1',
				'social_share'                             => '1',
				'social_style'                             => '1',
				'social_icon_style'                        => '1',
				'social_icon_size'                         => '1',
				'facebook_link'                            => '1',
				'twitter_link'                             => '1',
				'linkedin_link'                            => '1',
				'email_link'                               => '1',
				'whatsapp_link'                            => '1',
				'pinterest_link'                           => '1',
				'facebook_link_with_count'                 => '0',
				'pinterest_link_with_count'                => '0',
				'social_count_position'                    => 'bottom',
				'bdp_post_offset'                          => '0',
				'template_bgcolor'                         => '#ffffff',
				'template_color'                           => '#000',
				'template_alterbgcolor'                    => '#ffffff',
				'template_ftcolor'                         => '#2376ad',
				'template_fthovercolor'                    => '#2b2b2b',
				'grid_hoverback_color'                     => '#000000',
				'template_title_alignment'                 => 'left',
				'template_titlecolor'                      => '#222222',
				'template_titlehovercolor'                 => '#666666',
				'template_titlebackcolor'                  => '',
				'template_titlefontsize'                   => '30',
				'template_titlefontface'                   => '',
				'template_contentfontface'                 => '',
				'related_post_by'                          => 'category',
				'bdp_related_post_order_by'                => 'date',
				'bdp_related_post_order'                   => 'DESC',
				'txtExcerptlength'                         => '50',
				'content_fontsize'                         => '14',
				'unique_design_option'                     => '',
				'firstletter_fontsize'                     => '20',
				'firstletter_contentcolor'                 => '#000000',
				'template_contentcolor'                    => '#7b95a6',
				'template_content_hovercolor'              => '#ed4b1f',
				'txtReadmoretext'                          => 'Read More',
				'readmore_font_family_font_type'           => '',
				'readmore_font_family'                     => '',
				'readmore_fontsize'                        => '14',
				'readmore_font_weight'                     => 'normal',
				'readmore_font_line_height'                => '1.5',
				'readmore_font_text_transform'             => 'none',
				'readmore_font_text_decoration'            => 'none',
				'readmore_font_letter_spacing'             => '0',
				'read_more_on'                             => '2',
				'template_readmorecolor'                   => '#2376ad',
				'template_readmorehovercolor'              => '#2376ad',
				'template_readmorebackcolor'               => '#dcdee0',
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
				'bdp_readmore_button_borderleft'           => '0',
				'bdp_readmore_button_borderleftcolor'      => '',
				'bdp_readmore_button_borderright'          => '0',
				'bdp_readmore_button_borderrightcolor'     => '',
				'bdp_readmore_button_bordertop'            => '0',
				'bdp_readmore_button_bordertopcolor'       => '',
				'bdp_readmore_button_borderbottom'         => '0',
				'bdp_readmore_button_borderbottomcolor'    => '',
				'template_columns'                         => '2',
				'template_grid_skin'                       => 'default',
				'template_grid_height'                     => '300',
				'bdp_blog_order_by'                        => '',
				'bdp_blog_order'                           => 'DESC',
				'related_post_title'                       => esc_html__( 'Related Posts', 'blog-designer-pro' ),
				'date_color_of_readmore'                   => '0',
				'template_easing'                          => 'easeOutSine',
				'display_timeline_bar'                     => '0',
				'item_width'                               => '400',
				'item_height'                              => '570',
				'display_arrows'                           => '1',
				'enable_autoslide'                         => '0',
				'enable_lazy_load'                         => '0',
				'enable_lazy_load_blur_image'              => '0',
				'template_lazy_load_color'                 => '#fff',
				'enable_print_page'                        => '1',
				'scroll_speed'                             => '1000',
				'easy_timeline_effect'                     => 'flip-effect',
				'display_feature_image'                    => '0',
				'thumbnail_skin'                           => '0',
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
				'bdp_price_font_letter_spacing'            => '0',
				'bdp_price_font_text_transform'            => 'none',
				'bdp_price_font_text_decoration'           => 'none',
				'bdp_addtocart_button_font_text_transform' => 'none',
				'bdp_addtocart_button_font_text_decoration' => 'none',
				'bdp_addtowishlist_button_font_text_transform' => 'none',
				'bdp_addtowishlist_button_font_text_decoration' => 'none',
				'display_addtocart_button'                 => '0',
				'bdp_addtocart_button_fontface_font_type'  => '',
				'bdp_addtocart_button_fontface'            => '',
				'bdp_addtocart_button_fontsize'            => '14',
				'bdp_addtocart_button_font_weight'         => 'normal',
				'bdp_addtocart_button_font_italic'         => '0',
				'bdp_addtocart_button_letter_spacing'      => '0',
				'display_addtocart_button_line_height'     => '1.5',
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
				'bdp_addtocartbutton_hover_padding_leftright' => '0',
				'bdp_addtocartbutton_hover_padding_topbottom' => '0',
				'bdp_addtocartbutton_hover_margin_topbottom' => '0',
				'bdp_addtocartbutton_hover_margin_leftright' => '0',
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
				'bdp_addtowishlist_button_fontface_font_type' => '',
				'bdp_addtowishlist_button_fontface'        => '',
				'bdp_addtowishlist_button_fontsize'        => '14',
				'bdp_addtowishlist_button_font_weight'     => 'normal',
				'bdp_addtowishlist_button_font_italic'     => '0',
				'bdp_addtowishlist_button_letter_spacing'  => '0',
				'display_wishlist_button_line_height'      => '1.5',
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
				'bdp_wishlistbutton_hover_margin_topbottom' => '5',
				'bdp_wishlistbutton_hover_margin_leftright' => '5',
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
				'display_edd_addtocart_button'             => '0',
				'bdp_edd_addtocart_button_fontface_font_type' => '',
				'bdp_edd_addtocart_button_fontface'        => '',
				'bdp_edd_addtocart_button_fontsize'        => '14',
				'bdp_edd_addtocart_button_font_weight'     => 'normal',
				'bdp_edd_addtocart_button_font_italic'     => '0',
				'bdp_edd_addtocart_button_letter_spacing'  => '0',
				'display_edd_addtocart_button_line_height' => '1.5',
				'bdp_edd_addtocart_textcolor'              => '#ffffff',
				'bdp_edd_addtocart_backgroundcolor'        => '#777777',
				'bdp_edd_addtocart_text_hover_color'       => '#ffffff',
				'bdp_edd_addtocart_hover_backgroundcolor'  => '#333333',
				'bdp_edd_addtocartbutton_borderleft'       => '0',
				'bdp_edd_addtocartbutton_borderleftcolor'  => '',
				'bdp_edd_addtocartbutton_borderright'      => '0',
				'bdp_edd_addtocartbutton_borderrightcolor' => '',
				'bdp_edd_addtocartbutton_bordertop'        => '0',
				'bdp_edd_addtocartbutton_bordertopcolor'   => '',
				'bdp_edd_addtocartbutton_borderbottom'     => '0',
				'bdp_edd_addtocartbutton_borderbottomcolor' => '',
				'bdp_edd_addtocartbutton_hover_borderleft' => '0',
				'bdp_edd_addtocartbutton_hover_borderleftcolor' => '',
				'bdp_edd_addtocartbutton_hover_borderright' => '0',
				'bdp_edd_addtocartbutton_hover_borderrightcolor' => '',
				'bdp_edd_addtocartbutton_hover_bordertop'  => '0',
				'bdp_edd_addtocartbutton_hover_bordertopcolor' => '',
				'bdp_edd_addtocartbutton_hover_borderbottom' => '0',
				'bdp_edd_addtocartbutton_hover_borderbottomcolor' => '',
				'display_edd_addtocart_button_border_hover_radius' => '0',
				'bdp_edd_addtocartbutton_hover_padding_leftright' => '0',
				'bdp_edd_addtocartbutton_hover_padding_topbottom' => '0',
				'bdp_edd_addtocartbutton_hover_margin_topbottom' => '0',
				'bdp_edd_addtocartbutton_hover_margin_leftright' => '0',
				'bdp_edd_addtocartbutton_padding_leftright' => '10',
				'bdp_edd_addtocartbutton_padding_topbottom' => '10',
				'bdp_edd_addtocartbutton_margin_leftright' => '15',
				'bdp_edd_addtocartbutton_margin_topbottom' => '10',
				'bdp_edd_addtocartbutton_alignment'        => 'left',
				'display_edd_addtocart_button_border_radius' => '0',
				'bdp_edd_addtocart_button_left_box_shadow' => '0',
				'bdp_edd_addtocart_button_right_box_shadow' => '0',
				'bdp_edd_addtocart_button_top_box_shadow'  => '0',
				'bdp_edd_addtocart_button_bottom_box_shadow' => '0',
				'bdp_edd_addtocart_button_box_shadow_color' => '',
				'bdp_edd_addtocart_button_hover_left_box_shadow' => '0',
				'bdp_edd_addtocart_button_hover_right_box_shadow' => '0',
				'bdp_edd_addtocart_button_hover_top_box_shadow' => '0',
				'bdp_edd_addtocart_button_hover_bottom_box_shadow' => '0',
				'bdp_edd_addtocart_button_hover_box_shadow_color' => '',
			);
			$bdp_settings = apply_filters( 'bdp_change_default_settings', $bdp_settings );
		}

		/**
		 * Run the updater code for Single Post Layout
		 */
		if ( isset( $_GET['do_update_bdp_single_template'] ) && 'do' === $_GET['do_update_bdp_single_template'] && get_option( 'bdp_single_template' ) ) {
			$old_single_data     = get_option( 'bdp_single_template' );
			$all_single_template = Bdp_Template::get_all_single_template_settings();
			if ( ! $all_single_template ) {
				global $wpdb;
				$table_name   = $wpdb->prefix . 'bdp_single_layouts';
				$bdp_settings = apply_filters( 'bdp_single_template_settings', $old_single_data );
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$insert = $wpdb->insert(
					$table_name,
					array(
						'single_name'     => esc_html__( 'All Post Settings', 'blog-designer-pro' ),
						'single_template' => 'all',
						'sub_categories'  => '',
						'single_post_id'  => '',
						'settings'        => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $insert ) {
					wp_die( esc_html__( 'Error in run the updater.', 'blog-designer-pro' ) );
				} else {
					$message      = 'single_added_msg';
					$shortcode_id = $wpdb->insert_id;
				}
				delete_option( 'bdp_single_template' );
				$send = admin_url( 'admin.php?page=single_post&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				do_action( 'bdp_add_single_layout', $shortcode_id );
				wp_redirect( $send );
				exit();
			} else {
				delete_option( 'bdp_single_template' );
			}
		}

		/**
		 * Run the updater code for change template name
		 */
		if ( isset( $_GET['do_update_bdp_template_name_changed'] ) && 'do' === $_GET['do_update_bdp_template_name_changed'] ) {
			$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
			if ( 1 == $bdp_template_name_changed ) {
				/** Blog Layout */
				$table_name   = $wpdb->prefix . 'blog_designer_pro_shortcodes';
				$count_layout = $wpdb->get_var( "SELECT COUNT(`bdid`) FROM {$wpdb->prefix}blog_designer_pro_shortcodes" );
				if ( $count_layout > 0 ) {
					$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}blog_designer_pro_shortcodes" ), ARRAY_A );
					foreach ( $get_allsettings as $get_allsetting ) {
						$bdp_settings = maybe_unserialize( $get_allsetting['bdsettings'] );
						if ( 'classical' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'nicy';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'bdsettings' => $get_allsetting['bdsettings'] ), array( 'bdid' => $get_allsetting['bdid'] ) );
						}
						if ( 'lightbreeze' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'sharpen';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'bdsettings' => $get_allsetting['bdsettings'] ), array( 'bdid' => $get_allsetting['bdid'] ) );
						}
						if ( 'spektrum' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'hub';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'bdsettings' => $get_allsetting['bdsettings'] ), array( 'bdid' => $get_allsetting['bdid'] ) );
						}
					}
				}
				wp_reset_postdata();
				/** Archive Layout */
				$table_name    = $wpdb->prefix . 'bdp_archives';
				$count_archive = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_archives" );
				if ( $count_archive > 0 ) {
					$get_allsettings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bdp_archives", ARRAY_A );
					foreach ( $get_allsettings as $get_allsetting ) {
						$bdp_settings = maybe_unserialize( $get_allsetting['settings'] );
						if ( 'classical' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'nicy';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['settings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
						if ( 'lightbreeze' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'sharpen';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
						if ( 'spektrum' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'hub';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
					}
				}
				wp_reset_postdata();
				/** Single Product Layout */
				$table_name   = $wpdb->prefix . 'bdp_single_layouts';
				$count_single = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_layouts" );
				if ( $count_single > 0 ) {
					$get_allsettings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bdp_single_layouts", ARRAY_A );
					foreach ( $get_allsettings as $get_allsetting ) {
						$bdp_settings = maybe_unserialize( $get_allsetting['settings'] );
						if ( 'classical' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'nicy';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['settings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
						if ( 'lightbreeze' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'sharpen';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
						if ( 'spektrum' === $bdp_settings['template_name'] ) {
							$bdp_settings['template_name'] = 'hub';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$get_allsetting['bdsettings'] = maybe_serialize( $bdp_settings );
							$wpdb->update( $table_name, array( 'settings' => $get_allsetting['settings'] ), array( 'id' => $get_allsetting['id'] ) );
						}
					}
				}

				update_option( 'bdp_template_name_changed', 0 );
			}
			update_option( 'bdp_template_name_changed', 0 );
			$send = admin_url( 'admin.php?page=layouts' );
			$send = add_query_arg( 'message', $message, $send );
			wp_redirect( $send );
			exit();
		}
	}

	/**
	 *
	 * Display Notice foe Out Date Copy
	 */
	public function bdp_outdated_templates_notices() {
		$bdp_pages                 = array( 'layouts', 'add_shortcode', 'archive_layouts', 'product_archive_layouts', 'bdp_add_product_archive_layout', 'bdp_add_archive_layout', 'single_layouts', 'single_post', 'single_product_layouts', 'single_product', 'add_edd_archive', 'edd_archive_layouts' );
		$bdp_template_outdated     = get_option( 'bdp_template_outdated', 0 );
		$bdp_override_template_dir = '';
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $bdp_pages ) && 1 != $bdp_template_outdated ) {
			$bdp_outdated = true;
			if ( 'layouts' === $_GET['page'] || 'add_shortcode' === $_GET['page'] ) {
				$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/blog/';
				$bdp_override_template_dir = get_template_directory() . '/bdp_templates/blog/';
				if ( ! is_dir( $bdp_override_template_dir ) ) {
					$bdp_outdated = false;
				}
			}
			if ( 'archive_layouts' === $_GET['page'] || 'bdp_add_archive_layout' === $_GET['page'] ) {
				$bdp_archive_template = get_template_directory() . '/bdp_templates/archive/archive.php';
				if ( file_exists( $bdp_archive_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/archive/';
					$bdp_override_template_dir = get_template_directory() . '/bdp_templates/archive/';
				} else {
					$bdp_outdated = true;
				}
			}
			if ( 'product_archive_layouts' === $_GET['page'] || 'bdp_add_product_archive_layout' === $_GET['page'] ) {
				$bdp_product_archive_template = get_template_directory() . '/bdp_templates/woocommerce/archive/archive-product.php';
				if ( file_exists( $bdp_product_archive_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/archive/';
					$bdp_override_template_dir = get_template_directory() . 'bdp_templates/woocommerce/archive/archive-product.php';
				} else {
					$bdp_outdated = true;
				}
			}
			if ( 'single_product_layouts' === $_GET['page'] || 'single_product' === $_GET['page'] ) {
				$bdp_single_product_template = get_template_directory() . '/bdp_templates/woocommerce/single/single-product.php';
				if ( file_exists( $bdp_single_product_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/woocommerce/single/';
					$bdp_override_template_dir = get_template_directory() . 'bdp_templates/woocommerce/single/single-product.php';
				} else {
					$bdp_outdated = true;
				}
			}
			if ( 'single_edd_download' === $_GET['page'] || 'single_edd_layouts' === $_GET['page'] ) {
				$bdp_single_product_template = get_template_directory() . '/bdp_templates/edd_templates/single/single-download.php';
				if ( file_exists( $bdp_single_product_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/';
					$bdp_override_template_dir = get_template_directory() . 'bdp_templates/edd_templates/single/single-download.php';
				} else {
					$bdp_outdated = true;
				}
			}
			if ( 'edd_archive_layouts' === $_GET['page'] || 'add_edd_archive' === $_GET['page'] ) {
				$bdp_single_product_template = get_template_directory() . '/bdp_templates/edd_templates/archive/archive-download.php';
				if ( file_exists( $bdp_single_product_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/edd_templates/';
					$bdp_override_template_dir = get_template_directory() . 'bdp_templates/edd_templates/archive/archive-download.php';
				} else {
					$bdp_outdated = true;
				}
			}
			if ( 'single_layouts' === $_GET['page'] || 'single_post' === $_GET['page'] ) {
				$bdp_single_template = get_template_directory() . '/bdp_templates/single/single.php';
				if ( file_exists( $bdp_single_template ) ) {
					$bdp_core_template_dir     = BLOGDESIGNERPRO_DIR . 'bdp_templates/single/';
					$bdp_override_template_dir = get_template_directory() . '/bdp_templates/single/';
				} else {
					$bdp_outdated = true;
				}
			}

			if ( is_dir( $bdp_override_template_dir ) ) {
				$bdp_override_templates_layouts = scandir( $bdp_override_template_dir );
				foreach ( $bdp_override_templates_layouts as $key => $value ) {
					if ( '.' !== $value && '..' !== $value ) {
						$bdp_core_template = $bdp_core_template_dir . $value;
						if ( ! file_exists( $bdp_core_template ) ) {
							$bdp_outdated = false;
							continue;
						}
						$core_version          = Bdp_Utility::check_file_version( $bdp_core_template_dir . $value );
						$bdp_override_template = $bdp_override_template_dir . $value;
						$template_version      = Bdp_Utility::check_file_version( $bdp_override_template );

						if ( $core_version > $template_version ) {
							$bdp_outdated = true;
							break;
						} else {
							$bdp_outdated = false;
						}
					}
				}
			}
		}
	}

	/**
	 *
	 * Create table if table not found when plugin is active
	 *
	 * @global object $wpdb
	 */
	public function bdp_table_status() {
		global $wpdb;
		if ( is_plugin_active( 'blog-designer-pro/blog-designer-pro.php' ) ) {
			$bdp_front = new Bdp_Front_Functions();

			/**
			 * Create blog table
			 *
			 * @since 2.6
			 */
			$bdpro_shortcode = $wpdb->prefix . 'blog_designer_pro_shortcodes';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $bdpro_shortcode ) ) != $bdpro_shortcode ) {
				$bdp_front->bdp_create_shortcodes_table();
			}

			/**
			 * Create archive table
			 */
			$archive_table = $wpdb->prefix . 'bdp_archives';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $archive_table ) ) != $archive_table ) {
				$bdp_front->set_archive_table();
			}

			/**
			 * Create archive Product table
			 *
			 * @since 2.6
			 */
			$product_archive_table = $wpdb->prefix . 'bdp_product_archives';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $product_archive_table ) ) != $product_archive_table ) {
				$bdp_front->set_product_archive_table();
			}

			/**
			 * Create single Product table
			 *
			 * @since 2.6
			 */
			$single_product_table = $wpdb->prefix . 'bdp_single_product';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_product_table ) ) != $single_product_table ) {
				$bdp_front->set_single_product_table();
			}

			/**
			 * Create download single table
			 *
			 * @since 2.7
			 */
			$single_edd_table = $wpdb->prefix . 'bdp_single_ed_download';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_edd_table ) ) != $single_edd_table ) {
				$bdp_front->set_single_edd_table();
			}

			/**
			 * Create download archive table
			 *
			 * @since 2.7
			 */
			$archive_edd_table = $wpdb->prefix . 'bdp_edd_archives';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $archive_edd_table ) ) != $archive_edd_table ) {
				$bdp_front->set_edd_archive_table();
			}

			/**
			 * Create single post table
			 */
			$single_table = $wpdb->prefix . 'bdp_single_layouts';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_table ) ) != $single_table ) {
				$bdp_front->set_single_table();
			}
		}
	}
	/**
	 * Enqueue script and style
	 *
	 * @param object $hook_suffix hook.
	 * @return void
	 */
	public function bdp_admin_front_scripts( $hook_suffix ) {
		if ( isset( $_GET['page'] ) && ( 'add_shortcode' === $_GET['page'] || 'bdp_add_archive_layout' === $_GET['page'] || 'single_post' === $_GET['page'] || 'bdp_add_product_archive_layout' === $_GET['page'] || 'single_product' === $_GET['page'] || 'add_edd_archive' === $_GET['page'] || 'single_edd_download' === $_GET['page'] ) ) {
			$fontawesome_icon_url = BLOGDESIGNERPRO_URL . '/public/css/font-awesome.min.css';
			wp_register_script( 'bdp-admin-front-social', BLOGDESIGNERPRO_URL . '/public/js/SocialShare.js', null, '6.5.1', false );
			wp_enqueue_script( 'bdp-admin-front-social' );
			wp_register_style( 'bdp-admin-fontawesome-stylesheets', $fontawesome_icon_url, null, '5.0.8', false );
			wp_enqueue_style( 'bdp-admin-fontawesome-stylesheets' );
		}
	}
	/**
	 * Enqueue Admin scripts and style
	 *
	 * @global object $hook_suffix
	 * @param string $hook_suffix hoook.
	 * @return void
	 */
	public function bdp_admin_scripts( $hook_suffix ) {
		global $wp_version;
		global $bdp_version;
		wp_enqueue_style( 'bdp_support_css', plugins_url( 'css/bdp_support.css', __FILE__ ), null, $bdp_version, false );
		if ( isset( $_GET['page'] ) && 'bdp_getting_started' === $_GET['page'] ) {
			wp_enqueue_script( 'bdp-clipboard', plugins_url( 'js/clipboard.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		}
		$bdp_admin_pages = array( 'layouts', 'archive_layouts', 'add_shortcode', 'single_post', 'bdp_add_archive_layout', 'bdp_add_product_archive_layout', 'single_product', 'bdp_export', 'single_layouts', 'bdp_getting_started', 'designer_welcome_page', 'product_archive_layouts', 'single_product_layouts', 'single_edd_download', 'single_edd_layouts', 'edd_archive_layouts', 'add_edd_archive' );

		/* start single posts script and style */
		wp_register_style( 'custom-meta-box-style', BLOGDESIGNERPRO_URL . '/admin/css/bdp-cptmb.css', null, '1.0' );
		wp_enqueue_style( 'custom-meta-box-style' );

		wp_register_style( 'bookblock-admin', BLOGDESIGNERPRO_URL . '/admin/css/bookblock.css', null, '1.0' );
		wp_enqueue_style( 'bookblock-admin' );

		wp_enqueue_style( 'font-awesome-min', BLOGDESIGNERPRO_URL . '/admin/css/font-awesome.min.css', null, '6.5.1' );
		// wp_enqueue_style( 'font-awesome.min' );.

		wp_enqueue_style( 'slick_admin', BLOGDESIGNERPRO_URL . '/admin/css/slick.css', null, '1.0' );

		wp_enqueue_style( 'wp-color-picker' );
		// wp_enqueue_style('dynamic_css', BLOGDESIGNERPRO_URL . '/public/css/layout-dynamic-style.php',null,'1.0' );.
		wp_enqueue_script( 'bdp-admin-script', plugins_url( 'js/bdp-admin-script.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion', 'wp-color-picker', 'jquery-ui-dialog' ), '2.6', false );

		wp_enqueue_script( 'lazysize_load-admin', plugins_url( 'js/lazysizes.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );

		wp_enqueue_script( 'slick_admin', plugins_url( 'js/slick.min.js', __FILE__ ), array( 'jquery' ), '1.4.1', true );
		wp_enqueue_script( 'carousel_admin', plugins_url( 'js/Carousel.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'js-admin-pdf', plugins_url( '/js/jspdf.min.js', __FILE__ ), array( 'jquery' ), '1.5.3', true );
		wp_enqueue_script( 'bdp-admin-modernize-custom-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array( 'jquery' ), '2.6.2', true );
		wp_enqueue_script( 'bdp-admin-bookblock-script', plugins_url( 'js/jquery.bookblock.js', __FILE__ ), array( 'jquery' ), '2.0.1', true );

		wp_enqueue_script( 'bdp-single-post-script', plugins_url( 'js/bdp-single-post-script.js', __FILE__ ), null, '2.6', false );

		wp_enqueue_script( 'bdp-chosen-script', plugins_url( 'js/chosen.jquery.js', __FILE__ ), null, '2.6', false );
		wp_enqueue_style( 'choosen-style-handle', plugins_url( 'css/chosen.min.css', __FILE__ ), null, '1.0' );

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_localize_script(
			'bdp-admin-script',
			'bdpro_js',
			array(
				'wp_version'                  => $wp_version,
				'nothing_found'               => esc_html__( 'Oops, nothing found!', 'blog-designer-pro' ),
				'choose_archive'              => esc_html__( 'Choose template for archive page', 'blog-designer-pro' ),
				'default_style_template'      => esc_html__( 'Apply default style of this selected template', 'blog-designer-pro' ),
				'set_archive_template'        => esc_html__( 'Set Archive Template', 'blog-designer-pro' ),
				'no_template_exist'           => esc_html__( 'No template exist for selection', 'blog-designer-pro' ),
				'close'                       => esc_html__( 'Close', 'blog-designer-pro' ),
				'choose_blog_template'        => esc_html__( 'Choose the blog template you love', 'blog-designer-pro' ),
				'set_blog_template'           => esc_html__( 'Set Blog Template', 'blog-designer-pro' ),
				'select_arrow'                => esc_html__( 'Select Arrow', 'blog-designer-pro' ),
				'choose_single_post_template' => esc_html__( 'Choose the template you love for your single post', 'blog-designer-pro' ),
				'set_single_template'         => esc_html__( 'Set Single Post Template', 'blog-designer-pro' ),
				'reset_data'                  => esc_html__( 'Do you want to reset data?', 'blog-designer-pro' ),
				'archive_template_preview'    => esc_html__( 'Your archive template preview', 'blog-designer-pro' ),
				'template_preview'            => esc_html__( 'Your template preview', 'blog-designer-pro' ),
				'enter_font_url'              => esc_html__( 'Please enter font URL', 'blog-designer-pro' ),
				'please_enter_font_url'       => esc_html__( 'Please enter a valid font URL', 'blog-designer-pro' ),
				'remove'                      => esc_html__( 'Remove', 'blog-designer-pro' ),
				'remove_font'                 => esc_html__( 'Remove Font', 'blog-designer-pro' ),
				'font_added'                  => esc_html__( 'Font added successfully.', 'blog-designer-pro' ),
				'font_not_added'              => esc_html__( 'Font not added successfully.', 'blog-designer-pro' ),
				'delete_google_font'          => esc_html__( 'Are you sure want to delete google font?', 'blog-designer-pro' ),
				'font_deleted'                => esc_html__( 'Font deleted successfully.', 'blog-designer-pro' ),
				'font_not_deleted'            => esc_html__( 'Font not deleted successfully.', 'blog-designer-pro' ),
				'readmore'                    => esc_html__( 'Read More', 'blog-designer-pro' ),
				'loadmore'                    => esc_html__( 'Load More', 'blog-designer-pro' ),
				'info'                        => esc_html__( 'info.', 'blog-designer-pro' ),
				'information'                 => esc_html__( 'information', 'blog-designer-pro' ),
				'about'                       => esc_html__( 'About', 'blog-designer-pro' ),
				'learn_more'                  => esc_html__( 'Learn More', 'blog-designer-pro' ),
				'view_more'                   => esc_html__( 'View More', 'blog-designer-pro' ),
				'info_about'                  => esc_html__( 'Information about', 'blog-designer-pro' ),
				'continue_reading'            => esc_html__( 'Continue Reading', 'blog-designer-pro' ),
				'view_article'                => esc_html__( 'View Article', 'blog-designer-pro' ),
				'keep_reading'                => esc_html__( 'Keep Reading', 'blog-designer-pro' ),
				'related_posts'               => esc_html__( 'Related Posts', 'blog-designer-pro' ),
				'share_posts'                 => esc_html__( 'Share This Post', 'blog-designer-pro' ),
				'show_more_posts'             => esc_html__( 'Show More Posts', 'blog-designer-pro' ),
				'related_products'            => esc_html__( 'Related Products', 'blog-designer-pro' ),
				'share_products'              => esc_html__( 'Share This Products', 'blog-designer-pro' ),
				'show_more_products'          => esc_html__( 'Show More Products', 'blog-designer-pro' ),
				'related_downloads'           => esc_html__( 'Related Products', 'blog-designer-pro' ),
				'share_downloads'             => esc_html__( 'Share This Downloads', 'blog-designer-pro' ),
				'show_more_downloads'         => esc_html__( 'Show More Downloads', 'blog-designer-pro' ),
				'share_it'                    => esc_html__( 'Share It Now', 'blog-designer-pro' ),
				'you_also_like'               => esc_html__( 'You may also like', 'blog-designer-pro' ),
				'more_stories'                => esc_html__( 'More Stories', 'blog-designer-pro' ),
				'share'                       => esc_html__( 'Share it', 'blog-designer-pro' ),
				'chk_related_post'            => esc_html__( 'Check Related Posts', 'blog-designer-pro' ),
				'more_post'                   => esc_html__( 'More Posts', 'blog-designer-pro' ),
				'chk_more_related_post'       => esc_html__( 'Check more related posts', 'blog-designer-pro' ),
				'chk_related_product'         => esc_html__( 'Check Related Products', 'blog-designer-pro' ),
				'more_product'                => esc_html__( 'More Products', 'blog-designer-pro' ),
				'chk_more_related_product'    => esc_html__( 'Check more related products', 'blog-designer-pro' ),
				'chk_related_download'        => esc_html__( 'Check Related Products', 'blog-designer-pro' ),
				'more_download'               => esc_html__( 'More Downloads', 'blog-designer-pro' ),
				'chk_more_related_download'   => esc_html__( 'Check more related products', 'blog-designer-pro' ),
				'share_now'                   => esc_html__( 'Share Now', 'blog-designer-pro' ),
				'change_html'                 => esc_html__( 'Image Height', 'blog-designer-pro' ),
				'the_author'                  => esc_html__( 'The Author', 'blog-designer-pro' ),
				'read_more_hover'             => esc_html__( 'Read More Link Hover Color', 'blog-designer-pro' ),
				'bdp_font_base'               => ( is_ssl() ) ? 'https://' : 'http://',
				'startup_text'                => esc_html__( 'STARTUP', 'blog-designer-pro' ),
				'is_rtl'                      => ( is_rtl() ) ? 1 : 0,
				'bdp_template_name_changed'   => get_option( 'bdp_template_name_changed', 1 ),
				'copied'                      => esc_html__( 'Copied', 'blog-designer-pro' ),
				'copy_for_support'            => esc_html__( 'Copy for Support', 'blog-designer-pro' ),
				'like'                        => esc_html__( 'Like', 'blog-designer-pro' ),
				'unlike'                      => esc_html__( 'Unlike', 'blog-designer-pro' ),
				'ajax_nonce'                  => wp_create_nonce( 'ajax-nonce' ),
			)
		);
		/* end single posts script and style */

		if ( isset( $_GET['page'] ) && ( in_array( $_GET['page'], $bdp_admin_pages ) ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			if ( function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
			if ( isset( $_GET['page'] ) && ( 'add_shortcode' === $_GET['page'] ) ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
			wp_enqueue_script( 'bdp-admin-script', plugins_url( 'js/bdp-admin-script.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-accordion', 'wp-color-picker', 'jquery-ui-tabs' ), '2.6', false );
			wp_localize_script(
				'bdp-admin-script',
				'bdpro_js',
				array(
					'wp_version'                  => $wp_version,
					'nothing_found'               => esc_html__( 'Oops, nothing found!', 'blog-designer-pro' ),
					'choose_archive'              => esc_html__( 'Choose template for archive page', 'blog-designer-pro' ),
					'default_style_template'      => esc_html__( 'Apply default style of this selected template', 'blog-designer-pro' ),
					'set_archive_template'        => esc_html__( 'Set Archive Template', 'blog-designer-pro' ),
					'no_template_exist'           => esc_html__( 'No template exist for selection', 'blog-designer-pro' ),
					'close'                       => esc_html__( 'Close', 'blog-designer-pro' ),
					'choose_blog_template'        => esc_html__( 'Choose the blog template you love', 'blog-designer-pro' ),
					'set_blog_template'           => esc_html__( 'Set Blog Template', 'blog-designer-pro' ),
					'select_arrow'                => esc_html__( 'Select Arrow', 'blog-designer-pro' ),
					'choose_single_post_template' => esc_html__( 'Choose the template you love for your single post', 'blog-designer-pro' ),
					'set_single_template'         => esc_html__( 'Set Single Post Template', 'blog-designer-pro' ),
					'reset_data'                  => esc_html__( 'Do you want to reset data?', 'blog-designer-pro' ),
					'archive_template_preview'    => esc_html__( 'Your archive template preview', 'blog-designer-pro' ),
					'template_preview'            => esc_html__( 'Your template preview', 'blog-designer-pro' ),
					'enter_font_url'              => esc_html__( 'Please enter font URL', 'blog-designer-pro' ),
					'please_enter_font_url'       => esc_html__( 'Please enter a valid font URL', 'blog-designer-pro' ),
					'remove'                      => esc_html__( 'Remove', 'blog-designer-pro' ),
					'remove_font'                 => esc_html__( 'Remove Font', 'blog-designer-pro' ),
					'font_added'                  => esc_html__( 'Font added successfully.', 'blog-designer-pro' ),
					'font_not_added'              => esc_html__( 'Font not added successfully.', 'blog-designer-pro' ),
					'delete_google_font'          => esc_html__( 'Are you sure want to delete google font?', 'blog-designer-pro' ),
					'font_deleted'                => esc_html__( 'Font deleted successfully.', 'blog-designer-pro' ),
					'font_not_deleted'            => esc_html__( 'Font not deleted successfully.', 'blog-designer-pro' ),
					'readmore'                    => esc_html__( 'Read More', 'blog-designer-pro' ),
					'loadmore'                    => esc_html__( 'Load More', 'blog-designer-pro' ),
					'info'                        => esc_html__( 'info.', 'blog-designer-pro' ),
					'information'                 => esc_html__( 'information', 'blog-designer-pro' ),
					'about'                       => esc_html__( 'About', 'blog-designer-pro' ),
					'learn_more'                  => esc_html__( 'Learn More', 'blog-designer-pro' ),
					'view_more'                   => esc_html__( 'View More', 'blog-designer-pro' ),
					'info_about'                  => esc_html__( 'Information about', 'blog-designer-pro' ),
					'continue_reading'            => esc_html__( 'Continue Reading', 'blog-designer-pro' ),
					'view_article'                => esc_html__( 'View Article', 'blog-designer-pro' ),
					'keep_reading'                => esc_html__( 'Keep Reading', 'blog-designer-pro' ),
					'related_posts'               => esc_html__( 'Related Posts', 'blog-designer-pro' ),
					'share_posts'                 => esc_html__( 'Share This Post', 'blog-designer-pro' ),
					'show_more_posts'             => esc_html__( 'Show More Posts', 'blog-designer-pro' ),
					'related_products'            => esc_html__( 'Related Products', 'blog-designer-pro' ),
					'share_products'              => esc_html__( 'Share This Products', 'blog-designer-pro' ),
					'show_more_products'          => esc_html__( 'Show More Products', 'blog-designer-pro' ),
					'related_downloads'           => esc_html__( 'Related Products', 'blog-designer-pro' ),
					'share_downloads'             => esc_html__( 'Share This Downloads', 'blog-designer-pro' ),
					'show_more_downloads'         => esc_html__( 'Show More Downloads', 'blog-designer-pro' ),
					'share_it'                    => esc_html__( 'Share It Now', 'blog-designer-pro' ),
					'you_also_like'               => esc_html__( 'You may also like', 'blog-designer-pro' ),
					'more_stories'                => esc_html__( 'More Stories', 'blog-designer-pro' ),
					'share'                       => esc_html__( 'Share it', 'blog-designer-pro' ),
					'chk_related_post'            => esc_html__( 'Check Related Posts', 'blog-designer-pro' ),
					'more_post'                   => esc_html__( 'More Posts', 'blog-designer-pro' ),
					'chk_more_related_post'       => esc_html__( 'Check more related posts', 'blog-designer-pro' ),
					'chk_related_product'         => esc_html__( 'Check Related Products', 'blog-designer-pro' ),
					'more_product'                => esc_html__( 'More Products', 'blog-designer-pro' ),
					'chk_more_related_product'    => esc_html__( 'Check more related products', 'blog-designer-pro' ),
					'chk_related_download'        => esc_html__( 'Check Related Products', 'blog-designer-pro' ),
					'more_download'               => esc_html__( 'More Downloads', 'blog-designer-pro' ),
					'chk_more_related_download'   => esc_html__( 'Check more related products', 'blog-designer-pro' ),
					'share_now'                   => esc_html__( 'Share Now', 'blog-designer-pro' ),
					'change_html'                 => esc_html__( 'Image Height', 'blog-designer-pro' ),
					'the_author'                  => esc_html__( 'The Author', 'blog-designer-pro' ),
					'read_more_hover'             => esc_html__( 'Read More Link Hover Color', 'blog-designer-pro' ),
					'bdp_font_base'               => ( is_ssl() ) ? 'https://' : 'http://',
					'startup_text'                => esc_html__( 'STARTUP', 'blog-designer-pro' ),
					'is_rtl'                      => ( is_rtl() ) ? 1 : 0,
					'bdp_template_name_changed'   => get_option( 'bdp_template_name_changed', 1 ),
					'copied'                      => esc_html__( 'Copied', 'blog-designer-pro' ),
					'copy_for_support'            => esc_html__( 'Copy for Support', 'blog-designer-pro' ),
					'ajax_nonce'                  => wp_create_nonce( 'ajax-nonce' ),
				)
			);

			wp_enqueue_script( 'choosen-script-handle', plugins_url( 'js/chosen.jquery.js', __FILE__ ), null, '1.0', false );
			wp_enqueue_style( 'choosen-style-handle', plugins_url( 'css/chosen.min.css', __FILE__ ), null, '1.0' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			$screen          = get_current_screen();
			$plugin_data     = get_plugin_data( WP_PLUGIN_DIR . '/blog-designer-pro/blog-designer-pro.php', $markup = true, $translate = true );
			$current_version = $plugin_data['Version'];
			$old_version     = get_option( 'bdp_version' );
			if ( $old_version != $current_version ) {
				update_option( 'bdp_version', $current_version );
			}
		}
		if ( 'index.php' === $hook_suffix ) {
			$admin_stylesheet_url = plugins_url( 'css/admin.css', __FILE__ );
			$adminstylesheet      = __DIR__ . '/css/admin.css';
			if ( file_exists( $adminstylesheet ) ) {
				wp_register_style( 'bdp-admin-stylesheets', $admin_stylesheet_url, null, $bdp_version, false );
				wp_enqueue_style( 'bdp-admin-stylesheets' );
			}
		}
	}

	/**
	 * Duplicate Layout
	 */
	public function bdp_duplicate_layout() {

		/** Duplicate Blog Layout */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_post_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_add_shortcode', $closed, true );
			$layout_setting = Bdp_Template::get_shortcode_settings( sanitize_text_field( wp_unslash( $_GET['layout'] ) ) );
			if ( $layout_setting ) {
				$layout_setting['blog_page_display'] = 0;
				$shortcode_name                      = $layout_setting['unique_shortcode_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				$shortcode_id                        = Bdp_Template::insert_layout( $shortcode_name, $layout_setting );
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				do_action( 'bdp_duplicate_layout_settings', $shortcode_id );
				$send = admin_url( 'admin.php?page=add_shortcode&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			} else {
				wp_die( esc_html__( 'No layout to duplicate has been supplied!', 'blog-designer-pro' ) );
			}
		}
		/** Duplicate Archive Post Layout */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_archive_post_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_bdp_add_archive_layout', $closed, true );
			global $wpdb;
			$archive_id    = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$archive_table = $wpdb->prefix . 'bdp_archives';
			if ( is_numeric( $archive_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE ID = %d", $archive_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings    = $get_allsettings[0]['settings'];
				$layout_setting = Bdp_Template::get_shortcode_settings( sanitize_text_field( wp_unslash( $_GET['layout'] ) ) );
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings          = maybe_unserialize( $allsettings );
					$archive_template_name = $get_allsettings[0]['archive_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
					$shortcode_name        = $layout_setting['unique_shortcode_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
					$shortcode_id          = Bdp_Template::insert_layout( $shortcode_name, $layout_setting );
				}
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$bdp_settings['custom_archive_type'] = '';
				$archive_layout_setting              = $wpdb->insert(
					$archive_table,
					array(
						'archive_name'     => sanitize_text_field( $archive_template_name ),
						'archive_template' => '',
						'sub_categories'   => '',
						'settings'         => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $archive_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_archive_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=bdp_add_archive_layout&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}

		/**
		 * Dupliacte Product Archive layout
		 *
		 * @since 2.6
		 */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_product_archive_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_bdp_add_product_archive_layout', $closed, true );
			global $wpdb;
			$archive_id            = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$archive_product_table = $wpdb->prefix . 'bdp_product_archives';
			if ( is_numeric( $archive_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives WHERE ID = %d", $archive_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings = $get_allsettings[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings          = maybe_unserialize( $allsettings );
					$archive_template_name = $get_allsettings[0]['product_archive_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				}
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$bdp_settings['custom_archive_type'] = '';
				$archive_layout_setting              = $wpdb->insert(
					$archive_product_table,
					array(
						'product_archive_name'     => sanitize_text_field( $archive_template_name ),
						'product_archive_template' => '',
						'product_sub_categories'   => '',
						'settings'                 => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $archive_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_archive_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=bdp_add_product_archive_layout&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}

		/**
		 * Dupliacte Product Archive layout
		 *
		 * @since 2.7
		 */
		/** Dupliacte Download Archive Post type */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_download_archive_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_add_edd_archive', $closed, true );
			global $wpdb;
			$archive_id    = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$archive_table = $wpdb->prefix . 'bdp_edd_archives';
			if ( is_numeric( $archive_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_edd_archives WHERE ID = %d", $archive_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings = $get_allsettings[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings          = maybe_unserialize( $allsettings );
					$archive_template_name = $get_allsettings[0]['download_archive_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				}
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$bdp_settings['custom_archive_type'] = '';
				$bdp_settings['template_category']   = '';
				$bdp_settings['template_tags']       = '';
				$bdp_settings['archive_name']        = $bdp_settings['archive_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				$archive_layout_setting              = $wpdb->insert(
					$archive_table,
					array(
						'download_archive_name'     => sanitize_text_field( $archive_template_name ),
						'download_archive_template' => '',
						'download_sub_categories'   => '',
						'settings'                  => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $archive_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_archive_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=add_edd_archive&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}

		/** Dupliacte Single Post type Layout */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_single_post_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpsinglegeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_single_post', $closed, true );
			global $wpdb;
			$single_id    = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$single_table = $wpdb->prefix . 'bdp_single_layouts';
			if ( is_numeric( $single_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_layouts WHERE ID = %d", $single_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings = $get_allsettings[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings         = maybe_unserialize( $allsettings );
					$custom_single_type   = $get_allsettings[0]['single_template'];
					$single_template_name = $get_allsettings[0]['single_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				}
				$bdp_settings['bdp_single_type']    = '';
				$bdp_settings['single_layout_name'] = array();
				$bdp_settings['template_tags']      = array();
				$bdp_settings['template_posts']     = array();
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$single_layout_setting = $wpdb->insert(
					$single_table,
					array(
						'single_name'     => sanitize_text_field( $single_template_name ),
						'single_template' => sanitize_text_field( $bdp_settings['template_name'] ),
						'sub_categories'  => '',
						'single_post_id'  => '',
						'settings'        => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $single_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				$message = 'shortcode_duplicate_msg';
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_single_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=single_post&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}

		/**
		 * Dupliacte Single Product type Layout
		 *
		 * @since 2.7
		 */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_single_product_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpsinglegeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_single_product', $closed, true );
			global $wpdb;
			$single_id            = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$single_product_table = $wpdb->prefix . 'bdp_single_product';
			if ( is_numeric( $single_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_product WHERE ID = %d", $single_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings = $get_allsettings[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings         = maybe_unserialize( $allsettings );
					$custom_single_type   = $get_allsettings[0]['single_product_template'];
					$single_template_name = $get_allsettings[0]['single_product_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				}
				$bdp_settings['bdp_single_type']    = '';
				$bdp_settings['single_layout_name'] = array();
				$bdp_settings['template_tags']      = array();
				$bdp_settings['template_posts']     = array();
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$single_layout_setting = $wpdb->insert(
					$single_product_table,
					array(
						'single_product_name'     => sanitize_text_field( $single_template_name ),
						'single_product_template' => sanitize_text_field( $bdp_settings['template_name'] ),
						'sub_categories'          => '',
						'single_product_id'       => '',
						'settings'                => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $single_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				$message = 'shortcode_duplicate_msg';
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_single_product_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=single_product&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}

		/**
		 * Dupliacte Single Download type Layout
		 *
		 * @since 2.7
		 */
		if ( ( isset( $_GET['layout'] ) && '' != $_GET['layout'] ) && ( isset( $_GET['action'] ) && 'duplicate_single_edd_download_in_edit' === $_GET['action'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpsinglegeneral' );
			$closed = array_filter( $closed );
			update_user_option( $user->ID, 'bdpclosedbdpboxes_single_edd_download', $closed, true );
			global $wpdb;
			$single_id             = sanitize_text_field( wp_unslash( $_GET['layout'] ) );
			$single_download_table = $wpdb->prefix . 'bdp_single_ed_download';
			if ( is_numeric( $single_id ) ) {
				$get_allsettings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_ed_download WHERE ID = %d", $single_id ), ARRAY_A );
			}
			if ( ! isset( $get_allsettings[0]['settings'] ) ) {
				echo '<div class="updated notice">';
				wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
				echo '</div>';
			} else {
				$allsettings = $get_allsettings[0]['settings'];
				if ( is_serialized( $allsettings ) ) {
					$bdp_settings         = maybe_unserialize( $allsettings );
					$custom_single_type   = $get_allsettings[0]['single_download_template'];
					$single_template_name = $get_allsettings[0]['single_download_name'] . ' ' . esc_html__( 'Copy', 'blog-designer-pro' );
				}
				$bdp_settings['bdp_single_type']    = '';
				$bdp_settings['single_layout_name'] = array();
				$bdp_settings['template_tags']      = array();
				$bdp_settings['template_posts']     = array();
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$single_layout_setting = $wpdb->insert(
					$single_download_table,
					array(
						'single_download_name'     => sanitize_text_field( $single_template_name ),
						'single_download_template' => sanitize_text_field( $bdp_settings['template_name'] ),
						'sub_categories'           => '',
						'single_download_id'       => '',
						'settings'                 => maybe_serialize( $bdp_settings ),
					),
					array( '%s', '%s', '%s', '%s' )
				);
				if ( false == $single_layout_setting ) {
					$shortcode_id = 0;
				} else {
					$shortcode_id = $wpdb->insert_id;
				}
				$message = 'shortcode_duplicate_msg';
				if ( $shortcode_id > 0 ) {
					$message = 'shortcode_duplicate_msg';
					do_action( 'bdp_duplicate_single_download_layout_settings', $shortcode_id );
				} else {
					wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
				}
				$send = admin_url( 'admin.php?page=single_edd_download&action=edit&id=' . $shortcode_id );
				$send = add_query_arg( 'message', $message, $send );
				wp_redirect( $send );
				exit();
			}
		}
	}
	/**
	 * Save template at admin side
	 *
	 * @global object $wpdb
	 * @global array $bdp_settings
	 * @global string $bdp_table_name
	 * @global WP_Error $bdp_errors
	 * @global string $bdp_success
	 */
	public function bdp_save_admin_template() {
		global $wpdb, $bdp_settings, $bdp_table_name, $bdp_errors, $bdp_success;
		if ( isset( $_GET['page'] ) && ( 'layouts' === $_GET['page'] || 'single_layouts' === $_GET['page'] || 'archive_layouts' === $_GET['page'] || 'product_archive_layouts' === $_GET['page'] || 'add_shortcode' === $_GET['page'] ) ) {
			$user   = wp_get_current_user();
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			if ( 'layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_add_shortcode', $closed, true );
			}
			if ( 'single_layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_single_post', $closed, true );
			}
			if ( 'archive_layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_bdp_add_archive_layout', $closed, true );
			}
			if ( isset( $_GET['page'] ) && 'product_archive_layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_product_archive_layouts', $closed, true );
			}
			if ( isset( $_GET['page'] ) && 'edd_archive_layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_add_edd_archive', $closed, true );
			}
			if ( isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
				update_user_option( $user->ID, 'bdpclosedbdpboxes_single_edd_download', $closed, true );
			}
		}

		/** Save Blog Layout Template */
		if ( isset( $_GET['page'] ) && 'add_shortcode' === $_GET['page'] ) {
			if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
				$user   = wp_get_current_user();
				$closed = array( 'bdpgeneral' );
				$closed = array_filter( $closed );
				update_user_option( $user->ID, 'bdpclosedbdpboxes_add_shortcode', $closed, true );
			}
			if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) && isset( $_POST['bdp-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-submit-nonce'] ) ), 'bdp-shortcode-form-submit' ) ) {
				$bdp_settings = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$templates = array();
				if ( isset( $_POST['bdp-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-submit-nonce'] ) ), 'bdp-shortcode-form-submit' ) ) {
					if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
						if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
							$shortcode_id = sanitize_text_field( wp_unslash( $_GET['id'] ) );
						} else {
							$shortcode_id = '';
						}
						$bdp_settings = apply_filters( 'bdp_update_blog_layout_settings', $bdp_settings );
						$save         = $wpdb->update(
							$bdp_table_name,
							array(
								'shortcode_name' => isset( $_POST['unique_shortcode_name'] ) ? sanitize_text_field( wp_unslash( $_POST['unique_shortcode_name'] ) ) : '',
								'bdsettings'     => maybe_serialize( $bdp_settings ),
							),
							array( 'bdid' => $shortcode_id ),
							array( '%s', '%s' ),
							array( '%d' )
						);
						if ( false == $save ) {
							$bdp_errors = new WP_Error( 'save_error', esc_html__( 'No Changes Found.', 'blog-designer-pro' ) );
						} else {
							$templates['ID']           = isset( $_POST['blog_page_display'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_page_display'] ) ) : '';
							$templates['post_content'] = '[wp_blog_designer id="' . $shortcode_id . '"]';
							wp_update_post( $templates );
							if ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
								$bdp_success = esc_html__( 'Layout reset successfully.', 'blog-designer-pro' );
								do_action( 'bdp_reset_shortcode', $shortcode_id );
							} else {
								$bdp_success = esc_html__( 'Layout updated successfully. ', 'blog-designer-pro' );
								do_action( 'bdp_update_shortcode', $shortcode_id );
							}
							if ( isset( $_POST['blog_page_display'] ) && $_POST['blog_page_display'] > 0 ) {
								$bdp_success .= ' <a href="' . esc_url( get_the_permalink( sanitize_text_field( wp_unslash( $_POST['blog_page_display'] ) ) ) ) . '" target="_blank">' . esc_html__( 'View Layout', 'blog-designer-pro' ) . '</a>';
							}
						}
					} else {
						$bdp_settings = apply_filters( 'bdp_add_blog_layout_settings', $bdp_settings );
						if ( isset( $_POST['unique_shortcode_name'] ) ) {
							$shortcode_id = Bdp_Template::insert_layout( sanitize_text_field( wp_unslash( $_POST['unique_shortcode_name'] ) ), $bdp_settings );
						}
						if ( $shortcode_id > 0 ) {
							$message = 'shortcode_added_msg';
						} else {
							wp_die( esc_html__( 'Error in Adding shortcode.', 'blog-designer-pro' ) );
						}
						$templates['ID']           = isset( $_POST['blog_page_display'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_page_display'] ) ) : '';
						$templates['post_content'] = '[wp_blog_designer id="' . $shortcode_id . '"]';
						wp_update_post( $templates );
						$send = admin_url( 'admin.php?page=add_shortcode&action=edit&id=' . $shortcode_id );
						$send = add_query_arg( 'message', $message, $send );
						do_action( 'bdp_add_shortcode', $shortcode_id );
						wp_redirect( $send );
						exit();
					}
				} else {
					wp_redirect( '?page=layouts' );
				}
			}
		}

		/** Save Single Post */
		if ( isset( $_GET['page'] ) && 'single_post' === $_GET['page'] ) {
			if ( ! isset( $_GET['action'] ) || '' === $_GET['action'] ) {
				$user   = wp_get_current_user();
				$closed = array( 'bdpsinglegeneral' );
				$closed = array_filter( $closed );
				update_user_option( $user->ID, 'bdpclosedbdpboxes_single_post', $closed, true );
			}
			if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
				$bdp_settings = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$post_ids     = isset( $_POST['template_posts'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_posts'] ) ) ) : '';
				$single_table = $wpdb->prefix . 'bdp_single_layouts';
				if ( isset( $_POST['bdp-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-submit-nonce'] ) ), 'bdp-shortcode-form-submit' ) ) {
					if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
						if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
							$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
						} else {
							$shortcode_id = '';
						}
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$bdp_settings = apply_filters( 'bdp_update_single_layout_settings', $bdp_settings );
						$save         = $wpdb->update(
							$single_table,
							array(
								'single_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'  => sanitize_text_field( $categories ),
								'single_post_id'  => sanitize_text_field( $post_ids ),
								'settings'        => maybe_serialize( $bdp_settings ),
							),
							array( 'ID' => $shortcode_id ),
							array( '%s', '%s', '%s', '%s', '%s' ),
							array( '%d' )
						);
						if ( false == $save ) {
							$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating single page settings.', 'blog-designer-pro' ) );
						} elseif ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
								$bdp_success = esc_html__( 'Single layout reset successfully.', 'blog-designer-pro' );
								do_action( 'bdp_reset_single_page', $shortcode_id );
						} else {
							$bdp_success = esc_html__( 'Single layout updated successfully.', 'blog-designer-pro' );
							do_action( 'bdp_update_single_page', $shortcode_id );
						}
					} else {
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$insert = $wpdb->insert(
							$single_table,
							array(
								'single_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'  => sanitize_text_field( $categories ),
								'single_post_id'  => sanitize_text_field( $post_ids ),
								'settings'        => maybe_serialize( $bdp_settings ),
							),
							array( '%s', '%s', '%s', '%s' )
						);
						if ( false == $insert ) {
							wp_die( esc_html__( 'Error in creating single post layout.', 'blog-designer-pro' ) );
						} else {
							$message      = 'single_added_msg';
							$shortcode_id = $wpdb->insert_id;
						}
						$send = admin_url( 'admin.php?page=single_post&action=edit&id=' . $shortcode_id );
						$send = add_query_arg( 'message', $message, $send );
						do_action( 'bdp_add_single_layout', $shortcode_id );
						wp_redirect( $send );
						exit();
					}
				}
			}
		}

		/** Save Single Product Layout Template */
		if ( isset( $_GET['page'] ) && 'single_product' === $_GET['page'] ) {
			if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
				$user   = wp_get_current_user();
				$closed = array( 'bdpsinglegeneral' );
				$closed = array_filter( $closed );
				update_user_option( $user->ID, 'bdpclosedbdpboxes_single_product', $closed, true );
			}
			if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
				$bdp_settings = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$post_ids     = isset( $_POST['template_posts'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_posts'] ) ) ) : '';
				$single_table = $wpdb->prefix . 'bdp_single_product';
				if ( isset( $_POST['bdp-single_product-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-single_product-submit-nonce'] ) ), 'bdp-product-shortcode-form-submit' ) ) {
					if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
						if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
							$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
						} else {
							$shortcode_id = '';
						}
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$bdp_settings = apply_filters( 'bdp_update_single_layout_settings', $bdp_settings );
						$save         = $wpdb->update(
							$single_table,
							array(
								'single_product_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_product_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'          => sanitize_text_field( $categories ),
								'single_product_id'       => sanitize_text_field( $post_ids ),
								'settings'                => maybe_serialize( $bdp_settings ),
							),
							array( 'ID' => $shortcode_id ),
							array( '%s', '%s', '%s', '%s', '%s' ),
							array( '%d' )
						);
						if ( false == $save ) {
							$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating single page settings.', 'blog-designer-pro' ) );
						} elseif ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
								$bdp_success = esc_html__( 'Single layout reset successfully.', 'blog-designer-pro' );
								do_action( 'bdp_reset_single_page', $shortcode_id );
						} else {
							$bdp_success = esc_html__( 'Single layout updated successfully.', 'blog-designer-pro' );
							do_action( 'bdp_update_single_page', $shortcode_id );
						}
					} else {
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$bdp_settings = apply_filters( 'bdp_single_template_settings', $bdp_settings );
						$insert       = $wpdb->insert(
							$single_table,
							array(
								'single_product_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_product_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'          => sanitize_text_field( $categories ),
								'single_product_id'       => sanitize_text_field( $post_ids ),
								'settings'                => maybe_serialize( $bdp_settings ),
							),
							array( '%s', '%s', '%s', '%s' )
						);
						if ( false == $insert ) {
							wp_die( esc_html__( 'Error in creating single post layout.', 'blog-designer-pro' ) );
						} else {
							$message      = 'single_added_msg';
							$shortcode_id = $wpdb->insert_id;
						}
						$send = admin_url( 'admin.php?page=single_product&action=edit&id=' . $shortcode_id );
						$send = add_query_arg( 'message', $message, $send );
						do_action( 'bdp_add_single_product_layout', $shortcode_id );
						wp_redirect( $send );
						exit();
					}
				}
			}
		}

		/** Save Single Download Layout */
		if ( isset( $_GET['page'] ) && 'single_edd_download' === $_GET['page'] ) {
			if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
				$user   = wp_get_current_user();
				$closed = array( 'bdpsinglegeneral' );
				$closed = array_filter( $closed );
				update_user_option( $user->ID, 'bdpclosedbdpboxes_single_edd_download', $closed, true );
			}
			if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
				$bdp_settings = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				$post_ids     = isset( $_POST['template_posts'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_posts'] ) ) ) : '';
				$single_table = $wpdb->prefix . 'bdp_single_ed_download';
				if ( isset( $_POST['bdp-download-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-download-submit-nonce'] ) ), 'bdp-download-shortcode-form-submit' ) ) {

					if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
						if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
							$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
						} else {
							$shortcode_id = '';
						}
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$bdp_settings = apply_filters( 'bdp_update_single_layout_settings', $bdp_settings );
						$save         = $wpdb->update(
							$single_table,
							array(
								'single_download_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_download_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'           => sanitize_text_field( $categories ),
								'single_download_id'       => sanitize_text_field( $post_ids ),
								'settings'                 => maybe_serialize( $bdp_settings ),
							),
							array( 'ID' => $shortcode_id ),
							array( '%s', '%s', '%s', '%s', '%s' ),
							array( '%d' )
						);
						if ( false == $save ) {
							$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating single page settings.', 'blog-designer-pro' ) );
						} elseif ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
								$bdp_success = esc_html__( 'Single layout reset successfully.', 'blog-designer-pro' );
								do_action( 'bdp_reset_single_page', $shortcode_id );
						} else {
							$bdp_success = esc_html__( 'Single layout updated successfully.', 'blog-designer-pro' );
							do_action( 'bdp_update_single_page', $shortcode_id );
						}
					} else {
						if ( isset( $_POST['bdp_single_type'] ) && 'category' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
						} elseif ( isset( $_POST['bdp_single_type'] ) && 'tag' === $_POST['bdp_single_type'] ) {
							$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
						} else {
							$categories = '';
						}
						$bdp_settings = apply_filters( 'bdp_single_template_settings', $bdp_settings );
						$insert       = $wpdb->insert(
							$single_table,
							array(
								'single_download_name'     => isset( $_POST['single_layout_name'] ) ? sanitize_text_field( wp_unslash( $_POST['single_layout_name'] ) ) : '',
								'single_download_template' => sanitize_text_field( wp_unslash( $_POST['bdp_single_type'] ) ),
								'sub_categories'           => sanitize_text_field( $categories ),
								'single_download_id'       => sanitize_text_field( $post_ids ),
								'settings'                 => maybe_serialize( $bdp_settings ),
							),
							array( '%s', '%s', '%s', '%s' )
						);
						if ( false == $insert ) {
							wp_die( esc_html__( 'Error in creating single post layout.', 'blog-designer-pro' ) );
						} else {
							$message      = 'single_added_msg';
							$shortcode_id = $wpdb->insert_id;
						}
						$send = admin_url( 'admin.php?page=single_edd_download&action=edit&id=' . $shortcode_id );
						$send = add_query_arg( 'message', $message, $send );
						do_action( 'bdp_add_single_edd_download_layout', $shortcode_id );
						wp_redirect( $send );
						exit();
					}
				}
			}
		}
		/** Save Getting Started Page options */
		if ( isset( $_GET['page'] ) && 'bdp_getting_started' === $_GET['page'] ) {
			if ( isset( $_POST['savedata'] ) ) {
				$bdp_settings = $_POST;
				if ( isset( $_POST['bdp-singlefile-submit-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-singlefile-submit-nonce'] ) ), 'bdp-singlefile-form-submit' ) ) {
					$template        = 'bdp_templates/single/single.php';
					$singlefile_html = isset( $_POST['singlefile_html'] ) ? sanitize_textarea_field( wp_unslash( $_POST['singlefile_html'] ) ) : '';
					$bdp_settings    = apply_filters( 'bdp_update_single_file_settings', $bdp_settings );
					$save            = update_option( 'bdp_single_file_template', maybe_serialize( $bdp_settings ) );
					Bdp_Template::singlefile_save_template( $singlefile_html, $template );
					$bdp_success = esc_html__( 'Single flie updated successfully.', 'blog-designer-pro' );
					do_action( 'bdp_update_single_file' );
				}
			}
		}
	}

	/**
	 * Save Archive Template at admin side
	 *
	 * @global object $wpdb
	 * @global WP_Error $bdp_errors
	 * @global string $bdp_success
	 */
	public function bdp_save_admin_archive_template() {
		global $wpdb, $bdp_errors, $bdp_success;
		if ( isset( $_POST['bdp-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-archive-nonce'] ) ), 'bdp-archive-page-submit' ) || isset( $_POST['bdp-product-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-product-archive-nonce'] ) ), 'bdp-product-archive-page-submit' ) || isset( $_POST['bdp-download-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-download-archive-nonce'] ) ), 'bdp-download-archive-page-submit' ) ) {
			/** Save post Archive Layout  */
			if ( isset( $_GET['page'] ) && 'bdp_add_archive_layout' === $_GET['page'] ) {
				$archive_table = $wpdb->prefix . 'bdp_archives';
				$bdp_settings  = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
					$user   = wp_get_current_user();
					$closed = array( 'bdpgeneral' );
					$closed = array_filter( $closed );
					update_user_option( $user->ID, 'bdpclosedbdpboxes_bdp_add_archive_layout', $closed, true );
				}
				if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
					$templates = array();
					if ( isset( $_POST['bdp-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-archive-nonce'] ) ), 'bdp-archive-page-submit' ) ) {
						if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
							if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
								$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
							} else {
								$shortcode_id = '';
							}
							if ( isset( $_POST['custom_archive_type'] ) && 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( isset( $_POST['custom_archive_type'] ) && 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} elseif ( isset( $_POST['custom_archive_type'] ) && 'author_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_author'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_author'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_update_archive_layout_settings', $bdp_settings );
							$save         = $wpdb->update(
								$archive_table,
								array(
									'archive_name'     => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'sub_categories'   => sanitize_text_field( $categories ),
									'settings'         => maybe_serialize( $bdp_settings ),
								),
								array( 'ID' => $shortcode_id ),
								array( '%s', '%s', '%s', '%s' ),
								array( '%d' )
							);
							if ( false == $save ) {
								$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating archive settings.', 'blog-designer-pro' ) );
							} else {
								if ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
									do_action( 'bdp_reset_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Archive layout reset successfully.', 'blog-designer-pro' );
								} else {
									do_action( 'bdp_update_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Archive layout updated successfully.', 'blog-designer-pro' );
								}
								update_option( 'bdp_multi_author_selection', 0 );
							}
						} else {
							if ( 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} elseif ( isset( $_POST['custom_archive_type'] ) && 'author_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_author'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_author'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_add_archive_layout_settings', $bdp_settings );
							$insert       = $wpdb->insert(
								$archive_table,
								array(
									'archive_name'     => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'sub_categories'   => sanitize_text_field( $categories ),
									'settings'         => maybe_serialize( $bdp_settings ),
								),
								array( '%s', '%s', '%s', '%s' )
							);
							if ( false == $insert ) {
								wp_die( esc_html__( 'Error in creating archive layout.', 'blog-designer-pro' ) );
							} else {
								$message      = 'archive_added_msg';
								$shortcode_id = $wpdb->insert_id;
								update_option( 'bdp_multi_author_selection', 0 );
							}
							$send = admin_url( 'admin.php?page=bdp_add_archive_layout&action=edit&id=' . $shortcode_id );
							$send = add_query_arg( 'message', $message, $send );
							do_action( 'bdp_add_archive_layout', $shortcode_id );
							wp_redirect( $send );
							exit();
						}
					} else {
						wp_redirect( '?page=archive_layouts' );
					}
				}
			}
			/** Save Woocommerce Product Archive Layout  */
			if ( isset( $_GET['page'] ) && 'bdp_add_product_archive_layout' === $_GET['page'] ) {
				$archive_product_table = $wpdb->prefix . 'bdp_product_archives';
				$product_archive_table = $archive_product_table;
				$bdp_settings          = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
					$user   = wp_get_current_user();
					$closed = array( 'bdpgeneral' );
					$closed = array_filter( $closed );
					update_user_option( $user->ID, 'bdpclosedbdpboxes_bdp_add_product_archive_layout', $closed, true );
				}
				if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
					$templates = array();
					if ( isset( $_POST['bdp-product-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-product-archive-nonce'] ) ), 'bdp-product-archive-page-submit' ) ) {
						if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
							if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
								$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
							} else {
								$shortcode_id = '';
							}
							if ( isset( $_POST['custom_archive_type'] ) && 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( isset( $_POST['custom_archive_type'] ) && 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_update_archive_layout_settings', $bdp_settings );

							$save = $wpdb->update(
								$product_archive_table,
								array(
									'product_archive_name' => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'product_archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'product_sub_categories' => sanitize_text_field( $categories ),
									'settings'             => maybe_serialize( $bdp_settings ),
								),
								array( 'ID' => $shortcode_id ),
								array( '%s', '%s', '%s', '%s' ),
								array( '%d' )
							);
							if ( false == $save ) {
								$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating archive settings.', 'blog-designer-pro' ) );
							} else {
								if ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
									do_action( 'bdp_reset_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Product archive layout reset successfully.', 'blog-designer-pro' );
								} else {
									do_action( 'bdp_update_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Product archive layout updated successfully.', 'blog-designer-pro' );
								}
								update_option( 'bdp_multi_author_selection', 0 );
							}
						} else {
							if ( 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_add_product_archive_layout_settings', $bdp_settings );
							$insert       = $wpdb->insert(
								$archive_product_table,
								array(
									'product_archive_name' => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'product_archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'product_sub_categories' => $categories,
									'settings'             => maybe_serialize( $bdp_settings ),
								),
								array( '%s', '%s', '%s', '%s' )
							);
							if ( false == $insert ) {
								wp_die( esc_html__( 'Error in creating product archive layout.', 'blog-designer-pro' ) );
							} else {
								$message      = 'archive_added_msg';
								$shortcode_id = $wpdb->insert_id;
								update_option( 'bdp_multi_author_selection', 0 );
							}
							$send = admin_url( 'admin.php?page=bdp_add_product_archive_layout&action=edit&id=' . $shortcode_id );
							$send = add_query_arg( 'message', $message, $send );
							do_action( 'bdp_add_product_archive_layout', $shortcode_id );
							wp_redirect( $send );
							exit();
						}
					} else {
						wp_redirect( '?page=layouts' );
					}
				}
			}

			/** Save Download Archive Layout  */
			if ( isset( $_GET['page'] ) && 'add_edd_archive' === $_GET['page'] ) {
				$archive_table = $wpdb->prefix . 'bdp_edd_archives';
				$bdp_settings  = $_POST;
				if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
					foreach ( $bdp_settings as $single_key => $single_val ) {
						if ( is_array( $single_val ) ) {
							foreach ( $single_val as $s_key => $s_val ) {
								$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
							}
						} elseif ( 'custom_css' === $single_key ) {
								$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
						} elseif ( 'mail_content' === $single_key ) {
							$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
						} else {
							$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
						}
					}
				}
				if ( ! isset( $_GET['action'] ) || '' == $_GET['action'] ) {
					$user   = wp_get_current_user();
					$closed = array( 'bdpgeneral' );
					$closed = array_filter( $closed );
					update_user_option( $user->ID, 'bdpclosedbdpboxes_add_edd_archive', $closed, true );
				}
				if ( isset( $_POST['savedata'] ) || ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) ) {
					$templates = array();
					if ( isset( $_POST['bdp-download-archive-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp-download-archive-nonce'] ) ), 'bdp-download-archive-page-submit' ) ) {
						if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
							if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
								$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
							} else {
								$shortcode_id = '';
							}
							if ( isset( $_POST['custom_archive_type'] ) && 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( isset( $_POST['custom_archive_type'] ) && 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_update_archive_layout_settings', $bdp_settings );
							$save         = $wpdb->update(
								$archive_table,
								array(
									'download_archive_name' => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'download_archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'download_sub_categories' => sanitize_text_field( $categories ),
									'settings' => maybe_serialize( $bdp_settings ),
								),
								array( 'ID' => $shortcode_id ),
								array( '%s', '%s', '%s', '%s' ),
								array( '%d' )
							);
							if ( false == $save ) {
								$bdp_errors = new WP_Error( 'save_error', esc_html__( 'Error in updating archive settings.', 'blog-designer-pro' ) );
							} else {
								if ( isset( $_POST['resetdata'] ) && '' != $_POST['resetdata'] ) {
									do_action( 'bdp_reset_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Download product archive layout reset successfully.', 'blog-designer-pro' );
								} else {
									do_action( 'bdp_update_archive_page', $shortcode_id );
									$bdp_success = esc_html__( 'Download product archive layout updated successfully.', 'blog-designer-pro' );
								}
								update_option( 'bdp_multi_author_selection', 0 );
							}
						} else {
							if ( 'category_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_category'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_category'] ) ) ) : '';
							} elseif ( 'tag_template' === $_POST['custom_archive_type'] ) {
								$categories = isset( $_POST['template_tags'] ) ? implode( ',', array_map( 'sanitize_text_field', wp_unslash( $_POST['template_tags'] ) ) ) : '';
							} else {
								$categories = '';
							}
							$bdp_settings = apply_filters( 'bdp_add_download_archive_layout_settings', $bdp_settings );
							$insert       = $wpdb->insert(
								$archive_table,
								array(
									'download_archive_name' => isset( $_POST['archive_name'] ) ? sanitize_text_field( wp_unslash( $_POST['archive_name'] ) ) : '',
									'download_archive_template' => sanitize_text_field( wp_unslash( $_POST['custom_archive_type'] ) ),
									'download_sub_categories' => $categories,
									'settings' => maybe_serialize( $bdp_settings ),
								),
								array( '%s', '%s', '%s', '%s' )
							);
							if ( false == $insert ) {
								wp_die( esc_html__( 'Error in creating download archive post layout.', 'blog-designer-pro' ) );
							} else {
								$message      = 'archive_added_msg';
								$shortcode_id = $wpdb->insert_id;
								update_option( 'bdp_multi_author_selection', 0 );
							}
							$send = admin_url( 'admin.php?page=add_edd_archive&action=edit&id=' . $shortcode_id );
							$send = add_query_arg( 'message', $message, $send );
							do_action( 'bdp_add_edd_archive_layout', $shortcode_id );
							wp_redirect( $send );
							exit();
						}
					} else {
						wp_redirect( '?page=layouts' );
					}
				}
			}
		}
	}

	/**
	 * Capability to admin menu
	 *
	 * @return capability
	 */
	private function bdp_manage_blog_design_pro() {
		$manage_options_cap = apply_filters( 'bdp_manage_blog_designs_capability', 'manage_options' );
		return $manage_options_cap;
	}

	/**
	 * Delete All type of Layout
	 *
	 * @global object $wpdb
	 * @global string $bdp_table_name
	 */
	public function bdp_delete_admin_template() {
		global $wpdb, $bdp_table_name, $archive_table, $product_archive_table, $download_archive_table;
		/** Delete Blog Layout*/
		if ( isset( $_GET['page'] ) && 'layouts' === $_GET['page'] && isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$shortcode_id = '';
			}
			$bdp_table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';

			/*
			 * Delete Shortcode settings from database
			 */
			do_action( 'bdp_delete_shortcode', $shortcode_id );
			$bdp_is_delete = $wpdb->delete(
				$bdp_table_name,
				array( 'bdid' => $shortcode_id )
			);
		}
		/** Delete archive template */
		if ( isset( $_GET['page'] ) && 'archive_layouts' === $_GET['page'] && isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			$archive_table = $wpdb->prefix . 'bdp_archives';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$archive_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$archive_id = '';
			}
			do_action( 'bdp_delete_archive', $archive_id );
			$bdp_is_delete = $wpdb->delete(
				$archive_table,
				array( 'id' => $archive_id )
			);
		}
		/**
		 * Delete Product archive template
		 *
		 *  @since 2.6
		 */
		if ( isset( $_GET['page'] ) && 'product_archive_layouts' === $_GET['page'] && isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			$product_archive_table = $wpdb->prefix . 'bdp_product_archives';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$archive_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$archive_id = '';
			}
			do_action( 'bdp_delete_archive_product', $archive_id );
			$bdp_is_delete = $wpdb->delete(
				$product_archive_table,
				array( 'id' => $archive_id )
			);
		}
		/**
		 * Delete Download archive template
		 *
		 *  @since 2.7
		 */
		if ( isset( $_GET['page'] ) && 'edd_archive_layouts' === $_GET['page'] && isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {
			$download_archive_table = $wpdb->prefix . 'bdp_edd_archives';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$archive_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$archive_id = '';
			}

			/*
			 * Delete archive template settings from database
			 */
			do_action( 'bdp_delete_archive_download', $archive_id );
			$bdp_is_delete = $wpdb->delete(
				$download_archive_table,
				array( 'id' => $archive_id )
			);
		}
		/** Delete single template */
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) && isset( $_GET['page'] ) && 'single_layouts' === $_GET['page'] ) {
			$single_table = $wpdb->prefix . 'bdp_single_layouts';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$shortcode_id = '';
			}
			do_action( 'bdp_delete_single_layout', $shortcode_id );
			$bdp_single_delete = $wpdb->delete(
				$single_table,
				array( 'id' => $shortcode_id )
			);
		}
		/**
		 * Delete Single Product Layout
		 *
		 * @since 2.6
		 */
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) && isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] ) {
			$single_product_table = $wpdb->prefix . 'bdp_single_product';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$shortcode_id = '';
			}
			do_action( 'bdp_delete_product_single_layout', $shortcode_id );
			$bdp_single_delete = $wpdb->delete(
				$single_product_table,
				array( 'id' => $shortcode_id )
			);
		}
		/**
		 * Delete Single download Layout
		 *
		 * @since 2.7
		 */
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) && isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
			$single_download_table = $wpdb->prefix . 'bdp_single_ed_download';
			if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
				$shortcode_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$shortcode_id = '';
			}
			do_action( 'bdp_delete_single_download_layout', $shortcode_id );
			$bdp_single_delete = $wpdb->delete(
				$single_download_table,
				array( 'id' => $shortcode_id )
			);
		}
	}

	/**
	 * Multiple Deletion of shortcode
	 *
	 * @global object $wpdb
	 * @global string $bdp_table_name
	 */
	public function bdp_multiple_delete_admin_template() {
		global $wpdb, $bdp_table_name, $archive_table, $product_archive_table, $download_archive_table;
		$single_table          = $wpdb->prefix . 'bdp_single_layouts';
		$single_product_table  = $wpdb->prefix . 'bdp_single_product';
		$single_download_table = $wpdb->prefix . 'bdp_single_ed_download';
		if ( isset( $_POST['bdp_take_action_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp_take_action_nonce'] ) ), 'bdp_take_action' ) ) {
			/** Multiple Blog Layout Delete */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$shortcodes = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						if ( isset( $_GET['page'] ) ) {
							foreach ( $shortcodes as $shortcode ) {
								do_action( 'bdp_delete_shortcode', $shortcode );
								$wpdb->delete( $bdp_table_name, array( 'bdid' => $shortcode ) );
							}
						}
					}
				}
			}

			/** Multiple Delete single layouts */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$shortcodes = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							do_action( 'bdp_delete_single_layout', $shortcode );
							$wpdb->delete( $single_table, array( 'id' => $shortcode ) );
						}
					}
				}
			}

			/**
			 * Multiple Delete single Product layouts
			 *
			 * @since 2.6
			 */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$shortcodes = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							do_action( 'bdp_delete_single_layout', $shortcode );
							$wpdb->delete( $single_product_table, array( 'id' => $shortcode ) );
						}
					}
				}
			}
			/**
			 * Multiple delete single download layout
			 *
			 * @since 2.7
			 */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$shortcodes = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							do_action( 'bdp_delete_single_layout', $shortcode );
							$wpdb->delete( $single_download_table, array( 'id' => $shortcode ) );
						}
					}
				}
			}
			/**
			 * Multiple delete archive layout
			 */
			if ( isset( $_POST['take_action'] ) && 'archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$archives = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							do_action( 'bdp_delete_archive', $archive );
							$wpdb->delete( $archive_table, array( 'id' => $archive ) );
						}
					}
				}
			}
			/**
			 * Multiple delete Archive Product layout
			 *
			 * @since 2.6
			 */
			if ( isset( $_POST['take_action'] ) && 'product_archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$archives = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							do_action( 'bdp_delete_archive_product', $archive );
							$wpdb->delete( $product_archive_table, array( 'id' => $archive ) );
						}
					}
				}
			}

			/**
			 * Multiple delete Download archive layout
			 *
			 * @since 2.7
			 */
			if ( isset( $_POST['take_action'] ) && 'edd_archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'delete_pr' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
						$archives = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							do_action( 'bdp_delete_archive_download', $archive );
							$wpdb->delete( $download_archive_table, array( 'id' => $archive ) );
						}
					}
				}
			}
		}
	}

	/**
	 * Export Layout
	 *
	 * @since 2.7
	 */
	public function bdp_multiple_export_admin_template() {
		global $wpdb, $bdp_table_name, $archive_table, $product_archive_table, $download_archive_table;
		$single_table          = $wpdb->prefix . 'bdp_single_layouts';
		$single_product_table  = $wpdb->prefix . 'bdp_single_product';
		$single_download_table = $wpdb->prefix . 'bdp_single_ed_download';
		if ( isset( $_POST['bdp_take_action_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp_take_action_nonce'] ) ), 'bdp_take_action' ) ) {
			/** Export Blog Layout */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'layouts' === $_GET['page'] ) {
						$bdp_table     = $wpdb->prefix . 'blog_designer_pro_shortcodes';
						$export_layout = array();
						$shortcodes    = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							$get_data = '';
							if ( is_numeric( $shortcode ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}blog_designer_pro_shortcodes WHERE bdid = %s", $shortcode ), ARRAY_A );
							}
							do_action( 'bdp_export_blog_layout_settings', $shortcode );
							if ( ! empty( $get_data ) ) {
								$bdsettings                      = maybe_unserialize( $get_data['bdsettings'] );
								$bdsettings['blog_page_display'] = '0';
								$get_data['bdsettings']          = maybe_serialize( $bdsettings );
								$export_layout[]                 = $get_data;
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_layouts.txt', $output );
						}
					}
				}
			}
			/** Export Single Post Layout */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'single_layouts' === $_GET['page'] ) {
						$single_table  = $wpdb->prefix . 'bdp_single_layouts';
						$export_layout = array();
						$shortcodes    = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							$get_data = '';
							if ( is_numeric( $shortcode ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_layouts WHERE id = %s", $shortcode ), ARRAY_A );
							}
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
								do_action( 'bdp_export_single_layout_settings', $shortcode );
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_single_layouts.txt', $output );
						}
					}
				}
			}
			/**
			 * Export Single Product Layout
			 *
			 * @since 2.6
			 */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] ) {
						$export_layout        = array();
						$shortcodes           = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						$single_product_table = $wpdb->prefix . 'bdp_single_product';
						foreach ( $shortcodes as $shortcode ) {
							$get_data = '';
							if ( is_numeric( $shortcode ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_product WHERE id = %s", $shortcode ), ARRAY_A );
							}
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
								do_action( 'bdp_export_single_product_settings', $shortcode );
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_single_product.txt', $output );
						}
					}
				}
			}
			/**
			 * Export Single Download Layout
			 *
			 * @since 2.7
			 */
			if ( isset( $_POST['take_action'] ) && isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-action-top2'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top2'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'single_edd_layouts' === $_GET['page'] ) {
						$export_layout = array();
						$shortcodes    = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $shortcodes as $shortcode ) {
							$get_data = '';
							if ( is_numeric( $shortcode ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_single_ed_download WHERE id = %s", $shortcode ), ARRAY_A );
							}
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
								do_action( 'bdp_export_single_download_settings', $shortcode );
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_single_ed_download.txt', $output );
						}
					}
				}
			}
			/** Export archive post layout */
			if ( isset( $_POST['take_action'] ) && 'archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'archive_layouts' === $_GET['page'] ) {
						$export_layout = array();
						$archives      = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							$get_data = '';
							if ( is_numeric( $archive ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_archives WHERE id = %s", $archive ), ARRAY_A );
							}
							do_action( 'bdp_export_archive_layout_settings', $archive );
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_archive_layouts.txt', $output );
						}
					}
				}
			}

			/**
			 * Export Product Archive Layout
			 *
			 * @since 2.6
			 */
			if ( isset( $_POST['take_action'] ) && 'product_archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'product_archive_layouts' === $_GET['page'] ) {
						$export_layout = array();
						$archives      = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							$get_data = '';
							if ( is_numeric( $archive ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_product_archives WHERE id = %s", $archive ), ARRAY_A );
							}
							do_action( 'bdp_export_product_archive_layout_settings', $archive );
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_product_archive_layouts.txt', $output );
						}
					}
				}
			}

			/**
			 * Export Download Archive Layout
			 *
			 * @since 2.7
			 * @return void
			 */
			if ( isset( $_POST['take_action'] ) && 'edd_archive_layouts' === $_GET['page'] ) {
				if ( ( isset( $_POST['bdp-action-top'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-action-top'] ) ) ) ) || ( isset( $_POST['bdp-archive-action'] ) && 'bdp_export' === esc_html( sanitize_text_field( wp_unslash( $_POST['bdp-archive-action'] ) ) ) ) ) {
					if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) && isset( $_GET['page'] ) && 'edd_archive_layouts' === $_GET['page'] ) {
						$export_layout = array();
						$archives      = array_map( 'sanitize_text_field', wp_unslash( $_POST['chk_remove_all'] ) );
						foreach ( $archives as $archive ) {
							$get_data = '';
							if ( is_numeric( $archive ) ) {
								$get_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bdp_edd_archives WHERE id = %s", $archive ), ARRAY_A );
							}
							do_action( 'bdp_export_download_archive_layout_settings', $archive );
							if ( ! empty( $get_data ) ) {
								$export_layout[] = $get_data;
							}
						}
						if ( count( $export_layout ) > 0 ) {
							$output = base64_encode( maybe_serialize( $export_layout ) );
							$this->save_as_txt_file( 'bdp_download_archive_layouts.txt', $output );
						}
					}
				}
			}
		}
	}

	/**
	 * Save as txt file
	 *
	 * @param string $file_name file.
	 * @param string $output output.
	 * @return void
	 */
	public function save_as_txt_file( $file_name, $output ) {
		header( 'Content-type: application/text', true, 200 );
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
		echo esc_attr( $output );
		exit;
	}
	/**
	 * Add shortcode in page
	 *
	 * @global type $pagenow
	 * @global object $wpdb
	 * @return void
	 */
	public function bdp_admin_footer() {
		global $pagenow;
		// Only run in post/page creation and edit screens.
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
			global $wpdb;
			// Get the slider information.
			$shortcodes = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ORDER BY bdid DESC' );
			?>
			<script type="text/javascript">jQuery(document).ready(function(){(function($){jQuery('#insertBlogdesignerShortcode').on('click',function () {var id=jQuery('#blogdesigner-select option:selected').val();window.send_to_editor('[wp_blog_designer id="' + id + '"]');tb_remove()})}(jQuery))});</script>
			<div id="choose-blogdesigner-shortcode" style="display: none;">
				<div class="wrap">
					<?php
					if ( count( $shortcodes ) ) {
						echo "<h3 style='margin-bottom: 20px;'>" . esc_html__( 'Insert Blog Designer Shortcode', 'blog-designer-pro' ) . '</h3>';
						echo "<select id='blogdesigner-select'>";
						echo '<option disabled=disabled>' . esc_html__( 'Choose shortcode', 'blog-designer-pro' ) . '</option>';
						foreach ( $shortcodes as $shortcode ) {
							if ( '' != $shortcode->shortcode_name ) {
								$shortcode_name = $shortcode->shortcode_name;
							} else {
								$shortcode_name = esc_html__( 'no title', 'blog-designer-pro' );
							}
							echo "<option value='" . esc_attr( $shortcode->bdid ) . "'>" . esc_attr( $shortcode_name ) . '</option>';
						}
						echo '</select>';
						echo "<button class='button primary' id='insertBlogdesignerShortcode'>" . esc_html__( 'Insert Shortcode', 'blog-designer-pro' ) . '</button>';
					} else {
						esc_html_e( 'No shortcodes found', 'blog-designer-pro' );
					}
					?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Add add shortcode button
	 *
	 * @param html $context context.
	 * @global string $pagenow page now.
	 * @return String Button above visual text editor
	 */
	public function bdp_insert_button( $context ) {
		global $pagenow;
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
			$context .= '<a href="#TB_inline?&inlineId=choose-blogdesigner-shortcode" class="thickbox button" title="' .
					esc_attr__( 'Select blog designer shortcode', 'blog-designer-pro' ) .
					'"><span class="wp-media-buttons-icon" style="background: url(' . BLOGDESIGNERPRO_URL .
					'/public/images/blog-designer-pro.png); background-repeat: no-repeat; background-position: left bottom;background-size:90% auto;"></span> ' .
					esc_html__( 'Add Blog Designer Shortcode', 'blog-designer-pro' ) . '</a>';
		}
		return $context;
	}

	/**
	 * Add per page option in screen option in Blog Layout templates list
	 *
	 * @global string $bdp_screen_option_page
	 * @return void
	 */
	public function bdp_screen_options() {
		global $bdp_screen_option_page;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_option_page ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page',
		);
		add_screen_option( 'per_page', $args );
	}

	/**
	 * Add per page option in screen option in archive list
	 *
	 * @global string $bdp_screen_option_archive_page
	 * @return void
	 */
	public function bdp_screen_options_archive() {
		global $bdp_screen_option_archive_page;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_option_archive_page ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page',
		);
		add_screen_option( 'per_page', $args );
	}

	/**
	 * Sdd per page option in screen option in easy digital download archive list
	 *
	 * @global string $bdp_screen_edd_archive
	 * @since 2.7
	 * @return void
	 */
	public function bdp_screen_options_edd_archive() {
		global $bdp_screen_edd_archive;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_edd_archive ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page_download',
		);
		add_screen_option( 'per_page', $args );
	}

	/**
	 * Add per page option in screen option in product archive list
	 *
	 * @global string $bdp_screen_product_archive
	 * @since 2.6
	 * @return void
	 */
	public function bdp_screen_options_product_archive() {
		global $bdp_screen_product_archive;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_product_archive ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page_product',
		);
		add_screen_option( 'per_page', $args );
	}

	/**
	 * Add per page option in screen option in easy digital download single list
	 *
	 * @global string $bdp_screen_single_edd
	 * @since 2.7
	 * @return void
	 */
	public function bdp_screen_options_single_download() {
		global $bdp_screen_single_edd;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_single_edd ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_screen_single_edd',
		);
		add_screen_option( 'per_page', $args );
	}

	/**
	 * Add per page option in screen option in single product list
	 *
	 * @global string $bdp_screen_single_product
	 * @since 2.6
	 * @return void
	 */
	public function bdp_screen_options_single_product() {
		global $bdp_screen_single_product;
		$screen = get_current_screen();
		// get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_screen_single_product ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page_product_single',
		);
		add_screen_option( 'per_page', $args );
	}
	/**
	 *
	 * Aadd per page option in screen option in single list
	 *
	 * @global string $bdp_single_screen
	 * @return void
	 */
	public function bdp_screen_options_single() {
		global $bdp_single_screen;
		$screen = get_current_screen();
		// Get out of here if we are not on our settings page.
		if ( ! is_object( $screen ) || $screen->id != $bdp_single_screen ) {
			return;
		}
		$args = array(
			'label'   => esc_html__( 'Number of items per page:', 'blog-designer-pro' ),
			'default' => 10,
			'option'  => 'bdp_per_page_single',
		);
		add_screen_option( 'per_page', $args );
	}
	/**
	 * Validate blog layout screen options on update.
	 *
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option( $status, $option, $value ) {
		if ( 'bdp_per_page' === $option ) {
			return $value;
		}
	}
	/**
	 * Validate single post layout screen options on update.
	 *
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option_single( $status, $option, $value ) {
		if ( 'bdp_per_page_single' === $option ) {
			return $value;
		}
	}
	/**
	 * Validate archive product layout screen options on update.
	 *
	 * @since 2.6
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option_archive_product( $status, $option, $value ) {
		if ( 'bdp_per_page_product' === $option ) {
			return $value;
		}
	}

	/**
	 * Validate archive easy digital download layout screen options on update.
	 *
	 * @since 2.7
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option_archive_download( $status, $option, $value ) {
		if ( 'bdp_per_page_download' === $option ) {
			return $value;
		}
	}

	/**
	 * Validate single easy digital download layout screen options on update.
	 *
	 * @since 2.7
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option_single_download( $status, $option, $value ) {
		if ( 'bdp_screen_single_edd' === $option ) {
			return $value;
		}
	}
	/**
	 * Validate single product layout screen options on update.
	 *
	 * @since 2.6
	 * @param bool   $status   Whether to save or skip saving the screen option value. Default false.
	 * @param string $option The option name.
	 * @param int    $value  The number of rows to use.
	 * @return type
	 */
	public function bdp_set_screen_option_single_product( $status, $option, $value ) {
		if ( 'bdp_per_page_single_product' === $option ) {
			return $value;
		}
	}
	/**
	 * Admin notice layouts notice dismiss
	 *
	 * @return void
	 */
	public function bdp_admin_notice_dismiss() {
		?>
		<script id="bdp_admin_notice_dismiss" type="text/javascript">jQuery(document).ready(function(){jQuery('.bdp-admin-notice-pro-layouts').on('click',function(){jQuery.ajax({type:'POST',url:ajaxurl,data:{action:'bdp_layouts_notice_dismissible'}})})});</script>
		<?php
	}

	/**
	 * Import layouts
	 *
	 * @global string $import_success
	 * @global object $wpdb
	 * @global string $import_error
	 * @return void
	 */

	public function bdp_upload_import_file() {
		if ( ! empty( $_POST ) && ! empty( $_FILES['bdp_import'] ) && check_admin_referer( 'bdp_import', 'bdp_import_nonce' ) ) {
			// check_admin_referer prints fail page and dies.
			global $import_success, $wpdb, $import_error;
			// Uploaded file.
			$uploaded_file = $_FILES['bdp_import'];
			if ( isset( $_POST['layout_import_types'] ) && '' == $_POST['layout_import_types'] ) {
				$import_error = esc_html__( 'You must have to select import type', 'blog-designer-pro' );
				return;
			}
			// Check file type.
			$mimes        = array(
				'txt' => 'text/plain',
			);
			$bdp_filetype = wp_check_filetype( $uploaded_file['name'], $mimes );
			if ( 'txt' != $bdp_filetype['ext'] && ! wp_match_mime_types( 'txt', $bdp_filetype['type'] ) ) {
				$import_error = esc_html__( 'You must upload a .txt file generated by this plugin.', 'blog-designer-pro' );
				return;
			}
			// Upload file and check uploading error.
			$file_data = wp_handle_upload(
				$uploaded_file,
				array(
					'test_type' => false,
					'test_form' => false,
				)
			);
			if ( isset( $file_data['error'] ) ) {
				$import_error = $file_data['error'];
				return;
			}
			// Check file exists or not.
			if ( ! file_exists( $file_data['file'] ) ) {

				$import_error = esc_html__( 'Import file could not be found. Please try again.', 'blog-designer-pro' );
				return;
			}
			$content = $this->import_layouts( $file_data['file'] );
			if ( $content ) {
				/** Import blog layout */
				if ( isset( $_POST['layout_import_types'] ) && 'blog_layouts' === $_POST['layout_import_types'] ) {
					$bdp_table_name = $wpdb->prefix . 'blog_designer_pro_shortcodes';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $bdp_table_name ) ) == $bdp_table_name ) {
						foreach ( $content as $single_content ) {
							$shortcode_name = isset( $single_content['shortcode_name'] ) ? $single_content['shortcode_name'] : '';
							$bdsettings     = isset( $single_content['bdsettings'] ) ? maybe_unserialize( $single_content['bdsettings'] ) : '';
							if ( isset( $bdsettings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdsettings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdsettings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdsettings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdsettings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$blog_layout_id = $wpdb->insert(
								$bdp_table_name,
								array(
									'shortcode_name' => sanitize_text_field( $shortcode_name ),
									'bdsettings'     => maybe_serialize( $bdsettings ),
								)
							);
							do_action( 'bdp_import_blog_layout_settings', $shortcode_name );
						}
						$import_success = esc_html__( 'Blog Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}
				/** Import archive post layout */
				if ( isset( $_POST['layout_import_types'] ) && 'archive_layouts' === $_POST['layout_import_types'] ) {
					$table_name = $wpdb->prefix . 'bdp_archives';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) == $table_name ) {
						foreach ( $content as $single_content ) {
							$archive_name     = isset( $single_content['archive_name'] ) ? $single_content['archive_name'] : '';
							$archive_template = isset( $single_content['archive_template'] ) ? $single_content['archive_template'] : '';
							$sub_categories   = isset( $single_content['sub_categories'] ) ? $single_content['sub_categories'] : '';
							$bdp_settings     = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							if ( 'search_template' === $archive_template || 'date_template' === $archive_template ) {
								$archives_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_archives WHERE archive_template = %s", $archive_template ) );
								$archive_table  = $wpdb->prefix . 'bdp_archives';

								if ( $archives_count > 0 ) {
									$wpdb->update(
										$archive_table,
										array(
											'archive_name' => sanitize_text_field( $archive_name ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'settings'     => maybe_serialize( $bdp_settings ),
										),
										array( 'archive_template' => $archive_template )
									);
								} else {
									$wpdb->insert(
										$table_name,
										array(
											'archive_name' => sanitize_text_field( $archive_name ),
											'archive_template' => sanitize_text_field( $archive_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'settings'     => maybe_serialize( $bdp_settings ),
										)
									);
								}
							} else {
								$archives_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_archives WHERE sub_categories = %s", $sub_categories ) );
								if ( $archives_count > 0 ) {
									$archive_table = $wpdb->prefix . 'bdp_archives';
									$wpdb->update(
										$archive_table,
										array(
											'archive_name' => sanitize_text_field( $archive_name ),
											'archive_template' => sanitize_text_field( $archive_template ),
											'settings'     => maybe_serialize( $bdp_settings ),
										),
										array( 'sub_categories' => $sub_categories )
									);
								} else {
									$wpdb->insert(
										$table_name,
										array(
											'archive_name' => sanitize_text_field( $archive_name ),
											'archive_template' => sanitize_text_field( $archive_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'settings'     => maybe_serialize( $bdp_settings ),
										)
									);
								}
							}
						}
						do_action( 'bdp_import_archive_layout_settings', $archive_name );
						$import_success = esc_html__( 'Archive Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}

				/**
				 * Import Product archive layout
				 *
				 * @since 2.6
				 */
				if ( isset( $_POST['layout_import_types'] ) && 'product_archive_layouts' === $_POST['layout_import_types'] ) {
					$archive_product_table = $wpdb->prefix . 'bdp_product_archives';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $archive_product_table ) ) == $archive_product_table ) {
						foreach ( $content as $single_content ) {
							$archive_name     = isset( $single_content['product_archive_name'] ) ? $single_content['product_archive_name'] : '';
							$archive_template = isset( $single_content['product_archive_template'] ) ? $single_content['product_archive_template'] : '';
							$sub_categories   = isset( $single_content['product_sub_categories'] ) ? $single_content['product_sub_categories'] : '';
							$bdp_settings     = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$archives_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_product_archives WHERE product_sub_categories = %s ", $sub_categories ) );
							if ( $archives_count > 0 ) {

								$wpdb->update(
									$archive_product_table,
									array(
										'product_archive_name' => sanitize_text_field( $archive_name ),
										'product_archive_template' => sanitize_text_field( $archive_template ),
										'settings' => maybe_serialize( $bdp_settings ),
									),
									array( 'product_sub_categories' => $sub_categories )
								);
							} else {
								$wpdb->insert(
									$archive_product_table,
									array(
										'product_archive_name' => sanitize_text_field( $archive_name ),
										'product_archive_template' => sanitize_text_field( $archive_template ),
										'product_sub_categories' => sanitize_text_field( $sub_categories ),
										'settings' => maybe_serialize( $bdp_settings ),
									)
								);
							}
						}
						do_action( 'bdp_import_archive_layout_settings', $archive_name );
						$import_success = esc_html__( 'Archive Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}
				/**
				 * Import Download Archive layout
				 *
				 * @since 2.7
				 */
				if ( isset( $_POST['layout_import_types'] ) && 'download_archive_layouts' === $_POST['layout_import_types'] ) {
					$download_archive_table = $wpdb->prefix . 'bdp_edd_archives';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $download_archive_table ) ) == $download_archive_table ) {
						foreach ( $content as $single_content ) {
							$archive_name     = isset( $single_content['download_archive_name'] ) ? $single_content['download_archive_name'] : '';
							$archive_template = isset( $single_content['download_archive_template'] ) ? $single_content['download_archive_template'] : '';
							$sub_categories   = isset( $single_content['download_sub_categories'] ) ? $single_content['download_sub_categories'] : '';
							$bdp_settings     = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							$archives_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_edd_archives WHERE download_sub_categories = %s", $sub_categories ) );
							if ( $archives_count > 0 ) {
								$wpdb->update(
									$download_archive_table,
									array(
										'download_archive_name' => sanitize_text_field( $archive_name ),
										'download_archive_template' => sanitize_text_field( $archive_template ),
										'settings' => maybe_serialize( $bdp_settings ),
									),
									array( 'download_sub_categories' => $sub_categories )
								);
							} else {
								$wpdb->insert(
									$download_archive_table,
									array(
										'download_archive_name' => sanitize_text_field( $archive_name ),
										'download_archive_template' => sanitize_text_field( $archive_template ),
										'download_sub_categories' => sanitize_text_field( $sub_categories ),
										'settings' => maybe_serialize( $bdp_settings ),
									)
								);
							}
						}
						do_action( 'bdp_import_archive_layout_settings', $archive_name );
						$import_success = esc_html__( 'Archive Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}

				/**
				 * Import Single post layouts
				 */
				if ( isset( $_POST['layout_import_types'] ) && 'single_layouts' === $_POST['layout_import_types'] ) {
					$single_table = $wpdb->prefix . 'bdp_single_layouts';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_table ) ) == $single_table ) {
						foreach ( $content as $single_content ) {
							$single_name     = isset( $single_content['single_name'] ) ? $single_content['single_name'] : '';
							$single_template = isset( $single_content['single_template'] ) ? $single_content['single_template'] : '';
							$sub_categories  = isset( $single_content['sub_categories'] ) ? $single_content['sub_categories'] : '';
							$single_post_id  = isset( $single_content['single_post_id'] ) ? $single_content['single_post_id'] : '';
							$bdp_settings    = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							do_action( 'bdp_import_single_layout_settings', $single_name );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							if ( 'all' === $single_template ) {
								$single_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_layouts WHERE single_template = %s", $single_template ) );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_table,
										array(
											'single_name' => sanitize_text_field( $single_name ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_post_id' => sanitize_text_field( $single_post_id ),
											'settings'    => maybe_serialize( $bdp_settings ),
										),
										array( 'single_template' => $single_template )
									);
								} else {
									$wpdb->insert(
										$single_table,
										array(
											'single_name' => sanitize_text_field( $single_name ),
											'single_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_post_id' => sanitize_text_field( $single_post_id ),
											'settings'    => maybe_serialize( $bdp_settings ),
										)
									);
								}
							} else {
								$single_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_layouts WHERE single_post_id = %d", $single_post_id ) );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_table,
										array(
											'single_name' => sanitize_text_field( $single_name ),
											'single_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_post_id' => sanitize_text_field( $single_post_id ),
											'settings'    => maybe_serialize( $bdp_settings ),
										),
										array( 'single_post_id' => $single_post_id )
									);
								} else {
									$wpdb->insert(
										$single_table,
										array(
											'single_name' => sanitize_text_field( $single_name ),
											'single_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_post_id' => sanitize_text_field( $single_post_id ),
											'settings'    => maybe_serialize( $bdp_settings ),
										)
									);
								}
							}
						}
						$import_success = esc_html__( 'Single Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}

				/**
				 * Import Single Product layouts
				 *
				 * @since 2.6
				 */
				if ( isset( $_POST['layout_import_types'] ) && 'product_single_layouts' === $_POST['layout_import_types'] ) {
					$single_product_table = $wpdb->prefix . 'bdp_single_product';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_product_table ) ) == $single_product_table ) {
						foreach ( $content as $single_content ) {
							$single_name     = isset( $single_content['single_product_name'] ) ? $single_content['single_product_name'] : '';
							$single_template = isset( $single_content['single_product_template'] ) ? $single_content['single_product_template'] : '';
							$sub_categories  = isset( $single_content['sub_categories'] ) ? $single_content['sub_categories'] : '';
							$single_post_id  = isset( $single_content['single_product_id'] ) ? $single_content['single_product_id'] : '';
							$bdp_settings    = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							do_action( 'bdp_import_single_layout_settings', $single_name );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							if ( 'all' === $single_template ) {
								$single_count = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_product WHERE single_product_template = %s", $single_template );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_product_table,
										array(
											'single_product_name' => sanitize_text_field( $single_name ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_product_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										),
										array( 'single_product_template' => $single_template )
									);
								} else {
									$wpdb->insert(
										$single_product_table,
										array(
											'single_product_name' => sanitize_text_field( $single_name ),
											'single_product_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_product_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										)
									);
								}
							} else {
								$single_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_product WHERE single_product_id = %d", $single_post_id ) );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_product_table,
										array(
											'single_product_name' => sanitize_text_field( $single_name ),
											'single_product_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_product_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										),
										array( 'single_product_id' => $single_post_id )
									);
								} else {
									$wpdb->insert(
										$single_product_table,
										array(
											'single_product_name' => sanitize_text_field( $single_name ),
											'single_product_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_product_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										)
									);
								}
							}
						}
						$import_success = esc_html__( 'Single Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}
				/**
				 * Import Single Download layouts
				 *
				 * @since 2.7
				 */
				if ( isset( $_POST['layout_import_types'] ) && 'download_single_layouts' === $_POST['layout_import_types'] ) {
					$single_download_table = $wpdb->prefix . 'bdp_single_ed_download';
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $single_download_table ) ) == $single_download_table ) {
						foreach ( $content as $single_content ) {
							$single_name     = isset( $single_content['single_download_name'] ) ? $single_content['single_download_name'] : '';
							$single_template = isset( $single_content['single_download_template'] ) ? $single_content['single_download_template'] : '';
							$sub_categories  = isset( $single_content['sub_categories'] ) ? $single_content['sub_categories'] : '';
							$single_post_id  = isset( $single_content['single_download_id'] ) ? $single_content['single_download_id'] : '';
							$bdp_settings    = isset( $single_content['settings'] ) ? maybe_unserialize( $single_content['settings'] ) : '';
							do_action( 'bdp_import_single_download_layout_settings', $single_name );
							if ( isset( $bdp_settings ) && ! empty( $bdp_settings ) ) {
								foreach ( $bdp_settings as $single_key => $single_val ) {
									if ( is_array( $single_val ) ) {
										foreach ( $single_val as $s_key => $s_val ) {
											$bdp_settings[ $single_key ][ $s_key ] = sanitize_text_field( $s_val );
										}
									} elseif ( 'custom_css' === $single_key ) {
											$bdp_settings[ $single_key ] = wp_strip_all_tags( $single_val );
									} elseif ( 'mail_content' === $single_key ) {
										$bdp_settings[ $single_key ] = wp_kses( $single_val, self::args_kses() );
									} else {
										$bdp_settings[ $single_key ] = sanitize_text_field( $single_val );
									}
								}
							}
							if ( 'all' === $single_template ) {
								$single_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_template = %s", $single_template ) );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_download_table,
										array(
											'single_download_name' => sanitize_text_field( $single_name ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_download_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										),
										array( 'single_download_template' => $single_template )
									);
								} else {
									$wpdb->insert(
										$single_download_table,
										array(
											'single_download_name' => sanitize_text_field( $single_name ),
											'single_download_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_download_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										)
									);
								}
							} else {
								$single_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(`id`) FROM {$wpdb->prefix}bdp_single_ed_download WHERE single_download_id = %d", $single_post_id ) );
								if ( $single_count > 0 ) {
									$wpdb->update(
										$single_download_table,
										array(
											'single_download_name' => sanitize_text_field( $single_name ),
											'single_download_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_download_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										),
										array( 'single_download_id' => $single_post_id )
									);
								} else {
									$wpdb->insert(
										$single_download_table,
										array(
											'single_download_name' => sanitize_text_field( $single_name ),
											'single_download_template' => sanitize_text_field( $single_template ),
											'sub_categories' => sanitize_text_field( $sub_categories ),
											'single_download_id' => sanitize_text_field( $single_post_id ),
											'settings' => maybe_serialize( $bdp_settings ),
										)
									);
								}
							}
						}
						$import_success = esc_html__( 'Single Layout imported successfully', 'blog-designer-pro' );
					} else {
						$import_error = esc_html__( 'Table not found. Please try again.', 'blog-designer-pro' );
						return;
					}
				}
			}
		}
	}
	/**
	 * Import layouts
	 *
	 * @param string $file file.
	 * @return maybe_unserialized content
	 */
	public function import_layouts( $file ) {
		global $import_error;
		if ( file_exists( $file ) ) {
			$file_content = $this->bdp_file_contents( $file );
			if ( isset( $_POST['bdp_import_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bdp_import_nonce'] ) ), 'bdp_import' ) ) {
				if ( is_serialized( base64_decode( $file_content ) ) ) {
					$unserialized_content = maybe_unserialize( base64_decode( $file_content ) );
					if ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'blog_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['bdid'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'archive_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['archive_name'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'single_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['single_name'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'product_archive_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['product_archive_name'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'product_single_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['single_product_name'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'download_archive_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['download_archive_name'] ) ) {
						return $unserialized_content;
					} elseif ( $unserialized_content && isset( $_POST['layout_import_types'] ) && 'download_single_layouts' == $_POST['layout_import_types'] && ! empty( $unserialized_content[0]['single_download_name'] ) ) {
						return $unserialized_content;
					} else {
						$import_error = esc_html__( 'Please check your file format and file type. Please try again.', 'blog-designer-pro' );
						return;
					}
				} else {
					$import_error = esc_html__( 'Import file is empty. Please try again.', 'blog-designer-pro' );
					return;
				}
			}
		} else {

			$import_error = esc_html__( 'Import file could not be found. Please try again.', 'blog-designer-pro' );
			return;
		}
	}
	/**
	 * File Content
	 *
	 * @param string $path path.
	 * @return string $bdp_content
	 */
	public function bdp_file_contents( $path ) {
		$bdp_content = '';
		if ( function_exists( 'realpath' ) ) {
			$filepath = realpath( $path );
		}
		if ( ! $filepath || ! @is_file( $filepath ) ) {
			return '';
		}
		if ( ini_get( 'allow_url_fopen' ) ) {
			$bdp_file_method = 'fopen';
		} else {
			$bdp_file_method = 'file_get_contents';
		}
		if ( 'fopen' === $bdp_file_method ) {
			$bdp_handle = fopen( $filepath, 'rb' );
			if ( false != $bdp_handle ) {
				while ( ! feof( $bdp_handle ) ) {
					$bdp_content .= fread( $bdp_handle, 8192 );
				}
				fclose( $bdp_handle );
			}
			return $bdp_content;
		} else {
			return wp_remote_get( $filepath );
		}
	}

	/**
	 * Replace alt and title tag
	 *
	 * @param html $text text.
	 * @return html $text
	 */
	public function bdp_replace_content( $text ) {
		$alt  = get_the_author_meta( 'display_name' );
		$text = str_replace( 'alt=\'\'', 'alt=\'' . $alt . '\' title=\'' . $alt . '\'', $text );
		return $text;
	}
	/**
	 * Replace alt and title tag
	 *
	 * @return void
	 */
	public function bdp_template_name_changed_updater() {
		$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		?>
		<div class="updated">
			<p>
				<strong>
					<?php esc_html_e( 'Blog Designer PRO Data Update', 'blog-designer-pro' ); ?>
				</strong> &#8211; <?php esc_html_e( 'We need to update your layouts design data according to the latest version.', 'blog-designer-pro' ); ?>
			</p>
			<p class="submit">
				<a href="<?php echo esc_url( add_query_arg( 'do_update_bdp_template_name_changed', 'do', $req_uri ) ); ?>" class="bdp-template-chnage-now button-primary">
					<?php esc_html_e( 'Run the updater', 'blog-designer-pro' ); ?>
				</a>
			</p>
		</div>
		<script type="text/javascript">
			jQuery('.bdp-template-chnage-now').on('click','click', function () {
				return window.confirm('<?php echo esc_js( esc_html__( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'blog-designer-pro' ) ); ?>');
			});
		</script>
		<?php
	}
	/**
	 * Argument for Kses.
	 *
	 * @since    1.0.0
	 * @return  array
	 */
	public static function args_kses() {
		$common_attr = array(
			'class'            => true,
			'id'               => true,
			'style'            => true,
			'name'             => true,
			'src'              => true,
			'type'             => true,
			'for'              => true,
			'value'            => true,
			'data-placeholder' => true,
		);
		$args_kses   = array(
			'div'        => array(
				'class'    => true,
				'id'       => true,
				'style'    => true,
				'script'   => true,
				'adid'     => true,
				'bdid'     => true,
				'btype'    => true,
				'adview'   => true,
				'bid'      => true,
				'data-aos' => true,
			),
			'script'     => array(
				'type'    => true,
				'charset' => true,
			),
			'style'      => array(
				'type' => true,
			),
			'iframe'     => array(
				'class'        => true,
				'src'          => true,
				'style'        => true,
				'marginwidth'  => true,
				'marginheight' => true,
				'scrolling'    => true,
				'frameborder'  => true,
			),
			'img'        => array(
				'class'    => true,
				'src'      => true,
				'alt'      => true,
				'decoding' => true,
				'width'    => true,
				'height'   => true,
			),
			'a'          => array(
				'href'              => true,
				'adid'              => true,
				'bdid'              => true,
				'btype'             => true,
				'adview'            => true,
				'bid'               => true,
				'class'             => true,
				'target'            => true,
				'data-href'         => true,
				'data-share'        => true,
				'data-url'          => true,
				'data-description'  => true,
				'data-mdia'         => true,
				'data-shortcode-id' => true,
				'data-text'         => true,
				'data-title'        => true,
				'data-id'           => true,
				'script'            => true,
				'id'                => true,
				'data-nonce'        => true,
				'data-post-id'      => true,
				'title'             => true,
				'aria-label'        => true,
			),
			'input'      => $common_attr,
			'fieldset'   => $common_attr,
			'label'      => $common_attr,
			'select'     => $common_attr,
			'sup'        => $common_attr,
			'b'          => $common_attr,
			'em'         => $common_attr,
			'option'     => array(
				'value' => true,
			),
			'time'       => array(
				'class'    => true,
				'datetime' => true,
			),
			'ul'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'li'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'p'          => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'i'          => array(
				'class' => true,
				'style' => true,
			),
			'h1'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'h2'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'h3'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'h4'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'h5'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'h6'         => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'span'       => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'strong'     => array(
				'class' => true,
				'style' => true,
			),
			'figure'     => array(
				'class' => true,
			),
			'video'      => array(
				'controls' => true,
				'src'      => true,
			),
			'audio'      => array(
				'controls' => true,
				'src'      => true,
			),
			'blockquote' => array(
				'class' => true,
			),
			'nav'        => array(
				'class' => true,
				'role'  => true,
			),
		);
		return $args_kses;
	}

	/**
	 * Single Add Post Meta Box.
	 */
	public function bdp_single_add_post_meta_box() {
		add_meta_box( 'singleposts-meta-box', 'Single Post Settings', array( &$this, 'bdp_single_posts_meta_box_markup' ), 'post', 'normal', 'high', null );
		$cpt_result = array(
			'public'   => true,
			'_builtin' => false,
		);
		$screens    = get_post_types( $cpt_result );
		if ( ! empty( $screens ) ) {
			foreach ( $screens as $screen ) {
				add_meta_box( 'singleposts-meta-box', 'Single Post Settings', array( &$this, 'bdp_single_posts_meta_box_markup' ), $screen, 'normal', 'high', null );
			}
		}
	}

	/**
	 * Single Posts Meta box Markup
	 *
	 * @param string $post Post.
	 */
	public function bdp_single_posts_meta_box_markup( $post ) {
		$bdp_single_settings                = get_post_meta( $post->ID, 'bdp_single_settings', true );
		$bdp_single_selected_templates      = isset( $bdp_single_settings['single_selecttemplate_field'] ) ? $bdp_single_settings['single_selecttemplate_field'] : array();
		$bdp_single_template_titlebackcolor = isset( $bdp_single_settings['bdp_single_template_titlebackcolor'] ) ? $bdp_single_settings['bdp_single_template_titlebackcolor'] : '';
		$bdp_single_post_open_icon          = isset( $bdp_single_settings['bdp_single_post_open_icon'] ) ? $bdp_single_settings['bdp_single_post_open_icon'] : 'fas fa-plus';
		$bdp_single_post_close_icon         = isset( $bdp_single_settings['bdp_single_post_close_icon'] ) ? $bdp_single_settings['bdp_single_post_close_icon'] : 'fas fa-minus';
		?>
		<div class="inside-singleposts">
			<ul class="bd-singlepost-wrappers">	
			<li class="singleposts_post_template">
					<div class="bdp-select-layout">
					</div>
					<div class="bdp-left">
						<span class="bdp-key-title">
							<?php esc_html_e( 'Select Layout', 'blog-designer-pro' ); ?>
						</span>
					</div>
					<div class="bdp-right">
						<div class="typo-field">
							<select name="bdp_single_settings[single_selecttemplate_field][]" id="single_selecttemplate_field" class="chosen-select" multiple>
								<?php
									$tempate_list = array( 'accordion' => 'Accordion Template' );
								foreach ( $tempate_list as $key => $value ) {
									?>
										<option value="<?php echo esc_attr( $key ); ?>"
											<?php
											if ( is_array( $bdp_single_selected_templates ) ) {
												if ( in_array( $key, $bdp_single_selected_templates ) ) {
													echo 'selected="selected"';}
											}
											?>
										><?php echo esc_attr( $value ); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</li>
				<li class="singleposts_post_open_icon">
					<div class="bdp-option-wrap bdp-option-single-icon-fontawesome">
						<div class="bdp-left">
							<span class="bdp-key-title">
								<?php esc_html_e( 'Select Open Icon', 'blog-designer-pro' ); ?>
							</span>
						</div>
						<div class="bdp-option-input bdp_single_icon_wrap_open">
							<input class="icon-input" id="bdp_single_post_open_icon" name="bdp_single_settings[bdp_single_post_open_icon]" type="text" value="<?php echo esc_attr( $bdp_single_post_open_icon ); ?>">
							<a id="" class="open button button-primary"><?php esc_html_e( 'Select icon', 'blog-designer-pro' ); ?></a>
							<div id="dialogbox_open" class="dialogbox_open" title="<?php esc_attr_e( 'Select Icon For Open', 'blog-designer-pro' ); ?>" style="display:none">
								<input type="hidden" value="" name="" class="hidden_input_val"/>
								<input type="text" id="icon_search_open" placeholder="<?php esc_attr_e( 'Search icon for open', 'blog-designer-pro' ); ?>" style="margin-bottom:5px;">
								<div class="bdp_single_icon_div_open" id="bdp_single_icon_div_open">
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="singleposts_post_close_icon">
					<div class="bdp-option-wrap bdp-option-single-icon-fontawesome">
						<div class="bdp-left">
							<span class="bdp-key-title">
								<?php esc_html_e( 'Select Close Icon', 'blog-designer-pro' ); ?>
							</span>
						</div>
						<div class="bdp-option-input bdp_single_icon_wrap_close">
							<input class="icon-input" id="bdp_single_post_close_icon" name="bdp_single_settings[bdp_single_post_close_icon]" type="text" value="<?php echo esc_attr( $bdp_single_post_close_icon ); ?>">
							<a id="" class="open button button-primary"><?php esc_html_e( 'Select icon', 'blog-designer-pro' ); ?></a>
							<div id="dialogbox_close" class="dialogbox_close" title="<?php esc_attr_e( 'Select Icon For Close', 'blog-designer-pro' ); ?>" style="display:none">
								<input type="hidden" value="" name="" class="hidden_input_val"/>
								<input type="text" id="icon_search_close" placeholder="<?php esc_attr_e( 'Search icon for close', 'blog-designer-pro' ); ?>" style="margin-bottom:5px;">
								<div class="bdp_single_icon_div_close" id="bdp_single_icon_div_close">
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="singleposts_post_bg">
					<div class="bdp-left">
						<span class="bdp-key-title">
							<?php esc_html_e( 'Select Title Background Color', 'blog-designer-pro' ); ?>
						</span>
					</div>
					<div class="bdp-right">
						<input type="text" name="bdp_single_settings[bdp_single_template_titlebackcolor]" id="bdp_single_template_titlebackcolor" value="<?php echo esc_attr( $bdp_single_template_titlebackcolor ); ?>"/>
					</div>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Save Single Meta Data.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $post Post.
	 * @param string $update Update.
	 */
	public function bdp_save_single_meta_data( $post_id, $post, $update ) {
		if ( isset( $_POST['meta-box-order-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta-box-order-nonce'] ) ), 'meta-box-order' ) ) {
			if ( isset( $_POST['bdp_single_settings'] ) ) {
				update_post_meta( $post_id, 'bdp_single_settings', array_map( 'sanitize_text_field', wp_unslash( $_POST['bdp_single_settings'] ) ) );
			}
		}
	}
}
new Bdp_Admin_Functions();
