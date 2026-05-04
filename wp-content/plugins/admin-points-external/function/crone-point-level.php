<?php

function convert_point_level_crone() {
    
    if (!wp_next_scheduled('convert_process_mycred_points')) {
        wp_schedule_event(strtotime('17:10:00'), 'daily', 'convert_process_mycred_points');
    }
}
function convert_point_level() {
    add_action('rest_api_init', function() {
        register_rest_route('shopbiz/v1', '/convert-point/', array(
            'methods' => 'GET',
            'callback' => 'process_mycred_points_function',
        ));
    });
}

add_action('convert_process_mycred_points', 'process_mycred_points_function');
function process_mycred_points_function() {
    global $wpdb;
	$timestamps = getDynamicTimestamps();
    // Tanggal hari sebelumnya
    $yesterday_start = $timestamps['start_date'];//strtotime('yesterday midnight +7 hours');
    $yesterday_end = $timestamps['end_date'];//strtotime('today midnight +7 hours') - 1;

    // Ambil log `poin_external` dari tabel wp_mycreed_log untuk hari sebelumnya
    $logs = $wpdb->get_results($wpdb->prepare(
        "SELECT a.id, a.user_id, creds, website, c.user_partner_id, a.ref_id
         FROM {$wpdb->prefix}myCRED_log a 
         LEFT JOIN {$wpdb->prefix}shopbiz_user_sync c ON a.user_id = c.user_id
         WHERE ctype = 'point_external_website' AND ref = 'reward' AND time >= %d AND time <= %d",
        $yesterday_start, $yesterday_end
    ));
    
    if ($logs === null) {
        error_log('Query Error: ' . $wpdb->last_error);
        //die(0);
    } 


    // Proses setiap log
    if ($logs) {
        foreach ($logs as $log) {
            $id = $log->id;
            $user_id = $log->user_id;
            $points = (float) $log->creds;
            $website = $log->website;
            $user_partner_id = $log->user_partner_id;
            $ref_id = $log->ref_id;
            // Ambil nilai poin_level dari usermeta
            $current_level_points = (float) get_user_meta($user_id, 'poin_level', true);
            $current_external_points = (float) get_user_meta($user_id, 'poin_external', true);

            // point bonus
            add_point_level($user_id, $points, $website, $ref_id );

            // point level baru
            add_point_upgrade($user_id, $points, $website, $ref_id );
            // Kurangi poin_external
            substract_quota_from_crone($user_id, $points, $website, $ref_id );

            update_mycred_log_ctype($id, 'convert');
            
			$usermlm = get_user_meta($user_id,'shopbiz_user_id',true);
            // kirim poin ke mlm
			if($usermlm){
            	send_pv_to_mlm($user_id, $points);
			}
			calculate_bonus_from_point_external($user_id, $points, $website, $ref_id );
        }
    }
}

