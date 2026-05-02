<?php
/**
 * Plugin Name: Instant Sponsor Commission
 * Description: Setting Sponsor commission after customer done order
 * Version: 1.0
 * Author: Novan.NP
 * Text Domain: 
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('INSTANT_SPONSOR_COMMISSION_VERSION', '1.0.0');
define('INSTANT_SPONSOR_COMMISSION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('INSTANT_SPONSOR_COMMISSION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once INSTANT_SPONSOR_COMMISSION_PLUGIN_PATH . 'admin/settings.php';
require_once INSTANT_SPONSOR_COMMISSION_PLUGIN_PATH . 'functions/functions.php';


