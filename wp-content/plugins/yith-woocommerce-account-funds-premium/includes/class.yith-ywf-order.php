<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage order features
 *
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Order' ) ) {
	/**
	 * Class YITH_YWF_Order
	 */
	class YITH_YWF_Order {
		/**
		 * YITH_YWF_Order constructor.
		 */
		public function __construct() {

			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ), 10, 2 );
			add_action( 'woocommerce_store_api_checkout_update_order_from_request', array(
				$this,
				'api_update_order_meta'
			), 10, 2 );
			add_action( 'woocommerce_order_status_changed', array( $this, 'manage_order_funds' ), 10, 3 );
			add_action( 'woocommerce_before_pay_action', array( $this, 'fix_total_with_partial_payment' ), 20 );
			add_action( 'woocommerce_admin_order_totals_after_tax', array(
				$this,
				'woocommerce_admin_order_totals_show_user_funds'
			) );
			add_action( 'woocommerce_admin_order_totals_after_total', array(
				$this,
				'woocommerce_admin_order_totals_user_funds_available'
			) );
			add_filter( 'woocommerce_get_order_item_totals', array( $this, 'get_order_fund_item_total' ), 10, 2 );
			add_action( 'woocommerce_create_refund', array( $this, 'check_if_valid_refund' ), 20, 2 );
			add_action( 'woocommerce_refund_deleted', array( $this, 'refund_deleted_order_funds' ), 10, 2 );
			add_filter( 'woocommerce_order_get_total', array( $this, 'show_order_total_include_funds' ), 20, 2 );
			// update order deposit meta.
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_deposit_meta' ), 10, 2 );
			add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this, 'update_order_deposit_meta' ), 10, 2 );

			// order again.
			add_action( 'woocommerce_order_details_after_order_table', array( $this, 'deposit_again' ), 5, 1 );

			add_filter( 'woocommerce_ajax_calc_line_taxes', array( $this, 'remove_deposit_from_items' ), 10, 3 );
			if ( is_admin() ) {
				$has_hpos = get_option( 'woocommerce_custom_orders_table_enabled', 'no' );
				if ( 'yes' === $has_hpos ) {
					add_filter( 'views_woocommerce_page_wc-orders', array( $this, 'add_order_deposit_view' ) );
					add_filter( 'woocommerce_order_list_table_prepare_items_query_args', array(
						$this,
						'add_order_query_args'
					) );
				} else {
					add_filter( 'views_edit-shop_order', array( $this, 'add_order_deposit_view' ) );
					add_action( 'pre_get_posts', array( $this, 'filter_order_deposit_for_view' ) );
				}

				add_action( 'woocommerce_admin_order_totals_after_total', array(
					$this,
					'add_order_custom_field'
				), 20, 1 );
				add_action( 'add_meta_boxes', array( $this, 'add_order_customer_funds_metabox' ), 10, 2 );
				add_action( 'wp_ajax_add_funds', array( $this, 'refund_funds_in_partial_payments' ) );

			}

		}


		/**
		 * Add or remove funds from customer balance when the order change the status
		 *
		 * @param int $order_id The order id.
		 * @param string $old_status The old order status.
		 * @param string $new_status The new status.
		 *
		 * @since 1.0.0
		 *
		 * @author YITH <plugins@yithemes.com>
		 */
		public function manage_order_funds( $order_id, $old_status, $new_status ) {
			yith_account_funds_clear_session();

			$order = wc_get_order( $order_id );
			if ( apply_filters( 'ywf_manage_order_funds', false, $order_id ) || ! wp_get_post_parent_id( $order_id ) ) {
				if ( ywf_order_has_deposit( $order ) ) {

					switch ( $new_status ) {

						case 'completed':
							$this->add_deposit_order( $order );
							break;
					}
				} else {

					$funds_order = $order->get_meta( '_order_funds' );

					$funds_order_remove = $order->get_meta( '_order_fund_removed' );

					if ( ! empty( $funds_order ) ) {
						switch ( $new_status ) {

							case 'completed':
							case 'processing':
							case 'pending':
							case 'on-hold':
								$this->add_fund_order( $order, $funds_order_remove, $funds_order );
								break;
							case 'cancelled':
								$this->remove_fund_order( $order, $funds_order_remove, $funds_order );
								break;
						}
					}
				}
			}
		}


		/**
		 * Add order fund and decrement user fund
		 *
		 * @param WC_Order $order The order.
		 * @param bool $has_removed Funds already removed.
		 * @param float $funds The funds amount.
		 *
		 * @since 1.0.0
		 *
		 */
		public function add_fund_order( $order, $has_removed, $funds ) {

			$order_id            = $order->get_id();
			$customer_id         = $order->get_user_id();
			$customer_fund       = new YITH_YWF_Customer( $customer_id );
			$total_fund_refunded = $order->get_meta( '_order_funds_refunded' );
			$total_fund_refunded = '' === $total_fund_refunded ? 0 : $total_fund_refunded;

			if ( ( empty( $has_removed ) || 'no' === $has_removed ) && ! empty( $funds ) ) {
				$customer_fund->decrement_funds( $funds );
				$funds_show_to_order_currency = apply_filters( 'yith_show_funds_used_into_order_currency', $funds, $order_id );
				$order->update_meta_data( '_order_fund_removed', 'yes' );
				$order->save();
				/* translators: %1$s is the amount of funds , %2$s is the customer id */
				$order_note = sprintf( __( 'Removed %1$s funds from customer #%2$s account', 'yith-woocommorce-funds' ), wc_price( $funds_show_to_order_currency ), $customer_id );
				$order->add_order_note( $order_note );
				$default = apply_filters(
					'ywf_add_fund_order_log_arguments',
					array(
						'user_id'        => $customer_id,
						'order_id'       => $order_id,
						'fund_user'      => $funds - $total_fund_refunded,
						'type_operation' => 'pay',
					)
				);

				do_action( 'ywf_add_user_log', $default );
			}

		}

		/**
		 * Remove order fund and increment user fund
		 *
		 * @param WC_Order $order The order.
		 * @param bool $has_removed Funds already removed.
		 * @param float $funds The funds.
		 *
		 */
		public function remove_fund_order( $order, $has_removed, $funds ) {

			$order_id            = $order->get_id();
			$customer_id         = $order->get_user_id();
			$customer_fund       = new YITH_YWF_Customer( $customer_id );
			$total_fund_refunded = $order->get_meta( '_order_funds_refunded' );
			$total_fund_refunded = empty( $total_fund_refunded ) ? 0 : $total_fund_refunded;

			if ( 'yes' === $has_removed && $funds ) {

				$customer_fund->add_funds( $funds );
				$order->update_meta_data( '_order_fund_removed', 'no' );
				$order->save();

				$funds_show_to_order_currency = apply_filters( 'yith_show_funds_used_into_order_currency', $funds, $order_id );
				/* translators: %1$s is the amount of funds , %2$s is the customer id */
				$order_note = sprintf( __( 'Added %1$s funds to customer #%2$s account', 'yith-woocommorce-funds' ), wc_price( $funds_show_to_order_currency ), $customer_id );
				$order->add_order_note( $order_note );

				$default = array(
					'user_id'        => $customer_id,
					'order_id'       => $order_id,
					'fund_user'      => $funds - $total_fund_refunded,
					'type_operation' => 'restore',
				);
				do_action( 'ywf_add_user_log', $default );
			}

		}

		/**
		 * Add funds to customer
		 *
		 * @param WC_Order $order The order.
		 *
		 * @since 1.0.0
		 *
		 */
		public function add_deposit_order( $order ) {

			$total    = $this->get_order_deposit_total( $order );
			$order_id = $order->get_id();

			$user_id        = $order->get_user_id();
			$fund_deposited = $order->get_meta( '_fund_deposited' );

			if ( empty( $fund_deposited ) || 'no' === $fund_deposited ) {

				$customer_fund = new YITH_YWF_Customer( $user_id );
				$order->update_meta_data( '_fund_deposited', 'yes' );
				$order->save();
				/* translators: %1$s is the amount of funds , %2$s is the customer id */
				$order_note = sprintf( __( 'Added %1$s funds to customer #%2$s account', 'yith-woocommorce-funds' ), wc_price( $total ), $user_id );
				$order->add_order_note( $order_note );

				$total = apply_filters( 'yith_admin_deposit_funds', $total, $order_id );
				$customer_fund->add_funds( $total );
				$default = array(
					'user_id'        => $user_id,
					'order_id'       => $order_id,
					'fund_user'      => $total,
					'type_operation' => 'deposit',
				);
				do_action( 'ywf_add_user_log', $default );
			}
		}


		/**
		 * Check if order can be refunded
		 *
		 * @param WC_Order_Refund $refund The refund object.
		 * @param array $args Extra arguments.
		 *
		 * @throws Exception The exception.
		 */
		public function check_if_valid_refund( $refund, $args ) {

			$order_id = $args['order_id'];
			$order    = wc_get_order( $order_id );
			if ( ywf_order_has_deposit( $order ) ) {

				$refund_total = $refund->get_amount();

				$customer_id      = $order->get_user_id();
				$customer         = new YITH_YWF_Customer( $customer_id );
				$funds            = apply_filters( 'yith_show_funds_used_into_order_currency', $customer->get_funds(), $order_id );
				$raw_refund_total = $refund_total;
				$refund_total     = apply_filters( 'yith_admin_deposit_funds', $refund_total, $order_id );

				if ( $refund_total > $funds && $funds > 0 ) {

					$refund->set_amount( $funds );
					$refund->set_total( $funds * - 1 );
				}

				$refund_total                = $refund->get_amount();
				$refund_total_admin_currency = apply_filters( 'yith_admin_order_total', $refund_total, $order_id );

				$refund_total_formatted = wc_price( $refund_total, array( 'currency' => $refund->get_currency() ) );

				/* translators: %1$s is the amount of funds , %2$s is the customer id */
				$order_note = sprintf( __( 'Removed %1$s funds from customer #%2$s account', 'yith-woocommorce-funds' ), $refund_total_formatted, $customer_id );
				$order->add_order_note( $order_note );

				$customer->decrement_funds( $refund_total_admin_currency );
				$default = array(
					'user_id'        => $customer_id,
					'order_id'       => $order_id,
					'fund_user'      => $refund_total_admin_currency,
					'type_operation' => 'remove',
				);
				do_action( 'ywf_add_user_log', $default );

			}
		}

		/**
		 * Save fund used in order meta
		 *
		 * @param int $order_id The order id.
		 * @param array $posted The posted data.
		 *
		 * @since 1.0.0
		 */
		public function update_order_meta( $order_id, $posted ) {
			if ( 'yith_funds' !== $posted['payment_method'] && isset( WC()->session->ywf_partial_payment ) && 'yes' === WC()->session->ywf_partial_payment && isset( WC()->session->ywf_fund_used ) ) {

				$funds_used = WC()->session->ywf_fund_used;
				$order      = wc_get_order( $order_id );

				if ( ! is_null( $funds_used ) ) {
					// phpcs:disabled
					$meta_data_update = array(
						'_order_funds'                      => $funds_used,
						'_order_fund_removed'               => 'no',
						'ywf_total_paid_with_other_gateway' => $order->get_total( 'edit' ),
						'ywf_partial_payment'               => 'yes',
					);
					//phpcs:enabled
					foreach ( $meta_data_update as $meta_key => $meta_value ) {
						$order->update_meta_data( $meta_key, $meta_value );
					}

					$order->save();

				}
			}
		}

		/**
         * Save fund used in api order meta
         *
		 * @param WC_Order $order The order.
		 * @param WP_REST_Request $request The request
		 *
		 * @return void
		 */
        public function api_update_order_meta( $order, $request ){

	        if ( 'yith_funds' !== $request['payment_method'] && isset( WC()->session->ywf_partial_payment ) && 'yes' === WC()->session->ywf_partial_payment && isset( WC()->session->ywf_fund_used ) ) {
		        $funds_used = WC()->session->ywf_fund_used;
		        $meta_data_update = array(
			        '_order_funds'                      => $funds_used,
			        '_order_fund_removed'               => 'no',
			        'ywf_total_paid_with_other_gateway' => $order->get_total( 'edit' )-$funds_used,
			        'ywf_partial_payment'               => 'yes',
		        );

		        //phpcs:enabled
		        foreach ( $meta_data_update as $meta_key => $meta_value ) {
			        $order->update_meta_data( $meta_key, $meta_value );
		        }
	        }

        }

		/**
		 * Save order deposit meta
		 *
		 * @param int|WC_Order $the_order The order id or the object.
		 * @param array $posted The posted data.
		 *
		 * @throws Exception The exception.
		 *
		 * @since 1.0.0
		 */
		public function update_order_deposit_meta( $the_order, $posted ) {

			$order = $the_order instanceof WC_Order ? $the_order : wc_get_order( $the_order );

			if ( 1 === $order->get_item_count() ) {

				$has_deposit_product = false;
				$deposit_item        = false;
				foreach ( $order->get_items() as $item ) {
					$product_id = wc_get_order_item_meta( $item->get_id(), '_product_id', true );

					$product = wc_get_product( $product_id );

					if ( $product->is_type( 'ywf_deposit' ) ) {
						$deposit_item        = $item;
						$has_deposit_product = true;
						break;
					}
				}
				if ( false !== WC()->session->get( 'deposit_amount', false ) ) {
					$amount_deposit = WC()->session->get( 'deposit_amount', false );
				} else {
					$amount_deposit = $deposit_item ? $order->get_item_total( $deposit_item ) : false;
				}
				if ( $has_deposit_product && $amount_deposit ) {

					$meta_data_update = array(
						'_order_has_deposit'    => 'yes',
						'_order_deposit_amount' => $amount_deposit,
					);

					foreach ( $meta_data_update as $meta_key => $meta_value ) {
						$order->update_meta_data( $meta_key, $meta_value );
					}

					$order->save();
				}
			}

		}


		/**
		 * Print custom order details in admin
		 *
		 * @param int $order_id The order id.
		 *
		 * @since 1.0.0
		 *
		 */
		public function woocommerce_admin_order_totals_show_user_funds( $order_id ) {
			$order       = wc_get_order( $order_id );
			$order_funds = $order->get_meta( '_order_funds' );
			if ( $order_funds ) {

				?>
                <tr>
                    <td class="label"><?php echo wc_help_tip( __( 'Funds used by the customer to pay for this order.', 'yith-woocommerce-account-funds' ) ); //phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Funds used', 'yith-woocommerce-account-funds' ); ?>
                    </td>

                    <td width="1%"></td>
                    <td class="total">
						<?php echo $this->get_formatted_order_total( $order ); //phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </td>
                </tr>
				<?php

			}
		}

		/**
		 * Show the funds available in order totals
		 *
		 * @param int $order_id The order id.
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_admin_order_totals_user_funds_available( $order_id ) {

			$order = wc_get_order( $order_id );

			if ( ywf_order_has_deposit( $order ) ) {

				$user_funds   = new YITH_YWF_Customer( $order->get_user_id() );
				$tot_funds_av = apply_filters( 'yith_admin_order_totals_user_available', $user_funds->get_funds(), $order_id );
				?>
                <input type="hidden" class="ywf_available_user_fund" value="<?php echo esc_attr( $tot_funds_av ); ?>">
				<?php
			}
		}

		/**
		 * Get the formatted total
		 *
		 * @param WC_Order $order The order.
		 * @param string $tax_display Tax display.
		 * @param bool $display_refunded Bool value.
		 *
		 * @return string
		 */
		public function get_formatted_order_total( $order, $tax_display = '', $display_refunded = true ) {

			global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName

			$order_id = $order->get_id();
			$total    = apply_filters( 'yith_show_funds_used_into_order_currency', $order->get_meta( '_order_funds' ), $order_id );

			$currency        = $YITH_FUNDS->is_wc_2_7 ? $order->get_currency() : $order->get_order_currency(); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
			$formatted_total = wc_price( - $total, array( 'currency' => $currency ) );
			$order_total     = $total;
			$total_refunded  = apply_filters( 'yith_show_funds_used_into_order_currency', $order->get_meta( '_order_funds_refunded' ), $order_id );
			$tax_string      = '';

			// Tax for inclusive prices.
			if ( wc_tax_enabled() && 'incl' === $tax_display ) {
				$tax_string_array = array();

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
					foreach ( $order->get_tax_totals() as $code => $tax ) {
						$tax_amount         = ( $total_refunded && $display_refunded ) ? wc_price( WC_Tax::round( $tax->amount - $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ) ), array( 'currency' => $order->get_order_currency() ) ) : $tax->formatted_amount;
						$tax_string_array[] = sprintf( '%s %s', $tax_amount, $tax->label );
					}
				} else {
					$tax_amount         = ( $total_refunded && $display_refunded ) ? $order->get_total_tax() - $order->get_total_tax_refunded() : $order->get_total_tax();
					$tax_string_array[] = sprintf( '%s %s', wc_price( $tax_amount, array( 'currency' => $currency ) ), WC()->countries->tax_or_vat() );
				}
				if ( ! empty( $tax_string_array ) ) {
					/* translators: %s show the tax amount */
					$tax_string = ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) );
				}
			}

			if ( $total_refunded && $display_refunded ) {
				$formatted_total = '<del>' . strip_tags( $formatted_total ) . '</del> <ins>' . wc_price( ( $order_total - $total_refunded ), array( 'currency' => $currency ) ) . $tax_string . '</ins>'; // phpcs:ignore
			} else {
				$formatted_total .= $tax_string;
			}

			return apply_filters( 'woocommerce_get_formatted_order_funds_total', $formatted_total, $order );
		}

		/**
		 * Return order total with funds
		 *
		 * @param float $total The order total.
		 * @param WC_Order $order The order.
		 *
		 * @return float
		 * @since 1.0.0
		 *
		 */
		public function show_order_total_include_funds( $total, $order ) {

			if ( ywf_order_has_deposit( $order ) ) {
				return $total;
			}
			$order_id = $order->get_id();
			$funds    = apply_filters( 'yith_show_funds_used_into_order_currency', $order->get_meta( '_order_funds' ), $order_id );

			if ( ! empty( $funds ) ) {

				$partial_payment = $order->get_meta( 'ywf_partial_payment' );

				if ( floatval( 0 ) === floatval( $total ) ) {
					return $total + floatval( $funds );
				}
				if ( 'yes' === $partial_payment ) {
					$paid_with_other_gateway = $order->get_meta( 'ywf_total_paid_with_other_gateway' );

					if ( empty( $paid_with_other_gateway ) ) {

						if ( ! is_callable( $order, 'get_total_fees' ) ) {
							$fees       = $order->get_fees();
							$total_fees = 0;
							foreach ( $fees as $fee ) {
								$total_fees += $fee->get_total();
							}
						} else {
							$total_fees = $order->get_total_fees();
						}

						$paid_with_other_gateway = ( $order->get_subtotal() + $total_fees + $order->get_shipping_total() );

						foreach ( $order->get_tax_totals() as $code => $tax_total ) {

							$paid_with_other_gateway += $tax_total->amount;
						}
						$paid_with_other_gateway -= $order->get_total_discount();

						$paid_with_other_gateway -= floatval( $funds );
					}

					return $paid_with_other_gateway;
				}
			}

			return $total;
		}

		/**
		 * Add order amount total filter
		 *
		 * @param int $order_id The order id.
		 *
		 * @since 1.0.0
		 *
		 */
		public function add_include_order_total_with_fund_filter( $order_id ) {

			add_filter( 'woocommerce_order_amount_total', array( $this, 'show_order_total_include_funds' ), 20, 2 );
		}


		/**
		 * Remove filter
		 *
		 * @since 1.0.0
		 */
		public function remove_order_total_with_fund_filter() {

			remove_filter( 'woocommerce_order_amount_total', array( $this, 'show_order_total_include_funds' ), 20 );
		}


		/**
		 * Add order item line into email
		 *
		 * @param array $total_rows The total rows.
		 * @param WC_Order $order The order.
		 *
		 * @return array
		 * @since 1.0.0
		 *
		 */
		public function get_order_fund_item_total( $total_rows, $order ) {

			$order_id = $order->get_id();
			$fund     = apply_filters( 'yith_show_funds_used_into_order_currency', $order->get_meta( '_order_funds' ), $order_id );

			if ( ! empty( $fund ) ) {

				$currency           = $order->get_currency();
				$is_partial_payment = $order->get_meta( 'ywf_partial_payment' );

				if ( 'yes' === $is_partial_payment ) {

					$payment_method = $order->get_payment_method_title();

					$order_total = $order->get_total();
					$index       = array_search( 'payment_method', array_keys( $total_rows ), true );
					$paid_rows   = array(
						'ywf_fund_used'  => array(
							'label' => apply_filters( 'ywf_display_used_funds', __( 'Total with funds:', 'yith-woocommerce-account-funds' ) ),
							'value' => wc_price( $fund, array( 'currency' => $currency ) ),
						),
						'ywf_paid_other' => array(
							/* translators: %s the payment method used to complete the oder */
							'label' => sprintf( __( 'Total with %s: ', 'yith-woocommerce-account-funds' ), $payment_method ),
							'value' => wc_price( $order_total, array( 'currency' => $currency ) ),
						),
					);

					$total_rows['order_total']['value'] = wc_price( $order_total + $fund, array( 'currency' => $currency ) );

				} else {
					$index     = array_search( 'order_total', array_keys( $total_rows ), true );
					$paid_rows = array(
						'ywf_funds_used' => array(
							'label' => apply_filters( 'ywf_display_used_funds', __( 'Funds used', 'yith-woocommerce-account-funds' ) ),
							'value' => wc_price( - $fund, array( 'currency' => $currency ) ),
						),
					);
				}

				$total_rows = array_slice( $total_rows, 0, $index, true ) + $paid_rows + array_slice( $total_rows, $index, count( $total_rows ) - 1, true );

			}

			return $total_rows;
		}

		/**
		 * Remove a refund from an order
		 *
		 * @param int $refund_id The refund id.
		 * @param int $order_id The order id.
		 *
		 * @since 1.0.0
		 */
		public function refund_deleted_order_funds( $refund_id, $order_id ) {

			$order          = wc_get_order( $order_id );
			$payment_method = $order->get_payment_method();
			$customer_id    = $order->get_user_id();

			if ( 'yith_funds' === $payment_method ) {

				$funds_refunded = apply_filters( 'yith_show_funds_used_into_order_currency', $order->get_meta( '_order_funds_refunded' ), $order_id );

				$total_refund = $order->get_total_refunded();
				$how_refund   = wc_format_decimal( $funds_refunded - $total_refund, wc_get_price_decimals() );

				$customer = new YITH_YWF_Customer( $customer_id );

				$how_refund_base_currency   = apply_filters( 'yith_how_refund_base_currency', $how_refund, $order_id );
				$total_refund_base_currency = apply_filters( 'yith_how_refund_base_currency', $total_refund, $order_id );
				$customer->decrement_funds( $how_refund_base_currency );

				$order->update_meta_data( '_order_funds_refunded', $total_refund_base_currency );
				$order->save();
				/* translators: %1$s is the amount of funds, %2$s is the customer id */
				$order_note = sprintf( __( 'Removed %1$s funds from customer #%2$s account', 'yith-woocommorce-funds' ), wc_price( $how_refund ), $order->get_user_id() );
				$order->add_order_note( $order_note );

				$default = array(
					'user_id'        => $customer_id,
					'order_id'       => $order_id,
					'fund_user'      => $how_refund_base_currency,
					'type_operation' => 'pay',
				);

				do_action( 'ywf_add_user_log', $default );

			}
		}

		/**
		 * Add custom view in order table
		 *
		 * @param array $views The views.
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function add_order_deposit_view( $views ) {

			$tot_order = $this->count_order_deposit();

			if ( $tot_order > 0 ) {
				$filter_url   = esc_url(
					add_query_arg(
						array(
							'post_type'         => 'shop_order',
							'ywf_order_deposit' => true,
						),
						admin_url( 'edit.php' )
					)
				);
				$filter_class = isset( $_GET['ywf_order_deposit'] ) ? 'current' : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$views['ywf_order_deposit'] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>', $filter_url, $filter_class, __( 'Deposit', 'yith-woocommerce-account-funds' ), $tot_order );
			}

			return $views;
		}

		/**
		 * Customize query
		 *
		 * @since 1.0.0
		 */
		public function filter_order_deposit_for_view() {
			if ( isset( $_GET['ywf_order_deposit'] ) && wp_unslash( $_GET['ywf_order_deposit'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				add_filter( 'posts_join', array( $this, 'filter_order_join_for_view' ) );
				add_filter( 'posts_where', array( $this, 'filter_order_where_for_view' ) );
			}
		}

		/**
		 * Filter the order to show only fund deposit orders
		 *
		 * @param array $query_args The query args.
		 *
		 * @return array
		 */
		public function add_order_query_args( $query_args ) {
			if ( isset( $_GET['ywf_order_deposit'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$query_args['meta_key']   = '_order_has_deposit';
				$query_args['meta_value'] = 'yes';
				$query_args['compare']    = '=';
			}

			return $query_args;
		}

		/**
		 * Add joins to order view query
		 *
		 * @param string $join The join query.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function filter_order_join_for_view( $join ) {
			global $wpdb;

			$join .= " LEFT JOIN {$wpdb->prefix}postmeta as pm ON {$wpdb->posts}.ID = pm.post_id";

			return $join;
		}

		/**
		 * Add conditions to order view query
		 *
		 * @param string $where Original where query section.
		 *
		 * @return string filtered where query section
		 * @since 1.0.0
		 *
		 * @since 1.0.0
		 */
		public function filter_order_where_for_view( $where ) {
			global $wpdb;
			$where .= $wpdb->prepare(
				' AND pm.meta_key = %s AND pm.meta_value = %s',
				array(
					'_order_has_deposit',
					'yes',
				)
			);

			return $where;
		}


		/**
		 * Count order with deposit
		 *
		 * @return int
		 * @since 1.0.0
		 */
		public function count_order_deposit() {
			global $wpdb;
			$query  = $wpdb->prepare(
				"SELECT DISTINCT COUNT(*) FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
                                     WHERE {$wpdb->posts}.post_type = %s AND ( {$wpdb->postmeta}.meta_key=%s AND {$wpdb->postmeta}.meta_value = %s )",
				'shop_order',
				'_order_has_deposit',
				'yes'
			);
			$result = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared

			return $result;
		}

		/**
		 * Perform the deposit again action
		 *
		 * @param WC_Order $order The order.
		 */
		public function deposit_again( $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button', 10 );

				$total = $this->get_order_deposit_total( $order );

				$args = array(
					'text'   => __( 'Deposit again', 'yith-woocommerce-account-funds' ),
					'type'   => 'button',
					'amount' => $total,
				);

				echo YITH_YWF_Shortcodes::make_a_deposit_small( $args ); //phpcs:ignore WordPress.Security.EscapeOutput

			} else {
				add_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button', 10, 1 );
			}
		}

		/**
		 * Return the total of fund deposited
		 *
		 * @param WC_Order $order The order.
		 *
		 * @return float
		 * @since 1.0.11
		 * Return the order total excluding fees
		 */
		public function get_order_deposit_total( $order ) {

			$total = 0;

			if ( ywf_order_has_deposit( $order ) ) {

				$total = $order->get_meta( '_order_deposit_amount' );
			}

			return $total;
		}

		/**
		 * Remove deposit form calculate tax procedure
		 *
		 * @param array $items The items.
		 * @param int $order_id The order id.
		 * @param string $country The country.
		 *
		 * @return array
		 * @throws Exception The exception.
		 * @since 1.0.0
		 */
		public function remove_deposit_from_items( $items, $order_id, $country ) {

			$order = wc_get_order( $order_id );
			if ( ywf_order_has_deposit( $order ) ) {

				global $YITH_FUNDS; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
				$order_item_id = $items['order_item_id'];
				foreach ( $order_item_id as $key => $item_id ) {

					$product_id = $YITH_FUNDS->is_wc_2_7 ? wc_get_order_item_meta( $item_id, '_product_id', true ) : $order->get_item_meta( $item_id, '_product_id', true ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
					$product    = wc_get_product( $product_id );

					if ( $product->is_type( 'ywf_deposit' ) ) {
						unset( $items['order_item_id'][ $key ] );
						break;
					}
				}
			}

			return $items;
		}


		/**
		 * Refund the partial payments
		 */
		public function refund_funds_in_partial_payments() {

			if ( isset( $_REQUEST['funds_to_add'] ) && isset( $_POST['security'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'yith-add-funds-nonce' ) && current_user_can( 'edit_shop_orders' ) ) {

				$fund_to_add_display = wp_unslash( $_REQUEST['funds_to_add'] ); // phpcs:ignore
				$customer_id         = wp_unslash( $_REQUEST['customer_id'] ); // phpcs:ignore
				$order_id            = wp_unslash( $_REQUEST['order_id'] ); // phpcs:ignore
				$order               = wc_get_order( $order_id );
				$customer            = new YITH_YWF_Customer( $customer_id );
				$fund_to_add         = apply_filters( 'yith_admin_deposit_funds', $fund_to_add_display, $order_id );
				$fund_to_add_display = wc_price( $fund_to_add_display, array( 'currency' => $order->get_currency() ) );

				/* translators: %1$s is the amount of funds %2$s is the customer id */
				$order_note = sprintf( __( 'Add %1$s funds to customer #%2$s', 'yith-woocommorce-funds' ), $fund_to_add_display, $order->get_user_id() );

				$customer->add_funds( $fund_to_add );
				$default = array(
					'user_id'        => $order->get_user_id(),
					'order_id'       => $order_id,
					'fund_user'      => $fund_to_add,
					'type_operation' => 'admin_op',
					/* translators: %1$s is the amount of funds, %2$s is the order id */
					'description'    => sprintf( __( 'Add %1$s fund in the order #%2$s', 'yith-woocommerce-account-funds' ), $fund_to_add_display, $order_id ),
				);
				do_action( 'ywf_add_user_log', $default );

				wp_send_json( $order_note );

			}
		}

		/**
		 * Check if the order is a partial order and add hidden field
		 *
		 * @param int $order_id The order id.
		 *
		 * @since 1.3.0
		 */
		public function add_order_custom_field( $order_id ) {

			$order = wc_get_order( $order_id );

			$partial_payment = $order->get_meta( 'ywf_partial_payment' );
			if ( 'yes' === $partial_payment ) {
				?>
                <input type="hidden" id="ywf_partial_payment" value="<?php echo esc_attr( $partial_payment ); ?>">
				<?php
			}
		}


		/**
		 * Add metabox for customer fund when the order is a partial payment
		 *
		 * @since 1.3.0
		 */
		public function add_order_customer_funds_metabox( $post_type, $post ) {

			if ( in_array( $post_type, array( wc_get_page_screen_id( 'shop-order' ), 'shop-order' ), true ) ) {
				$order           = wc_get_order( $post );
				$partial_payment = $order->get_meta( 'ywf_partial_payment' );

				if ( apply_filters( 'yith_account_funds_show_order_metabox', 'yes' === $partial_payment, $partial_payment, $order ) ) {
					add_meta_box(
						'yith-wc-order-account-funds-metabox',
						__( 'Account Funds', 'yith-woocommerce-delivery-date' ),
						array(
							$this,
							'order_customer_funds_meta_box_content',
						),
						$post_type,
						'side',
						'low'
					);
				}
			}
		}

		/**
		 * Print metabox
		 *
		 * @since 1.3.0
		 */
		public function order_customer_funds_meta_box_content() {
			include_once 'admin/meta-boxes/html-account-fund-meta-box.php';
		}

		/**
		 * Set the right order total with partial payment
		 *
		 * @param WC_Order $order The order.
		 *
		 * @throws Exception The exception.
		 */
		public function fix_total_with_partial_payment( $order ) {
			$partial_payment           = $order->get_meta( 'ywf_partial_payment' );
			$to_pay_with_other_gateway = $order->get_meta( 'ywf_total_paid_with_other_gateway' );

			if ( $order->has_status( 'failed' ) && 'yes' === $partial_payment && ! empty( $to_pay_with_other_gateway ) ) {
				$order->set_total( $to_pay_with_other_gateway );
			}
		}


	}
}
/**
 * Instance the class
 *
 * @since 1.0.0
 */
function YITH_YWF_Order() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
	return new YITH_YWF_Order();
}
