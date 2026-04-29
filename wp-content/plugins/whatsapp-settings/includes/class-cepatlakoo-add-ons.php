<?php 

if ( ! class_exists( 'Redux' ) ) {
    return;
}

$opt_name = "cl_options";
Redux::setSection( $opt_name, array(
        'id'               => 'wa_notification',
        'fields' => array(
              array(
                'id'                        => 'cepatlakoo_wanotification_vendor',
                'type'                      => 'section',
                'title'                     => esc_html__('Notification for Vendor', 'cepatlakoo'),
                'subtitle'                  => esc_html__('Set notification message for vendor', 'cepatlakoo'),
                'indent'                    => true,
            ),
            array(
                'id'                        => 'cepatlakoo_wa_new_order_vendor_info',
                'type'                      => 'info',
                'style'                     => 'success',
                'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
                'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_products%</strong> : Daftar Produk yang dipesan', 'cepatlakoo'),
                ),
            array(
                'id'       => 'enable_wa_vendor_notification', // ID unik untuk field ini
                'type'     => 'checkbox',
                'title'    => __( 'Aktifkan Notifikasi WhatsApp untuk vendor', 'cepatlakoo' ),
                'subtitle' => __( 'Aktifkan fitur ini untuk mengirimkan notifikas WhatsApp kepada vendor setelah customer melakukan pembayaran', 'cepatlakoo' ),
                'desc'     => __( 'Centang untuk mengaktifkan notifikasi WhatsApp kepada vendor', 'cepatlakoo' ),
                'default'  => true, // Default tidak aktif
            ),
            array(
                'id'       => 'wa_vendor_message_template', // ID unik untuk field ini
                'type'     => 'textarea',
                'title'    => __( 'Template pesan untuk pengiriman notifikasi kepada vendor', 'cepatlakoo' ),
                'subtitle' => __( 'Aktifkan fitur ini untuk mengirimkan notifikasi WhatsApp kepada vendor setelah customer melakukan pembayaran', 'cepatlakoo' ),
                'default'  => true, // Default tidak aktif
            ),
             array(
                'id'                        => 'cepatlakoo_wanotification_main',
                'type'                      => 'section',
                'title'                     => esc_html__('Main', 'cepatlakoo'),
                'subtitle'                  => esc_html__('Main settings', 'cepatlakoo'),
                'indent'                    => true,
            ),
            
        ),
    ) );
    
// Hook into the same AJAX action as the original plugin
// add_action( 'wp_ajax_cl_send_order_notification', 'custom_cepatlakoo_ajax_notification' );
// add_action( 'wp_ajax_nopriv_cl_send_order_notification', 'custom_cepatlakoo_ajax_notification' );

// if ( ! function_exists( 'custom_cepatlakoo_ajax_notification' ) ) {
//     function custom_cepatlakoo_ajax_notification() {
//         // Get the order ID from the POST request
//         $order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : null;
//         error_log('THIS IS FROM CUSTOM AJAX CEPATLAKOO');
//         // Check if the order ID is valid
//         if ( ! empty( $order_id ) ) {

//             // Add any custom notification logic here, like vendor notifications
//             cepatlakoo_new_order_vendor( $order_id );

//             // Optional: Ensure the status is marked as processed after custom logic
//             add_post_meta( $order_id, 'cepatlakoo_ajax_notification', date( 'Y-m-d H:i:s' ) );
//         }

//     }
// }

