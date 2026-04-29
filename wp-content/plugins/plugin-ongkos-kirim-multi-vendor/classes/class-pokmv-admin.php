<?php

/**
 * Admin class
 */
class POKMV_Admin {

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

		add_filter( 'pok_setting_defaults', array( $this, 'setting_defaults' ) );
		if ( version_compare( POK_VERSION, '3.4.2', '<=' ) ) {
			add_action( 'pok_setting_miscellaneous', array( $this, 'setting_html_legacy' ) );
			add_filter( 'pok_setting_tabs', array( $this, 'register_vendor_tab' ) );
		} else {
			add_action( 'pok_setting_sections', array( $this, 'setting_html' ) );
			add_filter( 'pok_admin_tabs', array( $this, 'register_vendor_tab' ) );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
		add_action( 'pok_admin_handle_action', array( $this, 'handle_actions' ), 10, 2 );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_admin', $this );
	}

	/**
	 * Set seting defaults
	 *
	 * @param  array $defaults Setting defaults.
	 * @return array           Setting defaults.
	 */
	public function setting_defaults( $defaults ) {
		$defaults['enable_multi_vendor']    = 'no';
		// $defaults['allowed_vendor_setting'] = array( 'store_location', 'couriers', 'specific_service' );
		return $defaults;
	}

	/**
	 * Generate setting html
	 *
	 * @param  array $sections Setting sections.
	 */
	public function setting_html( $sections ) {
		$sections['vendors']['template'] = POKMV_PLUGIN_PATH . 'templates/admin/setting.php';
		return $sections;
	}

	/**
	 * Generate setting html (legacy)
	 * For Plugin Ongkos Kirim 3.4.2 or below
	 *
	 * @param  array $settings Setting values.
	 */
	public function setting_html_legacy( $settings ) {
		?>
			</div>
		</div>
		<div class="setting-section" id="multi-vendor">
			<h4 class="section-title">
				<?php esc_html_e( 'Multi Vendor', 'pokmv' ); ?>
			</h4>
			<div class="setting-table">
		<?php
		include_once POKMV_PLUGIN_PATH . 'templates/admin/setting.php';
	}

	/**
	 * Register vendor tab
	 *
	 * @param  array $tabs Admin tabs.
	 */
	public function register_vendor_tab( $tabs ) {
		if ( 'yes' === $this->setting->get('enable_multi_vendor') ) {
			$tabs = array_slice( $tabs, 0, 1, true ) +
				array(
					'vendor' => array(
						'label'     => __( 'Vendors', 'pokmv' ),
						'callback'  => array( $this, 'render_subpage_setting_vendors' ),
					),
				) +
				array_slice( $tabs, 1, count( $tabs ) - 1, true );
			$tabs['custom']['callback'] = array( $this, 'render_subpage_setting_custom' );
		}
		return $tabs;
	}

	public function render_subpage_setting_vendors() {
		require_once POKMV_PLUGIN_PATH . 'classes/class-pokmv-vendor-table.php';
		$table = new POKMV_Vendor_Table();
		$table->output_table();
	}

	public function render_subpage_setting_custom() {
		?>
		<div class="pok-setting-notice">
			<p><?php esc_html_e( 'Sorry, this feature are unavailable on multi-vendor mode', 'pkmv' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Handle admin actions
	 *
	 * @param  string    $action    Action type.
	 * @param  POK_Admin $pok_admin POK Admin Class.
	 */
	public function handle_actions( $action, $pok_admin ) {
		// update setting.
		if ( wp_verify_nonce( $action, 'update_vendor_setting' ) && isset( $_GET['edit'] ) ) { // Input var okay.
			$vendor_id = intval( $_GET['edit'] ); // Input var okay.
			if ( isset( $_POST['pokmv_vendor'] ) && is_array( $_POST['pokmv_vendor'] ) ) { // Input var okay.
				$new = wp_unslash( $_POST['pokmv_vendor'] ); // WPCS: Input var okay, CSRF ok, sanitization ok.
				$setting = pokmv_get_vendor( $vendor_id );
				if ( isset( $new['origin'] ) ) {
					$setting->set( 'origin', $new['origin'] );
				}
				$setting->set( 'courier', isset( $new['courier'] ) ? $new['courier'] : array() );
				if ( isset( $new['specific_service'] ) ) {
					$setting->set( 'specific_service', $new['specific_service'] );
				}
				$setting->set( 'specific_service_option', isset( $new['specific_service_option'] ) ? $new['specific_service_option'] : array() );
			}
			$pok_admin->add_notice( sprintf( __( 'Setting for %s has been updated', 'pokmv' ), pokmv_get_vendor_store_name( $vendor_id ) ) );
			wp_safe_redirect( admin_url( 'admin.php?page=pok_setting&tab=vendor' ) );
			die;
		}
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'ongkos-kirim_page_pok_setting' === $screen->id ) {
			wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array(), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'pok-admin' ), POKMV_VERSION, true );
		}
	}

}
