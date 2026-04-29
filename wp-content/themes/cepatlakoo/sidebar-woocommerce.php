<?php
/**
 * The Sidebar WooCommerce containing the main widget area
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

<?php
global $cl_options;
if ( $cl_options['cepatlakoo_shop_layout'] == 'sidebar-none' )
	return;
?>

<div id="sidebar" class="right-sidebar-shop-page">
<?php
	// Load widgets
	if ( is_active_sidebar( 'cepatlakoo-woocommerce-sidebar' ) ) {
		dynamic_sidebar( 'cepatlakoo-woocommerce-sidebar' );
	}
?>
</div>
