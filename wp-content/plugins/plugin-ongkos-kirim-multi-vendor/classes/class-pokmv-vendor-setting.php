<?php

/**
 * Vendor Setting class
 */
class POKMV_Vendor_Setting {

	/**
	 * POK core
	 * 
	 * @var POK_Core
	 */
	protected $core;

	/**
	 * POK Setting
	 * 
	 * @var POK_Setting
	 */
	protected $pok_setting;

	/**
	 * Vendor ID
	 * 
	 * @var integer
	 */
	public $vendor_id;

	/**
	 * Vendor setting location
	 * 
	 * @var string
	 */
	public $store_at;

	/**
	 * Vendor settings
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Default settings
	 *
	 * @var array
	 */
	private $defaults;

	/**
	 * Constructor
	 *
	 * @param int $vendor_id Vendor ID.
	 */
	public function __construct( $vendor_id ) {
		global $pok_core;
		$this->core         = $pok_core;
		$this->pok_setting  = new POK_Setting();
		$this->init_defaults();
		$this->vendor_id = $vendor_id;
		$this->store_at = pokmv_vendor_data_location();
		if ( 'term' === $this->store_at ) {
			$this->settings = get_term_meta( $vendor_id, 'pokmv_vendor_setting', true );
		} else {
			$this->settings = get_user_meta( $vendor_id, 'pokmv_vendor_setting', true );
		}
	}

	/**
	 * Init default settings
	 */
	private function init_defaults() {
		$store_location = $this->pok_setting->get( 'store_location' );
		$couriers       = $this->pok_setting->get( 'couriers' );
		if ( is_array( $store_location ) && ! empty( $store_location ) && isset( $store_location[0] ) ) {
			$origin = $store_location[0];
		} else {
			$origin = 0;
		}
		$this->defaults = array(
			'origin'    				=> $origin,
			'courier'   				=> $couriers,
			'specific_service'          => 'no',
			'specific_service_option'   => array(),
		);
	}

	/**
	 * Get all settings
	 *
	 * @return array Settings
	 */
	public function get_all() {
		if ( '' !== $this->settings && is_array( $this->settings ) ) {
			return wp_parse_args( $this->settings, $this->defaults );
		}
		return $this->defaults;
	}

	/**
	 * Get single setting
	 *
	 * @param  string $index Setting index.
	 * @return mixed         Setting value.
	 */
	public function get( $index = '' ) {
		if ( '' !== $this->settings && is_array( $this->settings ) && isset( $this->settings[ $index ] ) ) {
			return $this->settings[ $index ];
		} elseif ( isset( $this->defaults[ $index ] ) ) {
			return $this->defaults[ $index ];
		}
		return false;
	}

	/**
	 * Set setting value
	 *
	 * @param string $index Setting index.
	 * @param mixed  $value Setting value.
	 */
	public function set( $index, $value ) {
		$settings = $this->get_all();
		if ( in_array( $index, array_keys( $this->defaults ), true ) ) {
			$settings[ $index ] = $value;
		}
		if ( 'term' === $this->store_at ) {
			update_term_meta( $this->vendor_id, 'pokmv_vendor_setting', $settings );
			$this->settings = get_term_meta( $this->vendor_id, 'pokmv_vendor_setting', true );
		} else {
			update_user_meta( $this->vendor_id, 'pokmv_vendor_setting', $settings );
			$this->settings = get_user_meta( $this->vendor_id, 'pokmv_vendor_setting', true );
		}
	}

}
