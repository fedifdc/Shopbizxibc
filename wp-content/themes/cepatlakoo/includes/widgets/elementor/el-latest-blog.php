<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CepatLakoo_Latest_News_Widget_Elementor extends Widget_Base {

	public function get_name() {
		return 'cepatlakoo-latest-news';
	}

	public function get_title() {
		return esc_html__( 'CL - Latest Blog', 'cepatlakoo' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-bullet-list';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'cepatlakoo_section_name',
			[
				'label' => esc_html__( 'CL - Latest Blog', 'cepatlakoo' ),
			]
		);

		$this->add_control(
			'cepatlakoo_posts_per_page',
			[
				'label' => esc_html__( 'Number of Posts', 'cepatlakoo' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
			]
		);

		$this->add_control(
			'cepatlakoo_image_size',
			[
				'label' => __( 'Image Size', 'cepatlakoo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'thumbnail'  => __( 'thumbnail', 'cepatlakoo' ),
					'medium' => __( 'medium', 'cepatlakoo' ),
					'large' => __( 'large', 'cepatlakoo' ),
					'full' => __( 'full', 'cepatlakoo' ),
				],
			]
		);

		$this->add_control(
		    'cepatlakoo_posts_limiter',
		    [
		        'label' => esc_html__( 'Post Content Limiter', 'cepatlakoo' ),
		        'type' => Controls_Manager::SLIDER,
		        'default' => [
		            'size' => 15,
		        ],
		        'range' => [
		            'size' => [
		                'min' => 0,
		                'max' => 100,
		                'step' => 5,
		            ]
		        ],
		        'size_units' => [ 'size' ],
		    ]
		);

		$this->end_controls_section();
	}

	protected function render() {
		// Reference : https://github.com/pojome/elementor/issues/738
		// get our input from the widget settings.
   		
   		$settings = $this->get_settings();
		$cepatlakoo_post_count = ! empty( $settings['cepatlakoo_posts_per_page'] ) ? (int)$settings['cepatlakoo_posts_per_page'] : 4;
		$cepatlakoo_image_size = ! empty( $settings['cepatlakoo_image_size'] ) ? $settings['cepatlakoo_image_size'] : 'medium';
		$cepatlakoo_posts_limiter = ! empty( $settings['cepatlakoo_posts_limiter'] ) ? $settings['cepatlakoo_posts_limiter'] : 60;
	?>
	    <section id="recents-article-widget" class="elementor-widget">
			<div class="postlist row column-4">
			<?php
				$args_latest_blog = array(
			        'post_type'	=> 'post',
					'post_status' => 'publish',
					'ignore_sticky_posts'	=> 1,
					'posts_per_page' => absint( $cepatlakoo_post_count ),
					'orderby' => 'date',
					'order' => 'desc'
				);

			    $wp_query = new \WP_Query();
			    $wp_query->query( $args_latest_blog );
			    ?>

			    <?php if ( $wp_query->have_posts() ) : ?>
			    	<?php while ( $wp_query->have_posts() ) : ?>
			    		<?php $wp_query->the_post(); ?>
						<article class="hentry column">
							<?php if ( has_post_thumbnail() ) : ?>
							<div class="thumbnail">
								<a href="<?php the_permalink() ?>" title="<?php get_the_title() ?>"><?php echo get_the_post_thumbnail( get_the_ID(), $cepatlakoo_image_size ) ?></a>
							</div>
							<?php endif; ?>
							<div class="detail">
								<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php get_the_title() ?>"><?php the_title(); ?></a></h3>
								<p><?php echo wp_trim_words( get_the_content(), $cepatlakoo_posts_limiter['size'] , '...')  ?></p>
							</div>
							<div class="entry-meta">
								<span><i class="icon icon-calendar"></i> <?php echo date_i18n( 'd F, Y', strtotime( get_the_date('Y-m-d'), false ) ) ?></span>
							</div>
						</article>
					<?php endwhile; ?>
				<?php else: ?>
					<article class="hentry column">
						<p><?php esc_html_e('There\'s no news found.', 'cepatlakoo'); ?></p>
					</article>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</section>
	<?php
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
Plugin::instance()->widgets_manager->register( new CepatLakoo_Latest_News_Widget_Elementor() );
