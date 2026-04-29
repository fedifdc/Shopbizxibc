<?php
/**
 * Template Name: Homepage
 * The template for displaying page homepage
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

<div id="main" <?php echo cepatlakoo_fb_pixel_data(); ?> >
	<div id="maincontent">
		<div class="clearfix">
			<div id="contentarea" class="fullwidth">
			<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						the_content();
						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'cepatlakoo' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					}
				} else {
					get_template_part( 'content-none' );
				}
			?>
			</div>
		</div>
	</div>
</div>
<!-- End : Main Content -->

<?php get_footer(); ?>
