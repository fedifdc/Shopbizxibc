<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage all plugin features
 *
 * @class   YITH_Funds
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_Funds' ) ) {
	/**
	 * Class YITH_Funds
	 */
	class YITH_Funds {

		/**
		 * The unique access of the class
		 *
		 * @var YITH_Funds
		 */
		protected static $instance;
		/**
		 * This is the instance of the YITH WooCommerce Panel class.
		 *
		 * @var YIT_Plugin_Panel_WooCommerce
		 */
		protected $panel;
		/**
		 * This is the name of the panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_funds_panel';

		/**
		 * Check the WooCommerce version
		 *
		 * @var bool
		 */
		public $is_wc_2_7;

		/**
		 * YITH_Funds constructor.
		 */
		public function __construct() {

			$this->is_wc_2_7 = version_compare( WC()->version, '2.7.0', '>=' );

			// Load Plugin Framework.
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'load_privacy' ), 20 );
			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_FUNDS_DIR . '/' . basename( YITH_FUNDS_FILE ) ), array(
				$this,
				'action_links'
			) );
			// Add row meta.
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// Add action for register and update plugin.
			add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
			add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );

			// Add YITH FUNDS menu.
			add_action( 'admin_menu', array( $this, 'add_menu' ), 5 );

			// Add admin style and script.
			add_action( 'admin_enqueue_scripts', array( $this, 'include_admin_scripts' ) );

			if ( apply_filters( 'ywf_user_enable_edit_funds', true ) ) {
				// Add deposit column in user table.
				add_action( 'manage_users_columns', array( $this, 'add_user_deposit_column' ) );
				add_action( 'manage_users_custom_column', array( $this, 'show_user_deposit_column' ), 10, 3 );
				add_action( 'yith_account_funds_user_log', array( $this, 'users_log_table' ) );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'include_frontend_scripts' ), 20 );

			// Add custom gateway.
			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway_funds_class' ) );
			add_action( 'widgets_init', array( $this, 'register_ywf_widgets' ) );

			// Add to my-account the new endpoints.
			add_action( 'woocommerce_before_my_account', array( $this, 'show_customer_funds' ) );
			add_action( 'woocommerce_before_my_account', array( $this, 'show_customer_make_deposit_form' ), 20 );
			add_action( 'woocommerce_before_my_account', array( $this, 'show_customer_recent_history' ), 30 );

			// Customer email.
			add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_emails' ) );
			add_action( 'init', array( $this, 'create_deposit_product' ), 15 );
			add_filter( 'product_type_selector', array( $this, 'add_product_type' ) );
			add_filter( 'product_type_options', array( $this, 'add_type_option' ) );

			YITH_Fund_EndPoints();
			YITH_YWF_Cart_Process();
			YITH_YWF_Deposit_Fund_Checkout();
			YWF_Log();
			YITH_YWF_Order();

			add_action( 'init', array( $this, 'fund_compatibility_subscription' ), 5 );
			add_action( 'init', array( $this, 'fund_compatibility' ), 25 );
			// add admin notices.
			add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );

			/*GDPR integration*/

			add_filter( 'wp_privacy_personal_data_exporters', array(
				$this,
				'register_export_account_fund_info'
			), 10, 2 );
			add_filter( 'wp_privacy_personal_data_erasers', array(
				$this,
				'register_eraser_account_fund_info'
			), 10, 2 );

			add_action( 'wp_loaded', array( $this, 'redirect_at_make_a_deposit_page' ), 25 );

			$show_checkbox = get_option( 'ywf_user_privacy', 'no' );

			if ( 'yes' === $show_checkbox ) {

				add_action( 'woocommerce_edit_account_form', array( $this, 'show_checkbox' ), 15 );
				add_action( 'woocommerce_save_account_details', array( $this, 'register_customer_choose' ), 20 );
			}

			add_filter( 'yith_plugin_fw_panel_wc_extra_row_classes', array( $this, 'add_extra_classes' ), 10, 2 );

		}

		/**
		 * Create or return the instance
		 *
		 * @return YITH_Funds
		 * @since  1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Load the YITH plugin Framework
		 *
		 * @since  1.0.0
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Action Links.
		 * Add the action links to plugin admin page.
		 *
		 * @param array $links array with plugin links.
		 *
		 * @return array
		 * @since    1.0
		 */
		public function action_links( $links ) {

			$links = yith_add_action_links( $links, $this->panel_page, true, YITH_FUNDS_SLUG );

			return $links;
		}

		/**
		 * Plugin_row_meta.
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array  $new_row_meta_args The new plugin meta.
		 * @param array  $plugin_meta The plugin meta.
		 * @param string $plugin_file The filename of plugin.
		 * @param array  $plugin_data The plugin data.
		 * @param string $status The plugin status.
		 * @param string $init_file The filename of plugin.
		 *
		 * @return   array
		 * @since    1.0
		 * @use plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_FUNDS_INIT' ) {

			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug']       = YITH_FUNDS_SLUG;
				$new_row_meta_args['is_premium'] = true;
			}

			return $new_row_meta_args;
		}

		/**
		 * Add a panel under YITH Plugins tab.
		 *
		 * @return   void
		 * @since    1.0
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function add_menu() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = apply_filters(
				'yith_funds_add_tab',
				array(
					'general-settings'   => __( 'Settings', 'yith-woocommerce-account-funds' ),
					'user-log-settings'  => __( 'Users\' funds', 'yith-woocommerce-account-funds' ),
					'emails-multi-tab'   => __( 'Email settings', 'yith-woocommerce-account-funds' ),
					'endpoints-settings' => __( 'Funds endpoints', 'yith-woocommerce-account-funds' ),
					'vendor-multi-tab'   => __( 'Vendors & Funds', 'yith-woocommerce-account-funds' ),
					'privacy-settings'   => __( 'Account & Privacy', 'yith-woocommerce-account-funds' ),
				)
			);

			$args = array(
				'create_menu_page'   => true,
				'parent_slug'        => '',
				'page_title'         => 'YITH WooCommerce Account Funds',
				'plugin_description' => __( 'Let customers deposit funds in their online wallet in your store and use them at any time to proceed with faster checkout', 'yith-woocommerce-account-funds' ),
				'menu_title'         => 'Account Funds',
				'capability'         => apply_filters( 'yith_funds_menu_capability', 'manage_options' ),
				'parent'             => '',
				'parent_page'        => 'yith_plugin_panel',
				'class'              => yith_set_wrapper_class(),
				'page'               => $this->panel_page,
				'admin-tabs'         => $admin_tabs,
				'options-path'       => YITH_FUNDS_DIR . '/plugin-options',
				'plugin_slug'        => YITH_FUNDS_SLUG,
				'is_premium'         => true,
			);

			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once YITH_FUNDS_DIR . 'plugin-fw/lib/yith-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Register plugins for activation tab.
		 *
		 * @since  1.0.0
		 */
		public function register_plugin_for_activation() {
			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once YITH_FUNDS_DIR . 'plugin-fw/licence/lib/yit-licence.php';
				require_once YITH_FUNDS_DIR . 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}
			YIT_Plugin_Licence()->register( YITH_FUNDS_INIT, YITH_FUNDS_SECRET_KEY, YITH_FUNDS_SLUG );
		}

		/**
		 * Register plugins for update tab.
		 *
		 * @since  1.0.0
		 */
		public function register_plugin_for_updates() {
			if ( ! class_exists( 'YIT_Upgrade' ) ) {
				require_once YITH_FUNDS_DIR . 'plugin-fw/lib/yit-upgrade.php';
			}
			YIT_Upgrade()->register( YITH_FUNDS_SLUG, YITH_FUNDS_INIT );
		}

		/**
		 * Add custom gateway
		 *
		 * @param array $methods The gateways.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_gateway_funds_class( $methods ) {

			$methods[] = 'WC_Gateway_YITH_Funds';

			return $methods;
		}


		/**
		 *
		 * If current endpoint is make-a-deposit, load checkout scripts
		 *
		 * @param bool $is_checkout Is checkout page.
		 *
		 * @since  1.0.7
		 */
		public function load_script_checkout( $is_checkout ) {

			if ( ywf_is_make_deposit() ) {
				return true;
			}

			return $is_checkout;
		}


		/**
		 * Check if user profile is complete
		 *
		 * @return bool
		 * @since  1.0.0
		 */
		public function check_user_profile() {

			$billing_country         = WC()->customer->get_billing_country();
			$customer_country_fields = WC()->countries->get_address_fields( $billing_country );
			$user_id                 = get_current_user_id();
			/**
			 * The user
			 *
			 * @var WP_User $user
			 */
			$user = get_user_by( 'id', $user_id );

			foreach ( $customer_country_fields as $key => $value ) {

				if ( isset( $value['required'] ) && $value['required'] ) {

					$current_field = $user->get( $key );

					if ( empty( $current_field ) ) {
						return false;
					}
				}
			}

			return true;
		}


		/**
		 * Add deposit column in user table
		 *
		 * @param array $columns The wp table list columns.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_user_deposit_column( $columns ) {

			$columns['user_deposit'] = __( 'Deposit', 'yith-woocommerce-account-funds' );

			return $columns;
		}

		/**
		 * Show user deposit in user table
		 *
		 * @param string $value The value to show.
		 * @param string $column_name The column name.
		 * @param int    $user_id The user id.
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function show_user_deposit_column( $value, $column_name, $user_id ) {

			if ( 'user_deposit' === $column_name ) {

				$customer = new YITH_YWF_Customer( $user_id );
				$funds    = apply_filters( 'yith_admin_user_deposit_column', $customer->get_funds() );
				$value    = wc_price( $funds );

				$show_log_params = array(
					'page'    => 'yith_funds_panel',
					'user_id' => $user_id,
					'tab'     => 'user-log-settings',
					'action'  => 'update',
				);

				$show_log_link       = esc_url( add_query_arg( $show_log_params, admin_url( 'admin.php' ) ) );
				$actions['show_log'] = sprintf( '<a href="%s">%s</a>', $show_log_link, __( 'Show logs', 'yith-woocommerce-account-funds' ) );

				$value .= $this->row_actions( $actions );
			}

			return $value;
		}

		/**
		 * Show the row action in user fund table
		 *
		 * @param array $actions The actions.
		 * @param bool  $always_visible If is visible.
		 *
		 * @return string
		 */
		public function row_actions( $actions, $always_visible = false ) {
			$action_count = count( $actions );
			$i            = 0;

			if ( ! $action_count ) {
				return '';
			}

			$out = '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';
			foreach ( $actions as $action => $link ) {
				++ $i;
				$sep = ( $i === $action_count ) ? '' : ' | ';
				$out = $out . "<span class='$action'>$link$sep</span>";
			}
			$out .= '</div>';

			$out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details' ) . '</span></button>';

			return $out;
		}

		/**
		 * Register the widgets
		 *
		 * @since  1.0.0
		 */
		public function register_ywf_widgets() {
			require_once 'includes/widgets/class.yith-ywf-make-a-deposit-widget.php';
			require_once 'includes/widgets/class.yith-ywf-view-user-funds-widget.php';
			register_widget( 'YITH_YWF_Make_a_Deposit_Widget' );
			register_widget( 'YITH_YWF_View_User_Funds_Widget' );
		}


		/**
		 * Include admin style and script
		 *
		 * @since  1.0.0
		 */
		public function include_admin_scripts() {

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			if ( isset( $_GET['page'] ) && 'yith_funds_panel' === wp_unslash( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$is_customize_active = defined( 'YITH_WCMAP_PREMIUM' ) && YITH_WCMAP_PREMIUM;
				wp_enqueue_script( 'ywf_admin_script', YITH_FUNDS_ASSETS_URL . 'js/ywf-admin' . $suffix . '.js', array( 'jquery' ), YITH_FUNDS_VERSION, true );

				$params = array(
					'is_customize_active' => $is_customize_active,
					'wc_currency'         => get_woocommerce_currency_symbol(),
				);
				wp_localize_script( 'ywf_admin_script', 'ywf_admin', $params );

				wp_enqueue_style( 'ywf_admin_style', YITH_FUNDS_ASSETS_URL . 'css/ywf_backend.css', array(), YITH_FUNDS_VERSION );
			}

			if ( ( isset( $_GET['post'] ) && get_post_type( wp_unslash( $_GET['post'] ) ) === 'shop_order' ) || ( isset( $_GET['page'], $_GET['action'] ) && 'wc-orders' === $_GET['page'] && 'edit' === $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				wp_enqueue_script( 'ywf_order_admin_script', YITH_FUNDS_ASSETS_URL . 'js/ywf-order-admin' . $suffix . '.js', array( 'jquery' ), YITH_FUNDS_VERSION, true );

				$params = array(
					'ajax_url' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
					'messages' => array(
						'tot_av_refund_tip'    => __( 'You cannot refund an amount greater than user\'s total funds available.', 'yith-woocommerce-account-funds' ),
						'error_message_refund' => __( 'Attention! User\'s current funds are lower than the amount you are entering', 'yith-woocommerce-account-funds' ),
						'no_automatic_refund'  => __( 'Attention! It is not possible to refund this order automatically because it was partially paid with funds. Only the manual refund option is available.', 'yith-woocommerce-account-funds' ),
						'error_wrong_funds'    => __( 'Attention! It is not possible to set a value < 0', 'yith-woocommerce-account-funds' ),
					),
					'actions'  => array(
						'add_funds' => 'add_funds',
					),
				);
				wp_localize_script( 'ywf_order_admin_script', 'ywf_params', $params );
			}
		}

		/**
		 * Include style and script
		 *
		 * @since  1.0.0
		 */
		public function include_frontend_scripts() {

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_enqueue_style( 'ywf_style', YITH_FUNDS_ASSETS_URL . 'css/ywf_frontend.css', array(), YITH_FUNDS_VERSION );
			wp_enqueue_script( 'ywf_script', YITH_FUNDS_ASSETS_URL . 'js/ywf-frontend' . $suffix . '.js', array( 'jquery' ), YITH_FUNDS_VERSION, true );

			global $post;
			$gateway = get_option( 'ywf_select_gateway', array() );
			$gateway = ! is_array( $gateway ) ? array() : $gateway;
			if ( has_block( 'woocommerce/checkout', $post ) && count( $gateway ) > 0 ) {
				$deps = include YITH_FUNDS_DIR . 'assets/js/wc-blocks/available-gateways/index.asset.php';

				wp_enqueue_script(
					'yith-funds-available-gateway',
					YITH_FUNDS_ASSETS_URL . 'js/wc-blocks/available-gateways/index.js',
					$deps['dependencies'],
					$deps['version'],
					true
				);

				wp_localize_script( 'yith-funds-available-gateway', 'yith_allowed_gateway_args', array(
					'gateways'           => $gateway,
					'deposit_product_id' => get_option( '_ywf_deposit_id' ),
					'all_gateways'       => array_keys( WC()->payment_gateways()->get_available_payment_gateways() )
				) );
			}
		}

		/**
		 * Show the available funds for a customer
		 *
		 * @since  1.0.0
		 */
		public function show_customer_funds() {

			echo do_shortcode( '[yith_ywf_show_user_fund]' );  //phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Show make a deposit form
		 *
		 * @since  1.0.0
		 */
		public function show_customer_make_deposit_form() {
			if ( apply_filters( 'ywf_prevent_customer_add_funds', false ) ) {
				return;
			}
			echo do_shortcode( '[yith_ywf_make_a_deposit_form]' ); //phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Show fund history
		 *
		 * @since  1.0.0
		 */
		public function show_customer_recent_history() {

			if ( YWF_Log()->count_log() > 0 ) {
				$endpoint_slug         = yith_account_funds_get_endpoint_slug( 'view-history' );
				$endpoint_url          = esc_url( wc_get_endpoint_url( $endpoint_slug ) );
				$title                 = sprintf( '<h2>%s</h2><span class="ywf_show_all_history"><a href="%s">(%s)</a></span>', __( 'Recent history', 'yith-woocommerce-account-funds' ), $endpoint_url, __( 'Show all', 'yith-woocommerce-account-funds' ) );
				$query_args['limit']   = 5;
				$query_args['offset']  = 0;
				$query_args['user_id'] = get_current_user_id();

				$additional_params = array(
					'user_log_items'   => YWF_Log()->get_log( $query_args ),
					'page_links'       => false,
					'show_filter_form' => false,
					'show_total'       => false,
				);

				$additional_params['atts'] = $additional_params;

				echo $title; //phpcs:ignore WordPress.Security.EscapeOutput
				wc_get_template( 'view-deposit-history.php', $additional_params, '', YITH_FUNDS_TEMPLATE_PATH );
			}
		}

		/**
		 * Add custom email class
		 *
		 * @param array $emails The WooCommerce emails.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_woocommerce_emails( $emails ) {

			$emails['YITH_YWF_Customer_Email']        = include YITH_FUNDS_INC . 'emails/class.yith-ywf-customer-email.php';
			$emails['YITH_YWF_Advise_Customer_Email'] = include YITH_FUNDS_INC . 'emails/class.yith-ywf-advise-customer-email.php';
			$emails['YITH_YWF_Vendor_Redeem_Email']   = include YITH_FUNDS_INC . 'emails/class.yith-ywf-vendor-redeem-email.php';

			return $emails;
		}

		/**
		 * Show the users log table in admin
		 *
		 * @since  1.0.0
		 */
		public function users_log_table() {

			require_once YITH_FUNDS_TEMPLATE_PATH . 'admin/user-log-table.php';

		}


		/**
		 * Initialize compatibility class
		 *
		 * @since  1.0.1
		 */
		public function fund_compatibility() {
			YITH_FUNDS_Compatibility();
		}

		/**
		 * Add the integration with YITH WooCommerce Subscription plugin.
		 *
		 * @since  1.1.2
		 */
		public function fund_compatibility_subscription() {
			/**YITH WooCommerce Subscription*/
			if ( defined( 'YITH_YWSBS_PREMIUM' ) && version_compare( YITH_YWSBS_VERSION, '1.5.2', '>' ) ) {
				require_once YITH_FUNDS_INC . 'compatibility/yith-woocommerce-subscription/class.yith-funds-yith-subscription-integration.php';
				require_once YITH_FUNDS_INC . 'compatibility/yith-woocommerce-subscription/class.yith-funds-yith-subscription.php';
				YITH_Funds_YITH_Subscription_Integration();
			}
		}

		/**
		 * Show a notice in admin if YITH Customize My Account plugin is active
		 *
		 * @since  1.0.0
		 */
		public function show_admin_notices() {

			$is_customize_active = defined( 'YITH_WCMAP_PREMIUM' ) && YITH_WCMAP_PREMIUM;
			if ( ( isset( $_GET['page'] ) && 'yith_funds_panel' === $_GET['page'] ) && ( isset( $_GET['tab'] ) && 'endpoints-settings' === $_GET['tab'] ) && $is_customize_active ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$message   = __( 'Customize My Account Page is activated, you can change the YITH Account Funds Endpoints here', 'yith-woocommerce-account-funds' );
				$admin_url = admin_url( 'admin.php' );
				$args      = array(
					'page' => 'yith_wcmap_panel',
					'tab'  => 'endpoints',
				);
				$page_url  = esc_url( add_query_arg( $args, $admin_url ) );
				$message   = sprintf( '%1$s <a href="%2$s">%2$s</a>', $message, $page_url );
				?>

                <div class="notice notice-info" style="padding-right: 38px;position: relative;">
                    <p><?php echo $message; //phpcs:ignore WordPress.Security.EscapeOutput ?></p>
                </div>

				<?php
			}
		}

		/**
		 * Create the default deposit product
		 *
		 * @since  1.0.0
		 */
		public function create_deposit_product() {

			$deposit_id      = get_option( '_ywf_deposit_id', - 1 );
			$deposit_product = wc_get_product( $deposit_id );

			if ( - 1 === intval( $deposit_id ) || ! $deposit_product ) {

				$deposit_id = wp_insert_post(
					array(
						'post_title'   => __( 'YITH Deposit', 'yith-woocommerce-account-funds' ),
						'post_type'    => 'product',
						'post_status'  => 'private',
						'post_content' => __( 'This product has been created by YITH Account Funds Plugin, please not remove', 'yith-woocommerce-account-funds' ),
					)
				);

				wp_set_object_terms( $deposit_id, 'ywf_deposit', 'product_type' );

				$product = wc_get_product( $deposit_id );

				$product->set_sold_individually( 'yes' );
				$product->set_virtual( 'yes' );
				$product->set_catalog_visibility( 'hidden' );
				$product->save();
				update_option( '_ywf_deposit_id', $deposit_id );
			}

		}

		/**
		 * Add the custom product type
		 *
		 * @param array $product_type The supported product type.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_product_type( $product_type ) {

			$product_type['ywf_deposit'] = __( 'Deposit', 'yith-woocommerce-account-funds' );

			return $product_type;
		}

		/**
		 * Add in the product metabox a custom class
		 *
		 * @param array $array The metabox classes.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_type_option( $array ) {
			if ( isset( $array['virtual'] ) ) {
				$css_class     = $array['virtual']['wrapper_class'];
				$add_css_class = 'show_if_ywf_deposit';
				$class         = empty( $css_class ) ? $add_css_class : $css_class .= ' ' . $add_css_class;

				$array['virtual']['wrapper_class'] = $class;
			}
			if ( isset( $array['downloadable'] ) ) {
				$css_class     = $array['downloadable']['wrapper_class'];
				$add_css_class = 'show_if_ywf_deposit';
				$class         = empty( $css_class ) ? $add_css_class : $css_class .= ' ' . $add_css_class;

				$array['downloadable']['wrapper_class'] = $class;
			}

			return $array;
		}

		/**
		 * Register export action
		 *
		 * @param array $exporters The GDPR exporters.
		 *
		 * @return array
		 * @since  1.0.22
		 */
		public function register_export_account_fund_info( $exporters ) {

			$exporters['ywsfl-export-list'] = array(
				'exporter_friendly_name' => __( 'Account Funds Info', 'yith-woocommerce-account-funds' ),
				'callback'               => array(
					$this,
					'export_account_fund_info',
				),
			);

			return $exporters;
		}

		/**
		 * Export account fund info
		 *
		 * @param string $email_address The user email.
		 * @param int    $page The current page number.
		 *
		 * @return array
		 * @since  1.0.22
		 *
		 */
		public function export_account_fund_info( $email_address, $page = 1 ) {
			$data_to_export = array();

			$user = get_user_by( 'email', $email_address );

			$account_info = array();
			if ( $user instanceof WP_User ) {

				$user_id = $user->ID;

				global $wpdb;

				$table_name = $wpdb->prefix . 'ywf_user_fund_log';
				$query      = $wpdb->prepare( "SELECT type_operation, COUNT(*) as total_op, SUM( ABS( fund_user ) ) as total FROM {$table_name} WHERE user_id = %d GROUP BY type_operation", $user_id ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

				$list = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

				if ( count( $list ) > 0 ) {
					$type_operation = ywf_get_operation_type();

					foreach ( $list as $data ) {

						$type_operation_name = isset( $type_operation[ $data['type_operation'] ] ) ? $type_operation[ $data['type_operation'] ] : 'N/A';
						$total_info          = __( 'for a total of ', 'yith-woocommerce-account_funds' );
						$value               = sprintf( '%d x %s %s %s', $data['total_op'], _n( $type_operation_name, $type_operation_name . 's', $data['total_op'], 'yith-woocommerce-account-funds' ), $total_info, wc_price( $data['total'] ) ); // phpcs:ignore
						$account_info[]      = array(
							'name'  => $type_operation_name,
							'value' => $value,
						);

					}
				}

				$available_funds = get_user_meta( $user_id, '_customer_fund', true );

				$account_info[] = array(
					'name'  => __( 'Available fund', 'yith-woocommerce-account-funds' ),
					'value' => wc_price( $available_funds ),
				);

			}
			$data_to_export[] = array(
				'group_id'    => 'ywf_fund_info',
				'group_label' => __( 'Account Funds Log', 'yith-woocommerce-account-funds' ),
				'data'        => $account_info,
				'item_id'     => 'account_fund_id',
			);

			return array(
				'data' => $data_to_export,
				'done' => true,
			);

		}

		/**
		 * Register the eraser fund information
		 *
		 * @param array $erasers The erasers.
		 *
		 * @return array
		 * @since  1.0.22
		 */
		public function register_eraser_account_fund_info( $erasers ) {

			$erasers['ywsfl-export-list'] = array(
				'eraser_friendly_name' => __( 'Account funds', 'yith-woocommerce-account-funds' ),
				'callback'             => array(
					$this,
					'eraser_account_fund_info',
				),
			);

			return $erasers;

		}


		/**
		 * Erase the info
		 *
		 * @param string $email_address The user email.
		 * @param int    $page The current page id.
		 *
		 * @return array
		 * @since  1.0.22
		 */
		public function eraser_account_fund_info( $email_address, $page = 1 ) {

			$response = array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);

			$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

			if ( ! $user instanceof WP_User ) {
				return $response;
			}

			$user_id = $user->ID;

			global $wpdb;

			$deleted = $wpdb->delete( $wpdb->prefix . 'ywf_user_fund_log', array( 'user_id' => $user_id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			if ( $deleted > 0 ) {
				$response['items_removed'] = true;
				$response['messages'][]    = sprintf( '%d %s', $deleted, _n( 'Item removed from Account Fund log', 'Items removed from Account Fund log', $deleted, 'yith-woocommerce-account-funds' ) );
			}

			delete_user_meta( $user_id, '_customer_fund' );

			$response['messages'][] = __( 'Removed user meta _customer_fund properly', 'yith-woocommerce-account-funds' );

			return $response;
		}

		/**
		 * If a customer set a value in the form, will be redirect in the deposti page
		 *
		 * @since  1.0.0
		 */
		public function redirect_at_make_a_deposit_page() {

			if ( isset( $_POST['make_a_deposit_form'] ) && wp_verify_nonce( wp_unslash( $_POST['make_a_deposit_form'] ), 'make_a_deposit_form' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$make_a_deposit_url = wc_get_endpoint_url( yith_account_funds_get_endpoint_slug( 'make-a-deposit' ), '', wc_get_page_permalink( 'myaccount' ) );

				$amount = isset( $_POST['amount'] ) ? wp_unslash( $_POST['amount'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$lang   = isset( $_POST['lang'] ) ? wp_unslash( $_POST['lang'] ) : '';     // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$query_args = array(
					'amount' => $amount,
				);

				if ( ! empty( $lang ) ) {
					$query_args['lang'] = $lang;
				}

				$make_a_deposit_url = esc_url_raw( add_query_arg( $query_args, $make_a_deposit_url ) );

				wp_safe_redirect( $make_a_deposit_url );
				exit();
			}
		}

		/**
		 * Add a checkbox in my account
		 *
		 * @since  YITH
		 */
		public function show_checkbox() {

			wc_get_template( 'ywf-email-checkbox-agree.php', array(), '', YITH_FUNDS_TEMPLATE_PATH . 'woocommerce/myaccount/' );
		}

		/**
		 * Save the customer choose
		 *
		 * @param int $user_id The user d.
		 *
		 */
		public function register_customer_choose( $user_id ) {

			$agree_choose = isset( $_POST['ywf_agree_send_email'] ); // phpcs:ignore

			update_user_meta( $user_id, '_ywf_agree_to_send_email', $agree_choose );
		}

		/**
		 * Show the privacy policy
		 *
		 * @since  1.0.0
		 */
		public function load_privacy() {
			require_once YITH_FUNDS_INC . '/class.yith-ywf-privacy-policy.php';
		}

		/**
		 * Add a custom class in plugin fields
		 *
		 * @param array $classes The classes.
		 * @param array $field The field.
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function add_extra_classes( $classes, $field ) {

			$vendor_field_ids = array(
				'ywf_vendor_can_charge',
				'ywf_vendor_can_use',
				'ywf_vendor_can_redeem',
				'ywf_min_fund_needs',
				'ywf_max_fund_redeem',
				'ywf_redeeming_gateway',
				'ywf_redeeming_payment_type',
				'ywf_redeeming_day',
				'ywf_enable_manual_redeeming',
				'ywf_redeeming_button_label',
				'ywf_redeeming_button_text_color',
				'ywf_redeeming_button_background_color',
				'ywf_redeem_email_enabled',
				'ywf_redeem_email_recipient',
				'ywf_redeem_email_subject',
				'ywf_redeem_email_heading',
				'ywf_redeem_email_type',
			);

			if ( in_array( $field['id'], $vendor_field_ids, true ) ) {

				if ( ! defined( 'YITH_WPV_PREMIUM' ) || ( defined( 'YITH_WPV_PREMIUM' ) && defined( 'YITH_WPV_VERSION' ) && version_compare( YITH_WPV_VERSION, '3.7.0', '<' ) ) ) {

					$classes[] = 'yith-disabled';
				}
			}

			return $classes;
		}


	}
}

