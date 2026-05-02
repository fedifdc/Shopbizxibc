<?php
global $wpdb;
if (!defined('WAFU_SCRIPT')) { die();  exit; }
if (isset($_GET['import']) && is_numeric($_GET['import'])) {
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm` WHERE `grup_id`=".$_GET['import']);
	if (!isset($grup->grup_nama)) {
		echo '<div class="wrap"><h2>Maaf, grup tidak ditemukan</h2></div>';
		exit;
	}
} else {
	exit;
}
if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
	$current_user_id = intval($_GET['user_id']);
	$is_admin_page = true;
} else {
	$is_admin_page = false;
	$current_user_id = get_current_user_id();
}
?>
<div class="wrap">
<h1 class="wp-heading-inline">Import Subscriber ke <?php echo $grup->grup_nama;?></h1>
<hr class="wp-header-end">
<?php
$abjad = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if (isset($_FILES['filemember']['name'])) {
	$upload_dir = wp_upload_dir();
  $targetPath = $upload_dir['basedir'] .'/'. $_FILES['filemember']['name'];
  move_uploaded_file($_FILES['filemember']['tmp_name'], $targetPath);

  include "xlsxparser.php";
  $insert_member = '';
  $i = 0;
	if ( $members = SimpleXLSX::parse($targetPath) ) {
	    foreach ($members->rows() as $member) {	    		
	  		$nowa = wafu_format_mlm($member[($_POST['nowa'])]);
	  		if ($nowa != '' && strlen($nowa) > 8) {
	    		if (isset($_POST['custom']) && count($_POST['custom']) > 0) {
	    			foreach ($_POST['custom'] as $custom => $value) {
	    				if (isset($member[$value])) {
	    					$datacustom[$custom] = $wpdb->_real_escape($member[$value]);
	    				}
	    			}
	    			if (isset($datacustom) && is_array($datacustom)) {
	    				$dbcustom = serialize($datacustom);
	    			} else {
	    				$dbcustom = '';
	    			}
	    		} else {
	    			$dbcustom = '';
	    		}

	    		$insert_member .= "(".$grup->grup_id.",'".$wpdb->_real_escape($member[($_POST['nama'])])."','".$nowa."','".$dbcustom."','".$current_user_id."'),";
	    		$i++;
	  		}
	  	}

	  	if ($insert_member != '') {
		  	$wpdb->query("INSERT INTO `wafu_import_mlm` (`import_grup`,`import_nama`,`import_wa`,`import_custom`,`camp_user_wp`) 
		  		VALUES ".substr($insert_member,0,-1));
				
				echo '<div class="notice notice-success is-dismissible">
				<p>Berhasil Import '.$i.' member. Data akan masuk ke memberlist secara bertahap untuk validasi. Pastikan anda menjalankan file cronimport.php melalui cronjob.</p>
				</div>';
		} else {
			echo '<div class="notice notice-error is-dismissible">
			<p>Tidak ada data yang diimpor. Sepertinya <strong>Kolom '.substr($abjad,$_POST['nowa'],1).'</strong> tidak berisi nomor WhatsApp.</p>
			</div>';
		}
	} else {
	    echo '<div class="notice notice-error is-dismissible"><p>'.SimpleXLSX::parseError().'</p></div>';
	}
}
?> 
	<form action="" enctype="multipart/form-data" method="post">
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">File Excel</label>
		    <div class="col-sm-6">
			    <input type="file" class="form-control" name="filemember" accept=".xls,.xlsx">
			    <small class="form-text text-muted">Gunakan format xlsx atau xls</small>
		    </div>		    
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Kolom Nama</label>
		    <div class="col-sm-2">
		    	<select name="nama">
			    <?php
			    for ($i=0; $i < 26; $i++) { 
			    	echo '<option value="'.$i.'">'.substr($abjad,$i,1).'</option>';

			    }
			    ?>
			    </select>
		    </div>		    
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Kolom No. WA</label>
		    <div class="col-sm-2">
			    <select name="nowa">
			    <?php
			    for ($i=0; $i < 26; $i++) { 
			    	echo '<option value="'.$i.'"';
			    	if ($i == 1) { echo ' selected'; }
			    	echo '>'.substr($abjad,$i,1).'</option>';
			    }
			    ?>
			    </select>
		    </div>		    
		</div>
		<?php 
		if (isset($grup->grup_custom) && $grup->grup_custom != '') {
			$custom = explode("\n", $grup->grup_custom);
			if (is_array($custom) && count($custom) > 0) {
				$kol = 2;
				foreach ($custom as $custom) {
					echo '
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">'.$custom.'</label>
					    <div class="col-sm-2">
						    <select name="custom['.trim($custom).']">';						    
						    for ($i=0; $i < 26; $i++) { 
						    	echo '<option value="'.$i.'"';
						    	if ($i == $kol) { echo ' selected'; }
						    	echo '>'.substr($abjad,$i,1).'</option>';
						    }
						    echo '
						    </select>
					    </div>		    
					</div>
					';
					$kol++;
				}
			}
		}
		?>
		<input type="submit" value="Import" class="btn btn-success"/>
	</form>

	<?php 
	if (isset($_GET['del']) && is_numeric($_GET['del'])) {
		$wpdb->query("DELETE FROM `wafu_import_mlm` WHERE `import_id`=".$_GET['del']);
		echo '<div class="notice notice-success is-dismissible mt-3"><p>Data telah dihapus</p></div>';
	}

	$perpage = 20;
	if (isset($_GET['start']) && is_numeric($_GET['start'])) {
		$start = ($_GET['start']-1)*$perpage;
	} else {
		$start = 0;
	}
	$data = $wpdb->get_results("SELECT * FROM `wafu_import_mlm` 
		LEFT JOIN `wafu_grup_mlm` ON `wafu_grup_mlm`.`grup_id` = `wafu_import_mlm`.`import_grup`
		WHERE `wafu_import_mlm`.`camp_user_wp`  =". $current_user_id."
		LIMIT ".$start.",".$perpage);
	if (count($data) > 0) {
		echo '
		<h2 class="mt-2">Daftar Import</h2>
<div class="table-responsive">
	<table class="table">
	<thead class="thead thead-dark">
		<tr><th>NAMA</th><th>WHATSAPP</th><th>GRUP</th><th>&nbsp;</th></tr>
	</thead>
	<tbody>';
		foreach ($data as $data) {
			if($is_admin_page) {
				echo '
				<tr><td>'.$data->import_nama.'</td>
				<td>'.$data->import_wa.'</td>
				<td>'.$data->grup_nama.'</td>
				<td><a href="admin.php?page=wafu_member_aff&import='.$_GET['import'].'&del='.$data->import_id.'&user_id='.$current_user_id.'" class="btn btn-sm btn-danger">Delete</a></td>
					';
			} else {
				
			echo '
				<tr><td>'.$data->import_nama.'</td>
				<td>'.$data->import_wa.'</td>
				<td>'.$data->grup_nama.'</td>
				<td><a href="wafu-member?import='.$_GET['import'].'&del='.$data->import_id.'" class="btn btn-sm btn-danger">Delete</a></td>
					';
			}
		}
		echo '
	</tbody>
	</table>
</div>';

		
$jml = $wpdb->get_var("SELECT COUNT(*) FROM `wafu_import_mlm`"); 
$jmlpage = floor(($jml/$perpage)+1);

echo '
Jumlah Data: '.$jml.' <a href="admin.php?page=wafu_member&delall=yes" 
title="Hapus semua daftar import" class="btn btn-sm btn-danger">Delete All</a>
<nav aria-label="Page navigation example" style="margin-top:20px">
  <ul class="pagination">';

$urlpage = '';
if (isset($_GET['start']) && is_numeric($_GET['start'])) {
	$page = $_GET['start'];
} else {
	$page = 1;
}

foreach ($_GET as $get => $value) {
	if ($get != 'start') {
		$urlpage .= $get.'='.$value.'&';
	}
}

if ($jmlpage > 10) {
	if ($page <= 5){
		for ($i=1;$i<=5;$i++) {			
			echo '<li class="page-item';
			if ($page == $i) { echo ' active'; }
			echo '"><a class="page-link" href="?'.$urlpage.'start='.$i.'">'.$i.'</a></li> ';
		}
		echo '... <li class="page-item"><a class="page-link" href="?'.$urlpage.'start='.$jmlpage.'">'.$jmlpage.'</a></li> ';
	} elseif ($page > 5 && $page < ($jmlpage-5)) {
		echo '<li class="page-item"><a class="page-link" href=?page=cbaf_memberlist&start=1">1</a></li> ... ';
		for ($i=($page-5);$i<=($page+5);$i++) {
			if ($i == $page) {
			echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
			} else {
			echo '<li class="page-item"><a class="page-link" href="?'.$urlpage.'start='.$i.'">'.$i.'</a></li> ';
			}
		}
		echo '... <li class="page-item"><a class="page-link" href="?'.$urlpage.'start='.$jmlpage.'">'.$jmlpage.'</a></li> ';
	} else {
		echo '<li class="page-item"><a class="page-link" href="?page='.$urlpage.'start=1">1</a></li> ... ';
		for ($i=($jmlpage-5);$i<=$jmlpage;$i++) {
			if ($i == $page) {
				echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" href="?'.$urlpage.'start='.$i.'">'.$i.'</a></li> ';
			}
		}
	}
} else {
	for ($i=1;$i<=$jmlpage;$i++) {
		if ($i == $page) {
		echo '<li class="page-item active"><span class="page-link">'.$i.'</span></li>';
		} else {
		echo '<li class="page-item"><a class="page-link" href="?'.$urlpage.'start='.$i.'">'.$i.'</a></li> ';
		}
	}
}

echo '
  </ul>
</nav>';
	}
	?>
</div>