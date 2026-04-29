<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PPOB_Tripay_Checkout
{
    private $api;
    public function __construct()
    {
        $this->api = new PPOB_Tripay_API();
        add_shortcode('custom_quick_checkout', [$this, 'render_custom_checkout']);
        add_action('wp_ajax_check_pln_user_prabayar', [$this, 'checkPlnPrabayar']);
        add_action('wp_ajax_custom_quick_checkout', [$this, 'process_checkout']);
        add_action('wp_ajax_nopriv_custom_quick_checkout', [$this, 'process_checkout']);
        add_action('wp_ajax_process_tripay_prabayar', [$this, 'process_tripay_prabayar']);
    }

    public function checkPlnPrabayar()
    {
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => 'You must be logged in to use quick checkout.']);
        }

        $no_meter_pln = isset($_POST['no_meter_pln']) ? sanitize_text_field($_POST['no_meter_pln']) : '';

        if (empty($no_meter_pln)) {
            wp_send_json_error(['message' => 'No Meteran PLN tidak valid']);
        }

        $response = $this->api->request('https://cek-id-pln-pasca-dan-pra-bayar.p.rapidapi.com/pln/' . $no_meter_pln . '/token_pln', null, 'GET', [], [
            'x-rapidapi-key' => get_option('ppob_tripay_rapid_api_key'),
            'x-rapidapi-host' => 'cek-id-pln-pasca-dan-pra-bayar.p.rapidapi.com'
        ]);

        if (is_wp_error($response)) {

            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        wp_send_json($response, 200);
    }
    public function render_custom_checkout()
    {
       if (!is_user_logged_in()) {
            $current_url = esc_url($_SERVER['REQUEST_URI']);
            $login_url = wp_login_url($current_url);
            
            return '<p>Anda harus login terlebih dahulu</p>
                    <a href="' . $login_url . '" class="button">Login</a>';
        }

        $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

        // Get WooCommerce payment methods
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

        if (empty($available_gateways)) {
            return '<p>Metode Pembayaran tidak ada</p>';
        }

        ob_start();
?>
        <form id="quick-checkout-form">
            <!-- selected product detail like product code, phone number but not input just text label-->
            <input type="hidden" id="quantity" value="1">
            <h3>Detail Pembelian</h3>
            <h4>Produk:</h4>
            <input type="hidden" id="selected-product-code" value="">
            <p id="selected-product-name"></p>
            <h4>No.Hp/No.Pelanggan/UserID:</h4>
            <p id="selected-phone-number"> </p>
            <?php if ($category_id == 19) : ?>
                <h4>No Meteran PLN:</h4>
                <p id="selected-meter-number"> </p>
            <?php endif; ?>
            <h3 id="selected-product-price"></h3>
            <input type="hidden" id="selected-product-id">

            <hr>

            <h3>Metode Pembayaran</h3>
            <ul>
                
                
               <?php 
                    $balance = new YITH_YWF_Customer(get_current_user_id());
                    $balance = $balance->get_funds();
                    
                    // Loop through available gateways
                    foreach ($available_gateways as $gateway_id => $gateway) { 
                        // If balance > 0, only show YITH Funds
                        if ($balance > 0 && $gateway_id !== 'yith_funds') {
                            continue; // Skip non-YITH Funds payment methods
                        }
                    ?>
                        <li style="list-style-type: none; display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="radio" name="payment_method" value="<?php echo esc_attr($gateway_id); ?>" class="payment-method-radio">
                                <?php if ($gateway->get_icon()): ?>
                                    <span style="height: 60px; display: flex; align-items: center;">
                                        <?php echo str_replace('<img', '<img style="height: auto; width: 40px;"', $gateway->get_icon()); ?>
                                    </span>
                                <?php endif; ?>
                                <strong><?php echo esc_html($gateway->get_title()); ?></strong>
                    
                                <!-- Show YITH Funds balance -->
                                <?php if ($gateway_id == "yith_funds") { ?>
                                    <span style="font-size: 12px; color: #666;">Saldo: <?php echo esc_html($balance); ?></span>
                                <?php } ?>
                            </label>
                        </li>
                    
                        <!-- Payment Description (Initially Hidden) -->
                        <?php if ($gateway->get_description()): ?>
                            <p class="payment-description" style="margin-left: 25px; font-size: 14px; color: #666; display: none;">
                                <?php echo wp_kses_post($gateway->get_description()); ?>
                            </p>
                        <?php endif; ?>
                    
                    <?php } // End foreach ?>
            </ul>
            <button class="button" style="width: 100%;" type="submit">Bayar</button>
        </form>

        <script>
            jQuery(document).ready(function($) {
                $(".payment-method-radio").on("change", function() {
                    $(".payment-description").hide(); // Hide all descriptions
                    $(this).closest("li").next("p.payment-description").show(); // Show the selected one
                });
                $('#quick-checkout-form').submit(function(e) {
                    e.preventDefault();

                    var selectedMethod = $('input[name="payment_method"]:checked').val();
                    var productId = $('#selected-product-id').val();
                    var phone = $('#phone_number').val();
                    var meterNumber = $('#meter_number').val();

                    if (!phone) {
                        alert("No. Hp/No.Pelanggan/UserID tidak valid");
                        return;
                    }

                    if (!selectedMethod) {
                        alert('Silahkan pilih metode pembayaran');
                        return;
                    }

                    if (!productId) {
                        alert('Silahkan pilih produk terlebih dahulu');
                        return;
                    }


                    $.ajax({
                        type: 'POST',
                        url: PPOBTripay.ajax_url, // WP AJAX URL
                        data: {
                            action: 'custom_quick_checkout',
                            payment_method: selectedMethod,
                            product_id: productId,
                            phone: phone,
                            meter_number: meterNumber
                        },
                        beforeSend: function() {
                            $('#quick-checkout-form button').text('Memproses...').prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = response.data.redirect;
                            } else {
                                alert(response.data.message);
                            }
                        },
                        complete: function() {
                            $('#quick-checkout-form button').text('Bayar').prop('disabled', false);
                        }
                    });
                });
            });
        </script>
