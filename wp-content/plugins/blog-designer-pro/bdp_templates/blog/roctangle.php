<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/roctangle.php.
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
$display_filter_by = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
$category          = '';
if ( ! empty( $display_filter_by ) ) {
	$category_detail = wp_get_post_terms( $post->ID, $display_filter_by );
	if ( ! empty( $category_detail ) ) {
		foreach ( $category_detail as $cd ) {
			$category .= $cd->slug . ' ';
		}
	}
}
?>
<div class="bdp_blog_template roctangle-post-wrapper blog_masonry_item bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
	<?php do_action( 'bdp_before_post_content' ); ?>
	<div class="post-image-wrap">
		<?php
		$thumbnail_class = 'bdp-has-thumbnail';
		if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
			$thumbnail_class = 'bdp-no-thumbnail';
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
			?>
			<figure class="post-image bdp-post-image">
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
				<?php
				$post_thumbnail      = 'deport-thumbnail';
				$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
				$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
				if ( ! empty( $thumbnail ) ) {
					echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
					echo wp_kses( apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ), Bdp_Admin_Functions::args_kses() );
					echo ( $bdp_post_image_link ) ? '</a>' : '';
				}

				if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
					?>
					<div class="bdp-pinterest-share-image">
						<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
						<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
					</div>
					<?php
				}
				?>
			</figure>
			<?php
		}
		?>
		<div class="post-meta-wrapper <?php echo esc_attr( $thumbnail_class ); ?>">
			<?php
			$display_date          = $bdp_settings['display_date'];
			$display_author        = $bdp_settings['display_author'];
			$display_postlike      = $bdp_settings['display_postlike'];
			$display_comment_count = $bdp_settings['display_comment_count'];

			if ( 1 == $display_date ) {
				$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
				$ar_year   = get_the_time( 'Y' );
				$ar_month  = get_the_time( 'm' );
				$ar_day    = get_the_time( 'd' );
				?>
				<div class="post_date">
					<?php echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '" class="date">' : '<span class="date">'; ?>
					<span class="date"><?php echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'd' ) ) : esc_html( get_the_time( 'd' ) ); ?></span>
					<span class="month"><?php echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'M' ) ) : esc_html( get_the_time( 'M' ) ); ?></span>
					<span class="year"><?php echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'Y' ) ) : esc_html( get_the_time( 'Y' ) ); ?></span>
					<?php echo ( $date_link ) ? '</a>' : '</span>'; ?>
				</div>
				<?php
			}

			if ( 1 == $display_author || 1 == $display_postlike || 1 == $display_comment_count ) {
				if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
					echo '<div class="post-meta-div-cover">';
				}
				if ( 1 == $display_author || 1 == $display_postlike ) {
					?>
					<div class="post-meta-div"> 
						<?php
						if ( 1 == $display_author ) {
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
							?>
							<span class="author">
								<i class="fas fa-user"></i>
								<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
							</span>
							<?php
						}
						if ( 1 == $display_postlike ) {
							echo do_shortcode( '[likebtn_shortcode]' );
						}
						?>
					</div> 
					<?php
				}

				if ( 1 == $display_comment_count && ! post_password_required() ) {
					if ( comments_open() || get_comments_number() ) {
						?>
						<span class="post-comment">
							<i class="far fa-comment"></i>
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

				if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
	<div class="post-content-wrapper <?php echo esc_attr( $thumbnail_class ); ?>">
		<div class="post-title">
			<h2>
			<?php
			$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
			if ( 1 == $bdp_post_title_link ) {
				echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
			}
			$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
			echo ( $total_noofline ) ? '<span>' : '';
			echo esc_html( get_the_title() );
			echo ( $total_noofline ) ? '</span>' : '';
			if ( 1 == $bdp_post_title_link ) {
				echo '</a>';
			}
			?>
			</h2>
		</div>
		<?php
		if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
			do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
		}
		if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
			do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
		}
		?>
		<div class="post-content">
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
				if ( 1 == $read_more_on ) {
					echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>';
				}
			}
			?>
		</div>

		<?php
		if ( 'post' === $bdp_settings['custom_post_type'] ) {
			if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
				$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? false : true;
				$categories_list = get_the_category();
				if ( ! empty( $categories_list ) ) {
					?>
					<div class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
						<?php
						esc_html_e( 'Category', 'blog-designer-pro' );
						foreach ( $categories_list as $category_list ) {
							echo '<span class="post-category">';
							if ( $categories_link ) {
								echo '<a rel="tag" href="' . esc_url( get_category_link( $category_list->term_id ) ) . '">';
							}
							echo esc_attr( $category_list->name );
							if ( $categories_link ) {
								echo '</a>';
							}
							echo '</span>';
						}
						?>
					</div>
					<?php
				}
			}
		} else {
			$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
			$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
			echo '<div class="post-footer">';
			foreach ( $taxonomy_names as $taxonomy_single ) {
				$taxonomy = $taxonomy_single->name;
				$sep      = 1;
				if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
					$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
					$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
					if ( isset( $taxonomy ) ) {
						if ( isset( $term_list ) && ! empty( $term_list ) ) {
							?>
							<div class="category-link<?php echo ( $taxonomy_link ) ? ' categories_link' : ''; ?>">
								<?php
								echo esc_html( $taxonomy_single->label );
								foreach ( $term_list as $term_nm ) {
									$term_link = get_term_link( $term_nm );
									echo '<span class="post-category">';
									echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
									echo esc_attr( $term_nm->name );
									echo ( $taxonomy_link ) ? '</a>' : '';
									echo '</span>';
								}
								?>
							</div>
							<?php
						}
					}
				}
			}
			echo '</div>';
		}
		if ( Bdp_Template_Acf::is_acf_plugin() ) {
			if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
				echo '<div class="bdp_acf_field">';
				do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
				echo '</div>';
			}
		}

		if ( 'post' === $bdp_settings['custom_post_type'] ) {
			if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
				$tags_lists = get_the_tags();
				$tag_link   = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? false : true;

				if ( ! empty( $tags_lists ) ) {
					?>
					<div class="tags<?php echo ( $tag_link ) ? ' tag_link' : ''; ?>">
						<?php
						esc_html_e( 'Tags', 'blog-designer-pro' );
						foreach ( $tags_lists as $tags_list ) {
							echo '<span class="tag">';
							if ( $tag_link ) {
								echo '<a rel="tag" href="' . esc_url( get_tag_link( $tags_list->term_id ) ) . '">';
							}
							echo esc_attr( $tags_list->name );
							if ( $tag_link ) {
								echo '</a>';
							}
							echo '</span>';
						}
						?>
					</div>
					<?php
				}
			}
		}
		Bdp_Utility::get_social_icons( $bdp_settings );
		echo '<div class="content-footer">';
		if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) {
			?>
			<div class="read-more">
				<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a>'; ?>
			</div>
			<?php
		}
		echo '</div>';
		?>

	</div>
	<?php do_action( 'bdp_after_post_content' ); ?>
</div>

<?php
do_action( 'bdp_separator_after_post' );
