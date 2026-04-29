<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (isset($_POST['service']) && $_POST['service'] != '') {
	foreach ($_POST as $key => $value) {
		if ($value != '') {
			if ($key == 'kode_unsubscribe') {
				$wafusetting[$key] = preg_replace("/[^a-zA-Z0-9\s]/", "", $value);
			} else {
				$wafusetting[$key] = sanitize_text_field($value);
			}
		}
	}

	if (!isset($_POST['wafu_test'])) {
		unset($wafusetting['wafu_test']);
	}
	update_option('wafusetting',$wafusetting);	
	echo '
	<div class="notice notice-success is-dismissible">
		<p>Update berhasil</p>
	</div>';
} else {
	$wafusetting = get_option('wafusetting');
}
$dir = str_replace('wafu_settings.php','service',__FILE__);
$file = scandir($dir);
if (is_array($file) && count($file) > 3) {
	$i = 0;
	foreach ($file as $file) {
		if ($file != '.' && $file != '..' && $file != 'index.php') {			
			$konten = file_get_contents($dir.'/'.$file);
			$konten = wafu_getcontent($konten,'/*','*/');
			if ($konten != '') {
				$services[$i]['file'] = str_replace('.php','',$file);
				$konten = explode("\n",$konten);
				$j = 0;

				foreach ($konten as $line) {				
					if (strpos($line, 'Service Name') !== false) { 
						$services[$i]['name'] = str_replace('Service Name','',$line); 
						$services[$i]['name'] = trim(str_replace(':','',$services[$i]['name']));
					}
					if (strpos($line, 'Service URL') !== false) { 
						$services[$i]['url'] = str_replace('Service URL','',$line); 
						$services[$i]['url'] = trim(str_replace(':','',$services[$i]['url']));
					}
					if (strpos($line, 'API Documentation') !== false) { 
						$services[$i]['doc'] = str_replace('API Documentation','',$line); 
						$services[$i]['doc'] = trim(str_replace(':','',$services[$i]['doc']));
					}
					if (strpos($line, 'Field API') !== false) { 
						$apifield = trim(str_replace('Field API','',$line));
						$expfield = explode(':',$apifield);
						$services[$i]['data'][$j]['label'] = trim($expfield[0]);
						$services[$i]['data'][$j]['field'] = trim($expfield[1]);
						$j++;
					}
				}
			}
			$i++;
		}
	}
}

