<?php
/**
 * Plugin Name: WooCommerce Media Promotion
 * Description: Handles CRUD for promotional images and videos with a custom database table.
 * Version: 1.0.0
 * Author: Nofan
 * Text Domain: wc-media-promotion
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WC_Media_Promotion {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'wc_media_promotion';

        register_activation_hook(__FILE__, [$this, 'create_database_table']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_save_media', [$this, 'save_media']);
        add_action('wp_ajax_delete_media', [$this, 'delete_media']);
        add_action('wp_ajax_get_media', [$this, 'get_media']);

    }
    
    

    public function create_database_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(10) NOT NULL,
            content TEXT NOT NULL,
            caption TEXT,
            category VARCHAR(255) NOT NULL,
            published BOOLEAN DEFAULT 0,
            publisher BIGINT UNSIGNED,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Media Promotions',
            'Media Promotions',
            'manage_options',
            'wc-media-promotion',
            [$this, 'render_admin_page'],
            'dashicons-format-image',
            25
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script('wc-media-promotion', plugin_dir_url(__FILE__) . 'script.js', ['jquery'], null, true);
        wp_enqueue_style('wc-media-promotion', plugin_dir_url(__FILE__) . 'style.css');
    }

    public function render_admin_page() {
        global $wpdb;
        $table = $this->table_name;

        $media_items = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
        ?>
        <div class="wrap">
            <h1>Media Promotions </h1>
            <button id="open-modal" class="button button-primary">Add Media</button>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <!--<th>ID</th>-->
                        <!--<th>Type</th>-->
                        <th>Content</th>
                        <th>Caption</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th>Publisher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($media_items as $item): ?>
                        <?php $user_info = get_userdata($item->publisher); ?>
                        <tr>
                            
                            <!--<td><?php echo esc_html($item->type); ?></td>-->
                           <td>
                              <?php if ($item->type === 'video'): ?>
                                <?php
                                $content = esc_url($item->content);
                            
                                // Match both YouTube Shorts, regular YouTube URLs, and youtu.be links
                                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([\w-]+)/', $content, $matches)) {
                                    $videoId = $matches[1]; // Extract video ID
                                    ?>
                                    <iframe width="200px" height="auto" src="https://www.youtube.com/embed/<?php echo esc_attr($videoId); ?>" frameborder="0" allowfullscreen></iframe>
                                <?php } else { ?>
                                    <video width="200px" controls>
                                    <source src="' . esc_url($content) . '" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <?php } ?>
                            <?php elseif ($item->type === 'image'): ?>
                                <img src="<?php echo esc_url($item->content); ?>" alt="Image" style="height: auto; max-width:200px">
                            <?php else: ?>
                                <?php echo esc_html($item->content); ?>
                            <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($item->caption); ?></td>
                            <td><?php echo esc_html($item->category); ?></td>
                            <td><?php echo $item->published ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $user_info ? esc_html($user_info->user_login) : 'Unknown'; ?></td>
                            <td>
                                <a href="#" class="button edit-media" data-id="<?php echo $item->id; ?>">Edit</a>
                                <a href="#" class="button delete-media" data-id="<?php echo $item->id; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="media-modal" class="modal">
            <div class="modal-content">
                <h2>Add/Edit Media</h2>
                <form id="media-form" method="POST">
                    <input type="hidden" name="media_id" id="media-id">
                    <p>
                        <label>Type:</label>
                        <select name="type" id="media-type">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </p>
                    <p id="image-upload">
                        <label>Upload Image:</label>
                        <input type="file" name="image_file">
                    </p>
                    <p >
                        <label>Video URL:</label>
                        <input type="text" name="content" id="video-url">
                    </p>
                    <p>
                        <label>Kategori</label>
                        <select id="category" name="category" value="promotion">
                            <option value="promotion">Promosi</option>
                            <option value="tutorial">Tutorial</option>
                            <option value="product">Produk</option>
                        </select>
                    </p>
                    <p>
                        <label>Caption:</label>
                        <textarea type="text" id="caption" name="caption"></textarea>
                    </p>
                    <p>
                        <label>Published:</label>
                        <input type="checkbox" id="published" name="published" value="1">
                    </p>
                    <p>
                        <button type="submit" class="button button-primary">Save</button>
                        <button type="button" id="close-modal" class="button">Cancel</button>
                    </p>
                </form>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                // Open Modal
                $('#open-modal').click(function() {
                    $('#media-modal').fadeIn();
                     $('#media-id').val("");
                            $('#media-type').val("image").trigger('change');
                            $('#caption').val("");
                            $('#category').val("promotion");
                            $('#published').prop('checked', false);
            
                            if (response.data.type === 'image') {
                                $('#current-image').attr('src', "").show();
                            } else {
                                $('#video-url').val("");
                            }
                });
            
                // Close Modal
                $('#close-modal, .modal-overlay').click(function() {
                    $('#media-modal').fadeOut();
                });
            
                // Toggle Between Image Upload & Video URL Input
                $('#media-type').change(function() {
                    if ($(this).val() === 'image') {
                        $('#image-upload').show();
                        $('#video-url').hide();
                    } else {
                        $('#image-upload').hide();
                        $('#video-url').show();
                    }
                }).trigger('change');
            
                // Delete Media
                $('.delete-media').click(function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this media?')) {
                        let mediaId = $(this).data('id');
                        $.post(ajaxurl, { action: 'delete_media', id: mediaId }, function(response) {
                            if (response.success) {
                                alert('Media deleted successfully');
                                location.reload();
                            } else {
                                alert('Failed to delete media');
                            }
                        }).fail(function() {
                            alert('Error deleting media');
                        });
                    }
                });
            
                // Handle Form Submission for Adding/Editing Media
                $('#media-form').submit(function(e) {
                    e.preventDefault();
            
                    let formData = new FormData(this);
                    formData.append('action', 'save_media'); // Add action for AJAX handler
            
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                alert('Media saved successfully');
                                location.reload();
                            } else {
                                alert('Failed to save media');
                            }
                        },
                        error: function() {
                            alert('Error processing request');
                        }
                    });
                });
            
                // Open Modal for Editing Media
                $('.edit-media').click(function(e) {
                    e.preventDefault();
                    let mediaId = $(this).data('id');
            
                    $.post(ajaxurl, { action: 'get_media', id: mediaId }, function(response) {
                        if (response.success) {
                            // alert(JSON.stringify(response.data.data));
                            $('#media-id').val(response.data.data.id);
                            $('#media-type').val(response.data.data.type).trigger('change');
                            $('#caption').val(response.data.data.caption);
                            $('#published').prop('checked', response.data.data.published == 1);
                            $('#category').val(response.data.data.category);

                            if (response.data.type === 'image') {
                                $('#current-image').attr('src', response.data.data.content).show();
                            } else {
                                $('#video-url').val(response.data.data.content);
                            }
            
                            $('#media-modal').fadeIn();
                        } else {
                            alert('Failed to fetch media details');
                        }
                    }).fail(function() {
                        alert('Error fetching media');
                    });
                });
            
                // Publish/Unpublish Media
                $('.toggle-publish').click(function(e) {
                    e.preventDefault();
                    let mediaId = $(this).data('id');
                    let newStatus = $(this).data('status') ? 0 : 1;
            
                    $.post(ajaxurl, { action: 'toggle_publish', id: mediaId, status: newStatus }, function(response) {
                        if (response.success) {
                            alert('Status updated successfully');
                            location.reload();
                        } else {
                            alert('Failed to update status');
                        }
                    }).fail(function() {
                        alert('Error updating status');
                    });
                });
            });
        </script>
        <?php
    }

    public function save_media() {
        global $wpdb;
        $table = $this->table_name;
    
        error_log('Saving media...');
    
        // Check POST data
        error_log('POST data: ' . print_r($_POST, true));
        error_log('FILES data: ' . print_r($_FILES, true));
    
        $type = sanitize_text_field($_POST['type']);
        $caption = sanitize_text_field($_POST['caption']);
        $category = sanitize_text_field($_POST['category']);
        $published = isset($_POST['published']) ? 1 : 0;
        $publisher = get_current_user_id();
        $content = '';
    
        if ($type === 'image' && !empty($_FILES['image_file']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
    
            $upload = wp_handle_upload($_FILES['image_file'], ['test_form' => false]);
            
            if (!isset($upload['error'])) {
                $content = $upload['url']; // Save full URL to database
            } else {
                wp_send_json_error(['message' => 'Image upload failed: ' . $upload['error']]);
            }
        } elseif ($type === 'video') {
            $content = esc_url($_POST['content']);
        }
    
        error_log('Content URL: ' . $content);
    
        $data = [
            'type'      => $type,
            'content'   => $content,
            'caption'   => $caption,
            'category'  => $category,
            'published' => $published,
            'publisher' => $publisher,
        ];
    
        if (!empty($_POST['media_id'])) {
            $result = $wpdb->update($table, $data, ['id' => intval($_POST['media_id'])]);
            error_log('Media updated: ' . ($result ? 'Success' : $wpdb->last_error));
        } else {
            $result = $wpdb->insert($table, $data);
            error_log('Media inserted: ' . ($result ? 'Success' : $wpdb->last_error));
        }
    
        if ($result) {
            wp_send_json_success(['message' => 'Media saved successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to save media']);
        }
    }
    
    public function get_media() {
        global $wpdb;
        $table = $this->table_name;
    
        if (!isset($_POST['id'])) {
            wp_send_json_error(['message' => 'Invalid request']);
        }
    
        $id = intval($_POST['id']);
        $media = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    
        if (!$media) {
            wp_send_json_error(['message' => 'Media not found']);
        }
    
        wp_send_json_success(['data' => $media]);
    }
    public function delete_media() {
        global $wpdb;
        $wpdb->delete($this->table_name, ['id' => intval($_POST['id'])]);
        wp_send_json_success();
    }
}

new WC_Media_Promotion();

function media_promotion_shortcode($atts) {
    global $wpdb;

    // Get shortcode attributes
    $atts = shortcode_atts([
        'category' => '',
        'columns'  => 3, // Default 3 kolom
    ], $atts);

    $category_filter = $atts['category'] ? $wpdb->prepare("AND category = %s", $atts['category']) : '';
    $columns = intval($atts['columns']);

    // Query media promotions
    $results = $wpdb->get_results("
        SELECT * FROM {$wpdb->prefix}wc_media_promotion
        WHERE published = 1 $category_filter
        ORDER BY created_at DESC
    ");

    if (!$results) {
        return '<p>No media promotions found.</p>';
    }

    // Get user sponsor info
    $user_id = get_current_user_id();
    $sponsor = '';
    if ($user_id) {
        $sponsor = $wpdb->get_var($wpdb->prepare("SELECT subdomain FROM wp_member WHERE idwp = %d", $user_id));
    }

    // Grid layout
    // $output = '<h2 class="media-promotion-title">Media Promotions</h2>';
    $output = '<div class="media-promotion-grid" style="display: grid; grid-template-columns: repeat(' . $columns . ', 1fr); gap: 15px;">';

    foreach ($results as $item) {
        $content_url = esc_url($item->content);
        $final_url = $content_url;

        // Append sponsor to URL if available
        if ($sponsor) {
            $final_url = add_query_arg('reg', $sponsor, $content_url);
        }

        // Determine media type
        $media_html = '';
        if ($item->type === 'video') {
            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([\w-]+)/', $content_url, $matches)) {
                $video_id = $matches[1];
                $media_html = '<div class="media-wrapper">
                    <iframe class="media-content" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allowfullscreen></iframe>
                </div>';
            } else {
                
                $media_html = '<div class="media-wrapper">
                    <video class="media-content" controls>
                        <source src="' . esc_url($final_url) . '" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>';
                    }
        } elseif ($item->type === 'image') {
            $media_html = '<div class="media-wrapper">
                <img class="media-content" src="' . esc_url($final_url) . '" alt="Image">
            </div>';
        } else {
            $media_html = esc_html($item->content);
        }
        // WhatsApp sharing link
        $whatsapp_caption = esc_attr($item->caption);
        $whatsapp_message = $whatsapp_caption ? "$whatsapp_caption\n\n$final_url" : $final_url;

        $output .= '<div class="media-promotion-card">
                        <div class="media-preview">' . $media_html . '</div>
                        <div class="media-info">
                            <a href="' . esc_url($final_url) . '" class="media-link">View Media</a>
                            <button class="whatsapp-share-btn" data-url="' . esc_url($final_url) . '" data-caption="' . $whatsapp_caption . '">Share on WhatsApp</button>
                        </div>
                    </div>';
    }

    $output .= '</div>'; // End Grid

    // CSS untuk 16:9 Aspect Ratio & Grid
    $output .= '<style>
        .media-promotion-grid {
            display: grid;
            grid-template-columns: repeat(' . $columns . ', 1fr);
            gap: 15px;
        }
        .media-promotion-card {
            background: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .media-wrapper {
            width: 100%;
            position: relative;
            overflow: hidden;
            border-radius: 5px;
            aspect-ratio: 16 / 9; /* 16:9 Ratio */
        }
        .media-content {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .whatsapp-share-btn {
            background: #25D366;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>';

    // JavaScript for WhatsApp sharing
    $output .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".whatsapp-share-btn").forEach(function(button) {
                button.addEventListener("click", function() {
                    const url = this.getAttribute("data-url");
                    const caption = this.getAttribute("data-caption") || "";
                    const fullMessage = caption ? caption + "\n\n" + url : url;
                    const whatsappLink = "whatsapp://send?text=" + encodeURIComponent(fullMessage);
                    window.location.href = whatsappLink;
                });
            });
        });
    </script>';

    return $output;
}
add_shortcode('media-promotion', 'media_promotion_shortcode');