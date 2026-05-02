<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/timeline.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_timeline_template', 10, 2 );
if ( ! function_exists( 'bdp_archive_timeline_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter alter.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_timeline_template( $bdp_settings, $alter ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}
		global $post;
		$post_type         = get_post_type( $post->ID );
		$format            = get_post_format();
		$bdp_all_post_type = array( 'product', 'download' );
		$post_format       = '';
		if ( 'status' === $format ) {
			$post_format = 'fas fa-comment';
		} elseif ( 'aside' === $format ) {
			$post_format = 'fas fa-file-alt';
		} elseif ( 'image' === $format ) {
			$post_format = 'far fa-file-image';
		} elseif ( 'gallery' === $format ) {
			$post_format = 'far fa-file-image';

		} elseif ( 'link' === $format ) {
			$post_format = 'fas fa-link';
		} elseif ( 'quote' === $format ) {
			$post_format = 'fas fa-quote-left';
		} elseif ( 'audio' === $format ) {
			$post_format = 'fas fa-music';
		} elseif ( 'video' === $format ) {
			$post_format = 'fas fa-video';
		} elseif ( 'chat' === $format ) {
			$post_format = 'fab fa-weixin';
		} else {
			$post_format = 'fas fa-thumbtack';
		}
		$image_hover_effect = '';
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
				$style                 = 'style = "background-color: transparent; background-attachment: ' . $background_attachment . '; background-size: cover; background-image: url(' . $url . '); "';
			}
		}
		$timeline_design = ( isset( $bdp_settings['timeline_design'] ) && '' != $bdp_settings['timeline_design'] ) ? $bdp_settings['timeline_design'] : 'design1';
		?>
		<div class="blog_template bdp_blog_template timeline blog-wrap <?php echo esc_attr( $alter ); ?>">
			<?php
			do_action( 'bdp_before_post_content' );
			if ( 'design2' == $timeline_design ) {
				?>
				<div class="bdp-post-icon">
				<?php
				$display_date = $bdp_settings['display_date'];
				$date_link    = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
				$ar_year      = get_the_time( 'Y' );
				$ar_month     = get_the_time( 'F' );
				$ar_day       = get_the_time( 'd' );
				echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, get_the_time( 'm' ), $ar_day ) ) . '">' : '';
				?>
					<span class="month"><?php echo esc_attr( get_the_time( 'M' ) ); ?></span>
					<span class="day"><?php echo esc_attr( get_the_time( 'd' ) ); ?></span>
				<?php echo '</a>'; ?>
				</div>	
			<?php } ?>
			<div class="post_hentry  <?php echo esc_attr( $post_format ); ?>">
				<div class="post_content_wrap animateblock" <?php echo ( isset( $style ) && '' != $style ) ? esc_attr( $style ) : ''; ?>>
					<div class="post_wrapper box-blog">
						<?php
						$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
						if ( '' != $label_featured_post && is_sticky() ) {
							?>
							<div class="label_featured_post"><span><?php echo esc_html( $label_featured_post ); ?></span></div> 
							<?php
						}
						?>
						<?php
						$show_fearue_image = 1;
						if ( isset( $bdp_settings['blog_background_image'] ) && 1 == $bdp_settings['blog_background_image'] ) {
							$show_fearue_image = 0;
						}
						if ( 1 == $show_fearue_image ) {
							if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
								?>
								<div class="bdp-post-image post-video bdp-video">
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
									?>
									<div class="photo bdp-post-image">
										<?php
										echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
										echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
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
										?>
									</div>
									<?php
								} else {
									$display_date = $bdp_settings['display_date'];
									if ( 1 == $display_date ) {
										?>
										<div class="no_post_media">
										</div>
										<?php
									}
								}
							}
						} else {
							$display_date = $bdp_settings['display_date'];
							if ( 1 == $display_date ) {
								?>
								<div class="no_post_media">
								</div>
								<?php
							}
						}
						?>
						<div class="desc">
							<?php
							if ( 'design2' == $timeline_design ) {
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
											if ( isset( $taxonomy ) ) {
												if ( isset( $term_list ) && ! empty( $term_list ) ) {
													?>
													<span class="categories category-link <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
														<span class="link-lable">  
														<?php
														if ( in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
															?>
															<i class="fas fa-folder"></i> 
															<?php
														}
														?>
														&nbsp;<?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
													</span>
													<?php
												}
											}
										}
									}
								} else {
									if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
										$categories_list = get_the_category_list( ', ' );
										$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
										?>
										<span class="categories <?php echo ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
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
									}
								}
							}
							?>
							<h3 class="entry-title">
								<?php
								$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
								if ( 1 == $bdp_post_title_link ) {
									?>
									<a href="<?php the_permalink(); ?>">
									<?php } ?>
									<?php
									$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
									echo ( $total_noofline ) ? '<span>' : '';
									the_title();
									echo ( $total_noofline ) ? '</span>' : '';
									if ( 1 == $bdp_post_title_link ) {
										?>
									</a>
								<?php } ?>
							</h3>
							<?php
							if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] || 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) {
								?>
								<div class="date_wrap">
									<?php
									$display_author = $bdp_settings['display_author'];
									if ( 1 == $display_author ) {
										$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
										?>
										<span class="posted_by <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
											<i class="fas fa-user"></i>
											<span> <?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?> </span>
										</span>
										<?php
									}
									$display_date = $bdp_settings['display_date'];
									$ar_year      = get_the_time( 'Y' );
									$ar_month     = get_the_time( 'F' );
									$ar_day       = get_the_time( 'd' );
									if ( 1 == $display_date ) {
										$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
										?>
										<div class="datetime">
											<?php if ( 'design1' == $timeline_design ) { ?>
												<?php echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, get_the_time( 'm' ), $ar_day ) ) . '">' : ''; ?>
													<span class="month"><?php echo esc_attr( get_the_time( 'M' ) ); ?></span>
													<span class="day"><?php echo esc_attr( get_the_time( 'd' ) ); ?></span>
												<?php
												echo '</a>';
											}
											?>
										</div>
										<?php
									}
									if ( 1 == $bdp_settings['display_comment_count'] ) {
										?>
										<span class="post-comment">
											<i class="fas fa-comment"></i>
											<?php
											if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
												comments_number( __( 'No Comments', 'blog-designer-pro' ), '1 ' . __( 'comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ) );
											} else {
												comments_popup_link( __( 'Leave a Comment', 'blog-designer-pro' ), __( '1 comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ), 'comments-link', __( 'Comments are off', 'blog-designer-pro' ) );
											}
											?>
										</span>
										<?php
									}

									if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
										echo do_shortcode( '[likebtn_shortcode]' );
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
							<?php
							if ( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ) ) {
								?>
								<div class="post_content">
									<?php
									echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ), Bdp_Admin_Functions::args_kses() );
									?>
									<?php
									$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
									$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
									$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
									if ( 'new' == $link_behaviour ) {
										$link_behaviour = '_blank';
									} elseif ( 'self' == $link_behaviour ) {
										$link_behaviour = '_SELF';
									}
									if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) :
										$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : __( 'Read More', 'blog-designer-pro' );
										$post_link   = get_permalink( $post->ID );
										if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
											$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
										}
										if ( 1 == $read_more_on ) {
											echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
										}
										?>
									<?php endif; ?>
								</div>
									<?php if ( 2 == $read_more_on && 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) { ?>
									<div class="read_more">
										<?php
										if ( 'design2' == $timeline_design ) {
											echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
										} else {
											echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '"><i class="fas fa-plus"></i> ' . esc_html( $readmoretxt ) . ' </a>';
										}
										?>
									</div>
										<?php
									}
							}
							?>
						</div>
					</div>
					<?php
					$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true;
					?>
						<footer class="blog_footer text-capitalize">
						<?php
						if ( 'design1' == $timeline_design ) {
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
										if ( isset( $taxonomy ) ) {
											if ( isset( $term_list ) && ! empty( $term_list ) ) {
												?>
												<span class="categories category-link <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
													<span class="link-lable">  
													<?php
													if ( in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
														?>
														<i class="fas fa-folder"></i> 
														<?php
													} else {
														?>
														<i class="fas fa-bookmark"></i> <?php } ?>&nbsp;<?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
												</span>
												<?php
											}
										}
									}
								}
							} else {
								if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
									$categories_list = get_the_category_list( ', ' );
									$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
									?>
									<span class="categories <?php echo ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
										<span class="link-lable"> <i class="fas fa-folder"></i> <?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?>:&nbsp; </span>
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
								}
								if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
									$tags_list = get_the_tag_list( '', ', ' );
									$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
									if ( $tag_link ) {
										$tags_list = wp_strip_all_tags( $tags_list );
									}
									if ( $tags_list ) :
										?>
										<span class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
											<?php if ( 'design2' == $timeline_design ) { ?>
												<span class="link-lable"> <i class="fas fa-tag"></i> </span>
											<?php } else { ?>
												<span class="link-lable"> <i class="fas fa-bookmark"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>:&nbsp; </span>
											<?php } ?>
											<?php
											echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
											$show_sep = true;
											?>
										</span>
										<?php
									endif;
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
						if ( $social_share && ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) ) {
								Bdp_Utility::get_social_icons( $bdp_settings );
						}
						?>
						</footer>
				   
				</div>
			</div>
			<?php do_action( 'bdp_after_post_content' ); ?>
		</div>
		<?php
		do_action( 'bdp_separator_after_post' );
	}
}
