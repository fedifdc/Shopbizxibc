<?php
class YithAccountAPI extends HandleAPIRequest {
    public function get_user_balance_endpoint($request) {
        global $wpdb;
        $website = $request->get_header('website');
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_partner_id = $body['user_partner_id'];
        $user_id = $this->get_user_id_by_partner_id($user_partner_id, $website);
        $customer = ywpar_get_customer($user_id);
        if($customer){
            $points = $customer->get_total_points();
        } else {
            $points = 0;
        }
        
        return new WP_REST_Response(array('points' => $points ), 200);
    
    }

    public function deduct_user_points_endpoint($request) {
        global $wpdb;
        $website = $request->get_header('website');
        $body = json_decode(stripslashes($request->get_body()), true);
       ;
        $user_partner_id = $body['user_partner_id'];
        $user_id = $this->get_user_id_by_partner_id($user_partner_id, $website);
        $customer = ywpar_get_customer($user_id);
        $current_points = $customer->get_total_points();
        $points_to_deduct = $body['point'];
        if ( $current_points > 0 && $points_to_deduct > 0) {
            if ($current_points >= $points_to_deduct) {
                $new_points = $current_points -  $points_to_deduct;
                error_log('total points: ' . $new_points);  
                $customer->set_total_points( ( $new_points > 0 ) ? $new_points : 0 );
                $customer->save();
                error_log('mulai inserts: ' . json_encode($body));
                $wpdb->insert(
                    'wp_yith_ywpar_points_log',
                    array(
                        'user_id' => $user_id,
                        'amount' => -$points_to_deduct,
                        'action' => 'redeem_website_partner',
                        'date_earning' => current_time('mysql'),
                        'description' => 'Points deducted via '. $website 
                    ),
                    array(
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                        '%s'
                    )
                );
                return new WP_REST_Response(array('success' => true, 'points' => $customer->get_total_points()), 200);
            } else {
                return new WP_REST_Response(array('success' => false, 'message' => 'Insufficient points'), 400);
            }
        } else {
            return new WP_REST_Response(array('success' => false, 'message' => 'Invalid request'), 400);
        }
    }

    public function refund_user_points_endpoint($request) {
        global $wpdb;
        $website = $request->get_header('website');
        $body = json_decode(stripslashes($request->get_body()), true);
     
        $user_partner_id = $body['user_partner_id'];
        $user_id = $this->get_user_id_by_partner_id($user_partner_id, $website);
        $customer = ywpar_get_customer($user_id);
        $current_points =intval( $customer->get_total_points());
        $points_to_refund = intval($body['point']);
        if ( $current_points >=0 && $points_to_refund > 0) {
            $new_points = $current_points +  $points_to_refund;
            $customer->set_total_points( ( $new_points > 0 ) ? $new_points : 0 );
            $customer->save();
            error_log('mulai refund log: ' . json_encode($body));
            $wpdb->insert(
                'wp_yith_ywpar_points_log',
                array(
                    'user_id' => $user_id,
                    'amount' => $points_to_refund,
                    'action' => 'refund_website_partner',
                    'date_earning' => current_time('mysql'),
                    'description' => 'Points refunded via '. $website 
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s'
                )
            );
            return new WP_REST_Response(array('success' => true, 'points' => $customer->get_total_points()), 200);
        } else {
            return new WP_REST_Response(array('success' => false, 'message' => 'Invalid request'), 400);
        }
    }

    public function register_routes() {
        add_action('rest_api_init', function () {
            register_rest_route('shopbiz/v1', '/yith-balance/', array(
                'methods' => 'POST',
                'callback' => array($this, 'get_user_balance_endpoint'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
            register_rest_route('shopbiz/v1', '/deduct-yith-points/', array(
                'methods' => 'POST',
                'callback' => array($this, 'deduct_user_points_endpoint'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
            register_rest_route('shopbiz/v1', '/refund-yith-points/', array(
                'methods' => 'POST',
                'callback' => array($this, 'refund_user_points_endpoint'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }
}
?>