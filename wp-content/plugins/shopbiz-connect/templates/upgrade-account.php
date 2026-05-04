<?php
add_action('admin_menu', 'shopbiz_connect_menu_upgrade');
function shopbiz_connect_menu_upgrade()
{
  add_options_page(
    'ShopBiz Connect Upgrade',
    'ShopBiz Connect Upgrade',
    'manage_options',
    'shopbiz-connect-upgrade',
    'shopbiz_connect_render_upgrade'
  );
}


function shopbiz_connect_render_upgrade()
{
  global $wpdb;

  // Pagination setup
  $items_per_page = 10;
  $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
  $offset = ($current_page - 1) * $items_per_page;

  // Query to fetch users with the specified conditions
    $query = "
    SELECT u.ID, u.user_login, u.user_email, um1.meta_value AS point_level, m.homepage
    FROM {$wpdb->users} u
    INNER JOIN {$wpdb->usermeta} um1 ON u.ID = um1.user_id AND um1.meta_key = 'point_upgrade' AND CAST(um1.meta_value AS UNSIGNED) >= 5000000
    LEFT JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id AND um2.meta_key = 'shopbiz_user_id'
    LEFT JOIN wp_member m ON u.ID = m.idwp
    WHERE um2.meta_value is NULL
    order by u.ID ASC
    LIMIT %d OFFSET %d
  ";
  $users = $wpdb->get_results($wpdb->prepare($query, $items_per_page, $offset));

  // Count total users for pagination
  $count_query = "
    SELECT COUNT(*)
    FROM {$wpdb->users} u
    INNER JOIN {$wpdb->usermeta} um1 ON u.ID = um1.user_id AND um1.meta_key = 'point_upgrade' AND CAST(um1.meta_value AS UNSIGNED) >= 5000000
    LEFT JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id AND um2.meta_key = 'shopbiz_user_id'
    WHERE um2.meta_value is NULL
  ";
  $total_users = $wpdb->get_var($count_query);
  $total_pages = ceil($total_users / $items_per_page);

  // Render the table
  ?>
  <div class="wrap">
    <h1>ShopBiz Connect Upgrade</h1>
    
    <form method="post" action="">
      <table class="widefat fixed striped">
        <thead>
          <tr>
            <th></th>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Point Level</th>
            <th>Sponsor</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
              <tr>
                <?php
                $homepage = unserialize($user->homepage);
                $uplines = $homepage['uplines'];
                $uplines_array = explode(',', $uplines);
                $upline_user_login = '';
                $referral_id = null;
                foreach ($uplines_array as $upline) {
                $referral_id = get_user_meta($upline, 'shopbiz_user_id', true);
                    if ($referral_id) {
                        $upline_user = get_userdata($upline);
                        $upline_user_login = $upline_user ? $upline_user->user_login : 'Unknown';
                        break;
                    }
                }
                ?>
                <td><input type="checkbox" name="selected_users[]" value="<?php echo esc_attr($user->ID); ?>"></td>
                <td><?php echo esc_html($user->ID); ?></td>
                <td><?php echo esc_html($user->user_login); ?></td>
                <td><?php echo esc_html($user->user_email); ?></td>
                <td><?php echo esc_html($user->point_level); ?></td>
                <td><?php echo esc_html($upline_user_login); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="5">No users found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <br>
      <input type="button" id="upgrade-users-button" class="button-primary" value="Upgrade Selected Users">
<script>

document.getElementById('upgrade-users-button').addEventListener('click', function (e) {
        const selectedUsers = document.querySelectorAll('input[name="selected_users[]"]:checked');
        if (selectedUsers.length === 0) {
      alert('Please select at least one user to upgrade.');
      return;
        }

        // Create the modal
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';
        modal.style.zIndex = '1000';

        const modalContent = document.createElement('div');
        modalContent.style.backgroundColor = '#fff';
        modalContent.style.padding = '20px';
        modalContent.style.borderRadius = '5px';
        modalContent.style.textAlign = 'center';
        modalContent.innerHTML = `
      <p>Are you sure you want to upgrade the selected users?</p>
      <button id="confirm-upgrade" class="button-primary">Yes</button>
      <button id="cancel-upgrade" class="button-secondary">No</button>
        `;

        modal.appendChild(modalContent);
        document.body.appendChild(modal);

        // Handle modal actions
        document.getElementById('confirm-upgrade').addEventListener('click', function () {
      modal.remove();
      const form = document.querySelector('form');
      form.submit();
        });

        document.getElementById('cancel-upgrade').addEventListener('click', function () {
      modal.remove();
        });
      });
    </script>
    </form>

    <!-- Pagination -->
    <div class="tablenav">
      <div class="tablenav-pages">
        <?php
        if ($total_pages > 1) {
          $pagination_args = array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'current' => $current_page,
            'total' => $total_pages,
          );
          echo paginate_links($pagination_args);
        }
        ?>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('select-all').addEventListener('click', function (e) {
      const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
      checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
    });
 

  </script>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_users'])) {
    $selected_users = array_map('intval', $_POST['selected_users']);

    foreach ($selected_users as $user_id) {
      // Update the user meta to set 'user_mlm_id' to 1
      shopbiz_upgrade_new($user_id);
    }

    // Redirect to avoid form resubmission
    wp_redirect(add_query_arg('updated', 'true', $_SERVER['REQUEST_URI']));
    exit;
  }

  
}
function formatMobileNumber1($mobileNumber) {
    // Remove spaces from the mobile number
    $formattedNumber = str_replace(' ', '', $mobileNumber);
    
    // Optionally, you can also handle other characters if needed, such as dashes or parentheses
    $formattedNumber = preg_replace('/[^\d+]/', '', $formattedNumber); // Keep only digits and plus sign
    
    return $formattedNumber;
}


