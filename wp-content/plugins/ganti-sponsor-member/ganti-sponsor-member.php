<?php
/**
 * Plugin Name: Ganti Sponsor Member
 * Description: Plugin untuk mengganti sponsor member dan update uplines.
 * Version: 1.0
 * Author: Ruli
 */

add_action('admin_menu', 'gsm_add_admin_page');

function gsm_add_admin_page() {
    add_menu_page(
        'Ganti Sponsor',
        'Ganti Sponsor',
        'manage_options',
        'ganti-sponsor',
        'gsm_admin_page',
        'dashicons-randomize',
        100
    );
}

function gsm_admin_page() {
    global $wpdb;

    if (isset($_POST['id_member']) && isset($_POST['id_sponsor'])) {
       
        $id_member = intval($_POST['id_member']);
        $id_sponsor = intval($_POST['id_sponsor']);

        echo "<div class='notice notice-info'><p>Memproses perubahan sponsor...</p></div>";
        gsm_ganti_sponsor($wpdb, $id_member, $id_sponsor);
        echo "<div class='notice notice-success'><p>Berhasil update sponsor dan uplines.</p></div>";
    }

    ?>
    <div class="wrap">
        <h1>Ganti Sponsor Member</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="id_member">Pilih Member</label></th>
                    <td>
                        <select name="id_member" required class="regular-text">
                            <option value="">-- Pilih Member --</option>
                            <?php
                            $members = $wpdb->get_results("SELECT idwp, username FROM wp_member ORDER BY username ASC");
                            foreach ($members as $member) {
                                echo "<option value='{$member->idwp}'>{$member->username} (ID: {$member->idwp})</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="id_sponsor">Pilih Sponsor Baru</label></th>
                    <td>
                        <select name="id_sponsor" required class="regular-text">
                            <option value="">-- Pilih Sponsor --</option>
                            <?php
                            foreach ($members as $member) {
                                echo "<option value='{$member->idwp}'>{$member->username} (ID: {$member->idwp})</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button('Ganti Sponsor'); ?>
        </form>
    </div>
    <?php
}

function gsm_ganti_sponsor($wpdb, $id_member, $id_sponsor_baru) {
    // Ambil uplines sponsor baru
    $sponsor_homepage = $wpdb->get_var($wpdb->prepare(
        "SELECT homepage FROM wp_member WHERE idwp = %d", $id_sponsor_baru
    ));
   
    if (!$sponsor_homepage) return;

    $sponsor_data = unserialize($sponsor_homepage);
    $uplines_sponsor = isset($sponsor_data['uplines']) ? explode(',', $sponsor_data['uplines']) : [];
    $uplines_baru = array_merge([$id_sponsor_baru], $uplines_sponsor);

    // Update member utama
    $member_homepage = $wpdb->get_var($wpdb->prepare(
        "SELECT homepage FROM wp_member WHERE idwp = %d", $id_member
    ));
    if (!$member_homepage) return;

    $member_data = unserialize($member_homepage);
    $uplines_for_replace =  $member_data['uplines'];
    $member_data['uplines'] = implode(',', $uplines_baru);
    $wpdb->update(
        'wp_member',
        [
            'homepage'   => serialize($member_data),
            'id_referral' => $id_sponsor_baru
        ],
        ['idwp' => $id_member]
    );

    // Update semua downline yang memiliki ID member ini dalam uplines
    $offset = 0;
    $limit = 100; // Process in chunks of 100 rows
    do {
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT idwp, homepage FROM wp_member WHERE homepage LIKE %s LIMIT %d OFFSET %d",
            '%' . $id_member . ',%',
            $limit,
            $offset
        ));
       
        foreach ($results as $row) {
            $data = unserialize($row->homepage);
            if (!isset($data['uplines'])) continue;

            $uplines = explode(',', $data['uplines']);
            $pos = array_search($id_member, $uplines);
            if ($pos !== false) {
               
                // Ganti path uplines dari titik member yang diganti ke atas
                $new_path = str_replace($uplines_for_replace , implode(',', $uplines_baru) , $data['uplines']);
                $data['uplines'] = $new_path;
                $wpdb->update(
                    'wp_member',
                    ['homepage' => serialize($data)],
                    ['idwp' => $row->idwp]
                );
            }
        }

        $offset += $limit;
    } while (count($results) > 0);
}
