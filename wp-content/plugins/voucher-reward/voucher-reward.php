<?php
/**
 * Plugin Name: Voucher Reward
 * Description: Plugin untuk membuat tabel hadiah dan user hadiah dengan pengaturan di halaman admin.
 * Version: 1.0
 * Author: Novan
 * Text Domain: voucher-reward
 */

if (!defined('ABSPATH')) {
    exit;
}

class VoucherReward
{
    public function __construct()
    {
        // Hook aktivasi untuk membuat tabel
        register_activation_hook(__FILE__, [$this, 'onActivate']);
        
        // Hook untuk menambah menu admin
        add_action('admin_menu', [$this, 'addAdminMenus']);
      	add_shortcode('user_prize_current', [$this,'tampilkan_hadiah_pengguna_saat_ini']);

      	
    }

    /**
     * Membuat tabel saat plugin diaktifkan.
     */

public function tampilkan_hadiah_pengguna_saat_ini() {
    global $wpdb;
    $user_id = get_current_user_id(); // Ambil ID pengguna yang sedang login

    // Jika pengguna tidak login
    if ($user_id == 0) {
        return '<div style="padding: 20px; background-color: #fff4e6; border: 2px dashed #ff6f00; color: #d35400; border-radius: 10px; text-align: center;">
                    <p style="font-size: 18px; font-weight: bold;">Anda harus login untuk melihat Reward Anda.</p>
                </div>';
    }

    // Tabel yang digunakan
    $user_prize_table = $wpdb->prefix . 'user_prizes';
    $prize_table = $wpdb->prefix . 'prizes';

    // Cek jika tombol klaim hadiah diklik
    if (isset($_POST['claim_prize']) && $_POST['claim_prize'] == '1') {
        $wpdb->update(
            $user_prize_table,
            ['is_claimed' => 1], // Update is_claimed menjadi 1
            ['user_id' => $user_id], // Berdasarkan user_id
            ['%d'], // Format nilai baru
            ['%d']  // Format kondisi
        );
        return '<div style="padding: 20px; background-color: #e6ffed; border: 2px dashed #28a745; color: #2c8f3e; border-radius: 10px; text-align: center;">
                    <p style="font-size: 18px; font-weight: bold;">Reward Anda telah berhasil diklaim!</p>
                </div>';
    }

    // Ambil data hadiah pengguna
    $user_prize = $wpdb->get_row($wpdb->prepare("
        SELECT 
            up.points, 
            up.assigned_at,
            up.is_claimed,
            up.is_approved,
            p.prize_name,
            p.image_url
        FROM {$user_prize_table} up
        LEFT JOIN {$prize_table} p ON up.prize_id = p.id
        WHERE up.user_id = %d
    ", $user_id));

    // Jika tidak ada data hadiah untuk pengguna ini
    if (!$user_prize) {
        return '<div style="padding: 20px; background-color: #fff4e6; border: 2px dashed #ff6f00; color: #d35400; border-radius: 10px; text-align: center;">
                    <p style="font-size: 18px; font-weight: bold;">Anda belum memiliki Reward.</p>
                </div>';
    }

    // Mulai output HTML
    ob_start();
    ?>
    <div style="margin: 20px auto; max-width: 600px; background-color: #fffbf4; border: 2px dashed #ff6f00; border-radius: 10px; display: flex; align-items: center; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Gambar Hadiah -->
        <?php if (!empty($user_prize->image_url)): ?>
            <div style="flex: 1; text-align: center; padding: 10px;">
                <img src="<?php echo esc_url($user_prize->image_url); ?>" alt="Gambar Hadiah" style="max-width: 100%; max-height: 150px; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
            </div>
        <?php endif; ?>
        
        <!-- Informasi Hadiah -->
        <div style="flex: 2; padding: 20px;">
            <h3 style="margin: 0 0 10px; font-size: 20px; color: #d35400; text-transform: uppercase;">Selamat!🎁</h3>
            <h4>Anda berhasil mengumpulkan poin dan mendapatkan:</h4>
            <div style="display:flex; justify-content: space-between;">
                <div>
                    <p style="margin: 5px 0; font-size: 18px; font-weight: bold; color: #ff6f00;">Nama Hadiah:</p>
                    <p style="margin: 5px 0 15px; font-size: 16px; color: #000;"><?php echo !empty($user_prize->prize_name) ? esc_html($user_prize->prize_name) : 'Belum ada hadiah'; ?></p>
                </div>
                <div>
                    <p style="margin: 5px 0; font-size: 18px; font-weight: bold; color: #ff6f00;">Poin Anda:</p>
                    <p style="margin: 5px 0 15px; font-size: 16px; color: #000;"><?php echo esc_html($user_prize->points); ?>/10</p>
                </div>
            </div>
            <p style="margin: 5px 0; font-size: 18px; font-weight: bold; color: #ff6f00;">Poin di update Pada:</p>
            <p style="margin: 5px 0; font-size: 16px; color: #000;"><?php echo esc_html($user_prize->assigned_at); ?></p>
        </div>
    </div>

    <!-- Tombol Klaim Hadiah -->
    <?php if ($user_prize): ?>
        <?php if ($user_prize->is_claimed === "0" && $user_prize->is_approved === "0"): ?>
            <!-- Tampilkan Tombol -->
            <form method="post" style="margin-top: 20px; text-align: center;">
                <?php wp_nonce_field('claim_prize_action', 'claim_prize_nonce'); ?>
                <input type="hidden" name="claim_prize" value="1">
                <button type="submit" style="padding: 10px 20px; background-color: #ff6f00; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    Klaim Reward
                </button>
            </form>
        <?php elseif ($user_prize->is_claimed === "1" && $user_prize->is_approved === "0"): ?>
            <!-- Hadiah Sudah Diklaim -->
            <p style="margin-top: 20px; text-align: center; color: #ffc107; font-weight: bold;">
                Reward sudah diklaim, menunggu Disetujui.
            </p>
        <?php elseif ($user_prize->is_approved === "1"): ?>
            <!-- Hadiah Sudah Diapprove -->
            <p style="margin-top: 20px; text-align: center; color: #28a745; font-weight: bold;">
                Reward anda sudah di Setujui, <br><strong style="color: red;"><i>
              	" Mohon tunggu penyerahan langsung dari shopbiz. Hati2 penipuan mengatasnamakan Shopbiz, kami tidak meminta biaya untuk reward ini dalam bentuk apapun "
              <i><strong>
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p style="margin-top: 20px; text-align: center; color: #dc3545; font-weight: bold;">
            Data hadiah tidak tersedia.
        </p>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}
// Register shortcode

    public function onActivate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Tabel untuk menyimpan data hadiah
        $prize_table = $wpdb->prefix . 'prizes';
        $create_prize_table = "CREATE TABLE $prize_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            prize_name VARCHAR(255) NOT NULL,
            quota INT NOT NULL,
            remaining_quota INT NOT NULL,
            priority INT NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";

        // Tabel untuk menyimpan data user hadiah
        $user_prize_table = $wpdb->prefix . 'user_prizes';
        $create_user_prize_table = "CREATE TABLE $user_prize_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            username VARCHAR(255) NOT NULL,
            display_name VARCHAR(255) NOT NULL,
            is_approved BOOLEAN DEFAULT FALSE,
            is_claimed BOOLEAN DEFAULT FALSE,
            points INT NOT NULL,
            prize_id INT NULL,
            assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (prize_id) REFERENCES $prize_table(id) ON DELETE SET NULL
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($create_prize_table);
        dbDelta($create_user_prize_table);
    }

    /**
     * Menambah menu admin untuk mengelola hadiah dan user hadiah.
     */
    public function addAdminMenus()
    {
        add_menu_page(
            'Voucher Reward',
            'Voucher Reward',
            'manage_options',
            'voucher-reward',
            [$this, 'renderPrizesPage'],
            'dashicons-tickets-alt',
            56
        );

        add_submenu_page(
            'voucher-reward',
            'Manage Prizes',
            'Manage Prizes',
            'manage_options',
            'voucher-reward',
            [$this, 'renderPrizesPage']
        );

        add_submenu_page(
            'voucher-reward',
            'User Prizes',
            'User Prizes',
            'manage_options',
            'user-prizes',
            [$this, 'renderUserPrizesPage']
        );
    }

    /**
     * Render halaman untuk mengelola hadiah.
     */
    public function renderPrizesPage()
    {
        include plugin_dir_path(__FILE__) . 'admin/prizes-page.php';
    }

    /**
     * Render halaman untuk melihat user hadiah.
     */
    public function renderUserPrizesPage()
    {
        include plugin_dir_path(__FILE__) . 'admin/user-prizes-page.php';
    }
}

new VoucherReward();