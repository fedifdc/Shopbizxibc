<?php
/**
 * Confirmation Form Template
 *
 * @package tpc
 * @since 1.0.0
 * @version 2.0.0
 */

?>

<?php if ( ! empty( $css ) ) : ?>
	<style>
		<?php echo $css ?>
	</style>
<?php endif; ?>

<form id="tpc-confirmation-form" class="payment-confirmation-form" enctype="multipart/form-data" method="post">

	<?php do_action( 'tpc_confirmation_form', $this ); ?>

	<?php $this->fields->render_fields( $html ); ?>

	<div class="message-wrap">
	</div>

	<div class="action-wrap">
		<?php wp_nonce_field( 'send_confirmation', 'tpc_action' ); ?>
		<button id="submit-confirmation" type="submit" class="button button-primary"><?php esc_html_e( 'Submit', 'tpc' ) ?></button>
	</div>
	
</form>