<?php
/**
 * The template for displaying header part.
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

<?php ob_start(); // Initiate the output buffer ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<!-- Start : Header -->
<?php 	if ( ! isset( $_GET['elementor_library'] ) && did_action('wp_head') == 1 ) : ?>
    

	<header id="masthead" class="with-bg" itemscope itemtype="http://schema.org/WPHeader">
		<!-- Start : Top Header -->
		<?php cepatlakoo_topbar(); ?>
		<!-- End : Top Header -->

		<!-- Start : Main Header -->
		<?php cepatlakoo_header_style_option(); ?>
		<!-- End : Main Header -->
	</header>
	<?php
    // load sidebar cart panel
    if ( class_exists( 'WooCommerce' ) ) {
    	cepatlakoo_sidebar_cart_content();
    }
    ?>
<?php endif; ?>
<!-- End : Header -->
