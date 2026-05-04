<?php
/**
* Gateway class
*/

if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
	return;
}

Class Cepatlakoo_Bank_Payment extends WC_Payment_Gateway {

    public function __construct($bank_id, $bank_icon, $bank_title, $bank_desc) {
        $this->id                 = $bank_id;
        // $this->icon               = apply_filters('woocommerce_offline_icon', '');
        $this->icon               = $bank_icon;
        $this->has_fields         = true;
        $this->method_title       = $bank_title;
        $this->method_description = $bank_desc;
        
        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();
        
        // Define user set variables
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->instructions = $this->get_option( 'instructions', $this->description );
        
        // Actions
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

        // Bank account fields shown on the thanks page and in emails.
		$this->account_details = get_option(
			'cepatlakoo_bank_accounts_'.$this->id,
			array(
				array(
					'account_name'   => $this->get_option( 'account_name' ),
					'account_number' => $this->get_option( 'account_number' ),
					'sort_code'      => $this->get_option( 'sort_code' ),
					'bank_name'      => $this->get_option( 'bank_name' ),
					'iban'           => $this->get_option( 'iban' ),
					'bic'            => $this->get_option( 'bic' ),
				),
			)
		);

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );
        
        // Customer Emails
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }

    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields() {
    
        $this->form_fields = apply_filters( 'wc_offline_form_fields', array(
        
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'cepatlakoo' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Transfer ', 'cepatlakoo' ).$this->method_title,
                'default' => 'no'
            ),
            
            'title' => array(
                'title'       => __( 'Title', 'cepatlakoo' ),
                'type'        => 'text',
                'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'cepatlakoo' ),
                'default'     => __( 'Transfer ', 'cepatlakoo' ).$this->method_title,
                'desc_tip'    => true,
            ),
            
            'description' => array(
                'title'       => __( 'Description', 'cepatlakoo' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see on your checkout.', 'cepatlakoo' ),
                'default'     => sprintf( __( 'Transfer pembayaran ke rekening bank %s kami.', 'cepatlakoo' ), $this->method_title),
                'desc_tip'    => true,
            ),
            
            'instructions' => array(
                'title'       => __( 'Instructions', 'cepatlakoo' ),
                'type'        => 'textarea',
                'description' => __( 'Instructions that will be added to the thank you page and emails.', 'cepatlakoo' ),
                'default'     => __( 'Harap transfer sesuai total nominal pembayaran ke rekening bank berikut.', 'cepatlakoo' ),
                'desc_tip'    => true,
            ),
            'account_details' => array(
				'type' => 'account_details',
			)
        ) );
    }
    
	/**
	 * Generate account details html.
	 *
	 * @return string
	 */
	public function generate_account_details_html() {

		ob_start();

		// Get sortcode label in the $locale array and use appropriate one.
		$sortcode = __( 'Bank code', 'cepatlakoo' );

		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php esc_html_e( 'Account details:', 'cepatlakoo' ); ?></th>
			<td class="forminp" id="bacs_accounts">
				<div class="wc_input_table_wrapper">
					<table class="widefat wc_input_table sortable" cellspacing="0">
						<thead>
							<tr>
								<th class="sort">&nbsp;</th>
								<th><?php esc_html_e( 'Account name', 'cepatlakoo' ); ?></th>
								<th><?php esc_html_e( 'Account number', 'cepatlakoo' ); ?></th>
								<th><?php esc_html_e( 'Bank name', 'cepatlakoo' ); ?></th>
								<th><?php echo esc_html( $sortcode ); ?></th>
								<th><?php esc_html_e( 'IBAN', 'cepatlakoo' ); ?></th>
								<th><?php esc_html_e( 'BIC / Swift', 'cepatlakoo' ); ?></th>
							</tr>
						</thead>
						<tbody class="accounts">
							<?php
							$i = -1;
							if ( $this->account_details ) {
								foreach ( $this->account_details as $account ) {
									$i++;

									echo '<tr class="account">
										<td class="sort"></td>
										<td><input type="text" value="' . esc_attr( wp_unslash( $account['account_name'] ) ) . '" name="bacs_account_name[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['account_number'] ) . '" name="bacs_account_number[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( wp_unslash( $account['bank_name'] ) ) . '" name="bacs_bank_name[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['sort_code'] ) . '" name="bacs_sort_code[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['iban'] ) . '" name="bacs_iban[' . esc_attr( $i ) . ']" /></td>
										<td><input type="text" value="' . esc_attr( $account['bic'] ) . '" name="bacs_bic[' . esc_attr( $i ) . ']" /></td>
									</tr>';
								}
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="7"><a href="#" class="add button"><?php esc_html_e( '+ Add account', 'cepatlakoo' ); ?></a> <a href="#" class="remove_rows button"><?php esc_html_e( 'Remove selected account(s)', 'cepatlakoo' ); ?></a></th>
							</tr>
						</tfoot>
					</table>
				</div>
				<script type="text/javascript">
					jQuery(function() {
						jQuery('#bacs_accounts').on( 'click', 'a.add', function(){

							var size = jQuery('#bacs_accounts').find('tbody .account').length;

							jQuery('<tr class="account">\
									<td class="sort"></td>\
									<td><input type="text" name="bacs_account_name[' + size + ']" /></td>\
									<td><input type="text" name="bacs_account_number[' + size + ']" /></td>\
									<td><input type="text" name="bacs_bank_name[' + size + ']" /></td>\
									<td><input type="text" name="bacs_sort_code[' + size + ']" /></td>\
									<td><input type="text" name="bacs_iban[' + size + ']" /></td>\
									<td><input type="text" name="bacs_bic[' + size + ']" /></td>\
								</tr>').appendTo('#bacs_accounts table tbody');

							return false;
						});
					});
				</script>
			</td>
		</tr>
		<?php
		return ob_get_clean();

	}

	/**
	 * Save account details table.
	 */
	public function save_account_details() {

		$accounts = array();

		// phpcs:disable WordPress.CSRF.NonceVerification.NoNonceVerification -- Nonce verification already handled in WC_Admin_Settings::save()
		if ( isset( $_POST['bacs_account_name'] ) && isset( $_POST['bacs_account_number'] ) && isset( $_POST['bacs_bank_name'] )
			 && isset( $_POST['bacs_sort_code'] ) && isset( $_POST['bacs_iban'] ) && isset( $_POST['bacs_bic'] ) ) {

			$account_names   = wc_clean( wp_unslash( $_POST['bacs_account_name'] ) );
			$account_numbers = wc_clean( wp_unslash( $_POST['bacs_account_number'] ) );
			$bank_names      = wc_clean( wp_unslash( $_POST['bacs_bank_name'] ) );
			$sort_codes      = wc_clean( wp_unslash( $_POST['bacs_sort_code'] ) );
			$ibans           = wc_clean( wp_unslash( $_POST['bacs_iban'] ) );
			$bics            = wc_clean( wp_unslash( $_POST['bacs_bic'] ) );

			foreach ( $account_names as $i => $name ) {
				if ( ! isset( $account_names[ $i ] ) ) {
					continue;
				}

				$accounts[] = array(
					'account_name'   => $account_names[ $i ],
					'account_number' => $account_numbers[ $i ],
					'bank_name'      => $bank_names[ $i ],
					'sort_code'      => $sort_codes[ $i ],
					'iban'           => $ibans[ $i ],
					'bic'            => $bics[ $i ],
				);
			}
		}
		// phpcs:enable

		update_option( 'cepatlakoo_bank_accounts_'.$this->id, $accounts );
	}
    
	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id Order ID.
	 */
    public function thankyou_page( $order_id ) {
        if ( $this->instructions ) {
            echo '<p class="bank-instruction">'. wptexturize( $this->instructions ) .'</p>';
		}
		$this->bank_details( $order_id );
    }

    /**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
        if ( ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
			if( $this->instructions ){
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
			$this->bank_details( $order->get_id() );
		}
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @return array
     */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );
        
        // Mark as on-hold (we're awaiting the payment)
        $order->update_status( 'on-hold', __( 'Awaiting Transfer', 'cepatlakoo' ) );
        
        // Reduce stock levels
        $order->reduce_order_stock();
        
        // Remove cart
        WC()->cart->empty_cart();
        
        // Return thankyou redirect
        return array(
            'result' 	=> 'success',
            'redirect'	=> $this->get_return_url( $order )
        );
	}
	
	/**
	 * Get bank details and place into a list format.
	 *
	 * @param int $order_id Order ID.
	 */
	private function bank_details( $order_id = '' ) {

		if ( empty( $this->account_details ) ) {
			return;
		}
		
		// Get order and store in $order.
		$order = wc_get_order( $order_id );

		// Get sortcode label in the $locale array and use appropriate one.
		$sortcode = __( 'Bank code', 'cepatlakoo' );

		$bacs_accounts = get_option( 'cepatlakoo_bank_accounts_'.$this->id );

		if ( ! empty( $bacs_accounts ) ) {
			$account_html = '';
			$has_details  = false;

			foreach ( $bacs_accounts as $bacs_account ) {
				$bacs_account = (object) $bacs_account;

				if ( $bacs_account->account_name ) {
					$account_html .= '<h3 class="wc-bacs-bank-details-account-name">' . wp_kses_post( wp_unslash( $bacs_account->bank_name ) ) . '</h3>' . PHP_EOL;
				}

				$account_html .= '<ul class="wc-bacs-bank-details order_details bacs_details">' . PHP_EOL;

				// BACS account fields shown on the thanks page and in emails.
				$account_fields = apply_filters(
					'woocommerce_bacs_account_fields', array(
						'account_number' => array(
							'label' => __( 'Account number', 'cepatlakoo' ),
							'value' => $bacs_account->account_number,
						),
						'account_name'      => array(
							'label' => __( 'Account name', 'cepatlakoo' ),
							'value' => $bacs_account->account_name,
						),
						'sort_code'      => array(
							'label' => $sortcode,
							'value' => $bacs_account->sort_code,
						),
						'iban'           => array(
							'label' => __( 'IBAN', 'cepatlakoo' ),
							'value' => $bacs_account->iban,
						),
						'bic'            => array(
							'label' => __( 'BIC', 'cepatlakoo' ),
							'value' => $bacs_account->bic,
						),
					), $order_id
				);

				foreach ( $account_fields as $field_key => $field ) {
					if( ! empty( $field['value'] ) && $field_key == 'account_number' && did_action('woocommerce_email_before_order_table') == 0 ){
						$account_html .= '<li class="' . esc_attr( $field_key ) . ' cl-copy-rek-value" data-rek="'.wp_kses_post( wptexturize( $field['value'] ) ).'">' . wp_kses_post( $field['label'] ) . ': <strong>' . wp_kses_post( wptexturize( $field['value'] ) ) . '<button class="cl-copy-rekening">'. __( 'Copy', 'cepatlakoo' ). '</button></strong></li>' . PHP_EOL;
						$has_details   = true;
					}
					else if ( ! empty( $field['value'] ) ) {
						$account_html .= '<li class="' . esc_attr( $field_key ) . '">' . wp_kses_post( $field['label'] ) . ': <strong>' . wp_kses_post( wptexturize( $field['value'] ) ) . '</strong></li>' . PHP_EOL;
						$has_details   = true;
					}
				}

				$account_html .= '</ul>';
			}

			if ( $has_details ) {
				echo '<section class="woocommerce-bacs-bank-details"><h2 class="wc-bacs-bank-details-heading">' . esc_html__( 'Our bank details', 'cepatlakoo' ) . '</h2>' . $account_html . '</section>';
			}
		}

	}
}

