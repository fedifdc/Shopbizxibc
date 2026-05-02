<?php
if(!defined('ABSPATH')){
	exit;
}

class Whatsapp_User_WP{
    
  	public function __construct()
    {
        // Add WhatsApp Number field to user profile in admin
    
        add_action('show_user_profile', [$this, 'custom_add_whatsapp_field']); // For user profile
        add_action('edit_user_profile' , [$this, 'custom_add_whatsapp_field']); // For admin edit user
        
        // Save WhatsApp Number field
        
        add_action('personal_options_update',[$this,'custom_save_whatsapp_field'] ); // For user profile
        add_action('edit_user_profile_update', [$this,'custom_save_whatsapp_field']); // For admin edit user
      // Tambahkan field WhatsApp di halaman Edit Akun
        add_action('woocommerce_edit_account_form', function() {
            $user_id = get_current_user_id();
            $whatsapp_number = get_user_meta($user_id, '_whatsapp_number', true);

            echo '<p class="form-row form-row-wide">
                <label for="whatsapp_number">WhatsApp Number <span class="required">*</span></label>
                <input type="text" class="input-text" name="whatsapp_number" id="whatsapp_number" value="' . esc_attr($whatsapp_number) . '" />
                <strong>Pastikan nomor WhatsApp Anda valid sebelum menyimpan perubahan karena nomor ini digunakan untuk verifikasi OTP</strong
            </p>';
        });

        // Simpan data WhatsApp dan update database tambahan
        add_action('woocommerce_save_account_details', function($user_id) {
            if (isset($_POST['whatsapp_number'])) {
                $whatsapp_number = sanitize_text_field($_POST['whatsapp_number']);

                // Update user meta
                update_user_meta($user_id, '_whatsapp_number', $whatsapp_number);

                global $wpdb;

                // Update tabel wp_member
                $wp_member = $wpdb->get_row($wpdb->prepare("SELECT homepage FROM wp_member WHERE idwp = %d", $user_id));
                if ($wp_member) {
                    $homepage_data = unserialize($wp_member->homepage);
                    $homepage_data['whatsapp'] = $whatsapp_number;

                    $wpdb->update(
                        'wp_member',
                        ['homepage' => serialize($homepage_data)],
                        ['idwp' => $user_id],
                        ['%s'],
                        ['%d']
                    );
                }

                // Update tabel wafu_member
                $wafu_member = $wpdb->get_row($wpdb->prepare("SELECT member_idwp FROM wafu_member WHERE member_idwp = %d", $user_id));
                if ($wafu_member) {
                    $wpdb->update(
                        'wafu_member',
                        ['member_wa' => $whatsapp_number],
                        ['member_idwp' => $user_id],
                        ['%s'],
                        ['%d']
                    );
                }
            }
        });
    }
    
    public function custom_add_whatsapp_field($user) { ?>
        <h3>Additional Information</h3>
        <table class="form-table">
            <tr>
                <th><label for="whatsapp_number">WhatsApp Number</label></th>
                <td>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, '_whatsapp_number', true)); ?>" 
                           class="regular-text" /><br>
                    <span class="description">Enter the user's WhatsApp number.</span>
                </td>
            </tr>
        </table>
    <?php }
    
    public function custom_save_whatsapp_field($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        if (isset($_POST['whatsapp_number'])) {
            update_user_meta($user_id, '_whatsapp_number', normalize_whatsapp_number(sanitize_text_field($_POST['whatsapp_number'])));
        }
    }
}