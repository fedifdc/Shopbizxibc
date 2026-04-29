<?php
/**
 * Template Name: Simple Checkout Page
 * The template for displaying simple checkout page
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.2.5
 */
?>

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
// Get header
if ( class_exists('WooCommerce') && ! is_wc_endpoint_url( 'order-received' ) ) {
	get_header( 'blank' );
} else {
	get_header();
}
?>

<!-- Start : Main Content -->
<div id="main" <?php echo cepatlakoo_fb_pixel_data(); ?> >
	<div id="maincontent">
		<div class="container clearfix">
			<div id="contentarea" class="fullwidth">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article <?php post_class(); ?> >
							<div class="entry-content">
								<?php the_content(); ?>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				<?php else : ?>
					<?php get_template_part( 'content-none' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<!-- End : Main Content -->

<?php
// Get footer
if ( class_exists('WooCommerce') && !is_wc_endpoint_url( 'order-received' ) ) {
	get_footer( 'blank' );
} else {
	get_footer();
}
?>
