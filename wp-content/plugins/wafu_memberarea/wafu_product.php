<?php
add_action('woocommerce_product_options_general_product_data', 'add_grup_wafu_field');
function add_grup_wafu_field() {
    global $wpdb;

    $results = $wpdb->get_results("SELECT grup_id, grup_nama FROM wafu_grup");
    $results2 = $wpdb->get_results("SELECT grup_id, grup_nama FROM wafu_grup_mlm");
    if (empty($results)) {
        echo '<p class="description">' . __('Tidak ada grup wafu yang tersedia.', 'woocommerce') . '</p>';
        return;
    }
    // Merge $results and $results2
    $results = array_merge($results, $results2);
    echo '<div class="options_group">';
    $selected = get_post_meta(get_the_ID(), '_grup_wafu', true);

    woocommerce_wp_select(array(
        'id' => '_grup_wafu',
        'label' => __('Grup Wafu', 'woocommerce'),
        'options' => array_reduce($results, function($carry, $row) {
            $carry[$row->grup_id] = $row->grup_nama;
            return $carry;
        }, array('' => 'Pilih grup wafu')),
        'value' => $selected,
        'desc_tip' => true,
        'description' => __('Pilih grup wafu untuk produk ini.', 'woocommerce'),
    ));
    echo '</div>';
}
add_action('woocommerce_process_product_meta', 'save_grup_wafu_field');
function save_grup_wafu_field($post_id) {
    if (isset($_POST['_grup_wafu'])) {
        update_post_meta($post_id, '_grup_wafu', sanitize_text_field($_POST['_grup_wafu']));
    }
}


add_action('dokan_product_edit_after_product_tags', 'dokan_add_grup_wafu_field');
function dokan_add_grup_wafu_field($post) {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT grup_id, grup_nama FROM wafu_grup_mlm WHERE camp_user_wp = %d",
        $user_id
    ));

    $selected = get_post_meta($post->ID, '_grup_wafu', true);

    echo '<div class="dokan-form-group">';
    echo '<label for="grup_wafu">' . __('Grup Wafu', 'woocommerce') . '</label>';
    echo '<select name="grup_wafu" id="grup_wafu" class="dokan-form-control">';
    echo '<option value="">Pilih grup wafu</option>';

    foreach ($results as $grup) {
        echo '<option value="' . esc_attr($grup->grup_id) . '" ' . selected($selected, $grup->grup_id, false) . '>' . esc_html($grup->grup_nama) . '</option>';
    }

    echo '</select>';
    echo '</div>';
}

add_action('dokan_process_product_meta', 'save_dokan_grup_wafu_field');

function save_dokan_grup_wafu_field($post_id) {
    if (isset($_POST['grup_wafu'])) {
        update_post_meta($post_id, '_grup_wafu', sanitize_text_field($_POST['grup_wafu']));
    }
}



add_action('admin_footer', function () {
    echo "<script>jQuery('#_grup_wafu').select2();</script>";
});


add_action('woocommerce_order_status_completed', 'send_order_complete_webhook');
function send_order_complete_webhook($order_id) {
    if (!$order_id) return;

    $order = wc_get_order($order_id);
    if (!$order instanceof WC_Order) return;

    $user_id = $order->get_user_id();
    if (!$user_id) return;

    // Ambil nomor WhatsApp pembeli
    $whatsapp_number = get_user_meta($user_id, '_whatsapp_number', true);
    // Ambil user_login dari user_id
    $user_login = get_userdata($user_id)->user_login;
    if (empty($user_login)) return;
    if (empty($whatsapp_number)) return;

    // Ambil item pertama dari order
    $items = $order->get_items();
    $first_item = reset($items);
    if (!$first_item) return;

    $product_id = $first_item->get_product_id();

    $grup_wafu_data = get_grup_wafu_data_by_product($product_id);
    if (!$grup_wafu_data) return;

    // Ambil vendor_id berdasarkan product_id
    if (!function_exists('get_camp_user_wp_by_product')) return;
    $vendor_id = get_camp_user_wp_by_product($product_id);
    if (empty($vendor_id)) return;

    // Ambil _grup_wafu dari salah satu produk
    $grup_wafu = '';
    foreach ($items as $item) {
        $pid = $item->get_product_id();
        $grup_wafu_meta = get_post_meta($pid, '_grup_wafu', true);
        if (!empty($grup_wafu_meta)) {
            $grup_wafu = $grup_wafu_meta;
            break;
        }
    }

    if (empty($grup_wafu)) return;

    // Ambil nomor WhatsApp vendor
    $author_whatsapp_number = get_user_meta($vendor_id, '_whatsapp_number', true);
    if (empty($author_whatsapp_number)) return;

    // Buat pesan
    $pesan = wafu_generate_link_change_group_text(
        $vendor_id,
        $grup_wafu_data['grup_code'],
        $user_login,
        ''
    );

    // Siapkan payload
    $data = array(
        'pengirim' => $whatsapp_number,
        'device'   => $author_whatsapp_number,
        'pesan'    => $pesan,
    );

  
   // log_webhook_request($vendor_id, $data);
    error_log("vendor id wafu_member ". $data);

    // Webhook URL
    $webhook_url = plugins_url('wafu_memberarea/wafu_webhook_internal.php') . '?user_id=' . $vendor_id;

    // Kirim request ke webhook
   wafu_proses($whatsapp_number, $pesan, $vendor_id);
}


