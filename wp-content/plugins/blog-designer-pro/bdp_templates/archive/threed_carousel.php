<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/threed_carousel.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      2.3
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'bd_archive_design_format_function', 'bdp_archive_threed_carousel_template', 10, 2 );

if ( ! function_exists( 'bdp_archive_threed_carousel_template' ) ) {

	/**
	 * Add html for 3D_carousel template
	 *
	 * @param array $bdp_settings settings.
	 * @param type  $alterclass class.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_threed_carousel_template( $bdp_settings, $alterclass ) {

		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}

		global $post;
		$post_type            = get_post_type( $post->ID );
		$bdp_all_post_type    = array( 'product', 'download' );
		$class_name           = 'blog_template bdp_blog_template threed_carousel';
		$bdp_post_image_link  = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
		$slider_column        = 1;
		$slider_column_ipad   = 1;
		$slider_column_tablet = 1;
		$slider_column_mobile = 1;
		if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
			$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
			$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
			$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
			$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;
		}
		$column_class = 'columns_' . esc_attr( $slider_column ) . ' columns_' . esc_attr( $slider_column_ipad ) . ' columns_' . esc_attr( $slider_column_tablet ) . ' columns_' . esc_attr( $slider_column_mobile );
		?>
		<li class="<?php echo esc_attr( $class_name ) . ' ' . esc_attr( $column_class ); ?>">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="post_hentry_1">
				<div class="bdp-post-image">
					<?php
					$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
					if ( '' != $label_featured_post && is_sticky() ) {
						?>
						<div class="label_featured_post"><span><?php echo esc_attr( $label_featured_post ); ?></span></div> 
						<?php
					}
					if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
						?>
						<div class="bdp-post-image post-video">
							<?php
							if ( 'quote' === get_post_format() ) {
								if ( has_post_thumbnail() ) {
									$post_thumbnail = 'full';
									$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
									echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
									echo '<div class="upper_image_wrapper">';
									echo wp_kses( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
									echo '</div>';
								}
							} elseif ( 'link' === get_post_format() ) {
								if ( has_post_thumbnail() ) {
									$post_thumbnail = 'full';
									$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
									echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
									echo '<div class="upper_image_wrapper bdp_link_post_format">';
									echo wp_kses( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
									echo '</div>';
								}
							} else {
								echo wp_kses( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							}
							?>
						</div>
						<?php
					} else {
						$post_thumbnail      = 'full';
						$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
						if ( ! empty( $thumbnail ) ) {
							echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
							echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
							echo ( $bdp_post_image_link ) ? '</a>' : '';

							if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
								?>
								<div class="bdp-pinterest-share-image">
									<?php
									$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
									?>
									<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
								</div>
								<?php
							}
							if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
								$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
								echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
								do_action( 'bdp_woocommerce_sale_tag' );
								echo '</div>';
							}
						}
					}

					?>
				</div>
				<div class="blog_header">
				<div class="blog_single_wrap_box"> 
					<?php
					if ( in_array( $post_type, $bdp_all_post_type ) ) {
						$bdp_tax_cat = '';
						if ( 'product' === $post_type ) {
							$bdp_tax_cat = 'product_cat';
						} elseif ( 'download' === $post_type ) {
							$bdp_tax_cat = 'download_category';
						}
						if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) {
							$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true;
							$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
							$sep                = 1;
							?>
								<div class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
								<?php
								foreach ( $product_categories as $category ) {
									if ( 1 != $sep ) {
										?>
										<span class="seperater"></span>
										<?php
									}
									echo ( $categories_link ) ? '<a href="' . esc_url( get_term_link( $category->term_id ) ) . '">' : '';
									echo esc_html( $category->name );
									echo ( $categories_link ) ? '</a>' : '';
									++$sep;
								}
								?>
							</div>
							<?php
						}
					} elseif ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
						if ( $categories_link ) {
							$categories_list = get_the_category_list( ', ' );
							$categories_list = wp_strip_all_tags( $categories_list );
						}
						if ( $categories_list ) :
							echo '<div class="category-link">';
							echo wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
							$show_sep = true;
							echo '</div>';
							endif;
					}
					?>
					<h2>
						<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
						if ( 1 == $bdp_post_title_link ) {
							?>
							<a href="<?php esc_url( the_permalink() ); ?>">
							<?php } ?>
							<?php
							$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
							echo ( $total_noofline ) ? '<span>' : '';
							echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
							echo ( $total_noofline ) ? '</span>' : '';
							if ( 1 == $bdp_post_title_link ) {
								?>
							</a>
						<?php } ?>
					</h2>

					<?php
					if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] || 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
						?>
						<div class="metadatabox">
							<?php
							if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) {
								if ( 1 == $bdp_settings['display_author'] ) {
									$author_link  = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
									$author_class = ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] && 'gallery' !== get_post_format( $post->ID ) ) ? 'class="post-video-format"' : '';
									?>
									<div class="mauthor">
										<i class="fas fa-user"></i>
										<span class="author">
											<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
										</span>
									</div>
									<?php
								}
								if ( 1 == $bdp_settings['display_date'] ) {
									$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
									?>
									<div class="post-date">
										<i class="fas fa-calendar-alt"></i>
										<?php
										$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
										$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
										$ar_year     = get_the_time( 'Y' );
										$ar_month    = get_the_time( 'm' );
										$ar_day      = get_the_time( 'd' );
										echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
										echo esc_html( $bdp_date );
										echo ( $date_link ) ? '</a>' : '';
										?>
									</div>
									<?php
								}
							}
							if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
								if ( 1 == $bdp_settings['display_comment_count'] ) {
									?>
									<div class="post-comment">
										<i class="fas fa-comments"></i>
										<?php
										if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
											comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
										} else {
											comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
										}
										?>
									</div>
									<?php
								}

								if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
									echo '<div class="postlike_btn">';
									echo do_shortcode( '[likebtn_shortcode]' );
									echo '</div>';
								}
							}
							?>
						</div>
						<?php
					}
					if ( 'product' === $post_type ) {
						do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
					}
					if ( 'download' === $post_type ) {
						do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
					}
					?>
					<div class="post_content">
						<div class="post_content-inner">
							<?php
							echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ), Bdp_Admin_Functions::args_kses() );
							$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
							$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
							$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
							if ( 'new' == $link_behaviour ) {
								$link_behaviour = '_blank';
							} elseif ( 'self' == $link_behaviour ) {
								$link_behaviour = '_SELF';
							}
							if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) {
								$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
								$post_link   = get_permalink( $post->ID );
								if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
									$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
								}
								if ( 2 == $read_more_on ) {
									echo '<div class="read-more">';
								}
								echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
								if ( 2 == $read_more_on ) {
									echo '</div>';
								}
							}
							?>
						</div>
					</div>

					<?php
					if ( in_array( $post_type, $bdp_all_post_type ) ) {
						$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
						$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
						foreach ( $taxonomy_names as $taxonomy_single ) {
							$taxonomy = $taxonomy_single->name;
							$sep      = 1;
							if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
								$term_list            = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
								$taxonomy_link        = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
								$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
								if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
									if ( isset( $term_list ) && ! empty( $term_list ) ) {
										?>
										<div class="tags taxonomy">
											<span class="link-lable"> <i class="fas fa-folder"></i> <?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
											<?php
											foreach ( $term_list as $term_nm ) {
												$term_link = get_term_link( $term_nm );

												if ( 1 != $sep ) {
													?>
													<span class="seperater"><?php echo ', '; ?></span>
													<?php
												}
												echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
												echo esc_html( $term_nm->name );
												echo ( $taxonomy_link ) ? '</a>' : '';
												++$sep;
											}
											?>
										</div>
										<?php
									}
								}
							}
						}
					} elseif ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
							$tags_list = get_the_tag_list( '', ', ' );
							$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
						if ( $tag_link ) {
							$tags_list = wp_strip_all_tags( $tags_list );
						}
						if ( $tags_list ) :
							?>
								<div class="tags"> 
									<i class="fas fa-tags"></i>
							<?php
								echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
								$show_sep = true;
							?>
								</div>
							<?php
							endif;
					}
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					Bdp_Utility::get_social_icons( $bdp_settings );
					?>
					</div>
				</div>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</li>
		<?php
		$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
		if ( 1 == $display_slider_controls ) {
			?>
		<span data-role="none" class="bd-arrows left bd-left-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings    ['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>
		<span data-role="none" class="bd-arrows right bd-right-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>
			<?php
		}
	}
}
