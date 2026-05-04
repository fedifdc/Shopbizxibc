<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage the table that show the user funds list
 *
 * @package YITH\AccountFunds\Admin\Tables
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * YITH WooCommerce Account Funds Customers List Table
 *
 * @class YITH_WC_Funds_User_List_Table
 * @since   1.3.0
 * @author YITH <plugins@yithemes.com>
 */
class YITH_WC_Funds_User_List_Table extends WP_List_Table {


	/**
	 * YITH_WC_Funds_User_List_Table constructor.
	 *
	 * @param array $args The attrs.
	 */
	public function __construct( $args = array() ) {
		parent::__construct( array() );

		$this->process_bulk_action();
	}

	/**
	 * Column list.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'user_id'   => __( 'ID', 'yith-woocommerce-account-funds' ),
			'user_info' => __( 'User', 'yith-woocommerce-account-funds' ),
			'funds'     => __( 'Funds', 'yith-woocommerce-account-funds' ),
			'action'    => __( 'Action', 'yith-woocommerce-account-funds' ),
		);

		return $columns;
	}

	/**
	 * Prepare items
	 *
	 */
	public function prepare_items() {

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$users_per_page = 25;

		$paged = ( isset( $_GET['paged'] ) ) ? wp_unslash( $_GET['paged'] ) : '';  // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $paged ) || ! is_numeric( $paged ) || $paged <= 0 ) {
			$paged = 1;
		}

		$args = array(
			'number' => $users_per_page,
			'offset' => ( $paged - 1 ) * $users_per_page,
		);

		if ( $this->is_site_users ) {
			$args['blog_id'] = $this->site_id;
		}

		$orderby = isset( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order   = isset( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $orderby ) {
			if ( 'meta_value_num' === $orderby ) {
				$args['meta_key'] = '_customer_fund';    // phpcs:ignore WordPress.DB.SlowDBQuery
			}
			$args['orderby'] = $orderby;
		}

		if ( $order ) {
			$args['order'] = $order;
		}

		$args = $this->add_filter_args( $args );

		$wp_user_search = new WP_User_Query( $args );

		$this->items = $wp_user_search->get_results();
		$this->set_pagination_args(
			array(
				'total_items' => $wp_user_search->get_total(),
				'per_page'    => $users_per_page,
			)
		);

	}

	/**
	 * Show info in the column
	 *
	 * @param object $item The item.
	 * @param string $column_name The column name.
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'user_id':
				$value = $item->ID;
				break;
			case 'user_info':
				$email = '<a href="mailto:' . $item->user_email . '">' . $item->user_email . '</a>';

				$value = $item->display_name . '<br>' . $email;
				break;
			case 'funds':
				$funds    = get_user_meta( $item->ID, '_customer_fund', true );
				$currency = get_woocommerce_currency();

				$value = wc_price( $funds, array( 'currency' => $currency ) );
				break;
			default:
				$value = ''; // Show the whole array for troubleshooting purposes.
		}

		return $value;

	}


	/**
	 * Return the sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_id'   => array( 'ID', false ),
			'user_info' => array( 'display_name', false ),
			'funds'     => array( 'meta_value_num', false ),
		);

		return $sortable_columns;
	}


	/**
	 * Handles the checkbox column output.
	 *
	 * @param object $item The item.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="user[]" value="%s" />', $item->ID );
	}

	/**
	 * Get the column action
	 *
	 * @param object $item The item.
	 *
	 * @return string
	 */
	public function column_action( $item ) {
		$arg            = remove_query_arg( array( 'paged', 'orderby', 'order' ) );
		$url            = add_query_arg(
			array(
				'action'  => 'update',
				'user_id' => $item->ID,
			),
			$arg
		);
		$history_button = '<a href="' . esc_url( $url ) . '" class="ywf_update_funds button action">' . __( 'View Logs', 'yith-woocommerce-account-funds' ) . '</a>';

		return $history_button;
	}


	/**
	 * Adds in any query arguments based on the current filters
	 *
	 * @param array $args associative array of WP_Query arguments used to query and populate the list table.
	 *
	 * @return array associative array of WP_Query arguments used to query and populate the list table
	 * @since 1.0
	 */
	private function add_filter_args( $args ) {
		// filter by customer.
		if ( isset( $_POST['_customer_user'] ) && $_POST['_customer_user'] > 0 ) { // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$args['include'] = array( wp_unslash( $_POST['_customer_user'] ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		return $args;
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination, which
	 * includes our Filters: Customers, Products, Availability Dates
	 *
	 * @param string $which the placement, one of 'top' or 'bottom'.
	 *
	 * @since 1.0
	 * @see WP_List_Table::extra_tablenav();
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			// Customers, products.

			echo '<div class="alignleft actions">';

			$user_string = '';
			$user_id     = 0;
			$sel         = '';
			$customer    = isset( $_REQUEST['_customer_user'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_customer_user'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( $customer ) {

				$user_id = absint( $customer );
				$user    = get_user_by( 'id', $user_id );

				$user_string     = sprintf(/* translators: 1: user display name 2: user ID 3: user email */
					esc_html__( '%1$s (#%2$s &ndash; %3$s)', 'woocommerce' ),
					$user->display_name,
					absint( $user->ID ),
					$user->user_email
				);
				$sel[ $user_id ] = $user_string;
			}
			?>
			<select class="wc-customer-search" name="_customer_user" data-placeholder="<?php esc_attr_e( 'Filter by registered customer', 'woocommerce' ); ?>" data-allow_clear="true">
				<?php if ( ! empty( $sel ) ) : ?>
				<option value="<?php echo esc_attr( $user_id ); ?>" selected="selected"><?php echo htmlspecialchars( wp_kses_post( $user_string ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<option>
					<?php endif; ?>
			</select>
			<?php
			submit_button( __( 'Filter' ), 'button', false, false, array( 'id' => 'post-query-submit' ) );

			echo '</div>';
		}
	}


}
