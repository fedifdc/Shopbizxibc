<?php

/**
 * Template Name: PPOB Categories
 */

get_header();
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
$label = "No. Hp";

if (!$category_id) {
    //redirect ke halaman utama
    wp_redirect(home_url());
    exit;
}

?>
<div id="maincontent">
    <div id="descModal" class="modal-overlay">
        <div class="modal-content">
            <button id="closeModal" class="modal-close">❌</button>
            <h3>Deskripsi Produk</h3>
            <p id="modalContent" style="margin-top: 8px;"></p>
        </div>
    </div>

    <!-- Modal Tagihan -->
    <div id="billModal" class="modal-overlay">
        <div class="modal-content">
            <button id="closeBillModal" class="modal-close">❌</button>
            <h3>Tagihan</h3>
            <p id="billModalContent" style="margin-top: 8px;"></p>
        </div>
    </div>
    <div class="container clearfix">
        <div class="woocommerce">
            <!-- #billModal -->

            <div class="woocommerce-notices-wrapper"></div>

            <div class="ppob-checkout-wrapper" style="display: flex; ">
                <!-- PPOB Detail (2/3) -->
                <div class="ppob-details" style="flex: 2; min-width: 60%; background: #fff; padding: 20px; border: 1px solid #eaeaea; border-radius: 5px;">


                    <input type="hidden" id="ppob-type" value="<?php echo $_GET['type'] ?>">
                    <input type="hidden" id="category_id" value="<?php echo $_GET['category_id'] ?>">

                    <!-- Input Nomor HP & Pilih Operator -->
                    <div class="ppob-form-row form-row">
                        <!-- No.HP / Pelanggan / User ID -->
                        <div class="ppob-form-group form-group" style="flex:1;">
                            <label for="phone_number"><?php echo $label; ?></label>
                            <input type="text" id="phone_number" class="input-text" placeholder="08xxx">
                        </div>

                        <!-- No Meteran PLN -->
                        <?php if ($category_id == 19) : ?>
                            <div class="ppob-meter-input-group meter-input-group" style="flex:1;">
                                <label for="meter_number">No. Meteran PLN</label>
                                <div class="ppob-meter-input-wrapper meter-input-wrapper">
                                    <input type="text" id="meter_number" class="ppob-meter-input meter-input" placeholder="No. Meteran PLN">
                                    <button id="checkBillPrabayar" class="ppob-meter-button meter-button">Cek</button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Pilih Operator -->
                        <div class="ppob-form-group form-group" style="flex:1;">
                            <label for="operator_id">Pilih Operator</label>
                            <select id="operator_id" style="width: 100%; padding: 8px; font-size: 14px;">
                                <option value="">Pilih Operator</option>
                            </select>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="product-list">
                        <h3 style="margin-bottom: 10px;">Daftar Produk:</h3>
                        <div id="productList" style="
                            display: grid; 
                            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); 
                            gap: 15px;
                            justify-content: center;
                        ">
                            <!-- Produk akan dimuat melalui AJAX -->
                        </div>
                    </div>
                </div>

                <!-- Quick Checkout (1/3) -->
                <div class="quick-checkout" style="flex: 1; min-width: 35%; background: #fff; padding: 20px; border: 1px solid #eaeaea; border-radius: 5px;">
                    <?php echo do_shortcode('[custom_quick_checkout]'); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
    /* Responsiveness for PPOB Form Row */
    .ppob-form-row {
        display: flex;
        align-items: stretch;
        gap: 10px;
    }

    .ppob-form-row input,
    .ppob-form-row select {
        width: 100%;
    }

    /* Responsive Layout */
    @media (max-width: 1024px) {
        .ppob-form-row {
            flex-direction: column;
            gap: 15px;
        }

        .ppob-form-row .ppob-form-group,
        .ppob-form-row .ppob-meter-input-group {
            width: 100%;
        }

        .ppob-form-row input,
        .ppob-form-row select {
            width: 100%;
        }
    }

    /* Optimize for Extra Small Screens */
    @media (max-width: 480px) {
        .ppob-form-row {
            gap: 8px;
        }

        .ppob-form-row label {
            font-size: 14px;
        }

        .ppob-form-row input,
        .ppob-form-row select {
            font-size: 14px;
        }
    }

    /* Overlay Background */
    .modal-overlay {
        display: none;
        z-index: 10000;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    /* Modal Box */
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 90%;
        width: 320px;
        text-align: center;
        position: relative;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Close Button */
    .modal-close {
        position: absolute;
        top: 8px;
        right: 8px;
        border: none;
        background: transparent;
        font-size: 18px;
        cursor: pointer;
    }

    /* Responsive Wrapper */
    .ppob-checkout-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* PPOB Details & Quick Checkout */
    .ppob-details,
    .quick-checkout {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 5px;
    }

    /* Responsive Layout */
    @media (max-width: 1024px) {
        .ppob-checkout-wrapper {
            flex-direction: column;
        }

        .ppob-details,
        .quick-checkout {
            width: 100%;
            min-width: unset;
        }
    }

    /* Responsive Grid for Product List */
    #productList {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 10px;
    }

    /* Product List Grid - More Compact */
    #productList {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        /* Smaller min size */
        gap: 8px;
        /* Reduced gap */
    }

    /* Medium Screens - Fit More Items */
    @media (max-width: 1024px) {
        #productList {
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 6px;
        }
    }

    /* Small Screens - More Compact */
    @media (max-width: 768px) {
        #productList {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 4px;
        }
    }

    /* Extra Small Screens */
    @media (max-width: 480px) {
        #productList {
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 3px;
        }
    }

    .form-row {
        display: flex;
        align-items: stretch;
        gap: 10px;
    }

    /* Adjust layout for tablets */
    @media (max-width: 1024px) {
        .form-row {
            gap: 15px;
        }
    }

    /* Stack items vertically for smaller screens */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .form-row p,
        .form-row div {
            width: 100%;
        }

        .form-row input,
        .form-row select {
            width: 100%;
        }
    }

    /* Optimize for extra small screens */
    @media (max-width: 480px) {
        .form-row {
            gap: 8px;
        }

        .form-row label {
            font-size: 14px;
        }

        .form-row input,
        .form-row select {
            padding: 8px;
            font-size: 14px;
        }

        /* .form-row button {
            font-size: 14px;
            padding: 8px 12px;
        } */
    }

    .meter-input-group {
        width: 100%;
    }

    .meter-input-wrapper {
        display: flex;
        align-items: center;
        width: 100%;
        position: relative;
    }

    .meter-input {
        flex: 1;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .meter-button {
        padding: 10px 12px;
        margin-left: 10px;
        background-color:rgb(0, 0, 0);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .meter-input-wrapper {
            flex-wrap: nowrap;
            /* Prevents wrapping */
        }

        .meter-input {
            width: 100%;
        }

        .meter-button {
            white-space: nowrap;
        }
    }
</style>

<script>
    var PPOBTripay = {
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>'
    };
</script>

<?php
get_footer();
?>