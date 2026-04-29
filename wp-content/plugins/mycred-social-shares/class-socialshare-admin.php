<?php

/**
 * Description of class-mycred-socialshare-admin
 *
 * @author soha
 */
class MyCred_Social_Shares_Settings {

    public function __construct() {
        add_action('mycred_add_menu', array($this, 'mycred_socialshare_menu'));
        add_action('admin_init', array($this, 'mycred_socialshare_registerFieldsOfSetting'));
        add_action('admin_enqueue_scripts', array($this, 'mycred_socialshare_enqueue_scripts'));
		add_action('wp_ajax_call_post_type_json', array($this, 'post_type_json_data_populate'));
    }
	
	public function post_type_json_data_populate() {
		
		global $wpdb;
		
		$post_type_data	= "'". implode("','",$_GET['post_type_array'])."'";
        
		$name = $_GET['q'];		 
$results =	$wpdb->get_results( "SELECT ID,post_name  FROM ".$wpdb->prefix."posts WHERE post_type IN (".$post_type_data.") AND post_status='publish' AND post_title LIKE '".$name."%' ");	
		$result_count =	count($results);
		echo"["; 
		$sno=0;
		foreach($results as $result )
		{	
			$sno++;
			if($sno==$result_count){$print_comma="";}else{$print_comma=',';}
			echo'{"id":"'.$result->ID.'","text":"'.$result->post_name.'"}'.$print_comma;
		}
		echo"]";
		wp_die();
	}
	
	
    public function mycred_socialshare_menu($mycred) {
        $mycred_version = (float) explode(' ', myCRED_VERSION)[0];

            if( $mycred_version >= 1.8 ){
        add_submenu_page(
                MYCRED_SLUG, 'Social Shares', 'Social Shares', $mycred->get_point_editor_capability(), 'social-shares-settings', array($this, 'social_share_settings')
        );
    }else{
         add_submenu_page(
                MYCRED_SLUG, 'Social Shares', 'Social Shares', $mycred->get_point_admin_capability(), 'social-shares-settings', array($this, 'social_share_settings')
        );
    }
    }

