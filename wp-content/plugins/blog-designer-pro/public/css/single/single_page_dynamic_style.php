<?php
/**
 * Single posts custom style css
 */
add_action('wp_head', 'bdp_single_page_style', 13);

function bdp_single_page_style() {
    global $post;
    if(isset($bdp_settings['enable_lazy_load']) && 1 == $bdp_settings['enable_lazy_load']){
        add_filter('wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5);
    }
    if (is_single() && isset($post->post_type) && ($post->post_type == 'post' || $post->post_type == 'product' || $post->post_type == 'download')) {
        $post_type = $post->post_type;
        $post_id = $post->ID;
       
        if( $post_type == 'post'){
            $cat_ids = wp_get_post_categories($post_id);
            $tag_ids = wp_get_post_tags($post_id);
            $single_data = Bdp_Template::get_single_template_settings($cat_ids, $tag_ids);
        } 
        if($post_type == 'product') {
            $cat_ids = wp_get_post_terms($post_id,'product_cat',array('fields'=>'ids'));
            $tag_ids = wp_get_post_terms($post_id,'product_tag',array('fields'=>'ids'));
            $single_data = Bdp_Template::get_single_prodcut_template_settings($cat_ids, $tag_ids);
        }
        if($post_type == 'download') {
            $cat_ids = wp_get_post_terms($post_id,'download_category',array('fields'=>'ids'));
            $tag_ids = wp_get_post_terms($post_id,'download_tag',array('fields'=>'ids'));
            $single_data = Bdp_Template::get_single_download_template_settings($cat_ids, $tag_ids);
        }
     
        if (!$single_data) {
            return;
        }
        if ($single_data && is_serialized($single_data)) {
            $single_data_setting = unserialize($single_data);
        }
        $display_comment = '';
        if(isset($single_data_setting['display_comment'])){
            $display_comment = $single_data_setting['display_comment'];
        }
       
        $display_date           = isset( $single_data_setting['display_author_data'] ) ? $single_data_setting['display_date'] : '';

        $load_single_font = array();
        if (isset($single_data_setting['override_single']) && $single_data_setting['override_single'] == 1) {
            $firstletter_fontsize = $firstletter_contentcolor = $firstletter_contentfontface = '';
            if (isset($single_data_setting['firstletter_big']) && $single_data_setting['firstletter_big'] == 1) {
                $firstletter_fontsize = (isset($single_data_setting['firstletter_fontsize']) && $single_data_setting['firstletter_fontsize'] != '') ? $single_data_setting['firstletter_fontsize'] : 30;
                $firstletter_contentfontface = (isset($single_data_setting['firstletter_font_family']) && $single_data_setting['firstletter_font_family'] != '') ? $single_data_setting['firstletter_font_family'] : "";
                if (isset($single_data_setting['firstletter_font_family_font_type']) && $single_data_setting['firstletter_font_family_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $firstletter_contentfontface;
                }
                $firstletter_contentcolor = (isset($single_data_setting['firstletter_contentcolor']) && $single_data_setting['firstletter_contentcolor'] != '') ? $single_data_setting['firstletter_contentcolor'] : "#000000";
            }
            $template_name = apply_filters('bdp_filter_template', $single_data_setting['template_name']);
            $templatecolor = (isset($single_data_setting['template_color']) && $single_data_setting['template_color'] != '') ? $single_data_setting['template_color'] : "#000"; 
            $templatecolor1 = (isset($single_data_setting['template_color1']) && $single_data_setting['template_color1'] != '') ? $single_data_setting['template_color1'] : "#a2c5bf";
            $templatecolor2 = (isset($single_data_setting['template_color2']) && $single_data_setting['template_color2'] != '') ? $single_data_setting['template_color2'] : "#72616e"; 
            $templatecolor3 = (isset($single_data_setting['template_color3']) && $single_data_setting['template_color3'] != '') ? $single_data_setting['template_color3'] : "#e8846b"; 
            $templatecolor4 = (isset($single_data_setting['template_color4']) && $single_data_setting['template_color4'] != '') ? $single_data_setting['template_color4'] : "#16528e"; 
            $templatecolor5 = (isset($single_data_setting['template_color5']) && $single_data_setting['template_color5'] != '') ? $single_data_setting['template_color5'] : "#e54b4b"; 
            $templatecolor6 = (isset($single_data_setting['template_color6']) && $single_data_setting['template_color6'] != '') ? $single_data_setting['template_color6'] : "#167c80"; 
            $template_bgcolor = (isset($single_data_setting['template_bgcolor']) && $single_data_setting['template_bgcolor'] != '') ? $single_data_setting['template_bgcolor'] : "#fff";

            $titlecolor = (isset($single_data_setting['template_titlecolor']) && $single_data_setting['template_titlecolor'] != '') ? $single_data_setting['template_titlecolor'] : "#000";
            $template_titlefontsize = (isset($single_data_setting['template_titlefontsize']) && $single_data_setting['template_titlefontsize'] != '') ? $single_data_setting['template_titlefontsize'] : 30;
            $template_titlefontface = (isset($single_data_setting['template_titlefontface']) && $single_data_setting['template_titlefontface'] != '') ? $single_data_setting['template_titlefontface'] : "";

            if (isset($single_data_setting['template_titlefontface_font_type']) && $single_data_setting['template_titlefontface_font_type'] == "Google Fonts") {
                $load_single_font[] = $template_titlefontface;
            }

            $winter_category_color = (isset($single_data_setting['winter_category_color']) && $single_data_setting['winter_category_color'] != '') ? $single_data_setting['winter_category_color'] : "#e7492f";
            $linkcolor = (isset($single_data_setting['template_ftcolor']) && $single_data_setting['template_ftcolor'] != '') ? $single_data_setting['template_ftcolor'] : "#000";
            $linkhovercolor = (isset($single_data_setting['template_fthovercolor']) && $single_data_setting['template_fthovercolor'] != '') ? $single_data_setting['template_fthovercolor'] : "#000";

            $contentcolor = (isset($single_data_setting['template_contentcolor']) && $single_data_setting['template_contentcolor'] != '') ? $single_data_setting['template_contentcolor'] : "#000";
            $content_fontsize = (isset($single_data_setting['content_fontsize']) && $single_data_setting['content_fontsize'] != '') ? $single_data_setting['content_fontsize'] : 16;
            $content_fontface = (isset($single_data_setting['template_contentfontface']) && $single_data_setting['template_contentfontface'] != '') ? $single_data_setting['template_contentfontface'] : "";
            if (isset($single_data_setting['template_contentfontface_font_type']) && $single_data_setting['template_contentfontface_font_type'] == "Google Fonts") {
                $load_single_font[] = $content_fontface;
            }
            
            //for lazy load
            $enable_lazy_load = (isset($single_data_setting['enable_lazy_load']) && $single_data_setting['enable_lazy_load'] != '') ? $single_data_setting['enable_lazy_load'] : '';
            $enable_lazy_load_blur_image = (isset($single_data_setting['enable_lazy_load_blur_image']) && $single_data_setting['enable_lazy_load_blur_image'] != '') ? $single_data_setting['enable_lazy_load_blur_image'] : '';
           
            $template_lazy_load_color = (isset($single_data_setting['template_lazy_load_color']) && $single_data_setting['template_lazy_load_color'] != '') ? $single_data_setting['template_lazy_load_color'] : "#000";

            // for related post title
            $relatedTitleColor = (isset($single_data_setting['related_title_color']) && $single_data_setting['related_title_color'] != '') ? $single_data_setting['related_title_color'] : "#333333";
            $relatedTitleSize = (isset($single_data_setting['related_title_fontsize']) && $single_data_setting['related_title_fontsize'] != '') ? $single_data_setting['related_title_fontsize'] : 25;
            $relatedTitleFace = (isset($single_data_setting['related_title_fontface']) && $single_data_setting['related_title_fontface'] != '') ? $single_data_setting['related_title_fontface'] : "";

            //Post title setting
            $total_noofline 				 = isset( $single_data_setting['total_noofline'] ) ? $single_data_setting['total_noofline'] : '2';
            $template_post_content_wrap_from = isset( $single_data_setting['template_post_content_wrap_from'] ) ? $single_data_setting['template_post_content_wrap_from'] : 'normal';
            $post_length_setting 				 = isset( $single_data_setting['post_length_setting'] ) ? $single_data_setting['post_length_setting'] : '0';

            if (isset($single_data_setting['related_title_fontface_font_type']) && $single_data_setting['related_title_fontface_font_type'] == "Google Fonts") {
                $load_single_font[] = $relatedTitleFace;
            }
            if($post_type == 'download') {
                /**
                * Easy Digital Download Price Text
                */

                $bdp_edd_price_color = isset($single_data_setting['bdp_edd_price_color']) ? $single_data_setting['bdp_edd_price_color'] : '#444444';
                $bdp_edd_price_alignment = isset($single_data_setting['bdp_edd_price_alignment']) ? $single_data_setting['bdp_edd_price_alignment'] : 'left';
                $bdp_edd_price_paddingleft = isset($single_data_setting['bdp_edd_price_paddingleft']) ? $single_data_setting['bdp_edd_price_paddingleft'] : '10';
                $bdp_edd_price_paddingright = isset($single_data_setting['bdp_edd_price_paddingright']) ? $single_data_setting['bdp_edd_price_paddingright'] : '10';
                $bdp_edd_price_paddingtop = isset($single_data_setting['bdp_edd_price_paddingtop']) ? $single_data_setting['bdp_edd_price_paddingtop'] : '10';
                $bdp_edd_price_paddingbottom = isset($single_data_setting['bdp_edd_price_paddingbottom']) ? $single_data_setting['bdp_edd_price_paddingbottom'] : '10';
                $bdp_edd_pricefontface = (isset($single_data_setting['bdp_edd_pricefontface']) && $single_data_setting['bdp_edd_pricefontface'] != '') ? $single_data_setting['bdp_edd_pricefontface'] : '';
                if (isset($single_data_setting['bdp_edd_pricefontface_font_type']) && $single_data_setting['bdp_edd_pricefontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_edd_pricefontface;
                }
                $bdp_edd_pricefontsize = (isset($single_data_setting['bdp_edd_pricefontsize']) && $single_data_setting['bdp_edd_pricefontsize'] != '') ? $single_data_setting['bdp_edd_pricefontsize'] : 18;
                $bdp_edd_price_font_weight = isset($single_data_setting['bdp_edd_price_font_weight']) ? $single_data_setting['bdp_edd_price_font_weight'] : '';
                $bdp_edd_price_font_line_height = isset($single_data_setting['bdp_edd_price_font_line_height']) ? $single_data_setting['bdp_edd_price_font_line_height'] : '';
                $bdp_edd_price_font_italic = isset($single_data_setting['bdp_edd_price_font_italic']) ? $single_data_setting['bdp_edd_price_font_italic'] : '';
                $bdp_edd_price_font_text_decoration = isset($single_data_setting['bdp_edd_price_font_text_decoration']) ? $single_data_setting['bdp_edd_price_font_text_decoration'] : 'none';

                $bdp_edd_price_font_letter_spacing = isset($single_data_setting['bdp_edd_price_font_letter_spacing']) ? $single_data_setting['bdp_edd_price_font_letter_spacing'] : '0';

                /**
                 * Edd Add To Cart Button 
                 */
                $bdp_edd_addtocart_textcolor = isset($single_data_setting['bdp_edd_addtocart_textcolor']) ? $single_data_setting['bdp_edd_addtocart_textcolor'] : '';
                $bdp_edd_addtocart_backgroundcolor = isset($single_data_setting['bdp_edd_addtocart_backgroundcolor']) ? $single_data_setting['bdp_edd_addtocart_backgroundcolor'] : '';
                $bdp_edd_addtocart_text_hover_color = isset($single_data_setting['bdp_edd_addtocart_text_hover_color']) ? $single_data_setting['bdp_edd_addtocart_text_hover_color'] : '';
                $bdp_edd_addtocart_hover_backgroundcolor = isset($single_data_setting['bdp_edd_addtocart_hover_backgroundcolor']) ? $single_data_setting['bdp_edd_addtocart_hover_backgroundcolor'] : '';
                $bdp_edd_addtocartbutton_borderleft = isset($single_data_setting['bdp_edd_addtocartbutton_borderleft']) ? $single_data_setting['bdp_edd_addtocartbutton_borderleft'] : '';
                $bdp_edd_addtocartbutton_borderleftcolor = isset($single_data_setting['bdp_edd_addtocartbutton_borderleftcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_borderleftcolor'] : '';
                $bdp_edd_addtocartbutton_borderright = isset($single_data_setting['bdp_edd_addtocartbutton_borderright']) ? $single_data_setting['bdp_edd_addtocartbutton_borderright'] : '';
                $bdp_edd_addtocartbutton_borderrightcolor = isset($single_data_setting['bdp_edd_addtocartbutton_borderrightcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_borderrightcolor'] : '';
                $bdp_edd_addtocartbutton_bordertop = isset($single_data_setting['bdp_edd_addtocartbutton_bordertop']) ? $single_data_setting['bdp_edd_addtocartbutton_bordertop'] : '';
                $bdp_edd_addtocartbutton_bordertopcolor = isset($single_data_setting['bdp_edd_addtocartbutton_bordertopcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_bordertopcolor'] : '';
                $bdp_edd_addtocartbutton_borderbuttom = isset($single_data_setting['bdp_edd_addtocartbutton_borderbuttom']) ? $single_data_setting['bdp_edd_addtocartbutton_borderbuttom'] : '';
                $bdp_edd_addtocartbutton_borderbottomcolor = isset($single_data_setting['bdp_edd_addtocartbutton_borderbottomcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_borderbottomcolor'] : '';
                $display_edd_addtocart_button_border_radius = isset($single_data_setting['display_edd_addtocart_button_border_radius']) ? $single_data_setting['display_edd_addtocart_button_border_radius'] : '';
                $bdp_edd_addtocartbutton_padding_leftright = isset($single_data_setting['bdp_edd_addtocartbutton_padding_leftright']) ? $single_data_setting['bdp_edd_addtocartbutton_padding_leftright'] : '';
                $bdp_edd_addtocartbutton_padding_topbottom = isset($single_data_setting['bdp_edd_addtocartbutton_padding_topbottom']) ? $single_data_setting['bdp_edd_addtocartbutton_padding_topbottom'] : '';
                $bdp_edd_addtocartbutton_margin_leftright = isset($single_data_setting['bdp_edd_addtocartbutton_margin_leftright']) ? $single_data_setting['bdp_edd_addtocartbutton_margin_leftright'] : '';
                $bdp_edd_addtocartbutton_margin_topbottom = isset($single_data_setting['bdp_edd_addtocartbutton_margin_topbottom']) ? $single_data_setting['bdp_edd_addtocartbutton_margin_topbottom'] : '';
                $bdp_edd_addtocartbutton_alignment = isset($single_data_setting['bdp_edd_addtocartbutton_alignment']) ? $single_data_setting['bdp_edd_addtocartbutton_alignment'] : 'left';

                $bdp_edd_addtocartbutton_hover_borderleft = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderleft']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderleft'] : '';
                $bdp_edd_addtocartbutton_hover_borderleftcolor = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderleftcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderleftcolor'] : '';
                $bdp_edd_addtocartbutton_hover_borderright = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderright']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderright'] : '';
                $bdp_edd_addtocartbutton_hover_borderrightcolor = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderrightcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderrightcolor'] : '';
                $bdp_edd_addtocartbutton_hover_bordertop = isset($single_data_setting['bdp_edd_addtocartbutton_hover_bordertop']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_bordertop'] : '';
                $bdp_edd_addtocartbutton_hover_bordertopcolor = isset($single_data_setting['bdp_edd_addtocartbutton_hover_bordertopcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_bordertopcolor'] : '';
                $bdp_edd_addtocartbutton_hover_borderbuttom = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderbuttom']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderbuttom'] : '';
                $bdp_edd_addtocartbutton_hover_borderbottomcolor = isset($single_data_setting['bdp_edd_addtocartbutton_hover_borderbottomcolor']) ? $single_data_setting['bdp_edd_addtocartbutton_hover_borderbottomcolor'] : '';
                $display_edd_addtocart_button_border_hover_radius = isset($single_data_setting['display_edd_addtocart_button_border_hover_radius']) ? $single_data_setting['display_edd_addtocart_button_border_hover_radius'] : '0';

                $bdp_edd_addtocart_button_top_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_top_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_top_box_shadow'] : '';
                $bdp_edd_addtocart_button_top_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_top_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_top_box_shadow'] : '';
                $bdp_edd_addtocart_button_right_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_right_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_right_box_shadow'] : '';
                $bdp_edd_addtocart_button_bottom_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_bottom_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_bottom_box_shadow'] : '';
                $bdp_edd_addtocart_button_left_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_left_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_left_box_shadow'] : '';
                $bdp_edd_addtocart_button_box_shadow_color = isset($single_data_setting['bdp_edd_addtocart_button_box_shadow_color']) ? $single_data_setting['bdp_edd_addtocart_button_box_shadow_color'] : '';
                
                $bdp_edd_addtocart_button_hover_top_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_hover_top_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_hover_top_box_shadow'] : '';
                $bdp_edd_addtocart_button_hover_right_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_hover_right_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_hover_right_box_shadow'] : '';
                $bdp_edd_addtocart_button_hover_bottom_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_hover_bottom_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_hover_bottom_box_shadow'] : '';
                $bdp_edd_addtocart_button_hover_left_box_shadow = isset($single_data_setting['bdp_edd_addtocart_button_hover_left_box_shadow']) ? $single_data_setting['bdp_edd_addtocart_button_hover_left_box_shadow'] : '';
                $bdp_edd_addtocart_button_hover_box_shadow_color = isset($single_data_setting['bdp_edd_addtocart_button_hover_box_shadow_color']) ? $single_data_setting['bdp_edd_addtocart_button_hover_box_shadow_color'] : '';
                $bdp_edd_addtocart_button_fontface = (isset($single_data_setting['bdp_edd_addtocart_button_fontface']) && $single_data_setting['bdp_edd_addtocart_button_fontface'] != '') ? $single_data_setting['bdp_edd_addtocart_button_fontface'] : '';
                if (isset($single_data_setting['bdp_edd_addtocart_button_fontface_font_type']) && $single_data_setting['bdp_edd_addtocart_button_fontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_edd_addtocart_button_fontface;
                }
                $bdp_edd_addtocart_button_fontsize = (isset($single_data_setting['bdp_edd_addtocart_button_fontsize']) && $single_data_setting['bdp_edd_addtocart_button_fontsize'] != '') ? $single_data_setting['bdp_edd_addtocart_button_fontsize'] : "inherit";
                $bdp_edd_addtocart_button_font_weight = isset($single_data_setting['bdp_edd_addtocart_button_font_weight']) ? $single_data_setting['bdp_edd_addtocart_button_font_weight'] : '';
                $bdp_edd_addtocart_button_font_italic = isset($single_data_setting['bdp_edd_addtocart_button_font_italic']) ? $single_data_setting['bdp_edd_addtocart_button_font_italic'] : '';
                $bdp_edd_addtocart_button_letter_spacing = isset($single_data_setting['bdp_edd_addtocart_button_letter_spacing']) ? $single_data_setting['bdp_edd_addtocart_button_letter_spacing'] : '0';

                $display_addtocart_button_line_height = isset($single_data_setting['display_addtocart_button_line_height']) ? $single_data_setting['display_addtocart_button_line_height'] : '1.5';
                $bdp_edd_addtocart_button_font_text_transform = isset($single_data_setting['bdp_edd_addtocart_button_font_text_transform']) ? $single_data_setting['bdp_edd_addtocart_button_font_text_transform'] : 'none';
                $bdp_edd_addtocart_button_font_text_decoration = isset($single_data_setting['bdp_edd_addtocart_button_font_text_decoration']) ? $single_data_setting['bdp_edd_addtocart_button_font_text_decoration'] : 'none';
            }
            if($post_type == 'product') {
                $bdp_star_rating_bg_color = isset($single_data_setting['bdp_star_rating_bg_color']) ? $single_data_setting['bdp_star_rating_bg_color'] : '';
                $bdp_star_rating_color = isset($single_data_setting['bdp_star_rating_color']) ? $single_data_setting['bdp_star_rating_color'] : '';
                $bdp_star_rating_alignment = isset($single_data_setting['bdp_star_rating_alignment']) ? $single_data_setting['bdp_star_rating_alignment'] : 'left';
                $bdp_star_rating_paddingleft = isset($single_data_setting['bdp_star_rating_paddingleft']) ? $single_data_setting['bdp_star_rating_paddingleft'] : '10';
                $bdp_star_rating_paddingright = isset($single_data_setting['bdp_star_rating_paddingright']) ? $single_data_setting['bdp_star_rating_paddingright'] : '10';
                $bdp_star_rating_paddingtop = isset($single_data_setting['bdp_star_rating_paddingtop']) ? $single_data_setting['bdp_star_rating_paddingtop'] : '10';
                $bdp_star_rating_paddingbottom = isset($single_data_setting['bdp_star_rating_paddingbottom']) ? $single_data_setting['bdp_star_rating_paddingbottom'] : '10';
                $bdp_star_rating_marginleft = isset($single_data_setting['bdp_star_rating_marginleft']) ? $single_data_setting['bdp_star_rating_marginleft'] : '10';
                $bdp_star_rating_marginright = isset($single_data_setting['bdp_star_rating_marginright']) ? $single_data_setting['bdp_star_rating_marginright'] : '10';
                $bdp_star_rating_margintop = isset($single_data_setting['bdp_star_rating_margintop']) ? $single_data_setting['bdp_star_rating_margintop'] : '10';
                $bdp_star_rating_marginbottom = isset($single_data_setting['bdp_star_rating_marginbottom']) ? $single_data_setting['bdp_star_rating_marginbottom'] : '10';

                /**
                 *  Woocommerce sale tag
                 */

                $bdp_sale_tagtextcolor = isset($single_data_setting['bdp_sale_tagtextcolor']) ? $single_data_setting['bdp_sale_tagtextcolor'] : '';
                $bdp_sale_tagbgcolor = isset($single_data_setting['bdp_sale_tagbgcolor']) ? $single_data_setting['bdp_sale_tagbgcolor'] : '';
                $bdp_sale_tag_angle = isset($single_data_setting['bdp_sale_tag_angle']) ? $single_data_setting['bdp_sale_tag_angle'] : '';
                $bdp_sale_tag_border_radius = isset($single_data_setting['bdp_sale_tag_border_radius']) ? $single_data_setting['bdp_sale_tag_border_radius'] : '';
                $bdp_sale_tagtext_marginleft = isset($single_data_setting['bdp_sale_tagtext_marginleft']) ? $single_data_setting['bdp_sale_tagtext_marginleft'] : '5';
                $bdp_sale_tagtext_marginright = isset($single_data_setting['bdp_sale_tagtext_marginright']) ? $single_data_setting['bdp_sale_tagtext_marginright'] : '5';
                $bdp_sale_tagtext_margintop = isset($single_data_setting['bdp_sale_tagtext_margintop']) ? $single_data_setting['bdp_sale_tagtext_margintop'] : '5';
                $bdp_sale_tagtext_marginbottom = isset($single_data_setting['bdp_sale_tagtext_marginbottom']) ? $single_data_setting['bdp_sale_tagtext_marginbottom'] : '5';
                $bdp_sale_tagtext_paddingleft = isset($single_data_setting['bdp_sale_tagtext_paddingleft']) ? $single_data_setting['bdp_sale_tagtext_paddingleft'] : '5';
                $bdp_sale_tagtext_paddingright = isset($single_data_setting['bdp_sale_tagtext_paddingright']) ? $single_data_setting['bdp_sale_tagtext_paddingright'] : '5';
                $bdp_sale_tagtext_paddingtop = isset($single_data_setting['bdp_sale_tagtext_paddingtop']) ? $single_data_setting['bdp_sale_tagtext_paddingtop'] : '5';
                $bdp_sale_tagtext_paddingbottom = isset($single_data_setting['bdp_sale_tagtext_paddingbottom']) ? $single_data_setting['bdp_sale_tagtext_paddingbottom'] : '5';
                $bdp_sale_tagfontface = (isset($single_data_setting['bdp_sale_tagfontface']) && $single_data_setting['bdp_sale_tagfontface'] != '') ? $single_data_setting['bdp_sale_tagfontface'] : '';
                if (isset($single_data_setting['bdp_sale_tagfontface_font_type']) && $single_data_setting['bdp_sale_tagfontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_sale_tagfontface;
                }
                $bdp_sale_tagfontsize = (isset($single_data_setting['bdp_sale_tagfontsize']) && $single_data_setting['bdp_sale_tagfontsize'] != '') ? $single_data_setting['bdp_sale_tagfontsize'] : "inherit";
                $bdp_sale_tag_font_weight = isset($single_data_setting['bdp_sale_tag_font_weight']) ? $single_data_setting['bdp_sale_tag_font_weight'] : '';
                $bdp_sale_tag_font_line_height = isset($single_data_setting['bdp_sale_tag_font_line_height']) ? $single_data_setting['bdp_sale_tag_font_line_height'] : '';
                $bdp_sale_tag_font_italic = isset($single_data_setting['bdp_sale_tag_font_italic']) ? $single_data_setting['bdp_sale_tag_font_italic'] : '';
                $bdp_sale_tag_font_text_transform = isset($single_data_setting['bdp_sale_tag_font_text_transform']) ? $single_data_setting['bdp_sale_tag_font_text_transform'] : 'none';
                $bdp_sale_tag_font_text_decoration = isset($single_data_setting['bdp_sale_tag_font_text_decoration']) ? $single_data_setting['bdp_sale_tag_font_text_decoration'] : 'none';
                $bdp_sale_tag_font_letter_spacing = isset($single_data_setting['bdp_sale_tag_font_letter_spacing']) ? $single_data_setting['bdp_sale_tag_font_letter_spacing'] : '0';

                

                /**
                 *  Woocommerce price text 
                 */
                $bdp_sale_price_bgcolor = isset($single_data_setting['bdp_sale_price_bgcolor']) ? $single_data_setting['bdp_sale_price_bgcolor'] : '';
                $bdp_pricetextcolor = isset($single_data_setting['bdp_pricetextcolor']) ? $single_data_setting['bdp_pricetextcolor'] : '#444444';
                $bdp_pricetext_alignment = isset($single_data_setting['bdp_pricetext_alignment']) ? $single_data_setting['bdp_pricetext_alignment'] : 'left';
                $bdp_pricetext_paddingleft = isset($single_data_setting['bdp_pricetext_paddingleft']) ? $single_data_setting['bdp_pricetext_paddingleft'] : '10';
                $bdp_pricetext_paddingright = isset($single_data_setting['bdp_pricetext_paddingright']) ? $single_data_setting['bdp_pricetext_paddingright'] : '10';
                $bdp_pricetext_paddingtop = isset($single_data_setting['bdp_pricetext_paddingtop']) ? $single_data_setting['bdp_pricetext_paddingtop'] : '10';
                $bdp_pricetext_paddingbottom = isset($single_data_setting['bdp_pricetext_paddingbottom']) ? $single_data_setting['bdp_pricetext_paddingbottom'] : '10';
                $bdp_pricetext_marginleft = isset($single_data_setting['bdp_pricetext_marginleft']) ? $single_data_setting['bdp_pricetext_marginleft'] : '10';
                $bdp_pricetext_marginright = isset($single_data_setting['bdp_pricetext_marginright']) ? $single_data_setting['bdp_pricetext_marginright'] : '10';
                $bdp_pricetext_margintop = isset($single_data_setting['bdp_pricetext_margintop']) ? $single_data_setting['bdp_pricetext_margintop'] : '10';
                $bdp_pricetext_marginbottom = isset($single_data_setting['bdp_pricetext_marginbottom']) ? $single_data_setting['bdp_pricetext_marginbottom'] : '10';
                $bdp_pricefontface = (isset($single_data_setting['bdp_pricefontface']) && $single_data_setting['bdp_pricefontface'] != '') ? $single_data_setting['bdp_pricefontface'] : '';
                if (isset($single_data_setting['bdp_pricefontface_font_type']) && $single_data_setting['bdp_pricefontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_pricefontface;
                }
                $bdp_pricefontsize = (isset($single_data_setting['bdp_pricefontsize']) && $single_data_setting['bdp_pricefontsize'] != '') ? $single_data_setting['bdp_pricefontsize'] : "inherit";
                $bdp_price_font_weight = isset($single_data_setting['bdp_price_font_weight']) ? $single_data_setting['bdp_price_font_weight'] : '';
                $bdp_price_font_line_height = isset($single_data_setting['bdp_price_font_line_height']) ? $single_data_setting['bdp_price_font_line_height'] : '';
                $bdp_price_font_italic = isset($single_data_setting['bdp_price_font_italic']) ? $single_data_setting['bdp_price_font_italic'] : '';
                $bdp_price_font_text_transform = isset($single_data_setting['bdp_price_font_text_transform']) ? $single_data_setting['bdp_price_font_text_transform'] : 'none';
                $bdp_price_font_text_decoration = isset($single_data_setting['bdp_price_font_text_decoration']) ? $single_data_setting['bdp_price_font_text_decoration'] : 'none';
                $bdp_price_font_letter_spacing = isset($single_data_setting['bdp_price_font_letter_spacing']) ? $single_data_setting['bdp_price_font_letter_spacing'] : '0';
                
                /**
                 * Add To Cart Button 
                 */
                $bdp_addtocart_textcolor = isset($single_data_setting['bdp_addtocart_textcolor']) ? $single_data_setting['bdp_addtocart_textcolor'] : '';
                $bdp_addtocart_backgroundcolor = isset($single_data_setting['bdp_addtocart_backgroundcolor']) ? $single_data_setting['bdp_addtocart_backgroundcolor'] : '';
                $bdp_addtocart_text_hover_color = isset($single_data_setting['bdp_addtocart_text_hover_color']) ? $single_data_setting['bdp_addtocart_text_hover_color'] : '';
                $bdp_addtocart_hover_backgroundcolor = isset($single_data_setting['bdp_addtocart_hover_backgroundcolor']) ? $single_data_setting['bdp_addtocart_hover_backgroundcolor'] : '';
                $bdp_addtocartbutton_borderleft = isset($single_data_setting['bdp_addtocartbutton_borderleft']) ? $single_data_setting['bdp_addtocartbutton_borderleft'] : '';
                $bdp_addtocartbutton_borderleftcolor = isset($single_data_setting['bdp_addtocartbutton_borderleftcolor']) ? $single_data_setting['bdp_addtocartbutton_borderleftcolor'] : '';
                $bdp_addtocartbutton_borderright = isset($single_data_setting['bdp_addtocartbutton_borderright']) ? $single_data_setting['bdp_addtocartbutton_borderright'] : '';
                $bdp_addtocartbutton_borderrightcolor = isset($single_data_setting['bdp_addtocartbutton_borderrightcolor']) ? $single_data_setting['bdp_addtocartbutton_borderrightcolor'] : '';
                $bdp_addtocartbutton_bordertop = isset($single_data_setting['bdp_addtocartbutton_bordertop']) ? $single_data_setting['bdp_addtocartbutton_bordertop'] : '';
                $bdp_addtocartbutton_bordertopcolor = isset($single_data_setting['bdp_addtocartbutton_bordertopcolor']) ? $single_data_setting['bdp_addtocartbutton_bordertopcolor'] : '';
                $bdp_addtocartbutton_borderbuttom = isset($single_data_setting['bdp_addtocartbutton_borderbuttom']) ? $single_data_setting['bdp_addtocartbutton_borderbuttom'] : '';
                $bdp_addtocartbutton_borderbottomcolor = isset($single_data_setting['bdp_addtocartbutton_borderbottomcolor']) ? $single_data_setting['bdp_addtocartbutton_borderbottomcolor'] : '';
                $display_addtocart_button_border = isset($single_data_setting['display_addtocart_button_border']) ? $single_data_setting['display_addtocart_button_border'] : '0';
                $display_addtocart_button_border_radius = isset($single_data_setting['display_addtocart_button_border_radius']) ? $single_data_setting['display_addtocart_button_border_radius'] : '';
                $bdp_addtocartbutton_padding_leftright = isset($single_data_setting['bdp_addtocartbutton_padding_leftright']) ? $single_data_setting['bdp_addtocartbutton_padding_leftright'] : '10';
                $bdp_addtocartbutton_padding_topbottom = isset($single_data_setting['bdp_addtocartbutton_padding_topbottom']) ? $single_data_setting['bdp_addtocartbutton_padding_topbottom'] : '10';
                $bdp_addtocartbutton_margin_leftright = isset($single_data_setting['bdp_addtocartbutton_margin_leftright']) ? $single_data_setting['bdp_addtocartbutton_margin_leftright'] : '';
                $bdp_addtocartbutton_margin_topbottom = isset($single_data_setting['bdp_addtocartbutton_margin_topbottom']) ? $single_data_setting['bdp_addtocartbutton_margin_topbottom'] : '';
                $bdp_addtocartbutton_alignment = isset($single_data_setting['bdp_addtocartbutton_alignment']) ? $single_data_setting['bdp_addtocartbutton_alignment'] : 'left';
    
                $bdp_addtocartbutton_hover_borderleft = isset($single_data_setting['bdp_addtocartbutton_hover_borderleft']) ? $single_data_setting['bdp_addtocartbutton_hover_borderleft'] : '';
                $bdp_addtocartbutton_hover_borderleftcolor = isset($single_data_setting['bdp_addtocartbutton_hover_borderleftcolor']) ? $single_data_setting['bdp_addtocartbutton_hover_borderleftcolor'] : '';
                $bdp_addtocartbutton_hover_borderright = isset($single_data_setting['bdp_addtocartbutton_hover_borderright']) ? $single_data_setting['bdp_addtocartbutton_hover_borderright'] : '';
                $bdp_addtocartbutton_hover_borderrightcolor = isset($single_data_setting['bdp_addtocartbutton_hover_borderrightcolor']) ? $single_data_setting['bdp_addtocartbutton_hover_borderrightcolor'] : '';
                $bdp_addtocartbutton_hover_bordertop = isset($single_data_setting['bdp_addtocartbutton_hover_bordertop']) ? $single_data_setting['bdp_addtocartbutton_hover_bordertop'] : '';
                $bdp_addtocartbutton_hover_bordertopcolor = isset($single_data_setting['bdp_addtocartbutton_hover_bordertopcolor']) ? $single_data_setting['bdp_addtocartbutton_hover_bordertopcolor'] : '';
                $bdp_addtocartbutton_hover_borderbuttom = isset($single_data_setting['bdp_addtocartbutton_hover_borderbuttom']) ? $single_data_setting['bdp_addtocartbutton_hover_borderbuttom'] : '';
                $bdp_addtocartbutton_hover_borderbottomcolor = isset($single_data_setting['bdp_addtocartbutton_hover_borderbottomcolor']) ? $single_data_setting['bdp_addtocartbutton_hover_borderbottomcolor'] : '';
                $display_addtocart_button_border_hover_radius = isset($single_data_setting['display_addtocart_button_border_hover_radius']) ? $single_data_setting['display_addtocart_button_border_hover_radius'] : '0';
                $bdp_addtocart_button_top_box_shadow = isset($single_data_setting['bdp_addtocart_button_top_box_shadow']) ? $single_data_setting['bdp_addtocart_button_top_box_shadow'] : '';
                $bdp_addtocart_button_right_box_shadow = isset($single_data_setting['bdp_addtocart_button_right_box_shadow']) ? $single_data_setting['bdp_addtocart_button_right_box_shadow'] : '';
                $bdp_addtocart_button_bottom_box_shadow = isset($single_data_setting['bdp_addtocart_button_bottom_box_shadow']) ? $single_data_setting['bdp_addtocart_button_bottom_box_shadow'] : '';
                $bdp_addtocart_button_left_box_shadow = isset($single_data_setting['bdp_addtocart_button_left_box_shadow']) ? $single_data_setting['bdp_addtocart_button_left_box_shadow'] : '';
                $bdp_addtocart_button_box_shadow_color = isset($single_data_setting['bdp_addtocart_button_box_shadow_color']) ? $single_data_setting['bdp_addtocart_button_box_shadow_color'] : '';
                $bdp_addtocart_button_hover_top_box_shadow = isset($single_data_setting['bdp_addtocart_button_hover_top_box_shadow']) ? $single_data_setting['bdp_addtocart_button_hover_top_box_shadow'] : '';
                $bdp_addtocart_button_hover_right_box_shadow = isset($single_data_setting['bdp_addtocart_button_hover_right_box_shadow']) ? $single_data_setting['bdp_addtocart_button_hover_right_box_shadow'] : '';
                $bdp_addtocart_button_hover_bottom_box_shadow = isset($single_data_setting['bdp_addtocart_button_hover_bottom_box_shadow']) ? $single_data_setting['bdp_addtocart_button_hover_bottom_box_shadow'] : '';
                $bdp_addtocart_button_hover_left_box_shadow = isset($single_data_setting['bdp_addtocart_button_hover_left_box_shadow']) ? $single_data_setting['bdp_addtocart_button_hover_left_box_shadow'] : '';
                $bdp_addtocart_button_hover_box_shadow_color = isset($single_data_setting['bdp_addtocart_button_hover_box_shadow_color']) ? $single_data_setting['bdp_addtocart_button_hover_box_shadow_color'] : '';
                $bdp_addtocart_button_fontface = (isset($single_data_setting['bdp_addtocart_button_fontface']) && $single_data_setting['bdp_addtocart_button_fontface'] != '') ? $single_data_setting['bdp_addtocart_button_fontface'] : '';
                if (isset($single_data_setting['bdp_addtocart_button_fontface_font_type']) && $single_data_setting['bdp_addtocart_button_fontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_addtocart_button_fontface;
                }
                $bdp_addtocart_button_fontsize = (isset($single_data_setting['bdp_addtocart_button_fontsize']) && $single_data_setting['bdp_addtocart_button_fontsize'] != '') ? $single_data_setting['bdp_addtocart_button_fontsize'] : "inherit";
                $bdp_addtocart_button_font_weight = isset($single_data_setting['bdp_addtocart_button_font_weight']) ? $single_data_setting['bdp_addtocart_button_font_weight'] : '';
                $bdp_addtocart_button_font_italic = isset($single_data_setting['bdp_addtocart_button_font_italic']) ? $single_data_setting['bdp_addtocart_button_font_italic'] : '';
                $bdp_addtocart_button_letter_spacing = isset($single_data_setting['bdp_addtocart_button_letter_spacing']) ? $single_data_setting['bdp_addtocart_button_letter_spacing'] : '0';

                $display_addtocart_button_line_height = isset($single_data_setting['display_addtocart_button_line_height']) ? $single_data_setting['display_addtocart_button_line_height'] : '1.5';
                $bdp_addtocart_button_font_text_transform = isset($single_data_setting['bdp_addtocart_button_font_text_transform']) ? $single_data_setting['bdp_addtocart_button_font_text_transform'] : 'none';
                $bdp_addtocart_button_font_text_decoration = isset($single_data_setting['bdp_addtocart_button_font_text_decoration']) ? $single_data_setting['bdp_addtocart_button_font_text_decoration'] : 'none';
                
                    /**
                    * Add To Whishlist Button 
                    */
                $bdp_wishlist_textcolor = isset($single_data_setting['bdp_wishlist_textcolor']) ? $single_data_setting['bdp_wishlist_textcolor'] : '';
                $bdp_wishlist_backgroundcolor = isset($single_data_setting['bdp_wishlist_backgroundcolor']) ? $single_data_setting['bdp_wishlist_backgroundcolor'] : '';
                $bdp_wishlist_text_hover_color = isset($single_data_setting['bdp_wishlist_text_hover_color']) ? $single_data_setting['bdp_wishlist_text_hover_color'] : '';
                $bdp_wishlist_hover_backgroundcolor = isset($single_data_setting['bdp_wishlist_hover_backgroundcolor']) ? $single_data_setting['bdp_wishlist_hover_backgroundcolor'] : '';
                $bdp_wishlistbutton_borderleft = isset($single_data_setting['bdp_wishlistbutton_borderleft']) ? $single_data_setting['bdp_wishlistbutton_borderleft'] : '';
                $bdp_wishlistbutton_borderleftcolor = isset($single_data_setting['bdp_wishlistbutton_borderleftcolor']) ? $single_data_setting['bdp_wishlistbutton_borderleftcolor'] : '';
                $bdp_wishlistbutton_borderright = isset($single_data_setting['bdp_wishlistbutton_borderright']) ? $single_data_setting['bdp_wishlistbutton_borderright'] : '';
                $bdp_wishlistbutton_borderrightcolor = isset($single_data_setting['bdp_wishlistbutton_borderrightcolor']) ? $single_data_setting['bdp_wishlistbutton_borderrightcolor'] : '';
                $bdp_wishlistbutton_bordertop = isset($single_data_setting['bdp_wishlistbutton_bordertop']) ? $single_data_setting['bdp_wishlistbutton_bordertop'] : '';
                $bdp_wishlistbutton_bordertopcolor = isset($single_data_setting['bdp_wishlistbutton_bordertopcolor']) ? $single_data_setting['bdp_wishlistbutton_bordertopcolor'] : '';
                $bdp_wishlistbutton_borderbuttom = isset($single_data_setting['bdp_wishlistbutton_borderbuttom']) ? $single_data_setting['bdp_wishlistbutton_borderbuttom'] : '';
                $bdp_wishlistbutton_borderbottomcolor = isset($single_data_setting['bdp_wishlistbutton_borderbottomcolor']) ? $single_data_setting['bdp_wishlistbutton_borderbottomcolor'] : '';
                $display_wishlist_button_border_radius = isset($single_data_setting['display_wishlist_button_border_radius']) ? $single_data_setting['display_wishlist_button_border_radius'] : '';
                $bdp_wishlistbutton_padding_leftright = isset($single_data_setting['bdp_wishlistbutton_padding_leftright']) ? $single_data_setting['bdp_wishlistbutton_padding_leftright'] : '';
                $bdp_wishlistbutton_padding_topbottom = isset($single_data_setting['bdp_wishlistbutton_padding_topbottom']) ? $single_data_setting['bdp_wishlistbutton_padding_topbottom'] : '';
                $bdp_wishlistbutton_margin_leftright = isset($single_data_setting['bdp_wishlistbutton_margin_leftright']) ? $single_data_setting['bdp_wishlistbutton_margin_leftright'] : '';
                $bdp_wishlistbutton_margin_topbottom = isset($single_data_setting['bdp_wishlistbutton_margin_topbottom']) ? $single_data_setting['bdp_wishlistbutton_margin_topbottom'] : '';
                $bdp_wishlistbutton_alignment = isset($single_data_setting['bdp_wishlistbutton_alignment']) ? $single_data_setting['bdp_wishlistbutton_alignment'] : 'left';
                $bdp_cart_wishlistbutton_alignment = isset($single_data_setting['bdp_cart_wishlistbutton_alignment']) ? $single_data_setting['bdp_cart_wishlistbutton_alignment'] : 'left';
                $bdp_wishlistbutton_hover_borderleft = isset($single_data_setting['bdp_wishlistbutton_hover_borderleft']) ? $single_data_setting['bdp_wishlistbutton_hover_borderleft'] : '';
                $bdp_wishlistbutton_hover_borderleftcolor = isset($single_data_setting['bdp_wishlistbutton_hover_borderleftcolor']) ? $single_data_setting['bdp_wishlistbutton_hover_borderleftcolor'] : '';
                $bdp_wishlistbutton_hover_borderright = isset($single_data_setting['bdp_wishlistbutton_hover_borderright']) ? $single_data_setting['bdp_wishlistbutton_hover_borderright'] : '';
                $bdp_wishlistbutton_hover_borderrightcolor = isset($single_data_setting['bdp_wishlistbutton_hover_borderrightcolor']) ? $single_data_setting['bdp_wishlistbutton_hover_borderrightcolor'] : '';
                $bdp_wishlistbutton_hover_bordertop = isset($single_data_setting['bdp_wishlistbutton_hover_bordertop']) ? $single_data_setting['bdp_wishlistbutton_hover_bordertop'] : '';
                $bdp_wishlistbutton_hover_bordertopcolor = isset($single_data_setting['bdp_wishlistbutton_hover_bordertopcolor']) ? $single_data_setting['bdp_wishlistbutton_hover_bordertopcolor'] : '';
                $bdp_wishlistbutton_hover_borderbuttom = isset($single_data_setting['bdp_wishlistbutton_hover_borderbuttom']) ? $single_data_setting['bdp_wishlistbutton_hover_borderbuttom'] : '';
                $bdp_wishlistbutton_hover_borderbottomcolor = isset($single_data_setting['bdp_wishlistbutton_hover_borderbottomcolor']) ? $single_data_setting['bdp_wishlistbutton_hover_borderbottomcolor'] : '';
                $display_wishlist_button_border_hover_radius = isset($single_data_setting['display_wishlist_button_border_hover_radius']) ? $single_data_setting['display_wishlist_button_border_hover_radius'] : '0';
                $bdp_wishlist_button_top_box_shadow = isset($single_data_setting['bdp_wishlist_button_top_box_shadow']) ? $single_data_setting['bdp_wishlist_button_top_box_shadow'] : '';
                $bdp_wishlist_button_right_box_shadow = isset($single_data_setting['bdp_wishlist_button_right_box_shadow']) ? $single_data_setting['bdp_wishlist_button_right_box_shadow'] : '';
                $bdp_wishlist_button_bottom_box_shadow = isset($single_data_setting['bdp_wishlist_button_bottom_box_shadow']) ? $single_data_setting['bdp_wishlist_button_bottom_box_shadow'] : '';
                $bdp_wishlist_button_left_box_shadow = isset($single_data_setting['bdp_wishlist_button_left_box_shadow']) ? $single_data_setting['bdp_wishlist_button_left_box_shadow'] : '';
                $bdp_wishlist_button_box_shadow_color = isset($single_data_setting['bdp_wishlist_button_box_shadow_color']) ? $single_data_setting['bdp_wishlist_button_box_shadow_color'] : '';
                $bdp_wishlist_button_hover_top_box_shadow = isset($single_data_setting['bdp_wishlist_button_hover_top_box_shadow']) ? $single_data_setting['bdp_wishlist_button_hover_top_box_shadow'] : '';
                $bdp_wishlist_button_hover_right_box_shadow = isset($single_data_setting['bdp_wishlist_button_hover_right_box_shadow']) ? $single_data_setting['bdp_wishlist_button_hover_right_box_shadow'] : '';
                $bdp_wishlist_button_hover_bottom_box_shadow = isset($single_data_setting['bdp_wishlist_button_hover_bottom_box_shadow']) ? $single_data_setting['bdp_wishlist_button_hover_bottom_box_shadow'] : '';
                $bdp_wishlist_button_hover_left_box_shadow = isset($single_data_setting['bdp_wishlist_button_hover_left_box_shadow']) ? $single_data_setting['bdp_wishlist_button_hover_left_box_shadow'] : '';
                $bdp_wishlist_button_hover_box_shadow_color = isset($single_data_setting['bdp_wishlist_button_hover_box_shadow_color']) ? $single_data_setting['bdp_wishlist_button_hover_box_shadow_color'] : '';
                $bdp_wishlistbutton_on = isset($single_data_setting['bdp_wishlistbutton_on']) ? $single_data_setting['bdp_wishlistbutton_on'] : '1';
                $display_addtowishlist_button = isset($single_data_setting['display_addtowishlist_button']) ? $single_data_setting['display_addtowishlist_button'] : '0';
                $bdp_addtowishlist_button_fontface = (isset($single_data_setting['bdp_addtowishlist_button_fontface']) && $single_data_setting['bdp_addtowishlist_button_fontface'] != '') ? $single_data_setting['bdp_addtowishlist_button_fontface'] : '';
                if (isset($single_data_setting['bdp_addtowishlist_button_fontface_font_type']) && $single_data_setting['bdp_addtowishlist_button_fontface_font_type'] == 'Google Fonts') {
                    $load_single_font[] = $bdp_addtowishlist_button_fontface;
                }
                $bdp_addtowishlist_button_fontsize = (isset($single_data_setting['bdp_addtowishlist_button_fontsize']) && $single_data_setting['bdp_addtowishlist_button_fontsize'] != '') ? $single_data_setting['bdp_addtowishlist_button_fontsize'] : "inherit";
                $bdp_addtowishlist_button_font_weight = isset($single_data_setting['bdp_addtowishlist_button_font_weight']) ? $single_data_setting['bdp_addtowishlist_button_font_weight'] : '';
                $bdp_addtowishlist_button_font_italic = isset($single_data_setting['bdp_addtowishlist_button_font_italic']) ? $single_data_setting['bdp_addtowishlist_button_font_italic'] : '';
                $bdp_addtowishlist_button_letter_spacing = isset($single_data_setting['bdp_addtowishlist_button_letter_spacing']) ? $single_data_setting['bdp_addtowishlist_button_letter_spacing'] : '0';

                $display_wishlist_button_line_height = isset($single_data_setting['display_wishlist_button_line_height']) ? $single_data_setting['display_wishlist_button_line_height'] : '1.5';
                $bdp_addtowishlist_button_font_text_transform = isset($single_data_setting['bdp_addtowishlist_button_font_text_transform']) ? $single_data_setting['bdp_addtowishlist_button_font_text_transform'] : 'none';
                $bdp_addtowishlist_button_font_text_decoration = isset($single_data_setting['bdp_addtowishlist_button_font_text_decoration']) ? $single_data_setting['bdp_addtowishlist_button_font_text_decoration'] : 'none';
            }
            // for author title
            $authorTitleSize = (isset($single_data_setting['author_title_fontsize']) && $single_data_setting['author_title_fontsize'] != '') ? $single_data_setting['author_title_fontsize'] : 16;
            $authorTitleFace = (isset($single_data_setting['author_title_fontface']) && $single_data_setting['author_title_fontface'] != '') ? $single_data_setting['author_title_fontface'] : "";

            if (isset($single_data_setting['author_title_fontface_font_type']) && $single_data_setting['author_title_fontface_font_type'] == "Google Fonts") {
                $load_single_font[] = $authorTitleFace;
            }
            // for author title
            $txtSocialTextSize = (isset($single_data_setting['txtSocialTextSize']) && $single_data_setting['txtSocialTextSize'] != '') ? $single_data_setting['txtSocialTextSize'] : 18;
            $txtSocialTextFont = (isset($single_data_setting['txtSocialTextFont']) && $single_data_setting['txtSocialTextFont'] != '') ? $single_data_setting['txtSocialTextFont'] : "";

            if (isset($single_data_setting['txtSocialTextFont_font_type']) && $single_data_setting['txtSocialTextFont_font_type'] == "Google Fonts") {
                $load_single_font[] = $txtSocialTextFont;
            }

            $social_icon_style = (isset($single_data_setting['social_icon_style']) && $single_data_setting['social_icon_style'] != '') ? $single_data_setting['social_icon_style'] : 0;
            $social_style = (isset($single_data_setting['social_style']) && $single_data_setting['social_style'] != '') ? $single_data_setting['social_style'] : '';

            $story_startup_background = (isset($single_data_setting['story_startup_background']) && $single_data_setting['story_startup_background'] != '') ? $single_data_setting['story_startup_background'] : "";
            $story_startup_text_color = (isset($single_data_setting['story_startup_text_color']) && $single_data_setting['story_startup_text_color'] != '') ? $single_data_setting['story_startup_text_color'] : "";

            /**
             * Post title font options
             */
            $template_title_font_weight = isset($single_data_setting['template_title_font_weight']) ? $single_data_setting['template_title_font_weight'] : '';
            $template_title_font_line_height = isset($single_data_setting['template_title_font_line_height']) ? $single_data_setting['template_title_font_line_height'] : '';
            $template_title_font_italic = isset($single_data_setting['template_title_font_italic']) ? $single_data_setting['template_title_font_italic'] : '';
            $template_title_font_text_transform = isset($single_data_setting['template_title_font_text_transform']) ? $single_data_setting['template_title_font_text_transform'] : 'none';
            $template_title_font_text_decoration = isset($single_data_setting['template_title_font_text_decoration']) ? $single_data_setting['template_title_font_text_decoration'] : 'none';
            $template_title_font_letter_spacing = isset($single_data_setting['template_title_font_letter_spacing']) ? $single_data_setting['template_title_font_letter_spacing'] : '0';

            /**
             * Post Content font options
             */
            $template_content_font_weight = isset($single_data_setting['template_content_font_weight']) ? $single_data_setting['template_content_font_weight'] : '';
            $template_content_font_line_height = isset($single_data_setting['template_content_font_line_height']) ? $single_data_setting['template_content_font_line_height'] : '';
            $template_content_font_italic = isset($single_data_setting['template_content_font_italic']) ? $single_data_setting['template_content_font_italic'] : '';
            $template_content_font_text_transform = isset($single_data_setting['template_content_font_text_transform']) ? $single_data_setting['template_content_font_text_transform'] : 'none';
            $template_content_font_text_decoration = isset($single_data_setting['template_content_font_text_decoration']) ? $single_data_setting['template_content_font_text_decoration'] : 'none';
            $template_content_font_letter_spacing = isset($single_data_setting['template_content_font_letter_spacing']) ? $single_data_setting['template_content_font_letter_spacing'] : '0';
            $backgroundbg_image_src = (isset($single_data_setting['bdp_bg_image_src']) && $single_data_setting['bdp_bg_image_src'] != '') ? $single_data_setting['bdp_bg_image_src'] : "";

            $bdp_title_top_box_shadow = isset( $single_data_setting['bdp_title_top_box_shadow'] ) ? $single_data_setting['bdp_title_top_box_shadow'].'px' : '0';
            $bdp_title_right_box_shadow = isset( $single_data_setting['bdp_title_right_box_shadow'] ) ? $single_data_setting['bdp_title_right_box_shadow'].'px' : '0';
            $bdp_title_bottom_box_shadow = isset( $single_data_setting['bdp_title_bottom_box_shadow'] ) ? $single_data_setting['bdp_title_bottom_box_shadow'].'px' : '0';
            $bdp_title_box_shadow_color = isset( $single_data_setting['bdp_title_box_shadow_color'] ) ? $single_data_setting['bdp_title_box_shadow_color'] : '';

            $bdp_content_top_box_shadow = isset( $single_data_setting['bdp_content_top_box_shadow'] ) ? $single_data_setting['bdp_content_top_box_shadow'].'px' : '0';
            $bdp_content_right_box_shadow = isset( $single_data_setting['bdp_content_right_box_shadow'] ) ? $single_data_setting['bdp_content_right_box_shadow'].'px' : '0';
            $bdp_content_bottom_box_shadow = isset( $single_data_setting['bdp_content_bottom_box_shadow'] ) ? $single_data_setting['bdp_content_bottom_box_shadow'].'px' : '0';
            $bdp_content_box_shadow_color = isset( $single_data_setting['bdp_content_box_shadow_color'] ) ? $single_data_setting['bdp_content_box_shadow_color'] : '';

            //Single Post title Margin-Padding
            $single_post_title_marginleft = isset( $single_data_setting['single_post_title_marginleft']) ? $single_data_setting['single_post_title_marginleft'] : '';
            $single_post_title_marginright	= isset( $single_data_setting['single_post_title_marginright']) ? $single_data_setting['single_post_title_marginright'] : '';
            $single_post_title_margintop	= isset( $single_data_setting['single_post_title_margintop']) ? $single_data_setting['single_post_title_margintop'] : '';
            $single_post_title_marginbottom	= isset( $single_data_setting['single_post_title_marginbottom']) ? $single_data_setting['single_post_title_marginbottom'] : '';
            $single_post_title_paddingleft	= isset( $single_data_setting['single_post_title_paddingleft']) ? $single_data_setting['single_post_title_paddingleft'] : '';
            $single_post_title_paddingright	= isset( $single_data_setting['single_post_title_paddingright']) ? $single_data_setting['single_post_title_paddingright'] : '';
            $single_post_title_paddingtop	= isset( $single_data_setting['single_post_title_paddingtop']) ? $single_data_setting['single_post_title_paddingtop'] : '';
            $single_post_title_paddingbottom	= isset( $single_data_setting['single_post_title_paddingbottom']) ? $single_data_setting['single_post_title_paddingbottom'] : '';

            //Single Post content Margin-Padding
            $single_post_content_marginleft	= isset( $single_data_setting['single_post_content_marginleft']) ? $single_data_setting['single_post_content_marginleft'] : '';
            $single_post_content_marginright	= isset( $single_data_setting['single_post_content_marginright']) ? $single_data_setting['single_post_content_marginright'] : '';
            $single_post_content_margintop		= isset( $single_data_setting['single_post_content_margintop']) ? $single_data_setting['single_post_content_margintop'] : '';
            $single_post_content_marginbottom	= isset( $single_data_setting['single_post_content_marginbottom']) ? $single_data_setting['single_post_content_marginbottom'] : '';
            $single_post_content_paddingleft	= isset( $single_data_setting['single_post_content_paddingleft']) ? $single_data_setting['single_post_content_paddingleft'] : '';
            $single_post_content_paddingright	= isset( $single_data_setting['single_post_content_paddingright']) ? $single_data_setting['single_post_content_paddingright'] : '';
            $single_post_content_paddingtop	= isset( $single_data_setting['single_post_content_paddingtop']) ? $single_data_setting['single_post_content_paddingtop'] : '';
            $single_post_content_paddingbottom	= isset( $single_data_setting['single_post_content_paddingbottom']) ? $single_data_setting['single_post_content_paddingbottom'] : '';
            
            //Single Product title Margin-Padding
            $product_single_post_title_marginleft = isset( $single_data_setting['product_single_post_title_marginleft']) ? $single_data_setting['product_single_post_title_marginleft'] : '';
            $product_single_post_title_marginright	= isset( $single_data_setting['product_single_post_title_marginright']) ? $single_data_setting['product_single_post_title_marginright'] : '';
            $product_single_post_title_margintop	= isset( $single_data_setting['product_single_post_title_margintop']) ? $single_data_setting['product_single_post_title_margintop'] : '';
            $product_single_post_title_marginbottom	= isset( $single_data_setting['product_single_post_title_marginbottom']) ? $single_data_setting['product_single_post_title_marginbottom'] : '';
            $product_single_post_title_paddingleft	= isset( $single_data_setting['product_single_post_title_paddingleft']) ? $single_data_setting['product_single_post_title_paddingleft'] : '';
            $product_single_post_title_paddingright	= isset( $single_data_setting['product_single_post_title_paddingright']) ? $single_data_setting['product_single_post_title_paddingright'] : '';
            $product_single_post_title_paddingtop	= isset( $single_data_setting['product_single_post_title_paddingtop']) ? $single_data_setting['product_single_post_title_paddingtop'] : '';
            $product_single_post_title_paddingbottom	= isset( $single_data_setting['product_single_post_title_paddingbottom']) ? $single_data_setting['product_single_post_title_paddingbottom'] : '';
            //Single Product Post content Margin-Padding
            $product_single_post_content_marginleft	= isset( $single_data_setting['product_single_post_content_marginleft']) ? $single_data_setting['product_single_post_content_marginleft'] : '';
            $product_single_post_content_marginright	= isset( $single_data_setting['product_single_post_content_marginright']) ? $single_data_setting['product_single_post_content_marginright'] : '';
            $product_single_post_content_margintop		= isset( $single_data_setting['product_single_post_content_margintop']) ? $single_data_setting['product_single_post_content_margintop'] : '';
            $product_single_post_content_marginbottom	= isset( $single_data_setting['product_single_post_content_marginbottom']) ? $single_data_setting['product_single_post_content_marginbottom'] : '';
            $product_single_post_content_paddingleft	= isset( $single_data_setting['product_single_post_content_paddingleft']) ? $single_data_setting['product_single_post_content_paddingleft'] : '';
            $product_single_post_content_paddingright	= isset( $single_data_setting['product_single_post_content_paddingright']) ? $single_data_setting['product_single_post_content_paddingright'] : '';
            $product_single_post_content_paddingtop	= isset( $single_data_setting['product_single_post_content_paddingtop']) ? $single_data_setting['product_single_post_content_paddingtop'] : '';
            $product_single_post_content_paddingbottom	= isset( $single_data_setting['product_single_post_content_paddingbottom']) ? $single_data_setting['product_single_post_content_paddingbottom'] : '';

            //Download Single Post title Margin-Padding
            $download_single_post_title_marginleft = isset( $single_data_setting['download_single_post_title_marginleft']) ? $single_data_setting['download_single_post_title_marginleft'] : '';
            $download_single_post_title_marginright	= isset( $single_data_setting['download_single_post_title_marginright']) ? $single_data_setting['download_single_post_title_marginright'] : '';
            $download_single_post_title_margintop	= isset( $single_data_setting['download_single_post_title_margintop']) ? $single_data_setting['download_single_post_title_margintop'] : '';
            $download_single_post_title_marginbottom	= isset( $single_data_setting['download_single_post_title_marginbottom']) ? $single_data_setting['download_single_post_title_marginbottom'] : '';
            $download_single_post_title_paddingleft	= isset( $single_data_setting['download_single_post_title_paddingleft']) ? $single_data_setting['download_single_post_title_paddingleft'] : '';
            $download_single_post_title_paddingright	= isset( $single_data_setting['download_single_post_title_paddingright']) ? $single_data_setting['download_single_post_title_paddingright'] : '';
            $download_single_post_title_paddingtop	= isset( $single_data_setting['download_single_post_title_paddingtop']) ? $single_data_setting['download_single_post_title_paddingtop'] : '';
            $download_single_post_title_paddingbottom	= isset( $single_data_setting['download_single_post_title_paddingbottom']) ? $single_data_setting['download_single_post_title_paddingbottom'] : '';
            //Download Single Post content Margin-Padding
            $download_single_post_content_marginleft	= isset( $single_data_setting['download_single_post_content_marginleft']) ? $single_data_setting['download_single_post_content_marginleft'] : '';
            $download_single_post_content_marginright	= isset( $single_data_setting['download_single_post_content_marginright']) ? $single_data_setting['download_single_post_content_marginright'] : '';
            $download_single_post_content_margintop		= isset( $single_data_setting['download_single_post_content_margintop']) ? $single_data_setting['download_single_post_content_margintop'] : '';
            $download_single_post_content_marginbottom	= isset( $single_data_setting['download_single_post_content_marginbottom']) ? $single_data_setting['download_single_post_content_marginbottom'] : '';
            $download_single_post_content_paddingleft	= isset( $single_data_setting['download_single_post_content_paddingleft']) ? $single_data_setting['download_single_post_content_paddingleft'] : '';
            $download_single_post_content_paddingright	= isset( $single_data_setting['download_single_post_content_paddingright']) ? $single_data_setting['download_single_post_content_paddingright'] : '';
            $download_single_post_content_paddingtop	= isset( $single_data_setting['download_single_post_content_paddingtop']) ? $single_data_setting['download_single_post_content_paddingtop'] : '';
            $download_single_post_content_paddingbottom	= isset( $single_data_setting['download_single_post_content_paddingbottom']) ? $single_data_setting['download_single_post_content_paddingbottom'] : '';

            if (get_option('bdp_custom_google_fonts') != '') {
                $sidebar = explode(',', get_option('bdp_custom_google_fonts'));
                foreach ($sidebar as $key => $value) {
                    $whatIWant = substr($value, strpos($value, "=") + 1);
                    $load_single_font[] = $whatIWant;
                }
            }
            if (!empty($load_single_font)) {
                $loadFontArr = array_values(array_unique($load_single_font));
                foreach ($loadFontArr as $font_family) {
                    if ($font_family != '') {
                        $setBase = (is_ssl()) ? "https://" : "http://";
                        $font_href = $setBase . 'fonts.googleapis.com/css?family=' . $font_family;
                        ?>
                        <script type="text/javascript">

                            var gfont = document.createElement("link"),
                                    before = document.getElementsByTagName("link")[0],
                                    loadHref = true;

                            jQuery('head').find('*').each(function () {
                                if (jQuery(this).attr('href') == '<?php echo $font_href; ?>')
                                {
                                    loadHref = false;
                                }
                            });
                            if (loadHref)
                            {
                                gfont.href = '<?php echo $font_href; ?>';
                                gfont.rel = 'stylesheet';
                                gfont.type = 'text/css';
                                gfont.media = 'all';
                                before.parentNode.insertBefore(gfont, before);
                            }
                        </script>
                        <?php
                    }
                }
            }
            ?>

            <style type="text/css" id="bdp_single_page_style">
            /* Single layout */
            .bdp_single .bdp_blog_template h1.post-title span,
            .bdp_single .post-title span,
            .bdp_single .bdp_blog_template .blog_header h1 {
                <?php if(isset($post_length_setting) && $post_length_setting) {?>
                display: -webkit-box;
                -webkit-line-clamp: <?php echo $total_noofline?>;
                -webkit-box-orient: vertical;
                overflow: hidden;
                line-height: 1.5;
                    <?php }	?>
                <?php
                        if(isset($template_post_content_wrap_from) && '' !== $template_post_content_wrap_from){
                    ?>
                    word-break : <?php echo $template_post_content_wrap_from; ?>;
                    <?php	
                        } 
                    ?>
                }


           <?php if($enable_lazy_load_blur_image == 1) { ?>
                .bdp_single .lazyload,
                .bdp_single .lazyloading {
                     -webkit-filter: blur(10px);
                    filter: blur(10px);
                    transition: filter 6000ms, -webkit-filter 6000ms;
                } 
                 <?php }
                 if(!empty($template_lazy_load_color) ){?>
                .bdp_single .lazyload,
                .bdp_single .lazyloading{
                     background-color: <?php echo esc_attr( $template_lazy_load_color); ?> !important ;	 
                     transition: filter 15000ms, -webkit-filter 15000ms;
                 }
            
                <?php }


             if ($social_icon_style == 0 && $social_style == 0) { ?>
                    .bdp_blog_template .social-component a {
                        border-radius: 100%;
                        -webkit-border-radius: 100%;
                        -moz-border-radius: 100%;
                        -khtml-border-radius: 100%;
                    }
            <?php } ?>
            <?php if($post_type == 'download') { ?>
                /*Download Single Post Title CSS*/
                .bdp_single_download .bdp_blog_template .post-title,
                .bdp_single_download .bdp_blog_template h1.post-title,
                .bdp_single_download .bdp_blog_template .blog_header h1,
                .bdp_single_download .bdp_blog_template h1.blog_header,
                .bdp_post_title,
                .bdp_single_download .bdp_blog_template .post-title h1,
                .bdp_single_download .bdp_blog_template .post_title {
                    margin-left: <?php echo esc_attr($download_single_post_title_marginleft).'px'; ?>;
                    margin-right: <?php echo esc_attr($download_single_post_title_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($download_single_post_title_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($download_single_post_title_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($download_single_post_title_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($download_single_post_title_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($download_single_post_title_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($download_single_post_title_paddingbottom).'px'; ?>;
                }
                .bdp_single_download .bdp_blog_template .post-title a,
                .bdp_single_download .bdp_blog_template h1 a,
                .bdp_single_download .bdp_blog_template .blog_header h1 a,
                .bdp_single_download .bdp_blog_template h1.blog_header a, 
                .bdp_post_title a,
                .bdp_single_download .bdp_blog_template .post-title h1 a,
                .bdp_single_download .bdp_blog_template .post_title a,
                .bdp_single_download .post_content_wrap .post-title > a {
                    margin-left: <?php echo esc_attr($download_single_post_title_marginleft).'px'; ?>;
                    margin-right: <?php echo esc_attr($download_single_post_title_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($download_single_post_title_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($download_single_post_title_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($download_single_post_title_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($download_single_post_title_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($download_single_post_title_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($download_single_post_title_paddingbottom).'px'; ?>;
                }

                /*Download Single Post Content CSS*/
                .bdp_single_download .bdp_blog_template .post-content,
                .bdp_single_download .bdp_blog_template .post-content p,
                .bdp_single_download .bdp_blog_template .post_content,
                .bdp_single_download .bdp_blog_template .post_content p,
                .bdp_single_download .bdp_blog_template .post_content-inner,
                .bdp_single_download .bdp_blog_template .post_content-inner p,
                .bdp_single_download .bdp_blog_template .postcontent {
                    margin-left: <?php echo esc_attr($download_single_post_content_marginleft) .'px'; ?>;
                    margin-right: <?php echo esc_attr($download_single_post_content_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($download_single_post_content_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($download_single_post_content_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($download_single_post_content_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($download_single_post_content_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($download_single_post_content_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($download_single_post_content_paddingbottom).'px'; ?>;
                }

                .bdp_single.bdp_single_download .bdp_edd_price_wrapper {
                    <?php if (isset($bdp_edd_price_alignment)) {  ?> text-align: <?php echo $bdp_edd_price_alignment; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_paddingleft)) { ?> padding-left: <?php echo $bdp_edd_price_paddingleft. 'px'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_paddingright)) { ?> padding-right: <?php echo $bdp_edd_price_paddingright. 'px'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_paddingtop)) { ?> padding-top: <?php echo $bdp_edd_price_paddingtop. 'px'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_paddingbottom)) { ?> padding-bottom: <?php echo $bdp_edd_price_paddingbottom. 'px'; ?>;<?php } ?>
                }

                .bdp_single.bdp_single_download .bdp_edd_price_wrapper .edd_price,
                .bdp_single.bdp_single_download .bdp_edd_price_wrapper .edd_price .span {
                    color: <?php echo $bdp_edd_price_color; ?> !important;
                    font-size: <?php echo $bdp_edd_pricefontsize . 'px'; ?>;
                    <?php if (isset($bdp_edd_pricefontface) && $bdp_edd_pricefontface != '') { ?> font-family: <?php echo $bdp_edd_pricefontface; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_font_weight)) { ?> font-weight: <?php echo $bdp_edd_price_font_weight; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_font_line_height)) { ?> line-height: <?php echo $bdp_edd_price_font_line_height; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_font_italic) && $bdp_edd_price_font_italic == '1') { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_font_letter_spacing)) { ?> letter-spacing: <?php echo $bdp_edd_price_font_letter_spacing. 'px'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_price_font_text_decoration)) { ?> text-decoration: <?php echo $bdp_edd_price_font_text_decoration; ?>;<?php } ?>
                    width: auto;
                    word-break: break-all;
                }
                .bdp_single.bdp_single_download a.bdp_edd_view_button,
                .bdp_single.bdp_single_download .edd_go_to_checkout,
                .bdp_single.bdp_single_download .edd-add-to-cart-label {
                    <?php if (isset($bdp_edd_addtocart_button_fontface)) { ?> font-family: <?php echo $bdp_edd_addtocart_button_fontface; ?>;<?php } ?>
                    font-size: <?php echo $bdp_edd_addtocart_button_fontsize . 'px'; ?>;
                    <?php if (isset($bdp_edd_addtocart_button_font_weight)) { ?> font-weight: <?php echo $bdp_edd_addtocart_button_font_weight; ?>;<?php } ?>
                    <?php if (isset($display_addtocart_button_line_height)) { ?> line-height: <?php echo $display_addtocart_button_line_height; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_addtocart_button_font_italic) && $bdp_edd_addtocart_button_font_italic == 1) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_addtocart_button_letter_spacing)) { ?>letter-spacing: <?php echo $bdp_edd_addtocart_button_letter_spacing. 'px'; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_addtocart_button_font_text_transform)) { ?> text-transform: <?php echo $bdp_edd_addtocart_button_font_text_transform; ?>;<?php } ?>
                    <?php if (isset($bdp_edd_addtocart_button_font_text_decoration)) { ?> text-decoration: <?php echo $bdp_edd_addtocart_button_font_text_decoration; ?> !important;<?php } ?>
                    <?php if (isset($bdp_edd_addtocart_textcolor)) { ?> color: <?php echo $bdp_edd_addtocart_textcolor; ?>;<?php } ?>
                    width: auto;
                }
                .bdp_single.bdp_single_download a.bdp_edd_view_button,
                .bdp_single.bdp_single_download .edd_go_to_checkout,
                .bdp_single.bdp_single_download .edd_purchase_submit_wrapper .edd-add-to-cart { 
                    <?php if (isset($bdp_edd_addtocart_backgroundcolor)) { ?> background-color: <?php echo $bdp_edd_addtocart_backgroundcolor; ?> !important ;<?php } ?>
                    <?php if (isset($bdp_edd_addtocartbutton_borderleft)) { ?>border-left:<?php echo $bdp_edd_addtocartbutton_borderleft.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_borderleftcolor; ?> !important;<?php } ?>
                    <?php if (isset($bdp_edd_addtocartbutton_borderright)) { ?>border-right:<?php echo $bdp_edd_addtocartbutton_borderright.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_borderrightcolor; ?> !important;<?php } ?>
                    <?php if (isset($bdp_edd_addtocartbutton_bordertop)) { ?>border-top:<?php echo $bdp_edd_addtocartbutton_bordertop.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_bordertopcolor; ?> !important;<?php } ?>
                    <?php if (isset($bdp_edd_addtocartbutton_borderbuttom)) { ?>border-bottom:<?php echo $bdp_edd_addtocartbutton_borderbuttom.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_borderbottomcolor; ?> !important;<?php } ?>
                    <?php if (isset($display_edd_addtocart_button_border_radius)) { ?>border-radius:<?php echo $display_edd_addtocart_button_border_radius.'px';?> !important;<?php } ?>
                    padding : <?php echo $bdp_edd_addtocartbutton_padding_topbottom.'px'; ?> <?php echo $bdp_edd_addtocartbutton_padding_leftright.'px'; ?>;
                    <?php if(isset($bdp_edd_addtocart_button_box_shadow_color)) { ?>box-shadow: <?php echo $bdp_edd_addtocart_button_top_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_right_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_bottom_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_left_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_box_shadow_color; ?> !important; <?php } ?>
                    width: auto;
                }
                .bdp_single.bdp_single_download .edd_purchase_submit_wrapper {
                    <?php if(isset($bdp_edd_addtocartbutton_alignment)) { ?> text-align: <?php echo $bdp_edd_addtocartbutton_alignment; ?>;<?php } ?>
                    margin: <?php echo $bdp_edd_addtocartbutton_margin_topbottom.'px'; ?> <?php echo $bdp_edd_addtocartbutton_margin_leftright.'px'; ?>;
                }
                .bdp_single.bdp_single_download .bdp_edd_download_buy_button {
                    <?php if(isset($bdp_edd_addtocartbutton_alignment)) { ?> text-align: <?php echo $bdp_edd_addtocartbutton_alignment; ?>;<?php } ?>
                }
                .bdp_single .related_post_wrap .edd_purchase_submit_wrapper .edd-add-to-car:hover .edd-add-to-cart-label,
                .bdp_single.bdp_single_download .edd_purchase_submit_wrapper .edd-add-to-cart:hover,
                .bdp_single.bdp_single_download a.bdp_edd_view_button:hover,
                .bdp_single.bdp_single_download .edd_go_to_checkout:hover,
                .bdp_single.bdp_single_download .edd-add-to-cart:hover {
                    <?php if(isset($bdp_edd_addtocart_hover_backgroundcolor)) { ?> background-color: <?php echo $bdp_edd_addtocart_hover_backgroundcolor; ?>;<?php } ?>
                    <?php if(isset($bdp_edd_addtocartbutton_hover_borderleft)) { ?>border-left:<?php echo $bdp_edd_addtocartbutton_hover_borderleft.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_hover_borderleftcolor; ?> !important;<?php } ?>
                    <?php if(isset($bdp_edd_addtocartbutton_hover_borderright)) { ?>border-right:<?php echo $bdp_edd_addtocartbutton_hover_borderright.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_hover_borderrightcolor; ?> !important;<?php } ?>
                    <?php if(isset($bdp_edd_addtocartbutton_hover_bordertop)) { ?>border-top:<?php echo $bdp_edd_addtocartbutton_hover_bordertop.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_hover_bordertopcolor; ?> !important;<?php } ?>
                    <?php if(isset($bdp_edd_addtocartbutton_hover_borderbuttom)) { ?>border-bottom:<?php echo $bdp_edd_addtocartbutton_hover_borderbuttom.'px'; ?> solid <?php echo $bdp_edd_addtocartbutton_hover_borderbottomcolor; ?> !important;<?php } ?>
                    <?php if (isset($display_edd_addtocart_button_border_hover_radius)) { ?>border-radius:<?php echo $display_edd_addtocart_button_border_hover_radius.'px';?> !important;<?php } ?>
                    <?php if(isset($bdp_edd_addtocart_button_hover_box_shadow_color) && $bdp_edd_addtocart_button_hover_box_shadow_color) { ?>box-shadow: <?php echo $bdp_edd_addtocart_button_hover_top_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_hover_right_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_hover_bottom_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_hover_left_box_shadow.'px'; ?> <?php echo $bdp_edd_addtocart_button_hover_box_shadow_color; ?> !important; <?php } ?>
                }
                .bdp_single.bdp_single_download a.bdp_edd_view_button:hover,
                .bdp_single.bdp_single_download .edd_go_to_checkout:hover,
                .bdp_single.bdp_single_download .edd-add-to-cart-label:hover {
                    <?php if (isset($bdp_edd_addtocart_text_hover_color) && $bdp_edd_addtocart_text_hover_color) { ?> color: <?php echo $bdp_edd_addtocart_text_hover_color; ?> !important;<?php } ?>
                }

            <?php } ?>
            <?php if($post_type == 'product') { ?>

                /*Single Product Post Title*/
                .bdp_single_product .bdp_blog_template .post-title,
                .bdp_single_product .bdp_blog_template h1.post-title,
                .bdp_single_product .bdp_blog_template .blog_header h1,
                .bdp_single_product .bdp_blog_template h1.blog_header,
                .bdp_post_title,
                .bdp_single_product .bdp_blog_template .post-title h1,
                .bdp_single_product .bdp_blog_template .post_title {
                    margin-left: <?php echo esc_attr($product_single_post_title_marginleft).'px'; ?>;
                    margin-right: <?php echo esc_attr($product_single_post_title_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($product_single_post_title_margintop).'px !important'; ?>;
                    margin-bottom: <?php echo esc_attr($product_single_post_title_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($product_single_post_title_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($product_single_post_title_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($product_single_post_title_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($product_single_post_title_paddingbottom).'px'; ?>;
                }
                .bdp_single_product .bdp_blog_template .post-title a,
                .bdp_single_product .bdp_blog_template h1 a,
                .bdp_single_product .bdp_blog_template .blog_header h1 a,
                .bdp_single_product .bdp_blog_template h1.blog_header a, 
                .bdp_post_title a,
                .bdp_single_product .bdp_blog_template .post-title h1 a,
                .bdp_single_product .bdp_blog_template .post_title a,
                .bdp_single_product .post_content_wrap .post-title > a {
                    margin-left: <?php echo esc_attr($product_single_post_title_marginleft).'px'; ?>;
                    margin-right: <?php echo esc_attr($product_single_post_title_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($product_single_post_title_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($product_single_post_title_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($product_single_post_title_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($product_single_post_title_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($product_single_post_title_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($product_single_post_title_paddingbottom).'px'; ?>;
                }

                /*Single Product Post Content CSS*/
                .bdp_single_product .bdp_blog_template .post-content,
                .bdp_single_product .bdp_blog_template .post-content p,
                .bdp_single_product .bdp_blog_template .post_content,
                .bdp_single_product .bdp_blog_template .post_content p,
                .bdp_single_product .bdp_blog_template .post_content-inner,
                .bdp_single_product .bdp_blog_template .post_content-inner p,
                .bdp_single_product .bdp_blog_template .postcontent {
                    margin-left: <?php echo esc_attr($product_single_post_content_marginleft) .'px'; ?>;
                    margin-right: <?php echo esc_attr($product_single_post_content_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($product_single_post_content_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($product_single_post_content_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($product_single_post_content_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($product_single_post_content_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($product_single_post_content_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($product_single_post_content_paddingbottom).'px'; ?>;
                }

                    .bdp_woocommerce_price_wrap {
                        <?php if ($bdp_pricetext_alignment) { ?> text-align: <?php echo $bdp_pricetext_alignment; ?>;<?php } ?>
                    }
                    .bdp_single_product.brit_co .bdp_woocommerce_sale_wrap.right-top, .bdp_single_product.brit_co .bdp_woocommerce_sale_wrap.right-bottom span.onsale {
                        /* right: 37.2em; */
                    }
                    .bdp_single_product.cool_horizontal .bdp_woocommerce_sale_wrap.right-top, .bdp_single_product.cool_horizontal .bdp_woocommerce_sale_wrap.right-bottom span.onsale {
                        /* right: 35.7em; */
                    }
                    .bdp_single_product.brit_co .woocommerce-product-gallery__trigger, .bdp_single_product.timeline .woocommerce-product-gallery__trigger, .bdp_single_product.media-grid .woocommerce-product-gallery__trigger {
                        width: 5%;
                    }
                    .bdp_woocommerce_sale_wrap.right-top {
                        position: absolute;
                        left: auto;
                        right:0;
                    }
                    .bdp_woocommerce_sale_wrap.left-top {
                        position: absolute;
                    }
                    .bdp_woocommerce_sale_wrap.left-top span.onsale {
                        left: 0;
                        right: auto !important;
                    }
                    .bdp_woocommerce_sale_wrap.right-top span.onsale{
                        right: 0 !important;
                        left: auto !important;
                    }
                    .bdp_woocommerce_sale_wrap.left-bottom span.onsale{
                        top: auto !important;
                        bottom: 0;
                        left: 0;
                        right: auto !important;
                    }
                    .bdp_woocommerce_sale_wrap.right-bottom span.onsale{
                        right: 0 !important;
                        left: auto !important;
                        bottom: 0;
                        top: auto !important;
                    }
                    .bdp_woocommerce_price_wrap .price del .woocommerce-Price-amount {
                        text-decoration: line-through;
                    } 
                    .bdp_woocommerce_sale_wrap span.onsale:before,
                    .bdp_woocommerce_sale_wrap span.onsale:after {
                        content: '' !important;
                        border: none !important;
                    }
                    body:not(.woocommerce) .star-rating {
                        overflow: hidden;
                        position: relative;
                        height: 1em;
                        line-height: 1;
                        font-size: 1em;
                        width: 5.4em;
                        font-family: star;
                    }
                    .bdp_woocommerce_star_wrap {
                        text-align: <?php echo $bdp_star_rating_alignment; ?>;   
                    }
                    .bdp_woocommerce_star_wrap .star-rating {
                        float: none;
                        display: inline-block;
                    }
                    .bdp_woocommerce_star_wrap .star-rating {
                        <?php if ($bdp_star_rating_paddingleft) { ?> padding-left: <?php echo $bdp_star_rating_paddingleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_paddingright) { ?> padding-right: <?php echo $bdp_star_rating_paddingright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_paddingtop) { ?> padding-top: <?php echo $bdp_star_rating_paddingtop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_paddingbottom) { ?> padding-bottom: <?php echo $bdp_star_rating_paddingbottom. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_marginleft) { ?> margin-left: <?php echo $bdp_star_rating_marginleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_marginright) { ?> margin-right: <?php echo $bdp_star_rating_marginright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_margintop) { ?> margin-top: <?php echo $bdp_star_rating_margintop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_star_rating_marginbottom) { ?> margin-bottom: <?php echo $bdp_star_rating_marginbottom. 'px'; ?>;<?php } ?>
                        <?php if ('center' == $bdp_star_rating_alignment) { ?> margin: auto;<?php } ?>
                        <?php if ('right' == $bdp_star_rating_alignment) { ?> margin-left: auto; margin-right: 0;<?php } ?>
                    }
                    
                    .bdp_woocommerce_price_wrap ins,
                    .bdp_related_woocommerce_price_wrap ins{
                        display: inline-block;
                        <?php if ($bdp_sale_price_bgcolor) { ?> background-color: <?php echo $bdp_sale_price_bgcolor; ?> !important;<?php } ?>
                    }
                    body:not(.woocommerce) .star-rating {
                        line-height: 1;
                        font-size: 1em;
                        font-family: star;
                    }
                    .star-rating {
                        float: none;
                    }
                    .star-rating:before {
                        color: <?php if($bdp_star_rating_color != ''){ echo $bdp_star_rating_color; }else { echo $contentcolor; } ?>;
                    }
                    .star-rating span,.blog-start-rating-text {
                        color: <?php if($bdp_star_rating_bg_color != ''){ echo $bdp_star_rating_bg_color; }else { echo $linkcolor; } ?>;
                    }
                    body:not(.woocommerce) .star-rating:before {
                        content: '\73\73\73\73\73';
                        float: left;
                        top: 0;
                        left: 0;
                        position: absolute;
                    }
                    body:not(.woocommerce) .star-rating span {
                        overflow: hidden;
                        float: left;
                        top: 0;
                        left: 0;
                        position: absolute;
                        padding-top: 1.5em;
                    }
                    body:not(.woocommerce) .star-rating span:before {
                        content: '\53\53\53\53\53';
                        top: 0;
                        position: absolute;
                        left: 0;
                    }
                    <?php if(!empty($bdp_sale_tag_angle)){ ?>
                        .bdp_woocommerce_sale_wrap.right-top{
                            <?php if ($bdp_sale_tagtext_paddingtop) { ?> margin-top: <?php echo $bdp_sale_tagtext_paddingtop. 'px'; ?> !important;<?php } ?>
                            <?php if ($bdp_sale_tagtext_paddingright) { ?> margin-right: <?php echo $bdp_sale_tagtext_paddingright. 'px'; ?>!important;<?php } ?>
                        }
                        .bdp_woocommerce_sale_wrap.left-top{
                            <?php if ($bdp_sale_tagtext_paddingtop) { ?> margin-top: <?php echo $bdp_sale_tagtext_paddingtop. 'px'; ?> !important;<?php } ?>
                            <?php if ($bdp_sale_tagtext_paddingright) { ?> margin-left: <?php echo $bdp_sale_tagtext_paddingright. 'px'; ?>!important;<?php } ?>
                        }
                    <?php } ?>
                    .bdp_woocommerce_sale_wrap span.onsale {
                        color: <?php echo $bdp_sale_tagtextcolor; ?> !important;
                        font-size: <?php echo $bdp_sale_tagfontsize . 'px'; ?>;
                        <?php if ($bdp_sale_tagfontface) { ?> font-family: <?php echo $bdp_sale_tagfontface; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_weight) { ?> font-weight: <?php echo $bdp_sale_tag_font_weight; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_line_height) { ?> line-height: <?php echo $bdp_sale_tag_font_line_height; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_letter_spacing) { ?> letter-spacing: <?php echo $bdp_sale_tag_font_letter_spacing. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_text_transform) { ?> text-transform: <?php echo $bdp_sale_tag_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_text_decoration) { ?> text-decoration: <?php echo $bdp_sale_tag_font_text_decoration; ?>;<?php } ?>
                        background-color: <?php echo $bdp_sale_tagbgcolor; ?> !important;
                        <?php if ($bdp_sale_tagtext_marginleft) { ?> margin-left: <?php echo $bdp_sale_tagtext_marginleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_marginright) { ?> margin-right: <?php echo $bdp_sale_tagtext_marginright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_margintop) { ?> margin-top: <?php echo $bdp_sale_tagtext_margintop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_marginbottom) { ?> margin-bottom: <?php echo $bdp_sale_tagtext_marginbottom. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingleft) { ?> padding-left: <?php echo $bdp_sale_tagtext_paddingleft. 'px'; ?> !important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingright) { ?> padding-right: <?php echo $bdp_sale_tagtext_paddingright. 'px'; ?>!important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingtop) { ?> padding-top: <?php echo $bdp_sale_tagtext_paddingtop. 'px'; ?> !important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingbottom) { ?> padding-bottom: <?php echo $bdp_sale_tagtext_paddingbottom. 'px'; ?> !important;<?php } ?>
                        width: auto;
                        transform: rotate(<?php echo $bdp_sale_tag_angle;?>deg);
                        border-radius: <?php echo $bdp_sale_tag_border_radius;?>% !important;
                        top: 0;
                    
                    }
                    .bdp_woocommerce_sale_wrap span.onsale {
                        z-index: 1 !important;
                    }
                    .bdp_woocommerce_sale_wrap span.onsale {
                        min-height: 0 ;
                        min-width: 0;
                    }
                    body:not(.woocommerce) .bdp_woocommerce_sale_wrap span.onsale{
                        position: absolute;
                        text-align: center;
                        top: -.5em;
                        left: -.5em;
                        z-index: 1 !important;
                        background-color: #77a464;
                        color: #fff;
                    }
                    .bdp_woocommerce_add_to_cart_wrap .quantity {
                        display: inline-block;
                        
                    }
                    .bdp_single_product table td, 
                    .bdp_single_product table th {
                        padding: 10px;
                        display: table-cell;
                    }
                    .bdp_related_woocommerce_sale_wrap .onsale {
                        color: <?php echo $bdp_sale_tagtextcolor; ?> !important;
                        font-size: <?php echo $bdp_sale_tagfontsize . 'px'; ?>;
                        <?php if ($bdp_sale_tagfontface) { ?> font-family: <?php echo $bdp_sale_tagfontface; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_weight) { ?> font-weight: <?php echo $bdp_sale_tag_font_weight; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_line_height) { ?> line-height: <?php echo $bdp_sale_tag_font_line_height; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_letter_spacing) { ?> letter-spacing: <?php echo $bdp_sale_tag_font_letter_spacing. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_text_transform) { ?> text-transform: <?php echo $bdp_sale_tag_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_sale_tag_font_text_decoration) { ?> text-decoration: <?php echo $bdp_sale_tag_font_text_decoration; ?>;<?php } ?>
                        background-color: <?php echo $bdp_sale_tagbgcolor; ?> !important;
                        <?php if ($bdp_sale_tagtext_marginleft) { ?> margin-left: <?php echo $bdp_sale_tagtext_marginleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_marginright) { ?> margin-right: <?php echo $bdp_sale_tagtext_marginright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_margintop) { ?> margin-top: <?php echo $bdp_sale_tagtext_margintop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_marginbottom) { ?> margin-bottom: <?php echo $bdp_sale_tagtext_marginbottom. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingleft) { ?> padding-left: <?php echo $bdp_sale_tagtext_paddingleft. 'px'; ?> !important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingright) { ?> padding-right: <?php echo $bdp_sale_tagtext_paddingright. 'px'; ?>!important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingtop) { ?> padding-top: <?php echo $bdp_sale_tagtext_paddingtop. 'px'; ?> !important;<?php } ?>
                        <?php if ($bdp_sale_tagtext_paddingbottom) { ?> padding-bottom: <?php echo $bdp_sale_tagtext_paddingbottom. 'px'; ?> !important;<?php } ?>
                        width: 10%;
                        transform: rotate(<?php echo $bdp_sale_tag_angle;?>deg);
                        border-radius: <?php echo $bdp_sale_tag_border_radius;?>% !important;
                        top: 0;
                    }

                    .right .bdp_related_woocommerce_sale_wrap .onsale, .left .bdp_related_woocommerce_sale_wrap .onsale {
                        width: 30%;
                    }
                    
                    .bdp_related_product_woocommerce_add_to_cart_wrap .product_type_external,
                    .bdp_related_product_woocommerce_add_to_cart_wrap .product_type_grouped,
                    .bdp_related_product_woocommerce_add_to_cart_wrap .single_add_to_cart_button,
                    .bdp_related_product_woocommerce_add_to_cart_wrap .add_to_cart_button .wpbm-span,
                    .bdp_related_product_woocommerce_add_to_cart_wrap .add_to_cart_button {
                        <?php if ($bdp_addtocart_textcolor) { ?>color: <?php echo $bdp_addtocart_textcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocart_backgroundcolor) { ?>background: <?php echo $bdp_addtocart_backgroundcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderleft) { ?>border-left:<?php echo $bdp_addtocartbutton_borderleft.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderleftcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderright) { ?>border-right:<?php echo $bdp_addtocartbutton_borderright.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderrightcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_bordertop) { ?>border-top:<?php echo $bdp_addtocartbutton_bordertop.'px'; ?> solid <?php echo $bdp_addtocartbutton_bordertopcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderbuttom) { ?>border-bottom:<?php echo $bdp_addtocartbutton_borderbuttom.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderbottomcolor; ?> !important;<?php } ?>
                        border-radius:<?php echo $display_addtocart_button_border_radius.'px';?> !important;
                        padding : <?php echo $bdp_addtocartbutton_padding_topbottom.'px'; ?> <?php echo $bdp_addtocartbutton_padding_leftright.'px'; ?>;
                        margin: <?php echo $bdp_addtocartbutton_margin_topbottom.'px'; ?> <?php echo $bdp_addtocartbutton_margin_leftright.'px'; ?>;
                        <?php if($bdp_addtocart_button_box_shadow_color) { ?>box-shadow: <?php echo $bdp_addtocart_button_top_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_right_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_bottom_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_left_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_box_shadow_color; ?> !important; <?php } ?>
                        display: inline-block;
                    }
                    .bdp_single_product .price {
                        display: inline-block;
                        <?php if ($bdp_pricetext_paddingleft) { ?> padding-left: <?php echo $bdp_pricetext_paddingleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_paddingright) { ?> padding-right: <?php echo $bdp_pricetext_paddingright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_paddingtop) { ?> padding-top: <?php echo $bdp_pricetext_paddingtop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_paddingbottom) { ?> padding-bottom: <?php echo $bdp_pricetext_paddingbottom. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_marginleft) { ?> margin-left: <?php echo $bdp_pricetext_marginleft. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_marginright) { ?> margin-right: <?php echo $bdp_pricetext_marginright. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_margintop) { ?> margin-top: <?php echo $bdp_pricetext_margintop. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_marginbottom) { ?> margin-bottom: <?php echo $bdp_pricetext_marginbottom. 'px'; ?>;<?php } ?>
                    }
                    .bdp_single_product .price .woocommerce-Price-amount {
                        color: <?php echo $bdp_pricetextcolor; ?> !important;
                        font-size: <?php echo $bdp_pricefontsize . 'px'; ?>;
                        <?php if ($bdp_pricefontface) { ?> font-family: <?php echo $bdp_pricefontface; ?>;<?php } ?>
                        <?php if ($bdp_price_font_weight) { ?> font-weight: <?php echo $bdp_price_font_weight; ?>;<?php } ?>
                        <?php if ($bdp_price_font_line_height) { ?> line-height: <?php echo $bdp_price_font_line_height; ?>;<?php } ?>
                        <?php if ($bdp_price_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($bdp_price_font_letter_spacing) { ?> letter-spacing: <?php echo $bdp_price_font_letter_spacing. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_price_font_text_transform) { ?> text-transform: <?php echo $bdp_price_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_price_font_text_decoration) { ?> text-decoration: <?php echo $bdp_price_font_text_decoration; ?>;<?php } ?>
                        <?php if ($bdp_pricetext_alignment) { ?> text-align: <?php echo $bdp_pricetext_alignment; ?>;<?php } ?>
                        width: auto;
                        word-break: break-all;
                        
                    }
                    .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button,
                    .bdp_woocommerce_add_to_cart_wrap .single_add_to_cart_button,
                    .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button .wpbm-span,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_external,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_grouped {
                        font-size: <?php echo $bdp_addtocart_button_fontsize . 'px'; ?>;
                        <?php if ($bdp_addtocart_button_fontface) { ?> font-family: <?php echo $bdp_addtocart_button_fontface; ?>;<?php } ?>
                        <?php if ($bdp_addtocart_button_font_weight) { ?> font-weight: <?php echo $bdp_addtocart_button_font_weight; ?>;<?php } ?>
                        <?php if ($display_addtocart_button_line_height) { ?> line-height: <?php echo $display_addtocart_button_line_height; ?>;<?php } ?>
                        <?php if ($bdp_addtocart_button_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        letter-spacing: <?php echo $bdp_addtocart_button_letter_spacing. 'px'; ?>;
                        <?php if ($bdp_addtocart_button_font_text_transform) { ?> text-transform: <?php echo $bdp_addtocart_button_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_addtocart_button_font_text_decoration) { ?> text-decoration: <?php echo $bdp_addtocart_button_font_text_decoration; ?>;<?php } ?>
                    }

                    .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button,
                    .bdp_woocommerce_add_to_cart_wrap .single_add_to_cart_button,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_external,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_grouped {
                        border-bottom: 3px solid !important;
                        <?php if ($bdp_addtocart_textcolor) { ?>color: <?php echo $bdp_addtocart_textcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocart_backgroundcolor) { ?>background: <?php echo $bdp_addtocart_backgroundcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderleft) { ?>border-left:<?php echo $bdp_addtocartbutton_borderleft.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderleftcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderright) { ?>border-right:<?php echo $bdp_addtocartbutton_borderright.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderrightcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_bordertop) { ?>border-top:<?php echo $bdp_addtocartbutton_bordertop.'px'; ?> solid <?php echo $bdp_addtocartbutton_bordertopcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_borderbuttom) { ?>border-bottom:<?php echo $bdp_addtocartbutton_borderbuttom.'px'; ?> solid <?php echo $bdp_addtocartbutton_borderbottomcolor; ?> !important;<?php } ?>
                        border-radius:<?php echo $display_addtocart_button_border_radius.'px';?> !important;
                        padding : <?php echo $bdp_addtocartbutton_padding_topbottom.'px'; ?> <?php echo $bdp_addtocartbutton_padding_leftright.'px'; ?> !important;
                        margin: <?php echo $bdp_addtocartbutton_margin_topbottom.'px'; ?> <?php echo $bdp_addtocartbutton_margin_leftright.'px'; ?> !important;
                        <?php if($bdp_addtocart_button_box_shadow_color) { ?>box-shadow: <?php echo $bdp_addtocart_button_top_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_right_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_bottom_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_left_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_box_shadow_color; ?> !important; <?php } ?>
                        display: inline-block;
                        border: none;
                    }
                    .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button:hover,
                    .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button:focus,
                    .bdp_woocommerce_add_to_cart_wrap .single_add_to_cart_button:hover,
                    .bdp_woocommerce_add_to_cart_wrap .single_add_to_cart_button:focus,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_external:hover,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_external:focus,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_grouped:hover,
                    .bdp_woocommerce_add_to_cart_wrap .product_type_grouped:focus,
                    .bdp_single .related_post_wrap a:hover,
                    .bdp_single .related_post_wrap a:focus {
                        <?php if ($bdp_addtocart_text_hover_color) { ?>color: <?php echo $bdp_addtocart_text_hover_color; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocart_hover_backgroundcolor) { ?>background: <?php echo $bdp_addtocart_hover_backgroundcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_hover_borderleft) { ?>border-left:<?php echo $bdp_addtocartbutton_hover_borderleft.'px'; ?> solid <?php echo $bdp_addtocartbutton_hover_borderleftcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_hover_borderright) { ?>border-right:<?php echo $bdp_addtocartbutton_hover_borderright.'px'; ?> solid <?php echo $bdp_addtocartbutton_hover_borderrightcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_hover_bordertop) { ?>border-top:<?php echo $bdp_addtocartbutton_hover_bordertop.'px'; ?> solid <?php echo $bdp_addtocartbutton_hover_bordertopcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_addtocartbutton_hover_borderbuttom) { ?>border-bottom:<?php echo $bdp_addtocartbutton_hover_borderbuttom.'px'; ?> solid <?php echo $bdp_addtocartbutton_hover_borderbottomcolor; ?> !important;<?php } ?>
                        border-radius:<?php echo $display_addtocart_button_border_hover_radius.'px';?> !important;
                        <?php if($bdp_addtocart_button_hover_box_shadow_color) { ?>box-shadow: <?php echo $bdp_addtocart_button_hover_top_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_hover_right_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_hover_bottom_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_hover_left_box_shadow.'px'; ?> <?php echo $bdp_addtocart_button_hover_box_shadow_color; ?> !important;<?php } ?>
                    }
                    <?php if($bdp_wishlistbutton_on == 1 && $display_addtowishlist_button == 1) { ?>
                        .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp{
                        text-align:<?php echo $bdp_cart_wishlistbutton_alignment; ?>;
                    }
                    <?php } else { ?>
                        .bdp_woocommerce_add_to_cart_wrap {
                            text-align:<?php echo $bdp_addtocartbutton_alignment; ?>;
                        }
                        .bdp_woocommerce_add_to_cart_wrap .quantity {
                            float: none !important;
                        }
                        .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
                        .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
                        .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show{
                            text-align:<?php echo $bdp_wishlistbutton_alignment; ?>;
                        }
                    <?php } ?>
                    
                    
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse .feedback,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse .feedback{ 
                        display: none !important; 
                    }
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button .add_to_wishlist,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a{
                        font-size: <?php echo $bdp_addtowishlist_button_fontsize . 'px'; ?>;
                        <?php if ($bdp_addtowishlist_button_fontface) { ?> font-family: <?php echo $bdp_addtowishlist_button_fontface; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_font_weight) { ?> font-weight: <?php echo $bdp_addtowishlist_button_font_weight; ?>;<?php } ?>
                        <?php if ($display_wishlist_button_line_height) { ?> line-height: <?php echo $display_wishlist_button_line_height; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        letter-spacing: <?php echo $bdp_addtowishlist_button_letter_spacing. 'px'; ?>;
                        <?php if ($bdp_addtowishlist_button_font_text_transform) { ?> text-transform: <?php echo $bdp_addtowishlist_button_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_font_text_decoration) { ?> text-decoration: <?php echo $bdp_addtowishlist_button_font_text_decoration; ?>;<?php } ?>
                    }
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button .add_to_wishlist,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a {
                        <?php if ($bdp_wishlist_textcolor) { ?>color: <?php echo $bdp_wishlist_textcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlist_backgroundcolor) { ?>background: <?php echo $bdp_wishlist_backgroundcolor; ?>;<?php } ?>
                        <?php if ($bdp_wishlistbutton_borderleft) { ?>border-left:<?php echo $bdp_wishlistbutton_borderleft.'px'; ?> solid <?php echo $bdp_wishlistbutton_borderleftcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_borderright) { ?>border-right:<?php echo $bdp_wishlistbutton_borderright.'px'; ?> solid <?php echo $bdp_wishlistbutton_borderrightcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_bordertop) { ?>border-top:<?php echo $bdp_wishlistbutton_bordertop.'px'; ?> solid <?php echo $bdp_wishlistbutton_bordertopcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_borderbuttom) { ?>border-bottom:<?php echo $bdp_wishlistbutton_borderbuttom.'px'; ?> solid <?php echo $bdp_wishlistbutton_borderbottomcolor; ?> !important;<?php } ?>
                        border-radius:<?php echo $display_wishlist_button_border_radius.'px';?> !important;
                        padding : <?php echo $bdp_wishlistbutton_padding_topbottom.'px'; ?> <?php echo $bdp_wishlistbutton_padding_leftright.'px'; ?>;
                        margin: <?php echo $bdp_wishlistbutton_margin_topbottom.'px'; ?> <?php echo $bdp_wishlistbutton_margin_leftright.'px'; ?>;
                        <?php if($bdp_wishlist_button_box_shadow_color) { ?>box-shadow: <?php echo $bdp_wishlist_button_top_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_right_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_bottom_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_left_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_box_shadow_color; ?> !important;<?php } ?>
                        display: inline-block;
                        
                    }
                    .bdp_related_woocommerce_sale_wrap span.onsale{
                        left: 0;
                        top: 0;
                    }
                    
                    .add_to_wishlist:before {
                        content: "\f08a";
                        font-family: fontawesome;
                        <?php if ($bdp_addtowishlist_button_font_weight) { ?> font-weight: <?php echo $bdp_addtowishlist_button_font_weight; ?>;<?php } ?>
                        vertical-align: middle;
                        <?php if ($bdp_addtowishlist_button_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        font-size: <?php echo $bdp_addtowishlist_button_fontsize . 'px'; ?>;
                        <?php if ($display_wishlist_button_line_height) { ?> line-height: <?php echo $display_wishlist_button_line_height; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_letter_spacing) { ?> letter-spacing: <?php echo $bdp_addtowishlist_button_letter_spacing. 'px'; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_font_text_transform) { ?> text-transform: <?php echo $bdp_addtowishlist_button_font_text_transform; ?>;<?php } ?>
                        <?php if ($bdp_addtowishlist_button_font_text_decoration) { ?> text-decoration: <?php echo $bdp_addtowishlist_button_font_text_decoration; ?>;<?php } ?>
                    }
                    .bdp_blog_template img.ajax-loading {
                        display: none !important;
                    }
                    <?php if($bdp_wishlistbutton_on == 1 && $display_addtowishlist_button == 1) { ?>
                        
                        .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line {
                            padding: 3px;
                        }
                        .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_cart_wrap {
                            display: inline-block;
                            width: auto;
                            vertical-align: top;
                        }
                        .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_wishlist_wrap {
                            display: inline-block;
                            width: auto;
                            vertical-align: top;
                        }
                    <?php } ?>
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button .add_to_wishlist:hover,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button .add_to_wishlist:focus,
                    .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist:hover,
                    .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist:focus,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a:hover,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a:focus,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a:hover,
                    .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a:focus {
                        <?php if ($bdp_wishlist_text_hover_color) { ?>color: <?php echo $bdp_wishlist_text_hover_color; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlist_hover_backgroundcolor) { ?>background: <?php echo $bdp_wishlist_hover_backgroundcolor; ?>;<?php } ?>
                        <?php if ($bdp_wishlistbutton_hover_borderleft) { ?>border-left:<?php echo $bdp_wishlistbutton_hover_borderleft.'px'; ?> solid <?php echo $bdp_wishlistbutton_hover_borderleftcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_hover_borderright) { ?>border-right:<?php echo $bdp_wishlistbutton_hover_borderright.'px'; ?> solid <?php echo $bdp_wishlistbutton_hover_borderrightcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_hover_bordertop) { ?>border-top:<?php echo $bdp_wishlistbutton_hover_bordertop.'px'; ?> solid <?php echo $bdp_wishlistbutton_hover_bordertopcolor; ?> !important;<?php } ?>
                        <?php if ($bdp_wishlistbutton_hover_borderbuttom) { ?>border-bottom:<?php echo $bdp_wishlistbutton_hover_borderbuttom.'px'; ?> solid <?php echo $bdp_wishlistbutton_hover_borderbottomcolor; ?> !important;<?php } ?>
                        border-radius:<?php echo $display_wishlist_button_border_hover_radius.'px';?> !important;
                        <?php if($bdp_wishlist_button_hover_box_shadow_color) { ?>box-shadow: <?php echo $bdp_wishlist_button_hover_top_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_hover_right_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_hover_bottom_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_hover_left_box_shadow.'px'; ?> <?php echo $bdp_wishlist_button_hover_box_shadow_color; ?> !important;<?php } ?>;
                    }
                    .bdp_woocommerce_meta_box {
                        display: inline-block;
                        width: 100%;
                    }
                <?php } ?>
                .bdp-count {
                    padding-left: 5px;
                }
                .bdp_single .comment-list .comment-content,
                .bdp_single .comment-form label,
                .bdp_single .comment-list .comment-content p {                    
                    <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                }
                .bdp_single .comment-list .comment-content,
                .bdp_single .comment-form label,
                .bdp_single .comment-list .comment-content p:not(.has-text-color):not(.has-large-font-size):not(.wp-block-cover-text), 
                .bdp_single .woocommerce-noreviews,
                .bdp_single .woocommerce #respond input#submit {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                }
                .bdp_single .woocommerce-Reviews-title {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                }
                .bdp_single .comment-list .comment-content,
                .bdp_single .comment-form label,
                .bdp_single .comment-list .comment-content p:not(.has-text-color):not(.has-large-font-size):not(.wp-block-cover-text), 
                .bdp_single .woocommerce-noreviews,
                .bdp_single .woocommerce #respond input#submit {
                    color: <?php echo $contentcolor; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .comment-list .comment-content,
                .bdp_single .comment-form label,
                .bdp_single .comment-list .comment-content p:not(.has-large-font-size):not(.wp-block-cover-text) {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                }
                .bdp_single #respond .comment-form-comment textarea#comment{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $contentcolor; ?>;
                    <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                }
                .bdp_blog_template.brite .post-meta > div i,
                .bdp_single .relatedposts .relatedthumb .related_post_content,
                .bdp_single .bdp_blog_template .post_content,
                .bdp_single .bdp_blog_template .post_content p:not(.has-text-color):not(.has-large-font-size):not(.wp-block-cover-text),
                .bdp_single .author_content p,
                .display_post_views p{
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single .bdp_blog_template .post_content,
                .bdp_single .bdp_blog_template .post_content p {
                    text-shadow: <?php echo esc_attr($bdp_content_top_box_shadow) .' '. esc_attr($bdp_content_right_box_shadow) .' '. esc_attr($bdp_content_bottom_box_shadow).' '. esc_attr($bdp_content_box_shadow_color); ?>;
                }
                .bdp_single .relatedposts .relatedthumb .related_post_content,
                .bdp_single .bdp_blog_template .post_content,
                .bdp_single .bdp_blog_template .post_content p:not(.has-text-color),
                .bdp_single .author_content p,
                .display_post_views p,
                .bdp_single_product .bdp_woocommerce_meta_box .sku_wrapper {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    word-break: break-word;
                }
                .bdp_single .bdp_blog_template.classical .blog_header h1,
                .bdp_single .bdp_blog_template .blog_header h1.post-title,
                .bdp_single .bdp_blog_template .blog_header h1,
                .bdp_single .bdp_blog_template h1.post-title {
                    text-shadow: <?php echo esc_attr($bdp_title_top_box_shadow) .' '. esc_attr($bdp_title_right_box_shadow) .' '. esc_attr($bdp_title_bottom_box_shadow).' '. esc_attr($bdp_title_box_shadow_color); ?> !important; 
                    margin-left: <?php echo esc_attr($single_post_title_marginleft).'px'; ?>;
                    margin-right: <?php echo esc_attr($single_post_title_marginright).'px'; ?>;
                    margin-top: <?php echo esc_attr($single_post_title_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($single_post_title_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($single_post_title_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($single_post_title_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($single_post_title_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($single_post_title_paddingbottom).'px'; ?>;
                }
                .bdp_single .glossary .blog_item {
                    background: <?php echo esc_attr($template_bgcolor); ?>;
                }
                .bdp_single_product .bdp_woocommerce_meta_box .sku_wrapper {
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single .relatedposts .relatedthumb .related_post_content,
                .bdp_single .bdp_blog_template .post_content,
                .bdp_single .bdp_blog_template .post_content blockquote:not(.wp-block-quote.is-style-large) p,
                .bdp_single .bdp_blog_template .post_content p:not(.has-huge-font-size):not(.has-large-font-size):not(.has-medium-font-size):not(.has-small-font-size):not(.wp-block-cover-text),
                .bdp_single .author_content p,
                .display_post_views p{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                }
                .bdp_single .bdp_blog_template .post_content h1,
                .bdp_single .bdp_blog_template .post_content h2,
                .bdp_single .bdp_blog_template .post_content h3,
                .bdp_single .bdp_blog_template .post_content h4,
                .bdp_single .bdp_blog_template .post_content h5,
                .bdp_single .bdp_blog_template .post_content h6 {
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                }
                .bdp_single .bdp_blog_template .blog_header h1.post-title,
                .bdp_single .bdp_blog_template .blog_header h1,
                .bdp_single .bdp_blog_template h1.post-title {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                    margin-top: 0;
                }
                .bd .bdp_single .bdp_blog_template .blog_header h1.post-title,
                .bdp_single .bdp_blog_template .blog_header h1,
                .bdp_single .bdp_blog_template h1.post-title {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.easy_timeline .link-label {
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single.easy_timeline .blog_footer .share-this span {
                    font-size: <?php echo $txtSocialTextSize . 'px'; ?>;
                   <?php if ($txtSocialTextFont) { ?> font-family: <?php echo $txtSocialTextFont; ?>; <?php } ?>
                   color : <?php echo $titlecolor; ?>;
                   <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?> 
                }
                .bdp_single .seperater {
                    margin-right: 5px;
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single .post-navigation .nav-links .post-title,
                .bdp_single .post-navigation .nav-links .post-title,
                .bdp_single .bdp_blog_template .tags,
                .bdp_single .bdp_blog_template .categories,
                .bdp_single .bdp_blog_template .category-link,
                .bdp_single .bdp_blog_template .category-links,
                .bdp_single .author,
                .bdp_single .post-tags-wrapp,
                .bdp_single .related_post_wrap,
                .bdp_single .comment-respond .comment-form,
                .bdp_single .comments-area .comment-body,
                .bdp_single .social-component .social-share,
                .bdp_single .link-lable,
                .bdp_single .footer_meta,
                .bdp_single .meta-archive {
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single .bdp_blog_template a,
                .bdp_single .bdp_blog_template .accodine-title-name,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .bdp_blog_template .tags a,
                .bdp_single .bdp_blog_template .categories a,
                .bdp_single .bdp_blog_template .category-link a,
                .bdp_single .bdp_blog_template .category-links a,
                .bdp_single .author a,
                .bdp_single .related_post_wrap a span,
                .bdp_single .related_post_wrap a,
                .bdp_single .comment-respond .comment-form a,
                .bdp_single .comments-area .comment-body a,
                .bdp_single .social-component .social-share a,
                .bdp_single .link-lable,
                .bdp_single .footer_meta a,
                .bdp_single .bdp_blog_template,
                .bdp_single .post-navigation .nav-links .post-title,
                .bdp_single .post-navigation .nav-links .post-title,
                .bdp_single .bdp_blog_template .tags,
                .bdp_single .bdp_blog_template .categories,
                .bdp_single .bdp_blog_template .category-link,
                .bdp_single .bdp_blog_template .category-links,
                .bdp_single .author,
                .bdp_single .related_post_wrap,
                .bdp_single .comment-respond .comment-form,
                .bdp_single .comments-area .comment-body,
                .bdp_single .social-component .social-share,
                .bdp_single .link-lable,
                .bdp_single .footer_meta,
                .bdp_single .meta-archive {
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single_product ol{
                    display: inline-block;
                    width: 100%;
                    padding: 0;
                    margin: 2px 0 0 0;
                }
                .bdp_single_product .woocommerce-product-gallery {
                    margin-bottom: 0;
                }
                .bdp_single .bdp_blog_template .tags,
                .bdp_single .bdp_blog_template .categories,
                .bdp_single .bdp_blog_template .category-link,
                .bdp_single .bdp_blog_template .category-links,
                .bdp_single .author,
                .bdp_single .navigation.post-navigation .nav-links a .post-data span.navi-post-title,
                .bdp_single .navigation.post-navigation .post-data .navi-post-date,
                .bdp_single .author-avatar label,
                .bdp_single .post-meta label,
                .bdp_single .footer_meta {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    color: <?php echo $contentcolor; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .bdp_blog_template p:not(.has-text-color):not(.wp-block-file__button) a,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .bdp_blog_template .tags a,
                .bdp_single .bdp_blog_template .categories a,
                .bdp_single .bdp_blog_template .category-link a,
                .bdp_single .bdp_blog_template .category-links a,
                .bdp_single .author a,
                .bdp_single .related_post_wrap a span,
                .bdp_single .related_post_wrap a,
                .bdp_single .comment-respond .comment-form a,
                .bdp_single .comments-area .comment-body a,
                .bdp_single .social-component .social-share a,
                .bdp_single .footer_meta a {
                    color:<?php echo $linkcolor; ?>;
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .link-lable,
                .bdp_single table.variations label,
                .bdp_single .reset_variations {
                    color:<?php echo $contentcolor; ?>;
                }
                .bdp-post-meta span {
                    color:<?php echo $contentcolor; ?>;
                }
                .bdp_single .link-lable,
                .bdp_single table.variations label,
                .bdp_single table.variations select,
                .bdp_single .reset_variations {
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single span.left_nav,
                .bdp_single span.right_nav {
                    color:<?php echo $linkcolor; ?>;
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .bdp_blog_template .social-component.bdp-social-style-custom a{
                    border: 1px solid <?php echo $linkcolor; ?>;
                }
                .bdp_single .comments-area .comment-reply-link {
                    border-color:<?php echo $linkcolor; ?>;
                    color:<?php echo $linkcolor; ?>;
                }
                .bdp_single .bdp_blog_template a:hover:not(.has-text-color):not(.wp-block-file__button),
                .bdp_single .bdp_blog_template .accodine-title-name:hover:not(.has-text-color):not(.wp-block-file__button),
                .bdp_single .bdp_blog_template a.month:hover,
                .bdp_single .bdp_blog_template .accodine-title-name.month:hover,
                .bdp_single .bdp_blog_template .post-comment a, 
                .bdp_single .bdp_blog_template .bdp-wrapper-like i,
                .bdp_single .bdp_blog_template .bdp-wrapper-like .bdp-count,
                .bdp_single a.styled-button:hover span.left_nav,
                .bdp_single a.styled-button:hover span.right_nav,
                .bdp_single .post-navigation .nav-links a:focus .post-title,
                .bdp_single .post-navigation .nav-links a:hover .post-title,
                .bdp_single .bdp_blog_template .tags a:hover,
                .bdp_single .bdp_blog_template .categories a:hover,
                .bdp_single .bdp_blog_template .category-link a:hover,
                .bdp_single .author a:hover,
                .bdp_single .related_post_wrap a:hover,
                .bdp_single .comment-respond .comment-form a:hover,
                .bdp_single .comments-area .comment-body a:hover,
                .bdp_single .social-component .social-share a:hover {
                    color: <?php echo $linkhovercolor; ?> !important;
                }
                .bdp_single .comments-area .comment-reply-link:hover {
                    border-color:<?php echo $linkhovercolor; ?>;
                    color: <?php echo $linkhovercolor; ?>;

                }
                .bdp_related_woocommerce_sale_wrap {
                    position: relative;
                }
                .bdp_single .bdp_blog_template a,
                .bdp_single .bdp_blog_template .accodine-title-name,
                .bdp_single .bdp_blog_template .tags a,
                .bdp_single .bdp_blog_template .categories a,
                .bdp_single .bdp_blog_template .category-link a,
                .bdp_single .bdp_blog_template a.month,
                .bdp_single .bdp_blog_template .accodine-title-name.month,
                .bdp_single .bdp_blog_template .post-comment a,
                .bdp_single .bdp_blog_template a,
                .bdp_single .bdp_blog_template .accodine-title-name,
                .bdp_single .bdp_blog_template a.month,
                .bdp_single .bdp_blog_template .accodine-title-name.month,
                .bdp_single .bdp_blog_template .post-comment a, 
                .bdp_single .bdp_blog_template .bdp-wrapper-like i,
                .bdp_single .bdp_blog_template .bdp-wrapper-like .bdp-count,
                .bdp_single a.styled-button span.left_nav,
                .bdp_single a.styled-button span.right_nav,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .post-navigation .nav-links a .post-title,
                .bdp_single .bdp_blog_template .tags a,
                .bdp_single .bdp_blog_template .categories a,
                .bdp_single .bdp_blog_template .category-link a,
                .bdp_single .author a,
                .bdp_single .related_post_wrap a span,
                .bdp_single .related_post_wrap a,
                .bdp_single .comment-respond .comment-form a,
                .bdp_single .comments-area .comment-body a,
                .bdp_single .social-component .social-share a {
                    color:<?php echo $linkcolor; ?> !important;
                }
                .bdp_single.foodbox .bdp-post-title h2, .bdp_single.foodbox .bdp-post-title h2 a, .bdp_single.foodbox .bdp-post-title a {
                    color:<?php echo $titlecolor; ?> !important;
                }
                .bdp_single .related_post_wrap .relatedpost_title {
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .related_post_wrap h3 {
                    color:<?php echo $relatedTitleColor; ?>;
                    font-size: <?php echo $relatedTitleSize . 'px'; ?>;
                    <?php if ($relatedTitleFace) { ?> font-family: <?php echo $relatedTitleFace; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.region .related_post_wrap h3:before {
                    background-color: <?php echo $relatedTitleColor; ?>;
                }
                .bdp_single .author-avatar-div .author_content .author a,
                .bdp_single .author-avatar-div .author_content .author {
                    font-size: <?php echo $authorTitleSize . 'px'; ?>;
                <?php if ($authorTitleFace) { ?> font-family: <?php echo $authorTitleFace; ?>; <?php } ?>
                }
                .bdp_single .bdp_blog_template .share-this {
                    font-size: <?php echo $txtSocialTextSize . 'px'; ?>;
                    <?php if ($txtSocialTextFont) { ?> font-family: <?php echo $txtSocialTextFont; ?>; <?php } ?>
                    color : <?php echo $titlecolor; ?>;
                    display: inline-block;
                    vertical-align: top;
                    position: relative;
                    margin-top: 15px;
                    margin-right: 15px;
                }
                .bdp_single .bdp_blog_template .share-this i{
                    font-family: Font Awesome\ 5 Brands !important;
                }
                .bdp_single .gallery-caption{
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .bdp_blog_template .social-component .social-share .count,
                .bdp_single .navigation.post-navigation .post-data .navi-post-date,
                .bdp_single .gallery-caption{
                    color: <?php echo $contentcolor; ?>;
                }
                /*.bdp_single .author-avatar-div span.author,
                .bdp_single .comments-title,
                .bdp_single .comment-reply-title,
                .bdp_single .no-comments,
                .bdp_single .woocommerce-noreviews {
                    color: <?php //echo $titlecolor; ?>;
                    <?php //if ($template_titlefontface) { ?> font-family: <?php //echo $template_titlefontface; ?>; <?php //} ?>
                    <?php //if ($template_title_font_weight) { ?> font-weight: <?php //echo $template_title_font_weight; ?>;<?php //} ?>
                    <?php //if ($template_title_font_line_height) { ?> line-height: <?php //echo $template_title_font_line_height; ?>;<?php //} ?>
                    <?php //if ($template_title_font_italic) { ?> font-style: <?php //echo 'italic'; ?>;<?php //} ?>
                    <?php //if ($template_title_font_text_transform) { ?> text-transform: <?php //echo $template_title_font_text_transform; ?>;<?php //} ?>
                    <?php //if ($template_title_font_text_decoration) { ?> text-decoration: <?php //echo $template_title_font_text_decoration; ?>;<?php //} ?>
                    <?php //if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php //echo $template_title_font_letter_spacing . 'px'; ?>;<?php //} ?>
                }*/
                .bdp_single .navigation.post-navigation .nav-links a .post-data span.navi-post-title {
                    color: <?php echo $linkcolor; ?>;
                    word-break: break-word;
                }
                .bdp_single .navigation.post-navigation .nav-links a:hover .post-data span.navi-post-title {
                    color: <?php echo $linkhovercolor; ?>;
                }
                .bdp_single blockquote {
                    border-color: <?php echo $linkhovercolor; ?>;
                    background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                    padding: 15px 15px 15px 30px;
                    margin: 15px 0;
                    color: <?php echo $contentcolor; ?>;
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single .bdp_blog_template .post-content,
                .bdp_single .bdp_blog_template .post-content p,
                .bdp_single .bdp_blog_template .post_content,
                .bdp_single .bdp_blog_template .post_content p,
                .bdp_single .bdp_blog_template .post_content-inner,
                .bdp_single .bdp_blog_template .post_content-inner p,
                .bdp_single .bdp_blog_template .postcontent {
                    margin-left: <?php echo esc_attr($single_post_content_marginleft) .'px !important'; ?>;
                    margin-right: <?php echo esc_attr($single_post_content_marginright).'px !important'; ?>;
                    margin-top: <?php echo esc_attr($single_post_content_margintop).'px'; ?>;
                    margin-bottom: <?php echo esc_attr($single_post_content_marginbottom).'px'; ?>;
                    padding-left: <?php echo esc_attr($single_post_content_paddingleft).'px'; ?>;
                    padding-right: <?php echo esc_attr($single_post_content_paddingright).'px'; ?>;
                    padding-top: <?php echo esc_attr($single_post_content_paddingtop).'px'; ?>;
                    padding-bottom: <?php echo esc_attr($single_post_content_paddingbottom).'px'; ?>;
                }
            <?php
            if ($template_name == "pedal") { ?>
                .bdp_single.pedal .post-meta-cats-tags a,
                .bdp_single.pedal .bdp-date-link {
                    color: <?php echo $linkcolor; ?>;
                    background: <?php echo $templatecolor; ?>;
                    <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                }
                .bdp_single.pedal .bdp-month-link {
                    color: <?php echo $templatecolor; ?>;
                    background: <?php echo $linkcolor; ?>;
                }
                .bdp_single.pedal .bdp_blog_template .post-meta-cats-tags a:hover {
                    color: <?php echo $linkhovercolor; ?>;
                }
                .bdp_single.pedal  {
                    background: <?php echo $template_bgcolor; ?>;
                }
            <?php }
            if ($template_name == "quci") { ?> 
                .bdp_single.quci .post-meta-cats-tags a {
                    color: <?php echo $linkcolor; ?>;
                    border: 1px solid <?php echo $linkcolor; ?>;
                }
                .bdp_single.quci  {
                    background: <?php echo $template_bgcolor; ?>;
                }
            <?php }
            if ($template_name == "boxy-clean") {
                ?>
                    .single_wrap.blog_template .full_wrap,
                    .post-meta .post-comment,
                    .post-meta .postdate,
                    .bdp_single.boxy-clean .author-avatar-div,
                    .bdp_single.boxy-clean .related_post_wrap,
                    .bdp_single.boxy-clean:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.boxy-clean .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.boxy-clean p:not(.has-text-color):not(.wp-block-file__button) a,.relatedpost_title,
                    .bdp_single.boxy-clean .post-navigation .nav-links a:hover .post-title,
                    .bdp_single.boxy-clean .post-navigation .nav-links a:focus .post-title{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.boxy-clean .blog_header h1{
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>;<?php } ?>
                    }
                    .bdp_single.boxy-clean .bdp_blog_template .post_content > p:not(.has-text-color){
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single.boxy-clean .bdp_blog_template .post_content,
                    .bdp_single.boxy-clean .bdp_blog_template .post_content p,
                    .bdp_single.boxy-clean .author_content p{                        
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single.boxy-clean .bdp_blog_template .post_content,
                    .bdp_single.boxy-clean .bdp_blog_template .post_content p:not(.has-text-color):not(.has-large-font-size):not(.wp-block-cover-text),
                    .bdp_single.boxy-clean .author_content p{
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.boxy-clean .bdp_blog_template .post_content,
                    .bdp_single.boxy-clean .bdp_blog_template .post_content:not(.wp-block-quote.is-style-large) p,
                    .bdp_single.boxy-clean .bdp_blog_template .post_content p:not(.has-huge-font-size):not(.has-large-font-size):not(.has-medium-font-size):not(.has-small-font-size):not(.wp-block-cover-text),
                    .bdp_single.boxy-clean .author_content p{
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                    }
                    .bdp_single.boxy-clean .bdp-wrapper-like i,
                    .bdp_single.boxy-clean .author_content .author,
                    .bdp_single.boxy-clean .tags.bdp-no-links,
                    .bdp_single.boxy-clean .tags .link-lable,
                    .bdp_single.boxy-clean .post-meta .post-comment,
                    .bdp_single.boxy-clean .footer_meta .category-link.bdp-no-links,
                    .bdp_single.boxy-clean .footer_meta .category-link .link-lable,
                    .bdp_single .relatedposts .relatedthumb .relatedthumb_content_wrap .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.boxy-clean .tags,
                    .bdp_single.boxy-clean .footer_meta .category-link {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.boxy-clean .post-comment > a {
                        border-color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.boxy-clean .bdp_blog_template.boxy-clean a:hover:not(.has-text-color):not(.wp-block-file__button),
                    .bdp_single .relatedthumb:hover .relatedpost_title,.bdp_single .relatedthumb:hover .relatedpost_title
                    .bdp_single.boxy-clean .post-navigation .nav-links a:focus .post-title,
                    .bdp_single.boxy-clean .post-navigation .nav-links a:hover .post-title {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.boxy-clean .author.box_author {
                        background: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.boxy-clean .navigation.post-navigation .post-data span.navi-post-title, 
                    .bdp_single.boxy-clean .navigation.post-navigation .post-data span.navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.boxy-clean .post-meta .post-comment, .bdp_single.boxy-clean .post-meta .postdate{
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.boxy-clean .navigation.post-navigation .post-previous a.prev-link:hover span.left_nav,
                    .bdp_single.boxy-clean .navigation.post-navigation .post-previous a.prev-link:hover span.navi-post-title,
                    .bdp_single.boxy-clean .navigation.post-navigation .post-next a.next-link:hover span.right_nav,
                    .bdp_single.boxy-clean .navigation.post-navigation .post-next a.next-link:hover span.navi-post-title,
                    .bdp_single.boxy-clean .navigation.post-navigation .post-next a.next-link:hover span.navi-post-text,
                    .bdp_single.boxy-clean .navigation.post-navigation .post-previous a.prev-link:hover span.navi-post-text{
                        color: <?php echo $linkhovercolor; ?>
                    }
                <?php
                if ($display_date == 1 || $display_comment == 1) {
                    ?>
                        .bdp_single.boxy-clean{
                            /*padding-right:80px;*/
                            padding-right:75px;
                        }
                        @media screen and (max-width: 910px) {
                            .bdp_single.boxy-clean {
                                padding-right: 90px;
                            }
                        }
                    <?php
                }
            }
            if ($template_name == "boxy") {
                ?>
                    .bdp_single.boxy .author-avatar,
                    .bdp_single.boxy .post-meta,
                    .post-comment{
                        border-bottom:4px solid <?php echo $templatecolor; ?>;
                    }
                    .post-comment{
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .avtar-img > a {
                        border:4px solid <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.boxy .blog_wrap.blog_template.bdp_blog_template,
                    .single_wrap.blog_template .full_wrap,
                    .post-meta .post-comment,
                    .post-meta .postdate,
                    .bdp_single.boxy .author-avatar-div,
                    .bdp_single.boxy .related_post_wrap,
                    .bdp_single.boxy .navigation.post-navigation,
                    .bdp_single.boxy:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.boxy .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.boxy .footer_meta .category-link.bdp-no-links,
                    .bdp_single.boxy .footer_meta .category-link .link-lable,
                    .bdp_single.boxy .footer_meta .tags.bdp-no-links,
                    .bdp_single.boxy .footer_meta .tags .link-lable,
                    .bdp_single .relatedposts .relatedthumb .related_post_content{
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.boxy .footer_meta .tags,
                    .bdp_single.boxy .footer_meta .category-link {
                        color: <?php echo $contentcolor; ?>;
                    }
                    <?php if (isset($single_data_setting['related_post_content_length']) && $single_data_setting['related_post_content_length'] == 0 && $single_data_setting['related_post_content_length'] == '') { ?>
                        .bdp_single.boxy .related_post_div .relatedthumb > a .relatedpost_title{
                            border-radius: 0 0 5px 5px;
                        }
                    <?php
                }
            }
            if ($template_name == "lightbreeze") {
                ?>
                    .bdp_blog_template.blog_template,
                    .bdp_blog_template .category-main,
                    .bdp_single .navigation.post-navigation .nav-links .nav-previous,
                    .bdp_single .navigation.post-navigation .nav-links .nav-next,
                    .bdp_single.lightbreeze .author-avatar-div,
                    .bdp_single.lightbreeze .related_post_wrap,
                    .bdp_single.lightbreeze:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.lightbreeze .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template .category-main:before,.bdp_blog_template .category-main:after{
                        border-bottom-color: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.lightbreeze #post-nav .post-data .navi-post-title,
                    .bdp_single.lightbreeze #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.lightbreeze #post-nav .post-data .navi-post-title,
                    .bdp_single.lightbreeze #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.lightbreeze #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.lightbreeze #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.lightbreeze #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.lightbreeze #post-nav a.next-link:hover .right_nav,
                    .bdp_single.lightbreeze #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.lightbreeze #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template .metadatabox .category-link.bdp-no-links,
                    .bdp_blog_template .metadatabox .category-link .link-lable,
                    .bdp_single.lightbreeze .meta_data_box,
                    .bdp_blog_template.lightbreeze .tags.bdp-no-links,
                    .bdp_blog_template.lightbreeze .tags i,
                    .bdp_blog_template .metadatabox .metauser,
                    .bdp_single.lightbreeze .bdp_blog_template .share-this,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.lightbreeze .author_content .author {
                        color: <?php echo $titlecolor; ?>;
                    }
                    .bdp_blog_template .metadatabox .metauser .bdp-has-links,
                    .bdp_blog_template.lightbreeze .tags,
                    .bdp_blog_template .metadatabox .category-link,
                    .bdp_blog_template .metauser a,
                    .bdp_blog_template .mdate a,
                    .bdp_blog_template .metacomments a {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "classical") {
                ?>
                    .bdp_single.classical .navigation.post-navigation,
                    .bdp_single.classical .author-avatar-div,
                    .bdp_single.classical .related_post_wrap,
                    .bdp_single.classical .bdp_blog_template.classical,
                    .bdp_single.classical:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.classical .woocommerce-Reviews,
                    .bdp_blog_template.classical .entry-container,
                    .bdp_blog_template.classical .entry-meta {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.classical .entry-meta .up_arrow:after,
                    .bdp_blog_template.classical .entry-meta .up_arrow:before{
                        border-color : rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) <?php echo $template_bgcolor; ?>;
                    }
                <?php if (isset($single_data_setting['template_bgcolor'])) { ?>
                        .bdp_single.classical .navigation.post-navigation{
                            background: transparent;
                        }
                        .bdp_single.classical .related_post_wrap,.bdp_single.classical .comments-area,
                        
                        .bdp_single .navigation.post-navigation .nav-links .nav-previous,.bdp_single .navigation.post-navigation .nav-links .nav-next,.bdp_single.classical .author-avatar-div{
                            background: <?php echo $template_bgcolor; ?>;
                        }
                <?php } ?>
                    .bdp_single .bdp_blog_template.classical a:not(.has-text-color):not(.wp-block-file__button),.bdp_single .navigation.post-navigation .post-data span.navi-post-title,
                    .bdp_single .navigation.post-navigation .post-data span.navi-post-text,
                    .bdp_single .bdp_blog_template .social-component a{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.classical a:hover:not(.has-text-color):not(.wp-block-file__button),.bdp_single .navigation.post-navigation .post-data span.navi-post-title:hover,
                    .bdp_single .navigation.post-navigation .post-data span.navi-post-text:hover{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.classical .blog_header h1 {
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>;<?php } ?>
                        color: <?php echo $titlecolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.classical .post_content > p:not(.has-text-color) {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single .bdp_blog_template.classical .post_content,
                    .bdp_single .bdp_blog_template.classical .post_content p:not(.has-text-color):not(.has-large-font-size):not(.wp-block-cover-text){
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.classical .post_content,
                    .bdp_single .bdp_blog_template.classical .post_content p {                        
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single .bdp_blog_template.classical .post_content,
                    .bdp_single .bdp_blog_template.classical .post_content blockquote:not(.wp-block-quote.is-style-large) p,
                    .bdp_single .bdp_blog_template.classical .post_content p:not(.has-large-font-size):not(.wp-block-cover-text){
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                    }
                    .bdp_blog_template.classical .post-meta-cats-tags .tags .link-lable,
                    .bdp_blog_template.classical .post-meta-cats-tags .category-link .link-lable,
                    .bdp_blog_template.classical .post-meta-cats-tags .category-link.bdp-no-links,
                    .bdp_blog_template.classical .metadatabox,
                    .bdp_blog_template.classical .link-lable,
                    .bdp_single .relatedposts .relatedthumb .related_post_content    {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_blog_template.classical .metadatabox span.bdp-has-links,
                    .bdp_blog_template.classical .post-meta-cats-tags .tags,
                    .bdp_blog_template.classical .post-meta-cats-tags .category-link {
                        color: <?php echo $linkcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>;
                    }

                    .bdp_single.classical .navigation.post-navigation{
                        border: medium none;
                    }

                <?php
            }
            if ($template_name == "nicy") {
                ?>
                    .bdp_blog_template.nicy .entry-container,
                    .bdp_blog_template.nicy .entry-meta,
                    .bdp_single.nicy .navigation.post-navigation,
                    .bdp_single.nicy .author-avatar-div,
                    .bdp_single.nicy .related_post_wrap {
                        background: none repeat scroll 0 0 <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.nicy .post-meta-cats-tags .tags.bdp-no-links,
                    .bdp_blog_template.nicy .post-meta-cats-tags .tags .link-lable,
                    .bdp_blog_template.nicy .post-meta-cats-tags .category-link.bdp-no-links,
                    .bdp_blog_template.nicy .post-meta-cats-tags .category-link .link-lable {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_blog_template.nicy .metadatabox {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.nicy .post-meta-cats-tags .tags,
                    .bdp_blog_template.nicy .post-meta-cats-tags .category-link {
                        color: <?php echo $linkcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>;
                    }
                <?php
            }
            if ($template_name == "winter") {
                ?>
                    .bdp_single.winter .bdp_blog_template .posted_by.bdp_has_links,
                    .bdp_single.winter .bdp_blog_template .tags.bdp_has_links {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.winter .bdp-post-image .category-link {
                        background-color:<?php echo $winter_category_color; ?>;
                    }
                    .bdp_single.winter .bdp-post-image .category-link:before {
                        border-right: 10px solid <?php echo $winter_category_color; ?>;
                    }
                    .bdp_single.winter a:not(.wp-block-button__link.has-text-color),
                    .relatedpost_title,
                    .bdp_single.winter .post-navigation .nav-links a .post-title,
                    .bdp_single.winter .post-navigation .nav-links a .post-title{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.winter a:hover ,.relatedthumb:hover .relatedpost_title,
                    .bdp_single.winter .post-navigation .nav-links a:hover .post-title,
                    .bdp_single.winter .post-navigation .nav-links a:focus .post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.winter #post-nav .post-data .navi-post-title,
                    .bdp_single.winter #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.winter #post-nav .post-data .navi-post-title,
                    .bdp_single.winter #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.winter #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.winter #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.winter #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.winter #post-nav a.next-link:hover .right_nav,
                    .bdp_single.winter #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.winter #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.winter .posted_by,
                    .bdp_single.winter .posted_by .link-lable,
                    .bdp_single .relatedposts .relatedthumb .related_post_content, .datetime{
                        color: <?php echo $contentcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "sharpen") {
                ?>
                    .bdp_single.sharpen .meta_data_box div.bdp_has_links,
                    .bdp_single.sharpen .category-list.bdp_has_links {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.sharpen .meta_data_box div,
                    .bdp_single.sharpen .meta_data_box div .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.sharpen .box-template.sharpen,
                    .bdp_single.sharpen .navigation.post-navigation.bdp-post-navigation,
                    .bdp_single.sharpen .author-avatar-div.bdp_blog_template,
                    .bdp_single.sharpen .related_post_wrap,
                    .bdp_single.sharpen:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.sharpen .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                   
                <?php
            }
            if ($template_name == "spektrum") {
                ?>
                    .bdp_single.spektrum .bdp_blog_template.spektrum,
                    .bdp_single.spektrum .navigation.post-navigation.bdp-post-navigation,
                    .bdp_single.spektrum .author-avatar-div.bdp_blog_template,
                    .bdp_single.spektrum .related_post_wrap,
                    .bdp_single.spektrum:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.spektrum .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.spektrum a.date{
                        background: <?php echo $titlecolor; ?>
                    }
                    .bdp_single.spektrum .author_content .author a:before{
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.spektrum .post-bottom > span.bdp_has_links,
                    .bdp_single.spektrum .author_content .author a{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.spektrum .author_content .author a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.spektrum #post-nav .post-data .navi-post-title,
                    .bdp_single.spektrum #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.spektrum #post-nav .post-data .navi-post-title,
                    .bdp_single.spektrum #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.spektrum #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.spektrum #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.spektrum #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.spektrum #post-nav a.next-link:hover .right_nav,
                    .bdp_single.spektrum #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.spektrum #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.spektrum .author-avatar-div .author_content .author a:hover:before{
                        background: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template.spektrum .post-bottom > span .link-lable,
                    .post-bottom > span,
                    .bdp_single .relatedposts .relatedthumb .related_post_content,
                    .bdp_single.spektrum .author_content > p{
                        color: <?php echo $contentcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "media-grid") {
                ?>
                   
                    .bdp_blog_template.media-grid .category-link{
                        background-color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.media-grid h1.entry-title{
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>;<?php } ?>
                    }
                    .bdp_blog_template.media-grid .content-inner {
                        background-color: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.media-grid .author-avatar-div .avtar-img a:before{
                        background-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_blog_template.media-grid .entry-meta .metabox-upper > span .post-author.bdp-no-links,
                    .bdp_blog_template.media-grid .entry-meta .metabox-upper > span .post-author .link-lable,
                    .bdp_blog_template.media-grid .metadatabox .tags i,
                    .bdp_blog_template.media-grid .metacomments i,
                    .bdp_blog_template.media-grid .metadatabox .tags.bdp-no-links {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.media-grid .entry-meta .metabox-upper > span .post-author,
                    .bdp_blog_template.media-grid .metadatabox .tags {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "deport") {
                ?>
                    .bdp_single.deport {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.deport .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template.deport h1.post-title{
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    }
                    .bdp_single.deport .bdp_single-meta-line{
                        background: <?php echo $templatecolor; ?>;
                    }
                    .deport .metadatabox span.dot-separater{
                        color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.deport .tags.bdp-no-links, 
                    .bdp_single.deport .tags i, 
                    .bdp_single.deport .category-links i,
                    .bdp_single.deport .category-links.bdp-no-links,
                    .bdp_single .bdp_blog_template.deport .metadatabox .single-metadatabox,
                    .bdp_single .bdp_blog_template.deport .metadatabox .single-metadatabox .post-author.bdp-no-links,
                    .bdp_single .bdp_blog_template.deport .metadatabox .single-metadatabox .post-author .link-lable,
                    .bdp_single.deport .metadatabox > span,
                    .bdp_single .relatedposts .relatedthumb .related_post_content,
                    .bdp_single .tags .link-lable  {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.deport .metadatabox .single-metadatabox .post-author,
                    .bdp_single.deport .tags, 
                    .bdp_single.deport .category-links {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "navia") {
                ?>
                    .bdp_single.navia .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.navia .blog_template.navia,
                    .bdp_single .navigation.post-navigation .nav-links .nav-previous,
                    .bdp_single .navigation.post-navigation .nav-links .nav-next,
                    .bdp_single.navia .author-avatar-div,
                    .bdp_single.navia .related_post_wrap,
                    .bdp_single.navia:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.navia .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.navia h1.post-title{
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?>font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    }
                    .bdp_single.navia #post-nav .post-data .navi-post-title,
                    .bdp_single.navia #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.navia #post-nav .post-data .navi-post-title,
                    .bdp_single.navia #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.navia #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.navia #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.navia #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.navia #post-nav a.next-link:hover .right_nav,
                    .bdp_single.navia #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.navia #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.navia .author-avatar-div .author_content .author a:before{
                        background: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.navia .author-avatar-div .author_content .author a:hover:before{
                        background: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.navia .post-metadata .post-author.bdp-no-links,
                    .bdp_single.navia .post-metadata .post-author .link-lable,
                    .bdp_single.navia .post-metadata .bdp_date_category_comment .post-category.bdp-no-links,
                    .bdp_single.navia .post-metadata .bdp_date_category_comment .post-category .link-lable,
                    .bdp_single.navia .post-content-area .tags,
                    .bdp_single .relatedposts .relatedthumb .related_post_content,
                    .bdp_single.navia .bdp_date_category_comment {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    }
                    .bdp_single.navia .post-metadata .post-author,
                    .bdp_single .post-metadata .bdp_date_category_comment > span a,
                    .bdp_single.navia .post-metadata .bdp_date_category_comment .post-category,
                     .bdp_single.navia .post-metadata .bdp_date_category_comment .post-category a {
                        color: <?php echo $linkcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    }
                    .bdp_single .post-metadata .bdp_date_category_comment > span a:hover,
                    .bdp_single.navia .post-metadata .bdp_date_category_comment .post-category a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }
            if ($template_name == "news") {
                ?>
                    .bdp_single.news .bdp_blog_template.news,
                    .bdp_single .navigation.post-navigation .nav-links .previous-post,
                    .bdp_single .navigation.post-navigation .nav-links .next-post,
                    .bdp_single.news .author_div,
                    .bdp_single.news .related_post_wrap,
                    .bdp_single.news .comment-form,
                    .bdp_single.news:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.news .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.news h1.post-title {
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    }
                    .bdp_single.news .post-content-div .post-content,
                    .bdp_single.news .post-content-div .post-content p {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single.news .related_post_wrap h3,
                    .bdp_single.news .author_content .author,
                    .bdp_single.news .author_div li.active {
                        color: <?php echo $titlecolor; ?>;
                    }
                    .bdp_single.news #post-nav .post-data .navi-post-title,
                    .bdp_single.news #post-nav .post-data .navi-post-text {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.news #post-nav .post-data .navi-post-title,
                    .bdp_single.news #post-nav .post-data .navi-post-text {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.news #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.news #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.news #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.news #post-nav a.next-link:hover .right_nav,
                    .bdp_single.news #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.news #post-nav a.next-link:hover .post-data span.navi-post-title {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.news .author_content .author,
                    .bdp_single.news .author_content .author a {
                        font-size: <?php echo $authorTitleSize . 'px'; ?>;
                        <?php if ($authorTitleFace) { ?> font-family: <?php echo $authorTitleFace; ?>; <?php } ?>
                    }
                    .bdp_single.news .post-category.bdp-no-links,
                    .bdp_single.news .post-category i,
                    .bdp_single.news .tags.bdp-no-links,
                    .bdp_single.news .tags i,
                    .bdp_single.news .post_meta,
                    .bdp_single.news .relatedposts .relatedthumb .related_post_content,
                    .bdp_single.news .tag_cat {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.news .tags,
                    .bdp_single.news .post-category,
                    .bdp_single.news .post_meta .post-author.bdp-has-links {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "region") {
                ?>
                    .bdp_single.region .navigation.post-navigation .nav-links > div a span.screen-reader-text:hover{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.region #post-nav .post-data .navi-post-title,
                    .bdp_single.region #post-nav .post-data .navi-post-text,
                    .bdp_single.region .navigation.post-navigation .post-data .navi-post-date{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.region #post-nav .post-data .navi-post-title,
                    .bdp_single.region #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.region #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.region #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.region #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.region #post-nav a.next-link:hover .right_nav,
                    .bdp_single.region #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.region #post-nav a.next-link:hover .post-data span.navi-post-title,
                    .bdp_single.region .navigation.post-navigation a:hover .post-data .navi-post-date{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.region .blog_footer.footer_meta .category-link.bdp-no-links,
                    .bdp_single.region .blog_footer.footer_meta .category-link .link-lable,
                    .bdp_single.region .blog_footer.footer_meta .tags.bdp-no-links,
                    .bdp_single.region .blog_footer.footer_meta .tags .link-lable,
                    .bdp_single.region .posted_by, .bdp_single .relatedposts .relatedthumb .related_post_content, .article_comments {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.region .posted_by .bdp-has-links,
                    .bdp_single.region .blog_footer.footer_meta .category-link,
                    .bdp_single.region .blog_footer.footer_meta .tags {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .blog_template.bdp_blog_template.region {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "glossary") {
                ?>
                    .bdp_single.glossary .blog_header .posted_by a:hover time,
                    .bdp_single.glossary .related_post_div .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.glossary .blog_header .posted_by a time,
                    .bdp_single.glossary #post-nav .post-data .navi-post-title,
                    .bdp_single.glossary #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.glossary #post-nav .post-data .navi-post-title,
                    .bdp_single.glossary #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.glossary #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.glossary #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.glossary #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.glossary #post-nav a.next-link:hover .right_nav,
                    .bdp_single.glossary #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.glossary #post-nav a.next-link:hover .post-data span.navi-post-title,
                    .bdp_single.glossary .nav-links a:hover span.navi-post-date{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single .blog_item .blog_footer .category-link.bdp-no-links,
                    .bdp_single .blog_item .blog_footer .category-link .link-lable,
                    .bdp_single .blog_item .blog_footer .tags.bdp-no-links,
                    .bdp_single .blog_item .blog_footer .tags .link-lable,
                    .bdp_single.glossary .posted_by .post-author.bdp-no-links,
                    .bdp_single.glossary .posted_by .post-author .link-lable,
                    .bdp_single.glossary .posted_by .metacomments,
                    .bdp_single.glossary .posted_by .datetime,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_single.glossary .blog_item .blog_footer .share-this {
                        color: <?php echo $titlecolor; ?>;
                    }
                    .bdp_single.glossary .posted_by .post-author,
                    .bdp_single .blog_item .blog_footer .tags,
                    .bdp_single .blog_item .blog_footer .category-link,
                    .bdp_single .post-author a,
                    .bdp_single .metacomments a{
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "offer_blog") {
                ?>
                    .bdp_single.offer_blog .related_post_div .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.offer_blog .bdp_blog_template.offer_blog,
                    .bdp_single.offer_blog .related_post_wrap,
                    .bdp_single.offer_blog .bdp-post-navigation,
                    .bdp_single.offer_blog .author_div.bdp_blog_template,
                    .bdp_single.offer_blog:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.offer_blog .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.offer_blog #post-nav .post-data .navi-post-title,
                    .bdp_single.offer_blog #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.offer_blog #post-nav .post-data .navi-post-title,
                    .bdp_single.offer_blog #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.offer_blog #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.offer_blog #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.offer_blog #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.offer_blog #post-nav a.next-link:hover .right_nav,
                    .bdp_single.offer_blog #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.offer_blog #post-nav a.next-link:hover .post-data span.navi-post-title,
                    .bdp_single.offer_blog .nav-links a:hover span.navi-post-date{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single .offer_blog.bdp_blog_template span.date,
                    .bdp_single .offer_blog.bdp_blog_template span.author,
                    .bdp_single .offer_blog.bdp_blog_template span.post-category i,
                    .bdp_single .offer_blog.bdp_blog_template span.post-category.bdp-no-links,
                    .bdp_single .offer_blog.bdp_blog_template span.comment,
                    .bdp_single .offer_blog.bdp_blog_template span.tags,
                    .bdp_single .relatedposts .relatedthumb .related_post_content{
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single .offer_blog.bdp_blog_template span.author .bdp-has-links,
                    .bdp_single .offer_blog.bdp_blog_template span.post-category {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.offer_blog .bdp_blog_template .share-this {
                        color: <?php echo $titlecolor; ?>;
                    }
                    .offer_blog.bdp_blog_template .tags a {
                        background-color: <?php echo $template_bgcolor; ?>;
                        border-color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "timeline") {
                ?>
                    .bdp_single.timeline .datetime{
                        background: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.timeline .related_post_wrap,
                    .bdp_single.timeline .bdp-post-navigation,
                    .bdp_single.timeline .author_div.bdp_blog_template,
                    .bdp_single .bdp_blog_template.timeline .post_content_wrap,
                    .bdp_single.timeline:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.timeline .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single .bdp_blog_template.timeline .post_content_wrap,
                    .bdp_single .bdp_blog_template.timeline .post_content_wrap .blog_footer,
                    .bdp_single.timeline .navigation.post-navigation,
                    .bdp_single.timeline:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.timeline .woocommerce-Reviews {
                        border-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.timeline .related_post_div .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.timeline .author_div,.bdp_single.timeline .related_post_wrap{
                        border: 1px solid <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.timeline #post-nav .post-data .navi-post-title,
                    .bdp_single.timeline #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.timeline #post-nav .post-data .navi-post-title,
                    .bdp_single.timeline #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.timeline #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.timeline #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.timeline #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.timeline #post-nav a.next-link:hover .right_nav,
                    .bdp_single.timeline #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.timeline #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.timeline .author_div .author {
                        font-size: <?php echo $authorTitleSize . 'px'; ?>;
                        <?php if ($authorTitleFace) { ?>font-family: <?php echo $authorTitleFace; ?>; <?php } ?>
                        line-height: 1.5;
                    }
                    .bdp_single.timeline .blog_footer span,
                    .bdp_single.timeline .blog_footer span.comments-link,
                    .bdp_single.timeline .blog_footer span.tags,
                    .bdp_single.timeline .blog_footer span.categories,
                    .bdp_single.timeline .blog_footer span.categories .link-lable,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.timeline .blog_footer span.tags.bdp_has_links,
                    .bdp_single.timeline .blog_footer span.posted_by.bdp_has_links,
                    .bdp_single.timeline .blog_footer span.categories.bdp_has_links {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "brit_co") {
                ?>  
                    .bdp_single.brit_co .navigation.post-navigation,
                    .bdp_single.brit_co .author-avatar-div,
                    .bdp_single.brit_co .related_post_wrap,
                    .bdp_single.brit_co .bdp_blog_template.britco,
                    .bdp_single.brit_co:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.brit_co .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.brit_co .post-navigation .nav-links > div > a span.screen-reader-text,
                    .bdp_single .navigation.post-navigation .nav-links a .post-data span.navi-post-title {
                        color : <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.brit_co .post-navigation .nav-links > div > a:hover span.screen-reader-text,
                    .bdp_single .navigation.post-navigation .nav-links a:hover .post-data span.navi-post-title  {
                        color : <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.brit_co a.prev-link:hover span.left_nav, .bdp_single.brit_co a.next-link:hover span.right_nav{
                        color : <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template.britco .metadatabox .tags.bdp-no-links,
                    .bdp_blog_template.britco .metadatabox .tags .link-lable,
                    .bdp_blog_template.britco .metadatabox .post-category.bdp-no-links,
                    .bdp_blog_template.britco .metadatabox .post-category .link-lable,
                    .bdp_single.brit_co .metadatabox,
                    .bdp_single.brit_co .metadatabox .metauser .link-lable,
                    .bdp_single.brit_co .metadatabox .metauser.bdp-no-links,
                    .bdp_single.brit_co .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.brit_co .metadatabox .metauser,
                    .bdp_blog_template.britco .metadatabox .tags,
                    .bdp_blog_template.britco .metadatabox .post-category {
                        color : <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.brit_co .navigation.post-navigation .nav-links .nav-previous, .bdp_single.brit_co .navigation.post-navigation .nav-links .nav-next {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "evolution") {
                ?>
                    .bdp_single.evolution .navigation.post-navigation,
                    .bdp_single.evolution .author-avatar-div,
                    .bdp_single.evolution .related_post_wrap,
                    .bdp_single.evolution .bdp_blog_template.evolution,
                    .bdp_single.evolution:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.evolution .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.evolution .post-category.bdp-no-links,
                    .bdp_single.evolution .tags.bdp-no-links,
                    .bdp_single.evolution .tags i,
                    .bdp_single.evolution .date,
                    .bdp_single.evolution .author.bdp-no-links,
                    .bdp_single.evolution .author i,
                    .bdp_single.evolution .author .link-lable,
                    .bdp_single.evolution .comment,
                    .bdp_single.evolution .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.evolution .author-avatar-div:before,
                    .bdp_single.evolution .author-avatar-div:after{
                        background: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.evolution .author,
                    .bdp_single.evolution .post-category,
                    .bdp_single.evolution .tags,
                    .bdp_single.evolution .nav-links a .navi-post-title {
                        color : <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.evolution .nav-links a:hover .navi-post-title {
                        color : <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }
            if ($template_name == "invert-grid") {
                ?>
                    .bdp_single.invert-grid .category-link{
                        background: <?php echo $templatecolor; ?>;
                        color: white;
                    }
                    .bdp_single.invert-grid .seperater {
                        color: #fff;
                    }
                    .bdp_single.invert-grid .bdp_blog_template.invert-grid,
                    .bdp_single.invert-grid .navigation.post-navigation.bdp-post-navigation,
                    .bdp_single.invert-grid .author-avatar-div.bdp_blog_template,
                    .bdp_single.invert-grid .related_post_wrap,
                    .bdp_single.invert-grid:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.invert-grid .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.invert-grid .metadatabox .mdate {
                        background: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.invert-grid .metadatabox .mdate a,
                    .bdp_single.invert-grid .metadatabox .mdate a:hover {
                        color: #ffffff;
                    }
                    .bdp_single.invert-grid #post-nav .post-data .navi-post-title,
                    .bdp_single.invert-grid #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.invert-grid #post-nav .post-data .navi-post-title,
                    .bdp_single.invert-grid #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.invert-grid #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.invert-grid #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.invert-grid #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.invert-grid #post-nav a.next-link:hover .right_nav,
                    .bdp_single.invert-grid #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.invert-grid #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.invert-grid .metadatabox,
                    .bdp_single.invert-grid .tags.bdp-has-links,
                    .bdp_single.invert-grid .tags .link-lable,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.invert-grid .metadatabox .post-author.bdp-has-links,
                    .bdp_single.invert-grid .tags {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .metadatabox .mdate span.mdate-month {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                    }
                    .metadatabox .mdate span.mdate-day {
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    }
                <?php
            }
            if ($template_name == "story") {
                ?>
                    .bdp_single.story .relatedposts .relatedthumb .related_post_content{
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    <?php if ($content_fontface) { ?>
                        .story .author_content .author{
                            font-family: <?php echo $content_fontface; ?>;
                        }
                    <?php } ?>
                    .story .line-col-top{
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .story .date-icon-left{
                        background: <?php echo $templatecolor; ?>
                    }
                    .story .author_content .author,
                    .story .bdp-wrapper-like i,
                    .story .tags,
                    .story .tags .link-lable,
                    .story .post-metadata,
                    .story .categories,
                    .story .categories .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .story .tags.bdp_has_links,
                    .story .categories.bdp_has_links,
                    .story .post-metadata span.bdp_has_links {
                        color: <?php echo $linkcolor; ?>
                    }
                    .story .date-icon-arrow-bottom:before{
                        border-top-color: <?php echo $templatecolor; ?>;
                    }
                    .startup{
                        background: <?php echo $story_startup_background; ?>;
                        color: <?php echo $story_startup_text_color; ?>;
                    }
                <?php
            }
            if ($template_name == "easy_timeline") {
                ?>
                    .bdp_single.easy_timeline .post_hentry .post_content_wrap,
                    .bdp_single.easy_timeline .author_div,
                    .bdp_single.easy_timeline .related_post_wrap,
                    .bdp_single.easy_timeline .navigation.post-navigation  {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.easy_timeline .related_post_div .relatedthumb:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.easy_timeline #post-nav .post-data .navi-post-title,
                    .bdp_single.easy_timeline #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.easy_timeline #post-nav .post-data .navi-post-title,
                    .bdp_single.easy_timeline #post-nav .post-data .navi-post-text{
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.easy_timeline #post-nav a.prev-link:hover .left_nav,
                    .bdp_single.easy_timeline #post-nav a.prev-link:hover .post-data span.navi-post-text,
                    .bdp_single.easy_timeline #post-nav a.prev-link:hover .post-data span.navi-post-title,
                    .bdp_single.easy_timeline #post-nav a.next-link:hover .right_nav,
                    .bdp_single.easy_timeline #post-nav a.next-link:hover .post-data span.navi-post-text,
                    .bdp_single.easy_timeline #post-nav a.next-link:hover .post-data span.navi-post-title{
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.easy_timeline .author_div .author {
                        font-size: <?php echo $authorTitleSize . 'px'; ?>;
                        <?php if ($authorTitleFace) { ?> font-family: <?php echo $authorTitleFace; ?>; <?php } ?>
                        line-height: 1.5;
                    }
                    .bdp_single.easy_timeline .blog_footer > span,
                    .bdp_single.easy_timeline .blog_footer span.comments-link,
                    .bdp_single.easy_timeline .blog_footer span.tags.bdp-no-links,
                    .bdp_single.easy_timeline .blog_footer span.tags i,
                    .bdp_single.easy_timeline .easy_timeline_auth_date .posted_by.bdp-no-links,
                    .bdp_single.bdp_single_product.easy_timeline .easy_timeline_auth_date .posted_by,
                    .bdp_single.easy_timeline .easy_timeline_auth_date .posted_by i,
                    .bdp_single.easy_timeline .blog_footer span.categories.bdp-no-links,
                    .bdp_single.easy_timeline .blog_footer span.categories i,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.easy_timeline .easy_timeline_auth_date .posted_by,
                    .bdp_single.easy_timeline .blog_footer span.tags,
                    .bdp_single.easy_timeline .blog_footer span.categories {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.easy_timeline .reply a.comment-reply-link {
                        background: <?php echo $titlecolor; ?>;
                    }
                    .bdp_single.easy_timeline .reply a.comment-reply-link:hover {
                        background: #fff;
                    }
                    .bdp_single.easy_timeline .easy_timeline_auth_date,
                    .bdp_single.easy_timeline .easy_timeline_comment,
                    .bdp_single.easy_timeline .desc,
                    .bdp_single.easy_timeline .blog_footer,
                    .bdp_single.easy_timeline .author_div,
                    .bdp_single.easy_timeline .post-navigation,
                    .bdp_single.easy_timeline .related_post_wrap,
                    .bdp_single.easy_timeline .comments-area{
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                        font-size: <?php echo $content_fontsize; ?>px;
                        color: <?php echo $contentcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "cool_horizontal") {
                ?>
                    .photo.bdp-post-image {
                        background: <?php echo Bdp_Utility::hex2rgba($templatecolor, 0.5); ?> none repeat scroll 0 0;
                    }
                    .bdp_single.cool_horizontal:before {
                        background: <?php echo $templatecolor; ?>;
                    }
                    <?php if ($template_titlefontface) { ?>
                        .bdp_single.cool_horizontal .author_div .author_content .author{
                            font-family: <?php echo $template_titlefontface; ?>;
                        }
                    <?php } ?>
                    .bdp_single.cool_horizontal .date-dot,
                    .bdp_single.cool_horizontal .comment-list:before,
                    .bdp_single.cool_horizontal .avtar-img:before {
                        border: 2px solid <?php echo $templatecolor; ?>;
                    }
                    body.single .site-content .bdp_single.cool_horizontal .comments-area,
                    .bdp_single .bdp_blog_template.cool_horizontal .post_content_wrap .blog_footer,
                    .bdp_single .bdp_blog_template.cool_horizontal pre code,
                    .bdp_single .bdp_blog_template.cool_horizontal .datetime,
                    .bdp_single.cool_horizontal .comment-author,
                    .bdp_single.cool_horizontal .bdp-wrapper-like i,
                    .bdp_single.cool_horizontal .author_content .author,
                    .bdp_single.cool_horizontal .comment-form label,
                    .bdp_single.cool_horizontal .relatedposts .relatedthumb .related_post_content,
                    .comment-form label {
                        color : <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                        font-size: <?php echo $content_fontsize; ?>px;
                    }
                    .bdp_single.cool_horizontal input[type="text"],
                    .bdp_single.cool_horizontal input[type="date"],
                    .bdp_single.cool_horizontal input[type="time"],
                    .bdp_single.cool_horizontal input[type="datetime-local"],
                    .bdp_single.cool_horizontal input[type="week"],
                    .bdp_single.cool_horizontal input[type="month"], input[type="text"],
                    .bdp_single.cool_horizontal input[type="email"],
                    .bdp_single.cool_horizontal input[type="url"],
                    .bdp_single.cool_horizontal input[type="password"],
                    .bdp_single.cool_horizontal input[type="search"],
                    .bdp_single.cool_horizontal input[type="tel"],
                    .bdp_single.cool_horizontal input[type="number"],
                    .bdp_single.cool_horizontal textarea {
                        color : <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.cool_horizontal .blog_footer span .posted_by.bdp-no-links,
                    .bdp_single.cool_horizontal .blog_footer span .posted_by i,
                    .bdp_single.cool_horizontal .blog_footer span .tags.bdp-no-links,
                    .bdp_single.cool_horizontal .blog_footer span .tags i,
                    .bdp_single.cool_horizontal .blog_footer span .categories.bdp-no-links,
                    .bdp_single.cool_horizontal .blog_footer span .categories i {
                        color : <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.cool_horizontal .blog_footer span .posted_by,
                    .bdp_single.cool_horizontal .blog_footer span .tags,
                    .bdp_single.cool_horizontal .blog_footer span .categories {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "overlay_horizontal") {
                ?>
                    .bdp_single.overlay_horizontal *,
                    .bdp_single.overlay_horizontal .author_div .author_content span.author,
                    .bdp_single.overlay_horizontal input[type="text"],
                    .bdp_single.overlay_horizontal input[type="date"],
                    .bdp_single.overlay_horizontal input[type="time"],
                    .bdp_single.overlay_horizontal input[type="datetime-local"],
                    .bdp_single.overlay_horizontal input[type="week"],
                    .bdp_single.overlay_horizontal input[type="month"], input[type="text"],
                    .bdp_single.overlay_horizontal input[type="email"],
                    .bdp_single.overlay_horizontal input[type="url"],
                    .bdp_single.overlay_horizontal input[type="password"],
                    .bdp_single.overlay_horizontal input[type="search"],
                    .bdp_single.overlay_horizontal input[type="tel"],
                    .bdp_single.overlay_horizontal input[type="number"],
                    .bdp_single.overlay_horizontal textarea {
                        color : <?php echo $contentcolor; ?>;
                    }
                    body.single .site-content .bdp_single.overlay_horizontal .comments-area,
                    .bdp_single .bdp_blog_template.overlay_horizontal .post_content_wrap .blog_footer,
                    .bdp_single .bdp_blog_template.overlay_horizontal pre code,
                    .bdp_single.overlay_horizontal .comment-author,
                    .bdp_single.overlay_horizontal .comment-form label,
                    .bdp_single.overlay_horizontal .relatedposts .relatedthumb .related_post_content,
                    .comment-form label {
                        color : <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                        font-size: <?php echo $content_fontsize; ?>px;
                    }
                    .horizontal2-cover {
                        background: <?php echo Bdp_Utility::hex2rgba($template_bgcolor, '0.65'); ?>;
                    }
                    .overlay_horizontal pre {
                        background-color: <?php echo Bdp_Utility::hex2rgba($contentcolor, 0.1); ?>;
                    }
                    .bdp-date-meta > a:hover .month,
                    .bdp-wrapper-like > .bdp-like-button:hover .bdp-count,
                    .bdp-wrapper-like > .bdp-like-button:hover i,
                    .relatedthumb > a:hover .relatedpost_title{
                        color: <?php echo $linkhovercolor; ?> !important;
                    }
                    .author_content .author {
                        <?php if ($authorTitleFace) { ?>font-family: <?php echo $authorTitleFace; ?>; <?php } ?>
                    }
                <?php
            }
            if ($template_name == "explore") {
                ?>
                    .bdp_single.explore .bdp_blog_template.explore_wrapper,
                    .bdp_single.explore .author-avatar-div,
                    .bdp_single.explore .related_post_wrap,
                    .bdp_single.explore .navigation.post-navigation  {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.explore .mdate a {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php if (isset($single_data_setting['firstletter_big']) && $single_data_setting['firstletter_big'] == 1) { ?>
                        .bdp_single .bdp_blog_template .post_content.entry-content > p:nth-child(2):first-letter{
                            font-size:<?php echo $firstletter_fontsize . 'px'; ?>;
                            color: <?php echo $firstletter_contentcolor; ?>;
                            <?php if ($firstletter_contentfontface) { ?>font-family:<?php echo $firstletter_contentfontface; ?>; <?php } ?>
                            float: left;
                            margin-right:10px;
                            <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                            <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                            <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                            <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                            <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                            <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                        }
                <?php } ?>
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "my_diary") {
                ?>
                    .blog_template.my_diary_wrapper,
                    .my_diary_wrapper .my_diary_wrapper,
                    .my_diary_wrapper .navigation.post-navigation.bdp-post-navigation,
                    .my_diary_wrapper .author-avatar-div.bdp_blog_template,
                    .my_diary_wrapper .related_post_wrap,
                    .my_diary_wrapper #reviews,
                    .my_diary_wrapper .comment-form,
                    .bdp_single.my_diary:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.my_diary .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .single_blog_wrapper .my_diary_wrapper .post-comment i,
                    .single_blog_wrapper .my_diary_wrapper .post-content-area .tags,
                    .single_blog_wrapper .my_diary_wrapper .post-content-area .tags a {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .single_blog_wrapper .my_diary_wrapper .post-content-area .tags a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .my_diary.bdp_single .my_diary_wrapper .blog_header h1.post-title {
                        font-size: <?php echo $template_titlefontsize; ?>px;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                        color: <?php echo $titlecolor; ?>;
                    }
                <?php
            }
            if ($template_name == "elina") {
                ?>
                    .elina.bdp_single .bdp_blog_template.elina-post-wrapper,
                    .bdp_single.elina .author-avatar-div,
                    .bdp_single.elina .related_post_wrap,
                    .nav-links .previous-post,
                    .nav-links .next-post,
                    .bdp_single.elina:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.elina .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .elina.bdp_single .blog_header h1.post-title {
                        font-size: <?php echo $template_titlefontsize; ?>px;
                        <?php if ($template_titlefontface) { ?>font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                        color: <?php echo $titlecolor; ?>;
                    }
                    .elina.bdp_single .elina-post-wrapper .bdp-wrapper-like i,
                    .elina.bdp_single .elina-post-wrapper .tags.bdp-no-links,
                    .elina.bdp_single .elina-post-wrapper .tags .link-lable,
                    .elina.bdp_single .elina-post-wrapper .metadatabox,
                    .elina.bdp_single .elina-post-wrapper .categories.bdp-no-links,
                    .bdp_single .relatedposts .relatedthumb .related_post_content {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .elina.bdp_single .elina-post-wrapper .metadatabox .bdp-has-links,
                    .elina.bdp_single .elina-post-wrapper .tags,
                    .elina.bdp_single .elina-post-wrapper .categories,
                    .elina.bdp_single .elina-post-wrapper .metadatabox a {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "masonry_timeline") {
                ?>
                    .masonry_timeline .masonry-timeline-wrapp,
                    .masonry_timeline.bdp_single .navigation.post-navigation,
                    .bdp_single.masonry_timeline .related_post_wrap,
                    .masonry_timeline .author-avatar-div,
                    .masonry-timeline-wrapp.bdp_blog_template .social-component,
                    .bdp_single.masonry_timeline:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.masonry_timeline .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .masonry_timeline .post-content-area .categories.bdp-no-links,
                    .bdp_single .relatedposts .relatedthumb .related_post_content, 
                    .post-content-area .categories, .post-footer .metadatabox, 
                    .masonry_timeline .post-footer .metadatabox > span.mauthor.bdp-no-links,
                    .masonry_timeline .post-footer .metadatabox > span.mauthor i,
                    .masonry_timeline .tags.bdp-has-links,
                    .masonry_timeline .tags .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .masonry_timeline .post-footer .metadatabox > span.mauthor,
                    .masonry_timeline .tags,
                    .masonry_timeline .post-content-area .categories,
                    .masonry_timeline .mauthor a,
                    .masonry_timeline .mdate a,
                    .masonry_timeline .post-comment a{
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "brite") {
                ?>
                
                    .bdp_single.brite .bdp_blog_template.brite,
                    .bdp_single.brite .author-avatar-div,
                    .bdp_single.brite .navigation.post-navigation,
                    .bdp_single.brite .related_post_wrap,
                    .bdp_single.brite #comments,
                    .bdp_blog_template.brite {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-tags {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-tags span.tag a,
                    .bdp_blog_template.brite .post-tags-wrapp.bdp-no-links span {
                        background-color: <?php echo $winter_category_color; ?> !important;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                        padding: <?php echo $content_fontsize .'px'?> 1em;
                    }
                    .bdp_blog_template.brite .post-tags-wrapp.bdp-no-links span:hover {
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-tags-wrapp.bdp-no-links span:hover:after {
                        border-right: <?php echo $content_fontsize . 'px'; ?> solid <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-tags span.tag a:after,
                    .bdp_blog_template.brite .post-tags-wrapp.bdp-no-links span:after {
                        border-top: <?php echo $content_fontsize + ($content_fontsize / 2)  .'px'?> solid transparent;
                        border-bottom: <?php echo $content_fontsize + ($content_fontsize / 2) .'px'?> solid transparent;
                        border-right: <?php echo $content_fontsize .'px'?> solid <?php echo $winter_category_color; ?>;
                    }
                    .bdp_blog_template.brite .post-tags span.tag a:hover {
                        background-color: <?php echo $linkcolor; ?> !important;
                    }
                    .bdp_blog_template.brite .post-tags span.tag a:hover:after {
                        border-right: <?php echo $content_fontsize . 'px'; ?> solid <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-tags-wrapp.bdp-no-links span {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        margin-bottom: <?php echo $content_fontsize + 15 . 'px'; ?>;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                    }
                    .bdp_blog_template.brite .post-meta > .post-categories.bdp-no-links,
                    .bdp_blog_template.brite .post-meta > .post-categories i {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.brite .post-author .author-name.bdp-has-links,
                    .bdp_blog_template.brite .post-meta  .post-categories a,
                    .bdp_blog_template.brite .author-name a,
                    .bdp_blog_template.brite .date-meta a,
                    .bdp_blog_template.brite .post-comment a {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if ($template_name == "chapter") {
                ?>
                    .bdp_single.chapter .bdp_blog_template.chapter,
                    .chapter .author-avatar-div.bdp_blog_template,
                    .chapter .related_post_wrap,
                    .chapter .navigation.post-navigation {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .chapter .chapter-header{
                        color: <?php echo $contentcolor; ?>;
                    }
                    .chapter-header .post-comment i,
                    .chapter .chapter-footer
,                    .chapter-header > div i,
                    .chapter .post-categories {
                        color: <?php echo $linkcolor; ?>;
                    }

                    .chapter-header .post-comment:hover i,
                    .chapter-header .post-comment:hover a {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }
            if ($template_name == "tagly") {
                ?>
                    .bdp_single.tagly .bdp_single_related_post_leftright,
                    .bdp_single.tagly .bdp_blog_template.tagly,
                    .bdp_single.tagly .author-avatar-div,
                    .bdp_single.tagly .navigation.post-navigation,
                    .bdp_single.tagly .related_post_wrap,
                    .bdp_single.tagly #comments  {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.tagly .right-side-area h1.bdp_post_title {
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                        color: <?php echo $titlecolor; ?>;
                        <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                        <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                        <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                        <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                        margin-top: 0;
                    }
                    .bdp_blog_template.tagly .right-side-area h1.bdp_post_title:before {
                        background: <?php echo $templatecolor; ?>;
                        box-shadow: 6px -2px 0 <?php echo $templatecolor; ?>;
                        height: <?php echo $template_titlefontsize . 'px'; ?>;
                        top: <?php echo ($template_titlefontsize / 10); ?>px;
                    }
                    .bdp_blog_template.tagly .left-side-area {
                        background-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_blog_template.tagly .left-side-area:before {
                        border-top-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_blog_template.tagly .social-componentbefore {
                        border-bottom-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_blog_template.tagly .right-side-area .tagly-footer,
                    .bdp_single.tagly .comment-list .comment-content p,
                    .bdp_single.tagly .comment-author,
                    .tagly .tagly-footer .post-tags {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_blog_template.tagly .right-side-area .categories,
                    .bdp_blog_template.tagly .right-side-area .tagly-footer a,
                    .bdp_blog_template.tagly .right-side-area .categories a,
                    .bdp_blog_template.tagly .metadatabox,
                    .bdp_blog_template.tagly .metadatabox span,
                    .bdp_blog_template.tagly .metadatabox a {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    }

                    .bdp_blog_template.tagly .metadatabox i {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    }
                    .bdp_blog_template.tagly .metadatabox span.author i,
                    .tagly .tagly-footer .post-tags .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.tagly .metadatabox span.author.bdp_has_links,
                    .tagly .tagly-footer .post-tags.bdp_has_links,
                    .bdp_blog_template.tagly .right-side-area .tagly-footer a,
                    .bdp_blog_template.tagly .right-side-area .categories.bdp_has_links,
                    .bdp_blog_template.tagly .right-side-area .categories a,
                    .bdp_blog_template.tagly .metadatabox span a {
                        color: <?php echo $linkcolor; ?>;
                    }

                    .bdp_blog_template.tagly .right-side-area .tagly-footer a:hover,
                    .bdp_blog_template.tagly .right-side-area .tagly-footer a:focus,
                    .bdp_blog_template.tagly .right-side-area .categories a:hover,
                    .bdp_blog_template.tagly .right-side-area .categories a:focus,
                    .bdp_blog_template.tagly .metadatabox span a:hover,
                    .bdp_blog_template.tagly .metadatabox span a:focus {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }
            if ($template_name == 'pretty') {
                ?>
                    .single .bdp_single.pretty .navigation.post-navigation,
                    .bdp_single .author-avatar-div, .bdp_single .related_post_wrap,
                    .bdp_single.pretty .author-avatar-div,
                    .single .bdp_single.pretty .comment-list article,
                    .single .bdp_single.pretty .comment-list .pingback,
                    .bdp_single.pretty .comment-respond .comment-form,
                    .single .bdp_single.pretty .comment-list .trackback,
                    .bdp_blog_template.pretty .right-content-wrapper,
                    .bdp_blog_template.pretty .bdp-post-image.post-has-image:before,
                    .bdp_archive.pretty .author-avatar-div {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.pretty .blog_header .post_date {
                        background: <?php echo $templatecolor; ?>;
                    }
                    .bdp_blog_template.pretty .left-content-wrapper{
                        background: <?php echo Bdp_Utility::hex2rgba($templatecolor, 0.5); ?>;
                    }
                    .bdp_blog_template.pretty .left-content-wrapper.post-has-image:before {
                        border-bottom-color: <?php echo Bdp_Utility::hex2rgba($templatecolor, 0.5); ?>;
                    }
                    .bdp_blog_template.pretty .post-meta-cats-tags .tags > a:hover {
                        border-color: <?php echo $linkhovercolor; ?>;
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template.pretty .post-meta-cats-tags .tags > a {
                        border-color: <?php echo $linkcolor; ?>;
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.pretty .post-meta-cats-tags .tags.bdp-no-links,
                    .bdp_blog_template.pretty .post-meta-cats-tags .tags .link-lable,
                    .bdp_blog_template.pretty .post-meta-cats-tags .category-link.bdp-no-links,
                    .bdp_blog_template.pretty .post-meta-cats-tags .category-link .link-lable,
                    .bdp_blog_template.pretty > p:not(.has-text-color), .bdp_blog_template.pretty .metadatabox author.bdp-no-links,
                    .bdp_single .bdp_blog_template.pretty .post_content blockquote:not(.wp-block-quote.is-style-large) p,
                    .bdp_blog_template.pretty > p:not(.has-text-color), .bdp_blog_template.pretty .metadatabox author .link-lable,
                    .bdp_blog_template.pretty > p:not(.has-text-color), .bdp_blog_template.pretty .metadatabox {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                    .bdp_blog_template.pretty .metadatabox > span i {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.pretty p:not(.has-text-color) {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_blog_template.pretty p,
                    .bdp_single .bdp_blog_template.pretty .post_content blockquote:not(.wp-block-quote.is-style-large) p,
                    .bdp_blog_template.pretty .metadatabox author,
                    .bdp_blog_template.pretty > p:not(.has-text-color), .bdp_blog_template.pretty .metadatabox author,
                    .bdp_blog_template.pretty .post-meta-cats-tags .tags,
                    .bdp_blog_template.pretty .post-meta-cats-tags .category-link {
                        color: <?php echo $linkcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>;<?php } ?>
                    }
                <?php
            }

            if ($template_name == 'roctangle') {
                ?>
                    .bdp_single.roctangle .bdp_single_related_post_leftright,
                    .bdp_single.roctangle .bdp_blog_template.roctangle,
                    .bdp_single.roctangle .author-avatar-div,
                    .bdp_single.roctangle .navigation.post-navigation,
                    .bdp_single.roctangle .related_post_wrap,
                    .bdp_single.roctangle #comments {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.roctangle .author-avatar-div.bdp_blog_template .author_content {
                        border-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.roctangle .blog_template .post-meta-wrapper .post_date {
                        background: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.roctangle .post-content-wrapper .metadatabox .link-lable,
                    .bdp_single.roctangle .blog_template .post-content-wrapper .metadatabox .tags,
                    .bdp_single.roctangle .blog_template .post-content-wrapper .metadatabox .tags .link-lable {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $contentcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    }
                    .bdp_single.roctangle .blog_template .post-meta-wrapper .post-meta-div > span,
                    .bdp_single.roctangle .blog_template .post-content-wrapper .metadatabox .post-category,
                    .bdp_single.roctangle .metadatabox .tags a {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        border-color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    }
                    .bdp_single.roctangle .blog_template .post-meta-wrapper .post-meta-div span a:hover,
                    .bdp_single.roctangle .blog_template .post-meta-wrapper .post-meta-div span a:hover i,
                    .bdp_single.roctangle .metadatabox .post-categories a:hover,
                    .bdp_single.roctangle .metadatabox .tags a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.roctangle .blog_template .post-content-wrapper .metadatabox .tags.bdp_has_links,
                    .bdp_single.roctangle .blog_template .post-meta-wrapper .post-meta-div span i {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.roctangle blockquote {
                        border-color: <?php echo $templatecolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($templatecolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }

                <?php
            }

            if ($template_name == "minimal") {
                $meta_fontsize = $content_fontsize + 3;
                ?>
                    .minimal .post-content-wrapper,
                    .bdp_single.minimal .bdp-post-navigation,
                    .bdp_single.minimal .author-avatar-div,
                    .bdp_single.minimal .related_post_wrap,
                    .bdp_single.minimal #comments,
                    .bdp_single .bdp_blog_template.minimal{
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .minimal .post-category-wrapp .post-category {
                        border-color: <?php echo $linkcolor; ?>;
                    }
                    .minimal .post-header-meta > span {
                        font-size: <?php echo $meta_fontsize . 'px'; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp.bdp-no-links span {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        margin-bottom: <?php echo $content_fontsize + 15 . 'px'; ?>;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp span a,
                    .minimal .post-tags .post-tags-wrapp.bdp-no-links span {
                        background-color: <?php echo $winter_category_color; ?>;
                        padding: <?php echo $content_fontsize .'px'?> 1em;
                    }
                    .minimal .post-tags .post-tags-wrapp span a:after,
                    .minimal .post-tags .post-tags-wrapp.bdp-no-links span:after {
                        border-top: <?php echo $content_fontsize + ($content_fontsize / 2)  .'px'?> solid transparent;
                        border-bottom: <?php echo $content_fontsize + ($content_fontsize / 2) .'px'?> solid transparent;
                        border-right: <?php echo $content_fontsize .'px'?> solid <?php echo $winter_category_color; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp.bdp-no-links span:hover {
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp.bdp-no-links span:hover:after {
                        border-right: <?php echo $content_fontsize . 'px'; ?> solid <?php echo $linkcolor; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp span a {
                        background-color: <?php echo $winter_category_color; ?>;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp span a:hover {
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .minimal .post-tags .post-tags-wrapp span a:hover:after {
                        border-right: <?php echo $content_fontsize . 'px'; ?> solid <?php echo $linkcolor; ?>;
                    }

                    .minimal .post-tags {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_single.minimal blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                    .minimal .post-header-meta > span,
                    .minimal .post-category-wrapp .post-category,
                    .bdp_single.minimal .bdp-post-meta,
                    .bdp_single.minimal .post-comment:not(.no-bdp-links) {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.minimal .post-comment:not(.no-bdp-links):hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }

            if ($template_name == "glamour") {
                ?>
                    .bdp_single.glamour .bdp_blog_template.glamour,
                    .bdp_single.glamour .bdp-post-navigation,
                    .bdp_single.glamour .related_post_wrap,
                    .bdp_single.glamour .woocommerce-Reviews,
                    .glamour .post-content-wrapper,
                    .glamour .post-footer-meta,
                    .glamour .glamour-social-cover,
                    .bdp_single.glamour .author-avatar-div,
                    .bdp_single.glamour:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.glamour .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .glamour .post-footer-meta,
                    .glamour .glamour-social-cover {
                        border-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.glamour .post-content-wrapper > .category-link a,
                    .bdp_single.glamour .post-content-wrapper > .category-link {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.glamour .post-content-wrapper > .category-link a:hover {
                        color: <?php echo $linkcolor; ?>;
                        text-decoration: underline;
                    }
                    .glamour-social-cover .glamour-social-links-closed i,
                    .glamour-footer-icon span a i {
                        color: <?php echo $linkhovercolor; ?>;
                        border-color: <?php echo $linkhovercolor; ?>;
                    }
                    .glamour-social-cover .glamour-social-links-closed i:hover,
                    .glamour-footer-icon span a i:hover {
                        background: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.glamour blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: #<?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                    .bdp_single.glamour .post-content-wrapper > .tags.link-lable {
                        color: <?php echo $contentcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_single.glamour .post-content-wrapper > .tags,
                    .bdp_single.glamour .post-footer-meta > span,
                    .bdp_single .display_post_views p {
                        color: <?php echo $linkcolor; ?>;
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                <?php
            }

            if($template_name == 'famous') {
                ?>
                    .bdp_single.famous blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                    .bdp_single_product.famous,
                    .bdp_single.famous .author-avatar-div,
                    .bdp_single.famous .related_post_wrap,
                    .bdp_single.famous:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.famous .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_single.famous .category-link,
                    .bdp_single.famous .post-meta,
                    .bdp_single.famous .post-footer-meta,
                    .bdp_single.famous .post-footer-meta .display_post_views p {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_single.famous .category-link,
                    .bdp_single.famous .category-link a {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.famous .bdp_blog_template .category-link a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                <?php
            }
            if($template_name == 'fairy') {
                ?>
                    .bdp_single.fairy .bdp_blog_template,
                    .bdp_single.fairy .author-avatar-div,
                    .bdp_single.fairy .bdp-post-navigation,
                    .bdp_single.fairy .author-avatar-div,
                    .bdp_single.fairy .related_post_wrap,
                    .bdp_single.fairy:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.fairy .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .bdp_blog_template.fairy .post-meta .display_post_views p {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.fairy .post-tags .post-tags-wrapp span,
                    .bdp_blog_template.fairy .category-link .tag {
                        border-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.fairy .category-link,
                    .bdp_blog_template.fairy .post-tags {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_blog_template.fairy .category-link .link-lable,
                    .bdp_blog_template.fairy .post-tags .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.fairy blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                <?php
            }
            if($template_name == 'clicky') {
                ?>
                    .clicky .post-meta,
                    .clicky .post-tags,
                    .clicky .author .author-name,
                    .clicky .post-category-wrapp span,
                    .clicky .display_post_views p {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .clicky .post-category-wrapp span {
                        border-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.clicky .bdp_blog_template,
                    .bdp_single.clicky .bdp-post-navigation,
                    .bdp_single.clicky .author-avatar-div,
                    .bdp_single.clicky .related_post_wrap,
                    .bdp_single.clicky:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.clicky .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .clicky .post-tags .post-tags-wrapp span {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        margin-bottom: <?php echo $content_fontsize + 15 . 'px'; ?>;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                    }
                    .clicky .post-tags .post-tags-wrapp.bdp-no-links span,
                    .clicky .post-tags .post-tags-wrapp span a{
                        background-color: <?php echo $winter_category_color; ?>;
                        padding: <?php echo round($content_fontsize / 2) .'px'?> 1em;
                    }
                    .clicky .post-tags .post-tags-wrapp span a:hover {
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .clicky .post-tags .post-tags-wrapp span a:hover:after {
                        border-right-color: <?php echo $linkcolor; ?>;
                    }

                    .clicky .post-tags .post-tags-wrapp.bdp-no-links span{
                        background-color: <?php echo $winter_category_color; ?>;
                        margin-left: <?php echo $content_fontsize + 15 . 'px'; ?>;
                    }
                    .clicky .post-tags .post-tags-wrapp.bdp-no-links span:after,
                    .clicky .post-tags .post-tags-wrapp span a:after {
                        border-top-color: transparent;
                        border-bottom-color: transparent;
                        border-right-color: <?php echo $winter_category_color; ?>;
                    }
                    .clicky .post-tags .post-tags-wrapp.bdp-no-links span:hover {
                        background-color: <?php echo $linkcolor; ?>;
                    }
                    .clicky .post-tags .post-tags-wrapp.bdp-no-links span:hover:after {
                        border-right-color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_single.clicky blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                <?php
            }

            if($template_name == 'cover') {
                ?>
                    .bdp_single.cover .bdp_blog_template,
                    .bdp_single.cover .bdp-post-navigation,
                    .bdp_single.cover .related_post_wrap,
                    .bdp_single.cover:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.cover .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .bdp_blog_template.cover .category-link,
                    .bdp_blog_template.cover .post-meta,
                    .bdp_blog_template.cover .post-footer-meta,
                    .bdp_blog_template.cover .post-footer-meta .display_post_views p {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_blog_template.cover .category-link,
                    .bdp_blog_template.cover .category-link a {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_blog_template.cover .category-link a:hover {
                        color: <?php echo $linkcolor; ?>;
                    }
                    .bdp_blog_template.cover blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                <?php
            }

            if($template_name == 'steps') {
                ?>
                    .bdp_single.steps,
                    .bdp_single.steps > div,
                    .bdp_blog_template.steps .post-meta,
                    .bdp_single.steps > .bdp-post-navigation,
                    .bdp_single.steps > div:before,
                    .bdp_single.steps > .bdp-post-navigation:before {
                        border-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.steps > div:after,
                    .bdp_single.steps > .bdp-post-navigation:after {
                        color: <?php echo $templatecolor; ?>;
                        border-color: <?php echo $templatecolor; ?>;
                    }
                    .bdp_single.steps > div,
                    .bdp_single.steps > .bdp-post-navigation,
                    .bdp_single.steps > div:after,
                    .bdp_single.steps > .bdp-post-navigation:after,
                    .bdp_single.steps > div:before,
                    .bdp_single.steps > .bdp-post-navigation:before {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .bdp_blog_template.cover .post-meta,
                    .bdp_blog_template.steps .display_post_views p,
                    .bdp_blog_template.steps .post-meta-cats-tags {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_blog_template.steps .post-meta-cats-tags .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.steps blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($templatecolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                    .bdp_blog_template.steps .post-meta > span {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }

            if ($template_name == "miracle") {
                ?>
                    .bdp_single.miracle .miracle_blog .bdp-post-format {
                        color: <?php echo $titlecolor; ?>;
                        font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    }
                    .bdp_single.miracle .miracle_blog,
                    .bdp_single.miracle .navigation.post-navigation,
                    .bdp_single.miracle .author-avatar-div,
                    .bdp_single.miracle .related_post_wrap,
                    .bdp_single.miracle:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.miracle .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?> !important;
                    }
                    .bdp_single.miracle .miracle_blog .post-meta,
                    .bdp_single.miracle .miracle_blog .post-meta a,
                    .bdp_single.miracle .miracle_blog .category-link,
                    .bdp_single.miracle .miracle_blog .category-link a,
                    .bdp_single.miracle .miracle_blog .tags,
                    .bdp_single.miracle .miracle_blog .tags a {
                        font-size: <?php echo $content_fontsize . 'px'; ?>;
                        color: <?php echo $linkcolor; ?>;
                        <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                    .bdp_single.miracle .miracle_blog .post-meta .bdp_no_links,
                    .bdp_single.miracle .miracle_blog .category-link a:hover,
                    .bdp_single.miracle .miracle_blog .tags a:hover,
                    .bdp_single.miracle .miracle_blog .post-meta a:hover {
                        color: <?php echo $linkhovercolor; ?>;
                    }
                    .bdp_single.miracle .miracle_blog .category-link.bdp_no_links,
                    .bdp_single.miracle .miracle_blog .category-link .link-lable,
                    .bdp_single.miracle .miracle_blog .tags.bdp_no_links,
                    .bdp_single.miracle .miracle_blog .tags .link-lable {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.miracle .miracle_blog blockquote {
                        border-color: <?php echo $linkhovercolor; ?>;
                        background-color: <?php echo Bdp_Utility::hex2rgba($linkhovercolor, 0.1); ?>;
                        padding: 15px 15px 15px 30px;
                        margin: 15px 0;
                    }
                <?php
            }
            
            if ($template_name == "hub") {
                ?>
                    .bdp_blog_template.hub .tags.bdp-no-links,
                    .bdp_blog_template.hub .post-bottom .categories.bdp-no-links {
                        color: <?php echo $contentcolor; ?>;
                    }
                    .bdp_single.hub .bdp_blog_template.hub,
                    .bdp_single.hub .navigation.post-navigation.bdp-post-navigation,
                    .bdp_single.hub .author-avatar-div.bdp_blog_template,
                    .bdp_single.hub .related_post_wrap,
                    .bdp_single.hub:not(.bdp_single_product) .comments-area,
                    .bdp_single_product.hub .woocommerce-Reviews {
                        background: <?php echo $template_bgcolor; ?>;
                    }
                    .bdp_blog_template.hub .post-bottom span.post-by .bdp-has-links,
                    .bdp_blog_template.hub .tags,
                    .bdp_blog_template.hub .tags i,
                    .bdp_blog_template.hub .post-by,
                    .post-bottom span,
                    .bdp_blog_template.hub .post-bottom .categories i,
                    .bdp_blog_template.hub .post-bottom .categories,
                    .bdp_blog_template.hub .metacomments a {
                        color: <?php echo $linkcolor; ?>;
                    }
                <?php
            }
            if($template_name == 'foodbox') { ?>
                .foodbox-content-wrap,
                .foodbox .author-avatar-div,
                .bdp_single.foodbox:not(.bdp_single_product) .comments-area,
                .bdp_single_product.foodbox .woocommerce-Reviews {
                    background:<?php echo $template_bgcolor; ?>;
                }
               .foodbox_blog .post_content .foodbox-quote {
                    color:<?php echo $templatecolor; ?>;
                }
               .foodbox-blog-wrapp:before,
               .foodbox-blog-wrapp:after {
                    border-color: <?php echo $templatecolor; ?>;
                }
                .bdp_single.foodbox .bdp-post_content,
                .bdp_single.foodbox .bdp-post_content p {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $contentcolor; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                <?php if($backgroundbg_image_src != '') { ?>
                    .bdp-single-wrapper.foodbox-cover {
                        background-image: url("<?php echo $backgroundbg_image_src; ?>");
                    }
            <?php }
            }
            if($template_name == 'soft_block') { ?>
                .bdp_single.soft_block .soft_block_template,
                .bdp_single.soft_block .bdp-post-navigation,
                .bdp_single.soft_block .author-avatar-div.bdp_blog_template,
                .bdp_single.soft_block .related_post_wrap,
                .bdp_single.soft_block:not(.bdp_single_product) .comments-area,
                .bdp_single_product.soft_block .woocommerce-Reviews {
                    background:<?php echo $template_bgcolor; ?>;
                }
                .bdp_single.soft_block .bdp-post-date span a{
                    color:<?php echo $template_bgcolor; ?> !important;
                }
                .bdp_single #respond .comment-form-comment textarea#comment{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $template_bgcolor; ?>;
                    <?php if ($content_fontface) { ?>font-family: <?php echo $content_fontface; ?>; <?php } ?>
                }
               
                .bdp_single.soft_block .soft_block_template .post-meta a,
                .bdp_single.soft_block .soft_block_template .category-link a,
                .bdp_single.soft_block .soft_block_template .tags a {
                    color: <?php echo $linkcolor; ?>;
                }
                .bdp_single.soft_block .soft_block_template .post-meta a,
                .bdp_single.soft_block .soft_block_template .category-link a,
                .bdp_single.soft_block .soft_block_template .tags a,
                .bdp_single.soft_block .soft_block_template .post-meta,
                .bdp_single.soft_block .soft_block_template .category-link,
                .bdp_single.soft_block .soft_block_template .tags,
                .bdp_single.soft_block .soft_block_template .bdp-post_content,
                .bdp_single.soft_block .soft_block_template .bdp-post_content p {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.soft_block .soft_block_template .post-meta,
                .bdp_single.soft_block .soft_block_template .category-link,
                .bdp_single.soft_block .soft_block_template .tags,
                .bdp_single.soft_block .soft_block_template .bdp-post_content,
                .bdp_single.soft_block .soft_block_template .bdp-post_content p {
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single.soft_block .soft_block_template .post-title {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
            <?php
            }
            if($template_name == 'wise_block') { ?>
                .bdp_single.wise_block .bdp_blog_template.wise,
                .bdp_single.wise_block .bdp-post-navigation,
                .bdp_single.wise_block .author-avatar-div.bdp_blog_template,
                .bdp_single.wise_block .related_post_wrap,
                .bdp_single.wise_block:not(.bdp_single_product) .comments-area,
                .bdp_single_product.wise_block .woocommerce-Reviews {
                    background:<?php echo $template_bgcolor; ?>;
                }
                .bdp_single.wise_block .blog_template.wise .metadatabox,
                .bdp_single.wise_block .blog_template.wise .bdp-wrapper-like i,
                .bdp_single.wise_block .blog_template.wise .bdp-wrapper-like .bdp-count,
                .bdp_single.wise_block .blog_template.wise .metadatabox a,
                .bdp_single.wise_block .blog_template.wise .category-link,
                .bdp_single.wise_block .blog_template.wise .category-link a,
                .bdp_single.wise_block .blog_template.wise .tags,
                .bdp_single.wise_block .blog_template.wise .tags a,
                .bdp_single.wise_block .blog_template.wise .bdp_post_content,
                .bdp_single.wise_block .blog_template.wise .bdp_post_content p{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.wise_block .blog_template.wise .metadatabox,
                .bdp_single.wise_block .blog_template.wise .bdp-wrapper-like i,
                .bdp_single.wise_block .blog_template.wise .bdp-wrapper-like .bdp-count,
                .bdp_single.wise_block .blog_template.wise .metadatabox a,
                .bdp_single.wise_block .blog_template.wise .category-link a,
                .bdp_single.wise_block .blog_template.wise .tags a {
                    color: <?php echo $linkcolor; ?>;
                }
                .bdp_single.wise_block .blog_template.wise .bdp_post_content,
                .bdp_single.wise_block .blog_template.wise .bdp_post_content p {
                    color: <?php echo $contentcolor; ?>;
                }
                .bdp_single.wise_block .blog_template.wise .post-title {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
            <?php
            }
            if($template_name == 'schedule') { ?>
                .bdp_single.schedule .schedule-content-wrap,
                .bdp_single.schedule .author-avatar-div.bdp_blog_template,
                .bdp_single.schedule .related_post_wrap,
                .bdp_single.schedule:not(.bdp_single_product) .comments-area,
                .bdp_single_product.schedule .woocommerce-Reviews {
                    background:<?php echo $template_bgcolor; ?>;
                }
                .blog_template.schedule .meta-archive a {
                    background: <?php echo $templatecolor; ?>;
                }
               .blog_template.schedule .schedule-content-wrap:after,
               .bdp_single.schedule .author-avatar-div:before,
               .bdp_single.schedule .author-avatar-div:after,
               .bdp_single.schedule .navigation.post-navigation:after,
               .bdp_single.schedule .navigation.post-navigation .nav-links:after,
               .bdp_single.schedule .related_post_wrap:after,
               .bdp_single.schedule .related_post_wrap:before,
               .bdp_single_product.schedule .woocommerce-Reviews:before,
               .bdp_single_product.schedule .woocommerce-Reviews:after,
               .bdp_single.schedule .comments-area:before,
               .bdp_single.schedule .comments-area:after,
               .bdp_single.schedule:not(.bdp_single_product) .comments-area:before, .bdp_single_product.schedule .woocommerce-Reviews:before, .bdp_single.schedule .related_post_wrap:before,
               .bdp_single.schedule:not(.bdp_single_product) .comments-area:after, .bdp_single_product.schedule .woocommerce-Reviews:after, .bdp_single.schedule .related_post_wrap:after {
                    border-color: <?php echo $templatecolor; ?>;
                }
                .bdp_single.schedule .author-avatar-div:before,
                .bdp_single.schedule .related_post_wrap:before,
                .bdp_single.schedule .comments-area:before,
                .bdp_single_product.schedule .woocommerce-Reviews:before,
                .bdp_single.schedule .navigation.post-navigation .nav-links:after,
                .blog_template.schedule .schedule-circle:after,
                .bdp_single.schedule:not(.bdp_single_product) .comments-area:before, .bdp_single_product.schedule .woocommerce-Reviews:before, .bdp_single.schedule .related_post_wrap:before  {
                    background:<?php echo $templatecolor; ?>;
                }
                @media screen and (max-width: 480px) {
                    .blog_template.schedule .bdp-post-meta {
                    box-shadow: 0px 0px 5px <?php echo $templatecolor; ?>;
                    }
                }
               
                .blog_template.schedule .schedule-circle:after,
                .bdp_single.schedule .author-avatar-div:before,
                .bdp_single.schedule .navigation.post-navigation .nav-links:after,
                .bdp_single.schedule .comments-area:before,
                .bdp_single.schedule .comments-area:after,
                .bdp_single.schedule .related_post_wrap:after,
                .bdp_single.schedule .related_post_wrap:before,
                .bdp_single_product.schedule .woocommerce-Reviews:before,
                 .bdp_single_product.schedule .woocommerce-Reviews:after {
                    background: <?php echo $templatecolor; ?>;
                }
                .bdp_single.schedule .blog_template.schedule .bdp-wrapper-like i{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $linkcolor; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.schedule .blog_template.schedule .bdp-wrapper-like .bdp-count,
                .bdp_single.schedule .blog_template.schedule .metadatabox a,
                .bdp_single.schedule .blog_template.schedule .post-category a,
                .bdp_single.schedule .blog_template.schedule .tags a,
                .bdp_single.schedule .blog_template.schedule .bdp_post_content,
                .bdp_single.schedule .blog_template.schedule .bdp-post_content p {
                    color: <?php echo $linkcolor; ?>;
                }
                .bdp_single.schedule .blog_template.schedule .bdp-wrapper-like .bdp-count,
                .bdp_single.schedule .blog_template.schedule .metadatabox a,
                .bdp_single.schedule .blog_template.schedule .post-category a,
                .bdp_single.schedule .blog_template.schedule .tags a,
                .bdp_single.schedule .blog_template.schedule .bdp_post_content,
                .bdp_single.schedule .blog_template.schedule .bdp-post_content p,
                .bdp_single.schedule .blog_template.schedule .tags,
                .bdp_single.schedule .blog_template.schedule .metadatabox,
                .bdp_single.schedule .blog_template.schedule .post-category{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.schedule .blog_template.schedule .tags,
                .bdp_single.schedule .blog_template.schedule .metadatabox,
                .bdp_single.schedule .blog_template.schedule .post-category,
                .bdp_single.schedule .blog_template.schedule .bdp_post_content,
                .bdp_single.schedule .blog_template.schedule .bdp-post_content p {
                    color: <?php echo $contentcolor; ?>;
                    
                }
                .bdp_single.schedule .schedule-time a,.bdp_single.schedule .blog_template.schedule .post-category,
                .blog_template.schedule .tags a {
                    <?php if ($winter_category_color) { ?> background-color: <?php echo $winter_category_color; ?>; <?php } ?>
                    color: #fff !important;
                    word-break: normal;
                }
                .blog_template.schedule .tags a:after{
                    <?php if ($winter_category_color) { ?>border-color: transparent <?php echo $winter_category_color; ?> transparent transparent; <?php } ?>
                    
                }
                .bdp_single.schedule .blog_template.schedule .post-title,
                .bdp_single.schedule .blog_template.schedule .post-title a {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                    text-shadow: <?php echo esc_attr($bdp_title_top_box_shadow) .' '. esc_attr($bdp_title_right_box_shadow) .' '. esc_attr($bdp_title_bottom_box_shadow).' '. esc_attr($bdp_title_box_shadow_color); ?> ;
                    margin-top: 10px;
                }
            <?php
            }
            if($template_name == 'neaty_block') { ?>
                .bdp_single.neaty_block .bdp_blog_template.neaty-blog,
                .bdp_single.neaty_block .bdp-post-navigation,
                .bdp_single.neaty_block .author-avatar-div.bdp_blog_template,
                .bdp_single.neaty_block .related_post_wrap,
                .bdp_single.neaty_block:not(.bdp_single_product) .comments-area,
                .bdp_single_product.neaty_block .woocommerce-Reviews,
                .bdp_single.neaty_block .social-share-links,
                .bdp_single.neaty_block .nav-links {
                    background:<?php echo $template_bgcolor; ?>;
                }
              
                .bdp_single.neaty_block .bdp-wrapper-like i{
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $linkcolor; ?>;
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.neaty_block .metadatabox,
                .bdp_single.neaty_block .bdp-wrapper-like .bdp-count,
                .bdp_single.neaty_block .metadatabox a,
                .bdp_single.neaty_block .post-category,
                .bdp_single.neaty_block .post-category a,
                .bdp_single.neaty_block .tags,
                .bdp_single.neaty_block .tags a,
                .bdp_single.neaty_block .post_content,
                .bdp_single.neaty_block .post_content p,
                .bdp_single.neaty_block .post-author a,
                .bdp_single.neaty_block .post-author,
                .bdp_single.neaty_block .comments-link,
                .bdp_single.neaty_block .bdp-post-date a,
                .bdp_single.neaty_block .bdp-post-date span {
                    font-size: <?php echo $content_fontsize . 'px'; ?>;
                    color: <?php echo $linkcolor; ?>;
                    <?php if ($content_fontface) { ?> font-family: <?php echo $content_fontface; ?>; <?php } ?>
                    <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                    <?php if ($template_content_font_line_height) { ?> line-height: <?php echo $template_content_font_line_height; ?>;<?php } ?>
                    <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                }
                .bdp_single.neaty_block .post-title,
                .bdp_single.neaty_block .post-title a {
                    font-size: <?php echo $template_titlefontsize . 'px'; ?>;
                    color: <?php echo $titlecolor; ?>;
                    <?php if ($template_titlefontface) { ?> font-family: <?php echo $template_titlefontface; ?>; <?php } ?>
                    <?php if ($template_title_font_weight) { ?> font-weight: <?php echo $template_title_font_weight; ?>;<?php } ?>
                    <?php if ($template_title_font_line_height) { ?> line-height: <?php echo $template_title_font_line_height; ?>;<?php } ?>
                    <?php if ($template_title_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                    <?php if ($template_title_font_text_transform) { ?> text-transform: <?php echo $template_title_font_text_transform; ?>;<?php } ?>
                    <?php if ($template_title_font_text_decoration) { ?> text-decoration: <?php echo $template_title_font_text_decoration; ?>;<?php } ?>
                    <?php if ($template_title_font_letter_spacing) { ?> letter-spacing: <?php echo $template_title_font_letter_spacing . 'px'; ?>;<?php } ?>
                    margin-top: 20px;
                }
            <?php
            }
            if (isset($single_data_setting['firstletter_big']) && $single_data_setting['firstletter_big'] == 1) {
                ?>
                    .bdp_single .bdp_blog_template .entry-content > *:first-child:first-letter,
                    .bdp_single .bdp_blog_template .entry-content > p:first-child:first-letter,
                    .bdp_single .bdp_blog_template .post_content > p:first-child:first-letter,
                    .bdp_single .bdp-first-letter{
                        font-size:<?php echo $firstletter_fontsize . 'px'; ?>;
                        color: <?php echo $firstletter_contentcolor; ?>;
                        <?php if ($firstletter_contentfontface) { ?> font-family:<?php echo $firstletter_contentfontface; ?>; <?php } ?>
                        <?php if ($template_content_font_weight) { ?> font-weight: <?php echo $template_content_font_weight; ?>;<?php } ?>
                        <?php if ($firstletter_fontsize) { ?> line-height: <?php echo $firstletter_fontsize * 75 / 100 . 'px'; ?>;<?php } ?>
                        <?php if ($template_content_font_italic) { ?> font-style: <?php echo 'italic'; ?>;<?php } ?>
                        <?php if ($template_content_font_text_transform) { ?> text-transform: <?php echo $template_content_font_text_transform; ?>;<?php } ?>
                        <?php if ($template_content_font_text_decoration) { ?> text-decoration: <?php echo $template_content_font_text_decoration; ?>;<?php } ?>
                        <?php if ($template_content_font_letter_spacing) { ?> letter-spacing: <?php echo $template_content_font_letter_spacing . 'px'; ?>;<?php } ?>
                    }
                <?php
            }
            if (isset($single_data_setting['custom_css']) && !empty($single_data_setting['custom_css'])) {
                echo $single_data_setting['custom_css'];
            }
            ?></style>
            <?php
        }
    }
}
