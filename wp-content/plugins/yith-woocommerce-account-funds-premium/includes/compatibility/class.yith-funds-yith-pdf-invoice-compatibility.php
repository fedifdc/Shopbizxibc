<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage the compatibility with YITH PDF Invoice
 *
 * @package YITH\AccountFunds\Classes\Compatibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_Funds_YITH_PDF_Invoice_Compatibility' ) ) {
	/**
	 * Class YITH_Funds_YITH_PDF_Invoice_Compatibility
	 */
	class YITH_Funds_YITH_PDF_Invoice_Compatibility {
		/**
		 * The instance of the class
		 *
		 * @var YITH_Funds_YITH_PDF_Invoice_Compatibility
		 */
		protected static $instance;

		/**
		 * YITH_Funds_YITH_PDF_Invoice_Compatibility constructor.
		 */
		public function __construct() {

			add_filter( 'yith_ywpi_create_automatic_invoices', array( $this, 'exclude_deposit_to_invoice' ), 10, 2 );
			add_filter( 'yith_ywpi_show_invoice_button_order_list', array( $this, 'hide_invoice_button_order_list' ), 10, 2 );
			add_filter( 'yith_ywpi_show_packing_slip_button_order_list', array( $this, 'hide_invoice_button_order_list' ), 10, 2 );
			add_filter( 'yith_ywpi_show_invoice_button_order_page', array( $this, 'hide_invoice_button_order_page' ), 10, 2 );
			add_filter( 'yith_ywpi_show_packing_slip_button_order_page', array( $this, 'hide_packing_slip_button_order_page' ), 10, 2 );
			add_filter( 'yith_ywpi_show_pro_forma_invoice_button_view_order', array( $this, 'hide_pro_forma_invoice_button_view_order' ), 10, 2 );
			add_action( 'yith_ywpi_after_button_order_list', array( $this, 'after_button_order_list' ), 10 );
			add_filter( 'yith_ywpi_print_document_notes', array( $this, 'print_order_funds_usage_note' ), 10, 2 );
		}

		/**
		 * The unique access
		 *
		 * @return YITH_Funds_YITH_PDF_Invoice_Compatibility
		 * @since 1.0.0
		 * @author YITH <plugins@yithemes.com>
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Exclude order deposit from automatic invoice
		 *
		 * @param bool $make_invoice Check if build invoice.
		 * @param int  $the_order The order id.
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 */
		public function exclude_deposit_to_invoice( $make_invoice, $the_order ) {

			if ( ! ( $the_order instanceof WC_Order ) ) {
				$the_order = wc_get_order( $the_order );
			}
			if ( ywf_order_has_deposit( $the_order ) ) {

				$make_invoice = false;
			}

			return $make_invoice;
		}

		/**
		 * Hide the invoice button in the order list
		 *
		 * @param string   $html The button html.
		 * @param WC_Order $order The order.
		 *
		 * @return string
		 * @since 1.0.0
		 *
		 */
		public function hide_invoice_button_order_list( $html, $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				$html = '';
			}

			return $html;
		}

		/**
		 * Hide invoice button in deposit order
		 *
		 * @param bool     $show_button Show or not the button.
		 * @param WC_Order $order The order.
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 */
		public function hide_invoice_button_order_page( $show_button, $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				$show_button = false;
			}

			return $show_button;
		}


		/**
		 * Show a custom message in the deposit orders
		 *
		 * @param WC_Order $order The order.
		 *
		 * @since 1.0.0
		 *
		 */
		public function after_button_order_list( $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				echo sprintf( '<small>%s</small>', __( 'It is not possible to create an invoice for deposit', 'yith-woocommerce-account-funds' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Hide pro-forma invoice button in my-account
		 *
		 * @param bool     $show_button Show or not the button.
		 * @param WC_Order $order The order.
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 */
		public function hide_pro_forma_invoice_button_view_order( $show_button, $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				$show_button = false;
			}

			return $show_button;
		}


		/**
		 * Hide invoice packing slip button in deposit order
		 *
		 * @param bool     $show_button Show or hide.
		 * @param WC_Order $order The order.
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 */
		public function hide_packing_slip_button_order_page( $show_button, $order ) {

			if ( ywf_order_has_deposit( $order ) ) {

				$show_button = false;
			}

			return $show_button;
		}

		/**
		 * Set right order total
		 *
		 * @param float        $order_total The order total.
		 * @param int|WC_Order $the_order The order id or order object.
		 *
		 * @return float
		 * @since 1.0.0
		 *
		 */
		public function set_order_total_in_invoice( $order_total, $the_order ) {

			if ( ! ( $the_order instanceof WC_Order ) ) {
				$the_order = wc_get_order( $the_order );
			}
			$funds_used = $the_order->get_meta( '_order_funds' );

			if ( $funds_used ) {
				$order_total = $order_total - $funds_used;
			}

			return $order_total;
		}

		/**
		 * Add fund row in total review
		 *
		 * @param WC_Order $order The order.
		 *
		 * @since 1.0.0
		 *
		 */
		public function add_fund_total_in_invoice( $order ) {

			$funds_used = $order->get_meta( '_order_funds' );

			if ( $funds_used ) {
				?>
				<tr class="invoice-details-funds-total">
					<td class="column-product"><?php esc_html_e( 'Discount for Funds Used', 'yith-woocommerce-account-funds' ); ?></td>
					<td class="column-total"><?php echo wc_price( - $funds_used ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?></td>
				</tr>
				<?php
			}
		}

		/**
		 * Add custom notice in the order paid with funds
		 *
		 * @param string        $notes The notes.
		 * @param YITH_Document $document The pdf document.
		 *
		 * @return string
		 */
		public function print_order_funds_usage_note( $notes, $document ) {

			$order          = $document->order;
			$payment_method = $order->get_meta( 'payment_method' );
			$br             = $notes ? '<br>' : '';

			if ( 'yith_funds' === $payment_method ) {

				$notes .= sprintf( '%s%s', $br, __( 'Order paid through funds', 'yith-woocommerce-account-funds' ) );

			}

			return $notes;
		}
	}
}
/**
 * Get the instance
 *
 * @return YITH_Funds_YITH_PDF_Invoice_Compatibility
 */
function YITH_Funds_YITH_PDF_Invoice_Compatibility() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

	return YITH_Funds_YITH_PDF_Invoice_Compatibility::get_instance();
}

