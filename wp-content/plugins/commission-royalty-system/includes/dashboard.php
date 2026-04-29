<?php
/**
 * Live Dashboard: Overview, transaction detail, history log.
 */

defined('ABSPATH') || exit;

function crs_render_live_dashboard() {
    global $wpdb;
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $table_member = $wpdb->prefix . 'member';

    // Today's date
    $today = date('Y-m-d');

    // ── Section 1: Live Overview ──
    $today_total = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(commission_amount), 0) FROM $table_assign WHERE DATE(created_at) = %s AND status = 'credited'",
        $today
    ));

    $today_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_assign WHERE DATE(created_at) = %s AND status = 'credited'",
        $today
    ));

    $pending_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_assign WHERE status = 'pending'");

    $total_vouchers = $wpdb->get_var("SELECT COALESCE(SUM(jml_voucher), 0) FROM $table_member");
    $used_vouchers = $wpdb->get_var("SELECT COALESCE(SUM(sisa_voucher), 0) FROM $table_member");

    // Top 10 receivers today
    $top_receivers = $wpdb->get_results($wpdb->prepare(
        "SELECT m.username, m.nama, a.recipient_id,
                SUM(a.commission_amount) as total_amount,
                COUNT(a.id) as txn_count
         FROM $table_assign a
         LEFT JOIN $table_member m ON a.recipient_id = m.idwp
         WHERE DATE(a.created_at) = %s AND a.status = 'credited'
         GROUP BY a.recipient_id
         ORDER BY total_amount DESC
         LIMIT 10",
        $today
    ), ARRAY_A);

    ?>
    <h2>Live Overview — <?php echo esc_html(date('d F Y')); ?></h2>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; border-left: 4px solid #2271b1; padding: 20px; border-radius: 4px;">
            <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">Total Royalty Today</h3>
            <p style="margin: 0; font-size: 28px; font-weight: bold; color: #2271b1;">Rp <?php echo number_format($today_total, 0, ',', '.'); ?></p>
        </div>
        <div style="background: #fff; border-left: 4px solid #00a32a; padding: 20px; border-radius: 4px;">
            <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">Credited Transactions</h3>
            <p style="margin: 0; font-size: 28px; font-weight: bold; color: #00a32a;"><?php echo $today_count; ?></p>
        </div>
        <div style="background: #fff; border-left: 4px solid #dba617; padding: 20px; border-radius: 4px;">
            <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">Pending Royalty</h3>
            <p style="margin: 0; font-size: 28px; font-weight: bold; color: #dba617;"><?php echo $pending_count; ?></p>
        </div>
        <div style="background: #fff; border-left: 4px solid #d63638; padding: 20px; border-radius: 4px;">
            <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">Total Voucher Available</h3>
            <p style="margin: 0; font-size: 28px; font-weight: bold; color: #d63638;"><?php echo number_format($total_vouchers, 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Top 10 Receivers -->
    <?php if (!empty($top_receivers)): ?>
    <h3>Top 10 Receivers Today</h3>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Name</th>
                <th>Total Amount</th>
                <th>Transactions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($top_receivers as $i => $r): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo esc_html($r['username']); ?></td>
                <td><?php echo esc_html($r['nama']); ?></td>
                <td>Rp <?php echo number_format($r['total_amount'], 0, ',', '.'); ?></td>
                <td><?php echo $r['txn_count']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <hr style="margin: 30px 0;">

    <!-- ── Section 2: Recent Transactions ── -->
    <h2>Recent Transactions</h2>

    <?php
    $recent = $wpdb->get_results(
        "SELECT a.*, m_buyer.username as buyer_username, m_buyer.nama as buyer_name,
                m_rec.username as recipient_username, m_rec.nama as recipient_name
         FROM $table_assign a
         LEFT JOIN $table_member m_buyer ON a.buyer_id = m_buyer.idwp
         LEFT JOIN $table_member m_rec ON a.recipient_id = m_rec.idwp
         ORDER BY a.created_at DESC
         LIMIT 50",
        ARRAY_A
    );
    ?>

    <?php if (!empty($recent)): ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Order</th>
                <th>Buyer</th>
                <th>Recipient</th>
                <th>Tier</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent as $r): ?>
            <tr>
                <td>#<?php echo esc_html($r['order_id']); ?></td>
                <td><?php echo esc_html($r['buyer_username'] ?: 'N/A'); ?></td>
                <td><?php echo esc_html($r['recipient_username'] ?: 'N/A'); ?></td>
                <td>Tier <?php echo esc_html($r['tier_number']); ?></td>
                <td><?php echo esc_html($r['rate_percent']); ?>%</td>
                <td>Rp <?php echo number_format($r['commission_amount'], 0, ',', '.'); ?></td>
                <td>
                    <?php
                    $status_class = 'pending' === $r['status'] ? 'color: #dba617;' : ('credited' === $r['status'] ? 'color: #00a32a;' : 'color: #d63638;');
                    ?>
                    <span style="<?php echo $status_class; ?> font-weight: bold;">
                        <?php echo esc_html(ucfirst($r['status'])); ?>
                    </span>
                </td>
                <td><?php echo esc_html($r['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No transactions yet.</p>
    <?php endif; ?>

    <hr style="margin: 30px 0;">

    <!-- ── Section 3: Export ── -->
    <h2>Export History</h2>
    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=commission-royalty&tab=dashboard&action=export_csv')); ?>" class="button button-primary">
            📥 Export CSV
        </a>
    </p>

    <?php
    // Handle CSV export
    if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
        crs_export_csv();
    }
}

function crs_export_csv() {
    global $wpdb;
    $table_assign = $wpdb->prefix . 'crs_royalty_assignments';
    $table_member = $wpdb->prefix . 'member';

    $rows = $wpdb->get_results(
        "SELECT a.created_at as 'Tanggal',
                m_rec.username as 'Username',
                m_rec.nama as 'Nama',
                a.tier_number as 'Tier',
                a.rate_percent as 'Rate',
                a.commission_amount as 'Komisi',
                a.order_id as 'Order ID',
                a.status as 'Status'
         FROM $table_assign a
         LEFT JOIN $table_member m_rec ON a.recipient_id = m_rec.idwp
         ORDER BY a.created_at DESC",
        ARRAY_A
    );

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=royalty-history-' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Tanggal', 'Username', 'Nama', 'Tier', 'Rate (%)', 'Komisi', 'Order ID', 'Status']);

    foreach ($rows as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
