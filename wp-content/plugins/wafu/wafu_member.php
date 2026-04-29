<?php 
if (!defined('WAFU_SCRIPT')) { die();  exit; }
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
	include('member_add.php');
} elseif (isset($_GET['import']) && is_numeric($_GET['import'])) {
	include('member_import.php');
} elseif (isset($_GET['delall']) && $_GET['delall'] == 'yes') {
	$wpdb->query("DELETE FROM `wafu_import`");
	echo '
	<div class="wrap">
	<h2>Semua daftar import telah dihapus</h2>	
	</div>';
} else {
	include('member_list.php');
}
?>