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

    // === SECTION: Xendit API ===
    add_settings_section(
        'xendit_payment_settings_section',
        'Xendit API Settings',
        'xendit_payment_settings_section_callback',
        'xendit-payment-settings'
    );

    add_settings_field('xendit_payment_prod_key', 'Production Key', 'xendit_payment_prod_key_callback', 'xendit-payment-settings', 'xendit_payment_settings_section');
    add_settings_field('xendit_payment_dev_key', 'Development Key', 'xendit_payment_dev_key_callback', 'xendit-payment-settings', 'xendit_payment_settings_section');
    add_settings_field('xendit_payment_fee', 'Transaction Fee (Rp)', 'xendit_payment_fee_callback', 'xendit-payment-settings', 'xendit_payment_settings_section');

    // === SECTION: IBC Token Bonus ===
    add_settings_section(
        'ibc_token_settings_section',
        'IBC Token Bonus Settings',
        'ibc_token_settings_section_callback',
        'xendit-payment-settings'
    );

    add_settings_field('ibc_bonus_percentage', 'Bonus IBC (%)', 'ibc_bonus_percentage_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('ibc_token_price_idr', 'Harga 1 IBC (Rp)', 'ibc_token_price_idr_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('ibc_token_contract', 'Contract Address IBC', 'ibc_token_contract_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('ibc_token_decimals', 'Token Decimals', 'ibc_token_decimals_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('bsc_rpc_url', 'BSC RPC URL', 'bsc_rpc_url_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('bsc_chain_id', 'BSC Chain ID', 'bsc_chain_id_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('bsc_hot_wallet_address', 'Hot Wallet Address', 'bsc_hot_wallet_address_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('bsc_hot_wallet_private_key', 'Hot Wallet Private Key', 'bsc_hot_wallet_private_key_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
    add_settings_field('ibc_wallet_info', 'Saldo Hot Wallet', 'ibc_wallet_info_callback', 'xendit-payment-settings', 'ibc_token_settings_section');
}

// === Xendit Section Callbacks ===
function xendit_payment_settings_section_callback() {
    echo 'Enter your Xendit API keys below:';
}

function xendit_payment_prod_key_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['prod_key']) ? esc_attr($options['prod_key']) : '';
    echo "<input type='text' name='xendit_payment_settings[prod_key]' value='$val' class='regular-text' />";
}

function xendit_payment_dev_key_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['dev_key']) ? esc_attr($options['dev_key']) : '';
    echo "<input type='text' name='xendit_payment_settings[dev_key]' value='$val' class='regular-text' />";
}

function xendit_payment_fee_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['fee']) ? esc_attr($options['fee']) : '10000';
    echo "<input type='text' name='xendit_payment_settings[fee]' value='$val' class='regular-text' />";
    echo "<p class='description'>Biaya admin per transaksi withdraw (dalam Rupiah)</p>";
}

// === IBC Token Section Callbacks ===
function ibc_token_settings_section_callback() {
    echo '<p>Konfigurasi bonus IBC Token untuk setiap withdraw. Member akan mendapat bonus IBC Token senilai X% dari jumlah withdraw.</p>';
    echo '<p><strong>Contoh:</strong> WD Rp 100.000, Bonus 50%, Harga IBC Rp 1.000 → Member dapat Rp 100.000 + 50 IBC Token</p>';
}

function ibc_bonus_percentage_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['ibc_bonus_percentage']) ? esc_attr($options['ibc_bonus_percentage']) : '0';
    echo "<input type='number' step='0.01' min='0' max='100' name='xendit_payment_settings[ibc_bonus_percentage]' value='$val' class='regular-text' />";
    echo "<p class='description'>Persentase bonus IBC Token dari jumlah WD. Set 0 untuk menonaktifkan.</p>";
}

function ibc_token_price_idr_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['ibc_token_price_idr']) ? esc_attr($options['ibc_token_price_idr']) : '1000';
    echo "<input type='number' step='0.01' min='0' name='xendit_payment_settings[ibc_token_price_idr]' value='$val' class='regular-text' />";
    echo "<p class='description'>Harga 1 IBC Token dalam Rupiah (ditentukan manual)</p>";
}

function ibc_token_contract_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['ibc_token_contract']) ? esc_attr($options['ibc_token_contract']) : '0x0e59f3A747fd8E510368A18c8a3e1C28b6a3D01d';
    echo "<input type='text' name='xendit_payment_settings[ibc_token_contract]' value='$val' class='regular-text' />";
    echo "<p class='description'>Alamat smart contract IBC Token di BSC</p>";
}

function ibc_token_decimals_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['ibc_token_decimals']) ? esc_attr($options['ibc_token_decimals']) : '18';
    echo "<input type='number' min='0' max='18' name='xendit_payment_settings[ibc_token_decimals]' value='$val' class='regular-text' />";
}

function bsc_rpc_url_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['bsc_rpc_url']) ? esc_attr($options['bsc_rpc_url']) : 'https://bsc-dataseed.binance.org/';
    echo "<input type='text' name='xendit_payment_settings[bsc_rpc_url]' value='$val' class='regular-text' />";
    echo "<p class='description'>Mainnet: https://bsc-dataseed.binance.org/ | Testnet: https://data-seed-prebsc-1-s1.binance.org:8545/</p>";
}

function bsc_chain_id_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['bsc_chain_id']) ? esc_attr($options['bsc_chain_id']) : '56';
    echo "<input type='number' name='xendit_payment_settings[bsc_chain_id]' value='$val' class='regular-text' />";
    echo "<p class='description'>Mainnet: 56 | Testnet: 97</p>";
}

function bsc_hot_wallet_address_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['bsc_hot_wallet_address']) ? esc_attr($options['bsc_hot_wallet_address']) : '';
    echo "<input type='text' name='xendit_payment_settings[bsc_hot_wallet_address]' value='$val' class='regular-text' />";
    echo "<p class='description'>Alamat wallet BSC yang menyimpan IBC Token untuk disbursement</p>";
}

function bsc_hot_wallet_private_key_callback() {
    $options = get_option('xendit_payment_settings');
    $val = isset($options['bsc_hot_wallet_private_key']) ? esc_attr($options['bsc_hot_wallet_private_key']) : '';
    echo "<input type='password' name='xendit_payment_settings[bsc_hot_wallet_private_key]' value='$val' class='regular-text' />";
    echo "<p class='description' style='color:red;'>⚠️ RAHASIA! Private key wallet untuk menandatangani transaksi. Jangan dibagikan!</p>";
}

function ibc_wallet_info_callback() {
    $options = get_option('xendit_payment_settings');
    if (empty($options['bsc_hot_wallet_address']) || empty($options['ibc_token_contract'])) {
        echo "<p style='color:gray;'>Isi Hot Wallet Address dan Contract Address terlebih dahulu, lalu Save.</p>";
        return;
    }

    echo "<div id='ibc-wallet-info'>Loading...</div>";
    echo "<script>
        jQuery(document).ready(function($) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: { action: 'get_ibc_wallet_info' },
                success: function(response) {
                    if (response.success) {
                        var d = response.data;
                        $('#ibc-wallet-info').html(
                            '<strong>BNB:</strong> ' + d.bnb_balance + ' BNB<br>' +
                            '<strong>IBC Token:</strong> ' + d.ibc_balance + ' IBC'
                        );
                    } else {
                        $('#ibc-wallet-info').html('<span style=\"color:red\">Error: ' + response.data + '</span>');
                    }
                },
                error: function() {
                    $('#ibc-wallet-info').html('<span style=\"color:red\">Gagal mengambil data</span>');
                }
            });
        });
    </script>";
}
?>