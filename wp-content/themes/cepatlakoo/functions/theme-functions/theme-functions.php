<?php

/**
 * Display Page Title
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_page_title')) {
    function cepatlakoo_page_title()
    {
        global $wp_query;
        $title = '';
        if (is_category()) :
            $title = sprintf(esc_html__('%s Category Archives', 'cepatlakoo'), single_cat_title('', false));
        elseif (is_tag()) :
            $title = sprintf(esc_html__('%s Tag Archives', 'cepatlakoo'), single_cat_title('', false));
        elseif (is_day()) :
            $title = sprintf(esc_html__('%s Daily Archives', 'cepatlakoo'), single_cat_title('', false));
        elseif (is_month()) :
            $title = sprintf(esc_html__('%s Monthly Archives', 'cepatlakoo'), single_cat_title('', false));
        elseif (is_year()) :
            $title = sprintf(esc_html__('%s Yearly Archives', 'cepatlakoo'), single_cat_title('', false));
        elseif (is_author()) :
            $author = get_user_by('slug', get_query_var('author_name'));
            $title = esc_html__('Author Archives', 'cepatlakoo');
        elseif (is_search()) :
            if ($wp_query->found_posts) {
                $title .= sprintf(esc_html__('Search results for: "%s"', 'cepatlakoo'), esc_attr(get_search_query()));
            } else {
                $title .= sprintf(esc_html__('Search results for: "%s"', 'cepatlakoo'), esc_attr(get_search_query()));
            }
        elseif (is_404()) :
            $title = esc_html__('Not Found', 'cepatlakoo');
        elseif (is_singular('post') || is_home() || is_page_template('template-blog.php')) :
            $title = get_the_title(get_the_ID());
        elseif (is_archive()) :
            $title = esc_html__('Archives', 'cepatlakoo');
        else :
            if (has_custom_logo()) {
                $title = wp_trim_words(get_the_title(), 10, ' ...');
            } else {
                $title = wp_trim_words(get_the_title(), 10, ' ...');
            }
        endif;

        $cepatlakoo_page_title = $title;

        echo $cepatlakoo_page_title;
    }
}

/**
 * Function to display custom logo
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_logo')) {
    function cepatlakoo_logo()
    {
        global $cl_options;

        $logo_type = !empty($cl_options['cepatlakoo_logo_type']) ? $cl_options['cepatlakoo_logo_type'] : '';
        $logo_url = !empty($cl_options['cepatlakoo_logo_image']['url']) ? $cl_options['cepatlakoo_logo_image']['url'] : '';
        $logo_height = !empty($cl_options['cepatlakoo_logo_height']) ? $cl_options['cepatlakoo_logo_height'] : '';
?>
        <?php if ($logo_type == 'image') : ?>
            <div id="logo" class="custom-logo">
                <a href="<?php echo home_url(); ?>"><img style="height: <?php echo $logo_height; ?>px" src="<?php echo $logo_url; ?>" /></a>
            </div>
        <?php elseif ($logo_type == 'image-text') : ?>
            <div id="logo" class="logo-image-text">
                <h2 class="site-title">

                    <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <img style="height: <?php echo $logo_height; ?>px" src="<?php echo $logo_url; ?>" />
                        <span class="title"><?php echo esc_attr(get_bloginfo('name')); ?></span>
                    </a>
                </h2>
            </div>
        <?php else : ?>
            <div id="logo" class="logo-title">
                <h2 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <?php echo esc_attr(get_bloginfo('name')); ?>
                    </a>
                </h2>
            </div>
        <?php endif; ?>
    <?php
    }
}

function cepatlakoo_customize_register($wp_customize)
{
    global $wp_customize;

    $wp_customize->remove_control('custom_logo');
    $wp_customize->remove_control('blogname');
    $wp_customize->remove_control('blogdescription');
}
add_action('customize_register', 'cepatlakoo_customize_register', 11);

/**
 * Function for addding itemprop attributes to menu anchor
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */

