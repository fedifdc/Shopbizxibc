<?php
function handle_order_status_cancel($order_id) {
    // Skip if MLM connection is disabled in admin settings
    if (get_option('shopbiz_mlm_enabled', '1') !== '1') {
        error_log("MLM connection disabled - skipping point deduction for cancelled order $order_id");
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'myCRED_log'; // Update table name if different
    $api_url = get_option('shopbiz_api_url') . '/user/update-pv';

    $sponsor_points_query = $wpdb->prepare(
        "SELECT user_id, creds FROM $table_name WHERE ctype = 'point_level' AND ref = 'commission' AND ref_id = %d ORDER BY id DESC LIMIT 1",
        $order_id
    );

    // Log the query
    error_log("Sponsor points query (Cancel Order): $sponsor_points_query");

    $sponsor_cred_data = $wpdb->get_row($sponsor_points_query);

    // Log the result of the query
    error_log("Sponsor points data (Cancel Order): " . print_r($sponsor_cred_data, true));

    if ($sponsor_cred_data && $sponsor_cred_data->creds > 0) {

        $sponsor_id = $sponsor_cred_data->user_id;

        // Log the sponsor ID
        error_log("Sponsor ID (Cancel Order): $sponsor_id");

        $shopbiz_sponsor_id = get_user_meta($sponsor_id, 'shopbiz_user_id', true);

        // Log the ShopBiz sponsor ID
        error_log("ShopBiz Sponsor ID (Cancel Order): " . ($shopbiz_sponsor_id ? $shopbiz_sponsor_id : "Not found"));

        if (!$shopbiz_sponsor_id) {
            error_log("ShopBiz Sponsor ID not found for Sponsor ID (Cancel Order): $sponsor_id");
            return null;
        }

        if ($sponsor_id) {
            $sponsor_userdata = get_userdata($sponsor_id);

            // Log sponsor user data
            error_log("Sponsor User Data (Cancel Order): " . print_r($sponsor_userdata, true));

            $sponsor_api_data = array(
                'pv' => intval($sponsor_cred_data->creds),
                'action' => 'deduct', // Set action to "deduct"
                'username' => $sponsor_userdata->user_login,
            );

            // Log sponsor API data
            error_log("Sponsor API Data (Cancel Order): " . print_r($sponsor_api_data, true));

            $sponsor_response = wp_remote_post($api_url, array(
                'body' => json_encode($sponsor_api_data),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'secret_key' => get_option('shopbiz_api_token'),
                    'prefix' => '1119',
                ),
                'method' => 'POST',
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

// add_action('woocommerce_order_status_cancelled', 'handle_order_status_cancel');

// Hook into WooCommerce order cancellation
add_action('woocommerce_order_status_cancelled', 'schedule_handle_order_status_cancel_shopbiz_connect', 10, 1);

// Function to schedule the cron event
function schedule_handle_order_status_cancel_shopbiz_connect($order_id) {
    if (!wp_next_scheduled('handle_order_status_cancel_cron_shopbiz_connect', array($order_id))) {
        wp_schedule_single_event(time() + 10, 'handle_order_status_cancel_cron_shopbiz_connect', array($order_id));
    }
}

// Actual function that will be executed in the background
add_action('handle_order_status_cancel_cron_shopbiz_connect', 'handle_order_status_cancel', 10, 1);