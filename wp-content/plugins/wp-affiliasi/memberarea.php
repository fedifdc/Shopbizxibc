<?php 
if (!defined('IS_IN_SCRIPT')) { die();  exit; } 
if (!isset($user_ID) || $user_ID == 0) { 
	$refer = $_SERVER['REQUEST_URI'];
	header("Location: ".wp_login_url($refer));
}
global $premium, $freemember;
$member = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp` = ".$user_ID);
$sponsor = $member->id_referral;
if ($sponsor != 0) {
	$sponsorsaya = $wpdb->get_row("SELECT `nama`,`email`,`telp` FROM `wp_member` WHERE `idwp` = ".$sponsor, ARRAY_A);
} else {
	$sponsorsaya = '';
}

$datakomisi = get_option('komisi');

$menuoption = get_option('menuoption');
$options = get_option('cb_pengaturan');

$urlaff = urlaff($member->subdomain);

$showtxt .= '
<div style="text-align:center">
<h2>Hallo, '.$member->nama.'</h2>	
<p>Link Affiliasi Anda:<br/>
<b><a href="'.$urlaff.'">'.$urlaff.'</a></b>';

if (substr(get_urlpendek($urlaff), 0, 4) == 'http') {
	$showtxt .= '<br/>(<a href="'.get_urlpendek($urlaff).'">'.get_urlpendek($urlaff).'</a>)';
} 
if (!isset($options['matauang'])) {
	$options['matauang'] = 'Rp.';
}
$jmldownline = $wpdb->get_var("SELECT COUNT(*) FROM `wp_member` WHERE `id_referral`=".$user_ID);
$showtxt .= '</p>
</div>
<table style="text-align:left">
<tr><td>No. ID</td><td>: <b>'.$member->idwp.'</b></td></tr>
<tr><td>Jumlah Pengunjung</td><td>: <b>'.number_format($member->read).'</b></td></tr>
<tr><td>Jumlah Rekrut Langsung</td><td>: <b>';			
$showtxt .= number_format($jmldownline).'</b></td></tr>	
<tr><td>Total Pendapatan</td><td>: <b>'.$options['matauang'].' '.number_format($member->jml_voucher).',-</b></td></tr>
<tr><td>Belum Dibayar</td><td>: <b>'.$options['matauang'].' '.number_format($member->sisa_voucher).'-</b></td></tr>
<tr><td>Status Keanggotaan</td><td>: <b>';

if ($member->membership == 2) { 
	$showtxt .= 'Affiliator Member'; 
} else { 
    $showtxt .= 'Free Member <a href="' . site_url() . '/dasbor">[UPGRADE SEKARANG]</a>';
//     $showtxt .= 'Free Member <a href="'.site_url().'/?page_id='.$options['order'].'">[UPGRADE SEKARANG]</a>'; 
}

$showtxt .= '</b></td></tr></table>';

if (isset($sponsorsaya['nama'])) {
	$showtxt .= '
	<h3>Data Sponsor</h3>
	<p>
	Nama Sponsor : <b>'.$sponsorsaya['nama'].'</b><br/>
	Email Sponsor : '.$sponsorsaya['email'].'<br/>
	Telp. Sponsor : '.$sponsorsaya['telp'].'</p>';
}

if (isset($menuoption['infobaru']) && is_array($menuoption['infobaru']) && count($menuoption['infobaru']) > 0) {
	if (in_array(0, $menuoption['infobaru'])) {
		// Kosong
	} else {
		if (in_array(-1, $menuoption['infobaru'])) {
			query_posts( 'posts_per_page=5' );
		} else {
			$listcat = array();
			foreach ($menuoption['infobaru'] as $infobaru) {
				if ($infobaru > 0) {
					array_push($listcat, $infobaru);
				}
			}

			//print_r($listcat);
			query_posts( array( 'category__in' => $listcat, 'posts_per_page' => 5) );
		}
		$showtxt .= '<h3>Informasi Terbaru</h3>
		<ol>';
		while (have_posts()) : the_post();
	      $showtxt .=  '<li><b><a href="'.get_the_permalink().'">'.get_the_title().'</a></b><br/>
		  '.get_the_excerpt().'</li>';
		endwhile; 
		wp_reset_query();
		$showtxt .= '</ol>';
	}
}

$custom = unserialize($member->homepage);
if (!isset($custom['uplines'])) {
	$custom['uplines'] = cbaff_uplines($sponsor);
	$customdb = serialize($custom);
	$wpdb->query("UPDATE `wp_member` SET `homepage`='$customdb' WHERE `idwp`=$user_ID");
}
?>
