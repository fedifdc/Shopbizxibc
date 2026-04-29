<?php

// Add a new menu item under the "Settings" menu
add_action('admin_menu', 'shopbiz_connect_add_menu');

function shopbiz_connect_add_menu()
{
  add_options_page(
    'ShopBiz Connect Settings',
    'ShopBiz Connect',
    'manage_options',
    'shopbiz-connect-settings',
    'shopbiz_connect_render_settings'
  );
}

// Render the settings page
function shopbiz_connect_render_settings()
{
?>
  <div class="wrap">
    <h1>ShopBiz Connect Settings</h1>
    <form method="post" action="options.php">
      <?php
      // Output the settings fields
      settings_fields('shopbiz-connect-settings');
      do_settings_sections('shopbiz-connect-settings');
      submit_button();
      ?>
    </form>
  </div>
<?php
}

// Register the settings and fields
add_action('admin_init', 'shopbiz_connect_register_settings');

function shopbiz_connect_register_settings()
{
  // Register the settings section
  add_settings_section(
    'shopbiz-connect-section',
    'API Settings',
    'shopbiz_connect_section_callback',
    'shopbiz-connect-settings'
  );

  // Register the API URL field
  add_settings_field(
    'shopbiz_api_url',
    'API URL',
    'shopbiz_connect_api_url_callback',
    'shopbiz-connect-settings',
    'shopbiz-connect-section'
  );

  // Register the API Token field
  add_settings_field(
    'shopbiz_api_token',
    'API Token',
    'shopbiz_connect_api_token_callback',
    'shopbiz-connect-settings',
    'shopbiz-connect-section'
  );

	add_settings_field(
    'shopbiz_api_mlm_url',
    'API MLM Url',
    'shopbiz_connect_api_mlm_url_callback',
    'shopbiz-connect-settings',
    'shopbiz-connect-section'
  );
    add_settings_field(
    'shopbiz_api_mlm_api_key',
    'API Key MLM',
    'shopbiz_connect_api_key_mlm_callback',
    'shopbiz-connect-settings',
    'shopbiz-connect-section'
  );

  // Register the Point Level Percentage field
  add_settings_field(
    'shopbiz_point_level_percentage',
    'Point Level Percentage',
    'shopbiz_connect_point_level_percentage_callback',
    'shopbiz-connect-settings',
    'shopbiz-connect-section'
  );

  // Register the settings
  register_setting('shopbiz-connect-settings', 'shopbiz_api_url');
  register_setting('shopbiz-connect-settings', 'shopbiz_api_token');
  register_setting('shopbiz-connect-settings', 'shopbiz_api_mlm_url');
  register_setting('shopbiz-connect-settings', 'shopbiz_api_mlm_api_key');
  register_setting('shopbiz-connect-settings', 'shopbiz_point_level_percentage');
}

// Callback function for the settings section
function shopbiz_connect_section_callback()
{
  echo '<p>Enter your API settings below:</p>';
}
function shopbiz_connect_api_key_mlm_callback()
{
  $api_key = get_option('shopbiz_api_mlm_api_key');
  echo '<input type="text" name="shopbiz_api_mlm_api_key" value="' . esc_attr($api_key) . '" style="width:70%">';
}

// Callback function for the API URL field
function shopbiz_connect_api_url_callback()
{
  $api_url = get_option('shopbiz_api_url');
  echo '<input type="text" name="shopbiz_api_url" value="' . esc_attr($api_url) . '" style="width:70%">';
}

// Callback function for the API Token field
function shopbiz_connect_api_token_callback()
{
  $api_token = get_option('shopbiz_api_token');
  echo '<input type="text" name="shopbiz_api_token" value="' . esc_attr($api_token) . '" style="width:70%">';
}

// Callback function for the API Token field
function shopbiz_connect_api_mlm_url_callback()
{
  $api_mlm_url = get_option('shopbiz_api_mlm_url');
  echo '<input type="text" name="shopbiz_api_mlm_url" value="' . esc_attr($api_mlm_url) . '" style="width:70%">';
}
// Callback function for the Point Level Percentage field
function shopbiz_connect_point_level_percentage_callback()
{
  $percentage = get_option('shopbiz_point_level_percentage');
  echo '<input type="number" name="shopbiz_point_level_percentage" value="' . esc_attr($percentage) . '" style="width:70%" min="0" max="100" step="0.01">';
}