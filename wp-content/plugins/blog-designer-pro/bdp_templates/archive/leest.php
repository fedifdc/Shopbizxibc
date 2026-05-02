<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/leest.php.
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

add_action( 'bd_archive_design_format_function', 'bdp_archive_leest_template', 10, 2 );

if ( ! function_exists( 'bdp_archive_leest_template' ) ) {

	/**
	 * Add html for leest template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class Link.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_leest_template( $bdp_settings, $alter_class ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );
		}

		global $post;
		$post_type           = get_post_type( $post->ID );
		$bdp_all_post_type   = array( 'product', 'download' );
		$class_name          = 'blog_template bdp_blog_template leest blog_masonry_item';
		$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;

		$slider_speed              = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : 600;
		$slider_autoplay           = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : 1;
		$slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : 100;
		$template_slider_scroll    = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : 1;
		$display_slider_controls   = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 1;
		$display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : 1;

		$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
		$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
		$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;

		if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
			$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
			$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
			$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
			$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;

			$template_slider_effect = isset( $bdp_settings['template_slider_effect'] ) ? $bdp_settings['template_slider_effect'] : 1;

		} else {
			$slider_column          = 1;
			$slider_column_ipad     = 1;
			$slider_column_tablet   = 1;
			$slider_column_mobile   = 1;
			$template_slider_effect = 1;
		}

		?>
		<?php do_action( 'bdp_before_archive_post_content' ); ?>
	
	<div class="card-box-wrap ">
		<div class="card-box <?php echo $alter_class; ?>">	
			<div class="card-box-img">
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
					echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link ">' : '';
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
				} else {
					$thumbnail = Bdp_Posts::get_sample_image( $post->ID, 'leest' );
					echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
					echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
					echo ( $bdp_post_image_link ) ? '</a>' : '';
				}
			}

			?>
			</div>
			<div class="card-box-text">
				<?php
				if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] || 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
					?>

					<ul class="message_catagroys">

						<?php

						// Category and Tag.
						if ( in_array( $post_type, $bdp_all_post_type ) ) {
							$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
							$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );

							foreach ( $taxonomy_names as $taxonomy_single ) {
								$taxonomy = $taxonomy_single->name;
								$sep      = 1;

								if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
									$term_list = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );

									$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
									if ( isset( $taxonomy ) ) {
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											?>
											<li>
												<div class="categories <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
													<?php
													echo '<span>' . esc_attr( $taxonomy_single->label ) . '&nbsp;:&nbsp;</span>';
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
														$sep++;
													}
													?>
												</div>
											</li>
											<?php
										}
									}
								}
							}
						} else {
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
								?>
								<li>
									<div class="categories <?php echo ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
										<span class="mess_catory-icone">
											<i class="fas fa-folder"></i>
											<?php
											$categories_list = get_the_category_list( ', ' );
											$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
											if ( $categories_link ) {
												$categories_list = wp_strip_all_tags( $categories_list );
											}
											if ( $categories_list ) :
												echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
											endif;
											?>
										</span>
									</div>
								</li>
								<?php
							}
						}
						// Category and Tag.
						?>
						<?php
						if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) {
							if ( 1 == $bdp_settings['display_author'] ) {
								$author_link  = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
								$author_class = ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] && 'gallery' !== get_post_format( $post->ID ) ) ? 'class="post-video-format"' : '';
								?>
								<li>
									<div class="mess_catory">
										<span class="mess_catory-icone">
											<i class="fas fa-user"></i>
											<?php
											echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
											?>
										</span>
									</div>
								</li>
								<?php
							}
							if ( 1 == $bdp_settings['display_date'] ) {
								$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
								?>
								<li>
									<div class="mess_catory"> 
										<span class="mess_catory-icone">
											<i class="fas fa-calendar-alt"></i>
											<?php
											$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
											$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
											$ar_year     = get_the_time( 'Y' );
											$ar_month    = get_the_time( 'm' );
											$ar_day      = get_the_time( 'd' );

											echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
											echo esc_attr( $bdp_date );
											echo ( $date_link ) ? '</a>' : '';
											?>
										</span>
									</div>
								</li>
								<?php
							}
						}
						if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
							if ( 1 == $bdp_settings['display_comment_count'] ) {
								?>
								<li>
									<div class="mess_catory"> 
										<span class="mess_catory-icone">
											<i class="fas fa-comments"></i>
											<?php
											if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
												comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
											} else {
												comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
											}
											?>
										</span>
									</div>
								</li>
								<?php
							}

							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
								echo '<li><div class="mess_catory"><span class="mess_catory-icone">';
								echo do_shortcode( '[likebtn_shortcode]' );
								echo '</div></li>';
							}
						}
						if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
							$tags_list = get_the_tag_list( '', ', ' );
							$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
							if ( $tag_link ) {
								$tags_list = wp_strip_all_tags( $tags_list );
							}
							if ( $tags_list ) :
								?>
								<li>
									<div class="categories <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
										<span class="mess_catory-icone">
											<i class="fas fa-bookmark"></i>&nbsp;
											<?php
											echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
											?>
										</span>
									</div>
								</li>
								<?php
							endif;
						}
						?>
						 
				</ul>
					<?php
				}

				?>
				
				<h2 class="card-box-title leest-title">
					<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
					if ( 1 == $bdp_post_title_link ) {
						?>
						<a href="<?php esc_url( the_permalink() ); ?>">
						<?php } ?>
							<?php
								$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
								echo ( $total_noofline ) ? '<span>' : '';
								echo esc_html( get_the_title() );
								echo ( $total_noofline ) ? '</span>' : '';
							if ( 1 == $bdp_post_title_link ) {
								?>
						</a>
					<?php } ?>
					</a>
				</h2>
				<?php
				if ( 'product' === $post_type ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( 'download' === $post_type ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				?>
				<div class="card-box-discrition">
					<?php if ( 0 == $bdp_settings['rss_use_excerpt'] ) : ?>
						<div class="content_upper_div">
							<?php
							$content = apply_filters( 'the_content', get_the_content( $post->ID ) );
							$content = apply_filters( 'bdp_content_change', $content, $post->ID );
							echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
							?>
						</div>
						<?php
					else :
						$template_post_content_from = 'from_content';
						$readmoretxt                = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
						$post_link                  = get_permalink( $post->ID );
						$read_more_on               = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
						$read_more_link             = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						$link_behaviour             = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
						if ( 'new' == $link_behaviour ) {
							$link_behaviour = '_blank';
						} elseif ( 'self' == $link_behaviour ) {
							$link_behaviour = '_SELF';
						}
						if ( isset( $bdp_settings['template_post_content_from'] ) ) {
							$template_post_content_from = $bdp_settings['template_post_content_from'];
						}
						if ( 'from_excerpt' === $template_post_content_from ) {
							if ( '' != get_the_excerpt() ) {
								$bdp_excerpt_data = get_the_excerpt( get_the_ID() );
							} else {
								$excerpt          = get_the_content( $post->ID );
								$excerpt_length   = $bdp_settings['txtExcerptlength'];
								$text             = strip_shortcodes( $excerpt );
								$text             = apply_filters( 'the_content', $text );
								$text             = str_replace( ']]>', ']]&gt;', $text );
								$bdp_excerpt_data = wp_trim_words( $text, $excerpt_length, '' );
								$bdp_excerpt_data = apply_filters( 'bdp_excerpt_change', $bdp_excerpt_data, $post->ID );
							}
						} else {
							$excerpt          = get_the_content( $post->ID );
							$excerpt_length   = $bdp_settings['txtExcerptlength'];
							$text             = strip_shortcodes( $excerpt );
							$text             = apply_filters( 'the_content', $text );
							$text             = str_replace( ']]>', ']]&gt;', $text );
							$bdp_excerpt_data = wp_trim_words( $text, $excerpt_length, '' );
							$bdp_excerpt_data = apply_filters( 'bdp_excerpt_change', $bdp_excerpt_data, $post->ID );
						}
						if ( '' != $bdp_excerpt_data ) {
							?>
							<p><?php echo wp_kses( $bdp_excerpt_data, Bdp_Admin_Functions::args_kses() ); ?>
							<?php

							if ( 1 == $read_more_link && 0 != $bdp_settings['rss_use_excerpt'] && 1 == $read_more_on ) {
								echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
							}
							echo '</p>';
						}
					endif;
					?>
				
				</div>
				
				<?php
				if ( '' != get_the_content() ) {
					if ( 2 == $read_more_on && 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {
						   echo '<div class="card-box-link"><a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a></div>';
					}
				}
				?>
				<?php if ( $bdp_settings['social_share'] ) { ?>
				<ul class="social_media">
					<li class="bpd-social-fields-li"> 
						<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?> 
					</li>
				</ul>
				<?php } ?>
			</div>
		</div> 
	</div>
		<?php do_action( 'bdp_after_archive_post_content' ); ?>
		<?php
		do_action( 'bdp_separator_after_post' );

	}
}
