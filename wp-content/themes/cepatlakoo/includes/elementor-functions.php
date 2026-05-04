<?php
// Define constant
if (!defined('CL_ELEMENTOR_DIR')) {
    define('CL_ELEMENTOR_DIR', get_template_directory() . '/includes/widgets/elementor/' );
}

// Load elementor custom element
function cepatlakoo_include_widgets($widgets_manager) {

    if ( class_exists( 'WooCommerce' ) ){ // Load if Woocommerce Available
        require_once( CL_ELEMENTOR_DIR . 'el-featured-products.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Featured_Products_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-hand-picked-products.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Hand_Picked_Products_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-recent-products.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Recent_Products_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-best-selling-products.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Best_Selling_Products_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-single-product-highlight.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Single_Products_Highlight_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-products-by-category.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Products_By_Category_Widget_Elementor() );

        require_once( CL_ELEMENTOR_DIR . 'el-product-statistics.php' );
        $widgets_manager->register( new Elementor\CepatLakoo_Product_Statistics_Widget_Elementor() );
    }

    require_once( CL_ELEMENTOR_DIR . 'el-twitter-feeds.php' );
    $widgets_manager->register( new Elementor\Widget_Twitter_Feeds() );

    require_once( CL_ELEMENTOR_DIR . 'el-latest-blog.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_Latest_News_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-custom-form.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_Custom_Form_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-contact-buttons.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_Messenger_Buttons_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-courier-logo.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_Courier_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-bank-logo.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_Bank_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-countdown.php' );
    $widgets_manager->register( new Elementor\Cepatlakoo_Countdown_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-slideshow.php' );
    $widgets_manager->register( new Elementor\CepatLakoo_SlideShow_Widget_Elementor() );

    require_once( CL_ELEMENTOR_DIR . 'el-testimonials.php' );
    $widgets_manager->register( new Elementor\Cepatlakoo_Testimony_Widget_Elementor() );

}
add_action('elementor/widgets/register', 'cepatlakoo_include_widgets');

add_action( 'elementor/editor/before_enqueue_scripts', function() {
    wp_enqueue_script(
        'el-owl-carousel',
        get_template_directory_uri() .'/assets/js/owl.carousel.min.js',
        [
            'elementor-editor', // dependency
        ],
        '1.4.4',
        true // in_footer
    );

    wp_enqueue_script( 'jquery.match-height', get_template_directory_uri() .'/assets/js/matchheight-min.js', array('jquery'), null, true );

    wp_enqueue_script(
        'el-countdown',
        get_template_directory_uri() .'/assets/js/jquery.countdown.js',
        [
            'elementor-editor', // dependency
        ],
        '2.1.0',
        true // in_footer
    );

    wp_enqueue_script(
        'cepatlakoo-editor',
        get_template_directory_uri() .'/assets/js/elementor-cepatlakoo-editor.js',
        [
            'elementor-editor', // dependency
        ],
        '1.0.0',
        true // in_footer
    );
    
    wp_localize_script('cepatlakoo-editor', '_cepatlakoo_cart', array(
        'imagelist' => cepatlakoo_extract_el_library(),
        'cl_cart_elementor'  => get_option( 'elementor_use_mini_cart_template', 'no' ) != 'yes' ? 'no' : 'yes',
    ));

    wp_enqueue_script(
        'el-owl-thumb',
        get_template_directory_uri() .'/assets/js/owl-thumb.js',
        [
            'elementor-editor', // dependency
        ],
        null,
        true // in_footer
    );

    wp_enqueue_script(
        'el-jquery.magnific-popup',
        get_template_directory_uri() .'/assets/js/jquery.magnific-popup.js',
        [
            'elementor-editor', // dependency
        ],
        null,
        true // in_footer
    );

    wp_enqueue_style(
        'clel-preview', get_template_directory_uri() .'/assets/css/dist/elementor-preview.css',
        [],
        '1.0.0'
    );

    if ( class_exists( 'WooCommerce' ) ) {
        wc()->init();
        wc()->frontend_includes();
        cepatlakoo_init_wc();
    }

} );

// Cepatlakoo General Script
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'cepatlakoo', get_template_directory_uri() .'/assets/js/elementor-cepatlakoo-frontend.js', array('jquery'), null, true);
} );

add_action('elementor/frontend/after_enqueue_scripts', function(){
    wp_enqueue_script( 'cl-countdown', get_template_directory_uri() .'/assets/js/jquery.countdown.js', '', '2.1.0', false);
});

add_action('wp_head', 'cl_custom_styles', 100);
function cl_custom_styles() {
    echo "<style>.woocommerce-product-gallery { opacity: 1 !important; }</style>";
}
?>
