<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; }
?> 
<div class="wrap">
<h1 class="wp-heading-inline">Daftar Subscriber
<?php 
if (isset($_GET['grup']) && is_numeric($_GET['grup'])) {
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_id`=".$_GET['grup']);
	if ($grup->grup_id != '') {
		echo '<a href="admin.php?page=wafu_member&grup='.$grup->grup_id.'">'.$grup->grup_nama.'</a>';
		$idgrup = $grup->grup_id;
	}	else {
		$idgrup = '';
	}
} else {
	$idgrup = '';
}
?>
</h1>
<br/>
<?php 
if (isset($idgrup) && is_numeric($idgrup)) {
	echo '<a href="admin.php?page=wafu_member&add='.$idgrup.'" class="page-title-action">Add Subscriber</a>
	<a href="admin.php?page=wafu_member&import='.$idgrup.'" class="page-title-action">Import</a>
	<a href="'.plugins_url('wafu/downloadmember.php').'?grup='.$idgrup.'" class="page-title-action">Download</a>';
} else {
	echo '<a href="'.plugins_url('wafu/downloadmember.php').'" class="page-title-action">Download</a>';
	echo '<a href="admin.php?page=wafu_member&nogrup=1" class="page-title-action">No Grup Subs</a>';
}
?>
<hr class="wp-header-end mb-3">
<?php
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
	$wpdb->query("DELETE FROM `wafu_member` WHERE `member_id`=".$_GET['hapus']);
	$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$_GET['hapus']);
	if ($wpdb->last_error !== '') {
	    $wpdb->print_error();
	} else {
		echo '<div class="notice notice-success is-dismissible">
		<p>Subscriber telah dihapus</p>
		</div>';
	}
} elseif (isset($_GET['unsub']) && is_numeric($_GET['unsub'])) {
	$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `gtm_id`=".$_GET['unsub']);
	if ($wpdb->last_error !== '') {
	    $wpdb->print_error();
	} else {
		echo '<div class="notice notice-success is-dismissible">
		<p>Subscriber telah dikeluarkan dari grup</p>
		</div>';
	}
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	if (isset($_POST['member_nama']) && isset($_POST['member_wa'])) {		
		$wpdb->query("UPDATE `wafu_member` SET 
			`member_nama`='".sanitize_text_field($_POST['member_nama'])."',
			`member_wa`='".wafu_format($_POST['member_wa'])."' 
			WHERE `member_id`=".$_GET['id']);
		if (isset($_POST['custom']) && is_array($_POST['custom']) && isset($_GET['grup'])) {
			$dbcustom = serialize($_POST['custom']);
			$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_customdata` = '".$dbcustom."' 
				WHERE `member_id`=".$_GET['id']." AND `grup_id`=".$_GET['grup']);
		}
		echo '<div class="notice notice-success is-dismissible">
			<p>Member telah diupdate</p>
			</div>';
	}

	$member = $wpdb->get_row("SELECT * FROM `wafu_member` WHERE `member_id`=".$_GET['id']);

	if (isset($_GET['unsub']) && is_numeric($_GET['unsub'])) {
		# unsubscribe
		$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `gtm_id`=".$_GET['unsub']);
		if ($wpdb->last_error !== '') {
	    	$wpdb->print_error();
		} else {
			echo '<div class="notice notice-success is-dismissible">
			<p>Member telah berhenti berlangganan</p>
			</div>';
		}
	} elseif (isset($_GET['sub']) && is_numeric($_GET['sub'])) {
		# subscribe
		# Dapatkan pesan selamat datang dan next post
		$data = $wpdb->get_results("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$_GET['sub']." 
			ORDER BY `camp_sort` LIMIT 0,2");
		if (count($data) > 1) {
			$i = 0;
			foreach ($data as $camp) {
				if ($i == 0) {
					if ($member->member_nama != '') {
						$konten = str_replace('[nama]',$member->member_nama,$camp->camp_content);
					} else {
						$konten = str_replace('[nama]','Sobat',$camp->camp_content);
					}
					wafu_kirim($member->member_wa,$konten);
				} elseif ($i == 1) {
					$datenow = strtotime(current_time('Y-m-d H:i:s'));
					$wpdb->query("INSERT INTO `wafu_gruptomember` 
						(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_tglgabung`) VALUES 
						(".$member->member_id.",".$_GET['sub'].",".$camp->camp_id.",'".date('Y-m-d H:i:s',
							strtotime('+1 day', $datenow))."',
						0,1,'".current_time('Y-m-d H:i:s')."')");
					if($wpdb->last_error !== '') {					    
					    $wpdb->print_error();
					} 
				}
				$i++;
			}
		} elseif (count($data) == 1) {
			foreach ($data as $camp) {
				if ($member->member_nama != '') {
					$konten = str_replace('[nama]',$member->member_nama,$camp->camp_content);
				} else {
					$konten = str_replace('[nama]','Sobat',$camp->camp_content);
				}
				wafu_kirim($member->member_wa,$konten);
				$datenow = strtotime(current_time('Y-m-d H:i:s'));
				$wpdb->query("INSERT INTO `wafu_gruptomember` 
						(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_tglgabung`) VALUES 
						(".$member->member_id.",".$_GET['sub'].",0,'0000-00-00 00:00:00',
						0,1,'".current_time('Y-m-d H:i:s')."')");
				if ($wpdb->last_error !== '') {					    
				    $wpdb->print_error();
				} 
			}
		}
	}	
?>
	<form action="" method="post">
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Nama</label>
		    <div class="col-sm-10">
			    <input type="text" name="member_nama" value="<?php if (isset($member->member_nama)) { echo $member->member_nama; } ?>"/>
		    </div>		    
		</div>
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">Whatsapp</label>
		    <div class="col-sm-10">
			    <input type="text" name="member_wa" value="<?php if (isset($member->member_wa)) { echo $member->member_wa; } ?>"/>
		    </div>		    
		</div>
		<?php
		if (isset($_GET['grup']) && is_numeric($_GET['grup'])) {
			$data = $wpdb->get_row("SELECT * FROM `wafu_gruptomember`,`wafu_grup` 
				WHERE `wafu_gruptomember`.`grup_id`=`wafu_grup`.`grup_id`
				AND `wafu_gruptomember`.`member_id`=".$_GET['id']."
				AND `wafu_gruptomember`.`grup_id`=".$_GET['grup']);

			if ($data->grup_custom != '') {
				$custom = explode("\n",$data->grup_custom);
				$customdata = unserialize($data->gtm_customdata);
				foreach ($custom as $custom) {
					echo '
		<div class="form-group row">
			<label class="col-sm-2 col-form-label">'.trim($custom).'</label>
		    <div class="col-sm-10">
			    <input type="text" name="custom['.trim($custom).']" value="';
			    if (isset($customdata[trim($custom)])) { echo $customdata[trim($custom)]; }
			    echo '"/>
		    </div>		    
		</div>';
					
				}
			}
		}
		?>
		<input type="submit" value="Simpan" class="btn btn-success"/>
	</form>
	<h3 class="mt-5">Grup yang diikuti</h3>
		<?php
    	$grup = $wpdb->get_results("SELECT *, `wafu_grup`.`grup_id` AS `idgrup` 
    		FROM `wafu_grup` LEFT JOIN `wafu_gruptomember` 
    		ON `wafu_grup`.`grup_id` = `wafu_gruptomember`.`grup_id` 
    		AND `wafu_gruptomember`.`member_id`=".$_GET['id']);
    	echo '<table class="table">
    	<thead>
    	<tr><th>Nama Grup</th><th>Action</th>
    	</thead>
    	<tbody>';
    	foreach ($grup as $grup) {    		
    		echo '<tr><td><a href="admin.php?page=wafu_member&grup='.$grup->idgrup.'">'.$grup->grup_nama.'</a></td>';    		
    		if (isset($grup->grup_id) && $grup->grup_id > 0) {
    			echo '
    			<td><a href="admin.php?page=wafu_member&id='.$_GET['id'].'&grup='.$grup->grup_id.'"
    			class="btn btn-success btn-sm">Lihat Data</a> &nbsp;
    			<a href="admin.php?page=wafu_member&id='.$_GET['id'].'&unsub='.$grup->gtm_id.'"
    			class="btn btn-danger btn-sm">Unsubscribe</a></td>';
    		} else {
    			echo '<td><a href="admin.php?page=wafu_member&id='.$_GET['id'].'&sub='.$grup->idgrup.'"
    			class="btn btn-info btn-sm">Subscribe</a></td>';
    		}
    		echo '</tr>';
    	}
    	echo '</tbody>
    	</table>';
    	?>
<?php
} else {

if (isset($_GET['start']) && is_numeric($_GET['start'])) {
	$start = ($_GET['start']-1)*20;
} else {
	$start = 0;
}

if (isset($_POST['keyword']) && $_POST['keyword'] != '') {
	if (is_numeric($_POST['keyword'])) {
		$keyword = wafu_format($_POST['keyword']);
		$where = " WHERE `wafu_member`.`member_wa` = '".$keyword."'";
	} else {
		$keyword = sanitize_text_field($_POST['keyword']);
		$where = " WHERE `wafu_member`.`member_nama` LIKE '%".$keyword."%'";
	}
} else {
	$where = '';
}

if (isset($_GET['grup']) && is_numeric($_GET['grup'])) {
	if (isset($where) && $where != '') { $where .= " AND "; } else { $where = " WHERE "; }
	$where .= "`wafu_gruptomember`.`grup_id`=".$_GET['grup'];
	$orderby = "`wafu_gruptomember`.`gtm_tglgabung`";
} elseif (isset($_GET['nogrup']) && $_GET['nogrup'] == 1) {
	$where = " WHERE `wafu_member`.`member_id` NOT IN (SELECT `member_id` FROM `wafu_gruptomember`)";
	$orderby = '`wafu_member`.`member_tglgabung`';
} else {
	$where .= '';
	$orderby = '`wafu_member`.`member_tglgabung`';
}

$data = $wpdb->get_results("SELECT *,`wafu_member`.`member_id` AS `idmember` FROM `wafu_member` 
	LEFT JOIN `wafu_gruptomember` ON `wafu_gruptomember`.`member_id` = `wafu_member`.`member_id` 
	".$where." 
	GROUP BY `wafu_member`.`member_id`
	ORDER BY ".$orderby." DESC LIMIT ".$start.",20");
if (count($data) > 0) {
	echo '
	<form action="" class="form mb-3" method="post">
	<div class="row">
	<div class="col-8">
	<input type="text" class="form-control" placeholder="Masukkan nama/no HP" name="keyword"';
	if (isset($_POST['keyword']) && $_POST['keyword'] != '') {
		echo 'value = "'.$_POST['keyword'].'"';
	}
	echo '/>
	</div>
	<div class="col-4">
	<input type="submit" class="btn btn-primary" value="Cari"/>
	</div>
	</div>
	</form>

	<div class="table-responsive">
	<table class="table">
	<thead class="thead thead-dark">
		<tr><th>NAMA</th><th>WHATSAPP</th><th>SCHEDULE</th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	';
	foreach ($data as $member) {
		if ($member->member_nama != '') {
			$nama = $member->member_nama;
		} else {
			$nama = 'No name';
		}
		echo '
		<tr>
		<td><a href="admin.php?page=wafu_member&id='.$member->idmember.'&grup='.$idgrup.'">'.$nama.' ('.$member->member_count.' chat)</a></td>
		<td><a href="https://wa.me/'.wafu_format($member->member_wa).'" target="_blank">'.$member->member_wa.'</a></td>
		<td>';
		if (isset($idgrup) && is_numeric($idgrup)) {
			if (isset($member->gtm_nextid) && $member->gtm_nextid > 0) {
				echo $member->gtm_schedule.'</td><td>';
			} else {
				echo 'selesai</td><td>';
			}
			echo '
		<a href="admin.php?page=wafu_member&unsub='.$member->gtm_id.'&grup='.$idgrup.'" class="btn btn-sm btn-warning">Unsubscribe</a>';
		}
		echo '
		<a href="admin.php?page=wafu_member&hapus='.$member->idmember.'&grup='.$idgrup.'" class="btn btn-sm btn-danger">Hapus</a></td>
		</tr>';
	}
	echo '</tbody>
	</table>
	</div>';
}

$jml = $wpdb->get_var("SELECT COUNT(*) FROM `wafu_member` 
	LEFT JOIN `wafu_gruptomember` ON `wafu_gruptomember`.`member_id` = `wafu_member`.`member_id` ".$where); 
$jmlpage = floor(($jml/20)+1);

echo '
Jumlah Member :'.$jml.'
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
<p>&nbsp;</p>
<div id="show"></div>
</div>