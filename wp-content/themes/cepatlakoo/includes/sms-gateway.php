<?php
/**
 * Template for proccessing SMS Notification
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.2
 */
include( get_template_directory() . '/includes/smsGateway/smsGateway.php' ); // Load Library

/**
 * Functions to send SMS if order status change for customer
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.2
 */
if ( ! function_exists( 'cepatlakoo_customer_order_notification' ) ) {
    function cepatlakoo_customer_order_notification($order) {
        error_log("==================== START SEND WA CEPATLAKOO " . date('Y-m-d H:i:s') . " ======================");
        global $woocommerce, $cl_options;

        $cepatlakoo_sms_gateway_token = !empty( $cl_options['cepatlakoo_sms_gateway_token'] ) ? $cl_options['cepatlakoo_sms_gateway_token'] : '';
        
        if( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wa_token'] ) ? $cl_options['cepatlakoo_wa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowa_key'] ) ? $cl_options['cepatlakoo_woowa_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wanotif_key'] ) ? $cl_options['cepatlakoo_wanotif_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wablas_token'] ) ? $cl_options['cepatlakoo_wablas_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowandroid_cs_id'] ) ? $cl_options['cepatlakoo_woowandroid_cs_id'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_ruangwa_token'] ) ? $cl_options['cepatlakoo_ruangwa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_waresponder_device'] ) ? $cl_options['cepatlakoo_waresponder_device'] : '';
        }
        else{
            $cl_wa_token            = '';
        }

        $cepatlakoo_sms_gateway_deviceID = !empty( $cl_options['cepatlakoo_sms_gateway_deviceID'] ) ? $cl_options['cepatlakoo_sms_gateway_deviceID'] : '';
        $cepatlakoo_sms_gateway_phone = !empty( $cl_options['cepatlakoo_sms_gateway_phone'] ) ? $cl_options['cepatlakoo_sms_gateway_phone'] : '';

        $cepatlakoo_sms_new_order = !empty( $cl_options['cepatlakoo_sms_new_order'] ) ? $cl_options['cepatlakoo_sms_new_order'] : '';
        $cepatlakoo_sms_order_process = !empty( $cl_options['cepatlakoo_sms_order_process'] ) ? $cl_options['cepatlakoo_sms_order_process'] : '';
        $cepatlakoo_sms_order_complete = !empty( $cl_options['cepatlakoo_sms_order_complete'] ) ? $cl_options['cepatlakoo_sms_order_complete'] : '';
        $cepatlakoo_sms_order_pending = !empty( $cl_options['cepatlakoo_sms_order_pending'] ) ? $cl_options['cepatlakoo_sms_order_pending'] : '';
        $cepatlakoo_sms_order_failed = !empty( $cl_options['cepatlakoo_sms_order_failed'] ) ? $cl_options['cepatlakoo_sms_order_failed'] : '';
        $cepatlakoo_sms_order_refunded = !empty( $cl_options['cepatlakoo_sms_order_refunded'] ) ? $cl_options['cepatlakoo_sms_order_refunded'] : '';
        $cepatlakoo_sms_order_cancel = !empty( $cl_options['cepatlakoo_sms_order_cancel'] ) ? $cl_options['cepatlakoo_sms_order_cancel'] : '';

        // Make Connection to SmsGateway
        $smsGateway = new SmsGateway($cepatlakoo_sms_gateway_token);
        $cl_device_id = $cepatlakoo_sms_gateway_deviceID;
        //get order & buyer datas
        $cl_order = new WC_Order( $order );
        $cl_custom_fee = $cl_order->get_fees();

        if ( $cl_custom_fee ) {
            foreach($cl_custom_fee as $cl_fee){}
        } else {
            $cl_fee = null;
        }
        
        $cl_order_id = trim(str_replace('#', '', $cl_order->get_order_number()));
        $cl_base_order_status = $cl_order->get_status();
        $cl_status_list = array(
                            'pending' => __('Pending', 'cepatlakoo'),
                            'processing' => __('Processing', 'cepatlakoo'),
                            'on-hold' => __('On Hold', 'cepatlakoo'),
                            'completed' => __('Completed', 'cepatlakoo'),
                            'cancelled' => __('Cancelled', 'cepatlakoo'),
                            'refunded' => __('Refunded', 'cepatlakoo'),
                            'failed' => __('Failed', 'cepatlakoo'),
                        );
        foreach ($cl_status_list as $cl_status_lists => $translate){
            if ($cl_base_order_status == $cl_status_lists){
                $cl_order_status = $translate;
            }
        }
        $cl_order->add_order_note(__('trigger change status '.$cl_order_status, 'cepatlakoo'));

        $cl_shop_name = get_bloginfo('name');
        $cl_fullname = get_post_meta( $cl_order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $cl_order_id, '_billing_last_name', true );
        $cl_email = get_post_meta( $cl_order_id, '_billing_email', true );
        $cl_phone_number = get_post_meta( $cl_order_id, '_billing_phone', true );
        $cl_shipping_price = strip_tags( wc_price( get_post_meta( $cl_order_id, '_order_shipping', true ) ) );
        $cl_total_price = strip_tags( wc_price( $cl_order->get_total() ) );
        $cl_subtotal_price = strip_tags( wc_price( $cl_order->get_subtotal() ) );
        $cl_tracking_code = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) : '-';
        $cl_tracking_date = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true ) ) ? date( get_option('date_format'), strtotime(get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true )) ) : '-';
        $cl_courier = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) : '-';
        ( $cl_fee ) ? $cl_payment_code = $cl_fee->get_total() : $cl_payment_code = null;
        //formating phone number (format : +628....)
        if( strpos($cl_phone_number, '-') !== false ){
            $cl_phone_number = str_replace('-', '', $cl_phone_number);
        }
        if ( preg_match('[^62]', $cl_phone_number ) ) {
            $wa_phone = str_replace('62', '+62', $cl_phone_number);
        } else if ( $cl_phone_number[0] == '0' ) {
            $cl_phone_number = ltrim( $cl_phone_number, '0' );
            $wa_phone = '+62'. $cl_phone_number;
        } else if ( $cl_phone_number[0] == '8' ) {
            $wa_phone = '+62'. $cl_phone_number;
        } else {
            $wa_phone = $cl_phone_number;
        }
        //replace shortcode option to real data each status
        if ($cl_base_order_status == 'processing'){
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_tracking_date%', $cl_tracking_date, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_process);
                $cepatlakoo_sms_order_process = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_process, $cl_device_id );
            }
            
            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_process');
            }

        } else if ($cl_base_order_status == 'pending') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_pending);
                $cepatlakoo_sms_order_pending = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_pending, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_pending');
            }

        } else if ($cl_base_order_status == 'on-hold') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_new_order);
                $cepatlakoo_sms_new_order = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_new_order, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_new_order');
            }

        } else if ($cl_base_order_status == 'completed') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_tracking_date%', $cl_tracking_date, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_complete);
                $cepatlakoo_sms_order_complete = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_complete, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_complete');
            }

        } else if ($cl_base_order_status == 'cancelled') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_cancel);
                $cepatlakoo_sms_order_cancel = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_cancel, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_cancel');
            }

        } else if ($cl_base_order_status == 'refunded') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_refunded);
                $cepatlakoo_sms_order_refunded = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_refunded, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_refunded');
            }

        } else if ($cl_base_order_status == 'failed') {
            if( !empty($cepatlakoo_sms_gateway_token) ){
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_failed);
                $cepatlakoo_sms_order_failed = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_failed, $cl_device_id );
            }

            if ( !empty($cl_wa_token) ) {
                cepatlakoo_wassenger_generate_msg($cl_order_id, 'cepatlakoo_wa_order_failed');
            }

        }
            error_log("==================== END SEND WA CEPATLAKOO " . date('Y-m-d H:i:s') . " ======================");

    }
}
// add_action('woocommerce_order_status_pending', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_failed', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_on-hold', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_completed', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_processing', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_refunded', 'cepatlakoo_customer_order_notification', 10);
add_action('woocommerce_order_status_cancelled', 'cepatlakoo_customer_order_notification', 10);
add_action( 'wp_ajax_cl_send_order_notification', 'cepatlakoo_ajax_notification' );
add_action( 'wp_ajax_nopriv_cl_send_order_notification', 'cepatlakoo_ajax_notification' );

