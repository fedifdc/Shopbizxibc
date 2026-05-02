<?php

require_once  PLUGIN_RESI_PATH . 'inc/lib.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/plugin-option.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/order-post-meta.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/order-column.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/order-status-and-action.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/send-email.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/ajax.php' ;
require_once  PLUGIN_RESI_PATH . 'inc/teman-plugin.php' ;

function plugin_resi_init() {

	// languages
	load_plugin_textdomain( PLUGIN_RESI, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init','plugin_resi_init' );

function plugin_resi_get_ekspedisis() {
	$ekspedisis = plugin_resi_daftar_ekspedisi_default();
	// Get dari options
	$ekspedisis_extra = get_option( 'plugin_resi_options' );

	for ( $i = 1; $i <= 5 ; $i++ ) {
		$ifarg[0] = isset( $ekspedisis_extra[ 'extra_courier_name_' . $i ] ) && $ekspedisis_extra[ 'extra_courier_name_' . $i ] != '';
		$ifarg[1] = isset( $ekspedisis_extra[ 'extra_courier_website_' . $i ] ) && $ekspedisis_extra[ 'extra_courier_website_' . $i ] != '';

		if ( $ifarg[0] && $ifarg[1] ) {
			$ekspedisis_key = sanitize_title( $ekspedisis_extra[ 'extra_courier_name_' . $i ] );

			$ekspedisis[ $ekspedisis_key ] = array(
				'nama' => $ekspedisis_extra[ 'extra_courier_name_' . $i ],
				'url' => $ekspedisis_extra[ 'extra_courier_website_' . $i ],
			);
		}
	}
	return $ekspedisis;
}

function plugin_resi_daftar_ekspedisi_default() {
	$ekspedisis = array(
		'dhl' => array(
			'nama' => 'DHL',
			'url' => 'http://www.dhl.com',
		),
		'fedex' => array(
			'nama' => 'FEDEX',
			'url' => 'http://www.fedex.com',
		),
		'ups' => array(
			'nama' => 'UPS',
			'url' => 'http://www.ups.com',
		),
		'jne' => array(
			'nama' => 'JNE',
			'url' => 'http://www.jne.co.id',
		),
		'tiki' => array(
			'nama' => 'Tiki',
			'url' => 'http://tiki-online.com/',
		),
		'pos' => array(
			'nama' => 'Pos Indonesia',
			'url' => 'http://www.posindonesia.co.id/',
		),
		'esl' => array(
			'nama' => 'ESL',
			'url' => 'http://www.esl-express.com/',
		),
		'pcp' => array(
			'nama' => 'PCP',
			'url' => 'http://www.pcpexpress.com/',
		),
		'rpx' => array(
			'nama' => 'RPX',
			'url' => 'http://rpx.co.id/',
		),
		'wahana' => array(
			'nama' => 'Wahana',
			'url' => 'http://wahana.com/',
		),
		'jnt' => array(
			'nama' => 'J&T',
			'url' => 'https://jet.co.id/track',
		),
		'pandu' => array(
			'nama' => 'Pandu Logistics',
			'url' => 'http://www.pandulogistics.com/trace',
		),
		'sicepat' => array(
			'nama' => 'Sicepat',
			'url' => 'http://sicepat.com/cek-resi',
		),
		'pahala' => array(
			'nama' => 'Pahala Express',
			'url' => 'http://www.pahalaexpress.co.id/',
		),
		'cahaya' => array(
			'nama' => 'Cahaya Logistik',
			'url' => 'http://www.cahayalogistik.com/',
		),
		'sap' => array(
			'nama' => 'SAP Express',
			'url' => 'http://sap-express.id/',
		),
		'jet' => array(
			'nama' => 'JET Express',
			'url' => 'http://www.jetexpress.co.id/',
		),
		'indah' => array(
			'nama' => 'Indah Cargo',
			'url' => 'https://www.indahonline.com/?op=tracking',
		),
		'dse' => array(
			'nama' => '21 Espress',
			'url' => 'https://21express.co.id/track',
		),
		'slis' => array(
			'nama' => 'Solusi Express',
			'url' => 'http://www.solusi-logistics.com/',
		),
		'expedito' => array(
			'nama' => 'Expedito',
			'url' => 'https://expedito.co.id/',
		),
		'first' => array(
			'nama' => 'First Logistics',
			'url' => 'http://www.firstlogistics.co.id/trace/',
		),
		'star' => array(
			'nama' => 'Star Cargo',
			'url' => 'https://www.starcargo.co.id/',
		),
		'nss' => array(
			'nama' => 'NSS Express',
			'url' => 'http://www.nssxpress.co.id/'
		)
	);
	return apply_filters( 'plugin_resi_daftar_ekspedisis', $ekspedisis );
}

function plugin_resi_get_ekspedisi( $courier = '', $index = '' ) {
	$courier = strtolower( $courier );
	$ekspedisis = plugin_resi_get_ekspedisis();
	if ( isset( $ekspedisis[ $courier ] ) ) {
		if ( '' !== $index ) {
			if ( isset( $ekspedisis[ $courier ][ $index ] ) ) {
				return $ekspedisis[ $courier ][ $index ];
			}
		} else {
			return $ekspedisis[ $courier ];
		}
	}
	return false;
}

function plugin_resi_get_order_data( $order_id ) {
	$order = wc_get_order( $order_id );
	if(!$order){
	    return;
	}
	$data  = $order->get_meta( 'plugin_resi_data', true );
	if ( '' === $data ) {
		$data = array(
			'courier'   => $order->get_meta( 'plugin_resi_ekspedisi',true ),
			'service'   => '',
			'tracking'  => $order->get_meta( 'plugin_resi_nomor_resi',true ),
		);
	}
	return wp_parse_args( $data, array(
		'courier'   => '',
		'service'   => '',
		'tracking'  => '',
	) );
}

function plugin_resi_set_order_data( $order_id, $args ) {
	$order = wc_get_order( $order_id );
	if(!$order){
	    return;
	}
	$old = plugin_resi_get_order_data( $order_id );
	$args = wp_parse_args( $args, $old );

	$order->update_meta_data( 'plugin_resi_data', $args );
	$order->save();
}

add_action( 'admin_enqueue_scripts', 'plugin_resi_enqueue_scripts', 10, 1 );
function plugin_resi_enqueue_scripts( $hook ) {
	$current_screen = get_current_screen();
	// woocommerce post type
	if ( $current_screen && ( 'shop_order' === $current_screen->post_type || 'woocommerce_page_wc-orders' === $current_screen->id ) ) {
		wp_enqueue_script( 'plugin_resi', plugins_url( 'asset/js/plugin-resi.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'plugin_resi', plugins_url( 'asset/css/plugin-resi.css', __FILE__ ) );

		// Localize the script with new data
		$translation_array = array(
			'empty_tracking_number' => __( 'Please fill the tracking number', PLUGIN_RESI ),
		);

		wp_localize_script( 'plugin_resi_script', 'plugin_resi_localization', $translation_array );
	}

	// plugin resi options page
	if ( $hook == 'toplevel_page_plugin-resi' ) {
		wp_register_script( 'plugin_resi_admin', plugins_url( 'asset/js/plugin-resi-admin.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'plugin_resi_admin' );

		wp_enqueue_style( 'plugin_resi', plugins_url( 'asset/css/plugin-resi-admin.css', __FILE__ ) );
	}
}

// add shipped status to sales report
add_filter( 'woocommerce_reports_order_statuses', 'plugin_resi_reports_order_statuses' );
function plugin_resi_reports_order_statuses( $args ) {
	$args[] = 'shipped';

	return $args;
}