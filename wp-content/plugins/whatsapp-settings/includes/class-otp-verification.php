<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WhatsApp_OTP_Verification
{

    private $api_url = 'https://tegal.wablas.com/api/send-message';
    private $authorization_key;
  	private $secret_key;
    private $message_template;

    public function __construct()
    {
        $this->authorization_key = get_option('whatsapp_api_key', '');
      	$this->secret_key = get_option('whatsapp_secret_key', '');
        $this->message_template = get_option('whatsapp_otp_message', 'Kode OTP Anda adalah: %otp%');
        // Register shortcode
        add_shortcode('whatsapp_otp_verification', [$this, 'render_otp_verification_form']);

        // Register AJAX actions for sending and verifying OTP
        add_action('wp_ajax_send_otp', [$this, 'ajax_send_otp']);
        add_action('wp_ajax_nopriv_send_otp', [$this, 'ajax_send_otp']);
        add_action('wp_ajax_verify_otp', [$this, 'ajax_verify_otp']);
        add_action('wp_ajax_nopriv_verify_otp', [$this, 'ajax_verify_otp']);
      	add_action('wp_ajax_generate_whatsapp_update_link', [$this,'generate_whatsapp_update_link']);
      	add_action('wp_ajax_nopriv_generate_whatsapp_update_link', [$this,'generate_whatsapp_update_link']);
      
    }

    public function generate_whatsapp_update_link() {
        // Pastikan pengguna sudah login
        if (!session_id()) {
            session_start();
            error_log('[OTP Verification] New session started');
        }

        $user_id = $_SESSION['otp_user_id'];
        $user = get_userdata($user_id);

        if (!$user) {
            wp_send_json_error(['message' => 'User tidak ditemukan.'], 404);
        }

        // Generate token unik
        $token = wp_generate_password(32, false);
        update_user_meta($user_id, '_whatsapp_change_token', $token);

        // Buat URL untuk update nomor WhatsApp
        $update_url = add_query_arg([
            'user_id' => $user_id,
            'token'   => $token
        ], site_url('/add-whatsapp-number/'));

        // Ambil logo dari WooCommerce
        $logo_url = get_theme_mod('custom_logo');
        $logo_img = $logo_url ? wp_get_attachment_image_src($logo_url, 'full')[0] : '';

        // Subject email
        $subject = "Pembaruan Nomor WhatsApp Anda";

        // Email dengan desain HTML
        $message = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }
                .container {
                    background: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    max-width: 500px;
                    margin: auto;
                }
                .logo {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .logo img {
                    max-width: 150px;
                }
                .title {
                    font-size: 18px;
                    font-weight: bold;
                    text-align: center;
                    color: #333;
                }
                .message {
                    font-size: 16px;
                    text-align: center;
                    color: #666;
                    margin: 20px 0;
                }
                .button {
                    display: block;
                    width: 100%;
                    text-align: center;
                    background: darkorange;
                    color: white;
                    padding: 12px;
                    border-radius: 5px;
                    text-decoration: none;
                    font-weight: bold;
                }
                .footer {
                    font-size: 12px;
                    text-align: center;
                    color: #999;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">
                    ' . ($logo_img ? '<img src="' . esc_url($logo_img) . '" alt="Website Logo">' : '') . '
                </div>
                <div class="title">Permintaan Perubahan Nomor WhatsApp</div>
                <div class="message">
                    Kami menerima permintaan untuk memperbarui nomor WhatsApp Anda.<br>
                    Klik tombol di bawah ini untuk melanjutkan proses perubahan.
                </div>
                <a href="' . esc_url($update_url) . '" class="button">Update No. WhatsApp</a>
                <div class="footer">
                    Jangan bagikan link ini kepada siapapun, termasuk pihak ShopBiz.<br>
                    Jika Anda tidak meminta perubahan ini, abaikan email ini.
                </div>
            </div>
        </body>
        </html>';

        // Headers untuk email HTML
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        // Kirim email
        wp_mail($user->user_email, $subject, $message, $headers);

        wp_send_json_success(['message' => 'Link pembaruan Nomor WhatsApp telah dikirim melalui email.']);
    }



    public function render_otp_verification_form()
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION['otp_user_id'])) {
            return '<p>' . esc_html__('Sesi telah kedaluwarsa. Silakan masuk lagi.', 'text-domain') . '</p>';
        }

        ob_start();