add_action('woocommerce_order_status_waiting-conf', 'cepatlakoo_new_order_vendor', 10, 1);
if ( ! function_exists( 'cepatlakoo_new_order_vendor' ) ) {
    function cepatlakoo_new_order_vendor( $order_id ) {
        global $cl_options;
        // Check if WhatsApp notifications for vendors are enabled
        $enable_wa_notification = isset( $cl_options['enable_wa_vendor_notification'] ) ? $cl_options['enable_wa_vendor_notification'] : false;
        if ( ! $enable_wa_notification ) {
            error_log("WhatsApp vendor notification is disabled. Skipping logic for order ID: " . $order_id);
            return; // Exit if the checkbox is not checked
        }
        error_log("Starting new order vendor notification for order ID: " . $order_id);

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            error_log("Order not found for order ID: " . $order_id);
            return;
        }

        $items = $order->get_items();
        $vendors_notified = [];

        foreach ( $items as $item ) {
            $product = $item->get_product(); // Get product object
            if ( $product ) {
                $vendor_id = get_post_field( 'post_author', $product->get_id() );
                if ( ! $vendor_id || in_array( $vendor_id, $vendors_notified ) ) {
                    continue; // Skip if vendor is already notified or if vendor ID is not found
                }

                $vendors_notified[] = $vendor_id;
                $vendor_store_info = dokan_get_store_info( $vendor_id );
                $vendor_phone = isset( $vendor_store_info['phone'] ) ? $vendor_store_info['phone'] : '';

                if ( empty( $vendor_phone ) ) {
                    error_log("Vendor phone is empty for vendor ID: " . $vendor_id);
                    continue;
                }

                // Format phone number
                if ( preg_match( '/^62/', $vendor_phone ) ) {
                    $wa_phone = str_replace( '62', '+62', $vendor_phone );
                } elseif ( $vendor_phone[0] === '0' ) {
                    $vendor_phone = ltrim( $vendor_phone, '0' );
                    $wa_phone = '+62' . $vendor_phone;
                } elseif ( $vendor_phone[0] === '8' ) {
                    $wa_phone = '+62' . $vendor_phone;
                } else {
                    $wa_phone = $vendor_phone;
                }

                error_log("Formatted WhatsApp phone for vendor ID " . $vendor_id . ": " . $wa_phone);

                // Prepare WhatsApp message template
                $cl_wa_new_order_admin = !empty( $cl_options['wa_vendor_message_template'] ) ? $cl_options['wa_vendor_message_template'] : '';
                $cl_wa_new_order_vendor = $cl_wa_new_order_admin; // Use a base template for replacements

                // Replace placeholders
                $cl_wa_new_order_vendor = str_replace( '%lakoo_order_id%', $order_id, $cl_wa_new_order_vendor );
                $cl_wa_new_order_vendor = str_replace( '%lakoo_order_status%', $order->get_status(), $cl_wa_new_order_vendor );
                $cl_wa_new_order_vendor = str_replace( '%lakoo_shop_name%', $vendor_store_info['store_name'], $cl_wa_new_order_vendor );

                // Prepare product list for this vendor
                $data_product = [];
                foreach ( $items as $item ) {
                    $product = $item->get_product();
                    if ( $product && get_post_field( 'post_author', $product->get_id() ) == $vendor_id ) {
                        $data_product[] = $item->get_name() . ' (' . $item->get_quantity() . ')';
                    }
                }

                $cl_wa_new_order_vendor = str_replace( '%lakoo_products%', implode( ', ', $data_product ), $cl_wa_new_order_vendor );
                $cl_wa_new_order_vendor = str_replace( '%lakoo_total_price%', $order->get_total(), $cl_wa_new_order_vendor );

                error_log("WhatsApp message for vendor: " . $cl_wa_new_order_vendor);

                // Send WhatsApp notification to the vendor
                if ( ! empty( $cl_options['cepatlakoo_wa_services'] ) ) {
                    if ( $cl_options['cepatlakoo_wa_services'] === 'woo-wa' ) {
                        cepatlakoo_woowa_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via woo-wa service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'wassenger' ) {
                        cepatlakoo_wassenger_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via wassenger service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'wanotif' ) {
                        cepatlakoo_wanotif_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via wanotif service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'wablas' ) {
                        cepatlakoo_wablas_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via wablas service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'woo-android' ) {
                        cepatlakoo_woowandroid_send( $wa_phone, $cl_wa_new_order_vendor, 'After Checkout', $order_id, $order->get_status() );
                        error_log("Sent message via woo-android service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'ruangwa' ) {
                        cepatlakoo_ruangwa_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via ruangwa service to: " . $wa_phone);
                    } elseif ( $cl_options['cepatlakoo_wa_services'] === 'waresponder' ) {
                        cepatlakoo_waresponder_send( $wa_phone, $cl_wa_new_order_vendor );
                        error_log("Sent message via waresponder service to: " . $wa_phone);
                    }
                }
            }
        }
    }
}