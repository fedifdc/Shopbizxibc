<?php

/**
 * Custom taxonomy class
 */
class POKMV_Taxonomy {

	/**
	 * POK core
	 * 
	 * @var POK_Core
	 */
	protected $core;

	/**
	 * POK Helper
	 * 
	 * @var POK_Helper
	 */
	protected $helper;

	/**
	 * POK Setting
	 * 
	 * @var POK_Setting
	 */
	protected $setting;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $pok_core;
		global $pok_helper;
		$this->core     = $pok_core;
		$this->setting  = new POK_Setting();
		$this->helper   = $pok_helper;
		if ( ! defined( 'POKMV_TAXONOMY_NAME' ) ) {
			define( 'POKMV_TAXONOMY_NAME', apply_filters( 'pokmv_taxonomy_name', 'pokmv_vendor' ) );
		}

		$this->register_custom_taxonomy();
		add_action( POKMV_TAXONOMY_NAME . '_add_form_fields', array( $this, 'add_taxonomy_fields' ) );
		add_action( POKMV_TAXONOMY_NAME . '_edit_form_fields', array( $this, 'edit_taxonomy_fields' ) );
		add_action( 'edited_' . POKMV_TAXONOMY_NAME, array( $this, 'save_taxonomy_fields' ) );
		add_action( 'created_' . POKMV_TAXONOMY_NAME, array( $this, 'save_taxonomy_fields' ), 10, 2 );
		add_filter( 'manage_edit-' . POKMV_TAXONOMY_NAME . '_columns', array( $this, 'custom_taxonomy_columns' ) );
		add_filter( 'manage_' . POKMV_TAXONOMY_NAME . '_custom_column', array( $this, 'custom_taxonomy_column_content' ), 10, 3 );
		add_action( 'save_post_product', array( $this, 'handle_save_product' ), 10, 3 );
		add_action( 'manage_product_posts_custom_column', array( $this, 'render_quick_edit_vendor_data' ), 99, 2 );
		add_action( 'woocommerce_product_quick_edit_end', array( $this, 'quick_edit_option' ) );
		add_action( 'woocommerce_product_bulk_edit_end', array( $this, 'product_bulk_option' ) );
		add_action( 'woocommerce_product_quick_edit_save', array( $this, 'process_quick_bulk_edit' ) );
		add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'process_quick_bulk_edit' ) );
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'add_vendor_info' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Let 3rd parties unhook the above via this hook.
		do_action( 'pokmv_taxonomy', $this );
	}

	/**
	 * Register custom taxonomy
	 */
	public function register_custom_taxonomy() {
		$labels = array(
			'name'          => _x( 'Vendors', 'pokmv' ),
			'singular_name' => _x( 'Vendor', 'pokmv' ),
			'all_items'     => __( 'All Vendors', 'pokmv' ),
			'edit_item'     => __( 'Edit Vendor', 'pokmv' ),
			'view_item'     => __( 'View Vendor', 'pokmv' ),
			'update_item'   => __( 'Update Vendor', 'pokmv' ),
			'add_new_item'  => __( 'Add New Vendor', 'pokmv' ),
			'new_item_name' => __( 'New Vendor Name', 'pokmv' ),
			'search_items'  => __( 'Search Vendors', 'pokmv' ),
			'popular_items' => __( 'Popular Vendors', 'pokmv' ),
		);
		$args = array(
			'hierarchical'       => false,
			'labels'             => $labels,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'query_var'          => true,
			'capabilities'       => array( 'manage_categories' ),
			'rewrite'            => array( 'slug' => apply_filters( 'pokmv_taxonomy_slug', 'vendor' ) ),
			'show_in_quick_edit' => false,
			'meta_box_cb'        => array( $this, 'custom_meta_box' ),
		);
		register_taxonomy( POKMV_TAXONOMY_NAME, array( 'product' ), apply_filters( 'pokmv_taxonomy_args', $args ) );
	}

	/**
	 * Add custom taxonomy fields
	 */
	public function add_taxonomy_fields() {
		pokmv_get_template_part( 'custom-taxonomy/add-fields', array() );
	}

	/**
	 * Add custom fields to edit taxonomy
	 *
	 * @param  object $term Term object.
	 */
	public function edit_taxonomy_fields( $term ) {
		$args = array(
			'vendor_id' => $term->term_id,
		);
		pokmv_get_template_part( 'custom-taxonomy/edit-fields', $args );
	}

	/**
	 * Save custom taxonomy fields
	 *
	 * @param  integer $term_id Term ID.
	 */
	public function save_taxonomy_fields( $term_id ) {
		if ( wp_verify_nonce( $_POST['_inline_edit'], 'taxinlineeditnonce' ) ) {
			return;
		}

		$setting = pokmv_get_vendor( $term_id );

		if ( isset( $_POST['pokmv_vendor']['origin'] ) ) {
			$setting->set( 'origin', $_POST['pokmv_vendor']['origin'] );
		}
		$setting->set( 'courier', isset( $_POST['pokmv_vendor']['courier'] ) ? $_POST['pokmv_vendor']['courier'] : array() );
	}

	/**
	 * Register new custom column to taxonomy list
	 *
	 * @param  array $columns List columns.
	 * @return array          List columns.
	 */
	public function custom_taxonomy_columns( $columns ) {
		$columns['origin'] = __( 'Vendor Location', 'pokmv' );
		unset( $columns['description'] );
		return $columns;
	}

	/**
	 * Add new custom column content to taxonomy list
	 *
	 * @param  string  $content     Content to display.
	 * @param  string  $column_name Column name.
	 * @param  integer $term_id     Term ID.
	 * @return string               Content to display.
	 */
	public function custom_taxonomy_column_content( $content, $column_name, $term_id ) {
		$setting = pokmv_get_vendor( $term_id );
		if ( 'origin' === $column_name ) {
			$content = $this->core->get_single_city( $setting->get( 'origin' ) );
		}
		return $content;
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( ( ( 'term' === $screen->base || 'edit-tags' === $screen->base ) && POKMV_TAXONOMY_NAME === $screen->taxonomy ) || ( 'post' === $screen->base && 'product' === $screen->post_type ) ) {
			wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array(), '4.0.5' );
			wp_enqueue_style( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/css/admin.css', array( 'select2' ), POKMV_VERSION );
			wp_enqueue_script( 'pokmv-admin', POKMV_PLUGIN_URL . '/assets/js/admin.js', array( 'jquery', 'select2' ), POKMV_VERSION, true );
		}
	}

	/**
	 * Render custom metabox
	 */
	public function custom_meta_box() {
		global $post;
		$selected_vendor_id = pokmv_get_product_vendor( wc_get_product( $post->ID ) );
		?>
		<div class="pokmv-taxonomy-metabox">
			<select name="vendor" id="pok-vendor" class="init-select2" placeholder="<?php esc_attr_e( 'Select vendor', 'pokmv' ); ?>">
				<option value=""><?php esc_html_e( 'No vendor', 'pokmv' ); ?></option>
				<?php foreach ( pokmv_get_vendor_list() as $vendor_id => $vendor_name ) : ?>
					<option value="<?php echo esc_attr( $vendor_id ); ?>" <?php echo $vendor_id === $selected_vendor_id ? 'selected' : ''; ?>><?php echo esc_html( $vendor_name ); ?></option>
				<?php endforeach; ?>
			</select>
			<a href="<?php echo esc_url( admin_url( 'edit-tags.php?post_type=product&taxonomy=' . POKMV_TAXONOMY_NAME ) ); ?>"><?php esc_html_e( 'Manage Vendors', 'pokmv' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Handle save product
	 *
	 * @param  integer $post_id Post ID.
	 * @param  object  $post    Post object.
	 * @param  boolean $update  Update or not.
	 */
	public function handle_save_product( $post_id, $post, $update ) {
		if ( isset( $_POST['vendor'] ) ) {
			wp_set_post_terms( $post_id, array( intval( $_POST['vendor'] ) ), POKMV_TAXONOMY_NAME );
		}
	}

	/**
	 * Assign value for quick edit data
	*
	* @param array $column
	* @param integer $post_id
	*
	 * @return void
	 **/
	function render_quick_edit_vendor_data( $column, $post_id ) {
		$product_vendor_id = pokmv_get_product_vendor( wc_get_product( $post_id ) );
		switch ( $column ) {
			case 'name' :
				?>
				<div class="hidden" id="pokmv_vendor_id_inline_<?php echo esc_attr( $post_id ); ?>">
					<div id="pokmv_vendor_id"><?php echo esc_html( $product_vendor_id ); ?></div>
				</div>
				<?php
				break;
			default :
				break;
		}
	}

	/**
	 * Render quick product option
	 */
	public function quick_edit_option() {
		?>
		<div class="pokmv-fields" style="clear:both;">
			<label>
				<span class="title"><?php esc_html_e( 'Vendor', 'pokmv' ); ?></span>
				<span class="input-text-wrap">
					<select class="vendor pokmv-vendor" name="vendor_override">
						<option value=""><?php esc_html_e( 'No vendor', 'pokmv' ); ?></option>
						<?php
						foreach ( pokmv_get_vendor_list() as $vendor_id => $vendor_name ) {
							echo '<option value="' . esc_attr( $vendor_id ) . '">' . esc_html( $vendor_name ) . '</option>';
						}
						?>
					</select>
				</span>
			</label>
		</div>
		
		<script>
			(function($){
				$('#the-list').on('click', '.editinline', function(){
					var post_id = $(this).closest('tr').attr('id');

					post_id = post_id.replace("post-", "");

					var $pokmv_vendor_id_inline = $('#pokmv_vendor_id_inline_' + post_id).find('#pokmv_vendor_id').text();

					$( 'select[name="vendor_override"] option:selected', '.inline-edit-row' ).attr( 'selected', false ).change();
					$( 'select[name="vendor_override"] option[value="' + $pokmv_vendor_id_inline + '"]' ).attr( 'selected', 'selected' ).change();
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Render bulk product option
	 */
	public function product_bulk_option() {
		?>
		<label>
			<span class="title"><?php esc_html_e( 'Vendor', 'pokmv' ); ?></span>
			<span class="input-text-wrap">
				<select class="vendor" name="vendor_override">
					<option value=""><?php esc_html_e( '— No change —', 'woocommerce' ); ?></option>
					<option value="0"><?php esc_html_e( 'No vendor', 'pokmv' ); ?></option>
					<?php
					foreach ( pokmv_get_vendor_list() as $vendor_id => $vendor_name ) {
						echo '<option value="' . esc_attr( $vendor_id ) . '">' . esc_html( $vendor_name ) . '</option>';
					}
					?>
				</select>
			</span>
		</label>
		<?php
	}

	/**
	 * Process quick & bulk edit
	 *
	 * @param  object $product Product object.
	 */
	public function process_quick_bulk_edit( $product ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( isset( $_REQUEST['vendor_override'] ) && '' !== $_REQUEST['vendor_override'] ) {
			if ( 0 !== intval( $_REQUEST['vendor_override'] ) ) {
				$posted_vendor_id = (int) $_REQUEST['vendor_override'];

				wp_set_post_terms( $product->get_id(), array( $posted_vendor_id ), POKMV_TAXONOMY_NAME );
			} else {
				wp_set_post_terms( $product->get_id(), array(), POKMV_TAXONOMY_NAME );
			}
		}
	}



	/**
	 * Add vendor info to order items
	 *
	 * @param integer $item_id Item ID.
	 * @param object  $item    Order item object.
	 */
	public function add_vendor_info( $item_id, $item ) {
		if ( 'line_item' === $item->get_type() ) {
			$vendor_id = pokmv_get_product_vendor( wc_get_product( $item->get_product_id() ) );
			if ( 0 !== $vendor_id ) {
				echo '<div class="wc-order-item-variation"><strong>' . esc_html__( 'Vendor:', 'pokmv' ) . '</strong> ' . esc_html( pokmv_get_vendor_store_name( $vendor_id ) ) . '</div>';
			}
		}
	}

}
