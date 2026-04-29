<?php
/**
 * Core: Royalty Calculation Algorithm
 * Linear scan naik ke atas dari buyer, assign komisi per tier.
 *
 * Rank logic matches instant-sponsor-commission:
 *   Rank ditentukan dari point_upgrade (wp_usermeta), bukan membership field.
 *
 * Upline tracking matches wp-affiliasi's cbaff_uplines():
 *   id_referral in wp_member IS the sponsor's idwp directly (no conversion).
 *
 *   >= 5,000,000 → Prestige
 *   >= 3,000,000 → Premium
 *   >= 1,000,000 → Basic
 *   >=   250,000 → Starter
 *   <  250,000   → Free
 */

defined('ABSPATH') || exit;

/**
 * Calculate royalty for a buyer.
 *
 * @param int $buyer_id WordPress user ID of the buyer
 * @return array [tier_number => ['user_id' => int, 'rate_percent' => float]]
 */
/**
 * Accumulate total_omset (sum point_level all downlines) for every member.
 * Runs BEFORE royalty calculation in cron.
 *
 * Uses recursive CTE to walk downline tree from each member.
 */
function crs_accumulate_all_omset() {
    global $wpdb;
    $member_table = $wpdb->prefix . 'member';
    $usermeta = $wpdb->prefix . 'usermeta';

    crs_log("[omset] Starting total_omset accumulation...");

    // Use recursive CTE to calculate total_omset for ALL members in one query
    // Then UPDATE wp_member.total_omset per member
    $sql = "
        UPDATE $member_table m
        LEFT JOIN (
            SELECT 
                root.id_referral AS sponsor_idwp,
                COALESCE(SUM(um.meta_value), 0) AS total_omset
            FROM (
                SELECT idwp, id_referral FROM $member_table
            ) root
            INNER JOIN (
                WITH RECURSIVE downlines AS (
                    SELECT idwp, id_referral FROM $member_table
                    UNION ALL
                    SELECT m2.idwp, m2.id_referral
                    FROM $member_table m2
                    INNER JOIN downlines d ON m2.id_referral = d.idwp
                )
                SELECT idwp, id_referral FROM downlines
            ) dl ON dl.id_referral = root.idwp
            LEFT JOIN $usermeta um ON um.user_id = dl.idwp AND um.meta_key = 'point_level'
            GROUP BY root.id_referral
        ) calc ON m.idwp = calc.sponsor_idwp
        SET m.total_omset = COALESCE(calc.total_omset, 0)
    ";

    $result = $wpdb->query($sql);

    if ($result === false) {
        crs_log("[omset] ERROR: Failed to accumulate omset");
        return false;
    }

    // Count members with updated omset
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $member_table WHERE total_omset > 0");
    crs_log("[omset] Done. $count members have total_omset > 0");

    return true;
}

/**
 * Check if a member qualifies for royalty based on downline requirements.
 *
 * Checks:
 * 1. min_direct_dl — minimum direct downline count
 * 2. min_total_omset — minimum total omset from all downlines (pre-computed)
 * 3. min_dl_rank — minimum downline rank requirement (count of direct downlines with rank >= X)
 *
 * @param int $idwp The member's idwp
 * @return array ['qualified' => bool, 'reasons' => string[]]
 */
