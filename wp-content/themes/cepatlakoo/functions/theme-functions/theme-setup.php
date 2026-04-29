<?php
/**
* List of theme setup functions
*/

// Check if the function exist
if ( !function_exists( 'cepatlakoo_theme_setup' ) ) {
	function cepatlakoo_theme_setup() {
		// Register sidebar widgets
		add_action( 'widgets_init', 'cepatlakoo_register_sidebars' );

		// Add post thumbnail feature
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'cepatlakoo-featured-post-big', 430, 430, true );
		add_image_size( 'cepatlakoo-featured-post', 215, 215, true );
		add_image_size( 'cepatlakoo-blog-thumb', 860, 9999, false );
		// add_image_size( 'cepatlakoo-slideshow-carousel', 396, 550, true );

		// Add WordPress navigation menus
		add_theme_support( 'nav-menus' );

		register_nav_menus( array(
			'cepatlakoo-left-navigation' => esc_html__( 'Main Menu Navigation', 'cepatlakoo' ),
			'cepatlakoo-footer-navigation' => esc_html__( 'Footer Menu Navigation', 'cepatlakoo' ),
		) );

		// Add Title Tag Support
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Add custom background feature
		add_theme_support( 'custom-background' );

		// Add custom logo support
		add_theme_support( 'custom-logo', array(
			'height'      => 29,
			'width'       => 158,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		 ) );

		// Declare WooCommerce support
	    add_theme_support( 'woocommerce' );
	    add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
	}
}
add_action( 'after_setup_theme', 'cepatlakoo_theme_setup' );

// Theme Localization
load_theme_textdomain( 'cepatlakoo', get_template_directory().'/lang' );

// Set maximum image width displayed in a single post or page
if ( ! isset( $content_width ) ) {
	$content_width = 1180;
}
?>