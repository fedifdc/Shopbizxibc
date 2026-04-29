<?php

/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $post;

$reward = get_post_meta($post->ID, 'mycred_reward', true);
if (isset($reward['point_level']) && is_numeric($reward['point_level'])) {
	$point_level = number_format($reward['point_level'], 0, ',', '.');
	$display_point = '<div class="yith-par-message" style="background-color:#E4F6F3; color: #000000">';
	$display_point .= '<img style="max-width: 16px; margin-right: 5px;" src="' . site_url() . '/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">';
	$display_point .= 'Dapatkan <strong><span class="product_point">' . $point_level . '</span> Point Bonus </strong>';
	$display_point .= '</div>';
	echo $display_point;
}

if (isset($reward['point_upgrade']) && is_numeric($reward['point_upgrade'])) {
	$point_upgrade = number_format($reward['point_upgrade'], 0, ',', '.');
	$display_upgrade = '<div class="yith-par-message" style="background-color:#F6E4E4; color: #000000; margin-bottom: 10px; margin-left: 10px">';
	$display_upgrade .= '<img style="max-width: 16px; margin-right: 5px;" src="' . site_url() . '/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">';
	$display_upgrade .= 'Dapatkan <strong><span class="product_upgrade">' . $point_upgrade . '</span> Point Level</strong>';
	$display_upgrade .= '</div>';
	echo $display_upgrade;
}


$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

if (!$short_description) {
	return;
}

?>

<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. 
	?>
</div>