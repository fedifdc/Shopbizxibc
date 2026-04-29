<?php 
# CRON BROADCAST - Jalankan Setting Cron min. 2 menit sekali
# Cek apakah ada BC yg jalan

$jmlbcmin = 5;	# Jumlah nomor yang dikirim minimal
$jmlbcmax = 10;	# Jumlah nomor yang dikirim maksimal
$sleepmin = 4; 	# Jarak waktu antar pesan minimal (detik)
$sleepmax = 10;	# Jarak waktu antar pesan maksimal (detik)

$wp_load = substr( dirname( __FILE__ ), 0, strpos( dirname( __FILE__ ), 'wp-content' ) ) . 'wp-load.php';
if ( ! empty( $wp_load ) && file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	die('Could not load WordPress');
}

$jam = wp_date('H:i');
if (!jamaktif($jam)) { echo 'Di luar jam aktif'; die; }

$show = '';
$wafusetting = get_option('wafusetting');

$databc = $wpdb->get_row("SELECT * FROM `wafu_bc` WHERE `bc_status`=1");
if (isset($databc->bc_id)) {
	# Siapkan konten untuk dikirim
	$kontenbc = $databc->bc_isi;
	$gambar = $databc->bc_image;
	$show .= 'BC Siap kirim<br/>';
} else {
	# Cek apakah ada BC yang antri
	$databc = $wpdb->get_row("SELECT * FROM `wafu_bc` WHERE `bc_status`=0 AND `bc_tglkirim` <= '".wp_date('Y-m-d H:i:s')."'");
	if (isset($databc->bc_id)) {
		#Ubah status bc jadi running
		$kontenbc = $databc->bc_isi;
		$gambar = $databc->bc_image;
		$target = unserialize($databc->bc_target);
		$target = implode (", ", $target);
		$wpdb->query("UPDATE `wafu_bc` SET `bc_status`=1 WHERE `bc_id`=".$databc->bc_id);
		
		#Ubah status bc member target jadi 1
		$syarat = '';
		if (isset($databc->khususdone) && $databc->khususdone == 1) {
			$syarat .= " AND `gtm_nextid`=0";
		} 
		if (isset($databc->khususchat) && $databc->khususchat == 1) {
			$syarat .= " AND `member_id` IN (SELECT `member_id` FROM `wafu_member` WHERE `member_count` >=2)";
		}

		$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_bc` = 1 WHERE `gtm_status`=1 AND `grup_id` IN ('".$target."')".$syarat);
	} else {
		$show .= 'Tidak ada BC yg dikirim ('.wp_date('Y-m-d H:i:s').')<br/>';
	}
}

if (isset($kontenbc) && $kontenbc != '') {
	# Cari member yg Antri BC
	$send = $wpdb->get_results("SELECT * FROM `wafu_gruptomember` 
		LEFT JOIN `wafu_member` ON `wafu_gruptomember`.`member_id`=`wafu_member`.`member_id`
		WHERE `wafu_gruptomember`.`gtm_bc` = 1 LIMIT 0,".rand($jmlbcmin,$jmlbcmax));
	if (count($send) > 0) {
		foreach ($send as $send) {
			// code...		
			if (isset($send->member_wa) && $send->member_wa != '') {
				# Kirim WA
				$spintax = new wafu_spin();
				$konten = $spintax->process($kontenbc);
				
				if (isset($send->member_nama) && $send->member_nama != '') {
					$konten = str_replace('[nama]',$send->member_nama,$konten);
				} else {
					$konten = str_replace('[nama]','Sobat',$konten);
				}

				if (isset($send->gtm_customdata) && $send->gtm_customdata != '') {
					$customdata = unserialize($send->gtm_customdata);
					if (isset($customdata) && count($customdata) > 0) {
						foreach ($customdata as $key => $value) {									
							$konten = str_replace('['.$key.']',$value,$konten);
						}
					}
				}

				if (function_exists('wp_affiliasi')) {
					if (isset($send->member_idwp) && is_numeric($send->member_idwp) && $send->member_idwp > 0) {
						if (strpos($konten, '[member_') !== false || strpos($konten, '[sponsor_') !== false) {
							# Ambil data member dan replace shortcode
							$member = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `id_user`=".$send->member_idwp);
							$sponsor = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp`=".$member->id_referral);
							if (isset($member->id_user)) {
								foreach ($member as $key => $value) {
									if ($key != 'homepage') {
										$konten = str_replace('[member_'.$key.']', $value, $konten);
										if (isset($sponsor->$key)) {
											$konten = str_replace('[sponsor_'.$key.']', $sponsor->$key, $konten);
										}	
									} else {
										$lainlain = unserialize($value);
										$lainsponsor = unserialize($sponsor->homepage);
										if (isset($lainlain['whatsapp'])) {
											$konten = str_replace('[member_whatsapp]', formatwa($lainlain['whatsapp']), $konten);
										}
										if (isset($lainsponsor['whatsapp'])) {
											$konten = str_replace('[sponsor_whatsapp]', formatwa($lainsponsor['whatsapp']), $konten);
										}
										
										foreach ($lainlain as $lainkey => $lainvalue) {
											$konten = str_replace('[member_'.$lainkey.']', $lainvalue, $konten);
											if (isset($lainsponsor[$lainkey])) {
												$konten = str_replace('[sponsor_'.$lainkey.']', $lainsponsor[$lainkey], $konten);
											}
										}
									}
								}

								$affmember = urlaff($member->subdomain);
								$konten = str_replace('[member_urlaff]', $affmember, $konten);
								$affsponsor = urlaff($sponsor->subdomain);
								$konten = str_replace('[sponsor_urlaff]', $affsponsor, $konten);						
							}
						}
					}
				}
				
				# Update status bc member jadi 0
				$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_bc`=0 WHERE `member_id`=".$send->member_id);
				$show .= '<p>'.wp_date('Y-m-d H:i:s').': ['.$send->member_wa.'] '.$konten.'</p>';

				wafu_kirim($send->member_wa,$konten,$gambar);
				sleep(rand($sleepmin,$sleepmax));
			} else {
				# Ubah langsung jadi 0 biar gak nyangkut
				$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_bc`=0 WHERE `gtm_id`=".$send->gtm_id);
			}
		}
	} else {
		# Ubah status BC jadi done
		if (isset($databc->bc_id)) {
			$wpdb->query("UPDATE `wafu_bc` SET `bc_status`=2, `bc_tglselesai`='".wp_date('Y-m-d H:i:s')."' 
				WHERE `bc_id`=".$databc->bc_id);
			$show .= '<p>BC Done</p>';
		} else {
			$show .= 'BC ID tidak ada';
		}
		
	}
} else {
	$show .= 'konten tidak ada';
}

echo $show;