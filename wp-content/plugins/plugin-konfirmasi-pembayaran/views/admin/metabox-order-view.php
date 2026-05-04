<?php if ( isset( $confirm['file'] ) && ! empty( $confirm['file'] ) ) : ?>
	<a href="<?php echo wp_get_attachment_image_url( $confirm['file'], 'full' ); ?>" target="_blank">
		<img src="<?php echo wp_get_attachment_image_url( $confirm['file'], 'medium' ); ?>" alt="Proof of Payment">
	</a>
<?php endif; ?>
<table>
	<?php foreach ( $fields as $key => $field ) : ?>
		<?php if ( ! $field['required'] && 'file' !== $key && isset( $confirm[ $key ] ) && ! empty( $confirm[ $key ] ) ) : ?>
			<?php if ( 'note' === $key ) : ?>
				<tr>
					<td class="index note" colspan="2">
						<?php echo esc_html( $field['name'] ) ?>
						<p><?php echo esc_html( $confirm[ $key ] ) ?></p>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td class="index"><?php echo esc_html( $field['name'] ) ?></td>
					<td class="value"><?php echo 'amount' === $key ? wc_price( $confirm[ $key ] ) : esc_html( $confirm[ $key ] ) ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php do_action( 'tpc_metabox_order_view', $confirm, $order ); ?>

</table>
<?php if ( ! empty( $confirm ) ) : ?>
	<p class="info filled">
		<?php
			if ( isset( $confirm['submit_date'] ) && ! empty( $confirm['submit_date'] ) ) {
				printf( __( 'The customer has sent the proof of payment on %s.', 'tpc' ), '<strong>' . $confirm['submit_date'] . '</strong>' );
			}
		?>
		<?php
			if ( $confirmed = $order->get_meta( 'tpc_confirmed', true ) ) {
				printf( __( 'It was confirmed by admin on %s.', 'tpc' ), '<strong>' . $confirmed . '</strong>' );
			} elseif ( $rejected = $order->get_meta( 'tpc_rejected', true ) ) {
				printf( __( 'It was rejected by admin on %s', 'tpc' ), '<strong>' . $rejected . '</strong>' );
				if ( $reason = $order->get_meta( 'tpc_reject_reason', true ) ) {
					echo ' ';
					printf( __( 'with reason %s', 'tpc' ), '<strong>' . $reason . '</strong>' );
				}
				echo '.';
			}
		?>
		<a class="toggle-edit"><?php esc_html_e( 'Click here to edit', 'tpc' ); ?></a>.
	</p>
	<?php if ( $order->has_status('waiting-confirm') ) : ?>
		<table class="actions">
			<tr>
				<td><button class="button button-primary tpc-confirm" data-id="<?php echo esc_attr( $order->get_id() ) ?>" type="button"><?php esc_html_e( 'Confirm', 'tpc' ) ?></button></td>
				<td><button class="button tpc-reject" data-id="<?php echo esc_attr( $order->get_id() ) ?>" type="button"><?php esc_html_e( 'Reject', 'tpc' ) ?></button></td>
			</tr>
		</table>
	<?php endif; ?>
<?php else: ?>
	<p class="info"><?php esc_html_e( 'The customer has not sent the proof of payment yet.', 'tpc' ) ?> <a class="toggle-edit"><?php esc_html_e( 'Click here to edit', 'tpc' ); ?></a>.</p>
<?php endif; ?>