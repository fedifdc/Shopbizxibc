<?php
/**
 * The Sidebar WooCommerce detail containing the main widget area
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

<div id="sidebar" class="right-sidebar-shop-page default-sidebar">
<?php
	// Load widgets
	if ( is_active_sidebar( 'cepatlakoo-woocommerce-detail-sidebar' ) ) {
		dynamic_sidebar( 'cepatlakoo-woocommerce-detail-sidebar' );
	}
?>
</div>
