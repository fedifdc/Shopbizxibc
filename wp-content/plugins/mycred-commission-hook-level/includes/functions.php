<?php

defined( 'ABSPATH' ) || exit;

add_action( 'mycred_load_hooks', 'mycred_load_commission_level_hook' );


function mycred_load_commission_level_hook() {
    if ( ! class_exists( 'MyCred_Commission_Hooks_Level' ) ) :
        //mycred_register_hook( 'commission_level_upgrade', 'MyCred_Commission_Hooks_Level' );
        class MyCred_Commission_Hooks_Level extends myCRED_Hook {

            public function __construct( $hook_prefs, $type ) {
                parent::__construct( array(
                    'id'       => 'commission_level_upgrade',
                    'defaults' => array(
                        'commission_level_percent' => array(
                            'Starter'  => 75,
                            'Basic'    => 50,
                            'Premium'  => 25,
                            'Prestige' => 20,
                          	'Free Member' => 1, 
                        )
                    )
                ), $hook_prefs, $type );
            }

            public function run() {

                // Hook into WooCommerce order completion or other events here
                // add_action('woocommerce_order_status_completed', array($this, 'award_commission_level_points'), 10, 1);
                add_action('woocommerce_order_status_completed', array($this, 'schedule_award_commission_level_points'), 10, 1);

                add_action('background_award_commission_level_points', array($this, 'award_commission_level_points'), 10, 1);

                // add_action('woocommerce_payment_complete', array($this, 'award_commission_level_points'), 10, 1);
				// Hook into order cancellation to revoke points
				
              	add_action( 'woocommerce_order_status_changed', function( $order_id, $old_status, $new_status ) {
                    if ( $new_status === 'completed' ) {
                        $order = wc_get_order( $order_id );
                        $order->update_meta_data( '_previous_status', $old_status );
                        $order->save();
                    }
                }, 10, 3 );
                
                add_action('revoke_sponsor_commission_level_cron', [$this,'revoke_sponsor_commission_level_points']);
                $this->run_revoke_hook();

                // add_action( 'woocommerce_thankyou', array($this, 'award_commission_level_points'), 10, 1);
            }
            
            // Schedule the function to run in the background
            public function schedule_revoke_sponsor_commission($order_id) {
                if (!wp_next_scheduled('revoke_sponsor_commission_level_cron', array($order_id))) {
                    wp_schedule_single_event(time() + 10, 'revoke_sponsor_commission_level_cron', array($order_id));
                }
            }
            
            // Hook into cron event
	        public function schedule_award_commission_level_points($order_id) {
                if (!wp_next_scheduled('background_award_commission_level_points', array($order_id))) {
                    wp_schedule_single_event(time() + 10, 'background_award_commission_level_points', array($order_id));
                }
            }
          	
          
          	public function run_revoke_hook() {
                // Mendapatkan semua status order
                $order_statuses = wc_get_order_statuses();

                foreach ($order_statuses as $status_key => $status_label) {
                    // Melewati status 'completed' karena ini adalah status asal
                    if ($status_key === 'wc-completed') {
                        continue;
                    }

                    // Menambahkan hook untuk setiap transisi dari 'completed' ke status lain
                    add_action(
                        'woocommerce_order_status_completed_to_' . str_replace('wc-', '', $status_key),
                        [$this, 'schedule_revoke_sponsor_commission'],
                        10,
                        1
                    );
                }
            }
            public function award_commission_level_points( $order_id ) {
                error_log("Starting award_commission_level_points for order_id: $order_id");
            
                $order = wc_get_order( $order_id );
                if ( ! $order ) {
                    error_log("Order not found for order_id: $order_id");
                    return;
                }
            
                $user_id = $order->get_user_id();
                error_log("User ID retrieved: $user_id");
              	$username = '';
            	if ($user_id) {
                    // Fetch the username for the user ID
                    $username = get_userdata($user_id)->user_login;
                    error_log("User ID retrieved: $user_id, Username: $username");
                } else {
                    error_log("User ID not found for the order.");
                }
                $types = mycred_get_types();
                error_log("Point types: " . print_r($types, true));
            	error_log("POINT TYPE PARENT: " . $this->mycred_type);
                foreach ( $types as $point_type => $point_type_label ) {
                    error_log("this mycred_type :  $this->mycred_type" );
                    error_log("Processing point type: $point_type");
            
                    if ( $point_type == $this->mycred_type ) {
                   // if (in_array($point_type, ['point_level','point_upgrade'])) {
                        
                        $mycred = mycred( $point_type );
                        $reference = apply_filters('mycred_woo_reward_reference', 'commission', $order_id, $point_type);
                        //$reference = apply_filters('mycred_woo_reward_reference', 'commission', $order_id, 'point_level');
                        $log = apply_filters('mycred_woo_reward_log', '%plural% commission for store purchase #' . $order_id . ' from '. $username , $order_id, $point_type);
                
                        error_log("Reference: $reference, Log: $log");
                
                        $sponsor_id = $this->get_sponsor( $user_id );
                        error_log("Sponsor ID: " . ( $sponsor_id ? $sponsor_id : "None" ));
                
                        if ( ! $sponsor_id ) {
                            error_log("No sponsor found for user_id: $user_id");
                            return;
                        }
                
                        //$points_earned = $this->get_user_points( $mycred, $user_id, $order, $point_type );
                        $points_earned = $this->get_user_points( $mycred, $user_id, $order,'point_level' );
                        error_log("Points earned by user $user_id: $points_earned");
                
                        $commission = $this->calculate_commission( $sponsor_id, $points_earned );
                        error_log("Commission calculated for sponsor $sponsor_id: $commission");
                
                        if ( $commission > 0 ) {
                            error_log("Awarding $commission points to sponsor $sponsor_id");
                            
                            mycred_add('commission', $sponsor_id, $commission, $log, $order_id, array('ref_type' => 'post'), $point_type);
                            insert_commission_log($sponsor_id, 'mycred', 'Komisi Order', 'Komisi Order Completed #'. $order_id .' from '. $username , $commission, $point_type, 'success');
    
                            
                            
                          	if($this->mycred_type == 'point_voucher'){
                          	    $this->handle_user_prizes($sponsor_id, $commission);
                            }
                          	
                          	
                        } else {
                            // insert_commission_log($sponsor_id, 'mycred', 'Komisi Order', 'Komisi Order Completed #'. $order_id .' from '. $username , 0, $point_type, 'success');
    
                            error_log("Commission is 0 or less, skipping reward.");
                        }
                    } else {
                        error_log("Skipping point type: $point_type");
                        continue;
                    }
            

                }
                error_log("Finished award_commission_level_points for order_id: $order_id");
            }
          
          	
			
			public function revoke_sponsor_commission_level_points($order_id) {
                error_log("Starting revoke_sponsor_commission_level_points for order: " . $order_id);

                $order = wc_get_order($order_id);
                if (!$order) {
                    error_log("Order not found for ID: " . $order_id);
                    return;
                }

               

                $user_id = $order->get_user_id();
                error_log("Processing for user ID: " . $user_id);
				$username = '';
            	if ($user_id) {
                    // Fetch the username for the user ID
                    $username = get_userdata($user_id)->user_login;
                    error_log("User ID retrieved: $user_id, Username: $username");
                } else {
                    error_log("User ID not found for the order.");
                }
                $types = mycred_get_types();
                error_log("Available point types: " . print_r($types, true));

                foreach ($types as $point_type => $point_type_label) {
                    error_log("Processing point type: " . $point_type);

                    if ($point_type != $this->mycred_type) {
                        error_log("Skipping point type: " . $point_type . " (not matching mycred_type: " . $this->mycred_type . ")");
                        continue;
                    }

                    $mycred = mycred($point_type);
                    $sponsor_id = $this->get_sponsor($user_id);

                    if (!$sponsor_id) {
                        error_log("No sponsor found for user ID: " . $user_id);
                        continue;
                    }
                    error_log("Found sponsor ID: " . $sponsor_id);

                    // Calculate the commission points previously awarded
                    $points_earned = $this->get_user_points($mycred, $user_id, $order, $point_type);
                    error_log("Points earned: " . $points_earned);

                    $commission = $this->calculate_commission($sponsor_id, $points_earned);
                    error_log("Commission calculated: " . $commission);

                    // Revoke the sponsor's commission points
                    if ($commission > 0) {
                        $log = __('Commission points revoked due to order cancellation for #' . $order_id . 'from ' . $username , 'mycred');
                        error_log("Revoking commission of " . $commission . " points from sponsor ID: " . $sponsor_id);

                        try {
                            $result = mycred_add('commission', $sponsor_id, -$commission, $log, $order_id, array('ref_type' => 'post'), $point_type);
                            insert_commission_log($sponsor_id, 'mycred', 'Komisi Order', 'Revoke Komisi Order #'. $order_id , -$commission, $point_type, 'success');

                            error_log("Revoke result: " . ($result ? "success" : "failed"));
                        } catch (Exception $e) {
                            error_log("Error while revoking points: " . $e->getMessage());
                            insert_commission_log($sponsor_id, 'mycred', 'Komisi Order', 'Revoke Komisi Order #'. $order_id , -$commission, $point_type, 'failed');

                        }
                    } else {
                        insert_commission_log($sponsor_id, 'mycred', 'Komisi Order', 'Revoke Komisi Order #'. $order_id , 0, $point_type, 'success');

                        error_log("No commission to revoke (commission <= 0)");
                    }
                }
                error_log("Completed revoke_sponsor_commission_level_points for order: " . $order_id);
            }

            private function get_sponsor( $user_id ) {
                global $wpdb;
                $table = $wpdb->prefix . 'member';
                return $wpdb->get_var( $wpdb->prepare( "SELECT id_referral FROM $table WHERE idwp = %d", $user_id ) );
            }

            private function get_user_points($mycred, $user_id, $order, $point_type) {
                    $items = $order->get_items();
                    $types = mycred_get_types();
                    $payout = 0;
                    $order_id = $order->get_id(); // Ambil order ID sekali di awal
                    
                    foreach ($items as $item) {
                        // Get the product ID or the variation ID
                        $product_id = absint($item['product_id']);
                        $variation_id = absint($item['variation_id']);
                
                        // Check if the product is an affiliate product
                        $is_affiliate_product = get_post_meta($variation_id ? $variation_id : $product_id, '_is_affiliate_product', true);
                
                        if ($is_affiliate_product === "yes" && $point_type === "point_level" ) {
                            $affiliate_reward_serialized = get_post_meta($order_id, 'poin_level_affiliate', true);
                            $affiliate_reward = maybe_unserialize($affiliate_reward_serialized);
                
                            if (is_array($affiliate_reward) && !empty($affiliate_reward)) {
                                // Jika array, jumlahkan semua reward
                                $payout += array_sum($affiliate_reward);
                            } elseif (is_numeric($affiliate_reward)) {
                                // Jika hanya satu nilai, langsung tambahkan
                                $payout += $affiliate_reward;
                            }
                            
                            continue; // Lewati ke iterasi berikutnya, tetap lanjutkan loop
                        } 
                
                        // Jika bukan produk afiliasi, lanjutkan perhitungan reward normal
                        //$reward_amount = mycred_get_woo_product_reward($product_id, $variation_id, $point_type);
                        $reward_amount = mycred_get_woo_product_reward($product_id, $variation_id,'point_level');
                        error_log("rewarda amount : $reward_amount");
                        $payout += ($mycred->number($reward_amount) * $item['qty']);
                    }
                
                    return $payout; // Return total payout
                }

			/*
            private function calculate_commission( $sponsor_id, $points ) {
                // Get sponsor's badge level and apply commission rules
                $badge_level = $this->get_badge_level( $sponsor_id );
            
                // Get the percentage based on the badge level from preferences
                $commission_level_percent = isset( $this->prefs['commission_level_percent'][ $badge_level ] ) 
                    ? (float) $this->prefs['commission_level_percent'][ $badge_level ]
                    : 0;
            
                // Cast points to float for safety
                $points = (float) $points;
            
                // Calculate commission points
                error_log("MYCRED COMMISION HOOK - Points: $points, Commission Percent: $commission_level_percent");
                error_log( "Points: " . var_export($points, true) );
                error_log( "Commission Percent: " . var_export($commission_level_percent, true) );
                return ( $points * $commission_level_percent ) / 100;
            }
            */
			private function calculate_commission($sponsor_id, $points) {
                $badge_level = $this->get_badge_level($sponsor_id);
                $commission_level_percent = $this->prefs['commission_level_percent'][$badge_level] ?? 0;
                return ($points * $commission_level_percent) / 100;
            }
          
			/*private function get_badge_level( $user_id ) {
				// This function should return the user's current badge level based on points
				// For example, you could query MyCred to get the user's current points and determine their badge
				$user_points = mycred_get_users_balance( $user_id, 'point_level' );

				if ( $user_points >= 5000000 ) {
					return 'Prestige';
				} elseif ( $user_points >= 3000000 ) {
					return 'Premium';
				} elseif ( $user_points >= 1000000 ) {
					return 'Basic';
				} elseif ( $user_points >= 250000 ) {
					return 'Starter';
				}

				return 'Free Member';
			}*/
          	private function get_badge_level($user_id) {
                $user_points = mycred_get_users_balance($user_id, 'point_upgrade');

                if ($user_points >= 5000000) return 'Prestige';
                if ($user_points >= 3000000) return 'Premium';
                if ($user_points >= 1000000) return 'Basic';
                if ($user_points >= 250000) return 'Starter';

                return 'Free Member';
            }
            
            public function check_and_assign_prize($user_id) {
              global $wpdb;

              $user_prizes_table = $wpdb->prefix . 'user_prizes';
              $prizes_table = $wpdb->prefix . 'prizes';

              // Ambil poin pengguna dari MyCred
              $current_points = mycred_get_users_balance($user_id,'point_voucher');

              // Periksa apakah pengguna sudah ada di tabel user_prizes
              $existing_entry = $wpdb->get_row($wpdb->prepare("
                  SELECT id, points FROM $user_prizes_table WHERE user_id = %d
              ", $user_id));

              if ($existing_entry) {
                  // Jika pengguna sudah ada, perbarui kolom points
                  $wpdb->update(
                      $user_prizes_table,
                      ['points' => $current_points],
                      ['id' => $existing_entry->id]
                  );

                  // Jika poin kurang dari 10, keluar dari fungsi
                  if ($current_points < 10) {
                      return;
                  }

                  // Jika sudah ada hadiah, keluar dari fungsi
                  if (!empty($existing_entry->prize_id)) {
                      return;
                  }
              } else {
                  // Jika pengguna belum ada, dan poin kurang dari 10, keluar dari fungsi
                  if ($current_points < 10) {
                      return;
                  }

                  // Jika pengguna belum ada, tambahkan data baru tanpa hadiah
                  $wpdb->insert(
                      $user_prizes_table,
                      [
                          'user_id' => $user_id,
                          'points' => $current_points,
                          'prize_id' => null,
                          'assigned_at' => null,
                      ]
                  );
              }

              // Ambil hadiah berdasarkan prioritas dan kuota
              $prize = $wpdb->get_row("
                  SELECT *
                  FROM $prizes_table
                  WHERE remaining_quota > 0
                  ORDER BY priority ASC
                  LIMIT 1
              ");

              // Jika tidak ada hadiah yang memenuhi syarat, keluar dari fungsi
              if (!$prize) {
                  return;
              }

              // Perbarui hadiah pengguna di tabel user_prizes
              $wpdb->update(
                  $user_prizes_table,
                  [
                      'prize_id' => $prize->id,
                      'assigned_at' => current_time('mysql'),
                  ],
                  ['user_id' => $user_id]
              );

              // Kurangi kuota hadiah
              $wpdb->update(
                  $prizes_table,
                  ['remaining_quota' => $prize->remaining_quota - 1],
                  ['id' => $prize->id]
              );
          }
          
			private function handle_user_prizes($user_id) {
              global $wpdb;

              // Nama tabel user_prizes
              $table_name = $wpdb->prefix . 'user_prizes';

              // Mendapatkan poin pengguna saat ini dan mengurangi 1
              $current_points = mycred_get_users_balance($user_id, 'point_voucher');
              $adjusted_points = $current_points; // Hindari poin negatif

              // Cek apakah user sudah ada di tabel user_prizes
              $user_prize = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", $user_id));

              if ($user_prize) {
                  // Jika user sudah ada, ganti poin dengan poin yang sudah dikurangi
                  $wpdb->update(
                      $table_name,
                      array('points' => $adjusted_points),
                      array('user_id' => $user_id),
                      array('%d'),
                      array('%d')
                  );
                  error_log("Updated points for user_id $user_id to $adjusted_points (adjusted from $current_points)");
              } else {
                  // Jika user belum ada, insert baru
                  $wpdb->insert(
                      $table_name,
                      array(
                          'user_id'      => $user_id,
                          'username'     => $this->get_username($user_id),
                          'display_name' => $this->get_display_name($user_id),
                          'points'       => $adjusted_points,
                          'prize_id'     => null, // Belum ada hadiah
                      ),
                      array('%d', '%s', '%s', '%d', '%s')
                  );
                  error_log("Inserted user_id $user_id with adjusted points $adjusted_points (adjusted from $current_points)");
              }

              // Cek dan berikan hadiah jika memenuhi syarat
              $this->check_and_assign_prize($user_id);
          }
          
          private function get_username($user_id) {
              $user = get_userdata($user_id);
              return $user ? $user->user_login : '';
          }

          /**
           * Get the display name of the user by user_id
           * @param int $user_id
           * @return string
           */
          private function get_display_name($user_id) {
              $user = get_userdata($user_id);
              return $user ? $user->display_name : '';
          }


			public function preferences() {
				// Preferences form to allow admins to configure commission percentage per badge
				$prefs = $this->prefs;
				?>
				<label for="commission_level_percent[Free Member]"><?php _e( 'Free Member Badge Commission (%)', 'mycred' ); ?></label>
				<input type="number" name="<?php echo $this->field_name( array( 'commission_level_percent', 'Free Member' ) ); ?>" id="commission_level_percent[Free Member]" value="<?php echo $prefs['commission_level_percent']['Free Member']; ?>" class="small-text" />%
				<br>
				<label for="commission_level_percent[Starter]"><?php _e( 'Starter Badge Commission (%)', 'mycred' ); ?></label>
				<input type="number" name="<?php echo $this->field_name( array( 'commission_level_percent', 'Starter' ) ); ?>" id="commission_level_percent[Starter]" value="<?php echo $prefs['commission_level_percent']['Starter']; ?>" class="small-text" />%
				<br>
				<label for="commission_level_percent[Basic]"><?php _e( 'Basic Badge Commission (%)', 'mycred' ); ?></label>
				<input type="number" name="<?php echo $this->field_name( array( 'commission_level_percent', 'Basic' ) ); ?>" id="commission_level_percent[Basic]" value="<?php echo $prefs['commission_level_percent']['Basic']; ?>" class="small-text" />%
				<br>
				<label for="commission_level_percent[Premium]"><?php _e( 'Premium Badge Commission (%)', 'mycred' ); ?></label>
				<input type="number" name="<?php echo $this->field_name( array( 'commission_level_percent', 'Premium' ) ); ?>" id="commission_level_percent[Premium]" value="<?php echo $prefs['commission_level_percent']['Premium']; ?>" class="small-text" />%
				<br>
				<label for="commission_level_percent[Prestige]"><?php _e( 'Prestige Badge Commission (%)', 'mycred' ); ?></label>
				<input type="number" name="<?php echo $this->field_name( array( 'commission_level_percent', 'Prestige' ) ); ?>" id="commission_level_percent[Prestige]" value="<?php echo $prefs['commission_level_percent']['Prestige']; ?>" class="small-text" />%
				<?php
			}

			public function sanitise_preferences( $data ) {
				// Sanitize the commission percentages
				$new_data = $data;
				$new_data['commission_level_percent']['Starter']  = absint( $data['commission_level_percent']['Starter'] );
				$new_data['commission_level_percent']['Basic']    = absint( $data['commission_level_percent']['Basic'] );
				$new_data['commission_level_percent']['Premium']  = absint( $data['commission_level_percent']['Premium'] );
				$new_data['commission_level_percent']['Prestige'] = absint( $data['commission_level_percent']['Prestige'] );
              	$new_data['commission_level_percent']['Free Member'] = absint( $data['commission_level_percent']['Free Member'] );
				return $new_data;
			}
        }
    endif;
}
