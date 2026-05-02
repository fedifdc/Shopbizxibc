<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/neaty_block.php.
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
add_action( 'bd_single_design_format_function', 'bdp_single_neaty_block_template', 10, 1 );
if ( ! function_exists( 'bdp_single_neaty_block_template' ) ) {

	/**
	 * Add html for neaty_block template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_neaty_block_template( $bdp_settings ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );
		}
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		?>
		<div class="blog_wrap blog_template bdp_blog_template neaty-blog">
		<?php do_action( 'bdp_before_single_post_content' ); ?>
		<div class="goodlook-blog">
		   
			<?php if ( has_post_thumbnail() && isset( $bdp_settings['display_thumbnail'] ) && 1 == $bdp_settings['display_thumbnail'] ) { ?>
				<div class="bdp-post-image">
					<?php
					if ( 'product' === $post_type ) {
						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && 1 == isset( $bdp_settings['social_share'] ) && $bdp_settings['social_share'] ) {
							echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
						}
						do_action( 'bdp_woocommerce_show_product_images', $bdp_settings, $post->ID );
					} else {
						$single_post_image = Bdp_Posts::get_the_single_post_thumbnail( $bdp_settings, get_post_thumbnail_id(), get_the_ID() );
						echo wp_kses( apply_filters( 'bdp_single_post_thumbnail_filter', $single_post_image, get_the_ID() ), Bdp_Admin_Functions::args_kses() );
						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
							echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
						}
					}
					$display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
					if ( 1 == $display_author ) {
						?>
						<div class="post-avatar">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
						</div>
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
								<li>
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
					<?php } ?>
				</div>
			<?php } ?>
			<?php
			$display_date = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1;
			if ( 1 == $display_date ) {
				$date_link  = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
				$ar_year    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'Y' ) : get_the_time( 'Y' );
				$ar_month   = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'm' ) : get_the_time( 'm' );
				$ar_day     = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? get_the_modified_time( 'd' ) : get_the_time( 'd' );
				$date_class = ( $date_link ) ? 'bdp_has_links' : 'bdp_no_links';
				echo '<div class="bdp-post-date">';
				echo '<ul>';
				echo '<li><span class="date ' . esc_attr( $date_class ) . '">';
				echo ( 'product' !== $post_type && $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
				echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'd' ) ) : esc_html( get_the_time( 'd' ) );
				echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
				echo '</span></li>';
				echo '<li><span class="month ' . esc_attr( $date_class ) . '">';
				echo ( 'product' !== $post_type && $date_link ) ? '<a class="mdate" href="' . esc_url( get_month_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
				echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'F' ) ) : esc_html( get_the_time( 'M' ) );
				echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
				echo '</span></li>';
				echo '<li><span class="year ' . esc_attr( $date_class ) . '">';
				echo ( 'product' !== $post_type && $date_link ) ? '<a class="mdate" href="' . esc_url( get_year_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
				echo esc_html( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'Y' ) ) : esc_html( get_the_time( 'Y' ) );
				echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
				echo '</span></li>';
				echo '</ul>';
				echo '</div>';
			}
			if ( in_array( $post_type, $bdp_all_post_type ) ) {
				$bdp_tax_cat = '';
				if ( 'product' === $post_type ) {
					$bdp_tax_cat = 'product_cat';
				} elseif ( 'download' === $post_type ) {
					$bdp_tax_cat = 'download_category';
				}
				if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) {
					$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true;
					$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
					$sep                = 1;
					?>
					<div class="post-category">
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
					</div>
					<?php
				}
			} else {
				if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
					$categories_list = get_the_category_list( ', ' );
					$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
					?>
					<div class="post-category <?php echo ( $categories_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
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
			}
			$display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
			if ( 1 == $display_author ) {
				$author_link  = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
				$author_class = ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links';
				echo '<span class="post-author ' . esc_attr( $author_class ) . '">';
				echo '<i class="fas fa-user"></i>';
				echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() );
				echo '</span>';
			}

			?>
			<div class="post-content">
				<?php
				$display_title = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1;
				if ( 1 == $display_title ) {
					?>
					<div class="bdp-post-title">
						<h2 class="post-title">
							<?php
								$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
								echo ( $total_noofline ) ? '<span>' : '';
								echo esc_html( get_the_title() );
								echo ( $total_noofline ) ? '</span>' : '';
							?>
						</h2>
					</div>
					<?php
				}
				if ( 'product' === $post_type ) {
					do_action( 'bdp_woocommerce_meta_data', $bdp_settings, $post->ID );
				}
				if ( 'download' === $post_type && isset( $bdp_settings['display_download_price'] ) && 1 == $bdp_settings['display_download_price'] ) {
					do_action( 'bdp_edd_single_download_price', $post->ID );
				}
				?>
				<div class="post_content">
					<?php
						do_action( 'bdp_single_post_content_data', $bdp_settings, $post->ID );
					if ( 'download' === $post_type && isset( $bdp_settings['display_edd_addtocart_button'] ) && 1 == $bdp_settings['display_edd_addtocart_button'] ) {
						do_action( 'bdp_edd_single_download_cart_button', $post->ID );
					}
					?>
					<?php
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) {
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_single_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
		</div>
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
					$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
					if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) {
						if ( isset( $term_list ) && ! empty( $term_list ) ) {
							?>
							<div class="tags">
							<span class="link-lable"> <i class="fas fa-tags"></i> <?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
								<?php
								foreach ( $term_list as $term_nm ) {
									if ( 1 != $sep ) {
										?>
										<span class="seperater"><?php echo ', '; ?></span>
										<?php
									}
									$term_link = get_term_link( $term_nm );
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
		} else {
			if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) {
				$tags_list = get_the_tag_list( '', ', ' );
				$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false;
				if ( $tag_link ) {
					$tags_list = wp_strip_all_tags( $tags_list );
				}
				if ( $tags_list ) :
					?>
					<div class="tags <?php echo ( $tag_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
						<span class="link-lable"> <i class="fas fa-tags"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
						<?php echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() ); ?>
					</div>
					<?php
				endif;
			}
		}

		if ( isset( $bdp_settings['display_post_views'] ) && 0 != $bdp_settings['display_post_views'] ) {
			if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) {
				echo '<div class="display_post_views">';
				echo wp_kses( Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ), Bdp_Admin_Functions::args_kses() );
				echo '</div>';
			}
		}
		if ( isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
			$social_share_position = ( isset( $bdp_settings['social_share_position'] ) && '' != $bdp_settings['social_share_position'] ) ? $bdp_settings['social_share_position'] : '';
			echo '<div class="social-share-links ' . esc_attr( $social_share_position ) . '_position">';
			if ( is_single() ) {
				do_action( 'bdp_social_share_text', $bdp_settings );
			}
			Bdp_Utility::get_social_icons( $bdp_settings );
			echo '</div>';
		}

		?>
		<?php do_action( 'bdp_after_single_post_content' ); ?>
		</div>
		<?php
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_image' ), 5, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_start' ), 10, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_name' ), 15, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_biography' ), 20, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_social_links' ), 25, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_end' ), 30, 2 );
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_title' ), 5, 4 );
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_item' ), 10, 9 );
	}
}
