<?php
/**
 * Admin panel: Tier management, Rank Cap, Upgrade criteria.
 */

defined('ABSPATH') || exit;

add_action('admin_menu', 'crs_admin_menu');
function crs_admin_menu() {
    add_menu_page(
        'Commission Royalty',
        'Royalty System',
        'manage_options',
        'commission-royalty',
        'crs_admin_dashboard',
        'dashicons-money-alt',
        56
    );
}

function crs_admin_dashboard() {
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tiers';
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Commission Royalty System', 'commission-royalty-system'); ?></h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=commission-royalty&tab=tiers" class="nav-tab <?php echo $active_tab === 'tiers' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Dynamic Tiers', 'commission-royalty-system'); ?>
            </a>
            <a href="?page=commission-royalty&tab=rankcaps" class="nav-tab <?php echo $active_tab === 'rankcaps' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Rank Caps', 'commission-royalty-system'); ?>
            </a>
            <a href="?page=commission-royalty&tab=upgrade" class="nav-tab <?php echo $active_tab === 'upgrade' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Upgrade Criteria', 'commission-royalty-system'); ?>
            </a>
            <a href="?page=commission-royalty&tab=dashboard" class="nav-tab <?php echo $active_tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Dashboard', 'commission-royalty-system'); ?>
            </a>
            <a href="?page=commission-royalty&tab=settings" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Settings', 'commission-royalty-system'); ?>
            </a>
        </h2>

        <div class="tab-content">
            <?php
            switch ($active_tab) {
                case 'tiers':
                    crs_render_tiers_tab();
                    break;
                case 'rankcaps':
                    crs_render_rank_caps_tab();
                    break;
                case 'upgrade':
                    crs_render_upgrade_tab();
                    break;
                case 'dashboard':
                    crs_render_live_dashboard();
                    break;
                case 'settings':
                    crs_render_settings_tab();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

// ============================================================
// Tiers Tab
// ============================================================
function crs_render_tiers_tab() {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_tiers';

    // Handle form submissions
    if (isset($_POST['crs_action']) && $_POST['crs_action'] === 'save_tiers') {
        check_admin_referer('crs_save_tiers');

        if (isset($_POST['tier_rate']) && is_array($_POST['tier_rate'])) {
            foreach ($_POST['tier_rate'] as $tier_id => $rate) {
                $rate = floatval($rate);
                $wpdb->update($table,
                    ['rate_percent' => $rate, 'updated_at' => current_time('mysql')],
                    ['id' => intval($tier_id)]
                );
            }
        }

        if (isset($_POST['new_tier_rate'])) {
            $new_rate = floatval($_POST['new_tier_rate']);
            $max_tier = $wpdb->get_var("SELECT MAX(tier_number) FROM $table");
            $new_tier_number = $max_tier ? (int)$max_tier + 1 : 1;
            $wpdb->insert($table, [
                'tier_number' => $new_tier_number,
                'rate_percent' => $new_rate,
            ]);
        }

        echo '<div class="notice notice-success"><p>Tiers saved successfully.</p></div>';
    }

    // Handle delete
    if (isset($_POST['crs_action']) && $_POST['crs_action'] === 'delete_tier') {
        check_admin_referer('crs_delete_tier');
        $tier_id = intval($_POST['tier_id']);
        $wpdb->delete($table, ['id' => $tier_id]);

        // Reorder tier numbers
        $tiers = $wpdb->get_results("SELECT id FROM $table ORDER BY tier_number ASC", ARRAY_A);
        foreach ($tiers as $i => $t) {
            $wpdb->update($table,
                ['tier_number' => $i + 1],
                ['id' => $t['id']]
            );
        }

        echo '<div class="notice notice-success"><p>Tier deleted.</p></div>';
    }

    $tiers = $wpdb->get_results("SELECT * FROM $table ORDER BY tier_number ASC", ARRAY_A);
    ?>
    <h2>Dynamic Tiers Management</h2>
    <p>Configure the percentage rate for each tier. Tiers are numbered sequentially from top to bottom.</p>

    <form method="post" action="">
        <?php wp_nonce_field('crs_save_tiers'); ?>
        <input type="hidden" name="crs_action" value="save_tiers">

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Tier</th>
                    <th>Rate (%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tiers as $tier): ?>
                <tr>
                    <td><strong>Tier <?php echo esc_html($tier['tier_number']); ?></strong></td>
                    <td>
                        <input type="number" step="0.01" min="0" max="100" name="tier_rate[<?php echo $tier['id']; ?>]"
                            value="<?php echo esc_attr($tier['rate_percent']); ?>" class="regular-text">
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <?php wp_nonce_field('crs_delete_tier'); ?>
                            <input type="hidden" name="crs_action" value="delete_tier">
                            <input type="hidden" name="tier_id" value="<?php echo $tier['id']; ?>">
                            <button type="submit" class="button button-link-delete" onclick="return confirm('Delete this tier?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Tier</h3>
        <table class="form-table">
            <tr>
                <th><label>Rate (%)</label></th>
                <td>
                    <input type="number" step="0.01" min="0" max="100" name="new_tier_rate" value="0" class="regular-text">
                </td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Tiers & Add New</button>
        </p>
    </form>
    <?php
}

// ============================================================
// Rank Caps Tab
// ============================================================
function crs_render_rank_caps_tab() {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_rank_caps';

    if (isset($_POST['crs_action']) && $_POST['crs_action'] === 'save_caps') {
        check_admin_referer('crs_save_caps');

        if (isset($_POST['cap_max_tier']) && is_array($_POST['cap_max_tier'])) {
            foreach ($_POST['cap_max_tier'] as $cap_id => $max_tier) {
                $wpdb->update($table,
                    ['max_tier' => intval($max_tier), 'updated_at' => current_time('mysql')],
                    ['id' => intval($cap_id)]
                );
            }
        }

        echo '<div class="notice notice-success"><p>Rank caps saved.</p></div>';
    }

    $caps = $wpdb->get_results("SELECT * FROM $table ORDER BY max_tier ASC", ARRAY_A);
    ?>
    <h2>Rank Cap Configuration</h2>
    <p>Set the maximum tier each rank can receive. Rank "Free" cannot receive any royalty (cap = 0).</p>

    <form method="post" action="">
        <?php wp_nonce_field('crs_save_caps'); ?>
        <input type="hidden" name="crs_action" value="save_caps">

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Max Tier Allowed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($caps as $cap): ?>
                <tr>
                    <td><strong><?php echo esc_html($cap['rank_label']); ?></strong> (<?php echo esc_html($cap['rank_key']); ?>)</td>
                    <td>
                        <input type="number" min="0" max="50" name="cap_max_tier[<?php echo $cap['id']; ?>]"
                            value="<?php echo esc_attr($cap['max_tier']); ?>" class="small-text">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Rank Caps</button>
        </p>
    </form>
    <?php
}

// ============================================================
// Upgrade Criteria Tab
// ============================================================
function crs_render_upgrade_tab() {
    global $wpdb;
    $caps_table = $wpdb->prefix . 'crs_rank_caps';

    // --- Save DL Qualification Requirements ---
    if (isset($_POST['crs_action']) && $_POST['crs_action'] === 'save_dl_qualification') {
        check_admin_referer('crs_save_dl_qualification');

        if (isset($_POST['dl_qual']) && is_array($_POST['dl_qual'])) {
            foreach ($_POST['dl_qual'] as $rank_key => $data) {
                // Assemble alt_requirements JSON from individual fields
                $alt = [
                    'min_dl'        => intval($data['alt_min_dl'] ?? 0),
                    'min_omset'     => floatval($data['alt_min_omset'] ?? 0),
                    'dl_rank'       => sanitize_key($data['alt_dl_rank'] ?? 'free'),
                    'dl_rank_count' => intval($data['alt_dl_rank_count'] ?? 0),
                ];
                // Only save JSON if at least one alt field has a value
                $alt_json = '';
                if ($alt['min_dl'] > 0 || $alt['min_omset'] > 0 || $alt['dl_rank'] !== 'free' || $alt['dl_rank_count'] > 0) {
                    $alt_json = wp_json_encode($alt);
                }

                $wpdb->update($caps_table, [
                    'min_direct_dl'      => intval($data['min_direct_dl']),
                    'min_total_omset'    => floatval($data['min_total_omset']),
                    'min_dl_rank'        => sanitize_key($data['min_dl_rank']),
                    'min_dl_rank_count'  => intval($data['min_dl_rank_count']),
                    'alt_requirements'   => $alt_json,
                    'updated_at'         => current_time('mysql'),
                ], ['rank_key' => sanitize_key($rank_key)]);
            }
        }

        echo '<div class="notice notice-success is-dismissible"><p>✅ DL Qualification requirements saved.</p></div>';
    }



    $caps = $wpdb->get_results("SELECT * FROM $caps_table ORDER BY CASE rank_key WHEN 'free' THEN 0 WHEN 'starter' THEN 1 WHEN 'basic' THEN 2 WHEN 'premium' THEN 3 WHEN 'prestige' THEN 4 END ASC", ARRAY_A);
    $ranks = ['free', 'starter', 'basic', 'premium', 'prestige'];

    // Parse alt_requirements JSON for form display
    $alt_data = [];
    foreach ($caps as $cap) {
        $parsed = [];
        if (!empty($cap['alt_requirements'])) {
            $parsed = json_decode($cap['alt_requirements'], true) ?: [];
        }
        $alt_data[$cap['rank_key']] = [
            'min_dl'          => isset($parsed['min_dl']) ? (int)$parsed['min_dl'] : 0,
            'min_omset'       => isset($parsed['min_omset']) ? (float)$parsed['min_omset'] : 0,
            'dl_rank'         => isset($parsed['dl_rank']) ? $parsed['dl_rank'] : 'free',
            'dl_rank_count'   => isset($parsed['dl_rank_count']) ? (int)$parsed['dl_rank_count'] : 0,
        ];
    }

    $top_omset = $wpdb->get_results(
        "SELECT idwp, nama, total_omset FROM {$wpdb->prefix}member ORDER BY total_omset DESC LIMIT 5",
        ARRAY_A
    );

    // Rank distribution
    $rank_dist = [];
    foreach ($ranks as $rk) {
        $threshold = crs_rank_threshold($rk);
        $next_threshold = crs_next_rank_threshold($rk);

        if ($rk === 'free') {
            $cnt = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}member WHERE idwp IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'point_upgrade' AND CAST(meta_value AS SIGNED) < 250000)");
        } elseif ($next_threshold === null) {
            // Prestige (no upper bound)
            $cnt = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}member WHERE idwp IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'point_upgrade' AND CAST(meta_value AS SIGNED) >= %d)",
                $threshold
            ));
        } else {
            $cnt = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}member WHERE idwp IN (SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'point_upgrade' AND CAST(meta_value AS SIGNED) >= %d AND CAST(meta_value AS SIGNED) < %d)",
                $threshold, $next_threshold
            ));
        }
        $rank_dist[$rk] = $cnt;
    }
    ?>
    <h2>DL Qualification Requirements (Per-Rank)</h2>
    <p class="description">Syarat downline qualification untuk tiap rank. Member harus pass syarat ini baru bisa terima royalti di tier-nya. Jika gagal, tier di-skip ke upline berikutnya.</p>

    <div class="inline notice notice-info">
        <p><strong>Top 5 Member by Total Omset:</strong></p>
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($top_omset as $m): ?>
            <li><?php echo esc_html($m['nama']); ?> (ID <?php echo $m['idwp']; ?>) — Rp <?php echo number_format($m['total_omset'], 0, ',', '.'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="inline notice notice-info">
        <p><strong>Rank Distribution (by point_upgrade):</strong></p>
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($ranks as $rk): ?>
            <li><?php echo esc_html(ucfirst($rk)); ?>: <strong><?php echo (int)$rank_dist[$rk]; ?></strong> members</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <form method="post" action="">
        <?php wp_nonce_field('crs_save_dl_qualification'); ?>
        <input type="hidden" name="crs_action" value="save_dl_qualification">

        <table class="wp-list-table widefat fixed striped" style="table-layout:fixed;">
            <colgroup>
                <col style="width:90px;">
                <col style="width:65px;">
                <col style="width:110px;">
                <col style="width:65px;">
                <col style="width:140px;">
                <col style="width:65px;">
                <col style="width:110px;">
                <col style="width:65px;">
                <col style="width:140px;">
            </colgroup>
            <thead>
                <tr>
                    <th rowspan="2">Rank</th>
                    <th colspan="4" style="text-align:center; border-bottom:2px solid #2271b1;">Syarat Utama (AND — semua harus pass)</th>
                    <th colspan="4" style="text-align:center; border-bottom:2px solid #00a32e;">Syarat Alternatif (OR — pass salah satu sudah cukup)</th>
                </tr>
                <tr style="font-size:12px;">
                    <th>Min DL</th>
                    <th>Min Rank DL</th>
                    <th>Count</th>
                    <th>Min Omset</th>
                    <th>Alt Min DL</th>
                    <th>Alt Min Rank</th>
                    <th>Alt Count</th>
                    <th>Alt Min Omset</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($caps as $cap): $alt = $alt_data[$cap['rank_key']] ?? []; ?>
                <tr>
                    <td><strong><?php echo esc_html(ucfirst($cap['rank_key'])); ?></strong></td>
                    <!-- Main requirements -->
                    <td>
                        <input type="number" min="0" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][min_direct_dl]"
                            value="<?php echo esc_attr($cap['min_direct_dl']); ?>" class="small-text" style="width:50px;">
                    </td>
                    <td>
                        <select name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][min_dl_rank]" style="width:100%;">
                            <?php foreach ($ranks as $r): ?>
                            <option value="<?php echo esc_attr($r); ?>" <?php selected($cap['min_dl_rank'], $r); ?>>
                                <?php echo esc_html(ucfirst($r)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" min="0" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][min_dl_rank_count]"
                            value="<?php echo esc_attr($cap['min_dl_rank_count']); ?>" class="small-text" style="width:50px;">
                    </td>
                    <td>
                        <input type="number" min="0" step="1000000" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][min_total_omset]"
                            value="<?php echo esc_attr($cap['min_total_omset']); ?>" class="regular-text" style="width:120px;">
                    </td>
                    <!-- Alt requirements -->
                    <td>
                        <input type="number" min="0" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][alt_min_dl]"
                            value="<?php echo esc_attr($alt['min_dl']); ?>" class="small-text" style="width:50px;">
                    </td>
                    <td>
                        <select name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][alt_dl_rank]" style="width:100%;">
                            <?php foreach ($ranks as $r): ?>
                            <option value="<?php echo esc_attr($r); ?>" <?php selected($alt['dl_rank'], $r); ?>>
                                <?php echo esc_html(ucfirst($r)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" min="0" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][alt_dl_rank_count]"
                            value="<?php echo esc_attr($alt['dl_rank_count']); ?>" class="small-text" style="width:50px;">
                    </td>
                    <td>
                        <input type="number" min="0" step="1000000" name="dl_qual[<?php echo esc_attr($cap['rank_key']); ?>][alt_min_omset]"
                            value="<?php echo esc_attr($alt['min_omset']); ?>" class="regular-text" style="width:120px;">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="description" style="margin-top:8px;">
            <strong>Logika:</strong> Member kualifikasi jika <em>semua syarat utama</em> pass <strong>ATAU</strong> <em>semua syarat alternatif</em> yang terisi pass.<br>
            Isi 0 pada field yang tidak ingin dijadikan syarat. Kosongkan semua field alternatif untuk menonaktifkan jalur OR.
        </p>

        <p class="submit">
            <button type="submit" class="button button-primary">💾 Save DL Qualification</button>
        </p>
    </form>


    <?php
}