add_action('after_setup_theme', 'cl_bank_payment_gateway_init');
function cl_bank_payment_gateway_init() {
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

    class CL_Bank_Payment_BRI extends Cepatlakoo_Bank_Payment{
        public function __construct() {
			$bank_id = 'cl_payment_bri';
			$bank_icon = get_template_directory_uri() . '/assets/images/svg/banks/bri.svg';
            $bank_title = 'Bank BRI';
            $bank_desc = __( 'You can setup offline payment method using Bank BRI transfer', 'cepatlakoo' );
            parent::__construct($bank_id, $bank_icon, $bank_title, $bank_desc);
        }
    }
    class CL_Bank_Payment_BCA extends Cepatlakoo_Bank_Payment{
        public function __construct() {
            $bank_id = 'cl_payment_bca';
            $bank_icon = get_template_directory_uri() . '/assets/images/svg/banks/bca.svg';
            $bank_title = 'Bank BCA';
            $bank_desc = __( 'You can setup offline payment method using Bank BCA transfer', 'cepatlakoo' );
            parent::__construct($bank_id, $bank_icon, $bank_title, $bank_desc);
        }
    }
    class CL_Bank_Payment_BNI extends Cepatlakoo_Bank_Payment{
        public function __construct() {
			$bank_id = 'cl_payment_bni';
			$bank_icon = get_template_directory_uri() . '/assets/images/svg/banks/bni.svg';
            $bank_title = 'Bank BNI';
            $bank_desc = __( 'You can setup offline payment method using Bank BNI transfer', 'cepatlakoo' );
            parent::__construct($bank_id, $bank_icon, $bank_title, $bank_desc);
        }
    }
    class CL_Bank_Payment_Mandiri extends Cepatlakoo_Bank_Payment{
        public function __construct() {
			$bank_id = 'cl_payment_mandiri';
			$bank_icon = get_template_directory_uri() . '/assets/images/svg/banks/mandiri.svg';
            $bank_title = 'Bank Mandiri';
            $bank_desc = __( 'You can setup offline payment method using Bank Mandiri transfer', 'cepatlakoo' );
            parent::__construct($bank_id, $bank_icon, $bank_title, $bank_desc);
        }
    }
    
    /**
     * Add the Gateway to WooCommerce
     **/

    if ( ! function_exists( 'cl_woocommerce_add_gateway_name_gateway' ) ){
        function cl_woocommerce_add_gateway_name_gateway($methods) {
            $methods[] = 'CL_Bank_Payment_BCA';
            $methods[] = 'CL_Bank_Payment_BRI';
            $methods[] = 'CL_Bank_Payment_BNI';
            $methods[] = 'CL_Bank_Payment_Mandiri';
            return $methods;
        }
        add_filter('woocommerce_payment_gateways', 'cl_woocommerce_add_gateway_name_gateway' );
    }

}