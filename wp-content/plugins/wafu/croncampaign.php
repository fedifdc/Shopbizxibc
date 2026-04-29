<?php 
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
if (!jamaktif($jam)) { echo 'Di luar jam aktif'; die; }

$show = '';
$wafusetting = get_option('wafusetting');
$data = $wpdb->get_results("SELECT * FROM `wafu_gruptomember`,`wafu_member`,`wafu_grup`,`wafu_campaign` 
	WHERE `wafu_gruptomember`.`member_id`=`wafu_member`.`member_id`
	AND `wafu_gruptomember`.`grup_id` = `wafu_grup`.`grup_id`
	AND `wafu_gruptomember`.`gtm_nextid` = `wafu_campaign`.`camp_id`
	AND `wafu_gruptomember`.`gtm_status` = 1
	AND `wafu_gruptomember`.`gtm_schedule` <= '".wp_date('Y-m-d H:i:s')."'
	LIMIT 0,".rand($jmlmin,$jmlmax));

if (count($data) > 0) {
	foreach ($data as $data) {
		$nohp = $data->member_wa;
		$konten = $data->camp_content;
		$gambar = $data->camp_image;
		if ($data->grup_footer != '') {
			$konten .= "\n\r".$data->grup_footer;
		}

		if ($data->grup_custom != '') {
			$grupcustom = explode("\n", $data->grup_custom);
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

		if (function_exists('wp_affiliasi')) {
			if (isset($data->member_idwp) && is_numeric($data->member_idwp) && $data->member_idwp > 0) {
				if (strpos($konten, '[member_') !== false || strpos($konten, '[sponsor_') !== false) {
					# Ambil data member dan replace shortcode
					$member = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `id_user`=".$data->member_idwp);
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
		
		// Update database utk next campaign
		$nextcamp = $wpdb->get_row("SELECT * FROM `wafu_campaign` 
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
			$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_nextid`=".$nextcamp->camp_id.", 
				`gtm_schedule`='".wp_date('Y-m-d H:i:s',strtotime($delay))."'
				WHERE `gtm_id`=".$data->gtm_id);
		} else {
			$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_nextid`=0, `gtm_schedule`='0000-00-00 00:00:00'
					WHERE `gtm_id`=".$data->gtm_id);
		}		
		
		// Kirim WA
		wafu_kirim($nohp,$konten,$gambar);
		sleep(rand($sleepmin,$sleepmax));
	}
}