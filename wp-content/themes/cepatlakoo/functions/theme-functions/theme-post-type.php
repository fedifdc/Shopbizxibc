<?php
/**
 * Theme Post Type
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

// Custom Slideshow Post Type
if ( ! function_exists( 'cepatlakoo_slideshow_post_type') ) {
    function cepatlakoo_slideshow_post_type() {
        $labels = array(
            'name'                  => esc_html__( 'Slideshow', 'cepatlakoo' ),
            'singular_name'         => esc_html__( 'Slideshow', 'cepatlakoo' ),
            'add_new'               => esc_html__( 'Add New', 'cepatlakoo' ),
            'add_new_item'          => esc_html__( 'Add New Slideshow', 'cepatlakoo' ),
            'edit_item'             => esc_html__( 'Edit Slideshow', 'cepatlakoo' ),
            'new_item'              => esc_html__( 'New Slideshow', 'cepatlakoo' ),
            'view_item'             => esc_html__( 'View Slideshow', 'cepatlakoo' ),
            'search_items'          => esc_html__( 'Search Slideshow', 'cepatlakoo' ),
            'not_found'             => esc_html__( 'Nothing found', 'cepatlakoo' ),
            'not_found_in_trash'    => esc_html__( 'Nothing found in Trash', 'cepatlakoo' ),
            'parent_item_colon'     => ''
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'cl_slideshow', 'with_front' => true, 'feed' => false ),
            'menu_icon'             => 'dashicons-images-alt2',
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'menu_position'         => 30,
            'supports'              => array('title','author')
        );

        register_post_type( 'cl_slideshow', $args );
    }
}
add_action('init', 'cepatlakoo_slideshow_post_type');

// Custom countdown post type
if ( ! function_exists( 'cepatlakoo_countdown_post_type') ) {
    function cepatlakoo_countdown_post_type() {
        $labels = array(
            'name'                  => esc_html__( 'Countdown Timer', 'cepatlakoo' ),
            'singular_name'         => esc_html__( 'Countdown Timer', 'cepatlakoo' ),
            'add_new'               => esc_html__( 'Add New', 'cepatlakoo' ),
            'add_new_item'          => esc_html__( 'Add New Countdown Timer', 'cepatlakoo' ),
            'edit_item'             => esc_html__( 'Edit Countdown Timer', 'cepatlakoo' ),
            'new_item'              => esc_html__( 'New Countdown Timer', 'cepatlakoo' ),
            'view_item'             => esc_html__( 'View Countdown Timer', 'cepatlakoo' ),
            'search_items'          => esc_html__( 'Search Countdown Timer', 'cepatlakoo' ),
            'not_found'             => esc_html__( 'Nothing found', 'cepatlakoo' ),
            'not_found_in_trash'    => esc_html__( 'Nothing found in Trash', 'cepatlakoo' ),
            'parent_item_colon'     => ''
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'cl-countdown-timer', 'with_front' => true, 'feed' => false ),
            'menu_icon'             => 'dashicons-clock',
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'menu_position'         => 30,
            'supports'              => array('title', 'author')
        );

        register_post_type( 'cl_countdown_timer', $args );
    }
}
add_action('init', 'cepatlakoo_countdown_post_type');

// ONLY Story CUSTOM TYPE POSTS
add_filter('manage_cl_countdown_timer_posts_columns', 'cepatlakoo_columns_head_only_cl_countdown_timer', 10);
add_action('manage_cl_countdown_timer_posts_custom_column', 'cepatlakoo_columns_content_only_cl_countdown_timer', 10, 2);

// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
if ( ! function_exists( 'cepatlakoo_columns_head_only_cl_countdown_timer') ) {
    function cepatlakoo_columns_head_only_cl_countdown_timer($defaults) {
        $defaults['author'] = esc_html__( 'Author', 'cepatlakoo' );
        $defaults['ct_shortcode'] = esc_html__( 'Shortcode', 'cepatlakoo' );

        $new = array();

        foreach($defaults as $key=>$value) {
            if ($key=='date') {
               $new['author'] = $author;
               $new['ct_shortcode'] = $ct_shortcode;
            }
            $new[$key]=$value;
        }

        return $new;
    }
}

if ( ! function_exists( 'cepatlakoo_columns_content_only_cl_countdown_timer') ) {
    function cepatlakoo_columns_content_only_cl_countdown_timer($column_name, $post_ID) {
        if ($column_name == 'ct_shortcode') {
            echo '<span class="cl-shortcode">';
                $countdown_type = get_post_meta( $post_ID, 'cl_countdown_type', true );
                $ct_shortcode_fieldvalue = sprintf( '[cepatlakoo-countdown id="%1$d" type="%2$s"]', $post_ID, $countdown_type );
                if ($ct_shortcode_fieldvalue)
                    update_post_meta( $post_ID, 'cl_countdown_shortcode', $ct_shortcode_fieldvalue);

                    echo get_post_meta( $post_ID, 'cl_countdown_shortcode', true);
            echo '</span>';
        }
    }
}

function cepatlakoo_save_cl_countdown_shortcode_meta($post_id, $post) {

    global $typenow;

    $countdown_meta['cl_countdown_shortcode'] = (isset($_POST['cl_countdown_shortcode']) ? $_POST['cl_countdown_shortcode']    : '');

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post->ID;

    if ( 'cl_countdown_timer' == $post->post_type ) {
        if ( !current_user_can( 'edit_page', $post->ID ) )
            return $post->ID;
    } else {
    if ( !current_user_can( 'edit_post', $post->ID ) )
        return $post->ID;
    }
    
    if( !empty($typenow) && $typenow != 'shop_order' && $typenow == 'cl_countdown_timer'){
        // Authentication passed now we save the data
        if (!isset($_POST['cl_countdown_shortcode'])) {
            $countdown_type = get_post_meta( $post->ID, 'cl_countdown_type', true );
            $ct_shortcode_fieldvalue = sprintf( '[cepatlakoo-countdown id="%1$d" type="%2$s"]', $post->ID, $countdown_type );
            if ($ct_shortcode_fieldvalue)
                update_post_meta( $post->ID, 'cl_countdown_shortcode', $ct_shortcode_fieldvalue);
            else
                delete_post_meta( $post->ID, 'cl_countdown_shortcode');

            return $ct_shortcode_fieldvalue;
        }
    }
}
add_action('save_post', 'cepatlakoo_save_cl_countdown_shortcode_meta', 1, 2); // save the custom fields

//Add Thumbnail Preview to Custom Post Type Elementor Lib

if ( ! function_exists( 'cepatlakoo_edit_cass_columns') ) {
    function cepatlakoo_edit_cass_columns( $columns ) {
        $columns['featured_image'] = esc_html__( 'Thumbnail' , 'cepatlakoo' );
        return $columns;
    }
}
add_filter( 'manage_edit-elementor_library_columns', 'cepatlakoo_edit_cass_columns' ) ;

if ( ! function_exists( 'cepatlakoo_manage_cass_columns') ) {
    function cepatlakoo_manage_cass_columns( $column, $post_id ) {
        if ($column == 'featured_image') {
            $post_thumbnail_id = get_post_thumbnail_id($post_id);
            if ($post_thumbnail_id) {
                $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
            } else {
                $post_thumbnail_img = '';
            }
            if ($post_thumbnail_img){
                echo '<img src="' . esc_url( $post_thumbnail_img[0] ) . '" />';
            }
        }
    }
}
add_action( 'manage_elementor_library_posts_custom_column', 'cepatlakoo_manage_cass_columns', 10, 2 );
?>