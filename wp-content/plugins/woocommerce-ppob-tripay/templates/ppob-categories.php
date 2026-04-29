<?php

/**
 * Template Name: PPOB Categories
 */

get_header();
?>
<div id="maincontent">
    <div class="container clearfix">
        <div class="ppob-container max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Pembelian & Tagihan</h2>

            <!-- Kategori -->
            <div class="mb-6 px-4">
                <h3 class="text-lg font-semibold mb-4">Kategori</h3>
                <div id="category_id" class="p-4 bg-gray-100 rounded-md shadow-sm">
                   <div class="spinner-border text-primary" role="status">
                        <span id="loading-categories">Loading...</span>
                    </div>
                    <!-- Produk akan dimuat melalui AJAX -->
                    
                </div>
            </div>

        </div>

    </div>
</div>


<script>
    var PPOBTripay = {
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>'
    };
</script>

<?php
get_footer();
?>