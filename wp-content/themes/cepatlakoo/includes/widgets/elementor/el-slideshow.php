<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_SlideShow_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-slideshow';
	}

	public function get_title() {
		return esc_html__( 'CL - Slideshow', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-slider-push';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Slideshow', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_post_id',
			[
				'label' => esc_html__( 'Choose Slideshow', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'options' => cepatlakoo_extract_cpt_slideshow(),
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
   	  	$settings = $this->get_settings();

		$cepatlakoo_post_id = ! empty( $settings['cepatlakoo_post_id'] ) ? (int)$settings['cepatlakoo_post_id'] : null;
		echo do_shortcode( '[cepatlakoo-slideshow id="'.$cepatlakoo_post_id .'"]' );
	}
	
	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_SlideShow_Widget_Elementor() );