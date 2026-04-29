<?php
/*
Bismillahirrahmaanirrahiim
Alhamdulillahirobbil 'alamiin

Plugin Name: Affiliasi WAFU - WhatsApp Auto Follow Up
Plugin URI: https://cafebisnis.com/produk/wafu
Description: Mengirimkan pesan whatsapp secara berantai dan otomatis. Auto reply chat dan broadcast ke semua kontak. Bisa digunakan bersama wp-affiliasi dan bisa juga tanpa wp-affiliasi
Version: 1.1.6
Author: Lutvi Avandi
Author URI: https://LutviAvandi.com/
*/
define('WAFU_SCRIPT',1);
// require 'update/plugin-update-checker.php';
// $MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//     'https://cafebisnis.com/updatewafu.php',
//     __FILE__,
//     'wafu'
// );


require_once __DIR__ . '/wafu_upgrade_link.php';
require_once __DIR__ . '/wafu_product.php';

function wafu_install_mlm() {
	global $wpdb;
	include('wafu_install_mlm.php');
}

add_action('activate_wafu_memberarea/wafu_memberarea.php', 'wafu_install_mlm');

# Update Database
function wafu_mlm_load() {
	global $wpdb;
	if ($wpdb->get_var("show tables like 'wafu_campaign_mlm'")) {
		$ver = get_option('wafu_ver');
		if ($ver === FALSE) {
			$wpdb->query("ALTER TABLE `wafu_campaign_mlm` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
			$wpdb->query("ALTER TABLE `wafu_bc_mlm` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
			$wpdb->query("ALTER TABLE `wafu_campaign_mlm` CHANGE `camp_delay` `camp_delay` DECIMAL(4,1) NOT NULL DEFAULT '1'");
		}

		if ($ver == 101) {
			$wpdb->query('ALTER TABLE `wafu_bc_mlm` CHANGE `bc_tglkirim` `bc_tglkirim` DATETIME NOT NULL, CHANGE `bc_status` `bc_status` CHAR(1) NOT NULL');
		}

		# Update WAFU 1.0.5
		if (get_option('wafu_ver',0) < 105) {
			# Update tabel utk support image
			$wpdb->query("ALTER TABLE `wafu_bc_mlm` ADD `bc_image` VARCHAR(200) NULL AFTER `bc_isi`");
			$wpdb->query("ALTER TABLE `wafu_campaign_mlm` ADD `camp_image` VARCHAR(200) NULL AFTER `camp_content`");
			$wpdb->query("ALTER TABLE `wafu_campaign_mlm` ADD `camp_periode` CHAR(1) NOT NULL DEFAULT '1' AFTER `camp_delay`");
			$wpdb->query("ALTER TABLE `wafu_import` ADD `import_custom` TEXT NULL AFTER `import_wa`");
		}

		if (get_option('wafu_ver',0) < 109) {		
			$wpdb->query("ALTER TABLE `wafu_bc_mlm` 
				ADD `bc_tglselesai` DATETIME NULL AFTER `bc_target`, 
				ADD `khususdone` CHAR(1) NULL AFTER `bc_tglselesai`");
		}

		if (get_option('wafu_ver',0) < 111) {	
			$wpdb->query("ALTER TABLE `wafu_member_mlm` ADD `member_count` bigint(20) NOT NULL DEFAULT 0 AFTER `member_val`");
			$sql = "
			CREATE TABLE `wafu_chat_mlm` (
				`chat_id` bigint(20) NOT NULL auto_increment,
				`chat_key` varchar(200) NOT NULL,
				`chat_jawab` longtext NOT NULL,
				PRIMARY KEY  (`chat_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
			$wpdb->query($sql);
		}

		if (get_option('wafu_ver',0) < 114) {	
			$wpdb->query("ALTER TABLE `wafu_bc_mlm` ADD `khususchat` CHAR(1) NULL AFTER `khususdone`");
			update_option('wafu_ver',114);
		}		
	}
}

add_action( 'plugins_loaded', 'wafu_mlm_load' );

add_action('wp_enqueue_scripts', 'wafu_enqueue_frontend_assets');
function wafu_enqueue_frontend_assets() {
	// Hanya muat jika shortcode digunakan di halaman
	//if (!is_singular()) return;

    if (strpos($_SERVER['REQUEST_URI'], 'wafu-') !== false) {
    	global $post;
    	$shortcodes = ['wafu_grup', 'wafu_member', 'wafu_chat', 'wafu_settings', 'wafu_broadcast', 'wafu_reg_wa_mlm', 'wafu_reg_mlm','wafu_bc_front'];
    	foreach ($shortcodes as $shortcode) {
    		if (has_shortcode($post->post_content, $shortcode) ) {
    			// CSS
    			wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', [], '4.5.2');
    			wp_enqueue_style('datetimepicker-css', plugins_url('wafu_memberarea/bootstrap-datetimepicker.min.css'));
    
    			// JS
    			wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js', ['jquery'], null, true);
    			wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);
    			wp_enqueue_script('datetimepicker-js', plugins_url('wafu_memberarea/bootstrap-datetimepicker.min.js'), ['jquery'], null, true);
    
    			// Inline script in footer
    			//add_action('wp_footer', 'wafu_inline_script_frontend');
    			break;
    		}
    	}
    }
}

add_action('wp_head', 'wafu_head_frontend');
function wafu_head_frontend() {
    if (strpos($_SERVER['REQUEST_URI'], 'wafu-') !== false) {
        global $post;
        $shortcodes = ['wafu_grup', 'wafu_member', 'wafu_chat', 'wafu_settings', 'wafu_broadcast', 'wafu_reg_wa_mlm', 'wafu_reg_mlm', 'wafu_bc_front'];
        foreach ($shortcodes as $shortcode) {
            if (has_shortcode($post->post_content, $shortcode)) {
                echo '
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
                <link rel="stylesheet" href="' . plugins_url('wafu/bootstrap-datetimepicker.min.css') . '" />
                ';
                break;
            }
        }
    }
}

add_action('wp_footer', 'wafu_footer_frontend');
function wafu_footer_frontend() {
    if (strpos($_SERVER['REQUEST_URI'], 'wafu-') !== false) {
        global $post;
        $shortcodes = ['wafu_grup', 'wafu_member', 'wafu_chat', 'wafu_settings', 'wafu_broadcast', 'wafu_reg_wa_mlm', 'wafu_reg_mlm', 'wafu_bc_front'];
        foreach ($shortcodes as $shortcode) {
            if (has_shortcode($post->post_content, $shortcode)) {
                echo '
                <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
                <script src="' . plugins_url('wafu/bootstrap-datetimepicker.min.js') . '"></script>
                <style>
              
               
                
                </style>
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
                        forceParse: 0,
                        container: "body"
                    });

                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $j("#blah").attr("src", e.target.result);
                            };
                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    $j("#imgInp").change(function() {
                        readURL(this);
                    });
                });
                </script>';
                break;
            }
        }
    }
}


function wafu_grup_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'wafu_grup.php');
    return ob_get_clean();
}
add_shortcode('wafu_grup', 'wafu_grup_shortcode');

function wafu_member_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'wafu_member.php');
    return ob_get_clean();
}
add_shortcode('wafu_member', 'wafu_member_shortcode');

function wafu_chat_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'wafu_chat.php');
    return ob_get_clean();
}
add_shortcode('wafu_chat', 'wafu_chat_shortcode');

function wafu_settings_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'wafu_settings.php');
    return ob_get_clean();
}
add_shortcode('wafu_settings', 'wafu_settings_shortcode');


function wafu_kirim_mlm($nohp,$pesan,$gambar='', $user_id=null) {
	
	global $wpdb;
	$pesan = stripslashes($pesan);
	$nohp = wafu_format_mlm($nohp);	
	if($user_id === null) {
		$user_id = get_current_user_id();
	}
	$wafusetting = get_user_meta($user_id, 'wafusetting', true);
	include('service/'.$wafusetting['service'].'.php');
	return $return;
}

function dd(...$args) {
	echo '<pre>';
	foreach ($args as $arg) {
		var_dump($arg);
	}
	echo '</pre>';
	die();
}

function wafu_send_mlm($idgtm,$pesan,$gambar='') {
	
	# Kirim pesan dg fitur komplit berdasar data gtm
	global $wpdb;
	$data = db_row("SELECT * FROM `wafu_gruptomember_mlm` 
		LEFT JOIN `wafu_member_mlm` ON `wafu_member_mlm`.`member_id`=`wafu_gruptomember_mlm`.`member_id`
		WHERE `wafu_gruptomember_mlm`.`gtm_id`=".$idgtm);
	$pesan = stripslashes($pesan);
	$nohp = wafu_format_mlm($nohp);

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

	$spintax = new wafu_spin_mlm();
	$pesan = $spintax->process($pesan);

	$wafusetting = get_user_meta(get_current_user_id(), 'wafusetting', true);
	include('service/'.$wafusetting['service'].'.php');
	return $return;
}

function wafu_format_mlm($nomor) {	
	$nomor = preg_replace('/[^0-9]/', '', $nomor);
	$nomor = preg_replace('/^620/','62', $nomor);
	$nomor = preg_replace('/^0/','62', $nomor);
	return $nomor;
}

function wafu_ip_mlm() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
    	$ip = $_SERVER['HTTP_CLIENT_IP']; 
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else { 
    	$ip = $_SERVER['REMOTE_ADDR']; 
    }
    return $ip;
}

add_shortcode('wafu_reg_wa_mlm', 'wafu_reg_wa_mlm'); // Form untuk diubah jadi kode wa
add_shortcode('wafu_reg_mlm', 'wafu_reg_mlm'); // Form biasa seperti autoresponder

function wafu_reg_wa_mlm($atts) {
	global $wpdb;
	$showtxt = '';
	$wafusetting = get_user_meta(get_current_user_id(), 'wafusetting', true);
	$a = shortcode_atts( 
		array(
			'grup' => 1
		), $atts);
	$grup = $wpdb->get_row("SELECT `grup_code`,`grup_custom` FROM `wafu_grup_mlm`  WHERE `grup_id`=".$a['grup']);
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

function wafu_reg_mlm($atts) {
	global $wpdb;
	$showtxt = '';
	$wafusetting = get_user_meta(get_current_user_id(), 'wafusetting', true);
	$a = shortcode_atts( 
		array(
			'grup' => 1
		), $atts);
	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm`  WHERE `grup_id`=".$a['grup']);
	if (isset($grup->grup_custom) && $grup->grup_custom != '') {
		$field = explode("\n", $grup->grup_custom);
	}

	if (isset($_POST['nama']) && $_POST['nama'] != '' && isset($_POST['nowa']) && $_POST['nowa'] != '') {
		// Posting data
		$cek = $wpdb->get_var("SELECT `member_id` FROM `wafu_member_mlm` WHERE `member_wa`='".wafu_format_mlm($_POST['nowa'])."'");
		if (isset($cek) && $cek > 0) {
			// Kalau sudah terdaftar, tidak perlu validasi
			$member_id = $cek;
			$status = 1;
		} else {
			$kodeval = mt_rand(10000,99999);
			$wpdb->query("INSERT INTO `wafu_member_mlm` (`member_nama`,`member_wa`,`member_tglgabung`,`member_ip`,`member_val`) VALUES 
				('".$wpdb->_real_escape($_POST['nama'])."','".wafu_format_mlm($_POST['nowa'])."','".current_time('Y-m-d H:i:s')."','".wafu_ip_mlm()."','".$kodeval."')");
			$member_id = $wpdb->insert_id;
			$status = 0;
		}

		// Persiapan Input GTM
		$cek = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." AND `grup_id`=".$a['grup']);

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

			$camp = $wpdb->get_row("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$a['grup']." ORDER BY `camp_sort` LIMIT 0,1");
	    	if (isset($camp->camp_id)) {
	    		$nextid = $camp->camp_id;
	    		$schedule = current_time('Y-m-d H:i:s');
	    	} else {
	    		$nextid = 0;
	    		$schedule = 0;
	    	}

	    	$wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
				(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`) 
				VALUES (".$member_id.",".$a['grup'].",".$nextid.",'".$schedule."',0,".$status.",'".$customdata."','".current_time('Y-m-d H:i:s')."')");

	    	if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
			}

	    	// Kirim link validasi
	    	if (isset($status) && $status == 0) {
	    		$pesan = str_replace('[kodeval]',$kodeval,$grup->grup_isival);
		    	wafu_kirim_mlm(wafu_format_mlm($_POST['nowa']),$pesan);
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
					$wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." AND `grup_id` IN (".$listgrup.")");
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
		$member = $wpdb->get_row("SELECT * FROM `wafu_member_mlm`,`wafu_gruptomember_mlm` 
			WHERE `wafu_member_mlm`.`member_id` = `wafu_gruptomember_mlm`.`member_id`
			AND `wafu_member_mlm`.`member_val`='".$_GET['kodeval']."'");
		if (isset($member->member_id)) {
			$wpdb->query("UPDATE `wafu_member_mlm` SET `member_val`='' WHERE `member_id`=".$member->member_id);
			$wpdb->query("UPDATE `wafu_gruptomember_mlm` SET `gtm_status`=1 WHERE `member_id`=".$member->member_id);
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
class wafu_spin_mlm
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

function wafu_getcontent_mlm($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

function wafu_wpaff_mlm($id_user,$action,$pesan) {
	global $wpdb;
	// Ambil data member
	$member = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `id_user`=".$id_user);
	$camp_user_wp = $member->id_referral;
	$membercustom = unserialize($member->homepage);
	if (isset($membercustom['whatsapp']) && $membercustom['whatsapp'] != '') {
		$nomorwa = wafu_format_mlm($membercustom['whatsapp']);
	} elseif (isset($member->telp) && is_numeric(wafu_format_mlm($member->telp))) {
		$nomorwa = wafu_format_mlm($member->telp);
	}

	if (isset($nomorwa) && is_numeric($nomorwa)) {
		# Masukkan table member wafu
		$cek = $wpdb->get_var("SELECT `member_id` FROM `wafu_member_mlm` WHERE 
			`member_wa`='".$nomorwa."'");
		if (is_numeric($cek) && $cek > 0) {
			$wpdb->query("UPDATE `wafu_member_mlm` SET `member_idwp`=".$id_user." WHERE `member_id`=".$cek);
			$member_id = $cek;
		} else {
			$wpdb->query("INSERT INTO `wafu_member_mlm` 
				(`member_idwp`,`member_nama`,`member_wa`,`member_tglgabung`,`member_ip`,`member_val`,`camp_user_wp`) VALUES 
				(".$id_user.",'".$wpdb->_real_escape($member->nama)."','".$nomorwa."','".current_time('Y-m-d H:i:s')."','".wafu_ip_mlm()."','',".$camp_user_wp.")");
			$member_id = $wpdb->insert_id;
		}

		# Masukkan grup
		$wafusetting = get_user_meta($camp_user_wp, 'wafusetting', true);
		if (isset($wafusetting['grup_'.$action]) && is_numeric($wafusetting['grup_'.$action])) {
			$cek = $wpdb->get_var("SELECT `gtm_id` FROM `wafu_gruptomember_mlm` WHERE 
				`member_id`=".$member_id." AND `grup_id`=".$wafusetting['grup_'.$action]);
			if (is_numeric($cek) && $cek > 0) {
				# diem aja
			} else {
				# Masukkan Grup
				$camp = $wpdb->get_row("SELECT * FROM `wafu_campaign_mlm` WHERE `camp_grup`=".$wafusetting['grup_'.$action]." ORDER BY `camp_sort` LIMIT 0,1");

	    	if (isset($camp->camp_id)) {
	    		$nextid = $camp->camp_id;
	    		$schedule = current_time('Y-m-d H:i:s');
	    	} else {
	    		$nextid = 0;
	    		$schedule = 0;
	    	}

	    	$customdata = serialize($pesan['wafuaff']);

	    	$wpdb->query("INSERT INTO `wafu_gruptomember_mlm` 
				(`member_id`,`grup_id`,`gtm_nextid`,`gtm_schedule`,`gtm_bc`,`gtm_status`,`gtm_customdata`,`gtm_tglgabung`,`camp_user_wp`) 
				VALUES (".$member_id.",".$wafusetting['grup_'.$action].",".$nextid.",'".$schedule."',0,1,'".$customdata."','".current_time('Y-m-d H:i:s')."',".$camp_user_wp.")");

	    	# Auto Unsubscribe dari grup lama
	    	$grup = $wpdb->get_row("SELECT * FROM `wafu_grup_mlm`  WHERE `grup_id`=".$wafusetting['grup_'.$action]);
	    	if (isset($grup->grup_unsubscribe) && $grup->grup_unsubscribe != '') {
	    		$unsub = unserialize($grup->grup_unsubscribe);
	    		$unsuball = implode(',', $unsub);
	    		$wpdb->query("DELETE FROM `wafu_gruptomember_mlm` WHERE `member_id`=".$member_id." 
	    			AND `grup_id` IN (".$unsuball.")");
	    	}

	    	if ($wpdb->last_error !== '') {
			    $wpdb->print_error();
				}
			}
		}
	}
}

add_action('cb_notif','wafu_wpaff_mlm',10,3);

function wafu_filter_mlm($pesan) {
	# Filter data di sini
	$wafusetting = get_user_meta(get_current_user_id(), 'wafusetting', true);
	$pesan['wafuaff']['produk_orderid'] = '[produk_orderid]';
	$pesan['wafuaff']['produk_nama'] = '[produk_nama]';
	$pesan['wafuaff']['produk_diskripsi'] = '[produk_diskripsi]';
	$pesan['wafuaff']['produk_harga'] = '[produk_harga]';
	$pesan['wafuaff']['produk_hargaunik'] = '[produk_hargaunik]';
	return $pesan;
}

add_filter('cb_notif_pesan','wafu_filter_mlm',10,1);

function jamaktif_mlm($input,$user_id=null) {
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

function wafu_pendekin_mlm($text, $length=100) {
   $length = abs((int)$length);
   if(strlen($text) > $length) {
      $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
   }
   return($text);
}

function wafu_bc_mlm() {
	global $wpdb, $user_ID;
	include ("wafu_bc.php");
}

add_shortcode('wafu_broadcast', 'wafu_bc_front');

function wafu_bc_front() {
    //ob_start();
    wafu_bc_mlm(); // ini fungsi yg biasanya dipanggil di wp-admin
    //return ob_get_clean();
}

// Tambahkan interval 5 menit ke WordPress cron schedule
add_filter('cron_schedules', function($schedules) {
	if (!isset($schedules['five_minutes'])) {
		$schedules['five_minutes'] = [
			'interval' => 120, // 5 menit dalam detik
			'display' => __('Every 5 Minutes')
		];
	}
	return $schedules;
});

// Jalankan setiap 5 menit
if (!wp_next_scheduled('wafu_cron_bc')) {
	wp_schedule_event(time(), 'five_minutes', 'wafu_cron_bc');
}

if (!wp_next_scheduled('wafu_cron_campaign')) {
	wp_schedule_event(time(), 'five_minutes', 'wafu_cron_campaign');
}

if (!wp_next_scheduled('wafu_cron_import')) {
	wp_schedule_event(time(), 'five_minutes', 'wafu_cron_import');
}

// Register hook
add_action('wafu_cron_bc', 'wafu_run_cron_bc');
add_action('wafu_cron_campaign', 'wafu_run_cron_campaign');
add_action('wafu_cron_import', 'wafu_run_cron_import');

// Implementasi fungsi
function wafu_run_cron_bc() {
	include plugin_dir_path(__FILE__) . 'cronbc.php';
}

function wafu_run_cron_campaign() {
	include plugin_dir_path(__FILE__) . 'croncampaign.php';
}

function wafu_run_cron_import() {
	include plugin_dir_path(__FILE__) . 'cronimport.php';
}



////===========
add_action('admin_footer', 'wafu_footer_mlm');
function wafu_footer_mlm() {
	if (isset($_GET['page']) && substr($_GET['page'], 0,5) == 'wafu_memberarea_') {
		echo '
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<script src="'.plugins_url('wafu_memberarea/bootstrap-datetimepicker.min.js').'"></script>
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

function wafuadminmemberarea() {
	add_menu_page('WAFU Affiliator', 'WAFU Affiliator', '', 'wafu_admin_memberarea', 'wafu_settings_aff', 'dashicons-whatsapp');		
	add_submenu_page('wafu_admin_memberarea', 'WAFU Broadcast', 'Broadcast', 'manage_options', 'wafu_bc_aff', 'wafu_bc_aff');
	add_submenu_page('wafu_admin_memberarea', 'WAFU Grup', 'Grup', 'manage_options', 'wafu_grup_aff', 'wafu_grup_aff');
	add_submenu_page('wafu_admin_memberarea', 'WAFU Member List', 'Member List', 'manage_options', 'wafu_member_aff', 'wafu_member_aff');
	add_submenu_page('wafu_admin_memberarea', 'WAFU Auto Chat', 'Auto Chat', 'manage_options', 'wafu_chat_aff', 'wafu_chat_aff');
	add_submenu_page('wafu_admin_memberarea', 'WAFU User Credential', 'Credential Settings', 'manage_options', 'wafu_settings_aff', 'wafu_settings_aff');
	add_submenu_page('wafu_admin_memberarea', 'WAFU Settings', 'Settings', 'manage_options', 'wafu_memberarea_settings', 'wafu_memberarea_settings_page');
}

function wafu_list_users_with_settings() {
	global $wpdb;

	// Handle search
	$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

	// Handle pagination
	$items_per_page = 10;
	$current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
	$offset = ($current_page - 1) * $items_per_page;

	// Query to get total users count
	$total_users_query = "
		SELECT COUNT(*) 
		FROM {$wpdb->users} 
		WHERE 1=1
	";

	if (!empty($search_query)) {
		$total_users_query .= " AND (user_login LIKE '%{$search_query}%' OR user_email LIKE '%{$search_query}%')";
	}

	$total_users = $wpdb->get_var($total_users_query);

	// Query to get users
	$query = "
		SELECT ID, user_login, user_email 
		FROM {$wpdb->users} 
		WHERE 1=1
	";

	if (!empty($search_query)) {
		$query .= " AND (user_login LIKE '%{$search_query}%' OR user_email LIKE '%{$search_query}%')";
	}

	$query .= " LIMIT $offset, $items_per_page";

	$users = $wpdb->get_results($query);

	// Display the search form
	echo '<form method="get" action="">';
	echo '<input type="hidden" name="page" value="wafu_list_users_with_settings">';
	echo '<input type="text" name="search" value="' . esc_attr($search_query) . '" placeholder="Search by username or email">';
	echo '<button type="submit">Search</button>';
	echo '</form>';

	// Display the table
	echo '<style>
		.wp-list-table {
			width: 100%;
			border-collapse: collapse;
		}
		.wp-list-table th, .wp-list-table td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}
		.wp-list-table th {
			background-color: #f4f4f4;
			font-weight: bold;
		}
		.wp-list-table tr:nth-child(even) {
			background-color: #f9f9f9;
		}
		.wp-list-table tr:hover {
			background-color: #f1f1f1;
		}
	</style>';

	echo '<table class="wp-list-table widefat fixed striped">';
	echo '<thead><tr><th>ID</th><th>Username</th><th>Email</th><th colspan=5>WAFU Settings</th></tr></thead>';
	echo '<tbody>';

	if ($users) {
		foreach ($users as $user) {
			echo '<tr>';
			echo '<td>' . esc_html($user->ID) . '</td>';
			echo '<td>' . esc_html($user->user_login) . '</td>';
			echo '<td>' . esc_html($user->user_email) . '</td>';
			echo '<td><a href="' . admin_url('admin.php?page=wafu_settings_aff&user_id=' . esc_attr($user->ID)) . '">Edit Settings</a></td>';
			echo '<td><a href="' . admin_url('admin.php?page=wafu_grup_aff&user_id=' . esc_attr($user->ID)) . '">Edit Groups</a></td>';
			echo '<td><a href="' . admin_url('admin.php?page=wafu_member_aff&user_id=' . esc_attr($user->ID)) . '">Edit Members</a></td>';
			echo '<td><a href="' . admin_url('admin.php?page=wafu_chat_aff&user_id=' . esc_attr($user->ID)) . '">Edit Auto Chat</a></td>';
			echo '<td><a href="' . admin_url('admin.php?page=wafu_bc_aff&user_id=' . esc_attr($user->ID)) . '">Edit Broadcast</a></td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="5">No users found with WAFU settings.</td></tr>';
	}

	echo '</tbody>';
	echo '</table>';

	// Display pagination
	$total_pages = ceil($total_users / $items_per_page);
	echo '<div class="tablenav">';
	echo '<div class="tablenav-pages">';
	if ($total_pages > 1) {
		echo '<span class="pagination-links">';
		if ($current_page > 1) {
			echo '<a class="prev-page" href="' . esc_url(add_query_arg(['paged' => max(1, $current_page - 1), 'search' => urlencode($search_query)])) . '">&laquo;</a>';
		} else {
			echo '<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>';
		}

		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i === $current_page) {
				echo '<span class="current-page">' . esc_html($i) . '</span>';
			} else {
				echo '<a class="page-numbers" href="' . esc_url(add_query_arg(['paged' => $i, 'search' => urlencode($search_query)])) . '">' . esc_html($i) . '</a>';
			}
		}

		if ($current_page < $total_pages) {
			echo '<a class="next-page" href="' . esc_url(add_query_arg(['paged' => min($total_pages, $current_page + 1), 'search' => urlencode($search_query)])) . '">&raquo;</a>';
		} else {
			echo '<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>';
		}
		echo '</span>';
	}
	echo '</div>';
	echo '</div>';
}
function wafu_settings_aff() {
	global $wpdb, $user_ID;
	include ("wafu_settings.php");
}

function wafu_campaign_aff() {
	global $wpdb, $user_ID;
	include ("wafu_campaign.php");
}

function wafu_grup_aff() {
	global $wpdb, $user_ID;
	include ("wafu_grup.php");
}

function wafu_member_aff() {
	global $wpdb, $user_ID;
	include ("wafu_member.php");
}

function wafu_chat_aff() {
	global $wpdb, $user_ID;
	include ("wafu_chat.php");
}

function wafu_addmember_aff() {
	global $wpdb, $user_ID;
	include ("wafu_addmember.php");
}

function wafu_import_aff() {
	global $wpdb, $user_ID;
	include ("wafu_import.php");
}

function wafu_bc_aff() {
	global $wpdb, $user_ID;
	include ("wafu_bc.php");
}
add_action('admin_menu', 'wafuadminmemberarea');

// Settings page callback
function wafu_memberarea_settings_page() {
    // Check if the user has submitted the form
    if (isset($_POST['submit'])) {
        // Save settings
        update_option('wafu_max_groups', intval($_POST['max_groups']));
        update_option('wafu_max_campaigns', intval($_POST['max_campaigns']));
        update_option('wafu_max_autochat', intval($_POST['max_autochat']));
        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Get current settings
    $max_groups = get_option('wafu_max_groups', 10); // Default to 10
    $max_campaigns = get_option('wafu_max_campaigns', 5); // Default to 5
	$max_autochat = get_option('wafu_max_autochat', 5);
    ?>
    <div class="wrap">
        <h1>WAFU Member Area Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="max_groups">Max Groups</label></th>
                    <td><input type="number" name="max_groups" id="max_groups" value="<?php echo esc_attr($max_groups); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_campaigns">Max Campaigns</label></th>
                    <td><input type="number" name="max_campaigns" id="max_campaigns" value="<?php echo esc_attr($max_campaigns); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_autochat">Max Autochat</label></th>
                    <td><input type="number" name="max_autochat" id="max_autochat" value="<?php echo esc_attr($max_autochat); ?>" class="regular-text"></td>
                </tr>
                
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
function wafu_generate_link_change_group($camp_user_wp, $group_code, $member_name, $customdata) {
	$base_url = "https://wa.me/";
	$user_setting = get_user_meta($camp_user_wp, 'wafusetting', true);
	$sender_number = wafu_format_mlm($user_setting['sender']);
	$query_string = "?text=" . rawurlencode('join' . '#'.$group_code .'#'. $member_name . $customdata);
	$link = $base_url . $sender_number . '/' . $query_string;
	return $link;
}
?>