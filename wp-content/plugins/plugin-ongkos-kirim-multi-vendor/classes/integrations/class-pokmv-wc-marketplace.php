<?php

/**
 * WC Marketplace integration class
 */
class POKMV_WC_Marketplace {

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
	 * COnstructor.
	 */
	public function __construct() {
		global $pok_core;
		global $pok_helper;
		$this->core     = $pok_core;
		$this->setting  = new POK_Setting();
		$this->helper   = $pok_helper;

		// front.
		add_filter( 'wcmp_fpm_fields_shipping', array( $this, 'front_product_shipping_field' ), 10, 2 );
		add_action( 'after_wcmp_fpm_data_meta_save', array( $this, 'save_product_shipping_field' ), 10, 3 );
		add_action( 'wcmp_before_shipping_form_end_vendor_dashboard', array( $this, 'front_vendor_shipping_origin_field' ) );
		add_action( 'before_wcmp_vendor_dashboard', array( $this, 'save_vendor_dashboard_data' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wcmp_frontend_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_filter( 'wcmp_add_vendor_shipping_class', '__return_false', 10 ); // disable vendor shipping class creation.

		// admin.
		add_filter( 'wcmp_vendor_fields', array( $this, 'custom_user_fields' ), 10, 2 );
		add_action( 'personal_options_update', array( $this, 'save_vendor_data' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_vendor_data' ) );
		add_action( 'wcmp_vendor_preview_tabs_post', array( $this, 'admin_vendor_setting_tab' ) );
		add_action( 'wcmp_vendor_preview_tabs_form_post', array( $this, 'admin_vendor_setting_content' ) );
		add_action( 'wcmp_vendor_preview_tabs_form_pre', array( $this, 'admin_vendor_setting_save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_wcmarketplace', $this );
	}

	/**
	 * Add shipping option to frontend product editor
	 *
	 * @param  array $fields     Shipping fields.
	 * @param  int   $product_id Product ID.
	 * @return array             New shipping fields.
	 */
	public function front_product_shipping_field( $fields, $product_id ) {
		$enable_insurance = ( 'yes' === get_post_meta( $product_id, 'enable_insurance', true ) ) ? 'yes' : 'no';
		$enable_timber_packing = ( 'yes' === get_post_meta( $product_id, 'enable_timber_packing', true ) ) ? 'yes' : 'no';

		$fields['enable_insurance'] = array(
			'label'         => __( 'Shipping insurance', 'pokmv' ),
			'text'          => __( 'Add shipping insurance fee on checkout', 'pokmv' ),
			'type'          => 'checkbox',
			'class'         => 'regular-select pro_ele simple variable',
			'label_class'   => 'pro_title',
			'value'         => 'yes',
			'dfvalue'       => $enable_insurance,
		);
		$fields['enable_timber_packing'] = array(
			'label'         => __( 'Timber packing', 'pokmv' ),
			'text'          => __( 'Add timber packing fee on checkout', 'pokmv' ),
			'type'          => 'checkbox',
			'class'         => 'regular-select pro_ele simple variable',
			'label_class'   => 'pro_title',
			'value'         => 'yes',
			'dfvalue'       => $enable_timber_packing,
		);
		return $fields;
	}

	/**
	 * Save product shipping option
	 *
	 * @param  int   $product_id Product ID.
	 * @param  array $form_data  Form data.
	 * @param  array $attributes Product attributes.
	 */
	public function save_product_shipping_field( $product_id, $form_data, $attributes ) {
		if ( isset( $form_data['enable_insurance'] ) && 'yes' === $form_data['enable_insurance'] ) {
			update_post_meta( $product_id, 'enable_insurance', 'yes' );
		} else {
			update_post_meta( $product_id, 'enable_insurance', 'no' );
		}
		if ( isset( $form_data['enable_timber_packing'] ) && 'yes' === $form_data['enable_timber_packing'] ) {
			update_post_meta( $product_id, 'enable_timber_packing', 'yes' );
		} else {
			update_post_meta( $product_id, 'enable_timber_packing', 'no' );
		}
	}

	/**
	 * Render shipping origin field on vendor dashboard
	 */
	public function front_vendor_shipping_origin_field() {
		$args = array(
			'vendor_id' => pokmv_get_current_vendor_id(),
		);
		pokmv_get_template_part( 'wc-marketplace/front-shipping', $args );
	}

	/**
	 * Save custom vendor dashboard data
	 */
	public function save_vendor_dashboard_data() {
		global $WCMp;
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			if ( 'vendor-shipping' === $WCMp->endpoints->get_current_endpoint() ) {
				if ( isset( $_POST['pokmv_vendor'] ) ) {
					$data = $_POST['pokmv_vendor'];
					$setting = pokmv_get_vendor( get_current_vendor_id() );
					if ( isset( $data['origin'] ) ) {
						$setting->set( 'origin', $data['origin'] );
					}
					$setting->set( 'courier', isset( $data['courier'] ) ? $data['courier'] : array() );
					if ( wc_has_notice( __( 'Shipping Data Not Updated', 'dc-woocommerce-multi-vendor' ), 'success' ) ) {
						wc_clear_notices();
						wc_add_notice( __( 'Shipping Data Updated', 'dc-woocommerce-multi-vendor' ), 'success' );
					}
				}
			}
		}
	}

	/**
	 * Enqueue scripts on admin
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( 'wcmp_page_vendors' === $screen->id ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
		}
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		if ( is_vendor_dashboard() ) {
			wp_enqueue_script( 'pokmv-front', POKMV_PLUGIN_URL . '/assets/js/front.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			wp_localize_script(
				'pokmv-front', 'pokmv', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Enqueue styles
	 *
	 * @param  boolean $is_vendor_dashboard Check if vendor dashboard.
	 */
	public function enqueue_styles( $is_vendor_dashboard = false ) {
		if ( $is_vendor_dashboard ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-marketplace', POKMV_PLUGIN_URL . '/assets/css/marketplace.css', array( 'select2' ), POKMV_VERSION );
		}
	}

	/**
	 * Add custom user fields
	 *
	 * @param  array $fields  User fields.
	 * @param  int   $user_id User ID.
	 * @return array          User fields.
	 */
	public function custom_user_fields( $fields, $user_id ) {
		if ( pokmv_is_user_vendor( $user_id ) ) {
			$vendor_setting = pokmv_get_vendor( $user_id );
			$origin = $vendor_setting->get( 'origin' );
			$city = $this->core->get_single_city( $origin );
			$fields['vendor_shipping_origin'] = array(
				'name'      => 'pokmv_vendor[origin]',
				'label'     => __( 'Shipping origin', 'pokmv' ),
				'type'      => 'select',
				'value'     => $origin,
			);
			if ( 'rajaongkir' === $this->setting->get( 'base_api' ) ) {
				$fields['vendor_shipping_origin']['class'] = 'user-profile-fields regular-text init-select2';
				$fields['vendor_shipping_origin']['options'] = array();
				foreach ( $this->core->get_all_city() as $rcity ) {
					$fields['vendor_shipping_origin']['options'][ $rcity->city_id ] = ( 'Kabupaten' === $rcity->type ? 'Kab. ' : 'Kota ' ) . $rcity->city_name . ', ' . $rcity->province;
				}
			} else {
				$fields['vendor_shipping_origin']['class'] = 'user-profile-fields regular-text select2-ajax';
				$fields['vendor_shipping_origin']['options'] = isset( $city ) && ! empty( $city ) ? array( $origin => $city ) : array();
				$fields['vendor_shipping_origin']['custom_attributes'] = array(
					'action'    => 'pok_search_city',
					'nonce'     => wp_create_nonce( 'search_city' ),
				);
			}
		}
		return $fields;
	}

	/**
	 * Save vendor shipping origin from user edit
	 *
	 * @param  integer $user_id User ID.
	 */
	public function save_vendor_data( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		if ( isset( $_POST['pokmv_vendor']['origin'] ) ) {
			$setting = pokmv_get_vendor( $user_id );
			$setting->set( 'origin', intval( $_POST['pokmv_vendor']['origin'] ) );
		}
	}

	/**
	 * Add new tab to vendor setting
	 *
	 * @param  boolean $is_approved_vendor Check if vendor is approved.
	 */
	public function admin_vendor_setting_tab( $is_approved_vendor ) {
		if ( $is_approved_vendor ) {
			?>
			<li> 
				<a href="#shipping"><span class="dashicons dashicons-location-alt"></span> <?php esc_html_e( 'Shipping', 'pokmv' ); ?></a>
			</li>
			<?php
		}
	}

	/**
	 * Add new tab content to vendor setting
	 *
	 * @param  boolean $is_approved_vendor Check if vendor is approved.
	 */
	public function admin_vendor_setting_content( $is_approved_vendor ) {
		if ( $is_approved_vendor ) {
			$vendor_id = isset( $_GET['ID'] ) ? intval( $_GET['ID'] ) : 0; // Input var okay.
			$args = array(
				'vendor_id' => $vendor_id,
			);
			pokmv_get_template_part( 'wc-marketplace/admin-manage-vendor', $args );
		}
	}

	/**
	 * Save vendor profile
	 *
	 * @param  boolean $is_approved_vendor Check if vendor is approved.
	 */
	public function admin_vendor_setting_save( $is_approved_vendor ) {
		if ( $is_approved_vendor && isset( $_GET['ID'] ) && pokmv_is_user_vendor( intval( $_GET['ID'] ) ) && isset( $_POST['pokmv_vendor'] ) ) {
			$setting = pokmv_get_vendor( intval( $_GET['ID'] ) );
			if ( isset( $_POST['pokmv_vendor']['origin'] ) ) {
				$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
			}
			$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
		}
	}

}
