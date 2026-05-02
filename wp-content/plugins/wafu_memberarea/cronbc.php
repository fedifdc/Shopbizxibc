<?php 
global $wpdb;
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
//if (!jamaktif_mlm($jam)) { echo 'Di luar jam aktif'; die; }

$show = '';


$databc = $wpdb->get_row("SELECT * FROM `wafu_bc_mlm` WHERE `bc_status`=1");
if (isset($databc->bc_id)) {
	# Siapkan konten untuk dikirim
	$kontenbc = $databc->bc_isi;
	$gambar = $databc->bc_image;
	$show .= 'BC Siap kirim<br/>';
} else {
	# Cek apakah ada BC yang antri
	$databc = $wpdb->get_row("SELECT * FROM `wafu_bc_mlm` WHERE `bc_status`=0 AND `bc_tglkirim` <= '".wp_date('Y-m-d H:i:s')."'");
	if (isset($databc->bc_id)) {
		#Ubah status bc jadi running
		$kontenbc = $databc->bc_isi;
		$gambar = $databc->bc_image;
		$target = unserialize($databc->bc_target);
		$target = implode (", ", $target);
		$wpdb->query("UPDATE `wafu_bc_mlm` SET `bc_status`=1 WHERE `bc_id`=".$databc->bc_id);
		
		#Ubah status bc member target jadi 1
		$syarat = '';
		if (isset($databc->khususdone) && $databc->khususdone == 1) {
			$syarat .= " AND `gtm_nextid`=0";
		} 
		if (isset($databc->khususchat) && $databc->khususchat == 1) {
			$syarat .= " AND `member_id` IN (SELECT `member_id` FROM `wafu_member_mlm` WHERE `member_count` >=2)";
		}

		$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_bc` = 1 WHERE `gtm_status`=1 AND `grup_id` IN ('".$target."')".$syarat);
	} else {
		$show .= 'Tidak ada BC yg dikirim ('.wp_date('Y-m-d H:i:s').')<br/>';
	}
}

if (isset($kontenbc) && $kontenbc != '') {
	# Cari member yg Antri BC
	$send = $wpdb->get_results("SELECT * FROM `wafu_gruptomember_mlm` 
		LEFT JOIN `wafu_member_mlm` ON `wafu_gruptomember_mlm`.`member_id`=`wafu_member_mlm`.`member_id`
		WHERE `wafu_gruptomember_mlm`.`gtm_bc` = 1 LIMIT 0,".rand($jmlbcmin,$jmlbcmax));
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


				
				# Update status bc member jadi 0
				$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_bc`=0 WHERE `member_id`=".$send->member_id);
				$show .= '<p>'.wp_date('Y-m-d H:i:s').': ['.$send->member_wa.'] '.$konten.'</p>';

				wafu_kirim_mlm($send->member_wa,$konten,$gambar, $send->camp_user_wp);
				sleep(rand($sleepmin,$sleepmax));
			} else {
				# Ubah langsung jadi 0 biar gak nyangkut
				$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_bc`=0 WHERE `gtm_id`=".$send->gtm_id);
			}
		}
	} else {
		# Ubah status BC jadi done
		if (isset($databc->bc_id)) {
			$wpdb->query("UPDATE `wafu_bc_mlm` SET `bc_status`=2, `bc_tglselesai`='".wp_date('Y-m-d H:i:s')."' 
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