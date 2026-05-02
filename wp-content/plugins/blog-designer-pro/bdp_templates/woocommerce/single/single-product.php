<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/woocommerce/single/single-product.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      2.4
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

get_header();
Bdp_Posts::set_post_views( get_the_ID() );
global $wpdb;
$bdp_settings              = Bdp_Woocommerce::get_single_product_template_settings();
$alter_class               = '';
$style                     = '';
$bdp_theme                 = apply_filters( 'bdp_filter_template', $bdp_settings['template_name'] );
$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
if ( 1 == $bdp_template_name_changed ) {
	if ( 'classical' === $bdp_theme ) {
		$bdp_theme = 'nicy';
	} elseif ( 'lightbreeze' === $bdp_theme ) {
		$bdp_theme = 'sharpen';
	} elseif ( 'spektrum' === $bdp_theme ) {
		$bdp_theme = 'hub';
	}
} else {
	update_option( 'bdp_template_name_changed', 0 );
}

$main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : '';
if ( has_post_thumbnail() && 'overlay_horizontal' === $bdp_theme ) {
		$url   = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
		$style = 'style=background-image:url(' . esc_attr( $url ) . ')';
}
$bdp_display_related_post = isset( $bdp_settings['bdp_display_related_post'] ) ? $bdp_settings['bdp_display_related_post'] : 'bottom';
if ( 'overlay_horizontal' === $bdp_theme || 'cool_horizontal' === $bdp_theme ) {
	?>
	<div class="horizontal2-wrapper" <?php echo esc_attr( $style ); ?>>
		<div class="horizontal2-cover">
		<?php
} elseif ( 'foodbox' === $bdp_theme ) {
				echo '<div class="bdp-single-wrapper foodbox-cover">';
}
?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<?php
				do_action( 'bdp_before_single_page' );

				if ( '' != $main_container_class ) {
					echo '<div class="' . esc_attr( $main_container_class ) . '">';
				}
				?>
				<div class="bdp_single bdp_single_product  <?php echo esc_attr( $bdp_theme ) . ' ' . esc_attr( $bdp_display_related_post ); ?>">
					<?php
					if ( 'offer_blog' === $bdp_theme ) {
						echo '<div class="bdp_single_offer_blog">';
					}
					if ( 'winter' === $bdp_theme ) {
						echo '<div class="bdp_single_winter">';
					}
					// Start the loop.
					while ( have_posts() ) :
						the_post();
						if ( isset( $bdp_settings['display_related_post'] ) && 1 == $bdp_settings['display_related_post'] && 'bottom' !== $bdp_display_related_post ) {
							echo '<div class="bdp_single_related_post_leftright">';
						}
						// Include the single post content template.
						Bdp_Template::get_template( 'single/' . $bdp_theme . '.php' );

						do_action( 'bd_single_design_format_function', $bdp_settings, $alter_class );

						$display_author   = isset( $bdp_settings['display_author_data'] ) ? $bdp_settings['display_author_data'] : 0;
						$txt_author_title = isset( $bdp_settings['txtAuthorTitle'] ) ? $bdp_settings['txtAuthorTitle'] : '[author]';

						$display_author_biography = isset( $bdp_settings['display_author_biography'] ) ? $bdp_settings['display_author_biography'] : '';

						if ( 1 == $display_author && 'brite' === $bdp_theme ) {
							?>
							<div class="author-avatar-div bdp_blog_template">
								<?php
								do_action( 'bdp_author_detail', $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings );
								?>
							</div>
							<?php
						}
						if ( isset( $bdp_settings['display_navigation'] ) && 1 == $bdp_settings['display_navigation'] ) {
							// Previous/next post navigation.
							if ( isset( $bdp_settings['bdp_post_navigation_filter'] ) && '' != $bdp_settings['bdp_post_navigation_filter'] ) {

								$navigation_type = $bdp_settings['bdp_post_navigation_filter'];
								$prev_post       = get_previous_post( true, '', $navigation_type );
							} else {
								$prev_post = get_previous_post();
							}
							if ( isset( $bdp_settings['bdp_post_navigation_filter'] ) && '' != $bdp_settings['bdp_post_navigation_filter'] ) {
								$navigation_type = $bdp_settings['bdp_post_navigation_filter'];
								$next_post       = get_next_post( true, '', $navigation_type );
							} else {
								$next_post = get_next_post();
							}
							if ( ! empty( $prev_post ) || ! empty( $next_post ) ) {
								?>
								<nav id="post-nav" class="navigation post-navigation bdp-post-navigation">
									<div class="nav-links">
										<?php
										$nav_thumb_size       = array( 60, 60 );
										$nav_thumb_class      = array( 'class' => 'bdp_nav_post_img' );
										$post_nav_date_format = get_option( 'date_format' );
										if ( ! empty( $prev_post ) ) {
											$args      = array(
												'posts_per_page' => 1,
												'post_type' => 'product',
												'include' => $prev_post->ID,
											);
											$prev_post = get_posts( $args );
											foreach ( $prev_post as $post ) {
												setup_postdata( $post );
												?>
												<div class="previous-post">
													<div class="post-previous nav-previous">
														<a href="<?php esc_url( the_permalink() ); ?>" class="prev-link">
															<span class="left_nav fas fa-chevron-left"></span>
															<?php
															if ( has_post_thumbnail() && isset( $bdp_settings['display_pn_image'] ) && 1 == $bdp_settings['display_pn_image'] ) {
																?>
																<span class="navi-post-thumbnail">
																	<?php
																	echo wp_kses( apply_filters( 'bdp_nav_post_thumbnail_filter', get_the_post_thumbnail( get_the_ID(), $nav_thumb_size, $nav_thumb_class ), get_the_ID() ), Bdp_Admin_Functions::args_kses() );
																	?>
																</span>
															<?php } ?>
															<div class="post-data">
																<?php
																if ( isset( $bdp_settings['display_pn_title'] ) && 1 == $bdp_settings['display_pn_title'] ) {
																	?>
																	<span class="navi-post-title"><?php esc_attr( the_title() ); ?></span>
																<?php } else { ?>
																	<span class="navi-post-text meta-nav" aria-hidden="true">
																		<?php echo wp_kses( apply_filters( 'bdp_post_nav_prev_title', esc_html__( 'Previous Post', 'blog-designer-pro' ) ), Bdp_Admin_Functions::args_kses() ); ?>
																	</span>
																	<?php
																}
																if ( isset( $bdp_settings['display_pn_date'] ) && 1 == $bdp_settings['display_pn_date'] ) {
																	?>
																	<span class="navi-post-date"><?php echo wp_kses( apply_filters( 'bdp_post_nav_date_format', get_the_time( $post_nav_date_format, get_the_ID() ), get_the_ID() ), Bdp_Admin_Functions::args_kses() ); ?></span>
																<?php } ?>
															</div>
														</a>
													</div>
												</div>
												<?php
												wp_reset_postdata();
											} //end foreach
										} // end if

										if ( ! empty( $next_post ) ) {
											$args      = array(
												'posts_per_page' => 1,
												'post_type' => 'product',
												'include' => $next_post->ID,
											);
											$next_post = get_posts( $args );
											foreach ( $next_post as $post ) {
												setup_postdata( $post );
												?>
												<div class="next-post">
													<div class="post-next nav-next">
														<a href="<?php esc_url( the_permalink() ); ?>" class="next-link">

															<div class="post-data">
																<?php
																if ( isset( $bdp_settings['display_pn_title'] ) && 1 == $bdp_settings['display_pn_title'] ) {
																	?>
																	<span class="navi-post-title"><?php esc_attr( the_title() ); ?></span>
																<?php } else { ?>
																	<span class="navi-post-text meta-nav" aria-hidden="true">
																		<?php echo wp_kses( apply_filters( 'bdp_post_nav_next_title', esc_html__( 'Next Post', 'blog-designer-pro' ) ), Bdp_Admin_Functions::args_kses() ); ?>
																	</span>
																	<?php
																}
																if ( isset( $bdp_settings['display_pn_date'] ) && 1 == $bdp_settings['display_pn_date'] ) {
																	?>
																	<span class="navi-post-date"><?php echo wp_kses( apply_filters( 'bdp_post_nav_date_format', get_the_time( $post_nav_date_format, get_the_ID() ), get_the_ID() ), Bdp_Admin_Functions::args_kses() ); ?></span>
																<?php } ?>
															</div>
															<?php
															if ( has_post_thumbnail() && isset( $bdp_settings['display_pn_image'] ) && 1 == $bdp_settings['display_pn_image'] ) {
																?>
																<span class="navi-post-thumbnail">
																	<?php
																	echo wp_kses( apply_filters( 'bdp_nav_post_thumbnail_filter', get_the_post_thumbnail( get_the_ID(), $nav_thumb_size, $nav_thumb_class ), get_the_ID() ), Bdp_Admin_Functions::args_kses() );
																	?>
																</span>
															<?php } ?>
															<span class="right_nav fas fa-chevron-right"></span>
														</a>
													</div>
												</div>
												<?php
												wp_reset_postdata();
											} //end foreach
										} // end if
										?>
									</div>
								</nav>
								<?php
							}
						}

						$display_author           = isset( $bdp_settings['display_author_data'] ) ? $bdp_settings['display_author_data'] : 0;
						$txt_author_title         = isset( $bdp_settings['txtAuthorTitle'] ) ? $bdp_settings['txtAuthorTitle'] : '[author]';
						$display_author_biography = isset( $bdp_settings['display_author_biography'] ) ? $bdp_settings['display_author_biography'] : '';
						if ( 1 == $display_author && 'news' !== $bdp_theme && 'timeline' !== $bdp_theme && 'story' !== $bdp_theme && 'brite' !== $bdp_theme ) {
							?>
							<div class="author-avatar-div bdp_blog_template">
								<?php
								do_action( 'bdp_author_detail', $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings );
								?>
							</div>
							<?php
						}

						$related_post_number = isset( $bdp_settings['related_post_number'] ) ? $bdp_settings['related_post_number'] : '';

						$col_class = '';
						if ( 2 == $related_post_number ) {
							$post_perpage = 2;
						}
						if ( 3 == $related_post_number ) {
							$post_perpage = 3;
						}
						if ( 4 == $related_post_number ) {
							$post_perpage = 4;
						}

						$related_post_by = isset( $bdp_settings['related_post_by'] ) ? $bdp_settings['related_post_by'] : '';
						$title           = isset( $bdp_settings['related_post_title'] ) ? $bdp_settings['related_post_title'] : '';

						if ( isset( $bdp_settings['display_related_post'] ) && 1 == $bdp_settings['display_related_post'] && 'bottom' === $bdp_display_related_post ) {
							$related_post_content_from   = isset( $bdp_settings['related_post_content_from'] ) ? $bdp_settings['related_post_content_from'] : '';
							$related_post_content_length = isset( $bdp_settings['related_post_content_length'] ) ? $bdp_settings['related_post_content_length'] : '';
							$args                        = array();
							$orderby                     = 'date';
							$order                       = 'DESC';
							if ( isset( $bdp_settings['bdp_related_post_order_by'] ) && '' != $bdp_settings['bdp_related_post_order_by'] ) {
								$orderby = $bdp_settings['bdp_related_post_order_by'];
							}
							if ( isset( $bdp_settings['bdp_related_post_order'] ) ) {
								$order = $bdp_settings['bdp_related_post_order'];
							}
							$related_post_by = isset( $bdp_settings['related_post_by'] ) ? $bdp_settings['related_post_by'] : '';
							if ( 'category' === $related_post_by ) {
								global $post;
								$categories = get_the_terms( $post->ID, 'product_cat' );
								if ( $categories ) {
									$category_ids = array();
									foreach ( $categories as $individual_category ) {
										$category_ids[] = $individual_category->term_id;
									}

									$args = array(
										'post_type'      => 'product',
										'post__not_in'   => array( $post->ID ),
										'posts_per_page' => $post_perpage,
										'ignore_sticky_posts' => 1,
										'orderby'        => $orderby,
										'order'          => $order,
										'tax_query'      => array(
											'taxonomy' => 'product_cat',
											'field'    => 'term_id',
											'terms'    => $category_ids,

										),
									);
								}
							} elseif ( 'tag' === $related_post_by ) {
								global $post;
								$tags = get_the_terms( $post->ID, 'product_tag' );
								if ( $tags ) {
									$tag_ids = array();
									foreach ( $tags as $individual_tag ) {
										$tag_ids[] = $individual_tag->term_id;
									}
									$args = array(
										'tag__in'        => $tag_ids,
										'post__not_in'   => array( $post->ID ),
										'posts_per_page' => $post_perpage, // Number of related posts to display.
										'orderby'        => $orderby,
										'order'          => $order,
									);
								}
							}

							$my_query = new wp_query( $args );
							if ( $my_query->have_posts() ) {
								?>
								<div class="related_post_wrap">
									<?php
									do_action( 'bdp_related_post_detail', $bdp_theme, $post_perpage, $related_post_by, $title, $orderby, $order, $related_post_content_length, $related_post_content_from, $bdp_settings );
									?>
								</div>
								<?php
							}
						}
						// If comments are open or we have at least one comment, load up the comment template.
						if ( 'yes' === get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
							if ( isset( $bdp_settings['display_comment'] ) && 1 == $bdp_settings['display_comment'] ) {
								if ( comments_open() || get_comments_number() ) {
									do_action( 'do_woocommerce_after_single_product_summary' );
								}
							}
						}

						if ( isset( $bdp_settings['display_related_post'] ) && 1 == $bdp_settings['display_related_post'] && 'bottom' !== $bdp_display_related_post ) {
							echo '</div>';
						}
						if ( isset( $bdp_settings['display_related_post'] ) && 1 == $bdp_settings['display_related_post'] && 'bottom' !== $bdp_display_related_post ) {
							$related_post_content_from   = isset( $bdp_settings['related_post_content_from'] ) ? $bdp_settings['related_post_content_from'] : '';
							$related_post_content_length = isset( $bdp_settings['related_post_content_length'] ) ? $bdp_settings['related_post_content_length'] : '';
							$args                        = array();
							$orderby                     = 'date';
							$order                       = 'DESC';
							if ( isset( $bdp_settings['bdp_related_post_order_by'] ) && '' != $bdp_settings['bdp_related_post_order_by'] ) {
								$orderby = $bdp_settings['bdp_related_post_order_by'];
							}
							if ( isset( $bdp_settings['bdp_related_post_order'] ) ) {
								$order = $bdp_settings['bdp_related_post_order'];
							}
							if ( 'category' === $related_post_by ) {
								global $post;
								$categories = get_the_terms( $post->ID, 'product_cat' );
								if ( $categories ) {
									$category_ids = array();
									foreach ( $categories as $individual_category ) {
										$category_ids[] = $individual_category->term_id;
									}

									$args = array(
										'post_type'      => 'product',
										'post__not_in'   => array( $post->ID ),
										'posts_per_page' => $post_perpage,
										'orderby'        => $orderby,
										'order'          => $order,
										'tax_query'      => array(
											'taxonomy' => 'product_cat',
											'field'    => 'term_id',
											'terms'    => $category_ids,

										),
									);
								}
							} elseif ( 'tag' === $related_post_by ) {
								global $post;
								$tags = get_the_terms( $post->ID, 'product_tag' );
								if ( $tags ) {
									$tag_ids = array();
									foreach ( $tags as $individual_tag ) {
										$tag_ids[] = $individual_tag->term_id;
									}
									$args = array(
										'tag__in'        => $tag_ids,
										'post__not_in'   => array( $post->ID ),
										'posts_per_page' => $post_perpage, // Number of related posts to display.
										'orderby'        => $orderby,
										'order'          => $order,
									);
								}
							}

							$my_query = new wp_query( $args );
							if ( $my_query->have_posts() ) {
								?>
							<div class="bdp_display_related_post_left_right_wrap">
								<div class="related_post_wrap">
									<?php
									do_action( 'bdp_related_post_detail', $bdp_theme, $post_perpage, $related_post_by, $title, $orderby, $order, $related_post_content_length, $related_post_content_from, $bdp_settings );
									?>
								</div>
							</div>
								<?php
							}
						}

						// End of the loop.
					endwhile;
					if ( 'offer_blog' === $bdp_theme || 'winter' === $bdp_theme ) {
						echo '</div>';
					}
					?>
				</div>
				<?php
				if ( '' != $main_container_class ) {
					echo '</div">';
				}
				do_action( 'bdp_after_single_page' );
				?>
			</main><!-- .site-main -->
		</div><!-- .content-area -->
		<?php
		get_sidebar();
		if ( 'overlay_horizontal' === $bdp_theme || 'cool_horizontal' === $bdp_theme ) {
			?>
		</div>
	</div>
			<?php
		} elseif ( 'foodbox' === $bdp_theme ) {
			echo '</div>';
		}
		get_footer();
