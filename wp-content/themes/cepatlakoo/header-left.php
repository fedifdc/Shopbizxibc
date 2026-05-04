<?php
/**
 * The template for displaying header middle template
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

<!-- START: Left Menu Navigation -->
<div id="main-header" class="header-logo-left">
    <div class="container clearfix">
        <div class="header-wrap">
        <?php cepatlakoo_logo(); ?>

        <?php if ( has_nav_menu( 'cepatlakoo-left-navigation' ) ) : ?>
            <nav id="main-menu" class="main-menu site-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
				<meta itemprop="name" content="Navigation Menu">
                <?php wp_nav_menu( array( 'theme_location' => 'cepatlakoo-left-navigation', 'container' => null, 'menu_class' => 'main-menu', 'depth' => 4 ) ); ?>
            </nav>
        <?php endif; ?>

        <?php
        // load cart profile menu
        if ( class_exists( 'WooCommerce' ) ) {
            echo cepatlakoo_display_cart_profile_menu();
        }
        ?>
        </div>
    </div>
</div>
<!-- END: Left Menu Navigation -->
