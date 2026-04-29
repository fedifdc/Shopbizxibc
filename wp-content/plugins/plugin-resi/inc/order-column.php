<?php 

function plugin_resi_column($columns) {
    
	$result = 	array_slice($columns, 0, 3, true) +
    			array( 'resi'  => __('Tracking') ) +
    			array_slice($columns, 3, count($columns) - 1, true) ;

    return $result;
}

function plugin_resi_column_manage( $column, $post_id ) 
{	
	global $post;

	switch( $column ) {
		case 'resi' :

			$order_resi = plugin_resi_get_order_data( $post_id );
			$order = wc_get_order($post_id);

			if( ! empty( $order_resi['tracking'] ) && ! empty( $order_resi['courier'] ) && plugin_resi_get_ekspedisi( $order_resi['courier'] ) ) {
				echo "<b>[" . plugin_resi_get_ekspedisi( $order_resi['courier'], 'nama' ) . "]</b> - " . $order_resi['tracking'];
				echo "<p><a href='" . plugin_resi_get_ekspedisi( $order_resi['courier'], 'url' ) . "'>" . plugin_resi_get_ekspedisi( $order_resi['courier'], 'url' ) . "</a></p>";
			}
			elseif( empty( $order_resi['tracking'] ) && $order->get_status() == 'completed' ) {
				printf( __( "Please fill tracking number <a href='%s'>here</a>", PLUGIN_RESI ), admin_url( "post.php?post=".$post_id."&action=edit" ) );
			}
			else{
				echo "-";	
			}

			break;
		default :
			break;

	}

}

add_filter( 'manage_woocommerce_page_wc-orders_columns', 'plugin_resi_column' );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'plugin_resi_column_manage', 100, 2 );
add_filter( 'manage_edit-shop_order_columns', 'plugin_resi_column' );
add_action( 'manage_shop_order_posts_custom_column', 'plugin_resi_column_manage', 100, 2 );