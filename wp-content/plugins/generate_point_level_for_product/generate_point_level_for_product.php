<?php
/**
 * Plugin Name: MyCred Generate value for point level
 * Description: Menambahkan generate value point level
 * Version: 1.0
 * Author: Ruli setiawan
 */

// Add Submenu Page
add_action( 'save_post_product', 'generate_point_level_for_product', PHP_INT_MAX);
add_action( 'woocommerce_save_product_variation','generate_point_level_mycred_woo_save_product_variation_detail' );

function getBV(){
    $bv = get_option('shopbiz_point_level_percentage', true);
    if($bv == ''){
        $bv = 0;
    } else {
        if($bv < 50){
            $bv = $bv + 50;
        } else if($bv >= 50){
            $bv = 100;
        }
    }
    return $bv;
}
function generate_point_level_for_product($post_id) {

    if (sizeof($_POST) == 0 || get_post_type($post_id) != 'product') {
        return;
    }
   
    $isPointShareActive = get_post_meta($post_id, 'ps_active', true);
  
    $price = !empty($_POST['_regular_price']) ? wc_format_decimal($_POST['_regular_price']) : 0;
    $comission_produk = !empty($_POST['PS']) ? wc_format_decimal($_POST['PS']) : 0;
    $bvPercent = getBV();
    $bvType = get_post_meta($post_id, 'BV_type', true);
    $commissionAffiliateValue = get_post_meta($post_id, 'BV', true);
    if ($isPointShareActive == 1) {
        if ($bvType == 'percent'){
            $comission_produk = $comission_produk - $commissionAffiliateValue;
            $point_level = ($price * ($comission_produk / 100));
            $point_updgrade = ($price * ($comission_produk / 100)) * ($bvPercent / 100);
        } else {
            $comission_produk = ($price * ($comission_produk / 100)) - $commissionAffiliateValue;
            $point_level= $comission_produk;
            $point_updgrade = $comission_produk  * ($bvPercent / 100);
        }
       
    } else {
       $redeem_point_manual = $_POST['redeem_points_field'];
       update_post_meta($post_id, '_redeem_points', mycred('point_level')->number($redeem_point_manual));
       return;
    }

    $new_point = mycred('point_level')->number($point_level);
    $new_point_upgrade = mycred('point_upgrade')->number($point_updgrade);

    // Retrieve existing reward
    $existing_rewards = get_post_meta($post_id, 'mycred_reward', true);
    $existing_rewards = is_array($existing_rewards) ? $existing_rewards : [];

    // Merge with the new point type reward
    $existing_rewards['point_level'] = $new_point;
    $existing_rewards['point_upgrade'] = $new_point_upgrade;

    // print_r( $existing_rewards);
    // die(0);

    // Update the meta
    mycred_update_post_meta($post_id, 'mycred_reward', $existing_rewards);
    update_post_meta($post_id, '_redeem_points', $new_point);


    // Handle variations if applicable
    //$product = wc_get_product($post_id);
    $product = get_product( $post_id );
    if ( $product->is_type( 'variable' ) ) {
		$have_variation =  1;
	} else {
		$have_variation = 0;
	}
	if($have_variation == 1){
        $variations = wc_get_product( $post_id )->get_available_variations();
        foreach($variations as $variation){

            $new_var_setup = array(); 
            $variation_id =  $variation['variation_id'];
            $mycred = mycred( 'point_level' );
            $variation_price = $variation['display_price'] != null ? $variation['display_price'] : 0;
            $isPointShareActive = get_post_meta($post_id, 'ps_active', true);
            $point_type = 'point_level';
           
            if ($isPointShareActive == 1) {
                $variation_point_level = ($variation_price * ($comission_produk / 100));
                $variation_point_updgrade = ($variation_price * ($comission_produk / 100)) * ($bvPercent / 100);
            } else {
                $variation_point_level = ($variation_price * ($comission_produk / 100)) * (90 / 100);
                $variation_point_updgrade = ($variation_price * ($comission_produk / 100)) * (90 / 100);
            }
            $variation_new_point = mycred('point_level')->number($variation_point_level);
            $variation_new_point = mycred('point_upgrade')->number($variation_point_level);
    
            $existing_variation_rewards = get_post_meta($variation_id, '_mycred_reward', true);
            $existing_variation_rewards = is_array($existing_variation_rewards) ? $existing_variation_rewards : [];

            $existing_variation_rewards['point_level'] = $variation_new_point;
            $existing_variation_rewards['point_upgrade'] = $variation_point_updgrade;

            mycred_update_post_meta($variation_id, '_mycred_reward', $existing_variation_rewards);
            update_post_meta($variation_id, '_variation_redeem_points', $variation_new_point);
        }
    }
    
}

