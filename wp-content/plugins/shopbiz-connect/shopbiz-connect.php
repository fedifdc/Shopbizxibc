<?php

/**
 * Plugin Name: ShopBiz Connect
 * Plugin URI: https://shopbiz.id/shopbiz-connect
 * Description: This plugin connects ShopBiz MLM to WordPress.
 * Version: 1.0.0
 * Author: Sandi Anjar Maulana
 * Author URI: https://shopbiz.id
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shopbiz-connect
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/functions/options.php';
require_once __DIR__ . '/functions/registration.php';
require_once __DIR__ . '/functions/upgrade.php';
require_once __DIR__ . '/functions/order_complete.php';
require_once __DIR__ . '/functions/order_cancel.php';
require_once __DIR__ . '/functions/commission_meta_box.php';
require_once __DIR__ . '/functions/wpbv.php';
require_once __DIR__ . '/templates/my-account.php';
require_once __DIR__ . '/templates/upgrade-account.php';
require_once __DIR__ . '/api/upgrade.php';

#define route
$sync_api = new Upgrade();
$sync_api->sync_register_process();