/**
 * Functions to handle ajax to send SMS & WhatsApp message
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if ( ! function_exists( 'cepatlakoo_ajax_notification' ) ) {
    function cepatlakoo_ajax_notification(){
        $order = $_POST['order_id'];
        $status = !empty( get_post_meta($order, 'cepatlakoo_ajax_notification', true ) ) ? false : true;
        if( !empty($order) && $status){
            cepatlakoo_customer_order_notification($order);
            cepatlakoo_new_order_admin($order);
            add_post_meta($order, 'cepatlakoo_ajax_notification', date('Y-m-d H:i:s'));
        }
    }
}
/**
 * Functions to send SMS & WhatsApp message if order status change for admin
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.2
 */
if ( ! function_exists( 'cepatlakoo_new_order_admin' ) ) {
    function cepatlakoo_new_order_admin( $order ) {
        global $woocommerce, $cl_options;

        $cepatlakoo_sms_gateway_token = !empty( $cl_options['cepatlakoo_sms_gateway_token'] ) ? $cl_options['cepatlakoo_sms_gateway_token'] : '';
        
        if( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wa_token'] ) ? $cl_options['cepatlakoo_wa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowa_key'] ) ? $cl_options['cepatlakoo_woowa_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wanotif_key'] ) ? $cl_options['cepatlakoo_wanotif_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wablas_token'] ) ? $cl_options['cepatlakoo_wablas_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowandroid_cs_id'] ) ? $cl_options['cepatlakoo_woowandroid_cs_id'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_ruangwa_token'] ) ? $cl_options['cepatlakoo_ruangwa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_waresponder_device'] ) ? $cl_options['cepatlakoo_waresponder_device'] : '';
        }
        else{
            $cl_wa_token            = '';
        }
        
        $cepatlakoo_sms_gateway_deviceID = !empty( $cl_options['cepatlakoo_sms_gateway_deviceID'] ) ? $cl_options['cepatlakoo_sms_gateway_deviceID'] : '';
        $cepatlakoo_sms_gateway_phone = !empty( $cl_options['cepatlakoo_sms_gateway_phone'] ) ? $cl_options['cepatlakoo_sms_gateway_phone'] : '';

        $cepatlakoo_sms_new_order_admin = !empty( $cl_options['cepatlakoo_sms_new_order_admin'] ) ? $cl_options['cepatlakoo_sms_new_order_admin'] : '';
        $cl_wa_new_order_admin = !empty( $cl_options['cepatlakoo_wa_new_order_admin'] ) ? $cl_options['cepatlakoo_wa_new_order_admin'] : '';
        
        $cl_wa_admin_phone = !empty( $cl_options['cepatlakoo_wa_admin_phone'] ) ? $cl_options['cepatlakoo_wa_admin_phone'] : '';
        if ( preg_match('[^62]', $cl_wa_admin_phone ) ) {
            $wa_phone = str_replace('62', '+62', $cl_wa_admin_phone);
        }else if ( $cl_wa_admin_phone[0] == '0' ) {
            $cl_wa_admin_phone = ltrim( $cl_wa_admin_phone, '0' );
            $wa_phone = '+62'. $cl_wa_admin_phone;
        }else if ( $cl_wa_admin_phone[0] == '8' ) {
            $wa_phone = '+62'. $cl_wa_admin_phone;
        } else {
            $wa_phone = $cl_wa_admin_phone;
        }

        $smsGateway = new SmsGateway($cepatlakoo_sms_gateway_token);
        $cl_device_id = $cepatlakoo_sms_gateway_deviceID;
        $cl_admin_number = $cepatlakoo_sms_gateway_phone;
        //get order & buyer datas
        $cl_order = new WC_Order( $order );
        $cl_order_id = trim(str_replace('#', '', $cl_order->get_order_number()));
        $cl_order_status = $cl_order->get_status();
        $cl_status_list = array(
                            'pending' => __('Pending', 'cepatlakoo'),
                            'processing' => __('Processing', 'cepatlakoo'),
                            'on-hold' => __('On Hold', 'cepatlakoo'),
                            'completed' => __('Completed', 'cepatlakoo'),
                            'cancelled' => __('Cancelled', 'cepatlakoo'),
                            'refunded' => __('Refunded', 'cepatlakoo'),
                            'failed' => __('Failed', 'cepatlakoo'),
                        );
        foreach ($cl_status_list as $cl_status_lists => $translate){
            if ($cl_order_status == $cl_status_lists){
                $cl_order_status = $translate;
            }
        }

        $cl_shop_name = get_bloginfo('name');

        $items = $cl_order->get_items();
        $cl_shipping_price = strip_tags( wc_price( get_post_meta( $cl_order_id, '_order_shipping', true ) ) );
        $cl_total_price = strip_tags( wc_price( $cl_order->get_total() ) );
        $cl_subtotal_price = strip_tags( wc_price( $cl_order->get_subtotal() ) );
        $data_product = array();
    
        foreach ( $items as $item ) {
            $meta_data = $item->get_formatted_meta_data( '' );
            $var = array();
            foreach ($meta_data as $meta){
                if( $meta->display_key != '_reduced_stock' )
                    $var[] = $meta->display_key.': '.$meta->value;
            }
            $var = ' '.implode(', ', $var);

            array_push( $data_product, $item['name']  . $var .' ('. $item['quantity'] .'): '. strip_tags( wc_price($item->get_total())) );
        }
    
        $data_products = implode(",\n--\n",$data_product);
        //replace shortcode option to real data
        // SMS
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_new_order_admin);
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_new_order_admin);
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_new_order_admin);
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_new_order_admin);
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_new_order_admin);
        $cepatlakoo_sms_new_order_admin = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_new_order_admin);

        // WhatsApp
        $cl_wa_new_order_admin = str_replace( '%lakoo_order_id%', $cl_order_id, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_order_status%', $cl_order_status, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_products%', $data_products, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_total_price%', $cl_total_price, $cl_wa_new_order_admin);
        $cl_wa_new_order_admin = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cl_wa_new_order_admin);

        if( !empty($cepatlakoo_sms_gateway_token) )
            $sent_to_admin = $smsGateway->sendMessageToNumber($cl_admin_number, $cepatlakoo_sms_new_order_admin, $cl_device_id );

        if ( !empty($cl_wa_token) ) {
            // cepatlakoo_wassenger_send( $cl_wa_admin_phone, $cl_wa_new_order_admin );
            if( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
                cepatlakoo_woowa_send( $wa_phone, $cl_wa_new_order_admin );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
                cepatlakoo_wassenger_send( $wa_phone, $cl_wa_new_order_admin );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
                cepatlakoo_wanotif_send( $wa_phone, $cl_wa_new_order_admin );
            }            
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
                cepatlakoo_wablas_send( $wa_phone, $cl_wa_new_order_admin );
            }            
            else if ( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
                cepatlakoo_woowandroid_send( $wa_phone, $cl_wa_new_order_admin, 'After Checkout', $cl_order_id, $cl_order_status  );
            }            
            else if ( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
                cepatlakoo_ruangwa_send( $wa_phone, $cl_wa_new_order_admin );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
                cepatlakoo_waresponder_send( $wa_phone, $cl_wa_new_order_admin );
            }
        }
    }
}
// add_action('woocommerce_checkout_order_processed', 'cepatlakoo_new_order_admin', 10);
// add_action( 'woocommerce_new_order', 'cepatlakoo_new_order_admin',  1, 1  );
// add_action('woocommerce_order_status_pending_to_processing_notification', 'cepatlakoo_new_order_admin', 10);
// add_action('woocommerce_order_status_pending_to_on-hold_notification', 'cepatlakoo_new_order_admin', 10);
// add_action('woocommerce_order_status_pending_to_completed_notification', 'cepatlakoo_new_order_admin', 10);

