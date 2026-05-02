<?php

// Add Ads checkbox, URL field, and point estimation on the product edit page
add_action('dokan_product_edit_after_pricing', 'add_custom_ads_fields_on_edit');
function add_custom_ads_fields_on_edit($post) {
    ?>
    <div class="dokan-form-group">
        <label>
            <input type="checkbox" name="_is_ads_product" id="_is_ads_product" value="yes" <?php checked(get_post_meta(get_the_ID(), '_is_ads_product', true), 'yes'); ?>>
            <?php _e('Is Ads Product', 'dokan'); ?>
        </label>
    </div>

    <div class="dokan-form-group show_if_ads_simple" style="display: none;">
        <label for="_ads_product_url"><?php _e('Ads Product URL', 'dokan'); ?></label>
        <input type="url" name="_ads_product_url" id="_ads_product_url" class="dokan-form-control" value="<?php echo esc_url(get_post_meta(get_the_ID(), '_ads_product_url', true)); ?>" placeholder="https://marketplace.com/product-url">
        <p class="description"><?php _e('Enter the Ads URL for this product', 'dokan'); ?></p>
    </div>

    <div class="dokan-form-group show_if_ads_simple" style="display: none;">
        <label for="point_reward_estimation"><?php _e('Point Ads Reward Estimation', 'dokan'); ?></label>
        <input type="number" name="point_reward_estimation_ads" id="point_reward_estimation" class="dokan-form-control" value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_point_reward_estimation_ads', true)); ?>" placeholder="100">
        <p class="description"><?php _e('Enter the point estimation rewarded to customers.', 'dokan'); ?></p>
    </div>

    <script>
      (function($) {
          $(document).ready(function() {
              function toggleAffiliateFields() {
                  const isAffiliateChecked = $('#_is_ads_product').is(':checked');
                  const productType = $('#product_type').val();

                  if (isAffiliateChecked) {
                      if (productType === 'variable') {
                          // Show fields specific to variable Ads products
                          $('.show_if_ads_simple').hide();
                          $('.show_if_ads_variable').show();
                      } else {
                          // Show fields specific to simple Ads products
                          $('.show_if_ads_simple').show();
                          $('.show_if_ads_variable').hide();
                      }
                  } else {
                      // Hide all Ads fields if not an Ads product
                      $('.show_if_ads_simple').hide();
                      $('.show_if_ads_variable').hide();
                  }
              }

              // Initial check on page load
              toggleAffiliateFields();

              // Toggle on checkbox and product type change
              $('#_is_ads_product, #product_type').on('change', function() {
                  toggleAffiliateFields();
              });
          });
      })(jQuery);
  </script>
    <?php
}
// Hook into Dokan's process product meta to save custom Ads fields
add_action('dokan_process_product_meta', 'save_custom_ads_fields_dokan', 10, 2);
function save_custom_ads_fields_dokan($post_id) {
    // Check if the Ads checkbox is set in the form submission
    $is_ads_product = $_POST['_is_ads_product'];
    update_post_meta($post_id, '_is_ads_product', $is_ads_product);
    // Update the Ads product URL if the checkbox is checked
    if ($is_ads_product === 'yes' && !empty($_POST['_ads_product_url'])) {
        update_post_meta($post_id, '_ads_product_url', esc_url_raw($_POST['_ads_product_url']));
    } else {
        delete_post_meta($post_id, '_ads_product_url'); // Remove URL if unchecked
    }

    // Save Point Reward Estimation if provided
    if ($is_ads_product == 'yes' && isset($_POST['point_reward_estimation_ads'])) {
        $point_reward = intval($_POST['point_reward_estimation_ads']);
        update_post_meta($post_id, '_point_reward_estimation_ads', $point_reward);
        $new_setup = array();
        $new_setup[ 'point_level' ] = $point_reward;
        mycred_update_post_meta( $post_id, 'mycred_reward', $new_setup);
        // print("<pre>");
        // print_r($new_setup);
        // print_r($point_reward);
        // print("</pre>");
        // die(0);

    } else {
        delete_post_meta($post_id, 'point_level'); // Remove point estimation if unchecked
    }
}


