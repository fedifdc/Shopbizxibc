<?php

class Request_Xendit {
    public $api_key;
    public $api_base = 'https://api.xendit.co';

    public function __construct() {
        $this->api_key = $this->get_xendit_api_key();
    }

    public function send_request($endpoint, $method = 'GET', $data = []) {
        $url = $this->api_base . $endpoint;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->api_key . ':')
            //'Authorization: '. $this->api_key
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);
error_log(
    'data xendit callback: ' .
    json_encode([
    'http_code' => $http_code,
    'response' => json_decode($response, true)
]));
        return [
            'http_code' => $http_code,
            'response' => json_decode($response, true)
        ];
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

 

}
?>