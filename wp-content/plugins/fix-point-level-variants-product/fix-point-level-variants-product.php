<?php
/**
 * Fix point level variants product
 *
 * @package       FIXPOINTLE
 * @author        Novan
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Fix point level variants product
 * Plugin URI:    https://mydomain.com
 * Description:   Tampilkan reward point level di produk variasi
 * Version:       1.0.0
 * Author:        Novan
 * Author URI:    https://novan-portfolio.netlify.app/
 * Text Domain:   fix-point-level-variants-product
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Display placeholder for variable product MyCred reward points.
function display_variable_product_mycred_reward_meta() {
    global $product;

    if ( $product->is_type( 'variable' ) ) {
        echo '<div id="variable-product-mycred-reward-meta" class="product-meta"></div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'display_variable_product_mycred_reward_meta', 25 );

// Add MyCred reward points to variation data.
function add_mycred_reward_to_variation_data( $variation_data, $product, $variation ) {
    // Retrieve and unserialize the '_mycred_reward' meta data
    $serialized_reward = get_post_meta( $variation->get_id(), '_mycred_reward', true );

    error_log( print_r($serialized_reward, true) ); // Debugging line to check data


    $mycred_reward = maybe_unserialize( $serialized_reward );

    if ( ! empty( $mycred_reward ) && is_array( $mycred_reward ) ) {
        $variation_data['mycred_reward'] = $mycred_reward['point_level'] ?? 0;
        $variation_data['mycred_level'] = $mycred_reward['point_upgrade'] ?? 0;
    } else {
        $variation_data['mycred_reward'] = 0;
    }
    $BV_type = get_post_meta($parent_id , 'BV_type', true);
    $kredit = 0;
    if ($BV_type){
        if (is_null($BV)) {
            $BV = 0;
        }
        $parent_id = wp_get_post_parent_id( $variation->get_id() );
        $price = get_post_meta($parent_id , '_price', true);

        if ($BV_type === 'percent') {
            $kredit += floatval($price) * ( floatval($BV) / 100);
        
        } else {
            $kredit += floatval($BV);
        }
        $variation_data['komisi'] = $kredit;
    }

    return $variation_data;
}
add_filter( 'woocommerce_available_variation', 'add_mycred_reward_to_variation_data', 10, 3 );

// Add the reward points display to the footer of the page
function add_mycred_reward_script() {
    if ( is_product() ) {
        global $product;

        // Cek apakah produk afiliasi
        $is_affiliate_product = get_post_meta($product->get_id(), '_is_affiliate_product', true);

        // Hanya jalankan pada produk variabel
        if ( $product->is_type( 'variable' ) ) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var rewardDisplay = $('#mycred_reward_display');

                    $('form.variations_form').on('show_variation', function(event, variation) {
                        var pointReward = variation.point_reward_estimation;
                        var mycredReward = variation.mycred_reward;
                        var mycredLevel = variation.mycred_level;
                        var kredit = variation.BV; // Nilai kredit (komisi) dari PHP
                        var isAffiliateProduct = <?php echo json_encode($is_affiliate_product); ?>;

                        // Jika produk afiliasi
                        if (isAffiliateProduct == 'yes') {
                            if (pointReward && pointReward > 0) {
                                rewardDisplay.html(`
                                    <div class="yith-par-message" style="background-color:#E4F6F3; color:#000000; margin-bottom:10px;">
                                        <img style="max-width:16px; margin-right:5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
                                        Dapatkan Hingga <strong><span class="product_point">${pointReward}</span> Poin Bonus</strong>
                                    </div>
                                    <div class="yith-par-message" style="background-color:#F6E4E4; color:#000000;">
                                        <img style="max-width:16px; margin-right:5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
                                        Dapatkan <strong><span class="product_upgrade">${mycredLevel}</span> Poin Level</strong>
                                    </div>
                                `);
                            } else {
                                rewardDisplay.html('<p>Tidak ada reward poin pada produk ini.</p>');
                            }
                        } 
                        // Produk non-affiliate
                        else {
                            rewardDisplay.html(''); // Kosongkan dulu

                            // Tampilkan poin bonus jika ada
                            if (mycredReward && mycredReward > 0) {
                                rewardDisplay.append(`
                                    <div class="yith-par-message" style="background-color:#E4F6F3; color:#000000; margin-bottom:10px;">
                                        <img style="max-width:16px; margin-right:5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
                                        Dapatkan <strong><span class="product_point">${mycredReward}</span> Poin Bonus</strong>
                                    </div>
                                `);
                            }

                            // Tampilkan poin level jika ada
                            if (mycredLevel && mycredLevel > 0) {
                                rewardDisplay.append(`
                                    <div class="yith-par-message" style="background-color:#F6E4E4; color:#000000; margin-bottom:10px;">
                                        <img style="max-width:16px; margin-right:5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
                                        Dapatkan <strong><span class="product_upgrade">${mycredLevel}</span> Poin Level</strong>
                                    </div>
                                `);
                            }

                            // 🔥 Tambahkan perhitungan komisi (kredit BV)
                            if (kredit && kredit > 0) {
                                rewardDisplay.append(`
                                    <div class="yith-par-message" style="background-color:#FFF3CD; color:#000000;">
                                        <img style="max-width:16px; margin-right:5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
                                        Dapatkan komisi sebesar <strong><span class="product_kredit">${Number(kredit).toLocaleString('id-ID')}</span></strong>
                                    </div>
                                `);
                            }
                        }
                    });

                    // Reset tampilan jika variasi dibatalkan
                    $('form.variations_form').on('reset_data', function() {
                        rewardDisplay.html('');
                    });
                });
            </script>
            <?php
        }
    }
}

add_action( 'wp_footer', 'add_mycred_reward_script' );
// POINT ESTIMASI PRODUK AFFILIATE
add_action( 'woocommerce_single_product_summary', 'display_point_estimation_message', 20 );
function display_point_estimation_message() {
    global $post;
    // Cek apakah produk ini variasi
    if ($post->post_type === 'product_variation') {
        $parent_id = $post->post_parent;
    } else {
        $parent_id = $post->ID;
    }
    
    // Ambil BV dan BV_type dari produk (kalau variasi, ambil dari parent)
    $BV = get_post_meta($parent_id, 'BV', true);
    if (is_null($BV) || $BV === '') {
        $BV = 0;
    }
    
    $BV_type = get_post_meta($parent_id, 'BV_type', true);
    
    // Ambil harga (tetap dari produk variasinya, karena harga bisa berbeda)
    $price = get_post_meta($post->ID, '_price', true);
    
    // Hitung nilai kredit (bonus value)
    if ($BV_type === 'percent') {
        $kredit += floatval($price) * (floatval($BV) / 100);
    } else {
        $kredit += floatval($BV);
    }

    // Periksa apakah produk adalah affiliate product
    $is_affiliate_product = get_post_meta( $post->ID, '_is_affiliate_product', true );
    if ( $is_affiliate_product === 'yes' ) {
        // Ambil estimasi poin
        $point_reward = get_post_meta( $post->ID, 'point_reward_estimation', true );

        // Pastikan estimasi poin tidak kosong
        if ( ! empty( $point_reward ) ) {
            echo '<div id="affiliate-point-message" class="yith-par-message" style="background-color:#E4F6F3; color: #000000; margin-bottom: 10px;">
            <img style="max-width: 16px; margin-right: 5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
            Dapatkan Hingga <strong><span class="product_point">' . esc_html( $point_reward ) . '</span> Poin Level</strong>
            </div>';
        }
    }

        if( $kredit ){
        echo '<div class="yith-par-message" style="background-color:#F6E4E4; color: #000000; margin-bottom: 10px;">
        <img style="max-width: 16px; margin-right: 5px;" src="https://shopbiz.id/wp-content/plugins/yith-woocommerce-points-and-rewards-premium/assets/images/badge.svg">
        Dapatkan komisi sebesar <strong><span class="product_upgrade">' . number_format( $kredit, 0, ',', '.' ) . '</span> </strong>
        </div>';
    }
}



// HIDE POINT LEVEL MESSAGE
add_action( 'wp_footer', 'hide_non_affiliate_messages' );
function hide_non_affiliate_messages() {
    if ( is_product() ) { // Pastikan hanya dijalankan di halaman produk
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                if ($('#affiliate-point-message').length) {
                    // Sembunyikan semua .yith-par-message kecuali #affiliate-point-message
                    //$('.yith-par-message').not('#affiliate-point-message').hide();
                }
            });
        </script>
        <?php
    }
}
// Add placeholder for MyCred reward points display, only for variable products.
function add_mycred_reward_placeholder() {
    global $product;

    // Check if the product is a variable product
    if ( $product->is_type( 'variable' ) ) {
        echo '<div id="mycred_reward_display"><p>Pilih produk untuk melihat reward Poin Level dan Poin Bonus</p></div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'add_mycred_reward_placeholder', 15 );

// Function to display the affiliate button for simple and variable products
function add_affiliate_button() {
    global $product;

    // For simple products
    if ( $product->is_type( 'simple' ) ) {
        $is_affiliate = get_post_meta( $product->get_id(), '_is_affiliate_product', true );
        $affiliate_url = get_post_meta( $product->get_id(), '_affiliate_product_url', true );
        $BV = get_post_meta( $product->get_id(), 'BV', true);

        if (is_null($BV)) {
            $BV = 0;
        }
        $BV_type = get_post_meta($product->get_id(), 'BV_type', true);

        $price = get_post_meta($product->get_id(), '_price', true);

        if ($BV_type === 'percent') {
            $kredit += floatval($price) * ( floatval($BV) / 100);
        } else {
            $kredit += floatval($BV);
        }
        // If it's an affiliate product, show the button
        if ( 'yes' === $is_affiliate && ! empty( $affiliate_url ) ) {
            echo '<a href="' . esc_url( $affiliate_url ) . '" class="button product_type_external" target="_blank" style="width: 100%; margin-top: 10px; text-align: center; background-color: transparent; border: 2px solid #eb7d34; color: #eb7d34 !important; padding: 10px 20px; font-size: 16px;">Lihat Produk</a>';
        }
    }

    // For variable products
    if ( $product->is_type( 'variable' ) ) {

        if (is_null($BV)) {
            $BV = 0;
        }
        $BV_type = get_post_meta($product->get_id(), 'BV_type', true);

        $price = get_post_meta($product->get_id(), '_price', true);

        if ($BV_type === 'percent') {
            $kredit += floatval($price) * ( floatval($BV) / 100);
        } else {
            $kredit += floatval($BV);
        }
        // Placeholder to show affiliate link when a variation is selected
        echo '<div id="affiliate-product-link" style="margin-top: 15px;"></div>';

        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // On variation select, check if the variation has the affiliate meta and display the button
                $('form.variations_form').on('show_variation', function(event, variation) {
                    if (variation._affiliate_product_url) {
                        $('#affiliate-product-link').html('<a href="' + variation._affiliate_product_url + '" class="button product_type_external" target="_blank"style="width: 100%; margin-top: 10px; text-align: center; background-color: transparent; border: 2px solid #eb7d34; color: #eb7d34 !important; padding: 10px 20px; font-size: 16px;">Lihat Produk</a>');
                    } else {
                        $('#affiliate-product-link').html(''); // Clear the button if no affiliate link
                    }
                });

                // On reset (no variation selected), clear the affiliate button
                $('form.variations_form').on('reset_data', function() {
                    $('#affiliate-product-link').html('');
                });
            });
        </script>
        <?php
    }
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_affiliate_button' );

    // Add affiliate link to available variation data for variable products
    function add_affiliate_data_to_variation( $variation_data, $product, $variation ) {
        // Ambil ID variasi & induk
    $variation_id = $variation->get_id();
    $parent_id    = wp_get_post_parent_id( $variation_id );
    
    // Ambil BV dan BV_type dari variasi, jika kosong fallback ke parent
    $BV = get_post_meta( $variation_id, 'BV', true );
    $BV_type = get_post_meta( $variation_id, 'BV_type', true );
    
    if ( $BV === '' || is_null($BV) ) {
        $BV = get_post_meta( $parent_id, 'BV', true );
    }
    if ( $BV_type === '' || is_null($BV_type) ) {
        $BV_type = get_post_meta( $parent_id, 'BV_type', true );
    }
    
    // Default kalau tetap kosong
    if ( $BV === '' || is_null($BV) ) {
        $BV = 0;
    }
    if ( $BV_type === '' || is_null($BV_type) ) {
        $BV_type = 'nominal';
    }
    
    // Gunakan harga variasi (kalau ada), fallback ke harga induk
    $price = get_post_meta( $variation_id, '_price', true );
    if ( $price === '' || is_null($price) ) {
        $price = get_post_meta( $parent_id, '_price', true );
    }
    
    // Hitung kredit
    if ( $BV_type === 'percent' ) {
        $kredit = floatval($price) * ( floatval($BV) / 100 );
    } else {
        $kredit = floatval($BV);
    }

    // Ambil data affiliate dari variasi
    $is_affiliate = get_post_meta( $variation->get_id(), '_is_affiliate_product', true );
    $affiliate_url = get_post_meta( $variation->get_id(), '_affiliate_product_url', true );
    $product_reward = get_post_meta( $variation->get_id(), 'point_reward_estimation', true );

    // Tambahkan data affiliate + BV ke data variasi
    $variation_data['_is_affiliate_product'] = $is_affiliate;
    $variation_data['_affiliate_product_url'] = $affiliate_url;
    $variation_data['point_reward_estimation'] = $product_reward;
    $variation_data['BV'] = $kredit;
    
    return $variation_data;
}

add_filter( 'woocommerce_available_variation', 'add_affiliate_data_to_variation', 10, 3 );
