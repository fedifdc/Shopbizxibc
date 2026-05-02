<?php
function reset_user_points_with_sponsor_commission() {
    global $wpdb;

     $commission_settings = array(
       	'Free Member' => get_option('free_member_commission', 20), 
        'Starter'  => get_option('starter_commission', 5), 
        'Basic'    => get_option('basic_commission', 10), 
        'Premium'  => get_option('premium_commission', 15),
        'Prestige' => get_option('prestige_commission', 20),
        'Reset'    => get_option('reset_user_commission',10)
    );


    $users = get_users();
    foreach ($users as $user) {
        $user_id = $user->ID;

        $is_member = get_user_meta($user_id, 'shopbiz_user_id', true);
        
        if ($is_member) {
            continue;
        }

        $registered_date_utc = new DateTime(get_userdata($user_id)->user_registered, new DateTimeZone('UTC'));
        $current_date_utc = new DateTime('now', new DateTimeZone('UTC'));
        $date_diff = $current_date_utc->diff($registered_date_utc)->days;

        error_log("Processing User ID: {$user_id}, Date Diff: {$date_diff}");

        if ($date_diff >= 31) { 
            $mycred = mycred('point_level');
            $point_level = intval(get_user_meta($user_id, 'point_level', true));
// 			$is_free = $wpdb->get_var($wpdb->prepare("SELECT membership FROM wp_member WHERE idwp = %d", $user_id));
            if ($point_level > 0) {
                $name = $wpdb->get_var($wpdb->prepare(
                    "SELECT nama FROM wp_member WHERE idwp = %d",
                    $user_id
                    ));
                $sponsor_id = $wpdb->get_var($wpdb->prepare(
                    "SELECT id_referral FROM wp_member WHERE idwp = %d",
                    $user_id
                ));

                if ($sponsor_id) {
                    $sponsor_points = intval(get_user_meta($sponsor_id, 'point_level', true));
                    $sponsor_level = 'Starter';
                    if ($sponsor_points >= 5000000) {
                        $sponsor_level = 'Prestige';
                    } elseif ($sponsor_points >= 3000000) {
                        $sponsor_level = 'Premium';
                    } elseif ($sponsor_points >= 1000000) {
                        $sponsor_level = 'Basic';
                    } else {
                    	$sponsor_level = 'Free Member';
                    }

                    $commission_percentage = $commission_settings[$sponsor_level];
                    $point_level_percentage = $commission_settings['reset'];
                    $commission_points = ($point_level * $commission_percentage) / 100;
                    
                    $point_giveaway = ($point_level_percentage * $point_level);
                    $log_giveaway = apply_filters('mycred_woo_reward_log', '%plural% for not upgraded in 30days', time(), 'point_external');;
                    $ref_id_giveaway =time();
                    mycred_add('reset_point',$sponsor_id, $point_giveaway, $log_giveaway, $ref_id_giveaway, '', 'point_external');
                    
                    // Update the wp_member table for sponsor
                    $wpdb->query($wpdb->prepare(
                        "UPDATE wp_member 
                         SET jml_voucher = jml_voucher + %d, 
                             sisa_voucher = sisa_voucher + %d 
                         WHERE idwp = %d",
                        $commission_points, // Add to jml_voucher
                        $commission_points, // Add to sisa_voucher
                        $sponsor_id         // Where idwp = sponsor_id
                    ));
                    
                    // Get user points as kredit
                    $kredit = $point_level;  // Get user's point level from user meta
                    $jmlkomisi = $commission_points;  // Calculate the commission based on the user's point level
                    $transaksi = 'Komisi referral dari ' . $name; 
                    $id_user = $user_id; 
                    $id_referral = $sponsor_id; 
                    $order_id = time(); // Static order ID
                    
                    // Insert the record into cb_laporan table
                    $wpdb->query($wpdb->prepare(
                        "INSERT INTO `cb_laporan` (`tanggal`, `transaksi`, `kredit`, `komisi`, `keterangan`, `id_user`, `id_sponsor`, `id_order`) 
                        VALUES (NOW(), %s, %d, %d, 'cbaff', %d, %d, %d)",
                        $transaksi,  // %s for string
                        0,     // %d for kredit (user's point level)
                        $jmlkomisi,  // %d for komisi (calculated commission)
                        $id_user,    // %d for id_user
                        $id_referral, // %d for id_sponsor
                        $order_id    // Static order ID
                    ));
                    insert_commission_log($id_referral, 'wp_affiliate', 'reset_point', 'reset point for 30 days not upgrade [Level 1] from '.$name, $jmlkomisi, 'uang', 'success');
                    
                    // Add also the point based on settings for each level into next id_refferal untul based on setting
    
                    // Log the update
                    error_log("Updated wp_member for Sponsor ID: {$sponsor_id} with {$commission_points} added to jml_voucher and sisa_voucher.");

                    error_log("Sponsor ID: {$sponsor_id} earned {$commission_points} points from User ID: {$user_id}");
                    
                    $next_sponsor =$wpdb->get_var($wpdb->prepare(
                        "SELECT id_referral FROM wp_member WHERE idwp = %d",
                        $sponsor_id
                    ));
                    process_commission_for_levels($id_user, $point_level, $next_sponsor,$name);
                } 
                update_user_meta($user_id,'point_reset', $point_level);
                
                $reference = apply_filters('mycred_woo_reward_reference', 'reset_point', $order_id, 'point_level');
                $log = apply_filters('mycred_woo_reward_log', '%plural% for not upgraded in 30days', $order_id, 'point_level');
                $ref_id =  time(); 
                error_log("Ref id : ". $ref_id);
                error_log("Reference: $reference, Log: $log");
                mycred_add('reset_point', $user_id, -$point_level, $log, $ref_id, '', 'point_level');
                
                insert_commission_log($user_id, 'mycred', 'reset_point', 'reset point for 30 days not upgraded', -$point_level, 'point_level', 'success'); // ini pengurangan untuk user

                // $mycred->update_users_balance($user_id, -$point_level);
                 
                error_log("Reset points for User ID: {$user_id}, Points Reset: {$point_level}");
                $current_date_utc = current_time('mysql', true); // Get current date/time in WordPress format
                $wpdb->update(
                    $wpdb->users,
                    array('user_registered' => $current_date_utc), // Set new registration date
                    array('ID' => $user_id), // Condition to match the user
                    array('%s'), // Data format for user_registered
                    array('%d')  // Format for user ID
                );
                
                update_user_meta($user_id,'is_reset', true);

                // Log the reset of user_registered date
                error_log("User ID: {$user_id} registered date has been reset to current date.");
            } else{
                $current_date_utc = current_time('mysql', true); // Get current date/time in WordPress format
                $wpdb->update(
                    $wpdb->users,
                    array('user_registered' => $current_date_utc), // Set new registration date
                    array('ID' => $user_id), // Condition to match the user
                    array('%s'), // Data format for user_registered
                    array('%d')  // Format for user ID
                );
                error_log("User ID: {$user_id} registered date has been reset to current date.");

            }
        }
    }
}

