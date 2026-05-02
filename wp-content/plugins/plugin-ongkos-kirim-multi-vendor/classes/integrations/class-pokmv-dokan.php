<?php

/**
 * Dokan integration class
 */
class POKMV_Dokan {

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

		// front.
		add_action( 'dokan_get_dashboard_settings_nav', array( $this, 'add_vendor_shipping_menu' ) );
		add_action( 'dokan_render_settings_content', array( $this, 'add_vendor_shipping_page' ), 20 );
		add_action( 'wp_ajax_pokmv_dokan_settings', array( $this, 'ajax_settings' ) );
		add_filter( 'dokan_seller_wizard_steps', array( $this, 'insert_wizard_step' ) );
		add_action( 'dokan_product_edit_after_inventory_variants', array( $this, 'add_shipping_section_on_product' ), 10, 2 );
		add_action( 'dokan_product_updated', array( $this, 'save_product' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// admin.
		add_action( 'dokan_seller_meta_fields', array( $this, 'custom_user_meta' ), 9 );
		add_action( 'profile_update', array( $this, 'save_vendor_data' ), 10, 2 );
		add_filter( 'dokan_vendor_to_array', array( $this, 'vendor_data' ), 10, 2 );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_dokan', $this );
	}

	/**
	 * Add shipping tab menu to vendor dashboard
	 *
	 * @param array $menus Dashboard menu.
	 */
	public function add_vendor_shipping_menu( $menus ) {
		if ( ! isset( $menus['shipping'] ) ) {
			$menus['shipping'] = array(
				'title'      => __( 'Shipping', 'pokmv' ),
				'icon'       => '<i class="fa fa-truck"></i>',
				'url'        => dokan_get_navigation_url( 'settings/shipping' ),
				'pos'        => 40,
				'permission' => 'dokan_view_store_settings_menu',
			);
		}
		return $menus;
	}

	/**
	 * Add shipping tab content to vendor dashboard
	 *
	 * @param array $query_vars Current query vars.
	 */
	public function add_vendor_shipping_page( $query_vars ) {
		if ( isset( $query_vars['settings'] ) && 'shipping' === $query_vars['settings'] ) {
			if ( ! current_user_can( 'dokan_view_store_settings_menu' ) ) {
				dokan_get_template_part(
					'global/dokan-error', '', array(
						'deleted' => false,
						'message' => __( 'You have no permission to view this page', 'pokmv' ),
					)
				);
			} else {
				$args = array(
					'vendor_id' => dokan_get_current_user_id(),
				);
				pokmv_get_template_part( 'dokan/vendor-shipping', $args );
			}
		}
	}

	/**
	 * Handle saving vendor shipping via ajax
	 */
    function ajax_settings() {

        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            wp_send_json_error( __( 'Are you cheating?', 'pokmv' ) );
        }

        if ( ! isset( $_POST['_wpnonce'] ) ) {
            wp_send_json_error( __( 'Are you cheating?', 'pokmv' ) );
        }

        $post_data = wp_unslash( $_POST );

        $post_data['dokan_update_profile'] = '';

        switch ( $post_data['form_id'] ) { // WPCS: CSRF ok.
            case 'shipping-form':
                if ( ! current_user_can( 'dokan_view_store_settings_menu' ) ) {
                    wp_send_json_error( __( 'Pemission denied', 'pokmv' ) );
                }

                if ( ! wp_verify_nonce( sanitize_key( $post_data['_wpnonce'] ), 'dokan_shipping_settings_nonce' ) ) {
                    wp_send_json_error( __( 'Are you cheating?', 'pokmv' ) );
                }
                break;
        }

        if ( isset( $post_data['pokmv_vendor'] ) ) {
        	$this->save_profile( dokan_get_current_user_id(), $post_data['pokmv_vendor'] );
        }

        $success_msg = __( 'Shipping settings has been updated', 'pokmv' );

        $data = apply_filters( 'dokan_ajax_settings_response', array(
            'msg' => $success_msg,
        ) );

        wp_send_json_success( $data );
    }

	/**
	 * Insert step shipping to vendor registration wizard
	 *
	 * @param  array $steps Wizard steps.
	 * @return array        Wizard steps.
	 */
	public function insert_wizard_step( $steps ) {
		$custom_steps = array(
			'introduction'  => $steps['introduction'],
			'store'         => $steps['store'],
			'shipping'      => array(
				'name'      => __( 'Shipping', 'pokmv' ),
				'view'      => array( $this, 'wizard_setup_shipping' ),
				'handler'   => array( $this, 'wizard_setup_shipping_save' ),
			),
			'payment'       => $steps['payment'],
			'next_steps'    => $steps['next_steps'],
		);
		return $custom_steps;
	}

	/**
	 * Wizard step shipping content
	 */
	public function wizard_setup_shipping() {
		$args = array(
			'vendor_id' => dokan_get_current_user_id(),
		);
		pokmv_get_template_part( 'dokan/wizard-shipping', $args );
	}

