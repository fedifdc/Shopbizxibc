<?php

/**
 * The template for displaying footer part.
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
?>

<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
global $cl_options;
?>

<!-- Start : Footer -->
<?php if (!isset($_GET['elementor_library'])) : ?>
	<footer id="colofon" itemscope itemtype="http://schema.org/WPFooter">
		<?php if (has_nav_menu('cepatlakoo-footer-navigation')) : ?>
			<div id="footer-menu-area">
				<div class="container clearfix">
					<nav id="footer-menu" class="footer-menu site-navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<meta itemprop="name" content="Footer Navigation Menu">
						<?php wp_nav_menu(array('theme_location' => 'cepatlakoo-footer-navigation', 'container' => null, 'menu_class' => 'footer-menu', 'depth' => 1)); ?>
					</nav>
				</div>
			</div>
		<?php endif; ?>

		<?php if (is_active_sidebar('cepatlakoo-footer-widget-1') || is_active_sidebar('cepatlakoo-footer-widget-2') || is_active_sidebar('cepatlakoo-footer-widget-3') || is_active_sidebar('cepatlakoo-footer-widget-4')) : ?>
			<div id="footer-widgets-area">
				<div class="container clearfix">
					<div class="footer-widgets row column-4">
						<?php
						if (is_active_sidebar('cepatlakoo-footer-widget-1') || is_active_sidebar('cepatlakoo-footer-widget-2') || is_active_sidebar('cepatlakoo-footer-widget-3') || is_active_sidebar('cepatlakoo-footer-widget-4')) {
							// Check if the user is logged in
                    if (is_user_logged_in()) {
                        // Set footer limit to 2 if the user is logged in
                        $footer_limit = 2;
                    } else {
                        // Use the theme's footer limit, default to 3
                        $footer_limit = isset($cl_options['cepatlakoo_footer_widget_column']) ? $cl_options['cepatlakoo_footer_widget_column'] : 3;
                    }
                    
                    // Calculate column class
                    $col_class = 'col-md-' . 12 / $footer_limit;
                    
                    for ($i = 1; $i <= $footer_limit; $i++) {
                        // Skip the third widget if the user is logged in
                        if ($i === 3 && is_user_logged_in()) {
                            continue;
                        }
                        echo '<div class="' . $col_class . '">';
                        dynamic_sidebar('cepatlakoo-footer-widget-' . $i);
                        echo '</div>';
                    }
// 							$footer_limit = isset($cl_options['cepatlakoo_footer_widget_column']) ? $cl_options['cepatlakoo_footer_widget_column'] : 3;
// 							$col_class = 'col-md-'. 12 / $footer_limit;
							
// 							for ( $i = 1; $i <= $footer_limit; $i++ ) {
// 								echo '<div class="'. $col_class .'">';
// 									dynamic_sidebar('cepatlakoo-footer-widget-'. $i);
// 								echo '</div>';
// 							}

						} else {
							echo '<div class="container"><p class="no-widget">';
							printf(wp_kses(__('There is no widget assigned. You can start assigning widgets to "Footer Widgets" widget area from the <a href="%s" target="_blank">Widgets</a>.', 'cepatlakoo'), array('a' => array('href' => array()))), admin_url('/widgets.php'));
							echo '</p></div>';
						}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div id="footer-info">
			<div class="container clearfix">
				<div class="site-infos">
					<?php
					// Copyright text
					global $cl_options;
					$cepatlakoo_copyright_text = !empty($cl_options['cepatlakoo_copyright_text']) ? $cl_options['cepatlakoo_copyright_text'] : '';
					$cepatlakoo_copyright_text = str_replace('%current_year%', date('Y'), $cepatlakoo_copyright_text);
					$cepatlakoo_copyright_text = str_replace('%site_name%', get_bloginfo('name'), $cepatlakoo_copyright_text);

					printf(wp_kses(html_entity_decode($cepatlakoo_copyright_text), array('a' => array('href' => array(), 'target' => array()), 'p' => array(), 'br' => array(), 'strong' => array(), 'i' => array())));
					?>
				</div>
			</div>
		</div>
		<?php if (!is_page_template('blank')) : ?>
			<div id="backtotop">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M12 3l12 18h-24z" />
				</svg>
			</div>
		<?php endif; ?>
	</footer>

<?php endif; ?>
<!-- End : Footer -->

<?php
    if ( class_exists( 'WooCommerce' ) ) {
		// load popup cart panel
    	cepatlakoo_popup_cart_content();
    }
?>
<!-- /END POPUP CART -->

<?php wp_footer(); ?>
</body>

</html>