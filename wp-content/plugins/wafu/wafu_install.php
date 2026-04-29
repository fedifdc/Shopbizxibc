<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; } 
if (!$wpdb->get_var("show tables like 'wafu_campaign'")) {
	$sql = "
	CREATE TABLE `wafu_campaign` (
		`camp_id` bigint(20) NOT NULL auto_increment,
		`camp_grup` bigint(20) NOT NULL,
		`camp_title` varchar(200) NOT NULL,
		`camp_content` longtext NOT NULL,
		`camp_image` varchar(200) NULL,
		`camp_delay` DECIMAL(4,1) NOT NULL default '1',
		`camp_periode` CHAR(1) NOT NULL DEFAULT '1',
		`camp_sort` tinyint(4) NOT NULL,
		PRIMARY KEY  (`camp_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);

	$sql = "
	CREATE TABLE `wafu_grup` (
		`grup_id` int(11) NOT NULL auto_increment,
		`grup_nama` varchar(100) NOT NULL,
		`grup_diskripsi` varchar(250) NULL,
		`grup_tgl_bikin` datetime NOT NULL,
		`grup_validate` varchar(200) NULL,
		`grup_success` varchar(200) NULL,
		`grup_code` varchar(50) NULL,
		`grup_optin` tinyint(4) NOT NULL,
		`grup_custom` text NULL,
		`grup_unsubscribe` varchar(100) NOT NULL,
		`grup_isival` longtext NULL,
		`grup_footer` TEXT NOT NULL,
		PRIMARY KEY  (`grup_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);

	$sql = "
	CREATE TABLE `wafu_gruptomember` (
		`gtm_id` bigint(20) NOT NULL auto_increment,
		`member_id` bigint(20) NOT NULL,
		`grup_id` int(11) NOT NULL,
		`gtm_nextid` bigint(20) NOT NULL,
		`gtm_schedule` DATETIME NOT NULL,
		`gtm_bc` char(1) NOT NULL,
		`gtm_status` char(1) NOT NULL,
		`gtm_customdata` TEXT NULL,
		`gtm_tglgabung` DATETIME NOT NULL,
		PRIMARY KEY  (`gtm_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);
	
	$sql = "
	CREATE TABLE `wafu_member` (
		`member_id` bigint(20) NOT NULL auto_increment,
		`member_idwp` bigint(20) NOT NULL,
		`member_nama` varchar(200) NOT NULL,
		`member_wa` varchar(200) NOT NULL,
		`member_tglgabung` datetime NOT NULL,
		`member_ip` varchar(15) NOT NULL,
		`member_val` VARCHAR(5) NULL,
		`member_count` bigint(20) NOT NULL DEFAULT 0,
		PRIMARY KEY  (`member_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);

	$sql = "
	CREATE TABLE `wafu_import` (
		`import_id` bigint(20) NOT NULL auto_increment,
		`import_grup` bigint(20) NOT NULL,
		`import_nama` varchar(200) NOT NULL,
		`import_wa` varchar(200) NOT NULL,
		`import_custom` TEXT NULL,
		PRIMARY KEY  (`import_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);

	$sql = "
	CREATE TABLE `wafu_bc` (
		`bc_id` bigint(20) NOT NULL auto_increment,
		`bc_judul` varchar(200) NOT NULL,
		`bc_isi` longtext NOT NULL,
		`bc_image` varchar(200) NULL,
		`bc_tglkirim` datetime NOT NULL,
		`bc_status` char(1) NOT NULL, # 0 = antri, 1 = proses, 2 = selesai
		`bc_target` varchar(50) NOT NULL,
		`bc_tglselesai` DATETIME NULL, 
		`khususdone` CHAR(1) NULL,
		`khususchat` CHAR(1) NULL,
		PRIMARY KEY  (`bc_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);

	$sql = "
	CREATE TABLE `wafu_chat` (
		`chat_id` bigint(20) NOT NULL auto_increment,
		`chat_key` varchar(200) NOT NULL,
		`chat_jawab` longtext NOT NULL,
		PRIMARY KEY  (`chat_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;";
	$wpdb->query($sql);
	
	# Install Data Default
	$wasetting = array(
		'sender' => '8888888',
		'activestart' => '00:00',
		'activeend' => '24:00',
		'kode_unsubscribe' => 'stop',
		'nama_kosong' => 'Sobat',
		'pesan_unsubscribe' => 'Anda telah berhenti berlangganan. Mohon maaf jika ada salah kata. Terima kasih',
		'salah_kode' => 'Maaf, kode yang anda masukkan salah',
		'pesan_terdaftar' => 'Nomor anda telah terdaftar di grup ini',
		'pesan_update' => 'Nomor anda telah terdaftar, kami akan mengupdate data nama anda menjadi *[nama]*'
	);
	update_option('wasetting',$wasetting);
	update_option('wafu_ver',114);	
}