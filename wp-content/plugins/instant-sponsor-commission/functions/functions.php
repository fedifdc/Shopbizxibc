<?php
function get_sponsor_level($points) {
    error_log("Calculating sponsor level for points: $points");
    if ($points >= 5000000) {
        error_log("Level: Prestige");
        return 'Prestige';
    } elseif ($points >= 3000000) {
        error_log("Level: Premium");
        return 'Premium';
    } elseif ($points >= 1000000) {
        error_log("Level: Basic");
        return 'Basic';
    } else if ($points >= 250000) {
        error_log("Level: Starter");
        return 'Starter';
    } else {
        error_log("Level: Free Member");
        return 'Free Member';
    }
}

function get_user_id_from_order($order_id) {
    // Pastikan WooCommerce diaktifkan
    if (!class_exists('WC_Order')) {
        error_log("WooCommerce is not active. Cannot fetch user ID from order.");
        return false;
    }

    // Ambil objek order dari WooCommerce
    $order = wc_get_order($order_id);
    if (!$order) {
        error_log("Order with ID $order_id not found.");
        return false;
    }

    // Ambil ID pengguna dari order
    $user_id = $order->get_user_id();
    if (!$user_id) {
        error_log("No user associated with order ID $order_id.");
    } else {
        error_log("Found user ID: $user_id for order ID $order_id");
    }

    return $user_id;
}

function calculate_commission($user_id, $commission_settings, $order) {
    global $wpdb;

    // Hitung poin level dari pesanan
    $mycred = mycred(); // Pastikan myCred tersedia
    $point_type = 'point_level'; // Ubah jika menggunakan jenis poin lain
    //$point_type = 'point_upgrade'; // Ubah jika menggunakan jenis poin lain

    $point_earned = get_user_points($mycred, $user_id, $order, $point_type);
    error_log("Points earned by user $user_id: $point_earned");

    $name = $wpdb->get_var($wpdb->prepare(
        "SELECT nama FROM wp_member WHERE idwp = %d",
        $user_id
    ));
    error_log("User name: $name");

    $sponsor_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id_referral FROM wp_member WHERE idwp = %d",
        $user_id
    ));
    error_log("Sponsor ID: $sponsor_id");

    if ($sponsor_id) {
        $sponsor_username = $wpdb->get_var($wpdb->prepare(
            "SELECT username FROM wp_users WHERE ID = %d",
            $sponsor_id
        ));
        $sponsor_points = intval(get_user_meta($sponsor_id, 'point_level', true));
        $sponsor_points_chek = intval(get_user_meta($sponsor_id, 'point_upgrade', true));
        error_log("Sponsor points: $sponsor_points");

        $sponsor_level = get_sponsor_level($sponsor_points_chek);
        error_log("Sponsor level: $sponsor_level");

        // Hitung komisi berdasarkan level sponsor
        $commission_percentage = $commission_settings[$sponsor_level];
        $commission_points = ($point_earned * $commission_percentage) / 100;

        // Update voucher sponsor
        $wpdb->query($wpdb->prepare(
            "UPDATE wp_member 
             SET jml_voucher = jml_voucher + %d, 
                 sisa_voucher = sisa_voucher + %d 
             WHERE idwp = %d",
            $commission_points, 
            $commission_points, 
            $sponsor_id
        ));
        insert_commission_log($sponsor_id, 'wp_affiliate', 'Komisi Instan', 'Instant Komisi Order Completed  #'. $order->get_id() .' from '. $name , $commission_points, 'uang', 'success');


        error_log("Updated sponsor voucher. Commission points: $commission_points");

        // Log komisi
        log_commission($user_id, $sponsor_id, $point_earned, $commission_points, $name, $order->get_id());
    }
}

function log_commission($user_id, $sponsor_id, $point_type, $commission, $name, $order_id) {
    global $wpdb;

    // Ambil username dari user ID
    $username = $wpdb->get_var($wpdb->prepare(
        "SELECT user_login FROM {$wpdb->users} WHERE ID = %d",
        $user_id
    ));

    if (!$username) {
        error_log("Username not found for user ID: $user_id");
        $username = "Unknown"; // Fallback jika username tidak ditemukan
    }

    error_log("Logging commission for user: $username (ID: $user_id), Sponsor: $sponsor_id, Points: $point_type, Commission: $commission");

    // Simpan log ke dalam tabel cb_laporan
    $result = $wpdb->query($wpdb->prepare(
        "INSERT INTO cb_laporan (tanggal, transaksi, kredit, komisi, keterangan, id_user, id_sponsor, id_order) 
         VALUES (NOW(), %s, %d, %d, 'cbaff', %d, %d, %d)",
        "Komisi pembelian referral dari $name (username: $username)", 
        $point_type, 
        $commission, 
        $user_id, 
        $sponsor_id, 
        $order_id
    ));
    error_log("Inserted commission log. Query result: $result");
}

