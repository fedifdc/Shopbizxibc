<?php
/**
 * Template Name: Fullwidth
 * The template for displaying page fullwidth
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

			<div id="contentarea" class="fullwidth">
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article <?php post_class(); ?> >
							<div class="entry-content">
								<?php
								the_content();
								wp_link_pages( array(
									'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'cepatlakoo' ) . '</span>',
									'after'       => '</div>',
									'link_before' => '<span>',
									'link_after'  => '</span>',
								) );
								?>
							</div>
						</article>
						<?php comments_template( '', true ); ?>
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

<?php get_footer(); ?>
