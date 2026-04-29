<?php

/**
 * Woocommerce Product Vendors integration class
 */
class POKMV_Product_Vendors {

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

		// admin.
		add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_add_form_fields', array( $this, 'add_taxonomy_fields' ) );
		add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_edit_form_fields', array( $this, 'edit_taxonomy_fields' ) );
		add_action( 'edited_' . WC_PRODUCT_VENDORS_TAXONOMY, array( $this, 'save_taxonomy_fields' ), 10, 2 );
		add_action( 'created_' . WC_PRODUCT_VENDORS_TAXONOMY, array( $this, 'save_taxonomy_fields' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// vendors.
		add_action( 'admin_menu', array( $this, 'register_vendor_menus' ), 999 );
		add_action( 'admin_init', array( $this, 'handle_form_submit' ) );
		add_action( 'wcpv_registration_form', array( $this, 'vendor_registration_form' ) );
		add_action( 'wcpv_shortcode_registration_form_process', array( $this, 'handle_vendor_registration_form' ), 10, 2 );
		add_action( 'wcpv_vendor_order_detail_order_data_column', array( $this, 'insert_shipping_detail' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_productvendors', $this );
	}

	/**
	 * Add custom fields to taxonomy
	 *
	 * @param string $taxonomy Taxonomy.
	 */
	public function add_taxonomy_fields( $taxonomy ) {
		$args = array(
			'vendor_id' => get_current_user_id(),
		);
		pokmv_get_template_part( 'product-vendors/add-taxonomy-fields', $args );
	}

	/**
	 * Add custom fields to edit taxonomy
	 *
	 * @param  object $term Term object.
	 */
	public function edit_taxonomy_fields( $term ) {
		$args = array(
			'vendor_id' => $term->term_id,
		);
		pokmv_get_template_part( 'product-vendors/edit-taxonomy-fields', $args );
	}

	/**
	 * Save custom taxonomy fields
	 *
	 * @param  integer $term_id Term ID.
	 */
	public function save_taxonomy_fields( $term_id ) {
		$setting = pokmv_get_vendor( $term_id );
		if ( isset( $_POST['pokmv_vendor']['origin'] ) ) {
			$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
		}
		$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( ( ( 'term' === $screen->base || 'edit-tags' === $screen->base ) && WC_PRODUCT_VENDORS_TAXONOMY === $screen->taxonomy ) || 'store-settings_page_wcpv-vendor-settings-shipping' === $screen->id || 'admin_page_wcpv-vendor-order' === $screen->id ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
		}
	}

	/**
	 * Register submenu shipping to vendor setting
	 */
	public function register_vendor_menus() {
		if ( WC_Product_Vendors_Utils::auth_vendor_user() && WC_Product_Vendors_Utils::is_admin_vendor() ) {
			add_submenu_page( 'wcpv-vendor-settings', __( 'Shipping', 'pokmv' ), __( 'Shipping', 'pokmv' ), 'manage_product', 'wcpv-vendor-settings-shipping', array( $this, 'render_shipping_setting_page' ) );
		}
	}

	/**
	 * Render shipping setting page
	 */
	public function render_shipping_setting_page() {
		$args = array(
			'vendor_id' => pokmv_get_current_vendor_id(),
		);
		pokmv_get_template_part( 'product-vendors/vendor-setting-shipping', $args );
	}

	/**
	 * Handle vendor setting save
	 */
	public function handle_form_submit() {
		if ( isset( $_REQUEST['wcpv_save_vendor_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wcpv_save_vendor_settings_nonce'] ) ), 'wcpv_save_vendor_settings' ) ) { // Input var okay.
			$vendor_id = pokmv_get_current_vendor_id(); // Input var okay.
			$setting = pokmv_get_vendor( $vendor_id );
			if ( isset( $_POST['pokmv_vendor']['origin'] ) ) { // Input var okay.
				$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
			}
			$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
		}
	}

	/**
	 * Add shipping origin to vendor registration field
	 */
	public function vendor_registration_form() {
		$args = array(
			'vendor_id' => get_current_user_id(),
		);
		pokmv_get_template_part( 'product-vendors/vendor-registration-form', $args );
	}

	/**
	 * Handle vendor registration form
	 *
	 * @param  array $args       Args.
	 * @param  array $form_items Form items.
	 */
	public function handle_vendor_registration_form( $args, $form_items ) {
		if ( isset( $form_items['pokmv_vendor']['origin'] ) ) {
			$term = get_term_by( 'name', $args['vendor_name'], WC_PRODUCT_VENDORS_TAXONOMY );
			if ( false !== $term ) {
				$setting = pokmv_get_vendor( $term->term_id );
				$setting->set( 'origin', $form_items['pokmv_vendor']['origin'] );
				$setting->set( 'courier', $this->core->get_courier( $this->setting->get( 'base_api' ), $this->setting->get( 'rajaongkir_type' ) ) );
			}
		}
	}

	/**
	 * Insert shipping detail to vendor's order detail
	 *
	 * @param  object $order Order object.
	 */
	public function insert_shipping_detail( $order ) {
		foreach ( $order->get_shipping_methods() as $shipping_method ) {
			if ( $shipping_method->meta_exists( 'vendor_id' ) && intval( pokmv_get_current_vendor_id() ) === intval( $shipping_method->get_meta( 'vendor_id', true ) ) ) {
				$method = $shipping_method;
			}
		}
		if ( isset( $method ) ) {
			?>
				<div class="pokmv-pv-insertion">
					<p>
						<strong><?php esc_html_e( 'Shipping service:', 'pokmv' ); ?></strong>
						<?php echo esc_html( $method->get_name() ); ?>
					</p>
					<p>
						<strong><?php esc_html_e( 'Shipping cost:', 'pokmv' ); ?></strong>
						<?php echo wp_kses_post( wc_price( $method->get_total() ) ); ?>
					</p>
				</div>
			<?php
		}
	}

	/**
	 * Enqueue WC Vendors specific scripts on front side
	 */
	public function front_enqueue_scripts() {
		global $post;
		if ( has_shortcode( $post->post_content, 'wcpv_registration' ) ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-product-vendors', POKMV_PLUGIN_URL . '/assets/css/product-vendors.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-front', POKMV_PLUGIN_URL . '/assets/js/front.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			wp_localize_script(
				'pokmv-front', 'pokmv', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

}
