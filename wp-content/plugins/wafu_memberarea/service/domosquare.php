<?php
/*
Service Name: Domosquare
Service URL: https://billingotomatis.com
API Documentation: https://www.domosquare.com/tutorial/billingotomatis/api-wa-gateway-billingotomatis.html
Field API Server: domo_server
Field API ID: domo_id
Field API Key: domo_key
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
# Handle Send Message
if (isset($wafusetting['domo_id']) && $wafusetting['domo_id'] != '') {
	$var['api_id'] = $wafusetting['domo_id'];
	$var['api_key'] = $wafusetting['domo_key'];
	$var['phone'] = $nohp;
	$var['text'] = stripslashes($pesan);
	if ($gambar != '') {
		#mengirimkan gambar/attachment
		$var['mime'] = 'image/png';
		$var['filename'] = 'gambar.png';
		$upload_dir = wp_upload_dir();
		$gambar = $upload_dir['basedir'].'/pic/'.$gambar;
		$var['filedata'] = base64_encode(file_get_contents($gambar));
	}
	$ch = curl_init($wafusetting['domo_server']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
	$return = curl_exec($ch);
	curl_close($ch);
}
?>