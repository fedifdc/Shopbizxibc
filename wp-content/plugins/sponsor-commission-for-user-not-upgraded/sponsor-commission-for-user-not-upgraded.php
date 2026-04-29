<?php
/**
 * Plugin Name: Sponsor Commission for user not upgraded
 * Description: Setting Sponsor commission if user not upgraded in 30 days
 * Version: 1.0
 * Author: Novan.NP
 * Text Domain: komship-integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('SPONSOR_COMMISSION_VERSION', '1.0.0');
define('SPONSOR_COMMISSION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SPONSOR_COMMISSION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once SPONSOR_COMMISSION_PLUGIN_PATH . 'admin/settings.php';
require_once SPONSOR_COMMISSION_PLUGIN_PATH . 'cron/cron.php';

