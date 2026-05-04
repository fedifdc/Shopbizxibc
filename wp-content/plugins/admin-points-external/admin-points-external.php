<?php
/**
 * Plugin Name: Admin External Point Sync
 * Description: Plugin untuk sinkronisasi poin dengan website partner.
 * Version: 1.0
 * Author: Ruli Setiawan
 */

 require_once __DIR__ . '/include/handle-account-api.php';
 require_once __DIR__ . '/include/handle-transaction-api.php';
 require_once __DIR__ . '/include/handle-yith-account-api.php';
 require __DIR__ . '/function/crone-point-level.php';
 require __DIR__ . '/template/user-sync-list.php';
 
 register_activation_hook(__FILE__, 'shopbiz_create_api_key_table');
 function shopbiz_create_api_key_table() {
     global $wpdb;
     $shopbiz_api_table = $wpdb->prefix . 'shopbiz_api_keys';
     $shopbiz_user_sync_table = $wpdb->prefix . 'shopbiz_user_sync';
     
     $charset_collate = $wpdb->get_charset_collate();
 
     $shopbiz_api_keys = "CREATE TABLE IF NOT EXISTS $shopbiz_api_table (
         id BIGINT(20) NOT NULL AUTO_INCREMENT,
         user_id BIGINT(20) NOT NULL,
         api_key VARCHAR(64) NOT NULL,
         website  VARCHAR(200) NOT NULL,
         secret_key VARCHAR(64) NOT NULL,
         PRIMARY KEY (id),
         UNIQUE KEY website (website)
     ) $charset_collate;";
 
     $shopbiz_user_sync = "CREATE TABLE IF NOT EXISTS $shopbiz_user_sync_table (
         id BIGINT(20) NOT NULL AUTO_INCREMENT,
         user_id BIGINT(20) NOT NULL,
         user_partner_id BIGINT(20) NOT NULL,
         website  VARCHAR(200) NOT NULL,
         is_sync BOOLEAN NOT NULL DEFAULT 1,
         PRIMARY KEY (id)
     ) $charset_collate;";
 
   
     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     dbDelta($shopbiz_api_keys);
     dbDelta($shopbiz_user_sync);
 
 }


 add_shortcode('shopbiz_customer_api_key_page', 'shopbiz_display_generate_keys_form');
 
 function shopbiz_display_generate_keys_form() {
     ob_start();
 
     $user_id = get_current_user_id();
 
     if ($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'shopbiz_api_keys';
 
        $api_key_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", $user_id));
 
        if ($api_key_data) {
             echo '<form method="post" style="padding: 20px; border-radius: 10px; font-family: Arial, sans-serif; position: relative;">';
             
             echo '<div style="margin-bottom: 15px; position: relative;">';
             echo '<label for="api_key" style="display: block; font-weight: bold; margin-bottom: 5px;">API Key:</label>';
             echo '<input type="text" id="api_key" value="' . esc_html($api_key_data->api_key) . '" readonly style="width: 100%; padding: 10px; border: 1px solid #444; border-radius: 5px;">';
             echo '<button type="button" class="btn btn-primary"  onclick="copyToClipboard(\'api_key\')" style="position: absolute; top: 35px; right: 10px;  border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 12px;">Copy</button>';
             echo '</div>';
             
             echo '<div style="margin-bottom: 15px; position: relative;">';
             echo '<label for="secret_key" style="display: block; font-weight: bold; margin-bottom: 5px;">Secret Key:</label>';
             echo '<input type="text" id="secret_key" value="' . esc_html($api_key_data->secret_key) . '" readonly style="width: 100%; padding: 10px; border: 1px solid #444;  border-radius: 5px;">';
             echo '<button type="button" class="btn btn-primary"  onclick="copyToClipboard(\'secret_key\')" style="position: absolute; top: 35px; right: 10px;  border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 12px;">Copy</button>';
             echo '</div>';
             
             echo '<div style="margin-bottom: 15px; position: relative;">';
             echo '<label for="website" style="display: block; font-weight: bold; margin-bottom: 5px;">Website:</label>';
             echo '<input type="text" id="website" value="' . esc_html($api_key_data->website) . '" readonly style="width: 100%; padding: 10px; border: 1px solid #444;  border-radius: 5px;">';
             echo '<button class="btn btn-primary" type="button" onclick="copyToClipboard(\'website\')" style="position: absolute; top: 35px; right: 10px;  border: none; border-radius: 5px; padding: 5px 10px; cursor: pointer; font-size: 12px;">Copy</button>';
             echo '</div>';

             echo '<script>
                     function copyToClipboard(elementId) {
                         const inputElement = document.getElementById(elementId);
                         inputElement.select();
                         document.execCommand("copy");
                         alert("Copied to clipboard: " + inputElement.value);
                     }
                   </script>';
         
             echo '</form>';
         } else {
             echo '<form method="post" style=" padding: 20px; border-radius: 10px; font-family: Arial, sans-serif;">';
             
             echo '<div style="margin-bottom: 15px;">';
             echo '<label for="website" style="display: block; font-weight: bold; margin-bottom: 5px;">Website:</label>';
             echo '<input name="website" id="website" type="text" required style="width: 100%; padding: 10px; border: 1px solid #444;  border-radius: 5px;">';
             echo '</div>';
             
             echo '<button type="submit" name="generate_api_keys" class="button" style="padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Generate API Key & Secret Key</button>';
             
             echo '</form>';
         }
         if (isset($_POST['generate_api_keys'])) {
             $website = sanitize_text_field($_POST['website']);
             shopbiz_generate_api_and_secret_key($user_id, $website);
             wp_redirect(get_permalink());
             exit;
         }
     } else {
         echo '<p>You need to be logged in to view this page.</p>';
     }
 
     return ob_get_clean();
 }

function shopbiz_generate_api_and_secret_key($user_id, $website) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shopbiz_api_keys';
    $api_key = wp_generate_password(32, false);
    $secret_key = wp_generate_password(64, true, false);

    $wpdb->insert($table_name, array(
        'user_id'    => $user_id,
        'api_key'    => $api_key,
        'website'    => $website,
        'secret_key' => $secret_key
    ));
}

add_action('rest_api_init', function() {
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, x-api-key, x-secret-key, website');
        return $value;
    });
});
$shopbiz_account = new ShopbizAccountPartnerAPI();
$shopbiz_account->register_endpoints();
$shopbiz_account->sync_account_partnert_endpoints();
$shopbiz_account->test_sync_endpoint();
$shopbiz_account->commission_endpoint();
$shopbiz_point = new ShopbizPointExternalAPI();
$shopbiz_point->transaction_point_external_api();
$shopbiz_point->log_point_quota();
$shopbiz_point->log_point_external();
$shopbiz_point->log_point_pending();
$shopbiz_point->point_level_by_user_website();
$shopbiz_point->check_user_sync();
$shopbiz_point->point_external_refund();
$shopbiz_point->check_point_level_convert_api();
$shopbiz_point->list_user_sync();
convert_point_level();
convert_point_level_crone();
convert_point_external_crone();
add_sync_user_menu();
$yithAccountAPI = new YithAccountAPI();
$yithAccountAPI->register_routes();