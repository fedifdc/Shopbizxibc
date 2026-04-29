<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/edd_templates/archive/archive-download.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.3
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8 col-xs-12 ">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
				<?php
				do_action( 'bdp_before_download_archive_page' );
				$archive_id   = '';
				$bdp_theme    = '';
				$bdp_settings = array();
				$archive_list = array();
				$archive_list = Bdp_Edd::get_download_archive_list();
				$paged        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				if ( is_tax( 'download_category' ) && in_array( 'category_template', $archive_list ) ) {
					$categories        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$category_id       = $categories->term_id;
					$bdp_category_data = Bdp_Edd::get_download_category_template_settings( $category_id, $archive_list );
					$archive_id        = $bdp_category_data['id'];
					$bdp_settings      = $bdp_category_data['bdp_settings'];
					if ( $bdp_settings ) {
						$bdp_theme = $bdp_settings['template_name'];
					}
				} elseif ( is_tax( 'download_tag' ) && in_array( 'tag_template', $archive_list ) ) {
					$tags         = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$tag_id       = $tags->term_id;
					$bdp_tag_data = Bdp_Template::get_download_tag_template_settings( $tag_id, $archive_list );
					$archive_id   = $bdp_tag_data['id'];
					$bdp_settings = $bdp_tag_data['bdp_settings'];
					if ( $bdp_settings ) {
						$bdp_theme = $bdp_settings['template_name'];
					}
				}
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				$alter_class = '';
				$prev_year   = '';
				$alter       = 1;
				$alter_val   = null;
				global $wp_product_query;
				$arg_posts            = Bdp_Edd::get_download_archive_wp_query( $bdp_settings );
				$loop                 = new WP_Query( $arg_posts );
				$temp_query           = get_queried_object_id();
				$bdp_is_author        = is_author();
				$wp_product_query     = null;
				$wp_product_query     = $loop;
				$max_num_pages        = $wp_product_query->max_num_pages;
				$sticky_posts         = get_option( 'sticky_posts' );
				$prev_year1           = null;
				$prev_year            = null;
				$alter_val            = null;
				$prev_month           = null;
				$ajax_preious_year    = '';
				$ajax_preious_month   = '';
				$main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : '';
				if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'blog_carousel' === $bdp_theme ) {
					$unique_id = wp_rand();
				}
				if ( 'threed_carousel' === $bdp_theme ) {
					$unique_id = wp_rand();
				}
				if ( 'flip_book_3d' === $bdp_theme ) {
					$unique_id = wp_rand();
				}
				?>
				<?php
				$same_height_class = '';
				$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0';
				if ( 1 == $apply_same_height ) {
					$same_height_class = 'same_height_all';
				}
				if ( ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) ) {
					if ( ! wp_style_is( 'choosen-handle-css' ) ) {
						wp_enqueue_style( 'choosen-handle-css' );
					}
					if ( ! wp_script_is( 'choosen-handle-script' ) ) {
						wp_enqueue_script( 'choosen-handle-script' );
					}
					$filter_array = array( 'boxy', 'boxy-clean', 'cool_horizontal', 'overlay_horizontal', 'news', 'invert-grid', 'brit_co', 'media-grid' );
					if ( in_array( $bdp_theme, $filter_array ) ) {
						?>
						<form name="bdp-filer-change" id="bdp-filer-change">
						<?php
						echo '<div class="bdp_filter_option">';
						esc_html_e( 'Choose from below options to filter your posts', 'blog-designer-pro' );
						echo '<br/>';
						if ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) {
							while ( have_posts() ) :
								the_post();
								$dates[ get_the_time( 'Y-m' ) ] = get_the_time( 'F Y' );
								endwhile;
							?>
								<select name="filter_date[]" id="filter_date" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> date" multiple="multiple">
								<?php
								krsort( $dates );
								foreach ( $dates as $key => $value ) {
									?>
										<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php
								}
								?>
								</select>
								<?php
						}
						echo '</div>';
						?>
							<input type="hidden" name="blog_template" id="blog_template" value="<?php echo esc_attr( $bdp_theme ); ?>" />
							<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="<?php echo esc_attr( $layout_id ); ?>" />
							<input type="hidden" name="blog_itemMargin" id="blog_itemMargin" value="
							<?php
							if ( isset( $bdp_settings['template_post_margin'] ) ) {
								echo esc_attr( $bdp_settings['template_post_margin'] );
							}
							?>
							" />
							<input type="hidden" name="blog_itemWidth" id="blog_itemWidth" value="
							<?php
							if ( isset( $bdp_settings['item_width'] ) ) {
								echo esc_attr( $bdp_settings['item_width'] );
							}
							?>
							" />
							<input type="hidden" name="blog_itemHeight" id="blog_itemHeight" value="
							<?php
							if ( isset( $bdp_settings['item_height'] ) ) {
								echo esc_attr( $bdp_settings['item_height'] );
							}
							?>
							" />
							<input type="hidden" name="blog_easing" id="blog_easing" value="
							<?php
							if ( isset( $bdp_settings['template_easing'] ) ) {
								echo esc_attr( $bdp_settings['template_easing'] );
							}
							?>
							" />
							<input type="hidden" name="blog_startFrom" id="blog_startFrom" value="
							<?php
							if ( isset( $bdp_settings['timeline_start_from'] ) ) {
								echo esc_attr( $bdp_settings['timeline_start_from'] );
							}
							?>
							" />
							<input type="hidden" name="blog_hideLogbook" id="blog_hideLogbook" value="
							<?php
							if ( isset( $bdp_settings['display_timeline_bar'] ) ) {
								echo esc_attr( $bdp_settings['display_timeline_bar'] );
							}
							?>
							" />
							<input type="hidden" name="blog_autoplay" id="blog_autoplay" value="
							<?php
							if ( isset( $bdp_settings['enable_autoslide'] ) ) {
								echo esc_html( $bdp_settings['enable_autoslide'] );
							}
							?>
							" />
							<input type="hidden" name="blog_scrollSpeed" id="blog_scrollSpeed" value="
							<?php
							if ( isset( $bdp_settings['scroll_speed'] ) ) {
								echo esc_html( $bdp_settings['scroll_speed'] );
							}
							?>
							" />
							<input type="hidden" name="blog_page_number" id="blog_page_number" value="<?php echo esc_attr( $paged ); ?>" />
						</form>
							<?php
					}
				}
				?>
				<?php
				$url = '';
				if ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ) {
					$url = 'https://';
				} else {
					$url = 'http://';
				}
				$url              .= isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
				$url              .= isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
				$urlcheck          = $url;
				$enable_print_page = isset( $bdp_settings['enable_print_page'] ) ? $bdp_settings['enable_print_page'] : 1;
				if ( 1 == $enable_print_page ) {
					$txt_print_page = isset( $bdp_settings['txt_print_page'] ) ? $bdp_settings['txt_print_page'] : '';
					if ( isset( $bdp_settings['txt_print_page'] ) && '' != $bdp_settings['txt_print_page'] ) {
						?>
				<div class="printbtn" style="text-align:right; padding:30px;">
					<a href="<?php echo esc_url( $urlcheck ); ?>" class="print_click">
						<span class="pdftitle"><?php echo esc_html( $txt_print_page ); ?></span>
					</a>
				</div>

						<?php
					}
				}
				if ( 'blog_carousel' == $bdp_theme || 'sallet_slider' == $bdp_theme || 'colorful_sliding' === $bdp_theme || 'sunshiny_slider' == $bdp_theme ) {

					?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('#flexslider_script').remove();
								jQuery("div").removeClass("flex-viewport");
								jQuery(".flex-viewport").removeAttr("style");
								jQuery("ul").removeAttr("style");
								jQuery("li").removeAttr("style");
								jQuery('.sunshiny_slider').css("margin-top", "30px");
								jQuery('.sunshiny_slider').css("width", "203.333px");
								jQuery(".post_hentry_1").removeAttr("style");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } elseif ( 'brit_co' == $bdp_theme ) { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery("div").removeClass("content_wrapper");
								jQuery(".bdp_blog_wraper").removeAttr("style");
								jQuery(".content_bottom_wrapper").css("display", "block");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
					<?php
				} elseif ( 'cool_horizontal' == $bdp_theme || 'overlay_horizontal' == $bdp_theme ) {
					?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('.logbook_script').remove();
								jQuery('.lb-items').removeAttr("style");
								jQuery('.lb-items').css("display", "block");
								jQuery('.horizontal').css("margin-top", "30px");
								jQuery('.overlay_horizontal').css("margin-top", "30px");
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
					<?php
				} elseif ( 'crayon_slider' == $bdp_theme ) {
					?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('#flexslider_script').remove();
								jQuery("div").removeClass("flex-viewport");
								jQuery(".flex-viewport").removeAttr("style");
								jQuery("ul").removeAttr("style");
								jQuery("li").removeAttr("style");
								jQuery('crayon_slider').css("margin-top", "30px");
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
					<?php
				} elseif ( 'explore' == $bdp_theme ) {
					?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('.post_hentry').each(function() {
									jQuery('.grid-overlay').css('visibility', 'visible');
									jQuery('.grid-overlay').css('transform', 'unset');
									jQuery('.grid-overlay').css('-webkit-transform', 'unset');
									jQuery('.blog_header').css('transition', 'unset');
									jQuery('.blog_header').css('top', '15%');
								});
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } elseif ( 'hoverbic' == $bdp_theme ) { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('.post_hentry').each(function() {
									jQuery('.blog_header').css('backface-visibility', 'visible');
									jQuery('.blog_header').css('opacity', '1');
									jQuery('.blog_header').css('transform', 'rotateY(0deg)');
									jQuery('.blog_template.hoverbic').css('height', '390px');
								});
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } elseif ( 'roctangle' == $bdp_theme ) { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery(".roctangle-post-wrapper").removeAttr("style");
								jQuery('.post-image').css('transform', 'matrix(1, -0.14, 0, 1, 0, 20);');
								jQuery('.roctangle img').css('height', '300px');
								jQuery('.roctangle img').css('width', '300px');
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } elseif ( 'threed_carousel' == $bdp_theme ) { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('#carousel_script').remove();
								jQuery(".bdp-3d-container").removeAttr("style");
								jQuery("ul").removeAttr("style");
								jQuery("li").removeAttr("style");
								jQuery('.threed_carousel').css("margin-top", "30px");
								jQuery('.post_hentry_1').each(function() {
									//$('.blog_header').removeAttr("style");
									jQuery('.blog_header').css('top', '50%');
									jQuery('.blog_header').css('width', '100%');
									jQuery('.blog_header').css('overflow', 'unset');
									jQuery('.blog_header').css('opacity', '0.7');
								});
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } elseif ( 'flip_book_3d' == $bdp_theme ) { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery('#bookblock-thumb-style').remove();
								jQuery('#bookblock-script').remove();
								jQuery(".bdp_flip_book_3d.bb-bookblock").removeAttr("style");
								jQuery(".bdp_flip_book_3d").removeAttr("style");
								jQuery(".bb-item").removeAttr("style");
								jQuery('.blog_template.flip_book_3d').css("margin-top", "30px");
								jQuery('.bb-item').each(function() {
									jQuery(".flip_book_3d.bb-item").css("display", "block");
									jQuery(".flip_book_3d.bb-item").css('position', 'unset');
									jQuery('.blog_header').css('width', '100%');
									jQuery('.blog_header').css('overflow', 'unset');
									jQuery('.blog_header').css('opacity', '0.7');
									jQuery('.blog_header').css('transform', 'unset');
									jQuery('.post_hentry_1 img').css('vertical-align', 'unset');
								});
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
				<?php } else { ?>
					<script>
						jQuery(document).ready(function() {
							jQuery('.print_click').on("click", function(e) {
								jQuery("img").removeClass(" lazyloaded");
								jQuery("img").removeClass("lazyload");
								jQuery("img").removeAttr("style");
								jQuery('.famous-grid').css("margin-top", "30px");
								jQuery('.blog_header').removeClass('hover');
								jQuery('.print_click').hide();
								window.print();
							});
						});
					</script>
					<?php
				}
				?>
				<div class="blog_template bdp_archive_download_product_template bdp_wrapper <?php echo esc_attr( $bdp_theme ); ?>_cover bdp_archive <?php echo esc_attr( $bdp_theme ) . ' layout_id_' . esc_attr( $archive_id ); ?>">
					<?php
					if ( '' != $main_container_class ) {
						echo '<div class="' . esc_attr( $main_container_class ) . '">';
					}
					if ( 'offer_blog' === $bdp_theme ) {
						echo '<div class="bdp_single_offer_blog">';
					}
					if ( 'accordion' === $bdp_theme ) {
						$template_icon_alignment = ( isset( $bdp_settings['template_icon_alignment'] ) && '' != $bdp_settings['template_icon_alignment'] ) ? $bdp_settings['template_icon_alignment'] : 'icon-left';

						$bdp_accordion_layout_class = ( isset( $bdp_settings['accordion_template'] ) && '' != $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1';
						echo '<div class="blog_template accordion accordion_wrapper ' . esc_attr( $bdp_accordion_layout_class ) . ' ' . esc_attr( $template_icon_alignment ) . '">';

					}
					if ( 'winter' === $bdp_theme ) {
						echo '<div class="bdp_single_winter">';
					}
					if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_more_btn' === $bdp_settings['pagination_type'] ) ) {
						echo "<div class='bdp-load-more-pre'>";
					}
					if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) ) {
						echo "<div class='bdp-load-onscroll-pre' id='bdp-load-onscroll-pre'>";
					}
					if ( 'timeline' === $bdp_theme ) {
						if ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'left_side' === $bdp_settings['bdp_timeline_layout'] ) {
							if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
								echo '<div class="timeline_bg_wrap left_side with_year"><div class="timeline_back clearfix">';
							} else {
								echo '<div class="timeline_bg_wrap left_side"><div class="timeline_back clearfix">';
							}
						} elseif ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'right_side' === $bdp_settings['bdp_timeline_layout'] ) {
							if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
								echo '<div class="timeline_bg_wrap right_side with_year"><div class="timeline_back clearfix">';
							} else {
								echo '<div class="timeline_bg_wrap right_side"><div class="timeline_back clearfix">';
							}
						} elseif ( 'date' === $orderby || 'modified' === $orderby ) {
								echo '<div class="timeline_bg_wrap date_order"><div class="timeline_back clearfix">';
						} else {
							echo '<div class="timeline_bg_wrap"><div class="timeline_back clearfix">';
						}
						$ajax_preious_year  = get_the_date( 'Y' );
						$ajax_preious_month = get_the_time( 'M' );
					}
					if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
						echo '<div class="bdp-row ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . '">';
					}
					if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'advice' === $bdp_theme || 'minimal' === $bdp_theme ) {
						$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . esc_attr( $bdp_settings['column_setting'] ) : 'column_layout_2';
						$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . esc_attr( $bdp_settings['column_setting_ipad'] ) : 'column_layout_ipad_2';
						$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . esc_attr( $bdp_settings['column_setting_tablet'] ) : 'column_layout_tablet_1';
						$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . esc_attr( $bdp_settings['column_setting_mobile'] ) : 'column_layout_mobile_1';
						$column_class          = esc_attr( $column_setting ) . ' ' . esc_attr( $column_setting_ipad ) . ' ' . esc_attr( $column_setting_tablet ) . ' ' . esc_attr( $column_setting_mobile );
						if ( 'roctangle' === $bdp_theme ) {
							echo '<div class="bdp-row masonry ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
						} else {
							echo '<div class="bdp-row ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . '">';
						}
					}
					if ( 'glossary' === $bdp_theme || 'quci' === $bdp_theme || 'boxy' === $bdp_theme ) {
						echo '<div class="bdp-js-masonry masonry bdp_' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
					}
					if ( 'boxy-clean' === $bdp_theme ) {
						echo '<div class="blog_template boxy-clean"><ul>';
					}
					$slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation1';
					$cd_design_type    = isset( $bdp_settings['cd_design_type'] ) ? $bdp_settings['cd_design_type'] : 'design1';
					if ( 'crayon_slider' === $bdp_theme ) {
						if ( 'design2' === $cd_design_type ) {
							echo '<div class="blog_template slider_template crayon_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides ' . esc_attr( $cd_design_type ) . '">';
						} else {
							echo '<div class="blog_template slider_template crayon_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
						}
					}
					if ( 'sallet_slider' === $bdp_theme ) {
						echo '<div class="blog_template slider_template sallet_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'colorful_sliding' === $bdp_theme ) {
						echo '<div class="blog_template slider_template colorful_sliding ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'sunshiny_slider' === $bdp_theme ) {
						echo '<div class="blog_template slider_template sunshiny_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'blog_carousel' === $bdp_theme ) {
						echo '<div class="blog_template slider_template blog_carousel ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'threed_carousel' === $bdp_theme ) {
						echo '<div class="bdp-3d-container blog_template slider_template  slider_' . esc_attr( $unique_id ) . ' "><ul class=" slides slider_gallery ">';
					}
					if ( 'flip_book_3d' === $bdp_theme ) {
						echo '<div class="bb-custom-wrapper blog_template slider_template slider_' . esc_attr( $unique_id ) . ' "><div id="bb-bookblock" class="bdp_flip_book_3d bb-bookblock">';
					}
					if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
						echo '<div class="logbook flatLine flatNav flatButton">';
					}
					if ( 'easy_timeline' === $bdp_theme ) {
						echo '<div class="blog_template bdp_blog_template easy-timeline-wrapper"><ul class="easy-timeline" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
					}
					if ( 'steps' === $bdp_theme ) {
						echo '<div class="blog_template bdp_blog_template steps-wrapper"><ul class="steps" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
					}
					if ( 'my_diary' === $bdp_theme ) {
						echo '<div class="my_diary_wrapper">';
					}
					if ( 'story' === $bdp_theme ) {
						echo '<div class="story_wrapper">';
					}
					if ( 'brite' === $bdp_theme ) {
						echo '<div class="brite-wrapp">';
					}
					if ( 'foodbox' === $bdp_theme ) {
						echo '<div class="foodbox-blog-wrapp">';
					}
					if ( 'neaty_block' === $bdp_theme ) {
						echo '<div class="neaty_block_blog_wrapp">';
					}
					if ( 'wise_block' === $bdp_theme ) {
						echo '<div class="blog_template wise_block_wrapper ' . esc_attr( $same_height_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
					}
					if ( 'soft_block' === $bdp_theme ) {
						echo '<div class="blog_template soft_block_wrapper">';
					}
					if ( 'schedule' === $bdp_theme ) {
						echo '<div class="blog_template schedule_wrapper">';
					}
					if ( 'ticker' === $bdp_theme ) {
						$ticker_effect            = isset( $bdp_settings['ticker_effect'] ) ? $bdp_settings['ticker_effect'] : 'fade';
						$ticker_autoplay_interval = isset( $bdp_settings['ticker_autoplay_interval'] ) ? $bdp_settings['ticker_autoplay_interval'] : '3000';
						$ticker_autoplay          = ( isset( $bdp_settings['ticker_autoplay'] ) && ( '1' == $bdp_settings['ticker_autoplay'] ) ) ? 'true' : 'false';
						$ticker_label_text        = isset( $bdp_settings['ticker_label_text'] ) ? $bdp_settings['ticker_label_text'] : esc_html_e( 'Latest Post', 'blog-designer-pro' );
						echo '<div class="blog-ticker-wrapper" id="blog-ticker-style-' . esc_attr( $ticker_effect ) . '" data-conf="{&quot;ticker_effect&quot;:&quot;' . esc_attr( $ticker_effect ) . '&quot;,&quot;autoplay&quot;:&quot;' . esc_attr( $ticker_autoplay ) . '&quot;,&quot;speed&quot;:' . esc_attr( $ticker_autoplay_interval ) . ',&quot;font_style&quot;:&quot;normal&quot;,&quot;scroll_speed&quot;:1}">';
						echo '<div class="ticker-title">
						<div class="ticker-style-title">' . esc_html( $ticker_label_text ) . '</div>
						<span></span>
						</div>';
						echo '<div class="blog-ticker-controls">
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-prev"></span></div>
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-next"></span></div>
						</div>';
						echo '<div class="blog-tickers">
						<ul>';
					}
					if ( 'banner' === $bdp_theme ) {
						echo '<div class="banner-container"><div class="banner-row">';
					}

					$display_tabbed_filter_by = 'download_category';
					if ( isset( $bdp_settings['archive_display_tabbed_filter_by'] ) ) {
						$display_tabbed_filter_by = $bdp_settings['archive_display_tabbed_filter_by'];

					}
					$tabbed_slug       = array();
					$tabbed_post_style = 0;
					$post_type         = get_post_type( $archive_id );
					if ( 'tabbed' === $bdp_theme ) {
						$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
						$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
						if ( ! empty( $terms ) ) {
							foreach ( $taxonomy_names as $taxonomy_name ) {
								if ( $taxonomy_name->name == $display_tabbed_filter_by ) {
									if ( isset( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] ) ) {
										$tax_count = count( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] );
										foreach ( $terms as $term ) {
											for ( $i = 0; $i < $tax_count; $i++ ) {
												if ( $term->name == $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ][ $i ] ) {
													$tabbed_slug[ $term->name ] = $term->slug;
												}
											}
										}
									}
								}
							}
							array_unique( $tabbed_slug );
						}
						if ( empty( $tabbed_slug ) ) {
							$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
							$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
							foreach ( $taxonomy_names as $taxonomy_name ) {
								foreach ( $terms as $term ) {
									$tabbed_slug[ $term->name ] = $term->slug;
								}
							}
							array_unique( $tabbed_slug );
						}
						wp_enqueue_script( 'jquery' );
						?>
					<script>
						jQuery(document).ready(function() {
							var $tabs = jQuery(".layout_id_<?php echo esc_attr( $archive_id ); ?> #tabs").tabs({
								activate: function(event, ui) {
									var active = jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> #tabs').tabs('option', 'active');
									var active_content = jQuery(".layout_id_<?php echo esc_attr( $archive_id ); ?> #tabs ul>li a").eq(active).attr("href");
									var leftContent = jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> #tabs').find(active_content + " .left-side.bdp-tabbed-all-post-content").outerHeight();
									var rightContent = jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> #tabs').find(active_content + " .right-side.bdp-tabbed-all-post-content").outerHeight();
									if (rightContent > leftContent) {
										jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', 'scroll');
										jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> .right-side.bdp-tabbed-all-post-content').css('max-height', leftContent);
									} else {
										jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', '')
									}
								}
							});
							jQuery(".layout_id_<?php echo esc_attr( $archive_id ); ?> .ui-tabs-panel").each(function(i) {
								var totalSize = jQuery(".layout_id_<?php echo esc_attr( $archive_id ); ?> .ui-tabs-panel").size();
								if (i != totalSize) {
									next = i + 1;
									jQuery(this).append("<a href='#' class='next-tab mover' rel='" + next + "'><i class='fa fa-angle-right' aria-hidden='true'></i></a>");
								}
								if (i != totalSize) {
									prev = i - 1;
									jQuery(this).append("<a href='#' class='prev-tab mover' rel='" + prev + "'><i class='fa fa-angle-left' aria-hidden='true'></i></a>");
								}
							});
							jQuery('.layout_id_<?php echo esc_attr( $archive_id ); ?> .next-tab, .layout_id_<?php echo esc_attr( $archive_id ); ?> .prev-tab').on('click', function() {
								$tabs.tabs('option', 'active', jQuery(this).attr("rel"));
								return false
							});
						});
					</script>
						<?php

						echo '<div id="tabs">';
						if ( ! empty( $tabbed_slug ) ) {
							echo '<ul class="tabs">';
							foreach ( $tabbed_slug as $key => $val ) {
								echo '<li><a href="#' . esc_attr( $val ) . '">' . esc_html( $val ) . '</a></li>';
							}
							echo '</ul>';
							$tabbi             = 1;
							$post_type         = $post_type;
							$bdp_tabbed_layout = 'left_side';
							if ( isset( $bdp_settings['bdp_tabbed_layout'] ) ) {
								$bdp_tabbed_layout = $bdp_settings['bdp_tabbed_layout'];
							}
							foreach ( $tabbed_slug as $key => $val ) {

								$tabbed_posts              = Bdp_Posts::get_wp_query( $bdp_settings );
								$tabbed_posts['tax_query'] = array(
									array(
										'taxonomy' => $display_tabbed_filter_by,
										'field'    => 'slug',
										'terms'    => $val,
									),
								);

								$tabbed     = new WP_Query( $tabbed_posts );
								$post_count = $tabbed->post_count;
								if ( $tabbed->have_posts() ) {
									$ti                = 1;
									$classes           = '';
									$tabbed_post_style = 0;
									$alter_class       = '';
									$prev_year         = '';
									$paged             = 0;
									$count_sticky      = '';
									$alter_val         = '';
									echo '<div id="' . esc_attr( $val ) . '" class="' . esc_attr( $classes ) . '">';
									while ( $tabbed->have_posts() ) :
										$tabbed->the_post();
										if ( 1 == $ti ) {
											echo '<div class="left-side bdp-tabbed-all-post-content ' . esc_attr( $bdp_tabbed_layout ) . '">';
										}
										if ( $ti > 1 ) {
											$tabbed_post_style = 1;
										}
										if ( 2 == $ti ) {
											echo '<div class="right-side bdp-tabbed-all-post-content ' . esc_attr( $bdp_tabbed_layout ) . '">';
										}

										Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
										// Bdp_Template::get_template('archive/' . $bdp_theme . '.php');.
										do_action( 'bd_tabbed_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style );
										if ( 1 == $ti ) {
											echo '</div>';
										}

										++$ti;

									endwhile;
									if ( $post_count > 1 ) {
										echo '</div>';
									}
									echo '</div>';
								}

								wp_reset_postdata();
								++$tabbi;
							}
						}

						echo '</div>';
					}


					if ( have_posts() ) {
						while ( $loop->have_posts() ) :
							$loop->the_post();
							if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = ' alternative-back ';
								} else {
									$alter_class = '';
								}
							}
							if ( 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'story' === $bdp_theme || 'fairy' === $bdp_theme || 'clicky' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'leest' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'text-top';
								} else {
									$alter_class = 'text-bottom';
								}
							}
							if ( 'timeline' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_attr( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$prev_month = '';
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_attr( $this_year ) . '</span><span class="month">' . esc_attr( $prev_month ) . '</span></span></p>';
											}
										}
									}
								}
								if ( 'story' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										$this_year = get_the_date( 'Y' );
										if ( $prev_year1 != $this_year ) {
											$prev_year1 = $this_year;
											$prev_year  = 0;
										} elseif ( $prev_year1 == $this_year ) {
											$prev_year = 1;
										}
									} else {
										$prev_year = get_the_date( 'Y' );
									}
								}
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 1 == $firstpost_unique_design ) {
									if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
													$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							++$alter;
						endwhile;
					}
					if ( 'foodbox' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'neaty_block' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'wise_block' === $bdp_theme || 'soft_block' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'schedule' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'blog_carousel' === $bdp_theme ) {
						echo '</ul></div>';
						if ( isset( $bdp_settings['post_slider_thumb'] ) && 1 == $bdp_settings['post_slider_thumb'] ) {
							echo '<div class="post_slider_thumbnail slider_template ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
							while ( have_posts() ) :
								the_post();
								$post_thumbnail = 'full';
								$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
								echo '<li>' . wp_kses( $thumbnail, Bdp_Admin_Functions::args_kses() ) . '</li>';
							endwhile;
							echo '</ul></div>';
						}
					}
					if ( 'threed_carousel' === $bdp_theme ) {
						echo '</ul></div>';
					}
					if ( 'flip_book_3d' === $bdp_theme ) {
						$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
						if ( 1 == $display_slider_controls ) {
							$pre  = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
							$next = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
						}

							echo '<nav><span id="bb-nav-prev" aria-label="Previous" tabindex="0" role="button"  data-role="none"  class="bb-custom-icon bb-custom-icon-arrow-left bd-arrows left bd-left-arrow  ' . esc_attr( $pre ) . ' "></span> <span id="bb-nav-next" aria-label="Previous" tabindex="0" role="button" data-role="none" class="bb-custom-icon bb-custom-icon-arrow-right bd-arrows right bd-right-arrow  ' . esc_attr( $next ) . '" ></span></nav></div></div>';
					}
					if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme || 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'quci' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
						echo '</div>';
					}

					if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'advice' === $bdp_theme || 'minimal' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'timeline' === $bdp_theme ) {
						echo '</div>
                            </div>';
					}
					if ( 'easy_timeline' === $bdp_theme || 'steps' === $bdp_theme ) {
						echo '</div></ul>';
					}
					if ( 'offer_blog' === $bdp_theme || 'winter' === $bdp_theme || 'my_diary' === $bdp_theme || 'story' === $bdp_theme || 'brite' === $bdp_theme || 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme || 'accordion' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'ticker' === $bdp_theme ) {
						echo '</ul></div></div>';
					}
					if ( 'ticker' === $bdp_theme ) {
						echo '</div></div>';
					}
					$slider_array = array( 'cool_horizontal', 'overlay_horizontal', 'crayon_slider', 'sunshiny_slider', 'sallet_slider', 'colorful_sliding', 'blog_carousel', 'threed_carousel', 'flip_book_3d' );
					if ( ! in_array( $bdp_theme, $slider_array ) && ( isset( $bdp_settings['pagination_type'] ) && 'no_pagination' !== $bdp_settings['pagination_type'] ) ) {
						if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_more_btn' === $bdp_settings['pagination_type'] ) ) {
							echo '</div>';
							$is_loadmore_btn = '';
							if ( $max_num_pages > 1 ) {
								$is_loadmore_btn = '';
							} else {
								$is_loadmore_btn = '1';
							}
							if ( is_front_page() ) {
								$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
							} else {
								$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							}
							$bdp_search_text = '';
							if ( isset( $_GET['s'] ) ) {
								$bdp_search_text = esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
							}
							echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';
							echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
							echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
							echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
							echo '<input type="hidden" name="term_id" id="term_id" value="' . esc_attr( $temp_query ) . '" />';
							echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
							echo '<input type="hidden" name="blog_layout" id="blog_layout" value="download_archive_layout" />';
							echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $archive_id ) . '" />';
							if ( 'timeline' === $bdp_theme ) {
								echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
								echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
							}
							echo wp_kses( Bdp_Utility::get_loader( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							echo '</form>';
							if ( '' === $is_loadmore_btn ) {
								$class = isset( $bdp_settings['load_more_button_template'] ) ? $bdp_settings['load_more_button_template'] : 'template-1';
								echo '<div class="bdp-load-more text-center" style="float:left;width:100%">';
								echo '<a href="javascript:void(0);" class="button bdp-load-more-btn ' . esc_attr( $class ) . '">';
								echo isset( $bdp_settings['loadmore_button_text'] ) && '' != $bdp_settings['loadmore_button_text'] ? esc_html( $bdp_settings['loadmore_button_text'] ) : esc_html__( 'Load More', 'blog-designer-pro' );
								echo '</a>';
								echo '</div>';
							}
						} elseif ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) ) {
							echo '</div>';
							$is_load_onscroll_btn = '';
							if ( $max_num_pages > 1 ) {
								$is_load_onscroll_btn = '';
							} else {
								$is_load_onscroll_btn = '1';
							}
							$bdp_search_text = '';
							if ( isset( $_GET['s'] ) ) {
								$bdp_search_text = esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
							}
							if ( is_front_page() ) {
								$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
							} else {
								$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							}
							echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';

							echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
							if ( 'story' === $bdp_theme ) {
								echo '<input type="hidden" name="this_year" id="this_year" value="' . esc_attr( $this_year ) . '" />';
							}
							echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
							echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
							echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
							echo '<input type="hidden" name="blog_layout" id="blog_layout" value="download_archive_layout" />';
							echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $archive_id ) . '" />';
							echo '<input type="hidden" name="term_id" id="term_id" value="' . esc_attr( $category_id ) . '" />';
							if ( 'timeline' === $bdp_theme ) {
								echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
								echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
							}
							echo wp_kses( Bdp_Utility::get_loader( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							echo '</form>';
							if ( '' === $is_load_onscroll_btn ) {
								$class = '';
								echo '<div class="bdp-load-onscroll text-center">';
								echo '<a href="javascript:void(0);" class="button bdp-load-onscroll-btn ' . esc_url( $class ) . '">';
								echo esc_html__( 'Loading Posts', 'blog-designer-pro' ) . '</a>';
								echo '</div>';
							}
						}
						if ( isset( $bdp_settings['pagination_type'] ) && 'paged' === $bdp_settings['pagination_type'] ) {
							$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
							echo '<div class="wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
							echo wp_kses( Bdp_Woocommerce::standard_product_paging_nav( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							echo '</div>';
						}
					}
					if ( '' != $main_container_class ) {
						echo '</div">';
					}
					wp_reset_postdata();
					$wp_product_query = null;
					$wp_product_query = $temp_query;
					wp_reset_postdata();
					?>
				</main>
			</div>
		</div>
		<div class="col-sm-4 col-xs-12 blog-sidebar">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
