<?php 
# Digunakan utk mendapatkan variabel pesan dan nomor HP pengirim
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$nohp = $data['pengirim'];
$pengirim = $data['device'];
$pesan = $data['pesan'];
?>