function add_point_level($user_id, $payout, $website, $ref_id){
    $point_type = "point_level";
    $log = "Given {$payout} from convert poin external from user {$user_id} member of {$website}";
    $reference = "convert point";
    mycred_add(
        $reference,
        $user_id,
        $payout,
        $log,
        $ref_id,
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

function substract_quota_from_crone($user_id, $payout, $website, $ref_id){
    $point_type = 'point_external_website';
    $log = "Convert {$payout} to Point Level from user {$user_id} member of {$website}";
    $reference = "convert_point_external";
    mycred_add(
        $reference,
        $user_id,
        -$payout,
        $log,
        $ref_id,
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

function convert_point_external_crone() {
    
    if (!wp_next_scheduled('convert_process_mycred_point_externals')) {
        wp_schedule_event(strtotime('17:01:00'), 'daily', 'convert_process_mycred_point_externals');
    }
}

function convert_process_mycred_point_externals(){
    global $wpdb;
    $point_type = 'point_external_pending';

    // Ambil log `poin_external` dari tabel wp_mycreed_log untuk hari sebelumnya
    $logs = $wpdb->get_results($wpdb->prepare(
            "SELECT a.id, a.user_id, creds, website, c.user_id as user_partner_id, a.ref_id
                FROM {$wpdb->prefix}myCRED_log a 
                LEFT JOIN {$wpdb->prefix}shopbiz_user_sync c ON a.user_id = c.user_id
                WHERE ctype = 'point_external_pending' AND ref='reward' order by id asc"));
     
     if($logs == null){
        error_log('Query Error: ' . $wpdb->last_error);
     }
    
    // die(0);
     if($logs){

        foreach ($logs as $log){
           
            $id = $log->id;
            $user_id = $log->user_id;
            $points = (float) $log->creds;
            $website = $log->website;
            $user_partner_id = $log->user_partner_id;
            $current_quota = (float) get_user_meta($user_partner_id, 'point_seller_internal', true);
            $ref_id = $log->ref_id;
            if($current_quota >= $points){

                add_point_external_from_pending($user_id, $points, $website, $ref_id);

                substract_point_external_from_crone($id, $user_id, $points, $website,$ref_id);

                update_mycred_log_ctype($id, 'convert');

                send_pv_to_mlm($user_id, $points);
            }
        }
     }
}

function add_point_external_from_pending($user_id, $payout, $website){
    $point_type = 'point_external_website';
    $log = "Given {$payout} from convert poin external pending from user {$user_id} member of {$website}";
    $reference = "convert_point_external_pending";
    mycred_add(
        $reference,
        $user_id,
        $payout,
        $log,
        '',
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

function substract_point_external_from_crone($id,$user_id, $payout, $website, $ref_id){
    $point_type = "point_external_pending";
    $log = "Convert {$payout} to Point External from user {$user_id} member of {$website}";
    $reference = "convert_point_to_external";
    mycred_add(
        $reference,
        $user_id,
        -$payout,
        $log,
        $ref_id,
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

function substract_point_seller_internal_via_crone($user_id, $payout, $website, $ref_id){
    $point_type = "point_seller_internal";
    $log = "Debit {$payout} from Point External Pending form user {$user_id} member of {$website}";
    $reference = "debit_quota_from_pending";
    mycred_add(
        $reference,
        $user_id,
        -$payout,
        $log,
        $ref_id,
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

function update_mycred_log_ctype($id, $ref) {
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

function send_pv_to_mlm($user_id, $point){
    $api_url = get_option('shopbiz_api_url') . '/user/update-pv';
    $api_data = array(
      'pv' => $point,
      'bv' => $point,
      'action' => 'add',
      'username' => get_userdata($user_id)->user_login
    );

    $api_data_json = json_encode($api_data);

    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'secret_key' => get_option('shopbiz_api_token'),
            'prefix' => '1119',
        ),
        'body' => $api_data_json, 
        'method' => 'POST',
    )
    );

    if (is_wp_error($response)) {
      error_log('API call failed: ' . $response->get_error_message());
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      $response_body = wp_remote_retrieve_body($response);
      if (200 !== $response_code) {
        error_log('API call returned an error: ' . $response_body);
      } else {
        $response_body = json_decode($response_body, true);
        print_r($response_body); 
      }
    }
}

function getDynamicTimestamps($timezone = 'Asia/Jakarta') {
	$now = new DateTime('now'); 
	$start_date = new DateTime($now->format('Y-m-d 00:00:00'));
    $end_date = new DateTime($now->format('Y-m-d 23:59:59'));
    return [
        'start_date' => $start_date->getTimestamp(),
        'end_date' => $end_date->getTimestamp(),
    ];
}

function add_point_upgrade($user_id, $payout, $website, $ref_id){
    $point_type = "point_upgrade";
    $log = "Given {$payout} from convert poin external from user {$user_id} member of {$website}";
    $reference = "convert point";
    mycred_add(
        $reference,
        $user_id,
        $payout,
        $log,
        $ref_id,
        array( 'ref_type' => 'post' ),
        $point_type
    );
}