function crs_check_downline_qualifies($idwp) {
    global $wpdb;
    $member_table = $wpdb->prefix . 'member';
    $usermeta = $wpdb->prefix . 'usermeta';
    $rank_caps_table = $wpdb->prefix . 'crs_rank_caps';

    $member = crs_get_member($idwp);
    if (!$member) {
        return ['qualified' => false, 'reasons' => ['Member not found']];
    }

    $rank = crs_get_rank($member);
    if ($rank === 'free') {
        return ['qualified' => true, 'reasons' => []]; // Free has no requirements
    }

    // Get requirements for this rank
    $req = $wpdb->get_row($wpdb->prepare(
        "SELECT min_direct_dl, min_total_omset, min_dl_rank, min_dl_rank_count, alt_requirements FROM $rank_caps_table WHERE rank_key = %s LIMIT 1",
        sanitize_key($rank)
    ), ARRAY_A);

    if (!$req) {
        return ['qualified' => false, 'reasons' => ['No requirements found for rank: ' . $rank]];
    }

    $reasons = [];
    $qualified = true;

    // Check 1: Direct downline count
    $direct_dl = (int)$member['downline_lngsg'] ?? 0;
    $min_dl = (int)$req['min_direct_dl'];
    if ($min_dl > 0 && $direct_dl < $min_dl) {
        $qualified = false;
        $reasons[] = "Direct DL: $direct_dl / $min_dl required";
    }

    // Check 2: Total omset (pre-computed)
    $total_omset = (float)$member['total_omset'] ?? 0;
    $min_omset = (float)$req['min_total_omset'];
    if ($min_omset > 0 && $total_omset < $min_omset) {
        $qualified = false;
        $reasons[] = sprintf("Total omset: %.0f / %.0f required", $total_omset, $min_omset);
    }

    // Check 3: Min downline rank (count direct downlines with rank >= threshold)
    $min_dl_rank = $req['min_dl_rank'];
    $min_dl_rank_count = (int)$req['min_dl_rank_count'];
    if ($min_dl_rank_count > 0 && $min_dl_rank !== 'free') {
        $count_qualified_dl = crs_count_direct_downlines_with_min_rank($idwp, $min_dl_rank);

        if ($count_qualified_dl < $min_dl_rank_count) {
            $qualified = false;
            $reasons[] = "DL rank ($min_dl_rank): $count_qualified_dl / $min_dl_rank_count required";
        }
    }

    if ($qualified) {
        return ['qualified' => true, 'reasons' => []];
    }

    // If default path failed, try alternative path (OR logic)
    if (!empty($req['alt_requirements']) && is_string($req['alt_requirements'])) {
        $alt = json_decode($req['alt_requirements'], true);
        if ($alt && crs_check_alt_path($idwp, $member, $alt)) {
            return ['qualified' => true, 'reasons' => ['Qualified via alt path']];
        }
    }

    return ['qualified' => false, 'reasons' => $reasons];
}

/**
 * Count direct downlines with rank >= the given minimum rank.
 */
function crs_count_direct_downlines_with_min_rank($idwp, $min_rank) {
    global $wpdb;
    $member_table = $wpdb->prefix . 'member';
    $rank_order = ['free' => 0, 'starter' => 1, 'basic' => 2, 'premium' => 3, 'prestige' => 4];
    $min_rank_val = isset($rank_order[$min_rank]) ? $rank_order[$min_rank] : 0;

    $direct_downlines = $wpdb->get_results($wpdb->prepare(
        "SELECT idwp FROM $member_table WHERE id_referral = %d",
        $idwp
    ), ARRAY_A);

    $count = 0;
    foreach ($direct_downlines as $dl) {
        $dl_rank = crs_get_rank($dl);
        $dl_rank_val = isset($rank_order[$dl_rank]) ? $rank_order[$dl_rank] : 0;
        if ($dl_rank_val >= $min_rank_val) {
            $count++;
        }
    }

    return $count;
}

/**
 * Check if a member qualifies via an alternative qualification path.
 *
 * @param int $idwp Member's idwp
 * @param array $member wp_member record
 * @param array $alt Alternative requirements: min_dl, min_omset, dl_rank, dl_rank_count
 * @return bool
 */