// Hook the cron job
add_action('reset_user_points_cron_event', 'reset_user_points_with_sponsor_commission');
// Add also the point based on settings for each level into next id_refferal until based on setting
function process_commission_for_levels($user_id, $point_level, $sponsor_id,$from) {
    global $wpdb;

    // Retrieve commission rates from settings
    $commission_rates = get_option('sponsor_commission_data', []);
    $current_sponsor = $sponsor_id;
    $level = 2; // Start from Level 2

    foreach ($commission_rates as $rate) {
        if (!$current_sponsor) {
            break; // No more sponsors in the chain
        }

        // Get sponsor's name from the wp_member table
        $sponsor_name = $wpdb->get_var($wpdb->prepare(
            "SELECT nama FROM wp_member WHERE idwp = %d",
            $current_sponsor
        ));

        if (!$sponsor_name) {
            $sponsor_name = 'Unknown'; // Fallback if the name is not found
        }

        $commission_points = ($point_level * $rate) / 100;

        // Update the wp_member table for current sponsor
        $wpdb->query($wpdb->prepare(
            "UPDATE wp_member 
             SET jml_voucher = jml_voucher + %d, 
                 sisa_voucher = sisa_voucher + %d 
             WHERE idwp = %d",
            $commission_points,
            $commission_points,
            $current_sponsor
        ));
        
        $name = $wpdb->get_var($wpdb->prepare(
            "SELECT nama FROM wp_member WHERE idwp = %d",
            $user_id
        ));

        // Log the update with sponsor name
        error_log("Level {$level}: Sponsor ID {$current_sponsor} ({$sponsor_name}) earned {$commission_points} points.");

        // Insert into cb_laporan table
        $wpdb->query($wpdb->prepare(
            "INSERT INTO `cb_laporan` (`tanggal`, `transaksi`, `kredit`, `komisi`, `keterangan`, `id_user`, `id_sponsor`, `id_order`) 
             VALUES (NOW(), %s, %d, %d, 'cbaff', %d, %d, %d)",
            "Komisi Referal Level {$level} dari User {$from}",
            $point_level,
            $commission_points,
            $user_id,
            $current_sponsor,
            999999999 // Static order ID
        ));
        insert_commission_log($current_sponsor, 'wp_affiliate', 'reset_point', 'reset point for 30 days not upgrade [Level '. $level .']'.$name , $commission_points, 'uang', 'success');


        // Find the next sponsor (up the referral chain)
        $current_sponsor = $wpdb->get_var($wpdb->prepare(
            "SELECT id_referral FROM wp_member WHERE idwp = %d",
            $current_sponsor
        ));

        $level++; // Move to the next level
    }
}
// Schedule the cron job if not already scheduled
if (!wp_next_scheduled('reset_user_points_cron_event')) {
    wp_schedule_event(time(), 'daily', 'reset_user_points_cron_event');
}

