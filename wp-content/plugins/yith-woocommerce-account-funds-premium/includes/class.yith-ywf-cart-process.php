<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the class that manage cart feature
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Cart_Process' ) ) {
	/**
	 * Class YITH_YWF_Cart_Process
	 */
	class YITH_YWF_Cart_Process {
		/**
		 * The instance of class
		 *
		 * @var YITH_YWF_Cart_Process
		 */
		protected static $instance;

		/**
		 * YITH_YWF_Cart_Process constructor.
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'include_checkout_script' ) );
			if ( ywf_enable_discount() ) {
				add_filter( 'woocommerce_coupon_message', array( $this, 'get_discount_applied_message' ), 15, 3 );
				add_filter( 'woocommerce_cart_totals_coupon_label', array( $this, 'coupon_label' ) );
				add_filter( 'woocommerce_cart_totals_coupon_html', array( $this, 'coupon_html' ), 10, 2 );
				add_action( 'woocommerce_before_cart', array( $this, 'show_discount_message' ) );
				add_action( 'woocommerce_before_single_product', array( $this, 'show_discount_message' ) );
				add_action( 'woocommerce_before_checkout_form', array( $this, 'show_recharge_message' ) );

				add_action( 'render_block_woocommerce/cart', array( $this, 'show_discount_message_block' ), 10, 2 );
				add_action( 'render_block_woocommerce/store-notices', array(
					$this,
					'show_discount_message_block'
				), 10, 2 );
				add_action( 'render_block_woocommerce/checkout', array( $this, 'show_recharge_message_block' ), 10, 2 );
				if ( defined( 'YITH_YWDPD_PREMIUM' ) ) {
					add_action( 'ywdpd_after_cart_process_discounts', array( $this, 'calculate_totals' ), 10 );
				} else {
					add_action( 'woocommerce_cart_updated', array( $this, 'calculate_totals' ), 99 );
					add_action( 'woocommerce_cart_loaded_from_session', array( $this, 'calculate_totals' ) );
				}
			}

			add_action( 'init', array( $this, 'init_partial_payment' ), 20 );


			add_action( 'woocommerce_review_order_before_order_total', array( $this, 'add_funds_row' ) );

			add_action( 'init', array( $this, 'clear_session' ) );
			add_action( 'woocommerce_review_order_before_order_total', array( $this, 'hide_tax_information' ), 5, 1 );
			add_filter( 'woocommerce_get_cancel_order_url_raw', array(
				$this,
				'add_argument_on_cancel_order_url'
			), 10, 1 );
			add_action( 'woocommerce_order_status_changed', array( $this, 'remove_funds_if_failed' ), 10, 3 );

			add_filter( 'woocommerce_get_coupon_id_from_code', array( $this, 'maybe_create_funds_coupon' ), 20, 2 );
		}


		/**
		 * Return the instance
		 *
		 * @return YITH_YWF_Cart_Process unique access
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Clear session
		 *
		 * @since 1.0.0
		 */
		public function clear_session() {
			if ( isset( $_GET['remove_yith_funds'] ) ) {  // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				yith_account_funds_clear_session();
				foreach ( WC()->payment_gateways()->get_available_payment_gateways() as $gateway ) {
					WC()->session->chosen_payment_method = $gateway->id;
					break;
				}
				wp_safe_redirect( esc_url_raw( remove_query_arg( 'remove_yith_funds' ) ) );
				exit();
			}
		}

		/**
		 * Add cancel order url
		 *
		 * @param string $url The cancel url.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function add_argument_on_cancel_order_url( $url ) {

			$url = esc_url( add_query_arg( array( 'remove_yith_funds' ), $url ) );

			return $url;
		}


		/**
		 * Check if is funds gateway is chosen
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function fund_payment_chosen() {
			$all_payments = WC()->payment_gateways()->get_available_payment_gateways();

			return ( ( isset( $all_payments['yith_funds'] ) && true === $all_payments['yith_funds']->chosen ) || ( isset( $_POST['payment_method'] ) && 'yith_funds' === $_POST['payment_method'] ) );  // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}


		/**
		 * Return current coupon is user pay with funds
		 *
		 * @return string|bool
		 * @since 1.0.0
		 */
		public function get_coupon_code() {
			return isset( WC()->session->yith_fund_coupon ) ? WC()->session->yith_fund_coupon : false;
		}

		/**
		 * Generate the coupon code.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function generate_coupon_code() {
			if ( ! is_null( WC()->session ) ) {
				$coupon_code = sprintf( 'yith_funds_coupon_%s', get_current_user_id() );
				WC()->session->set( 'yith_fund_coupon', $coupon_code );

				return $coupon_code;
			}

			return false;
		}


		/**
		 * Add the discount in cart
		 *
		 * @throws Exception The exception.
		 */
		public function apply_discount_coupon() {
			$coupon_code = $this->get_coupon_code();
			if ( is_null( WC()->cart ) || ! ywf_enable_discount() || ( ! empty( $coupon_code ) && WC()->cart->has_discount( $this->get_coupon_code() ) ) || ! $this->fund_payment_chosen() ) {
				return;
			}

			$cart_total   = WC()->cart->total;
			$current_user = get_current_user_id();

			if ( $current_user ) {

				$customer = new YITH_YWF_Customer( $current_user );
				$funds    = apply_filters( 'yith_show_available_funds', $customer->get_funds() );

				if ( $funds >= $cart_total ) {

					global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

					$coupon_code = $this->generate_coupon_code();

					if ( $YITH_FUNDS->is_wc_2_7 ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName

						$coupon      = new WC_Coupon( $coupon_code );
						$coupon_data = $this->get_shop_coupon_data( array(), $coupon_code );

						$wc_discount = new WC_Discounts( WC()->cart );
						$valid       = $wc_discount->is_coupon_valid( $coupon );
						$valid       = is_wp_error( $valid ) ? false : $valid;
						if ( $valid ) {

							foreach ( $coupon_data as $data_key => $data_value ) {
								if ( is_callable( array( $coupon, 'set_' . $data_key ) ) ) {
									$method = 'set_' . $data_key;
									$coupon->$method( $data_value );

								}
							}
						} else {

							$coupon->read_manual_coupon( $coupon_code, $coupon_data );
						}

						if ( ! $valid || $coupon->get_changes() ) {
							$coupon->save();
						}
					}

					if ( $valid ) {
						WC()->cart->add_discount( $coupon_code );
					}
				}
			}

		}


		/**
		 * Generate the coupon data for user that use funds
		 *
		 * @param array $data The coupon data.
		 * @param string $coupon_code The coupon code.
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_shop_coupon_data( $data, $coupon_code ) {

			if ( strtolower( $coupon_code ) === strtolower( $this->get_coupon_code() ) ) {
				$data = array(
					'discount_type'              => ywf_get_discount_type(),
					'amount'                     => floatval( ywf_get_discount_value() ),
					'individual_use'             => ! defined( 'YITH_YWDPD_PREMIUM' ),
					'product_ids'                => array(),
					'exclude_product_ids'        => array(),
					'usage_limit'                => '',
					'usage_limit_per_user'       => '',
					'limit_usage_to_x_items'     => '',
					'usage_count'                => '',
					'expiry_date'                => '',
					'free_shipping'              => false,
					'product_categories'         => array(),
					'exclude_product_categories' => array(),
					'exclude_sale_items'         => false,
					'minimum_amount'             => '',
					'maximum_amount'             => '',
					'customer_email'             => array(),
				);

				$data = apply_filters( 'yith_funds_coupon_data', $data, $coupon_code );
			}

			return $data;

		}

		/**
		 * Change the "Coupon applied successfully" message to "Discount Applied Successfully"
		 *
		 * @param string $message the message text.
		 * @param string $message_code the message code.
		 * @param WC_Coupon $coupon The coupon.
		 *
		 * @return string the modified messages
		 * @since 1.0
		 */
		public function get_discount_applied_message( $message, $message_code, $coupon ) {
			global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			$coupon_code = $YITH_FUNDS->is_wc_2_7 ? $coupon->get_code() : $coupon->code; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			if ( WC_Coupon::WC_COUPON_SUCCESS === $message_code && $coupon_code === $this->get_coupon_code() ) {
				$message = __( 'Discount applied for having used your account funds!', 'yith-woocommerce-account-funds' );
			}

			return $message;

		}

		/**
		 * Make the label for the coupon look nicer
		 *
		 * @param string $label The coupon label.
		 *
		 * @return string
		 */
		public function coupon_label( $label ) {

			if ( strstr( strtoupper( $label ), strtoupper( 'yith_funds_coupon' ) ) ) {
				$label = esc_html( __( 'Discount', 'yith-woocommerce-account-funds' ) );
			}

			return $label;
		}

		/**
		 * Make the html for the coupon look nicer
		 *
		 * @param string $html The coupon link.
		 * @param WC_Coupon $coupon The coupon.
		 *
		 * @return string
		 */
		public function coupon_html( $html, $coupon ) {
			global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			$coupon_code = $YITH_FUNDS->is_wc_2_7 ? $coupon->get_code() : $coupon->code; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
			if ( $coupon_code === $this->get_coupon_code() ) {
				$html = current( explode( '<a ', $html ) );
			}

			return $html;
		}


		/**
		 * Calculate totals
		 */
		public function calculate_totals() {
			$is_rest = isset( $_REQUEST['context'] ) && 'yith_apply_fund_coupon' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) );
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $is_wc_rest = (  strpos( $request_uri,'/wp-json/wc/store/v1/batch') !== false  || strpos($request_uri,'/wp-json/wc/store/v1/checkout'  ) !== false ) ;

			if ( ! $is_rest && !$is_wc_rest ) {

				if ( $this->fund_payment_chosen() ) {

					$this->apply_discount_coupon();

				} else {
					if ( $this->get_coupon_code() && WC()->cart->has_discount( $this->get_coupon_code() ) ) {
						WC()->cart->remove_coupon( $this->get_coupon_code() );
					}
				}
			}
		}

		/**
		 * Remove the coupon from the cart
		 *
		 * @since 1.0.0
		 */
		public function remove_funds_coupon() {

			if ( 'yes' === WC()->session->ywf_partial_payment ) {

				if ( WC()->cart->has_discount( 'yith_funds_used_data' ) ) {

					$discount_applied = WC()->cart->get_applied_coupons();
					$applied_coupon   = array();

					foreach ( $discount_applied as $key => $code ) {

						if ( 'yith_funds_used_data' === $code ) {
							continue;
						}

						$applied_coupon[] = $code;
					}

					WC()->cart->applied_coupons = $applied_coupon;
				}
			}
		}

		/**
		 * Show the amount fund used for the order
		 *
		 * @param WC_Order $order The order.
		 */
		public function display_used_funds( $order ) {
			$funds_used = $order->get_meta( '_order_funds' );
			if ( 'yith_funds' === $order->payment_method || ! empty( $funds_used ) ) {

				$fund_used_label = apply_filters( 'ywf_display_used_funds', __( 'Funds used', 'yith-woocommerce-account-funds' ) );
				?>
                <tr class="ywf-funds-used">
                    <td class="product-name">
						<?php echo $fund_used_label . '&nbsp;';  //phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>
                    <td class="product-total">
						<?php echo wc_price( $funds_used ); //phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>
                </tr>
				<?php
			}
		}

		/**
		 * Add a row to show the funds used
		 *
		 * @since 1.0.0
		 */
		public function add_funds_row() {

			if ( isset( WC()->session->ywf_partial_payment ) && 'yes' === WC()->session->ywf_partial_payment ) {

				$funds_amount = is_null( WC()->session->ywf_fund_used ) ? 0 : WC()->session->ywf_fund_used;

				if ( $funds_amount > 0 ) {

					?>
                    <tr class="order-discount">
                        <th><?php esc_html_e( 'User funds', 'yith-woocommerce-account-funds' ); ?></th>
                        <td>-<?php echo wc_price( $funds_amount ); //phpcs:ignore WordPress.Security.EscapeOutput ?>
                            <a href="<?php echo esc_url( add_query_arg( 'remove_yith_funds', true, get_permalink( is_cart() ? wc_get_page_id( 'cart' ) : wc_get_page_id( 'checkout' ) ) ) ); ?>"><?php esc_html_e( '[Remove]', 'yith-woocommerce-account-funds' ); ?></a>
                        </td>
                    </tr>
					<?php
				}
			}
		}

		/**
		 * Get the discount message
		 *
		 * @return string
		 */
		public function get_discount_message() {
			$type_discount = ywf_get_discount_type();
			$discount      = apply_filters( 'yith_discount_value', ywf_get_discount_value(), $type_discount );
			$message       = '';
			if ( ! YITH_YWF_Deposit_Fund_Checkout()->is_deposit_in_cart() && apply_filters( 'yith_account_funds_show_discount_message', true ) ) {
				if ( apply_filters( 'ywf_show_discount_message', true ) ) {
					if ( 'fixed_cart' === $type_discount ) {

						$price_label = sprintf( '<strong>%s</strong>', wc_price( $discount ) );
					} else {

						$price_label = sprintf( '<strong>%s</strong>', $discount . '%' );
					}

					$message_1 = _x( 'Pay the order using your account funds and get a', 'Part of: Pay the order using your account funds and get a 50% discount on your cart', 'yith-woocommerce-account-funds' );
					$message_2 = _x( 'discount on your cart', 'Part of: Pay the order using your account funds and get a 50% discount on your cart', 'yith-woocommerce-account-funds' );
					$message   = sprintf( '%s %s %s', $message_1, $price_label, $message_2 );

					$message = apply_filters( 'yith_account_funds_discount_message', $message, $price_label );
				}
			}

			return $message;
		}

		/**
		 * Show customer message
		 *
		 * @since 1.0.0
		 */
		public function show_discount_message() {

			$message = $this->get_discount_message();

			if ( ! empty( $message ) ) {
				wc_add_notice( $message );
			}
		}


		/**
		 * Show discount message in the cart page
		 *
		 * @param string $content The content.
		 * @param array $parsed_block The block args.
		 *
		 * @return string
		 */
		public function show_discount_message_block( $content, $parsed_block ) {
			$message = $this->get_discount_message();

			if ( ! empty( $message ) ) {
				$content = '<div class="woocommerce-message">' . $message . '</div>' . $content;
			}

			return $content;
		}


		/**
		 * Get the recharge message
		 *
		 * @return string
		 */
		public function get_recharge_message() {
			$message = '';
			if ( is_user_logged_in() && ! YITH_YWF_Deposit_Fund_Checkout()->is_deposit_in_cart() && apply_filters( 'yith_account_funds_show_recharge_message', true ) ) {
				$customer_id = get_current_user_id();

				$yith_cust  = new YITH_YWF_Customer( $customer_id );
				$funds      = apply_filters( 'yith_show_available_funds', $yith_cust->get_funds() );
				$cart_total = WC()->cart->total;

				if ( $funds >= 0 && $funds < $cart_total ) {

					$amount_rech = $cart_total - $funds;
					$min         = ywf_get_min_fund_rechargeable();
					$min         = ( '' !== $min ) ? floatval( wc_format_decimal( $min ) ) : 0;
					$max         = ywf_get_max_fund_rechargeable();

					if ( '' === $max || $amount_rech < $max ) {
						if ( $amount_rech < $min ) {

							$amount_rech = $min;
						}
						$url                   = wc_get_page_permalink( 'myaccount' );
						$make_deposit_endpoint = apply_filters( 'ywf_make_deposit_slug', 'make-a-deposit' );
						$endpoint_url          = esc_url( add_query_arg( array( 'amount' => $amount_rech ), wc_get_endpoint_url( $make_deposit_endpoint, '', $url ) ) );
						$button                = sprintf( '<a href="%s" class="button wc-foward">%s</a>', $endpoint_url, __( 'Add a Deposit', 'yith-woocommerce-account-funds' ) );
						$message               = sprintf( '%s %s %s %s ', $button, __( 'Deposit at least', 'yith-woocommerce-account-funds' ), wc_price( $amount_rech ), __( 'to get the available discount', 'yith-woocommerce-account-funds' ) );
					}
				}
			}

			return $message;
		}

		/**
		 * Show the recharge message in the checkout block
		 *
		 * @param string $content The content.
		 * @param array $parsed_block The block args.
		 *
		 * @return string
		 */
		public function show_recharge_message_block( $content, $parsed_block ) {
			$message = $this->get_recharge_message();
			if ( ! empty( $message ) ) {
				$content = '<div class="woocommerce-info">' . $message . '</div>' . $content;
			}

			return $content;
		}

		/**
		 * Show recharge message
		 *
		 * @since 1.0.0
		 */
		public function show_recharge_message() {
			$message = $this->get_recharge_message();
			if ( ! empty( $message ) ) {

				wc_add_notice( $message, 'notice' );
			}
		}

		/**
		 * Support to YITH Dynamic Pricing and Discounts Premium
		 *
		 * @since 1.0.16
		 */
		public function remove_discount() {

			if ( WC()->cart->has_discount( $this->get_coupon_code() ) ) {
				WC()->cart->remove_coupon( $this->get_coupon_code() );

			}
		}

		/**
		 * Check if the coupon is individual use
		 *
		 * @param bool $applied Can be applied.
		 * @param string $coupon_code The coupon code.
		 * @param array $coupons The coupons.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function apply_individual_use_coupon( $applied, $coupon_code, $coupons ) {

			$cart = ! empty( WC()->cart ) ? WC()->cart : false;

			if ( $coupon_code !== $this->get_coupon_code() && ( $cart && $cart->has_discount( $coupon_code ) ) ) {
				$applied = $coupons;
			}

			return $applied;
		}

		/**
		 * Check if can applied with other coupons
		 *
		 * @param bool $skip Apply or not.
		 * @param string|WC_Coupon $coupon_code Coupon code.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function apply_with_individual_use_coupon( $skip, $coupon_code ) {

			global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
			$coupon_code = $YITH_FUNDS->is_wc_2_7 ? $coupon_code->get_code() : $coupon_code->code; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			if ( $coupon_code === $this->get_coupon_code() ) {
				$skip = true;
			}

			return $skip;

		}

		/**
		 * Include checkout script
		 *
		 * @since 1.0.0
		 */
		public function include_checkout_script() {

			if ( ( is_checkout() || is_checkout_pay_page() ) ) {

				wp_enqueue_script( 'ywf-checkout', YITH_FUNDS_ASSETS_URL . '/js/' . yit_load_js_file( 'ywf-checkout.js' ), array( 'jquery' ), YITH_FUNDS_VERSION, true );
			}
		}

		/**
		 * Calculated total
		 *
		 * @param WC_Cart $cart The cart.
		 */
		public function apply_partial_payment( $cart ) {
			$customer_id = get_current_user_id();

			if ( $customer_id && ( WC()->session->get( 'ywf_partial_payment', 'no' ) === 'yes' ) ) {
				$customer_fund       = new YITH_YWF_Customer( $customer_id );
				$funds               = apply_filters( 'yith_show_available_funds', $customer_fund->get_funds() );
				$original_cart_total = $cart->get_total( 'edit' );
				$sub_cart_total      = $cart->get_subtotal() + $cart->get_subtotal_tax();

				$funds_used = min( $sub_cart_total, $funds );

				if ( $funds < $original_cart_total ) {
					$cart->total = $original_cart_total - $funds_used;
				}
				WC()->session->set( 'ywf_fund_used', $funds_used );
				$message = sprintf( '%s', __( 'Your funds have been used successfully. Pay the rest now!', 'yith-woocommerce-account-funds' ) );
				wc_add_notice( $message, 'success', array( 'partial_funds_message' => true ) );
			}

		}

		/**
		 * Check if there is a partial payment, then hide tax information on total
		 *
		 * @since 2.0.0
		 */
		public function hide_tax_information() {

			if ( ! is_null( WC()->session ) && 'yes' === WC()->session->get( 'ywf_partial_payment', 'no' ) ) {
				$style = 'form.checkout tr.order-total td small.includes_tax{display:none;}';
				?>
                <style>
                    <?php echo $style; //phpcs:ignore WordPress.Security.EscapeOutput ?>
                </style>
				<?php
			}

		}

		/**
		 * Check if there is an order with partial payment
		 *
		 * @param int $order_id The order id.
		 * @param WC_Checkout $checkout The checkout.
		 *
		 * @return int
		 */
		public function get_order_awaiting_partial_payment( $order_id, $checkout ) {

			if ( ! is_null( WC()->session ) ) {

				$order_id_with_partial_payment = absint( WC()->session->get( 'ywf_order_awaiting_partial_payment' ) );

				$order = $order_id_with_partial_payment ? wc_get_order( $order_id_with_partial_payment ) : null;

				if ( $order && $order->has_status( array( 'pending', 'failed' ) ) ) {
					$data               = $checkout->get_posted_data();
					$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

					$order_id   = $order_id_with_partial_payment;
					$funds_used = WC()->session->get( 'ywf_fund_used', 0 );
					$cart_total = WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
					$total      = WC()->cart->get_total( 'edit' );

					if ( $funds_used > $cart_total ) {

						$residue        = $funds_used - $cart_total;
						$shipping_total = WC()->cart->shipping_total - $residue;
						$order->set_shipping_total( $shipping_total );
					}

					$order->set_total( $total );
					$order->set_payment_method( isset( $available_gateways[ $data['payment_method'] ] ) ? $available_gateways[ $data['payment_method'] ] : $data['payment_method'] );
					$order->add_meta_data( 'ywf_partial_payment', 'yes', true );
					$order->save();
					do_action( 'woocommerce_checkout_update_order_meta', $order_id, $data );
				}
			}

			return $order_id;
		}

		/**
		 * Show the partial payment button is necessary
		 *
		 * @since 1.3.4
		 */
		public function show_partial_payment_button() {

			if ( ! is_null( WC()->cart ) && is_user_logged_in() && ! is_null( WC()->session ) && ! YITH_YWF_Deposit_Fund_Checkout()->is_deposit_in_cart() ) {

				$customer_id   = get_current_user_id();
				$cart_total    = WC()->cart->get_total( 'edit' );
				$customer_fund = new YITH_YWF_Customer( $customer_id );
				$funds         = apply_filters( 'yith_show_available_funds', $customer_fund->get_funds() );

				if ( $funds > 0 && $cart_total > $funds && ! yith_plugin_fw_is_true( WC()->session->get( 'ywf_partial_payment', 'no' ) ) ) {
					?>
					<?php
					/* translators: %s is the amount of funds */
					$order_button_text = apply_filters( 'ywf_order_button_text', _x( 'Use your funds (%s) or ', 'Use your funds (20$) or Place Order', 'yith-woocommerce-account-funds' ), $funds );
					$order_button_text = sprintf( $order_button_text, wc_price( $funds ) );

					$checkout_url = wc_get_checkout_url();
					$args         = array(
						'partial_payment' => wp_create_nonce( 'ywf-apply-partial-payment' ),
					);
					$checkout_url = esc_url( add_query_arg( $args, $checkout_url ) );
					$button       = sprintf(
						'<a href="%s" id="%s" class="%s">%s</a>',
						$checkout_url,
						'ywf_partial_payment',
						'button',
						$order_button_text
					);

					echo apply_filters( 'yith_account_funds_partial_payment_button_html', $button ); //phpcs:ignore WordPress.Security.EscapeOutput
					?>

					<?php
				}
			}
		}

		/**
		 * Check if the button "Partial Payment" has been clicked
		 *
		 * @since 1.3.4
		 */
		public function start_with_partial_payment() {

			if ( isset( $_GET['partial_payment'] ) ) {

				$action = wp_unslash( $_GET['partial_payment'] );  // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				if ( wp_verify_nonce( $action, 'ywf-apply-partial-payment' ) ) {

					if ( ! yith_plugin_fw_is_true( WC()->session->get( 'ywf_partial_payment', 'no' ) ) ) {
						WC()->session->set( 'ywf_partial_payment', 'yes' );
					}
					wp_safe_redirect( remove_query_arg( 'partial_payment' ) );
					die();
				}
			}
		}

		/**
		 * Remove funds from session if payment fail
		 *
		 * @param string $from Previous order status.
		 * @param string $to Current order status.
		 * @param WC_Order $order The order.
		 */
		public function remove_funds_if_failed( $from, $to, $order ) {

			if ( 'failed' === $to && ( is_checkout() || is_checkout_pay_page() ) ) {

				yith_account_funds_clear_session();
			}
		}

		/**
		 * Hook the action to manage the partial payment
		 *
		 * @return void
		 */
		public function init_partial_payment() {
			if ( ywf_partial_payment_enabled() ) {

				add_action( 'woocommerce_review_order_before_submit', array( $this, 'show_partial_payment_button' ) );
				add_action( 'wp_loaded', array( $this, 'start_with_partial_payment' ), 20 );
				/* PARTIAL PAYMENT*/
				add_action( 'woocommerce_after_calculate_totals', array( $this, 'apply_partial_payment' ), 20 );
			}
		}

		/**
		 * @param int $coupon_id The coupon id.
		 * @param string $coupon_code The coupon code.
		 *
		 * @return int
		 */
		public function maybe_create_funds_coupon( $coupon_id, $coupon_code ) {
			remove_filter( 'woocommerce_get_coupon_id_from_code', array( $this, 'maybe_create_funds_coupon' ), 20 );
			$request_uri      = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$request_to_check = '/wp-json/wc/store/v1/cart/apply-coupon?code=' . $coupon_code;
			$context          = isset ( $_REQUEST['context'] ) && 'yith_apply_fund_coupon' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) );

			// If the coupon doesn't exists and is plugin rest api request
			if ( $context && $coupon_id === 0 && strpos( $request_uri, $request_to_check ) !== false ) {

				$coupon      = new WC_Coupon( $coupon_code );
				$coupon_data = $this->get_shop_coupon_data( array(), $coupon_code );
				$coupon->read_manual_coupon( $coupon_code, $coupon_data );
				$coupon->save();
				$coupon_id = $coupon->get_id();

			}
			add_filter( 'woocommerce_get_coupon_id_from_code', array( $this, 'maybe_create_funds_coupon' ), 20, 2 );

			return $coupon_id;
		}

	}
}

/**
 * Get instance of the class.
 *
 * @return YITH_YWF_Cart_Process
 * @since 1.0.0
 */
function YITH_YWF_Cart_Process() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return YITH_YWF_Cart_Process::get_instance();
}
