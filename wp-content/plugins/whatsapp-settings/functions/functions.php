<?php

function is_whatsapp_number_used($whatsapp_number) {
    global $wpdb;

    // Normalisasi nomor: hapus awalan "+", ganti 0 dengan 62
    $normalized_number = normalize_whatsapp_number($whatsapp_number);

    // Query untuk mencari user dengan nomor WhatsApp tersebut
    $query = $wpdb->prepare(
        "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s",
        '_whatsapp_number',
        $normalized_number
    );

    // Dapatkan hasil query
    $user_id = $wpdb->get_var($query);

    // Jika user_id ditemukan, berarti nomor sudah digunakan
    return !empty($user_id);
}

function normalize_whatsapp_number($number) {
    // Remove all non-digit characters
    $number = preg_replace('/\D/', '', $number);

    // If the number starts with +62, remove the +
    if (strpos($number, '+62') === 0) {
        $number = substr($number, 1); // Remove +, leaving 62
    }
    // If the number starts with 0, replace 0 with 62
    elseif (strpos($number, '0') === 0) {
        $number = '62' . substr($number, 1); // Replace 0 with 62
    }
    // If the number does not start with 62, prepend 62
    elseif (strpos($number, '62') !== 0) {
        $number = '62' . $number; // Prepend 62
    }

    return $number;
}

function is_valid_whatsapp_update_token($user_id, $token) {
    if (!$user_id || !$token) {
        return false;
    }

    // Ambil token dari usermeta
    $saved_token = get_user_meta($user_id, '_whatsapp_change_token', true);

    return ($saved_token && $token === $saved_token);
}