<?php
global $cl_options;
$order_ids = sanitize_text_field ( $_GET['print-order'] );
$order_ids = ( !empty($order_ids) ) ? explode('-', $order_ids) : '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<head>
    <title><?php echo get_bloginfo(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable = yes">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() .'/assets/css/dist/reset.css' ?>" type="text/css" media="all">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() .'/assets/css/dist/style-print.css' ?>" type="text/css" media="all">
</head>

<body>
<div class="print-button" onClick="window.print();"><?php esc_html_e( 'Print Now', 'cepatlakoo' ); ?></div>

<?php if(!empty($order_ids) && is_array($order_ids)) :
    $page_break = 0;
    $sum_order = 0;
    foreach($order_ids as $order_id) :
        $order = wc_get_order( $order_id );
        $order_data = $order->get_data();
        $shop_name = (!empty($cl_options['cepatlakoo_shop_name']) ) ? $cl_options['cepatlakoo_shop_name'] : get_bloginfo();
        $shop_phone = (!empty($cl_options['cepatlakoo_shop_phone']) ) ? $cl_options['cepatlakoo_shop_phone'] : '';
        $shop_address = (!empty($cl_options['cepatlakoo_shop_address']) ) ? $cl_options['cepatlakoo_shop_address'] : '';
        
        if( ($order->shipping_address_1 != $order->billing_address_1) && (!empty($cl_options['cepatlakoo_dropship_shipping_label_status']) ) && $cl_options['cepatlakoo_dropship_shipping_label_status'] == true ){
            $shop = $order->get_formatted_billing_address();
        }
        else{
            $shop = '<p style="white-space: pre-wrap;">'.$shop_name.'<br>'.$shop_address.'<br>'.$shop_phone.'</p>';
        }

        if( !empty($order->get_formatted_shipping_address()) ){
            $cust_shipping = $order->get_formatted_shipping_address();
        }
        else{
            $cust_shipping = apply_filters( 'woocommerce_order_formatted_shipping_address', $order->get_address( 'billing' ) );
            $cust_shipping = WC()->countries->get_formatted_address( $cust_shipping );
        }

        $shipping = $order->get_shipping_methods();

        if(count($shipping) > 0 ){
            foreach($shipping as $ship){
                $shipping = $ship->get_method_title();
                $arr_ship = explode(" Estimasi", $shipping);
                if(is_array($arr_ship) ){
                    $shipping = $arr_ship[0];
               }
            }
        }
        else{
            $shipping = false;
        }

        $sum_order++;
        if( $sum_order > 2 || $page_break+count($order->get_items()) > 30 ) :
            $page_break = 0;
            $sum_order = 0;
        ?>
            <div class="cl_page_break" style="page-break-after: always;"></div>
        <?php endif; ?>

        <div class="container clearfix">
            <section class="page-break-after"></section>
            <div id="label-content">
                <div class="label-inner">
                    <h1 id="logo"><?php echo $shop_name; ?></h1>
                    <div id="content">
                        <h2 id="order-id"><?php _e( 'Order ID:', 'cepatlakoo' ); ?> <span>#<?php echo $order_id; ?></span></h2>
                        <div id="addresses">
                            <div class="address recipient">
                                <p><b><?php _e( 'To:', 'cepatlakoo' ); ?></b></p>
                                <p><?php echo $cust_shipping; ?> <br><?php _e( 'Phone:', 'cepatlakoo' ); ?> <?php echo $order_data['billing']['phone']; ?>
                                </p>
                            </div>
                            <div class="address sender">
                                <p><b><?php _e( 'Sender:', 'cepatlakoo' ); ?></b></p>
                                <?php echo $shop; ?>
                                <?php if( $shipping != false ) : ?>
                                    <p id="shipment">
                                        <span><?php _e( 'Shipping Courier:', 'cepatlakoo' ); ?></span>
                                        <span><b><?php echo $shipping; ?></b></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="orders">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Orders', 'cepatlakoo' ); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach( $order->get_items() as $item_id => $item_product ) :
                                        $weight = '';
                                        $product = $item_product->get_product();
                                        
                                        if( (!empty($cl_options['cepatlakoo_shipping_weight']) ) && $cl_options['cepatlakoo_shipping_weight'] == true ){
                                            $weight = $product->get_weight().esc_attr( get_option('woocommerce_weight_unit' ) );
                                        }
                                        $page_break++;
                                        $meta_data = $item_product->get_formatted_meta_data( '' );
                                        $var = array();
                                        foreach ($meta_data as $meta){
                                            if( $meta->display_key != '_reduced_stock' )
                                                $var[] = $meta->display_key.': '.$meta->value;
                                        }
                                        $var = implode(', ', $var);
                                        ?>
                                        <tr>
                                            <td><?php echo $item_product->get_quantity(); ?> x <b><?php echo $product->get_name().'</b><br><small>'. $var .'</small>'; ?></td>
                                            <td><?php echo ( !empty($weight) ) ? '<span class="cl-shipping-weight">@ '.$weight.'</span>' : ''; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="page-break-before"></section>
    <?php endforeach;
endif;
?>
</body>
<script>
    window.print();
</script>
</html>
