<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Cepatlakoo_Testimony_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-testimony';
	}

	public function get_title() {
		return esc_html__( 'CL - Testimonials', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Testimonials', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_testimony_name',
			[
				'label' => esc_html__( 'Testimony Name', 'cepatlakoo' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Testimony Name', 'cepatlakoo'),
				'title' => esc_html__( 'Enter name of testimony', 'cepatlakoo' ),
			]
		);

		$this->add_control(
		  'cepatlakoo_testimony_photo',
		  [
		     'label' => esc_html__( 'Testimony Photo', 'cepatlakoo' ),
		     'type' => Controls_Manager::MEDIA,
		     'default' => [
		        'url' => Utils::get_placeholder_image_src(),
		     ],
		  ]
		);

		$this->add_control(
		  'cepatlakoo_testimony_text',
		  [
		     'label'   => esc_html__( 'Testimony Text', 'cepatlakoo' ),
		     'title'   => esc_html__( 'Enter name of testimony', 'cepatlakoo' ),
		     'type'    => Controls_Manager::TEXTAREA,
			 'rows'    => '10',
		     'default' => esc_html__( 'Enter your testimony text below here', 'cepatlakoo' ),
		  ]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
		$settings = $this->get_settings();
		$cepatlakoo_testimony_name = ! empty( $settings['cepatlakoo_testimony_name'] ) ? $settings['cepatlakoo_testimony_name'] : esc_html__('Testimony Name', 'cepatlakoo');

   	  	$cepatlakoo_testimony_photo = ! empty( $settings['cepatlakoo_testimony_photo'] ) ? $settings['cepatlakoo_testimony_photo']['id'] : '';
		$cepatlakoo_testimony_text = ! empty( $settings['cepatlakoo_testimony_text'] ) ? $settings['cepatlakoo_testimony_text']: esc_html__( 'Enter your testimony text below here', 'cepatlakoo' );
		
		ob_start();
		?> 
   	  	<section id="testimony-widget">
		  	<div class="testiomny-slider">
		      	<div class="testimony-item">
		      		<div class="thumbnail">
		      			<?php echo wp_get_attachment_image( absint($cepatlakoo_testimony_photo),'thumbnail') ?>
		      		</div>
		      		<div class="detail">
		      			<p><?php esc_attr_e($cepatlakoo_testimony_text); ?></p>
		      			<span><?php esc_attr_e($cepatlakoo_testimony_name); ?></span>
		      		</div>
		      	</div>
		  	</div>
		</section>
		<?php 
		$tesimony_item = ob_get_clean();
		echo $tesimony_item;
	}
	
	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new Cepatlakoo_Testimony_Widget_Elementor() );