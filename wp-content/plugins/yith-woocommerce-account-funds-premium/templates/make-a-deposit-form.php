<?php
/**
 * Template that show the small make a deposit form
 *
 * @package YITH\AccountFunds\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( apply_filters( 'yith_funds_user_is_available', true ) ) {
	$price_format = get_woocommerce_price_format();

	$currency     = '<span class="ywf_currency_symbol">' . get_woocommerce_currency_symbol() . '</span>';
	$input_number = sprintf( '<input type="number" name="amount" placeholder="%s" class="ywf_deposit " min="%s" max="%s" step="%s">', __( 'Enter amount', 'yith-woocommerce-account-funds' ), $min, $max, $step );
	$endpoint_url = wc_get_page_permalink( 'myaccount' );
	$lang         = isset( $_GET['lang'] ) ? wp_unslash( $_GET['lang'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$endpoint_url = remove_query_arg( 'lang', $endpoint_url );

	?>
	<div class="ywf_make_a_deposit_form">
		<form method="post" action="">
			<p><?php echo sprintf( $price_format, $currency, $input_number ); //phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<input type="submit" class="button" value="<?php esc_attr_e( 'Add funds now', 'yith-woocommerce-account-funds' ); ?>">
			<input type="hidden" name="make_a_deposit_form" value="<?php echo esc_attr( wp_create_nonce( 'make_a_deposit_form' ) ); ?>">
			<?php if ( $lang ) : ?>
				<input type="hidden" name="lang" value="<?php echo esc_attr( $lang ); ?>">
			<?php endif; ?>
		</form>
	</div>
	<?php
}
