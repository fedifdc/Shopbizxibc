<?php 
$wp_load = substr( dirname( __FILE__ ), 0, strpos( dirname( __FILE__ ), 'wp-content' ) ) . 'wp-load.php';
if ( ! empty( $wp_load ) && file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	die('Could not load WordPress');
}

$wafusetting = get_option('wafusetting');

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => trim($wafusetting['onesenderv2_server']).'api/v1/messages',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',  
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($wafusetting['onesenderv2_key'])
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response,TRUE);
echo '<pre>';
print_r($data);