<?php

add_filter( 'dokan_get_order_status_class', 'dokan_add_custom_order_status_button_class', 10, 2 );
function dokan_add_custom_order_status_button_class( $text, $status ) {
    switch ( $status ) {
		case 'wc-waiting-conf':
        case 'waiting-conf':
			$text = 'info';
			break;
		case 'wc-waiting-confirm':
		case 'waiting-confirm':
			$text = 'warning';
			break;
		case 'wc-shipped':
		case 'shipped':
			$text = 'warning';
			break;
		case 'wc-pending':
		case 'pending':
			$text = 'danger';
			break;
			
    }       
    return $text;
}

add_filter( 'dokan_get_order_status_translated', 'dokan_add_custom_order_status_translated', 10, 2 );
function dokan_add_custom_order_status_translated( $text, $status ) {
    switch ( $status ) {
		case 'wc-pending':
		case 'pending':
			$text = __('Waiting payment', 'dokan-lite');
			break;
		case 'wc-waiting-confirm':
		case 'waiting-confirm':
		case 'wc-waiting-conf':
        case 'waiting-conf':
			$text = __( 'Waiting confirm', 'dokan-lite' );
			break;
		case 'wc-shipped':
		case 'shipped':
			$text = __( 'Shipping', 'dokan-lite');
			break;
		default:
			$text = __( $status, 'dokan-lite');
    }       
    return $text;
}

add_filter('woocommerce_admin_order_actions','dokan_process_button_when_order_status_waiting_confirm',10,2);

function dokan_process_button_when_order_status_waiting_confirm($actions, $order){
	if( in_array( $order->get_status(), ['pending', 'wc-pending'] ,true)){
		if(isset($actions['processing'])){
			unset($actions['processing']);
		}
	}
	if( in_array($order->get_status(), ['pending', 'wc-pending','waiting-confirm'], true)){
		if(isset($actions['view'])){
			unset($actions['view']);
		}
	}
	if(( in_array( $order->get_status(), [ 'wc-waiting-conf', 'waiting-conf' ], true ) )){
		$actions['processing'] = [
			'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=dokan-mark-order-processing&order_id=' . $order->get_id() ), 'dokan-mark-order-processing' ),
			'name'   => __( 'Processing', 'dokan-lite' ),
			'action' => 'processing',
			'icon'   => '<i class="far fa-clock">&nbsp;</i>',
		];
	}
	if (isset($actions['complete'])) {
    	unset($actions['complete']);
	}

	return $actions;
}

add_action('wp_footer', 'remove_dokan_products_class');

function remove_dokan_products_class() { ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Select the dashboard widget with class 'dokan-dashboard-widget' and remove the 'products' class
                $('.dashboard-widget.products').removeClass('products');
				$('.wc-order-data-row.wc-order-totals-items.wc-order-items-editable').addClass('dokan-clearfix');
            });
        </script>
        <?php
}

add_action('wp_head', 'custom_dokan_css');
function custom_dokan_css(){ ?>
	<style>
		table.woocommerce_order_items.dokan-table.dokan-table-strip.table{
			white-space: nowrap;
		}
	</style>
<?php
}
// Add Affiliate checkbox and URL field on the product edit page
// Add Affiliate checkbox, URL field, and point estimation on the product edit page
add_action('dokan_product_edit_after_pricing', 'add_custom_affiliate_fields_on_edit');
function add_custom_affiliate_fields_on_edit($post) {
    ?>
    <div class="dokan-form-group">
        <label>
            <input type="checkbox" name="_is_affiliate_product" id="_is_affiliate_product" value="yes" <?php checked(get_post_meta(get_the_ID(), '_is_affiliate_product', true), 'yes'); ?>>
            <?php _e('Is Affiliate Product', 'dokan'); ?>
        </label>
    </div>

    <div class="dokan-form-group show_if_affiliate_simple" style="display: none;">
        <label for="_affiliate_product_url"><?php _e('Affiliate Product URL', 'dokan'); ?></label>
        <input type="url" name="_affiliate_product_url" id="_affiliate_product_url" class="dokan-form-control" value="<?php echo esc_url(get_post_meta(get_the_ID(), '_affiliate_product_url', true)); ?>" placeholder="https://marketplace.com/product-url">
        <p class="description"><?php _e('Enter the affiliate URL for this product', 'dokan'); ?></p>
    </div>

    <div class="dokan-form-group show_if_affiliate_simple" style="display: none;">
        <label for="point_reward_estimation"><?php _e('Point Reward Estimation', 'dokan'); ?></label>
        <input type="number" name="point_reward_estimation" id="point_reward_estimation" class="dokan-form-control" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'point_reward_estimation', true)); ?>" placeholder="100">
        <p class="description"><?php _e('Enter the point estimation rewarded to customers.', 'dokan'); ?></p>
    </div>

    <script>
      (function($) {
          $(document).ready(function() {
              function toggleAffiliateFields() {
                  const isAffiliateChecked = $('#_is_affiliate_product').is(':checked');
                  const productType = $('#product_type').val();

                  if (isAffiliateChecked) {
                      if (productType === 'variable') {
                          // Show fields specific to variable affiliate products
                          $('.show_if_affiliate_simple').hide();
                          $('.show_if_affiliate_variable').show();
                      } else {
                          // Show fields specific to simple affiliate products
                          $('.show_if_affiliate_simple').show();
                          $('.show_if_affiliate_variable').hide();
                      }
                  } else {
                      // Hide all affiliate fields if not an affiliate product
                      $('.show_if_affiliate_simple').hide();
                      $('.show_if_affiliate_variable').hide();
                  }
              }

              // Initial check on page load
              toggleAffiliateFields();

              // Toggle on checkbox and product type change
              $('#_is_affiliate_product, #product_type').on('change', function() {
                  toggleAffiliateFields();
              });
          });
      })(jQuery);
  </script>
    <?php
}
// Hook into Dokan's process product meta to save custom affiliate fields
add_action('dokan_process_product_meta', 'save_custom_affiliate_fields_dokan', 10, 2);
function save_custom_affiliate_fields_dokan($post_id) {
    // Check if the affiliate checkbox is set in the form submission
    $is_affiliate_product = isset($_POST['_is_affiliate_product']) ? 'yes' : 'no';
    update_post_meta($post_id, '_is_affiliate_product', $is_affiliate_product);

    // Update the affiliate product URL if the checkbox is checked
    if ($is_affiliate_product === 'yes' && !empty($_POST['_affiliate_product_url'])) {
        update_post_meta($post_id, '_affiliate_product_url', esc_url_raw($_POST['_affiliate_product_url']));
    } else {
        delete_post_meta($post_id, '_affiliate_product_url'); // Remove URL if unchecked
    }

    // Save Point Reward Estimation if provided
    if ($is_affiliate_product === 'yes' && isset($_POST['point_reward_estimation'])) {
        update_post_meta($post_id, 'point_reward_estimation', sanitize_text_field($_POST['point_reward_estimation']));
    } else {
        delete_post_meta($post_id, 'point_reward_estimation'); // Remove point estimation if unchecked
    }
}


