
<div class="wrap">
<!-- <h1 class="wp-heading-inline">WAFU Broadcast</h1> -->
<a href="wafu-broadcast#newbc" class="page-title-action">Add New</a>
<hr class="wp-header-end">
<?php
if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$current_user_id = intval($_GET['user_id']);
	$is_admin_page = true;
} else {
	$is_admin_page = false;
	$current_user_id = get_current_user_id();
}
$grup = $wpdb->get_results("SELECT `grup_id`,`grup_nama` FROM `wafu_grup_mlm` where camp_user_wp=".$current_user_id);
if (count($grup) > 0) {
	foreach ($grup as $grup) {
		$datagrup[$grup->grup_id] = $grup->grup_nama;
	}

	if (isset($_FILES["gambar"]["name"]) && $_FILES["gambar"]["name"] != '') { 
		//Set max file size in bytes
		$max_size = 1024000;
		//Set default file extension whitelist
		$whitelist_ext = array('jpeg','jpg','png','gif');
		//Set default file type whitelist
		$whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');
		
		$upload_dir = wp_upload_dir();
		$pic_dir = $upload_dir['basedir'].'/pic';			
		if( ! file_exists( $pic_dir ) ) { wp_mkdir_p( $pic_dir ); }
		$filename = 'wafubc'.date('YmdHis');
		$output_filename = $pic_dir.'/'.$filename;
		$target_file = $upload_dir['basedir'].'/pic/'.$filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"],PATHINFO_EXTENSION));

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
          $txterror = "Maaf, hanya support JPG, JPEG, PNG & GIF saja.";
          $uploadOk = 0;
        }

        //Check that the file is of the right type
		if (!in_array($_FILES["gambar"]["type"], $whitelist_type)) {
		  $txterror = "Maaf, hanya support JPG, JPEG, PNG & GIF saja.";
		  $uploadOk = 0;
		}

        // Check file size
        if ($_FILES["gambar"]["size"] > $max_size) {
          $txterror = 'Maaf, gambar terlalu besar. Max. 1Mb';
          $uploadOk = 0;
        }

        if ($uploadOk == 1) {
          if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file.'.'.$imageFileType)) {
            $gambar = $filename.'.'.$imageFileType;
          } else {
            $txterror = 'Maaf, gambar tidak dapat diupload';
          }
        }
    } 

	if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		if (isset($_POST['bc_judul']) && isset($_POST['bc_isi'])) {
			$dbgb = $khususdone = '';
			if (isset($gambar) && $gambar != '') {
				$dbgb = "`bc_image`='".$gambar."',";
			} 

			if (isset($_POST['khususdone']) && $_POST['khususdone'] == 1) {
				$khususdone = 1;
			} 

			$wpdb->query("UPDATE `wafu_bc_mlm` SET				
				`bc_judul`='".$wpdb->_real_escape($_POST['bc_judul'])."',
				`bc_isi`='".$wpdb->_real_escape($_POST['bc_isi'])."',
				".$dbgb."
				`bc_tglkirim`='".$wpdb->_real_escape($_POST['bc_tglkirim'])."',
				`bc_target`='".serialize($_POST['bc_target'])."',
				`khususdone` = '".$khususdone."'
				WHERE `bc_id`=".$_GET['edit']);
			if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
			} else {
				echo '<div class="notice notice-success is-dismissible">
				<p>Broadcast telah diupdate</p>
				</div>';
			}
		}
		$data = $wpdb->get_row("SELECT * FROM `wafu_bc_mlm` WHERE `bc_id`=".$_GET['edit']." AND camp_user_wp=".$current_user_id);
		$datatarget = unserialize($data->bc_target);
		$act = 'Edit';
	} elseif (isset($_GET['resend']) && is_numeric($_GET['resend'])) {
		if (isset($_POST['bc_judul']) && isset($_POST['bc_isi'])) {
			$dbgb = $khususdone = '';
			if (isset($gambar) && $gambar != '') {
				$dbgb = "`bc_image`='".$gambar."',";
			} 

			if (isset($_POST['khususdone']) && $_POST['khususdone'] == 1) {
				$khususdone = 1;
			} 

			$wpdb->query("UPDATE `wafu_bc_mlm` SET				
				`bc_judul`='".$wpdb->_real_escape($_POST['bc_judul'])."',
				`bc_isi`='".$wpdb->_real_escape($_POST['bc_isi'])."',
				".$dbgb."
				`bc_tglkirim`='".$wpdb->_real_escape($_POST['bc_tglkirim'])."',
				`bc_status`=0,
				`bc_target`='".serialize($_POST['bc_target'])."',
				`khususdone` = '".$khususdone."'
				WHERE `bc_id`=".$_GET['resend']);
			if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
			} else {
				echo '<div class="notice notice-success is-dismissible">
				<p>Broadcast telah diupdate</p>
				</div>';
			}
		}

		$data = $wpdb->get_row("SELECT * FROM `wafu_bc_mlm` WHERE `bc_id`=".$_GET['resend']);
		$datatarget = unserialize($data->bc_target);
		$act = 'Resend';
	} elseif (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
		$wpdb->query("UPDATE `wafu_bc_mlm` SET `bc_status`=2 WHERE `bc_id`=".$_GET['cancel']);
		$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_bc`=0");
		
		echo '<div class="notice notice-success is-dismissible">
				<p>Broadcast telah dihentikan</p>
				</div>';
		$act = 'Kirim';
	} elseif (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
		$wpdb->query("DELETE FROM `wafu_bc_mlm`  WHERE `bc_id`=".$_GET['hapus']);
		echo '<div class="notice notice-success is-dismissible">
				<p>Broadcast telah dihapus</p>
				</div>';
		$act = 'Kirim';
	} else {
		if (isset($_POST['bc_judul']) && isset($_POST['bc_isi'])) {
			if (isset($gambar) && $gambar != '') { 
				# Diam aja
			} else {
				$gambar = '';
			}

			if (isset($_POST['khususdone']) && $_POST['khususdone'] == 1) {
				$khususdone = 1;
			} else {
				$khususdone = '';
			}

			$wpdb->query("INSERT INTO `wafu_bc_mlm` 
				(`bc_judul`,`bc_isi`,`bc_image`,`bc_tglkirim`,`bc_status`,`bc_target`,`khususdone`,`camp_user_wp`) 
				VALUES
				(
				'".$wpdb->_real_escape($_POST['bc_judul'])."',
				'".$wpdb->_real_escape($_POST['bc_isi'])."',
				'".$gambar."',
				'".$wpdb->_real_escape($_POST['bc_tglkirim'])."',
				0,
				'".serialize($_POST['bc_target'])."',
				'".$khususdone."',
				'".$current_user_id."'
				)");
			if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
			} else {
				echo '<div class="notice notice-success is-dismissible">
				<p>Broadcast telah ditambahkan</p>
				</div>';
			}
		}
		$act = 'Kirim';
	}	

	$databc = $wpdb->get_results("SELECT * FROM `wafu_bc_mlm` where camp_user_wp='".$current_user_id."' ORDER BY `bc_status`,`bc_tglkirim` DESC");
	if (count($databc) > 0) {
		echo '<table class="table">
		<thead class="thead thead-dark">
			<tr>
				<th>Judul BC</th>
				<th>Tgl Kirim</th>
				<th>Target</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>';
		foreach ($databc as $bc) {
			echo '
			<tr>
			<td>';
				if (isset($bc->bc_image) && $bc->bc_image != '') {
					$upload_dir = wp_upload_dir();
					echo '<img src="'.$upload_dir['baseurl'].'/pic/'.$bc->bc_image.'" style="max-height:30px; float:left; margin-right:10px;">';
				}
				echo $bc->bc_judul.'</td>
			<td>'.$bc->bc_tglkirim.'</td>
			<td>';
			$bctarget = unserialize($bc->bc_target);
			foreach ($bctarget as $bctarget) {
				if (isset($datagrup[$bctarget])) {
					echo '- '.$datagrup[$bctarget].'<br/>';
				}
			}

			if (isset($bc->khususdone) && $bc->khususdone == 1) {
				echo '<small><br/>Done Only</small>';
			}
			echo '</td>';
			if ($bc->bc_status == 2) {
				echo '<td>Selesai</td>
				<td><a href="wafu-broadcast?resend='.$bc->bc_id.'#newbc" class="btn btn-sm btn-success">Resend</a>';
			} elseif ($bc->bc_status == 1) {
				echo '<td class="text-success">Running</td>
				<td><a href="wafu-broadcast?cancel='.$bc->bc_id.'" class="btn btn-sm btn-warning">Cancel</a>';
			} else {
				echo '<td>Antri</td><td>';
			}
			echo '
			<a href="wafu-broadcast?edit='.$bc->bc_id.'#newbc" class="btn btn-sm btn-primary">Edit</a> &nbsp;
			<a href="wafu-broadcast?hapus='.$bc->bc_id.'" class="btn btn-sm btn-danger">Hapus</a>
			</td>
			</tr>';
		}
		echo '
		</tbody>
		</table>';
	} else {
		echo '<p>Belum ada Broadcast</p>';
	}
	?>
	<a name="newbc"></a>
	<h3><?php echo $act; ?>	Broadcast</h3>	
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Judul Broadcast</label>
		    <div class="col-sm-10">
			    <input type="text" class="form-control" name="bc_judul" value="<?php if (isset($data->bc_judul)) { echo $data->bc_judul; } ?>"/>
		    </div>		    
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Isi Broadcast</label>
		    <div class="col-sm-10">
			    <textarea class="form-control" name="bc_isi" rows="5"><?php if (isset($data->bc_isi)) { echo stripslashes($data->bc_isi); } ?></textarea>
		    </div>		    
		</div>
		<div class="form-group row">
	        <label class="col-sm-2 col-form-label">Gambar</label>
	        <div class="col-sm-10">
	            <input type="file" class="form-control" name="gambar" id="imgInp" />
	            <small class="form-text text-muted">Maksimal Ukuran Gambar 1MB. Sangat disarankan lebar max. 500px</small>
	            <div class="col-sm-5">
	            <img id="blah" src="<?php 
	            if (isset($data->bc_image) && $data->bc_image != '') {
	            	$upload_dir = wp_upload_dir();
	            	echo $upload_dir['baseurl'].'/pic/'.$data->bc_image;
	            } else {
	            	echo plugins_url('wafu_memberarea/kosong.png');
	            }
	            ?>" alt="" class="img-fluid" />
	          	</div>
	        </div>
	    </div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Grup Target</label>
		    <div class="col-sm-10">
			    <select multiple name="bc_target[]">
			    	<?php
			    	foreach ($datagrup as $key => $value) {
			    		echo '<option value="'.$key.'"';
			    		if (isset($datatarget) && in_array($key, $datatarget)) {
			    			echo ' selected';
			    		}
			    		echo '>'.$value.'</option>';
			    	}
			    	?>
			    	<small class="form-text text-muted">Bisa pilih lebih dari 1 grup</small>
			    </select>
		    </div>		    
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Syarat Subscriber</label>
	    <div class="col-sm-10">
	    	<input type="checkbox" name="khususdone" value="1"
	    	<?php if (isset($data->khususdone) && $data->khususdone == 1) { echo ' checked'; } ?>
	    	> Sudah menyelesaikan campaign
	    	<br/><input type="checkbox" name="khususchat" value="1"
	    	<?php if (isset($data->khususchat) && $data->khususchat == 1) { echo ' checked'; } ?>
	    	> Sudah kirim chat lebih dari 2x
	    </div>
		</div>
    	<div class="form-group row">
			<label class="col-sm-2 col-form-label">Jadwal Broadcast</label>
			<div class="col-sm-5">
				<div class="input-append date form_datetime" data-date="<?php if (isset($data->bc_tglkirim)) { echo $data->bc_tglkirim; } else { echo current_time('Y-m-d H:i:s'); } ?>" data-date-format="dd-mm-yyyy hh:ii" data-link-field="dtp_input1">            
				    <input class="form-control" size="16" type="text" value="<?php if (isset($data->bc_tglkirim)) { echo $data->bc_tglkirim; } else { echo current_time('Y-m-d H:i:s'); } ?>" readonly>
				    <span class="add-on"><span class="icon-remove"></span></span>
					<span class="add-on"><span class="icon-th"></span></span>
				</div>
				<input type="hidden" name="bc_tglkirim" id="dtp_input1" value="<?php if (isset($data->bc_tglkirim)) { echo $data->bc_tglkirim; } else { echo current_time('Y-m-d H:i:s'); } ?>" />
				<small class="form-text text-muted">Pastikan setting <a href="options-general.php">timezone</a> wordpress sudah sesuai daerah anda agar lebih akurat</small>
			</div>		    
		</div>

		<input type="submit" value="<?php echo $act; ?> Broadcast" class="btn btn-success"/>
	</form>
<?php	
} else {
	echo '<p><a href="wafu-grup">Klik di sini</a> untuk membuat grup terlebih dahulu</p>';
}
?>
<p>&nbsp;</p>
<div id="show"></div>
</div>
