<?php
/**
 * Plugin Name: ads Plugin Product
 * Description: Handle Order ads Product
 * Version: 1.0
 * Author: Ruli Setiawan
 */
// start block add metabox
 add_action( 'woocommerce_product_options_general_product_data', 'custom_ads_product_field' );
 function custom_ads_product_field() {
     global $post;
     echo '<div class="options_group">';
 
     // Checkbox untuk Product ads
     woocommerce_wp_checkbox( array(
         'id'            => '_is_ads_product',
         'label'         => __('Ads Product', 'woocommerce'),
         'description'   => __('Check if this is an ads product', 'woocommerce'),
     ) );
 
     // Input Estimasi Reward Point
     woocommerce_wp_text_input( array(
         'id'            => 'point_reward_estimation_ads',
         'label'         => __('Estimasi Ads Reward Point (Jangan diisi jika produk variable)', 'woocommerce'),
         'placeholder'   => '100',
         'description'   => __('Enter the point level will rewarded to customer', 'woocommerce'),
         'desc_tip'      => true,
         'type'          => 'number',
         'value'         => get_post_meta( $post->ID, '_point_reward_estimation_ads', true ),
         'wrapper_class' => 'show_if_ads',
     ) );
 
     // Input Link Marketplace
     woocommerce_wp_text_input( array(
         'id'            => '_ads_product_url',
         'label'         => __('Ads Product URL (Jangan diisi jika produk variable)', 'woocommerce'),
         'placeholder'   => 'ads product url',
         'description'   => __('Enter the ads URL for this product', 'woocommerce'),
         'desc_tip'      => true,
         'type'          => 'url',
         'wrapper_class' => 'show_if_ads',
     ) );
 
     echo '</div>';
 }
 
 add_action( 'admin_footer', 'custom_ads_product_field_script' );
 function custom_ads_product_field_script() {
     ?>
     <script type="text/javascript">
         jQuery(document).ready(function($) {
             function toggleAffiliateFields() {
                 if ($('#_is_ads_product').is(':checked')) {
                     $('.show_if_ads').show();
                 } else {
                     $('.show_if_ads').hide();
                 }
             }
 
             // Initial check
             toggleAffiliateFields();
 
             // Toggle on checkbox change
             $('#_is_ads_product').change(function() {
                 toggleAffiliateFields();
             });
         });
     </script>
     <?php
 }
 
 add_action( 'woocommerce_process_product_meta', 'save_custom_ads_product_fields' );
 function save_custom_ads_product_fields( $post_id ) {
     // Simpan nilai checkbox Affiliate Product
     $is_ads_product = isset( $_POST['_is_ads_product'] ) ? 'yes' : 'no';
     update_post_meta( $post_id, '_is_ads_product', $is_ads_product );
 
     // Simpan nilai Estimasi Reward Point
     if ($is_ads_product == 'yes' && isset($_POST['point_reward_estimation_ads'])) {
         update_post_meta( $post_id, '_point_reward_estimation_ads', sanitize_text_field( $_POST['point_reward_estimation_ads'] ) );
         $new_setup = array();
         $new_setup[ 'point_level' ] = sanitize_text_field( $_POST['point_reward_estimation_ads']);
         $new_setup[ 'point_upgrade' ] = sanitize_text_field( $_POST['point_reward_estimation_ads']);
         mycred_update_post_meta( $post_id, 'mycred_reward', $new_setup);
     }
 
     // Simpan nilai link marketplace
     if ( isset( $_POST['_ads_product_url'] ) ) {
         update_post_meta( $post_id, '_ads_product_url', esc_url_raw( $_POST['_ads_product_url'] ) );
     }
 }
 
 // Menambahkan input link marketplace di setiap variasi produk
 add_action( 'woocommerce_product_after_variable_attributes', 'add_ads_link_to_variations', 10, 3 );
 function add_ads_link_to_variations( $loop, $variation_data, $variation ) {
     // Input Link Marketplace untuk variasi
     woocommerce_wp_text_input( array(
         'id'            => '_is_ads_product_' . $variation->ID,
         'name'          => 'variation_ads_product_url[' . $variation->ID . ']',
         'label'         => __('Ads Product URL', 'woocommerce'),
         'placeholder'   => 'ads produc url',
         'value'         => get_post_meta( $variation->ID, '_ads_product_url', true ),
         'description'   => __('Enter the ads URL for this variation', 'woocommerce'),
         'desc_tip'      => true,
         'type'          => 'url',
         'wrapper_class' => 'show_if_ads',
     ) );
     
     woocommerce_wp_text_input( array(
         'id'            => 'point_reward_estimation_ads_' . $variation->ID,
         'name'          => 'variation_point_reward_estimation[' . $variation->ID . ']',
         'label'         => __('Point Ads Reward Estimation', 'woocommerce'),
         'placeholder'   => '1000',
         'value'         => get_post_meta( $variation->ID, '_point_reward_estimation_ads', true ),
         'description'   => __('Enter point reward estimation for customer', 'woocommerce'),
         'desc_tip'      => true,
         'type'          => 'number',
         'wrapper_class' => 'show_if_ads',
     ) );
 }
 // Menyimpan nilai input link marketplace untuk setiap variasi
 add_action( 'woocommerce_save_product_variation', 'save_ads_link_variations', 10, 2 );
 function save_ads_link_variations( $variation_id, $i ) {
     // Simpan nilai link marketplace variasi
     if ( isset( $_POST['variation_ads_product_url'][$variation_id] ) ) {
         update_post_meta( $variation_id, '_ads_product_url', $_POST['variation_ads_product_url'][$variation_id] );
     }
     if( isset($_POST['variation_point_reward_estimation'][$variation_id])){
         update_post_meta( $variation_id, 'point_reward_estimation_ads', intval($_POST['variation_point_reward_estimation'][$variation_id]));
     }
 }
 
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_button_text', 90, 2);

