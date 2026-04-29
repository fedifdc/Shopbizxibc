<?php
/**
 * Plugin Name: Affiliate Plugin Product
 * Description: Handle Order Affliate Product
 * Version: 1.0
 * Author: Ruli Setiawan
 */

 remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
 add_action('woocommerce_thankyou', 'woocommerce_order_affiliate_table', 10 );

function woocommerce_order_affiliate_table( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );

    if ( ! $order ) {
        return;
    }
    wc_get_template(
        'order-details.php',
        array(
            'order_id'       => $order_id,
            'show_downloads' => apply_filters( 'woocommerce_order_downloads_table_show_downloads', ( $order->has_downloadable_item() && $order->is_download_permitted() ), $order ),
        )
    );
} 

if (!function_exists('cepatlakoo_checkout_payment_confirmation_affiliate_buttons')) {
    function cepatlakoo_checkout_payment_confirmation_affiliate_buttons()
    {
        global $cl_options, $wp;

        // get values from theme options
        $payment_confirmation_buttons = $cl_options['cepatlakoo_payment_confirmation_buttons'];
        $confirmation_page = $cl_options['cepatlakoo_select_confirmation'];
        $web_confirmation_text = $cl_options['cepatlakoo_form_confirmation_text'];
        $wa_confirmation_text = $cl_options['cepatlakoo_wa_confirmation_text'];
        $wa_confirmation_number = $cl_options['cepatlakoo_wa_confirmation_number'];
        $wa_confirmation_greeting_text = $cl_options['cepatlakoo_wa_confirmation_greeting_text'];
        $order_id  = absint($wp->query_vars['order-received']);

        $order = new WC_Order($order_id);
        $order_status = $order->get_status();
        $payment = $order->get_payment_method();
        $order_data = $order->get_data(); // The Order data
        $email = $order_data['billing']['email'];
        
        $is_affiliate = 'no';
        $is_ads_product ='no';
        foreach ($order->get_items() as $item_id => $item ) {
            $product = $item->get_product();
            $check_variant = get_post_meta( $product->id, '_is_affiliate_product', true );
            $is_ads_product = get_post_meta( $product->id, '_is_ads_product', true );
           if($check_variant == 'yes'){
                $is_affiliate = 'yes';
                continue;
            }
        }

        if( $is_affiliate  == 'no'){
              if ($payment_confirmation_buttons == 'none' || ($order_status != 'pending' && $order_status != 'on-hold' && $order_status != 'waiting-conf' && $order_status != 'waiting-confirm') ){
              if( $is_ads_product == 'yes'){
                 
                }else{
                    return;
                }
              }
        }

        ?>
        <?php
		  if(!in_array($order_status,['waiting-confirm','waiting-conf']) || ($is_ads_product == 'yes')) {
			if($is_affiliate == 'yes') {
		    ?>
            <p class="confirmation-buttons">
                <?php if (($payment_confirmation_buttons == 'web-form' || $payment_confirmation_buttons == 'all') && $confirmation_page) : ?>
                    <a href="<?php echo get_site_url() . '/konfirmasi-pembayaran-affiliasi' . '?orderid=' . $order_id . '&email=' . $email; ?>" class="confirmation-button web-form">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M6 12h10v1h-10v-1zm7.816-3h-7.816v1h9.047c-.45-.283-.863-.618-1.231-1zm-7.816-2h6.5c-.134-.32-.237-.656-.319-1h-6.181v1zm13 3.975v2.568c0 4.107-6 2.457-6 2.457s1.518 6-2.638 6h-7.362v-20h9.5c.312-.749.763-1.424 1.316-2h-12.816v24h10.189c3.163 0 9.811-7.223 9.811-9.614v-3.886c-.623.26-1.297.421-2 .475zm4-6.475c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5 2.015-4.5 4.5-4.5 4.5 2.015 4.5 4.5zm-2.156-.882l-.696-.696-2.116 2.169-.992-.941-.696.697 1.688 1.637 2.812-2.866z" />
                        </svg>
                        <?php echo $web_confirmation_text; ?>
                    </a>
                <?php endif; ?>
            <?php } else if($is_ads_product == 'yes') { ?>
                <p class="confirmation-buttons">
                  <?php if (($payment_confirmation_buttons == 'web-form' || $payment_confirmation_buttons == 'all') && $confirmation_page) : ?>
                    <a href="<?php echo get_site_url() . '/konfirmasi-ads' . '?orderid=' . $order_id . '&email=' . $email; ?>" class="confirmation-button web-form">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M6 12h10v1h-10v-1zm7.816-3h-7.816v1h9.047c-.45-.283-.863-.618-1.231-1zm-7.816-2h6.5c-.134-.32-.237-.656-.319-1h-6.181v1zm13 3.975v2.568c0 4.107-6 2.457-6 2.457s1.518 6-2.638 6h-7.362v-20h9.5c.312-.749.763-1.424 1.316-2h-12.816v24h10.189c3.163 0 9.811-7.223 9.811-9.614v-3.886c-.623.26-1.297.421-2 .475zm4-6.475c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5 2.015-4.5 4.5-4.5 4.5 2.015 4.5 4.5zm-2.156-.882l-.696-.696-2.116 2.169-.992-.941-.696.697 1.688 1.637 2.812-2.866z" />
                        </svg>
                        Konfirmasi Bukti Partisipasi
                    </a> 
            <?php endif; ?>
            <?php } else { ?>
                <p class="confirmation-buttons">
                <?php if (($payment_confirmation_buttons == 'web-form' || $payment_confirmation_buttons == 'all') && $confirmation_page) : ?>
                    <a href="<?php echo get_site_url() . '/konfirmasi-2' . '?orderid=' . $order_id . '&email=' . $email; ?>" class="confirmation-button web-form">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M6 12h10v1h-10v-1zm7.816-3h-7.816v1h9.047c-.45-.283-.863-.618-1.231-1zm-7.816-2h6.5c-.134-.32-.237-.656-.319-1h-6.181v1zm13 3.975v2.568c0 4.107-6 2.457-6 2.457s1.518 6-2.638 6h-7.362v-20h9.5c.312-.749.763-1.424 1.316-2h-12.816v24h10.189c3.163 0 9.811-7.223 9.811-9.614v-3.886c-.623.26-1.297.421-2 .475zm4-6.475c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5 2.015-4.5 4.5-4.5 4.5 2.015 4.5 4.5zm-2.156-.882l-.696-.696-2.116 2.169-.992-.941-.696.697 1.688 1.637 2.812-2.866z" />
                        </svg>
                        <?php echo $web_confirmation_text; ?>
                    </a>
                <?php endif; ?>
                <?php } ?>  
            <?php if (($payment_confirmation_buttons == 'whatsapp' || $payment_confirmation_buttons == 'all') && $wa_confirmation_text && $wa_confirmation_number) :
                if (strpos($wa_confirmation_number, '-') !== false) {
                    $wa_confirmation_number = str_replace('-', '', $wa_confirmation_number);
                }
                if (preg_match('[^\+62]', $wa_confirmation_number)) {
                    $wa_phone = str_replace('+62', '62', $wa_confirmation_number);
                } else if ($wa_confirmation_number[0] == '0') {
                    $wa_confirmation_number = ltrim($wa_confirmation_number, '0');
                    $wa_phone = '62' . $wa_confirmation_number;
                } else if ($wa_confirmation_number[0] == '8') {
                    $wa_phone = '62' . $wa_confirmation_number;
                } else {
                    $wa_phone = $wa_confirmation_number;
                }

                if (strpos($wa_phone, "-")) {
                    $wa_phone = str_replace('-', '', $wa_phone);
                }

                $wa_base_url = 'https://api.whatsapp.com/';

                $wa_confirmation_greeting_text = str_replace('%lakoo_order_id%', $order_id, $wa_confirmation_greeting_text);

                $wa_confirmation_greeting_text = str_replace("&nbsp;", '', $wa_confirmation_greeting_text);
                $wa_confirmation_greeting_text = str_replace("\n", '%0A', $wa_confirmation_greeting_text);
                $wa_confirmation_greeting_text = str_replace("#", '%23', $wa_confirmation_greeting_text);
                $wa_confirmation_greeting_text = str_replace("-", '%2D', $wa_confirmation_greeting_text);
                $wa_confirmation_greeting_text = str_replace("&", '%26', $wa_confirmation_greeting_text);

            ?>
                <a href="<?php echo $wa_base_url . 'send?phone=' . $wa_phone . '&text=' . esc_attr($wa_confirmation_greeting_text); ?>" class="confirmation-button whatsapp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                    </svg>
                    <?php echo $wa_confirmation_text; ?>
                </a>

            <?php endif; ?>
        </p> 
        <?php
    	}
	}
}
remove_action('woocommerce_thankyou', 'cepatlakoo_checkout_payment_confirmation_buttons', 9);
add_action('woocommerce_thankyou', 'cepatlakoo_checkout_payment_confirmation_affiliate_buttons', 9);

