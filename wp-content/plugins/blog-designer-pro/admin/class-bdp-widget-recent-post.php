<?php
/**
 * Recent Post Widget for Blog Designer Pro
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
 * Blog Designer PRO Recent Post Widget.
 *
 * @class   Bdp_Utility
 * @version 1.0.0
 */
class Bdp_Widget_Recent_Post extends WP_Widget {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			'bdp_recent_post_widget',
			'BDP &rarr; ' . __( 'Recent Posts', 'blog-designer-pro' ),
			array(
				'classname'   => 'bdp_recent_post_widget',
				'description' => __(
					'Show recent posts, most liked posts and most commented post.',
					'blog-designer-pro'
				),
			)
		);
		$this->alt_option_name = 'bdp_recent_post_widget';
		add_action( 'save_post', array( $this, 'flush_bdp_recent_post_widget' ) );
		add_action( 'deleted_post', array( $this, 'flush_bdp_recent_post_widget' ) );
		add_action( 'switch_theme', array( $this, 'flush_bdp_recent_post_widget' ) );
		add_action( 'init', array( &$this, 'flush_widgte_bdp_recent_css' ) );
	}
	/**
	 * Flush Widgte Recent CSS
	 *
	 * @return void
	 */
	public function flush_widgte_bdp_recent_css() {
		$bdp_disable_fa_css = get_option( 'bdp_disable_fa_css', 0 );
		if ( ! is_admin() && 0 == $bdp_disable_fa_css ) {
			wp_enqueue_style( 'bdp-recent-widget-fontawesome-stylesheets', BLOGDESIGNERPRO_URL . '/public/css/font-awesome.min.css', null, '6.5.1' );
		}
	}
	/**
	 * Widget
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$before_widget       = $args['before_widget'];
		$after_widget        = $args['after_widget'];
		$before_title        = $args['before_title'];
		$after_title         = $args['after_title'];
		$title               = isset( $instance['title'] ) ? $instance['title'] : '';
		$show_date           = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
		$show_view           = isset( $instance['show_view'] ) ? (bool) $instance['show_view'] : false;
		$select_post_type    = isset( $instance['select_post_type'] ) ? esc_attr( $instance['select_post_type'] ) : '';
		$select_image_layout = isset( $instance['select_image_layout'] ) ? esc_attr( $instance['select_image_layout'] ) : '';
		$posts_per_page      = isset( $instance['posts_per_page'] ) ? esc_attr( $instance['posts_per_page'] ) : -1;
		$show_feature_image  = isset( $instance['show_feature_image'] ) ? (bool) $instance['show_feature_image'] : true;
		wp_enqueue_style( 'bdp-recent-widget-css' );
		if ( is_rtl() ) {
			wp_enqueue_style( 'bdp-recent-widget-rtl-css' );
		}
		echo wp_kses( $before_widget, Bdp_Admin_Functions::args_kses() );
		if ( $title ) {
			echo wp_kses( $before_title . $title . $after_title, Bdp_Admin_Functions::args_kses() );
		}
		if ( 'most_viewed_post' === $select_post_type ) {
			$query = array(
				'post_type'           => 'post',
				'meta_key'            => '_bdp_post_daily_count',
				'orderby'             => 'meta_value_num',
				'posts_per_page'      => $posts_per_page,
				'ignore_sticky_posts' => 1,
			);
		} elseif ( 'most_liked_post' === $select_post_type ) {
			$query = array(
				'post_type'           => 'post',
				'posts_per_page'      => $posts_per_page,
				'meta_key'            => '_post_like_count',
				'orderby'             => 'meta_value_num',
				'ignore_sticky_posts' => 1,
			);
		} elseif ( 'most_commented_post' === $select_post_type ) {
			$query = array(
				'post_type'           => 'post',
				'posts_per_page'      => $posts_per_page,
				'orderby'             => 'comment_count',
				'ignore_sticky_posts' => 1,
			);
		} else {
			$query = array(
				'post_type'           => 'post',
				'posts_per_page'      => $posts_per_page,
				'ignore_sticky_posts' => 1,
			);
		}
		$image_layout = '';
		if ( 'circle' === $select_image_layout ) {
			$image_layout = ' img_circle';
		}
		// The Query.
		$the_query = new WP_Query( $query );
		// The Loop.
		if ( $the_query->have_posts() ) {
			$remove_space = '';
			if ( ! $show_feature_image ) {
				$remove_space = ' remove_padding';
			}
			echo '<div class="recent-post-wrapper">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$remove_space_thumb = '';
				?>
				<div class="recent-post-inner">
					<?php
					if ( $show_feature_image ) {
						if ( ! has_post_thumbnail() ) {
							$remove_space_thumb = ' remove_padding';
						}
						if ( has_post_thumbnail() ) {
							?>
							<div class="bdp-feature-image<?php echo esc_attr( $image_layout ); ?>">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>">
									<?php
									echo get_the_post_thumbnail( get_the_ID(), array( 60, 60 ), '' );
									?>
								</a>
							</div>
							<?php
						}
					}
					?>
					<div class="bdp-metadata<?php echo esc_attr( $remove_space ) . esc_attr( $remove_space_thumb ); ?>">
						<h3>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>">
								<?php echo esc_html( get_the_title() ); ?>
							</a>
						</h3>
						<?php
						if ( $show_date ) {
							?>
							<span>
								<?php
								$date_format = get_option( 'date_format' );
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );
								echo '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">';
								echo '<i class="far fa-calendar-alt"></i>&nbsp;&nbsp;';
								echo wp_kses( apply_filters( 'bdp_date_format', get_the_time( $date_format, get_the_ID() ), get_the_ID() ), Bdp_Admin_Functions::args_kses() );
								echo '</a>';
								?>
							</span>
							<?php
						}
						if ( $show_view ) {
							$count = get_post_meta( get_the_ID(), '_bdp_post_views_count', true );
							if ( '' != $count && $count > 0 ) {
								?>
								<span>
									<a href="<?php echo esc_url( get_the_permalink() ); ?>">
									<?php echo ( $show_date ) ? '  - ' : ''; ?>
									<?php
									echo esc_html( $count ) . ' ' . esc_html__( 'Views', 'blog-designer-pro' );
									?>
									</a>
								</span>
								<?php
							}
						}
						?>
					</div>
				</div>
				<?php
			}
			echo '</div>';
			wp_reset_postdata();
		}
		echo wp_kses( $after_widget, Bdp_Admin_Functions::args_kses() );
	}
	/**
	 * Form
	 *
	 * @param array $instance instance.
	 * @return void
	 */
	public function form( $instance ) {
		$title               = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$select_post_type    = isset( $instance['select_post_type'] ) ? esc_attr( $instance['select_post_type'] ) : '';
		$select_image_layout = isset( $instance['select_image_layout'] ) ? esc_attr( $instance['select_image_layout'] ) : '';
		$posts_per_page      = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : '5';
		$show_feature_image  = isset( $instance['show_feature_image'] ) ? (bool) $instance['show_feature_image'] : true;
		$show_date           = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
		$show_view           = isset( $instance['show_view'] ) ? (bool) $instance['show_view'] : false;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_post_type' ) ); ?>"><?php esc_html_e( 'Select Post Layout', 'blog-designer-pro' ); ?>:</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'select_post_type' ) ); ?>">
				<option value="recent_post" <?php selected( $select_post_type, 'recent_post' ); ?> ><?php esc_html_e( 'Most Recent Posts', 'blog-designer-pro' ); ?></option>
				<option value="most_liked_post" <?php selected( $select_post_type, 'most_liked_post' ); ?> ><?php esc_html_e( 'Most Liked Posts', 'blog-designer-pro' ); ?></option>
				<option value="most_commented_post" <?php selected( $select_post_type, 'most_commented_post' ); ?> ><?php esc_html_e( 'Most Commented Posts', 'blog-designer-pro' ); ?></option>
				<option value="most_viewed_post" <?php selected( $select_post_type, 'most_viewed_post' ); ?> ><?php esc_html_e( 'Most Viewed Posts', 'blog-designer-pro' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_image_layout' ) ); ?>"><?php esc_html_e( 'Select Image Layout', 'blog-designer-pro' ); ?>:</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'select_image_layout' ) ); ?>">
				<option value="circle" <?php selected( $select_image_layout, 'circle' ); ?> ><?php esc_html_e( 'Circle', 'blog-designer-pro' ); ?></option>
				<option value="square" <?php selected( $select_image_layout, 'square' ); ?> ><?php esc_html_e( 'Square', 'blog-designer-pro' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Posts Per Page', 'blog-designer-pro' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" min="1" value="<?php echo esc_attr( $posts_per_page ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_feature_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_feature_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_feature_image' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_feature_image' ) ); ?>"><?php esc_attr_e( 'Show Feature Image', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_attr_e( 'Show Date', 'blog-designer-pro' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_view ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_view' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_view' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_view' ) ); ?>"><?php esc_attr_e( 'Show Views', 'blog-designer-pro' ); ?></label>
		</p>
		<?php
	}
	/**
	 * Update
	 *
	 * @param array $new_instance new instance.
	 * @param array $old_instance old instance.
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                        = array();
		$instance['title']               = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
		$instance['select_post_type']    = isset( $new_instance['select_post_type'] ) ? $new_instance['select_post_type'] : '';
		$instance['select_image_layout'] = isset( $new_instance['select_image_layout'] ) ? $new_instance['select_image_layout'] : '';
		$instance['posts_per_page']      = isset( $new_instance['posts_per_page'] ) ? $new_instance['posts_per_page'] : '';
		$instance['show_feature_image']  = isset( $new_instance['show_feature_image'] ) ? (bool) $new_instance['show_feature_image'] : false;
		$instance['show_date']           = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_view']           = isset( $new_instance['show_view'] ) ? (bool) $new_instance['show_view'] : false;
		$alloptions                      = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['bdp_recent_post_widget'] ) ) {
			delete_option( 'bdp_recent_post_widget' );
		}
		return $instance;
	}
	/**
	 * Flush Recent Post Widget
	 *
	 * @return void
	 */
	public function flush_bdp_recent_post_widget() {
		wp_cache_delete( 'bdp_recent_post_widget', 'widget' );
	}
}
add_action( 'widgets_init', 'bdp_recent_post_widget' );
/**
 * Recent Post Widget
 *
 * @return void
 */
function bdp_recent_post_widget() {
	register_widget( 'Bdp_Widget_Recent_Post' );
}
