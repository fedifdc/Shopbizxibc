<?php
/**
 * The template for displaying author page
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
<?php $curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) ); ?>
<div id="main">
	<div id="maincontent">
		<div class="container clearfix">
			<?php get_template_part( 'includes/breadcrumb' ); ?>

			<?php get_sidebar(); ?>

			<div id="contentarea">
				<h1 class="page-title"><?php cepatlakoo_page_title(); ?></h1>
				<div class="archive-title archive-user">
					<?php $user_info = get_userdata( $curauth->ID ); ?>
					<div class="thumbnail"><?php echo get_avatar( $user_info->ID, '100' ); ?></div>
					<div class="detail">
						<h4><?php echo isset( $user_info->display_name ) ? $user_info->display_name : ''; ?></h4>
						<p><?php echo wpautop( $user_info->description ); ?></p>
					</div>
				</div>

				<div class="archive-tab">
					<a class="active" href="#"><?php esc_html_e('Posts', 'cepatlakoo'); ?></a>
				</div>

				<div class="archive-content">
					<div class="postlist">
					<?php
						// Load posts
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
							'paged'         		=> $paged,
							'author'		 		=> $user_info->ID
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
			</div>
		</div>
	</div>
</div>
<!-- End : Main Content -->

<?php get_footer(); ?>