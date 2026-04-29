<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/hoverbic.php.
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

$total_col    = $bdp_settings['template_columns'];
$total_height = $bdp_settings['template_grid_height'];
$grid_height  = ( isset( $bdp_settings['blog_grid_height'] ) && 1 != $bdp_settings['blog_grid_height'] ) ? false : true;
$grid_skin    = $bdp_settings['template_grid_skin'];
$full_height  = ( $grid_height ) ? 'height: ' . $total_height . 'px;' : '';
$alter_class;
$col_class = '';
if ( 'repeat' === $grid_skin ) {
	if ( 1 == $alter_class || 1 == ( $alter_class % 5 ) ) {
		$col_class    = 'two_column full-col repeat';
		$full_height .= 'clear: left;';
	} else {
		$col_class = 'two_column full-col small-col repeat';
		if ( 2 == ( $alter_class % 5 ) || 4 == ( $alter_class % 5 ) ) {
			$full_height .= 'clear: left;';
		}
	}
} elseif ( 'default' === $grid_skin ) {
	if ( 1 == $alter_class ) {
		$col_class    = 'two_column full-col';
		$full_height .= 'clear: left;';
	} else {
		$col_class    = 'two_column small-col full-col';
		$full_height .= ( ( $alter_class ) % 2 == 0 ) ? 'clear: left;' : '';
	}
}

$div_height = ( '' != $full_height ) ? 'style="' . esc_attr( $full_height ) . '"' : '';
if ( has_post_thumbnail() ) {
	$post_thumbnail = 'full';
	$resized_image  = apply_filters( 'bdp_post_thumbnail_filter', get_the_post_thumbnail( $post->ID, $post_thumbnail ), $post->ID );
}

$class_name = 'blog_template bdp_blog_template hoverbic ';
if ( '' != $col_class ) {
	$class_name .= ' ' . $col_class;
}
$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
$display_filter_by   = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
$category            = '';
if ( ! empty( $display_filter_by ) ) {
	$category_detail = wp_get_post_terms( $post->ID, $display_filter_by );
	if ( ! empty( $category_detail ) ) {
		foreach ( $category_detail as $cd ) {
			$category .= $cd->slug . ' ';
		}
	}
}
?>
<div class="<?php echo esc_attr( $class_name ); ?> bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>" <?php echo esc_attr( $div_height ); ?>>
	<?php do_action( 'bdp_before_post_content' ); ?>
	<div class="post_hentry">
		<?php
		$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
		if ( '' != $label_featured_post && is_sticky() ) {
			?>
			<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
			<?php
		}
		if ( class_exists( 'woocommerce' ) && 'product' === $bdp_settings['custom_post_type'] ) {
			if ( isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) {
				$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
				echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
				do_action( 'bdp_woocommerce_sale_tag' );
				echo '</div>';
			}
		}
		?>
		<div class="bdp-post-image">
			<?php
			if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
				?>
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
			} elseif ( has_post_thumbnail() ) {
				echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
				echo wp_kses( $resized_image, Bdp_Admin_Functions::args_kses() );
				echo ( $bdp_post_image_link ) ? '</a>' : '';
				if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
					?>
					<div class="bdp-pinterest-share-image">
						<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
						<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
					</div>
					<?php
				}
			} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) {
				$thumbnail = wp_get_attachment_image( $bdp_settings['bdp_default_image_id'], 'full' );
				echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
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
			} else {
				$thumbnail = Bdp_Posts::get_sample_image( $post->ID, 'boxy_clean' );
				echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
				echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
				echo ( $bdp_post_image_link ) ? '</a>' : '';
			}
			?>
		</div>
		<div class="blog_header">
			<div class="header_wrapper">
				<div class="post-title">
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
				</div>

				<?php
				if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] || 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
					?>
					<div class="metadatabox">
						<?php
						if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) {
							echo '<div class="metabox-top">';
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
									<i class="far fa-calendar-alt"></i>
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
							echo '</div>';
						}
						if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) {
							echo '<div class="metabox-bottom">';
							if ( 1 == $bdp_settings['display_comment_count'] ) {
								?>
								<div class="post-comment">
									<i class="fas fa-comment"></i>
									<?php
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
										comments_number( esc_html__( 'Leave a Comment', 'blog-designeresc_html__ro' ), esc_html__( '1 comment', 'blog-designeesc_html__pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
									} else {
										comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designeresc_html__ro' ), esc_html__( '1 comment', 'blog-designeesc_html__pro' ), '% ' . esc_html__( 'comments', 'blog-designer-esc_html__o' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
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
							echo '</div>';
						}
						?>
					</div>
					<?php
				}
				if ( 'post' === $bdp_settings['custom_post_type'] ) {
					if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
						?>
						<div class="category-link">
							<i class="fas fa-folder"></i>
							<?php
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
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
					if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
						$tags_list = get_the_tag_list( '', ', ' );
						$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
						if ( $tag_link ) {
							$tags_list = wp_strip_all_tags( $tags_list );
						}
						if ( $tags_list ) :
							?>
							<div class="tags">
								<i class="fas fa-bookmark"></i>&nbsp;
								<?php
								echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
								$show_sep = true;
								?>
							</div>
							<?php
						endif;
					}
				} else {
					$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
					$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
					foreach ( $taxonomy_names as $taxonomy_single ) {
						$taxonomy = $taxonomy_single->name;
						$sep      = 1;
						if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
							$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
							$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
							if ( isset( $taxonomy ) ) {
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									?>
									<div class="category-link">
										<i class="fas fa-folder"></i> 
										<strong><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</strong>
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
				Bdp_Utility::get_social_icons( $bdp_settings );
				?>
			</div>
		</div>
	</div>
	<?php do_action( 'bdp_after_post_content' ); ?>
</div>
<?php
do_action( 'bdp_separator_after_post' );
