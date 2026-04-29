<?php
/*
Service Name: Whacenter
Service URL: https://whacenter.com/
API Documentation: https://order.whacenter.com/docs/
Field API Device ID: whacenter_device_id
*/
# Handle Kirim Pesan

//date_default_timezone_set("Asia/Jakarta");
//$time = date("Y-m-d H:i");

if (isset($wafusetting['whacenter_device_id']) && $wafusetting['whacenter_device_id'] != '' && isset($nohp) && isset($pesan)) {
	if (isset($gambar) && $gambar != '') {
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
	}

	$url = 'https://app.whacenter.com/api/send';

	$ch = curl_init($url);

	$device_id = $wafusetting['whacenter_device_id'];

	$data = array(
	    'device_id' => $device_id,
	    'number' => $nohp,
	    'message' => $pesan,
	    'file' => $gambar,
	    //'schedule' => $time
	   
	);
	$payload = $data;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	

	$return = curl_exec($ch);

	curl_close($ch);
}
