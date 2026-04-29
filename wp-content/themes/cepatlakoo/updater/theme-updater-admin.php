<?php
/**
 * Theme updater admin page and functions.
 *
 * @package EDD Sample Theme
 */

class EDD_Theme_Updater_Admin {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	 protected $remote_api_url = null;
	 protected $theme_slug = null;
	 protected $version = null;
	 protected $author = null;
	 protected $download_id = null;
	 protected $renew_url = null;
	 protected $strings = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function __construct( $config = array(), $strings = array() ) {
		$def_config = $config;
		$config = wp_parse_args( $config, array(
			'remote_api_url' => 'https://cepatlakoo.com', // Site where EDD is hosted
			'item_name'      => THEME_NAME, // Name of theme
			'theme_slug'     => THEME_SLUG, // Theme slug
			'version'        => THEME_VERSION, // The current version of this theme
			'author'         => 'Cepatlakoo', // The author of this theme
			'download_id'    => '126', // Optional, used for generating a license renewal link
			'renew_url'      => '', // Optional, allows for a custom license renewal link
			'beta'           => false, // Optional, set to true to opt into beta versions
		) );

		$strings = wp_parse_args( $strings, array(
			'theme-license'             => __( 'Lisensi Theme', 'cepatlakoo' ),
			'enter-key'                 => __( 'Masukkan kode lisensi Anda.', 'cepatlakoo' ),
			'license-key'               => __( 'Kode Lisensi', 'cepatlakoo' ),
			'license-action'            => __( 'License Action', 'cepatlakoo' ),
			'deactivate-license'        => __( 'Nonaktifkan Lisensi', 'cepatlakoo' ),
			'activate-license'          => __( 'Aktifkan Lisensi', 'cepatlakoo' ),
			'status-unknown'            => __( 'Status lisensi tidak diketahui.', 'cepatlakoo' ),
			'renew'                     => __( 'Perpanjang lisensi?', 'cepatlakoo' ),
			'unlimited'                 => __( 'unlimited', 'cepatlakoo' ),
			'license-key-is-active'     => __( 'Kode lisensi aktif.', 'cepatlakoo' ),
			'expires%s'                 => __( 'Akan kadaluarsa pada %s.', 'cepatlakoo' ),
			'expires-never'             => __( 'Lisensi Lifetime.', 'cepatlakoo' ),
			'%1$s/%2$-sites'            => __( 'Anda mengaktifkan <span class="active-sites">%1$s / %2$s</span> website.', 'cepatlakoo' ),
			'license-key-expired-%s'    => __( 'Kode lisensi kadaluarsa pada %s.', 'cepatlakoo' ),
			'license-key-expired'       => __( 'Kode lisensi telah kadaluarsa.', 'cepatlakoo' ),
			'license-keys-do-not-match' => __( 'Kode lisensi tidak cocok.', 'cepatlakoo' ),
			'license-is-inactive'       => __( 'Lisensi tidak aktif.', 'cepatlakoo' ),
			'license-key-is-disabled'   => __( 'Kode lisensi telah didisable..', 'cepatlakoo' ),
			'site-is-inactive'          => __( 'Website tidak aktif.', 'cepatlakoo' ),
			'license-status-unknown'    => __( 'Status lisensi tidak diketahui.', 'cepatlakoo' ),
			'update-notice'             => __( "Mengupdate theme ini akan menghapus semua perubahan pada file theme yang telah Anda lakukan. Klik 'Cancel' untuk membatalkan, klik 'OK' untuk mengupdate.", 'cepatlakoo' ),
			'update-available'          => __('<strong>%1$s %2$s</strong> tersedia. <a href="%3$s" class="thickbox" title="%4s">Cek apa saja yang baru</a> atau <a href="%5$s"%6$s>update sekarang</a>.', 'cepatlakoo' ),
			'cl-sites-limited'    		=> __( 'Lisensi Anda telah mencapai batas pemakaian maksimal.', 'cepatlakoo' ),
		) );

		// Set config arguments
		$this->remote_api_url = $config['remote_api_url'];
		$this->item_name = $config['item_name'];
		$this->theme_slug = sanitize_key( $config['theme_slug'] );
		$this->version = $config['version'];
		$this->author = $config['author'];
		$this->download_id = $config['download_id'];
		$this->renew_url = $config['renew_url'];
		$this->beta = $config['beta'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		if( !empty($def_config) ){
			add_action( 'init', array( $this, 'updater' ) );
			add_action( 'admin_init', array( $this, 'register_option' ) );
			add_action( 'admin_init', array( $this, 'license_action' ) );
			add_action( 'admin_menu', array( $this, 'license_menu' ) );
			add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
			add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );
			add_action( 'admin_notices', array( $this, 'cl_check_license' ) );
		}
	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	function updater() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		/* If there is no valid license key status, don't allow updates. */
		if ( get_option( $this->theme_slug . '_license_key_status', false) != 'valid' ) {
			return;
		}

		if ( !class_exists( 'EDD_Theme_Updater' ) ) {
			// Load our custom theme updater
			include( dirname( __FILE__ ) . '/theme-updater-class.php' );
		}

		new EDD_Theme_Updater(
			array(
				'remote_api_url' 	=> $this->remote_api_url,
				'version' 			=> $this->version,
				'license' 			=> trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_name' 		=> $this->item_name,
				'author'			=> $this->author,
				'beta'              => $this->beta
			),
			$this->strings
		);
	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function license_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['theme-license'],
			$strings['theme-license'],
			'manage_options',
			$this->theme_slug . '-license',
			array( $this, 'license_page' )
		);
	}

	/**
	 * Outputs the markup used on the theme license page.
	 *
	 * since 1.0.0
	 */
	function license_page() {

		$strings = $this->strings;

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status = get_option( $this->theme_slug . '_license_key_status', false );
		
		// Checks license status to display under license key
		if ( ! $license ) {
			$message    = $strings['enter-key'];
		} else {
			// delete_transient( $this->theme_slug . '_license_message' );
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}
		?>
		<div class="wrap">
			<h2><?php echo $strings['theme-license'] ?></h2>
			<form method="post" action="options.php">

				<?php settings_fields( $this->theme_slug . '-license' ); ?>

				<table class="form-table">
					<tbody>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php echo $strings['license-key']; ?>
							</th>
							<td>
								<input id="<?php echo $this->theme_slug; ?>_license_key" name="<?php echo $this->theme_slug; ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" <?php echo ($status == 'valid') ? 'readonly' : ''; ?>/>
								<p class="description">
									<?php echo $message; ?>
								</p>
							</td>
						</tr>

						<?php if ( $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php echo $strings['license-action']; ?>
							</th>
							<td>
								<?php
								wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
								if ( 'valid' == $status ) { ?>
									<input type="submit" class="button-secondary" name="<?php echo $this->theme_slug; ?>_license_deactivate" value="<?php esc_attr_e( $strings['deactivate-license'] ); ?>"/>
								<?php } else { ?>
									<input type="submit" class="button-secondary" name="<?php echo $this->theme_slug; ?>_license_activate" value="<?php esc_attr_e( $strings['activate-license'] ); ?>"/>
								<?php }
								?>
							</td>
						</tr>
						<?php } ?>

					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		<?php
	}

	/**
	 * Registers the option used to store the license key in the options table.
	 *
	 * since 1.0.0
	 */
	function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);
	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 * @return string $new Sanitized license key.
	 */
	function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old != $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
			delete_transient( 'cl-ongkir-check_license' );
		}

		return $new;
	}

	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	 function get_api_response( $api_params ) {

		// Call the custom API.
		$response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			wp_die( $response->get_error_message(), __( 'Error' ) . $response->get_error_code() );
		}

		return $response;
	 }

	/**
	 * Activates the license key.
	 *
	 * @since 1.0.0
	 */
	function activate_license() {
		delete_transient( 'cepatlakoo_license_status' );
		update_option( $this->theme_slug . '_license_key', $_POST[$this->theme_slug.'_license_key'] );
		$license = $_POST[$this->theme_slug.'_license_key'];
		// exit();
		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url()
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'cepatlakoo' );
			}

		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $args['name'] );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					case 'cl-sites-limited':

						$message = __( 'Your license key has reached domain activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

				if ( ! empty( $message ) ) {
					$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
					$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

					wp_redirect( $redirect );
					exit();
				}

			}

		}

		// $response->license will be either "active" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			delete_transient( $this->theme_slug . '_license_message' );
			delete_transient( 'cepatlakoo_license_status' );
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( $this->item_name ),
			'url'        => home_url()
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.'. 'cepatlakoo' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data && ( $license_data->license == 'deactivated' ) ) {
				delete_option( $this->theme_slug . '_license_key_status' );
				delete_transient( $this->theme_slug . '_license_message' );
				delete_transient( 'cepatlakoo_license_status' );
			}

		}

		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
			$redirect = add_query_arg( array( 'sl_theme_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		wp_redirect( admin_url( 'themes.php?page=' . $this->theme_slug . '-license' ) );
		exit();

	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	function get_renewal_link() {

		// If a renewal link was passed in the config, use that
		if ( '' != $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
		if ( '' != $this->download_id && $license_key ) {
			$url = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
			return $url;
		}

		// Otherwise return the remote_api_url
		return $this->remote_api_url;

	}



	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[$this->theme_slug . '_license_deactivate'] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}

	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 * @return string $message License status message.
	 */
	function check_license( $raw = false ) {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;
		
		if( !empty($license) ){
			$api_params = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->item_name ),
				'url'        => home_url()
			);

			if( ! get_transient( 'cepatlakoo_license_status', false ) ){
				$response = $this->get_api_response( $api_params );
				set_transient( 'cepatlakoo_license_status', $response, ( 60 * 60 * 24 ) );
			}
			else{
				$response = get_transient( 'cepatlakoo_license_status' );
			}

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = $strings['license-status-unknown'];
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				
				// If response doesn't include license data, return
				if ( !isset( $license_data->license ) ) {
					$message = $strings['license-status-unknown'];
					return $message;
				}

				// We need to update the license status at the same time the message is updated
				if ( $license_data && isset( $license_data->license ) ) {
					update_option( $this->theme_slug . '_license_key_status', $license_data->license );
				}

				// Get expire date
				$expires = false;
				$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
				if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
					$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
				} elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
					$expires = 'lifetime';
				}

				// Get site counts
				$site_count = isset($license_data->site_count) ? $license_data->site_count : 0 ;
				$license_limit = isset($license_data->license_limit) ? $license_data->license_limit : 0;

				// If unlimited
				if ( 0 == $license_limit ) {
					$license_limit = $strings['unlimited'];
				}

				if ( $license_data->license == 'valid' ) {
					$message = $strings['license-key-is-active'] . ' ';
					if ( isset( $expires ) && 'lifetime' != $expires ) {
						$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
					}
					if ( isset( $expires ) && 'lifetime' == $expires ) {
						$message .= $strings['expires-never'];
					}
					if ( $site_count && $license_limit ) {
						$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit ).'<br>'.$renew_link;
					}
				} else if ( $license_data->license == 'expired' ) {
					if ( $expires ) {
						$message = sprintf( $strings['license-key-expired-%s'], $expires );
					} else {
						$message = $strings['license-key-expired'];
					}
					if ( $renew_link ) {
						$message .= ' ' . $renew_link;
					}
				} else if ( $license_data->license == 'invalid' ) {
					$message = $strings['license-keys-do-not-match'];
				} else if ( $license_data->license == 'inactive' ) {
					$message = $strings['license-is-inactive'];
				} else if ( $license_data->license == 'disabled' ) {
					$message = $strings['license-key-is-disabled'];
				} else if ( $license_data->license == 'site_inactive' ) {
					// Site is inactive
					$message = $strings['site-is-inactive'];
				} else if ( $license_data->license == 'cl-sites-limited' ) {
					// Domain limited
					$message = $strings['cl-sites-limited'];
				} else {
					$message = $strings['license-status-unknown'];
				}

			}
		}
		else{
			$license_data = (object)[];
			$license_data->license = 'empty';
			$message = __( 'Kode lisensi belum diaktifkan. Silahkan aktifkan kode lisensi theme Anda sekarang.', 'cepatlakoo' );
		}

		if($raw){
			return array('raw' => $license_data, 'msg' => $message);
		}
		else{
			return $message;
		}
	}

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
 			return $r;
 		}

 		// Decode the JSON response
 		$themes = json_decode( $r['body']['themes'] );

 		// Remove the active parent and child themes from the check
 		$parent = get_option( 'template' );
 		$child = get_option( 'stylesheet' );
 		unset( $themes->themes->$parent );
 		unset( $themes->themes->$child );

 		// Encode the updated JSON response
 		$r['body']['themes'] = json_encode( $themes );

 		return $r;
	}

	/**
	 * Function for show license notification
	 *
	 * @since 1.4.2
	 */
	function cl_check_license() {
		$check = $this->check_license( true );
		
		if ( $check['raw']->license == 'empty' ) : ?>
			<div class="notice notice-license">
				<div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cepatlakoo-icon.png" alt=""/></div>

				<div class="content">
					<p>
						<strong><?php esc_html_e('Lisensi Cepatlakoo'); ?></strong> <br />
						<?php echo $check['msg'] ?>
					</p>
				</div>

				<div class="cta">
					<a href="<?php echo admin_url('themes.php?page=cepatlakoo-license'); ?>" class="button cl-button"><?php esc_html_e( 'Aktifkan Lisensi' ); ?></a>
				</div>
			</div>
		<?php elseif ( $check['raw']->license == 'expired' ) : ?>
			<div class="notice notice-license notice-danger">
				<div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cepatlakoo-icon.png" alt=""/></div>

				<div class="content">
					<p><strong><?php esc_html_e('Kode Lisensi Cepatlakoo Anda Telah Kadaluarsa'); ?></strong> <br />
					<?php esc_html_e( 'Silahkan perpanjang lisensi Anda agar tetap bisa mendapatkan versi-versi terbaru dari tema ini dan dapatkan diskon spesial untuk perpanjang lisensi.' ); ?></p>
				</div>

				<div class="cta">
					<a href="<?php echo esc_url( $this->get_renewal_link() ); ?>" target="_blank" class="button cl-button"><?php esc_html_e( 'Perpanjang Lisensi' ); ?></a>
				</div>
			</div>
		<?php elseif ( $check['raw']->license != 'valid' ) : ?>
			<div class="notice notice-license">
				<div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cepatlakoo-icon.png" alt=""/></div>

				<div class="content">
					<p>
						<strong><?php esc_html_e('Cepatlakoo License'); ?></strong> <br />
						<?php echo $check['msg'] ?>
					</p>
				</div>

				<div class="cta">
					<a href="<?php echo admin_url('themes.php?page=cepatlakoo-license'); ?>" class="button cl-button"><?php esc_html_e( 'Aktifkan Lisensi' ); ?></a>
				</div>
			</div>
		<?php elseif ( $check['raw']->license == 'valid' && $check['raw']->expires != 'lifetime' && strtotime($check['raw']->expires) < strtotime('+1 month') ) : ?>
		<?php //elseif ( $check['raw']->license == 'valid' && strtotime('2019-05-20') < strtotime('+1 month') ) : ?>
			<div class="notice notice-license notice-warning">
				<div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cepatlakoo-icon.png" alt=""/></div>

				<div class="content">
					<p><strong><?php esc_html_e('Kode Lisensi Cepatlakoo Anda Akan Segera Kadaluarsa'); ?></strong> <br />
					<?php esc_html_e( 'Silahkan perpanjang lisensi Anda agar tetap bisa mendapatkan versi-versi terbaru dari tema ini dan dapatkan diskon spesial untuk perpanjang lisensi.' ); ?>
					
					<a href="<?php echo admin_url('/themes.php?page=cepatlakoo-license'); ?>"><?php esc_html_e( 'Cek masa aktif lisensi Anda' ); ?></a>
					</p>
				</div>

				<div class="cta">
					<a href="<?php echo esc_url( $this->get_renewal_link() ); ?>" target="_blank" class="button cl-button"><?php esc_html_e( 'Perpanjang Lisensi' ); ?></a>
				</div>
			</div>
		<?php endif; 
	}
}