<?php
/**
 * Plugin Installer
 *
 * @package     Blog_Designer_PRO
 * @category    Core
 * @author  Darren Cooney
 * @link        https://github.com/dcooney/wordpress-plugin-installer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Plugin Installer
 *
 * @class   Blog_Designer_PRO_Plugin_Installer
 * @version 1.0.0
 */
class Blog_Designer_PRO_Plugin_Installer {
	/**
	 * Start
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function start() {
	}
	/**
	 * Initialize the display of the free plugins
	 *
	 * @param string $plugins plugin.
	 * @since 1.0.0
	 */
	public static function init( $plugins ) {
		?>
		<?php
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		foreach ( $plugins as $plugin ) :
			$button_classes = 'install button';
			$button_text    = esc_html__( 'Install Now', 'blog-designer-pro' );
			$api            = plugins_api(
				'plugin_information',
				array(
					'slug'   => sanitize_file_name( $plugin['slug'] ),
					'fields' => array(
						'short_description' => true,
						'sections'          => false,
						'requires'          => false,
						'downloaded'        => true,
						'last_updated'      => false,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
						'icons'             => true,
						'banners'           => true,
					),
				)
			);
			if ( ! is_wp_error( $api ) ) { // confirm error free.
				$main_plugin_file = self::get_plugin_file( $plugin['slug'] ); // Get main plugin file.
				if ( self::check_file_extension( $main_plugin_file ) ) { // check file extension.
					if ( is_plugin_active( $main_plugin_file ) ) {
						// plugin activation, confirmed!
						$button_classes = 'button disabled';
						$button_text    = esc_html__( 'Activated', 'blog-designer-pro' );
					} else {
						// It's installed, let's activate it.
						$button_classes = 'activate button button-primary';
						$button_text    = esc_html__( 'Activate', 'blog-designer-pro' );
					}
				}
				// Send plugin data to template.
				self::render_template( $plugin, $api, $button_text, $button_classes );
			}
		endforeach;
	}
	/**
	 * Render display template for each free plugin
	 *
	 * @param string $plugin plugin.
	 * @param string $api api.
	 * @param string $button_text text.
	 * @param string $button_classes classes.
	 * @since 1.0.0
	 */
	public static function render_template( $plugin, $api, $button_text, $button_classes ) {
		?>
		<div class="plugin">
			<div class="plugin-wrap">
				<img src="<?php echo esc_attr( $api->icons['1x'] ); ?>" alt="">
				<h3><?php echo esc_html( $api->name ); ?></h3>
				<p><?php echo esc_html( $api->short_description ); ?></p>
				<p class="plugin-author">
				<?php
				esc_html_e( 'By ', 'blog-designer-pro' );
				echo wp_kses( $api->author, Bdp_Admin_Functions::args_kses() );
				?>
				</p>
			</div>
			<ul class="activation-row">
				<li><a class="<?php echo esc_attr( $button_classes ); ?>" data-slug="<?php echo esc_attr( $api->slug ); ?>" data-name="<?php echo esc_attr( $api->name ); ?>" href="<?php echo esc_url( get_admin_url() ); ?>update.php?action=install-plugin&amp;plugin=<?php echo esc_attr( $api->slug ); ?>&amp;_wpnonce=<?php echo esc_attr( wp_create_nonce( 'install-plugin_' . $api->slug ) ); ?>"><?php echo esc_html( $button_text ); ?></a></li>
				<li><a href="https://wordpress.org/plugins/<?php echo esc_attr( $api->slug ); ?>/" target="_blank"><?php esc_html_e( 'More Details', 'blog-designer-pro' ); ?></a></li>
			</ul>
		</div>
		<?php
	}
	/**
	 * Initialize the display of the premium plugins
	 *
	 * @param string $plugins plugin.
	 * @since 1.0.0
	 */
	public static function init_premium( $plugins ) {
		foreach ( $plugins as $plugin ) :
			$button_classes = '';
			$button_text    = '';
			$api            = array(
				'slug'        => isset( $plugin['slug'] ) ? $plugin['slug'] : '',
				'url'         => isset( $plugin['url'] ) ? $plugin['url'] : '',
				'full_url'    => isset( $plugin['full_url'] ) ? $plugin['full_url'] : '',
				'name'        => isset( $plugin['name'] ) ? $plugin['name'] : '',
				'description' => isset( $plugin['description'] ) ? $plugin['description'] : '',
				'icons'       => isset( $plugin['icons'] ) ? $plugin['icons'] : '',
				'author'      => isset( $plugin['author'] ) ? $plugin['author'] : '',
				'author_url'  => isset( $plugin['author_url'] ) ? $plugin['author_url'] : '',
			);
			if ( ! is_wp_error( $api ) ) { // confirm error free.
				$main_plugin_file = self::get_plugin_file( $plugin['slug'] ); // Get main plugin file.
				if ( self::check_file_extension( $main_plugin_file ) ) { // check file extension.
					if ( is_plugin_active( $main_plugin_file ) ) {
						// plugin activation, confirmed!.
						$button_classes = 'button disabled';
						$button_text    = esc_html__( 'Activated', 'blog-designer-pro' );
					} else {
						// It's installed, let's activate it.
						$button_classes = 'activate button button-primary premium-activation';
						$button_text    = esc_html__( 'Activate', 'blog-designer-pro' );
					}
				}
				// Send plugin data to template.
				self::render_premium_template( $plugin, $api, $button_text, $button_classes );
			}
		endforeach;
	}
	/**
	 * Render display template for each premium plugin
	 *
	 * @param string $plugin plugin.
	 * @param string $api api.
	 * @param string $button_text text.
	 * @param string $button_classes class.
	 * @since 1.0.0
	 */
	public static function render_premium_template( $plugin, $api, $button_text, $button_classes ) {
		$slug        = $api['slug'];
		$url         = $api['url'];
		$full_url    = $api['full_url'];
		$name        = $api['name'];
		$description = $api['description'];
		$icons       = $api['icons'];
		$author      = $api['author'];
		$author_url  = $api['author_url'];
		// Affiliate link.
		$ref_url = '';
		$aff_ref = apply_filters( 'ocean_affiliate_ref', $ref_url );
		// Add & is has referal link.
		if ( $aff_ref ) {
			$if_ref = '&';
		} else {
			$if_ref = '?';
		}
		?>
		<div class="plugin">
			<div class="plugin-wrap">
				<?php
				if ( $icons ) {
					?>
					<img src="<?php echo esc_url( $icons ); ?>" alt="<?php echo esc_attr( $name ); ?>" />
					<?php
				}
				if ( $name ) {
					?>
					<h2><?php echo esc_html( $name ); ?></h2>
					<?php
				}
				if ( $description ) {
					?>
					<p><?php echo esc_html( $description ); ?></p>
					<?php
				}
				if ( $author ) {
					?>
					<p class="plugin-author"><?php esc_html_e( 'By', 'blog-designer-pro' ); ?> <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author ); ?></a></p>
					<?php
				}
				?>
			</div>
			<ul class="activation-row">
				<li>
					<?php
					// Get main plugin file.
					$main_plugin_file = self::get_plugin_file( $plugin['slug'] );
					// If the plugin is installed.
					if ( self::check_file_extension( $main_plugin_file ) ) {
						?>
						<a class="<?php echo esc_attr( $button_classes ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>" data-name="<?php echo esc_attr( $name ); ?>" href="<?php echo esc_attr( get_admin_url() ); ?>update.php?action=install-plugin&amp;plugin=<?php echo esc_attr( $slug ); ?>&amp;_wpnonce=<?php echo esc_attr( wp_create_nonce( 'install-plugin_' . $slug ) ); ?>"><?php echo esc_html( $button_text ); ?></a>
						<?php
						// If the plugin is not installed.
					} elseif ( $full_url ) {
						?>
							<a class="button premium-link" href="<?php echo esc_url( $full_url ); ?>" target="_blank"><?php esc_html_e( 'Get This Plugin', 'blog-designer-pro' ); ?></a>
							<?php
					} else {
						?>
							<a class="button premium-link" href="
							<?php
							echo esc_attr( $url ) . esc_attr( $slug ) . '/' . esc_attr( $aff_ref ) . esc_attr( $if_ref );
							echo 'utm_source=admin-extensions&utm_medium=extension&utm_campaign=OWP-extensions-page&utm_content=';
							echo esc_attr( $name );
							?>
							" target="_blank"><?php esc_attr_e( 'Get This Add On', 'blog-designer-pro' ); ?></a>
							<?php

					}
					?>
				</li>
				<li>
					<?php
					// If full url, used for the rec. plugins tab.
					if ( $full_url ) {
						?>
						<a href="<?php echo esc_url( $full_url ); ?>" target="_blank"><?php esc_html_e( 'More Details', 'blog-designer-pro' ); ?></a>
						<?php
					} else {
						?>
						<a href="
						<?php
						echo esc_attr( $url ) . esc_attr( $slug ) . '/' . esc_attr( $aff_ref ) . esc_attr( $if_ref );
						echo 'utm_source=admin-extensions&utm_medium=extension&utm_campaign=OWP-extensions-page&utm_content=';
						echo esc_attr( $name );
						?>
						" target="_blank"><?php esc_html_e( 'More Details', 'blog-designer-pro' ); ?></a>
						<?php
					}
					?>
				</li>
				<li class="ribbon"><?php esc_html_e( 'Premium', 'blog-designer-pro' ); ?></li>
			</ul>
		</div>
		<?php
	}
	/**
	 * A method to get the main plugin file
	 *
	 * @param string $plugin_slug plugin slug.
	 * @since 1.0.0
	 */
	public static function get_plugin_file( $plugin_slug ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php'; // Load plugin lib.
		$plugins = get_plugins();
		foreach ( $plugins as $plugin_file => $plugin_info ) {
			// Get the basename of the plugin e.g. [askismet]/askismet.php.
			$slug = dirname( plugin_basename( $plugin_file ) );
			if ( $slug ) {
				if ( $slug == $plugin_slug ) {
					return $plugin_file;
				}
			}
		}
		return null;
	}
	/**
	 * A helper to check file extension
	 *
	 * @param string $filename filename.
	 * @since 1.0.0
	 */
	public static function check_file_extension( $filename ) {
		if ( isset( $filename ) && substr( strrchr( $filename, '.' ), 1 ) === 'php' ) {
			// has .php exension.
			return true;
		} else {
			// ./wp-content/plugins.
			return false;
		}
	}
	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed  $_data data.
	 * @param string $_action action.
	 * @param object $_args args.
	 * @return object $_data
	 *
	 * @since 1.0.0
	 */
	public function oceanwp_plugins_api_filter( $_data, $_action = '', $_args = null ) {
		if ( 'plugin_information' !== $_action ) {
			return $_data;
		}
		if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {
				return $_data;
		}
		$to_send      = array(
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => array(
				'banners' => false, // These will be supported soon hopefully.
				'reviews' => false,
			),
		);
		$api_response = $this->api_request( 'plugin_information', $to_send );
		if ( false != $api_response ) {
			$_data = $api_response;
		}
		return $_data;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string $_action The requested action.
	 * @param array  $_data   Parameters for the API action.
	 * @return false|object
	 */
	private function api_request( $_action, $_data ) {
		global $wp_version;
		$data = array_merge( $this->api_data, $_data );
		if ( $data['slug'] != $this->slug ) {
			return;
		}
		if ( empty( $this->license_key ) ) {
			return;
		}
		if ( home_url() == $this->api_url ) {
			return false; // Don't allow a plugin to ping itself.
		}

		$api_params = array(
			'edd_action' => 'get_version',
			'license'    => $this->license_key,
			'item_name'  => isset( $this->item_name ) ? $this->item_name : false,
			'item_id'    => isset( $this->item_id ) ? $this->item_id : false,
			'slug'       => $data['slug'],
			'author'     => $this->author,
			'url'        => home_url(),
		);

		$request = wp_remote_post(
			$this->api_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		if ( ! is_wp_error( $request ) ) {
			$request = json_decode( wp_remote_retrieve_body( $request ) );
		}

		if ( $request && isset( $request->sections ) ) {
			$request->sections = maybe_unserialize( $request->sections );
		} else {
			$request = false;
		}

		return $request;
	}

}
$blog_designer_pro_plugin_installer = new Blog_Designer_PRO_Plugin_Installer();
