<?php

/**
 * Function to remove and add action hook woocommerce
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

// Add Countdown
add_action('cepatlakoo_summary_product_highlight', function () {
    cepatlakoo_set_countdown_scarcity($ct_id = get_the_ID());
}, 23);
add_action('woocommerce_single_product_summary', function () {
    cepatlakoo_set_countdown_scarcity($ct_id = get_the_ID());
}, 25);

if (!function_exists('cepatlakoo_init_wc')) {
    function cepatlakoo_init_wc()
    {
        // Cepatlakoo Custom Ordering
        // add_action( 'cepatlakoo_before_shop_order', 'woocommerce_catalog_ordering', 30 );
        // add_action( 'cepatlakoo_after_shop_order', 'woocommerce_catalog_ordering', 30 );

        // // Cepatlakoo Custom Pagination
        // add_action( 'cepatlakoo_before_shop_pagination', 'woocommerce_pagination', 10 );
        // add_action( 'cepatlakoo_after_shop_pagination', 'woocommerce_pagination', 10 );

        // Cepatlakoo Custom Rating
        // add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 100 );

        // Cepatlakoo Before Quick View Hook
        add_action('cepatlakoo_before_quick_view', 'woocommerce_show_product_images', 10);
        add_action('cepatlakoo_before_quick_view', 'cepatlakoo_view_full_detail', 15);

        // Cepatlakoo Quick View Hook
        add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_title', 5);
        add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_rating', 10);
        add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_price', 15);
        add_action( 'cepatlakoo_summary_quick_view', function(){
            cepatlakoo_set_countdown_scarcity($ct_id = get_the_ID(), 'quickview' );
        }, 23 );

        // Cepatlakoo Single Product Highlight Hook
        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_title', 5);
        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_rating', 10);
        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_price', 15);
        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_excerpt', 20);

        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_add_to_cart', 25);
        add_action('cepatlakoo_summary_product_highlight', 'woocommerce_template_single_meta', 40);
        add_action('cepatlakoo_summary_product_highlight', 'cepatlakoo_social_sharing_product', 50);

        add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_add_to_cart', 25);
        // add_action( 'cepatlakoo_summary_quick_view', 'cepatlakoo_social_sharing_product', 50 );

        // Add meta counter to Single Product Page
        add_action('woocommerce_single_product_summary', 'cepatlakoo_render_meta_counter', 6);

        // Add Social Sharing to Single Product Page
        add_action('woocommerce_single_product_summary', 'cepatlakoo_social_sharing_product', 50);

        // Cepatlakoo Remove Upsell
        add_action('cepatlakoo_button_add_cart', 'woocommerce_template_loop_add_to_cart', 10);

        // Cepatlakoo Panel Hover
        add_action('cepatlakoo_panel_addto_cart', 'cepatlakoo_panel_view_add_to_cart', 5);

        // Cepatlakoo Remove Taxonomy Description
        remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);

        // Add Function to Hook
        add_action('cepatlakoo_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
        add_action('woocommerce_before_checkout_form', 'cepatlakoo_stepbystep');
        add_action('woocommerce_before_cart', 'cepatlakoo_stepbystep');
        // add_action( 'woocommerce_before_single_product', 'cepatlakoo_increase_meta_view', 10 );

        // Remove Button in Shop v.1.1.1
        // remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');


    }
}
add_filter('init', 'cepatlakoo_init_wc');

/**
 * Function to set full gallery image size
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.2.1
 */
if (!function_exists('cepatlakoo_gallery_image_size')) {
    function cepatlakoo_gallery_image_size()
    {
        return 'full';
    }
}
add_filter('woocommerce_gallery_image_size', 'cepatlakoo_gallery_image_size');

/**
 * Function to set 3 column grid of shop page loop
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_loop_columns')) {
    function cepatlakoo_loop_columns()
    {
        global $cl_options;

        return isset($cl_options['cepatlakoo_shop_columns']) ? $cl_options['cepatlakoo_shop_columns'] : 3;
    }
}
add_filter('loop_shop_columns', 'cepatlakoo_loop_columns');

/**
 * Function to get 404 url
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.1
 */
if (!function_exists('cepatlakoo_get_notfound_url')) {
    function cepatlakoo_get_notfound_url()
    {
        return get_site_url() . '/404/';
    }
}

/**
 * Function to customize woocomerce product search widget
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_custom_product_searchform')) {
    function cepatlakoo_custom_product_searchform($form)
    { ?>
        <div class="search-widget">
            <div class="search-form">
                <form id="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group">
                        <input type="text" id="s" class="search-field" placeholder="<?php echo esc_html_e('Type search product and hit enter...', 'cepatlakoo'); ?>" value="<?php esc_attr(the_search_query()); ?>" name="s" />
                        <input type="submit" class="btn regular-btn submit-btn primary-bg">
                        <input type="hidden" name="post_type" value="product" />
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
add_filter('get_product_search_form', 'cepatlakoo_custom_product_searchform');

/**
 * Function to display cart & profile menu ajax
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 * Source : https://gaurangdabhi.wordpress.com/2015/07/24/how-to-update-cart-using-ajax-woocommerce/
 */
if (!function_exists('cepatlakoo_header_add_to_cart_fragment')) {
    function cepatlakoo_header_add_to_cart_fragment($fragments)
    {
        global $woocommerce;
        if (class_exists('WooCommerce')) : 
            ob_start();?>
            <div class="user-carts">
                <span class="cart-counter">
                    <?php if (WC()->cart->cart_contents_count > 0) : ?>
                        <svg class="not-empty" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                        </svg>

                        <label><?php echo WC()->cart->cart_contents_count; ?></label>
                    <?php else : ?>
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                        </svg>

                        <label><?php echo WC()->cart->cart_contents_count; ?></label>
                    <?php endif; ?>
                </span>
            </div>
            <?php
            $fragments['div.user-carts'] = ob_get_clean();
        endif;

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'cepatlakoo_header_add_to_cart_fragment',10,1);

if (!function_exists('cepatlakoo_sidebar_cart_fragment')) {
    function cepatlakoo_sidebar_cart_fragment($fragments)
    {
        global $woocommerce, $cl_options;
        $style = (isset($cl_options['cepatlakoo_cart_type']) && !empty($cl_options['cepatlakoo_cart_type'])) ? $cl_options['cepatlakoo_cart_type'] : 'sidebar';
        $wc_ajax_enable = !empty( get_option('woocommerce_enable_ajax_add_to_cart') ) ? get_option('woocommerce_enable_ajax_add_to_cart') : 'no';

        if (class_exists('WooCommerce')) :
            ob_start(); ?>
            <div class="cart-holders">
                <?php if ($style == 'sidebar') : ?>
                    <div id="close-cart-overlay" class="close-cart"></div>
                    <div id="close-cart-btn" class="close-cart">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.416 10L20 18.584L18.584 20L10 11.416L1.41602 20L0 18.584L8.58398 10L0 1.41602L1.41602 0L10 8.58398L18.584 0L20 1.41602L11.416 10Z" fill="#C4C4C4" />
                        </svg>
                    </div>
                <?php endif; ?>
                <?php if ($woocommerce->cart->cart_contents_count > 0) : ?>
                    <div class="cart-header modal-head">
                        <div class="cart-icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>
                        </div>
                        <div class="cart-info">
                            <?php if ($style == 'popup') : ?>
                                <span class="cart-count"><?php esc_html_e('Item(s) successfully added to cart', 'cepatlakoo'); ?> </span>
                            <?php else : ?>
                                <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                                <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($style == 'popup') : ?>
                            <div class="close-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="cart-header modal-head">
                        <div class="cart-icon empty">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>
                        </div>
                        <div class="cart-info">
                            <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                            <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                        </div>
                        <?php if ($style == 'popup') : ?>
                            <div class="close-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php do_action('cepatlakoo_before_popup_cart'); ?>
                <div class="cart-content">
                    <?php ($style == 'popup' && $wc_ajax_enable == 'yes') ? cepatlakoo_cart_popup() : woocommerce_mini_cart(); ?>

                    <div class="cart-footer primary-bg">
                        <a href="#" class="cart-btn btn wc-forward <?php echo ($style == 'popup') ? 'close-cart' : ''; ?>"><?php esc_html_e('Continue Shopping', 'cepatlakoo'); ?></a>

                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-btn btn wc-forward"><?php esc_html_e('View Cart', 'cepatlakoo'); ?></a>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn btn checkout">
                            <?php
                            $proceed_to_checkout_text = isset($cl_options['cepatlakoo_proceed_to_checkout_text']) ? $cl_options['cepatlakoo_proceed_to_checkout_text'] : __('Konfirmasi Pesanan', 'cepatlakoo');
                            echo $proceed_to_checkout_text;
                            ?>
                            <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="checkout-icon">
                                <path d="M17.6865 5.42385L12.5012 0.238643C12.1831 -0.0795476 11.6672 -0.0795476 11.349 0.238643C11.0308 0.556833 11.0308 1.07272 11.349 1.39091L15.1433 5.18521L2.44778 5.18521C2.44663 5.18521 2.44548 5.1852 2.44433 5.1852H0.814777C0.364788 5.1852 0 5.54999 0 5.99998C0 6.44997 0.364788 6.81476 0.814777 6.81476L15.1433 6.81477L11.349 10.6091C11.0308 10.9273 11.0308 11.4431 11.349 11.7613C11.6672 12.0795 12.1831 12.0795 12.5012 11.7613L17.6865 6.57612C18.0046 6.25793 18.0046 5.74204 17.6865 5.42385Z" fill="white" />
                            </svg>

                        </a>
                    </div>
                </div>
                <?php
                do_action('cepatlakoo_after_popup_cart');
                WC()->session->__unset('cl_cart_last_product');
                ?>
            </div>
            <?php
            $fragments['div.cart-holders'] = ob_get_clean();
        endif;

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'cepatlakoo_sidebar_cart_fragment',10,1);

/**
 * Function to display cart & profile menu
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_display_cart_profile_menu')) {
    function cepatlakoo_display_cart_profile_menu()
    {
        global $woocommerce, $cl_options;

        $cepatlakoo_cart_option = !empty($cl_options['cepatlakoo_shoping_cart']) ? $cl_options['cepatlakoo_shoping_cart'] : '';
        $cepatlakoo_whatsapp_only = !empty($cl_options['cepatlakoo_single_product_button_type']) ? $cl_options['cepatlakoo_single_product_button_type'] : '';

        if (!$cepatlakoo_cart_option || $cepatlakoo_whatsapp_only != 'whatsapp') : // shopping cart is disable
            if (class_exists('WooCommerce')) : ?>
                <div class="user-carts">
                    <span class="cart-counter">
                        <?php if ($woocommerce->cart->cart_contents_count > 0) : ?>
                            <svg class="not-empty" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>

                            <label><?php echo WC()->cart->cart_contents_count; ?></label>
                        <?php else : ?>
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>

                            <label><?php echo WC()->cart->cart_contents_count; ?></label>
                        <?php endif; ?>
                    </span>
                </div>
        <?php endif;
        endif;
    }
}

/**
 * Function to display fragment cart
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_sidebar_cart_content')) {
    function cepatlakoo_sidebar_cart_content()
    {
        global $woocommerce, $cl_options;

        $style = (isset($cl_options['cepatlakoo_cart_type']) && !empty($cl_options['cepatlakoo_cart_type'])) ? $cl_options['cepatlakoo_cart_type'] : 'sidebar';
        $wc_ajax_enable = !empty( get_option('woocommerce_enable_ajax_add_to_cart') ) ? get_option('woocommerce_enable_ajax_add_to_cart') : 'no';
        ?>
        <?php if (class_exists('WooCommerce') && $style == 'sidebar') :  ?>
            <div class="cart-holders">
                <?php if ($woocommerce->cart->cart_contents_count > 0) : ?>
                    <div class="cart-header">
                        <div class="cart-icon">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>
                        </div>
                        <div class="cart-info">
                            <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                            <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="cart-header">
                        <div class="cart-icon empty">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                            </svg>
                        </div>
                        <div class="cart-info">
                            <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                            <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="cart-content">
                    <?php ($style == 'popup' && $wc_ajax_enable == 'yes') ? cepatlakoo_cart_popup() : woocommerce_mini_cart(); ?>
                    <div class="cart-footer primary-bg">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-btn btn wc-forward"><?php esc_html_e('View Cart', 'cepatlakoo'); ?></a>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn btn checkout">
                            <?php
                            $proceed_to_checkout_text = $cl_options['cepatlakoo_proceed_to_checkout_text'];
                            echo $proceed_to_checkout_text;
                            ?>
                            <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="checkout-icon">
                                <path d="M17.6865 5.42385L12.5012 0.238643C12.1831 -0.0795476 11.6672 -0.0795476 11.349 0.238643C11.0308 0.556833 11.0308 1.07272 11.349 1.39091L15.1433 5.18521L2.44778 5.18521C2.44663 5.18521 2.44548 5.1852 2.44433 5.1852H0.814777C0.364788 5.1852 0 5.54999 0 5.99998C0 6.44997 0.364788 6.81476 0.814777 6.81476L15.1433 6.81477L11.349 10.6091C11.0308 10.9273 11.0308 11.4431 11.349 11.7613C11.6672 12.0795 12.1831 12.0795 12.5012 11.7613L17.6865 6.57612C18.0046 6.25793 18.0046 5.74204 17.6865 5.42385Z" fill="white" />
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
        WC()->session->__unset('cl_cart_last_product');
    }
}

/**
 * Function to hide default page title woocommerce
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_hide_page_title')) {
    function cepatlakoo_hide_page_title()
    {
        return false;
    }
}
add_filter('woocommerce_show_page_title', 'cepatlakoo_hide_page_title');

/**
 * Function to setup woocommerce page title
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_shop_page_title')) {
    function cepatlakoo_shop_page_title($page_title)
    {
        if ($page_title) :
            if (is_product_category()) :
                global $wp_query;
                $cat = $wp_query->get_queried_object();
                $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id); ?>
                <div id="page-title" style="background-image: url(<?php echo $image; ?>)">
                    <h2><?php echo $page_title; ?></h2>
                    <?php if (is_tax(array('product_cat', 'product_tag')) && 0 === absint(get_query_var('paged'))) :
                        $description = wc_format_content(term_description());
                        if ($description) : ?>
                            <div class="term-description"><?php echo $description; ?></div>
                    <?php endif;
                    endif; ?>
                </div>
            <?php endif; ?>
        <?php endif;
    }
}
add_filter('woocommerce_page_title', 'cepatlakoo_shop_page_title');

/**
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
//Related products limit
if (!function_exists('cepatlakoo_related_products_limit')) {
    function cepatlakoo_related_products_limit()
    {
        global $product, $cepatlakoo_option;
        $orderby = '';
        $columns = $cepatlakoo_option['cepatlakoo_related_product_limit'];
        $related = $product->get_related($cepatlakoo_option['cepatlakoo_related_product_limit']);
        $args = array(
            'post_type'           => 'product',
            'no_found_rows'       => 1,
            'posts_per_page'      => $cepatlakoo_option['cepatlakoo_related_product_limit'],
            'ignore_sticky_posts' => 1,
            'orderby'             => $orderby,
            'post__in'            => $related,
            'post__not_in'        => array($product->get_id())
        );
        return $args;
    }
}
add_filter('woocommerce_related_products_args', 'cepatlakoo_related_products_limit');

/**
 * Function to define product image sizes
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_woocommerce_image_dimensions')) {
    function cepatlakoo_woocommerce_image_dimensions()
    {
        global $pagenow;

        if (!isset($_GET['activated']) || $pagenow != 'themes.php') {
            return;
        }
        $catalog = array(
            'width'     => '300',   // px
            'height'    => '300',   // px
            'crop'      => 1        // true
        );
        $single = array(
            'width'     => '600',   // px
            'height'    => '600',   // px
            'crop'      => 1        // true
        );
        $thumbnail = array(
            'width'     => '180',   // px
            'height'    => '180',   // px
            'crop'      => 1        // true
        );
        // Image sizes
        update_option('shop_catalog_image_size', $catalog);       // Product category thumbs
        update_option('shop_single_image_size', $single);         // Single product image
        update_option('shop_thumbnail_image_size', $thumbnail);   // Image gallery thumbs
    }
}
add_action('after_switch_theme', 'cepatlakoo_woocommerce_image_dimensions', 1);

/**
 * Function to display featured product
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_featured_product')) {
    function cepatlakoo_featured_product()
    { ?>
        <section id="feature-brands-widget" class="homepage-widget shopping-widget">
            <div class="container fullwidth clearfix">
                <div id="feature-brands">
                    <ul>
                        <?php
                        $args_featured_product = array(
                            'post_type'      => 'product',
                            'post_status'    => 'publish',
                            'order'          => 'DESC',
                            'orderby'        => 'meta_value',
                            'meta_key'       => '_featured',
                            'posts_per_page' => 5
                        );
                        $cepatlakoo_count = 1;

                        $cepatlakoo_featured_product = new WP_Query();
                        $cepatlakoo_featured_product->query($args_featured_product);

                        if ($cepatlakoo_featured_product->have_posts()) :
                            while ($cepatlakoo_featured_product->have_posts()) : $cepatlakoo_featured_product->the_post();
                        ?>
                                <li>
                                    <div class="thumbnail">
                                        <?php
                                        if ($cepatlakoo_count == 1) {
                                            the_post_thumbnail('cepatlakoo-featured-post-big', array('alt' => get_the_title(), 'title' => get_the_title()));
                                        } else {
                                            the_post_thumbnail('cepatlakoo-featured-post', array('alt' => get_the_title(), 'title' => get_the_title()));
                                        }
                                        ?>
                                    </div>
                                    <div class="detail">
                                        <div class="table">
                                            <div class="tablecell">
                                                <div class="brand-wrap">
                                                    <h3><a href="<?php the_permalink(); ?>" class="brand-icon"><?php the_title(); ?></a></h3>
                                                    <a href="<?php the_permalink(); ?>" class="btn regular-btn no-fill-btn"><?php esc_html_e('Shop now', 'cepatlakoo'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="overlay-link" href="<?php the_permalink(); ?>"></a>
                                    </div>
                                </li>
                        <?php
                                $cepatlakoo_count++;
                            endwhile;;
                        endif;

                        wp_reset_postdata();
                        ?>
                    </ul>
                </div>
            </div>
        </section>
    <?php
    }
}

/**
 * Function to display button View Full Details
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_view_full_detail')) {
    function cepatlakoo_view_full_detail()
    { ?>
        <p class="buttons">
            <a href="<?php the_permalink(); ?>" class="button checkout wc-forward"><?php esc_html_e('View Full Details', 'cepatlakoo'); ?></a>
        </p>
        <?php
    }
}

/**
 * Function to display cepatlakoo single style
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_single_style')) {
    function cepatlakoo_single_style($id)
    {
        global $cl_options;

        $cepatlakoo_single_sidebar_opt = !empty($cl_options['cepatlakoo_single_sidebar_opt']) ? $cl_options['cepatlakoo_single_sidebar_opt'] : '';

        $cepatlakoo_sidebar_options = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_sidebar_options', true)) ? esc_attr(get_post_meta(get_the_ID(), 'cepatlakoo_sidebar_options', true)) : '1';

        if (!empty($cepatlakoo_single_sidebar_opt) || $cepatlakoo_single_sidebar_opt == 0) :
            if ($cepatlakoo_single_sidebar_opt == 0) :
                if ($cepatlakoo_sidebar_options == 1) :
                    get_sidebar('woocommerce-detail');
                    add_filter('woocommerce_output_related_products_args', 'cepatlakoo_related_products_count');
                    function cepatlakoo_related_products_count($args)
                    {
                        $args['posts_per_page'] = 3;
                        $args['columns'] = 3;
                        return $args;
                    }
        ?>
                    <div id="contentarea">
                        <div id="products-area">
                            <div class="product-content-area">
                                <div class="product-list">
                                    <?php
                                    if (have_posts()) :
                                        woocommerce_content();
                                    else : ?>
                                        <p><?php echo esc_html_e('The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($cepatlakoo_sidebar_options == 2) : ?>
                    <div class="no-sidebar">
                        <div id="products-area">
                            <div class="product-content-area">
                                <div class="product-list">
                                    <?php
                                    if (have_posts()) :
                                        woocommerce_content();
                                    else : ?>
                                        <p><?php echo esc_html_e('The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif;
            elseif ($cepatlakoo_single_sidebar_opt == 1) : // With Sidebar
                get_sidebar('woocommerce-detail');
                add_filter('woocommerce_output_related_products_args', 'cepatlakoo_related_products_count');
                function cepatlakoo_related_products_count($args)
                {
                    $args['posts_per_page'] = 3;
                    $args['columns'] = 3;
                    return $args;
                }
                ?>
                <div id="contentarea">
                    <div id="products-area">
                        <div class="product-content-area">
                            <div class="product-list">
                                <?php
                                if (have_posts()) :
                                    woocommerce_content();
                                else : ?>
                                    <p><?php echo esc_html_e('The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($cepatlakoo_single_sidebar_opt == 2) : // Without SIidebar 
            ?>
                <div class="no-sidebar">
                    <div id="products-area">
                        <div class="product-content-area">
                            <div class="product-list">
                                <?php
                                if (have_posts()) :
                                    woocommerce_content();
                                else : ?>
                                    <p><?php echo esc_html_e('The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;
        else :
            get_sidebar('woocommerce-detail');
            add_filter('woocommerce_output_related_products_args', 'cepatlakoo_related_products_count');
            function cepatlakoo_related_products_count($args)
            {
                $args['posts_per_page'] = 3;
                $args['columns'] = 3;
                return $args;
            } ?>
            <div id="contentarea">
                <div id="products-area">
                    <div class="product-content-area">
                        <div class="product-list">
                            <?php
                            if (have_posts()) :
                                woocommerce_content();
                            else : ?>
                                <p><?php echo esc_html_e('The page you\'re looking for is not available. The page may have been deleted or unpublished.', 'cepatlakoo'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}

/**
 * Function to display add to cart ajax in quick view
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_quick_view_add_to_cart')) {
    function cepatlakoo_quick_view_add_to_cart()
    {
        global $product;

        do_action('woocommerce_before_add_to_cart_form');

        /**
         * @since 3.0.0.
         */
        do_action('woocommerce_before_add_to_cart_quantity');

        // Quantity
        if (!$product->is_sold_individually() && !$product->is_type('variable')) {
            woocommerce_quantity_input(array(
                'min_value'   => apply_filters('woocommerce_quantity_input_min', 1, $product),
                'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product),
                'input_value' => (isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1)
            ));
        }

        /**
         * @since 3.0.0.
         */
        do_action('woocommerce_after_add_to_cart_quantity');

        // Button
        if ($product->is_type('simple')) : // Single Type Product
            echo apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(isset($quantity) ? $quantity : 1),
                    esc_attr($product->get_id()),
                    esc_attr($product->get_sku()),
                    esc_attr(isset($class) ? $class : 'add_to_cart_button ajax_add_to_cart'),
                    esc_html($product->add_to_cart_text())
                ),
                $product
            );
        elseif ($product->is_type('grouped')) : // Variable Type Product 
        ?>
            <a href="<?php echo esc_url($product->get_product_url()); ?>" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo esc_html($product->get_button_text()); ?></a>
        <?php elseif ($product->is_type('external')) : // External Type Product 
        ?>
            <a href="<?php echo esc_url($product->get_product_url()); ?>" rel="nofollow" class="single_add_to_cart_button button alt">
                <?php if ($product->get_button_text()) :
                    esc_html_e($product->get_button_text());
                else :
                    esc_html_e('Buy Product', 'cepatlakoo');
                endif; ?>
            </a>
        <?php else : // Else Product Type
            echo apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(isset($quantity) ? $quantity : 1),
                    esc_attr($product->get_id()),
                    esc_attr($product->get_sku()),
                    esc_attr(isset($class) ? $class : 'add_to_cart_button'),
                    esc_html($product->add_to_cart_text())
                ),
                $product
            );
        endif;

        do_action('woocommerce_after_add_to_cart_form');
    }
}