// Add affiliate URL field to the variation form
add_action('dokan_product_after_variation_pricing', 'add_affiliate_url_and_points_to_dokan_variations', 10, 3);
function add_affiliate_url_and_points_to_dokan_variations($loop, $variation_data, $variation) {
    // Retrieve the affiliate URL and point reward estimation if available
    $affiliate_url = get_post_meta($variation->ID, '_affiliate_product_url', true);
    $point_reward = get_post_meta($variation->ID, 'point_reward_estimation', true);
    $is_affiliate_product = get_post_meta($variation->ID, '_is_affiliate_product', true); // Check if the variation is affiliate

    // Only display fields if the variation is affiliate
    ?>
    <div class="show_if_affiliate_variable dokan-form-group variation-affiliate-url-<?php echo esc_attr($variation->ID); ?>" >
        <label for="variation_affiliate_url_<?php echo esc_attr($variation->ID); ?>"><?php _e('Affiliate Product URL', 'dokan'); ?></label>
        <input type="url" name="variation_affiliate_url[<?php echo esc_attr($variation->ID); ?>]" class="dokan-form-control" value="<?php echo esc_url($affiliate_url); ?>" placeholder="https://marketplace.com/product-url">
        <p class="description"><?php _e('Enter the affiliate URL for this variation', 'dokan'); ?></p>
    </div>

    <div class="show_if_affiliate_variable dokan-form-group variation-point-reward-estimation-<?php echo esc_attr($variation->ID); ?>" >
        <label for="variation_point_reward_estimation_<?php echo esc_attr($variation->ID); ?>"><?php _e('Point Reward Estimation', 'dokan'); ?></label>
        <input type="number" name="variation_point_reward_estimation[<?php echo esc_attr($variation->ID); ?>]" class="dokan-form-control" value="<?php echo esc_attr($point_reward); ?>" placeholder="100">
        <p class="description"><?php _e('Enter the point reward estimation for this variation', 'dokan'); ?></p>
    </div>
    <?php
}

// Save the Affiliate URL and Point Reward Estimation for each variation in Dokan
add_action('dokan_process_product_meta', 'save_affiliate_url_and_points_for_dokan_variations', 10, 2);

function save_affiliate_url_and_points_for_dokan_variations($post_id) {
    // Check if variations exist in the request
    if (isset($_POST['variation_affiliate_url'])) {
        foreach ($_POST['variation_affiliate_url'] as $variation_id => $affiliate_url) {
            // Ensure the variation ID is an integer
            $variation_id = intval($variation_id);
            
            if (!empty($affiliate_url)) {
                // Update the affiliate product URL for the variation
                update_post_meta($variation_id, '_affiliate_product_url', esc_url_raw($affiliate_url));
            } else {
                // Remove URL if it's empty
                delete_post_meta($variation_id, '_affiliate_product_url');
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
                update_post_meta($variation_id, 'point_reward_estimation', intval($point_reward));
            } else {
                // Remove point reward estimation if it's empty or non-numeric
                delete_post_meta($variation_id, 'point_reward_estimation');
            }
        }
    }
}

add_filter( 'product_type_selector', 'remove_product_types' );

function remove_product_types( $types ){
    unset( $types['grouped'] );
    unset( $types['external'] );

    return $types;
}

function remove_product_types_dokan( $product_types ){
	unset( $product_types['external'] );
	unset( $product_types['grouped'] );
    return $product_types;
}
add_filter( 'dokan_product_types', 'remove_product_types_dokan', 11 );


