<?php
// update pv when order is completed
function shopbiz_order_complete($order_id)
{
  global $wpdb;
  $order = wc_get_order($order_id);
  $customer_id = $order->get_customer_id();
  $userdata = get_userdata($customer_id);

  $table_name = $wpdb->prefix . 'myCRED_log';
  $query = $wpdb->prepare("SELECT SUM(creds) as total FROM $table_name
    WHERE ctype = 'point_level' AND user_id = %d AND ref_id = %d", array($userdata->ID, $order_id));
  $cred_data = $wpdb->get_row($query);

  error_log('cred_data: ' . print_r($cred_data, true));

  if ($cred_data && $cred_data->total > 0) {
    $api_url = get_option('shopbiz_api_url') . '/user/update-pv';
    $api_data = array(
      'pv' => intval($cred_data->total),
      'action' => 'add',
      'username' => $userdata->user_login,
      'data' => json_encode($query),
    );

    $response = wp_remote_post($api_url, array(
      'body' => $api_data,
      'headers' => array(
        'api-key' => get_option('shopbiz_api_token'),
      )
    )
    );

    // Handle the API response
    if (is_wp_error($response)) {
      // API call failed
      error_log('API call failed: ' . $response->get_error_message());
    } else {
      // API call successful
      $response_code = wp_remote_retrieve_response_code($response);
      $response_body = wp_remote_retrieve_body($response);
      if (200 !== $response_code) {
        // API call returned an error
        error_log('API call returned an error: ' . $response_body);
      } else {
        $response_body = json_decode($response_body, true);
        print_r($response_body);
        // Process the API response as needed
      }
      // Process the API response as needed
    }
  } else {
    error_log('No pv data found for order ' . $order_id);
  }
}
add_action('woocommerce_order_status_completed', 'shopbiz_order_complete', 20);