function crs_check_alt_path($idwp, $member, $alt) {
    $rank_order = ['free' => 0, 'starter' => 1, 'basic' => 2, 'premium' => 3, 'prestige' => 4];

    // Check direct downline count
    $direct_dl = (int)($member['downline_lngsg'] ?? 0);
    $min_dl = isset($alt['min_dl']) ? (int)$alt['min_dl'] : 0;
    if ($min_dl > 0 && $direct_dl < $min_dl) {
        return false;
    }

    // Check total omset
    $total_omset = (float)($member['total_omset'] ?? 0);
    $min_omset = isset($alt['min_omset']) ? (float)$alt['min_omset'] : 0;
    if ($min_omset > 0 && $total_omset < $min_omset) {
        return false;
    }

    // Check downline rank count
    $dl_rank = isset($alt['dl_rank']) ? $alt['dl_rank'] : 'free';
    $dl_rank_count = isset($alt['dl_rank_count']) ? (int)$alt['dl_rank_count'] : 0;
    if ($dl_rank_count > 0 && $dl_rank !== 'free') {
        $count = crs_count_direct_downlines_with_min_rank($idwp, $dl_rank);
        if ($count < $dl_rank_count) {
            return false;
        }
    }

    return true;
}

/**
 * Calculate royalty for a buyer.
 *
 * @param int $buyer_id WordPress user ID of the buyer
 * @return array [tier_number => ['user_id' => int, 'rate_percent' => float]]
 */
function crs_calculate_royalty($buyer_id) {
    $tiers = crs_get_tiers();
    if (empty($tiers)) {
        return [];
    }

    // Find the wp_member record for this buyer
    $buyer_member = crs_get_member($buyer_id);
    if (!$buyer_member) {
        crs_log("Buyer $buyer_id: No wp_member record found.");
        return [];
    }

    $position = $buyer_id;
    $assigned = [];
    $max_depth = get_option('crs_max_scan_depth', 200);
    $depth = 0;

    foreach ($tiers as $tier_number => $rate) {
        $found = false;

        while (!$found && $position !== null && $depth < $max_depth) {
            $depth++;
            $upline_id = crs_get_upline_id($position);

            if ($upline_id === null || $upline_id === 0) {
                break; // Root reached
            }

            $upline_member = crs_get_member($upline_id);
            $position_member = crs_get_member($position);

            // Check if upline is Free rank — floor, cannot receive royalty
            $upline_rank = crs_get_rank($upline_member);
            if ($upline_rank === 'free') {
                $position = $upline_id;
                continue;
            }

            // Compare ranks: upline must have rank >= direct child below
            $below_rank = crs_get_rank($position_member);
            if (!crs_rank_qualifies($upline_rank, $below_rank)) {
                $position = $upline_id;
                continue;
            }

            // Check Rank Cap
            $max_tier = crs_get_max_tier_for_rank($upline_rank);
            if ($tier_number > $max_tier) {
                $position = $upline_id;
                continue;
            }

            // Check Downline Qualifications (min_dl, total_omset, min_dl_rank)
            $dl_check = crs_check_downline_qualifies($upline_id);
            if (!$dl_check['qualified']) {
                crs_log("Upline $upline_id ($upline_rank): DL qualification failed — " . implode('; ', $dl_check['reasons']));
                $position = $upline_id;
                continue;
            }

            // ✅ QUALIFIED
            $assigned[$tier_number] = [
                'user_id'      => $upline_id,
                'rate_percent' => $rate,
            ];
            $position = $upline_id;
            $found = true;
        }
    }

    return $assigned;
}

/**
 * Get upline/sponsor ID for a WordPress user.
 *
 * Matches wp-affiliasi's cbaff_uplines() logic:
 *   id_referral in wp_member IS the sponsor's idwp (WordPress user ID) directly.
 *   No conversion needed — just follow id_referral chain.
 *
 * @param int $user_id WordPress user ID
 * @return int|null upline WordPress user ID, or null if none
 */
