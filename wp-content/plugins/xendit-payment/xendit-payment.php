<?php
/*
Plugin Name: Shopbiz Xendit Payout + IBC Token
Plugin URI: https://example.com/xendit-payment
Description: Payment gateway plugin for Xendit with IBC Token (BSC) bonus on withdrawals.
Version: 2.0.0
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
require_once plugin_dir_path(__FILE__) . 'includes/class-ibc-transfer.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-ibc-revenue-share.php';
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
    $ibc_transfer = new IBC_Transfer();
    $results = [];

    foreach ($ids as $id) {
        $result = ['id' => $id, 'xendit' => 'pending', 'ibc' => 'skipped'];

        // 1. Xendit Disbursement (existing flow)
        try {
            $response = $payout_request->create_payout($id);
            $result['xendit'] = 'sent';
        } catch (Exception $e) {
            $result['xendit'] = 'error: ' . $e->getMessage();
            error_log('Xendit payout error for WD#' . $id . ': ' . $e->getMessage());
        }

        // 2. IBC Token Bonus Transfer (new)
        if ($ibc_transfer->is_enabled()) {
            try {
                $ibc_result = process_ibc_bonus_for_withdraw($id, $ibc_transfer);
                $result['ibc'] = $ibc_result['success'] ? 'sent' : ('error: ' . $ibc_result['error']);
            } catch (Exception $e) {
                $result['ibc'] = 'error: ' . $e->getMessage();
                error_log('IBC transfer error for WD#' . $id . ': ' . $e->getMessage());
            }
        }

        $results[] = $result;
    }

    wp_send_json_success(['message' => 'Requests processed', 'details' => $results]);
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

    if ($status == 'COMPLETED') {
        // Retrieve the amount and ID from the cb_withdraw_requests table
        $wd_data = $wpdb->get_row($wpdb->prepare(
            "SELECT id, amount FROM $table_name WHERE reference_id = %s",
            $reference_id
        ));
        
        if ($wd_data && $wd_data->amount > 0) {
            $rev_share = new IBC_Revenue_Share();
            $rev_share->distribute_revenue($wd_data->amount, $wd_data->id);
        }
    }

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

/**
 * Process IBC token bonus for a withdraw request
 */
function process_ibc_bonus_for_withdraw($withdraw_id, $ibc_transfer = null) {
    global $wpdb;

    if (!$ibc_transfer) {
        $ibc_transfer = new IBC_Transfer();
    }

    if (!$ibc_transfer->is_enabled()) {
        return ['success' => false, 'error' => 'IBC not enabled'];
    }

    // Get withdraw request data
    $wd = $wpdb->get_row($wpdb->prepare(
        "SELECT wr.*, m.bsc_wallet_address 
         FROM cb_withdraw_requests wr 
         JOIN wp_member m ON wr.user_id = m.idwp 
         WHERE wr.id = %d",
        $withdraw_id
    ));

    if (!$wd) {
        return ['success' => false, 'error' => 'Withdraw request not found'];
    }

    if (empty($wd->bsc_wallet_address)) {
        // Update IBC status in DB
        $wpdb->update('cb_withdraw_requests', 
            ['ibc_status' => 'skipped', 'ibc_error' => 'No BSC wallet address'],
            ['id' => $withdraw_id]
        );
        return ['success' => false, 'error' => 'Member has no BSC wallet address'];
    }

    // Calculate IBC bonus
    $ibc_amount = $ibc_transfer->calculate_ibc_amount($wd->amount);
    $ibc_price = $ibc_transfer->get_ibc_price_idr();
    $bonus_pct = $ibc_transfer->get_bonus_percentage();

    if ($ibc_amount <= 0) {
        return ['success' => false, 'error' => 'IBC amount is 0'];
    }

    // Update DB with IBC details before transfer
    $wpdb->update('cb_withdraw_requests', [
        'ibc_token_amount' => $ibc_amount,
        'ibc_token_price' => $ibc_price,
        'ibc_bonus_percentage' => $bonus_pct,
        'ibc_wallet_address' => $wd->bsc_wallet_address,
        'ibc_status' => 'processing',
    ], ['id' => $withdraw_id]);

    // Execute transfer
    $result = $ibc_transfer->transfer_tokens($wd->bsc_wallet_address, $ibc_amount, $withdraw_id);

    // Update DB with result
    $wpdb->update('cb_withdraw_requests', [
        'ibc_tx_hash' => $result['tx_hash'] ?? '',
        'ibc_status' => $result['success'] ? 'completed' : 'failed',
        'ibc_error' => $result['error'] ?? '',
    ], ['id' => $withdraw_id]);

    return $result;
}