/**
 * Function to display panel hover
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_panel_view_add_to_cart')) {
    function cepatlakoo_panel_view_add_to_cart()
    {
        global $product;
        global $cl_options;

        if (!isset($cl_options['cepatlakoo_quickview_status']) || $cl_options['cepatlakoo_quickview_status'] == true) : ?>
            <a href="<?php echo esc_url(get_template_directory_uri()) . '/includes/quick-view.php' ?>" onclick="return false;" class="quick-view-btn cepatlakoo-ajax-quick-view" data-product="<?php echo $product->get_id() ?>" title="<?php esc_html_e('Quick view product', 'woocommerce'); ?>"><?php esc_html_e('Quick view', 'woocommerce'); ?></a>
        <?php
        endif;
    }
}

/**
 * Function to display social sharing product
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_social_sharing_product')) {
    function cepatlakoo_social_sharing_product()
    { ?>
        <div class="social-sharing">
            <h4 class="widget-title"><?php esc_html_e('Share this product', 'cepatlakoo'); ?></h4>
            <ul>
                <li><a title="<?php esc_html_e('Facebook Share', 'cepatlakoo'); ?>" target="_blank" href="<?php echo esc_url('https://www.facebook.com/sharer.php?u=' . urlencode(get_permalink(get_the_ID()))); ?>&t=<?php echo esc_attr(get_the_title(get_the_ID())); ?>">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.5 13.75V8.75C22.5 7.37 23.62 6.25 25 6.25H27.5V0H22.5C18.3575 0 15 3.3575 15 7.5V13.75H10V20H15V40H22.5V20H27.5L30 13.75H22.5Z" fill="white" />
                        </svg>
                    </a></li>
                <li><a title="<?php esc_html_e('Twitter Share', 'cepatlakoo'); ?>" target="_blank" href="<?php echo esc_url('http://twitter.com/share?url=' . urlencode(get_permalink(get_the_ID()))); ?>&text=<?php echo esc_attr(get_the_title(get_the_ID())); ?>&count=horizontal">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                                <path d="M12.5016 36.2583C27.5968 36.2583 35.8515 23.7524 35.8515 12.9083C35.8515 12.5531 35.8442 12.1994 35.8283 11.8475C37.4305 10.6891 38.8233 9.24345 39.922 7.59794C38.4516 8.25162 36.869 8.69138 35.2091 8.89005C36.9035 7.87382 38.2044 6.26676 38.8178 4.35086C37.2321 5.2908 35.4761 5.97379 33.6066 6.34275C32.1091 4.74759 29.9769 3.74997 27.616 3.74997C23.0842 3.74997 19.4089 7.42521 19.4089 11.9555C19.4089 12.5998 19.4809 13.226 19.6216 13.8269C12.8009 13.4836 6.75264 10.2182 2.70539 5.25235C2.00074 6.46512 1.59424 7.87412 1.59424 9.37742C1.59424 12.2244 3.04322 14.7381 5.24629 16.2085C3.89985 16.167 2.6352 15.7974 1.52954 15.1822C1.52832 15.2167 1.52832 15.2502 1.52832 15.2869C1.52832 19.2615 4.357 22.58 8.1125 23.3319C7.42281 23.5196 6.6971 23.6206 5.9485 23.6206C5.42054 23.6206 4.90602 23.5687 4.40583 23.4726C5.45076 26.7334 8.48055 29.1061 12.0725 29.1727C9.26363 31.3742 5.72511 32.6856 1.87958 32.6856C1.21796 32.6856 0.564271 32.6477 -0.078125 32.572C3.55378 34.8999 7.86684 36.2586 12.5019 36.2586" fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="40" height="40" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a></li>
                <li>
                    <?php $cepatlakoo_pinterestimage = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
                    <a title="<?php esc_html_e('Pinterest Share', 'cepatlakoo'); ?>" target="_blank" href="<?php echo esc_url('http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink(get_the_ID()))); ?>&media=<?php echo esc_url($cepatlakoo_pinterestimage[0]); ?>&description=<?php echo esc_attr(get_the_title(get_the_ID())); ?>" count-layout="vertical">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0)">
                                <path d="M20.5433 0C9.57833 0.00166667 3.75 7.02667 3.75 14.6867C3.75 18.2383 5.735 22.67 8.91333 24.075C9.82 24.4833 9.7 23.985 10.48 21.0017C10.5417 20.7533 10.51 20.5383 10.31 20.3067C5.76667 15.0517 9.42333 4.24833 19.895 4.24833C35.05 4.24833 32.2183 25.2183 22.5317 25.2183C20.035 25.2183 18.175 23.2583 18.7633 20.8333C19.4767 17.945 20.8733 14.84 20.8733 12.7583C20.8733 7.51167 13.0567 8.29 13.0567 15.2417C13.0567 17.39 13.8167 18.84 13.8167 18.84C13.8167 18.84 11.3017 29 10.835 30.8983C10.045 34.1117 10.9417 39.3133 11.02 39.7617C11.0683 40.0083 11.345 40.0867 11.5 39.8833C11.7483 39.5583 14.7883 35.2217 15.64 32.0867C15.95 30.945 17.2217 26.3117 17.2217 26.3117C18.06 27.825 20.4767 29.0917 23.0517 29.0917C30.7117 29.0917 36.2483 22.3583 36.2483 14.0033C36.2217 5.99333 29.3667 0 20.5433 0V0Z" fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="40" height="40" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                </li>
                <li class="whatsapp"><a title="<?php esc_html_e('Whatsapp Share', 'cepatlakoo'); ?>" target="_blank" href="<?php echo esc_url('https://wa.me/?text=' . str_replace(' ', '%20', get_the_title()) . ' ' . urlencode(get_permalink(get_the_ID()))); ?>">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M33.2648 6.68014C29.7565 3.16772 25.0908 1.23253 20.1202 1.23047C9.8777 1.23047 1.54182 9.56612 1.5377 19.8113C1.53633 23.0864 2.39189 26.2834 4.0181 29.1014L1.38184 38.7305L11.2327 36.1464C13.947 37.627 17.0028 38.4073 20.1126 38.4082H20.1204C30.3617 38.4082 38.6985 30.0719 38.7024 19.8262C38.7044 14.8608 36.7734 10.1923 33.2648 6.68014ZM20.1202 35.27H20.1138C17.3425 35.2689 14.6245 34.5241 12.2528 33.1171L11.6891 32.7823L5.84343 34.3158L7.40372 28.6164L7.03636 28.0321C5.49026 25.573 4.67384 22.7307 4.67522 19.8125C4.67842 11.2969 11.6071 4.3689 20.1263 4.3689C24.2517 4.37027 28.1297 5.97885 31.0456 8.89824C33.9616 11.8176 35.5665 15.6981 35.5651 19.8251C35.5614 28.3413 28.6332 35.27 20.1202 35.27V35.27ZM28.592 23.7025C28.1278 23.47 25.845 22.3471 25.4192 22.1919C24.994 22.037 24.6841 21.9598 24.3748 22.4245C24.0652 22.8891 23.1755 23.9351 22.9045 24.2448C22.6335 24.5547 22.363 24.5936 21.8986 24.361C21.4342 24.1287 19.9382 23.6382 18.1646 22.0564C16.7844 20.8253 15.8527 19.3048 15.5817 18.8402C15.3111 18.3751 15.5794 18.148 15.7854 17.8926C16.288 17.2684 16.7913 16.6141 16.946 16.3044C17.101 15.9945 17.0234 15.7233 16.9071 15.4909C16.7913 15.2586 15.8627 12.9732 15.4759 12.0433C15.0987 11.1383 14.7163 11.2605 14.4311 11.2463C14.1605 11.2328 13.8509 11.2301 13.5412 11.2301C13.2317 11.2301 12.7287 11.3461 12.3029 11.8112C11.8774 12.2761 10.6781 13.3992 10.6781 15.6846C10.6781 17.97 12.3418 20.1778 12.5739 20.4877C12.806 20.7976 15.8481 25.4874 20.5056 27.4983C21.6134 27.9771 22.4781 28.2626 23.1526 28.4766C24.265 28.83 25.2769 28.7801 26.077 28.6606C26.9692 28.5271 28.8238 27.5372 29.2111 26.4528C29.5979 25.3681 29.5979 24.4386 29.4817 24.2448C29.3658 24.0511 29.0562 23.9351 28.592 23.7025V23.7025Z" fill="white" />
                        </svg>

                    </a></li>
            </ul>
        </div>
    <?php
    }
}

/**
 * Function to get custom image size
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_get_product_thumbnail')) {
    function cepatlakoo_get_product_thumbnail($size = 'large')
    {
        global $post, $woocommerce;
        $output = '<div class="col-lg-4">';
        if (has_post_thumbnail()) {
            $output .= get_the_post_thumbnail($post->ID, $size);
        } else {
            $output .= wc_placeholder_img($size);
        }
        $output .= '</div>';
        return $output;
    }
}

/**
 * Function to display contact buttons on product page
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_cart_custom_button')) {
    function cepatlakoo_cart_custom_button()
    {
        global $cl_options;

        $cepatlakoo_cart_option = !empty($cl_options['cepatlakoo_shoping_cart']) ? $cl_options['cepatlakoo_shoping_cart'] : '';
        $cepatlakoo_cart_sms = !empty($cl_options['cepatlakoo_cart_sms']) ? $cl_options['cepatlakoo_cart_sms'] : '';
        $cepatlakoo_cart_sms_message = !empty($cl_options['cepatlakoo_cart_sms_message']) ? $cl_options['cepatlakoo_cart_sms_message'] : '';
        $cepatlakoo_cart_sms_text = !empty($cl_options['cepatlakoo_cart_sms_text']) ? $cl_options['cepatlakoo_cart_sms_text'] : '';
        $cepatlakoo_cart_sms_event = !empty($cl_options['cepatlakoo_cart_sms_event']) ? $cl_options['cepatlakoo_cart_sms_event'] : '';
        $cepatlakoo_cart_line = !empty($cl_options['cepatlakoo_cart_line']) ? $cl_options['cepatlakoo_cart_line'] : '';
        $cepatlakoo_cart_line_text = !empty($cl_options['cepatlakoo_cart_line_text']) ? $cl_options['cepatlakoo_cart_line_text'] : '';
        $cepatlakoo_cart_line_event = !empty($cl_options['cepatlakoo_cart_line_event']) ? $cl_options['cepatlakoo_cart_line_event'] : '';
        $cepatlakoo_cart_phone = !empty($cl_options['cepatlakoo_cart_phone']) ? $cl_options['cepatlakoo_cart_phone'] : '';
        $cepatlakoo_cart_phone_text = !empty($cl_options['cepatlakoo_cart_phone_text']) ? $cl_options['cepatlakoo_cart_phone_text'] : '';
        $cepatlakoo_cart_phone_event = !empty($cl_options['cepatlakoo_cart_phone_event']) ? $cl_options['cepatlakoo_cart_phone_event'] : '';
        $cepatlakoo_cart_telegram = !empty($cl_options['cepatlakoo_cart_telegram']) ? $cl_options['cepatlakoo_cart_telegram'] : '';
        $cepatlakoo_cart_telegram_text = !empty($cl_options['cepatlakoo_cart_telegram_text']) ? $cl_options['cepatlakoo_cart_telegram_text'] : '';
        $cepatlakoo_cart_telegram_event = !empty($cl_options['cepatlakoo_cart_telegram_event']) ? $cl_options['cepatlakoo_cart_telegram_event'] : '';
        $cepatlakoo_cart_message = !empty($cl_options['cepatlakoo_message_above_contact']) ? $cl_options['cepatlakoo_message_above_contact'] : '';
        $cepatlakoo_contact_button_larger = !empty($cl_options['cepatlakoo_contact_button_larger']) ? $cl_options['cepatlakoo_contact_button_larger'] : '';
        $cepatlakoo_message_popup_heading = !empty($cl_options['cepatlakoo_message_popup_heading']) ? $cl_options['cepatlakoo_message_popup_heading'] : '';
        $cepatlakoo_contact_button_message = !empty($cl_options['cepatlakoo_message_popup']) ? $cl_options['cepatlakoo_message_popup'] : '';

        $product_name = get_the_title(get_the_ID());
        $product_url = urlencode(get_permalink(get_the_ID()));

        $cepatlakoo_cart_sms_message = str_replace('%lakoo_product_name%', $product_name, $cepatlakoo_cart_sms_message);
        $cepatlakoo_cart_sms_message = str_replace('%lakoo_product_url%', $product_url, $cepatlakoo_cart_sms_message);
        $cepatlakoo_cart_sms_message = cepatlakoo_replace_hexcode($cepatlakoo_cart_sms_message); ?>
        <?php if (is_singular('product')) : ?>
            <?php $style = ''; ?>
            <div class="quick-contact-info">
                <?php if ($cepatlakoo_cart_message) : ?>
                    <p class="contact-message"><?php echo $cepatlakoo_cart_message; ?></p>
                <?php endif; ?>

                <?php if ($cepatlakoo_cart_sms) : ?>
                    <?php
                    if (is_array($cepatlakoo_cart_sms)) {
                        $cepatlakoo_cart_sms = $cepatlakoo_cart_sms[array_rand($cepatlakoo_cart_sms)];
                    }
                    ?>
                    <?php if (wp_is_mobile()) : ?>
                        <a <?php echo $style ?> fb-pixel="<?php echo $cepatlakoo_cart_sms_event; ?>" href="sms:<?php echo $cepatlakoo_cart_sms ?>?body=<?php echo $cepatlakoo_cart_sms_message; ?>" class="sms">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/images/sms-icon-a.svg"><?php echo $cepatlakoo_cart_sms_text; ?>
                        </a>
                    <?php else : ?>
                        <a <?php echo $style; ?> fb-pixel="<?php echo $cepatlakoo_cart_sms_event; ?>" href="#sms" class="sms">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/sms-icon-a.svg"><?php echo $cepatlakoo_cart_sms_text; ?>
                        </a>

                        <!-- Modal -->
                        <div id="sms" class="mfp-hide white-popup-block cl-mfp">
                            <h2 class="mb-3"><?php echo $cepatlakoo_message_popup_heading; ?></h2>
                            <p>
                                <?php
                                $cepatlakoo_lightbox_message = str_replace("[contact_app]", "SMS", $cepatlakoo_contact_button_message);
                                $cepatlakoo_lightbox_final_message = str_replace("[contact_id]", $cepatlakoo_cart_sms, $cepatlakoo_lightbox_message);
                                esc_attr_e($cepatlakoo_lightbox_final_message);
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($cepatlakoo_cart_line) : ?>
                    <?php
                    if (is_array($cepatlakoo_cart_line)) {
                        $cepatlakoo_cart_line = $cepatlakoo_cart_line[array_rand($cepatlakoo_cart_line)];
                    }
                    ?>
                    <?php if (wp_is_mobile()) : ?>
                        <a <?php echo $style ?> fb-pixel="<?php echo $cepatlakoo_cart_line_event; ?>" href="line://ti/p/~<?php echo $cepatlakoo_cart_line; ?>" class="line">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/images/line-icon-a.svg"><?php echo $cepatlakoo_cart_line_text; ?>
                        </a>
                    <?php else : ?>
                        <a <?php echo $style; ?> fb-pixel="<?php echo $cepatlakoo_cart_line_event; ?>" href="#line" class="line">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/line-icon-a.svg"><?php echo $cepatlakoo_cart_line_text; ?>
                        </a>

                        <!-- Modal -->
                        <div id="line" class="mfp-hide white-popup-block cl-mfp">
                            <h2 class="mb-3"><?php echo $cepatlakoo_message_popup_heading; ?></h2>
                            <p>
                                <?php
                                $cepatlakoo_lightbox_message = str_replace("[contact_app]", "LINE", $cepatlakoo_contact_button_message);
                                $cepatlakoo_lightbox_final_message = str_replace("[contact_id]", $cepatlakoo_cart_line, $cepatlakoo_lightbox_message);
                                esc_attr_e($cepatlakoo_lightbox_final_message);
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($cepatlakoo_cart_phone) : ?>
                    <?php
                    if (is_array($cepatlakoo_cart_phone)) {
                        $cepatlakoo_cart_phone = $cepatlakoo_cart_phone[array_rand($cepatlakoo_cart_phone)];
                    }
                    ?>
                    <?php if (wp_is_mobile()) : ?>
                        <a <?php echo $style ?> fb-pixel="<?php echo $cepatlakoo_cart_phone_event; ?>" href="tel:<?php echo $cepatlakoo_cart_phone ?>" class="phone">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/images/phone-icon-a.svg"><?php echo $cepatlakoo_cart_phone_text; ?>
                        </a>
                    <?php else : ?>
                        <a <?php echo $style; ?> fb-pixel="<?php echo $cepatlakoo_cart_phone_event; ?>" href="#phone" class="phone">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/phone-icon-a.svg"><?php echo $cepatlakoo_cart_phone_text; ?>
                        </a>
                        <!-- Modal -->

                        <div id="phone" class="mfp-hide white-popup-block cl-mfp">
                            <h2 class="mb-3"><?php echo $cepatlakoo_message_popup_heading; ?></h2>
                            <p>
                                <?php
                                $cepatlakoo_lightbox_message = str_replace("[contact_app]", "Phone", $cepatlakoo_contact_button_message);
                                $cepatlakoo_lightbox_final_message = str_replace("[contact_id]", $cepatlakoo_cart_phone, $cepatlakoo_lightbox_message);
                                esc_attr_e($cepatlakoo_lightbox_final_message);
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($cepatlakoo_cart_telegram) : ?>
                    <?php
                    if (is_array($cepatlakoo_cart_telegram)) {
                        $cepatlakoo_cart_telegram = $cepatlakoo_cart_telegram[array_rand($cepatlakoo_cart_telegram)];
                    }
                    ?>
                    <a <?php echo $style; ?> fb-pixel="<?php echo $cepatlakoo_cart_telegram_event; ?>" href="https://t.me/<?php echo $cepatlakoo_cart_telegram; ?>" class="telegram">
                        <img src="<?php echo get_template_directory_uri() ?>/assets/images/telegram-icon-a.svg"><?php echo $cepatlakoo_cart_telegram_text; ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif;
    }
}

/**
 * Function to Set Option in Init
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_init_base')) {
    function cepatlakoo_init_base()
    {
        global $cl_options;

        $cepatlakoo_cart_option = !empty($cl_options['cepatlakoo_shoping_cart']) ? $cl_options['cepatlakoo_shoping_cart'] : '';
        $cepatlakoo_contact_button_trigger = !empty($cl_options['cepatlakoo_contact_button_trigger']) ? $cl_options['cepatlakoo_contact_button_trigger'] : '';
        $cepatlakoo_sticky_buttons = !empty($cl_options['cepatlakoo_sticky_buttons']) ? $cl_options['cepatlakoo_sticky_buttons'] : '';
        $cepatlakoo_quickview_product_description = !empty($cl_options['cepatlakoo_quickview_product_description']) ? $cl_options['cepatlakoo_quickview_product_description'] : '';
        $cepatlakoo_quickview_product_catergoryandtag = !empty($cl_options['cepatlakoo_quickview_product_catergoryandtag']) ? $cl_options['cepatlakoo_quickview_product_catergoryandtag'] : '';
        $cepatlakoo_order_tracking_type = !empty($cl_options['cepatlakoo_order_tracking_type']) ? $cl_options['cepatlakoo_order_tracking_type'] : '';
        $cepatlakoo_button_type = !empty($cl_options['cepatlakoo_single_product_button_type']) ? $cl_options['cepatlakoo_single_product_button_type'] : '';
        $cepatlakoo_button_loop = !empty($cl_options['cepatlakoo_loop_product_button_type']) ? $cl_options['cepatlakoo_loop_product_button_type'] : 'none';
        $cepatlakoo_via_wa = !empty($cl_options['cepatlakoo_checkout_via_whatsapp']) ? $cl_options['cepatlakoo_checkout_via_whatsapp'] : '';

        // var_dump( $cepatlakoo_button_loop );
        if ($cepatlakoo_button_loop == 'none') {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        } else if ($cepatlakoo_button_loop == 'both') {
            add_action('woocommerce_after_shop_loop_item', 'cepatlakoo_wc_product_whatsapp_button', 30);
        } else if ($cepatlakoo_button_loop == 'whatsapp') {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
            add_action('woocommerce_after_shop_loop_item', 'cepatlakoo_wc_product_whatsapp_button', 30);
        }

        if ($cepatlakoo_button_type == 'whatsapp' && $cepatlakoo_cart_option == false) {
            Redux::set_option('cl_options', 'cepatlakoo_shoping_cart', true);
            $cepatlakoo_cart_option = true;
            WC()->cart->empty_cart();
        } else if ($cepatlakoo_button_type != 'whatsapp' && $cepatlakoo_cart_option != false) {
            Redux::set_option('cl_options', 'cepatlakoo_shoping_cart', false);
        }

        if( isset($cl_options['cepatlakoo_shop_sorting']) && $cl_options['cepatlakoo_shop_sorting'] == '1' )
            add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
        else
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

        if( isset($cl_options['cepatlakoo_shop_result_count']) && $cl_options['cepatlakoo_shop_result_count'] == '1' )
            add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        else
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

        if ($cepatlakoo_cart_option || $cepatlakoo_button_type == 'whatsapp') { // checked
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            remove_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_add_to_cart', 25);
            remove_action('cepatlakoo_summary_quick_view', 'cepatlakoo_quick_view_add_to_cart', 30);
            remove_action('cepatlakoo_summary_product_highlight', 'cepatlakoo_quick_view_add_to_cart', 30);

            update_option('woocommerce_cart_redirect_after_add', 'no');
            update_option('woocommerce_enable_ajax_add_to_cart', 'no');

            add_action('template_redirect', function () {
                if (is_checkout() || is_cart()) {
                    wp_redirect(cepatlakoo_get_notfound_url(), '301');
                    exit;
                } else {
                    return;
                }
            });

            remove_action('init', 'woocommerce_add_to_cart_action', 10);
            remove_action('init', 'woocommerce_checkout_action', 10);
            remove_action('init', 'woocommerce_pay_action', 10);

            add_filter('woocommerce_before_cart', function () {
                return 'cabbbss';
            });
        } else {
            // update_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' );
        }

        if ($cepatlakoo_button_type != 'addtocart') {
            add_action('woocommerce_single_product_summary', 'cepatlakoo_wc_product_whatsapp_button', 30);
        }

        if ($cepatlakoo_contact_button_trigger) {
            add_action('woocommerce_single_product_summary', 'cepatlakoo_cart_custom_button', 30);
            // add_action( 'woocommerce_after_single_variation', 'cepatlakoo_cart_custom_button', 30 );
        }

        if ($cepatlakoo_sticky_buttons) {
            add_action('woocommerce_single_product_summary', 'cepatlakoo_sticky_buttons', 20);
        }

        if ($cepatlakoo_quickview_product_description) {
            add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_excerpt', 20);
        }

        if ($cepatlakoo_quickview_product_catergoryandtag) {
            add_action('cepatlakoo_summary_quick_view', 'woocommerce_template_single_meta', 40);
        }

        if ($cepatlakoo_order_tracking_type == 'orderid-phone') {
            remove_shortcode('woocommerce_order_tracking');
            add_shortcode('woocommerce_order_tracking', 'cepatlakoo_woocommerce_order_tracking');
        }

        if (isset($cl_options['cepatlakoo_wc_loop_product_stock']) && $cl_options['cepatlakoo_wc_loop_product_stock'] == 1)
            add_action('woocommerce_after_shop_loop_item', 'cepatlakoo_wc_product_loop_stock', 5);

        if (isset($cl_options['cepatlakoo_wc_show_breadcrumb']) && $cl_options['cepatlakoo_wc_show_breadcrumb'] == 0)
            remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

        if ($cepatlakoo_via_wa) {
            add_filter('woocommerce_pay_order_button_html', 'cepatlakoo_woocommerce_checkout_btn');
            add_filter('woocommerce_order_button_html', 'cepatlakoo_woocommerce_checkout_btn');
        }

        // Show/hide related products
        if (empty($cl_options['cepatlakoo_wc_related_products'])) {
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        }

        // Show/hide upsell products
        if (empty($cl_options['cepatlakoo_wc_upsell_products'])) {
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
        }
    }
}
add_action('init', 'cepatlakoo_init_base', 11);

/**
 * Function to Check Current Version Woocommerce
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_check_version_threshold')) {
    function cepatlakoo_check_version_threshold()
    {
        global $woocommerce;
        if (version_compare($woocommerce->version, '3.0.0', ">=")) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Function to get the id from woocommerce
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_WC_ID')) {
    function cepatlakoo_WC_ID()
    {
        if (is_shop()) {
            $wc_id = get_option('woocommerce_shop_page_id');
        } elseif (is_cart()) {
            $wc_id = get_option('woocommerce_cart_page_id');
        } elseif (is_checkout()) {
            $wc_id = get_option('woocommerce_checkout_page_id');
        } elseif (is_wc_endpoint_url('order-pay')) {
            $wc_id = get_option('woocommerce_pay_page_id');
        } elseif (is_wc_endpoint_url('order-received')) {
            $wc_id = get_option('woocommerce_thanks_page_id');
        } elseif (is_wc_endpoint_url('edit-address')) {
            $wc_id = get_option('woocommerce_edit_address_page_id');
        } elseif (is_wc_endpoint_url('view-order')) {
            $wc_id = get_option('woocommerce_view_order_page_id');
        } elseif (is_account_page()) {
            $wc_id = get_option('woocommerce_myaccount_page_id');
        } else {
            $wc_id = get_the_ID();
        }

        return $wc_id;
    }
}

/**
 * Function to Add to cart by AJAX
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_add_tocart_product')) {
    function cepatlakoo_add_tocart_product()
    {
        $product_id = sanitize_text_field($_POST['product_id']);
        $variation_id = isset($_POST['variation_id']) ? sanitize_text_field($_POST['variation_id']) : '';
        $variation = isset($_POST['variation_data']) ? sanitize_text_field($_POST['variation_data']) : '';
        $submit_type = isset($_POST['submit_type']) ? sanitize_text_field($_POST['submit_type']) : '';
        $data_product = isset($_POST['data_product']) ? $_POST['data_product'] : '';
        $post_data = $_POST;

        if (!empty($product_id) || !empty($data_product)) {
            unset($post_data['product_id'], $post_data['variation_id'], $post_data['variation_id'], $post_data['variation_data'], $post_data['submit_type'], $post_data['action'], $post_data['quantity']);

            $variations = explode(",", $variation);
            $attr = array();
            $val = array();
            foreach ($variations as $k => $v) {
                if ($k % 2 == 0) {
                    array_push($attr, $v);
                } else {
                    array_push($val, $v);
                }
            }

            if (count($val) > 0)
                $list = array_combine($attr, $val);

            $quantity = absint($_POST['quantity']);
            global $woocommerce;

            if ($variation_id) {
                WC()->cart->add_to_cart($product_id, $quantity, wc_get_product_variation_attributes($variation_id), $list, $post_data);
                // WC()->session->set( 'cl_cart_last_product', ['product_id' => 0, 'variation_id' => (int)$variation_id] );
            } else {
                if ($submit_type == 'multiple') {
                    $data_product = explode(",", $data_product);
                    foreach ($data_product as $key => $value) {
                        $data_val = explode("q", $value);
                        WC()->cart->add_to_cart($data_val[0], $data_val[1], 0, array(), $post_data);
                    }
                } else {
                    WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $post_data);
                }

                // WC()->session->set( 'cl_cart_last_product', ['product_id' => (int)$product_id, 'variation_id' => 0] );
            }
        }

        wp_die();
    };
};
add_action('wp_ajax_cepatlakoo_wc_add_cart', 'cepatlakoo_add_tocart_product');
add_action('wp_ajax_nopriv_cepatlakoo_wc_add_cart', 'cepatlakoo_add_tocart_product');

/**
 * Function to remove product by AJAX
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_product_remove')) {
    function cepatlakoo_product_remove()
    {
        $cart_item_key = sanitize_text_field(wp_unslash($_POST['product_id']));
        $cart_item     = WC()->cart->get_cart_item($cart_item_key);

        if ($cart_item_key) {
            WC()->cart->remove_cart_item($cart_item_key);

            $product = wc_get_product($cart_item['product_id']);

            /* translators: %s: Item name. */
            $item_removed_title = apply_filters('woocommerce_cart_item_removed_title', $product ? sprintf(_x('&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce'), $product->get_name()) : __('Item', 'woocommerce'), $cart_item);

            // Don't show undo link if removed item is out of stock.
            if ($product && $product->is_in_stock() && $product->has_enough_stock($cart_item['quantity'])) {
                /* Translators: %s Product title. */
                $removed_notice  = sprintf(__('%s has been removed from cart.', 'cepatlakoo'), $item_removed_title);
                $removed_notice .= ' <a href="' . esc_url(wc_get_cart_undo_url($cart_item_key)) . '" class="restore-item">' . __('Undo?', 'woocommerce') . '</a>';
            } else {
                /* Translators: %s Product title. */
                $removed_notice = sprintf(__('%s has been removed from cart.', 'cepatlakoo'), $item_removed_title);
            }

            wc_add_notice($removed_notice, apply_filters('woocommerce_cart_item_removed_notice_type', 'success'));
        }

        wp_die();
    }
}
add_action('wp_ajax_my_wc_remove_product', 'cepatlakoo_product_remove');
add_action('wp_ajax_nopriv_my_wc_remove_product', 'cepatlakoo_product_remove');

