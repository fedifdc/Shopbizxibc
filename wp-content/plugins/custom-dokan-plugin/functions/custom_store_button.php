<?php



add_action( 'dokan_after_store_tabs', 'add_other_button_after_store_tabs' , 99 );

function add_other_button_after_store_tabs( $vendor_id ) {
    $vendor = dokan()->vendor->get( $vendor_id );
    ob_start();
?>

    <?php 
        $sync_data_partner = get_user_meta( $vendor_id, 'sync_data_partner', true );
        $sync_data_partner = ! empty( $sync_data_partner ) ? maybe_unserialize( $sync_data_partner ) : [];
        if ( ! empty( $sync_data_partner ) ) : ?>
        <?php
            
            $user_vendor_sync = true;
            $partner_website = str_replace("https://", $sync_data_partner['website'], '');
            global $wpdb;
            $query = $wpdb->prepare("SELECT user_id FROM `wp_shopbiz_api_keys` WHERE website = %s", $partner_website);
            $owner_id = $wpdb->get_row($query);
            $query_check_user = $wpdb->prepare("SELECT id FROM `wp_shopbiz_user_sync` WHERE user_id = %d AND website = %s", $user_id, $website);
            $is_sync_user = $wpdb->get_row($query_check_user);
        
        ?>
    <li class="dokan-store-support-btn-wrap dokan-right">
             <button onclick="window.open('<?php echo esc_url( $sync_data_partner['website'] ); ?>', '_blank')" class="dokan-btn dokan-btn-theme dokan-btn-sm" style="position: relative; top: 3px"><?php esc_html_e( 'Website', 'dokan-lite' ); ?></a>
        </li>
            <?php if($is_sync_user) : ?>
                <li class="dokan-store-support-btn-wrap dokan-right">
                <button onclick="window.open('<?php echo esc_url( $sync_data_partner['website'] ); ?>', '_blank')" class="dokan-btn dokan-btn-theme dokan-btn-sm" style="position: relative; top: 3px"><?php esc_html_e( 'Hubungkan Akun', 'dokan-lite' ); ?></button>
            </li>
                <?php else : ?>
                    <li class="dokan-store-support-btn-wrap dokan-right">
<button onclick="window.open('<?php echo esc_url( $sync_data_partner['website'] ); ?>', '_blank')" class="dokan-btn dokan-btn-theme dokan-btn-sm" style="position: relative; top: 3px"><?php esc_html_e( 'Akun Terhubung', 'dokan-lite' ); ?></a>
                </li>
                <?php endif; ?>
        <?php endif; ?>
<?php
    echo ob_get_clean();
}