    public function mycred_socialshare_enqueue_scripts() {
        if (is_admin()) {
            wp_enqueue_style('core', plugin_dir_url(__FILE__) . 'assets/css/social-share-addon.css', false);
            wp_enqueue_style('mycred-social-icons', plugin_dir_url(__FILE__) . 'assets/css/mycred-social-icons.css', false);
            wp_register_script('admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '', true);
            wp_enqueue_script('admin-js');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
			
		// select2 files include
			wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css', false, '1.0', 'all' );
			wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_style( 'select2css' );
			wp_enqueue_script( 'select2' );
        }
    }

    public function mycred_socialshare_registerFieldsOfSetting() {
        register_setting('social-shares-settings', 'facebook_app_id');
        register_setting('social-shares-settings', 'facebook-share');
        register_setting('social-shares-settings', 'twitter-share');
        register_setting('social-shares-settings', 'pinterest-share');
        register_setting('social-shares-settings', 'linkedin-share');
        register_setting('social-shares-settings', 'facebook_title');
        register_setting('social-shares-settings', 'twitter_title');
        register_setting('social-shares-settings', 'pinterest_title');
        register_setting('social-shares-settings', 'linkedin_title');

        register_setting('social-shares-settings', 'display_all');
        register_setting('social-shares-settings', 'no_show_button');

    
		
		register_setting('social-shares-settings', 'show_post_type');
        register_setting('social-shares-settings', 'social_position');
		
		
        register_setting('social-shares-settings', 'button_style');
        register_setting('social-shares-settings', 'icon_style');
        register_setting('social-shares-settings', 'text_style');
        register_setting('social-shares-settings', 'icon_style_hover');
		
		register_setting('social-shares-settings', 'follow_button_style');
        register_setting('social-shares-settings', 'follow_icon_style');
        register_setting('social-shares-settings', 'follow_text_style');
        register_setting('social-shares-settings', 'follow_icon_style_hover');

        register_setting('social-shares-settings', 'social_fields_sort');
        register_setting('social-shares-settings', 'social_follow_button_fields_sort');
		
        register_setting('social-shares-settings', 'social_fields_check');
        register_setting('social-shares-settings', 'social_follow_button_fields_check');
		
        register_setting('social-shares-settings', 'social_fields_name');
        register_setting('social-shares-settings', 'social_follow_button_fields_name');
		
		
		register_setting('social-shares-settings', 'social_follow_button_custom_filed_text');
		
		
		
        register_setting('social-shares-settings', 'social_share_exclude_settings');
        
        register_setting('social-shares-settings', 'txtCheckNotification');
        register_setting('social-shares-settings', 'none_login_user_show_icons');
		
    }

   public function social_share_settings() {
        ?>
        <div class="wrap" id="myCRED-wrap">
            <h2><?php _e('General Settings', 'mycred-social-shares'); ?></h2>
            <form method="post" action="options.php" id="theme-options-form">

                <?php 
                
                settings_fields('social-shares-settings');
                do_settings_sections('social-shares-settings');
                
                $fields_order_default = array(
                    0 => array(
                        'id' => '0',
                        'name' => 'facebook',
                        'slug' => 'facebook_share'
                    ),
                    1 => array(
                        'id' => '1',
                        'name' => 'twitter',
                        'slug' => 'twitter_share'
                    ),
                    2 => array(
                        'id' => '2',
                        'name' => 'pinterest',
                        'slug' => 'pinterest_share'
                    ),
                    3 => array(
                        'id' => '3',
                        'name' => 'linkedin',
                        'slug' => 'linkedin_share'
                    )
                );
                
                $fields_follow_button_order_default = array(
                    0 => array(
                        'id' => '0',
                        'name' => 'facebook',
                        'slug' => 'facebook_follow_button_share'
                    ),
                    1 => array(
                        'id' => '1',
                        'name' => 'twitter',
                        'slug' => 'twitter_follow_button_share'
                    ),
                    2 => array(
                        'id' => '2',
                        'name' => 'youtube',
                        'slug' => 'youtube_follow_button_share'
                    ),
                    3 => array(
                        'id' => '3',
                        'name' => 'twitch',
                        'slug' => 'twitch_follow_button_share'
                    ),
                    4 => array(
                        'id' => '4',
                        'name' => 'instagram',
                        'slug' => 'instagram_follow_button_share'
                    ),
                );
                ?>

                <ul class="mycred mycred-tab-link subsubsub">
                    <li>
                        <a href="javascript:void(0)" class="nav-tab current" data-tab="mycred-settings-share">Share</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-tab" data-tab="mycred-settings-follow_up">Follow Up</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-tab" data-tab="mycred-settings-general">Settings</a>
                    </li>
                </ul>
    
                <div class="mycred-tab-content admin-mycred ">
                    <div class="mycred-tab-block" id="mycred-settings-general">
                        <table class="form-table">
                            <tr>
                                <th>Check this option to hide/unhide login notification.</th>
                                <td>
                                    <?php $check_notification = get_option('txtCheckNotification'); ?>
                                    <input type="hidden" value="general-settings" value="mycred-settings-general">
                                    <label for="txtCheckNotificationYes">
                                        <input type="radio" name="txtCheckNotification" id="txtCheckNotificationYes" value="yes" <?php if('yes'==$check_notification): echo 'checked'; endif; ?> >&nbsp; Show notification
                                    </label>&nbsp;&nbsp;&nbsp;

                                    <label for="txtCheckNotificationNo">
                                        <input type="radio" name="txtCheckNotification" id="txtCheckNotificationNo" value="no" <?php if('no'==$check_notification): echo 'checked'; endif; ?> >&nbsp; Hide notification
                                    </label>
                                </td>
                            </tr>   
                            <tr>
                                <th>Show share icons</th>
                                <td>
                                    <?php $none_login_user_show_icons = get_option('none_login_user_show_icons'); ?>
                                    
                                    <label for="none_login_user_show_icons">
                                        <input type="checkbox" name="none_login_user_show_icons" id="none_login_user_show_icons" value="yes" <?php if('yes'==$none_login_user_show_icons): echo 'checked'; endif; ?> >Non login user to show share/follow icons
                                    </label> 

                                                                   
                                </td>
                            </tr> 
                        </table>
                    </div>
    
                    <div class="mycred-tab-block" id="mycred-settings-share">
                        <table class="form-table">
                            <tr> 
                                <th scope="row"><?php _e('Enable the social networks', 'mycred-social-shares'); ?>
                                    <br>
                                    <span class="social-span" style="margin-top: 0px;font-size: 12px;"><?php _e('Drag and drop sharing icons to sort the display of sharing buttons', 'mycred-social-shares'); ?></span>

                                </th>
                                <td>
                                    <ul class="filter-fields-list">
                                        <?php
                                         $filter_fields_order = get_option('social_fields_sort', $fields_order_default);
                                         
                                        $selected = get_option('social_fields_check');
                                        foreach ($filter_fields_order as $value) {
                                            ?>
                                            <?php
                                            if (isset($value['id'])) {
                                                $id = $value['id'];
                                            }
                                            if (isset($value['name'])) {
                                                $name = $value['name'];
                                            }
                                            if (isset($value['slug'])) {
                                                $slug = $value['slug'];
                                            }
                                            ?>
                                            <li class="sortable-item mycred_share_width_fix">
                                            
                                            
                                               <div class="mycred-select-option"> 
                                               <i class="mycred-social-icon-bars"></i>
                                               <input type="checkbox" name="social_fields_check[<?php echo $id; ?>]" value="true" <?php 
                                                if (!empty($selected)) {
                                                    foreach ( $selected as $key => $value) {
                                                        if ($key == (int)$id) {
                                                            echo get_option('social_fields_check')[$id] == 'true' ? "checked" : "" ;
                                                        }
                                                    } 
                                                }

                                                ?>> <?php _e($name, 'mycred-social-shares'); ?>
                                                </div>
                                                <div class="my_cred_follow_button_input_field">
                                                <input type="text" class="form-control mycred-social-custom-fields" placeholder="<?php _e('Custom Title', 'mycred-social-shares'); ?>" name="social_fields_name[<?php echo $id; ?>]" value="<?php echo esc_attr(get_option('social_fields_name')[$id]); ?>" />
                                                </div>
                                                <input type="hidden" name="social_fields_sort[<?php echo $id; ?>][id]" value="<?php echo $id; ?>" />
                                                <input type="hidden" name="social_fields_sort[<?php echo $id; ?>][name]" value="<?php echo $name; ?>" />
                                                <input type="hidden" name="social_fields_sort[<?php echo $id; ?>][slug]" value="<?php echo $slug; ?>" />
                                            </li>

                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Facebook app Settings: ', 'mycred-social-shares'); ?></th>
                                <td> <a href="https://developers.facebook.com/apps" target="_blank">Create an App</a> if you don't have one fill Consumer key. </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Facebook app ID: ', 'mycred-social-shares'); ?></th>
                                <td><input type="text" id="facebook_app_id" name="facebook_app_id" value="<?php echo get_option('facebook_app_id'); ?>" class="form-control" >
                                <br><small class="response_message">(This is not the Facebook app id of an application)</small>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Show social sharing buttons for which Post types? ', 'mycred-social-shares'); ?></th>
                                <td>
                                    <?php
                                    
                                    foreach (get_post_types() as $post_type) {
                                        $exclude = array('attachment', 'revision','scheduled-action','shop_order_refund','shop_coupon', 'nav_menu_item', 'shop_order','product_variation','custom_css', 'customize_changeset', 'oembed_cache', 'mycred_badge', 'user_request', 'wp_block');
                                        if (TRUE === in_array($post_type, $exclude))
                                            continue;
                                        ?>
                                        <div style="display: inline-block; margin: 5px;">
                                        <input type="checkbox" class="show_post_type" name="show_post_type[]" value="<?php echo $post_type; ?>"  <?php
                                        $empty = array();
                                        $array_option = get_option('show_post_type') == '' ? $empty : get_option('show_post_type');
                                        if (TRUE === in_array($post_type, $array_option)) {
                                            echo "checked";
                                        }
                                        ?>>

                                          <?php _e(get_post_type_object($post_type)->label, 'mycred-social-shares'); ?>
                                       </div>
                                       <?php
                                    }
                                    ?>

                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Show the social sharing buttons', 'mycred-social-shares'); ?></th>
                                <td>
                                    <select class="form-control" name="social_position">
                                        <option value="none" <?php echo get_option('social_position') == "none" ? "selected" : "" ?>><?php _e('None', 'mycred-social-shares'); ?></option>
                                        <option value="before" <?php echo get_option('social_position') == "before" ? "selected" : "" ?>><?php _e('Before the content', 'mycred-social-shares'); ?></option>
                                        <option value="after" <?php echo get_option('social_position') == "after" ? "selected" : "" ?>><?php _e('After the Content', 'mycred-social-shares'); ?></option>
                                        <option value="both" <?php echo get_option('social_position') == "both" ? "selected" : "" ?>><?php _e('Before and After the content', 'mycred-social-shares'); ?></option>
                                    </select><br />
                                    <small>(Note: Selelct "None" to use shortcode or function)</small>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Exclude posts or pages etc.', 'mycred-social-shares'); ?></th>
                                <td class="mycred-socialshare-select">
                                
                                <?php 
                                    $posts_ids = get_option('social_share_exclude_settings');
                                ?>      
                                <select class="js-example-basic-multiple form-control" name="social_share_exclude_settings[]" multiple="multiple">
                                <?php 
                                if($posts_ids){
                                    foreach($posts_ids as $posts_id)
                                    {
                                        ?><option value="<?php echo $posts_id;?>" selected><?php echo get_the_title( $posts_id );?></option><?php 
                                    }
                                }
                                ?>
                                </select>
                                     
                                </td>
                            </tr>
                            <script>
                                function post_type_value_function() {   
                                    post_type_value = [];
                                    jQuery("input[name^='show_post_type']").each(function() {
                                        if(jQuery(this).prop("checked") == true){
                                            post_type_value.push(jQuery(this).val());
                                        }
                                    });
                                    return post_type_value;
                                }
                                ajax_url = <?php   echo "'".admin_url( 'admin-ajax.php' )."'"; ?>  
                                jQuery(document).ready(function($) {
                                    jQuery('.js-example-basic-multiple').select2({
                                        placeholder: "Search page or Post etc.",
                                        ajax: {
                                        url: ajax_url,
                                        data: function (params) {
                                            return {
                                                q: params.term,
                                                'action': 'call_post_type_json',
                                                'post_type_array':  post_type_value_function()
                                            };
                                        },
                                        dataType: 'json',
                                        processResults: function (data, params) {
                                                return  { 
                                                    results: data 
                                                };
                                            }
                                        }
                                    });
                                });
                            </script>
                            <style>
                                .mycred-socialshare-select .select2-container .select2-selection .select2-search{
                                    margin: -25px 0;
                                }
                                .select2-container--default .select2-results__option[aria-selected=true] {
                                background-color: #f1f1f1;
                                cursor: not-allowed;
                                color: #909090;
                                }
                                li.select2-results__option {
                                border-bottom: 1px solid #e1e1e1;
                                }
                                dd, li {
                                margin-bottom: 2px;
                                }
                                button.mycred-social-icons, a.mycred-social-icons, button.social-text {
                                    margin: 7px 0;
                                }
                                .my_cred_follow_button_input_field .form-control {
                                    width: 49% !important;
                                }
                            </style>
                            <tr valign="top">
                                <th scope="row"></th>
                                <td>
                                    <code>[mycred_display_social_icons]</code> use this shortcode incase if your theme is custom or other functions like to show social icons before and after the content is not working properly on your site. Also this shortcode will help you to display social icons on any position.<br /><br />
                                    <code>
                                        &lt;?php 
                                            if ( class_exists('MyCred_Social_Shares_Frontend') )
                                                echo mycred_display_social_icons();
                                        ?&gt;    
                                    </code>&nbsp; this code is for developers to display social icons in themes.
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Social Sharing button style', 'mycred-social-shares'); ?></th>
                                <td>
                                    <div class="clearfix" style="margin-bottom: 10px;">
                                        <input type="checkbox" class="check" name="button_style" value="true"  <?php echo get_option('button_style') == 'true' ? "checked" : "" ?>><?php _e('Button Style', 'mycred-social-shares'); ?><br/>
                                        <button class="mycred-social-icons mycred-social-icon-facebook"><a href="#">facebook</a></button>
                                        <button class="mycred-social-icons mycred-social-icon-twitter"><a href="#">twitter</a></button>
                                        <button class="mycred-social-icons mycred-social-icon-linkedin"><a href="#">linkedin</a></button>
                                        <button class="mycred-social-icons mycred-social-icon-pinterest"><a href="#">pinterest</a></button>
                                    </div>
                                    <div class="clearfix" style="margin-bottom: 10px;">
                                        <input type="checkbox" class="check" name="icon_style" value="true"  <?php echo get_option('icon_style') == 'true' ? "checked" : "" ?>><?php _e('Icon Style', 'mycred-social-shares'); ?><br/>
                                        <a href="#" class="mycred-social-icons mycred-social-icon-facebook"></a>
                                        <a href="#" class="mycred-social-icons mycred-social-icon-twitter"></a>
                                        <a href="#" class="mycred-social-icons mycred-social-icon-linkedin"></a>
                                        <a href="#" class="mycred-social-icons mycred-social-icon-pinterest"></a>
                                    </div>
                                    <div class="clearfix" style="margin-bottom: 10px;">
                                        <input type="checkbox" class="check" name="text_style" value="true"  <?php echo get_option('text_style') == 'true' ? "checked" : "" ?>><?php _e('Text Style', 'mycred-social-shares'); ?><br/>
                                        <button class="facebook social-text"><a href="#">facebook</a></button>
                                        <button class="twitter social-text"><a href="#">twitter</a></button>
                                        <button class="linkedin social-text"><a href="#">linkedin</a></button>
                                        <button class="pinterest social-text"><a href="#">pinterest</a></button>
                                    </div>
                                    <div class="clearfix" style="margin-bottom: 10px;">
                                        <input type="checkbox" class="check" name="icon_style_hover" value="true"  <?php echo get_option('icon_style_hover') == 'true' ? "checked" : "" ?>><?php _e('Icon / text on-hover style', 'mycred-social-shares'); ?><br/>
                                        <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-facebook"></a>
                                        <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-twitter"></a>
                                        <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-linkedin"></a>
                                        <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-pinterest"></a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="mycred-tab-block" id="mycred-settings-follow_up">
                        <table class="form-table">
                            <tr valign="top"> 
                                <th scope="row"><?php _e('Enable the social follow button', 'mycred-social-shares'); ?>
                                <br>
                                    <span class="social-span" style="margin-top: 0px;font-size: 12px;"><?php _e('Drag and drop follow button to sort the display of follow buttons', 'mycred-social-shares'); ?></span>

                                </th>
                                <td>
                                    <ul class="filter-fields-list">
                                        <?php
                                         $filter_follow_button_fields_order = get_option('social_follow_button_fields_sort', $fields_follow_button_order_default);
                                    
                                        $selected = get_option('social_follow_button_fields_check');
                                        foreach ($filter_follow_button_fields_order as $value) {
                                            ?>
                                            <?php
                                            if (isset($value['id'])) {
                                                $id = $value['id'];
                                            }
                                            if (isset($value['name'])) {
                                                $name = $value['name'];
                                            }
                                            if (isset($value['slug'])) {
                                                $slug = $value['slug'];
                                            }
                                            ?>
                                            <li class="sortable-item">
                                            
                                               <div class="mycred-select-option"> 
                                               <i class="mycred-social-icon-bars"></i>
                                               <input type="checkbox" name="social_follow_button_fields_check[<?php echo $id; ?>]" value="true" <?php 
                                                if (!empty($selected)) {
                                                    foreach ( $selected as $key => $value) {
                                                        if ($key == (int)$id) {
                                                            echo get_option('social_follow_button_fields_check')[$id] == 'true' ? "checked" : "" ;
                                                        }
                                                    } 
                                                }

                                                ?>> <?php _e($name, 'mycred-social-shares'); ?>
                                                </div>
                                                <div class="my_cred_follow_button_input_field">
                                                    <input type="text" class="form-control mycred-social-custom-fields" placeholder="<?php _e('Custom Title', 'mycred-social-shares'); ?>" name="social_follow_button_custom_filed_text[<?php echo $id; ?>]" value="<?php echo esc_attr(get_option('social_follow_button_custom_filed_text')[$id]); ?>" />
                                                    <input type="text" class="form-control mycred-social-custom-fields" placeholder="<?php _e('Page Name', 'mycred-social-shares'); ?>" name="social_follow_button_fields_name[<?php echo $id; ?>]" value="<?php echo esc_attr(get_option('social_follow_button_fields_name')[$id]); ?>" />
                                                </div>
                                                <input type="hidden" name="social_follow_button_fields_sort[<?php echo $id; ?>][id]" value="<?php echo $id; ?>" />
                                                <input type="hidden" name="social_follow_button_fields_sort[<?php echo $id; ?>][name]" value="<?php echo $name; ?>" />
                                                <input type="hidden" name="social_follow_button_fields_sort[<?php echo $id; ?>][slug]" value="<?php echo $slug; ?>" />
                                            </li>

                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"></th>
                                <td>
                                    <code>[mycred_display_social_follow_button]</code> use this shortcode incase if your theme is custom or other functions like to show social icons before and after the content is not working properly on your site. Also this shortcode will help you to display social icons on any position.<br /><br />
                                    <code>
                                        &lt;?php 
                                            if ( class_exists('MyCred_Social_Follow_Button_Frontend') )
                                                echo mycred_display_social_follow_button_Frontend();
                                        ?&gt;    
                                    </code>&nbsp; this code is for developers to display social icons in themes.
                                </td>
                            </tr>
                 
                            <tr valign="top">
                                <th scope="row"><?php _e('Social Follow button style', 'mycred-social-shares'); ?></th>
                                <td>
                                <div class="clearfix">
                                    <input type="checkbox" class="follow_check" name="follow_button_style" value="true"  <?php echo get_option('follow_button_style') == 'true' ? "checked" : "" ?>><?php _e('Button Style', 'mycred-social-shares'); ?><br/>

                                    <button class="mycred-social-icons mycred-social-icon-facebook"><a href="#">facebook</a></button>
                                    <button class="mycred-social-icons mycred-social-icon-twitter"><a href="#">twitter</a></button>
                                    <button class="mycred-social-icons mycred-social-icon-youtube"><a href="#">youtube</a></button>
                                    <button class="mycred-social-icons mycred-social-icon-twitch"><a href="#">twitch</a></button>
                                    <button class="mycred-social-icons mycred-social-icon-instagram"><a href="#">instagram</a></button>

                                </div>
                                <div class="clearfix">
                                    <input type="checkbox" class="follow_check" name="follow_icon_style" value="true"  <?php echo get_option('follow_icon_style') == 'true' ? "checked" : "" ?>><?php _e('Icon Style', 'mycred-social-shares'); ?><br/>
                                    <a href="#" class="mycred-social-icons mycred-social-icon-facebook"></a>
                                    <a href="#" class="mycred-social-icons mycred-social-icon-twitter"></a>
                                    <a href="#" class="mycred-social-icons mycred-social-icon-youtube"></a>
                                    <a href="#" class="mycred-social-icons mycred-social-icon-twitch"></a>
                                    <a href="#" class="mycred-social-icons mycred-social-icon-instagram"></a>
                                </div>
                                <div class="clearfix">
                                    <input type="checkbox" class="follow_check" name="follow_text_style" value="true"  <?php echo get_option('follow_text_style') == 'true' ? "checked" : "" ?>><?php _e('Text Style', 'mycred-social-shares'); ?><br/>
                                    <button class="facebook social-text"><a href="#">facebook</a></button>
                                    <button class="twitter social-text"><a href="#">twitter</a></button>
                                    <button class="youtube social-text"><a href="#">youtube</a></button>
                                    <button class="twitch social-text"><a href="#">twitch</a></button>
                                    <button class="instagram social-text"><a href="#">Instagram</a></button>
                                </div>
                                <div class="clearfix">
                                    <input type="checkbox" class="follow_check" name="follow_icon_style_hover" value="true"  <?php echo get_option('follow_icon_style_hover') == 'true' ? "checked" : "" ?>><?php _e('Icon / text on-hover style', 'mycred-social-shares'); ?><br/>
                                    <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-facebook"></a>
                                    <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-twitter"></a>
                                    <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-youtube"></a>
                                    <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-twitch"></a>
                                    <a href="#" class="i-text-admin mycred-social-icons mycred-social-icon-instagram"></a><br>
                                </div>
                                </td>
                            </tr>
                        </table><br><br>
                    </div>
                </div>
    
        <?php submit_button(); ?>
            </form>
        </div>
        <script>
        <?php if(get_option('facebook_app_id')){?>
        facebook_app_id = '<?php echo get_option('facebook_app_id'); ?>';
        jQuery.ajax({
        url : "https://graph.facebook.com/" + facebook_app_id + "",
        statusCode: {
                200: function() {
                    jQuery('#facebook_app_id').addClass('correct-app-id');
                },
                404: function() {
                    jQuery('#facebook_app_id').addClass('incorrect-app-id');
                    jQuery('.response_message').show();
                },
                400: function() {
                    jQuery('#facebook_app_id').addClass('incorrect-app-id');
                    jQuery('.response_message').show();
                }
        }
});

        <?php } ?>
            jQuery(document).ready(function ($) {
                $('.check').click(function () {
                    $('.check').not(this).prop('checked', false);
                });
                $('.follow_check').click(function () {
                    $('.follow_check').not(this).prop('checked', false);
                });

                if ($('.display-all').prop('checked')) {
                    $('.display-none').hide();
                }
                $('.display-all').click(function () {
                    if ($('.display-all').prop('checked')) {
                        $('.display-none').hide();
                    } else {
                        $('.display-none').show();
                    }
                });
            });
        </script>
        <?php
    }
}