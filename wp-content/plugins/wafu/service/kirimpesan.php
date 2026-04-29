<?php
/*
Service Name: Kirim Pesan
Service URL: https://kirimpesan.ristekmuslim.com
API Documentation: https://documenter.getpostman.com/view/4847271/SVtN5CQd
Field API WA Login: kp_wa
Field API Password: kp_pass
Field API Instance ID: kp_id
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (!isset($wafusetting['kp_token'])) {	
	function wafu_getdata($content,$start,$end){
	    $r = explode($start, $content);
	    if (isset($r[1])){
	        $r = explode($end, $r[1]);
	        return $r[0];
	    }
	    return '';
	}
	
	$param = array(
		'uid' => $wafusetting['kp_wa'],
	  	'pass' => $wafusetting['kp_pass']
	);
	$data_json = json_encode($param);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://apiks.ristekmuslim.com/public/v1/client/login');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array (
	  'Content-Type: application/json; charset=utf-8'));
	$response = curl_exec($ch);
	curl_close($ch);
	$token = wafu_getdata($response,'{"token":"','"');
	$expired = wafu_getdata($response,'{"expire":','}');

	if ($token != '') { 
		$wafusetting['kp_token'] = $token; 
		update_option('wafusetting',$wafusetting);
	} else {
		$response = '<div class="notice notice-error is-dismissible"><p>Gagal mendapatkan token kirimpesan. Silahkan periksa kembali data anda</p></div>';
	}	
} else {
	$token = $wafusetting['kp_token'];
}

if (isset($token) && $token != '') {
	$param = array(
		'instanceID' => $wafusetting['kp_id'],
		'phone' => $nohp,
		'message' => stripslashes($pesan)
	);

	$data_json = json_encode($param);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://apiks.ristekmuslim.com/client/v1/message/send-text');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json; charset=utf-8',
	'Authorization: Bearer '.$token)); //os_auth
	$return = curl_exec($ch);
	curl_close($ch);
} else {
	$return = 'Token belum ada';
}