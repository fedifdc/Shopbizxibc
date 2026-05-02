<?php
namespace WooYoutubeLink;

if (!defined('ABSPATH')) {
    exit;
}

class Dokan {
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('dokan_product_edit_after_pricing', [$this, 'add_youtube_field']);
        add_action('dokan_process_product_meta', [$this, 'save_youtube_field'], 10, 1);
    }

    public function add_youtube_field($post_id) {

    $youtube_link = get_post_meta($post_id->ID, '_youtube_link', true);
   
    ?>
    <div class="dokan-form-group">
        <label for="_youtube_link" class="form-label"><?php esc_html_e('Marketing Link', 'woo-youtube-link'); ?></label>
        <input type="url" class="dokan-form-control" name="_youtube_link" id="_youtube_link" value="<?php echo esc_attr($youtube_link); ?>" placeholder="Marketing kontent link">
    </div>
    <?php
}


    public function save_youtube_field($post_id) {
        if (isset($_POST['_youtube_link'])) {
            update_post_meta($post_id, '_youtube_link', sanitize_text_field($_POST['_youtube_link']));
        }
    }
}