// Handle AJAX post request
add_action('wp_ajax_shopbiz_upgrade', 'shopbiz_upgrade');
add_action('wp_ajax_nopriv_shopbiz_upgrade', 'shopbiz_upgrade');

function shopbiz_upgrade_new($user_id = null)
{
  

  global $wpdb;
  
    // Query data dari database
    $result = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM wp_member WHERE idwp = %d",
            $user_id
        )
    );

    if ($result) {
        $unserializeData = unserialize($result->homepage);
        $is_premium = $result->membership;
        $data = [
            'nik' => $result->ktp,
            'alamat1' => $result->alamat,
            'foto_ktp' => $result->foto_ktp,
            'alamat2' => $result->provinsi,
            'alamat3' => $result->kota,
            'namaBank' => $result->bank,
            'ownerName' => strtoupper($result->ac),
            'noRek' => $result->rekening,
            'passwordTransaksi' => $unserializeData[1],
            'whatsapp' => $unserializeData['whatsapp'],
            'foto_ktp_url' => $unserializeData['foto_ktp'],
        ];
        $current_point = mycred_get_users_total($user_id, 'point_level');
        $is_member = get_user_meta($user_id, 'shopbiz_user_id', true);
        
        // Get current user ID
        $user_id = $user_id;
        // Get shipping address fields
        $shipping_address_1 = get_user_meta($user_id, 'shipping_address_1', true);
        if(!$shipping_address_1){
            $shipping_address_1 = get_user_meta($user_id, 'billing_address_1', true);
        }
        $shipping_city = get_user_meta($user_id, 'billing_city', true);
        $shipping_state = get_user_meta($user_id,'billing_state', true);
        $is_process = get_user_meta($user_id, 'sync_mlm_status', true);
        $reg_message = get_user_meta($user_id, 'sync_error_message', true);
        $user_meta = get_user_meta($user_id);
    }

  // Fetch form data
  $nik = sanitize_text_field($data['nik']);
  $alamat = sanitize_textarea_field($shipping_address_1);
  $state = sanitize_textarea_field( $shipping_state);
  $city = sanitize_textarea_field( $shipping_city);

  $nama_bank = sanitize_text_field($data['namaBank']);
  $owner_name = sanitize_text_field($data['ownerName']);
  $no_rek = sanitize_text_field($_POST['noRek']);
  $password_transaksi = sanitize_text_field($_POST['passwordTransaksi']);
  $foto_ktp_url = sanitize_text_field($_POST['foto_ktp']); // The URL of the uploaded image
  update_user_meta($user_id,'foto_ktp_url',$foto_ktp_url);


  // Query user data from database
  $table_reg = $wpdb->prefix . 'shopbiz_registration';
  $query_reg = $wpdb->prepare("SELECT * FROM $table_reg WHERE customer_id = %d", $user_id);
  $reg_data = $wpdb->get_row($query_reg);
  $phone = get_user_meta($user_id, 'billing_phone', true);

  $table_referral = $wpdb->prefix . 'member';
  $query_referral = $wpdb->prepare("SELECT * FROM $table_referral WHERE idwp = %d", $user_id);
  $referral_data = $wpdb->get_row($query_referral);
  $uplines = unserialize($referral_data->homepage)['uplines'];
  $uplines_array = explode(',', $uplines);
  $referral_id = null;

  foreach ($uplines_array as $upline) {
    $referral_id = get_user_meta($upline, 'shopbiz_user_id', true);
    if ($referral_id) {
      break;
    }
  }
  if (!$referral_id) {
    $referral_id = 1;
  }
  $bonus_level =intval(get_user_meta($user_id, 'point_level', true));
  $updrade_level =intval(get_user_meta($user_id, 'point_upgrade', true));
  // Trigger API call on registration
  $api_url = get_option('shopbiz_api_url') . '/register';
