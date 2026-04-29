<?php

/**
 * WC Vendors integration class
 */
class POKMV_WC_Vendors {

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
		add_action( 'wcvendors_shop_settings_admin_saved', array( $this, 'save_vendor_setting' ) );
		add_filter( 'wcvendors_vendor_order_page_get_columns', array( $this, 'insert_shipping_column_to_orders' ) );
		add_filter( 'wcvendors_vendor_order_page_column_default', array( $this, 'insert_shipping_content_to_orders' ), 10, 3 );

		// front.
		add_action( 'wcvendors_settings_after_shop_description', array( $this, 'add_vendor_shipping_option' ) );
		add_action( 'wcvendors_shop_settings_saved', array( $this, 'save_vendor_setting' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_hooks_wcvendors', $this );
	}

	/**
	 * Insert new column to orders table
	 *
	 * @param  array $columns Columns.
	 * @return array          Columns.
	 */
	public function insert_shipping_column_to_orders( $columns ) {
		$edited_columns = array(
			'cb'        => $columns['cb'],
			'order_id'  => $columns['order_id'],
			'customer'  => $columns['customer'],
			'products'  => $columns['products'],
			'total'     => $columns['total'],
			'date'      => $columns['date'],
			'shipping'  => __( 'Shipping', 'pokmv' ),
			'status'    => $columns['status'],
		);
		return $edited_columns;
	}

	/**
	 * Set new column content with shipping method
	 *
	 * @param  string $content     Default content.
	 * @param  object $item        Loop item.
	 * @param  string $column_name Columne name.
	 * @return string              Set content.
	 */
	public function insert_shipping_content_to_orders( $content, $item, $column_name ) {
		if ( 'shipping' === $column_name ) {
			$order = wc_get_order( $item->order_id );
			$shipping_methods = $order->get_shipping_methods();

			if ( $shipping_methods ) {
				foreach ( $shipping_methods as $method_item_id => $shipping_object ) {
					$shipping_seller_id = wc_get_order_item_meta( $method_item_id, 'seller_id', true );
					if ( get_current_user_id() === intval( $shipping_seller_id ) ) {
						$content = $shipping_object->get_name();
						break;
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Add shipping option to vendor dashboard setting
	 */
	public function add_vendor_shipping_option() {
		$args = array(
			'vendor_id' => pokmv_get_current_vendor_id(),
		);
		if ( ! is_admin() ) {
			pokmv_get_template_part( 'wc-vendors/front-shipping', $args );
		} else {
			pokmv_get_template_part( 'wc-vendors/admin-shipping', $args );
		}
	}

	/**
	 * Save vendor setting
	 *
	 * @param  integer $vendor_id       Vendor ID.
	 */
	public function save_vendor_setting( $vendor_id ) {
		if ( isset( $_POST['pokmv_vendor'] ) ) {
			$this->save_profile( $vendor_id, $_POST['pokmv_vendor'] );
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
	 * Enqueue WC Vendors specific scripts on front side
	 */
	public function front_enqueue_scripts() {
		global $post;
		if ( has_shortcode( $post->post_content, 'wcv_shop_settings' ) ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-wcvendors', POKMV_PLUGIN_URL . '/assets/css/wcvendors.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-front', POKMV_PLUGIN_URL . '/assets/js/front.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			wp_localize_script(
				'pokmv-front', 'pokmv', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Enqueue WC Vendors specific scripts on admin
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( 'toplevel_page_wcv-vendor-shopsettings' === $screen->id ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-wcvendors', POKMV_PLUGIN_URL . '/assets/css/wcvendors.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-front', POKMV_PLUGIN_URL . '/assets/js/front.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
			wp_localize_script(
				'pokmv-front', 'pokmv', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

}
