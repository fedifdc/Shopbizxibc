<?php
//require __DIR__ . '/handle-api-request-class.php';
//require_once WP_PLUGIN_DIR .'/mycred/includes/classes/class.query-log.php';

class ShopbizPointExternalAPI extends HandleAPIRequest{

    public function point_level_by_user_website() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/point-level-user-website/', array(
                'methods' => 'POST',
                'callback' => array($this, 'get_user_point_level_by_website'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function check_point_level_convert_api() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/check-point-level-convert/', array(
                'methods' => 'POST',
                'callback' => array($this, 'check_oder_convert_to_poin_level'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function transaction_point_external_api() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/point-external/', array(
                'methods' => 'POST',
                'callback' => array($this, 'point_external_from_transaction'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function check_user_sync() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/check-user-sync/', array(
                'methods' => 'POST',
                'callback' => array($this, 'check_user_sync_by_user_partner_id'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function point_external_from_transaction($request) {
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_partner_id = $body['user_partner_id'];
        $poin_transaction = $body['poin_transaction'];
        $website = $request->get_header('website');
		$order_id = $body['order_id'];
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        $curUser = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_partner_id = %s AND website = %s",
            $user_partner_id, $website
        ));
      
		$website_owner = $this->get_user_id($request);
		 
        if(!$curUser){
            return new WP_REST_Response( array(
                'status' => 'fail',
                'message' => "email tidak terdaftar di shopbiz.id"
            ), 422);
        }
        $user = get_user_by('id',$curUser->user_id);
        $partner_user_id = $this->get_user_id($request);
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        
        $is_success = $this->insert_point($request,$user->id,$poin_transaction , $partner_user_id,$website, $website_owner, $order_id);
		    
        if($is_success){
            return new WP_REST_Response( array(
                'status' => 'success',
                'message' => "Berhasil Input Point dari account ${website} dengan account shopbiz.id"
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'fail',
                'message' => "error"
            ), 200);
        }  
    }

    public function log_point_quota() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/point-quota-log/', array(
                'methods' => 'GET',
                'callback' => array($this, 'poin_shopbiz_point_log'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function poin_shopbiz_point_log($request){
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_ids = $this->get_website_users_logs($request->get_header('website'));
        $args = array(
            'user_id' => $this->get_user_id($request),
            'number'    =>  -1,
            'order'     =>  'DESC',
            'ctype'     =>  "point_seller_internal",
        );

        $log =  $this->custom_query_log(  $args );
        if ( sizeOf($log) > 0) {
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => $log
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => []
            ), 200);
        }
    }

    public function log_point_external() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/point-external-log/', array(
                'methods' => 'GET',
                'callback' => array($this, 'poin_shopbiz_external_log'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function poin_shopbiz_external_log($request){
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_ids = $this->get_website_users_logs($request->get_header('website'));
  
        $args = array(
            'user_id' => $user_ids,
            'number'    =>  -1,
            'order'     =>  'DESC',
            'ctype'     =>  "point_external_website",
            'entry_like' => $request->get_header('website')
        );
       $log =  $this->custom_query_log(  $args );
        if ( sizeOf($log) > 0) {
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => $log
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => []
            ), 200);
        }
    }

