<?php

if ( ! function_exists( 'pokmv_is_pok_compatible' ) ) {
	/**
	 * Check if Plugin Ongkos Kirim version is compatible
	 *
	 * @return boolean Compatibility status
	 */
	function pokmv_is_pok_compatible() {
		if ( ( class_exists( 'Plugin_Ongkos_Kirim' ) || in_array( 'plugin-ongkos-kirim/plugin-ongkos-kirim.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) && defined( 'POK_VERSION' ) && version_compare( POK_VERSION, '3.8.7', '>=' ) ) {
			global $pok_helper;
			if ( ! $pok_helper->is_plugin_active() ) {
				return false;
			}
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'pokmv_is_multi_vendor_active' ) ) {
	/**
	 * Check if one of many supported marketplace plugin active
	 *
	 * @return boolean Active status.
	 */
	function pokmv_is_multi_vendor_active() {
		return pokmv_is_dokan_active() || pokmv_is_wcmarketplace_active() || pokmv_is_wcvendors_active() || pokmv_is_yithvendors_active() || pokmv_is_productvendors_active() || pokmv_is_wcfm_active();
	}
}

if ( ! function_exists( 'pokmv_is_dokan_active' ) ) {
	/**
	 * Check if dokan is active
	 *
	 * @return boolean Dokan status.
	 */
	function pokmv_is_dokan_active() {
		return class_exists( 'WeDevs_Dokan' ) || in_array( 'dokan-lite/dokan.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) || in_array( 'dokan-pro/dokan-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_dokan_pro_active' ) ) {
	/**
	 * Check if dokan pro is active
	 *
	 * @return boolean Dokan status.
	 */
	function pokmv_is_dokan_pro_active() {
		return class_exists( 'Dokan_Pro' ) || in_array( 'dokan-pro/dokan-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_wcmarketplace_active' ) ) {
	/**
	 * Check if WC Marketplace is active
	 *
	 * @return boolean WC Marketplace status.
	 */
	function pokmv_is_wcmarketplace_active() {
		return class_exists( 'WCMp' ) || in_array( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_yithvendors_active' ) ) {
	/**
	 * Check if WC Vendors is active
	 *
	 * @return boolean WC Vendors status.
	 */
	function pokmv_is_yithvendors_active() {
		return class_exists( 'YITH_Vendors' ) || in_array( 'yith-woocommerce-product-vendors/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_wcvendors_active' ) ) {
	/**
	 * Check if WC Vendors is active
	 *
	 * @return boolean WC Vendors status.
	 */
	function pokmv_is_wcvendors_active() {
		return class_exists( 'WC_Vendors' ) || in_array( 'wc-vendors/class-wc-vendors.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_productvendors_active' ) ) {
	/**
	 * Check if WC Vendors is active
	 *
	 * @return boolean WC Vendors status.
	 */
	function pokmv_is_productvendors_active() {
		return class_exists( 'WC_Product_Vendors' ) || in_array( 'woocommerce-product-vendors/woocommerce-product-vendors.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_is_wcfm_active' ) ) {
	/**
	 * Check if WCFM Multivendor Marketplace is active
	 *
	 * @return boolean WC Vendors status.
	 */
	function pokmv_is_wcfm_active() {
		return class_exists( 'WCFMmp' ) || in_array( 'wc-multivendor-marketplace/wc-multivendor-marketplace.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}
}

if ( ! function_exists( 'pokmv_get_active_multivendor_plugin' ) ) {
	/**
	 * Get one of many marketplace plugin if more than one installed
	 *
	 * @return string Plugin code.
	 */
	function pokmv_get_active_multivendor_plugin() {
		$platform = 'pokmv';
		if ( pokmv_is_dokan_active() ) {
			$platform = 'dokan';
		} elseif ( pokmv_is_wcmarketplace_active() ) {
			$platform = 'wc-marketplace';
		} elseif ( pokmv_is_yithvendors_active() ) {
			$platform = 'yith-vendors';
		} elseif ( pokmv_is_wcvendors_active() ) {
			$platform = 'wc-vendors';
		} elseif ( pokmv_is_productvendors_active() ) {
			$platform = 'product-vendors';
		} elseif ( pokmv_is_wcfm_active() ) {
			$platform = 'wcfm';
		}
		return apply_filters( 'pokmv_active_multivendor_plugin', $platform );
	}
}

if ( ! function_exists( 'pokmv_get_template_part' ) ) {
	/**
	 * Get template part
	 *
	 * @param  string $slug File slug.
	 * @param  array  $args Given argument.
	 */
	function pokmv_get_template_part( $slug, $args = array() ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}
		include POKMV_PLUGIN_PATH . 'templates/' . $slug . '.php';
	}
}

if ( ! function_exists( 'pokmv_get_current_vendor_id' ) ) {
	/**
	 * Get current vendor id
	 *
	 * @return int Vendor ID.
	 */
	function pokmv_get_current_vendor_id() {
		$active_plugin = pokmv_get_active_multivendor_plugin();
		if ( 'dokan' === $active_plugin ) {
			$store_info = dokan_get_store_info( $vendor_id );
			return $store_info['store_name'];
		} elseif ( 'wc-marketplace' === $active_plugin ) {
			return get_current_vendor_id();
		} elseif ( 'yith-vendors' === $active_plugin ) {
			$vendor = yith_get_vendor( 'current', 'user' );
			return isset( $vendor->id ) ? $vendor->id : 0;
		} elseif ( 'product-vendors' === $active_plugin ) {
			return WC_Product_Vendors_Utils::get_logged_in_vendor();
		} else {
			return get_current_user_id();
		}
	}
}

if ( ! function_exists( 'pokmv_get_vendor_store_name' ) ) {
	/**
	 * Get vendor store name
	 *
	 * @param  integer $vendor_id Vendor ID.
	 * @return string             Store name.
	 */
	function pokmv_get_vendor_store_name( $vendor_id ) {
		$active_plugin = pokmv_get_active_multivendor_plugin();
		if ( 'dokan' === $active_plugin ) {
			$store_info = dokan_get_store_info( $vendor_id );
			return $store_info['store_name'];
		} elseif ( 'wc-marketplace' === $active_plugin ) {
			$vendor = get_wcmp_vendor( $vendor_id );
			if ( $vendor instanceof WCMp_Vendor ) {
				return $vendor->get_page_title();
			} else {
				return get_bloginfo( 'name' );
			}
		} elseif ( 'wc-vendors' === $active_plugin ) {
			return WCV_Vendors::is_vendor( $vendor_id ) ? WCV_Vendors::get_vendor_shop_name( $vendor_id ) : get_bloginfo( 'name' );
		} elseif ( 'yith-vendors' === $active_plugin ) {
			if ( 0 !== intval( $vendor_id ) ) {
				$vendor = yith_get_vendor( $vendor_id );
				if ( isset( $vendor->term->name ) ) {
					return $vendor->term->name;
				}
			}
			return get_bloginfo( 'name' );
		} elseif ( 'product-vendors' === $active_plugin ) {
			if ( 0 !== intval( $vendor_id ) && $vendor = WC_Product_Vendors_Utils::get_vendor_data_by_id( $vendor_id ) ) {
				return $vendor['name'];
			} else {
				return get_bloginfo( 'name' );
			}
		} elseif ( 'wcfm' === $active_plugin ) {
			if ( $store_name = wcfm_get_vendor_store_name( $vendor_id ) ) {
				return $store_name;
			}
			return get_bloginfo( 'name' );
		} elseif ( 'pokmv' === $active_plugin ) {
			if ( 0 !== intval( $vendor_id ) ) {
				$term = get_term( $vendor_id, POKMV_TAXONOMY_NAME );
				if ( ! is_null( $term ) ) {
					return $term->name;
				}
			}
			return get_bloginfo( 'name' );
		} else {
			$userdata = get_userdata( $vendor_id );
			if ( isset( $userdata->display_name ) ) {
				return $userdata->display_name;
			}
			return '';
		}
	}
}

if ( ! function_exists( 'pokmv_get_vendor' ) ) {
	/**
	 * Get vendor data
	 *
	 * @param  integer $vendor_id Vendor ID.
	 * @return object             POKMV_Vendor_Setting class.
	 */
	function pokmv_get_vendor( $vendor_id ) {
		return new POKMV_Vendor_Setting( $vendor_id );
	}
}

if ( ! function_exists( 'pokmv_is_user_vendor' ) ) {
	/**
	 * Check if user is a vendor
	 *
	 * @param  integer $user_id User ID.
	 * @return boolean          User is a vendor.
	 */
	function pokmv_is_user_vendor( $user_id ) {
		$active_plugin = pokmv_get_active_multivendor_plugin();
		if ( 'dokan' === $active_plugin ) {
			return dokan_is_user_seller( $user_id );
		} elseif ( 'wc-marketplace' === $active_plugin ) {
			return is_user_wcmp_vendor( $user_id );
		} elseif ( 'wc-vendors' === $active_plugin ) {
			return WCV_Vendors::is_vendor( $user_id );
		} elseif ( 'yith-vendors' === $active_plugin ) {
			$vendor = yith_get_vendor( 'current', 'user' );
			return isset( $vendor->id ) && 0 !== intval( $vendor->id );
		} elseif ( 'product-vendors' === $active_plugin ) {
			return WC_Product_Vendors_Utils::is_vendor( $user_id );
		}
		return false;
	}
}

if ( ! function_exists( 'pokmv_get_product_vendor' ) ) {
	/**
	 * Get vendor ID from product
	 *
	 * @param  object $product Product object.
	 * @return integer          Vendor ID.
	 */
	function pokmv_get_product_vendor( $product ) {
		if ( $product->is_type( 'variation' ) ) {
			$product = wc_get_product( $product->get_parent_id() );
		}
		$active_plugin = pokmv_get_active_multivendor_plugin();
		if ( 'yith-vendors' === $active_plugin ) {
			$vendor = yith_get_vendor( $product, 'product' );
			return isset( $vendor->id ) ? $vendor->id : 0;
		} elseif ( 'product-vendors' === $active_plugin ) {
			return intval( WC_Product_Vendors_Utils::get_vendor_id_from_product( $product->get_id() ) );
		} elseif ( 'pokmv' === $active_plugin ) {
			$terms = get_the_terms( $product->get_id(), POKMV_TAXONOMY_NAME );
			if ( $terms && ! empty( $terms ) ) {
				return $terms[0]->term_id;
			}
			return 0;
		} else {
			return get_post_field( 'post_author', $product->get_id() );
		}
	}
}

if ( ! function_exists( 'pokmv_get_city_name' ) ) {
	/**
	 * Get single city name
	 *
	 * @param  integer $city_id City ID.
	 * @return string           City name.
	 */
	function pokmv_get_city_name( $city_id ) {
		global $pok_core;
		if ( is_null( $pok_core ) ) {
			return false;
		}
		$city = $pok_core->get_single_city( $city_id );
		if ( isset( $city ) && ! empty( $city ) ) {
			return $city;
		}
		return false;
	}
}

if ( ! function_exists( 'pokmv_generate_field' ) ) {
	/**
	 * Generate setting field
	 *
	 * @param  string  $field     Field name.
	 * @param  integer $vendor_id Vendor ID.
	 */
	function pokmv_generate_field( $field, $vendor_id = 0 ) {
		global $pok_core;
		global $pok_helper;
		$pok_setting = new POK_Setting();
		$vendor_setting = pokmv_get_vendor( $vendor_id );
		$args = array(
			'pok_setting'       => $pok_setting->get_all(),
			'core'              => $pok_core,
			'helper'            => $pok_helper,
			'vendor_setting'    => $vendor_setting->get_all(),
		);
		switch ( $field ) {
			case 'origin':
				if ( 'rajaongkir' === $pok_setting->get( 'base_api' ) ) {
					$args['cities'] = $pok_core->get_all_city();
				} else {
					$args['origins'] = $pok_core->get_origins();
				}
				pokmv_get_template_part( 'fields/origin', $args );
				break;
			case 'couriers':
				pokmv_get_template_part( 'fields/couriers', $args );
				break;
		}
	}
}

if ( ! function_exists( 'pokmv_vendor_data_location' ) ) {
	/**
	 * Get vendor data store location based on platform
	 * 
	 * @return string Data location
	 */
	function pokmv_vendor_data_location() {
		if ( in_array( pokmv_get_active_multivendor_plugin(), array( 'pokmv', 'yith-vendors', 'product-vendors' ), true ) ) {
			return 'term';
		} else {
			return 'user';
		}
	}
}

if ( ! function_exists( 'pokmv_get_vendor_list' ) ) {
	/**
	 * Get vendor list
	 *
	 * @return array List of vendor.
	 */
	function pokmv_get_vendor_list() {
		global $wp_version;
		$vendors = array();
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$terms = get_terms(
				array(
					'taxonomy'      => POKMV_TAXONOMY_NAME,
					'hide_empty'    => false,
				)
			);
		} else {
			$terms = get_terms(
				POKMV_TAXONOMY_NAME, array(
					'hide_empty'    => false,
				)
			);
		}
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$vendors[ $term->term_id ] = $term->name;
			}
		}
		return $vendors;
	}
}

if ( ! function_exists( 'pokmv_is_wcfm_vendor_using_pok' ) ) {
	/**
	 * Check if WCFM's vendor is using POK as shipping
	 * 
	 * @return boolean Is vendor using POK
	 */
	function pokmv_is_wcfm_vendor_using_pok( $vendor_id ) {
		$vendor_shipping_details = get_user_meta( $vendor_id, '_wcfmmp_shipping', true );
		return isset( $vendor_shipping_details['_wcfmmp_user_shipping_type'] ) && 'by_pok' === $vendor_shipping_details['_wcfmmp_user_shipping_type'];
	}
}