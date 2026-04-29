<?php
// File: /Applications/XAMPP/xamppfiles/htdocs/shopbiz_190225/wp-content/plugins/xendit-payment/includes/api.php

// Add action to initialize the webhook endpoint
// add_action('rest_api_init', function () {
//     register_rest_route('xendit-payment/v1', '/webhook', array(
//         'methods' => 'POST',
//         'callback' => 'handle_xendit_webhook',
//         'permission_callback' => '__return_true',
//     ));
// });

// function handle_xendit_webhook(WP_REST_Request $request) {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'xendit_webhook';

//     $body = $request->get_body();
//     $data = json_decode($body, true);

//     if (json_last_error() !== JSON_ERROR_NONE) {
//         return new WP_Error('invalid_json', 'Invalid JSON payload', array('status' => 400));
//     }

//     // Process the webhook data
//     // Example: Log the data for debugging
//     error_log(print_r($data, true));

//     // Extract relevant fields from the webhook data
//     $event = isset($data['event']) ? $data['event'] : '';
//     $external_id = isset($data['external_id']) ? $data['external_id'] : '';
//     $xendit_id = isset($data['id']) ? $data['id'] : '';
//     $status = isset($data['status']) ? $data['status'] : '';
//     $amount = isset($data['amount']) ? $data['amount'] : 0;
//     $bank_code = isset($data['bank_code']) ? $data['bank_code'] : '';
//     $account_number = isset($data['account_number']) ? $data['account_number'] : '';

//     // Insert the data into the xendit_webhook table
//     $wpdb->insert(
//         $table_name,
//         array(
//             'event' => $event,
//             'external_id' => $external_id,
//             'xendit_id' => $xendit_id,
//             'status' => $status,
//             'amount' => $amount,
//             'bank_code' => $bank_code,
//             'account_number' => $account_number,
//             'data' => wp_json_encode($data),
//             'created_at' => current_time('mysql'),
//             'updated_at' => current_time('mysql')
//         ),
//         array(
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%f',
//             '%s',
//             '%s',
//             '%s',
//             '%s',
//             '%s'
//         )
//     );

//     // Add your custom processing logic here

//     return new WP_REST_Response(array('status' => 'success'), 200);
// }
?>