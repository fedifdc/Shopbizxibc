<?php
/**
 * Plugin Auto Update.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

/**
 * To update plugin  auto
 *
 * @class   Bdp_Wp_Auto_Update
 * @version 1.0.0
 */
class Bdp_Wp_Auto_Update {

	/**
	 * The plugin current version
	 *
	 * @var string
	 */
	public $current_version;

	/**
	 * The plugin remote update path
	 *
	 * @var string
	 */
	public $update_path;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 *
	 * @var string
	 */
	public $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/blog-designer-pro/blog-designer-pro.php', $markup = true, $translate = true );
		// Set the class public variables.
		$this->current_version = $plugin_data['Version'];
		$this->update_path     = 'https://www.solwininfotech.com/sollicweb/blog_designer_pro_check_purchase_code_tf.php';
		$this->plugin_slug     = 'blog-designer-pro/blog-designer-pro.php';
		list ($t1, $t2)        = explode( '/', $this->plugin_slug );
		$this->slug            = str_replace( '.php', '', $t2 );
		// define the alternative API for updating checking.
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update' ) );
		// Define the alternative response for information checking.
		add_filter( 'plugins_api', array( &$this, 'check_info' ), 10, 3 );
	}

	/**
	 * Store Data.
	 */
	public function store_data() {
		update_option( 'bdp_stored_data', 'yes' );
		$site_url = get_site_url();
		$site_url = str_replace( 'www.', '', str_replace( 'http://', '', str_replace( 'https://', '', $site_url ) ) );
		update_option( 'bdp_stored_website', $site_url );

		$ipaddress = '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = isset( $_SERVER['HTTP_CLIENT_IP'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) : '';
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) : '';
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = isset( $_SERVER['HTTP_X_FORWARDED'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) ) : '';
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) ) : '';
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = isset( $_SERVER['HTTP_FORWARDED'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) ) : '';
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		} else {
			$ipaddress = 'UNKNOWN';
		}
		$version = $this->current_version;

		$request = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'     => 'store_data',
					'site_url'   => $site_url,
					'ip_address' => $ipaddress,
					'version'    => $version,
				),
			)
		);
	}
	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param string $username username.
	 * @param string $purchase_code purchase code.
	 * @return $return
	 */
	public function update_license( $username, $purchase_code ) {
		$return = $this->get_remote_license( $username, $purchase_code );
		if ( 'correct' === $return ) {
			update_option( 'bdp_username', sanitize_text_field( $username ) );
			update_option( 'bdp_purchase_code', sanitize_text_field( $purchase_code ) );
		}
		return $return;
	}

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param string $transient transient.
	 * @return object $transient
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		if ( 'correct' === $this->get_remote_license() ) {
			// Get the remote version.
			$remote_version = $this->get_remote_version();
			// If a newer version is available, add the update.
			if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
				$obj                                       = new stdClass();
				$obj->slug                                 = $this->slug;
				$obj->new_version                          = $remote_version;
				$obj->url                                  = $this->update_path;
				$obj->package                              = $this->update_path;
				$transient->response[ $this->plugin_slug ] = $obj;
			}
		}
		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false false.
	 * @param array   $action action.
	 * @param object  $arg arg.
	 * @return bool|object
	 */
	public function check_info( $false, $action, $arg ) {
		if ( 'correct' === $this->get_remote_license() ) {
			if ( isset( $arg->slug ) ) {
				if ( $arg->slug === $this->slug ) {
					$information = $this->get_remote_information();
					return $information;
				}
			}
		}
		return false;
	}

	/**
	 * Return the remote version
	 *
	 * @return string $remote_version
	 */
	public function get_remote_version() {
		$request = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'  => 'version',
					'product' => $this->slug,
				),
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}
		return false;
	}

	/**
	 * Return the changelog
	 *
	 * @return string $changelog
	 */
	public function get_remote_changelog() {
		$request = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'  => 'changelog',
					'product' => $this->slug,
				),
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}
		return false;
	}

	/**
	 * Get information about the remote version
	 *
	 * @return bool|object
	 */
	public function get_remote_information() {
		$request = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'  => 'info',
					'product' => $this->slug,
				),
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return maybe_unserialize( $request['body'] );
		}
		return false;
	}

	/**
	 * Get remote notice
	 *
	 * @return bool|object
	 */
	public function get_remote_notice() {
		$request = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'  => 'message',
					'product' => $this->slug,
				),
			)
		);

		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			if ( '' != $request['body'] ) {
				return $request['body'];
			} else {
				return '';
			}
		}
		return false;
	}

	/**
	 * Return the status of the plugin licensing
	 *
	 * @param string $username username.
	 * @param string $purchase_code purchase code.
	 * @return boolean $remote_license
	 */
	public function get_remote_license( $username = '', $purchase_code = '' ) {
		if ( '' == $username ) {
			$username = get_option( 'bdp_username' );
		}
		if ( '' == $purchase_code ) {
			$purchase_code = get_option( 'bdp_purchase_code' );
		}
		$site_url = get_site_url();
		$request  = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'        => 'license',
					'plugin_name'   => $this->slug,
					'site_url'      => $site_url,
					'username'      => $username,
					'purchase_code' => $purchase_code,
				),
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return $request['body'];
		}
		return false;
	}


	/**
	 * Deregister Site
	 *
	 * @param string $username username.
	 * @param string $purchase_code purchase code.
	 * @return $return
	 */
	public function deregister_site( $username, $purchase_code ) {
		$site_url = get_site_url();
		$request  = wp_remote_post(
			$this->update_path,
			array(
				'body' => array(
					'action'        => 'unregister',
					'plugin_name'   => $this->slug,
					'site_url'      => $site_url,
					'username'      => $username,
					'purchase_code' => $purchase_code,
				),
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			$return = $request['body'];
			if ( 'success' === $return ) {
				delete_option( 'bdp_username' );
				delete_option( 'bdp_purchase_code' );
			}
		}
		return $return;
	}

}
