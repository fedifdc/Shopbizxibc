<?php
// Add admin menu for Sponsor Settings
function add_instant_sponsor_settings_menu() {
    add_menu_page(
        'Instant Sponsor Commission',
        'Instant Sponsor Commission',
        'manage_options',
        'instant-sponsor-commission-settings',
        'render_instant_sponsor_commissions_settings_page',
        'dashicons-admin-generic',
        90
    );
}
add_action('admin_menu', 'add_instant_sponsor_settings_menu');

// Enqueue custom CSS for modern UI
function instant_sponsor_settings_custom_css() {
    $screen = get_current_screen();
    if ($screen->id === 'toplevel_page_instant-sponsor-commission-settings') {
        echo '
        <style>
            body {
                font-family: "Arial", sans-serif;
                background-color: #f9f9f9;
            }
            .wrap {
                max-width: 800px;
                margin: 20px auto;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            h1 {
                background-color: #0073aa;
                color: #fff;
                margin: 0;
                padding: 20px;
                text-align: center;
            }
            form {
                padding: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                padding: 12px;
                border-bottom: 1px solid #ddd;
            }
            input[type="number"],
            input[type="text"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .button-primary {
                background-color: #0073aa;
                border-color: #0073aa;
                color: #fff;
                padding: 10px 20px;
                border-radius: 4px;
            }
        </style>
        ';
    }
}
add_action('admin_head', 'instant_sponsor_settings_custom_css');

// Render the settings page
function render_instant_sponsor_commissions_settings_page() {
    // if (!current_user_can('manage_options')) {
    //     return;
    // }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['settings_nonce']) && wp_verify_nonce($_POST['settings_nonce'], 'save_settings')) {
        // Badge Settings
        update_option('starter_commission_instant', sanitize_text_field($_POST['starter_commission_instant']));
        update_option('basic_commission_instant', sanitize_text_field($_POST['basic_commission_instant']));
        update_option('premium_commission_instant', sanitize_text_field($_POST['premium_commission_instant']));
        update_option('prestige_commission_instant', sanitize_text_field($_POST['prestige_commission_instant']));
      	update_option('free_member_commission_instant', sanitize_text_field($_POST['free_member_commission_instant']));

        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Get settings
    $starter_commission = get_option('starter_commission_instant', 0);
    $basic_commission = get_option('basic_commission_instant', 0);
    $premium_commission = get_option('premium_commission_instant', 0);
    $prestige_commission = get_option('prestige_commission_instant', 0);
   	$free_member_commission = get_option('free_member_commission_instant', 0);


    ?>
    <div class="wrap">
        <h1>Sponsor Settings</h1>

        <div style="background: #e7f4f9; border-left: 4px solid #0073aa; padding: 15px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;">Pengaturan Komisi Poin (Point Level & Point Upgrade)</h3>
            <p>Pengaturan persentase komisi untuk <strong>Point Level</strong> dan <strong>Point Upgrade</strong> terintegrasi langsung dengan sistem myCred Hooks.</p>
            <a href="<?php echo admin_url('admin.php?page=mycred-hooks'); ?>" class="button button-secondary" target="_blank">Atur Komisi Poin di myCred Hooks &rarr;</a>
        </div>

        <form method="POST" action="">
            <?php wp_nonce_field('save_settings', 'settings_nonce'); ?>
            
            <!-- Badge Settings -->
            <h2>Instant Voucher Commission (Uang)</h2>
            <p style="margin-bottom: 15px; color: #555;">Pengaturan di bawah ini hanya berlaku untuk persentase komisi instan yang diberikan berupa <strong>saldo uang / voucher</strong>.</p>
            <table class="form-table">
              	<tr>
                    <th>Free Member Commission (%)</th>
                    <td><input type="number" name="free_member_commission_instant" value="<?php echo esc_attr($free_member_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Starter Commission (%)</th>
                    <td><input type="number" name="starter_commission_instant" value="<?php echo esc_attr($starter_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Basic Commission (%)</th>
                    <td><input type="number" name="basic_commission_instant" value="<?php echo esc_attr($basic_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Premium Commission (%)</th>
                    <td><input type="number" name="premium_commission_instant" value="<?php echo esc_attr($premium_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Prestige Commission (%)</th>
                    <td><input type="number" name="prestige_commission_instant" value="<?php echo esc_attr($prestige_commission); ?>" step="0.01" required></td>
                </tr>
            </table>

            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}