function generate_point_level_mycred_woo_save_product_variation_detail($post_id) {
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $new_setup = array();
    $bvPercent = getBV();
    if (isset($_POST['variation_redeem_points'][$post_id])) {
        $variation_redeem_points_manual = wc_format_decimal($_POST['variation_redeem_points'][$post_id]);
    } else {
        $variation_redeem_points_manual = 0;
    }
    if (!isset($_POST['_mycred_reward']) || !isset($_POST['_mycred_reward'][$post_id])) {
        return;
    }

    $price = !empty($_POST['variable_regular_price'][key($_POST['variable_regular_price'])]) 
        ? wc_format_decimal($_POST['variable_regular_price'][key($_POST['variable_regular_price'])]) 
        : 0;

    $comission_produk = get_post_meta($_POST['product_id'], 'PS', true);
    $comission_produk = $comission_produk ?: 10;

    $isPointShareActive = get_post_meta($_POST['product_id'], 'ps_active', true);

    if ($isPointShareActive == 1) {
       // $point_level = ($price * ($comission_produk / 100));
        $point_level = ($price * ($comission_produk / 100)) * ($bvPercent / 100);
        $point_updgrade = ($price * ($comission_produk / 100)) * ($bvPercent / 100);
    } else {
        update_post_meta($post_id, '_variation_redeem_points', mycred('point_level')->number($variation_redeem_points_manual));
        return;
    }
    
    $new_point = mycred('point_level')->number($point_level);
    $new_point_upgrade = mycred('point_upgrade')->number($point_level);
    $existing_rewards = get_post_meta($post_id, '_mycred_reward', true);
    $existing_rewards = is_array($existing_rewards) ? $existing_rewards : [];
    $existing_rewards = array();
    $existing_rewards['point_level'] = $new_point;
    $existing_rewards['point_upgrade'] = $new_point_upgrade;
    
    mycred_update_post_meta($post_id, '_mycred_reward', $existing_rewards);
    update_post_meta($post_id, '_variation_redeem_points', $new_point);
}

// Hook untuk menambahkan meta box ke produk di WooCommerce
add_action( 'add_meta_boxes_product', 'add_redeem_points_meta_box' );

function add_redeem_points_meta_box() {
	$product = wc_get_product( get_the_ID() );
	if( $product->is_type( 'variable' ) != 'variable' ) {
		add_meta_box(
			'redeem_points_meta_box',     // ID unik meta box
			'Redeem Points',              // Judul meta box
			'display_redeem_points_meta_box',  // Fungsi callback untuk menampilkan isi
			'product',                    // Meta box ini hanya muncul di produk (post type: 'product')
			'side',                       // Tampilkan di bagian "side"
			'high'                        // Prioritas tinggi
		);
	}
}

// Fungsi callback untuk menampilkan meta box di admin
function display_redeem_points_meta_box( $post ) {
    // Ambil nilai meta jika sudah ada
    $redeem_points = get_post_meta( $post->ID, '_redeem_points', true );

    // Tampilkan input field untuk memasukkan jumlah poin
    echo '<label for="redeem_points_field">Redeem Points:</label>';
    echo '<input type="number" id="redeem_points_field" name="redeem_points_field" value="' . esc_attr( $redeem_points ) . '" style="width:100%;" />';
}
add_action( 'save_post_product', 'save_redeem_points_meta_box_data' );

function save_redeem_points_meta_box_data( $post_id ) {
    // Periksa apakah meta box sedang disimpan pada post tipe 'product'
//     if ( isset( $_POST['redeem_points_field'] ) && 'product' === get_post_type( $post_id ) ) {
//         $redeem_points = sanitize_text_field( $_POST['redeem_points_field'] );
//         update_post_meta( $post_id, '_redeem_points', $redeem_points );
//     }
}

// Tambahkan custom field untuk tiap variasi produk variabel
add_action( 'woocommerce_product_after_variable_attributes', 'add_redeem_points_to_variations', 10, 3 );

