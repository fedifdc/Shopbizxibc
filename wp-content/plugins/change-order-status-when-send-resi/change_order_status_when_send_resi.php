<?php
/*
Plugin Name: Change Order Status Send Resi
Description: A custom plugin to split my account shortcode.
Version: 1.0
Author: Novan Noviansyah P
*/

add_action('wp_ajax_resi_send_tracking_number','change_order_status_when_send_resi');

function change_order_status_when_send_resi(){
   	$post_id = isset( $_POST['order_id'] ) ? intval($_POST['order_id']) : 0;
	
	$order = wc_get_order( $post_id );
	if($order)
    	$order->update_status('shipped');

}

function plugin_resi_render_tracking_form($order_id) {
    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'plugin_resi_noncename');

    $order_resi = plugin_resi_get_order_data($order_id);
    $order = wc_get_order($order_id);
    $shippings = $order->get_shipping_methods();
    if (!empty($shippings)) {
        $values = array_values($shippings);
        $shipping = array_shift($values);
        if (empty($order_resi['courier']) && $shipping->meta_exists('courier')) {
            $order_resi['courier'] = $shipping->get_meta('courier', true);
        }
        if (empty($order_resi['service']) && $shipping->meta_exists('service')) {
            $order_resi['service'] = $shipping->get_meta('service', true);
        }
    }

    ob_start(); // Start output buffering
    ?>

	   <div class="dokan-form-group">
		<label><?php _e('Courier', PLUGIN_RESI); ?></label>
		<div class="dokan-form-group">
			<?php
			$ekspedisis = plugin_resi_get_ekspedisis();
			echo "<select name='plugin_resi_ekspedisi' class='dokan-form-control'>";
			foreach ($ekspedisis as $key => $data) {
				$selected = strtolower($order_resi['courier']) === strtolower($key) ? 'selected' : '';
				echo "<option value='{$key}' $selected >{$data['nama']}</option>";
			}
			echo '</select>';
			?>
			<p><?php _e('Add other courier ', PLUGIN_RESI); ?><a href="<?php echo admin_url('admin.php?page=plugin-resi'); ?>"><?php _e('here', PLUGIN_RESI); ?></a></p>
		</div>
	</div>

	<div class="dokan-form-group">
		<label><?php _e('Service (optional)', PLUGIN_RESI); ?></label>
		<div class="dokan-form-group">
			<input type="text" class="dokan-form-control" name="plugin_resi_layanan" value="<?php echo esc_attr($order_resi['service']); ?>" placeholder="<?php _e('Enter service name', PLUGIN_RESI); ?>">
		</div>
	</div>

	<div class="dokan-form-group">
		<label><?php _e('Tracking number', PLUGIN_RESI); ?></label>
		<div class="dokan-form-group">
			<input type="text" class="dokan-form-control" name="plugin_resi_nomor_resi" value="<?php echo esc_attr($order_resi['tracking']); ?>" placeholder="<?php _e('Enter tracking number', PLUGIN_RESI); ?>">
		</div>
	</div>

	<div class="dokan-form-group">
		<button type="button" id="send-tracking" class="dokan-button dokan-button-primary"><?php _e('Send tracking number', PLUGIN_RESI); ?></button>
		<span class="resi-result"></span>
	</div>

    <script>
        jQuery(function($){
            if ('' === $("input[name=plugin_resi_nomor_resi]").val()) {
                $('#send-tracking').attr('disabled', true);
            } else {
                $('#send-tracking').attr('disabled', false);
            }
            $("input[name=plugin_resi_nomor_resi]").on('change keyup', function () {
                var value = $(this).val();
                $('#send-tracking').attr('disabled', value === '');
            });

            $('#send-tracking').on('click', function() {
                var courier = $("select[name=plugin_resi_ekspedisi]").val();
                var service = $("input[name=plugin_resi_layanan]").val();
                var tracking = $("input[name=plugin_resi_nomor_resi]").val();
                var order_id = <?php echo (int) $order_id; ?>;
                $(this).attr('disabled', true);
                $('.resi-result').html('sending...');
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
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
                        $('.resi-result').html('Done!, Refresh page!');
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

    return ob_get_clean(); // Get buffered content
}

add_action('dokan_order_detail_after_order_general_details', 'add_send_resi_to_dokan',10,1);

function add_send_resi_to_dokan($order){
	echo plugin_resi_render_tracking_form($order->get_id());
}

