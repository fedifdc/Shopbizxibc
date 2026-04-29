<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/glossary.php.
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

$col_class = Bdp_Template::column_class( $bdp_settings );

$class_name = 'blog_template bdp_blog_template glossary blog_masonry_item';
if ( '' != $col_class ) {
	$class_name .= ' ' . $col_class;
}
$image_hover_effect = '';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
	$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
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
?>
<div class="<?php echo esc_attr( $class_name ); ?> bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
	<?php do_action( 'bdp_before_post_content' ); ?>
	<div class="blog_item">
		<div class="blog_header"> 
		<?php
			$display_date   = $bdp_settings['display_date'];
			$display_author = $bdp_settings['display_author'];
			$date_format    = ( isset( $bdp_settings['post_date_format'] ) && 'default' != $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
			$bdp_date       = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
			$comment_cnt    = $bdp_settings['display_comment_count'];
			$ar_year        = get_the_time( 'Y' );
			$ar_month       = get_the_time( 'm' );
			$ar_day         = get_the_time( 'd' );
		if ( 1 == $display_author || 1 == $display_date || 1 == $comment_cnt || 1 == $bdp_settings['display_postlike'] ) {
			?>
					<div class="posted_by">
			<?php
			if ( 1 == $display_author && 1 == $display_date || 1 == $bdp_settings['display_postlike'] ) {
				$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
				$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
				echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
				?>
				<time datetime="" class="datetime"><?php echo esc_html( $bdp_date ); ?></time>
				<?php
				echo ( $date_link ) ? '</a>' : '';
				?>
				<span class="post-author <?php echo ( ! $author_link ) ? 'bdp_no_links' : ''; ?>">&nbsp; | &nbsp;
					<?php
					echo '<span class="link-lable">' . esc_html__( 'By ', 'blog-designer-pro' ) . '</span>';
					echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
					?>
				</span>
				<?php
			} elseif ( 1 == $display_author ) {
				$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
				?>
				<div class="icon-date"></div>
				<span class="post-author <?php echo ( ! $author_link ) ? 'bdp_no_links' : ''; ?>">
				<?php
				echo '<span class="link-lable">' . esc_html__( 'By ', 'blog-designer-pro' ) . '</span>';
				echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
				?>
				</span>

				<?php
			} elseif ( 1 == $display_date ) {
				$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
				echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
				?>
				<time datetime="" class="datetime">
				<?php echo wp_kses( apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID ), Bdp_Admin_Functions::args_kses() ); ?>
				</time>
				<?php
				echo ( $date_link ) ? '</a>' : '';
			}
			if ( 1 == $comment_cnt ) {
				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
					?>
						<span class="comment">
						<?php
						echo ( ( 1 == $display_author && 1 == $display_date ) || ( 1 == $display_author || 1 == $display_date ) ) ? ' | ' : '';
						$comment_link = ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) ? false : true;
						Bdp_Posts::comment_count( $comment_link );
						?>
						</span>
						<?php
						endif;
			}
			if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
				echo do_shortcode( '[likebtn_shortcode]' );
			}
			?>
					</div>
			<?php
		}
		?>
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
					echo esc_html( get_the_title() );
					echo ( $total_noofline ) ? '</span>' : '';
					if ( 1 == $bdp_post_title_link ) {
						?>
					</a>
				<?php } ?>
			</h2>
		</div>
		<div class="post_summary_outer">
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
				$post_thumbnail      = 'full';
				$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
				$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
				if ( ! empty( $thumbnail ) ) {
					?>
					<?php
					$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
					if ( '' != $label_featured_post && is_sticky() ) {
						?>
						<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
						<?php
					}
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
			if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
				do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
			}
			if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
				do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
			}
			?>
		</div>
		<?php
		if ( 'post' === $bdp_settings['custom_post_type'] ) {
			if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) {
				?>
				<div class="blog_footer">
					<div class="footer_meta">
						<?php
						if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
							?>
							<span class="category-link <?php echo ( $categories_link ) ? 'bdp_no_links' : ''; ?>">
								<span class="link-lable"> <i class="fas fa-folder"></i><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?>&nbsp;:&nbsp; </span>
								<?php
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
								<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : ''; ?>">
									<span class="link-lable"> <i class="fas fa-bookmark"></i><?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?>&nbsp;:&nbsp; </span>
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
		} else {
			$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
			$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
			if ( ! empty( $taxonomy_names ) ) {
				?>
				<div class="blog_footer">
					<?php
					foreach ( $taxonomy_names as $taxonomy_single ) {
						$taxonomy = $taxonomy_single->name;
						$sep      = 1;
						if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) {
							$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
							$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true;

							if ( isset( $taxonomy ) ) {
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									?>
									<div class="footer_meta">
										<span class="category-link">
											<span class="link-lable"> <i class="fas fa-folder"></i>&nbsp;<?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
					?>
				</div>
				<?php
			}
		}
		?>

		<div class="post_content">
				<div class="post_content-inner">
					<?php if ( 0 == $bdp_settings['rss_use_excerpt'] ) : ?>
						<div class="content_upper_div">
							<?php
							$content = apply_filters( 'the_content', get_the_content( $post->ID ) );
							$content = apply_filters( 'bdp_content_change', $content, $post->ID );
							echo wp_kses( $content, Bdp_Admin_Functions::args_kses() );
							?>
						</div>
						<?php
					else :
						$template_post_content_from = 'from_content';
						if ( isset( $bdp_settings['template_post_content_from'] ) ) {
							$template_post_content_from = $bdp_settings['template_post_content_from'];
						}
						if ( 'from_excerpt' === $template_post_content_from ) {
							if ( '' != get_the_excerpt() ) {
								$bdp_excerpt_data = get_the_excerpt( get_the_ID() );
							} else {
								$excerpt          = get_the_content( $post->ID );
								$excerpt_length   = $bdp_settings['txtExcerptlength'];
								$text             = strip_shortcodes( $excerpt );
								$text             = apply_filters( 'the_content', $text );
								$text             = str_replace( ']]>', ']]&gt;', $text );
								$bdp_excerpt_data = wp_trim_words( $text, $excerpt_length, '' );
								$bdp_excerpt_data = apply_filters( 'bdp_excerpt_change', $bdp_excerpt_data, $post->ID );
							}
						} else {
							$excerpt          = get_the_content( $post->ID );
							$excerpt_length   = $bdp_settings['txtExcerptlength'];
							$text             = strip_shortcodes( $excerpt );
							$text             = apply_filters( 'the_content', $text );
							$text             = str_replace( ']]>', ']]&gt;', $text );
							$bdp_excerpt_data = wp_trim_words( $text, $excerpt_length, '' );
							$bdp_excerpt_data = apply_filters( 'bdp_excerpt_change', $bdp_excerpt_data, $post->ID );
						}
						if ( '' != $bdp_excerpt_data ) {
							?>
							<p class="post_content-inner"><?php echo wp_kses( $bdp_excerpt_data, Bdp_Admin_Functions::args_kses() ); ?></p>
							<?php
							$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
							$link_behaviour = isset( $bdp_settings['link_behaviour'] ) ? $bdp_settings['link_behaviour'] : 'self';
							if ( 'new' == $link_behaviour ) {
								$link_behaviour = '_blank';
							} elseif ( 'self' == $link_behaviour ) {
								$link_behaviour = '_SELF';
							}
							if ( 1 == $read_more_link && 0 != $bdp_settings['rss_use_excerpt'] ) {
								$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' );
								$post_link   = get_permalink( $post->ID );
								if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) {
									$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID );
								}
								?>
								<div class="overlay" style="background:<?php echo esc_attr( Bdp_Utility::hex2rgba( $bdp_settings['template_content_hovercolor'], 0.9 ) ); ?>">
									<div class="read-more-class">
										<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '"><i class="fas fa-link"></i> ' . esc_html( $readmoretxt ) . ' </a>'; ?>
									</div>
								</div>
								<?php
							}
						}
					endif;
					?>
				</div>
			</div>

		<?php
		if ( Bdp_Template_Acf::is_acf_plugin() ) {
			if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
				echo '<div class="bdp_acf_field">';
				do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
				echo '</div>';
			}
		}
		?>
		<?php
		Bdp_Utility::get_social_icons( $bdp_settings );
		?>
	</div>
	<?php do_action( 'bdp_after_post_content' ); ?>
</div>
<?php
do_action( 'bdp_separator_after_post' );
