<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Hand_Picked_Products_Widget_Elementor extends Widget_Base {
	public function get_name() {
		return 'cepatlakoo-hand-picked-product';
	}

	public function get_title() {
		return esc_html__( 'CL - Handpicked Products', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_handpicked_products',
			[
				'label' => esc_html__( 'CL - Handpicked Products', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_products_id',
			[
				'label' => esc_html__( 'Products ID', 'cepatlakoo' ),
				'type' => Controls_Manager::TEXT,
				'title' => esc_html__( 'Enter products id, separate by comma (,) ex : 1990, 1890, etc', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_info',
			[
				'label' => esc_html__( 'Peringatan', 'cepatlakoo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Field "Products ID" ke depannya tidak akan dihapus lagi, gunakan field "Select Products" sebagai penggantinya. Harap kosongkan saja value pada field "Products ID".', 'cepatlakoo' ),
				'content_classes' => 'clel-notice',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Get all products
		$products = wc_get_products( array(
			'limit' => -1,
			'status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
		) );

		$get_options = [];
		foreach ( $products as $product ) {
			$get_options[ $product->get_ID() ] = $product->get_title() .' ('. $product->get_ID() .')';
		}

		$this->add_control(
			'cepatlakoo_products',
			[
				'label' => __( 'Select Products', 'plugin-domain' ),
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
				'label' => esc_html__( 'Carousel Navigation', 'cepatlakoo' ),
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
	            'label' => esc_html__( 'Number of Columns', 'cepatlakoo' ),
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
		$products = implode( ',', $settings['cepatlakoo_products'] );

		$post_ids = !empty( $settings['cepatlakoo_products_id'] ) ? $settings['cepatlakoo_products_id'] : '';
		$product_ids = !empty( $products ) ? $products : $post_ids;
		$post_count = !empty( $settings['cepatlakoo_posts_per_page'] ) ? (int)$settings['cepatlakoo_posts_per_page'] : 4;
		$post_column = !empty( $settings['cepatlakoo_posts_column'] ) ? (int)$settings['cepatlakoo_posts_column'] : 4;
		$post_order_by = !empty( $settings['cepatlakoo_post_orderby'] ) ? $settings['cepatlakoo_post_orderby'] : 'date';
		$post_order = !empty( $settings['cepatlakoo_post_order'] ) ? $settings['cepatlakoo_post_order'] : 'desc';
		$post_display = !empty( $settings['cepatlakoo_posts_display'] ) ? $settings['cepatlakoo_posts_display'] : 'all';
		$post_display_nav = !empty( $settings['cepatlakoo_posts_display_navigation'] ) ? 'data-nav="'.$settings['cepatlakoo_posts_display_navigation'].'"' : '';

		echo ($post_display != 'all') ? '<div class="product-carousel" '.$post_display_nav.'>' : '';
		echo do_shortcode( '[products limit="'. $post_count .'"  columns="'. $post_column .'" orderby="'. $post_order_by .'" order="'. $post_order .'" ids="'. $product_ids .'"]' );
		echo ($post_display != 'all') ? '</div>' : '';
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Hand_Picked_Products_Widget_Elementor() );