// Hook into the Dokan save variation action
// Hook into the WooCommerce save variation action
add_action('woocommerce_save_product_variation', 'update_affiliate_url_and_point_reward_for_dokan_variations', 10, 2);

function update_affiliate_url_and_point_reward_for_dokan_variations($variation_id, $loop) {
    // Check if the affiliate URL is set in the request
    if (isset($_POST['variation_affiliate_url'][$variation_id])) {
        $affiliate_url = $_POST['variation_affiliate_url'][$variation_id];
        
        if (!empty($affiliate_url)) {
            // Update the affiliate product URL for the variation
            update_post_meta($variation_id, '_affiliate_product_url', esc_url_raw($affiliate_url));
        } else {
            // Remove the URL if it's empty
            delete_post_meta($variation_id, '_affiliate_product_url');
        }
    }

    // Check if the point reward estimation is set in the request
    if (isset($_POST['variation_point_reward_estimation'][$variation_id])) {
        $point_reward = $_POST['variation_point_reward_estimation'][$variation_id];
        
        if (!empty($point_reward) && is_numeric($point_reward)) {
            // Update the point reward estimation for the variation
            update_post_meta($variation_id, 'point_reward_estimation', intval($point_reward));
        } else {
            // Remove the point reward estimation if it's empty or non-numeric
            delete_post_meta($variation_id, 'point_reward_estimation');
        }
    }
}

add_action('woocommerce_checkout_order_processed', 'custom_dokan_sync_on_order_create', 10, 1);

function custom_dokan_sync_on_order_create($order_id) {
    // Get the WooCommerce order object
    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    // Check the _is_affiliate_product meta value
    $is_affiliate = get_post_meta($order_id, '_is_affiliate_product', true);

    // Only sync if _is_affiliate_product is 'yes', 'no', or null
    if ($is_affiliate === 'yes') {
        // Call the Dokan sync function
        if (function_exists('dokan_sync_insert_order')) {
            dokan_sync_insert_order($order_id);
        }
    }
}

function custom_hide_dokan_edit_status() {
    echo '<style>
        .dokan-edit-status {
            display: none !important;
        }
    </style>';
}
add_action('wp_head', 'custom_hide_dokan_edit_status');

// remove dokan shipping
function remove_dokan_shipping_tax_content() {
    global $dokan;
    if (isset($dokan->dashboard->templates->products)) {
        remove_action(
            'dokan_product_edit_after_inventory_variants',
            array($dokan->dashboard->templates->products, 'load_shipping_tax_content'),
            10
        );
    }
}

add_action('init', 'remove_dokan_shipping_tax_content', 20);


// Add the custom PIN Settings menu to the Dokan Dashboard
add_filter( 'dokan_get_dashboard_settings_nav', 'dokan_add_pin_settings_menu',11 );

function dokan_add_pin_settings_menu( $settings_nav ) {
    $settings_nav['pin_settings'] = array(
        'title'      => __( 'PIN Settings', 'dokan' ),
        'icon'       => '<i class="fas fa-key"></i>',
        'url'        => dokan_get_navigation_url( 'settings/pin_settings' ),
        'pos'        => 80, // Adjust position as needed
//         'permission' => 'dokan_view_store_social_menu' // Permissions for vendor
    );

    return $settings_nav;
}

// Hook into Dokan's settings content renderer
add_action( 'dokan_render_settings_content', 'dokan_render_pin_settings_content' );

function dokan_render_pin_settings_content( $query_vars ) {
    global $wp;

    // Check if we're on the "PIN Settings" page
    if ( isset( $wp->query_vars['settings'] ) && 'pin_settings' === $wp->query_vars['settings'] ) {
//         if ( ! current_user_can( 'dokan_manage_store_settings' ) ) {
//             dokan_get_template_part(
//                 'global/dokan-error', '', [
//                     'deleted' => false,
//                     'message' => __( 'You have no permission to view this page', 'dokan-lite' ),
//                 ]
//             );
//             return;
//         }
		display_withdraw_message(); 
        ?>
		
        <form method="post" id="dokan-pin-settings-form" class="dokan-form">
            <?php wp_nonce_field( 'dokan_pin_settings_nonce', 'dokan_pin_settings_nonce_field' ); ?>

            <div class="dokan-form-group">
                <label for="dokan_password"><?php esc_html_e( 'Enter Your Password', 'dokan' ); ?></label>
                <input type="password" name="dokan_password" id="dokan_password" class="dokan-form-control" required>
                <p class="help-block"><?php esc_html_e( 'For security purposes, please enter your password to set or update your PIN.', 'dokan' ); ?></p>
            </div>

            <div class="dokan-form-group">
                <label for="dokan_withdraw_pin"><?php esc_html_e( 'Set or Update Your 6-digit PIN', 'dokan' ); ?></label>
                <input type="password" name="dokan_withdraw_pin" id="dokan_withdraw_pin" class="dokan-form-control" maxlength="6" pattern="\d{6}" placeholder="<?php esc_attr_e( 'Enter a 6-digit PIN', 'dokan' ); ?>" required>
                <p class="help-block"><?php esc_html_e( 'This PIN will be used for secure withdrawals.', 'dokan' ); ?></p>
            </div>

            <div class="dokan-form-group">
                <button type="submit" class="dokan-btn dokan-btn-theme"><?php esc_html_e( 'Save PIN', 'dokan' ); ?></button>
            </div>
        </form>
        <?php
    }
}


