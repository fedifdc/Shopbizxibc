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

<!-- START: Middle Menu Navigation -->
<div id="main-header" class="header-logo-middle">
	<div class="container clearfix">
		<div class="header-wrap">
		<?php cepatlakoo_logo(); ?>

        <?php
	    // load cart profile menu
	    if ( class_exists( 'WooCommerce' ) ) {
	        echo cepatlakoo_display_cart_profile_menu();
	    }
	    ?>

		<!-- START: Left Menu Navigation -->
		<?php if ( has_nav_menu( 'cepatlakoo-left-navigation' ) ) : ?>
			<nav id="left-menu" class="main-menu site-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
				<meta itemprop="name" content="Left Navigation Menu">
				<?php wp_nav_menu( array( 'theme_location' => 'cepatlakoo-left-navigation', 'container' => null, 'menu_class' => 'main-menu', 'depth' => 4 ) ); ?>
			</nav>
		<?php endif; ?>
		<!-- END: Left Menu Navigation -->

		<!-- START: Right Menu Navigation -->
		<?php if ( has_nav_menu( 'cepatlakoo-right-navigation' ) ) : ?>
			<nav id="right-menu" class="main-menu site-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
				<meta itemprop="name" content="Right Navigation Menu">
				<?php wp_nav_menu( array( 'theme_location' => 'cepatlakoo-right-navigation', 'container' => null, 'menu_class' => 'main-menu', 'depth' => 3 ) ); ?>
			</nav>
		<?php endif; ?>
		<!-- END: Right Menu Navigation -->
		</div>
	</div>
</div>
<!-- START: Middle Menu Navigation -->
