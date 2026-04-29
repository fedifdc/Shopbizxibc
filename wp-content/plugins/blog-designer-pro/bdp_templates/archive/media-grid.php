<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/media-grid.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_media_grid_template', 10, 5 );
if ( ! function_exists( 'bdp_archive_media_grid_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter class.
	 * @param string $prev_year year.
	 * @param string $alter_val alter.
	 * @param string $paged page.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_media_grid_template( $bdp_settings, $alter, $prev_year, $alter_val, $paged ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}
		global $post, $wp_query;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$no_image_post     = '';
		if ( ! has_post_thumbnail() || empty( $bdp_settings['bdp_default_image_id'] ) ) {
			$no_image_post = 'no_image_post';
		}

		$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;

		if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
			$blog_unique_design = $bdp_settings['firstpost_unique_design'];
		} else {
			$blog_unique_design = 0;
		}
		$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0';
		$template_name     = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';

		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
		}
		if ( $column_setting >= 3 && 1 == $blog_unique_design && 1 == $alter_val && 0 == $prev_year && 1 == $paged ) {
			echo '<div class="media-grid-wrap first_post">';
		} elseif ( $column_setting <= 2 && 1 == $blog_unique_design && 1 == $alter_val && 0 == $prev_year && 1 == $paged ) {
			echo '<div class="media-grid-wrapp first_post">';
		} else {
			if ( 1 != $blog_unique_design && 1 == $alter_val ) {
				echo '<div class="media-grid-wrapper">';
			} elseif ( $paged > 1 && 1 == $alter_val ) {
				echo '<div class="media-grid-wrapper">';
			} elseif ( 1 == $blog_unique_design && 1 == $paged && 2 == $alter_val && $column_setting <= 2 ) {
				echo '<div class="media-grid-wrapper">';
			} elseif ( 1 == $blog_unique_design && 1 == $paged && 3 == $alter_val && $column_setting >= 2 ) {
				echo '<div class="media-grid-wrapper">';
			}
		}
		?>
		<div class="blog_template bdp_blog_template media-grid <?php echo esc_attr( get_post_format( $post->ID ) ); ?>">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="post-body-div 
			<?php
			echo esc_attr( $no_image_post );
			if ( ! in_array( $post_type, $bdp_all_post_type ) && 0 == $bdp_settings['display_category'] ) {
				echo ' category-not-visible';
			}
			?>
			">
				<div class="bdp-post-image">
					<?php
					$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
					if ( '' != $label_featured_post && is_sticky() ) {
						?>
						<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
						<?php
					}
					?>
					<?php
					if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
						echo '<div class="post-video bdp-video">';
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
						echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
						$post_thumbnail      = 'invert-grid-thumb';
						$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
						echo ( $bdp_post_image_link ) ? '</a>' : '';
						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
							echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
						}
						if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
							$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
							echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
							do_action( 'bdp_woocommerce_sale_tag' );
							echo '</div>';
						}
						echo '</figure>';
					}
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
								<span class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
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
										$sep++;
									}
									?>
								</span>
							<?php
						}
					} else {
						if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
							?>
							<?php
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
							if ( $categories_link ) {
								$categories_list = wp_strip_all_tags( $categories_list );
							}
							if ( $categories_list ) :
								echo '<span class="category-link">';
								echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
								$show_sep = true;
								echo '</span>';
							endif;
							?>
							<?php
						}
					}
					?>
				</div>
				<div class="content-container">
					<div class="shadow-box"></div>
					<div class="content-inner">
						<h2 class="post-title entry-title" >
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
							$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
							$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
							$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
							if ( 'new' == $link_behaviour ) {
								$link_behaviour = '_blank';
							} elseif ( 'self' == $link_behaviour ) {
								$link_behaviour = '_SELF';
							}
							if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) :
								$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
								$post_link   = get_permalink( $post->ID );
								if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
									$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
								}
								if ( 1 == $read_more_on ) {
									?>
									<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_html( $link_behaviour ); ?>">
										<?php echo esc_html( $readmoretxt ); ?>
									</a>
									<?php
								}
								?>
							<?php endif; ?>
						</div>
						<?php
						if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link && 2 == $read_more_on ) :
							?>
							<div class="read-more">
								<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_html( $link_behaviour ); ?>">
									<?php echo esc_html( $readmoretxt ); ?>
								</a>
							</div>
							<?php
						endif;
						?>

						<div class="metadatabox">
							<?php
							$display_author = $bdp_settings['display_author'];
							$display_date   = $bdp_settings['display_date'];
							if ( 1 == $display_author || 1 == $display_date ) {
								?>
								<span><?php esc_html_e( 'Posted', 'blog-designer-pro' ); ?>&nbsp;</span>
								<?php
							}
							if ( 1 == $display_author ) {
								?>
								<span class="post-author">
									<span><?php esc_html_e( 'by', 'blog-designer-pro' ); ?>&nbsp;</span>
									<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
								</span>
								<?php
							}
							if ( 1 == $display_date ) {
								$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
								$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
								$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );
								?>
								<span class="mdate"><span><?php esc_html_e( 'on', 'blog-designer-pro' ); ?></span>
									<?php
									echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
									echo esc_html( $bdp_date );
									echo ( $date_link ) ? '</a>' : '';
									?>
								</span>
								<?php
							}
							if ( 1 == $bdp_settings['display_comment_count'] ) {
								?>
								<span class="metacomments">
									<i class="fas fa-comments"></i>
									<?php
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
										comments_number( '0', '1', '%' );
									} else {
										comments_popup_link( '0', '1', '%' );
									}
									?>
								</span>
								<?php
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
								echo do_shortcode( '[likebtn_shortcode]' );
							}
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
												<div class="taxonomies <?php echo esc_attr( $taxonomy ); ?> <?php echo ( ! $taxonomy_link ) ? 'bdp_no_links' : ''; ?>">
													<div class="tags <?php echo esc_attr( $no_image_post ); ?>">
														<span class="link-lable"><i class="fas fa-tags"></i>&nbsp;<?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
															$sep++;
														}
														?>
													</div>
												</div>
												<?php
											}
										}
									}
								}
							} else {
								if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
									$tags_list = get_the_tag_list( '', ', ' );
									$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
									if ( $tag_link ) {
										$tags_list = wp_strip_all_tags( $tags_list );
									}
									if ( $tags_list ) :
										?>
										<div class="tags">
											<?php
											echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
											$show_sep = true;
											?>
										</div>
										<?php
									endif;
								}
							}
							if ( Bdp_Template_Acf::is_acf_plugin() ) {
								if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
									echo '<div class="bdp_acf_field">';
									do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
									echo '</div>';
								}
							}
							?>
						</div>
						<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
					</div>
				</div>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
		if ( $column_setting >= 3 && 1 == $blog_unique_design && 2 == $alter_val && 0 == $prev_year && 1 == $paged ) {
			echo '</div>';
		} elseif ( $column_setting <= 2 && 1 == $blog_unique_design && 1 == $alter_val && 0 == $prev_year && 1 == $paged ) {
			echo '</div>';
		} elseif ( 1 == $prev_year && $wp_query->post_count == $alter_val ) {
			echo '</div>';
		} elseif ( 1 != $blog_unique_design && $wp_query->post_count == $alter_val ) {
			echo '</div>';
		}
	}
}
