<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function handle_xendit_webhook_payment_billing(WP_REST_Request $request)
{
    $body = $request->get_json_params();

    if (!$body || !isset($body['status']) || $body['status'] !== 'PAID') {
        return new WP_REST_Response('Invalid webhook data', 400);
    }

    // Ambil data penting dari webhook
    $invoice_id = isset($body['external_id']) ? $body['external_id'] : '';
    $amount = isset($body['amount']) ? $body['amount'] : 0;
    $currency = isset($body['currency']) ? $body['currency'] : 'IDR';
    $payer_email = isset($body['payer_email']) ? $body['payer_email'] : '';
    $merchant_name = isset($body['merchant_name']) ? $body['merchant_name'] : 'Xendit';
    $paid_at = isset($body['paid_at']) ? date('d M Y, H:i', strtotime($body['paid_at'])) : '';
    $payment_method = isset($body['payment_method']) ? $body['payment_method'] : '';
    $payment_channel = isset($body['payment_channel']) ? $body['payment_channel'] : '';
    $items = isset($body['items']) ? $body['items'] : [];
    $description = isset($body['description']) ? $body['description'] : '';

    error_log("Webhook data: " . print_r($body, true));
    // Dapatkan logo situs
    $site_logo = get_custom_logo();
    if (!$site_logo) {
        $site_logo = '<h1>' . get_bloginfo('name') . '</h1>';
    }

    // Buat email body dengan tampilan simpel & light mode
    $email_body = "<div style='font-family: Arial, sans-serif; background: #ffffff; color: #333; padding: 20px;'>
        <div style='text-align: center;'>$site_logo</div>
        <h2 style='color: #4CAF50;'>Pembayaran Berhasil!</h2>
        <p>Halo,</p>
        <p>Terima kasih telah melakukan pembayaran melalui <strong>$merchant_name</strong>. Berikut detail transaksinya:</p>
        <ul>
            <li><strong>ID Invoice:</strong> $invoice_id</li>
            <li><strong>Jumlah Dibayar:</strong> $currency " . number_format($amount, 0, ',', '.') . "</li>
            <li><strong>Tanggal Pembayaran:</strong> $paid_at</li>
            <li><strong>Email Pembayar:</strong> $payer_email</li>
            <li><strong>Metode Pembayaran:</strong> $payment_method </li>
            <li><strong>Channel Pembayaran:</strong> $payment_channel </li>
            <li><strong>Keterangan:</strong> $description</li>
        </ul>
        <h3>Detail Produk:</h3>
        <ul>
            <li><strong>Produk:</strong></li>";
    foreach ($items as $item) {
        $email_body .= "<li>" . $item['name'] . " - " . $item['price'] . " x " . $item['quantity'] . "</li>";
    }
    $email_body .= "</ul>
        <p>Terima kasih telah berbelanja di toko kami. Pesanan Anda akan segera diproses.</p>   


        <p>Jika Anda memiliki pertanyaan, silakan hubungi kami.</p>
        <p>Salam,<br><strong>" . get_bloginfo('name') . "</strong></p>
    </div>";

    // Kirim email ke pembayar
    $subject = 'Konfirmasi Pembayaran Berhasil';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail('novangarut@gmail.com', $subject, $email_body, $headers);

    return new WP_REST_Response('Webhook handled successfully', 200);
}

function handle_tripay_payment_complete($order_id)
{
    // Cek apakah ini transaksi Tripay
    $is_tripay_transaction = get_post_meta($order_id, 'is_tripay_transaction', true);

    if ($is_tripay_transaction === "yes") {
        $checkout = new PPOB_Tripay_Checkout();
        $checkout->process_tripay_prabayar($order_id);
    }
}
add_action('woocommerce_payment_complete', 'handle_tripay_payment_complete');