function wc_cart_totals_order_total_affiliate_html() {
	$value = ' <strong>' . wc_price(get_affiliate_meta_products_total()) . '</strong> ';
	echo apply_filters( 'woocommerce_cart_totals_order_total_html', $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if (!function_exists('cepatlakoo_confirmation_button_edit')) {
    function cepatlakoo_confirmation_button_edit($order_id)
    {
        global $cl_options;

        $cepatlakoo_button_confirmation = !empty($cl_options['cepatlakoo_payment_confirmation_buttons']) ? $cl_options['cepatlakoo_payment_confirmation_buttons'] : '';
        $cepatlakoo_form_text = !empty($cl_options['cepatlakoo_form_confirmation_text']) ? $cl_options['cepatlakoo_form_confirmation_text'] : __('Confirm Payment on Website', 'cepatlakoo');
        $cepatlakoo_wa_text = !empty($cl_options['cepatlakoo_wa_confirmation_text']) ? $cl_options['cepatlakoo_wa_confirmation_text'] : __('Confirm Payment via WhatsApp', 'cepatlakoo');
        $cepatlakoo_wa_number = !empty($cl_options['cepatlakoo_wa_confirmation_number']) ? $cl_options['cepatlakoo_wa_confirmation_number'] : '';
        $wa_confirmation_greeting_text = !empty($cl_options['cepatlakoo_wa_confirmation_greeting_text']) ? $cl_options['cepatlakoo_wa_confirmation_greeting_text'] : '';
        $cepatlakoo_confirmation_page = !empty($cl_options['cepatlakoo_select_confirmation']) ? $cl_options['cepatlakoo_select_confirmation'] : '';
        $cepatlakoo_general_bg_theme_color = !empty($cl_options['cepatlakoo_general_bg_theme_color']['background-color']) ? $cl_options['cepatlakoo_general_bg_theme_color']['background-color'] : '';

        $order = new WC_Order($order_id);
        $order_status = $order->get_status();
        $order_no = $order->get_order_number();
        $order_data = $order->get_data();
        $email = $order_data['billing']['email'];

        // Load WooCommerce email colour settings
        $bg         = get_option('woocommerce_email_background_color');
        $body       = get_option('woocommerce_email_body_background_color');
        $base       = get_option('woocommerce_email_base_color');
        $base_text  = wc_light_or_dark($base, '#202020', '#ffffff');
        $text       = get_option('woocommerce_email_text_color');
        // only show confirmation button only if order status is on-hold or pending
        if ($cepatlakoo_button_confirmation != 'none') :
            if (('on-hold' == $order_status || 'pending' == $order_status) && $cepatlakoo_confirmation_page) :
                $style = (did_action('woocommerce_email_after_order_table')) ? 'style="background-color: ' . $base . '; padding: 13px 20px; font-size: 14px; color: ' . $base_text . ' !important; text-decoration: none;"' : 'class="button"'; ?>
                <div style="margin:30px 0;">
                    <?php if (($cepatlakoo_button_confirmation == 'web-form' || $cepatlakoo_button_confirmation == 'all')) : 
                        $site_url = get_site_url(); ?>
                        <p><a <?php echo $style; ?> href="<?php echo $site_url . '/konfirmasi-pembayaran-affiliasi?orderid=' . $order_no . '&email=' . $email; ?>"><?php echo $cepatlakoo_form_text; ?></a></p>
                    <?php endif;

                    if (($cepatlakoo_button_confirmation == 'whatsapp' || $cepatlakoo_button_confirmation == 'all')) :
                        if (strpos($cepatlakoo_wa_number, '-') !== false) {
                            $cepatlakoo_wa_number = str_replace('-', '', $cepatlakoo_wa_number);
                        }
                        if (preg_match('[^\+62]', $cepatlakoo_wa_number)) {
                            $wa_phone = str_replace('+62', '62', $cepatlakoo_wa_number);
                        } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '0') {
                            $cepatlakoo_wa_number = ltrim($cepatlakoo_wa_number, '0');
                            $wa_phone = '62' . $cepatlakoo_wa_number;
                        } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '8') {
                            $wa_phone = '62' . $cepatlakoo_wa_number;
                        } else {
                            $wa_phone = $cepatlakoo_wa_number;
                        }

                        if (strpos($wa_phone, "-")) {
                            $wa_phone = str_replace('-', '', $wa_phone);
                        }

                        $wa_base_url = 'https://api.whatsapp.com/';

                        $wa_confirmation_greeting_text = str_replace('%lakoo_order_id%', $order_no, $wa_confirmation_greeting_text);

                        $wa_confirmation_greeting_text = str_replace("&nbsp;", '', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("\n", '%0A', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("#", '%23', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("-", '%2D', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("&", '%26', $wa_confirmation_greeting_text);

                    ?>
                        <p><a <?php echo $style; ?> href="<?php echo $wa_base_url . 'send?phone=' . $wa_phone . '&text=' . esc_attr($wa_confirmation_greeting_text); ?>"><?php echo $cepatlakoo_wa_text; ?></a></p>
                    <?php endif; ?>
                </div>
            <?php endif;
        endif;
    }
}
add_action('woocommerce_view_order', 'switch_action', 9);
function switch_action($order_id){
    $order = new WC_Order($order_id);
    $is_affiliate = 'no';
    $check_ads = 'no';
    foreach ($order->get_items() as $item_id => $item ) {
        $product = $item->get_product();
        $check_variant = get_post_meta( $product->id, '_is_affiliate_product', true );
        $check_ads = get_post_meta( $product->id, '_is_ads_product', true );
        if($check_variant == 'yes'){
            $is_affiliate = 'yes';
            continue;
        }
    }
    if($is_affiliate == 'yes' ){
        cepatlakoo_confirmation_button_edit($order_id);
    }else if($check_ads == 'yes'){
        cepatlakoo_confirmation_button_ads($order_id);
    }else{
        cepatlakoo_confirmation_button($order_id);
    }
}

function get_affiliate_meta_products_total() {
    $affiliate_total = 0;

    // Looping melalui semua item di keranjang
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        $product_id = $cart_item['product_id'];

        $is_affiliate_product = get_post_meta( $product_id, '_is_affiliate_product', true );

        if ( $is_affiliate_product === 'yes' ) {
            $product_price = wc_get_price_to_display($_product);
            $affiliate_total += $product_price * $cart_item['quantity'];
        }
    }

    return $affiliate_total;
}

/**
 * Get order total html including inc tax if needed.
 */
function wc_cart_totals_order_total_non_affiliate_html() {
	$value = '<strong>' . wc_price(get_non_affiliate_meta_products_total()) . '</strong> ';
	echo apply_filters( 'woocommerce_cart_totals_order_total_html', $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function my_plugin_enqueue_scripts() {
    // Pastikan jQuery dimasukkan
    wp_enqueue_script( 'jquery' );

    // Masukkan file custom JavaScript Anda
    wp_enqueue_script(
        'custom-plugin-js', 
        plugin_dir_url( __FILE__ ) . 'cart-affiliate.js', 
        array( 'jquery', 'wc-cart' ), 
        false, 
        true 
    );

    wp_localize_script( 'custom-plugin-js', 'wc_cart_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wc_cart_nonce' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'my_plugin_enqueue_scripts' );


function get_non_affiliate_meta_products_total() {
    $affiliate_total = 0;

  	$non_affiliate_total = WC()->cart->get_cart_contents_total() - get_affiliate_meta_products_total();

    return $non_affiliate_total ;
}

add_filter('woocommerce_locate_template', 'custom_cart_template_from_plugin', 10, 3);

function custom_cart_template_from_plugin($template, $template_name, $template_path) {
    if ($template_name === 'cart/cart.php') {
        $custom_template = plugin_dir_path(__FILE__) . 'cart.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    } else if ($template_name === 'order-details-item-affiliate.php') {
        $custom_template = plugin_dir_path(__FILE__) . 'order-details-item-affiliate.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }    
    } else if ($template_name === 'order/order-details.php') {
        $custom_template = plugin_dir_path(__FILE__) . 'order-details.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    } else if ($template_name === 'order-details-item-ads.php') {
        $custom_template = plugin_dir_path(__FILE__) . 'order-details-item-ads.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }    
    } else if ($template_name === 'checkout/thankyou.php') {
        $custom_template = plugin_dir_path(__FILE__) . 'thankyou.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }    
    } 
    return $template;
}

add_action('wp_enqueue_scripts', 'enqueue_custom_cart_styles');

function enqueue_custom_cart_styles() {
    if (is_cart()) {
        wp_enqueue_style('custom-cart-styles', plugin_dir_url(__FILE__) . 'style.css');
    }
}
function custom_enqueue_cart_script() {
    wp_enqueue_script( 'custom-cart-script', plugin_dir_url( __FILE__ ) . 'cart-affiliate.js', array( 'jquery' ), '1.0', true );

    wp_localize_script( 'custom-cart-script', 'wc_cart_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wc_cart_nonce' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_cart_script' );


if (!function_exists('cepatlakoo_confirmation_button_ads')) {
    function cepatlakoo_confirmation_button_ads($order_id)
    {
        global $cl_options;

        $cepatlakoo_button_confirmation = !empty($cl_options['cepatlakoo_payment_confirmation_buttons']) ? $cl_options['cepatlakoo_payment_confirmation_buttons'] : '';
        $cepatlakoo_form_text = !empty($cl_options['cepatlakoo_form_confirmation_text']) ? $cl_options['cepatlakoo_form_confirmation_text'] : __('Confirm Payment on Website', 'cepatlakoo');
        $cepatlakoo_wa_text = !empty($cl_options['cepatlakoo_wa_confirmation_text']) ? $cl_options['cepatlakoo_wa_confirmation_text'] : __('Confirm Payment via WhatsApp', 'cepatlakoo');
        $cepatlakoo_wa_number = !empty($cl_options['cepatlakoo_wa_confirmation_number']) ? $cl_options['cepatlakoo_wa_confirmation_number'] : '';
        $wa_confirmation_greeting_text = !empty($cl_options['cepatlakoo_wa_confirmation_greeting_text']) ? $cl_options['cepatlakoo_wa_confirmation_greeting_text'] : '';
        $cepatlakoo_confirmation_page = !empty($cl_options['cepatlakoo_select_confirmation']) ? $cl_options['cepatlakoo_select_confirmation'] : '';
        $cepatlakoo_general_bg_theme_color = !empty($cl_options['cepatlakoo_general_bg_theme_color']['background-color']) ? $cl_options['cepatlakoo_general_bg_theme_color']['background-color'] : '';

        $order = new WC_Order($order_id);
        $order_status = $order->get_status();
        $order_no = $order->get_order_number();
        $order_data = $order->get_data();
        $email = $order_data['billing']['email'];

        // Load WooCommerce email colour settings
        $bg         = get_option('woocommerce_email_background_color');
        $body       = get_option('woocommerce_email_body_background_color');
        $base       = get_option('woocommerce_email_base_color');
        $base_text  = wc_light_or_dark($base, '#202020', '#ffffff');
        $text       = get_option('woocommerce_email_text_color');
        // only show confirmation button only if order status is on-hold or pending
        if ($cepatlakoo_button_confirmation != 'none') :
            if (('completed' != $order_status) && $cepatlakoo_confirmation_page) :
                $style = (did_action('woocommerce_email_after_order_table')) ? 'style="background-color: ' . $base . '; padding: 13px 20px; font-size: 14px; color: ' . $base_text . ' !important; text-decoration: none;"' : 'class="button"'; ?>
                <div style="margin:30px 0;">
                    <?php if (($cepatlakoo_button_confirmation == 'web-form' || $cepatlakoo_button_confirmation == 'all')) : 
                        $site_url = get_site_url(); ?>
                        <p><a <?php echo $style; ?> href="<?php echo $site_url . '/konfirmasi-ads?orderid=' . $order_no . '&email=' . $email; ?>">Konfirmasi Bukti Partisipasi</a></p>
                    <?php endif;

                    if (($cepatlakoo_button_confirmation == 'whatsapp' || $cepatlakoo_button_confirmation == 'all')) :
                        if (strpos($cepatlakoo_wa_number, '-') !== false) {
                            $cepatlakoo_wa_number = str_replace('-', '', $cepatlakoo_wa_number);
                        }
                        if (preg_match('[^\+62]', $cepatlakoo_wa_number)) {
                            $wa_phone = str_replace('+62', '62', $cepatlakoo_wa_number);
                        } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '0') {
                            $cepatlakoo_wa_number = ltrim($cepatlakoo_wa_number, '0');
                            $wa_phone = '62' . $cepatlakoo_wa_number;
                        } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '8') {
                            $wa_phone = '62' . $cepatlakoo_wa_number;
                        } else {
                            $wa_phone = $cepatlakoo_wa_number;
                        }

                        if (strpos($wa_phone, "-")) {
                            $wa_phone = str_replace('-', '', $wa_phone);
                        }

                        $wa_base_url = 'https://api.whatsapp.com/';

                        $wa_confirmation_greeting_text = str_replace('%lakoo_order_id%', $order_no, $wa_confirmation_greeting_text);

                        $wa_confirmation_greeting_text = str_replace("&nbsp;", '', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("\n", '%0A', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("#", '%23', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("-", '%2D', $wa_confirmation_greeting_text);
                        $wa_confirmation_greeting_text = str_replace("&", '%26', $wa_confirmation_greeting_text);

                    ?>
                        <p><a <?php echo $style; ?> href="<?php echo $wa_base_url . 'send?phone=' . $wa_phone . '&text=' . esc_attr($wa_confirmation_greeting_text); ?>"><?php echo $cepatlakoo_wa_text; ?></a></p>
                    <?php endif; ?>
                </div>
            <?php endif;
        endif;
    }
}
