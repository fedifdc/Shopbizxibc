<?php
/**
 * Recent Comments Widgets
 *
 * @package WordPress
 * @subpackage CepatLakoo
 * @since CepatLakoo 1.0.0
 */
 
// Widgets
add_action( 'widgets_init', 'cepatlakoo_recent_comments_widget' );

// Register our widget
function cepatlakoo_recent_comments_widget() {
	register_widget( 'Cepatlakoo_Recent_Comments' );
}

// Cepatlakoo Recent Comments Widget
class Cepatlakoo_Recent_Comments extends WP_Widget {

	//  Setting up the widget
	function __construct() {
		$widget_ops  = array( 'classname' => 'recent-widget recent-comment-widget', 'description' => esc_html__( 'Display recent comments with avatar.', 'cepatlakoo' ) );
		$control_ops = array( 'id_base' => 'cepatlakoo_recent_comments' );

		parent::__construct( 'cepatlakoo_recent_comments', esc_html__( 'CepatLakoo - Recent Comments', 'cepatlakoo' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $cepatlakoo_option;
		
		extract( $args );

		$cepatlakoo_recent_comments_title = apply_filters( 'widget_title',empty( $instance['cepatlakoo_recent_comments_title'] ) ? esc_html__( 'Recent Comments', 'cepatlakoo' ) : $instance['cepatlakoo_recent_comments_title'], $instance, $this->id_base );
		$cepatlakoo_recent_comments_count = !empty( $instance['cepatlakoo_recent_comments_count'] ) ? absint( $instance['cepatlakoo_recent_comments_count'] ) : 4;
		$cepatlakoo_recent_excerpt_count = !empty( $instance['cepatlakoo_recent_excerpt_count'] ) ? absint( $instance['cepatlakoo_recent_excerpt_count'] ) : 50;

		if ( ! $cepatlakoo_recent_comments_count ) $cepatlakoo_recent_comments_count = 4;

        echo $before_widget; ?>
        <div class="sidebar-widget widget"> 
        <?php echo $before_title . esc_attr( $cepatlakoo_recent_comments_title ) . $after_title;

			$args = array(
				'status' => 'approve',
				'number' => absint( $cepatlakoo_recent_comments_count )
			);
			$comments = get_comments( $args ); 
		?>
			<div class="recent-posts recent-comments">
				<?php foreach ( $comments as $comment ) { ?>
				    <article class="hentry recent-posts">
						<div class="thumbnail">
							<a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo $comment->comment_ID; ?>" rel="external nofollow">
								<?php echo get_avatar( $comment, '60' ); ?>
							</a>
						</div>
						<div class="detail">
							<div class="entry-meta">
								<span>
									<a href="<?php get_the_author_link(); ?>"><?php echo '<span class="recommauth">' . ( $comment->comment_author ) . '</span>'; ?></a></span>	
						    	</span>
							</div>
							<a href="<?php echo get_permalink( $comment->comment_post_ID ); ?>#comment-<?php echo $comment->comment_ID; ?>" rel="external nofollow"><?php echo wp_html_excerpt( get_the_title( $comment->comment_post_ID ), $cepatlakoo_recent_excerpt_count, '...' ); ?></a>
						</div>
					</article>
				<?php } ?>
			</div>
		</div>
		<?php echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['cepatlakoo_recent_comments_title'] 	= strip_tags( $new_instance['cepatlakoo_recent_comments_title'] );
		$instance['cepatlakoo_recent_comments_count']  = (int) $new_instance['cepatlakoo_recent_comments_count'];
		$instance['cepatlakoo_recent_excerpt_count']  	= (int) $new_instance['cepatlakoo_recent_excerpt_count'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('cepatlakoo_recent_comments_title' => esc_html__( 'Recent Comments', 'cepatlakoo' ), 'cepatlakoo_recent_comments_count' => '5', 'cepatlakoo_recent_excerpt_count' => '50') );
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_comments_title' ); ?>"><?php esc_html_e( 'Widget Title:', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_comments_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_comments_title' ); ?>" value="<?php echo esc_attr( $instance['cepatlakoo_recent_comments_title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_comments_count' ); ?>"><?php esc_html_e( 'Number of comments to show:', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_comments_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_comments_count' ); ?>" value="<?php echo absint( $instance['cepatlakoo_recent_comments_count'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cepatlakoo_recent_excerpt_count' ); ?>"><?php esc_html_e( 'Comments Excerpt Limiter', 'cepatlakoo' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'cepatlakoo_recent_excerpt_count' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'cepatlakoo_recent_excerpt_count' ); ?>" value="<?php echo absint( $instance['cepatlakoo_recent_excerpt_count'] ); ?>" />
            <p><small><?php esc_html_e('The comment excerpt in the first comment will be trim after reaching the number of characters defined.', 'cepatlakoo'); ?></small></p>
        </p>
<?php
	}
}
?>