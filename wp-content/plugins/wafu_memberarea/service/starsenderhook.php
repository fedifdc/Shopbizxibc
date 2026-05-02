<?php 
# Digunakan utk mendapatkan variabel pesan dan nomor HP pengirim
$json 		= file_get_contents('php://input');
$data 		= json_decode($json);
$pesan 		= $data->message; 
$nohp 		= wafu_format_mlm($data->from); 
?>