<?php
        return ob_get_clean();
    }

    public function process_checkout()
    {
        global $wpdb;
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => 'You must be logged in to use quick checkout.']);
        }

        $payment_method = isset($_POST['payment_method']) ? wc_clean($_POST['payment_method']) : '';
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $no_meter_pln = isset($_POST['meter_number']) ? sanitize_text_field($_POST['meter_number']) : '';

        if (empty($phone)) {
            wp_send_json_error(['message' => 'Nomor Hp/UserID/No. Pelanggan tidak valid']);
        }
        if (empty($payment_method)) {
            wp_send_json_error(['message' => 'Please select a payment method.']);
        }

        //find product with SKU == product_code
        $tripay_cart = new PPOB_Tripay_Cart();

        $product = wc_get_product($product_id);

        if (!$product) {
            wp_send_json_error(['message' => 'Product not found.']);
        }

        // Create a new order
        $customer_id = get_current_user_id();
        $order = wc_create_order([
            'customer_id' => $customer_id,
        ]);

        if (is_wp_error($order)) {
            wp_send_json_error(['message' => $order->get_error_message()]);
        }

        $order->add_product($product, $quantity);
        $order->set_payment_method($payment_method);
        $order->set_payment_method_title(WC()->payment_gateways()->payment_gateways()[$payment_method]->title);
        $order->calculate_totals();
        update_post_meta($order->get_id(), 'tripay_phone_number', $phone);
        update_post_meta($order->get_id(), 'is_tripay_transaction', "yes");

        if ($no_meter_pln) {
            update_post_meta($order->get_id(), 'no_meter_pln', $no_meter_pln);
            update_post_meta($order->get_id(), 'is_pln_prabayar', true);
        }


        // Process payment
        $payment_gateway = wc_get_payment_gateway_by_order($order);
        if (!$payment_gateway) {
            wp_send_json_error(['message' => 'Invalid payment method.']);
        }

        $result = $payment_gateway->process_payment($order->get_id());

        if (isset($result['result']) && $result['result'] === 'success') {
            wp_send_json_success([
                'redirect' => $result['redirect'],
                'message'  => 'Order placed successfully!',
            ]);
        } else {
            wp_send_json_error(['message' => 'Payment failed.']);
        }
    }

    public function process_tripay_prabayar($order_id)
    {
        if (!$order_id) {
            error_log("[PPOB_Tripay] Order ID tidak valid");
            return ['success' => false, 'message' => 'Order ID tidak valid'];
        }

        $order = wc_get_order($order_id);

        if (!$order) {
            error_log("[PPOB_Tripay] Order tidak ditemukan: $order_id");
            return ['success' => false, 'message' => 'Order tidak ditemukan'];
        }

        // Get order item SKU for the code
        $code = "";
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            if ($product) {
                $code = $product->get_sku();
                break;
            }
        }

        if (empty($code)) {
            error_log("[PPOB_Tripay] Produk tidak memiliki kode SKU untuk order: $order_id");
            return ['success' => false, 'message' => 'Produk tidak memiliki kode SKU'];
        }

        // Get order phone from order meta (tripay_phone_number)
        $order_phone = get_post_meta($order_id, 'tripay_phone_number', true);

        if (empty($order_phone)) {
            error_log("[PPOB_Tripay] Nomor telepon tidak ditemukan untuk order: $order_id");
            return ['success' => false, 'message' => 'Nomor telepon tidak ditemukan'];
        }

        // Prepare request body
        $body = [
            'inquiry'   => "I",
            'code'      => $code,
            'phone'     => $order_phone,
            'api_trxid' => $order_id,
            'pin'       => get_option('ppob_tripay_pin'),
        ];

        $is_pln_prabayar = get_post_meta($order_id, 'is_pln_prabayar', true);
        $no_meter_pln = get_post_meta($order_id, 'no_meter_pln', true);

        if ($is_pln_prabayar && $no_meter_pln) {
            $body['inquiry'] = "PLN";
            $body['no_meter_pln'] = $no_meter_pln;
        }

        error_log("[PPOB_Tripay] Mengirim request ke Tripay untuk order: $order_id | Data: " . json_encode($body));

        // Kirim permintaan ke API Tripay
        $response = $this->api->request('/transaksi/pembelian', json_encode($body));

        if (is_wp_error($response)) {

            error_log("[PPOB_Tripay] Gagal menghubungi Tripay untuk order: $order_id | Error: " . $response->get_error_message());
            $order = wc_get_order($order_id);
            $order->set_status('failed');
            return ['success' => false, 'message' => 'Gagal menghubungi Tripay'];
        }

        if (!$response['success']) {
            error_log("[PPOB_Tripay] Transaksi gagal untuk order: $order_id | Response: " . json_encode($response));
            return ['success' => false, 'message' => 'Transaksi gagal'];
        }

        error_log("[PPOB_Tripay] Transaksi berhasil untuk order: $order_id | Response: " . json_encode($response));
        update_post_meta($order_id, 'tripay_transaction_id', $response['trxid']);

        return [
            'success' => true,
            'message' => 'Transaksi berhasil',
            'data'    => $response,
        ];
    }
}


new PPOB_Tripay_Checkout();
