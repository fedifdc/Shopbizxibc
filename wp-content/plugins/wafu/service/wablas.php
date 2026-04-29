<?php
/*
Service Name: WA Blas
Service URL: https://wablas.com/
API Documentation: https://kudus.wablas.com/doc-api
Field API Domain API: wablas_api
Field API Token: wablas_token
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($wafusetting['wablas_token']) && $wafusetting['wablas_token'] != '') {
	$curl = curl_init();
	$token = $wafusetting['wablas_token'];
	if (isset($gambar) && $gambar != '') {
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
		$payload = [
		    "data" => [
		        [
		            'phone' => $nohp,
		            'caption' => stripslashes($pesan),
		            'image' => $gambar
		        ]
		    ]
		];

		$url = $wafusetting['wablas_api']."/api/v2/send-image";
	} else {
		$payload = [
	    "data" => [
		        [
		            'phone' => $nohp,
		            'message' => stripslashes($pesan),
		        ]
		    ]
		];
		$url = $wafusetting['wablas_api']."/api/v2/send-message";
	}
	
	curl_setopt($curl, CURLOPT_HTTPHEADER,
	    array(
	        "Authorization: $token",
	        "Content-Type: application/json"
	    )
	);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	$return = curl_exec($curl);
	curl_close($curl);
}
?>