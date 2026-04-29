<?php 
# Cron untuk pengecekan koneksi wa gateway dengan HP. 
# Setting cronjob tiap 30 menit sekali atau 1 jam sekali sesuai selera

$waadmin = '00000000'; # Ganti dengan nomor WA Anda sendiri
$isipesan = '✅ ```[tanggal]```: Cek koneksi WAFU';

$wp_load = substr( dirname( __FILE__ ), 0, strpos( dirname( __FILE__ ), 'wp-content' ) ) . 'wp-load.php';
if ( ! empty( $wp_load ) && file_exists( $wp_load ) ) {
	require_once $wp_load;
} else {
	die('Could not load WordPress');
}
$jam = wp_date('H:i');
if (!jamaktif_mlm($jam)) { echo 'Di luar jam aktif ('.$start.'-'.$end.')'; die; }
$isipesan = str_replace('[tanggal]',wp_date('Y-m-d H:i:s'),$isipesan);
wafu_kirim_mlm($waadmin,$isipesan);