<?php
/**
 * Member Area Dashboard Integration for Commission Royalty System
 */

defined('ABSPATH') || exit;

/**
 * Get detailed royalty qualification status for a member.
 * Returns progress for ALL ranks defined in the system.
 */
function crs_get_member_royalty_progress($user_id) {
    global $wpdb;
    $table_caps = $wpdb->prefix . 'crs_rank_caps';
    $table_member = $wpdb->prefix . 'member';

    // Get current member data
    $member = crs_get_member($user_id);
    if (!$member) return [];

    // Get all rank requirements
    $ranks = $wpdb->get_results("SELECT * FROM $table_caps ORDER BY max_tier ASC", ARRAY_A);
    
    $current_points = crs_get_point_upgrade($user_id);
    $current_rank = crs_points_to_rank($current_points);
    $current_omset = (float)($member['total_omset'] ?? 0);
    $current_direct_dl = (int)($member['downline_lngsg'] ?? 0);

    $results = [];
    foreach ($ranks as $rank) {
        $rank_key = $rank['rank_key'];
        if ($rank_key === 'free') continue; // Skip free

        $progress = [
            'rank_label' => $rank['rank_label'],
            'rank_key'   => $rank_key,
            'max_tier'   => (int)$rank['max_tier'],
            'is_current' => ($current_rank === $rank_key),
            'criteria'   => []
        ];

        // 1. Point Upgrade (Rank requirement)
        // We need to know the point thresholds. Since they are hardcoded in rank.php, 
        // we'll use a helper to get them.
        $target_points = crs_get_rank_point_threshold($rank_key);
        $progress['criteria']['points'] = [
            'label'    => 'Poin Upgrade',
            'current'  => $current_points,
            'target'   => $target_points,
            'percent'  => $target_points > 0 ? min(100, ($current_points / $target_points) * 100) : 100,
            'achieved' => ($current_points >= $target_points)
        ];

        // 2. Direct Downlines
        $target_dl = (int)$rank['min_direct_dl'];
        $progress['criteria']['direct_dl'] = [
            'label'    => 'Downline Langsung',
            'current'  => $current_direct_dl,
            'target'   => $target_dl,
            'percent'  => $target_dl > 0 ? min(100, ($current_direct_dl / $target_dl) * 100) : 100,
            'achieved' => ($current_direct_dl >= $target_dl)
        ];

        // 3. Total Omset
        $target_omset = (float)$rank['min_total_omset'];
        $progress['criteria']['omset'] = [
            'label'    => 'Total Omset Grup',
            'current'  => $current_omset,
            'target'   => $target_omset,
            'percent'  => $target_omset > 0 ? min(100, ($current_omset / $target_omset) * 100) : 100,
            'achieved' => ($current_omset >= $target_omset)
        ];

        // 4. Downline Rank Requirement
        $target_dl_rank = $rank['min_dl_rank'];
        $target_dl_rank_count = (int)$rank['min_dl_rank_count'];
        if ($target_dl_rank_count > 0 && $target_dl_rank !== 'free') {
            $count_qualified = crs_count_direct_downlines_with_min_rank($user_id, $target_dl_rank);
            $progress['criteria']['dl_rank'] = [
                'label'    => "Downline Rank Min. " . ucfirst($target_dl_rank),
                'current'  => $count_qualified,
                'target'   => $target_dl_rank_count,
                'percent'  => min(100, ($count_qualified / $target_dl_rank_count) * 100),
                'achieved' => ($count_qualified >= $target_dl_rank_count)
            ];
        }

        // Overall Eligibility for this rank's royalty
        $eligibility = crs_check_downline_qualifies_for_rank($user_id, $rank_key);
        $progress['eligible'] = $eligibility['qualified'];
        
        $results[] = $progress;
    }

    return $results;
}


/**
 * Render the HTML for the royalty indicator.
 */
function crs_render_member_royalty_indicator($user_id) {
    $progress_data = crs_get_member_royalty_progress($user_id);
    if (empty($progress_data)) return '';

    ob_start();
    ?>
    <div class="crs-royalty-dashboard">
        <h3 style="margin-top: 0; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 20px;">🏆</span> Kualifikasi Komisi Royalti
        </h3>
        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">
            Berikut adalah target yang perlu Anda capai untuk membuka potensi komisi royalti di setiap tingkatan.
        </p>

        <div class="crs-tiers-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
            <?php foreach ($progress_data as $p): ?>
                <div class="crs-tier-card" style="background: #f8fafc; border: 1px solid <?php echo $p['eligible'] ? '#cbd5e1' : '#e2e8f0'; ?>; border-radius: 10px; padding: 15px; position: relative; transition: all 0.2s;">
                    <?php if ($p['eligible']): ?>
                        <div style="position: absolute; top: -10px; right: 10px; background: #22c55e; color: white; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">QUALIFIED</div>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0; color: #1e293b; font-size: 16px;"><?php echo esc_html($p['rank_label']); ?></h4>
                        <span style="font-size: 12px; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">Max Tier <?php echo $p['max_tier']; ?></span>
                    </div>

                    <div class="crs-criteria-list">
                        <?php foreach ($p['criteria'] as $key => $c): ?>
                            <div style="margin-bottom: 12px;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                    <span style="color: #475569;"><?php echo esc_html($c['label']); ?></span>
                                    <span style="font-weight: 600; color: <?php echo $c['achieved'] ? '#16a34a' : '#ef4444'; ?>;">
                                        <?php 
                                        if ($key === 'omset' || $key === 'points') {
                                            echo number_format($c['current'], 0, ',', '.') . ' / ' . number_format($c['target'], 0, ',', '.');
                                        } else {
                                            echo $c['current'] . ' / ' . $c['target'];
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div style="height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                                    <div style="width: <?php echo $c['percent']; ?>%; height: 100%; background: <?php echo $c['achieved'] ? '#22c55e' : '#3b82f6'; ?>; border-radius: 3px; transition: width 0.5s ease-out;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .crs-royalty-dashboard {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #edf2f7;
        }
        .crs-tier-card:hover {
            border-color: #3b82f6 !important;
            transform: translateY(-2px);
        }
    </style>
    <?php
    return ob_get_clean();
}
