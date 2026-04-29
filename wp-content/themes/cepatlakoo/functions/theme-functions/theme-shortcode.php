<?php
/**
 * Theme Shortcode
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! function_exists( 'cepatlahoo_countdown_shortcode') ) {
    function cepatlahoo_countdown_shortcode($atts) {
        global $wp;
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
        // override default attributes with user attributes
        $cepatlakoo_atts = shortcode_atts( array(
        	'id' => '',
    		'type' => '',
        ), $atts);
        
        wp_enqueue_script( 'cl-countdown' );

        $countdown_id = $cepatlakoo_atts['id'];
        $ct_id = ( class_exists( 'WooCommerce' ) && is_checkout() ) ? absint( $wp->query_vars['order-received'] ) : get_the_ID();
        $ct_position = $cepatlakoo_atts['type'];
        $countdown_type = get_post_meta( $countdown_id, 'cl_countdown_type', true );
        if ($countdown_type == "Evergreen Countdown") {
            $countdown_day = !empty(get_post_meta( $countdown_id, 'cl_countdown_day', true )) ? get_post_meta( $countdown_id, 'cl_countdown_day', true ) : 0;
            $countdown_hour = !empty(get_post_meta( $countdown_id, 'cl_countdown_hour', true )) ? get_post_meta( $countdown_id, 'cl_countdown_hour', true ) : 0;
            $countdown_minute = !empty(get_post_meta( $countdown_id, 'cl_countdown_minute', true )) ? get_post_meta( $countdown_id, 'cl_countdown_minute', true ) : 0;
            $countdown_second = !empty(get_post_meta( $countdown_id, 'cl_countdown_second', true )) ? get_post_meta( $countdown_id, 'cl_countdown_second', true ) : 0;
            $countdown_detection = get_post_meta( $countdown_id, 'cl_countdown_detection', true );
            $expiry = time() + (86400 * 365); // 86400 = 1 day expires
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

            $set_curr_time = date( 'Y/m/d H:i:s e' );
            $curr_time = new DateTime($set_curr_time);
            $add = '+'.$countdown_day.' days +'.$countdown_hour.' hours +'.$countdown_minute.' minutes +'.$countdown_second.' seconds';
            $set_new_date = date('Y-m-d H:i:s e',strtotime($add, strtotime($set_curr_time)));
            $set_countdown_time = $set_new_date;
            $countdown_time = $set_countdown_time;

            if ($countdown_detection == 'Cookie') {
                if ($countdown_id != '') :
                    $cookie_countdown_date_time_name = 'scarcity_countdown_date_time_'.$ct_id.'_'.$countdown_id;
                    $get_cookie_countdown_date_time = $set_countdown_time;
                    
                    wp_localize_script('cepatlakoo-functions', '_cepatlakoo', array(
                        'scarcity_countdown_date_time'  => $get_cookie_countdown_date_time,
                        'scarcity_cookies_name'         => $cookie_countdown_date_time_name,
                        'scarcity_start_date_time'      => $set_curr_time,
                        'scarcity_countdown_type'       => $countdown_type,
                        'scarcity_countdown_timer'      => $add
                    )); ?>
                    
                    <div class="sc-time" style="display:none;"><?php echo esc_attr($get_cookie_countdown_date_time) ?></div>
                    <div class="sc-cookies" style="display:none;"><?php echo esc_attr($cookie_countdown_date_time_name) ?></div>
                    <div class="sc-type" style="display:none;"><?php echo esc_attr($countdown_type) ?></div>
                    <div class="sc-timer" style="display:none;"><?php echo esc_attr($add) ?></div>
                <?php endif;
            } else {
                $ipaddress = '';
                if (getenv('HTTP_CLIENT_IP'))
                    $ipaddress = getenv('HTTP_CLIENT_IP');
                elseif (getenv('HTTP_X_FORWARDED_FOR'))
                    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                elseif (getenv('HTTP_X_FORWARDED'))
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                elseif (getenv('HTTP_FORWARDED_FOR'))
                    $ipaddress = getenv('HTTP_FORWARDED_FOR');
                elseif (getenv('HTTP_FORWARDED'))
                    $ipaddress = getenv('HTTP_FORWARDED');
                elseif (getenv('REMOTE_ADDR'))
                    $ipaddress = getenv('REMOTE_ADDR');
                else
                    $ipaddress = 'UNKNOWN';

                $ipaddress = ($ipaddress == '::1') ? '127.0.0.1' : $ipaddress;
                
                $ct_old = get_post_meta( $ct_id, 'cl_countdown_ip_'.$countdown_id, true );
                
                if ($countdown_id != '') :
                    if( isset($ct_old[$ipaddress]) && $ct_old[$ipaddress]['timer'] == $add ){
                        $get_cookie_countdown_date_time = $ct_old[$ipaddress]['value'];
                    }
                    else{
                        if( $ct_old == '' ){
                            $arr_ip = array( $ipaddress => array(
                                'value' => $set_countdown_time,
                                'timer' => $add
                            ));
                            add_post_meta($ct_id, 'cl_countdown_ip_'.$countdown_id, $arr_ip, true);
                        }
                        else{
                            $ct_old[$ipaddress] = array(
                                'value' => $set_countdown_time,
                                'timer' => $add
                            );
                            update_post_meta($ct_id, 'cl_countdown_ip_'.$countdown_id, $ct_old);
                        }
                        $get_cookie_countdown_date_time = $set_countdown_time;
                    }
                    
                    wp_localize_script('cepatlakoo-functions', '_cepatlakoo', array(
                        'scarcity_countdown_date_time' => $get_cookie_countdown_date_time,
                        'scarcity_countdown_type' => $countdown_type
                    )); ?>

                    <div class="sc-time" style="display:none;"><?php echo esc_attr($get_cookie_countdown_date_time) ?></div>
                    <div class="sc-type" style="display:none;"><?php echo esc_attr($countdown_type) ?></div>
                <?php endif;
            }
        } else { // IF Nornam COuntdown
            // $countdown_date = get_post_meta( $countdown_id, 'cl_normal_countdown_date', true );
            $countdown_date = get_field('cl_normal_countdown_date', $countdown_id);
            $countdown_hour = get_post_meta( $countdown_id, 'cl_normal_countdown_hour', true );
            $countdown_minute = get_post_meta( $countdown_id, 'cl_normal_countdown_minute', true );
            $countdown_second = get_post_meta( $countdown_id, 'cl_normal_countdown_second', true );
            $countdown_detection = get_post_meta( $countdown_id, 'cl_normal_countdown_detection', true );
            $expiry = time() + (86400 * 360); // 86400 = 1 day expires
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

            $set_curr_time = date( 'Y/m/d H:i:s', current_time( 'timestamp', 0 ) );
            $curr_time = new DateTime($set_curr_time);

            $set_countdown_time = $countdown_date.' '.$countdown_hour.':'.$countdown_minute.':'.$countdown_second;
            $countdown_time = date_create_from_format('Y-m-d H:i:s', $set_countdown_time);

            wp_localize_script('cepatlakoo-functions', '_cepatlakoo', array(
                'scarcity_countdown_date_time' => $set_countdown_time,
                'scarcity_countdown_type' => $countdown_type
            ));

            echo '<div class="sc-time" style="display:none;">'.$set_countdown_time.'</div>';
            echo '<div class="sc-type" style="display:none;">'.$countdown_type.'</div>';
        }

        if (!empty($get_cookie_countdown_date_time) || !empty($countdown_id) || !empty($countdown_type)) {
            $cepatlakoo_countdown_heading_cart = !empty( $cl_options['cepatlakoo_countdown_heading_cart'] ) ? $cl_options['cepatlakoo_countdown_heading_cart'] : '';
            $cepatlakoo_countdown_subheading_cart = !empty( $cl_options['cepatlakoo_countdown_subheading_cart'] ) ? $cl_options['cepatlakoo_countdown_subheading_cart'] : '';
        }else{
            $cepatlakoo_countdown_heading_cart = null;
            $cepatlakoo_countdown_subheading_cart = null;
        }
        
        if( $cepatlakoo_countdown_heading_cart !== null && $cepatlakoo_countdown_subheading_cart !== null && !empty($countdown_id) ) {
        ?>
            <div id="countdown-widget">
                <div id="countdown-container">
                    <?php if ( $cepatlakoo_countdown_heading_cart || $cepatlakoo_countdown_subheading_cart ) : ?>
                        <div class="coutndown-head">
                            <?php if( $cepatlakoo_countdown_heading_cart ) : ?>
                                <h3><?php echo $cepatlakoo_countdown_heading_cart; ?></h3>
                            <?php endif; ?>
    
                            <?php if( $cepatlakoo_countdown_subheading_cart ) : ?>
                                <h4 class="subheading"><?php echo $cepatlakoo_countdown_subheading_cart; ?></h4>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
    
                    <div id="countdown" data-state="loading" data-stellar-ratio="0.5">
                        <div id="timer">
                            <div class="number-container month"><div class="number"></div><div class="text"></div></div>
                            <div class="number-container day"><div class="number"></div><div class="text"></div></div>
                            <div class="number-container hour"><div class="number"></div><div class="text"></div></div>
                            <div class="number-container minute"><div class="number"></div><div class="text"></div></div>
                            <div class="number-container second"><div class="number"></div><div class="text"></div></div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
}

if ( ! function_exists( 'cepatlahoo_shortcodes_init') ) {
    function cepatlahoo_shortcodes_init() {
        add_shortcode('cepatlakoo-countdown', 'cepatlahoo_countdown_shortcode');
    }
}
add_action('init', 'cepatlahoo_shortcodes_init');

/**
* Functions to set Slideshow to Shortcode
*
* @package WordPress
* @subpackage CepatLakoo
* @since CepatLakoo 1.0.0
*/
if ( ! function_exists( 'cepatlakoo_slideshow_shortcode') ) {
    function cepatlakoo_slideshow_shortcode($atts) {
        extract(shortcode_atts(array(
            'id' => '',
            'item' => 4,
        ), $atts));

        $idescape = absint( $id );
        $type = ! empty( get_post_meta( $idescape, 'cepatlakoo_choose_slideshow', true ) ) ? get_post_meta( $idescape, 'cepatlakoo_choose_slideshow', true ) : esc_html('carousel');
        $cepatlakoo_auto_rotate = ! empty( get_post_meta( $idescape, 'cepatlakoo_slideshow_autoplay', true ) ) && ( get_post_meta( $idescape, 'cepatlakoo_slideshow_autoplay', true ) == '1' || get_post_meta( $idescape, 'cepatlakoo_slideshow_autoplay', true ) == 'on') ? 'true' : 'false';
        $cepatlakoo_looping = ! empty( get_post_meta( $idescape, 'cepatlakoo_slideshow_loop', true ) ) && ( get_post_meta( $idescape, 'cepatlakoo_slideshow_loop', true ) == '1' || get_post_meta( $idescape, 'cepatlakoo_slideshow_loop', true ) == 'on' ) ? 'true' : 'false';
        $cepatlakoo_delay = ! empty( get_post_meta( $idescape, 'cepatlakoo_slideshow_delay', true ) ) ? get_post_meta( $idescape, 'cepatlakoo_slideshow_delay', true )*1000 : 8000;
        
        if ($type == 'fullwidth') {
            $cepatlakoo_number_ofpost = 1;
        } elseif ($type == 'carousel') {
            $cepatlakoo_number_ofpost = ! empty( get_post_meta( $idescape, 'cepatlakoo_slideshow_item', true ) ) ? get_post_meta( $idescape, 'cepatlakoo_slideshow_item', true ) : 6;
        }

        if( ! $idescape ) {
            return esc_html__( 'Please choose a slideshow first.', 'cepatlakoo' );
        }

        $params = array( 'post_type' => 'cl_slideshow', 'p' => $idescape);

        $wp_query = new \WP_Query($params);
            if ( $wp_query->have_posts() ) :
                while ( $wp_query->have_posts() ) :
                    $wp_query->the_post();
                    $cepatlakoo_slider = '<section class="cepatlakoo-slideshow" number="'.$cepatlakoo_number_ofpost.'">';
                        $cepatlakoo_slider .= '<div class="'. esc_attr( 'cepatlakoo-' . $type) .'" data-rotate="'.$cepatlakoo_auto_rotate.'" data-loop="'.$cepatlakoo_looping.'" data-delay="'.$cepatlakoo_delay.'">';
                            $cepatlakoo_slider .= '<div class="preload"></div>';

                            // $cepatlakoo_entries = ! empty( get_post_meta( $idescape, 'cepatlakoo_slideshow_image_group', true ) ) ? get_post_meta( $idescape, 'cepatlakoo_slideshow_image_group', true ) : false;
                                
                                if ( get_field('cepatlakoo_slideshow_image_group', $idescape) ){
                                    // foreach ( (array) $cepatlakoo_entries as $key => $entry ) {
                                    while( the_repeater_field('cepatlakoo_slideshow_image_group', $idescape) ) {
                                        $cepatlakoo_image_thumb = get_sub_field('cepatlakoo_slideshow_image');
                                        // $cepatlakoo_image_thumb = esc_url ( $cepatlakoo_image_link['url'] );
                                        $cepatlakoo_trigger = get_sub_field('cepatlakoo_slideshow_enable_text');

                                        if ( $cepatlakoo_trigger ) {
                                            $cepatlakoo_head_title = get_sub_field('cepatlakoo_slideshow_heading');
                                            $cepatlakoo_short_desc = get_sub_field('cepatlakoo_slideshow_desc');
                                            $cepatlakoo_button_url = get_sub_field('cepatlakoo_slideshow_button_url');
                                            $cepatlakoo_button_text = get_sub_field('cepatlakoo_slideshow_button_text');
                                            $cepatlakoo_button_url_open = get_sub_field('cepatlakoo_slideshow_open_link');

                                            if ($cepatlakoo_button_url_open) {
                                                $cepatlakoo_button_url_open = '_blank';
                                            } else {
                                                $cepatlakoo_button_url_open = '';
                                            }
                                        }

                                        if ( $type == 'carousel' ) {
                                            $cepatlakoo_slider .= '<div class="cmc-item">';
                                                    $cepatlakoo_slider .= '<div class="thumbnail">';
                                                    $cepatlakoo_slider .= '<a href="' . esc_url( $cepatlakoo_button_url ) . '" target="' . esc_attr( $cepatlakoo_button_url_open ) . '">';
                                                        $cepatlakoo_slider .= '<img src="' . esc_url( $cepatlakoo_image_thumb ) .'" title="' . get_the_title() . '">';
                                                        $cepatlakoo_slider .= '</a>';
                                                        if ( $cepatlakoo_trigger ) {
                                                            $cepatlakoo_slider .= '<a href="' . esc_url( $cepatlakoo_button_url ) . '" class="cmc-btn btn primary-bg large-btn" target="' . esc_attr( $cepatlakoo_button_url_open ) . '">' . esc_attr( $cepatlakoo_button_text ) . '</a>';
                                                        }
                                                    $cepatlakoo_slider .= '</div>';

                                                if ( $cepatlakoo_trigger ) {
                                                    $cepatlakoo_slider .= '<div class="detail">';
                                                        $cepatlakoo_slider .= '<h3><a href="' . esc_url( $cepatlakoo_button_url ) . '" target="' . esc_attr( $cepatlakoo_button_url_open ) . '">' . esc_attr( $cepatlakoo_head_title ) . '</a></h3>';
                                                        $cepatlakoo_slider .= '<p>' . esc_attr ( $cepatlakoo_short_desc ) . '</p>';
                                                    $cepatlakoo_slider .= '</div>';
                                                }

                                                $cepatlakoo_slider .= '<div class="slide-carousel-item">' . esc_attr( $cepatlakoo_number_ofpost ) . '</div>';

                                            $cepatlakoo_slider .= '</div>';

                                        } elseif ($type == 'fullwidth') {
                                            $cepatlakoo_slider .= '<div class="sc-item">';
                                                if ( $cepatlakoo_trigger ) {
                                                    $cepatlakoo_slider .= '<div class="sc-item-content">';
                                                        $cepatlakoo_slider .= '<div class="table">';
                                                            $cepatlakoo_slider .= '<div class="tablecell">';

                                                                $cepatlakoo_slider .= '<div class="sc-item-inner">';
                                                                    $cepatlakoo_slider .= '<h3>' . esc_attr( $cepatlakoo_head_title ) . '</h3>';
                                                                    $cepatlakoo_slider .= '<p>' . esc_attr ( $cepatlakoo_short_desc ) . '</p>';
                                                                    $cepatlakoo_slider .= '<a href="' . esc_url( $cepatlakoo_button_url ) . '" class="btn primary-bg large-btn" target="' . esc_attr( $cepatlakoo_button_url_open ) . '">' . esc_attr( $cepatlakoo_button_text ) . '</a>';
                                                                $cepatlakoo_slider .= '</div>';

                                                            $cepatlakoo_slider .= '</div>';
                                                        $cepatlakoo_slider .= '</div>';
                                                    $cepatlakoo_slider .= '</div>';
                                                }

                                                $cepatlakoo_slider .= '<img src="' . esc_url( $cepatlakoo_image_thumb ) .'" title="' . get_the_title() . '">';

                                            $cepatlakoo_slider .= '</div>';
                                        }
                                    }
                                } else {
                                    if ( $type == 'carousel' ) {
                                        $cepatlakoo_slider .= esc_html__( 'Slideshow carousel empty.', 'cepatlakoo' );
                                    } elseif ( $type == 'fullwidth' ) {
                                        $cepatlakoo_slider .= esc_html__( 'Slideshow full width empty.', 'cepatlakoo' );
                                    }
                                }
                    $cepatlakoo_slider .= '</section>';
                endwhile;
                wp_reset_postdata();
            else:
                $cepatlakoo_slider = '<p>';
                $cepatlakoo_slider .=  esc_html__( 'Slideshow not found.', 'cepatlakoo' );
                $cepatlakoo_slider .= '</p>';
            endif;
        return $cepatlakoo_slider;
    }
}
add_shortcode('cepatlakoo-slideshow', 'cepatlakoo_slideshow_shortcode');