// Modify the header title for the "PIN Settings" page
add_filter( 'dokan_dashboard_settings_heading_title', 'dokan_custom_pin_settings_heading_title', 10, 2 );

function dokan_custom_pin_settings_heading_title( $heading, $query_vars ) {
    global $wp;

    // Check if we're on the "PIN Settings" page
    if ( isset( $wp->query_vars['settings'] ) && 'pin_settings' === $wp->query_vars['settings'] ) {
        $heading = __( 'Pin Settings', 'dokan' ); // Custom header title
    }

    return $heading;
}
add_action( 'wp_footer', 'dokan_pin_restrict_to_digits' );

function dokan_pin_restrict_to_digits() {
    ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Check if the PIN input exists on the page
            const pinInput = document.getElementById('dokan_withdraw_pin');
            
            // Only add event listener if the input element is found
            if (pinInput) {
                pinInput.addEventListener('input', function(event) {
                    // Allow only numbers and limit the input to 6 digits
                    this.value = this.value.replace(/\D/g, '').slice(0, 6);
                });
            }
        });
    </script>
    <?php
}

add_action( 'init', 'dokan_save_withdraw_pin' );

function dokan_save_withdraw_pin() {
    // Initialize messages array
    $messages = array();

    // Check if the form was submitted
    if ( isset( $_POST['dokan_withdraw_pin'] ) && isset( $_POST['dokan_pin_settings_nonce_field'] ) ) {
        // Verify the nonce for security
        if ( ! wp_verify_nonce( $_POST['dokan_pin_settings_nonce_field'], 'dokan_pin_settings_nonce' ) ) {
            $messages[] = __( 'Security check failed. Please try again.', 'dokan' );
            set_withdraw_message( $messages );
            return;
        }

        // Get the current user ID
        $user_id = get_current_user_id();

        // Verify entered password
        if ( isset( $_POST['dokan_password'] ) && ! empty( $_POST['dokan_password'] ) ) {
            $entered_password = sanitize_text_field( $_POST['dokan_password'] );
            $user = get_user_by( 'id', $user_id );

            // Verify password with stored hash
            if ( ! wp_check_password( $entered_password, $user->user_pass, $user_id ) ) {
                $messages[] = __( 'Incorrect password. Please try again.', 'dokan' );
                set_withdraw_message( $messages );
                return;
            }
        } else {
            $messages[] = __( 'Please enter your password.', 'dokan' );
            set_withdraw_message( $messages );
            return;
        }

        // Validate and sanitize the PIN
        $pin = sanitize_text_field( $_POST['dokan_withdraw_pin'] );
        if ( ! preg_match( '/^\d{6}$/', $pin ) ) {
            $messages[] = __( 'Please enter a valid 6-digit numeric PIN.', 'dokan' );
            set_withdraw_message( $messages );
            return;
        }

        // Hash the PIN and save it to user meta
        $hashed_pin = password_hash( $pin, PASSWORD_DEFAULT );
        update_user_meta( $user_id, '_dokan_withdraw_pin', $hashed_pin );

        // Success message
        $messages[] = __( 'PIN saved successfully!', 'dokan' );
        set_withdraw_message( $messages );
    }
}

// Function to store messages for displaying
function set_withdraw_message( $messages ) {
    // Store messages to be displayed globally
    $_SESSION['dokan_withdraw_messages'] = $messages;
}

function display_withdraw_message() {
    if ( isset( $_SESSION['dokan_withdraw_messages'] ) && ! empty( $_SESSION['dokan_withdraw_messages'] ) ) {
        // Loop through each message
        foreach ( $_SESSION['dokan_withdraw_messages'] as $message ) {
            // Check if the message indicates success or error
            if ( strpos( $message, 'success' ) !== false ) {
                // Success message: Use dokan-message class
                echo '<div class="dokan-message">';
                echo '<p class="dokan-message-text">' . esc_html( $message ) . '</p>';
                echo '</div>';
            } else {
                // Error message: Use dokan-error class
                echo '<div class="dokan-error">';
                echo '<p class="dokan-error-text">' . esc_html( $message ) . '</p>';
                echo '</div>';
            }
        }

        // Clear the messages after displaying them
        unset( $_SESSION['dokan_withdraw_messages'] );
    }
}

// Enqueue SelectWoo assets
function force_dokan_selectwoo() {
    // Only load on Dokan pages
    if (!dokan_is_seller_dashboard() && !is_shop() && !is_product()) {
        return;
    }
   
    
    // Deregister any existing select2 to avoid conflicts
    wp_deregister_script('select2');
    wp_deregister_style('select2');

    // Enqueue SelectWoo
    wp_enqueue_style('selectWoo', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION);
    wp_enqueue_script('selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array('jquery'), WC_VERSION, true);
    
    
}
add_action('wp_enqueue_scripts', 'force_dokan_selectwoo', 100);

// Optional: Force SelectWoo in admin if needed
function force_dokan_admin_selectwoo() {
    if (!is_admin()) {
        return;
    }

    // Check if we're on a Dokan admin page
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'dokan') === false) {
        return;
    }

    wp_enqueue_style('selectWoo', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION);
    wp_enqueue_script('selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array('jquery'), WC_VERSION, true);
}
add_action('admin_enqueue_scripts', 'force_dokan_admin_selectwoo', 100);

