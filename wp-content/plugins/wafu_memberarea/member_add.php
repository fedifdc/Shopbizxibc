<?php
global $wpdb;
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$current_user_id = intval($_GET['user_id']);
	$is_admin_page = true;
} else {
	$is_admin_page = false;
	$current_user_id = get_current_user_id();
}
if (isset($_GET['add']) && is_numeric($_GET['add'])) :
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm` WHERE `grup_id`=".$_GET['add']." AND `grup_user_wp`=".$current_user_id);
	if (!$grup) {
		echo '<div class="wrap"><h2>Grup tidak ditemukan</h2></div>';
		return;
	}

	if (isset($_POST['member_nama']) && isset($_POST['member_wa'])) {
		$nowa = wafu_format_mlm($_POST['member_wa']);
		$nama = $wpdb->_real_escape($_POST['member_nama']);
		$memberid = $wpdb->get_var("SELECT `member_id` FROM `wafu_member_mlm` WHERE `member_wa`='".$nowa."'");
		if (!is_numeric($memberid)) {
			$wpdb->query("INSERT INTO `wafu_member_mlm` (`member_nama`,`member_wa`,`member_tglgabung`,`camp_user_wp`) VALUE 
					('".$nama."','".$nowa."','".current_time('Y-m-d H:i:s')."',".$current_user_id.")");
			$memberid = $wpdb->insert_id;
		}

		if ($grup->grup_custom != '') {
			$grupcustom = explode("\n",$grup->grup_custom);
			$i = 2;
			foreach ($grupcustom as $grupcustom) {
				if (isset($_POST['custom'][trim($grupcustom)])) {
					$customdata[trim($grupcustom)] = $_POST['custom'][trim($grupcustom)];
					$i++;
				}
			}
			if (isset($customdata) && count($customdata) > 0) {
				$dbcustom = serialize($customdata);
			} else {
				$dbcustom = '';
			}
		} else {
			$dbcustom = '';
		}
		
		$gtm = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$memberid." 
			AND `grup_id`=".$_GET['add']);
		if ($gtm > 0) {
			echo '<div class="notice notice-warning is-dismissible">
			<p>Member telah terdaftar</p>
			</div>';
		} else {
			// Dapatkan pesan selamat datang dan next post
			$data = $wpdb->get_results("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$_GET['add']." ORDER BY `camp_sort` LIMIT 0,2");
			if (count($data) > 1) {
				$i = 0;
				foreach ($data as $camp) {
					if ($i == 0) {
						if ($nama != '') {
							$konten = str_replace('[nama]',$nama,$camp->camp_content);
						} else {
							$konten = str_replace('[nama]','Sobat',$camp->camp_content);
						}
						$gambar = $camp->camp_image;
						if (isset($customdata) && count($customdata) > 0) {
							foreach ($customdata as $key => $value) {									
								$konten = str_replace('['.$key.']',$value,$konten);
							}
						}
						wafu_kirim_mlm($nowa,$konten,$gambar);
					} elseif ($i == 1) {
						# Menentukan jadwal berikutnya
						switch ($camp->camp_periode) {
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
						$delay = '+'.intval($camp->camp_delay).$periode;
						$wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
							(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`, `camp_user_wp`) VALUES 
							(".$memberid.",".$_GET['add'].",".$camp->camp_id.",
							'".wp_date('Y-m-d H:i:s',strtotime($delay))."',
							0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."',".$current_user_id.")");
						if($wpdb->last_error !== '') {					    
						    $wpdb->print_error();
						} else {
							if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
								// Auto Unsubscribe
								$unsub = unserialize($grup->grup_unsubscribe);
								$listgrup = implode(',', $unsub);
								$wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$memberid." AND `grup_id` IN (".$listgrup.")");
								if ($wpdb->last_error !== '') {
								    $wpdb->print_error();
								}								
							}
							
							echo '<div class="notice notice-success is-dismissible">
							<p>Member telah ditambahkan</p>
							</div>';								
						}
					}
					$i++;
				}
			} elseif (count($data) == 1) {
				foreach ($data as $camp) {
					if ($nama != '') {
						$konten = str_replace('[nama]',$nama,$camp->camp_content);
					} else {
						$konten = str_replace('[nama]','Sobat',$camp->camp_content);
					}
					$gambar = $camp->camp_image;
					if (isset($customdata) && count($customdata) > 0) {
						foreach ($customdata as $key => $value) {
							$konten = str_replace('['.$key.']',$value,$konten);
						}
					}
					wafu_kirim_mlm($nowa,$konten,$gambar);				
					$wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
							(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`,`camp_user_wp`) VALUES 
							(".$memberid.",".$_GET['add'].",0,'0000-00-00 00:00:00',
							0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."',".$current_user_id.")");
					if ($wpdb->last_error !== '') {					    
					    $wpdb->print_error();
					} else {
						if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
							// Auto Unsubscribe
							$unsub = unserialize($grup->grup_unsubscribe);
							$listgrup = implode(',', $unsub);
							$wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$memberid." AND `grup_id` IN (".$listgrup.")");
							if ($wpdb->last_error !== '') {
							    $wpdb->print_error();
							}								
						}
						
						echo '<div class="notice notice-success is-dismissible">
						<p>Subscriber telah ditambahkan. 
						<a href="wafu-member?grup='.$_GET['add'].'">Klik untuk Melihat Daftar Subscriber</a></p>
						</div>';
					}
				}
			} else {
				echo '<div class="notice notice-warning is-dismissible">
				<p>Grup tidak memiliki campaign selamat datang. <a href="wafu-grup?campaign='.$_GET['add'].'">Silahkan tambahkan 1 campaign dulu</a></p>
				</div>';
			}
		}
	}
?>
<div class="wrap">
<?php if ($is_admin_page): ?>
	<h1 class="wp-heading-inline">Tambah Subscriber ke <?php echo '<a href="admin.php?page=wafu_member_aff&grup='.$grup->grup_id.'">'.$grup->grup_nama.'</a>';?></h1>
<?php else: ?>
<h1 class="wp-heading-inline">Tambah Subscriber ke <?php echo '<a href="wafu-member?grup='.$grup->grup_id.'">'.$grup->grup_nama.'</a>';?></h1>
<?php endif; ?>
<hr class="wp-header-end">
<form action="" method="post">
<div class="form-group row">
	<label class="col-sm-2 col-form-label">Nama</label>
    <div class="col-sm-10">
	    <input type="text" name="member_nama"/>
    </div>		    
</div>
<div class="form-group row">
	<label class="col-sm-2 col-form-label">Whatsapp</label>
    <div class="col-sm-10">
	    <input type="text" name="member_wa"/>
    </div>		    
</div>
<?php
if (isset($grup->grup_custom) && $grup->grup_custom != '') {
	$custom = explode("\n",$grup->grup_custom);
	if (count($custom) > 0) {
		foreach($custom as $custom) {
			echo '
			<div class="form-group row">
				<label class="col-sm-2 col-form-label">'.trim($custom).'</label>
			    <div class="col-sm-10">
				    <input type="text" name="custom['.trim($custom).']"/>
			    </div>		    
			</div>
			';
		}
	}
}
?>
<input type="submit" value="Simpan" class="btn btn-success" />
</form>
<?php endif; ?>
</div>