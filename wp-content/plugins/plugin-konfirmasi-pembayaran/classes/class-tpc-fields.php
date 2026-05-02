<?php

/**
 * TPC Fields
 */
class TPC_Fields {

	/**
	 * Fields
	 * 
	 * @var array
	 */
	private $fields;

	/**
	 * Setting object
	 * 
	 * @var TPC_Setting
	 */
	private $setting;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setting = new TPC_Setting();
		$this->set_defaults();
	}

	/**
	 * Set default fields
	 */
	public function set_defaults() {
		$this->fields = apply_filters( 'tpc_default_fields', array(
			'order_id'	=> array(
				'name'		=> __( 'Order ID', 'tpc' ),
				'required'	=> true,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_order_id' )
			),
			'customer_email'	=> array(
				'name'		=> __( 'Customer Email', 'tpc' ),
				'required'	=> true,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_customer_email' )
			),
			'customer_name'	=> array(
				'name'		=> __( 'Customer Name', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_customer_name' )
			),
			'transfer_date'	=> array(
				'name'		=> __( 'Transfer Date', 'tpc' ),
				'required'	=> false,
				'callback'		=> array( $this, 'render_field_transfer_date' )
			),
			'amount'	=> array(
				'name'			=> __( 'Transfer Amount', 'tpc' ),
				'required'		=> false,
				'additional' 	=> array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_amount' )
			),
			'bank'	=> array(
				'name'		=> __( 'Bank', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'options'	=> array(
						'label'	=> __( 'Bank options (separate with comma)', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_bank' )
			),
			'customer_bank'	=> array(
				'name'		=> __( 'Customer Bank', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_customer_bank' )
			),
			'customer_account'	=> array(
				'name'		=> __( 'Customer Bank Account', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_customer_account' )
			),
			'file'	=> array(
				'name'		=> __( 'Attached Photo', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'maxsize'	=> array(
						'label'		=> __( 'Max file size', 'tpc' ),
						'type'		=> 'number'
					)
				),
				'callback'		=> array( $this, 'render_field_file' )
			),
			'note'	=> array(
				'name'		=> __( 'Note', 'tpc' ),
				'required'	=> false,
				'additional' => array(
					'placeholder' => array(
						'label'	=> __( 'Placeholder', 'tpc' ),
						'type'	=> 'text'
					)
				),
				'callback'		=> array( $this, 'render_field_note' )
			),
			'recaptcha'	=> array(
				'name'			=> __( 'Recaptcha V2', 'tpc' ),
				'required'		=> true,
				'additional' => array(
					'sitekey' => array(
						'label'	=> __( 'Site Key', 'tpc' ),
						'type'	=> 'text'
					),
					'secretkey' => array(
						'label'	=> __( 'Secret Key', 'tpc' ),
						'type'	=> 'password'
					)
				),
				'callback'		=> array( $this, 'render_field_recaptcha' )
			)
		) );
	}

	/**
	 * Get all fields
	 * 
	 * @return array Fields.
	 */
	public function get_all() {
		return $this->fields;
	}

	/**
	 * Get all used fields
	 * 
	 * @return array Field keys.
	 */
	public function get_used_fields() {
		$fields = array();
		$form = $this->setting->get('form_html');
		foreach ( array_keys( $this->fields ) as $field ) {
			if ( false !== stripos( $form, '[' . $field . ']' ) ) {
				$fields[] = $field;
			}
		}
		return $fields;
	}

	/**
	 * Get all required fields
	 */
	public function get_required_fields() {
		$fields = array();
		$settings = $this->setting->get_all();
		foreach ( $this->fields as $key => $value) {
			if ( $settings[ 'field_' . $key . '_required' ] ) {
				$fields[] = $key;
			}
		}
		return $fields;
	}

	/**
	 * Render html fields
	 * 
	 * @param  string $html Form HTML.
	 */
	public function render_fields( $html ) {
		foreach ( $this->fields as $key => $field ) {
			if ( is_callable( $field['callback'] ) ) {
				ob_start();
				call_user_func( $field['callback'] );
				$html = str_replace( '[' . $key . ']', ob_get_clean(), $html );
			}
		}
		echo $html;
	}

	/**
	 * Render Order ID field
	 */
	public function render_field_order_id() {
		if ( is_user_logged_in() ) {
			$orders_on_hold = wc_get_orders( array(
				'status'		=> 'on-hold',
				'customer_id'	=> get_current_user_id()
			) );
			$orders_pending = wc_get_orders( array(
				'status'		=> 'pending',
				'customer_id'	=> get_current_user_id()
			) );
			$orders_waiting_confirm = wc_get_orders( array(
				'status'		=> 'waiting-confirm',
				'customer_id'	=> get_current_user_id()
			) );
			$orders = array_merge( (array) $orders_on_hold, (array) $orders_pending, (array) $orders_waiting_confirm );
			$options = array();
			foreach ( $orders as $order ) {
				$options[ $order->get_id() ] = '#' . $order->get_id() . ' (' . $order->get_date_created()->date_i18n( get_option('date_format') ) . ') - ' . wc_get_order_status_name( $order->get_status() );
			}
			?>
			<select name="order_id" class="tpc-field <?php echo $this->get_class('order_id'); ?>" required>
				<?php foreach ( $options as $key => $value ) : ?>
					<option value="<?php echo esc_attr( $key ) ?>" <?php echo isset( $_GET['order_id'] ) && intval( $_GET['order_id'] ) === $key ? 'selected' : '' ?>><?php echo esc_html( $value ) ?></option>
				<?php endforeach; ?>
			</select>
			<?php
		} else {
			?>
			<input type="number" name="order_id" class="tpc-field <?php echo $this->get_class('order_id'); ?>" placeholder="<?php echo $this->get_placeholder('order_id') ?>" required>
			<?php
		}
	}

	/**
	 * Render customer email field
	 */
	public function render_field_customer_email() {
		$email = '';
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$email = $user->user_email;
			if ( isset( $_GET['order_id'] ) && 0 !== intval( $_GET['order_id'] ) ) {
				if ( $order = wc_get_order( $_GET['order_id'] ) ) {
					$email = $order->get_billing_email();
				}
			}			
		}
		?>
		<input type="email" name="customer_email" class="tpc-field <?php echo $this->get_class('customer_email'); ?>" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php echo $this->get_placeholder('customer_email') ?>" required>
		<?php
	}

	/**
	 * Render customer name field
	 */
	public function render_field_customer_name() {
		$this->render_general_field( 'customer_name' );
	}

	/**
	 * Render transfer date field
	 */
	public function render_field_transfer_date() {
		?>
		<input type="text" name="transfer_date" class="tpc-field tpc-datepicker <?php echo $this->get_class('transfer_date'); ?>" placeholder="<?php echo $this->get_placeholder('transfer_date') ?>" <?php $this->is_required('transfer_date'); ?>>
		<?php
	}

	/**
	 * Render amount field
	 */
	public function render_field_amount() {
		$this->render_general_field( 'amount', 'number' );
	}

	/**
	 * Render bank field
	 */
	public function render_field_bank() {
		$options = explode( ',', $this->setting->get('field_bank_options') );
		$options = array_map( 'trim', $options );
		?>
		<select name="bank" class="tpc-field <?php echo $this->get_class('bank'); ?>" <?php $this->is_required('bank'); ?>>
			<?php foreach ( $options as $option ) : ?>
				<option value="<?php echo esc_attr( $option ) ?>"><?php echo esc_html( $option ) ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render customer bank field
	 */
	public function render_field_customer_bank() {
		$this->render_general_field( 'customer_bank' );
	}

	/**
	 * Render customer account field
	 */
	public function render_field_customer_account() {
		$this->render_general_field( 'customer_account' );
	}

	/**
	 * Render file field
	 */
	public function render_field_file() {
		?>
		<input type="file" name="file" class="tpc-field tpc-file <?php echo $this->get_class('file'); ?>" <?php $this->is_required('file'); ?> accept="image/*">
		<?php
	}

	/**
	 * Render file field
	 */
	public function render_field_note() {
		?>
		<textarea name="note" class="tpc-field <?php echo $this->get_class('note'); ?>" placeholder="<?php echo $this->get_placeholder('note') ?>" <?php $this->is_required('note'); ?>></textarea>
		<?php
	}

	/**
	 * Render recaptcha field
	 */
	public function render_field_recaptcha() {
		$sitekey = $this->setting->get('field_recaptcha_sitekey');
		$secretkey = $this->setting->get('field_recaptcha_secretkey');
		if ( empty( $sitekey ) || empty( $secretkey ) ) {
			return;
		}
		?>
		<div class="g-recaptcha <?php echo $this->get_class('recaptcha'); ?>" data-sitekey="<?php echo esc_attr( $sitekey ); ?>"></div>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php
	}

	/**
	 * Render general field
	 * 
	 * @param  string $key  Field key.
	 * @param  string $type Field type.
	 */
	private function render_general_field( $key, $type = 'text' ) {
		?>
		<input type="<?php echo esc_attr( $type ) ?>" name="<?php echo esc_attr( $key ) ?>" class="tpc-field <?php echo $this->get_class( $key ); ?>" placeholder="<?php echo $this->get_placeholder( $key ) ?>" <?php $this->is_required( $key ); ?> <?php echo 'number' === $type ? 'step="any"' : ''; ?>>
		<?php
	}

	/**
	 * Get field's additional classes
	 * 
	 * @param  string $key Field key.
	 * @return string      Additional classes.
	 */
	private function get_class( $key ) {
		return $this->setting->get( 'field_' . $key . '_class' );
	}

	/**
	 * Get field's placeholder
	 * 
	 * @param  string $key Field key.
	 * @return string      Placeholder.
	 */
	private function get_placeholder( $key ) {
		return $this->setting->get( 'field_' . $key . '_placeholder' );
	}

	/**
	 * Print required attribute if field is a required field
	 * 
	 * @param  string  $key Field key.
	 */
	private function is_required( $key ) {
		if ( $this->setting->get( 'field_' . $key . '_required' ) ) {
			echo 'required';
		}
	}

}
