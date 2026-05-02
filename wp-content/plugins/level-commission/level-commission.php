<?php
/**
 * Plugin Name: Level Commission
 * Description: Setting Level commission if user not upgraded in 30 days without point deduction
 * Version: 1.0
 * Author: Novan.NP
 * Text Domain: level-commission
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('LEVEL_COMMISSION_VERSION', '1.0.0');
define('LEVEL_COMMISSION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LEVEL_COMMISSION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once LEVEL_COMMISSION_PLUGIN_PATH . 'admin/settings.php';
require_once LEVEL_COMMISSION_PLUGIN_PATH . 'cron/cron.php';