function add_custom_zip_dokan($address, $seller_address_fields) { ?>
    <div class="dokan-form-group">
        <label for="postcode" class="dokan-form-label"><?php _e('Postcode', 'your-textdomain'); ?> <span class="dokan-required">*</span></label>
        <select name="postcode" id="postcode_dokan" class="dokan-form-control" required>
            <option value=""><?php _e('Select Postcode', 'shopbiz.id'); ?></option>
        </select>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            var address = <?php echo json_encode($address); ?>;
    
            
            if (address && address.address_id && address.address_label) {
                
                var optionHtml = '<option value=\'' + JSON.stringify(address) + '\' ' + 'selected'  + '>' + address.address_label + '</option>';
    
                
                $('#postcode_dokan').append(optionHtml);
    
                $('#postcode_dokan').val(JSON.stringify(address));
            }
        });
    </script>

<?php }

add_action('dokan_vendor_address_verification_template', 'add_custom_zip_dokan', 999, 2);

add_filter('dokan_seller_address_fields','remove_dokan_zip_field');

function remove_dokan_zip_field($args){
    if(isset($args['zip'])) {
        unset($args['zip']);
    }
    return $args;
}
add_filter('dokan_store_profile_settings_args', 'add_postcode_into_dokan_store', 99, 2);

function add_postcode_into_dokan_store($dokan_settings, $store_id) {
    
    $postcode = isset($_POST['postcode']) ? sanitize_text_field(wp_unslash($_POST['postcode'])) : '';
    
    if ($postcode) {
        
        $decoded_postcode = json_decode($postcode, true); 

        
        if (is_array($decoded_postcode) && isset($decoded_postcode['zip_code']) && isset($decoded_postcode['id'])) {
            // Update user meta with the decoded zip_code
            update_user_meta($store_id, 'dokan_store_postcode', $decoded_postcode['zip_code']);
            update_user_meta($store_id, 'dokan_store_komship_address_id', $decoded_postcode['id']);
            update_user_meta($store_id, 'dokan_store_komship_address_label', $decoded_postcode['label']);
        }
    }

    
    return $dokan_settings;
}

add_filter('dokan_vendor_shop_data','populate_postcode_dokan', 99 ,2);
function populate_postcode_dokan($shop_info,$vendor) {
    $postcode = get_user_meta($vendor->id, 'dokan_store_postcode',true);
    $address_id = get_user_meta($vendor->id , 'dokan_store_komship_address_id', true);
    $address_label = get_user_meta($vendor->id , 'dokan_store_komship_address_label', true);
    
    $shop_info['address']['postcode'] =  $postcode;
    $shop_info['address']['address_id'] =  $address_id;
    $shop_info['address']['address_label'] = $address_label;
    
    return $shop_info;
}


add_action('dokan_withdraw_content_after_balance_button', 'add_verify_pin_button_in_dokan_withdraw');

function add_verify_pin_button_in_dokan_withdraw() {
    $user_id = get_current_user_id();
    $stored_pin = get_user_meta($user_id, '_dokan_withdraw_pin', true);

    if (empty($stored_pin)) {
        // Tampilkan pesan jika PIN belum diatur
        ?>
        <div class="dokan-pin-warning" style="background: white; position:absolute; display: inline-block; top:-30px;">
            <p><?php esc_html_e('You need to set a PIN before requesting withdrawals, you can go Settings>PIN Settings.', 'dokan-lite'); ?></p>
            <a href="<?php echo esc_url(dokan_get_navigation_url('settings/pin_settings/')); ?>" class="dokan-btn">
                <?php esc_html_e('Set PIN Now', 'dokan-lite'); ?>
            </a>
        </div>
        <script>
            
            jQuery(document).ready(function($){
                $('button#dokan-request-withdraw-button').remove();
            });
        </script>
        <?php
    } else {
        // Tampilkan tombol Verify PIN dan dialog
        ?>
        <button class="dokan-btn" id="dokan-verify-pin-button"><?php esc_html_e('Verify PIN', 'dokan-lite'); ?></button>
        
        <div id="verify-pin-dialog" style="display: none;">
            <p><?php esc_html_e('Please enter your PIN to request withdraw:', 'dokan-lite'); ?></p>
            <input type="password" id="dokan-pin-input" placeholder="<?php esc_attr_e('Enter PIN', 'dokan-lite'); ?>" />
            <button class="dokan-btn" id="submit-pin"><?php esc_html_e('Submit', 'dokan-lite'); ?></button>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('button#dokan-request-withdraw-button').hide();
                var dokanPinNonce = '<?php echo wp_create_nonce('verify_dokan_pin'); ?>';

                // Show PIN dialog on button click
                $('#dokan-verify-pin-button').on('click', function () {
                    $('#verify-pin-dialog').show();
                });

                // Handle PIN submission
                $('#submit-pin').on('click', function () {
                    const pin = $('#dokan-pin-input').val();

                    if (!pin) {
                        alert('Please enter your PIN.');
                        return;
                    }

                    // Send AJAX request to verify PIN
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'verify_dokan_pin',
                            pin: pin,
                            nonce: dokanPinNonce,
                        },
                        success: function (response) {
                            if (response.success) {
                                alert(response.data.message);
                                $('#verify-pin-dialog').hide();
                                $('#dokan-verify-pin-button').hide(); // Hide Verify PIN button
                                $('#dokan-request-withdraw-button').show(); // Show Request Withdraw button
                            } else {
                                alert(response.data.message);
                            }
                        },
                        error: function () {
                            alert('An error occurred. Please try again.');
                        },
                    });
                });
            });
        </script>
        <style>
            #dokan-request-withdraw-button{
                display:none;
            }
            #dokan-pin-input{
                margin-right:10px;
            }
            /* Inline CSS for Dialog and Warning */
            #verify-pin-dialog {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                padding: 20px;
                border: 1px solid #ddd;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                display: none;
            }

            #verify-pin-dialog p {
                margin-bottom: 10px;
            }

            #verify-pin-dialog input {
                width: 100%;
                margin-bottom: 10px;
                padding: 5px;
                border: 1px solid #ddd;
            }

            #verify-pin-dialog button {
                display: block;
                width: 100%;
                padding: 5px 10px;
                background: darkorange;
                color: #fff;
                border: none;
                cursor: pointer;
            }

            .dokan-pin-warning {
                padding: 15px;
                background-color: #ffebe8;
                border: 1px solid #ff0000;
                color: #ff0000;
                margin-bottom: 15px;
            }

            .dokan-pin-warning a {
                margin-top: 10px;
                display: inline-block;
                background: #0073aa;
                color: #fff;
                padding: 5px 10px;
                text-decoration: none;
            }
        </style>
        <?php
    }
}

