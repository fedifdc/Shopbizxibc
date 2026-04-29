<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/nicy.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_nicy_template', 10, 1 );
if ( ! function_exists( 'bdp_archive_nicy_template' ) ) {
	/**
	 * Add html for boxy template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_nicy_template( $bdp_settings ) {
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
		?>
		<div class="blog_template bdp_blog_template nicy">

			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="entry-container">
				<div class="blog_header">
					<div class="pull-left">
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
						$display_date          = $bdp_settings['display_date'];
						$display_author        = $bdp_settings['display_author'];
						$author_link           = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
						$date_format           = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
						$bdp_date              = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
						$display_comment_count = $bdp_settings['display_comment_count'];
						$ar_year               = get_the_time( 'Y' );
						$ar_month              = get_the_time( 'm' );
						$ar_day                = get_the_time( 'd' );
						if ( 1 == $display_date || 1 == $bdp_settings['display_postlike'] || 1 == $display_author || 1 == $display_comment_count ) {
							?>
							<div class="metadatabox">
								<?php
								if ( 1 == $display_author || 1 == $display_date ) {
									?>
									<div class="metadata">
									<?php
									if ( 1 == $display_author && 1 == $display_date ) {
										esc_html_e( 'Posted by', 'blog-designer-pro' );
										?>
											&nbsp;<span class="post_author <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
											<?php
											echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
											?>
											</span>
											<?php
											$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
											echo ' ';
											esc_html_e( 'on', 'blog-designer-pro' );
											echo ' ';
											echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
											echo esc_html( $bdp_date );
											echo ( $date_link ) ? '</a>' : '';
									} elseif ( 1 == $display_author ) {
										esc_html_e( 'Posted by', 'blog-designer-pro' );
										echo ' ';
										?>
										<span class="post_author <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
										<?php
										echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
										?>
										</span>
										<?php
									} elseif ( 1 == $display_date ) {
										$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
										esc_html_e( 'Posted on', 'blog-designer-pro' );
										echo ' ';
										echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
										echo esc_html( $bdp_date );
										echo ( $date_link ) ? '</a>' : '';
									}
									?>
										</div>
										<?php
								}
								if ( 1 == $display_comment_count ) {
									?>
									<div class="metacomments"><i class="fas fa-comment"></i>
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
									echo do_shortcode( '[likebtn_shortcode]' );
								}
								?>
							</div>
						<?php } ?>
					</div>
					<div class="blog-header-avatar">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
					</div>
				</div>
				<?php if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { ?>
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
					$post_thumbnail = 'full';
					$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
					if ( ! empty( $thumbnail ) ) {
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
						?>
						<div class="bdp-post-image">
							<?php
							echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
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
							?>
						</div>
						<?php
					}
				}
				if ( in_array( $post_type, $bdp_all_post_type ) ) {
					$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
					$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
					echo '<div class="post-meta-cats-tags">';
					foreach ( $taxonomy_names as $taxonomy_single ) {
						$taxonomy = $taxonomy_single->name;
						$sep      = 1;
						if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
							$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
							$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
							if ( isset( $taxonomy ) ) {
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									?>
									<div class="taxonomies <?php echo esc_attr( $taxonomy ); ?>">
										<span class="link-lable"> <?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
										<span class="category-link <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
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
					echo '</div>';
				} else {
					$tags_list       = get_the_tag_list( '', ', ' );
					$categories_list = get_the_category_list( ', ' );
					if ( ( 1 == $bdp_settings['display_category'] && $categories_list ) || ( 1 == $bdp_settings['display_tag'] && $tags_list ) ) {
						echo '<div class="post-meta-cats-tags">';
					}
					if ( 1 == $bdp_settings['display_category'] && $categories_list ) {
						$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
						?>
						<div class="category-link <?php echo ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
							<?php
							$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
							if ( '' != $label_featured_post && is_sticky() ) {
								?>
								<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
								<?php
							}
							?>
							<?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> : 
							<?php
							if ( $categories_link ) {
								$categories_list = wp_strip_all_tags( $categories_list );
							}
							if ( $categories_list ) :
								echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
								$show_sep = true;
								endif;
							?>
						</div>
						<?php
					}
					if ( 1 == $bdp_settings['display_tag'] && $tags_list ) {
						$tag_link = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
						if ( $tag_link ) {
							$tags_list = wp_strip_all_tags( $tags_list );
						}
						if ( $tags_list ) :
							?>
							<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
								<span class="link-lable"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
								<?php
								echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
								$show_sep = true;
								?>
							</div>
							<?php
						endif;
					}
					if ( ( 1 == $bdp_settings['display_category'] && $categories_list ) || ( 1 == $bdp_settings['display_tag'] && $tags_list ) ) {
						echo '</div>';
					}
				}
				if ( Bdp_Template_Acf::is_acf_plugin() ) {
					if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
						echo '<div class="bdp_acf_field">';
						do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
						echo '</div>';
					}
				}
				if ( 'product' === $post_type ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( 'download' === $post_type ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				?>
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
					endif;
					?>
				</div>
			</div>
			<div class="entry-meta clearfix">
				<div class="up_arrow"></div>
				<div class="pull-left">
					<?php
					if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link && 2 == $read_more_on ) :
						?>
						<div class="read-more">
							<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_html( $link_behaviour ); ?>">
								<?php echo esc_html( $readmoretxt ); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
				<div class="pull-right social-component-count-<?php echo esc_attr( $bdp_settings['social_count_position'] ); ?>">
					<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
				</div>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
	}
}
