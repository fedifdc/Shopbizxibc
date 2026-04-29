<?php

/**
 * WCFM Multivendor Marketplace integration class
 */
class POKMV_WCFM {

	/**
	 * POK core
	 * 
	 * @var POK_Core
	 */
	protected $core;

	/**
	 * POK Helper
	 * 
	 * @var POK_Helper
	 */
	protected $helper;

	/**
	 * POK Setting
	 * 
	 * @var POK_Setting
	 */
	protected $setting;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $pok_core;
		global $pok_helper;
		$this->core     = $pok_core;
		$this->setting  = new POK_Setting();
		$this->helper   = $pok_helper;

		// setting.
		add_filter( 'wcfmmp_shipping_types', array( $this, 'register_shipping_type' ) );
		add_action( 'begin_wcfm_settings_form_style', array( $this, 'render_store_manager_shipping_setting' ), 15 );
		add_action( 'wcfm_settings_update', array( &$this, 'update_store_manager_shipping_setting' ), 15 );
		add_action( 'wcfm_marketplace_shipping', array( &$this, 'render_vendor_shipping_setting' ) );
		add_action( 'wcfm_vendor_settings_before_update', array( $this, 'update_vendor_shipping_setting' ), 20, 2 );
		add_action( 'wcfm_vendor_shipping_settings_update', array( $this, 'update_vendor_shipping_setting' ), 20, 2 );

