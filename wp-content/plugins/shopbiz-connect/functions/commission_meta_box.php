<?php
// Add meta box for custom post type product
function commission_product_meta_box()
{
  add_meta_box(
    'shopbiz_commission_product_meta_box',
    'Komisi Produk',
    'render_commission_product_meta_box',
    'product',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'commission_product_meta_box');

function render_commission_product_meta_box($post)
{
  // Retrieve current values
  $BV = get_post_meta($post->ID, 'BV', true);
  $BV_type = get_post_meta($post->ID, 'BV_type', true);

  // Output fields
  echo '<label for="BV_type">Tipe Nilai:</label> ';
  echo '<select id="BV_type" name="BV_type" onchange="toggleBVInput(this.value)">';
  echo '<option value="nominal"' . selected($BV_type, 'nominal', false) . '>Nominal</option>';
  echo '<option value="percent"' . selected($BV_type, 'percent', false) . '>Persen</option>';
  echo '</select>';

  echo '<div id="BV_input_wrapper">';
  if ($BV_type === 'percent') {
    echo '<label for="BV">Nilai (%):</label> ';
    echo '<input type="number" id="BV" name="BV" value="' . esc_attr($BV) . '" min="0" max="100">';
  } else {
    echo '<label for="BV">Nilai:</label> ';
    echo '<input type="text" id="BV" name="BV" value="' . esc_attr($BV) . '">';
  }
  echo '</div>';

  echo '<input type="hidden" name="shopbiz_product_meta_box_nonce" value="' . wp_create_nonce('shopbiz_save_product_meta_box') . '">';

  // Add JavaScript for dynamic input toggle
  echo '<script>
    function toggleBVInput(value) {
      const wrapper = document.getElementById("BV_input_wrapper");
      wrapper.innerHTML = "";
      if (value === "percent") {
        wrapper.innerHTML = `<label for="BV">Nilai (%):</label> <input type="number" id="BV" name="BV" min="0" max="100">`;
      } else {
        wrapper.innerHTML = `<label for="BV">Nilai:</label> <input type="text" id="BV" name="BV">`;
      }
    }
  </script>';
}

// Save meta box data
function shopbiz_save_product_meta_box($post_id)
{
  // Check if nonce is set
  if (!isset($_POST['shopbiz_product_meta_box_nonce'])) {
    return;
  }

  // Verify nonce
  if (!wp_verify_nonce($_POST['shopbiz_product_meta_box_nonce'], 'shopbiz_save_product_meta_box')) {
    return;
  }

  // Check if autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Check if user has permission to save data
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // Save meta box data
  if (isset($_POST['BV'])) {
        update_post_meta($post_id, 'BV_type', sanitize_text_field($_POST['BV_type']));
        update_post_meta($post_id, 'BV', sanitize_text_field($_POST['BV']));
  }
}
add_action('save_post_product', 'shopbiz_save_product_meta_box');

// Add meta box for variation custom fields
function commission_variation_meta_box() {
    add_meta_box(
        'shopbiz_commission_variation_meta_box',
        'Komisi Variasi Produk',
        'render_commission_variation_meta_box',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'commission_variation_meta_box');

function render_commission_variation_meta_box($post) {
    // Only show for variable products
    if ($post->post_type !== 'product') {
        return;
    }
    
    $product = wc_get_product($post->ID);
    if (!$product || !$product->is_type('variable')) {
        echo '<p>Metabox ini hanya tersedia untuk produk variasi.</p>';
        return;
    }
    
    echo '<div id="variation_commission_settings">';
    echo '<p>Atur komisi untuk setiap variasi produk:</p>';
    echo '<p><em>Kosongkan jika ingin menggunakan setting dari produk induk</em></p>';
    echo '</div>';
}

// Add custom fields to variation
function commission_variation_settings_fields($loop, $variation_data, $variation) {
    $BV = get_post_meta($variation->ID, 'BV', true);
    $BV_type = get_post_meta($variation->ID, 'BV_type', true);
    
    // Get parent values for reference
    $parent_BV = get_post_meta($variation->post_parent, 'BV', true);
    $parent_BV_type = get_post_meta($variation->post_parent, 'BV_type', true);
    ?>
    <div class="variation-commission-fields">
        <h4>Komisi Variasi</h4>
        
        <p class="form-row form-row-first">
            <label for="BV_type_<?php echo $loop; ?>">Tipe Nilai:</label>
            <select id="BV_type_<?php echo $loop; ?>" name="BV_type[<?php echo $loop; ?>]" class="bv-type-select">
                <option value="">Gunakan dari induk (<?php echo ($parent_BV_type ?: 'nominal'); ?>)</option>
                <option value="nominal" <?php selected($BV_type, 'nominal'); ?>>Nominal</option>
                <option value="percent" <?php selected($BV_type, 'percent'); ?>>Persen</option>
            </select>
        </p>
        
        <p class="form-row form-row-last">
            <label for="BV_<?php echo $loop; ?>">Nilai:</label>
            <?php if ($BV_type === 'percent'): ?>
                <input type="number" id="BV_<?php echo $loop; ?>" name="BV[<?php echo $loop; ?>]" value="<?php echo esc_attr($BV); ?>" min="0" max="100" placeholder="Dari induk: <?php echo esc_attr($parent_BV); ?>">
            <?php else: ?>
                <input type="text" id="BV_<?php echo $loop; ?>" name="BV[<?php echo $loop; ?>]" value="<?php echo esc_attr($BV); ?>" placeholder="Dari induk: <?php echo esc_attr($parent_BV); ?>">
            <?php endif; ?>
        </p>
        
        <p class="description">
            Nilai induk: <?php echo esc_html($parent_BV ?: '0'); ?> (<?php echo esc_html($parent_BV_type ?: 'nominal'); ?>)
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Dynamic input type change
        $('.bv-type-select').on('change', function() {
            var loop = $(this).attr('name').match(/\[(\d+)\]/)[1];
            var value = $(this).val();
            var parentValue = '<?php echo esc_js($parent_BV); ?>';
            
            if (value === 'percent') {
                $('#BV_' + loop).attr('type', 'number').attr('min', '0').attr('max', '100').attr('placeholder', 'Dari induk: ' + parentValue);
            } else {
                $('#BV_' + loop).attr('type', 'text').removeAttr('min').removeAttr('max').attr('placeholder', 'Dari induk: ' + parentValue);
            }
        });
    });
    </script>
    <?php
}
add_action('woocommerce_product_after_variable_attributes', 'commission_variation_settings_fields', 10, 3);

