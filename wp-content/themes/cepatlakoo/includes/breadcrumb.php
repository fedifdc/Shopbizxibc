<?php
/**
 * The Template for displaying breadcrumb
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
?>

<!-- Start : Breadcrumb -->
<?php 
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div id="breadcrumbs">', '</div>' );
	}
?>
<!-- End : Breadcrumb -->
