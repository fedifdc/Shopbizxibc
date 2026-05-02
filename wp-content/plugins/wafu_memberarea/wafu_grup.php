<?php 
if (isset($_GET['campaign']) && is_numeric($_GET['campaign'])) {
	include('grup_campaign.php');
} else {
	include('grup_home.php');
}
?>