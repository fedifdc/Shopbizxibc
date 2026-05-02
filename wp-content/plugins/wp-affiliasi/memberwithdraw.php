<?php
    if (!defined('IS_IN_SCRIPT')) { die();  exit; } 
    if (!isset($user_ID) || $user_ID == 0) { 
    	$refer = $_SERVER['REQUEST_URI'];
    	header("Location: ".wp_login_url($refer));
    }
    	$showtxt .= '<h2>Withdraw</h2>';
    	$showtxt .= '[affiliator-withdraw]';

    
    
    
    
