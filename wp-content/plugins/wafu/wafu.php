<?php
/*
Bismillahirrahmaanirrahiim
Alhamdulillahirobbil 'alamiin

Plugin Name: WAFU - WhatsApp Auto Follow Up
Plugin URI: https://cafebisnis.com/produk/wafu
Description: Mengirimkan pesan whatsapp secara berantai dan otomatis. Auto reply chat dan broadcast ke semua kontak. Bisa digunakan bersama wp-affiliasi dan bisa juga tanpa wp-affiliasi
Version: 1.1.6
Author: Lutvi Avandi
Author URI: https://LutviAvandi.com/
*/
define('WAFU_SCRIPT',1);
require 'update/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://cafebisnis.com/updatewafu.php',
    __FILE__,
    'wafu'
);

function wafu_install() {
	global $wpdb;
	include('wafu_install.php');
}

add_action('activate_wafu/wafu.php', 'wafu_install');

# Update Database
function wafu_load() {
	global $wpdb;
	if ($wpdb->get_var("show tables like 'wafu_campaign'")) {
		$ver = get_option('wafu_ver');
		if ($ver === FALSE) {
			$wpdb->query("ALTER TABLE `wafu_campaign` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
			$wpdb->query("ALTER TABLE `wafu_bc` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
			$wpdb->query("ALTER TABLE `wafu_campaign` CHANGE `camp_delay` `camp_delay` DECIMAL(4,1) NOT NULL DEFAULT '1'");
		}

		if ($ver == 101) {
			$wpdb->query('ALTER TABLE `wafu_bc` CHANGE `bc_tglkirim` `bc_tglkirim` DATETIME NOT NULL, CHANGE `bc_status` `bc_status` CHAR(1) NOT NULL');
		}

		# Update WAFU 1.0.5
		if (get_option('wafu_ver',0) < 105) {
			# Update tabel utk support image
			$wpdb->query("ALTER TABLE `wafu_bc` ADD `bc_image` VARCHAR(200) NULL AFTER `bc_isi`");
			$wpdb->query("ALTER TABLE `wafu_campaign` ADD `camp_image` VARCHAR(200) NULL AFTER `camp_content`");
			$wpdb->query("ALTER TABLE `wafu_campaign` ADD `camp_periode` CHAR(1) NOT NULL DEFAULT '1' AFTER `camp_delay`");
			$wpdb->query("ALTER TABLE `wafu_import` ADD `import_custom` TEXT NULL AFTER `import_wa`");
		}

		if (get_option('wafu_ver',0) < 109) {		
			$wpdb->query("ALTER TABLE `wafu_bc` 
				ADD `bc_tglselesai` DATETIME NULL AFTER `bc_target`, 
				ADD `khususdone` CHAR(1) NULL AFTER `bc_tglselesai`");
		}

		if (get_option('wafu_ver',0) < 111) {	
			$wpdb->query("ALTER TABLE `wafu_member` ADD `member_count` bigint(20) NOT NULL DEFAULT 0 AFTER `member_val`");
			$sql = "
			CREATE TABLE `wafu_chat` (
				`chat_id` bigint(20) NOT NULL auto_increment,
				`chat_key` varchar(200) NOT NULL,
				`chat_jawab` longtext NOT NULL,
				PRIMARY KEY  (`chat_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
			$wpdb->query($sql);
		}

		if (get_option('wafu_ver',0) < 114) {	
			$wpdb->query("ALTER TABLE `wafu_bc` ADD `khususchat` CHAR(1) NULL AFTER `khususdone`");
			update_option('wafu_ver',114);
		}		
	}
}

add_action( 'plugins_loaded', 'wafu_load' );

function wafu_head() {
    if (isset($_GET['page']) && substr($_GET['page'], 0,5) == 'wafu_') {
    	echo '    	
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    	<link rel="stylesheet" href="'.plugins_url('wafu/bootstrap-datetimepicker.min.css').'" />';
    }
}

add_action('admin_head', 'wafu_head');

add_action('admin_footer', 'wafu_footer');
function wafu_footer() {
	if (isset($_GET['page']) && substr($_GET['page'], 0,5) == 'wafu_') {
		echo '
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script src="'.plugins_url('wafu/bootstrap-datetimepicker.min.js').'"></script>
		<script>
		var $j = jQuery.noConflict();
		$j(function() {
			$j(".form_datetime").datetimepicker({
	          format: "dd-mm-yyyy hh:ii",
	          weekStart: 1,
	          todayBtn:  1,
	          autoclose: 1,
	          todayHighlight: 1,
	          startView: 2,
	          forceParse: 0
	        });
	        function readURL(input) {
	            if (input.files && input.files[0]) {
	                var reader = new FileReader();
	                
	                reader.onload = function (e) {
	                    $j("#blah").attr("src", e.target.result);
	                }
	                
	                reader.readAsDataURL(input.files[0]);
	            }
	        }

			$j("#imgInp").change(function(){
	            readURL(this);
	        });
        });
        </script>';
	}
}

function wafuadmin() {
	add_menu_page('WAFU', 'WAFU', '', 'wafu_admin','wafu_settings','dashicons-whatsapp');		
	add_submenu_page('wafu_admin', 'WAFU Broadcast', 'Broadcast', 'manage_options', 'wafu_bc', 'wafu_bc');
	add_submenu_page('wafu_admin', 'WAFU Grup', 'Grup', 'manage_options', 'wafu_grup', 'wafu_grup');
	add_submenu_page('wafu_admin', 'WAFU Member List', 'Member List', 'manage_options', 'wafu_member', 'wafu_member');
	add_submenu_page('wafu_admin', 'WAFU Auto Chat', 'Auto Chat', 'manage_options', 'wafu_chat', 'wafu_chat');
	add_submenu_page('wafu_admin', 'WAFU Settings', 'Settings', 'manage_options', 'wafu_settings', 'wafu_settings');
}

add_action('admin_menu', 'wafuadmin');

function wafu_settings() {
	global $wpdb, $user_ID;
	include ("wafu_settings.php");
}

function wafu_campaign() {
	global $wpdb, $user_ID;
	include ("wafu_campaign.php");
}

function wafu_grup() {
	global $wpdb, $user_ID;
	include ("wafu_grup.php");
}

function wafu_member() {
	global $wpdb, $user_ID;
	include ("wafu_member.php");
}

function wafu_chat() {
	global $wpdb, $user_ID;
	include ("wafu_chat.php");
}

function wafu_addmember() {
	global $wpdb, $user_ID;
	include ("wafu_addmember.php");
}

function wafu_import() {
	global $wpdb, $user_ID;
	include ("wafu_import.php");
}

function wafu_bc() {
	global $wpdb, $user_ID;
	include ("wafu_bc.php");
}

function wafu_kirim($nohp,$pesan,$gambar='') {
	global $wpdb;
	$pesan = stripslashes($pesan);
	$nohp = wafu_format($nohp);	
	$wafusetting = get_option('wafusetting');
	include('service/'.$wafusetting['service'].'.php');
	return $return;
}

function wafu_send($idgtm,$pesan,$gambar='') {
	# Kirim pesan dg fitur komplit berdasar data gtm
	global $wpdb;
	$data = db_row("SELECT * FROM `wafu_gruptomember` 
		LEFT JOIN `wafu_member` ON `wafu_member`.`member_id`=`wafu_gruptomember`.`member_id`
		WHERE `wafu_gruptomember`.`gtm_id`=".$idgtm);
	$pesan = stripslashes($pesan);
	$nohp = wafu_format($nohp);

	if ($data->member_nama != '') {
		$pesan = str_replace('[nama]',$data->member_nama,$pesan);
	} else {
		$pesan = str_replace('[nama]','Sobat',$pesan);
	}

	if (isset($data->gtm_customdata) && $data->gtm_customdata != '') {
		$customdata = unserialize($data->gtm_customdata);
		if (isset($customdata) && count($customdata) > 0) {
			foreach ($customdata as $key => $value) {									
				$pesan = str_replace('['.$key.']',$value,$pesan);
			}
		}
	}

	$spintax = new wafu_spin();
	$pesan = $spintax->process($pesan);

	$wafusetting = get_option('wafusetting');
	include('service/'.$wafusetting['service'].'.php');
	return $return;
}

function wafu_format($nomor) {	
	$nomor = preg_replace('/[^0-9]/', '', $nomor);
	$nomor = preg_replace('/^620/','62', $nomor);
	$nomor = preg_replace('/^0/','62', $nomor);
	return $nomor;
}

function wafu_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
    	$ip = $_SERVER['HTTP_CLIENT_IP']; 
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else { 
    	$ip = $_SERVER['REMOTE_ADDR']; 
    }
    return $ip;
}

add_shortcode('wafu_regwa', 'wafu_regwa'); // Form untuk diubah jadi kode wa
add_shortcode('wafu_reg', 'wafu_reg'); // Form biasa seperti autoresponder

function wafu_regwa($atts) {
	global $wpdb;
	$showtxt = '';
	$wafusetting = get_option('wafusetting');
	$a = shortcode_atts( 
		array(
			'grup' => 1
		), $atts);
	$grup = $wpdb->get_row("SELECT `grup_code`,`grup_custom` FROM `wafu_grup` WHERE `grup_id`=".$a['grup']);
	if (isset($grup->grup_custom) && $grup->grup_custom != '') {
		$field = explode("\n", $grup->grup_custom);
	}

	if (isset($_POST['nama']) && $_POST['nama'] != '') {
		$pesan = $grup->grup_code.'#'.sanitize_text_field($_POST['nama']);
		if (isset($_POST['custom'])) {
			foreach ($_POST['custom'] as $custom) {
				$pesan .= '#'.sanitize_text_field($custom);
			}
		}

		$showtxt .= '
		<script type="text/javascript">
		<!--
		window.location = "https://wa.me/'.$wafusetting['sender'].'/?text='.rawurlencode($pesan).'"
		//-->
		</script>';	
	} else {

		$showtxt .= '
		<form action="" method="post">
			<table class="wafuform">
			<tr><td class="wafulabel">Nama Lengkap</td>
			<td class="wafuinputcell"><input type="text" name="nama" class="wafuinput"/></td></tr>';
		if (isset($field) && is_array($field)) {
			foreach ($field as $field) {
				$showtxt .= '<tr><td class="wafulabel">'.trim($field).'</td>
				<td><input type="text" name="custom[]" class="wafuinput"/></td></tr>';
			}
		}
		$showtxt .= '
			</table>
			<input type="submit" class="wafusubmit" value="Daftar"/>
		</form>
		';
	}
	return $showtxt;
}

function wafu_reg($atts) {
	global $wpdb;
	$showtxt = '';
	$wafusetting = get_option('wafusetting');
	$a = shortcode_atts( 
		array(
			'grup' => 1
		), $atts);
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_id`=".$a['grup']);
	if (isset($grup->grup_custom) && $grup->grup_custom != '') {
		$field = explode("\n", $grup->grup_custom);
	}

	if (isset($_POST['nama']) && $_POST['nama'] != '' && isset($_POST['nowa']) && $_POST['nowa'] != '') {
		// Posting data
		$cek = $wpdb->get_var("SELECT `member_id` FROM `wafu_member` WHERE `member_wa`='".wafu_format($_POST['nowa'])."'");
		if (isset($cek) && $cek > 0) {
			// Kalau sudah terdaftar, tidak perlu validasi
			$member_id = $cek;
			$status = 1;
		} else {
			$kodeval = mt_rand(10000,99999);
			$wpdb->query("INSERT INTO `wafu_member` (`member_nama`,`member_wa`,`member_tglgabung`,`member_ip`,`member_val`) VALUES 
				('".$wpdb->_real_escape($_POST['nama'])."','".wafu_format($_POST['nowa'])."','".current_time('Y-m-d H:i:s')."','".wafu_ip()."','".$kodeval."')");
			$member_id = $wpdb->insert_id;
			$status = 0;
		}

		// Persiapan Input GTM
		$cek = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." AND `grup_id`=".$a['grup']);

		if (isset($cek) && $cek > 0) {
			$showtxt .= 'Anda sudah terdaftar sebelumnya.';
		} else {
			if (isset($_POST['custom'])) {
				foreach ($_POST['custom'] as $key => $value) {
					$customdata[$key] = $wpdb->_real_escape($value);
				}
				$customdata = serialize($customdata);
			} else {
				$customdata = '';
			}

			$camp = $wpdb->get_row("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$a['grup']." ORDER BY `camp_sort` LIMIT 0,1");
	    	if (isset($camp->camp_id)) {
	    		$nextid = $camp->camp_id;
	    		$schedule = current_time('Y-m-d H:i:s');
	    	} else {
	    		$nextid = 0;
	    		$schedule = 0;
	    	}

	    	$wpdb->query("INSERT INTO `wafu_gruptomember` 
				(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) 
				VALUES (".$member_id.",".$a['grup'].",".$nextid.",'".$schedule."',0,".$status.",'".$customdata."','".current_time('Y-m-d H:i:s')."')");

	    	if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
			}

	    	// Kirim link validasi
	    	if (isset($status) && $status == 0) {
	    		$pesan = str_replace('[kodeval]',$kodeval,$grup->grup_isival);
		    	wafu_kirim(wafu_format($_POST['nowa']),$pesan);
		    	$showtxt .= '
		    	<form action="" method="get">
		    	<p>Silahkan masukkan kode validasi yang telah kami kirimkan ke whatsapp anda</p>
		    	<p><input type="text" name="kodeval" class="wafuinput" /><br/>
		    	<input type="submit" value="Validasi"/></p>
		    	</form>
		    	';   	
	    	} else {
	    		// Langsung auto unsubscribe jika ada
				if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
					$unsub = unserialize($grup->grup_unsubscribe);
					$listgrup = implode(',', $unsub);
					$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
					if ($wpdb->last_error !== '') {
					    $wpdb->print_error();
					}
				}
				$showtxt .= '
		    	Terima kasih telah mendaftar di '.$grup->grup_nama.'
		    	';
	    	}
    	}
	} elseif (isset($_GET['kodeval']) && is_numeric($_GET['kodeval'])) {
		$member = $wpdb->get_row("SELECT * FROM `wafu_member`,`wafu_gruptomember` 
			WHERE `wafu_member`.`member_id` = `wafu_gruptomember`.`member_id`
			AND `wafu_member`.`member_val`='".$_GET['kodeval']."'");
		if (isset($member->member_id)) {
			$wpdb->query("UPDATE `wafu_member` SET `member_val`='' WHERE `member_id`=".$member->member_id);
			$wpdb->query("UPDATE `wafu_gruptomember` SET `gtm_status`=1 WHERE `member_id`=".$member->member_id);
			$showtxt = '<p>Validasi Berhasil. Terima kasih telah mendaftar di '.$grup->grup_nama.'</p>';
		}
	} else {

		$showtxt .= '
		<form action="" method="post">
			<table class="wafuform">
			<tr><td class="wafulabel">Nama Lengkap</td>
			<td class="wafuinputcell"><input type="text" name="nama" class="wafuinput" required/></td></tr>
			<tr><td class="wafulabel">Nomor WhatsApp</td>
			<td class="wafuinputcell"><input type="text" name="nowa" class="wafuinput" required/></td></tr>
			';
		if (isset($field) && is_array($field)) {
			foreach ($field as $field) {
				$showtxt .= '<tr><td class="wafulabel">'.trim($field).'</td>
				<td><input type="text" name="custom['.trim($field).']" class="wafuinput"/></td></tr>';
			}
		}
		$showtxt .= '
			</table>
			<input type="submit" class="wafusubmit" value="Daftar"/>
		</form>
		';
	}
	return $showtxt;
}

/**
 * Spintax - A helper class to process Spintax strings.
 * @name Spintax
 * @author Jason Davis - http://www.codedevelopr.com/
 */
class wafu_spin
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*)\}/x',
            array($this, 'replace'),
            $text
        );
    }

    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        return $parts[array_rand($parts)];
    }
}

function wafu_getcontent($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function wafu_wpaff($id_user,$action,$pesan) {
	global $wpdb;
	// Ambil data member
	$member = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `id_user`=".$id_user);
	$membercustom = unserialize($member->homepage);
	if (isset($membercustom['whatsapp']) && $membercustom['whatsapp'] != '') {
		$nomorwa = wafu_format($membercustom['whatsapp']);
	} elseif (isset($member->telp) && is_numeric(wafu_format($member->telp))) {
		$nomorwa = wafu_format($member->telp);
	}

	if (isset($nomorwa) && is_numeric($nomorwa)) {
		# Masukkan table member wafu
		$cek = $wpdb->get_var("SELECT `member_id` FROM `wafu_member` WHERE 
			`member_wa`='".$nomorwa."'");
		if (is_numeric($cek) && $cek > 0) {
			$wpdb->query("UPDATE `wafu_member` SET `member_idwp`=".$id_user." WHERE `member_id`=".$cek);
			$member_id = $cek;
		} else {
			$wpdb->query("INSERT INTO `wafu_member` 
				(`member_idwp`,`member_nama`,`member_wa`,`member_tglgabung`,`member_ip`,`member_val`) VALUES 
				(".$id_user.",'".$wpdb->_real_escape($member->nama)."','".$nomorwa."','".current_time('Y-m-d H:i:s')."','".wafu_ip()."','')");
			$member_id = $wpdb->insert_id;
		}

		# Masukkan grup
		$wafusetting = get_option('wafusetting');
		if (isset($wafusetting['grup_'.$action]) && is_numeric($wafusetting['grup_'.$action])) {
			$cek = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember` WHERE 
				`member_id`=".$member_id." AND `grup_id`=".$wafusetting['grup_'.$action]);
			if (is_numeric($cek) && $cek > 0) {
				# diem aja
			} else {
				# Masukkan Grup
				$camp = $wpdb->get_row("SELECT * FROM `wafu_campaign` WHERE `camp_grup`=".$wafusetting['grup_'.$action]." ORDER BY `camp_sort` LIMIT 0,1");

	    	if (isset($camp->camp_id)) {
	    		$nextid = $camp->camp_id;
	    		$schedule = current_time('Y-m-d H:i:s');
	    	} else {
	    		$nextid = 0;
	    		$schedule = 0;
	    	}

	    	$customdata = serialize($pesan['wafuaff']);

	    	$wpdb->query("INSERT INTO `wafu_gruptomember` 
				(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) 
				VALUES (".$member_id.",".$wafusetting['grup_'.$action].",".$nextid.",'".$schedule."',0,1,'".$customdata."','".current_time('Y-m-d H:i:s')."')");

	    	# Auto Unsubscribe dari grup lama
	    	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup` WHERE `grup_id`=".$wafusetting['grup_'.$action]);
	    	if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
	    		$unsub = unserialize($grup->grup_unsubscribe);
	    		$unsuball = implode(',', $unsub);
	    		$wpdb->query("DELETE FROM `wafu_gruptomember` WHERE `member_id`=".$member_id." 
	    			AND `grup_id` IN (".$unsuball.")");
	    	}

	    	if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
				}
			}
		}
	}
}

add_action('cb_notif','wafu_wpaff',10,3);

function wafu_filter($pesan) {
	# Filter data di sini
	$wafusetting = get_option('wafusetting');
	$pesan['wafuaff']['produk_orderid'] = '[produk_orderid]';
	$pesan['wafuaff']['produk_nama'] = '[produk_nama]';
	$pesan['wafuaff']['produk_diskripsi'] = '[produk_diskripsi]';
	$pesan['wafuaff']['produk_harga'] = '[produk_harga]';
	$pesan['wafuaff']['produk_hargaunik'] = '[produk_hargaunik]';
	return $pesan;
}

add_filter('cb_notif_pesan','wafu_filter',10,1);

function jamaktif($input) {
    # Cek jam aktif
	$setting = get_option('wafusetting');
	if (isset($setting['activestart']) && $setting['activestart'] != '') {
		$start = $setting['activestart'];
	} else {
		$start = '00:00';
	}

	if (isset($setting['activeend']) && $setting['activeend'] != '') {
		$end = $setting['activeend'];
	} else {
		$end = '24:00';
	}

    $f = DateTime::createFromFormat('!H:i', $start);
    $t = DateTime::createFromFormat('!H:i', $end);
    $i = DateTime::createFromFormat('!H:i', $input);
    if ($f > $t) $t->modify('+1 day');
    return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
}

function wafu_pendekin($text, $length=100) {
   $length = abs((int)$length);
   if(strlen($text) > $length) {
      $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
   }
   return($text);
}