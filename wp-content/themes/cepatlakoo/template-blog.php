<?php
/**
 * Template Name: Blog
 * The template for displaying blog template
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
?>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>

<!-- Start : Main Content -->
<div id="main" <?php echo cepatlakoo_fb_pixel_data(); ?> >
	<div id="maincontent">
		<div class="container clearfix">
			<?php get_template_part( 'includes/breadcrumb' ); ?>

			<div id="contentarea">
				<div class="postlist">
				<?php
		            if ( get_query_var( 'paged' ) ) {
		                $paged = get_query_var( 'paged' );
		            } elseif ( get_query_var( 'page' ) ) {
		                $paged = get_query_var( 'page' );
		            } else {
		                $paged = 1;
		            }

		            $args = array(
						'post_type' 			=> 'post',
						'post_status' 			=> 'publish',
						'ignore_sticky_posts' 	=> 1,
						'paged'         		=> $paged
					);

					$wp_query = new WP_Query();
					$wp_query->query( $args );

					if ( $wp_query->have_posts() ) {
						while( $wp_query->have_posts() ) {
							$wp_query->the_post();
							get_template_part( 'content' );
						}
						wp_reset_postdata();
						get_template_part( 'includes/pagination' );
					} else {
						esc_html_e( 'The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo' );
					}
				?>
				</div>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<!-- End : Main Content -->
<?php get_footer(); ?>
