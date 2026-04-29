<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Confirmation list table
 */
class TPC_Report_Table extends WP_List_Table {

	/**
	 * Start date
	 *
	 * @var string
	 */
	protected $start_date;

	/**
	 * End date
	 *
	 * @var string
	 */
	protected $end_date;

	/**
	 * Max items.
	 *
	 * @var int
	 */
	protected $max_items;

	/**
	 * Payment status.
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Setting.
	 *
	 * @var TPC_Setting
	 */
	protected $setting;

	/**
	 * Constructor
	 *
	 * @param string $status Payment status.
	 */
	public function __construct( $status ) {
		$this->status  = $status;
		$this->setting = new TPC_Setting();
		parent::__construct(
			array(
				'singular' => 'confirmation',
				'plural'   => 'confirmation',
				'ajax'     => false,
			)
		);
	}

	/**
	 * No items found text.
	 */
	public function no_items() {
		esc_html_e( 'No order found', 'tpc' );
	}

	/**
	 * Don't need this.
	 *
	 * @param string $position Position.
	 */
	public function display_tablenav( $position ) {

		if ( 'top' !== $position ) {
			parent::display_tablenav( $position );
		}
	}

	/**
	 * Output the report.
	 */
	public function output_report() {

		$ranges = array(
			'year'       => __( 'Year', 'woocommerce' ),
			'last_month' => __( 'Last month', 'woocommerce' ),
			'month'      => __( 'This month', 'woocommerce' ),
			'7day'       => __( 'Last 7 days', 'woocommerce' ),
		);

		$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( wp_unslash( $_GET['range'] ) ) : '7day';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ), true ) ) {
			$current_range = '7day';
		}

		$this->calculate_current_range( $current_range );
		$this->prepare_items();

		?>
		<div id="poststuff" class="woocommerce-reports-wide">
			<div class="postbox">
				<div class="stats_range">
					<ul>
						<?php
						foreach ( $ranges as $range => $name ) {
							echo '<li class="' . ( $current_range == $range ? 'active' : '' ) . '"><a href="' . esc_url( remove_query_arg( array( 'start_date', 'end_date' ), add_query_arg( 'range', $range ) ) ) . '">' . esc_html( $name ) . '</a></li>';
						}
						?>
						<li class="custom <?php echo ( 'custom' === $current_range ) ? 'active' : ''; ?>">
							<?php esc_html_e( 'Custom:', 'woocommerce' ); ?>
							<form method="GET">
								<div>
									<?php
									// Maintain query string.
									foreach ( $_GET as $key => $value ) {
										if ( is_array( $value ) ) {
											foreach ( $value as $v ) {
												echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '[]" value="' . esc_attr( sanitize_text_field( $v ) ) . '" />';
											}
										} else {
											echo '<input type="hidden" name="' . esc_attr( sanitize_text_field( $key ) ) . '" value="' . esc_attr( sanitize_text_field( $value ) ) . '" />';
										}
									}
									?>
									<input type="hidden" name="range" value="custom" />
									<input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['start_date'] ) ) ? esc_attr( wp_unslash( $_GET['start_date'] ) ) : ''; ?>" name="start_date" class="range_datepicker from" /><?php //@codingStandardsIgnoreLine ?>
									<span>&ndash;</span>
									<input type="text" size="11" placeholder="yyyy-mm-dd" value="<?php echo ( ! empty( $_GET['end_date'] ) ) ? esc_attr( wp_unslash( $_GET['end_date'] ) ) : ''; ?>" name="end_date" class="range_datepicker to" /><?php //@codingStandardsIgnoreLine ?>
									<button type="submit" class="button" value="<?php esc_attr_e( 'Go', 'woocommerce' ); ?>"><?php esc_html_e( 'Go', 'woocommerce' ); ?></button>
									<?php wp_nonce_field( 'custom_range', 'wc_reports_nonce', false ); ?>
								</div>
							</form>
						</li>
					</ul>
				</div>
			</div>
			<?php $this->display(); ?>
		</div>
		<?php
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		$set_columns = $this->setting->get('report_columns');

		$columns = array(
			'id' => __( 'Order', 'tpc' ),
		);
		if ( in_array( 'order_date', $set_columns ) ) {
			$columns['order_date'] = __( 'Order Date', 'tpc' );
		}
		if ( in_array( 'order_total', $set_columns ) ) {
			$columns['order_total'] = __( 'Order Total', 'tpc' );
		}
		if ( in_array( 'payment_amount', $set_columns ) ) {
			$columns['payment_amount'] = __( 'Transfer Amount', 'tpc' );
		}
		if ( in_array( 'payment_date', $set_columns ) ) {
			$columns['payment_date'] = __( 'Transfer Date', 'tpc' );
		}
		if ( in_array( 'bank', $set_columns ) ) {
			$columns['bank'] = __( 'Bank', 'tpc' );
		}
		if ( in_array( 'file', $set_columns ) ) {
			$columns['file'] = __( 'Photo', 'tpc' );
		}
		if ( in_array( 'order_status', $set_columns ) ) {
			$columns['order_status'] = __( 'Order Status', 'tpc' );
		}
		if ( in_array( 'payment_status', $set_columns ) ) {
			$columns['payment_status'] = __( 'Payment Status', 'tpc' );
		}

		return apply_filters( 'tpc_report_column', $columns );
	}

	/**
	 * Get column value.
	 *
	 * @param mixed  $item          Item.
	 * @param string $column_name   Column name.
	 */
	public function column_default( $item, $column_name ) {
		$order = wc_get_order( $item->id );
		if ( $item->detail ) {
			$item->detail = maybe_unserialize( $item->detail );
			$amount = isset( $item->detail['amount'] ) ? floatval( $item->detail['amount'] ) : 0;
			$date 	= isset( $item->detail['transfer_date'] ) ? floatval( $item->detail['transfer_date'] ) : '-';
			$bank 	= isset( $item->detail['bank'] ) ? $item->detail['bank'] : '-';
			$file 	= isset( $item->detail['file'] ) ? intval( $item->detail['file'] ) : '';
			if ( 'waiting-confirm' === $item->status ) {
				$status = __( 'Waiting', 'tpc' );
			} else {
				if ( $confirmed = $order->get_meta( 'tpc_confirmed', true ) ) {
					$status = __( 'Confirmed', 'tpc' );
				} elseif ( $rejected = $order->get_meta( 'tpc_rejected', true ) ) {
					$status = __( 'Rejected', 'tpc' );
				} else {
					$status = __( 'Waiting', 'tpc' );
				}
			}
		} elseif ( $item->detail_old ) {
			$item->detail_old = maybe_unserialize( $item->detail_old );
			$amount = isset( $item->detail_old['amount'] ) ? floatval( $item->detail_old['amount'] ) : 0;
			$date 	= isset( $item->detail_old['date'] ) ? floatval( $item->detail_old['date'] ) : '-';
			$bank 	= isset( $item->detail_old['bank_destination'] ) ? $item->detail_old['bank_destination'] : '-';
			$file 	= isset( $item->detail_old['file'] ) ? intval( $item->detail_old['file'] ) : '';
			if ( 'waiting-confirm' === $item->status ) {
				$status = __( 'Waiting', 'tpc' );
			} else {
				$status = __( 'Confirmed', 'tpc' );
			}
		}
		switch ( $column_name ) {
			case 'id':
				echo '<a href="' . esc_url( admin_url( 'post.php?action=edit&post=' . $item->id ) ) . '"><strong>#' . esc_html( $item->id ) . '</strong></a>';
				break;
			case 'order_date':
				echo esc_html( date_i18n( 'M j, Y', strtotime( $item->order_date ) ) );
				break;
			case 'order_total':
				echo wp_kses_post( wc_price( $item->total ) );
				break;
			case 'payment_amount':
				echo wp_kses_post( wc_price( $amount ) );
				break;
			case 'payment_date':
				echo esc_html( date_i18n( 'M j, Y', strtotime( $date ) ) );
				break;
			case 'bank':
				echo esc_html( $bank );
				break;
			case 'file':
				if ( ! empty( $file ) ) {
					?>
					<a href="<?php echo wp_get_attachment_image_url( $file, 'full' ); ?>" target="_blank"><?php echo esc_html_e( 'View', 'tpc' ) ?></a>
					<?php
				} else {
					echo '-';
				}
				break;
			case 'order_status':
				echo esc_html( wc_get_order_status_name( $item->status ) );
				break;
			case 'payment_status':
				echo esc_html( $status );
				break;
		}
	}

	/**
	 * Prepare customer list items.
	 */
	public function prepare_items() {

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$current_page          = absint( $this->get_pagenum() );
		$per_page              = apply_filters( 'woocommerce_admin_stock_report_products_per_page', 20 );

		$this->get_items( $current_page, $per_page );

		/**
		 * Pagination.
		 */
		$this->set_pagination_args(
			array(
				'total_items' => $this->max_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->max_items / $per_page ),
			)
		);
	}

	/**
	 * Get Products matching stock criteria.
	 *
	 * @param int $current_page Current page.
	 * @param int $per_page     Per page.
	 */
	public function get_items( $current_page, $per_page ) {
		global $wpdb;

		$this->max_items = 0;
		$this->items     = array();

		$query_from = apply_filters(
			'tpc_report_query_form', "FROM {$wpdb->posts} as orders
			LEFT JOIN {$wpdb->postmeta} AS detail ON ( orders.ID = detail.post_id AND detail.meta_key = 'tpc_data' ) 
			LEFT JOIN {$wpdb->postmeta} AS detail_old ON ( orders.ID = detail_old.post_id AND detail.meta_key = 'tpc_confirmation' ) 
			LEFT JOIN {$wpdb->postmeta} AS total ON ( total.meta_id = ( SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id = orders.ID AND meta_key = '_order_total' LIMIT 1 ) )  
			WHERE 1=1
			AND orders.post_type IN ( 'shop_order' )
			AND orders.post_status NOT IN ( 'auto-draft', 'draft', 'trash' )
			AND orders.post_date >= %s
			AND orders.post_date <= %s
			AND ( detail.meta_value IS NOT NULL OR detail_old.meta_value IS NOT NULL )
		"
		);
		$this->items        = $wpdb->get_results( $wpdb->prepare( "SELECT orders.ID as id, orders.post_status as status, orders.post_date as order_date, total.meta_value as total, detail.meta_value as detail, detail_old.meta_value as detail_old {$query_from} ORDER BY orders.ID DESC LIMIT %d, %d;", date( 'Y-m-d', $this->start_date ), date( 'Y-m-d', $this->end_date ), ( $current_page - 1 ) * $per_page, $per_page ) );
		$this->max_items    = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( DISTINCT orders.ID ) {$query_from};", date( 'Y-m-d', $this->start_date ), date( 'Y-m-d', $this->end_date ) ) );
	}


	/**
	 * Get the current range and calculate the start and end dates.
	 *
	 * @param  string $current_range Current range.
	 */
	public function calculate_current_range( $current_range ) {

		switch ( $current_range ) {

			case 'custom':
				$this->start_date = max( strtotime( '-20 years' ), strtotime( sanitize_text_field( $_GET['start_date'] ) ) );

				if ( empty( $_GET['end_date'] ) ) {
					$this->end_date = strtotime( 'midnight', current_time( 'timestamp' ) );
				} else {
					$this->end_date = strtotime( 'midnight', strtotime( sanitize_text_field( $_GET['end_date'] ) ) );
				}
				break;

			case 'year':
				$this->start_date    = strtotime( date( 'Y-01-01', current_time( 'timestamp' ) ) );
				$this->end_date      = strtotime( 'midnight', current_time( 'timestamp' ) );
				break;

			case 'last_month':
				$first_day_current_month = strtotime( date( 'Y-m-01', current_time( 'timestamp' ) ) );
				$this->start_date        = strtotime( date( 'Y-m-01', strtotime( '-1 DAY', $first_day_current_month ) ) );
				$this->end_date          = strtotime( date( 'Y-m-t', strtotime( '-1 DAY', $first_day_current_month ) ) );
				break;

			case 'month':
				$this->start_date    = strtotime( date( 'Y-m-01', current_time( 'timestamp' ) ) );
				$this->end_date      = strtotime( 'midnight', current_time( 'timestamp' ) );
				break;

			case '7day':
				$this->start_date    = strtotime( '-6 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
				$this->end_date      = strtotime( 'midnight', current_time( 'timestamp' ) );
				break;
		}
	}

}