// Handle AJAX PIN Verification
add_action('wp_ajax_verify_dokan_pin', 'verify_dokan_pin');
function verify_dokan_pin() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'verify_dokan_pin')) {
        wp_send_json_error(['message' => __('Invalid nonce.', 'dokan-lite')]);
    }

    // Check PIN
    $user_id = get_current_user_id();
    $stored_pin = get_user_meta($user_id, '_dokan_withdraw_pin', true);
    $input_pin = isset($_POST['pin']) ? sanitize_text_field($_POST['pin']) : '';
    
    if (wp_check_password($input_pin, $stored_pin)) {
        wp_send_json_success(['message' => __('PIN verified successfully.', 'dokan-lite')]);
    } else {
        wp_send_json_error(['message' => __('Invalid PIN. Please try again.', 'dokan-lite')]);
    }
}


add_action('dokan_dashboard_left_widgets', 'add_seller_topup_point');

function add_seller_topup_point() { ?>
    <div class="dokan-widget mycred-balance-widget" style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background-color: #fff;">
        <h3 class="widget-title" style="font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #333;">
            <?php esc_html_e('Saldo Points', 'mycred'); ?>
        </h3>
        <div class="widget-content" style="font-size: 28px; color: #555;">
            <?php echo do_shortcode('[mycred_my_balance type="point_seller_internal"]'); ?>
        </div>
        <a class="btn btn-danger" style="color:white; background: darkorange; margin-top:20px;" href="https://shopbiz.id/?s=point+seller&post_type=product&dgwt_wcas=1">Top Up</a>
        <button class="btn btn-info" style="margin-top:20px; color:white;" onclick="openHistoryModal()">View History</button>
    </div>

    <!-- Modal -->
    <div id="historyModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.5); z-index:1000;">
        <div style="background:#fff; margin:10% auto; padding:20px; border-radius:8px; width:80%; max-width:500px; position:relative;">
            <h3><?php esc_html_e('Point History', 'mycred'); ?></h3>
            <div id="historyContent">
                <?php echo do_shortcode('[mycred_history user_id="current" type="point_seller_internal"]'); ?>
            </div>
            <button onclick="closeHistoryModal()" style="position:absolute; top:10px; right:10px; background:transparent; color:black; border:none; border-radius:50%; width:30px; height:30px;">&times;</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                const target = e.target;

                // Cek jika target adalah link pagination di dalam modal
                if (target.closest('#historyModal a') && target.href) {
                    e.preventDefault();

                    // Muat ulang konten menggunakan AJAX
                    const historyContent = document.getElementById('historyContent');
                    fetch(target.href)
                        .then(response => response.text())
                        .then(html => {
                            // Parse konten HTML baru
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            // Ambil elemen pembungkus konten shortcode
                            const newContent = doc.querySelector('#historyContent');
                            if (newContent) {
                                historyContent.innerHTML = newContent.innerHTML;
                            }
                        })
                        .catch(error => console.error('Error fetching paginated content:', error));
                }
            });
        });

        function openHistoryModal() {
            document.getElementById('historyModal').style.display = 'block';
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').style.display = 'none';
        }
    </script>
<?php }

