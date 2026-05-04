<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
    return;
}

$cl_store_address = '';

if ( class_exists( 'woocommerce' ) ) {
	global $pok_helper;
    $countries         = apply_filters( 'woocommerce_countries', include WC()->plugin_path() . '/i18n/countries.php' );
    $states            = apply_filters( 'woocommerce_states', include WC()->plugin_path() . '/i18n/states.php' );
    $store_address     = get_option( 'woocommerce_store_address' );
    $store_address_2   = get_option( 'woocommerce_store_address_2' );
    $store_city        = get_option( 'woocommerce_store_city' );
    $store_postcode    = get_option( 'woocommerce_store_postcode' );
    $split_country     = explode( ":", get_option( 'woocommerce_default_country' ) );
    $store_country     = isset($countries[$split_country[0]]) ? $countries[$split_country[0]] : '';
    $store_state       = '';
    if ( isset($states[$split_country[0]]) ) {
        $state_key = isset($split_country[1]) ? $split_country[1] : '';
        if ( is_object( $pok_helper ) && method_exists( $pok_helper, 'map_ongkir_states' ) ) {
            $state_key = $pok_helper->map_ongkir_states($state_key);
        }
        $store_state = isset($states[$split_country[0]][$state_key]) ? $states[$split_country[0]][$state_key] : '';
    }

    $cl_store_address = $store_address .' '. $store_address_2 .', '. $store_city .', '. $store_state .', '. $store_postcode .', '. $store_country;
}

$site_logo = get_theme_mod( 'custom_logo' );
$logo_image = wp_get_attachment_image_src( $site_logo , 'full' );
$logo_url = is_array($logo_image) && isset($logo_image[0]) && !empty($logo_image[0]) ? $logo_image[0] : get_template_directory_uri().'/assets/images/logo-cepatlakoo-purple.png';

// Generate array for number of items per page
for ( $i=1; $i<=30; $i++ ) {
    $items_per_pages_array[$i] = $i;
}

// This is your option name where all the Redux data is stored.
$opt_name = "cl_options";

// This line is only for altering the demo. Can be easily removed.
// $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

/*
 *
 * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
 *
 */

$sampleHTML = '';
if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
    Redux_Functions::initWpFilesystem();

    global $wp_filesystem;

    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
}

// Background Patterns Reader
$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
$sample_patterns      = array();

if ( is_dir( $sample_patterns_path ) ) {

    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
        $sample_patterns = array();

        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                $name              = explode( '.', $sample_patterns_file );
                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                $sample_patterns[] = array(
                    'alt' => $name,
                    'img' => $sample_patterns_url . $sample_patterns_file
                );
            }
        }
    }
}

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => __( 'Theme Options', 'cepatlakoo' ),
    'page_title'           => __( 'Theme Options', 'cepatlakoo' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'show_options_object'  => false,
    'update_notice'        => false,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => 61,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => get_template_directory_uri() .'/assets/images/icon-cepatlakoo.png',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'cepatlakoo',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
$args['admin_bar_links'][] = array(
    'id'    => 'cl-member',
    'href'  => 'https://cepatlakoo.com/dashboard/',
    'title' => __( 'Member Area', 'cepatlakoo' ),
);

$args['admin_bar_links'][] = array(
    'id'    => 'cl-kb',
    'href'  => 'https://cepatlakoo.com/knowledgebase/',
    'title' => __( 'Knowledge Base', 'cepatlakoo' ),
);

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args['share_icons'][] = array(
    'url'   => 'https://cepatlakoo.com',
    'title' => 'Visit our website',
    'icon'  => 'el el-link'
    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
);

// Panel Intro text -> before the form
if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
    if ( ! empty( $args['global_variable'] ) ) {
        $v = $args['global_variable'];
    } else {
        $v = str_replace( '-', '_', $args['opt_name'] );
    }
    $args['intro_text'] = wp_kses( __( '<b>Need help?</b> Login to the member area and join our Facebook support group and watch the video tutorials. <a href="https://cepatlakoo.com/dashboard/" target="_blank">Click here</a>', 'cepatlakoo' ), array(
                    'a' => array( 'href' => array(), 'target' => array() ),
                    'b' => array() )
                );
} else {
    $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'cepatlakoo' );
}

// Add content after the form.
// $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'cepatlakoo' );

Redux::setArgs( $opt_name, $args );

/*
 * ---> END ARGUMENTS
 */


/*
 * ---> START HELP TABS
 */

$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information 1', 'cepatlakoo' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'cepatlakoo' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => __( 'Theme Information 2', 'cepatlakoo' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'cepatlakoo' )
    )
);
Redux::set_help_tab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'cepatlakoo' );
Redux::set_help_sidebar( $opt_name, $content );

/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*
    As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for
 */

// Set Facebook Pixel Events
$fb_pixel_events = array(
    'AddPaymentInfo' => esc_html( 'AddPaymentInfo' ),
    'AddToCart' => esc_html( 'AddToCart' ),
    'AddToWishlist' => esc_html( 'AddToWishlist' ),
    'CompleteRegistration' => esc_html( 'CompleteRegistration' ),
    'Contact' => esc_html( 'Contact' ),
    'CustomizeProduct' => esc_html( 'CustomizeProduct' ),
    'Donate' => esc_html( 'Donate' ),
    'FindLocation' => esc_html( 'FindLocation' ),
    'InitiateCheckout' => esc_html( 'InitiateCheckout' ),
    'Lead' => esc_html( 'Lead' ),
    'PageView' => esc_html( 'PageView' ),
    'Purchase' => esc_html( 'Purchase' ),
    'Schedule' => esc_html( 'Schedule' ),
    'Search' => esc_html( 'Search' ),
    'StartTrial' => esc_html( 'StartTrial' ),
    'SubmitApplication' => esc_html( 'SubmitApplication' ),
    'Subscribe' => esc_html( 'Subscribe' ),
    'ViewContent' => esc_html( 'ViewContent' ),
);

$fb_pixel_checkout_events = array(
    'InitiateCheckout' => esc_html( 'InitiateCheckout' ),
    'AddPaymentInfo' => esc_html( 'AddPaymentInfo' ),
    'Purchase' => esc_html( 'Purchase' )
);