function handle_order_completed($order_id) {
    error_log("Handling completed order ID: $order_id");

    $commission_settings = array(
        'Free Member' => get_option('free_member_commission_instant', 20),
        'Starter'     => get_option('starter_commission_instant', 5),
        'Basic'       => get_option('basic_commission_instant', 10),
        'Premium'     => get_option('premium_commission_instant', 15),
        'Prestige'    => get_option('prestige_commission_instant', 20)
    );

    $order = wc_get_order($order_id);

    if (!$order) {
        error_log("Order not found for ID $order_id");
        return;
    }

    $user_id = get_user_id_from_order($order_id);
    if (!$user_id) {
        error_log("User ID not found for order ID $order_id");
        return;
    }

    calculate_commission($user_id, $commission_settings, $order);
}
// add_action('woocommerce_order_status_completed', 'handle_order_completed');
add_action('woocommerce_order_status_completed', 'schedule_handle_order_completed');
add_action('background_handle_order_completed_instant_commission', 'handle_order_completed');

function schedule_handle_order_completed($order_id) {
    if (!wp_next_scheduled('background_handle_order_completed', array($order_id))) {
        wp_schedule_single_event(time() + 10, 'background_handle_order_completed_instant_commission', array($order_id));
    }
}



function get_user_points($mycred, $user_id, $order, $point_type) {
    $items = $order->get_items();
    $types = mycred_get_types();
    $payout = 0;

    foreach ($items as $item) {
        // Get the product ID or the variation ID
        $product_id = absint($item['product_id']);
        $variation_id = absint($item['variation_id']);

        // Check if the product is an affiliate product
        $is_affiliate_product = get_post_meta($variation_id ? $variation_id : $product_id, '_is_affiliate_product', true);

        if ($is_affiliate_product === "yes") {
            $order_id = $order->get_id();
            $affiliate_reward_serialized = get_post_meta($order_id, 'poin_level_affiliate', true);
            // Unserialize the meta value if it's serialized
            $affiliate_reward = maybe_unserialize($affiliate_reward_serialized);

            if (is_array($affiliate_reward)) {
                // Assuming you want to sum all rewards for affiliate products
                $payout = array_sum($affiliate_reward);
            } else {
                // If the unserialized data is not an array, treat it as a single value
                $payout = $affiliate_reward;
            }
            break; 
        } else {
            $reward_amount = mycred_get_woo_product_reward($product_id, $variation_id, $point_type);
            $payout += ($mycred->number($reward_amount) * $item['qty']);
        }
    }

    error_log("Total payout for order $order->get_id(): $payout");
    return $payout; // Return the total payout
}



// Add hooks for revoking points when transitioning from 'completed' to other statuses
function run_revoke_hook() {
    // Get all order statuses
    $order_statuses = wc_get_order_statuses();

    foreach ($order_statuses as $status_key => $status_label) {
        // Skip the 'completed' status itself
        if ($status_key === 'wc-completed') {
            continue;
        }

        // Add a hook for each transition from 'completed' to another status
        add_action(
            'woocommerce_order_status_completed_to_' . str_replace('wc-', '', $status_key),
            'schedule_revoke_sponsor_commission',
            10,
            1
        );
    }
}
add_action('init', 'run_revoke_hook');

// Schedule the function to run in the background
function schedule_revoke_sponsor_commission($order_id) {
    if (!wp_next_scheduled('revoke_sponsor_commission_cron', array($order_id))) {
        wp_schedule_single_event(time() + 10, 'revoke_sponsor_commission_cron', array($order_id));
    }
}

// Hook into cron event
add_action('revoke_sponsor_commission_cron', 'revoke_sponsor_commission_points');