function add_menu_atts($atts, $item, $args)
{
    $atts['itemprop'] = 'url';
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_atts', 10, 3);

/**
 * Display Entry Meta
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_entry_meta')) {
    function cepatlakoo_entry_meta()
    {
        global $post;
    ?>
        <div class="entry-meta">
            <span><a href="<?php echo get_author_posts_url($post->post_author); ?>" itemprop="name">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24.6074 22.6221C25.9355 23.0811 27.1318 23.7256 28.1963 24.5557C29.2705 25.376 30.1787 26.3281 30.9209 27.4121C31.6729 28.4961 32.249 29.6826 32.6494 30.9717C33.0498 32.2607 33.25 33.6035 33.25 35H31.375C31.375 33.3984 31.0869 31.9141 30.5107 30.5469C29.9443 29.1699 29.1582 27.9785 28.1523 26.9727C27.1465 25.9668 25.9551 25.1807 24.5781 24.6143C23.2109 24.0381 21.7266 23.75 20.125 23.75C19.0801 23.75 18.0742 23.8818 17.1074 24.1455C16.1406 24.4092 15.2373 24.7852 14.3975 25.2734C13.5674 25.752 12.8105 26.333 12.127 27.0166C11.4531 27.6904 10.8721 28.4473 10.3838 29.2871C9.90527 30.1172 9.53418 31.0156 9.27051 31.9824C9.00684 32.9492 8.875 33.9551 8.875 35H7C7 33.5938 7.20508 32.251 7.61523 30.9717C8.02539 29.6826 8.60645 28.501 9.3584 27.4268C10.1104 26.3525 11.0186 25.4053 12.083 24.585C13.1572 23.7646 14.3535 23.1152 15.6719 22.6367C14.9102 22.2266 14.2266 21.7285 13.6211 21.1426C13.0156 20.5566 12.498 19.9072 12.0684 19.1943C11.6484 18.4717 11.3213 17.7051 11.0869 16.8945C10.8623 16.0742 10.75 15.2344 10.75 14.375C10.75 13.0762 10.9941 11.8604 11.4824 10.7275C11.9707 9.58496 12.6396 8.58887 13.4893 7.73926C14.3389 6.88965 15.3301 6.2207 16.4629 5.73242C17.6055 5.24414 18.8262 5 20.125 5C21.4238 5 22.6396 5.24414 23.7725 5.73242C24.915 6.2207 25.9111 6.88965 26.7607 7.73926C27.6104 8.58887 28.2793 9.58496 28.7676 10.7275C29.2559 11.8604 29.5 13.0762 29.5 14.375C29.5 15.2344 29.3828 16.0693 29.1484 16.8799C28.9238 17.6904 28.5967 18.4521 28.167 19.165C27.7471 19.8779 27.2344 20.5322 26.6289 21.1279C26.0332 21.7139 25.3594 22.2119 24.6074 22.6221ZM12.625 14.375C12.625 15.4102 12.8203 16.3818 13.2109 17.29C13.6113 18.1982 14.1484 18.9941 14.8223 19.6777C15.5059 20.3516 16.3018 20.8887 17.21 21.2891C18.1182 21.6797 19.0898 21.875 20.125 21.875C21.1602 21.875 22.1318 21.6797 23.04 21.2891C23.9482 20.8887 24.7393 20.3516 25.4131 19.6777C26.0967 18.9941 26.6338 18.1982 27.0244 17.29C27.4248 16.3818 27.625 15.4102 27.625 14.375C27.625 13.3398 27.4248 12.3682 27.0244 11.46C26.6338 10.5518 26.0967 9.76074 25.4131 9.08691C24.7393 8.40332 23.9482 7.86621 23.04 7.47559C22.1318 7.0752 21.1602 6.875 20.125 6.875C19.0898 6.875 18.1182 7.0752 17.21 7.47559C16.3018 7.86621 15.5059 8.40332 14.8223 9.08691C14.1484 9.76074 13.6113 10.5518 13.2109 11.46C12.8203 12.3682 12.625 13.3398 12.625 14.375Z" fill="#C0C0C0" />
                    </svg>

                    <?php echo get_the_author(); ?></a>
            </span>

            <span itemprop="datePublished" datetime="<?php echo get_the_date('c'); ?>">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M30 7H36V35H4V7H10V5H12V7H28V5H30V7ZM10 9H6V13H34V9H30V11H28V9H12V11H10V9ZM6 33H34V15H6V33Z" fill="#C0C0C0" />
                </svg>

                <?php echo date_i18n('F j, Y', strtotime(get_the_date('Y-m-d'), false)); ?>
            </span>

            <meta itemprop="dateModified" content="<?php echo get_the_modified_date('c'); ?>">

            <span>
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M37.5 15C37.8385 15 38.1576 15.0651 38.457 15.1953C38.7695 15.3255 39.0365 15.5078 39.2578 15.7422C39.4922 15.9635 39.6745 16.224 39.8047 16.5234C39.9349 16.8229 40 17.1419 40 17.4805C40 17.8711 39.9089 18.2487 39.7266 18.6133L31.543 35H0V7.5C0 7.14844 0.0651042 6.82292 0.195312 6.52344C0.325521 6.22396 0.501302 5.96354 0.722656 5.74219C0.957031 5.50781 1.22396 5.32552 1.52344 5.19531C1.82292 5.0651 2.14844 5 2.5 5H9.375C9.96094 5 10.4622 5.0651 10.8789 5.19531C11.3086 5.3125 11.6862 5.46224 12.0117 5.64453C12.3503 5.82682 12.6497 6.02865 12.9102 6.25C13.1706 6.47135 13.431 6.67318 13.6914 6.85547C13.9648 7.03776 14.2513 7.19401 14.5508 7.32422C14.8633 7.44141 15.2214 7.5 15.625 7.5H30C30.3516 7.5 30.6771 7.5651 30.9766 7.69531C31.276 7.82552 31.5365 8.00781 31.7578 8.24219C31.9922 8.46354 32.1745 8.72396 32.3047 9.02344C32.4349 9.32292 32.5 9.64844 32.5 10V15H37.5ZM2.5 28.457L8.53516 16.3867C8.75651 15.957 9.0625 15.6185 9.45312 15.3711C9.85677 15.1237 10.2995 15 10.7812 15H30V10H15.625C15.0391 10 14.5312 9.94141 14.1016 9.82422C13.6849 9.69401 13.3073 9.53776 12.9688 9.35547C12.6432 9.17318 12.3503 8.97135 12.0898 8.75C11.8294 8.52865 11.5625 8.32682 11.2891 8.14453C11.0286 7.96224 10.7422 7.8125 10.4297 7.69531C10.1302 7.5651 9.77865 7.5 9.375 7.5H2.5V28.457ZM37.5 17.5H10.7812L3.28125 32.5H30L37.5 17.5Z" fill="#c0c0c0" />
                </svg>
                <?php the_category(', '); ?>
            </span>
        </div>
        <?php
    }
}

/**
 * Function to load comment list
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_comment_list')) {
    function cepatlakoo_comment_list($comment, $args, $depth)
    {
        global $post;
        $author_post_id = $post->post_author;
        $GLOBALS['comment'] = $comment;

        // Allowed html tags will be display
        $allowed_html = array(
            'a' => array('href' => array(), 'title' => array()),
            'abbr' => array('title' => array()),
            'acronym' => array('title' => array()),
            'strong' => array(),
            'b' => array(),
            'blockquote' => array('cite' => array()),
            'cite' => array(),
            'code' => array(),
            'del' => array('datetime' => array()),
            'em' => array(),
            'i' => array(),
            'q' => array('cite' => array()),
            'strike' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array()
        );

        switch ($comment->comment_type):
            case '': ?>
                <li id="comment-<?php comment_ID() ?>" class="clearfix">
                    <div class="thumbnail">
                        <?php echo get_avatar($comment, 70); ?>
                    </div>
                    <div class="detail">
                        <h5><?php comment_author(); ?></h5>
                        <?php
                        if ($comment->comment_approved == '0') :
                        ?>
                            <p class="moderate"><?php esc_html_e('Your comment is now awaiting moderation before it will appear on this post.', 'cepatlakoo'); ?></p>
                        <?php
                        endif;
                        echo apply_filters('comment_text', wp_kses(get_comment_text(), $allowed_html));
                        ?>

                        <div class="comment-footer">
                            <span class="meta">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30 7H36V35H4V7H10V5H12V7H28V5H30V7ZM10 9H6V13H34V9H30V11H28V9H12V11H10V9ZM6 33H34V15H6V33Z" fill="#C0C0C0" />
                                </svg>
                                <?php echo get_comment_date('F d, Y - h.i a'); ?>
                            </span>
                            <p class="replies"><span><span><?php echo comment_reply_link(array('reply_text' => esc_html__('Reply', 'cepatlakoo'), 'depth' => $depth, 'max_depth' => $args['max_depth']));  ?></span></p>
                        </div>
                    </div>
                </li>
            <?php
            case 'comment': ?>
                <li id="comment-<?php comment_ID() ?>" class="clearfix">
                    <div class="thumbnail">
                        <?php echo get_avatar($comment, 70); ?>
                    </div>
                    <div class="detail">
                        <h5><?php comment_author(); ?></h5>
                        <?php
                        if ($comment->comment_approved == '0') :
                        ?>
                            <p class="moderate"><?php esc_html_e('Your comment is now awaiting moderation before it will appear on this post.', 'cepatlakoo'); ?></p>
                        <?php
                        endif;
                        echo apply_filters('comment_text', wp_kses(get_comment_text(), $allowed_html));
                        ?>

                        <div class="comment-footer">
                            <span class="meta">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30 7H36V35H4V7H10V5H12V7H28V5H30V7ZM10 9H6V13H34V9H30V11H28V9H12V11H10V9ZM6 33H34V15H6V33Z" fill="#C0C0C0" />
                                </svg>
                                <?php echo get_comment_date('F d, Y - h.i a'); ?>
                            </span>
                            <p class="replies"><span><span><?php echo comment_reply_link(array('reply_text' => esc_html__('Reply', 'cepatlakoo'), 'depth' => $depth, 'max_depth' => $args['max_depth']));  ?></span></p>
                        </div>
                    </div>
                </li>
            <?php
                break;
            case 'pingback':
            case 'trackback':
            ?>
                <li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
                    <div class="detail">
                        <div class="author">
                            <a href="<?php comment_author_url() ?>"><?php esc_html_e('Pingback', 'cepatlakoo'); ?></a>
                        </div>
                        <h5>
                            <?php comment_author(); ?>
                        </h5>
                        <div class="meta">
                            <?php comment_date();
                            echo ' - ';
                            comment_time(); ?>
                            <span class="edit-link"><i class="fa fa-edit"></i><?php edit_comment_link(esc_html__(' Edit Comment', 'cepatlakoo'), '', ''); ?></span>
                        </div>
                        <hr class="comment-line" />
                    </div>
                </li>
        <?php
                break;
        endswitch;
    }
}

/**
 * Function to check option FB Pixel, SEO and Google Analytics
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_check_fb_pixel')) {
    function cepatlakoo_check_fb_pixel()
    {
        global $cl_options;
        $is_admin = current_user_can('administrator');

        $cepatlakoo_facebook_pixel_id = (!empty($cl_options['cepatlakoo_facebook_pixel_id']) && ((isset($cl_options['cepatlakoo_facebook_pixel_admin']) && $cl_options['cepatlakoo_facebook_pixel_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
        $cepatlakoo_tiktok_pixel_id = (!empty($cl_options['cepatlakoo_tiktok_pixel_id']) && ((isset($cl_options['cepatlakoo_tiktok_pixel_admin']) && $cl_options['cepatlakoo_tiktok_pixel_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_tiktok_pixel_id'] : '';
        $cepatlakoo_google_analytics_tracking = (!empty($cl_options['cepatlakoo_google_analytics_tracking']) && ((isset($cl_options['cepatlakoo_google_analytics_admin']) && $cl_options['cepatlakoo_google_analytics_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_google_analytics_tracking'] : '';
        $cepatlakoo_seo_trigger = !empty($cl_options['cepatlakoo_seo_trigger']) ? $cl_options['cepatlakoo_seo_trigger'] : '';
        
        if ($cepatlakoo_seo_trigger) {
            add_action('wp_head', 'cepatlakoo_search_engine_optimize', 3);
            add_filter('pre_get_document_title', 'cepatlakoo_custom_title', 10);
        }
        if ($cepatlakoo_facebook_pixel_id) {
            add_action('wp_head', 'cepatlakoo_fb_pixel', 99);
        }
        if ($cepatlakoo_tiktok_pixel_id) {
            add_action('wp_head', 'cepatlakoo_tt_pixel', 99);
        }
        if ($cepatlakoo_google_analytics_tracking) {
            add_action('wp_head', 'cepatlakoo_google_analytics_tracking', 98);
        }
    }
}
add_action('init', 'cepatlakoo_check_fb_pixel');

/**
 * Function to add class to confirmation page
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_add_class_confirmation')) {
    function cepatlakoo_add_class_confirmation($classes)
    {
        global $cl_options;

        $cepatlakoo_select_confirmation = !empty($cl_options['cepatlakoo_select_confirmation']) ? $cl_options['cepatlakoo_select_confirmation'] : '';
        $cepatlakoo_purchase_confirmation = !empty($cl_options['cepatlakoo_purchase_confirmation']) ? $cl_options['cepatlakoo_purchase_confirmation'] : '';

        if (!empty($cepatlakoo_select_confirmation)) {
            if (is_page($cepatlakoo_select_confirmation)) {
                $classes[] = 'confirmation-page wcfb-' . $cepatlakoo_purchase_confirmation;
            }
        }

        return $classes;
    }
}
add_filter('body_class', 'cepatlakoo_add_class_confirmation');

/**
 * Function to check google analytics Tracking
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_google_analytics_tracking')) {
    function cepatlakoo_google_analytics_tracking()
    {
        global $cl_options;
        $is_admin = current_user_can('administrator');

        $cepatlakoo_google_analytics_tracking = (!empty($cl_options['cepatlakoo_google_analytics_tracking']) && ((isset($cl_options['cepatlakoo_google_analytics_admin']) && $cl_options['cepatlakoo_google_analytics_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_google_analytics_tracking'] : ''; ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php esc_attr_e($cepatlakoo_google_analytics_tracking); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', '<?php esc_attr_e($cepatlakoo_google_analytics_tracking); ?>');
        </script>
        <?php
    }
}

/**
 * Function to Form top
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_comment_form_top')) {
    function cepatlakoo_comment_form_top()
    {
    }
}
add_action('comment_form_top', 'cepatlakoo_comment_form_top');

if (!function_exists('cepatlakoo_comment_form_bottom')) {
    function cepatlakoo_comment_form_bottom()
    {
    }
}
add_action('comment_form', 'cepatlakoo_comment_form_bottom', 1);

/**
 * Function to Form Bottom
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_comment_field_to_bottom')) {
    function cepatlakoo_comment_field_to_bottom($fields)
    {
        $comment_field = $fields['comment'];
        unset($fields['comment']);
        $fields['comment'] = $comment_field;
        return $fields;
    }
}
add_filter('comment_form_fields', 'cepatlakoo_comment_field_to_bottom');

/**
 * Display Post Pagination in Blog Page
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_display_pagination')) {
    function cepatlakoo_display_pagination()
    {
        global $wp_query;
        if ($wp_query->max_num_pages > 1) : ?>
            <div class="pagination older-newer">
                <?php
                if (function_exists('wp_pagenavi')) {
                    wp_pagenavi(); // pagenavi
                } else {
                    previous_posts_link('&#8592;' . esc_html__('Newer post', 'cepatlakoo'));
                    next_posts_link(esc_html__('Older Posts', 'cepatlakoo') . '&#8594;');
                }
                ?>
            </div>
        <?php endif;
    }
}

/**
 * Display Share Buttons
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_display_share_buttons')) {
    function cepatlakoo_display_share_buttons()
    {
        global $cl_options;

        $cepatlakoo_share_button = !empty($cl_options['cepatlakoo_share_button']) ? $cl_options['cepatlakoo_share_button'] : '';

        if ($cepatlakoo_share_button == 1) : ?>
            <div class="widget article-widget">
                <div class="social-sharing article-share-widget">
                    <h4 class="widget-title"><?php esc_html_e('Share this article', 'cepatlakoo'); ?></h4>
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
            </div>
        <?php endif;
    }
}

/**
 * Function to trigger Facebook Pixel
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_fb_pixel')) {
    function cepatlakoo_fb_pixel()
    {
        global $cl_options;

        $is_admin = current_user_can('administrator');

        $cepatlakoo_facebook_pixel_id = (!empty($cl_options['cepatlakoo_facebook_pixel_id']) && ((isset($cl_options['cepatlakoo_facebook_pixel_admin']) && $cl_options['cepatlakoo_facebook_pixel_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
        $fb_pixels = $cepatlakoo_facebook_pixel_id;
        if (!empty($cepatlakoo_facebook_pixel_id) || count($fb_pixels) > 0) : ?>
            <!-- Facebook Pixel Code -->
            <script>
                ! function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window,
                    document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
                <?php if (!is_array($cepatlakoo_facebook_pixel_id)) : ?>
                    fbq('init', '<?php echo esc_attr($cepatlakoo_facebook_pixel_id); ?>'); // Insert your pixel ID here.
                    <?php elseif (is_array($fb_pixels) && count($fb_pixels) > 0) :
                    foreach ($fb_pixels as $fb_p) : $fb_p = trim($fb_p); ?>
                        fbq('init', '<?php echo esc_attr($fb_p); ?>'); // Insert your pixel ID here.
                    <?php endforeach; ?>
                <?php endif; ?>
                fbq('track', 'ViewContent');
                fbq('track', 'PageView');
            </script>
            <noscript>
                <?php if (!is_array($cepatlakoo_facebook_pixel_id)) : ?>
                    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $cepatlakoo_facebook_pixel_id; ?>&ev=PageView&noscript=1" />
                    <?php elseif (is_array($fb_pixels) && count($fb_pixels) > 0) :
                    foreach ($fb_pixels as $fb_p) : $fb_p = trim($fb_p); ?>
                        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_p; ?>&ev=PageView&noscript=1" />
                    <?php endforeach; ?>
                <?php endif; ?>
            </noscript>
            <!-- DO NOT MODIFY -->
            <!-- End Facebook Pixel Code -->
        <?php endif;
    }
}

/**
 * Function to trigger TikTok Pixel
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.6.7
 */
if (!function_exists('cepatlakoo_tt_pixel')) {
    function cepatlakoo_tt_pixel()
    {
        global $cl_options;

        $cepatlakoo_tiktok_pixel_id = (!empty($cl_options['cepatlakoo_tiktok_pixel_id']) && ((isset($cl_options['cepatlakoo_tiktok_pixel_admin']) && $cl_options['cepatlakoo_tiktok_pixel_admin'] == 0) || !$is_admin)) ? $cl_options['cepatlakoo_tiktok_pixel_id'] : '';
        $tt_pixels = $cepatlakoo_tiktok_pixel_id;
        
        if (!empty($cepatlakoo_tiktok_pixel_id) || count($tt_pixels) > 0) : ?>
            <!-- Tiktok Pixel Code -->
            <script>
                !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

                //Part 2
                <?php if (!is_array($cepatlakoo_tiktok_pixel_id)) : ?>
                    ttq.load(<?php echo esc_attr($tt_pixels); ?>);
                <?php elseif (is_array($tt_pixels) && count($tt_pixels) > 0) :
                    foreach ($tt_pixels as $tt_p) : $tt_p = trim($tt_p); ?>
                        ttq.load('<?php echo esc_attr($tt_p); ?>');
                    <?php endforeach; ?>
                <?php endif; ?>

                ttq.page();
                }(window, document, 'ttq');
                </script>
            <!-- DO NOT MODIFY -->
            <!-- End Tiktok Pixel Code -->
        <?php endif;
    }
}

/**
 * Cepatlakoo gallery slider function
 *
 * @package WordPress
 * @subpackage Cepatlakoo
 * @since Cepatlakoo 1.0.0
 */
