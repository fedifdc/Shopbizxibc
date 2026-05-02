<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the class that manage the make a deposit widget
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH\AccountFunds\Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Make_a_Deposit_Widget' ) ) {
	/**
	 * Class YITH_YWF_Make_a_Deposit_Widget
	 */
	class YITH_YWF_Make_a_Deposit_Widget extends WP_Widget { // phpcs:ignore
		/**
		 * YITH_YWF_Make_a_Deposit_Widget constructor.
		 */
		public function __construct() {
			parent::__construct(
				'yith_ywf_make_a_deposit_widget',
				__( 'YITH Account Funds: Make a deposit widget', 'yith-woocommerce-account-funds' ),
				array(
					'description' => __( 'Shows the form to let customers deposit funds', 'yith-woocommerce-account-funds' ),
				)
			);

		}

		/**
		 * Show the widget form in backend
		 *
		 * @param array $instance the widget instance.
		 *
		 */
		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yith-woocommerce-account-funds' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<?php
		}

		/**
		 * The update instance method.
		 *
		 * @param array $new_instance the new widget instance.
		 * @param array $old_instance the old widget instance.
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance          = array();
			$instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';

			return $instance;
		}

		/**
		 * Show the widget in frontend.
		 *
		 * @param array $args Widget args.
		 * @param array $instance the widget instance.
		 */
		public function widget( $args, $instance ) {
			if ( apply_filters( 'ywf_prevent_customer_add_funds', false ) ) {
				return;
			}
			// phpcs:disable
			echo $args['before_widget'];
			if ( isset( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}
			echo do_shortcode( '[yith_ywf_make_a_deposit_form]' );
			echo $args['after_widget'];
			// phpcs:enable
		}
	}
}