if (isset($_POST['nohp']) && $_POST['nohp'] != '') {
	$gambar = '';
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
		$filename = 'wafutest'.date('YmdHis');
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
  	$konten = $_POST['pesan'];
  	$spintax = new wafu_spin();
		$konten = $spintax->process($konten);
		$cek = wafu_kirim(wafu_format($_POST['nohp']), $konten,$gambar);
		echo '<div class="notice notice-success is-dismissible">
		<p>Respon Server WA Gateway:</p>
		<code>'.$cek.'</code></div>';
	}
}
?>
<div class="wrap">
<h1 class="wp-heading-inline">WAFU Settings</h1>
<hr class="wp-header-end">
<form action="" method="post">
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pilih Layanan</label>
	    <div class="col-sm-10">
		    <select name="service" id="service" class="form-control">
		    	<option value="">Pilih</option>
		    	<?php
		    	foreach ($services as $service) {
		    		echo '<option value="'.$service['file'].'"';
		    		if (isset($wafusetting['service']) && $service['file'] == $wafusetting['service']) { echo ' selected'; }
		    		echo '>'.$service['name'].'</option>';
		    	}
		    	?>
		    </select>
	    </div>
	</div>
	<?php
	foreach ($services as $service) {
		echo '<div id="form'.$service['file'].'">';
		if (isset($service['data']) && count($service['data']) > 0) {
			foreach ($service['data'] as $data) {
				echo '
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">'.$data['label'].'</label>
				    <div class="col-sm-10">
					    <input type="text" name="'.$data['field'].'" class="form-control" value="';
					    if (isset($wafusetting[$data['field']]) && $wafusetting[$data['field']] != '') {
					    	echo $wafusetting[$data['field']];
					    }
					    echo '"/>
				    </div>
				</div>
				';
			}
		}
		echo '</div>';
	}

	?>

	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Nomor WA Sender</label>
	    <div class="col-sm-5">
		    <input type="text" name="sender" class="form-control" value="<?php if (isset($wafusetting['sender'])) { echo $wafusetting['sender']; } ?>"/>
		    <small class="form-text text-muted">Masukkan nomor HP anda dg format: <code>628123456789</code></small>
	    </div>
	</div>
	<p><a data-toggle="collapse" href="#advanced" role="button" aria-expanded="false" aria-controls="info">Setting Lanjutan ⮟</a></p>
  <div class="collapse" id="advanced">
  <div class="form-group row">
		<label class="col-sm-2 col-form-label">Jam Aktif</label>
		<div class="col-sm-8">
			<div class="row">
		    <div class="col-sm-4">
			    Dari jam <select name="activestart" class="form-control" style="max-width: 200px;">
			    	<?php 
			    	for ($i=0; $i <= 24; $i++) { 
			    		$jam = str_pad($i,2,"0", STR_PAD_LEFT).':00';
			    		echo '<option value="'.$jam.'"';
			    		if (isset($wafusetting['activestart']) && $wafusetting['activestart'] == $jam) {
			    			echo ' selected';
			    		}
			    		echo '>'.str_pad($i,2,"0", STR_PAD_LEFT).':00</option>';
			    	}
			    	?>
			    </select>
			  </div>
			  <div class="col-sm-4">hingga jam
			    <select name="activeend" class="form-control" style="max-width: 200px;">
			    	<?php 
			    	for ($i=0; $i <= 24 ; $i++) { 
			    		$jam = str_pad($i,2,"0", STR_PAD_LEFT).':00';
			    		echo '<option value="'.$jam.'"';
			    		if (isset($wafusetting['activeend']) && $wafusetting['activeend'] == $jam) {
			    			echo ' selected';
			    		} elseif (!isset($wafusetting['activeend']) && $jam == 24) {
			    			echo ' selected';
			    		}
			    		echo '>'.str_pad($i,2,"0", STR_PAD_LEFT).':00</option>';
			    	}
			    	?>
			    </select>
			  </div> 
			</div>
		  <small class="form-text text-muted">Tentukan Jam kerja WAFU. Gunakan 00:00 - 24:00 jika ingin 24 jam</small>
	  </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Kode Unsubscribe</label>
	    <div class="col-sm-5">
		    <input type="text" name="kode_unsubscribe" class="form-control" value="<?php if (isset($wafusetting['kode_unsubscribe'])) { echo $wafusetting['kode_unsubscribe']; } ?>"/>
		    <small class="form-text text-muted">Gunakan huruf kecil semua untuk menghindari kesalahan</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Sebutan Nama Kosong</label>
	    <div class="col-sm-5">
		    <input type="text" name="nama_kosong" class="form-control" value="<?php if (isset($wafusetting['nama_kosong'])) { echo $wafusetting['nama_kosong']; } else { echo 'Sobat'; } ?>"/>
		    <small class="form-text text-muted">Kata pengganti untuk subscriber yang tidak mengisi nama. Contoh: Sobat</small>
	    </div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pesan Unsubscribe</label>
	    <div class="col-sm-10">
		    <input type="text" name="pesan_unsubscribe" class="form-control" value="<?php if (isset($wafusetting['pesan_unsubscribe'])) { echo $wafusetting['pesan_unsubscribe']; } ?>"/>
		    <small class="form-text text-muted">Pesan yang dikirimkan ketika unsubscribe</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pesan saat Salah Kode</label>
	    <div class="col-sm-10">
		    <input type="text" name="salah_kode" class="form-control" value="<?php if (isset($wafusetting['salah_kode'])) { echo $wafusetting['salah_kode']; } ?>"/>
		    <small class="form-text text-muted">Pesan yang dikirimkan jika salah mengirimkan kode grup yang di-unsubscribe</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pesan jika nomor sudah terdaftar</label>
	    <div class="col-sm-10">
		    <input type="text" name="pesan_terdaftar" class="form-control" value="<?php if (isset($wafusetting['pesan_terdaftar'])) { echo $wafusetting['pesan_terdaftar']; } ?>"/>
		    <small class="form-text text-muted">Pesan yang dikirimkan jika nomor subscriber telah terdaftar</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Pesan nomor terdaftar dan Update nama</label>
	    <div class="col-sm-10">
		    <input type="text" name="pesan_update" class="form-control" value="<?php if (isset($wafusetting['pesan_update'])) { echo $wafusetting['pesan_update']; } ?>"/>
		    <small class="form-text text-muted">Pesan yang dikirimkan jika nomor subscriber telah terdaftar dan dia menyertakan nama. Maka nama akan diupdate menjadi nama yg baru. Gunakan kode <code>[nama]</code> untuk memunculkan nama yang baru.</small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Test Webhook</label>
	    <div class="col-sm-10">
		    <input type="checkbox" name="wafu_test" value="1" <?php if (isset($wafusetting['wafu_test'])) { echo 'checked'; } ?>/> Centang Opsi ini agar WAFU mengembalikan pesan apapun yg diterima
	    </div>
	</div>	
	</div>	<!-- End Collapse -->
	<?php if (function_exists('wp_affiliasi')) :?>
	<h3 class="mt-2">Integrasi dg WP Affiliasi</h3>
	<?php
	/*
	- registrasi
	- upgrade
	- beli
	- prosesbeli
	- komisi
	*/
	$groups = $wpdb->get_results("SELECT * FROM `wafu_grup`");
	?>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Grup Registrasi</label>
	    <div class="col-sm-10">
		    <select name="grup_registrasi" class="form-control">
		    	<option value=""></option>
		    	<?php
		    	foreach ($groups as $grup) {
		    		echo '<option value="'.$grup->grup_id.'"';
		    		if (isset($wafusetting['grup_registrasi']) && $wafusetting['grup_registrasi'] == $grup->grup_id) {
		    			echo ' selected';
		    		}
		    		echo '>'.$grup->grup_nama.'</option>';
		    	}
		    	?>
		    </select>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Grup Upgrade</label>
	    <div class="col-sm-10">
		    <select name="grup_upgrade" class="form-control">
		    	<option value=""></option>
		    	<?php
		    	foreach ($groups as $grup) {
		    		echo '<option value="'.$grup->grup_id.'"';
		    		if (isset($wafusetting['grup_upgrade']) && $wafusetting['grup_upgrade'] == $grup->grup_id) {
		    			echo ' selected';
		    		}
		    		echo '>'.$grup->grup_nama.'</option>';
		    	}
		    	?>
		    </select>
	    </div>
	</div>
	<!--
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Grup Beli Produk</label>
	    <div class="col-sm-10">
		    <select name="grup_beli" class="form-control">
		    	<option value=""></option>
		    	<?php
		    	foreach ($groups as $grup) {
		    		echo '<option value="'.$grup->grup_id.'"';
		    		if (isset($wafusetting['grup_beli']) && $wafusetting['grup_beli'] == $grup->grup_id) {
		    			echo ' selected';
		    		}
		    		echo '>'.$grup->grup_nama.'</option>';
		    	}
		    	?>
		    </select>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Grup Proses Beli</label>
	    <div class="col-sm-10">
		    <select name="grup_prosesbeli" class="form-control">
		    	<option value=""></option>
		    	<?php
		    	foreach ($groups as $grup) {
		    		echo '<option value="'.$grup->grup_id.'"';
		    		if (isset($wafusetting['grup_prosesbeli']) && $wafusetting['grup_prosesbeli'] == $grup->grup_id) {
		    			echo ' selected';
		    		}
		    		echo '>'.$grup->grup_nama.'</option>';
		    	}
		    	?>
		    </select>
	    </div>
	</div>	
	-->
	<?php endif; ?>
	
	<input type="submit" value="Simpan" class="btn btn-success"/>
