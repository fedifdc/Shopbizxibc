<?php
/*
Service Name: OneSender v2
Service URL: https://onesender.net
API Documentation: https://onesender.net/docs/
Field API Server: onesenderv2_server
Field API Key: onesenderv2_key
*/
if (!defined('WAFU_SCRIPT')) { die();  exit; }
 
# Handle Send Message

if (isset($wafusetting['onesenderv2_server']) && $wafusetting['onesenderv2_server'] != '') {

	$api_url = trim($wafusetting['onesenderv2_server']);

	if ( '/' == substr($api_url, -1) ) {
		$api_url .= 'api/v1/messages';
	} else if ( '/api' == substr($api_url, -4) ) {
		$api_url .= '/v1/messages';
	}

	$api_url = str_replace('/api/api/', '/api/', $api_url);
	$api_key = trim($wafusetting['onesenderv2_key']);

	$message = array(
                'recipient_type' => 'individual',
                'to' 			 => $nohp,
                'type' 			 => 'text',
                'text' 			 => array( 'body' => stripslashes($pesan) ),
            );
	if ($gambar != '') {

		$upload_obj = wp_get_upload_dir();

		$dir_path = str_replace($upload_obj['subdir'], '', $upload_obj['path']);

		$gambar = $dir_path . '/pic/' . $gambar;
		if ( file_exists($gambar) ) {

			$type 		= pathinfo($gambar, PATHINFO_EXTENSION);
			$image_res 	= file_get_contents($gambar);
			$base64 	= 'data:image/' . $type . ';base64,' . base64_encode($image_res);

	    	$image_msg = array(
	    		'raw' => $base64
	    	);

	    	if (!empty($pesan)) {
	    		$image_msg['caption'] = stripslashes($pesan);
	    	}

			$message = array(
	                'recipient_type' => 'individual',
	                'to' 			 => $nohp,
	                'type' 			 => 'image',
	                'image' 		 => $image_msg,
	            );
		}
	} 

	$conn_args = array(
            'method'      		=> 'POST',
            'timeout'     		=> 45,
            'sslverify'   		=> false,
            'headers'     		=> array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' 	=> 'application/json'
            ),
            'body'        		=> json_encode( $message ),
        );

	$request 	= wp_remote_post( $api_url, $conn_args );
	$return 	= "Berhasil";
	if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// return wp_remote_retrieve_body( $request );
		$return = "Gagal kirim pesan";
	}
}
?>