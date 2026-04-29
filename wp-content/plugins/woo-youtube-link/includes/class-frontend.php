<?php
namespace WooYoutubeLink;

if (!defined('ABSPATH')) {
    exit;
}

class Frontend {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Daftarkan hook hanya saat single product
        add_action('wp', function () {
            if (is_product()) {
                // Gabungkan kedua tombol dalam satu fungsi
                add_action('woocommerce_share', [$this, 'render_share_and_youtube_buttons'], 25);
            }
        });
    }
    
    /**
     * Render tombol share dan YouTube dalam satu container
     */
    public function render_share_and_youtube_buttons() {
        $product = wc_get_product(get_the_ID());
        if (!$product) return;
        
        echo '<div class="woo-custom-buttons-container">';
        $this->output_icon($product->get_id());
        $this->output_share_button($product->get_id());
        echo '</div>';
        
        // Output CSS dan JS hanya sekali
        static $assets_loaded = false;
        if (!$assets_loaded) {
            $this->output_share_assets();
            $assets_loaded = true;
        }
    }
    
    /**
     * Cetak anchor ikon clapperboard jika meta tersedia
     */
    private function output_icon($product_id) {
        $youtube_link = get_post_meta($product_id, '_youtube_link', true);
        if (empty($youtube_link)) return;
    
        $label = esc_attr__('Marketing Kit', 'woo-youtube-link');
        echo '<a href="' . esc_url($youtube_link) . '" target="_blank" rel="noopener" class="woo-youtube-icon" aria-label="' . $label . '" title="' . $label . '"><i class="fa-solid fa-clapperboard" aria-hidden="true"></i></a>';
    }
    
    /**
     * Cetak tombol share dengan opsi
     */
    private function output_share_button($product_id) {
        global $wpdb;
        
        $product = wc_get_product($product_id);
        if (!$product) return;
        
        // Dapatkan subdomain jika user logged in
        $subdomain = '';
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $subdomain = $wpdb->get_var($wpdb->prepare(
                "SELECT subdomain FROM wp_member WHERE idwp = %d",
                $user_id
            ));
        }
        
        $product_url = esc_url(get_permalink($product_id));
        if (!empty($subdomain)) {
            $product_url = add_query_arg('reg', $subdomain, $product_url);
        }
        
        $product_title = esc_html($product->get_name());
        
        echo '
        <div class="woo-share-button-wrapper">
            <button type="button" class="woo-share-button" aria-label="' . esc_attr__('Bagikan produk', 'woo-youtube-link') . '">
                <i class="fas fa-share-alt" aria-hidden="true"></i>
            </button>
            <div class="woo-share-options">
                <a href="https://api.whatsapp.com/send?text=' . urlencode($product_title . ' ' . $product_url) . '" target="_blank" class="woo-share-option woo-share-whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($product_url) . '" target="_blank" class="woo-share-option woo-share-facebook">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
                <button type="button" class="woo-share-option woo-share-copy-link" data-link="' . $product_url . '">
                    <i class="fas fa-copy"></i> ' . esc_html__('Salin Tautan', 'woo-youtube-link') . '
                </button>
            </div>
        </div>';
    }
    
    /**
     * Output CSS dan JavaScript untuk tombol share
     */
    private function output_share_assets() {
        echo '
        <style>
            .woo-custom-buttons-container {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                margin-left: 10px;
            }
            
            .woo-youtube-icon {
                width: 40px;
                height: 40px;
                background-color: #fff;
                color: #ff0000; /* Warna merah YouTube */
                border: 1px solid #ddd;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 16px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                text-decoration: none;
            }
            
            .woo-youtube-icon:hover {
                background-color: #f8f9fa;
                transform: scale(1.05);
                color: #cc0000; /* Warna merah YouTube lebih gelap saat hover */
            }
            
            .woo-share-button-wrapper {
                display: inline-block;
                position: relative;
            }
            
            .woo-share-button {
                width: 40px;
                height: 40px;
                background-color: #fff;
                color: #333;
                border: 1px solid #ddd;
                border-radius: 50%;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                font-size: 16px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            
            .woo-share-button:hover {
                background-color: #f8f9fa;
                transform: scale(1.05);
            }
            
            .woo-share-options {
                display: none;
                position: absolute;
                bottom: 100%;
                left: 0;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                padding: 10px;
                width: 160px;
                z-index: 100;
                margin-bottom: 10px;
            }
            
            .woo-share-option {
                display: flex;
                align-items: center;
                padding: 8px;
                color: #333;
                text-decoration: none;
                font-size: 14px;
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
                border-radius: 3px;
            }
            
            .woo-share-option i {
                margin-right: 8px;
                width: 16px;
                text-align: center;
            }
            
            .woo-share-option:hover {
                background-color: #f8f9fa;
            }
            
            .woo-share-whatsapp { color: #25D366; }
            .woo-share-facebook { color: #4267B2; }
            
            /* Sembunyikan di mobile */
            @media (max-width: 768px) {
              
            }
        </style>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const shareButton = document.querySelector(".woo-share-button");
                if (shareButton) {
                    const shareOptions = document.querySelector(".woo-share-options");
                    
                    shareButton.addEventListener("click", function(e) {
                        e.stopPropagation();
                        shareOptions.style.display = (shareOptions.style.display === "block") ? "none" : "block";
                    });
                    
                    document.addEventListener("click", function() {
                        shareOptions.style.display = "none";
                    });
                    
                    shareOptions.addEventListener("click", function(e) {
                        e.stopPropagation();
                    });
                    
                    const copyLink = document.querySelector(".woo-share-copy-link");
                    if (copyLink) {
                        copyLink.addEventListener("click", function() {
                            const link = this.getAttribute("data-link");
                            navigator.clipboard.writeText(link).then(() => {
                                alert("' . esc_js(__('Tautan berhasil disalin!', 'woo-youtube-link')) . '");
                            }).catch(err => {
                                console.error("Gagal menyalin: ", err);
                            });
                        });
                    }
                }
            });
        </script>';
    }
}