if (!function_exists('cepatlakoo_gallery')) {
    function cepatlakoo_gallery($content, $attr)
    {
        $post = get_post();
        static $instance = 0;
        $instance++;

        $html5 = current_theme_supports('html5', 'gallery');
        if (isset($attr['orderby'])) :
            $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
            if (!$attr['orderby'])
                unset($attr['orderby']);
        endif;

        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'size'       => 'thumbnail',
            'columns'    => 3,
            'include'    => '',
            'exclude'    => ''
        ), $attr));

        $id = intval($id);
        if ('RAND' == $order)
            $orderby = 'none';

        if (!empty($include)) {
            $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif (!empty($exclude)) {
            $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        } else {
            $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        }

        $size = 'thumbnail';

        if (empty($attachments))
            return '';

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment)
                $output .= wp_get_attachment_image($att_id, $size) . "\n";
            return $output;
        }

        if (!empty($attr['link'])) {
            $typelink = "file";
        } else {
            $typelink = "attachment";
        }

        $selector = "gallery-{$instance}";
        $size_class = sanitize_html_class($size);
        $output = "<div id='gallery-{$instance}' class='gallery cepatlakoo-gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} $typelink'>";
        $i = 0;
        foreach ($attachments as $id => $attachment) {
            if (!empty($attr['link']) && 'file' === $attr['link'])
                $image_output = wp_get_attachment_link($id, $size);
            elseif (!empty($attr['link']) && 'none' === $attr['link'])
                $image_output = wp_get_attachment_link($id, $size);
            else
                $image_output =  wp_get_attachment_link($id, $size, true, false);
            $image_meta  = wp_get_attachment_metadata($id);
            $orientation = '';
            if (isset($image_meta['height'], $image_meta['width'])) {
                $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
            }
            $output .= "<dl class='gallery-item'>";
            $output .= "
            <dt class='gallery-icon {$orientation}'>
                $image_output
            </dt>";
            $output .= '<figcaption class="wp-caption-text gallery-caption" id="' . $attachment->ID . '">' . $attachment->post_excerpt . '</figcaption>';
            $output .= "</dl>";
            if (!$html5 && $columns > 0 && ++$i % $columns == 0) {
                $output .= '<br style="clear: both" />';
            }
        }
        $output .= "</div>";
        return $output;
    }
}
add_filter('post_gallery', 'cepatlakoo_gallery', 10, 2);