function custom_add_to_cart_button_text($button_text, $product) {
    $special_flag = get_post_meta($product->get_id(), '_is_ads_product', true);
    if ($special_flag == 'yes') {
        return __('Order Now', 'your-textdomain');
    }
    return $button_text;
}

//add_action('woocommerce_add_to_cart_redirect', 'custom_redirect_after_add_to_cart', 10, 1);

function custom_redirect_after_add_to_cart($url) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        
        $product_id = $cart_item['product_id'];
        $meta_value = get_post_meta($product_id, '_is_ads_product', true);

        if ($meta_value == 'yes') {

            // Cek apakah order sudah ada untuk customer ini (hindari duplikasi)
            $existing_order = wc_get_orders([
                'limit' => 1,
                'customer_id' => get_current_user_id(),
                'status' => 'processing'
            ]);

            if (!empty($existing_order)) {
                error_log('Order sudah ada, langsung redirect.');
                return $existing_order[0]->get_checkout_order_received_url();
            }

            // Buat order baru
            $order = wc_create_order();
            $order->add_product(wc_get_product($product_id), $cart_item['quantity']);
            $order->set_status('processing');
            if (is_user_logged_in()) {
                $order->set_customer_id(get_current_user_id());
 				$order->update_meta_data('_customer_user', get_current_user_id());
            }
            $order->calculate_totals();
            $order->save();

            // Kosongkan keranjang agar tidak ada produk double
            WC()->cart->empty_cart();

            // Debugging
            error_log('Redirecting to order confirmation page: ' . $order->get_checkout_order_received_url());

            // Redirect
            wp_redirect($order->get_checkout_order_received_url());
            exit; // Hentikan eksekusi setelah redirect
        }
    }

    return $url; // Lanjutkan redirect default jika kondisi tidak terpenuhi
}

add_shortcode('ads_product_confirmation_form', 'ads_product_confirmation_form_shortcode');