// Registrasi endpoint webhook
add_action('rest_api_init', function () {
    register_rest_route('xendit/v1', '/payment-callback/', array(
        'methods' => 'POST',
        'callback' => 'handle_xendit_webhook_payment_billing',
        'permission_callback' => '__return_true',
    ));
});

//buat endpoint webhook untuk status pembayaran xendit
add_action('rest_api_init', function () {
    register_rest_route('tripay/v1', '/payment-status-callback/', array(
        'methods' => 'POST',
        'callback' => 'handle_tripay_webhook_payment_status',
        'permission_callback' => '__return_true',
    ));
});


function handle_tripay_webhook_payment_status(WP_REST_Request $request)
{
    $body = $request->get_json_params();
    error_log("Triggering Tripay Webhook ....");

    if (!$body || !isset($body[0]['status'])) {
        return new WP_REST_Response(['message' => 'Invalid webhook data'], 400);
    }

    error_log("Tripay Webhook data: " . print_r($body, true));

    $order_id = $body[0]['api_trxid'] ?? null;
    $trx_id = $body[0]['trxid'] ?? null;
    $status = $body[0]['status']; // Status berupa angka (1 = sukses, 2 = gagal)


    if (!$order_id) {
        return new WP_REST_Response(['message' => 'Order ID is missing'], 400);
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return new WP_REST_Response(['message' => 'Order not found'], 404);
    }

    update_post_meta($order_id, 'tripay_trxid', $trx_id);
    update_post_meta($order_id, 'tripay_api_trxid', $body[0]['api_trxid'] ?? '');
    update_post_meta($order_id, 'tripay_produk', $body[0]['produk'] ?? '');
    update_post_meta($order_id, 'tripay_harga', $body[0]['harga'] ?? 0);
    update_post_meta($order_id, 'tripay_target', $body[0]['target'] ?? '');
    update_post_meta($order_id, 'tripay_note', $body[0]['note'] ?? '');
    update_post_meta($order_id, 'tripay_status', $status);
    update_post_meta($order_id, 'tripay_token', $body[0]['token'] ?? '');
    update_post_meta($order_id, 'tripay_created_at', $body[0]['created_at'] ?? '');

    if ($status == 1) { // 1 = sukses (PAID)
        $order->set_status('completed');
        $order->save();

        $is_pln_prabayar = get_post_meta($order_id, 'is_pln_prabayar', true);
        update_post_meta($order_id, 'tripay_user_number', $body[0]['user_number'] ?? '');
        if (empty($body[0]['token']) || $body[0]['token'] == '-') {
            $token = $body[0]['token'] ?? '';
            update_post_meta($order_id, 'tripay_token', $token);
           // Cek jika class WhatsApp_OTP_Verification tersedia
            if (class_exists('WhatsApp_OTP_Verification')) {
                $wa = new WhatsApp_OTP_Verification();
        
                // Ambil customer ID dari order
                $order = wc_get_order($order_id);
                if ($order) {
                    $customer_id = $order->get_user_id();
        
                    // Ambil nomor WhatsApp dari user meta
                    $phone = get_user_meta($customer_id, '_whatsapp_number', true);
        
                    // Kirim token jika nomor ada
                    if (!empty($phone)) {
                        $send_token = $wa->send_pln_token($phone, $token);
                    }
                }
            }
        }
        
    } elseif ($status == 2) { // 2 = gagal (FAILED)
        $order->set_status('failed');
        $order->save();

        $customer_id = $order->get_customer_id();
        $yith_funds = new YITH_YWF_Customer($customer_id);

        $amount = $order->get_total();
        $yith_funds->add_funds_with_log($amount, [
            'type_operation' => 'restore',
            'description'    => 'Pembelian gagal, dana dikembalikan',
            'order_id'       => $order_id,
        ]);
    } else {
        return new WP_REST_Response(['message' => 'Unknown status'], 400);
    }

    return new WP_REST_Response(['message' => 'Webhook processed successfully'], 200);
}

