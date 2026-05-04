<?php 
if (!defined('IS_IN_SCRIPT')) { die();  exit; }
$order = wc_get_order($order_id);
$kredit = 0;

foreach ($order->get_items() as $item) {
    $product = $item->get_product();
    
    if (!$product) {
        continue;
    }
    
    // Untuk produk variasi, ambil objek variasi
    if ($product->is_type('variation')) {
        $product_id = $product->get_parent_id();
        $variation_id = $product->get_id();
        $target_id = $variation_id;
    } else {
        $product_id = $product->get_id();
        $variation_id = 0;
        $target_id = $product_id;
    }
    
    // Coba ambil dari variasi dulu
    $BV = get_post_meta($target_id, 'BV', true);
    $BV_type = get_post_meta($target_id, 'BV_type', true);
    
    // Fallback ke produk induk jika tidak ada di variasi atau kosong
    if (($BV === '' || $BV === false || $BV === null) && $variation_id > 0) {
        $BV = get_post_meta($product_id, 'BV', true);
        $BV_type = get_post_meta($product_id, 'BV_type', true);
    }
    
    // Jika masih kosong, set default
    if (empty($BV) || $BV === '') {
        $BV = 0;
    }
    if (empty($BV_type)) {
        $BV_type = 'nominal'; // default
    }
    
    if ($BV_type === 'percent') {
        $kredit += floatval($item->get_total()) * (floatval($BV) / 100);
    } else {
        $kredit += floatval($BV) * $item->get_quantity();
    }
}

$id_user = $order->get_user_id();
$id_sponsor = get_post_meta($order_id,'Sponsor ID',true);
$status = $wpdb->get_row("SELECT * FROM `wp_member` WHERE `idwp`=".$id_user);
# Kirim Notif ke Upline langsung
$datalain = array(
	'produk_orderid' =>	$order_id,
	'produk_harga' => $kredit
);
cb_notif($status->id_user,'woo',$datalain);
# Auto Update Premium
$options = get_option('cb_pengaturan');
if (isset($options['autopremiumwoo']) && $options['autopremiumwoo'] == 1) {
	$wpdb->query("UPDATE `wp_member` SET `membership`=2 WHERE `idwp` = ".$id_user);
}

$custom = unserialize($status->homepage);
if (!isset($custom['uplines'])) {
	$custom['uplines'] = cbaff_uplines($status->id_referral);
	$customdb = serialize($custom);
	$wpdb->query("UPDATE `wp_member` SET `homepage`='$customdb' WHERE `idwp`=$id_sponsor");
}

if ($custom['uplines'] != 0) {
	$iduplines = $id_sponsor.','.$custom['uplines'];
	$uplines = $wpdb->get_results("SELECT * FROM `wp_member` WHERE `idwp` IN ($iduplines) ORDER BY FIELD(`idwp`,$iduplines)");
} else {
	$uplines = $wpdb->get_results("SELECT * FROM `wp_member` WHERE `idwp`=$id_sponsor");
}
$komisi = get_option('komisi');
$i = 0;

foreach ($uplines as $upline) {
	if ($upline->idwp != 0) {
		$jmlkomisi = 0;
	    $id_referral = '';
		if ($komisi['pps'][$i]['woofree']==0 && $upline->membership==1) {
			// Lewati
		} else {
			if ($upline->membership == 2) {
				if (isset($komisi['pps'][$i]['woopremium']) && $komisi['pps'][$i]['woopremium'] > 0) {
					$jmlkomisi = ($komisi['pps'][$i]['woopremium']/100)*$kredit;
				} else {
					$jmlkomisi = ($komisi['pps'][$i]['premium']/100)*$kredit;
				}
			} else {
				if (isset($komisi['pps'][$i]['woofree']) && $komisi['pps'][$i]['woofree'] > 0) {
					$jmlkomisi = ($komisi['pps'][$i]['woofree']/100)*$kredit;
				} else {
					$jmlkomisi = ($komisi['pps'][$i]['free']/100)*$kredit;
				}
			}

			$id_referral = $upline->idwp;

			if ($i==0) {
				$wpdb->query("UPDATE `wp_member` SET `downline_lngsg`=`downline_lngsg`+1,`jml_downline`=`jml_downline`+1,`jml_voucher`=`jml_voucher`+'$jmlkomisi', `sisa_voucher`=`sisa_voucher`+'$jmlkomisi' WHERE `idwp` = '$id_referral'");
				
			} else {
				$wpdb->query("UPDATE `wp_member` SET `jml_downline`=`jml_downline`+1,`jml_voucher`=`jml_voucher`+'$jmlkomisi', `sisa_voucher`=`sisa_voucher`+'$jmlkomisi' WHERE `idwp` = '$id_referral'");
			}

			if ($jmlkomisi > 0) {
				$transaksi = 'Penjualan Produk Order No. '.$order_id.' Level '.($i+1);
				$wpdb->query("INSERT INTO `cb_laporan` (`tanggal`,`transaksi`,`kredit`,`komisi`,`keterangan`,`id_user`,`id_sponsor`,`id_order`) VALUES (NOW(),'$transaksi','$kredit','$jmlkomisi','woo','$id_user','$id_referral','$order_id')");
			
			    insert_commission_log($id_user, 'wp_affiliate', 'Komisi Affiliasi', 'Affiliasi Komisi Order Completed  #'. $order_id , $jmlkomisi, 'uang', 'success');
			}
			$i++;
		}
	}
}
?>