</form>
<p>&nbsp;</p>
<div class="alert alert-info mt-3" role="alert">
<p><strong>CRON JOBS Command (jalankan 2 menit sekali):</strong> <br/>
	<code>php <?php echo plugin_dir_path(__FILE__); ?>cronbc.php</code><br/>
	<code>php <?php echo plugin_dir_path(__FILE__); ?>croncampaign.php</code><br/>
	<code>php <?php echo plugin_dir_path(__FILE__); ?>cronimport.php</code><br/>
<strong>URL Webhook: </strong><code><?php echo plugins_url('wafu/wafu_webhook.php');?></code></p>
<p><a href="https://www.youtube.com/watch?v=19EcZLOhtZ8&list=PLNuPCSS--FFqltwtc46N9QEELNO6WYoEQ&index=4" target="_blank" class="btn btn-info">Panduan pemasangan cronjob WAFU</a></p>
</div>
<h2 class="mt-5">Test Kirim WhatsApp</h2>
<form action="" method="post" enctype="multipart/form-data">
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Nomor Tujuan</label>
	    <div class="col-sm-10">
		    <input type="text" name="nohp" value="" placeholder="format: 08123456789"/>
	    </div>
	</div>
	<div class="form-group row">
        <label class="col-sm-2 col-form-label">Gambar</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="gambar" id="imgInp" />
            <small class="form-text text-muted">Maksimal Ukuran Gambar 1MB.</small>
            <img id="blah" src="<?php echo plugins_url('wafu/kosong.png');?>" alt="" class="img-fluid" />
        </div>
    </div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Isi Pesan</label>
	    <div class="col-sm-10">
		    <textarea class="form-control" name="pesan" rows="7"></textarea>
	    </div>
	</div>
	<input type="submit" value="Kirim WA" class="btn btn-info"/>
</form>


</div>
<script>
	var $jset = jQuery.noConflict();
	$jset(function(){
		<?php
		$hide = '';
		foreach ($services as $service) {
			$hide .= "\n".'$jset("#form'.$service['file'].'").hide();';
		}

		echo $hide;
		if (isset($wafusetting['service'])) { 
			echo "\n".'$jset("#form'.$wafusetting['service'].'").show();'; 
		}
		echo '
		$jset("#service").change(function () {
			'.$hide.'
			var text = $jset("#service").val();
			$jset("#form"+text).show();
		});';
		?>
	});
</script>