<?php
/*
Service Name: Ruang WA v3
Service URL: https://ruangwa.id/
API Documentation: https://documenter.getpostman.com/view/6375136/TzeUp9av
Field API Token: ruangwa_token
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($wafusetting['ruangwa_token']) && $wafusetting['ruangwa_token'] != '') {
  $token = $wafusetting['ruangwa_token'];
  if (isset($gambar) && $gambar != '') {
    $upload_dir = wp_upload_dir();
    $gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
    $url = 'https://app.ruangwa.id/api/send_image';
    $postfield = 'token='.$token.'&number='.$nohp.'&file='.$gambar.'&caption='.stripslashes(urlencode($pesan));
  } else {
    $url = 'https://app.ruangwa.id/api/send_express';
    $postfield = 'token='.$token.'&number='.$nohp.'&message='.stripslashes(urlencode($pesan));
  }

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $postfield,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/x-www-form-urlencoded'
    )
  ));

  $return = curl_exec($curl);

  curl_close($curl);
}