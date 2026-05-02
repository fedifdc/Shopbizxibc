<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/accordion.php.
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
if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
	add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );

}
global $post;
$image_hover_effect = '';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
	$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}
$bdp_all_post_type             = array( 'product', 'download' );
$accordion_template            = isset( $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1';
$bdp_single_settings           = get_post_meta( get_the_ID(), 'bdp_single_settings', true );
$bdp_single_selected_templates = isset( $bdp_single_settings['single_selecttemplate_field'] ) ? $bdp_single_settings['single_selecttemplate_field'] : array();

if ( ! empty( $bdp_single_selected_templates ) && is_array( $bdp_single_selected_templates ) && in_array( 'accordion', $bdp_single_selected_templates ) ) {
	$bdp_single_post_open_icon  = isset( $bdp_single_settings['bdp_single_post_open_icon'] ) ? $bdp_single_settings['bdp_single_post_open_icon'] : 'fas fa-plus';
	$bdp_single_post_close_icon = isset( $bdp_single_settings['bdp_single_post_close_icon'] ) ? $bdp_single_settings['bdp_single_post_close_icon'] : 'fas fa-minus';
} else {

	$bdp_dynamic_icon = isset( $bdp_settings['bdp_dynamic_icon'] ) ? $bdp_settings['bdp_dynamic_icon'] : 'plus_minus';
	if ( 'plus_minus' == $bdp_dynamic_icon ) {
		$bdp_single_post_open_icon  = 'fas fa-plus';
		$bdp_single_post_close_icon = 'fas fa-minus';
	} elseif ( 'single_chevron' == $bdp_dynamic_icon ) {
		$bdp_single_post_open_icon  = 'fas fa-chevron-down';
		$bdp_single_post_close_icon = 'fas fa-chevron-up';
	} elseif ( 'double_chevron' == $bdp_dynamic_icon ) {
		$bdp_single_post_open_icon  = 'fas fa-angle-double-down';
		$bdp_single_post_close_icon = 'fas fa-angle-double-up';
	} elseif ( 'hand_point' == $bdp_dynamic_icon ) {
		$bdp_single_post_open_icon  = 'fas fa-hand-point-down';
		$bdp_single_post_close_icon = 'fas fa-hand-point-up';
	} elseif ( 'solid_arrow' == $bdp_dynamic_icon ) {
		$bdp_single_post_open_icon  = 'fas fa-arrow-down';
		$bdp_single_post_close_icon = 'fas fa-arrow-up';
	}
}
?>
<div class='blog_accordion_uniquecontainer'>
	<div class='blog_accordion_section'>
		<div class="blog_wrap bdp_blog_template accordion bdp_blog_single_post_wrapp">
			<?php do_action( 'bdp_before_post_content' ); ?>
			<?php
			$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
			if ( '' != $label_featured_post && is_sticky() ) {
				?>
				<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
				<?php
			}
			?>
			<?php if ( 'accordion-template-5' == $accordion_template ) { ?>
				<h3 class="accodine-title-wrap">
				<div class="bdp-before-accordion-5"></div>
					<?php
					echo '<div class="accordion-icon-header" data-accordion-header="' . esc_attr( $bdp_single_post_open_icon ) . '" data-accordion-active-header="' . esc_attr( $bdp_single_post_close_icon ) . '"></div>';

					$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
					if ( 1 == $bdp_post_title_link ) {
						?>
						<a href="<?php esc_url( the_permalink() ); ?>">
						<?php } ?>
						<?php
						$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
						echo ( $total_noofline ) ? '<span class="accodine-title-name">' : '';
						echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
						echo ( $total_noofline ) ? '</span>' : '';
						if ( 1 == $bdp_post_title_link ) {
							?>
						</span>
						</a>
					<?php } ?>
					<div class="bdp-after-accordion-5"></div>
				</h3>
			<?php } else { ?>
				<h3 class="accodine-title-wrap">
					<?php
					echo '<div class="accordion-icon-header" data-accordion-header="' . esc_attr( $bdp_single_post_open_icon ) . '" data-accordion-active-header="' . esc_attr( $bdp_single_post_close_icon ) . '"></div>';

					$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
					if ( 1 == $bdp_post_title_link ) {
						?>
						<a href="<?php esc_url( the_permalink() ); ?>">
						<?php } ?>
						<?php
						$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
						echo ( $total_noofline ) ? '<span class="accodine-title-name">' : '';
						echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
						echo ( $total_noofline ) ? '</span>' : '';
						if ( 1 == $bdp_post_title_link ) {
							?>
						</a>
					<?php } ?>
				</h3>
			<?php } ?>
			<div class="post_content accordion-content">
			<?php
			if ( 1 == $bdp_settings['display_feature_image'] ) {
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
							if ( class_exists( 'woocommerce' ) && 'product' === $bdp_settings['custom_post_type'] ) {
								if ( isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
									$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
									echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
									do_action( 'bdp_woocommerce_sale_tag' );
									echo '</div>';
								}
							}
							echo '</figure>';
							?>
						</div>
						<?php
					}
				}
			}
			?>

			<?php
			$display_date          = $bdp_settings['display_date'];
			$display_author        = $bdp_settings['display_author'];
			$date_format           = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
			$date_link             = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
			$bdp_date              = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
			$display_comment_count = $bdp_settings['display_comment_count'];
			$ar_year               = get_the_time( 'Y' );
			$ar_month              = get_the_time( 'm' );
			$ar_day                = get_the_time( 'd' );
			if ( 1 == $display_date || 1 == $display_author || 1 == $bdp_settings['display_postlike'] || 1 == $display_comment_count ) {
				?>
				<div class="metadatabox">
					<?php
					if ( 1 == $display_date || 1 == $display_author || 1 == $bdp_settings['display_postlike'] || 1 == $display_comment_count ) {
						?>
						<div class="metadata">
							<?php
							if ( 1 == $display_author && 1 == $display_date ) {
								$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
								esc_html_e( 'Posted by', 'blog-designer-pro' );
								echo ' ';
								?>
									<span class="<?php echo ( $author_link ) ? 'bdp_hs_link' : 'bdp_no_link'; ?>">
									<?php
									echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
									?>
									</span>
									<?php
									echo ' ';
									esc_html_e( 'on', 'blog-designer-pro' );
									echo ' ';
									echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
									echo esc_html( $bdp_date );
									echo ( $date_link ) ? '</a>' : '';
							} elseif ( 1 == $display_author ) {
								$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
								esc_html_e( 'Posted by', 'blog-designer-pro' );
								echo ' ';
								?>
									<span class="<?php echo ( $author_link ) ? 'bdp_hs_link' : 'bdp_no_link'; ?>">
									<?php
									echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
									?>
									</span>
									<?php
							} elseif ( 1 == $display_date ) {
								esc_html_e( 'Posted on', 'blog-designer-pro' );
								echo ' ';
								echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
								echo esc_html( $bdp_date );
								echo ( $date_link ) ? '</a>' : '';
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
								echo do_shortcode( '[likebtn_shortcode]' );
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
									comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), 1, '%' );
							} else {
								comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), 1, '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
							}
							?>
						</div>
					<?php } ?>
				</div>
				<?php
			}
			if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
				do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
			}
			?>

			<div class="post_content-inner">
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
				if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) {
					$post_link = get_permalink( $post->ID );
					if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
						$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
					}
					if ( 1 == $read_more_on ) {
						$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
						?>
						<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>" target="<?php echo esc_html( $link_behaviour ); ?>">
							<?php echo esc_html( $readmoretxt ); ?>
						</a>
						<?php
					}
				}
				?>
			</div>
			<?php
			if ( ( '' != $bdp_settings['txtReadmoretext'] && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) || ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) ) {
				if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link && 2 == $read_more_on ) {
					?>
						<div class="read-more">
						<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>'; ?>
						</div>
						<?php
				}
			}
				echo '<div class="content-footer">';
			if ( 'post' === $bdp_settings['custom_post_type'] ) {
				if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) {
					echo '<div class="post-meta-cats-tags">';
				}
				if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
					$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
					?>
						<div class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
							<span class="link-lable"> <i class="fas fa-folder-open"></i> <?php esc_html_e( 'Category', 'blog-designer-pro' ); ?>:&nbsp; </span>
						<?php
						$categories_list = get_the_category_list( ', ' );
						if ( $categories_link ) {
							$categories_list = wp_strip_all_tags( $categories_list );
						}
						if ( $categories_list ) :
							echo ' ';
							echo ' ' . wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
							$show_sep = true;
							endif;
						?>
						</div>
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
							<div class="tags<?php echo ( $tags_list ) ? ' tag_link' : ''; ?>">
								<span class="link-lable"> <i class="fas fa-tags"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>:&nbsp; </span>
							<?php
							echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
							$show_sep = true;
							?>
							</div>
							<?php
						endif;
				}
				if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) {
					echo '</div>';
				}
			} else {
				$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
				$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
				foreach ( $taxonomy_names as $taxonomy_single ) {
					$taxonomy = $taxonomy_single->name;
					if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
						$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
						$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
						if ( isset( $taxonomy ) ) {
							if ( isset( $term_list ) && ! empty( $term_list ) ) {
								$sep = 1;
								echo '<div class="post-meta-cats-tags">';
								?>
									<span class="category-link taxonomies <?php echo esc_attr( $taxonomy ); ?>">
										<span class="link-lable"> <i class="fas fa-folder-open"></i> <?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
										<span class="category-link<?php echo ( $taxonomy_link ) ? ' categories_link' : ''; ?>">
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
									</span>
									<?php
									echo '</div>';
							}
						}
					}
				}
			}
				echo '</div>';
			?>
				<?php
				$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true;
				if ( $social_share ) {
					?>
				<div class="social-component-count-<?php echo esc_attr( $bdp_settings['social_count_position'] ); ?>">
					<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
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
			</div>
			<?php do_action( 'bdp_after_post_content' ); ?>
		</div>
	</div>
</div>
	<?php do_action( 'bdp_separator_after_post' ); ?>
