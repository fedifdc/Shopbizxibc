<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Withdrawal_Request {
    private $api_url = 'https://tegal.wablas.com/api/send-message';
    private $authorization_key;
  	private $secret_key;
    private static $executed = false; // Tambahkan flag static

    public function __construct() {
        add_action('withdraw_request_submitted', [$this,'notify_admin_withdraw'], 10, 4);
        $this->authorization_key = get_option('whatsapp_api_key', '');
        $this->secret_key = get_option('whatsapp_secret_key', '');
    }

    function send_withdraw_notification($user_id, $amount, $total_amount) {
        $user_info = get_userdata($user_id);
        $email = $user_info->user_email;
        // no hp admin
        $subject = 'Permintaan Withdraw Diajukan';
        $message = "Halo " . $user_info->display_name . ",\n\n".
                   "Permintaan withdraw sebesar Rp. " . number_format($amount) . " telah diajukan.\n".
                   "Biaya: Rp. " . number_format($total_amount - $amount) . "\n".
                   "Total: Rp. " . number_format($total_amount) . "\n\n".
                   "Silakan cek dashboard Anda untuk statusnya.";
    
       
    }

    function notify_admin_withdraw($user_id, $amount, $total_amount, $reference_id) {
        if (self::$executed) return; // Mencegah eksekusi ulang
        self::$executed = true;

        // Tambahkan log untuk debug
        error_log('notify_admin_withdraw executed');

        $admin_email = get_option('admin_email');
        $user_info = get_userdata($user_id);
        // $phone = get_user_meta($user_id, '_whatsapp_number', true);
        // if(empty($phone)){
        //     $phone = get_user_meta($user_id, 'billing_phone', true);
        // }
        $phone = get_option('whatsapp_notif_withdrawl_number', '');
        $message = "Pengguna " . $user_info->display_name . " (ID: $user_id) telah mengajukan withdraw sebesar dengan id ". $reference_id ." Rp. " . number_format($amount) . ".\n".
                   "Total termasuk biaya: Rp. " . number_format($total_amount) . ".\n".
                   "Silakan periksa dashboard untuk menindaklanjuti.";
    
      $this->send_notif($phone, $message);
    }
    /**
     * Send OTP to the provided phone number
     */
    public function send_notif($phone, $message)
    {
        if (empty($this->authorization_key)) {
            return false; // API key is not set.
        }

        // Generate a 6-digit OTP
     
        $wa = normalize_whatsapp_number($phone);
        // Send request to API
        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'Authorization' => $this->authorization_key.'.'.$this->secret_key,
            ],
            'body' => [
                'phone'   => $wa,
                'message' => $message,
            ],
        ]);

        if (is_wp_error($response)) {
            return false; // Request failed.
        }

     

        return true;
    }

    
}

new Withdrawal_Request();