// Add custom styles for MyCred history to wp_head
function custom_mycred_history_styles_wp_head() {
    echo "
    <style>
    /* Wrapper */
    .mycred-history-wrapper {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    /* Table */
    table.mycred-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Table Header */
    table.mycred-table th {
        background-color: darkorange;
        color: #ffffff;
        text-align: left;
        padding: 12px;
        font-size: 14px;
    }

    /* Table Rows */
    table.mycred-table tr {
        border-bottom: 1px solid #eaeaea;
    }
    table.mycred-table tr:nth-child(even) {
        background-color: #f4f4f4;
    }
    table.mycred-table td {
        padding: 10px;
        font-size: 14px;
        color: #333;
    }

    /* Columns */
    table.mycred-table .column-time {
        font-weight: bold;
        font-size: 12px;
        color: #555;
    }
    table.mycred-table .column-username {
        color: #555;
    }
    table.mycred-table .column-creds {
        color: darkorange;
        font-weight: bold;
    }
    table.mycred-table .column-entry {
        font-size: 12px;
        color: #777;
    }

    /* Navigation */
    .mycred-history-wrapper nav {
        margin-top: 20px;
        text-align: center;
    }
    .mycred-history-wrapper nav ul {
        list-style: none;
        padding: 0;
        display: inline-flex;
        gap: 8px;
    }
    .mycred-history-wrapper nav ul li {
        padding: 8px 12px !important;
        border: 1px solid darkorange;
        border-radius: 4px;
        color: darkorange;
        font-size: 14px;
        cursor: pointer;
        background: #ffffff;
        transition: all 0.3s ease;
    }
    .mycred-history-wrapper nav ul li:hover {
        background: darkorange;
        color: #ffffff;
    }
    .mycred-history-wrapper nav ul li.active {
        background: darkorange;
        color: #ffffff;
    }
    </style>
    ";
}
add_action('wp_head', 'custom_mycred_history_styles_wp_head');


// Add custom section to Dokan order details
add_action( 'dokan_komship', 'add_affiliate_payment_confirmation_dokan' );
function add_affiliate_payment_confirmation_dokan( $order ) {
    $order_id = $order->get_id();

    // Check if order status is 'completed'
    $order_status = $order->get_status();
    $is_completed = ($order_status === 'completed');
    
    // Get payment confirmations and point level affiliate data
    $payment_confirmations = get_post_meta( $order_id, '_affiliate_payment_confirmation', true );
    $poin_level_affiliate = get_post_meta($order_id, 'poin_level_affiliate', true);
    
    echo '<div id="affiliate-metabox">';
    $vendor_points = [];
    $i = 0;
    if ( ! empty( $payment_confirmations ) ) {
        echo '<h4>' . __( 'Konfirmasi Pembayaran Affiliate', 'your-textdomain' ) . '</h4>';
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
            
            $estimation_point_level = get_post_meta($product_id, 'point_reward_estimation', true);
    
            echo '<li><strong>' . __( 'Produk:', 'your-textdomain' ) . '</strong> ' . esc_html( $product_name ) . ' x' . esc_html( $order_item_quantity ) . '</li>';
            echo '<li><strong>' . __( 'Produk ID:', 'your-textdomain' ) . '</strong> ' . esc_html( $product_id ) . '</li>';
            echo '<li><strong>' . __( 'Marketplace:', 'your-textdomain' ) . '</strong> ' . esc_html( $confirmation['marketplace_name'] ) . '</li>';
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
        echo '<p>' . __( 'Tidak ada konfirmasi pembayaran affiliate untuk pesanan ini.', 'your-textdomain' ) . '</p>';
    }
    echo '</div>';
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var isCompleted = <?php echo $is_completed ? 'true' : 'false'; ?>;
            
            if (isCompleted) {
                $('#complete-order').attr('disabled', 'disabled');
                $('.poin-level-input').attr('disabled', 'disabled');
            }

            $('#complete-order').click(function() {
                if (isCompleted) {
                    alert('<?php echo __( 'Pesanan sudah selesai. Tidak dapat mengubah poin level.', 'your-textdomain' ); ?>');
                    return;
                }

                var orderId = $(this).data('order-id');
                var allPointsValid = true;
                var points = [];
                var confirmExceeds = false;
                var insufficientBalance = false;

                $('.poin-level-input').each(function(index) {
                    var value = parseInt($(this).val(), 10);
                    var estimation = parseInt($('.point-estimation').eq(index).text(), 10);
                    var vendorBalance = parseInt($(this).data('vendor-balance'), 10);
                    var productName = $(this).data('product-name');
                    
                    if (!value || value <= 0) {
                        allPointsValid = false;
                    }

                    if (value > vendorBalance) {
                        insufficientBalance = true;
                        alert('<?php echo __( 'Saldo vendor tidak mencukupi untuk produk ', 'your-textdomain' ); ?>' + productName);
                    }
                    
                    if (value > estimation) {
                        confirmExceeds = true;
                    }

                    points.push(value);
                });

                if (!allPointsValid) {
                    alert('<?php echo __( 'Semua poin level harus diisi sebelum menyelesaikan pesanan.', 'your-textdomain' ); ?>');
                    return;
                }
                
                if (confirmExceeds) {
                    if (!confirm('<?php echo __( 'Beberapa poin level melebihi estimasi. Apakah Anda yakin ingin melanjutkan?', 'your-textdomain' ); ?>')) {
                        return; // Cancel process
                    }
                }

                if (insufficientBalance) {
                    return; // Batalkan jika ada saldo vendor yang tidak mencukupi
                }

                $.ajax({
                    url: komshipData.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'complete_affiliate_order',
                        order_id: orderId,
                        points: points,
                    },
                    success: function(response) {
                        alert(response.data.message);
                        
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('<?php echo __( 'Terjadi kesalahan. Silakan coba lagi.', 'your-textdomain' ); ?>');
                    }
                });
            });
        });
    </script>
    <?php
}


