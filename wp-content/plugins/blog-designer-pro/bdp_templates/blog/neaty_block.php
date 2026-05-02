<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/neaty_block.php.
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

$image_hover_effect = 'bdp-post-image ';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
	$image_hover_effect .= ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}
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
$bdp_all_post_type = array( 'product', 'download' );
?>
<div class="bdp_blog_template blog_template neaty_block_blog bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
	<?php
	do_action( 'bdp_before_post_content' );
		$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
	if ( '' != $label_featured_post && is_sticky() ) {
		?>
		<div class="label_featured_post"><span><?php echo esc_attr( $label_featured_post ); ?></span></div> 
		<?php
	}
	$post_thumbnail = 'full';
	if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) {
		echo '<div class="bdp-post-image post-video">';
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
		$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
		$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
		if ( ! empty( $thumbnail ) ) {
			echo '<figure class="neaty-block-img-wrapper bdp-mb-20  ' . esc_attr( $image_hover_effect ) . '">';
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
			$display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
			if ( 1 == $display_author ) {
				?>
					<div class="neaty-block-avatar">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
					</div>
				<?php } ?>
					<div class="bdp-comments-box">
						<ul>
						<?php
							$display_postlike = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : 0;
						if ( 1 == $display_postlike ) {
							echo '<li>' . do_shortcode( '[likebtn_shortcode]' ) . '</li>';
						}
						?>
							<?php
							$display_comment_count = isset( $bdp_settings['display_comment_count'] ) ? $bdp_settings['display_comment_count'] : 1;
							if ( 1 == $display_comment_count ) {
								$disable_link_comment = ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) ? true : false;
								?>
								<li class="bdp-neaty-comment-li">
									<i class="fas fa-comment"></i>
									<?php
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
										comments_number( '0', '1', '%' );
									} else {
										comments_popup_link( '0', '1', '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
									}
									?>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
					<?php
					echo '</figure>';
		}
	}
	$display_date = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1;
	if ( 1 == $display_date ) {
		$date_link  = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
		$ar_year    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'Y' ) : get_the_time( 'Y' );
		$ar_month   = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'm' ) : get_the_time( 'm' );
		$ar_day     = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'd' ) : get_the_time( 'd' );
		$date_class = ( $date_link ) ? 'bdp_has_links' : 'bdp_no_links';
		echo '<div class="bdp-neaty-block-metadata">';
		echo '<ul>';
		echo '<li><span class="date ' . esc_attr( $date_class ) . '">';
		echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
		echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'd' ) ) : esc_html( get_the_time( 'd' ) );
		echo ( $date_link ) ? '</a>' : '';
		echo '</span></li>';
		echo '<li><span class="month ' . esc_attr( $date_class ) . '">';
		echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_month_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
		echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'F' ) ) : esc_html( get_the_time( 'M' ) );
		echo ( $date_link ) ? '</a>' : '';
		echo '</span></li>';
		echo '<li><span class="year ' . esc_attr( $date_class ) . '">';
		echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_year_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
		echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'Y' ) ) : esc_html( get_the_time( 'Y' ) );
		echo ( $date_link ) ? '</a>' : '';
		echo '</span></li>';
		echo '</ul>';
		echo '</div>';
	}
	$display_date          = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1;
	$display_author        = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
	$display_comment_count = isset( $bdp_settings['display_comment_count'] ) ? $bdp_settings['display_comment_count'] : 1;

	echo '<div class="post-meta">';
	if ( 'post' === $bdp_settings['custom_post_type'] ) {
		if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
			$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
			?>
				<span class="category-link<?php echo ( $categories_link ) ? ' bdp_no_links' : ' bdp_has_links'; ?>">
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
	} elseif ( isset( $bdp_settings['custom_post_type'] ) && in_array( $bdp_settings['custom_post_type'], $bdp_all_post_type ) ) {
		$bdp_tax_cat = '';
		if ( 'product' === $bdp_settings['custom_post_type'] ) {
			$bdp_tax_cat = 'product_cat';
		} elseif ( 'download' === $bdp_settings['custom_post_type'] ) {
			$bdp_tax_cat = 'download_category';
		}
		if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) {
			$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true;
			$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
			$sep                = 1;
			?>
			<span class="post-category <?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
				<i class="fas fa-folder-open"></i> &nbsp;
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
	}
	if ( 1 == $display_author ) {
		$author_link  = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
		$author_class = ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links';
		echo '<span class="post-author ' . esc_attr( $author_class ) . '">';
		echo '<i class="fas fa-user"></i>';
		echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
		echo '</span>';
	}

	if ( empty( $thumbnail ) ) {
		$display_postlike = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : 0;
		if ( 1 == $display_postlike ) {
			echo '<span>' . do_shortcode( '[likebtn_shortcode]' ) . '</span>';
		}
		$display_comment_count = isset( $bdp_settings['display_comment_count'] ) ? $bdp_settings['display_comment_count'] : 1;
		if ( 1 == $display_comment_count ) {
			$disable_link_comment = ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) ? true : false;
			?>
			<span>
				<i class="fas fa-comment"></i>
				<?php
				if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
					comments_number( '0', '1', '%' );
				} else {
					comments_popup_link( '0', '1', '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
				}
				?>
			</span>
			<?php
		}
	}


	echo '</div>';
	if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
		do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
	}
	if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
		do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
	}
	?>
	<div class="post_content">
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
				echo esc_html( get_the_title() );
				echo ( $total_noofline ) ? '</span>' : '';
				if ( 1 == $bdp_post_title_link ) {
					?>
				</a>
			<?php } ?>
		</h2>
		<p>
		<?php
		echo wp_kses( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ), Bdp_Admin_Functions::args_kses() );
		$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
		$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 1;
		$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
		if ( 'new' == $link_behaviour ) {
			$link_behaviour = '_blank';
		} elseif ( 'self' == $link_behaviour ) {
			$link_behaviour = '_SELF';
		}
		if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {
			$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Continue Reading', 'blog-designer-pro' );
			$post_link   = get_permalink( $post->ID );
			if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
				$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
			}
			if ( 1 == $read_more_on ) {
				echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '"> ' . esc_html( $readmoretxt ) . ' </a>';
			}
		}
		?>
		</p>
	</div>

	<?php
	if ( 'post' === $bdp_settings['custom_post_type'] ) {
		if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
			$tags_list = get_the_tag_list( '', ', ' );
			$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
			if ( $tag_link ) {
				$tags_list = wp_strip_all_tags( $tags_list );
			}
			if ( $tags_list ) :
				?>
				<span class="tags<?php echo ( $tag_link ) ? ' bdp_no_links' : ' bdp_has_links'; ?>">
					<i class="fas fa-tags"></i>
					<?php
					echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
					$show_sep = true;
					?>
				</span>
				<?php
			endif;
		}
	}
	if ( 'post' !== $bdp_settings['custom_post_type'] ) {
		$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
		$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
		foreach ( $taxonomy_names as $taxonomy_single ) {
			$taxonomy = $taxonomy_single->name;
			if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
				$term_list            = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
				$taxonomy_link        = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;
				$taxonomy_class       = ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links';
				$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
				if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
					if ( isset( $term_list ) && ! empty( $term_list ) ) {
						echo '<div class="post-meta-cats-tags">';
						$sep = 1;
						?>
						<div class="category-link taxonomies <?php echo esc_attr( $taxonomy ) . ' ' . esc_attr( $taxonomy_class ); ?>">
							<span class="link-lable"> <?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
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
						echo '</div>';
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
	?>
	<div class="post-bottom">
		<?php
		if ( 2 == $read_more_on && 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {
			echo '<div class="read_more_div">';
			echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '"> ' . esc_html( $readmoretxt ) . ' </a>';
			echo '</div>';
		}
		$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true;
		if ( $social_share ) {
			Bdp_Utility::get_social_icons( $bdp_settings );
		}

		?>
	</div>
	<?php
	do_action( 'bdp_after_post_content' );
	?>
</div>
<?php
