<?php
class Komship_API {
    private $base_url;
    private $api_key;
    private $api_version = 'v1';

    public function __construct() {
        //$this->base_url = get_option('komship_base_url', 'https://api.collaborator.komerce.my.id');
        $this->base_url = get_option('komship_base_url', 'https://api.collaborator.komerce.id');
        $this->api_key = get_option('komship_api_key');
    }

    public function create_label($order_no){
        $order_no_encoded = urlencode($order_no);
        $url = sprintf(
            '%s/order/api/%s/orders/print-label',
            $this->base_url,
            $this->api_version
        );
        $param = [
            'page' => 'page_1',
            'order_no' => $order_no_encoded,
        ];
        $response = $this->send_request($url, $order_data, 'POST' , $param );
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        return isset($response['data']) ? $response['data'] : [];
    }
    
    public function history_airway_bill($data){
        $url = sprintf(
            '%s/order/api/%s/orders/history-airway-bill',
            $this->base_url,
            $this->api_version
        );
        $param = array(
                'shipping' => $data['shipping'],
                'airway_bill' => $data['airway_bill']
            );
        $response = $this->send_request($url, null, 'GET' , $param);
        
        if(is_wp_error($response)){
            return $response;
        }
        return isset($response['data']) ? $response['data'] : [];
    }
    
    public function request_pickup($order_data){
        $url = sprintf(
            '%s/order/api/%s/pickup/request',
            $this->base_url,
            $this->api_version
        );
        
        $response = $this->send_request($url, $order_data, 'POST' , []);
        
        if(is_wp_error($response)){
            return $response;
        }
        return isset($response['data']) ? $response['data'] : [];
    }
    
    public function cancel_order($order_no) {
        $order_no_encoded = urlencode($order_no);
    
        $url = sprintf(
            '%s/order/api/%s/orders/cancel',
            $this->base_url,
            $this->api_version
        );
    
        // Set the query parameters
        $body = [
            'order_no' => $order_no_encoded
        ];
    
        
        $response = $this->send_request($url, $body, 'PUT', []);
    
        // Check if the response is an error
        if (is_wp_error($response)) {
            return $response;
        }
        
        return isset($response['data']) ? $response['data'] : [];
    }
    
    
    /**
     * Search for destinations by keyword
     * 
     * @param string $keyword Postcode or location keyword
     * @return array|WP_Error Array of destinations or WP_Error on failure
     */
    public function search_destinations($keyword) {
        $url = sprintf(
            '%s/tariff/api/%s/destination/search',
            $this->base_url,
            $this->api_version
        );

        $response = $this->send_request($url, null, 'GET', [
            'keyword' => sanitize_text_field($keyword)
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        return isset($response['data']) ? $response['data'] : [];
    }
    
    public function get_order_detail($order_no) {

        $order_no_encoded = urlencode($order_no);
    
        $url = sprintf(
            '%s/order/api/%s/orders/detail',
            $this->base_url,
            $this->api_version
        );
    
        // Set the query parameters
        $params = [
            'order_no' => $order_no_encoded
        ];
    
        
        $response = $this->send_request($url, null, 'GET', $params);
    
        // Check if the response is an error
        if (is_wp_error($response)) {
            return $response;
        }
        
        return isset($response['data']) ? $response['data'] : [];
    }


    /**
     * Calculate shipping rates
     * 
     * @param int $shipper_id Shipper destination ID
     * @param int $receiver_id Receiver destination ID
     * @param float $weight Weight in kg
     * @param float $item_value Total order value
     * @param string $cod Whether to use COD (yes/no)
     * @return array|WP_Error Shipping calculations or WP_Error on failure
     */
    public function calculate_shipping($shipper_id, $receiver_id, $weight, $item_value, $cod = 'no') {
        $url = sprintf(
            '%s/tariff/api/%s/calculate',
            $this->base_url,
            $this->api_version
        );

        $params = [
            'shipper_destination_id' => absint($shipper_id),
            'receiver_destination_id' => absint($receiver_id),
            'weight' => floatval($weight),
            'item_value' => floatval($item_value),
            'cod' => sanitize_text_field($cod)
        ];

        $response = $this->send_request($url, null, 'GET', $params);

        if (is_wp_error($response)) {
            return $response;
        }

        return isset($response['data']) ? $response['data'] : [];
    }
    
    /**
     * Store a new order
     * 
     * @param array $order_data Order data in specified format
     * @return array|WP_Error Response data or WP_Error on failure
     */
    public function store_order($order_data) {
        $url = sprintf(
            '%s/order/api/%s/orders/store',
            $this->base_url,
            $this->api_version
        );

        $response = $this->send_request($url, $order_data, 'POST');

        if (is_wp_error($response)) {
            return $response;
        }

        return isset($response['data']) ? $response['data'] : [];
    }

    /**
     * Send API request
     * 
     * @param string $url API endpoint URL
     * @param array|null $body Request body for POST requests
     * @param string $method HTTP method (GET/POST)
     * @param array $params URL parameters for GET requests
     * @return array|WP_Error Response data or WP_Error on failure
     */
    private function send_request($url, $body = null, $method = 'POST', $params = []) {
        if (!empty($params)) {
            $url = add_query_arg($params, $url);
        }

        $args = [
            'headers' => [
                'x-api-key' => $this->api_key,
                'Accept' => 'application/json'
            ],
            'method' => $method,
            'timeout' => 45,
        ];

        if ($body !== null) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = wp_json_encode($body);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return new WP_Error(
                'komship_api_error',
                sprintf(__('API request failed: %s', 'komship-integration'), $response->get_error_message())
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        if ($response_code !== 200 && $response_code !== 201) {
            return new WP_Error(
                'komship_api_error',
                sprintf(__('%s', 'komship-integration'), $response_body)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error(
                'komship_api_error',
                __('Invalid JSON response from API', 'komship-integration')
            );
        }

        return $data;
    }
}