function ads_product_confirmation_form_shortcode() {
    if (!is_user_logged_in()) {
        return __('You need to be logged in to confirm your ads product.', 'your-textdomain');
    }

    $order_id = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;
    if (!$order_id) {
        return __('Invalid order ID.', 'your-textdomain');
    }

    ob_start();
    ?>
    <style>
        .ads-confirmation-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .ads-confirmation-form p {
            margin-bottom: 15px;
        }
        .ads-confirmation-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .ads-confirmation-form input[type="text"],
        .ads-confirmation-form input[type="file"],
        .ads-confirmation-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .ads-confirmation-form input[type="submit"] {
            background-color: #0073aa;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .ads-confirmation-form input[type="submit"]:hover {
            background-color: #005177;
        }
    </style>

    <form method="post" enctype="multipart/form-data" class="ads-confirmation-form">
        <p>
            <label for="order_id"><?php _e('No Tugas:', 'your-textdomain'); ?></label>
            <input type="text" id="order_id" name="order_id" value="<?php echo esc_attr($order_id); ?>" readonly />
        </p>
        <?php
        $order = wc_get_order($order_id);
        foreach ($order->get_items() as $item_id => $item) {
            $product_id = $item->get_product_id();
            $product_name = $item->get_name();
            ?>
            <hr>
            <h3><?php echo $product_name; ?></h3>
            
            <p>
                <label>Unggah File untuk tugas<b><?php echo sprintf(__(' %s:', 'your-textdomain'), $product_name); ?></b></label>
                <input type="file" name="ads_confirmation_file[<?php echo $product_id; ?>]" required />
            </p>
           
            <p>
                <label>Keterangan untuk tugas <b><?php echo sprintf(__(' %s:', 'your-textdomain'), $product_name); ?></b></label>
                <textarea name="ads_confirmation_note[<?php echo $product_id; ?>]" rows="4" placeholder="<?php _e('Enter additional notes here...', 'your-textdomain'); ?>"></textarea>
            </p>
            <?php
        }
        ?>
        <p>
            <input type="submit" name="submit_ads_confirmation" value="<?php _e('Submit Tugas', 'your-textdomain'); ?>" />
        </p>
    </form>
    <?php

    if (isset($_POST['submit_ads_confirmation'])) {
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);

        if ($order) {
            foreach ($_FILES['ads_confirmation_file']['name'] as $product_id => $filename) {
                if ($_FILES['ads_confirmation_file']['error'][$product_id] == UPLOAD_ERR_OK) {
                    $file = array(
                        'name'     => $_FILES['ads_confirmation_file']['name'][$product_id],
                        'type'     => $_FILES['ads_confirmation_file']['type'][$product_id],
                        'tmp_name' => $_FILES['ads_confirmation_file']['tmp_name'][$product_id],
                        'error'    => $_FILES['ads_confirmation_file']['error'][$product_id],
                        'size'     => $_FILES['ads_confirmation_file']['size'][$product_id],
                    );

                    $upload = wp_handle_upload($file, array('test_form' => false));
                    if ($upload && !isset($upload['error'])) {
                        update_post_meta($order_id, 'has_ads_confirmation', 'yes');
                        update_post_meta($order_id, '_ads_confirmation_file_' . $product_id, $upload['url']);
                        update_post_meta($order_id, '_ads_confirmation_note_' . $product_id, sanitize_textarea_field($_POST['ads_confirmation_note'][$product_id]));
                    } else {
                        echo '<p>' . __('Error uploading file for product ID ' . $product_id, 'your-textdomain') . '</p>';
                    }
                } else {
                    echo '<p>' . __('Error with file upload for product ID ' . $product_id, 'your-textdomain') . '</p>';
                }
            }

            wp_redirect(home_url('/dasbor'));
            exit;
        } else {
            echo '<p>' . __('Invalid order ID.', 'your-textdomain') . '</p>';
        }
    }

    return ob_get_clean();
}


