<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PPOB_Tripay_Cart
{
    public function __construct()
    {
        add_action('wp_ajax_ppob_add_to_cart', [$this, 'ppob_add_to_cart']);
        add_action('wp_ajax_nopriv_ppob_add_to_cart', [$this, 'ppob_add_to_cart']);
    }

    /**
     * Tambahkan layanan PPOB ke cart WooCommerce
     */
    public function ppob_add_to_cart()
    {
        global $wpdb;
        if (!isset($_POST['product_code'])) {
            wp_send_json_error(['message' => 'Data layanan tidak lengkap!']);
        }

        $service_code  = sanitize_text_field($_POST['product_code']);

        $table_name = $wpdb->prefix . 'tripay_references';
        $service = $wpdb->get_row("SELECT * FROM $table_name WHERE code = '$service_code'");

        if (!$service) {
            wp_send_json_error(['message' => 'Mohon maaf, Saat ini sedang tidak tersedia']);
        }

        $final_price = $service->price + $service->markup;

        $product_id = $this->get_existing_product($service_code);

        if (!$product_id) {
            $product_id = $this->create_ppob_product($service->name, $final_price, $service_code);
            $reward = [
                'point_level' => $service->point_level,
            ];
            update_post_meta($product_id, 'mycred_reward', $reward);
            // Set author menjadi 0 (tanpa pemilik)
            global $wpdb;
            $wpdb->update(
                $wpdb->posts,
                ['post_author' => 0],
                ['ID' => $product_id],
                ['%d'],
                ['%d']
            );
        } else {
            // Update harga produk
            $product = wc_get_product($product_id);
            $product->set_regular_price($final_price);
            $product->set_price($final_price);
            $product->set_catalog_visibility('hidden');
            $product->set_sold_individually(true);
            $product->set_virtual(true);
            $product->set_sku($service_code);
            // set author to empty
            $product->save();

            // Set author menjadi 0 (tanpa pemilik)
            global $wpdb;
            $wpdb->update(
                $wpdb->posts,
                ['post_author' => 0],
                ['ID' => $product_id],
                ['%d'],
                ['%d']
            );

            //update mycred point_level
            $mycred_reward['point_level'] = $service->point_level;
            update_post_meta($product_id, 'mycred_reward', $mycred_reward);
        }

        if (!$product_id) {
            wp_send_json_error(['message' => 'Gagal menambahkan layanan ke cart!']);
        }

        wp_send_json(
            [
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan ke cart!',
            ]
        );
    }

    /**
     * Cari produk PPOB berdasarkan SKU (service_code)
     */
    public function get_existing_product($service_code)
    {
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_sku',
                    'value' => $service_code,
                    'compare' => '='
                ]
            ]
        ];

        $products = get_posts($args);

        if (!empty($products)) {
            return $products[0]->ID;
        }

        return false;
    }

    /**
     * Buat produk PPOB di WooCommerce secara dinamis
     */
    public function create_ppob_product($name, $price, $sku)
    {
        $product = new WC_Product_Simple();
        $product->set_name($name);
        $product->set_regular_price($price);
        $product->set_price($price);
        $product->set_catalog_visibility('hidden');
        $product->set_sold_individually(true);
        $product->set_virtual(true);
        $product->set_sku($sku);

        $product_id = $product->save();

        // Save the service_code as a custom meta field
        update_post_meta($product_id, '_service_code', $sku);
        return $product_id;
    }
}

// Inisialisasi PPOB Cart Handler
new PPOB_Tripay_Cart();
