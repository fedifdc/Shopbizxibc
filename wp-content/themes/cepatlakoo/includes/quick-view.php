<?php
/**
 * Template for displaying Quick View Template/
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ) {
	$prod_id =  sanitize_text_field ( absint ( $_POST[ 'data_id' ] ) );
	$post = get_post( $prod_id );
	$product = wc_get_product( $prod_id );
	ob_start();

	if (cepatlakoo_check_version_threshold()){
		wc_get_template( 'content-single-product-qv.php' );
	} else {
		woocommerce_get_template( 'content-single-product-qv.php' );
	}

	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
} else {
	wp_redirect( home_url() ); exit;
}
?>
<!-- END : Quick View Content -->