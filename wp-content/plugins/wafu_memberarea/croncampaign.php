<?php 
global $wpdb;
# CRON CAMPAIGN - Jalankan Setting Cron min. 2 menit sekali

#Random Jumlah Pesan yg dikirim per running
$jmlmin = 5;	# Jumlah nomor yang dikirim minimal
$jmlmax = 10;	# Jumlah nomor yang dikirim maksimal
#Random Delay antar pesan
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

$data = $wpdb->get_results("SELECT * FROM `wafu_gruptomember_mlm`,`wafu_member_mlm`,`wafu_grup_mlm`,`wafu_campaign_mlm` 
	WHERE `wafu_gruptomember_mlm`.`member_id`=`wafu_member_mlm`.`member_id`
	AND `wafu_gruptomember_mlm`.`grup_id` = `wafu_grup_mlm`.`grup_id`
	AND `wafu_gruptomember_mlm`.`gtm_nextid` = `wafu_campaign_mlm`.`camp_id`
	AND `wafu_gruptomember_mlm`.`gtm_status` = 1
	AND `wafu_gruptomember_mlm`.`gtm_schedule` <= '".wp_date('Y-m-d H:i:s')."'
	LIMIT 0,".rand($jmlmin,$jmlmax));

if (count($data) > 0) {
	foreach ($data as $data) {
		$nohp = $data->member_wa;
		$konten = $data->camp_content;
		$user_id = $data->camp_user_wp;
		$gambar = $data->camp_image;
		$next_grup_id = $data->camp_next_grup;
		$newgrup = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `wafu_grup_mlm` WHERE `grup_id` = %d", $data->camp_next_grup)
        );
		if ($data->grup_footer != '') {
			$konten .= "\n\r".$data->grup_footer;
		}

		if ($data->grup_custom != '') {
			$grupcustom = explode("\n", $data->grup_custom);
		}

		if($newgrup->grup_custom  > 0) {
			$newgrupcustom = explode("\n", $newgrup->grup_custom);
		} else {
			$newgrupcustom = '';
		}

		$spintax = new wafu_spin();
		$konten = $spintax->process($konten);

		if ($data->member_nama != '') {
			$konten = str_replace('[nama]',$data->member_nama,$konten);
		} else {
			$konten = str_replace('[nama]','Sobat',$konten);
		}

		if (isset($grupcustom)) {
			$customdata = unserialize($data->gtm_customdata);			
			foreach ($grupcustom as $grupcustom) {
				if (isset($customdata[trim($grupcustom)])) {
					$konten = str_replace('['.trim($grupcustom).']',$customdata[trim($grupcustom)],$konten);
    			} else {
    					$konten = str_replace('['.trim($grupcustom).']','',$konten);
    			}
		    }

		}
		
		if ($newgrupcustom != '') {
		    $grup_custom = $wpdb->get_var(
                $wpdb->prepare("SELECT grup_custom FROM `wafu_grup_mlm` WHERE `grup_id` = %d", $data->camp_next_grup)
            );
            $grup_custom = explode("\n", $grup_custom);
			$newcustomdata = unserialize($data->gtm_customdata);			
			foreach ($grup_custom as $grup_custom) {
				if (isset($newcustomdata[trim($grup_custom)])) {
					$newcustomstring .= '#'.$newcustomdata[trim($grup_custom)];
				} else {
					$newcustomstring .= '#'.trim($grup_custom);
				}
			}
		
		}
			
		if ($data->member_nama != '' && $newcustomstring != '') {
		    $grupcode = $wpdb->get_var("SELECT grup_code FROM `wafu_grup_mlm` WHERE `grup_id`=" . intval($data->camp_next_grup));
			$link =  wafu_generate_link_change_group($data->camp_user_wp, $grupcode, $data->member_nama, $newcustomstring);
			$konten = str_replace('[gruplink]', $link, $konten);
		} else {
		    $grupcode = $wpdb->get_var("SELECT grup_code FROM `wafu_grup_mlm` WHERE `grup_id`=" . intval($data->camp_next_grup));
			$link =  wafu_generate_link_change_group($newgrup->camp_user_wp, $grupcode, $data->member_nama, '');
			$konten = str_replace('[gruplink]', $link, $konten);
		}


		// Update database utk next campaign
		$nextcamp = $wpdb->get_row("SELECT * FROM `wafu_campaign_mlm` 
			WHERE `camp_grup`=".$data->camp_grup." 
			AND `camp_sort` > ".$data->camp_sort." 
			ORDER BY `camp_sort`,`camp_id` LIMIT 0,1");
		if (isset($nextcamp->camp_id)) {
			switch ($nextcamp->camp_periode) {
				case 1:
					// code...
					$periode = ' days';
					break;
				case 2:
					// code...
					$periode = ' hours';
					break;
				case 3:
					// code...
					$periode = ' minutes';
					break;
			}
			$delay = '+'.intval($nextcamp->camp_delay).$periode;
			$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_nextid`=".$nextcamp->camp_id.", 
				`gtm_schedule`='".wp_date('Y-m-d H:i:s',strtotime($delay))."'
				WHERE `gtm_id`=".$data->gtm_id);
		} else {
			$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_nextid`=0, `gtm_schedule`='0000-00-00 00:00:00'
					WHERE `gtm_id`=".$data->gtm_id);
		}		
		
		// Kirim WA
		wafu_kirim_mlm($nohp,$konten,$gambar,$user_id);
		sleep(rand($sleepmin,$sleepmax));
	}
}