// // Handle the AJAX request to save affiliate points
// add_action('wp_ajax_save_affiliate_points', 'save_affiliate_points_callback');
// function save_affiliate_points_callback() {
//     if ( isset($_POST['order_id']) && isset($_POST['poin_level_affiliate']) ) {
//         $order_id = intval($_POST['order_id']);
//         $poin_level_affiliate = $_POST['poin_level_affiliate'];

//         update_post_meta($order_id, 'poin_level_affiliate', $poin_level_affiliate);

//         $order = wc_get_order($order_id);
//         if ($order && $order->get_status() !== 'completed') {
//             $order->update_status('completed');
//         }

//         // Respond with success
//         wp_send_json_success(array('message' => __('Affiliate points saved successfully and order marked as completed.', 'your-textdomain')));
//     }

//     wp_send_json_error(array('message' => __('Failed to save affiliate points.', 'your-textdomain')));
// }
function handle_affiliate_product_zero_earning( $earning, $order, $context ) {
    // Loop through each item in the order
    foreach ( $order->get_items() as $item ) {
        // Get product and parent ID
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
        $parent_id = wp_get_post_parent_id( $product_id ) ?: $product_id;

        // Check if the parent product is marked as an affiliate product
        $is_affiliate = get_post_meta( $parent_id, '_is_affiliate_product', true );

        if ( $is_affiliate === 'yes' ) {
            // Set earnings to 0 for both admin and seller contexts
            if ( $context === 'admin' || $context === 'seller' ) {
                return 0;
            }
        }
    }

    // Return the original earning if not affiliate or context is different
    return $earning;
}

add_filter( 'dokan_get_earning_by_order', 'handle_affiliate_product_zero_earning', 10, 3 );

function customize_admin_report_earnings( $data, $group_by, $year, $start, $end, $seller_id ) {
    foreach ( $data as &$row ) {
        $order = wc_get_order( $row->order_id );
        $is_affiliate_product = false;

        if ( $order ) {
            foreach ( $order->get_items() as $item ) {
                // Get product and parent ID
                $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
                $parent_id = wp_get_post_parent_id( $product_id ) ?: $product_id;

                // Check if the parent product is marked as an affiliate product
                $is_affiliate = get_post_meta( $parent_id, '_is_affiliate_product', true );

                if ( $is_affiliate === 'yes' ) {
                    $is_affiliate_product = true;
                    break;
                }
            }
        }

        if ( $is_affiliate_product ) {
            $row->earning = 0;
        }
    }
    return $data;
}

add_filter( 'dokan_get_admin_report_data', 'customize_admin_report_earnings', 100, 6 );

add_filter( 'dokan_get_dashboard_settings_nav', 'dokan_add_api_key_settings_menu',11 );

function dokan_add_api_key_settings_menu( $settings_nav ) {
    $settings_nav['api-key'] = array(
        'title'      => __( 'Partner API Key', 'dokan' ),
        'icon'       => '<i class="fas fa-key"></i>',
        'url'        => dokan_get_navigation_url( 'settings/api-key' ),
        'pos'        => 80, // Adjust position as needed
//         'permission' => 'dokan_view_store_social_menu' // Permissions for vendor
    );

    return $settings_nav;
}


add_action( 'dokan_render_settings_content', 'dokan_render_api_key_settings_content' );

function dokan_render_api_key_settings_content( $query_vars ) {
    if ( isset( $query_vars['settings'] ) && $query_vars['settings'] == 'api-key' ) {
        echo do_shortcode('[shopbiz_customer_api_key_page]');
    }
}

// Modify the heading title for Dokan settings
function dokan_custom_settings_heading_title( $header, $query_vars ) {
    if ( $query_vars === 'api-key' ) {
        $header = __( 'Partner API Key', 'dokan' );
    }

    return $header;
}
add_filter( 'dokan_dashboard_settings_heading_title', 'dokan_custom_settings_heading_title', 10, 2 );

add_filter('dokan_bulk_order_statuses', function($statuses){
    unset($statuses['wc-completed']);
    return $statuses;
},100,1);

// Tambahkan field ke form produk Dokan
add_action('dokan_product_edit_after_pricing', function ($post) {
    $dropship_url = get_post_meta($post->ID, '_dropship_url', true);
    ?>
    <div class="dokan-form-group">
        <label for="dropship_url"><?php esc_html_e('URL Produk Dropship', 'text-domain'); ?></label>
        <input
            type="url"
            class="dokan-form-control"
            name="_dropship_url"
            id="dropship_url"
            value="<?php echo esc_url($dropship_url); ?>"
            placeholder="https://"
        />
    </div>
    <?php
});



// Simpan data dari form produk Dokan
add_action('dokan_process_product_meta', function ($post) {
    if (isset($_POST['_dropship_url'])) {
        update_post_meta($post->ID, '_dropship_url', esc_url_raw($_POST['_dropship_url']));
    }
}, 10, 1);
