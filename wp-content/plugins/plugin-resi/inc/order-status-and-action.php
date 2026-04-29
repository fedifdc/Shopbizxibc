<?php 

/** 
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
 */
function plugin_resi_order_shipped() {
    register_post_status( 'wc-shipped', array(
        'label'                     => __('Dikirim',PLUGIN_RESI),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Dikirim <span class="count">(%s)</span>', 'Dikirim <span class="count">(%s)</span>' , PLUGIN_RESI)
    ) );
}
add_action( 'init', 'plugin_resi_order_shipped' );

// Add to list of WC Order statuses
function add_shipped_order_statuses( $order_statuses ) {
    
    $order_statuses['wc-shipped'] = __('Dikirim',PLUGIN_RESI);

    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'add_shipped_order_statuses' );

/** 
 * Add JS to check if nomor resi is there
 */

// Order action send tracking number
add_action( 'woocommerce_order_actions','plugin_resi_add_order_action_send_tracking_number' );
function plugin_resi_add_order_action_send_tracking_number( $actions ) {
    $actions['plugin_resi_send_tracking_number'] = __( 'Kirim Kode Resi', PLUGIN_RESI );
    
    return $actions;
}

add_action( 'woocommerce_order_action_plugin_resi_send_tracking_number' , 'plugin_resi_action_send_tracking_number',10,1 );

function plugin_resi_action_send_tracking_number( $order = false) {

    if (!$order) return;

    // Ensure this function sends the email with tracking number
    plugin_resi_do_send_email( $order );
	
}

add_action( 'woocommerce_order_status_changed','plugin_resi_order_shipped_send_email',10,3 );
function plugin_resi_order_shipped_send_email(  $id, $old_status, $new_status ) {
    if ( $new_status != 'shipped' ) return;
    $order = wc_get_order( $id );
    if ( 'yes' === $order->get_meta( 'shipped_email_sent', true ) ) return;

    plugin_resi_do_send_email( $order );
}

/**
 * Add View Resi
 */
add_action( 'woocommerce_my_account_my_orders_actions', 'plugin_resi_my_orders_actions', 10, 2 );
function plugin_resi_my_orders_actions( $actions, $order ) {
    $is_shipped = $order->get_meta( 'shipped_email_sent', true );
    $resi       = $order->get_meta( 'plugin_resi_data', true );
    if( isset( $is_shipped ) && 'yes' === $is_shipped && $resi ) {
        $actions['view_resi'] = array(
            'url'  => esc_url( $order->get_view_order_url() . '#tracking' ),
            'name' => __( 'Tracking', 'plugin-resi' ),
        );
    }
    return $actions;
}

/**
 * Add detail resi to view order page 
 */
function plugin_resi_view_order_resi( $order_id ) {
    $order = wc_get_order( $order_id );
    $resi  = $order->get_meta( 'plugin_resi_data', true );
    if( $resi ):
    ?>
    <div id="tracking">
        <h2><?php _e( 'Tracking', 'plugin-resi' ); ?></h2>
        <table class="woocommerce-table">
            <?php 
            if( !empty( $resi['courier'] ) ):
                $courier = plugin_resi_get_ekspedisi( $resi['courier'] );
                ?>
                <tr>
                    <th width="50%"><?php _e( 'Kurir', 'plugin-resi' ); ?></th>
                    <td>
                        <?php 
                        if( $courier ) {
                            echo esc_html( $courier['nama'] );
                            if( !empty( $courier['url'] ) ) {
                                ?>
                                <br><a href="<?php echo esc_url( $courier['url'] ); ?>" target="_blank" rel="nofollow"><?php echo esc_url( $courier['url'] ); ?></a>
                                <?php
                            }
                        } else {
                            echo esc_html( strtoupper( $resi['courier'] ) );
                        }
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if( !empty( $resi['service'] ) ): ?>
                <tr>
                    <th><?php _e( 'Service', 'plugin-resi' ); ?></th>
                    <td><?php echo esc_html( $resi['service'] ); ?></td>
                </tr>
            <?php endif; ?>
            <?php if( !empty( $resi['tracking'] ) ): ?>
                <tr>
                    <th><?php _e( 'Nomor Resi', 'plugin-resi' ); ?></th>
                    <td><?php echo esc_html( strtoupper( $resi['tracking'] ) ); ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php
    endif;
}
add_action( 'woocommerce_view_order', 'plugin_resi_view_order_resi', 100 );
