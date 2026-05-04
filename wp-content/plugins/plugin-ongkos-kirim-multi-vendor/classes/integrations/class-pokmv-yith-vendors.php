<?php

/**
 * YITH Multivendor integration class
 */
class POKMV_YITH_Vendors {

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
	 * YITH taxonomy name.
	 * 
	 * @var string
	 */
	protected $taxonomy_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $pok_core;
		global $pok_helper;
		$this->core     = $pok_core;
		$this->setting  = new POK_Setting();
		$this->helper   = $pok_helper;
		$this->taxonomy_name = YITH_Vendors()->get_taxonomy_name();

		// admin.
		add_action( $this->taxonomy_name . '_add_form_fields', array( $this, 'add_taxonomy_fields' ) );
		add_action( $this->taxonomy_name . '_edit_form_fields', array( $this, 'edit_taxonomy_fields' ) );
		add_action( 'yith_wpv_after_save_taxonomy', array( $this, 'save_taxonomy_fields' ), 10, 2 );
		add_action( 'edited_' . $this->taxonomy_name, array( $this, 'save_taxonomy_fields_alt' ) );
		add_action( 'created_' . $this->taxonomy_name, array( $this, 'save_taxonomy_fields_alt' ) );
		add_action( 'yith_product_vendors_details_fields', array( $this, 'admin_vendor_custom_fields' ) );
		add_action( 'admin_init', array( $this, 'handle_form_submit' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// front.
		add_action( 'yith_wcmv_add_shipping_order_item', array( $this, 'add_shipping_to_suborder' ), 10, 2 );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_yithvendors', $this );
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
		pokmv_get_template_part( 'yith-vendors/add-taxonomy-fields', $args );
	}

	/**
	 * Add custom fields to edit taxonomy
	 *
	 * @param  object $term Term object.
	 */
	public function edit_taxonomy_fields( $term ) {
		$vendor = yith_get_vendor( $term );
		$args = array(
			'vendor_id' => $vendor->id,
		);
		pokmv_get_template_part( 'yith-vendors/edit-taxonomy-fields', $args );
	}

	/**
	 * Save custom taxonomy fields
	 *
	 * @param  object $vendor     Vendor object.
	 * @param  array  $post_value Post value.
	 */
	public function save_taxonomy_fields( $vendor, $post_value ) {
		$setting = pokmv_get_vendor( $vendor->id );
		if ( isset( $_POST['pokmv_vendor']['origin'] ) ) {
			$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
		}
		$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
	}

	/**
	 * Alternative hook to save taxonomy fields
	 *
	 * @param  integer $term_id Term ID.
	 */
	public function save_taxonomy_fields_alt( $term_id ) {
		$vendor = yith_get_vendor( get_term( $term_id ) );
		$this->save_taxonomy_fields( $vendor, array() );
	}

	/**
	 * Add custom fields to admin profile setting
	 *
	 * @param  integer $vendor_id Vendor ID.
	 */
	public function admin_vendor_custom_fields( $vendor_id ) {
		$args = array(
			'vendor_id' => $vendor_id,
		);
		pokmv_get_template_part( 'yith-vendors/admin-vendor-fields', $args );
	}

	/**
	 * Handle form submit
	 */
	public function handle_form_submit() {
		if ( isset( $_REQUEST['yith_vendor_admin_update_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['yith_vendor_admin_update_nonce'] ) ), 'yith_vendor_admin_update' ) ) { // Input var okay.
			if ( isset( $_POST['update_vendor_id'] ) ) { // Input var okay.
				$vendor_id = intval( $_POST['update_vendor_id'] ); // Input var okay.
				$setting = pokmv_get_vendor( $vendor_id );
				if ( isset( $_POST['pokmv_vendor']['origin'] ) ) { // Input var okay.
					$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
				}
				$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
			}
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( ( ( 'term' === $screen->base || 'edit-tags' === $screen->base ) && $this->taxonomy_name === $screen->taxonomy ) || 'toplevel_page_yith_vendor_details' === $screen->base ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
		}
	}

	/**
	 * Add shipping to suborder
	 *
	 * @param integer $suborder_id Suborder ID.
	 * @param object  $yith_orders YITH_Orders class.
	 */
	public function add_shipping_to_suborder( $suborder_id, $yith_orders ) {
		$suborder       = wc_get_order( $suborder_id );
		$parent_id      = $suborder->get_parent_id();
		$parent_order   = wc_get_order( $parent_id );
		$vendor         = yith_get_vendor( get_post_field( 'post_author', $suborder_id ), 'user' );

		foreach ( $parent_order->get_shipping_methods() as $shipping_method ) {
			if ( $shipping_method->meta_exists( 'vendor_id' ) && intval( $vendor->id ) === intval( $shipping_method->get_meta( 'vendor_id', true ) ) ) {
				if ( is_a( $shipping_method, 'WC_Order_Item_Shipping' ) ) {
					$item = new WC_Order_Item_Shipping();

					$item->set_props(
						array(
							'method_title' => $shipping_method->get_name(),
							'method_id'    => $shipping_method->get_method_id(),
							'total'        => $shipping_method->get_total(),
							'taxes'        => $shipping_method->get_taxes(),
						)
					);

					$metadata = $shipping_method->get_meta_data();

					if ( $metadata ) {
						foreach ( $metadata as $meta ) {
							$item->add_meta_data( $meta->key, $meta->value );
						}
					}

					$suborder->add_item( $item );
					$suborder->set_shipping_total( $shipping_method->get_total() );
					$suborder->save();
				}
			}
		}
	}

}
