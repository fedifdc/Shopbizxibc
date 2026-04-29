<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$user_prize_table = $wpdb->prefix . 'user_prizes';
$prize_table = $wpdb->prefix . 'prizes';

// Fetch user prizes along with prize information
$user_prizes = $wpdb->get_results("
    SELECT 
        u.ID AS user_id, 
        u.display_name, 
        u.user_login, 
        up.points, 
        up.prize_id, 
        up.assigned_at,
        up.is_approved,
        up.is_claimed,
        p.prize_name,
        p.image_url
    FROM {$wpdb->users} u
    INNER JOIN $user_prize_table up ON u.ID = up.user_id
    LEFT JOIN $prize_table p ON up.prize_id = p.id
    ORDER BY up.points DESC
");

// Handle prize assignment, deletion, and user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
          case 'approve_prize':
           	if (isset($_POST['user_id'])) {
                $user_id = intval($_POST['user_id']);

                // Update the is_approved field to true for the user
                $wpdb->update(
                    $user_prize_table,
                    ['is_approved' => 1], // Set is_approved to true
                    ['user_id' => $user_id] // Where user_id matches
                );

                echo '<script>alert("Prize approved successfully."); window.location.reload();</script>';
            } 
            case 'assign_prize':
                if (isset($_POST['user_id']) && isset($_POST['prize_id'])) {
                    $user_id = intval($_POST['user_id']);
                    $prize_id = intval($_POST['prize_id']);
                    
                    // Check the prize's remaining quota before assigning
                    $remaining_quota = $wpdb->get_var("SELECT remaining_quota FROM $prize_table WHERE id = $prize_id");

                    if ($remaining_quota > 0) {
                        // Check if a prize is already assigned to the user, update it
                        $existing_prize = $wpdb->get_var("SELECT id FROM $user_prize_table WHERE user_id = $user_id");

                        if ($existing_prize) {
                            // Update existing prize
                            $wpdb->update($user_prize_table, ['prize_id' => $prize_id, 'assigned_at' => current_time('mysql')], ['user_id' => $user_id]);
                        } else {
                            // Assign new prize
                            $wpdb->insert($user_prize_table, ['user_id' => $user_id, 'prize_id' => $prize_id, 'assigned_at' => current_time('mysql')]);
                        }

                        // Update the remaining_quota
                        $wpdb->update($prize_table, ['remaining_quota' => $remaining_quota - 1], ['id' => $prize_id]);

                        echo '<script>alert("Prize assigned successfully"); window.location.reload();</script>';
                    } else {
                        echo '<script>alert("Prize quota is exhausted"); window.location.reload();</script>';
                    }
                }
                break;

            case 'delete_prize':
                if (isset($_POST['user_id'])) {
                    $user_id = intval($_POST['user_id']);
                    
                    // Get the prize ID assigned to the user
                    $user_prize = $wpdb->get_row("SELECT prize_id FROM $user_prize_table WHERE user_id = $user_id");
                    
                    if ($user_prize && $user_prize->prize_id) {
                        // Get the prize's remaining quota before deleting
                        $prize_id = $user_prize->prize_id;
                        $remaining_quota = $wpdb->get_var("SELECT remaining_quota FROM $prize_table WHERE id = $prize_id");

                        // Remove the prize assignment
                        $wpdb->update($user_prize_table, ['prize_id' => null, 'assigned_at' => null, 'is_approved' => 0, 'is_claimed' => 0], ['user_id' => $user_id]);

                        // Update the remaining_quota
                        $wpdb->update($prize_table, ['remaining_quota' => $remaining_quota + 1], ['id' => $prize_id]);

                        echo '<script>alert("Prize removed successfully"); window.location.reload();</script>';
                    }
                }
                break;

            case 'delete_user':
                if (isset($_POST['user_id'])) {
                    $user_id = intval($_POST['user_id']);

                    // Delete the user from the users table
                    require_once(ABSPATH . 'wp-admin/includes/user.php');
                    wp_delete_user($user_id);

                    // Remove any prize assignment related to this user
                    $wpdb->update($user_prize_table, ['prize_id' => null, 'assigned_at' => null], ['user_id' => $user_id]);

                    echo '<script>alert("User deleted successfully"); window.location.reload();</script>';
                }
                break;
            case 'reset_points':
                 if (class_exists('myCRED_Core')) {
                    $mycred = mycred(); // Initialize the myCRED core class
                    $point_type = 'point_voucher';

                    // Retrieve all users
                    $args = array(
                        'fields' => 'ID', // Only fetch user IDs
                        'number' => -1,   // Get all users
                    );

                    $user_query = new WP_User_Query($args);
                    $user_ids = $user_query->get_results();

                    if (!empty($user_ids)) {
                        foreach ($user_ids as $user_id) {
                            // Reset the points for each user
                          	if ( ! $mycred->exclude_user( $user_id ) )
                            	$mycred->update_users_balance($user_id, 0, $point_type);
                        }
                        echo '<script>alert("All point_voucher points have been reset to 0."); window.location.reload();</script>';
                    } else {
                        echo '<script>alert("No users found.");</script>';
                    }
                }
                break;

        case 'delete_all_users':

            // Clear user_prizes table
            $wpdb->query("DELETE FROM {$user_prize_table}");

            echo '<script>alert("All users have been deleted."); window.location.reload();</script>';
            break;
        }
    }
}
?>
<div class="wrap">
    <h1>User Prizes</h1>
  	 <!-- Reset Points Form -->
    <form method="post" onsubmit="return confirm('Are you sure you want to reset all point_voucher points to 0? This action cannot be undone.');">
        <input type="hidden" name="action" value="reset_points">
        <button type="submit" class="button button-primary">Reset All Points</button>
    </form>

    <!-- Delete All Users Form -->
    <form method="post" onsubmit="return confirm('Are you sure you want to delete all users in the user_prizes table? This action cannot be undone.');">
        <input type="hidden" name="action" value="delete_all_users">
        <button type="submit" class="button button-secondary">Delete All Users</button>
    </form>

    <br>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Points</th>
                <th>Prize Name</th>
                <th>Prize Image</th>
                <th>Assigned At</th>
              	<th>Approval</th>
              	<th>Claim</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($user_prizes)): ?>
                <?php foreach ($user_prizes as $user_prize): ?>
                    <tr>
                        <td><?php echo esc_html($user_prize->user_id); ?></td>
                        <td><?php echo esc_html($user_prize->display_name); ?></td>
                        <td><?php echo esc_html($user_prize->user_login); ?></td>
                        <td><?php echo esc_html($user_prize->points); ?>/10</td>
                        <td>
                            <?php echo !empty($user_prize->prize_name) ? esc_html($user_prize->prize_name) : 'No Prize'; ?>
                        </td>
                        <td>
                            <?php if (!empty($user_prize->image_url)): ?>
                                <img src="<?php echo esc_url($user_prize->image_url); ?>" alt="Prize Image" style="max-width: 100px; height: auto;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($user_prize->assigned_at); ?></td>
                      	<td>
                            <?php echo $user_prize->is_approved === "1" ? "Sudah Di Approve" : "-"; ?>
                        </td>
                      	<td>
                            <?php echo $user_prize->is_claimed ? "Sudah Di Klaim" : "-"; ?>
                        </td>
                        <td>
                            <!-- Prize Assignment Form -->
                          	<?php if (empty($user_prize->prize_id)): ?>
                              <form action="" method="post" style="display:inline-block;">
                                  <input type="hidden" name="action" value="assign_prize">
                                  <input type="hidden" name="user_id" value="<?php echo esc_attr($user_prize->user_id); ?>">
                                  <select name="prize_id" required>
                                      <option value="">Select Prize</option>
                                      <?php
                                      $prizes = $wpdb->get_results("SELECT * FROM $prize_table");

                                      foreach ($prizes as $prize):
                                      ?>
                                          <option value="<?php echo esc_attr($prize->id); ?>" <?php echo $user_prize->prize_id == $prize->id ? 'selected' : ''; ?>>
                                              <?php echo esc_html($prize->prize_name); ?> (Remaining: <?php echo esc_html($prize->remaining_quota); ?>)
                                          </option>
                                      <?php endforeach; ?>
                                  </select>
                                  <button type="submit" class="button">Assign Prize</button>
                              </form>
                          	<?php endif;?>

                            <!-- Delete Prize Form -->
                          	<?php if (!empty($user_prize->prize_id)): ?>
                              <form action="" method="post" style="display:inline-block;">
                                  <input type="hidden" name="action" value="delete_prize">
                                  <input type="hidden" name="user_id" value="<?php echo esc_attr($user_prize->user_id); ?>">
                                  <button type="submit" class="button deleteBtn">Delete Prize</button>
                              </form>
							<?php endif;?>
                            <!-- Delete User Form -->
                            <form action="" method="post" style="display:inline-block;" onsubmit="return confirmDelete();">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?php echo esc_attr($user_prize->user_id); ?>">
                                <button type="submit" class="button deleteBtn">Delete User</button>
                            </form>
                          	<!-- Approve User Prize-->
                          	<?php if (!empty($user_prize->prize_id) && $user_prize->is_approved === "0"): ?>
                                <form action="" method="post" style="display:inline-block;">
                                    <input type="hidden" name="action" value="approve_prize">
                                    <input type="hidden" name="user_id" value="<?php echo esc_attr($user_prize->user_id); ?>">
                                    <button type="submit" class="button button-primary">Approve</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No user prizes found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function confirmDelete() {
        return confirm('Are you sure you want to delete this user? This action cannot be undone.');
    }
</script>