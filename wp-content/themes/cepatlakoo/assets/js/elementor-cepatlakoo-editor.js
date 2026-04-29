(function($) {

    'use strict';

    var buFFer = 0;
    var stat_select = 0;

    var prevBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M12.5918 2.43164L5.02344 10L12.5918 17.5684L10.2383 19.9316L0.306641 10L10.2383 0.0683594L12.5918 2.43164Z" fill="#C0C0C0"/></svg>';
    var nextBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M2.76172 0.0683594L12.6934 10L2.76172 19.9316L0.408203 17.5684L7.97656 10L0.408203 2.43164L2.76172 0.0683594Z" fill="#C0C0C0"/></svg>';


    function toolbar_ft_product() {
        var local = $("iframe").contents().find("li .custom-shop-buttons").length;
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-featured-product").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    $("iframe").contents().find("li .custom-shop-buttons").each(function() {
                        var theSibl = $(this).parent(),
                            theImage = theSibl.find('img'),
                            thisHeight = $(this).outerHeight(),
                            theHeight = theImage.outerHeight();

                        $(this).css('top', theHeight - thisHeight)
                    });

                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').html('<i class="fa icon-check"></i>');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').addClass('disabled');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse span.feedback').remove();

                    clearInterval(Listener);
                }
            }
        }, 1000);
    }

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
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

    function toolbar_rc_product() {
        var local = $("iframe").contents().find("li .custom-shop-buttons").length;
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-recent-product").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    $("iframe").contents().find("li .custom-shop-buttons").each(function() {
                        var theSibl = $(this).parent(),
                            theImage = theSibl.find('img'),
                            thisHeight = $(this).outerHeight(),
                            theHeight = theImage.outerHeight();

                        $(this).css('top', theHeight - thisHeight)
                    });

                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').html('<i class="fa icon-check"></i>');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').addClass('disabled');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse span.feedback').remove();

                    clearInterval(Listener);
                }
            }
        }, 1000);
    }

    function toolbar_bs_product() {
        var local = $("iframe").contents().find("li .custom-shop-buttons").length;
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-best-selling-product").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    $("iframe").contents().find("li .custom-shop-buttons").each(function() {
                        var theSibl = $(this).parent(),
                            theImage = theSibl.find('img'),
                            thisHeight = $(this).outerHeight(),
                            theHeight = theImage.outerHeight();

                        $(this).css('top', theHeight - thisHeight)
                    });

                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').html('<i class="fa icon-check"></i>');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse a').addClass('disabled');
                    $("iframe").contents().find('ul.products .yith-wcwl-wishlistexistsbrowse span.feedback').remove();

                    clearInterval(Listener);
                }
            }
        }, 1000);
    }

    function callDependencies($salt) {
        $("iframe").contents().find('.gallery-thumbnail').each(function(index, element) {
            var get_gallery_thumb_opt = $(this).text();
            var that = $(this).siblings('.shop-detail-custom');

            if (get_gallery_thumb_opt == 'hide_gallery') {
                that.find(".thumbnails-slider").addClass('owl-carousel').owlCarousel({
                    items: 1,
                    loop: false,
                    margin: 0,
                    nav: true,
                    dots: false,
                    singleItem: true,
                    thumbs: false,
                    thumbsPrerendered: true,
                    autoHeight: false,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
                
                $("iframe").contents().find('.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image').not(':first').hide();
            } else if (get_gallery_thumb_opt == 'show_gallery') {
                that.find(".thumbnails-slider").addClass('owl-carousel').owlCarousel({
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
            }
            $(that).find('.owl-thumbs button').each(function() { // the containers for all your galleries
                var imageSource = $(this).text();
                $(this).html('<img src="' + imageSource + '">');
            });
        });

        $("iframe").contents().find('.variation-type').each(function() {
            if($(this).text() == 2 ){
                var that = $(this);
                $(this).siblings('.shop-detail-custom').find('table.variations select').each(function(index, element) {
                    var label = $(this).children("option:selected").text();

                    var tab_attribs = $(this).find('select option').map(function() {
                        return $(this).attr("value");
                    });

                        $(this).removeClass('dropdown');
                        $(this).attr('id', 'variation' + index);
                        $(this).wrap("<div class='warrior-variation'></div>")
                            .parent()
                            .after()
                            .append("<div id='" + index + "' class='size-select-widget'><ul></ul>");
                        $(element).each(function(idx, elm) {
                           $(this).find('option').each(function(id, el) {
                                if( id > 0 ){
                                    $(this).parent().parent().find('.size-select-widget ul:last').append('<li data-val="' + el.getAttribute("value") + '">' + el.text + '</li>');
                                }
                            });
                        });
                        $(this).remove();
                    $(that).siblings('.shop-detail-custom').find('.size-select-widget ul li').on('click', function() { // when mouse clicked fuzzy select
                        $(that).siblings('.shop-detail-custom').find('#'+ index +'.size-select-widget ul li').removeClass("selected");
                        $(this).addClass("selected");
                        var selectedLI = $(this).data('val');
                        // console.log(selectedLI);
                        $('select#variation' + index).val(selectedLI).trigger('change');
                    });
                    $(this).hide();
                });
                stat_select = 1;
            } else if ( $(this).text() == 1) {
                $('table.variations select').removeClass('ui dropdown');
            }
        });

        $("iframe").contents().find('.thumbnails-slider').each(function() { // the containers for all your galleries
            $("iframe").contents().find(this).magnificPopup({
                delegate: 'a', // child items selector, by clicking on it popup will open
                type: 'image',
                preloader: true,
                // gallery: {
                //     enabled: true
                // }
            });
        });

        $("iframe").contents().find('.woocommerce .product-carousel ul.products').each(function() { // the containers for all your galleries
            var nav = $("iframe").contents().find(this).parents('.product-carousel').attr('data-nav');
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

            $("iframe").contents().find(this).addClass('owl-carousel').owlCarousel(carouselParams);
        });
    }

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

    function get_slideshow() {
        $("iframe").contents().find('.preload').fadeOut('slow'); // preloader
        $("iframe").contents().find('.preload').remove(); // preloader

        $("iframe").contents().find('.elementor-widget-cepatlakoo-slideshow').each(function(index) {
            var cepatlakooCarouselItem = $(this).find('.slide-carousel-item').html();
            var cepatlakooTypeSlider = $(this).find('section.cepatlakoo-slideshow div:first').attr('class');
            var cepatlakooCarouselRotate = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-rotate') === 'true') ? true : false;
            var cepatlakooCarouselLoop = ( $(this).find('section.cepatlakoo-slideshow div:first').attr('data-loop') === 'true') ? true : false;
            var cepatlakooCarouselDelay = $(this).find('section.cepatlakoo-slideshow div:first').attr('data-delay');
            var cepatlakooTypeSlidershow = false;
            var cepatlakooItemSlidershow = 1;

            if (cepatlakooTypeSlider == 'carousel') {
                cepatlakooTypeSlidershow = false;
            } else if (cepatlakooTypeSlider == 'fullwidth') {
                cepatlakooTypeSlidershow = true;
            }

            if (cepatlakooTypeSlider == 'cepatlakoo-carousel') {
                $(this).find('.cepatlakoo-carousel').addClass('owl-carousel').owlCarousel({ // first section slider
                    items: cepatlakooCarouselItem,
                    singleItem: false,
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
            // sliderAutoHeight();

        });
    }

    $(document).ready(function($) {
        setTimeout(function() {
            if ($("iframe").contents().find('.size-select-widget').length == 0) {
                $("iframe").contents().find('.warrior-variation .ui.dropdown.selection').hide();
            } else {
                $("iframe").contents().find('.warrior-variation .ui.dropdown.selection').show();
            }
            callDependencies('cepatlakoo');
        }, 5000);

        $("iframe").contents().find(".tweets ul").addClass('owl-carousel').owlCarousel({ items: 1, loop: !0, margin: 0, controls: !0, animation: "fade", autoHeight: !0 });

        toolbar_ft_product();
        toolbar_rc_product();
        toolbar_bs_product();
    });

    $('iframe').on('load', function(){
        setTimeout(function() {
            $("iframe").contents().find('.single-slider, .warrior-z-carousel ul').addClass('owl-carousel').owlCarousel({ // first section slider
                items: 1,
                loop: true,
                margin: 0,
                singleItem: true,
                thumbs: false,
                nav: false,
                autoHeight: true,
                navText: [prevBtnIcon, nextBtnIcon]
            });
        }, 4000);
    });

    // Twitter
    $(document).on('input propertychange paste change', "input", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-twitter-feeds").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    $("iframe").contents().find(".single-slider").addClass('owl-carousel').owlCarousel({
                        items: 1,
                        loop: false,
                        margin: 0,
                        controls: false,
                        thumbs: false,
                        singleItem: true,
                        autoHeight: true,
                        animation: "fade",
                    });
                    clearInterval(Listener);
                }
            }
        }, 1000);
    }, 600));

    // VARIASI
    $(document).on('change', "select[data-setting='cepatlakoo_variation_style']", debounce(function() {
        // alert('variation');
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-single-product-highlight").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    callDependencies('cepatlakoo');
                    clearInterval(Listener);
                }
            }
        }, 500);
    }, 1000));

    // PRODUCT ID
    $(document).on('keyup', "input[data-setting='cepatlakoo_product_id']", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-single-product-highlight").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    clearInterval(Listener);
                    callDependencies();
                    clearInterval(Listener);
                }
            }
        }, 400);
    }, 400));

    $(document).on('change', '.elementor-control-custom_height input[type="number"], .elementor-control-custom_height ', debounce(function() {
        sliderAutoHeight();
    }, 100));

    $(document).on('slide', '.elementor-control-custom_height .ui-slider', debounce(function() {
        sliderAutoHeight();
    }, 100));

    $(document).ready(function() {
        setTimeout(function() {
            document.getElementById('elementor-preview-iframe').contentDocument.location.reload(true);
        }, 2000);
        sliderAutoHeight();
    });

    // Show Thumbnail
    $(document).on('change', "select[data-setting='cepatlakoo_display_gallery_thumbnail']", debounce(function() {
        var local = $("iframe").contents().find(".thumbnails-slider").length;
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-single-product-highlight").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    callDependencies('cepatlakoo');
                    clearInterval(Listener);
                }
            }
        }, 400);
    }, 400));

    $(document).on('change', "select[data-setting='cepatlakoo_display_additional_info'], select[data-setting='cepatlakoo_display_short_description']", debounce(function() {
        var local = $("iframe").contents().find(".thumbnails-slider").length;
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-single-product-highlight").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    callDependencies('cepatlakoo');
                    clearInterval(Listener);
                }
            }
        }, 400);
    }, 400));

    // Carousel
    $(document).on('input propertychange paste change', "input, select", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            callDependencies('cepatlakoo');
            get_slideshow();
        }, 1000);
        // console.log('change');
    }, 600));

    // Countdown
    $(document).on('change', "select[data-setting='cepatlakoo_countdown_id']", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-countdown").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                // if (stat_load == 1) {
                if (true) {
                    var get_scarcity_countdown_type = $("iframe").contents().find('.sc-type').text();
                    var get_scarcity_countdown_date_time = $("iframe").contents().find('.sc-time').text();
                    var get_scarcity_countdown_cookies = $("iframe").contents().find('.sc-cookies').text();
                    var get_scarcity_countdown_timer = $("iframe").contents().find('.sc-timer').text();

                    if (get_scarcity_countdown_type == "Evergreen Countdown") {
                        if (get_scarcity_countdown_date_time != null || get_scarcity_countdown_date_time != '') {
                            var finalDate = get_scarcity_countdown_date_time;

                            if(get_scarcity_countdown_cookies !== undefined){
                                finalDate = cl_countdown_get_cookies(get_scarcity_countdown_cookies, get_scarcity_countdown_date_time, get_scarcity_countdown_timer);
                            }

                            var date = new Date();
                            date.setTime(date.getTime() + (1 * 2 * 60 * 60 * 1000));
                            $("iframe").contents().find('#countdown').countdown(finalDate, function(event) {
                                if (event.strftime('%m') != '00') {
                                    $("iframe").contents().find('.number-container.month .number').html(event.strftime('%m'));
                                    $("iframe").contents().find('.number-container.month .text').html(event.strftime('Bulan'));
                                } else {
                                    $("iframe").contents().find('.number-container.month').hide();
                                }
                                if (event.strftime('%D') != '00') {
                                    $("iframe").contents().find('.number-container.day .number').html(event.strftime('%D'));
                                    $("iframe").contents().find('.number-container.day .text').html(event.strftime('Hari'));
                                } else {
                                    $("iframe").contents().find('.number-container.day').hide();
                                }
                                $("iframe").contents().find('.number-container.hour .number').html(event.strftime('%H'));
                                $("iframe").contents().find('.number-container.hour .text').html(event.strftime('Jam'));
                                $("iframe").contents().find('.number-container.minute .number').html(event.strftime('%M'));
                                $("iframe").contents().find('.number-container.minute .text').html(event.strftime('Menit'));
                                $("iframe").contents().find('.number-container.second .number').html(event.strftime('%S'));
                                $("iframe").contents().find('.number-container.second .text').html(event.strftime('Detik'));
                            });
                        }
                    } else {
                        var finalDate = get_scarcity_countdown_date_time;
                        $("iframe").contents().find('#countdown').countdown(finalDate, function(event) {
                            if (event.strftime('%m') != '00') {
                                $("iframe").contents().find('.number-container.month .number').html(event.strftime('%m'));
                                $("iframe").contents().find('.number-container.month .text').html(event.strftime('Bulan'));
                            } else {
                                $("iframe").contents().find('.number-container.month').hide();
                            }
                            if (event.strftime('%D') != '00') {
                                $("iframe").contents().find('.number-container.day .number').html(event.strftime('%D'));
                                $("iframe").contents().find('.number-container.day .text').html(event.strftime('Hari'));
                            } else {
                                $("iframe").contents().find('.number-container.day').hide();
                            }
                            $("iframe").contents().find('.number-container.hour .number').html(event.strftime('%H'));
                            $("iframe").contents().find('.number-container.hour .text').html(event.strftime('Jam'));
                            $("iframe").contents().find('.number-container.minute .number').html(event.strftime('%M'));
                            $("iframe").contents().find('.number-container.minute .text').html(event.strftime('Menit'));
                            $("iframe").contents().find('.number-container.second .number').html(event.strftime('%S'));
                            $("iframe").contents().find('.number-container.second .text').html(event.strftime('Detik'));
                        });
                    }
                }
                clearInterval(Listener);
            }
        }, 1000);
    }, 600));

    $(document).on('change click', "select[data-setting='cepatlakoo_post_id']", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-slideshow").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    get_slideshow();
                    clearInterval(Listener);
                }
            }
        }, 400);
    }, 300));

    $(document).on('click', ".elementor-editor-cepatlakoo-slideshow-settings a", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("iframe").contents().find("div.elementor-widget-cepatlakoo-slideshow").hasClass("elementor-loading")) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    get_slideshow();
                    clearInterval(Listener);
                }
            }
        }, 300);
    }, 300));

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $(document).on('click', "#elementor-template-library-menu-my-templates", debounce(function() {
        var idTemplate = [];
        var templateSpace = $('#elementor-template-library-templates-container').attr('data-template-source');
        if (templateSpace == 'local') {
            $("#elementor-template-library-templates-container div.elementor-template-library-template-local").each(function() {
                // $(this).removeClass('elementor-template-library-template-local');
                // $(this).addClass('elementor-template-library-template-remote');
                $(this).attr('id', getParameterByName('template_id', $(this).find('.elementor-template-library-template-controls .elementor-template-library-template-export a').attr('href')));
            });
        }
        $("#elementor-template-library-templates-container div.elementor-template-library-template-local").each(function() {
            var idImage = $(this).attr('id');
            $(this).find('.elementor-template-library-template-icon i').replaceWith("<img src=" + _cepatlakoo.imagelist[idImage] + " />");
        });
    }, 600));

    $(document).on('click', "#elementor-panel-footer-templates-modal", debounce(function() {
        var stat_load = 0;
        var Listener = setInterval(function() {
            if ($("#elementor-template-library-loading").length) {
                stat_load = 1;
            } else {
                if (stat_load == 1) {
                    setTimeout(function() {
                        $("#elementor-template-library-menu-my-templates").remove();
                        $("#elementor-template-library-header-menu").append('<div id="elementor-template-library-menu-my-templates" class="elementor-template-library-menu-item" data-type="page" data-template-source="local">Cepatlakoo Page</div>');
                        $("#elementor-template-library-header-menu").append('<div id="elementor-template-library-menu-my-templates" class="elementor-template-library-menu-item" data-type="section" data-template-source="local">Cepatlakoo Section</div>');
                    }, 1000);
                    clearInterval(Listener);
                }
            }
        }, 500);

        if (!$("#elementor-template-library-menu-my-templates").attr('data-type')) {
            $("#elementor-template-library-menu-my-templates").remove();
            $("#elementor-template-library-header-menu").append('<div id="elementor-template-library-menu-my-templates" class="elementor-template-library-menu-item" data-type="page" data-template-source="local">Cepatlakoo Page</div>');
            $("#elementor-template-library-header-menu").append('<div id="elementor-template-library-menu-my-templates" class="elementor-template-library-menu-item" data-type="section" data-template-source="local">Cepatlakoo Section</div>');
        }

    }, 600));

    $(document).on('click', "#elementor-template-library-menu-my-templates", debounce(function() {
        var TempType = $(this).attr('data-type');
        if (TempType == 'page') {
            $("#elementor-template-library-templates-container div.elementor-template-library-template-local").each(function() {
                var typeTemplate = $(this).find('.elementor-template-library-template-type').text();
                if (typeTemplate != 'Page') {
                    $(this).remove();
                }
            });
        } else if (TempType == 'section') {
            $("#elementor-template-library-templates-container div.elementor-template-library-template-local").each(function() {
                var typeTemplate = $(this).find('.elementor-template-library-template-type').text();
                if (typeTemplate != 'Section') {
                    $(this).remove();
                }
            });
        }
    }, 600));

    $(window).load(function() {
        buFFer = $("iframe").contents().find(".tweets ul li").length;
        $("iframe").contents().find(".tweets ul").addClass('owl-carousel').owlCarousel({
            thumbs: false,
            items: 1,
            loop: !0,
            margin: 0,
            controls: !0,
            animation: "fade",
            autoHeight: !0
        });
        var get_variation_type_opt = $("iframe").contents().find('#variation-type').text();
        if (get_variation_type_opt == 2) {
            $("iframe").contents().find('.warrior-variation .ui.dropdown.selection').hide();
        };

        $("iframe").contents().find('.sc-item').each(function() {
            var imG = $(this).find('img'),
                srC = imG.attr('src');
            $(this).css('background-image', 'url(' + srC + ')')
        })

        setTimeout(function() {
            get_slideshow();

            $("iframe").contents().find(".products li.product").matchHeight();
            
            if( _cepatlakoo_cart.cl_cart_elementor == 'no' ){
                $("iframe").contents().find(".elementor-menu-cart__main").remove();
            }
        }, 3500);
    });

    function sliderAutoHeight() {
        $("iframe").contents().find('.cepatlakoo-fullwidth').each(function() {
            var parentOne = $(this).parents('.elementor-element'),
                parentTwo = $(this).parents('.elementor-container'),
                mastherSliderHeight = $(this).parents('.elementor-container').outerHeight();

            if (parentOne.hasClass('elementor-section-height-min-height')) {
                $(this).parent().addClass('minHeight');
                $(this).css('min-height', mastherSliderHeight);
            }
        });
        // $("iframe").contents().find('.sc-item').each(function() {

    }
})(jQuery);
