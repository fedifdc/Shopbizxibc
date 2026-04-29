<?php
    function get_list_all_user_sync() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'shopbiz_user_sync';

        $users = $wpdb->get_results(
            "SELECT u.user_id, u.user_partner_id, w.user_email, u.website
             FROM $table_name u
             JOIN {$wpdb->prefix}users w ON u.user_id = w.ID"
        );

        if ($users) {
            return array(
                'status' => 'success',
                'data' => $users
            );
        } else {
            return array(
                'status' => 'fail',
                'message' => 'No users found'
            );
        }
    }

    function add_sync_user_menu() {
        add_action('admin_menu', function() {
            add_menu_page(
                'Shopbiz Partner Member',
                'Shopbiz Partner Member',
                'manage_options',
                'user-sync-list',
                'display_user_sync_list',
                'dashicons-admin-users',
                6
            );
        });
    }

    function display_user_sync_list() {
        ?>
        <div class="wrap">
            <h1>User Sync List</h1>
            <table id="user-sync-list" class="display" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px;">User ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">User Partner ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">User Email</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Partner</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = get_list_all_user_sync();
                    
                    if ($users['status'] === 'success') {
                        foreach ($users['data'] as $user) {
                            echo '<tr>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($user->user_id) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($user->user_partner_id) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($user->user_email) . '</td>';
                            echo '<td style="border: 1px solid #ddd; padding: 8px;">' . esc_html($user->website) . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('#user-sync-list').DataTable();
            });
        </script>
        <?php
    }