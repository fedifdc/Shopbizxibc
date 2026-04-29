<?php

add_action('wp_ajax_send_test_email', 'ajax_send_test_email' );
function ajax_send_test_email() 
{    
	$order = wc_get_order(intval($_POST['order']));
	$email = sanitize_email($_POST['email']);
	
	// do send email
	plugin_resi_do_send_email($order, false, $email);

	die();
}

add_action('wp_ajax_resi_send_tracking_number', 'resi_send_tracking_number' );
function resi_send_tracking_number() {    
	$post_id = isset( $_POST['order_id'] ) ? intval($_POST['order_id']) : 0;
	$order = wc_get_order( $post_id );
	$courier = isset( $_POST['courier'] ) ? sanitize_text_field( wp_unslash( $_POST['courier'] ) ) : '';
	$service = isset( $_POST['service'] ) ? sanitize_text_field( wp_unslash( $_POST['service'] ) ) : '';
	$tracking = isset( $_POST['tracking'] ) ? sanitize_text_field( wp_unslash( $_POST['tracking'] ) ) : '';

	plugin_resi_set_order_data( $post_id, array(
		'courier'   => $courier,
		'service'   => $service,
		'tracking'	=> $tracking
	) );
	
	// do send email
	plugin_resi_do_send_email( $order );

	die();
}