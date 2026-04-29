<?php
function plugin_resi_lib__print_select($arr_data, $name, $value)
{
	$print_select = "";
	$print_select.= "<select name='$name'>";
	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $value ? 'selected' : '';
		$print_select.= "<option value='{$data['value']}' $selected >{$data['label']}</option>";
	}
	$print_select.= "</select>";
	echo $print_select;
}

function plugin_resi_lib__print_radio($arr_data, $name, $value)
{
	$print_select = "";
	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $value ? 'checked' : '';
		$print_select.= "<input type='radio' name='$name' value='{$data['value']}' $selected > {$data['label']}<br>";
	}
	echo $print_select;
}

function plugin_resi_lib__print_checkbox($arr_data)
{
	$print_select = "";
	foreach ($arr_data as $data) 
	{
		$selected = $data['value'] == $data['saved_value'] ? 'checked' : '';
		$print_select.= "<input type='checkbox' name='{$data['name']}' value='{$data['value']}' $selected > {$data['label']}<br>";
	}
	echo $print_select;
}

function plugin_resi_lib__post_select( $select_id, $selected = 0, $limit = -1) 
{
	$orders = wc_get_orders(array(
	    'limit' => $limit,
	    'orderby' => 'date',
	    'order' => 'DESC',
	    'return' => 'ids',
	) );

    echo '<select name="'. $select_id .'" id="'.$select_id.'" class="select2">';
    echo '<option value="">Select order</option>';
    foreach ($orders as $order_id ) {
		$order = wc_get_order( $order_id );
		$billing_name = $order->get_meta('_billing_first_name' , true);
		$billing_email = $order->get_meta('_billing_email', true);

		$value = '#' . $order_id . ' &#8722; ' . $billing_name . ' &#8722; ' . $billing_email;

        echo '<option value="', $order_id, '"', $selected == $order_id ? ' selected="selected"' : '', '>', $value, '</option>';
    }
    echo '</select>';
}

if ( ! function_exists( 'plugin_resi_is_order' ) ) {
	function plugin_resi_is_order( $post_id = 0 ) {
		return class_exists('OrderUtil') && 'shop_order' === OrderUtil::get_order_type( $post_id );
	}
}