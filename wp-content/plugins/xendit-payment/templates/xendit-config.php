<?php
// Add menu item to the admin dashboard
add_action('admin_menu', 'xendit_payment_menu');

function xendit_payment_menu() {
    add_menu_page(
        'Xendit Payment Settings',
        'Xendit Payment',
        'manage_options',
        'xendit-payment-settings',
        'xendit_payment_settings_page',
        'dashicons-admin-generic'
    );
}

// Display the settings page
function xendit_payment_settings_page() {
    ?>
    <div class="wrap">
        <h1>Xendit Payment Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('xendit_payment_settings_group');
            do_settings_sections('xendit-payment-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register and define the settings
add_action('admin_init', 'xendit_payment_settings_init');

function xendit_payment_settings_init() {
    register_setting('xendit_payment_settings_group', 'xendit_payment_settings');

    add_settings_section(
        'xendit_payment_settings_section',
        'Xendit API Settings',
        'xendit_payment_settings_section_callback',
        'xendit-payment-settings'
    );

    add_settings_field(
        'xendit_payment_prod_key',
        'Production Key',
        'xendit_payment_prod_key_callback',
        'xendit-payment-settings',
        'xendit_payment_settings_section'
    );

    add_settings_field(
        'xendit_payment_dev_key',
        'Development Key',
        'xendit_payment_dev_key_callback',
        'xendit-payment-settings',
        'xendit_payment_settings_section'
    );

    add_settings_field(
        'xendit_payment_fee',
        'Transaction Fee',
        'xendit_payment_fee_callback',
        'xendit-payment-settings',
        'xendit_payment_settings_section'
    );

    function xendit_payment_fee_callback() {
        $options = get_option('xendit_payment_settings');
        $fee = isset($options['fee']) ? esc_attr($options['fee']) : '';
        echo "<input type='text' name='xendit_payment_settings[fee]' value='$fee' />";
    }
}

function xendit_payment_settings_section_callback() {
    echo 'Enter your Xendit API keys below:';
}

function xendit_payment_prod_key_callback() {
    $options = get_option('xendit_payment_settings');
    $prod_key = isset($options['prod_key']) ? esc_attr($options['prod_key']) : '';
    echo "<input type='text' name='xendit_payment_settings[prod_key]' value='$prod_key' />";
}

function xendit_payment_dev_key_callback() {
    $options = get_option('xendit_payment_settings');
    $dev_key = isset($options['dev_key']) ? esc_attr($options['dev_key']) : '';
    echo "<input type='text' name='xendit_payment_settings[dev_key]' value='$dev_key' />";
}


?>