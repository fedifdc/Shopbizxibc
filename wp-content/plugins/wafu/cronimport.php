<?php 
# CRON IMPORT - Jalankan Setting Cron min. 2 menit sekali
$jmlmin = 5;	# Jumlah nomor yang diimport minimal
$jmlmax = 10;	# Jumlah nomor yang diimport maksimal
$delaymin = 4; 	# Jarak waktu antar pesan minimal (detik)
$delaymax = 10;	# Jarak waktu antar pesan maksimal (detik)

$wp_load = substr( dirname( __FILE__ ), 0, strpos( dirname( __FILE__ ), 'wp-content' ) ) . 'wp-load.php';
if ( ! empty( $wp_load ) && file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	die('Could not load WordPress');
}
$jam = wp_date('H:i');
if (!jamaktif($jam)) { echo 'Di luar jam aktif'; die; }

$import = $wpdb->get_results("SELECT * FROM `wafu_import` LIMIT 0,".rand($jmlmin,$jmlmax));
$datenow = strtotime(wp_date('Y-m-d H:i:s'));
$show = '';
if (count($import) > 0) {
	foreach ($import as $import) {
		# Cek apakah nomor sudah terdaftar
		$wa = wafu_format($import->import_wa);
		$member = $wpdb->get_row("SELECT * FROM `wafu_member` WHERE `member_wa`='".$wa."'");
		if (isset($member->member_id)) {
			$member_id = $member->member_id;
			# Jika ada data nama, update datanya
			if ($import->import_nama != '') {
				$wpdb->query("UPDATE `wafu_member` SET `member_nama`='".$wpdb->_real_escape($import->import_nama)."'
					WHERE `member_id`=".$member_id);
			}
		} else {
			# Masukkan data member
			$wpdb->query("INSERT INTO `wafu_member` (`member_nama`,`member_wa`,`member_tglgabung`) VALUE 
					('".$wpdb->_real_escape($import->import_nama)."','".$import->import_wa."','".wp_date('Y-m-d H:i:s')."')");
			$member_id = $wpdb->insert_id;
		}

		# Cek apakah member sudah terdaftar
		$gtm = $wpdb->get_row("SELECT * FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." 
			AND `grup_id`=".$import->import_grup);
		if (!isset($gtm->gtm_id)) {
			$camp = $wpdb->get_row("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$import->import_grup." ORDER BY `camp_sort` LIMIT 0,1");
	    	if (isset($camp->camp_id)) {
	    		$nextid = $camp->camp_id;
	    	} else {
	    		$nextid = 0;
	    		$schedule = 0;
	    		$noschedule = 1;
	    	}

	    	if (!isset($noschedule)) {   
	    		$rand = mt_rand($delaymin,$delaymax);
	    		$datenow = $datenow + $rand;
	    		$schedule = date('Y-m-d H:i:s', ($datenow));
    		}

    		if ($import->import_custom != '') {
    			$dbcustom = $import->import_custom;
    		} else {
    			$dbcustom = '';
    		}

    		$wpdb->query("INSERT INTO `wafu_gruptomember` 
				(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) 
				VALUES (".$member_id.",".$import->import_grup.",".$nextid.",'".$schedule."',0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
    		$show .= $import->import_wa.' telah dimasukkan<br/>';
		} else {			
			$show .= 'DUPLIKAT: '.$import->import_wa.'<br/>';
		}
		# Hapus dari tabel import
		$wpdb->query("DELETE FROM `wafu_import` WHERE `import_id`=".$import->import_id);
	}
} else {
	$show = 'Tabel import kosong';
}
echo $show;