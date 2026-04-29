<?php
/**
 * Titan Framework Class
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
class CepatLakoo_Titan_Options {
    // Constructor
    function __construct() {
        add_action( 'tf_create_options', array( $this, 'cepatlakoo_create_options' ) );
    }

    // Create options function
    function cepatlakoo_create_options() {
        if ( class_exists('TitanFramework') ) {
            $titan = TitanFramework::getInstance( 'cepatlakoo' );

            $fb_pixel_events = array(
                'PageView' => esc_html( 'PageView' ),
                'ViewContent' => esc_html( 'ViewContent' ),
                'AddToWishlist' => esc_html( 'AddToWishlist' ),
                'AddToCart' => esc_html( 'AddToCart' ),
                'InitiateCheckout' => esc_html( 'InitiateCheckout' ),
                'AddCustomerInfo' => esc_html( 'AddCustomerInfo' ),
                'Purchase' => esc_html( 'Purchase' ),
                'AddPaymentInfo' => esc_html( 'AddPaymentInfo' ),
                'Lead' => esc_html( 'Lead' ),
                'CompleteRegistration' => esc_html( 'CompleteRegistration' ),
            );

            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array(),
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
                'b' => array(),
                'i' => array(),
            );

            // Create theme options panel
            $optionPanel = $titan->createAdminPanel( array(
                'name' => esc_html__( 'CepatLakoo Options', 'cepatlakoo' ),
                'title' => esc_html__( 'Theme Options', 'cepatlakoo' ),
            ));

            // Create Sub menu panel
            $customizePanel = $optionPanel->createAdminPanel( array(
                'name' => esc_html__( 'Customize', 'cepatlakoo' ),
            ) );

            // Create options for general options
            $generalTab = $optionPanel->createTab( array(
                'name' => esc_html__( 'General Settings', 'cepatlakoo' ),
            ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Post Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Post Excerpt Length', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_post_exceprt_length',
                    'type' => 'number',
                    'default' => '65',
                    'max' => '100',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Share Button', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_share_button',
                    'type' => 'enable',
                    'default' => true,
                    'desc' => esc_html__( 'Enable or disable share buttons on detail page.', 'cepatlakoo' ),
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Post Navigation', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_post_nav',
                    'type' => 'enable',
                    'default' => true,
                    'desc' => esc_html__( 'Enable or disable post navigation on detail page.', 'cepatlakoo' )
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Quick View Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_quickview_product_description',
                    'name' => esc_html__( 'Quick View Product Description', 'cepatlakoo' ),
                    'type' => 'enable',
                    'default' => true,
                    'desc' => esc_html__( 'Enable or disable product description in quick view.', 'cepatlakoo' )
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_quickview_product_catergoryandtag',
                    'name' => esc_html__( 'Quick View Product Category & Tag', 'cepatlakoo' ),
                    'type' => 'enable',
                    'default' => false,
                    'desc' => esc_html__( 'Enable or disable product category and tag in quick view.', 'cepatlakoo' )
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Top Bar Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Top Bar', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_top_bar',
                    'type' => 'enable',
                    'default' => true,
                    'desc' => esc_html__( 'Enable or disable top bar.', 'cepatlakoo' )
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Facebook Profile Url', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_fb_profile_url',
                    'type' => 'text',
                    'placeholder' => __( 'https://www.facebook.com/cepatlakoo'),
                    'default' => 'https://www.facebook.com/cepatlakoo',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Twitter Profile Url', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_tw_profile_url',
                    'type' => 'text',
                    'placeholder' => __( 'https://twitter.com/cepatlakoo'),
                    'default' => 'https://twitter.com/cepatlakoo',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Instagram Profile Url', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_itg_profile_url',
                    'type' => 'text',
                    'placeholder' => __( 'https://instagram.com/cepatlakoo'),
                    'default' => 'https://instagram.com/cepatlakoo',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Phone Label', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_customer_phone_label',
                    'type' => 'text',
                    'default' => 'CS:',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Phone Number', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_customer_care_phone',
                    'type' => 'text',
                    'default' => '021-67219821',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Top Bar Message', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_top_bar_msg',
                    'type' => 'textarea',
                    'desc' => esc_html__( 'Message to be displayed on the top bar. Allowed HTML tags: <a>, <br />, <p>, <b>, <i>, <em>,<strong>', 'cepatlakoo' ),
                    'default' => 'Diskon besar di banyak kategori produk.',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Page Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_single_sidebar_opt',
                    'type' => 'select',
                    'name' => esc_html__( 'Single Product Sidebar', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Override Product Detail Sidebar for all Products.', 'cepatlakoo' ),
                    'options' => array(
                        '0' => esc_html__( 'Default', 'cepatlakoo' ),
                        '1' => esc_html__( 'With Sidebar', 'cepatlakoo' ),
                        '2' => esc_html__( 'Without Sidebar', 'cepatlakoo' ),
                    ),
                    'default' => '0',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_header_style_opt',
                    'type' => 'select',
                    'name' => esc_html__( 'Header Layout Style', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Override Header Layout Style for all Pages.', 'cepatlakoo' ),
                    'options' => array(
                        '1' => esc_html__( 'Logo Left', 'cepatlakoo' ),
                        '2' => esc_html__( 'Logo Middle', 'cepatlakoo' ),
                    ),
                    'default' => '1',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_open_graph_trigger',
                    'type' => 'checkbox',
                    'name' => esc_html__( 'Open Graph', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Enable Open Graph.', 'cepatlakoo' ),
                    'default' => true,
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_google_analytics_tracking',
                    'type' => 'text',
                    'name' => esc_html__( 'Google Analytics Tracking ID', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Set Google Analytics Tracking ID.', 'cepatlakoo' ),
                    'default' => '',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_seo_trigger',
                    'type' => 'checkbox',
                    'name' => esc_html__( 'Search Engine Optimize', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Enable SEO Optimization.', 'cepatlakoo' ),
                    'default' => true,
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Countdown Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_countdown_heading_cart',
                    'name' => esc_html__( 'Countdown Timer Heading', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Countdown heading in product detail page.', 'cepatlakoo' ),
                    'type' => 'text',
                    'default' => 'Kesempatan Terbatas',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_countdown_subheading_cart',
                    'name' => esc_html__( 'Countdown Timer Sub Heading', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Countdown sub heading in product detail page.', 'cepatlakoo' ),
                    'type' => 'text',
                    'default' => 'Hanya dijual terbatas, cepat amankan produk ini agar kamu tidak kehabisan.',
                ));
                $generalTab->createOption( array(
                    'name' => esc_html__( 'Footer Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $generalTab->createOption( array(
                    'id' => 'cepatlakoo_copyright_text',
                    'name' => esc_html__( 'Copyright Text', 'cepatlakoo' ),
                    'desc' => esc_html__( 'Copyright text in footer. Allowed HTML tags: <a>, <br />, <p>, <strong>', 'cepatlakoo' ),
                    'type' => 'textarea',
                    'default' => 'Copyright 2017. All Rights Reserved <br /> Designed by <a href="http://cepatlakoo.com" target="_blank">Cepatlakoo</a>',
                ));
                $generalTab->createOption( array(
                    'type' => 'save'
                ));

            $ShoppingCartTab = $optionPanel->createTab( array(
                'name' => esc_html__( 'Shopping Cart', 'cepatlakoo' ),
            ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_shoping_cart',
                    'type'      => 'checkbox',
                    'name'      => esc_html__( 'Shoping Cart', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'Deactivate Shopping Cart', 'cepatlakoo' ),
                    'default'   => false,
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_sticky_button_trigger',
                    'type'      => 'checkbox',
                    'name'      => esc_html__( 'Sticky Whatsapp Button', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'Enable Sticky Whatsapp Button', 'cepatlakoo' ),
                    'default'   => true,
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_contact_button_trigger',
                    'type'      => 'checkbox',
                    'name'      => esc_html__( 'Contact Buttons', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'Enable Contact Buttons', 'cepatlakoo' ),
                    'default'   => true,
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_message_above_contact',
                    'type'      => 'textarea',
                    'name'      => esc_html__( 'Message Above Contact Buttons', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'This message will be displayed on top of the contact buttons', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Untuk pemesanan, silahkan klik tombol di bawah ini:' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_message_popup_heading',
                    'type'      => 'text',
                    'name'      => esc_html__( 'Contact Button Desktop Heading', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'This message will be displayed on popup when clicking contact buttons on desktop', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Cara Membeli' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_message_popup',
                    'type'      => 'textarea',
                    'name'      => esc_html__( 'Contact Button Desktop Message', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'This message will be displayed on popup when clicking contact buttons on desktop', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Silahkan menghubungi kami via [contact_app] di [contact_id] pada perangkat handphone Anda.' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name' => esc_html__( 'Follow Up Buttons', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $ShoppingCartTab->createOption( array(
                        'name'      => esc_html__( 'WhatsApp Message', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_followup_wa_message',
                        'type'      => 'textarea',
                        'desc'      => esc_html__( 'Enter your WhatsApp message. You can use %lakoo_name%, %lakoo_order_id%, %lakoo_products%, %lakoo_shipping_cost%, %lakoo_order_total% to display order detail.', 'cepatlakoo' ),
                        'default'   => esc_html( 'Halo %lakoo_name%,
ID Pesanan Anda: #%lakoo_order_id%

Detail Pesanan:
%lakoo_products%

Ongkir: %lakoo_shipping_cost%
*Total:* %lakoo_order_total%

Pesanan Anda sudah kami terima, jika ada kendala dalam pemesanan bisa menghubungi kami via WA.' ),
                    ));
                    $ShoppingCartTab->createOption( array(
                        'name'      => esc_html__( 'SMS Message', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_followup_sms_message',
                        'type'      => 'textarea',
                        'desc'      => esc_html__( 'Enter your SMS message. You can use %lakoo_name%, %lakoo_order_id%, %lakoo_products%, %lakoo_shipping_cost%, %lakoo_order_total% to display order detail.', 'cepatlakoo' ),
                        'default'   => esc_html( 'Halo %lakoo_name%,
ID Pesanan Anda: #%lakoo_order_id%

Detail Pesanan:
%lakoo_products%

Ongkir: %lakoo_shipping_cost%
Total: %lakoo_order_total%

Pesanan Anda sudah kami terima, jika ada kendala dalam pemesanan bisa menghubungi kami via SMS.' ),
                    ));
                $ShoppingCartTab->createOption( array(
                    'name' => esc_html__( 'Messenger Buttons on Product Page', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'WhatsApp Number', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_wa',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Whatsapp Number. Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__( '6285966634332' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'WhatsApp Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_wa_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Whatsapp Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Beli via WhatsApp' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'WhatsApp Message', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_wa_message',
                    'type'      => 'textarea',
                    'desc'      => esc_html__( 'Enter your WhatsApp message. Use %lakoo_product_name% to display the product name.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Hai, saya berminat dengan %lakoo_product_name%' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_wa_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'WhatsApp FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'BBM', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_bbm',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your BBM Pin.  Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'E09K98' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'BBM Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_bbm_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your BBM Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Chat di BBM' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_bbm_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'BBM FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'SMS', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_sms',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Phone Number. Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__( '0812000912' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'SMS Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_sms_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your SMS Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Beli via SMS', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'SMS Message', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_sms_message',
                    'type'      => 'textarea',
                    'desc'      => esc_html__( 'Enter your SMS Message display. Use %lakoo_product_name% to display the product name.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Hai, saya berminat dengan %lakoo_product_name%', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_sms_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'SMS FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Line', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_line',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Line ID. Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'agnezmos', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Line Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_line_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Line Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Chat di LINE', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_line_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'Line FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Phone', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_phone',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Phone Number. Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__('+628127776622', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Phone Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_phone_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Phone Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__('Telepon Kami', 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_phone_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'Phone FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Telegram', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_telegram',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Telegram ID. Leave it blank to hide the button.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'telegram' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'name'      => esc_html__( 'Telegram Text', 'cepatlakoo' ),
                    'id'        => 'cepatlakoo_cart_telegram_text',
                    'type'      => 'text',
                    'desc'      => esc_html__( 'Enter your Telegram Text display.', 'cepatlakoo' ),
                    'default'   => esc_html__( 'Beli via Telegram' , 'cepatlakoo' ),
                ));
                $ShoppingCartTab->createOption( array(
                    'id'        => 'cepatlakoo_cart_telegram_event',
                    'type'      => 'select',
                    'name'      => esc_html__( 'Telegram FB Ads Event', 'cepatlakoo' ),
                    'options'   => $fb_pixel_events,
                    'default'   => 'AddToWishlist',
                ));
                $ShoppingCartTab->createOption( array(
                    'type' => 'save'
                ));

            // Create options for Facebook options
            $OptimizeTab = $optionPanel->createTab( array(
                'name' => esc_html__( 'Optimize', 'cepatlakoo' ),
            ));
                $OptimizeTab->createOption( array(
                    'id'        => 'cepatlakoo_minify_html',
                    'type'      => 'checkbox',
                    'name'      => esc_html__( 'Minify HTML & JS', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'Minify HTML to help speed up your website.', 'cepatlakoo' ),
                    'default'   => false,
                ));
                $OptimizeTab->createOption( array(
                    'id'        => 'cepatlakoo_remove_querystring',
                    'type'      => 'checkbox',
                    'name'      => esc_html__( 'Remove JS/CSS Version', 'cepatlakoo' ),
                    'desc'      => esc_html__( 'Remove JS & CSS version.', 'cepatlakoo' ),
                    'default'   => false,
                ));
                $OptimizeTab->createOption( array(
                    'type' => 'save'
                ));

            // Create options for Facebook options
            $FBPixelTab = $optionPanel->createTab( array(
                'name' => esc_html__( 'Facebook Pixel', 'cepatlakoo' ),
            ));
                $FBPixelTab->createOption( array(
                    'name'  => esc_html__( 'Facebook Pixel ID', 'cepatlakoo' ),
                    'id'    => 'cepatlakoo_facebook_pixel_id',
                    'type'  => 'text',
                    'desc'  =>  sprintf( wp_kses( __('Enter your Facebook Pixel ID. you can create your Pixel ID by following this <a href="%s" target="_blank"> article</a>.', 'cepatlakoo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://www.facebook.com/business/help/952192354843755?helpref=faq_content#createpixel' ) )
                ));
                $FBPixelTab->createOption( array(
                    'name' => 'Select Purchase Confirmation Page',
                    'id' => 'cepatlakoo_select_confirmation',
                    'type' => 'select-pages',
                    'desc' => esc_html__( 'Select payment confirmation page.', 'cepatlakoo' ),
                ) );
                $FBPixelTab->createOption( array(
                    'id'    => 'cepatlakoo_purchase_confirmation',
                    'type'  => 'select',
                    'name'  => esc_html__( 'Woocommerce Purchase Confirmation', 'cepatlakoo' ),
                    'desc'  => esc_html__( 'Select which Facebook Pixel event should be triggered on WooCommerce Thank You page.', 'cepatlakoo' ),
                    'options' => $fb_pixel_events,
                    'default' => 'Purchase',
                ));
                $FBPixelTab->createOption( array(
                    'type' => 'save'
                ));

            // Create options for Facebook options
            $CepatlakooColorTab = $customizePanel->createTab( array(
                'name' => esc_html__( 'Color', 'cepatlakoo' ),
            ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'General Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'General Theme Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_general_theme_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Border color, link color', 'cepatlakoo' ),
                        'css'       => '.widget li.recentcomments span, .wp-pagenavi span.current, .post-navigation ul li .detail > a,.products-pagination span.current, .woocommerce .products li .custom-shop-buttons a:nth-child(2), #contentarea .woocommerce-Price-amount.amount, #contentarea span.amount, .widget ul.product_list_widget li .quantity span, .woocommerce-MyAccount-navigation ul li.is-active a, .product_meta span.posted_in:before, .woocommerce .product_meta span.tagged_as:before, #contentarea span.woocommerce-Price-currencySymbol, .woocommerce div.product .out-of-stock, #sidebar .entry-meta span, fieldset legend, #backtotop, ul.product-categories li:hover a:before, .price_slider_amount button, .ui.steps .step.active .title
                                    { color: value !important; }',
                        'default'   => '#372248',
                    ));

                    $titan->createCSS( ' .custom-shop-buttons i { color: $cepatlakoo_general_theme_color } ' );
                    $titan->createCSS( ' .custom-shop-buttons { border-bottom: solid 2px $cepatlakoo_general_theme_color !important; } ' );
                    $titan->createCSS( ' .woocommerce div.product .woocommerce-tabs ul.tabs li.active { border-bottom: solid 2px $cepatlakoo_general_theme_color !important; } ' );
                    $titan->createCSS( ' .custom-shop-buttons > a:hover i { color: #fff !important } ' );
                    $titan->createCSS( ' .site-navigation ul.user-menu-menu li { color: #fff !important } ' );



                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'General Background Theme Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_general_bg_theme_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Header, buttons, discount badge, ', 'cepatlakoo' ),
                        'css'       => '.custom-shop-buttons #contentarea a.btn, li.product .custom-shop-buttons .btn.cepatlakoo-ajax-quick-view:hover,
                                        .woocommerce .products li .custom-shop-buttons a:nth-child(2):hover, .woocommerce a.button, .woocommerce button.button,
                                        .woocommerce input.button, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt, .woocommerce a.btn:hover,
                                        .woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range, a.add_to_wishlist:hover,
                                        .woocommerce ul.products li.product .ribbons, .woocommerce #main .onsale, .woocommerce .product-list .ribbons, .woocommerce .product .ribbons,
                                        .woocommerce .summary .add_to_cart_button, .owl-dots .owl-dot.active, .woocommerce #respond input#submit, .size-select-widget ul li.selected,
                                        .primary-bg, a.cart-btn.btn.wc-forward, #dialog:before, #main-header, .page-template-template-blog #contentarea a.btn
                                        { background: value !important; }',
                        'default'   => '#372248',
                    ));

                    $titan->createCSS( '.page-template-template-blog #contentarea a.btn { border: $cepatlakoo_general_bg_theme_color !important; } ' );
                    $titan->createCSS( ' @media screen and (max-width: 1024px) { .user-account-menu, .mobile-menu-menu-expander { background: $cepatlakoo_general_bg_theme_color } } ' );
                    $titan->createCSS( ' .search-widget-header .search-widget { border-top: solid 2px $cepatlakoo_general_bg_theme_color !important; } ' );
                    $titan->createCSS( ' #top-bar { background: $cepatlakoo_general_bg_theme_color } ' );

                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Link Theme Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_link_theme_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'General link color', 'cepatlakoo' ),
                        'css'       => 'a, a:link, .entry-content a, .entry-summary a { color: value; }',
                        'default'   => '#555555',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Link Hover Theme Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_linkhover_theme_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'General link hover color', 'cepatlakoo' ),
                        'css'       => 'a:hover, .entry-content a:hover, .entry-summary a:hover { color: value; }',
                        'default'   => '#d64933',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Back to top background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_backtotop_bg_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Background color for back to top button', 'cepatlakoo' ),
                        'css'       => '#backtotop{ background: value; }',
                        'default'   => '#ffffff',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Back to top background hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_backtotop_bg_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Background hover color for back to top button', 'cepatlakoo' ),
                        'css'       => '#backtotop:hover{ background: value; }',
                        'default'   => '#d64933',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Form Field Background Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_form_field_bg_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => 'input:not([type]), input[type=date], input[type=datetime-local], input[type=email], input[type=file], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], textarea, .select2-container .select2-selection--single, select, { background-color: value !important; }',
                        'default'   => '#fafafa',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Woocommerce Button Background Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_woocommerce_button_bg_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Background color for add to cart, checkout buttons', 'cepatlakoo' ),
                        'css'       => '.wc-proceed-to-checkout a.checkout-button, #dialog:before, .form-row.place-order input#place_order, .woocommerce div.product form.cart .button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce-cart .wc-proceed-to-checkout a.button, .cart-content a.checkout-btn, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button, ul.products li.product a.button.product_type_variable, ul.products li.product a.button.product_type_grouped, .woocommerce ul.products li.product a.button.product_type_external, .woocommerce a.single_add_to_cart_button { background: value !important; }',
                        'default'   => '#399E5A',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Woocommerce Price Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_woocommerce_price_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Price color', 'cepatlakoo' ),
                        'css'       => '#contentarea ins .woocommerce-Price-amount.amount, #contentarea ins span.woocommerce-Price-currencySymbol, .woocommerce .summary p.price ins .woocommerce-Price-amount { color: value !important; }',
                        'default'   => '#27ae60',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Woocommerce Striketrough Price Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_woocommerce_striketrough_price_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Color for striketrough price (usually when you applied sale price)', 'cepatlakoo' ),
                        'css'       => '#contentarea del .woocommerce-Price-amount.amount, #contentarea del span.woocommerce-Price-currencySymbol, .woocommerce .summary p.price del .woocommerce-Price-amount { color: value !important; }',
                        'default'   => '#7e7e7e',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Shopping Cart Icon not Empty Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_cart_not_empty_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Color for shopping cart icon not empty', 'cepatlakoo' ),
                        'css'       => '.user-carts .cart-counter .icon.not-empty { color: value !important; }',
                        'default'   => '#c7ac7f',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Top Bar Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Top bar menu color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_topbar_menu_bg_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#top-bar a, #top-bar ul.user-menu-menu li, #top-bar .socials li a, .customer-care a, .flash-info, .flash-info a, #top-bar .cart-counter, #top-bar .avatar:after, .customer-care b, .customer-care i, .user-account-menu, .user-options i { color: value; }',
                        'default'   => '#ffffff',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Top bar link hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_topbar_link_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#top-bar a:hover{ color: value !important; }',
                        'default'   => '#928da8',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Social media hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_social_media_hover',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#top-bar .socials li a:hover { color: value !important; }',
                        'default'   => '#f2f2f2',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Header Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Main menu link hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_main_menu_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#main-header #main-menu li a:hover { color: value; }',
                        'default'   => '#F0CF65',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Sub menu link hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_sub_menu_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#main-header #main-menu li ul.sub-menu li a:hover { color: value; }',
                        'default'   => '#ffffff',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Sub menu background hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_sub_menu_bg_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#main-header #main-menu li ul.sub-menu li a:hover { background: value !important; }',
                        'default'   => '#d64933',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Footer Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Footer Widget Background Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_footer_widget_background_color',
                        'type'      => 'color',
                        'css'       => '#footer-widgets-area { background-color: value !important; }',
                        'default'   => '#372248',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Footer Background Color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_footer_background_color',
                        'type'      => 'color',
                        'css'       => '#footer-info { background-color: value !important; }',
                        'default'   => '#2e1c3b',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Footer hover link color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_footer_hover_link_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#colofon a:hover, .widget_categories ul li:hover a, .widget_categories ul li:hover a:before { color: value; }',
                        'default'   => '#c1c1c1',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Widget Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Widget footer link color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_widget_footer_link_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => 'div#footer-widgets-area a, .site-infos a { color: value; }',
                        'default'   => '#ffffff',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Sidebar Widget link color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_widget_link_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#sidebar a{ color: value; }',
                        'default'   => '#333333',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Widget link hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_widget_link_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#sidebar a:hover{ color: value; }',
                        'default'   => '#F08700',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Blog Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Page title background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_page_title_bg_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '#page-title{ background: value; }',
                        'default'   => '#faf9fc',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Post meta text color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_post_meta_text_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.page-template .entry-meta span, .single .entry-meta span{ color: value !important; }',
                        'default'   => '#aaaaaa',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Post meta link color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_postmeta_link_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.page-template .entry-meta a{ color: value; }',
                        'default'   => '#aaaaaa',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Read More button color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_post_btn_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.page-template-template-blog #contentarea a.btn{ color: value !important; }',
                        'default'   => '#ffffff',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Sharing button color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_sharing_btn_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.share-article-widget a i { color: value; }',
                        'default'   => '#b4b4b4',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Sharing button hover color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_sharing_btn_hover_color',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.share-article-widget a i:hover { color: value; }',
                        'default'   => '#f66767',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Contact Button Settings (Buttons on Product Detail Page)', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'BBM background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_bbm',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.blackberry { background: value !important; }',
                        'default'   => '#019cde',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Whatsapp background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_wa',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.whatsapp { background: value !important; }',
                        'default'   => '#26d367',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'SMS background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_sms',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.sms { background: value !important; }',
                        'default'   => '#efc33c',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'LINE background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_line',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.line { background: value !important; }',
                        'default'   => '#44b654',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Phone background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_phone',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.phone { background: value !important; }',
                        'default'   => '#1ad0dd',
                    ));
                    $CepatlakooColorTab->createOption( array(
                        'name'      => esc_html__( 'Telegram background color', 'cepatlakoo' ),
                        'id'        => 'cepatlakoo_custom_bg_telegram',
                        'type'      => 'color',
                        'desc'      => esc_html__( 'Pick a color', 'cepatlakoo' ),
                        'css'       => '.quick-contact-info a.telegram {  background: value !important; }',
                        'default'   => '#38afe2',
                    ));
                $CepatlakooColorTab->createOption( array(
                    'name' => esc_html__( 'Login Page', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooColorTab->createOption( array(
                        'name' => esc_html__( 'Logo Login Dashboard', 'cepatlakoo' ),
                        'id' => 'cepatlakoo_logo_login_dashboard',
                        'type' => 'upload',
                        'desc' => esc_html__( 'Upload your image', 'cepatlakoo' ),
                    ) );
                    $CepatlakooColorTab->createOption( array(
                        'name' => esc_html__( 'Icon Admin Dashboard', 'cepatlakoo' ),
                        'id' => 'cepatlakoo_icon_admin_dashboard',
                        'type' => 'upload',
                        'desc' => esc_html__( 'Upload your image', 'cepatlakoo' ),
                    ) );
                $CepatlakooColorTab->createOption( array(
                    'type' => 'save'
                ));
             // Create options for Facebook options
            $CepatlakooTypographyTab = $customizePanel->createTab( array(
                'name' => esc_html__( 'Typography', 'cepatlakoo' ),
            ));
                $CepatlakooTypographyTab->createOption( array(
                    'name' => esc_html__( 'General Typography Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H1', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h1_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.2em',
                            'font-weight'           => '700',
                            'font-size'             => '30px',
                        ),
                        'css'                 => 'h1 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H2', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h2_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.4em',
                            'font-weight'           => '700',
                            'font-size'             => '25px',
                        ),
                        'css'                 => 'h2 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H3', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h3_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.3em',
                            'font-weight'           => '700',
                            'font-size'             => '20px',
                        ),
                        'css'                 => 'h3 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H4', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h4_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.3em',
                            'font-weight'           => '400',
                            'font-size'             => '16px',
                        ),
                        'css'                 => 'h4 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H5', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h5_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.3em',
                            'font-weight'           => '400',
                            'font-size'             => '12px',
                        ),
                        'css'                 => 'h5 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'H6', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_heading_h6_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#333333',
                            'line-height'           => '1.3em',
                            'font-weight'           => '400',
                            'font-size'             => '10px',
                        ),
                        'css'                 => 'h6 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Main Typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_main_font_typo',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#555555',
                            'line-height'           => '1.3em',
                            'font-weight'           => '400',
                            'font-size'             => '14px',
                        ),
                        'css'                 => 'body:not(.elementor-page), #top-bar { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__(  'Site title typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_site_title_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#ffffff',
                            'line-height'           => '1.2em',
                            'font-weight'           => '400',
                            'font-size'             => '30px',
                        ),
                        'css'                 => '#main-header h2 a { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Menu text typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_menu_text_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#ffffff',
                            'line-height'           => '1.8em',
                            'font-weight'           => '700',
                            'font-size'             => '12px',
                        ),
                        'css'                 => '.site-navigation ul li > a, .site-navigation ul li > b b a { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Sub menu text typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_submenu_text_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => true,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#000000',
                            'line-height'           => '1.8em',
                            'font-weight'           => '400',
                            'font-size'             => '12px',
                            'text-transform'        => 'none',
                        ),
                        'css'                 => '.site-navigation ul li > ul.sub-menu li a { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Sub menu 2nd text typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_submenu_2nd_text_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => true,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#000000',
                            'line-height'           => '1.8em',
                            'font-weight'           => '400',
                            'font-size'             => '12px',
                            'text-transform'        => 'none',
                        ),
                        'css'                 => '.site-navigation ul li > ul.sub-menu li ul.sub-menu li a { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Footer typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_footer_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => true,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#bababa',
                            'line-height'           => '1.8em',
                            'font-weight'           => '400',
                            'font-size'             => '14px',
                            'text-transform'        => 'none',
                        ),
                        'css'                 => 'footer#colofon { value }'
                    ));
                $CepatlakooTypographyTab->createOption( array(
                    'name' => esc_html__( 'Blog Typography Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Page title typography', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_page_title_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#333333',
                            'line-height'           => '1.2em',
                            'font-weight'           => '400',
                            'font-size'             => '24px',
                        ),
                        'css'                 => '#page-title h2 { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Post title color', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_post_title_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#000000',
                            'line-height'           => '1.2em',
                            'font-weight'           => '600',
                            'font-size'             => '32px',
                        ),
                        'css'                 => 'article h1.post-title, .postlist article.hentry h3.post-title { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'Paragraf Text', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_paragraf_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#555555',
                            'line-height'           => '1.8em',
                            'font-weight'           => '400',
                            'font-size'             => '16px',
                        ),
                        'css'                 => 'article.hentry p { value }'
                    ));
                $CepatlakooTypographyTab->createOption( array(
                    'name' => esc_html__( 'WooCommerce Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'WooCommerce Product Title', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_woo_product_title_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Montserrat',
                            'color'                 => '#000000',
                            'line-height'           => '1.2em',
                            'font-weight'           => '400',
                            'font-size'             => '35px',
                        ),
                        'css'                 => '.woocommerce .summary h1.product_title { value }'
                    ));
                    $CepatlakooTypographyTab->createOption( array(
                        'name'                  => esc_html__( 'WooCommerce Paragraf Text', 'cepatlakoo' ),
                        'id'                    => 'cepatlakoo_woo_paragraf_typography',
                        'type'                  => 'font',
                        'desc'                  => esc_html__( 'Select a style', 'cepatlakoo' ) ,
                        'show_font_weight'      => true,
                        'show_font_style'       => false,
                        'show_line_height'      => true,
                        'show_letter_spacing'   => false,
                        'show_text_transform'   => false,
                        'show_font_variant'     => false,
                        'show_text_shadow'      => false,
                        'default'               => array(
                            'font-family'           => 'Lato',
                            'color'                 => '#555555',
                            'line-height'           => '1.5em',
                            'font-weight'           => '400',
                            'font-size'             => '16px',
                        ),
                        'css'                 => '.woocommerce .summary p:not(a), .woocommerce .woocommerce-tabs p { value }'
                    ));
                $CepatlakooTypographyTab->createOption( array(
                    'type' => 'save'
                ));
            // Create options for general options
            $smsNotificationTab = $optionPanel->createTab( array(
                'name' => esc_html__( 'SMS Notification', 'cepatlakoo' ),
            ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Gateway Settings', 'cepatlakoo' ),
                    'type' => 'heading',
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Gateway E-mail', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_gateway_email',
                    'type' => 'text',
                    'placeholder' => esc_attr__( 'email@smsgateway.me', 'cepatLakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Gateway Password', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_gateway_password',
                    'type' => 'text',
                    'default' => '',
                    'placeholder' => esc_attr__( 'yourpassword', 'cepatLakoo' ),
                    'is_password' => true,
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Gateway DeviceID', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_gateway_deviceID',
                    'type' => 'text',
                    'placeholder' => esc_attr__( '526345', 'cepatLakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Gateway Phone Number', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_gateway_phone',
                    'type' => 'text',
                    'placeholder' => esc_attr__( '085613242215', 'cepatLakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Notification for Admin', 'cepatlakoo' ),
                    'type' => 'heading',
                    'desc' => 'Anda dapat menggunakan kode berikut :: <strong>%lakoo_order_id%</strong> : Nomor Pemesanan | <strong>%lakoo_order_status%</strong> : Status Pemesanan | <strong>%lakoo_shop_name%</strong> : Nama Toko' ,
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'New Order Alert ', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_new_order_admin',
                    'type' => 'textarea',
                    'default' => esc_attr__( 'Ada orderan baru dengan nomor #%lakoo_order_id% di %lakoo_shop_name%. Segera proses.' , 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'SMS Notification for Customer', 'cepatlakoo' ),
                    'type' => 'heading',
                    'desc' => 'Anda dapat menggunakan kode berikut :: <strong>%lakoo_order_id%</strong> : Nomor Pemesanan | <strong>%lakoo_order_status%</strong> : Status Pemesanan | <strong>%lakoo_shop_name%</strong> : Nama Toko | <strong>%lakoo_fullname%</strong> : Nama Lengkap Buyer | </br> <strong>%lakoo_email%</strong> : Email Buyer | <strong>%lakoo_phone_number%</strong> : Nomor Telepon Buyer | <strong>%lakoo_shipping_price%</strong> : Biaya Ongkos Kirim | <strong>%lakoo_total_price%</strong> : Total Harga Pemesanan | <strong>%lakoo_tracking_code%</strong> : Kode Resi | </br> <strong>%lakoo_courier%</strong> : Nama Ekspedisi | <strong>%lakoo_payment_code%</strong> : Kode Pembayaran',
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'New Order Alert', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_new_order',
                    'type' => 'textarea',
                    'default' => esc_attr__( 'Order Anda #%lakoo_order_id% sudah kami terima dan akan segera kami proses. Terimakasih sudah berbelanja di %lakoo_shop_name%.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order processing', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_process',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Status order #%lakoo_order_id% diubah menjadi: Sedang Diproses.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order completed', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_complete',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Status order #%lakoo_order_id% diubah menjadi: Sudah Dikirim dengan %lakoo_courier%, nomor resi: %lakoo_tracking_code%', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order pending', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_pending',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Pending.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order failed', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_failed',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Gagal.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order refunded', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_refunded',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Refund Uang.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Order cancelled', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_cancel',
                    'type' => 'textarea',
                    'default' => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Dibatalkan.', 'cepatlakoo' ),
                ));
                $smsNotificationTab->createOption( array(
                    'name' => esc_html__( 'Notes custom message:', 'cepatlakoo' ),
                    'id' => 'cepatlakoo_sms_order_note',
                    'type' => 'textarea',
                    'default' =>  esc_attr__( '%lakoo_shop_name% - Catatan ditambahkan pada order #%lakoo_order_id%: %lakoo_note%', 'cepatlakoo' ),
                    'desc' => 'Anda dapat menggunakan tambahan kode untuk melampirkan catatan :: <strong>%lakoo_note%</strong> : Catatan untuk Buyer </br></br>',
                ));
            $smsNotificationTab->createOption( array(
                'type' => 'save'
            ));

        }
    }

}
if( ! get_option('cepatlakoo_migration_themeoption') ){
    new CepatLakoo_Titan_Options();
}

/**
 * Function for display notice for admin to migration
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
function cepatlakoo_migration_admin_notice(){
    $cl_theme = wp_get_theme();
    $cl_version = $cl_theme->get( 'Version' );
    $step = 1;
    if( file_exists( WP_PLUGIN_DIR . '/redux-framework/redux-framework.php') && !is_plugin_active( 'redux-framework/redux-framework.php' ) ){
        $step = 2;
    }
    else if( !get_option('cepatlakoo_migration_themeoption') ){
        $step = 3;
    }
    
    if (!file_exists( WP_PLUGIN_DIR . '/redux-framework/redux-framework.php') && strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']  , 'page=tgmpa-install-plugins') && !strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']  , 'tgmpa-install=install-plugin') && get_option('cepatlakoo_options')){
        echo '<div class="notice notice-info is-dismissible">
                    <p><strong>' . esc_html__('Cepatlakoo – Pasang Plugin Redux Framework', 'cepatlakoo') . '</strong></p>
                      <p class="cl-notif">' . esc_html__('Silahkan melakukan instalasi plugin Redux Framework agar proses migrasi dapat dilakukan.', 'cepatlakoo') . '</p>
                 </div>';
    }
    else if ( !strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']  , 'tgmpa-install=install-plugin') && 
        !strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']  , 'page=cepatlakoo_migration_menu') &&
        get_option('cepatlakoo_options') &&
        ( !file_exists( WP_PLUGIN_DIR . '/redux-framework/redux-framework.php') ||
        !is_plugin_active( 'redux-framework/redux-framework.php' ) ||
        !get_option('cepatlakoo_migration_themeoption') ) ) {
            echo '<div class="notice notice-info is-dismissible">
                    <p><strong>' . esc_html__('Cepatlakoo – Migrasi Theme Options', 'cepatlakoo') . '</strong></p>
                      <p class="cl-notif">' . esc_html__('Klik tombol Migrasi Sekarang untuk melakukan proses migrasi theme options.', 'cepatlakoo') . '</p>
                    <a href="'.admin_url('options-general.php?page=cepatlakoo_migration_menu&step='.$step ).'" class="button-primary">' . esc_html__('Migrasi Sekarang', 'cepatlakoo') . '</a> <p>&nbsp;</p>
                 </div>';
    }
}
add_action('admin_notices', 'cepatlakoo_migration_admin_notice', 99);

/**
 * Function for processing AJAX Request by Migration
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
add_action( 'wp_ajax_nopriv_cl_migration', 'cl_migration' );
add_action( 'wp_ajax_cl_migration', 'cl_migration' );
function cl_migration() {
    if ( is_plugin_active( 'redux-framework/redux-framework.php' ) ) {
        check_migration_theme_option();
        echo '1';
    }
    die();
}

/**
 * Function for processing AJAX Request by Activating Plugin
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
add_action( 'wp_ajax_nopriv_cl_active_redux', 'cl_active_redux' );
add_action( 'wp_ajax_cl_active_redux', 'cl_active_redux' );
function cl_active_redux() {
    if ( ! is_plugin_active( 'redux-framework/redux-framework.php' ) ) {
        activate_plugin( '/redux-framework/redux-framework.php' );
        echo '1';
    }
    die();
}

/**
 * Function for checking migration
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
function check_migration_theme_option(){
    if( ! get_option('cepatlakoo_migration_themeoption') ){
        $old_option = get_option( 'cepatlakoo_options' );
        
        if( $old_option ){
            if( is_serialized( $old_option ) ){
                $proc_data = unserialize( $old_option ); //make data to array, nested not yet unserialize
            }else{
                $proc_data = $old_option;
            }

            $new_data = array(
                'last_tab' => '0',
                'cepatlakoo_post_exceprt_length' => cL_exist( $proc_data, 'cepatlakoo_post_exceprt_length' ),
                'cepatlakoo_share_button' => cL_exist( $proc_data, 'cepatlakoo_share_button' ),
                'cepatlakoo_post_nav' => cL_exist( $proc_data, 'cepatlakoo_post_nav' ),
                'cepatlakoo_quickview_product_description' => cL_exist( $proc_data, 'cepatlakoo_quickview_product_description' ),
                'cepatlakoo_quickview_product_catergoryandtag' => cL_exist( $proc_data, 'cepatlakoo_quickview_product_catergoryandtag' ),
                'cepatlakoo_top_bar' => cL_exist( $proc_data, 'cepatlakoo_top_bar' ),
                'cepatlakoo_fb_profile_url' => cL_exist( $proc_data, 'cepatlakoo_fb_profile_url' ),
                'cepatlakoo_tw_profile_url' => cL_exist( $proc_data, 'cepatlakoo_tw_profile_url' ),
                'cepatlakoo_itg_profile_url' => cL_exist( $proc_data, 'cepatlakoo_itg_profile_url' ),
                'cepatlakoo_customer_phone_label' => cL_exist( $proc_data, 'cepatlakoo_customer_phone_label' ),
                'cepatlakoo_customer_care_phone' => cL_exist( $proc_data, 'cepatlakoo_customer_care_phone' ),
                'cepatlakoo_top_bar_msg' => cL_exist( $proc_data, 'cepatlakoo_top_bar_msg' ),
                'cepatlakoo_single_sidebar_opt' => cL_exist( $proc_data, 'cepatlakoo_single_sidebar_opt' ),
                'cepatlakoo_header_style_opt' => cL_exist( $proc_data, 'cepatlakoo_header_style_opt' ),
                'cepatlakoo_open_graph_trigger' => cL_exist( $proc_data, 'cepatlakoo_open_graph_trigger' ),

                'cepatlakoo_google_analytics_tracking' => cL_exist( $proc_data, 'cepatlakoo_google_analytics_tracking' ),
                'cepatlakoo_seo_trigger' => cL_exist( $proc_data, 'cepatlakoo_seo_trigger' ),
                'cepatlakoo_countdown_heading_cart' => cL_exist( $proc_data, 'cepatlakoo_countdown_heading_cart' ),
                'cepatlakoo_countdown_subheading_cart' => cL_exist( $proc_data, 'cepatlakoo_countdown_subheading_cart' ),
                'cepatlakoo_copyright_text' => cL_exist( $proc_data, 'cepatlakoo_copyright_text' ),
                'cepatlakoo_shoping_cart' => cL_exist( $proc_data, 'cepatlakoo_shoping_cart' ),
                'cepatlakoo_sticky_button_trigger' => cL_exist( $proc_data, 'cepatlakoo_sticky_button_trigger' ),
                'cepatlakoo_contact_button_trigger' => cL_exist( $proc_data, 'cepatlakoo_contact_button_trigger' ),
                'cepatlakoo_message_above_contact' => cL_exist( $proc_data, 'cepatlakoo_message_above_contact' ),

                'cepatlakoo_message_popup_heading' => cL_exist( $proc_data, 'cepatlakoo_message_popup_heading' ),
                'cepatlakoo_message_popup' => cL_exist( $proc_data, 'cepatlakoo_message_popup' ),
                'cepatlakoo_followup_wa_message' => cL_exist( $proc_data, 'cepatlakoo_followup_wa_message' ),
                'cepatlakoo_followup_sms_message' => cL_exist( $proc_data, 'cepatlakoo_followup_sms_message' ),

                'cepatlakoo_cart_wa' => cL_exist( $proc_data, 'cepatlakoo_cart_wa' ),
                'cepatlakoo_cart_wa_text' => cL_exist( $proc_data, 'cepatlakoo_cart_wa_text' ),
                'cepatlakoo_cart_wa_message' => cL_exist( $proc_data, 'cepatlakoo_cart_wa_message' ),
                'cepatlakoo_cart_wa_event' => cL_exist( $proc_data, 'cepatlakoo_cart_wa_event' ),

                'cepatlakoo_cart_bbm' => cL_exist( $proc_data, 'cepatlakoo_cart_bbm' ),
                'cepatlakoo_cart_bbm_text' => cL_exist( $proc_data, 'cepatlakoo_cart_bbm_text' ),
                'cepatlakoo_cart_bbm_event' => cL_exist( $proc_data, 'cepatlakoo_cart_bbm_event' ),

                'cepatlakoo_cart_sms' => cL_exist( $proc_data, 'cepatlakoo_cart_sms' ),
                'cepatlakoo_cart_sms_text' => cL_exist( $proc_data, 'cepatlakoo_cart_sms_text' ),
                'cepatlakoo_cart_sms_message' => cL_exist( $proc_data, 'cepatlakoo_cart_sms_message' ),
                'cepatlakoo_cart_sms_event' => cL_exist( $proc_data, 'cepatlakoo_cart_sms_event' ),

                'cepatlakoo_cart_line' => cL_exist( $proc_data, 'cepatlakoo_cart_line' ),
                'cepatlakoo_cart_line_text' => cL_exist( $proc_data, 'cepatlakoo_cart_line_text' ),
                'cepatlakoo_cart_line_event' => cL_exist( $proc_data, 'cepatlakoo_cart_line_event' ),

                'cepatlakoo_cart_phone' => cL_exist( $proc_data, 'cepatlakoo_cart_phone' ),
                'cepatlakoo_cart_phone_text' => cL_exist( $proc_data, 'cepatlakoo_cart_phone_text' ),
                'cepatlakoo_cart_phone_event' => cL_exist( $proc_data, 'cepatlakoo_cart_phone_event' ),

                'cepatlakoo_cart_telegram' => cL_exist( $proc_data, 'cepatlakoo_cart_telegram' ),
                'cepatlakoo_cart_telegram_text' => cL_exist( $proc_data, 'cepatlakoo_cart_telegram_text' ),
                'cepatlakoo_cart_telegram_event' => cL_exist( $proc_data, 'cepatlakoo_cart_telegram_event' ),

                'cepatlakoo_minify_html' => cL_exist( $proc_data, 'cepatlakoo_minify_html' ),
                'cepatlakoo_remove_querystring' => cL_exist( $proc_data, 'cepatlakoo_remove_querystring' ),
                'cepatlakoo_facebook_pixel_id' => cL_exist( $proc_data, 'cepatlakoo_facebook_pixel_id' ),

                'cepatlakoo_select_confirmation' => cL_exist( $proc_data, 'cepatlakoo_select_confirmation' ),
                'cepatlakoo_purchase_confirmation' => cL_exist( $proc_data, 'cepatlakoo_purchase_confirmation' ),

                'cepatlakoo_sms_gateway_email' => cL_exist( $proc_data, 'cepatlakoo_sms_gateway_email' ),
                'cepatlakoo_sms_gateway_password' => cL_exist( $proc_data, 'cepatlakoo_sms_gateway_password' ),
                'cepatlakoo_sms_gateway_deviceID' => cL_exist( $proc_data, 'cepatlakoo_sms_gateway_deviceID' ),
                'cepatlakoo_sms_gateway_phone' => cL_exist( $proc_data, 'cepatlakoo_sms_gateway_phone' ),

                'cepatlakoo_sms_new_order_admin' => cL_exist( $proc_data, 'cepatlakoo_sms_new_order_admin' ),
                'cepatlakoo_sms_new_order' => cL_exist( $proc_data, 'cepatlakoo_sms_new_order' ),
                'cepatlakoo_sms_order_process' => cL_exist( $proc_data, 'cepatlakoo_sms_order_process' ),
                'cepatlakoo_sms_order_complete' => cL_exist( $proc_data, 'cepatlakoo_sms_order_complete' ),
                'cepatlakoo_sms_order_pending' => cL_exist( $proc_data, 'cepatlakoo_sms_order_pending' ),
                'cepatlakoo_sms_order_failed' => cL_exist( $proc_data, 'cepatlakoo_sms_order_failed' ),
                'cepatlakoo_sms_order_refunded' => cL_exist( $proc_data, 'cepatlakoo_sms_order_refunded' ),
                'cepatlakoo_sms_order_cancel' => cL_exist( $proc_data, 'cepatlakoo_sms_order_cancel' ),
                'cepatlakoo_sms_order_note' => cL_exist( $proc_data, 'cepatlakoo_sms_order_note' ),

                //TYPO
                'cepatlakoo_heading_h1_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h1_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h1_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h1_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h1_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h1_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_heading_h2_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h2_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h2_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h2_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h2_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h2_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_heading_h3_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h3_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h3_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h3_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h3_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h3_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_heading_h4_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h4_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h4_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h4_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h4_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h4_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_heading_h5_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h5_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h5_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h5_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h5_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h5_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_heading_h6_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h6_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h6_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h6_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h6_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_heading_h6_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_main_font_typo' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_main_font_typo', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_main_font_typo', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_main_font_typo', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_main_font_typo', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_main_font_typo', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_site_title_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_site_title_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_site_title_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_site_title_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_site_title_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_site_title_typography', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_menu_text_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_menu_text_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_menu_text_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_menu_text_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_menu_text_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_menu_text_typography', 'color'),
                    //'google' => 1,
                ),


                'cepatlakoo_submenu_text_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_text_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_text_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_text_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_text_typography', 'line-height'),
                    'text-transform' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'text-transform'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_text_typography', 'color'),
                    //'google' => 1,
                ),


                'cepatlakoo_submenu_2nd_text_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'color'),
                    'text-transform' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'text-transform'),
                    //'google' => 1,
                ),

                'cepatlakoo_footer_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_footer_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_footer_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_footer_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_footer_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_footer_typography', 'color'),
                    'text-transform' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_submenu_2nd_text_typography', 'text-transform'),
                    //'google' => 1,
                ),

                'cepatlakoo_page_title_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_page_title_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_page_title_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_page_title_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_page_title_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_page_title_typography', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_post_title_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_post_title_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_post_title_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_post_title_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_post_title_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_post_title_typography', 'color'),
                    //'google' => 1,
                ),

                'cepatlakoo_paragraf_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_paragraf_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_paragraf_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_paragraf_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_paragraf_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_paragraf_typography', 'color'),
                    // 'google' => 1,
                ),

                'cepatlakoo_woo_product_title_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_product_title_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_product_title_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_product_title_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_product_title_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_product_title_typography', 'color'),
                    // 'google' => 1,
                ),

                'cepatlakoo_woo_paragraf_typography' => array(
                    'font-family' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_paragraf_typography', 'font-family'),
                    'font-weight' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_paragraf_typography', 'font-weight'),
                    'font-size' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_paragraf_typography', 'font-size'),
                    'line-height' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_paragraf_typography', 'line-height'),
                    'color' => cepatlakoo_pointer_array($proc_data, 'cepatlakoo_woo_paragraf_typography', 'color'),
                    // 'google' => 1,
                ),

                // COLOR
                'cepatlakoo_general_theme_color' => cL_exist( $proc_data, 'cepatlakoo_general_theme_color' ),

                'cepatlakoo_general_bg_theme_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_general_bg_theme_color' ),
                ),

                'cepatlakoo_link_theme_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_link_theme_color' ),
                ),

                'cepatlakoo_linkhover_theme_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_linkhover_theme_color' ),
                ),

               'cepatlakoo_backtotop_bg_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_backtotop_bg_color' ),
                ),

                'cepatlakoo_backtotop_bg_hover_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_backtotop_bg_hover_color' ),
                ),

                'cepatlakoo_form_field_bg_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_form_field_bg_color' ),
                ),

                'cepatlakoo_woocommerce_button_bg_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_woocommerce_button_bg_color' ),
                ),

                'cepatlakoo_woocommerce_price_color' => cL_exist( $proc_data, 'cepatlakoo_woocommerce_price_color' ),

                'cepatlakoo_woocommerce_striketrough_price_color' => cL_exist( $proc_data, 'cepatlakoo_woocommerce_striketrough_price_color' ),

               'cepatlakoo_cart_not_empty_color' => cL_exist( $proc_data, 'cepatlakoo_cart_not_empty_color' ),

                'cepatlakoo_topbar_link_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_topbar_menu_bg_color' ),
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_topbar_link_hover_color' )
                ),

                'cepatlakoo_social_media_hover' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_social_media_hover' ),
                ),

                'cepatlakoo_main_menu_hover_color' => array(
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_main_menu_hover_color' ),
                ),

               'cepatlakoo_sub_menu_hover_color' => array(
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_sub_menu_hover_color' ),
                ),

                'cepatlakoo_sub_menu_bg_hover_color' => array(
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_sub_menu_bg_hover_color' ),
                ),

                'cepatlakoo_footer_widget_background_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_footer_widget_background_color' ),
                ),

                'cepatlakoo_footer_background_color' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_footer_background_color' ),
                ),

                'cepatlakoo_footer_hover_link_color' => array(
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_footer_hover_link_color' ),
                ),

                'cepatlakoo_widget_footer_link_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_widget_footer_link_color' ),
                ),

               'cepatlakoo_widget_link_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_widget_link_color' ),
                        'hover' => cL_exist( $proc_data, 'cepatlakoo_widget_link_hover_color' ),
                ),

                'cepatlakoo_page_title_bg_color' => array(

                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_page_title_bg_color' ),
                ),

                'cepatlakoo_post_meta_text_color' => cL_exist( $proc_data, 'cepatlakoo_post_meta_text_color' ),

               'cepatlakoo_postmeta_link_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_postmeta_link_color' ),
                ),

                'cepatlakoo_post_btn_color' => array(
                        'regular' => cL_exist( $proc_data, 'cepatlakoo_post_btn_color' ),
                ),

                'cepatlakoo_sharing_btn_color' => array(
                        'regular'   => cL_exist( $proc_data, 'cepatlakoo_sharing_btn_color' ),
                        'hover'     => cL_exist( $proc_data, 'cepatlakoo_sharing_btn_hover_color' )
                ),

                'cepatlakoo_custom_bg_bbm' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_bbm' ),
                ),

                'cepatlakoo_custom_bg_wa' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_wa' ),
                ),

               'cepatlakoo_custom_bg_sms' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_sms' ),
                ),

                'cepatlakoo_custom_bg_line' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_line' ),
                ),

                'cepatlakoo_custom_bg_phone' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_phone' ),
                ),

               'cepatlakoo_custom_bg_telegram' => array(
                        'background-color' => cL_exist( $proc_data, 'cepatlakoo_custom_bg_telegram' ),
                ),

                'cepatlakoo_logo_login_dashboard' => array(
                        'url' => '',
                        'id' => cL_exist( $proc_data, 'cepatlakoo_logo_login_dashboard' ),
                        'height' => '',
                        'height' => '',
                        'thumbnail' => ''
                ),

                'cepatlakoo_icon_admin_dashboard' => array(
                        'url' => '',
                        'id' => cL_exist( $proc_data, 'cepatlakoo_icon_admin_dashboard' ),
                        'height' => '',
                        'height' => '',
                        'thumbnail' => '',
                ),
            );
            
            $new_data = cepatlakoo_array_filter_recursive($new_data, function($var){return ( !is_null($var) ); });
            $new_data = cepatlakoo_array_filter_recursive($new_data, function($var){return ( (!is_array($var) && !is_null($var)) || (is_array($var) && !empty($var) ) ); });
            
            update_option( 'cl_options',  $new_data );
            if ( FALSE === get_option('cepatlakoo_migration_themeoption' ) && FALSE === update_option('cepatlakoo_migration_themeoption', FALSE ) ) {
              add_option('cepatlakoo_migration_themeoption', '0');
            }
            update_option( 'cepatlakoo_migration_themeoption',  '1' );
            deactivate_plugins( TF_PATH .'/titan-framework.php', true);
        }else{
            update_option( 'cepatlakoo_migration_themeoption',  '1' );
        }
    }
}


/**
 * Recursively filter an array
 *
 * @param array $array
 * @param callable $callback
 *
 * @return array
 */