// Add Ads URL field to the variation form
add_action('dokan_product_after_variation_pricing', 'add_ads_url_and_points_to_dokan_variations', 10, 3);
function add_ads_url_and_points_to_dokan_variations($loop, $variation_data, $variation) {
    // Retrieve the Ads URL and point reward estimation if available
    $affiliate_url = get_post_meta($variation->ID, '_ads_product_url', true);
    $point_reward = get_post_meta($variation->ID, '_point_reward_estimation_ads', true);
    $is_ads_product = get_post_meta($variation->ID, '_is_ads_product', true); // Check if the variation is affiliate

    // Only display fields if the variation is affiliate
    ?>
    <div class="show_if_ads_variable dokan-form-group variation-affiliate-url-<?php echo esc_attr($variation->ID); ?>" >
        <label for="variation_ads_url_<?php echo esc_attr($variation->ID); ?>"><?php _e('Ads Product URL', 'dokan'); ?></label>
        <input type="url" name="variation_ads_url[<?php echo esc_attr($variation->ID); ?>]" class="dokan-form-control" value="<?php echo esc_url($affiliate_url); ?>" placeholder="https://marketplace.com/product-url">
        <p class="description"><?php _e('Enter the Ads URL for this variation', 'dokan'); ?></p>
    </div>

    <div class="show_if_ads_variable dokan-form-group variation_point_ads_estimation_ads-<?php echo esc_attr($variation->ID); ?>" >
        <label for="variation_point_ads_estimation_ads<?php echo esc_attr($variation->ID); ?>"><?php _e('Point Ads Reward Estimation', 'dokan'); ?></label>
        <input type="number" name="variation_point_ads_estimation_ads[<?php echo esc_attr($variation->ID); ?>]" class="dokan-form-control" value="<?php echo esc_attr($point_reward); ?>" placeholder="100">
        <p class="description"><?php _e('Enter the point reward estimation for this variation', 'dokan'); ?></p>
    </div>
    <?php
}

// Save the Ads URL and Point Reward Estimation for each variation in Dokan
add_action('dokan_process_product_meta', 'save_ads_url_and_points_for_dokan_variations', 10, 2);

function save_ads_url_and_points_for_dokan_variations($post_id) {
    // Check if variations exist in the request
    if (isset($_POST['variation_ads_url'])) {
        foreach ($_POST['variation_ads_url'] as $variation_id => $affiliate_url) {
            // Ensure the variation ID is an integer
            $variation_id = intval($variation_id);
            
            if (!empty($affiliate_url)) {
                // Update the Ads product URL for the variation
                update_post_meta($variation_id, '_ads_product_url', esc_url_raw($affiliate_url));
            } else {
                // Remove URL if it's empty
                delete_post_meta($variation_id, '_ads_product_url');
            }
        }
    }
    
    // Save point reward estimation for variations
    if (isset($_POST['variation_point_reward_estimation'])) {
        foreach ($_POST['variation_point_reward_estimation'] as $variation_id => $point_reward) {
            // Ensure the variation ID is an integer
            $variation_id = intval($variation_id);

            // Check if the point reward is numeric and not empty
            if (is_numeric($point_reward) && !empty($point_reward)) {
                update_post_meta($variation_id, '_point_reward_estimation_ads', intval($point_reward));
            } else {
                // Remove point reward estimation if it's empty or non-numeric
                delete_post_meta($variation_id, '_point_reward_estimation_ads');
            }
        }
    }
}

// Hook into the Dokan save variation action
// Hook into the WooCommerce save variation action
add_action('woocommerce_save_product_variation', 'update_ads_url_and_point_reward_for_dokan_variations', 10, 2);

function update_ads_url_and_point_reward_for_dokan_variations($variation_id, $loop) {
    // Check if the Ads URL is set in the request
    if (isset($_POST['variation_ads_url'][$variation_id])) {
        $affiliate_url = $_POST['variation_ads_url'][$variation_id];
        
        if (!empty($affiliate_url)) {
            // Update the Ads product URL for the variation
            update_post_meta($variation_id, '_ads_product_url', esc_url_raw($affiliate_url));
        } else {
            // Remove the URL if it's empty
            delete_post_meta($variation_id, '_ads_product_url');
        }
    }

    // Check if the point reward estimation is set in the request
    if (isset($_POST['point_reward_estimation_ads'][$variation_id])) {
        $point_reward = $_POST['point_reward_estimation_ads'][$variation_id];
        
        if (!empty($point_reward) && is_numeric($point_reward)) {
            // Update the point reward estimation for the variation
            update_post_meta($variation_id, '_point_reward_estimation_ads', intval($point_reward));
        } else {
            // Remove the point reward estimation if it's empty or non-numeric
            delete_post_meta($variation_id, '_point_reward_estimation_ads');
        }
    }
}

add_action('woocommerce_checkout_order_processed', 'custom_dokan_sync_on_order_ads_create', 10, 1);

function custom_dokan_sync_on_order_ads_create($order_id) {
    // Get the WooCommerce order object
    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    // Check the _is_ads_product meta value
    $is_Ads = get_post_meta($order_id, '_is_ads_product', true);

    // Only sync if _is_ads_product is 'yes', 'no', or null
    if ($is_Ads === 'yes') {
        // Call the Dokan sync function
        if (function_exists('dokan_sync_insert_order')) {
            dokan_sync_insert_order($order_id);
        }
    }
}


