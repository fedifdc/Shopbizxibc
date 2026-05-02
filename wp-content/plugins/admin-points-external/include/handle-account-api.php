<?php
require __DIR__ . '/handle-api-request-class.php';


class ShopbizAccountPartnerAPI extends HandleAPIRequest{

    public function register_endpoints() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/quota/', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_quota_data'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function get_quota_data($request) {
        $user_id = $this->get_user_id($request);
        $quota = get_user_meta($user_id, 'point_seller_internal', true);
        return new WP_REST_Response( array(
            'status' => 'success',
            'quota' => intval($quota)
        ), 200);
    }

    public function sync_account_partnert_endpoints() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/sync-account/', array(
                'methods' => 'POST',
                'callback' => array($this, 'sync_user_account'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function sync_user_account($request) {
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);
        $email = html_entity_decode($body['shopbiz_email']);
        $user_partner_id = html_entity_decode($body['user_partner_id']);
        $website = html_entity_decode($request->get_header('website'));
       
        $user = get_user_by_email($email);
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        
        $checkSync = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d AND user_partner_id = %d AND website = %s",
            $user->ID,
            $user_partner_id,
            $website
        ));
        
        if($checkSync){
            $wpdb->update($table_name, 
            array(
                'user_id'            => $user->id,
            ), array(
                'user_partner_id'    => $user_partner_id,
                'website'            => $website,
            )
            );

            return new WP_REST_Response( array(
                'status' => 'success',
                'message' => "Berhasil update Sync account {$website} dengan account shopbiz.id"
            ), 200);
        }
        if(!$user){
            return new WP_REST_Response( array(
                'status' => 'fail',
                'message' => "email {$email}  dan {$user} tidak terdaftar di shopbiz.id"
            ), 433);
        }
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
       
        $wpdb->insert($table_name, array(
            'user_id'            => $user->id,
            'user_partner_id'    => $user_partner_id,
            'website'            => $website,
            'is_sync'            => 1
        ));
        return new WP_REST_Response( array(
            'status' => 'success',
            'message' => "Berhasil Sync account {$website} dengan account shopbiz.id"
        ), 200);
    }
    
    public function test_sync_endpoint() {
            add_action('rest_api_init', function() {
                register_rest_route('shopbiz/v1', '/submit-sync/', array(
                    'methods' => 'GET',
                    'callback' => array($this, 'test_sync'),
                    'permission_callback' => array($this, 'authenticate_request'),
                ));
            });
    }

    public function test_sync($request) {
        $user_id = $this->get_user_id($request);
        $website = "https://".html_entity_decode($request->get_header('website'));
        $sync_data = array(
            'sync' => true,
            'website' => $website
        );
        update_user_meta($user_id, 'sync_data_partner', serialize($sync_data));
        return new WP_REST_Response(array(
            'status' => 'success',
            'message' => "API key is valid and sync endpoint is working"
        ), 200);
    }

    public function commission_endpoint() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/send-commission/', array(
                'methods' => 'POST',
                'callback' => array($this, 'send_commission'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function send_commission($request) {
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);

        $order_id = html_entity_decode($body['order_id']);
        $coupon_code = html_entity_decode($body['coupon_code']);
        $order_total = floatval($body['order_total']);
        $komisi_persen = floatval($body['komisi_persen']);
        $expected_komisi = floatval($body['komisi_seharusnya']);
        $actual_discount = floatval($body['diskon_terpakai']);
        $selisih = floatval($body['komisi']);
        $coupon_owner_id = intval($body['coupon_owner_id']);
        $order_user_id = intval($body['order_user_id']);
        $order_user_data = $body['order_user_data'];
        $website = html_entity_decode($request->get_header('website'));

        $user_data = get_userdata($order_user_id);

        $payload = [
            'order_id'          => $order_id,
            'coupon_code'       => $coupon_code,
            'order_total'       => $order_total,
            'komisi_persen'     => $komisi_persen,
            'komisi_seharusnya' => $expected_komisi,
            'diskon_terpakai'   => $actual_discount,
            'komisi'            => $selisih,
            'coupon_owner_id'   => $coupon_owner_id,
            'user_email'        => $user_data ? $user_data->user_email : '',
            'website'           => $website,
            'user_partner_id'   => $coupon_owner_id,
            'order_user_id'     => $order_user_id,
            'order_user_data'   => $order_user_data
        ];

        array(
            'order_id'          => $payload['order_id'],
            'coupon_code'       => $payload['coupon_code'],
            'order_total'       => $payload['order_total'],
            'komisi_persen'     => $payload['komisi_persen'],
            'komisi_seharusnya' => $payload['komisi_seharusnya'],
            'diskon_terpakai'   => $payload['diskon_terpakai'],
            'komisi'            => $payload['komisi'],
            'coupon_owner_id'   => $payload['coupon_owner_id'],
            'user_email'        => $payload['user_email'],
            'website'           => $payload['website'],
            'user_partner_id'   => $payload['user_partner_id'],
            'order_user_id'     => $payload['order_user_id'],
            'order_user_data'   => $payload['order_user_data'],
            'created_at'        => current_time('mysql')
        );

        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        $user_sync_data = $wpdb->get_row($wpdb->prepare(
            "SELECT user_id FROM $table_name WHERE user_partner_id = %d AND website = %s",
            $payload['coupon_owner_id'],
            $payload['website']
        ));

        if ($user_sync_data) {
            $payload['coupon_owner_id'] = $user_sync_data->user_id;
        } else {
            return new WP_REST_Response(array(
                'status' => 'fail',
                'message' => "User with partner ID {$payload['user_partner_id']} and website {$payload['website']} not found in sync table"
            ), 404);
        }

        // Retrieve id_user and id_referral (id_sponsor) from wp_member table using coupon_owner_id
        $member_data = $wpdb->get_row($wpdb->prepare(
            "SELECT id_user, id_referral FROM wp_member WHERE idwp = %d",
            $payload['coupon_owner_id']
        ));

        if (!$member_data) {
            return new WP_REST_Response(array(
            'status' => 'fail',
            'message' => "User with coupon_owner_id {$payload['coupon_owner_id']} not found in wp_member table"
            ), 404);
        }

        $id_user = $member_data->id_user;
        $id_sponsor = $member_data->id_referral;
        // $transaksi = 'Komisi dari coupon dari order Produk Order No. '.$payload['order_id']. ' dari website ' . $payload['website'] . ' dari user ' . $payload['order_user_id']['user_email']. ' dengan kode kupon ' . $payload['coupon_code']. 'untuk user ' . $payload['user_email'];
       
        // $wpdb->query("INSERT INTO `cb_laporan` (`tanggal`,`transaksi`,`kredit`,`komisi`,`keterangan`,`id_user`,`id_sponsor`,`id_order`) VALUES (NOW(),'$transaksi','$kredit','$payload['komisi']','woo','$id_user','$id_referral','$order_id')");
        
        // insert_commission_log($id_user, 'wp_affiliate', 'Komisi Affiliasi', 'Affiliasi Komisi Order Completed  #'. $order->get_id() .' from '. $id_user , $jmlkomisi, 'uang', 'success');
       
        $wpdb->query("UPDATE `wp_member` SET `sisa_voucher`=`sisa_voucher`+".$payload['komisi']." WHERE `idwp`=".$payload['coupon_owner_id']);
		
		// Ambil data member	
		$checkupline = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp` = ".$payload['coupon_owner_id']);
		
		// Masukkan laporan keuangan 
		$id_order = time();
		$transaksi = 'Komisi dari website '.$payload['website'].' dari voucher' .$payload['coupon_code']. 'untuk '.$checkupline->nama;
		$wpdb->query("INSERT INTO `cb_laporan` 
					(`tanggal`,`transaksi`,`debet`,`kredit`,`komisi`,`keterangan`,`id_sponsor`,`id_user`,`id_order`)
					VALUES  ('".wp_date('Y-m-d H:i:s')."','".$transaksi."',0,".$payload['komisi'].",".$payload['komisi'].",'kvcr',".$id_user.",".$payload['coupon_owner_id'].",".$payload['order_id'].")");
		
		$datalain = array(
			'komisi' => $payload['komisi']
		);

        insert_commission_log($payload['coupon_owner_id'], 'wp_affiliate', 'Komisi Affiliasi','komisi coupon', $payload['komisi'], 'uang', 'success');
        return new WP_REST_Response(array(
            'status' => 'success',
            'message' => "Commission successfully recorded for order {$order_id} on website {$website}"
        ), 200);
    }

    public function cancel_commission_endpoint() {
        add_action('rest_api_init', function() {
            register_rest_route('shopbiz/v1', '/cancel-send-commission/', array(
                'methods' => 'POST',
                'callback' => array($this, 'cancel_send_commission'),
                'permission_callback' => array($this, 'authenticate_request'),
            ));
        });
    }

    public function cancel_send_commission($request) {
        global $wpdb;
        $body = json_decode(stripslashes($request->get_body()), true);

        $order_id = html_entity_decode($body['order_id']);
        $coupon_code = html_entity_decode($body['coupon_code']);
        $order_total = floatval($body['order_total']);
        $komisi_persen = floatval($body['komisi_persen']);
        $expected_komisi = floatval($body['komisi_seharusnya']);
        $actual_discount = floatval($body['diskon_terpakai']);
        $selisih = floatval($body['komisi']);
        $coupon_owner_id = intval($body['coupon_owner_id']);
        $order_user_id = intval($body['order_user_id']);
        $order_user_data = $body['order_user_data'];
        $website = html_entity_decode($request->get_header('website'));

        $user_data = get_userdata($order_user_id);

        $payload = [
            'order_id'          => $order_id,
            'coupon_code'       => $coupon_code,
            'order_total'       => $order_total,
            'komisi_persen'     => $komisi_persen,
            'komisi_seharusnya' => $expected_komisi,
            'diskon_terpakai'   => $actual_discount,
            'komisi'            => $selisih,
            'coupon_owner_id'   => $coupon_owner_id,
            'user_email'        => $user_data ? $user_data->user_email : '',
            'website'           => $website,
            'user_partner_id'   => $coupon_owner_id,
            'order_user_id'     => $order_user_id,
            'order_user_data'   => $order_user_data
        ];

        array(
            'order_id'          => $payload['order_id'],
            'coupon_code'       => $payload['coupon_code'],
            'order_total'       => $payload['order_total'],
            'komisi_persen'     => $payload['komisi_persen'],
            'komisi_seharusnya' => $payload['komisi_seharusnya'],
            'diskon_terpakai'   => $payload['diskon_terpakai'],
            'komisi'            => $payload['komisi'],
            'coupon_owner_id'   => $payload['coupon_owner_id'],
            'user_email'        => $payload['user_email'],
            'website'           => $payload['website'],
            'user_partner_id'   => $payload['user_partner_id'],
            'order_user_id'     => $payload['order_user_id'],
            'order_user_data'   => $payload['order_user_data'],
            'created_at'        => current_time('mysql')
        );

        $table_name = $wpdb->prefix . 'shopbiz_user_sync';
        $user_sync_data = $wpdb->get_row($wpdb->prepare(
            "SELECT user_id FROM $table_name WHERE user_partner_id = %d AND website = %s",
            $payload['coupon_owner_id'],
            $payload['website']
        ));

        if ($user_sync_data) {
            $payload['coupon_owner_id'] = $user_sync_data->user_id;
        } else {
            return new WP_REST_Response(array(
                'status' => 'fail',
                'message' => "User with partner ID {$payload['user_partner_id']} and website {$payload['website']} not found in sync table"
            ), 404);
        }

        // Retrieve id_user and id_referral (id_sponsor) from wp_member table using coupon_owner_id
        $member_data = $wpdb->get_row($wpdb->prepare(
            "SELECT id_user, id_referral FROM wp_member WHERE idwp = %d",
            $payload['coupon_owner_id']
        ));

        if (!$member_data) {
            return new WP_REST_Response(array(
            'status' => 'fail',
            'message' => "User with coupon_owner_id {$payload['coupon_owner_id']} not found in wp_member table"
            ), 404);
        }

        $id_user = $member_data->id_user;
        $id_sponsor = $member_data->id_referral;
        // $transaksi = 'Komisi dari coupon dari order Produk Order No. '.$payload['order_id']. ' dari website ' . $payload['website'] . ' dari user ' . $payload['order_user_id']['user_email']. ' dengan kode kupon ' . $payload['coupon_code']. 'untuk user ' . $payload['user_email'];
       
        // $wpdb->query("INSERT INTO `cb_laporan` (`tanggal`,`transaksi`,`kredit`,`komisi`,`keterangan`,`id_user`,`id_sponsor`,`id_order`) VALUES (NOW(),'$transaksi','$kredit','$payload['komisi']','woo','$id_user','$id_referral','$order_id')");
        
        // insert_commission_log($id_user, 'wp_affiliate', 'Komisi Affiliasi', 'Affiliasi Komisi Order Completed  #'. $order->get_id() .' from '. $id_user , $jmlkomisi, 'uang', 'success');
       
        $wpdb->query("UPDATE `wp_member` SET `sisa_voucher`=`sisa_voucher`-".$payload['komisi']." WHERE `idwp`=".$payload['coupon_owner_id']);
		
		// Ambil data member	
		$checkupline = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp` = ".$payload['coupon_owner_id']);
		
		// Masukkan laporan keuangan 
		$id_order = time();
        $id_user = $payload['coupon_owner_id'];
		$transaksi = 'Komisi dari website '.$payload['website'].' dari voucher' .$payload['coupon_code']. 'untuk '.$checkupline->nama;
		$wpdb->query("INSERT INTO `cb_laporan` 
					(`tanggal`,`transaksi`,`debet`,`kredit`,`komisi`,`keterangan`,`id_sponsor`,`id_user`,`id_order`)
					VALUES  ('".wp_date('Y-m-d H:i:s')."','".$transaksi. $payload['komisi']."',0,". $payload['komisi'].",'kvcr',".$id_user.",".$payload['coupon_owner_id'].",".$payload['order_id'].")");
		
		$datalain = array(
			'komisi' => $payload['komisi']
		);

        insert_commission_log($payload['coupon_owner_id'], 'wp_affiliate', 'Komisi Affiliasi','komisi coupon', -1 * $payload['komisi'], 'uang', 'success');
        return new WP_REST_Response(array(
            'status' => 'success',
            'message' => "Commission cancel for recorded for order {$order_id} on website {$website}"
        ), 200);
    }

}
