<?php
/**
 * Widget: myCRED Social follow
 * @since 0.1
 * @version 1.4.3
 */

// register myCred_Widget
add_action( 'widgets_init', function(){
	register_widget( 'myCred_Widget' );
});
class myCred_Widget extends WP_Widget {
	// class constructor
public function __construct() {
	$widget_ops = array( 
		'classname' => 'myCred_Widget',
		'description' => 'Points for Social follow Buttons',
	);
	parent::__construct( 'myCred_Widget', '(myCred) Social Follow', $widget_ops );
}
	 public $args = array(
        'before_title'  => '<h4 class="myCred_Widget_Headding">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
 public function widget( $args, $instance ) {
 
        echo $args['before_widget'];
 
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
 
        echo '<div class="textwidget">';
 	
		echo do_shortcode('[mycred_display_social_follow_button]');  
         
			
        echo '</div>';
 
        echo $args['after_widget'];
 
    }




public function form( $instance ) {
 
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'mycred_social_share' );
      //  $text = ! empty( $instance['text'] ) ? $instance['text'] : esc_html__( '', 'mycred_social_share' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'mycred_social_share' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
		<?php echo do_shortcode('[mycred_display_social_follow_button]');   ?>
		</p>
		 <p>
		 Click here to <a href="admin.php?page=social-shares-settings"><b>Manage Settings</b></a>
		 </p>
        <?php
 
    }

	// save options

    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        
 
        return $instance;
    }
	
}
