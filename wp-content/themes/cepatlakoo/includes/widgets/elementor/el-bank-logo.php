<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Bank_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-bank';
	}

	public function get_title() {
		return esc_html__( 'CL - Bank Logo', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-favorite';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Bank Logo', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_bank_name',
			[
				'label' => esc_html__( 'Bank Name', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bca',
				'options' => [
					'bjb' => esc_html__( 'Bank BJB', 'cepatlakoo' ),
					'bankdki' => esc_html__( 'Bank DKI', 'cepatlakoo' ),
					'bca' => esc_html__( 'BCA', 'cepatlakoo' ),
					'bcasyariah' => esc_html__( 'BCA Syariah', 'cepatlakoo' ),
					'bni' => esc_html__( 'BNI', 'cepatlakoo' ),
					'bnisyariah' => esc_html__( 'BNI Syariah', 'cepatlakoo' ),
					'bri' => esc_html__( 'BRI', 'cepatlakoo' ),
					'brisyariah' => esc_html__( 'BRI Syariah', 'cepatlakoo' ),
					'btn' => esc_html__( 'BTN', 'cepatlakoo' ),
					'cimbniaga' => esc_html__( 'CIMB Niaga', 'cepatlakoo' ),
					'danamon' => esc_html__( 'DANAMON', 'cepatlakoo' ),
					'mandiri' => esc_html__( 'Mandiri', 'cepatlakoo' ),
					'mandirisyariah' => esc_html__( 'Mandiri Syariah', 'cepatlakoo' ),
					'maybank' => esc_html__( 'Maybank', 'cepatlakoo' ),
					'muamalat' => esc_html__( 'Bank Muamalat', 'cepatlakoo' ),
					'panin' => esc_html__( 'Panin Bank', 'cepatlakoo' ),
					'permata' => esc_html__( 'Permata Bank', 'cepatlakoo' ),
				],
			]
		);

		$this->add_control(
			'cepatlakoo_bank_size',
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
		            '{{WRAPPER}} .elementor-bank img' => 'width: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_height_size',
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
		            '{{WRAPPER}} .elementor-bank' => 'height: {{SIZE}}{{UNIT}};',
		        ],
			]
		);

		$this->add_control(
			'cepatlakoo_bank_align',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-bank' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cepatlakoo_bank_verticalalign',
			[
				'label' => esc_html__( 'Vertical Alignment', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'cepatlakoo' ),
					'middle' => esc_html__( 'Middle', 'cepatlakoo' ),
					'bottom' => esc_html__( 'Bottom', 'cepatlakoo' ),
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-bank .elementor-valign' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
   	  	$settings = $this->get_settings();

   	  	$cepatlakoo_bank_name = ! empty( $settings['cepatlakoo_bank_name'] ) ? esc_attr($settings['cepatlakoo_bank_name']) : '';
?>
		<div class="elementor-bank">
			<div class="elementor-valign">
				<?php if ($cepatlakoo_bank_name == "bjb") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bjb.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bankdki") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bankdki.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bca") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bca.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bcasyariah") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bcasyariah.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bni") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bni.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bnisyariah") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bnisyariah.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "bri") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/bri.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "brisyariah") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/brisyariah.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "btn") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/btn.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "cimbniaga") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/cimbniaga.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "danamon") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/danamon.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "mandiri") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/mandiri.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "mandirisyariah") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/mandirisyariah.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "maybank") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/maybank.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "muamalat") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/muamalat.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "panin") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/panin.svg">
				<?php endif; ?>
				<?php if ($cepatlakoo_bank_name == "permata") : ?>
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/svg/banks/permata.svg">
				<?php endif; ?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Bank_Widget_Elementor() );
