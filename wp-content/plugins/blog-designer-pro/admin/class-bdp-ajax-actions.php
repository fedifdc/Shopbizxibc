<?php
/**
 * Administration API: Core Ajax handlers
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Ajax_Actions
 * @version 1.0.0
 */
class Bdp_Ajax_Actions {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_custom_post_taxonomy', array( $this, 'bdp_custom_post_taxonomy' ) );
		add_action( 'wp_ajax_get_custom_taxonomy_terms', array( $this, 'bdp_get_custom_taxonomy_terms' ) );
		add_action( 'wp_ajax_custom_post_taxonomy_display_settings', array( $this, 'bdp_custom_post_taxonomy_display_settings' ) );
		add_action( 'wp_ajax_bdp_get_posts_single_template', array( $this, 'bdp_get_posts_single_template' ) );
		add_action( 'wp_ajax_bdp_preview_request', array( $this, 'bdp_preview_request' ) );
		add_action( 'wp_ajax_bdp_archive_preview_request', array( $this, 'bdp_archive_preview_request' ) );
		add_action( 'wp_ajax_bdp_closed_bdpboxes', array( $this, 'bdp_closed_bdpboxes' ) );
		add_action( 'wp_ajax_bdp_admin_notice_pro_layouts_dismiss', array( $this, 'bdp_admin_notice_pro_layouts_dismiss' ) );
		add_action( 'wp_ajax_bdp_create_layout_from_blog_designer_dismiss', array( $this, 'bdp_create_layout_from_blog_designer_dismiss' ) );
		add_action( 'wp_ajax_nopriv_bdp_blog_template_search_result', array( $this, 'bdp_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_blog_template_search_result', array( $this, 'bdp_blog_template_search_result' ) );
		add_action( 'wp_ajax_nopriv_bdp_single_blog_template_search_result', array( $this, 'bdp_single_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_single_blog_template_search_result', array( $this, 'bdp_single_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_notice_template_outdated_dismiss', array( $this, 'bdp_notice_template_outdated_dismiss' ) );
	}
	/**
	 * Ajax handler to get custom post taxonomy
	 *
	 * @return void
	 */
	public function bdp_custom_post_taxonomy() {
		ob_start();
		?>
		<table>
			<tbody>
				<?php
				if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) {
					$custom_posttype = esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) );
				}
				$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
				$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
				if ( ! empty( $taxonomy_names ) ) {
					foreach ( $taxonomy_names as $taxonomy_name ) {
						if ( ! empty( $taxonomy_name ) ) {
							$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
							if ( ! empty( $terms ) ) {
								?>
								<tr class="custom-taxonomy">
									<td>
										<?php
										esc_html_e( 'Select', 'blog-designer-pro' );
										echo ' ' . esc_html( $taxonomy_name->label );
										?>
									</td>
									<td>
										<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_terms[]" id="terms_<?php echo esc_attr( $taxonomy_name->name ); ?>">
											<?php
											foreach ( $terms as $term ) {
												?>
												<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
											<?php } ?>
										</select>
										<div class="exclude_tag_list_div"><label><input id="exclude_<?php echo esc_html( $taxonomy_name->name ); ?>_list" name="exclude_<?php echo esc_html( $taxonomy_name->name ); ?>_list" type="checkbox" value="1" /> <?php echo esc_html__( 'Exclude Selected ', 'blog-designer-pro' ) . esc_attr( $taxonomy_name->label ); ?></label></div>
									</td>
								</tr>
								<?php
							}
						}
					}
				}
				?>
			</tbody>
		</table>
		<?php
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Administration API: Core Ajax handlers
	 * Ajax handler to get custom post taxonomy terms
	 *
	 * @since 2.1
	 * @return void
	 */
	public function bdp_get_custom_taxonomy_terms() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) {
				$custom_posttype = esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) );
			}
			$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
			$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
			if ( ! empty( $taxonomy_names ) ) {
				foreach ( $taxonomy_names as $taxonomy_name ) {
					$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
					if ( ! empty( $terms ) ) {
						?>
						<li class="bdp-post-terms">
							<div class="bdp-left"><span class="bdp-key-title"><?php echo esc_html__( 'Select', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ); ?></span></div>
							<div class="bdp-right">
								<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_terms[]" id="terms_<?php echo esc_attr( $taxonomy_name->name ); ?>">
									<?php foreach ( $terms as $term ) { ?>
										<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
									<?php } ?>
								</select>
								<div class="exclude_tag_list_div"><label><input id="exclude_<?php echo esc_attr( $taxonomy_name->name ); ?>_list" name="exclude_<?php echo esc_attr( $taxonomy_name->name ); ?>_list" type="checkbox" value="1" /> <?php echo esc_html__( 'Exclude Selected ', 'blog-designer-pro' ) . esc_html( $taxonomy_name->label ); ?></label></div>
							</div>
						</li>
						<?php
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Ajax handler to get custom post taxonomy display settings
	 * Administration API: Core Ajax handlers
	 *
	 * @since 2.1
	 */
	public function bdp_custom_post_taxonomy_display_settings() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) {
				$custom_posttype = esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) );
			}
			$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
			$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
			if ( 'post' === $custom_posttype ) {
				?>
				<div class="bdp-typography-cover display-custom-taxonomy">
					<div class="bdp-typography-label">
						<span class="bd-key-title"><?php esc_html_e( 'Post Category', 'blog-designer-pro' ); ?></span>
					</div>
					<div class="bdp-typography-content">
						<fieldset class="bdp-social-options bdp-display_author buttonset">
							<input id="display_category_1" name="display_category" type="radio" value="1" checked="checked" />
							<label for="display_category_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
							<input id="display_category_0" name="display_category" type="radio" value="0" />
							<label for="display_category_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
						</fieldset>
						<label class="disable_link"><input id="disable_link_category" name="disable_link_category" type="checkbox" value="1" /> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?></label>
						<label class="filter_data"><input id="filter_cat" name="filter_category" type="checkbox" value="1" /> <?php esc_html_e( 'Display Filter for Categories', 'blog-designer-pro' ); ?></label>
					</div>
				</div>
				<div class="bdp-typography-cover display-custom-taxonomy">
					<div class="bdp-typography-label">
						<span class="bd-key-title"><?php esc_html_e( 'Post Tag', 'blog-designer-pro' ); ?></span>
					</div>
					<div class="bdp-typography-content">
						<fieldset class="bdp-social-options bdp-display_author buttonset">
							<input id="display_tag_1" name="display_tag" type="radio" value="1" checked="checked" />
							<label for="display_tag_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
							<input id="display_tag_0" name="display_tag" type="radio" value="0" />
							<label for="display_tag_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
						</fieldset>
						<label class="disable_link"><input id="disable_link_tag" name="disable_link_tag" type="checkbox" value="1" /> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?></label>
						<label class="filter_data"><input id="filter_tag" name="filter_tags" type="checkbox" value="1" /> <?php esc_html_e( 'Display Filter for Tags', 'blog-designer-pro' ); ?></label>
					</div>
				</div>
				<?php
			} elseif ( ! empty( $taxonomy_names ) ) {
				foreach ( $taxonomy_names as $taxonomy_name ) {
					if ( ! empty( $taxonomy_name ) ) {
						?>
						<div class="bdp-typography-cover display-custom-taxonomy">
							<div class="bdp-typography-label">
								<span class="bd-key-title"><?php echo esc_attr( $taxonomy_name->label ); ?></span>
							</div>
							<div class="bdp-typography-content">
								<fieldset class="bdp-display_tax buttonset">
									<input id="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_1" name="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="radio" value="1" />
									<label for="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
									<input id="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_0" name="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="radio" value="0" checked="checked" />
									<label for="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
								</fieldset>
								<label class="disable_link">
									<input id="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" name="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="checkbox" value="1" 
									<?php
									if ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] ) ) {
										checked( 1, $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] );
									}
									?>
									/>
									<?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
								</label>
							</div>
						</div>
						<?php
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Get post listing
	 *
	 * @return void
	 */
	public function bdp_get_posts_single_template() {
		ob_start();
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$tax_ids = isset( $_POST['tax_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['tax_ids'] ) ) : array();
			$tax     = isset( $_POST['tax'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['tax'] ) ) ) : '';
			global $wpdb;
			$db_posts      = $wpdb->get_results( 'SELECT single_post_id FROM ' . $wpdb->prefix . 'bdp_single_layouts' );
			$db_posts_list = array();
			if ( $db_posts ) {
				foreach ( $db_posts as $db_post ) {
					$sub_list = $db_post->single_post_id;
					if ( $sub_list ) {
						$db_post_ids = explode( ',', $sub_list );
						foreach ( $db_post_ids as $db_post_id ) {
							$db_posts_list[] = $db_post_id;
						}
					}
				}
			}
			$final_posts = $db_posts_list;
			if ( 'tag' === $tax && ! empty( $tax_ids ) ) {
				$args = array(
					'posts_per_page' => -1,
					'post_type'      => 'post',
					'orderby'        => 'date',
					'order'          => 'desc',
					'tag__in'        => $tax_ids,
				);
			} elseif ( 'category' === $tax && ! empty( $tax_ids ) ) {
				$args = array(
					'posts_per_page' => -1,
					'post_type'      => 'post',
					'orderby'        => 'date',
					'order'          => 'desc',
					'category__in'   => $tax_ids,
				);
			} else {
				$args = array(
					'posts_per_page' => -1,
					'post_type'      => 'post',
					'orderby'        => 'date',
					'order'          => 'desc',
				);
			}
			$allposts = get_posts( $args );
			?>
			<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Posts', 'blog-designer-pro' ); ?></span></div>
			<div class="bdp-right">
				<?php
				if ( $allposts ) {
					?>
					<select data-placeholder="<?php esc_attr_e( 'Choose Posts', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_posts[]" id="template_posts">
						<?php
						foreach ( $allposts as $single_post ) :
							setup_postdata( $single_post );
							?>
							<option 
							<?php
							if ( in_array( $single_post->ID, $final_posts ) ) {
								echo 'disabled="disabled" ';
							}
							?>
							value="<?php echo esc_attr( $single_post->ID ); ?>"><?php echo esc_html( $single_post->post_title ); ?></option>
							<?php
						endforeach;
						wp_reset_postdata();
						?>
					</select>
					<div class="bdp-setting-description bdp-note">
						<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
						<?php esc_html_e( 'Default All Posts Selected', 'blog-designer-pro' ); ?>
					</div>
					<?php
				} else {
					esc_html_e( 'No posts found', 'blog-designer-pro' );
				}
				?>
			</div>
			<?php
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Function for getting post list (function not in use)
	 */
	public function bdp_get_taxonomy_list() {
		ob_start();
		if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) {
			$custom_posttype = esc_attr( sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) );
		}
		$taxonomy_names = get_object_taxonomies( $custom_posttype );
		$sep            = 1;
		if ( ! empty( $taxonomy_names ) ) {
			foreach ( $taxonomy_names as $taxonomy_name ) {
				if ( ! empty( $taxonomy_name ) ) {
					$terms = get_terms( $taxonomy_name, array( 'hide_empty' => false ) );
					if ( ! empty( $terms ) ) {
						if ( 1 != $sep ) {
							echo ',';
						}
						echo wp_kses( $taxonomy_name, Bdp_Admin_Functions::args_kses() );
						++$sep;
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data;
		die();
	}
	/**
	 * Ajax handler for preview
	 *
	 * @return void
	 */
	public function bdp_preview_request() {
		if ( isset( $_POST['ajaxnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajaxnonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['settings'] ) && isset( $_POST['layout_id'] ) ) {
				$bdp_settings = array();
				$layout_id    = sanitize_text_field( wp_unslash( $_POST['layout_id'] ) );
				// Ignore this for PHPCS.
				$settings = isset( $_POST['settings'] ) ? sanitize_text_field( wp_unslash( $_POST['settings'] ) ) : '';
				parse_str( $settings, $bdp_settings );
				echo Bdp_Template::layout_view_portion( $layout_id, $bdp_settings );
				exit();
			}
		}
	}
	/**
	 * Ajax handler for archive preview
	 *
	 * @return void
	 */
	public function bdp_archive_preview_request() {
		if ( isset( $_POST['ajaxnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajaxnonce'] ) ), 'ajax-nonce' ) ) {
			if ( isset( $_POST['settings'] ) ) {
				$bdp_settings = array();
				$settings     = isset( $_POST['settings'] ) ? sanitize_text_field( wp_unslash( $_POST['settings'] ) ) : '';
				parse_str( $settings, $bdp_settings );
				$alter_class = '';
				$alter       = 1;
				$bdp_theme   = $bdp_settings['template_name'];
				$layout_id   = isset( $_POST['layout_id'] ) ? sanitize_text_field( wp_unslash( $_POST['layout_id'] ) ) : '';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) {
					$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
				} else {
					$firstpost_unique_design = 0;
				}
				if ( ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) ) {
					if ( ! wp_style_is( 'choosen-handle-css' ) ) {
						wp_enqueue_style( 'choosen-handle-css' );
					}
					if ( ! wp_script_is( 'choosen-handle-script' ) ) {
						wp_enqueue_script( 'choosen-handle-script' );
					}
					$filter_array = array( 'boxy', 'boxy-clean', 'cool_horizontal', 'overlay_horizontal', 'news', 'invert-grid', 'brit_co', 'media-grid' );
					if ( in_array( $bdp_theme, $filter_array ) ) {
						?>
						<form name="bdp-filer-change" id="bdp-filer-change">
							<?php
							echo '<div class="bdp_filter_option">';
							esc_html_e( 'Choose from below options to filter your posts', 'blog-designer-pro' );
							echo '<br/>';
							if ( isset( $bdp_settings['filter_date'] ) && 1 == $bdp_settings['filter_date'] ) {
								// while ( have_posts() ) :
								// the_post();.
								$dates[ get_the_time( 'Y-m' ) ] = get_the_time( 'F Y' );
								// endwhile;.
								?>
								<select name="filter_date[]" id="filter_date" class="chosen-select filter_data" data-placeholder="<?php esc_attr_e( 'Choose', 'blog-designer-pro' ); ?> date" multiple="multiple">
									<?php
									krsort( $dates );
									foreach ( $dates as $key => $value ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php
									}
									?>
								</select>
								<?php
							}
							echo '</div>';
							?>
							<input type="hidden" name="blog_template" id="blog_template" value="<?php echo esc_attr( $bdp_theme ); ?>" />
							<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="<?php echo esc_attr( $layout_id ); ?>" />
							<input type="hidden" name="blog_itemMargin" id="blog_itemMargin" value="
						<?php
						if ( isset( $bdp_settings['template_post_margin'] ) ) {
							echo esc_attr( $bdp_settings['template_post_margin'] );
						}
						?>
						" />
							<input type="hidden" name="blog_itemWidth" id="blog_itemWidth" value="
						<?php
						if ( isset( $bdp_settings['item_width'] ) ) {
							echo esc_attr( $bdp_settings['item_width'] );
						}
						?>
						" />
							<input type="hidden" name="blog_itemHeight" id="blog_itemHeight" value="
						<?php
						if ( isset( $bdp_settings['item_height'] ) ) {
							echo esc_attr( $bdp_settings['item_height'] );
						}
						?>
						" />
							<input type="hidden" name="blog_easing" id="blog_easing" value="
						<?php
						if ( isset( $bdp_settings['template_easing'] ) ) {
							echo esc_attr( $bdp_settings['template_easing'] );
						}
						?>
						" />
							<input type="hidden" name="blog_startFrom" id="blog_startFrom" value="
						<?php
						if ( isset( $bdp_settings['timeline_start_from'] ) ) {
							echo esc_attr( $bdp_settings['timeline_start_from'] );
						}
						?>
						" />
							<input type="hidden" name="blog_hideLogbook" id="blog_hideLogbook" value="
						<?php
						if ( isset( $bdp_settings['display_timeline_bar'] ) ) {
							echo esc_attr( $bdp_settings['display_timeline_bar'] );
						}
						?>
						" />
							<input type="hidden" name="blog_autoplay" id="blog_autoplay" value="
						<?php
						if ( isset( $bdp_settings['enable_autoslide'] ) ) {
							echo esc_html( $bdp_settings['enable_autoslide'] );
						}
						?>
						" />
							<input type="hidden" name="blog_scrollSpeed" id="blog_scrollSpeed" value="
						<?php
						if ( isset( $bdp_settings['scroll_speed'] ) ) {
							echo esc_html( $bdp_settings['scroll_speed'] );
						}
						?>
						" />
							<input type="hidden" name="blog_page_number" id="blog_page_number" value="<?php echo esc_attr( $paged ); ?>" />
						</form>
						<?php
					}
				}
				?>
				<?php
					$url = '';

				if ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ) {
					$url = 'https://';
				} else {
					$url = 'http://';
				}
					$url              .= isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
					$url              .= isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
					$urlcheck          = $url;
					$enable_print_page = isset( $bdp_settings['enable_print_page'] ) ? $bdp_settings['enable_print_page'] : 1;
				if ( 1 == $enable_print_page ) {
					$txt_print_page = isset( $bdp_settings['txt_print_page'] ) ? $bdp_settings['txt_print_page'] : '';
					if ( isset( $bdp_settings['txt_print_page'] ) && '' != $bdp_settings['txt_print_page'] ) {
						?>
							<div class="printbtn" style="text-align:right; padding:30px;">
								<a href="<?php echo esc_url( $urlcheck ); ?>" class="print_click" style="pointer-events: none;">
									<span class="pdftitle"><?php echo esc_html( $txt_print_page ); ?></span>
								</a>
							</div>			
							<?php
					}
				}
				?>
				<div class="blog_template bdp_wrapper <?php echo esc_attr( $bdp_theme ); ?>_cover bdp_archive <?php echo esc_attr( $bdp_theme ); ?> layout_id_<?php echo esc_attr( $layout_id ); ?>">
					<?php
					if ( 'accordion' === $bdp_theme ) {
						$template_icon_alignment = ( isset( $bdp_settings['template_icon_alignment'] ) && '' != $bdp_settings['template_icon_alignment'] ) ? $bdp_settings['template_icon_alignment'] : 'icon-left';

						$bdp_accordion_layout_class = ( isset( $bdp_settings['accordion_template'] ) && '' != $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1';
						echo '<div class="blog_template accordion accordion_wrapper ' . esc_attr( $bdp_accordion_layout_class ) . ' ' . esc_attr( $template_icon_alignment ) . '">';
					}
					if ( 'offer_blog' === $bdp_theme ) {
						echo '<div class="bdp_single_offer_blog">';
					}
					if ( 'winter' === $bdp_theme ) {
						echo '<div class="bdp_single_winter">';
					}
					if ( 'author_template' === $bdp_settings['custom_archive_type'] ) {
						$display_author           = isset( $bdp_settings['display_author_data'] ) ? $bdp_settings['display_author_data'] : 0;
						$txt_author_title         = isset( $bdp_settings['txtAuthorTitle'] ) ? $bdp_settings['txtAuthorTitle'] : '[author]';
						$display_author_biography = isset( $bdp_settings['display_author_biography'] ) ? $bdp_settings['display_author_biography'] : '';
						if ( 1 == $display_author ) {
							?>
							<div class="author-avatar-div bdp_blog_template bdp-author-avatar">
								<?php
								if ( 'news' === $bdp_theme ) {
									?>
									<div class="author_div bdp_blog_template">
										<ul class="nav nav-tabs">
											<li class="active">
												<span class="ts-fab-tab-text"><?php esc_html_e( 'About Author', 'blog-designer-pro' ); ?></span>
											</li>
										</ul>
										<div class="tab-content">
											<div id="home" class="tab-pane fade in active">
												<?php
												do_action( 'bdp_author_archive_detail', $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings );
												?>
											</div>
										</div>
									</div>
									<?php
								} else {
									do_action( 'bdp_author_archive_detail', $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings );
								}
								?>
							</div>
							<?php
						}
					}
					if ( 'timeline' === $bdp_theme ) {
						if ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'left_side' === $bdp_settings['bdp_timeline_layout'] ) {
							if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
								echo '<div class="timeline_bg_wrap left_side with_year"><div class="timeline_back clearfix">';
							} else {
								echo '<div class="timeline_bg_wrap left_side"><div class="timeline_back clearfix">';
							}
						} elseif ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'right_side' === $bdp_settings['bdp_timeline_layout'] ) {
							if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) {
								echo '<div class="timeline_bg_wrap right_side with_year"><div class="timeline_back clearfix">';
							} else {
								echo '<div class="timeline_bg_wrap right_side"><div class="timeline_back clearfix">';
							}
						} elseif ( 'date' === $orderby || 'modified' === $orderby ) {
								echo '<div class="timeline_bg_wrap date_order"><div class="timeline_back clearfix">';
						} else {
							echo '<div class="timeline_bg_wrap"><div class="timeline_back clearfix">';
						}
					}
					if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
						echo '<div class="bdp-row ' . esc_attr( $bdp_theme ) . '">';
					}
					if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
						$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . $bdp_settings['column_setting'] : 'column_layout_2';
						$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . $bdp_settings['column_setting_ipad'] : 'column_layout_ipad_2';
						$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . $bdp_settings['column_setting_tablet'] : 'column_layout_tablet_1';
						$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . $bdp_settings['column_setting_mobile'] : 'column_layout_mobile_1';
						$column_class          = $column_setting . ' ' . $column_setting_ipad . ' ' . $column_setting_tablet . ' ' . $column_setting_mobile;
						echo '<div class="bdp-row ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
					}
					if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme ) {
						echo '<div class="bdp-js-masonry masonry bdp_' . esc_attr( $bdp_theme ) . '">';
					}
					if ( 'boxy-clean' === $bdp_theme ) {
						echo '<div class="blog_template boxy-clean"><ul>';
					}
					$slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation3';
					if ( 'crayon_slider' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="blog_template slider_template crayon_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'sallet_slider' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="blog_template slider_template sallet_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'colorful_sliding' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="blog_template slider_template colorful_sliding ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'sunshiny_slider' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="blog_template slider_template sunshiny_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'blog_carousel' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="blog_template slider_template blog_carousel ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
					}
					if ( 'leest' === $bdp_theme ) {
						$class_name  = 'blog_template bdp_blog_template leest blog_masonry_item';
						$leest_design = isset( $bdp_settings['leest_design'] ) ? $bdp_settings['leest_design'] : 'top-bottom';
						if ( 'top' === $leest_design ) {
							echo '<div class="main-leest-class ' . esc_attr( $leest_design ) . '">';
						} elseif ( 'bottom' === $leest_design ) {
							echo '<div class="main-leest-class ' . esc_attr( $leest_design ) . '">';
						} else {
							echo '<div class="main-leest-class ' . esc_attr( $leest_design ) . '">';
						}
						?>
						<div class="<?php echo esc_attr( $class_name ); ?>  bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
						<?php
						echo '<div class="leest-wapper ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $slider_navigation ) . '"><div class="slick-slider list-slick-slider">';
					}
					if ( 'threed_carousel' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="bdp-3d-container blog_template slider_template  slider_' . esc_attr( $unique_id ) . ' "><ul class=" slides slider_gallery ">';
					}
					if ( 'flip_book_3d' === $bdp_theme ) {
						$unique_id = wp_rand();
						echo '<div class="bb-custom-wrapper blog_template slider_template slider_' . esc_attr( $unique_id ) . ' "><div id="bb-bookblock" class="bdp_flip_book_3d bb-bookblock">';
					}
					if ( 'banner' === $bdp_theme ) {
						echo '<div class="banner-container"><div class="banner-row">';
					}
					if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
						echo '<div class="logbook flatLine flatNav flatButton">';
					}
					if ( 'easy_timeline' === $bdp_theme ) {
						echo '<div class="blog_template bdp_blog_template easy-timeline-wrapper"><ul class="easy-timeline" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
					}
					if ( 'steps' === $bdp_theme ) {
						echo '<div class="blog_template bdp_blog_template steps-wrapper"><ul class="steps" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
					}
					if ( 'my_diary' === $bdp_theme ) {
						echo '<div class="my_diary_wrapper">';
					}
					if ( 'story' === $bdp_theme ) {
						echo '<div class="story_wrapper">';
					}
					if ( 'brite' === $bdp_theme ) {
						echo '<div class="brite-wrapp">';
					}
					if ( 'foodbox' === $bdp_theme ) {
						echo '<div class="foodbox-blog-wrapp">';
					}
					if ( 'neaty_block' === $bdp_theme ) {
						echo '<div class="neaty_block_blog_wrapp">';
					}
					if ( 'wise_block' === $bdp_theme ) {
						echo '<div class="blog_template wise_block_wrapper">';
					}
					if ( 'ticker' === $bdp_theme ) {
						$ticker_effect            = isset( $bdp_settings['ticker_effect'] ) ? $bdp_settings['ticker_effect'] : 'fade';
						$ticker_autoplay_interval = isset( $bdp_settings['ticker_autoplay_interval'] ) ? $bdp_settings['ticker_autoplay_interval'] : '3000';
						$ticker_autoplay          = ( isset( $bdp_settings['ticker_autoplay'] ) && ( '1' == $bdp_settings['ticker_autoplay'] ) ) ? 'true' : 'false';
						$ticker_label_text        = isset( $bdp_settings['ticker_label_text'] ) ? $bdp_settings['ticker_label_text'] : esc_html_e( 'Latest Post', 'blog-designer-pro' );
						echo '<div class="blog-ticker-wrapper" id="blog-ticker-style-' . esc_attr( $ticker_effect ) . '" data-conf="{&quot;ticker_effect&quot;:&quot;' . esc_attr( $ticker_effect ) . '&quot;,&quot;autoplay&quot;:&quot;' . esc_attr( $ticker_autoplay ) . '&quot;,&quot;speed&quot;:' . esc_attr( $ticker_autoplay_interval ) . ',&quot;font_style&quot;:&quot;normal&quot;,&quot;scroll_speed&quot;:1}">';
						echo '<div class="ticker-title">
						<div class="ticker-style-title">' . esc_html( $ticker_label_text ) . '</div>
						<span></span>
						</div>';
						echo '<div class="blog-ticker-controls">
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-prev"></span></div>
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-next"></span></div>
						</div>';
						echo '<div class="blog-tickers">
						<ul>';
					}
					$display_tabbed_filter_by = 'category';
					if ( isset( $bdp_settings['display_tabbed_filter_by'] ) ) {
						$display_tabbed_filter_by = $bdp_settings['display_tabbed_filter_by'];
					}
					$tabbed_slug       = array();
					$tabbed_post_style = 0;
					$post_type         = get_post_type( $layout_id );
					if ( 'tabbed' === $bdp_theme ) {
						$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
						$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
						if ( ! empty( $terms ) ) {
							foreach ( $taxonomy_names as $taxonomy_name ) {
								if ( $taxonomy_name->name == $display_tabbed_filter_by ) {
									if ( isset( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] ) ) {
										$tax_count = count( $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ] );
										foreach ( $terms as $term ) {
											for ( $i = 0; $i < $tax_count; $i++ ) {
												if ( $term->name == $bdp_settings[ $taxonomy_name->name . '_tabbed_terms' ][ $i ] ) {
													$tabbed_slug[ $term->name ] = $term->slug;
												}
											}
										}
									}
								}
							}
							array_unique( $tabbed_slug );
						}
						if ( empty( $tabbed_slug ) ) {
							$terms          = get_terms( $display_tabbed_filter_by, array( 'hide_empty' => true ) );
							$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
							foreach ( $taxonomy_names as $taxonomy_name ) {
								foreach ( $terms as $term ) {
									$tabbed_slug[ $term->name ] = $term->slug;
								}
							}
							array_unique( $tabbed_slug );
						}
						wp_enqueue_script( 'jquery' );
						?>
						<script>
							jQuery(document).ready(function() {
								var $tabs = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs").tabs({
									activate: function(event, ui) {
										var active = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').tabs('option', 'active');
										var active_content = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs ul>li a").eq(active).attr("href");
										var leftContent = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').find(active_content + " .left-side.bdp-tabbed-all-post-content").outerHeight();
										var rightContent = jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> #tabs').find(active_content + " .right-side.bdp-tabbed-all-post-content").outerHeight();
										if (rightContent > leftContent) {
											jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', 'scroll');
											jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('max-height', leftContent);
										} else {
											jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .right-side.bdp-tabbed-all-post-content').css('overflow-y', '')
										}
									}
								});
								jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .ui-tabs-panel").each(function(i) {
									var totalSize = jQuery(".layout_id_<?php echo esc_attr( $layout_id ); ?> .ui-tabs-panel").size();
									if (i != totalSize) {
										next = i + 1;
										jQuery(this).append("<a href='#' class='next-tab mover' rel='" + next + "'><i class='fa fa-angle-right' aria-hidden='true'></i></a>");
									}
									if (i != totalSize) {
										prev = i - 1;
										jQuery(this).append("<a href='#' class='prev-tab mover' rel='" + prev + "'><i class='fa fa-angle-left' aria-hidden='true'></i></a>");
									}
								});
								jQuery('.layout_id_<?php echo esc_attr( $layout_id ); ?> .next-tab, .layout_id_<?php echo esc_attr( $layout_id ); ?> .prev-tab').on('click', function() {
									$tabs.tabs('option', 'active', jQuery(this).attr("rel"));
									return false
								});
							});
						</script>
						<?php

						echo '<div id="tabs">';
						if ( ! empty( $tabbed_slug ) ) {
							echo '<ul class="tabs">';
							foreach ( $tabbed_slug as $key => $val ) {
								echo '<li><a href="#' . esc_attr( $val ) . '">' . esc_attr( $val ) . '</a></li>';
							}
							echo '</ul>';
							$tabbi             = 1;
							$post_type         = $post_type;
							$bdp_tabbed_layout = 'left_side';
							if ( isset( $bdp_settings['bdp_tabbed_layout'] ) ) {
								$bdp_tabbed_layout = $bdp_settings['bdp_tabbed_layout'];
							}
							foreach ( $tabbed_slug as $key => $val ) {
								$tabbed_posts              = Bdp_Posts::get_wp_query( $bdp_settings );
								$tabbed_posts['tax_query'] = array(
									array(
										'taxonomy' => $display_tabbed_filter_by,
										'field'    => 'slug',
										'terms'    => $val,
									),
								);
								$tabbed                    = new WP_Query( $tabbed_posts );
								$post_count                = $tabbed->post_count;
								if ( $tabbed->have_posts() ) {
									$ti                = 1;
									$classes           = '';
									$tabbed_post_style = 0;
									$alter_class       = '';
									$prev_year         = '';
									$paged             = 0;
									$count_sticky      = '';
									$alter_val         = '';
									echo '<div id="' . esc_attr( $val ) . '" class="' . esc_attr( $classes ) . '">';
									while ( $tabbed->have_posts() ) :
										$tabbed->the_post();
										if ( 1 == $ti ) {
											echo '<div class="left-side bdp-tabbed-all-post-content ' . esc_attr( $bdp_tabbed_layout ) . '">';
										}
										if ( $ti > 1 ) {
											$tabbed_post_style = 1;
										}
										if ( 2 == $ti ) {
											echo '<div class="right-side bdp-tabbed-all-post-content ' . esc_attr( $bdp_tabbed_layout ) . '">';
										}
										Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
										do_action( 'bd_tabbed_archive_design_format_function', $bdp_theme, $bdp_settings, $alter_class, $prev_year, $paged, $count_sticky, $alter_val, $tabbed_post_style );
										echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $alter, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
										if ( 1 == $ti ) {
											echo '</div>';
										}

										++$ti;
									endwhile;
									if ( $post_count > 1 ) {
										echo '</div>';
									}
									echo '</div>';
								}

								wp_reset_postdata();
								++$tabbi;
							}
						}

						echo '</div>';
					}
					global $wp_query;
					$posts_per_page = $bdp_settings['posts_per_page'];
					$max_num_pages  = $wp_query->max_num_pages;
					$orderby        = 'date';
					$order          = 'DESC';
					if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
						$orderby = $bdp_settings['bdp_blog_order_by'];
					}
					if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) {
						$order = $bdp_settings['bdp_blog_order'];
					}
					$paged       = Bdp_Utility::paged();
					$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );

					if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
						if ( isset( $bdp_settings['template_category'][0] ) ) {
							$cat = $bdp_settings['template_category'][0];
						} else {
							$cat = '';
						}
						if ( 'meta_value_num' === $orderby ) {
							$orderby_str = $orderby . ' date';
						} else {
							$orderby_str = $orderby;
						}
						$arg_posts = array(
							'post_type'      => 'post',
							'posts_per_page' => $posts_per_page,
							'orderby'        => $orderby_str,
							'order'          => $order,
							'paged'          => $paged,
							'post_status'    => $post_status,
							'cat'            => $cat,
						);
						if ( 'meta_value_num' === $orderby ) {
							$arg_posts['meta_query'] = array(
								'relation' => 'OR',
								array(
									'key'     => '_post_like_count',
									'compare' => 'NOT EXISTS',
								),
								array(
									'key'     => '_post_like_count',
									'compare' => 'EXISTS',
								),
							);
						}
					} elseif ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
						if ( isset( $bdp_settings['template_tags'][0] ) ) {
							$tag = $bdp_settings['template_tags'][0];
						} else {
							$tag = '';
						}
						if ( 'meta_value_num' === $orderby ) {
							$orderby_str = $orderby . ' date';
						} else {
							$orderby_str = $orderby;
						}
						$arg_posts = array(
							'post_type'      => 'post',
							'posts_per_page' => $posts_per_page,
							'orderby'        => $orderby_str,
							'order'          => $order,
							'paged'          => $paged,
							'post_status'    => $post_status,
							'tag_id'         => $tag,
						);
						if ( 'meta_value_num' === $orderby ) {
							$arg_posts['meta_query'] = array(
								'relation' => 'OR',
								array(
									'key'     => '_post_like_count',
									'compare' => 'NOT EXISTS',
								),
								array(
									'key'     => '_post_like_count',
									'compare' => 'EXISTS',
								),
							);
						}
					} elseif ( 'date_template' === $bdp_settings['custom_archive_type'] ) {
						if ( 'meta_value_num' === $orderby ) {
							$orderby_str = $orderby . ' date';
						} else {
							$orderby_str = $orderby;
						}
						$arg_posts = array(
							'post_type'      => 'post',
							'posts_per_page' => $posts_per_page,
							'orderby'        => $orderby_str,
							'order'          => $order,
							'paged'          => $paged,
							'post_status'    => $post_status,
							'year'           => get_query_var( 'year' ),
							'monthnum'       => get_query_var( 'monthnum' ),
							'day'            => get_query_var( 'day' ),
						);
						if ( 'meta_value_num' === $orderby ) {
							$arg_posts['meta_query'] = array(
								'relation' => 'OR',
								array(
									'key'     => '_post_like_count',
									'compare' => 'NOT EXISTS',
								),
								array(
									'key'     => '_post_like_count',
									'compare' => 'EXISTS',
								),
							);
						}
					} else {
						if ( 'meta_value_num' === $orderby ) {
							$orderby_str = $orderby . ' date';
						} else {
							$orderby_str = $orderby;
						}
						$arg_posts = array(
							'post_type'      => 'post',
							'posts_per_page' => $posts_per_page,
							'orderby'        => $orderby_str,
							'order'          => $order,
							'paged'          => $paged,
							'post_status'    => $post_status,
						);
						if ( 'meta_value_num' === $orderby ) {
							$arg_posts['meta_query'] = array(
								'relation' => 'OR',
								array(
									'key'     => '_post_like_count',
									'compare' => 'NOT EXISTS',
								),
								array(
									'key'     => '_post_like_count',
									'compare' => 'EXISTS',
								),
							);
						}
					}
					if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
						$arg_posts['ignore_sticky_posts'] = 1;
					}
					if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
						$arg_posts['ignore_sticky_posts'] = 1;
					}
					$loop       = new WP_Query( $arg_posts );
					$temp_query = $wp_query;
					$wp_query   = null;
					$wp_query   = $loop;
					$prev_year1 = null;
					$prev_year  = null;
					$alter_val  = null;
					$prev_month = null;
					if ( $loop->have_posts() ) {
						// Start the loop.
						while ( have_posts() ) :
							the_post();
							if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = ' alternative-back ';
								} else {
									$alter_class = '';
								}
							}
							if ( 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'story' === $bdp_theme || 'fairy' === $bdp_theme || 'clicky' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'leest' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'text-top';
								} else {
									$alter_class = 'text-bottom';
								}
							}
							if ( 'timeline' === $bdp_theme ) {
								if ( 0 == $alter % 2 ) {
									$alter_class = 'even_class';
								} else {
									$alter_class = 'odd_class';
								}
							}
							if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
								$alter_class = $alter;
							}
							if ( $bdp_theme ) {
								if ( 'timeline' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
											$this_year = get_the_date( 'Y' );
											if ( $prev_year != $this_year ) {
												$prev_year = $this_year;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
											}
										} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
											$this_year  = get_the_date( 'Y' );
											$this_month = get_the_time( 'M' );
											$prev_year  = $this_year;
											if ( $prev_month != $this_month ) {
												$prev_month = $this_month;
												echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
											}
										}
									}
								}
								if ( 'story' === $bdp_theme ) {
									if ( 'date' === $orderby || 'modified' === $orderby ) {
										$this_year = get_the_date( 'Y' );
										if ( $prev_year1 != $this_year ) {
											$prev_year1 = $this_year;
											$prev_year  = 0;
										} elseif ( $prev_year1 == $this_year ) {
											$prev_year = 1;
										}
									} else {
										$prev_year = get_the_date( 'Y' );
									}
								}
								if ( 'media-grid' === $bdp_theme ) {
									$alter_val = $alter;
								}
								if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'clicky' === $bdp_theme ) {
									if ( 1 == $firstpost_unique_design ) {
										$alter_val = $alter;
										if ( 1 == $paged ) {
											if ( 1 == $alter ) {
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
									if ( 'media-grid' === $bdp_theme ) {
										$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2;
										$alter_val      = $alter;
										if ( 1 == $paged ) {
											if ( $column_setting >= 2 && $alter <= 2 ) {
												$prev_year = 0;
											} elseif ( 1 == $alter ) {
													$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										} else {
											$prev_year = 1;
										}
									}
								}
							}
							// Include the single post content template.
							Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
							do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
							if ( 'tabbed' != $bdp_theme ) {
								echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $bdp_settings, $alter, $bdp_theme, $paged ), Bdp_Admin_Functions::args_kses() );
							}
							++$alter;
							// End of the loop.
						endwhile;
						if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'colorful_sliding' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'blog_carousel' === $bdp_theme ) {
							echo '</ul></div>';
						}
						if ( 'threed_carousel' === $bdp_theme ) {
							echo '</ul></div>';
						}
						if ( 'flip_book_3d' === $bdp_theme ) {
							$display_slider_controls = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 'arrow1';
							if ( 1 == $display_slider_controls ) {
								$pre  = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
								$next = ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? $bdp_settings['arrow_style_hidden'] : 'portfolio-slick-prev';
							}

							echo '<nav><span id="bb-nav-prev" aria-label="Previous" tabindex="0" role="button"  data-role="none"  class="bb-custom-icon bb-custom-icon-arrow-left bd-arrows left bd-left-arrow  ' . esc_attr( $pre ) . ' "></span> <span id="bb-nav-next" aria-label="Previous" tabindex="0" role="button" data-role="none" class="bb-custom-icon bb-custom-icon-arrow-right bd-arrows right bd-right-arrow  ' . esc_attr( $next ) . '" ></span></nav></div></div>';
						}
						if ( 'foodbox' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'neaty_block' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'leest' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'wise_block' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'timeline' === $bdp_theme ) {
							echo '</div></div>';
						}
						if ( 'easy_timeline' === $bdp_theme || 'steps' === $bdp_theme ) {
							echo '</div></ul>';
						}
						if ( 'accordion' === $bdp_theme || 'offer_blog' === $bdp_theme || 'winter' === $bdp_theme || 'my_diary' === $bdp_theme || 'story' === $bdp_theme || 'brite' === $bdp_theme || 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
							echo '</div>';
						}
						if ( 'ticker' === $bdp_theme ) {
							echo '</ul></div></div>';
						}
						if ( 'banner' === $bdp_theme ) {
							echo '</div></div>';
						}
						$slider_array = array( 'crayon_slider', 'sunshiny_slider', 'sallet_slider', 'colorful_sliding', 'blog_carousel', 'threed_carousel', 'flip_book_3d', 'leest' );
						if ( in_array( $bdp_theme, $slider_array ) ) {

							if ( ! wp_script_is( 'bdp-galleryimage-script', $list = 'enqueued' ) ) {
								wp_enqueue_script( 'bdp-galleryimage-script' );
							}

							$post_number               = isset( $bdp_settings['bdp_number_post'] ) ? $bdp_settings['bdp_number_post'] : 5;
							$width                     = isset( $bdp_settings['slider_custom_width'] ) ? $bdp_settings['slider_custom_width'] : 400;
							$height                    = isset( $bdp_settings['slider_custom_height'] ) ? $bdp_settings['slider_custom_height'] : 400;
							$distance                  = isset( $bdp_settings['slider_distance'] ) ? $bdp_settings['slider_distance'] : 150;
							$template_slider_scroll    = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : 1;
							$display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : 1;
							$display_slider_controls   = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 1;
							$slider_autoplay           = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : 1;
							$slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : 7000;
							$slider_speed              = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : 600;
							$template_slider_effect    = isset( $bdp_settings['template_slider_effect'] ) ? $bdp_settings['template_slider_effect'] : 'slide';
							$slider_column             = 1;
							if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
								$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
								$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
								$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
								$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;
							} else {
								$slider_column        = 1;
								$slider_column_ipad   = 1;
								$slider_column_tablet = 1;
								$slider_column_mobile = 1;
							}
							$slider_arrow = isset( $bdp_settings['arrow_style_hidden'] ) ? $bdp_settings['arrow_style_hidden'] : 'arrow1';
							if ( '' == $slider_arrow ) {
								$prev = "<i class='fas fa-chevron-left'></i>";
								$next = "<i class='fas fa-chevron-right'></i>";
							} else {
								$prev = "<div class='" . esc_attr( $slider_arrow ) . "'></div>";
								$next = "<div class='" . esc_attr( $slider_arrow ) . "'></div>";
							}

							if ( 1 == $display_slider_controls ) {
								?>
								<span data-role="none" class="pd-arrows left pd-left-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>
								<span data-role="none" class="pd-arrows right pd-right-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>
								<?php
							}

							?>
							<script type="text/javascript" class="dynamic_script">
								<?php if ( 'threed_carousel' === $bdp_theme ) { ?>
									jQuery(document).ready(function() {

										jQuery('.threed_carousel .bdp-3d-container').carousel({
											num: 5,
											maxWidth: 400,
											maxHeight: 400,
											distance: 120,
											scale: 0.6,
											<?php if ( $slider_autoplay ) { ?>
												autoPlay: true,
											<?php } else { ?>
												autoPlay: false,
											<?php } ?>
											<?php if ( $slider_autoplay ) { ?>
												showTime: <?php echo esc_attr( $slider_autoplay_intervals ); ?>,
											<?php } ?>
											<?php if ( $slider_speed ) { ?>
												animationTime: <?php echo esc_attr( $slider_speed ); ?>,
											<?php } ?>
											prevArrow: '<span data-role="none" class="bd-arrows bd-left-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-prev'; ?>" aria-label="Previous" tabindex="0" role="button"></span>',
											nextArrow: '<span data-role="none" class="bd-arrows bd-right-arrow <?php echo ( isset( $bdp_settings['arrow_style_hidden'] ) ) ? esc_attr( $bdp_settings['arrow_style_hidden'] ) : 'portfolio-slick-next'; ?>" aria-label="Next" tabindex="0" role="button"></span>',

										});
									});
									<?php
								} elseif ( 'flip_book_3d' === $bdp_theme ) {
									wp_enqueue_script( 'bdp-admin-modernize-custom-script', plugins_url( 'js/modernizr.custom.js', __FILE__ ), array( 'jquery' ), '2.6.2', true );
									wp_enqueue_script( 'bdp-admin-bookblock-script', plugins_url( 'js/jquery.bookblock.js', __FILE__ ), array( 'jquery' ), '2.0.1', true );
									?>
									jQuery(document).ready(function($) {
										var Page = (function() {
											var config = {
													$bookBlock: $('.bdp_flip_book_3d'),
													$navNext: $('#bb-nav-next'),
													$navPrev: $('#bb-nav-prev'),
													$navFirst: $('#bb-nav-first'),
													$navLast: $('#bb-nav-last')
												},

												init = function() {
													config.$bookBlock.bookblock({
														speed: 1000,
														shadowSides: 0.8,
														shadowFlip: 0.7
													});
													initEvents();
												},
												initEvents = function() {

													var $slides = config.$bookBlock.children();

													// add navigation events
													config.$navNext.on('click touchstart', function() {
														config.$bookBlock.bookblock('next');
														return false;
													});

													config.$navPrev.on('click touchstart', function() {
														config.$bookBlock.bookblock('prev');
														return false;
													});

													config.$navFirst.on('click touchstart', function() {
														config.$bookBlock.bookblock('first');
														return false;
													});

													config.$navLast.on('click touchstart', function() {
														config.$bookBlock.bookblock('last');
														return false;
													});

													// add swipe events
													$slides.on({
														'swipeleft': function(event) {
															config.$bookBlock.bookblock('next');
															return false;
														},
														'swiperight': function(event) {
															config.$bookBlock.bookblock('prev');
															return false;
														}
													});

													// add keyboard events
													$(document).keydown(function(e) {
														var keyCode = e.keyCode || e.which,
															arrow = {
																left: 37,
																up: 38,
																right: 39,
																down: 40
															};

														switch (keyCode) {
															case arrow.left:
																config.$bookBlock.bookblock('prev');
																break;
															case arrow.right:
																config.$bookBlock.bookblock('next');
																break;
														}
													});
												};

											return {
												init: init
											};
										})(jQuery);
										Page.init();
									});
									<?php
								} elseif ( 'leest' === $bdp_theme ) {
									?>
										$(".slick-slider").slick({
										nextArrow: '<i class="fa fa-arrow-right"></i>',
										prevArrow: '<i class="fa fa-arrow-left"></i>',
										slidesToShow: <?php echo esc_attr( $slider_column ); ?>,
										infinite: false,
										slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
										<?php echo ( 1 == $template_slider_effect ) ? 'fade: true,' : 'fade: false,'; ?>
										<?php echo ( $slider_speed ) ? 'speed:' . esc_attr( $slider_speed ) . ',' : ''; ?>
										<?php echo ( 1 == $slider_autoplay ) ? 'autoplay: true,' : 'autoplay: false,'; ?>
										<?php echo ( 1 == $slider_autoplay ) ? 'autoplaySpeed:' . esc_attr( $slider_autoplay_intervals ) . ',' : ''; ?>									
										<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true,' : 'dots: false,'; ?>
										<?php echo ( 1 == $display_slider_controls ) ? 'arrows: true,' : 'arrows: false,'; ?>
										responsive: [
										{
											breakpoint: 980,
											settings: {
											slidesToShow: <?php echo esc_attr( $slider_column_ipad ); ?>,
											slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
											infinite: true,
										<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
											}
										},
										{
											breakpoint: 720,
											settings: {
											slidesToShow: <?php echo esc_attr( $slider_column_tablet ); ?>,
											slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
											infinite: true,
										<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
											}
										},
										{
											breakpoint: 480,
											settings: {
											slidesToShow: <?php echo esc_attr( $slider_column_mobile ); ?>,
											slidesToScroll: <?php echo esc_attr( $template_slider_scroll ); ?>,
											infinite: true,
										<?php echo ( 1 == $display_slider_navigation ) ? 'dots: true' : 'dots: false'; ?>
											}
										}
										]
										});
										jQuery('.leest-wapper .slick-slider').each(function(){
											var mainheight = 0;
											var mainparent = jQuery(this);
											mainparent.find('.card-box-img').each(function(){
												var newheight = jQuery(this).innerHeight();
												if( newheight > mainheight){ mainheight = newheight; }
											});
											mainparent.find('.card-box-img').css('min-height', mainheight);
											var height = 0;
											mainparent.find('.card-box-text').each(function(){
												var newheight = jQuery(this).innerHeight();
												if( newheight > height){ height = newheight; }
											});
											mainparent.find('.card-box-text').css('min-height', height);
										});
								<?php	} else { ?>
									jQuery(document).ready(function() {
										var $maxItems = 1;
										if (jQuery(window).width() > 980) {
											$maxItems = <?php echo esc_attr( $slider_column ); ?>;
										}
										elseif(jQuery(window).width() <= 980 && jQuery(window).width() > 720) {
											$maxItems = <?php echo esc_attr( $slider_column_ipad ); ?>;
										}
										elseif(jQuery(window).width() <= 720 && jQuery(window).width() > 480) {
											$maxItems = <?php echo esc_attr( $slider_column_tablet ); ?>;
										}
										elseif(jQuery(window).width() <= 480) {
											$maxItems = <?php echo esc_attr( $slider_column_mobile ); ?>;
										}
										jQuery('.slider_' + <?php echo esc_attr( $unique_id ); ?>).flexslider({
											move: <?php echo esc_attr( $template_slider_scroll ); ?>,
											animation: '<?php echo esc_attr( $template_slider_effect ); ?>',
											itemWidth: 10,
											itemMargin: 15,
											minItems: 1,
											maxItems: $maxItems,
											rtl: 
											<?php
											if ( is_rtl() ) {
												echo 1;
											} else {
												echo 0;
											}
											?>
													,
											<?php if ( $display_slider_navigation ) { ?>
												directionNav: true,
											<?php } else { ?>
												directionNav: false,
											<?php } ?>
											<?php if ( $display_slider_controls ) { ?>
												controlNav: true,
											<?php } else { ?>
												controlNav: false,
											<?php } ?>
											<?php if ( $slider_autoplay ) { ?>
												slideshow: true,
											<?php } else { ?>
												slideshow: false,
											<?php } ?>
											<?php if ( $slider_autoplay ) { ?>
												slideshowSpeed: <?php echo esc_attr( $slider_autoplay_intervals ); ?>,
											<?php } ?>
											<?php if ( $slider_speed ) { ?>
												animationSpeed: <?php echo esc_attr( $slider_speed ); ?>,
											<?php } ?>
											prevText: "<?php echo esc_attr( $prev ); ?>",
											nextText: "<?php echo esc_attr( $next ); ?>"
										});
									});
								<?php } ?>
							</script>
							<?php

						}
						if ( ! in_array( $bdp_theme, $slider_array ) && isset( $bdp_settings['pagination_type'] ) && 'paged' == $bdp_settings['pagination_type'] ) {
							$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
							echo '<div class="wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
							echo wp_kses( Bdp_Posts::standard_paging_nav( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							echo '</div>';
						} elseif ( ! in_array( $bdp_theme, $slider_array ) && isset( $bdp_settings['pagination_type'] ) && 'load_more_btn' == $bdp_settings['pagination_type'] ) {
							echo "<div class='bdp-load-more-pre'>";
							$is_loadmore_btn = '';
							// if ( $max_num_pages > 1 ) {
							// $is_loadmore_btn = '';
							// } else {
							// $is_loadmore_btn = '1';
							// }.
							if ( is_front_page() ) {
								$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
							} else {
								$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							}
							echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';
							echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
							echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
							echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
							echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
							echo '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
							echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $layout_id ) . '" />';
							if ( 'timeline' === $bdp_theme ) {
								echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
								echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
							}
							echo wp_kses( Bdp_Utility::get_loader( $bdp_settings ), Bdp_Admin_Functions::args_kses() );
							echo '</form>';
							if ( '' == $is_loadmore_btn ) {
								$class = isset( $bdp_settings['load_more_button_template'] ) ? $bdp_settings['load_more_button_template'] : 'template-1';
								echo '<div class="bdp-load-more text-center" style="float:left;width:100%">';
								echo '<a href="javascript:void(0);" class="button bdp-load-more-btn ' . esc_attr( $class ) . '">';
								if ( 'template-3' === $class ) {
									echo '<span class="bdp-lmb-top"></span>';
								}
								echo isset( $bdp_settings['loadmore_button_text'] ) && '' != $bdp_settings['loadmore_button_text'] ? esc_html( $bdp_settings['loadmore_button_text'] ) : esc_html__( 'Load More', 'blog-designer-pro' );
								if ( 'template-3' === $class ) {
									echo '<span class="bdp-lmb-bottom"></span>';
								}
								echo '</a>';
								echo '</div>';
							}
							echo '</div>';
						} elseif ( ! in_array( $bdp_theme, $slider_array ) && isset( $bdp_settings['pagination_type'] ) && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) {
							echo '</div>';
							$is_load_onscroll_btn = '';
							// if ( $max_num_pages > 1 ) {
							// $is_load_onscroll_btn = '';
							// } else {
							// $is_load_onscroll_btn = '1';
							// }.
							if ( is_front_page() ) {
								$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
							} else {
								$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
							}
							echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';

							echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
							if ( 'story' === $bdp_theme ) {
								echo '<input type="hidden" name="this_year" id="this_year" value="' . esc_attr( $this_year ) . '" />';
							}
							echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
							echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
							echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
							echo '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';
							echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $layout_id ) . '" />';
							if ( 'timeline' === $bdp_theme ) {
								echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
								echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
							}
							echo wp_kses( Bdp_Utility::get_loader( $bdp_settings ), Bdp_Admin_Functions::args_kses() );

							echo '</form>';
							if ( '' == $is_load_onscroll_btn ) {
								$class = '';
								echo '<div class="bdp-load-onscroll text-center">';
								echo '<a href="javascript:void(0);" class="button bdp-load-onscroll-btn ' . esc_attr( $class ) . '">';
								echo esc_html__( 'Loading Posts', 'blog-designer-pro' ) . '</a>';
								echo '</div>';
							}
						}
					} else {
						esc_html_e( 'No posts found', 'blog-designer-pro' );
					}
					wp_reset_postdata();
					$wp_query = null;
					$wp_query = $temp_query;
					?>
				</div>
				<?php
			}
			exit();
		}
	}
	/**
	 * Ajax handler for Store closed box id
	 *
	 * @return void
	 */
	public function bdp_closed_bdpboxes() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$closed = isset( $_POST['closed'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_POST['closed'] ) ) ) : array();
			$closed = array_filter( $closed );
			$page   = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
			if ( sanitize_key( $page ) != $page ) {
				wp_die( 0 );
			}
			$user = wp_get_current_user();
			if ( ! $user ) {
				wp_die( -1 );
			}
			if ( is_array( $closed ) ) {
				update_user_option( $user->ID, "bdpclosedbdpboxes_$page", $closed, true );
			}
			wp_die( 1 );
		}
	}
	/**
	 * Admin notice layouts notice dismiss
	 *
	 * @since 1.6
	 */
	public static function bdp_admin_notice_pro_layouts_dismiss() {
		update_option( 'bdp_admin_notice_pro_layouts_dismiss', true );
	}
	/**
	 * Admin notice layouts transfer notice dismiss
	 *
	 * @since 1.6
	 */
	public static function bdp_create_layout_from_blog_designer_dismiss() {
		update_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss', true );
	}
	/**
	 * Blog Template Search Result
	 *
	 * @since 1.6
	 */
	public function bdp_blog_template_search_result() {
		if ( isset( $_POST['ajaxnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajaxnonce'] ) ), 'ajax-nonce' ) ) {
			$template_name = isset( $_POST['temlate_name'] ) ? strtolower( sanitize_text_field( wp_unslash( $_POST['temlate_name'] ) ) ) : '';
			$tempate_list  = Bdp_Template::blog_template_list();
			foreach ( $tempate_list as $key => $value ) {
				if ( '' == $template_name ) {
					?>
					<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" 
						<?php
						echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : '';
						?>
						>
						<div class="template-thumbnail-inner">
							<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<div class="hover_overlay">
								<div class="popup-template-name">
									<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
									<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
								</div>
							</div>
						</div>
						<span class="bdp-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
					</div>
					<?php
				} elseif ( preg_match( '/' . trim( $template_name ) . '/', $key ) ) {
					?>
					<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" 
						<?php
						echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : '';
						?>
						>
						<div class="template-thumbnail-inner">
							<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<div class="hover_overlay">
								<div class="popup-template-name">
									<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
									<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
								</div>
							</div>
						</div>
						<span class="bdp-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
					</div>
					<?php
				}
			}
			exit();
		}
	}

	/**
	 * Single Blog Template Search Result
	 *
	 * @since 1.6
	 */
	public function bdp_single_blog_template_search_result() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
			$template_name = isset( $_POST['temlate_name'] ) ? sanitize_text_field( wp_unslash( $_POST['temlate_name'] ) ) : '';
			$tempate_list  = Bdp_Template::single_blog_template_list();
			foreach ( $tempate_list as $key => $value ) {
				if ( '' == $template_name ) {
					?>
					<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" 
						<?php
						echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : '';
						?>
						>
						<div class="template-thumbnail-inner">
							<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<div class="hover_overlay">
								<div class="popup-template-name">
									<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
									<div class="popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
								</div>
							</div>
						</div>
						<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
					</div>
					<?php
				} elseif ( preg_match( '/' . trim( $template_name ) . '/', $key ) ) {
					?>
					<div class="template-thumbnail <?php echo esc_attr( esc_attr( $value['class'] ) ); ?>" 
						<?php
						echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : '';
						?>
						>
						<div class="template-thumbnail-inner">
							<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<div class="hover_overlay">
								<div class="popup-template-name">
									<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
									<div class="popup-view"><a href="<?php echo esc_html( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
								</div>
							</div>
						</div>
						<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
					</div>
					<?php
				}
			}
			exit();
		}
	}
	/**
	 * Admin notice template outdated notice dismiss
	 *
	 * @since 2.0
	 */
	public function bdp_notice_template_outdated_dismiss() {
		update_option( 'bdp_template_outdated', 1 );
	}
}
new Bdp_Ajax_Actions();
