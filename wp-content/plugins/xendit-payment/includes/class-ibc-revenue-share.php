<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IBC_Revenue_Share {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'), 99);
        add_action('admin_init', array($this, 'register_settings'));
        add_filter('mycred_types', array($this, 'register_mycred_point_type'));
        add_action('wp_ajax_process_monthly_ibc_payout', array($this, 'process_monthly_ibc_payout'));
    }

    /**
     * Register the ibc_internal point type in myCred dynamically
     */
    public function register_mycred_point_type($types) {
        if (!isset($types['ibc_internal'])) {
            $types['ibc_internal'] = 'IBC Internal (Rev Share)';
        }
        return $types;
    }

    /**
     * Add sub-menu under Xendit Payment
     */
    public function add_admin_menu() {
        add_submenu_page(
            'xendit-payment-settings',
            'IBC Revenue Share',
            'IBC Revenue Share',
            'manage_options',
            'ibc-revenue-share',
            array($this, 'admin_page_html')
        );
    }

    /**
     * Register options
     */
    public function register_settings() {
        register_setting('ibc_revenue_share_group', 'ibc_revshare_dev_pool');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_dev_hot_wallet');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_dev_private_key');
        
        register_setting('ibc_revenue_share_group', 'ibc_revshare_marketing_pool');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_marketing_hot_wallet');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_marketing_private_key');
        
        register_setting('ibc_revenue_share_group', 'ibc_revshare_owner_pool');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_owner_hot_wallet');
        register_setting('ibc_revenue_share_group', 'ibc_revshare_owner_private_key');
    }

    /**
     * Helper to render pool input rows
     */
    private function render_pool_inputs($option_name, $label) {
        $prefix = str_replace('_pool', '', $option_name);
        $hw_option = $prefix . '_hot_wallet';
        $pk_option = $prefix . '_private_key';
        
        $hw_val = get_option($hw_option, '');
        $pk_val = get_option($pk_option, '');

        $pool_data = get_option($option_name, array());
        if (!is_array($pool_data)) $pool_data = array();

        echo '<h3>' . esc_html($label) . '</h3>';
        echo '<table class="form-table" style="background:#f9f9f9; padding: 10px; margin-bottom: 10px;">';
        echo '<tr><th scope="row" style="padding-left:10px;">Source Hot Wallet Address</th><td><input type="text" name="' . esc_attr($hw_option) . '" value="' . esc_attr($hw_val) . '" class="regular-text" /></td></tr>';
        echo '<tr><th scope="row" style="padding-left:10px;">Source Private Key</th><td><input type="password" name="' . esc_attr($pk_option) . '" value="' . esc_attr($pk_val) . '" class="regular-text" /></td></tr>';
        echo '</table>';

        echo '<table class="form-table ' . esc_attr($option_name) . '-table">';
        echo '<thead><tr><th>User (Username)</th><th>Persentase (%)</th><th>BSC Wallet Address</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        
        $total_percentage = 0;
        foreach ($pool_data as $index => $data) {
            $user_id = isset($data['user_id']) ? esc_attr($data['user_id']) : '';
            $percentage = isset($data['percentage']) ? floatval($data['percentage']) : 0;
            $wallet = isset($data['wallet']) ? esc_attr($data['wallet']) : '';
            $total_percentage += $percentage;

            echo '<tr>';
            echo '<td>';
            wp_dropdown_users(array(
                'name' => esc_attr($option_name) . '[' . $index . '][user_id]',
                'selected' => $user_id,
                'show_option_none' => '-- Pilih User --'
            ));
            echo '</td>';
            echo '<td><input type="number" step="0.01" min="0" max="100" name="' . esc_attr($option_name) . '[' . $index . '][percentage]" value="' . $percentage . '" required /></td>';
            echo '<td><input type="text" name="' . esc_attr($option_name) . '[' . $index . '][wallet]" value="' . $wallet . '" class="regular-text" required /></td>';
            echo '<td><button type="button" class="button remove-row">Remove</button></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<p><strong>Total Persentase saat ini: ' . $total_percentage . '%</strong> <span style="color:red; font-size: 12px;">(Harus tepat 100% agar pembagian sempurna)</span></p>';
        echo '<button type="button" class="button add-row" data-target=".' . esc_attr($option_name) . '-table" data-name="' . esc_attr($option_name) . '">Add Member</button>';
        echo '<hr>';
    }

    /**
     * Admin UI
     */
    public function admin_page_html() {
        ?>
        <div class="wrap">
            <h1>IBC Revenue Share (1:7)</h1>
            <p>Setiap User melakukan Withdraw, total nilai withdraw akan dibagi 7, dan hasilnya akan dibagikan ke dompet internal Tim Developer, Marketing, dan Owner berdasarkan persentase di bawah ini.</p>
            
            <h2 class="nav-tab-wrapper">
                <a href="#tab-settings" class="nav-tab nav-tab-active" id="tab-link-settings">Pengaturan Persentase</a>
                <a href="#tab-payout" class="nav-tab" id="tab-link-payout">Pencairan Bulanan (Payout)</a>
            </h2>

            <div id="tab-settings" class="tab-content">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('ibc_revenue_share_group');
                    
                    $this->render_pool_inputs('ibc_revshare_dev_pool', 'Tim Developer');
                    $this->render_pool_inputs('ibc_revshare_marketing_pool', 'Tim Marketing');
                    $this->render_pool_inputs('ibc_revshare_owner_pool', 'Tim Owner');

                    submit_button('Save Settings');
                    ?>
                </form>
            </div>

            <div id="tab-payout" class="tab-content" style="display:none;">
                <h3>Status Internal Wallet</h3>
                <p>Klik tombol di bawah ini untuk mengeksekusi pengiriman otomatis saldo IBC Internal (myCred) milik Tim ke alamat BSC mereka masing-masing. Saldo mereka akan dikembalikan ke 0 setelah berhasil.</p>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Role / Tim</th>
                            <th>Saldo Internal (IBC)</th>
                            <th>BSC Wallet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $this->render_payout_rows('Developer', 'ibc_revshare_dev_pool');
                        $this->render_payout_rows('Marketing', 'ibc_revshare_marketing_pool');
                        $this->render_payout_rows('Owner', 'ibc_revshare_owner_pool');
                        ?>
                    </tbody>
                </table>
                <br>
                <button id="btn-process-payout" class="button button-primary button-large">Proses Semua Payout ke BSC</button>
                <div id="payout-log" style="margin-top: 20px; background: #fff; border: 1px solid #ccc; padding: 15px; height: 300px; overflow-y: scroll;">
                    <strong>Log Eksekusi:</strong><br>
                </div>
            </div>

        </div>

        <?php
        // Template dropdown for JavaScript
        $dropdown_template = wp_dropdown_users(array(
            'echo' => false,
            'name' => '##NAME##',
            'show_option_none' => '-- Pilih User --'
        ));
        $dropdown_template = str_replace(array("\r", "\n"), '', $dropdown_template);
        ?>
        <script>
            var userDropdownTemplate = '<?php echo addslashes($dropdown_template); ?>';
            jQuery(document).ready(function($) {
                // Tab switching
                $('.nav-tab').click(function(e) {
                    e.preventDefault();
                    $('.nav-tab').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active');
                    $('.tab-content').hide();
                    $($(this).attr('href')).show();
                });

                // Add row
                $('.add-row').click(function() {
                    var target = $(this).data('target');
                    var name = $(this).data('name');
                    var index = $(target + ' tbody tr').length;
                    
                    var dropdown = userDropdownTemplate.replace('##NAME##', name + '[' + index + '][user_id]');
                    
                    var html = '<tr>';
                    html += '<td>' + dropdown + '</td>';
                    html += '<td><input type="number" step="0.01" min="0" max="100" name="' + name + '[' + index + '][percentage]" value="0" required /></td>';
                    html += '<td><input type="text" name="' + name + '[' + index + '][wallet]" class="regular-text" required /></td>';
                    html += '<td><button type="button" class="button remove-row">Remove</button></td>';
                    html += '</tr>';
                    
                    $(target + ' tbody').append(html);
                });

                // Remove row
                $(document).on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                });

                // Process Payout
                $('#btn-process-payout').click(function() {
                    if(!confirm('Anda yakin ingin mentransfer saldo internal ke jaringan BSC sekarang? Proses ini tidak bisa dibatalkan.')) return;
                    
                    var btn = $(this);
                    btn.prop('disabled', true).text('Sedang Memproses...');
                    $('#payout-log').append('Mulai memproses payout...<br>');

                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: { action: 'process_monthly_ibc_payout' },
                        success: function(res) {
                            if(res.success) {
                                res.data.logs.forEach(function(log) {
                                    $('#payout-log').append(log + '<br>');
                                });
                                $('#payout-log').append('<span style="color:green"><strong>Proses selesai. Silakan refresh halaman.</strong></span><br>');
                            } else {
                                $('#payout-log').append('<span style="color:red">Error: ' + res.data + '</span><br>');
                            }
                            btn.prop('disabled', false).text('Proses Semua Payout ke BSC');
                        },
                        error: function() {
                            $('#payout-log').append('<span style="color:red">Terjadi kesalahan koneksi AJAX.</span><br>');
                            btn.prop('disabled', false).text('Proses Semua Payout ke BSC');
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * Render rows for payout table
     */
    private function render_payout_rows($role_name, $option_name) {
        $pool_data = get_option($option_name, array());
        if (empty($pool_data)) return;

        foreach ($pool_data as $member) {
            $user_id = $member['user_id'];
            $wallet = $member['wallet'];
            $user = get_userdata($user_id);
            $username = $user ? $user->user_login : 'User Not Found';
            
            // Get balance from myCred
            $balance = mycred_get_users_balance($user_id, 'ibc_internal');
            
            echo '<tr>';
            echo '<td>' . esc_html($user_id) . '</td>';
            echo '<td>' . esc_html($username) . '</td>';
            echo '<td>' . esc_html($role_name) . '</td>';
            echo '<td><strong>' . number_format($balance, 2) . ' IBC</strong></td>';
            echo '<td>' . esc_html($wallet) . '</td>';
            echo '</tr>';
        }
    }

    /**
     * Distribute Revenue Share (Triggered on Withdraw Approval)
     */
    public function distribute_revenue($withdraw_amount, $withdraw_id) {
        if (!function_exists('mycred_add')) return;

        $base_bonus = $withdraw_amount / 7;

        $this->distribute_to_pool('ibc_revshare_dev_pool', $base_bonus, $withdraw_id, 'Developer');
        $this->distribute_to_pool('ibc_revshare_marketing_pool', $base_bonus, $withdraw_id, 'Marketing');
        $this->distribute_to_pool('ibc_revshare_owner_pool', $base_bonus, $withdraw_id, 'Owner');
    }

    private function distribute_to_pool($option_name, $base_bonus, $withdraw_id, $role) {
        $pool_data = get_option($option_name, array());
        if (empty($pool_data)) return;

        foreach ($pool_data as $member) {
            $user_id = $member['user_id'];
            $percentage = floatval($member['percentage']);
            if ($percentage <= 0 || !$user_id) continue;

            $amount_to_give = ($base_bonus * $percentage) / 100;
            if ($amount_to_give > 0) {
                mycred_add(
                    'ibc_revshare', 
                    $user_id, 
                    $amount_to_give, 
                    sprintf('Revenue share (%s) from Withdraw #%s', $role, $withdraw_id), 
                    $withdraw_id, 
                    array(), 
                    'ibc_internal'
                );
            }
        }
    }

    /**
     * AJAX Process Monthly Payout
     */
    public function process_monthly_ibc_payout() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        if (!class_exists('IBC_Transfer')) {
            wp_send_json_error('Class IBC_Transfer missing.');
        }

        $ibc_transfer = new IBC_Transfer();
        if (!$ibc_transfer->is_enabled()) {
            wp_send_json_error('IBC Transfer belum dikonfigurasi dengan benar di pengaturan Xendit.');
        }

        $logs = array();
        
        $pools = [
            'Developer' => 'ibc_revshare_dev_pool',
            'Marketing' => 'ibc_revshare_marketing_pool',
            'Owner' => 'ibc_revshare_owner_pool'
        ];

        foreach ($pools as $role => $option_name) {
            $prefix = str_replace('_pool', '', $option_name);
            $hw_address = get_option($prefix . '_hot_wallet');
            $hw_pk = get_option($prefix . '_private_key');

            if (!empty($hw_address) && !empty($hw_pk) && method_exists($ibc_transfer, 'set_custom_hot_wallet')) {
                $ibc_transfer->set_custom_hot_wallet($hw_address, $hw_pk);
            }

            $pool_data = get_option($option_name, array());
            foreach ($pool_data as $member) {
                $user_id = $member['user_id'];
                $wallet = $member['wallet'];
                $balance = mycred_get_users_balance($user_id, 'ibc_internal');

                if ($balance > 0 && !empty($wallet)) {
                    // Try to send IBC to BSC
                    $result = $ibc_transfer->transfer_tokens($wallet, $balance, 'payout_'.$user_id);
                    
                    if ($result['success']) {
                        // Deduct internal balance
                        mycred_add(
                            'ibc_payout', 
                            $user_id, 
                            -$balance, 
                            sprintf('Monthly Payout %s to BSC. TX: %s', $role, $result['tx_hash']), 
                            time(), 
                            array(), 
                            'ibc_internal'
                        );
                        $logs[] = "<span style='color:green;'>SUCCESS [{$role} UID {$user_id}]: {$balance} IBC sent to {$wallet}. TX: {$result['tx_hash']}</span>";
                    } else {
                        $logs[] = "<span style='color:red;'>FAILED [{$role} UID {$user_id}]: {$result['error']}</span>";
                    }
                }
            }
        }

        if (empty($logs)) {
            $logs[] = "Tidak ada saldo yang perlu ditransfer.";
        }

        wp_send_json_success(array('logs' => $logs));
    }
}

// Instantiate
new IBC_Revenue_Share();
