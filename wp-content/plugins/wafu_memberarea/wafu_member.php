<?php 
if (!defined('WAFU_SCRIPT')) { die();  exit; }
if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$current_user_id = intval($_GET['user_id']);
} else {
	$current_user_id = get_current_user_id();
}
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
	include('member_add.php');
} elseif (isset($_GET['import']) && is_numeric($_GET['import'])) {
	include('member_import.php');
} elseif (isset($_GET['delall']) && $_GET['delall'] == 'yes') {
	$wpdb->query("DELETE FROM `wafu_import_mlm` where `camp_user_wp`=".$current_user_id);
	echo '
	<div class="wrap">
	<h2>Semua daftar import telah dihapus</h2>	
	</div>';
} else {
	include('member_list.php');
}
?>