?>
        <form id="otp-verification-form" class="otp-form">
            <h2><?php esc_html_e('Verifikasi OTP', 'text-domain'); ?></h2>
            <p><?php esc_html_e('Masukkan OTP yang telah dikirimkan ke nomor WhatsApp Anda.', 'text-domain'); ?></p>

            <div class="otp-input-wrapper">
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-1" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-2" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-3" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-4" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-5" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
                <div class="otp-input-box">
                    <input type="tel" pattern="[0-9]{1}" maxlength="1" id="otp-6" class="otp-input" autocomplete="off" inputmode="numeric" />
                </div>
            </div>

            <button type="button" id="verify_otp_button" class="button"><?php esc_html_e('Verifikasi OTP', 'text-domain'); ?></button>
            <span id="otp_loading" style="display: none;"><?php esc_html_e('Memverifikasi...', 'text-domain'); ?></span>
			
            <div id="timer" style="margin-top: 15px;">
                <span id="countdown">05:00</span>
            </div>

            <button type="button" id="resend_otp" class="button" style="display: none;"><?php esc_html_e('Kirim Ulang OTP', 'text-domain'); ?></button>
        	<a href="#" id="request-whatsapp-change">Tidak memiliki akses ke no WhatsApp? klik disini</a>
			</form>

        <script type="text/javascript">
            jQuery(function($) {
              
                var otpInputs = $('.otp-input');
                var timerInterval;
                var endTime;

                // Format time as MM:SS
                function formatTime(timeInSeconds) {
                    const minutes = Math.floor(timeInSeconds / 60);
                    const seconds = timeInSeconds % 60;
                    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                // Start or resume timer
                function startTimer(duration) {
                    clearInterval(timerInterval);

                    if (!endTime) {
                        endTime = Date.now() + (duration * 1000);
                        localStorage.setItem('otpEndTime', endTime);
                    }

                    $('#resend_otp').hide();

                    timerInterval = setInterval(() => {
                        const now = Date.now();
                        const timeLeft = Math.max(0, Math.ceil((endTime - now) / 1000));

                        $('#countdown').text(formatTime(timeLeft));

                        if (timeLeft === 0) {
                            clearInterval(timerInterval);
                            $('#resend_otp').show();
                            endTime = null;
                            localStorage.removeItem('otpEndTime');
                        }
                    }, 1000);
                }

                // Check for existing timer on page load
                const savedEndTime = localStorage.getItem('otpEndTime');
                if (savedEndTime) {
                    const timeLeft = Math.max(0, Math.ceil((parseInt(savedEndTime) - Date.now()) / 1000));
                    if (timeLeft > 0) {
                        endTime = parseInt(savedEndTime);
                        startTimer(timeLeft);
                    } else {
                        $('#resend_otp').show();
                        $('#countdown').text('00:00');
                        localStorage.removeItem('otpEndTime');
                    }
                } else {
                    startTimer(300); // Start 5-minute timer on initial load
                }

                // Handle OTP input navigation
                otpInputs.on('input', function() {
                    var inputValue = $(this).val();
                    if (inputValue.length > 1) {
                        $(this).val(inputValue.slice(0, 1));
                    }

                    var index = otpInputs.index(this);
                    if (inputValue.length > 0 && index < otpInputs.length - 1) {
                        otpInputs.eq(index + 1).focus();
                    } else if (inputValue.length === 0 && index > 0) {
                        otpInputs.eq(index - 1).focus();
                    }
                });

                // Handle keyboard navigation for OTP inputs
                otpInputs.on('keydown', function(e) {
                    var index = otpInputs.index(this);

                    if (e.key === 'Backspace' && $(this).val() === '' && index > 0) {
                        e.preventDefault();
                        otpInputs.eq(index - 1).focus().val('');
                    }
                });

                // Verify OTP
                $('#verify_otp_button').on('click', function() {
                    var otp = '';
                    otpInputs.each(function() {
                        otp += $(this).val();
                    });

                    if (otp.length < 6) {
                        alert('<?php esc_html_e("Harap masukkan OTP lengkap.", "text-domain"); ?>');
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
                                clearInterval(timerInterval);
                                localStorage.removeItem('otpEndTime');
                                alert('<?php esc_html_e("OTP berhasil diverifikasi.", "text-domain"); ?>');
                                window.location.href = '<?php echo esc_url(home_url('/my-account')); ?>';
                            } else {
                                alert(response.data.message);
                            }
                        }
                    });
                });

                // Handle Resend OTP
                $('#resend_otp').on('click', function() {
                    var phone = '<?php echo get_user_meta($_SESSION["otp_user_id"], "_whatsapp_number", true); ?>';
                    $(this).prop('disabled', true);

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'send_otp',
                            phone: phone
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('<?php esc_html_e("OTP berhasil dikirim ulang.", "text-domain"); ?>');
                                endTime = null;
                                startTimer(300); 

                                // Clear OTP inputs
                                otpInputs.val('');
                                otpInputs.first().focus();
                            } else {
                                alert(response.data.message);
                                $('#resend_otp').prop('disabled', false);
                            }
                        }
                    });
                });
              
              	$('#request-whatsapp-change').on('click', function(e) {
                    e.preventDefault();

                    var $btn = $(this); // Simpan referensi ke tombol

                    // Nonaktifkan tombol agar tidak bisa diklik lagi
                    $btn.prop('disabled', true).addClass('disabled').text('Mengirim tautan...');

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX handler
                        type: 'POST',
                        data: {
                            action: 'generate_whatsapp_update_link'
                        },
                        success: function(response) {
                            alert(response.data.message);
                            $btn.text('Permintaan Ganti Nomor telah dikirim'); // Ubah teks tombol
                        },
                        error: function(response) {
                            alert('Error: ' + response.responseJSON.data.message);
                            $btn.prop('disabled', false).removeClass('disabled').text('Kirim ulang permintaan gati nomor WhatsApp'); // Aktifkan kembali jika error
                        }
                    });
                });

                // Clean up on page unload
                $(window).on('unload', function() {
                    if (endTime) {
                        localStorage.setItem('otpEndTime', endTime);
                    }
                });
            });
        </script>

        <style>
            .otp-form {
                display: flex;
                flex-direction: column;
                align-items: center;
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                background-color: #f9f9f9;
            }

            .otp-input-wrapper {
                display: flex;
                gap: 10px;
                justify-content: center;
                margin-bottom: 20px;
                width: 100%;
            }

            .otp-input-box {
                width: 50px;
                height: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #fff;
                border-radius: 5px;
            }

            .otp-input {
                width: 100%;
                height: 100%;
                text-align: center;
                font-size: 24px !important;
                font-weight: bold;
                border: 1px solid #ccc;
                border-radius: 5px;
                outline: none;
                background: transparent;
            }

            .button {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                text-align: center;
                display: inline-block;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                border: none;
                margin: 10px 0;
                transition: background-color 0.3s;
            }

            .button:hover {
                background-color: #45a049;
            }

            .button:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }

            #timer {
                font-size: 16px;
                color: #333;
                font-weight: bold;
            }

            @media (max-width: 600px) {
                .otp-input-wrapper {
                    gap: 8px;
                }

                .otp-input-box {
                    width: 45px;
                    height: 45px;
                }

                .otp-input {
                    font-size: 20px !important;
                }
            }
        </style>
