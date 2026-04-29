<?php
// Add admin menu
// function wafu_memberarea_admin_menu() {
//     add_menu_page(
//         'WAFU Member Area Settings', // Page title
//         'Member Area Settings',      // Menu title
//         'manage_options',            // Capability
//         'wafu_memberarea_settings',  // Menu slug
//         'wafu_memberarea_settings_page', // Callback function
//         'dashicons-admin-generic',   // Icon
//         80                           // Position
//     );
// }
// add_action('admin_menu', 'wafu_memberarea_admin_menu');

// Settings page callback
function wafu_memberarea_settings_page() {
    // Check if the user has submitted the form
    if (isset($_POST['submit'])) {
        // Save settings
        update_option('wafu_max_groups', intval($_POST['max_groups']));
        update_option('wafu_max_campaigns', intval($_POST['max_campaigns']));
        update_option('wafu_max_autochat', intval($_POST['max_autochat']));
        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }

    // Get current settings
    $max_groups = get_option('wafu_max_groups', 10); // Default to 10
    $max_campaigns = get_option('wafu_max_campaigns', 5); // Default to 5
    $max_autochat = get_option('wafu_max_autochat', 3); // Default to 3
    ?>
    <div class="wrap">
        <h1>WAFU Member Area Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="max_groups">Max Groups</label></th>
                    <td><input type="number" name="max_groups" id="max_groups" value="<?php echo esc_attr($max_groups); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_campaigns">Max Campaigns</label></th>
                    <td><input type="number" name="max_campaigns" id="max_campaigns" value="<?php echo esc_attr($max_campaigns); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_autochat">Max Autochat</label></th>
                    <td><input type="number" name="max_autochat" id="max_autochat" value="<?php echo esc_attr($max_autochat); ?>" class="regular-text"></td>
                </tr>
                
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
?>