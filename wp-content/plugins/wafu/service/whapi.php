<?php
/*
Service Name: WHAPI
Service URL: Service URL: https://whapi.io/
API Documentation: https://whapi.io/home/documentation
Field API ID: whapi_id
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($wafusetting['whapi_id']) && $wafusetting['whapi_id'] != '') {
	if (isset($gambar) && $gambar != '') {
		$type = 'image';
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
		$message = array(
	      0 => array (
	            'time' => time(),
	            'type' => 'image',
	            'value' => $gambar
	      ),	      
	      1 => array (
	            'time' => time(),
	            'type' => 'text',
	            'value' => $pesan
	      )	    
	    );
	} else {
		$message = array(
	      0 => array (
	            'time' => time(),
	            'type' => 'text',
	            'value' => $pesan
	      )
	    );
	}
	$data = array (
		'app' => array (
		  'id' => $wafusetting['whapi_id'],
		  'time' => time(),
		  'data' => array (
		    'recipient' => array ('id' => wafu_format($nohp))
		  )
		)
	);

	$data['app']['data']['message'] = $message;

	$datawa = json_encode($data);

	$ch = curl_init('https://whapi.io/api/send');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $datawa);
	$return = curl_exec($ch);
	curl_close($ch);
}
?>