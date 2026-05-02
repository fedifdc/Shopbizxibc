<?php
/*
Plugin Name: Shopbiz Xendit Payout
Plugin URI: https://example.com/xendit-payment
Description: A payment gateway plugin for Xendit.
Version: 1.0.0
Author: Ruli Setiawan
Author URI: https://example.com
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-payout-request.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-request-xendit.php';
// Main plugin class
class Xendit_Payment_Gateway {
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        // Initialize the plugin
        // Add your initialization code here
        require_once plugin_dir_path(__FILE__) . 'includes/class-request-xendit.php';
        require_once plugin_dir_path(__FILE__) . 'templates/xendit-config.php';
       
    }
}

// Initialize the plugin
new Xendit_Payment_Gateway();

add_action('wp_ajax_approve_withdraw_requests', 'approve_withdraw_requests');

function approve_withdraw_requests() {
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
        wp_send_json_error('Invalid request');
    }

    $ids = $_POST['ids'];
    $payout_request = new Payout_Request();

    foreach ($ids as $id) {
        try {
            $response = $payout_request->create_payout($id);
            
        } catch (Exception $e) {
            wp_send_json_error('Error creating payout: ' . $e->getMessage());
        }
        
    }

    wp_send_json_success('Requests approved successfully');
}


function update_withdraw_request_status($reference_id, $status, $xendit_id) {
    global $wpdb;
    $table_name = 'cb_withdraw_requests';
    if($status == 'COMPLETED'){
        $status_value = 'approved';
    } elseif($status == 'FAILED'){
        $status_value = 'rejected';
    } else {
        $status_value = 'pending';
    }
   
    $wpdb->update(
        $table_name,
        array(
            'status' => $status_value,
            'xendit_id' => $xendit_id
        ), // SET
        array('reference_id' => $reference_id) // WHERE
    );

    if ($status == 'FAILED') {
        // Retrieve the amount from the cb_withdraw_requests table
        $amount = $wpdb->get_var($wpdb->prepare(
            "SELECT amount FROM $table_name WHERE reference_id = %s",
            $reference_id
        ));

        // Update the voucher amount in the wp_member table
        $wpdb->query($wpdb->prepare(
            "UPDATE wp_member SET voucher = voucher + %f WHERE id = (SELECT user_id FROM $table_name WHERE reference_id = %s)",
            $amount,
            $reference_id
        ));
    }
}

add_action('rest_api_init', function () {
    register_rest_route('xendit/v1', '/webhook', array(
        'methods' => 'POST',
        'callback' => 'handle_xendit_webhook',
    ));
});

function handle_xendit_webhook(WP_REST_Request $request) {
    $body = $request->get_json_params();

    global $wpdb;
    $table_name = 'wp_xendit_webhooks';
    $body = $request->get_body();
    $data = json_decode($body, true);
    
    $event = isset($data['event']) ? $data['event'] : '';
    $external_id = isset($data['external_id']) ? $data['external_id'] : '';
    $xendit_id = isset($data['id']) ? $data['id'] : '';
    $status = isset($data['status']) ? $data['status'] : '';
    $amount = isset($data['amount']) ? $data['amount'] : 0;
    $bank_code = isset($data['bank_code']) ? $data['bank_code'] : '';
    $account_number = isset($data['account_number']) ? $data['account_number'] : '';

    // Check if the external_id already exists in the xendit_webhook table
    $existing_entry = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE external_id = %s",
        $external_id
    ));

    if ($existing_entry) {
        return new WP_REST_Response('Webhook already processed', 200);
    }
    // Insert the data into the xendit_webhook table
    $wpdb->insert(
        $table_name,
        array(
            'event' => $event,
            'external_id' => $external_id,
            'xendit_id' => $xendit_id,
            'status' => $status,
            'amount' => $amount,
            'bank_code' => $bank_code,
            'account_number' => $account_number,
            'data' => wp_json_encode($data),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%f',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );
  

    $xendit_id = $data['id'];

   
    update_withdraw_request_status($external_id, $status, $xendit_id);
    return new WP_REST_Response('Webhook handled successfully', 200);
}

add_action('wp_ajax_reject_withdraw_requests', 'reject_withdraw_requests');

function reject_withdraw_requests() {
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
        wp_send_json_error('Invalid request');
    }

    $ids = $_POST['ids'];
    global $wpdb;
    $table_name = 'cb_withdraw_requests';

    foreach ($ids as $id) {
        $wpdb->update(
            $table_name,
            array('status' => 'rejected'), // SET
            array('id' => $id) // WHERE
        );

        // Retrieve the amount from the cb_withdraw_requests table
        $total_amount = $wpdb->get_var($wpdb->prepare(
            "SELECT total_amount FROM $table_name WHERE id = %d",
            $id
        ));

        // Update the status to 'rejected'
        $wpdb->update(
            $table_name,
            array('status' => 'rejected'), // SET
            array('id' => $id) // WHERE
        );

        // Update the voucher amount in the wp_member table
        $wpdb->query($wpdb->prepare(
            "UPDATE wp_member SET sisa_voucher = sisa_voucher + %f WHERE idwp = (SELECT user_id FROM $table_name WHERE id = %d)",
            $total_amount,
            $id
        ));
    }

    wp_send_json_success('Requests rejected successfully');
}


add_action('wp_ajax_get_xendit_balance', 'get_xendit_balance');

function get_xendit_balance() {
    // Check if the current user has the required capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized request');
    }

    // Include the Xendit API class
    require_once plugin_dir_path(__FILE__) . 'includes/class-request-xendit.php';

    // Create an instance of the Xendit API class
    $xendit_request = new Payout_Request();

    try {
        // Get the balance from Xendit
        $balance = $xendit_request->check_balance();
        // Parse the balance response
        $balanceresponse = $balance['response'];
        // Format the balance as currency in IDR
        $formatted_balance = number_format($balanceresponse['balance'], 2, ',', '.');

        // Send the formatted balance as a JSON response
        wp_send_json_success(array('balance' => $formatted_balance));
    } catch (Exception $e) {
        // Send an error response if there was an issue
        wp_send_json_error('Error fetching balance: ' . $e->getMessage());
    }
}
?>