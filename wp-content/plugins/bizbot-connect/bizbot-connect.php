<?php
/**
 * Plugin Name: BizBot Connect
 * Plugin URI: https://example.com/bizbot-connect
 * Description: A plugin to integrate BizBot services with your WordPress site.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bizbot-connect
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('botsailor_sync', 'botsailor_sync_shortcode');
function botsailor_sync_shortcode() {
    ob_start();
    $user_id = get_current_user_id();
    $botsailor_data = get_user_meta($user_id, 'botsailor_data', true);
    ?>

    <div id="botsailor-sync-section">
    <?php if ($botsailor_data): ?>
            <div style="background-color: #f1f1f1; padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <p style="font-weight: bold; font-size: 16px; margin-bottom: 10px;">Akun BizBot tersinkron:</p>
            <ul style="list-style: none; padding: 0; margin: 0 0 15px 0;">
                <li style="margin-bottom: 5px;"><strong>Nama:</strong> <?= esc_html($botsailor_data['name']); ?></li>
                <li style="margin-bottom: 5px;"><strong>Email:</strong> <?= esc_html($botsailor_data['email']); ?></li>
                <li style="margin-bottom: 5px;"><strong>Paket:</strong> <?= esc_html($botsailor_data['package_name']); ?></li>
            </ul>
            <button id="botsailor_login_button" style="background-color: #0073aa; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px;">Login ke BizBot</button>
            </div>
        <?php else: ?>
            <form id="botsailor-sync-form" style="background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 400px; margin: 0 auto;">
                <label for="botsailor_email" style="display: block; font-weight: bold; margin-bottom: 10px;">Masukkan email BizBot Anda:</label>
                <input type="email" name="botsailor_email" id="botsailor_email" placeholder="Email Anda" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px; box-sizing: border-box;">
                <button type="submit" id="botsailor_sync_button" style="background-color: #0073aa; color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px;">Sync BizBot</button>
                <p id="botsailor_message" style="margin-top: 10px; color: #555; font-size: 14px;"></p>
                <p class="botsailor-info-text">
                Untuk menggunakan fitur ini, Anda harus memiliki akun BizBot. Jika belum punya, silakan 
                <a href="https://bizbot.shopbiz.id/register" target="_blank" class="botsailor-register-link">mendaftar di BizBot</a>.
            </p>
            </form>
          
        
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("botsailor-sync-form");
        const loginBtn = document.getElementById("botsailor_login_button");
        const message = document.getElementById("botsailor_message");

        if (loginBtn) {
            loginBtn.addEventListener("click", function () {
                fetch("<?= esc_url(admin_url('admin-ajax.php')); ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ action: "botsailor_get_login_url" })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "ok") {
                        window.open(data.login_url, "_blank");
                    } else {
                        alert(data.message);
                    }
                });
            });
        }

        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const email = document.getElementById("botsailor_email").value;
                message.innerHTML = "Memeriksa email ke server BizBot...";

                fetch("<?= esc_url(admin_url('admin-ajax.php')); ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({
                        action: "botsailor_sync_user",
                        botsailor_email: email
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "ok") {
                        location.reload();
                    } else {
                        message.innerHTML = `<span style="color:red;">${data.message}</span>`;
                    }
                });
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}

// Ajax: Sync user ke BizBot
add_action('wp_ajax_botsailor_sync_user', 'handle_botsailor_sync_user');
add_action('wp_ajax_nopriv_botsailor_sync_user', 'handle_botsailor_sync_user');

function handle_botsailor_sync_user() {
    $email = sanitize_email($_POST['botsailor_email'] ?? '');
    if (empty($email)) {
        wp_send_json(['status' => 'error', 'message' => 'Email tidak boleh kosong.']);
    }

    $apiToken = '10384|ncL9tBgvjtGm9TxKEqwmqwNyyTjB7faE8ay9AzoKc8535255';
    $url = "https://botsailor.com/api/v1/user/list?id=" . urlencode($email) . "&apiToken=" . urlencode($apiToken);
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json(['status' => 'error', 'message' => 'Gagal menghubungi server BizBot.']);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $user_data = $body['message'] ?? null;

    if (!$user_data) {
        wp_send_json(['status' => 'error', 'message' => 'Email belum terdaftar di BizBot.']);
    }

    // Simpan ke user meta
    $user_id = get_current_user_id();
    if ($user_id) {
        update_user_meta($user_id, 'botsailor_data', $user_data);
    }

    // Dapatkan direct login URL
    $params = [
        'apiToken'   => $apiToken,
        'email'      => $user_data['email'],
        'name'       => $user_data['name'],
        'mobile'     => $user_data['mobile'],
        'package_id' => $user_data['package_id'],
        'status'     => $user_data['status']
    ];

    $login_url = "https://botsailor.com/api/v1/user/get/direct-login-url?" . http_build_query($params);
    $login_response = wp_remote_get($login_url);

    if (is_wp_error($login_response)) {
        wp_send_json(['status' => 'error', 'message' => 'Gagal mendapatkan URL login.']);
    }

    $login_body = json_decode(wp_remote_retrieve_body($login_response), true);
    $direct_login = $login_body['message']['direct_login_url'] ?? '';

    if (!$direct_login) {
        wp_send_json(['status' => 'error', 'message' => 'Direct login URL tidak ditemukan.']);
    }

    wp_send_json(['status' => 'ok', 'login_url' => esc_url_raw($direct_login)]);
}

// Ajax: Ambil ulang direct login URL jika sudah tersimpan
add_action('wp_ajax_botsailor_get_login_url', 'handle_botsailor_get_login_url');
function handle_botsailor_get_login_url() {
    $user_id = get_current_user_id();
    $user_data = get_user_meta($user_id, 'botsailor_data', true);

    if (!$user_data) {
        wp_send_json(['status' => 'error', 'message' => 'Data belum tersinkron.']);
    }

    $apiToken = '10384|ncL9tBgvjtGm9TxKEqwmqwNyyTjB7faE8ay9AzoKc8535255';
    $params = [
        'apiToken'   => $apiToken,
        'email'      => $user_data['email'],
        'name'       => $user_data['name'],
        'mobile'     => $user_data['mobile'],
        'package_id' => $user_data['package_id'],
        'status'     => $user_data['status']
    ];

    $url = "https://botsailor.com/api/v1/user/get/direct-login-url?" . http_build_query($params);
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json(['status' => 'error', 'message' => 'Gagal menghubungi BizBot.']);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    $direct_login = $body['message']['direct_login_url'] ?? null;

    if ($direct_login) {
        wp_send_json(['status' => 'ok', 'login_url' => esc_url_raw($direct_login)]);
    } else {
        wp_send_json(['status' => 'error', 'message' => 'Gagal mendapatkan URL login.']);
    }
}
