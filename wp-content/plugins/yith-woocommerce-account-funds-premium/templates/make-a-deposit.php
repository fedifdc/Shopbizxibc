<?php
/**
 * Template that show the make a deposit form
 *
 * @package YITH\AccountFunds\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="make_a_deposit_checkout">
	<?php

	/**
	 * See these hooks
	 *
	 * @Hooks
	 * display_available_user_funds - 10
	 */
	do_action( 'before_make_a_deposit_form' );


	$hide_form = ( isset( $amount ) && '' !== $amount );
	$min       = ywf_get_min_fund_rechargeable();
	$max       = ywf_get_max_fund_rechargeable();
	$step      = get_option( 'yith_funds_step', 1 );
	$currency  = get_woocommerce_currency();

	$product_id = get_option( '_ywf_deposit_id' );
	$product    = wc_get_product( $product_id );
	$amount     = apply_filters( 'yith_amount_to_deposit', $amount, $currency );
	$hide_form  = $hide_form && ( $amount >= $min && ( '' === $max || $amount <= $max ) );

	$message = sprintf( '<p>%s <strong>%s</strong><a href="" class="ywf_show_form">%s</a></p>', __( 'You are loading', 'yith-woocommerce-account-funds' ), wc_price( $amount ), __( 'Enter a different amount', 'yith-woocommerce-account-funds' ) );
	?>
	<div class="ywf_message_amount <?php echo $hide_form ? 'ywf_show' : ''; ?>">
		<?php echo wp_kses_post( $message ); ?>
	</div>
	<div class="ywf_make_a_deposit_container">
		<div class="ywf_product_image">
			<?php echo $product->get_image( 'woocommerce_single' ); //phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="ywf_summary">
			<h3><?php echo esc_html( $product->get_title() ); ?></h3>

			<?php
			$price_format = get_woocommerce_price_format();
			$currency     = get_woocommerce_currency_symbol();
			$input_number = sprintf( '<input type="number" name="amount_deposit" placeholder="%s" class="ywf_deposit" min="%s"  max="%s" value="%s" step="%s">', __( 'Enter amount', 'yith-woocommerce-account-funds' ), $min, $max, $amount, $step );
			?>
			<form id="make-a-deposit" class="checkout woocommerce-checkout" name="make_a_deposit" method="post">
				<p class="ywf_amount_input_container <?php echo $hide_form ? 'ywf_hide' : ''; ?>">
					<label for="amount_deposit"><?php esc_html_e( 'Amount', 'yith-woocommerce-account-funds' ); ?></label>
					<?php
					$price_format = get_woocommerce_price_format();
					$currency     = '<span class="ywf_currency_symbol">' . get_woocommerce_currency_symbol() . '</span>';
					echo '<span class="ywf_deposit_content">' . sprintf( $price_format, $currency, $input_number ) . '</span>'; //phpcs:ignore WordPress.Security.EscapeOutput
					?>

				</p>
				<input type="hidden" name="deposit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'add_deposit' ) ); ?>">
				<input type="hidden" name="currency" value="<?php echo esc_attr( get_woocommerce_currency() ); ?>">
				<input type="submit" class="add_a_deposit_button button" value="<?php echo esc_html( $product->single_add_to_cart_text() ); ?>">
			</form>
		</div>
	</div>
	<?php
	do_action( 'after_make_a_deposit_form' );
	?>

</div>
