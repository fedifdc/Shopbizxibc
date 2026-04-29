<?php
/**
 * Rank management helpers.
 *
 * Rank is determined SOLELY by point_upgrade (wp_usermeta) balance:
 *   >= 5,000,000 → Prestige
 *   >= 3,000,000 → Premium
 *   >= 1,000,000 → Basic
 *   >=   250,000 → Starter
 *   <  250,000   → Free
 *
 * No separate upgrade validation — rank is always live based on balance.
 */

defined('ABSPATH') || exit;
