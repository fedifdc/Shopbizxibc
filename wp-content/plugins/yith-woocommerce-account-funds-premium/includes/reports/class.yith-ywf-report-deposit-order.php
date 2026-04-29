<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Class that manage Deposit report
 *
 * @package YITH\AccountFunds\Classes\Reports
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Report_Deposit_Order' ) ) {
	/**
	 * Class YITH_YWF_Report_Deposit_Order
	 */
	class YITH_YWF_Report_Deposit_Order extends WC_Admin_Report {

		/**
		 * Chart colours.
		 *
		 * @var array
		 */
		public $chart_colours = array();

		/**
		 * The report data.
		 *
		 * @var stdClass
		 */
		private $report_data;

		/**
		 * Get report data.
		 *
		 * @return stdClass
		 */
		public function get_report_data() {
			if ( empty( $this->report_data ) ) {
				$this->query_report_data();
			}

			return $this->report_data;
		}

		/**
		 * Build the report data
		 *
		 * @author YITH <plugins@yithemes.com>
		 * @since 1.0.0
		 * init report_data
		 */
		public function query_report_data() {

			$this->report_data                        = new stdClass();
			$this->report_data->total_deposit         = 0;
			$this->report_data->count                 = 0;
			$this->report_data->average_total_deposit = 0;

			$args = array(
				'post_type'   => 'shop_order',
				'fields'      => 'ids',
				'post_status' => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
				'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_order_has_deposit',
						'value' => 'yes',
					),
				),
				'date_query'  => array(
					array(
						'after'     => gmdate( 'Y-m-d', $this->start_date ),
						'before'    => gmdate( 'Y-m-d', $this->end_date ),
						'inclusive' => true,
					),
				),
			);

			$orders_ids = get_posts( $args );

			$this->report_data->count = count( $orders_ids );

			if ( $this->report_data->count > 0 ) {

				foreach ( $orders_ids as $order_id ) {
					$order = wc_get_order( $order_id );

					$this->report_data->orders_count[ $order_id ]             = new stdClass();
					$this->report_data->orders_count[ $order_id ]->order_date = ywf_get_date_created_order( $order );
					$this->report_data->orders_count[ $order_id ]->count      = 0;
				}

				foreach ( $orders_ids as $order_id ) {

					$order = wc_get_order( $order_id );

					$this->report_data->total_deposit += $order->get_total();

					$this->report_data->order_deposits[ $order_id ]             = new stdClass();
					$this->report_data->order_deposits[ $order_id ]->order_date = ywf_get_date_created_order( $order );
					$this->report_data->order_deposits[ $order_id ]->total      = $order->get_total();

					$this->report_data->orders_count[ $order_id ]->count += 1;
				}
			} else {
				$this->report_data->orders_count   = array();
				$this->report_data->order_deposits = array();
			}

			$this->report_data->average_total_deposit = wc_format_decimal( $this->report_data->total_deposit / ( $this->chart_interval + 1 ), 2 );
		}

		/**
		 * Show report
		 *
		 * @since 1.0.0
		 */
		public function output_report() {
			$this->chart_colours = array(
				'sales_amount' => '#b1d4ea',
				'average'      => '#e74c3c',
				'order_count'  => '#dbe1e3',
			);

			$current_range = YITH_YWF_Reports()->get_current_date_range();

			$this->calculate_current_range( $current_range );

			$args = array(
				'report'        => $this,
				'current_range' => $current_range,
				'ranges'        => YITH_YWF_Reports()->get_ranges(),
			);

			wc_get_template( 'deposit-order.php', $args, '', YITH_FUNDS_TEMPLATE_PATH . 'woocommerce/admin/reports/' );
		}

		/**
		 * Get the cart legend
		 *
		 * @return array
		 */
		public function get_chart_legend() {
			$legend = array();
			$data   = $this->get_report_data();

			switch ( $this->chart_groupby ) {
				case 'day':
					/* translators: %s is the average of deposit */
					$average_total_funds = sprintf( __( 'Average daily deposits: %s', 'yith-woocommerce-account-funds' ), '<strong>' . wc_price( $data->average_total_deposit ) . '</strong>' );
					break;
				case 'month':
				default:
					/* translators: %s is the average of deposit */
					$average_total_funds = sprintf( __( 'Average gross monthly sales: %s', 'yith-woocommerce-account-funds' ), '<strong>' . wc_price( $data->average_total_deposit ) . '</strong>' );
					break;
			}

			$legend[] = array(
				/* translators: %s is the gross deposits */
				'title'            => sprintf( __( 'Gross sales in this period: %s', 'yith-woocommerce-account-funds' ), '<strong>' . wc_price( $data->total_deposit ) . '</strong>' ),
				'placeholder'      => __( 'This is the sum of the order totals after any refunds.', 'yith-woocommerce-account-funds' ),
				'color'            => $this->chart_colours['sales_amount'],
				'highlight_series' => 1,
			);

			$legend[] = array(
				'title'            => $average_total_funds,
				'color'            => $this->chart_colours['average'],
				'highlight_series' => 2,
			);

			$legend[] = array(
				/* translators: is the amount of deposit placed */
				'title'            => sprintf( __( '%s deposits placed', 'yith-woocommerce-account-funds' ), '<strong>' . $data->count . '</strong>' ),
				'color'            => $this->chart_colours['order_count'],
				'highlight_series' => 0,
			);

			return $legend;
		}

		/**
		 * Get the main chart
		 *
		 * @since 1.0.0
		 */
		public function get_main_chart() {
			global $wp_locale;

			$deposits_net = isset( $this->report_data->order_deposits ) ? $this->prepare_chart_data( $this->report_data->order_deposits, 'order_date', 'total', $this->chart_interval, $this->start_date, $this->chart_groupby ) : array();
			// Encode in json format.
			$chart_data = wp_json_encode(
				array(
					'deposits_net'   => array_values( $deposits_net ),
					'deposits_count' => isset( $this->report_data->orders_count ) ? array_values( $this->prepare_chart_data( $this->report_data->orders_count, 'order_date', 'count', $this->chart_interval, $this->start_date, $this->chart_groupby ) ) : false,
				)
			);

			?>
			<div class="chart-container">
				<div class="chart-placeholder main"></div>
			</div>
			<script type="text/javascript">

				var main_chart;

				jQuery(function () {
					var orders_data = JSON.parse(decodeURIComponent('<?php echo rawurlencode( $chart_data ); ?>'));
					var drawGraph = function (highlight) {
						var series = [
							{
								label: "<?php echo esc_js( __( 'Deposits placed', 'yith-woocommerce-account-funds' ) ); ?>",
								data: orders_data.deposits_count,
								color: "<?php echo esc_js( $this->chart_colours['order_count'] ); ?>",
								points: {show: false, radius: 0},
								bars: {fillColor: '<?php echo esc_js( $this->chart_colours['order_count'] ); ?>', fill: true, show: true, lineWidth: 0, barWidth: <?php echo esc_js( $this->barwidth ); ?> * 0.5, align: 'center'},
								shadowSize: 0,
								hoverable: true,
								yaxis: 1
							},
							{
								label: "<?php echo esc_js( __( 'Deposit amount', 'yith-woocommerce-account-funds' ) ); ?>",
								data: orders_data.deposits_net,
								color: "<?php echo esc_js( $this->chart_colours['sales_amount'] ); ?>",
								points: {show: true},
								lines: {show: true, lineWidth: 2, fill: false},
								shadowSize: 0,
								hoverable: true,
								prepend_tooltip: "<?php echo get_woocommerce_currency_symbol(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>",
								yaxis: 2
							},
							{
								label: "<?php echo esc_js( __( 'Average net sales', 'woocommerce' ) ); ?>",
								data: [[ <?php echo esc_js( min( array_keys( $deposits_net ) ) ); ?>, <?php echo esc_js( $this->report_data->average_total_deposit ); ?> ], [ <?php echo esc_js( max( array_keys( $deposits_net ) ) ); ?>, <?php echo esc_js( $this->report_data->average_total_deposit ); ?> ]],
								yaxis: 2,
								color: '<?php echo esc_js( $this->chart_colours['average'] ); ?>',
								points: {show: false},
								lines: {show: true, lineWidth: 2, fill: false},
								shadowSize: 0,
								hoverable: true
							},
						];

						if (highlight !== 'undefined' && series[highlight]) {
							highlight_series = series[highlight];

							highlight_series.color = '#9c5d90';

							if (highlight_series.bars)
								highlight_series.bars.fillColor = '#9c5d90';

							if (highlight_series.lines) {
								highlight_series.lines.lineWidth = 5;
							}
						}

						main_chart = jQuery.plot(
							jQuery('.chart-placeholder.main'),
							series,
							{
								legend: {
									show: false
								},
								grid: {
									color: '#aaa',
									borderColor: 'transparent',
									borderWidth: 0,
									hoverable: true
								},
								xaxes: [{
									color: '#aaa',
									position: "bottom",
									tickColor: 'transparent',
									mode: "time",
									timeformat: "<?php echo ( 'day' === $this->chart_groupby ) ? '%d %b' : '%b'; ?>",
									monthNames: JSON.parse(decodeURIComponent('<?php echo rawurlencode( wp_json_encode( array_values( $wp_locale->month_abbrev ) ) ); ?>')),
									tickLength: 1,
									minTickSize: [1, "<?php echo esc_js( $this->chart_groupby ); ?>"],
									font: {
										color: "#aaa"
									}
								}],
								yaxes: [
									{
										min: 0,
										minTickSize: 1,
										tickDecimals: 0,
										color: '#d4d9dc',
										font: {color: "#aaa"}
									},
									{
										position: "right",
										min: 0,
										tickDecimals: 2,
										alignTicksWithAxis: 1,
										color: 'transparent',
										font: {color: "#aaa"}
									}
								],
							}
						);

						jQuery('.chart-placeholder').resize();
					}

					drawGraph();

					jQuery('.highlight_series').hover(
						function () {
							drawGraph(jQuery(this).data('series'));
						},
						function () {
							drawGraph();
						}
					);
				});
			</script>
			<?php
		}
	}
}
