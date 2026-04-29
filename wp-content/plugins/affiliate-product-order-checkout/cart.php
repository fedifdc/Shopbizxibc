<?php
ob_start(); // Mulai buffer output
function add_shortcode_to_before_cart_content() {
    echo do_shortcode('[total_cart_points enable_refresh="no"]');
}
add_action('woocommerce_before_cart', 'add_shortcode_to_before_cart_content', 10);
do_action( 'woocommerce_before_cart' );
?>
<div class="woocommerce-cart-notice-minimum-amount default-layout">
    <form class="woocommerce-cart-form ui form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" id="cart-form">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>
        
        <?php 
        // Array untuk menyimpan affiliate dan non-affiliate products
        $affiliate_products = array();
        $non_affiliate_products = array();
        
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            
            // Cek apakah produk adalah affiliate
            $is_affiliate_product = get_post_meta( $product_id, '_is_affiliate_product', true );
            
            if ( $is_affiliate_product === 'yes' ) {
                $affiliate_products[$cart_item_key] = $cart_item;
            } else {
                $non_affiliate_products[$cart_item_key] = $cart_item;
            }
        }

        // Fungsi untuk menampilkan produk dengan pengelompokan vendor
        function display_products_by_vendor($products, $type = 'regular') {
            // Kelompokkan produk berdasarkan vendor
            $vendor_products = array();
            
            foreach ($products as $cart_item_key => $cart_item) {
                $product_id = $cart_item['product_id'];
                $vendor_id = get_post_field('post_author', $product_id);
                
                if (!isset($vendor_products[$vendor_id])) {
                    $vendor_products[$vendor_id] = array(
                        'vendor_info' => dokan()->vendor->get($vendor_id),
                        'products' => array(),
                        'subtotal' => 0
                    );
                }
                
                $vendor_products[$vendor_id]['products'][$cart_item_key] = $cart_item;
                
                // Hitung subtotal vendor
                $item_subtotal = (float) $cart_item['data']->get_price() * (int) $cart_item['quantity'];
                $vendor_products[$vendor_id]['subtotal'] += $item_subtotal;
            }
            
            // Tampilkan produk per vendor
            foreach ($vendor_products as $vendor_data) {
                $vendor = $vendor_data['vendor_info'];
                $vendor_name = $vendor->get_shop_name();
                $vendor_url = $vendor->get_shop_url();
                $vendor_subtotal = $vendor_data['subtotal'];
                ?>
                
                <div class="vendor-group vendor-group-<?php echo esc_attr($type); ?>">
                    <div class="vendor-header">
                        <a href="<?php echo esc_url($vendor_url); ?>" target="_blank" class="vendor-name">
                            <?php echo esc_html($vendor_name); ?>
                        </a>
                        <span class="vendor-subtotal"><?php echo wc_price($vendor_subtotal); ?></span>
                    </div>
                    
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
                                <th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail image', 'woocommerce' ); ?></span></th>
                                <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                                <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                                <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                                <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($vendor_data['products'] as $cart_item_key => $cart_item) {
                                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                
                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                    ?>
                                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                        <td class="product-remove">
                                            <?php
                                                echo apply_filters(
                                                    'woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $_product->get_name() ) ) ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $_product->get_sku() )
                                                    ),
                                                    $cart_item_key
                                                );
                                            ?>
                                        </td>

                                        <td class="product-thumbnail">
                                            <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                            if ( ! $product_permalink ) {
                                                echo $thumbnail;
                                            } else {
                                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                            }
                                            ?>
                                        </td>

                                        <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( $_product->get_name() );
                                            } else {
                                                echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
                                            }

                                            echo wc_get_formatted_cart_item_data( $cart_item );

                                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                echo wp_kses_post( '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>' );
                                            }
                                            ?>
                                        </td>

                                        <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                            <?php echo WC()->cart->get_product_price( $_product ); ?>
                                        </td>

                                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                            <div class="quantity-buttons">
                                                <button type="button" class="qty-minus" data-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                                <?php
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                                echo $product_quantity;
                                                ?>
                                                <button type="button" class="qty-plus" data-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                            </div>
                                        </td>

                                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                            <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
        }
        ?>

        <?php if ( ! empty( $non_affiliate_products ) ) : ?>
            <h3><?php esc_html_e( 'Regular Produk', 'woocommerce' ); ?></h3>
            <?php display_products_by_vendor($non_affiliate_products, 'regular'); ?>
            
            <div class="cart-actions">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                </button>
                <a href="<?php echo home_url( '/checkout' ); ?>" id="non-affiliate-checkout" class="button cart-confirm-button">Konfirmasi</a>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $affiliate_products ) ) : ?>
            <hr>
            <h3><?php esc_html_e( 'Affiliate Products', 'woocommerce' ); ?></h3>
            <?php display_products_by_vendor($affiliate_products, 'affiliate'); ?>
            
            <div class="cart-actions">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                </button>
                <a href="<?php echo home_url( '/checkout-affiliate' ); ?>" id="affiliate-checkout" class="button cart-confirm-button">Konfirmasi</a>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
</div>

<style>
/* CSS untuk mobile-friendly vendor grouping */
.vendor-group {
    margin-bottom: 30px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.vendor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.vendor-name {
    font-weight: 700;
    font-size: 18px;
    color: #333;
    text-decoration: none;
}

.vendor-name:hover {
    color: #0073aa;
    text-decoration: underline;
}

.vendor-subtotal {
    font-weight: 600;
    font-size: 16px;
    color: #0073aa;
}

/* Tombol kuantitas - PERBAIKAN STYLING */
.quantity-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 0;
}

