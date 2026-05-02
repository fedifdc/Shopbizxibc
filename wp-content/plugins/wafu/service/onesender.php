<?php
/*
Service Name: OneSender
Service URL: https://onesender.id
API Documentation: https://onesender.id/docs/
Field API Server: onesender_server
Field API ID: onesender_id
Field API Key: onesender_key
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; }

# Handle Send Message

if (isset($wafusetting['onesender_server']) && $wafusetting['onesender_server'] != '') {

	$requestParam  = [
		'phone'     => $nohp,
		'message'   => stripslashes($pesan),
		'type'      => 'text',
	];

	if ($gambar != '') {
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['basedir'].'/pic/'.$gambar;

		if (function_exists('curl_file_create')) { // php 5.5+
			$requestParam['attachment'] = curl_file_create($gambar);
		} else { // 
			$requestParam['attachment'] = '@' . realpath($gambar);
		}

		$requestParam['type'] = 'image';
	}

	$url = trim($wafusetting['onesender_server']);
	$headers = array(
		'Authorization: Bearer ' . trim($wafusetting['onesender_key'])
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $requestParam);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
	$response = curl_exec($ch);
	curl_close($ch);


	$array_response = json_decode( $response, true );

	if (json_last_error() == JSON_ERROR_NONE) {
	   $return = "Berhasil";
	} else {
		$return = "Gagal kirim pesan";
	}

}
?>