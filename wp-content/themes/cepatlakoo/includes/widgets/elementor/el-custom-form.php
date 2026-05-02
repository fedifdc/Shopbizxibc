<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Custom_Form_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-custom-form';
	}

	public function get_title() {
		return esc_html__( 'CL - Custom Form', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-form-horizontal';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Custom Form', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_html_code',
			[
				'label' => esc_html__( 'Form Code', 'cepatlakoo' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => '10',
			]
		);

		$this->add_control(
			'cepatlakoo_form_width',
			[
				'label' => esc_html__( 'Form Width', 'cepatlakoo' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 560,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 2000,
		                'step' => 1,
		            ],
		            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .custom-form-area' => 'width: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_form_display',
			[
				'label' => esc_html__( 'Form Display', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'cepatlakoo' ),
					'full_width' => esc_html__( 'Full Width', 'cepatlakoo' ),
				],
			]
		);

		$this->add_control(
			'cepatlakoo_button_width',
			[
				'label' => esc_html__( 'Input Field Width', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'cepatlakoo' ),
					'split' => esc_html__( 'Width 50%', 'cepatlakoo' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cepatlakoo_section_button_style',
			[
				'label' => esc_html__( 'Button Style', 'cepatlakoo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cepatlakoo_button_typography',
				'label' => esc_html__( 'Typography', 'cepatlakoo' ),
				'scheme' => Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button',
			]
		);

		$this->add_control(
			'cepatlakoo_button_style_color',
			[
				'label' => esc_html__( 'Button Color', 'cepatlakoo' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Core\Schemes\Color::get_type(),
					'value' => Core\Schemes\Color::COLOR_1,
				],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cepatlakoo_button_bg_color',
			[
				'label' => esc_html__( 'Button Background Color', 'cepatlakoo' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c92f2f',
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cepatlakoo_button_bg_hover_color',
			[
				'label' => esc_html__( 'Button Background Hover Color', 'cepatlakoo' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input[type="reset"]:hover, {{WRAPPER}} .custom-form-area input[type="button"]:hover, {{WRAPPER}} .custom-form-area input[type="submit"]:hover, {{WRAPPER}} .custom-form-area button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'cepatlakoo_button_height',
			[
				'label' => esc_html__( 'Button Height', 'cepatlakoo' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 47,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 120,
		                'step' => 1,
		            ],
		            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button' => 'height: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cepatlakoo_button_border',
				'label' => esc_html__( 'Button Border', 'cepatlakoo' ),
				'placeholder' => '1px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button',
			]
		);

		$this->add_control(
			'cepatlakoo_button_border_radius',
			[
				'label' => esc_html__( 'Button Border Radius', 'cepatlakoo' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input[type="reset"], {{WRAPPER}} .custom-form-area input[type="button"], {{WRAPPER}} .custom-form-area input[type="submit"], {{WRAPPER}} .custom-form-area button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'cepatlakoo_section_form_style',
			[
				'label' => esc_html__( 'Form Style', 'cepatlakoo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cepatlakoo_form_typography',
				'label' => esc_html__( 'Typography', 'cepatlakoo' ),
				'scheme' => Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .custom-form-area input',
			]
		);

		$this->add_control(
			'cepatlakoo_form_style_color',
			[
				'label' => esc_html__( 'Input Field Text Color', 'cepatlakoo' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Core\Schemes\Color::get_type(),
					'value' => Core\Schemes\Color::COLOR_1,
				],
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cepatlakoo_form_height',
			[
				'label' => esc_html__( 'Input Field Height', 'cepatlakoo' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
		            'size' => 47,
		        ],
		        'range' => [
		            'px' => [
		                'min' => 50,
		                'max' => 100,
		                'step' => 1,
		            ],
		            '%' => [
		                'min' => 0,
		                'max' => 100,
		            ],
		        ],
		        'size_units' => [ 'px', '%' ],
		        'selectors' => [
		            '{{WRAPPER}} .custom-form-area input' => 'height: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_form_bg_color',
			[
				'label' => esc_html__( 'Form Background Color', 'cepatlakoo' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cepatlakoo_form_border',
				'label' => esc_html__( 'Form Border', 'cepatlakoo' ),
				'placeholder' => '1px',
				'default' => '0px',
				'selector' => '{{WRAPPER}} .custom-form-area input',
			]
		);

		$this->add_control(
			'cepatlakoo_form_border_radius',
			[
				'label' => esc_html__( 'Form Border Radius', 'cepatlakoo' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .custom-form-area input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
   	  	$settings = $this->get_settings();

   	  	$form_html_code = ! empty( $settings['cepatlakoo_html_code'] ) ? $settings['cepatlakoo_html_code'] : '';
   	  	$cepatlakoo_form_display = ! empty( $settings['cepatlakoo_form_display'] ) ? esc_attr($settings['cepatlakoo_form_display']) : '';
   	  	$cepatlakoo_button_width = ! empty( $settings['cepatlakoo_button_width'] ) ? esc_attr($settings['cepatlakoo_button_width']) : '';

   	  	if ( $cepatlakoo_form_display == "full_width" ) {
   	  		$form_classes = "style-full ";
   	  		if ( $cepatlakoo_button_width == "split" ) {
	   	  		$button_classes = "button-split";
	   	  	} else {
	   	  		$button_classes = "button-default";
	   	  	}
   	  	} else {
   	  		$form_classes = "style-default ";
   	  		if ( $cepatlakoo_button_width == "split" ) {
	   	  		$button_classes = "button-split";
	   	  	} else {
	   	  		$button_classes = "button-default";
	   	  	}
   	  	}
?>
		<div class="custom-form-area <?php echo esc_attr($form_classes).' '.esc_attr($button_classes); ?>">
			<?php echo $form_html_code; ?>
		</div>
<?php
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Custom_Form_Widget_Elementor() );