// Add custom section to Dokan order details
add_action( 'dokan_komship', 'add_ads_payment_confirmation_dokan' );
function add_ads_payment_confirmation_dokan( $order ) {
    $order_id = $order->get_id();

    // Check if order status is 'completed'
    $order_status = $order->get_status();
    $is_completed = ($order_status === 'completed');
    
    // Get payment confirmations and point level Ads data
    $payment_confirmations = get_post_meta( $order_id, '_ads_payment_confirmation', true );
    $order_items = $order->get_items();
    foreach ($order_items as $item_id => $item) {
        $product_id = $item->get_product_id();
    }
    $poin_level_Ads = get_post_meta($product_id, '_point_reward_estimation_ads', true);
    
    echo '<div id="ads-metabox">';
    $vendor_points = [];
    $i = 0;
    if ( ! empty( $payment_confirmations ) ) {
        echo '<h4>' . __( 'Konfirmasi Pembayaran Ads', 'your-textdomain' ) . '</h4>';
        echo '<ul>';
        foreach ( $payment_confirmations as $confirmation ) {
            $product_id = $confirmation['product_id'];
            $product = wc_get_product( $product_id );
            $product_name = $product ? $product->get_name() : __( 'Produk tidak ditemukan', 'your-textdomain' );
            $product_author_id = get_post_field('post_author', $product_id);
            $vendor_point = mycred_get_users_balance($product_author_id, 'point_seller_internal');
            $vendor_points[] = $vendor_point; // Simpan untuk digunakan di JavaScript
            
            // Get product quantity from order
            $order_item_quantity = 0;
            foreach ( $order->get_items() as $item ) {
                if ( $item->get_product_id() == $product_id ) {
                    $order_item_quantity = $item->get_quantity();
                    break;
                }
            }
            
            $estimation_point_level = get_post_meta($product_id, 'point_reward_estimation_ads', true);
    
            echo '<li><strong>' . __( 'Produk:', 'your-textdomain' ) . '</strong> ' . esc_html( $product_name ) . ' x' . esc_html( $order_item_quantity ) . '</li>';
            echo '<li><strong>' . __( 'Produk ID:', 'your-textdomain' ) . '</strong> ' . esc_html( $product_id ) . '</li>';
            echo '<li><strong>' . __( 'Nomor Transaksi:', 'your-textdomain' ) . '</strong> ' . esc_html( $confirmation['transaction_id'] ) . '</li>';
            echo '<li><strong>' . __( 'Estimasi Reward Point Level:', 'your-textdomain' ) . '</strong> <span class="point-estimation">' . esc_html( $estimation_point_level * $order_item_quantity ) . '</span></li>';
            echo '<label><strong>Total Poin Level Yang Akan Dikirim</strong>: </label>';
            echo '<input type="number" class="poin-level-input" name="poin_level_affiliate[]" value="' . esc_attr( $poin_level_affiliate[$i] ?? '' ) . '" placeholder="Total Point Level" ' . ($is_completed ? 'disabled' : '') . ' data-vendor-balance="' . esc_attr( $vendor_point ) . '" data-product-name="' . esc_attr( $product_name ) . '">';
            echo '<input type="hidden" name="product_affiliate[]" value="' . esc_attr( $product_id ) . '">';
            echo '<br><br><hr>';
            $i++;
        }
        echo '</ul>';
    
        // Disable the button if order is completed
        echo '<button id="complete-order" data-order-id="' . esc_attr( $order_id ) . '" class="button-primary btn btn-warning" ' . ($is_completed ? 'disabled' : '') . '>' . __( 'Selesaikan Pesanan', 'your-textdomain' ) . '</button>';
    } else {
        echo '<p>' . __( 'Tidak ada konfirmasi pembayaran Ads untuk pesanan ini.', 'your-textdomain' ) . '</p>';
    }
    echo '</div>';
}

function handle_ads_product_zero_earning( $earning, $order, $context ) {
    // Loop through each item in the order
    foreach ( $order->get_items() as $item ) {
        // Get product and parent ID
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
        $parent_id = wp_get_post_parent_id( $product_id ) ?: $product_id;

        // Check if the parent product is marked as an Ads product
        $is_Ads = get_post_meta( $parent_id, '_is_ads_product', true );

        if ( $is_Ads === 'yes' ) {
            // Set earnings to 0 for both admin and seller contexts
            if ( $context === 'admin' || $context === 'seller' ) {
                return 0;
            }
        }
    }

    // Return the original earning if not Ads or context is different
    return $earning;
}

add_filter( 'dokan_get_earning_by_order', 'handle_ads_product_zero_earning', 10, 3 );

function customize_admin_ads_report_earnings( $data, $group_by, $year, $start, $end, $seller_id ) {
    foreach ( $data as &$row ) {
        $order = wc_get_order( $row->order_id );
        $is_ads_product = false;

        if ( $order ) {
            foreach ( $order->get_items() as $item ) {
                // Get product and parent ID
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                $parent_id = wp_get_post_parent_id( $product_id ) ?: $product_id;

                // Check if the parent product is marked as an Ads product
                $is_Ads = get_post_meta( $parent_id, '_is_ads_product', true );

                if ( $is_Ads === 'yes' ) {
                    $is_ads_product = true;
                    break;
                }
            }
        }

        if ( $is_ads_product ) {
            $row->earning = 0;
        }
    }
    return $data;
}

add_filter( 'dokan_get_admin_report_data', 'customize_admin_ads_report_earnings', 100, 6 );
