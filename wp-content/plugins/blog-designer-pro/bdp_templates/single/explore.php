<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/explore.php.
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

add_action( 'bd_single_design_format_function', 'bdp_single_explore_template', 10, 1 );

if ( ! function_exists( 'bdp_single_explore_template' ) ) {

	/**
	 * Add html for cool_horizontal template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_explore_template( $bdp_settings ) {
		if ( isset( $bdp_settings['enable_lazy_load'] ) && 1 == $bdp_settings['enable_lazy_load'] ) {
			add_filter( 'wp_get_attachment_image_attributes', 'bdp_lazyload_images_modify_post_thumbnail_attr', 11, 5 );
		}
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		?>
		<div class="blog_template bdp_blog_template explore_wrapper">
			<?php do_action( 'bdp_before_single_post_content' ); ?>

			<?php
			if ( isset( $bdp_settings['display_thumbnail'] ) && 1 == $bdp_settings['display_thumbnail'] ) {
				if ( has_post_thumbnail() ) {
					?>
					<div class="post-media">
						<div class="bdp-post-image">
							<?php
							if ( 'product' === $post_type ) {
								if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
									echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
								}
								do_action( 'bdp_woocommerce_show_product_images', $bdp_settings, $post->ID );
							} else {
								if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) {
									echo wp_kses( Bdp_Utility::pinterest( $post->ID ), Bdp_Admin_Functions::args_kses() );
								}
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) {
								echo do_shortcode( '[likebtn_shortcode]' );
							}
							?>
						</div>
					</div>
					<?php
				}
			}
			?>
			<div class="post-content-area">
				<?php
				$display_title = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1;
				if ( 1 == $display_title ) {
					?>
					<div class="blog_header">
						<h1 class="post-title">
							<?php
							$total_noofline = isset( $bdp_settings['total_noofline'] ) ? $bdp_settings['total_noofline'] : '2';
							echo ( $total_noofline ) ? '<span>' : '';
							echo esc_html( get_the_title() );
							echo ( $total_noofline ) ? '</span>' : '';
							?>
						</h1>
					</div>
					<?php
				}
				?>
				<div class="post_content entry-content">
					<div class="post-right-box">
						<div class="metadatabox">
							<?php
							if ( 1 == $bdp_settings['display_date'] ) {
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );
								$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true;
								$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
								$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
								?>
								<div class="mdate">
									<b>
										<?php
										if ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) {
											esc_html_e( 'Last Updated on', 'blog-designer-pro' );
										} else {
											esc_html_e( 'Published on', 'blog-designer-pro' );
										}
										?>
										:
									</b>
									<?php
									echo ( 'product' === $post_type && $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
									echo esc_html( $bdp_date );
									echo ( 'product' === $post_type && $date_link ) ? '</a>' : '';
									?>
								</div>
								<?php
							}

							if ( 1 == $bdp_settings['display_author'] ) {
								$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
								?>
								<div class="mauthor">
									<b><?php esc_html_e( 'Published by', 'blog-designer-pro' ); ?>:</b>
									<?php echo wp_kses( Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ), Bdp_Admin_Functions::args_kses() ); ?>
								</div>
								<?php
							}
							if ( in_array( $post_type, $bdp_all_post_type ) ) {
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
												?>
												<div class="categories  <?php echo ( $taxonomy_link ) ? '' : ' categories_link'; ?>">
													<b><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </b>
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
							} else {
								if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) {
									?>
									<div class="categories">
										<?php
										$categories_list = get_the_category_list( ', ' );
										$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false;
										if ( $categories_link ) {
											$categories_list = wp_strip_all_tags( $categories_list );
										}
										if ( $categories_list ) :
											?>
											<b><?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?> :</b>
											<?php
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
											<b><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> : </b>
											<?php
											echo wp_kses( $tags_list, Bdp_Admin_Functions::args_kses() );
											$show_sep = true;
											?>
										</div>
										<?php
									endif;
								}
							}

							if ( isset( $bdp_settings['display_comment'] ) && 1 == $bdp_settings['display_comment'] ) {
								?>
								<span class="post-comment">
									<i class="fas fa-comment"></i>
									<?php
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) {
										$id     = get_the_ID();
										$number = get_comments_number( $id );
										if ( 0 == $number && ! comments_open() && ! pings_open() ) {
											echo esc_html__( 'Comments are off', 'blog-designer-pro' );
										} else {
											comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
										}
									} else {
										comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
									}
									?>
								</span>
								<?php
							}
							if ( isset( $bdp_settings['display_post_views'] ) && 0 != $bdp_settings['display_post_views'] ) {
								if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) {
									echo '<div class="display_post_views">';
									echo wp_kses( Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ), Bdp_Admin_Functions::args_kses() );
									echo '</div>';
								}
							}
							?>
							<div class="post-share-div">
								<?php
								if ( is_single() ) {
									do_action( 'bdp_social_share_text', $bdp_settings );
								}
								Bdp_Utility::get_social_icons( $bdp_settings );
								?>
							</div>
						   
						</div>
					</div>
					<?php
					if ( 'product' === $post_type ) {
						do_action( 'bdp_woocommerce_meta_data', $bdp_settings, $post->ID );
					}
					if ( 'download' === $post_type && isset( $bdp_settings['display_download_price'] ) && 1 == $bdp_settings['display_download_price'] ) {
						do_action( 'bdp_edd_single_download_price', $post->ID );
					}
					?>
					<div class="bdp_post_content">
						<?php
						do_action( 'bdp_single_post_content_data', $bdp_settings, $post->ID );
						if ( 'download' === $post_type && isset( $bdp_settings['display_edd_addtocart_button'] ) && 1 == $bdp_settings['display_edd_addtocart_button'] ) {
							do_action( 'bdp_edd_single_download_cart_button', $post->ID );
						}
						?>
					</div>
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
