jQuery(document).ready(function ($) {
    function showLoading() {
        $("#ppob-loading").show();
    }

    function hideLoading() {
        $("#ppob-loading").hide();
    }
    function fetchCategoriesOption() {
        $("ppob-providers-select").prop("disabled", true);
        showLoading();
        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_sync_categories",
                nonce: ppob_tripay_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    var select = $("#ppob-category-select");
                    select.empty(); // Kosongkan dropdown sebelum menambahkan kategori baru

                    // Ambil category_id dari localStorage
                    var category_id = localStorage.getItem("category_id");

                    $.each(response.data, function (index, category) {
                        var option = $("<option></option>")
                            .attr("value", category.id)
                            .text(category.product_name);

                        if (category.id == category_id) {
                            option.attr("selected", "selected");
                        }

                        select.append(option);
                    });

                    // Perbaiki selector dengan ID yang benar
                    $("#ppob-providers-select").prop("disabled", false);

                    // Simpan category_id ke localStorage hanya jika belum ada
                    if (!category_id) {
                        localStorage.setItem("category_id", response.data[0].id);
                    }

                    fetchProvidersOption();
                } else {
                    $("#ppob-category-select").html("<option value=''>Kategori tidak ditemukan</option>");
                    $("ppob-providers-select").prop("disabled", false);
                }
                hideLoading();
            },
            error: function () {
                $("ppob-providers-select").prop("disabled", true);
                $("#ppob-category-select").html("<option value=''>No Categories Found</option>");
                hideLoading();
            }
        });

    }

    function fetchProvidersOption() {
        showLoading();
        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_sync_operators",
                category_id: $("#ppob-category-select").val(),
                nonce: ppob_tripay_ajax.nonce
            },
            success: function (response) {

                if (response.success) {
                    var select = $("#ppob-providers-select");
                    select.empty(); 

                    var operator_id = localStorage.getItem("operator_id");

                    $.each(response.data, function (index, provider) {
                        var option = $("<option></option>")
                            .attr("value", provider.id)
                            .text(provider.product_name);


                        if (provider.id == operator_id) {
                            option.attr("selected", "selected");
                        }

                        select.append(option);
                    });


                    $("#ppob-providers-select").prop("disabled", false);

                    
                    if (!operator_id) {
                        localStorage.setItem("operator_id", response.data[0].id);
                    }

                    fetchProductsByOperator();
                } else {
                    $("#ppob-provider-select").html("<option value=''>Kategori tidak ditemukan</option>");
                }
                hideLoading();
            },
            error: function () {
                $("#ppob-category-select").html("<option value=''>No Categories Found</option>");
                $("ppob-providers-select").prop("disabled", false);
                hideLoading();
            }
        });

    }

    function sync_products(button) {

        button.prop("disabled", true).text("Syncing...");

        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_sync_products",
                category_id: $("#ppob-category-select").val(),
                operator_id: $("#ppob-providers-select").val(),
                nonce: ppob_tripay_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    // alert(JSON.stringify(response));
                    $("#sync-result").html(
                        `<p style="color: green;">✅ Sync Successfuly!</p>`
                    );
                    button.prop("disabled", false).text("Sync Again");
                    fetchProductsByOperator();
                } else {
                    $("#sync-result").html(
                        `<p style="color: red;">❌ Sync gagal!</p>`
                    );
                    button.prop("disabled", false).text("Sync Again");
                }
            }
        });
    }

    function fetchProductsByOperator() {
        var tableBody = $("#ppob-products-list");
        tableBody.empty(); // Kosongkan tabel sebelum menambahkan data baru

        showLoading();
        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_get_products",
                operator_id: $("#ppob-providers-select").val(),
                category_id: $("#ppob-category-select").val(),
                nonce: ppob_tripay_ajax.nonce
            },
            success: function (response) {
                hideLoading();

                if (response.success) {
                    $.each(response.data, function (index, product) {
                        tableBody.append(`
                            <tr>
                                <td class="code_tripay">${product.code}</td>
                                <td>${product.name}</td>
                                
                                <td>Rp. ${product.price}</td>

                                <td>
                                    <input name="markup[]" type="number" value="${product.markup}" class="markup-input" disabled>
                                </td>
                                <td>
                                    <input name="point_level[]" type="number" value="${product.point_level}" class="point-input" disabled>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tableBody.html("<tr><td colspan='6'>Produk tidak ditemukan, silahkan coba lakukan Sync</td></tr>");
                }

                $("#ppob-edit-products").text("Bulk Edit Product");
                $("#ppob-edit-products-apply").hide();
                $(".markup-input").prop("disabled", true);
                $(".point-input").prop("disabled", true);

            },
            error: function () {
                tableBody.html("<tr><td colspan='6'>Gagal mengambil data</td></tr>");
                hideLoading();
            }
        });
    }

    function fetchBalances() {

        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_get_balances",
                nonce: ppob_tripay_ajax.nonce
            },
            success: function (response) {

                if (response.success) {
                    $("#saldo").text(response.data);
                } else {
                    $("#saldo").text("Gagal mengambil data");
                }
            },
            error: function () {
                $("#saldo").text("Gagal mengambil data");
            }
        });
    }

    $("#ppob-category-select").change(function () {

        $("#ppob-providers-select").empty();
        $("#ppob-providers-select").append("<option value=''>Loading...</option>");
        // save to localStorage
        localStorage.setItem("category_id", $(this).val());
        fetchProvidersOption();
    });

    $("#ppob-providers-select").change(function () {
        // save to local storage
        localStorage.setItem("operator_id", $(this).val());
        fetchProductsByOperator();

    });
    $("#ppob-sync-products").click(function () {
        sync_products($(this));
    });

    $("#ppob-show-products").click(function () {

        fetchProductsByOperator();
    });

    //create toggle to enable & Disable all input element inside table
    $("#ppob-edit-products").click(function () {
        let isEnabled = $(".markup-input").prop("disabled");

        if (isEnabled) {
            $(this).text("Cancel Bulk Edit");
            $("#ppob-edit-products-apply").show();
            $(".markup-input").prop("disabled", false);
            $(".point-input").prop("disabled", false);
        }
        else {
            $(this).text("Bulk Edit Product");
            $("#ppob-edit-products-apply").hide();
            $(".markup-input").prop("disabled", true);
            $(".point-input").prop("disabled", true);
        }

    });

    function applyBulkEdit() {
        var products = [];
        $("#ppob-products-list tr").each(function () {
            var product = {
                code: $(this).find(".code_tripay").text(),
                markup: $(this).find(".markup-input").val(),
                point_level: $(this).find(".point-input").val()
            };
            products.push(product);
        });

        $.ajax({
            url: ppob_tripay_ajax.ajax_url,
            type: "POST",
            data: {
                action: "ppob_tripay_bulk_edit_product",
                nonce: ppob_tripay_ajax.nonce,
                products: products
            },
            success: function (response) {
                if (response.success) {
                    alert("Products updated successfully");
                    fetchProductsByOperator();
                    $("#ppob-edit-products").text("Bulk Edit Product");
                    $("#ppob-edit-products-apply").hide();
                    $(".markup-input").prop("disabled", true);
                    $(".point-input").prop("disabled", true);

                } else {
                    alert("Failed to update products: " + response.message);
                }
            },
            error: function () {
                alert("An error occurred while updating products");
            }
        });
    }

    $("#ppob-edit-products-apply").click(function () {
        applyBulkEdit();
    });
    fetchCategoriesOption();
    fetchBalances();

});
