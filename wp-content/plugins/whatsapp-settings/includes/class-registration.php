<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WhatsApp_Registration
{

    public function __construct()
    {
        add_action('woocommerce_register_form', [$this, 'add_custom_register_fields'], 1);
        add_action('woocommerce_register_post', [$this, 'validate_whatsapp_field'], 10, 3);
        add_action('user_register', [$this, 'handle_wp_affiliate'], 20);
        add_action('woocommerce_created_customer', [$this, 'save_whatsapp_field'], 1);
      	add_action('wp_footer', [$this,'enqueue_scripts']);
    }

    // Add custom fields in the registration form
    public function add_custom_register_fields()
    {
?>
        <!-- WhatsApp Number -->
        
        <div class="form-row form-row-wide">
            <label for="whatsapp_number"><?php esc_html_e('Nomor WhatsApp', 'text-domain'); ?> <span class="required">*</span></label>
            <div class="whatsapp-container" style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 5px; padding: 5px;">
                <span style="margin-right: 10px; font-size: 16px; font-weight: bold;">+62</span>
                <input style="flex: 1; border: none; outline: none;" 
                       type="number" 
                       class="input-text whatsapp-input" 
                       name="whatsapp_number" 
                       id="whatsapp_number" 
                       value="<?php 
                           if (!empty($_POST['whatsapp_number'])) {
                               echo esc_attr(ltrim($_POST['whatsapp_number'], '62')); // Remove '62' if entered manually
                           } 
                       ?>" 
                       required 
                       placeholder="8123xxxxxxx" />
                <button type="button" id="send_otp" class="button otp-button" style="margin-left: 10px;"><?php esc_html_e('Kirim OTP', 'text-domain'); ?></button>
            </div>
            <span id="send_otp_loading" class="otp-loading" style="display: none;">Mengirim Kode...</span>

        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const whatsappInput = document.getElementById("whatsapp_number");
            
                whatsappInput.addEventListener("input", function () {
                    let value = whatsappInput.value;
            
                    // Remove any non-numeric characters
                    value = value.replace(/[^0-9]/g, "");
            
                    // If the input starts with +62, 62, or 0, remove those prefixes
                    if (value.startsWith("+62")) {
                        value = value.substring(3); // Remove +62
                    } else if (value.startsWith("62")) {
                        value = value.substring(2); // Remove 62
                    } else if (value.startsWith("0")) {
                        value = value.substring(1); // Remove 0
                    }
            
                    // Ensure the input starts with 8 and has a maximum length of 15 digits
                    if (value.length > 0 && !value.startsWith("8")) {
                        value = ""; // Clear the input if it doesn't start with 8
                    }
            
                    // Limit the length to 15 digits (62 + 8xxxxxxx)
                    if (value.length > 15) {
                        value = value.substring(0, 15);
                    }
            
                    // Update the input value
                    whatsappInput.value = value;
                });
            });
        </script>

        <!-- OTP Input -->
        <div class="form-row form-row-wide" id="otp_field" style="display: none;">
            <label for="otp"><?php esc_html_e('OTP', 'text-domain'); ?> <span class="required">*</span></label>
            <div class="otp-container" style="display:flex;">
                <input type="number" style="margin-right: 20px; flex:1; display:inline-block;" class="input-text otp-input" name="otp" id="otp" required placeholder="Enter OTP" />
                <button type="button" id="verify_otp" class="button otp-button"><?php esc_html_e('Verifikasi OTP', 'text-domain'); ?></button>
                <span id="verify_otp_loading" class="otp-loading" style="display: none;">Memverifikasi...</span>
            </div>
        </div>

        <!-- Sponsor Field -->
        <div class="form-row form-row-wide">
            <label for="sponsor"><?php esc_html_e('Sponsor (Opsional)', 'text-domain'); ?></label>
            <?php
            $sponsor_value = ''; // Default value.
            if (defined('CB_SPONSOR')) {
                $datasponsor = unserialize(CB_SPONSOR);
                $sponsor_value = isset($datasponsor['subdomain']) ? esc_attr($datasponsor['subdomain']) : '';
            }
            ?>
            <input type="text" class="input-text" name="sponsor" id="sponsor" value="<?php echo $sponsor_value; ?>" />
        </div>
    <?php
        // Enqueue custom script for OTP and form validation

    }

    // Enqueue custom scripts for sending OTP and handling OTP validation
    public function enqueue_scripts()
    {
    ?>
        <script type="text/javascript">
            jQuery(function($) {
                // Disable the WooCommerce Register button initially
                $('.woocommerce-form-register__submit').prop('disabled', true);

                // Handle Send OTP button click
                $('#send_otp').on('click', function() {
                    var whatsappNumber = $('#whatsapp_number').val();
                    if (whatsappNumber === '') {
                        alert('<?php esc_html_e("Harap masukkan nomor WhatApp.", "text-domain"); ?>');
                        return;
                    }

                    // Show loading indicator
                    $('#send_otp').hide();
                    $('#send_otp_loading').show();

                    // Send OTP via AJAX
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        method: 'POST',
                        data: {
                            action: 'send_otp',
                            phone: whatsappNumber
                        },
                        success: function(response) {
                            // Hide loading indicator
                            $('#send_otp_loading').hide();
                            $('#send_otp').show();

                            if (response.success) {
                                alert('<?php esc_html_e("OTP sent successfully.", "text-domain"); ?>');
                                $('#otp_field').show();
                                startResendTimer();
                            } else {
                                alert('<?php esc_html_e("Failed to send OTP. Please try again.", "text-domain"); ?>');
                            }
                        }
                    });
                });

                // Handle Verify OTP button click
                $('#verify_otp').on('click', function() {
                    var otp = $('#otp').val();
                    if (otp === '') {
                        alert('<?php esc_html_e("Please enter the OTP.", "text-domain"); ?>');
                        return;
                    }

                    // Show loading indicator
                    $('#verify_otp').hide();
                    $('#verify_otp_loading').show();

                    // Verify OTP via AJAX
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                      
                        method: 'POST',
                        data: {
                            action: 'verify_otp',
                            otp: otp,
                            phone: $('#whatsapp_number').val()
                        },
                        success: function(response) {
                            // Hide loading indicator
                            $('#verify_otp_loading').hide();
                            $('#verify_otp').show();

                            if (response.success) {
                                alert('<?php esc_html_e("OTP verified successfully.", "text-domain"); ?>');
                                // Enable WooCommerce Register button after OTP verification
                                $('.woocommerce-form-register__submit').prop('disabled', false);
                                $('#verify_otp').hide();
                            } else {
                                alert('<?php esc_html_e("Invalid OTP. Please try again.", "text-domain"); ?>');
                            }
                        }
                    });
                });

                // Timer for Resend OTP button
                function startResendTimer() {
                    var timer = 300;
                    var timerInterval = setInterval(function() {
                        if (timer > 0) {
                            $('#send_otp').text('<?php esc_html_e("Resend OTP in", "text-domain"); ?> ' + timer + 's');
                            timer--;
                        } else {
                            clearInterval(timerInterval);
                            $('#send_otp').text('<?php esc_html_e("Resend OTP", "text-domain"); ?>');
                            $('#send_otp').prop('disabled', false);
                        }
                    }, 1000);
                    $('#send_otp').prop('disabled', true);
                }
            });
        </script>
