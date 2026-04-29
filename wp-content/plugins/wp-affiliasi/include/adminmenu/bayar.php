<?php 
if (!defined('IS_IN_SCRIPT')) { die();  exit; } 
if (!current_user_can('manage_options')) { die; exit(); } 

echo '<div class="wrap">
<h1 class="wp-heading-inline">Bayar Komisi</h1>
<a class="page-title-action" href="https://cafebisnis.com/pwa.php?id=33" target="_blank" role="button" aria-expanded="false">Bantuan</a>';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$data = $wpdb->get_results("SELECT * FROM `cb_laporan` WHERE `id_sponsor`=".$_GET['id']);
	if (is_array($data) && count($data) > 0) {
		$total = $totalkredit = $totaldebet = 0;
		echo '
		<table class="table">
		<thead class="thead thead-dark">
			<tr><th>Tanggal</th>
			<th>Transaksi</th>
			<th class="text-right">Debet</th>
			<th class="text-right">Kredit</th>
			<th class="text-right">Saldo</th>
			</tr>
		</thead>
		<tbody>';
		foreach ($data as $data) {			
			if ($data->keterangan != 'trx') {
				echo '<tr><td>'.$data->tanggal.'</td>
				<td>'.$data->transaksi.'</td>';
				if ($data->keterangan == 'refund') {
					echo '
					<td class="text-right">'.number_format(($data->komisi)*-1).'</td>
					<td class="text-right">0</td>';
					$total = $total+($data->komisi);
				} else {
					echo '
					<td class="text-right">'.number_format($data->debet).'</td>
					<td class="text-right">'.number_format($data->komisi).'</td>';
					$total = $total+($data->komisi-$data->debet);
				}
				$totalkredit = $totalkredit + $data->komisi;
				$totaldebet = $totaldebet + $data->debet;
				echo '
				<td class="text-right">'.number_format($total).'</td>
				</tr>';
			}
		}
		echo '
		<tr>
		<td colspan="2" class="text-right"><strong>TOTAL</strong></td>
		<td class="text-right">'.number_format($totaldebet).'</td>
		<td class="text-right">'.number_format($totalkredit).'</td>
		<td>&nbsp;</td></tr>
		</tbody>
		</table>';
	}
} else {
	if (isset($_POST['bayar']) && is_numeric($_POST['bayar'])) {
		$wpdb->query("UPDATE `wp_member` SET `sisa_voucher`=`sisa_voucher`-".$_POST['bayar']." WHERE `idwp`=".$_POST['iduser']);
		
		// Ambil data member	
		$checkupline = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp` = ".$_POST['iduser']);
		
		// Masukkan laporan keuangan 
		$id_order = time();
		$transaksi = 'Pembayaran Komisi '.$checkupline->nama;
		$wpdb->query("INSERT INTO `cb_laporan` 
					(`tanggal`,`transaksi`,`debet`,`kredit`,`komisi`,`keterangan`,`id_user`,`id_sponsor`,`id_order`)
					VALUES  ('".wp_date('Y-m-d H:i:s')."','".$transaksi."',".$_POST['bayar'].",0,0,'wd',0,".$_POST['iduser'].",".$id_order.")");
		
		$datalain = array(
			'komisi' => $_POST['bayar']
		);

		cb_notif($checkupline->id_user,'komisi',$datalain);

		echo '<div id="message2" class="notice notice-success is-dismissible"><p><b>'.$checkupline->nama.'</b> Sudah Dibayar</p></div>';
		$showtxt = '';
		$showtxt = apply_filters('cbaff_bayar_sukses',$showtxt,$checkupline->id_user);
	}

	$options = get_option("cb_pengaturan");
	$limit = $options['limit'];
	if ($limit == '') { $limit = 1; }
	$member = $wpdb->get_results("SELECT * FROM `wp_member` WHERE `sisa_voucher`>= ".$limit." ORDER BY `sisa_voucher` DESC");
		
	echo'
		<table class="widefat">
		<thead>
		<tr>
			<th scope="col"  width="35%">Nama Lengkap</th>
			<th scope="col"  width="35%">Data Bank</th>
			<th scope="col"  width="30%">Jumlah Uang</th>
		</tr>
		</thead>
		<tbody>';
	if (isset($member)) {
		$totalbayar = 0;
		foreach ($member as $member) {
		echo '
		<tr>
			<td><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=cbaf_memberlist&profil='.$member->idwp.'">'.$member->nama.'</a> <br>
			('.$member->username.')</td>';
		if ($member->rekening && $member->bank) {
		echo '
			<td><b>'.$member->bank.'</b><br>a/n. '.$member->ac.'<br>'.$member->rekening.'</td>';
		} else {
		echo '
			<td>Belum mengisi rekening</td>';
		}
		echo'
			<td>
			<form action="" method="post">
			Sisa : <a href="admin.php?page=cbaf_bayar&id='.$member->idwp.'">'.number_format($member->sisa_voucher).'</a><br>
			Bayar : <input type="text" name="bayar" size="5" value="'.$member->sisa_voucher.'">
			<input type="hidden" name="iduser" value="'.$member->idwp.'">
			<input type="submit" class="button button-primary" value="Go">
			</form>
			</td>
		</tr>';	
		$totalbayar = $totalbayar+$member->sisa_voucher;
		}
	} else {
		echo '<tr><td colspan="3" align="center">Belum ada member yang dibayar</td></tr>';
	}
	echo '
		</tbody>
		</table>
		<p align="center">TOTAL PEMBAYARAN : <b>Rp. '.number_format($totalbayar).',-</b></p>
		<p>&nbsp;</p>';
}
echo '</div>'; //End Wrap
?>