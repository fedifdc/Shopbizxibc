<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PPOB_Tripay_Admin
{
    private $api;

    private $table_tripay;

    const CRON_HOOK_PRODUCTS = 'ppob_tripay_sync_products';

    public function __construct($tripay_api)
    {
        global $wpdb;
        $this->api = $tripay_api;

        $this->table_tripay = $wpdb->prefix . 'tripay_references';

        error_log("[PPOB_Tripay] Initializing PPOB_Tripay_Admin");

        // Register AJAX actions
        add_action('wp_ajax_ppob_tripay_sync_categories', [$this, 'ajax_sync_categories']);
        add_action('wp_ajax_ppob_tripay_sync_operators', [$this, 'ajax_sync_operators']);
        add_action('wp_ajax_ppob_tripay_sync_products', [$this, 'ajax_sync_products']);
        add_action('wp_ajax_ppob_tripay_get_products', [$this, 'ppob_tripay_get_products']);
        add_action('wp_ajax_ppob_tripay_get_balances', [$this, 'ajax_get_balances']);
        add_action('wp_ajax_ppob_tripay_bulk_edit_product', [$this, 'ajax_bulk_edit_tripay_product']);



        add_action(self::CRON_HOOK_PRODUCTS, [$this, 'sync_tripay_products']);
    }

    public function ajax_bulk_edit_tripay_product()
    {
        // Validasi nonce untuk keamanan
        check_ajax_referer('ppob_tripay_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $products = isset($_POST['products']) ? $_POST['products'] : [];
        if (empty($products)) {
            wp_send_json_error(['message' => 'Products data is required']);
        }

        $this->bulk_edit_products($products);

        wp_send_json([
            'success' => true,
            'message' => 'Products updated successfully'
        ]);
    }

    function ppob_tripay_get_products()
    {
        global $wpdb;

        // Validasi nonce untuk keamanan
        check_ajax_referer('ppob_tripay_nonce', 'nonce');

        $operator_id = isset($_POST['operator_id']) ? sanitize_text_field($_POST['operator_id']) : '';
        $category_id = isset($_POST['category_id']) ? sanitize_text_field($_POST['category_id']) : '';

        if (empty($operator_id)) {
            wp_send_json_error(['error' => 'Operator ID tidak valid']);
        }

        $tripay = $wpdb->prefix . "tripay_references";
        $products = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$tripay} WHERE operator_id = %s AND category_id = %s", $operator_id, $category_id));

        if (!empty($products)) {

            wp_send_json_success($products);
        } else {
            wp_send_json_error(['error' => 'Produk tidak ditemukan']);
        }
    }

    public function ajax_sync_categories()
    {
        // add nonce check
        // Validasi nonce untuk keamanan
        check_ajax_referer('ppob_tripay_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $categories = $this->sync_tripay_categories();
        wp_send_json(
            [
                'success' => true,
                'message' => 'Categories synced successfully',
                'data' => $categories
            ]
        );
    }

    public function ajax_sync_operators()
    {    // Validasi nonce untuk keamanan
        check_ajax_referer('ppob_tripay_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        if (!isset($_POST['category_id'])) {
            wp_send_json_error(['message' => 'Category ID is required']);
        }

        $category_id = intval($_POST['category_id']);
        $operators = $this->sync_tripay_operators($category_id);

        wp_send_json(
            [
                "success" => true,
                "message" => "Operators synced successfully",
                "data" => $operators
            ]
        );
    }

    public function ajax_get_balances()
    {
        // add nonce check
        // Validasi nonce untuk keamanan
        check_ajax_referer('ppob_tripay_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $balance = $this->fetch_tripay_balance();
        wp_send_json(
            [
                "success" => true,
                "message" => "Balance fetched successfully",
                "data" => $balance
            ]
        );
    }

    public function ajax_sync_products()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        if (!isset($_POST['category_id']) || !isset($_POST['operator_id'])) {
            wp_send_json_error(['message' => 'Category ID and Operator ID are required']);
        }

        $category_id = intval($_POST['category_id']);
        $operator_id = intval($_POST['operator_id']);
        $products = $this->sync_tripay_products($category_id, $operator_id);

        wp_send_json([
            "success" => true,
            "message" => "Products synced successfully",
            "data" => $products
        ]);
    }

    /**
     * Schedule product sync cron job.
     */
    public function schedule_sync_products($category_id, $operator_id)
    {
        if (!wp_next_scheduled(self::CRON_HOOK_PRODUCTS, [$category_id, $operator_id])) {
            wp_schedule_single_event(time(), self::CRON_HOOK_PRODUCTS, [$category_id, $operator_id]);
        }
        return true;
    }

    /**
     * Fetch and store categories.
     */
    public function sync_tripay_categories()
    {
        global $wpdb;

        error_log("[PPOB_Tripay] Syncing categories...");

        $categories = $this->fetch_api_data('/pembelian/category');
        if (!$categories) {
            error_log("[PPOB_Tripay] Failed to fetch categories");
            return;
        }

        error_log("[PPOB_Tripay] Categories synced successfully");
        return $categories;
    }

    /**
     * Fetch and store operators by category.
     */
    public function sync_tripay_operators($category_id)
    {
        global $wpdb;

        error_log("[PPOB_Tripay] Syncing operators for category $category_id...");

        $operators = $this->fetch_api_data('/pembelian/operator', ['category_id' => $category_id]);
        if (!$operators) {
            error_log("[PPOB_Tripay] Failed to fetch operators");
            return;
        }


        error_log("[PPOB_Tripay] Operators synced successfully for category $category_id");
        return $operators;
    }

    /**
     * Fetch and store products by operator.
     */
    public function sync_tripay_products($category_id, $operator_id)
    {
        global $wpdb;

        error_log("[PPOB_Tripay] Syncing products for operator $operator_id...");

        // Mengambil data produk dari API Tripay
        $products = $this->fetch_api_data('/pembelian/produk', [
            'category_id' => $category_id,
            'operator_id' => $operator_id,
        ]);

        // Jika gagal mengambil data, hentikan proses
        if (!$products) {
            error_log("[PPOB_Tripay] Failed to fetch products");
            return;
        }

        $insert_data = [];
        foreach ($products as $product) {
            $insert_data[] = [
                'code'        => sanitize_text_field($product['code']),  // Membersihkan kode produk
                'name'        => sanitize_text_field($product['product_name']), // Membersihkan nama produk
                'price'       => intval($product['price']), // Konversi harga ke integer
                'operator_id' => $operator_id,  // Menyimpan operator ID
                'category_id' => $category_id,  // Menyimpan kategori ID
            ];
        }

        // Memasukkan data produk ke dalam database jika ada produk baru
        if (!empty($insert_data)) {
            $this->batch_insert($this->table_tripay, ['code', 'name', 'price', 'operator_id', 'category_id'], $insert_data);
        }

        foreach ($insert_data as $data) {
            $ppob_cart = new PPOB_Tripay_Cart();

            // Mengecek apakah produk sudah ada di WooCommerce berdasarkan kode produk
            $product_id = $ppob_cart->get_existing_product($data['code']);

            if ($product_id) {
                // Jika produk sudah ada, update product_id ke tabel tripay
                $wpdb->update(
                    $this->table_tripay,
                    ['product_id' => $product_id],
                    ['code' => $data['code']],
                    ['%d'], // Format untuk integer (product_id)
                    ['%s']  // Format untuk string (code)
                );
                continue;
            } else {
                // Jika produk belum ada, buat produk baru di WooCommerce
                $product_id = $ppob_cart->create_ppob_product($data['name'], $data['price'], $data['code']);

                if ($product_id) {
                    // Jika berhasil membuat produk, set author ke 0 agar tidak ada pemilik spesifik
                    $wpdb->update(
                        $wpdb->posts,
                        ['post_author' => 0],
                        ['ID' => $product_id],
                        ['%d'], // Format untuk integer
                        ['%d']  // Format untuk kondisi ID
                    );

                    // Setelah produk dibuat, update product_id ke tabel tripay
                    $wpdb->update(
                        $this->table_tripay,
                        ['product_id' => $product_id],
                        ['code' => $data['code']],
                        ['%d'], // Format untuk integer (product_id)
                        ['%s']  // Format untuk string (code)
                    );
                }
            }
        }

        error_log("[PPOB_Tripay] Products synced successfully for operator $operator_id");
        return $products;
    }
    
    public function fetch_tripay_balance()
    {
        $balance = $this->api->request('/ceksaldo', null, "GET");
        if (!$balance) {
            error_log("[PPOB_Tripay] Failed to fetch balance");
            return;
        }
        return $balance['message'];
    }
    /**
     * Fetch API data and handle errors.
     */
    private function fetch_api_data($endpoint, $params = [])
    {
        $response = $this->api->request($endpoint, $params, "GET");

        if (is_wp_error($response)) {
            error_log("[PPOB_Tripay] API Error ($endpoint): " . $response->get_error_message());
            return false;
        }

        if (!$response || !isset($response['data'])) {
            error_log("[PPOB_Tripay] Invalid response from API ($endpoint).");
            return false;
        }

        return $response['data'];
    }

    /**
     * Batch insert data into the database.
     */
    private function batch_insert($table, $columns, $data)
    {
        global $wpdb;

        $values = [];
        $placeholders = [];
        foreach ($data as $row) {
            $row_values = [];
            foreach ($columns as $column) {
                $row_values[] = $row[$column];
            }
            $values = array_merge($values, $row_values);
            $placeholders[] = "(" . implode(',', array_fill(0, count($row_values), '%s')) . ")";
        }

        $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES " . implode(', ', $placeholders) . "
                ON DUPLICATE KEY UPDATE " . implode(', ', array_map(fn($col) => "$col = VALUES($col)", $columns));

        $wpdb->query($wpdb->prepare($sql, ...$values));

        error_log("[PPOB_Tripay] Batch inserted " . count($data) . " records into $table.");
    }

    private function batch_update($table_name, $data, $primary_key = 'ID')
    {
        global $wpdb;

        if (empty($data)) {
            return new WP_Error('empty_data', 'No data provided for batch update.');
        }

        $cases = [];
        $conditions = [];

        foreach ($data as $row) {
            if (!isset($row[$primary_key])) {
                return new WP_Error('missing_primary_key', 'Primary key is missing in one or more rows.');
            }

            // Escape and add primary key values
            $primary_key_value = esc_sql($row[$primary_key]);
            $conditions[] = "'$primary_key_value'"; // Wrap string values in quotes

            foreach ($row as $field => $value) {
                if ($field === $primary_key) {
                    continue;
                }

                // Determine proper escaping
                $value = is_numeric($value) ? $value : "'" . esc_sql($value) . "'";

                if (!isset($cases[$field])) {
                    $cases[$field] = [];
                }

                // Fix the CASE statement format
                $cases[$field][] = "WHEN `$primary_key` = '$primary_key_value' THEN $value";
            }
        }

        // If no valid cases, return an error
        if (empty($cases)) {
            return new WP_Error('invalid_data', 'No valid fields to update.');
        }

        // Construct the SQL statement
        $sql = "UPDATE `$table_name` SET ";
        $updates = [];

        foreach ($cases as $field => $field_cases) {
            $updates[] = "`$field` = CASE " . implode(' ', $field_cases) . " END";
        }

        $sql .= implode(', ', $updates);
        $sql .= " WHERE `$primary_key` IN (" . implode(',', $conditions) . ");";

        // Execute the query
        $result = $wpdb->query($sql);

        error_log("QUERY: " . $sql);

        if ($result === false) {
            return new WP_Error('db_error', 'Failed to update rows in the database.');
        }

        return $result;
    }

    public function bulk_edit_products($products)
    {

        $update_data = [];
        foreach ($products as $product) {
            $update_data[] = [
                'code'    => sanitize_text_field($product['code']),
                'markup' => intval($product['markup']),
                'point_level' => intval($product['point_level']),

            ];
        }

        if (!empty($update_data)) {
            $this->batch_update($this->table_tripay, $update_data, 'code');

            //get products in post with have SKU same with code and update the point_level and price (markup+price)
            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => '_sku',
                        'value' => array_column($products, 'code'),
                        'compare' => 'IN'
                    ]
                ]
            ];

            $products = get_posts($args);
            foreach ($products as $product) {
                $product_id = $product->ID;
                $product_sku = get_post_meta($product_id, '_sku', true);
                $product_price = get_post_meta($product_id, '_price', true);
                $product_point_level = get_post_meta($product_id, 'mycred_reward', true);
                $mycred_reward = unserialize($product_point_level);

                foreach ($update_data as $data) {
                    if ($data['code'] == $product_sku) {
                        $new_price = $product_price + $data['markup'];
                        update_post_meta($product_id, '_price', $new_price);
                        $mycred_reward['point_level'] = $data['point_level'];
                        update_post_meta($product_id, 'mycred_reward', $mycred_reward);
                    }
                }
            }
        }

        error_log("[PPOB_Tripay] Products updated successfully.");
        return true;
    }
}

// Register deactivation hook to clear scheduled events
register_deactivation_hook(__FILE__, function () {
    error_log("[PPOB_Tripay] Clearing scheduled events on deactivation.");
    wp_clear_scheduled_hook(PPOB_Tripay_Admin::CRON_HOOK_PRODUCTS);
});