/**
 * Function to update product by AJAX
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_update_item_cart')) {
    function cepatlakoo_update_item_cart()
    {
        $cart = WC()->instance()->cart;
        $id = sanitize_text_field($_POST['product_id']);
        $cart_item_id = $cart->find_product_in_cart($id);
        $prod = $cart->cart_contents[$id];

        if (!empty($prod['variation_id']))
            WC()->session->set('cl_cart_last_product', ['product_id' => 0, 'variation_id' => (int)$prod['variation_id']]);
        else
            WC()->session->set('cl_cart_last_product', ['product_id' => $prod['product_id'], 'variation_id' => 0]);

        // var_dump( $cart_item_id );
        // var_dump( $cart->cart_contents[$id] );

        $quantity = absint($_POST['quantity']);

        if ($cart_item_id) {
            $cart->set_quantity($cart_item_id, $quantity);
        }

        wp_die();
    }
}
add_action('wp_ajax_update_item_cart', 'cepatlakoo_update_item_cart');
add_action('wp_ajax_nopriv_update_item_cart', 'cepatlakoo_update_item_cart');

/**
 * Function step by step Checkout
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_stepbystep')) {
    function cepatlakoo_stepbystep()
    {
        ?>
        <div class="ui ordered steps">
            <?php if (is_cart()) : ?>
                <div class="active current step">
                <?php elseif (is_checkout()) : ?>
                    <div class="completed step">
                    <?php endif; ?>
                    <div class="content">
                        <div class="title"><?php esc_html_e('Shopping Cart', 'cepatlakoo'); ?></div>
                        <div class="description"><?php esc_html_e('Confirm your ordered items', 'cepatlakoo'); ?></div>
                    </div>
                    </div>
                    <?php if (is_cart()) : ?>
                        <div class="active step">
                        <?php elseif (is_checkout()) : ?>
                            <div class="active current step">
                            <?php endif; ?>
                            <div class="content">
                                <div class="title"><?php esc_html_e('Checkout', 'cepatlakoo'); ?></div>
                                <div class="description"><?php esc_html_e('Enter billing information', 'cepatlakoo'); ?></div>
                            </div>
                            </div>
                            <?php if (is_cart()) : ?>
                                <div class="active step">
                                <?php elseif (is_checkout()) : ?>
                                    <div class="active step">
                                    <?php endif; ?>
                                    <div class="content">
                                        <div class="title"><?php esc_html_e('Order Completed', 'cepatlakoo'); ?></div>
                                        <div class="description"><?php esc_html_e('Your order is completed', 'cepatlakoo'); ?></div>
                                    </div>
                                    </div>
                                </div>
                            <?php
                        }
                    }

                    /**
                     * Function step by step Thankyou page
                     *
                     * @package WordPress
                     * @subpackage CepatLakoo
                     * @since CepatLakoo 1.0.0
                     */
                    if (!function_exists('cepatlakoo_stepbystep_thankyou')) {
                        function cepatlakoo_stepbystep_thankyou()
                        {
                            ?>
                                <div class="ui ordered steps">
                                    <div class="completed step">
                                        <div class="content">
                                            <div class="title"><?php esc_html_e('Shopping Cart', 'cepatlakoo'); ?></div>
                                            <div class="description"><?php esc_html_e('Confirm your ordered items', 'cepatlakoo'); ?></div>
                                        </div>
                                    </div>
                                    <div class="completed step">
                                        <div class="content">
                                            <div class="title"><?php esc_html_e('Checkout', 'cepatlakoo'); ?></div>
                                            <div class="description"><?php esc_html_e('Enter billing information', 'cepatlakoo'); ?></div>
                                        </div>
                                    </div>
                                    <div class="completed step">
                                        <div class="content">
                                            <div class="title"><?php esc_html_e('Order Completed', 'cepatlakoo'); ?></div>
                                            <div class="description"><?php esc_html_e('Your order is completed', 'cepatlakoo'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }

                        /**
                         * Function to trigger FB Pixel Purchase
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 1.2.2
                         */
                        if (!function_exists('cepatlakoo_trigger_fbpixel_purchase')) {
                            function cepatlakoo_trigger_fbpixel_purchase($order)
                            {
                                global $cl_options;
                                $ids = array();
                                $total = 0;
                                $items = $order->get_items();
                                foreach ($items as $item) {
                                    $item_id = $item['product_id'];
                                    $total = $total + $item['qty'];
                                    // $ids[] = 'wc_post_id_'.$item_id;
                                    $ids[] = $item_id;
                                }

                                if ($order->has_status('completed') || $order->has_status('processing')) {
                                    $cl_checkout_pixel_id = (!empty($cl_options['cepatlakoo_purchase_confirmation'])) ? $cl_options['cepatlakoo_purchase_confirmation'] : $cl_options['cepatlakoo_pixel_order_received'];
                                    $cl_checkout_tt_pixel_id = (!empty($cl_options['cepatlakoo_tiktok_purchase_confirmation'])) ? $cl_options['cepatlakoo_tiktok_purchase_confirmation'] : $cl_options['cepatlakoo_tiktok_pixel_order_received'];
                                } else {
                                    $cl_checkout_pixel_id = (!empty($cl_options['cepatlakoo_pixel_order_received'])) ? $cl_options['cepatlakoo_pixel_order_received'] : 'InitiateCheckout';
                                    $cl_checkout_tt_pixel_id = (!empty($cl_options['cepatlakoo_tiktok_pixel_order_received'])) ? $cl_options['cepatlakoo_tiktok_pixel_order_received'] : 'InitiateCheckout';
                                }

                                wp_localize_script('cepatlakoo-functions', '_fbpixel_initCheckout', array(
                                    'prod_ids'  => $ids,
                                    'total'     => $order->get_total(),
                                    'items'     => $total,
                                    'type'      => $cl_checkout_pixel_id,
                                    'currency'  => $order->get_currency()
                                ));

                                wp_localize_script('cepatlakoo-functions', '_ttpixel_initCheckout', array(
                                    'prod_ids'  => $ids,
                                    'total'     => $order->get_total(),
                                    'items'     => $total,
                                    'type'      => $cl_checkout_tt_pixel_id,
                                    'currency'  => $order->get_currency()
                                ));
                            }
                        }

                        /**
                         * Functions to add Custom Column in Shop Order Posttype
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @source : www.deluxeblogtips.com/add-custom-column/
                         *
                         */
                        add_filter('manage_edit-shop_order_columns', 'cepatlakoo_add_column_shop_order');
                        if (!function_exists('cepatlakoo_add_column_shop_order')) {
                            function cepatlakoo_add_column_shop_order($columns)
                            {
                                $columns['contact'] = esc_html('Contact Buyer', 'cepatlakoo');
                                return $columns;
                            }
                        }

                        if (!function_exists('get_variation_data_from_variation_id')) {
                            function get_variation_data_from_variation_id($item_id)
                            {
                                $_product = new WC_Product_Variation($item_id);
                                $variation_data = $_product->get_variation_attributes(); // variation data in array
                                // $variation_detail = woocommerce_get_formatted_variation($variation_data, true);  // this will give all variation detail in one line
                                // $variation_detail = woocommerce_get_formatted_variation( $variation_data, false);  // this will give all variation detail one by one
                                return $variation_data; // $variation_detail will return string containing variation detail which can be used to print on website
                                // return $variation_data; // $variation_data will return only the data which can be used to store variation data
                            }
                        }

                        /**
                         * Functions to add Custom Column in Shop Order Posttype
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @source : www.deluxeblogtips.com/add-custom-column/
                         *
                         */

                        add_filter('woocommerce_admin_order_preview_get_order_details', 'cepatlakoo_preview_order_action');
                        if (!function_exists('cepatlakoo_preview_order_action')) {
                            function cepatlakoo_preview_order_action($datas)
                            {
                                $contact    = '<div class="wc-action-button-group">
        <label>Contact : </label>
        <span class="wc-action-button-group__items">
        ' . cepatlakoo_render_order_contact($datas['data']['id']) . '
        </span>
        </div>';
                                $datas['actions_html'] .= $contact;

                                return $datas;
                            }
                        }

                        add_action('manage_posts_custom_column',  'cepatlakoo_show_column_shop_order');
                        if (!function_exists('cepatlakoo_show_column_shop_order')) {
                            function cepatlakoo_show_column_shop_order($name)
                            {
                                switch ($name) {
                                    case 'contact':
                                        global $post;
                                        echo cepatlakoo_render_order_contact($post->ID);
                                }
                            }
                        }

                        /**
                         * Functions to add follow up contact buttons on WooCommerce orders page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 1.0.0
                         *
                         */
                        if (!function_exists('cepatlakoo_render_order_contact')) {
                            function cepatlakoo_render_order_contact($post_id)
                            {
                                global $woocommerce, $post, $cl_options;
                                $returns = '';

                                $cepatlakoo_followup_wa_message = !empty($cl_options['cepatlakoo_followup_wa_message']) ? $cl_options['cepatlakoo_followup_wa_message'] : '';
                                $cepatlakoo_followup_sms_message = !empty($cl_options['cepatlakoo_followup_sms_message']) ? $cl_options['cepatlakoo_followup_sms_message'] : '';

                                $order = new WC_Order($post_id);
                                $order_id = trim(str_replace('#', '', $order->get_order_number()));

                                // Get an instance of the WC_Order object
                                $order = wc_get_order($order_id);
                                $items = $order->get_items();
                                $data_product = array();

                                foreach ($items as $item) {
                                    $meta_data = $item->get_formatted_meta_data('');
                                    $var = array();
                                    foreach ($meta_data as $meta) {
                                        if ($meta->display_key != '_reduced_stock')
                                            $var[] = $meta->display_key . ': ' . $meta->value;
                                    }
                                    $var = ' ' . implode(', ', $var);
                                    array_push($data_product, $item['name']  . $var . ' (' . $item['quantity'] . '): ' . strip_tags(wc_price($item->get_total())));
                                }

                                $data_products = implode(",\n--\n", $data_product);

                                $order_data = $order->get_data(); // The Order data

                                $order_id = $order_data['id'];

                                ## Creation and modified WC_DateTime Object date string ##

                                // Using a timestamp ( with php getTimestamp() function as method)
                                $order_shipping_total = strip_tags(wc_price($order_data['shipping_total']));
                                $order_shipping_tax = $order_data['shipping_tax'];
                                $order_total = $order_data['cart_tax'];
                                $order_total_tax = $order_data['total_tax'];

                                ## BILLING INFORMATION:
                                $order_billing_first_name = $order_data['billing']['first_name'];
                                $order_billing_last_name = isset($order_data['billing']['last_name']) ? $order_data['billing']['last_name'] : '';

                                $order_total = strip_tags(wc_price($order->get_total()));
                                $order_subtotal = strip_tags(wc_price($order->get_subtotal()));

                                $full_name = @get_post_meta($post_id, '_billing_first_name')[0] . ' ' . @get_post_meta($post_id, '_billing_last_name')[0];
                                $full_name = esc_html($full_name);
                                $cl_shop_name = get_bloginfo('name');
                                $cl_phone_number = get_post_meta($post_id, '_billing_phone', true);
                                $cl_email = get_post_meta($post_id, '_billing_email', true);

                                $cl_custom_fee = $order->get_fees();

                                if ($cl_custom_fee) {
                                    foreach ($cl_custom_fee as $cl_fee) {
                                    }
                                } else {
                                    $cl_fee = null;
                                }

                                ($cl_fee) ? $cl_payment_code = $cl_fee->get_total() : $cl_payment_code = null;

                                $cl_base_order_status = $order->get_status();
                                $cl_order_status = __('Pending', 'cepatlakoo');
                                $cl_status_list = array(
                                    'pending' => __('Pending', 'cepatlakoo'),
                                    'processing' => __('Processing', 'cepatlakoo'),
                                    'on-hold' => __('On Hold', 'cepatlakoo'),
                                    'completed' => __('Completed', 'cepatlakoo'),
                                    'cancelled' => __('Cancelled', 'cepatlakoo'),
                                    'refunded' => __('Refunded', 'cepatlakoo'),
                                    'failed' => __('Failed', 'cepatlakoo'),
                                );

                                foreach ($cl_status_list as $cl_status_lists => $translate) {
                                    if ($cl_base_order_status == $cl_status_lists) {
                                        $cl_order_status = $translate;
                                    }
                                }

                                if (is_plugin_active('cepatlakoo-input-resi/cepatlakoo-input-resi.php')) {
                                    if ($cl_order_status == __('Completed', 'cepatlakoo')) {
                                        $cepatlakoo_followup_wa_message = !empty($cl_options['cepatlakoo_followup_wa_message_success']) ? $cl_options['cepatlakoo_followup_wa_message_success'] : $cepatlakoo_followup_wa_message;
                                        $cepatlakoo_followup_sms_message = !empty($cl_options['cepatlakoo_followup_sms_message_success']) ? $cl_options['cepatlakoo_followup_sms_message_success'] : $cepatlakoo_followup_sms_message;
                                    }
                                    $cl_resi_code = !empty(get_post_meta($order_id, '_cepatlakoo_resi', true)) ? get_post_meta($order_id, '_cepatlakoo_resi', true) : '';
                                    $cl_resi_date = !empty(get_post_meta($order_id, '_cepatlakoo_resi_date', true)) ? date(get_option('date_format'), strtotime(get_post_meta($order_id, '_cepatlakoo_resi_date', true))) : '';
                                    $cl_resi_courier = !empty(get_post_meta($order_id, '_cepatlakoo_ekspedisi', true)) ? get_post_meta($order_id, '_cepatlakoo_ekspedisi', true) : '';

                                    $cepatlakoo_followup_wa_message = str_replace('%lakoo_courier%', $cl_resi_courier, $cepatlakoo_followup_wa_message);
                                    $cepatlakoo_followup_wa_message = str_replace('%lakoo_tracking_code%', $cl_resi_code, $cepatlakoo_followup_wa_message);
                                    $cepatlakoo_followup_wa_message = str_replace('%lakoo_tracking_date%', $cl_resi_date, $cepatlakoo_followup_wa_message);

                                    $cepatlakoo_followup_sms_message = str_replace('%lakoo_courier%', $cl_resi_courier, $cepatlakoo_followup_sms_message);
                                    $cepatlakoo_followup_sms_message = str_replace('%lakoo_tracking_code%', $cl_resi_code, $cepatlakoo_followup_sms_message);
                                    $cepatlakoo_followup_sms_message = str_replace('%lakoo_tracking_date%', $cl_resi_date, $cepatlakoo_followup_sms_message);
                                }

                                // WhatsApp
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_name%', $full_name, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_fullname%', $full_name, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_email%', $cl_email, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_order_id%', $order_id, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_order_status%', $cl_order_status, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_order_total%', $order_total, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_total_price%', $order_total, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_subtotal_price%', $order_subtotal, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_products%', $data_products, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_shipping_cost%', $order_shipping_total, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_shipping_price%', $order_shipping_total, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace('%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace("&nbsp;", '', $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace("\n", '%0A', $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace("#", '%23', $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace("-", '%2D', $cepatlakoo_followup_wa_message);
                                $cepatlakoo_followup_wa_message = str_replace("&", '%26', $cepatlakoo_followup_wa_message);

                                // SMS
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_shop_name%', $cl_shop_name, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_name%', $full_name, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_fullname%', $full_name, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_email%', $cl_email, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_phone_number%', $cl_phone_number, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_order_id%', $order_id, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_order_status%', $cl_order_status, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_order_total%', $order_total, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_total_price%', $order_total, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_subtotal_price%', $order_subtotal, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_products%', $data_products, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_shipping_cost%', $order_shipping_total, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_shipping_price%', $order_shipping_total, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace('%lakoo_payment_code%', $cl_payment_code, $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace("&nbsp;", '', $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace("\n", '%0A', $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace("#", '%23', $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace("-", '%2D', $cepatlakoo_followup_sms_message);
                                $cepatlakoo_followup_sms_message = str_replace("&", '%26', $cepatlakoo_followup_sms_message);

                                $phone_number = get_post_meta($post_id, '_billing_phone')[0];

                                // Update v.1.0.1
                                if (strpos($phone_number, '-') !== false) {
                                    $phone_number = str_replace('-', '', $phone_number);
                                }
                                if (preg_match('[^\+62]', $phone_number)) {
                                    $phone_number = str_replace('+62', '62', $phone_number);
                                } else if ($phone_number[0] == '0') {
                                    $phone_number = ltrim($phone_number, '0');
                                    $phone_number = '62' . $phone_number;
                                } else if ($phone_number[0] == '8') {
                                    $phone_number = '62' . $phone_number;
                                } else {
                                    $phone_number = $phone_number;
                                }

                                $wa_base_url = 'https://api.whatsapp.com/';

                                $returns .= '<a class="button wc-action-button whatsapp"
                    href="' . $wa_base_url . 'send?l=id&phone=' . $phone_number . '&text=' .  $cepatlakoo_followup_wa_message . '">' . esc_html__('WhatsApp', 'cepatlakoo') . '</a>';

                                $returns .= '<a class="button wc-action-button sms"
                    href="sms:' . $phone_number . '?body=' . $cepatlakoo_followup_sms_message . '">' . esc_html__('SMS', 'cepatlakoo') . '</a>';

                                $returns .= "<script type='text/javascript'>
                document.addEventListener('DOMContentLoaded', (function () {
                    link = new SMSLink.link();
                    link.replaceAll();
                }), false);
            </script>";
                                return $returns;
                            }
                        }

                        /**
                         * Functions to inject button in email & view order page in my account
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.1.3
                         *
                         */
                        if (!function_exists('cepatlakoo_confirmation_button')) {
                            function cepatlakoo_confirmation_button($order_id)
                            {
                                global $cl_options;

                                $cepatlakoo_button_confirmation = !empty($cl_options['cepatlakoo_payment_confirmation_buttons']) ? $cl_options['cepatlakoo_payment_confirmation_buttons'] : '';
                                $cepatlakoo_form_text = !empty($cl_options['cepatlakoo_form_confirmation_text']) ? $cl_options['cepatlakoo_form_confirmation_text'] : __('Confirm Payment on Website', 'cepatlakoo');
                                $cepatlakoo_wa_text = !empty($cl_options['cepatlakoo_wa_confirmation_text']) ? $cl_options['cepatlakoo_wa_confirmation_text'] : __('Confirm Payment via WhatsApp', 'cepatlakoo');
                                $cepatlakoo_wa_number = !empty($cl_options['cepatlakoo_wa_confirmation_number']) ? $cl_options['cepatlakoo_wa_confirmation_number'] : '';
                                $wa_confirmation_greeting_text = !empty($cl_options['cepatlakoo_wa_confirmation_greeting_text']) ? $cl_options['cepatlakoo_wa_confirmation_greeting_text'] : '';
                                $cepatlakoo_confirmation_page = !empty($cl_options['cepatlakoo_select_confirmation']) ? $cl_options['cepatlakoo_select_confirmation'] : '';
                                $cepatlakoo_general_bg_theme_color = !empty($cl_options['cepatlakoo_general_bg_theme_color']['background-color']) ? $cl_options['cepatlakoo_general_bg_theme_color']['background-color'] : '';

                                $order = new WC_Order($order_id);
                                $order_status = $order->get_status();
                                $order_no = $order->get_order_number();
                                $order_data = $order->get_data(); // The Order data
                                $email = $order_data['billing']['email'];

                                // Load WooCommerce email colour settings
                                $bg         = get_option('woocommerce_email_background_color');
                                $body       = get_option('woocommerce_email_body_background_color');
                                $base       = get_option('woocommerce_email_base_color');
                                $base_text  = wc_light_or_dark($base, '#202020', '#ffffff');
                                $text       = get_option('woocommerce_email_text_color');

                                // only show confirmation button only if order status is on-hold or pending
                                if ($cepatlakoo_button_confirmation != 'none') :
                                    if (('on-hold' == $order_status || 'pending' == $order_status) && $cepatlakoo_confirmation_page) :
                                        $style = (did_action('woocommerce_email_after_order_table')) ? 'style="background-color: ' . $base . '; padding: 13px 20px; font-size: 14px; color: ' . $base_text . ' !important; text-decoration: none;"' : 'class="button"'; ?>
                                        <div style="margin:30px 0;">
                                            <?php if (($cepatlakoo_button_confirmation == 'web-form' || $cepatlakoo_button_confirmation == 'all')) : ?>
                                                <p><a <?php echo $style; ?> href="<?php echo get_permalink($cepatlakoo_confirmation_page) . '?orderid=' . $order_no . '&email=' . $email; ?>"><?php echo $cepatlakoo_form_text; ?></a></p>
                                            <?php endif;

                                            if (($cepatlakoo_button_confirmation == 'whatsapp' || $cepatlakoo_button_confirmation == 'all')) :
                                                if (strpos($cepatlakoo_wa_number, '-') !== false) {
                                                    $cepatlakoo_wa_number = str_replace('-', '', $cepatlakoo_wa_number);
                                                }
                                                if (preg_match('[^\+62]', $cepatlakoo_wa_number)) {
                                                    $wa_phone = str_replace('+62', '62', $cepatlakoo_wa_number);
                                                } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '0') {
                                                    $cepatlakoo_wa_number = ltrim($cepatlakoo_wa_number, '0');
                                                    $wa_phone = '62' . $cepatlakoo_wa_number;
                                                } else if (isset($cepatlakoo_wa_number[0]) && $cepatlakoo_wa_number[0] == '8') {
                                                    $wa_phone = '62' . $cepatlakoo_wa_number;
                                                } else {
                                                    $wa_phone = $cepatlakoo_wa_number;
                                                }

                                                if (strpos($wa_phone, "-")) {
                                                    $wa_phone = str_replace('-', '', $wa_phone);
                                                }

                                                $wa_base_url = 'https://api.whatsapp.com/';

                                                $wa_confirmation_greeting_text = str_replace('%lakoo_order_id%', $order_no, $wa_confirmation_greeting_text);

                                                $wa_confirmation_greeting_text = str_replace("&nbsp;", '', $wa_confirmation_greeting_text);
                                                $wa_confirmation_greeting_text = str_replace("\n", '%0A', $wa_confirmation_greeting_text);
                                                $wa_confirmation_greeting_text = str_replace("#", '%23', $wa_confirmation_greeting_text);
                                                $wa_confirmation_greeting_text = str_replace("-", '%2D', $wa_confirmation_greeting_text);
                                                $wa_confirmation_greeting_text = str_replace("&", '%26', $wa_confirmation_greeting_text);

                                            ?>
                                                <p><a <?php echo $style; ?> href="<?php echo $wa_base_url . 'send?phone=' . $wa_phone . '&text=' . esc_attr($wa_confirmation_greeting_text); ?>"><?php echo $cepatlakoo_wa_text; ?></a></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;
                                endif;
                            }
                        }
                        add_action('woocommerce_email_after_order_table', 'cepatlakoo_confirmation_button', 10, 2);
                       // add_action('woocommerce_view_order', 'cepatlakoo_confirmation_button', 9);

                        /**
                         * Functions to add site logo to checkout page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.2.5
                         *
                         */
                        if (!function_exists('cepatlakoo_checkout_logo ')) {
                            function cepatlakoo_checkout_logo()
                            {
                                if (is_page_template('template-simple-checkout.php')) {
                                    cepatlakoo_logo();
                                }
                            }
                        }
                        add_action('woocommerce_before_checkout_form', 'cepatlakoo_checkout_logo');

                        // Move coupon field
                        // remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
                        // add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

                        /**
                         * Functions to modify number of products per page
                         *
                         * @package WooCommerce
                         * @subpackage Cepatlakoo
                         * @since Cepatlakoo 1.3.0
                         *
                         */
                        if (!function_exists('cepatlakoo_loop_shop_per_page')) {
                            function cepatlakoo_loop_shop_per_page($cols)
                            {
                                global $cl_options;

                                // $cols contains the current number of products per page based on the value stored on Options -> Reading
                                // Return the number of products you wanna show per page.
                                $cols = isset($cl_options['cepatlakoo_shop_per_page']) ? $cl_options['cepatlakoo_shop_per_page'] : '12';
                                return $cols;
                            }
                        }
                        add_filter('loop_shop_per_page', 'cepatlakoo_loop_shop_per_page', 20);

                        /**
                         * Functions to hide coupon code in cart & checkout page
                         *
                         * @package WooCommerce
                         * @subpackage Cepatlakoo
                         * @since Cepatlakoo 1.3.3
                         *
                         */
                        if (!function_exists('cepatlakoo_disable_coupon')) {
                            function cepatlakoo_disable_coupon($enabled)
                            {
                                global $cl_options;

                                if (isset($cl_options['cepatlakoo_coupon_code']) && $cl_options['cepatlakoo_coupon_code'] == '0') {
                                    if (is_cart() || is_checkout()) {
                                        $enabled = false;
                                    }
                                }
                                return $enabled;
                            }
                        }
                        add_filter('woocommerce_coupons_enabled', 'cepatlakoo_disable_coupon');

                        /**
                         * Functions to display countdown timer & payment confirmation button in order-received page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.3.3
                         *
                         */
                        if (!function_exists('cepatlakoo_countdown_order_received ')) {
                            function cepatlakoo_countdown_order_received()
                            {
                                global $cl_options, $wp;

                                $order_id  = absint($wp->query_vars['order-received']);
                                $order = new WC_Order($order_id);
                                $order_status = $order->get_status();
                                $payment = $order->get_payment_method();

                                // check if countdown for order received page is activated in theme options
                                if ($cl_options['cepatlakoo_countdown_timer_order_received'] && ($order_status == 'pending' || $order_status == 'on-hold') && $payment != 'cod') {
                                    $cepatlakoo_countdown_timer = !empty($cl_options['cepatlakoo_countdown_order_received']) ? $cl_options['cepatlakoo_countdown_order_received'] : '';
                                    $cepatlakoo_confirmation_page = !empty($cl_options['cepatlakoo_select_confirmation']) ? $cl_options['cepatlakoo_select_confirmation'] : '';
                                    $cepatlakoo_countdown_text = !empty($cl_options['cepatlakoo_countdown_order_received_text']) ? $cl_options['cepatlakoo_countdown_order_received_text'] : '';

                                    if ($cepatlakoo_confirmation_page && $cepatlakoo_countdown_text) : ?>
                                        <div class="countdown-text"><?php echo nl2br($cepatlakoo_countdown_text); ?></div>
                                <?php endif;

                                    if ($cepatlakoo_countdown_timer) :
                                        echo do_shortcode('[cepatlakoo-countdown id="' . $cepatlakoo_countdown_timer . '"]');
                                    endif;
                                }
                            }
                        }
                        add_action('woocommerce_thankyou', 'cepatlakoo_countdown_order_received', 9);

                        /**
                         * Functions to display payment confirmation buttons on checkout order-received page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        if (!function_exists('cepatlakoo_checkout_payment_confirmation_buttons')) {
                            function cepatlakoo_checkout_payment_confirmation_buttons()
                            {
                                global $cl_options, $wp;

                                // get values from theme options
                                $payment_confirmation_buttons = $cl_options['cepatlakoo_payment_confirmation_buttons'];
                                $confirmation_page = $cl_options['cepatlakoo_select_confirmation'];
                                $web_confirmation_text = $cl_options['cepatlakoo_form_confirmation_text'];
                                $wa_confirmation_text = $cl_options['cepatlakoo_wa_confirmation_text'];
                                $wa_confirmation_number = $cl_options['cepatlakoo_wa_confirmation_number'];
                                $wa_confirmation_greeting_text = $cl_options['cepatlakoo_wa_confirmation_greeting_text'];
                                $order_id  = absint($wp->query_vars['order-received']);

                                $order = new WC_Order($order_id);
                                $order_status = $order->get_status();
                                $payment = $order->get_payment_method();
                                $order_data = $order->get_data(); // The Order data
                                $email = $order_data['billing']['email'];

                                if ($payment_confirmation_buttons == 'none' || ($order_status != 'pending' && $order_status != 'on-hold') || $payment == 'cod')
                                    return;
                                ?>
                                <p class="confirmation-buttons">
                                    <?php if (($payment_confirmation_buttons == 'web-form' || $payment_confirmation_buttons == 'all') && $confirmation_page) : ?>
                                        <a href="<?php echo get_permalink($confirmation_page) . '?orderid=' . $order_id . '&email=' . $email; ?>" class="confirmation-button web-form">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="M6 12h10v1h-10v-1zm7.816-3h-7.816v1h9.047c-.45-.283-.863-.618-1.231-1zm-7.816-2h6.5c-.134-.32-.237-.656-.319-1h-6.181v1zm13 3.975v2.568c0 4.107-6 2.457-6 2.457s1.518 6-2.638 6h-7.362v-20h9.5c.312-.749.763-1.424 1.316-2h-12.816v24h10.189c3.163 0 9.811-7.223 9.811-9.614v-3.886c-.623.26-1.297.421-2 .475zm4-6.475c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5 2.015-4.5 4.5-4.5 4.5 2.015 4.5 4.5zm-2.156-.882l-.696-.696-2.116 2.169-.992-.941-.696.697 1.688 1.637 2.812-2.866z" />
                                            </svg>
                                            <?php echo $web_confirmation_text; ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (($payment_confirmation_buttons == 'whatsapp' || $payment_confirmation_buttons == 'all') && $wa_confirmation_text && $wa_confirmation_number) :
                                        if (strpos($wa_confirmation_number, '-') !== false) {
                                            $wa_confirmation_number = str_replace('-', '', $wa_confirmation_number);
                                        }
                                        if (preg_match('[^\+62]', $wa_confirmation_number)) {
                                            $wa_phone = str_replace('+62', '62', $wa_confirmation_number);
                                        } else if ($wa_confirmation_number[0] == '0') {
                                            $wa_confirmation_number = ltrim($wa_confirmation_number, '0');
                                            $wa_phone = '62' . $wa_confirmation_number;
                                        } else if ($wa_confirmation_number[0] == '8') {
                                            $wa_phone = '62' . $wa_confirmation_number;
                                        } else {
                                            $wa_phone = $wa_confirmation_number;
                                        }

                                        if (strpos($wa_phone, "-")) {
                                            $wa_phone = str_replace('-', '', $wa_phone);
                                        }

                                        $wa_base_url = 'https://api.whatsapp.com/';

                                        $wa_confirmation_greeting_text = str_replace('%lakoo_order_id%', $order_id, $wa_confirmation_greeting_text);

                                        $wa_confirmation_greeting_text = str_replace("&nbsp;", '', $wa_confirmation_greeting_text);
                                        $wa_confirmation_greeting_text = str_replace("\n", '%0A', $wa_confirmation_greeting_text);
                                        $wa_confirmation_greeting_text = str_replace("#", '%23', $wa_confirmation_greeting_text);
                                        $wa_confirmation_greeting_text = str_replace("-", '%2D', $wa_confirmation_greeting_text);
                                        $wa_confirmation_greeting_text = str_replace("&", '%26', $wa_confirmation_greeting_text);

                                    ?>
                                        <a href="<?php echo $wa_base_url . 'send?phone=' . $wa_phone . '&text=' . esc_attr($wa_confirmation_greeting_text); ?>" class="confirmation-button whatsapp">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                            </svg>
                                            <?php echo $wa_confirmation_text; ?>
                                        </a>

                                    <?php endif; ?>
                                </p>
                                <?php
                            }
                        }
//                        add_action('woocommerce_thankyou', 'cepatlakoo_checkout_payment_confirmation_buttons', 9);

                        /**
                         * Functions to display product image on checkout page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.3.4
                         *
                         */
                        if (!function_exists('cepatlakoo_product_image_checkout ')) {
                            function cepatlakoo_product_image_checkout($cart_item_name, $cart_item)
                            {
                                global $cl_options;

                                if (isset($cl_options['cepatlakoo_checkout_product_image']) && $cl_options['cepatlakoo_checkout_product_image']) {
                                    return (is_checkout()) ? $cart_item['data']->get_image() . $cart_item_name : $cart_item_name;
                                } else {
                                    return (is_checkout()) ? $cart_item_name : $cart_item_name;
                                }
                            }
                        }
                        add_action('woocommerce_cart_item_name', 'cepatlakoo_product_image_checkout', 10, 2);

                        /**
                         * Functions to hide product tabs info in WooCommerce single product page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.3.4
                         *
                         */
                        if (!function_exists('cepatlakoo_remove_wc_product_tabs ')) {
                            function cepatlakoo_remove_wc_product_tabs($tabs)
                            {
                                global $cl_options;

                                if (empty($cl_options['cepatlakoo_wc_product_tabs'])) {
                                    unset($tabs['description']);
                                    unset($tabs['reviews']);
                                    unset($tabs['additional_information']);
                                }

                                if (empty($cl_options['cepatlakoo_wc_product_tabs_description']))
                                    unset($tabs['description']);

                                if (empty($cl_options['cepatlakoo_wc_product_tabs_reviews']))
                                    unset($tabs['reviews']);

                                if (empty($cl_options['cepatlakoo_wc_product_tabs_additional']))
                                    unset($tabs['additional_information']);

                                return $tabs;
                            }
                        }
                        add_filter('woocommerce_product_tabs', 'cepatlakoo_remove_wc_product_tabs', 98);

                        /**
                         * Functions to display additional badge to product page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.3.4
                         *
                         */
                        if (!function_exists('cepatlakoo_add_badge_wc_product_page')) {
                            function cepatlakoo_add_badge_wc_product_page()
                            {
                                global $cl_options;
                                $badges = is_array($cl_options['cepatlakoo_product_badges']) ? $cl_options['cepatlakoo_product_badges'] : [$cl_options['cepatlakoo_product_badges']];

                                if (isset($cl_options['cepatlakoo_product_badges']) && array_filter($badges)) : ?>
                                    <div class="cl-product-badges">
                                        <?php foreach ($badges as $key => $value) :
                                            $value = trim($value);
                                            if (!empty($value)) : ?>
                                                <span><?php echo $value; ?></span>
                                        <?php endif;
                                        endforeach ?>
                                    </div>
                                <?php endif;

                                // print_r( $badges );
                            }
                        }
                        add_action('woocommerce_single_product_summary', 'cepatlakoo_add_badge_wc_product_page', 1);

                        /**
                         * Functions to add meta print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        add_action('add_meta_boxes', 'cepatlakoo_shipping_label_meta_box');
                        if (!function_exists('cepatlakoo_shipping_label_meta_box')) {
                            function cepatlakoo_shipping_label_meta_box()
                            {
                                global $cl_options;
                                if (isset($cl_options['cepatlakoo_shipping_label_status']) && $cl_options['cepatlakoo_shipping_label_status'] == true) {
                                    add_meta_box('cl-shipping-label', __('Print Shipping Label', 'cepatlakoo'), 'cepatlakoo_shipping_label_detail', 'shop_order', 'side', 'core');
                                }
                            }
                        }

                        /**
                         * Functions to generate content meta box print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        if (!function_exists('cepatlakoo_shipping_label_detail')) {
                            function cepatlakoo_shipping_label_detail()
                            {
                                $url = admin_url('admin-ajax.php?print-order=' . get_the_ID() . '&action=cl_shipping_label'); ?>

                                <a href="<?php echo urldecode(esc_url_raw($url)); ?>" target="_blank" class="button save_order button-primary cl-print-preview-button" id="woocommerce-delivery-notes-bulk-print-button"><?php _e('Print now', 'cepatlakoo') ?></a>
                                <?php
                            }
                        }

                        /**
                         * Functions to add action print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        if (!function_exists('cepatlakoo_order_bulk_action')) {
                            function cepatlakoo_order_bulk_action($actions)
                            {
                                global $cl_options;
                                if (isset($cl_options['cepatlakoo_shipping_label_status']) && $cl_options['cepatlakoo_shipping_label_status'] == true) {
                                    $actions['cl_bulk_shipping_note'] = __('Print Shipping Label', 'cepatlakoo');
                                }
                                return $actions;
                            }
                        }
                        add_filter('bulk_actions-edit-shop_order', 'cepatlakoo_order_bulk_action');

                        /**
                         * Functions to add action print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        if (!function_exists('cepatlakoo_bulk_action_handler')) {
                            function cepatlakoo_bulk_action_handler($redirect_to, $action, $post_ids)
                            {
                                if ($action == 'cl_bulk_shipping_note' && count($post_ids) > 0) {
                                    $redirect_to = add_query_arg('cl_bulk_shipping_note', 'true', $redirect_to);
                                    $redirect_to = add_query_arg('order-id', implode("-", $post_ids), $redirect_to);
                                    return $redirect_to;
                                }

                                return $redirect_to;
                            }
                        }
                        add_filter('handle_bulk_actions-edit-shop_order', 'cepatlakoo_bulk_action_handler', 10, 3);

                        /**
                         * Functions to add notification ready print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        add_action('admin_notices', 'cepatlakoo_bulk_action_admin_notice');
                        if (!function_exists('cepatlakoo_bulk_action_admin_notice')) {
                            function cepatlakoo_bulk_action_admin_notice()
                            {
                                if (isset($_REQUEST['cl_bulk_shipping_note'])) :
                                    $url = admin_url('admin-ajax.php?print-order=' . $_REQUEST['order-id'] . '&action=cl_shipping_label');
                                ?>
                                    <div id="cl-bulk-print-message" class="updated">
                                        <p><?php _e('Shipping slip(s) is now ready to print.', 'cepatlakoo'); ?> <a href="<?php echo urldecode(esc_url_raw($url)); ?>" target="_blank" class="cl-print-preview-button" id="cl-bulk-shipping-label-button"><?php _e('Print Now', 'cepatlakoo') ?></a> <span class="print-preview-loading spinner"></span></p>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            window.open("<?php echo $url; ?>", "_blank"); // will open new tab on document ready
                                            // window.history.replaceState({}, document.title, "/");
                                        });
                                    </script>
                                <?php endif;
                            }
                        }

                        /**
                         * Functions to load tempalte print shipping label
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since Cepatlakoo 1.4.0
                         *
                         */
                        add_action('wp_ajax_cl_shipping_label', 'cepatlakoo_print_template');
                        if (!function_exists('cepatlakoo_print_template')) {
                            function cepatlakoo_print_template()
                            {
                                global $cl_options;
                                if (is_admin() && current_user_can('edit_shop_orders') && !empty($_REQUEST['print-order']) && !empty($_REQUEST['action']) && isset($cl_options['cepatlakoo_shipping_label_status']) && $cl_options['cepatlakoo_shipping_label_status'] == true) {
                                    $order_ids = sanitize_text_field($_GET['print-order']);
                                    get_template_part('includes/print-label');

                                    exit;
                                }
                            }
                        }

                        /**
                         * Functions to change add to cart text on single product page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        add_filter('woocommerce_product_single_add_to_cart_text', 'cepatlakoo_single_add_to_cart_text');
                        if (!function_exists('cepatlakoo_single_add_to_cart_text')) {
                            function cepatlakoo_single_add_to_cart_text()
                            {
                                global $cl_options;
                                $cart_button_text = !empty($cl_options['cepatlakoo_add_to_cart_text']) ? $cl_options['cepatlakoo_add_to_cart_text'] : '';

                                return $cart_button_text;
                            }
                        }

                        /**
                         * Functions to change add to cart text on product archives page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        add_filter('woocommerce_product_add_to_cart_text', 'cepatlakoo_archive_add_to_cart_text');
                        if (!function_exists('cepatlakoo_archive_add_to_cart_text')) {
                            function cepatlakoo_archive_add_to_cart_text($cart_button_text)
                            {
                                if ($cart_button_text == __('Add to cart', 'woocommerce') || $cart_button_text == __('Select options', 'woocommerce')) {
                                    global $cl_options;
                                    $cart_button_text = $cl_options['cepatlakoo_add_to_cart_text'];
                                } else if ($cart_button_text == __('Read more', 'woocommerce')) {
                                    $cart_button_text = __('Out of Stock', 'woocommerce');
                                }
                                return $cart_button_text;
                            }
                        }

                        /**
                         * Functions to change proceed to checkout on cart page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        if (!function_exists('cepatlakoo_wc_proceed_to_checkout_button')) {
                            function cepatlakoo_wc_proceed_to_checkout_button()
                            {
                                global $cl_options;
                                $proceed_to_checkout_text = $cl_options['cepatlakoo_proceed_to_checkout_text'];

                                echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="checkout-button button alt wc-forward">' . $proceed_to_checkout_text . '</a>';
                            }
                        }
                        remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
                        add_action('woocommerce_proceed_to_checkout', 'cepatlakoo_wc_proceed_to_checkout_button', 20);

                        /**
                         * Functions to change place order text on checkout page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        add_filter('woocommerce_order_button_text', 'cepatlakoo_place_order_text');
                        if (!function_exists('cepatlakoo_place_order_text')) {
                            function cepatlakoo_place_order_text($button_text)
                            {
                                global $cl_options;
                                $checkout_button_text = $cl_options['cepatlakoo_place_order_text'];

                                return $checkout_button_text;
                            }
                        }

                        /**
                         * Functions to overide function order tracking
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        if (!function_exists('cepatlakoo_woocommerce_order_tracking')) {
                            function cepatlakoo_woocommerce_order_tracking($atts)
                            {
                                if (is_null(WC()->cart)) {
                                    return;
                                }
                                $atts        = shortcode_atts(array(), $atts, 'woocommerce_order_tracking');
                                $nonce_value = wc_get_var($_REQUEST['woocommerce-order-tracking-nonce'], wc_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.

                                ob_start();
                                if (isset($_REQUEST['orderid']) && wp_verify_nonce($nonce_value, 'woocommerce-order_tracking')) { // WPCS: input var ok.

                                    $order_id    = empty($_REQUEST['orderid']) ? 0 : ltrim(wc_clean(wp_unslash($_REQUEST['orderid'])), '#'); // WPCS: input var ok.
                                    $order_phone = empty($_REQUEST['order_phone']) ? '' : wc_clean(wp_unslash($_REQUEST['order_phone'])); // WPCS: input var ok.

                                    if (!$order_id) {
                                        wc_print_notice(__('Please enter a valid order ID', 'woocommerce'), 'error');
                                    } elseif (!$order_phone) {
                                        wc_print_notice(__('Please enter a valid phone number', 'woocommerce'), 'error');
                                    } else {
                                        $order = wc_get_order(apply_filters('woocommerce_shortcode_order_tracking_order_id', $order_id));

                                        if ($order && $order->get_id() && strtolower($order->get_billing_phone()) === strtolower($order_phone)) {
                                            do_action('woocommerce_track_order', $order->get_id());
                                            wc_get_template(
                                                'order/tracking.php',
                                                array(
                                                    'order' => $order,
                                                )
                                            );
                                            return;
                                        } else {
                                            wc_print_notice(__('Sorry, the order could not be found. Please contact us if you are having difficulty finding your order details.', 'woocommerce'), 'error');
                                        }
                                    }
                                }

                                get_template_part('includes/wc-order-tracking-form');
                                $return = ob_get_contents();
                                ob_end_clean();

                                return $return;
                            }
                        }

                        /**
                         * Functions to render sticky buttons in single product page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         *
                         */
                        if (!function_exists('cepatlakoo_sticky_buttons')) {
                            function cepatlakoo_sticky_buttons()
                            {
                                global $cl_options;

                                $cl_sticky = $cl_options['cepatlakoo_single_product_button_type'];
                                $cepatlakoo_add_to_cart_text = !empty($cl_options['cepatlakoo_add_to_cart_text']) ? $cl_options['cepatlakoo_add_to_cart_text'] : '';
                                $cepatlakoo_cookie_timer = !empty($cl_options['cepatlakoo_wa_rotator_cookies']) ? $cl_options['cepatlakoo_wa_rotator_cookies'] : 'none';
                                $all_cs = !empty($cl_options['cepatlakoo_wa_rotator_cs']) ? $cl_options['cepatlakoo_wa_rotator_cs'] : array();
                                $cepatlakoo_cart_wa = preg_split('/\r\n|[\r\n]/', $all_cs);
                                $cepatlakoo_cart_wa = (count($cepatlakoo_cart_wa) > 1) ? $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)] : $cepatlakoo_cart_wa[0];
                                $data = explode('|', $cepatlakoo_cart_wa);
                                $cepatlakoo_cart_wa = isset($data[1]) ? $data[1] : '';
                                $name = $data[0];

                                if (is_array($cepatlakoo_cart_wa)) {
                                    $cepatlakoo_cart_wa = $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)];
                                }

                                if ($cepatlakoo_cart_wa) {
                                    if (strpos($cepatlakoo_cart_wa, '-') !== false) {
                                        $cepatlakoo_cart_wa = str_replace('-', '', $cepatlakoo_cart_wa);
                                    }
                                    if (preg_match('[^\+62]', $cepatlakoo_cart_wa)) {
                                        $cepatlakoo_cart_wa = str_replace('+62', '62', $cepatlakoo_cart_wa);
                                    } else if ($cepatlakoo_cart_wa[0] == '0') {
                                        $cepatlakoo_cart_wa = ltrim($cepatlakoo_cart_wa, '0');
                                        $cepatlakoo_cart_wa = '62' . $cepatlakoo_cart_wa;
                                    } else if ($cepatlakoo_cart_wa[0] == '8') {
                                        $cepatlakoo_cart_wa = '62' . $cepatlakoo_cart_wa;
                                    } else {
                                        $cepatlakoo_cart_wa = $cepatlakoo_cart_wa;
                                    }
                                }

                                if (wp_is_mobile()) : ?>
                                    <div class="floating-product-actions">
                                        <div class="fpa-row">
                                            <?php if ($cl_sticky == 'whatsapp' || $cl_sticky == 'both') :
                                                $cepatlakoo_cart_wa_text = !empty($cl_options['cepatlakoo_cart_wa_text']) ? $cl_options['cepatlakoo_cart_wa_text'] : '';
                                                $cepatlakoo_cart_wa_text = str_replace('%lakoo_wa_cs%', $name, $cepatlakoo_cart_wa_text);
                                                $cepatlakoo_cart_wa_message = !empty($cl_options['cepatlakoo_cart_wa_message']) ? $cl_options['cepatlakoo_cart_wa_message'] : '';
                                                $cepatlakoo_cart_wa_event = !empty($cl_options['cepatlakoo_cart_wa_event']) ? $cl_options['cepatlakoo_cart_wa_event'] : '';

                                                $product_name = get_the_title(get_the_ID());
                                                $product_url = urlencode(get_permalink(get_the_ID()));
                                                $cepatlakoo_cart_wa_message = str_replace('%lakoo_product_name%', $product_name, $cepatlakoo_cart_wa_message);
                                                $cepatlakoo_cart_wa_message = str_replace('%lakoo_product_url%', $product_url, $cepatlakoo_cart_wa_message);
                                                $cepatlakoo_cart_wa_message = cepatlakoo_replace_hexcode($cepatlakoo_cart_wa_message);

                                                $wa_base_url = 'https://api.whatsapp.com/';

                                                if ($cepatlakoo_cookie_timer != 'none') {
                                                    $set_curr_time = date('Y/m/d H:i:s e');

                                                    if ($cepatlakoo_cookie_timer == '1week')
                                                        $add = '+7 days';
                                                    else if ($cepatlakoo_cookie_timer == '2weeks')
                                                        $add = '+14 days';
                                                    else if ($cepatlakoo_cookie_timer == '3weeks')
                                                        $add = '+21 days';
                                                    else if ($cepatlakoo_cookie_timer == '1month')
                                                        $add = '+1 month';
                                                    else if ($cepatlakoo_cookie_timer == '3months')
                                                        $add = '+3 month';
                                                    else if ($cepatlakoo_cookie_timer == '6months')
                                                        $add = '+6 month';
                                                    else if ($cepatlakoo_cookie_timer == '1year')
                                                        $add = '+1 year';
                                                    else
                                                        $add = '+10 year';

                                                    $set_new_date = date('Y-m-d H:i:s e', strtotime($add, strtotime($set_curr_time)));

                                                    wp_localize_script('cepatlakoo-functions', 'cl_wa_rotator', array(
                                                        'cl_wa_cookies_name'        => 'cl-wa-rotator-cookies',
                                                        'cl_wa_start_cookie'        => $set_curr_time,
                                                        'cl_wa_expired_cookie'      => $set_new_date,
                                                        'cl_wa_number'              => (int)$cepatlakoo_cart_wa,
                                                        'cl_wa_href'              => $wa_base_url . 'send?l=id&phone=' . (int)$cepatlakoo_cart_wa . '&text=' . esc_attr($cepatlakoo_cart_wa_message),
                                                        'cl_wa_label'               => $cepatlakoo_cart_wa_text,
                                                    ));
                                                }
                                            ?>
                                                <a class="fpa-btn fpa-whatsapp-btn" href="<?php echo $wa_base_url; ?>send?l=id&phone=<?php echo (int)$cepatlakoo_cart_wa; ?>&text=<?php echo esc_attr($cepatlakoo_cart_wa_message); ?>" fb-pixel="<?php echo $cepatlakoo_cart_wa_event; ?>" title="<?php echo $cepatlakoo_cart_wa_text; ?>" data-number="<?php echo (int)$cepatlakoo_cart_wa; ?>">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.6221 4.34209C19.3417 2.05902 16.309 0.801144 13.0781 0.799805C6.4205 0.799805 1.00218 6.21798 0.999502 12.8773C0.998612 15.0062 1.55473 17.0842 2.61176 18.9159L0.898193 25.1748L7.30125 23.4952C9.06555 24.4576 11.0518 24.9647 13.0732 24.9653H13.0783C19.7351 24.9653 25.154 19.5467 25.1566 12.887C25.1579 9.65952 23.9027 6.62499 21.6221 4.34209ZM13.0781 22.9255H13.074C11.2726 22.9248 9.50592 22.4407 7.96432 21.5261L7.59791 21.3085L3.79823 22.3053L4.81242 18.6007L4.57363 18.2209C3.56867 16.6224 3.03799 14.775 3.03889 12.8781C3.04097 7.34298 7.54461 2.83978 13.0821 2.83978C15.7636 2.84067 18.2843 3.88625 20.1796 5.78386C22.075 7.68144 23.1182 10.2038 23.1173 12.8863C23.1149 18.4218 18.6116 22.9255 13.0781 22.9255ZM18.5848 15.4066C18.2831 15.2555 16.7992 14.5256 16.5225 14.4247C16.2461 14.324 16.0447 14.2739 15.8436 14.5759C15.6424 14.8779 15.0641 15.5578 14.8879 15.7591C14.7118 15.9606 14.5359 15.9858 14.2341 15.8347C13.9322 15.6837 12.9598 15.3648 11.807 14.3367C10.9099 13.5364 10.3043 12.5481 10.1281 12.2461C9.95221 11.9438 10.1266 11.7962 10.2605 11.6302C10.5872 11.2245 10.9143 10.7992 11.0149 10.5979C11.1156 10.3964 11.0652 10.2201 10.9896 10.0691C10.9143 9.91809 10.3108 8.43258 10.0593 7.82814C9.81415 7.23989 9.56559 7.31932 9.38021 7.31009C9.20432 7.30132 9.00308 7.29956 8.80178 7.29956C8.6006 7.29956 8.27365 7.37496 7.99688 7.67728C7.72031 7.97947 6.94076 8.70948 6.94076 10.195C6.94076 11.6805 8.02217 13.1156 8.17303 13.317C8.3239 13.5184 10.3013 16.5668 13.3286 17.8739C14.0487 18.1851 14.6108 18.3707 15.0492 18.5098C15.7722 18.7395 16.43 18.7071 16.95 18.6294C17.53 18.5426 18.7355 17.8992 18.9872 17.1943C19.2386 16.4893 19.2386 15.8851 19.1631 15.7591C19.0878 15.6332 18.8865 15.5578 18.5848 15.4066Z" fill="white" />
                                                    </svg>
                                                    <span><?php echo $cepatlakoo_cart_wa_text; ?></span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cl_sticky == 'addtocart' || $cl_sticky == 'both') : ?>
                                                <a class="fpa-btn fpa-cart-btn" href="#" onclick="jQuery('html,body').animate({scrollTop:jQuery('.single_add_to_cart_button').offset().top-200}, 200);" title="<?php echo $cepatlakoo_add_to_cart_text; ?>">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                                                    </svg>
                                                    <span><?php echo $cepatlakoo_add_to_cart_text; ?></span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php
                            }
                        }

                        /**
                         * Function to display WhatsApp button right after add to cart button
                         * in single product page
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.0.0
                         */
                        if (!function_exists('cepatlakoo_wc_product_whatsapp_button')) {
                            function cepatlakoo_wc_product_whatsapp_button()
                            {
                                global $cl_options;

                                $all_cs = !empty($cl_options['cepatlakoo_wa_rotator_cs']) ? $cl_options['cepatlakoo_wa_rotator_cs'] : array();
                                $cepatlakoo_cookie_timer = !empty($cl_options['cepatlakoo_wa_rotator_cookies']) ? $cl_options['cepatlakoo_wa_rotator_cookies'] : 'none';
                                $cepatlakoo_cart_wa = preg_split('/\r\n|[\r\n]/', $all_cs);
                                $cepatlakoo_cart_wa = array_filter($cepatlakoo_cart_wa, function ($value) {
                                    return !is_null($value) && $value !== '';
                                });
                                $cepatlakoo_cart_wa = (count($cepatlakoo_cart_wa) > 1) ? $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)] : $cepatlakoo_cart_wa[0];
                                $data = explode('|', $cepatlakoo_cart_wa);
                                $cepatlakoo_cart_wa = $data[1];
                                $name = $data[0];

                                if (is_array($cepatlakoo_cart_wa)) {
                                    $cepatlakoo_cart_wa = $cepatlakoo_cart_wa[array_rand($cepatlakoo_cart_wa)];
                                }

                                if ($cepatlakoo_cart_wa) {
                                    if (strpos($cepatlakoo_cart_wa, '-') !== false) {
                                        $cepatlakoo_cart_wa = str_replace('-', '', $cepatlakoo_cart_wa);
                                    }
                                    if (preg_match('[^\+62]', $cepatlakoo_cart_wa)) {
                                        $cepatlakoo_cart_wa = str_replace('+62', '62', $cepatlakoo_cart_wa);
                                    } else if ($cepatlakoo_cart_wa[0] == '0') {
                                        $cepatlakoo_cart_wa = ltrim($cepatlakoo_cart_wa, '0');
                                        $cepatlakoo_cart_wa = '62' . $cepatlakoo_cart_wa;
                                    } else if ($cepatlakoo_cart_wa[0] == '8') {
                                        $cepatlakoo_cart_wa = '62' . $cepatlakoo_cart_wa;
                                    } else {
                                        $cepatlakoo_cart_wa = $cepatlakoo_cart_wa;
                                    }
                                }

                                $cepatlakoo_cart_wa_text = !empty($cl_options['cepatlakoo_cart_wa_text']) ? $cl_options['cepatlakoo_cart_wa_text'] : '';
                                $cepatlakoo_cart_wa_text = str_replace('%lakoo_wa_cs%', $name, $cepatlakoo_cart_wa_text);
                                $cepatlakoo_cart_wa_message = !empty($cl_options['cepatlakoo_cart_wa_message']) ? $cl_options['cepatlakoo_cart_wa_message'] : '';
                                $cepatlakoo_cart_wa_event = !empty($cl_options['cepatlakoo_cart_wa_event']) ? $cl_options['cepatlakoo_cart_wa_event'] : '';

                                $product_name = get_the_title(get_the_ID());
                                $product_url = urlencode(get_permalink(get_the_ID()));
                                $cepatlakoo_cart_wa_message = str_replace('%lakoo_product_name%', $product_name, $cepatlakoo_cart_wa_message);
                                $cepatlakoo_cart_wa_message = str_replace('%lakoo_product_url%', $product_url, $cepatlakoo_cart_wa_message);
                                $cepatlakoo_cart_wa_message = cepatlakoo_replace_hexcode($cepatlakoo_cart_wa_message);

                                if ($cepatlakoo_cart_wa) :
                                    $wa_base_url = 'https://api.whatsapp.com/';

                                    if ($cepatlakoo_cookie_timer != 'none') {
                                        $set_curr_time = date('Y/m/d H:i:s e');

                                        if ($cepatlakoo_cookie_timer == '1week')
                                            $add = '+7 days';
                                        else if ($cepatlakoo_cookie_timer == '2weeks')
                                            $add = '+14 days';
                                        else if ($cepatlakoo_cookie_timer == '3weeks')
                                            $add = '+21 days';
                                        else if ($cepatlakoo_cookie_timer == '1month')
                                            $add = '+1 month';
                                        else if ($cepatlakoo_cookie_timer == '3months')
                                            $add = '+3 month';
                                        else if ($cepatlakoo_cookie_timer == '6months')
                                            $add = '+6 month';
                                        else if ($cepatlakoo_cookie_timer == '1year')
                                            $add = '+1 year';
                                        else
                                            $add = '+10 year';

                                        $set_new_date = date('Y-m-d H:i:s e', strtotime($add, strtotime($set_curr_time)));

                                        wp_localize_script('cepatlakoo-functions', 'cl_wa_rotator', array(
                                            'cl_wa_cookies_name'        => 'cl-wa-rotator-cookies',
                                            'cl_wa_start_cookie'        => $set_curr_time,
                                            'cl_wa_expired_cookie'      => $set_new_date,
                                            'cl_wa_number'              => (int)$cepatlakoo_cart_wa,
                                            'cl_wa_href'              => $wa_base_url . 'send?l=id&phone=' . (int)$cepatlakoo_cart_wa . '&text=' . esc_attr($cepatlakoo_cart_wa_message),
                                            'cl_wa_label'               => $cepatlakoo_cart_wa_text,
                                        ));
                                    } ?>

                                    <div class="buy-via-whatsapp">
                                        <a href="<?php echo $wa_base_url; ?>send?l=id&phone=<?php echo (int)$cepatlakoo_cart_wa; ?>&text=<?php echo esc_attr($cepatlakoo_cart_wa_message); ?>" fb-pixel="<?php echo $cepatlakoo_cart_wa_event; ?>" title="<?php echo $cepatlakoo_cart_wa_text; ?>" class="button contact-wa-button" data-number="<?php echo (int)$cepatlakoo_cart_wa; ?>">
                                            <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.6221 4.34209C19.3417 2.05902 16.309 0.801144 13.0781 0.799805C6.4205 0.799805 1.00218 6.21798 0.999502 12.8773C0.998612 15.0062 1.55473 17.0842 2.61176 18.9159L0.898193 25.1748L7.30125 23.4952C9.06555 24.4576 11.0518 24.9647 13.0732 24.9653H13.0783C19.7351 24.9653 25.154 19.5467 25.1566 12.887C25.1579 9.65952 23.9027 6.62499 21.6221 4.34209ZM13.0781 22.9255H13.074C11.2726 22.9248 9.50592 22.4407 7.96432 21.5261L7.59791 21.3085L3.79823 22.3053L4.81242 18.6007L4.57363 18.2209C3.56867 16.6224 3.03799 14.775 3.03889 12.8781C3.04097 7.34298 7.54461 2.83978 13.0821 2.83978C15.7636 2.84067 18.2843 3.88625 20.1796 5.78386C22.075 7.68144 23.1182 10.2038 23.1173 12.8863C23.1149 18.4218 18.6116 22.9255 13.0781 22.9255ZM18.5848 15.4066C18.2831 15.2555 16.7992 14.5256 16.5225 14.4247C16.2461 14.324 16.0447 14.2739 15.8436 14.5759C15.6424 14.8779 15.0641 15.5578 14.8879 15.7591C14.7118 15.9606 14.5359 15.9858 14.2341 15.8347C13.9322 15.6837 12.9598 15.3648 11.807 14.3367C10.9099 13.5364 10.3043 12.5481 10.1281 12.2461C9.95221 11.9438 10.1266 11.7962 10.2605 11.6302C10.5872 11.2245 10.9143 10.7992 11.0149 10.5979C11.1156 10.3964 11.0652 10.2201 10.9896 10.0691C10.9143 9.91809 10.3108 8.43258 10.0593 7.82814C9.81415 7.23989 9.56559 7.31932 9.38021 7.31009C9.20432 7.30132 9.00308 7.29956 8.80178 7.29956C8.6006 7.29956 8.27365 7.37496 7.99688 7.67728C7.72031 7.97947 6.94076 8.70948 6.94076 10.195C6.94076 11.6805 8.02217 13.1156 8.17303 13.317C8.3239 13.5184 10.3013 16.5668 13.3286 17.8739C14.0487 18.1851 14.6108 18.3707 15.0492 18.5098C15.7722 18.7395 16.43 18.7071 16.95 18.6294C17.53 18.5426 18.7355 17.8992 18.9872 17.1943C19.2386 16.4893 19.2386 15.8851 19.1631 15.7591C19.0878 15.6332 18.8865 15.5578 18.5848 15.4066Z" fill="white" />
                                            </svg>
                                            <?php echo $cepatlakoo_cart_wa_text; ?>
                                        </a>
                                    </div>
                                <?php endif;
                            }
                        }

                        /**
                         * Functions to show product stock on product loop
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.1.0
                         * Based on codes from Woocommerce Show Stock plugin by Team Bright Vessel
                         */
                        if (!function_exists('cepatlakoo_wc_product_loop_stock')) {
                            function cepatlakoo_wc_product_loop_stock()
                            {
                                global $product;

                                $low_stock_notify = 3;
                                if (get_option('woocommerce_notify_low_stock_amount')) {
                                    $low_stock_notify = get_option('woocommerce_notify_low_stock_amount');
                                }

                                if (!$product->is_type('variable')) {

                                    if ($product->get_stock_quantity()) {
                                        if ($product->managing_stock() && $product->is_in_stock()) {
                                            echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo');

                                            if ($product->get_stock_quantity()) {
                                                echo ' ' . number_format($product->get_stock_quantity(), 0, '', '');
                                            }

                                            echo '</span>';
                                        } elseif (number_format($product->get_stock_quantity(), 0, '', '') < $low_stock_notify) {
                                            echo '<span class="cl-item-stock">';
                                            printf(esc_html__('Only %s left in stock!', 'cepatlakoo'), number_format($product->get_stock_quantity(), 0, '', ''));
                                            echo '</span>';
                                        }
                                    } elseif ($product->is_on_backorder()) {
                                        echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo') . ' ' . esc_html__('Backorder', 'cepatlakoo') . '</span>';
                                    } elseif ($product->is_in_stock()) {
                                        echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo') . ' ' . esc_html__('Available', 'cepatlakoo') . '</span>';
                                    } else {
                                        echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo') . ' ' . esc_html__('Out of stock', 'cepatlakoo') . '</span>';
                                    }
                                } else {
                                    if ($product->managing_stock() && $product->get_stock_quantity()) { // if manage stock is enabled
                                        $product_variations = $product->get_available_variations();
                                        $stock = 0;

                                        foreach ($product_variations as $variation) {
                                            $stock = (int)$stock + (int)$variation['max_qty'];
                                        }

                                        if ($stock > 0) {
                                            if (number_format($stock, 0, '', '') < $low_stock_notify) {
                                                echo '<span class="cl-item-stock">';
                                                printf(esc_html__('Only %s left in stock!', 'cepatlakoo'), number_format($stock, 0, '', ''));
                                                echo '</span>';
                                            } else {
                                                echo '<span class="cl-item-stock">';
                                                printf(esc_html__('%s left in stock', 'cepatlakoo'), number_format($stock, 0, '', ''));
                                                echo '</span>';
                                            }
                                        }
                                    } elseif ($product->backorders_allowed()) {
                                        echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo') . ' ' . esc_html__('Backorder', 'cepatlakoo') . '</span>';
                                    } elseif ($product->is_in_stock()) {
                                        echo '<span class="cl-item-stock">' . esc_html__('Stock:', 'cepatlakoo') . ' ' . esc_html__('Available', 'cepatlakoo') . '</span>';
                                    } else {
                                        echo '<span class="cl-item-stock">' . esc_html__('Out of stock', 'cepatlakoo') . '</span>';
                                    }
                                }
                            }
                        }

                        /**
                         * Functions to change button checkout via wa
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.1.0
                         *
                         */
                        if (!function_exists('cepatlakoo_woocommerce_checkout_btn')) {
                            function cepatlakoo_woocommerce_checkout_btn()
                            {
                                return '<input type="hidden" name="action" value="cl_checkout_wa"><button class="button alt cl-checkout-whatsapp" id="place_order" value="' . __('Checkout via WhatsApp', 'cepatlakoo') . '" data-value="' . __('Checkout via WhatsApp', 'cepatlakoo') . '">' . __('Checkout via WhatsApp', 'cepatlakoo') . '</button>';
                            }
                        }

                        /**
                         * Functions to process order with whatsapp
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.1.0
                         */
                        if (!function_exists('cepatlakoo_submit_checkout_wa')) {
                            function cepatlakoo_submit_checkout_wa()
                            {
                                $checkout = new CL_Checkout();
                                $checkout->process_checkout();
                            }
                        }
                        add_action('wp_ajax_cl_checkout_wa', 'cepatlakoo_submit_checkout_wa');
                        add_action('wp_ajax_nopriv_cl_checkout_wa', 'cepatlakoo_submit_checkout_wa');

                        /**
                         * Function to pre fill on konfirmasi form
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.2.0
                         */

                        function cl_check_confirm_data()
                        {
                            $nonce = $_POST['nonce'];

                            // var_dump($_POST);
                            if (!wp_verify_nonce($nonce, 'cl-ajax-nonce')) {
                                die('Nonce value cannot be verified.');
                            }

                            // The $_REQUEST contains all the data sent via ajax
                            if (isset($_POST)) {
                                $email = isset($_POST['email']) ? $_POST['email'] : '';
                                $code = isset($_POST['code']) ? $_POST['code'] : '';
                                // $code = 42904;

                                $check = wc_get_order($code);

                                if (!empty($email) && !empty($code) && $check) {
                                    $order = new WC_Order($code);
                                    // If you're debugging, it might be useful to see what was sent in the $_REQUEST

                                    $order_data = $order->get_data(); // The Order data

                                    if ($order_data['billing']['email'] == $email) {
                                        $return = [
                                            'email' => $order_data['billing']['email'],
                                            'name' => $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'],
                                            'total' => (int) $order->get_total(),
                                        ];
                                        wp_send_json([
                                            'status' => true,
                                            'message' => 'Data found',
                                            'data' => $return
                                        ]);
                                    } else {
                                        wp_send_json([
                                            'status' => false,
                                            'message' => 'Email and id not match'
                                        ]);
                                    }
                                } else {
                                    wp_send_json([
                                        'status' => false,
                                        'message' => 'Data not found'
                                    ]);
                                }
                            } else {
                                wp_send_json([
                                    'status' => false,
                                    'message' => 'Parameter not found'
                                ]);
                            }

                            // Always die in functions echoing ajax content
                            die();
                        }

                        add_action('wp_ajax_cl_check_confirm_data', 'cl_check_confirm_data');
                        add_action('wp_ajax_nopriv_cl_check_confirm_data', 'cl_check_confirm_data');

                        /**
                         * Function to display fragment cart as popup
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.4.1
                         */
                        if (!function_exists('cepatlakoo_popup_cart_content')) {
                            function cepatlakoo_popup_cart_content()
                            {
                                global $woocommerce, $cl_options;

                                $style = (isset($cl_options['cepatlakoo_cart_type']) && !empty($cl_options['cepatlakoo_cart_type'])) ? $cl_options['cepatlakoo_cart_type'] : 'sidebar';
                                $wc_ajax_enable = !empty( get_option('woocommerce_enable_ajax_add_to_cart') ) ? get_option('woocommerce_enable_ajax_add_to_cart') : 'no';
                                // var_dump( get_option('woocommerce_enable_ajax_add_to_cart') ); exit();
                                ?>
                                <?php if (class_exists('WooCommerce') && $style == 'popup') :  ?>

                                    <div class="cl-modal-cart" style="display: none;">
                                        <div class="modal-body">
                                            <div class="modal-content">
                                                <!-- <div class="cl-cart-popup-holders"> -->
                                                <div class="cart-holders">
                                                    <?php if ($woocommerce->cart->cart_contents_count > 0) : ?>
                                                        <div class="cart-header modal-head">
                                                            <div class="cart-icon">
                                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                                                                </svg>
                                                            </div>
                                                            <div class="cart-info">
                                                                <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                                                                <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                                                            </div>
                                                            <div class="close-cart">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="cart-header modal-head">
                                                            <div class="cart-icon empty">
                                                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M30.4297 28C30.9505 28 31.4388 28.0977 31.8945 28.293C32.3503 28.4883 32.7474 28.7552 33.0859 29.0938C33.4245 29.4323 33.6914 29.8294 33.8867 30.2852C34.082 30.7409 34.1797 31.2292 34.1797 31.75C34.1797 32.2708 34.082 32.7591 33.8867 33.2148C33.6914 33.6706 33.4245 34.0677 33.0859 34.4062C32.7474 34.7448 32.3503 35.0117 31.8945 35.207C31.4388 35.4023 30.9505 35.5 30.4297 35.5C29.9089 35.5 29.4206 35.4023 28.9648 35.207C28.5091 35.0117 28.112 34.7448 27.7734 34.4062C27.4349 34.0677 27.168 33.6706 26.9727 33.2148C26.7773 32.7591 26.6797 32.2708 26.6797 31.75C26.6797 31.3464 26.7513 30.9297 26.8945 30.5H16.4648C16.6081 30.9297 16.6797 31.3464 16.6797 31.75C16.6797 32.2708 16.582 32.7591 16.3867 33.2148C16.1914 33.6706 15.9245 34.0677 15.5859 34.4062C15.2474 34.7448 14.8503 35.0117 14.3945 35.207C13.9388 35.4023 13.4505 35.5 12.9297 35.5C12.4089 35.5 11.9206 35.4023 11.4648 35.207C11.0091 35.0117 10.612 34.7448 10.2734 34.4062C9.9349 34.0677 9.66797 33.6706 9.47266 33.2148C9.27734 32.7591 9.17969 32.2708 9.17969 31.75C9.17969 31.0339 9.36849 30.3698 9.74609 29.7578C10.1367 29.1458 10.6576 28.6836 11.3086 28.3711L4 6.5H0.308594V4H5.79688L7.14844 8H40L34.1797 25.5H12.9883L13.8281 28H30.4297ZM7.98828 10.5L12.1484 23H32.3633L36.5234 10.5H7.98828Z" fill="white" />
                                                                </svg>
                                                            </div>
                                                            <div class="cart-info">
                                                                <span class="cart-count"><?php echo sprintf(_n(' %d item', ' %d items', WC()->cart->cart_contents_count, 'cepatlakoo'), WC()->cart->cart_contents_count); ?></span>
                                                                <?php esc_html_e('in your shopping cart', 'cepatlakoo'); ?>
                                                            </div>
                                                            <div class="close-cart">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="cart-content">
                                                        <?php //($style == 'popup') ? cepatlakoo_cart_popup() : woocommerce_mini_cart();?>
                                                        <?php if ($wc_ajax_enable == 'no') 
                                                            woocommerce_mini_cart(); ?>
                                                        <div class="cart-footer primary-bg">
                                                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-btn btn wc-forward"><?php esc_html_e('View Cart', 'cepatlakoo'); ?></a>

                                                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn btn checkout">
                                                                <?php
                                                                $proceed_to_checkout_text = $cl_options['cepatlakoo_proceed_to_checkout_text'];
                                                                echo $proceed_to_checkout_text;
                                                                ?>
                                                                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="checkout-icon">
                                                                    <path d="M17.6865 5.42385L12.5012 0.238643C12.1831 -0.0795476 11.6672 -0.0795476 11.349 0.238643C11.0308 0.556833 11.0308 1.07272 11.349 1.39091L15.1433 5.18521L2.44778 5.18521C2.44663 5.18521 2.44548 5.1852 2.44433 5.1852H0.814777C0.364788 5.1852 0 5.54999 0 5.99998C0 6.44997 0.364788 6.81476 0.814777 6.81476L15.1433 6.81477L11.349 10.6091C11.0308 10.9273 11.0308 11.4431 11.349 11.7613C11.6672 12.0795 12.1831 12.0795 12.5012 11.7613L17.6865 6.57612C18.0046 6.25793 18.0046 5.74204 17.6865 5.42385Z" fill="white" />
                                                                </svg>

                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php
                            }
                        }

                        /**
                         * Function to include cart-popup
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.4.1
                         */
                        if (!function_exists('cepatlakoo_cart_popup')) {
                            function cepatlakoo_cart_popup($args = array())
                            {

                                $defaults = array(
                                    'list_class' => '',
                                );

                                $args = wp_parse_args($args, $defaults);

                                wc_get_template('cart/cart-popup.php', $args);
                            }
                        }

                        /**
                         * Function to add session for cart-popup
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.4.1
                         */
                        if (!function_exists('cepatlakoo_wc_add_to_cart')) {
                            function cepatlakoo_wc_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
                            {
                                global $cl_options;

                                // $style = ( isset($cl_options['cepatlakoo_cart_type']) && !empty($cl_options['cepatlakoo_cart_type']) ) ? $cl_options['cepatlakoo_cart_type'] : 'sidebar';
                                // if( $style == 'popup' ){
                                if (!empty($variation_id))
                                    WC()->session->set('cl_cart_last_product', ['product_id' => 0, 'variation_id' => (int)$variation_id]);
                                else
                                    WC()->session->set('cl_cart_last_product', ['product_id' => $product_id, 'variation_id' => 0]);
                                // }

                                // var_dump( WC()->session->get( 'cl_cart_last_product' ) );
                                // var_dump( ['product_id' => $product_id, 'variation_id' => (int)$variation_id] );
                                // exit();
                            };
                        }
                        add_action('woocommerce_add_to_cart', 'cepatlakoo_wc_add_to_cart', 10, 6);

                        if (get_option('elementor_use_mini_cart_template', 'no') == 'no') {
                            if (class_exists('WooCommerce')) {
                                add_action('elementor/theme/before_do_header', 'cepatlakoo_sidebar_cart_content');
                                add_action('wp_footer', 'cepatlakoo_popup_cart_content');
                            }
                        }

                        /**
                         * Function to change default WooCommerce sale text badge
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.5.0
                         */
                        function cepatlakoo_wc_sale_text($text, $post, $_product)
                        {
                            global $cl_options;

                            $sale_text = isset($cl_options['cepatlakoo_sale_badge_text']) ? esc_html($cl_options['cepatlakoo_sale_badge_text']) : 'Diskon';
                            return '<span class="onsale">' . $sale_text . '</span>';
                        }
                        add_filter('woocommerce_sale_flash', 'cepatlakoo_wc_sale_text', 10, 3);

                        /**
                         * Function to increase meta view
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.5.2
                         */
                        if (!function_exists('cepatlakoo_increase_meta_view')) {
                            function cepatlakoo_increase_meta_view()
                            {
                                $nonce = $_POST['nonce'];

                                // var_dump($_POST);
                                if (!wp_verify_nonce($nonce, 'cl-ajax-nonce')) {
                                    die('Nonce value cannot be verified.');
                                }

                                // The $_REQUEST contains all the data sent via ajax
                                if (isset($_POST)) {
                                    $postid = $_POST['postid'];

                                    $count_key = 'cepatlakoo_views_count';
                                    $count = get_post_meta($postid, $count_key, true);
                                    if ($count == '') {
                                        $count = 0;
                                        delete_post_meta($postid, $count_key);
                                        add_post_meta($postid, $count_key, 1);
                                    } else {
                                        $count++;
                                        update_post_meta($postid, $count_key, $count);
                                    }

                                    $sold = (int)get_post_meta($postid, 'total_sales', true);
                                    wp_send_json([
                                        'status' => true,
                                        'message' => 'Data found',
                                        'data' => ['view' => number_format($count, 0), 'sold' => number_format($sold, 0)]
                                    ]);
                                } else {
                                    wp_send_json([
                                        'status' => false,
                                        'message' => 'Parameter not found'
                                    ]);
                                }

                                die();
                            }
                        }
                        add_action('wp_ajax_cepatlakoo_increase_view', 'cepatlakoo_increase_meta_view');
                        add_action('wp_ajax_nopriv_cepatlakoo_increase_view', 'cepatlakoo_increase_meta_view');

                        /**
                         * Function to show meta counter
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.5.2
                         */
                        if (!function_exists('cepatlakoo_render_meta_counter')) {
                            function cepatlakoo_render_meta_counter()
                            {
                                global $product;
                                global $cl_options;

                                $view_count = number_format((int)get_post_meta($product->get_id(), 'cepatlakoo_views_count', true), 0);
                                $sales_count = number_format((int)get_post_meta($product->get_id(), 'total_sales', true), 0);
                                $view_label = $cl_options['cepatlakoo_wc_product_view_counter_text'] ? $cl_options['cepatlakoo_wc_product_view_counter_text'] : 'Dilihat';
                                $sold_label = $cl_options['cepatlakoo_wc_product_sold_counter_text'] ? $cl_options['cepatlakoo_wc_product_sold_counter_text'] : 'Terjual';
                            ?>

                                <?php if ($cl_options['cepatlakoo_wc_product_meta_counter'] != 'hide') : ?>
                                    <div class="cl-view-sold">
                                        <?php if (isset($cl_options['cepatlakoo_wc_product_meta_counter']) && ($cl_options['cepatlakoo_wc_product_meta_counter'] == 'all' || $cl_options['cepatlakoo_wc_product_meta_counter'] == 'view')) : ?>
                                            <span><?php echo $view_label; ?> <span class="cl-view-value"><?php echo $view_count; ?>x</span></span>
                                        <?php endif; ?>
                                        &bull;
                                        <?php if (isset($cl_options['cepatlakoo_wc_product_meta_counter']) && ($cl_options['cepatlakoo_wc_product_meta_counter'] == 'all' || $cl_options['cepatlakoo_wc_product_meta_counter'] == 'sold')) : ?>
                                            <span><?php echo $sold_label; ?> <span class="cl-sold-value"><?php echo $sales_count; ?></span></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                        <?php
                            }
                        }


                        /**
                         * Function to show/hide related products
                         *
                         * @package WordPress
                         * @subpackage CepatLakoo
                         * @since CepatLakoo 2.5.2
                         */
                        if (!function_exists('cepatlakoo_related_products')) {
                            function cepatlakoo_related_products()
                            {
                                global $cl_options;
                            }
                        }
