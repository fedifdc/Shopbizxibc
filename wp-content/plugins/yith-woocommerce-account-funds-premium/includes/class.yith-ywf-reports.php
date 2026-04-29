<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage the report
 *
 * @package YITH\AccountFunds\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_YWF_Reports' ) ) {
	/**
	 * Class YITH_YWF_Reports
	 */
	class YITH_YWF_Reports extends WC_Admin_Reports {

		/**
		 * The instance of class
		 *
		 * @var YITH_YWF_Reports
		 */
		protected static $instance;
		/**
		 * The report path
		 *
		 * @var string
		 */
		protected $report_path;

		/**
		 * YITH_YWF_Reports constructor.
		 */
		public function __construct() {
			$this->report_path = YITH_FUNDS_INC . 'reports/';

			add_filter( 'woocommerce_admin_reports', array( $this, 'add_funds_reports' ) );

		}

		/**
		 * Add funds reports to wc_reports
		 *
		 * @param array $reports The reports.
		 *
		 * @return array
		 * @author YITH <plugins@yithemes.com>
		 * @since 1.0.0
		 */
		public function add_funds_reports( $reports ) {

			$fund_reports = array(
				'ywf_funds' => array(
					'title'   => __( 'Deposits', 'yith-woocommerce-account-funds' ),
					'reports' => array(
						'deposit_order' => array(
							'title'       => __( 'Deposits', 'yith-woocommerce-account-funds' ),
							'description' => '',
							'hide_title'  => true,
							'callback'    => array( $this, 'load_reports' ),
						),
					),
				),
			);

			return array_merge( $reports, $fund_reports );
		}

		/**
		 * Load reports
		 *
		 * @param string $name The report name.
		 *
		 * @since 1.0.0
		 */
		public function load_reports( $name ) {

			$class = 'YITH_YWF_Report_' . $name;
			$name  = 'class.yith-ywf-report-' . sanitize_title( str_replace( '_', '-', $name ) ) . '.php';

			if ( file_exists( $this->report_path . $name ) ) {
				include_once $this->report_path . $name;
			} elseif ( ! class_exists( $class ) ) {
				return;
			}

			$report = new $class();
			$report->output_report();
		}

		/**
		 * Create or return the instance
		 *
		 * @return YITH_YWF_Reports unique access
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Output an export link
		 */
		public function get_export_button() {
			$current_range = $this->get_current_date_range();
			?>
			<a
				href="#"
				download="report-<?php echo esc_attr( $current_range ); ?>-<?php echo esc_attr( date_i18n( 'Y-m-d' ) ); ?>.csv"
				class="export_csv"
				data-export="chart"
				data-xaxes="<?php esc_attr_e( 'Date', 'yith-woocommerce-account-funds' ); ?>"
				data-groupby="<?php echo isset( $this->chart_groupby ) ? esc_attr( $this->chart_groupby ) : ''; ?>"
			>
				<?php esc_html_e( 'CSV export', 'yith-woocommerce-account-funds' ); ?>
			</a>
			<?php
		}

		/**
		 * Get hte current date range
		 *
		 * @return string The current range
		 */
		public function get_current_date_range() {
			$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( wp_unslash( $_GET['range'] ) ) : '7day'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ), true ) ) {
				$current_range = '7day';
			}

			return $current_range;
		}

		/**
		 * Get the date ranges
		 *
		 * @return array
		 */
		public function get_ranges() {
			return array(
				'year'       => __( 'Year', 'yith-woocommerce-account-funds' ),
				'last_month' => __( 'Last month', 'yith-woocommerce-account-funds' ),
				'month'      => __( 'This month', 'yith-woocommerce-account-funds' ),
				'7day'       => __( 'Last 7 days', 'yith-woocommerce-account-funds' ),
			);
		}

		/**
		 * Get the date query args for WP_Query
		 *
		 * @param string $start_date   The start date.
		 * @param string $end_date     The end date.
		 *
		 * @return array The query args
		 */
		public function get_wp_query_date_args( $start_date, $end_date ) {
			return array(
				'date_query' => array(
					'after'     => array(
						'year'  => gmdate( 'Y', $start_date ),
						'month' => gmdate( 'n', $start_date ),
						'day'   => gmdate( 'j', $start_date ),
					),
					'before'    => array(
						'year'  => gmdate( 'Y', $end_date ),
						'month' => gmdate( 'n', $end_date ),
						'day'   => gmdate( 'j', $end_date ),
					),
					'inclusive' => true,
				),
			);
		}

	}
}


if ( ! function_exists( 'YITH_YWF_Reports' ) ) {
	/**
	 * Main instance of plugin
	 *
	 * @return YITH_YWF_Reports
	 * @since  1.0
	 */
	function YITH_YWF_Reports() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		return YITH_YWF_Reports::get_instance();
	}
}

YITH_YWF_Reports();
