<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PPOB_Tripay_Shortcodes
{
    private static $instance = null;
    private $api;

    public function __construct()
    {
        $this->api = new PPOB_Tripay_API(); // Menggunakan class API yang sudah ada

        add_shortcode('ppob_tripay', [$this, 'render_shortcode']);
        add_shortcode('ppob_tripay_detail', [$this, 'detail_tripay']);
        // add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('wp_footer', function () {
        ?>
        <script src="<?php echo plugin_dir_url(__FILE__) . 'ppob-tripay.js'; ?>"></script>
        <script>
            (function($) {
                $(document).ready(function() {
                    console.log("PPOB Tripay script loaded!");
                    var ajaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    PPOBTripay = { ajax_url: ajaxUrl };
                });
            })(jQuery);
        </script>
        <?php
    });

        // AJAX Actions
        add_action('wp_ajax_get_saldo', [$this, 'get_saldo']);
        add_action('wp_ajax_nopriv_get_saldo', [$this, 'get_saldo']);
        add_action('wp_ajax_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_nopriv_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_get_operators', [$this, 'get_operators']);
        add_action('wp_ajax_nopriv_get_operators', [$this, 'get_operators']);
        add_action('wp_ajax_get_products', [$this, 'get_products']);
        add_action('wp_ajax_nopriv_get_products', [$this, 'get_products']);
        add_action('wp_ajax_get_product_detail', [$this, 'get_product_detail']);
        add_action('wp_ajax_nopriv_get_product_detail', [$this, 'get_product_detail']);
    }

    public function get_saldo()
    {
        $response = $this->api->request('/ceksaldo');
        wp_send_json($response);
    }

    public function get_categories()
    {
        $type = $_POST['type'] ?? 'prabayar'; // Default ke prabayar
        $endpoint = ($type === 'pascabayar') ? '/pembayaran/category' : '/pembelian/category';

        $response = $this->api->request($endpoint, null, "GET");
        wp_send_json($response);
    }

    public function get_operators()
    {
        $type = $_POST['type'] ?? 'prabayar';
        $endpoint = ($type === 'pascabayar') ? '/pembayaran/operator' : '/pembelian/operator';
        $body = [
            'category_id' => $_POST['category_id'] ?? null,
        ];
        error_log("RESPONSE GET OPERATOR");
        $response = $this->api->request($endpoint, $body, 'GET');
        wp_send_json($response);
    }

    public function get_products()
    {
        global $wpdb;

        $type = $_POST['type'] ?? 'prabayar';
        $endpoint = ($type === 'pascabayar') ? '/pembayaran/produk' : '/pembelian/produk';
        $category_id = $_POST['category_id'] ?? null;
        $operator_id = $_POST['operator_id'] ?? null;

        $body = [
            'category_id' => $category_id,
            'operator_id' => $operator_id,
        ];

        $response = $this->api->request($endpoint, $body, 'GET');

        if ($response['success'] == true && $type != 'pascabayar') {
            // Ambil data dari database Tripay
            $tripay_table = $wpdb->prefix . "tripay_references";

            // Buat query dengan parameterized query untuk menghindari SQL Injection
            $query = "SELECT * FROM {$tripay_table} WHERE 1=1";
            $query_params = [];

            if (!empty($category_id)) {
                $query .= " AND `category_id` = %d";
                $query_params[] = $category_id;
            }
            if (!empty($operator_id)) {
                $query .= " AND `operator_id` = %d";
                $query_params[] = $operator_id;
            }

            // Eksekusi query
            if (!empty($query_params)) {
                $products = $wpdb->get_results($wpdb->prepare($query, ...$query_params));
            } else {
                $products = $wpdb->get_results($query);
            }

            // Gabungkan data response API dengan data dari database Tripay
            if (!empty($products)) {
                foreach ($response['data'] as $key => $value) {
                    foreach ($products as $product) {
                        if ($value['code'] == $product->code) {
                            $response['data'][$key]['markup'] = $product->markup;
                            $response['data'][$key]['point_level'] = $product->point_level;
                            $response['data'][$key]['product_id'] = $product->product_id;
                        }
                    }
                }
            } else {
                $response['data'] = [];
            }
        }

        // Hanya tampilkan produk yang ada di database Tripay (yang telah disinkronisasi)
        wp_send_json($response);
    }
    public function get_product_detail()
    {
        $type = $_POST['type'] ?? 'prabayar';
        $endpoint = ($type === 'pascabayar') ? '/pembayaran/produk/cek' : '/pembelian/produk/cek';
        $body = [
            'code' => $_POST['code'] ?? '',
        ];

        if (empty($body['code'])) {
            wp_send_json(['success' => false, 'message' => 'Kode produk diperlukan']);
        }

        $response = $this->api->request($endpoint, $body, 'GET');
        wp_send_json($response);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('ppob-tripay', plugin_dir_url(__FILE__) . 'ppob-tripay.js', ['jquery'], null, true);
        wp_localize_script('ppob-tripay', 'PPOBTripay', ['ajax_url' => admin_url('admin-ajax.php')]);
    }


    public function render_shortcode()
    {
        ob_start();
?>
        <div class="ppob-container max-w-3xl mx-auto p-6 ">
            <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Beli Produk PPOB</h2>

            <!-- Tabs untuk Prabayar & Pascabayar -->
            <div class="mb-4 flex border-b">
                <button class="tab-btn px-4 py-2 w-1/2 text-lg font-semibold border-b-2 border-transparent hover:border-blue-500 focus:border-blue-500 text-center active" data-type="prabayar">Prabayar</button>
                <button class="tab-btn px-4 py-2 w-1/2 text-lg font-semibold border-b-2 border-transparent hover:border-blue-500 focus:border-blue-500 text-center" data-type="pascabayar">Pascabayar</button>
            </div>

            <!-- Kategori -->
            <!--<div class="mb-4">
              <label class="block font-semibold mb-2">Pilih Kategori:</label>
              <select id="category_id" class="w-full border p-2 rounded">
                  <option value="">Semua Kategori</option>
              </select>
          </div> -->
            <div style="margin-bottom: 16px;">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 8px;">Kategori</h3>
                <div id="category_id" style="">
                    <!-- Produk akan dimuat melalui AJAX -->
                </div>
            </div>

        </div>

        <script>
            var PPOBTripay = {
                ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>'
            };
        </script>
    <?php
        return ob_get_clean();
    }

    // Function to render the PPOB detail (2/3)
    // Function to render the PPOB detail (2/3) and Quick Checkout (1/3)
    public function detail_tripay()
    {
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        if (!$category_id) {
            return '<p class="woocommerce-error">Category ID is required.</p>';
        }

        ob_start();
    ?>
        <div id="descModal" style="
            display: none;
            z-index:9999;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        ">
            <div style="
        background: white;
        padding: 16px;
        border-radius: 8px;
        width: 300px;
        text-align: center;
        position: relative;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    ">
                <button id="closeModal" style="
            position: absolute;
            top: 8px;
            right: 8px;
            border: none;
            background: transparent;
            font-size: 16px;
            cursor: pointer;
        ">❌</button>
                <h3>Deskripsi Produk</h3>
                <p id="modalContent" style="margin-top: 8px;"></p>
            </div>
        </div>
        <div class="woocommerce">

            <div class="woocommerce-notices-wrapper"></div>

            <div class="ppob-checkout-wrapper" style="display: flex;  gap: 20px;">
                <!-- PPOB Detail (2/3) -->
                <div class="ppob-details" style="flex: 2; min-width: 60%; background: #fff; padding: 20px; border: 1px solid #eaeaea; border-radius: 5px;">


                    <input type="hidden" id="ppob-type" value="<?php echo $_GET['type'] ?>">
                    <input type="hidden" id="category_id" value="<?php echo $_GET['category_id'] ?>">

                    <!-- Input Nomor HP & Pilih Operator -->
                    <div class="form-row" style="display: flex; align-items: center; gap: 20px;">
                        <!-- No.HP / Pelanggan / User ID -->
                        <p class="form-row" style="flex: 1; margin: 0; line-height: 2;">
                            <label style="color:black" for="phone_number">No.Hp/No.Pelanggan/UserID</label>
                            <input type="text" id="phone_number" class="input-text" placeholder="Nomor HP" style="width: 100%;">
                        </p>

                        <!-- Pilih Operator -->
                        <p class="form-row" style="flex: 1;line-height: 2; margin: 0;">
                            <label style="color:black" for="operator_id">Pilih Operator</label>
                            <select id="operator_id" class="input-text" style="width: 100%;">
                                <option value="">Pilih Operator</option>
                            </select>
                        </p>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="form-row">
                        <h3 style="margin-bottom: 10px;">Daftar Produk:</h3>
                        <div id="productList" style="
                            display: grid; 
                            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
                            gap: 15px;
                            justify-content: center;
                        ">
                            <!-- Produk akan dimuat melalui AJAX -->
                        </div>
                    </div>
                </div>

                <!-- Quick Checkout (1/3) -->
                <div class="quick-checkout" style="flex: 1; min-width: 35%; background: #fff; padding: 20px; border: 1px solid #eaeaea; border-radius: 5px;">
                    <?php echo do_shortcode('[custom_quick_checkout]'); ?>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
