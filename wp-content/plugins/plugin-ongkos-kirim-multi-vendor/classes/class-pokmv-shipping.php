<?php

/**
 * Shipping class
 */
class POKMV_Shipping {

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
		global $pok_helper;
		$this->helper = $pok_helper;
		$this->setting = new POK_Setting();
		if ( 'yes' === $this->setting->get( 'enable_multi_vendor' ) ) {
			add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'set_packages' ) );
			add_filter( 'woocommerce_shipping_package_name', array( $this, 'change_package_names' ), 999999, 3 );
			add_filter( 'pok_admin_switch_courier_origin', array( $this, 'change_switch_courier_origin' ), 10, 2 );
			add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_item_meta' ) );
			if ( $this->helper->compare_wc_version( '>=', '3.1.0' ) ) {
				add_filter( 'woocommerce_order_item_display_meta_key', array( $this, 'change_meta_keys' ), 10, 3 );
				add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'change_meta_values' ), 60, 3 );
			}
			add_filter( 'pok_calculate_cost_couriers', array( $this, 'set_vendor_couriers' ), 10, 2 );
			add_filter( 'pok_rates_apply_filter', array( $this, 'disable_pok_service_filter' ), 10, 3 );
			add_filter( 'pok_rates', array( $this, 'filter_services' ), 10, 2 );
			add_filter( 'pok_rate', array( $this, 'add_rate_meta' ), 10, 3 );
			add_filter( 'pok_shipping_estimation_origin', array( $this, 'get_origin_shipping_from_vendor' ), 10, 2 );
			add_action( 'pok_hooks_addresses', array( $this, 'unhook_pok_addresses' ) );

			// Let 3rd parties unhook the above via this hook.
			do_action( 'pokmv_shipping', $this );
		}
	}

	/**
	 * Split shipping fee options depend on vendors on the cart
	 *
	 * @param array $packages Packages.
	 */
	public function set_packages( $packages ) {
		$vendor_packages = array();
		$vendors = $this->get_vendors_from_cart();
		foreach ( $vendors as $vendor_id => $items ) {
			$vendor_setting = pokmv_get_vendor( $vendor_id );
			$vendor_packages[ $vendor_id ] = array(
				'vendor_name'   => pokmv_get_vendor_store_name( $vendor_id ),
				'vendor_id'     => $vendor_id,
				'seller_id'     => $vendor_id,
				'origin'        => $vendor_setting->get( 'origin' ),
				'vendor_setting' => $vendor_setting->get_all(),
				'weight'        => $this->helper->get_total_weight( $items ),
				'contents'      => $items,
				'contents_cost' => array_sum( wp_list_pluck( $items, 'line_total' ) ),
				'applied_coupons' => WC()->cart->applied_coupons,
				'destination'   => array(
					'country'   => WC()->customer->get_shipping_country(),
					'state'     => WC()->customer->get_shipping_state(),
					'postcode'  => WC()->customer->get_shipping_postcode(),
					'city'      => WC()->customer->get_shipping_city(),
					'address'   => WC()->customer->get_shipping_address(),
					'address_2' => WC()->customer->get_shipping_address_2(),
				),
			);
		}
		return $vendor_packages;
	}

	/**
	 * Change package names
	 *
	 * @param  string  $title   Current title.
	 * @param  integer $i       Repeater.
	 * @param  array   $package Package data.
	 * @return string           New title.
	 */
	public function change_package_names( $title, $i, $package ) {
		// hide shipping detail on WCFM if vendor is using another shipping type.
		if ( 'wcfm' === pokmv_get_active_multivendor_plugin() && isset( $package['vendor_id'] ) && false === pokmv_is_wcfm_vendor_using_pok( $package['vendor_id'] ) ) {
			return $title;
		}
		if ( 'wcfm' !== pokmv_get_active_multivendor_plugin() ) {
			$title  = __( 'Shipping from', 'pokmv' ) . ' ' . $package['vendor_name'];
		}
		$title  .= '<p class="pokmv-cart-vendor" style="line-height: 1; font-weight: normal;">';
		if ( 'yes' === $this->setting->get( 'show_weight_on_checkout' ) ) {
			$title .= '<small>' . esc_html__( 'Ship weight', 'pokmv' ) . ': ' . $package['weight'] . ' kg</small><br/>';
		}
		$title .= '<small>' . esc_html__( 'Ship from', 'pokmv' ) . ': ' . pokmv_get_city_name( $package['origin'] ) . '</small>';
		$title .= '</p>';
		return apply_filters( 'pokmv_checkout_shipping', $title, $package );
	}

	/**
	 * Group cart contents by vendor
	 *
	 * @return array Contents per vendor.
	 */
	private function get_vendors_from_cart() {
		$vendors = array();
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			$vendor_id = pokmv_get_product_vendor( $item['data'] );
			if ( ! isset( $vendors[ $vendor_id ] ) ) {
				$vendors[ $vendor_id ] = array();
			}
			$vendors[ $vendor_id ][ $key ] = $item;
		}
		return $vendors;
	}

	/**
	 * Change shipping origin when change courier from admin.
	 *
	 * @param  int    $origin Original origin.
	 * @param  object $item   Shipping item.
	 * @return int            Vendor origin.
	 */
	public function change_switch_courier_origin( $origin, $item ) {
		if ( $item->meta_exists( 'seller_id' ) ) {
			$vendor = pokmv_get_vendor( $item->get_meta( 'seller_id', true ) );
			$origin = $vendor->get( 'origin' );
		}
		return $origin;
	}

	/**
	 * Hide custom item meta from order
	 *
	 * @param  array $metas Item metas.
	 * @return array        Item metas.
	 */
	public function hide_item_meta( $metas ) {
		$metas[] = 'vendor_id';
		$metas[] = 'package_qty';
		return $metas;
	}

	/**
	 * Change meta keys
	 *
	 * @param  string $meta_key Original meta key.
	 * @param  object $meta     Meta object.
	 * @param  object $item     Item object.
	 * @return string           Changed meta key.
	 */
	public function change_meta_keys( $meta_key, $meta, $item ) {
		if ( 'seller_id' === $meta->key ) {
			$meta_key = __( 'Ship from', 'pokmv' );
		}
		return $meta_key;
	}

	/**
	 * Change meta values
	 *
	 * @param  string $display_value Original value.
	 * @param  object $meta          Meta object.
	 * @param  object $item          Item object.
	 * @return string                Changed value.
	 */
	public function change_meta_values( $display_value, $meta, $item ) {
		if ( 'seller_id' === $meta->key ) {
			global $pok_core;
			$vendor = pokmv_get_vendor( $meta->value );
			$origin = $vendor->get( 'origin' );
			$city = $pok_core->get_single_city( $origin );
			$display_value = pokmv_get_vendor_store_name( $meta->value ) . ' (' . $city . ')';
		}
		return $display_value;
	}

	/**
	 * Set vendor couriers when get
	 *
	 * @param array $couriers Couriers.
	 * @param array $package  Package data.
	 */
	public function set_vendor_couriers( $couriers, $package ) {
		if ( isset( $package['vendor_id'] ) && isset( $package['vendor_setting'] ) ) {
			$couriers = $package['vendor_setting']['courier'];
		}
		return $couriers;
	}

	/**
	 * Filter courier services
	 *
	 * @param  array $rates   Rates.
	 * @param  array $package Package data.
	 * @return array          Rates.
	 */
	public function filter_services( $rates, $package ) {
		if ( isset( $package['vendor_id'] ) && isset( $package['vendor_setting'] ) && 'yes' === $package['vendor_setting']['specific_service'] ) {
			$tmp_rates = array();
			foreach ( $rates as $rate ) {
				if ( in_array( sanitize_title( $rate['class'] ), $package['vendor_setting']['specific_service_option'], true ) ) {
					$tmp_rates[] = $rate;
				}
			}
			$rates = $tmp_rates;
		}
		return $rates;
	}

	/**
	 * Disable POK's service filter functionality if vendor is detected
	 *
	 * @param  boolean $is_active Filter active or not.
	 * @param  array   $rate      Rate data.
	 * @param  array   $package   Package data.
	 * @return boolean            Filter active or not.
	 */
	public function disable_pok_service_filter( $is_active, $rate, $package ) {
		if ( isset( $package['vendor_id'] ) && 0 !== $package['vendor_id'] ) {
			$is_active = false;
		}
		return $is_active;
	}

	/**
	 * Add vendor_id to rate meta
	 *
	 * @param array $final_rate WC Rate.
	 * @param array $rate       POK Rate.
	 * @param array $package    Package data.
	 */
	public function add_rate_meta( $final_rate, $rate, $package ) {
		if ( isset( $package['vendor_id'] ) ) {
			$final_rate['meta_data']['seller_id'] = $package['vendor_id'];
			$final_rate['meta_data']['vendor_id'] = $package['vendor_id'];
		}
		return $final_rate;
	}

	/**
	 * Unhook POK Addresses hooks
	 *
	 * @param  object $class POK_Hooks_Addresses class.
	 */
	public function unhook_pok_addresses( $class ) {
		remove_action( 'woocommerce_review_order_after_cart_contents', array( $class, 'show_additional_info_on_checkout' ) );
	}

	/**
	 * Get origin shipping based on vendor of the product
	 *
	 * @param int $origin default origin
	 * @param WC_Product_Simple $product profuct object
	 * @return int $origin	origin id
	 * @throws conditon
	 **/
	public function get_origin_shipping_from_vendor( int $origin, $product ) {
		$vendor_id = pokmv_get_product_vendor( $product );

		if ( ! $vendor_id ) {
			return $origin;
		}

		$vendor_setting = pokmv_get_vendor( $vendor_id );

		return (int) ($vendor_setting->get( 'origin' ) ?? $origin);
	}
}
