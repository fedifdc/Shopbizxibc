<?php
/**
 * Function to load JS & CSS files
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists( 'cepatlakoo_enqueue_scripts' ) ) {
	function cepatlakoo_enqueue_scripts() {
		global $pagenow, $woocommerce, $cl_options;

		// Only load these scripts on frontend
		if ( !is_admin() && $pagenow != 'wp-login.php' ) {

			if ( is_singular() ) {
				wp_enqueue_script( 'comment-reply' );
			}

			wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'wc-cart-fragments' );

			// Load all JS files
			// wp_enqueue_script( 'foundations', get_template_directory_uri() .'/assets/js/foundation.min.js', array('jquery'), '3.1', true );
			wp_enqueue_script( 'bootstrap', get_template_directory_uri() .'/assets/js/bootstrap.min.js', array('jquery'), '3.1', true );
			// wp_enqueue_script( 'fitvids', get_template_directory_uri() .'/assets/js/jquery.fitvids.js', array('jquery'), '1.1', true );
			wp_enqueue_script( 'mixitup', get_template_directory_uri() .'/assets/js/jquery.mixitup.min.js', array('jquery'), '2.1.11', true );
    		wp_enqueue_script( 'why-input', get_template_directory_uri() .'/assets/js/what-input.js', array('jquery'), '2.2.10', true);
			wp_enqueue_script( 'range', get_template_directory_uri() .'/assets/js/jquery.range-min.js', array('jquery'), null, true );

			wp_register_script( 'cl-countdown', get_template_directory_uri() .'/assets/js/jquery.countdown.js', '', '2.1.0', false);
			
			wp_enqueue_script( 'jrespond', get_template_directory_uri() .'/assets/js/jrespond.min.js', array('jquery'), '0.10', true );
			wp_enqueue_script( 'owl-carousel', get_template_directory_uri() .'/assets/js/owl.carousel.min.js', array('jquery'), null, true );
			wp_enqueue_script( 'owl-carousel-thumb', get_template_directory_uri() .'/assets/js/owl-thumb.js', array('jquery'), null, true );
			wp_enqueue_script( 'jquery.magnific-popup', get_template_directory_uri() .'/assets/js/jquery.magnific-popup.js', array('jquery'), null, true );
			wp_enqueue_script( 'jquery.match-height', get_template_directory_uri() .'/assets/js/matchheight-min.js', array('jquery'), null, true );
			wp_enqueue_script( 'cepatlakoo-functions', get_template_directory_uri() .'/assets/js/functions.js', array('jquery'), null, true );

			wp_localize_script( 'cepatlakoo-functions', '_warrior', array(
				'cart_redirection' => get_option('woocommerce_cart_redirect_after_add'),
				'cart_url'	=> esc_url( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ),
				'currency_woo' => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '',
				'js_textdomain' => array(
                                esc_html__('Navigation', 'cepatlakoo'),
                                esc_html__('Login', 'cepatlakoo'),
                                esc_html__('Register', 'cepatlakoo'),
                                esc_html__('Filter', 'cepatlakoo'),
                                esc_html__('Copied', 'cepatlakoo'),
                              ),
			));

			$settings = array(
				'ajax_url'  		 => admin_url( 'admin-ajax.php' ),
				'nonce'				 => wp_create_nonce('cl-ajax-nonce'),
				'cepatlakoo_path'  	 => esc_url( get_template_directory_uri() ),
				'postid'			 => get_the_ID(),
			);
			wp_localize_script( 'cepatlakoo-functions', 'wc_ajax', $settings );

			$is_admin = current_user_can('administrator');
			if ( class_exists( 'WooCommerce' ) ){
				$_product = wc_get_product( get_the_ID() );
				if( get_post_type() == 'product' )
					$price = $_product->get_price();
			}
			else
				$price = 0;

			$pixel_api_token = ( !empty( $cl_options['cepatlakoo_facebook_pixel_token'] ) ) ? true : false;
			$pixel_integration_type = ( !empty( $cl_options['cepatlakoo_pixel_type'] ) ) ? $cl_options['cepatlakoo_pixel_type'] : 'meta_only';
			$pixel_api_event_list = ( !empty( $cl_options['cepatlakoo_pixel_api_event_list'] ) ) ? $cl_options['cepatlakoo_pixel_api_event_list'] : '';
			$cepatlakoo_facebook_pixel_id = ( !empty( $cl_options['cepatlakoo_facebook_pixel_id'] ) && ( (isset($cl_options['cepatlakoo_facebook_pixel_admin']) && $cl_options['cepatlakoo_facebook_pixel_admin'] == 0) || !$is_admin ) ) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
			$pixel_ori = get_post_meta(get_the_ID(), 'cepatlakoo_fbpixel_event', true );
			$pixel = ($pixel_ori == 'custom') ? get_post_meta(get_the_ID(), 'cepatlakoo_fbpixel_custom', true ) : $pixel_ori;
			$pixel_currency = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_currency', true );
			$pixel_price = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_value', true );
			$pixel_price = (empty($pixel_price) && !empty($price)) ? $price : $pixel_price;
			$pixel_category = get_post_meta(get_the_ID(), '_category', true );
			$pixel_ids = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content_ids', true );
			$pixel_name = !empty( get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content_name', true ) ) ? get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content_name', true ) : get_the_title();
			$pixel_type = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content_type', true );
			$pixel_content = !empty( get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content', true ) ) ? get_post_meta(get_the_ID(), 'cepatlakoo_pixel_content', true ) : get_the_excerpt();
			$pixel_num = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_num_items', true );
			$pixel_ltv = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_predicted_ltv', true );
			$pixel_search = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_search_string', true );
			$pixel_status = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_status', true );
			$fbpixel_select = get_post_meta(get_the_ID(), 'cepatlakoo_fbpixel_id', true );
			$pixel_select = ( is_array($fbpixel_select) && in_array( 'all', $fbpixel_select ) ) ? 'all' : $fbpixel_select;
			$cart_class = '';
			if (isset($cl_options['cepatlakoo_single_product_button_type']) && $cl_options['cepatlakoo_single_product_button_type'] == 'addtocart'){
				$cart_class = 'cart-btn-only';
			}
			else if (isset($cl_options['cepatlakoo_single_product_button_type']) && $cl_options['cepatlakoo_single_product_button_type'] == 'whatsapp'){
				$cart_class = 'wa-btn-only';
			}
			else if (isset($cl_options['cepatlakoo_single_product_button_type']) && $cl_options['cepatlakoo_single_product_button_type'] == 'both'){
				$cart_class = 'cart-wa-btn-only';
			}

			// Init cepatlakoo prevent empty data for countdown
			wp_localize_script( 'cepatlakoo-functions', '_cepatlakoo_cart', array(
				'cl_cart_elementor'  => get_option( 'elementor_use_mini_cart_template', 'no' ) != 'yes' ? 'no' : 'yes',
				'cl_cart_class'  => $cart_class,
				'cl_cart_style'  	 => ( isset($cl_options['cepatlakoo_cart_type']) && !empty($cl_options['cepatlakoo_cart_type']) ) ? $cl_options['cepatlakoo_cart_type'] : 'sidebar',
				'cl_cart_session'  	 => ( class_exists( 'WooCommerce' ) && !empty( WC()->session->get( 'cl_cart_last_product' ) ) ) ? 'true' : 'false',
				'cl_cart_wc_ajax'  	 => ( !empty( get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) ) ? get_option( 'woocommerce_enable_ajax_add_to_cart' ) : 'no',
			));
			
			wp_localize_script( 'cepatlakoo-functions', '_fbpixel', array(
				'fbpixel_api_token' => $pixel_api_token,
				'fbpixel_api_event_list' => $pixel_api_event_list,
				'fbpixel' => is_array($cepatlakoo_facebook_pixel_id) ? implode(',', $cepatlakoo_facebook_pixel_id) : $cepatlakoo_facebook_pixel_id,
				'event_ori' => esc_attr( $pixel_ori ),
				'event' => esc_attr( $pixel ),
				'price' => $pixel_price,
				'currency' => $pixel_currency,
				'category' => $pixel_category,
				'ids' => $pixel_ids,
				'name' => $pixel_name,
				'type' => $pixel_type,
				'content' => $pixel_content,
				'num_items' => $pixel_num,
				'predicted_ltv' => $pixel_ltv,
				'search' => $pixel_search,
				'status' => $pixel_status,
				'select' => ( is_array($cepatlakoo_facebook_pixel_id) && ($pixel_select != 'all' || $pixel_select != '') ) ? $pixel_select : 'all',
				'integration_type' => $pixel_integration_type,
			));
			
			$cepatlakoo_tiktok_pixel_id = ( !empty( $cl_options['cepatlakoo_tiktok_pixel_id'] ) && ( (isset($cl_options['cepatlakoo_tiktok_pixel_admin']) && $cl_options['cepatlakoo_tiktok_pixel_admin'] == 0) || !$is_admin ) ) ? $cl_options['cepatlakoo_tiktok_pixel_id'] : '';
			$tt_pixel_ori = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_event', true );
			$tt_pixel = ($pixel_ori == 'custom') ? get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_custom', true ) : $pixel_ori;
			$tt_pixel_currency = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_currency', true );
			$tt_pixel_price = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_value', true );
			$tt_pixel_price = (empty($pixel_price) && !empty($price)) ? $price : $pixel_price;
			$tt_pixel_category = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content_category', true );
			$tt_pixel_ids = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content_ids', true );
			$tt_pixel_name = !empty( get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content_name', true ) ) ? get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content_name', true ) : get_the_title();
			$tt_pixel_type = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content_type', true );
			$tt_pixel_content = !empty( get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content', true ) ) ? get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_content', true ) : get_the_excerpt();
			$tt_pixel_num = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_num_items', true );
			$tt_pixel_ltv = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_predicted_ltv', true );
			$tt_pixel_search = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_search_string', true );
			$tt_pixel_status = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_status', true );
			$ttpixel_select = get_post_meta(get_the_ID(), 'cepatlakoo_ttpixel_id', true );
			$tt_pixel_select = ( is_array($ttpixel_select) && in_array( 'all', $ttpixel_select ) ) ? 'all' : $ttpixel_select;

			wp_localize_script( 'cepatlakoo-functions', '_ttpixel', array(
				'ttpixel' => is_array($cepatlakoo_tiktok_pixel_id) ? implode(',', $cepatlakoo_tiktok_pixel_id) : $cepatlakoo_tiktok_pixel_id,
				'event' => esc_attr( $tt_pixel ),
				'price' => $tt_pixel_price,
				'currency' => $tt_pixel_currency,
				'category' => $tt_pixel_category,
				'ids' => $tt_pixel_ids,
				'name' => $tt_pixel_name,
				'type' => $tt_pixel_type,
				'content' => $tt_pixel_content,
				'num_items' => $tt_pixel_num,
				'predicted_ltv' => $tt_pixel_ltv,
				'search' => $tt_pixel_search,
				'status' => $tt_pixel_status,
				'select' => ( is_array($cepatlakoo_tiktok_pixel_id) && ($ttpixel_select != 'all' || $ttpixel_select != '') ) ? $ttpixel_select : 'all',
			));
			
			wp_localize_script( 'cepatlakoo-functions', '_gacon', array(
				'fbpixel' => is_array($cepatlakoo_facebook_pixel_id) ? implode(',', $cepatlakoo_facebook_pixel_id) : $cepatlakoo_facebook_pixel_id,
				'event' => esc_attr( $pixel ),
				'price' => $pixel_price,
				'currency' => $pixel_currency,
				'category' => $pixel_category,
				'ids' => $pixel_ids,
				'name' => $pixel_name,
				'type' => $pixel_type,
				'content' => $pixel_content,
				'num_items' => $pixel_num,
				'predicted_ltv' => $pixel_ltv,
				'search' => $pixel_search,
				'status' => $pixel_status,
				'select' => ( is_array($cepatlakoo_facebook_pixel_id) && ($pixel_select != 'all' || $pixel_select != '') ) ? $pixel_select : 'all',
			));
			wp_localize_script( 'cepatlakoo-functions', '_fbpixel_purchase', array());
			wp_localize_script( 'cepatlakoo-functions', '_fbpixel_initCheckout', array());
			wp_localize_script( 'cepatlakoo-functions', '_fbpixel_checkoutEvent', array(
				'type' => !empty( $cl_options['cepatlakoo_pixel_checkout'] ) ? $cl_options['cepatlakoo_pixel_checkout'] : 'InitiateCheckout',
			));
			
			wp_localize_script( 'cepatlakoo-functions', '_ttpixel_initCheckout', array());
			wp_localize_script( 'cepatlakoo-functions', '_ttpixel_checkoutEvent', array(
				'type' => !empty( $cl_options['cepatlakoo_tiktok_pixel_checkout'] ) ? $cl_options['cepatlakoo_tiktok_pixel_checkout'] : 'InitiateCheckout',
				'confirmation' => !empty( $cl_options['cepatlakoo_tiktok_purchase_confirmation'] ) ? $cl_options['cepatlakoo_tiktok_purchase_confirmation'] : 'CompletePayment',
			));

			// Load all CSS files
			
			wp_enqueue_style( 'cepatlakoo-style-minified', get_template_directory_uri() .'/assets/css/dist/styles.min.css', array(), false, 'all' );
			wp_enqueue_style( 'cepatlakoo-style', get_template_directory_uri() .'/style.css', array(), false, 'all' );

			if( !isset($cl_options['cepatlakoo_quickview_status']) || $cl_options['cepatlakoo_quickview_status'] == true ) {
				wp_enqueue_script( 'photoswipe' );
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_script( 'zoom' );
				wp_enqueue_script( 'flexslider' );
				wp_enqueue_script( 'wc-single-product' );
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cepatlakoo_enqueue_scripts' );

/**
 * Function to load JS & CSS files in wp-admin
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists( 'cepatlakoo_enquene_admin_scripts' ) ) {
	function cepatlakoo_enquene_admin_scripts($hook) {
		global $typenow;

		wp_enqueue_style( 'cepatlakoo-backend', get_template_directory_uri() .'/assets/css/dist/backend.css', array(), false, 'all' );
		wp_enqueue_script( 'sms-link', get_template_directory_uri() .'/assets/js/sms-link.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'cepatlakoo-functions-admin', get_template_directory_uri() .'/assets/js/functions-admin.js', array('jquery', 'jquery-ui-sortable'), '1.0.0', true );

		$settings = array(
			'ajax_url'  		 => admin_url( 'admin-ajax.php' ),
			'admin_url'			 => get_admin_url(),
			'texdomain'			 => array(
														esc_html('Migrasi Sukses, Terimakasih', 'cepatlakoo'),
														esc_html('Silahkan Install dan Aktifkan plugin Redux Framework', 'cepatlakoo'),
														esc_html('Plugin Berhasil Diaktifkan...', 'cepatlakoo'),
														esc_html('Plugin Gagal Diaktifkan...', 'cepatlakoo'),
													)
		);
		wp_localize_script( 'cepatlakoo-functions-admin', 'wpadmin_ajax', $settings );
		remove_action( 'wp_footer', 'woocommerce_photoswipe' );
	}
}
add_action( 'admin_enqueue_scripts', 'cepatlakoo_enquene_admin_scripts' );

/**
 * Manage WooCommerce styles and scripts.
 *
 * @package WordPress
 * @subpackage Etalase
 * @since Etalase 1.0.0
 */
