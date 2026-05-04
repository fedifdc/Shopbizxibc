<?php
/*
Plugin Name: Disable YITH Points for Suborders
Description: A custom plugin to disable YITH points for suborders.
Version: 1.0
Author: Ruli Setiwan
*/
add_action('woocommerce_order_status_completed', 'disable_points_for_suborders', 10, 1);

function disable_points_for_suborders($order_id) {
    global $wpdb;
	$table_name = $wpdb->prefix.'yith_ywpar_points_log';
 	$table_user_db = $wpdb->prefix. 'usermeta';
	$getOrderObject = wc_get_order( $order_id );
	$data = $getOrderObject->get_data();
	if($data['parent_id'] <> 0){
		$wpdb->delete($table_name , array( 'order_id' => $order_id ));
	}
}