function crs_get_upline_id($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'member';

    // id_referral IS the sponsor's idwp — no conversion needed
    $sponsor_idwp = $wpdb->get_var($wpdb->prepare(
        "SELECT id_referral FROM $table WHERE idwp = %d LIMIT 1",
        $user_id
    ));

    if ($sponsor_idwp === null || $sponsor_idwp == 0) {
        return null;
    }

    return (int)$sponsor_idwp;
}

/**
 * Get wp_member record for a WordPress user ID.
 */
function crs_get_member($user_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'member';

    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE idwp = %d LIMIT 1",
        $user_id
    ), ARRAY_A);

    return $row ?: null;
}

/**
 * Determine rank of a user based on point_upgrade MyCRED balance.
 *
 * Matching logic from instant-sponsor-commission/functions/functions.php:
 *   Rank = get_sponsor_level(point_upgrade)
 */
function crs_get_rank($member) {
    if (!$member) {
        return 'free';
    }

    $user_id = $member['idwp'];
    $points = crs_get_point_upgrade($user_id);

    return crs_points_to_rank($points);
}

/**
 * Convert point_upgrade value to rank string.
 * Matches instant-sponsor-commission's get_sponsor_level().
 */
function crs_points_to_rank($points) {
    $points = intval($points);

    if ($points >= 5000000) {
        return 'prestige';
    } elseif ($points >= 3000000) {
        return 'premium';
    } elseif ($points >= 1000000) {
        return 'basic';
    } elseif ($points >= 250000) {
        return 'starter';
    }

    return 'free';
}

/**
 * Get MyCRED point_upgrade balance for a user.
 * This is the value that determines rank.
 * Taken from wp_usermeta via get_user_meta().
 */
function crs_get_point_upgrade($user_id) {
    return intval(get_user_meta($user_id, 'point_upgrade', true));
}

/**
 * Get MyCRED point_level balance for a user.
 */
function crs_get_mycred_point_level($user_id) {
    if (!function_exists('mycred')) {
        return 0;
    }

    try {
        $mycred = mycred('point_level');
        return $mycred->get_users_cred($user_id);
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Check if upline rank qualifies over the below rank.
 * Upline rank must be >= below rank in the hierarchy.
 * (Spec: strict >, but with note that equal also qualifies.)
 */
function crs_rank_qualifies($upline_rank, $below_rank) {
    $rank_order = [
        'free'     => 0,
        'starter'  => 1,
        'basic'    => 2,
        'premium'  => 3,
        'prestige' => 4,
    ];

    $upline_val = isset($rank_order[$upline_rank]) ? $rank_order[$upline_rank] : 0;
    $below_val = isset($rank_order[$below_rank]) ? $rank_order[$below_rank] : 0;

    return $upline_val >= $below_val;
}

/**
 * Get max tier for a given rank (from CRS rank caps table).
 */
function crs_get_max_tier_for_rank($rank) {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_rank_caps';

    $max_tier = $wpdb->get_var($wpdb->prepare(
        "SELECT max_tier FROM $table WHERE rank_key = %s LIMIT 1",
        sanitize_key($rank)
    ));

    return $max_tier !== null ? (int)$max_tier : 0;
}

/**
 * Get configured tiers (ordered by tier_number).
 * Returns: [tier_number => rate_percent]
 */
function crs_get_tiers() {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_tiers';

    $rows = $wpdb->get_results("SELECT tier_number, rate_percent FROM $table ORDER BY tier_number ASC", ARRAY_A);

    $tiers = [];
    foreach ($rows as $row) {
        $tiers[$row['tier_number']] = (float)$row['rate_percent'];
    }

    return $tiers;
}

/**
 * Get requirements for a rank (from crs_rank_caps).
 */
function crs_get_rank_requirements($rank) {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_rank_caps';

    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT min_direct_dl, min_total_omset, min_dl_rank, min_dl_rank_count, alt_requirements FROM $table WHERE rank_key = %s LIMIT 1",
        sanitize_key($rank)
    ), ARRAY_A);

    return $row ?: null;
}

