<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
				<h1 class="page-title"><?php cepatlakoo_page_title(); ?></h1>
				<div class="postlist">
				<?php
	            	// The loop
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
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