<?php
        return ob_get_clean();
    }

    public function ajax_send_otp()
    {
        // Check if phone number is provided
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        if (empty($phone)) {
            wp_send_json_error(['message' => 'Phone number is required.']);
        }
        $phone = normalize_whatsapp_number($phone);
        $otp_sent = $this->send_otp($phone);
        if ($otp_sent) {
            wp_send_json_success(['message' => 'OTP sent successfully.']);
        } else {
            wp_send_json_error(['message' => 'Failed to send OTP. Please try again.']);
        }
    }

    public function ajax_verify_otp()
    {
        // Check if phone and OTP are provided
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $otp = isset($_POST['otp']) ? sanitize_text_field($_POST['otp']) : '';
        
        if (empty($phone) || empty($otp)) {
            wp_send_json_error(['message' => 'Phone number and OTP are required.']);
        }
        $phone = normalize_whatsapp_number($phone);
        // Verify the OTP
        $otp_verified = $this->verify_otp($phone, $otp);
        if ($otp_verified) {
            wp_send_json_success(['message' => 'OTP verified successfully.']);
        } else {
            wp_send_json_error(['message' => 'Invalid or expired OTP.']);
        }
    }

    public function send_pln_token($phone, $token)
    {
        if (empty($this->authorization_key)) {
            return false; // API key is not set.
        }
    
        if (empty($token)) {
            return false; // Token is required.
        }
    
        // Ambil template dari pengaturan admin, gunakan default jika kosong
        $template = get_option('ppob_pln_token_message_template', 'Terima kasih, token PLN Anda: %token%');
        $message = str_replace('%token%', $token, $template);
    
        // Normalisasi nomor WhatsApp
        $wa = normalize_whatsapp_number($phone);
    
        // Kirim request ke API WhatsApp
        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'Authorization' => $this->authorization_key . '.' . $this->secret_key,
            ],
            'body' => [
                'phone'   => $wa,
                'message' => $message,
            ],
        ]);
    
        if (is_wp_error($response)) {
            return false; // Gagal mengirim
        }
    
        return true; // Berhasil mengirim
    }

    /**
     * Send OTP to the provided phone number
     */
    public function send_otp($phone)
    {
        if (empty($this->authorization_key)) {
            return false; // API key is not set.
        }

        // Generate a 6-digit OTP
        $otp = 123456;//rand(100000, 999999);

        // Message to send
        $message = str_replace('%otp%', $otp, $this->message_template);
        $wa = normalize_whatsapp_number($phone);
        // Send request to API
        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'Authorization' => $this->authorization_key.'.'.$this->secret_key,
            ],
            'body' => [
                'phone'   => $wa,
                'message' => $message,
            ],
        ]);

        if (is_wp_error($response)) {
            return false; // Request failed.
        }

        // Hash the OTP before storing
        $hashed_otp = password_hash($otp, PASSWORD_DEFAULT);
        
        // Store the hashed OTP in the database
        $this->store_otp($wa, $hashed_otp);

        return true;
    }

    /**
     * Store the hashed OTP in the database
     */
    private function store_otp($phone, $hashed_otp)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'whatsapp_verification';

        // Use current_time() to ensure WordPress timezone is respected
        $current_time = current_time('mysql',false);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($current_time))); // OTP expires in 5 minutes.

        $wpdb->insert(
            $table_name,
            [
                'phone_number' => $phone,
                'otp'          => $hashed_otp,
                'status'       => 'pending',
                'created_at'   => $current_time,
                'expires_at'   => $expires_at,
            ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );
    }

    /**
     * Verify OTP
     */
    public function verify_otp($phone, $otp)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'whatsapp_verification';
        $current_time = current_time('mysql'); // Use WordPress timezone for current time
        $otp_status =  'pending';
        $wa= normalize_whatsapp_number($phone);
        // Retrieve the hashed OTP from the database
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT `id`, `otp` 
             FROM `$table_name` 
             WHERE `phone_number` = %s 
             AND `expires_at` > %s 
             AND `status` = 'pending' 
             ORDER BY `created_at` DESC 
             LIMIT 1",
                $wa,
                $current_time
            )
        );

        if ($result && password_verify($otp, $result->otp)) {
            // Mark OTP as verified
            $wpdb->update(
                $table_name,
                ['status' => 'verified'],
                ['id' => $result->id],
                ['%s'],
                ['%d']
            );
            return true; // OTP is valid.
        }

        return false; // OTP is invalid or expired.
    }

    /**
     * Reverify OTP
     *
     * Fungsi ini mengecek ulang OTP yang sudah memiliki status "verified" dan menghapus baris OTP setelah diverifikasi.
     *
     * @param string $phone Nomor telepon pengguna.
     * @param string $otp Kode OTP yang akan diverifikasi ulang.
     * @return bool True jika OTP valid dan cocok, false jika tidak valid.
     */
    public function reverify_otp($phone, $otp)
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'whatsapp_verification';
        $current_time = current_time('mysql'); // Menggunakan timezone WordPress.

        // Ambil data OTP dari database dengan status "verified"
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT `id`, `otp` 
                 FROM `$table_name` 
                 WHERE `phone_number` = %s 
                 AND `status` = 'verified' 
                 AND `expires_at` > %s 
                 ORDER BY `created_at` DESC 
                 LIMIT 1",
                $phone,
                $current_time
            )
        );

        // Jika data ditemukan dan OTP cocok
        if ($result && password_verify($otp, $result->otp)) {
            // Hapus baris OTP dari database
            $wpdb->delete(
                $table_name,
                ['id' => $result->id], // Kondisi penghapusan berdasarkan ID
                ['%d'] // Format ID sebagai integer
            );

            return true; // OTP valid
        }

        return false; // OTP tidak valid atau kedaluwarsa
    }
}
