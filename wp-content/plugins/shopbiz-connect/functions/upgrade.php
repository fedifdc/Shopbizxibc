<?php
if (!defined('ABSPATH')) {
  exit;
}


function formatMobileNumber($mobileNumber) {
    // Remove spaces from the mobile number
    $formattedNumber = str_replace(' ', '', $mobileNumber);
    
    // Optionally, you can also handle other characters if needed, such as dashes or parentheses
    $formattedNumber = preg_replace('/[^\d+]/', '', $formattedNumber); // Keep only digits and plus sign
    
    return $formattedNumber;
}


// Handle AJAX post request
add_action('wp_ajax_shopbiz_upgrade', 'shopbiz_upgrade');
add_action('wp_ajax_nopriv_shopbiz_upgrade', 'shopbiz_upgrade');

function shopbiz_upgrade()
{
  $nonce = $_POST['nonce'];

  // Verify nonce
  if (!wp_verify_nonce($nonce, 'shopbiz-upgrade-nonce')) {
    die('Nonce value cannot be verified.');
  }

  global $wpdb;
  $userdata = wp_get_current_user();
  
    // Query data dari database
    $result = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM wp_member WHERE idwp = %d",
            $userdata->ID
        )
    );

  // Fetch form data
  $nik = sanitize_text_field($_POST['nik']);
  $alamat = sanitize_textarea_field($_POST['alamat_1']);
  $state = sanitize_textarea_field($_POST['alamat_2']);
  $city = sanitize_textarea_field($_POST['alamat_3']);

  $nama_bank = sanitize_text_field($_POST['nama_bank']);
  $owner_name = sanitize_text_field($_POST['owner_name']);
  $no_rek = sanitize_text_field($_POST['no_rek']);
  $password_transaksi = sanitize_text_field($_POST['password_transaksi']);
  $local_time = sanitize_text_field($_POST['local_time']);
  $foto_ktp_url = sanitize_text_field($_POST['foto_ktp']); // The URL of the uploaded image
  update_user_meta($userdata->ID,'foto_ktp_url',$foto_ktp_url);


  // Query user data from database
  $table_reg = $wpdb->prefix . 'shopbiz_registration';
  $query_reg = $wpdb->prepare("SELECT * FROM $table_reg WHERE customer_id = %d", $userdata->ID);
  $reg_data = $wpdb->get_row($query_reg);
  $phone = get_user_meta($userdata->ID, 'billing_phone', true);

  $table_referral = $wpdb->prefix . 'member';
  $query_referral = $wpdb->prepare("SELECT * FROM $table_referral WHERE idwp = %d", $userdata->ID);
  $referral_data = $wpdb->get_row($query_referral);
  $uplines = unserialize($referral_data->homepage)['uplines'];
  $uplines_array = explode(',', $uplines);
  $referral_id = null;

  foreach ($uplines_array as $upline) {
    $referral_id = get_user_meta($upline, 'shopbiz_user_id', true);
    if ($referral_id) {
      break;
    }
  }
  if (!$referral_id) {
    $referral_id = 1;
  }
  $point_level =intval(get_user_meta($userdata->ID, 'point_level', true));
  $updrade_level =intval(get_user_meta($userdata->ID, 'point_upgrade', true));
  // Trigger API call on registration
  $api_url = get_option('shopbiz_api_url') . '/register';
  $api_data = array(
    'email' => $userdata->user_email,
    'first_name' => $userdata->first_name,
	'last_name' => $userdata->last_name,
    'gender' => 'M',
    'mobile' => formatMobileNumber($phone),
    'password' => $reg_data->password,
    'placement' => '',
    'position' => '',
    'pv' => $point_level,
    'bv' => $updrade_level,
    'referralId' => $referral_id && !empty($referral_id) ? $referral_id : 1,
    'username' => $userdata->user_login,
    'ecomCustomerRefId' => $userdata->ID,
    'nik' => $nik,
    "bank_name" => $nama_bank,
	"bank_account_name" => $owner_name,
    "bank_account_number" => $no_rek,
	'transaction_password' => $password_transaksi,
	 'address' => $alamat,
	 'state' => $state,
	 'city' => $city
    
  );

  // Make the API call
