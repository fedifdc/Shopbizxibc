<?php 
# Digunakan utk mendapatkan variabel pesan dan nomor HP pengirim
$json  	= file_get_contents( "php://input" );
$data  	= json_decode( $json, true );

$nohp 	= '';
$pesan 	= '';

if ( isset( $data['message_text']) ) {
	$pesan = $data['message_text'];

	if ( !empty( $data['chat'] ) && substr($data['chat'], -5) != '@g.us') {
    	$nohp = preg_replace( '/[^0-9]+/', '', $data['chat'] );
    }
}
?>
