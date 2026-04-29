<?php
/**
 * Scroll Widget for Blog Designer Pro
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blog Designer PRO Scroll Widget.
 *
 * @class   Bdp_Utility
 * @version 1.0.0
 */
class Bdp_Widget_Scroll_Widget extends WP_Widget {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			'bdp_scroll_widget',
			'BDP &rarr; ' . __( 'Scroll Widget', 'blog-designer-pro' ),
			array(
				'classname'   => 'bdp_scroll_widget',
				'description' => __(
					'Blog Designer PRO Post Scroll Widget',
					'blog-designer-pro'
				),
			)
		);
	}
	/**
	 * Widget
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$before_widget           = $args['before_widget'];
		$after_widget            = $args['after_widget'];
		$before_title            = $args['before_title'];
		$after_title             = $args['after_title'];
		$title                   = isset( $instance['title'] ) ? $instance['title'] : '';
		$category                = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
		$number_of_post          = isset( $instance['number_of_post'] ) ? esc_attr( $instance['number_of_post'] ) : '';
		$show_post_date          = isset( $instance['show_post_date'] ) ? (bool) $instance['show_post_date'] : false;
		$display_post_content    = isset( $instance['display_post_content'] ) ? (bool) $instance['display_post_content'] : false;
		$content_words_limit     = isset( $instance['content_words_limit'] ) ? esc_attr( $instance['content_words_limit'] ) : '';
		$show_post_thumbnail     = isset( $instance['show_post_thumbnail'] ) ? (bool) $instance['show_post_thumbnail'] : false;
		$link_target             = isset( $instance['link_target'] ) && ( 'true' == $instance['link_target'] ) ? '_blank' : '_self';
		$slider_speed            = isset( $instance['slider_speed'] ) ? esc_attr( $instance['slider_speed'] ) : '';
		$slider_design           = isset( $instance['slider_design'] ) ? esc_attr( $instance['slider_design'] ) : '';
		$widget_display_category = isset( $instance['widget_display_category'] ) ? (bool) $instance['widget_display_category'] : false;
		$owl_class               = '';
		echo wp_kses( $before_widget, Bdp_Admin_Functions::args_kses() );
		if ( $title ) {
			echo wp_kses( $before_title . $title . $after_title, Bdp_Admin_Functions::args_kses() );
		}
		global $post;
		$ids = array();
		if ( 'design5' == $slider_design || 'design6' == $slider_design ) {
			$ppr_first_column  = floor( $number_of_post / 2 );
			$ppr_second_column = floor( $number_of_post - $ppr_first_column );

			// WP Query Parameter 2.
			$post_args2 = array(
				'post_type'           => 'post',
				'post_status'         => array( 'publish' ),
				'posts_per_page'      => $ppr_second_column,
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			);

			// Category Parameter.
			if ( ! empty( $category ) ) {
				$post_args2['tax_query'] = array(
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $category,
					),
				);
			}
		} else {
			$ppr_first_column = $number_of_post;
		}
		// WP Query Parameter.
		$post_args = array(
			'post_type'           => 'post',
			'post_status'         => array( 'publish' ),
			'posts_per_page'      => $ppr_first_column,
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
		);

		// Category Parameter.
		if ( ! empty( $category ) ) {
			$post_args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $category,
				),
			);
		}

		// The Query.
		$the_query          = new WP_Query( $post_args );
		$bdp_gallery_slider = __DIR__ . '/css/slick.css';
		if ( file_exists( $bdp_gallery_slider ) ) {
			wp_enqueue_style( 'bdp-slick-stylesheets', plugins_url( 'css/slick.css', __FILE__ ), null, '1.0' );
		}
		wp_enqueue_script( 'bdp-slick-script', plugins_url( 'js/slick.min.js', __FILE__ ), null, '1.0', false );
		wp_enqueue_style( 'bdp-owl-stylesheets', plugins_url( 'css/owl.min.css', __FILE__ ), null, '1.0' );
		wp_enqueue_script( 'bdp-owl-script', plugins_url( 'js/owl.carousel.min.js', __FILE__ ), null, '1.0', false );
		wp_enqueue_script( 'bdp-owl-min-script', plugins_url( 'js/owl.min.js', __FILE__ ), null, '1.0', false );
		?>
		<style>.vertical-slider ul li p {
			margin:0
		}
		.vertical-slider ul { 
			margin-left: 0; 
		}
		.vertical-slider .slides.design2 .slick-slide img,
		.vertical-slider .slides.design3 .slick-slide img,
		.vertical-slider .slides.design4 .slick-slide img {
			display: block;
			position: relative;
			width: auto;
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}
		.vertical-slider .slides.design2 .slick-arrow {
			background: rgba(0,0,0,0.5);
			font-size: 20px;
			padding: 5px 10px 5px 10px;
			color: #fff;
			line-height: normal !important;
			position: absolute;
			z-index: 1;
			top: 40%;
			cursor: pointer;
		}
		.vertical-slider .slides.design3 .slick-arrow,
		.vertical-slider .slides.design4 .slick-arrow {
			background: rgba(0,0,0,0.5);
			font-size: 20px;
			padding: 5px 10px 5px 10px;
			color: #fff;
			line-height: normal !important;
			position: absolute;
			z-index: 1;
			top: 0;
			cursor: pointer;
		}
		section#bdp_scroll_widget-2 {
			overflow:hidden;
		}
		.vertical-slider .slides.design3 i.fas.fa-chevron-left.slick-arrow,
		.vertical-slider .slides.design4 i.fas.fa-chevron-left.slick-arrow {
			right: 40px;
		}
		.vertical-slider .slides.design2 i.fas.fa-chevron-right.slick-arrow,
		.vertical-slider .slides.design3 i.fas.fa-chevron-right.slick-arrow,
		.vertical-slider .slides.design4 i.fas.fa-chevron-right.slick-arrow {
			right: 0px;
		}
		.vertical-slider .slides.design2 .wp-bd-right-content {
			position: absolute;
			bottom: 0;
			padding: 15px;
			text-align: center;
			float: none;
			width: auto;
		}
		.vertical-slider .slides.design2 .wp-bd-title,
		.vertical-slider .slides.design2 .wp-bd-content {
			width: 250px;
		}
		.vertical-slider .slides.design2 .wp-bd-title > a, 
		.vertical-slider .slides.design2 .wp-bd-category p a,
		.vertical-slider .slides.design2 .wp-bd-meta .bdp-time, 
		.vertical-slider .slides.design2 .wp-bd-content p, 
		.vertical-slider .slides.design2 .wp-bd-category p {
			color: #FFF !important;
		}
		.vertical-slider .slides.design2 .wp-bd-right-content .wp-bd-category p a {
			border-bottom: 3px solid;
		}
		.vertical-slider .slides.design2 .wp-bd-right-content .wp-bd-title > a,
		.vertical-slider .slides.design3 .wp-bd-right-content .wp-bd-title > a,
		.vertical-slider .slides.design3 .wp-bd-right-content .wp-bd-category p a,
		.vertical-slider .slides.design4 .wp-bd-right-content .wp-bd-title > a,
		.vertical-slider .slides.design4 .wp-bd-right-content .wp-bd-category p a {
			text-decoration: none;
		}
		.vertical-slider .slides.design3 .wp-bd-post-li,
		.vertical-slider .slides.design4 .wp-bd-post-li {
			margin-top: 40px;
		}
		.vertical-slider .slides.design3 .wp-bd-right-content .wp-bd-category p a,
		.vertical-slider .slides.design4 .wp-bd-right-content .wp-bd-category p a {
			border-bottom: 3px solid #f1f1f1;
		}
		.slides.design2 .wp-bd-right-content div,
		.slides.design3 .wp-bd-right-content div,
		.slides.design4 .wp-bd-right-content div {
			padding-bottom: 10px;
		}
		.vertical-slider .slides.design4 .wp-bd-main-class {
			display: flex;
			width: 100%;
		}
		.vertical-slider .slides.design4 .wp-bd-count {
			padding: 20px;
			font-size: 50px;
			color: #000;
		}
		.vertical-slider .slides.design4 .wp-bd-right-content {
			padding: 20px;
			padding-top: 30px;
		}
		.vertical-slider .slides .wp-bd-right-content .wp-bd-category {
			font-size: 15px;
		}
		.vertical-slider .slides .wp-bd-right-content .wp-bd-category a {
			margin-right: 5px;
		}
		.vertical-slider .slides.design2 .wp-bd-title,
		.vertical-slider .slides.design3 .wp-bd-title,
		.vertical-slider .slides.design4 .wp-bd-title {
			font-size: 18px;
		}
		.vertical-slider .slides.design2 .wp-bd-meta .bdp-time,
		.vertical-slider .slides.design3 .wp-bd-meta .bdp-time,
		.vertical-slider .slides.design4 .wp-bd-meta .bdp-time {
			font-size: 14px;
		}
		.vertical-slider .slides.design5 .wp-bd-post-li.wp-bd-clearfix {
			margin-bottom: -60px;
		}
		.vertical-slider .slides.design5 .wp-bd-right-content {
			position: relative;
			bottom: 100px;
			padding: 18px 20px;
			z-index: 4;
			display: grid;
		}
		.vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-title {
			order: 2;
			font-size: 20px;
			color: #FFF;
		}
		.vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-title a, .vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-category a, .vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-content {
			color: #FFF;
		}
		.vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-meta {
			font-size: 14px;
			color: #FFF;
		}
		.vertical-slider .slides.design5 .wp-bd-left-img .wp-bd-image-bg img {
			border-radius: 15px;
		}
		.owl-carousel .owl-nav {
			top: -53px;
			right: -4px;
			position: absolute;
			z-index: 1;
			cursor: pointer;
		}
		.owl-carousel .owl-nav .owl-prev, .owl-carousel .owl-nav .owl-next {
			padding: 5px;
			background: none !important;
			color: #FFF !important;
			margin-left: 21px;
		}
		.owl-carousel .owl-nav .owl-prev:hover, .owl-carousel .owl-nav .owl-next:hover,
		.owl-carousel .owl-nav .owl-prev:focus, .owl-carousel .owl-nav .owl-next:focus {
			border: none !important;
			outline: none;
			margin-right: 3px;
			margin-left: 24px;
			margin-top: 3px;
			padding: 5px;
		}
		.vertical-slider .slides.design6 .wp-bd-post-li.wp-bd-clearfix {
			margin-bottom: 20px;
		}
		.vertical-slider .slides.design6 .wp-bd-list-content {
			display: flex;
		}
		.vertical-slider .slides.design6 .wp-bd-right-content {
			margin-left: 20px;
		}
		.vertical-slider .slides.design6 .wp-bd-left-img .wp-bd-image-bg img {
			width: 80px;
			height: 80px;
		}
		.vertical-slider .slides.design6 .wp-bd-post-li .wp-bd-list-content .wp-bd-left-img {
			width: 80px;
		}
		.vertical-slider .slides.design6 .wp-bd-right-content .wp-bd-title {
			order: 2;
		}
		.vertical-slider .slides.design6 .wp-bd-right-content {
			display: grid;
		}
		.vertical-slider .slides.design6 .wp-bd-right-content .wp-bd-title {
			font-size: 20px;
		}
		.vertical-slider .slides.design5 .wp-bd-right-content .wp-bd-title a,
		.vertical-slider .slides.design6 .wp-bd-right-content .wp-bd-title a {
			text-decoration: none;
		}
		.vertical-slider .slides.design6 .wp-bd-right-content .wp-bd-meta {
			font-size: 14px;
			margin: 10px 0 -12px 0 !important;
		}
		@media screen and (max-width: 767px) {
			.vertical-slider .slides.design2 i.fas.fa-chevron-right.slick-arrow,
			.vertical-slider .slides.design3 i.fas.fa-chevron-right.slick-arrow,
			.vertical-slider .slides.design4 i.fas.fa-chevron-right.slick-arrow {
				right: 30px;
			}
			.vertical-slider .slides.design3 i.fas.fa-chevron-left.slick-arrow,
			.vertical-slider .slides.design4 i.fas.fa-chevron-left.slick-arrow {
				right: 70px;
			}
		}
		@media screen and (min-width: 652px) and (max-width: 1024px) {
			.vertical-slider .slides.design2 .wp-bd-right-content {
				text-align: left;
			}
		}
		@media screen and (min-width: 768px) and (max-width: 1199px) {
			.vertical-slider .slides.design2 .wp-bd-title,
			.vertical-slider .slides.design2 .wp-bd-content {
				width: 150px;
			}
		}
		@media screen and (min-width: 1024px) and (max-width: 1099px) {
			.vertical-slider .slides.design4 .wp-bd-count {
				padding: 10px;
			}
			.vertical-slider .slides.design4 .wp-bd-right-content {
				padding: 0;
			}
		}
		.vertical-slider .wp-bd-post-li .wp-bd-list-content .wp-bd-left-img {
			width: 100%;
		}
		</style>
		<script>
			jQuery(document).ready(function(e) {
				var slider_design = '<?php echo esc_attr( $slider_design ); ?>';
				if(slider_design == 'design1') {
					jQuery('.vertical-slider .slides').slick({
						dots: false,
						arrows: false,
						vertical: true,
						slidesToShow: 3,
						slidesToScroll: 1,
						autoplay: true,
						autoplaySpeed: <?php echo esc_attr( $slider_speed ); ?>,
						verticalSwiping: true,
					});
				} 
				if(slider_design == 'design2' || slider_design == 'design3' || slider_design == 'design4') {
					jQuery('.vertical-slider .slides').addClass(slider_design);
					jQuery('.vertical-slider .slides').slick({
						dots: false,
						adaptiveHeight: true,
						slidesToShow: 1,
						slidesToScroll: 1,
						autoplay: true,
						infinite: true,
						autoplaySpeed: <?php echo esc_attr( $slider_speed ); ?>,
						prevArrow:'<i class="fas fa-chevron-left"></i>',
						nextArrow:'<i class="fas fa-chevron-right"></i>',
					});
				}
				if(slider_design == 'design5' || slider_design == 'design6') {
					jQuery('.vertical-slider .slides').addClass(slider_design);
					jQuery('.owl-carousel').owlCarousel({
						loop: true,
						margin: 30,
						nav: true,
						dots: false,
						smartSpeed:1500,
						navText: [ '<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
						responsive: {
							0: {
								items: 1
							},
							576: {
								items: 1
							},
							992: {
								items: 1
							},
						}
					});
				} 
			});
		</script>
		<?php
		// The Loop.
		if ( 'design5' == $slider_design || 'design6' == $slider_design ) {
			echo '<div class="owl-carousel">';
				echo '<div class="item">';
		}
		if ( $the_query->have_posts() ) {
			echo '<div class="vertical-slider">';
				echo '<ul class="slides">';
				$count = 1;
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$ids[]          = get_the_ID();
					$post_id    = $post->ID;
					$image      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( 300, 300 ), false );
					$feat_image = isset( $image[0] ) ? $image[0] : '';
					$post_link  = get_permalink( $post->ID );
					$category   = get_the_category();
					$content    = '';
				if ( has_excerpt( $post_id ) ) {
					$content = get_the_excerpt();
				} else {
					$content = ! empty( $content ) ? 'rer' : get_the_content();
				}
				if ( ! empty( $content ) ) {
					$content = strip_shortcodes( $content ); // Strip shortcodes.
					$content = wp_trim_words( $content, $content_words_limit, '..' );
				}
				?>
				<li class="wp-bd-post-li wp-bd-clearfix">
					<?php if ( 'true' == $show_post_thumbnail ) { ?>
						<div class="wp-bd-list-content">
							<?php if ( ! empty( $feat_image ) ) { ?>
							<div class="wp-bd-left-img">
								<div class="wp-bd-image-bg">
									<a  href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>">                                       
										<img src="<?php echo esc_url( $feat_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />                                       
									</a>
								</div>
							</div>
							<?php } ?>
							<?php if ( 'design4' == $slider_design ) { ?>
							<div class="wp-bd-main-class">
								<div class="wp-bd-count"><p><?php echo esc_html( $count ); ?></p></div>
								<?php } ?>
								<div class="wp-bd-right-content 
								<?php
								if ( empty( $feat_image ) ) {
									echo 'bdp-post-full-content'; }
								?>
								">
									<?php if ( 'true' == $widget_display_category ) { ?>
										<div class="wp-bd-category">    
											<p><?php echo esc_html( the_category( ' ' ) ); ?></p>
										</div>
									<?php } ?>								
										<h4 class="wp-bd-title">
											<a href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_title(); ?></a>
										</h4>

										<?php if ( 'true' == $show_post_date ) { ?>
										<div class="wp-bd-meta" 
											<?php
											if ( 'true' != $display_post_content ) {
												?>
											style="margin:0px;" <?php } ?>>
										<span class="wp-bd-meta-innr bdp-time">
											<?php if ( 'design2' == $slider_design || 'design3' == $slider_design || 'design4' == $slider_design || 'design5' == $slider_design || 'design6' == $slider_design ) { ?>
												<i class="far fa-clock"> </i><?php } ?> &nbsp;<?php echo get_the_date(); ?></span>
										</div>
											<?php
										}
										if ( 'true' == $display_post_content ) {
											?>
											<div class="wp-bd-content">    
												<p><?php echo wp_kses( $content, Bdp_Admin_Functions::args_kses() ); ?></p>
											</div>
									<?php } ?>
								</div>
							<?php if ( 'design4' == $slider_design ) { ?>
								</div>
							<?php } ?>
						</div>

					<?php } else { ?>
						<div class="wp-bd-list-content">							
							<h4 class="wp-bd-title"> 
								<a href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_title(); ?></a>
							</h4>

							<?php if ( 'true' == $show_post_date ) { ?>
							<div class="wp-bd-meta" 
								<?php
								if ( 'true' != $display_post_content ) {
									?>
								style="margin:0px;" <?php } ?>>
								<span class="wp-bd-meta-innr bdp-time"><i class="far fa-clock"> </i> &nbsp;<?php echo get_the_date(); ?></span>
							</div>
								<?php
							}

							if ( 'true' == $display_post_content ) {
								?>
							<div class="wp-bd-content">
								<p><?php echo wp_kses( $content, Bdp_Admin_Functions::args_kses() ); ?></p>
							</div>
							<?php } ?>
						</div>
					<?php } ?>
				</li>
				<?php
				++$count;
			}
				echo '</ul>';
			echo '</div>';
		}
		if ( 'design5' == $slider_design || 'design6' == $slider_design ) {
				echo '</div>';
				echo '<div class="item">';
			if ( ! empty( $ids ) ) {
				$post_args2['post__not_in'] = $ids;
			}
			$the_query2 = new WP_Query( $post_args2 );
			if ( $the_query2->have_posts() ) {
				echo '<div class="vertical-slider">';
					echo '<ul class="slides">';
					$count = 1;
				while ( $the_query2->have_posts() ) {
						$the_query2->the_post();
						$image      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( 300, 300 ), false );
						$feat_image = isset( $image[0] ) ? $image[0] : '';
						$post_link  = get_permalink( $post->ID );
						$category   = get_the_category();
						$content    = '';
					if ( has_excerpt( $post_id ) ) {
						$content = get_the_excerpt();
					} else {
						$content = ! empty( $content ) ? 'rer' : get_the_content();
					}
					if ( ! empty( $content ) ) {
						$content = strip_shortcodes( $content ); // Strip shortcodes.
						$content = wp_trim_words( $content, $content_words_limit, '..' );
					}
					?>
					<li class="wp-bd-post-li wp-bd-clearfix">
						<?php if ( 'true' == $show_post_thumbnail ) { ?>
							<div class="wp-bd-list-content">
								<?php if ( ! empty( $feat_image ) ) { ?>
									<div class="wp-bd-left-img">
										<div class="wp-bd-image-bg">
											<a  href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>">                                       
												<img src="<?php echo esc_url( $feat_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />                                       
											</a>
										</div>
									</div>
								<?php } ?>
								<?php if ( 'design4' == $slider_design ) { ?>
								<div class="wp-bd-main-class">
									<div class="wp-bd-count"><p><?php echo esc_html( $count ); ?></p></div>
									<?php } ?>
									<div class="wp-bd-right-content 
									<?php
									if ( empty( $feat_image ) ) {
										echo 'bdp-post-full-content'; }
									?>
									">
										<?php if ( 'true' == $widget_display_category ) { ?>
											<div class="wp-bd-category">    
												<p><?php echo esc_html( the_category( ' ' ) ); ?></p>
											</div>
										<?php } ?>								
											<h4 class="wp-bd-title">
												<a href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_title(); ?></a>
											</h4>

											<?php if ( 'true' == $show_post_date ) { ?>
											<div class="wp-bd-meta" 
												<?php
												if ( 'true' != $display_post_content ) {
													?>
												style="margin:0px;" <?php } ?>>
											<span class="wp-bd-meta-innr bdp-time">
												<?php if ( 'design2' == $slider_design || 'design3' == $slider_design || 'design4' == $slider_design || 'design5' == $slider_design || 'design6' == $slider_design ) { ?>
													<i class="far fa-clock"> </i><?php } ?> &nbsp;<?php echo get_the_date(); ?></span>
											</div>
												<?php
											}
											if ( 'true' == $display_post_content ) {
												?>
												<div class="wp-bd-content">    
													<p><?php echo wp_kses( $content, Bdp_Admin_Functions::args_kses() ); ?></p>
												</div>
										<?php } ?>
									</div>
								<?php if ( 'design4' == $slider_design ) { ?>
									</div>
								<?php } ?>
							</div>

						<?php } else { ?>
							<div class="wp-bd-list-content">							
								<h4 class="wp-bd-title"> 
									<a href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_title(); ?></a>
								</h4>

								<?php if ( 'true' == $show_post_date ) { ?>
								<div class="wp-bd-meta" 
									<?php
									if ( 'true' != $display_post_content ) {
										?>
									style="margin:0px;" <?php } ?>>
									<span class="wp-bd-meta-innr bdp-time"><i class="far fa-clock"> </i> &nbsp;<?php echo get_the_date(); ?></span>
								</div>
									<?php
								}

								if ( 'true' == $display_post_content ) {
									?>
								<div class="wp-bd-content">
									<p><?php echo wp_kses( $content, Bdp_Admin_Functions::args_kses() ); ?></p>
								</div>
								<?php } ?>
							</div>
						<?php } ?>
					</li>
					<?php
					++$count;
				}
					echo '</ul>';
				echo '</div>';
			}
				echo '</div>';
			echo '</div>';
		}
		wp_reset_postdata();
		echo wp_kses( $after_widget, Bdp_Admin_Functions::args_kses() );
	}
	/**
	 * Form
	 *
	 * @param array $instance instance.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults = array(
			'number_of_post'          => 5,
			'title'                   => esc_html__( 'Latest Posts Scrolling', 'blog-designer-pro' ),
			'show_post_date'          => true,
			'show_post_thumbnail'     => true,
			'category'                => 0,
			'slider_speed'            => 500,
			'link_target'             => false,
			'content_words_limit'     => 20,
			'display_post_content'    => false,
			'slider_design'           => 0,
			'widget_display_category' => false,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title                   = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$category                = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
		$number_of_post          = isset( $instance['number_of_post'] ) ? esc_attr( $instance['number_of_post'] ) : '5';
		$show_post_date          = isset( $instance['show_post_date'] ) ? (bool) $instance['show_post_date'] : false;
		$display_post_content    = isset( $instance['display_post_content'] ) ? (bool) $instance['display_post_content'] : false;
		$content_words_limit     = isset( $instance['content_words_limit'] ) ? esc_attr( $instance['content_words_limit'] ) : '';
		$show_post_thumbnail     = isset( $instance['show_post_thumbnail'] ) ? (bool) $instance['show_post_thumbnail'] : false;
		$link_target             = isset( $instance['link_target'] ) ? (bool) $instance['link_target'] : false;
		$slider_speed            = isset( $instance['slider_speed'] ) ? esc_attr( $instance['slider_speed'] ) : '';
		$slider_design           = isset( $instance['slider_design'] ) ? esc_attr( $instance['slider_design'] ) : '';
		$widget_display_category = isset( $instance['widget_display_category'] ) ? (bool) $instance['widget_display_category'] : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?>:</label>
			<?php
				$dropdown_args = array(
					'taxonomy'        => 'category',
					'class'           => 'widefat',
					'show_option_all' => esc_html__( 'All', 'blog-designer' ),
					'id'              => $this->get_field_id( 'category' ),
					'name'            => $this->get_field_name( 'category' ),
					'selected'        => $instance['category'],
				);
				wp_dropdown_categories( $dropdown_args );
				?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_post' ) ); ?>"><?php esc_html_e( 'Posts Per Page', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_post' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_post' ) ); ?>" type="number" value="<?php echo esc_attr( $number_of_post ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slider_design' ) ); ?>"><?php esc_html_e( 'Select Slider Design', 'blog-designer-pro' ); ?>:</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'slider_design' ) ); ?>" id="select_slider_design">
				<option value="design1" <?php selected( $slider_design, 'design1' ); ?> ><?php esc_html_e( 'Default Slider Layout', 'blog-designer-pro' ); ?></option>
				<option value="design2" <?php selected( $slider_design, 'design2' ); ?> ><?php esc_html_e( 'Post Slider Layout 1', 'blog-designer-pro' ); ?></option>
				<option value="design3" <?php selected( $slider_design, 'design3' ); ?> ><?php esc_html_e( 'Post Slider Layout 2', 'blog-designer-pro' ); ?></option>
				<option value="design4" <?php selected( $slider_design, 'design4' ); ?> ><?php esc_html_e( 'Post Slider Layout 3', 'blog-designer-pro' ); ?></option>
				<option value="design5" <?php selected( $slider_design, 'design5' ); ?> ><?php esc_html_e( 'Slider Layout 1', 'blog-designer-pro' ); ?></option>
				<option value="design6" <?php selected( $slider_design, 'design6' ); ?> ><?php esc_html_e( 'Slider Layout 2', 'blog-designer-pro' ); ?></option>
			</select>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_post_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_post_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_post_date' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_post_date' ) ); ?>"><?php esc_attr_e( 'Display Date', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $widget_display_category ); ?> id="<?php echo esc_attr( $this->get_field_id( 'widget_display_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_display_category' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_display_category' ) ); ?>"><?php esc_attr_e( 'Display Category', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $display_post_content ); ?> id="<?php echo esc_attr( $this->get_field_id( 'display_post_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_post_content' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_post_content' ) ); ?>"><?php esc_attr_e( 'Display Short Content', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'content_words_limit' ) ); ?>"><?php esc_html_e( 'Content Words Limit', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content_words_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content_words_limit' ) ); ?>" type="number" value="<?php echo esc_attr( $content_words_limit ); ?>" />
			<span class="description"><em><?php esc_html_e( 'Content words limit will only work if Display Short Content checked', 'blog-designer-pro' ); ?></em></span>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_post_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_post_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_post_thumbnail' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_post_thumbnail' ) ); ?>"><?php esc_attr_e( 'Display Thumbnail in left side', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $link_target ); ?> id="<?php echo esc_attr( $this->get_field_id( 'link_target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_target' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_target' ) ); ?>"><?php esc_attr_e( 'Open Link in a New Tab', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slider_speed' ) ); ?>"><?php esc_html_e( 'Slider Speed', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slider_speed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slider_speed' ) ); ?>" type="number" value="<?php echo esc_attr( $slider_speed ); ?>" />
		</p>
		<?php
	}
	/**
	 * Update
	 *
	 * @param array $new_instance new instance.
	 * @param array $old_instance old instance.
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                            = array();
		$instance['title']                   = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number_of_post']          = isset( $new_instance['number_of_post'] ) ? $new_instance['number_of_post'] : '';
		$instance['show_post_date']          = isset( $new_instance['show_post_date'] ) ? (bool) $new_instance['show_post_date'] : false;
		$instance['show_post_thumbnail']     = isset( $new_instance['show_post_thumbnail'] ) ? (bool) $new_instance['show_post_thumbnail'] : false;
		$instance['category']                = isset( $new_instance['category'] ) ? $new_instance['category'] : '';
		$instance['slider_speed']            = isset( $new_instance['slider_speed'] ) ? $new_instance['slider_speed'] : '';
		$instance['link_target']             = isset( $new_instance['link_target'] ) ? (bool) $new_instance['link_target'] : false;
		$instance['display_post_content']    = isset( $new_instance['display_post_content'] ) ? (bool) $new_instance['display_post_content'] : false;
		$instance['content_words_limit']     = isset( $new_instance['content_words_limit'] ) ? $new_instance['content_words_limit'] : 20;
		$instance['slider_design']           = isset( $new_instance['slider_design'] ) ? $new_instance['slider_design'] : '';
		$instance['widget_display_category'] = isset( $new_instance['widget_display_category'] ) ? (bool) $new_instance['widget_display_category'] : false;

		return $instance;
	}
}
/**
 * Scroll Widget
 *
 * @return void
 */
function bdp_scroll_widget() {
	register_widget( 'Bdp_Widget_Scroll_Widget' );
}

add_action( 'widgets_init', 'bdp_scroll_widget' );