/**
 * AJAX: Get IBC hot wallet info (admin only)
 */
add_action('wp_ajax_get_ibc_wallet_info', 'get_ibc_wallet_info');
function get_ibc_wallet_info() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    try {
        // Check if class exists
        if (!class_exists('IBC_Transfer')) {
            wp_send_json_error('Class IBC_Transfer tidak ditemukan. Cek file class-ibc-transfer.php');
        }

        $ibc = new IBC_Transfer();

        // Check if enabled
        if (!$ibc->is_enabled()) {
            $options = get_option('xendit_payment_settings');
            $debug = [];
            if (empty($options['ibc_token_contract'])) $debug[] = 'Contract Address kosong';
            if (empty($options['bsc_hot_wallet_address'])) $debug[] = 'Hot Wallet Address kosong';
            if (empty($options['bsc_hot_wallet_private_key'])) $debug[] = 'Private Key kosong';
            if (empty($options['ibc_bonus_percentage']) || $options['ibc_bonus_percentage'] <= 0) $debug[] = 'Bonus % = 0';
            if (empty($options['ibc_token_price_idr']) || $options['ibc_token_price_idr'] <= 0) $debug[] = 'Harga IBC = 0';

            wp_send_json_success([
                'bnb_balance' => 'N/A (Belum dikonfigurasi)',
                'ibc_balance' => 'N/A',
                'warning' => implode(', ', $debug),
            ]);
        }

        $bnb = $ibc->get_bnb_balance();
        $ibc_bal = $ibc->get_ibc_balance();

        wp_send_json_success([
            'bnb_balance' => $bnb,
            'ibc_balance' => $ibc_bal,
        ]);

    } catch (Exception $e) {
        wp_send_json_error('Exception: ' . $e->getMessage());
    } catch (Error $e) {
        wp_send_json_error('Error: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
    }
}


/**
 * Run DB migration for IBC columns on plugin activation
 */
register_activation_hook(__FILE__, 'xendit_ibc_db_migration');
add_action('admin_init', 'xendit_ibc_check_db_version');

function xendit_ibc_check_db_version() {
    if (get_option('xendit_ibc_db_version') !== '2.0') {
        xendit_ibc_db_migration();
    }
}

function xendit_ibc_db_migration() {
    global $wpdb;

    // Add IBC columns to cb_withdraw_requests
    $table = 'cb_withdraw_requests';
    $columns_to_add = [
        'ibc_token_amount' => 'DECIMAL(20,8) DEFAULT 0',
        'ibc_token_price' => 'DECIMAL(15,2) DEFAULT 0',
        'ibc_bonus_percentage' => 'DECIMAL(5,2) DEFAULT 0',
        'ibc_wallet_address' => 'VARCHAR(100) DEFAULT NULL',
        'ibc_tx_hash' => 'VARCHAR(100) DEFAULT NULL',
        'ibc_status' => "VARCHAR(20) DEFAULT 'none'",
        'ibc_error' => 'TEXT DEFAULT NULL',
    ];

    foreach ($columns_to_add as $col => $def) {
        $check = $wpdb->get_results("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'");
        if (empty($check)) {
            $wpdb->query("ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$def}");
        }
    }

    // Add bsc_wallet_address to wp_member
    $check = $wpdb->get_results("SHOW COLUMNS FROM `wp_member` LIKE 'bsc_wallet_address'");
    if (empty($check)) {
        $wpdb->query("ALTER TABLE `wp_member` ADD COLUMN `bsc_wallet_address` VARCHAR(100) DEFAULT NULL");
    }

    update_option('xendit_ibc_db_version', '2.0');
}
?>