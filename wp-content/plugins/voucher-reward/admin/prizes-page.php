<?php
if (!defined('ABSPATH')) {
    exit;
}
global $wpdb;
$prize_table = $wpdb->prefix . 'prizes';

// Handle form submissions for saving and updating prizes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete_prize':
                if (isset($_POST['id'])) {
                    $wpdb->delete($prize_table, ['id' => intval($_POST['id'])]);
                    echo '<script>alert("Prize deleted successfully");window.location.href="admin.php?page=voucher-reward";</script>';
                }
                break;
            case 'save_prize':
                // Save or update prize based on input data
                $image_url = '';
                if (!empty($_FILES['image_url']['name'])) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    $upload_overrides = array('test_form' => false);
                    $uploaded_file = wp_handle_upload($_FILES['image_url'], $upload_overrides);
                    if (isset($uploaded_file['url'])) {
                        $image_url = $uploaded_file['url'];
                    }
                }

                $prize_data = [
                    'prize_name' => sanitize_text_field($_POST['prize_name']),
                    'quota' => intval($_POST['quota']),
                    'priority' => intval($_POST['priority']),
                    'remaining_quota' => intval($_POST['quota']),
                ];

                if ($image_url) {
                    $prize_data['image_url'] = esc_url($image_url);
                }

                if ($_POST['prize_id']) {
                    $wpdb->update($prize_table, $prize_data, ['id' => intval($_POST['prize_id'])]);
                } else {
                    $wpdb->insert($prize_table, $prize_data);
                }
                echo '<script>alert("Prize saved successfully");window.location.href="admin.php?page=voucher-reward";</script>';
                break;
        }
    }
}

// Get prizes data
$prizes = $wpdb->get_results("SELECT * FROM $prize_table ORDER BY priority ASC");

// Add necessary scripts and styles
wp_enqueue_media();
?>
<div class="wrap">
    <h1>Manage Prizes</h1>
    <button type="button" class="page-title-action" id="addNewPrizeBtn">Add New Prize</button>

    <!-- Prize List Table -->
    <table class="wp-list-table widefat fixed striped" id="prizeTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prize Name</th>
                <th>Quota</th>
                <th>Remaining Quota</th>
                <th>Priority</th>
                <th>Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prizes as $prize): ?>
                <tr data-id="<?php echo esc_attr($prize->id); ?>">
                    <td><?php echo esc_html($prize->id); ?></td>
                    <td><?php echo esc_html($prize->prize_name); ?></td>
                    <td><?php echo esc_html($prize->quota); ?></td>
                    <td><?php echo esc_html($prize->remaining_quota); ?></td>
                    <td><?php echo esc_html($prize->priority); ?></td>
                    <td>
                        <?php if (!empty($prize->image_url)): ?>
                            <img src="<?php echo esc_url($prize->image_url); ?>" alt="Prize Image" style="max-width: 100px; height: auto;">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="button editBtn" data-id="<?php echo esc_attr($prize->id); ?>">Edit</button>
                        <form action="" method="post" style="display:inline-block;" class="deleteForm">
                            <input type="hidden" name="action" value="delete_prize">
                            <input type="hidden" name="id" value="<?php echo esc_attr($prize->id); ?>">
                            <button type="submit" class="button deleteBtn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add/Edit Modal -->
    <div id="prizeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add New Prize</h2>
            <form id="prizeForm" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="prize_id" id="prize_id">
                <input type="hidden" name="action" id="form_action" value="save_prize">
                
                <table class="form-table">
                    <tr>
                        <th><label for="prize_name">Prize Name</label></th>
                        <td><input type="text" name="prize_name" id="prize_name" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="quota">Quota</label></th>
                        <td><input type="number" name="quota" id="quota" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="priority">Priority</label></th>
                        <td><input type="number" name="priority" id="priority" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="image_url">Image</label></th>
                        <td>
                            <input type="file" name="image_url" id="image_url" accept="image/*">
                            <div id="image_preview"></div>
                        </td>
                    </tr>
                </table>
                <div class="submit-button">
                    <button type="submit" class="button button-primary">Save Prize</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            position: relative;
            border-radius: 4px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .submit-button {
            text-align: right;
            margin-top: 20px;
        }
        #image_preview img {
            max-width: 200px;
            margin-top: 10px;
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Open Add Modal
            $('#addNewPrizeBtn').on('click', function() {
                $('#modalTitle').text('Add New Prize');
                $('#prizeForm')[0].reset();
                $('#form_action').val('save_prize');
                $('#prize_id').val('');
                $('#image_preview').empty();
                $('#prizeModal').show();
            });

            // Open Edit Modal and load prize data
            $(document).on('click', '.editBtn', function() {
                var prizeId = $(this).data('id');
                $('#form_action').val('save_prize');
                $('#prize_id').val(prizeId);

                // Get prize data from the table
                var row = $(this).closest('tr');
                var prizeName = row.find('td').eq(1).text();
                var quota = row.find('td').eq(2).text();
                var priority = row.find('td').eq(4).text();
                var imageUrl = row.find('td').eq(5).find('img').attr('src');

                // Set the modal form fields with the data
                $('#prize_name').val(prizeName);
                $('#quota').val(quota);
                $('#priority').val(priority);
                if (imageUrl) {
                    $('#image_preview').html('<img src="' + imageUrl + '" alt="Prize Image">');
                }

                $('#modalTitle').text('Edit Prize');
                $('#prizeModal').show();
            });

            // Close Modal
            $('.close, .modal').on('click', function(e) {
                if (e.target === this) {
                    $('#prizeModal').hide();
                }
            });

            $('.modal-content').on('click', function(e) {
                e.stopPropagation();
            });

            // Confirm before delete
            $('.deleteForm').on('submit', function(e) {
                if (!confirm('Are you sure you want to delete this prize?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</div>