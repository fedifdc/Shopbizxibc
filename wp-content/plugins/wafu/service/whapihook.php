<?php 
# Digunakan utk mendapatkan variabel pesan dan nomor HP pengirim
$get 	= file_get_contents("php://input");
$data 	= json_decode($get,true);
$pesan 	= $data['app']['data']['message'][0]['value']; 
#$pesan = $get;
$nohp 	= str_replace('@s.whatsapp.net','',$data['app']['data']['sender']['id']); 
?>