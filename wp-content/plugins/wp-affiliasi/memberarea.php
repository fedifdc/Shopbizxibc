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
<div class="wpaff-hero">
    <h2>Hallo, '.$member->nama.'</h2>	
    <p>Selamat datang di member area. Bagikan link affiliasi Anda untuk mulai menghasilkan komisi.</p>
    <div class="wpaff-aff-link">
        <div>
            <span style="font-size:12px; opacity:0.8; display:block; margin-bottom:5px;">Link Affiliasi Utama:</span>
            <a href="'.$urlaff.'">'.$urlaff.'</a>
        </div>
        <button onclick="navigator.clipboard.writeText(\''.$urlaff.'\'); alert(\'Link copied!\');" style="background:white; color:var(--wpaff-primary); border:none; padding:8px 12px; border-radius:6px; cursor:pointer; font-weight:600; font-size:12px;">Copy Link</button>
    </div>';

if (substr(get_urlpendek($urlaff), 0, 4) == 'http') {
	$showtxt .= '
    <div class="wpaff-aff-link" style="margin-top:10px; background:rgba(0,0,0,0.1);">
        <div>
            <span style="font-size:12px; opacity:0.8; display:block; margin-bottom:5px;">Link Pendek:</span>
            <a href="'.get_urlpendek($urlaff).'">'.get_urlpendek($urlaff).'</a>
        </div>
    </div>';
} 

$showtxt .= '</div>';

if (!isset($options['matauang'])) {
	$options['matauang'] = 'Rp.';
}

$jmldownline = $wpdb->get_var("SELECT COUNT(*) FROM `wp_member` WHERE `id_referral`=".$user_ID);

if (function_exists('crs_render_member_royalty_indicator')) {
    $showtxt .= crs_render_member_royalty_indicator($user_ID);
}

$showtxt .= '
<div class="wpaff-stats-grid">
    <div class="wpaff-stat-card">
        <span class="wpaff-stat-label">ID Member</span>
        <span class="wpaff-stat-value">#'.$member->idwp.'</span>
    </div>
    <div class="wpaff-stat-card">
        <span class="wpaff-stat-label">Pengunjung</span>
        <span class="wpaff-stat-value">'.number_format($member->read).'</span>
    </div>
    <div class="wpaff-stat-card">
        <span class="wpaff-stat-label">Rekrut Langsung</span>
        <span class="wpaff-stat-value">'.number_format($jmldownline).'</span>
    </div>
    <div class="wpaff-stat-card">
        <span class="wpaff-stat-label">Total Pendapatan</span>
        <span class="wpaff-stat-value">'.$options['matauang'].' '.number_format($member->jml_voucher).'</span>
    </div>
</div>

<div class="wpaff-content-grid">
    <div class="wpaff-main-col">';

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
			query_posts( array( 'category__in' => $listcat, 'posts_per_page' => 5) );
		}
		
        $showtxt .= '
        <div class="wpaff-card">
            <h3>Informasi Terbaru</h3>
            <ul class="wpaff-info-list">';
            
		while (have_posts()) : the_post();
	      $showtxt .=  '
                <li class="wpaff-info-item">
                    <b><a href="'.get_the_permalink().'" style="color:var(--wpaff-primary); text-decoration:none;">'.get_the_title().'</a></b>
                    <div style="font-size:14px; color:var(--wpaff-text-muted); margin-top:5px;">'.get_the_excerpt().'</div>
                </li>';
		endwhile; 
		wp_reset_query();
		$showtxt .= '</ul></div>';
	}
}

$showtxt .= '
    </div>
    <div class="wpaff-sidebar-col">
        <div class="wpaff-card">
            <h3>Status Keanggotaan</h3>
            <div style="text-align:center; padding:10px 0;">';
            
if ($member->membership == 2) { 
	$showtxt .= '<span class="wpaff-badge wpaff-badge-premium">Affiliator Member</span>'; 
} else { 
    $showtxt .= '
        <span class="wpaff-badge wpaff-badge-free">Free Member</span>
        <a href="' . site_url() . '/dasbor" class="wpaff-upgrade-btn">UPGRADE SEKARANG</a>';
}

$showtxt .= '
            </div>
            <div style="margin-top:20px; border-top:1px solid var(--wpaff-border); padding-top:15px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span style="color:var(--wpaff-text-muted);">Belum Dibayar:</span>
                    <b style="color:var(--wpaff-secondary);">'.$options['matauang'].' '.number_format($member->sisa_voucher).'</b>
                </div>
            </div>
        </div>';

if (isset($sponsorsaya['nama'])) {
	$showtxt .= '
        <div class="wpaff-card">
            <h3>Data Sponsor</h3>
            <div class="wpaff-sponsor-card">
                <p><b>'.$sponsorsaya['nama'].'</b></p>
                <p style="color:var(--wpaff-text-muted);">'.$sponsorsaya['email'].'</p>
                <p style="color:var(--wpaff-text-muted);">'.$sponsorsaya['telp'].'</p>
                <a href="https://wa.me/'.preg_replace('/[^0-9]/', '', $sponsorsaya['telp']).'" target="_blank" style="display:block; margin-top:10px; text-align:center; background:#25D366; color:white; padding:8px; border-radius:6px; text-decoration:none; font-size:13px; font-weight:600;">Hubungi via WhatsApp</a>
            </div>
        </div>';
}

$showtxt .= '
    </div>
</div>';

$custom = unserialize($member->homepage);
if (!isset($custom['uplines'])) {
	$custom['uplines'] = cbaff_uplines($sponsor);
	$customdb = serialize($custom);
	$wpdb->query("UPDATE `wp_member` SET `homepage`='$customdb' WHERE `idwp`=$user_ID");
}
?>