// ============================================================
// COMMISSION CALCULATION (order-based wrapper)
// ============================================================

/**
 * Calculate commission for a WooCommerce order.
 *
 * Wrapper around crs_calculate_royalty() that:
 * 1. Loads the WC order
 * 2. Extracts buyer_id
 * 3. Runs linear upward scan
 * 4. Returns structured commission array
 *
 * @param int $order_id WooCommerce order ID
 * @return array [{tier, user_id, username, amount, rate_percent}] or empty array
 */
function crs_calculate_commission($order_id) {
    global $wpdb;

    // Load WooCommerce order
    if (!function_exists('wc_get_order')) {
        crs_log("crs_calculate_commission: WooCommerce not loaded.");
        return [];
    }

    $order = wc_get_order($order_id);
    if (!$order) {
        crs_log("crs_calculate_commission: Order $order_id not found.");
        return [];
    }

    // Get buyer WP user ID
    $buyer_user_id = $order->get_user_id();
    if (!$buyer_user_id) {
        crs_log("crs_calculate_commission: Order $order_id is a guest order.");
        return [];
    }

    // Run the core linear upward scan algorithm
    $assignments = crs_calculate_royalty($buyer_user_id);
    if (empty($assignments)) {
        crs_log("crs_calculate_commission: No qualified uplines for buyer $buyer_user_id (order $order_id).");
        return [];
    }

    // Determine base amount (point_level earned from order)
    $base_amount = crs_get_order_point_level($order);
    if ($base_amount <= 0) {
        crs_log("crs_calculate_commission: No point_level earned for order $order_id.");
        return [];
    }

    // Build structured result
    $member_table = $wpdb->prefix . 'member';
    $commissions = [];

    foreach ($assignments as $tier_number => $data) {
        $recipient_id = (int)$data['user_id'];
        $rate = (float)$data['rate_percent'];
        $amount = round($base_amount * ($rate / 100), 2);

        // Get username from wp_member
        $username = $wpdb->get_var($wpdb->prepare(
            "SELECT username FROM $member_table WHERE idwp = %d LIMIT 1",
            $recipient_id
        ));
        if (!$username) {
            // Fallback to WP user login
            $wp_user = get_user_by('id', $recipient_id);
            $username = $wp_user ? $wp_user->user_login : "user_$recipient_id";
        }

        $commissions[] = [
            'tier'           => (int)$tier_number,
            'user_id'        => $recipient_id,
            'username'       => $username,
            'amount'         => $amount,
            'rate_percent'   => $rate,
            'base_amount'    => $base_amount,
            'order_id'       => $order_id,
            'buyer_id'       => $buyer_user_id,
        ];
    }

    crs_log("crs_calculate_commission: Order $order_id — " . count($commissions) . " commissions from base=$base_amount");

    return $commissions;
}

/**
 * Save commission results to wp_crs_transactions.
 *
 * @param array $commissions Result from crs_calculate_commission()
 * @param string $status Initial status ('pending', 'credited', 'failed')
 * @return int Number of rows inserted
 */
function crs_save_commissions($commissions, $status = 'pending') {
    global $wpdb;
    $table = $wpdb->prefix . 'crs_transactions';

    if (empty($commissions)) {
        return 0;
    }

    $count = 0;
    foreach ($commissions as $c) {
        $result = $wpdb->insert($table, [
            'order_id'          => (int)$c['order_id'],
            'buyer_id'          => (int)$c['buyer_id'],
            'recipient_id'      => (int)$c['user_id'],
            'tier_number'       => (int)$c['tier'],
            'rate_percent'      => $c['rate_percent'],
            'commission_amount' => $c['amount'],
            'base_amount'       => $c['base_amount'],
            'status'            => sanitize_key($status),
        ]);

        if ($result !== false) {
            $count++;
        }
    }

    crs_log("crs_save_commissions: Inserted $count transactions.");
    return $count;
}
