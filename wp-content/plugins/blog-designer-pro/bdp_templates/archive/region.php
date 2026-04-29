<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/region.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_region_template', 10, 2 );
if ( ! function_exists( 'bdp_archive_region_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alterclass class.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_region_template( $bdp_settings, $alterclass ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$class_name        = 'blog_template bdp_blog_template region';
		if ( '' != $alterclass ) {
			$class_name .= ' ' . $alterclass;
		}
		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
		}
		?>
		<div class="<?php echo esc_attr( $class_name ); ?>">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<?php
			$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
			if ( '' != $label_featured_post && is_sticky() ) {
				?>
				<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
				<?php
			}
			?>
			<div class="blog_header">
				<div class="metadatabox">
				<?php
					$display_date   = $bdp_settings['display_date'];
					$display_author = $bdp_settings['display_author'];
					$author_link    = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
					$date_format    = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
					$bdp_date       = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
					$ar_year        = get_the_time( 'Y' );
					$ar_month       = get_the_time( 'm' );
					$ar_day         = get_the_time( 'd' );
				if ( 1 == $bdp_settings['display_comment_count'] ) {
					?>
						<div class="comment_wrapper">
							<div class="metacomments">
								<span class="article_comments">
								<?php
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
									comments_number( esc_html__( 'No Comments', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
								} else {
									comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
								}
								?>
								</span>
							</div>
						</div>
						<?php
				}
				if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
					echo do_shortcode( '[likebtn_shortcode]' );
				}
				?>
					<div class="posted_by">
					<?php
					if ( 1 == $display_author && 1 == $display_date ) {
						if ( ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ) {
							esc_html_e( 'Last updated', 'blog-designer-pro' );
						} else {
							esc_html_e( 'Published on', 'blog-designer-pro' );
						}
						?>
							&nbsp;:&nbsp;
							<time datetime="" class="datetime">
							<?php
							$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
							echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							?>
							</time>

							<span class="author-meta <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
								<span class="link-lable"> | <?php esc_html_e( 'By', 'blog-designer-pro' ); ?> </span>
								<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
							</span>
							<?php
					} elseif ( 1 == $display_author ) {
						?>
							<span class="author-meta <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
								<span class="link-lable"> <?php esc_html_e( 'By', 'blog-designer-pro' ); ?> </span>
								<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
							</span>
							<?php
					} elseif ( 1 == $display_date ) {
						if ( ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ) {
							esc_html_e( 'Last updated', 'blog-designer-pro' );
						} else {
							esc_html_e( 'Published on', 'blog-designer-pro' );
						}
						$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
						?>
							&nbsp;:&nbsp;
							<time datetime="" class="datetime">
							<?php
							echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							?>
							</time>
							<?php
					}
					?>
					</div>
				</div>
				<h2 class="post-title">
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
					</a>
				</h2>
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
							$bdp_exclude_taxonomy = array( 'product_tag', 'download_tag' );
							if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									?>
									<div class="taxonomy_region">
										<span class="category-link <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
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
												echo esc_html( $term_nm->name );
												echo ( $taxonomy_link ) ? '</a>' : '';
												$sep++;
											}
											?>
										</span>
									</div>
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
						<span class="category-link <?php echo ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
							<?php
							if ( $categories_link ) {
								$categories_list = wp_strip_all_tags( $categories_list );
							}
							if ( $categories_list ) :
								?>
								<span class="link-lable"><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span> 
								<?php
								echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
							endif;
							?>
						</span>
						<?php
					}
				}
				?>
			</div>

			<?php if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { ?>
				<div class="post-video">
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
					<div class="bdp-post-image">
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
				}
			}
			if ( 'product' === $post_type ) {
				do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
			}
			if ( 'download' === $post_type ) {
				do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
			}
			?>
			<div class="post_content_wrap">
				<?php if ( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ) ) { ?>
					<div class="post_content">
						<?php echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ), Bdp_Admin_Functions::args_kses() ); ?>
						<?php
						$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
						$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
						if ( 'new' == $link_behaviour ) {
							$link_behaviour = '_blank';
						} elseif ( 'self' == $link_behaviour ) {
							$link_behaviour = '_SELF';
						}
						if ( 0 != $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) :
							$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
							$post_link   = get_permalink( $post->ID );
							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
							}
							if ( 1 == $read_more_on ) {
								echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
							}

						endif;
						?>
					</div>
				<?php } ?>
				<?php
				if ( 0 != $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link && 2 == $read_more_on ) {
					echo '<div class="read-more-div"><a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a></div>';
				}
				?>
			</div>
			<div class="blog_footer">
				<?php
				if ( in_array( $post_type, $bdp_all_post_type ) ) {
					$bdp_tax_tag = '';
					if ( 'product' === $post_type ) {
						$bdp_tax_tag = 'product_tag';
					} elseif ( 'download' === $post_type ) {
						$bdp_tax_tag = 'download_tag';
					}
					if ( '' != $bdp_tax_tag && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_tag ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_tag ] ) {
						$tag_link     = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_tag ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_tag ] ) ? false : true;
						$product_tags = wp_get_post_terms( $post->ID, $bdp_tax_tag, array( 'hide_empty' => 'false' ) );
						$sep          = 1;
						?>
							<div class="post-category">
								<span class="category-link<?php echo ( $tag_link ) ? ' tag_link' : ''; ?>">
									<span class="link-lable"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
									<?php
									foreach ( $product_tags as $category ) {
										if ( 1 != $sep ) {
											?>
											<span class="seperater"><?php echo ', '; ?></span>
											<?php
										}
										echo ( $tag_link ) ? '<a href="' . esc_url( get_term_link( $category->term_id ) ) . '">' : '';
										echo esc_html( $category->name );
										echo ( $tag_link ) ? '</a>' : '';
										$sep++;
									}
									?>
								</span>
							</div>
							<?php
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
							<div class="footer_meta">
								<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
									<span class="link-lable"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
									<?php echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() ); ?>
								</div>
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
				Bdp_Utility::get_social_icons( $bdp_settings );
				?>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
		do_action( 'bdp_archive_separator_after_post' );
	}
}
