<?php

add_action( 'add_meta_boxes', 'plugin_resi_add_custom_box' );
function plugin_resi_add_custom_box() {
	add_meta_box(
		'plugin_resi_meta_box',
		__( 'Shipping Tracking',PLUGIN_RESI ) ,
		'plugin_resi_meta_box_callback',
		[
			'shop_order',
			'woocommerce_page_wc-orders'
		],
		'side'
	);
}

add_action( 'save_post', 'plugin_resi_save_postdata', 1 );
function plugin_resi_save_postdata( $post_id ) {
	// verify if this is an auto save routine
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// verify this came from the our screen and with proper authorization
	// because save_post can be triggered at other times
	$_POST['plugin_resi_noncename'] = isset( $_POST['plugin_resi_noncename'] ) ? $_POST['plugin_resi_noncename'] : '';
	if ( ! wp_verify_nonce( $_POST['plugin_resi_noncename'], plugin_basename( __FILE__ ) ) ) {
		return;
	}
	// Check permissions
	if ( isset( $_POST['post_type'] ) ) {
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	}

	$args = array(
		'courier'	=> isset( $_POST['plugin_resi_ekspedisi'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_resi_ekspedisi'] ) ) : '',
		'service'	=> isset( $_POST['plugin_resi_layanan'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_resi_layanan'] ) ) : '',
		'tracking'	=> isset( $_POST['plugin_resi_nomor_resi'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_resi_nomor_resi'] ) ) : '',
	);

	plugin_resi_set_order_data( $post_id, $args );
}

/**
 * Prints the box content
 */
function plugin_resi_meta_box_callback( $post ) {
	// Use nonce for verification.
	wp_nonce_field( plugin_basename( __FILE__ ), 'plugin_resi_noncename' );
    $order_resi = plugin_resi_get_order_data( $post->ID );
	$order = wc_get_order( $post->ID );
	$shippings = $order->get_shipping_methods();
	if ( ! empty( $shippings ) ) {
		$values   = array_values( $shippings );
		$shipping = array_shift( $values );
		if ( empty( $order_resi['courier'] ) && $shipping->meta_exists( 'courier' ) ) {
            $order_resi['courier'] = $shipping->get_meta( 'courier', true );
        }
        if ( empty( $order_resi['service'] ) && $shipping->meta_exists( 'service' ) ) {
            $order_resi['service'] = $shipping->get_meta( 'service', true );
        }
	}
	?>

	<table style="width:100%" class="form-table table-plugin-resi">

		<tr>
			<th><?php _e( 'Courier',PLUGIN_RESI ); ?></th>
		</tr>
		<tr>
			<td>
				<?php
					$ekspedisis = plugin_resi_get_ekspedisis();

					$print_select = '';
					$print_select .= "<select name='plugin_resi_ekspedisi'>";
				foreach ( $ekspedisis as $key => $data ) {
					$selected = strtolower( $order_resi['courier'] ) === strtolower( $key ) ? 'selected' : '';

					$print_select .= "<option value='{$key}' $selected >{$data['nama']}</option>";
				}
					$print_select .= '</select>';

					echo $print_select;
				?>

				<p><?php _e( 'Add other courier ',PLUGIN_RESI ); ?><a href="<?php echo admin_url( 'admin.php?page=plugin-resi' ); ?>"><?php _e( 'here',PLUGIN_RESI ); ?></a></p>
			</td>
		</tr>

		<tr>
			<th><?php _e( 'Service (optional)',PLUGIN_RESI ); ?></th>
		</tr>
		<tr>
			<td><input type="text" style="width:100%" name="plugin_resi_layanan" value="<?php echo $order_resi['service']; ?>"></td>
		</tr>

		<tr>
			<th><?php _e( 'Tracking number',PLUGIN_RESI ); ?></th>
		</tr>
		<tr>
			<td><input type="text" style="width:100%" name="plugin_resi_nomor_resi" value="<?php echo $order_resi['tracking']; ?>"></td>
		</tr>
		<tr>
			<td>
				<button type="button" id="send-tracking" class="button-primary"><?php _e( 'Send tracking number', PLUGIN_RESI ); ?></button>
				<span class="resi-result"></span>
			</td>
		</tr>
	</table>
	<script>
		jQuery(function($){
			if ( '' === $("input[name=plugin_resi_nomor_resi]").val() ) {
				$('#send-tracking').attr('disabled', true);
			} else {
				$('#send-tracking').attr('disabled', false);
			}
			$("input[name=plugin_resi_nomor_resi]").on('change keyup', function () {
				var value = $(this).val();
				if ( '' === value ) {
					$('#send-tracking').attr('disabled', true);
				} else {
					$('#send-tracking').attr('disabled', false);
				}
			});

			$('#send-tracking').on('click', function() {
				var courier = $("select[name=plugin_resi_ekspedisi]").val();
				var service = $("input[name=plugin_resi_layanan]").val();
				var tracking = $("input[name=plugin_resi_nomor_resi]").val();
				var order_id = $('#post_ID').val();
				$(this).attr('disabled', true);
				$('.resi-result').html('sending...');
				$.ajax({
					url: ajaxurl,
					type: 'post',
					data: {
						action: 'resi_send_tracking_number',
						order_id: order_id,
						courier: courier,
						service: service,
						tracking: tracking
					},
					context: this,
					success: function(res) {
						$(this).attr('disabled', false);
						$('.resi-result').html('email sent');
					},
					error: function(err) {
						console.log(err);
						$(this).attr('disabled', false);
						$('.resi-result').html('error');
					}
				});
			});
		});
	</script>
	<?php
}
