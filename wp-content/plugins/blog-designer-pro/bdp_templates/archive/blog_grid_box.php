<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/blog_grid_box.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_blog_grid_box_template', 10, 1 );

if ( ! function_exists( 'bdp_archive_blog_grid_box_template' ) ) {
	/**
	 * Add html for blog_grid_box template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_blog_grid_box_template( $bdp_settings ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}

		global $post;
		$post_type          = get_post_type( $post->ID );
		$bdp_all_post_type  = array( 'product', 'download' );
		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
		}
		$has_thumbnail = false;
		?>
		<div class="bdp_blog_template blog_grid_box-post post-body-div post-body-div-right">
			<?php
			$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
			if ( '' != $label_featured_post && is_sticky() ) {
				?>
				<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
				<?php
			}
			?>
			<div class="post-body-div-inner">
				<?php
				if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
					add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );
				}
				do_action( 'bdp_before_archive_post_content' );
				if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
					$post_formate = array( 'gallery', 'video', 'audio' );
					if ( ! in_array( get_post_format(), $post_formate ) ) {
						$has_thumbnail = true;
					}
					$class = ( 'video' === get_post_format() ) ? 'blog_grid_box-img-wrapper post-video bdp-video' : 'blog_grid_box-img-wrapper bdp-post-image post-video';
					echo '<div class="' . esc_attr( $class ) . '">';
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
					echo '</div>';
				} else {
					$post_thumbnail      = 'blog_grid_box_thumb';
					$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
					$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
					$image_class         = ( isset( $bdp_settings['thumbnail_skin'] ) && 1 == $bdp_settings['thumbnail_skin'] ) ? 'circle' : '';
					if ( ! empty( $thumbnail ) ) {
						echo '<div class="blog_grid_box-img-wrapper bdp-post-image">';
						echo '<figure class="' . esc_attr( $image_hover_effect ) . ' ' . esc_attr( $image_class ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
						echo ( $bdp_post_image_link ) ? '</a>' : '';

						if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
							$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
							echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
							do_action( 'bdp_woocommerce_sale_tag' );
							echo '</div>';
						}
						echo '</figure>';
						echo '</div>';
					} else {
						$has_thumbnail = true;
					}
				}
				?>
				<div class="bdp_post_content <?php echo esc_attr( empty( $thumbnail ) ) ? 'no-thumbnail' : ''; ?>">
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
								<div class="bdp-post-categories<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
								<?php
								foreach ( $product_categories as $category ) {
									if ( 1 != $sep ) {
										?>
											<span class="seperater"><?php echo ', '; ?></span>
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
							$categories_list = wp_strip_all_tags( $categories_list );
						}
						?>
							<div class="bdp-post-categories <?php echo ( $categories_link ) ? 'bdp-no-links' : ''; ?>">
								<?php
								if ( $categories_list ) :
									echo wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
									$show_sep = true;
								endif;
								?>
							</div>
							<?php

					}
					?>
					<h2 class="bdp_post_title">
						<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
						if ( 1 == $bdp_post_title_link ) {
							echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
						}
						$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
						echo ( $total_noofline ) ? '<span>' : '';
						echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
						echo ( $total_noofline ) ? '</span>' : '';
						if ( 1 == $bdp_post_title_link ) {
							echo '</a>';
						}
						?>
					</h2>
					<?php
					$display_author        = $bdp_settings['display_author'];
					$display_date          = $bdp_settings['display_date'];
					$display_postlike      = $bdp_settings['display_postlike'];
					$display_comment_count = $bdp_settings['display_comment_count'];

					if ( 1 == $display_author || 1 == $display_date || 1 == $display_comment_count || 1 == $display_postlike ) {
						echo '<div class="bdp-post-meta">';
						if ( 1 == $display_author ) {
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
							?>
							<span class="author <?php echo ( ! $author_link ) ? 'bdp-no-links' : ''; ?>">
								<?php
								echo ( $author_link ) ? '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' : '';
								echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
								echo ( $author_link ) ? '</a>' : '';
								?>
							</span>
							<?php
						}
						echo esc_html__( ' / ', 'blog-designer-pro' );
						if ( 1 == $display_date ) {
							$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );
							$class       = ( $date_link ) ? 'date-meta bdp-no-links' : 'date-meta';
							echo '<span class="' . esc_attr( $class ) . '">';
							echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							echo '</span>';
						}

						if ( 1 == $display_postlike ) {
							echo '<span class="postlike_btn">';
							echo do_shortcode( '[likebtn_shortcode]' );
							echo '</span>';
						}

						if ( 1 == $display_comment_count ) {
							$disable_link_comment = ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) ? true : false;
							?>
							<span class="comment <?php echo ( $disable_link_comment ) ? 'bdp-no-links' : ''; ?>">
								<i class="fas fa-comment"></i>
								<?php
								if ( $disable_link_comment ) {
									comments_number( esc_html__( 'No Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
								} else {
									comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
								}
								?>
							</span>
							<?php
						}
						echo '</div>';
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
											<div class="bdp-blog_grid_box-tag <?php echo ( $taxonomy_link ) ? 'bdp-has-links' : 'bdp-no-links'; ?>">
												<span class="link-lable"><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
												<?php
												foreach ( $term_list as $term_nm ) {
													$term_link = get_term_link( $term_nm );
													if ( 1 != $sep ) {
														echo ', ';
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
							if ( $tags_list ) {
								$class = ( $tag_link ) ? 'bdp-no-links' : '';
								echo '<div class="bdp-blog_grid_box-tag ' . esc_attr( $class ) . '">';
								?>
									<span class="link-lable"> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>:&nbsp; </span> 
									<?php
									echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
									echo '</div>';
							}
						}
					}
					?>
					<?php
					if ( 'product' === $post_type ) {
						do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
					}
					if ( 'download' === $post_type ) {
						do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
					}
					?>
					<div class="post_content">
						<?php
						echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ), Bdp_Admin_Functions::args_kses() );
						$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
						$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
						if ( 'new' == $link_behaviour ) {
							$link_behaviour = '_blank';
						} elseif ( 'self' == $link_behaviour ) {
							$link_behaviour = '_SELF';
						}
						if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {
							$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
							$post_link   = get_permalink( $post->ID );
							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
							}
							if ( 2 == $read_more_on ) {
								echo '<div class="read-more-div">';
							}
							echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
							if ( 2 == $read_more_on ) {
								echo '</div>';
							}
						}
						?>
					</div>
					<?php
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					?>
					<?php
					Bdp_Utility::get_social_icons( $bdp_settings );
					?>
				</div>
				<?php do_action( 'bdp_after_archive_post_content' ); ?>
			</div>
		</div>
		<?php
	}
}
