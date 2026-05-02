<?php
/*
Service Name: Starsender
Service URL: https://lutvi.com/starsender
API Documentation: https://starsender.online/rest-api
Field API Key: starsender_api
*/
# Handle Kirim Pesan
if (isset($wafusetting['starsender_api']) && $wafusetting['starsender_api'] != '' && isset($nohp) && isset($pesan)) {
	if (isset($gambar) && $gambar != '') {
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['baseurl'].'/pic/'.$gambar;
	}

	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://starsender.online/api/sendFiles?message='.rawurlencode($pesan).'&tujuan='.rawurlencode($nohp.'@s.whatsapp.net'),
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => array('file'=> $gambar),
	  CURLOPT_HTTPHEADER => array(
	    'apikey: '.$wafusetting['starsender_api']
	  ),
	));

	$return = curl_exec($curl);

	curl_close($curl);
}

$return .= $gambar;
