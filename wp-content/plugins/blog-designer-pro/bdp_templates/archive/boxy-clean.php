<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/boxy-clean.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_boxyclean_template', 10, 5 );
if ( ! function_exists( 'bdp_archive_boxyclean_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class class.
	 * @param string $prev_year year.
	 * @param string $alter_val alter.
	 * @param string $paged paged.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_boxyclean_template( $bdp_settings, $alter_class, $prev_year, $alter_val, $paged ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

		}
		global $post;
		$bdp_all_post_type  = array( 'product', 'download' );
		$post_type          = get_post_type( $post->ID );
		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
		}
		$total_col        = isset( $bdp_settings['template_columns'] ) && $bdp_settings['template_columns'] > 0 ? $bdp_settings['template_columns'] : 3;
		$total_col_ipad   = isset( $bdp_settings['template_columns_ipad'] ) && $bdp_settings['template_columns_ipad'] > 0 ? $bdp_settings['template_columns_ipad'] : 2;
		$total_col_tablet = isset( $bdp_settings['template_columns_tablet'] ) && $bdp_settings['template_columns_tablet'] > 0 ? $bdp_settings['template_columns_tablet'] : 1;
		$total_col_mobile = isset( $bdp_settings['template_columns_mobile'] ) && $bdp_settings['template_columns_mobile'] > 0 ? $bdp_settings['template_columns_mobile'] : 1;
		$col_class        = Bdp_Template::column_class( $bdp_settings );
		if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
			$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
		} else {
			$firstpost_unique_design = 0;
		}
		if ( 1 == $firstpost_unique_design ) {
			if ( 0 == $prev_year && 1 == $alter_val && 1 == $paged ) {
				$col_class     .= ' first_post';
				$post_thumbnail = 'full';
				$col_class     .= ' bb bt';
			} elseif ( 1 == $prev_year && 1 != $alter_val && 1 == $paged ) {
				if ( 0 != ( $alter_val - 1 ) % $total_col ) {
					$col_class .= ' br_desktop';
				}
				if ( 0 != ( $alter_val - 1 ) % $total_col_ipad ) {
					$col_class .= ' br_ipad';
				}
				if ( 0 != ( $alter_val - 1 ) % $total_col_tablet ) {
					$col_class .= ' br_tablet';
				}
				if ( 0 != ( $alter_val - 1 ) % $total_col_mobile ) {
					$col_class .= ' br_mobile';
				}
				$col_class .= ' bb';
				if ( ( $alter_val - 1 ) <= $total_col ) {
					$col_class .= ' bt_desktop';
				}
				if ( ( $alter_val - 1 ) <= $total_col_ipad ) {
					$col_class .= ' bt_ipad';
				}
				if ( ( $alter_val - 1 ) <= $total_col_tablet ) {
					$col_class .= ' bt_tablet';
				}
				if ( ( $alter_val - 1 ) <= $total_col_mobile ) {
					$col_class .= ' bt_mobile';
				}
				$post_thumbnail = 'invert-grid-thumb';
			} elseif ( 1 == $prev_year && 1 != $paged ) {
				if ( 0 != ( $alter_val ) % $total_col ) {
					$col_class .= ' br_desktop';
				}
				if ( 0 != ( $alter_val ) % $total_col_ipad ) {
					$col_class .= ' br_ipad';
				}
				if ( 0 != ( $alter_val ) % $total_col_tablet ) {
					$col_class .= ' br_tablet';
				}
				if ( 0 != ( $alter_val ) % $total_col_mobile ) {
					$col_class .= ' br_mobile';
				}
				$col_class .= ' bb';
				if ( ( $alter_val ) <= $total_col ) {
					$col_class .= ' bt_desktop';
				}
				if ( ( $alter_val ) <= $total_col_ipad ) {
					$col_class .= ' bt_ipad';
				}
				if ( ( $alter_val ) <= $total_col_tablet ) {
					$col_class .= ' bt_tablet';
				}
				if ( ( $alter_val ) <= $total_col_mobile ) {
					$col_class .= ' bt_mobile';
				}
				$post_thumbnail = 'invert-grid-thumb';
			}
		} else { // $alter_class % $total_col != 0.
			if ( 0 != $alter_class % $total_col ) {
				$col_class .= ' br_desktop';
			}
			if ( 0 != $alter_class % $total_col_ipad ) {
				$col_class .= ' br_ipad';
			}
			if ( 0 != $alter_class % $total_col_tablet ) {
				$col_class .= ' br_tablet';
			}
			if ( 0 != $alter_class % $total_col_mobile ) {
				$col_class .= ' br_mobile';
			}
			$col_class .= ' bb';
			if ( $alter_class <= $total_col ) {
				$col_class .= ' bt_desktop';
			}
			if ( $alter_class <= $total_col_ipad ) {
				$col_class .= ' bt_ipad';
			}
			if ( $alter_class <= $total_col_tablet ) {
				$col_class .= ' bt_tablet';
			}
			if ( $alter_class <= $total_col_mobile ) {
				$col_class .= ' bt_mobile';
			}
			$post_thumbnail = 'invert-grid-thumb';
		}
		?>
		<li class="blog_wrap bdp_blog_template <?php echo ( '' != $col_class ) ? esc_attr( $col_class ) : ''; ?>">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="post-meta">
				<?php
				$display_date = ( isset( $bdp_settings['display_date'] ) && '' != $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : '';
				if ( 1 == $display_date ) {
					$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
					$ar_year   = get_the_time( 'Y' );
					$ar_month  = get_the_time( 'm' );
					$ar_day    = get_the_time( 'd' );
					?>
					<div class="postdate">
						<?php echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : ''; ?>
						<span class="month"><?php echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_post_modified_time( 'M d' ) ) : esc_html( get_the_time( 'M d' ) ); ?></span>
						<span class="year"><?php echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_post_modified_time( 'Y' ) ) : esc_html( get_the_time( 'Y' ) ); ?></span>
						<?php echo ( $date_link ) ? '</a>' : ''; ?>
					</div>
					<?php
				}
				if ( 1 == $bdp_settings['display_comment_count'] ) {
					if ( comments_open() ) {
						?>
						<span class="post-comment">
							<i class="fas fa-comment"></i>
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
				}
				?>
			</div>
			<div class="post-media">
				<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
				if ( '' != $label_featured_post && is_sticky() ) {
					?>
					<div class="label_featured_post"><span><?php echo esc_html( $label_featured_post ); ?></span></div> 
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
					?>
					<div class="bdp-post-image">
						<?php
						$post_thumbnail      = 'invert-grid-thumb';
						$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
						echo '<figure class="' . esc_html( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
						echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
						echo ( $bdp_post_image_link ) ? '</a>' : '';
						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
							?>
							<div class="bdp-pinterest-share-image">
								<?php
								$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								?>
								<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_url( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
							</div>
							<?php
						}
						if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
							$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
							echo '<div class="bdp_woocommerce_sale_wrap ' . esc_html( $bdp_sale_tagtext_alignment ) . '">';
							do_action( 'bdp_woocommerce_sale_tag' );
							echo '</div>';
						}
						echo '</figure>';
						?>
					</div>
					<?php
				}
				$display_author = $bdp_settings['display_author'];
				if ( 1 == $display_author ) {
					$author_class = ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] && get_post_format( $post->ID ) != 'gallery' ) ? 'post-video-format' : '';
					?>
					<span class="author <?php echo esc_attr( $author_class ); ?>">
						<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
					</span>
					<?php
				}
				?>
			</div>
			<div class="post_summary_outer">
				<div class="blog_header">
					<h2>
						<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
						if ( 1 == $bdp_post_title_link ) {
							?>
							<a href="<?php the_permalink(); ?>">
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
				</div>
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
					$readmoretxt    = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
					$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
					if ( 'new' == $link_behaviour ) {
						$link_behaviour = '_blank';
					} elseif ( 'self' == $link_behaviour ) {
						$link_behaviour = '_SELF';
					}
					if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {

						$post_link = get_permalink( $post->ID );
						if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
							$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
						}
						if ( 1 == $read_more_on ) {
							echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
						}
					}
					if ( ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) || ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) ) {
						echo '<div class="content-footer">';
						if ( '' != $bdp_settings['txtReadmoretext'] && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) {
							?>
							<div class="read-more">
								<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '"><i class="fas fa-link"></i>' . esc_html( $readmoretxt ) . ' </a>'; ?>
							</div>
							<?php
						}
						if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
							echo do_shortcode( '[likebtn_shortcode]' );
						}
						echo '</div>';
					}
					?>
				</div>
			</div>
			<?php
			if ( in_array( $post_type, $bdp_all_post_type ) ) {
				?>
				<div class="blog_footer">
				<div class="footer_meta">
					<?php
					$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
					$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
					foreach ( $taxonomy_names as $taxonomy_single ) {

						$taxonomy = $taxonomy_single->name;
						$sep      = 1;
						if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
							$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
							$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
							if ( isset( $taxonomy ) ) {
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									echo '<div class="taxonomy">';
									?>
									<span class="category-link<?php echo ( $taxonomy_link ) ? '' : ' categories_link'; ?>">
									<?php
									if ( 'product_cat' === $taxonomy || 'download_category' === $taxonomy ) {
										?>
										<i class="fas fa-folder"></i> 
										<?php
									} else {
										?>
										<i class="fas fa-bookmark"></i> <?php } ?>
									<?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
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
									</span>
									<?php
									echo '</div>';
								}
							}
						}
					}
					?>
				</div>
			</div>
				<?php
			} elseif ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) {
				?>
					<div class="blog_footer">
						<div class="footer_meta">
							<?php
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
								$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
								?>
								<span class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
									<span class="link-lable"> <i class="fas fa-folder"></i> <?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
									<?php
									$categories_list = get_the_category_list( ', ' );
									if ( $categories_link ) {
										$categories_list = wp_strip_all_tags( $categories_list );
									}
									if ( $categories_list ) :
										echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
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
									<div class="tags<?php echo ( $tag_link ) ? ' tag_link' : ''; ?>">
										<span class="link-lable"> <i class="fas fa-bookmark"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
										<?php
										echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
										$show_sep = true;
										?>
									</div>
									<?php
								endif;
							}
							?>
						</div>
					</div>
					<?php

			}
			if ( Bdp_Template_Acf::is_acf_plugin() ) {
				if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
					echo '<div class="bdp_acf_field">';
					do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
					echo '</div>';
				}
			}
			?>
			<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</li>
		<?php
		do_action( 'bdp_archive_separator_after_post' );
	}
}