/**
 * Function to display Open Graph
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_open_graph')) {
    function cepatlakoo_open_graph()
    {
        global $cl_options;

        $cepatlakoo_open_graph_trigger = !empty($cl_options['cepatlakoo_open_graph_trigger']) ? $cl_options['cepatlakoo_open_graph_trigger'] : '';
        $desc = @get_the_excerpt();
        if (defined('DOING_AJAX') && DOING_AJAX && have_posts()) {
            while (have_posts()) {
                the_post();
                $desc   = get_the_excerpt();
            }
        }

        // get the values from meta box
        $cepatlakoo_facebook_title_og = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_facebook_title_og', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_facebook_title_og', true) : get_the_title();
        $cepatlakoo_facebook_desc_og = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_facebook_desc_og', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_facebook_desc_og', true) : $desc;
        $cepatlakoo_facebook_image_og = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_facebook_image_og', true)) ? wp_get_attachment_url(get_post_meta(get_the_ID(), 'cepatlakoo_facebook_image_og', true)) : get_the_post_thumbnail_url();
        if ($cepatlakoo_open_graph_trigger == true) : ?>
            <meta property="og:title" content="<?php echo wp_strip_all_tags($cepatlakoo_facebook_title_og); ?>" />
            <meta property="og:url" content="<?php echo get_permalink(get_the_ID()); ?>" />
            <?php if ($cepatlakoo_facebook_desc_og) : ?>
                <meta property="og:description" content="<?php echo wp_strip_all_tags($cepatlakoo_facebook_desc_og); ?>" />
            <?php endif; ?>
            <?php if ($cepatlakoo_facebook_image_og) : ?>
                <meta property="og:image" content="<?php echo $cepatlakoo_facebook_image_og; ?>" />
            <?php endif; ?>
            <?php endif;
    }
}
add_action('wp_head', 'cepatlakoo_open_graph', 5);

/**
 * Function to Set Search Engine
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_search_engine_optimize')) {
    function cepatlakoo_search_engine_optimize()
    {
        $cepatlakoo_seo_title = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_title_text', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_title_text', true) : '';
        $cepatlakoo_seo_desc = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_desc', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_desc', true) : get_the_excerpt();
        $cepatlakoo_seo_keyword = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_keyword', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_keyword', true) : '';
        $cepatlakoo_seo_robotindex = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_robotindex', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_robotindex', true) : '';
        $cepatlakoo_seo_robotfollow = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_robotfollow', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_robotfollow', true) : '';
        $cepatlakoo_trigger_seo = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_trigger_seo', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_trigger_seo', true) : '0';

        if ($cepatlakoo_trigger_seo) :
            if ($cepatlakoo_seo_desc) : ?>
                <meta name="description" content="<?php echo esc_attr($cepatlakoo_seo_desc); ?>" />
            <?php endif; ?>
            <?php if ($cepatlakoo_seo_keyword) : ?>
                <meta name="keywords" content="<?php echo esc_attr($cepatlakoo_seo_keyword); ?>" />
            <?php endif; ?>
            <?php if ($cepatlakoo_seo_robotindex && $cepatlakoo_seo_robotfollow) : ?>
                <meta name="robots" content="[<?php echo esc_attr($cepatlakoo_seo_robotindex); ?>],[<?php echo esc_attr($cepatlakoo_seo_robotfollow); ?>]" />
        <?php endif;
        endif;
    }
}

/**
 * Function to Set Title
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_custom_title')) {
    function cepatlakoo_custom_title($title)
    {
        $cepatlakoo_trigger_seo = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_trigger_seo', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_trigger_seo', true) : '0';
        if ($cepatlakoo_trigger_seo) :
            $cepatlakoo_seo_title = !empty(get_post_meta(get_the_ID(), 'cepatlakoo_seo_title_text', true)) ? get_post_meta(get_the_ID(), 'cepatlakoo_seo_title_text', true) : '';
            return $cepatlakoo_seo_title;
        endif;
    }
}

/**
 * Function to display post navigation
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_post_navigation')) {
    function cepatlakoo_post_navigation()
    {
        global $cl_options;

        $cepatlakoo_get__cur_post_type = get_post_type(get_the_ID());
        $cepatlakoo_post_nav = !empty($cl_options['cepatlakoo_post_nav']) ? $cl_options['cepatlakoo_post_nav'] : '';

        if ($cepatlakoo_post_nav == 1 && $cepatlakoo_get__cur_post_type == "post") :
            global $post;
            $cepatlakoo_display = '<div class="widget article-widget">';
            $cepatlakoo_display .= '<div class="post-navigation"><ul><li>';

            $prevPost = get_previous_post(); // START : Previous Post
            if ($prevPost) {
                $args = array(
                    'posts_per_page' => 1,
                    'post_type'      => 'post',
                    'include' => absint($prevPost->ID)
                );
                $prevPost = get_posts($args);
                foreach ($prevPost as $post) {
                    setup_postdata($post);
                    if (has_post_thumbnail()) {
                        $cepatlakoo_display .= '<div class="thumbnail"><a href="' . get_the_permalink() . '">';
                        $cepatlakoo_display .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('alt' => get_the_title(), 'title' => get_the_title()));
                        $cepatlakoo_display .= '</a></div>';
                    }
                    $cepatlakoo_display .= '<div class="detail">';
                    $cepatlakoo_display .= '<a href="' . get_the_permalink() . '">' . esc_html__('Previous post ', 'cepatlakoo') . '</a>';
                    $cepatlakoo_display .= '<h3><a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' . wp_trim_words(get_the_title(), 10, '...') . '</a></h3>';
                    $cepatlakoo_display .= '</div></li>';
                    wp_reset_postdata();
                } //end foreach
            } // end if
            // END : Previous Post

            $nextPost = get_next_post();  // START : Next Post
            if ($nextPost) {
                $args = array(
                    'posts_per_page' => 1,
                    'post_type'      => 'post',
                    'include' => absint($nextPost->ID)
                );
                $nextPost = get_posts($args);
                foreach ($nextPost as $post) {
                    setup_postdata($post);
                    $cepatlakoo_display .= '<li>';
                    if (has_post_thumbnail()) {
                        $cepatlakoo_display .= '<div class="thumbnail"><a href="' . get_the_permalink() . '">';
                        $cepatlakoo_display .= get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('alt' => get_the_title(), 'title' => get_the_title()));
                        $cepatlakoo_display .= '</a></div>';
                    }
                    $cepatlakoo_display .= '<div class="detail">';
                    $cepatlakoo_display .= '<a href="' . get_the_permalink() . '">' . esc_html__('Next post ', 'cepatlakoo') . '</a>';
                    $cepatlakoo_display .= '<h3><a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' . wp_trim_words(get_the_title(), 10, '...') . '</a></h3>';
                    $cepatlakoo_display .= '</div></li>';
                    wp_reset_postdata();
                } //end foreach
            } // end if
            // END : Next Post

            $cepatlakoo_display .= '</div>';
            $cepatlakoo_display .= '</div>';

            return $cepatlakoo_display;
        endif;
    }
}

/**
 * Change default excerpt more text
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_excerpt_more ')) {
    function cepatlakoo_excerpt_more()
    {
        return '...';
    }
}
add_filter('excerpt_more', 'cepatlakoo_excerpt_more', 999);

/**
 * Change default excerpt length
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_excerpt_length ')) {
    function cepatlakoo_excerpt_length($length)
    {
        global $cl_options;

        $cepatlakoo_post_exceprt_length = !empty($cl_options['cepatlakoo_post_exceprt_length']) ? $cl_options['cepatlakoo_post_exceprt_length'] : '';

        if ($cepatlakoo_post_exceprt_length) {
            return absint($cepatlakoo_post_exceprt_length);
        } else {
            return 65;
        }
    }
}
add_filter('excerpt_length', 'cepatlakoo_excerpt_length', 999);

/**
 * Display Cepatlakoo Top Bar
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_topbar ')) {
    function cepatlakoo_topbar()
    {
        global $cl_options;

        $cepatlakoo_top_bar = !empty($cl_options['cepatlakoo_top_bar']) ? $cl_options['cepatlakoo_top_bar'] : '';
        $cepatlakoo_fb_profile_url = !empty($cl_options['cepatlakoo_fb_profile_url']) ? $cl_options['cepatlakoo_fb_profile_url'] : '';
        $cepatlakoo_tw_profile_url = !empty($cl_options['cepatlakoo_tw_profile_url']) ? $cl_options['cepatlakoo_tw_profile_url'] : '';
        $cepatlakoo_itg_profile_url = !empty($cl_options['cepatlakoo_itg_profile_url']) ? $cl_options['cepatlakoo_itg_profile_url'] : '';
        $cepatlakoo_tiktok_profile_url = !empty($cl_options['cepatlakoo_tiktok_profile_url']) ? $cl_options['cepatlakoo_tiktok_profile_url'] : '';
        $cepatlakoo_customer_care_phone = !empty($cl_options['cepatlakoo_customer_care_phone']) ? $cl_options['cepatlakoo_customer_care_phone'] : '';
        $cepatlakoo_customer_phone_type = !empty($cl_options['cepatlakoo_customer_phone_type']) ? $cl_options['cepatlakoo_customer_phone_type'] : 'phone';
        $cepatlakoo_customer_phone_label = !empty($cl_options['cepatlakoo_customer_phone_label']) ? $cl_options['cepatlakoo_customer_phone_label'] : '';
        $cepatlakoo_top_bar_msg = !empty($cl_options['cepatlakoo_top_bar_msg']) ? $cl_options['cepatlakoo_top_bar_msg'] : '';

        $user = wp_get_current_user();
        ?>

        <?php if ($cepatlakoo_top_bar == 1) : ?>
            <div id="top-bar">
                <div class="container clearfix">
                    <div class="row-bar">
                        <div class="contact-info">
                            <?php if (!empty($cepatlakoo_fb_profile_url) || !empty($cepatlakoo_tw_profile_url) || !empty($cepatlakoo_itg_profile_url) || !empty($cepatlakoo_tiktok_profile_url)) : ?>
                                <div class="socials">
                                    <ul>
                                        <?php if (!empty($cepatlakoo_fb_profile_url)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($cepatlakoo_fb_profile_url); ?>" target="_blank" title="Our facebook page">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M22.5 13.75V8.75C22.5 7.37 23.62 6.25 25 6.25H27.5V0H22.5C18.3575 0 15 3.3575 15 7.5V13.75H10V20H15V40H22.5V20H27.5L30 13.75H22.5Z" fill="white" />
                                                    </svg>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($cepatlakoo_tw_profile_url)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($cepatlakoo_tw_profile_url); ?>" target="_blank" title="Our twitter">
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
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($cepatlakoo_itg_profile_url)) : ?>
                                            <li>
                                                <a href="<?php echo esc_url($cepatlakoo_itg_profile_url); ?>" target="_blank" title="Our instagram page">
                                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0)">
                                                            <path d="M20.0067 9.72998C14.3351 9.72998 9.74341 14.3266 9.74341 19.9933C9.74341 25.665 14.3401 30.2566 20.0067 30.2566C25.6784 30.2566 30.2701 25.66 30.2701 19.9933C30.2701 14.3216 25.6734 9.72998 20.0067 9.72998V9.72998ZM20.0067 26.655C16.3251 26.655 13.3451 23.6733 13.3451 19.9933C13.3451 16.3133 16.3267 13.3316 20.0067 13.3316C23.6867 13.3316 26.6684 16.3133 26.6684 19.9933C26.6701 23.6733 23.6884 26.655 20.0067 26.655V26.655Z" fill="white" />
                                                            <path d="M28.2468 0.126651C24.5668 -0.0450151 15.4518 -0.0366818 11.7685 0.126651C8.5318 0.278318 5.6768 1.05998 3.37513 3.36165C-0.471532 7.20831 0.0201346 12.3916 0.0201346 19.9933C0.0201346 27.7733 -0.413199 32.8366 3.37513 36.625C7.2368 40.485 12.4951 39.98 20.0068 39.98C27.7135 39.98 30.3735 39.985 33.0985 38.93C36.8035 37.4916 39.6001 34.18 39.8735 28.2316C40.0468 24.55 40.0368 15.4366 39.8735 11.7533C39.5435 4.73165 35.7751 0.473318 28.2468 0.126651V0.126651ZM34.0718 34.08C31.5501 36.6016 28.0518 36.3766 19.9585 36.3766C11.6251 36.3766 8.28347 36.5 5.84513 34.055C3.0368 31.26 3.54513 26.7716 3.54513 19.9666C3.54513 10.7583 2.60013 4.12665 11.8418 3.65332C13.9651 3.57832 14.5901 3.55332 19.9351 3.55332L20.0101 3.60332C28.8918 3.60332 35.8601 2.67332 36.2785 11.9133C36.3735 14.0216 36.3951 14.655 36.3951 19.9916C36.3935 28.2283 36.5501 31.59 34.0718 34.08V34.08Z" fill="white" />
                                                            <path d="M30.6767 11.7233C32.0012 11.7233 33.075 10.6496 33.075 9.325C33.075 8.00043 32.0012 6.92667 30.6767 6.92667C29.3521 6.92667 28.2783 8.00043 28.2783 9.325C28.2783 10.6496 29.3521 11.7233 30.6767 11.7233Z" fill="white" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0">
                                                                <rect width="40" height="40" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($cepatlakoo_tiktok_profile_url)) : ?>
                                        <li>
                                                <a href="<?php echo esc_url($cepatlakoo_tiktok_profile_url); ?>" target="_blank" title="Our tiktok page">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tiktok" viewBox="0 0 16 16">
                                                        <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z" />
                                                    </svg>
                                                </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($cepatlakoo_customer_care_phone)) : ?>
                                <div class="customer-care">
                                    <p>
                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M31.3867 23.6914C31.9596 23.6914 32.5065 23.8021 33.0273 24.0234C33.5612 24.2318 34.0299 24.5378 34.4336 24.9414L38.7305 29.2383C39.1341 29.6419 39.4401 30.1107 39.6484 30.6445C39.8698 31.1654 39.9805 31.7122 39.9805 32.2852C39.9805 32.8581 39.8698 33.4115 39.6484 33.9453C39.4401 34.4661 39.1341 34.9284 38.7305 35.332L38.457 35.6055C37.7539 36.3086 37.1094 36.9336 36.5234 37.4805C35.9375 38.0273 35.3255 38.4896 34.6875 38.8672C34.0495 39.2318 33.3398 39.5117 32.5586 39.707C31.7773 39.9023 30.8464 40 29.7656 40C28.138 40 26.4648 39.7461 24.7461 39.2383C23.0273 38.7305 21.3151 38.0273 19.6094 37.1289C17.9167 36.2305 16.25 35.1628 14.6094 33.9258C12.9818 32.6888 11.4388 31.3411 9.98047 29.8828C8.53516 28.4115 7.20052 26.862 5.97656 25.2344C4.7526 23.5938 3.69792 21.9271 2.8125 20.2344C1.92708 18.5286 1.23698 16.8294 0.742188 15.1367C0.247396 13.444 0 11.8034 0 10.2148C0 9.13411 0.0911458 8.20964 0.273438 7.44141C0.46875 6.67318 0.748698 5.97005 1.11328 5.33203C1.49089 4.69401 1.94661 4.08854 2.48047 3.51562C3.02734 2.92969 3.65234 2.28516 4.35547 1.58203L4.66797 1.26953C5.07161 0.865885 5.53385 0.553385 6.05469 0.332031C6.57552 0.110677 7.12891 0 7.71484 0C8.28776 0 8.83464 0.110677 9.35547 0.332031C9.88932 0.553385 10.3581 0.865885 10.7617 1.26953L15.0586 5.56641C15.4622 5.97005 15.7682 6.4388 15.9766 6.97266C16.1979 7.49349 16.3086 8.04036 16.3086 8.61328C16.3086 9.1862 16.2109 9.70052 16.0156 10.1562C15.8203 10.599 15.5794 11.0026 15.293 11.3672C15.0065 11.7318 14.6875 12.0638 14.3359 12.3633C13.9974 12.6628 13.6849 12.9557 13.3984 13.2422C13.112 13.5286 12.8711 13.8216 12.6758 14.1211C12.4805 14.4076 12.3828 14.7201 12.3828 15.0586C12.3828 15.5534 12.5586 15.9766 12.9102 16.3281L23.6719 27.0898C24.0234 27.4414 24.4466 27.6172 24.9414 27.6172C25.2799 27.6172 25.5924 27.5195 25.8789 27.3242C26.1784 27.1289 26.4714 26.888 26.7578 26.6016C27.0443 26.3151 27.3372 26.0026 27.6367 25.6641C27.9362 25.3125 28.2682 24.9935 28.6328 24.707C28.9974 24.4206 29.401 24.1797 29.8438 23.9844C30.2995 23.7891 30.8138 23.6914 31.3867 23.6914ZM29.7656 37.5C30.7031 37.5 31.4844 37.4154 32.1094 37.2461C32.7474 37.0638 33.3203 36.8099 33.8281 36.4844C34.3359 36.1458 34.8307 35.7292 35.3125 35.2344C35.7943 34.7396 36.3477 34.1797 36.9727 33.5547C37.3242 33.2031 37.5 32.7799 37.5 32.2852C37.5 32.0508 37.4023 31.7773 37.207 31.4648C37.0247 31.1393 36.7839 30.8008 36.4844 30.4492C36.1849 30.0977 35.8464 29.7396 35.4688 29.375C35.1042 28.9974 34.7396 28.6458 34.375 28.3203C34.0234 27.9818 33.6914 27.6758 33.3789 27.4023C33.0794 27.1159 32.8451 26.888 32.6758 26.7188C32.3242 26.3672 31.8945 26.1914 31.3867 26.1914C31.0482 26.1914 30.7357 26.2891 30.4492 26.4844C30.1628 26.6797 29.8763 26.9206 29.5898 27.207C29.3034 27.4935 29.0039 27.8125 28.6914 28.1641C28.3919 28.5026 28.0599 28.8151 27.6953 29.1016C27.3307 29.388 26.9206 29.6289 26.4648 29.8242C26.0221 30.0195 25.5143 30.1172 24.9414 30.1172C24.3685 30.1172 23.8151 30.013 23.2812 29.8047C22.7604 29.5833 22.2982 29.2708 21.8945 28.8672L11.1328 18.1055C10.7292 17.7018 10.4167 17.2396 10.1953 16.7188C9.98698 16.1849 9.88281 15.6315 9.88281 15.0586C9.88281 14.4857 9.98047 13.9779 10.1758 13.5352C10.3711 13.0794 10.612 12.6693 10.8984 12.3047C11.1849 11.9401 11.4974 11.6081 11.8359 11.3086C12.1875 10.9961 12.5065 10.6966 12.793 10.4102C13.0794 10.1237 13.3203 9.83724 13.5156 9.55078C13.7109 9.26432 13.8086 8.95182 13.8086 8.61328C13.8086 8.10547 13.6328 7.67578 13.2812 7.32422C13.112 7.15495 12.8841 6.92057 12.5977 6.62109C12.3242 6.30859 12.0182 5.97656 11.6797 5.625C11.3542 5.26042 11.0026 4.89583 10.625 4.53125C10.2604 4.15365 9.90234 3.8151 9.55078 3.51562C9.19922 3.21615 8.86068 2.97526 8.53516 2.79297C8.22266 2.59766 7.94922 2.5 7.71484 2.5C7.22005 2.5 6.79688 2.67578 6.44531 3.02734C5.82031 3.65234 5.26042 4.20573 4.76562 4.6875C4.28385 5.16927 3.86719 5.66406 3.51562 6.17188C3.17708 6.67969 2.91667 7.2526 2.73438 7.89062C2.5651 8.51562 2.48047 9.29036 2.48047 10.2148C2.48047 11.6732 2.71484 13.1771 3.18359 14.7266C3.66536 16.276 4.32292 17.8255 5.15625 19.375C6.0026 20.9245 6.9987 22.4544 8.14453 23.9648C9.29036 25.4622 10.5404 26.8815 11.8945 28.2227C13.2487 29.5638 14.6745 30.8008 16.1719 31.9336C17.6823 33.0664 19.2057 34.0495 20.7422 34.8828C22.2917 35.7031 23.8281 36.3477 25.3516 36.8164C26.888 37.2721 28.3594 37.5 29.7656 37.5Z" fill="white" />
                                        </svg>

                                        <b><?php echo esc_attr($cepatlakoo_customer_phone_label); ?></b>
                                        <?php if ($cepatlakoo_customer_phone_type == 'wa') :
                                            if (strpos($cepatlakoo_customer_care_phone, '-') !== false) {
                                                $cepatlakoo_customer_care_phone = str_replace('-', '', $cepatlakoo_customer_care_phone);
                                            }
                                            if (preg_match('[^\+62]', $cepatlakoo_customer_care_phone)) {
                                                $wa_phone = str_replace('+62', '62', $cepatlakoo_customer_care_phone);
                                            } else if ($cepatlakoo_customer_care_phone[0] == '0') {
                                                $cepatlakoo_customer_care_phone = ltrim($cepatlakoo_customer_care_phone, '0');
                                                $wa_phone = '62' . $cepatlakoo_customer_care_phone;
                                            } else if ($cepatlakoo_customer_care_phone[0] == '8') {
                                                $wa_phone = '62' . $cepatlakoo_customer_care_phone;
                                            } else {
                                                $wa_phone = $cepatlakoo_customer_care_phone;
                                            }

                                            if (strpos($cepatlakoo_customer_care_phone, "-")) {
                                                $wa_phone = str_replace('-', '', $wa_phone);
                                            }

                                            $wa_base_url = 'https://api.whatsapp.com/';
                                        ?>
                                            <a href="<?php echo $wa_base_url; ?>send?l=id&phone=<?php echo $wa_phone; ?>" target="_blank">
                                            <?php else : ?>
                                                <a href="tel:<?php echo esc_attr($cepatlakoo_customer_care_phone); ?>">
                                                <?php endif; ?>
                                                <?php echo esc_attr($cepatlakoo_customer_care_phone); ?></a>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($cepatlakoo_top_bar_msg)) : ?>
                            <div class="flash-info">
                                <?php
                                echo wp_kses(
                                    $cepatlakoo_top_bar_msg,
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                            'title' => array()
                                        ),
                                        'b' => array(),
                                        'em' => array(),
                                        'strong' => array(),
                                        'i' => array(),
                                        'p' => array(),
                                    )
                                );
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="user-options">
                            <div class="user-account-menu">
                                <div class="avatar">
                                    <label><?php esc_html_e('My Account', 'cepatlakoo'); ?></label>
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M27.5684 13.4082L29.9316 15.7617L20 25.6934L10.0684 15.7617L12.4316 13.4082L20 20.9766L27.5684 13.4082Z" fill="white" />
                                    </svg>
                                </div>
                                <?php if (class_exists('WooCommerce')) :  ?>
                                    <ul class="user-menu-menu">
                                        <?php if (is_user_logged_in()) : ?>
                                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php echo $user->display_name; ?>"><?php esc_html_e('My Account', 'cepatlakoo'); ?></a></li>
                                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>orders/"><?php esc_html_e('My Orders', 'cepatlakoo'); ?></a></li>
                                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>points/"><?php esc_html_e('Poin Level', 'cepatlakoo'); ?></a></li>
                                            <li><a href="<?php echo wp_logout_url(get_permalink('woocommerce_myaccount_page_id')); ?>"><?php esc_html_e('Sign Out', 'cepatlakoo'); ?></a></li>
                                        <?php else : ?>
                                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><?php esc_html_e('Sign in / Sign Up', 'cepatlakoo'); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>

                            <div class="search-tool">
                                <div class="search-widget">
                                    <div class="search-trigger">
                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M38 15.3398C38 16.5638 37.8372 17.7487 37.5117 18.8945C37.1992 20.0273 36.75 21.0885 36.1641 22.0781C35.5911 23.0547 34.901 23.9531 34.0938 24.7734C33.2865 25.5807 32.388 26.2708 31.3984 26.8438C30.4089 27.4167 29.3411 27.8659 28.1953 28.1914C27.0625 28.5039 25.8841 28.6602 24.6602 28.6602C23.1758 28.6602 21.737 28.4258 20.3438 27.957C18.9505 27.4753 17.668 26.7786 16.4961 25.8672L4.85156 37.5117C4.52604 37.8372 4.12891 38 3.66016 38C3.20443 38 2.8138 37.8372 2.48828 37.5117C2.16276 37.1862 2 36.7956 2 36.3398C2 36.1185 2.03906 35.9036 2.11719 35.6953C2.20833 35.487 2.33203 35.3047 2.48828 35.1484L14.1328 23.5039C13.2214 22.332 12.5247 21.0495 12.043 19.6562C11.5742 18.263 11.3398 16.8242 11.3398 15.3398C11.3398 14.1159 11.4961 12.9375 11.8086 11.8047C12.1341 10.6589 12.5833 9.59115 13.1562 8.60156C13.7292 7.61198 14.4193 6.71354 15.2266 5.90625C16.0469 5.09896 16.9453 4.40885 17.9219 3.83594C18.9115 3.25 19.9727 2.80078 21.1055 2.48828C22.2513 2.16276 23.4362 2 24.6602 2C25.8841 2 27.0625 2.16276 28.1953 2.48828C29.3411 2.80078 30.4089 3.25 31.3984 3.83594C32.388 4.40885 33.2865 5.09896 34.0938 5.90625C34.901 6.71354 35.5911 7.61198 36.1641 8.60156C36.75 9.59115 37.1992 10.6589 37.5117 11.8047C37.8372 12.9375 38 14.1159 38 15.3398ZM24.6602 25.3398C26.0404 25.3398 27.3359 25.0794 28.5469 24.5586C29.7578 24.0247 30.8125 23.3086 31.7109 22.4102C32.6224 21.4987 33.3385 20.4375 33.8594 19.2266C34.3932 18.0156 34.6602 16.7201 34.6602 15.3398C34.6602 13.9596 34.3997 12.6641 33.8789 11.4531C33.3581 10.2292 32.6419 9.16797 31.7305 8.26953C30.832 7.35807 29.7708 6.64193 28.5469 6.12109C27.3359 5.60026 26.0404 5.33984 24.6602 5.33984C23.2799 5.33984 21.9844 5.60677 20.7734 6.14062C19.5625 6.66146 18.5013 7.3776 17.5898 8.28906C16.6914 9.1875 15.9753 10.2422 15.4414 11.4531C14.9206 12.6641 14.6602 13.9596 14.6602 15.3398C14.6602 16.7201 14.9206 18.0156 15.4414 19.2266C15.9753 20.4375 16.6914 21.4987 17.5898 22.4102C18.5013 23.3086 19.5625 24.0247 20.7734 24.5586C21.9844 25.0794 23.2799 25.3398 24.6602 25.3398Z" fill="white" />
                                        </svg>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search-widget-header">
                            <?php get_template_part('search', 'product'); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }
}

/**
 * Function to add custom classes to the body
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
add_filter('body_class', 'cepatlakoo_custom_body_classes');
if (!function_exists('cepatlakoo_custom_body_classes ')) {
    function cepatlakoo_custom_body_classes($classes)
    {
        if (is_home() || is_front_page()) {
            $classes[] = 'homepage woocommerce';
        } else {
            $classes[] = 'woocommerce';
        }
        return $classes;
    }
}

/**
 * Function to display Cepatlakoo header style
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_header_style_option')) {
    function cepatlakoo_header_style_option()
    {
        global $wp_query, $cl_options;

        $cepatlakoo_header_style_opt = !empty($cl_options['cepatlakoo_header_style_opt']) ? $cl_options['cepatlakoo_header_style_opt'] : '';
        $cepatlakoo_header_style = get_post_meta(get_the_ID(), 'cepatlakoo_header_style', true);

        if (!empty($cepatlakoo_header_style_opt) || $cepatlakoo_header_style_opt == 0) {
            if ($cepatlakoo_header_style_opt == 0) {
                if ($cepatlakoo_header_style) {
                    if ($cepatlakoo_header_style == 1) {
                        get_template_part('header-left');
                    } elseif ($cepatlakoo_header_style == 2) {
                        get_template_part('header-middle');
                    } else {
                        if ($cepatlakoo_header_style_opt == 1) {
                            get_template_part('header-left');
                        } elseif ($cepatlakoo_header_style_opt == 2) {
                            get_template_part('header-middle');
                        } else {
                            get_template_part('header-left');
                        }
                    }
                } else {
                    if ($cepatlakoo_header_style_opt == 1) {
                        get_template_part('header-left');
                    } elseif ($cepatlakoo_header_style_opt == 2) {
                        get_template_part('header-middle');
                    } else {
                        get_template_part('header-left');
                    }
                }
            } elseif ($cepatlakoo_header_style_opt == 1) {
                get_template_part('header-left');
            } elseif ($cepatlakoo_header_style_opt == 2) {
                get_template_part('header-middle');
            } else {
                if ($cepatlakoo_header_style == 1) {
                    get_template_part('header-left');
                } elseif ($cepatlakoo_header_style == 2) {
                    get_template_part('header-middle');
                } else {
                    if ($cepatlakoo_header_style_opt == 1) {
                        get_template_part('header-left');
                    } elseif ($cepatlakoo_header_style_opt == 2) {
                        get_template_part('header-middle');
                    } else {
                        get_template_part('header-left');
                    }
                }
            }
        } else {
            get_template_part('header-left');
        }
    }
}

/**
 * Functions to remove querystring from static resource
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_remove_script_version')) {
    function cepatlakoo_remove_script_version($src)
    {
        global $cl_options;

        $cepatlakoo_remove_querystring = !empty($cl_options['cepatlakoo_remove_querystring']) ? $cl_options['cepatlakoo_remove_querystring'] : '';

        if ($cepatlakoo_remove_querystring) {
            $parts = explode('?ver', $src);
            return $parts[0];
        } else {
            return $src;
        }
    }
}
add_filter('script_loader_src', 'cepatlakoo_remove_script_version', 15, 1);
add_filter('style_loader_src', 'cepatlakoo_remove_script_version', 15, 1);

/**
 * Functions to trigger minify html
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
add_action('get_header', 'cepatlakoo_init_minify_html', 1);
if (!function_exists('cepatlakoo_init_minify_html')) {
    function cepatlakoo_init_minify_html()
    {
        global $cl_options;

        $cepatlakoo_minify_html = !empty($cl_options['cepatlakoo_minify_html']) ? $cl_options['cepatlakoo_minify_html'] : '';

        if ($cepatlakoo_minify_html) {
            ob_start('cepatlakoo_minify_html_output');
        }
    }
}

/**
 * Functions to Minify HTML
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_minify_html_output')) {
    function cepatlakoo_minify_html_output($buffer)
    {
        if (substr(ltrim($buffer), 0, 5) == '<?xml')
            return ($buffer);
        $buffer = str_replace(array(chr(13) . chr(10), chr(9)), array(chr(10), ''), $buffer);
        $buffer = str_ireplace(array('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array('CLAKOO-START<script', '/script>CLAKOO-END', 'CLAKOO-START<pre', '/pre>CLAKOO-END', 'CLAKOO-START<textarea', '/textarea>CLAKOO-END', 'CLAKOO-START<style', '/style>CLAKOO-END'), $buffer);
        $split = explode('CLAKOO-END', $buffer);
        $buffer = '';
        for ($i = 0; $i < count($split); $i++) {
            $ii = strpos($split[$i], 'CLAKOO-START');
            if ($ii !== false) {
                $process = substr($split[$i], 0, $ii);
                $buffer_data = substr($split[$i], $ii + 12);
                if (substr($buffer_data, 0, 7) == '<script') {
                    $split2 = explode(chr(10), $buffer_data);
                    $buffer_data = '';
                    for ($iii = 0; $iii < count($split2); $iii++) {
                        if ($split2[$iii])
                            $buffer_data .= trim($split2[$iii]) . chr(10);
                        if (strpos($split2[$iii], '//') !== false && substr(trim($split2[$iii]), -1) == ';')
                            $buffer_data .= chr(10);
                    }
                    if ($buffer_data)
                        $buffer_data = substr($buffer_data, 0, -1);
                    $buffer_data = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer_data);
                } elseif (substr($buffer_data, 0, 6) == '<style') {
                    $buffer_data = preg_replace(array('/\>[^\S ]+/u', '/[^\S ]+\</u', '/(\s)+/u'), array('>', '<', '\\1'), $buffer_data);
                    $buffer_data = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer_data);
                    $buffer_data = str_replace(array(chr(10), ' {', '{ ', ' }', '} ', '( ', ' )', ' :', ': ', ' ;', '; ', ' ,', ', ', ';}'), array('', '{', '{', '}', '}', '(', ')', ':', ':', ';', ';', ',', ',', '}'), $buffer_data);
                }
            } else {
                $process = $split[$i];
                $buffer_data = '';
            }
            $process = preg_replace(array('/\>[^\S ]+/u', '/[^\S ]+\</u', '/(\s)+/u'), array('>', '<', '\\1'), $process);
            $process = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/u', '', $process);
            $buffer .= $process . $buffer_data;
        }
        $buffer = str_replace(array(chr(10) . '<script', chr(10) . '<style', '*/' . chr(10), 'CLAKOO-START'), array('<script', '<style', '*/', ''), $buffer);
        if (strtolower(substr(ltrim($buffer), 0, 15)) == '<!doctype html>')
            $buffer = str_replace(' />', '>', $buffer);
        return ($buffer);
    }
}

