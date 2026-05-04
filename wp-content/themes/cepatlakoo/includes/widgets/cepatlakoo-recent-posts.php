<?php
/**
 * Recent Posts Widgets
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'cepatlakoo_recent_posts_widget' );

// Register our widget
function cepatlakoo_recent_posts_widget() {
	register_widget( 'Cepatlakoo_Recent_Posts' );
}

// CepatLakoo Recent Posts
class Cepatlakoo_Recent_Posts extends WP_Widget {

	//  Setting up the widget
	function __construct() {
		$widget_ops  = array( 'classname' => 'recent-widgets', 'description' => esc_html__( 'Display recent posts with thumbnails.', 'cepatlakoo' ) );
		$control_ops = array( 'id_base' => 'cepatlakoo_recent_posts' );

		parent::__construct( 'cepatlakoo_recent_posts', esc_html__( 'CepatLakoo - Recent Posts', 'cepatlakoo' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $cepatlakoo_option;
		
		extract( $args );

		$cepatlakoo_recent_posts_title = apply_filters( 'widget_title', empty( $instance['cepatlakoo_recent_posts_title'] ) ?  esc_html__( 'Recent Posts', 'cepatlakoo' ) : $instance['cepatlakoo_recent_posts_title'], $instance, $this->id_base );
		$cepatlakoo_recent_posts_count = !empty( $instance['cepatlakoo_recent_posts_count'] ) ? absint( $instance['cepatlakoo_recent_posts_count'] ) : 4;
		$cepatlakoo_recent_title_limiter = !empty( $instance['cepatlakoo_recent_title_limiter'] ) ? absint( $instance['cepatlakoo_recent_title_limiter'] ) : 10;


		if ( ! $cepatlakoo_recent_posts_count ) $cepatlakoo_recent_posts_count = 4;

		$args_recent_posts = array(
			'post_type' 			=> 'post',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> absint( $cepatlakoo_recent_posts_count )
		);

		$cepatlakoo_recent_posts = new WP_Query();
		$cepatlakoo_recent_posts->query( $args_recent_posts );

		if ( $cepatlakoo_recent_posts->have_posts() ) : 
			echo $before_widget; ?>
			<div class="sidebar-widget widget">
			<?php echo $before_title . esc_attr( $cepatlakoo_recent_posts_title ) . $after_title; ?>
				<div class="recent-posts">
					<?php while ( $cepatlakoo_recent_posts->have_posts() ) : $cepatlakoo_recent_posts->the_post(); ?>
						<article <?php post_class('recent-posts'); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="thumbnail">
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'title' => get_the_title() ) ); ?></a>
								</div>
							<?php endif; ?>
							<div class="detail">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo wp_trim_words( get_the_title(), absint( $cepatlakoo_recent_title_limiter ), ' ...' ); ?></a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
			</div>
		<?php echo $after_widget; 
		endif; wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['cepatlakoo_recent_posts_title'] 		= strip_tags( $new_instance['cepatlakoo_recent_posts_title'] );
		$instance['cepatlakoo_recent_posts_count']  	= (int) $new_instance['cepatlakoo_recent_posts_count'];
		$instance['cepatlakoo_recent_title_limiter']  	= (int) $new_instance['cepatlakoo_recent_title_limiter'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'cepatlakoo_recent_posts_title' => esc_html__( 'Recent Posts', 'cepatlakoo' ), 'cepatlakoo_recent_posts_count' => '5', 'cepatlakoo_recent_title_limiter' => '10' ) );
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_posts_title' ); ?>"><?php esc_html_e( 'Widget Title:', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_posts_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_posts_title' ); ?>" value="<?php echo esc_attr( $instance['cepatlakoo_recent_posts_title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_posts_count' ); ?>"><?php esc_html_e( 'Number of posts to show:', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_posts_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_posts_count' ); ?>" value="<?php echo absint( $instance['cepatlakoo_recent_posts_count'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_title_limiter' ); ?>"><?php esc_html_e( 'Post Title Limiter', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_title_limiter' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_title_limiter' ); ?>" value="<?php echo absint( $instance['cepatlakoo_recent_title_limiter'] ); ?>" />
            <p><small><?php esc_html_e( 'The post title will be trim after reaching the number of characters defined.', 'cepatlakoo' ); ?></small></p>
        </p>
<?php
	}
}
?>