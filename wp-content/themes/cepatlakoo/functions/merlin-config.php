<?php
/**
 * Merlin WP configuration file.
 *
 * @package   Merlin WP
 * @version   1.0.0
 * @link      https://merlinwp.com/
 * @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
 * @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
 * @license   Licensed GPLv3 for Open Source Use
 * *
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and settings.
 */
$wizard = new Merlin(

	$config = array(
		'directory'            => 'includes/merlinwp', // Location / directory where Merlin WP is placed in your theme.
		'merlin_url'           => 'cepatlakoo-wizard', // The wp-admin page slug where Merlin WP loads.
		'parent_slug'          => 'themes.php', // The wp-admin parent page slug for the admin menu item.
		'capability'           => 'manage_options', // The capability required for this menu to be displayed to the user.
		'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
		'dev_mode'             => false, // Enable development mode for testing.
		'license_step'         => true, // EDD license activation step.
		'license_required'     => true, // Require the license activation step.
		'license_help_url'     => 'https://cepatlakoo.com', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => 'https://cepatlakoo.com', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => 'Cepatlakoo', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => 'cepatlakoo', // EDD_Theme_Updater_Admin item_slug.
		// 'ready_big_button_url' => '', // Link for the big button on the ready step.
	),
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup', 'cepatlakoo' ),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'cepatlakoo' ),
		'return-to-dashboard'      => esc_html__( 'Kembali ke Dashboard', 'cepatlakoo' ),
		'ignore'                   => esc_html__( 'Non-aktifkan panduan ini', 'cepatlakoo' ),

		'btn-skip'                 => esc_html__( 'Lewati', 'cepatlakoo' ),
		'btn-next'                 => esc_html__( 'Selanjutnya', 'cepatlakoo' ),
		'btn-start'                => esc_html__( 'Mulai', 'cepatlakoo' ),
		'btn-no'                   => esc_html__( 'Batalkan', 'cepatlakoo' ),
		'btn-plugins-install'      => esc_html__( 'Instal', 'cepatlakoo' ),
		'btn-child-install'        => esc_html__( 'Instal', 'cepatlakoo' ),
		'btn-content-install'      => esc_html__( 'Instal', 'cepatlakoo' ),
		'btn-import'               => esc_html__( 'Import', 'cepatlakoo' ),
		'btn-license-activate'     => esc_html__( 'Aktifkan', 'cepatlakoo' ),
		'btn-license-skip'         => esc_html__( 'Nanti Saja', 'cepatlakoo' ),

		/* translators: Theme Name */
		'license-header%s'         => esc_html__( 'Aktifkan %s', 'cepatlakoo' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s sudah aktif', 'cepatlakoo' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Masukkan kode lisensi Anda agar bisa mendapatkan update versi-versi terbaru theme %s.', 'cepatlakoo' ),
		'license-label'            => esc_html__( 'Kode list_terms_exclusions', 'cepatlakoo' ),
		'license-success%s'        => esc_html__( 'Kode lisensi sudah diaktifkan untuk website ini, silahkan lanjut ke langkah berikutnya.', 'cepatlakoo' ),
		'license-json-success%s'   => esc_html__( 'Kode lisensi berhasil diaktifkan.', 'cepatlakoo' ),
		'license-tooltip'          => esc_html__( 'Butuh bantuan?', 'cepatlakoo' ),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__( 'Selamat datang di %s', 'cepatlakoo' ),
		'welcome-header-success%s' => esc_html__( 'Halo. Selamat datang kembali', 'cepatlakoo' ),
		'welcome%s'                => esc_html__( 'Panduan instalasi ini akan membantu setup theme Cepatlakoo, instalasi plugin serta mengimport konten dummy. Sangat disarankan untuk menjalankan panduan instalasi ini hingga selesai.', 'cepatlakoo' ),
		'welcome-success%s'        => esc_html__( 'Anda sepertinya sudah pernah menjalankan panduan instalasi theme Cepatlakoo. Jika Anda ingin tetap melanjutkan, klik tombol "Mulai" di bawah.', 'cepatlakoo' ),

		'child-header'             => esc_html__( 'Install Child Theme', 'cepatlakoo' ),
		'child-header-success'     => esc_html__( 'Child theme berhasil diinstal!', 'cepatlakoo' ),
		'child'                    => esc_html__( 'Child theme hanya perlu digunakan jika Anda ingin membuat perubahan atau modifikasi pada kode-kode yang ada pada theme Cepatlakoo. Child theme sifatnya opsional.', 'cepatlakoo' ),
		'child-success%s'          => esc_html__( 'Child theme Anda sudah diinstal dan kini sudah diaktifkan.', 'cepatlakoo' ),
		'child-action-link'        => esc_html__( 'Apa itu Child Theme?', 'cepatlakoo' ),
		'child-json-success%s'     => esc_html__( 'Keren! Child theme Anda sudah berhasil diinstal dan kini sudah diaktifkan.', 'cepatlakoo' ),
		'child-json-already%s'     => esc_html__( 'Keren! Child theme Anda sudah berhasil diinstal dan kini sudah diaktifkan.', 'cepatlakoo' ),

		'plugins-header'           => esc_html__( 'Instalasi Plugin', 'cepatlakoo' ),
		'plugins-header-success'   => esc_html__( 'Instalasi plugin berhasil!', 'cepatlakoo' ),
		'plugins'                  => esc_html__( 'Langkah ini akan meng-instal plugin-plugin wajib dan opsional yang dibutuhkan oleh theme Cepatlakoo.', 'cepatlakoo' ),
		'plugins-success%s'        => esc_html__( 'Plugin telah berhasil diinstal dan diupdate. Klik tombol "Selanjutnya" untuk melanjutkan ke langkah berikutnya.', 'cepatlakoo' ),
		'plugins-action-link'      => esc_html__( 'Lihat Daftar Plugin', 'cepatlakoo' ),

		'import-header'            => esc_html__( 'Import Konten Dummy', 'cepatlakoo' ),
		'import'                   => esc_html__( 'Langkah ini akan mengimport konten dummy ke website Anda. Sifatnya opsional tapi sangat kami sarankan mengimport konten dummy agar Anda menjadi lebih familiar dengan theme Cepatlakoo.', 'cepatlakoo' ),
		'import-action-link'       => esc_html__( 'Lihat Daftar Yang Diimport', 'cepatlakoo' ),

		'ready-header'             => esc_html__( 'Konten berhasil diimport!', 'cepatlakoo' ),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__( 'Theme Cepatlakoo telah berhasil disetup. Selamat menggunakan theme %s.', 'cepatlakoo' ),
		'ready-action-link'        => esc_html__( 'Informasi Tambahan', 'cepatlakoo' ),
		'ready-big-button'         => esc_html__( 'Lihat website Anda', 'cepatlakoo' ),
		'ready-link-1'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__( 'Kenali WordPress', 'cepatlakoo' ) ),
		'ready-link-2'             => sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://cepatlakoo.com/bantuan/', esc_html__( 'Butuh Bantuan?', 'cepatlakoo' ) ),
		'ready-link-3'             => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=cepatlakoo&tab=1' ), esc_html__( 'Halaman Seting Cepatlakoo', 'cepatlakoo' ) ),
	)
);