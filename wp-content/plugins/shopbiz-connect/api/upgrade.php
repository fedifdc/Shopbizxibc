<?php

class Upgrade {

    public function sync_register_process() {
        add_action('rest_api_init', function() {
            // Register order endpoint
            register_rest_route('shopbiz/v1', '/sync-mlm-account/', array(
                'methods' => 'POST',
                'callback' => array($this, 'sync_register_account'),
                #'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function sync_register_account($request) {
        //point_external
        global $wpdb;
        global $post;
        //get param
        $body = json_decode(stripslashes($request->get_body()), true);
        $user_mlm_id = $body['user_mlm_id'];
        $user_shopbiz_id = $body['user_shopbiz_id'];
        $message = $body['message'];
        $status = $body['status'];
        if($status == 1 || $user_mlm_id > 1 ){
            //masukan ke mlm 
            update_user_meta( $user_shopbiz_id,'sync_mlm_status', 1);
            update_user_meta( $user_shopbiz_id,'shopbiz_user_id',$user_mlm_id); 
            
             // 	  UPGRADE MEMBER AFFILIATE
            $table_name = $wpdb->prefix . 'member'; // 'wp_member' table
        
            $wpdb->query($wpdb->prepare(
                "UPDATE $table_name SET membership = 2 WHERE idwp = %d", $user_shopbiz_id
                ));
            
            $update_user_register_date = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table_reg 
                    SET registered_at = NOW() 
                    WHERE customer_id = %d",
                    $user_shopbiz_id
                )
            );
            
            $member_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT member_id FROM wafu_member WHERE member_idwp = %d",
                    $user_shopbiz_id
                )
            );
            
            $wpdb->query(
                $wpdb->prepare(
                    "update wafu_gruptomember SET grup_id = 2, gtm_nextid=2, gtm_bc=1 where member_id = %d",
                    $member_id
                    )
            );
            
            
            // Get the file information
            $file_url = get_user_meta( $user_shopbiz_id, 'foto_ktp_url', true);
            if($file_url == null){
                return new WP_REST_Response( array(
                'status' => true,
                'message' => "Berhasil Sync account  dengan account shopbiz.id"
            ), 200);
            }
            $file_name = basename($file_url);
		    $file_type = 'image/jpeg';//mime_content_type($file_url); // Tipe MIME file
         
    
            // Prepare the URL
            $mlm_url = get_option('shopbiz_api_mlm_url');
            if (empty($mlm_url)) {
                $mlm_url = get_option('shopbiz_api_url');
            }
            $url = rtrim($mlm_url, '/') . '/node_api/kyc-upload-direct?category=1&type=kyc&user_id=' . esc_attr($user_mlm_id);
     
            // Generate a unique boundary
            $boundary = wp_generate_password(24, false);
    
            // Prepare the headers
            $api_key = get_option('shopbiz_api_mlm_api_key');
            if (empty($api_key)) {
                $api_key = get_option('shopbiz_api_token');
            }
            $headers = array(
                'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
                'api-key' => $api_key
            );
    
            // Prepare the payload
            $payload = '';
    
            // Add standard POST fields
            $post_fields = array(
                'name' => 'value' // Add other fields if needed
            );
           $payload = '';
			// First, add the standard POST fields:
			foreach ( $post_fields as $name => $value ) {
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name .
					'"' . "\r\n\r\n";
				$payload .= $value;
				$payload .= "\r\n";
			}

            // Add the file upload
            //if ($file_url) {
                $payload .= '--' . $boundary . "\r\n";
                $payload .= 'Content-Disposition: form-data; name="file"; filename="' . $file_name . '"' . "\r\n";
                $payload .= 'Content-Type: ' . $file_type . "\r\n"; // Add appropriate MIME type for the file
                $payload .= "\r\n";
                $payload .= file_get_contents($file_url) . "\r\n";
            //}
    
            // End the payload
            $payload .= '--' . $boundary . '--';
   
            // Send the POST request
            $upload = wp_remote_post($url, array(
                'headers' => $headers,
                'body'    => $payload,
                'method'  => 'POST',
                'sslverify'  => false 
            ));
    
          $body = wp_remote_retrieve_body($upload);
        
        $response = array(
            'success' => true,
            'message' => 'Selamat Anda telah terdaftar di ShopBiz.
              Silahkan <a href="https://affiliasi.virans.com/" target="_blank">login ke aplikasi ShopBiz</a>
              menggunakan username dan password yang sama dengan akun ini.',
             'body' => $upload
          );
            return new WP_REST_Response( array(
                'status' => true,
                'message' => "Berhasil Sync account  dengan account shopbiz.id"
            ), 200);
        } else {
            // Masukkan ke input poin level
            update_user_meta( $user_shopbiz_id,'sync_mlm_status', 2);
			if ($message == "No package found for pv value: 0"){
				$message = "Poin Level anda belum mencukupi";
			}
			update_user_meta( $user_shopbiz_id,'sync_mlm_status', 2);
            update_user_meta( $user_shopbiz_id,'sync_error_message', $message);
            return new WP_REST_Response( array(
                'status' => false,
            ), 500);
        }
        
    }  
}