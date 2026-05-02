<?php
add_action('woocommerce_account_dashboard', 'shopbiz_display_generate_keys_button');
function shopbiz_display_generate_keys_button() {
    $user_id = get_current_user_id();

    if ($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'shopbiz_api_keys';
        $website = $_POST['website']; 
        $api_key_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", $user_id));

        if ($api_key_data) {
            echo '<p>Your API Key: <strong>' . esc_html($api_key_data->api_key) . '</strong></p>';
            echo '<p>Your Secret Key: <strong>' . esc_html($api_key_data->secret_key) . '</strong></p>';
        } else {
            echo '<form method="post">';
            echo '<label for="website">Website : </label>';
            echo '<input name="website" type="text">';
            echo '<button type="submit" name="generate_api_keys" class="button">Generate API Key & Secret Key</button>';
            echo '</form>';
        }

        if (isset($_POST['generate_api_keys'])) {
            shopbiz_generate_api_and_secret_key($user_id,$website);
            wp_redirect(get_permalink());
            exit;
        }
    }
}

// Fungsi untuk generate API Key dan Secret Key
function shopbiz_generate_api_and_secret_key($user_id,$website) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'shopbiz_api_keys';

    // Generate API Key dan Secret Key acak
    $api_key = wp_generate_password(32, false); // 32 karakter alfanumerik
    $secret_key = wp_generate_password(64, false, true); // 64 karakter dengan simbol

    // Masukkan ke database
    $wpdb->insert($table_name, array(
        'user_id'    => $user_id,
        'api_key'    => $api_key,
        'website'    => $website,
        'secret_key' => $secret_key
    ));
}