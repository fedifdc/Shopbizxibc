<?php
/*
Plugin Name: Disable MyCreed Points for Suborders
Description: A custom plugin to disable MyCreed points for suborders.
Version: 1.0
Author: Ruli Setiwan
*/
function remove_mycred_woo_payout_rewards_action() {
    // Check if the function mycred_woo_payout_rewards exists and if WooCommerce is active
    if ( function_exists( 'mycred_woo_payout_rewards' ) && class_exists( 'WooCommerce' ) ) {
        // Remove the action
        remove_action( 'woocommerce_payment_complete', 'mycred_woo_payout_rewards' );
        remove_action( 'woocommerce_order_status_completed', 'mycred_woo_payout_rewards' );
    }
}
// Hook into 'init' or any hook that runs after 'mycred_load_hooks'
add_action( 'init', 'remove_mycred_woo_payout_rewards_action', 100 );

add_action('woocommerce_order_status_completed', 'update_mycred_on_order_complete');
//add_action('woocommerce_payment_complete', 'update_mycred_on_order_complete');

function update_mycred_on_order_complete( $order_id ) {
	 
    // Get Parent
    $parent_order_id = wp_get_post_parent_id($order_id);
	$poin_level_percentage = 0;
    // Get Order

    $order    = wc_get_order( $order_id );
    global $woocommerce;
    $coupons = $order->get_coupon_codes();
   
    $redemped = $order->get_meta('_ywpar_redemped_points', true);
    
    apply_filters( 'mycred_before_woo_payout_reward', true, $order);
    
  	if(!empty($coupons)){
        if(!is_array($coupons)){
            $coupon_id = wc_get_coupon_id_by_code($coupons);
        }else{
            $coupon_id = wc_get_coupon_id_by_code($coupons[0]);
        }
     
     $poin_level_percentage = get_post_meta($coupon_id, 'poin_level_percentage', true);
    if($poin_level_percentage == 0){
        $poin_level_percentage = 100;
    }
     if($poin_level_percentage == null || $poin_level_percentage == 0){
        // apply_filters( 'mycred_before_woo_payout_reward', false, $order);
        // return;
      } else {
          apply_filters( 'mycred_before_woo_payout_reward', true, $order);
      }
    }
    

    $paid_with = ( version_compare( $woocommerce->version, '3.0', '>=' ) ) ? $order->get_payment_method() : $order->payment_method;
    $buyer_id  = ( version_compare( $woocommerce->version, '3.0', '>=' ) ) ? $order->get_user_id() : $order->user_id;

    // If we paid with myCRED we do not award points by default
    if ( $paid_with == 'mycred' && apply_filters( 'mycred_woo_reward_mycred_payment', false, $order ) === false )
        return;

    // Get items
    $items    = $order->get_items();
    $types    = mycred_get_types();
   

    // Loop through each point type
    foreach ( $types as $point_type => $point_type_label ) {
        if($point_type === 'point_voucher'){
        	continue;
        }
        // Load type
        $mycred = mycred( $point_type );

        // Check for exclusions
        if ( $mycred->exclude_user( $buyer_id ) ) continue;

        // Calculate reward
        $payout = $mycred->zero();
        foreach ( $items as $item ) {

            // Get the product ID or the variation ID
            $product_id    = absint( $item['product_id'] );
            $variation_id  = absint( $item['variation_id'] );
            $reward_amount = mycred_get_woo_product_reward( $product_id, $variation_id, $point_type );
			$is_affiliate = get_post_meta( $product_id, '_is_affiliate_product', true );
            $is_ads = get_post_meta( $product_id, '_is_ads_product', true );

            if($is_ads == 'yes'){
                $vendor_id = get_post_field( 'post_author', $product_id ); 
                $buyer_id = $order->get_customer_id();
                if ( function_exists( 'dokan_get_vendor_by_product' ) ) {
                    $vendor = dokan_get_vendor_by_product( $product_id );
                    $vendor_id = $vendor->get_id();
                  
                } else {
                    $vendor_id = get_post_field( 'post_author', $product_id );
                }
            }
          
         
            // Reward can not be empty or zero
            if ( $reward_amount != '' && $reward_amount != 0 )
                $payout = ( $payout + ( $mycred->number( $reward_amount ) * $item['qty'] ) );
                

        }

        // We can not payout zero points
        if ( $payout === $mycred->zero() ) continue;
		if ( $is_affiliate === 'yes'){ return null; }
        // Let others play with the reference and log entry
        $reference = apply_filters( 'mycred_woo_reward_reference', 'reward', $order_id, $point_type );
        $log       = apply_filters( 'mycred_woo_reward_log',       '%plural% reward for store purchase', $order_id, $point_type );

        // Make sure we only get points once per order
        if ( ! $mycred->has_entry( $reference, $order_id, $buyer_id ) ) {
			$getOrderObject = wc_get_order( $order_id );
			$data = $getOrderObject->get_data();
			if(!empty($coupons) && $poin_level_percentage > 0){
			    if($point_type === 'point_level'){
                    $payout = $payout * $poin_level_percentage / 100;
			    }
            }
            $redemped_numeric = floatval($redemped);
            if($redemped_numeric > 0 && $redemped_numeric < $payout){
                $payout = $payout - $redemped_numeric;
            }
			if($data['parent_id'] == 0){
                if($is_ads == 'yes'){
                   
                    $vendor_point = mycred_get_users_balance( $vendor_id, 'point_seller_internal' );
                   
                    if($vendor_point >= $payout){
                        mycred_add(
                            $reference,
                            $buyer_id,
                            $payout,
                            $log,
                            $order_id,
                            array( 'ref_type' => 'post' ),
                            $point_type
                        );
                        mycred_add(
                            $reference,
                            $vendor_id,
                            -$payout,
                            $log,
                            $order_id,
                            array( 'ref_type' => 'post' ),
                            'point_seller_internal'
                        );
                        //add_mycred_points( $reference, $buyer_id, $payout, $log, $order_id, $ref_type, $point_type );
                    }
                }else{
                    mycred_add(
                        $reference,
                        $buyer_id,
                        $payout,
                        $log,
                        $order_id,
                        array( 'ref_type' => 'post' ),
                        $point_type
                    );
                }
                
            // Execute
        	}
		}
    }

}

function subtract_mycred_points_seller( $order_id, $amount, $vendor_id, ) {
    $order = wc_get_order( $order_id );
    $items = $order->get_items();

    $vendor_ids = array();
    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        $vendor_id = get_post_field( 'post_author', $product_id );
        if ( ! in_array( $vendor_id, $vendor_ids ) ) {
            $vendor_ids[] = $vendor_id;
        }
    }
    $vendor_point = mycred_get_users_balance( $product_author_id, 'point_seller_internal' );
    $buyer_id = get_post_meta( $order_id, '_customer_user', true );
    $point_type = 'point_seller';
    $mycred = mycred( $point_type );

    // Check if user is excluded
    if ( $mycred->exclude_user( $buyer_id ) ) return;

    // Subtract points
    $reference = 'subtraction';
    $log = 'Points subtracted for order #' . $order_id;

    if ( ! $mycred->has_entry( $reference, $order_id, $buyer_id ) ) {
        mycred_subtract(
            $reference,
            $buyer_id,
            $amount,
            $log,
            $order_id,
            array( 'ref_type' => 'post' ),
            $point_type
        );
    }
}

function add_mycred_points( $reference, $buyer_id, $payout, $log, $order_id, $ref_type, $point_type ) {
    mycred_add(
        $reference,
        $buyer_id,
        $payout,
        $log,
        $order_id,
        $ref_type,
        'point_level'
    );
}