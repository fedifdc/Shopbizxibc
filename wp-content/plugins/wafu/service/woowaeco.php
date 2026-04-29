<?php
/*
Service Name: Woowa Server
Service URL: https://woo-wa.com/
API Documentation: http://api.woo-wa.com/
Field API Key: woowa_api
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
$key = $wafusetting['woowa_api'];
$url ='http://116.203.191.58/api/send_message';
$data = array(
  "phone_no"=> $nohp,
  "key"		=> $key,
  "message"	=> $pesan
);
$data_string = json_encode($data);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 360);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Content-Length: ' . strlen($data_string))
);
$return = curl_exec($ch);
curl_close($ch);