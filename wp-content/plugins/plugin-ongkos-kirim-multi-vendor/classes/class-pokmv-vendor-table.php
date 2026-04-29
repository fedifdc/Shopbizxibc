<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Shipping report table
 */
class POKMV_Vendor_Table extends WP_List_Table {

	/**
	 * POK Core
	 *
	 * @var object
	 */
	protected $core;

	/**
	 * POK Setting
	 *
	 * @var object
	 */
	protected $setting;

	/**
	 * POK Helper
	 *
	 * @var object
	 */
	protected $helper;

	/**
	 * Shipping origins
	 *
	 * @var array
	 */
	protected $origins;

	/**
	 * Main origin
	 *
	 * @var int
	 */
	protected $main_origin;

	/**
	 * Max items
	 *
	 * @var int
	 */
	protected $max_items;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $pok_helper;
		global $pok_core;
		$this->core     = $pok_core;
		$this->setting  = new POK_Setting();
		$this->helper   = $pok_helper;
		$main_origins   = $this->setting->get( 'store_location' );
		$this->main_origin = ( isset( $main_origins[0] ) ? $main_origins[0] : 0 );
		parent::__construct(
			array(
				'singular' => 'Vendor',
				'plural'   => 'Vendors',
				'ajax'     => false,
			)
		);
	}

	/**
	 * No items found text.
	 */
	public function no_items() {
		esc_html_e( 'No vendor found', 'pok' );
	}

	/**
	 * Set table classes
	 *
	 * @return array Classes.
	 */
	protected function get_table_classes() {
		return array( 'widefat', 'striped', $this->_args['plural'] );
	}

	/**
	 * Output the table.
	 */
	public function output_table() {
		if ( isset( $_GET['edit'] ) && ! empty( $_GET['edit'] ) ) {
			if ( $vendor = $this->get_single_vendor( intval( $_GET['edit'] ) ) ) {
				$vendor->setting    = pokmv_get_vendor( intval( $_GET['edit'] ) );
				$vendor_settings    = $vendor->setting->get_all();
				$settings           = $this->setting->get_all();
				$couriers           = $this->core->get_courier( $settings['base_api'], $settings['rajaongkir_type'] );
				$all_couriers       = $this->core->get_all_couriers();
				$services           = $this->core->get_courier_service();
				if ( 'rajaongkir' === $settings['base_api'] ) {
					$cities = $this->core->get_all_city();
				} else {
					$origins = $this->core->get_origins();
				}
				if ( version_compare( POK_VERSION, '3.4.2', '<=' ) ) {
					include_once POKMV_PLUGIN_PATH . 'templates/admin/edit-vendor-legacy-2.php';
				} elseif ( version_compare( POK_VERSION, '3.8.5', '<=' ) ) {
					include_once POKMV_PLUGIN_PATH . 'templates/admin/edit-vendor-legacy.php';
				} else {
					include_once POKMV_PLUGIN_PATH . 'templates/admin/edit-vendor.php';
				}
			} else {
				wp_redirect( admin_url( 'admin.php?page=pok_setting&tab=vendor' ) );
				exit;
			}
		} else {
			$this->prepare_items();
			?>
			<div class="pok-report pokmv-vendors woocommerce-reports-wide">
				<?php $this->display(); ?>
			</div>
			<?php
		}
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns() {

		$columns = array(
			'vendor'    => __( 'Vendor', 'pokmv' ),
			'origin'    => __( 'Origin', 'pokmv' ),
			'couriers'  => __( 'Couriers', 'pokmv' ),
			'action'    => __( 'Action', 'pokmv' ),
		);

		return apply_filters( 'pokmv_vendor_table_columns', $columns );
	}

	/**
	 * Set sortable columns
	 *
	 * @return array Columns.
	 */
	protected function get_sortable_columns() {
		$sortable_columns = array(
			'vendor'    => array( 'name', true ),
		);
		return apply_filters( 'pokmv_vendor_table_sortable_columns', $sortable_columns );
	}

	/**
	 * Get column value.
	 *
	 * @param mixed  $item          Item.
	 * @param string $column_name   Column name.
	 */
	public function column_default( $item, $column_name ) {
		$platform = pokmv_get_active_multivendor_plugin();

		$output = '';
		if ( isset( $item->{$column_name} ) ) {
			$output = $item->{$column_name};
		}

		if ( 0 !== intval( $item->id ) ) {
			$vendor     = pokmv_get_vendor( $item->id );
			$setting    = $vendor->get_all();
		}

		switch ( $column_name ) {
			case 'vendor':
				if ( 0 !== intval( $item->id ) ) {
					if ( in_array( $platform, array( 'dokan', 'wc-vendors', 'wc-marketplace' ) ) ) {
						$store_name = pokmv_get_vendor_store_name( $item->id );
						if ( ! empty( $store_name ) && $item->name !== $store_name ) {
							$item->name .= ' (' . $store_name . ')';
						}
					}
					$output = '<a class="edit-vendor" href="' . admin_url( 'admin.php?page=pok_setting&tab=vendor&edit=' . esc_attr( $item->id ) ) . '"><strong>' . esc_html( $item->name ) . '</strong></a>';
				} else {
					$output = '<strong>' . esc_html( $item->name ) . '</strong>';
				}
				break;
			case 'origin':
				if ( 0 !== intval( $item->id ) ) {
					$output = pokmv_get_city_name( $setting['origin'] );
				} else {
					$output = pokmv_get_city_name( $this->main_origin );
				}
				break;
			case 'couriers':
				if ( 0 !== intval( $item->id ) ) {
					$couriers = $setting['courier'];
				} else {
					$couriers = $this->setting->get( 'couriers' );
				}
				foreach ( $couriers as $key => $courier ) {
					$couriers[ $key ] = $this->helper->get_courier_name( $courier );
				}
				echo implode( ', ', $couriers );
				break;
			case 'action':
				if ( 0 !== intval( $item->id ) ) {
					$output = '<a class="edit-vendor button button-small" href="' . admin_url( 'admin.php?page=pok_setting&tab=vendor&edit=' . esc_attr( $item->id ) ) . '">' . __( 'Edit Setting', 'pokmv' ) . '</a>';
				} else {
					$output = '<button class="button button-small" disabled>' . __( 'Edit Setting', 'pokmv' ) . '</button>';
				}
				break;
		}
		echo apply_filters( 'pokmv_vendor_table_column', $output, $column_name, $item, $this ); // WPCS: XSS ok.
	}

	/**
	 * Show additional filter
	 *
	 * @param  string $which Tablenav location.
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			?>
			<form action=""> 
				<div class="alignleft actions">
					<input type="text" name="s" value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>" placeholder="<?php esc_attr_e( 'Search vendor', 'pokmv' ); ?>">
					<div style="width: 200px; display: inline-block;">
						<select name="filter_origin" class="init-select2">
							<option value=""><?php esc_html_e( 'All origins', 'pokmv' ); ?></option>
							<?php foreach ( $this->origins as $key => $city ) : ?>
								<option <?php echo isset( $_GET['filter_origin'] ) && intval( $_GET['filter_origin'] ) === intval( $key ) ? 'selected' : ''; // WPCS: Input var okay, CSRF okay. ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $city ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<input type="hidden" name="page" value="pok_setting">
					<input type="hidden" name="tab" value="vendor">
					<input class="button" type="submit" value="<?php esc_html_e( 'Get Vendors', 'pokmv' ); ?>">
				</div>
			</form>
			<?php
		}
	}

	/**
	 * Prepare list items.
	 */
	public function prepare_items() {

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$current_page          = absint( $this->get_pagenum() );
		$per_page              = apply_filters( 'pokmv_vendor_per_page', 20 );

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

		$platform       = pokmv_get_active_multivendor_plugin();
		$data_location  = pokmv_vendor_data_location();

		$this->max_items = 0;
		$this->items     = array();
		$this->origins   = array();
		$store_location = $this->setting->get( 'store_location' );
		if ( is_array( $store_location ) && ! empty( $store_location ) && isset( $store_location[0] ) ) {
			$this->origins[ $store_location[0] ] = pokmv_get_city_name( $store_location[0] );
		}

		$queries = array();

		$queries['union_start'] = "FROM (
			SELECT 0 AS id, 'Main Vendor' AS name, '' AS setting 
			UNION ALL ";

		if ( 'term' === $data_location ) {

			if ( 'pokmv' === $platform ) {
				$taxonomy_name = 'pokmv_vendor';
			} elseif ( 'product-vendors' === $platform ) {
				$taxonomy_name = 'wcpv_product_vendors';
			} elseif ( 'yith-vendors' === $platform ) {
				$taxonomy_name = 'yith_shop_vendor';
			}

			$queries['select']      = 'SELECT terms.term_id AS id, terms.name AS name, setting.meta_value AS setting ';
			$queries['from']        = "FROM {$wpdb->terms} as terms ";
			$queries['join']        = "INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id AND term_taxonomy.taxonomy = '" . esc_sql( $taxonomy_name ) . "' ) 
				LEFT JOIN {$wpdb->termmeta} AS setting ON ( terms.term_id = setting.term_id AND setting.meta_key = 'pokmv_vendor_setting' ) ";

		} elseif ( 'user' === $data_location ) {

			if ( 'dokan' === $platform ) {
				$role_name = 'seller';
			} elseif ( 'wc-marketplace' === $platform ) {
				$role_name = 'dc_vendor';
			} elseif ( 'wc-vendors' === $platform ) {
				$role_name = 'vendor';
			} elseif ( 'wcfm' === $platform ) {
				$role_name = 'wcfm_vendor';
			}

			$queries['select']      = 'SELECT users.ID AS id, users.display_name AS name, setting.meta_value AS setting ';
			$queries['from']        = "FROM {$wpdb->users} as users ";
			$queries['join']        = "INNER JOIN {$wpdb->usermeta} AS role ON ( users.ID = role.user_id AND role.meta_key = '{$wpdb->prefix}capabilities' AND role.meta_value LIKE '%:" . strlen( $role_name ) . ':"' . $role_name . "\"%' ) 
				LEFT JOIN {$wpdb->usermeta} AS setting ON ( users.ID = setting.user_id AND setting.meta_key = 'pokmv_vendor_setting' ) ";
		}

		$queries['union_end']   = ') uni ';
		$queries['where']       = 'WHERE 1=1 ';

		if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) { // Input var okay.
			$queries['where']  .= 'AND name LIKE \'%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%\'';
		}
		if ( isset( $_GET['filter_origin'] ) ) { // Input var okay.
			if ( 0 !== intval( $_GET['filter_origin'] ) ) {
				if ( intval( $this->main_origin ) !== intval( $_GET['filter_origin'] ) ) {
					$queries['where'] .= 'AND setting LIKE \'%"origin";s:' . strlen( $_GET['filter_origin'] ) . ':"' . intval( $_GET['filter_origin'] ) . '"%\' ';
				} else {
					$queries['where'] .= 'AND ( setting LIKE \'%"origin";s:' . strlen( $_GET['filter_origin'] ) . ':"' . intval( $_GET['filter_origin'] ) . '"%\' OR setting LIKE \'\' ) ';
				}
			}
		}

		$query_from = apply_filters( 'pokmv_vendor_table_query_from', "{$queries['union_start']}{$queries['select']}{$queries['from']}{$queries['join']}{$queries['union_end']}", $queries, $data_location, $platform );
		$queries    = apply_filters( 'pokmv_vendor_table_query', $queries, $data_location, $platform );

		$orderby    = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'name'; // WPCS: Input var okay, CSRF okay.
		$order      = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'asc'; // WPCS: Input var okay, CSRF okay.

		$this->items        = $wpdb->get_results( $wpdb->prepare( "SELECT id, name, setting {$query_from}{$queries['where']} ORDER BY {$orderby} {$order} LIMIT %d, %d;", ( $current_page - 1 ) * $per_page, $per_page ) );
		$this->max_items    = $wpdb->get_var( "SELECT COUNT( id ) {$query_from}{$queries['where']};" );
		$vendors            = $wpdb->get_results( "SELECT id, name, setting {$query_from} ORDER BY {$orderby} {$order};" );

		foreach ( $vendors as $vendor ) {
			if ( isset( $vendor->setting ) && ! empty( $vendor->setting ) ) {
				$setting = maybe_unserialize( $vendor->setting );
				if ( isset( $setting['origin'] ) && ! empty( $setting['origin'] ) ) {
					$this->origins[ $setting['origin'] ] = pokmv_get_city_name( $setting['origin'] );
				}
			}
		}
		ksort( $this->origins );
	}

	/**
	 * Get single vendor data
	 *
	 * @param  integer $vendor_id Vendor ID.
	 * @return mixed              Vendor data.
	 */
	public function get_single_vendor( $vendor_id = 0 ) {
		global $wpdb;

		$platform       = pokmv_get_active_multivendor_plugin();
		$data_location  = pokmv_vendor_data_location();

		if ( 'term' === $data_location ) {

			if ( 'pokmv' === $platform ) {
				$taxonomy_name = 'pokmv_vendor';
			} elseif ( 'product-vendors' === $platform ) {
				$taxonomy_name = 'wcpv_product_vendors';
			} elseif ( 'yith-vendors' === $platform ) {
				$taxonomy_name = 'yith_shop_vendor';
			}

			$queries['select']      = 'SELECT terms.term_id AS id, terms.name AS name ';
			$queries['from']        = "FROM {$wpdb->terms} as terms ";
			$queries['join']        = "INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id AND term_taxonomy.taxonomy = '" . esc_sql( $taxonomy_name ) . "' ) ";
			$queries['where']       = 'WHERE terms.term_id = %d ';

		} elseif ( 'user' === $data_location ) {

			if ( 'dokan' === $platform ) {
				$role_name = 'seller';
			} elseif ( 'wc-marketplace' === $platform ) {
				$role_name = 'dc_vendor';
			} elseif ( 'wc-vendors' === $platform ) {
				$role_name = 'vendor';
			} elseif ( 'wcfm' === $platform ) {
				$role_name = 'wcfm_vendor';
			}

			$queries['select']      = 'SELECT users.ID AS id, users.display_name AS name ';
			$queries['from']        = "FROM {$wpdb->users} as users ";
			$queries['join']        = "INNER JOIN {$wpdb->usermeta} AS role ON ( users.ID = role.user_id AND role.meta_key = '{$wpdb->prefix}capabilities' AND role.meta_value LIKE '%:" . strlen( $role_name ) . ':"' . $role_name . "\"%' ) ";
			$queries['where']       = 'WHERE users.ID = %d ';
		}

		$queries    = apply_filters( 'pokmv_vendor_single_query', $queries, $data_location, $platform );
		$vendor     = $wpdb->get_row( $wpdb->prepare( "{$queries['select']}{$queries['from']}{$queries['join']}{$queries['where']}", $vendor_id ) );
		return $vendor;
	}

}