/**
 * Functions to set countdown scarcity
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_set_countdown_scarcity')) {
    function cepatlakoo_set_countdown_scarcity($ct_id, $ct_position = 'woo')
    {
        global $cl_options;
        wp_enqueue_script('cl-countdown');
        $countdown_id = get_post_meta($ct_id, 'cepatlakoo_countdown_timer_opt', true);
        $check = get_post_status($countdown_id);
        if ($check != 'publish')
            return false;

        $countdown_type = get_post_meta($countdown_id, 'cl_countdown_type', true);
        $arr_ip = array();


        if ($countdown_type == "Evergreen Countdown") {
            $countdown_day = !empty(get_post_meta($countdown_id, 'cl_countdown_day', true)) ? get_post_meta($countdown_id, 'cl_countdown_day', true) : 0;
            $countdown_hour = !empty(get_post_meta($countdown_id, 'cl_countdown_hour', true)) ? get_post_meta($countdown_id, 'cl_countdown_hour', true) : 0;
            $countdown_minute = !empty(get_post_meta($countdown_id, 'cl_countdown_minute', true)) ? get_post_meta($countdown_id, 'cl_countdown_minute', true) : 0;
            $countdown_second = !empty(get_post_meta($countdown_id, 'cl_countdown_second', true)) ? get_post_meta($countdown_id, 'cl_countdown_second', true) : 0;
            $countdown_detection = get_post_meta($countdown_id, 'cl_countdown_detection', true);
            $expiry = time() + (86400 * 365); // 86400 = 1 day expires
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

            $set_curr_time = date('Y/m/d H:i:s e');
            $curr_time = new DateTime($set_curr_time);
            $add = '+' . $countdown_day . ' days +' . $countdown_hour . ' hours +' . $countdown_minute . ' minutes +' . $countdown_second . ' seconds';
            // $set_new_date = date('Y-m-d', strtotime("+".$countdown_day." days"));
            $set_new_date = date('Y-m-d H:i:s e', strtotime($add, strtotime($set_curr_time)));
            // $set_countdown_time = $set_new_date.' '.$countdown_hour.':'.$countdown_minute.':'.$countdown_second;
            $set_countdown_time = $set_new_date;
            // $countdown_time = date_create_from_format('Y-m-d H:i:s', $set_countdown_time);
            $countdown_time = $set_countdown_time;

            if ($countdown_detection == 'Cookie') {
                if ($countdown_id != '') :
                    $cookie_countdown_date_time_name = 'scarcity_countdown_date_time_' . $ct_id . '_' . $countdown_id;
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

                $ct_old = get_post_meta($ct_id, 'cl_countdown_ip_' . $countdown_id, true);

                if ($countdown_id != '') :

                    if (isset($ct_old[$ipaddress]) && $ct_old[$ipaddress]['timer'] == $add) {
                        $get_cookie_countdown_date_time = $ct_old[$ipaddress]['value'];
                    } else {
                        if ($ct_old == '') {
                            $arr_ip = array($ipaddress => array(
                                'value' => $set_countdown_time,
                                'timer' => $add
                            ));
                            add_post_meta($ct_id, 'cl_countdown_ip_' . $countdown_id, $arr_ip, true);
                        } else {
                            $ct_old[$ipaddress] = array(
                                'value' => $set_countdown_time,
                                'timer' => $add
                            );
                            update_post_meta($ct_id, 'cl_countdown_ip_' . $countdown_id, $ct_old);
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
        } else {
            // $countdown_date = get_post_meta( $countdown_id, 'cl_normal_countdown_date', true );
            $countdown_date = get_field('cl_normal_countdown_date', $countdown_id);
            $countdown_hour = get_post_meta($countdown_id, 'cl_normal_countdown_hour', true);
            $countdown_minute = get_post_meta($countdown_id, 'cl_normal_countdown_minute', true);
            $countdown_second = get_post_meta($countdown_id, 'cl_normal_countdown_second', true);
            $countdown_detection = get_post_meta($countdown_id, 'cl_normal_countdown_detection', true);
            $expiry = time() + (86400 * 360); // 86400 = 1 day expires
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

            $set_curr_time = date('Y/m/d H:i:s', current_time('timestamp', 0));
            $curr_time = new DateTime($set_curr_time);

            $set_countdown_time = $countdown_date . ' ' . $countdown_hour . ':' . $countdown_minute . ':' . $countdown_second;
            $countdown_time = date_create_from_format('Y-m-d H:i:s', $set_countdown_time);

            wp_localize_script('cepatlakoo-functions', '_cepatlakoo', array(
                'scarcity_countdown_date_time' => $set_countdown_time,
                'scarcity_countdown_type' => $countdown_type
            ));

            echo '<div class="sc-time" style="display:none;">' . $set_countdown_time . '</div>';
            echo '<div class="sc-type" style="display:none;">' . $countdown_type . '</div>';
        }

        if (!empty($get_cookie_countdown_date_time) || !empty($countdown_id) || !empty($countdown_type)) {
            $cepatlakoo_countdown_heading_cart = !empty($cl_options['cepatlakoo_countdown_heading_cart']) ? $cl_options['cepatlakoo_countdown_heading_cart'] : '';
            $cepatlakoo_countdown_subheading_cart = !empty($cl_options['cepatlakoo_countdown_subheading_cart']) ? $cl_options['cepatlakoo_countdown_subheading_cart'] : '';
        } else {
            $cepatlakoo_countdown_heading_cart = null;
            $cepatlakoo_countdown_subheading_cart = null;
        }

        if ($cepatlakoo_countdown_heading_cart !== null && $cepatlakoo_countdown_subheading_cart !== null && !empty($countdown_id)) { ?>
            <div id="countdown-widget">
                <div id="countdown-container">
                    <?php if ($cepatlakoo_countdown_heading_cart || $cepatlakoo_countdown_subheading_cart) : ?>
                        <div class="coutndown-head">
                            <?php if ($cepatlakoo_countdown_heading_cart) : ?>
                                <h3><?php echo $cepatlakoo_countdown_heading_cart; ?></h3>
                            <?php endif; ?>

                            <?php if ($cepatlakoo_countdown_subheading_cart) : ?>
                                <h4 class="subheading"><?php echo $cepatlakoo_countdown_subheading_cart; ?></h4>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div id="<?php echo ($ct_position == 'woo') ? 'countdown' : 'countdown_qv'; ?>" data-stellar-ratio="0.5">
                        <div id="timer">
                            <div class="number-container month">
                                <div class="number"></div>
                                <div class="text"></div>
                            </div>
                            <div class="number-container day">
                                <div class="number"></div>
                                <div class="text"></div>
                            </div>
                            <div class="number-container hour">
                                <div class="number"></div>
                                <div class="text"></div>
                            </div>
                            <div class="number-container minute">
                                <div class="number"></div>
                                <div class="text"></div>
                            </div>
                            <div class="number-container second">
                                <div class="number"></div>
                                <div class="text"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
}

/**
 * Functions to get slug from URL
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_getslug_url')) {
    function cepatlakoo_getslug_url()
    {
        return substr($_SERVER['REQUEST_URI'], 1);
    }
}

/**
 * Functions to extract image size
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_extract_image_size')) {
    function cepatlakoo_extract_image_size()
    {
        $array = get_intermediate_image_sizes();
        foreach ($array as $key => $value) {
            $out[$value] = $value;
        }
        return $out;
    }
}

/**
 * Functions to get attachment ID
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
if (!function_exists('cepatlakoo_get_image_id')) {
    function cepatlakoo_get_image_id($image_url)
    {
        global $wpdb;

        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));

        if (!$attachment) {
            return '';
        }

        return $attachment[0];
    }
}

/**
 * Functions to extract cpt slideshow
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_extract_cpt_slideshow')) {
    function cepatlakoo_extract_cpt_slideshow()
    {
        $post_list = get_posts(array(
            'post_type'             => 'cl_slideshow',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
        ));

        $posts = array();

        foreach ($post_list as $post) {
            $posts += array(esc_html('Select Slideshow', 'cepatlakoo'), $post->ID => $post->post_title);
        }
        return $posts;
    }
}

/**
 * Functions to extract thumnail cpt Elementor Lib
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_extract_el_library')) {
    function cepatlakoo_extract_el_library()
    {
        $post_list = get_posts(array(
            'post_type'             => 'elementor_library',
            'post_status'           => 'publish',
            'posts_per_page'        => -1,
        ));

        $imagelist = array();
        if ($post_list) {
            foreach ($post_list as $post) {
                $imagelist += array($post->ID => get_the_post_thumbnail_url($post->ID, 'cepatlakoo-featured-post'));
            }
        }
        return $imagelist;
    }
}

/**
 * Functions to get last ID in cpt slideshow
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_lastid_slideshow')) {
    function cepatlakoo_lastid_slideshow()
    {
        $latest_cpt = get_posts("post_type=cl_slideshow&numberposts=1");
        if ($latest_cpt) {
            return absint($latest_cpt[0]->ID);
        } else {
            return;
        }
    }
}

/**
 * Functions to get last ID in cpt slideshow
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_fb_pixel_data')) {
    function cepatlakoo_fb_pixel_data()
    {
        // global $cl_options;
        // $is_admin = current_user_can('administrator');

        // $cepatlakoo_facebook_pixel_id = ( !empty( $cl_options['cepatlakoo_facebook_pixel_id'] ) && ( (isset($cl_options['cepatlakoo_facebook_pixel_admin']) && $cl_options['cepatlakoo_facebook_pixel_admin'] == 0) || !$is_admin ) ) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
        // $pixel_ori = get_post_meta(get_the_ID(), 'cepatlakoo_fbpixel_event', true );
        // $pixel = ($pixel_ori == 'custom') ? get_post_meta(get_the_ID(), 'cepatlakoo_fbpixel_custom', true ) : $pixel_ori;
        // $pixel_currency = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_currency', true );
        // $pixel_price = get_post_meta(get_the_ID(), 'cepatlakoo_pixel_price', true );

        //     if ( $cepatlakoo_facebook_pixel_id ) {
        //         if ( $pixel ) {
        //             if ( $pixel == 'AddToCart' || $pixel == 'InitiateCheckout' || $pixel == 'Purchase' || $pixel_ori == 'custom' ){
        //                 return 'fb-pixel="' . esc_attr( $pixel ) .'" fb-currency="'. esc_attr( !empty( $pixel_currency ) ? $pixel_currency : 'IDR' ) .'" fb-price="'. esc_attr( !empty( $pixel_price ) ? $pixel_price : '0' ) .'"';
        //             } else {
        //                 return 'fb-pixel="' . esc_attr( $pixel ) .'"';
        //             }
        //         }else{
        //             return 'fb-pixel="ViewContent"';
        //         }
        //     } else {
        //         return;
        //     }
    }
}
/**
 * Functions to extract cpt slideshow
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @
 */
