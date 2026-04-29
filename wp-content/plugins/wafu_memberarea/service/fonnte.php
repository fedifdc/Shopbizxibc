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
    'target' => $nohp.'|Fonnte|Admin',
    'message' =>  stripslashes($pesan),
    //'url' => 'https://md.fonnte.com/images/wa-logo.png',
    //'filename' => 'filename',
    'schedule' => 0,
    'typing' => false,
    'delay' => '0',
    'countryCode' => '62',
    //'file' => new CURLFile("localfile.jpg"),
    //'location' => '-7.983908, 112.621391',
    'followup' => 0,
];

curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: ".$token,
    )
);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_URL, "https://api.fonnte.com/send");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
$return = curl_exec($curl);
curl_close($curl);