if ( ! function_exists( 'cepatlakoo_scripts_include_lightbox' ) ) {
	function cepatlakoo_scripts_include_lightbox() {
	  	global $woocommerce;
	  	if ( class_exists( 'WooCommerce' ) ) {
		  	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}
		// wp_dequeue_script('prettyPhoto');
		// wp_dequeue_script('prettyPhoto-init');
	}
}
add_action( 'wp_enqueue_scripts', 'cepatlakoo_scripts_include_lightbox' );

/**
 * Function to load google fonts
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
// https://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/
if ( ! function_exists( 'cepatlakoo_slug_fonts_url' ) ) {
	function cepatlakoo_slug_fonts_url() {
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		* supported by Lora, translate this to 'off'. Do not translate
		* into your own language.
		*/

		/* Translators: If there are characters in your language that are not
		* supported by Open Sans, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$cepatlakoo_montserrat = esc_html_x( 'on', 'Montserrat font: on or off', 'cepatlakoo' );

		if ( 'off' !== $cepatlakoo_montserrat ) {
			$font_families = array();

			if ( 'off' !== $cepatlakoo_montserrat ) {
				$font_families[] = 'Montserrat:400,700';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
}

/**
 * Function to Enqueue scripts and styles google fonts
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists( 'cepatlakoo_slug_scripts' ) ) {
	function cepatlakoo_slug_scripts() {
	    wp_enqueue_style( 'cepatlakoo-fonts', cepatlakoo_slug_fonts_url(), array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'cepatlakoo_slug_scripts' );

/**
 * Function to Inline Style Redux Framework
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if ( ! function_exists( 'cepatlakoo_redux_inline_style' ) ) {
	function cepatlakoo_redux_inline_style() {
	    if( get_option('cepatlakoo_migration_themeoption') ) {
	    	global $cl_options;

			$cepatlakoo_general_theme_color = !empty( $cl_options['cepatlakoo_general_theme_color'] ) ? $cl_options['cepatlakoo_general_theme_color'] : '';
			$cepatlakoo_general_bg_theme_color = !empty( $cl_options['cepatlakoo_general_bg_theme_color'] ) ? $cl_options['cepatlakoo_general_bg_theme_color']['background-color'] : '';
			$cepatlakoo_topbar_bg_color = !empty( $cl_options['cepatlakoo_topbar_bg_color'] ) ? $cl_options['cepatlakoo_topbar_bg_color']['background-color'] : '';
?>
			<style>
	        .custom-shop-buttons i { color: <?php echo $cepatlakoo_general_theme_color; ?> }
	        .custom-shop-buttons { border-bottom: solid 2px <?php echo $cepatlakoo_general_theme_color; ?> !important; }
	        /*.woocommerce div.product .woocommerce-tabs ul.tabs li.active { border-bottom: solid 2px <?php echo $cepatlakoo_general_theme_color; ?> !important; }*/
	        .custom-shop-buttons > a:hover i { color: #fff !important }
	        .site-navigation ul.user-menu-menu li { color: #fff !important }
	        .search-widget-header .search-widget { border-top: solid 2px <?php echo $cepatlakoo_general_bg_theme_color; ?> !important; }
	        #top-bar { background-color: <?php echo $cepatlakoo_general_bg_theme_color; ?> }

	        #top-bar ul.user-menu-menu { background-color: <?php echo $cepatlakoo_topbar_bg_color; ?> }

	        @media screen and (max-width: 1024px) {
	        	.user-account-menu, .mobile-menu-menu-expander {
	        		background: <?php echo $cepatlakoo_general_bg_theme_color; ?>
	        	}
	       	}
			</style>
<?php
	    }
	}
}
add_action( 'wp_head', 'cepatlakoo_redux_inline_style' );

/**
 * Function to include woocommerce_photoswipe library
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.4.15
 */
if ( ! function_exists( 'cepatlakoo_include_footer' ) ) {
	function cepatlakoo_include_footer(){
		global $cl_options;
		
		if ( !is_admin() && class_exists( 'WooCommerce' ) && ( !isset($cl_options['cepatlakoo_quickview_status']) || $cl_options['cepatlakoo_quickview_status'] == true ) ) {
			add_action( 'wp_footer', 'woocommerce_photoswipe' );
		}
	}
}
add_action( 'init', 'cepatlakoo_include_footer' );
