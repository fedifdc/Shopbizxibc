<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WhatsApp_Login
{

    public function __construct()
    {
        add_shortcode('add_whatsapp_number', [$this, 'verify_whatsapp_shortcode']);
        add_filter('authenticate', [$this, 'redirect_to_otp_verification'], 30, 3);
        add_action('template_redirect', [$this, 'check_otp_verification_page']);
        add_action('wp_ajax_update_whatsapp_number', [$this, 'update_whatsapp_number_callback']);
        add_action('wp_ajax_nopriv_update_whatsapp_number', [$this, 'update_whatsapp_number_callback']);
        add_action('wp_ajax_verify_post_login_otp', [$this, 'verify_post_login_otp']);
        add_action('wp_ajax_nopriv_verify_post_login_otp', [$this, 'verify_post_login_otp']);
        add_action('woocommerce_edit_account_form', [$this, 'add_otp_setting_dropdown_to_edit_account']);
        add_action('woocommerce_save_account_details', [$this, 'save_otp_setting_dropdown_to_user_meta'], 10, 1);
    }

    public function add_otp_setting_dropdown_to_edit_account()
    {
        $user_id = get_current_user_id();
        $otp_frequency = get_user_meta($user_id, '_otp_frequency', true); // Ambil nilai dari user meta
        $options = [1 => '1 Hari sekali', 3 => '3 Hari sekali', 7 => '1 Minggu sekali', 14 => '2 Minggu sekali', 30 => '1 Bulan sekali']; // Opsi dropdown

	?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="otp_frequency"><?php esc_html_e('Frekuensi Permintaan OTP Login', 'textdomain'); ?></label>
            <select name="otp_frequency" id="otp_frequency" class="woocommerce-Input woocommerce-Input--select">
                <?php foreach ($options as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($otp_frequency, $value); ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php
    }

    public function save_otp_setting_dropdown_to_user_meta($user_id)
    {
        if (isset($_POST['otp_frequency'])) {
            $otp_frequency = intval($_POST['otp_frequency']);
            $valid_options = [1, 3, 7, 14, 30]; // Opsi yang valid

            if (in_array($otp_frequency, $valid_options)) {
                update_user_meta($user_id, '_otp_frequency', $otp_frequency);
            } else {
                wc_add_notice(__('Invalid OTP frequency selected.', 'textdomain'), 'error');
            }
        }
    }

    function update_whatsapp_number_callback()
    {
        global $wpdb;
        $whatsapp_number = '';
        if (isset($_POST['user_id']) && isset($_POST['whatsapp_number'])) {
            $user_id = intval($_POST['user_id']);
            $whatsapp_number = sanitize_text_field($_POST['whatsapp_number']);
            if(is_whatsapp_number_used($_POST['whatsapp_number'])){
                wp_send_json_error(['message' => __('Nomor WhatsApp telah digunakan oleh akun lain', 'text-domain')]);
            }

            update_user_meta($user_id, '_whatsapp_number', normalize_whatsapp_number($whatsapp_number));
            update_user_meta($user_id, 'billing_phone', normalize_whatsapp_number($whatsapp_number));

            $member = $wpdb->get_row(
                $wpdb->prepare(
                    'SELECT * FROM `wp_member` WHERE `idwp` = %d',
                    $user_id
                ),
                ARRAY_A
            );

            if ($member) {
                // Unserialize the homepage field
                $homepage = maybe_unserialize($member['homepage']);
                error_log('INI LOGIN');
                // Update the WhatsApp number in the homepage array
                $homepage['whatsapp'] = normalize_whatsapp_number($whatsapp_number);

                // Serialize the updated homepage field
                $updated_homepage = maybe_serialize($homepage);

                // Update the database
                $wpdb->query(
                    $wpdb->prepare(
                        'UPDATE `wp_member` SET `homepage` = %s WHERE `idwp` = %d',
                        $updated_homepage,
                        $user_id
                    )
                );
                $member_wafu = $wpdb->get_row($wpdb->prepare('SELECT * FROM `wafu_member` WHERE `member_idwp`=%d', $user_id));

                if ($member_wafu) {
                    $update_result = $wpdb->update(
                        'wafu_member', // Table name
                        ['member_wa' => normalize_whatsapp_number($whatsapp_number)], // Data to update
                        ['member_idwp' => $user_id], // Where condition
                        ['%s'], // Data format for member_wa
                        ['%d']  // Data format for member_idwp
                    );
                
                    if ($update_result === false) {
                        // Log or handle error
                        error_log("Failed to update WhatsApp number for user_id $user_id");
                    }
                }
            }
            wp_set_auth_cookie($user_id);
            unset($_SESSION['whatsapp_user_id']);
          	delete_user_meta($user_id, '_whatsapp_change_token');
            wp_send_json_success();
        }

        wp_send_json_error();
    }

    /**
     * Handles user redirection to OTP verification based on WhatsApp number and frequency settings
     * 
     * @param WP_User|WP_Error $user     WordPress user object or error
     * @param string           $username Username (unused)
     * @param string           $password Password (unused)
     * @return WP_User|WP_Error User object or error object
     */
    public function redirect_to_otp_verification($user, $username = '', $password = '')
    {
        global $wpdb;

        error_log('[OTP Verification] Starting verification process');

        if (is_wp_error($user)) {
            error_log('[OTP Verification] User login error: ' . $user->get_error_message());
			return $user;
        }

        error_log('[OTP Verification] Processing user ID: ' . $user->ID);

        // Start session if not already started
        if (!session_id()) {
            session_start();
            error_log('[OTP Verification] New session started');
        }

        $whatsapp_number = get_user_meta($user->ID, '_whatsapp_number', true);
        
        
        error_log('[OTP Verification] WhatsApp number found: ' . ($whatsapp_number ? 'Yes' : 'No'));

        if (empty($whatsapp_number)) {
            error_log('[OTP Verification] No WhatsApp number found, redirecting to add number page');
            $_SESSION['whatsapp_user_id'] = $user->ID;
            wp_clear_auth_cookie();
            wp_safe_redirect(home_url('/add-whatsapp-number'));
            exit;
        }
        
        // Check OTP frequency
        $otp_frequency = (int) get_user_meta($user->ID, '_otp_frequency', true) ?: 7;
        if (!$otp_frequency){
            update_user_meta($user-ID, '_otp_frequency', 7);
        }
        error_log("[OTP Verification] OTP frequency setting: {$otp_frequency} days");

        if ($otp_frequency > 0) {
          	//disable for temp
            $table_name = $wpdb->prefix . 'whatsapp_verification';
            $query = $wpdb->prepare(
                "SELECT created_at 
            FROM {$table_name} 
            WHERE phone_number = %s 
            AND status = 'verified' 
            ORDER BY created_at DESC 
            LIMIT 1",
                $whatsapp_number
            );
            error_log("[OTP Verification] Executing query: {$query}");

            $last_otp_time = $wpdb->get_var($query);
            error_log("[OTP Verification] Last OTP verification time: " . ($last_otp_time ?? 'Never'));

            if ($last_otp_time) {
                $last_otp_timestamp = strtotime($last_otp_time);
                $next_allowed_time = $last_otp_timestamp + ($otp_frequency * DAY_IN_SECONDS);
                $current_time = strtotime(current_time('mysql'));

                error_log(sprintf(
                    '[OTP Verification] Time check - Current: %s, Next allowed: %s',
                    date('Y-m-d H:i:s', $current_time),
                    date('Y-m-d H:i:s', $next_allowed_time)
                ));

                if ($current_time < $next_allowed_time) {
                    error_log('[OTP Verification] Within frequency period, skipping OTP');
                    return $user;
                }
            }
        }

        // Waktu jeda untuk pengiriman OTP dalam detik
        $otp_resend_delay = 60; // 60 detik
        
        // Periksa apakah sesi pengiriman OTP sudah ada
        if (isset($_SESSION['otp_last_sent_time'])) {
            $time_since_last_otp = time() - $_SESSION['otp_last_sent_time'];
        
            if ($time_since_last_otp < $otp_resend_delay) {
                error_log('[OTP Verification] OTP already sent recently, waiting before resending');
                /*return new WP_Error(
                    'otp_already_sent',
                    sprintf(
                        __('Please wait %d seconds before requesting a new OTP.', 'text-domain'),
                        $otp_resend_delay - $time_since_last_otp
                    )
                );*/
              	error_log('[OTP Verification] OTP sent successfully, redirecting to verification page');
                $_SESSION['otp_user_id'] = $user->ID;
                wp_clear_auth_cookie();
                wp_safe_redirect(home_url('/verification'));
                exit;
              
            }
        }
        
        // Send OTP
        error_log('[OTP Verification] Attempting to send OTP');
        $whatsapp_otp = new WhatsApp_OTP_Verification();
        
        try {
            $otp_sent = $whatsapp_otp->send_otp(normalize_whatsapp_number($whatsapp_number));
            error_log("[OTP Verification] OTP send result: " . ($otp_sent ? 'Success' : 'Failed'));
        } catch (Exception $e) {
            error_log("[OTP Verification] Exception while sending OTP: " . $e->getMessage());
            $otp_sent = false;
        }
        
        if (!$otp_sent) {
            error_log('[OTP Verification] Failed to send OTP, returning error');
            return new WP_Error(
                'otp_send_failed',
                __('Failed to send OTP to WhatsApp number. Please try again.', 'text-domain')
            );
        }
        
        // Simpan waktu pengiriman terakhir di sesi
        $_SESSION['otp_last_sent_time'] = time();
        
        error_log('[OTP Verification] OTP sent successfully, redirecting to verification page');
        $_SESSION['otp_user_id'] = $user->ID;
        wp_clear_auth_cookie();
        wp_safe_redirect(home_url('/verification'));
        exit;
    }
    // Check if user is on the OTP verification page
    public function check_otp_verification_page()
    {
        if (!session_id()) {
            session_start();
        }

        if (is_page('verification') && empty($_SESSION['otp_user_id'])) {
            wp_redirect(home_url('/my-account'));
            exit;
        }
    }

    // Render OTP verification form
    public function render_otp_verification_form()
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION['otp_user_id'])) {
            return '<p>' . esc_html__('Sesi telah berakhir. Silahkan login ulang', 'text-domain') . '</p>';
        }

        ob_start();
    ?>
        <form id="otp-verification-form">
            <h2><?php esc_html_e('Verifikasi OTP', 'text-domain'); ?></h2>
            <p><?php esc_html_e('Masukkan kode OTP yang telah dikirimkan ke nomor WhatsApp.', 'text-domain'); ?></p>

            <div class="form-row">
                <label for="otp"><?php esc_html_e('OTP', 'text-domain'); ?> <span class="required">*</span></label>
                <input type="number" id="otp" name="otp" required placeholder="<?php esc_attr_e('Masukkan OTP', 'text-domain'); ?>" />
            </div>

            <button type="button" id="verify_otp_button" class="button"><?php esc_html_e('Verify OTP', 'text-domain'); ?></button>
            <span id="otp_loading" style="display: none;"><?php esc_html_e('Memverifikasi...', 'text-domain'); ?></span>
        </form>

        <script type="text/javascript">
            jQuery(function($) {
                $('#verify_otp_button').on('click', function() {
                    var otp = $('#otp').val();
                    if (!otp) {
                        alert('<?php esc_html_e("Please enter the OTP.", "text-domain"); ?>');
                        return;
                    }

                    $('#verify_otp_button').hide();
                    $('#otp_loading').show();

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'verify_post_login_otp',
                            otp: otp
                        },
                        success: function(response) {
                            $('#otp_loading').hide();
                            $('#verify_otp_button').show();

                            if (response.success) {
                                alert('<?php esc_html_e("OTP verified successfully.", "text-domain"); ?>');
                                window.location.href = '<?php echo esc_url(home_url('/my-account')); ?>';
                            } else {
                                alert(response.data.message);
                            }
                        }
                    });
                });
            });
        </script>
    <?php
        return ob_get_clean();
    }

    // Verify OTP after login
    public function verify_post_login_otp()
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION['otp_user_id'])) {
            wp_send_json_error(['message' => __('Sesi telah habis. Silahkan login ulang', 'text-domain')]);
        }

        $user_id = $_SESSION['otp_user_id'];
        $otp = sanitize_text_field($_POST['otp']);

        $whatsapp_otp = new WhatsApp_OTP_Verification();
        $whatsapp_number = get_user_meta($user_id, '_whatsapp_number', true);
        
        if(empty($whatsapp_number)){
             wp_send_json_error(['message' => __('Tidak ada nomor Whatsapp yang terdaftar pada akun Anda', 'text-domain')]);
        }
        if ($whatsapp_otp->verify_otp($whatsapp_number, $otp)) {
            wp_set_auth_cookie($user_id); // Log the user back in
            unset($_SESSION['otp_user_id']); // Clear the session
            wp_send_json_success(['message' => __('Kode OTP  telah terverifikasi.', 'text-domain')]);
        } else {
            wp_send_json_error(['message' => __('Kode OTP tidak valid atau kadaluarsa.', 'text-domain')]);
        }
    }

    function verify_whatsapp_shortcode()
    {
        if (!session_id()) {
            session_start();
        }
			//check query param token & user_id available in url
        if (empty($_SESSION['whatsapp_user_id']) && (empty($_GET['token']) || empty($_GET['user_id']))) {
            return '<p>' . esc_html__('Sesi telah berakhir. Silahkan login ulang', 'text-domain') . '</p>';
        }
      
      	if(!empty($_GET['token'])){
      	    if(!is_valid_whatsapp_update_token($_GET['user_id'],$_GET['token'])){
        	    return '<p>' . esc_html__('Link ini sudah tidak berlaku', 'text-domain') . '</p>';
            }
      	}

        ob_start();
    ?>
        <div id="whatsapp-verification-form" style="font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto;">
            <h3 style="text-align: center; color: darkorange;"><?php esc_html_e( empty($_GET['user_id']) ? 'Tambahkan No WhatsApp' : 'Perbarui No WhatsApp', 'text-domain'); ?></h3>

            <!-- WhatsApp Number -->
            <div class="form-row" style="margin-bottom: 20px;">
                <label for="whatsapp_number"><?php esc_html_e('Nomor WhatsApp', 'text-domain'); ?></label>
                <div style="display: flex; align-items: center;">
                    <input type="text" id="whatsapp_number" placeholder="628123xxxxx" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-right: 10px;" />
                    <button id="send_otp_wa" style="background-color: darkorange; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;"><?php esc_html_e('Kirim OTP', 'text-domain'); ?></button>
                </div>
                <span id="send_otp_loading" style="display: none; color: darkorange;"><?php esc_html_e('Mengirim OTP...', 'text-domain'); ?></span>
                <div id="timer_container" style="display: none; margin-top: 10px;">
                    <span><?php esc_html_e('Kirim ulang dalam:', 'text-domain'); ?> </span>
                    <span id="timer">05:00</span>
                </div>
            </div>

            <!-- OTP Input -->
            <div class="form-row" id="otp_field" style="display: none; margin-bottom: 20px;">
                <label for="otp"><?php esc_html_e('Masukkan OTP', 'text-domain'); ?></label>
                <div style="display: flex; align-items: center;">
                    <input type="text" id="otp" placeholder="Masukkan kode OTP" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-right: 10px;" />
                    <button id="verify_otp_wa" style="background-color: darkorange; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;"><?php esc_html_e('Verifikasi', 'text-domain'); ?></button>
                </div>
                <span id="verify_otp_loading" style="display: none; color: darkorange;"><?php esc_html_e('Memverifikasi...', 'text-domain'); ?></span>
            </div>

            <!-- Success Message -->
            <div id="success_message" style="display: none; text-align: center; color: green; font-weight: bold;">
                <?php esc_html_e('Nomor WhatsApp berhasil diverifikasi!', 'text-domain'); ?>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                let timerInterval;
                let endTime;
            
                function formatTime(timeInSeconds) {
                    const minutes = Math.floor(timeInSeconds / 60);
                    const seconds = timeInSeconds % 60;
                    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            
                function showOtpField() {
                    const whatsappNumber = localStorage.getItem('whatsappNumber');
                    if (whatsappNumber) {
                        $('#whatsapp_number').val(whatsappNumber).prop('disabled', true);
                        $('#otp_field').show();
                        $('#send_otp_wa').prop('disabled', true).css('opacity', '0.5');
                        $('#timer_container').show();
                    }
                }
            
                function hideOtpField() {
                    $('#otp_field').hide();
                    $('#whatsapp_number').prop('disabled', false).val('');
                    $('#send_otp_wa').prop('disabled', false).css('opacity', '1');
                    $('#timer_container').hide();
                    localStorage.removeItem('whatsappNumber');
                    localStorage.removeItem('otpEndTime');
                }
            
                function startTimer(duration) {
                    clearInterval(timerInterval);
                    if (!endTime) {
                        endTime = Date.now() + (duration * 1000);
                        localStorage.setItem('otpEndTime', endTime);
                    }
                    showOtpField();
                    timerInterval = setInterval(() => {
                        const now = Date.now();
                        const timeLeft = Math.max(0, Math.ceil((endTime - now) / 1000));
                        $('#timer').text(formatTime(timeLeft));
                        if (timeLeft === 0) {
                            clearInterval(timerInterval);
                            hideOtpField();
                            endTime = null;
                        }
                    }, 1000);
                }
            
                const savedEndTime = localStorage.getItem('otpEndTime');
                if (savedEndTime) {
                    const timeLeft = Math.max(0, Math.ceil((parseInt(savedEndTime) - Date.now()) / 1000));
                    if (timeLeft > 0) {
                        endTime = parseInt(savedEndTime);
                        startTimer(timeLeft);
                    } else {
                        hideOtpField();
                    }
                }
            
                // ✅ Pastikan event tidak terpasang dua kali dengan `.off('click')`
                $('#send_otp_wa').off('click').on('click', function() {
                    const whatsappNumber = $('#whatsapp_number').val();
                    if (!whatsappNumber) {
                        alert('<?php esc_html_e("Harap masukkan nomor WhatsApp.", "text-domain"); ?>');
                        return;
                    }
            
                    if ($('#send_otp_wa').prop('disabled')) {
                        return;
                    }
            
                    // ✅ Nonaktifkan tombol untuk mencegah double click
                    $('#send_otp_wa').prop('disabled', true);
                    $('#send_otp_loading').show();
            
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'send_otp',
                        phone: whatsappNumber
                    }, function(response) {
                        $('#send_otp_loading').hide();
            
                        if (response.success) {
                            localStorage.setItem('whatsappNumber', whatsappNumber);
                            startTimer(300); // Mulai timer 5 menit
                            alert('<?php esc_html_e("OTP berhasil dikirim ke nomor Anda.", "text-domain"); ?>');
                        } else {
                            alert('<?php esc_html_e("Gagal mengirim OTP. Coba lagi.", "text-domain"); ?>');
                            $('#send_otp_wa').prop('disabled', false);
                        }
                    }).fail(function() {
                        alert('<?php esc_html_e("Terjadi kesalahan, silakan coba lagi.", "text-domain"); ?>');
                        $('#send_otp_wa').prop('disabled', false);
                        $('#send_otp_loading').hide();
                    });
                });
                
                $('#verify_otp_wa').on('click', function() {
                    const otp = $('#otp').val();
                    if (!otp) {
                        alert('<?php esc_html_e("Harap masukkan OTP.", "text-domain"); ?>');
                        return;
                    }

                    $('#otp').prop('disabled', true);
                    $('#verify_otp_wa').hide();
                    $('#verify_otp_loading').show();

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'verify_otp',
                            otp: otp,
                            phone: $('#whatsapp_number').val()
                        },
                        success: function(response) {
                            $('#verify_otp_loading').hide();
                            $('#verify_otp_wa').show();

                            if (response.success) {
                                const user_id = '<?php echo $_SESSION["whatsapp_user_id"] ?? $_GET['user_id'] ??''; ?>';

                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    method: 'POST',
                                    data: {
                                        action: 'update_whatsapp_number',
                                        user_id: user_id,
                                        whatsapp_number: $('#whatsapp_number').val(),
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            clearInterval(timerInterval);
                                            hideOtpField();
                                            alert('<?php esc_html_e("Nomor WhatsApp telah ditambahkan ke akun Anda", "text-domain"); ?>');
                                            window.location.href = '<?php echo esc_url(home_url('/my-account')); ?>';
                                        } else {
                                            alert(JSON.stringify(response.data.message));
                                            hideOtpField();
                                            $('#otp').prop('disabled', false);
                                        }
                                    }
                                });
                            } else {
                                alert('<?php esc_html_e("OTP tidak valid atau kadaluwarsa", "text-domain"); ?>');
                                $('#otp').prop('disabled', false);
                            }
                        }
                    });
                });
            });
        </script>
<?php
        return ob_get_clean();
    }
}
