<?php
/**
 * The Blog Designer Support.
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
require_once ABSPATH . 'wp-admin/includes/plugin.php';
/**
 * Blog Designer PRO Support Functions Class.
 *
 * @class   Bdp_Support
 * @version 1.0.0
 */
class Bdp_Support {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_plugin_active( 'beaver-builder-lite-version/fl-builder.php' ) ) {
			add_action( 'fl_builder_ui_panel_after_modules', array( $this, 'add_bdp_widget' ) );
		}
		if ( is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
			add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $this, 'siteorigin_panels_add_widgets_dialog_tabs_fun' ), 20 );
			add_filter( 'siteorigin_panels_widgets', array( $this, 'siteorigin_panels_add_recommended_widgets_fun' ) );
		}
		if ( is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'suuport_script' ) );
		}
		if ( is_plugin_active( 'fusion-builder/fusion-builder.php' ) ) {
			add_action( 'fusion_builder_before_init', array( $this, 'fusion_element_bdp' ) );
		}
		if ( is_plugin_active( 'fusion/fusion-core.php' ) ) {
			add_action( 'init', array( $this, 'fsn_init_bdp' ), 12 );
			add_shortcode( 'fsn_blog_designer_pro', array( $this, 'fsn_blog_designer_pro_shortcode' ) );
		}
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_bdp_elementor_widget' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'suuport_script' ), 20 );
		}
	}
	/**
	 * Beaver Builder Lite
	 *
	 * @return void
	 */
	public function add_bdp_widget() {
		?>
		<div id="fl-builder-blocks-bdp-widget" class="fl-builder-blocks-section">
			<span class="fl-builder-blocks-section-title"><?php esc_html_e( 'Blog Designer', 'blog-designer-pro' ); ?><i class="fas fa-chevron-down"></i>
			</span>
			<div class="fl-builder-blocks-section-content fl-builder-modules"><span class="fl-builder-block fl-builder-block-module" data-widget="Blog_Designer_Pro_Widget" data-type="widget"><span class="fl-builder-block-title"><?php esc_html_e( 'Blog Designer PRO', 'blog-designer-pro' ); ?></span></span></div>
		</div>
		<?php
	}
	/**
	 * Page Builder by SiteOrigin
	 *
	 * @since 1.0.0
	 * @param array $tabs tabs.
	 * @return array $tabs
	 */
	public function siteorigin_panels_add_widgets_dialog_tabs_fun( $tabs ) {
		$tabs['blog_designer'] = array(
			'title'  => esc_html__( 'Blog Designer', 'blog-designer-pro' ),
			'filter' => array(
				'groups' => array( 'blog_designer' ),
			),
		);
		return $tabs;
	}
	/**
	 * Site Origin Panels add recommended widgets
	 *
	 * @since 1.0.0
	 * @param array $widgets widgets.
	 * @return array $widgets
	 */
	public function siteorigin_panels_add_recommended_widgets_fun( $widgets ) {
		foreach ( $widgets as $widget_id => &$widget ) {
			if ( strpos( $widget_id, 'BDP_Widget_' ) === 0 || strpos( $widget_id, 'blog_designer_pro_widget' ) !== false ) {
				$widget['groups'][] = 'blog_designer';
				$widget['icon']     = 'bdp_icon';
			}
		}
		return $widgets;
	}
	/**
	 * Support Script
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function suuport_script() {
		wp_enqueue_style( 'bdp_support_css', plugins_url( 'blog-designer-pro/admin/css/bdp_support.css' ), null, '1.0' );
	}
	/**
	 * Fusion Element Builder
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function fusion_element_bdp() {
		global $wpdb;
		$shortcodes  = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' );
		$bdp_layouts = array();
		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				$bdp_layouts[ $shortcode->shortcode_name ] = $shortcode->bdid;
			}
		}
		fusion_builder_map(
			array(
				'name'      => esc_attr__( 'Blog Designer PRO', 'blog-designer-pro' ),
				'shortcode' => 'wp_blog_designer',
				'icon'      => 'bdp_icon',
				'params'    => array(
					array(
						'type'       => 'select',
						'heading'    => esc_attr__( 'Select Layout', 'blog-designer-pro' ),
						'param_name' => 'id',
						'default'    => '',
						'value'      => $bdp_layouts,
					),
				),
			)
		);
	}
	/**
	 * Fusion Page Builder
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function fsn_init_bdp() {
		if ( function_exists( 'fsn_map' ) ) {
			global $wpdb;
			$shortcodes  = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' );
			$bdp_layouts = array();
			if ( $shortcodes ) {
				foreach ( $shortcodes as $shortcode ) {
					$bdp_layouts[ $shortcode->bdid ] = $shortcode->shortcode_name;
				}
			}
			fsn_map(
				array(
					'name'          => esc_html__( 'Blog Designer PRO', 'blog-designer-pro' ),
					'shortcode_tag' => 'fsn_blog_designer_pro',
					'description'   => esc_html__( 'Blog Designer is a step ahead wordpress plugin that allows you to modify blog page, single page and archive page layouts and design.', 'blog-designer-pro' ),
					'icon'          => 'fsn_blog',
					'params'        => array(
						array(
							'type'       => 'select',
							'param_name' => 'id',
							'label'      => esc_html__( 'Select Blog Designer Layout', 'blog-designer-pro' ),
							'options'    => $bdp_layouts,
						),
					),
				)
			);
		}
	}
	/**
	 * FSN Blog Designer PRO Shortcode
	 *
	 * @since 1.0.0
	 * @param array  $atts atts.
	 * @param string $content content.
	 * @return html $output
	 */
	public function fsn_blog_designer_pro_shortcode( $atts, $content ) {
		ob_start();
		?>
		<div class="fsn-bdp <?php echo esc_attr( fsn_style_params_class( $atts ) ); ?>">
			<?php echo do_shortcode( '[wp_blog_designer id="' . $atts['id'] . '"]' ); ?>
		</div>
		<?php
		$output = ob_get_clean();
		return $output;
	}

	/**
	 * Create widget option for blog Designer Pro plugin
	 *
	 * @param object $widgets_manager objecet of the widget.
	 * @return void
	 */
	public function register_bdp_elementor_widget( $widgets_manager ) {

		require_once __DIR__ . '/class-bdp-elementor.php';

		$widgets_manager->register( new \Elementor_BDP_Widget() );
	}
}
new Bdp_Support();
