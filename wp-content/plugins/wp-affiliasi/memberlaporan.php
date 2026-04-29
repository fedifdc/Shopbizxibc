<?php
if (!defined('IS_IN_SCRIPT')) { die();  exit; } 
if (!isset($user_ID) || $user_ID == 0) { 
	$refer = $_SERVER['REQUEST_URI'];
	header("Location: ".wp_login_url($refer));
}
if (isset($_GET['page']) && $_GET['page'] == 'laporan') {
	$showtxt .= '<h2>Laporan Keuangan</h2>';
}

// if (isset($_GET['detil'])) {
// 	$exp = explode('-',$_GET['detil']);
// 	if (is_numeric($exp[0]) && is_numeric($exp[1])) {
// 		$tgl = $exp[0].'-'.$exp[1];
// 		$select = "SELECT * FROM `cb_laporan` WHERE `id_sponsor`=".$user_ID." AND MONTH(`tanggal`) = ".$exp[1]." AND YEAR(`tanggal`) = ".$exp[0];
// 		$data = $wpdb->get_results($select);
// 		$showtxt .= '
// 		<h4>Laporan '.$tgl.'</h4>
// 		<table>
// 		<tr>
// 			<td>Tanggal</td>
// 			<td>Transaksi</td>
// 			<td style="text-align:right">Omset</td>
// 			<td style="text-align:right">Komisi</td>
// 		</tr>';
// 		foreach ($data as $data) {
// 			$showtxt .= '
// 			<tr>
// 				<td>'.date('d-m H:i', strtotime($data->tanggal)).'</td>
// 				<td>'.$data->transaksi.'</td>';
// 			if ($data->komisi > 0) {
// 				$showtxt .= '
// 				<td style="text-align:right">'.number_format($data->kredit).'</td>
// 				<td style="text-align:right">'.number_format($data->komisi).'</td>
// 			</tr>';
// 			} elseif ($data->keterangan == 'wd') {
// 				$showtxt .= '
// 				<td style="text-align:right">0</td>
// 				<td style="text-align:right">-'.number_format($data->debet).'</td>
// 			</tr>';
// 			} else {				
// 				$showtxt .= '
// 				<td style="text-align:right">0</td>
// 				<td style="text-align:right">'.number_format($data->komisi).'</td></tr>';
// 			}
// 		}
// 		$showtxt .= '</table>';
// 	}
// 	//
// } 
if (isset($_GET['detil'])) {
    $exp = explode('-', $_GET['detil']);
    if (is_numeric($exp[0]) && is_numeric($exp[1])) {
        $tgl = $exp[0] . '-' . $exp[1];
        $select = "SELECT * FROM `cb_laporan` WHERE `id_sponsor`=" . $user_ID . " AND MONTH(`tanggal`) = " . $exp[1] . " AND YEAR(`tanggal`) = " . $exp[0];
        $data = $wpdb->get_results($select);
 

        $showtxt .= '
        <h4>Laporan ' . $tgl . '</h4>
        <div class="table-container">
            <table class="scrollable-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Omset</th>
                        <th>Komisi</th>
                    </tr>
                </thead>
                <tbody>';
                
        foreach ($data as $row) {
            $showtxt .= '
                    <tr>
                        <td>' . date('d-m H:i', strtotime($row->tanggal)) . '</td>
                        <td>' . $row->transaksi . '</td>';
            if ($row->komisi > 0) {
                $showtxt .= '
                        <td>' . number_format($row->kredit) . '</td>
                        <td>' . number_format($row->komisi) . '</td>
                    </tr>';
            } elseif ($row->keterangan == 'wd') {
                $showtxt .= '
                        <td>0</td>
                        <td>-' . number_format($row->debet) . '</td>
                    </tr>';
            } else {
                $showtxt .= '
                        <td>0</td>
                        <td>' . number_format($row->komisi) . '</td>
                    </tr>';
            }
        }

        $showtxt .= '
                </tbody>
            </table>
        </div>';
    }
}
else {
	$data = $wpdb->get_results("SELECT SUM(`kredit`) AS `omset`, SUM(`komisi`) AS `totkomisi`, DATE_FORMAT( `tanggal`,  '%Y-%m' ) AS `bulan` FROM  `cb_laporan` WHERE `id_sponsor`=".$user_ID." GROUP BY `bulan` ORDER BY `tanggal` DESC");

	foreach ($data as $data) {
		$duit[$data->bulan]['omset'] = $data->omset;
		$duit[$data->bulan]['komisi'] = $data->totkomisi;
	}

	$now = date("Y-m-d");
	$showtxt .='
	<table style="width:100%">
		<thead>
			<tr>
				<th>Bulan</th>
				<th style="text-align:right">Omset</th>
				<th style="text-align:right">Komisi</th>
			</tr>
		</thead>
		<tbody>';
			if (isset($duit) && is_array($duit)) {
				foreach ($duit as $key => $value) {
					if (isset($_GET['hal']) && $_GET['hal'] == 'laporan') {
						$addpage = '&hal=laporan';
					} else {
						$addpage = '';
					}
					$showtxt .= '<tr><td><a href="'.site_url().'/?page_id='.get_the_ID().$addpage.'&detil='.$key.'">'.date('F Y',strtotime($key)).'</a></td>
					<td style="text-align:right">'.number_format($value['omset']).'</td></td>
					<td style="text-align:right">'.number_format($value['komisi']).'</td></td></tr>';
				}
			}
	$showtxt .='
		</tbody>
	</table>';
}
?>