// Revoke sponsor commission points
function revoke_sponsor_commission_points($order_id) {
    global $wpdb;

    error_log("Revoking commission for order ID: $order_id");

    $order = wc_get_order($order_id);
    if (!$order) {
        error_log("Order not found for ID $order_id");
        return;
    }

    $user_id = get_user_id_from_order($order_id);
    if (!$user_id) {
        error_log("User ID not found for order ID $order_id");
        return;
    }

    // Fetch the sponsor ID
    $sponsor_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id_referral FROM wp_member WHERE idwp = %d",
        $user_id
    ));

    if ($sponsor_id) {
        // Fetch the sponsor's referral username
        $sponsor_username = $wpdb->get_var($wpdb->prepare(
            "SELECT username FROM wp_users WHERE ID = %d",
            $sponsor_id
        ));

        // Fetch commission points logged for this order
        $commission_points = $wpdb->get_var($wpdb->prepare(
            "SELECT komisi FROM cb_laporan WHERE id_order = %d AND id_sponsor = %d",
            $order_id,
            $sponsor_id
        ));

        if ($commission_points) {
            // Revert sponsor points and vouchers
            $wpdb->query($wpdb->prepare(
                "UPDATE wp_member 
                 SET jml_voucher = jml_voucher - %d, 
                     sisa_voucher = sisa_voucher - %d 
                 WHERE idwp = %d",
                $commission_points,
                $commission_points,
                $sponsor_id
            ));
            
            insert_commission_log($sponsor_id, 'wp_affiliate', 'Komisi Instant', 'Revoke Komisi Order  #'. $order_id .' from '. $sponsor_username , -$commission_points, 'uang', 'success');


            error_log("Reverted commission points: $commission_points for sponsor username: $sponsor_username");

            // Optionally, log the reversal
            $wpdb->query($wpdb->prepare(
                "INSERT INTO cb_laporan (tanggal, transaksi, kredit, komisi, keterangan, id_user, id_sponsor, id_order) 
                 VALUES (NOW(), %s, 0, %d, 'cbaff reversal', %d, %d, %d)",
                "Pengembalian komisi dari pembatalan order $order_id oleh sponsor $sponsor_username",
                -$commission_points,
                $user_id,
                $sponsor_id,
                $order_id
            ));
        } else {
            error_log("No commission points found for order ID: $order_id and sponsor username: $sponsor_username");
        }
    } else {
        error_log("No sponsor found for user ID: $user_id");
    }
}

// calculate commission from poin level external
function calculate_bonus_from_point_external($user_id, $points, $website, $ref_id ){
    error_log("Handling commission for external points amount ".$points." from ". $website. "with order id ". $ref_id);

    $commission_settings = array(
        'Free Member' => get_option('free_member_commission_instant', 20),
        'Starter'     => get_option('starter_commission_instant', 5),
        'Basic'       => get_option('basic_commission_instant', 10),
        'Premium'     => get_option('premium_commission_instant', 15),
        'Prestige'    => get_option('prestige_commission_instant', 20)
    );


    calculate_commission_convert_point($user_id, $commission_settings, $points, $website, $ref_id);
}

function calculate_commission_convert_point($user_id, $commission_settings, $point_earned, $website, $ref_id) {
    global $wpdb;

    // Hitung poin level dari pesanan
    $mycred = mycred(); // Pastikan myCred tersedia
    $point_type = 'point_level';
   // $point_type = 'point_upgrade'; -> harus di di ubah lagi
   
    error_log("Points earned by user $user_id: $point_earned");

    $name = $wpdb->get_var($wpdb->prepare(
        "SELECT nama FROM wp_member WHERE idwp = %d",
        $user_id
    ));
    error_log("User name: $name");

    $sponsor_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id_referral FROM wp_member WHERE idwp = %d",
        $user_id
    ));
    error_log("Sponsor ID: $sponsor_id");

    if ($sponsor_id) {
        $sponsor_username = $wpdb->get_var($wpdb->prepare(
            "SELECT username FROM wp_users WHERE ID = %d",
            $sponsor_id
        ));
        $sponsor_points = intval(get_user_meta($sponsor_id, 'point_level', true));
        $sponsor_points_chek = intval(get_user_meta($sponsor_id, 'point_upgrade', true));
        error_log("Sponsor points: $sponsor_points");

        $sponsor_level = get_sponsor_level($sponsor_points_chek);
        error_log("Sponsor level: $sponsor_level");

        // Hitung komisi berdasarkan level sponsor
        $commission_percentage = $commission_settings[$sponsor_level];
        $commission_points = ($point_earned * $commission_percentage) / 100;

        // Update voucher sponsor
        $wpdb->query($wpdb->prepare(
            "UPDATE wp_member 
             SET jml_voucher = jml_voucher + %d, 
                 sisa_voucher = sisa_voucher + %d 
             WHERE idwp = %d",
            $commission_points, 
            $commission_points, 
            $sponsor_id
        ));
        insert_commission_log($sponsor_id, 'wp_affiliate', 'Komisi Instan', 'Instant Komisi Order external Point  #'. $ref_id .' from '. $name , $commission_points, 'uang', 'success');


        error_log("Updated sponsor voucher. Commission points: $commission_points");

        // Log komisi
        log_commission($user_id, $sponsor_id, $point_earned, $commission_points, $name, $ref_id);
    }
}
?>