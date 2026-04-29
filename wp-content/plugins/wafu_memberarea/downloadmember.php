<?php
$load = $_SERVER["DOCUMENT_ROOT"].'/wp-load.php';
require_once($load);
if (!current_user_can('manage_options')) { die; }
if (isset($_GET['grup']) && is_numeric($_GET['grup'])) {
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm` WHERE `grup_id`=".$_GET['grup']);
	if (isset($grup->grup_id)) {
		$data = $wpdb->get_results("SELECT * FROM `wafu_gruptomember_mlm`		
		LEFT JOIN `wafu_member_mlm` ON `wafu_gruptomember_mlm`.`member_id` = `wafu_member_mlm`.`member_id`
		WHERE `wafu_gruptomember_mlm`.`grup_id`=".$grup->grup_id);		
	}
}

if (!isset($data)) {
	# Kalau gak ada data grup, berarti ambil semua data subscriber baik yg masuk grup maupun tidak
	$data = $wpdb->get_results("SELECT * FROM `wafu_member_mlm`");
	$namafile = 'subs-'.date('Ymd').'.xlsx';
} else {
	$namafile = 'subs-'.$grup->grup_nama.'-'.date('Ymd').'.xlsx';
}

if (is_array($data) && count($data) > 0) {
	include('xlsxgen.php');
	$i = 1;
	$member[0] = array('Nama','No. Whatsapp','Tanggal Gabung');
	if (isset($grup->grup_custom) && $grup->grup_custom != ''){
		$custom = explode("\n", $grup->grup_custom);
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $customitem) {
				array_push($member[0], trim($customitem));
			}
		}
	}

	foreach ($data as $data) {
		$member[$i] = array($data->member_nama,$data->member_wa,$data->member_tglgabung);
		if (isset($custom) && is_array($custom) 
			&& isset($data->gtm_customdata) && $data->gtm_customdata != '') {
			$datacustom = unserialize($data->gtm_customdata);
			foreach ($custom as $customitem) {
				if (isset($datacustom[trim($customitem)])) {
					array_push($member[$i],$datacustom[trim($customitem)]);
				} else {
					array_push($member[$i],'');
				}
			}
		}
		$i++;
	}


	$xlsx = SimpleXLSXGen::fromArray( $member );
	//$xlsx->saveAs('member.xlsx');
	$upload_dir = wp_upload_dir();
	$xlsx->downloadAs($namafile);
}
?>