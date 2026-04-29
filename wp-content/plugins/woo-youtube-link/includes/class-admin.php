<?php
namespace WooYoutubeLink;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_youtube_field']);
        add_action('woocommerce_process_product_meta', [$this, 'save_youtube_field']);
    }

    public function add_youtube_field() {
        echo '<div class="options_group">';
        woocommerce_wp_text_input([
            'id' => '_youtube_link',
            'label' => __('Marketing Link', 'woo-youtube-link'),
            'placeholder' => 'link marketing dari halaman produk',
            'desc_tip' => true,
            'description' => __('Tambahkan link Marketing untuk produk ini.', 'woo-youtube-link')
        ]);
        echo '</div>';
    }

    public function save_youtube_field($post_id) {
        if (isset($_POST['_youtube_link'])) {
            update_post_meta($post_id, '_youtube_link', sanitize_text_field($_POST['_youtube_link']));
        }
    }
}
