<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PPOB_Tripay_API
{
    private $api_key;
    private $base_url;

    public function __construct()
    {
        $this->api_key = get_option('ppob_tripay_api_key');
        $this->base_url = get_option('ppob_tripay_base_url');
    }

    /**
     * Kirim request ke API Tripay
     */
    public function request($url, $body = null, $method = 'POST', $params = [], $headers = [])
    {
        if (!empty($params)) {
            $url = add_query_arg($params, $url);
        }

        $args = [
            'method' => $method,
            'timeout' => 45,
        ];

        if (!empty($headers)) {
            $args['headers'] = $headers;
        } else {
            $args['headers']['Authorization'] = 'Bearer ' . $this->api_key;
            $args['headers']['Content-Type'] = 'application/json';
        }


        if ($body !== null) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = $body;
            error_log("Body: " . print_r($args['body'], true));
        }

        // Log request data
        error_log("=== Tripay API Request ===");
        error_log("URL: " . $this->base_url . $url);
        error_log("Method: " . $method);

        $response = null;
        if (isset($headers['x-rapidapi-key'])) {
            $response = wp_remote_request($url, $args);
        } else {
            $response = wp_remote_request($this->base_url . $url, $args);
        }


        if (is_wp_error($response)) {
            return new WP_Error(
                'tripay_api_error',
                sprintf(__('API request failed: %s', 'tripay-integration'), $response->get_error_message())
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);


        if ($response_code !== 200 && $response_code !== 201) {
            return new WP_Error(
                'tripay_api_error',
                sprintf(__('%s', 'tripay-integration'), $response_body)
            );
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error(
                'tripay_api_error',
                __('Invalid JSON response from API', 'tripay-integration')
            );
        }

        return $data;
    }
}