    public function log_point_pending() {
        add_action('rest_api_init', function() {
            // Register order endpoint
            register_rest_route('shopbiz/v1', '/point-pending-log/', array(
                'methods' => 'GET',
                'callback' => array($this, 'poin_shopbiz_pending_log'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }
    
    public function poin_shopbiz_pending_log($request){
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_ids = $this->get_website_users_logs($request->get_header('website'));
        
        $args = array(
            'user_id' => $user_ids,
            'number'    =>  -1,
            'order'     =>  'DESC',
            'ctype'     =>  "point_external_pending",
            'entry_like' => $request->get_header('website')
        );
        
        $log =  $this->custom_query_log(  $args );
        if ( sizeOf($log) > 0) { 
            //return  $log->display();
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => $log
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'success',
                'data' => []
            ), 200);
        }
    }

    public function get_website_users_logs($website){
        global $wpdb;
        $table_name = $wpdb->prefix . 'shopbiz_user_sync'; // Nama tabel lengkap
    
        $user_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT user_id FROM $table_name WHERE website = %s",
            $website
        ));
        if(sizeOf($user_ids) == 0){
            return -1;
        }
        
        return implode(',', $user_ids);
    }

    public function get_user_point_level_by_website($request){
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_id = $body['user_id'];
        $website = $request->get_header('website');
        $user_shopbiz = $wpdb->get_var($wpdb->prepare(
            "SELECT  
                user_id
                FROM {$wpdb->prefix}shopbiz_user_sync a
                WHERE a.user_partner_id = %s and a.website = %s",
            $user_id, $website
        ));
        
        $logs = $wpdb->get_var($wpdb->prepare(
            "SELECT  
                SUM(a.creds) AS total_points
                FROM {$wpdb->prefix}myCRED_log a
                WHERE a.ctype = 'point_level'
                and user_id = %s",
            $user_shopbiz
        ));

        return new WP_REST_Response( array(
            'status' => 'success',
            'data' => $logs
        ), 200);
            
    }

    public function check_user_sync_by_user_partner_id($request){
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_id = $body['user_id'];
        $website = $request->get_header('website');
        $user_shopbiz = $wpdb->get_results($wpdb->prepare(
            "SELECT  
                a.user_id, b.user_email
                FROM {$wpdb->prefix}shopbiz_user_sync a
                join {$wpdb->prefix}users b on a.user_id = b.ID 
                WHERE a.user_partner_id = %s and a.website = %s",
            $user_id, $website
        ));
        
        if($user_shopbiz && count($user_shopbiz) > 0){
            return new WP_REST_Response( array(
                'status' => 'sync',
                'email' => $user_shopbiz[0]->user_email,
                'user_id' => $user_shopbiz[0]->user_id
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'not sync',
                'message' => "email tidak terdaftar di shopbiz.id"
            ), 422);
        }
             
    }

    function custom_query_log($args = array()) {
        global $wpdb;
        $defaults = array(
            'user_id'    => array(), 
            'ctype'      => '',
            'number'     => 10,
            'order'      => 'DESC',
            'entry_like' => '',
        );

        $args = wp_parse_args($args, $defaults);

        $query = "SELECT id, ref, ref_id, user_id, creds, ctype, time, entry, data 
                  FROM {$wpdb->prefix}myCRED_log 
                  WHERE 1=1";

        if (!empty($args['user_id'])) {
            $query .= " AND user_id IN ({$args['user_id']})";
        }

        if (!empty($args['ctype'])) {
            $query .= $wpdb->prepare(" AND ctype = %s", $args['ctype']);
        }
    
        if (!empty($args['entry_like'])) {
            $wild = '%';
            $find =$wpdb->esc_like( $args['entry_like']);
            $like = $wild .  $find . $wild;
            $query .= " AND entry LIKE  CONCAT('%', '$find','%')";
        }
  
       
        $query .= " ORDER BY time " . ($args['order'] === 'ASC' ? 'ASC' : 'DESC');

        // Tambahkan batasan jumlah log
        // if ($args['number'] !== -1) {
        //     $query .= $wpdb->prepare(" LIMIT %d", intval($args['number']));
        // }
      
        $results = $wpdb->get_results($query);
      
        return $results;

        $formatted_results = array();
        foreach ($results as $row) {
            $formatted_results[] = array(
                'id'       => $row['id'],
                'ref'      => $row['ref'],
                'ref_id'   => $row['ref_id'],
                'user_id'  => $row['user_id'],
                'creds'    => $row['creds'],
                'ctype'    => $row['ctype'],
                'time'     => $row['time'],
                'entry'    => $row['entry'],
                'data'     => $row['data'], // Serialized data
            );
        }

        return $formatted_results;
    }

    public function check_oder_convert_to_poin_level($request){
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_id = $body['user_id'];
        $order_id = $body['order_id'];
        $website = $request->get_header('website');
        
        // Escape dan siapkan string untuk LIKE query
        $wild = '%';
        $find = $wpdb->esc_like($website);
        $like = $wild . $find . $wild;
        
        // Query
        $query = "
            SELECT id
            FROM {$wpdb->prefix}myCRED_log
            WHERE ctype = 'point_level'
              AND ref_id = %s 
              AND entry LIKE %s
        ";
        
        $prepared_query = $wpdb->prepare($query, $order_id, $like);
        $log_id = $wpdb->get_results($prepared_query);
        if ($log_id && count($log_id) > 0) {
            return new WP_REST_Response(array(
                'status' => 'convert',
            ), 200);
        } else {
            return new WP_REST_Response(array(
                'status' => 'not_convert',
            ), 200);
        }    
    }

    public function point_external_refund(){
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/point-external-refund/', array(
                'methods' => 'POST',
                'callback' => array($this, 'point_external_refund_transaction'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function point_external_refund_transaction($request){
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_partner_id = $body['user_partner_id'];
        $poin_transaction = $body['poin_transaction'];
        $website = $request->get_header('website');
        $order_id = $body['order_id'];
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';


        $curUser = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_partner_id = %s AND website = %s",
            $user_partner_id, $website
        ));

        $website_owner = $this->get_user_id($request);
        if(!$curUser){
            return new WP_REST_Response( array(
                'status' => 'fail',
                'message' => "email tidak terdaftar di shopbiz.id"
            ), 422);
        }
        $user = get_user_by('id',$curUser->user_id);
        $partner_user_id = $this->get_user_id($request);
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        
        $is_success = $this->refund_point($request,$user->id,$poin_transaction , $partner_user_id,$website, $website_owner, $order_id);
            
        if($is_success){
            return new WP_REST_Response( array(
                'status' => 'success',
                'message' => "Berhasil refund Point dari account ${website} dan order ${order_id} dengan account shopbiz.id"
            ), 200);
        } else {
            return new WP_REST_Response( array(
                'status' => 'fail',
                'message' => "error"
            ), 200);
        }  
    }
    
     public function list_user_sync() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/list-user-sync/', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_list_user_sync'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function get_list_user_sync($request) {
        global $wpdb;
        $website = $request->get_header('website');
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';

        $users = $wpdb->get_results($wpdb->prepare(
            "SELECT u.user_id, u.user_partner_id, w.user_email 
             FROM $table_name u
             JOIN {$wpdb->prefix}users w ON u.user_id = w.ID
             WHERE u.website = %s",
            $website
        ));

        if ($users) {
            return new WP_REST_Response(array(
                'status' => 'success',
                'data' => $users
            ), 200);
        } else {
            return new WP_REST_Response(array(
                'status' => 'fail',
                'message' => 'No users found'
            ), 404);
        }
    }
}