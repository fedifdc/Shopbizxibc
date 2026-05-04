<?php
/**
 * The template for displaying product page from woocommerce
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

get_header();
?>

<!-- Start : Main Content -->
<?php
global $cl_options;
$cepatlakoo_facebook_pixel_id = !empty( $cl_options['cepatlakoo_facebook_pixel_id'] ) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
$cl_shop_sidebar_class = !empty( $cl_options['cepatlakoo_shop_layout'] ) && $cl_options['cepatlakoo_shop_layout'] == 'sidebar-none' ? 'class=fullwidth' : '';
?>

<?php if ( $cepatlakoo_facebook_pixel_id ) : ?>
	<div id="main" fb-campaign-url="<?php echo esc_url( cepatlakoo_getslug_url() ); ?>" fb-content-name="<?php echo get_the_title() ?>" fb-product-id="<?php echo get_the_ID() ?>" <?php echo cepatlakoo_fb_pixel_data(); ?>>
<?php endif; ?>

<?php
if ( ! is_shop() && ! is_single() ) {
	woocommerce_page_title();
}
?>
		<div id="maincontent">
			<div class="container clearfix">
				<!-- Start : Breadcrumb -->
				<?php
				if ( function_exists( 'woocommerce_breadcrumb' ) && $cl_options['cepatlakoo_wc_show_breadcrumb'] == 1) {
					$args = array(
						'delimiter' => ' / ',
						'wrap_before' => '<div id="breadcrumbs">',
						'wrap_after' => '</div>'
					);
				 	woocommerce_breadcrumb( $args );
				}
				?>
				<!-- End : Breadcrumb -->
				<?php
				if ( is_single() ) :
					cepatlakoo_single_style( get_the_ID() );
				else :
					get_sidebar( 'woocommerce' );
				?>
					<div id="contentarea" <?php echo $cl_shop_sidebar_class; ?> itemprop="WebPageElement" itemscope itemtype="http://schema.org/mainContentOfPage">
						<div id="products-area">

							<div class="product-content-area">
								<div class="product-list">
								<?php
									if ( have_posts() ) {
										woocommerce_content();
									} else {
										echo '<p>';
										esc_html_e( 'The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo' );
										echo '</p>';
									}
								?>
								</div>
							</div>

						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<!-- End : Main Content -->
<?php get_footer(); ?>