/**
 * Functions to send SMS if order note add for customer
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.2
 */
if ( ! function_exists( 'cepatlakoo_send_customer_note' ) ) {
    function cepatlakoo_send_customer_note($data) {
        global $woocommerce, $cl_options;

        $cepatlakoo_sms_gateway_token = !empty( $cl_options['cepatlakoo_sms_gateway_token'] ) ? $cl_options['cepatlakoo_sms_gateway_token'] : '';
        
        if( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wa_token'] ) ? $cl_options['cepatlakoo_wa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowa_key'] ) ? $cl_options['cepatlakoo_woowa_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wanotif_key'] ) ? $cl_options['cepatlakoo_wanotif_key'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_wablas_token'] ) ? $cl_options['cepatlakoo_wablas_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_woowandroid_cs_id'] ) ? $cl_options['cepatlakoo_woowandroid_cs_id'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_ruangwa_token'] ) ? $cl_options['cepatlakoo_ruangwa_token'] : '';
        }
        elseif( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
            $cl_wa_token            = !empty( $cl_options['cepatlakoo_waresponder_device'] ) ? $cl_options['cepatlakoo_waresponder_device'] : '';
        }
        else{
            $cl_wa_token            = '';
        }

        $cepatlakoo_sms_gateway_deviceID = !empty( $cl_options['cepatlakoo_sms_gateway_deviceID'] ) ? $cl_options['cepatlakoo_sms_gateway_deviceID'] : '';
        $cepatlakoo_sms_gateway_phone = !empty( $cl_options['cepatlakoo_sms_gateway_phone'] ) ? $cl_options['cepatlakoo_sms_gateway_phone'] : '';
        $cepatlakoo_sms_order_note = !empty( $cl_options['cepatlakoo_sms_order_note'] ) ? $cl_options['cepatlakoo_sms_order_note'] : '';

        $cl_wa_order_note = !empty( $cl_options['cepatlakoo_wa_order_note'] ) ? $cl_options['cepatlakoo_wa_order_note'] : '';

        $smsGateway = new SmsGateway($cepatlakoo_sms_gateway_token);
        $cl_device_id = $cepatlakoo_sms_gateway_deviceID;

        $cl_order = new WC_Order( $data['order_id'] );
        $cl_custom_fee = $cl_order->get_fees();
        if ( $cl_custom_fee ) {
            foreach($cl_custom_fee as $cl_fee){}
        }else{
            $cl_fee = null;
        }
        //get order & buyer datas
        $cl_order_id = trim(str_replace('#', '', $cl_order->get_order_number()));
        $cl_base_order_status = $cl_order->get_status();
        $cl_status_list = array(
                            'pending' => __('Pending', 'cepatlakoo'),
                            'processing' => __('Processing', 'cepatlakoo'),
                            'on-hold' => __('On Hold', 'cepatlakoo'),
                            'completed' => __('Completed', 'cepatlakoo'),
                            'cancelled' => __('Cancelled', 'cepatlakoo'),
                            'refunded' => __('Refunded', 'cepatlakoo'),
                            'failed' => __('Failed', 'cepatlakoo'),
                        );
        foreach ($cl_status_list as $cl_status_lists => $translate){
            if ($cl_base_order_status == $cl_status_lists){
                $cl_order_status = $translate;
            }
        }
        $cl_shop_name = get_bloginfo('name');
        $cl_fullname = get_post_meta( $cl_order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $cl_order_id, '_billing_last_name', true );
        $cl_email = get_post_meta( $cl_order_id, '_billing_email', true );
        $cl_phone_number = get_post_meta( $cl_order_id, '_billing_phone', true );
        $cl_shipping_price = strip_tags( wc_price( get_post_meta( $cl_order_id, '_order_shipping', true ) ) );
        $cl_total_price = strip_tags( wc_price( $cl_order->get_total() ) );
        $cl_subtotal_price = strip_tags( wc_price( $cl_order->get_subtotal() ) );
        $cl_tracking_code = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) : '-';
        $cl_tracking_date = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true ) ) ? date( get_option('date_format'), strtotime(get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true )) ) : '-';
        $cl_courier = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) : '-';
        ( $cl_fee ) ? $cl_payment_code = $cl_fee->get_total() : $cl_payment_code = null;
        //formating phone number (format : +628....)
        if( strpos($cl_phone_number, '-') !== false ){
            $cl_phone_number = str_replace('-', '', $cl_phone_number);
        }
        if ( preg_match('[^62]', $cl_phone_number ) ) {
            $wa_phone = str_replace('62', '+62', $cl_phone_number);
        }else if ( $cl_phone_number[0] == '0' ) {
            $cl_phone_number = ltrim( $cl_phone_number, '0' );
            $wa_phone = '+62'. $cl_phone_number;
        }else if ( $cl_phone_number[0] == '8' ) {
            $wa_phone = '+62'. $cl_phone_number;
        } else {
            $wa_phone = $cl_phone_number;
        }
        //replace shortcode option to real data
        if( !empty($cepatlakoo_sms_gateway_token) ){
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_order_id%', $cl_order_id, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_order_status%', $cl_order_status, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_fullname%', $cl_fullname, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_email%', $cl_email, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_total_price%', $cl_total_price, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_tracking_date%', $cl_tracking_date, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_courier%', $cl_courier, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_sms_order_note);
            $cepatlakoo_sms_order_note = str_replace( '%lakoo_note%', wptexturize($data['customer_note']) , $cepatlakoo_sms_order_note);
            
            $sent_to_admin = $smsGateway->sendMessageToNumber($wa_phone, $cepatlakoo_sms_order_note, $cl_device_id );
        }

        $cl_wa_order_note = str_replace( '%lakoo_order_id%', $cl_order_id, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_order_status%', $cl_order_status, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_fullname%', $cl_fullname, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_email%', $cl_email, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_total_price%', $cl_total_price, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_tracking_date%', $cl_tracking_date, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_courier%', $cl_courier, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cl_wa_order_note);
        $cl_wa_order_note = str_replace( '%lakoo_note%', wptexturize($data['customer_note']) , $cl_wa_order_note);
        if( !empty($cl_wa_token) ){
            // cepatlakoo_wassenger_send($wa_phone, $cl_wa_order_note);
            if( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
                cepatlakoo_woowa_send( $wa_phone, $cl_wa_order_note );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
                cepatlakoo_wassenger_send( $wa_phone, $cl_wa_order_note );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
                cepatlakoo_wanotif_send( $wa_phone, $cl_wa_order_note );
            }
            else if ( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
                cepatlakoo_wablas_send( $wa_phone, $cl_wa_order_note );
            }else if ( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
                cepatlakoo_woowandroid_send( $wa_phone, $cl_wa_order_note, 'After Checkout', $cl_order_id, $cl_order_status  );
            }else if ( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
                cepatlakoo_ruangwa_send( $wa_phone, $cl_wa_order_note );
            }else if ( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
                cepatlakoo_waresponder_send( $wa_phone, $cl_wa_order_note );
            }
        }

    }
}
add_action('woocommerce_new_customer_note', 'cepatlakoo_send_customer_note', 10);