function get_grup_wafu_data_by_product($product_id) {
    global $wpdb;

    // Ambil nilai _grup_wafu dari meta produk
    $grup_wafu_id = get_post_meta($product_id, '_grup_wafu', true);

    if (!$grup_wafu_id) {
        return false; // Tidak ada grup wafu terpilih
    }

    // Ambil seluruh data berdasarkan grup_id
    $grup_data = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM wafu_grup_mlm WHERE grup_id = %d",
            $grup_wafu_id
        ),
        ARRAY_A // Kembalikan sebagai array asosiatif
    );

    return $grup_data;
}

function get_camp_user_wp_by_product($product_id) {
    global $wpdb;

    // Ambil nilai _grup_wafu dari meta produk
    $grup_wafu_id = get_post_meta($product_id, '_grup_wafu', true);

    if (!$grup_wafu_id) {
        return false; // Tidak ada grup wafu terpilih
    }

    // Ambil camp_user_wp dari tabel wafu_grup_mlm berdasarkan grup_id
    $camp_user_wp = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT camp_user_wp FROM wafu_grup_mlm WHERE grup_id = %d",
            $grup_wafu_id
        )
    );

    return $camp_user_wp;
}

function wafu_generate_link_change_group_text($camp_user_wp, $group_code, $member_name, $customdata = '') {
	$user_setting = get_user_meta($camp_user_wp, 'wafusetting', true);
	$sender_number = wafu_format_mlm($user_setting['sender']);
	$query_string = 'join' . '#'.$group_code .'#'. $member_name . $customdata;
	$link = $query_string;
	return $link;
}