function shopbizpay_balance_card_shortcode($atts)
{
    // Extract attributes
    $atts = shortcode_atts([
        'name' => 'ShopbizPay'
    ], $atts, 'shopbizpay');

    // Get current user ID
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your balance.</p>';
    }

    $customer_id = get_current_user_id();
    $yith_funds = new YITH_YWF_Customer($customer_id);
    $balance = $yith_funds->get_funds();

    // Format balance
    $balance_display = wc_price($balance);

    // Card HTML
    ob_start();
?>
    <div class="shopbizpay-card">
        <div class="shopbizpay-icon">
            <i class="dashicons dashicons-money-alt"></i>
        </div>
        <div class="shopbizpay-content">
            <h3><?php echo esc_html($atts['name']); ?></h3>
            <p><strong><?php echo $balance_display; ?></strong></p>
        </div>
        <div style="margin-left: 20px; color: darkorange;">
                    <?php echo do_shortcode("[yith_ywf_make_a_deposit_small type='link' text='Top up' amount=50000]");?>
        </div>        
    </div>
    <style>
        .shopbizpay-card {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            /*margin: auto;*/
        }

        .shopbizpay-icon {
            background: darkorange;
            color: #fff;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 24px;
            margin-right: 15px;
        }

        .shopbizpay-content h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .shopbizpay-content p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        @media (max-width: 480px) {
            .shopbizpay-card {
                flex-direction: column;
                text-align: center;
            }

            .shopbizpay-icon {
                margin: 0 0 10px;
            }
        }
    </style>
<?php
    return ob_get_clean();
}
add_shortcode('shopbizpay', 'shopbizpay_balance_card_shortcode');
function display_tripay_transaction_details($order)
{
    $trx_id = get_post_meta($order->get_id(), 'tripay_trxid', true);
    $phone = get_post_meta($order->get_id(), 'tripay_phone_number', true);
    $produk = get_post_meta($order->get_id(), 'tripay_produk', true);
    $note = get_post_meta($order->get_id(), 'tripay_note', true);
    $token = get_post_meta($order->get_id(), 'tripay_token', true);

    if ($trx_id) {
        echo '<div style="overflow-x:auto;">'; // Scrollable wrapper for small screens
        echo '<table style="width:100%; border-collapse: collapse; border: 1px solid #ddd; font-size: 16px;">';
        echo '<tr style="background: #f8f8f8; font-weight: bold;"><th style="padding: 10px; border: 1px solid #ddd;">Keterangan</th><th style="padding: 10px; border: 1px solid #ddd;">Detail</th></tr>';
        echo '<tr><td style="padding: 8px; border: 1px solid #ddd;">ID Transaksi</td><td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($trx_id) . '</td></tr>';
        echo '<tr><td style="padding: 8px; border: 1px solid #ddd;">No. HP</td><td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($phone) . '</td></tr>';
        echo '<tr><td style="padding: 8px; border: 1px solid #ddd;">Produk</td><td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($produk) . '</td></tr>';
        if ($token) {
            echo '<tr><td style="padding: 8px; border: 1px solid #ddd;">Token</td><td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($token) . '</td></tr>';
        }
        echo '<tr><td style="padding: 8px; border: 1px solid #ddd;">Catatan</td><td style="padding: 8px; border: 1px solid #ddd;">' . esc_html($note) . '</td></tr>';
        echo '</table>';
        echo '</div>'; // Close responsive wrapper
    }
}
add_action('woocommerce_order_details_after_order_table_items', 'display_tripay_transaction_details');

function ppob_category_button_shortcode() {
    ob_start(); ?>
    <div class="category-grid-item">
        <a href="https://shopbiz.id/ppob-categories" class="category-main-link" style="display: flex; align-items: center; justify-content: center; padding: 15px;">
            <img src="https://cdn-icons-png.flaticon.com/512/595/595853.png" alt="Icon" width="24" height="24" style="margin-right: 10px;">
            Pulsa & lainnya
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ppob_button', 'ppob_category_button_shortcode');