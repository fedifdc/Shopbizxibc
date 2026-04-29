<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; }
$wafusetting = get_option('wafusetting');
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
		$wpdb->query("UPDATE `wafu_grup` SET 
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
	$data = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_id`=".$_GET['edit']);
} else {
	if (isset($_POST['grup_nama'])) {
		if (isset($_POST['grup_unsubscribe'])) { 
			$unsubscribe = serialize($_POST['grup_unsubscribe']); 
		} else { 
			$unsubscribe = ''; 
		}

		$wpdb->query("INSERT INTO `wafu_grup` 
			(`grup_nama`,`grup_diskripsi`,`grup_tgl_bikin`,`grup_validate`,`grup_success`,`grup_code`,`grup_custom`,`grup_unsubscribe`,`grup_isival`,`grup_footer`) VALUES 
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
				'".$wpdb->_real_escape($_POST['grup_footer'])."'
			)
			");
		$idgrup = $wpdb->insert_id;
		if($wpdb->last_error !== '') {
		    $wpdb->print_error();
		} else {
			# Bikin campaign default sesuai nama grup
			$kontenawal = 'Terima kasih, anda telah bergabung bersama '.$wpdb->_real_escape($_POST['grup_nama']);
			$wpdb->query("INSERT INTO `wafu_campaign` (`camp_grup`,`camp_title`,`camp_content`,`camp_image`,`camp_delay`,`camp_periode`,`camp_sort`) VALUES (".$idgrup.",'Selamat Bergabung','".$kontenawal."','',0,1,1)");
			echo '<div class="notice notice-success is-dismissible">
			<p>Grup telah ditambahkan</p>
			</div>';
		}
	} elseif (isset($_GET['del']) && is_numeric($_GET['del'])) {
		$wpdb->query("DELETE FROM `wafu_grup` WHERE `grup_id`=".$_GET['del']);
		if($wpdb->last_error !== '') {
		    $wpdb->print_error();
		} else {
			echo '<div class="notice notice-success is-dismissible">
			<p>Grup telah dihapus</p>
			</div>';
		}
	}

	$datagrup = $wpdb->get_results("SELECT *, 
		COUNT(`wafu_gruptomember`.`grup_id`) AS `jml_member`, 
		`wafu_grup`.`grup_id` AS `idgrup` 
		FROM `wafu_grup` LEFT JOIN `wafu_gruptomember` 
		ON `wafu_gruptomember`.`grup_id` = `wafu_grup`.`grup_id` 
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
			echo '
		<div class="border-bottom mb-2 pb-2">
			<div class="row">
				<div class="col-10">
					<a href="admin.php?page=wafu_grup&edit='.$grup->idgrup.'" title="Edit Grup" class="font-weight-bold">
					'.$grup->grup_nama.'</a> &nbsp;<small><a data-toggle="collapse" href="#info'.$grup->idgrup.'" role="button" aria-expanded="false" aria-controls="info'.$grup->idgrup.'" 
						class="badge badge-pill badge-primary">info ⮟</a></small>                   
				</div>
				<div class="col-2">					
					<div class="float-right">
					<a href="admin.php?page=wafu_grup&del='.$grup->idgrup.'" class="ml-2 btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus grup '.$grup->grup_nama.'?\')">Hapus</a>
					</div>
				</div>
			</div>			
			<div class="row collapse" id="info'.$grup->idgrup.'">
				<div class="col-12 pb-3">
		           ID: '.$grup->idgrup.'<br/>
		           Jml Subs: <a href="admin.php?page=wafu_member&grup='.$grup->idgrup.'" 
		           title="Klik untuk melihat daftar subscriber">'.number_format($grup->jml_member).'</a><br/>
		           Kode Subscribe: <a href="https://wa.me/'.wafu_format($wafusetting['sender']).'/?text='.rawurlencode($grup->grup_code.$kodereg).'" target="_blank"><code>'.$grup->grup_code.$kodereg.'</code></a><br/>
				   Kode Unsubscribe: <a href="https://wa.me/'.wafu_format($wafusetting['sender']).'/?text='.rawurlencode($wafusetting['kode_unsubscribe'].'#'.$grup->grup_code).'" target="_blank"><code>'.$wafusetting['kode_unsubscribe'].'#'.$grup->grup_code.'</code></a><br/>
		           <small>'.$grup->grup_diskripsi.'</small><br/>
				   <a href="admin.php?page=wafu_grup&campaign='.$grup->idgrup.'" class="btn btn-sm btn-success" title="Kelola Chat Berseri">Campaign</a>
				   <a href="admin.php?page=wafu_member&add='.$grup->idgrup.'" class="btn btn-sm btn-primary" title="Tambah Subscriber Manual">Add Subs</a>
				   <a href="admin.php?page=wafu_member&import='.$grup->idgrup.'" class="btn btn-sm btn-secondary" title="Tambah Subscriber dari Excel">Import Subs</a>
	            </div>
	        </div>
	    </div>
			';
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
				FROM `wafu_grup` ORDER BY `grup_id`");
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