/**
 * Get point_upgrade threshold for a rank.
 */
function crs_rank_threshold($rank) {
    $thresholds = [
        'free'     => 0,
        'starter'  => 250000,
        'basic'    => 1000000,
        'premium'  => 3000000,
        'prestige' => 5000000,
    ];
    return $thresholds[$rank] ?? 0;
}

/**
 * Get threshold for the next rank up.
 */
function crs_next_rank_threshold($rank) {
    $thresholds = [
        'free'     => 250000,
        'starter'  => 1000000,
        'basic'    => 3000000,
        'premium'  => 5000000,
        'prestige' => null,
    ];
    return $thresholds[$rank] ?? null;
}

// ============================================================
// Settings Tab
// ============================================================
function crs_render_settings_tab() {
    if (isset($_POST['crs_action']) && $_POST['crs_action'] === 'save_settings') {
        check_admin_referer('crs_save_settings');

        update_option('crs_max_scan_depth', intval($_POST['max_scan_depth']));
        update_option('crs_memory_limit_mb', intval($_POST['memory_limit_mb']));
        update_option('crs_cron_time', sanitize_text_field($_POST['cron_time']));

        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    $max_depth = get_option('crs_max_scan_depth', 200);
    $memory = get_option('crs_memory_limit_mb', 256);
    $cron_time = get_option('crs_cron_time', '23:00');
    ?>
    <h2>System Settings</h2>

    <form method="post" action="">
        <?php wp_nonce_field('crs_save_settings'); ?>
        <input type="hidden" name="crs_action" value="save_settings">

        <table class="form-table">
            <tr>
                <th><label>Max Scan Depth</label></th>
                <td>
                    <input type="number" min="10" max="500" name="max_scan_depth" value="<?php echo esc_attr($max_depth); ?>" class="small-text">
                    <p class="description">Maximum levels to scan per transaction. Default: 200.</p>
                </td>
            </tr>
            <tr>
                <th><label>Memory Limit (MB)</label></th>
                <td>
                    <input type="number" min="128" max="1024" name="memory_limit_mb" value="<?php echo esc_attr($memory); ?>" class="small-text">
                    <p class="description">Memory limit for cron runs. Default: 256 MB.</p>
                </td>
            </tr>
            <tr>
                <th><label>Cron Time (HH:MM)</label></th>
                <td>
                    <input type="time" name="cron_time" value="<?php echo esc_attr($cron_time); ?>">
                    <p class="description">Time for nightly validation & credit. Current timezone: <?php echo wp_timezone_string(); ?></p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Settings</button>
        </p>
    </form>
    <?php
}
