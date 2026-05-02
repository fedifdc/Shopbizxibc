<?php
// Add admin menu for Level Commission Settings
function add_lc_settings_menu() {
    add_menu_page(
        'Level Commission Settings',
        'Level Commission',
        'manage_options',
        'level-commission-settings',
        'render_lc_settings_page',
        'dashicons-admin-generic',
        90
    );
}
add_action('admin_menu', 'add_lc_settings_menu');

// Enqueue custom CSS for modern UI
function lc_settings_custom_css() {
    $screen = get_current_screen();
    if ($screen->id === 'toplevel_page_level-commission-settings') {
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
add_action('admin_head', 'lc_settings_custom_css');

// Render the settings page
function render_lc_settings_page() {
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['settings_nonce']) && wp_verify_nonce($_POST['settings_nonce'], 'save_settings')) {
        // General Settings
        update_option('lc_bonus_period_days', intval($_POST['bonus_period_days']));
        
        $old_cron_time = get_option('lc_cron_execution_time', '11:30');
        $new_cron_time = sanitize_text_field($_POST['cron_execution_time']);
        update_option('lc_cron_execution_time', $new_cron_time);
        
        // If cron time changed, clear the schedule so it reschedules
        if ($old_cron_time !== $new_cron_time) {
            wp_clear_scheduled_hook('lc_daily_cron_event');
        }

        // Badge Settings
        update_option('lc_starter_commission', sanitize_text_field($_POST['starter_commission']));
        update_option('lc_basic_commission', sanitize_text_field($_POST['basic_commission']));
        update_option('lc_premium_commission', sanitize_text_field($_POST['premium_commission']));
        update_option('lc_prestige_commission', sanitize_text_field($_POST['prestige_commission']));
      	update_option('lc_free_member_commission', sanitize_text_field($_POST['free_member_commission']));

        // Commission Levels
        $commission_rates = isset($_POST['commission_rates']) ? array_map('floatval', $_POST['commission_rates']) : [];
        update_option('lc_commission_data', $commission_rates);

        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Get settings
    $bonus_period_days = get_option('lc_bonus_period_days', 30);
    $cron_execution_time = get_option('lc_cron_execution_time', '11:30');
    $starter_commission = get_option('lc_starter_commission', 0);
    $basic_commission = get_option('lc_basic_commission', 0);
    $premium_commission = get_option('lc_premium_commission', 0);
    $prestige_commission = get_option('lc_prestige_commission', 0);
   	$free_member_commission = get_option('lc_free_member_commission', 0);
    $commission_data = get_option('lc_commission_data', []);

    ?>
    <div class="wrap">
        <h1>Level Commission Settings</h1>
        <form method="POST" action="">
            <?php wp_nonce_field('save_settings', 'settings_nonce'); ?>
            
            <!-- General Settings -->
            <h2>General Settings</h2>
            <table class="form-table">
                <tr>
                    <th>Bonus Period (Days)</th>
                    <td><input type="number" name="bonus_period_days" value="<?php echo esc_attr($bonus_period_days); ?>" min="1" required></td>
                </tr>
                <tr>
                    <th>Cron Execution Time (HH:MM)</th>
                    <td><input type="time" name="cron_execution_time" value="<?php echo esc_attr($cron_execution_time); ?>" required></td>
                </tr>
            </table>

            <!-- Badge Settings -->
            <h2>Commission for Level 1</h2>
            <table class="form-table">
              	<tr>
                    <th>Free Member Commission (%)</th>
                    <td><input type="number" name="free_member_commission" value="<?php echo esc_attr($free_member_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Starter Commission (%)</th>
                    <td><input type="number" name="starter_commission" value="<?php echo esc_attr($starter_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Basic Commission (%)</th>
                    <td><input type="number" name="basic_commission" value="<?php echo esc_attr($basic_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Premium Commission (%)</th>
                    <td><input type="number" name="premium_commission" value="<?php echo esc_attr($premium_commission); ?>" step="0.01" required></td>
                </tr>
                <tr>
                    <th>Prestige Commission (%)</th>
                    <td><input type="number" name="prestige_commission" value="<?php echo esc_attr($prestige_commission); ?>" step="0.01" required></td>
                </tr>
            </table>

            <!-- Commission Levels -->
            <h2>Commission Levels</h2>
            <table>
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Commission Rate (%)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="commission-settings-table">
                    <?php
                    $level = 2;
                    if (!empty($commission_data)) :
                        foreach ($commission_data as $rate) :
                            ?>
                            <tr>
                                <td>Level <?php echo $level++; ?></td>
                                <td><input type="number" name="commission_rates[]" value="<?php echo esc_attr($rate); ?>" step="0.01" required></td>
                                <td><button type="button" class="button remove-level">Remove</button></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
            <button type="button" class="button add-level">Add New Level</button>
            
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('commission-settings-table');
            const addButton = document.querySelector('.add-level');

            addButton.addEventListener('click', function() {
                const level = table.rows.length + 2;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>Level ${level}</td>
                    <td><input type="number" name="commission_rates[]" step="0.01" required></td>
                    <td><button type="button" class="button remove-level">Remove</button></td>
                `;
                table.appendChild(row);
            });

            table.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-level')) {
                    event.target.closest('tr').remove();
                }
            });
        });
    </script>
    <?php
}
