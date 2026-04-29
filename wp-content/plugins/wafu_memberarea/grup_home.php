<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; }
global $wpdb;

if ( current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$is_admin_page=true;
	$current_user_id = intval($_GET['user_id']);
} else {
	$is_admin_page = false;
	$current_user_id = get_current_user_id();
}
$wafusetting = get_user_meta($current_user_id, 'wafusetting', true);
if(!$wafusetting) {
	$wafusetting = [
		'sender' => '',
		'kode_unsubscribe' => ''
	];
}
echo '<div class="wrap">
	<h1 class="wp-heading-inline">Grup</h1>
	<hr class="wp-header-end mb-4">';

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	if (isset($_POST['grup_nama'])) {
		if (isset($_POST['grup_unsubscribe'])) { 
			$unsubscribe = serialize($_POST['grup_unsubscribe']); 
		} else { 
			$unsubscribe = ''; 
		}
		$wpdb->query("UPDATE `wafu_grup_mlm`  SET 
			`grup_nama`= '".$wpdb->_real_escape($_POST['grup_nama'])."',
			`grup_diskripsi`= '".$wpdb->_real_escape($_POST['grup_diskripsi'])."',
			`grup_code`= '".$wpdb->_real_escape($_POST['grup_code'])."',
			`grup_custom`= '".$wpdb->_real_escape($_POST['grup_custom'])."',
			`grup_unsubscribe`= '".$unsubscribe."',
			`grup_isival`= '".$wpdb->_real_escape($_POST['grup_isival'])."',
			`grup_footer`= '".$wpdb->_real_escape($_POST['grup_footer'])."'
			WHERE `grup_id`=".$_GET['edit']);
		if ($wpdb->last_error !== '') {
		    $wpdb->print_error();
		} else {
			echo '<div class="notice notice-success is-dismissible">
			<p>Update berhasil</p>
			</div>';
		}
	}
	$data = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm`  WHERE `grup_id`=".$_GET['edit'] . " AND `camp_user_wp` = ".$current_user_id);
	if (!$data) {
		echo '<div class="notice notice-error is-dismissible">
			<p>Grup tidak ditemukan</p>
			</div>';
		exit;
	}
} else {
	if (isset($_POST['grup_nama'])) {
		if (isset($_POST['grup_unsubscribe'])) { 
			$unsubscribe = serialize($_POST['grup_unsubscribe']); 
		} else { 
			$unsubscribe = ''; 
		}

		$max_groups = get_option('wafu_max_groups', true);
		$current_group_count = $wpdb->get_var("SELECT COUNT(*) FROM `wafu_grup_mlm` WHERE `camp_user_wp` = ".$current_user_id);

		if ($max_groups && $current_group_count >= $max_groups) {
			echo '<div class="notice notice-error is-dismissible">
			<p>Anda telah mencapai batas maksimal grup yang dapat dibuat.</p>
			</div>';
		} else {
			$wpdb->query("INSERT INTO `wafu_grup_mlm`  
				(`grup_nama`,`grup_diskripsi`,`grup_tgl_bikin`,`grup_validate`,`grup_success`,`grup_code`,`grup_custom`,`grup_unsubscribe`,`grup_isival`,`grup_footer`,`camp_user_wp`) VALUES 
				(
					'".$wpdb->_real_escape($_POST['grup_nama'])."',
					'".$wpdb->_real_escape($_POST['grup_diskripsi'])."',
					'".current_time('Y-m-d H:i:s')."',
					'',
					'',
					'".$wpdb->_real_escape($_POST['grup_code'])."',
					'".$wpdb->_real_escape($_POST['grup_custom'])."',
					'".$unsubscribe."',
					'".$wpdb->_real_escape($_POST['grup_isival'])."',
					'".$wpdb->_real_escape($_POST['grup_footer'])."',
					'".$current_user_id."'
				)
				");
			$idgrup = $wpdb->insert_id;
			if($wpdb->last_error !== '') {
				$wpdb->print_error();
			} else {
				# Bikin campaign default sesuai nama grup
				$kontenawal = 'Terima kasih, anda telah bergabung bersama '.$wpdb->_real_escape($_POST['grup_nama']);
				$wpdb->query("INSERT INTO `wafu_campaign_mlm` (`camp_grup`,`camp_title`,`camp_content`,`camp_image`,`camp_delay`,`camp_periode`,`camp_sort`,`camp_user_wp`) VALUES (".$idgrup.",'Selamat Bergabung','".$kontenawal."','',0,1,1,'".$current_user_id."')");
				echo '<div class="notice notice-success is-dismissible">
				<p>Grup telah ditambahkan</p>
				</div>';
			}
		}
	} elseif (isset($_GET['del']) && is_numeric($_GET['del'])) {
		$wpdb->query("DELETE FROM `wafu_grup_mlm`  WHERE `grup_id`=".$_GET['del']);
		if($wpdb->last_error !== '') {
		    $wpdb->print_error();
		} else {
			echo '<div class="notice notice-success is-dismissible">
			<p>Grup telah dihapus</p>
			</div>';
		}
	}

	$datagrup = $wpdb->get_results("SELECT *, 
		COUNT(`wafu_gruptomember_mlm`.`grup_id`) AS `jml_member`, 
		`wafu_grup_mlm` .`grup_id` AS `idgrup`, `wafu_grup_mlm` .`camp_user_wp` as camp_user_wp
		FROM `wafu_grup_mlm`  LEFT JOIN `wafu_gruptomember_mlm` 
		ON `wafu_gruptomember_mlm`.`grup_id` = `wafu_grup_mlm` .`grup_id`
		where `wafu_grup_mlm`.`camp_user_wp` = ".$current_user_id." 
		GROUP BY `idgrup` ORDER BY `idgrup`");

	if (count($datagrup) > 0) {		
		foreach ($datagrup as $grup) {
			$kodereg = '#Nama';
			if ($grup->grup_custom != '') {
				$custom = explode("\n", $grup->grup_custom);
				foreach ($custom as $custom) {
					$kodereg .= '#'.trim($custom);
				}
			}
			if($is_admin_page){
				echo '
				<div class="border-bottom mb-2 pb-2">
					<div class="row">
						<div class="col-10">
							<a href="admin.php?page=wafu_grup_aff&edit='.$grup->idgrup.'&user_id='.$current_user_id.'" title="Edit Grup" class="font-weight-bold">
							'.$grup->grup_nama.'</a> &nbsp;<small><a data-toggle="collapse" href="#info'.$grup->idgrup.'" role="button" aria-expanded="false" aria-controls="info'.$grup->idgrup.'" 
								class="badge badge-pill badge-primary">info ⮟</a></small>                   
						</div>
						<div class="col-2">					
							<div class="float-right">
							<a href="admin.php?page=wafu_grup_aff&del='.$grup->idgrup.'&user_id='.$current_user_id.'" class="ml-2 btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus grup '.$grup->grup_nama.'?\')">Hapus</a>
							</div>
						</div>
					</div>			
					<div class="row collapse" id="info'.$grup->idgrup.'">
						<div class="col-12 pb-3">
						   ID: '.$grup->idgrup.'<br/>
						   Jml Subs: <a href="admin.php?page=wafu_member_aff&grup='.$grup->idgrup.'&user_id='.$current_user_id.' 
						   title="Klik untuk melihat daftar subscriber">'.number_format($grup->jml_member).'</a><br/>
						   Kode Subscribe: <a href="https://wa.me/'.wafu_format_mlm($wafusetting['sender']).'/?text='.rawurlencode('join#'.$grup->grup_code.$kodereg).'" target="_blank"><code>join#'.$grup->grup_code.$kodereg.'</code></a><br/>
						   Kode Unsubscribe: <a href="https://wa.me/'.wafu_format_mlm($wafusetting['sender']).'/?text='.rawurlencode('join#'.$wafusetting['kode_unsubscribe'].'#'.$grup->grup_code).'" target="_blank"><code>unsubcribe#'.$wafusetting['kode_unsubscribe'].'#'.$grup->grup_code.'</code></a><br/>
						   <small>'.$grup->grup_diskripsi.'</small><br/>
						   <a href="admin.php?page=wafu_grup_aff&campaign='.$grup->idgrup.'" class="btn btn-sm btn-success" title="Kelola Chat Berseri">Campaign</a>
						   <a href="admin.php?page=wafu_member_aff&add='.$grup->idgrup.'" class="btn btn-sm btn-primary" title="Tambah Subscriber Manual">Add Subs</a>
						   <a href="admin.php?page=wafu_member_aff&import='.$grup->idgrup.'" class="btn btn-sm btn-secondary" title="Tambah Subscriber dari Excel">Import Subs</a>
						</div>
					</div>
				</div>
					';
			} else {
				echo '
		<div class="border-bottom mb-2 pb-2">
			<div class="row">
				<div class="col-10">
					<a href="/wafu-grup?edit='.$grup->idgrup.'" title="Edit Grup" class="font-weight-bold">
					'.$grup->grup_nama.'</a> &nbsp;<small><a data-toggle="collapse" href="#info'.$grup->idgrup.'" role="button" aria-expanded="false" aria-controls="info'.$grup->idgrup.'" 
						class="badge badge-pill badge-primary">info ⮟</a></small>                   
				</div>
				<div class="col-2">					
					<div class="float-right">
					<a href="/wafu-grup?del='.$grup->idgrup.'" class="ml-2 btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus grup '.$grup->grup_nama.'?\')">Hapus</a>
					</div>
				</div>
			</div>			
			<div class="row collapse" id="info'.$grup->idgrup.'">
				<div class="col-12 pb-3">
		           ID: '.$grup->idgrup.'<br/>
		           Jml Subs: <a href="/wafu-member?grup='.$grup->idgrup.'" 
		           title="Klik untuk melihat daftar subscriber">'.number_format($grup->jml_member).'</a><br/>
		           Kode Subscribe: <a href="https://wa.me/'.wafu_format_mlm($wafusetting['sender']).'/?text='.rawurlencode('join#'.$grup->grup_code.$kodereg).'" target="_blank"><code>join#'.$grup->grup_code.$kodereg.'</code></a><br/>
				   Kode Unsubscribe: <a href="https://wa.me/'.wafu_format_mlm($wafusetting['sender']).'/?text='.rawurlencode('join#'.$wafusetting['kode_unsubscribe'].'#'.$grup->grup_code).'" target="_blank"><code>unsubcribe#'.$wafusetting['kode_unsubscribe'].'#'.$grup->grup_code.'</code></a><br/>
		           <small>'.$grup->grup_diskripsi.'</small><br/>
				   <a href="/wafu-grup?campaign='.$grup->idgrup.'" class="btn btn-sm btn-success" title="Kelola Chat Berseri">Campaign</a>
				   <a href="/wafu-member?add='.$grup->idgrup.'" class="btn btn-sm btn-primary" title="Tambah Subscriber Manual">Add Subs</a>
				   <a href="/wafu-member?import='.$grup->idgrup.'" class="btn btn-sm btn-secondary" title="Tambah Subscriber dari Excel">Import Subs</a>
	            </div>
	        </div>
	    </div>
			';
			}
		}
	} else {
		echo '<p>Silahkan membuat grup</p>';
	}
}

if (isset($data->grup_unsubscribe) && $data->grup_unsubscribe != '') {
	$unsub = unserialize($data->grup_unsubscribe);
} 
?>
<h2 class="mt-5 mb-3"><?php if (isset($_GET['edit'])) { echo 'Edit'; } else { echo 'Buat'; } ?> Grup</h2>
<form action="" method="post">
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Nama Grup</label>
	    <div class="col-sm-10">
		    <input type="text" class="form-control" name="grup_nama" value="<?php if (isset($data->grup_nama)) { echo $data->grup_nama; } ?>" />
	    </div>		    
	</div>
		<div class="form-group row">
		<label class="col-sm-2 col-form-label">Kode Grup</label>
	    <div class="col-sm-10">
		    <input type="text" class="form-control" name="grup_code" value="<?php if (isset($data->grup_code)) { echo $data->grup_code; } ?>" />
		    <small class="form-text text-muted">Kode untuk registrasi dan unsubscribe grup. Gunakan huruf kecil semua untuk menghindari kesalahan</small>
	    </div>		    
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Custom Field</label>
	    <div class="col-sm-10">
		    <textarea name="grup_custom" class="form-control" rows="4" placeholder="Username
Password"><?php if (isset($data->grup_custom)) { echo $data->grup_custom; } ?></textarea>
		    <small class="form-text text-muted">Isi nama2 field tambahan. Per baris 1 field</small>
	    </div>
	</div>	
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Footer</label>
	    <div class="col-sm-10">
		    <textarea name="grup_footer" class="form-control" rows="4"><?php if (isset($data->grup_footer)) { echo $data->grup_footer; } ?></textarea>
		    <small class="form-text text-muted">Text yang disertakan di akhir tiap pesan</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Auto Unsubscribe</label>
	    <div class="col-sm-10">
		    <select multiple  class="form-control" name="grup_unsubscribe[]">
		    	<option value=""></option>
		    	<?php
		    	$datagrup = $wpdb->get_results("SELECT *
				FROM `wafu_grup_mlm` where camp_user_wp= ".$current_user_id."  ORDER BY `grup_id`");
		    	if (count($datagrup) > 0) {
		    		foreach ($datagrup as $grup) {
		    			if (isset($_GET['edit']) && $_GET['edit'] == $grup->grup_id) {
		    				# diem aja
		    			} else {
			    			echo '<option value="'.$grup->grup_id.'"';
			    			if (isset($unsub) && in_array($grup->grup_id, $unsub)) { echo ' selected'; }
			    			echo '>'.$grup->grup_nama.'</option>';
		    			}
		    		}
		    	}
		    	?>
		    </select>
		    <small class="form-text text-muted">Otomatis keluar dari grup yg anda pilih begitu bergabung di grup ini</small>
	    </div>		    
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Diskripsi grup</label>
	    <div class="col-sm-10">
		    <textarea name="grup_diskripsi" class="form-control" rows="4"><?php if (isset($data->grup_diskripsi)) { echo $data->grup_diskripsi; } ?></textarea>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pesan Validasi</label>
	    <div class="col-sm-10">
		    <textarea name="grup_isival" class="form-control" rows="4">
<?php 
if (isset($data->grup_isival)) { 
echo $data->grup_isival; 
} else {
echo 'Kode Validasi Anda:
*[kodeval]*
';
}
?>
		    	</textarea>
		    <small class="form-text text-muted">Gunakan kode <code>[kodeval]</code> untuk menampilkan kode validasi</small>
	    </div>
	</div>

	

	<input type="submit" value="<?php if (isset($_GET['edit'])) { echo 'Edit'; } else { echo 'Buat'; } ?> Grup" class="btn btn-success"/>
</form>
<p>&nbsp;</p>
</div>