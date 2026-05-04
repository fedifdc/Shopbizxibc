<?php
/*
Service Name: Dripsender
Service URL: https://dripsender.id/
API Documentation: https://docs.dripsender.id/
Field API Key: drip_key
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($wafusetting['drip_key']) && $wafusetting['drip_key'] != '') {
	$api_key = trim($wafusetting['drip_key']);
	
	$message = array(
                'api_key'	=> $api_key,
                'phone'		=> $nohp,
                'text' 		=> stripslashes($pesan)
            );
	if (isset($gambar) && $gambar != '') {
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
		$message['media_url'] = $gambar;
	} 

	$message = json_encode($message);
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'http://api.dripsender.id/send',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $message,
	  CURLOPT_HTTPHEADER => array('Content-Type: application/json')
	));

	$return = curl_exec($curl);
	curl_close($curl);
}