$userdata = get_userdata($user_id);
if (!$userdata) {
    $response = array(
        'success' => false,
        'message' => 'User not found.',
    );
    wp_send_json($response);
}
  $api_data = array(
    'email' => $userdata->user_email,
    'first_name' => $userdata->first_name,
	'last_name' => $userdata->last_name,
    'gender' => 'M',
    'mobile' => formatMobileNumber1($phone),
    'password' => $reg_data->password,
    'placement' => '',
    'position' => '',
    'pv' => $bonus_level, //
    'bv' => $updrade_level,
    'referralId' => $referral_id && !empty($referral_id) ? $referral_id : 1,
    'username' => $userdata->user_login,
    'ecomCustomerRefId' => $user_id,
    'nik' => $nik,
    "bank_name" => $nama_bank,
	"bank_account_name" => $owner_name,
    "bank_account_number" => $no_rek,
	'transaction_password' => $password_transaksi,
	 'address' => $alamat,
	 'state' => $state,
	 'city' => $city
    
  );
	$api_data_json = json_encode($api_data);
// print_r($api_data);
// die(0);
	$response = wp_remote_post($api_url, array(
		'headers' => array(
			'Content-Type' => 'application/json', // Specify JSON content type
			'secret_key' => get_option('shopbiz_api_token'), // Custom header
			'prefix' => '1119', // Additional custom header
		),
		'body' => $api_data_json, // JSON data
		'method' => 'POST', // HTTP POST method
	));

  // Handle the API response
  if (is_wp_error($response)) {
    error_log('API call failed: ' . $response->get_error_message());
    $response = array(
      'success' => false,
      'message' => 'Upgrade failed.',
    );
   // wp_send_json($response);
  } else {
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
	 $data = json_decode($response_body);
	  
	if($response_code == 422) {
		$response = array(
        'success' => false,
        'message' => $data->message,
      );
		
      //wp_send_json($response);
	}
    if ($response_code != 200 && $response_code != 201) {
      error_log('API call returned an error: ' . $response_body);
      $response = array(
        'success' => false,
        'message' => 'Poin tidak mencukupi untuk upgrade',
		'error' => $response_body,
		'body' => $api_data,
      );
		
      //wp_send_json($response);
    } else {
		update_user_meta($user_id, 'sync_mlm_status', 0);
      $response = array(
        'success' => true,
        'message' => 'Proses Pendaftaran dalam antrian, harap perikas secara berkala',
      );
	  $table_name = $wpdb->prefix . 'member'; // 'wp_member' table
     // wp_send_json($response);
    }
  }
}
