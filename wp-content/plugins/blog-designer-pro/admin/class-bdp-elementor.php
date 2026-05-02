<?php
/**
 * The Elementor functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

/**
 * Widget class for the Elementor_BDP_Widget.
 *
 * @since 1.0.0
 */
class Elementor_BDP_Widget extends \Elementor\Widget_Base {
	/**
	 * Get name of Widget.
	 */
	public function get_name() {
		return 'Blog Designer Pro';
	}
	/**
	 * Get title of Widget.
	 */
	public function get_title() {
		return esc_html__( 'Blog Designer Pro', 'blog-designer-pro' );
	}
	/**
	 * Get the icon of Widget.
	 */
	public function get_icon() {
		return 'bdp-widget-icon';
	}
	/**
	 * Get the category of Widget.
	 */
	public function get_categories() {
		return array( 'basic' );
	}
	/**
	 * Get the keyword of Widget.
	 */
	public function get_keywords() {
		return array( 'blog', 'BDP' );
	}
	/**
	 * Register control of Widget.
	 */
	protected function register_controls() {
		global $wpdb;
		$shortcodes  = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' );
		$bdp_layouts = array();
		if ( $shortcodes ) {
			foreach ( $shortcodes as $shortcode ) {
				$bdp_layouts[ $shortcode->bdid ] = isset( $shortcode->shortcode_name ) && ! empty( $shortcode->shortcode_name ) ? $shortcode->shortcode_name : '(no title)';
			}
		}
		// Content Tab Start.

		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Select Layout', 'blog-designer-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'bdp_id',
			array(
				'label'   => esc_html__( 'Select Layout', 'blog-designer-pro' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $bdp_layouts,
			)
		);

		$this->end_controls_section();

		// Content Tab End.
	}
	/**
	 * Render the Widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="elementor-bdp">
			<?php echo do_shortcode( '[wp_blog_designer id="' . $settings['bdp_id'] . '"]' ); ?>
		</div>
		<?php
		$style = new Bdp_Front_Functions();
		if ( is_admin() ) {
			$style->bdp_add_template_style();
			$style->bdp_template_dynamic_css();
			$style->bdp_template_dynamic_script();
		}
	}
}

