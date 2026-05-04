<?php
/**
 * The template for displaying page default
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

<?php
if ( class_exists( 'WooCommerce' ) ) {
	$check_page = is_woocommerce() || is_cart() || is_checkout() || is_account_page();
} else {
    $check_page = '';
}
?>

<?php if ( $check_page ) : ?>
	<div id="main" <?php echo cepatlakoo_fb_pixel_data(); ?> >
		<div id="maincontent">
			<div class="container clearfix">
				<?php get_template_part( 'includes/breadcrumb' ); ?>

				<div id="contentarea" class="fullwidth">
					<h1 class="page-title"><?php the_title(); ?></h1>

					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<article <?php post_class( 'hentry' ) ?> itemscope itemtype="https://schema.org/CreativeWork">
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
						<?php  comments_template( '', true ); ?>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					<?php else : ?>
						<?php get_template_part( 'content-none' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

<?php else : ?>

	<div id="main" <?php echo cepatlakoo_fb_pixel_data(); ?> >
		<div id="maincontent">
			<div class="container clearfix">
				<?php get_template_part( 'includes/breadcrumb' ); ?>

				<div id="contentarea">
					<h1 class="page-title"><?php the_title(); ?></h1>
					
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post();  ?>
							<article <?php post_class() ?> >
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

				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
