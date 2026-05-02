<?php
/**
 * Template for displaying post navigation.
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
?>

<!-- Start : Post Navigation -->
<?php
if ( function_exists( 'cepatlakoo_post_navigation' ) ) {
	echo cepatlakoo_post_navigation();
} 
?>
<!-- End : Post Navigation -->