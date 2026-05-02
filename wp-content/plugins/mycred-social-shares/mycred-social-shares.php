<?php

/**
 * Plugin Name: myCRED Social Share
 * Version: 1.4.9
 * Description: Social Share Addon with myCRED Plugin that allows admin to assign points to users for each timeshare post type on social media. Admin can manage the criteria of share through the presentation method on the site.
 * Plugin URI: https://www.mycred.me/store/mycred-social-share-add-on/
 * Author: myCred
 * Author URI: https://mycred.me
 * Author Email: support@mycred.me
 * Requires at least: WP 4.8
 * Tested up to: WP 6.3.2
 * Text Domain: mycred_social_share
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MYCRED_SOCIALSHARE_VERSION',  '1.4.9' );
define( 'MYCRED_SOCIALSHARE_SLUG',     'mycred-social-shares' );
define( 'MYCRED_SOCIALSHARE_NAME',     plugin_basename( __FILE__ ) );
define( 'MYCRED_SOCIALSHARE_THIS',     __FILE__ );
define( 'MYCRED_SOCIALSHARE_ROOT_DIR', plugin_dir_path( MYCRED_SOCIALSHARE_THIS ) );

add_action('plugins_loaded', 'mycred_socialshare');

function mycred_socialshare() {
    if (class_exists('myCRED_Hook')) {
        if (is_admin()) {
            require(plugin_dir_path(__FILE__) . '/class-socialshare-admin.php');
			$social_settings = new MyCred_Social_Shares_Settings();
			
        }
		require_once(plugin_dir_path(__FILE__) . '/class-socialshare-widget.php');
        require_once(plugin_dir_path(__FILE__) . '/class-socialshare-frontend.php');
        require_once(plugin_dir_path(__FILE__) . '/class-socialshare-follow-button-frontend.php');
        require(plugin_dir_path(__FILE__) . '/class-social-share-hook.php');
		add_action('wp_head', 'mycred_social_head_includes');
        add_action( 'wp_enqueue_scripts', 'mycred_social_shares_frontend_assets' );
		add_filter( 'mycred_all_references', 'mycred_social_share_add_custom_references' );
		add_shortcode( 'mycred_display_social_icons', 'mycred_display_social_icons' );    
		add_shortcode( 'mycred_display_social_follow_button', 'mycred_display_social_follow_button_Frontend' );    
    } else {
        add_action('admin_notices', 'mycred_socialshare_notices');
    }
}


function mycred_social_share_add_custom_references( $list ) {

	$list['social_share'] = 'Social Share';
	$list['social_follow'] = 'Social Follow';

	return $list;

}


function mycred_social_head_includes() {?>

    <meta property="og:url" content="<?php echo get_the_permalink(); ?>"  >
    <meta property="og:title" content="<?php echo get_the_title(); ?>"  >
    <meta property="og:description" content="<?php echo wp_strip_all_tags( get_the_excerpt() );?>" >
    <meta property="fb:app_id" content="<?php echo get_option('facebook_app_id'); ?>"/>
    <meta property="og:image" content="<?php echo the_post_thumbnail_url( 'full' ); ?>">
    <meta name="twitter:image" content="<?php echo the_post_thumbnail_url( 'full' ); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'assets/css/social-share-addon.css' ?>">
    <?php

}

function mycred_socialshare_notices() {
    printf('<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error', __('MyCRED Social Share requires MyCred to be installed and active.', 'mycred_social_share'));
}

function social_position($content = null,$output = null){
	switch (get_option('social_position')) {
                case 'after' :
                    return $content.$output;
                    break;
                case 'before' :
					return $output.$content;
                    
					break;
                case 'both' :
                   return $output.$content.$output;
                    break;
                default :
                   return $content;
                    break;
            }
}

function mycred_display_social_icons() {
	
    if (!is_user_logged_in()) {
        $output = '';
		if ( 'yes' == get_option('none_login_user_show_icons') ){
				 
				ob_start(); //Start output buffer
				echo MyCred_Social_Shares();
				$output = ob_get_contents(); //Grab output
				ob_end_clean(); //Discard output buffer
			}
        if ( 'yes'==get_option('txtCheckNotification') ) :
            $output .= "<div class='mycred_socialshare_notice'>" . __( 'If you want to share and earn points please', 'mycred_social_share' ) . "<a href='".wp_login_url( get_permalink())."'>" . __('login', 'mycred_social_share') . "</a>" . __('first', 'mycred_social_share') . "</div>";
            return $output;
        endif;

        return $output;
    }

  return MyCred_Social_Shares();
}

function MyCred_Social_Shares(){
if (class_exists('MyCred_Social_Shares_Frontend')) {
        $is_social_shares_hooks = get_option('mycred_pref_hooks');
        if(in_array( 'social_share', $is_social_shares_hooks['active'] )){
            $social_frontend = new MyCred_Social_Shares_Frontend();
		    ob_start(); 
            $social_frontend->social_share_html();
            $output = ob_get_contents(); 
            ob_end_clean();
            return $output;
        }
    }
}

function mycred_display_social_follow_button_Frontend() {
	
	if (!is_user_logged_in()) {
		 $output = '';
		if ( 'yes' == get_option('none_login_user_show_icons') ){
				 
				ob_start(); //Start output buffer
				echo Follow_Button_Frontend();
				$output = ob_get_contents(); //Grab output
				ob_end_clean(); //Discard output buffer
			}
        if ( 'yes'==get_option('txtCheckNotification') ) :
            $output .= "<div class='mycred_socialshare_notice'>" . __( 'If you want to share and earn points please', 'mycred_social_share' ) . "<a href='".wp_login_url( get_permalink())."'>" . __('login', 'mycred_social_share') . "</a>" . __('first', 'mycred_social_share') . "</div>";
            return $output;
        endif;

        return $output;
    }
  return  Follow_Button_Frontend();
}
function Follow_Button_Frontend() {
	 if (class_exists('MyCred_Social_Follow_Button_Frontend')) {
        $is_social_shares_hooks = get_option('mycred_pref_hooks');
        if(in_array( 'social_share', $is_social_shares_hooks['active'] )){
            $social_frontend = new MyCred_Social_Follow_Button_Frontend();
            ob_start(); 
            $social_frontend->social_share_follow_button_html();
            $output = ob_get_contents(); 
            ob_end_clean();
            return $output;
        }
    }
}
function mycred_social_shares_frontend_assets() {
    wp_enqueue_style('mycred-social-icons', plugin_dir_url(__FILE__) . 'assets/css/mycred-social-icons.css', false);

    $enabled_icons = get_option('social_fields_check');

    $pinterest_index = 2;
    $linkedin_index  = 3;

    if ( ! empty( $enabled_icons[ $pinterest_index ] ) && $enabled_icons[ $pinterest_index ] ) {
        wp_enqueue_script( 'mycred-pinterest-lib', '//platform.linkedin.com/in.js' );
    }

    if ( ! empty( $enabled_icons[ $linkedin_index ] ) && $enabled_icons[ $linkedin_index ] ) {
        wp_enqueue_script( 'mycred-linkedin-lib', '//assets.pinterest.com/js/pinit.js' );
    }

}

/**
 * Load License
 * @since 1.4.6
 * @version 1.0
 */
function mycred_social_shares_load_license() {

    if ( class_exists('myCRED_License') ) {
            
        new myCRED_License( 
            array(
                'version' => MYCRED_SOCIALSHARE_VERSION,
                'slug'    => MYCRED_SOCIALSHARE_SLUG,
                'base'    => __FILE__
            )
        );
        
    }
    else {

        add_action( 'admin_notices', 'mycred_social_shares_license_admin_notice' );

    }

}
add_action( 'mycred_init', 'mycred_social_shares_load_license' );

function mycred_social_shares_license_admin_notice() {

    echo '<div class="notice notice-error is-dismissible"><p>myCRED for Beaver Builder requires myCred 2.3 or greater version to work your license properly.</p></div>';

}