if (!function_exists('cepatlakoo_get_post_type_post_options')) {
    function cepatlakoo_get_post_type_post_options()
    {
        $post_list = get_posts(array(
            'post_type'             => 'cl_countdown_timer',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
        ));

        $posts = array();

        $posts = array(esc_html('Select Countdown', 'cepatlakoo'));
        foreach ($post_list as $post) {
            $posts += array(esc_html('Select Countdown', 'cepatlakoo'), $post->ID => $post->post_title);
        }
        return $posts;
    }
}

/**
 * Functions to set logo in Admin Dashboard
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @source : code.tutsplus.com/articles/customizing-the-wordpress-dashboard-for-your-clients--wp-21513
 *
 */
if (!function_exists('cepatlakoo_custom_login_logo')) {
    function cepatlakoo_custom_login_logo()
    {
        global $cl_options;

        $cepatlakoo_logo_login_dashboard = !empty($cl_options['cepatlakoo_logo_login_dashboard']) ? $cl_options['cepatlakoo_logo_login_dashboard'] : '';
        $attachment = wp_get_attachment_image_src($cepatlakoo_logo_login_dashboard, 'large');

        if ($attachment) {
            $attachment = $attachment[0];
            echo '
                <style type="text/css">
                    .login h1 a {
                        background-image:url(' . esc_url($attachment) . ') !important;
                        height: 44px;
                        background-size: 150px;
                        width: auto;
                    }
                </style>
            ';
        }
    }
}
add_action('login_head',  'cepatlakoo_custom_login_logo');

