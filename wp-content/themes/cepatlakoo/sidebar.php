<?php
/**
 * The Sidebar Default containing the main widget area
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

<div id="sidebar" class="default-sidebar" itemscope itemtype="http://schema.org/WPSidebar">
<?php
	if ( is_active_sidebar('cepatlakoo-sidebar-widget') ) {
		dynamic_sidebar( 'cepatlakoo-sidebar-widget' );
	} else {
// 		echo '<div class="container"><p class="no-widget">';
// 		printf( wp_kses( __('There is no widget assigned. You can start assigning widgets to "Sidebar Widgets" widget area from the <a href="%s" target="_blank">Widgets</a>.', 'cepatlakoo'), array(  'a' => array( 'href' => array() ) ) ), admin_url('/widgets.php') );
// 		echo '</p></div>';
	}
?>
</div>
