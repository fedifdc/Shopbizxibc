<?php
/**
 * Checkout functionality
 *
 * The WooCommerce checkout class handles the checkout process, collecting user data and processing the payment.
 *
 * @package WooCommerce/Classes
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Checkout class.
 */
class CL_Checkout extends WC_Checkout {

    /**
	 * Process the checkout after the confirm order button is pressed.
	 *
	 * @throws Exception When validation fails.
	 */
	public function process_checkout() {
        global $cl_options;
		try {
			$nonce_value = wc_get_var( $_REQUEST['woocommerce-process-checkout-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, 'woocommerce-process_checkout' ) ) {
				WC()->session->set( 'refresh_totals', true );
				throw new Exception( __( 'We were unable to process your order, please try again.', 'woocommerce' ) );
			}

			wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );
			wc_set_time_limit( 0 );

			do_action( 'woocommerce_before_checkout_process' );

			if ( WC()->cart->is_empty() ) {
				/* translators: %s: shop cart url */
				throw new Exception( sprintf( __( 'Sorry, your session has expired. <a href="%s" class="wc-backward">Return to shop</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'shop' ) ) ) );
			}

			do_action( 'woocommerce_checkout_process' );

			$errors      = new WP_Error();
			$posted_data = $this->get_posted_data();

			// Update session for customer and totals.
			$this->update_session( $posted_data );

			// Validate posted data and cart items before proceeding.
			$this->validate_checkout( $posted_data, $errors );

			foreach ( $errors->errors as $code => $messages ) {
				$data = $errors->get_error_data( $code );
				foreach ( $messages as $message ) {
					wc_add_notice( $message, 'error', $data );
				}
			}

			if ( empty( $posted_data['woocommerce_checkout_update_totals'] ) && 0 === wc_notice_count( 'error' ) ) {
				$all_cs = !empty( $cl_options['cepatlakoo_wa_rotator_cs'] ) ? $cl_options['cepatlakoo_wa_rotator_cs'] : array();
				$cepatlakoo_cart_wa = preg_split('/\r\n|[\r\n]/', $all_cs);
				$cepatlakoo_cart_wa = (count($cepatlakoo_cart_wa) > 1) ? $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)] : $cepatlakoo_cart_wa[0];
				$data = explode('|', $cepatlakoo_cart_wa);
				$cepatlakoo_cart_wa = $data[1];

				if ( is_array($cepatlakoo_cart_wa) ) {
					$cepatlakoo_cart_wa = $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)];
				}

				$admin_no = $cepatlakoo_cart_wa;
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				$different_address = isset($_POST['ship_to_different_address']) ? true : false;
				$all_fields = WC()->checkout->checkout_fields;
				$subtotal = 0;

				// var_dump($all_fields['billing']); exit();
				foreach($all_fields['billing'] as $key => $billing){
					if( $key == 'cl_bill_city_name' )
						continue;
					else if( $key == 'cl_bill_subdistrict_name' )
						$text .= '*'.__( 'Billing Subdistrict', 'cepatlakoo' ).':* '.$_POST[$key].'\n';
					else if( $key == 'billing_city' && isset($_POST['cl_bill_city_name']) )
						$text .= '*'.__( 'Billing City', 'cepatlakoo' ).':* '.$_POST['cl_bill_city_name'].'\n';
					else if( $key == 'billing_subdistrict' && isset($_POST['cl_bill_subdistrict_name']) )
						continue;
					else if( $key == 'billing_country' )
						$text .= '*'.$billing['label'].':* '.WC()->countries->countries[ $_POST[$key] ].'\n';
					else if( $key == 'billing_state' )
						$text .= '*'.$billing['label'].':* '.WC()->countries->get_states( $_POST['billing_country'] )[ $_POST[$key] ].'\n';
					else
						$text .= '*'.$billing['label'].':* '.$_POST[$key].'\n';
				}

				if($different_address){
					$text .= '\n*'.__('Shipping Address').'*\n';
					foreach($all_fields['shipping'] as $key => $shipping){
						if( $key == 'cl_ship_city_name' )
							continue;
						else if( $key == 'cl_ship_city_name' )
							$text .= '*'.__( 'Shipping Subdistrict', 'cepatlakoo' ).':* '.$_POST[$key].'\n';
						else if( $key == 'shipping_city' && isset($_POST['cl_ship_city_name']) )
							$text .= '*'.__( 'Shipping City', 'cepatlakoo' ).':* '.$_POST['cl_ship_city_name'].'\n';
						else if( $key == 'shipping_subdistrict' && isset($_POST['cl_ship_subdistrict_name']) )
							continue;
						else if( $key == 'shipping_country' )
							$text .= '*'.$shipping['label'].':* '.WC()->countries->countries[ $_POST[$key] ].'\n';
						else if( $key == 'shipping_state' )
							$text .= '*'.$shipping['label'].':* '.WC()->countries->get_states( $_POST['shipping_country'] )[ $_POST[$key] ].'\n';
						else
							$text .= '*'.$shipping['label'].':* '.$_POST[$key].'\n';
					}	
				}

				$text .= '\n*'.__('Order Detail', 'cepatlakoo').':* \n';
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = $_product->get_name();
						$product_price     = WC()->cart->get_product_price( $_product );
						
						$text .= '*- '.$product_name.'* (x'.$cart_item['quantity'].') : '.strip_tags( $product_price ).'\n';
					}
				}

