<?php
// Pastikan file ini berada di dalam folder plugin WordPress

// Hook untuk mendaftarkan REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('wafu-memberarea/v1', '/grup', array(
        'methods' => 'GET',
        'callback' => 'wafu_change_group',
        'permission_callback' => function () {
            return current_user_can('edit_posts'); // Sesuaikan dengan izin yang diperlukan
        },
    ));
});

/**
 * Callback untuk REST API endpoint
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function wafu_change_group(WP_REST_Request $request) {
    // Ambil parameter 'code_grup' dari request
    $code_grup = $request->get_param('code_grup');

    // Validasi parameter
    if (empty($code_grup)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Parameter code_grup tidak ditemukan atau kosong.'
        ), 400);
    }

    global $wpdb;

    // Periksa apakah code_grup ada di tabel wafu_member_mlm
    $table_name = $wpdb->prefix . 'wafu_grup_mlm';
    $grup = $wpdb->get_var($wpdb->prepare(
        "SELECT * FROM $table_name WHERE code_grup = %s",
        $code_grup
    ));

    if ($grup) {
        // Jika ditemukan, buat wa.me link
        $table_name = $wpdb->prefix . 'wafu_member_mlm';
        $grup = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user = %s",
            $code_grup
        ));

        if ($grup) {
            $kodereg =  '#'.$grup->nama_member;
            $wafusetting = get_user_meta($grup->camp_user_wp,'wafu_setting');
            $wa_link = "https://wa.me/" . wafu_format_mlm($wafusetting['sender']) . "/?text=" . rawurlencode('join#' . $grup->grup_code . $kodereg);

            // Hit langsung link wa.me
            $response = wp_remote_get($wa_link);

            if (is_wp_error($response)) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => 'Gagal mengakses link WA.'
                ), 500);
            }

            // Perbarui grup pengguna
            $update_result = wafu_update_user_group($code_grup);

            if ($update_result) {
                return new WP_REST_Response(array(
                    'success' => true,
                    'message' => 'Grup berhasil diperbarui.',
                    'wa_link' => $wa_link
                ), 200);
            } else {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => 'Gagal memperbarui grup pengguna.'
                ), 500);
            }
        } else {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Grup tidak ditemukan.'
            ), 404);
        }
    }
}

/**
 * Fungsi untuk memperbarui grup pengguna
 *
 * @param string $code_grup
 * @return bool
 */
function wafu_update_user_group($code_grup) {
    // Contoh logika untuk memperbarui grup pengguna
    // Sesuaikan dengan implementasi spesifik Anda
    return true; // Return true jika berhasil, false jika gagal
}