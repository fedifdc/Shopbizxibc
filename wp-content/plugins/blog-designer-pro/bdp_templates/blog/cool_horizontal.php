<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/cool_horizontal.php.
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
$read_more_on       = '';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) {
	$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}
?>
<div class="blog_template bdp_blog_template horizontal blog-wrap lb-item" data-id="<?php echo get_the_date( 'd/m/Y' ); ?>" data-description="<?php echo esc_attr( get_the_title() ); ?>">
	<?php do_action( 'bdp_before_post_content' ); ?>
	<div class="post_hentry">
		<div class="post_content_wrap">
			<?php
			$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : '';
			if ( '' != $label_featured_post && is_sticky() ) {
				?>
				<div class="label_featured_post"><span><?php echo esc_attr( $label_featured_post ); ?></span></div> 
				<?php
			}

			?>
			<div class="post-title">
				<?php
				$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
				if ( 1 == $bdp_post_title_link ) {
					echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
				}
				$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
				echo ( $total_noofline ) ? '<span>' : '';
				echo wp_kses( get_the_title(), Bdp_Admin_Functions::args_kses() );
				echo ( $total_noofline ) ? '</span>' : '';
				if ( 1 == $bdp_post_title_link ) {
					echo '</a>';
				}

				$display_date = $bdp_settings['display_date'];
				if ( 1 == $display_date ) {
					$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
					$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
					$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
					$ar_year     = get_the_time( 'Y' );
					$ar_month    = get_the_time( 'm' );
					$ar_day      = get_the_time( 'd' );
					?>
					<div class="mdate">
						<i class="far fa-calendar-alt"></i>&nbsp;&nbsp;
						<?php
						echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
						echo esc_html( $bdp_date );
						echo ( $date_link ) ? '</a>' : '';
						?>
					</div>
					<?php
				}
				?>
			</div>
			<div class="post_wrapper box-blog">
				<?php
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
				} elseif ( has_post_thumbnail() ) {
					$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
					?>
					<div class="photo post-image">
						<?php
						echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						$url            = wp_get_attachment_url( get_post_thumbnail_id() );
						$width          = isset( $bdp_settings['item_width'] ) ? $bdp_settings['item_width'] : 400;
						$height         = isset( $bdp_settings['item_height'] ) ? $bdp_settings['item_height'] : 200;
						$lazyload_class = '';
						if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
							$lazyload_class = 'lazyload';

						}
						$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, get_post_thumbnail_id() );
						echo '<img class="' . esc_attr( $lazyload_class ) . '"  src="' . esc_attr( $resized_image['url'] ) . '" data-src="' . esc_url( $resized_image['url'] ) . '"  width="' . esc_attr( $resized_image['width'] ) . '" height="' . esc_attr( $resized_image['height'] ) . '" title="' . esc_attr( $post->post_title ) . '" alt="' . esc_attr( $post->post_title ) . '" />';
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
				} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) {
					$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
					?>
					<div class="photo post-image">
						<?php
						echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						$url           = wp_get_attachment_url( $bdp_settings['bdp_default_image_id'] );
						$width         = isset( $bdp_settings['item_width'] ) ? $bdp_settings['item_width'] : 400;
						$height        = isset( $bdp_settings['item_height'] ) ? $bdp_settings['item_height'] : 200;
						$resized_image = Bdp_Utility::image_resize( $width, $height, $url, true, $bdp_settings['bdp_default_image_id'] );
						echo '<img src="' . esc_attr( $resized_image['url'] ) . '" width="' . esc_attr( $resized_image['width'] ) . '" height="' . esc_attr( $resized_image['height'] ) . '" title="' . esc_attr( $post->post_title ) . '" alt="' . esc_attr( $post->post_title ) . '" />';
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
				} else {
					$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true;
					?>
					<div class="photo post-image">
						<?php
						echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						$url    = BLOGDESIGNERPRO_URL . '/public/images/no_available_image_900.gif';
						$width  = isset( $bdp_settings['item_width'] ) ? $bdp_settings['item_width'] : 400;
						$height = isset( $bdp_settings['item_height'] ) ? $bdp_settings['item_height'] : 200;
						echo '<img src="' . esc_attr( $url ) . '" style="width:' . esc_attr( $width ) . 'px;height:' . esc_attr( $height ) . 'px" width="' . esc_attr( $width ) . 'px" height="' . esc_attr( $height ) . 'px" title="' . esc_attr( $post->post_title ) . '" alt="' . esc_attr( $post->post_title ) . '" />';
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
				?>
				<!--<div class="post-content-area">-->
				<?php
				if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_comment_count'] || ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) ) {
					?>
					<div class="metadatabox">
						<?php
						if ( 1 == $bdp_settings['display_author'] ) {
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
							?>
							<span class="mauthor <?php echo ( $author_link ) ? 'bdp_has_link' : 'bdp_no_link'; ?>">
								<i class="fas fa-user"></i>
								<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
							</span>
							<?php
						}
						if ( 1 == $bdp_settings['display_comment_count'] ) {
							?>
							<span class="mcomments">
								<i class="fas fa-comment"></i>
								<?php
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
									comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
								} else {
									comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
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
				<?php } ?>
				<?php
				if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				?>
				<?php
				if ( Bdp_Posts::get_content( $post->ID, $bdp_settings, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'] ) ) {
					?>
					<div class="post_content">
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
				}
				if ( 2 == $read_more_on && 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) {
					echo '<div class="read-more-div"><a class="more-tag" href="' . esc_url( $post_link ) . '" target="' . esc_html( $link_behaviour ) . '">' . esc_html( $readmoretxt ) . ' </a></div>';
				}
				if ( 'post' === $bdp_settings['custom_post_type'] ) {
					if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) {
						echo '<div class="blog_footer">';
						if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
							?>
							<div class="categories<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
								<span class="link-lable"><i class="fas fa-folder"></i> <?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?>:&nbsp; </span>
								<?php
								$categories_list = get_the_category_list( ', ' );
								if ( $categories_link ) {
									$categories_list = wp_strip_all_tags( $categories_list );
								}
								if ( $categories_list ) {
									echo wp_kses( $categories_list, Bdp_Admin_Functions::args_kses() );
								}
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
							if ( $tags_list ) {
								?>
								<div class="tags<?php echo ( ! $tag_link ) ? ' tag_link' : ''; ?>">
									<span class="link-lable"><i class="fas fa-bookmark"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>:&nbsp; </span>
									<?php echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() ); ?>
								</div>
								<?php
							}
						}
						echo '</div>';
					}
				} else {
					echo '<div class="blog_footer">';
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
									?>
									<div class="categories<?php echo ( $taxonomy_link ) ? '' : ' categories_link'; ?>">
										<span class="link-lable"><i class="fas fa-folder"></i> <?php echo esc_attr( $taxonomy_single->label ); ?>:&nbsp;</span>
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
					echo '</div>';
				}
				if ( Bdp_Template_Acf::is_acf_plugin() ) {
					if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
						echo '<div class="bdp_acf_field">';
						do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
						echo '</div>';
					}
				}
				$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true;
				if ( $social_share ) {
					if ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) {
						echo '<div class="blog_footer_social_div">';
						Bdp_Utility::get_social_icons( $bdp_settings );
						echo '</div>';
					}
				}
				?>
				<!--</div>-->
			</div>
		</div>
	</div>
	<?php do_action( 'bdp_after_post_content' ); ?>
</div>
<?php
 do_action( 'bdp_separator_after_post' );