				$text .= '\n*'.__('Subtotal', 'cepatlakoo').':* '.strip_tags( wc_price(WC()->cart->get_totals()['subtotal']) ).'\n';
				
				foreach ( WC()->cart->get_fees() as $fee ) :
					$text .= '*'.$fee->name.':* '.strip_tags( wc_price($fee->amount) ).'\n';
				endforeach;

				foreach( WC()->session->get('shipping_for_package_0')['rates'] as $method_id => $rate ):
					if( WC()->session->get('chosen_shipping_methods')[0] == $method_id ):
						$rate_label = $rate->label; // The shipping method label name
						$text .= '*'.__('Shipping').':* '.$rate_label.' ('.strip_tags( wc_price( $rate->cost ) ).')\n';
						break;
					endif;
				endforeach;

				foreach( WC()->cart->get_applied_coupons() as $coupon ):
					if ( is_string( $coupon ) ) {
						$coupon = new WC_Coupon( $coupon );
					}
				
					$discount_amount_html = '';
				
					$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
					$discount_amount_html = '-' . strip_tags( wc_price( $amount ) );
				
					if ( $coupon->get_free_shipping() && empty( $amount ) ) {
						$discount_amount_html = __( 'Free shipping coupon', 'cepatlakoo' );
					}
					
					$text .= '*'.$coupon->get_code().':* '.$discount_amount_html.'\n';
				endforeach;
				
				$text .= '*'.__( 'Total', 'cepatlakoo' ).':* '.strip_tags( wc_price(WC()->cart->get_totals()['total']) ).'\n';
				$payment = WC()->session->get( 'chosen_payment_method' );

				if( isset($_POST['order_comments']) && !empty($_POST['order_comments']) )
					$text .= '*'.__( 'Notes', 'cepatlakoo' ).':* '.$_POST['order_comments'].'\n';
				
				$text .= '*'.__('Payment Method', 'cepatlakoo').':* '.$available_gateways[$payment]->method_title.'\n\n';
				
				$text = str_replace( "&nbsp;", '', $text);
				$text = str_replace( '\n', '%0A', $text);
				$text = str_replace( "#", '%23', $text);
				$text = str_replace( "-", '%2D', $text);
				$text = str_replace( "&",'%26', $text);

				if( strpos($admin_no, '-') !== false ){
					$admin_no = str_replace('-', '', $admin_no);
				}
				if ( preg_match('[^\+62]', $admin_no ) ) {
                    $wa_phone = str_replace('+62', '62', $admin_no);
                } else if ( $admin_no[0] == '0' ) {
                    $admin_no = ltrim( $admin_no, '0' );
                    $wa_phone = '62'. $admin_no;
                } else if ( $admin_no[0] == '8' ) {
                    $wa_phone = '62'. $admin_no;
                } else {
                    $wa_phone = $admin_no;
                }

                if ( strpos($wa_phone, "-") ) {
                    $wa_phone = str_replace('-', '', $wa_phone);
				}
				
				$wa_phone = str_replace(' ', '', $wa_phone);

				$wa_base_url = 'https://api.whatsapp.com/';
				// var_dump($text); exit();
				$return['redirect'] = $wa_base_url .'send?phone='. $wa_phone .'&text='. $text;
				$return['result'] = 'success';
				WC()->cart->empty_cart();
				// var_dump( $all_fields['billing'] );
				// var_dump($wa_phone);
				// var_dump($return['redirect']);
				// exit();
				wp_send_json( $return );
			}
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
		}
		$this->send_ajax_failure_response();
	}
}