function add_redeem_points_to_variations( $loop, $variation_data, $variation ) {
    // Ambil nilai redeem points untuk variasi
    $variation_redeem_points = get_post_meta( $variation->ID, '_variation_redeem_points', true );
    
    // Tampilkan input field untuk memasukkan redeem points per variasi
    echo '<tr>';
    echo '<td>';
    echo '<label for="variation_redeem_points_' . $variation->ID . '">Redeem Points:</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="number" id="variation_redeem_points_' . $variation->ID . '" name="variation_redeem_points[' . $variation->ID . ']" value="' . esc_attr( $variation_redeem_points ) . '" style="width:100%;" />';
    echo '</td>';
    echo '</tr>';
}

// Hook untuk menambahkan meta box ke produk di WooCommerce
add_action( 'add_meta_boxes_product', 'add_profitshare_meta_box' );

function add_profitshare_meta_box() {
	$product = wc_get_product( get_the_ID() );
		add_meta_box(
			'add_profit_share_field',     // ID unik meta box
			'Profit Share',              // Judul meta box
			'display_profit_share_meta_box',  // Fungsi callback untuk menampilkan isi
			'product',                    // Meta box ini hanya muncul di produk (post type: 'product')
			'normal',                       // Tampilkan di bagian "side"
			'default'                        // Prioritas tinggi
		);
}



function add_profit_share_field($post) {
    // Retrieve current values
    $PS = get_post_meta($post->ID, 'PS', true);
    $active = get_post_meta($post->ID, 'active', true);

    // Output fields
    echo '<label for="PS" class="form-label">Profit Share (%)</label>';
    echo '<input type="number" id="PS" name="PS" value="' . esc_attr($PS) . '" style="margin-bottom: 10px; display: block;">';

    // Checkbox for Active
    $checked = $active === '1' ? 'checked' : '';
    echo '<label for="active" class="form-label">Active</label>';
    echo '<input type="checkbox" id="active" name="active" value="1" ' . $checked . '> Active';

    echo '<input type="hidden" name="shopbiz_product_meta_box_nonce" value="' . wp_create_nonce('shopbiz_save_product_meta_box') . '">';
}

// Save custom fields data
function save_custom_profit_share_field($post_id) {
    // Check nonce
    if (!isset($_POST['shopbiz_product_meta_box_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['shopbiz_product_meta_box_nonce'], 'shopbiz_save_product_meta_box')) {
        return;
    }

    // Save Profit Share field
    if (isset($_POST['PS'])) {
        update_post_meta($post_id, 'PS', sanitize_text_field($_POST['PS']));
        
    }

    // Save Active field
    if (isset($_POST['active'])) {
        update_post_meta($post_id, 'ps_active', 1);
        $_POST['_per_product_admin_commission'] = sanitize_text_field($_POST['PS']);
        $_POST['_per_product_admin_commission_type'] = 'percentage';
    } else {
        update_post_meta($post_id, 'ps_active', 0);
    }
}


add_action('save_post', 'save_custom_profit_share_field');

  // Check if autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }



  // Save meta box data
  if (isset($_POST['PS'])) {
    update_post_meta($post_id, 'PS', sanitize_text_field($_POST['PS']));

  }

add_action( 'save_post_product', 'save_custom_profit_share_field', 10, 2 );
// Fungsi callback untuk menampilkan meta box di admin
function display_profit_share_meta_box( $post ) {
    // Ambil nilai meta jika sudah ada
    $point_share = get_post_meta( $post->ID, 'PS', true );
    $active = get_post_meta($post->ID, 'ps_active', true);

    // Tampilkan input field untuk memasukkan jumlah poin
    $checked = $active == 1 ? 'checked' : '';
    echo '<input type="checkbox" id="active" name="active" value="1" ' . $checked . '> Active</p>';
    echo '<p><small>When activated, all points will follow the value of profit share * price.</small></p>';
    echo '<p><label for="PS" class="form-label">Profit Share (%)</label>';
    echo '<input type="number" id="PS" name="PS" value="' . esc_attr( $point_share ) . '" style="width:100%;" /></p>';

    echo '<input type="hidden" name="shopbiz_product_meta_box_nonce" value="' . wp_create_nonce('shopbiz_save_product_meta_box') . '">';
}