/**
 * Functions to send message to wassenger
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.0
 *
 */
if ( ! function_exists( 'cepatlakoo_wassenger_send' ) ) {
    function cepatlakoo_wassenger_send($to, $msg) {
        global $cl_options;
        //check if $to and $msg empty return false
        if( empty($to) && empty($msg) ){
            return;
        }
        //check wa token is !empty
        if(isset($cl_options['cepatlakoo_wa_token']) && !empty($cl_options['cepatlakoo_wa_token'])){
            //generate param for wassanger
            if( !empty($cl_options['cepatlakoo_wa_device']) ){
                $data = json_encode( array("phone" => $to, "message" => $msg, "device" => $cl_options['cepatlakoo_wa_device']) );
            }
            else{
                $data = json_encode( array("phone" => $to, "message" => $msg) );
            }
            //send request to wassanger
            $ch = curl_init('https://api.wassenger.com/v1/messages');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Token: '.$cl_options['cepatlakoo_wa_token'],
            ));

            $resp = curl_exec($ch);
        }
    }
}

/**
 * Functions to cron daily send pending order notification with wassenger
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.0
 *
 */
if ( ! function_exists( 'cepatlakoo_wassenger_cron_pending_daily' ) ) {
    function cepatlakoo_wassenger_cron_pending_daily() {
        global $cl_options;
        // echo'<pre>';
        //check pending order after h+1
        if( !empty($cl_options['cepatlakoo_wa_order_pending_h1']) ){
            $args = array(
                'status' => 'on-hold',
                'date_created' => date('Y-m-d', strtotime("- 1 day")),
            );
            $orders = wc_get_orders( $args );
            
            if( !empty($orders) ){
                foreach( $orders as $ord ){
                    cepatlakoo_wassenger_generate_msg($ord, 'cepatlakoo_wa_order_pending_h1');
                }
            }
        }
        //check pending order after h+2
        if( !empty($cl_options['cepatlakoo_wa_order_pending_h2']) ){
            $args2 = array(
                'status' => 'on-hold',
                'date_created' => date('Y-m-d', strtotime("- 2 day")),
            );
            $orders2 = wc_get_orders( $args2 );
            
            if( !empty($orders2) ){
                foreach( $orders2 as $ord ){
                    cepatlakoo_wassenger_generate_msg($ord, 'cepatlakoo_wa_order_pending_h2');
                }
            }
        }
    }
}
// add_action('init', 'cepatlakoo_wassenger_cron_pending_daily');

