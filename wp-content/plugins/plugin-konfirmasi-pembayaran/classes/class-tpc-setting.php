<?php

/**
 * TPC Setting
 */
class TPC_Setting {

	/**
	 * Option name
	 * 
	 * @var string
	 */
	private $option_name;

	/**
	 * Settings data
	 * 
	 * @var array
	 */
	private $setting;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->option_name 	= 'tpc_setting';
		$this->setting 		= get_option( $this->option_name, $this->get_defaults() );
	}

	/**
	 * Get default values
	 *
	 * @param  string $index Defaults index.
	 * @return mixed         Default value.
	 */
	public function get_defaults( $index = null ) {
		$defaults = apply_filters(
			'tpc_setting_defaults', array(
				'must_login'						=> '',
				'payment_methods'					=> array('bacs'),
				'confirmed_status'					=> 'wc-processing',
				'rejected_status'					=> 'wc-on-hold',
				'redirection'						=> '',
				'submission_limit'					=> '',
				'report_columns'					=> array( 'order_date', 'order_total', 'order_status', 'payment_status' ),
				'thankyou_content'					=> '<p>Setelah Anda menyelesaikan pembayaran, pastikan untuk melakukan konfirmasi pada halaman berikut:</p>
<a href="[confirmation_url]" class="button tpc-button">Klik disini untuk konfirmasi pembayaran</a>',
				'onhold_email_content'				=> '<p>Setelah Anda menyelesaikan pembayaran, pastikan untuk melakukan konfirmasi pada halaman berikut:</p>
<a href="[confirmation_url]" class="button tpc-button">Klik disini untuk konfirmasi pembayaran</a>',
				'form_html'							=> '<p>
    <label>Nomor Order *</label>
    [order_id]
</p>
<p>
    <label>Email *</label>
    [customer_email]
</p>
<p>
    <label>Bukti Transfer (jpg/png) *</label>
    [file]
</p>',
				'form_css'							=> '',
				'field_order_id_required'			=> '1',
				'field_order_id_class'				=> '',
				'field_order_id_placeholder'		=> '',
				'field_customer_email_required'		=> '1',
				'field_customer_email_class'		=> '',
				'field_customer_email_placeholder'	=> '',
				'field_customer_name_required'		=> '',
				'field_customer_name_class'			=> '',
				'field_customer_name_placeholder'	=> '',
				'field_transfer_date_required'		=> '',
				'field_transfer_date_class'			=> '',
				'field_amount_required'				=> '',
				'field_amount_class'				=> '',
				'field_amount_placeholder'			=> '',
				'field_bank_required'				=> '',
				'field_bank_class'					=> '',
				'field_bank_options'				=> '',
				'field_customer_bank_required'		=> '',
				'field_customer_bank_class'			=> '',
				'field_customer_bank_placeholder'	=> '',
				'field_customer_account_required'	=> '',
				'field_customer_account_class'		=> '',
				'field_customer_account_placeholder' => '',
				'field_file_required'				=> '1',
				'field_file_class'					=> '',
				'field_file_maxsize'				=> 2048,
				'field_note_required'				=> '',
				'field_note_class'					=> '',
				'field_note_placeholder'			=> '',
				'field_recaptcha_required'			=> '1',
				'field_recaptcha_class'				=> '',
				'field_recaptcha_sitekey'			=> '',
				'field_recaptcha_secretkey'			=> '',
				'mes_success'						=> 'Permintaan Anda telah berhasil terkirim.',
				'mes_error'							=> 'Terjadi kesalahan dalam mengirim permintaan.',
				'mes_error_required'				=> 'Mohon isi semua data yang diwajibkan.',
				'mes_error_email'					=> 'Mohon sertakan alamat email yang valid.',
				'mes_error_order_id'				=> 'Tidak ditemukan order dengan nomor yang dimaksud.',
				'mes_error_order_email'				=> 'Tidak ditemukan order dengan nomor dan email yang dimaksud.',
				'mes_error_order_status'			=> 'Tidak ditemukan order yang butuh konfirmasi pembayaran dengan nomor dan email yang dimaksud.',
				'mes_error_date'					=> 'Format tanggal transfer tidak valid.',
				'mes_error_file_required'			=> 'Mohon sertakan foto bukti pembayaran.',
				'mes_error_file_size'				=> 'Ukuran berkas yang dikirim melebihi batas maksimum.',
				'mes_error_file_type'				=> 'Mohon hanya sertakan berkas gambar sebagai bukti.',
				'mes_error_file_upload'				=> 'Terjadi kesalahan ketika mengunggah berkas.',
				'mes_error_recaptcha_required'		=> 'Harap buktikan jika Anda bukan robot.',
				'mes_error_recaptcha_failed'		=> 'Gagal memvalidasi captcha.',
				'mes_error_limit'					=> 'Anda sudah melebihi batas untuk melakukan konfirmasi pada order ini.',
			)
		);
		if ( ! is_null( $index ) ) {
			if ( isset( $defaults[ $index ] ) ) {
				return $defaults[ $index ];
			} else {
				return false;
			}
		}
		return $defaults;
	}

	/**
	 * Save settings
	 *
	 * @param  array $settings Setting values.
	 */
	public function save( $settings = array() ) {
		$new_value = wp_parse_args( $settings, $this->get_defaults() );
		update_option( $this->option_name, $new_value, true );
		$this->setting = get_option( $this->option_name, $this->get_defaults() );
	}

	/**
	 * Reset settings
	 */
	public function reset() {
		$this->save( $this->get_defaults() );
	}

	/**
	 * Get all settings into one array
	 *
	 * @return array Setting values
	 */
	public function get_all() {
		return wp_parse_args( $this->setting, $this->get_defaults() );
	}

	/**
	 * Get single setting
	 *
	 * @param  string $index Setting index.
	 * @return mixed         Setting value if setting exists.
	 */
	public function get( $index = '' ) {
		if ( isset( $this->setting[ $index ] ) ) {
			$value = $this->setting[ $index ];
		} else {
			$value = $this->get_defaults( $index );
		}
		return apply_filters( 'tpc_setting_get_' . $index, $value );
	}

	/**
	 * Set single setting
	 *
	 * @param string $index Setting index.
	 * @param string $value Setting value.
	 */
	public function set( $index = '', $value = '' ) {
		if ( in_array( $index, array_keys( $this->setting ), true ) ) {
			$this->setting[ $index ] = isset( $value ) ? $value : $this->get_defaults( $index );
			update_option( $this->option_name, $this->setting, true );
			$this->setting = get_option( $this->option_name, $this->get_defaults() );
		}
	}

	/**
	 * Get confirmation page
	 * @return object Confirmation page.
	 */
	public function get_confirmation_page() {
		global $wpdb;

		$url = '';

		$sql = 'SELECT ID
			FROM ' . $wpdb->posts . '
			WHERE
				post_type = "page"
				AND post_status="publish"
				AND post_content LIKE "%[payment-confirmation]%"';

		if ($id = $wpdb->get_var($sql)) {
			return get_post($id);
		}

		return false;
	}

	/**
	 * Setting migration from old version.
	 * If user previously installed version <=1.0.2, then migrate their setting to new one
	 */
	public function setting_migration() {
		$old_settings = get_option( 'tpc_settings', array() );
		$setting = array(
			'field_file_maxsize'	=> isset( $old_settings['max_file_size'] ) ? $old_settings['max_file_size'] : $this->get_defaults( 'field_file_maxsize' ),
			'mes_success'			=> isset( $old_settings['notif_success_title'] ) ? $old_settings['notif_success_title'] : $this->get_defaults( 'mes_success' ),
			'mes_error'				=> isset( $old_settings['notif_failed_title'] ) ? $old_settings['notif_failed_title'] : $this->get_defaults( 'mes_error' )
		);
		$this->save( $setting );
	}

}
