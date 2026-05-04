<?php 
$wp_load = substr( dirname( __FILE__ ), 0, strpos( dirname( __FILE__ ), 'wp-content' ) ) . 'wp-load.php';
if ( ! empty( $wp_load ) && file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	die('Could not load WordPress');
}

$wafusetting = get_option('wafusetting');
include('service/'.$wafusetting['service'].'hook.php');

if (isset($nohp) && $nohp != '' && isset($pesan) && $pesan != '') {	
	$nohp = wafu_format($nohp);	
	$pecah = explode('#',$pesan);
	$kode = preg_replace("/[^a-zA-Z0-9\s]/", "", $pecah[0]);
	$kode2 = preg_replace("/[^a-zA-Z0-9\s]/", "", $pecah[1]);
	$data = $wpdb->get_row("SELECT * FROM `wafu_member` WHERE `member_wa`=".$nohp);
	# Cek apakah kode Unsubscribe
	if (strtolower($kode) == $wafusetting['kode_unsubscribe'] && isset($kode2) && $kode2 != '') {
		# Unsubscribe Start
		$grup_id = $wpdb->get_var("SELECT `grup_id` FROM `wafu_grup` WHERE `grup_code`='".strtolower($kode2)."'");
		if (isset($grup_id) && is_numeric($grup_id)) {				
			$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$data->member_id." AND `grup_id`=".$grup_id);
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
			if (isset($pecah[1]) && $pecah[1] != '') {			
				$nama = $wpdb->_real_escape($pecah[1]);
				$wpdb->query("UPDATE `wafu_member` SET `member_nama`='".$nama."',`member_count`=`member_count`+1 WHERE `member_id`=".$member_id);
			} else {
				$wpdb->query("UPDATE `wafu_member` SET `member_count`=`member_count`+1 WHERE `member_id`=".$member_id);
			}
		} else {
			$pecah = explode('#',$pesan);
			if (isset($pecah[1]) && $pecah[1] != '') {
				$nama = $wpdb->_real_escape($pecah[1]);
			} else {
				$nama = '';
			}

			$wpdb->query("INSERT INTO `wafu_member` (`member_nama`,`member_wa`,`member_tglgabung`,`member_count`) VALUE 
				('".$nama."','".$nohp."','".current_time('Y-m-d H:i:s')."',1)");
			$member_id = $wpdb->insert_id;
		}
		# Add Nomor Baru End

		# Cek apakah kode grup
		$grup = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_code`='".strtolower($kode)."'");
		if (isset($grup->grup_id)) {
			# Subscribe Start
			$data = $wpdb->get_var("SELECT COUNT(*) FROM `wafu_gruptomember` 
									WHERE `grup_id`=".$grup->grup_id." AND `member_id`=".$member_id);
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
					$i = 2;
					foreach ($grupcustom as $grupcustom) {
						if (isset($pecah[$i])) {
							$customdata[trim($grupcustom)] = $pecah[$i];
							$i++;
						}
					}
					if (count($customdata) > 0) {
						$dbcustom = serialize($customdata);
					} else {
						$dbcustom = '';
					}
				} else {
					$dbcustom = '';
				}

				# Dapatkan pesan selamat datang dan next post
				$data = $wpdb->get_results("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$grup->grup_id." 
					ORDER BY `camp_sort` LIMIT 0,2");
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
							$wpdb->query("INSERT INTO `wafu_gruptomember` 
								(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
								(".$member_id.",".$grup->grup_id.",".$camp->camp_id.",
								'".wp_date('Y-m-d H:i:s',strtotime($delay))."',0,1,'".$dbcustom."',
								'".wp_date('Y-m-d H:i:s')."')");
							if ($wpdb->last_error !== '') {					    
							    $wpdb->print_error();
							} elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
								// Auto Unsubscribe
								$unsub = unserialize($grup->grup_unsubscribe);
								$listgrup = implode(',', $unsub);
								$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
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
						
						$wpdb->query("INSERT INTO `wafu_gruptomember` 
								(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
								(".$member_id.",".$grup->grup_id.",0,'0000-00-00 00:00:00',
								0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
						if ($wpdb->last_error !== '') {					    
						    $wpdb->print_error();
						} elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
							// Auto Unsubscribe
							$unsub = unserialize($grup->grup_unsubscribe);
							$listgrup = implode(',', $unsub);
							$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
							if ($wpdb->last_error !== '') {
							    $wpdb->print_error();
							}
						}
					}
				} else {
					# Gak ada Campaign Blas
					$wpdb->query("INSERT INTO `wafu_gruptomember` 
							(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
							(".$member_id.",".$grup->grup_id.",0,'0000-00-00 00:00:00',
							0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
					if ($wpdb->last_error !== '') {					    
					    $wpdb->print_error();
					} elseif (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
						// Auto Unsubscribe
						$unsub = unserialize($grup->grup_unsubscribe);
						$listgrup = implode(',', $unsub);
						$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
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
			$key = $wpdb->get_results("SELECT * FROM `wafu_chat`");
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

		wafu_kirim($nohp,$kirimpesan,$gambar);
	}

	if (isset($wafusetting['wafu_test']) && $wafusetting['wafu_test'] == 1) {
		wafu_kirim($nohp,$pesan);
	}	
	
}