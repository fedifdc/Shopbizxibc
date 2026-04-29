jQuery(document).ready(function($) {
    $('#masthead').addClass('active');
    adminBar();
    $('mark.order-number').parent().addClass('custom-order-status');
    // $(document).foundation();
    var prevBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M12.5918 2.43164L5.02344 10L12.5918 17.5684L10.2383 19.9316L0.306641 10L10.2383 0.0683594L12.5918 2.43164Z" fill="#C0C0C0"/></svg>';
    var nextBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M2.76172 0.0683594L12.6934 10L2.76172 19.9316L0.408203 17.5684L7.97656 10L0.408203 2.43164L2.76172 0.0683594Z" fill="#C0C0C0"/></svg>';
    // trigger by event
    // $('a.reveal-link').trigger('click');
    // $('a.close-reveal-modal').trigger('click');

    setTimeout(function() {
        if ( _cepatlakoo_cart.cl_cart_session == 'true' && $('body').hasClass('woocommerce-cart') == false && $('body').hasClass('woocommerce-checkout') == false ) {
            // $(".cl-modal-cart").show();
            loaderSidebarAdd();
            triggerSidebar();
            loaderSidebarClose();
        }
    }, 1000);

    if( _cepatlakoo_cart.cl_cart_elementor == 'no' ){
        $('.elementor-menu-cart__main').remove();
    }

    $(".cl-modal-cart").hide();
    
    $(document).on('click', ".cl-modal-cart .close-cart", function() {
        $(".cl-modal-cart").hide();
    });

    
    //---------------- Add Boostrap table & select class ----------------//
    $('table').addClass('table');
    $('table>thead>tr>th').attr('scope','col');
    $('table>tbody>tr>th').attr('scope','row');
    $('.widget select').addClass('form-select');

    jQuery.ajax({
        type: "POST",
        url: wc_ajax.ajax_url,
        data: {
            'action': 'cepatlakoo_increase_view',
            'nonce' : wc_ajax.nonce,
            'postid' : wc_ajax.postid
        },
        success:function(resp) {
            if( resp.status ){
                if( resp.data.view != null )
                    jQuery('.cl-view-value').html(resp.data.view+'x');
                if( resp.data.sold != null )
                    jQuery('.cl-sold-value').html(resp.data.sold);
            }
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });

    /*
     * Function for filter price pixel
     * @param price : price pixel 
     * return price
     */
    function filterPricePixel(pricetag) {
        pricetag = pricetag.replace("Rp", "");
        if (pricetag.slice(-3) == '.00') {
            pricetag = pricetag.replace(".00", "");
        } else if (pricetag.slice(-3) == ',00') {
            pricetag = pricetag.replace(",00", "");
        }

        pricetag = Number(pricetag.replace(/[^0-9-]+/g,""));

        return pricetag;
    }
    /*
     * Function for process background color to rgba
     * return function()
     */
    $.cssHooks.backgroundColor = {
        get: function(elem) {
            if (elem.currentStyle)
                var bg = elem.currentStyle["backgroundColor"];
            else if (window.getComputedStyle)
                var bg = document.defaultView.getComputedStyle(elem,
                    null).getPropertyValue("background-color");
            if (bg.search("rgb") == -1)
                return bg;
            else {
                bg = bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

                function hex(x) {
                    return ("0" + parseInt(x).toString(16)).slice(-2);
                }
                if (bg) {
                    return "#" + hex(bg[1]) + hex(bg[2]) + hex(bg[3]);
                } else {
                    return "#000";
                }
            }
        }
    }

    /*
     * Function for set height header
     * return function()
     */
    function adminBar() {
        var adminbar = $('#wpadminbar').outerHeight(),
            winHeight = $(window).outerHeight(),
            footer = $('#colofon').outerHeight(),
            masthead = $('#masthead').outerHeight(),
            demo = $('.demo_store').outerHeight(),
            menu = masthead + topBarHeight + demo + adminbar,
            minHeight = winHeight - (menu + footer),
            mastStyle = {
                top: demo
            }

        if ($("#wpadminbar").length > 0) {
            $('body, html').addClass('admin-bar');
        }
        $('#masthead').css(mastStyle);

        $().css('margin-top', '0 !important');

        $('#maincontent').css('min-height', minHeight);
        $('body').css('padding-top', masthead)

        // console.log(menu+'px')
    }

    function sliderAutoHeight() {
        $('.cepatlakoo-fullwidth').each(function() {
            var parentOne = $(this).parents('.elementor-element');
                parentTwo = $(this).parents('.elementor-container');
                mastherSliderHeight = $(this).parents('.elementor-container').css('min-height');

            if (parentOne.hasClass('elementor-section-height-min-height')) {
                $(this).parent().addClass('minHeight');
                $(this).css('min-height', mastherSliderHeight);
            }
        })
    }

    /*
     * Function for get pricetag
     * return string
     */
    function cl_get_pricetag(selector, type) {
        var pricetag = type.parents('ins .woocommerce-Price-amount').text();

        if (type.parents('body').find('div.product').hasClass('product-type-variable')) {
            pricetag = type.find(' .woocommerce-variation-price .price ins .woocommerce-Price-amount').text(); //check if sale
            if (!pricetag) { // if empty
                pricetag = type.find(' .woocommerce-variation-price .price .woocommerce-Price-amount').text(); // get regular price
                if (!pricetag) { // if empty
                    pricetag = type.find(' ins .woocommerce-Price-amount').text(); // get sale price
                    if (!pricetag) { // if empty
                        pricetag = type.find(' .woocommerce-Price-amount').text(); // get regular price
                    }
                }
            }
        } else if (type.parents('body').find('div.product').hasClass('sale') && !type.hasClass('woocommerce-grouped-product-list-item')) {
            pricetag = type.find(' .price ins .woocommerce-Price-amount').text();
        } else if ( type.hasClass('woocommerce-grouped-product-list-item') ) {
            if(type.hasClass('sale')){
                pricetag = type.find(' ins .woocommerce-Price-amount').text();
            }
            else{
                pricetag = type.find(' .woocommerce-Price-amount').text();
            }
        } else {
            if (!pricetag) {
                pricetag = type.find(' .woocommerce-Price-amount').text();
            } else {
                pricetag = type.find(' ins .woocommerce-Price-amount').text();
            }
        }
        return pricetag;
    }

    /*
     * Function for get product ID
     * return integer
     */
    function cl_get_productID(selector, type) {
        if (selector.parents('.mfp-content').length) { // Quick View
            var idProd = selector.parents('.mfp-content .shop-detail-custom').find('div.product').attr('id');
            idProd = idProd.replace("product-", "");
        } else if ($('body').hasClass('elementor-page') && selector.parents('.shop-detail-custom').length > 0 ) { // Elementor
            var idProd = selector.parents('.shop-detail-custom').find('div.product').attr('id');
            idProd = idProd.replace("product-", "");
        }else if( selector.hasClass('ajax_add_to_cart') ){
            idProd = selector.attr('data-product_id');
        }else if( selector.siblings('input[name="product_id"]').length > 0 ){
            idProd = selector.siblings('input[name="product_id"]').val();
        } else {
            var idProd = selector.parents('.product-list').find('div.product').attr('id');
            idProd = idProd.replace("product-", "");
        }

        return idProd;
    }

    function setCookie(cname, cvalue, exdays, formated_date=false) {
        if( formated_date ){
            var d = new Date(exdays);
        }
        else{
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        }
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function cl_countdown_get_cookies(name, value, timer){
        var cookies = getCookie(name);
        var old_timer = getCookie(name+'_timer');
        if (cookies != "" && old_timer === timer) {
            return cookies;
        } else {
            setCookie(name, value, 360);
            setCookie(name+'_timer', timer, 360);
            return value;
        }
    }

    /*
     * Function to get the last messenger icon as a sticky
     * return function()
     */

    /*
     * Function to make throttle function
     * return function()
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    /*
     * Function for Close Sidebar Cart
     * return function()
     */
    function loaderSidebarClose() {
        setTimeout(function() {
            if ($('body').hasClass('woocommerce-checkout')) {
                $(document.body).trigger('update_checkout');
            }
            $('.loader-sidebar-cart').remove();
        }, 1000);
    }

    /*
     * Function for Load Sidebar Cart
     * return function()
     */
    function loaderSidebarAdd() {
        $('.loader-sidebar-cart').remove();
        $('.cart-holders').append('<div class="loader-sidebar-cart"><img src="' + wc_ajax.cepatlakoo_path + '/assets/images/loader.gif" /></div>');
        if (_warrior.cart_redirection == 'yes' && _cepatlakoo_cart.cl_cart_style == 'sidebar') {
            $('body').removeClass('cart-opened');
        }
    }

    /*
     * Function to trigger sidebar
     * return function()
     */
    function triggerSidebar(refresh = true) {
        if( refresh )
            $( document.body ).trigger( 'wc_fragment_refresh' );

        if( _cepatlakoo_cart.cl_cart_style == 'sidebar' && _cepatlakoo_cart.cl_cart_elementor == 'no' ){
            // if (_warrior.cart_redirection == 'no' && $('#main-header .user-carts').length > 0) {
            if (_warrior.cart_redirection == 'no') {
                $('body').addClass('cart-opened');
            }
        }
        else if( _cepatlakoo_cart.cl_cart_elementor == 'yes' && $('#elementor-menu-cart__toggle_button').length > 0 ){
            $('#elementor-menu-cart__toggle_button').click();
        }
        else{
            $(".cl-modal-cart").show();
        }

        localStorage.addToCart = "0";
    }

    /*
     * Function to Get Paramater by Name in Query String
     * @param name : string
     * @param url : url
     * return value query string
     */
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    /*
     * Function to Call Light Slider
     * return function()
     */
    function CallLightSlider() {
        $(".woocommerce  div.product > .images > .thumbnails-slider").addClass('owl-carousel').owlCarousel({
            items: 1,
            loop: false,
            margin: 0,
            nav: true,
            dots: false,
            singleItem: true,
            thumbs: true,
            thumbsPrerendered: true,
            autoHeight: true,
            navText: [prevBtnIcon, nextBtnIcon]
        });

        $(".woocommerce  #main div.product > .images > .thumbnails-slider").addClass('owl-carousel').owlCarousel({
            items: 1,
            loop: false,
            margin: 0,
            nav: true,
            dots: false,
            singleItem: true,
            thumbs: true,
            thumbsPrerendered: true,
            autoHeight: true,
            navText: [prevBtnIcon, nextBtnIcon]
        });

        $('button.owl-thumb-item').each(function() {
            if ($(this).text() == 'undefined') {
                $(this).text('');
            }
        })
    }

    function GetURLParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) 
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) 
            {
                return sParameterName[1];
            }
        }
    }

    function cl_check_confirm_data() {
        var nameSelector = ( jQuery("form.frm-fluent-form.payment-confirmation-form").length > 0 ) ? 'form.frm-fluent-form.payment-confirmation-form input[name="full_name"]' : '#fld_9014900_1';
        var orderidSelector = ( jQuery("form.frm-fluent-form.payment-confirmation-form").length > 0 ) ? 'form.frm-fluent-form.payment-confirmation-form input[name="kode_pesanan"]' : '#fld_3826030_1';
        var emailSelector = ( jQuery("form.frm-fluent-form.payment-confirmation-form").length > 0 ) ? 'form.frm-fluent-form.payment-confirmation-form input[name="email"]' : '#fld_8004623_1';
        var amountSelector = ( jQuery("form.frm-fluent-form.payment-confirmation-form").length > 0 ) ? 'form.frm-fluent-form.payment-confirmation-form input[name="jumlah"]' : '#fld_4251538_1';

        jQuery.ajax({
            type: "POST",
            url: wc_ajax.ajax_url,
            data: {
                'action': 'cl_check_confirm_data',
                'nonce' : wc_ajax.nonce,
                'code' : jQuery(orderidSelector).val(),
                'email' : jQuery(emailSelector).val()
            },
            success:function(resp) {
                if( resp.status ){
                    jQuery(nameSelector).val(resp.data.name);
                    jQuery(amountSelector).val(resp.data.total);
                }
                // console.log(resp);
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }

    //-------------------------- INIT ----------------------------------//
    var stat_select = 0;
    var headerHeight = $("#masthead").outerHeight();
    var adminBarHeight = ( $("#wpadminbar").length > 0 ) ? $("#wpadminbar").outerHeight() : 0;
    var topBarHeight = $("#top-bar").outerHeight();
    var footerHeight = $("#footer-info").outerHeight();
    var windowHeight = $(window).height();
    $('.vertical-nav ul li.has-child').append('<div class="dd-trigger"><i class="icon icon-angle-down"></i></div>');

    //-------------------------- INIT ----------------------------------//
    $(document).on('click', ".quantity-up", function() {
        /*
         * Listener Edited : 11/08/2017 10:30
         * Listener Used : Listener used for listen clicking up and adding product
         * Listener Page : Woocommerce Page
         * Listener Scope : Spesific
         */
        var input = $(this).parents().siblings('input[type="number"]');
        var min = input.attr('min');
        var max = input.attr('max');
        var oldValue = parseFloat(input.val());
        var newVal = oldValue + 1;
        if (max) {
            if (newVal > max) {
                newVal = max;
            }
        }

        $(this).parents().siblings("input:not([name='product_id'],[name='add-to-cart'],[name='variation_id'])").val(newVal);
        $(this).parents().siblings("input").trigger("change");
    });

    $(document).on('click', ".quantity-down", function() {
        /*
         * Listener Edited : 11/08/2017 10:30
         * Listener Used : Listener used for listen clicking down and adding product
         * Listener Page : Woocommerce Page
         * Listener Scope : Spesific
         */

        var input = $(this).parents().siblings('input[type="number"]');
        var min = input.attr('min');
        var max = input.attr('max');
        var oldValue = parseFloat(input.val());
        if (oldValue > 1) {
            var newVal = oldValue - 1;
        } else {
            newVal = 1;
        }
        if (max) {
            if (newVal > max) {
                newVal = max;
            }
        }

        $(this).parents().siblings("input:not([name='product_id'],[name='add-to-cart'],[name='variation_id'])").val(newVal);
        $(this).parents().siblings("input").trigger("change");
    });

    $(document).on('click', ".mfp-container .single_add_to_cart_button", function(e) {
        /* Listener Edited : 10/08/2017 15:22
         * Listener Used : Listener used for listening click and adding product with ajax in quick view add to cart
         * Listener Page : Quickview Listener
         * Listener Scope : Spesific
         */
        if (_warrior.cart_redirection == 'no') {
            e.preventDefault();
            // var product_id = $(this).siblings('input[name="product_id"]').val();
            var product_id = $(this).attr('value');
            var variation_id = $(this).siblings('input[name="variation_id"]').val();
            var quantity = ( $(this).siblings('.quantity').length > 0 ) ? $(this).siblings('.quantity').find('input[name="quantity"]').val() : 1;
            var postData = $(this).parents('form').find('input:not([name="product_id"]):not([name="variation_id"]):not([name="quantity"]):not([name="add-to-cart"]), textarea, select').serialize();
            $('.cart-dropdown-inner').empty();
            var variation = [];

            $('.variations select').each(function() {
                var attribName = $(this).attr('name'); // name attribute
                var atrribValue = $(this).val();
                variation.push(attribName + ',' + atrribValue);
            })

            if (variation_id != '' && variation_id != undefined) {
                var product_id = $(this).siblings('input[name="product_id"]').val();
                $.ajax({
                    async: true,
                    url: wc_ajax.ajax_url,
                    type: 'POST',
                    data: postData + '&action=cepatlakoo_wc_add_cart&product_id=' + variation_id + '&variation_id=' + variation_id + '&quantity=' + quantity + '&variation_data=' + variation,
                    // data: postData + 'action=cepatlakoo_wc_add_cart',

                    success: function(results) {
                        var magnificPopup = $.magnificPopup.instance;
                        magnificPopup.close();
                        loaderSidebarAdd();
                        // $(document.body).trigger('wc_fragment_refresh');
                        triggerSidebar();
                        loaderSidebarClose();
                    }
                });
            } else {

                $.ajax({
                    async: true,
                    url: wc_ajax.ajax_url,
                    type: 'POST',
                    data: 'action=cepatlakoo_wc_add_cart&product_id=' + product_id + '&quantity=' + quantity,

                    success: function(results) {
                        var magnificPopup = $.magnificPopup.instance;
                        magnificPopup.close();
                        loaderSidebarAdd();
                        // $(document.body).trigger('wc_fragment_refresh');
                        triggerSidebar();
                        loaderSidebarClose();
                    }
                });
            }
        }
    });

    $(document).on('click', ".single_add_to_cart_button", function(e) {
        /* Listener Edited : 10/08/2017 15:22
         * Listener Used : Listener used for adding product with ajax
         * Listener Page : Single Product, Elementor Single Product Highlight
         * Listener Scope : Spesific
         */
        if( !$(this).closest(".mfp-container").length ){
            var stateProduct = $(this).closest('div.product');
            if (!stateProduct.hasClass('product-type-external') && !stateProduct.hasClass('product-type-grouped')) { //check affiliate product
                if (_warrior.cart_redirection == 'no') {
                    e.preventDefault();
                    var product_id = $(this).siblings('input[name="product_id"]').val();
                    if (!product_id) {
                        product_id = $(this).val();
                    }
                    var variation_id = $(this).siblings('input[name="variation_id"]').val();
                    var quantity = ( $(this).siblings('.quantity').length > 0 ) ? $(this).siblings('.quantity').find('input[name="quantity"]').val() : 1;
                    var postData = $(this).parents('form').find('input:not([name="product_id"]):not([name="variation_id"]):not([name="quantity"]):not([name="add-to-cart"]), textarea, select').serialize();
                    var variation = [];
    
                    $('.variations select').each(function() {
                        var attribName = $(this).attr('name'); // name attribute
                        var atrribValue = $(this).val();
                        variation.push(attribName + ',' + atrribValue);
                    })
                    $('.cart-dropdown-inner').empty();
    
                    if (variation_id != '' && variation_id != undefined) {
                        // IF Not Variable Product
                        // console.log(variation_id);
                        $.ajax({
                            async: true,
                            url: wc_ajax.ajax_url,
                            type: 'POST',
                            data: postData + '&action=cepatlakoo_wc_add_cart&product_id=' + variation_id + '&variation_id=' + variation_id + '&quantity=' + quantity + '&variation_data=' + variation,
    
                            success: function(results) {
                                loaderSidebarAdd();
                                // $(document.body).trigger('wc_fragment_refresh');
                                triggerSidebar();
                                loaderSidebarClose();
                            }
                        });
                    } else {
                        // IF Variable Product
                        $.ajax({
                            async: true,
                            url: wc_ajax.ajax_url,
                            type: 'POST',
                            data: postData + '&action=cepatlakoo_wc_add_cart&product_id=' + product_id + '&quantity=' + quantity,
    
                            success: function(results) {
                                loaderSidebarAdd();
                                // $(document.body).trigger('wc_fragment_refresh');
                                triggerSidebar();
                                loaderSidebarClose();
                            }
                        });
                    }
                }
            }
            if (stateProduct.hasClass('product-type-grouped')) { //check affiliate product
                var cartID = [];
    
                $('table.group_table input').each(function() {
                    if ($(this).val() != 0) {
                        e.preventDefault();
                        var product_id = $(this).attr('name');
                        product_id = product_id.replace("quantity[", "");
                        product_id = product_id.replace("]", "");
                        var dataCollect = product_id + 'q' + $(this).val();
                        cartID.push(dataCollect);
                    } else {
                        localStorage.addToCart = "0";
                    }
                })
    
                $.ajax({
                    async: true,
                    url: wc_ajax.ajax_url,
                    type: 'POST',
                    data: 'action=cepatlakoo_wc_add_cart&data_product=' + cartID + '&submit_type=multiple',
    
                    success: function(results) {
                        loaderSidebarAdd();
                        // $(document.body).trigger('wc_fragment_refresh');
                        triggerSidebar();
                        loaderSidebarClose();
                    }
                });
            }
        }
    });

    $(document).on('change', ".woocommerce-mini-cart .quantity input", debounce(function() {
        /* Listener Edited : 10/08/2017 15:54
         * Listener Used : Listener used for listen quantity updated with debounce 0.8s
         * Listener Page : Sidebar Panel Cart / Whole Page
         * Listener Scope : Spesific
         */

        var product_key = $(this).attr('name');
        var product_key = product_key.match(/\[(.*)\]\[/i)[1];

        $.ajax({
            async: true,
            url: wc_ajax.ajax_url,
            type: 'POST',
            data: 'action=update_item_cart&product_id=' + product_key + '&quantity=' + $(this).val(),

            success: function(results) {
                loaderSidebarAdd(); //Call Sidebar
                // $(document.body).trigger('wc_fragment_refresh');
                triggerSidebar();
                loaderSidebarClose(); //Close Sidebar
            }
        });
    }, 800));

    $(document).on('click', ".mini_cart_item a.remove", function(e) {
        /* Listener Edited : 10/08/2017 15:54
         * Listener Used : Listener used for listen user remove product
         * Listener Page : Sidebar Panel Cart / Whole Page
         * Listener Scope : Spesific
         */

        e.preventDefault();
        var product_key = $(this).attr('href');
        product_key = getParameterByName('remove_item', product_key);

        jQuery.ajax({
            async: true,
            url: wc_ajax.ajax_url,
            type: 'POST',
            data: 'action=my_wc_remove_product&product_id=' + product_key,

            success: function(results) {
                loaderSidebarAdd();
                // $(document.body).trigger('wc_fragment_refresh');
                triggerSidebar();
                loaderSidebarClose();
            }
        });
        return false;
    });

    $(document).on('click', ".quick-contact-info a.whatsapp, .quick-contact-info a.telegram, .contact-wa-button, .quick-contact-info a.blackberry, .quick-contact-info a.sms, .quick-contact-info a.phone, .quick-contact-info a.line, .sticky-button a, a.fpa-whatsapp-btn", function(e) {
        /*
         * Listener Edited : 10/08/2017 15:54
         * Listener Used : Listener used for listening user click custom button wa and siplay modal
         * Listener Page : Elementor Page
         * Listener Scope : Global :: Elementor Cepatlakoo Messenger Button
         */
        var pixels = $(this).attr("fb-pixel");
        var select = $(this).attr("fb-select");
        var redirectUrl = $(this).attr("href");
        
        e.preventDefault();
        if (_fbpixel.fbpixel && pixels) {
            if( select == undefined || select == 'all' ){
                // fbq('track', pixels);
                cl_trigger_fbpixel_api(pixels);
            }
            else{
                // fbq('track', select, pixels);
                cl_trigger_fbpixel_api(select, pixels);
            }
        }
        setTimeout(function() {
            window.location.replace(redirectUrl);
        }, 500);
    });

    //----------------------------- Elementor Messenger Button -----------------------------//

    //----------------------------- GLOBAL -----------------------------//
    $(window).on('load', function() {
        adminBar();
        sliderAutoHeight();
    });
    $(window).on('resize', function() {
        setTimeout(function() {
            adminBar();
            sliderAutoHeight();
            console.log('resize');
        }, 500);
    });

    var cart_selector = (_cepatlakoo_cart.cl_cart_elementor == 'no') ? '.cart-counter, .elementor-menu-cart__toggle .elementor-button' : '.cart-counter';
    $(document).on('click', cart_selector, function(e) {
        /*
         * Listener Edited : 10/08/2017 16:04
         * Listener Used : Listener used for listening user click to button cart in topbar header
         * Listener Page : All Page in Top Header Button Cart
         * Listener Scope : Global
         */
        e.preventDefault();

        loaderSidebarAdd();
        // if (_warrior.cart_redirection == 'yes' || _cepatlakoo.cl_cart_style == 'popup') {
        if ( _cepatlakoo_cart.cl_cart_elementor == 'no' && (_warrior.cart_redirection == 'yes' || _cepatlakoo_cart.cl_cart_style == 'popup') ) {
            window.location.replace(_warrior.cart_url);
        }
        else {
            triggerSidebar();
        }
        loaderSidebarClose();
    });

    //----------------------------- GLOBAL -----------------------------//
    $(document).on('click', '.mfp-container a.add_to_cart_button', function() {
        /*
         * Listener Edited : 10/08/2017 16:04
         * Listener Used : Listener used for listening user click add to cart in Quick View
         * Listener Page : All Page in Quick View
         * Listener Scope : Spesific
         */

        var datEl = $(this);
        $(this).remove();
        $('.mfp-container .quantity').after(datEl); // Specific Selector, Cause Quick View Just Once in One Page
        $('.mfp-container .quantity').next('.add_to_cart_button').attr('data-quantity', $('input.qty').val());
        loaderSidebarAdd();
        triggerSidebar();
        loaderSidebarClose();
    });

    $(document).on('click', '#single-product-hightlight a.add_to_cart_button, #single-product-hightlight button.single_add_to_cart_button', function() {
        /*
         * Listener Edited : 10/08/2017 16:04
         * Listener Used : Listener used for listening user click add to cart and refresh the button for adding new quantity
         * Listener Page : Elementor Page : Elementor Single Product Highlight
         * Listener Scope : Spesific
         */

        var datEl = $(this);
        var datID = $(this).closest('.elementor-widget-cepatlakoo-single-product-highlight').attr('data-id');
        if ($(this).is('a')) {
            $(this).remove();
            $('div[data-id="' + datID + '"]').find('.quantity').after(datEl);
            $('div[data-id="' + datID + '"]').find('.quantity').next('.add_to_cart_button').attr('data-quantity', $('input.qty').val());
        } else {
            // Not Working
            $(this).remove();
            $('div[data-id="' + datID + '"]').find('.quantity').after(datEl);
            $('div[data-id="' + datID + '"]').find('.quantity').next('.add_to_cart_button').attr('data-quantity', $('input.qty').val());
        }
    });

    $(document).on('change', '.quantity input.qty', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for listening user change quantity input in
         * Listener Page : All Page have quantity
         * Listener Scope : Global
         */

        if ($(this).val() == 0) {
            $(this).val(1);
        }
        $(this).parent('.quantity').next('.add_to_cart_button').attr('data-quantity', $(this).val());
        $(this).data("quantity", $(this).val());

        if ($(this).attr('max')) {
            if (parseInt($(this).val()) > parseInt($(this).attr('max'))) {
                $(this).val($(this).attr('max')).trigger('change');
            }
        }
    });

    $(window).on('scroll', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for listen user scroll on website and set header with admin/ demo
         * Listener Page : All Page
         * Listener Scope : Global
         */

        var masthead = $('#masthead').outerHeight(),
            demo = $('.demo_store').outerHeight(),
            menu = masthead + demo;

        if ($(window).scrollTop() == 0) {
            setTimeout(function(){ adminBar(); }, 300);
        }
    });

    $(document).on('click', '#mp-close-click', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for listen user click close button magnificPopup
         * Listener Page : All Page
         * Listener Scope : Global
         */

        var magnificPopup = $.magnificPopup.instance;
        magnificPopup.close();
    });

    $(document).on('click', 'a.reset_variations', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for reset variation in wooocmmerce product
         * Listener Page : Woocommerce Page
         * Listener Scope : Spesific
         */

        // $(this).closest('form').find('.ui.dropdown').dropdown('restore defaults');
        $(this).closest('form').find('table.variations .size-select-widget ul li').removeClass("selected");
        $(this).closest('form').find('.single_variation_wrap').css('margin-top', '0');
    });

    $(document).on('change', '.entry-summary select', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for user change select variation and style single variation margin
         * Listener Page : Woocommerce Page
         * Listener Scope : Spesific
         */
        if( $('.woocommerce-variation-description').html() === ''){
            $('.single_variation_wrap').css('margin-top', '0px');
        }
        else{
            $('.single_variation_wrap').css('margin-top', '30px');
        }
    });

    $(document).on('click', "#single-product-hightlight a.ajax_add_to_cart", function() {
        /*
         * Listener Edited : 11/08/2017 10:45
         * Listener Used : Listener used for user click add to cart in single product highlight and display the notification
         * Listener Page : Elementor - Single Product Highlight
         * Listener Scope : Spesific
         */
        loaderSidebarAdd();
        triggerSidebar(false);
        loaderSidebarClose();
    });

    $(document).on('click', ".close-cart", function() {
        /*
         * Listener Edited : 11/08/2017 10:45
         * Listener Used : Listener used for user click remove sidebarcart
         * Listener Page : All Page
         * Listener Scope : Spesific
         */
        $('body').removeClass('cart-opened');
    });

    $(document).on('change', ".variations", function(e) {
        /*
         * Listener Edited : 11/08/2017 10:45
         * Listener Used : Listener used for change variations image
         * Listener Page : Woocommerce Page
         * Listener Scope : Spesific
         */
        var dataSelect = $(this).find("option:selected").index()
        $('.thumbnails-slider .owl-thumbs button:eq(0)').trigger('click');
    });

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > headerHeight) {
            $('#top-header, #mobile-menu, .cart-holders').addClass("sticky");
        } else {
            $('#top-header, #mobile-menu, .cart-holders').removeClass("sticky");
        }
    });

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > windowHeight) {
            $('#top-header, #mobile-menu').addClass("fixed");
        } else {
            $('#top-header, #mobile-menu').removeClass("fixed");
        }
    });

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > headerHeight+adminBarHeight) {
            $('#masthead').addClass("sticky");

            if( $('#wpadminbar').length > 0 ) {
                $('#masthead').addClass("adminbar-sticky");
                $('.demo_store').fadeOut();
            }
        } else {
            $('#masthead').removeClass("sticky adminbar-sticky");
            $('.demo_store').fadeIn();
            $('.search-widget-header').slideUp();
        }
    });

    $('.dropdown-tigger').on('click', function() {
        $('.sub-menu').hide();
        $(this).siblings('.sub-menu').slideToggle();
    });

    $('.mobile-menu-trigger').on('click', function() {
        $("#masthead").toggleClass("menu-opened");
    });

    $(".panel-title").on('click', function() {
        /*
         * Listener Edited : 11/08/2017 08:48
         * Listener Used : Listener used for listen user click and slidetoggale panel content
         * Listener Page : All Page
         * Listener Scope : Spesific - Just Element was Load
         */

        $(this).parents('.widget').toggleClass("active");
        $(this).siblings(".panel-content").slideToggle();
    })

    $('.warrior-selected-option').on('click', function() {
        $(this).next('ul').slideToggle(200);
        $('.warrior-selected-option').not(this).next('ul').hide();
    });

    $('.dd-trigger').on('click', function() {
        $(this).siblings(".sub-menu").slideToggle();
    });

    $('.user-account-menu .avatar').on('click', function() {
        $(this).next().slideToggle();
    });

    $('.search-trigger').on('click',(function(x) {
        $(this).parents('.search-tool').toggleClass("active");
        $('.search-widget-header').slideToggle();
        x.stopPropagation();
    }))

    $(document).on("click", function(x) {
    if ($(x.target).is('.search-widget-header, .search-tool, input') === false) {
        $('.search-widget-header').slideUp();
    }
  });

    $("#backtotop").on('click', function() {
        $("body, html").animate({
            scrollTop: 0
        });
    });

    //----------------x------------- EACH -----------------------------//
    $('.woocommerce-MyAccount-navigation').each(function() {
        $(this).append('<div class="navTrigger"></div>')
    })

    $('.navTrigger').each(function() {
        var theHtml = $(this).parents('.woocommerce-MyAccount-navigation').find('.is-active a').html();

        $(this).html(theHtml);
        $(this).on('click', function() {
            $(this).parent().toggleClass('menu-open');

        })
    })

    $('.main-cart-area .cross-sells').each(function() {
        var sibling = $(this).parents('.main-cart-area').find('form.main-cart-form'),
            sibling2 = $(this).parents('.main-cart-area').find('form.woocommerce-cart-form'),
            siblingY = sibling2.find('.shop_table'),
            siblingX = sibling.find('.shop_table');

        if (sibling.length) {
            $(this).insertAfter(siblingX)
        } else {
            $(this).insertAfter(siblingY)
        }
    })

    $('li .custom-shop-buttons, .quick-view-btn').each(function() {
        var theEl = $(this),
            theSibl = $(this).siblings('a'),
            theImage = theSibl.find('img'),
            theSiblT = theSibl.find('.thumbnail');

        theImage.wrap('<div class="thumbnail"></div>');
        $(this).insertAfter(theImage);
    });

    $('#customer_login .form-row input').each(function() {
        var theParent = $(this).parent();

        $(this).focus(function() {
            theParent.addClass('active');
        })

        $(this).blur(function() {
            if ($(this).val().length === 0) {
                theParent.removeClass('active');
            }
        })
    });

    if( $('#customer_login').length > 0 ) {
        $('body').addClass('woocommerce-login');
    }

    $("#customer_login").each(function() {
        var firstHeight = $('form.login').parent().outerHeight();

        $('form.login').fadeIn();
        $('form.login, form.register').parent().addClass('ct-content')
        $(this).animate({
            height: firstHeight + 150
        })
        $(this).prepend('<div class="customer-tab-header"><div class="ct-nav active" data-form="login">' + _warrior.js_textdomain[1] + '</div><div class="ct-nav" data-form="register">' + _warrior.js_textdomain[2] + '</div></div>');
    });

    $('.ct-nav').each(function() {
        var target = $(this).data('form'),
            targetElement = $('form.' + target).parents('.ct-content'),
            elementHeight = targetElement.outerHeight();

        $(this).on('click', function() {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            targetElement.siblings('.ct-content').fadeOut();
            targetElement.fadeIn();
            $("#customer_login").animate({
                height: elementHeight + 150
            })
        })
    });

    $('.woocommerce .product-carousel ul.products').each(function() {
        var nav = $(this).parents('.product-carousel').attr('data-nav');
        var carouselParams = {
            items: 4,
            singleItem: false,
            autoHeight: true,
            loop: false,
            autoplay: false,
            margin: 20,
            thumbs: false,
            responsiveClass: true,
            nav: true,
            navText: [prevBtnIcon, nextBtnIcon],
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                800: {
                    items: 4
                },
                1280: {
                    items: 4
                }
            }
        };

        if( nav == 'dot' ){
            carouselParams.nav = false;
        }
        else if( nav == 'arrow' ){
            carouselParams.dots = false;
        }

        $(this).addClass('owl-carousel').owlCarousel(carouselParams);
    });

    $(".warrior-tab-widget").each(function() {
        var $this = $(this),
            parentTab = $this.children(".warrior-tab-wrapper"),
            contentTab = parentTab.children(".warrior-tab-content"),
            contentNav = $this.children(".tab-nav"),
            firstChildrenTab = contentTab.first(),
            firstChildrenTabHeight = firstChildrenTab.outerHeight();

        parentTab.css("height", firstChildrenTabHeight);

        $(".tab-nav").each(function() {
            $(this).on('click', function() {
                var targetNavs = $(this).attr('data-id'),
                    theTargetActive = $("#tabs-" + targetNavs + ".warrior-tab-content"),
                    tehTargetHeight = theTargetActive.outerHeight();

                $(this).siblings().removeClass("active");
                $(this).addClass("active");
                contentTab.fadeOut();
                theTargetActive.fadeIn();
                parentTab.css("height", tehTargetHeight);
            })
        })
    })

    $(".warrior-lightbox").each(function() {
        var theTargetID = $(this).attr("href"),
            hashID = theTargetID.replace("#", "");

        $(this).on('click', function() {
            if ($('#warrior-lightbox-' + hashID).length) {
                $('#warrior-lightbox-' + hashID + '.warrior-lightbox-wrapper').delay(500).fadeIn(function() {
                    $(".thumbnails-slider").addClass('owl-carousel').owlCarousel({
                        items: 1,
                        loop: false,
                        margin: 0,
                        nav: true,
                        dots: false,
                        singleItem: true,
                        thumbs: true,
                        thumbsPrerendered: true,
                        autoHeight: false,
                        navText: [prevBtnIcon, nextBtnIcon]
                    });
                    $('.lSGallery li').css('width', '150px');
                });

                $('#' + hashID).css('display', 'block');
                $('body').css('overflow', 'inherit');
            } else {
                $('body').append('<div id="warrior-lightbox-' + hashID + '" class="warrior-lightbox-wrapper"><div class="warrior-lightbox-overlay"></div><div class="table"><div class="tablecell"><div class="warrior-lightbox-content"><div class="warrior-lightbox-inner woocommerce"><div class="close-warrior-lightbox"></div></div></div></div></div></div>');
                $("#" + hashID).appendTo('#warrior-lightbox-' + hashID + ' .warrior-lightbox-inner');
                $('#warrior-lightbox-' + hashID + '.warrior-lightbox-wrapper').delay(500).fadeIn(function() {
                    $(".thumbnails-slider").addClass('owl-carousel').owlCarousel({
                        items: 1,
                        loop: false,
                        margin: 0,
                        nav: true,
                        dots: false,
                        singleItem: true,
                        thumbs: true,
                        thumbsPrerendered: true,
                        autoHeight: true,
                        navText: [prevBtnIcon, nextBtnIcon]
                    });
                });
                $('body').css('overflow', 'hidden');
            }

            $(".close-warrior-lightbox, .warrior-lightbox-overlay").on('click', function() {
                $(this).parents('#warrior-lightbox-' + hashID + '.warrior-lightbox-wrapper').remove();
                $('body').css('overflow', 'inherit')
            });

        });
    });

    //------------------------- Magnific Popup JS - Button Contact CL -----------------------------//
    $(document).ready(function() {
        $('.sms, .line, .phone').magnificPopup();
    });

    //------------------------- Magnific Popup JS - Quick View Product -----------------------------//
    $('.cepatlakoo-ajax-quick-view').magnificPopup({
        type: 'ajax',
        fixedContentPos: false,
        showCloseBtn: true,
        closeBtnInside: true,
        closeOnBgClick: false,
        midClick: true,
        closeMarkup: '<div id="mp-close-click"  class="mfp-close"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.416 10L20 18.584L18.584 20L10 11.416L1.41602 20L0 18.584L8.58398 10L0 1.41602L1.41602 0L10 8.58398L18.584 0L20 1.41602L11.416 10Z" fill="#C4C4C4"/></svg></div>',
        ajax: {
            settings: {
                type: 'POST'
            },
            cursor: 'mfp-ajax-cur', // CSS class that will be added to body during the loading (adds "progress" cursor)
            tError: '<a href="%url%">The content</a> could not be loaded.' //  Error message, can contain %curr% and %total% tags if gallery is enabled
        },
        callbacks: {
            elementParse: function() {
                this.st.ajax.settings.data = {
                    data_id: this.st.el.attr('data-product')
                }
            },
            ajaxContentAdded: function() {
                ! function(a, b, c, d) {
                    a.fn.wc_variation_form = function() {
                        var c = this,
                            d = c.find(".single_variation"),
                            f = c.closest(".product"),
                            g = parseInt(c.data("product_id"), 10),
                            h = c.data("product_variations"),
                            i = h === !1,
                            j = !1,
                            k = c.find(".reset_variations"),
                            l = wp.template("variation-template"),
                            m = wp.template("unavailable-variation-template"),
                            n = c.find(".single_variation_wrap");
                        return n.show(), c.unbind("check_variations update_variation_values found_variation"), c.find(".reset_variations").unbind("click"), c.find(".variations select").unbind("change focusin"), c.on("click", ".reset_variations", function(a) { a.preventDefault(), c.find(".variations select").val("").change(), c.trigger("reset_data") }).on("hide_variation", function(a) { a.preventDefault(), c.find(".single_add_to_cart_button").removeClass("wc-variation-is-unavailable").addClass("disabled wc-variation-selection-needed"), c.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled") }).on("show_variation", function(a, b, d) { a.preventDefault(), d ? (c.find(".single_add_to_cart_button").removeClass("disabled wc-variation-selection-needed wc-variation-is-unavailable"), c.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-disabled").addClass("woocommerce-variation-add-to-cart-enabled")) : (c.find(".single_add_to_cart_button").removeClass("wc-variation-selection-needed").addClass("disabled wc-variation-is-unavailable"), c.find(".woocommerce-variation-add-to-cart").removeClass("woocommerce-variation-add-to-cart-enabled").addClass("woocommerce-variation-add-to-cart-disabled")) }).on("click", ".single_add_to_cart_button", function(c) {
                            var d = a(this);
                            d.is(".disabled") && (c.preventDefault(), d.is(".wc-variation-is-unavailable") ? b.alert(wc_add_to_cart_variation_params.i18n_unavailable_text) : d.is(".wc-variation-selection-needed") && b.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text))
                        }).on("reload_product_variations", function() { h = c.data("product_variations"), i = h === !1 }).on("reset_data", function() { f.find(".product_meta").find(".sku").wc_reset_content(), a(".product_weight").wc_reset_content(), a(".product_dimensions").wc_reset_content(), c.trigger("reset_image"), d.slideUp(200).trigger("hide_variation") }).on("reset_image", function() { c.wc_variations_image_update(!1) }).on("change", ".variations select", function() {
                            if (c.find('input[name="variation_id"], input.variation_id').val("").change(), c.find(".wc-no-matching-variations").remove(), i) {
                                j && j.abort();
                                var b = !0,
                                    d = !1,
                                    e = {};
                                c.find(".variations select").each(function() {
                                    var c = a(this).data("attribute_name") || a(this).attr("name"),
                                        f = a(this).val() || "";
                                    0 === f.length ? b = !1 : d = !0, e[c] = f
                                }), b ? (e.product_id = g, e.custom_data = c.data("custom_data"), c.block({ message: null, overlayCSS: { background: "#fff", opacity: .6 } }), j = a.ajax({ url: wc_cart_fragments_params.wc_ajax_url.toString().replace("%%endpoint%%", "get_variation"), type: "POST", data: e, success: function(a) { a ? c.trigger("found_variation", [a]) : (c.trigger("reset_data"), c.find(".single_variation").after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + "</p>"), c.find(".wc-no-matching-variations").slideDown(200)) }, complete: function() { c.unblock() } })) : c.trigger("reset_data"), d ? "hidden" === k.css("visibility") && k.css("visibility", "visible").hide().fadeIn() : k.css("visibility", "hidden")
                            } else c.trigger("woocommerce_variation_select_change"), c.trigger("check_variations", ["", !1]), a(this).blur();
                            a(".product.has-default-attributes > .images").fadeTo(200, 1), c.trigger("woocommerce_variation_has_changed")
                        }).on("focusin touchstart", ".variations select", function() { a(this).find("option:selected").attr("selected", "selected"), i || (c.trigger("woocommerce_variation_select_focusin"), c.trigger("check_variations", [a(this).data("attribute_name") || a(this).attr("name"), !0])) }).on("found_variation", function(b, e) {
                            var g = f.find(".product_meta").find(".sku"),
                                h = f.find(".product_weight"),
                                i = f.find(".product_dimensions"),
                                j = n.find(".quantity"),
                                k = !0;
                            e.sku ? g.wc_set_content(e.sku) : g.wc_reset_content(), e.weight ? h.wc_set_content(e.weight) : h.wc_reset_content(), e.dimensions ? i.wc_set_content(e.dimensions) : i.wc_reset_content(), c.wc_variations_image_update(e);
                            var o = "";
                            e.variation_is_visible ? (o = l({ variation: e }), o = o.replace("/*<![CDATA[*/", ""), o = o.replace("/*]]>*/", ""), d.html(o), c.find('input[name="variation_id"], input.variation_id').val(e.variation_id).change()) : (o = m(), o = o.replace("/*<![CDATA[*/", ""), o = o.replace("/*]]>*/", ""), d.html(o), c.find('input[name="variation_id"], input.variation_id').val("").change()), "yes" === e.is_sold_individually ? (j.find("input.qty").val("1").attr("min", "1").attr("max", ""), j.hide()) : (j.find("input.qty").attr("min", e.min_qty).attr("max", e.max_qty), j.show()), e.is_purchasable && e.is_in_stock && e.variation_is_visible || (k = !1), a.trim(d.text()) ? d.slideDown(200).trigger("show_variation", [e, k]) : d.show().trigger("show_variation", [e, k])
                        }).on("check_variations", function(c, f, g) {
                            if (!i) {
                                var j = !0,
                                    k = !1,
                                    l = {},
                                    m = a(this),
                                    n = m.find(".reset_variations");
                                m.find(".variations select").each(function() {
                                    var b = a(this).data("attribute_name") || a(this).attr("name"),
                                        c = a(this).val() || "";
                                    0 === c.length ? j = !1 : k = !0, f && b === f ? (j = !1, l[b] = "") : l[b] = c
                                });
                                var o = e.find_matching_variations(h, l);
                                if (j) {
                                    var p = o.shift();
                                    p ? m.trigger("found_variation", [p]) : (m.find(".variations select").val(""), g || m.trigger("reset_data"), b.alert(wc_add_to_cart_variation_params.i18n_no_matching_variations_text))
                                } else m.trigger("update_variation_values", [o]), g || m.trigger("reset_data"), f || d.slideUp(200).trigger("hide_variation");
                                k ? "hidden" === n.css("visibility") && n.css("visibility", "visible").hide().fadeIn() : n.css("visibility", "hidden")
                            }
                        }).on("update_variation_values", function(b, d) {
                            i || (c.find(".variations select").each(function(b, c) {
                                var e, f = a(c),
                                    g = a(c).data("show_option_none"),
                                    h = "no" === g ? "" : ":gt(0)";
                                f.data("attribute_options") || f.data("attribute_options", f.find("option" + h).get()), f.find("option" + h).remove(), f.append(f.data("attribute_options")), f.find("option" + h).removeClass("attached"), f.find("option" + h).removeClass("enabled"), f.find("option" + h).removeAttr("disabled"), e = "undefined" != typeof f.data("attribute_name") ? f.data("attribute_name") : f.attr("name");
                                for (var i in d)
                                    if ("undefined" != typeof d[i]) {
                                        var j = d[i].attributes;
                                        for (var k in j)
                                            if (j.hasOwnProperty(k)) {
                                                var l = j[k];
                                                if (k === e) {
                                                    var m = "";
                                                    d[i].variation_is_active && (m = "enabled"), l ? (l = a("<div/>").html(l).text(), l = l.replace(/'/g, "\\'"), l = l.replace(/"/g, '\\"'), f.find('option[value="' + l + '"]').addClass("attached " + m)) : f.find("option" + h).addClass("attached " + m)
                                                }
                                            }
                                    }
                                f.find("option" + h + ":not(.attached)").remove(), f.find("option" + h + ":not(.enabled)").attr("disabled", "disabled")
                            }), c.trigger("woocommerce_update_variation_values"))
                        }), c.trigger("wc_variation_form"), c
                    };
                    var e = {
                        find_matching_variations: function(a, b) {
                            for (var c = [], d = 0; d < a.length; d++) {
                                var f = a[d];
                                e.variations_match(f.attributes, b) && c.push(f)
                            }
                            return c
                        },
                        variations_match: function(a, b) {
                            var c = !0;
                            for (var e in a)
                                if (a.hasOwnProperty(e)) {
                                    var f = a[e],
                                        g = b[e];
                                    f !== d && g !== d && 0 !== f.length && 0 !== g.length && f !== g && (c = !1)
                                }
                            return c
                        }
                    };
                    a.fn.wc_set_content = function(a) { d === this.attr("data-o_content") && this.attr("data-o_content", this.text()), this.text(a) }, a.fn.wc_reset_content = function() { d !== this.attr("data-o_content") && this.text(this.attr("data-o_content")) }, a.fn.wc_set_variation_attr = function(a, b) { d === this.attr("data-o_" + a) && this.attr("data-o_" + a, this.attr(a) ? this.attr(a) : ""), this.attr(a, b) }, a.fn.wc_reset_variation_attr = function(a) { d !== this.attr("data-o_" + a) && this.attr(a, this.attr("data-o_" + a)) }, a.fn.wc_variations_image_update = function(a) {
                        var b = this,
                            c = b.closest(".product"),
                            d = c.find("div.images img:eq(0)"),
                            e = c.find("div.images a.zoom:eq(0)");
                        a && a.image_src && a.image_src.length > 1 ? (d.wc_set_variation_attr("src", a.image_src), d.wc_set_variation_attr("title", a.image_title), d.wc_set_variation_attr("alt", a.image_alt), d.wc_set_variation_attr("srcset", a.image_srcset), d.wc_set_variation_attr("sizes", a.image_sizes), e.wc_set_variation_attr("href", a.image_link), e.wc_set_variation_attr("title", a.image_caption)) : (d.wc_reset_variation_attr("src"), d.wc_reset_variation_attr("title"), d.wc_reset_variation_attr("alt"), d.wc_reset_variation_attr("srcset"), d.wc_reset_variation_attr("sizes"), e.wc_reset_variation_attr("href"), e.wc_reset_variation_attr("title"))
                    }, a(function() { "undefined" != typeof wc_add_to_cart_variation_params && a(".variations_form").each(function() { a(this).wc_variation_form().find(".variations select:eq(0)").change() }) })
                }(jQuery, window, document);
    
                $(".mfp-wrap .thumbnails-slider").owlCarousel({
                    items: 1,
                    loop: false,
                    margin: 0,
                    nav: true,
                    dots: false,
                    singleItem: true,
                    thumbs: true,
                    thumbsPrerendered: true,
                    autoHeight: false,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
    
                $('.mfp-wrap .thumbnails-slider').each(function() { // the containers for all your galleries
                    $(this).find('.owl-thumbs button').each(function() { // the containers for all your galleries
                        var imageSource = $(this).text();
                        $(this).html('<img src="' + imageSource + '">');
                    });
                });
    
                $(".thumbnails-slider a, .thumbnails-slider div a").on('click',(function(event) {
                    event.preventDefault();
                }));
    
                $('button.owl-thumb-item').each(function() {
                    if ($(this).text() == 'undefined') {
                        $(this).text('');
                    }
                });
                if ( $('#countdown-widget').length > 0 ){
                    if ( typeof _cepatlakoo !== 'undefined' ) {
                        var get_scarcity_countdown_type = _cepatlakoo.scarcity_countdown_type;
                        var get_scarcity_countdown_date_time = _cepatlakoo.scarcity_countdown_date_time;
                        var get_scarcity_countdown_cookies = _cepatlakoo.scarcity_cookies_name;
                        var get_scarcity_countdown_timer = _cepatlakoo.scarcity_countdown_timer;
                    } else {
                        var get_scarcity_countdown_type = $('.sc-type').text();
                        var get_scarcity_countdown_date_time = $('.sc-time').text();
                        var get_scarcity_countdown_cookies = $('.sc-cookies').text();
                        var get_scarcity_countdown_timer = $('.sc-timer').text();
                    }
    
                    if (get_scarcity_countdown_type == "Evergreen Countdown") {
                        // var finalDate = new Date(new Date().valueOf() + 1 * 10 * 60 * 60 * 1000);  // + hari * jam * menit * detik * 1000
        
                        if (get_scarcity_countdown_date_time != null || get_scarcity_countdown_date_time != '') {
                            var finalDate = get_scarcity_countdown_date_time;
        
                            if(get_scarcity_countdown_cookies !== undefined){
                                finalDate = cl_countdown_get_cookies(get_scarcity_countdown_cookies, get_scarcity_countdown_date_time, get_scarcity_countdown_timer);
                            }
        
                            var date = new Date();
                            date.setTime(date.getTime() + (1 * 2 * 60 * 60 * 1000));
                            $('#countdown_qv').countdown(finalDate, function(event) {
                                if (event.strftime('%m') != '00') {
                                    $('.number-container.month .number').html(event.strftime('%m'));
                                    $('.number-container.month .text').html(event.strftime('Bulan'));
                                } else {
                                    $('.number-container.month').hide();
                                }
                                if (event.strftime('%n') != '00') {
                                    $('.number-container.day .number').html(event.strftime('%n'));
                                    $('.number-container.day .text').html(event.strftime('Hari'));
                                } else {
                                    $('.number-container.day').hide();
                                }
                                $('.number-container.hour .number').html(event.strftime('%H'));
                                $('.number-container.hour .text').html(event.strftime('Jam'));
                                $('.number-container.minute .number').html(event.strftime('%M'));
                                $('.number-container.minute .text').html(event.strftime('Menit'));
                                $('.number-container.second .number').html(event.strftime('%S'));
                                $('.number-container.second .text').html(event.strftime('Detik'));
                                $('#countdown_qv').attr('data-state', 'loaded');
                            });
                        }
                    } else {
                        var finalDate = get_scarcity_countdown_date_time;
                        $('#countdown_qv').countdown(finalDate, function(event) {
                            if (event.strftime('%m') != '00') {
                                $('.number-container.month .number').html(event.strftime('%m'));
                                $('.number-container.month .text').html(event.strftime('Bulan'));
                            } else {
                                $('.number-container.month').hide();
                            }
                            if (event.strftime('%n') != '00') {
                                $('.number-container.day .number').html(event.strftime('%n'));
                                $('.number-container.day .text').html(event.strftime('Hari'));
                            } else {
                                $('.number-container.day').hide();
                            }
                            $('.number-container.hour .number').html(event.strftime('%H'));
                            $('.number-container.hour .text').html(event.strftime('Jam'));
                            $('.number-container.minute .number').html(event.strftime('%M'));
                            $('.number-container.minute .text').html(event.strftime('Menit'));
                            $('.number-container.second .number').html(event.strftime('%S'));
                            $('.number-container.second .text').html(event.strftime('Detik'));
                            $('#countdown_qv').attr('data-state', 'loaded');
                        });
                    }
                }
    
                $(".thumbnails-slider").addClass('owl-carousel').owlCarousel({
                    items: 1,
                    loop: false,
                    margin: 0,
                    nav: true,
                    dots: false,
                    singleItem: true,
                    thumbs: true,
                    thumbsPrerendered: true,
                    autoHeight: false,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
    
                $('button.owl-thumb-item').each(function() {
                    if ($(this).text() == 'undefined') {
                        $(this).text('');
                    }
                })
    
                // Button Disabled After Countdown Zero Condition in Single Product
                var countHour = $('.mfp-container #countdown_qv .hour .number').text();
                var countMinute = $('.mfp-container #countdown_qv .minute .number').text();
                var countSecond = $('.mfp-container #countdown_qv .second .number').text();
                if (countHour == '00' && countSecond == '00' && countMinute == '00') {
                    $('.mfp-container .entry-summary').find('button.single_add_to_cart_button').attr("disabled", true);
                    $('.mfp-container .entry-summary').find('a.add_to_cart_button').addClass("disabled");
                    $('.mfp-container .entry-summary').find('a.single_add_to_cart_button').addClass("disabled");
                    $('.mfp-container .entry-summary').find('button.single_add_to_cart_button').removeAttr('name');
                    $('.mfp-container .entry-summary').find('button.single_add_to_cart_button').removeAttr('value');
                    $('.mfp-container .entry-summary').find('.woocommerce-variation-add-to-cart input[type="hidden"]').remove();
                }
    
                if ($('.variation-type').text() == 2) {
                    $('table.variations select').each(function(index, element) {
                        var label = $(this).children("option:selected").text();
    
                        var tab_attribs = $('select option').map(function() {
                            return $(this).attr("value");
                        });
    
                        $(this).removeClass('dropdown');
                        $(this).attr('id', 'variation' + index);
                        $(this).wrap("<div class='warrior-variation'></div>")
                            .parent()
                            .after()
                            .append("<div id='" + index + "' class='size-select-widget'><ul></ul>");
    
                        $(element).each(function(idx, elm) {
    
                            $('option', elm).each(function(id, el) {
                                if (id > 0) {
                                    $('.size-select-widget ul:last').append('<li data-val="' + el.getAttribute("value") + '">' + el.text + '</li>');
                                }
                            });
                        });
    
                        $('#' + index + '.size-select-widget ul li').on('click', function() { // when mouse clicked fuzzy select
                            $('#' + index + '.size-select-widget ul li').removeClass("selected");
                            $(this).addClass("selected");
                            var selectedLI = $(this).data('val');
                            // console.log(selectedLI);
                            $('select#variation' + index).val(selectedLI).trigger('change');
                        });
                        $(this).hide();
                        $(this).parent().find('.ui.dropdown').dropdown('destroy');
                        $(this).dropdown('destroy');
                    });
                    stat_select = 1;
                }
    
                /*
                * Initialize all galleries on page.
                */
                $( '.woocommerce-product-gallery' ).each( function() {
                    $( this ).trigger( 'wc-product-gallery-before-init', [ this, wc_single_product_params ] );
                    $( this ).wc_product_gallery( wc_single_product_params );
                    $( this ).trigger( 'wc-product-gallery-after-init', [ this, wc_single_product_params ] );
                } );
            }
        }
    });

    //------------------------- Magnific Popup JS - Quick View Product -----------------------------//
    $('.scrollableList').show();
    $('.makeMeList').hide();
    $('.gallery-widget ul').mixItUp({
        selectors: {
            filter: '.gallery-filter',
            target: '.mix',
            sort: '.sort'
        }
    });

    $('.site-navigation ul li').on('mouseenter', function() {
        $(this).children(".sub-menu").stop().slideDown();
    }).on('mouseleave', function() {
        $(this).children(".sub-menu").stop().slideUp();
    });

    if ($('ul.products li span.onsale').length > 1) {
        $("span.onsale", "li.img.attachment-shop_catalog").wrap("<div class='product-content-header'></div>");
    }

    $(".pager a:first").addClass("older-posts");
    $(".pager a:last").addClass("newer-posts");

    $('.vc-button').removeAttr('style');

    //------------------------- Window Load & Ready -------------------------//
    $(window).on('load', function() {
        $('.sc-item').each(function() {
            var imG = $(this).find('img'),
                srC = imG.attr('src');

            $(this).css('background-image', 'url(' + srC + ')')
        })
        CallLightSlider();

        $('.warrior-variation').parent().addClass('dont');
        if (_warrior.gallery_thumbnail == 'hide_gallery') {
            $('#single-product-hightlight .lSPager.lSGallery').css('display', 'none');
        } else if (_warrior.gallery_thumbnail == 'show_gallery') {
            $('#single-product-hightlight .lSPager.lSGallery').css('display', 'block');
        }

        if (stat_select == 1) {
            $('.warrior-variation .selection.ui').css('display', 'none !important');
        }

        $('.cepatlakoo-quick-view .images ul').addClass('thumbnails-slider', 'quickview-slider', 1000, "easeInOutQuad");

        $('.range-slider').jRange({
            from: 0,
            to: 100,
            step: 1,
            scale: [0, 25, 50, 75, 100],
            format: '%s',
            width: 300,
            showLabels: true,
            isRange: true
        });

        $('.mega-sub-menu').each(function() {
            var winWidth = $(window).width(),
                subMenu = $(this),
                subMenuPos = subMenu.offset().left,
                subMenuWidth = subMenu.outerWidth(),
                allMenu = subMenuPos + subMenuWidth,
                marginMenu = allMenu - winWidth;

            if (allMenu > winWidth) {
                subMenu.css('margin-left', '-' + marginMenu + 'px');
            }
        });

        $('.mega-menu').each(function() {
            var winWidth = $(window).width();
            var subMenu = $(this).children('.sub-menu');
            var subMenuPos = (subMenu.length > 0) ? subMenu.offset().left : 0,
                subMenuWidth = (subMenu.length > 0) ? subMenu.outerWidth() : 0,
                allMenu = subMenuPos + subMenuWidth + 20,
                marginMenu = allMenu - winWidth;

            if (allMenu > winWidth) {
                subMenu.css('margin-left', '-' + marginMenu + 'px');
            }
        });

        // WooCommerce coupon field
        var couponMessageLink = $("a.showcoupon");
        var couponMessage = couponMessageLink.parent('.woocommerce-info');
        var coupon = $(".checkout_coupon");
        var loginLink = $('a.showlogin');
        var loginMessage = loginLink.parent('.woocommerce-info');
        var loginForm = $('.woocommerce-form-login')

        if( couponMessageLink.length !== 0 ) {
            $(couponMessage).insertAfter('.shop_table.woocommerce-checkout-review-order-table');
            $(coupon).insertAfter('.shop_table.woocommerce-checkout-review-order-table');
        }

        if( loginLink.length !== 0 ) {
            $(loginMessage).addClass('half-info').insertBefore('#customer_details .col-1');
            $(loginForm).addClass('half-info').insertBefore('#customer_details .col-1');
        }

        $('.single-slider, .warrior-z-carousel ul').addClass('owl-carousel').owlCarousel({ // first section slider
            items: 1,
            loop: true,
            margin: 0,
            nav: false,
            thumbs: false,
            singleItem: true,
            autoHeight: true,
            navText: [prevBtnIcon, nextBtnIcon]
        });

        var cepatlakooCarouselItem = $('.slide-carousel-item').html();
        var cepatlakooTypeSlider = $('#cepatlakoo-slide').attr('class');
        var cepatlakooTypeSlidershow = false;
        var cepatlakooItemSlidershow = 1;

        if (cepatlakooTypeSlider == 'carousel') {
            cepatlakooTypeSlidershow = false;
        } else if (cepatlakooTypeSlider == 'fullwidth') {
            cepatlakooTypeSlidershow = true;
            $('#cepatlakoo-slideshow .elementor img').css('width', '100%');
        }

        if (cepatlakooCarouselItem > 1) {
            cepatlakooItemSlidershow = cepatlakooCarouselItem
        } else {
            cepatlakooItemSlidershow = 1
        }

        $('.elementor-widget-cepatlakoo-slideshow').each(function(index) {
            // var idSlider = $(this).closest('[data-element_type="cepatlakoo-slideshow.default"]').attr('data-model-cid');
            // $(this).find('section.cepatlakoo-slideshow div:first').attr('id', idSlider);
            var cepatlakooCarouselItem = $(this).find('.slide-carousel-item').html();
            var cepatlakooCarouselRotate = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-rotate') === 'true') ? true : false;
            var cepatlakooCarouselLoop = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-loop') === 'true') ? true : false;
            var cepatlakooCarouselDelay = $(this).find('section.cepatlakoo-slideshow div:first').attr('data-delay');
            var cepatlakooTypeSlider = $(this).find('section.cepatlakoo-slideshow div:first').attr('class');
            if (cepatlakooTypeSlider == 'cepatlakoo-carousel') {
                $('.preload').fadeOut('slow'); // preloader
                $('.preload').remove(); // preloader
                $(this).find('.cepatlakoo-carousel').addClass('owl-carousel').owlCarousel({ // first section slider
                    items: cepatlakooCarouselItem,
                    singleItem: false,
                    autoHeight: true,
                    loop: cepatlakooCarouselLoop,
                    autoplay: cepatlakooCarouselRotate,
                    autoplayTimeout: cepatlakooCarouselDelay,
                    autoplayHoverPause: false,
                    margin: 10,
                    autoWidth:true,
                    thumbs: false,
                    responsiveClass: true,
                    nav: false,
                    dots: true,
                    responsive: {
                        0: {
                            items: 1,
                            autoWidth:false
                        },
                        400: {
                            items: 2,
                            autoWidth:false
                        },
                        800: {
                            items: 4,
                            autoWidth:false
                        },
                        1280: {
                            items: cepatlakooCarouselItem,
                            autoWidth:false
                        }
                    }
                });
            } else if (cepatlakooTypeSlider == 'cepatlakoo-fullwidth') {
                $('.preload').fadeOut('slow'); // preloader
                $('.preload').remove(); // preloader
                $(this).find('.cepatlakoo-fullwidth').addClass('owl-carousel').owlCarousel({ // first section slider
                    items: 1,
                    loop: cepatlakooCarouselLoop,
                    margin: 0,
                    singleItem: true,
                    nav: false,
                    thumbs: false,
                    dots: true,
                    autoHeight: true,
                    autoplay: cepatlakooCarouselRotate,
                    autoplayTimeout: cepatlakooCarouselDelay,
                    autoplayHoverPause: false,
                    lazyload: true,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
            }
            sliderAutoHeight();
        });

        $('.elementor-widget-cepatlakoo-recent-product').each(function(index) {
            // var idSlider = $(this).closest('[data-element_type="cepatlakoo-slideshow.default"]').attr('data-model-cid');
            // $(this).find('section.cepatlakoo-slideshow div:first').attr('id', idSlider);
            var cepatlakooCarouselItem = $(this).find('li').html();
            var cepatlakooCarouselRotate = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-rotate') === 'true') ? true : false;
            var cepatlakooCarouselLoop = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-loop') === 'true') ? true : false;
            var cepatlakooCarouselDelay = $(this).find('section.cepatlakoo-slideshow div:first').attr('data-delay');
            var cepatlakooTypeSlider = $(this).find('section.cepatlakoo-slideshow div:first').attr('class');
            if (cepatlakooTypeSlider == 'cepatlakoo-carousel') {
                $('.preload').fadeOut('slow'); // preloader
                $('.preload').remove(); // preloader
                $(this).find('.cepatlakoo-carousel ul').addClass('owl-carousel').owlCarousel({ // first section slider
                    items: 1,
                    singleItem: true,
                    autoHeight: true,
                    loop: cepatlakooCarouselLoop,
                    autoplay: cepatlakooCarouselRotate,
                    autoplayTimeout: cepatlakooCarouselDelay,
                    autoplayHoverPause: false,
                    margin: 0,
                    thumbs: false,
                    responsiveClass: true,
                    nav: false,
                    dots: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 2
                        },
                        800: {
                            items: 4
                        },
                        1280: {
                            items: cepatlakooCarouselItem
                        }
                    }
                });
            } else if (cepatlakooTypeSlider == 'cepatlakoo-fullwidth') {
                $('.preload').fadeOut('slow'); // preloader
                $('.preload').remove(); // preloader
                $(this).find('.cepatlakoo-fullwidth').addClass('owl-carousel').owlCarousel({ // first section slider
                    items: 1,
                    loop: cepatlakooCarouselLoop,
                    margin: 0,
                    singleItem: true,
                    nav: false,
                    thumbs: false,
                    dots: true,
                    autoHeight: true,
                    autoplay: cepatlakooCarouselRotate,
                    autoplayTimeout: cepatlakooCarouselDelay,
                    autoplayHoverPause: false,
                    lazyload: true,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
            }
        });

        $('.image-carousels').addClass('owl-carousel').addClass('owl-carousel').owlCarousel({
            loop: false,
            margin: 0,
            nav: true,
            thumbs: false,
            responsiveClass: true,
            autoHeight: false,
            singleItem: true
        });
    });

    $(document).ready(function() {
        $('#cepatlakoo-single-slider').addClass('owl-carousel').owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            singleItem: true,
            thumbs: false,
            nav: false,
            autoHeight: true,
            dots: true
        });

        $('.wc-bacs-bank-details-account-name').each(function() {
            var content = $(this).next('ul');

            $(this).detach().prependTo(content);
        })

        $('select.orderby').each(function() {
            $(this).wrap('<div class="order-by-wrapper"></div>');
            $('<span class="ob-arrow"><i class="icon icon-angle-down"></i></span>').insertAfter($(this));
        })

        if ( $('#countdown-widget').length > 0 ){
            if (_cepatlakoo.scarcity_countdown_date_time == null) {
                var get_scarcity_countdown_type = $('.sc-type').text();
                var get_scarcity_countdown_date_time = $('.sc-time').text();
                var get_scarcity_countdown_cookies = $('.sc-cookies').text();
                var get_scarcity_countdown_timer = $('.sc-timer').text();
            } else {
                var get_scarcity_countdown_type = _cepatlakoo.scarcity_countdown_type;
                var get_scarcity_countdown_date_time = _cepatlakoo.scarcity_countdown_date_time;
                var get_scarcity_countdown_cookies = _cepatlakoo.scarcity_cookies_name;
                var get_scarcity_countdown_timer = _cepatlakoo.scarcity_countdown_timer;
            }

            if (get_scarcity_countdown_type == "Evergreen Countdown") {
                // var finalDate = new Date(new Date().valueOf() + 1 * 10 * 60 * 60 * 1000);  // + hari * jam * menit * detik * 1000

                if (get_scarcity_countdown_date_time != null || get_scarcity_countdown_date_time != '') {
                    var finalDate = get_scarcity_countdown_date_time;

                    if(get_scarcity_countdown_cookies !== undefined){
                        finalDate = cl_countdown_get_cookies(get_scarcity_countdown_cookies, get_scarcity_countdown_date_time, get_scarcity_countdown_timer);
                    }

                    var date = new Date();
                    date.setTime(date.getTime() + (1 * 2 * 60 * 60 * 1000));
                    $('#countdown').countdown(finalDate, function(event) {
                        if (event.strftime('%m') != '00') {
                            $('.number-container.month .number').html(event.strftime('%m'));
                            $('.number-container.month .text').html(event.strftime('Bulan'));
                        } else {
                            $('.number-container.month').hide();
                        }
                        if (event.strftime('%n') != '00') {
                            $('.number-container.day .number').html(event.strftime('%n'));
                            $('.number-container.day .text').html(event.strftime('Hari'));
                        } else {
                            $('.number-container.day').hide();
                        }
                        $('.number-container.hour .number').html(event.strftime('%H'));
                        $('.number-container.hour .text').html(event.strftime('Jam'));
                        $('.number-container.minute .number').html(event.strftime('%M'));
                        $('.number-container.minute .text').html(event.strftime('Menit'));
                        $('.number-container.second .number').html(event.strftime('%S'));
                        $('.number-container.second .text').html(event.strftime('Detik'));
                        $('#countdown').attr('data-state', 'loaded');
                    });
                }
            } else {
                var finalDate = get_scarcity_countdown_date_time;
                $('#countdown').countdown(finalDate, function(event) {
                    if (event.strftime('%m') != '00') {
                        $('.number-container.month .number').html(event.strftime('%m'));
                        $('.number-container.month .text').html(event.strftime('Bulan'));
                    } else {
                        $('.number-container.month').hide();
                    }
                    if (event.strftime('%n') != '00') {
                        $('.number-container.day .number').html(event.strftime('%n'));
                        $('.number-container.day .text').html(event.strftime('Hari'));
                    } else {
                        $('.number-container.day').hide();
                    }
                    $('.number-container.hour .number').html(event.strftime('%H'));
                    $('.number-container.hour .text').html(event.strftime('Jam'));
                    $('.number-container.minute .number').html(event.strftime('%M'));
                    $('.number-container.minute .text').html(event.strftime('Menit'));
                    $('.number-container.second .number').html(event.strftime('%S'));
                    $('.number-container.second .text').html(event.strftime('Detik'));
                    $('#countdown').attr('data-state', 'loaded');
                });
            }
        }
        CallLightSlider();

        // Button Disabled After Countdown Zero Condition in Single Product
        var countHour = $('#countdown .hour .number').text();
        var countMinute = $('#countdown .minute .number').text();
        var countSecond = $('#countdown .second .number').text();
        if (countHour == '00' && countSecond == '00' && countMinute == '00') {
            $('.entry-summary').find('button.single_add_to_cart_button').attr("disabled", true);
            $('.entry-summary').find('a.add_to_cart_button').addClass("disabled");
            $('.entry-summary').find('a.single_add_to_cart_button').addClass("disabled");
            $('.entry-summary').find('button.single_add_to_cart_button').removeAttr('name');
            $('.entry-summary').find('button.single_add_to_cart_button').removeAttr('value');

            $('.entry-summary').find('.woocommerce-variation-add-to-cart input[type="hidden"]').remove();
        }

        if (_fbpixel.fbpixel) {
            // var FBCampaign = $('#main').attr('fb-campaign-url');
            // var FBContent = $('#main').attr('fb-content-name');
            // var FBProductID = $('#main').attr('fb-product-id');

            // fbq('track', 'PageView', {
            //     campaign_url: FBCampaign,
            //     content_name: FBContent,
            //     product_id: FBProductID,
            // });

            // Using Campaign Url
            if (_fbpixel.event != 'PageView') {
                cl_trigger_fbpixel_api('PageView');
            }
            //fbq('track', 'ViewContent');
        }

        if (_ttpixel.ttpixel) {
            // Using Campaign Url
            if (_ttpixel.event != 'ViewContent') {
                ttq.track('ViewContent');
            }
        }

        if ( _fbpixel.fbpixel && _fbpixel_purchase.type != undefined ) {
            // fbq('track', _fbpixel_purchase.type, {
            //     content_type: 'product',
            //     num_items: _fbpixel_purchase.items,
            //     content_ids: _fbpixel_purchase.prod_ids,
            //     value: filterPricePixel(_fbpixel_purchase.total),
            //     currency: _fbpixel_purchase.currency,
            // });

            cl_trigger_fbpixel_api(_fbpixel_purchase.type, {
                content_type: 'product',
                num_items: _fbpixel_purchase.items,
                content_ids: _fbpixel_purchase.prod_ids,
                value: filterPricePixel(_fbpixel_purchase.total),
                currency: _fbpixel_purchase.currency
            });
        }

        if ( _fbpixel.fbpixel && _fbpixel_initCheckout.type != undefined ) {
            // fbq('track', _fbpixel_initCheckout.type, {
            //     content_type: 'product',
            //     num_items: _fbpixel_initCheckout.items,
            //     content_ids: _fbpixel_initCheckout.prod_ids,
            //     value: filterPricePixel(_fbpixel_initCheckout.total),
            //     currency: _fbpixel_initCheckout.currency,
            // });
            
            cl_trigger_fbpixel_api(_fbpixel_initCheckout.type, {
                content_type: 'product',
                num_items: _fbpixel_initCheckout.items,
                content_ids: _fbpixel_initCheckout.prod_ids,
                value: filterPricePixel(_fbpixel_initCheckout.total),
                currency: _fbpixel_initCheckout.currency
            });
        }

        if ( _ttpixel.fbpixel && _ttpixel_initCheckout.type != undefined ) {
            ttq.track(_ttpixel_initCheckout.type, {
                content_type: 'product',
                quantity: _ttpixel_initCheckout.items,
                content_id: _ttpixel_initCheckout.prod_ids,
                value: filterPricePixel(_ttpixel_initCheckout.total),
                currency: _ttpixel_initCheckout.currency,
            });
        }

        var footerHeight = $("#colofon").outerHeight();
        $("#backtotop").on('click', function() {
            $("body, html").animate({
                scrollTop: 0
            });
        });

        if (_fbpixel.fbpixel || _ttpixel.ttpixel) {
            // var FBCampaign = $('#main').attr('fb-campaign-url');
            // var FBContent = $('#main').attr('fb-content-name');
            // var FBProductID = $('#main').attr('fb-product-id');
            // Using Campaign url
            var FBActionPage = _fbpixel.event;
            var TTActionPage = _ttpixel.event;

            var purchase = $('div.cepatlakoo-fbpixel').attr('wcfb-stat');
            if (purchase == 'purchase') {
                // Masih Error
                var price = $('.shop_table').find('tr:last .woocommerce-Price-amount').text();
                var prod_id = $('div.cepatlakoo-fbpixel-id').attr('wcid-order');
                var prod_arry = prod_id.split(',');
                var res = price.split("Rp");
                var res = res.pop('');
                // fbq('track', 'Purchase', {
                //     content_type: 'product',
                //     content_ids: prod_arry,
                //     value: res,
                //     currency: 'IDR',
                //     campaign_url: FBCampaign,
                //     content_name: FBContent,
                //     product_id: FBProductID
                // });
                // Using Campaign url

                // fbq('track', 'Purchase', {
                //     content_type: 'product',
                //     content_ids: prod_arry,
                //     value: filterPricePixel(res),
                //     currency: _warrior.currency_woo,
                // });
                
                cl_trigger_fbpixel_api('Purchase', {
                    content_type: 'product',
                    content_ids: prod_arry,
                    value: filterPricePixel(res),
                    currency: _warrior.currency_woo,
                });
            }

            if (_ttpixel.ttpixel) {
                var purchase = $('div.cepatlakoo-ttpixel').attr('wcfb-stat');
                if (purchase == 'purchase') {
                    // Masih Error
                    var price = $('.shop_table').find('tr:last .woocommerce-Price-amount').text();
                    var prod_id = $('div.cepatlakoo-fbpixel-id').attr('wcid-order');
                    var prod_arry = prod_id.split(',');
                    var res = price.split("Rp");
                    var res = res.pop('');
                    ttq.track('PlaceAnOrder', {
                        content_type: 'product',
                        content_id: prod_arry,
                        value: filterPricePixel(res),
                        currency: _warrior.currency_woo,
                    });
                }
            }

            // Purchase Confirmation
            if ( $('body.confirmation-page form.caldera_forms_form').length > 0) {
                var submitSelector = 'body.confirmation-page .cepatlakoo-confirmation input[type="submit"]';
            } else if ( $('body.confirmation-page form.frm-fluent-form').length > 0) {
                var submitSelector = 'body.confirmation-page button[type="submit"].cepatlakoo-confirmation';
            }
            else{
                var submitSelector = '.cepatlakoo-confirmation';
            }

            $(document).on('click', submitSelector, function() {

                // var amountContainer = $('body.confirmation-page .form-group.confirmation-amount input[type="text"]');

                if ( $('body.confirmation-page form.caldera_forms_form').length > 0) {
                    var amountContainer = $('body.confirmation-page .form-group.confirmation-amount input[type="text"]');
                } else if ( $('body.confirmation-page form.frm-fluent-form').length > 0) {
                    var amountContainer = $('body.confirmation-page input[type="text"].confirmation-amount');
                }

                var amount = '';

                if (amountContainer.val() != '') {
                    amount = amountContainer.val();
                } else {
                    amount = '0';
                }

                if (_fbpixel.fbpixel) {
                    cl_trigger_fbpixel_api('Purchase', {
                        value: filterPricePixel(amount),
                        currency: _warrior.currency_woo
                    });
                }

                if (_ttpixel.ttpixel) {
                    var confirm = (_ttpixel_checkoutEvent.confirmation) ? _ttpixel_checkoutEvent.confirmation : 'CompletePayment';
                    ttq.track(confirm, {
                        value: filterPricePixel(amount),
                        currency: _warrior.currency_woo
                    });
                }
            });

            if (_fbpixel.fbpixel) {
                var FBValue = _fbpixel.price; // metabox fb pixel
                var FBCurrency = _fbpixel.currency; // metabox fb pixel
                var FBPixelSelect = _fbpixel.select; // metabox fb pixel
                var curr = ( FBCurrency != '' && FBCurrency != undefined && FBCurrency != 'ALL') ? FBCurrency : _warrior.currency_woo;

                if (FBActionPage != '' && FBActionPage != 'noevent' && FBActionPage != undefined) {
                    if( FBActionPage == 'AddPaymentInfo' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_category: _fbpixel.category,
                                content_ids: _fbpixel.ids,
                                contents: _fbpixel.contents,
                                value: filterPricePixel(FBValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_category: _fbpixel.category,
                                    content_ids: _fbpixel.ids,
                                    contents: _fbpixel.contents,
                                    value: filterPricePixel(FBValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'AddToCart' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_ids: _fbpixel.ids,
                                content_name: _fbpixel.name,
                                content_type: _fbpixel.type,
                                contents: _fbpixel.contents,
                                value: filterPricePixel(FBValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_ids: _fbpixel.ids,
                                    content_name: _fbpixel.name,
                                    content_type: _fbpixel.type,
                                    contents: _fbpixel.contents,
                                    value: filterPricePixel(FBValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'AddToWishlist' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_name: _fbpixel.name,
                                content_category: _fbpixel.category,
                                content_ids: _fbpixel.ids,
                                contents: _fbpixel.contents,
                                value: filterPricePixel(FBValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_name: _fbpixel.name,
                                    content_category: _fbpixel.category,
                                    content_ids: _fbpixel.ids,
                                    contents: _fbpixel.contents,
                                    value: filterPricePixel(FBValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'CompleteRegistration' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_name: _fbpixel.name,
                                currency: curr,
                                status: _fbpixel.status,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_name: _fbpixel.name,
                                    currency: curr,
                                    status: _fbpixel.status,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'InitiateCheckout' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_category: _fbpixel.category,
                                content_ids: _fbpixel.ids,
                                contents: _fbpixel.contents,
                                currency: curr,
                                num_items: _fbpixel.num,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_category: _fbpixel.category,
                                    content_ids: _fbpixel.ids,
                                    contents: _fbpixel.contents,
                                    currency: curr,
                                    num_items: _fbpixel.num,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'Lead' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_category: _fbpixel.category,
                                content_name: _fbpixel.name,
                                currency: curr,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_category: _fbpixel.category,
                                    content_name: _fbpixel.name,
                                    currency: curr,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'Purchase' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_ids: _fbpixel.ids,
                                content_name: _fbpixel.name,
                                content_type: _fbpixel.type,
                                contents: _fbpixel.contents,
                                currency: curr,
                                num_items: _fbpixel.num,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_ids: _fbpixel.ids,
                                    content_name: _fbpixel.name,
                                    content_type: _fbpixel.type,
                                    contents: _fbpixel.contents,
                                    currency: curr,
                                    num_items: _fbpixel.num,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'Search' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_category: _fbpixel.category,
                                content_ids: _fbpixel.ids,
                                contents: _fbpixel.contents,
                                currency: curr,
                                search_string: _fbpixel.search,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_category: _fbpixel.category,
                                    content_ids: _fbpixel.ids,
                                    contents: _fbpixel.contents,
                                    currency: curr,
                                    search_string: _fbpixel.search,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'StartTrial' || FBActionPage == 'Subscribe' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                currency: curr,
                                predicted_ltv: _fbpixel.ltv,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    currency: curr,
                                    predicted_ltv: _fbpixel.ltv,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else if( FBActionPage == 'ViewContent' ){
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage, {
                                content_ids: _fbpixel.ids,
                                content_category: _fbpixel.category,
                                content_name: _fbpixel.name,
                                content_type: _fbpixel.type,
                                contents: _fbpixel.contents,
                                currency: curr,
                                value: filterPricePixel(FBValue),
                            });
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage, {
                                    content_ids: _fbpixel.ids,
                                    content_category: _fbpixel.category,
                                    content_name: _fbpixel.name,
                                    content_type: _fbpixel.type,
                                    contents: _fbpixel.contents,
                                    currency: curr,
                                    value: filterPricePixel(FBValue),
                                });
                            });
                        }
                    }
                    else{
                        if( FBPixelSelect == 'all' ){
                            cl_trigger_fbpixel_api(FBActionPage);
                        }
                        else{
                            $.each(FBPixelSelect, function( ind, fb_id ) {
                                fbq('trackSingle', fb_id, FBActionPage);
                            });
                        }
                    }
                }
            }

            if (_ttpixel.ttpixel) {
                var TTValue = _ttpixel.price; // metabox fb pixel
                var TTCurrency = _ttpixel.currency; // metabox fb pixel
                var TTPixelSelect = _ttpixel.select; // metabox fb pixel
                var curr = ( TTCurrency != '' && TTCurrency != undefined && TTCurrency != 'ALL') ? TTCurrency : _warrior.currency_woo;

                if (TTActionPage != '' && TTActionPage != 'noevent' && TTActionPage != undefined) {
                    if( TTActionPage == 'AddPaymentInfo' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_category: _ttpixel.category,
                                content_id: _ttpixel.ids,
                                contents: _ttpixel.contents,
                                value: filterPricePixel(TTValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_category: _ttpixel.category,
                                    content_id: _ttpixel.ids,
                                    contents: _ttpixel.contents,
                                    value: filterPricePixel(TTValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'AddToCart' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_id: _ttpixel.ids,
                                content_name: _ttpixel.name,
                                content_type: _ttpixel.type,
                                contents: _ttpixel.contents,
                                value: filterPricePixel(TTValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_id: _ttpixel.ids,
                                    content_name: _ttpixel.name,
                                    content_type: _ttpixel.type,
                                    contents: _ttpixel.contents,
                                    value: filterPricePixel(TTValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'AddToWishlist' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_name: _ttpixel.name,
                                content_category: _ttpixel.category,
                                content_id: _ttpixel.ids,
                                contents: _ttpixel.contents,
                                value: filterPricePixel(TTValue),
                                currency: curr,
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_name: _ttpixel.name,
                                    content_category: _ttpixel.category,
                                    content_id: _ttpixel.ids,
                                    contents: _ttpixel.contents,
                                    value: filterPricePixel(TTValue),
                                    currency: curr,
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'CompleteRegistration' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_name: _ttpixel.name,
                                currency: curr,
                                status: _ttpixel.status,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_name: _ttpixel.name,
                                    currency: curr,
                                    status: _ttpixel.status,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'InitiateCheckout' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_category: _ttpixel.category,
                                content_id: _ttpixel.ids,
                                contents: _ttpixel.contents,
                                currency: curr,
                                num_items: _ttpixel.num,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_category: _ttpixel.category,
                                    content_id: _ttpixel.ids,
                                    contents: _ttpixel.contents,
                                    currency: curr,
                                    num_items: _ttpixel.num,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'Lead' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_category: _ttpixel.category,
                                content_name: _ttpixel.name,
                                currency: curr,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_category: _ttpixel.category,
                                    content_name: _ttpixel.name,
                                    currency: curr,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'Purchase' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_id: _ttpixel.ids,
                                content_name: _ttpixel.name,
                                content_type: _ttpixel.type,
                                contents: _ttpixel.contents,
                                currency: curr,
                                num_items: _ttpixel.num,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_id: _ttpixel.ids,
                                    content_name: _ttpixel.name,
                                    content_type: _ttpixel.type,
                                    contents: _ttpixel.contents,
                                    currency: curr,
                                    num_items: _ttpixel.num,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'Search' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_category: _ttpixel.category,
                                content_id: _ttpixel.ids,
                                contents: _ttpixel.contents,
                                currency: curr,
                                search_string: _ttpixel.search,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_category: _ttpixel.category,
                                    content_id: _ttpixel.ids,
                                    contents: _ttpixel.contents,
                                    currency: curr,
                                    search_string: _ttpixel.search,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'StartTrial' || TTActionPage == 'Subscribe' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                currency: curr,
                                predicted_ltv: _ttpixel.ltv,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    currency: curr,
                                    predicted_ltv: _ttpixel.ltv,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else if( TTActionPage == 'ViewContent' ){
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage, {
                                content_id: _ttpixel.ids,
                                content_category: _ttpixel.category,
                                content_name: _ttpixel.name,
                                content_type: _ttpixel.type,
                                contents: _ttpixel.contents,
                                currency: curr,
                                value: filterPricePixel(TTValue),
                            });
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage, {
                                    content_id: _ttpixel.ids,
                                    content_category: _ttpixel.category,
                                    content_name: _ttpixel.name,
                                    content_type: _ttpixel.type,
                                    contents: _ttpixel.contents,
                                    currency: curr,
                                    value: filterPricePixel(TTValue),
                                });
                            });
                        }
                    }
                    else{
                        if( TTPixelSelect == 'all' ){
                            ttq.track( TTActionPage);
                        }
                        else{
                            $.each(TTPixelSelect, function( ind, tt_id ) {
                                ttq.instance(tt_id).track( TTActionPage);
                            });
                        }
                    }
                }
            }

            $(document).on('click', 'input[name="woocommerce_checkout_place_order"]', function() {
                localStorage.setItem("cepatlakoo_reg", $('input#createaccount').val());
            });

            var CREGIS = localStorage.getItem("cepatlakoo_reg");
            var WCFBLoc = $('div.cepatlakoo-fbpixel').attr('wcfb-loc');
            if (WCFBLoc == 'thankyou' && CREGIS == '1') {
                // fbq('track', 'CompleteRegistration', {
                //     campaign_url: FBCampaign,
                //     content_name: FBContent,
                //     product_id: FBProductID
                // });
                // Using Campaign url

                cl_trigger_fbpixel_api('CompleteRegistration');
                if (_ttpixel.ttpixel) {
                    ttq.track( 'CompleteRegistration');
                }
            }

            var nArray = new Array();

            //Add to Cart Product FB TRACK
            $(document).on('click', "button.single_add_to_cart_button, a.single_add_to_cart_button, a.add_to_cart_button, a.ajax_add_to_cart", function() {
                var pricetag;

                if( $(this).parents('.type-product').hasClass('product-type-grouped') ){
                    $('.product-type-grouped input[type="number"].qty').filter(function() {
                        if( parseInt($(this).val(), 10) > 0 ){
                            var parent = $(this).closest('.woocommerce-grouped-product-list-item');
                            if (parent !== undefined){
                                var prod_id = parent.attr('id').split("product-");
                                prod_id = prod_id.pop('');

                                var pricetag = cl_get_pricetag( $(this), parent);
                                var name = parent.find('.woocommerce-grouped-product-list-item__label a').text();

                                // fbq('track', 'AddToCart', {
                                //     value: filterPricePixel(pricetag),
                                //     currency: _warrior.currency_woo,
                                //     content_name: name,
                                //     product_id: prod_id
                                // });
                                
                                cl_trigger_fbpixel_api('AddToCart', {
                                    value: filterPricePixel(pricetag),
                                    currency: _warrior.currency_woo,
                                    content_name: name,
                                    product_id: prod_id
                                });

                                if (_ttpixel.ttpixel) {
                                    ttq.track( 'AddToCart', {
                                        value: filterPricePixel(pricetag),
                                        currency: _warrior.currency_woo,
                                        content_name: name,
                                        product_id: prod_id
                                    });
                                }
                            }
                        }
                    });
                }else{
                    var idProd = cl_get_productID($(this));
                    if ($.inArray(idProd, nArray) == -1) {
                        if ($(this).parents('.mfp-content').length) {
                            pricetag = cl_get_pricetag($(this), $('.mfp-content'));
                        } else {
                            pricetag = cl_get_pricetag($(this), $('div.product .summary'));
                            if (!pricetag) {
                                pricetag = $(this).parents('li').find('.price ins .woocommerce-Price-amount').text();
                                if (!pricetag) {
                                    pricetag = $(this).parents('li').find('.price .woocommerce-Price-amount').text();
                                } else {
                                    pricetag = $(this).parents('li').find('.price ins .woocommerce-Price-amount').text();
                                }
                            }
                        }
                        var name = $(this).parents('.type-product').find('.product_title').text();
                        // fbq('track', 'AddToCart', {
                        //     value: filterPricePixel(pricetag),
                        //     currency: _warrior.currency_woo,
                        //     content_name: name,
                        //     product_id: idProd
                        // });
                        
                        cl_trigger_fbpixel_api('AddToCart', {
                            value: filterPricePixel(pricetag),
                            currency: _warrior.currency_woo,
                            content_name: name,
                            product_id: idProd
                        });

                        if (_ttpixel.ttpixel) {
                            ttq.track( 'AddToCart', {
                                value: filterPricePixel(pricetag),
                                currency: _warrior.currency_woo,
                                content_name: name,
                                product_id: idProd
                            });
                        }
                        nArray.push(idProd);
                    }
                }
            });

            // Wishlist Product FB TRACK
            // $("a.add_to_wishlist").click(function() {
            //     var price = $(this).closest('li').find('.price .woocommerce-Price-amount').text();
            //     var res = price.split("Rp");
            //     var res = res.pop('');
            //     // fbq('track', 'AddToWishlist', {
            //     //     value: res,
            //     //     currency: 'IDR',
            //     //     campaign_url: FBCampaign,
            //     //     content_name: FBContent,
            //     //     product_id: FBProductID
            //     // });
            //     // Using Campaign url

            //     fbq('track', 'AddToWishlist', {
            //         value: res,
            //         currency: 'IDR',
            //     });
            // })

            if ($('body').hasClass('woocommerce-checkout')) {
                if (!$('body').hasClass('woocommerce-order-received')) {
                    var price = $('.woocommerce-checkout-review-order-table').find('.order-total .woocommerce-Price-amount').text();
                    var res = price.split("Rp");
                    var res = res.pop('');

                    // fbq('track', _fbpixel_checkoutEvent.type, {
                    //     value: filterPricePixel(price),
                    //     currency: _warrior.currency_woo,
                    // });

                    cl_trigger_fbpixel_api(_fbpixel_checkoutEvent.type, {
                        value: filterPricePixel(price),
                        currency: _warrior.currency_woo,
                    });

                    ttq.track( _ttpixel_checkoutEvent.type, {
                        value: filterPricePixel(price),
                        currency: _warrior.currency_woo,
                    });
                };
            };

            // WooCommmerce checkout
            // if ($('body').hasClass('woocommerce-checkout')) {
            //     var price = $('.woocommerce-table--order-details').find('.woocommerce-Price-amount').last().not('.woocommerce-Price-currencySymbol').text();
            //     if (price) {
            //         fbq('track', 'InitiateCheckout', {
            //             value: filterPricePixel(price),
            //             currency: _warrior.currency_woo,
            //         });
            //     }
            // };

            Array.min = function(array) {
                return Math.min.apply(Math, array);
            };
        };

        $('.gallery-thumbnail').each(function(index, element) {
            var get_gallery_thumb_opt = $(this).text();
            var that = $(this).siblings('.shop-detail-custom');
            if (get_gallery_thumb_opt == 'hide_gallery') {
                that.find('#single-product-hightlight .lSPager.lSGallery').css('display', 'none');
            } else if (get_gallery_thumb_opt == 'show_gallery') {
                that.find('#single-product-hightlight .lSPager.lSGallery').css('display', 'block');
            }
        });

        $('.single-slider, .warrior-z-carousel ul').addClass('owl-carousel').owlCarousel({ // first section slider
            items: 1,
            loop: true,
            margin: 0,
            singleItem: true,
            nav: false,
            thumbs: false,
            autoHeight: true,
            navText: [prevBtnIcon, nextBtnIcon]
        });

        $('.thumbnails-slider').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // child items selector, by clicking on it popup will open
                type: 'image',
                preloader: true,
                gallery: {
                    enabled: true
                }
            });

            $(this).find('.owl-thumbs button').each(function() { // the containers for all your galleries
                var imageSource = $(this).text();
                $(this).html('<img src="' + imageSource + '">');
            });
        });

        $('.cepatlakoo-gallery.file').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // child items selector, by clicking on it popup will open
                type: 'image',
                preloader: true,
                gallery: {
                    enabled: true
                }
            });
        });

        $('.main-cart-area .cross-sells').detach().appendTo('.main-cart-area');

        $('form').each(function() {
            $(this).addClass("ui form");
        });

        $('select').each(function() {});

        $('.btn.cepatlakoo-ajax-quick-view').on('click',(function(qv) {
            qv.preventDefault();
            return false;
        }))

        $('.sorting-option .menu .item').on('click', function() {
            var locationCurrent = $(location).attr('href').split("?")[0],
                theFilter = $(this).data('value');
            window.location = locationCurrent + '/?orderby=' + theFilter;
        });

        //------------------------ cl wa rotator function ------------------------//
        if ( typeof cl_wa_rotator !== 'undefined' ) {
            var number = getCookie(cl_wa_rotator.cl_wa_cookies_name+'_number');
            var label = getCookie(cl_wa_rotator.cl_wa_cookies_name+'_label');
            
            if( number == cl_wa_rotator.cl_wa_number && label != cl_wa_rotator.cl_wa_label ){
                label = cl_wa_rotator.cl_wa_label;
                setCookie(cl_wa_rotator.cl_wa_cookies_name+'_label', cl_wa_rotator.cl_wa_label, cl_wa_rotator.cl_wa_expired_cookie, true);
            }

            if( $('a.fpa-whatsapp-btn').length > 0 ){
                var cur_number = $('a.fpa-whatsapp-btn').attr('data-number');
                if( number != '' && number != cur_number ){
                    var cur_label = $('a.fpa-whatsapp-btn').attr('title');
                    var msg = $('a.fpa-whatsapp-btn').attr('href');
                    var msg = msg.replace(cur_number, number);
                    if( label == cur_label ){
                        var html = $('a.fpa-whatsapp-btn').html();
                        var msg = msg.replace(cur_label, label);
                        html = html.replace(cur_label, label);
                        $('a.fpa-whatsapp-btn').attr('title', label);
                        $('a.fpa-whatsapp-btn').html(html);
                    }
                    else{
                        setCookie(cl_wa_rotator.cl_wa_cookies_name+'_label', cl_wa_rotator.cl_wa_label, cl_wa_rotator.cl_wa_expired_cookie, true);
                    }

                    $('a.fpa-whatsapp-btn').attr('href', msg);
                    
                }
                else if(number === ''){
                    setCookie(cl_wa_rotator.cl_wa_cookies_name+'_number', cl_wa_rotator.cl_wa_number, cl_wa_rotator.cl_wa_expired_cookie, true);
                    setCookie(cl_wa_rotator.cl_wa_cookies_name+'_label', cl_wa_rotator.cl_wa_label, cl_wa_rotator.cl_wa_expired_cookie, true);
                }
            }

            if( $('a.contact-wa-button').length > 0 ){
                $.each($('a.contact-wa-button'), function( index, value ) {
                    if( typeof label != undefined && label != 'undefined' ){
                        var cur_number = $(value).attr('data-number');
                        var cur_label = $(value).attr('title');
                        var cur_label = cur_label.replace(/^\s+|\s+$/g, "");
                        var msg = $(value).attr('href');
                        var msg = msg.replace(cur_number, number);
                        var html = $(value).html();
    
                        if( number != '' && number != cur_number ){
                            html = html.replace(cur_label, label);
                            $(value).attr('title', label);
                            $(value).html(html);
                            $(value).attr('href', msg);
                        }
                        else if( number != '' && number == cur_number && label != cur_label ){
                            var msg = msg.replace(cur_label, label);
                            html = html.replace(cur_label, label);
    
                            $(value).attr('title', label);
                            $(value).html(html);
                            $(value).attr('href', msg);
                        }
                        else if(number === ''){
                            setCookie(cl_wa_rotator.cl_wa_cookies_name+'_number', cl_wa_rotator.cl_wa_number, cl_wa_rotator.cl_wa_expired_cookie, true);
                            setCookie(cl_wa_rotator.cl_wa_cookies_name+'_label', cl_wa_rotator.cl_wa_label, cl_wa_rotator.cl_wa_expired_cookie, true);
                        }
                    }
                });
            }
        }

        // Populate payment confirmation fields
        // based on querystrings
        var orderid = GetURLParameter('orderid');
        var email = GetURLParameter('email');

        // Caldera Forms support, already deprecated.
        // Please use Fluent Forms instead
        if ( jQuery('.CF59a37f2528a10.caldera_forms_form').length > 0 && orderid != undefined && orderid.length > 3 && email != undefined &&  email.length > 3 ){
            $('#fld_8004623_1').val(email);
            $('#fld_3826030_1').val(orderid);

            cl_check_confirm_data();
        }

        jQuery(document).on('change', '#fld_8004623_1, #fld_3826030_1, form.frm-fluent-form.payment-confirmation-form input[name="email"], form.frm-fluent-form.payment-confirmation-form input[name="kode_pesanan"]', function($) {
            cl_check_confirm_data();
        });

        // Fluent Forms support
        if ( jQuery('body.confirmation-page button.cepatlakoo-confirmation').length > 0 ) {
            jQuery('body.confirmation-page form.frm-fluent-form').addClass('payment-confirmation-form');
            
            if ( jQuery('form.frm-fluent-form.payment-confirmation-form').length > 0 && orderid != undefined && orderid.length > 3 && email != undefined &&  email.length > 3 ){
                jQuery('form.frm-fluent-form.payment-confirmation-form input[name="email"]').val(email);
                jQuery('form.frm-fluent-form.payment-confirmation-form input[name="kode_pesanan"]').val(orderid);

                cl_check_confirm_data();
            }
        }

        if ( jQuery('body.confirmation-page form.frm-fluent-form.payment-confirmation-form').length > 0 && orderid != undefined && orderid.length > 3 && email != undefined &&  email.length > 3 ) {
            var emailSelector = 'body.confirmation-page form.frm-fluent-form.payment-confirmation-form input[type="email"].confirmation-email';
            var orderidSelector = 'body.confirmation-page form.frm-fluent-form.payment-confirmation-form input.confirmation-order-id';
            var paramSelectors = emailSelector + ', ' + orderidSelector;

            // console.log(paramSelectors);

            $(emailSelector).val(email);
            $(orderidSelector).val(orderid);

            cl_check_confirm_data();
            
            jQuery(document).on('change', paramSelectors, function($) {
                cl_check_confirm_data();
            });
        }

        if ( jQuery('.summary.entry-summary').length > 0 ) {
            jQuery('.summary.entry-summary').addClass(_cepatlakoo_cart.cl_cart_class);
        }

    });

    //------------------------- Window Load & Ready -------------------------//
    // Sticky button
    function stickyButton() {

        $('.sticky-button.cloned').each(function() {
            var height = $(this).children('a:last-child').outerHeight();

            if ($(this).length) {
                $('#colofon').css('padding-bottom', height);
                $('#someone-purchased').css('margin-bottom', height);
                $('#backtotop').css('margin-bottom', height / 2);
                $('body').addClass('someone-contact');
            } else {
               $('body').removeClass('someone-contact');
            }
        })
    }

    $(window).on('load', stickyButton);
    $(window).on('resize', stickyButton);
    
    // Mobile Menu function
    function mobileMenu() {
        $('#top-bar .row-bar').prepend('<div class="contact-wrappers"></div>');
        $('.contact-info, .flash-info, .search-tool').clone().prependTo('.contact-wrappers');
        $('body').append('<div id="mobile-menu" class="site-navigation mobile-navigation"><div class="close-menu"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.416 10L20 18.584L18.584 20L10 11.416L1.41602 20L0 18.584L8.58398 10L0 1.41602L1.41602 0L10 8.58398L18.584 0L20 1.41602L11.416 10Z" fill="#C4C4C4"/></svg></div><div id="mobile-menu-menu"><div class="mobile-menu-menu-expander">' + _warrior.js_textdomain[0] + ' <i class="icon-chevron-down"></i></div></div><div id="close-menu"></div></div>');
        $("#main-header .container").prepend('<div class="mobile-menu-trigger"><svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M7 17.5833H33V21.4167H7V17.5833ZM7 8H33V11.8333H7V8ZM7 27.1667H33V31H7V27.1667Z" fill="white"/></svg><label>Menu</label></div>');
        $(".user-account-menu").clone().prependTo('#mobile-menu-menu');
        $("#left-menu ul.mega-menu , #right-menu ul.mega-menu, ul.main-menu, nav.main-menu ul:first").clone().appendTo('#mobile-menu-menu');
        $("#mobile-menu ul li.menu-item-has-children, #mobile-menu .mega-menu-item-has-children").prepend('<div class="dropdowner"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27.5684 13.4082L29.9316 15.7617L20 25.6934L10.0684 15.7617L12.4316 13.4082L20 20.9766L27.5684 13.4082Z" fill="#c0c0c0"/></svg></div>');
        $('.contact-wrappers .search-trigger').on('click',(function(x) {
            $(this).parents('.search-tool').toggleClass("active");
            $('.search-widget-header').slideToggle();
            x.stopPropagation();
        }))

        $(".mobile-menu-trigger").on('click', function() {
            $("#mobile-menu, body").toggleClass("menu-opened");
        })

        $('.right-sidebar-shop-page').siblings('#breadcrumbs').prepend('<div class="sidebar-trigger">' + _warrior.js_textdomain[3] +'</div>');
        $('.right-sidebar-shop-page').prepend('<div class="close-sidebar"></div>')
        $(".sidebar-trigger, .close-sidebar").on('click', function() {
            $('body').toggleClass('sidebar-open');
            $('.right-sidebar-shop-page').toggleClass('active');
            $(this).toggleClass('active');
        })

        $('.mobile-menu-menu-expander').on('click', function() {
            $('#mobile-menu-menu').toggleClass('opened');
        })

        $('.close-filter').on('click',function() {
            $(this).parent().fadeOut();
        })

        $(".contact-tirgger").each(function() {
            $(this).on('click', function() {
                $(this).parent().toggleClass('active');
            })
        })

        $(".dropdowner").on('click', function() {
            $(this).siblings('.sub-menu, .mega-sub-menu').toggleClass('opened');
        })

        $("li.menu-item a").on('click', function() {
            var linkValue = $(this).attr('href');

            if(linkValue = '#') {
                $(this).siblings('.sub-menu, .mega-sub-menu').toggleClass('opened');
            }
        })

        $("#close-menu, .close-menu").on('click', function() {
            $("#mobile-menu, body").removeClass("menu-opened");
        });

        $(document).on('mousedown', function(e) {
            if (!$(e.target).is('#dialog, #dialog a')) {
                $('#dialog').fadeOut();
            }
        });

        $('ul.products li .add_to_cart_button.ajax_add_to_cart').on('click', function() {
            if( _cepatlakoo_cart.cl_cart_wc_ajax == 'yes' ){
                setTimeout(function() {
                    //$(".cart-holders").slideToggle();
                    loaderSidebarAdd();
                    triggerSidebar(false);
                    loaderSidebarClose();
                }, 2000);
            }
        });
    }

    //---------------------- cl copy total -----------------//
    $('.cl-copy-total').on('click', function() {
        var total = parseInt( $('.cl-total-value').attr('data-total') );
        var textArea = document.createElement("textarea");
        textArea.value = total;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        $(this).append('<span class="copied">' + _warrior.js_textdomain[4] + '</span>');

        setTimeout(function() {
           $('span.copied').fadeOut('slow', function(){
              $('span.copied').remove();
           });
        }, 1000)
    })

    //---------------------- cl copy rekening -----------------//
    $('.cl-copy-rekening').on('click', function() {
        var rek = parseInt( $(this).parents('.cl-copy-rek-value').attr('data-rek') );
        var textArea = document.createElement("textarea");
        textArea.value = rek;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        $(this).append('<span class="copied">' + _warrior.js_textdomain[4] + '</span>');

        setTimeout(function() {
           $('span.copied').fadeOut('slow', function(){
              $('span.copied').remove();
           });
        }, 1000)
    })

    //---------------------- jRespond ----------------------//
    var jRes = jRespond([{
        label: 'mobile',
        enter: 0,
        exit: 800
    }, {
        label: 'small',
        enter: 0,
        exit: 1200
    }, {
        label: 'large',
        enter: 1201,
        exit: 10000
    }]);

    jRes.addFunc([{
        breakpoint: 'mobile',
        enter: function() {
            mobileMenu();
            var $infos = $('.sticky-button'),
                total = $('.sticky-button').length,

                last = $($infos[total - 1]),
                height = last.children('a:last-child').outerHeight(),
                whatsapp = last.children('.whatsapp');

            if (last.parents('.summary').length) {
                whatsapp.clone().appendTo('body').wrap('<div class="quick-contact-info sticky-button active cloned"></div>');
            } else {
                last.clone().addClass('active cloned').appendTo('body');
            }

            // $(document).on('click', ".quick-contact-info a.blackberry, .quick-contact-info a.sms, .quick-contact-info a.phone, .quick-contact-info a.line", function(e) { //delay quickcontact in mobile.
            //     // var redirectUrl = $(this).attr("href");
            //     // e.preventDefault();
            //     // setTimeout(function() {
            //     //     window.location.replace(redirectUrl);
            //     // }, 200);

            //     var pixels = $(this).attr("fb-pixel");
            //     alert(pixels);
            //     console.log(_fbpixel.fbpixel && pixels);
            //     if (_fbpixel.fbpixel && pixels) {
            //         fbq('track', pixels);
            //     }
            // });
        },
        exit: function() {
            $('.quick-contact-info.sticky-button.active.cloned').remove();
            $('body').removeClass('someone-contact');
            $('#backtotop, #someone-purchased').css('margin-bottom', '');
            $('#colofon').css('padding-bottom', '');

            $("#mobile-menu, .sidebar-trigger, .close-filter, .mobile-menu-trigger, .contact-tirgger, .contact-wrappers, .close-sidebar").remove();
        }
    }, {
        breakpoint: 'small',
        enter: function() {


            mobileMenu();
        },
        exit: function() {
            $("#mobile-menu, .sidebar-trigger, .close-filter, .mobile-menu-trigger, .contact-tirgger, .contact-wrappers").remove();
        }
    }, {
        breakpoint: 'large',
        enter: function() {
            $("#mobile-menu, .sidebar-trigger, .close-filter, .mobile-menu-trigger, .contact-tirgger, .contact-wrappers").remove();
            $('ul.products li .add_to_cart_button.ajax_add_to_cart').on('click', function() {
                if( _cepatlakoo_cart.cl_cart_wc_ajax == 'yes' ){
                    setTimeout(function() {
                        loaderSidebarAdd();
                        triggerSidebar(false);
                        loaderSidebarClose();
                    }, 1000);
                }
            });

            $('.single_add_to_cart_button').on('click', function() {
                localStorage.addToCart = "1";
            });

            if ($('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-shop')) {
                localStorage.addToCart = "0";
            }

            if (localStorage.addToCart == "1") {
                setTimeout(function() {
                    loaderSidebarAdd();
                    triggerSidebar();
                    loaderSidebarClose();
                    localStorage.addToCart = "0";
                }, 1000);
            }
            $(document).on('mousedown', function(e) {
                if (!$(e.target).is('.cart-holders,.cart-holders input, .cart-counter,.cart-holders div,.cart-holders ul,.cart-holders li, .cart-holders p,.cart-holders span,.cart-holders a')) {
                    $('body').removeClass('cart-opened');
                }
                if (!$(e.target).is('#dialog, #dialog a')) {
                    $('#dialog').fadeOut();
                }
            })
        }
    }]);
    //---------------------- jRespond ----------------------//

    //---------------- CL checkout via WhatsApp ----------------//
    $(document).on('click', '.cl-checkout-whatsapp', function(e){
        $( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        $.ajax({
            type: 'POST',
            url: wc_ajax.ajax_url,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            enctype: 'multipart/form-data',
            data: $('form.woocommerce-checkout').serialize(),
            success: function (result) {
                // console.log(result); // For testing (to be removed)
                if ( 'success' === result.result ) {
                    if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
                        window.location = result.redirect;
                    } else {
                        window.location = decodeURI( result.redirect );
                    }
                } else if ( 'failure' === result.result ) {
                    // console.log(result.messages);
                    $('.woocommerce-checkout').prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + result.messages + '</div>' );
                } else {
                    // console.log('Invalid response');
                }
                $( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).unblock();
            },
        });
        e.preventDefault();
    });

    //---------------- Add class list product ----------------//
    $("ul.products").addClass("list-custom-btn");
    
    if($('.buy-via-whatsapp').is(':visible')){
        $('.buy-via-whatsapp:visible').siblings('.add_to_cart_button, .product_type_variable, .product_type_simple').addClass("cart-small");
    }
    
    function cl_trigger_fbpixel_api(event, datas = ''){
        console.log('trigger cl_trigger_fbpixel_api');
        if( _fbpixel.fbpixel && _fbpixel.fbpixel_api_token && (jQuery.inArray(event, _fbpixel.fbpixel_api_event_list) !== -1 || (_fbpixel.event_ori == 'custom' && jQuery.inArray('custom', _fbpixel.fbpixel_api_event_list  !== -1) ) ) && _fbpixel.integration_type == 'convertions_and_meta'){
            console.log('use API: '+event);
            jQuery.ajax({
                type: "POST",
                url: wc_ajax.ajax_url,
                data: {
                    'action': 'cepatlakoo_fbpixel_api',
                    'agent' : navigator.userAgent,
                    'event' : event,
                    'datas' : datas
                },
                success:function(resp) {
                    if( resp.status ){
                        console.log(resp)
                    }
                    else{
                        console.log(resp.status)
                        console.log(resp)
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
        else{
            console.log('use tracking: '+event);
            if( datas == '' )
                fbq('track', event);
            else{
                fbq('track', event, datas);
            }
        }
    }
});
