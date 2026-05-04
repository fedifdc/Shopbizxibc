<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cepatlakoo_Countdown_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-countdown';
	}

	public function get_title() {
		return esc_html__( 'CL - Countdown', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Countdown', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_countdown_id',
			[
			'label' => esc_html__( 'Countdown Timer', 'cepatlakoo' ),
			'type' => Controls_Manager::SELECT,
			'options' => cepatlakoo_get_post_type_post_options(),
			]
		);

		$this->add_control(
			'cepatlakoo_countdown_align',
			[
				'label' => esc_html__( 'Alignment', 'cepatlakoo' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'cepatlakoo' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cepatlakoo' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'cepatlakoo' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} #countdown #timer' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
   	  	$settings = $this->get_settings();
		$cepatlakoo_countdown_id = ! empty( $settings['cepatlakoo_countdown_id'] ) ? esc_attr($settings['cepatlakoo_countdown_id']) : '';
		$countdown_shortcode = get_post_meta( $cepatlakoo_countdown_id, 'cl_countdown_shortcode', true );
		echo do_shortcode( $countdown_shortcode );
	}
	
	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new Cepatlakoo_Countdown_Widget_Elementor() );