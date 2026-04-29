<?php

require_once plugin_dir_path(__FILE__) . 'class-request-xendit.php';

class Payout_Request extends Request_Xendit {
    public function __construct() {
        parent::__construct();
    }

    public function get_xendit_api_key() {
        $options = get_option('xendit_payment_settings');
        $site_url = get_site_url();
    
        
        if (strpos($site_url, 'shopbiz.id') !== false) {
            return $options['prod_key'];
        } else {
            return $options['dev_key'];
        }
    }
    public function check_balance() {
        $api_key = $this->get_xendit_api_key();
        $req_class = new Request_Xendit();
        $result = $req_class->send_request('/balance', 'GET', []);
        error_log(print_r($result, true));
        if ($result === FALSE) {
            throw new Exception('Error checking balance');
        }
        return $result;
    }
    public function create_payout($id) {
        $data_request = $this->get_withdraw_request_by_id($id);


        $data = [
            'external_id' => $data_request->reference_id,
            'amount' => (int)$data_request->amount,
            'bank_code' =>  strtoupper($data_request->payment_method),
            'account_holder_name' => $data_request->ac,
            'account_number' => $data_request->account_number,
            'description' => "withdrawl request #{$data_request->id}",
            'metadata' => (object)[
                'user_id' => $data_request->user_id,
                'transaction_id' => $data_request->id,
            
            ]
        ];
            
        // Remove null values
        $data = array_filter($data, function($value) {
            return !is_null($value);
        });
       
      

        $req_class = new Request_Xendit();

        $result = $req_class->send_request('/disbursements', 'POST', $data);

        if ($result === FALSE) {
            throw new Exception('Error creating payout');
        }

        return $result;
    }

    function get_withdraw_request_by_id($id) {
        global $wpdb;
        $table_name = 'cb_withdraw_requests';
        $users_table = $wpdb->prefix . 'users';
        $table_member = $wpdb->prefix . 'member';
        return $wpdb->get_row($wpdb->prepare(
            "SELECT wr.*, u.user_email , m.ac
             FROM $table_name wr 
             JOIN $users_table u ON wr.user_id = u.ID 
             JOIN $table_member m ON wr.user_id = m.idwp
             WHERE wr.id = %d", 
            $id
        ));
    }
}   


?>