function cepatlakoo_array_filter_recursive( array $array, callable $callback = null ) {
    $array = is_callable( $callback ) ? array_filter( $array, $callback ) : array_filter( $array );
    foreach ( $array as &$value ) {
        if ( is_array( $value ) ) {
            $value = call_user_func( __FUNCTION__, $value, $callback );
        }
    }
 
    return $array;
}
 

/**
 * Function for getting data in nested array
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
function cepatlakoo_pointer_array( $base, $data, $pointer ){
    $data_pointer = unserialize($base[$data]);

    if ( isset( $data_pointer[$pointer] ) ) {
        return $data_pointer[$pointer];
    }else{
        return null;
    }
}

/**
 * Function for check value exist or not
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
function cL_exist( $base, $data ){
    if( is_serialized( $base )){
        $base = unserialize($base[$data]);
    }

    if ( isset( $base[$data] ) ) {
        return $base[$data];
    }else{
        return null;
    }
}

/**
 * Function for trigger override frontend
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if( ! get_option('cepatlakoo_migration_themeoption') && get_option('cepatlakoo_options') ){
    add_action( 'init', 'cepatlakoo_migration_init' ); // Function Display Words on Foot
}

/**
 * Function for override frontend
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if( ! function_exists('cepatlakoo_migration_init') ) {
    function cepatlakoo_migration_init() {
        if ((!strstr($_SERVER['PHP_SELF'], 'wp-cron.php') &&
            !strstr($_SERVER['PHP_SELF'], 'wp-login.php') &&
            !strstr($_SERVER['PHP_SELF'], 'wp-admin/') &&
            !strstr($_SERVER['PHP_SELF'], 'async-upload.php') &&!(strstr($_SERVER['PHP_SELF'], 'upgrade.php') &&
            !strstr($_SERVER['PHP_SELF'], '/plugins/') &&
            !strstr($_SERVER['PHP_SELF'], '/xmlrpc.php'))))
            {
        $protocol = !empty($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.1', 'HTTP/1.0')) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        $charset = get_bloginfo('charset') ? get_bloginfo('charset') : 'UTF-8';
        $status_code = (int) apply_filters('wp_maintenance_status_code', 503);

        nocache_headers();
        ob_start();
        header("Content-type: text/html; charset=$charset");
           get_template_part('template-migration');
        ob_flush();
        exit();
        }
    }
}

/**
 * Function for migration cmb2 metabox to acf
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.0.0
 */