		// checkout.
		add_filter( 'wcfmmp_split_shipping_packages', array( $this, 'setup_split_packages' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_wcfm', $this );
	}

	/**
	 * Register new shipping type
	 * 
	 * @param  array $types Shipping types.
	 * @return array        Shipping types.
	 */
	public function register_shipping_type( $types ) {
		$types['by_pok'] = __( 'Shipping by Plugin Ongkos Kirim', 'pokmv' );
		return $types;
	}

	/**
	 * Render POK's shipping setting on the store manager side
	 */
	public function render_store_manager_shipping_setting() {
		global $WCFM, $WCFMmp;

		if( !apply_filters( 'wcfm_is_allow_store_shipping', true ) ) return;

		$wcfmmp_marketplace_shipping_pok_options = get_option( 'woocommerce_wcfmmp_product_shipping_by_pok_settings', array() );
		$wcfmmp_marketplace_shipping_pok_enabled = ( ! empty( $wcfmmp_marketplace_shipping_pok_options ) && ! empty( $wcfmmp_marketplace_shipping_pok_options['enabled'] ) ) ? $wcfmmp_marketplace_shipping_pok_options['enabled'] : 'yes';
		?>
		<div id="wcfm_settings_form_shipping_by_pok_expander" class="wcfm_store_shipping_fields" style="margin-top:50px;">
			<div class="wcfm_vendor_settings_heading">
				<h2><?php esc_html_e('Shipping By Plugin Ongkos Kirim', 'pokmv'); ?></h2>
			</div>
			<div class="wcfm_clearfix"></div>
			<div class="store_address">
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_marketplace_settings_fields_shipping_pok', array(
					"enable_marketplace_shipping_pok" => array(
						'label' =>
						__('Enable', 'pokmv'),
						'type' => 'checkbox',
						'class' => 'wcfm-checkbox wcfm_ele wcfm_store_shipping_fields',
						'label_class' => 'wcfm_title checkbox_title wcfm_store_shipping_fields',
						'value' => 'yes',
						'dfvalue' => $wcfmmp_marketplace_shipping_pok_enabled,
						'desc_class' => 'wcfm_page_options_desc wcfm_store_shipping_fields',
						'desc' => __( 'Uncheck this to disable shipping calculation by Plugin Ongkos Kirim.', 'pokmv' ) ),
				) ) );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle save store shipping setting.
	 * 
	 * @param  array $wcfm_settings_form Shipping settings.
	 */
	public function update_store_manager_shipping_setting( $wcfm_settings_form ) {
		// Shipping by Zone
		if( isset( $wcfm_settings_form['enable_marketplace_shipping_pok'] ) ) {
			$enable_marketplace_shipping_pok = 'yes';
		} else {
			$enable_marketplace_shipping_pok = 'no';
		}
		$wcfmmp_marketplace_shipping_pok_options = get_option( 'woocommerce_wcfmmp_product_shipping_by_pok_settings', array() );
		$wcfmmp_marketplace_shipping_pok_options['enabled'] = $enable_marketplace_shipping_pok;
		update_option( 'woocommerce_wcfmmp_product_shipping_by_pok_settings', $wcfmmp_marketplace_shipping_pok_options );
	}

	/**
	 * Render POK's shipping setting on the vendor side.
	 */
	public function render_vendor_shipping_setting( $vendor_id ) {
		global $WCFM, $WCFMmp, $pok_core, $pok_helper;
		$pok_setting = new POK_Setting();
	
		if( !apply_filters( 'wcfm_is_allow_store_shipping', true ) || !apply_filters( 'wcfm_is_allow_vshipping_settings', true ) ) return;
		
		$wcfm_shipping_options = get_option( 'wcfm_shipping_options', array() );
		$wcfmmp_store_shipping_enabled = isset( $wcfm_shipping_options['enable_store_shipping'] ) ? $wcfm_shipping_options['enable_store_shipping'] : 'yes';
		if( $wcfmmp_store_shipping_enabled != 'yes' ) return;

		$wcfmmp_marketplace_shipping_options = get_option( 'woocommerce_wcfmmp_product_shipping_by_pok_settings', array() );

		$setting = pokmv_get_vendor( $vendor_id );
		$vendor_origin = $setting->get( 'origin' );
		$vendor_courier = $setting->get( 'courier' );
		$couriers = $this->core->get_courier( '', '' );

		$origin_options = array();
		if ( 'rajaongkir' === $pok_setting->get( 'base_api' ) ) {
			$cities = $pok_core->get_all_city();
			foreach ( $cities as $city ) {
				$origin_options[ $city->city_id ] = ( 'Kabupaten' === $city->type ? 'Kab. ' : 'Kota ' ) . $city->city_name . ', ' . $city->province;
			}
		} else {
			$origins = $pok_core->get_origins();
			foreach ( $origins as $city ) {
				$origin_options[ $city->id ] = $city->name;
			}
		}

		$courier_options = array();
		foreach ( $couriers as $courier ) {
			$courier_options[ $courier ] = $pok_helper->get_courier_name( $courier );
		}

		?>
		<style>
			#wcfm-main-contentainer #pokmv_vendor_couriers {
				margin-left: 0;
			}
		</style>
		<div id="wcfmmp_settings_form_shipping_by_pok" class="wcfm-content shipping_type by_pok hide_if_shipping_disabled">
			<div class="wcfm_vendor_settings_heading">
				<h3><?php esc_html_e( 'Shipping By Plugin Ongkos Kirim', 'pokmv' ); ?></h3>
			</div>
			<?php
			if ( ! isset($wcfmmp_marketplace_shipping_options['enabled']) || $wcfmmp_marketplace_shipping_options['enabled'] == 'no' ) {
				esc_html_e( 'Shipping By Plugin Ongkos Kirim is disabled by Admin. Please contact admin for details', 'pokmv' );
			} else {
				$WCFM->wcfm_fields->wcfm_generate_form_field( array(
					"pokmv_vendor_origin" => array(
						'label'       => __('Shipping Origin', 'pokmv'),
						'name'        => 'wcfmmp_shipping[pokmv_vendor][origin]',
						'type'        => 'select',
						'options'     => $origin_options,
						'class'       => 'wcfm-select wcfm_ele init-select2',
						'label_class' => 'wcfm_title',
						'value'       => $vendor_origin,
						'hints'       => __( 'This is your store location', 'pokmv' )
					)
				) );
				$WCFM->wcfm_fields->wcfm_generate_form_field( array(
					"pokmv_vendor_couriers" => array(
						'label'       => __('Couriers', 'pokmv'),
						'name'        => 'wcfmmp_shipping[pokmv_vendor][courier]',
						'type'        => 'checklist',
						'options'     => $courier_options,
						'class'       => 'wcfm-checkbox wcfm_ele',
						'label_class' => 'wcfm_title',
						'value'       => $vendor_courier,
						'hints'       => __( 'Select preferred couriers for your shipping service.', 'pokmv' )
					)
				) );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Handle save vendor shipping setting
	 * 
	 * @param  integer $vendor_id          Vendor ID.
	 * @param  array   $wcfm_settings_form Shipping settings.
	 */
	public function update_vendor_shipping_setting( $vendor_id, $wcfm_settings_form ) {
		if ( isset( $wcfm_settings_form['wcfmmp_shipping']['pokmv_vendor'] ) ) {
			$setting = pokmv_get_vendor( $vendor_id );
			if ( isset( $wcfm_settings_form['wcfmmp_shipping']['pokmv_vendor']['origin'] ) ) {
				$setting->set( 'origin', $wcfm_settings_form['wcfmmp_shipping']['pokmv_vendor']['origin'] );
			}
			$setting->set( 'courier', isset( $wcfm_settings_form['wcfmmp_shipping']['pokmv_vendor']['courier'] ) ? $wcfm_settings_form['wcfmmp_shipping']['pokmv_vendor']['courier'] : array() );
		}
	}

	/**
	 * Setup splitted packages to set POK shipping meta
	 * 
	 * @param  array $packages Shipping packages.
	 * @return array           Shipping packages.
	 */
	public function setup_split_packages( $packages ) {
		global $pok_helper;
		foreach ( $packages as $vendor_id => $package ) {
			if ( pokmv_is_wcfm_vendor_using_pok( $vendor_id ) ) {
				$vendor_setting = pokmv_get_vendor( $vendor_id );
				$items = array();
				foreach ( WC()->cart->get_cart() as $key => $item ) {
					if ( $vendor_id == pokmv_get_product_vendor( $item['data'] ) ) {
						$items[ $key ] = $item;
					}
				}
				$packages[ $vendor_id ]['vendor_name'] = pokmv_get_vendor_store_name( $vendor_id );
				$packages[ $vendor_id ]['seller_id']   = $vendor_id;
				$packages[ $vendor_id ]['origin']      = $vendor_setting->get( 'origin' );
				$packages[ $vendor_id ]['vendor_setting'] = $vendor_setting->get_all();
				$packages[ $vendor_id ]['weight']      = $pok_helper->get_total_weight( $items );
			}
		}
		return $packages;
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		if ( 'store-manager' === get_query_var('pagename') ) {
			if ( ! wcfm_is_vendor() ) {
				wp_enqueue_script( 'pokmv-wcfm-manager', POKMV_PLUGIN_URL . '/assets/js/wcfm-manager.js', array(), POKMV_VERSION, true );
			}
		}
	}

}
