<?php
/**
 * Template for displaying Page post type.
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
<div id="main">
    <div id="maincontent">  
        <div class="container clearfix">
            <?php get_template_part( 'includes/breadcrumb' ); ?>

            <?php get_sidebar(); ?>
            
            <div id="contentarea">
                <article id="cepatlakoo-404-post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?> itemscope itemtype="https://schema.org/CreativeWork">
                    <h1 class="post-title"><?php esc_html_e( 'Page Not Found', 'cepatlakoo' ); ?></h1>
                    <div class="entry-content">
                        <p><?php esc_html_e( 'The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo' ); ?></p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
<!-- End : Main Content -->

<?php get_footer(); ?>