//add_filter('woocommerce_loop_add_to_cart_link', 'custom_ads_product_button', 10, 2);

function custom_ads_product_button($button, $product) {
    $meta_value = get_post_meta($product->get_id(), '_is_ads_product', true);

    if ($meta_value == 'yes') {
        $url = add_query_arg('ads_product_id', $product->get_id(), site_url('/custom-redirect/'));
        $button = '<a href="' . esc_url($url) . '" class="button product_type_variable add_to_cart_button">Beli Sekarang</a>';
    }

    return $button;
}

//add_action('template_redirect', 'handle_ads_product_redirect');

function handle_ads_product_redirect() {
    if (isset($_GET['ads_product_id'])) {
        $product_id = intval($_GET['ads_product_id']);

        // Cek apakah produk memiliki meta _is_ads_product = yes
        $meta_value = get_post_meta($product_id, '_is_ads_product', true);
        if ($meta_value != 'yes') {
            wp_redirect(home_url());
            exit;
        }

        // Cek jika order sudah ada untuk menghindari duplikasi
        $existing_order = wc_get_orders([
            'limit' => 1,
            'customer_id' => get_current_user_id(),
            'status' => 'processing'
        ]);

        if (!empty($existing_order)) {
            wp_redirect($existing_order[0]->get_checkout_order_received_url());
            exit;
        }

        // Buat order baru
        $order = wc_create_order();
        $order->add_product(wc_get_product($product_id), 1); // Default quantity = 1
        $order->set_status('processing');

        if (is_user_logged_in()) {
            $order->set_customer_id(get_current_user_id());
            $order->update_meta_data('_customer_user', get_current_user_id());
        }

        $order->calculate_totals();
        $order->save();

        // Redirect ke halaman "Thank You"
        wp_redirect($order->get_checkout_order_received_url());
        exit;
    }
}

add_filter('woocommerce_thankyou_order_received_text', 'custom_thankyou_text', 10, 2);

function custom_thankyou_text($thank_you_title, $order) {
    if($order){
        if($order->get_status() == 'processing'){
            $is_ads_product = false;
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                if (get_post_meta($product_id, '_is_ads_product', true) == 'yes') {
                    $is_ads_product = true;
                    break;
                }
            }
    
            if ($is_ads_product) {
                return __('Terima kasih telah mengikuti Program promosi kami.', 'your-textdomain');
            }
        }
    }
    
}

 
add_action('woocommerce_thankyou', 'custom_thankyou_message_before_button', 20);

function custom_thankyou_message_before_button($order_id) {
    $order = wc_get_order($order_id);
    $is_ads_product = false;

    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        if (get_post_meta($product_id, '_is_ads_product', true) == 'yes') {
            $is_ads_product = true;
            break;
        }
    }

    if ($is_ads_product) {
        echo '<p>' . __('Terima kasih telah mengikuti Program promosi kami. Silakan konfirmasi pembayaran Anda dengan mengunggah bukti pembayaran.', 'your-textdomain') . '</p>';
    }
}

//add_filter('woocommerce_available_payment_gateways', 'custom_filter_payment_methods');

function custom_filter_payment_methods($gateways) {
    if (is_checkout()) {
    $cart = WC()->cart->get_cart();
    $has_ads_product = false;

    foreach ($cart as $cart_item) {
        $product_id = $cart_item['product_id'];
        $meta_value = get_post_meta($product_id, '_is_ads_product', true);

        if ($meta_value == 'yes') {
            $has_ads_product = true;
            break;
        }
    }

    if ($has_ads_product) {
      
        foreach ($gateways as $gateway_id => $gateway) {
            if ($gateway_id !== 'cod') { // Hanya izinkan metode COD
                unset($gateways[$gateway_id]);
            }
        }
    }
    }
    return $gateways;
}

// add_action('woocommerce_checkout_update_order_review', 'custom_set_default_payment_method');

