<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Products_By_Category_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-products-in-category';
	}

	public function get_title() {
		return esc_html__( 'CL - Products by Category', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Products by Category', 'cepatlakoo' ),
			]
		);

		// Get WooCommerce categories
		$woo_categories = get_terms( 'product_cat' );

		$get_options = [];
		foreach ( $woo_categories as $woo_category ) {
			$get_options[ $woo_category->slug ] = $woo_category->name;
		}

		$this->add_control(
			'cepatlakoo_cat_slugs',
			[
				'label' => __( 'Choose Categories', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $get_options,
				'default' => [],
			]
		);
		
		$this->add_control(
			'cepatlakoo_posts_display',
			[
			   'label' => esc_html__( 'Display As', 'cepatlakoo' ),
			   'type' => Controls_Manager::SELECT,
			   'default' => 'all',
			   'options' => [
				  'all' => 'Show All',
				  'carousel' => 'Carousel',
			   ]
			]
		);
		
		$this->add_control(
			'cepatlakoo_posts_display_navigation',
			[
				'label' => esc_html__( 'Carousel navigation', 'cepatlakoo' ),
			   'type' => Controls_Manager::SELECT,
			   'default' => 'both',
			   'condition' => [
					'cepatlakoo_posts_display' => 'carousel',
				],
			   'options' => [
				  'both' => 'Both',
				  'arrow' => 'Arrows',
				  'dot' => 'Dot',
			   ]
			]
		);

		$this->add_control(
         	'cepatlakoo_posts_column',
         	[
	            'label' => esc_html__( 'Number of Column', 'cepatlakoo' ),
	            'type' => Controls_Manager::SELECT,
	            'default' => '4',
	            'options' => [
	               '1' => '1',
	               '2' => '2',
	               '3' => '3',
	               '4' => '4',
	            ]
         	]
      	);

		$this->add_control(
			'cepatlakoo_posts_per_page',
			[
				'label' => esc_html__( 'Number of Items', 'cepatlakoo' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->add_control(
         	'cepatlakoo_post_orderby',
         	[
	            'label' => esc_html__( 'Sort Order By', 'cepatlakoo' ),
	            'type' => Controls_Manager::SELECT,
	            'default' => 'date',
	            'options' => [
	               'date' => esc_html__( 'Date', 'cepatlakoo' ),
	               'title' => esc_html__( 'Title', 'cepatlakoo' ),
	               'rand' => esc_html__( 'Random', 'cepatlakoo' ),
	            ]
         	]
      	);

      	$this->add_control(
         	'cepatlakoo_post_order',
         	[
	            'label' => esc_html__( 'Sort Order', 'cepatlakoo' ),
	            'type' => Controls_Manager::SELECT,
	            'default' => 'desc',
	            'options' => [
	               'asc' => esc_html__( 'Ascending', 'cepatlakoo' ),
	               'desc' => esc_html__( 'Descending', 'cepatlakoo' ),
	            ]
         	]
      	);

		$this->end_controls_section();
	}

	protected function render() {
		
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
   		$settings = $this->get_settings();

   		$cat_slugs = implode( ',', $settings['cepatlakoo_cat_slugs'] );

		$post_count = ! empty( $settings['cepatlakoo_posts_per_page'] ) ? (int)$settings['cepatlakoo_posts_per_page'] : 4;
		$post_column = ! empty( $settings['cepatlakoo_posts_column'] ) ? (int)$settings['cepatlakoo_posts_column'] : 4;
		$post_order_by = ! empty( $settings['cepatlakoo_post_orderby'] ) ? $settings['cepatlakoo_post_orderby'] : 'date';
		$post_order = ! empty( $settings['cepatlakoo_post_order'] ) ? $settings['cepatlakoo_post_order'] : 'desc';
		$post_display = ! empty( $settings['cepatlakoo_posts_display'] ) ? $settings['cepatlakoo_posts_display'] : 'all';
		$post_display_nav = ! empty( $settings['cepatlakoo_posts_display_navigation'] ) ? 'data-nav="'.$settings['cepatlakoo_posts_display_navigation'].'"' : '';

		echo ($post_display != 'all') ? '<div class="product-carousel" '.$post_display_nav.'>' : '';
		echo do_shortcode( '[products category="'. $cat_slugs .'" limit="'. $post_count .'"  columns="'. $post_column .'" orderby="'. $post_order_by .'" order="'. $post_order .'"]' );
		echo ($post_display != 'all') ? '</div>' : '';
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Recent_Products_Widget_Elementor() );
