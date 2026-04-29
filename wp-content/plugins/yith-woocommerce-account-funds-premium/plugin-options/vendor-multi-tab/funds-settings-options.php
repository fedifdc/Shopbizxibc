<?php
/**
 * Plugin Options : Vendor funds options
 *
 * @package YITH\AccountFunds\PluginOptions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$feature_available = defined( 'YITH_WPV_PREMIUM' ) && defined( 'YITH_WPV_VERSION' ) && version_compare( YITH_WPV_VERSION, '3.7.0', '>=' );

$settings = array(
	'vendor-multi-tab-funds-settings' => array(
		'vendor_funds_settings_start' => array(
			'type' => 'title',
			'name' => __( 'Funds Settings', 'yith-woocommerce-account-funds' ),
		),
		'vendor_funds_description'    => $feature_available ? array() : array(
			'type'             => 'yith-field',
			'yith-type'        => 'html',
			'yith-display-row' => true,
			'html'             => sprintf(
				'<p class="info-box">%s</p>',
				__( 'These features are available only with YITH WooCommerce MultiVendor Premium 3.7.0 or greater', 'yith-woocommerce-account-funds' )
			),
		),
		'vendor_can_charge_funds'     => array(
			'id'        => 'ywf_vendor_can_charge',
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'name'      => __( 'The vendor can charge funds', 'yith-woocommerce-account-funds' ),
			'default'   => 'no',
			'desc'      => __( 'If enabled, vendor can charge funds like a normal customer', 'yith-woocommerce-account-funds' ),
		),
		'vendor_can_use_funds'        => array(
			'id'        => 'ywf_vendor_can_use',
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'name'      => __( 'The vendor can use funds', 'yith-woocommerce-account-funds' ),
			'default'   => 'no',
			'desc'      => __( 'If enabled, vendor can use funds to purchase products', 'yith-woocommerce-account-funds' ),
		),
		'vendor_funds_settings_end'   => array(
			'type' => 'sectionend',
		),
	),
);

return $settings;
