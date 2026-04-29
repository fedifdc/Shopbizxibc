<?php
/**
 * The admin-facing functionality of the plugin.
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
 * @class   Bdp_Author
 * @version 1.0.0
 */
class Bdp_Author {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_image' ), 5, 2 );
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_name' ), 15, 4 );
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_content_cover_start' ), 10, 2 );
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_biography' ), 20, 2 );
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_social_links' ), 25, 4 );
		add_action( 'bdp_author_archive_detail', array( $this, 'display_author_content_cover_end' ), 30, 2 );
		add_filter( 'user_contactmethods', array( $this, 'author_social_links' ), 12, 1 );
		add_filter( 'coauthors_posts_link', array( $this, 'co_authors_posts_link' ) );
	}
	/**
	 * Function Display Author Image Using Action
	 *
	 * @param string $template template.
	 */
	public static function display_author_image( $template ) {
		$authorid    = get_the_author_meta( 'ID' );
		$author_link = get_author_posts_url( $authorid );
		?>
		<div class="avtar-img">
			<a href="<?php echo esc_url( $author_link ); ?>">
				<?php
				if ( 'brite' === $template ) {
					echo get_avatar( $authorid, 112 );
				} else {
					echo get_avatar( $authorid, 150 );
				}
				?>
			</a>
		</div>
		<?php
	}
	/**
	 * Function Display Author Name Using Action
	 *
	 * @param string $template template.
	 * @param html   $biography biography.
	 * @param string $title title.
	 * @param html   $bdp_settings settings.
	 * @return void
	 */
	public static function display_author_name( $template, $biography, $title, $bdp_settings ) {
		if ( ! empty( $title ) ) {
			$disable_link = isset( $bdp_settings['disable_link_author_div'] ) ? $bdp_settings['disable_link_author_div'] : 0;

			?>
			<span class="author">
				<?php
				if ( is_admin() ) {
					if ( is_user_logged_in() ) {
						$current_user_name = wp_get_current_user();
						$current_author    = $current_user_name->user_login;
						$title             = str_replace( '[author]', $current_author, $title );
						$cid               = get_current_user_id();
						$text              = $title;
						if ( 1 == $disable_link ) {
							$replace = get_the_author();
						} else {
							$author_id   = $cid;
							$author_link = get_author_posts_url( $author_id );
							$replace     = '<a href="' . $author_link . '">' . get_the_author() . '</a>';
						}
						echo wp_kses( str_replace( $title, $replace, $text ), Bdp_Admin_Functions::args_kses() );
					}
				}

				if ( is_author() ) {
					global $wp_query;
					$post_type_object = $wp_query->query;
					$authorid         = $post_type_object['author__in'];

					$text = $title;
					if ( 1 == $disable_link ) {
						$replace = get_the_author_meta( 'display_name', $authorid );
					} else {
						$replace = '<a href="' . get_author_posts_url( $authorid ) . '">' . get_the_author_meta( 'display_name', $authorid ) . '</a>';
					}
					echo esc_html( str_replace( '[author]', $replace, $text ) );
				} else {
					$text = $title;
					if ( 1 == $disable_link ) {
						$replace = get_the_author();
					} else {
						$author_id   = get_the_author_meta( 'ID' );
						$author_link = get_author_posts_url( $author_id );
						$replace     = '<a href="' . $author_link . '">' . get_the_author() . '</a>';
					}
					echo wp_kses( str_replace( '[author]', $replace, $text ), Bdp_Admin_Functions::args_kses() );
				}
				?>
			</span>
			<?php
		}
	}
	/**
	 * Function Display Author Biography Cover Div Start Using Action
	 *
	 * @param string $template template.
	 * @return void
	 */
	public static function display_author_content_cover_start( $template ) {
		echo '<div class="author_content">';
	}
	/**
	 * Function Display Author Biography Using Action
	 *
	 * @param string  $template template.
	 * @param boolean $display_author_biography bigogaraphy.
	 * @return void
	 */
	public static function display_author_biography( $template, $display_author_biography ) {
		if ( is_admin() ) {
			if ( is_user_logged_in() ) {
				$cid = get_current_user_id();
				if ( 1 == $display_author_biography ) {
					// global $wp_query;
					// $authorid = '';
					// $post_type_object = $wp_query->query;
					// if(!empty($cid)){
					// $authorid = $post_type_object['author__in'];
					// }.
					$description = get_the_author_meta( 'description', $cid );
					$description = apply_filters( 'bdp_author_bio', $description, $cid );
					if ( '' != $description ) {
						?>
						<p>
						<?php
						echo wp_kses( wpautop( $description ), Bdp_Admin_Functions::args_kses() );
						?>
							</p>
						<?php
					}
				}
			}
		}
		if ( 1 == $display_author_biography ) {
			global $wp_query;
			$authorid         = '';
			$post_type_object = $wp_query->query;
			if ( ! empty( $author_id ) ) {
				$authorid = $post_type_object['author__in'];
			}
			$description = get_the_author_meta( 'description', $authorid );
			$description = apply_filters( 'bdp_author_bio', $description, $authorid );
			if ( '' != $description ) {
				?>
				<p>
				<?php
				echo wp_kses( wpautop( $description ), Bdp_Admin_Functions::args_kses() );
				?>
					</p>
				<?php
			}
		}
	}
	/**
	 * Add social media links of author display in single post page
	 *
	 * @param string  $bdp_theme theme.
	 * @param boolean $display_author_biography bigraphy.
	 * @param string  $txt_author_title author title.
	 * @param array   $bdp_settings settings.
	 * @return void
	 */
	public static function display_author_social_links( $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings ) {
		if ( is_admin() ) {
			if ( is_user_logged_in() ) {
				$cid                = get_current_user_id();
				$enable_share_links = isset( $bdp_settings['display_author_social'] ) && 0 == $bdp_settings['display_author_social'] ? false : true;
				if ( $enable_share_links ) {
					$enable_email     = ( isset( $bdp_settings['author_email_link'] ) && 1 == $bdp_settings['author_email_link'] ) ? true : false;
					$enable_website   = ( isset( $bdp_settings['author_website_link'] ) && 0 == $bdp_settings['author_website_link'] ) ? false : true;
					$enable_facebook  = ( isset( $bdp_settings['author_facebook_link'] ) && 0 == $bdp_settings['author_facebook_link'] ) ? false : true;
					$enable_twitter   = ( isset( $bdp_settings['author_twitter_link'] ) && 0 == $bdp_settings['author_twitter_link'] ) ? false : true;
					$enable_linkedin  = ( isset( $bdp_settings['author_linkedin_link'] ) && 0 == $bdp_settings['author_linkedin_link'] ) ? false : true;
					$enable_youtube   = ( isset( $bdp_settings['author_youtube_link'] ) && 0 == $bdp_settings['author_youtube_link'] ) ? false : true;
					$enable_pinterest = ( isset( $bdp_settings['author_pinterest_link'] ) && 0 == $bdp_settings['author_pinterest_link'] ) ? false : true;
					$enable_instagram = ( isset( $bdp_settings['author_instagram_link'] ) && 0 == $bdp_settings['author_instagram_link'] ) ? false : true;
					$enable_reddit    = ( isset( $bdp_settings['author_reddit_link'] ) && 1 == $bdp_settings['author_reddit_link'] ) ? true : false;
					$enable_pocket    = ( isset( $bdp_settings['author_pocket_link'] ) && 1 == $bdp_settings['author_pocket_link'] ) ? true : false;
					$enable_skype     = ( isset( $bdp_settings['author_skype_link'] ) && 1 == $bdp_settings['author_skype_link'] ) ? true : false;
					$enable_wordpress = ( isset( $bdp_settings['author_wordpress_link'] ) && 1 == $bdp_settings['author_wordpress_link'] ) ? true : false;
					$enable_vine      = ( isset( $bdp_settings['author_vine_link'] ) && 1 == $bdp_settings['author_vine_link'] ) ? true : false;
					$enable_tumblr    = ( isset( $bdp_settings['author_tumblr_link'] ) && 1 == $bdp_settings['author_tumblr_link'] ) ? true : false;
					$website          = esc_url( get_the_author_meta( 'url', $cid ) );
					$email            = sanitize_email( get_the_author_meta( 'email', $cid ) );
					$facebook         = esc_url( get_the_author_meta( 'facebook', $cid ) );
					$twitter          = esc_url( get_the_author_meta( 'twitter', $cid ) );
					$linkedin         = esc_url( get_the_author_meta( 'linkedin', $cid ) );
					$youtube          = esc_url( get_the_author_meta( 'youtube', $cid ) );
					$pinterest        = esc_url( get_the_author_meta( 'pinterest', $cid ) );
					$instagram        = esc_url( get_the_author_meta( 'instagram', $cid ) );
					$reddit           = esc_url( get_the_author_meta( 'reddit', $cid ) );
					$pocket           = esc_url( get_the_author_meta( 'pocket', $cid ) );
					$skype            = esc_url( get_the_author_meta( 'skype', $cid ) );
					$wordpress        = esc_url( get_the_author_meta( 'WordPress', $cid ) );
					$vine             = esc_url( get_the_author_meta( 'vine', $cid ) );
					$tumblr           = esc_url( get_the_author_meta( 'tumblr', $cid ) );

					if ( ( ! empty( $facebook ) && $enable_facebook ) || ( ! empty( $twitter ) && $enable_twitter ) || ( ! empty( $linkedin ) && $enable_linkedin ) || ( ! empty( $website ) && $enable_website ) || ( ! empty( $email ) && $enable_email ) || ( ! empty( $youtube ) && $enable_youtube ) || ( ! empty( $pinterest ) && $enable_pinterest ) || ( ! empty( $instagram ) && $enable_instagram ) || ( ! empty( $reddit ) && $enable_reddit ) || ( ! empty( $pocket ) && $enable_pocket ) || ( ! empty( $skype ) && $enable_skype ) || ( ! empty( $vine ) && $enable_vine ) || ( ! empty( $tumblr ) && $enable_tumblr ) || ( ! empty( $wordpress ) && $enable_wordpress ) ) {

						$social_theme = ' default_social_style_1 ';
						if ( isset( $bdp_settings['default_icon_theme'] ) && isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							$social_theme = ' default_social_style_' . $bdp_settings['default_icon_theme'] . ' ';
						}
						?>
						<div class="social-component
						<?php
						echo esc_attr( $social_theme );
						if ( isset( $bdp_settings['social_style'] ) && 0 == $bdp_settings['social_style'] ) {
							if ( isset( $bdp_settings['social_icon_size'] ) && 0 == $bdp_settings['social_icon_size'] ) {
								echo ' large ';
							} elseif ( isset( $bdp_settings['social_icon_size'] ) && 2 == $bdp_settings['social_icon_size'] ) {
								echo ' extra_small ';
							}
						}
						?>
				">
						<?php if ( ! empty( $facebook ) && $enable_facebook ) { ?>
							<?php
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" class="bdp-facebook-share social-share-default"></a>
								<?php
							} else {
								?>
							<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" class="bdp-facebook-share facebook-share social-share-custom"><i class="fab fa-facebook-f"></i></a>
								<?php
							}
						}
						if ( ! empty( $linkedin ) && $enable_linkedin ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" class="bdp-linkedin-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" class="bdp-linkedin-share social-share-custom">
								<i class="fab fa-linkedin-in"></i>
							</a>
								<?php
						}
						?>
							<?php
						}
						if ( ! empty( $twitter ) && $enable_twitter ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" class="bdp-twitter-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" class="bdp-twitter-share social-share-custom"><i class="fab fa-x-twitter"></i></a>
								<?php
						}
						}
						if ( ! empty( $email ) && $enable_email ) {
							?>
							<?php
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo 'mailto:' . esc_attr( $email ); ?>" target="_blank" class="bdp-email-share social-share-default"></a>
							<?php } else { ?>
							<a href="<?php echo 'mailto:' . esc_attr( $email ); ?>" target="_blank" class="bdp-email-share social-share-custom"><i class="far fa-envelope-open"></i></a>
								<?php
							}
						}
						if ( ! empty( $website ) && $enable_website ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $website ); ?>" target="_blank" class="bdp-website-share social-share-default"></a>
								<?php
							} else {
								?>
							<a href="<?php echo esc_url( $website ); ?>" target="_blank" class="bdp-website-share social-share-custom"><i class="fas fa-globe"></i></a>
								<?php
							}
						}
						if ( ! empty( $youtube ) && $enable_youtube ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" class="bdp-youtube-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" class="bdp-youtube-share social-share-custom"><i class="fab fa-youtube"></i></a>
								<?php
						}
						}
						if ( ! empty( $pinterest ) && $enable_pinterest ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" class="bdp-pinterest-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" class="bdp-pinterest-share social-share-custom">
								<i class="fab fa-pinterest-p"></i>
							</a>
								<?php
						}
						}
						if ( ! empty( $instagram ) && $enable_instagram ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" class="bdp-instagram-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" class="bdp-instagram-share social-share-custom"><i class="fab fa-instagram"></i></a>
								<?php
						}
						}
						if ( ! empty( $reddit ) && $enable_reddit ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $reddit ); ?>" target="_blank" class="bdp-reddit-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $reddit ); ?>" target="_blank" class="bdp-reddit-share social-share-custom"><i class="fab fa-reddit-alien"></i></a>
								<?php
						}
						}
						if ( ! empty( $pocket ) && $enable_pocket ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $pocket ); ?>" target="_blank" class="bdp-pocket-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $pocket ); ?>" target="_blank" class="bdp-pocket-share social-share-custom"><i class="fab fa-get-pocket"></i></a>
								<?php
						}
						}
						if ( ! empty( $skype ) && $enable_skype ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $skype ); ?>" target="_blank" class="bdp-skype-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $skype ); ?>" target="_blank" class="bdp-skype-share social-share-custom"><i class="fab fa-skype"></i></a>
								<?php
						}
						}
						if ( ! empty( $wordpress ) && $enable_wordpress ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $wordpress ); ?>" target="_blank" class="bdp-wordpress-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $wordpress ); ?>" target="_blank" class="bdp-wordpress-share social-share-custom"><i class="fab fa-wordpress"></i></a>
								<?php
						}
						}
						if ( ! empty( $snapchat ) && $enable_snapchat ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $snapchat ); ?>" target="_blank" class="bdp-snapchat-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $snapchat ); ?>" target="_blank" class="bdp-snapchat-share social-share-custom"><i class="fab fa-snapchat-ghost"></i></a>
								<?php
						}
						}
						if ( ! empty( $vine ) && $enable_vine ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $vine ); ?>" target="_blank" class="bdp-vine-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $vine ); ?>" target="_blank" class="bdp-vine-share social-share-custom"><i class="fab fa-vine"></i></a>
								<?php
						}
						}
						if ( ! empty( $tumblr ) && $enable_tumblr ) {
							if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
								?>
							<a href="<?php echo esc_url( $tumblr ); ?>" target="_blank" class="bdp-tumblr-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $tumblr ); ?>" target="_blank" class="bdp-tumblr-share social-share-custom"><i class="fab fa-tumblr"></i></a>
								<?php
						}
						}
						?>
				</div>
						<?php
					}
				}
			}
		}
		$enable_share_links = isset( $bdp_settings['display_author_social'] ) && 0 == $bdp_settings['display_author_social'] ? false : true;
		if ( $enable_share_links ) {
			$enable_email     = ( isset( $bdp_settings['author_email_link'] ) && 1 == $bdp_settings['author_email_link'] ) ? true : false;
			$enable_website   = ( isset( $bdp_settings['author_website_link'] ) && 0 == $bdp_settings['author_website_link'] ) ? false : true;
			$enable_facebook  = ( isset( $bdp_settings['author_facebook_link'] ) && 0 == $bdp_settings['author_facebook_link'] ) ? false : true;
			$enable_twitter   = ( isset( $bdp_settings['author_twitter_link'] ) && 0 == $bdp_settings['author_twitter_link'] ) ? false : true;
			$enable_linkedin  = ( isset( $bdp_settings['author_linkedin_link'] ) && 0 == $bdp_settings['author_linkedin_link'] ) ? false : true;
			$enable_youtube   = ( isset( $bdp_settings['author_youtube_link'] ) && 0 == $bdp_settings['author_youtube_link'] ) ? false : true;
			$enable_pinterest = ( isset( $bdp_settings['author_pinterest_link'] ) && 0 == $bdp_settings['author_pinterest_link'] ) ? false : true;
			$enable_instagram = ( isset( $bdp_settings['author_instagram_link'] ) && 0 == $bdp_settings['author_instagram_link'] ) ? false : true;
			$enable_reddit    = ( isset( $bdp_settings['author_reddit_link'] ) && 1 == $bdp_settings['author_reddit_link'] ) ? true : false;
			$enable_pocket    = ( isset( $bdp_settings['author_pocket_link'] ) && 1 == $bdp_settings['author_pocket_link'] ) ? true : false;
			$enable_skype     = ( isset( $bdp_settings['author_skype_link'] ) && 1 == $bdp_settings['author_skype_link'] ) ? true : false;
			$enable_wordpress = ( isset( $bdp_settings['author_wordpress_link'] ) && 1 == $bdp_settings['author_wordpress_link'] ) ? true : false;
			$enable_snapchat  = ( isset( $bdp_settings['author_snapchat_link'] ) && 1 == $bdp_settings['author_snapchat_link'] ) ? true : false;
			$enable_vine      = ( isset( $bdp_settings['author_vine_link'] ) && 1 == $bdp_settings['author_vine_link'] ) ? true : false;
			$enable_tumblr    = ( isset( $bdp_settings['author_tumblr_link'] ) && 1 == $bdp_settings['author_tumblr_link'] ) ? true : false;
			$website          = esc_url( get_the_author_meta( 'url' ) );
			$email            = sanitize_email( get_the_author_meta( 'email' ) );
			$facebook         = esc_url( get_the_author_meta( 'facebook' ) );
			$twitter          = esc_url( get_the_author_meta( 'twitter' ) );
			$linkedin         = esc_url( get_the_author_meta( 'linkedin' ) );
			$youtube          = esc_url( get_the_author_meta( 'youtube' ) );
			$pinterest        = esc_url( get_the_author_meta( 'pinterest' ) );
			$instagram        = esc_url( get_the_author_meta( 'instagram' ) );
			$reddit           = esc_url( get_the_author_meta( 'reddit' ) );
			$pocket           = esc_url( get_the_author_meta( 'pocket' ) );
			$skype            = esc_url( get_the_author_meta( 'skype' ) );
			$wordpress        = esc_url( get_the_author_meta( 'WordPress' ) );
			$snapchat         = esc_url( get_the_author_meta( 'snapchat' ) );
			$vine             = esc_url( get_the_author_meta( 'vine' ) );
			$tumblr           = esc_url( get_the_author_meta( 'tumblr' ) );
			if ( ( ! empty( $facebook ) && $enable_facebook ) || ( ! empty( $twitter ) && $enable_twitter ) || ( ! empty( $linkedin ) && $enable_linkedin ) || ( ! empty( $website ) && $enable_website ) || ( ! empty( $email ) && $enable_email ) || ( ! empty( $youtube ) && $enable_youtube ) || ( ! empty( $pinterest ) && $enable_pinterest ) || ( ! empty( $instagram ) && $enable_instagram ) || ( ! empty( $reddit ) && $enable_reddit ) || ( ! empty( $pocket ) && $enable_pocket ) || ( ! empty( $skype ) && $enable_skype ) || ( ! empty( $snapchat ) && $enable_snapchat ) || ( ! empty( $vine ) && $enable_vine ) || ( ! empty( $tumblr ) && $enable_tumblr ) || ( ! empty( $wordpress ) && $enable_wordpress ) ) {
				$social_theme = ' default_social_style_1 ';
				if ( isset( $bdp_settings['default_icon_theme'] ) && isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
					$social_theme = ' default_social_style_' . $bdp_settings['default_icon_theme'] . ' ';
				}
				?>
				<div class="social-component
				<?php
				echo esc_attr( $social_theme );
				if ( isset( $bdp_settings['social_style'] ) && 0 == $bdp_settings['social_style'] ) {
					if ( isset( $bdp_settings['social_icon_size'] ) && 0 == $bdp_settings['social_icon_size'] ) {
						echo ' large ';
					} elseif ( isset( $bdp_settings['social_icon_size'] ) && 2 == $bdp_settings['social_icon_size'] ) {
						echo ' extra_small ';
					}
				}
				?>
				">
					<?php if ( ! empty( $facebook ) && $enable_facebook ) { ?>
						<?php
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" class="bdp-facebook-share social-share-default"></a>
							<?php
						} else {
							?>
							<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" class="bdp-facebook-share facebook-share social-share-custom"><i class="fab fa-facebook-f"></i></a>
							<?php
						}
					}
					if ( ! empty( $linkedin ) && $enable_linkedin ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" class="bdp-linkedin-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" class="bdp-linkedin-share social-share-custom">
								<i class="fab fa-linkedin-in"></i>
							</a>
							<?php
						}
						?>
						<?php
					}
					if ( ! empty( $twitter ) && $enable_twitter ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" class="bdp-twitter-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" class="bdp-twitter-share social-share-custom"><i class="fab fa-x-twitter"></i></a>
							<?php
						}
					}
					if ( ! empty( $email ) && $enable_email ) {
						?>
						<?php
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo 'mailto:' . esc_attr( $email ); ?>" target="_blank" class="bdp-email-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo 'mailto:' . esc_attr( $email ); ?>" target="_blank" class="bdp-email-share social-share-custom"><i class="far fa-envelope-open"></i></a>
							<?php
						}
					}
					if ( ! empty( $website ) && $enable_website ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $website ); ?>" target="_blank" class="bdp-website-share social-share-default"></a>
							<?php
						} else {
							?>
							<a href="<?php echo esc_url( $website ); ?>" target="_blank" class="bdp-website-share social-share-custom"><i class="fas fa-globe"></i></a>
							<?php
						}
					}
					if ( ! empty( $youtube ) && $enable_youtube ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" class="bdp-youtube-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" class="bdp-youtube-share social-share-custom"><i class="fab fa-youtube"></i></a>
							<?php
						}
					}
					if ( ! empty( $pinterest ) && $enable_pinterest ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" class="bdp-pinterest-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" class="bdp-pinterest-share social-share-custom">
								<i class="fab fa-pinterest-p"></i>
							</a>
							<?php
						}
					}
					if ( ! empty( $instagram ) && $enable_instagram ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" class="bdp-instagram-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" class="bdp-instagram-share social-share-custom"><i class="fab fa-instagram"></i></a>
							<?php
						}
					}
					if ( ! empty( $reddit ) && $enable_reddit ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $reddit ); ?>" target="_blank" class="bdp-reddit-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $reddit ); ?>" target="_blank" class="bdp-reddit-share social-share-custom"><i class="fab fa-reddit-alien"></i></a>
							<?php
						}
					}
					if ( ! empty( $pocket ) && $enable_pocket ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $pocket ); ?>" target="_blank" class="bdp-pocket-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $pocket ); ?>" target="_blank" class="bdp-pocket-share social-share-custom"><i class="fab fa-get-pocket"></i></a>
							<?php
						}
					}
					if ( ! empty( $skype ) && $enable_skype ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $skype ); ?>" target="_blank" class="bdp-skype-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $skype ); ?>" target="_blank" class="bdp-skype-share social-share-custom"><i class="fab fa-skype"></i></a>
							<?php
						}
					}
					if ( ! empty( $wordpress ) && $enable_wordpress ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $wordpress ); ?>" target="_blank" class="bdp-wordpress-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $wordpress ); ?>" target="_blank" class="bdp-wordpress-share social-share-custom"><i class="fab fa-wordpress"></i></a>
							<?php
						}
					}
					if ( ! empty( $snapchat ) && $enable_snapchat ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $snapchat ); ?>" target="_blank" class="bdp-snapchat-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $snapchat ); ?>" target="_blank" class="bdp-snapchat-share social-share-custom"><i class="fab fa-snapchat-ghost"></i></a>
							<?php
						}
					}
					if ( ! empty( $vine ) && $enable_vine ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $vine ); ?>" target="_blank" class="bdp-vine-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $vine ); ?>" target="_blank" class="bdp-vine-share social-share-custom"><i class="fab fa-vine"></i></a>
							<?php
						}
					}
					if ( ! empty( $tumblr ) && $enable_tumblr ) {
						if ( isset( $bdp_settings['social_style'] ) && 1 == $bdp_settings['social_style'] ) {
							?>
							<a href="<?php echo esc_url( $tumblr ); ?>" target="_blank" class="bdp-tumblr-share social-share-default"></a>
						<?php } else { ?>
							<a href="<?php echo esc_url( $tumblr ); ?>" target="_blank" class="bdp-tumblr-share social-share-custom"><i class="fab fa-tumblr"></i></a>
							<?php
						}
					}
					?>
				</div>
				<?php
			}
		}
	}
	/**
	 * Function Display Author Biography Cover Div End Using Action
	 *
	 * @param string $template template.
	 * @return void
	 */
	public static function display_author_content_cover_end( $template ) {
		echo '</div>';
	}
	/**
	 * Add facebook,twitter,Google+ links to user profile page.
	 *
	 * @param array $user_info user info.
	 * @return array updated userinfo
	 */
	public function author_social_links( $user_info ) {
		// Add user social contact links.
		$user_info['googleplus'] = esc_html__( 'Google+', 'blog-designer-pro' );
		$user_info['facebook']   = esc_html__( 'Facebook', 'blog-designer-pro' );
		$user_info['twitter']    = esc_html__( 'Twitter', 'blog-designer-pro' );
		$user_info['linkedin']   = esc_html__( 'LinkedIn', 'blog-designer-pro' );
		$user_info['youtube']    = esc_html__( 'YouTube', 'blog-designer-pro' );
		$user_info['pinterest']  = esc_html__( 'Pinterest', 'blog-designer-pro' );
		$user_info['instagram']  = esc_html__( 'Instagram', 'blog-designer-pro' );
		$user_info['reddit']     = esc_html__( 'Reddit', 'blog-designer-pro' );
		$user_info['pocket']     = esc_html__( 'Pocket', 'blog-designer-pro' );
		$user_info['skype']      = esc_html__( 'Skype', 'blog-designer-pro' );
		$user_info['wordpress']  = esc_html__( 'WordPress', 'blog-designer-pro' );
		$user_info['snapchat']   = esc_html__( 'Snapchat', 'blog-designer-pro' );
		$user_info['vine']       = esc_html__( 'Vine', 'blog-designer-pro' );
		$user_info['tumblr']     = esc_html__( 'Tumblr', 'blog-designer-pro' );
		return $user_info;
	}
	/**
	 * Get author template settings
	 *
	 * @param int   $author_id author id.
	 * @param array $archive_list archive list.
	 * @return Array Category Template settings
	 */
	public static function get_author_template_settings( $author_id, $archive_list = array() ) {
		$bdp_author_data = array();
		$bdp_settings    = array();
		$bdp_layout_id   = '';
		if ( ! empty( $archive_list ) && $archive_list ) {
			foreach ( $archive_list as $archive ) {
				if ( 'author_template' === $archive ) {
					global $wpdb;
					$author_template = '';
					if ( is_numeric( $author_id ) ) {
						$author_template = $wpdb->get_row(
							$wpdb->prepare(
								'SELECT id, settings FROM %s WHERE archive_template = %s AND find_in_set(%s, sub_categories) <> 0',
								$wpdb->prefix . 'bdp_archives',
								'author_template',
								$author_id
							)
						);
					}
					if ( ! empty( $author_template ) ) {
						$bdp_layout_id = $author_template->id;
						$allsettings   = $author_template->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = maybe_unserialize( $allsettings );
						}
					} else {
						$author_template = $wpdb->get_row( 'SELECT id, settings FROM ' . $wpdb->prefix . 'bdp_archives WHERE archive_template = "author_template" AND sub_categories = "" ORDER BY id DESC' );
						if ( ! empty( $author_template ) ) {
							$bdp_layout_id = $author_template->id;
							$allsettings   = $author_template->settings;
							if ( is_serialized( $allsettings ) ) {
								$bdp_settings = maybe_unserialize( $allsettings );
							}
						}
					}
				}
			}
		}
		$bdp_author_data['id']           = $bdp_layout_id;
		$bdp_author_data['bdp_settings'] = $bdp_settings;
		return $bdp_author_data;
	}
	/**
	 * Author filter function
	 *
	 * @param array $query query.
	 * @return Array query
	 */
	public static function author_filter_func( $query ) {
		global $wpdb, $authors, $wp_bdp_setting;
		$query               = str_replace( "\n", ' ', $query );
		$author_arr          = ( isset( $wp_bdp_setting['template_authors'] ) ) ? $wp_bdp_setting['template_authors'] : array();
		$exclude_author_list = isset( $wp_bdp_setting['exclude_author_list'] ) ? true : false;
		if ( ! empty( $author_arr ) ) {
			if ( $exclude_author_list ) {
				if ( preg_match( '/AND (\(.*term_taxonomy_id.*\)) AND/', $query, $matches ) ) {
					$query = str_replace( $matches[1], '(' . $matches[1] . " OR {$wpdb->posts}.post_author NOT IN (" . implode( ',', $author_arr ) . ' ) ) ', $query );
				} else {
					$query .= " AND {$wpdb->posts}.post_author NOT IN (" . implode( ',', $author_arr ) . ') ';
				}
			} elseif ( preg_match( '/AND (\(.*term_taxonomy_id.*\)) AND/', $query, $matches ) ) {
					$query = str_replace( $matches[1], '(' . $matches[1] . " OR {$wpdb->posts}.post_author IN (" . implode( ',', $author_arr ) . ' ) ) ', $query );
			} else {
				$query .= " AND {$wpdb->posts}.post_author IN (" . implode( ',', $author_arr ) . ') ';
			}
		}
		return $query;
	}

	/**
	 * Get post author
	 *
	 * @since 2.0
	 * @param int   $post_id post id.
	 * @param array $bdp_settings settings data.
	 * @return array $authors post authors
	 */
	public static function get_post_auhtors( $post_id, $bdp_settings ) {
		$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true;
		$authors     = '';
		if ( function_exists( 'coauthors_posts_links' ) ) {
			$authors = coauthors_posts_links( ',', ', ', null, null, false );
			$authors = ( ! $author_link ) ? wp_strip_all_tags( $authors ) : $authors;
		} else {
			$authors .= ( ( 'product' !== get_post_type( $post_id ) || 'download' !== get_post_type( $post_id ) ) && $author_link ) ? '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" >' : '';
			$authors .= get_the_author();
			$authors .= ( ( 'product' !== get_post_type( $post_id ) || 'download' !== get_post_type( $post_id ) ) && $author_link ) ? '</a>' : '';
		}
		return $authors;
	}
	/**
	 * Get co post author
	 *
	 * @since 2.0
	 * @param array $args args.
	 * @return string $args
	 */
	public function co_authors_posts_link( $args ) {
		$args['class'] = 'url fn';
		return $args;
	}
}
new Bdp_Author();
