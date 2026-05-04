<?php
/*
Plugin Name: MyCred Commission Plugin
Description: Handles commission points for MyCred based on user purchases.
Version: 1.0
Author: Novan N.P
*/

defined( 'ABSPATH' ) || exit;

// Include necessary files
require_once __DIR__ . '/includes/functions.php'; // Functions file for your plugin


add_filter( 'mycred_setup_hooks', 'setup_commission_hooks' );
function setup_commission_hooks( $installed ) {
    // Register your custom hook for commission calculation
    $installed['commission'] = array(
        'title' => __('Commission Points', 'mycred'),
        'description' => __('Handles commission points based on user purchases.', 'mycred'),
        'callback' => array('MyCred_Commission_Hooks')
    );
    return $installed;
}