/**
 * Functions to  CUSTOM ADMIN DASHBOARD HEADER LOGO
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @source : www.wpbeginner.com/wp-themes/adding-a-custom-dashboard-logo-in-wordpress-for-branding/
 *
 */
if (!function_exists('cepatlakoo_custom_admin_logo')) {
    function cepatlakoo_custom_admin_logo()
    {
        global $cl_options;

        $cepatlakoo_icon_admin_dashboard = !empty($cl_options['cepatlakoo_icon_admin_dashboard']) ? $cl_options['cepatlakoo_icon_admin_dashboard']['id'] : '';
        $cepatlakoo_logo_login_dashboard = !empty($cl_options['cepatlakoo_logo_login_dashboard']) ? $cl_options['cepatlakoo_logo_login_dashboard']['id'] : '';

        $attachment_icon = null;
        $attachment_logo = null;

        if ($cepatlakoo_logo_login_dashboard || $cepatlakoo_icon_admin_dashboard) {
            $attachment_logo = wp_get_attachment_image_src($cepatlakoo_logo_login_dashboard, 'full');
            $attachment_icon = wp_get_attachment_image_src($cepatlakoo_icon_admin_dashboard, 'full');
        }

        if ($attachment_logo) {
            $attachment_logo = $attachment_logo[0];
            echo '
            <style type="text/css">
                .wrap h1:before {
                    background-image: url(' . esc_url($attachment_logo) . ') !important;
                    background-size: contain;
                    background-repeat: no-repeat;
                    display: block;
                    width: 300px;
                    height: 50px;
                    content: "";
                    margin-bottom: 20px;
                }
            </style>
            ';
        }

        if ($attachment_icon) {
            $attachment_icon = $attachment_icon[0];
            echo '
            <style type="text/css">
                #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
                    background-image: url(' . esc_url($attachment_icon) . ') !important;
                    background-position: top center;
                    background-size: contain;
                    color:rgba(0, 0, 0, 0);
                }
                #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
                    background-position: 0 0;
                }
            </style>
            ';
        }
    }
}
add_action('wp_before_admin_bar_render',  'cepatlakoo_custom_admin_logo');
add_action('admin_head',  'cepatlakoo_custom_admin_logo');

/**
 * Functions to replace hexcode for sms and whatsapp
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
if (!function_exists('cepatlakoo_replace_hexcode')) {
    function cepatlakoo_replace_hexcode($string)
    {
        $cepatlakoo_replace_hexcode = str_replace('&#8211;', '%2D', $string);
        $cepatlakoo_replace_hexcode = str_replace('&', '%26', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace("\n", '%0A', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace('%26amp;', '%26', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace('%26#038;', '%26', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace('&#8211;', '%26', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace('%26#038;', '%26', $cepatlakoo_replace_hexcode);
        $cepatlakoo_replace_hexcode = str_replace("#", '%23', $cepatlakoo_replace_hexcode);
        return $cepatlakoo_replace_hexcode;
    }
}


/**
 * Functions to register migration menu
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
if (!function_exists('cepatlakoo_migration_page')) {
    function cepatlakoo_migration_page()
    {

        echo '<h2>' . __('Bingung? Tonton video tutorial migrasi theme options sekarang. <a href="https://cepatlakoo.com/video-tutorial/tutorial-migrasi-theme-options-cepatlakoo/" target="_blank">Klik di sini</a>', 'cepatlakoo') . '</h2>';
        $i = 0;
        $s1 = 'disabled';
        $s2 = 'disabled';
        $s3 = 'disabled';
        if (!file_exists(WP_PLUGIN_DIR . '/redux-framework/redux-framework.php')) {
            $i = 1;
            $s1 = 'active';
            if (get_option('cepatlakoo_migration_themeoption')) {
                update_option('cepatlakoo_migration_themeoption',  '0');
            }
        }

        if (!is_plugin_active('redux-framework/redux-framework.php')) {
            if (get_option('')) {
                update_option('cepatlakoo_migration_themeoption',  '0');
            }
            $s2 = ($i == 0) ? 'active' : 'disabled';
            $i = 2;
        }

        if (!get_option('cepatlakoo_migration_themeoption')) {
            $s3 = ($i == 0) ? 'active' : 'disabled';
        }
        echo '
        <div class="container">
            <div class="row form-group">
                <div class="col-xs-12">
                    <ul class="nav step-migration setup-panel">
                        <li class="' . $s1 . '"><a href="#step-1">
                            <p>' . esc_html__('Pasang Plugin Redux Framework', 'cepatlakoo') . '</p>
                        </a></li>
                        <li class="' . $s2 . '"><a href="#step-2">
                            <p>' . esc_html__('Aktifkan Plugin Redux Framework', 'cepatlakoo') . '</p>
                        </a></li>
                        <li class="' . $s3 . '"><a href="#step-3">
                            <p>' . esc_html__('Mulai Migrasi', 'cepatlakoo') . '</p>
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12 well text-center">
                        <h1> ' . esc_html__('LANGKAH 1 - Pasang Plugin Redux Framework', 'cepatlakoo') . '</h1>
                        <p>' . esc_html__('Cepatlakoo mengalami perubahan pada theme options,  Anda diwajibkan untuk menginstall plugin Redux Framework. Klik tombol Install Redux Framework untuk mulai menginstall plugin tersebut.', 'cepatlakoo') . '</p>
                        <a href="' . get_admin_url() . 'themes.php?page=tgmpa-install-plugins&plugin_status=install' . '" class="button-primary cl-install-redux">' . esc_html__('Install Redux Framework', 'cepatlakoo') . '</a>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-2">
                <div class="col-xs-12">
                    <div class="col-md-12 well">
                        <h1> ' . esc_html__('LANGKAH 2 - Aktifkan Plugin Redux Framework', 'cepatlakoo') . '</h1>
                        <p>' . esc_html__('Cepatlakoo mengalami perubahan pada theme options,  Anda diwajibkan untuk menginstall plugin Redux Framework. Klik tombol Install Redux Framework untuk mulai menginstall plugin tersebut pada halaman berikutnya.', 'cepatlakoo') . '</p>
                        <a href="#active_redux" class="button-primary cl-active-redux">' . esc_html__('Aktifkan Redux Framework', 'cepatlakoo') . '</a>
                        <img src=' . get_template_directory_uri() . '/assets/images/loader.gif class="migration-loader" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-3">
                <div class="col-xs-12">
                    <div class="col-md-12 well">
                    <h1> ' . esc_html__('LANGKAH 3 - Mulai Migrasi', 'cepatlakoo') . '</h1>
                    <p>' . esc_html__('Klik tombol Migrasi Sekarang untuk melakukan proses migrasi theme options ke Redux Framework.', 'cepatlakoo') . '</p>
                    <a href="#migration_themeoption" class="button-primary cl-migration">' . esc_html__('Migrasi Sekarang', 'cepatlakoo') . '</a>
                    <img src=' . get_template_directory_uri() . '/assets/images/loader.gif class="migration-loader" style="display:none">
                    </div>
                </div>
            </div>
        </div>';
    }
}

/**
 * Functions add submenu for migration
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
add_action('admin_menu', 'cepatlakoo_migration_menu');
if (!function_exists('cepatlakoo_migration_menu')) {
    function cepatlakoo_migration_menu()
    {
        if (
            get_option('cepatlakoo_options') &&
            (!file_exists(WP_PLUGIN_DIR . '/redux-framework/redux-framework.php') ||
                !is_plugin_active('redux-framework/redux-framework.php') ||
                !get_option('cepatlakoo_migration_themeoption'))
        ) {
            add_submenu_page('options-general.php', 'Update Themes Options', 'Update Themes Options', 'manage_options', 'cepatlakoo_migration_menu', 'cepatlakoo_migration_page');
        }
    }
}

/**
 * Functions for convertion option value from 1.3.7 to 1.4.2
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
add_filter("redux/args/cl_options", 'cepatlakoo_change_redux_arg');
function cepatlakoo_change_redux_arg($args)
{
    global $cl_options;

    if (is_array(Redux::get_option('cl_options', 'cepatlakoo_woocommerce_striketrough_price_color'))) {
        foreach (Redux::get_option('cl_options', 'cepatlakoo_woocommerce_striketrough_price_color') as $val) {
            Redux::set_option('cl_options', 'cepatlakoo_woocommerce_striketrough_price_color', $val);
        }
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_color_schemes')) && !empty($cl_options)) {
        Redux::set_option('cl_options', 'cepatlakoo_color_schemes', 'custom');
        $args['save_defaults'] = false;
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_form_button_bg_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_form_button_bg_color', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_form_button_bg_hover_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_form_button_bg_hover_color', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_form_button_text_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_header_text_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_form_button_text_color', Redux::get_option('cl_options', 'cepatlakoo_header_text_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_topbar_bg_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_topbar_bg_color', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_header_border')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_header_border', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_header_bg_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_header_bg_color', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_footer_menu_background_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_footer_menu_background_color', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_footer_border')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_footer_border', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_footer_text_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_header_text_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_footer_text_color', Redux::get_option('cl_options', 'cepatlakoo_header_text_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_woocommerce_button_bg_color_hover')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_header_text_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_woocommerce_button_bg_color_hover', Redux::get_option('cl_options', 'cepatlakoo_header_text_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_woocommerce_button_text_color')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_header_text_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_woocommerce_button_text_color', Redux::get_option('cl_options', 'cepatlakoo_header_text_color'));
    }

    if (is_null(Redux::get_option('cl_options', 'cepatlakoo_woocommerce_button_border')) && !empty(Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'))) {
        Redux::set_option('cl_options', 'cepatlakoo_woocommerce_button_border', Redux::get_option('cl_options', 'cepatlakoo_general_bg_theme_color'));
    }
    return $args;
}

/**
 * Functions add div main on start and end elementor section
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
add_action('elementor/page_templates/canvas/before_content', 'cepatlakoo_elementor_start_fbpixel', -1);
function cepatlakoo_elementor_start_fbpixel()
{
    echo '<div id="main" ' . cepatlakoo_fb_pixel_data() . '>';
}

add_action('elementor/page_templates/canvas/after_content', 'cepatlakoo_elementor_end_fbpixel', -1);
function cepatlakoo_elementor_end_fbpixel()
{
    echo '</div>';
}

/**
 * Functions to remove elementor first splash
 *
 * @package WordPress
 * @subpackage CepatLakoo
 *
 */
function cepatlakoo_remove_elementor_splash()
{
    delete_transient('elementor_activation_redirect');
    // delete_transient( '_wc_activation_redirect' );
    add_filter('woocommerce_prevent_automatic_wizard_redirect', '__return_true');
}
add_action('init', 'cepatlakoo_remove_elementor_splash');

/* Functions to check mobile / tablet
 *
 * @package Mobile_Detect
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.4.13
 *
 */
if (!function_exists('cepatlakoo_check_device_screen')) {
    function cepatlakoo_check_device_screen()
    {
        $detect = new Mobile_Detect;

        if (!$detect->isMobile() || $detect->isTablet()) {
            return true;
        } else {
            return false;
        }
    }
}

/* Functions to initiate merlinWP
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.5.0
 *
 */
add_action('after_switch_theme', 'cepatlakoo_check_theme_option_exist');
if (!function_exists('cepatlakoo_check_theme_option_exist')) {
    function cepatlakoo_check_theme_option_exist()
    {
        if (false != get_option('cl_options')) {
            add_option('merlin_cepatlakoo_completed', 1);
        }
    }
}

/* Functions to migration fb pixel to multiple
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.6.0
 *
 */
add_action('init', 'cepatlakoo_check_theme_option_multiple_fbpixel');
if (!function_exists('cepatlakoo_check_theme_option_multiple_fbpixel')) {
    function cepatlakoo_check_theme_option_multiple_fbpixel()
    {
        global $cl_options;

        if (!empty(($cl_options['cepatlakoo_facebook_pixel_id'])) && !is_array($cl_options['cepatlakoo_facebook_pixel_id']) && class_exists('Redux')) {
            Redux::set_option('cl_options', 'cepatlakoo_facebook_pixel_id', array($cl_options['cepatlakoo_facebook_pixel_id']));
        }
    }
}