if ( ! function_exists('cepatlakoo_200_migration') ) {
    function cepatlakoo_200_migration() {
        global $cl_options;

        if ( !get_option('cepatlakoo_200_migration') && class_exists('Redux') ) {

            $slides = get_posts([
                'numberposts'   => -1,
                'post_type'     => 'cl_slideshow',
            ]);
            
            if ( is_array($slides) ) {
                foreach ( $slides as $slide ) {
                    $metas = get_post_meta($slide->ID, 'cepatlakoo_slideshow_image_group', true);
                    $value = array();
                    if( is_array($metas) ){
                        foreach($metas as $meta){
                            $value[] = array(
                                'cepatlakoo_slideshow_image'        => isset($meta['cepatlakoo_slideshow_image_id']) ? $meta['cepatlakoo_slideshow_image_id'] : '',
                                'cepatlakoo_slideshow_enable_text'  => isset($meta['cepatlakoo_slideshow_enable_text']) ? $meta['cepatlakoo_slideshow_enable_text'] : '',
                                'cepatlakoo_slideshow_heading'      => isset($meta['cepatlakoo_slideshow_heading']) ? $meta['cepatlakoo_slideshow_heading'] : '',
                                'cepatlakoo_slideshow_desc'         => isset($meta['cepatlakoo_slideshow_desc']) ? $meta['cepatlakoo_slideshow_desc'] : '',
                                'cepatlakoo_slideshow_button_text'  => isset($meta['cepatlakoo_slideshow_button_text']) ? $meta['cepatlakoo_slideshow_button_text'] : '',
                                'cepatlakoo_slideshow_button_url'   => isset($meta['cepatlakoo_slideshow_button_url']) ? $meta['cepatlakoo_slideshow_button_url'] : '',
                                'cepatlakoo_slideshow_open_link'    => isset($meta['cepatlakoo_slideshow_open_link']) ? $meta['cepatlakoo_slideshow_open_link'] : '',
                            );
                        }
                        
                        $test = update_field( 'cepatlakoo_slideshow_image_group', $value, $slide->ID );
                    }
                }
            }

            $cepatlakoo_custom_bg_wa = $cl_options['cepatlakoo_custom_bg_wa'];
            $cepatlakoo_sticky_button_trigger = $cl_options['cepatlakoo_sticky_button_trigger'];
            
            Redux::set_option( 'cl_options', 'cepatlakoo_sticky_buttons', $cepatlakoo_sticky_button_trigger );

            Redux::set_option( 'cl_options', 'cepatlakoo_add_to_cart_text', 'Beli Sekarang' );
            Redux::set_option( 'cl_options', 'cepatlakoo_proceed_to_checkout_text', 'Konfirmasi Pesanan' );
            Redux::set_option( 'cl_options', 'cepatlakoo_place_order_text', 'Proses Pesanan' );
    
            if ( !$cl_options['cepatlakoo_sticky_button_trigger'] ) {
                Redux::set_option( 'cl_options', 'cepatlakoo_sticky_button_type', 'whatsapp' );
            }

            add_option('cepatlakoo_200_migration', '1');
        } else {
            return false;
        }
    }
}
add_action( 'init', 'cepatlakoo_200_migration', 99 );

/**
 * Function for migration cmb2 metabox to acf
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if ( ! function_exists('cepatlakoo_210_migration') ) {
    function cepatlakoo_210_migration() {
        global $cl_options;
        
        if ( !get_option('cepatlakoo_210_migration') && class_exists('Redux') && !empty($cl_options['cepatlakoo_cart_wa']) && $cl_options['cepatlakoo_cart_wa'] != 6285966634332 ) {
            $cs = 'CS '.get_bloginfo('name').' | '.$cl_options['cepatlakoo_cart_wa'];
            Redux::set_option( 'cl_options', 'cepatlakoo_wa_rotator_cs', $cs );

            add_option('cepatlakoo_210_migration', '1');
        } else {
            return false;
        }
    }
}
add_action( 'init', 'cepatlakoo_210_migration', 99 );
?>
