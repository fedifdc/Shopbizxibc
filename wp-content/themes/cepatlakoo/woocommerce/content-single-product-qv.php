<?php
/**
 * The template for displaying single product quick view
 *
 * @author  Themewarrior
 * @package WordPress/Cepatlakoo
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="shop-detail-custom woocommerce">
	<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	}
	?>

	<div id="product-<?php the_ID(); ?>" <?php post_class("cepatlakoo-quick-view"); ?>>
	<?php
	/**
	 * woocommerce_before_single_product_summary hook.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'cepatlakoo_before_single_product_summary' );
	?>

    <div class="image-quick-view">
	    <?php
	        /**
	         * cepatlakoo_before_quick_view hook.
	         *
	         * @hooked woocommerce_show_product_sale_flash - 5
	         * @hooked woocommerce_show_product_images - 10
	         * @hooked cepatlakoo_view_full_detail - 15
	         */
	        do_action( 'cepatlakoo_before_quick_view' );
	    ?>
    </div>

    <div class="summary entry-summary">
    	<?php
        /**
         * cepatlakoo_summary_quick_view hook.
         *
         * @hooked woocommerce_template_single_title - 5
         * @hooked woocommerce_template_single_rating - 10
         * @hooked woocommerce_template_single_price - 10
         * @hooked woocommerce_template_single_excerpt - 20
         * @hooked cepatlakoo_quick_view_add_to_cart - 30
         * @hooked woocommerce_template_single_meta - 45
         */
        do_action( 'cepatlakoo_summary_quick_view' );
    	?>
    </div>

	<?php
	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'cepatlakoo_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