	/**
	 * Wizard step shipping handle
	 */
	public function wizard_setup_shipping_save() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'dokan-seller-setup' ) ) {
			return;
		}

		if ( isset( $_POST['pokmv_vendor'] ) ) {
			$this->save_profile( dokan_get_current_user_id(), $_POST['pokmv_vendor'] );
		}

		wp_redirect( esc_url_raw( add_query_arg( 'step', 'payment' ) ) );
		exit;
	}

	/**
	 * Add shipping section to the product
	 *
	 * @param object $post    Post object.
	 * @param int    $post_id Post ID.
	 */
	public function add_shipping_section_on_product( $post, $post_id ) {
		$args = array(
			'post'      => $post,
			'post_id'   => $post_id,
		);
		pokmv_get_template_part( 'dokan/product-shipping', $args );
	}

	/**
	 * Save product detail
	 *
	 * @param  int $post_id Product ID.
	 */
	public function save_product( $post_id ) {
		if ( $product = wc_get_product( $post_id ) ) {
			if ( isset( $_POST['_weight'] ) ) {
				update_post_meta( $post_id, '_weight', floatval( $_POST['_weight'] ) );
			}
			if ( isset( $_POST['_length'] ) ) {
				update_post_meta( $post_id, '_length', floatval( $_POST['_length'] ) );
			}
			if ( isset( $_POST['_width'] ) ) {
				update_post_meta( $post_id, '_width', floatval( $_POST['_width'] ) );
			}
			if ( isset( $_POST['_height'] ) ) {
				update_post_meta( $post_id, '_height', floatval( $_POST['_height'] ) );
			}
			if ( isset( $_POST['enable_insurance'] ) && 'yes' === $_POST['enable_insurance'] ) {
				update_post_meta( $post_id, 'enable_insurance', 'yes' );
			} else {
				update_post_meta( $post_id, 'enable_insurance', 'no' );
			}
			if ( isset( $_POST['enable_timber_packing'] ) && 'yes' === $_POST['enable_timber_packing'] ) {
				update_post_meta( $post_id, 'enable_timber_packing', 'yes' );
			} else {
				update_post_meta( $post_id, 'enable_timber_packing', 'no' );
			}
		}
	}

	/**
	 * Custom field on edit user
	 *
	 * @param  object $user User object.
	 */
	public function custom_user_meta( $user ) {
		$setting = pokmv_get_vendor( $user->ID );
		$vendor_origin = $setting->get( 'origin' );
		$couriers = $this->core->get_courier( '', '' );
		?>
		<tr>
			<th><?php esc_html_e( 'Shipping origin', 'pokmv' ); ?></th>
			<td>
				<?php if ( 'rajaongkir' === $this->setting->get( 'base_api' ) ) : ?>
					<select name="pokmv_vendor[origin]" id="pok-vendor-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Select city', 'pokmv' ); ?>">
						<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
						<?php foreach ( $this->core->get_all_city() as $city ) : ?>
							<option value="<?php echo esc_attr( $city->city_id ); ?>" <?php echo ! empty( $vendor_origin ) && $vendor_origin === $city->city_id ? 'selected' : ''; ?>><?php echo esc_html( ( 'Kabupaten' === $city->type ? 'Kab. ' : 'Kota ' ) . $city->city_name . ', ' . $city->province ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<select name="pokmv_vendor[origin]" id="pok-vendor-origin" class="init-select2" placeholder="<?php esc_attr_e( 'Select city', 'pokmv' ); ?>">
						<option value=""><?php esc_html_e( 'Select your store location', 'pokmv' ); ?></option>
						<?php foreach ( $this->core->get_origins() as $city ) : ?>
							<option value="<?php echo esc_attr( $city->id ); ?>" <?php echo ! empty( $vendor_origin ) && $vendor_origin === $city->id ? 'selected' : ''; ?>><?php echo esc_html( $city->name ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Shipping Courier', 'pokmv' ); ?></th>
			<td>
				<?php foreach ( $couriers as $key => $courier ) : ?>
					<label>
						<input type="checkbox" name="pokmv_vendor[courier][]"
							value="<?php echo esc_attr( $courier ); ?>"
							<?php echo in_array( $courier, $setting->get( 'courier' ), true ) ? 'checked' : ''; ?>>
							<?php echo esc_html( $this->helper->get_courier_name( $courier ) ); ?>
					</label>
					<br>
				<?php endforeach; ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save user data
	 *
	 * @param  integer $user_id       User ID.
	 * @param  array   $old_user_data Old user data.
	 */
	public function save_vendor_data( $user_id, $old_user_data ) {
		if ( isset( $_POST['pokmv_vendor'] ) ) {
			$this->save_profile( $user_id, $_POST['pokmv_vendor'] );
		}
	}

	/**
	 * Save profile
	 *
	 * @param  integer $vendor_id Vendor ID.
	 * @param  array   $data      Saved data.
	 */
	private function save_profile( $vendor_id, $data ) {
		$setting = pokmv_get_vendor( $vendor_id );
		if ( isset( $data['origin'] ) ) {
			$setting->set( 'origin', $data['origin'] );
		}
		$setting->set( 'courier', isset( $data['courier'] ) ? $data['courier'] : array() );
	}

	/**
	 * Enqueue dokan specific scripts
	 */
	public function enqueue_scripts() {
		global $wp;
		if ( ( isset( $wp->query_vars['settings'] ) && 'shipping' === $wp->query_vars['settings'] ) || ( isset( $wp->query_vars['post_type'] ) && 'product' === $wp->query_vars['post_type'] && isset( $wp->query_vars['edit'] ) && 'true' === $wp->query_vars['edit'] ) ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-dokan', POKMV_PLUGIN_URL . '/assets/css/dokan.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-dokan-front', POKMV_PLUGIN_URL . '/assets/js/dokan-front.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			wp_localize_script(
				'pokmv-dokan-front', 'pokmv', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Add vendor shipping setting to the Dokan's REST
	 * 
	 * @param  array $data         Original vendor data.
	 * @param  class $vendor_class WeDevs\Dokan\Vendor class.
	 * @return array               Updated vendor data.
	 */
	public function vendor_data( $data, $vendor_class ) {
		$setting = pokmv_get_vendor( $data['id'] );
		$data['pokmv'] = $setting->get_all();
		return $data;
	}

}
