<?php 
# Digunakan utk mendapatkan variabel pesan dan nomor HP pengirim
if (isset($_POST['from']) && isset($_POST['body'])) {
	# Handle Webhook
	$nohp  = $wpdb->_real_escape($_POST['from']);
	$pesan = $wpdb->_real_escape($_POST['body']);
}