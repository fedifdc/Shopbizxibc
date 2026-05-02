<?php
// Update points for both customer and sponsor when an order is completed
function shopbiz_order_complete($order_id) {
    error_log("ADD COMMISSION TO MLM");
    global $wpdb;
    $order = wc_get_order($order_id);
    $customer_id = $order->get_customer_id();
    $userdata = get_userdata($customer_id);
	$shopbiz_user_id = get_user_meta($customer_id, 'shopbiz_user_id', true);
    // Table name for MyCred log
    $table_name = $wpdb->prefix . 'myCRED_log';
	if(!$shopbiz_user_id ){
	    error_log("USER TIDAK DITEMUKAN");
// 		return null;
	}else{
	    // Calculate points for the customer
        $customer_points_query = $wpdb->prepare(
            "SELECT SUM(creds) as total FROM $table_name WHERE ctype = 'point_level' AND user_id = %d AND ref_id = %d",
            $customer_id,
            $order_id
        );
        $customer_cred_data = $wpdb->get_row($customer_points_query);
    
        if ($customer_cred_data && $customer_cred_data->total > 0) {
            // Update customer points via API
            $api_url = get_option('shopbiz_api_url') . '/user/update-pv';
            $customer_api_data = array(
                'pv' => intval($customer_cred_data->total),
                'bv' => intval($customer_cred_data->total),
                'action' => 'add',
                'username' => $userdata->user_login,
    //             'data' => json_encode($customer_points_data),
            );
    
    //         $customer_response = wp_remote_post($api_url, array(
    //             'body' => $customer_api_data,
    //             'headers' => array(
    //                 'api-key' => get_option('shopbiz_api_token'),
    //             ),
    //         ));
    	$api_data_json = json_encode($customer_api_data);
    
    	$response = wp_remote_post($api_url, array(
            'headers' => array(
                'Content-Type' => 'application/json', // Specify JSON content type
                'secret_key' => get_option('shopbiz_api_token'), // Custom header
                'prefix' => '1119', // Additional custom header
            ),
            'body' => $api_data_json, // JSON data
            'method' => 'POST', // HTTP POST method
        )
        );
    
            // Log customer API response
            if (is_wp_error($response)) {
                error_log('Customer API call failed: ' . $response->get_error_message());
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                $response_body = wp_remote_retrieve_body($response);
                if (200 !== $response_code) {
                    error_log('Customer API call returned an error: ' . $response_body);
                }
            }
        } else {
            error_log('No commission points found for customer in order ' . $order_id);
        }
	}
    

//     // Get sponsor's commission points for the same order
//     $sponsor_points_query = $wpdb->prepare(
//         "SELECT user_id, creds FROM $table_name WHERE ctype = 'point_level' AND ref = 'commission' AND ref_id = %d ORDER BY id DESC LIMIT 1",
//         $order_id
//     );
//     $sponsor_cred_data = $wpdb->get_row($sponsor_points_query);

//     if ($sponsor_cred_data && $sponsor_cred_data->creds > 0) {
//         $sponsor_id = $sponsor_cred_data->user_id;
// 		$shopbiz_sponsor_id = get_user_meta($sponsor_id, 'shopbiz_user_id', true);
// 		if(!$shopbiz_sponsor_id){
// 			return null;
// 		}
//         if ($sponsor_id) {
//             $sponsor_userdata = get_userdata($sponsor_id);
//             $sponsor_api_data = array(
//                 'pv' => intval($sponsor_cred_data->creds),
//                 'action' => 'add',
//                 'username' => $sponsor_userdata->user_login,
// //                 'data' => json_encode($sponsor_points_data),
//             );

//             $sponsor_response = wp_remote_post($api_url, array(
//                 'body' => $sponsor_api_data,
//                 'headers' => array(
//                     'api-key' => get_option('shopbiz_api_token'),
//                 ),
//             ));

//             // Log sponsor API response
//             if (is_wp_error($sponsor_response)) {
//                 error_log('Sponsor API call failed: ' . $sponsor_response->get_error_message());
//             } else {
//                 $response_code = wp_remote_retrieve_response_code($sponsor_response);
//                 $response_body = wp_remote_retrieve_body($sponsor_response);
//                 if (200 !== $response_code) {
//                     error_log('Sponsor API call returned an error: ' . $response_body);
//                 }
//             }
//         } else {
//             error_log("No sponsor found for order ID: $order_id");
//         }
//     } else {
//         error_log('No commission points found for sponsor in order ' . $order_id);
//     }
    error_log("STARTING TO GIVE COMMISSION TO SPONSOR");
    $sponsor_points_query = $wpdb->prepare(
        "SELECT user_id, creds FROM $table_name WHERE ctype = 'point_level' AND ref = 'commission' AND ref_id = %d ORDER BY id DESC LIMIT 1",
        $order_id
    );
    
    // Log the query
    error_log("Sponsor points query: $sponsor_points_query");
    
    $sponsor_cred_data = $wpdb->get_row($sponsor_points_query);
    
    // Log the result of the query
    error_log("Sponsor points data: " . print_r($sponsor_cred_data, true));
    
    if ($sponsor_cred_data && $sponsor_cred_data->creds > 0) {
       
        $sponsor_id = $sponsor_cred_data->user_id;
    
        // Log the sponsor ID
        error_log("Sponsor ID: $sponsor_id");
    
        $shopbiz_sponsor_id = get_user_meta($sponsor_id, 'shopbiz_user_id', true);
    
        // Log the shopbiz sponsor ID
        error_log("ShopBiz Sponsor ID: " . ( $shopbiz_sponsor_id ? $shopbiz_sponsor_id : "Not found" ));
    
        if (!$shopbiz_sponsor_id) {
            error_log("ShopBiz Sponsor ID not found for Sponsor ID: $sponsor_id");
            return null;
        }
    
        if ($sponsor_id) {
            $sponsor_userdata = get_userdata($sponsor_id);
    
            // Log sponsor user data
            error_log("Sponsor User Data: " . print_r($sponsor_userdata, true));
        
            $sponsor_api_data = array(
                'pv' => intval($sponsor_cred_data->creds),
                'action' => 'add',
                'username' => $sponsor_userdata->user_login,
            );
    
            // Log sponsor API data
            error_log("Sponsor API Data: " . print_r($sponsor_api_data, true));
            $api_url = get_option('shopbiz_api_url') . '/user/update-pv';
            
            $sponsor_response = wp_remote_post($api_url, array(
                'body' => json_encode($sponsor_api_data),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'secret_key' => get_option('shopbiz_api_token'),
                    'prefix' => '1119'
                ),
                'method' => 'POST'
            ));
    
            // Log the API request
            error_log("API Request to $api_url with data: " . print_r($sponsor_api_data, true));
    
            // Log sponsor API response
            if (is_wp_error($sponsor_response)) {
                error_log('Sponsor API call failed: ' . $sponsor_response->get_error_message());
            } else {
                $response_code = wp_remote_retrieve_response_code($sponsor_response);
                $response_body = wp_remote_retrieve_body($sponsor_response);
    
                // Log the API response code and body
                error_log("Sponsor API response code: $response_code");
                error_log("Sponsor API response body: $response_body");
    
                if (200 !== $response_code) {
                    error_log('Sponsor API call returned an error: ' . $response_body);
                }
            }
        } else {
            error_log("No sponsor found for order ID: $order_id");
        }
    } else {
        error_log("No commission points found for sponsor in order $order_id");
    }
}

// add_action('woocommerce_order_status_completed', 'shopbiz_order_complete', 100);
add_action('woocommerce_order_status_completed', function($order_id) {
    if (!wp_next_scheduled('shopbiz_order_complete_background', [$order_id])) {
        wp_schedule_single_event(time(), 'shopbiz_order_complete_background', [$order_id]);
    }
}, 100);

add_action('shopbiz_order_complete_background', 'shopbiz_order_complete');