//   $response = wp_remote_post($api_url, array(
//     'body' => $api_data,
//     'headers' => array(
//       'api-key' => get_option('shopbiz_api_token'),
//     )
//   ));
//   
	$api_data_json = json_encode($api_data);

	$response = wp_remote_post($api_url, array(
		'headers' => array(
			'Content-Type' => 'application/json', // Specify JSON content type
			'secret_key' => get_option('shopbiz_api_token'), // Custom header
			'prefix' => '1119', // Additional custom header
		),
		'body' => $api_data_json, // JSON data
		'method' => 'POST', // HTTP POST method
	));

  // Handle the API response
  if (is_wp_error($response)) {
    error_log('API call failed: ' . $response->get_error_message());
    $response = array(
      'success' => false,
      'message' => 'Upgrade failed.',
    );
    wp_send_json($response);
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
	 $data = json_decode($response_body);
	  
	if($response_code == 422) {
		$response = array(
        'success' => false,
        'message' => $data->message,
      );
		
      wp_send_json($response);
	}
    if ($response_code != 200 && $response_code != 201) {
      error_log('API call returned an error: ' . $response_body);
      $response = array(
        'success' => false,
        'message' => 'Poin tidak mencukupi untuk upgrade',
		'error' => $response_body,
		'body' => $api_data,
      );
		
      wp_send_json($response);
    } else {
     // Decode the response body from previous API call
		//$response_body = json_decode($response_body, true);
		update_user_meta($userdata->ID, 'sync_mlm_status', 0);

		// Get the file information
// 		$file_name = $_FILES['file']['name'];
// 		$file_tmp = $_FILES['file']['tmp_name'];
// 		$file_type = $_FILES['file']['type'];

// 		// Prepare the URL
// 		$url = 'https://backoffice.shopbiz.id/node_api/kyc-upload-direct?category=1&type=kyc&user_id=' . esc_attr($response_body['id']);
 
// 		// Generate a unique boundary
// 		$boundary = wp_generate_password(24, false);

// 		// Prepare the headers
// 		$headers = array(
// 			'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
// 			'api-key' => "387245a5a919ea2071cc406b10b89d4685e5cc8e"
// 		);

// 		// Prepare the payload
// 		$payload = '';

// 		// Add standard POST fields
// 		$post_fields = array(
// 			'name' => 'value' // Add other fields if needed
// 		);
// 		foreach ($post_fields as $name => $value) {
// 			$payload .= '--' . $boundary . "\r\n";
// 			$payload .= 'Content-Disposition: form-data; name="' . $name . '"' . "\r\n\r\n";
// 			$payload .= $value . "\r\n";
// 		}

// 		// Add the file upload
// 		if ($file_tmp) {
// 			$payload .= '--' . $boundary . "\r\n";
// 			$payload .= 'Content-Disposition: form-data; name="file"; filename="' . $file_name . '"' . "\r\n";
// 			$payload .= 'Content-Type: ' . $file_type . "\r\n"; // Add appropriate MIME type for the file
// 			$payload .= "\r\n";
// 			$payload .= file_get_contents($file_tmp) . "\r\n";
// 		}

// 		// End the payload
// 		$payload .= '--' . $boundary . '--';

// 		// Send the POST request
// 		$upload = wp_remote_post($url, array(
// 			'headers' => $headers,
// 			'body'    => $payload,
// 			'method'  => 'POST'
// 		));

// 	  $body = wp_remote_retrieve_body($upload);
      $response = array(
        'success' => true,
        'message' => 'Proses Pendaftaran dalam antrian, harap perikas secara berkala',
		//'body' => $upload
      );
		
	  // 	  UPGRADE MEMBER AFFILIATE
	  $table_name = $wpdb->prefix . 'member'; // 'wp_member' table

// //       $updated = $wpdb->update(
// //             $table_name, 
// //             array('membership' => 2), 
// //             array('idwp' => $userdata->ID) 
// //        );
// 		$wpdb->query($wpdb->prepare(
// 			"UPDATE $table_name SET membership = 2 WHERE idwp = %d", $userdata->ID
// 			));
	  
// 		$update_user_register_date = $wpdb->query(
//         $wpdb->prepare(
//             "UPDATE $table_reg 
//             SET registered_at = NOW() 
//             WHERE customer_id = %d",
//             $userdata->ID
//         )
//     );
	
      wp_send_json($response);
    }
  }
}