/**
 * schedule daily cron for send pending order notification with wassenger
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.0
 *
 */
if ( ! wp_next_scheduled( 'cl_order_pending_daily_cron' ) ) {
    wp_schedule_event( time(), 'daily', 'cl_order_pending_daily_cron' );
}
add_action( 'cl_order_pending_daily_cron', 'cepatlakoo_wassenger_cron_pending_daily' );
/**
 * Functions to generate message for wassenger
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.0
 *
 */
function cepatlakoo_wassenger_generate_msg($orders, $opt){
    global $cl_options;
    //check is orders empty?
    if ( !empty($orders) ) {
        $cl_wa_pending_h1 = !empty( $cl_options[$opt] ) ? $cl_options[$opt] : '';
        $cl_order = new WC_Order( $orders );
        $cl_custom_fee = $cl_order->get_fees();
        //check custom fee from order
        if ( $cl_custom_fee ) {
            foreach($cl_custom_fee as $cl_fee){}
        } else {
            $cl_fee = null;
        }

        $cl_order_id = trim(str_replace('#', '', $cl_order->get_order_number()));
        $cl_base_order_status = $cl_order->get_status();
        $cl_status_list = array(
                            'pending' => __('Pending', 'cepatlakoo'),
                            'processing' => __('Processing', 'cepatlakoo'),
                            'on-hold' => __('On Hold', 'cepatlakoo'),
                            'completed' => __('Completed', 'cepatlakoo'),
                            'cancelled' => __('Cancelled', 'cepatlakoo'),
                            'refunded' => __('Refunded', 'cepatlakoo'),
                            'failed' => __('Failed', 'cepatlakoo'),
                        );
        foreach ($cl_status_list as $cl_status_lists => $translate) {
            if ($cl_base_order_status == $cl_status_lists) {
                $cl_order_status = $translate;
            }
        }
        //get order & buyer datas
        $cl_shop_name = get_bloginfo('name');
        $cl_fullname = get_post_meta( $cl_order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $cl_order_id, '_billing_last_name', true );
        $cl_email = get_post_meta( $cl_order_id, '_billing_email', true );
        $cl_phone_number = get_post_meta( $cl_order_id, '_billing_phone', true );
        $cl_shipping_price = strip_tags( wc_price( get_post_meta( $cl_order_id, '_order_shipping', true ) ) );
        $cl_total_price = strip_tags( wc_price( $cl_order->get_total() ) );
        $cl_subtotal_price = strip_tags( wc_price( $cl_order->get_subtotal() ) );
        $cl_tracking_code = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_resi', true ) : '-';
        $cl_tracking_date = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true ) ) ? date( get_option('date_format'), strtotime(get_post_meta( $cl_order_id, '_cepatlakoo_resi_date', true )) ) : '-';
        $cl_courier = !empty( get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) ) ? get_post_meta( $cl_order_id, '_cepatlakoo_ekspedisi', true ) : '-';
        ( $cl_fee ) ? $cl_payment_code = $cl_fee->get_total() : $cl_payment_code = null;
        $items = $cl_order->get_items();
        $cl_confirmation_page = !empty( $cl_options['cepatlakoo_select_confirmation'] ) ? $cl_options['cepatlakoo_select_confirmation'] : '';
        $cl_confirm_page = get_permalink( $cl_confirmation_page ).'?orderid='.$cl_order_id.'&email='.$cl_email;
        $data_product = array();
        //get ordered products
        
        foreach ( $items as $item ) {
            $meta_data = $item->get_formatted_meta_data( '' );
            $var = array();
            foreach ($meta_data as $meta){
                if( $meta->display_key != '_reduced_stock' )
                    $var[] = $meta->display_key.': '.$meta->value;
            }
            $var = ' '.implode(', ', $var);

            array_push( $data_product, $item['name']  . $var .' ('. $item['quantity'] .'): '. strip_tags( wc_price($item->get_total())) );
        }
    
        $data_products = implode(",\n--\n",$data_product);
        //formating phone number (format : +628....)
        if( strpos($cl_phone_number, '-') !== false ){
            $cl_phone_number = str_replace('-', '', $cl_phone_number);
        }

        if ( preg_match('[^62]', $cl_phone_number ) ) {
            $wa_phone = str_replace('62', '+62', $cl_phone_number);
        } else if ( $cl_phone_number[0] == '0' ) {
            $cl_phone_number = ltrim( $cl_phone_number, '0' );
            $wa_phone = '+62'. $cl_phone_number;
        } else if ( $cl_phone_number[0] == '8' ) {
            $wa_phone = '+62'. $cl_phone_number;
        } else {
            $wa_phone = $cl_phone_number;
        }
        //replace shortcode option to real data
        $cl_wa_pending_h1 = str_replace( '%lakoo_order_id%', $cl_order_id, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_order_status%', $cl_order_status, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_shop_name%', $cl_shop_name, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_fullname%', $cl_fullname, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_email%', $cl_email, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_phone_number%', $cl_phone_number, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_shipping_price%', $cl_shipping_price, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_total_price%', $cl_total_price, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_subtotal_price%', $cl_subtotal_price, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_tracking_code%', $cl_tracking_code, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_confirmation_page%', $cl_confirm_page, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_tracking_date%', $cl_tracking_date, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_products%', $data_products, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_courier%', $cl_courier, $cl_wa_pending_h1);
        $cl_wa_pending_h1 = str_replace( '%lakoo_payment_code%', $cl_payment_code, $cl_wa_pending_h1);
        //send message to wassenger

        if( $cl_options['cepatlakoo_wa_services'] == 'woo-wa' ){
            cepatlakoo_woowa_send( $wa_phone, $cl_wa_pending_h1 );
        }
        else if ( $cl_options['cepatlakoo_wa_services'] == 'wassenger' ){
            cepatlakoo_wassenger_send( $wa_phone, $cl_wa_pending_h1 );
        }
        else if ( $cl_options['cepatlakoo_wa_services'] == 'wanotif' ){
            cepatlakoo_wanotif_send( $wa_phone, $cl_wa_pending_h1 );
        }
        else if ( $cl_options['cepatlakoo_wa_services'] == 'wablas' ){
            cepatlakoo_wablas_send( $wa_phone, $cl_wa_pending_h1 );
        }else if ( $cl_options['cepatlakoo_wa_services'] == 'woo-android' ){
            cepatlakoo_woowandroid_send( $wa_phone, $cl_wa_pending_h1, 'After Checkout', $cl_order_id, $cl_order_status );
        }
        else if ( $cl_options['cepatlakoo_wa_services'] == 'ruangwa' ){
            cepatlakoo_ruangwa_send( $wa_phone, $cl_wa_pending_h1 );
        }
        else if ( $cl_options['cepatlakoo_wa_services'] == 'waresponder' ){
            cepatlakoo_waresponder_send( $wa_phone, $cl_wa_pending_h1 );
        }

        //add note to order log
        if ( $opt == 'cepatlakoo_wa_order_pending_h1' ) {
            $cl_order->add_order_note(__('Successfully sent H+1 follow up message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_pending_h2' ) {
            $cl_order->add_order_note(__('Successfully sent H+2 follow up message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_failed' ) {
            $cl_order->add_order_note(__('Successfully sent order Failed message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_refunded' ) {
            $cl_order->add_order_note(__('Successfully sent order Refunded message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_cancel' ) {
            $cl_order->add_order_note(__('Successfully sent order Canceled message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_complete' ) {
            $cl_order->add_order_note(__('Successfully sent order Completed message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_new_order' ) {
            $cl_order->add_order_note(__('Successfully sent new order message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_pending' ) {
            $cl_order->add_order_note(__('Successfully sent order Pending message via WhatsApp', 'cepatlakoo'));
        } elseif ( $opt == 'cepatlakoo_wa_order_process' ) {
            $cl_order->add_order_note(__('Successfully sent order Processed message via WhatsApp', 'cepatlakoo'));
        } else {
            $cl_order->add_order_note(__('Successfully sent '.$opt, 'cepatlakoo'));
        }
    }
}

/**
 * Functions to send message to woo-wa.com
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.9
 *
 */
if ( ! function_exists( 'cepatlakoo_woowa_send_v2' ) ) {
    function cepatlakoo_woowa_send_v2($to, $msg) {
        global $cl_options;
        //check if $to and $msg empty return false
        if( empty($to) && empty($msg) ){
            return;
        }
        //check wa token is !empty
        if(isset($cl_options['cepatlakoo_woowa_license']) && !empty($cl_options['cepatlakoo_woowa_license']) && isset($cl_options['cepatlakoo_woowa_domain']) && !empty($cl_options['cepatlakoo_woowa_domain']) ){
            //generate param for wassanger
            $to = str_replace('+', '', $to);
            /** value editable */
            $data['domain']         = $cl_options['cepatlakoo_woowa_domain']; //domain tanpa www. tanpa http. 
            $data['license']        = $cl_options['cepatlakoo_woowa_license']; //license dari member.woo-wa.com
            $data['wa_number']      = $to; // pake format 62 didepan nya... e.g 628975835238
            //$data['gambar']       = ''; //url online gambar pake https, jika tanpa gambar silahkan dikosongkan
            $data['text']           = $msg; //isi content yg ingin di kirim

            /** value static jgn di edit */
            $url='http://api.woo-wa.com/v2.0/send-message';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);

            $err = curl_error($ch);
            curl_close ($ch);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                //succcess send
            }
            // exit();
        }
    }
}

/**
 * Functions to send message to woo-wa.com api v3
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.5.4
 *
 */
if ( ! function_exists( 'cepatlakoo_woowa_send' ) ) {
    function cepatlakoo_woowa_send($to, $msg) {
        global $cl_options;
        //check if $to and $msg empty return false
        if( empty($to) && empty($msg) ){
            return;
        }
        
        //check wa token is !empty
        if( isset($cl_options['cepatlakoo_woowa_key']) && !empty($cl_options['cepatlakoo_woowa_key'])){
            $key=$cl_options['cepatlakoo_woowa_key'];
            $url='http://116.203.92.59/api/send_message';
            $data = array(
                "phone_no"  => $to,
                "key"		=> $key,
                "message"	=> $msg
            );
            $data_string = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            curl_exec($ch);
            // echo $res=curl_exec($ch);
            curl_close($ch);
            // exit();
        }
        else if(isset($cl_options['cepatlakoo_woowa_license']) && !empty($cl_options['cepatlakoo_woowa_license']) && isset($cl_options['cepatlakoo_woowa_domain']) && !empty($cl_options['cepatlakoo_woowa_domain']) ){
            cepatlakoo_woowa_send_v2($to, $msg);
        }
    }
}

/**
 * Functions to send message to wanotif.id
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.4.9
 *
 */
if ( ! function_exists( 'cepatlakoo_wanotif_send' ) ) {
    function cepatlakoo_wanotif_send($to, $msg) {
        global $cl_options;
        //check if $to and $msg empty return false
        if( empty($to) && empty($msg) ){
            return;
        }
        //check wa token is !empty
        if(isset($cl_options['cepatlakoo_wanotif_key']) && !empty($cl_options['cepatlakoo_wanotif_key']) ){
            // METHOD POST
            // Pastikan phone menggunakan kode negara 62 di depannya
            $apikey     = $cl_options['cepatlakoo_wanotif_key'];
            $phone      = $to;
            $message    = $msg;
            $url        = 'https://api.wanotif.id/v1/send';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'Apikey'    => $apikey,
                'Phone'     => $phone,
                'Message'   => $message,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }
    }
}

/**
 * Functions to send message to wablas.com
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.5.4
 *
 */
if ( ! function_exists( 'cepatlakoo_wablas_send' ) ) {
    function cepatlakoo_wablas_send($to, $msg) {
        global $cl_options;
        //check if $to and $msg empty return false
        if ( empty($to) && empty($msg) ) {
            return;
        }
        
        //check wa token is !empty
        if ( isset($cl_options['cepatlakoo_wablas_token']) ) {
            $curl = curl_init();
            $token = $cl_options['cepatlakoo_wablas_token'];
            $server = $cl_options['cepatlakoo_wablas_server'] ? str_replace(' ', '',$cl_options['cepatlakoo_wablas_server']) : 'console';
            $url = 'https://'. $server .'.wablas.com/api/send-message';
            $data = [
                'phone' => $to,
                'message' => $msg,
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array(
                    "Authorization: $token",
                )
            );
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);
            curl_close($curl);
        }
    }
}

/**
 * Functions to send message to woowandroid woo-wa.com
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 1.5.4
 *
 */
if ( ! function_exists( 'cepatlakoo_woowandroid_send' ) ) {
    function cepatlakoo_woowandroid_send($to, $msg, $type, $title, $notice) {
        global $cl_options;
        //check if $to and $msg empty return false
        if( empty($to) && empty($msg) ){
            return;
        }
        
        //check wa token is !empty
        if ( isset($cl_options['cepatlakoo_woowandroid_cs_id']) && !empty($cl_options['cepatlakoo_woowandroid_cs_id']) ) {
            $msg = str_replace('#', '', $msg);
            $param = array(
                'app_id' => '429d3472-da0f-4b2b-a63e-4644050caf8f', //app id don't change
                'include_player_ids' => [$cl_options['cepatlakoo_woowandroid_cs_id']], //you can take Player id from Woowandroid App CS ID menu.
                'data' => array(
                    "type"      => !empty($type) ? $type : 'After Checkout', //opsional Reminder/After Checkout/Pending Payment/dll editable
                    "message"   => $msg,
                    "no_wa"     => $to
                ),
                'contents'  => array(
                    "en"    => !empty($title) ? $title : 'Title'
                ),
                "headings"  =>  array(
                    "en"    => !empty($notice) ? $notice : 'Notice'
                )
            );
            $data_json = json_encode($param);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic NjY0NzE3MTYtMzc3ZC00YmY5LWJhNzQtOGRiMWM1ZTNhNzBh')); //os_auth don't change
            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
}

/**
 * Functions to send message to ruangwa.com
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 2.1.0
 *
 */
if ( ! function_exists( 'cepatlakoo_ruangwa_send' ) ) {
    function cepatlakoo_ruangwa_send($to, $msg) {
        global $cl_options;
        if( empty($to) && empty($msg) ){
            return;
        }
        
        //check token is !empty
        if( isset($cl_options['cepatlakoo_ruangwa_token']) && !empty($cl_options['cepatlakoo_ruangwa_token']) ){
            $msg = str_replace('#', '', $msg);

            $token = $cl_options['cepatlakoo_ruangwa_token'];
            $phone = $to;
            $message = $msg;
            $url = 'https://app.ruangwa.id/api/send_message';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'    => $token,
                'number'   => $phone,
                'message'  => $message,
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        }
    }
}

/**
 * Functions to send message to waresponder.com
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since Cepatlakoo 2.3.3
 *
 */
if ( ! function_exists( 'cepatlakoo_waresponder_send' ) ) {
    function cepatlakoo_waresponder_send($to, $msg) {
        global $cl_options;
        if( empty($to) && empty($msg) ){
            return;
        }
        
        //check token is !empty
        if( isset($cl_options['cepatlakoo_waresponder_device']) && !empty($cl_options['cepatlakoo_waresponder_device']) ){
            $msg = str_replace('#', '', $msg);

            $device = $cl_options['cepatlakoo_waresponder_device'];
            $phone = str_replace('+62', '0', $to);
            $message = $msg;

            // API URL
            $url = 'https://app.waresponder.com/api/send';

            // Create a new cURL resource
            $ch = curl_init($url);

            // Setup request to send json via POST
            $data = array(
                'device_id' => $device,
                'number' => $phone,
                'message' => $message,
                // 'file' => 'url file', //OPTIONAL
                // 'schedule' => 'Y-m-d H:i' //OPTIONAL
            );
            $payload = $data;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}

add_action( 'wp_footer', 'cl_send_thankyou_ajax' );
function cl_send_thankyou_ajax(){
    global $wp;
	// exit if we are not on the Thank You page
    if( !is_wc_endpoint_url( 'order-received' ) )
        return;

    $order_id  = absint( $wp->query_vars['order-received'] );
    ?>

	<script>
	jQuery(document).ready(function($) {
        $.ajax({
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            type: 'POST',
            data: "<?php echo 'action=cl_send_order_notification&order_id='.$order_id; ?>",
            success : function( data ){
                // console.log( data );
            }
        });
	});
	</script>
    <?php
}