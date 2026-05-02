<?php
class Komship_Order_Expedition {
    private $api;

    public function __construct() {
        $this->api = new Komship_API();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_airway_bill_js']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('init', function() {
            
            wp_register_script(
                'komship-admin',
                KOMSHIP_INTEGRATION_PLUGIN_URL . 'assets/js/admin.js',
                ['jquery', 'jquery-ui-autocomplete'],
                KOMSHIP_INTEGRATION_VERSION,
                true
            );
        
            wp_localize_script('komship-admin', 'komshipData', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('komship_nonce'),
            ]);
        
            foreach(['wp_enqueue_scripts', 'admin_enqueue_scripts'] as $hook) {
                add_action($hook, function() {
                    wp_enqueue_script('komship-admin');
                });
            }
        });       
        add_action('wp_ajax_komship_search_destination', [$this, 'ajax_search_destination']);
        add_action('wp_ajax_komship_calculate_shipping', [$this, 'ajax_calculate_shipping']);
        add_action('wp_ajax_komship_store_order', [$this, 'ajax_store_order']);
        add_action('wp_ajax_komship_order_detail', [$this, 'ajax_order_detail']);
        add_action('wp_ajax_komship_cancel_order', [$this, 'ajax_cancel_order']);
        add_action('wp_ajax_komship_request_pickup', [$this, 'ajax_request_pickup']);
        add_action('wp_ajax_komship_create_label', [$this, 'ajax_create_label']);
        add_action('wp_ajax_komship_history_airway_bill', [$this, 'ajax_history_airway_bill']);
       
        $this->add_expedition_meta_boxes();
        // add_action('dokan_komship', [$this,'render_komship_expedition_meta_box'], 10, 1);
        // add_action('dokan_komship', [$this,'komship_pickup_metabox_html'], 11, 1);
        // add_action('woocommerce_order_details_after_order_table', [$this, 'history_airway_bill_html'], 10,1);
        add_action('dokan_order_detail_after_order_items', [$this,'render_komship_expedition_meta_box'], 10, 1);
        add_action('dokan_order_detail_after_order_items', [$this,'komship_pickup_metabox_html'], 11, 1);

        
        add_action('woocommerce_order_details_after_order_table', [$this, 'render_airway_bill_history'], 100, 1);
        add_action('dokan_order_detail_after_order_items',  [$this, 'render_airway_bill_history'], 100, 1);
        
        error_log("KOMSHIP ORDER EXPEDITION INITIALIZED");
    }
   
    function enqueue_airway_bill_js() {
         
        $script_dependencies = ['jquery'];
    
        $script_version = file_exists(plugin_dir_path(__FILE__) . 'assets/js/airway_bill_history.js') 
            ? filemtime(plugin_dir_path(__FILE__) . 'assets/js/airway_bill_history.js') 
            : '1.0.0';
        error_log('JS VERSION'.  $script_version);
        wp_register_style(
            'font-awesome', 
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', 
            [], 
            '5.15.3', 
            'all'
        );
        wp_register_style(
            'airway-bill-style', 
            plugin_dir_url(__FILE__) . 'assets/css/airway_bill_history.css', 
            [], 
            '1.0.0', 
            'all'
        );
        
    
        // Directly enqueue the style and script
        wp_enqueue_style('airway-bill-style');
        wp_enqueue_script(
            'airway-bill-script',           
            plugin_dir_url(__FILE__) . 'assets/js/airway_bill_history_new.js', 
            $script_dependencies,           
            $script_version,              
            false                           
        );
        if (is_wc_endpoint_url('view-order')) {
            global $wp;
            $order_id = intval(end(explode('/', $wp->request)));
            $order = wc_get_order($order_id);
    
            if ($order) {
                $resi = $order->get_meta('plugin_resi_data');
                wp_localize_script('airway-bill-script', 'trackingData', array(
                    'courier'  => $resi['courier'] ?? '',
                    'service'  => $resi['service'] ?? '',
                    'tracking' => $resi['tracking'] ?? '',
                    'ajax_url' => admin_url('admin-ajax.php'), 
                    'nonce' => wp_create_nonce('komship_nonce'),
                ));
            }
            
        }
        wp_enqueue_style('font-awesome');
       
    }


    
    public function enqueue_scripts($hook) {
        // if ('post.php' !== $hook || get_post_type() !== 'shop_order') {
        //     return;
        // }
        // wp_enqueue_script('komship-admin', KOMSHIP_INTEGRATION_PLUGIN_URL . 'assets/js/admin.js', ['jquery', 'jquery-ui-autocomplete'], KOMSHIP_INTEGRATION_VERSION, true);
        wp_localize_script('komship-admin', 'komshipData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('komship_nonce'),
        ]);
    }
   

    public function add_expedition_meta_boxes() {
        error_log("KOMSHIP METABOX");
        add_action('add_meta_boxes', [$this, 'add_komship_expedition_meta_box']);
    }

    public function add_komship_expedition_meta_box() {
        error_log("MULAI KOMSHIP METABOX");
        add_meta_box(
            'komship_expedition_calculation',
            'Atur Pengiriman',
            [$this, 'render_komship_expedition_meta_box'],
            'woocommerce_page_wc-orders',
            'normal',
            // 'high'
        );
        add_meta_box(
            'komship_pickup_request',       
            'Komship Pickup Request',       
            [$this, 'komship_pickup_metabox_html'],         
            'woocommerce_page_wc-orders',                         
            'normal',                       
            // 'high'                       
        );
        
    }
    
