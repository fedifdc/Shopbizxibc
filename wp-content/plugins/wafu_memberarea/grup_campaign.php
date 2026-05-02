<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; }
global $wpdb;
if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$current_user_id = intval($_GET['user_id']);
} else {
	$current_user_id = get_current_user_id();
}
if (isset($_GET['campaign']) && is_numeric($_GET['campaign'])) :
	$grupaktif = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm` WHERE `grup_id`=".$_GET['campaign']." AND `camp_user_wp`=".$current_user_id);
	echo '
	<div class="wrap">
	<h1 class="wp-heading-inline">Campaign '.$grupaktif->grup_nama.'</h1>
	<hr class="wp-header-end">
	';
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
		$filename = 'wafucamp'.date('YmdHis');
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

    if (isset($txterror) && $txterror != '') {
    	echo '<div class="notice notice-warning is-dismissible">
				<p>'.$txterror.'</p></div>';
    } else {
		if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
			if (isset($_POST['camp_title']) && isset($_POST['camp_grup'])) {
				if (isset($gambar) && $gambar != '') {
					$dbgb = "`camp_image`='".$gambar."',";
				} else {
					$dbgb = '';
				}
				$wpdb->query("UPDATE `wafu_campaign_mlm` SET 
					`camp_grup` = '".$wpdb->_real_escape($_POST['camp_grup'])."',
					`camp_title` = '".$wpdb->_real_escape($_POST['camp_title'])."',
					`camp_content` = '".$wpdb->_real_escape($_POST['camp_content'])."',
					".$dbgb."
					`camp_delay` = '".$wpdb->_real_escape($_POST['camp_delay'])."',
					`camp_periode` = '".$wpdb->_real_escape($_POST['camp_periode'])."',
					`camp_sort` = '".$wpdb->_real_escape($_POST['camp_sort'])."',
					`camp_next_grup` = '".$_POST['camp_next_grup']."',
					`camp_user_wp` = '".$current_user_id."'
					WHERE `camp_id`=".$_GET['edit']
				);
				if ($wpdb->last_error !== '') {
				    $wpdb->print_error();
				} else {
					echo '<div class="notice notice-success is-dismissible">
					<p>Update berhasil. <a href="wafu-grup?campaign='.$_GET['campaign'].'">Kembali ke campaign</a></p>
					</div>';
				}
			}
			$data = $wpdb->get_row("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_id`=".$_GET['edit']." AND `camp_user_wp`=".$current_user_id);
			if (!$data) {
				echo '<div class="notice notice-error is-dismissible">
					<p>Campaign tidak ditemukan</p>
					</div>';
				exit;
			}
		} else {
			if (isset($_POST['camp_title']) && isset($_POST['camp_grup'])) {
				if (isset($gambar) && $gambar != '') { 
					# Diam aja
				} else {
					$gambar = '';
				}
				// print ("<pre>");
				// print_r($_POST);
				// print("</pre>");
				// die(0);
				$wpdb->query("INSERT INTO `wafu_campaign_mlm` 
					(`camp_grup`,`camp_title`,`camp_content`,`camp_image`,`camp_delay`,`camp_periode`,`camp_sort`,`camp_user_wp`,`camp_next_grup`) VALUES 
					(
					'".$wpdb->_real_escape($_POST['camp_grup'])."',
					'".$wpdb->_real_escape($_POST['camp_title'])."',
					'".$wpdb->_real_escape($_POST['camp_content'])."',					
					'".$gambar."',
					'".$wpdb->_real_escape($_POST['camp_delay'])."',
					'".$wpdb->_real_escape($_POST['camp_periode'])."',
					'".$wpdb->_real_escape($_POST['camp_sort'])."',
					'".$current_user_id."',
					'".$_POST['camp_next_grup']."'
					)
					");
				if($wpdb->last_error !== '') {
				    echo '<div class="notice notice-warning is-dismissible">';
				    $wpdb->print_error();
				    echo '</div>';
				} else {
					echo '<div class="notice notice-success is-dismissible">
					<p>Campaign telah ditambahkan</p>
					</div>';
				}
			} elseif (isset($_POST['camp'])) {
				foreach ($_POST['camp'] as $sort) {
					$wpdb->query("UPDATE `wafu_campaign_mlm` SET `camp_sort`=".$sort['sort']." WHERE `camp_id`=".$sort['id']);
				}
				echo '<div class="notice notice-success is-dismissible">
					<p>Campaign telah diurutkan</p>
					</div>';
			} elseif (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
				$wpdb->query("DELETE FROM `wafu_campaign_mlm` WHERE `camp_id`=".$_GET['hapus']);
			} 		

			$camp = $wpdb->get_results("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$_GET['campaign']." AND `camp_user_wp`=".$current_user_id." ORDER BY `camp_sort`");
			$i = 1;
			if (count($camp) > 0) {
				echo '
			<form action="" method="post" class="mb-5">
			<table class="table"">
			<thead class="thead thead-dark">
				<tr><th>Judul</th><th>Delay</th><th>Sort</th><th>Action</th></tr>
			</thead>
			<tbody>
			';
				$periode = array('','Hari','Jam','Menit');
				foreach ($camp as $camp) {
					echo '
				<tr>
				<td>';
				if (isset($camp->camp_image) && $camp->camp_image != '') {
					$upload_dir = wp_upload_dir();
					echo '<img src="'.$upload_dir['baseurl'].'/pic/'.$camp->camp_image.'" style="max-height:30px; float:left; margin-right:10px;">';
				}
				echo $camp->camp_title.'</td>
				<td>'.intval($camp->camp_delay).' '.$periode[$camp->camp_periode].'</td>
				<td><input type="number" style="width:60px" name="camp['.$i.'][sort]" value="'.$camp->camp_sort.'"/>
				<input type="hidden" name="camp['.$i.'][id]" value="'.$camp->camp_id.'"/></td>
				<td><a href="wafu-grup?campaign='.$_GET['campaign'].'&edit='.$camp->camp_id.'" class="btn btn-success btn-sm">Edit</a> &nbsp;
				<a href="wafu-grup?campaign='.$_GET['campaign'].'&hapus='.$camp->camp_id.'" class="btn btn-danger btn-sm">Hapus</a></td>
				</tr>';
					$i++;
				}
				echo '</tbody>
				</table>
			<input type="submit" value="Update Sort" class="btn btn-success"/>
			</form>';
			}
		}
    }

?>
<h3><?php if (isset($_GET['edit'])) { echo 'Edit'; } else { echo 'Buat'; } ?> Campaign</h3>
<form action="" method="post" enctype="multipart/form-data">
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Judul Campaign</label>
	    <div class="col-sm-10">
		    <input type="text" class="form-control" name="camp_title" value="<?php if (isset($data->camp_title)) { echo $data->camp_title; } ?>"/>
	    </div>		    
	</div>	
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Isi Campaign</label>
	    <div class="col-sm-10">
		    <textarea name="camp_content" class="form-control" rows="7"><?php 
		    if (isset($data->camp_content)) { echo stripslashes($data->camp_content); } ?></textarea>		    
		    <small class="form-text text-muted">Gunakan kode berikut untuk menampilkan data member:<br/>
		    	- <code>[nama]</code> : Nama member<br/>
				- <code>[gruplink]</code> : untuk pindah grup<br/>
		    <?php 
		    if ($grupaktif->grup_custom != '') {
		    	$custom = explode("\n",$grupaktif->grup_custom);
		    	foreach ($custom as $custom) {
		    		echo '- <code>['.trim($custom).']</code><br/>';
		    	}
		    }
		    ?>
		    </small>
	    </div>		    
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Grup Selanjutnya</label>
		<div class="col-sm-10">
			<select class="form-control" name="camp_next_grup">
				<?php 
				$grup_list = $wpdb->get_results("SELECT * FROM `wafu_grup_mlm` WHERE `camp_user_wp`=".$current_user_id);
				foreach ($grup_list as $grup) {
					$selected = (isset($data->camp_next_grup) && $data->camp_next_grup == $grup->grup_code) ? ' selected' : '';
					echo '<option value="'.$grup->grup_id.'"'.$selected.'>'.$grup->grup_nama.'</option>';
				}
				?>
			</select>
		</div>		    
	</div>
	<div class="form-group row">
        <label class="col-sm-2 col-form-label">Gambar</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="gambar" id="imgInp" />
            <small class="form-text text-muted">Maksimal Ukuran Gambar 1MB.</small>
            <img id="blah" src="<?php 
            if (isset($data->camp_image) && $data->camp_image != '') {
            	$upload_dir = wp_upload_dir();
            	echo $upload_dir['baseurl'].'/pic/'.$data->camp_image;
            } else {
            	echo plugins_url('wafu_memberarea/kosong.png');
            }
            ?>" alt="" class="img-fluid" />
        </div>
    </div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Delay</label>
	    <div class="col-sm-4">
			  <div class="input-group"> 
          <input type="text" class="form-control" name="camp_delay" value="<?php if (isset($data->camp_delay)) { echo intval($data->camp_delay); } else { echo 1; } ?>"/>
          <?php 
          $periode = array('','','',''); 
          if (isset($data->camp_periode)) {
          	$periode[$data->camp_periode] = ' selected';
          }
          ?>
          <select name="camp_periode" class="form-control">
              <option value="1"<?php echo $periode[1];?>>Hari</option>
              <option value="2"<?php echo $periode[2];?>>Jam</option>
              <option value="3"<?php echo $periode[3];?>>Menit</option>
          </select>
          <small class="form-text text-muted">Jarak waktu dg pesan sebelumnya.</small>
				</div>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Sort</label>
	    <div class="col-sm-2">
		    <input type="number" class="form-control" name="camp_sort" value="<?php if (isset($data->camp_sort)) { echo $data->camp_sort; } else { echo $i; } ?>" />
	    </div>		    
	</div>
	<input type="hidden" name="camp_grup" value="<?php echo $_GET['campaign'];?>"/>
	<input type="submit" value="<?php if (isset($_GET['edit'])) { echo 'Edit'; } else { echo 'Buat'; } ?> Campaign" class="btn btn-success"/>
</form>
</div>
<?php endif; ?>
