<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/tabbed.php.
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

add_action( 'bd_tabbed_archive_design_format_function', 'bdp_archive_tabbed_template', 10, 2 );
if ( is_admin() ) {
	$settings = isset( $_POST['settings'] ) && wp_verify_nonce( sanitize_key( $_POST['settings'] ) ) ? sanitize_text_field( wp_unslash( $_POST['settings'] ) ) : '';
	parse_str( $settings, $bdp_settings );
}
if ( ! function_exists( 'bdp_archive_tabbed_template' ) ) {
	/**
	 * Add html for tabbed template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class Alter Class.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_tabbed_template( $bdp_settings, $alter_class ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}

		global $post;
		$image_hover_effect = '';
		$post_type          = get_post_type( $post->ID );
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
		}
		if ( isset( $bdp_settings['blog_background_image'] ) && 1 == $bdp_settings['blog_background_image'] ) {
			if ( has_post_thumbnail() ) {
				$url = wp_get_attachment_url( get_post_thumbnail_id() );
			} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) {
				$url = wp_get_attachment_url( $bdp_settings['bdp_default_image_id'] );
			} else {
				$url = '';
			}
			if ( '' != $url ) {
				$background_attachment = ( isset( $bdp_settings['blog_background_image_style'] ) && 1 == $bdp_settings['blog_background_image_style'] ) ? 'fixed' : 'scroll';
				$style                 = 'style = "background-color: transparent; background-attachment: ' . $background_attachment . '; background-size: cover; background-image: url(' . esc_attr( $url ) . '); "';
			}
		}
		$bdp_all_post_type = array( 'product', 'download' );
		$tabbed_post_style = 0;
		?>
		<div class="blog_template bdp_blog_template bdp_post_content_tabbed blog-wrap bdp_blog_single_post_wrapp">
			<?php do_action( 'bdp_before_post_content' ); ?>
				<div class="bdp-post-inner-content">
					<div class="bdp-img">
						<?php
						$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
						if ( '' != $label_featured_post && is_sticky() ) {
							?>
							<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
							<?php
						}
						if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) ) {
							?>
							<div class="bdp-post-image post-video">
								<?php
								if ( 'quote' === get_post_format() ) {
									if ( has_post_thumbnail() ) {
										$post_thumbnail = 'full';
										if ( 1 == $tabbed_post_style ) {
											$post_thumbnail = 'tabbed_thumb';
										}
										$thumbnail = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
										if ( 1 == $tabbed_post_style ) {
											$post_thumbnail = 'tabbed_thumb';
											$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
										}
										echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
										echo '<div class="upper_image_wrapper">';
										echo wp_kses( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
										echo '</div>';
									}
								} elseif ( 'link' === get_post_format() ) {
									if ( has_post_thumbnail() ) {
										$post_thumbnail = 'full';
										if ( 1 == $tabbed_post_style ) {
											$post_thumbnail = 'tabbed_thumb';
										}
										$thumbnail = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
										if ( 1 == $tabbed_post_style ) {
											$post_thumbnail = 'tabbed_thumb';
											$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
										}
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
							?>
							<div class="bdp-post-image">
							<?php
								$post_thumbnail = 'brit_co_img';
								$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
							if ( 1 == $tabbed_post_style ) {
								$post_thumbnail = 'tabbed_thumb';
								$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
							}
								$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
							if ( ! empty( $thumbnail ) ) {
								echo '<figure class="bdp-mb-15 ' . esc_attr( $image_hover_effect ) . '">';
								echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
								echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
								echo ( $bdp_post_image_link ) ? '</a>' : '';

								if ( 0 == $tabbed_post_style ) {
									if ( 0 == $tabbed_post_style && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] && isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] ) {
										echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
									}
									if ( class_exists( 'woocommerce' ) && 'product' === $post_type ) {
										if ( isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
											$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
											echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
											do_action( 'bdp_woocommerce_sale_tag' );
											echo '</div>';
										}
									}
								}

								echo '</figure>';
							}
							?>
							</div>
						<?php } ?>
					</div>
					<?php
						echo '<div class="post-inner-right">';
					if ( 0 == $tabbed_post_style ) {
						echo '<h2 class="post-title bdp-mb-15">';
					} else {
						echo '<h3 class="post-title bdp-mb-15">';
					}
							$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
					if ( 1 == $bdp_post_title_link ) {
						?>
						<a href="<?php esc_url( the_permalink() ); ?>">
						<?php
					}
					$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
					echo ( $total_noofline ) ? '<span>' : '';
					the_title();
					echo ( $total_noofline ) ? '</span>' : '';
					if ( 1 == $bdp_post_title_link ) {
						?>
						</a>
						<?php
					}
					if ( 0 == $tabbed_post_style ) {
						echo '</h2>';
					} else {
						echo '</h3>';
					}

					?>
						<div class="bdp-tabbed-meta-content">
							<?php
							$display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
							$display_date   = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1;
							if ( 1 == $display_author ) {
								echo '<span class="author">' . esc_html__( 'By', 'blog-designer-pro' ) . '&nbsp;' . wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ) . '</span>';
							}
							if ( 1 == $display_author && 1 == $display_date ) {
								echo '<span class="bdp-separator">  | </span>';
							}
							if ( 1 == $display_date ) {
								$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
								$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
								$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );
								$date_class  = ( $date_link ) ? 'bdp_has_links' : 'bdp_no_links';
								echo '<span class="bdp-post-date post-date ' . esc_attr( $date_class ) . '">';
								echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
								echo esc_attr( $bdp_date );
								echo ( $date_link ) ? '</a>' : '';
								echo '</span>';
							}

							if ( 'post' === $post_type ) {

								if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
									if ( 1 == $bdp_settings['display_date'] && 1 == $bdp_settings['display_category'] ) {
										echo '<span class="bdp-separator"> | </span>';
									}
									$categories_list = get_the_category_list( ', ' );
									$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
									?>
									<span class="post-category bdp-mb-15 <?php echo ( $categories_link ) ? 'bdp-no-link' : 'bdp-has-links'; ?>">
										<?php
										if ( $categories_link ) {
											$categories_list = wp_strip_all_tags( $categories_list );
										}
										if ( $categories_list ) :
											echo wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
											$show_sep = true;
										endif;
										?>
									</span>
									<?php
									if ( 1 == $bdp_settings['display_category'] && 1 == $bdp_settings['display_comment_count'] ) {
										echo '<span class="bdp-separator"> | </span>';
									}
								}
							} elseif ( isset( $post_type ) && in_array( $post_type, $bdp_all_post_type ) ) {
								$bdp_tax_cat = '';
								if ( 'product' === $post_type ) {
									$bdp_tax_cat = 'product_cat';
								} elseif ( 'download' === $post_type ) {
									$bdp_tax_cat = 'download_category';
								}
								if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) {
									if ( 1 == $bdp_settings['display_date'] && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) {
										echo '<span class="bdp-separator">  | </span>';
									}
									$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true;
									$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
									$sep                = 1;
									?>
										<span class="post-category bdp-mb-15 <?php echo ( $categories_link ) ? ' bdp-no-link' : 'bdp-has-links'; ?>">
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
										</span>
									<?php
									if ( 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] && 1 == $bdp_settings['display_comment_count'] ) {
										echo '<span class="bdp-separator"> | </span>';
									}
								}
							}

							$display_comment_count = isset( $bdp_settings['display_comment_count'] ) ? $bdp_settings['display_comment_count'] : '';
							if ( 1 == $display_comment_count ) {
								if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
									?>
									<span class="comment">
										<?php
										if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
											comments_number( '0', '1', '%' );

										} else {
											// echo "&nbsp";.
											comments_popup_link( '0', '1', '%' );
										}
										?>
										<i class="fas fa-comments"></i>
									</span>
									<?php
								endif;
								$display_postlike = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : 1;
								if ( 1 == $display_comment_count && 1 == $display_postlike ) {
									echo '<span class="bdp-separator"> | </span>';
								}
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
								echo do_shortcode( '[likebtn_shortcode]' );
							}
							?>
						</div>
						<?php

						if ( 0 == $tabbed_post_style ) {
							if ( isset( $post_type ) && 'product' === $post_type ) {
								do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
							}
							if ( isset( $post_type ) && 'download' === $post_type ) {
								do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
							}
							?>
							<div class="bdp-post-content">
								<?php
								$rss_use_excerpt    = isset( $bdp_settings['rss_use_excerpt'] ) ? $bdp_settings['rss_use_excerpt'] : 1;
								$txt_excerpt_length = isset( $bdp_settings['txtExcerptlength'] ) ? $bdp_settings['txtExcerptlength'] : 50;
								$txt_read_more_text = isset( $bdp_settings['txtReadmoretext'] ) ? $bdp_settings['txtReadmoretext'] : 'Read More';
								echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $rss_use_excerpt, $txt_excerpt_length ), Bdp_Admin_Functions::args_kses() );
								$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
								$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 1;
								$readmoretxt    = '' != $txt_read_more_text ? $txt_read_more_text : esc_html__( 'Read More', 'blog-designer-pro' );
								$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
								if ( 'new' == $link_behaviour ) {
									$link_behaviour = '_blank';
								} elseif ( 'self' == $link_behaviour ) {
									$link_behaviour = '_SELF';
								}

								$rss_use_excerpt = isset( $bdp_settings['rss_use_excerpt'] ) ? $bdp_settings['rss_use_excerpt'] : 1;
								if ( 1 == $read_more_link && 1 == $rss_use_excerpt ) {
									$post_link = get_permalink( $post->ID );
									if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
										$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
									}
									if ( 1 == $read_more_on ) {
										echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
									}
								}
								if ( 1 == $read_more_link && 1 == $rss_use_excerpt && 2 == $read_more_on ) {
									$post_link = get_permalink( $post->ID );
									$post_link = get_permalink( $post->ID );
									if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
										$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
									}
									?>
									<div class="read-more bdp-mb-15">
										<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>'; ?>
									</div>
									<?php
								}
								?>
							</div>
							<?php
							if ( 'post' === $post_type ) {
								if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
									$tags_list = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? get_the_tag_list( '', ', ' ) : get_the_tag_list( '', ', ' );
									$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
									if ( $tag_link ) {
										$tags_list = wp_strip_all_tags( $tags_list );
									}
									if ( $tags_list ) :
										?>
										<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
											<?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>&nbsp;:&nbsp;
											<?php
											echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
											$show_sep = true;
											?>
										</div>
										<?php
									endif;
								}
							} else {
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
												<div class="tags taxonomies <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
													<span class="link-lable"><?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
													<?php
													foreach ( $term_list as $term_nm ) {
														$term_link = get_term_link( $term_nm );

														if ( 1 != $sep ) {
															?>
															<span class="seperater"><?php echo ', '; ?></span>
															<?php
														}
														echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
														echo esc_attr( $term_nm->name );
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
							}
							if ( Bdp_Template_Acf::is_acf_plugin() ) {
								if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
									echo '<div class="bdp_acf_field">';
									do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
									echo '</div>';
								}
							}
							if ( isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
								?>
							<div class="social-share">
								<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
							</div>
								<?php
							}
							?>
						<?php } ?>
					<?php
						echo '</div>';
					?>
				</div>
			<?php do_action( 'bdp_after_post_content' ); ?>
		</div>
		<?php
	}
}
