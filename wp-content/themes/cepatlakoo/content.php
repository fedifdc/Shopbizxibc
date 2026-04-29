<?php
/**
 * The template for displaying posts in the Standard post format.
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
?>

<article id="cepatlakoo-post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/CreativeWork">
	<?php if ( is_single() ) : ?>
		<h1 class="post-title" itemprop="headline"><?php the_title(); ?></h1>
		<?php cepatlakoo_entry_meta(); ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="thumbnail">
		        <a href="<?php the_permalink() ?>" title="<?php get_the_title() ?>">
		         	<?php the_post_thumbnail( 'cepatlakoo-blog-thumb', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
		        </a>
			</div>
	    <?php endif; ?>

		<div class="entry-content" itemprop="text">
			<?php the_content(); ?>
		</div>

		<?php
		// Display post tags
		if ( has_tag() ) {
			the_tags( '<p class="tags">', ' ', '</p>' );
		}
		?>
	<?php else : ?>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="thumbnail">
		        <a href="<?php the_permalink() ?>" title="<?php get_the_title() ?>">
		         	<?php the_post_thumbnail( 'cepatlakoo-blog-thumb', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?>
		        </a>
			</div>
		<?php endif; ?>

		<div class="detail">
			<h3 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title() ?>"><?php the_title(); ?></a></h3>
			<?php cepatlakoo_entry_meta(); ?>
			<?php the_excerpt(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" title="<?php the_title() ?>" class="btn regular-btn primary-btn primary-bg"><?php esc_html_e( 'Read more', 'cepatlakoo' ); ?></a>
	<?php endif; ?>
</article>