// GENERAL SETTINGS
Redux::setSection( $opt_name, array(
    'title'            => __( 'General Settings', 'cepatlakoo' ),
    'id'               => 'general_settings',
    'customizer_width' => '450px',
    'desc'             => __( 'General settings for the theme.', 'cepatlakoo' ),
    'icon'             => 'el el-cog',
    'fields'           => array(

        array(
            'id'                        => 'cepatlakoo_header_logo',
            'type'                      => 'section',
            'title'                     => esc_html__('Logo', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Site logo settings.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                        => 'cepatlakoo_logo_type',
            'type'                      => 'select',
            'title'                     => esc_html__('Logo Type', 'cepatlakoo'),
            'description'               => esc_html__('Choose what kind of logo you want to display.', 'cepatlakoo'),
            'options'                   => array(
                'text' => esc_html__('Site Title', 'cepatlakoo'),
                'image' => esc_html__('Image', 'cepatlakoo'),
                'image-text' => esc_html__('Image & Site Title', 'cepatlakoo'),
            ),
            'default'  => 'image',
        ),

        array(
            'id'                        => 'cepatlakoo_site_title_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Site Title', 'cepatlakoo'),
            'description'               => esc_html__('You can change site title value from Settings > General.', 'cepatlakoo'),
            'url'                       => true,
            'default'                   => get_bloginfo('name'),
            'readonly'                  => true,
            'required'                  => array( 
                                            array( 'cepatlakoo_logo_type', '!=', 'image' ),
            ),
        ),
        array(
            'id'                        => 'cepatlakoo_logo_image',
            'type'                      => 'media',
            'title'                     => esc_html__('Site Logo', 'cepatlakoo'),
            'url'                       => true,
            'default'                   => array(
                                            'url' => !empty($logo_url) ? $logo_url : get_template_directory_uri().'/assets/images/logo-cepatlakoo-purple.png',
            ),
            'required'                  => array( 
                                            array( 'cepatlakoo_logo_type', '!=', 'text' ),
            ),
        ),
        array(
            'id'                        => 'cepatlakoo_logo_height',
            'type'                      => 'slider',
            'title'                     => esc_html__('Logo Height', 'cepatlakoo'),
            'description'               => esc_html__('Logo height in pixel.', 'cepatlakoo'),
            'default'                   => 40,
            'min'                       => 10,
            'step'                      => 1,
            'max'                       => 70,
            'display_value'             => 'label',
            'required'                  => array( 
                                            array( 'cepatlakoo_logo_type', '!=', 'text' ),
            ),
        ),
        array(
            'id'                        => 'cepatlakoo_google_analytics_tracking',
            'type'                      => 'text',
            'title'                     => esc_html__('Google Analytics Tracking ID', 'cepatlakoo'),
            'desc'                      => esc_html__('Set Google Analytics Tracking ID.', 'cepatlakoo'),
            'default'                   => ''
        ),
        array(
            'id'                        => 'cepatlakoo_google_analytics_admin',
            'type'                      => 'switch',
            'title'                     =>  esc_html__( 'Disable Google Analytics for admin', 'cepatlakoo' ),
            'default'                   => '0',
            'desc'                      => esc_html__('Disable Google Analytics on all pages when login as admin.', 'cepatlakoo'),
        ),
        array(
            'id'                      => 'cepatlakoo_open_graph_trigger',
            'type'                    => 'switch',
            'title'                   => esc_html__('Open Graph', 'cepatlakoo'),
            'desc'                    => esc_html__('Enable Open Graph for Post, Page and Product post type.', 'cepatlakoo'),
            'default'                 => true,
        ),
        array(
            'id'                      => 'cepatlakoo_seo_trigger',
            'type'                    => 'switch',
            'title'                   => esc_html__('SEO Feature', 'cepatlakoo'),
            'desc'                    => esc_html__('Enable SEO feature in Post, Page and Product post type.', 'cepatlakoo'),
            'default'                 => true,
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Top Bar', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_topbar',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_top_bar',
            'type'                      => 'switch',
            'title'                     => esc_html__('Top Bar', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable top bar.', 'cepatlakoo' ),
            'default'                   => true,
        ),

        array(
            'id'                        => 'cepatlakoo_top_bar_icon_size',
            'type'                      => 'dimensions',
            'units'                     => 'px',
            'title'                     => esc_html__('Icon Size', 'cepatlakoo'),
            'output'                    => array('#top-bar .socials ul li a svg, #top-bar .customer-care svg, #top-bar .search-trigger svg'),
            'default'                   => array(
                                            'width'   => '14', 
                                            'height'  => '14'
                                        ),
        ),

        array(
            'id'                        => 'cepatlakoo_fb_profile_url',
            'type'                      => 'text',
            'title'                     => esc_html__('Facebook Profile Url', 'cepatlakoo'),
            'desc'                      => esc_html__('Leave it empty to hide the icon.', 'cepatlakoo'),
            'placeholder'               => 'https://www.facebook.com/cepatlakoo',
            'default'                   => 'https://www.facebook.com/cepatlakoo'
        ),

        array(
            'id'                        => 'cepatlakoo_tw_profile_url',
            'type'                      => 'text',
            'title'                     => esc_html__('Twitter Profile Url', 'cepatlakoo'),
            'desc'                      => esc_html__('Leave it empty to hide the icon.', 'cepatlakoo'),
            'placeholder'               => 'https://twitter.com/cepatlakoo',
            'default'                   => 'https://twitter.com/cepatlakoo'
        ),

        array(
            'id'                        => 'cepatlakoo_itg_profile_url',
            'type'                      => 'text',
            'title'                     => esc_html__('Instagram Profile Url', 'cepatlakoo'),
            'desc'                      => esc_html__('Leave it empty to hide the icon.', 'cepatlakoo'),
            'placeholder'               => 'https://instagram.com/cepatlakoo',
            'default'                   => 'https://instagram.com/cepatlakoo'
        ),

        array(
            'id'                        => 'cepatlakoo_tiktok_profile_url',
            'type'                      => 'text',
            'title'                     => esc_html__('Tiktok Profile Url', 'cepatlakoo'),
            'desc'                      => esc_html__('Leave it empty to hide the icon.', 'cepatlakoo'),
            'placeholder'               => 'https://tiktok.com/@cepatlakoo',
            'default'                   => ''
        ),

        array(
            'id'                        => 'cepatlakoo_customer_phone_label',
            'type'                      => 'text',
            'title'                     => esc_html__('Phone Label', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Text label on the left hand side of the phone number on top header.', 'cepatlakoo' ),
            'placeholder'               => 'Call Center :  ',
            'default'                   => 'CS:'
        ),

        array(
            'id'                        => 'cepatlakoo_customer_care_phone',
            'type'                      => 'text',
            'title'                     => esc_html__('Phone Number', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Phone number on top header.', 'cepatlakoo' ),
            'placeholder'               => '021-67219821',
            'default'                   => '021-67219821'
        ),

        array(
            'id'                        => 'cepatlakoo_customer_phone_type',
            'type'                      => 'select',
            'title'                     => esc_html__('Link Phone Number To', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Link Phone Number on top header.', 'cepatlakoo' ),
            'options'                   => array(
                'phone' => esc_html__( 'Phone', 'cepatlakoo' ),
                'wa'    => esc_html__( 'Whatsapp', 'cepatlakoo' ),
              ),
            'default'  => 'phone',
        ),

        array(
            'id'                        => 'cepatlakoo_top_bar_msg',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Top Bar Message', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Message to be displayed on the top bar. Allowed HTML tags: <a>, <br />, <p>, <b>, <i>, <em>,<strong>', 'cepatlakoo' ),
            'default'                   => 'Diskon besar di banyak kategori produk.'
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Header', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_header',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_header_style_opt',
            'type'                      => 'select',
            'title'                     => esc_html__('Header Layout Style', 'cepatlakoo'),
            'desc'                      => esc_html__('Override header layout style for all pages.', 'cepatlakoo'),
            'options'                   => array(
                                             '1' => esc_html__( 'Logo Left', 'cepatlakoo' ),
                                             '2' => esc_html__( 'Logo Middle', 'cepatlakoo' ),
            ),
            'default'  => '1',
        ),

        array(
            'id'                        => 'cepatlakoo_header_cart_icon_size',
            'type'                      => 'dimensions',
            'units'                     => 'px',
            'title'                     => esc_html__('Cart Icon Size', 'cepatlakoo'),
            'output'                    => array('#main-header .user-carts .cart-counter svg'),
            'default'                   => array(
                                            'width'   => '18', 
                                            'height'  => '30'
                                        ),
        ),

        array(
            'id'                        => 'cepatlakoo_mobile_menu_icon_size',
            'type'                      => 'dimensions',
            'units'                     => 'px',
            'title'                     => esc_html__('Mobile Menu Icon Size', 'cepatlakoo'),
            'output'                    => array('.mobile-menu-trigger svg'),
            'default'                   => array(
                                            'width'   => '22', 
                                            'height'  => '22'
                                        ),
        ),
    ),
) ); 

Redux::setSection( $opt_name, array(
    'title'            => __( 'Footer', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_footer',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_footer_widget_column',
            'type'                      => 'select',
            'title'                     => esc_html__('Number of Footer Widget Column', 'cepatlakoo'),
            'desc'                      => esc_html__('Number of columns in footer widget section.', 'cepatlakoo'),
            'options'                   => array(
                                            '1' => esc_html__( '1 Column', 'cepatlakoo' ),
                                            '2' => esc_html__( '2 Column', 'cepatlakoo' ),
                                            '3' => esc_html__( '3 Column', 'cepatlakoo' ),
                                            '4' => esc_html__( '4 Column', 'cepatlakoo' ),
                                          ),
            'default'  => '3',
        ),
        array(
            'id'                        => 'cepatlakoo_copyright_text',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Copyright Text', 'cepatlakoo'),
            'desc'                      => esc_html__('Copyright text in footer. Allowed HTML tags: <a>, <br />, <p>, <strong>. You can also use %site_name% to display site name, and %current_year% to display current year.', 'cepatlakoo'),
            'default'                   => 'Copyright %current_year%. All Rights Reserved <br /> Designed by <a href="https://cepatlakoo.com" target="_blank">Cepatlakoo</a>'
        ),
        array(
            'id'                        => 'cepatlakoo_footer_codes',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Footer Codes', 'cepatlakoo'),
            'desc'                      => esc_html__('Footer codes that will render after <footer> tag', 'cepatlakoo'),
            'default'                   => ''
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Head & Footer Codes', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_head_footer_codes',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_header_codes',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Head Codes', 'cepatlakoo'),
            'desc'                      => esc_html__('Head codes that will be in between <head> tag.', 'cepatlakoo'),
            'default'                   => ''
        ),
        array(
            'id'                        => 'cepatlakoo_footer_codes',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Footer Codes', 'cepatlakoo'),
            'desc'                      => esc_html__('Footer codes that will render right before </body> tag.', 'cepatlakoo'),
            'default'                   => ''
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Quick View', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_quickview',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_quickview_status',
            'type'                      => 'switch',
            'title'                     => esc_html__('Enable Quick View', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable quick view.', 'cepatlakoo' ),
            'default'                   => false,
        ),
        array(
            'id'                        => 'cepatlakoo_quickview_product_description',
            'type'                      => 'switch',
            'title'                     => esc_html__('Quick View Product Description', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable product description in quick view.', 'cepatlakoo' ),
            'default'                   => true,
            'required'                  => array( 'cepatlakoo_quickview_status', 'equals', true),
        ),

        array(
            'id'                        => 'cepatlakoo_quickview_product_catergoryandtag',
            'type'                      => 'switch',
            'title'                     => esc_html__('Quick View Product Category & Tag', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable product category and tag in quick view.', 'cepatlakoo' ),
            'default'                   => true,
            'required'                  => array( 'cepatlakoo_quickview_status', 'equals', true),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_blog',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_post_exceprt_length',
            'type'                      => 'slider',
            'title'                     => esc_html__('Post Excerpt Length', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Excerpt lenght on blog and archives page.', 'cepatlakoo' ),
            'default'                   => 65,
            'min'                       => 5,
            'step'                      => 1,
            'max'                       => 100,
            'display_value'             => 'text'
        ),
        array(
            'id'                        => 'cepatlakoo_author_box_status',
            'type'                      => 'switch',
            'title'                     => esc_html__('Author Bio Box', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable author box on blog page.', 'cepatlakoo' ),
            'default'                   => true,
        ),
        array(
            'id'                        => 'cepatlakoo_share_button',
            'type'                      => 'switch',
            'title'                     => esc_html__('Share Buttons', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable share buttons on detail page.', 'cepatlakoo' ),
            'default'                   => true,
        ),
        array(
            'id'                        => 'cepatlakoo_post_nav',
            'type'                      => 'switch',
            'title'                     => esc_html__('Post Navigation', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable post navigation on detail page.', 'cepatlakoo' ),
            'default'                   => true,
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Countdown Timer', 'cepatlakoo' ),
    'desc'            => __( 'Countdown settings for WooCommerce Product Detail Page.', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_countdown',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_countdown_heading_cart',
            'type'                      => 'text',
            'title'                     => esc_html__('Countdown Timer Heading', 'cepatlakoo'),
            'desc'                      => esc_html__('Countdown heading in product detail page.', 'cepatlakoo'),
            'default'                   => esc_html__('Kesempatan Terbatas', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_countdown_subheading_cart',
            'type'                      => 'text',
            'title'                     => esc_html__('Countdown Timer Sub Heading', 'cepatlakoo'),
            'desc'                      => esc_html__('Countdown sub heading in product detail page.', 'cepatlakoo'),
            'default'                   => 'Hanya dijual terbatas, cepat amankan produk ini agar kamu tidak kehabisan.'
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_html__( 'Optimization', 'cepatlakoo' ),
    'desc'             => esc_html__( 'This feature is still experimental. Please use these settings wisely, it could break your site.', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_general_info_optimize',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_minify_html',
            'type'                      => 'switch',
            'title'                     => esc_html__('Minify HTML & JS', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Minify HTML to help speed up your website.', 'cepatlakoo' ),
            'default'                   => false,
        ),
        array(
            'id'                        => 'cepatlakoo_remove_querystring',
            'type'                      => 'switch',
            'title'                     => esc_html__('Remove JS & CSS Version', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Remove JS & CSS version.', 'cepatlakoo' ),
            'default'                   => false,
        ),
    ),
) );

// STORE SETTINGS
Redux::setSection( $opt_name, array(
    'title'            => __( 'Store Settings', 'cepatlakoo' ),
    'id'               => 'store_settings',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related to shopping functionality.', 'cepatlakoo' ),
    'icon'             => 'el el-shopping-cart',
    'fields'           => array(

        array(
            'id'                      => 'cepatlakoo_shoping_cart',
            'type'                    => 'switch',
            'title'                   => esc_html__('Disable Shoping Cart', 'cepatlakoo'),
            'desc'                    => esc_html__('Disable shopping cart feature.', 'cepatlakoo'),
            'default'                 => '0',
            'required'                => array('cepatlakoo_single_product_button_type', '!=', 'whatsapp')
        ),
        
        array(
            'id'                        => 'cepatlakoo_single_sidebar_opt',
            'type'                      => 'select',
            'title'                     => esc_html__('Single Product Sidebar', 'cepatlakoo'),
            'desc'                      => esc_html__('Override Product Detail Sidebar for all Products.', 'cepatlakoo'),
            'options'                   => array(
                                            '0' => esc_html__( 'Default', 'cepatlakoo' ),
                                            '1' => esc_html__( 'With Sidebar', 'cepatlakoo' ),
                                            '2' => esc_html__( 'Without Sidebar', 'cepatlakoo' ),
                                          ),
            'default'  => '2',
        ),
        array(
            'id'                      => 'cepatlakoo_product_badges',
            'type'                    => 'sortable',
            'title'                   => __('Display Product Badges', 'cepatlakoo'),
            'desc'                    => __('Display additional small badges to product detail page, above product title.', 'cepatlakoo'),
            'mode'                    => 'text',
            'options'                 => array(
                                         '1' => '',
                                         '2' => '',
                                        ),
        ),
        array(
            'id'                      => 'cepatlakoo_wc_show_breadcrumb',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display WooCommerce Breacrumb', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide WooCommerce Breacrumb.', 'cepatlakoo'),
            'default'                 => '1',
        ),
        array(
            'id'                      => 'cepatlakoo_wc_loop_product_stock',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Stock on Product Loop', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide stock info on product loop.', 'cepatlakoo'),
            'default'                 => '1',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Shop', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_shop_info',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        
        array(
            'id'                        => 'cepatlakoo_shop_layout',
            'type'                      => 'select',
            'title'                     => esc_html__('Shop Layout', 'cepatlakoo'),
            'desc'                      => esc_html__('Choose the layout for shop & product category pages.', 'cepatlakoo'),
            'options'                   => array(
                                             'sidebar-none' => esc_html__('No Sidebar', 'cepatlakoo'),
                                             'sidebar-right' => esc_html__('Sidebar Right', 'cepatlakoo'),
                                          ),
            'default'  => 'sidebar-none',
        ),
        
        array(
            'id'                        => 'cepatlakoo_shop_columns',
            'type'                      => 'select',
            'title'                     => esc_html__('Shop Columns', 'cepatlakoo'),
            'desc'                      => esc_html__('Product grid columns in shop & product category pages.', 'cepatlakoo'),
            'options'                   => array(
                                             '1' => '1',
                                             '2' => '2',
                                             '3' => '3',
                                             '4' => '4',
                                             '5' => '5',
                                             '6' => '6',
                                          ),
            'default'  => '4',
        ),
        
        array(
            'id'                        => 'cepatlakoo_shop_per_page',
            'type'                      => 'select',
            'title'                     => esc_html__('Products per Page', 'cepatlakoo'),
            'desc'                      => esc_html__('Number of products per page in Shop or product archive pages.', 'cepatlakoo'),
            'options'                   => $items_per_pages_array,
            'default'                   => '12',
        ),

        array(
            'id'                      => 'cepatlakoo_shop_sorting',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Product Sorting', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide product sorting selectbox in shop & product category pages.', 'cepatlakoo'),
            'default'                 => '1',
        ),

        array(
            'id'                      => 'cepatlakoo_shop_result_count',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Product Result Count', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide product result count in shop & product category pages.', 'cepatlakoo'),
            'default'                 => '1',
        ),
        
        array(
            'id'                        => 'cepatlakoo_sale_badge_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Sale Badge Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Set sale badge text on discounted products.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Diskon' , 'cepatlakoo' ),
        ),

    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_html__( 'Products', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_product_info',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                      => 'cepatlakoo_wc_product_tabs',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display WooCommerce Product Tabs', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide WooComerce product tabs on product page.', 'cepatlakoo'),
            'default'                 => '1',
        ),
        array(
            'id'                      => 'cepatlakoo_wc_product_tabs_description',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Description Product Tabs', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide WooComerce description product tabs on product page.', 'cepatlakoo'),
            'default'                 => '1',
            'required'                => array( 'cepatlakoo_wc_product_tabs', 'equals', 1),
        ),
        array(
            'id'                      => 'cepatlakoo_wc_product_tabs_reviews',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Reviews Product Tabs', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide WooComerce reviews product tabs on product page.', 'cepatlakoo'),
            'default'                 => '1',
            'required'                => array( 'cepatlakoo_wc_product_tabs', 'equals', 1),
        ),
        array(
            'id'                      => 'cepatlakoo_wc_product_tabs_additional',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Additional Info Product Tabs', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide WooComerce Additional Info product tabs on product page.', 'cepatlakoo'),
            'default'                 => '1',
            'required'                => array( 'cepatlakoo_wc_product_tabs', 'equals', 1),
        ),

        array(
            'id'                      => 'cepatlakoo_wc_related_products',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Related Products', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide related products in product page.', 'cepatlakoo'),
            'default'                 => '1',
        ),

        array(
            'id'                      => 'cepatlakoo_wc_upsell_products',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Upsell Products', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide upsell products in product page.', 'cepatlakoo'),
            'default'                 => '1',
        ),

        array(
            'id'                        => 'cepatlakoo_wc_product_meta_counter',
            'type'                      => 'select',
            'title'                     => esc_html__('Product View & Sold Statistics', 'cepatlakoo'),
            'desc'                      => esc_html__('Show counter view and sold meta in product page.', 'cepatlakoo'),
            'options'                   => array(
                                            'hide' => esc_html__( 'Hide All', 'cepatlakoo' ),
                                            'view' => esc_html__( 'Only view counter', 'cepatlakoo' ),
                                            'sold' => esc_html__( 'Only sold counter', 'cepatlakoo' ),
                                            'all' => esc_html__( 'Show All', 'cepatlakoo' ),
                                        ),
            'default'  => 'hide',
        ),
        
        array(
            'id'                        => 'cepatlakoo_wc_product_view_counter_text',
            'type'                      => 'text',
            'title'                     => esc_html__('View Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Default text for number of views label.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Dilihat' , 'cepatlakoo' ),
        ),
        
        array(
            'id'                        => 'cepatlakoo_wc_product_sold_counter_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Sold Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Default text for number of product sold label.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Terjual' , 'cepatlakoo' ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_html__( 'Buttons', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_button_info',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        // Add to Cart & WhatsApp buttons
        array(
            'id'                        => 'cepatlakoo_sticky_buttons_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Add to Cart & WhatsApp Buttons', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Add to Cart & WhatsApp buttons on product page and product loop.', 'cepatlakoo'),
            'indent'                    => false,
        ),
        array(
            'id'                        => 'cepatlakoo_loop_product_button_type',
            'type'                      => 'select',
            'title'                     => esc_html__('Select Button(s) to Display in Product Loop', 'cepatlakoo'),
            'description'               => esc_html__('Choose which buttons to be displayed in product loop (product grid in Shop page, related products and homepage).', 'cepatlakoo'),
            'options'                   => array(
                'none' => esc_html__('None', 'cepatlakoo'),
                'addtocart' => esc_html__('Add to Cart Button', 'cepatlakoo'),
                'whatsapp' => esc_html__('WhatsApp Button', 'cepatlakoo'),
                'both' => esc_html__('Both Add to Cart & WhatsApp Buttons', 'cepatlakoo'),
            ),
            'default'  => 'none',
        ),
        array(
            'id'                        => 'cepatlakoo_single_product_button_type',
            'type'                      => 'select',
            'title'                     => esc_html__('Select Button(s) to Display Product Detail', 'cepatlakoo'),
            'description'               => esc_html__('Choose which buttons to be displayed in the product detail page.', 'cepatlakoo'),
            'options'                   => array(
                'addtocart' => esc_html__('Add to Cart Button', 'cepatlakoo'),
                'whatsapp' => esc_html__('WhatsApp Button', 'cepatlakoo'),
                'both' => esc_html__('Both Add to Cart & WhatsApp Buttons', 'cepatlakoo'),
            ),
            'default'  => 'addtocart',
        ),
        array(
            'id'                      => 'cepatlakoo_sticky_buttons',
            'type'                    => 'switch',
            'title'                   => esc_html__('Make Button(s) Sticky', 'cepatlakoo'),
            'desc'                    => esc_html__('Enable or disable sticky buttons on mobile devices.', 'cepatlakoo'),
            'default'                 => true,
        ),
        array(
            'id'                        => 'cepatlakoo_add_to_cart_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Add to Cart Button Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Default text for add to cart button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Beli Sekarang' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_wa_event',
            'type'                      => 'select',
            'title'                     => esc_html__( 'WhatsApp Facebook Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'AddToWishlist',
        ),

        array(
            'id'                    => 'cepatlakoo_wa_rotator_cs',
            'type'                  => 'textarea',
            'title'                 => esc_html__('WhatsApp Customer Service Agent', 'cepatlakoo'),
            'desc'                  => sprintf(esc_html__('List of available customer service. Put each customer service in its own line in the following format: %s, for example: %s.', 'cepatlakoo'), '<code>cs-name | whatsapp-number</code>', '<code>Amanda Zakia | 6281209001291</code>' ),
            'placeholder'            => esc_html__('Melinda | 6281289182192
Amanda Zakia | 6281209001291
Raisya Almeera | 628121002009
            ', 'cepatlakoo'),
            'default'               => 'Melinda | 628123456789
Amanda Zakia | 6285123456789
Raisya | 6283123456789',
        ),
        array(
            'id'                      => 'cepatlakoo_wa_rotator_cookies',
            'type'                    => 'select',
            'title'                   => esc_html__('Cookies on WhatsApp Rotator', 'cepatlakoo'),
            'desc'                    => esc_html__('Enable or disable cookies on WhatsApp rotator when a visitor visits the product / shop page, etc. When enabled the customer service number displayed in all pages will always be the same in the selected period.', 'cepatlakoo'),
            'options'                   => array(
                'none'      => esc_html__('Disable', 'cepatlakoo'),
                '1week'     => esc_html__('1 Week', 'cepatlakoo'),
                '2weeks'     => esc_html__('2 Weeks', 'cepatlakoo'),
                '3weeks'     => esc_html__('3 Weeks', 'cepatlakoo'),
                '1month'    => esc_html__('1 Month', 'cepatlakoo'),
                '3months'    => esc_html__('3 Months', 'cepatlakoo'),
                '6months'    => esc_html__('6 Months', 'cepatlakoo'),
                '1year'     => esc_html__('1 Year', 'cepatlakoo'),
                'lifetime'  => esc_html__('Lifetime', 'cepatlakoo'),
            ),
            'default'  => 'none',
        ),
        array(
            'id'                        => 'cepatlakoo_cart_wa_text',
            'type'                      => 'text',
            'title'                     => esc_html__('WhatsApp Button Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Text on WhatsApp button. Use %lakoo_wa_cs% to display cs name', 'cepatlakoo'),
            'default'                   => esc_html__( 'Beli via WhatsApp' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_wa_message',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'WhatsApp Greeting Message', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your WhatsApp message. Use %lakoo_product_name% to display the product name and %lakoo_product_url% to add product url', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Hai, saya berminat dengan %lakoo_product_name% %lakoo_product_url%' , 'cepatlakoo' ),
        ),
        // ---------------
        array(
            'id'                        => 'cepatlakoo_contact_buttons_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Contact Buttons', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Contact buttons on product page.', 'cepatlakoo'),
            'indent'                    => false,
        ),
        array(
            'id'                      => 'cepatlakoo_contact_button_trigger',
            'type'                    => 'switch',
            'title'                   => esc_html__('Contact Buttons', 'cepatlakoo'),
            'desc'                    => esc_html__('Enable or disable contact buttons.', 'cepatlakoo'),
            'default'                 => true,
        ),
        array(
            'id'                        => 'cepatlakoo_message_above_contact',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Message Above Contact Buttons', 'cepatlakoo'),
            'desc'                      => esc_html__('This message will be displayed on top of the contact buttons.', 'cepatlakoo'),
            'default'                   => esc_html__( 'Untuk pemesanan, silahkan klik tombol di bawah ini:' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_message_popup_heading',
            'type'                      => 'text',
            'title'                     => esc_html__('Contact Button Desktop Heading', 'cepatlakoo'),
            'desc'                      => esc_html__( 'This message will be displayed on popup when clicking contact buttons on desktop.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Cara Membeli' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_message_popup',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Contact Button Desktop Message', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'This message will be displayed on popup when clicking contact buttons on desktop.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Silahkan menghubungi kami via [contact_app] di [contact_id] pada perangkat handphone Anda.' , 'cepatlakoo' ),
        ),
        //-------------------------------
        array(
            'id'                        => 'cepatlakoo_cart_sms',
            'type'                      => 'text',
            'title'                     => esc_html__( 'SMS', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your Phone Number. Leave it blank to hide the button.', 'cepatlakoo'),
            'default'                   => esc_html__( '0812000912' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_sms_text',
            'type'                      => 'text',
            'title'                     => esc_html__( 'SMS Button Text', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Text on the SMS button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Beli via SMS', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_sms_message',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'SMS Message', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your SMS Message display. Use %lakoo_product_name% to display the product name and %lakoo_product_url% to add product url.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Hai, saya berminat dengan %lakoo_product_name% %lakoo_product_url%', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_sms_event',
            'type'                      => 'select',
            'title'                     => esc_html__( 'SMS Facebook Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'AddToWishlist',
        ),
         //----------------------------------
        array(
            'id'                        => 'cepatlakoo_cart_line',
            'type'                      => 'text',
            'title'                     => esc_html__( 'LINE', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your LINE ID. Leave it blank to hide the button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'agnezmos', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_line_text',
            'type'                      => 'text',
            'title'                     => esc_html__( 'LINE Button Text', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Text on LINE button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Chat di LINE', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_line_event',
            'type'                      => 'select',
            'title'                     => esc_html__( 'LINE Facebook Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'AddToWishlist',
        ),
        //----------------------------------
        array(
            'id'                        => 'cepatlakoo_cart_phone',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Phone', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your Phone Number. Leave it blank to hide the button.', 'cepatlakoo' ),
            'default'                   => esc_html__('+628127776622', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_phone_text',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Phone Button Text', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Text on phone button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Telepon Kami', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_phone_event',
            'type'                      => 'select',
            'title'                     => esc_html__( 'Phone Facebook Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'AddToWishlist',
        ),
        //----------------------------------
        array(
            'id'                        => 'cepatlakoo_cart_telegram',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Telegram', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Enter your Telegram ID. Leave it blank to hide the button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'telegram' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_telegram_text',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Telegram Button Text', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Text on Telegram button.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Beli via Telegram' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_telegram_event',
            'type'                      => 'select',
            'title'                     => esc_html__( 'Telegram Facebook Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'AddToWishlist',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Cart', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_shopping_cart_info',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_proceed_to_checkout_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Proceed to Checkout Button Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Default "Proceed to Checkout" text for slide-in cart button and in cart page.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Konfirmasi Pesanan' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_cart_type',
            'type'                      => 'select',
            'title'                     => esc_html__('Cart Type', 'cepatlakoo'),
            'desc'                      => esc_html__('Chooose quick cart display type.', 'cepatlakoo'),
            'options'                   => array(
                                            'sidebar' => esc_html__( 'Sidebar', 'cepatlakoo' ),
                                            'popup' => esc_html__( 'Popup', 'cepatlakoo' )
                                        ),
            'default'                   => 'sidebar',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Checkout', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_shopping_checkout_info',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                      => 'cepatlakoo_checkout_via_whatsapp',
            'type'                    => 'switch',
            'title'                   => esc_html__('Checkout Using WhatsApp', 'cepatlakoo'),
            'subtitle'                => esc_html__('WARNING: This will disable shopping cart functionality in your website and order data IS NOT submitted to database!', 'cepatlakoo'),
            'desc'                    => esc_html__('Activate checkout using WhatsApp. This will open WhatsApp after checkout and send order detail to customer service number defined under Store Settings > Products > WhatsApp Customer Service Agent.', 'cepatlakoo'),
            'default'                 => false,
        ),
        array(
            'id'                      => 'cepatlakoo_checkout_product_image',
            'type'                    => 'switch',
            'title'                   => esc_html__('Product Image on Checkout Page', 'cepatlakoo'),
            'desc'                    => esc_html__('Display or hide product image on checkout page.', 'cepatlakoo'),
            'default'                 => '1',
        ),

        array(
            'id'                        => 'cepatlakoo_checkout_shipping_style',
            'type'                      => 'select',
            'title'                     => esc_html__('Checkout Shipping Style', 'cepatlakoo'),
            'desc'                      => esc_html__('Select shipping method display style on checkout page.', 'cepatlakoo'),
            'options'                   => array(
                                            'radiobutton' => esc_html__( 'Default (Radio Button)', 'cepatlakoo' ),
                                            'select' => esc_html__( 'Dropdown Selectbox', 'cepatlakoo' )
                                        ),
            'default'                 => 'radiobutton',
        ),

        array(
            'id'                      => 'cepatlakoo_coupon_code',
            'type'                    => 'switch',
            'title'                   => esc_html__('Display Coupon Code', 'cepatlakoo'),
            'desc'                    => esc_html__('Turn on or off coupon code in cart & checkout page.', 'cepatlakoo'),
            'default'                 => '1',
        ),
        
        array(
            'id'                        => 'cepatlakoo_place_order_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Place Order Button Text', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Default text for "Place Order" button in checkout page.', 'cepatlakoo' ),
            'default'                   => esc_html__( 'Proses Pesanan' , 'cepatlakoo' ),
        ),
        array(
           'id'                         => 'cepatlakoo_checkout_confirmation',
           'type'                       => 'section',
           'title'                      => esc_html__('Payment Confirmation Buttons', 'cepatlakoo'),
           'subtitle'                   => esc_html__('Payment confirmation buttons settings on order received page (after checkout).', 'cepatlakoo'),
           'indent' => false
        ),

        array(
            'id'                    => 'cepatlakoo_payment_confirmation_buttons',
            'type'                  => 'select',
            'class'                 => 'cl-options-checkout',
            'title'                 => esc_html__('Which Buttons Should be Displayed', 'cepatlakoo'),
            'desc'                  => esc_html__( 'Choose which payment confirmation buttons will be displayed in order-received page.', 'cepatlakoo' ),
            'ajax_save'             => false,
            'options'               => array(
                'none'           => esc_html__( 'None', 'cepatlakoo' ),
                'web-form'       => esc_html__( 'Web Form Button', 'cepatlakoo' ),
                'whatsapp'       => esc_html__( 'WhatsApp Button', 'cepatlakoo' ),
                'all'            => esc_html__( 'All Buttons', 'cepatlakoo' ),
            ),
            'default'               => 'none',
        ),

        array(
            'id'                        => 'cepatlakoo_select_confirmation',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'Select Payment Confirmation Page', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Select payment confirmation page.', 'cepatlakoo' ),
            'data'                      => 'pages',
            'default'                   => '',
            'required'                  => array('cepatlakoo_payment_confirmation_buttons', 'equals', array('all','web-form') ),
        ),

        array(
            'id'                        => 'cepatlakoo_form_confirmation_text',
            'type'                      => 'text',
            'title'                     => esc_html__('Web Form Confirmation Button Text', 'cepatlakoo'),
            'description'               => esc_html__('Text for payment confirmation via web form button.', 'cepatlakoo'),
            'default'                   => esc_html__( 'Konfirmasi Pembayaran via Form' , 'cepatlakoo' ),
            'required'                  => array('cepatlakoo_payment_confirmation_buttons', 'equals', array('all','web-form') ),
        ),

        array(
            'id'                        => 'cepatlakoo_wa_confirmation_text',
            'type'                      => 'text',
            'title'                     => esc_html__('WhatsApp Confirmation Button Text', 'cepatlakoo'),
            'description'               => esc_html__('Text for payment confirmation via WhatsApp button.', 'cepatlakoo'),
            'default'                   => esc_html__( 'Konfirmasi Pembayaran via WA' , 'cepatlakoo' ),
            'required'                  => array('cepatlakoo_payment_confirmation_buttons', 'equals', array('all','whatsapp') ),
        ),

        array(
            'id'                        => 'cepatlakoo_wa_confirmation_number',
            'type'                      => 'text',
            'title'                     => esc_html__('Your WhatsApp Number', 'cepatlakoo'),
            'description'               => esc_html__('Please type your WhatsApp number using country code, for example: 62812900062719. ', 'cepatlakoo'),
            'placeholder'               => esc_html__('For example: 62812900062719', 'cepatlakoo'),
            'default'                   => '',
            'required'                  => array('cepatlakoo_payment_confirmation_buttons', 'equals', array('all','whatsapp') ),
        ),

        array(
            'id'                        => 'cepatlakoo_wa_confirmation_greeting_text',
            'type'                      => 'textarea',
            'title'                     => esc_html__('WhatsApp Greeting Text', 'cepatlakoo'),
            'desc'                      => esc_html__('Write the default greeting text that will sent to admin WhatsApp number. You can use %lakoo_order_id% to display order ID.', 'cepatlakoo'),
            'default'                   => '**[KONFIRMASI PEMBAYARAN]** Saya sudah mentransfer pembayaran untuk kode pesanan: %lakoo_order_id%',
            'validate'                  => 'html',
            'required'                  => array('cepatlakoo_payment_confirmation_buttons', 'equals', array('all','whatsapp') ),
        ),

        array(
           'id'                         => 'cepatlakoo_countdown_order_received_section',
           'type'                       => 'section',
           'title'                      => esc_html__('Countdown on Order Received Page', 'cepatlakoo'),
           'subtitle'                   => esc_html__('Order received page is the page after someone completing checkout.', 'cepatlakoo'),
           'indent' => false
        ),

        array(
            'id'                        => 'cepatlakoo_countdown_timer_order_received',
            'type'                      => 'switch',
            'title'                     => esc_html__('Countdown Timer on Order Received Page', 'cepatlakoo'),
            'default'                   => false,
        ),
        array(
            'id'                        => 'cepatlakoo_countdown_order_received',
            'type'                      => 'select',
            'title'                     => esc_html__( 'Select Countdown Timer', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Select the countdown timer you want to use for order-received page. Leave it empty if you don\'t want to display it.', 'cepatlakoo' ),
            'required'                  => array('cepatlakoo_countdown_timer_order_received', 'equals', '1'),
            'data'                      => 'post',
            'args'                      => array(
                                            'post_type'     => 'cl_countdown_timer'
                                        ),
        ),
        array(
            'id'                        => 'cepatlakoo_countdown_order_received_text',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Text Near the Countdown', 'cepatlakoo'),
            'desc'                      => esc_html__('Write a text that will be displayed near the countdown timer. Support HTML tags.', 'cepatlakoo'),
            'required'                  => array('cepatlakoo_countdown_timer_order_received', 'equals', '1'),
            'default'                   => esc_html__('Segera Lakukan Pembayaran', 'cepatlakoo'),
            'validate'                  => 'html',
        ),
    ),
) );

$follows = array(
    array(
        'id'                        => 'cepatlakoo_followup_info',
        'type'                      => 'info',
        'style'                     => 'success',
        'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
        'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /><strong>%lakoo_products%</strong> : Daftar Produk <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_fullname%</strong> : Nama Lengkap Buyer <br /> </br> <strong>%lakoo_email%</strong> : Email Buyer <br /> <strong>%lakoo_phone_number%</strong> : Nomor Telepon Buyer <br /> <strong>%lakoo_shipping_price%</strong> : Biaya Ongkos Kirim <br /> <strong>%lakoo_subtotal_price%</strong> : Sub Total Pemesanan <br /> <strong>%lakoo_total_price%</strong> : Total Harga Pemesanan', 'cepatlakoo'),
    ),
    array(
        'id'                        => 'cepatlakoo_followup_wa_message',
        'type'                      => 'textarea',
        'title'                     => esc_html__( 'WhatsApp Message', 'cepatlakoo' ),
        'desc'                      => esc_html__( 'Enter your WhatsApp message. You can use cepatlakoo shortcode to display order detail.', 'cepatlakoo' ),
        'default'   => esc_html( 'Halo %lakoo_fullname%,
ID Pesanan Anda: #%lakoo_order_id%

*Detil Pesanan:*
%lakoo_products%

Ongkir: %lakoo_shipping_price%
*Total:* %lakoo_total_price%

Jika belum melakukan pembayaran, segera lakukan pembayaran ke rekening bank kami.

Salam,
%lakoo_shop_name%' ),
    ),
    array(
        'id'                        => 'cepatlakoo_followup_sms_message',
        'type'                      => 'textarea',
        'title'                     => esc_html__( 'SMS Message', 'cepatlakoo' ),
        'desc'                      => esc_html__( 'Enter your SMS message. You can use cepatlakoo shortcode to display order detail.', 'cepatlakoo' ),
        'default'   => esc_html( 'Halo %lakoo_fullname%,
ID Pesanan Anda: #%lakoo_order_id%

Detail Pesanan:
%lakoo_products%

Ongkir: %lakoo_shipping_price%
Total: %lakoo_total_price%

Pesanan Anda sudah kami terima, jika ada kendala dalam pemesanan bisa menghubungi kami via SMS.' ),
    )
);

if ( is_plugin_active( 'cepatlakoo-input-resi/cepatlakoo-input-resi.php' ) ) {
    $follows[0] = array(
        'id'                        => 'cepatlakoo_followup_info',
        'type'                      => 'info',
        'style'                     => 'success',
        'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
        'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /><strong>%lakoo_products%</strong> : Daftar Produk <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_fullname%</strong> : Nama Lengkap Buyer <br /> </br> <strong>%lakoo_email%</strong> : Email Buyer <br /> <strong>%lakoo_phone_number%</strong> : Nomor Telepon Buyer <br /> <strong>%lakoo_shipping_price%</strong> : Biaya Ongkos Kirim <br /> <strong>%lakoo_subtotal_price%</strong> : Sub Total Pemesanan <br /> <strong>%lakoo_total_price%</strong> : Total Harga Pemesanan <br /> <strong>%lakoo_tracking_code%</strong> : Kode Resi <br /> <strong>%lakoo_tracking_date%</strong> : Tanggal Pengiriman <br /> <strong>%lakoo_courier%</strong> : Nama Ekspedisi <br /> <strong>%lakoo_payment_code%</strong> : Kode Pembayaran', 'cepatlakoo'),
    );

    array_push( $follows,
array(
    'id'                        => 'cepatlakoo_followup_wa_message_success',
    'type'                      => 'textarea',
    'title'                     => esc_html__( 'WhatsApp Message Complete', 'cepatlakoo' ),
    'desc'                      => esc_html__( 'Enter your WhatsApp message. You can use cepatlakoo shortcode to display order detail. If you use CL Input Resi plugin, you can also use %lakoo_courier%, %lakoo_tracking_code%, %lakoo_tracking_date%, %lakoo_total_price% .', 'cepatlakoo' ),
    'default'   => esc_html( 'Halo %lakoo_fullname%,
ID Pesanan Anda: #%lakoo_order_id%

*Detil Pesanan:*
%lakoo_products%

Ongkir: %lakoo_shipping_price%
*Total:* %lakoo_total_price%

Pesanan Anda sudah kami kirimkan dengan %lakoo_courier% pada tgl %lakoo_tracking_date% dengan no resi: %lakoo_tracking_code%, jika ada kendala dalam pemesanan bisa menghubungi kami via WA.' ),
),
array(
    'id'                        => 'cepatlakoo_followup_sms_message_success',
    'type'                      => 'textarea',
    'title'                     => esc_html__( 'SMS Message Complete', 'cepatlakoo' ),
    'desc'                      => esc_html__( 'Enter your SMS message. You can use cepatlakoo shortcode to display order detail. If you use CL Input Resi plugin, you can also use %lakoo_courier%, %lakoo_tracking_code%, %lakoo_tracking_date%, %lakoo_total_price% .', 'cepatlakoo' ),
    'default'   => esc_html( 'Halo %lakoo_fullname%,
ID Pesanan Anda: #%lakoo_order_id%

Detail Pesanan:
%lakoo_products%

Ongkir: %lakoo_shipping_price%
Total: %lakoo_total_price%

Pesanan Anda sudah kami kirimkan dengan %lakoo_courier% pada tgl %lakoo_tracking_date% dengan no resi: %lakoo_tracking_code%, jika ada kendala dalam pemesanan bisa menghubungi kami via SMS.' ),
) );
}

Redux::setSection( $opt_name, array(
    'title'            => __( 'Follow Up Buttons', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_shopping_cart_info_followup',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => $follows
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_html__( 'Shipping Label', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_shipping_label_message',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_shipping_label_status',
            'type'                      => 'switch',
            'title'                     => esc_html__('Shipping Label Status', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Enable or disable shipping label.', 'cepatlakoo' ),
            'default'                   => true,
        ),
        array(
            'id'                        => 'cepatlakoo_dropship_shipping_label_status',
            'type'                      => 'switch',
            'title'                     => esc_html__('Support Dropshipping', 'cepatlakoo'),
            'desc'                      => esc_html__( 'When enabled, if the billing and shipping address is different it will display billing as sender and shipping as recepient. When disabled, it will display billing as shipping address and Store Name as sender.', 'cepatlakoo' ),
            'default'                   => false,
        ),
        array(
            'id'                        => 'cepatlakoo_shipping_weight',
            'type'                      => 'switch',
            'title'                     => esc_html__('Show Product Weight', 'cepatlakoo'),
            'desc'                      => esc_html__( 'Display or hide product weight on shipping label.', 'cepatlakoo' ),
            'default'                   => true,
        ),
        array(
            'id'                        => 'cepatlakoo_shop_name',
            'type'                      => 'text',
            'title'                     => esc_html__('Store Name', 'cepatlakoo'),
            'desc'                      => esc_html__('Your store name.', 'cepatlakoo'),
            'default'                   => get_bloginfo('get_bloginfo')
        ),
        array(
            'id'                        => 'cepatlakoo_shop_phone',
            'type'                      => 'text',
            'title'                     => esc_html__('Store Phone', 'cepatlakoo'),
            'desc'                      => esc_html__('Your store phone number.', 'cepatlakoo'),
            'default'                   => ''
        ),
        array(
            'id'                        => 'cepatlakoo_shop_address',
            'type'                      => 'textarea',
            'title'                     => esc_html__('Store Address', 'cepatlakoo'),
            'desc'                      => esc_html__('Your store address.', 'cepatlakoo'),
            'default'                   => $cl_store_address,
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => esc_html__( 'Order Tracking', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_order_tracking_section',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_order_tracking_type',
            'type'                  => 'select',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__('Tracking Type', 'cepatlakoo'),
            'desc'                  => esc_html__( 'Select preferred tracking type. You can display order tracking form using [woocommerce_order_tracking] shortcode.', 'cepatlakoo' ),
            'ajax_save'             => false,
            'options'               => array(
                                        'orderid-email'     => esc_html__('By Order ID & Email (Default)', 'cepatlakoo'),
                                        'orderid-phone'     => esc_html__('By Order ID & Phone Number', 'cepatlakoo'),
            ),
            'default'               => 'orderid-email',
        ),
    ),
) );

$fb_pixel_events_custom = $fb_pixel_events;
$fb_pixel_events_custom = array_merge($fb_pixel_events_custom, ['custom' => 'Custom'] );

// FACEBOOK PIXEL
Redux::setSection( $opt_name, array(
    'title'            => __( 'Facebook Pixel', 'cepatlakoo' ),
    'id'               => 'facebook_pixel',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related to Facebook pixel.', 'cepatlakoo' ),
    'icon'             => 'el el-facebook',
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_pixel_type',
            'type'                      => 'select',
            'multi'                     => false,
            'title'                     =>  esc_html__( 'Meta Pixel Integration Type', 'cepatlakoo' ),
            'options'                   => array(
                                                'convertions_and_meta' => esc_html__( 'Conversions API and Meta Pixel', 'cepatlakoo' ),
                                                'meta_only' => esc_html__( 'Meta Pixel Only', 'cepatlakoo' ),
                                            ),
            'default'                   => 'meta_only',
            'desc'                      => esc_html__('Select pixel integration type', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_facebook_pixel_token',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Facebook Pixel API Token', 'cepatlakoo' ),
            'desc'                      => sprintf( esc_html__( 'Enter Facebook Pixel API token. You can get it from %s.', 'cepatlakoo' ), '<a href="https://www.facebook.com/business/help/952192354843755?helpref=faq_content#createpixel" target="_blank">https://www.facebook.com/business/help/952192354843755?helpref=faq_content#createpixel</a>' ),
            'placeholder'               => esc_html__( 'Example: cd5dd9e63429b3d2ca4795ee01c8fa205f962b2480804646c411a7e37af510d032f771bb387a1820', 'cepatlakoo' ),
            'default'                   => '',
            'required'                  => array( 'cepatlakoo_pixel_type', '=', 'convertions_and_meta' ),
        ),
        array(
            'id'                        => 'cepatlakoo_pixel_api_event_list',
            'type'                      => 'select',
            'multi'                     => true,
            'title'                     =>  esc_html__( 'Pixel Event with Convertion API', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events_custom,
            'default'                   => array('Lead', 'AddToCart', 'AddToWishlist', 'AddPaymentInfo', 'InitiateCheckout', 'Purchase'),
            'desc'                      => esc_html__('Select pixel event with Convertion API.', 'cepatlakoo'),
            'required'                  => array( array('cepatlakoo_facebook_pixel_token', '!=', ''), array('cepatlakoo_pixel_type', '=', 'convertions_and_meta') ),
        ),
        array(
            'id'                        => 'cepatlakoo_facebook_pixel_id',
            'type'                      => 'multi_text',
            'title'                     => esc_html__( 'Facebook Pixel ID', 'cepatlakoo' ),
            'desc'                      => sprintf( wp_kses( __('Enter your Facebook Pixel ID (use comma to apply multiple facebook pixel ID ). you can create your Pixel ID by following this <a href="%s" target="_blank"> article</a>.', 'cepatlakoo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://www.facebook.com/business/help/952192354843755?helpref=faq_content#createpixel' ) ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_facebook_pixel_admin',
            'type'                      => 'switch',
            'title'                     =>  esc_html__( 'Disable Facebook Pixel for admin', 'cepatlakoo' ),
            'default'                   => '0',
            'desc'                      => esc_html__('Disable Facebook Pixel on all pages when login as admin.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_pixel_checkout',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Checkout Page Pixel Event', 'cepatlakoo' ),
            'options'                   => $fb_pixel_checkout_events,
            'default'                   => 'InitiateCheckout',
            'desc'                      => esc_html__('Select pixel event for checkout page. Usually set to InitiateCheckout.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_pixel_order_received',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Onhold (Pending Payment)', 'cepatlakoo' ),
            'options'                   => $fb_pixel_checkout_events,
            'default'                   => 'AddPaymentInfo',
            'desc'                      => esc_html__('Pixel event in order received page when the order status is onhold.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_purchase_confirmation',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Purchase Confirmation', 'cepatlakoo' ),
            'options'                   => $fb_pixel_events,
            'default'                   => 'Purchase',
            'desc'                      => esc_html__('Pixel event in order received page when the order status is processing or complate.', 'cepatlakoo'),
        ),
    ),
) );

$tt_pixel_events = array(
    'AddPaymentInfo' => esc_html( 'AddPaymentInfo' ),
    'AddToCart' => esc_html( 'AddToCart' ),
    'AddToWishlist' => esc_html( 'AddToWishlist' ),
    'ClickButton' => esc_html( 'ClickButton' ),
    'CompletePayment' => esc_html( 'CompletePayment' ),
    'CompleteRegistration' => esc_html( 'CompleteRegistration' ),
    'Contact' => esc_html( 'Contact' ),
    'Download' => esc_html( 'Download' ),
    'InitiateCheckout' => esc_html( 'InitiateCheckout' ),
    'PlaceAnOrder' => esc_html( 'PlaceAnOrder' ),
    'Search' => esc_html( 'Search' ),
    'SubmitForm' => esc_html( 'SubmitForm' ),
    'Subscribe' => esc_html( 'Subscribe' ),
    'ViewContent' => esc_html( 'ViewContent' ),
);

$tt_pixel_checkout_events = array(
    'InitiateCheckout' => esc_html( 'InitiateCheckout' ),
    'AddPaymentInfo' => esc_html( 'AddPaymentInfo' ),
    'PlaceAnOrder' => esc_html( 'PlaceAnOrder' ),
    'CompletePayment' => esc_html( 'CompletePayment' ),
);
// TIKTOK PIXEL
Redux::setSection( $opt_name, array(
    'title'            => __( 'Tiktok Pixel', 'cepatlakoo' ),
    'id'               => 'tiktok_pixel',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related to Tiktok pixel.', 'cepatlakoo' ),
    'icon'             => 'el el-tiktok',
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_tiktok_pixel_id',
            'type'                      => 'multi_text',
            'title'                     => esc_html__( 'Tiktok Pixel ID', 'cepatlakoo' ),
            'desc'                      => sprintf( wp_kses( __('Enter your Tiktok Pixel ID (use comma to apply multiple tiktok pixel ID). you can create your Pixel ID by following this <a href="%s" target="_blank"> article</a>.', 'cepatlakoo'), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://ads.tiktok.com/marketing_api/docs?rid=5ipocbxyw8v&id=1701890973258754' ) ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_tiktok_pixel_admin',
            'type'                      => 'switch',
            'title'                     =>  esc_html__( 'Disable Tiktok Pixel for admin', 'cepatlakoo' ),
            'default'                   => '0',
            'desc'                      => esc_html__('Disable Tiktok Pixel on all pages when login as admin.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_tiktok_pixel_checkout',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Checkout Page Tiktok Pixel Event', 'cepatlakoo' ),
            'options'                   => $tt_pixel_checkout_events,
            'default'                   => 'PlaceAnOrder',
            'desc'                      => esc_html__('Select Tiktok pixel event for checkout page. Usually set to InitiateCheckout.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_tiktok_pixel_order_received',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Onhold (Pending Payment)', 'cepatlakoo' ),
            'options'                   => $tt_pixel_checkout_events,
            'default'                   => 'AddPaymentInfo',
            'desc'                      => esc_html__('Tiktok Pixel event in order received page when the order status is onhold.', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_tiktok_purchase_confirmation',
            'type'                      => 'select',
            'title'                     =>  esc_html__( 'WooCommerce Purchase Confirmation', 'cepatlakoo' ),
            'options'                   => $tt_pixel_events,
            'default'                   => 'CompletePayment',
            'desc'                      => esc_html__('Tiktok Pixel event in order received page when the order status is processing or complate.', 'cepatlakoo'),
        ),
    ),
) );

// SMS NOTIFICATION
Redux::setSection( $opt_name, array(
    'title'            => __( 'Notifications', 'cepatlakoo' ),
    'id'               => 'cl_wa_sms_notifications',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related WooCommerce SMS Notification using smsgateway.me service or WhatsApp.', 'cepatlakoo' ),
    'icon'             => 'el el-comment',
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'WhatsApp Notifications', 'cepatlakoo' ),
    'id'               => 'wa_notification',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_wa_services',
            'type'                      => 'select',
            'title'                     => esc_html__('Select WhatsApp Notification Service', 'cepatlakoo'),
            'desc'                      => esc_html__('Choose the service you want to use.', 'cepatlakoo'),
            'options'                   => array(
                                             'woo-wa' => esc_html__( 'woo-wa.com Server', 'cepatlakoo' ),
                                             'woo-android' => esc_html__( 'Woowandroid from woo-wa.com', 'cepatlakoo' ),
                                             'wanotif' => esc_html__( 'wanotif.id', 'cepatlakoo' ),
                                             'wablas' => esc_html__( 'wablas.com', 'cepatlakoo' ),
                                             'wassenger' => esc_html__( 'wassenger.com', 'cepatlakoo' ),
                                             'ruangwa' => esc_html__( 'ruangwa.id', 'cepatlakoo' ),
                                             'waresponder' => esc_html__( 'waresponder.com', 'cepatlakoo' ),
											 'qontak' => esc_html__('qontak.com', 'cepatlakoo'),
                                          ),
            'default'  => '',
        ),
        array(
            'id'    => 'info_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://wassenger.com/" target="_blank">wassenger.com</a> and have add your WhatsApp number to Wassenger. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>. Don\'t forget to watch <a href="https://cepatlakoo.com/video-tutorial/tutorial-konfigurasi-integrasi-whatsapp-notifications/" target="_blank">our tutorial video</a> to configure WhatsApp Notification.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wassenger'),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_token',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Wassanger API Token', 'cepatlakoo' ),
            'desc'                      => sprintf( esc_html__( 'Enter wassenger.com API token. You can get it from %s.', 'cepatlakoo' ), '<a href="https://console.wassenger.com/apikeys" target="_blank">https://console.wassenger.com/apikeys</a>' ),
            'placeholder'               => esc_html__( 'Example: cd5dd9e63429b3d2ca4795ee01c8fa205f962b2480804646c411a7e37af510d032f771bb387a1820', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wassenger'),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_device',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Wassenger Device ID', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Device ID to send notifications with wassenger.com. You can find your device ID in your Wassenger dashboard. Device ID is not your phone number!', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: 5bcfbc8554bfeb00164db76v', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wassenger'),
        ),

        array(
            'id'    => 'info_waresponder_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="waresponder.com" target="_blank">waresponder.com</a>. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'waresponder'),
        ),
        array(
            'id'                        => 'cepatlakoo_waresponder_device',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Wassenger Device ID', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Device ID to send notifications with waresponder.com. You can find your device ID in your waresponder dashboard. Device ID is not your phone number!', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: 5bcfbc8554bfeb00164db76v', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'waresponder'),
        ),

        array(
            'id'    => 'info_wanotif_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://wanotif.id/" target="_blank">wanotif.id</a>. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wanotif'),
        ),
        array(
            'id'                        => 'cepatlakoo_wanotif_key',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Wanotif API Key', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Type in your wanotif.id API key.', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: bJfDZrR7OAxqzm1kcTPX0my2tdkmXaQv', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wanotif'),
        ),

        array(
            'id'    => 'info_woowa_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://woo-wa.com/" target="_blank">woo-wa.com</a>. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'woo-wa'),
        ),
        array(
            'id'                        => 'cepatlakoo_woowa_key',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Woo-Wa Server Key', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Your woo-wa.com Server key.', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: 5bcfbc8554bfeb00164db76v', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'woo-wa'),
        ),

        array(
            'id'    => 'info_woowandroid_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://woo-wa.com/" target="_blank">woo-wa.com</a>. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'woo-android'),
        ),
        array(
            'id'                        => 'cepatlakoo_woowandroid_cs_id',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Woow Android CS ID', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Your Woow Android CS ID.', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: 3a0fd345-4d5e-4bdf-8d60-e7f80f422a9d', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'woo-android'),
        ),

        array(
            'id'    => 'info_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://wablas.com/" target="_blank">wablas.com</a> and have add your WhatsApp number to Wablas. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>. Don\'t forget to watch <a href="https://cepatlakoo.com/video-tutorial/tutorial-konfigurasi-integrasi-whatsapp-notifications/" target="_blank">our tutorial video</a> to configure WhatsApp Notification.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wablas'),
        ),
        array(
            'id'                        => 'cepatlakoo_wablas_token',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Wablas API Token', 'cepatlakoo' ),
            'desc'                      => sprintf( esc_html__( 'Enter wablas.com API token. You can get it from %s.', 'cepatlakoo' ), '<a href="https://wablas.com" target="_blank">https://wablas.com</a>' ),
            'placeholder'               => esc_html__( 'Example: abcGIBZJQVlIyIsq5wYiAtoq9FGXi8yEOM1dNZs9gFRuECrPKBIWruhsdxBl789L', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wablas'),
        ),
        array(
            'id'                        => 'cepatlakoo_wablas_server',
            'type'                      => 'text',
            'title'                     => esc_html__('Wablas Subdomain', 'cepatlakoo'),
            'desc'                      => __('Type in the server subdomain name in which your Wablas account is located. Wablas have lots of servers, make sure to fill it correctly. If your account is under https://console.wablas.com, simply put <code>console</code> for subdomain name.', 'cepatlakoo'),
            'default'  => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'wablas'),
        ),

        array(
            'id'    => 'info_ruangwa_warning',
            'type'  => 'info',
            'title' => __('NOTICE', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Make sure you already have an account on <a href="https://ruangwa.id/" target="_blank">ruangwa.id</a>. Also make sure to create a cron job on your server to make the follow up process runs automatically, <a href="https://cepatlakoo.com/knowledgebase/tutorial-membuat-cron-job/" target="_blank">refer to this tutorial</a>.', 'cepatlakoo'),
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'ruangwa'),
        ),
        array(
            'id'                        => 'cepatlakoo_ruangwa_token',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Ruangwa Token', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Type in your ruangwa.id Token.', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: bJfDZrR7OAxqzm1kcTPX0my2tdkmXaQv', 'cepatlakoo' ),
            'default'                   => '',
            'required' => array( 'cepatlakoo_wa_services', 'equals', 'ruangwa'),
        ),

        array(
            'id'                        => 'cepatlakoo_wanotification_admin',
            'type'                      => 'section',
            'title'                     => esc_html__('Notification for Admin', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Set notification message for admin', 'cepatlakoo'),
            'indent'                    => true,
        ),

        array(
            'id'                        => 'cepatlakoo_wa_new_order_admin_info',
            'type'                      => 'info',
            'style'                     => 'success',
            'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
            'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_products%</strong> : Daftar Produk yang dipesan', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_admin_phone',
            'type'                      => 'text',
            'title'                     => esc_html__( 'Admin Notification Number', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'The WhatsApp phone number to received new order notification. Put "+" and country code in front of your number. For Example: +628129004790', 'cepatlakoo' ),
            'placeholder'               => esc_html__( 'Example: +628129004790', 'cepatlakoo' ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_wa_new_order_admin',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'New Order Alert ', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Ada orderan baru dengan nomor *#%lakoo_order_id%* di %lakoo_shop_name%.

*Detil Pesanan:*
%lakoo_products%

Segera proses.' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wanotification_customer',
            'type'                      => 'section',
            'title'                     => esc_html__( 'WhatsApp Notification for Customer', 'cepatlakoo' ),
            'subtitle'                  => esc_html__('Set notification message for your customer', 'cepatlakoo'),
            'indent'                    => true,
        ),
        array(
            'id'                        => 'cepatlakoo_wanotification_customer_info',
            'type'                      => 'info',
            'style'                     => 'success',
            'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
            'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_fullname%</strong> : Nama Lengkap Buyer <br /> </br> <strong>%lakoo_email%</strong> : Email Buyer <br /> <strong>%lakoo_phone_number%</strong> : Nomor Telepon Buyer <br /> <strong>%lakoo_shipping_price%</strong> : Biaya Ongkos Kirim <br /> <strong>%lakoo_subtotal_price%</strong> : Sub Total Pemesanan <br /> <strong>%lakoo_total_price%</strong> : Total Harga Pemesanan <br /> <strong>%lakoo_confirmation_page%</strong> : Link Konfirmasi Pembayaran <br /> <strong>%lakoo_tracking_code%</strong> : Kode Resi <br /> <strong>%lakoo_tracking_date%</strong> : Tanggal Pengiriman <br /> <strong>%lakoo_courier%</strong> : Nama Ekspedisi <br /> <strong>%lakoo_payment_code%</strong> : Kode Pembayaran', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_new_order',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'New Order Alert', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
ID Pesanan Anda: #%lakoo_order_id%

*Detail Pesanan:*
%lakoo_products%

*Ongkir:* %lakoo_shipping_price%
*Total:* %lakoo_total_price%

Pesanan Anda sudah kami terima, jika belum melakukan pembayaran segera lakukan pembayaran ke rekening bank kami.

Salam,
%lakoo_shop_name%' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_process',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Being Processed', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* akan segera kami proses. Terimakasih sudah berbelanja di %lakoo_shop_name%.' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_complete',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Completed', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* sudah dikirim.

Kurir: %lakoo_courier%
Tanggal: %lakoo_tracking_date%
Nomor Resi: %lakoo_tracking_code%

Salam,
%lakoo_shop_name%' ),

        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_pending',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Pending Payment', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* statusnya *PENDING*. Segera lakukan pembayaran untuk pesanan berikut:

*Detil Pesanan:*
%lakoo_products%

*Ongkir:* %lakoo_shipping_price%
*Total:* %lakoo_total_price%' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_pending_h1',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Pending Payment After H+1', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Kami masih menunggu pembayaran untuk ID pesanan *#%lakoo_order_id%*

*Detil Pesanan:*
%lakoo_products%

Ongkir: %lakoo_shipping_price%
Total: %lakoo_total_price%

Segera lakukan pembayaran agar dapat kami bisa segera proses pesanan Anda.

Salam,
%lakoo_shop_name%' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_pending_h2',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Pending Payment After H+2', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Apakah ada kendala sehingga Anda belum melakukan pembayaran untuk ID pesanan *#%lakoo_order_id%*? Jika ada bisa ditanyakan ke kami kok.' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_failed',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Failed', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* statusnya *GAGAL*

Silahkan hubungi kami untuk keterangan lebih lanjut.

Salam,
%lakoo_shop_name%' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_refunded',
            'type'                      => 'textarea',
            'title'                     =>  esc_html__( 'Order Refunded', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* statusnya diubah ke *DANA DIKEMBALIKAN*.

Silahkan hubungi kami untuk keterangan lebih lanjut.

Salam,
%lakoo_shop_name%' ),

        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_cancel',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Cancelled', 'cepatlakoo' ),
            'default'                   => esc_attr( 'Halo %lakoo_fullname%,
Pesanan Anda *#%lakoo_order_id%* statusnya diubah ke *DIBATALKAN*.

Silahkan hubungi kami untuk keterangan lebih lanjut.

Salam,
%lakoo_shop_name%' ),
        ),
        array(
            'id'                        => 'cepatlakoo_wa_order_note',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'When a Note for Customer Added to Order', 'cepatlakoo' ),
            'default'                   =>  esc_attr( 'Halo %lakoo_fullname%,

Ada catatan ditambahkan pada order *#%lakoo_order_id%*:

-----

%lakoo_note%

-----

Salam,
%lakoo_shop_name%' ),
            'desc'                      => esc_html__( 'You can use this code to display the note message: %lakoo_note%', 'cepatlakoo' ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'SMS Notifications', 'cepatlakoo' ),
    'id'               => 'sms_notification',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'    => 'info_warning_sms',
            'type'  => 'info',
            'title' => __('PERHATIAN', 'cepatlakoo'),
            'style' => 'warning',
            'desc'  => __('Pastikan Anda sudah memiliki akun <a href="https://smsgateway.me" target="_blank">smsgateway.me</a> dan sudah menginstal aplikasi <a href="https://play.google.com/store/apps/details?id=networked.solutions.sms.gateway.api&hl=en" target="_blank">SMS Gateway API</a> di handphone Android Anda.', 'cepatlakoo')
        ),
        array(
            'id'                        => 'cepatlakoo_sms_gateway_token',
            'type'                      => 'text',
            'title'                     => esc_html__( 'SMS Gateway API Token', 'cepatlakoo' ),
            'desc'                      => sprintf( esc_html__( 'Enter smsgateway.me website API token. You can get it from %s .', 'cepatlakoo' ), '<a href="https://smsgateway.me/dashboard/settings" target="_blank">https://smsgateway.me/dashboard/settings</a>' ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_sms_gateway_deviceID',
            'type'                      => 'text',
            'title'                     => esc_html__( 'SMS Gateway DeviceID', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Your device ID registered to smsgateway.me, for example: 526345.', 'cepatlakoo' ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_sms_gateway_phone',
            'type'                      => 'text',
            'title'                     => esc_html__( 'SMS Gateway Phone Number', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'Your phone number which you have install smsgateway.me Android app. For example: 085613242215.', 'cepatlakoo' ),
            'default'                   => '',
        ),
        array(
            'id'                        => 'cepatlakoo_smsnotification_admin',
            'type'                      => 'section',
            'title'                     => esc_html__('SMS Notification for Admin', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Set notification message for admin', 'cepatlakoo'),
            'indent'                    => true,
        ),
        array(
            'id'                        => 'cepatlakoo_sms_new_order_admin_info',
            'type'                      => 'info',
            'style'                     => 'success',
            'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
            'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_new_order_admin',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'New Order Alert ', 'cepatlakoo' ),
            'default'                   => esc_attr__( 'Ada orderan baru dengan nomor #%lakoo_order_id% di %lakoo_shop_name%. Segera proses.' , 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_smsnotification_customer',
            'type'                      => 'section',
            'title'                     => esc_html__( 'SMS Notification for Customer', 'cepatlakoo' ),
            'subtitle'                  => esc_html__('Set notification message for your customer', 'cepatlakoo'),
            'indent'                    => true,
        ),
        array(
            'id'                        => 'cepatlakoo_smsnotification_customer_info',
            'type'                      => 'info',
            'style'                     => 'success',
            'title'                     => esc_html__('Gunakan Shortcode Ini Untuk Merangkai Pesan', 'cepatlakoo'),
            'desc'                      => __('<br /> <strong>%lakoo_order_id%</strong> : Nomor Pemesanan <br /> <strong>%lakoo_order_status%</strong> : Status Pemesanan <br /> <strong>%lakoo_shop_name%</strong> : Nama Toko <br /> <strong>%lakoo_fullname%</strong> : Nama Lengkap Buyer <br /> </br> <strong>%lakoo_email%</strong> : Email Buyer <br /> <strong>%lakoo_phone_number%</strong> : Nomor Telepon Buyer <br /> <strong>%lakoo_shipping_price%</strong> : Biaya Ongkos Kirim <br /> <strong>%lakoo_subtotal_price%</strong> : Sub Total Pemesanan <br /> <strong>%lakoo_total_price%</strong> : Total Harga Pemesanan <br /> <strong>%lakoo_tracking_code%</strong> : Kode Resi <br /> <strong>%lakoo_tracking_date%</strong> : Tanggal Pengiriman <br /> <strong>%lakoo_courier%</strong> : Nama Ekspedisi <br /> <strong>%lakoo_payment_code%</strong> : Kode Pembayaran', 'cepatlakoo'),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_new_order',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'New Order Alert', 'cepatlakoo' ),
            'default'                   => esc_attr__( 'Order Anda #%lakoo_order_id% sudah kami terima mohon segera melakukan pembayaran. Terimakasih sudah berbelanja di %lakoo_shop_name%.', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_process',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Being Processed', 'cepatlakoo' ),
            'default'                   => esc_attr__( 'Order Anda #%lakoo_order_id% sudah kami terima dan akan segera kami proses. Terimakasih sudah berbelanja di %lakoo_shop_name%.', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_complete',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Completed', 'cepatlakoo' ),
            'default'                   => esc_attr__( '%lakoo_shop_name% - Status order #%lakoo_order_id% diubah menjadi: Sudah Dikirim dengan %lakoo_courier%, tgl: %lakoo_tracking_date%, nomor resi: %lakoo_tracking_code%', 'cepatlakoo' ),

        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_pending',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order is Pending Payment', 'cepatlakoo' ),
            'default'                   => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Pending Payment.', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_failed',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Failed', 'cepatlakoo' ),
            'default'                   => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Gagal.','cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_refunded',
            'type'                      => 'textarea',
            'title'                     =>  esc_html__( 'Order Refunded', 'cepatlakoo' ),
            'default'                   => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Refund Uang.', 'cepatlakoo' ),

        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_cancel',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'Order Cancelled', 'cepatlakoo' ),
            'default'                   => esc_attr__( '%lakoo_shop_name% - Order Anda no. #%lakoo_order_id% status: Dibatalkan.', 'cepatlakoo' ),
        ),
        array(
            'id'                        => 'cepatlakoo_sms_order_note',
            'type'                      => 'textarea',
            'title'                     => esc_html__( 'When a Note for Customer Added to Order', 'cepatlakoo' ),
            'default'                   =>  esc_attr__( '%lakoo_shop_name% - Catatan ditambahkan pada order #%lakoo_order_id%: %lakoo_note%', 'cepatlakoo' ),
            'desc'                      => esc_html__( 'You can use this code to display the note message: %lakoo_note%' ),
        ),
    ),
) );

// TYPOGRAPHY
Redux::setSection( $opt_name, array(
    'title'            => __( 'Typography', 'cepatlakoo' ),
    'id'               => 'typography',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related typograhy.', 'cepatlakoo' ),
    'icon'             => 'el el-text-width',
    'fields'           => array(

        array(
            'id'                => 'cepatlakoo_main_font_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'Main Typography', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('body'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '16px',
                'font-weight'       => '400',
                'line-height'       => '28px',
                'color'             => '#555555',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Top Bar', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_topbar',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                => 'cepatlakoo_topbar_font',
            'type'              => 'typography',
            'title'             => esc_html__( 'Top Bar Typography', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('#top-bar, #top-bar .flash-info, #top-bar .user-options, #top-bar .user-account-menu .avatar > label'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '24px',
                'color'             => '#555555',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Header', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_header',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                => 'cepatlakoo_site_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__(  'Site Title', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('#main-header h2 a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '30px',
                'font-weight'       => '400',
                'line-height'       => '35px',
                'color'             => '#ffffff',
            )
        ),


        array(
            'id'                => 'cepatlakoo_menu_text_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Menu Text', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'text-transform'    => true,
            'text-align'        => false,
            'preview'           => true,
            'output'            => array('.site-navigation > ul li a, .site-navigation > ul li a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '15px',
                'font-weight'       => '700',
                'line-height'       => '18px',
                'color'             => '#ffffff',
                'text-transform'    => 'none',
            )
        ),

        array(
            'id'                => 'cepatlakoo_submenu_text_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Sub Menu Text', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'text-align'        => false,
            'subsets'           => false,
            'output'            => array('.site-navigation ul.sub-menu li a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '18px',
                'color'             => '#000000',
                'text-transform'     => 'none',
            )
        ),

        array(
            'id'                => 'cepatlakoo_submenu_2nd_text_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Sub Menu 2nd Text', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.site-navigation ul.sub-menu li ul.sub-menu li a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '12px',
                'font-weight'       => '400',
                'line-height'       => '18px',
                'color'             => '#000000',
                'text-transform'     => 'none',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Heading', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_heading',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                => 'cepatlakoo_heading_h1_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H1', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h1, article.hentry h1'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '35px',
                'font-weight'       => '700',
                'line-height'       => '40px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_heading_h2_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H2', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h2, article.hentry h2'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '30px',
                'font-weight'       => '700',
                'line-height'       => '35px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_heading_h3_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H3', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h3, article.hentry h3'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '20px',
                'font-weight'       => '700',
                'line-height'       => '25px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_heading_h4_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H4', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h4, article.hentry h4'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '16px',
                'font-weight'       => '400',
                'line-height'       => '25px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_heading_h5_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H5', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h5, article.hentry h5'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '12px',
                'font-weight'       => '400',
                'line-height'       => '20px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_heading_h6_typo',
            'type'              => 'typography',
            'title'             => esc_html__( 'H6', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('h6, article.hentry h6'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '10px',
                'font-weight'       => '400',
                'line-height'       => '16px',
                'color'             => '#333333',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Sidebar', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_sidebar',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                => 'cepatlakoo_sidebar_widget_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Sidebar Widget Title', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'subsets'           => false,
            'text-align'        => false,
            'output'            => array('#sidebar .widget h4.widget-title'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '700',
                'line-height'       => '24px',
                'color'             => '#333333',
                'text-transform'    => 'uppercase',
                'letter-spacing'    => '1px',
            )
        ),

        array(
            'id'                => 'cepatlakoo_sidebar_widget_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Sidebar Widget Text', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'subsets'           => false,
            'text-align'        => false,
            'output'            => array('#sidebar .widget, #sidebar table, #sidebar thead, #sidebar tbody, #sidebar .cite-block, #sidebar cite, #sidebar del .woocommerce-Price-amount.amount, #sidebar del .woocommerce-Price-amount.amount span, #sidebar .widget_categories ul li.cat-item, #sidebar .widget_categories ul li, #sidebar .widget ul li, #sidebar .widget ol li, #sidebar .widget p'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '16px',
                'font-weight'       => '400',
                'line-height'       => '24px',
                'color'             => '#555555',
                'text-transform'     => 'none',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Footer', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_footer',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                        => 'cepatlakoo_footer_menu_typography_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Menu', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Typography options for footer menu area.', 'cepatlakoo'),
            'indent'                    => false,
        ),
        
        array(
            'id'                => 'cepatlakoo_footer_menu_text_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Footer Menu Text', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'text-transform'    => true,
            'text-align'        => false,
            'preview'           => true,
            'output'            => array('#footer-menu-area .site-navigation > ul li a, #footer-menu-area .site-navigation > ul li a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '15px',
                'font-weight'       => '700',
                'line-height'       => '18px',
                'color'             => '#ffffff',
                'text-transform'    => 'none',
            )
        ),

        array(
            'id'                        => 'cepatlakoo_footer_widget_typography_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Widgets', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Typography options for footer widgets area.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                => 'cepatlakoo_footer_widget_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Footer Widget Title', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'subsets'           => false,
            'text-align'        => false,
            'output'            => array('footer#colofon .footer-widget h4.widget-title, #colofon .wp-block-group__inner-container h2'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '18px',
                'font-weight'       => '400',
                'line-height'       => '24px',
                'color'             => '#333333',
                'text-transform'     => 'none',
            )
        ),

        array(
            'id'                => 'cepatlakoo_footer_widget_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Footer Widget Text', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'subsets'           => false,
            'text-align'        => false,
            'output'            => array('#footer-widgets-area table, #footer-widgets-area thead, #footer-widgets-area tbody, #footer-widgets-area .cite-block, #footer-widgets-area cite, #footer-widgets-area del .woocommerce-Price-amount.amount, #footer-widgets-area del .woocommerce-Price-amount.amount span, #footer-widgets-area .widget_categories ul li.cat-item, #footer-widgets-area .widget_categories ul li, #footer-widgets-area .footer-widgets, .footer-widgets.row.column-4, .footer-widget ul li , .footer-widget ol li, .footer-widget p, #footer-widgets-area .footer-widgets ul.product_list_widget li>a'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '18px',
                'color'             => '#333333',
                'text-transform'     => 'none',
            )
        ),

        array(
            'id'                        => 'cepatlakoo_footer_copyright_typography_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Copyright', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Typography options for footer copyright area.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                => 'cepatlakoo_footer_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Footer Copyright', 'cepatlakoo' ),
            'google'            => true,
            'text-transform'     => true,
            'preview'           => true,
            'subsets'           => false,
            'text-align'        => false,
            'output'            => array('#footer-info'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '18px',
                'color'             => '#bababa',
                'text-transform'     => 'none',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_blog',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                => 'cepatlakoo_page_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Page Title', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('#page-title h2, #page-title h1'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '35px',
                'font-weight'       => '700',
                'line-height'       => '40px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_post_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Post Title', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('article h1.post-title, .postlist article.hentry h3.post-title'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '35px',
                'font-weight'       => '700',
                'line-height'       => '40px',
                'color'             => '#333333',
            )
        ),

        array(
            'id'                => 'cepatlakoo_paragraf_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Paragraph Text', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('article.hentry p'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '16px',
                'font-weight'       => '400',
                'line-height'       => '28px',
                'color'             => '#555555',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'WooCommerce', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_typography_woocommerce',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        
        array(
            'id'                        => 'cepatlakoo_woo_product_detail_typography_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Product Detail Page', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Typography options for product product detail page.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                => 'cepatlakoo_woo_product_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Title', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce .summary h1.product_title'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '35px',
                'font-weight'       => '700',
                'line-height'       => '40px',
                'color'             => '#000000',
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_paragraf_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Paragraph Text', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce .summary p:not(a)', '.woocommerce .woocommerce-tabs p', '.quick-contact-info p.contact-message, .reveal-overlay p, .woocommerce-product-details__short-description, .woocommerce .entry-content.woocommerce-Tabs-panel'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '16px',
                'font-weight'       => '400',
                'line-height'       => '24px',
                'color'             => '#555555',
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_product_detail_price_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Price', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce div.product p.price, .woocommerce div.product span.price'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '24px',
                'font-weight'       => '400',
                'line-height'       => '32px',
                'color'             => '#19793e',
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_product_detail_sale_price_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Sale (Discounted) Price', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce div.product p.price ins, .woocommerce div.product span.price ins'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '24px',
                'font-weight'       => '400',
                'line-height'       => '32px',
                'color'             => '#7e7e7e',
            )
        ),

        array(
            'id'                        => 'cepatlakoo_woo_product_loop_typography_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Product Loop', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Typography options for product loop area (product grid in Shop page, related product and homepage).', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                => 'cepatlakoo_woo_loop_product_title_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Title in Product Loop', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce ul.products li.product .woocommerce-loop-category__title, .woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '18px',
                'font-weight'       => '700',
                'line-height'       => '24px',
                'color'             => '#372248'
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_loop_product_price_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Price', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce ul.products li.product .price, .woocommerce .related.products ul.products li.product p.total .price, .woocommerce .related.products ul.products li.product .price'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '20px',
                'color'             => '#19793e',
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_loop_product_sale_price_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Sale (Discounted) Price', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce ul.products li.product .price ins, .woocommerce .related.products ul.products li.product p.total .price ins, .woocommerce .related.products ul.products li.product .price ins'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '20px',
                'color'             => '#7e7e7e',
            )
        ),

        array(
            'id'                => 'cepatlakoo_woo_loop_product_stock_typography',
            'type'              => 'typography',
            'title'             => esc_html__( 'Product Stocks', 'cepatlakoo' ),
            'google'            => true,
            'subsets'           => false,
            'preview'           => true,
            'text-align'        => false,
            'output'            => array('.woocommerce ul.products li.product span.cl-item-stock'),
            'default'           => array(
                'font-family'       => 'Source Sans Pro',
                'font-size'         => '14px',
                'font-weight'       => '400',
                'line-height'       => '20px',
                'color'             => '#555555',
            )
        ),

        array(
            'id'                        => 'cepatlakoo_woo_loop_product_stock_icon_size',
            'type'                      => 'dimensions',
            'units'                     => 'px',
            'title'                     => esc_html__('Stock Icon Size', 'cepatlakoo'),
            'output'                    => array('.woocommerce ul.products li.product span.cl-item-stock:before'),
            'default'                   => array(
                                            'width'   => '12', 
                                            'height'  => '12'
                                        ),
        ),
    ),
) );

// COLORS
Redux::setSection( $opt_name, array(
    'title'            => __( 'Colors', 'cepatlakoo' ),
    'id'               => 'colors',
    'customizer_width' => '450px',
    'desc'             => __( 'Configure settings related to colors.', 'cepatlakoo' ),
    'icon'             => 'el el-brush',
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_color_schemes',
            'type'                  => 'select',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__('Color Scheme', 'cepatlakoo'),
            'desc'                  => esc_html__( 'Set color by predefined color schemes.', 'cepatlakoo' ),
            'ajax_save'             => false,
            'options'               => array(
                                        'default'       => 'Default',
                                        'purple'        => 'Purple',
                                        'purewhite'     => 'Pure White',
                                        'lightgreen'    => 'Light Green',
                                        'sunset'        => 'Sunset',
                                        'strongblue'    => 'Strong Blue',
                                        'earthy'        => 'Earthy',
                                        'romantic'      => 'Romantic',
                                        'darkcloud'     => 'Dark Cloud',
                                        'cyan'          => 'Cyan',
                                        'custom'        => 'Custom',
            ),
            'default'               => 'default',
        ),
        array(
            'id'                    => 'cepatlakoo_general_theme_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__('General Theme Color', 'cepatlakoo'),
            'desc'                  => esc_html__( 'Color used for border and link color.', 'cepatlakoo' ),
            'transparent'           => false,
            'output'                => array('#sidebar h4.widget-title:after, .woocommerce .product_meta span.posted_in:before, .summary .product_meta span.posted_in:before, .widget li.recentcomments span, .wp-pagenavi span.current, .post-navigation ul li .detail > a,.products-pagination span.current, .woocommerce .products li .custom-shop-buttons a:nth-child(2), .widget ul.product_list_widget li .quantity span, .woocommerce-MyAccount-navigation ul li.is-active a, .product_meta span.posted_in:before, .woocommerce .product_meta span.tagged_as:before, .woocommerce div.product .out-of-stock, #sidebar .entry-meta span, fieldset legend, ul.product-categories li a:before, .price_slider_amount button, .ct-nav.active, .ui.steps .step.current .title, .ui.steps .step.completed .title, .woocommerce-pagination span:hover, .woocommerce-pagination span.current, ul.product-categories li:hover > a:before'),
            'default'               => '#372248',
        ),
        array(
            'id'                    => 'cepatlakoo_link_theme_color',
            'type'                  => 'link_color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'General Link Color', 'cepatlakoo' ),
            'active'                 => false,
            'output'                => array('a, .entry-content a, .entry-summary a, #top-bar a'),
            'default'               => array(
                                        'regular'  => '#1e73be',
                                        'hover'  => '#318e1c',
                                    ),
        ),
        array(
            'id'                    => 'cepatlakoo_link_icon_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Default icon color', 'cepatlakoo' ),
            'default'               => '#372248',
            'transparent'           => false,
            'output'                => array(
                                        'fill' => '.author-box .socials ul li a:hover svg path, .social-sharing ul li a:hover svg path, .sidebar-trigger svg path, .woocommerce .product-carousel ul.products.owl-carousel .owl-nav .owl-next:hover svg path, .woocommerce .product-carousel ul.products.owl-carousel .owl-nav .owl-prev:hover svg path'
                                    ),
            'validate'              => 'color',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Top Bar', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_topbar',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_topbar_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Top Bar Background Color', 'cepatlakoo' ),
            'output'                => array('#top-bar, #mobile-menu .user-account-menu'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#cedae1',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_user_menu_bg',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'User menu background', 'cepatlakoo' ),
            'output'                => array('#masthead #top-bar ul.user-menu-menu, #mobile-menu .user-account-menu'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#cedae1',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_user_menu_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'User menu link color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('#top-bar ul.user-menu-menu li, #top-bar ul.user-menu-menu li a, #mobile-menu ul.user-menu-menu li a, #mobile-menu .user-account-menu:before'),
            'default'               => array(
                                        'regular'  => '#5c677d',
                                        'hover'  => '#5c677d',
            ),
        ),
        array(
            'id'                    => 'cepatlakoo_topbar_text_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Top Bar Text Color', 'cepatlakoo' ),
            'transparent'           => false,
            'output'                => array('#top-bar, #top-bar .user-account-menu .avatar:after, #top-bar label, #top-bar .socials li, .customer-care, #top-bar .flash-info, #top-bar a, .customer-care b', '.user-options i'),
            'default'               => '#5c677d',
        ),
        array(
            'id'                    => 'cepatlakoo_topbar_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Top Bar Link Color', 'cepatlakoo' ),
            'active'                => false,
            'important'             => true,
            'output'                => array(', #top-bar .socials li a, .customer-care a, .flash-info a, #top-bar .cart-counter, #top-bar .avatar:after, .customer-care i, .user-account-menu'),
            'default'               => array(
                                        'regular'  => '#5c677d',
                                        'hover'  => '#928da8',
            ),
        ),
        array(
            'id'                    => 'cepatlakoo_topbar_icon_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Top bar icons color', 'cepatlakoo' ),
            'default'               => '#5c677d',
            'transparent'           => false,
            'output'                => array(
                                        'fill'  => '.socials ul li a svg path, .user-account-menu svg path, .customer-care svg path, .search-trigger svg path'
            ),
            'validate'              => 'color',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Header', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_header',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_header_border',
            'type'                  => 'border',
            'title'                 => esc_html__( 'Header Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array('#main-header'),
            'default'  => array(
                'border-color'  => '#8693a3',
                'border-style'  => 'solid',
                'border-top'    => '1px',
                'border-right'  => '0px',
                'border-bottom' => '0px',
                'border-left'   => '0px'
            )
        ),
        array(
            'id'                    => 'cepatlakoo_header_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Main header Background Color', 'cepatlakoo' ),
            'output'                => array('#main-header, #colofon .widget .tagcloud a:hover, #colofon .tagcloud a:hover'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#95A3B3',
            )
        ),
        array(
            'id'                    => 'cepatlakoo_header_text_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Header Text Color', 'cepatlakoo' ),
            'default'               => '#ffffff',
            'output'                => array(
                'color' => '#main-header, #main-header .site-title a, .user-account-menu .avatar label,  #main-header .mobile-menu-trigger label',
                'fill' => '#main-header .mobile-menu-trigger svg path',
            ),
        ),
        array(
            'id'                    => 'cepatlakoo_main_menu_hover_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Main Menu Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array(
                                        'regular' => '.site-navigation ul.main-menu li a',
                                        'hover' => '.site-navigation ul.main-menu li.current-menu-item > a, .site-navigation ul.main-menu li.current_page_item > a',
            ),
            'default'               => array(
                                        'regular'  => '#ffffff',
                                        'hover'  => '#ffe066',
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_sub_menu_hover_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Sub Menu Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('.site-navigation ul li > ul.sub-menu li a'),
            'default'               => array(
                                        'regular'  => '#555555',
                                        'hover'  => '#ffffff',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_sub_menu_bg_hover_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Sub Menu Hover Background Color', 'cepatlakoo' ),
            'output'                => array('.site-navigation ul li > ul.sub-menu li a:hover'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#95a3b3',
            )
        ),

        // array(
        //     'id'                        => 'cepatlakoo_header_cart_icon',
        //     'type'                      => 'section',
        //     'title'                     => esc_html__('Mobile Menu', 'cepatlakoo'),
        //     'subtitle'                  => esc_html__('Mobile menu color setting.', 'cepatlakoo'),
        //     'indent'                    => false,
        // ),

        // array(
        //     'id'                    => 'cepatlakoo_mobile_menu_icon_color',
        //     'type'                  => 'color',
        //     'class'                 => 'cl-options-colors',
        //     'title'                 => esc_html__( 'Icon Color', 'cepatlakoo' ),
        //     'transparent'           => false,
        //     'default'               => '#40618d',
        //     'output'                => array('fill' => '.mobile-menu-trigger svg path', 'color' => '.mobile-menu-trigger label'),
        // ),

        array(
            'id'                        => 'cepatlakoo_header_cart_icon',
            'type'                      => 'section',
            'title'                     => esc_html__('Header Cart Icon', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Header cart icon color setting.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_cart_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Shopping Cart Empty Color', 'cepatlakoo' ),
            'transparent'           => false,
            'default'               => '#ffffff',
            'output'                => array('fill' => '.user-carts .cart-counter svg path'),
        ),

        array(
            'id'                    => 'cepatlakoo_cart_not_empty_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Shopping Cart Not Empty Color', 'cepatlakoo' ),
            'default'               => '#ffd23f',
            'output'                => array(
                'fill' => '.user-carts .cart-counter svg.not-empty path'
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_cart_count_badge_bg',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Shopping Cart Badge Background', 'cepatlakoo' ),
            'default'               => '#8cb369',
            'output'                => array(
                'color'             => '.testimony-item span',
                'background-color' => '.testimony-item p:after, .testimony-item .thumbnail:after, #main-header .cart-counter label'
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_cart_count_badge_text_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Shopping Cart Badge Text Color', 'cepatlakoo' ),
            'default'               => '#ffffff',
            'output'                => array(
                'color' => '#main-header .cart-counter label'
            ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Footer', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_footer',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                        => 'cepatlakoo_footer_menu_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Menu', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Color options for footer menu area.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_footer_menu_background_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Footer Menu Background Color', 'cepatlakoo' ),
            'output'                => array('footer#colofon #footer-menu-area'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#f9f9f9',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_footer_hover_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Footer Link Color', 'cepatlakoo' ),
            'output'                => array('#colofon a, #colofon .site-navigation ul li a, footer#colofon #footer-info a'),
            'active'                => false,
            'default'               => array(
                                        'regular'  => '#777777',
                                        'hover'    => '#666666',
            ),
        ),

        array(
            'id'                        => 'cepatlakoo_footer_widgets_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Widgets', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Color options for footer widgets area.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_footer_widget_background_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Footer Widget Background Color', 'cepatlakoo' ),
            'output'                => array('footer#colofon #footer-widgets-area'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#f0f0f3',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_widget_footer_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Widget Footer Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('footer#colofon #footer-widgets-area a, footer#colofon .footer-widget a,#colofon .entry-meta span'),
            'default'               => array(
                                        'regular'  => '#777777',
                                        'hover'    => '#666666',
                                    ),
        ),

        array(
            'id'                    => 'cepatlakoo_footer_widget_title_border',
            'type'                  => 'color',
            'title'                 => esc_html__( 'Sidebar Widget Title Border', 'cepatlakoo' ),
            'output'                => array(
                                        'background-color' => '#footer-widgets-area .footer-widget h4.widget-title:after'
                                    ),
            'default'               => '#cccccc',
        ),

        array(
            'id'                        => 'cepatlakoo_footer_copyright_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Footer Copyright', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Color options for footer copyright area.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_footer_background_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Footer Copyright Background Color', 'cepatlakoo' ),
            'output'                => array('footer#colofon #footer-info'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#ffffff',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_footer_border',
            'type'                  => 'border',
            'title'                 => esc_html__( 'Footer Copyright Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array('footer#colofon #footer-info'),
            'default'  => array(
                'border-color'  => '#dddddd',
                'border-style'  => 'solid',
                'border-top'    => '1px',
                'border-right'  => '0px',
                'border-bottom' => '0px',
                'border-left'   => '0px'
            )
        ),

        array(
            'id'                    => 'cepatlakoo_footer_copyright_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Footer Copyright Color', 'cepatlakoo' ),
            'output'                => array('footer#colofon #footer-info .site-infos, #colofon .wp-caption p.wp-caption-text'),
            'transparent'			=> false,
            'default'               => '#555555',
        ),

        array(
            'id'                    => 'cepatlakoo_footer_copyright_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Footer Copyright Link Color', 'cepatlakoo' ),
            'output'                => array('footer#colofon #footer-info .site-infos a'),
            'active'                => false,
            'default'               => array(
                                        'regular'  => '#1e73be',
                                        'hover'    => '#956eb5',
            ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Sidebar', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_sidebar',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                    => 'cepatlakoo_widget_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Sidebar Widget Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('#sidebar a'),
            'default'               => array(
                                        'regular'  => '#1e73be',
                                        'hover'  => '#318e1c',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_sidebar_widget_title_border',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Sidebar Widget Title Border', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('#sidebar h4.widget-title:after'),
            'default'               => array(
                                        'regular'  => '#1e73be',
                                        'hover'  => '#318e1c',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_sidebar_border',
            'type'                  => 'color',
            'title'                 => esc_html__( 'Sidebar Widget Title Border', 'cepatlakoo' ),
            'output'                => array(
                                        'background-color' => '#sidebar h4.widget-title:after'
                                    ),
            'default'               => '#cccccc',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'WooCommerce', 'cepatlakoo' ),
    'desc'             => esc_html__('WooCommerce color settings.', 'cepatlakoo'),
    'id'               => 'cepatlakoo_colors_woocommerce',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_wc_button_section',
            'type'                      => 'section',
            'title'                     => esc_html__('WooCommerce Button', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Options for WooCommerce button.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_woocommerce_button_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WooCommerce Button Background Color', 'cepatlakoo' ),
            'output'                => array('.wp-block-tag-cloud a:hover, .wp-block-search .wp-block-search__button, #sidebar .widget .tagcloud a:hover, #sidebar .tagcloud a:hover, .cart-holders .cart-header .cart-icon, button.cl-copy-total:hover, .floating-product-actions .fpa-row a.fpa-btn.fpa-cart-btn, .woocommerce a.button, .button, .wc-proceed-to-checkout a.checkout-button, .form-row.place-order input#place_order, .woocommerce div.product form.cart .single_add_to_cart_button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce-cart .wc-proceed-to-checkout a.button, .cart-content a.checkout-btn, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button, ul.products li.product a.button.product_type_variable, ul.products li.product a.button.product_type_grouped, .woocommerce ul.products li.product a.button.product_type_external, .woocommerce a.single_add_to_cart_button, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .cart-holders .cart-footer a.checkout-btn, .woocommerce a.button.checkout-button.alt'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#399E5A',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_woocommerce_button_bg_color_hover',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WooCommerce Button Background Color Hover', 'cepatlakoo' ),
            'output'                => array('.wp-block-search .wp-block-search__button:hover, .woocommerce a.button:hover,  .woocommerce a.button.alt:hover, .button:hover, .woocommerce #respond input#submit:hover, .wc-proceed-to-checkout a.checkout-button:hover, .form-row.place-order input#place_order:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #payment #place_order:hover, .woocommerce-page #payment #place_order:hover, .woocommerce-cart .wc-proceed-to-checkout a.button:hover, .cart-content a.checkout-btn:hover, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button:hover, ul.products li.product a.button.product_type_variable:hover, ul.products li.product a.button.product_type_grouped:hover, .woocommerce ul.products li.product a.button.product_type_external:hover, .woocommerce a.single_add_to_cart_button:hover, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.alt:disabled[disabled]:hover, .cart-holders .cart-footer a.checkout-btn:hover, .cart-holders .cart-header .cart-icon:hover, .woocommerce a.button.checkout-button.alt:hover'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#399E5A',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_woocommerce_button_text_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'WooCommerce Button Text Color', 'cepatlakoo' ),
            'output'                => array('button.cl-copy-total:hover, .woocommerce a.button:hover, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .cart-holders .cart-footer a.checkout-btn, .primary-bg, a.primary-bg, .entrycontent a.primary-bg, .wc-proceed-to-checkout a.checkout-button, #dialog:before, .form-row.place-order input#place_order, .woocommerce div.product form.cart .button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce-cart .wc-proceed-to-checkout a.button, .cart-content a.checkout-btn, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button, ul.products li.product a.button.product_type_variable, ul.products li.product a.button.product_type_grouped, .woocommerce ul.products li.product a.button.product_type_external, .woocommerce a.single_add_to_cart_button, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover'),
            'transparent'           => false,
            'default'               => array(
                                        'regular'  => '#ffffff',
                                        'hover'  => '#fefefe',
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_woocommerce_button_border',
            'type'                  => 'border',
            'title'                 => esc_html__( 'Button Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array('.cart-holders .cart-footer a.checkout-btn, .wc-proceed-to-checkout a.checkout-button, #dialog:before, .form-row.place-order input#place_order, .woocommerce div.product form.cart .button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce-cart .wc-proceed-to-checkout a.button, .cart-content a.checkout-btn, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button, ul.products li.product a.button.product_type_variable, ul.products li.product a.button.product_type_grouped, .woocommerce ul.products li.product a.button.product_type_external, .woocommerce a.single_add_to_cart_button, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover'),
            'default'  => array(
            'border-color'  => '#399E5A',
            'border-style'  => 'solid',
            'border-top'    => '1px',
            'border-right'  => '1px',
            'border-bottom' => '1px',
            'border-left'   => '1px'
            )
        ),

        array(
            'id'                    => 'cepatlakoo_woocommerce_button_hover_border',
            'type'                  => 'border',
            'title'                 => esc_html__( 'Button Hover Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array('.cart-holders .cart-footer a.checkout-btn, .wc-proceed-to-checkout a.checkout-button, #dialog:before, .form-row.place-order input#place_order, .woocommerce div.product form.cart .button, .woocommerce #payment #place_order, .woocommerce-page #payment #place_order, .woocommerce-cart .wc-proceed-to-checkout a.button, .cart-content a.checkout-btn, .cart-content a.checkout-btn:hover, .woocommerce ul.products li.product a.add_to_cart_button, ul.products li.product a.button.product_type_variable, ul.products li.product a.button.product_type_grouped, .woocommerce ul.products li.product a.button.product_type_external, .woocommerce a.single_add_to_cart_button, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover'),
            'default'  => array(
            'border-color'  => '#399E5A',
            'border-style'  => 'solid',
            'border-top'    => '1px',
            'border-right'  => '1px',
            'border-bottom' => '1px',
            'border-left'   => '1px'
            )
        ),

        array(
            'id'                        => 'cepatlakoo_wc_whatsapp_button_section',
            'type'                      => 'section',
            'title'                     => esc_html__('WhatsApp Button', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Color options for WhatsApp button.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_bg_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WhatsApp Button Background Color', 'cepatlakoo' ),
            'output'                => array(
                'background-color' => '.buy-via-whatsapp .button.contact-wa-button, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button'
            ),
            'default'               => '#CCF2D8',
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_bg_hover_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WhatsApp Button Hover Background Color', 'cepatlakoo' ),
            'output'                => array(
                'background-color' => '.buy-via-whatsapp .button.contact-wa-button:hover, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn:hover, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button:hover'
            ),
            'default'               => '#CCF2D8',
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_border_color',
            'type'                  => 'border',
            'title'                 => esc_html__( 'WhatsApp Button Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array(
                'background-color' => '.buy-via-whatsapp .button.contact-wa-button, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button'
            ),
            'default'  => array(
                'border-color'  => '#399e5a',
                'border-style'  => 'solid',
                'border-top'    => '1px',
                'border-right'  => '1px',
                'border-bottom' => '1px',
                'border-left'   => '1px'
            )
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_border_hover_color',
            'type'                  => 'border',
            'title'                 => esc_html__( 'WhatsApp Button Hover Border', 'cepatlakoo' ),
            'transparent'           => false,
            'all'                   => false,
            'top'                   => true,
            'right'                 => true,
            'bottom'                => true,
            'left'                  => true,
            'output'                => array(
                'background-color' => '.buy-via-whatsapp .button.contact-wa-button:hover, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn:hover, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button:hover'
            ),
            'default'  => array(
                'border-color'  => '#399e5a',
                'border-style'  => 'solid',
                'border-top'    => '1px',
                'border-right'  => '1px',
                'border-bottom' => '1px',
                'border-left'   => '1px'
            )
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_text_color',
            'type'                  => 'link_color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WhatsApp Button Text Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('.buy-via-whatsapp .button.contact-wa-button, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button'),
            'default'               => array(
                'regular'  => '#399e5a',
                'hover'  => '#399e5a',
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_icon_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WhatsApp Button Icon Color', 'cepatlakoo' ),
            'output'                => array(
                'fill' => '.buy-via-whatsapp .button.contact-wa-button svg path, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn svg path, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button svg path'
            ),
            'default'               => '#399e5a',
        ),

        array(
            'id'                    => 'cepatlakoo_whatsapp_button_icon_hover_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'WhatsApp Button Icon Hover Color', 'cepatlakoo' ),
            'output'                => array(
                'fill' => '.buy-via-whatsapp .button.contact-wa-button:hover svg path, .floating-product-actions .fpa-row a.fpa-btn.fpa-whatsapp-btn:hover svg path, .woocommerce ul.products li.product .buy-via-whatsapp .button.contact-wa-button:hover svg path'
            ),
            'default'               => '#399e5a',
        ),

        array(
            'id'                        => 'cepatlakoo_discount_badge',
            'type'                      => 'section',
            'title'                     => esc_html__('WooCommerce Discount Badge', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Product discount badge on product detail page.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_discount_badge_background',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Discount Badge Background Color', 'cepatlakoo' ),
            'output'                => array('.woocommerce span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce .product .onsale, .woocommerce .product-list .onsale'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#ff5d8f',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_discount_badge_text_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Discount Badge Text Color', 'cepatlakoo' ),
            'transparent'           => false,
            'output'                => array('.woocommerce span.onsale, .woocommerce #main .onsale'),
            'default'               => '#ffffff',
        ),

        array(
            'id'                        => 'cepatlakoo_product_badges_section',
            'type'                      => 'section',
            'title'                     => esc_html__('Product Badges', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Product badges on product detail page.', 'cepatlakoo'),
            'indent'                    => false,
        ),

        array(
            'id'                    => 'cepatlakoo_product_badge_bg_color_1',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Product Badge 1 Background Color', 'cepatlakoo' ),
            'output'                => array('.woocommerce .cl-product-badges span'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#dedede',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_product_badge_text_color_1',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Product Badge 1 Text Color', 'cepatlakoo' ),
            'transparent'           => false,
            'output'                => array('.woocommerce .cl-product-badges span'),
            'default'               => '#555555',
        ),

        array(
            'id'                    => 'cepatlakoo_product_badge_bg_color_2',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Product Badge 2 Background Color', 'cepatlakoo' ),
            'output'                => array('.woocommerce .cl-product-badges span:nth-child(even)'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#ab4e68',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_product_badge_text_color_2',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Product Badge 2 Text Color', 'cepatlakoo' ),
            'transparent'           => false,
            'output'                => array('.woocommerce .cl-product-badges span:nth-child(even)'),
            'default'               => '#ffffff',
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Blog', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_blog',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_page_title_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Page Title Background Color', 'cepatlakoo' ),
            'output'                => array('#page-title'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#faf9fc',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_post_title_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Post Title Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('.postlist article.hentry h3.post-title a'),
            'default'               => array(
                                        'regular'  => '#333333',
                                        'hover'  => '#1e73be',
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_post_meta_text_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Post Meta Text Color', 'cepatlakoo' ),
            'output'                => array('.page-template .entry-meta span, .single .entry-meta span'),
            'transparent'           => false,
            'default'               => '#aaaaaa',
            'validate'              => "color",
        ),

        array(
            'id'                    => 'cepatlakoo_postmeta_link_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Post Meta Link Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('.entry-meta a'),
            'default'               => array(
                                        'regular'  => '#aaaaaa',
                                        'hover'  => '#d64933',
            ),
        ),

        array(
            'id'                    => 'cepatlakoo_sharing_btn_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Share Buttons Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('.share-article-widget a i'),
            'default'               => array(
                                        'regular'  => '#b4b4b4',
                                        'hover'  => '#d64933',
            ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Back to Top Button', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_backtotop',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                        => 'cepatlakoo_section_backtotop',
            'type'                      => 'section',
            'title'                     => esc_html__('Back to Top Button', 'cepatlakoo'),
            'subtitle'                  => esc_html__('Back to top floating button settings.', 'cepatlakoo'),
            'indent'                    => false,
        ),
        
        array(
            'id'                    => 'cepatlakoo_backtotop_icon_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Cepatlakoo Back to Top Icon Color', 'cepatlakoo' ),
            'transparent'           => false,
            'default'               => '#999999',
            'output'                => array('fill' => '#backtotop svg path, .woocommerce .product-carousel ul.products.owl-carousel .owl-nav .owl-prev:hover svg path, .woocommerce .product-carousel ul.products.owl-carousel .owl-nav .owl-next:hover svg path'),
        ),

        array(
            'id'                    => 'cepatlakoo_backtotop_icon_hover_color',
            'type'                  => 'color',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Cepatlakoo Back to Top Icon Color on hover', 'cepatlakoo' ),
            'transparent'           => false,
            'default'               => '#ffffff',
            'output'                => array('fill' => '#backtotop:hover svg path'),
        ),
        array(
            'id'                    => 'cepatlakoo_backtotop_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Back to Top Background Color', 'cepatlakoo' ),
            'output'                => array('#backtotop'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#cecece',
            )
        ),
        array(
            'id'                    => 'cepatlakoo_backtotop_bg_hover_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Back to Top Background Hover Color', 'cepatlakoo' ),
            'output'                => array('#backtotop:hover'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'default'               => array(
                                        'background-color' => '#333333',
            )
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Form', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_form',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(
        array(
            'id'                    => 'cepatlakoo_form_field_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Form Field Background Color', 'cepatlakoo' ),
            'output'                => array('input:not([type]), input[type="date"], input[type="datetime-local"], input[type="email"], input[type="file"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], textarea, .select2-container .select2-selection--single, select'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#fafafa',
            )
        ),
        array(
            'id'                    => 'cepatlakoo_form_button_bg_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Form Button Background Color', 'cepatlakoo' ),
            'output'                => array('.elementor-menu-cart__footer-buttons .elementor-button, .woocommerce .order-container .unique-code:after, #add_payment_method #payment ul.payment_methods li input.input-radio:checked+label:after, .woocommerce-cart #payment ul.payment_methods li input.input-radio:checked+label:after, .woocommerce-checkout #payment ul.payment_methods li input.input-radio:checked+label:after, #add_payment_method #payment ul.payment_methods li label:before, .woocommerce-cart #payment ul.payment_methods li label:before, .woocommerce-checkout #payment ul.payment_methods li label:before, .woocommerce .order-container .cl-order-summary:after,body .select2-container--default .select2-results__option.select2-results__option--highlighted,.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, input[type=submit], input[type=reset], input[type=button], button, a.btn, a.button, input.button, input.btn, .woocommerce a.btn, .woocommerce a.button, .woocommerce a.button.alt, table.shop_table.cart td.actions .button, .woocommerce button.button, .woocommerce input.button, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt, .woocommerce a.btn, .woocommerce #respond input#submit, .owl-dots .owl-dot.active, .ct-nav.active:after, p.buttons a.button.checkout, button.cl-copy-total, button.cl-copy-rekening'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#399e5a',
            )
        ),
        
        array(
            'id'                    => 'cepatlakoo_form_button_bg_hover_color',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Form Button Background Hover Color', 'cepatlakoo' ),
            'output'                => array('.elementor-menu-cart__footer-buttons .elementor-button:hover, input[type=submit]:hover, input[type=reset]:hover, input[type=button]:hover, button:hover, a.btn:hover,table.shop_table.cart td.actions .button:hover, a.button:hover, input.button:hover, input.btn:hover, .woocommerce a.btn:hover, .woocommerce a.button:hover,  .woocommerce a.button.alt:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:hover, .woocommerce a.btn:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, button.cl-copy-total:hover, button.cl-copy-rekening:hover'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#399e5a',
            )
        ),
        array(
            'id'                    => 'cepatlakoo_form_button_text_color',
            'type'                  => 'link_color',
            'title'                 => esc_html__( 'Form Button Text Color', 'cepatlakoo' ),
            'active'                => false,
            'output'                => array('input[type=submit], input[type=reset], input[type=button], button, a.btn, a.button, input.button, input.btn, .woocommerce a.btn, button, .woocommerce a.button, .woocommerce a.button.alt, .woocommerce button.button, .woocommerce input.button, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt, .woocommerce a.btn, .woocommerce #respond input#submit'),
            'default'               => array(
                                        'regular'  => '#ffffff',
                                        'hover'  => '#ffffff',
            ),
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'            => __( 'Contact Buttons', 'cepatlakoo' ),
    'id'               => 'cepatlakoo_colors_contact_buttons',
    'customizer_width' => '450px',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'                    => 'cepatlakoo_custom_bg_sms',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'SMS Background Color', 'cepatlakoo' ),
            'output'                => array('.quick-contact-info a.sms'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#efc33c',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_custom_bg_line',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'LINE Background Color', 'cepatlakoo' ),
            'output'                => array('.quick-contact-info a.line'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#44b654',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_custom_bg_phone',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Phone Background Color', 'cepatlakoo' ),
            'output'                => array('.quick-contact-info a.phone'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#1ad0dd',
            )
        ),

        array(
            'id'                    => 'cepatlakoo_custom_bg_telegram',
            'type'                  => 'background',
            'class'                 => 'cl-options-colors',
            'title'                 => esc_html__( 'Telegram Background Color', 'cepatlakoo' ),
            'output'                => array('.quick-contact-info a.telegram'),
            'preview'               => false,
            'preview_media'         => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'background-position'   => false,
            'background-image'      => false,
            'background-gradient'   => false,
            'background-clip'       => false,
            'background-origin'     => false,
            'background-size'       => false,
            'transparent'           => false,
            'default'               => array(
                                        'background-color' => '#38afe2',
            )
        ),
    ),
) );

if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
    $section = array(
        'icon'   => 'el el-list-alt',
        'title'  => __( 'Documentation', 'cepatlakoo' ),
        'fields' => array(
            array(
                'id'       => '17',
                'type'     => 'raw',
                'markdown' => true,
                'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                //'content' => 'Raw content here',
            ),
        ),
    );
    Redux::setSection( $opt_name, $section );
}
/*
 * <--- END SECTIONS
 */


/*
 *
 * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
 *
 */

/*
*
* --> Action hook examples
*
*/

// If Redux is running as a plugin, this will remove the demo notice and links
//add_action( 'redux/loaded', 'remove_demo' );

// Function to test the compiler hook and demo CSS output.
// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
//add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

// Change the arguments after they've been declared, but before the panel is created
//add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

// Change the default value of a field after it's been set, but before it's been useds
//add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

// Dynamically add a section. Can be also used to modify sections/fields
//add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

/**
 * This is a test function that will let you see when the compiler hook occurs.
 * It only runs if a field    set with compiler=>true is changed.
 * */
if ( ! function_exists( 'compiler_action' ) ) {
    function compiler_action( $options, $css, $changed_values ) {
        echo '<h1>The compiler hook has run!</h1>';
        echo "<pre>";
        print_r( $changed_values ); // Values that have changed since the last save
        echo "</pre>";
        //print_r($options); //Option values
        //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
    }
}

/**
 * Custom function for the callback validation referenced above
 * */
if ( ! function_exists( 'redux_validate_callback_function' ) ) {
    function redux_validate_callback_function( $field, $value, $existing_value ) {
        $error   = false;
        $warning = false;

        //do your validation
        if ( $value == 1 ) {
            $error = true;
            $value = $existing_value;
        } elseif ( $value == 2 ) {
            $warning = true;
            $value   = $existing_value;
        }

        $return['value'] = $value;

        if ( $error == true ) {
            $field['msg']    = 'your custom error message';
            $return['error'] = $field;
        }

        if ( $warning == true ) {
            $field['msg']      = 'your custom warning message';
            $return['warning'] = $field;
        }

        return $return;
    }
}

/**
 * Custom function for the callback referenced above
 */
if ( ! function_exists( 'redux_my_custom_field' ) ) {
    function redux_my_custom_field( $field, $value ) {
        print_r( $field );
        echo '<br/>';
        print_r( $value );
    }
}

/**
 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
 * so you must use get_template_directory_uri() if you want to use any of the built in icons
 * */
if ( ! function_exists( 'dynamic_section' ) ) {
    function dynamic_section( $sections ) {
        //$sections = array();
        $sections[] = array(
            'title'  => __( 'Section via hook', 'cepatlakoo' ),
            'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'cepatlakoo' ),
            'icon'   => 'el el-paper-clip',
            // Leave this as a blank section, no options just some intro text set above.
            'fields' => array()
        );

        return $sections;
    }
}

/**
 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
 * */
if ( ! function_exists( 'change_arguments' ) ) {
    function change_arguments( $args ) {
        //$args['dev_mode'] = true;

        return $args;
    }
}

/**
 * Filter hook for filtering the default value of any given field. Very useful in development mode.
 * */
if ( ! function_exists( 'change_defaults' ) ) {
    function change_defaults( $defaults ) {
        $defaults['str_replace'] = 'Testing filter hook!';

        return $defaults;
    }
}

/**
 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
 */
if ( ! function_exists( 'remove_demo' ) ) {
    function remove_demo() {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}

/**
* Functions to modify theme options values for color scehems
*
* @package WordPress
* @subpackage CepatLakoo
* @since CepatLakoo 1.3.6
*
*/
if ( ! function_exists( 'cepatlakoo_redux_color_schemes' ) ) {
    function cepatlakoo_redux_color_schemes() {
        global $cl_options;
        $color_scheme = $cl_options['cepatlakoo_color_schemes'];

        if( $color_scheme != 'custom' ){
            if ( $color_scheme == 'purple' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/purple.php' );
            } else if ( $color_scheme == 'purewhite' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/purewhite.php' );
            } else if ( $color_scheme == 'lightgreen' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/lightgreen.php' );
            } else if ( $color_scheme == 'sunset' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/sunset.php' );
            } else if ( $color_scheme == 'strongblue' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/strongblue.php' );
            } else if ( $color_scheme == 'earthy' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/earthy.php' );
            } else if ( $color_scheme == 'romantic' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/romantic.php' );
            } else if ( $color_scheme == 'darkcloud' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/darkcloud.php' );
            } else if ( $color_scheme == 'cyan' ) {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/cyan.php' );
            } else {
                include_once( get_template_directory() . '/functions/theme-functions/color-schemes/default.php' );
            }
        }

        header('Refresh:0');
    }
}
add_action ('redux/options/cl_options/saved', 'cepatlakoo_redux_color_schemes');