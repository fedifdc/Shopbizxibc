<?php
// Buat tabel untuk menyimpan user_id, api_key, dan secret_key saat plugin diaktifkan
register_activation_hook(__FILE__, 'shopbiz_create_api_key_table');

function shopbiz_create_api_key_table() {
    global $wpdb;
    $shopbiz_api_table = $wpdb->prefix . 'shopbiz_api_keys';
    $shopbiz_user_sync_table = $wpdb->prefix . 'shopbiz_user_sync';
    
    // Struktur SQL untuk membuat table
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
        PRIMARY KEY (id),
    ) $charset_collate;";

    // Gunakan dbDelta untuk membuat table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($shopbiz_api_keys);
    dbDelta($shopbiz_user_sync);

}