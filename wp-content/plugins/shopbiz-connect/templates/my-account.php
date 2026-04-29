<?php

/**
 * My Account - Points page
 */

if (!defined('ABSPATH')) {
  exit;
}

function point_level_endpoint()
{
  add_rewrite_endpoint('points', EP_ROOT | EP_PAGES);
  flush_rewrite_rules();
}
add_action('init', 'point_level_endpoint');

function point_reward_endpoint()
{
  add_rewrite_endpoint('point-reward', EP_ROOT | EP_PAGES);
  flush_rewrite_rules();
}
add_action('init', 'point_reward_endpoint');

// Tambahkan endpoint baru untuk poin eksternal
function point_upgrade_endpoint()
{
  add_rewrite_endpoint('point-upgrade', EP_ROOT | EP_PAGES);
  flush_rewrite_rules();
}
add_action('init', 'point_upgrade_endpoint');

function myaccount_custom_endpoint_query_var($query_vars)
{
  $query_vars['points'] = 'points';
  $query_vars['point-reward'] = 'point-reward';
  $query_vars['point-upgrade'] = 'point-upgrade';

  return $query_vars;
}
add_filter('woocommerce_get_query_vars', 'myaccount_custom_endpoint_query_var', 0);
// add_filter('woocommerce_get_query_vars', 'myaccount_custom_endpoint_query_var', 0);
// function myaccount_custom_endpoint_query_var($query_vars)
// {
//   $query_vars['points'] = 'points';
  

//   return $query_vars;
// }

// function point_level_menu_item($items)
// {
//   $items = array_slice($items, 0, count($items) - 1, true) +
//     array('points' => __('Poin Level', 'points')) +
//     array_slice($items, 1, null, true);

//   return $items;
// }
function point_level_menu_item($items) {
    // Sisipkan menu baru di antara menu lain
    $items = array_slice($items, 0, count($items) - 1, true) +
        array(
            'points' => __('Poin Bonus', 'points'),
            'point-reward' => __('Poin Reward', 'point_reward'),
            'point-upgrade' => __('Poin Level', 'point_upgrade'),
        ) +
        array_slice($items, count($items) - 1, null, true);

    return $items;
}
add_filter('woocommerce_account_menu_items', 'point_level_menu_item', 20);
// function point_menu_items($items)
// {
//   $items['points'] = __('Poin Level', 'points');
//   $items['point-pending'] = __('Poin Pending', 'points');
//   $items['point-external'] = __('Poin Eksternal', 'points');

//   return $items;
// }
// add_filter('woocommerce_account_menu_items', 'point_level_menu_item', 20);

