<?php
/**
 * WhatsApp Registration API Main Class
 * MODIFIED: Jika user sudah ada (berdasarkan Email ATAU WhatsApp), 
 * hanya tambah YITH points 500rb, tanpa update data
 * PRIORITAS: Cek Email dulu, baru WhatsApp
 */

if (!defined('ABSPATH')) {
    exit;
}

class WhatsApp_Registration_API
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function run()
    {
        // Register API endpoints
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    /**
     * Register API routes
     */
    public function register_api_routes()
    {
        // Register/Points endpoint
        register_rest_route('whatsapp-registration/v1', '/register', [
            'methods' => 'POST',
            'callback' => [$this, 'api_register_or_add_points'],
            'permission_callback' => '__return_true',
            'args' => [
                'username' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_username_for_new']
                ],
                'email' => [
                    'required' => true,
                    'validate_callback' => 'is_email'
                ],
                'password' => [
                    'required' => false
                ],
                'whatsapp_number' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_whatsapp_number']
                ],
                'otp' => [
                    'required' => true
                ],
                'first_name' => [
                    'required' => false
                ],
                'last_name' => [
                    'required' => false
                ],
                'sponsor' => [
                    'required' => false,
                    'validate_callback' => [$this, 'validate_sponsor']
                ]
            ]
        ]);

        // Send OTP endpoint
        register_rest_route('whatsapp-registration/v1', '/send-otp', [
            'methods' => 'POST',
            'callback' => [$this, 'api_send_otp'],
            'permission_callback' => '__return_true',
            'args' => [
                'phone' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_whatsapp_number']
                ]
            ]
        ]);

        // Verify OTP endpoint
        register_rest_route('whatsapp-registration/v1', '/verify-otp', [
            'methods' => 'POST',
            'callback' => [$this, 'api_verify_otp'],
            'permission_callback' => '__return_true',
            'args' => [
                'phone' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_whatsapp_number']
                ],
                'otp' => [
                    'required' => true
                ]
            ]
        ]);

        // Check username availability
        register_rest_route('whatsapp-registration/v1', '/check-username', [
            'methods' => 'POST',
            'callback' => [$this, 'api_check_username'],
            'permission_callback' => '__return_true',
            'args' => [
                'username' => [
                    'required' => true
                ]
            ]
        ]);

        // Check WhatsApp number and get user data
        register_rest_route('whatsapp-registration/v1', '/check-whatsapp', [
            'methods' => 'POST',
            'callback' => [$this, 'api_check_whatsapp'],
            'permission_callback' => '__return_true',
            'args' => [
                'whatsapp_number' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_whatsapp_number']
                ]
            ]
        ]);

        // Get user by WhatsApp number
        register_rest_route('whatsapp-registration/v1', '/get-user-by-whatsapp', [
            'methods' => 'POST',
            'callback' => [$this, 'api_get_user_by_whatsapp'],
            'permission_callback' => '__return_true',
            'args' => [
                'whatsapp_number' => [
                    'required' => true,
                    'validate_callback' => [$this, 'validate_whatsapp_number']
                ]
            ]
        ]);

        // Check Email and get user data
        register_rest_route('whatsapp-registration/v1', '/check-email', [
            'methods' => 'POST',
            'callback' => [$this, 'api_check_email'],
            'permission_callback' => '__return_true',
            'args' => [
                'email' => [
                    'required' => true,
                    'validate_callback' => 'is_email'
                ]
            ]
        ]);
    }

    /**
     * API Register or Add Points Only
     * MODIFIED: Cek berdasarkan Email dulu, baru WhatsApp
     */
    public function api_register_or_add_points($request)
    {
        $params = $request->get_json_params();
        
        try {
            // Check if user exists based on EMAIL first, then WhatsApp
            $existing_user_id = $this->get_existing_user_id_by_email_first($params);
            
            if ($existing_user_id) {
                // User SUDAH ADA (via Email atau WhatsApp) → ONLY ADD POINTS
                return $this->add_points_only($existing_user_id, $params);
            } else {
                // User BARU → Register new user
                return $this->register_new_user($params);
            }

        } catch (Exception $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get existing user ID by EMAIL first, then WhatsApp
     * PRIORITAS: Email dulu, baru WhatsApp
     * 
     * @param array $params Request parameters
     * @return int|null User ID if exists, null otherwise
     */
    private function get_existing_user_id_by_email_first($params)
    {
        // PRIORITAS 1: Cek berdasarkan Email
        $user_by_email = get_user_by('email', sanitize_email($params['email']));
        if ($user_by_email) {
            error_log('User found by Email (priority): ' . $user_by_email->ID);
            
            // Update WhatsApp number untuk user yang ditemukan via email
            $full_whatsapp = "62" . ltrim($params['whatsapp_number'], '0');
            update_user_meta($user_by_email->ID, '_whatsapp_number', $full_whatsapp);
            update_user_meta($user_by_email->ID, 'billing_phone', $full_whatsapp);
            
            return $user_by_email->ID;
        }
        
        // PRIORITAS 2: Cek berdasarkan WhatsApp number (jika tidak ditemukan via email)
        $whatsapp_number = "62" . ltrim($params['whatsapp_number'], '0');
        $user_by_whatsapp = get_users([
            'meta_key' => '_whatsapp_number',
            'meta_value' => $whatsapp_number,
            'number' => 1,
            'fields' => 'ID'
        ]);
        
        if (!empty($user_by_whatsapp)) {
            error_log('User found by WhatsApp (secondary): ' . $user_by_whatsapp[0]);
            return $user_by_whatsapp[0];
        }
        
        return null;
    }

    /**
     * Register new user
     */
    private function register_new_user($params)
    {
        // Validate all inputs for new user (tanpa validasi email exists)
        $validation_errors = $this->validate_new_user_data_no_email_check($params);
        
        if (!empty($validation_errors)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation_errors
            ], 400);
        }

        // Prepare user data
        $userdata = [
            'user_login' => sanitize_user($params['username']),
            'user_email' => sanitize_email($params['email']),
            'user_pass' => $params['password'],
            'first_name' => isset($params['first_name']) ? sanitize_text_field($params['first_name']) : '',
            'last_name' => isset($params['last_name']) ? sanitize_text_field($params['last_name']) : '',
            'display_name' => trim(
                (isset($params['first_name']) ? $params['first_name'] : '') . ' ' . 
                (isset($params['last_name']) ? $params['last_name'] : '')
            )
        ];

        // Create user
        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Failed to create user',
                'errors' => $user_id->get_error_messages()
            ], 500);
        }

        // Save WhatsApp number
        $full_whatsapp = "62" . ltrim($params['whatsapp_number'], '0');
        update_user_meta($user_id, '_whatsapp_number', $full_whatsapp);
        update_user_meta($user_id, 'billing_phone', $full_whatsapp);

        // Save sponsor if provided
        if (!empty($params['sponsor'])) {
            update_user_meta($user_id, 'sponsor', sanitize_text_field($params['sponsor']));
        }

        // Call handle_wp_affiliate function
        $this->call_wp_affiliate_handler($user_id, $params);

        // GIVE YITH POINTS - 500,000 points for new registration
        $points_given = $this->give_yith_points($user_id, 500000);
        
        if ($points_given) {
            $this->mark_registration_points_received($user_id);
            error_log('Successfully gave 500,000 YITH points to new user: ' . $user_id);
        } else {
            error_log('Failed to give YITH points to new user: ' . $user_id);
        }

        // Auto login after registration
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        return new WP_REST_Response([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user_id_wp' => $user_id,
                'user_id' => $user_id,
                'username' => $params['username'],
                'email' => $params['email'],
                'action' => 'registered',
                'points_awarded' => $points_given ? 500000 : 0,
                'found_by' => 'new_registration'
            ]
        ], 201);
    }

    /**
     * ADD POINTS ONLY for existing user
     */
    private function add_points_only($user_id, $params)
    {
        // Get existing user data (for response only)
        $user = get_user_by('ID', $user_id);
        
        if (!$user) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // VALIDASI OTP - tetap wajib
        if (empty($params['otp'])) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => ['otp' => 'Kode OTP wajib diisi']
            ], 400);
        }

        // CHECK IF USER ALREADY RECEIVED REGISTRATION POINTS
        $points_awarded = 0;
        
        if (!$this->has_received_registration_points($user_id)) {
            // Give YITH points only if belum pernah dapat
            $points_given = $this->give_yith_points($user_id, 0);
            
            if ($points_given) {
                $this->mark_registration_points_received($user_id);
                $points_awarded = 0;
                error_log('Successfully gave 500,000 YITH points to existing user: ' . $user_id);
            } else {
                error_log('Failed to give YITH points to existing user: ' . $user_id);
            }
        } else {
            error_log('User ' . $user_id . ' already received registration points, skipping');
        }

        // Tentukan bagaimana user ditemukan
        $found_by = 'whatsapp'; // default
        if ($user->user_email === sanitize_email($params['email'])) {
            $found_by = 'email';
        }
        
        // Login otomatis
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        return new WP_REST_Response([
            'success' => true,
            'message' => $points_awarded > 0 ? 'Points added successfully' : 'User already received points',
            'data' => [
                'user_id_wp' => $user_id,
                'user_id' => $user_id,
                'username' => $user->user_login,        // Data existing
                'email' => $user->user_email,           // Data existing
                'first_name' => $user->first_name,      // Data existing
                'last_name' => $user->last_name,        // Data existing
                'action' => 'points_only',
                'points_awarded' => $points_awarded,
                'found_by' => $found_by,
                'message_detail' => $points_awarded > 0 ? 
                    'Sudah Terhubung dengan Shopbiz.id' : 
                    'Anda sudah terhubung dengan Shopbiz.id'
            ]
        ], 200);
    }

    /**
     * Validate new user data WITHOUT email exists check
     */
    private function validate_new_user_data_no_email_check($data)
    {
        $errors = [];

        // Username validation
        if (empty($data['username'])) {
            $errors['username'] = 'Username wajib diisi';
        } elseif (strpos($data['username'], ' ') !== false) {
            $errors['username'] = 'Username tidak boleh mengandung spasi';
        } elseif (username_exists($data['username'])) {
            $errors['username'] = 'Username telah digunakan';
        }

        // Email validation - hanya validitas format, bukan cek exist
        if (empty($data['email'])) {
            $errors['email'] = 'Email wajib diisi';
        } elseif (!is_email($data['email'])) {
            $errors['email'] = 'Email tidak valid';
        }
        // TIDAK ADA pengecekan email_exists()

        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = 'Password wajib diisi';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Password minimal 6 karakter';
        }

        // WhatsApp validation
        if (empty($data['whatsapp_number'])) {
            $errors['whatsapp_number'] = 'Nomor WhatsApp wajib diisi';
        } elseif ($this->is_whatsapp_number_used($data['whatsapp_number'])) {
            // Tetap cek WhatsApp karena ini unique identifier
            $errors['whatsapp_number'] = 'Nomor WhatsApp telah digunakan oleh akun lain';
        }

        // OTP validation
        if (empty($data['otp'])) {
            $errors['otp'] = 'Kode OTP wajib diisi';
        }

        // Sponsor validation
        if (!empty($data['sponsor']) && !$this->validate_sponsor($data['sponsor'], null, null)) {
            $errors['sponsor'] = 'Kode Sponsor tidak ditemukan';
        }

        return $errors;
    }

    /**
     * API Check Email
     */
    public function api_check_email($request)
    {
        $params = $request->get_json_params();
        $email = sanitize_email($params['email']);

        $user = get_user_by('email', $email);
        $user_data = null;

        if ($user) {
            $user_data = [
                'user_id_wp' => $user->ID,
                'user_id' => $user->ID,
                'username' => $user->user_login,
                'email' => $user->user_email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'display_name' => $user->display_name,
                'has_received_points' => $this->has_received_registration_points($user->ID)
            ];
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'email' => $email,
                'exists' => (bool)$user,
                'user' => $user_data
            ]
        ], 200);
    }

    /**
     * Check if WhatsApp number is already used
     */
    private function is_whatsapp_number_used($number)
    {
        $number = "62" . ltrim($number, '0');
        
        $users = get_users([
            'meta_key' => '_whatsapp_number',
            'meta_value' => $number,
            'number' => 1,
            'fields' => 'ID'
        ]);

        return !empty($users);
    }

    /**
     * Give YITH points to user
     */
    private function give_yith_points($user_id, $points = 500000)
    {
        // Check if YITH Points and Rewards is active
        if (!class_exists('YITH_WC_Points_Rewards') && !function_exists('ywpar_get_customer')) {
            error_log('YITH Points and Rewards plugin not active');
            return false;
        }

        try {
            // Get customer points object
            $customer = ywpar_get_customer($user_id);
            
            if (!$customer) {
                error_log('Could not get YITH customer for user ID: ' . $user_id);
                return false;
            }

            // Get current points
            $current_points = $customer->get_total_points();
            error_log('Current points for user ' . $user_id . ': ' . $current_points);

            // Calculate new points total
            $new_points = $current_points + $points;
            
            // Set new points total
            $customer->set_total_points($new_points);
            $customer->save();

            // Log the points addition
            $this->log_yith_points_transaction($user_id, $points);

            error_log('Successfully gave ' . $points . ' points to user ' . $user_id . '. New total: ' . $new_points);
            
            return true;

        } catch (Exception $e) {
            error_log('Error giving YITH points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Log YITH points transaction
     */
    private function log_yith_points_transaction($user_id, $points)
    {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'yith_ywpar_points_log';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'amount' => $points,
                    'action' => 'whatsapp_registration_bonus',
                    'date_earning' => current_time('mysql'),
                    'description' => 'Bonus 500.000 points for WhatsApp registration'
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s'
                )
            );
        }
    }

    /**
     * Check if user already received registration points
     */
    private function has_received_registration_points($user_id)
    {
        $received = get_user_meta($user_id, '_wha_received_registration_points', true);
        return !empty($received);
    }

    /**
     * Mark user as received registration points
     */
    private function mark_registration_points_received($user_id)
    {
        update_user_meta($user_id, '_wha_received_registration_points', current_time('mysql'));
    }

    /**
     * API Send OTP
     */
    public function api_send_otp($request)
    {
        $params = $request->get_json_params();
        $phone = ltrim($params['phone'], '0');

        return new WP_REST_Response([
            'success' => true,
            'message' => 'OTP sent successfully (simulated)',
            'data' => [
                'phone' => $phone,
                'expires_in' => 300,
                'otp' => '123456'
            ]
        ], 200);
    }

    /**
     * API Verify OTP
     */
    public function api_verify_otp($request)
    {
        $params = $request->get_json_params();
        $phone = ltrim($params['phone'], '0');
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'OTP verified successfully (simulated)'
        ], 200);
    }

    /**
     * API Check Username
     */
    public function api_check_username($request)
    {
        $params = $request->get_json_params();
        $username = sanitize_user($params['username']);

        $exists = username_exists($username);
        $has_spaces = strpos($username, ' ') !== false;

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'username' => $username,
                'available' => !$exists && !$has_spaces,
                'exists' => (bool)$exists,
                'has_spaces' => $has_spaces
            ]
        ], 200);
    }

    /**
     * API Check WhatsApp Number
     */
    public function api_check_whatsapp($request)
    {
        $params = $request->get_json_params();
        $phone = ltrim($params['whatsapp_number'], '0');

        $user_id = $this->get_user_id_by_whatsapp($phone);
        $user_data = null;

        if ($user_id) {
            $user = get_user_by('ID', $user_id);
            if ($user) {
                $user_data = [
                    'user_id_wp' => $user_id,
                    'user_id' => $user_id,
                    'username' => $user->user_login,
                    'email' => $user->user_email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'display_name' => $user->display_name,
                    'has_received_points' => $this->has_received_registration_points($user_id)
                ];
            }
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'whatsapp_number' => $phone,
                'exists' => (bool)$user_id,
                'user' => $user_data
            ]
        ], 200);
    }

    /**
     * API Get User by WhatsApp Number
     */
    public function api_get_user_by_whatsapp($request)
    {
        $params = $request->get_json_params();
        $phone = ltrim($params['whatsapp_number'], '0');

        $user_id = $this->get_user_id_by_whatsapp($phone);

        if (!$user_id) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'User not found for this WhatsApp number'
            ], 404);
        }

        $user = get_user_by('ID', $user_id);
        
        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'user_id_wp' => $user_id,
                'user_id' => $user_id,
                'username' => $user->user_login,
                'email' => $user->user_email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'display_name' => $user->display_name,
                'whatsapp_number' => $phone,
                'has_received_points' => $this->has_received_registration_points($user_id)
            ]
        ], 200);
    }

    /**
     * Validation Functions
     */
    public function validate_username_for_new($username, $request, $key)
    {
        if (empty($username)) {
            return false;
        }
        if (strpos($username, ' ') !== false) {
            return false;
        }
        return true;
    }

    public function validate_whatsapp_number($number, $request, $key)
    {
        if (empty($number)) {
            return false;
        }
        $number = ltrim($number, '0');
        return is_numeric($number) && strlen($number) >= 9 && strlen($number) <= 15;
    }

    public function validate_sponsor($sponsor, $request, $key)
    {
        global $wpdb;
        
        if (empty($sponsor)) {
            return true;
        }

        $id_referral = $wpdb->get_var($wpdb->prepare(
            "SELECT idwp FROM wp_member WHERE subdomain = %s",
            sanitize_text_field($sponsor)
        ));

        return is_numeric($id_referral) && $id_referral > 0;
    }

    /**
     * Get user ID by WhatsApp number
     */
    private function get_user_id_by_whatsapp($number)
    {
        $number = "62" . ltrim($number, '0');
        
        $users = get_users([
            'meta_key' => '_whatsapp_number',
            'meta_value' => $number,
            'number' => 1,
            'fields' => 'ID'
        ]);

        return !empty($users) ? $users[0] : null;
    }

    /**
     * Call the original handle_wp_affiliate function
     */
    private function call_wp_affiliate_handler($user_id, $params)
    {
        // Simulate the global $_POST data
        $_POST['first_name'] = isset($params['first_name']) ? $params['first_name'] : '';
        $_POST['last_name'] = isset($params['last_name']) ? $params['last_name'] : '';

        // Create an instance of the original class if it exists
        if (class_exists('WhatsApp_Registration')) {
            $whatsapp_registration = new WhatsApp_Registration();
            
            if (method_exists($whatsapp_registration, 'handle_wp_affiliate')) {
                $whatsapp_registration->handle_wp_affiliate($user_id);
                return;
            }
        }

        // Fallback
        $this->direct_handle_wp_affiliate($user_id, $params);
    }

    /**
     * Direct implementation of handle_wp_affiliate (fallback)
     */
    private function direct_handle_wp_affiliate($customer_id, $params)
    {
        global $wpdb;
        
        $firstname = isset($params['first_name']) ? sanitize_text_field($params['first_name']) : '';
        $lastname = isset($params['last_name']) ? sanitize_text_field($params['last_name']) : '';
        $name = $firstname . ' ' . $lastname;
        
        // Remove any existing actions
        remove_action('user_register', 'cbaff_adduser');
        add_action('user_register', 'cbaff_adduser', 20);
        
        // Update user name in wp_member table
        $wpdb->query(
            $wpdb->prepare(
                'UPDATE `wp_member` SET `nama` = %s WHERE `idwp` = %d',
                $name,
                $customer_id
            )
        );
        
        // Send notification if cb_notif function exists
        if (function_exists('cb_notif')) {
            $id_user = $wpdb->get_var(
                $wpdb->prepare(
                    'SELECT id_user FROM wp_member WHERE idwp = %d',
                    $customer_id
                )
            );
    
            if ($id_user) {
                cb_notif($id_user, 'registrasi');
            }
        }
    }
}