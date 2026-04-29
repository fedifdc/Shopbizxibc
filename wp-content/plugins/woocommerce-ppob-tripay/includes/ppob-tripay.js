jQuery(document).ready(function ($) {
    function fetchData(action, data, callback) {
        $.ajax({
            url: PPOBTripay.ajax_url,
            type: "POST",
            data: { action, ...data },
            beforeSend: function () {
                $("#productList").html('<p class="text-center">Memuat produk...</p>');
            },
            success: callback,
            error: function (error) {
                console.error("Error:", error);
            }
        });
    }

    function renderCategories(categoriesData) {
        let options = "";

        $.each(categoriesData, function (type, categories) {
            if (categories.length > 0) {
                options += `<div style="margin-bottom: 16px;">
                                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 8px;">${type.charAt(0).toUpperCase() + type.slice(1)}</h3>
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">`;

                $.each(categories, function (index, category) {
                    if (category.status == 0) {
                        // skip if category is disabled
                        return;
                    }
                    options += `<a href="https://shopbiz.id/ppob-detail/?category_id=${category.id}&type=${type}">
                                    <div class="category-card">
                                        <img src="https://shopbiz.id/wp-content/uploads/ppob-tripay/${category.id}.svg" class="category-icon">
                                        <h4 class="category-title">${category.product_name ?? category.name}</h4>
                                    </div>
                                </a>`;
                });

                options += `</div></div>`;
            }
        });

        $("#category_id").html(options);
    }
    function loadCategories() {
        let categoriesData = {};
        
        fetchData("get_categories", { type: "prabayar" }, function (response) {

            categoriesData["prabayar"] = response.data;
            console.log("CATEGORY LOADED");
            checkAndRender();
            // renderCategories(categoriesData);

        });

        // fetchData("get_categories", { type: "pascabayar" }, function (response) {
        //     categoriesData["pascabayar"] = response.data;
        //     checkAndRender();
        // });

        function checkAndRender() {
            // if (categoriesData["prabayar"] && categoriesData["pascabayar"]) {
            if (categoriesData["prabayar"] ) {
                console.log("CATEGORY PRABAYAR FOUND LOADED");

                renderCategories(categoriesData);
                console.log("CATEGORY PRABAYAR RENDERED");
                $("#loading-categories").hide();
                
            }
        }

    }

    function loadOperators() {
        let category_id = $("#category_id").val();
        let type = $("#ppob-type").val();
        fetchData("get_operators", { type, category_id }, function (response) {
            let options = "";
            response.data.forEach(operator => {
                options += `<option value="${operator.id}">${operator.product_name ?? operator.name}</option>`;
            });
            $("#operator_id").html(options);
            //save operators data to local storage
            localStorage.setItem("operators", JSON.stringify(response.data));
            loadProducts();
        });
    }
    //prefix for phone number is available in operators attribute, pick operator_id which matcehs with prefix, prefix in operator is like this "0856,0857,0858,0815,0816,0855,0814", and also "085154,085155,085156,085157"
    function checkNumberPrefix() {
        let phone_number = $("#phone_number").val().trim();

        let operators = JSON.parse(localStorage.getItem("operators")) || [];
        let prefix = phone_number.length >= 4 ? phone_number.substr(0, 4) : "";
        let prefix2 = phone_number.length >= 5 ? phone_number.substr(0, 5) : "";

        // Prioritize prefix2 (5 digits), fallback to prefix (4 digits)
        let operator = operators.find(op => op.prefix.includes(prefix2)) ||
            operators.find(op => op.prefix.includes(prefix));

        if (operator) {
            $("#operator_id").val(operator.id);
            loadProducts();
        } else {
            alert("Prefix nomor telepon tidak ditemukan.");
        }
    }
    // check prefix if user was inputed until 7digits
    $("#phone_number").on("input", function () {
        let phone_number = $("#phone_number").val();
        if (phone_number.length >= 7) {
            checkNumberPrefix();
        }
    });


    function renderProducts(products) {
        let html = products.length
            ? products.map(product => `
                <div class="product-card" style="
                    background: #fff3e0; 
                    border: 1px solid #ff9800; 
                    border-radius: 8px; 
                    padding: 10px; 
                    text-align: center; 
                    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1); 
                    transition: transform 0.2s; 
                    position: relative;
                    display: flex; 
                    flex-direction: column;
                    min-height: 100px; /* Ensure uniform height */
                ">
                    
                    <button class="info-button" data-desc="${product.desc ?? 'Tidak ada deskripsi'}" style="
                        background: transparent; 
                        padding: 0;
                        border: none; 
                        font-size: 16px; 
                        cursor: pointer; 
                        position: absolute; 
                        top: 0px; 
                        right: 0px;">ℹ️</button>
                    
                    <div class="product-content" style="display: flex; flex-direction: column; align-items: center; flex-grow: 1;">
                        <h4 class="product-title" style="
                            font-size: 14px; 
                            font-weight: bold; 
                            color: #ff5722; 
                            margin: 5px 0;">${product.product_name ?? product.name}</h4>
                        
                        <p class="product-price" style="font-size: 18px; color: #333;">
                            Rp. ${Number(product.price ?? 0) + Number(product.markup ?? 0)}
                        </p>
                        
                        <span class="product-points" style="font-size: 12px; color: #ff9800; margin-bottom: 5px;">
                            🏆 ${product.point_level ?? 0} Points
                        </span>
                        
                        <button class="beli-button" data-code="${product.code}" data-name="${product.product_name ?? product.name}" data-price="${product.price ?? 0}" data-markup="${product.markup ?? 0}" 
                        data-productId="${product.product_id}" style="
                            width: 100%;
                            background: #ff9800; 
                            color: white; 
                            border: none; 
                            padding: 6px 12px; 
                            font-size: 12px; 
                            cursor: pointer; 
                            border-radius: 4px;
                            margin-top: auto; /* Ensures button stays at bottom */
                        ">
                            Beli
                        </button>
                    </div>
                </div>
            `).join("")
            : "<p style='text-align: center; color: red;'>Produk tidak ditemukan.</p>";

        document.getElementById("productList").innerHTML = html;
    }

    function loadProducts() {
        let category_id = $("#category_id").val();
        let operator_id = $("#operator_id").val();
        let type = $("#ppob-type").val();

        fetchData("get_products", { type, category_id, operator_id }, function (response) {
            if (type === "prabayar") {
                renderProducts(response.data);
            }
        });
    }



    function addToCart(button) {
        if ($("#phone_number").val() === "") {
            alert("Nomor telepon tidak boleh kosong");
            return;
        }

        if ($("#phone_number").val().length < 7) {
            alert("Nomor telepon tidak valid");
            return;
        }

        if ($("#meter_number").val() === "") {
            alert("Nomor meter tidak boleh kosong");
            return;
        }



        let productCode = button.data("code");
        let productName = button.data("name");
        let finalPrice = (parseFloat(button.data("price")) || 0) + (parseFloat(button.data("markup")) || 0);
        let productId = button.data("productid");
        console.log(button.data());
        button.prop("disabled", true).text("Menambahkan...");
        $.ajax({
            type: "POST",
            url: PPOBTripay.ajax_url,
            data: {
                action: "ppob_add_to_cart",
                product_code: productCode,
            },
            dataType: "json",
            success: function (response) {

                if (response.success) {
                    $("#selected-product-name").text(productName);
                    $("#selected-product-price").text("Rp. " + finalPrice);
                    $("#selected-phone-number").text($("#phone_number").val());
                    $("#selected-product-code").val(productCode);
                    $("#selected-product-id").val(productId);
                    $("#selected-meter-number").text($("#meter_number").val());
                    //go to #quick-checkout-form
                    $('html, body').animate({
                        scrollTop: $("#quick-checkout-form").offset().top
                    }, 1000);
                }


            },
            error: function (error) {
                alert(JSON.stringify(error));
            },
            complete: function () {
                button.prop("disabled", false).text("Beli");
            }
        });

    }

    // Event Listeners
    $(".tab-btn").on("click", function () {
        $(".tab-btn").removeClass("active text-blue-500");
        $(this).addClass("active text-blue-500");
        loadCategories();
    });

    $("#operator_id").on("change", loadProducts);

    $(document).on("click", ".beli-button", function () {
        addToCart($(this));
    });

    $(document).on("click", ".info-button", function () {
        let description = $(this).data("desc") || "Tidak ada deskripsi";
        $("#modalContent").text(description);
        $("#descModal").fadeIn();
    });

    // Close Description Modal
    $("#closeModal").click(function () {
        $("#descModal").fadeOut();
    });

    // Close when clicking outside the modal content
    $("#descModal").click(function (event) {
        if ($(event.target).is("#descModal")) {
            $("#descModal").fadeOut();
        }
    });

    // Initial Load
    loadCategories();
    if ($("#category_id").val()) {
        loadOperators();
    }

    $('#checkBillPrabayar').click(function (event) {
        $("#billModalContent").css('display', 'flex').html("<p>Loading...</p>"); // Show loading text
        $("#billModal").fadeIn(); // Show modal immediately

        $.ajax({
            type: "POST",
            url: PPOBTripay.ajax_url,
            data: {
                action: "check_pln_user_prabayar",
                no_meter_pln: $("#meter_number").val()
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#billModalContent").html(`
                        <div style="display: flex; flex-direction: column; gap: 10px; text-align: left;">
                            <div><strong>Nomor Meter:</strong> ${response.data.meter_number}</div>
                            <div><strong>ID Pelanggan:</strong> ${response.data.subscriber_id}</div>
                            <div><strong>Nama Pelanggan:</strong> ${response.data.subscriber_name}</div>
                            <div><strong>Daya:</strong> ${response.data.segment_power}</div>
                        </div>
                    `);
                } else {
                    $("#billModalContent").html("<p>Data tidak ditemukan.</p>");
                }
            },
            error: function (error) {
                $("#billModalContent").html("<p>Terjadi kesalahan saat mengambil data.</p>");
            }
        });
    });

    // $("#closeBillModal, #billModal").click(function (event) {
    //     if (event.target.id === "billModal" || event.target.id === "closeBillModal") {
    //         $("#billModal").fadeOut();
    //     }
    // });

    function openModal(modalId) {
        $(modalId).fadeIn();
    }

    function closeModal(modalId) {
        $(modalId).fadeOut();
    }

    // Open description modal
    // $('.info-button').on('click', function () {
    //     var content = $(this).data('desc'); // Get content from data attribute
    //     $('#modalContent').text(content);
    //     openModal('#descModal');
    // });

    // // Close description modal
    // $('#closeModal, #descModal').on('click', function (e) {
    //     if (e.target === this) {
    //         closeModal('#descModal');
    //     }
    // });

    // Open bill modal
    $('.openBillModal').on('click', function () {
        var content = $(this).data('content'); // Get bill content
        $('#billModalContent').text(content);
        openModal('#billModal');
    });

    // Close bill modal
    $('#closeBillModal, #billModal').on('click', function (e) {
        if (e.target === this) {
            closeModal('#billModal');
        }
    });
});