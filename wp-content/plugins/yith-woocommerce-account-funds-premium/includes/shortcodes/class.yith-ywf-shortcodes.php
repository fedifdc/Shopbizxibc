<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class manage plugin shortcodes
 *
 * @package YITH\AccountFunds\Classes\Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Shortcodes' ) ) {
	/**
	 * Class YITH_YWF_Shortcodes
	 */
	class YITH_YWF_Shortcodes {
		/**
		 * Show the  funds history
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 *
		 * @return string
		 * @since 1.0.0
		 * @author YITH <plugins@yithemes.com>
		 */
		public static function show_user_deposit_log( $atts, $content = null ) {

			$atts = shortcode_atts(
				array(
					'per_page'   => apply_filters( 'ywf_show_log_per_page', 10 ),
					'pagination' => 'no',
				),
				$atts
			);

			extract( $atts ); //phpcs:ignore WordPress.PHP.DontExtract

			$query_args                   = array();
			$query_args['type_operation'] = isset( $_POST['filter_deposit_type'] ) ? sanitize_text_field( wp_unslash( $_POST['filter_deposit_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

			$count = YWF_Log()->count_log( $query_args );

			$endpoint     = yith_account_funds_get_endpoint_slug( 'view-history' );
			$current_page = max( 1, get_query_var( $endpoint ) );
			$base_url     = '';

			$my_account_page = wc_get_page_id( 'myaccount' );

			if ( 'yes' === $pagination && $count > 1 ) {

				global $post;

				if ( intval( $my_account_page ) === intval( $post->ID ) ) {

					$base_url = wc_get_endpoint_url( $endpoint, '%#%' );

					$format = '%#%';

				} else {

					$base_url = esc_url( add_query_arg( array( 'paged' => '%#%' ), get_the_permalink( $post->ID ) ) );
					$format   = '?paged=%#%';
				}

				$pages = ceil( $count / $per_page );

				if ( $current_page > $pages ) {
					$current_page = $pages;
				}

				$offset = ( $current_page - 1 ) * $per_page;

				if ( $pages > 1 ) {
					$page_links = paginate_links(
						array(
							'base'     => $base_url,
							'format'   => $format,
							'current'  => $current_page,
							'total'    => $pages,
							'show_all' => true,
						)
					);
				}

				$query_args['limit']  = $per_page;
				$query_args['offset'] = $offset;

			}

			$query_args['user_id'] = get_current_user_id();

			$additional_params = array(
				'count'            => $count,
				'current_page'     => $current_page,
				'user_log_items'   => YWF_Log()->get_log( $query_args ),
				'page_links'       => isset( $page_links ) ? $page_links : false,
				'show_filter_form' => true,
				'show_total'       => true,
			);

			$atts = array_merge( $atts, $additional_params );

			$atts['atts'] = $atts;

			ob_start();
			wc_get_template( 'view-deposit-history.php', $atts, '', YITH_FUNDS_TEMPLATE_PATH );
			$template = ob_get_contents();
			ob_end_clean();

			return $template;
		}

		/**
		 * Show the users fund
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public static function show_user_fund( $atts, $content = null ) {

			$atts = shortcode_atts(
				array(
					'text_align'  => 'left',
					'font_weight' => 'normal',
					'message'     => __( 'Available funds:', 'yith-woocommerce-account-funds' ),
				),
				$atts
			);

			ob_start(); //phpcs:ignore WordPress.PHP.DontExtract
			wc_get_template( 'view-customer-fund.php', $atts, '', YITH_FUNDS_TEMPLATE_PATH );
			$template = ob_get_contents();
			ob_end_clean();

			return $template;
		}

		/**
		 * Show the deposit form
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public static function make_a_deposit_form( $atts, $content = null ) {

			if ( apply_filters( 'ywf_prevent_customer_add_funds', false ) ) {
				return '';
			}

			$max = ywf_get_max_fund_rechargeable();

			$atts = array(
				'min'  => wc_format_decimal( ywf_get_min_fund_rechargeable() ),
				'max'  => $max,
				'step' => get_option( 'yith_funds_step', 1 ),
			);

			ob_start();
			wc_get_template( 'make-a-deposit-form.php', $atts, '', YITH_FUNDS_TEMPLATE_PATH );
			$template = ob_get_contents();
			ob_end_clean();

			return $template;
		}

		/**
		 * Show the make a deposit endpoint
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public static function make_a_deposit_endpoint( $atts, $content = null ) {

			$max = ywf_get_max_fund_rechargeable();

			$step    = get_option( 'yith_funds_step', 1 );
			$default = array( 'show_wc_menu' => false );

			if ( ! is_user_logged_in() ) {
				wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
				exit;
			}

			global $is_make_a_deposit_form;
			$is_make_a_deposit_form = true;
			$default                = wp_parse_args( $atts, $default );
			$default['min']         = wc_format_decimal( ywf_get_min_fund_rechargeable() );
			$default['max']         = $max;
			$default['step']        = $step;
			$default['amount']      = isset( $_REQUEST['amount'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['amount'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			$default['payment']     = array(
				'checkout'           => WC()->checkout(),
				'available_gateways' => WC()->payment_gateways()->get_available_payment_gateways(),
				'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'yith-woocommerce-account-funds' ) ),
			);

			ob_start();
			wc_get_template( 'make-a-deposit.php', $default, YITH_FUNDS_TEMPLATE_PATH, YITH_FUNDS_TEMPLATE_PATH );
			$template = ob_get_contents();
			ob_end_clean();
			$is_make_a_deposit_form = false;

			return $template;

		}

		/**
		 * Show the short make a deposit form
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 */
		public static function make_a_deposit_small( $atts, $content = null ) {

			$atts = shortcode_atts(
				array(
					'text'   => __( 'Deposit', 'yith-woocommerce-account-funds' ),
					'type'   => 'button',
					'amount' => 0,
				),
				$atts
			);

			extract( $atts ); //phpcs:ignore WordPress.PHP.DontExtract
			$content     = '';
			$amount_html = wc_price( $amount );
			$url         = ywf_get_endpoint_url( 'make-a-deposit', array( 'amount' => $amount ) );
			switch ( $type ) {

				case 'button':
					$amount_input = sprintf( '<input type="hidden" value="%s" name="amount">', wc_format_decimal( $amount ) );
					$button       = sprintf( '<input type="submit" class="button" value="%s">', $text );
					$content      = sprintf( '<form method="get" action="%s">%s %s</form>', wc_get_endpoint_url( 'make-a-deposit', '', wc_get_page_permalink( 'myaccount' ) ), $button, $amount_input );
					break;
				case 'link':
					$content = sprintf( '<a href="%s" target="_blank">%s</a>', $url, $text );
					break;
				default:
					$endpoint = apply_filters( 'ywf_make_deposit_slug', yith_account_funds_get_endpoint_slug( 'make-a-deposit' ) );
					$content  = sprintf( '<span>%s</span>', esc_url( add_query_arg( array( 'amount' => $amount ), wc_get_page_permalink( 'myaccount' ) . $endpoint ) ) );
					break;
			}

			return $content;

		}

		/**
		 * Show the discount message
		 *
		 * @param array $atts The shortcode attrs.
		 * @param null  $content The content.
		 *
		 * @return false|string
		 * @since 1.0.0
		 */
		public static function show_discount_message( $atts, $content = null ) {
			$type_discount = ywf_get_discount_type();
			$discount      = apply_filters( 'yith_discount_value', ywf_get_discount_value(), $type_discount );

			if ( 'fixed_cart' === $type_discount ) {
				$price_label = sprintf( '<strong>%s</strong>', wc_price( $discount ) );
			} else {
				$price_label = sprintf( '<strong>%s</strong>', $discount . '%' );
			}

			$message_1 = _x( 'Pay the order using your account funds and get a', 'Part of: Pay the order using your account funds and get a 50% discount on your cart', 'yith-woocommerce-account-funds' );
			$message_2 = _x( 'discount on your cart', 'Part of: Pay the order using your account funds and get a 50% discount on your cart', 'yith-woocommerce-account-funds' );
			$message   = sprintf( '%s %s %s', $message_1, $price_label, $message_2 );

			if ( ! function_exists( 'wc_print_notice' ) ) {

				include_once WC()->plugin_path() . '/includes/wc-notice-functions.php';
			}
			ob_start();
			wc_print_notice( $message, 'success' );
			$message = ob_get_contents();
			ob_end_clean();

			return $message;
		}

		/**
		 * Show redeem vendor endpoint
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public static function redeem_vendor_funds() {
			ob_start();
			wc_get_template( 'redeem-funds.php', array(), YITH_FUNDS_TEMPLATE_PATH, YITH_FUNDS_TEMPLATE_PATH );
			$template = ob_get_contents();
			ob_end_clean();

			return $template;

		}
	}
}

add_shortcode( 'yith_ywf_show_history', array( 'YITH_YWF_Shortcodes', 'show_user_deposit_log' ) );
add_shortcode( 'yith_ywf_show_user_fund', array( 'YITH_YWF_Shortcodes', 'show_user_fund' ) );
add_shortcode( 'yith_ywf_make_a_deposit_form', array( 'YITH_YWF_Shortcodes', 'make_a_deposit_form' ) );
add_shortcode( 'yith_ywf_make_a_deposit_endpoint', array( 'YITH_YWF_Shortcodes', 'make_a_deposit_endpoint' ) );
add_shortcode( 'yith_ywf_make_a_deposit_small', array( 'YITH_YWF_Shortcodes', 'make_a_deposit_small' ) );
add_shortcode( 'yith_ywf_show_discount_message', array( 'YITH_YWF_Shortcodes', 'show_discount_message' ) );
add_shortcode( 'yith_ywf_redeem_vendor_funds', array( 'YITH_YWF_Shortcodes', 'redeem_vendor_funds' ) );