.qty-minus, .qty-plus {
    width: 32px;
    height: 32px;
    background: #f8f8f8;
    border: 1px solid #ddd;
    cursor: pointer;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    padding: 0;
    font-size: 16px;
    line-height: 1;
    transition: all 0.2s ease;
}

.qty-minus:hover, .qty-plus:hover {
    background: #e8e8e8;
    border-color: #ccc;
}

.quantity {
    margin: 0 5px;
    display: flex;
    align-items: center;
}

.quantity input {
    text-align: center;
    width: 40px;
    height: 32px;
    margin: 0;
    padding: 0 5px;
    border: 1px solid #ddd;
    border-radius: 0;
    -moz-appearance: textfield;
}

.quantity input::-webkit-outer-spin-button,
.quantity input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Perbaikan untuk nama produk di mobile */
.product-name {
    word-wrap: break-word;
    overflow-wrap: break-word;
    max-width: 200px;
}

/* Responsive design */
@media (max-width: 768px) {
    .vendor-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .vendor-group {
        padding: 12px;
        margin-bottom: 20px;
    }
    
    .shop_table {
        font-size: 14px;
    }
    
    .product-thumbnail img {
        width: 60px;
        height: auto;
    }
    
    .cart-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }
    
    .cart-actions .button {
        width: 100%;
        text-align: center;
    }
    
    .product-name {
        max-width: 150px;
    }
    
    /* Perbaikan tombol kuantitas di mobile */
    .quantity-buttons {
        justify-content: flex-start;
    }
    
    .qty-minus, .qty-plus {
        width: 28px;
        height: 28px;
        font-size: 14px;
    }
    
    .quantity input {
        width: 35px;
        height: 28px;
    }
}

@media (max-width: 480px) {
    .vendor-name {
        font-size: 16px;
    }
    
    .vendor-subtotal {
        font-size: 14px;
    }
    
    .shop_table thead {
        display: none;
    }
    
    .shop_table tbody tr {
        display: block;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        position: relative;
    }
    
    .shop_table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border: none;
    }
    
    .shop_table tbody td::before {
        content: attr(data-title);
        font-weight: 600;
        color: #666;
        margin-right: 10px;
        flex: 0 0 40%;
    }
    
    .product-remove {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .product-thumbnail {
        text-align: center;
        margin-bottom: 15px;
        justify-content: center;
    }
    
    .product-name {
        max-width: 100%;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .product-name::before {
        margin-bottom: 5px;
    }
    
    .quantity-buttons {
        justify-content: center;
    }
    
    /* Perbaikan khusus untuk kolom kuantitas di mobile */
    .product-quantity td {
        justify-content: space-between;
    }
    
    .product-quantity td::before {
        flex: 0 0 30%;
    }
}

/* Improved mobile experience */
.cart-actions {
    margin-top: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.cart-confirm-button {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 12px 24px;
    font-weight: 600;
}

.cart-confirm-button:hover {
    background: #45a049;
    color: white;
}

.vendor-group-regular {
    border-left: 4px solid #0073aa;
}

.vendor-group-affiliate {
    border-left: 4px solid #ff6b00;
}
div.quantity-buttons div.quantity {
    padding: 0 !important;
}

td.product-name a {
    display: inline-block;
    max-width: 24ch;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Fungsi untuk update kuantitas
    function updateQuantity(key, newQuantity) {
        // Pastikan nilai tidak kurang dari 0
        if (newQuantity < 0) newQuantity = 0;
        
        // Update nilai input
        $('input[name="cart[' + key + '][qty]"]').val(newQuantity);
        
        // Submit form untuk update keranjang
        $('#cart-form').append('<input type="hidden" name="update_cart" value="Update cart">');
        $('#cart-form').submit();
    }
    
    // Tombol tambah
    $('.qty-plus').on('click', function(e) {
        e.preventDefault();
        var key = $(this).data('key');
        var input = $('input[name="cart[' + key + '][qty]"]');
        var currentVal = parseInt(input.val()) || 0;
        var maxVal = parseInt(input.attr('max')) || Infinity;
        
        if (currentVal < maxVal) {
            updateQuantity(key, currentVal + 1);
        }
    });
    
    // Tombol kurang
    $('.qty-minus').on('click', function(e) {
        e.preventDefault();
        var key = $(this).data('key');
        var input = $('input[name="cart[' + key + '][qty]"]');
        var currentVal = parseInt(input.val()) || 0;
        var minVal = parseInt(input.attr('min')) || 0;
        
        if (currentVal > minVal) {
            updateQuantity(key, currentVal - 1);
        }
    });
    
    // Pastikan nilai input tetap valid saat diubah manual
    $('.quantity input').on('change', function() {
        var key = $(this).attr('name').match(/cart\[(.*)\]\[qty\]/)[1];
        var currentVal = parseInt($(this).val()) || 0;
        var minVal = parseInt($(this).attr('min')) || 0;
        var maxVal = parseInt($(this).attr('max')) || Infinity;
        
        if (currentVal < minVal) {
            $(this).val(minVal);
            updateQuantity(key, minVal);
        } else if (currentVal > maxVal) {
            $(this).val(maxVal);
            updateQuantity(key, maxVal);
        } else {
            updateQuantity(key, currentVal);
        }
    });
    
    // Hapus elemen dengan kelas .variation setelah dokumen dimuat
    function removeVariationElements() {
        $('dl.variation').each(function() {
            $(this).remove();
        });
    }
    
    // Jalankan fungsi setelah dokumen selesai dimuat
    removeVariationElements();
    
    // Jika ada konten yang dimuat secara dinamis, pantau perubahan DOM
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                    removeVariationElements();
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});
</script>

<?php
//return ob_get_clean(); // Ambil buffer output dan kosongkan
?>