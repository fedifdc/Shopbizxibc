<?php
/**
 * This template show in admin the funds details for a customer
 *
 * @package YITH\AccountFunds\Admin\MetaBoxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $theorder;

$order       = $theorder; // phpcs:ignore
$customer_id = $order->get_customer_id();

if ( $customer_id ) :
	$customer = new YITH_YWF_Customer( $customer_id );

	$total_funds             = apply_filters( 'yith_show_funds_used_into_order_currency', $customer->get_funds(), $order->get_id() );
	$total_funds_for_display = wc_price( $total_funds, array( 'currency' => $order->get_currency() ) );
	$customer_full_name      = $order->get_formatted_billing_full_name();
	?>
	<div id="yith_account_fund_meta_box">
		<p>
			<?php
			echo esc_html( $customer_full_name );
			?>
			<small>
				<?php
				/* translators: %s is the amount of funds available */
				echo sprintf( __( '(available funds %s)', 'yith-woocommerce-account-funds' ), $total_funds_for_display ); //phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</small>
		</p>
		<?php wp_nonce_field( 'yith-add-funds-nonce', 'yith_add_funds_nonce' ); ?>
		<label for="yith_add_funds" class="screen-reader-text"><?php esc_html_e( 'Add fund', 'yith-woocommerce-account-funds' ); ?></label>
		<input type="number" id="yith_add_funds" min="0" placeholder="<?php esc_html_e( 'Edit funds', 'yith-woocommerce-account-funds' ); ?>">
		<input type="hidden" id="yith_customer_id" value="<?php echo esc_attr( $customer_id ); ?>">
		<button type="button" class="ywf_add_funds button"><?php esc_html_e( 'Add', 'yith-woocommerce-account-funds' ); ?></button>
	</div>
	<?php
endif;
