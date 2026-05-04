<?php
/*
Plugin Name: MyCred Commission Plugin Point Level
Description: Handles commission points level for MyCred based on user purchases.
Version: 1.0
Author: Ruli
*/

defined( 'ABSPATH' ) || exit;

// Include necessary files
require_once __DIR__ . '/includes/functions.php'; // Functions file for your plugin


add_filter( 'mycred_setup_hooks', 'setup_commission_level_hooks' );
function setup_commission_level_hooks( $installed ) {
    // Register your custom hook for commission calculation
    $installed['commission_level_upgrade'] = array(
        'title' => __('Commission Points Level', 'mycred'),
        'description' => __('Handles commission points level based on user purchases.', 'mycred'),
        'callback' => array('MyCred_Commission_Hooks_Level')
    );
    return $installed;
}

