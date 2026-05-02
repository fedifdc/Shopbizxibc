<?php
/**
 * Layout Templates Dynamic Style Start.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Wp_Blog_Designer_PRO
 * @subpackage Wp_Blog_Designer_PRO/public
 */

?>
<style id="bdp_dynamic_style_<?php echo esc_attr( $shortcode_id ); ?>">
<?php
if ( 1 == get_option( 'bdp_template_name_changed', 1 ) ) { 
	if ( 'classical' === $bdp_theme ) {
		$bdp_theme = 'nicy';
	} elseif ( 'lightbreeze' === $bdp_theme ) {
		$bdp_theme = 'sharpen';
	} elseif ( 'spektrum' === $bdp_theme ) {
		$bdp_theme = 'hub';
	}
} else {
	update_option( 'bdp_template_name_changed', 0 );
}
	$layout_id                         = '.layout_id_' . $shortcode_id;
	$layout_filter_id                  = '.layout_filter_id_' . $shortcode_id;
	$template_bgcolor                  = ( isset( $bdp_settings['template_bgcolor'] ) && ! empty( $bdp_settings['template_bgcolor'] ) ) ? $bdp_settings['template_bgcolor'] : '';
	$template_icon_active_header_color = ( isset( $bdp_settings['template_icon_active_header_color'] ) && ! empty( $bdp_settings['template_icon_active_header_color'] ) ) ? $bdp_settings['template_icon_active_header_color'] : '';
	$template_icon_color               = isset( $bdp_settings['template_icon_color'] ) ? $bdp_settings['template_icon_color'] : '#000000';
	$template_icon_hover_color         = isset( $bdp_settings['template_icon_hover_color'] ) ? $bdp_settings['template_icon_hover_color'] : '#000000';
	$template_icon_bgcolor             = isset( $bdp_settings['template_icon_bgcolor'] ) ? $bdp_settings['template_icon_bgcolor'] : 'transparent';
	$icon_button_border_radius         = isset( $bdp_settings['icon_button_border_radius'] ) ? $bdp_settings['icon_button_border_radius'] : '0';
	$icon_fontsize                     = isset( $bdp_settings['icon_fontsize'] ) ? $bdp_settings['icon_fontsize'] : '14';
	$icon_paddingleft                  = isset( $bdp_settings['icon_paddingleft'] ) ? $bdp_settings['icon_paddingleft'] : '0';
	$icon_paddingright                 = isset( $bdp_settings['icon_paddingright'] ) ? $bdp_settings['icon_paddingright'] : '0';
	$icon_paddingbottom                = isset( $bdp_settings['icon_paddingbottom'] ) ? $bdp_settings['icon_paddingbottom'] : '0';
	$icon_paddingtop                   = isset( $bdp_settings['icon_paddingtop'] ) ? $bdp_settings['icon_paddingtop'] : '0';
	$content_button_border_radius      = isset( $bdp_settings['content_button_border_radius'] ) ? $bdp_settings['content_button_border_radius'] : '0';
	$repetative_icon_color1            = isset( $bdp_settings['repetative_icon_color1'] ) ? $bdp_settings['repetative_icon_color1'] : '';
	$repetative_icon_color2            = isset( $bdp_settings['repetative_icon_color2'] ) ? $bdp_settings['repetative_icon_color2'] : '';
	$repetative_icon_color3            = isset( $bdp_settings['repetative_icon_color3'] ) ? $bdp_settings['repetative_icon_color3'] : '';
	
	$template_titlecolor               = isset( $bdp_settings['template_titlecolor'] ) ? $bdp_settings['template_titlecolor'] : '#2096cd';
	$template_titlefontface		   	   = isset( $bdp_settings['template_titlefontface']) ? $bdp_settings['template_titlefontface'] : '';
	$template_titlefontsize            = isset( $bdp_settings['template_titlefontsize'] ) ? $bdp_settings['template_titlefontsize'] : '';
	$template_title_font_weight		   = isset( $bdp_settings['template_title_font_weight']) ? $bdp_settings['template_title_font_weight'] : '';
	$template_title_font_line_height   = isset( $bdp_settings['template_title_font_line_height'] ) ? $bdp_settings['template_title_font_line_height'] : '';
	$template_title_font_text_transform	= isset( $bdp_settings['template_title_font_text_transform']) ? $bdp_settings['template_title_font_text_transform'] : '';
	$template_title_font_text_decoration = isset( $bdp_settings['template_title_font_text_decoration']) ? $bdp_settings['template_title_font_text_decoration'] : '';
	$template_title_font_letter_spacing	= isset( $bdp_settings['template_title_font_letter_spacing']) ? $bdp_settings['template_title_font_letter_spacing'] : '';

	$template_color = isset( $bdp_settings['template_color']) ? $bdp_settings['template_color'] : '';
	$template_ftcolor = isset( $bdp_settings['template_ftcolor']) ? $bdp_settings['template_ftcolor'] : '';
	$template_fthovercolor = isset( $bdp_settings['template_fthovercolor']) ? $bdp_settings['template_fthovercolor'] : '';
	$template_titlehovercolor = isset( $bdp_settings['template_titlehovercolor']) ? $bdp_settings['template_titlehovercolor'] : '';
	$readmore_button_border_radius = isset( $bdp_settings['readmore_button_border_radius'] ) ? $bdp_settings['readmore_button_border_radius'] : "";
	$next_button_text = isset( $bdp_settings['next_button_text'] ) ? $bdp_settings['next_button_text'] : "";
	$previous_button_text = isset( $bdp_settings['previous_button_text'] ) ? $bdp_settings['previous_button_text'] : "";
	//Post title Margin-Padding
	$post_title_marginleft	= isset( $bdp_settings['post_title_marginleft']) ? $bdp_settings['post_title_marginleft'] : '';
	$post_title_marginright	= isset( $bdp_settings['post_title_marginright']) ? $bdp_settings['post_title_marginright'] : '';
	$post_title_margintop	= isset( $bdp_settings['post_title_margintop']) ? $bdp_settings['post_title_margintop'] : '';
	$post_title_marginbottom	= isset( $bdp_settings['post_title_marginbottom']) ? $bdp_settings['post_title_marginbottom'] : '';
	$post_title_paddingleft	= isset( $bdp_settings['post_title_paddingleft']) ? $bdp_settings['post_title_paddingleft'] : '';
	$post_title_paddingright	= isset( $bdp_settings['post_title_paddingright']) ? $bdp_settings['post_title_paddingright'] : '';
	$post_title_paddingtop	= isset( $bdp_settings['post_title_paddingtop']) ? $bdp_settings['post_title_paddingtop'] : '';
	$post_title_paddingbottom	= isset( $bdp_settings['post_title_paddingbottom']) ? $bdp_settings['post_title_paddingbottom'] : '';
	//Post content Margin-Padding
	$post_content_marginleft	= isset( $bdp_settings['post_content_marginleft']) ? $bdp_settings['post_content_marginleft'] : '';
	$post_content_marginright	= isset( $bdp_settings['post_content_marginright']) ? $bdp_settings['post_content_marginright'] : '';
	$post_content_margintop		= isset( $bdp_settings['post_content_margintop']) ? $bdp_settings['post_content_margintop'] : '';
	$post_content_marginbottom	= isset( $bdp_settings['post_content_marginbottom']) ? $bdp_settings['post_content_marginbottom'] : '';
	$post_content_paddingleft	= isset( $bdp_settings['post_content_paddingleft']) ? $bdp_settings['post_content_paddingleft'] : '';
	$post_content_paddingright	= isset( $bdp_settings['post_content_paddingright']) ? $bdp_settings['post_content_paddingright'] : '';
	$post_content_paddingtop	= isset( $bdp_settings['post_content_paddingtop']) ? $bdp_settings['post_content_paddingtop'] : '';
	$post_content_paddingbottom	= isset( $bdp_settings['post_content_paddingbottom']) ? $bdp_settings['post_content_paddingbottom'] : '';
	//Product Post title Margin-Padding
	$product_post_title_marginleft	= isset( $bdp_settings['product_post_title_marginleft']) ? $bdp_settings['product_post_title_marginleft'] : '';
	$product_post_title_marginright	= isset( $bdp_settings['product_post_title_marginright']) ? $bdp_settings['product_post_title_marginright'] : '';
	$product_post_title_margintop	= isset( $bdp_settings['product_post_title_margintop']) ? $bdp_settings['product_post_title_margintop'] : '';
	$product_post_title_marginbottom	= isset( $bdp_settings['product_post_title_marginbottom']) ? $bdp_settings['product_post_title_marginbottom'] : '';
	$product_post_title_paddingleft	= isset( $bdp_settings['product_post_title_paddingleft']) ? $bdp_settings['product_post_title_paddingleft'] : '';
	$product_post_title_paddingright	= isset( $bdp_settings['product_post_title_paddingright']) ? $bdp_settings['product_post_title_paddingright'] : '';
	$product_post_title_paddingtop	= isset( $bdp_settings['product_post_title_paddingtop']) ? $bdp_settings['product_post_title_paddingtop'] : '';
	$product_post_title_paddingbottom	= isset( $bdp_settings['product_post_title_paddingbottom']) ? $bdp_settings['product_post_title_paddingbottom'] : '';
	//Product Post content Margin-Padding
	$product_post_content_marginleft	= isset( $bdp_settings['product_post_content_marginleft']) ? $bdp_settings['product_post_content_marginleft'] : '';
	$product_post_content_marginright	= isset( $bdp_settings['product_post_content_marginright']) ? $bdp_settings['product_post_content_marginright'] : '';
	$product_post_content_margintop		= isset( $bdp_settings['product_post_content_margintop']) ? $bdp_settings['product_post_content_margintop'] : '';
	$product_post_content_marginbottom	= isset( $bdp_settings['product_post_content_marginbottom']) ? $bdp_settings['product_post_content_marginbottom'] : '';
	$product_post_content_paddingleft	= isset( $bdp_settings['product_post_content_paddingleft']) ? $bdp_settings['product_post_content_paddingleft'] : '';
	$product_post_content_paddingright	= isset( $bdp_settings['product_post_content_paddingright']) ? $bdp_settings['product_post_content_paddingright'] : '';
	$product_post_content_paddingtop	= isset( $bdp_settings['product_post_content_paddingtop']) ? $bdp_settings['product_post_content_paddingtop'] : '';
	$product_post_content_paddingbottom	= isset( $bdp_settings['product_post_content_paddingbottom']) ? $bdp_settings['product_post_content_paddingbottom'] : '';
	//Download Post title Margin-Padding
	$download_post_title_marginleft = isset( $bdp_settings['download_post_title_marginleft']) ? $bdp_settings['download_post_title_marginleft'] : '';
	$download_post_title_marginright	= isset( $bdp_settings['download_post_title_marginright']) ? $bdp_settings['download_post_title_marginright'] : '';
	$download_post_title_margintop	= isset( $bdp_settings['download_post_title_margintop']) ? $bdp_settings['download_post_title_margintop'] : '';
	$download_post_title_marginbottom	= isset( $bdp_settings['download_post_title_marginbottom']) ? $bdp_settings['download_post_title_marginbottom'] : '';
	$download_post_title_paddingleft	= isset( $bdp_settings['download_post_title_paddingleft']) ? $bdp_settings['download_post_title_paddingleft'] : '';
	$download_post_title_paddingright	= isset( $bdp_settings['download_post_title_paddingright']) ? $bdp_settings['download_post_title_paddingright'] : '';
	$download_post_title_paddingtop	= isset( $bdp_settings['download_post_title_paddingtop']) ? $bdp_settings['download_post_title_paddingtop'] : '';
	$download_post_title_paddingbottom	= isset( $bdp_settings['download_post_title_paddingbottom']) ? $bdp_settings['download_post_title_paddingbottom'] : '';
	//Download Post content Margin-Padding
	$download_post_content_marginleft	= isset( $bdp_settings['download_post_content_marginleft']) ? $bdp_settings['download_post_content_marginleft'] : '';
	$download_post_content_marginright	= isset( $bdp_settings['download_post_content_marginright']) ? $bdp_settings['download_post_content_marginright'] : '';
	$download_post_content_margintop		= isset( $bdp_settings['download_post_content_margintop']) ? $bdp_settings['download_post_content_margintop'] : '';
	$download_post_content_marginbottom	= isset( $bdp_settings['download_post_content_marginbottom']) ? $bdp_settings['download_post_content_marginbottom'] : '';
	$download_post_content_paddingleft	= isset( $bdp_settings['download_post_content_paddingleft']) ? $bdp_settings['download_post_content_paddingleft'] : '';
	$download_post_content_paddingright	= isset( $bdp_settings['download_post_content_paddingright']) ? $bdp_settings['download_post_content_paddingright'] : '';
	$download_post_content_paddingtop	= isset( $bdp_settings['download_post_content_paddingtop']) ? $bdp_settings['download_post_content_paddingtop'] : '';
	$download_post_content_paddingbottom	= isset( $bdp_settings['download_post_content_paddingbottom']) ? $bdp_settings['download_post_content_paddingbottom'] : '';
	$enable_lazy_load = isset( $bdp_settings['enable_lazy_load'] ) ? $bdp_settings['enable_lazy_load'] : ''; 
	$enable_lazy_load_blur_image = isset( $bdp_settings['enable_lazy_load_blur_image'] ) ? $bdp_settings['enable_lazy_load_blur_image'] : ''; 
	$template_lazy_load_color = isset($bdp_settings['template_lazy_load_color']) ? $bdp_settings['template_lazy_load_color'] : '';
	//Post title setting
	$total_noofline 				 = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
	$template_post_content_wrap_from = isset( $bdp_settings['template_post_content_wrap_from'] ) ? $bdp_settings['template_post_content_wrap_from'] : 'normal';
	$post_length_setting 				 = isset( $bdp_settings['post_length_setting'] ) ? $bdp_settings['post_length_setting'] : '0';

	if($enable_lazy_load_blur_image == 1) { ?>
		<?php  echo esc_attr( $layout_id ); ?> .lazyload,
		 <?php  echo esc_attr( $layout_id ); ?> .lazyloading {
			 -webkit-filter: blur(10px);
			filter: blur(10px);
			transition: filter 6000ms, -webkit-filter 6000ms;
		} 
		 <?php }
		 if(!empty($template_lazy_load_color) ){?>
		 <?php echo esc_attr( $layout_id ); ?> .lazyload,
		 <?php echo esc_attr( $layout_id ); ?> .lazyloading{
			 background-color: <?php echo esc_attr( $template_lazy_load_color); ?> !important ;	 
			 transition: filter 15000ms, -webkit-filter 15000ms;
		 }
	
		<?php }

	/**
	 * Post tilte 
	 */
	?>
	h3.ui-accordion-header a span,h3.ui-accordion-header span,
	.bdp_post_content .bdp_post_title a span ,.bdp_post_content .bdp_post_title span ,
	.bdp_blog_template.blog_carousel .blog_header h2 a span,.bdp_blog_template.blog_carousel .blog_header h2 span,
	.boxy-clean .blog_header h2 a span ,.boxy-clean .blog_header h2 span ,
	.boxy .blog_header h2 a span,.boxy .blog_header h2 span,
	.bdp_blog_template h2.post-title a span,.bdp_blog_template h2.post-title span,
	.brite-post-wrapper .post-title h2 span,
	.chapter-post-wrapper .post-title a h2 span,.chapter-post-wrapper .post-title h2 span,
	.bdp_blog_template.classical .blog_header h2 a span ,.bdp_blog_template.classical .blog_header h2 span ,
	.clicky .clicky-wrap h2.post-title a span ,	.clicky .clicky-wrap h2.post-title span ,
	.bdp_blog_template.colorful_sliding .blog_header h2 a span ,.bdp_blog_template.colorful_sliding .blog_header h2 span ,
	.horizontal .post-title  a span ,	.horizontal .post-title span ,
	.cover-post .cover-blog h2.bdp_post_title a span ,	.cover-post .cover-blog h2.bdp_post_title span ,
	.bdp_blog_template.crayon_slider .blog_header h2 a span ,	.bdp_blog_template.crayon_slider .blog_header h2 span ,
	.deport .deport-wrap .deport-title-area .post-title a span ,.deport .deport-wrap .deport-title-area .post-title  span ,
	.easy-timeline-wrapper .easy-timeline .post-title a span,.easy-timeline-wrapper .easy-timeline .post-title span,
	.elina-post-wrapper .post-title a span ,.elina-post-wrapper .post-title span,
	.evolution.bdp_blog_template .post-title a span,.evolution.bdp_blog_template .post-title span ,
	.explore .blog_header .post-title a span ,.explore .blog_header .post-title span ,
	.fairy .bdp-post-image h2.post_title a span ,.fairy .bdp-post-image h2.post_title span ,
	.famous-grid .post-body-div h2.post_title a span ,.famous-grid .post-body-div h2.post_title span,
	.flip_book_3d .blog_header h2 a span , .flip_book_3d .blog_header h2 span ,
	.foodbox_blog .post-title h2 a span,.foodbox_blog .post-title h2 span ,
	.glamour-blog .glamour-inner .post-title h2 a span , .glamour-blog .glamour-inner .post-title h2 span ,
	.bdp_blog_template.glossary .blog_header h2 a span, .bdp_blog_template.glossary .blog_header h2 span ,
	.bdp_blog_template.hoverbic .blog_header .post-title a span , .bdp_blog_template.hoverbic .blog_header .post-title span ,
	.hub .blog_header h2 a span,.hub .blog_header h2 span ,
	.bdp_blog_template h2.post-title a span, .bdp_blog_template h2.post-title span,
	.blog_template.lightbreeze .blog_header h2 a span , .blog_template.lightbreeze .blog_header h2 span ,
	.masonry-timeline-wrapp .post-title a span,.masonry-timeline-wrapp .post-title span,
	.card-box-text h2 a span,.card-box-text h2 span ,
	.bdp_blog_template h2.post-title a span , .bdp_blog_template h2.post-title span,
	.bdp_blog_template.media-grid .post-body-div h2.post-title a span,.bdp_blog_template.media-grid .post-body-div h2.post-title span ,
	.minimal .minimal-content-cover .post-title a span,.minimal .minimal-content-cover .post-title span,
	.miracle_blog .post-title h2 a span , .miracle_blog .post-title h2 span,
	.diary-posthead .post-title a span,.diary-posthead .post-title span ,
	.bdp_blog_template h2.post-title a span , .bdp_blog_template h2.post-title span ,
	.news.bdp_blog_template h2 a span ,.news.bdp_blog_template h2 span ,
	.bdp_blog_template.nicy .blog_header h2 a span,.bdp_blog_template.nicy .blog_header h2 span ,
	.bdp_blog_template.offer_blog .blog-title-meta h2 a span,.bdp_blog_template.offer_blog .blog-title-meta h2 span ,
	.overlay_horizontal .post-title a span,.overlay_horizontal .post-title span ,
	.pedal_blog .post-title h2 a span,  .pedal_blog .post-title h2 span ,
	.bdp_blog_template.pretty .blog_header h2 a span , .bdp_blog_template.pretty .blog_header h2 span ,
	.quci .blog_item .blog_header h2 a span , .quci .blog_item .blog_header h2 span ,
	.bdp_blog_template.region .blog_header h2 a span , .bdp_blog_template.region .blog_header h2 span ,
	.roctangle-post-wrapper .post-content-wrapper .post-title h2 a span,.roctangle-post-wrapper .post-content-wrapper .post-title h2 span ,
	.bdp_blog_template.sallet_slider .blog_header h2 a span, .bdp_blog_template.sallet_slider .blog_header h2 span ,
	.bdp_blog_template h2.post-title a span, .bdp_blog_template h2.post-title span ,
	.blog_template.sharpen .blog_header h2 a span, .blog_template.sharpen .blog_header h2 span ,
	.bdp_blog_template h2.post-title a span , 
	.spektrum .blog_header h2 a span,.spektrum .blog_header h2 span,
	.steps h2.post-title a span ,.steps h2.post-title span,
	.story .blog_header a span ,.story .blog_header span,
	.bdp_blog_template.sunshiny_slider .blog_header h2 a span , .bdp_blog_template.sunshiny_slider .blog_header h2 span ,
	.tabbed_cover .left-side .bdp_blog_template h2.post-title a span, .tabbed_cover .left-side .bdp_blog_template h2.post-title span ,
	.bdp_blog_template.tagly .right-side-area h2.bdp_post_title a span , .bdp_blog_template.tagly .right-side-area h2.bdp_post_title span,
	.bdp_blog_template.threed_carousel .blog_header h2 a span,.bdp_blog_template.threed_carousel .blog_header h2 span,
	.blog-tickers a span,.blog-tickers span ,
	.bdp_blog_template.timeline .desc h3 a span,.bdp_blog_template.timeline .desc h3 span ,
	.winter .blog_header h2 a span , .winter .blog_header h2 span ,
	.wise_block_blog h2.post-title a span, .wise_block_blog h2.post-title span { 
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

	/* Archive Layouts */
	.accordion h3.ui-accordion-header a span,.accordion h3.ui-accordion-header span {
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
	<?php
	
 if ( isset( $pagination_type ) && ( 'load_more_btn' === $pagination_type || 'load_onscroll_btn' === $pagination_type ) ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more .button.bdp-load-more-btn:hover{
		background: <?php echo esc_attr( $loadmore_button_color ); ?>;
		color: <?php echo esc_attr( $loadmore_button_bg_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn:not(.template-3){
		background: <?php echo esc_attr( $loadmore_button_bg_color ); ?>;
		color: <?php echo esc_attr( $loadmore_button_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3{
		color: <?php echo esc_attr( $loadmore_button_color ); ?>;
		background-color: <?php echo esc_attr( $loadmore_button_bg_color); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3:hover{
		color: <?php echo esc_attr( $loadmore_button_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn,
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3:after {
		border-color: <?php echo esc_attr( $loadmore_button_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3 span:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn.template-3 span:after {
		background: <?php echo esc_attr( $loadmore_button_color 	 ); ?>;
	}


	
	<?php
 }

 	

if ( '' != $loader_color && isset( $pagination_type ) && ( 'load_more_btn' === $pagination_type || 'load_onscroll_btn' === $pagination_type ) ) { 
	?>
	.bdp-circularG,.bdp-windows8 .bdp-wBall .bdp-wInnerBall,.bdp-cssload-thecube .bdp-cssload-cube:before,
	.bdp-ball-grid-pulse > div,.bdp-square,.bdp-floatBarsG,.bdp-movingBallLineG,.bdp-movingBallG,
	.bdp-cssload-ball:after,.bdp-spinload-loading i:first-child,.bdp-csball-loading i:nth-child(1), .bdp-csball-loading i:nth-child(1):before, .bdp-csball-loading i:nth-child(1):after,
	.bdp-bigball-loading i,.bdp-bubble-loading i,.bdp-ccball-loading i:nth-child(1), .bdp-ccball-loading i:nth-child(2):before,
	.bdp-cssload-dots:nth-child(n):before,.bdp-circlewave {
		background-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-square:nth-child(3),.bdp-spinload-loading i,.bdp-bigball-loading i:nth-child(2),.bdp-bubble-loading i:nth-child(2),
	.bdp-csball-loading i:nth-child(2), .bdp-csball-loading i:nth-child(2):before, .bdp-csball-loading i:nth-child(2):after,
	.bdp-ccball-loading i:nth-child(2), .bdp-ccball-loading i:nth-child(1):before,.bdp-cssload-dots:nth-child(n):after {
		background-color: <?php echo esc_attr( $color ); ?>;
	}
	.bdp-spinload-loading i:last-child,.bdp-csball-loading i:nth-child(3), .bdp-csball-loading i:nth-child(3):before, .bdp-csball-loading i:nth-child(3):after {
		background-color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	.bdp-spinloader,.bdp-cssload-inner.bdp-cssload-three {
		border-top-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-cssload-inner.bdp-cssload-one {
		border-bottom-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-cssload-inner.bdp-cssload-two {
		border-right-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-circlewave:after {
		border-top-color: <?php echo esc_attr( $loader_color ); ?>;
		border-bottom-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-doublec-loading {
		border-bottom-color: <?php echo esc_attr( $loader_color ); ?>;
		border-left-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-doublec-loading:before {
		border-top-color: <?php echo esc_attr( $loader_color ); ?>;
		border-right-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-cssload-aim {
		border-left-color: <?php echo esc_attr( $loader_color ); ?>;
		border-right-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-doublec-loading:after,.bdp-facebook_blockG,.bdp-loader div,.bdp-cssload-ball {
		border-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	.bdp-warningGradientOuterBarG {
		border-color: <?php echo esc_attr( $loader_color ); ?>;
		background: -moz-gradient(linear,0% 0%,0% 100%,from(#fff),to(<?php echo esc_attr( $loader_color ); ?>));
		background: linear-gradient(top,#fff,<?php echo esc_attr( $loader_color ); ?>);
		background: -o-linear-gradient(top,#fff,<?php echo esc_attr( $loader_color ); ?>);
		background: -ms-linear-gradient(top,#fff,<?php echo esc_attr( $loader_color ); ?>);
		background: -webkit-linear-gradient(top,#fff,<?php echo esc_attr( $loader_color ); ?>);
		background: -moz-linear-gradient(top,#fff,<?php echo esc_attr( $loader_color ); ?>);
	}
	.bdp-cssload-heartL,.bdp-cssload-heartR,.bdp-cssload-square {
		border-color: <?php echo esc_attr( $loader_color ); ?>;
		background-color: <?php echo esc_attr( $loader_color ); ?>;
	}
	@keyframes f_fadeG{
		0%{background-color:<?php echo esc_attr( $loader_color ); ?>;}
		100%{background-color:rgb(255,255,255);}
	}
	@keyframes ballsWaveG{
		0%{background-color:<?php echo esc_attr( $loader_color ); ?>;}
		100%{background-color:rgb(255,255,255);}
	}
	@keyframes bounce_floatBarsG{
		0%{transform:scale(1);background-color:<?php echo esc_attr( $loader_color ); ?>}
		100%{transform:scale(.3);background-color:rgb(255,255,255)}
	}
	@keyframes bounce_fountainG{
		0%{transform:scale(1);background-color:<?php echo esc_attr( $loader_color ); ?>}
		100%{transform:scale(.3);background-color:rgb(255,255,255)}
	}
	@keyframes audio_wave {
		0% {height:5px;transform:translateY(0px);background:<?php echo esc_attr( $loader_color ); ?>;}
		25% {height:30px;transform:translateY(15px);background:<?php echo esc_attr( $color ); ?>;}
		50% {height:5px;transform:translateY(0px);background:<?php echo esc_attr( $loader_color ); ?>;}
		100% {height:5px;transform:translateY(0px);background:<?php echo esc_attr( $color ); ?>;}
	}
	@keyframes fadeG {
		0%{background-color:<?php echo esc_attr( $loader_color ); ?>}
		100%{background-color:rgb(255,255,255)}
	}
	@keyframes circlewave {
		0% {transform: rotate(0deg);}
		50% {transform: rotate(180deg);background:<?php echo esc_attr( $color ); ?>;}
		100% {transform: rotate(360deg);}
	}
	@keyframes circlewave_after {
		0% {border-top:10px solid #9b59b6;border-bottom:10px solid <?php echo esc_attr( $color ); ?>;}
		50% {border-top:10px solid #3498db;border-bottom:10px solid <?php echo esc_attr( $loader_color ); ?>;}
		100% {border-top:10px solid #9b59b6;border-bottom:10px solid <?php echo esc_attr( $color ); ?>;}
	}
	<?php
}
if ( 0 == $social_icon_style && 0 == $social_style ) { 
	?>
	.social-component a {border-color: <?php echo esc_attr($template_ftcolor ).'!important';?> ;color: <?php echo esc_attr( $template_ftcolor).'!important';?>; border-radius: 100%}
	<?php
}
if ( isset( $beforeloop_readmoretext ) && '' != $beforeloop_readmoretext ) { 
	?>
	.bdp_wrapper<?php echo esc_attr( $layout_id ); ?> .custom_read_more.before_loop,
	.bdp_wrapper<?php echo esc_attr( $layout_id ); ?> .custom_read_more.after_loop{
		display: inline-block;margin: 30px 0;text-align: center;width: 100%;
	}
	.bdp_wrapper<?php echo esc_attr( $layout_id ); ?> .custom_read_more a {
		transition: 0.2s all;-webkit-transition: 0.2s all;-ms-transition: 0.2s all;-o-transition: 0.2s all;display: inline-block;padding: 7px 20px;box-shadow: none;
		<?php
		if ( isset( $beforeloop_readmorebackcolor ) ) {
			?>
			background: <?php echo esc_attr( $beforeloop_readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_readmorecolor ) ) {
			?>
			color: <?php echo esc_attr( $beforeloop_readmorecolor ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_titlefontsize ) ) {
			?>
			font-size: <?php echo esc_attr( $beforeloop_titlefontsize ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_titlefontface ) ) {
			?>
			font-family: <?php echo esc_attr( $beforeloop_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_weight ) ) {
			?>
			font-weight: <?php echo esc_attr( $beforeloop_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_line_height ) ) {
			?>
			line-height: <?php echo esc_attr( $beforeloop_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_italic ) && '1' == $beforeloop_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_text_transform ) ) {
			?>
			text-transform: <?php echo esc_attr( $beforeloop_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_text_decoration ) ) {
			?>
			text-decoration: <?php echo esc_attr( $beforeloop_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $beforeloop_title_font_letter_spacing ) ) {
			?>
			letter-spacing: <?php echo esc_attr( $beforeloop_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	.bdp_wrapper<?php echo esc_attr( $layout_id ); ?> .custom_read_more a:hover {
		color: <?php echo esc_attr( $beforeloop_readmorehovercolor ); ?>;
		background: <?php echo esc_attr( $beforeloop_readmorehoverbackcolor ); ?>;
	}
	<?php
}
if ( isset( $template_alternativebackground ) && '1' == $template_alternativebackground ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .white-content .alternative-back{
		background: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.alternative-back{
		background: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution.alternative-back{
		background: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php
}
/** Next Line Read more button css */
if ( isset( $read_more_link ) && isset( $readmorebutton_on ) && '1' == $read_more_link && '2' == $readmorebutton_on ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> ;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag:hover {
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner a.read-more,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more_div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-class a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .details a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more a.more-tag {
		<?php
		if ( isset( $readmore_button_margintop ) && '' != $readmore_button_margintop ) { 
			?>
			margin-top:<?php echo esc_attr( $readmore_button_margintop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_button_marginright ) && '' != $readmore_button_marginright ) { 
			?>
			margin-right:<?php echo esc_attr( $readmore_button_marginright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_button_marginbottom ) && '' != $readmore_button_marginbottom ) { 
			?>
			margin-bottom:<?php echo esc_attr( $readmore_button_marginbottom ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_button_marginleft ) && '' != $readmore_button_marginleft ) { 
			?>
			margin-left:<?php echo esc_attr( $readmore_button_marginleft ) . 'px'; ?>;<?php } ?>
		display: inline-block;
		text-align: center;
		<?php
		if ( isset( $readmore_font_family ) ) {
			?>
			font-family: <?php echo esc_attr( $readmore_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner a.read-more,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more_div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-class a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .details a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more a.more-tag {
		font-size: <?php echo esc_attr( $readmore_fontsize ) . 'px'; ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		background:<?php echo esc_attr( $readmorebackcolor ); ?> !important;
		<?php
		if ( isset( $readmore_font_weight ) && $readmore_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $readmore_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_line_height ) && $readmore_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $readmore_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_italic ) && '1' == $readmore_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_text_transform ) && $readmore_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $readmore_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_text_decoration ) && $readmore_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $readmore_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_letter_spacing ) && $readmore_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $readmore_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	} 
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner a.read-more:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more_div a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-class a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .details a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more a.more-tag:hover {
		color:<?php echo esc_attr( $readmorehovercolor ); ?>;
		<?php
			if ( isset( $bdp_settings['template_readmore_hover_backcolor'] ) ) {
			?>
				background:<?php echo esc_attr( $bdp_settings['template_readmore_hover_backcolor'] ); ?>;
		<?php } ?>
		
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner a.read-more,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more_div a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-class a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-bottom a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .details a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .chapter-footer .post-meta {
		<?php
        if ( isset( $readmore_button_paddingtop ) && '' != $readmore_button_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>; <?php } ?>
		<?php
        if ( isset( $readmore_button_paddingbottom ) && '' != $readmore_button_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_button_paddingright ) && '' != $readmore_button_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_button_paddingleft ) && '' != $readmore_button_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read_more_div,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-class,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-bottom,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .details,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.timeline .read_more {
		text-align: <?php echo esc_attr( $readmorebuttonalignment ); ?>;
		display: inline-block;width: 100%;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .read-more-wrapper {
		text-align: <?php echo esc_attr( $readmorebuttonalignment ); ?>;
		width: 100%;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news-wrapper .post-bottom{
		text-align: <?php echo esc_attr( $readmorebuttonalignment ); ?>;
		display: inline-block;width: auto;
	}
	<?php if ( isset( $readmorebuttonalignment ) && 'right' === $readmorebuttonalignment ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .deport.even_class .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .navia.even_class .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .read_more_div,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .read-more{
			text-align: left !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebuttonalignment ) && 'left' === $readmorebuttonalignment ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .deport.even_class .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .navia.even_class .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .read_more_div,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .read-more-div,
		<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .read-more{
			text-align: right !important;
		}
	<?php } ?>
<?php } ?>
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-pinterest-share-image a {
	background-image: url("<?php echo esc_url( plugins_url() ); ?>/blog-designer-pro/public/images/pinterest.png");
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content a,
<?php echo esc_attr( $layout_id ); ?> .blog_template a.more-tag {
	color: <?php echo esc_attr( $color ); ?>;
}
<?php
// Same line read more button css.
if ( isset( $read_more_link ) && isset( $readmorebutton_on ) && '1' == $read_more_link && '1' == $readmorebutton_on ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag, <?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag {
		margin-left:5px;padding:0;border:none;background:none;
		text-align: <?php echo esc_attr( $readmorebuttonalignment); ?>
	}
	<?php
}
/** Easy Digital Download Setting Css */
if ( 'easy-digital-downloads/easy-digital-downloads.php' ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_price_wrapper {
		<?php
		if ( isset( $bdp_edd_price_alignment ) && '' != $bdp_edd_price_alignment ) { 
			?>
			text-align: <?php echo esc_attr( $bdp_edd_price_alignment ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_price_wrapper .edd_price {
		<?php
		if ( isset( $bdp_edd_price_paddingleft ) && '' != $bdp_edd_price_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $bdp_edd_price_paddingleft ) . 'px'; ?>;<?php } ?>
		<?php
        if ( isset( $bdp_edd_price_paddingright ) && '' != $bdp_edd_price_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $bdp_edd_price_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_paddingtop ) && '' != $bdp_edd_price_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $bdp_edd_price_paddingtop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_paddingbottom ) && '' != $bdp_edd_price_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $bdp_edd_price_paddingbottom ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_price_wrapper .edd_price span {padding:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_price_wrapper .edd_price,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_price_wrapper .edd_price span {
		color: <?php echo esc_attr( $bdp_edd_price_color ); ?> !important;
		font-size: <?php echo esc_attr( $bdp_edd_pricefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_edd_pricefontface ) && '' != $bdp_edd_pricefontface ) { 
			?>
			font-family: <?php echo esc_attr( $bdp_edd_pricefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_font_weight ) ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_edd_price_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_font_line_height ) ) {
			?>
			line-height: <?php echo esc_attr( $bdp_edd_price_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_font_italic ) && '1' == $bdp_edd_price_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_font_letter_spacing ) ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_edd_price_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_price_font_text_decoration ) ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_edd_price_font_text_decoration ); ?>;<?php } ?>
		width: auto;word-break: break-all;
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area .bdp_edd_download_buy_button a.bdp_edd_view_button,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button a.bdp_edd_view_button,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd_go_to_checkout,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart-label,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart {
		<?php
		if ( isset( $bdp_edd_addtocart_button_fontface ) && '' != $bdp_edd_addtocart_button_fontface ) { 
			?>
			font-family: <?php echo esc_attr( $bdp_edd_addtocart_button_fontface ); ?>;<?php } ?>
		font-size: <?php echo esc_attr( $bdp_edd_addtocart_button_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_edd_addtocart_button_font_weight ) ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_edd_addtocart_button_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $display_edd_addtocart_button_line_height ) ) {
			?>
			line-height: <?php echo esc_attr( $display_edd_addtocart_button_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_font_italic ) && 1 == $bdp_edd_addtocart_button_font_italic ) { 
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_letter_spacing ) ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_edd_addtocart_button_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_font_text_transform ) ) {
			?>
			text-transform: <?php echo esc_attr( $bdp_edd_addtocart_button_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_font_text_decoration ) ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_edd_addtocart_button_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_textcolor ) ) {
			?>
			color: <?php echo esc_attr( $bdp_edd_addtocart_textcolor ); ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button a.bdp_edd_view_button,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd_go_to_checkout,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart { 
		<?php
		if ( isset( $bdp_edd_addtocart_backgroundcolor ) ) {
			?>
			background-color: <?php echo esc_attr( $bdp_edd_addtocart_backgroundcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_borderleft ) && '' != $bdp_edd_addtocartbutton_borderleft ) { 
			?>
			border-left:<?php echo esc_attr( $bdp_edd_addtocartbutton_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_borderleftcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_borderright ) && '' != $bdp_edd_addtocartbutton_borderright ) { 
			?>
			border-right:<?php echo esc_attr( $bdp_edd_addtocartbutton_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_borderrightcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_bordertop ) && '' != $bdp_edd_addtocartbutton_bordertop ) { 
			?>
			border-top:<?php echo esc_attr( $bdp_edd_addtocartbutton_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_bordertopcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_borderbuttom ) && '' != $bdp_edd_addtocartbutton_borderbuttom ) { 
			?>
			border-bottom:<?php echo esc_attr( $bdp_edd_addtocartbutton_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_borderbottomcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $display_edd_addtocart_button_border_radius ) && '' != $display_edd_addtocart_button_border_radius ) { 
			?>
			border-radius:<?php echo esc_attr( $display_edd_addtocart_button_border_radius ) . 'px'; ?>;<?php } ?>
		<?php if ( isset( $bdp_edd_addtocartbutton_padding_topbottom ) && '' != $bdp_edd_addtocartbutton_padding_topbottom ) {  ?>
			padding-top: <?php echo esc_attr( $bdp_edd_addtocartbutton_padding_topbottom ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $bdp_edd_addtocartbutton_padding_topbottom ) . 'px'; ?>;
		<?php } ?>
		<?php if ( isset( $bdp_edd_addtocartbutton_padding_leftright ) && '' != $bdp_edd_addtocartbutton_padding_leftright ) {  ?>
			padding-left: <?php echo esc_attr( $bdp_edd_addtocartbutton_padding_leftright ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $bdp_edd_addtocartbutton_padding_leftright ) . 'px'; ?>;
		<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_box_shadow_color ) && '' != $bdp_edd_addtocart_button_box_shadow_color ) { 
			?>
			box-shadow:<?php echo esc_attr( $bdp_edd_addtocart_button_top_box_shadow ) . 'px ' . esc_attr( $bdp_edd_addtocart_button_right_box_shadow ) . 'px ' . esc_attr( $bdp_edd_addtocart_button_bottom_box_shadow ) . 'px ' . esc_attr( $bdp_edd_addtocart_button_left_box_shadow ) . 'px ' . esc_attr( $bdp_edd_addtocart_button_box_shadow_color ); ?> !important;
		<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button {
		<?php
		if ( isset( $bdp_edd_addtocartbutton_alignment ) && '' != $bdp_edd_addtocartbutton_alignment ) { 
			?>
			text-align: <?php echo esc_attr( $bdp_edd_addtocartbutton_alignment ); ?>;<?php } ?>
		<?php if ( isset( $bdp_edd_addtocartbutton_margin_topbottom ) && '' != $bdp_edd_addtocartbutton_margin_topbottom ) {  ?>
			margin-top: <?php echo esc_attr( $bdp_edd_addtocartbutton_margin_topbottom ) . 'px'; ?>;
			margin-bottom: <?php echo esc_attr( $bdp_edd_addtocartbutton_margin_topbottom ) . 'px'; ?>;
		<?php } ?>
		<?php if ( isset( $bdp_edd_addtocartbutton_margin_leftright ) && '' != $bdp_edd_addtocartbutton_margin_leftright ) {  ?>
			margin-left: <?php echo esc_attr( $bdp_edd_addtocartbutton_margin_leftright ) . 'px'; ?>;
			margin-right:<?php echo esc_attr( $bdp_edd_addtocartbutton_margin_leftright ) . 'px'; ?>
		<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button a.bdp_edd_view_button:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd_go_to_checkout:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart:hover {
		<?php
		if ( isset( $bdp_edd_addtocart_hover_backgroundcolor ) && '' != $bdp_edd_addtocart_hover_backgroundcolor ) { 
			?>
			background-color: <?php echo esc_attr( $bdp_edd_addtocart_hover_backgroundcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_hover_borderleft ) && '' != $bdp_edd_addtocartbutton_hover_borderleft ) { 
			?>
			border-left:<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderleftcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_hover_borderright ) && '' != $bdp_edd_addtocartbutton_hover_borderright ) { 
			?>
			border-right:<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderrightcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_hover_bordertop ) && '' != $bdp_edd_addtocartbutton_hover_bordertop ) { 
			?>
			border-top:<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_hover_bordertopcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocartbutton_hover_borderbuttom ) && '' != $bdp_edd_addtocartbutton_hover_borderbuttom ) { 
			?>
			border-bottom:<?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_edd_addtocartbutton_hover_borderbottomcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $display_edd_addtocart_button_border_hover_radius ) && '' != $display_edd_addtocart_button_border_hover_radius ) { 
			?>
			border-radius:<?php echo esc_attr( $display_edd_addtocart_button_border_hover_radius ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_edd_addtocart_button_hover_box_shadow_color ) && '' != $bdp_edd_addtocart_button_hover_box_shadow_color ) { 
			?>
			box-shadow: <?php echo esc_attr( $bdp_edd_addtocart_button_hover_top_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_edd_addtocart_button_hover_right_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_edd_addtocart_button_hover_bottom_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_edd_addtocart_button_hover_left_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_edd_addtocart_button_hover_box_shadow_color ); ?> !important <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart:hover .edd-add-to-cart-label,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button a.bdp_edd_view_button:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd_go_to_checkout:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_edd_download_buy_button .edd-add-to-cart:hover {
		<?php
		if ( isset( $bdp_edd_addtocart_text_hover_color ) && $bdp_edd_addtocart_text_hover_color ) {
			?>
			color: <?php echo esc_attr( $bdp_edd_addtocart_text_hover_color ); ?> !important;<?php } ?>
	}
	<?php if ( isset( $bdp_edd_price_alignment ) && 'left' === $bdp_edd_price_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_edd_price_wrapper,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_edd_price_wrapper,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_edd_price_wrapper,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_edd_price_wrapper,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_edd_price_wrapper,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_edd_price_wrapper {text-align:right}
	<?php } ?>
	<?php if ( isset( $bdp_edd_price_alignment ) && 'right' === $bdp_edd_price_alignment ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_edd_price_wrapper,
		<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_edd_price_wrapper,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_edd_price_wrapper,
		<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_edd_price_wrapper,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_edd_price_wrapper,
		<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_edd_price_wrapper {text-align:left}
	<?php } ?>
	<?php if ( isset( $bdp_edd_addtocartbutton_alignment ) && 'left' === $bdp_edd_addtocartbutton_alignment ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_edd_download_buy_button {text-align:right}
	<?php } ?>
	<?php if ( isset( $bdp_edd_addtocartbutton_alignment ) && 'right' === $bdp_edd_addtocartbutton_alignment ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_edd_download_buy_button,
		<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_edd_download_buy_button {text-align:left}
	<?php } ?>
<?php } ?>
/** Pagination Css */
<?php if ( isset( $pagination_type ) && 'paged' === $pagination_type ) { ?>
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-2 .paging-navigation ul.page-numbers li a.next:before{
		/* content: '<?php echo esc_attr( $next_button_text ); ?>';     */
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-2 .paging-navigation ul.page-numbers li a.prev:after{
		/* content: '<?php echo esc_attr( $previous_button_text ); ?>'; */
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-2 .paging-navigation ul.page-numbers li a.prev:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-2 .paging-navigation ul.page-numbers li a.next:before {
		visibility: visible;
		padding: 6px 11px;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li a.prev,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li a.next {
		visibility:visible !important; padding:7px; top:2px;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li span.current{
		background: <?php echo esc_attr( $pagination_active_background_color ); ?>;
		color: <?php echo esc_attr( $pagination_text_active_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next:before,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev:before
	{
		background: <?php echo esc_attr( $pagination_background_color ); ?>;
		color: <?php echo esc_attr( $pagination_text_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next:hover:before,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev:hover:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers:focus,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.next:focus,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a.prev:focus{
		color: <?php echo esc_attr( $pagination_text_hover_color ); ?> ;
		background: <?php echo esc_attr( $pagination_background_hover_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-1 .paging-navigation ul.page-numbers li a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-1 .paging-navigation ul.page-numbers li span.current{
		border: none;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li span.current,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-4 .paging-navigation ul.page-numbers li span.current,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box .paging-navigation ul.page-numbers li span.current,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li a.next:before,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li a.prev:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-3 .paging-navigation ul.page-numbers li a.page-numbers{
		border:1px solid <?php echo esc_attr( $pagination_border_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-4 .paging-navigation ul.page-numbers li span.current:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-4 .paging-navigation ul.page-numbers li span.current:before{
		border-top:2px solid <?php echo esc_attr( $pagination_active_border_color ); ?>;
		border-left:1px solid <?php echo esc_attr( $pagination_active_border_color ); ?>;
		border-right:1px solid <?php echo esc_attr( $pagination_active_border_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-4 .paging-navigation ul.page-numbers li span.current{
		border-top:2px solid <?php echo esc_attr( $pagination_active_border_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .wl_pagination_box.template-4 .paging-navigation ul.page-numbers li a.page-numbers{
		border:1px solid <?php echo esc_attr( $pagination_border_color ); ?> !important;
	} 
	<?php
}
/* Social Share Style Css  */
if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) { 
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component a.social-share-default{
		padding:0;border:0;box-shadow: none;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.large a.social-share-default{
		padding: 0;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component{
		/* float:left;margin-top:10px;width:100%; */
		display: inline-block;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template .social-component > a {
		margin: 10px 8px 0 0;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.left_position .social-share {
		float: left;
	}
	<?php
} elseif ( isset( $bdp_settings['social_style'] ) && 2 == $bdp_settings['social_style'] ) { 
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.extra_small .social-share {
		display:inline-block;margin:10px 0 0;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.extra_small a {
		font-size:13px;height:27px;line-height:20px;margin:0px 2px 0 !important;padding:7px 0;width:27px;
	}
	<?php
}
?>
<?php echo esc_attr( $layout_id ); ?>.news_cover .bdp_blog_template.news-wrapper .social-component {width:auto}
/** Social Share count position */
<?php
if ( isset( $bdp_settings['social_count_position'] ) && 'bottom' === $bdp_settings['social_count_position'] ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count {
		background-color: transparent;border: 1px solid #ddd;border-radius: 5px;clear: both;float: left;line-height: 1;margin: 10px 0 0;padding: 5px 4%;text-align: center;width: 38px;position: relative;word-wrap: break-word;height: auto;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.large .social-share .count {width:45px}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count:before {border-bottom:8px solid #ddd;border-left:8px solid rgba(0,0,0,0);border-right:8px dashed rgba(0,0,0,0);content:"";left:0;margin:0 auto;position:absolute;right:0;top:-8px;width:0}
	<?php
} elseif ( isset( $bdp_settings['social_count_position'] ) && 'top' === $bdp_settings['social_count_position'] ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count {
		background-color: transparent;border: 1px solid #ddd;border-radius:5px;clear:both;float:none;line-height:1;margin:0 0 10px 0;padding:5px 4%;text-align:center;width:38px;position:relative;height:auto;
		color:<?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.large .social-share .count{width:45px}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .social-component .social-share .count{float:none}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count:before{border-top:8px solid #ddd;border-left:8px solid rgba(0,0,0,0);border-right:8px dashed rgba(0,0,0,0);content:"";left:0;margin:0 auto;position:absolute;right:0;bottom:-9px;width:0}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template .social-component > a{display:inline-block;margin-bottom:0;float:none;vertical-align:bottom}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share{display:inline-block;float:none}
	<?php
} else {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count {color:<?php echo esc_attr( $contentcolor ); ?>;background-color:transparent;border:1px solid #ddd;border-radius:5px;float:right;line-height:20px;margin:0 0 0 10px;padding:8px 0;text-align:center;width:38px;height:38px;position:relative;box-sizing:border-box}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.large .social-share .count{margin:0 0 0 7px;padding:12px 0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.large .social-share .count:before{top:30%}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component .social-share .count:before{border-top:8px solid rgba(0,0,0,0);border-bottom:8px dashed rgba(0,0,0,0);border-right:8px solid #ddd;content:"";margin:0 auto;position:absolute;left:-8px;top:27%;width:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component.extra_small .social-share .count:before{border-top:6px solid rgba(0,0,0,0);border-bottom:8px dashed rgba(0,0,0,0);border-right:6px solid #ddd;content:"";left:-33px;margin:0 auto;position:absolute;right:0;top:27%;width:0}
	<?php
}
?>
/** Post Title */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-title,
<?php echo esc_attr( $layout_id ); ?> .leest-wapper .card-box .card-box-text h2,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.post-title,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .blog_header h2,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.blog_header,
<?php echo esc_attr( $layout_id ); ?> .bdp_post_title,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-title h2,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_title,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .entry-title {
		text-align: <?php echo esc_attr( $title_alignment ); ?> !important;
		text-shadow: <?php echo esc_attr($bdp_title_top_box_shadow) .' '. esc_attr($bdp_title_right_box_shadow) .' '. esc_attr($bdp_title_bottom_box_shadow).' '. esc_attr($bdp_title_box_shadow_color); ?>;
		margin-left: <?php echo esc_attr($post_title_marginleft).'px'; ?>;
		margin-right: <?php echo esc_attr($post_title_marginright).'px'; ?>;
		margin-top: <?php echo esc_attr($post_title_margintop).'px !important'; ?>;
		margin-bottom: <?php echo esc_attr($post_title_marginbottom).'px !important'; ?>;
		padding-left: <?php echo esc_attr($post_title_paddingleft).'px'; ?>;
		padding-right: <?php echo esc_attr($post_title_paddingright).'px'; ?>;
		padding-top: <?php echo esc_attr($post_title_paddingtop).'px'; ?>;
		padding-bottom: <?php echo esc_attr($post_title_paddingbottom).'px'; ?>;
}

<?php if( 'center' == $title_alignment ) { ?>
	.bdp_blog_template.fairy .bdp-post-image .post_title {
		right: 40%;
	}
<?php } ?>
<?php if( 'right' == $title_alignment ) { ?>
	.bdp_blog_template.fairy .bdp-post-image .post_title {
		right: 0;
	}
<?php } ?>
<?php echo esc_attr( $layout_id ); ?> .blog-tickers ul li,
<?php echo esc_attr( $layout_id ); ?> .blog-tickers a {
	margin-left: <?php echo esc_attr($post_title_marginleft).'px'; ?>;
	margin-right: <?php echo esc_attr($post_title_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($post_title_margintop).'px !important'; ?>;
	margin-bottom: <?php echo esc_attr($post_title_marginbottom).'px !important'; ?>;
	padding-left: <?php echo esc_attr($post_title_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($post_title_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($post_title_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($post_title_paddingbottom).'px'; ?>;
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.post-title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .blog_header h2 a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.blog_header a, 
<?php echo esc_attr( $layout_id ); ?> .bdp_post_title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-title h2 a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .entry-title a,
.bdp_blog_template .post_content_wrap .post-title > a {
		text-shadow: <?php echo esc_attr($bdp_title_top_box_shadow) .' '. esc_attr($bdp_title_right_box_shadow) .' '. esc_attr($bdp_title_bottom_box_shadow).' '. esc_attr($bdp_title_box_shadow_color); ?>;
		margin-left: <?php echo esc_attr($post_title_marginleft).'px'; ?>;
		margin-right: <?php echo esc_attr($post_title_marginright).'px'; ?>;
		margin-top: <?php echo esc_attr($post_title_margintop).'px'; ?>;
		margin-bottom: <?php echo esc_attr($post_title_marginbottom).'px'; ?>;
		padding-left: <?php echo esc_attr($post_title_paddingleft).'px'; ?>;
		padding-right: <?php echo esc_attr($post_title_paddingright).'px'; ?>;
		padding-top: <?php echo esc_attr($post_title_paddingtop).'px'; ?>;
		padding-bottom: <?php echo esc_attr($post_title_paddingbottom).'px'; ?>;
}

/*Product Post Title*/
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template h2.post-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .blog_header h2,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template h2.blog_header,
<?php echo esc_attr( $layout_id ); ?> .bdp_post_title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-title h2,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .entry-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .blog-tickers ul li {
		margin-left: <?php echo esc_attr($product_post_title_marginleft).'px'; ?>;
		margin-right: <?php echo esc_attr($product_post_title_marginright).'px'; ?>;
		margin-top: <?php echo esc_attr($product_post_title_margintop).'px'; ?>;
		margin-bottom: <?php echo esc_attr($product_post_title_marginbottom).'px'; ?>;
		padding-left: <?php echo esc_attr($product_post_title_paddingleft).'px'; ?>;
		padding-right: <?php echo esc_attr($product_post_title_paddingright).'px'; ?>;
		padding-top: <?php echo esc_attr($product_post_title_paddingtop).'px'; ?>;
		padding-bottom: <?php echo esc_attr($product_post_title_paddingbottom).'px'; ?>;
}
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-title a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .blog_header h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template h2.blog_header a, 
<?php echo esc_attr( $layout_id ); ?> .bdp_post_title a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-title h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_title a,
.bdp_archive_product_template .post_content_wrap .post-title > a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .entry-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .blog-tickers a {
		margin-left: <?php echo esc_attr($product_post_title_marginleft).'px'; ?>;
		margin-right: <?php echo esc_attr($product_post_title_marginright).'px'; ?>;
		margin-top: <?php echo esc_attr($product_post_title_margintop).'px'; ?>;
		margin-bottom: <?php echo esc_attr($product_post_title_marginbottom).'px'; ?>;
		padding-left: <?php echo esc_attr($product_post_title_paddingleft).'px'; ?>;
		padding-right: <?php echo esc_attr($product_post_title_paddingright).'px'; ?>;
		padding-top: <?php echo esc_attr($product_post_title_paddingtop).'px'; ?>;
		padding-bottom: <?php echo esc_attr($product_post_title_paddingbottom).'px'; ?>;
}

/*Download Post Title*/
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template h2.post-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .blog_header h2,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template h2.blog_header,
.bdp_post_title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .entry-title h2,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-title h2,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .entry-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .blog-tickers ul li {
	margin-left: <?php echo esc_attr($download_post_title_marginleft).'px'; ?>;
	margin-right: <?php echo esc_attr($download_post_title_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($download_post_title_margintop).'px'; ?>;
	margin-bottom: <?php echo esc_attr($download_post_title_marginbottom).'px'; ?>;
	padding-left: <?php echo esc_attr($download_post_title_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($download_post_title_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($download_post_title_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($download_post_title_paddingbottom).'px'; ?>;
}
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-title a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .blog_header h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template h2.blog_header a, 
.bdp_post_title a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-title h2 a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_title a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content_wrap .post-title > a,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content_wrap .entry-title,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .blog-tickers a {
	margin-left: <?php echo esc_attr($download_post_title_marginleft).'px'; ?>;
	margin-right: <?php echo esc_attr($download_post_title_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($download_post_title_margintop).'px'; ?>;
	margin-bottom: <?php echo esc_attr($download_post_title_marginbottom).'px'; ?>;
	padding-left: <?php echo esc_attr($download_post_title_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($download_post_title_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($download_post_title_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($download_post_title_paddingbottom).'px'; ?>;
}
<?php if ( isset( $title_alignment ) && 'left' === $title_alignment ) { ?>
	<?php echo esc_attr( $layout_id ); ?> .deport.even_class h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .post-title,
	<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .clicky-wrap h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .navia.even_class h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap h2{text-align: right !important}
<?php } elseif ( isset( $title_alignment ) && 'right' === $title_alignment ) { ?>
	<?php echo esc_attr( $layout_id ); ?> .deport.even_class h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .post-title ,
	<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_star_wrap,
	<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .clicky-wrap h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .navia.even_class h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap h2{text-align: left !important}
<?php } ?>
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .entry-title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.post-title a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.post-title {
	color: <?php echo esc_attr( $titlecolor ); ?>;
	font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	<?php
	if ( isset( $template_titlefontface ) && $template_titlefontface ) {
		?>
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
		?>
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
		?>
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
		?>
		font-style: <?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
		?>
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
		?>
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
		?>
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.box-template .blog_header .post-title{
	<?php
	if ( isset( $titlebackcolor ) && $titlebackcolor ) {
		?>
		background: <?php echo esc_attr( $titlebackcolor ); ?>;<?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h2.post-title a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .entry-title a:hover{
	color:<?php echo esc_attr( $titlehovercolor ); ?> !important;
}
/*Post Content CSS*/
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-content,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-content p,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content p,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content-inner p,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .postcontent {
	text-shadow: <?php echo esc_attr($bdp_content_top_box_shadow) .' '. esc_attr($bdp_content_right_box_shadow) .' '. esc_attr($bdp_content_bottom_box_shadow).' '. esc_attr($bdp_content_box_shadow_color); ?>;
	margin-left: <?php echo esc_attr($post_content_marginleft) .'px'; ?>;
	margin-right: <?php echo esc_attr($post_content_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($post_content_margintop).'px'; ?>;
	margin-bottom: <?php echo esc_attr($post_content_marginbottom).'px'; ?>;
	padding-left: <?php echo esc_attr($post_content_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($post_content_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($post_content_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($post_content_paddingbottom).'px'; ?>;
}
/*Product Post Content CSS*/
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-content,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post-content p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_content,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_content p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_content-inner,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .post_content-inner p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template .postcontent {
	margin-left: <?php echo esc_attr($product_post_content_marginleft) .'px'; ?>;
	margin-right: <?php echo esc_attr($product_post_content_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($product_post_content_margintop).'px'; ?>;
	margin-bottom: <?php echo esc_attr($product_post_content_marginbottom).'px'; ?>;
	padding-left: <?php echo esc_attr($product_post_content_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($product_post_content_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($product_post_content_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($product_post_content_paddingbottom).'px'; ?>;
}
/* Download Post Content CSS*/
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-content,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post-content p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content-inner,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .post_content-inner p,
<?php echo esc_attr( $layout_id ); ?>.bdp_archive_download_product_template .bdp_blog_template .postcontent {
	margin-left: <?php echo esc_attr($download_post_content_marginleft) .'px'; ?>;
	margin-right: <?php echo esc_attr($download_post_content_marginright).'px'; ?>;
	margin-top: <?php echo esc_attr($download_post_content_margintop).'px'; ?>;
	margin-bottom: <?php echo esc_attr($download_post_content_marginbottom).'px'; ?>;
	padding-left: <?php echo esc_attr($download_post_content_paddingleft).'px'; ?>;
	padding-right: <?php echo esc_attr($download_post_content_paddingright).'px'; ?>;
	padding-top: <?php echo esc_attr($download_post_content_paddingtop).'px'; ?>;
	padding-bottom: <?php echo esc_attr($download_post_content_paddingbottom).'px'; ?>;
}
/** Apply content Font Family */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content_wrap,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content p,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .label_featured_post,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .label_featured_post span,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_summary_outer,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_hentry,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .blog_footer,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-comment {
	color: <?php echo esc_attr( $contentcolor ); ?>;
	<?php
	if ( isset( $content_font_family ) && '' != $content_font_family ) { 
		?>
		font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
}
/** Apply Content color */
<?php echo esc_attr( $layout_id ); ?> .deport-category-text.categories_link{
	color: <?php echo esc_attr( $contentcolor ); ?>
}
/** Font Awesome apply */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_hentry.fas {font-family: 'Font Awesome 5 Free'}
/** Apply link color */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .categories a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .category-link a,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .custom-categories a,
<?php echo esc_attr( $layout_id ); ?> .deport-category-text,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .metacomments a,
<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox a span.bdp-count {
	color:<?php echo esc_attr( $color ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template .social-component a {
	border-color:<?php echo esc_attr( $color ); ?>;
	color:<?php echo esc_attr( $color ); ?>;
}
/** Apply Link Hover Color */
<?php echo esc_attr( $layout_id ); ?> .blog_template .upper_image_wrapper.bdp_link_post_format a:hover{
	color: <?php echo esc_attr( $linkhovercolor ); ?>;
}
/** Apply Content Setting */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .label_featured_post,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .label_featured_post span,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content p {
	font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	<?php
	if ( isset( $content_font_weight ) && $content_font_weight ) {
		?>
		font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_line_height ) && $content_font_line_height ) {
		?>
		line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
		?>
		font-style: <?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
		?>
		text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
		?>
		text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
		?>
		letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .upper_image_wrapper blockquote,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .upper_image_wrapper blockquote p{
	<?php if(isset($content_fontsize)) { ?>font-size: <?php echo esc_attr( $content_fontsize ) + 3 . 'px'; ?>;<?php } ?>
	font-family: <?php echo esc_attr( $content_font_family ); ?>;
	color: <?php echo esc_attr( $contentcolor ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .upper_image_wrapper blockquote:before{
	<?php if(isset($content_fontsize)) { ?>font-size: <?php echo esc_attr( $content_fontsize ) + 5 . 'px'; ?>;<?php } ?>
	color: <?php echo esc_attr( $contentcolor ); ?>
}
<?php echo esc_attr( $layout_id ); ?> .blog_template .upper_image_wrapper.bdp_link_post_format a{
	<?php if(isset($content_fontsize)) { ?>font-size: <?php echo esc_attr( $content_fontsize ) + 5 . 'px'; ?>;<?php } ?>
	font-family: <?php echo esc_attr( $content_font_family ); ?>;
	background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $background, 0.9 ) ); ?>;
	color: <?php echo esc_attr( $color ); ?>;
}
/** Template color */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .upper_image_wrapper blockquote{
	background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $background, 0.9 ) ); ?>;
	border-color: <?php echo esc_attr( $templatecolor ); ?>;
}
/** Author Archive Settings */
<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div {
	background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
}
.bdp_archive <?php echo esc_attr( $layout_id ); ?> .author-avatar-div .author_content .author {
	color: <?php echo esc_attr( $titlecolor ); ?>;
	font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	<?php
	if ( isset( $template_titlefontface ) && $template_titlefontface ) {
		?>
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
		?>
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
		?>
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
		?>
		font-style: <?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
		?>
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
		?>
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
		?>
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
}
/** Woocommerce Layout Settings */
<?php
if ( ( Bdp_Woocommerce::is_woocommerce_plugin() || class_exists( 'woocommerce' ) ) && ( ! is_archive() || is_product_tag() || is_product_category() ) ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap {
		<?php
		if ( isset( $bdp_pricetext_alignment ) && ! empty( $bdp_pricetext_alignment ) ) {
			?>
			text-align: <?php echo esc_attr( $bdp_pricetext_alignment ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.right-top span.onsale{right: 0 !important;left: auto !important}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-bottom span.onsale{top: auto !important;bottom:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.right-bottom span.onsale{right: 0;left: auto !important;bottom:0;top:auto !important}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price del .woocommerce-Price-amount {text-decoration: line-through} 
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.right-top span.onsale{top:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-top span.onsale {left:0;top:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale:after {content:'' !important;border:none !important}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .star-rating {overflow:hidden;position:relative;height:1em;line-height:1;font-size:1em;width:5.4em;font-family:star}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_star_wrap,
	<?php echo esc_attr( $layout_id ); ?> .blog-start-rating-text {
		<?php
		if ( isset( $bdp_star_rating_alignment ) && '' != $bdp_star_rating_alignment ) { 
			?>
			justify-content: <?php echo esc_attr( $bdp_star_rating_alignment ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_paddingleft ) && '' != $bdp_star_rating_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $bdp_star_rating_paddingleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_paddingright ) && '' != $bdp_star_rating_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $bdp_star_rating_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_paddingtop ) && '' != $bdp_star_rating_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $bdp_star_rating_paddingtop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_paddingbottom ) && '' != $bdp_star_rating_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $bdp_star_rating_paddingbottom ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_star_wrap .star-rating {
		<?php
		if ( isset( $bdp_star_rating_marginleft ) && '' != $bdp_star_rating_marginleft ) { 
			?>
			margin-left: <?php echo esc_attr( $bdp_star_rating_marginleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_marginright ) && '' != $bdp_star_rating_marginright ) { 
			?>
			margin-right: <?php echo esc_attr( $bdp_star_rating_marginright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_margintop ) && '' != $bdp_star_rating_margintop ) { 
			?>
			margin-top: <?php echo esc_attr( $bdp_star_rating_margintop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_star_rating_marginbottom ) && '' != $bdp_star_rating_marginbottom ) { 
			?>
			margin-bottom: <?php echo esc_attr( $bdp_star_rating_marginbottom ) . 'px'; ?>;<?php } ?>
	}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .star-rating {line-height:1;font-size:1em;font-family:star}
	<?php echo esc_attr( $layout_id ); ?> .star-rating {float:none}
	<?php echo esc_attr( $layout_id ); ?> .star-rating:before {
		color: 
		<?php
		if ( isset( $bdp_star_rating_color ) && '' != $bdp_star_rating_color ) { 
			echo esc_attr( $bdp_star_rating_color );
		} else {
			echo esc_attr( $contentcolor );
		}
		?>
		;
		background: 
		<?php
		if ( isset( $bdp_star_rating_bg_color ) && '' != $bdp_star_rating_bg_color ) { 
			echo esc_attr( $bdp_star_rating_bg_color );
		} 
		?>
		;
	}
	<?php echo esc_attr( $layout_id ); ?> .star-rating span {
		background: 
		<?php
		if ( isset( $bdp_star_rating_bg_color ) && '' != $bdp_star_rating_bg_color ) { 
			echo esc_attr( $bdp_star_rating_bg_color );
		} else {
			echo esc_attr( $color );
		}
		?>
		;
	}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .star-rating:before {
		content:'\73\73\73\73\73';float:left;top:0;left:0;position:absolute;
	}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .star-rating span {
		overflow:hidden;float:left;top:0;left:0;position:absolute;padding-top:1.5em;
	}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .star-rating span:before {content:'\53\53\53\53\53';top:0;position:absolute;left:0}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale {z-index:1 !important}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale {min-height:0;min-width:0}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale{
		position:absolute;text-align:center;left:0;z-index:1 !important;color:#fff;
	}
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-bottom span.onsale,
	body:not(.woocommerce) <?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-top span.onsale,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-bottom span.onsale,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap.left-top span.onsale {
		right: auto !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_sale_wrap span.onsale {
		color: <?php echo esc_attr( $bdp_sale_tagtextcolor ); ?> !important;
		font-size: <?php echo esc_attr( $bdp_sale_tagfontsize ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_sale_tagfontface ) && '' != $bdp_sale_tagfontface ) { 
			?>
			font-family: <?php echo esc_attr( $bdp_sale_tagfontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_weight ) && '' != $bdp_sale_tag_font_weight ) { 
			?>
			font-weight: <?php echo esc_attr( $bdp_sale_tag_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_line_height ) && '' != $bdp_sale_tag_font_line_height ) { 
			?>
			line-height: <?php echo esc_attr( $bdp_sale_tag_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_italic ) && '1' == $bdp_sale_tag_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_letter_spacing ) && '' != $bdp_sale_tag_font_letter_spacing ) { 
			?>
			letter-spacing: <?php echo esc_attr( $bdp_sale_tag_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_text_transform ) && '' != $bdp_sale_tag_font_text_transform ) { 
			?>
			text-transform: <?php echo esc_attr( $bdp_sale_tag_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_font_text_decoration ) && '' != $bdp_sale_tag_font_text_decoration ) { 
			?>
			text-decoration: <?php echo esc_attr( $bdp_sale_tag_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagbgcolor ) && '' != $bdp_sale_tagbgcolor ) { 
			?>
			background-color: <?php echo esc_attr( $bdp_sale_tagbgcolor ); ?>; <?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_marginleft ) && '' != $bdp_sale_tagtext_marginleft ) { 
			?>
			margin-left: <?php echo esc_attr( $bdp_sale_tagtext_marginleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_marginright ) && '' != $bdp_sale_tagtext_marginright ) { 
			?>
			margin-right: <?php echo esc_attr( $bdp_sale_tagtext_marginright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_margintop ) && '' != $bdp_sale_tagtext_margintop ) { 
			?>
			margin-top: <?php echo esc_attr( $bdp_sale_tagtext_margintop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_marginbottom ) && '' != $bdp_sale_tagtext_marginbottom ) { 
			?>
			margin-bottom: <?php echo esc_attr( $bdp_sale_tagtext_marginbottom ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_paddingleft ) && '' != $bdp_sale_tagtext_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $bdp_sale_tagtext_paddingleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_paddingright ) && '' != $bdp_sale_tagtext_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $bdp_sale_tagtext_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_paddingtop ) && '' != $bdp_sale_tagtext_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $bdp_sale_tagtext_paddingtop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_sale_tagtext_paddingbottom ) && '' != $bdp_sale_tagtext_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $bdp_sale_tagtext_paddingbottom ) . 'px'; ?> ;<?php } ?>width: auto;
		<?php
		if ( isset( $bdp_sale_tag_angle ) && '' != $bdp_sale_tag_angle ) { 
			?>
			transform: rotate(<?php echo esc_attr( $bdp_sale_tag_angle ); ?>deg); <?php } ?>
		<?php
		if ( isset( $bdp_sale_tag_border_radius ) && '' != $bdp_sale_tag_border_radius ) { 
			?>
			border-radius: <?php echo esc_attr( $bdp_sale_tag_border_radius ); ?>% ; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price {
		<?php
		if ( isset( $bdp_pricetext_paddingleft ) && '' != $bdp_pricetext_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $bdp_pricetext_paddingleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_paddingright ) && '' != $bdp_pricetext_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $bdp_pricetext_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_paddingtop ) && '' != $bdp_pricetext_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $bdp_pricetext_paddingtop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_paddingbottom ) && '' != $bdp_pricetext_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $bdp_pricetext_paddingbottom ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price .woocommerce-Price-amount span {
		color: <?php echo esc_attr( $bdp_pricetextcolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price .woocommerce-Price-amount,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price {
		color: <?php echo esc_attr( $bdp_pricetextcolor ); ?> !important;
		font-size: <?php echo esc_attr( $bdp_pricefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_pricefontface ) && $bdp_pricefontface ) {
			?>
			font-family: <?php echo esc_attr( $bdp_pricefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_weight ) && $bdp_price_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_price_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_line_height ) && $bdp_price_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $bdp_price_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_italic ) && '1' == $bdp_price_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_letter_spacing ) && $bdp_price_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_price_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_text_transform ) && $bdp_price_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $bdp_price_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_price_font_text_decoration ) && $bdp_price_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_price_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_marginleft ) ) {
			?>
			margin-left:<?php echo esc_attr( $bdp_pricetext_marginleft ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_marginright ) ) {
			?>
			margin-right:<?php echo esc_attr( $bdp_pricetext_marginright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_margintop ) ) {
			?>
			margin-top: <?php echo esc_attr( $bdp_pricetext_margintop ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_marginbottom ) ) {
			?>
			margin-bottom: <?php echo esc_attr( $bdp_pricetext_marginbottom ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_pricetext_alignment ) && $bdp_pricetext_alignment ) {
			?>
			text-align: <?php echo esc_attr( $bdp_pricetext_alignment ); ?>;<?php } ?>
		width: auto;word-break: break-all;
		}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button .wpbm-span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_variable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_external,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_grouped,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_simple {
		font-size: <?php echo esc_attr( $bdp_addtocart_button_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_addtocart_button_fontface ) && $bdp_addtocart_button_fontface ) {
			?>
			font-family: <?php echo esc_attr( $bdp_addtocart_button_fontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_weight ) && $bdp_addtocart_button_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_addtocart_button_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $display_addtocart_button_line_height ) ) {
			?>
			line-height: <?php echo esc_attr( $display_addtocart_button_line_height ); ?>;<?php } ?>
        <?php if ( isset( $bdp_addtocart_button_font_italic ) && 1 == $bdp_addtocart_button_font_italic ) { ?> font-style: <?php echo 'italic'; ?>;<?php }  ?>
		<?php
		if ( isset( $bdp_addtocart_button_letter_spacing ) ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_addtocart_button_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_text_transform ) && $bdp_addtocart_button_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $bdp_addtocart_button_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_text_decoration ) && $bdp_addtocart_button_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_addtocart_button_font_text_decoration ); ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .added_to_cart {
		display:inline-block;padding:<?php echo esc_attr( $bdp_addtocartbutton_padding_topbottom ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocartbutton_padding_leftright ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_addtocart_button_fontface ) && $bdp_addtocart_button_fontface ) {
			?>
			font-family: <?php echo esc_attr( $bdp_addtocart_button_fontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_weight ) && $bdp_addtocart_button_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_addtocart_button_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $display_addtocart_button_line_height ) ) {
			?>
			line-height: <?php echo esc_attr( $display_addtocart_button_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_italic ) && '1' == $bdp_addtocart_button_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_letter_spacing ) ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_addtocart_button_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_text_transform ) && $bdp_addtocart_button_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $bdp_addtocart_button_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_button_font_text_decoration ) && $bdp_addtocart_button_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_addtocart_button_font_text_decoration ); ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_variable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_external,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_grouped,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_simple  {
		background-color: transparent;
		<?php
		if ( isset( $bdp_addtocart_textcolor ) && $bdp_addtocart_textcolor ) {
			?>
			color:<?php echo esc_attr( $bdp_addtocart_textcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_backgroundcolor ) && $bdp_addtocart_backgroundcolor ) {
			?>
			background: <?php echo esc_attr( $bdp_addtocart_backgroundcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_borderleft ) && $bdp_addtocartbutton_borderleft ) {
			?>
			border-left:<?php echo esc_attr( $bdp_addtocartbutton_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_borderleftcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_borderright ) && $bdp_addtocartbutton_borderright ) {
			?>
			border-right:<?php echo esc_attr( $bdp_addtocartbutton_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_borderrightcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_bordertop ) && $bdp_addtocartbutton_bordertop ) {
			?>
			border-top:<?php echo esc_attr( $bdp_addtocartbutton_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_bordertopcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_borderbuttom ) && $bdp_addtocartbutton_borderbuttom ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_addtocartbutton_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_borderbottomcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $display_addtocart_button_border_radius ) ) {
			?>
			border-radius:<?php echo esc_attr( $display_addtocart_button_border_radius ) . 'px'; ?> ;<?php } ?>
		padding:<?php echo esc_attr( $bdp_addtocartbutton_padding_topbottom ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocartbutton_padding_leftright ) . 'px'; ?>;
		margin-top:<?php echo esc_attr( $bdp_addtocartbutton_margin_topbottom ) . 'px'; ?>;
		margin-bottom:<?php echo esc_attr( $bdp_addtocartbutton_margin_topbottom ) . 'px'; ?>;
		margin-left:<?php echo esc_attr( $bdp_addtocartbutton_margin_leftright ) . 'px'; ?>;
		margin-right:<?php echo esc_attr( $bdp_addtocartbutton_margin_leftright ) . 'px'; ?>;
		<?php
		if ( isset( $bdp_addtocart_button_box_shadow_color ) && $bdp_addtocart_button_box_shadow_color ) {
			?>
			box-shadow: <?php echo esc_attr( $bdp_addtocart_button_top_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_right_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_bottom_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_left_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_box_shadow_color ); ?> !important; <?php } ?>
		display: inline-block;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .add_to_cart_button:focus,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_variable:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_variable:focus,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_external:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_external:focus,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_grouped:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_grouped:focus,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_simple:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap .product_type_simple:focus{
		<?php
		if ( isset( $bdp_addtocart_text_hover_color ) && $bdp_addtocart_text_hover_color ) {
			?>
			color:<?php echo esc_attr( $bdp_addtocart_text_hover_color ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_addtocart_hover_backgroundcolor ) && $bdp_addtocart_hover_backgroundcolor ) {
			?>
			background: <?php echo esc_attr( $bdp_addtocart_hover_backgroundcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_addtocartbutton_hover_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_hover_borderleftcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_addtocartbutton_hover_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_hover_borderrightcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_addtocartbutton_hover_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_hover_bordertopcolor ); ?> ;<?php } ?>
		<?php
		if ( isset( $bdp_addtocartbutton_hover_borderbuttom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_addtocartbutton_hover_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_addtocartbutton_hover_borderbottomcolor ); ?> ;<?php } ?>
		border-radius:<?php echo esc_attr( $display_addtocart_button_border_hover_radius ) . 'px'; ?> ;
		<?php
		if ( isset( $bdp_addtocart_button_hover_box_shadow_color ) && $bdp_addtocart_button_hover_box_shadow_color ) {
			?>
			box-shadow:<?php echo esc_attr( $bdp_addtocart_button_hover_top_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_hover_right_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_hover_bottom_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_hover_left_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_addtocart_button_hover_box_shadow_color ); ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp {
		text-align:<?php echo esc_attr( $bdp_cart_wishlistbutton_alignment ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_wishlist_wrap,
	<?php echo esc_attr( $layout_id ); ?> .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_cart_wrap {display:inline-block}
    <?php if ( isset( $bdp_wishlistbutton_on ) && isset( $display_addtowishlist_button ) && 1 == $bdp_wishlistbutton_on && 1 == $display_addtowishlist_button ) {  ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp{
		text-align:<?php echo esc_attr( $bdp_cart_wishlistbutton_alignment ); ?>;
	}
	<?php } else { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap {
			text-align:<?php echo esc_attr( $bdp_addtocartbutton_alignment ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button,
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse ,
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse{
			text-align:<?php echo esc_attr( $bdp_wishlistbutton_alignment ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse .feedback,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse .feedback{display: none !important}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a{
		<?php
		if ( isset( $bdp_addtowishlist_button_fontsize ) && $bdp_addtowishlist_button_fontsize ) {
			?>
			font-size: <?php echo esc_attr( $bdp_addtowishlist_button_fontsize ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_fontface ) && $bdp_addtowishlist_button_fontface ) {
			?>
			font-family: <?php echo esc_attr( $bdp_addtowishlist_button_fontface ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_font_weight ) && $bdp_addtowishlist_button_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $bdp_addtowishlist_button_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $display_wishlist_button_line_height ) && $display_wishlist_button_line_height ) {
			?>
			line-height: <?php echo esc_attr( $display_wishlist_button_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_font_italic ) && '1' == $bdp_addtowishlist_button_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_letter_spacing ) && $bdp_addtowishlist_button_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $bdp_addtowishlist_button_letter_spacing ) . 'px'; ?>; <?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_font_text_transform ) && $bdp_addtowishlist_button_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $bdp_addtowishlist_button_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_addtowishlist_button_font_text_decoration ) && $bdp_addtowishlist_button_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $bdp_addtowishlist_button_font_text_decoration ); ?> !important;<?php } ?>
	}
	<?php if ( class_exists( 'YITH_WCWL' ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist,
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a,
		<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a {
			<?php
			if ( isset( $bdp_wishlist_textcolor ) && $bdp_wishlist_textcolor ) {
				?>
				color: <?php echo esc_attr( $bdp_wishlist_textcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_wishlist_backgroundcolor ) && $bdp_wishlist_backgroundcolor ) {
				?>
				background: <?php echo esc_attr( $bdp_wishlist_backgroundcolor ); ?>;<?php } ?>
			<?php
			if ( isset( $bdp_wishlistbutton_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_wishlistbutton_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_borderleftcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_wishlistbutton_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_wishlistbutton_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_borderrightcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_wishlistbutton_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_wishlistbutton_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_bordertopcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_wishlistbutton_borderbuttom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_wishlistbutton_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_borderbottomcolor ); ?> ;<?php } ?>
			border-radius:<?php echo esc_attr( $display_wishlist_button_border_radius ) . 'px'; ?> ;
			padding : <?php echo esc_attr( $bdp_wishlistbutton_padding_topbottom ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlistbutton_padding_leftright ) . 'px'; ?>;
			margin-top: <?php echo esc_attr( $bdp_wishlistbutton_margin_topbottom ) . 'px'; ?>;
			margin-bottom: <?php echo esc_attr( $bdp_wishlistbutton_margin_topbottom ) . 'px'; ?>;
			margin-left: <?php echo esc_attr( $bdp_wishlistbutton_margin_leftright ) . 'px'; ?>;
			margin-right:<?php echo esc_attr( $bdp_wishlistbutton_margin_leftright ) . 'px'; ?>;
			<?php
			if ( isset( $bdp_wishlist_button_box_shadow_color ) && $bdp_wishlist_button_box_shadow_color ) {
				?>
				box-shadow: <?php echo esc_attr( $bdp_wishlist_button_top_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_right_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_bottom_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_left_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_box_shadow_color ); ?> !important;<?php } ?>
			display: inline-block;
		}
		<?php echo esc_attr( $layout_id ); ?> .add_to_wishlist:before {
			content:"\f08a";font-family:fontawesome;
			<?php
			if ( isset( $bdp_addtowishlist_button_font_weight ) ) {
				?>
				font-weight: <?php echo esc_attr( $bdp_addtowishlist_button_font_weight ); ?>;<?php } ?>
			vertical-align:middle;
			<?php
			if ( isset( $bdp_addtowishlist_button_font_italic ) && '1' == $bdp_addtowishlist_button_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			font-size: <?php echo esc_attr( $bdp_addtowishlist_button_fontsize ) . 'px'; ?>;
			<?php
			if ( isset( $display_wishlist_button_line_height ) && $display_wishlist_button_line_height ) {
				?>
				line-height: <?php echo esc_attr( $display_wishlist_button_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $bdp_addtowishlist_button_letter_spacing ) && $bdp_addtowishlist_button_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $bdp_addtowishlist_button_letter_spacing ) . 'px'; ?>;<?php } ?>
			<?php
			if ( isset( $bdp_addtowishlist_button_font_text_transform ) && $bdp_addtowishlist_button_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $bdp_addtowishlist_button_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $bdp_addtowishlist_button_font_text_decoration ) && $bdp_addtowishlist_button_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $bdp_addtowishlist_button_font_text_decoration ); ?>;<?php } ?>
		}
        <?php if ( isset( $bdp_wishlistbutton_on) && isset( $display_addtowishlist_button) && 1 == $bdp_wishlistbutton_on && 1 == $display_addtowishlist_button ) {  ?>
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line {padding:3px}
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_cart_wrap {display:inline-block;width:auto;vertical-align:top}
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_meta_box .bdp_wishlistbutton_on_same_line .bdp_woocommerce_add_to_wishlist_wrap{display:inline-block;width:auto;vertical-align:top}
		<?php } ?>
		<?php if ( isset( $bdp_wishlistbutton_alignment ) && 'right' === $bdp_wishlistbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show {text-align: left !important}
			<?php } ?>
			<?php if ( isset( $bdp_wishlistbutton_alignment ) && 'left' === $bdp_wishlistbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show {text-align: right !important}
			<?php } ?>
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist:hover,
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .add_to_wishlist:focus,
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a:hover,
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse a:focus,
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a:hover,
			<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse a:focus {
				<?php
				if ( isset( $bdp_wishlist_text_hover_color ) && $bdp_wishlist_text_hover_color ) {
					?>
					color: <?php echo esc_attr( $bdp_wishlist_text_hover_color ); ?>;<?php } ?>
				<?php
				if ( isset( $bdp_wishlist_hover_backgroundcolor ) && $bdp_wishlist_hover_backgroundcolor ) {
					?>
					background: <?php echo esc_attr( $bdp_wishlist_hover_backgroundcolor ); ?>;<?php } ?>
				<?php
				if ( isset( $bdp_wishlistbutton_hover_borderleft ) ) {
					?>
					border-left:<?php echo esc_attr( $bdp_wishlistbutton_hover_borderleft ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_hover_borderleftcolor ); ?>;<?php } ?>
				<?php
				if ( isset( $bdp_wishlistbutton_hover_borderright ) ) {
					?>
					border-right:<?php echo esc_attr( $bdp_wishlistbutton_hover_borderright ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_hover_borderrightcolor ); ?>;<?php } ?>
				<?php
				if ( isset( $bdp_wishlistbutton_hover_bordertop ) ) {
					?>
					border-top:<?php echo esc_attr( $bdp_wishlistbutton_hover_bordertop ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_hover_bordertopcolor ); ?>;<?php } ?>
				<?php
				if ( isset( $bdp_wishlistbutton_hover_borderbuttom ) ) {
					?>
					border-bottom:<?php echo esc_attr( $bdp_wishlistbutton_hover_borderbuttom ) . 'px'; ?> solid <?php echo esc_attr( $bdp_wishlistbutton_hover_borderbottomcolor ); ?>;<?php } ?>
				border-radius:<?php echo esc_attr( $display_wishlist_button_border_hover_radius ) . 'px'; ?>;
				<?php
				if ( isset( $bdp_wishlist_button_hover_box_shadow_color ) ) {
					?>
					box-shadow: <?php echo esc_attr( $bdp_wishlist_button_hover_top_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_hover_right_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_hover_bottom_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_hover_left_box_shadow ) . 'px'; ?> <?php echo esc_attr( $bdp_wishlist_button_hover_box_shadow_color ); ?>;<?php } ?>;
			}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price ins {background:none}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template img.ajax-loading {display: none !important}
    <?php if ( isset( $bdp_wishlistbutton_on ) && isset( $display_addtowishlist_button ) && 1 == $bdp_wishlistbutton_on && 1 == $display_addtowishlist_button ) {  ?>
		<?php if ( isset( $bdp_cart_wishlistbutton_alignment ) && 'left' === $bdp_cart_wishlistbutton_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp {text-align: right !important}
		<?php } ?>
		<?php if ( isset( $bdp_cart_wishlistbutton_alignment ) && 'right' === $bdp_cart_wishlistbutton_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_wishlistbutton_on_same_line.bdp_cartwishlist_wrapp {
				text-align: left !important;
				}
		<?php } ?>
	<?php } else { ?>
			<?php if ( isset( $bdp_addtocartbutton_alignment ) && 'left' === $bdp_addtocartbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_cart_wrap {
					text-align: right !important;
					}
			<?php } ?>
			<?php if ( isset( $bdp_addtocartbutton_alignment ) && 'right' === $bdp_addtocartbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_cart_wrap {
					text-align: left !important;
					}
			<?php } ?>
			<?php if ( isset( $bdp_wishlistbutton_alignment ) && 'right' === $bdp_wishlistbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show {
					text-align: left !important;
				}
			<?php } ?>
			<?php if ( isset( $bdp_wishlistbutton_alignment ) && 'left' === $bdp_wishlistbutton_alignment ) { ?>
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-add-button.show,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistexistsbrowse.show ,
				<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_wishlist_wrap .yith-wcwl-wishlistaddedbrowse.show {
					text-align: right !important;
				}
			<?php } ?>
		<?php } ?>
		<?php if ( isset( $bdp_star_rating_alignment ) && 'right' === $bdp_star_rating_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_star_wrap{
				text-align: left !important;
			}
		<?php } ?>
		<?php if ( isset( $bdp_star_rating_alignment ) && 'left' === $bdp_star_rating_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_star_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_star_wrap{
				text-align: right !important;
			}
		<?php } ?>
		<?php if ( isset( $bdp_pricetext_alignment ) && 'right' === $bdp_pricetext_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_price_wrap{
				text-align: left !important;
			}
		<?php } ?>
		<?php if ( isset( $bdp_pricetext_alignment ) && 'left' === $bdp_pricetext_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_price_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_price_wrap{
				text-align: right !important;
			}
		<?php } ?>
		<?php if ( isset( $bdp_addtocartbutton_alignment ) && 'left' === $bdp_addtocartbutton_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_cart_wrap {
				text-align: right !important;
				}
		<?php } ?>
		<?php if ( isset( $bdp_addtocartbutton_alignment ) && 'right' === $bdp_addtocartbutton_alignment ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .deport.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .navia.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .fairy.even_class .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp:nth-child(2n) .bdp_woocommerce_add_to_cart_wrap,
			<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .blog_post_wrap .bdp_woocommerce_add_to_cart_wrap {
				text-align: left !important;
				}
		<?php } ?>
<?php } ?>
/** End Woocommerce Layout settingd */
/** Link label css */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .link-lable{
	font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	color: <?php echo esc_attr( $color ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_content a:hover,
<?php echo esc_attr( $layout_id ); ?> .blog_template a.more-tag:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .categories a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .category-link a:hover,
<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .metadatabox .metacomments a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a:hover,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a:hover .bdp-count,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a:hover i{
	color:<?php echo esc_attr( $linkhovercolor ); ?>;
}

/* <?php //echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a i, */
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a,
<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .metadatabox li,
<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a .bdp-count{
	color: <?php echo esc_attr( $color ); ?>;
}
<?php
/* Boxy Layout Template CSS. */
if ( 'boxy' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .post_hentry{
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-category .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post_content-inner{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .blog_header h2 a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .blog_header h2{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		display: inline-block;
		<?php if ( isset( $titlebackcolor ) && $titlebackcolor ) { ?>
			background: <?php echo esc_attr( $titlebackcolor ); ?>;<?php } ?>
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) { ?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .number-date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .footer_meta .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .post-category span.category-link a,
	<?php echo esc_attr( $layout_id ); ?> .boxy .author a,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-metadata  span.author a,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-metadata > span a.comments-link,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-metadata .post-date a,
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-metadata .post-date a span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .social-component a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .blog_footer .footer_meta .tags a,
	<?php echo esc_attr( $layout_id ); ?> .post-metadata .post-comment a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .blog_footer .footer_meta .tags a:hover,{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .blog_header h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy .label_featured_post span {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?> ;
		
        <?php if ( isset( $readmorebutton_on) && 2 == $readmorebutton_on ) {  ?>border-color: <?php echo esc_attr( $readmorecolor ); ?>; <?php } ?>
        <?php if ( isset( $readmorebutton_on) && 2 == $readmorebutton_on ) {  ?>background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	}
    <?php if ( isset( $readmorebutton_on) && 1 == $readmorebutton_on ) {  ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy a.more-tag {border:none}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
    <?php if ( isset( $readmorebutton_on) && 2 == $readmorebutton_on ) {  ?>
		
		<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .boxy .post-metadata,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .footer_meta .tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .post-category span.category-link a{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .blog_footer .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .footer_meta .tags,
	<?php echo esc_attr( $layout_id ); ?> .boxy .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .post-category span.category-link {
		<?php if ( isset( $content_font_weight ) && $content_font_weight ) { ?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php if ( isset( $content_font_line_height ) && $content_font_line_height ) { ?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php if ( isset( $content_font_italic ) && '1' == $content_font_italic ) { ?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php if ( isset( $content_font_text_transform ) && $content_font_text_transform ) { ?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) { ?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) { ?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
        <?php if ( isset( $content_font_family) && '' != $content_font_family ) {  ?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .footer_meta .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .post-category span.category-link.categories_link,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .footer_meta .tags.tag_link,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy .footer_meta .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy .post-category span.category-link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php


}
/* Light Breeze Layout Template CSS. */
if ( 'lightbreeze' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .blog_header h2{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .blog_header h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .blog_header h2 a:hover{
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .read-more a{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .label_featured_post span {
		background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) {  ?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze  .read-more a:hover {
			background-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>




	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metacats,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metadate,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metacomments,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze.category-main-wrap .category-list {
		color: <?php echo esc_attr( $contentcolor ); ?>;
        <?php if ( isset( $content_font_family) && '' != $content_font_family ) {  ?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metacats a{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.lightbreeze .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .taxonomies,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metauser,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metauser.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .metauser .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .category-link.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .metadatabox .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .taxonomies .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .taxonomies.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .tags.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .tags i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze.alternative-back .category-main:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze.alternative-back .category-main:after{
		border-bottom-color: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .category-main{
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .read-more a.more-tag{
		<?php if ( isset( $bdp_readmore_button_borderleft ) ) { ?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_borderright ) ) { ?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_bordertop ) ) { ?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_borderbottom ) ) { ?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php if ( isset( $readmorebuttonborderradius ) ) { ?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze .read-more a.more-tag:hover{
		<?php if ( isset( $bdp_readmore_button_hover_borderleft ) ) { ?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_hover_borderright ) ) { ?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_hover_bordertop ) ) { ?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $bdp_readmore_button_hover_borderbottom ) ) { ?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php if ( isset( $readmore_button_hover_border_radius ) ) { ?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze.alternative-back,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.lightbreeze.alternative-back .category-main{
		background: <?php echo esc_attr( $alterbackground ); ?>;
	}
    <?php if ( isset( $firstletter_big) && 1 == $firstletter_big ) {  ?>
		<?php echo esc_attr( $layout_id ); ?> .lightbreeze.bdp_blog_template div.post_content > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .lightbreeze.bdp_blog_template div.post_content > p:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .lightbreeze.bdp_blog_template .post_content:first-letter {
			<?php if ( isset( $firstletter_font_family ) && $firstletter_font_family ) { ?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
			font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $firstletter_contentcolor ); ?>;
			float:none;margin-right:0;line-height:0;
		}
		<?php echo esc_attr( $layout_id ); ?> .lightbreeze.bdp_blog_template div.post_content {
			margin-top: <?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
		<?php
	}
}
/* Sharpen Layout Template CSS. */
if ( 'sharpen' === $bdp_theme ) {
	if ( isset( $template_alternativebackground ) && '1' == $template_alternativebackground ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen.alternative-back {
		background:<?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php } else { ?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen {
			background:<?php echo esc_attr( $background ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .blog_header h2{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .blog_header h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .blog_header h2 a:hover{
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metauser.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .category-list.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.blog_template.sharpen .label_featured_post span{
		background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .read-more a{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen a.more-tag {
			color: <?php echo esc_attr( $readmorecolor ); ?>;

		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;

		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .tags i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metauser .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metauser,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metacats,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metadate,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metacomments,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.category-main-wrap .category-list {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .metadatabox .metacats a{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.blog_template.sharpen .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php if ( isset( $template_alternativebackground ) && '1' == $template_alternativebackground ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .triangle_style .category-main:before,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .triangle_style .category-main:after{
			border-bottom-color: <?php echo esc_attr( $alterbackground ); ?>;
		}
	<?php } else { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .triangle_style .category-main:before,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .triangle_style .category-main:after{
			border-bottom-color: <?php echo esc_attr( $background ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .category-main:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .category-main:after{
		border-bottom-color: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php if ( isset( $template_alternativebackground ) && '1' == $template_alternativebackground ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .category-main{
			background: <?php echo esc_attr( $alterbackground ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen{
			background: <?php echo esc_attr( $background ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen.alternative-back .category-main{
			background: <?php echo esc_attr( $alterbackground ); ?>;
		}
	<?php } else { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sharpen .category-main{
			background: <?php echo esc_attr( $background ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sharpen .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .sharpen.bdp_blog_template div.post_content > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .sharpen.bdp_blog_template div.post_content > p:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .sharpen.bdp_blog_template .post_content:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
			font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
			color:<?php echo esc_attr( $firstletter_contentcolor ); ?>;
			float:none;margin-right:0;line-height:0
		}
		<?php echo esc_attr( $layout_id ); ?> .sharpen.bdp_blog_template div.post_content {
			margin-top:<?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
		<?php
	}
}
/* Classical Layout Template CSS. */
if ( 'classical' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical,.bdp_blog_template.classical .entry-container,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .entry-meta {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .blog_header h2 {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .blog_header h2 a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .blog_header h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	
	<?php echo esc_attr( $layout_id ); ?>  .bdp_blog_template.classical .post-meta-cats-tags .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags .category-link.categories_link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags .tags.tag_link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .metadatabox,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical p {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>;
	}
	
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.classical .author_content p{
		color: <?php echo esc_attr( $author_content_color ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags .tags {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .post-meta-cats-tags {
		border-color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .label_featured_post {
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .metacomments a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .categories a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive .author-avatar-div .author_content .author a,
	<?php echo esc_attr( $layout_id ); ?> .author-avatar-div.bdp_blog_template .social-component a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical span.bdp_no_link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component a {
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .metacomments a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.classical a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.classical .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Accordion Layout Template CSS. */
if ( 'accordion' === $bdp_theme ) { 
	?> 
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-1 .blog_wrap.bdp_blog_template.accordion,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .accordion-template-1 .blog_template.bdp_blog_template.accordion.accordion_wrapper {
		border-radius    : <?php echo esc_attr( $content_button_border_radius ) . 'px !important'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-1 .accordion .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-1 .accordion .accordion-content {
		background: <?php echo esc_attr( $template_bgcolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active {
		background: <?php echo esc_attr( $template_icon_active_header_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-1.accordion_wrapper {
		background: <?php echo esc_attr( $background_wrap ); ?> !important;
		padding: 10px;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion h3.ui-accordion-header:hover span.ui-accordion-header-icon:before  {
		color: <?php echo esc_attr( $template_icon_hover_color ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion .ui-state-default .ui-icon:before {
		<?php
		if ( isset( $icon_paddingtop ) && '' != $icon_paddingtop ) { 
			?>
			padding-top: <?php echo esc_attr( $icon_paddingtop ) . 'px'; ?>; <?php } ?>
		<?php
		if ( isset( $icon_paddingbottom ) && '' != $icon_paddingbottom ) { 
			?>
			padding-bottom: <?php echo esc_attr( $icon_paddingbottom ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $icon_paddingright ) && '' != $icon_paddingright ) { 
			?>
			padding-right: <?php echo esc_attr( $icon_paddingright ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $icon_paddingleft ) && '' != $icon_paddingleft ) { 
			?>
			padding-left: <?php echo esc_attr( $icon_paddingleft ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion .ui-state-default .ui-icon:before {
			color: <?php echo esc_attr( $template_icon_color ); ?>;
			font-size: <?php echo esc_attr( $icon_fontsize ) . 'px'; ?>;
			background: <?php echo esc_attr( $template_icon_bgcolor );?>;
			border-radius: <?php echo esc_attr( $icon_button_border_radius ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .ui-state-default .ui-icon{
		background: <?php echo esc_attr( $template_icon_bgcolor );?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .ui-state-default .ui-icon:before {
		top: 50%;
		transform: translateY(-50%);
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.accordion .bdp_social_share_postion.right_position {
			float: right;
		}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.accordion .bdp_social_share_postion.center_position {
			text-align: center;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.accordion .bdp_social_share_postion.center_position .social-component {
			display: inline-block;
			float: none;
	}
		
	<?php echo esc_attr( $layout_id ); ?> .ui-widget-content {
		border-color     : <?php echo esc_attr( $titlecolor ); ?> !important;
	}
	
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header a,
	<?php echo esc_attr( $layout_id ); ?> .ui-state-default .accodine-title-name,
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header {
		font-size : <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		color     : <?php echo esc_attr( $titlecolor ); ?>!important;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header a:hover, <?php echo esc_attr( $layout_id ); ?> .ui-accordion-header .accodine-title-name:hover  {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion .metadatabox a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion .metadatabox a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header a,
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header .accodine-title-name,
	<?php echo esc_attr( $layout_id ); ?> .ui-accordion-header {
		text-align: <?php echo esc_attr( $title_alignment ); ?> !important;
	}
	<?php if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .accordion div.post_content-inner > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .accordion div.post_content-inner > p:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .accordion .post_content-inner:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
				font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
				color:<?php echo esc_attr( $firstletter_contentcolor ); ?>;
				float:none; margin-top:5px; line-height:0;text-decoration:none;
		}
		<?php echo esc_attr( $layout_id ); ?> .post_content-inner:first-letter {
			margin-top: <?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
	<?php } ?>

	/* Layout 2 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2.accordion_wrapper {
		background: <?php echo esc_attr( $background_wrap ); ?> !important;
		padding: 10px;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .accordion .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .accordion .accordion-content {
		background: <?php echo esc_attr( $template_bgcolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .blog_wrap.bdp_blog_template.accordion {
			border-radius    : <?php echo esc_attr( $content_button_border_radius ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 .ui-accordion .ui-accordion-header .ui-icon {
			background : <?php echo esc_attr( ( $template_icon_bgcolor) ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 h3.ui-accordion-header:after {
			background: linear-gradient(to top left, transparent 0%,transparent 48%, <?php echo esc_attr( ( $template_icon_bgcolor) ); ?> 50%, <?php echo esc_attr( ( $template_icon_bgcolor) ); ?> 100%);
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-2 h3.ui-accordion-header:before {
			background: linear-gradient(to bottom left, transparent 0%,transparent 48%, <?php echo esc_attr( ( $template_icon_bgcolor) ); ?> 50%, <?php echo esc_attr( ( $template_icon_bgcolor) ); ?> 100%);
	}
		
	/* Layout 3 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_wrap.bdp_blog_template.accordion {
		border-radius    : <?php echo esc_attr( $content_button_border_radius ) . 'px'; ?>;
	}

	.bdp_archive .accordion-template-3 .blog_template.bdp_blog_template.accordion.accordion_wrapper {
		border-radius    : <?php echo esc_attr( $content_button_border_radius ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1)  h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) h3 {
		background: <?php echo '' != $repetative_icon_color1 ? esc_attr( Bdp_Utility::hex2rgba( $repetative_icon_color1, 0.2 ) ) : 'transparent';  ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) h3:before,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) .accordion-content:before {
		background : <?php echo esc_attr( ( $repetative_icon_color1) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2)  h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) h3 {
		background: <?php echo '' != $repetative_icon_color2 ? esc_attr( Bdp_Utility::hex2rgba( $repetative_icon_color2, 0.2 ) ) : 'transparent';  ?>;
	}
	
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) h3:before,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) .accordion-content:before {
		background : <?php echo esc_attr( ( $repetative_icon_color2) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3)  h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) h3 {
		background: <?php echo '' != $repetative_icon_color3 ? esc_attr( Bdp_Utility::hex2rgba( $repetative_icon_color3, 0.2 ) ) : 'transparent';  ?>;
	}
	
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) h3:before,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) .accordion-content:before {
		background : <?php echo esc_attr( ( $repetative_icon_color3) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) h3,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) .post_content.accordion-content {
		border-left: 15px solid <?php echo esc_attr( ( $repetative_icon_color1) ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) h3 {
		border-left: 15px solid <?php echo esc_attr( ( $repetative_icon_color2) ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) .post_content.accordion-content,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) h3 {
		border-left: 15px solid <?php echo esc_attr( ( $repetative_icon_color3) ); ?> !important;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 1) h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active a:focus {
		border: 4px solid <?php echo esc_attr( ( $repetative_icon_color1) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer h3 a {
		border: 4px solid transparent;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 2) h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active a:focus {
		border: 4px solid <?php echo esc_attr( ( $repetative_icon_color2) ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-3 .blog_accordion_uniquecontainer:nth-child(3n + 3) h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active a:focus {
		border: 4px solid <?php echo esc_attr( ( $repetative_icon_color3) ); ?>;
	}

	/* Layout 4 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-4 .blog_accordion_uniquecontainer {
		background: <?php echo esc_attr( $template_bgcolor ); ?>;
		border-radius: <?php echo esc_attr( $content_button_border_radius ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-4 .bdp_blog_template.accordion.bdp_blog_single_post_wrapp .ui-accordion-header{
		border-radius: <?php echo esc_attr( $content_button_border_radius ) . 'px'; ?>;
		z-index: 1;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-4 .bdp_blog_template.accordion.bdp_blog_single_post_wrapp .post_content {
		background: <?php echo esc_attr( $template_bgcolor ); ?>;
		margin-top: -20px !important;
		border-bottom-left-radius: <?php echo esc_attr( $content_button_border_radius - 20 ) . 'px'; ?>;
		border-bottom-right-radius: <?php echo esc_attr( $content_button_border_radius - 20 ) . 'px'; ?>;
		border-top-left-radius: <?php echo esc_attr( $content_button_border_radius - 20 ) . 'px'; ?>;
		border-top-right-radius: <?php echo esc_attr( $content_button_border_radius - 20 ) . 'px'; ?>;
		
		padding-top: 50px;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .accordion-template-4 .bdp_blog_template.accordion.bdp_blog_single_post_wrapp .post_content {
		border-radius: <?php echo esc_attr( $content_button_border_radius - 20 ) . 'px'; ?>;
	}
	<?php 
	if(isset($bdp_settings['accordion_template']) && $bdp_settings['accordion_template'] == 'accordion-template-5') { ?>
		<?php echo esc_attr( $layout_id ); ?>.accordion_cover {
			background: <?php echo esc_attr( $background_wrap ); ?> !important;
			padding: 25px 20px 0 20px;
		}
	<?php } ?>
	
	<?php echo esc_attr( $layout_id ); ?> .accordion.accordion-template-5 .post_content {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}

	/* Layout 6 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion.accordion-template-6 {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-6 .blog_accordion_uniquecontainer:before {
		border: 1px dashed <?php echo esc_attr( $titlecolor ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-6 h3.ui-accordion-header.ui-corner-top.ui-state-default.ui-accordion-icons.ui-accordion-header-active.ui-state-active span:before {
		background: <?php echo esc_attr( $template_icon_active_header_color ); ?>;
		color     : <?php echo esc_attr( $template_icon_hover_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-6 .accordion .ui-state-default .ui-icon:before {
		border: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion span.ui-accordion-header-icon.ui-icon:hover::before {
		color: <?php echo esc_attr( $template_icon_color ); ?>;
	}

	/* Layout 7 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-7 .post_content.accordion-content:before,<?php echo esc_attr( $layout_id ); ?> .accordion-template-7 .accordion-content:before {
		background    : <?php echo esc_attr( $titlecolor ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-7 .post_content.accordion-content {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}

	.bdp_archive .accordion-template-7 .entry-container.accordion-content {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}
	

	/* Layout 8 Css Start */
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header a:before,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header .accodine-title-name:before {
		border-right: 8px solid <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .bdp_blog_template.accordion .post_content {
		border-bottom: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
		border-left: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
		border-right: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive .accordion-template-8 .accordion .ui-state-default {
		border-bottom: 1px solid <?php echo esc_attr( $titlecolor ); ?> !important;
		border-left: 1px solid <?php echo esc_attr( $titlecolor ); ?> !important;
		border-right: 1px solid <?php echo esc_attr( $titlecolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header a,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header .accodine-title-name,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive .accordion-template-8 .ui-accordion-header a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive .accordion-template-8 .ui-accordion-header .accodine-title-name {
		border-top: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
		border-left: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
		border-right: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
		border-bottom: 1px solid <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8.icon-left span.ui-accordion-header-icon.ui-icon {
		border: 1px solid <?php echo esc_attr( $template_icon_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.accordion.accordion_wrapper.accordion-template-8,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .post_content.accordion-content {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}

	.bdp_archive .accordion-template-8 .entry-container.accordion-content {
		background    : <?php echo esc_attr( $template_bgcolor ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header a:after,
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 .ui-accordion-header a:after   {
		border-right: 7px solid <?php echo esc_attr( $template_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .accordion-template-8 span.ui-accordion-header-icon.ui-icon {
		background    : <?php echo esc_attr( $template_icon_bgcolor ); ?>;
	}
}
	
<?php } ?>

/* Evolution Layout Template CSS. */
<?php if ( 'evolution' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution .entry-title a,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution h2.post-title a {
			color: <?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		}
		<?php if ( isset( $titlebackcolor ) && '' != $titlebackcolor ) { 
			?>
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution .post-title{
				background: <?php echo esc_attr( $titlebackcolor ); ?>;
			}
		<?php } ?>
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-content-body,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .label_featured_post span{
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-bottom a,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .label_featured_post span{
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;
			color: <?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-bottom a:hover{
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-content-body p,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .author.bdp_no_links,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .author .link-lable,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .number-date,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .comment,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .tags.bdp_no_links,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category.bdp_no_links,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category .link-lable,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .bdp-wrapper-like {
			color: <?php echo esc_attr( $contentcolor ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .tags a,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-entry-meta .date i,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .tags i {
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .author,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .tags,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .tags a,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category a {
			color: <?php echo esc_attr( $color ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-category a:hover,
		<?php echo esc_attr( $layout_id ); ?> .evolution .author a:hover,
		<?php echo esc_attr( $layout_id ); ?> .evolution .icon_cnt a:hover,
		<?php echo esc_attr( $layout_id ); ?> .evolution .bdp-like-button:hover,
		<?php echo esc_attr( $layout_id ); ?> .evolution .number-date a:hover{
			color: <?php echo esc_attr( $linkhovercolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .blog_header{
			background: <?php echo esc_attr( $background ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .blog_header h2 a{
			color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution .blog_header .title .metadate a,
		<?php echo esc_attr( $layout_id ); ?> .evolution .blog_header .title .metadate span.author,
		<?php echo esc_attr( $layout_id ); ?> .evolution .blog_header .title .metadate span.time,
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-bottom .categories,
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-bottom .categories a,
		<?php echo esc_attr( $layout_id ); ?> .evolution .post-category a,
		<?php echo esc_attr( $layout_id ); ?> .evolution .icon_cnt a,
		<?php echo esc_attr( $layout_id ); ?> .evolution .author a,.evolution .number-date a,
		<?php echo esc_attr( $layout_id ); ?> .evolution .bdp-like-button {
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-category a,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-entry-meta span,
		<?php echo esc_attr( $layout_id ); ?> .evolution.bdp_blog_template .post-entry-meta span a {
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_archive.evolution .author-avatar-div:before,
		<?php echo esc_attr( $layout_id ); ?> .bdp_archive.evolution .author-avatar-div:after {
			background: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution .post-bottom a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution .post-bottom a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
			
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
		}

		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.evolution a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

		<?php
}
/* Spektrum Layout Template CSS. */
if ( 'spektrum' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.spektrum {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .label_featured_post span{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .post-meta-div {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .post-bottom{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .blog_header h2 a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .blog_header h2 {
		display: inline-block;
		width: 100%;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.spektrum .bdp-post-image .overlay a {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .post-bottom span{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .post-meta-div > span,
	<?php echo esc_attr( $layout_id ); ?> .spektrum .post-meta-div > span .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .post-meta-div > span a,
	<?php echo esc_attr( $layout_id ); ?> .spektrum .meta_tags span a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .post-meta-div > span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .spektrum .meta_tags span a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .details a {
		color :<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum a.more-tag:focus,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum a.more-tag{
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>

		<?php
		
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .label_featured_post span {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .spektrum .bdp-post-image{width:100%}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .date {
		background: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .details a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.spektrum .details a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}



	<?php
}
/* Hub Layout Template CSS. */
if ( 'hub' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.hub{
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .post_content{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub .post-bottom{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .blog_header h2 a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub .blog_header h2 {
		display: inline-block;
		width: 100%;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .label_featured_post{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .meta_tags span a {
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .meta_tags span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.date:hover{
		color:<?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .read_more_div a {
		color :<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .read_more_div a:hover{
		color :<?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .read_more_div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border:none;float:none;
			
		<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.more-tag:focus,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .hub .label_featured_post{
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .hub .bdp-post-image{
		width:100%;
	}
	<?php if ( isset( $bdp_settings['date_color_of_readmore'] ) && 1 == $bdp_settings['date_color_of_readmore'] && isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.date{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
			<?php
			if ( isset( $content_font_family ) && '' != $content_font_family ) { 
				?>
				font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.date:hover{
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			
		}

		<?php } else if(isset( $bdp_settings['date_color_of_readmore'] ) && 1 == $bdp_settings['date_color_of_readmore'] && isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { ?>
			<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.date{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			
			<?php
			if ( isset( $content_font_family ) && '' != $content_font_family ) { 
				?>
				font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub a.date:hover{
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			
			
		}

	<?php } else { ?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hub .date,
		<?php echo esc_attr( $layout_id ); ?> .hub .number-date {
			background: #212121;color:#fff;
			<?php
			if ( isset( $content_font_family ) && '' != $content_font_family ) { 
				?>
				font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		}
		<?php
	}
}
/* Offer Blog Layout Template CSS. */
if ( 'offer_blog' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.offer_blog.bdp_blog_template {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template,
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template .date,
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template .tags,
	<?php echo esc_attr( $layout_id ); ?> .offer_blog .post-entry-meta {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .blog-title-meta h2 a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog h2 {
		display: inline-block;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .offer_blog .label_featured_post span{
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template span.author,
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template span.author i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .offer_blog.bdp_blog_template span.author.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-by span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .comment a,
	<?php echo esc_attr( $layout_id ); ?> .post-entry-meta a,
	<?php echo esc_attr( $layout_id ); ?> .post_content a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-category a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .comment a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog  a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border:1px solid <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border:none; <?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.offer_blog .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-bottom a.more-tag{
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.offer_blog .post-bottom a.more-tag:hover{
		color:<?php echo esc_attr( $readmorehovercolor ); ?>;
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Nicy Blog Layout Template CSS. */
if ( 'nicy' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .entry-meta .up_arrow:after {
		border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .entry-container,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .entry-meta {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .blog_header h2 {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .blog_header h2 a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .blog_header h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.nicy .author_content p,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy p,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .metadatabox {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags {
		border-left: 10px solid <?php echo esc_attr( $color ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .entry-meta a.more-tag:hover {
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .label_featured_post {
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .entry-meta a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post_author.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .post-meta-cats-tags .category-link.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .metacomments a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .categories a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .category-link a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a,
	.author-avatar-div.bdp_blog_template .social-component a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .social-component a {
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .metacomments a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.nicy .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.nicy .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Winter Blog Layout Template CSS. */
if ( 'winter' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .winter {
		background-color:<?php echo esc_attr( $background ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter a,
	<?php echo esc_attr( $layout_id ); ?> .winter .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_header .metadatabox .posted_by,
	<?php echo esc_attr( $layout_id ); ?> .winter .blog_header .metadatabox div.tags a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .bdp-wrapper-like .bdp-like-button span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .bdp-wrapper-like .bdp-like-button{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter a:hover,
	<?php echo esc_attr( $layout_id ); ?> .winter .blog_header .metadatabox > span,
	<?php echo esc_attr( $layout_id ); ?> .winter .blog_header .metadatabox div.tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .bdp-wrapper-like .bdp-like-button:hover span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .bdp-wrapper-like .bdp-like-button:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .bdp-post-image .category-link a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .blog_header .metadatabox .posted_by span.auther-inner,
	<?php echo esc_attr( $layout_id ); ?> .winter .datetime,
	<?php echo esc_attr( $layout_id ); ?> .winter .tags,
	<?php echo esc_attr( $layout_id ); ?> .winter .category-link.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .winter .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .posted_by span,
	<?php echo esc_attr( $layout_id ); ?> .winter .blog_header .metadatabox > span{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .tags .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .metacomments i {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .blog_header h2 a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter .blog_header h2 {
		display: inline-block;
		background:<?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .number-date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .post-bottom .post-by span,
	<?php echo esc_attr( $layout_id ); ?> .winter .tags, .winter .category-link,
	<?php echo esc_attr( $layout_id ); ?> .blog_header .metadatabox .posted_by span.auther-inner a,
	<?php echo esc_attr( $layout_id ); ?> .winter .post-bottom .categories a{
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .details a {
		color :<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter a.more-tag{
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>!important; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter {
		border-bottom: 3px solid;border-color: <?php echo esc_attr( $bdp_settings['winter_category_color'] ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter .metacomments a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.winter .metacomments a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .posted_by span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .metacomments span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.winter .metacomments{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.winter .author-avatar-div {
		background: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .winter .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $bdp_settings['winter_category_color'] ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .winter .label_featured_post,
		<?php echo esc_attr( $layout_id ); ?> .winter .bdp-post-image .category-link {
			background-color : <?php echo esc_attr( $bdp_settings['winter_category_color'] ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .winter .label_featured_post:before,
		<?php echo esc_attr( $layout_id ); ?> .winter .bdp-post-image .category-link:before {
			border-right: 10px solid <?php echo esc_attr( $bdp_settings['winter_category_color'] ); ?>;opacity: 0.65;
		}
		<?php
	}
}
/* Region Blog Layout Template CSS. */
if ( 'region' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region .blog_footer {
		background:<?php echo esc_attr( $background ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region.alternative-back,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region.alternative-back .blog_footer {
		background:<?php echo esc_attr( $alterbackground ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .region .date,
	<?php echo esc_attr( $layout_id ); ?> .region .number-date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .region .post_content,
	<?php echo esc_attr( $layout_id ); ?> .region .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region .label_featured_post{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .region .category-link,
	<?php echo esc_attr( $layout_id ); ?> .region .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .region .tags,
	<?php echo esc_attr( $layout_id ); ?> .region .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by .author-meta,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by .author-meta .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .region .metadatabox .article_comments,
	<?php echo esc_attr( $layout_id ); ?> .region .metadatabox .bdp-wrapper-like{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .region .blog_header h2 a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .region .blog_header h2 a:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region .blog_header h2{
		display: inline-block;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .region .category-link.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .region .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by .author-meta.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .region .post-bottom .post-by span,
	<?php echo esc_attr( $layout_id ); ?> .region .post-bottom .categories a,
	<?php echo esc_attr( $layout_id ); ?> .region .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .region .tags a,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .region .post-bottom .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .region .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .region .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .region .posted_by a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .metacomments a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .region .details a {
		color :<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region .label_featured_post {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		border: 1px solid <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border: 1px solid <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.region .label_featured_post {
		transition: 0.2s all;
		-ms-transition: 0.2s all;
		-o-transition: 0.2s all;
		-webkit-transition: 0.2s all;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			border: 1px solid <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region .bdp-post-image{padding: 0 40px}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.region .author-avatar-div {
		background: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.region .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Quci Blog Layout Template CSS. */
if ( 'quci' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_footer .category-link a,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_footer .tags a{
		color:<?php echo esc_attr( $templatecolor ); ?>;
		border: 1px solid <?php echo esc_attr( $templatecolor ); ?>;padding:2px 5px;display:inline-block;margin-right:5px;margin-bottom:5px;
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_footer .category-link a:hover,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_footer .tags a:hover{
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
		border: 1px solid <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	/* Quci Blog Layout Template CSS For Post Title Visibility. */
	.quci .blog_item .blog_header h2 a{
		margin-left: -1px;
		display: flex;
	}
	/* Quci Blog Layout Template CSS For Post Title Visibility. */
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_header h2 a,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_header h2{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .posted_by,
	<?php echo esc_attr( $layout_id ); ?> .quci .tags{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_header h2 a:hover,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .blog_header h2:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item time.datetime,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item span.post-author a,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item span.comment a{
		color:<?php echo esc_attr( $color ); ?>!important;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .posted_by,
	<?php echo esc_attr( $layout_id ); ?> .quci .posted_by a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item time.datetime:hover,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item span.post-author a:hover,<?php echo esc_attr( $layout_id ); ?> .quci .blog_item span.comment a:hover{
		color:<?php echo esc_attr( $linkhovercolor ); ?>!important;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}

	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.quci a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.quci a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}

		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.quci a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .read-more-class a {
		color:<?php echo esc_attr( $bdp_settings['template_readmorecolor'] ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?>;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		margin-top: <?php echo esc_attr( $readmore_button_margintop ) . 'px'; ?>;
		margin-bottom: <?php echo esc_attr( $readmore_button_marginbottom ) . 'px'; ?>;
		margin-right: <?php echo esc_attr( $readmore_button_marginright ) . 'px'; ?>;
		margin-left: <?php echo esc_attr( $readmore_button_marginleft ) . 'px'; ?>;
		font-size: <?php echo esc_attr( $readmore_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_font_weight ) && $readmore_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $readmore_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_line_height ) && $readmore_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $readmore_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_italic ) && '1' == $readmore_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_text_transform ) && $readmore_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $readmore_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_text_decoration ) && $readmore_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $readmore_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $readmore_font_letter_spacing ) && $readmore_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $readmore_font_letter_spacing ) . 'px'; ?>;<?php } ?>    
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog_item .read-more-class a:hover{
		color:<?php echo esc_attr( $readmorehovercolor ); ?>;
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .quci .blog-content {
		border-bottom: 3px solid <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php
}
/* Pedal Blog Layout Template CSS. */
if ( 'pedal' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .tags.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.taxonomies.product_tag.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.taxonomies.download_tag.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.taxonomies.pa_color.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.taxonomies.pa_size.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .bdp-date-link{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .bdp-month-link{
		color: <?php echo esc_attr( $titlebackcolor ); ?>;
		background: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .post-title h2 a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .pedal_blog .post-title h2 {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .post-title h2 a:hover,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .pedal_blog .post-title h2:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> span.post-comment.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog span.post-date.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog span.post-author.bdp_has_links a,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .bdp-wrapper-like span,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .category-link.bdp_has_links,<?php echo esc_attr( $layout_id ); ?> .pedal_blog .tags.bdp_has_links{
		color:<?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .read_more_div a{
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?>;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pedal_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pedal_blog a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}
		
		<?php } ?>

		
		


	<?php echo esc_attr( $layout_id ); ?> .pedal_blog .read_more_div a:hover{
		color:<?php echo esc_attr( $readmorehovercolor ); ?>!important;
		background:<?php echo esc_attr( $bdp_settings['template_readmore_hover_backcolor'] ); ?>!important;
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Glossary Blog Layout Template CSS. */
if ( 'glossary' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.glossary .blog_item {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .post_content p{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .blog_item .blog_footer {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .post_summary_outer .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $background ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
		border-color: <?php echo esc_attr( $bdp_settings['template_content_hovercolor'] ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .blog_header h2 a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author{
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary .blog_header h2,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary .blog_header h2 a {
		display: block;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary .blog_header h2 a:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .number-date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .post-bottom .post-by span,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .tags a,
	<?php echo esc_attr( $layout_id ); ?> .glossary .post-author a,.glossary .comment a,.glossary .posted_by a,
	<?php echo esc_attr( $layout_id ); ?> .glossary .bdp_blog_template .social-component a ,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by a .datetime {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .details a {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary a.more-tag{
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;opacity: 0.9;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary .read-more-class a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary .read-more-class a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius:<?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.glossary a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .glossary .post_content-inner {
		border-color: <?php echo esc_attr( $bdp_settings['template_content_hovercolor'] ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-load-more a.button.bdp-load-more-btn {
		border-color:<?php echo esc_attr( $readmorebackcolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .category-link.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .category-link .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by .datetime,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .tags.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by .post-author.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by .post-author .link-lable {
		color:<?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .category-link,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .tags,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by .post-author {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glossary .footer_meta .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glossary .post-author a:hover,.glossary .comment a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glossary .posted_by a:hover .datetime{
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glossary .comment{
		color:<?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.glossary .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .glossary div.post-content .post_content-inner > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .glossary div.post-content .post_content-inner> p:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .glossary div.post-content .post_content-inner:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .glossary div.post_content .post_content-inner > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .glossary div.post_content .post_content-inner> p:first-child:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
				font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
				color:<?php echo esc_attr( $firstletter_contentcolor ); ?>;
				float:left;margin-right:5px;
		}
	<?php } ?>
	<?php
}
/* Boxy Clean Blog Layout Template CSS. */
if ( 'boxy-clean' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean ul li {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.boxy-clean .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean ul li:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.boxy-clean .author-avatar-div:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean ul li:hover .blog_header h2 {
		background: <?php echo esc_attr( $template_bghovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .date,
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .number-date {
		color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .footer_meta .tags a,
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .footer_meta .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .post-bottom .post-by span,
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean a{
		color: <?php echo esc_attr( $color ); ?> ;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .post_content,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .blog_footer,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .footer_meta,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .blog_header h2 {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .blog_header h2 a {
		color: <?php echo esc_attr( $titlecolor ); ?> ;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.blog_template.boxy-clean .author_content .author {
		color: <?php echo esc_attr( $author_titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .blog_header h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .footer_meta .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .boxy-clean .footer_meta .category-link a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean ul li,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .label_featured_post span {
		background:<?php echo esc_attr( $templatecolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .blog_wrap.bdp_blog_template .author:hover {
		background:<?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy-clean .blog_header h2 {
		display: inline-block;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy-clean .more-tag {
		color :<?php echo esc_attr( $readmorecolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy-clean .more-tag:hover {
		color :<?php echo esc_attr( $readmorehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.boxy-clean a.more-tag{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .tags {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .category-link .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .tags.tag_link,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .category-link.categories_link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.boxy-clean .read-more a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;

		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag:hover, <?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}

		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag:hover,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* News Blog Layout Template CSS. */
if ( 'news' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template a span,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .accodine-title-name span,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template a,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .accodine-title-name  {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template a span:hover,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .accodine-title-name span:hover,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template a:hover,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .accodine-title-name:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template h2 {
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template h2 a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .entry-title a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news h2.post-title a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.news .author_div li.active{
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .entry-title a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news h2.post-title a:hover,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template h2 a:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template.alternative-back{
		background: <?php echo esc_attr( $alterbackground ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news .post-content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .post-thumbnail-div .label_featured_post{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .post-bottom a:hover{
		background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .post-bottom a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .post-thumbnail-div .label_featured_post {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		padding: 5px 15px;
		border: none;
	}
	<?php echo esc_attr( $layout_id ); ?> .news .post-category,
	<?php echo esc_attr( $layout_id ); ?> .news .tags,
	<?php echo esc_attr( $layout_id ); ?> .news .metacomments {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .news .post-category .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .news .post-category i,
	<?php echo esc_attr( $layout_id ); ?> .news .tags i,
	<?php echo esc_attr( $layout_id ); ?> .news .mdate,
	<?php echo esc_attr( $layout_id ); ?> .news .post-author {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .news .mdate,
	<?php echo esc_attr( $layout_id ); ?> .news .post-author,
	<?php echo esc_attr( $layout_id ); ?> .news .metacomments,
	<?php echo esc_attr( $layout_id ); ?> .news.bdp_blog_template .post-bottom a{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .news .post-author.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .metacomments a,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox a span.bdp-count,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .tags a,
	<?php echo esc_attr( $layout_id ); ?> .news .tags.bdp_has_link,
	<?php echo esc_attr( $layout_id ); ?> .news .post-category.bdp_has_link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .categories a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .category-link a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .metacomments a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .category-link a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.news .author_div ul.nav-tabs li.active,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.news .author_div .tab-content {
		background: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .post-bottom a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	} 
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.news .post-bottom a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Deport Blog Layout Template CSS. */
if ( 'deport' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .deport a{
		color: <?php echo esc_attr( $color ); ?>;
		box-shadow:none;
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .deport-wrap .deport-title-area .post-title,
	<?php echo esc_attr( $layout_id ); ?> .deport .deport-wrap .deport-title-area .post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .deport-wrap .deport-title-area .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.deport .tags a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.deport .tags a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .deport.bdp_blog_template a.more-tag:hover,
		<?php echo esc_attr( $layout_id ); ?> .deport.bdp_blog_template .accodine-title-name.more-tag:hover{
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .deport.bdp_blog_template a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .deport.bdp_blog_template .accodine-title-name.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.deport .label_featured_post{
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .deport-wrap .deport-title-area:after{
		background:
		<?php
		if ( isset( $bdp_settings['deport_dashcolor'] ) ) {
			echo esc_attr( $bdp_settings['deport_dashcolor'] ) . ' !important';
		}
		?>
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span.author.bdp_no_link,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox .custom-categories,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox .custom-categories span.seperater,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span.author {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span i,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span.tags i,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span.tags.tag_link,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox .custom-categories.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox .custom-categories.bdp-no-links span,
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox .custom-categories .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .deport .metadatabox span.tags {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.deport .deport-title-area{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .deport.even_class .post_content::first-letter{
		line-height: <?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.deport .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.deport .author-avatar-div .author_content p {
		color: <?php echo esc_attr( $author_content_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag:hover,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template a.more-tag:hover,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .accodine-title-name.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag {

		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp_social_share_postion.right_position {
		text-align: right;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .bdp_social_share_postion.center_position {
		text-align: center;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .bdp_social_share_postion.center_position .social-component .social-share,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .bdp_social_share_postion.right_position .social-component .social-share {
		float: left;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .bdp_social_share_postion.right_position {
		text-align: right;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.even_class .bdp_social_share_postion.left_position .social-component .social-share {
		float: none;
	}
	<?php
}
/* Navia Blog Layout Template CSS. */
if ( 'navia' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia{
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template .mdate:hover,
	<?php echo esc_attr( $layout_id ); ?> .navia .post-metadata a:hover,
	<?php echo esc_attr( $layout_id ); ?> .navia .navia-content-wrap span.metacomments a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a,
	<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template .mdate,
	<?php echo esc_attr( $layout_id ); ?> .navia .post-metadata a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .post-title{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .post-title a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .post_content ,.bdp_archive .navia .post_content{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .more-tag{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.navia .author-avatar-div .author_content .author {
		color: <?php echo esc_attr( $author_titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template .mdate{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.navia .post-metadata,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.navia .post-content-area .tags.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .navia .navia-content-wrap .post-metadata .bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.navia .post-content-area .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .bdp-post-image {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .navia-content-wrap .post-metadata .bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.navia .post-content-area .tags {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .navia .post-content-area .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.navia .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .post_content a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .bdp-post-image .label_featured_post {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .post_content a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .bdp-post-image .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .post_content a.more-tag:hover {
		<?php 
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.navia .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template div.post_content > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template .post_content:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
				font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
				color:<?php echo esc_attr( $firstletter_contentcolor ); ?>;
				float:none;margin-right:0;line-height:0
		}
		<?php echo esc_attr( $layout_id ); ?> .navia.bdp_blog_template div.post_content {
			margin-top: <?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
	<?php } ?>
	<?php
}
/* Invert Grid Blog Layout Template CSS. */
if ( 'invert-grid' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-author,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid a {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid a:hover{
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-body-div h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-body-div h2 a:hover{
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-body-div h2,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-body-div h2 a {
		background:<?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size:<?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	<?php
	if ( isset( $template_titlefontface ) && $template_titlefontface ) {
		?>
		font-family:<?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
		?>
		font-weight:<?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
		?>
		line-height:<?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
		?>
		font-style:<?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
		?>
		text-transform:<?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
		?>
		text-decoration:<?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
		?>
		letter-spacing:<?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post_content{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .metadatabox {
		color:<?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	<?php
	if ( isset( $content_font_family ) && '' != $content_font_family ) { 
		?>
		font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-author .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .post-author.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .tags.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .tags i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .category-link a {color:#fff}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .invert-grid .read-more > span{
	<?php
	if ( isset( $content_font_family ) && '' != $content_font_family ) { 
		?>
		font-family:<?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .invert-grid .read-more > span{
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .invert-grid .read-more > span:hover{
		background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		color:<?php echo esc_attr( $readmorehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .label_featured_post {
		background: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.invert-grid .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .read-more a.more-tag {
	<?php
	if ( isset( $bdp_readmore_button_borderleft ) ) {
		?>
		border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderright ) ) {
		?>
		border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_bordertop ) ) {
		?>
		border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderbottom ) ) {
		?>
		border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top:<?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom:<?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right:<?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left:<?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
	<?php
	if ( isset( $readmorebuttonborderradius ) ) {
		?>
		border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.invert-grid .read-more a.more-tag:hover{
	<?php
	if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
		?>
		border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_borderright ) ) {
		?>
		border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
		?>
		border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
		?>
		border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $readmore_button_hover_border_radius ) ) {
		?>
		border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Brit CO Blog Layout Template CSS. */
if ( 'brit_co' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category a,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template a,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .accodine-title-name,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .social-component .social-share .count,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .tags {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category i,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .tags i,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .post-entry-meta .author,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .post-entry-meta .date i,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .tags.bdp-no-links {
		color: <?php echo esc_attr( $titlecolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .accodine-title-name:hover,
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .post-category a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a:hover {
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .read_more_text a{
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color: <?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .read_more_text a:hover{
		background: <?php echo esc_attr( $readmorecolor ); ?>;
		color: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template:hover .bdp_blog_wraper,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.britco:hover .label_featured_post{
		border-color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .comment a{
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .comment a:hover{
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .content_wrapper h2.post-title{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.brit_co .author_content .author {
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brit_co .bdp_blog_template .social-component a {
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.brit_co .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.britco .label_featured_post {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php
}
/* Media Grid Blog Layout Template CSS. */
if ( 'media-grid' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .category-link a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .post-body-div h2.post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .post-body-div h2.post-title a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .post-body-div h2.post-title a:focus {
		color: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.media-grid .author_content .author {
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .post-body-div h2.post-title {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .bdp-post-image .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .bdp-post-image .category-link a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .bdp-post-image .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .bdp-post-image .label_featured_post {
		background: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox span.metacomments i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox .tags i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.media-grid .author-avatar-div .avtar-img a:before {
		background: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox .tags.bdp_no_links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .taxonomies.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .taxonomies .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .metadatabox .tags {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid .content-inner {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.media-grid .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php
}
/* Timeline Blog Layout Template CSS. */
if ( 'timeline' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-right-color : <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline.blog-wrap .datetime,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap:before {
		background: none repeat scroll 0 0 <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline_year span.year_wrap {
		background: none repeat scroll 0 0 <?php echo esc_attr( $displaydate_backcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .post_hentry:before,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.timeline .timeline_bg_wrap:before{
		background:<?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-left: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:after {
		border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	/* left side design */
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;border-left: none;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:after {
		border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.right_side .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-left-color : <?php echo esc_attr( $templatecolor ); ?>;
	}
	/* right side design */
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .blog_template.bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-left: 8px solid <?php echo esc_attr( $templatecolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .blog_template.bdp_blog_template.timeline.blog-wrap.even_class .post_content_wrap:after {
		border-left: 8px solid <?php echo esc_attr( $templatecolor ); ?>;border-right: none;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .timeline_bg_wrap.left_side .bdp_blog_template.timeline.blog-wrap.odd_class .post_content_wrap:after {
		border-right-color : <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .post_content_wrap {
		border:1px solid <?php echo esc_attr( $templatecolor ); ?>;
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .post_content_wrap .blog_footer,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.timeline .author-avatar-div .avtar-img img.avatar{
		border-top: 1px solid <?php echo esc_attr( $templatecolor ); ?> ;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .label_featured_post span{
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .read_more a.btn-more{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .label_featured_post span{
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .post-icon {
		background:<?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .date_wrap .posted_by.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .date_wrap .posted_by a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .categories.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .tags a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .categories a {
		color:<?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .date_wrap .posted_by a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .categories a:hover {
		color:<?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .desc h3 {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		background:<?php echo esc_attr( $titlebackcolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		margin: 0;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .desc h3:hover a {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .desc h3 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size:  <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author{
		color:<?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.timeline a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.media-grid a.more-tag:hover {
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .timeline a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .date_wrap .posted_by,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .tags,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .categories,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .categories .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.timeline .author-avatar-div{
		border: 1px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline.blog-wrap .date_wrap .posted_by i,
	<?php echo esc_attr( $layout_id ); ?> .blog_footer span,.date_wrap{
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline .read_more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .timeline .read_more a.more-tag:hover {
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.timeline a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			border: 1px solid <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php 
	if ( isset($bdp_settings['timeline_design']) && 'design2' == $bdp_settings['timeline_design'] ) { ?>

		.bdp_post_list.timeline .bdp_blog_template.timeline,
		.blog_template.timeline_cover .bdp_blog_template.timeline {
			width: 50%;
			padding: 20px 60px !important;
			border-top: 7px solid;
			border-right: 7px solid;
			border-radius: 0 30px 0 0;
			position: relative;
			right: -3.5px;
			margin-bottom: 15px;
			float: left !important;
			clear: left !important;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline.even_class,
		.blog_template.timeline_cover .bdp_blog_template.timeline.even_class {
			margin: 130px 0 30px 0 !important;
			float: right !important;
			clear: right !important;
			border-right: none;
			border-left: 7px solid;
			border-radius: 30px 0 0 0;
			right: auto;
			left: -3.5px;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap {
			display: block;
			padding: 15px;
			position: relative;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap:after,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap:after {
			left: auto !important;
			right: 50px;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .bdp-format-icon,
		.blog_template.timeline_cover .bdp_blog_template.timeline .bdp-format-icon {
			width: 40px;
			border: 1px solid #fff;
			border-radius: 50%;
			height: 40px;
			display: flex;
			align-items: center;
			margin: 10px;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .bdp-post-format-icon,
		.blog_template.timeline_cover .bdp_blog_template.timeline .bdp-post-format-icon {
			background: none;
			border: 0px;
			text-align: center;
			width: 100%;
			font-size: 16px;
			line-height: normal;
			height: auto;
			vertical-align: middle;
			border-radius: 100%;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .bdp-post-icon,
		.blog_template.timeline_cover .bdp_blog_template.timeline .bdp-post-icon {
			display: block;
			width: 60px;
			height: 60px;
			line-height: 30px;
			border-radius: 50%;
			text-align: center;
			font-size: 21px;
			color: #fff;
			position: absolute;
			top: -35px;
			left: 0;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline.even_class .bdp-post-icon,
		.blog_template.timeline_cover .bdp_blog_template.timeline.even_class .bdp-post-icon {
			left: auto;
    		right: 0;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap:before,
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap:after,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap:before,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap:after {
			content: "";
			display: block;
			width: 10px;
			height: 50px;
			border-radius: 10px;
			border: 1px solid #fff !important;
			position: absolute;
			top: -35px;
			left: 50px;
		}
		.bdp_post_list.timeline {
			max-width: 1000px !important;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap {
			width: 80%;
		}
		.bdp_post_list.timeline .timeline_bg_wrap:before,
		.blog_template.timeline_cover .timeline_bg_wrap:before {
			background: none;
		}
		.bdp_post_list.timeline .timeline_bg_wrap .timeline_back,
		.blog_template.timeline_cover .timeline_bg_wrap .timeline_back {
			padding-top: 50px;
		}
		.bdp_post_list.timeline .design2 .timeline_bg_wrap .timeline_back:before, 
		.blog_template.timeline_cover .timeline_bg_wrap .timeline_back:before {
			content: "";
			width: 7px;
			height: 100%;
			margin: 0 auto;
			position: absolute;
			top: 80px;
			left: 0;
			right: 0;
		}
		.blog_template.bdp_blog_template.timeline.blog-wrap .post_hentry:before,
		.timeline_year {
			display: none;
		}
		.blog_template.bdp_blog_template.timeline.blog-wrap .categories a {
			text-transform: capitalize;
			border-bottom: 3px solid;
			border-color: #FFF;
		}
		.bdp_post_list.timeline .timeline.blog-wrap .entry-title {
			padding-top: 20px;
			padding-bottom: 20px;
		}
		.blog_template.bdp_blog_template.timeline.blog-wrap .categories {
			padding: 0;
		}
		.bdp_post_list.timeline .timeline.blog-wrap .datetime,
		.blog_template.timeline_cover .timeline.blog-wrap .datetime {
			background: none;
			position: relative;
			top: 0;
			left: 0;
			width: 145px;
			display: flex;
			padding-top: 0;
			height: 30px;
		}
		.bdp_post_list.timeline .timeline.blog-wrap .date_wrap,
		.blog_template.timeline_cover .timeline.blog-wrap .date_wrap {
			display: flex;
			flex-wrap: wrap;
			padding-bottom: 0;
		}
		.blog_template.bdp_blog_template.timeline.blog-wrap .post_content_wrap .blog_footer {
			border-top: none !important;
			padding: 0;
		}
		.bdp_blog_template.timeline .read_more {
			margin: 10px auto 0;
		}
		.bdp_post_list.timeline .timeline.blog-wrap .datetime .posted_by.bdp_has_links,
		.blog_template.timeline_cover .timeline.blog-wrap .datetime .posted_by.bdp_has_links {
			text-align: left;
		}
		.blog_template.timeline_cover .timeline_bg_wrap .timeline_back .timeline.blog-wrap {
			width: 100%;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline,
		.blog_template.timeline_cover .bdp_blog_template.timeline,
		.bdp_post_list.timeline .bdp_blog_template.timeline.even_class,
		.blog_template.timeline_cover .bdp_blog_template.timeline.even_class,
		.bdp_post_list.timeline .bdp_blog_template.timeline.odd_class,
		.blog_template.timeline_cover .bdp_blog_template.timeline.odd_class,
		.bdp_post_list.timeline .design2 .bdp_blog_template.timeline.even_class,
		.blog_template.timeline_cover.bdp_archive .main-timeline-class.design2 .bdp_blog_template.timeline.even_class,
		.bdp_post_list.timeline .design2 .bdp_blog_template.timeline,
		.blog_template.timeline_cover.bdp_archive .main-timeline-class.design2 .bdp_blog_template.timeline {
			border-color: <?php echo esc_attr( $template_color ) . '!important'; ?>;
		}
		.bdp_post_list.timeline .design2 .timeline_bg_wrap .timeline_back:before, 
		.blog_template.timeline_cover .timeline_bg_wrap .timeline_back:before {
			background: <?php echo esc_attr( $template_color ); ?>;
		}
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap:before,
		.bdp_post_list.timeline .bdp_blog_template.timeline .post_content_wrap:after,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap:before,
		.blog_template.timeline_cover .bdp_blog_template.timeline .post_content_wrap:after,
		.bdp_post_list.timeline .bdp_blog_template.timeline .bdp-post-icon,
		.blog_template.timeline_cover .bdp_blog_template.timeline .bdp-post-icon {
			background: <?php echo esc_attr( $template_alternative_color ); ?>;
		}
		@media screen and (max-width: 992px) {
			.bdp_post_list.timeline .bdp_blog_template.timeline.even_class,
			.bdp_post_list.timeline .bdp_blog_template.timeline.odd_class,
			.blog_template.bdp_archive_product_template.timeline_cover .bdp_blog_template.timeline.even_class,
			.blog_template.bdp_archive_product_template.timeline_cover .bdp_blog_template.timeline.odd_class,
			.blog_template.bdp_archive.timeline_cover .bdp_blog_template.timeline.odd_class,
			.blog_template.bdp_archive.timeline_cover .bdp_blog_template.timeline.even_class { 
				padding-right: 0 !important;
			}
		}
		@media screen and (max-width: 767px) {
			.bdp_post_list.timeline .bdp_blog_template.timeline,
			.bdp_post_list.timeline .bdp_blog_template.timeline.even_class,
			.bdp_post_list.timeline .bdp_blog_template.timeline.odd_class,
			.blog_template.timeline_cover .bdp_blog_template.timeline.odd_class,
			.blog_template.timeline_cover .bdp_blog_template.timeline.even_class {
				width: 100%;
				margin: 0 0 30px 0;
				border-right: none;
				border-left: 7px solid;
				border-radius: 30px 0 0 0;
				right: auto;
				left: 0;
			}
			.bdp_post_list.timeline .bdp_blog_template.timeline .bdp-post-icon,
			.blog_template.timeline_cover .bdp_blog_template.timeline .bdp-post-icon {
				left: auto;
				right: 0;
			}
			.bdp_post_list.timeline .bdp_blog_template.timeline.even_class,
			.blog_template.timeline_cover .bdp_blog_template.timeline.even_class {
				margin: 0 !important
			}
		}
	<?php
	}
}
/* Cool Horizontal Blog Layout Template CSS. */
if ( 'cool_horizontal' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title > a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title > a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-node-desc span,
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node:after,
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-item.lb-node-hover:before,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine .lb-item.lb-node-hover:before,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine .lb-node-desc span,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine a.lb-line-node:after{
		background-color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node.active:after,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine a.lb-line-node.active:after  {
		border-color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-item.lb-node-hover:after,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine .lb-item.lb-node-hover:after {
		border-top-color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node.active,
	<?php echo esc_attr( $layout_id ); ?> #content .logbook.flatLine a.lb-line-node.active {
		color: <?php echo esc_attr( $titlehovercolor ); ?>
	}

	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .categories {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .mauthor.bdp_no_link,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .bdp_no_link i,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .categories .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .categories.categories_link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal a,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title .mdate a,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title .mdate i,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags.tag_link,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags a,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .categories a,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .mauthor,
	<?php echo esc_attr( $layout_id ); ?> .horizontal.mdate i,
	<?php echo esc_attr( $layout_id ); ?> .horizontal.mauthor i,
	<?php echo esc_attr( $layout_id ); ?> .horizontal.mcomments i{
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal a:hover,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title .mdate a:hover,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer .categories a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title .mdate a{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .mdate i,
	<?php echo esc_attr( $layout_id ); ?> .mauthor i,
	<?php echo esc_attr( $layout_id ); ?> .mcomments i,
	<?php echo esc_attr( $layout_id ); ?> .tags i,
	<?php echo esc_attr( $layout_id ); ?> .categories i {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-title .mdate,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .metadatabox,
	<?php echo esc_attr( $layout_id ); ?> .horizontal .blog_footer {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .post-content-area .post_content {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal a.more-tag {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;
			border-color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .horizontal a.more-tag:hover {
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.horizontal a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> #content .logbook .lb-node-desc > span:after {
		border-color: #dd5555 transparent transparent;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.horizontal .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.horizontal .post_content p{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .horizontal .label_featured_post span {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorecolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php
}
/* Overlay Horizontal Blog Layout Template CSS. */
if ( 'overlay_horizontal' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-content-area .post-title{
		padding: 0 2px;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-content-area .post_content {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal a.more-tag {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal a.more-tag:hover {
			background-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color:<?php echo esc_attr( $readmorehovercolor ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal a,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-title .mdate a,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .blog_footer .tags a,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .blog_footer .categories a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-content-area .blog_footer .categories,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-content-area .blog_footer .tags {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal a:hover,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-title .mdate a:hover,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .blog_footer .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .blog_footer .categories a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post-content-area .metadatabox i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node.active, #content .logbook.flatLine a.lb-line-node.active{
		color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-item.lb-node-hover:after, #content .logbook.flatLine .lb-item.lb-node-hover:after{
		border-color: <?php echo esc_attr( $templatecolor ); ?> rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node.active:after, #content .logbook.flatLine a.lb-line-node.active:after{
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-node-desc span, #content .logbook.flatLine .lb-node-desc span,
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine .lb-item.lb-node-hover:before, #content .logbook.flatLine .lb-item.lb-node-hover:before,
	<?php echo esc_attr( $layout_id ); ?> .logbook.flatLine a.lb-line-node:after, #content .logbook.flatLine a.lb-line-node:after{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .post_content_wrap .label_featured_post {
		background: <?php echo esc_attr( $templatecolor ); ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.overlay_horizontal a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .overlay_horizontal .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Story Blog Layout Template CSS. */
if ( 'story' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .story .blog_header{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		line-height: 1.5;
		padding: 0 2px;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .blog_header,
	<?php echo esc_attr( $layout_id ); ?> .story .blog_header a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .blog_header a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .story a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorecolor ); ?>; <?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .story a.more-tag:hover {
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.story a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .story .blog_footer,
	<?php echo esc_attr( $layout_id ); ?> .story .post_content,
	<?php echo esc_attr( $layout_id ); ?> .story .post-metadata,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .category-link .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .blog_template .social-component a {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		border-color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story a,
	<?php echo esc_attr( $layout_id ); ?> .story .post-metadata .author-inner,
	<?php echo esc_attr( $layout_id ); ?> .story .post-media a,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .tags a,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .category-link.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .category-link a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story a:hover,
	<?php echo esc_attr( $layout_id ); ?> .story .post-media a:hover,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .story .footer_meta .category-link a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .line-col-bottom-secound,
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon-left {
		background : <?php echo esc_attr( $template_alternative_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .entity-content-right .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .story .entity-content-left .label_featured_post {
		background : <?php echo esc_attr( $template_alternative_color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .line-col-top,
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon-rights {
		background : <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .line-col-left {
		border-color: <?php echo esc_attr( $template_alternative_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .line-col-right {
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon-rights.date-icon-arrow-bottom:after {
		border-top-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon-left.date-icon-arrow-bottom:after {
		border-top-color: <?php echo esc_attr( $template_alternative_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .startup{
		background: <?php echo esc_attr( $story_startup_background ); ?>;
		color: <?php echo esc_attr( $story_startup_text_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .startup.ending{
		background: <?php echo esc_attr( $story_ending_background ); ?>;
		color: <?php echo esc_attr( $story_ending_text_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon-arrow-bottom:before {
		border-top-color: <?php echo esc_attr( $story_startup_border_color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .startup,
	<?php echo esc_attr( $layout_id ); ?> .story .date-icon,
	<?php echo esc_attr( $layout_id ); ?> .story .blog_post_wrap .post-media img {
		border-color: <?php echo esc_attr( $story_startup_border_color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .dote span {
		background-color: <?php echo esc_attr( $story_startup_border_color ); ?>
	}

	<?php echo esc_attr( $layout_id ); ?> .story .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Easy Timeline Blog Layout Template CSS. */
if ( 'easy_timeline' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline > li{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post-title{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post-title a {
		background-color: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post_content {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline .categorry-inner,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .easy-timeline .metadatabox {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .comment-count-inner,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .author-inner,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .date-inner,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .metadatabox span,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .categories .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .categories .bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .tags.bdp_no_links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline a.more-tag {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .blog_footer .link-lable {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline a,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post-title .mdate a,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .metadatabox span.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .tags,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .blog_footer .tags a,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .categories,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .blog_footer .categories a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline a:hover,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .post-title .mdate a:hover,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .blog_footer .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline-wrapper .easy-timeline .blog_footer .categories a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline > li{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline .mdate i,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline .metadatabox span i,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline .comments-link,
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline .category {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .easy-timeline .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .story .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Explore Blog Layout Template CSS. */
if ( 'explore' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .post-title,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .label_featured_post {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .grid-overlay,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .label_featured_post {
		background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $grid_hoverback_color, 0.5 ) ); ?> none repeat scroll 0 0;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .metabox-top,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .comments-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .label_featured_post {
		color: <?php echo esc_attr( $color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .category-link .seperater,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .category-link a {
		color: <?php echo esc_attr( $color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .category-link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .category-link, 
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header .tags a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header a:hover i {
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .blog_header a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore.large-col,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore.small-col{
		padding-left:<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		padding-right:<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		padding-bottom:<?php echo esc_attr( $grid_col_space ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.explore .bdp-grid-row{
		margin-left:-<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		margin-right:-<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
	}
	<?php
}
/* Hoverbic Blog Layout Template CSS. */
if ( 'hoverbic' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header .post-title{
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header .post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .label_featured_post {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .label_featured_post {
		background: <?php echo '' != $grid_hoverback_color ? esc_attr( Bdp_Utility::hex2rgba( $grid_hoverback_color, 0.8 ) ) : 'transparent';  ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .comments-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .author,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .label_featured_post {
		color: <?php echo esc_attr( $color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .blog_header a:hover i{
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic.small-col,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic.full-col {
		padding-left:<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		padding-right:<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		padding-bottom:<?php echo esc_attr( $grid_col_space ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp-grid-row{
		margin-left:-<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
		margin-right:-<?php echo esc_attr( ( $grid_col_space / 2 ) ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .category-link {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .category-link a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.hoverbic .tags a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php
}
/* My Diary Blog Layout Template CSS. */
if ( 'my_diary' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .my_diary_wrapper:before {
		background-color: <?php echo esc_attr( $background ); ?>;opacity: 0.03;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post.bdp_blog_template div.post_content,
	<?php echo esc_attr( $layout_id ); ?> .diary-post .diary-thumb .label_featured_post {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;  <?php } ?>
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .metadatabox,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) - 2; ?>px;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories span,
	<?php echo esc_attr( $layout_id ); ?> .metadatabox div span,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .post-comment i,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags i {
		font-size: <?php echo esc_attr( $content_fontsize ); ?>px;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories .seperater,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories a,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .metadatabox a,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .metadatabox .mauthor.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .post-comment a,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .categories a:focus,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .metadatabox a:hover,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .metadatabox a:focus,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .post-comment a:hover,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .post-comment a:focus,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter .tags a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title {
		font-size:<?php echo esc_attr( $template_titlefontsize ) . 'px;'; ?>
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post .diary-thumb .label_featured_post {
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title a:before,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title a:after {
		background-color: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title a:hover,
	<?php echo esc_attr( $layout_id ); ?> .diary-posthead .post-title a:focus {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-postcontent:before {
		border-color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $contentcolor, 0.5 ) ); ?>;
		color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $contentcolor, 0.5 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post.bdp_blog_template .social-component a {
		border-color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $contentcolor, 0.5 ) ); ?>;
		color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $contentcolor, 0.5 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post .diary-thumb .label_featured_post {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.diary-post a.more-tag {
			color: <?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.diary-post a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.diary-post a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .diary-post .read-more-div .more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border:none;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .read-more-div .more-tag:hover,
		<?php echo esc_attr( $layout_id ); ?> .read-more-div .more-tag:focus {
			background-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .diary_postfooter {
		border-color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $readmorebackcolor, 0.3 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .diary-post .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .diary-post.bdp_blog_template div.post_content > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .diary-post.bdp_blog_template .post_content:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
			font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $firstletter_contentcolor ); ?>;float: none;margin-right:5px;line-height: 0;
		}
		<?php echo esc_attr( $layout_id ); ?> .diary-post.bdp_blog_template div.post_content {
			margin-top: <?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
	<?php } ?>
	<?php
}
/* Elina Blog Layout Template CSS. */
if ( 'elina' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-share-div i,
	<?php echo esc_attr( $layout_id ); ?> .elina_wrapper .elina-post-wrapper {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-title {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-title a {
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area:before,
	<?php echo esc_attr( $layout_id ); ?> .post-content-area:after,
	<?php echo esc_attr( $layout_id ); ?> .elina-footer{
		background-color: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-title a:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-title a:focus {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .categories-outer .categories .categories-inner.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags.bdp_no_links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .categories-outer .categories .categories-inner,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .categories-outer .categories .categories-inner a,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .elina-footer a.comments-link,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags a,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-share-div a.post-share,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .author-name a,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .bdp-wrapper-like .bdp-like-button {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post_content,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-media .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .elina-postfooter{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .bdp-wrapper-like .bdp-like-button:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .categories-outer .categories .categories-inner a:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .elina-footer a.comments-link:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-content-area .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-share-div a.post-share:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper .post-media .label_featured_post {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:before,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:after {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorecolor ); ?>;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>


	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:hover:before,
	<?php echo esc_attr( $layout_id ); ?> .elina-post-wrapper a.more-tag:hover:after {
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		color: <?php echo esc_attr( $readmorehovercolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .elina .author-avatar-div .fakegb {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php
}
/* Masonry Timeline Blog Layout Template CSS. */
if ( 'masonry_timeline' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .social-component {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-content-area .post-title {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-title a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.masonry-timeline-wrapp .bdp-wrapper-like i,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .metadatabox span.mauthor i,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .metadatabox span.mauthor.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .categories.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .tags.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .year-number,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .metadatabox {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .metadatabox span.mauthor,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .categories,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .tags,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .category a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-content-area .post_content{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .image-blog .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px;'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp a.more-tag {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorecolor ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp a.more-tag:hover{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .image-blog .label_featured_post {
		color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .metadatabox a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .category a:hover,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .metadatabox a:hover,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .metadatabox a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .social-component .social-share a.close-div {
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-footer .social-component .social-share a.close-div:hover {
		background: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-title a,
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-title{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp .post-title {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
	if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp.bdp_blog_template div.post_content > *:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp.bdp_blog_template div.post_content > p:first-child:first-letter,
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp.bdp_blog_template .post_content:first-letter {
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
			font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $firstletter_contentcolor ); ?>;float:none;margin-right:0;line-height: 0;
		}
		<?php echo esc_attr( $layout_id ); ?> .masonry-timeline-wrapp.bdp_blog_template div.post_content {
			margin-top: <?php echo esc_attr( ( $firstletter_fontsize / 2 ) ) . 'px'; ?>;
		}
	<?php } ?>
	<?php
}
/* Sallet Slider Blog Layout Template CSS. */
if ( 'sallet_slider' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .bdp-post-image{
		height: <?php echo esc_attr( $slider_image_height ) . 'px'; ?>;overflow: hidden;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sallet_slider .blog_header > div > div > div{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sallet_slider img{height:100%}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .blog_header h2{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .blog_header h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .blog_header h2 a:hover{
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor a span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-date a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-comment a {
		color: <?php echo esc_attr( $color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor:hover i,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor a:hover span {
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sallet_slider .blog_header > div > div > div:before{
		background: <?php echo isset( $bdp_settings['winter_category_color'] ) ? esc_attr( $bdp_settings['winter_category_color'] ) : '#FF00AE'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sallet_slider .post_content .post_content-inner,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sallet_slider .post_content .post_content-inner p{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-date i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-date a:hover i {
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-date,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .tags,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .tags i,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .category-link,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .bdp-wrapper-like a{
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sallet_slider .label_featured_post {
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .mauthor a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-date a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .metadatabox .post-comment a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.sallet_slider .label_featured_post{
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .post_content a.more-tag {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .sallet_slider  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .sallet_slider  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .sallet_slider .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .sallet_slider .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sallet_slider .post_content a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	.bdp_blog_template.sallet_slider.right {
		<?php echo ( '' != $bdp_settings['winter_category_color'] ) ? 'background: ' . esc_attr( $bdp_settings['winter_category_color'] ) . ' ;' : '';  ?>
	}
	<?php
}
/* Colorful Sliding Blog Layout Template CSS. */
if ( 'colorful_sliding' === $bdp_theme ) {
	if ( '' != $slider_image_height ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+1) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+2) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+3) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+4) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+5) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor5 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .slides > li:nth-child(6n+6) .post_hentry::before {
			background: <?php echo esc_attr($templatecolor6 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .bdp-post-image{
			height: <?php echo esc_attr( $slider_image_height ) . 'px'; ?>;overflow: hidden;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.colorful_sliding img{height:100%}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .blog_header h2 {
			background: <?php echo esc_attr( $titlebackcolor ); ?>;
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .blog_header h2 a{
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .blog_header h2 a:hover{
			color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .mauthor a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .mauthor a span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-date a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-comment a{
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .mauthor a:hover span {
			color: <?php echo esc_attr( $linkhovercolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .label_featured_post span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .category-link a{
			background: <?php echo isset( $bdp_settings['winter_category_color'] ) ? esc_attr( $bdp_settings['winter_category_color'] ) : '#FF00AE'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.colorful_sliding .post_content .post_content-inner,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.colorful_sliding .post_content .post_content-inner p{
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .mauthor,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-date,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-comment,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .tags,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .tags i,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.colorful_sliding .label_featured_post,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .category-link{
			<?php
			if ( isset( $content_font_weight ) && $content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_line_height ) && $content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			color: <?php echo esc_attr( $contentcolor ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .mauthor a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-date a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .metadatabox .post-comment a:hover{
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .colorful_sliding .read-more a.more-tag {
			<?php
			if ( isset( $bdp_readmore_button_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
			<?php
			if ( isset( $readmorebuttonborderradius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .colorful_sliding .read-more a.more-tag:hover{
			<?php
			if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $readmore_button_hover_border_radius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .post_content a.more-tag{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			<?php
			if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
				?>
				background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
			<?php
			if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
				?>
				background: none;<?php } ?>
		}
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .post_content a.more-tag:hover{
				background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
				color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.colorful_sliding .post_content a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
		<?php
	}
}
/* Crayon Slider Blog Layout Template CSS. */
if ( 'crayon_slider' === $bdp_theme ) {
	if ( '' != $slider_image_height ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.crayon_slider .blog_header {
			background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $templatecolor, 0.8 ) ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .bdp-post-image {
			height: <?php echo esc_attr( $slider_image_height ) . 'px'; ?>;overflow:hidden
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.crayon_slider img {
			height: <?php echo esc_attr( $slider_image_height ) . 'px'; ?>;max-height: 100%;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .blog_header h2 {
			background: <?php echo esc_attr( $titlebackcolor ); ?>;
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .blog_header h2 a{
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .blog_header h2 a:hover{
			color:<?php echo esc_attr( $titlehovercolor ); ?> !important;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-date a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-comment a{
			color: <?php echo esc_attr( $color ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor span:hover {
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .category-link a,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.crayon_slider .blog_header:before{
			background: <?php echo isset( $bdp_settings['winter_category_color'] ) ? esc_attr( $bdp_settings['winter_category_color'] ) : '#FF00AE'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor span.bdp-no-links,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.crayon_slider .post_content .post_content-inner,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.crayon_slider .post_content .post_content-inner p{
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-date,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-comment,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .category-link{
			<?php
			if ( isset( $content_font_weight ) && $content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_line_height ) && $content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			color: <?php echo esc_attr( $contentcolor ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags .link-lable,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags .link-lable i,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags.tag_link {
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags i,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags {
			color: <?php echo esc_attr( $color ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .mauthor a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-date a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .metadatabox .post-comment a:hover{
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .tags > a:hover {
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .post_content a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			<?php
			if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
				?>
				background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
			<?php
			if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
				?>
				background: none;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .label_featured_post{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;
			<?php
			if ( isset( $content_font_weight ) && $content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_line_height ) && $content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more a.more-tag {
			<?php
			if ( isset( $bdp_readmore_button_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
			<?php
			if ( isset( $readmorebuttonborderradius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .sallet_slider .read-more a.more-tag:hover{
			<?php
			if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $readmore_button_hover_border_radius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .post_content a.more-tag:hover{
				background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
				color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.crayon_slider .post_content a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
		<?php
		if ( isset($bdp_settings['cd_design_type']) && 'design2' == $bdp_settings['cd_design_type'] ) {  ?>
			.bdp_blog_template.crayon_slider .blog_header { 
				width: 100%;
				padding: 25px 15px 10px 15px;
			}
			.blog_template.crayon_slider ul.flex-direction-nav .flex-prev {
				right: 30px;
				left: auto;
			}
			.blog_template.crayon_slider .flex-direction-nav a {
				top: 30px;
			}
			.slides.design2 .post_metadata .category-link {
				display: flex;
			}
			.slides.design2 .post_metadata .category-link a:nth-child(3n+1) {
				background: #1abc9c;
			}
			.slides.design2 .post_metadata .category-link a:nth-child(3n+2) {
				background: #3aadff;
			}
			.slides.design2 .post_metadata .category-link a:nth-child(3n+3) {
				background: #9b59b6;
			}
			.blog_template.crayon_slider .flex-control-nav {
				bottom: 20px;
				text-align: center;
			}
			.blog_template.crayon_slider .flex-control-paging li a {
				font-size: 0;
				background: rgba(255, 255, 255, 0.5);
			}
			.blog_template.crayon_slider .flex-control-paging li a.flex-active {
				background: rgba(0, 0, 0, 0.9);
			}
			.blog_template.crayon_slider .metadatabox > div {
				margin-right: 0;
			}
			.blog_template.crayon_slider .metadatabox .mauthor,
			.blog_template.crayon_slider .metadatabox .post-date,
			.slides.design2 .post_metadata .category-link a {
				text-transform: uppercase !important;
			}
			@media screen and (min-width: 641px) {
				.slides.design2 .blog_header {
					display: flex;
				}
				.slides.design2 .post_metadata {
					width: 50%;
				}
				.slides.design2 .post_content {
					padding: 10px !important;
					border-left: 1px solid #FFF;
					width: 50%;
				}
			}
			<?php } 
	}
}
/* Sunshiny Slider Blog Layout Template CSS. */
if ( 'sunshiny_slider' === $bdp_theme ) {
	if ( '' != $slider_image_height ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sunshiny_slider .post_hentry:before {
			background: linear-gradient(to top, <?php echo esc_attr( Bdp_Utility::hex2rgba( $templatecolor, 0.8 ) ); ?> 0px, rgba(0, 0, 0, 0) 100%);
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .bdp-post-image{
			height: <?php echo esc_attr( $slider_image_height ) . 'px'; ?>;overflow: hidden;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.sunshiny_slider img{height:100%}
		<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .bdp_blog_template.sunshiny_slider img{width:100%}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .blog_header h2 {
			background: <?php echo esc_attr( $titlebackcolor ); ?>;
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .blog_header h2 a{
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .blog_header h2 a:hover{
			color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .mauthor a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .mauthor a span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-date a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-comment a{
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .mauthor a:hover span {
			color: <?php echo esc_attr( $linkhovercolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .label_featured_post span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .category-link a{
			background: <?php echo isset( $bdp_settings['winter_category_color'] ) ? esc_attr( $bdp_settings['winter_category_color'] ) : '#FF00AE'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sunshiny_slider .post_content .post_content-inner,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sunshiny_slider .post_content .post_content-inner p{
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .mauthor,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-date,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-comment,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .tags,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .tags i,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.sunshiny_slider .label_featured_post,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .category-link{
			<?php
			if ( isset( $content_font_weight ) && $content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_line_height ) && $content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			color: <?php echo esc_attr( $contentcolor ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .mauthor a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-date a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .metadatabox .post-comment a:hover{
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .sunshiny_slider .read-more a.more-tag {
			<?php
			if ( isset( $bdp_readmore_button_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
			<?php
			if ( isset( $readmorebuttonborderradius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .sunshiny_slider .read-more a.more-tag:hover{
			<?php
			if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $readmore_button_hover_border_radius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .post_content a.more-tag{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			<?php
			if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
				?>
				background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
			<?php
			if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
				?>
				background: none;<?php } ?>
		}
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .post_content a.more-tag:hover{
				background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
				color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.sunshiny_slider .post_content a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
		<?php
	}
}
/* Pretty Blog Layout Template CSS. */
if ( 'pretty' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .right-content-wrapper,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .bdp-post-image.post-has-image:before {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .blog_header .post_date{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .left-content-wrapper{
		background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $templatecolor, 0.5 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .left-content-wrapper.post-has-image:before{
		border-bottom-color: <?php echo esc_attr( Bdp_Utility::hex2rgba( $templatecolor, 0.5 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags > a:hover{
		border-color: <?php echo esc_attr( $linkhovercolor ); ?>;
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags > span{
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .blog_header h2 {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color: <?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .blog_header h2 a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.pretty .author-avatar-div .author_content .author{
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .blog_header h2 a:hover {
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .entry-container .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.pretty .author_content p{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .date > span{
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .category-link ,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty p,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .metadatabox {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .entry-container .label_featured_post {
		border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
		background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .entry-meta .read-more a{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none; <?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .entry-meta .read-more a:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}

		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags.bdp_no_links > span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .metadatabox > span i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags > span a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .metadatabox > span.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a,
	<?php echo esc_attr( $layout_id ); ?> .author-avatar-div.bdp_blog_template .social-component a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .social-component a {
		border-color: <?php echo esc_attr( $color ); ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .post-meta-cats-tags .tags > span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive .author-avatar-div .author_content .author a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.pretty .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.pretty .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Tagly Blog Layout Template CSS. */
if ( 'tagly' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .categories a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .categories {
		color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .left-side-area,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .label_featured_post {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .left-side-area:before{
		border-top-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .social-component:before{
		border-bottom-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area h2.bdp_post_title:before{
		background-color: <?php echo esc_attr( $templatecolor ); ?>;
		box-shadow: 6px -2px 0 <?php echo esc_attr( $templatecolor ); ?>;
		height: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox span a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox .author.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox .author a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags.bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area h2.bdp_post_title a{
		color: <?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area h2.bdp_post_title a:hover{
		color: <?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area h2.bdp_post_title{
		font-size:<?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background:<?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox > span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .post_content p,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.tagly .author_content p{
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox .author i,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .metadatabox span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area .tags{
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area a.more-tag{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			padding: 10px 20px;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-radius: 5px;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area a.more-tag:hover{
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.tagly .right-side-area a.more-tag{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .tagly .author-avatar-div.bdp_blog_template {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .tagly .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .tagly .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Brite Blog Layout Template CSS. */
if ( 'brite' === $bdp_theme ) {
	$brite_tag_bgcolor = ( isset( $bdp_settings['winter_category_color'] ) && '' != $bdp_settings['winter_category_color'] ) ? $bdp_settings['winter_category_color'] : '#0e83cd'; 
	?>
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .brite-post-inner-wrapp{
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-title h2 {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-title a h2 {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-title a:hover h2,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-title a:focus h2 {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-header-meta .date-meta,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-tags,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-content-body .post-content,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-footer,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-meta {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-author .author-name {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like a i,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like a:hover i,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like a:focus i {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .date-meta > a,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-category > a,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-meta > a,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-comment > a,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like > a,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-categories > a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .date-meta > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .date-meta > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-category > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-category > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-categories > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-categories > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-meta > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-meta > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-comment > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-comment > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .bdp-wrapper-like > a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-tags span.tag:before {
		border-top: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid transparent;
		border-bottom: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid transparent;
		border-right: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid;
		border-right-color: <?php echo esc_attr( $brite_tag_bgcolor ); ?>;
		left: -<?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-tags span.tag {
		margin-left: <?php echo esc_attr( $content_fontsize ) + 15 . 'px'; ?>;
		background: <?php echo esc_attr( $brite_tag_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .label_featured_post {
		background: <?php echo esc_attr( $brite_tag_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-tags span.tag:hover:before {
		border-top: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid transparent;
		border-bottom: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid transparent;
		border-right: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> solid;
		border-right-color: <?php echo esc_attr( $contentcolor ); ?>;
		left: -<?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper .post-tags span.tag:hover {
		margin-left: <?php echo esc_attr( $content_fontsize ) + 15 . 'px'; ?>;
		background: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper a.more-tag:hover {
			background:<?php echo esc_attr( $readmorehoverbackcolor ); ?>;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .brite-post-wrapper a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Chapter Blog Layout Template CSS. */
if ( 'chapter' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-container {
		border-color:  <?php echo esc_attr( $templatecolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .chapter-header,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .chapter-footer,
	<?php echo esc_attr( $layout_id ); ?> .chapter .chapter-post-container .label_featured_post {
		background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $background, 0.8 ) ); ?>;
	}
	<?php if ( isset( $bdp_hide_hover_post ) && '0' == $bdp_hide_hover_post ) { ?>
		<?php echo esc_attr( $layout_id ); ?> .chapter-post-container:hover .bdp-pinterest-share-image{visibility:visible;opacity:1}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-title h2 {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-title a h2 {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-title a:hover h2,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-title a:focus h2{
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .date-meta,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-author,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-categories,
	<?php echo esc_attr( $layout_id ); ?> .chapter .chapter-post-container .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .bdp-wrapper-like,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .date-meta > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-author > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-categories > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-meta > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .bdp-wrapper-like > a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .date-meta > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .date-meta > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-author > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-author > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-categories > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-categories > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-meta > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-meta > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment:hover > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment:focus > a,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment:hover i,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .post-comment:focus i,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .bdp-wrapper-like > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .bdp-wrapper-like > a:focus,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .read-more-div a:hover,
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .read-more-div a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .chapter-post-wrapper .read-more-div a {
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php
}
/* Roctangle Blog Layout Template CSS. */
if ( 'roctangle' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post_date,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-image .label_featured_post {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .post-title {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .post-title h2 a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .post-title h2 a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-image .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .link-lable,
	.roctangle-post-wrapper .post-content-wrapper .category-link,
	.roctangle-post-wrapper .post-content-wrapper .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .tags a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .category-link span,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .tags span {
		border-color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .tags a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .post-meta-div .author a,
	<?php echo esc_attr( $layout_id ); ?> .post-meta-div .bdp-wrapper-like .bdp-count {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-comment a {
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-meta-div span i,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-meta-div span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-comment i {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-comment:hover i,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-comment:hover a,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-image-wrap .post-meta-wrapper .post-meta-div span:hover i,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-meta-div .author a:hover,
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-meta-div .bdp-wrapper-like:hover .bdp-count {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .content-footer a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none; <?php } ?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
	}

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.roctangle-post-wrapper  a.more-tag {
			color: <?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.roctangle-post-wrapper a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.roctangle-post-wrapper a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .post-content-wrapper .content-footer .read-more a.more-tag:hover {
		color: <?php echo esc_attr( $readmorehovercolor ); ?>;
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.region .author-avatar-div {
		background: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .roctangle-post-wrapper .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Glamour Blog Layout Template CSS. */
if ( 'glamour' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .glamour-inner .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .glamour-inner .post-title h2 a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		margin-top: 0;
		margin-bottom: 10px;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}   if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .glamour-inner .post-title h2 a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .glamour-inner .post-content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .post-categories,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .tags,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .glamour-meta div,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .post-categories a,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .tags a,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .glamour-meta div > a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		background: <?php echo esc_attr( $color ); ?>;
		border-color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .footer-entry .glamour-meta div .bdp-separator,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .tags,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .post-author,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .post-comment i {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .post-categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .glamour-meta div > a:hover,
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .glamour-meta div a:hover i {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-blog .glamour-opacity {
		background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $background, 0.4 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-social-cover {
		background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $background, 0.8 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner a.more-tag {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-social-cover .glamour-social-links-closed i,
	<?php echo esc_attr( $layout_id ); ?> .footer-entry .glamour-footer-icon span a i {
		color: <?php echo esc_attr( $color ); ?>;
		border-color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-social-cover .glamour-social-links-closed i:hover,
	<?php echo esc_attr( $layout_id ); ?> .footer-entry .glamour-footer-icon span a i:hover {
		background: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .glamour-inner .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Cover Blog Layout Template CSS. */
if ( 'cover' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .cover-post {
		background: linear-gradient(to right, <?php echo esc_attr( $templatecolor ); ?> 0%,<?php echo esc_attr( $templatecolor ); ?> 20%,<?php echo esc_attr( $background ); ?> 20%,<?php echo esc_attr( $background ); ?> 50%);
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog h2.bdp_post_title,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog h2.bdp_post_title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog h2.bdp_post_title a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-categories,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-cover-tag,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta span {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-categories.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-cover-tag.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-cover-tag .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta span.bdp-no-links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-categories a,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-cover-tag a,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta span a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-cover-tag a:hover,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta span a:hover span,
	<?php echo esc_attr( $layout_id ); ?> .cover-post .cover-blog .bdp-post-meta .comment:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.cover-post .post_content a.more-tag  {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>; <?php } ?>
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.cover-post .post_content a.more-tag:hover, <?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .cover-post .label_featured_post{
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.cover-post .post_content a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?>  .cover-post .label_featured_post{
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	.blog_template.bdp_archive.cover .bdp-author-avatar {background: linear-gradient(to right, <?php echo esc_attr( $templatecolor ); ?> 0%,<?php echo esc_attr( $templatecolor ); ?> 20%,<?php echo esc_attr( $author_bgcolor ); ?> 20%,<?php echo esc_attr( $author_bgcolor ); ?> 50%);}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Fairy Blog Layout Template CSS. */
if ( 'fairy' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .fairy_wrap .fairy-social-cover,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .fairy_wrap .fairy_footer,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .fairy_wrap,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .label_featured_post {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	/* <?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .fairy_wrap {
		background: <?php echo esc_attr( $background ); ?>;
	} */
	<?php echo esc_attr( $layout_id ); ?> .fairy .bdp-post-image h2.post_title,
	<?php echo esc_attr( $layout_id ); ?> .fairy .bdp-post-image h2.post_title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .post_content_area h2.post_title,
	<?php echo esc_attr( $layout_id ); ?> .fairy .post_content_area h2.post_title a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .post_content_area h2.post_title a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy.even_class .fairy_wrap .custom-categories{text-align:right;display:block}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area .custom-categories.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area .custom-categories .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy.even_class .post_content_area .post_title {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy.even_class .post_content_area .post_title a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy.even_class .post_content_area .post_title a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> div.bdp-post-image .post-meta-cover,
	<?php echo esc_attr( $layout_id ); ?> div.bdp-post-image .post-meta-cover a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> div.bdp-post-image .post-meta-cover a:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .bdp-post-image h2.post_title a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important	;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .label_featured_post {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .label_featured_post{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area .custom-categories,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox .metacomments,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox .metacomments.bdp-no-links:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy_footer .fairy-post-share,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy_footer span,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox span {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area a.more-tag {
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .post_content_area a,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy_footer span a,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox span a,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy-social-cover .fairy-social-links-closed,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .label_featured_post {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy_footer .fairy-post-share:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy-social-cover .fairy-social-links-closed:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox .metacomments:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .fairy_footer span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .fairy .fairy_wrap .metadatabox span a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.fairy .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Famous Blog Layout Template CSS. */
if ( 'famous' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid .post-body-div {
		background: <?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.famous .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .post-body-div h2.post_title,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .post-body-div h2.post_title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}   if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid .post-body-div .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .post-body-div h2.post_title a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .post-body-div .post_content,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .post-tags span.link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid a.more-tag {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories.bdp_no_links .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories.bdp_has_links .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories.bdp_has_links .seperater,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .custom-categories a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .category-link,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .post-tags,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .post-tags a,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .metadatabox > span,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .metadatabox > span a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .category-link.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .metadatabox > span.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .post-tags.bdp-no-links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .post-tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .metadatabox > span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .famous-grid .bdp_post_content .metadatabox > span:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid .post-body-div .label_featured_post {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid a.more-tag{
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.famous-grid a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	
	<?php
}
/* Step Blog Layout Template CSS. */
if ( 'steps' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li:before,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.steps .author-avatar-div,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.steps .author-avatar-div:before {
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li .steps-postformate {
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
		color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li:before {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.steps .author-avatar-div,
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.steps .author-avatar-div:before {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps:before,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps:after {
		background: <?php echo esc_attr( $templatecolor ); ?>;
		box-shadow: <?php echo esc_attr( Bdp_Utility::hex2rgba( $templatecolor, 0.3 ) ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps h2.post-title {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps h2.post-title a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .steps > li .label_featured_post span {
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post_content {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .steps .post_content .more-tag {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?> ;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .categories,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .categories a,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .tags,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .tags a,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta > span,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta > span a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta span:hover,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta span a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta > span.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .post-meta > span.bdp-no-links:hover,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .tags.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .steps .categories.bdp-no-links {
		color:<?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}

	<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .read-more-div a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .steps-wrapper .read-more-div a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .steps-wrapper  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>


	<?php
}
/* Minimal Blog Layout Template CSS. */
if ( 'minimal' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .minimal-post-container .minimal-entry,
	<?php echo esc_attr( $layout_id ); ?> .minimal-entry .minimal-social-cover {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_archive.minimal .author-avatar-div {
		background-color: <?php echo esc_attr( $author_bgcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-title a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal-post-container .minimal-entry:after {
		background: <?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-content,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .minimal-post-container .label_featured_post {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .tags,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-categories {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-categories a,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .tags a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template .minimal-entry .minimal-footer span,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-footer span,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-footer span a,
	<?php echo esc_attr( $layout_id ); ?> .minimal-social-cover .minimal-social-share-btn-close a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-categories a:hover,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-footer span:hover,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-footer span:hover a,
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-footer span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .minimal-social-cover .minimal-social-share-btn-close a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover a.more-tag:hover {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .minimal .minimal-content-cover a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .bdp_blog_template .social-component {width: calc(100% - 24px)}
	<?php echo esc_attr( $layout_id ); ?> .minimal .bdp_blog_template .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .minimal .bdp_blog_template .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Clicky Blog Layout Template CSS. */
if ( 'clicky' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.clicky,
	<?php echo esc_attr( $layout_id ); ?> .bdp_archive.clicky .bdp-author-avatar {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_add_to_cart_wrap {
		text-align:<?php echo esc_attr( $bdp_addtocartbutton_alignment ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap h2.post-title,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap h2.post-title a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}   if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap h2.post-title a:hover {
		color:<?php echo esc_attr( $titlehovercolor );  ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post_content,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post-meta-cats-tags .link-lable {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.clicky .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post-meta-cats-tags,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post-meta-cats-tags a {
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span:hover,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span:hover a,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post-meta-cats-tags a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span.bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .post-meta-cats-tags .bdp-no-links,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap .metadatabox span:hover.bdp-no-links {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.clicky .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?>.bdp-author-avatar .avtar-img:before,
	<?php echo esc_attr( $layout_id ); ?>.bdp-author-avatar .avtar-img:after,
	<?php echo esc_attr( $layout_id ); ?>.bdp-author-avatar .author_content:before,
	<?php echo esc_attr( $layout_id ); ?>.bdp-author-avatar .author_content:after,
	<?php echo esc_attr( $layout_id ); ?> .clicky.even_class div.bdp-post-image:before,
	<?php echo esc_attr( $layout_id ); ?> .clicky.even_class div.bdp-post-image:after,
	<?php echo esc_attr( $layout_id ); ?> .clicky div.bdp-post-image:before,
	<?php echo esc_attr( $layout_id ); ?> .clicky div.bdp-post-image:after,
	<?php echo esc_attr( $layout_id ); ?> .clicky.even_class .clicky-wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .clicky.even_class .clicky-wrap:after,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap:before,
	<?php echo esc_attr( $layout_id ); ?> .clicky .clicky-wrap:after {
		border-color: <?php echo esc_attr( $color ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?>.clicky .bdp-post-image  a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.clicky .bdp-post-image a.more-tag:hover {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_blog_template.clicky .label_featured_post {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Miracle Blog Layout Template CSS. */
if ( 'miracle' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.miracle_blog {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .bdp-author-avatar {
		border-color:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .bdp-post-format {
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta-cats-tags a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta-cats-tags a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-title h2 a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}   if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-title h2 a:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta-cats-tags > div,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta > span,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta > span a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $color ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .blog_template.miracle_blog .post-meta > span a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-meta-cats-tags > div.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-meta > span.bdp_no_links,
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-meta-cats-tags > div .link-lable {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-meta > span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .post-meta > span a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog a.more-tag,
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .label_featured_post span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .label_featured_post span {
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog a.more-tag {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorehoverbackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			border: none;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog a.more-tag:hover {
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .miracle_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .miracle_blog .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Foodbox Blog Layout Template CSS. */
if ( 'foodbox' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .foodbox-blog-wrapp {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php if ( isset( $backgroundbg_image_src ) && '' != $backgroundbg_image_src ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.foodbox_cover {
			background-image: url("<?php echo esc_attr( $backgroundbg_image_src ); ?>");
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .wl_pagination_box .paging-navigation ul.page-numbers li span.dots{
		color: <?php echo esc_attr( $pagination_background_color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-title h2 a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post_content .foodbox-quote {
		color:<?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox-blog-wrapp:before,
	<?php echo esc_attr( $layout_id ); ?> .foodbox-blog-wrapp:after,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .label_featured_post {
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-title h2 a:hover,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-title h2 a:focus {
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta .bdp_has_links,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .bdp_blog_template.foodbox_blog .post-meta span a,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-author {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta a:focus,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta .bdp_has_links:hover,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .bdp_blog_template.foodbox_blog .post-meta span a:hover,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-author:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.foodbox_blog .post_content a.more-tag {
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .foodbox_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>


	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .foodbox_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post_content p,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .foodbox-year h3, 
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .category-link,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .tags,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .link-lable  {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .social-component{margin:10px 0;width:auto;float:right}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .category-link,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .tags,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta .post-date,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-comment a,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-author,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .bdp-wrapper-like .bdp-count,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .category-link,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .tags,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .foodbox_blog .post-meta-cats-tags a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php
}
/* Neaty BLock Blog Layout Template CSS. */
if ( 'neaty_block' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?>.neaty_block_cover {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-title {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-title h2,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-title h2 a {
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
			<?php
		}   if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .bdp-neaty-block-metadata ul {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-title h2 a:hover,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-title h2 a:focus {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .bdp_blog_template.neaty_block_blog .post-meta span a,
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper .bdp_blog_template.neaty_block_blog .post-meta span {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .bdp-comments-box ul li,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .bdp-comments-box ul li a,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp-neaty-block-metadata a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .bdp-neaty-block-metadata ul li {
		border-color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .bdp-comments-box ul li a:hover,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog a.more-tag {
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .social-component{
		margin: 10px 0;width: auto;
		<?php
		if ( isset( $readmorebutton_on ) && '2' === $readmorebutton_on ) {
			?>
			float: right;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta .post-date,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-comment a,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-author,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta-cats-tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .post-meta-cats-tags a,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .tags a ,
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .tags {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .read_more_div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .neaty_block_blog .read_more_div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Wise Block Blog Layout Template CSS. */
if ( 'wise_block' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.wise_block_cover:before {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-title,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-title a {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-title h2 a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-title h2 a:focus {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-category a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .tags a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .bdp-has-links a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-date a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-category a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-meta a:focus,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .bdp-has-links a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-author a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-date a:hover, 
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .category-link a:focus,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .tags a:focus,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .bdp-has-links a:focus,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-author a:focus,
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .post-date a:focus {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.wise_block_blog .post_content .read-more .more-tag {
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.wise_block_blog .post_content .read-more .more-tag:hover {
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;<?php } ?>
		color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?> .wise_block_blog .social-component{margin:10px 0;width:auto;float:left}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .quote-icon i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-category a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-date a{
		font-family: <?php echo esc_attr( $content_font_family ); ?>;
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .metadatabox a {
		font-family: <?php echo esc_attr( $content_font_family ); ?>;
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-meta .post-date,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-comment a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-author,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .bdp-wrapper-like .bdp-count,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-meta-cats-tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .post-meta-cats-tags a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .comments-link {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog  a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.wise_block_blog  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}

		<?php } ?>

	<?php
}
/* Soft Block Blog Layout Template CSS. */
if ( 'soft_block' === $bdp_theme ) {
	?>
	<?php if ( ! empty( $background1 ) && empty( $background2 ) && empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background1 ); ?>;
		}
	<?php } ?>
	<?php if ( empty( $background1 ) && ! empty( $background2 ) && empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
	<?php } ?>
	<?php if ( empty( $background1 ) && empty( $background2 ) && ! empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background3 ); ?>;
		}
	<?php } ?>
	<?php if ( empty( $background1 ) && empty( $background2 ) && empty( $background3 ) && ! empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background4 ); ?>;
		}
	<?php } ?>
	<?php if ( empty( $background1 ) && empty( $background2 ) && empty( $background3 ) && empty( $background4 ) && ! empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background5 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background5 ); ?>;
		}
	<?php } ?>
	<?php if ( empty( $background1 ) && empty( $background2 ) && empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && ! empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper{
				background: <?php echo esc_attr( $background6 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper .meeting-day .day-inner{
			color: <?php echo esc_attr( $background6 ); ?>;
		}
	<?php } ?>
	<?php if ( ! empty( $background1 ) && ! empty( $background2 ) && empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(2n+1){
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(2n+2){
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(2n+1) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(2n+2) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
	<?php } ?>
	<?php if ( ! empty( $background1 ) && ! empty( $background2 ) && ! empty( $background3 ) && empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+1){
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+2){
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+3){
				background: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+1) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+2) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(3n+3) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background3 ); ?>;
		}
	<?php } ?>
	<?php if ( ! empty( $background1 ) && ! empty( $background2 ) && ! empty( $background3 ) && ! empty( $background4 ) && empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+1){
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+2){
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+3){
				background: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+4){
				background: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+1) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+2) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+3) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(4n+4) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background4 ); ?>;
		}
	<?php } ?>
	<?php if ( ! empty( $background1 ) && ! empty( $background2 ) && ! empty( $background3 ) && ! empty( $background4 ) && ! empty( $background5 ) && empty( $background6 ) ) { ?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+1){
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+2){
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+3){
				background: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+4){
				background: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+5){
				background: <?php echo esc_attr( $background5 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+1) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+2) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+3) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+4) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(5n+5) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background5 ); ?>;
		}
	<?php } ?>
	<?php
	if ( ! empty( $background1 ) && ! empty( $background2 ) && ! empty( $background3 ) && ! empty( $background4 ) && ! empty( $background5 ) && ! empty( $background6 ) ) {
		?>
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+1){
				background: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+2){
				background: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+3){
				background: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+4){
				background: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+5){
				background: <?php echo esc_attr( $background5 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+6){
				background: <?php echo esc_attr( $background6 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+1) .meeting-day .day-inner{
				color: <?php echo esc_attr( $background1 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+2) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background2 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+3) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background3 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+4) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background4 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+5) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background5 ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.bdp_wrapper.soft_block_cover .soft-block-post-wrapper:nth-of-type(6n+6) .meeting-day .day-inner{
			color: <?php echo esc_attr( $background6 ); ?>;
		}
	<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .meeting-day .day-inner,
	<?php echo esc_attr( $layout_id ); ?> .soft-block-post-wrapper .soft_block_wrapper:before {
		background: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .tags,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .tags a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .category-link,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .category-link a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .tags a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .bdp-has-links a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .date-meta a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .footer_meta a,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .footer_meta .seperater,
	<?php echo esc_attr( $layout_id ); ?>.soft_block_cover .post-meta .comments-link{
		color: <?php echo esc_attr( $color ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-meta i,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .bdp-wrapper-like a i {
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-title{
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .category-link a:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .bdp-has-links a:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-author a:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .date-meta a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .read-more .more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .post-title:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .read-more a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .read-more a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper  a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .read-more a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .soft_block_wrapper .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}
/* Schedule Blog Layout Template CSS. */
if ( 'schedule' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-meta a,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-category a,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .bdp-has-links a,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-author a,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .date-meta a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-title {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.schedule_cover .schedule-content-wrap {
		background:<?php echo esc_attr( $background ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.schedule_cover .schedule-content-wrap:after{
		border-color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php
	if ( isset( $bdp_settings['winter_category_color'] ) ) {
		?>
		<?php echo esc_attr( $layout_id ); ?>.schedule_cover .tags a,
		<?php echo esc_attr( $layout_id ); ?>.schedule_cover .schedule-time a {
			background: <?php echo esc_attr( $bdp_settings['winter_category_color'] ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.schedule_cover .tags a:after {
			border-color: transparent <?php echo esc_attr( $bdp_settings['winter_category_color'] ); ?> transparent transparent;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.schedule a.more-tag:focus,
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.schedule a.more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.schedule a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template.schedule a.more-tag{
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	@media screen and (max-width: 480px) {
		<?php echo esc_attr( $layout_id ); ?>.schedule_cover .bdp-post-meta {
		box-shadow: 0px 0px 5px <?php echo esc_attr( $templatecolor ); ?>;
		}
	}
	<?php echo esc_attr( $layout_id ); ?>.schedule_cover .meta-archive a,
	<?php echo esc_attr( $layout_id ); ?>.schedule_cover .schedule-circle:after {
		background: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-meta a:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-category a:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .bdp-has-links a:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-author a:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .date-meta a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .read-more .more-tag:hover,
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .post-title:hover {
		color:<?php echo esc_attr( $titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .schedule_wrapper .tags a {
		color: #fff;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-comment span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-comment span a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-comment span i,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .author-name a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .author-name {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-archive a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-archive,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .author-name a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-comment a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .post-meta,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .author-name,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .meta-comment,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .bdp-wrapper-like .bdp-count,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .schedule-time,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .schedule-time a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .post-category,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .post-category a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .tags .link-lable,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .tags a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .postcontent {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .read-more-div a.more-tag{
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.schedule .read-more-div a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php
}

/* Tabbed */
if ( 'tabbed' === $bdp_theme ) {
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h3.post-title a,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template h3.post-title {
		color: <?php echo esc_attr( $extra_titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_extratitlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_weight ) && $template_extra_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_extra_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_line_height ) && $template_extra_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_extra_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_italic ) && '1' == $template_extra_title_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_text_transform ) && $template_extra_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_extra_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_text_decoration ) && $template_extra_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_extra_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_extra_title_font_letter_spacing ) && $template_extra_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_extra_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .tabs li a {
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post-title a:hover {
		color: <?php echo esc_attr( $extra_titlehovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover ul.tabs {
		border-top: 5px solid <?php echo esc_attr( $templatecolor ); ?>;;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .prev-tab,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .next-tab,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover ul.tabs li.ui-state-active a {
		color: <?php echo esc_attr( $templatecolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content .bdp-separator,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .tags a,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .tags,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content i,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content span,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content a {
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .tags a:hover,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content a:hover {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-post-content{
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .tags a,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-post-content,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content span,
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .bdp-tabbed-meta-content a {
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		<?php
		if ( isset( $content_font_family ) && '' != $content_font_family ) { 
			?>
			font-family: <?php echo esc_attr( $content_font_family ); ?>; <?php } ?>
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .read-more a.more-tag{
	<?php
	if ( isset( $bdp_readmore_button_borderleft ) ) {
		?>
		border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderright ) ) {
		?>
		border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_bordertop ) ) {
		?>
		border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderbottom ) ) {
		?>
		border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
	padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
	padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
	padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
	padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
	<?php
	if ( isset( $readmorebuttonborderradius ) ) {
		?>
		border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .read-more a.more-tag:hover{
		<?php
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.tabbed_cover .more-tag:hover {
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.tabbed_cover  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php
}

/* Ticker */
if ( 'ticker' === $bdp_theme ) { ?>
	<?php echo esc_attr( $layout_id ); ?> .blog-tickers a {
		color: <?php echo esc_attr( $template_titlecolor ); ?> !important;
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ); ?>px;
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ); ?>;
		<?php if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { ?>
			font-style: <?php echo 'italic'; ?>;<?php }  ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog-tickers ul li {
		color: <?php echo esc_attr( $template_titlecolor ); ?> !important;
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ). 'px'; ?>;
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ). 'px'; ?>;
		<?php if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { ?>
			font-style: <?php echo 'italic'; ?>;<?php }  ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog-ticker-wrapper {
    	border-color: <?php echo esc_attr( $template_color ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .blog-ticker-wrapper .ticker-title {
    	background-color: <?php echo esc_attr( $template_color ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .blog-ticker-wrapper .ticker-title > span {
    	border-color: transparent transparent transparent <?php echo esc_attr( $template_color ); ?>;
	}

	<?php echo esc_attr( $layout_id ); ?> .blog-ticker-wrapper .blog-tickers ul li a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog-ticker-wrapper .blog-tickers ul li:hover {
    	color: <?php echo esc_attr( $template_titlehovercolor ); ?> !important;
	}
	<?php 
}

/* Blog Grid Box */
if ( 'blog_grid_box' === $bdp_theme ) { ?>
	<?php echo esc_attr( $layout_id ); ?>.bdp_post_list.blog_grid_box,
	<?php echo esc_attr( $layout_id ); ?>.blog_template.blog_grid_box_cover {
		background: <?php echo esc_attr( $template_color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .bdp-post-categories a,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .author a,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .date-meta a,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .comment a {
		color: <?php echo esc_attr( $template_ftcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .bdp-post-categories a:hover {
		color: <?php echo esc_attr( $template_fthovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .bdp_post_title a,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .bdp_post_title {
		color: <?php echo esc_attr( $template_titlecolor ); ?>;
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ); ?>px;
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { ?>
			font-style: <?php echo 'italic'; ?>;
		<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .bdp_post_title a:hover {
		color: <?php echo esc_attr( $template_titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .post-body-div-right:nth-child(1) .post_content,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .category-link,
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .metadatabox {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-family: <?php echo esc_attr( $content_font_family ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) .'px'; ?>;
		font-weight: <?php echo esc_attr( $content_font_weight ); ?>;
		line-height: <?php echo esc_attr( $content_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .post-body-div-right:nth-child(1) .post_content p .bp-first-letter {
		font-family: <?php echo esc_attr( $firstletter_font_family ); ?>;
		font-size: <?php echo esc_attr( $firstletter_fontsize ) .'px'; ?>;
		color: <?php echo esc_attr( $firstletter_contentcolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .read-more-div a {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background-color: <?php echo esc_attr( $readmorebackcolor); ?>;
		border-radius: <?php echo esc_attr( $readmore_button_border_radius ) .'px'; ?>;
		border-left: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> ;
		border-right: <?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> ;
		border-top: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> ;
		border-bottom: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?>;
		font-family: <?php echo esc_attr( $readmore_font_family); ?>;
		font-size: <?php echo esc_attr( $readmore_fontsize) .'px'; ?>;
		font-weight: <?php echo esc_attr( $readmore_font_weight); ?>;
		line-height: <?php echo esc_attr( $readmore_font_line_height); ?>;
		text-transform: <?php echo esc_attr( $readmore_font_text_transform); ?>;
		text-decoration: <?php echo esc_attr( $readmore_font_text_decoration); ?>;
		letter-spacing: <?php echo esc_attr( $readmore_font_letter_spacing); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .read-more-div a:hover {
		color: <?php echo esc_attr( $readmorehovercolor ); ?>;
		background-color: <?php echo esc_attr( $readmorehoverbackcolor); ?>;
		border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) .'px'; ?>;
		border-left: <?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> ;
		border-right: <?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> ;
		border-top: <?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> ;
		border-bottom: <?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .post_content {
		color: <?php echo esc_attr( $contentcolor );?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .bdp_post_content .post_content .bp-first-letter {
		font-family: <?php echo esc_attr( $firstletter_font_family ); ?>;
		font-size: <?php echo esc_attr( $firstletter_fontsize ) .'px'; ?>;
		color: <?php echo esc_attr( $firstletter_contentcolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .blog_grid_box-post .read-more  a.more-tag {
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			border-color: <?php echo esc_attr( $readmorebackcolor ); ?>;
			background:<?php echo esc_attr( $readmorebackcolor ); ?>;
		<?php } ?>
		color:<?php echo esc_attr( $readmorecolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .blog_grid_box-post .read-more a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
		}
	<?php } ?>

	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .blog_grid_box-post  a.more-tag {
			color:<?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.blog_grid_box .blog_grid_box-post  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

	<?php 
}

/* Blog Carousel Layout Template CSS. */
if ( 'blog_carousel' === $bdp_theme ) {

		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .blog_header h2 {
			background: <?php echo esc_attr( $titlebackcolor ); ?>;
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .blog_header h2 a{
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .blog_header h2 a:hover{
			color:<?php echo esc_attr( $titlehovercolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .mauthor a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .mauthor a span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-date a,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-comment a{
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .mauthor a:hover span {
			color: <?php echo esc_attr( $linkhovercolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .label_featured_post span,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.blog_carousel .post_content .post_content-inner,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.blog_carousel .post_content .post_content-inner p{
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $contentcolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .mauthor,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-date,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-comment,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .tags,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .tags i,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.blog_carousel .label_featured_post,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .category-link{
			<?php
			if ( isset( $content_font_weight ) && $content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_line_height ) && $content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			color: <?php echo esc_attr( $contentcolor ); ?>;
			font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .mauthor a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-date a:hover,
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .metadatabox .post-comment a:hover{
			color: <?php echo esc_attr( $linkhovercolor ); ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_carousel .read-more a.more-tag {
			<?php
			if ( isset( $bdp_readmore_button_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
			padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
			padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
			padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
			<?php
			if ( isset( $readmorebuttonborderradius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_carousel .read-more a.more-tag:hover{
			<?php
			if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
			<?php
			if ( isset( $readmore_button_hover_border_radius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .post_content a.more-tag{
			color:<?php echo esc_attr( $readmorecolor ); ?>;
			font-weight: bold;
			<?php
			if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
				?>
				background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
			<?php
			if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
				?>
				background: none;<?php } ?>
		}
		<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .post_content a.more-tag:hover{
				background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
				color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
			}
		<?php } ?>
		<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .post_content a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

		<?php echo esc_attr( $layout_id ); ?> .blog_template.blog_carousel li.blog_template.bdp_blog_template {
			background: <?php echo esc_attr( $template_bgcolor); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.blog_carousel .blog_header {
			background: <?php echo esc_attr( $template_color); ?>;
		}
		<?php
	
}
/* Leest Layout Template CSS. Start */
/* Leest Layout Template CSS. */
if ( 'leest' === $bdp_theme ) { ?>

	<?php echo esc_attr( $layout_id ); ?> .card-box-text { background: <?php echo esc_attr( $template_color); ?>;}

	/** Font Awesome apply */
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template .post_hentry.fas {font-family: 'Font Awesome 5 Free'}
	/** Apply link color */
	<?php echo esc_attr( $layout_id ); ?> .message_catagroys  li a, <?php echo esc_attr( $layout_id ); ?> .message_catagroys div.leest-tags a{ color:<?php echo esc_attr( $color ); ?>; }

	<?php echo esc_attr( $layout_id ); ?> .message_catagroys  li a:hover, <?php echo esc_attr( $layout_id ); ?> .message_catagroys div.leest-tags a:hover{ color:<?php echo esc_attr( $linkhovercolor ); ?> !important; }

	<?php echo esc_attr( $layout_id ); ?> .card-box-text h2.leest-title a {
		margin: 0 !important;
		padding: 0 !important;;
	}

	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price .woocommerce-Price-amount span,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price .woocommerce-Price-amount,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price del .amount,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price ins,
	<?php echo esc_attr( $layout_id ); ?> .bdp_woocommerce_price_wrap .price {
		color: #fff ; font-weight: 700 ;
	}

	<?php echo esc_attr( $layout_id ); ?> .leest .bdp_woocommerce_meta_box .bdp_woocommerce_star_wrap .blog-start-rating-text {
		color: <?php echo esc_attr( $bdp_pricetextcolor ); ?> !important;
	}

	<?php echo esc_attr( $layout_id ); ?> .card-box-text h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {	?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
		}

		.card-box-text h2.card-box-title a{
			display: inline-block;
		}

		<?php echo esc_attr( $layout_id ); ?> .card-box-text h2.card-box-title a:hover{
			color:<?php echo esc_attr( $titlehovercolor ); ?> !important;
		}

		<?php echo esc_attr( $layout_id ); ?> .card-box-text h2 {
			background: <?php echo esc_attr( $titlebackcolor ); ?>;
			color:<?php echo esc_attr( $titlecolor ); ?>;
			font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
			float: left;
			width: 100%;
			<?php
			if ( isset( $template_titlefontface ) && $template_titlefontface ) {
				?>
				font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		
		<?php echo esc_attr( $layout_id ); ?>.bdp_archive_product_template .card-box-text h2 {
			margin-left: <?php echo esc_attr($product_post_title_marginleft).'px'; ?>;
			margin-right: <?php echo esc_attr($product_post_title_marginright).'px'; ?>;
			margin-top: <?php echo esc_attr($product_post_title_margintop).'px'; ?>;
			margin-bottom: <?php echo esc_attr($product_post_title_marginbottom).'px'; ?>;
			padding-left: <?php echo esc_attr($product_post_title_paddingleft).'px'; ?>;
			padding-right: <?php echo esc_attr($product_post_title_paddingright).'px'; ?>;
			padding-top: <?php echo esc_attr($product_post_title_paddingtop).'px'; ?>;
			padding-bottom: <?php echo esc_attr($product_post_title_paddingbottom).'px'; ?>;
		}

		<?php echo esc_attr( $layout_id ); ?> .card-box-text h2 a{
				text-shadow: <?php echo esc_attr($bdp_title_top_box_shadow) .' '. esc_attr($bdp_title_right_box_shadow) .' '. esc_attr($bdp_title_bottom_box_shadow).' '. esc_attr($bdp_title_box_shadow_color); ?>;
				<?php
				if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		}
		
		<?php echo esc_attr( $layout_id ); ?> .card-box-discrition,
		<?php echo esc_attr( $layout_id ); ?> .card-box-discrition > p{
				color: <?php echo esc_attr( $contentcolor ); ?>;
				font-family: <?php echo esc_attr( $content_font_family ); ?>;
				font-size: <?php echo esc_attr( $content_fontsize ) .'px'; ?>;
				font-weight: <?php echo esc_attr( $content_font_weight ); ?>;
				line-height: <?php echo esc_attr( $content_font_line_height ); ?>;
				text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;
				letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ); ?>;
				<?php
			if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
		}
	<?php
	if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
		$first_letter_line_height = $firstletter_fontsize * 75 / 100;
		?>
		<?php echo esc_attr( $layout_id ); ?> .card-box-discrition > p:first-letter{
			<?php
			if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
				?>
				font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
			font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
			color: <?php echo esc_attr( $firstletter_contentcolor ); ?>;
			line-height: <?php echo esc_attr( $first_letter_line_height ) . 'px'; ?>;
			margin-right:5px;display:inline-block;
			<?php
			if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		}
		<?php } ?>
		/*Post Content CSS*/
		<?php echo esc_attr( $layout_id ); ?> .card-box-discrition {
			margin-left: <?php echo esc_attr($post_content_marginleft) . 'px'; ?>;
			margin-right: <?php echo esc_attr($post_content_marginright) . 'px'; ?>;
			margin-top: <?php echo esc_attr($post_content_margintop) . 'px'; ?>;
			margin-bottom: <?php echo esc_attr($post_content_marginbottom) . 'px'; ?>;
			padding-left: <?php echo esc_attr($post_content_paddingleft) . 'px'; ?>;
			padding-right: <?php echo esc_attr($post_content_paddingright) . 'px'; ?>;
			padding-top: <?php echo esc_attr($post_content_paddingtop) . 'px'; ?>;
			padding-bottom: <?php echo esc_attr($post_content_paddingbottom) . 'px'; ?>;
		}
		<?php echo esc_attr( $layout_id ); ?> .card-box-discrition p {
			text-shadow: <?php echo esc_attr($bdp_content_top_box_shadow) .' '. esc_attr($bdp_content_right_box_shadow) .' '. esc_attr($bdp_content_bottom_box_shadow).' '. esc_attr($bdp_content_box_shadow_color); ?>;
		}

		<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag {
			<?php
			if ( isset( $bdp_readmore_button_borderleft ) ) {
				?>
				border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderright ) ) {
				?>
				border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_bordertop ) ) {
				?>
				border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $bdp_readmore_button_borderbottom ) ) {
				?>
				border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> ;<?php } ?>
			<?php
			if ( isset( $readmorebuttonborderradius ) ) {
				?>
				border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> ;<?php } ?>
			}	

			<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag:hover {
				<?php
				if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
					?>
					border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
				<?php
				if ( isset( $bdp_readmore_button_hover_borderright ) ) {
					?>
					border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
				<?php
				if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
					?>
					border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
				<?php
				if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
					?>
					border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
				<?php
				if ( isset( $readmore_button_hover_border_radius ) ) {
					?>
					border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
			}

			<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag {
				<?php
				if ( isset( $readmore_button_margintop ) && '' != $readmore_button_margintop ) { 
					?>
					margin-top:<?php echo esc_attr( $readmore_button_margintop ) . 'px'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_button_marginright ) && '' != $readmore_button_marginright ) { 
					?>
					margin-right:<?php echo esc_attr( $readmore_button_marginright ) . 'px'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_button_marginbottom ) && '' != $readmore_button_marginbottom ) { 
					?>
					margin-bottom:<?php echo esc_attr( $readmore_button_marginbottom ) . 'px'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_button_marginleft ) && '' != $readmore_button_marginleft ) { 
					?>
					margin-left:<?php echo esc_attr( $readmore_button_marginleft ) . 'px'; ?>;<?php } ?>
				display: inline-block;
				text-align: center;
				<?php
				if ( isset( $readmore_font_family ) ) {
					?>
					font-family: <?php echo esc_attr( $readmore_font_family ); ?>; <?php } ?>
			}
			<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag {
				font-size: <?php echo esc_attr( $readmore_fontsize ) . 'px'; ?>;
				color:<?php echo esc_attr( $readmorecolor ); ?>;
				background:<?php echo esc_attr( $readmorebackcolor ); ?>;
				<?php
				if ( isset( $readmore_font_weight ) && $readmore_font_weight ) {
					?>
					font-weight: <?php echo esc_attr( $readmore_font_weight ); ?>;<?php } ?>
				<?php
				if ( isset( $readmore_font_line_height ) && $readmore_font_line_height ) {
					?>
					line-height: <?php echo esc_attr( $readmore_font_line_height ); ?>;<?php } ?>
				<?php
				if ( isset( $readmore_font_italic ) && '1' == $readmore_font_italic ) {
					?>
					font-style: <?php echo 'italic'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_font_text_transform ) && $readmore_font_text_transform ) {
					?>
					text-transform: <?php echo esc_attr( $readmore_font_text_transform ); ?>;<?php } ?>
				<?php
				if ( isset( $readmore_font_text_decoration ) && $readmore_font_text_decoration ) {
					?>
					text-decoration: <?php echo esc_attr( $readmore_font_text_decoration ); ?>;<?php } ?>
				<?php
				if ( isset( $readmore_font_letter_spacing ) && $readmore_font_letter_spacing ) {
					?>
					letter-spacing: <?php echo esc_attr( $readmore_font_letter_spacing ) . 'px'; ?>;<?php } ?>
			} 
			<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag:hover {
				color:<?php echo esc_attr( $readmorehovercolor ); ?>;
				<?php
					if ( isset( $bdp_settings['template_readmore_hover_backcolor'] ) ) {
					?>
						background:<?php echo esc_attr( $bdp_settings['template_readmore_hover_backcolor'] ); ?>;
				<?php } ?>
				
			}
			<?php echo esc_attr( $layout_id ); ?> .card-box-discrition a.more-tag {
				color:<?php echo esc_attr( $readmorecolor ); ?>;
			}
			<?php echo esc_attr( $layout_id ); ?> .card-box-discrition a.more-tag:hover {
				color:<?php echo esc_attr( $readmorehovercolor ); ?>;
			}
			<?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag {
				<?php
				if ( isset( $readmore_button_paddingtop ) && '' != $readmore_button_paddingtop ) { 
					?>
					padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>; <?php } ?>
				<?php
				if ( isset( $readmore_button_paddingbottom ) && '' != $readmore_button_paddingbottom ) { 
					?>
					padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_button_paddingright ) && '' != $readmore_button_paddingright ) { 
					?>
					padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;<?php } ?>
				<?php
				if ( isset( $readmore_button_paddingleft ) && '' != $readmore_button_paddingleft ) { 
					?>
					padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;<?php } ?>
			}


			/* <?php echo esc_attr( $layout_id ); ?> .card-box-link a.more-tag {
				text-align: <?php echo esc_attr( $readmorebuttonalignment ); ?>;
				display: inline-block;width: 100%;
			} */
			<?php echo esc_attr( $layout_id ); ?> .card-box-link { text-align: <?php echo esc_attr( $readmorebuttonalignment ); ?>; width: 100%;}
	<?php
}

/* leest Template CSS. End */
/* 3d Carousel Layout Template CSS. */

if ( 'threed_carousel' === $bdp_theme ) {

	 echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .blog_header h2 {
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .blog_header h2 a{
		color:<?php echo esc_attr( $titlecolor ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
		<?php
		if ( isset( $template_titlefontface ) && $template_titlefontface ) {
			?>
			font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .blog_header h2 a:hover{
		color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .mauthor a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .mauthor a span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-date a,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-comment a{
		color: <?php echo esc_attr( $color ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .mauthor a:hover span {
		color: <?php echo esc_attr( $linkhovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .label_featured_post span,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.threed_carousel .post_content .post_content-inner,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.threed_carousel .post_content .post_content-inner p{
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $contentcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .mauthor,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-date,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-comment,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .tags,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.threed_carousel .label_featured_post,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .category-link{
		<?php
		if ( isset( $content_font_weight ) && $content_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_line_height ) && $content_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .mauthor a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-date a:hover,
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .metadatabox .post-comment a:hover{
		color: <?php echo esc_attr( $linkhovercolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .threed_carousel .read-more a.more-tag {
		<?php
		if ( isset( $bdp_readmore_button_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
		padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
		padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
		padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
		padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
		<?php
		if ( isset( $readmorebuttonborderradius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
	}
	

	<?php echo esc_attr( $layout_id ); ?> .threed_carousel .read-more a.more-tag:hover{
		<?php
		
		if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
			?>
			border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderright ) ) {
			?>
			border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
			?>
			border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
			?>
			border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
		<?php
		if ( isset( $readmore_button_hover_border_radius ) ) {
			?>
			border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .post_content a.more-tag{
		color:<?php echo esc_attr( $readmorecolor ); ?>;
		font-weight: bold;
		<?php
		if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
			?>
			background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
		<?php
		if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
			?>
			background: none;<?php } ?>
	}
	<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.threed_carousel .read-more a.more-tag:hover{
			background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
	<?php } ?>
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.threed_carousel  a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?> .blog_template.threed_carousel li.blog_template.bdp_blog_template {
		background: <?php echo esc_attr( $template_bgcolor); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.threed_carousel .blog_header {
		background: <?php echo esc_attr( $template_color); ?>;
	}
	<?php

}
?>
<?php
/* Flip Book 3d Layout Template CSS. */

if ( 'flip_book_3d' === $bdp_theme ) {

 echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .blog_header h2 {
	background: <?php echo esc_attr( $titlebackcolor ); ?>;
	color:<?php echo esc_attr( $titlecolor ); ?>;
	font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	<?php
	if ( isset( $template_titlefontface ) && $template_titlefontface ) {
		?>
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_weight ) && $template_title_font_weight ) {
		?>
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_line_height ) && $template_title_font_line_height ) {
		?>
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { 
		?>
		font-style: <?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_transform ) && $template_title_font_text_transform ) {
		?>
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_text_decoration ) && $template_title_font_text_decoration ) {
		?>
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $template_title_font_letter_spacing ) && $template_title_font_letter_spacing ) {
		?>
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .blog_header h2 a{
	color:<?php echo esc_attr( $titlecolor ); ?>;
	font-size: <?php echo esc_attr( $template_titlefontsize ) . 'px'; ?>;
	<?php
	if ( isset( $template_titlefontface ) && $template_titlefontface ) {
		?>
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>; <?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .blog_header h2 a:hover{
	color:<?php echo esc_attr( $titlehovercolor ); ?>!important;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .mauthor a,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-date a,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-comment a{
	color: <?php echo esc_attr( $color ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .mauthor a:hover span {
	color: <?php echo esc_attr( $linkhovercolor ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .label_featured_post span,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox,
<?php echo esc_attr( $layout_id ); ?> .blog_template.flip_book_3d .post_content .post_content-inner,
<?php echo esc_attr( $layout_id ); ?> .blog_template.flip_book_3d .post_content .post_content-inner p{
	font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
	color: <?php echo esc_attr( $contentcolor ); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .mauthor,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-date,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-comment,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .tags,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .tags,
<?php echo esc_attr( $layout_id ); ?> .blog_template.flip_book_3d .label_featured_post,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .category-link{
	<?php
	if ( isset( $content_font_weight ) && $content_font_weight ) {
		?>
		font-weight: <?php echo esc_attr( $content_font_weight ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_line_height ) && $content_font_line_height ) {
		?>
		line-height: <?php echo esc_attr( $content_font_line_height ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_italic ) && '1' == $content_font_italic ) {
		?>
		font-style: <?php echo 'italic'; ?>;<?php } ?>
	<?php
	if ( isset( $content_font_text_transform ) && $content_font_text_transform ) {
		?>
		text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
		?>
		text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
	<?php
	if ( isset( $content_font_letter_spacing ) && $content_font_letter_spacing ) {
		?>
		letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
	color: <?php echo esc_attr( $contentcolor ); ?>;
	font-size: <?php echo esc_attr( $content_fontsize ) . 'px'; ?>;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .mauthor a:hover,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-date a:hover,
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .metadatabox .post-comment a:hover{
	color: <?php echo esc_attr( $linkhovercolor ); ?>
}
<?php echo esc_attr( $layout_id ); ?> .flip_book_3d .read-more a.more-tag {
	<?php
	if ( isset( $bdp_readmore_button_borderleft ) ) {
		?>
		border-left:<?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderright ) ) {
		?>
		border-right:<?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_bordertop ) ) {
		?>
		border-top:<?php echo esc_attr( $bdp_readmore_button_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_borderbottom ) ) {
		?>
		border-bottom:<?php echo esc_attr( $bdp_readmore_button_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?> !important;<?php } ?>
	padding-top: <?php echo esc_attr( $readmore_button_paddingtop ) . 'px'; ?>;
	padding-bottom: <?php echo esc_attr( $readmore_button_paddingbottom ) . 'px'; ?>;
	padding-right: <?php echo esc_attr( $readmore_button_paddingright ) . 'px'; ?>;
	padding-left: <?php echo esc_attr( $readmore_button_paddingleft ) . 'px'; ?>;
	<?php
	if ( isset( $readmorebuttonborderradius ) ) {
		?>
		border-radius: <?php echo esc_attr( $readmorebuttonborderradius ) . 'px'; ?> !important;<?php } ?>
}


<?php echo esc_attr( $layout_id ); ?> .flip_book_3d .read-more a.more-tag:hover{
	<?php
	
	if ( isset( $bdp_readmore_button_hover_borderleft ) ) {
		?>
		border-left:<?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_borderright ) ) {
		?>
		border-right:<?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_bordertop ) ) {
		?>
		border-top:<?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $bdp_readmore_button_hover_borderbottom ) ) {
		?>
		border-bottom:<?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?> !important;<?php } ?>
	<?php
	if ( isset( $readmore_button_hover_border_radius ) ) {
		?>
		border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) . 'px'; ?> !important;<?php } ?>
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .post_content a.more-tag{
	color:<?php echo esc_attr( $readmorecolor ); ?>;
	font-weight: bold;
	<?php
	if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
		?>
		background: <?php echo esc_attr( $readmorebackcolor ); ?>;<?php } ?>
	<?php
	if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		background: none;<?php } ?>
}
<?php if ( isset( $readmorebutton_on ) && 2 == $readmorebutton_on ) { 
	?>
	<?php echo esc_attr( $layout_id ); ?>.flip_book_3d .read-more a.more-tag:hover{
		color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		background: <?php echo esc_attr( $readmorehoverbackcolor ); ?> !important;
	}
<?php } ?>
<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.flip_book_3d .post_content-inner a.more-tag:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>

<?php echo esc_attr( $layout_id ); ?> .blog_template.flip_book_3d li.blog_template.bdp_blog_template {
	background: <?php echo esc_attr( $template_bgcolor); ?>;
}
<?php echo esc_attr( $layout_id ); ?> .blog_template.bdp_blog_template.flip_book_3d .blog_header {
	background: <?php echo esc_attr( $template_color); ?>;
}

<?php

}
/* Banner Template */
if ( 'banner' === $bdp_theme ) { ?>
	<?php echo esc_attr( $layout_id ); ?>.bdp_post_list.banner,
	<?php echo esc_attr( $layout_id ); ?>.blog_template.banner_cover {
		background: <?php echo esc_attr( $template_color ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .bdp-post-categories a,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .author a,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .date-meta a,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .comment a,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .date-meta i {
		color: <?php echo esc_attr( $template_ftcolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .bdp-post-categories a:hover {
		color: <?php echo esc_attr( $template_fthovercolor ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .bdp_post_title a,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .bdp_post_title {
		color: <?php echo esc_attr( $template_titlecolor ); ?>;
		font-family: <?php echo esc_attr( $template_titlefontface ); ?>;
		font-size: <?php echo esc_attr( $template_titlefontsize ); ?>px;
		font-weight: <?php echo esc_attr( $template_title_font_weight ); ?>;
		line-height: <?php echo esc_attr( $template_title_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $template_title_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $template_title_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $template_title_font_letter_spacing ); ?>;
		background: <?php echo esc_attr( $titlebackcolor ); ?>;
		<?php if ( isset( $template_title_font_italic ) && '1' == $template_title_font_italic ) { ?>
			font-style: <?php echo 'italic'; ?>;
		<?php } ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .bdp_post_title a:hover {
		color: <?php echo esc_attr( $template_titlehovercolor ); ?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .post-body-div-right:nth-child(1) .post_content,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .category-link,
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .metadatabox {
		color: <?php echo esc_attr( $contentcolor ); ?>;
		font-family: <?php echo esc_attr( $content_font_family ); ?>;
		font-size: <?php echo esc_attr( $content_fontsize ) .'px'; ?>;
		font-weight: <?php echo esc_attr( $content_font_weight ); ?>;
		line-height: <?php echo esc_attr( $content_font_line_height ); ?>;
		text-transform: <?php echo esc_attr( $content_font_text_transform ); ?>;
		text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;
		letter-spacing: <?php echo esc_attr( $content_font_letter_spacing ); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .post-body-div-right:nth-child(1) .post_content p .bp-first-letter {
		font-family: <?php echo esc_attr( $firstletter_font_family ); ?>;
		font-size: <?php echo esc_attr( $firstletter_fontsize ) .'px'; ?>;
		color: <?php echo esc_attr( $firstletter_contentcolor ); ?>
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .read-more-div a {
		color: <?php echo esc_attr( $readmorecolor ); ?>;
		background-color: <?php echo esc_attr( $readmorebackcolor); ?>;
		border-radius: <?php echo esc_attr( $readmore_button_border_radius ) .'px'; ?>;
		border-left: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderleftcolor ); ?> ;
		border-right: <?php echo esc_attr( $bdp_readmore_button_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderrightcolor ); ?> ;
		border-top: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_bordertopcolor ); ?> ;
		border-bottom: <?php echo esc_attr( $bdp_readmore_button_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_borderbottomcolor ); ?>;
		font-family: <?php echo esc_attr( $readmore_font_family); ?>;
		font-size: <?php echo esc_attr( $readmore_fontsize) .'px'; ?>;
		font-weight: <?php echo esc_attr( $readmore_font_weight); ?>;
		line-height: <?php echo esc_attr( $readmore_font_line_height); ?>;
		text-transform: <?php echo esc_attr( $readmore_font_text_transform); ?>;
		text-decoration: <?php echo esc_attr( $readmore_font_text_decoration); ?>;
		letter-spacing: <?php echo esc_attr( $readmore_font_letter_spacing); ?>;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .read-more-div a:hover {
		color: <?php echo esc_attr( $readmorehovercolor ); ?>;
		background-color: <?php echo esc_attr( $readmorehoverbackcolor); ?>;
		border-radius: <?php echo esc_attr( $readmore_button_hover_border_radius ) .'px'; ?>;
		border-left: <?php echo esc_attr( $bdp_readmore_button_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderleftcolor ); ?> ;
		border-right: <?php echo esc_attr( $bdp_readmore_button_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderrightcolor ); ?> ;
		border-top: <?php echo esc_attr( $bdp_readmore_button_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_bordertopcolor ); ?> ;
		border-bottom: <?php echo esc_attr( $bdp_readmore_button_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $read_more_button_hover_border_style ); ?> <?php echo esc_attr( $bdp_readmore_button_hover_borderbottomcolor ); ?>;
	}
	<?php if ( isset( $readmorebutton_on ) && 1 == $readmorebutton_on ) { 
		?>
		<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content  a.more-tag {
			color: <?php echo esc_attr( $readmorecolor ); ?>;
		}
		<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content  a:hover {
			color: <?php echo esc_attr( $readmorehovercolor ); ?> !important;
		}
		<?php } ?>
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .post_content {
		color: <?php echo esc_attr( $contentcolor );?> !important;
	}
	<?php echo esc_attr( $layout_id ); ?>.banner .bdp_post_content .post_content .bp-first-letter {
		font-family: <?php echo esc_attr( $firstletter_font_family ); ?>;
		font-size: <?php echo esc_attr( $firstletter_fontsize ) .'px'; ?>;
		color: <?php echo esc_attr( $firstletter_contentcolor ); ?>
	}
	<?php 
}

$archive_list = Bdp_Template::get_archive_list();
if ( is_author() && in_array( 'author_template', $archive_list ) ) { 
	?>
	.bdp_archive .author-avatar-div {background-color: <?php echo esc_attr( $author_bgcolor ); ?>;}
	.bdp_archive.chapter .author-avatar-div,.bdp_archive.hoverbic .author-avatar-div,.bdp_archive.crayon_slider .author-avatar-div{background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $author_bgcolor, 0.8 ) ); ?>;}
	.bdp_archive.explore .author-avatar-div {background: <?php echo esc_attr( Bdp_Utility::hex2rgba( $author_bgcolor, 0.5 ) ); ?>;}
	.bdp_archive .author-avatar-div .author_content .author a,.bdp_archive .author-avatar-div .author_content .author {
		color: <?php echo esc_attr( $author_titlecolor ); ?>;
		font-size: <?php echo esc_attr( $author_title_size ) . 'px'; ?>;
		<?php
		if ( isset( $author_title_face ) && $author_title_face ) {
			?>
			font-family: <?php echo esc_attr( $author_title_face ); ?>; <?php } ?>
		<?php
		if ( isset( $author_title_font_weight ) && $author_title_font_weight ) {
			?>
			font-weight: <?php echo esc_attr( $author_title_font_weight ); ?>;<?php } ?>
		<?php
		if ( isset( $author_title_font_line_height ) && $author_title_font_line_height ) {
			?>
			line-height: <?php echo esc_attr( $author_title_font_line_height ); ?>;<?php } ?>
		<?php
		if ( isset( $auhtor_title_font_italic ) && 1 == $auhtor_title_font_italic ) { 
			?>
			font-style: <?php echo 'italic'; ?>;<?php } ?>
		<?php
		if ( isset( $author_title_font_text_transform ) && $author_title_font_text_transform ) {
			?>
			text-transform: <?php echo esc_attr( $author_title_font_text_transform ); ?>;<?php } ?>
		<?php
		if ( isset( $author_title_font_text_decoration ) && $author_title_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $author_title_font_text_decoration ); ?>;<?php } ?>
		<?php
		if ( isset( $author_title_font_letter_spacing ) && $author_title_font_letter_spacing ) {
			?>
			letter-spacing: <?php echo esc_attr( $author_title_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		.bdp_archive .author-avatar-div .author_content p {
			color: <?php echo esc_attr( $author_content_color ); ?>;
			font-size: <?php echo esc_attr( $author_content_fontsize ) . 'px'; ?>;
			<?php
			if ( isset( $author_content_fontface ) && $author_content_fontface ) {
				?>
				font-family: <?php echo esc_attr( $author_content_fontface ); ?>; <?php } ?>
			<?php
			if ( isset( $author_content_font_weight ) && $author_content_font_weight ) {
				?>
				font-weight: <?php echo esc_attr( $author_content_font_weight ); ?>;<?php } ?>
			<?php
			if ( isset( $author_content_font_line_height ) && $author_content_font_line_height ) {
				?>
				line-height: <?php echo esc_attr( $author_content_font_line_height ); ?>;<?php } ?>
			<?php
			if ( isset( $auhtor_content_font_italic ) && 1 == $auhtor_content_font_italic ) { 
				?>
				font-style: <?php echo 'italic'; ?>;<?php } ?>
			<?php
			if ( isset( $author_content_font_text_transform ) && $author_content_font_text_transform ) {
				?>
				text-transform: <?php echo esc_attr( $author_content_font_text_transform ); ?>;<?php } ?>
			<?php
			if ( isset( $author_content_font_text_decoration ) && $author_content_font_text_decoration ) {
				?>
				text-decoration: <?php echo esc_attr( $author_content_font_text_decoration ); ?>;<?php } ?>
			<?php
			if ( isset( $auhtor_content_font_letter_spacing ) && $auhtor_content_font_letter_spacing ) {
				?>
				letter-spacing: <?php echo esc_attr( $auhtor_content_font_letter_spacing ) . 'px'; ?>;<?php } ?>
		}
		<?php
}
if ( isset( $firstletter_big ) && 1 == $firstletter_big ) { 
	$first_letter_line_height = $firstletter_fontsize * 75 / 100;
	?>
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post-content > *:first-child:first-letter,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post-content > p:first-child:first-letter,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post-content:first-letter,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post_content > *:first-child:first-letter,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post_content > p:first-child:first-letter,
	<?php echo esc_attr( $layout_id ); ?> .bdp_blog_template div.post_content:first-letter ,
	<?php echo esc_attr( $layout_id ); ?> .bdp-first-letter{
		<?php
		if ( isset( $firstletter_font_family ) && $firstletter_font_family ) {
			?>
			font-family:<?php echo esc_attr( $firstletter_font_family ); ?>; <?php } ?>
		font-size:<?php echo esc_attr( $firstletter_fontsize ) . 'px'; ?>;
		color: <?php echo esc_attr( $firstletter_contentcolor ); ?>;
		line-height: <?php echo esc_attr( $first_letter_line_height ) . 'px'; ?>;
		margin-right:5px;display:inline-block;
		<?php
		if ( isset( $content_font_text_decoration ) && $content_font_text_decoration ) {
			?>
			text-decoration: <?php echo esc_attr( $content_font_text_decoration ); ?>;<?php } ?>
	}
	<?php
}
?>

<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li a {
	padding: <?php echo esc_attr( $filter_paddingtop ) . 'px'; ?> <?php echo esc_attr( $filter_paddingright ) . 'px'; ?> <?php echo esc_attr( $filter_paddingbottom ) . 'px'; ?> <?php echo esc_attr( $filter_paddingleft ) . 'px'; ?> ;
	margin: <?php echo esc_attr( $filter_margintop ) . 'px'; ?> <?php echo esc_attr( $filter_marginright ) . 'px'; ?> <?php echo esc_attr( $filter_marginbottom ) . 'px'; ?> <?php echo esc_attr( $filter_marginleft ) . 'px'; ?> ;
	border-left: <?php echo esc_attr( $bdp_filter_borderleft ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_borderleftstyle ); ?> <?php echo esc_attr( $bdp_filter_borderleftcolor ); ?>;
	border-right: <?php echo esc_attr( $bdp_filter_borderright ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_borderrightstyle ); ?> <?php echo esc_attr( $bdp_filter_borderrightcolor ); ?>;
	border-top: <?php echo esc_attr( $bdp_filter_bordertop ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_bordertopstyle ); ?> <?php echo esc_attr( $bdp_filter_bordertopcolor ); ?>;
	border-bottom: <?php echo esc_attr( $bdp_filter_borderbottom ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_borderbottomstyle ); ?> <?php echo esc_attr( $bdp_filter_borderbottomcolor ); ?>;
	color: <?php echo esc_attr( $filter_color ); ?> !important;
	background-color: <?php echo esc_attr( $filter_background_color ); ?> !important;
}

<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li a.bdp_post_selected,
<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li a:hover {
	border-left: <?php echo esc_attr( $bdp_filter_hover_borderleft ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_hover_borderleftstyle ); ?> <?php echo esc_attr( $bdp_filter_hover_borderleftcolor ); ?>;
	border-right: <?php echo esc_attr( $bdp_filter_hover_borderright ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_hover_borderrightstyle ); ?> <?php echo esc_attr( $bdp_filter_hover_borderrightcolor ); ?>;
	border-top: <?php echo esc_attr( $bdp_filter_hover_bordertop ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_hover_bordertopstyle ); ?> <?php echo esc_attr( $bdp_filter_hover_bordertopcolor ); ?>;
	border-bottom: <?php echo esc_attr( $bdp_filter_hover_borderbottom ) . 'px'; ?> <?php echo esc_attr( $bdp_filter_hover_borderbottomstyle ); ?> <?php echo esc_attr( $bdp_filter_hover_borderbottomcolor ); ?>;
	transition: border-color 0.6s ease;transition: background-color 0.6s ease;transition: color 0.6s ease;
}

<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li a.bdp_post_selected,
<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li a:hover,
<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li span {
	color: <?php echo esc_attr( $filter_hover_color ); ?> !important;
	background-color: <?php echo esc_attr( $filter_background_hover_color ); ?> !important;
}
<?php echo esc_attr( $layout_filter_id ); ?> .bdp_filter_post_ul li span:before {
	border-top: 5px solid <?php echo esc_attr( $filter_background_hover_color ); ?> !important;
}
<?php
if ( isset( $bdp_settings['custom_css'] ) && ! empty( $bdp_settings['custom_css'] ) ) {
	echo $bdp_settings['custom_css']; 
}
?>

</style>
