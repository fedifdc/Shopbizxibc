<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Single_Products_Highlight_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-single-product-highlight';
	}

	public function get_title() {
		return esc_html__( 'CL - Single Product Highlight', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Single Product Highlight', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_product_id',
			[
				'label' => esc_html__( 'Product ID', 'cepatlakoo' ),
				'type' => Controls_Manager::TEXT,
				'default' => 0,
			]
		);

		$this->add_control(
			'cepatlakoo_display_product_title',
			[
				'label' => esc_html__( 'Product Title', 'cepatlakoo' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'cepatlakoo' ),
				'label_off' => esc_html__( 'Hide', 'cepatlakoo' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'cepatlakoo_display_product_rating',
			[
				'label' => esc_html__( 'Product Rating', 'cepatlakoo' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'cepatlakoo' ),
				'label_off' => esc_html__( 'Hide', 'cepatlakoo' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'cepatlakoo_display_short_description',
			[
				'label' => esc_html__( 'Short Description', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'show_desc',
				'options' => [
					'show_desc' => esc_html__( 'Show', 'cepatlakoo' ),
					'hide_desc' => esc_html__( 'Hide', 'cepatlakoo' ),
				]
			]
		);

		$this->add_control(
			'cepatlakoo_display_gallery_thumbnail',
			[
				'label' => esc_html__( 'Gallery Thumbnail', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hide_gallery',
				'options' => [
					'show_gallery' => esc_html__( 'Show', 'cepatlakoo' ),
					'hide_gallery' => esc_html__( 'Hide', 'cepatlakoo' ),
				]
			]
		);

		$this->add_control(
			'cepatlakoo_display_additional_info',
			[
				'label' => esc_html__( 'Additional Info', 'cepatlakoo' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hide_meta',
				'options' => [
					'show_meta' => esc_html__( 'Show', 'cepatlakoo' ),
					'hide_meta' => esc_html__( 'Hide', 'cepatlakoo' ),
				]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
   		$settings = $this->get_settings();
		$product_id = !empty( $settings['cepatlakoo_product_id'] ) ? (int)$settings['cepatlakoo_product_id'] : 0;
		$product_title = !empty( $settings['cepatlakoo_display_product_title'] ) ? $settings['cepatlakoo_display_product_title'] : 'yes';
		$product_rating = !empty( $settings['cepatlakoo_display_product_rating'] ) ? $settings['cepatlakoo_display_product_rating'] : 'yes';
		$gallery_thumbnail = !empty( $settings['cepatlakoo_display_gallery_thumbnail'] ) ? $settings['cepatlakoo_display_gallery_thumbnail'] : 'hide_gallery';
		$additional_info = !empty( $settings['cepatlakoo_display_additional_info'] ) ? $settings['cepatlakoo_display_additional_info'] : 'hide_meta';
		$short_description = !empty( $settings['cepatlakoo_display_short_description'] ) ? $settings['cepatlakoo_display_short_description'] : 'show_desc';

		echo '<section id="single-product-hightlight" class="woocommerce">';
			echo '<div class="gallery-thumbnail" style="display:none;">'. esc_attr( $gallery_thumbnail ) .'</div>';

			$params = array('posts_per_page' => 1, 'post_type' => 'product', 'p' => $product_id);
			$wc_query = new \WP_Query($params);

			if ($wc_query->have_posts()) :
				while ($wc_query->have_posts()) : $wc_query->the_post();
				
				wc_get_product( $product_id );

				// Product Title
				if ( $product_title == 'yes' ) {
					add_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_title', 5 );
				} else {
					remove_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_title', 5 );
				}

				// Product Rating
				if ( $product_rating == 'yes' ) {
					add_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_rating', 10 );
				} else {
					remove_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_rating', 10 );
				}

				// Short Description
				if ( $short_description == 'hide_desc' ) {
					remove_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_excerpt', 20 );
				} elseif ( $short_description == 'show_desc' ) {
					add_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_excerpt', 20 );
				}

				// Additional Info
				if ( $additional_info == 'hide_meta' ) {
					remove_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_meta', 40 );
				} elseif ( $additional_info == 'show_meta' ) {
					add_action( 'cepatlakoo_summary_product_highlight', 'woocommerce_template_single_meta', 40 );
				}

				wc_get_template_part( 'content-single-product-highlight' );

				endwhile; wp_reset_postdata();
			else:
				echo '<p>';
					echo esc_html__( 'Product not found.', 'cepatlakoo' );
				echo '</p>';
			endif;
		echo '</section>';
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Single_Products_Highlight_Widget_Elementor() );
