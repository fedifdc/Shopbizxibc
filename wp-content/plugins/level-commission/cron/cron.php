<?php
function process_level_commission_cron() {
    global $wpdb;

     $commission_settings = array(
       	'Free Member' => get_option('lc_free_member_commission', 20), 
        'Starter'  => get_option('lc_starter_commission', 5), 
        'Basic'    => get_option('lc_basic_commission', 10), 
        'Premium'  => get_option('lc_premium_commission', 15),
        'Prestige' => get_option('lc_prestige_commission', 20)
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

        error_log("LC Processing User ID: {$user_id}, Date Diff: {$date_diff}");

        $bonus_period = intval(get_option('lc_bonus_period_days', 30));

        if ($date_diff >= $bonus_period) { 
            $mycred = mycred('point_level');
            $total_points = intval(get_user_meta($user_id, 'point_level', true));
            $processed_points = intval(get_user_meta($user_id, 'lc_processed_points', true));
            $unprocessed_points = $total_points - $processed_points;

            if ($unprocessed_points > 0) {
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
                    if ($sponsor_points >= 5000000) {
                        $sponsor_level = 'Prestige';
                    } elseif ($sponsor_points >= 3000000) {
                        $sponsor_level = 'Premium';
                    } elseif ($sponsor_points >= 1000000) {
                        $sponsor_level = 'Basic';
                    } elseif ($sponsor_points >= 250000) {
                        $sponsor_level = 'Starter';
                    } else {
                    	$sponsor_level = 'Free Member';
                    }

                    $commission_percentage = $commission_settings[$sponsor_level];
                    $commission_points = ($unprocessed_points * $commission_percentage) / 100;
                    
                    // Update the wp_member table for sponsor
                    $wpdb->query($wpdb->prepare(
                        "UPDATE wp_member 
                         SET jml_voucher = jml_voucher + %d, 
                             sisa_voucher = sisa_voucher + %d 
                         WHERE idwp = %d",
                        $commission_points,
                        $commission_points,
                        $sponsor_id
                    ));
                    
                    $kredit = $unprocessed_points;
                    $jmlkomisi = $commission_points;
                    $transaksi = 'Komisi referral dari ' . $name; 
                    $id_user = $user_id; 
                    $id_referral = $sponsor_id; 
                    $order_id = time();
                    
                    // Insert the record into cb_laporan table
                    $wpdb->query($wpdb->prepare(
                        "INSERT INTO `cb_laporan` (`tanggal`, `transaksi`, `kredit`, `komisi`, `keterangan`, `id_user`, `id_sponsor`, `id_order`) 
                        VALUES (NOW(), %s, %d, %d, 'cbaff', %d, %d, %d)",
                        $transaksi,
                        0,
                        $jmlkomisi,
                        $id_user,
                        $id_referral,
                        $order_id
                    ));
                    insert_commission_log($id_referral, 'wp_affiliate', 'level_commission', 'level commission for 30 days not upgrade [Level 1] from '.$name, $jmlkomisi, 'uang', 'success');
                    
                    error_log("LC: Updated wp_member for Sponsor ID: {$sponsor_id} with {$commission_points} added to jml_voucher and sisa_voucher.");
                    error_log("LC: Sponsor ID: {$sponsor_id} earned {$commission_points} points from User ID: {$user_id}");
                    
                    $next_sponsor =$wpdb->get_var($wpdb->prepare(
                        "SELECT id_referral FROM wp_member WHERE idwp = %d",
                        $sponsor_id
                    ));
                    lc_process_commission_for_levels($id_user, $unprocessed_points, $next_sponsor, $name);
                } 
                
                // NO POINT DEDUCTION HERE (reset_point logic removed)
                
                error_log("LC: Evaluated {$unprocessed_points} new points for User ID: {$user_id}, but DID NOT reset points.");
                
                // Track the total points processed so they aren't calculated again next time
                update_user_meta($user_id, 'lc_processed_points', $total_points);
                
                $current_date_utc = current_time('mysql', true);
                $wpdb->update(
                    $wpdb->users,
                    array('user_registered' => $current_date_utc),
                    array('ID' => $user_id),
                    array('%s'),
                    array('%d')
                );
                
                update_user_meta($user_id,'lc_is_processed', true);

                error_log("LC: User ID: {$user_id} registered date has been reset to current date.");
            } else{
                $current_date_utc = current_time('mysql', true);
                $wpdb->update(
                    $wpdb->users,
                    array('user_registered' => $current_date_utc),
                    array('ID' => $user_id),
                    array('%s'),
                    array('%d')
                );
                error_log("LC: User ID: {$user_id} registered date has been reset to current date.");
            }
        }
    }
}

add_action('lc_daily_cron_event', 'process_level_commission_cron');

function lc_process_commission_for_levels($user_id, $point_level, $sponsor_id, $from) {
    global $wpdb;

    $commission_rates = get_option('lc_commission_data', []);
    $current_sponsor = $sponsor_id;
    $level = 2;

    foreach ($commission_rates as $rate) {
        if (!$current_sponsor) {
            break;
        }

        $sponsor_name = $wpdb->get_var($wpdb->prepare(
            "SELECT nama FROM wp_member WHERE idwp = %d",
            $current_sponsor
        ));

        if (!$sponsor_name) {
            $sponsor_name = 'Unknown';
        }

        $commission_points = ($point_level * $rate) / 100;

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

        error_log("LC Level {$level}: Sponsor ID {$current_sponsor} ({$sponsor_name}) earned {$commission_points} points.");

        $wpdb->query($wpdb->prepare(
            "INSERT INTO `cb_laporan` (`tanggal`, `transaksi`, `kredit`, `komisi`, `keterangan`, `id_user`, `id_sponsor`, `id_order`) 
             VALUES (NOW(), %s, %d, %d, 'cbaff', %d, %d, %d)",
            "Komisi Referal Level {$level} dari User {$from}",
            $point_level,
            $commission_points,
            $user_id,
            $current_sponsor,
            999999999
        ));
        insert_commission_log($current_sponsor, 'wp_affiliate', 'level_commission', 'level commission for 30 days not upgrade [Level '. $level .']'.$name , $commission_points, 'uang', 'success');

        $current_sponsor = $wpdb->get_var($wpdb->prepare(
            "SELECT id_referral FROM wp_member WHERE idwp = %d",
            $current_sponsor
        ));

        $level++;
    }
}

// Schedule the cron job at configured time daily if not already scheduled
if (!wp_next_scheduled('lc_daily_cron_event')) {
    $cron_time = get_option('lc_cron_execution_time', '11:30');
    $timezone = wp_timezone();
    $target_time = new DateTime($cron_time . ':00', $timezone);
    $now = new DateTime('now', $timezone);
    if ($now >= $target_time) {
        $target_time->modify('+1 day');
    }
    wp_schedule_event($target_time->getTimestamp(), 'daily', 'lc_daily_cron_event');
}