function wafu_proses($nohp, $pesan, $user_id) {
    global $wpdb;
    print("<pre>");
    print_r($nohp);
    print_r($pesan);
    print_r($user_id);
    print("</pre>");
  
    $vendor_id = $user_id;
    if (isset($nohp) && $nohp != '' && isset($pesan) && $pesan != '') {	
        $nohp = wafu_format_mlm($nohp);	
        $pecah = explode('#',$pesan);
        $kode = preg_replace("/[^a-zA-Z0-9\s]/", "", $pecah[1]);
        $kode2 = preg_replace("/[^a-zA-Z0-9\s]/", "", $pecah[2]);
        $data = $wpdb->get_row("SELECT * FROM `wafu_member_mlm` WHERE `member_wa`=".$nohp);
        print("<pre>");
        print_r($pecah);
        print("</pre>");
       
        # Cek apakah kode Unsubscribe
        if (strtolower($kode) == $wafusetting['kode_unsubscribe'] && isset($kode2) && $kode2 != '') {
            # Unsubscribe Start
            $grup_id = $wpdb->get_var("SELECT `grup_id` FROM `wafu_grup_mlm` WHERE `grup_code`='".strtolower($kode2)."'");
            if (isset($grup_id) && is_numeric($grup_id)) {				
                $wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$data->member_id." AND `grup_id`=".$grup_id);
                if ($wpdb->last_error !== '') {					    
                    $wpdb->print_error();
                } else {
                    if (isset($wafusetting['pesan_unsubscribe']) && $wafusetting['pesan_unsubscribe'] != '') {
                        $kirimpesan = $wafusetting['pesan_unsubscribe'];
                    } else {
                        $kirimpesan = 'Unsubscribe berhasil! Nomor anda telah dihapus dari daftar subscriber kami. ';
                    }
                }
            } else {
                if (isset($wafusetting['salah_kode']) && $wafusetting['salah_kode'] != '') {
                    $kirimpesan = $wafusetting['salah_kode'];
                } else {
                    $kirimpesan = 'Maaf, kode grup yang anda masukkan salah';
                }
            }
            # Unsubscribe End
        } elseif ($kode != '') {
            # Add Nomor Baru Start		
            if (isset($data->member_id)) {
                $member_id = $data->member_id;
                $nama = $data->member_nama;
                # UPDATE COUNTER
                if (isset($pecah[2]) && $pecah[2] != '') {			
                    $nama = $wpdb->_real_escape($pecah[2]);
                    $wpdb->query("UPDATE `wafu_member_mlm` SET `member_nama`='".$nama."',`camp_user_wp`=".$user_id.",`member_count`=`member_count`+1 WHERE `member_id`=".$member_id);
                } else {
                    $wpdb->query("UPDATE `wafu_member_mlm` SET `camp_user_wp`=".$user_id.",`member_count`=`member_count`+1 WHERE `member_id`=".$member_id);
                }
                print("<pre>");
              
                print_r('*888888*');
                print_r($pecah[2]);
                print("</pre>");
               
            } else {
                
                $pecah = explode('#',$pesan);
                print("<pre>");
              
                print_r($pecah);
                print("</pre>");
                if (isset($pecah[2]) && $pecah[2] != '') {
                    $nama = $wpdb->_real_escape($pecah[2]);
                } else {
                    $nama = '';
                }
              
               
                $wpdb->query("INSERT INTO `wafu_member_mlm` (`member_nama`,`member_wa`,`member_tglgabung`,`member_count`,`camp_user_wp`) VALUE 
                    ('".$nama."','".$nohp."','".current_time('Y-m-d H:i:s')."',1, '".$vendor_id."')");
                $member_id = $wpdb->insert_id;
              
               
            }
            # Add Nomor Baru End
    
            # Cek apakah kode grup
            $grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm` WHERE `grup_code`='".strtolower($kode)."' AND `camp_user_wp`=".$user_id);
            if (isset($grup->grup_id)) {
                # Subscribe Start
                $data = $wpdb->get_var("SELECT COUNT(*) FROM `wafu_gruptomember_mlm` 
                                        WHERE `grup_id`=".$grup->grup_id." AND `member_id`=".$member_id." AND `camp_user_wp`=".$user_id);
                if ($data > 0) {
                    if (isset($wafusetting['pesan_terdaftar']) && $wafusetting['pesan_terdaftar'] != '') {
                        $kirimpesan = $wafusetting['pesan_terdaftar'];
                    } else {
                        $kirimpesan = 'Nomor ini telah terdaftar sebelumnya.';
                    }
                } else {
                    # INPUT DATA SUBSCRIBER START
                    if ($grup->grup_custom != '') {
                        $grupcustom = explode("\n",$grup->grup_custom);
                        $i = 3;
                        foreach ($grupcustom as $grupcustom) {
                            if (isset($pecah[$i])) {
                                $customdata[trim($grupcustom)] = $pecah[$i];
                                $i++;
                            }
                        }
                        if($customdata != null){
                            if (count($customdata) > 0) {
                                $dbcustom = serialize($customdata);
                            } else {
                                $dbcustom = '';
                            }
                        } else{
                            $dbcustom = '';
                        }
                      
                    } else {
                        $dbcustom = '';
                    }
    
                    # Dapatkan pesan selamat datang dan next post
                    //$data = $wpdb->get_results("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$grup->grup_id." AND `camp_user_wp` =' ".$user_id."' ORDER BY `camp_sort` LIMIT 0,2");
                    $data = $wpdb->get_results("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$grup->grup_id." ORDER BY `camp_sort` LIMIT 0,2");
                    if (count($data) > 1) {
                        # Ada 2 campaign siap kirim
                        $i = 0;
                        foreach ($data as $camp) {
                            if ($i == 0) {
                                if ($nama != '') {
                                    $konten = str_replace('[nama]',$nama,$camp->camp_content);
                                } else {
                                    if (isset($wafusetting['nama_kosong'])) {
                                        $konten = str_replace('[nama]',$wafusetting['nama_kosong'],$camp->camp_content);	
                                    } else {
                                        $konten = str_replace('[nama]','',$camp->camp_content);	
                                    }										
                                }
                                if (isset($customdata) && count($customdata) > 0) {
                                    foreach ($customdata as $key => $value) {									
                                        $konten = str_replace('['.$key.']',$value,$konten);
                                    }
                                }
                                $kirimpesan = $konten;
                                $gambar = $camp->camp_image;
                            } elseif ($i == 1) {
                                switch ($camp->camp_periode) {
                                    case 1:
                                        $periode = ' days';
                                        break;
                                    case 2:
                                        $periode = ' hours';
                                        break;
                                    case 3:
                                        $periode = ' minutes';
                                        break;							
                                }
                                $delay = '+'.intval($camp->camp_delay).$periode;
                                $wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
                                    (`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`,`camp_user_wp`) VALUES 
                                    (".$member_id.",".$grup->grup_id.",".$camp->camp_id.",
                                    '".wp_date('Y-m-d H:i:s',strtotime($delay))."',0,1,'".$dbcustom."',
                                    '".wp_date('Y-m-d H:i:s')."','".$user_id."')");
                                if ($wpdb->last_error !== '') {					    
                                    $wpdb->print_error();
                                } elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
                                    // Auto Unsubscribe
                                    $unsub = unserialize($grup->grup_unsubscribe);
                                    $listgrup = implode(',', $unsub);
                                    $wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
                                    if ($wpdb->last_error !== '') {					    
                                        $wpdb->print_error();
                                    }
                                }
                            }
                            $i++;
                        }
                    } elseif (count($data) == 1) {
                        # Cuma ada 1 campaign doang
                        foreach ($data as $camp) {
                            if ($nama != '') {
                                $konten = str_replace('[nama]',$nama,$camp->camp_content);
                            } else {
                                if (isset($wafusetting['nama_kosong'])) {
                                    $konten = str_replace('[nama]',$wafusetting['nama_kosong'],$camp->camp_content);	
                                } else {
                                    $konten = str_replace('[nama]','',$camp->camp_content);	
                                }										
                            }
    
                            if (isset($customdata) && count($customdata) > 0) {
                                foreach ($customdata as $key => $value) {
                                    $konten = str_replace('['.$key.']',$value,$konten);
                                }
                            }
                            $kirimpesan = $konten;
                            $gambar = $camp->camp_image;
                            
                            $wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
                                    (`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`,`camp_user_wp`) VALUES 
                                    (".$member_id.",".$grup->grup_id.",0,'0000-00-00 00:00:00',
                                    0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."', '".$user_id."')");
                            if ($wpdb->last_error !== '') {					    
                                $wpdb->print_error();
                            } elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
                                // Auto Unsubscribe
                                $unsub = unserialize($grup->grup_unsubscribe);
                                $listgrup = implode(',', $unsub);
                                $wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
                                if ($wpdb->last_error !== '') {
                                    $wpdb->print_error();
                                }
                            }
                        }
                    } else {
                        # Gak ada Campaign Blas
                        $wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
                                (`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
                                (".$member_id.",".$grup->grup_id.",0,'0000-00-00 00:00:00',
                                0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
                        if ($wpdb->last_error !== '') {					    
                            $wpdb->print_error();
                        } elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
                            // Auto Unsubscribe
                            $unsub = unserialize($grup->grup_unsubscribe);
                            $listgrup = implode(',', $unsub);
                            $wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
                            if ($wpdb->last_error !== '') {
                                $wpdb->print_error();
                            }
                        }
                    }
    
                    # INPUT DATA SUBSCRIBER END
                }
                # Subscribe End
            } else {
                ###### Auto Chat Start ######
                $pesan = strtolower($pesan);
                $key = $wpdb->get_results("SELECT * FROM `wafu_chat_mlm` where `camp_user_wp`=".$user_id);
                if (count($key) > 0) {
                    foreach ($key as $key) {
                        if (isset($ketemu)) {
                            break;
                        } else {
                            if (strpos($key->chat_key, '|') !== false) {
                                $exp = explode('|', $key->chat_key);
                                if (count($exp) > 0) {
                                    foreach ($exp as $exp) {
                                        if (strpos($pesan,$exp)!== false) {
                                            $kirimpesan = $key->chat_jawab;
                                            $ketemu = 1;
                                        }
                                    }
                                }
                            } elseif (strpos($key->chat_key, '&') !== false) {
                                $exp = explode('&', $key->chat_key);
                                $itg = count(array_intersect($exp, explode(" ", preg_replace("/[^A-Za-z0-9' -]/", "", $pesan))));
                                if (count($exp) == $itg) {
                                    $kirimpesan = $key->chat_jawab;
                                }
                            } elseif (strpos($pesan,$key->chat_key)!== false) {
                                $kirimpesan = $key->chat_jawab;
                                $ketemu = 1;
                            } 
                        }
                    }
                }
    
                if (isset($kirimpesan) && $nama != '') {
                    $kirimpesan = str_replace('[nama]',$nama,$kirimpesan);
                } else {
                    if (isset($wafusetting['nama_kosong'])) {
                        $kirimpesan = str_replace('[nama]',$wafusetting['nama_kosong'],$kirimpesan);	
                    } else {
                        $kirimpesan = str_replace('[nama]','',$kirimpesan);	
                    }										
                }
                ###### Auto Chat End ######
            }
        }
    
        if (isset($kirimpesan) && $kirimpesan != '') {
            if (!isset($gambar)) {
                $gambar = '';
            }
           // wafu_kirim_mlm($nohp,$pesan);
            wafu_kirim_mlm($nohp,$kirimpesan,$gambar,$user_id);
        }
    
        if (isset($wafusetting['wafu_test']) && $wafusetting['wafu_test'] == 1) {
            wafu_kirim_mlm($nohp,$pesan, $user_id);
        }	
        
    }
}

