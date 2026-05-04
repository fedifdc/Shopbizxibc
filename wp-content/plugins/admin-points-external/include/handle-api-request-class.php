<?php
//require __DIR__ . '/trait-point-transaction.php';

abstract class HandleAPIRequest {

    protected $api_url;
    protected $api_key;
    protected $secret_key;
    protected $wpdb;
    protected $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'shopbiz_api_keys';
    }
    /**
     * Fungsi untuk mengautentikasi permintaan API
     */
    public function authenticate_request($request) {
        
        $api_key = $request->get_header('x-api-key');
        $secret_key = $request->get_header('x-secret-key');
        $website = $request->get_header('website');
       
        $this->api_key = $api_key;
        $this->$secret_key = $secret_key;
       
        if (!$api_key || !$secret_key || !$website) {
            return new WP_Error('missing_keys', 'API Key, Secret Key, or Website is missing', array('status' => 401));
        }

        $api_key_data = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $this->table_name WHERE api_key = %s AND secret_key = %s AND website = %s",
            $api_key, $secret_key, $website
        ));

        if ($api_key_data) {
            return 224;
        } else {
            return new WP_Error('invalid_keys', 'Invalid API Key or Secret Key', array('status' => 403));
        }
    }
    
    public function get_user_id($request) {
        $api_key = $request->get_header('x-api-key');
        $secret_key = $request->get_header('x-secret-key');
        $website = $request->get_header('website');

        if (!$api_key || !$secret_key || !$website) {
            return new WP_Error('missing_keys', 'API Key, Secret Key, or Website is missing', array('status' => 401));
        }

        $api_key_data = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM $this->table_name WHERE api_key = %s AND secret_key = %s AND website = %s",
            $api_key, $secret_key, $website
        ));

        if ($api_key_data) {
            return $api_key_data->user_id;
        } else {
            return new WP_Error('invalid_keys', 'Invalid API Key or Secret Key', array('status' => 403));
        }
    }

    public function get_quota($request, $partner_id){
        $quota = get_user_meta($partner_id, 'point_seller_internal', true);
        return $quota;
    }

    public function insert_point($request,$user_id,$point, $partner_id,$website, $website_owner, $order_id){
        //todo check quota point
       
        $quota =intval( $this->get_quota($request, $website_owner));
        $check_quota = intval($quota - intval($point));
		
        if($check_quota  >= 0) {
            $this->insert_point_external($user_id,$point, $partner_user_id,$website, $order_id);
            $this->substract_quota($website_owner,$point, $website, $user_id, $order_id);
            return true;
        } else {
            $this->insert_point_pending($user_id,$point,$partner_user_id,$website, $order_id);
            return true;
        }
    }

    public function refund_point($request,$user_id,$point, $partner_id,$website, $website_owner, $order_id){
        //check point on external or pending
        $point_log = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}myCRED_log WHERE user_id = %d AND ref_id = %s AND creds = %d AND ctype = %s",
            $user_id, $order_id, $point, 'point_external_website'
        ));

        $point_pending = $this->wpdb->get_row($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}myCRED_log WHERE user_id = %d AND ref_id = %s AND creds = %d AND ctype = %s",
            $user_id, $order_id, $point, 'point_external_pending'
        ));
       

        if($point_pending){
            $this->refund_point_pending($user_id,$point, $partner_user_id,$website, $order_id);
            $this->refund_quota($website_owner,$point, $website, $user_id, $order_id);
          return true;
        } else if($point_log){
            $this->refund_point_external($user_id,$point, $partner_user_id,$website, $order_id);
            $this->refund_quota($website_owner,$point, $website, $user_id, $order_id);
          return true;
        } else {
            return false;
        }
    }



    public function insert_point_external($user_id,$point, $partner_id,$website, $order_id){
        $point_type = "point_external_website";
        $payout = $point;
        $buyer_id  = $user_id;
        if($payout == 0){
            return;
        }
        $reference = "reward";
        $log       = "Memberikan Poin sebesar {$payout} sebagai reward dari  www.{$website}";
		   
		mycred_add(
			$reference,
			$buyer_id,
			$payout,
			$log,
			$order_id,
			array( 'ref_type' => 'post' , 'website'=> $website),
			$point_type
		);
		
    }

    public function refund_point_external($user_id,$point, $partner_id,$website, $order_id){
        $point_type = "point_external_website";
        $payout = $point;
        $buyer_id  = $user_id;
        if($payout == 0){
            return;
        }
        $reference = "external_refund";
        $log       = "Mengurangi Poin sebesar {$payout} sebagai refund dari order ${order_id} pada www.{$website}";
		   
		mycred_add(
			$reference,
			$buyer_id,
			-$payout,
			$log,
			$order_id,
			array( 'ref_type' => 'post' , 'website'=> $website),
			$point_type
		);
		
    }

    public function refund_point_pending($user_id,$point, $partner_id,$website, $order_id){
        $point_type = "point_external_pending";
        $payout = $point;
        $buyer_id  = $user_id;
        if($payout == 0){
            return;
        }
        $reference = "pending_refund";
        $log       = "Mengurangi Poin sebesar {$payout} sebagai refund dari order ${order_id} pada www.{$website}";
		   
		mycred_add(
			$reference,
			$buyer_id,
			-$payout,
			$log,
			$order_id,
			array( 'ref_type' => 'post' , 'website'=> $website),
			$point_type
		);
		
    }

    public function insert_point_pending($user_id,$point, $partner_id, $order_id){
        $point_type = 'point_external_pending';
        $payout = $point;
        $buyer_id  = $user_id;
        if($payout == 0){
            return;
        }
        $reference = "reward";
        $log       = "Poin sebesar{$payout} di tahan dari website www.{$website} karena Pont Seller nya kurang untuk memberikan Poin";
        
		mycred_add(
            $reference,
            $buyer_id,
            $payout,
            $log,
            $order_id,
            array( 'ref_type' => 'post' , 'website'=> $website),
            $point_type
        );
		
    }

    public function substract_quota($user_id, $payout, $website, $child_user, $order_id){
        $point_type = "point_seller_internal";
        $log = "Memberikan poin external sebesar {$payout} ke user {$child_user} member dari {$website}";
        $reference = "debit_quota";
		
        mycred_add(
            $reference,
            $user_id,
            -$payout,
            $log,
            $order_id,
            array( 'ref_type' => 'post' , 'website'=> $website),
            $point_type
        );
    }

    public function substract_point_external($user_id, $payout, $point_type, $website,$partner_user_id){
        $point_type = "mycred_default";
        $log = "Convert {$payout} from Point External member of {$website}";
        $reference = "convert-point-external";
        mycred_subtract(
            $reference,
            $buyer_id,
            $payout,
            $log,
            '',
            array( 'ref_type' => 'post' , 'website'=> $website),
            $point_type
        );
    }

    public function substract_point_pending($user_id, $payout, $point_type, $website,$partner_user_id){
        $point_type = "point_external_pending";
        $log = "Convert Pending Point {$payout}  from user {$user_id} member of {$website}";
        $reference = "convert-point-external-pending";
        mycred_subtract(
            $reference,
            $buyer_id,
            $payout,
            $log,
            '',
            array( 'ref_type' => 'post' ),
            $point_type
        );
    }

    public function refund_quota($user_id, $payout, $website, $child_user, $order_id){
        $point_type = "point_seller_internal";
        $log = "refund saldo sebesar {$payout} karena user {$child_user} melakukan refund dari ${order} pada website {$website}";
        $reference = "debit_quota";
		
        mycred_add(
            $reference,
            $user_id,
            $payout,
            $log,
            $order_id,
            array( 'ref_type' => 'post' , 'website'=> $website),
            $point_type
        );
    }
    
     public function update_mycred_log_ctype_transaction($id, $ref) {
        global $wpdb;
        if (empty($id) || !is_numeric($id)) {
            return new WP_Error('invalid_id', 'Invalid ID provided.');
        }
        $table_name = $wpdb->prefix . 'myCRED_log';
        $result = $wpdb->update(
            $table_name,
            array('ref' => $ref ),   
            array('id' => $id),
            array('%s'),
            array('%d')
        );
        if ($result === false) {
            return new WP_Error('db_update_error', 'Failed to update ctype.', $wpdb->last_error);
        }
    
        return $result;
    }
  
      public function get_user_id_by_partner_id($partner_user_id, $website) {
        global $wpdb;
        //select user_id to table wp_shopbiz_user_sync dengan kondisi user_partner_id = $partner_user_id
        $user_shopbiz = $wpdb->get_var($wpdb->prepare(
            "SELECT  
                user_id
                FROM {$wpdb->prefix}shopbiz_user_sync a
                WHERE a.user_partner_id = %s and a.website = %s",
            $partner_user_id, $website
        ));
        if ( $user_shopbiz) {
            return $user_shopbiz ;
        } else {
            return new WP_Error('invalid_partner_user_id', 'Invalid Partner User ID', array('status' => 404));
        }
    }
}