function point_level_endpoint_content()
{
  $current_point = mycred_get_users_total(get_current_user_id(), 'point_level');
  $is_member = get_user_meta(get_current_user_id(), 'shopbiz_user_id', true);
?>
  <div class="ywpar-wrapper">
    <h2>Poin Bonus</h2>
    <div class="ywpar_myaccount_entry_info">
      <div class="ywpar_summary_badge">
        <span class="ywpar_entry_info_title">Total Point Bonus</span>
        <!-- <span class="ywpar_to_redeem_title">to redeem</span> -->
        <span class="points_collected"><?php echo do_shortcode('[mycred_my_balance type="point_level"]'); ?></span>
        <!-- <span class="ywpar_total_collected_title">total collected:<span>0</span></span> -->
      </div>
      <div class="ywpar_summary_badge">
        <span class="ywpar_entry_info_title">Point Pending</span>
        <!-- <span class="ywpar_to_redeem_title">to redeem</span> -->
        <span class="points_collected"><?php echo do_shortcode('[mycred_my_balance type="point_external_pending"]'); ?></span>
        <!-- <span class="ywpar_total_collected_title">total collected:<span>0</span></span> -->
      </div>
      <div class="ywpar_summary_badge">
        <span class="ywpar_entry_info_title">Point Eksternal</span>
        <!-- <span class="ywpar_to_redeem_title">to redeem</span> -->
        <span class="points_collected"><?php echo do_shortcode('[mycred_my_balance type="point_external_website"]'); ?></span>
        <!-- <span class="ywpar_total_collected_title">total collected:<span>0</span></span> -->
      </div>
    </div>
    <?php if (($current_point >= 100 && $current_point < 5000000) || !$is_member) : ?>
      <div id="countdown-container" style="margin-top: 20px">
<!--         <div class="coutndown-head">
          <h4 class="subheading">Ayo buruan, upgrade akunmu sebelum:</h4>
        </div> -->

<!--         <div id="countdown" data-stellar-ratio="0.5" data-state="loaded">
          <div id="timer">
            <div class="number-container day">
              <div class="number">00</div>
              <div class="text">Hari</div>
            </div>
            <div class="number-container hour">
              <div class="number">00</div>
              <div class="text">Jam</div>
            </div>
            <div class="number-container minute">
              <div class="number">00</div>
              <div class="text">Menit</div>
            </div>
            <div class="number-container second">
              <div class="number">00</div>
              <div class="text">Detik</div>
            </div>
          </div>
        </div> -->
      </div>
<!--       <div id="upgrade" class="mt-4">
        <div class="cepatlakoo-message woocommerce-message" style="display: none;"></div>
        <button id="btn-upgrade" class="btn btn-primary">Upgrade ke MLM</button>
      </div> -->
        <?php elseif ($current_point >= 5000000 && $is_member) : ?>
      <!--<div class="mt-4">-->
      <!--  <a href="https://member.shopbiz.id/" target="_blank" class="button">Login ke MLM</a>-->
      <!--</div>-->
    <?php endif; ?> 
    <div class="tabs-container mt-4" style="font-family: Arial, sans-serif;">
  <!-- Tab Navigation -->
  <ul class="tabs-nav" style="list-style: none; display: flex; margin: 0; padding: 0; overflow-x: auto;">
    <li class="tab-link active" data-tab="tab-1" style="padding: 10px 20px; margin-right: 5px; cursor: pointer; background-color: #f4f4f4; border-radius: 5px 5px 0 0; transition: background-color 0.3s ease;">Riwayat Point Bonus</li>
    <li class="tab-link" data-tab="tab-2" style="padding: 10px 20px; margin-right: 5px; cursor: pointer; background-color: #f4f4f4; border-radius: 5px 5px 0 0; transition: background-color 0.3s ease;">Riwayat Point Pending</li>
    <li class="tab-link" data-tab="tab-3" style="padding: 10px 20px; margin-right: 5px; cursor: pointer; background-color: #f4f4f4; border-radius: 5px 5px 0 0; transition: background-color 0.3s ease;">Riwayat Point Eksternal</li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content-container" style="border: 1px solid #ddd; border-top: none; padding: 20px;">
    <div id="tab-1" class="tab-content active" style="display: block;">
      <h3>Riwayat Point Level</h3>
      <?php echo do_shortcode('[mycred_history type="point_level" user_id="current" show_user="0" number="20"]'); ?>
    </div>

    <div id="tab-2" class="tab-content" style="display: none;">
      <h3>Riwayat Point Pending</h3>
      <?php echo do_shortcode('[mycred_history type="point_external_pending" user_id="current" show_user="0" number="20"]'); ?>
    </div>

    <div id="tab-3" class="tab-content" style="display: none;">
      <h3>Riwayat Point Eksternal</h3>
      <?php echo do_shortcode('[mycred_history type="point_external_website" user_id="current" show_user="0" number="20"]'); ?>
    </div>
  </div>
</div>

<style>
  .tabs-nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .tab-link {
    flex: 1 1 auto;
    text-align: center;
    margin: 0 2px;
  }

  @media (max-width: 768px) {
    .tabs-nav {
      flex-direction: column;
      align-items: stretch;
    }

    .tab-link {
      margin: 2px 0;
      border-radius: 5px;
    }

    .tab-content-container {
      padding: 15px;
    }
  }
</style>

<script>
    jQuery(document).ready(function($) {
        $('.tab-link').on('click', function () {
              var tabId = $(this).data('tab');
            
              // Remove active class from all tabs and contents
              $('.tab-link')
                .removeClass('active')
                .css({
                  'background-color': '#f4f4f4',
                  color: '#000',
                });
              $('.tab-content').css('display', 'none');
            
              // Add active class to the clicked tab and the corresponding content
              $(this)
                .addClass('active')
                .css({
                  'background-color': '#007cba',
                  color: '#fff',
                });
              $('#' + tabId).css('display', 'block');
            });
    });
  </script> 
<?php
}
add_action('woocommerce_account_points_endpoint', 'point_level_endpoint_content');
function point_voucher_endpoint_content()
{ ?>
  <div class="ywpar-wrapper">
        <h2>Point Reward</h2>
        <div class="ywpar_myaccount_entry_info">
          <div class="ywpar_summary_badge">
            <span class="ywpar_entry_info_title">Points</span>
            <!-- <span class="ywpar_to_redeem_title">to redeem</span> -->
            <span class="points_collected"><?php echo do_shortcode('[mycred_my_balance type="point_voucher"]'); ?></span>
            <!-- <span class="ywpar_total_collected_title">total collected:<span>0</span></span> -->
          </div>
        </div>
        <div class="mt-4">
            <h3>Riwayat Point Reward</h3>
            <?php echo do_shortcode('[mycred_history type="point_voucher" user_id="current" show_user="0" number="20"]');?>
        </div>
        
    </div>
<?php }
add_action('woocommerce_account_point-reward_endpoint', 'point_voucher_endpoint_content');

// Konten untuk Point External
function point_updgrade_endpoint_content()
{
  ?>
  <div class="ywpar-wrapper">
        <h2>Poin Level</h2>
        <div class="ywpar_myaccount_entry_info">
          <div class="ywpar_summary_badge">
            <span class="ywpar_entry_info_title">Points</span>
            <!-- <span class="ywpar_to_redeem_title">to redeem</span> -->
            <span class="points_collected"><?php echo do_shortcode('[mycred_my_balance type="point_upgrade"]'); ?></span>
            <!-- <span class="ywpar_total_collected_title">total collected:<span>0</span></span> -->
          </div>
        </div>
         <div class="mt-4">
            <h3>Riwayat Poin Level</h3>
            <?php echo do_shortcode('[mycred_history type="point_upgrade" user_id="current" show_user="0" number="20"]');?>
        </div>
        
    </div>
<?php
}
add_action('woocommerce_account_point-upgrade_endpoint', 'point_updgrade_endpoint_content');