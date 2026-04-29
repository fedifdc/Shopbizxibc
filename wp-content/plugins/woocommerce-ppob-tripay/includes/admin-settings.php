<?php
function ppob_tripay_add_admin_menu()
{
    add_menu_page(
        'PPOB Tripay Settings',
        'PPOB Tripay',
        'manage_options',
        'ppob-tripay-settings',
        'ppob_tripay_settings_page'
    );

    add_submenu_page(
        'ppob-tripay-settings', // Parent menu
        'PPOB Tripay Settings', // Page title
        'Settings', // Menu title
        'manage_options', // Permissions
        'ppob-tripay-settings', // Slug
        'ppob_tripay_settings_page' // Callback function
    );

    add_submenu_page(
        'ppob-tripay-settings', // Parent menu
        'PPOB Tripay Sync', // Page title
        'Sync Products', // Menu title
        'manage_options', // Permissions
        'ppob-tripay-sync', // Slug
        'ppob_sync_admin_page' // Callback function
    );
     add_submenu_page(
        'ppob-tripay-settings', // Parent menu
        'PPOB Tripay WhatsApp', // Page title
        'Message Template', // Menu title
        'manage_options', // Permissions
        'ppob-tripay-whatsapp', // Slug
        'ppob_tripay_whatsapp_templat_message_pln_token' // Callback function
    );
}
add_action('admin_menu', 'ppob_tripay_add_admin_menu');

function ppob_tripay_whatsapp_templat_message_pln_token() {
    // Check for form submission
    if (isset($_POST['pln_token_message_template'])) {
        check_admin_referer('save_pln_token_message_template');
        $template = sanitize_textarea_field($_POST['pln_token_message_template']);
        update_option('ppob_pln_token_message_template', $template);
        echo '<div class="updated"><p>Template updated successfully.</p></div>';
    }

    // Get the saved template
    $saved_template = get_option('ppob_pln_token_message_template', 'Terima kasih, token PLN Anda: %token%');

    ?>
    <div class="wrap">
        <h1>PLN Token Message Template</h1>
        <form method="post">
            <?php wp_nonce_field('save_pln_token_message_template'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="pln_token_message_template">Message Template</label></th>
                    <td>
                        <textarea name="pln_token_message_template" id="pln_token_message_template" rows="5" cols="50" class="large-text"><?php echo esc_textarea($saved_template); ?></textarea>
                        <p class="description">Use <code>%token%</code> where you want to place the PLN token.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Template'); ?>
        </form>
    </div>
    <?php
}
function ppob_tripay_settings_page()
{
    if (isset($_POST['ppob_tripay_save_settings'])) {
        check_admin_referer('ppob_tripay_save_settings_nonce');

        update_option('ppob_tripay_api_key', sanitize_text_field($_POST['ppob_tripay_api_key']));
        update_option('ppob_tripay_base_url', esc_url_raw($_POST['ppob_tripay_base_url']));
        update_option('ppob_tripay_pin', sanitize_text_field($_POST['ppob_tripay_pin']));
        update_option('ppob_tripay_rapid_api_key', sanitize_text_field($_POST['ppob_tripay_rapid_api_key']));
        update_option('ppob_tripay_rapid_base_url', esc_url_raw($_POST['ppob_tripay_rapid_base_url']));

        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }

    $api_key = get_option('ppob_tripay_api_key', '');
    $base_url = get_option('ppob_tripay_base_url', '');
    $pin = get_option('ppob_tripay_pin', '');
    $rapid_api_key = get_option('ppob_tripay_rapid_api_key', '');
    $rapid_base_url = get_option('ppob_tripay_rapid_base_url', '');
?>
    <div class="wrap">
        <h1>PPOB Tripay Settings</h1>
        <form method="post">
            <?php wp_nonce_field('ppob_tripay_save_settings_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th><label for="ppob_tripay_api_key">API Key</label></th>
                    <td><input type="text" id="ppob_tripay_api_key" name="ppob_tripay_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ppob_tripay_base_url">Base URL</label></th>
                    <td><input type="text" id="ppob_tripay_base_url" name="ppob_tripay_base_url" value="<?php echo esc_url($base_url); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ppob_tripay_pin">PIN</label></th>
                    <td><input type="password" id="ppob_tripay_pin" name="ppob_tripay_pin" value="<?php echo esc_attr($pin); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ppob_tripay_rapid_api_key">Rapid API Key</label></th>
                    <td><input type="text" id="ppob_tripay_rapid_api_key" name="ppob_tripay_rapid_api_key" value="<?php echo esc_attr($rapid_api_key); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="ppob_tripay_rapid_base_url">Rapid API Base URL</label></th>
                    <td><input type="text" id="ppob_tripay_rapid_base_url" name="ppob_tripay_rapid_base_url" value="<?php echo esc_url($rapid_base_url); ?>" class="regular-text"></td>
                </tr>
            </table>

            <p><input type="submit" name="ppob_tripay_save_settings" class="button button-primary" value="Save Settings"></p>
        </form>
    </div>
<?php
}

// Halaman admin untuk sync PPOB
function ppob_sync_admin_page()
{
    global $wpdb;
    $tripay = $wpdb->prefix . "tripay_references";

    // $products = $wpdb->get_results("SELECT * FROM {$tripay}");
?>
    <div class="wrap">
        
        <h1>PPOB Sync</h1>
        <!-- CARD SALDO -->
        <div class="card">
            <div class="card-body" >
                <h2 id="saldo">Saldo Anda: Rp. 0</h2>
            </div>
        </div>
        <div id="sync-result">
        </div>
        <!-- Kontainer untuk Kategori -->
        <div id="products">
            <h3>Daftar Produk</h3>
            <!-- Buat option kategori -->
            <div style="margin-bottom: 20px;">
                <select id="ppob-category-select">
                    <option value="">Pilih Kategori</option>
                    <!-- FETCH FROM AJAX -->
                </select>
                <select id="ppob-providers-select">
                    <option value="">Pilih Provider</option>
                    <!-- FETCH FROM AJAX -->
                </select>
                <button id="ppob-sync-products" class="button button-primary">Sync Produk</button>
                <button id="ppob-show-products" class="button button-secondary">Tampilkan</button>
                <div id="ppob-loading" style="display:none;">
                    <p>Loading...</p>
                </div>
                <button id="ppob-edit-products" class="button button-secondary">Bulk Edit Product</button>
                <button style="display:none;" id="ppob-edit-products-apply" class="button button-primary">Apply Bulk</button>
                
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Markup</th>
                        <th>Point Level</th>
                    </tr>
                </thead>
                <tbody id="ppob-products-list">
                    <!-- FETCH FROM AJAX -->
                </tbody>
            </table>
            
        </div>



    </div>
<?php
}

function enqueue_admin_scripts_tripay($hook)
{
    if ($hook !== 'ppob-tripay_page_ppob-tripay-sync') {
        return;
    }
    wp_enqueue_script('ppob-tripay-admin', plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery'], null, true);
    wp_localize_script('ppob-tripay-admin', 'ppob_tripay_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ppob_tripay_nonce')
    ]);
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts_tripay');