/**
 * Function to generate google ads Conversion
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.0.0
 */
if (!function_exists('cepatlakoo_google_ads_conversion')) {
    function cepatlakoo_google_ads_conversion()
    {
        // $is_admin = current_user_can('administrator');
        // if( $is_admin )
        //     return false;

        $google_ads_conversion_id = get_post_meta(get_the_ID(), 'cepatlakoo_googleads_conversion_id', true);
        $google_ads_send_to = get_post_meta(get_the_ID(), 'cepatlakoo_googleads_send_to', true);
        $google_ads_value = get_post_meta(get_the_ID(), 'cepatlakoo_googleads_conversion_value', true);
        $google_ads_currency = get_post_meta(get_the_ID(), 'cepatlakoo_googleads_currency', true);
        $google_ads_transaction_id = get_post_meta(get_the_ID(), 'cepatlakoo_googleads_transaction_id', true);

        if (empty($google_ads_conversion_id))
            return false;
        ?>

        <!-- Global site tag (gtag.js) - Google Ads: CONVERSION_ID -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_ads_conversion_id; ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', '<?php echo $google_ads_conversion_id; ?>');
            gtag('event', 'conversion', {
                'send_to': '<?php echo $google_ads_send_to; ?>',
                'value': <?php echo $google_ads_value; ?>,
                'currency': '<?php echo $google_ads_currency; ?>'
            });
        </script>
    <?php
    }
}
add_action('wp_head', 'cepatlakoo_google_ads_conversion');

/**
 * Function to render blog element
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if (!function_exists('cepatlakoo_blog_post_elements')) {
    function cepatlakoo_blog_post_elements()
    {

        get_template_part('includes/share-buttons');
        get_template_part('includes/author-box');
        get_template_part('includes/post-nav');
        comments_template('', true);
    }
}
add_action('cl_blog_post_elements', 'cepatlakoo_blog_post_elements');

/**
 * Function to render user meta field
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if (!function_exists('cepatlakoo_social_media_field')) {
    function cepatlakoo_social_media_field($user)
    {
        global $cl_options;
        $userid = isset($user->ID) ? $user->ID : 0;
        if ($cl_options['cepatlakoo_author_box_status'] == false)
            return false;
    ?>
        <h3><?php _e('Social Media Profile'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="cl-social-media-facebook"><?php _e('Facebook', 'cepatlakoo'); ?></label></th>
                <td><input type="text" name="cl-social-media-facebook" class="cl-social-media-facebook regular-text" value="<?php echo get_the_author_meta('cl_social_media_facebook', $userid) ? get_the_author_meta('cl_social_media_facebook', $userid) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="cl-social-media-twitter"><?php _e('Twitter', 'cepatlakoo'); ?></label></th>
                <td><input type="text" name="cl-social-media-twitter" class="cl-social-media-twitter regular-text" value="<?php echo get_the_author_meta('cl_social_media_twitter', $userid) ? get_the_author_meta('cl_social_media_twitter', $userid) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="cl-social-media-instagram"><?php _e('Instagram', 'cepatlakoo'); ?></label></th>
                <td><input type="text" name="cl-social-media-instagram" class="cl-social-media-instagram regular-text" value="<?php echo get_the_author_meta('cl_social_media_instagram', $userid) ? get_the_author_meta('cl_social_media_instagram', $userid) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="cl-social-media-pinterest"><?php _e('Pinterest', 'cepatlakoo'); ?></label></th>
                <td><input type="text" name="cl-social-media-pinterest" class="cl-social-media-pinterest regular-text" value="<?php echo get_the_author_meta('cl_social_media_pinterest', $userid) ? get_the_author_meta('cl_social_media_pinterest', $userid) : ''; ?>"></td>
            </tr>
            <tr>
                <th><label for="cl-social-media-youtube"><?php _e('Youtube', 'cepatlakoo'); ?></label></th>
                <td><input type="text" name="cl-social-media-youtube" class="cl-social-media-youtube regular-text" value="<?php echo get_the_author_meta('cl_social_media_youtube', $userid) ? get_the_author_meta('cl_social_media_youtube', $userid) : ''; ?>"></td>
            </tr>
        </table>
<?php
    }
}
add_action('user_new_form', 'cepatlakoo_social_media_field');
add_action('edit_user_profile', 'cepatlakoo_social_media_field');
add_action('show_user_profile', 'cepatlakoo_social_media_field');

/**
 * Function to save user meta
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if (!function_exists('cepatlakoo_social_media_save')) {
    function cepatlakoo_social_media_save($user_id)
    {
        if (!current_user_can('edit_user', $user_id))
            return false;

        if (isset($_POST['cl-social-media-facebook']))
            update_usermeta($user_id, 'cl_social_media_facebook', ((strpos($_POST['cl-social-media-facebook'], 'http') === false && !empty($_POST['cl-social-media-facebook'])) ? 'https://' : '') . $_POST['cl-social-media-facebook']);

        if (isset($_POST['cl-social-media-twitter']))
            update_usermeta($user_id, 'cl_social_media_twitter', ((strpos($_POST['cl-social-media-twitter'], 'http') === false && !empty($_POST['cl-social-media-twitter'])) ? 'https://' : '') . $_POST['cl-social-media-twitter']);

        if (isset($_POST['cl-social-media-instagram']))
            update_usermeta($user_id, 'cl_social_media_instagram', ((strpos($_POST['cl-social-media-instagram'], 'http') === false && !empty($_POST['cl-social-media-instagram'])) ? 'https://' : '') . $_POST['cl-social-media-instagram']);

        if (isset($_POST['cl-social-media-pinterest']))
            update_usermeta($user_id, 'cl_social_media_pinterest', ((strpos($_POST['cl-social-media-pinterest'], 'http') === false && !empty($_POST['cl-social-media-pinterest'])) ? 'https://' : '') . $_POST['cl-social-media-pinterest']);

        if (isset($_POST['cl-social-media-youtube']))
            update_usermeta($user_id, 'cl_social_media_youtube', ((strpos($_POST['cl-social-media-youtube'], 'http') === false && !empty($_POST['cl-social-media-youtube'])) ? 'https://' : '') . $_POST['cl-social-media-youtube']);
    }
}
add_action('user_register', 'cepatlakoo_social_media_save');
add_action('personal_options_update', 'cepatlakoo_social_media_save');
add_action('edit_user_profile_update', 'cepatlakoo_social_media_save');

/**
 * Function to render header codes
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if (!function_exists('cepatlakoo_render_header_codes')) {
    function cepatlakoo_render_header_codes()
    {
        global $cl_options;

        if (!empty($cl_options['cepatlakoo_header_codes']))
            echo $cl_options['cepatlakoo_header_codes'];
    }
}
add_action('wp_head', 'cepatlakoo_render_header_codes', 99);

/**
 * Function to render footer codes
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.1.0
 */
if (!function_exists('cepatlakoo_render_footer_codes')) {
    function cepatlakoo_render_footer_codes()
    {
        global $cl_options;

        if (!empty($cl_options['cepatlakoo_footer_codes']))
            echo $cl_options['cepatlakoo_footer_codes'];
    }
}
add_action('wp_footer', 'cepatlakoo_render_footer_codes', 99);

/**
 * Function to add Bootstrap .table class
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.6.0
 */
function cepatlakoo_bootstrap_table_class($content)
{
    $content = str_replace(
        ['<table>', '</table>'],
        ['<table class="table table-bordered table-responsive">', '</table>'],
        $content
    );

    return $content;
}

add_filter( 'the_content', 'cepatlakoo_bootstrap_table_class' );

/**
 * Function to curl fbpixel API
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 2.6.4
 */
function cepatlakoo_fbpixel_api(){
    global $cl_options;

    $agent = sanitize_text_field($_POST['agent']);
    $event = sanitize_text_field($_POST['event']);
    $datas = $_POST['datas'];
    $token = ( isset($cl_options['cepatlakoo_facebook_pixel_token']) ) ? $cl_options['cepatlakoo_facebook_pixel_token'] : '';
    $pixels = ( isset($cl_options['cepatlakoo_facebook_pixel_id']) ) ? $cl_options['cepatlakoo_facebook_pixel_id'] : '';
    $ip = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];

    $current_user = wp_get_current_user();

    if ( 0 === $current_user->ID ) {
        $user_data = array(
            "client_user_agent" => $agent,
            "client_ip_address" => $ip,
        );
    } else {
        // https://developers.facebook.com/docs/facebook-pixel/advanced/advanced-matching
        $user_data = array(
            "client_user_agent" => $agent,
            "client_ip_address" => $ip,
            'em'          => hash('sha256', $current_user->user_email),
            'fn'          => hash('sha256', strtolower($current_user->user_firstname)),
            'ln'          => hash('sha256', strtolower($current_user->user_lastname)),
            'external_id' => strval( $current_user->ID ),
        );

        $user_id              = $current_user->ID;
        $user_data['ct']      = hash('sha256', get_user_meta( $user_id, 'billing_city', true ));
        $user_data['zp']      = hash('sha256', get_user_meta( $user_id, 'billing_postcode', true ));
        $user_data['country'] = hash('sha256', get_user_meta( $user_id, 'billing_country', true ));
        $user_data['st']      = hash('sha256', get_user_meta( $user_id, 'billing_state', true ));
        $user_data['ph']      = hash('sha256', preg_replace( '/[^0-9]/', '', get_user_meta( $user_id, 'billing_phone', true ) ));
    }

    if( !empty($token) && !empty($pixels) ){
        if( $event == 'InitiateCheckout' ){
            $raw_data = array(
                array(
                    "action_source" => "website",
                    "event_name" => "InitiateCheckout",
                    "event_time" => time(),
                    "user_data" => $user_data,
                    "custom_data" => $datas,
                ),
            );
        }
        else if( $event == 'AddPaymentInfo' ){
            $raw_data = array(
                array(
                    "action_source" => "website",
                    "event_name" => "AddPaymentInfo",
                    "event_time" => time(),
                    "user_data" => $user_data,
                    "custom_data" => $datas,
                ),
            );
        }
        else if( $event == 'Purchase' ){
            $raw_data = array(
                array(
                    "action_source" => "website",
                    "event_name" => "Purchase",
                    "event_time" => time(),
                    "user_data" => $user_data,
                    "custom_data" => $datas,
                ),
            );
        }
        else if( is_array($datas) && count($datas) > 0 ){
            $raw_data = array(
                array(
                    "action_source" => "website",
                    "event_name" => $event,
                    "event_time" => time(),
                    "user_data" => $user_data,
                    "custom_data" => $datas,
                ),
            );
        }
        else{
            $raw_data = array(
                array(
                    "action_source" => "website",
                    "event_name" => $event,
                    "event_time" => time(),
                    "user_data" => $user_data,
                ),
            );
        }

        $data = array(
            // "test_event_code" => "TEST22042",
            "access_token" => $token
        );
        $data['data'] = $raw_data;
            
        // var_dump($data); exit();
        $dataString = json_encode($data);
        $pixels = is_array($pixels) ? $pixels : [$pixels];
        foreach( $pixels as $pixel ){
            $ch = curl_init('https://graph.facebook.com/v15.0/'.$pixel.'/events');
            if (!$ch){
                $return = wp_send_json_success( array(
                    'message' => 'Error, curl init failed',
                    'status' => false,
                ), 200 );
            }
            
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($dataString))                                                                       
            );                                                                                                                                                                       
            $response[] = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close( $ch );
        }

        $return = wp_send_json_success( array(
            'message' => 'Success Triggered', 
            'log' => $response,
            'status' => true, 
        ), 200 );
    }
    else{
        $return = wp_send_json_success( array(
            'message' => 'Error, FB API token or pixel id not set',
            'status' => false,
        ), 200 );
    }

    return $return;
}
add_action('wp_ajax_cepatlakoo_fbpixel_api', 'cepatlakoo_fbpixel_api');
add_action('wp_ajax_nopriv_cepatlakoo_fbpixel_api', 'cepatlakoo_fbpixel_api');
