<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($_GET['add']) && is_numeric($_GET['add'])) :
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_id`=".$_GET['add']);

	if (isset($_POST['member_nama']) && isset($_POST['member_wa'])) {
		$nowa = wafu_format($_POST['member_wa']);
		$nama = $wpdb->_real_escape($_POST['member_nama']);
		$memberid = $wpdb->get_var("SELECT `member_id` FROM `wafu_member` WHERE `member_wa`='".$nowa."'");
		if (!is_numeric($memberid)) {
			$wpdb->query("INSERT INTO `wafu_member` (`member_nama`,`member_wa`,`member_tglgabung`) VALUE 
					('".$nama."','".$nowa."','".current_time('Y-m-d H:i:s')."')");
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
		
		$gtm = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember` WHERE `member_id`=".$memberid." 
			AND `grup_id`=".$_GET['add']);
		if ($gtm > 0) {
			echo '<div class="notice notice-warning is-dismissible">
			<p>Member telah terdaftar</p>
			</div>';
		} else {
			// Dapatkan pesan selamat datang dan next post
			$data = $wpdb->get_results("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$_GET['add']." ORDER BY `camp_sort` LIMIT 0,2");
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
						wafu_kirim($nowa,$konten,$gambar);
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
						$wpdb->query("INSERT INTO `wafu_gruptomember` 
							(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
							(".$memberid.",".$_GET['add'].",".$camp->camp_id.",
							'".wp_date('Y-m-d H:i:s',strtotime($delay))."',
							0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
						if($wpdb->last_error !== '') {					    
						    $wpdb->print_error();
						} else {
							if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
								// Auto Unsubscribe
								$unsub = unserialize($grup->grup_unsubscribe);
								$listgrup = implode(',', $unsub);
								$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$memberid." AND `grup_id` IN (".$listgrup.")");
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
					wafu_kirim($nowa,$konten,$gambar);				
					$wpdb->query("INSERT INTO `wafu_gruptomember` 
							(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) VALUES 
							(".$memberid.",".$_GET['add'].",0,'0000-00-00 00:00:00',
							0,1,'".$dbcustom."','".wp_date('Y-m-d H:i:s')."')");
					if ($wpdb->last_error !== '') {					    
					    $wpdb->print_error();
					} else {
						if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
							// Auto Unsubscribe
							$unsub = unserialize($grup->grup_unsubscribe);
							$listgrup = implode(',', $unsub);
							$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$memberid." AND `grup_id` IN (".$listgrup.")");
							if ($wpdb->last_error !== '') {
							    $wpdb->print_error();
							}								
						}
						
						echo '<div class="notice notice-success is-dismissible">
						<p>Subscriber telah ditambahkan. 
						<a href="admin.php?page=wafu_member&grup='.$_GET['add'].'">Klik untuk Melihat Daftar Subscriber</a></p>
						</div>';
					}
				}
			} else {
				echo '<div class="notice notice-warning is-dismissible">
				<p>Grup tidak memiliki campaign selamat datang. <a href="admin.php?page=wafu_grup&campaign='.$_GET['add'].'">Silahkan tambahkan 1 campaign dulu</a></p>
				</div>';
			}
		}
	}
?>
<div class="wrap">
<h1 class="wp-heading-inline">Tambah Subscriber ke <?php echo '<a href="admin.php?page=wafu_member&grup='.$grup->grup_id.'">'.$grup->grup_nama.'</a>';?></h1>
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