// Save variation custom fields
function save_commission_variation_settings_fields($variation_id, $loop) {
    // Save BV type
    if (isset($_POST['BV_type'][$loop])) {
        $bv_type = sanitize_text_field($_POST['BV_type'][$loop]);
        if (!empty($bv_type)) {
            update_post_meta($variation_id, 'BV_type', $bv_type);
        } else {
            // If empty, delete the meta to use parent's value
            delete_post_meta($variation_id, 'BV_type');
        }
    }
    
    // Save BV value
    if (isset($_POST['BV'][$loop])) {
        $bv_value = sanitize_text_field($_POST['BV'][$loop]);
        if (!empty($bv_value)) {
            update_post_meta($variation_id, 'BV', $bv_value);
        } else {
            // If empty, delete the meta to use parent's value
            delete_post_meta($variation_id, 'BV');
        }
    }
}
add_action('woocommerce_save_product_variation', 'save_commission_variation_settings_fields', 10, 2);

// Add CSS for better styling
function commission_variation_admin_styles() {
    ?>
    <style>
        .variation-commission-fields {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .variation-commission-fields h4 {
            margin-top: 0;
            color: #333;
        }
        .variation-commission-fields .description {
            font-style: italic;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
    <?php
}
add_action('admin_head', 'commission_variation_admin_styles');
