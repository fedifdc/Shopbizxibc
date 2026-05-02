<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Product_Statistics_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-product-statistics';
	}

	public function get_title() {
		return esc_html__( 'CL - Product Statistics', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-number-field';
	}

	protected function register_controls() {
		global $cl_options;

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'CL - Product Statistics', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_product_statistics_info',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'Widget CL - Product Statistics khusus digunakan pada halaman produk, untuk menampilkan statistik berapa kali halaman produk dilihat dan jumlah produk yang terjual.', 'cepatlakoo' ),
				'content_classes' => 'clel-notice',
			]
		);

		$this->add_responsive_control(
			'cepatlakoo_stats_alignment',
			[
				'label' => esc_html__( 'Alignment', 'cepatlakoo' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'cepatlakoo' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cepatlakoo' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'cepatlakoo' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'cepatlakoo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'cepatlakoo_stats_typography',
				'selector' => '{{WRAPPER}} .cl-view-sold > span',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'cepatlakoo' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cl-view-sold > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
		global $product;
        global $cl_options;
        
        $view_count = number_format( (int)get_post_meta($product->get_id(), 'cepatlakoo_views_count', true), 0);
        $sales_count = number_format( (int)get_post_meta($product->get_id(), 'total_sales', true), 0);
        $view_label = $cl_options['cepatlakoo_wc_product_view_counter_text'] ? $cl_options['cepatlakoo_wc_product_view_counter_text'] : 'Dilihat';
        $sold_label = $cl_options['cepatlakoo_wc_product_sold_counter_text'] ? $cl_options['cepatlakoo_wc_product_sold_counter_text'] : 'Terjual';
        ?>

        <?php if ( $cl_options['cepatlakoo_wc_product_meta_counter'] != 'hide' ) : ?>
        <div class="cl-view-sold">
            <?php if( isset($cl_options['cepatlakoo_wc_product_meta_counter']) && ($cl_options['cepatlakoo_wc_product_meta_counter'] == 'all' || $cl_options['cepatlakoo_wc_product_meta_counter'] == 'view') ): ?>
                <span><?php echo $view_label;?> <span class="cl-view-value"><?php echo $view_count; ?>x</span></span>
            <?php endif; ?>
            &bull;
            <?php if( isset($cl_options['cepatlakoo_wc_product_meta_counter']) && ($cl_options['cepatlakoo_wc_product_meta_counter'] == 'all' || $cl_options['cepatlakoo_wc_product_meta_counter'] == 'sold') ): ?>
                <span><?php echo $sold_label; ?> <span class="cl-sold-value"><?php echo $sales_count; ?></span></span>
            <?php endif; ?>
        </div>
        <?php endif;
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Product_Statistics_Widget_Elementor() );