<?php
    }

    // Validate WhatsApp number and OTP
    public function validate_whatsapp_field($username, $email, $validation_errors)
    {
        global $wpdb;
        // Validasi nomor WhatsApp
        if (empty($_POST['whatsapp_number'])) {
            $validation_errors->add('whatsapp_number_error', __('Nomor WhatsApp harus diisi.', 'text-domain'));
            return $validation_errors;
        } elseif (is_whatsapp_number_used($_POST['whatsapp_number'])){
            $validation_errors->add('whatsapp_number_error', __('Nomor WhatApp telah digunakan oleh akun lain','text-domain'));
            return $validation_errors;
        } else {
            $number = ltrim($_POST['whatsapp_number'], '0'); // Remove leading zero if any
            if (!is_numeric($number) || strlen($number) < 10) {
                $validation_errors->add('whatsapp_number_error', __('Nomor WhatsApp tidak valid.', 'text-domain'));
                return $validation_errors;
            }
        }

        $whatsapp_number = sanitize_text_field($_POST['whatsapp_number']);

        // Validasi OTP
        if (empty($_POST['otp'])) {
            $validation_errors->add('otp_error', __('Kode OTP harus diisi.', 'text-domain'));
            return $validation_errors; 
        }

        $otp = sanitize_text_field($_POST['otp']);

        // Cek ulang OTP
        if (class_exists('WhatsApp_OTP_Verification')) {
            $whatsapp_otp = new WhatsApp_OTP_Verification();
            $verify = $whatsapp_otp->reverify_otp(normalize_whatsapp_number($whatsapp_number), $otp);

            if (!$verify) {
                $validation_errors->add('otp_error', __('Kode OTP tidak valid atau telah kedaluwarsa.', 'text-domain'));
            }
        } else {
            // Kelas tidak ditemukan, tambahkan pesan error
            $validation_errors->add('otp_error', __('Sistem tidak dapat memverifikasi OTP. Silakan coba lagi.', 'text-domain'));
        }

        //cek sponsor
        if (isset($_POST['sponsor']) && $_POST['sponsor'] != '') {
            $id_referral = $wpdb->get_var("SELECT `idwp` FROM `wp_member` WHERE `subdomain`= '" . sanitize_text_field($_POST['sponsor']) . "'");

            if (is_numeric($id_referral) && $id_referral > 0) {
                //ok
            } else {
                $validation_errors->add('sponsor_error', __('Kode Sponsor tidak ditemukan', 'text-domain'));
            }
        }

        if (empty($_POST['username'])) {
            $validation_errors->add('username_error', __('Username wajib diisi', 'woocommerce'));
        } elseif ( strpos( $_POST['username'], ' ' ) !== false ) {
       		 $validation_errors->add( 'username_ada_spasi', __( 'Username tidak boleh mengandung spasi.', 'woocommerce' ) );
    	} elseif (username_exists($_POST['username'])) {
            $validation_errors->add('username_error', __('Username telah digunakan', 'woocommerce'));
        }

        return $validation_errors;
    }


    public function save_whatsapp_field($customer_id)
    {
        if (!empty($_POST['whatsapp_number'])) {
            update_user_meta($customer_id, '_whatsapp_number', sanitize_text_field("62".$_POST['whatsapp_number']));
            update_user_meta($customer_id, 'billing_phone', sanitize_text_field("62".$_POST['whatsapp_number']));
        }
    }

    public function handle_wp_affiliate($customer_id)
    {
        global $wpdb;
        
        // Ambil nama depan dan belakang dari POST dan sanitasi input
        $firstname = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $lastname = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $name = $firstname . ' ' . $lastname;
        
        // Pastikan tindakan tambahan user_register terdaftar ulang
        remove_action('user_register', 'cbaff_adduser');
        add_action('user_register', 'cbaff_adduser', 20);
        
        // Update nama pengguna di tabel wp_member
        $wpdb->query(
            $wpdb->prepare(
                'UPDATE `wp_member` SET `nama` = %s WHERE `idwp` = %d',
                $name,
                $customer_id
            )
        );
        
        // Kirim notifikasi jika fungsi cb_notif ada
        if (function_exists('cb_notif')) {
            $id_user = $wpdb->get_var(
                $wpdb->prepare(
                    'SELECT id_user FROM wp_member WHERE idwp = %d',
                    $customer_id
                )
            );
    
            if ($id_user) {
                cb_notif($id_user, 'registrasi'); // Kirim notifikasi
            }
        }
    }

}
?>