// Function to render Airway Bill history in WooCommerce Order details
    public function render_airway_bill_history($order) {
        $resi = $order->get_meta('plugin_resi_data');
        
        if(!$resi){
            return;
        }
        ?>
        
       <div class="container-tracking" >
            <h1 style="font-size: 24px; color: #333; text-align: center; margin-bottom: 20px;">Resi</h1>
            <p style="font-size: 16px; color: #555; margin: 10px 0;">Ekspedisi: <strong style="color: black;"><?php echo $resi['courier']; ?></strong></p>
            <p style="font-size: 16px; color: #555; margin: 10px 0;">Layanan: <strong style="color: black;"><?php echo $resi['service']; ?></strong></p>
            <p style="font-size: 16px; color: #555; margin: 10px 0;">Resi: <strong style="color: black;"><?php echo $resi['tracking']; ?></strong></p>
            <a id="view-history-btn">Lacak pengiriman</a>
        </div>
    
        <div id="airway-bill-tracking-modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Lacak Pengiriman</h2>
                <div class="loading-spinner" style="display: none;"></div>
                <div class="history-timeline"></div>
            </div>
        </div>
        <?php
    }
    
    function komship_pickup_metabox_html($post) {
        // Get the order_no from the post meta (make sure this meta key exists)
        $komship_order = get_post_meta($post->ID, 'komship_order', true);
        $has_request_pickup = get_post_meta($post->ID, 'has_request_pickup' ,true);
        $order_no = '';
        
        if ($komship_order) {
            $data = unserialize($komship_order);
            $order_no = $data['order_no'];
        }else{ ?>
            <h3>Anda belum mengatur pengiriman</h3>
        <?php return;
       }
        
        if($has_request_pickup){ ?>
            <h3>Permintaan Pickup pesanan ini sudah dikirimkan</h1>
        <?php 
            return;
        }
    
        
    
        ?>
        <style>
            .komship-pickup-form {
                background-color: #ffffff;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 10px 20px;
            }
    
            .komship-pickup-form label {
                font-weight: bold;
                color: #333;
                display: flex;
                align-items: center;
            }
    
            .komship-pickup-form input,
            .komship-pickup-form select {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
                background-color: #f9f9f9;
            }
    
            .komship-pickup-form button {
                grid-column: span 2;
                background-color: #ff8c00;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                font-size: 16px;
                cursor: pointer;
                text-align: center;
            }
    
            .komship-pickup-form button:hover {
                background-color: #e67e00;
            }
        </style>
        <h3>Request Pickup</h3>
        <div class="komship-pickup-form">
            <label for="pickup_date">Tanggal Pickup:</label>
            <input type="date" id="pickup_date" name="pickup_date" value="" required />
    
            <label for="pickup_time">Waktu Pickup:</label>
            <input type="time"  id="pickup_time" name="pickup_time" value="" required />
    
            <label for="pickup_vehicle">Kendaraan Pickup:</label>
            <select name="pickup_vehicle" id="pickup_vehicle" required>
                <option value="Truck">Truck</option>
                <option value="Motor">Motor</option>
                <option value="Mobil">Mobil</option>
            </select>
    
            <label for="order_no">Order No:</label>
            <input type="text" id="order_no" name="order_no" value="<?php echo esc_attr($order_no); ?>" readonly />
    
            <button type="button" id="send_pickup_request" class="button button-primary">Kirim Permintaan Pickup</button>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                function addSeconds(timeStr) {
                  return timeStr + ":00";
                }

                $('#send_pickup_request').on('click', function (e) {
                    e.preventDefault();
            
                    // Collect form data
                    const pickupDate = $('#pickup_date').val();
                    const pickupTime = $('#pickup_time').val();
                    const pickupVehicle = $('#pickup_vehicle').val();
                    const orderNo = $('#order_no').val();
                    const postId = Number(<?php echo $post->ID ?>) // WordPress global for current post ID
            
                    // Validate required fields
                    if (!pickupDate || !pickupTime || !pickupVehicle || !orderNo) {
                        alert('Please complete all fields before sending the request.');
                        return;
                    }
            
                    // Prepare AJAX request
                    $.ajax({
                        url: komshipData.ajax_url, // WordPress predefined AJAX handler
                        method: 'POST',
                        data: {
                            action: 'komship_request_pickup',
                            nonce: komshipData.nonce, // Ensure this is localized in your script
                            pickup_date: pickupDate,
                            pickup_time: pickupTime,
                            pickup_vehicle: pickupVehicle,
                            order_no: orderNo,
                            post_id: postId
                        },
                        beforeSend: function () {
                            $('#send_pickup_request').text('Sending...').prop('disabled', true);
                        },
                        success: function (response) {
                            console.log(`INI RESPONSE ${JSON.stringify(response)}`);
                            if (response.success ) {
                                 alert('Berhasil mengatur pengiriman');
                            } else if(response.data.message) {
                                 alert(response.data.message);
                            } else {
                                 alert('Ekspedisi tersebut tidak dapat melakukan pickup');
                            }
                            //  $('#send_pickup_request').text('Kirim Permintaan Pickup').prop('disabled', false);
                        },
                        error: function (xhr, status, error) {
                            alert('An error occurred: ' + error);
                        },
                        complete: function () {
                            $('#send_pickup_request').text('Kirim Permintaan Pickup').prop('disabled', false);
                        }
                    });
                });
            });

        </script>
        <?php
    }


    public function generateDummyAWB($prefix = 'AWB') {
        
        $randomNumber = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        
        $checkDigit = array_sum(str_split($randomNumber)) % 10;
        
        $awbNumber = $prefix . $randomNumber . $checkDigit;
    
        return $awbNumber;
    }
    
    public function getSubtotalOrders($order){
        
        return $order->get_subtotal();
    }
    

    public function render_komship_order_detail($post, $komship_order) { ?>
        <div id="order-details" class="order-details" style="font-family: 'Inter', system-ui, sans-serif; line-height: 1.5; max-width: 1000px; margin: 0 auto; padding: 10px; background: #fff; color: #333;">
            <h4 style="font-size: 20px; margin: 0 0 24px; color: #FF6B35; text-align: center; font-weight: 600;">Detail Pengiriman</h4>
    
            <!-- Main Info Card -->
            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">No. Order</span>
                            <strong style="font-size: 15px;" data-field="order_no"><?php echo esc_html($komship_order['order_no']); ?></strong>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Status</span>
                            <span style="background: #FFF0EB; color: #FF6B35; padding: 4px 12px; border-radius: 20px; font-size: 14px; display: inline-block;" data-field="order_status"><?php echo esc_html($komship_order['order_status']); ?></span>
                        </div>
                        <div>
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Tanggal</span>
                            <strong style="font-size: 15px;" data-field="order_date"><?php echo esc_html($komship_order['order_date']); ?></strong>
                        </div>
                    </div>
                    
                    <div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Nama Ekspedisi</span>
                            <strong style="font-size: 15px;" data-field="shipping"><?php echo esc_html($komship_order['shipping']); ?></strong>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Metode Pembayaran</span>
                            <strong style="font-size: 15px;" data-field="payment_method"><?php echo esc_html($komship_order['payment_method']); ?></strong>
                        </div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">No. Resi</span>
                            <strong style="font-size: 15px;" data-field="awb"><?php echo esc_html($komship_order['awb'] ?: '-'); ?></strong>
                        </div>
                            <a href="#" id="komship_send_resi" class="button" style="display:none;" style="background: darkorange;">Kirim Resi</a>
                            <a href="#" id="komship_create_label" class="button" style="display:none;" style="background: darkorange;">Unduh Label</a>
                            <p class="resi_result"></p>
                    </div>
                    <div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Tipe Ekspedisi</span>
                            <strong style="font-size: 15px;" data-field="shipping_type"><?php echo esc_html($komship_order['shipping_type']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Shipping Details -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
                <!-- Sender Info -->
                <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <h5 style="margin: 0 0 16px; font-size: 16px; color: #FF6B35;">Informasi Pengirim</h5>
                    <div style="margin-bottom: 12px;">
                        <strong style="font-size: 15px; display: block;" data-field="shipper_name"><?php echo esc_html($komship_order['shipper_name']); ?></strong>
                        <span style="font-size: 14px; color: #666;" data-field="shipper_phone"><?php echo esc_html($komship_order['shipper_phone']); ?></span>
                    </div>
                    <p style="margin: 0; font-size: 14px; color: #444; line-height: 1.5;" data-field="shipper_address"><?php echo esc_html($komship_order['shipper_address']); ?></p>
                </div>
    
                <!-- Receiver Info -->
                <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                    <h5 style="margin: 0 0 16px; font-size: 16px; color: #FF6B35;">Informasi Penerima</h5>
                    <div style="margin-bottom: 12px;">
                        <strong style="font-size: 15px; display: block;" data-field="receiver_name"><?php echo esc_html($komship_order['receiver_name']); ?></strong>
                        <span style="font-size: 14px; color: #666;" data-field="receiver_phone"><?php echo esc_html($komship_order['receiver_phone']); ?></span>
                    </div>
                    <p style="margin: 0; font-size: 14px; color: #444; line-height: 1.5;" data-field="receiver_address"><?php echo esc_html($komship_order['receiver_address']); ?></p>
                </div>
            </div>
    
            <!-- Cost Summary -->
            <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                <h5 style="margin: 0 0 16px; font-size: 16px; color: #FF6B35;">Ringkasan Biaya</h5>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Biaya Pengiriman</span>
                        <strong style="font-size: 15px;" data-field="shipping_cost">Rp <?php echo number_format($komship_order['shipping_cost'], 0, ',', '.'); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Cashback Pengiriman</span>
                        <strong style="font-size: 15px; color: #4CAF50;" data-field="shipping_cashback">Rp <?php echo number_format($komship_order['shipping_cashback'], 0, ',', '.'); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Biaya Tambahan</span>
                        <strong style="font-size: 15px;" data-field="additional_cost">Rp <?php echo number_format($komship_order['additional_cost'], 0, ',', '.'); ?></strong>
                    </div>
                     <div>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Biaya Layanan</span>
                        <strong style="font-size: 16px; color: #FF6B35;" data-field="service_fee">Rp <?php echo number_format($komship_order['service_fee'], 0, ',', '.'); ?></strong>
                    </div>
                    <div>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 4px;">Total Biaya Keseluruhan</span>
                        <strong style="font-size: 16px; color: #FF6B35;" data-field="grand_total">Rp <?php echo number_format($komship_order['grand_total'], 0, ',', '.'); ?></strong>
                    </div>
                   
                </div>
            </div>
    
            <!-- Order Items -->
            <div id="order-items" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                <?php foreach ($komship_order['order_details'] as $item): ?>
                    <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                        <h5 style="margin: 0 0 12px; font-size: 15px; font-weight: 600;"><?php echo esc_html($item['product_name']); ?></h5>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Varian</span>
                                <strong><?php echo esc_html($item['product_variant_name']); ?></strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Berat</span>
                                <strong><?php echo esc_html($item['product_weight']); ?> kg</strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Dimensi</span>
                                <strong><?php echo esc_html($item['product_length']); ?>x<?php echo esc_html($item['product_width']); ?>x<?php echo esc_html($item['product_height']); ?> cm</strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Kuantitas</span>
                                <strong><?php echo esc_html($item['qty']); ?></strong>
                            </div>
                        </div>
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #eee;">
                            <span style="color: #666; font-size: 13px;">Subtotal</span>
                            <strong style="display: block; color: #FF6B35; font-size: 15px;">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:20px; text-align: right;">
                <a href="" id="cancel_order" class="button" style="background:red;">Batalkan Pengiriman</a>
            </div>
        </div>
    
        <script>
        jQuery(document).ready(function($) {
            
            $('#cancel_order').on('click', function(e) {
                e.preventDefault();
                 
                if (!confirm("Kamu yakin ingin membatalkan pengiriman?")) {
                    return; 
                }
                const data = {
                    action: 'komship_cancel_order',
                    nonce: komshipData.nonce,
                    post_id: "<?php echo $post->ID?>" ,
                    order_no: "<?php echo $komship_order['order_no'] ?>" 
                };
                
                $.ajax({
                    url: komshipData.ajax_url,
                    type: 'POST',
                    data: data,
                    success: function(response){
                        if (response.success){
                        
                            alert('Pengiriman berhasil dibatalkan');
                            // refresh
                            location.reload();
                        } else {
                            alert(JSON.stringify(response));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', error);
                    }
                });
            });
            function downloadBase64AsFile(base64String, fileName) {
                // Create a Blob from the base64 string
                const byteCharacters = atob(base64String);
                const byteNumbers = new Array(byteCharacters.length).fill(0).map((_, i) => byteCharacters.charCodeAt(i));
                const byteArray = new Uint8Array(byteNumbers);
                
                // Create a Blob object
                const blob = new Blob([byteArray], { type: 'application/pdf' });
                
                // Create a temporary link element
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob); // Create an object URL for the Blob
                link.download = fileName; // Specify the file name
                link.click(); // Trigger the download by simulating a click event
            }
            function updateOrderDetails() {
                const data = {
                    nonce: komshipData.nonce,
                    action: 'komship_order_detail',
                    order_no: "<?php echo $komship_order['order_no'] ?>"
                };
    
                $.ajax({
                    url: komshipData.ajax_url,
                    type: 'GET',
                    data: data,
                    success: function(response) {
    
                        if (response.success) {
                            // Update simple text fields
                            const textFields = [
                                'order_no', 'order_status', 'order_date', 'shipping',
                                'payment_method', 'awb', 'shipper_name', 'shipper_phone',
                                'shipper_address', 'receiver_name', 'receiver_phone',
                                'receiver_address', 'shipping_type'
                            ];
    
                            textFields.forEach(field => {
                                if (response.data[field]) {
                                    $(`[data-field="${field}"]`).text(response.data[field]);
                                }
                            });
                            if (response.data['awb'] && response.data['awb'].trim() !== '') {
                                $('#komship_send_resi').show(); 
                                $('#komship_create_label').show();
                            } else {
                                $('#komship_send_resi').hide(); 
                                $('#komship_create_label').hide();
                            }
                                
                            // Update monetary fields
                            const moneyFields = [
                                'shipping_cost', 'shipping_cashback',
                                'additional_cost', 'grand_total', 'service_fee'
                            ];
    
                            moneyFields.forEach(field => {
                                if (response.data[field]) {
                                    $(`[data-field="${field}"]`).text('Rp ' + formatNumber(response.data[field]));
                                }
                            });
    
                            // Update order items
                            if (response.data.order_details && response.data.order_details.length) {
                                const itemsContainer = $('#order-items');
                                itemsContainer.empty();
                                
                                response.data.order_details.forEach(item => {
                                    const itemHtml = generateOrderItemHtml(item);
                                    itemsContainer.append(itemHtml);
                                });
                            }
                            $('#komship_send_resi').on('click', function(e){
                                e.preventDefault();
                                
                                $(this).attr('disabled', true);
                                
                                let order_id = Number(<?php echo $post->ID?>);
                                let courier = response.data['shipping'];
                                let service = response.data['shipping_type'];
                                let tracking = response.data['awb'];
                                
                                $.ajax({
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    type: 'post',
                                    data: {
                                        action: 'resi_send_tracking_number',
                                        order_id: order_id,
                                        courier: courier,
                                        service: service,
                                        tracking: tracking
                                    },
                                    context: this,
                                    success: function(res) {
                                        $(this).attr('disabled', false);
                                        $('.resi_result').html('Done!, Refresh halaman ini');
                                    },
                                    error: function(err) {
                                        console.log(err);
                                        $(this).attr('disabled', false);
                                        
                                    }
                                });
                            });
                            
                            $('#komship_create_label').on('click', function(e){
                                e.preventDefault();
                                
                                $(this).attr('disabled', true);
                                
                                let order_no = '<?php echo $komship_order['order_no'];?>'; 
                                
                                $.ajax({
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    type: 'post',
                                    data: {
                                        action: 'komship_create_label',
                                        nonce : komshipData.nonce,
                                        order_no : order_no
                                    },
                                    context: this,
                                    success: function(res) {
                                        $(this).attr('disabled', false);
                                        const base64 = res.data.base_64;
                                        const path = res.data.path
                                        downloadBase64AsFile(base64, path.split('/').pop());
                                        // alert(JSON.stringify(res));
                                    },
                                    error: function(err) {
                                        console.log(err);
                                        $(this).attr('disabled', false);
                                        
                                    }
                                });
                            });
                            console.log('Update completed'); 
                        } else {
                            console.error('Error:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', error);
                    }
                });
            }
    
            function generateOrderItemHtml(item) {
                return `
                    <div style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                        <h5 style="margin: 0 0 12px; font-size: 15px; font-weight: 600;">${item.product_name}</h5>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 14px;">
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Varian</span>
                                <strong>${item.product_variant_name}</strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Berat</span>
                                <strong>${item.product_weight} g</strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Dimensi</span>
                                <strong>${item.product_length}x${item.product_width}x${item.product_height} cm</strong>
                            </div>
                            <div>
                                <span style="color: #666; display: block; margin-bottom: 4px;">Kuantitas</span>
                                <strong>${item.qty}</strong>
                            </div>
                        </div>
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #eee;">
                            <span style="color: #666; font-size: 13px;">Subtotal</span>
                            <strong style="display: block; color: #FF6B35; font-size: 15px;">Rp ${formatNumber(item.subtotal)}</strong>
                        </div>
                    </div>
                `;
            }
    
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
    
            // Initial update
            updateOrderDetails();
    
            // Refresh every 30 seconds
            setInterval(updateOrderDetails, 30000);
        });
        </script>
    <?php }
    
    public function render_komship_expedition_meta_box($post) {
        error_log("KOMSHIP METABOX -> render_komship_expedition_meta_box");
        $order = '';
        if ($post instanceof WC_Order) {
            $order = $post;
        } else {
            if ('shop_order' === get_post_type($post->ID)) {
                $order = wc_get_order($post->ID);
            } else {
                return;
            }
        }
    
        
        // Now, $order is guaranteed to be a WC_Order object
        if (!$order) {
            return; // Exit if we could not get a valid order
        }
        $customer_name = $order->get_shipping_first_name() ? $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

        $customer_address = $order->has_shipping_address() 
            ? esc_html(preg_replace('/\s+/', ' ', trim($order->get_shipping_address_1() . ', ' . 
                ($order->get_shipping_address_2() ? $order->get_shipping_address_2() . ', ' : '') . 
                $order->get_shipping_city() . ', ' . 
                $order->get_shipping_postcode())))
            : esc_html(preg_replace('/\s+/', ' ', trim($order->get_billing_address_1() . ', ' . 
                ($order->get_billing_address_2() ? $order->get_billing_address_2() . ', ' : '') . 
                $order->get_billing_city() . ', ' . 
                $order->get_billing_postcode())));

        $customer_email = $order->get_billing_email(); 
        
        $customer_phone = $order->get_billing_phone(); 

        
        $komship_order = get_post_meta($order->get_id(), 'komship_order', true);
        if(!empty($komship_order)){ 
            $unserialized = unserialize($komship_order);
            echo $this->render_komship_order_detail($post, $unserialized);
        ?>
            
            <!--<section class="komship-order-info">-->
            <!--    <h5>Pengiriman sudah diatur</h5>-->
            <!--    <div class="order-details">-->
            <!--        <p><strong>Order ID:</strong> <?php echo esc_html($unserialized['order_id']); ?></p>-->
            <!--        <p><strong>Order No:</strong> <?php echo esc_html($unserialized['order_no']); ?></p>-->
            <!--    </div>-->
            <!--</section>-->
            
        <?php    
            return;
        }
        
        $itemSubtotal = $this->getSubtotalOrders($order);
                                    
        $weight_gram = $this->calculate_order_weight($order);
        $weight = $weight_gram == 0.1 || $weight_gram < 1000 ? 1 : $weight_gram / 1000;
        $total = $order->get_total();
        $seller = dokan_get_seller_id_by_order( $order->get_id() );
        error_log("DOKAN GET SELLER: " . print_r($seller, true));
        
        $seller_name = '';
        $seller_email = '';
        $seller_address = '';
        $seller_phone = '';
        $seller_postcode = '';
        
        if ( $seller ) {
            $vendor = dokan()->vendor->get( $seller ); 
            $seller_info = get_userdata( $seller );
        error_log("SELLER INFO");
        error_log(print_r($vendor,true));
            if ( ! $vendor ) {
                error_log( "DOKAN SELLER: Vendor info not found." );
            } else {
                $store_info = dokan_get_store_info( $seller_info->ID );
        
                if ( $store_info ) {
                    if ( ! empty( $store_info['store_name'] ) ) {
                        $seller_name = $store_info['store_name'];
                    } else {
                        $seller_name = $seller_info->first_name . ' ' . $seller_info->last_name;
                    }
                } else {
                    error_log( "DOKAN SELLER: Store info not set." );
                    $seller_name = $seller_info->first_name . ' ' . $seller_info->last_name;
                }
        
                $first_name = $vendor->get_first_name();    
                $last_name = $vendor->get_last_name();      
                $address = $vendor->get_address();          
                $location = $vendor->get_location();        
                $seller_phone = $vendor->get_phone();              
                $seller_email = $vendor->get_email();              
        
                if ( empty( $seller_name ) ) {
                    $seller_name = $first_name . ' ' . $last_name; 
                }
        
                
                if ( empty( $seller_phone ) ) {
                    $seller_phone = get_user_meta( $seller, 'billing_phone', true); 
                }
                
                
                $seller_phone = str_replace(' ', '', $seller_phone);
                
                // Format the phone number if it starts with '08' (convert to '62')
                if ( ! empty( $seller_phone ) && preg_match( '/^(?:\+62|08)(\d{7,14})$/', $seller_phone ) ) {
                    $seller_phone = '62' . substr( $seller_phone, 1 );  // Replace '08' with '62'
                }
                
                $customer_phone = str_replace(' ', '', $customer_phone);
                
                // Format the customer's phone number if it starts with '08' or '+62' (convert to '62')
                if ( ! empty( $customer_phone ) && preg_match('/^(?:\+62|08)(\d{7,14})$/', $customer_phone)) {
                    // If it starts with '08', replace it with '62', or if it starts with '+62', just strip the '+' and keep the number
                    if (substr($customer_phone, 0, 3) === '+62') {
                        // Remove the leading '+' and keep the rest
                        $customer_phone = '62' . substr($customer_phone, 3);
                    } elseif (substr($customer_phone, 0, 2) === '08') {
                        // Replace '08' with '62'
                        $customer_phone = '62' . substr($customer_phone, 1);
                    }
                }
        
                
                $seller_address = ! empty( $address ) 
                    ? $address['street_1'] . ' ' . $address['street_2'] . ' ' . $address['city'] . ' ' . $address['postcode'] 
                    : get_user_meta( $seller, 'billing_address_1', true) . ' ' . get_user_meta( $seller, 'billing_city', true) . ' ' . get_user_meta( $seller, 'billing_postcode', true); 
                $seller_postcode = ! empty($address) ? [
                        'address_label' => $address['address_label'],
                        'address_id' => $address['address_id'],
                        'postcode' => $address['postcode']
                    ] : [];
                    
                            error_log("SELLER Address: " . print_r($seller_address, true));
            }
        } else {
            error_log( "DOKAN SELLER: Seller ID not found for order " . $order->get_id() );
        }

        ?>
        <style>
    /* Global Styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    /* Loading Overlay */
    #loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: all;
    }

    .loading-message {
        font-size: 18px;
        color: #ff6600;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loading-message::before {
        content: "⏳"; /* Loading Icon */
        margin-right: 8px;
    }

    .orange-text {
        color: #ff6600;
        font-weight: 600;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 15px;
        font-size: 14px;
        border: 2px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        transition: all 0.3s ease-in-out;
    }

    input[type="number"], select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #ff6600;
        box-shadow: 0 0 8px rgba(255, 102, 0, 0.2);
    }

    .wc-enhanced-select {
        padding: 10px 15px;
        border-radius: 5px;
    }


    /* Section Styles */
    .order_form, .order-details {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    .order_form hr {
        margin-top: 20px;
        margin-bottom: 20px;
        border: 0;
        border-top: 1px solid #eee;
    }

    .product-item {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .product-item strong {
        font-size: 16px;
    }

    .product-item span {
        font-size: 14px;
        color: #555;
    }

    /* Orange Theme for Highlighting */
    .highlight {
        color: #ff6600;
        font-weight: 600;
    }

</style>
        <div style="font-family: Arial, sans-serif;">
        <div id="loading-overlay" style="display: none;">
            <div class="loading-message">Mencari Ekspedisi....</div>
        </div>
        <h3 class="orange-text" style="margin-top: 0;">Pencarian Ekspedisi</h3>
        <p><strong>Isi formulir berikut untuk mendapatkan pilihan ekspedisi</strong></p>

        <!-- Sender and Receiver Address Selection -->
        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1; min-width: 250px;">
                <label for="shipper_select">Alamat Pengirim:</label>
                <select id="shipper_select" name="shipper_id" class="wc-enhanced-select" style="width: 100%;" data-placeholder="Cari menggunakan kode pos, kecamatan, kabupaten dan lainnya.">
                    <option value=""></option>
                </select>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label for="receiver_select">Alamat Penerima:</label>
                <select id="receiver_select" name="receiver_id"  style="width: 100%;" data-placeholder="Cari menggunakan kode pos, kecamatan, kabupaten dan lainnya.">
                    <option value=""></option>
                </select>
            </div>
            
        </div>

        <!-- Package Weight and Total Value Fields -->
        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
            <div style="flex: 1; min-width: 250px;">
                <label for="package_weight">Berat Paket (kg):</label>
                <input type="number" step="0.01" id="package_weight" name="package_weight" value="<?php echo esc_attr($weight); ?>" style="width: 100%;" placeholder="Cth., 1.5">
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label for="item_value">Total Harga (Rp):</label>
                <input type="number" step="0.01" id="item_value" name="item_value" value="<?php echo esc_attr($total); ?>" style="width: 100%;" readonly placeholder="Total Pesanan">
            </div>
        </div>
        <div class="form-group" style="margin-top: 20px;">
            <button type="button" class="button button-primary" id="calculate_shipping_button">Cari Ekspedisi</button>
        </div>
        

        <!-- Order Form for Storing Expedition Details -->
        <div id="order-store-fields" style="margin-top: 100px; display: none;">
            <hr>
            <h4>Formulir Pengiriman Pesanan</h4>
            <p>Isi informasi yang dibutuhkan untuk mengatur pengiriman pesanan.</p>

            <form class="order_form" id="komship_store_order">
                <!-- Order Date -->
                <div class="form-group">
                    <label for="order_date_komship">Tanggal Pesanan</label>
                    <input type="datetime-local" id="order_date_komship" name="order_date_komship" class="form-control" required>
                </div>

                <!-- Shipper Information -->
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="brand_name">Nama Brand</label>
                        <input type="text" id="brand_name" name="brand_name" class="form-control" value="<?php echo esc_html($seller_name) ?>" required  placeholder="Masukkan nama brand">
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipper_name">Nama Pengirim</label>
                        <input type="text" id="shipper_name" name="shipper_name" class="form-control" value="<?php echo esc_html($seller_name) ?>" required placeholder="Masukkan nama pengirim">
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipper_phone">Nomor Telepon Pengirim</label>
                        <!-- pattern="^(62|8)(\d{8,12})$"-->
                        <input type="tel" id="shipper_phone" name="shipper_phone" class="form-control" value="<?php echo esc_html($seller_phone) ?>"  placeholder="628xxx atau 821xxx">
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipper_address">Alamat Pengirim</label>
                        <textarea id="shipper_address" name="shipper_address" class="form-control" required placeholder="Jl. Street No.42 etc.."><?php echo esc_html($seller_address) ?></textarea>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipper_email">Email Pengirim</label>
                        <input type="email" id="shipper_email" name="shipper_email" class="form-control" value="<?php echo esc_html($seller_email) ?>"  placeholder="soleh@gmail.com" required>
                    </div>
                </div>

                <!-- Receiver Information -->
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="receiver_name">Nama Penerima</label>
                        <input type="text" id="receiver_name" name="receiver_name" class="form-control" value="<?php echo esc_html($customer_name)?>" required>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="receiver_phone">Nomor Telepon Penerima</label>
                        <input type="tel" id="receiver_phone" name="receiver_phone" class="form-control" value="<?php echo esc_html( $customer_phone )?>"  placeholder="628xxx atau 821xxx" pattern="^(62|8)(\d{8,12})$" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="receiver_address">Alamat Penerima</label>
                    <textarea id="receiver_address" name="receiver_address" class="form-control" required placeholder="Jl. Street No.42 etc.."><?php echo esc_html(preg_replace('/\s+/', ' ', $order->get_shipping_address_1())) . ', ' . esc_html($order->get_shipping_city()) . ' ' . esc_html($order->get_shipping_postcode()); ?></textarea>
                </div>

                <!-- Expedition Search and Select Dropdown -->
                <div id="shipping-results" class="form-group">
                    <label for="expedition_select">Pilih Ekspedisi:</label>
                        <p><strong>Catatan:</strong> Lakukan pencarian ekspedisi terlebih dahulu agar pilihan ekspedisi muncul</p>
                    <select id="expedition_select" name="expedition_id" class="wc-enhanced-select" style="width: 100%;" data-placeholder="Pilih ekspedisi">
                        <option value=""></option>
                    </select>
                </div>
                <!--Insurance Value-->
                <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                    <div class="form-group" style="flex: 1; display: flex; justify-content: flex-start; align-items: center; flex-direction: row">
                        <input type="checkbox" id="use_insurance" name="use_insurance" style="margin-right: 5px;">
                        <label for="use_insurance" style="flex-shrink: 0; margin-right: 5px;">Gunakan Asuransi</label>
                    </div>
                
                    <div class="form-group" style="flex: 2; min-width: 250px; display: none;" id="insurance_amount_field">
                        <label for="insurance_amount">Jumlah Asuransi</label>
                        <input readonly type="number" id="insurance_amount" name="insurance_amount" class="form-control" value="0">
                    </div>
                </div>

                

                <!-- Shipping Details and Payment -->
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipping_type">Tipe Pengiriman</label>
                        <input readonly type="text" id="shipping_type" name="shipping_type" class="form-control" value="-">
                    </div>
                <?php 
                    $current_payment_method = $order->get_payment_method();
                ?>
                     <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" class="form-control" >
                            <option value="BANK TRANSFER" <?php echo ($current_payment_method !== 'cod') ? 'selected' : ''; ?>>BANK TRANSFER</option>
                            <option value="COD" <?php echo ($current_payment_method === 'cod') ? 'selected' : ''; ?>>COD</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipping_cost">Biaya Pengiriman</label>
                        <input readonly type="number" id="shipping_cost" name="shipping_cost" class="form-control" value="0" required>
                    </div>

                    <div class="form-group" style="flex: 1; min-width: 250px;">
                        <label for="shipping_cashback">Cashback Pengiriman</label>
                        <input readonly type="number" id="shipping_cashback" name="shipping_cashback" class="form-control" value="0" >
                    </div>
                </div>

               <!-- Order Details (Product) -->
                <h5 class="orange-text">Rincian Pesanan</h5>
                <div id="order_details" style="display: flex; flex-direction: column; gap: 10px;">
                    <?php 
                    // Loop through the order items (products)
                    $items = $order->get_items(); 
                    if ($items) :
                        foreach ($items as $item_id => $item) :
                            // Get product details
                            $product = $item->get_product();
                            $product_name = $item->get_name(); 
                            $product_variant_name = $item->get_meta('product_variant_name'); 
                            $product_weight = !empty($product->get_weight()) ? $product->get_weight() : 0;
                            $product_width = !empty($product->get_width()) ? $product->get_width() : 0;
                            $product_height = !empty($product->get_height()) ? $product->get_height() : 0;
                            $product_length = !empty($product->get_length()) ? $product->get_length() : 0;

                            $product_qty = $item->get_quantity(); // Quantity
                            $product_subtotal = $item->get_subtotal(); // Subtotal
                            $product_price = $product_qty > 0 ? $product_subtotal / $product_qty : 0; 
                
                            ?>
                            <div class="product-item" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;" 
                                 data-product-name="<?php echo esc_attr($product_name); ?>"
                                 data-product-variant="<?php echo esc_attr($product_variant_name); ?>"
                                 data-product-weight="<?php echo esc_attr($product_weight); ?>"
                                 data-product-height="<?php echo esc_attr($product_height); ?>"
                                 data-product-width="<?php echo esc_attr($product_width); ?>"
                                 data-product-length="<?php echo esc_attr($product_length); ?>"
                                 data-product-price="<?php echo esc_attr($product_price); ?>"
                                 data-product-quantity="<?php echo esc_attr($product_qty); ?>"
                                 data-product-subtotal="<?php echo esc_attr($product_subtotal); ?>">
                                 
                                <div style="display: flex; justify-content: space-between;">
                                    <strong><?php echo esc_html($product_name); ?></strong> 
                                    <span><?php echo esc_html($product_variant_name); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-top: 5px;">
                                    <span>Qty: <?php echo esc_html($product_qty); ?></span>
                                    <span>Berat: <?php echo esc_html($product_weight); ?> gram</span>
                                    <span>Panjang: <?php echo esc_html($product_length); ?> cm</span>
                                    <span>Lebar: <?php echo esc_html($product_width); ?> cm</span>
                                    <span>Tinggi: <?php echo esc_html($product_height); ?> cm</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #666;">
                                    <span>Harga: Rp <?php echo number_format($product_price, 0, ',', '.'); ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #666; margin-top: 5px;">
                                    <span>Subtotal: Rp <?php echo number_format($product_subtotal, 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No products found in the order.</p>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="button button-primary" id="submit_komship_order">Atur Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
    
        <script>
            jQuery(document).ready(function($) {
                <?php
                    if(dokan_is_user_seller( get_current_user_id() )){ ?>
                        $('#shipping_cashback').attr('hidden', true);
                    <?php }
                ?>
                // Select the parent container
                const orderStoreFields = $("#order-store-fields");
                // Select all form fields inside the container
                const formFields = orderStoreFields.find("input, select, textarea");
            
                // Function to toggle the required attribute
                function toggleRequired() {
                    if (orderStoreFields.is(":visible")) {
                        formFields.attr("required", "required");
                    } else {
                        formFields.removeAttr("required");
                    }
                }
            
                // Check on page load
                toggleRequired();
            
                // Watch for visibility changes dynamically
                const observer = new MutationObserver(function () {
                    toggleRequired();
                });
            
                // Observe changes in the style attribute of #order-store-fields
                if (orderStoreFields.length) {
                    observer.observe(orderStoreFields[0], { attributes: true, attributeFilter: ["style"] });
                }
                // var currentDateTime = new Date().toISOString().split('T')[0];
                // var currentDateTime = new Date().toISOString().slice(0, 16);
                function getLocalISODateTime() {
                    const now = new Date();
                    const offsetMs = now.getTimezoneOffset() * 60 * 1000;
                    const localDate = new Date(now.getTime() - offsetMs);
                    return localDate.toISOString().slice(0, 16);
                }
                 $('#order_date_komship').val(getLocalISODateTime());
                function formatDateTime(date) {
                    // Jika tidak ada parameter, gunakan tanggal saat ini
                    var now = date ? new Date(date) : new Date();
                
                    // Format setiap bagian tanggal dan waktu
                    var year = now.getFullYear();
                    var month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
                    var day = String(now.getDate()).padStart(2, '0');
                    var hours = String(now.getHours()).padStart(2, '0');
                    var minutes = String(now.getMinutes()).padStart(2, '0');
                    // var seconds = String(now.getSeconds()).padStart(2, '0');
                
                    // Gabungkan menjadi format "YYYY-MM-DD HH:mm:ss"
                    return `${year}-${month}-${day} ${hours}:${minutes}`;
                }
               
                
                if (typeof $.fn.selectWoo !== 'undefined') {
                    function showLoading() {
                        $('#loading-overlay').show();
                    }
            
                    function hideLoading() {
                        $('#loading-overlay').hide();
                    }
                    
                    function calculateInsurance(agent, grandTotal, totalProductPrice) {
                        let insurancePrice = 0;
                        console.log(`TOTAL PRODUCT PRICE : ${totalProductPrice}`);
                        switch(agent) {
                            case "JNE":
                                insurancePrice = (0.002 * totalProductPrice) + 5000;
                                break;
                            case "SICEPAT":
                                if (grandTotal > 500000) {
                                    insurancePrice = 0.003 * grandTotal;
                                }
                                break;
                            case "IDEXPRESS":
                                insurancePrice = 0.002 * totalProductPrice;
                                break;
                            case "SAP":
                                insurancePrice = (0.003 * totalProductPrice) + 2000; // AWB fee
                                break;
                            case "NINJA":
                                if (totalProductPrice <= 1000000) {
                                    insurancePrice = 2500;
                                } else {
                                    insurancePrice = 0.0025 * totalProductPrice;
                                }
                                break;
                            case "JNT":
                                insurancePrice = 0.002 * totalProductPrice;
                                break;
                            default:
                                insurancePrice = 0;
                        }
                        console.log(`INSURANCE PRICE: ${insurancePrice}`);
                        return Math.round(insurancePrice);
                    }
                    const insuranceStatus = <?php echo json_encode($order->get_meta('_billing_insurance')); ?>; // Replace $order_id with actual order ID
                        if (insuranceStatus === 1 || insuranceStatus === "1") {
                            $('#use_insurance').prop('checked', true); // Check the checkbox
                            $('#insurance_amount_field').show(); // Show the insurance field
                        }
                    // Automatically search expeditions when shipper and receiver are selected
                    // $('#shipper_select, #receiver_select, #payment_method').on('change', function() {
                    //     if ($('#shipper_select').val() && $('#receiver_select').val()) {
                            
                    //         const data = {
                    //             action: 'komship_calculate_shipping',
                    //             nonce: komshipData.nonce,
                    //             shipper_id: $('#shipper_select').val(),
                    //             receiver_id: $('#receiver_select').val(),
                    //             weight: $('#package_weight').val(),
                    //             item_value: $('#item_value').val(),
                    //             cod: $('#payment_method').val() === "BANK TRANSFER" ? "no" : "yes",
                    //         };
                    //         showLoading();
                    //         $.post(komshipData.ajax_url, data, function(response) {
                    //             if (response.success === true) {
                    //                 $('#order-store-fields').show();
                    //                 let expeditionOptions = '<option value=""></option>';
                            
                    //                 let shippingOptions = [...response.data.calculate_reguler, ...response.data.calculate_cargo];
                            
                    //                 if (shippingOptions.length > 0) {
                    //                     shippingOptions.forEach(function(shipping) {
                    //                         const shippingObjectString = JSON.stringify(shipping);
                    //                         expeditionOptions += `<option value='${shippingObjectString}'>${shipping.shipping_name} - ${shipping.service_name} - Rp.${shipping.shipping_cost} ${shipping.is_cod ? "(Bisa COD)" : ""}</option>`;
                    //                     });
                    //                 } else {
                    //                     expeditionOptions += '<option value="">No shipping options available</option>';
                    //                 }
                            
                    //                 $('#expedition_select').html(expeditionOptions);
                    //                 hideLoading();
                    //             }
                    //         });

                    //     }
                    // });
                    // Initialize a flag to control automatic triggering
                    let autoTriggerEnabled = false;
                    
                    // Button click to trigger the calculation for the first time
                    $('#calculate_shipping_button').on('click', function() {
                      
                        triggerShippingCalculation();
                        // Enable automatic triggering after the first calculation
                        autoTriggerEnabled = true;
                    });
                    
                    $('#shipper_select, #receiver_select, #payment_method').on('change', function() {
                        if (autoTriggerEnabled) {
                            triggerShippingCalculation();
                        }
                    });
                    
                    // Function to handle the shipping calculation logic
                    function triggerShippingCalculation() {
                       
                        
                        if ($('#shipper_select').val() && $('#receiver_select').val()) {
                            const data = {
                                action: 'komship_calculate_shipping',
                                nonce: komshipData.nonce,
                                shipper_id: $('#shipper_select').val(),
                                receiver_id: $('#receiver_select').val(),
                                weight: $('#package_weight').val(),
                                item_value: $('#item_value').val(),
                                cod: $('#payment_method').val() === "BANK TRANSFER" ? "no" : "yes",
                            };
                            console.log('data' + data);
                            showLoading();
                            $.post(komshipData.ajax_url, data, function(response) {
                                console.log("response " + response);
                                if (response.success === true) {
                                    $('#order-store-fields').show();
                                    let expeditionOptions = '<option value=""></option>';
                    
                                    let shippingOptions = [...response.data.calculate_reguler, ...response.data.calculate_cargo];
                    
                                    if (shippingOptions.length > 0) {
                                        shippingOptions.forEach(function(shipping) {
                                            const shippingObjectString = JSON.stringify(shipping);
                                            expeditionOptions += `<option value='${shippingObjectString}'>${shipping.shipping_name} - ${shipping.service_name} - Rp.${shipping.shipping_cost} ${shipping.is_cod ? "(Bisa COD)" : ""}</option>`;
                                        });
                                    } else {
                                        expeditionOptions += '<option value="">No shipping options available</option>';
                                    }
                    
                                    $('#expedition_select').html(expeditionOptions);
                                    hideLoading();
                                }
                            });
                        }
                    }
                    var shipperAddress = <?php echo json_encode($seller_postcode);?>;
                    console.log(shipperAddress);
                    if (shipperAddress && shipperAddress.address_id && shipperAddress.address_label) {
                
                        var optionHtml = '<option value=\'' + shipperAddress.address_id + '\' ' + 'selected'  + '>' + shipperAddress.address_label + '</option>';
                        
                        $('#shipper_select').append(optionHtml);
            
                        $('#shipper_select').val(shipperAddress.address_id).trigger('change');
                    }

                    $('#shipper_select').selectWoo({
                        ajax: {
                            url: komshipData.ajax_url,
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    action: 'komship_search_destination',
                                    nonce: komshipData.nonce,
                                    keyword: params.term
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.data.destinations.map(function(item) {
                                        return { id: item.id, text: item.label };
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 3,
                        placeholder: 'Search for a shipper',
                        allowClear: true
                    });
                    
                   <?php
                        // Mendapatkan postcode dari pengiriman atau penagihan
                        $postcode_id = $order->get_meta('komship_shipping_id') 
                                       ?: $order->get_meta('komship_billing_id');
                        $postcode = $order->get_meta('komship_shipping_postcode') 
                                    ?: $order->get_meta('komship_billing_postcode');
                        $postcode_label = $order->get_meta('komship_shipping_label') ?: $order->get_meta('komship_billing_label');
                        if ($postcode_id && $postcode && $postcode_label) { ?>
                            var postcodeId = "<?php echo esc_js($postcode_id); ?>"; 
                            var postcode = "<?php echo esc_js($postcode); ?>";
                            var postcodeLabel = "<?php echo esc_js($postcode_label); ?>";
                            $('#receiver_select').empty();
                            $('#receiver_select').append(`<option value="${postcodeId}">${postcodeLabel}</option>`);
                            $('#receiver_select').val(postcodeId).trigger('change');
                            console.log(postcodeId + ' ' + postcode + ' ' + postcodeLabel);
                        <?php } ?>
    
                    $('#receiver_select').selectWoo({
                                ajax: {
                                    url: komshipData.ajax_url,
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            action: 'komship_search_destination',
                                            nonce: komshipData.nonce,
                                            keyword: params.term // Gunakan input term untuk pencarian
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data.data.destinations.map(function(item) {
                                                return { id: item.id, text: item.label };
                                            })
                                        };
                                    },
                                    cache: true
                                },
                                minimumInputLength: 3,
                                placeholder: 'Search for a receiver',
                                allowClear: true
                            });
                    
                    $('#payment_method').on('change', function() {
                        $('#expedition_select').empty();
                        $('#expedition_select').val('');
                        $('#shipping_type').val('-');
                        $('#shipping_cost').val('0');
                        $('#shipping_cashback').val('0');
                        $('#service_fee').val('0');
                        $('#grand_total').val('0');
                        $('#insurance_amount').val('0');  
                        
                    });
                    
                    $('#expedition_select').on('change', function() {
                        // Pre-check insurance based on server-side data
                        

                        var selectedExpedition = $(this).val();  
                    
                        if (selectedExpedition) {
                            try {
                                var expeditionData = JSON.parse(selectedExpedition);  
                    
                                $('#shipping_type').val(expeditionData.service_name || '');
                                $('#shipping_cost').val(expeditionData.shipping_cost || 0);
                                $('#shipping_cashback').val(expeditionData.shipping_cashback || 0);
                                $('#service_fee').val(expeditionData.service_fee || 0);
                                $('#grand_total').val(expeditionData.grandtotal || 0);
                                
                                const isUsingInsurance = $('#use_insurance').is(':checked');
                                
                                if(isUsingInsurance) {
                                    let productsSubtotal = <?php echo $itemSubtotal; ?>;
                                    let insuranceValue = calculateInsurance(expeditionData.shipping_name, expeditionData.grandtotal, productsSubtotal ); 
                                    $('#insurance_amount').val(insuranceValue);
                                }
                                
                            } catch (error) {
                                console.error("Failed to parse the selected expedition data:", error);
                            }
                        } else {
                            console.warn("No expedition data selected.");
                        }
                    });
                    
                    $('#use_insurance').change(function() {
                        if ($(this).is(':checked')) {
                            $('#insurance_amount_field').show();
                            let productsSubtotal = <?php echo $itemSubtotal; ?>;
                            console.log(`PRODUCT SUBTOTAL : ${productsSubtotal}`);
                            let selectedExpedition = $('#expedition_select').val();
                            const expeditionData = JSON.parse(selectedExpedition);  
                            let insuranceValue = calculateInsurance(expeditionData.shipping_name, expeditionData.grandtotal, productsSubtotal ); 
                            $('#insurance_amount').val(insuranceValue);
                        } else {
                            $('#insurance_amount_field').hide();
                            $('#insurance_amount').val('');
                        }
                    });
                    
                    $('#submit_komship_order').on('click', function(e) {
                        
                        e.preventDefault();
                        const expeditionData = JSON.parse($('#expedition_select').val());
                    
                        const orderDate = $('#order_date_komship').val();
                        const brandName = $('#brand_name').val();
                        const shipperName = $('#shipper_name').val();
                        const shipperPhone = $('#shipper_phone').val();
                            
                        let shipperDestinationId = parseInt($('#shipper_select').val(), 10);  
                        if (isNaN(shipperDestinationId)) { 
                            shipperDestinationId = 0;
                        }
                        const shipperAddress = $('#shipper_address').val();
                        const shipperEmail = $('#shipper_email').val();
                        const receiverName = $('#receiver_name').val();
                        const receiverPhone = $('#receiver_phone').val();
                    
                        let receiverDestinationId = parseInt($('#receiver_select').val(), 10);  
                        if (isNaN(receiverDestinationId)) { 
                            receiverDestinationId = 0;
                        }
                        
                        const receiverAddress = $('#receiver_address').val();
                        const shipping = expeditionData.shipping_name
                        const shippingType = expeditionData.service_name || '';  
                        const paymentMethod = $('#payment_method').val();  
                        const shippingCost = expeditionData.shipping_cost || 0;
                        const shippingCashback = expeditionData.shipping_cashback || 0;
                        const serviceFee = expeditionData.service_fee || 0;
                        const additionalCost = 0; 
                        const grandTotal = parseFloat($('#item_value').val()) + shippingCost + additionalCost; 
                        const insuranceValue = $('#incurance_amount').val();  
                        let orderDetails = [];
                        
                        $('.product-item').each(function() {
                            const productName = $(this).data('product-name') || '';
                            const productVariantName = $(this).data('product-variant') || '';
                            const productWeight = isNaN(parseFloat($(this).data('product-weight'))) ? 0 : parseFloat($(this).data('product-weight')) / 1000;
                            const productWidth = parseFloat($(this).data('product-width')) || 0;
                            const productHeight = parseFloat($(this).data('product-height')) || 0;
                            const productLength = parseFloat($(this).data('product-length')) || 0;
                            const productPrice = parseFloat($(this).data('product-price')) || 0;
                            const productQty = parseInt($(this).data('product-quantity')) || 0;
                            const productSubtotal = parseFloat($(this).data('product-subtotal')) || 0;
    
                                // Push the product data into the orderDetails array
                            orderDetails.push({
                                product_name: productName,
                                product_variant_name: productVariantName,
                                product_price: productPrice,
                                product_weight: productWeight * 1000,
                                product_width: Math.round(productWidth),
                                product_height: Math.round(productHeight),
                                product_length: Math.round(productLength),
                                qty: productQty,
                                subtotal: productSubtotal
                            });
                        });
                        
                        let data = {
                            action: 'komship_store_order', 
                            nonce: komshipData.nonce,
                            wc_order_id: "<?php echo $order->get_id()?>",
                            order_date: formatDateTime(orderDate),
                            brand_name: brandName,
                            shipper_name: shipperName,
                            shipper_phone: shipperPhone,
                            shipper_destination_id: shipperDestinationId,
                            shipper_address: shipperAddress,
                            shipper_email: shipperEmail,
                            receiver_name: receiverName,
                            receiver_phone: receiverPhone,
                            receiver_destination_id: receiverDestinationId,
                            receiver_address: receiverAddress,
                            shipping: shipping,
                            shipping_type: shippingType,
                            payment_method: paymentMethod,
                            shipping_cost: shippingCost,
                            shipping_cashback: shippingCashback,
                            service_fee: paymentMethod === "COD" ? Math.round((0.028 * grandTotal)) : 0,
                            additional_cost: additionalCost,
                            grand_total: grandTotal,
                            cod_value: grandTotal,
                            insurance_value: insuranceValue,
                            order_details: orderDetails
                        };
                        
                        showLoading();
                         $.ajax({
                            url: komshipData.ajax_url,  
                            type: 'POST',
                            data: data,
                            success: function(response) {
                                if (response.success) {
                                    // alert("BERHASIL");
                                    alert('Berhasil Mengatur pengiriman');
                                    location.reload();
                                } else {
                                    alert(JSON.stringify(response));  
                                }
                                hideLoading();
                            },
                            error: function() {
                                alert('There was an error processing your order.');
                                hideLoading();
                            }
                        });
                        
                    });


                }else{
                    console.log("DOKAN NOT SUPPORT");
                }
            });

        </script>
        <?php
    }


    private function calculate_order_weight($order) {
        $weight = 0;
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if ($product && $product->has_weight()) {
                $weight += ($product->get_weight() * $item->get_quantity());
            }
        }
        return max(0.1, $weight); // Minimum weight 0.1 kg
    }

    public function ajax_search_destination() {
        check_ajax_referer('komship_nonce', 'nonce');
        $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
        if (empty($keyword)) {
            wp_send_json_error(['message' => __('Keyword is required', 'komship-integration')]);
        }

        // Fetch destinations from the API
        $results = $this->api->search_destinations($keyword);
        if (is_wp_error($results)) {
            wp_send_json_error(['message' => $results->get_error_message()]);
        }

        wp_send_json_success(['destinations' => $results]);
    }

    public function ajax_calculate_shipping() {
        check_ajax_referer('komship_nonce', 'nonce');
        
        $shipper_id = isset($_POST['shipper_id']) ? absint($_POST['shipper_id']) : 0;
        $receiver_id = isset($_POST['receiver_id']) ? absint($_POST['receiver_id']) : 0;
        $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
        $item_value = isset($_POST['item_value']) ? floatval($_POST['item_value']) : 0;
        $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : "no";
    
        if (!$shipper_id) {
            wp_send_json_error(['message' => __('Please select a shipper', 'komship-integration')]);
        }
    
        if (!$receiver_id) {
            wp_send_json_error(['message' => __('Please select a receiver', 'komship-integration')]);
        }
    
        if (!$weight) {
            wp_send_json_error(['message' => __('Please enter the package weight', 'komship-integration')]);
        }
    
        if (!$item_value) {
            wp_send_json_error(['message' => __('Please enter the item value', 'komship-integration')]);
        }
    
        // Calculate shipping via the API
        $shipping = $this->api->calculate_shipping($shipper_id, $receiver_id, $weight, $item_value, $payment_method);
        if (is_wp_error($shipping)) {
            wp_send_json_error(['message' => $shipping->get_error_message()]);
        }
    
        wp_send_json_success($shipping);
    }
    
    public function ajax_order_detail() {
        check_ajax_referer('komship_nonce', 'nonce');
        $order_data = $_GET;
        
        $order_data['order_no'] = isset($order_data['order_no']) ? sanitize_text_field($order_data['order_no']) : '';
        
        $response = $this->api->get_order_detail($order_data['order_no']);
        
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
    
        // Successfully stored the order
        
        wp_send_json_success($response);
        
    }
    
    public function ajax_history_airway_bill() {
        check_ajax_referer('komship_nonce', 'nonce');
        $order_data = $_GET;
        
        $order_data['airway_bill'] = isset($order_data['airway_bill']) ? sanitize_text_field($order_data['airway_bill']) : '';
        $order_data['shipping'] = isset($order_data['shipping']) ? sanitize_text_field($order_data['shipping']) : '';
        
        $response = $this->api->history_airway_bill(array(
                'airway_bill' => $order_data['airway_bill'],
                'shipping' => $order_data['shipping']
            ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
    
        // Successfully 
        
        wp_send_json_success($response);
        
    }
    
    public function ajax_cancel_order() {
        // Verify nonce for security
        check_ajax_referer('komship_nonce', 'nonce');
    
        // Retrieve POST data
        $order_no = isset($_POST['order_no']) ? sanitize_text_field($_POST['order_no']) : '';
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
        // Validate required data
        if (empty($order_no) || empty($post_id)) {
            wp_send_json_error(['message' => 'Missing required data.']);
        }
    
        // Call the API to cancel the order
        $response = $this->api->cancel_order($order_no);
        
        // Check if the API call returned an error
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
    
        // Attempt to delete the post meta
        if (delete_post_meta($post_id, 'komship_order') || delete_post_meta($post_id, 'has_request_pickup')) {
            wp_send_json_success([
                'success' => true,
                'message' => 'Berhasil membatalkan pengiriman'
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to delete order metadata.']);
        }
    
        wp_die(); // Always include wp_die() at the end of AJAX functions
    }
    
    public function ajax_request_pickup() {
        
        check_ajax_referer('komship_nonce', 'nonce');
    
        
        $order_no = isset($_POST['order_no']) ? sanitize_text_field($_POST['order_no']) : '';
        $pickup_date = isset($_POST['pickup_date']) ? sanitize_text_field($_POST['pickup_date']) : '';
        $pickup_time = isset($_POST['pickup_time']) ? sanitize_text_field($_POST['pickup_time']) : '';
        $pickup_vehicle = isset($_POST['pickup_vehicle']) ? sanitize_text_field($_POST['pickup_vehicle']) : '';
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
        
        if (empty($order_no) || empty($pickup_date) || empty($pickup_time) || empty($pickup_vehicle) || empty($post_id)) {
            wp_send_json_error(['message' => __('Missing required data.', 'komship-integration')]);
        }
    
        
        $request_body = [
            'pickup_date' => $pickup_date,
            'pickup_time' => $pickup_time,
            'pickup_vehicle' => $pickup_vehicle,
            'orders' => [
                ['order_no' => $order_no]
            ]
        ];
    
        
        $response = $this->api->request_pickup($request_body);
    
        
        // Handle API errors
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
    
            // Decode the JSON embedded in the error message
            $decoded_message = json_decode($error_message, true);
    
            // Extract 'meta.message' if available
            $user_message = isset($decoded_message['meta']['message']) 
                ? $decoded_message['meta']['message'] 
                : __('An unknown error occurred.', 'komship-integration');
    
            wp_send_json_error(['message' => $user_message]);
        }
    
        
        if (empty($response)) {
            wp_send_json_error(['message' => __('Empty response from API.', 'komship-integration')]);
        }
        
        if($response){
            // update_post_meta($post_id,'komship_response',json_encode($response));
            if($response[0]['status'] == "success"){
                update_post_meta($post_id, 'has_request_pickup', true);
                
                wp_send_json_success($response);
            }else{
                wp_send_json_error($response);
            }
        }
        
        wp_die();
    }


    public function ajax_create_label(){
        check_ajax_referer('komship_nonce', 'nonce');
        
        $order_no = isset($_POST['order_no']) ? sanitize_text_field($_POST['order_no']) : '';
        
        $response = $this->api->create_label($order_no);
        
        if(is_wp_error($response)){
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
        
        wp_send_json_success($response);
    }

    public function ajax_store_order() {
        check_ajax_referer('komship_nonce', 'nonce');
    
        $order_data = $_POST;
        
        // Sanitize top-level order data fields
        $order_data['wc_order_id'] = isset($order_data['wc_order_id']) ? absint($order_data['wc_order_id']) : 0 ;
        $order_data['shipper_destination_id'] = isset($order_data['shipper_destination_id']) ? absint($order_data['shipper_destination_id']) : 0;
        $order_data['receiver_destination_id'] = isset($order_data['receiver_destination_id']) ? absint($order_data['receiver_destination_id']) : 0;
        $order_data['shipping_cost'] = isset($order_data['shipping_cost']) ? absint($order_data['shipping_cost']) : 0;
        $order_data['shipping_cashback'] = isset($order_data['shipping_cashback']) ? absint($order_data['shipping_cashback']) : 0;
        $order_data['service_fee'] = isset($order_data['service_fee']) ? absint($order_data['service_fee']) : 0;
        $order_data['additional_cost'] = isset($order_data['additional_cost']) ? absint($order_data['additional_cost']) : 0;
        $order_data['grand_total'] = isset($order_data['grand_total']) ? absint($order_data['grand_total']) : 0;
        $order_data['cod_value'] = isset($order_data['cod_value']) ? absint($order_data['cod_value']) : 0;
        $order_data['insurance_value'] = isset($order_data['insurance_value']) ? absint($order_data['insurance_value']) : 0;
        

        if (isset($order_data['order_details']) && is_array($order_data['order_details'])) {
            foreach ($order_data['order_details'] as $key => $product) {
                $order_data['order_details'][$key]['product_name'] = sanitize_text_field($product['product_name'] ?? '');
                $order_data['order_details'][$key]['product_variant_name'] = sanitize_text_field($product['product_variant_name'] ?? '');
                $order_data['order_details'][$key]['product_price'] = isset($product['product_price']) ? floatval($product['product_price']) : 0;
                $order_data['order_details'][$key]['product_weight'] = isset($product['product_weight']) ? floatval($product['product_weight']) : 0;
                $order_data['order_details'][$key]['product_width'] = isset($product['product_width']) ? floatval($product['product_width']) : 0;
                $order_data['order_details'][$key]['product_height'] = isset($product['product_height']) ? floatval($product['product_height']) : 0;
                $order_data['order_details'][$key]['product_length'] = isset($product['product_length']) ? floatval($product['product_length']) : 0;
                $order_data['order_details'][$key]['qty'] = isset($product['qty']) ? absint($product['qty']) : 0;
                $order_data['order_details'][$key]['subtotal'] = isset($product['subtotal']) ? floatval($product['subtotal']) : 0;
            }
        } else {
            wp_send_json_error(['message' => __('Invalid order details.', 'komship-integration')]);
        }
            
        $response = $this->api->store_order($order_data);  
    
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
    
        // Successfully stored the order
        update_post_meta($order_data['wc_order_id'], 'komship_order', serialize($response));
        
        
        wp_send_json_success(['message' => __('Order stored successfully', 'komship-integration')]);
        
       }

}
