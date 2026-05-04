<?php
/**
 * Displaying Elementor Library
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

<div id="main" >
	<div id="maincontent">
		<div class="clearfix">
			<div id="contentarea" class="fullwidth">
			<?php
				if ( have_posts() ) {
					 while ( have_posts() ) : the_post();
							the_content();
					 endwhile;
					 wp_reset_postdata();
			  	} else {
			  		get_template_part( 'content-none' );
			  	}
			?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
