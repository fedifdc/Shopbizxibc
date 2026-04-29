<?php
/*
Service Name: Fonnte
Service URL: https://fonnte.com
API Documentation: https://fonnte.com
Field API Token: fonnte_token
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
$curl = curl_init();
$token = $wafusetting['fonnte_token'];
$data = [
    'phone' => $nohp,
    'type' => 'text',
    'delay' => 0,
    'delay_req' => 0,
    'text' => stripslashes($pesan)
];

curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: ".$token,
    )
);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_URL, "https://hp.fonnte.com/api/send_message.php");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$return = curl_exec($curl);
curl_close($curl);