// function custom_set_default_payment_method($posted_data) {
//     parse_str($posted_data, $form_data);
    
//     $cart = WC()->cart->get_cart();
//     $has_ads_product = false;

//     foreach ($cart as $cart_item) {
//         $product_id = $cart_item['product_id'];
//         $meta_value = get_post_meta($product_id, '_is_ads_product', true);

//         if ($meta_value == 'yes') {
//             $has_ads_product = true;
//             break;
//         }
//     }

//     if ($has_ads_product) {
//         WC()->session->set('payment_method',  'cl_payment_bca');
//     }
// }

//add_filter('woocommerce_payment_gateways_supports', 'enable_cod_for_ads_products', 100, 2);

function enable_cod_for_ads_products($supports, $gateway) {
    if ($gateway->id === 'cod') {
        $cart = WC()->cart->get_cart();
        foreach ($cart as $cart_item) {
            $product_id = $cart_item['product_id'];
            $meta_value = get_post_meta($product_id, '_is_ads_product', true);
            if ($meta_value == 'yes') {
                $supports[] = 'virtual';
                break;
            }
        }
    }
    return $supports;
}

//add_filter('woocommerce_checkout_fields', 'disable_payment_method_validation_for_ads_products');

function disable_payment_method_validation_for_ads_products($fields) {
    if (is_checkout()){
    $cart = WC()->cart->get_cart();
    $has_ads_product = false;

    foreach ($cart as $cart_item) {
        $product_id = $cart_item['product_id'];
        $meta_value = get_post_meta($product_id, '_is_ads_product', true);

        if ($meta_value == 'yes') {
            $has_ads_product = true;
            break;
        }
    }

    if ($has_ads_product) {
        remove_action('woocommerce_checkout_process', 'woocommerce_checkout_payment_method');
    }
   }

    return $fields;
}


//add_filter('woocommerce_available_payment_gateways', 'add_cod_to_available_gateways');

function add_cod_to_available_gateways($available_gateways) {
    if (is_checkout()) {
        $cart = WC()->cart->get_cart();
        $has_ads_product = false;

        foreach ($cart as $cart_item) {
            $product_id = $cart_item['product_id'];
            $meta_value = get_post_meta($product_id, '_is_ads_product', true);

            if ($meta_value == 'yes') {
                $has_ads_product = true;
                break;
            }
        }

        if ($has_ads_product && !isset($available_gateways['cod'])) {
            $available_gateways['cod'] = new WC_Gateway_COD();
        }
    }
    return $available_gateways;
}

add_shortcode('list_coupons_chalenge', 'list_coupons_shortcode');

function list_coupons_shortcode() {
    if (!is_user_logged_in()) {
        return __('You need to be logged in to view the coupons.', 'your-textdomain');
    }

    $coupon_ids = array(11769);
    $coupons = array();
    foreach ($coupon_ids as $coupon_id) {
        $coupon = new WC_Coupon($coupon_id);
        if ($coupon->get_id()) {
            $coupons[] = $coupon;
        }
    }
    if (empty($coupons)) {
        return __('No coupons found.', 'your-textdomain');
    }

    ob_start();
    ?>
    <table class="shop_table shop_table_responsive">
        <thead>
            <tr>
                <th><?php _e('Coupon Code', 'your-textdomain'); ?></th>
                <th><?php _e('Description', 'your-textdomain'); ?></th>
                <th><?php _e('Usage Limit', 'your-textdomain'); ?></th>
                <th><?php _e('Usage Count', 'your-textdomain'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($coupons as $coupon) : ?>
                <tr>
                    <td><?php echo esc_html($coupon->get_code()); ?></td>
                    <td><?php echo esc_html($coupon->get_description()); ?></td>
                    <td><?php echo esc_html($coupon->get_usage_limit()); ?></td>
                    <td><?php echo esc_html($coupon->get_usage_count()); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}