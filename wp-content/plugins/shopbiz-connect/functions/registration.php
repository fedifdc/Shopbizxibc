<?php
// Save custom registration fields
function shopbiz_registration( $customer_id ) {
  global $wpdb;
  $userdata = get_userdata($customer_id);
  $reg_data = array(
    'customer_id' => $customer_id,
    'username' => $userdata->user_login,
    'email' => $userdata->user_email,
    'password' => $_POST['password'],
    'referral_id' => $_POST['referral_id'] ?? 1,
  );
  $table_name = $wpdb->prefix . 'shopbiz_registration';
  $wpdb->insert($table_name, $reg_data);
}
add_action( 'woocommerce_created_customer', 'shopbiz_registration' );
