<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Courier_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-courier';
	}

	public function get_title() {
		return esc_html__( 'CL - Courier Logo', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-favorite';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Courier Logo', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_courier_name',
			[
				'label' => esc_html__( 'Courier Name', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'gojek',
				'options' => [
					'gojek' => esc_html__( 'Gojek', 'cepatlakoo' ),
					'grab' => esc_html__( 'Grab', 'cepatlakoo' ),
					'jt' => esc_html__( 'J & T', 'cepatlakoo' ),
					'jne' => esc_html__( 'JNE', 'cepatlakoo' ),
					'posindo' => esc_html__( 'Pos Indonesia', 'cepatlakoo' ),
					'rex' => esc_html__( 'REX', 'cepatlakoo' ),
					'sicepat' => esc_html__( 'Sicepat', 'cepatlakoo' ),
					'tiki' => esc_html__( 'TIKI', 'cepatlakoo' ),
					'wahana' => esc_html__( 'Wahana', 'cepatlakoo' ),
				],
			]
		);

		$this->add_control(
			'cepatlakoo_courier_size',
			[
				'label' => esc_html__( 'Size', 'cepatlakoo' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 100,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 500,
		                'step' => 1,
		            ],
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .courier img' => 'width: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_height_wrapper',
			[
				'label' => esc_html__( 'Height Wrapper', 'cepatlakoo' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 100,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 500,
		                'step' => 1,
		            ],
		        ],
		        'size_units' => [ 'px' ],
		        'selectors' => [
		            '{{WRAPPER}} .courier' => 'height: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_courier_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'middle',
				'options' => [
					'middle' => esc_html__( 'Middle', 'cepatlakoo' ),
					'top' => esc_html__( 'Top', 'cepatlakoo' ),
					'bottom' => esc_html__( 'Bottom', 'cepatlakoo' ),
				],
				'selectors' => [
		            '{{WRAPPER}} .courier .elementor-valign' => 'vertical-align: {{VALUE}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_courier_align',
			[
				'label' => esc_html__( 'Alignment', 'cepatlakoo' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'prefix_class' => 'btn-align-',
				'default' => 'left',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
   	  	$settings = $this->get_settings();

   	  	$cepatlakoo_courier_name = ! empty( $settings['cepatlakoo_courier_name'] ) ? esc_attr($settings['cepatlakoo_courier_name']) : '';
?>
		<div class="courier">
			<div class="elementor-valign">
				<?php if ($cepatlakoo_courier_name == "gojek") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/gojek.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "grab") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/grab.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "jt") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/j&t.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "jne") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/jne.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "posindo") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/posindo.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "rex") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/rex.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "sicepat") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/sicepat.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "tiki") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/tiki.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_courier_name == "wahana") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/couriers/wahana.svg">
				<?php endif; ?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Courier_Widget_Elementor() );
?>
