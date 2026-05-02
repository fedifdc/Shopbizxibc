//For load more functionality
jQuery(document).ready(function () {
    /* Accordion Jquery For Blog */
   
    jQuery('.blog_template.accordion.accordion_wrapper').accordion({
        collapsible: true,
        active: 0,
        heightStyle: "content",
        classes: {
            "ui-accordion": "highlight"
        },
        header: "h3.accodine-title-wrap",
        icons: { "header": jQuery(this).find(".accordion-icon-header").attr('data-accordion-header'), "activeHeader": jQuery(this).find(".accordion-icon-header").attr('data-accordion-active-header') }
    });

    /*Start Same Height Jquery */      
        if (jQuery( '.boxy' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.post_hentry').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.post_hentry').outerHeight(sameheight);
        }
        if (jQuery( '.chapter' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.chapter-post-container').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.chapter-post-container').outerHeight(sameheight);
        }
        if (jQuery( '.famous' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.post-body-div').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.post-body-div').outerHeight(sameheight);
        }
        if (jQuery( '.glamour' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.glamour-blog').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.glamour-blog').outerHeight(sameheight);
        }
        if (jQuery( '.minimal' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.minimal-post-container').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.minimal-post-container').outerHeight(sameheight);
        }
        if (jQuery( '.quci' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.blog_item').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.blog_item').outerHeight(sameheight);
        }
        if (jQuery( '.wise_block' ).hasClass( 'same_height_all' )) {
            var sameheight = 0;
            jQuery('.bdp_blog_wraper').each(function() {
                if(sameheight < jQuery(this).outerHeight()){
                    sameheight = jQuery(this).outerHeight();
                };
            });
            jQuery('.bdp_blog_wraper').outerHeight(sameheight);
        }
        /*End Same Height Jquery */

    jQuery('#bdp_sort_by').on('change', function () {
        var key = encodeURI('sortby');
        var value = encodeURI(jQuery(this).val());
        var current_sort_value = document.location.search.substr(1).split('&');
        var i = current_sort_value.length;
        var x;
        while (i--) {
            x = current_sort_value[i].split('=');
            if (x[0] == key) {
                x[1] = value;
                current_sort_value[i] = x.join('=');
                break;
            }
        }
        if (i < 0) {
            current_sort_value[current_sort_value.length] = [key, value].join('=');
        }
        document.location.search = current_sort_value.join('&');
    });
    if (jQuery('.bdp_filter_layout').length > 0) {
    var layoutMode = '';
    if(jQuery('.bdp-js-masonry').hasClass('masonry')){
        layoutMode = 'masonry';
    } else if(jQuery('.bdp-row.masonry').hasClass('roctangle')) {
        layoutMode = 'masonry';
    }else{
        layoutMode = 'fitRows';
    }
    var filterclass = '';
    var filterfindclass = '';
    if(jQuery('.bdp-js-masonry').hasClass('masonry')){
        filterclass = '.bdp_filter_class .bdp-js-masonry';
        filterfindclass = '.bdp-js-masonry';
    } else if(jQuery('.blog_template').hasClass('boxy-clean')){
        filterfindclass = '.blog_template ul';
        filterclass = '.bdp_filter_class .bdp_post_list';
    } else if(jQuery('.bdp-row.masonry').hasClass('roctangle')){
        filterfindclass = '.bdp-row.masonry';
        filterclass = '.bdp-row.masonry';
    } else if(jQuery('.brit_co').hasClass('bdp_brit_co')){
        filterfindclass = '.bdp_post_list .bdp_brit_co';
        filterclass = '.bdp_post_list .bdp_brit_co';
    } else {
        filterclass = '.bdp_filter_class .bdp_post_list';
        filterfindclass = '.bdp_post_list';
    }
    if(filterclass != '') {
        jQuery(filterclass).isotope({
            itemSelector: '.bdp_blog_single_post_wrapp',
            layoutMode: layoutMode,
            containerStyle: {
                position: 'relative',
                overflow: 'visible'
            },
            getSortData: {
                category: '[data-slug]'
            },
        });
    }
    if(filterfindclass != '') {
        jQuery('.bdp_filter_post_ul').on('click', 'li', function () {
            jQuery('.bdp_filter_post_ul li a').removeClass('bdp_post_selected');
            jQuery(this).children('a').addClass('bdp_post_selected');
            var filterValue = jQuery(this).attr('data-filter');
            var filterID = jQuery(this).attr('data-id');
            var filterCount = jQuery(this).attr('data-count');
            jQuery('#cat_tag_count').val(filterCount);
            if( jQuery('#filter_type').val() == 'category' || jQuery('#filter_type').val() == 'product_cat' ) {
                jQuery('#filter_category').val(filterID);
            } else if( jQuery('#filter_type').val() == 'post_tag' ) {
                jQuery('#filter_tag').val(filterID);
            }
            jQuery(this).closest('.bdp_filter_class').find(filterfindclass).isotope({filter: filterValue});
            if( jQuery('#paged').val() > 1 ) {
                jQuery('#paged').val(1);
            }
            if( jQuery('.bdp_wrapper').find('#bdp-paged-hidden').length == 1 ) {
                isotop_filter_pagination();
            }
        });
    }
}

    if (jQuery('.chosen-select').length > 0) {
        var config = {
            '.chosen-select': {},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        jQuery(".chosen-select").chosen();
    }

    var $previous_height = jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-previous').outerHeight();
    var $next_height = jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-next').outerHeight();
    if ($previous_height > $next_height) {
        jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-next').css('min-height', $previous_height);
        jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-previous').css('min-height', $previous_height);
    } else if ($previous_height < $next_height) {
        jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-next').css('min-height', $next_height);
        jQuery('.bdp_single .navigation.post-navigation .nav-links .nav-previous').css('min-height', $next_height);
    }

    if (jQuery('.bdp-flexslider.flexslider').length > 0) {
        jQuery('.bdp-flexslider.flexslider').flexslider({
            animation: "slide",
            controlNav: false,
            prevText: "",
            nextText: "",
            rtl: ajax_object.is_rtl
        });
    }

    jQuery('#bdp-filer-change select').on('change', function () {
        var template = jQuery('#blog_template').val();

        if (template == 'boxy') {
            if (jQuery('.bdp-js-masonry.masonry').hasClass('bdp_boxy')) {
                jQuery('.bdp-js-masonry.masonry').html('');
            } else {
                jQuery('.bdp-js-masonry.masonry').html('');
            }
        }
        if (template == 'boxy-clean') {
            jQuery('.boxy-clean ul').html('');
        }
        else if (template == 'invert-grid') {
            jQuery('.bdp-row').html('');
        } else if (template == 'media-grid') {
            jQuery('.media-grid-wrapper').html('');
        } 
        else if (template == 'news') {
            jQuery('.news-wrapper').remove();
        } else if (template == 'brit_co') {
            jQuery('.brit_co.bdp_brit_co').html('');
        } else {
            var min_height = jQuery('.logbook.flatLine').height();
            jQuery('.logbook.flatLine').css('min-height', min_height);
            jQuery('.logbook.flatLine').append('<div class="bdp_logbook_loader"></div>');
        }
        jQuery('.wl_pagination_box').hide();
        jQuery('.bdp-load-more').hide();
        var $this = jQuery(this).val();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: 'action=filter_change&' + jQuery('#bdp-filer-change').serialize() + '&nonce=' + ajax_object.ajax_nonce,
            cache: false,
            success: function (response) {
                if(response == ''){
                    response = 'No post found';
                }
                if (template == 'boxy') {
                    if (jQuery('.bdp-js-masonry.masonry').hasClass('bdp_boxy')) {
                        jQuery('.bdp-js-masonry.masonry').append(response);
                        jQuery('.bdp-js-masonry.masonry').imagesLoaded(function () {
                            jQuery('.bdp-js-masonry.masonry').masonry('reloadItems').masonry('layout').masonry();
                        });
                    } else {
                        jQuery('.bdp-js-masonry.masonry').append(response);
                        jQuery(".bdp-js-masonry.masonry").append(response).masonry('reload');
                    }
                }
                else if (template == 'boxy-clean') {
                    jQuery('.blog_template.boxy-clean ul').append(response);
                    bdp_get_boxy_clean_height();
                }
                else if (template == 'invert-grid') {
                    jQuery('.bdp-row.invert-grid').html('');
                    jQuery('.bdp-row.invert-grid').append(response);
                }
                else if (template == 'media-grid') {
                    jQuery('.media-grid-wrapper').append(response);
                }
                else if (template == 'news') {
                    jQuery('.bdp_wrapper').prepend(response);
                } else if (template == 'brit_co') {
                    jQuery('.brit_co.bdp_brit_co').append(response);
                    bdp_get_brit_co_height();
                    
                } 
                // else if (template == 'famous') {
                //     jQuery('.famous').append(response);
                //     bdp_get_famous_height();

                // } 
                else if(template == 'wise_block' ) {
                    jQuery('.wise_block_wrapper').append(response);
                    bdp_get_wise_block_height();
                } else {
                    var jsmasonry = jQuery(".logbook.flatLine.flatNav.flatButton");
                    jsmasonry.html(response);
                    if ( jQuery( ".my_logbook" ).length ) {
                        jQuery(".my_logbook").logbook({
                            levels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            showYears: 10,
                            del: 130,
                            vertical: false,
                            isPostLink: false,
                            isYears: false,
                            triggerWidth: 800,
                            itemMargin: parseInt(jQuery('#blog_itemMargin').val()),
                            customSize: {
                                "sheet": {"itemWidth": jQuery('#blog_itemWidth').val(), "itemHeight": jQuery('#blog_itemHeight').val(), "margin": jQuery('#blog_itemMargin').val()},
                                "active": {"itemWidth": jQuery('#blog_itemWidth').val(), "itemHeight": jQuery('#blog_itemHeight').val(), "imageHeight": "150"}
                            },
                            id: 10,
                            easing: jQuery('#blog_easing').val(),
                            enableSwipe: true,
                            startFrom: 'first',
                            enableYears: true,
                            class: {
                                readMore: '.lb-read-more',
                            },
                            hideLogbook: (jQuery('#blog_hideLogbook').val() == 1) ? true : false,
                            hideArrows: false,
                            closeItemOnTransition: false,
                            autoplay: (jQuery('#blog_autoplay').val() == 1) ? true : false,
                            scrollSpeed: parseInt(jQuery('#blog_scrollSpeed').val()),
                        });
                    }
                    //For Cool Horizontal Template
                    var maxHeight = Math.max.apply(null, jQuery('.horizontal .post-title').map(function () {
                        return jQuery(this).height();
                    }).get());
                    jQuery('.horizontal .post-title').css('min-height', maxHeight + 70);
                    var maxHeight = Math.max.apply(null, jQuery('.horizontal .post-content-area .post_wrapper').map(function () {
                        return jQuery(this).height();
                    }).get());
                    jQuery('.horizontal .post-content-area .post_wrapper').css('min-height', Math.round(maxHeight));

                    jQuery('.logbook.flatLine').css('min-height', '');

                    //For Overlay Horizontal Template 
                    var logbookMaxHeight = Math.max.apply(null, jQuery('.overlay_horizontal .post_hentry').map(function () {
                        return jQuery(this).height();
                    }).get());
                    if (logbookMaxHeight == 0) {
                        logbookMaxHeight = '420';
                    }
                    jQuery('.logbook .lb-item .post-image img').css('min-height', Math.round(logbookMaxHeight));
                    jQuery('.logbook .lb-item .post-image').css('min-height', Math.round(logbookMaxHeight));
                }
                if ($this != null) {
                    jQuery('.filter.wl_pagination_box').show();
                    jQuery('.bdp-load-more').show();
                }
            }
        });
    });
    //For Overlay Horizontal Template
    var logbookMaxHeight = Math.max.apply(null, jQuery('.overlay_horizontal .post_hentry').map(function () {
        return jQuery(this).height();
    }).get());
    if (logbookMaxHeight == 0) {
        logbookMaxHeight = '420';
    }
    jQuery('.logbook .lb-item .post-image img').css('min-height', Math.round(logbookMaxHeight));
    jQuery('.logbook .lb-item .post-image').css('min-height', Math.round(logbookMaxHeight));
    //For Cool Horizontal Template
    var maxHeight = Math.max.apply(null, jQuery('.horizontal .post-title').map(function () {
        return jQuery(this).height();
    }).get());
    jQuery('.horizontal .post-title').css('min-height', maxHeight + 70);
    var maxHeight = Math.max.apply(null, jQuery('.horizontal .post-content-area .post_wrapper').map(function () {
        return jQuery(this).height();
    }).get());
    jQuery('.horizontal .post-content-area .post_wrapper').css('min-height', Math.round(maxHeight));
    //For load more functionality
    function bdp_load_onscroll_ajax(layout_id) {
        var layout_id_class = ".layout_id_"+layout_id;
        var paged = parseInt(jQuery(layout_id_class +' #bdp-load-more-hidden #paged').val());
        var $timeline_year = jQuery(layout_id_class +' #bdp-load-more-hidden #timeline_previous_year').val();
        paged = paged + 1;
        var max_num_pages = parseInt(jQuery(layout_id_class +' #bdp-load-more-hidden #max_num_pages').val());
        jQuery(layout_id_class +' .bdp-load-more-btn').addClass('loading');
        this_year = jQuery('#bdp-load-more-hidden #this_year').val();
        jQuery(layout_id_class +' .bdp-load-more-btn').fadeOut();
        if (paged <= max_num_pages) {
            jQuery(layout_id_class +' .loading-image').fadeIn();
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: 'action=get_load_onscroll_blog&' + jQuery(layout_id_class +' #bdp-load-more-hidden').serialize() + '&nonce=' + ajax_object.ajax_nonce,
                cache: false,
                success: function (response) {
                    var jsmasonry = jQuery(layout_id_class +" .bdp-load-onscroll-pre").find("div");
                    jQuery(window).on('scroll', bdp_animate_elems);
                    // loop through each item to check when it animates

                    if (jsmasonry.hasClass('bdp-js-masonry')) {
                        if (jQuery(layout_id_class + ' .bdp-js-masonry.masonry').hasClass('bdp_glossary') || jQuery(layout_id_class + ' .bdp-js-masonry.masonry').hasClass('bdp_boxy')) {
                            jQuery(layout_id_class +' .bdp-js-masonry.masonry').append(response);
                            jQuery(layout_id_class +' .bdp-js-masonry.masonry').imagesLoaded(function () {
                                jQuery(layout_id_class +' .bdp-js-masonry.masonry').masonry('reloadItems').masonry('layout').masonry();
                            });
                        } else {
                            jQuery(layout_id_class +' .bdp-js-masonry.masonry').append(response);
                            jQuery(layout_id_class + " .bdp-js-masonry.masonry").append(response).masonry('reload');
                        }
                    } else if (jsmasonry.hasClass('timeline_bg_wrap')) {
                        jQuery(layout_id_class + ' div.timeline_back').append(response);
                        var only_year = jQuery(layout_id_class + ' div.timeline_back').find('.timeline_year .only_year');
                        jQuery(only_year).each(function () {
                            $timeline_year = jQuery(this).text();
                        });
                        jQuery(layout_id_class +' #bdp-load-more-hidden #timeline_previous_year').val(jQuery.trim($timeline_year));
                    } else if (jsmasonry.hasClass('bdp-grid-row')) {
                        jQuery(layout_id_class + ' div.bdp-grid-row').append(response);
                        bdp_explore_content_center()
                    }
                    else if (jsmasonry.hasClass('story')) {
                        jQuery(layout_id_class + ' .story_wrapper').append(response);
                        var $get_year = this_year;
                        jQuery(response).find('.year-number').each(function () {
                            if (jQuery(this).html() != '') {
                                $get_year = jQuery(this).html();
                            }
                        });
                        jQuery(layout_id_class + ' #bdp-load-more-hidden #this_year').val(jQuery.trim($get_year));
                    } else if (jsmasonry.hasClass('boxy-clean')) {
                        jQuery(layout_id_class + ' .blog_template.boxy-clean > ul').append(response);
                        bdp_get_boxy_clean_height();
                    } else if (jsmasonry.hasClass('brit_co')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre div.brit_co.bdp_brit_co').append(response);
                        bdp_get_brit_co_height();
                    }  
                    // else if (jsmasonry.hasClass('famous')) {
                    //     jQuery(layout_id_class + ' .famous').append(response);
                    //     bdp_get_famous_height();
                    // } 
                    else if(jsmasonry.hasClass( 'wise_block_wrapper' )) {
                        jQuery(layout_id_class + ' .wise_block_wrapper').append(response);
                        bdp_get_wise_block_height();
                    }  else if (jsmasonry.hasClass('easy-timeline-wrapper')) {
                        jQuery(layout_id_class + ' .easy-timeline-wrapper .easy-timeline').append(response);
                        easy_timeline_effects();
                    } else if (jsmasonry.hasClass('my_diary_wrapper')) {
                        jQuery(layout_id_class + ' .bdp-load-onscroll-pre .my_diary_wrapper').append(response);
                    } else if (jsmasonry.hasClass('elina_wrapper')) {
                        jQuery(layout_id_class + ' .bdp-load-onscroll-pre .elina_wrapper').append(response);
                        social_share_div();
                    } else if (jsmasonry.hasClass('masonry_timeline_wrapper')) {
                        jQuery(layout_id_class + ' .bdp-load-onscroll-pre .masonry_timeline_wrapper').append(response);
                        masonry_timeline_fun();
                    } else if (jsmasonry.hasClass('invert-grid')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre div.bdp-row').append(response);
                    } else if (jsmasonry.hasClass('brite-wrapp')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .brite-wrapp').append(response);
                    } else if (jsmasonry.hasClass('media-grid')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .media-grid-wrapper').append(response);
                    } else if (jsmasonry.hasClass('chapter')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row').append(response);
                    } else if (jsmasonry.hasClass('roctangle')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row').append(response).masonry('reloadItems').masonry('layout').masonry();
                    }else if (jsmasonry.hasClass('advice')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row').append(response);
                    } else if(jsmasonry.hasClass('fairy')){
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre').append(response);
                        fairy_template_height();
                    } else if(jsmasonry.hasClass('famous')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row').append(response);
                    } else if (jsmasonry.hasClass('steps-wrapper')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre div.steps-wrapper .steps').append(response);
                        steps_effects();
                    } else if (jsmasonry.hasClass('glamour')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row').append(response);
                        glamour_template_height();
                    } else if (jsmasonry.hasClass('minimal')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre .bdp-row ').append(response);
                    } else if (jsmasonry.hasClass('clicky')) {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre').append(response);
                        clicky_template_height();
                    } else if (jsmasonry.hasClass('soft_block_wrapper')) {
                        jQuery(layout_id_class + ' .blog_template.soft_block_wrapper').append(response);
                    } else if (jsmasonry.hasClass('neaty_block_blog_wrapp')) {
                        jQuery(layout_id_class + ' .neaty_block_blog_wrapp').append(response);
                    } else if (jsmasonry.hasClass('schedule_wrapper')) {
                       jQuery(layout_id_class + ' .schedule_wrapper').append(response);
                    } else if (jsmasonry.hasClass('foodbox-blog-wrapp')) {
                        jQuery(layout_id_class + ' .foodbox-blog-wrapp').append(response);
                    } else if (jsmasonry.hasClass('blog_accordion_section')) { 
                        jQuery(layout_id_class + ' .blog_accordion_section').after(response);
                        jQuery('.blog_template.accordion.accordion_wrapper').accordion({
                            collapsible: true,
                            active: 0,
                            heightStyle: "content",
                            classes: {
                                "ui-accordion": "highlight"
                            },
                            header: "h3.accodine-title-wrap",
                            icons: { "header": jQuery(this).find(".accordion-icon-header").attr('data-accordion-header'), "activeHeader": jQuery(this).find(".accordion-icon-header").attr('data-accordion-active-header') }
                        });
                    } 
                    else {
                        jQuery(layout_id_class + ' div.bdp-load-onscroll-pre').append(response);
                    }
                    jQuery(layout_id_class + ' .bdp-load-more-btn').removeClass('loading');
                    jQuery(layout_id_class + ' .loading-image').fadeOut();
                    jQuery(layout_id_class + ' .bdp-load-more-btn').fadeIn();
                    jQuery(layout_id_class + ' div.bdp-load-onscroll').show();
                    jQuery(layout_id_class + ' #bdp-load-more-hidden #paged').val(paged);
                    jQuery(layout_id_class + ' .edd-no-js').hide();
                    if (paged == max_num_pages)
                        jQuery(layout_id_class + ' .bdp-load-more-btn').fadeOut();
                        if (jQuery(layout_id_class + ' .bdp-flexslider.flexslider').length > 0) {
                            jQuery(layout_id_class + ' .bdp-flexslider.flexslider').flexslider({
                                animation: "slide",
                                controlNav: false,
                                prevText: "",
                                nextText: "",
                                rtl: ajax_object.is_rtl
                            });
                        }
                }
            });
        }
        return false;
    }
    //For load more functionality
    jQuery(".bdp-load-more-btn").on('click',function () {
        var layout_id = jQuery(this).closest('.bdp_wrapper').find('#blog_shortcode_id').val();
        var $data = jQuery(this).closest('.bdp_wrapper').find('form#bdp-load-more-hidden').serialize();
        bdp_load_more_ajax($data, layout_id);
    });
    jQuery(".bdp-load-onscroll-btn").on('click',function () {
        var layout_id = jQuery(this).closest('.bdp_wrapper').find('#blog_shortcode_id').val();
        var $select_value = jQuery('#bdp-filer-change select').val();
        if ($select_value == null || null != jQuery('#bdp-filer-change .search-choice span').text()) {
            bdp_load_onscroll_ajax(layout_id);
        }
    });
    var ajax_window = jQuery(window);
    ajax_window.on("scroll touchstart", function () {
        if (jQuery('a.bdp-load-onscroll-btn').length >= 1) {
            var content_offset = jQuery('a.bdp-load-onscroll-btn').offset();
            var top = Math.round(content_offset.top - jQuery(window).height());
            if (jQuery(window).scrollTop() >= top) {
                if (jQuery('div.bdp-load-onscroll').is(':visible')) {
                    jQuery('div.bdp-load-onscroll').hide();
                    jQuery('.bdp-load-onscroll-btn').trigger('click');
                }
            }
        }
    });
    jQuery(function () {
        var elems = jQuery('.animateblock');
        var winheight = jQuery(window).height();
        elems.each(function () {
            var elm = jQuery(this);
            var topcoords = elm.offset().top;
            if (topcoords < winheight) {
                // animate when top of the window is 3/4 above the element
                elm.addClass('animated');
                elm.removeClass('animateblock');
            }
        });

        jQuery('.timeline').each(function () {
            if (jQuery(this).offset().top < winheight)
            {
                var width = jQuery(this).attr('data-width');
                jQuery(this).animate({
                    width: width
                }, 1000);
            }
        });

        jQuery(window).scroll(function () {
            bdp_animate_elems();
        });

    });

    jQuery(document).on('click', '.bdp-wrapper-like .bdp-like-button', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var post_id = jQuery(this).attr('data-post-id');
        var security = jQuery(this).attr('data-nonce');
        var allbuttons;
        allbuttons = jQuery('.bdp-button-' + post_id);
        var loader = allbuttons.next('#bdp-loader');
        if (post_id !== '') {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'get_bdp_process_posts_like',
                    post_id: post_id,
                    nonce: security
                },
                beforeSend: function () {
                    loader.html('<div class="bdp-like-loader"><i class="fas fa-spinner fa-pulse fa-1x fa-fw"></i></div>');
                },
                success: function (response) {
                    var icon = response.icon;
                    var count = response.count;
                    allbuttons.html(icon + count);
                    if (response.status === 'unliked') {
                        var like_text = ajax_object.like;
                        allbuttons.prop('title', like_text);
                        allbuttons.removeClass('liked');
                    } else {
                        var unlike_text = ajax_object.unlike;
                        allbuttons.prop('title', unlike_text);
                        allbuttons.addClass('liked');
                    }
                    loader.empty();
                }
            });

        }
        return false;
    });

    jQuery(document).on('click', '.glamour-social-links', function () {
        jQuery(this).parents('.glamour-blog').find('.glamour-social-cover').addClass('show');
    });
    jQuery(document).on('click', '.glamour-social-links-closed', function () {
        jQuery(this).parents('.glamour-social-cover').removeClass('show');
    });

    jQuery(document).on('click', '.fairy .fairy_footer .fairy-post-share', function() {
        jQuery(this).parents('.fairy_wrap').find('.fairy-social-cover').addClass('show');
        jQuery(this).parents('.fairy_wrap').find('.fairy-social-cover').css({'bottom': 0 });
    })
    jQuery(document).on('click', '.fairy .fairy-social-cover .fairy-social-div-closed', function() {
        var $fairy_wrap =  jQuery(this).closest('.fairy_wrap');
        var $minHeight = $fairy_wrap.find('.fairy_footer').outerHeight();
        var $socialHeight = $fairy_wrap.find('.fairy-social-cover .social-component').outerHeight();
        if($minHeight < $socialHeight) {
            $minHeight = $socialHeight;
        }
        var $minbottom = $minHeight + 25;
        jQuery(this).parents('.fairy-social-cover').removeClass('show');
        jQuery(this).parents('.fairy-social-cover').css({ 'bottom': '-'+ $minbottom +'px' });
    });
    jQuery(document).on('click', '.minimal-footer span.minimal-social-share-btn', function() {
        jQuery(this).parents('.minimal-entry').find('.minimal-social-cover').addClass('show');
        jQuery(this).parents('.minimal-entry').find('.minimal-social-cover').css({'bottom': '15px' });
    });
    jQuery(document).on('click', '.minimal-social-cover span.minimal-social-share-btn-close', function() {
        var $minHeight = jQuery('.minimal .minimal-entry .minimal-social-cover').outerHeight();
        $minHeight = $minHeight + 2;
        jQuery(this).parents('.minimal-entry').find('.minimal-social-cover').removeClass('show');
        jQuery(this).parents('.minimal-entry').find('.minimal-social-cover').css({ 'bottom': '-'+ $minHeight +'px' });
    });

    var $bdp_image_height = jQuery('.blog_template.bdp_blog_template .bdp-post-image img').height();
    if ($bdp_image_height == 0) {
        $bdp_image_height = 250
    }
    jQuery('.blog_template.bdp_blog_template .bdp-video').each(function() {
        jQuery(this).find('iframe').attr('height', $bdp_image_height);
    });

});

jQuery(window).resize(function () {
    bdp_get_boxy_clean_height();
    clicky_template_height();
    fairy_template_height();
    glamour_template_height();
    bdp_explore_content_center();
    accordion_header_height();
});

jQuery(function () {
    easy_timeline_effects();
    steps_effects();
    clicky_template_height();
    fairy_template_height();
    minimum_template_social();
    glamour_template_height();
    social_share_div();
    masonry_timeline_fun();
    accordion_header_height();
});

function accordion_header_height() {
    jQuery('.accordion_wrapper.accordion-template-4 .ui-accordion-header').each(function () {
        if(jQuery(window).width() > 991 ) {
            var accordion_header = jQuery(this).outerHeight() - 15;
        }
        if(jQuery(window).width() <= 991 ) {
            var acw = jQuery(this).outerHeight() ;
            var accordion_header = acw - 5 ;
        }
        var sid = jQuery(this).attr('aria-controls');
        jQuery('#'+sid).css("margin-top","-"+accordion_header+"px");
        jQuery('#'+sid).css("padding-top",accordion_header+"px");
    });
}

function social_share_div() {

    var maxWidth = Math.max.apply(null, jQuery('.post-media').map(function () {
        return jQuery(this).width();
    }).get());
    maxWidth = (maxWidth / 2) + 10;
    var cusstyle = '<style> .post-content-area:before { border-left-width: ' + Math.round(maxWidth) + 'px; } .post-media:before { border-right-width: ' + Math.round(maxWidth) + 'px; } .post-media:after { border-left-width: ' + Math.round(maxWidth) + 'px; } </style>'
    jQuery('head').append(cusstyle);

    var previous_post_height = jQuery('.elina .nav-links .previous-post').height();
    var next_post_height = jQuery('.elina .nav-links .next-post').height();
    if (previous_post_height < next_post_height) {
        jQuery('.elina .nav-links .previous-post').css('min-height', next_post_height);
    } else {
        jQuery('.elina .nav-links .next-post').css('min-height', previous_post_height);
    }

    jQuery('.elina-footer').find('.social-component').append("<div class='social-share social-close-btn'><a class='close-div'title='close' href='javascript:void(0)'>x</a></div>");

    jQuery('.post-share-div > a.post-share').on('click',function () {
        jQuery(this).parents('.elina-footer').find('.social-component').addClass('open-content');
        jQuery(this).parents('.elina-footer').find('.social-component > .social-share').addClass('add-eff');
    });

    jQuery('.social-share a.close-div').on('click',function () {
        jQuery(this).parents('.elina-footer').find('.social-component').removeClass('open-content');
        jQuery(this).parents('.elina-footer').find('.social-component > .social-share').removeClass('add-eff');
    });

    jQuery('.elina.bdp_archive').find('.author-avatar-div').append('<div class="fakegb"></div>');
}

function masonry_timeline_fun() {
    jQuery('.masonry-timeline-wrapp .post-footer').find('.social-component').append("<div class='social-share social-close-btn'><a class='close-div'title='close' href='javascript:void(0)'>x</a></div>");

    jQuery('.masonry-timeline-wrapp .post-footer').each(function () {

        var social_icon_height = jQuery(this).find('.social-component').outerHeight();
        var read_more_div_height = jQuery(this).find('.read-more-div').outerHeight();
        var metabox_height = jQuery(this).find('.metadatabox').outerHeight();

        var max_height = Math.max(social_icon_height, metabox_height + read_more_div_height);
        jQuery(this).css("max-height", max_height + 2);

    });

    jQuery('.masonry-timeline-wrapp .post-footer a.post-share').on('click',function () {
        jQuery(this).parents('.post-footer').find('.social-component').addClass('open-content');
        jQuery(this).parents('.post-footer').find('.metadatabox').css('opacity', 0);

        var metabox_height = jQuery(this).parents('.post-footer').find('.metadatabox').outerHeight();
        var transform_css = 'translateY(-' + (metabox_height - 1) + 'px)';
        jQuery(this).parents('.post-footer').find('.social-component').css('transform', transform_css);
        jQuery(this).parents('.post-footer').find('.social-component').css('opacity', 1);

    });
    jQuery('.masonry-timeline-wrapp .post-footer a.close-div').on('click',function () {
        //jQuery(this).parents('.social-component').removeClass('open-content');
        jQuery(this).parents('.post-footer').find('.metadatabox').removeAttr("style");
        jQuery(this).parents('.post-footer').find('.social-component').removeAttr("style");
    });
}

function easy_timeline_effects() {
    var effect = jQuery('.easy-timeline').attr('data-effect');
    jQuery('.easy-timeline li').each(function () {
        if (jQuery(this).offset().top > jQuery(window).scrollTop() + jQuery(window).height() * 0.75) {
            jQuery(this).addClass('is-hidden');
        } else {
            jQuery(this).addClass(effect);
        }
    });

    jQuery(window).on('scroll', function () {
        jQuery('.easy-timeline li').each(function () {
            if ((jQuery(this).offset().top <= (jQuery(window).scrollTop() + jQuery(window).height() * 0.75)) && jQuery(this).hasClass("is-hidden")) {
                jQuery(this).removeClass("is-hidden").addClass(effect);
            }
        });
    });
}

function steps_effects() {
    var effect = jQuery('.steps-wrapper .steps').attr('data-effect');
    jQuery('.steps-wrapper .steps li').each(function () {
        if (jQuery(this).offset().top > jQuery(window).scrollTop() + jQuery(window).height() * 0.75) {
            jQuery(this).addClass('is-hidden');
        } else {
            jQuery(this).addClass(effect);
        }
    });

    jQuery(window).on('scroll', function () {
        jQuery('.steps-wrapper .steps li').each(function () {
            if ((jQuery(this).offset().top <= (jQuery(window).scrollTop() + jQuery(window).height() * 0.75)) && jQuery(this).hasClass("is-hidden")) {
                jQuery(this).removeClass("is-hidden").addClass(effect);
            }
        });
    });
}

function fairy_template_height() {
    jQuery('.bdp_wrapper .blog_template.fairy').each(function() {
        var $minHeight = jQuery(this).find('.fairy_footer').outerHeight();
        var $socialHeight = jQuery(this).find('.fairy-social-cover .social-component').outerHeight();
        if($minHeight < $socialHeight) {
            $minHeight = $socialHeight;
        }
        var $minbottom = $minHeight + 25;
        jQuery(this).find('.fairy_wrap .fairy-social-cover').css({ 'min-height': $minHeight + 'px','bottom': '-'+ $minbottom +'px' });
        
    });

    jQuery('.bdp_blog_template.fairy').find('.fairy_wrap').removeClass('bdp-fairy-center');
    jQuery('.bdp_blog_template.fairy').find('.bdp-post-image').removeClass('bdp-fairy-center');
    if(jQuery(window).width() > 899) {
        setTimeout( function(){
            jQuery('.bdp_blog_template.fairy').each(function() {
                var $imgDivHeight = jQuery(this).find('.bdp-post-image').outerHeight();
                var $contentDivHeight = jQuery(this).find('.fairy_wrap').outerHeight();
                if($imgDivHeight > $contentDivHeight) {
                    jQuery(this).find('.fairy_wrap').addClass('bdp-fairy-center');
                } else if($imgDivHeight < $contentDivHeight) {
                    jQuery(this).find('.bdp-post-image').addClass('bdp-fairy-center');
                }
            });
        }, 1000 );
    }
}

function glamour_template_height() {
    var $minHeight = jQuery('.glamour .glamour-social-cover').outerHeight();
    $minHeight = $minHeight + 2;
    jQuery('.glamour .glamour-social-cover').css({ 'bottom': '-'+ $minHeight +'px'});
    if(jQuery(window).width() <= 767 ){
        var $minHeightss = jQuery('.glamour .author-avatar-div ').height();
        $minHeightss = $minHeightss + 189;
        jQuery('.glamour .author-avatar-div').css({ 'height': $minHeightss  });
    } else  {
        var $minHeightss = jQuery('.glamour .author-avatar-div .author_content p:nth-child(3)').height();
        $minHeightss = $minHeightss + 189;
        jQuery('.glamour .author-avatar-div').css({ 'height': $minHeightss  });
    }
    
}

function minimum_template_social() {
    var $minHeight = jQuery('.minimal .minimal-entry .minimal-social-cover').outerHeight();
    $minHeight = $minHeight + 2;
    jQuery('.minimal .minimal-entry .minimal-social-cover').css({ 'bottom': '-'+ $minHeight +'px' });

}

function clicky_template_height() {
    if(jQuery('.bdp_wrapper').hasClass('clicky_cover')) {
        jQuery('.bdp_wrapper.clicky_cover').imagesLoaded(function () {
            jQuery('.bdp_blog_template.clicky').each(function() {
                jQuery(this).find('.clicky-wrap').removeClass('bdp-clicky-center');
                jQuery(this).find('.bdp-post-image').removeClass('bdp-clicky-center');
                jQuery(this).find('.clicky-wrap').removeAttr('style');
                jQuery(this).find('.bdp-post-image').removeAttr('style');
                var $imgDivHeight = jQuery(this).find('.bdp-post-image').outerHeight();
                var $contentDivHeight = jQuery(this).find('.clicky-wrap').outerHeight();
                if($imgDivHeight > $contentDivHeight) {
                    jQuery(this).find('.clicky-wrap').addClass('bdp-clicky-center').css('min-height', $imgDivHeight + 'px');
                } else if($imgDivHeight < $contentDivHeight) {
                    jQuery(this).find('.bdp-post-image').addClass('bdp-clicky-center').css('min-height', $contentDivHeight + 'px');
                }
            });
        });

        jQuery('.bdp_archive.clicky .avtar-img').removeAttr('style');
        jQuery('.bdp_archive.clicky .author_content').removeAttr('style');
        var $authorImage = jQuery('.bdp_archive.clicky .avtar-img').outerHeight();
        var $authorContent = jQuery('.bdp_archive.clicky .author_content').outerHeight();

        if($authorImage > $authorContent) {
            jQuery('.bdp_archive.clicky .author_content').css('min-height', $authorImage + 'px');
        } else if($authorImage < $authorContent) {
            jQuery('.bdp_archive.clicky .avtar-img').css('min-height', $authorContent + 'px');
        }
    }

}


/*----------- code to apply same height to all template 4 li ---------------*/
function bdp_get_boxy_clean_height() {
    if(jQuery('.bdp_wrapper').hasClass('boxy-clean_cover')) {
        var divs = jQuery(".boxy-clean li.blog_wrap").not('.first_post');
        if (jQuery(window).width() > 980) {
            var column = 4;
            if (divs.hasClass('three_column')) {
                column = 3;
            } else if (divs.hasClass('two_column')) {
                column = 2;
            } else if (divs.hasClass('one_column')) {
                column = 1;
            }
        } else if (jQuery(window).width() <= 980 && jQuery(window).width() > 720) {
            var column = 4;
            if (divs.hasClass('three_column_ipad')) {
                column = 3;
            } else if (divs.hasClass('two_column_ipad')) {
                column = 2;
            } else if (divs.hasClass('one_column_ipad')) {
                column = 1;
            }
        } else if (jQuery(window).width() <= 720 && jQuery(window).width() > 480) {
            var column = 4;
            if (divs.hasClass('three_column_tablet')) {
                column = 3;
            } else if (divs.hasClass('two_column_tablet')) {
                column = 2;
            } else if (divs.hasClass('one_column_tablet')) {
                column = 1;
            }
        } else if (jQuery(window).width() <= 480) {
            var column = 4;
            if (divs.hasClass('one_column_mobile')) {
                column = 3;
            } else if (divs.hasClass('two_column_mobile')) {
                column = 2;
            } else if (divs.hasClass('three_column_mobile')) {
                column = 1;
            }
        }
        jQuery(".boxy-clean li.blog_wrap").removeAttr('style');    
    
        jQuery('.boxy-clean li.blog_wrap').imagesLoaded(function () {
            for (var i = 0; i < divs.length; i += column) {
                var heights = jQuery(".boxy-clean li.blog_wrap").not('.first_post').slice(i, i + column).map(function () {
                    return jQuery(this).height();
                }).get();
                var maxHeight = Math.max.apply(null, heights);
                if (screen.width > 640) {
                    jQuery(".boxy-clean li.blog_wrap").not('.first_post').slice(i, i + column).css('height', maxHeight + 60);
                }
            } 
        });
            
    }
}

function bdp_animate_elems() {
    var elems = jQuery('.animateblock');
    var winheight = jQuery(window).height();
    var wintop = jQuery(window).scrollTop(); // calculate distance from top of window

    // loop through each item to check when it animates
    elems.each(function () {
        var elm = jQuery(this);
        if (elm.hasClass('animated')) {
            return true;
        } // if already animated skip to the next item
        var topcoords = elm.offset().top; // element's distance from top of page in pixels
        if (wintop > (topcoords - (winheight * 0.6))) {
            // animate when top of the window is 3/4 above the element
            elm.addClass('animated');
            elm.removeClass('animateblock');
        }
    });
    jQuery('.timeline').each(function () {
        if (wintop > jQuery(this).offset().top - winheight)
        {
            var width = jQuery(this).attr('data-width');
            jQuery(this).animate({
                width: width
            }, 500);
        }
    });
}

//For load more functionality
function bdp_load_more_ajax($data, layout_id) {
    var layout_id_class = ".layout_id_"+layout_id;
    var paged = parseInt(jQuery(layout_id_class + ' #bdp-load-more-hidden #paged').val());
    var this_year = jQuery(layout_id_class + ' #bdp-load-more-hidden #this_year').val();
    var $timeline_year = jQuery(layout_id_class + ' #bdp-load-more-hidden #timeline_previous_year').val();
    paged = paged + 1;
    var max_num_pages = parseInt(jQuery(layout_id_class + ' #bdp-load-more-hidden #max_num_pages').val());
    jQuery(layout_id_class + ' .bdp-load-more-btn').addClass('loading');
    jQuery(layout_id_class + ' .bdp-load-more-btn').fadeOut();

    if (paged <= max_num_pages) {
        jQuery(layout_id_class + ' .loading-image').fadeIn();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: 'action=get_loadmore_blog&' + $data + '&nonce=' + ajax_object.ajax_nonce,
            cache: false,
            success: function (response) {
                var jsmasonry = jQuery(layout_id_class + " .bdp-load-more-pre").find("div");
                jQuery(window).on('scroll', bdp_animate_elems);
                // loop through each item to check when it animates

                if (jsmasonry.hasClass('bdp-js-masonry')) {
                    if (jQuery(layout_id_class + ' .bdp-js-masonry.masonry').hasClass('bdp_glossary') || jQuery(layout_id_class + ' .bdp-js-masonry.masonry').hasClass('bdp_boxy')) {
                        jQuery(layout_id_class + ' .bdp-js-masonry.masonry').append(response);
                        jQuery(layout_id_class + ' .bdp-js-masonry.masonry').imagesLoaded(function () {
                            jQuery(layout_id_class + ' .bdp-js-masonry.masonry').masonry('reloadItems').masonry('layout').masonry();
                        });
                    } else {
                        jQuery(layout_id_class + ' .bdp-js-masonry.masonry').append(response);
                        jQuery(layout_id_class + " .bdp-js-masonry.masonry").append(response).masonry('reload');
                    }
                } else if (jsmasonry.hasClass('timeline_bg_wrap')) {
                    jQuery(layout_id_class + ' div.timeline_back').append(response);
                    var only_year = jQuery(layout_id_class + ' div.timeline_back').find('.timeline_year .only_year');
                    jQuery(only_year).each(function () {
                        $timeline_year = jQuery(this).text();
                    });
                    jQuery(layout_id_class + ' #bdp-load-more-hidden #timeline_previous_year').val(jQuery.trim($timeline_year));
                }
                else if (jsmasonry.hasClass('story')) {
                    jQuery(layout_id_class + ' .story_wrapper').append(response);
                    var $get_year = this_year;
                    jQuery(response).find('.year-number').each(function () {
                        if (jQuery(this).html() != '') {
                            $get_year = jQuery(this).html();
                        }
                    });
                    jQuery(layout_id_class + ' #bdp-load-more-hidden #this_year').val(jQuery.trim($get_year));
                } else if (jsmasonry.hasClass('bdp-grid-row')) {
                    jQuery(layout_id_class + ' div.bdp-grid-row').append(response);
                    bdp_explore_content_center()
                } else if (jsmasonry.hasClass('boxy-clean')) {
                    jQuery(layout_id_class + ' .blog_template.boxy-clean > ul').append(response);
                    bdp_get_boxy_clean_height();
                } else if (jsmasonry.hasClass('brit_co')) {
                    jQuery(layout_id_class + ' div.bdp-row .bdp_brit_co').append(response);
                    bdp_get_brit_co_height();
                } 
                // else if (jsmasonry.hasClass('famous')) {
                //     jQuery(layout_id_class + ' .famous').append(response);
                //     bdp_get_famous_height();
                // } 
                else if(jsmasonry.hasClass('wise_block_wrapper')) {
                    jQuery(layout_id_class + ' .wise_block_wrapper').append(response);
                    bdp_get_wise_block_height();
                } else if (jsmasonry.hasClass('easy-timeline-wrapper')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre div.easy-timeline-wrapper .easy-timeline').append(response);
                    easy_timeline_effects();
                } else if (jsmasonry.hasClass('my_diary_wrapper')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .my_diary_wrapper').append(response);
                } else if (jsmasonry.hasClass('elina_wrapper')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .elina_wrapper').append(response);
                    social_share_div();
                } else if (jsmasonry.hasClass('masonry_timeline_wrapper')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .masonry_timeline_wrapper').append(response);
                    masonry_timeline_fun();
                } else if (jsmasonry.hasClass('invert-grid')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre div.bdp-row').append(response);
                } else if (jsmasonry.hasClass('brite-wrapp')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .brite-wrapp').append(response);
                } else if (jsmasonry.hasClass('media-grid')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .media-grid-wrapper').append(response);
                    jQuery('.bdp_blog_template.media-grid .content-container').css('margin', '0px');
                } else if (jsmasonry.hasClass('chapter') || jsmasonry.hasClass('glamour')) {
                    glamour_template_height();
                    jQuery(layout_id_class + ' .bdp-load-more-pre .bdp-row').append(response);
                } else if (jsmasonry.hasClass('roctangle')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .bdp-row').append(response).masonry('reloadItems').masonry('layout').masonry();                    
                } else if (jsmasonry.hasClass('advice')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .bdp-row').append(response);
                } else if(jsmasonry.hasClass('fairy')){
                    jQuery(layout_id_class + ' div.bdp-load-more-pre').append(response);
                    fairy_template_height();
                } else if(jsmasonry.hasClass('famous')) {
                    jQuery(layout_id_class + ' .bdp-load-more-pre .famous').append(response);
                } else if (jsmasonry.hasClass('steps-wrapper')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre div.steps-wrapper .steps').append(response);
                    steps_effects();
                } else if (jsmasonry.hasClass('glamour')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre .bdp-row').append(response);
                    glamour_template_height();
                } else if (jsmasonry.hasClass('minimal')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre .bdp-row').append(response);
                } else if (jsmasonry.hasClass('clicky')) {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre').append(response);
                    clicky_template_height();
                } else if (jsmasonry.hasClass('soft_block_wrapper')) {
                    jQuery(layout_id_class + ' .blog_template.soft_block_wrapper').append(response);
                } else if (jsmasonry.hasClass('neaty_block_blog_wrapp')) {
                    jQuery(layout_id_class + ' .neaty_block_blog_wrapp').append(response);
                } else if (jsmasonry.hasClass('schedule_wrapper')) {
                   jQuery(layout_id_class + ' .schedule_wrapper').append(response);
                } else if (jsmasonry.hasClass('foodbox-blog-wrapp')) {
                    jQuery(layout_id_class + ' .foodbox-blog-wrapp').append(response);
                } else if (jsmasonry.hasClass('blog_accordion_section')) { 
                    jQuery(layout_id_class + ' .blog_accordion_section').after(response);
                    jQuery('.blog_template.accordion.accordion_wrapper').accordion({
                        collapsible: true,
                        active: 0,
                        heightStyle: "content",
                        classes: {
                            "ui-accordion": "highlight"
                        },
                        header: "h3.accodine-title-wrap",
                        icons: { "header": jQuery(this).find(".accordion-icon-header").attr('data-accordion-header'), "activeHeader": jQuery(this).find(".accordion-icon-header").attr('data-accordion-active-header') }
                    });
                }
                else {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre').append(response);
                }

                jQuery(layout_id_class + ' .bdp-load-more-btn').removeClass('loading');
                jQuery(layout_id_class + ' .loading-image').fadeOut();
                jQuery(layout_id_class + ' .bdp-load-more-btn').fadeIn();
                jQuery(layout_id_class + ' #bdp-load-more-hidden #paged').val(paged);
                jQuery(layout_id_class + ' .edd-no-js').hide();
                if (paged == max_num_pages)
                    jQuery(layout_id_class + ' .bdp-load-more-btn').fadeOut();
                jQuery('.bdp-flexslider.flexslider').flexslider({
                    animation: "slide",
                    controlNav: false,
                    prevText: "",
                    nextText: "",
                    rtl: ajax_object.is_rtl
                });
            }
        });
    }
    return false;
}
jQuery(window).on('load',function () {
    if (jQuery('.masonry').length > 0) {
        setTimeout(function () {
            jQuery('.masonry').imagesLoaded(function () {
                jQuery('.masonry').masonry({
                    columnWidth: 0,
                    itemSelector: '.blog_masonry_item',
                    isResizable: true
                });
            });
        }, 500);
    }
    bdp_get_boxy_clean_height();
    bdp_get_brit_co_height();
    bdp_explore_content_center();
    // bdp_get_famous_height();
    bdp_get_wise_block_height();
    bdp_get_tabbed_height();
    if (jQuery('.bdp_filter_layout').length > 0) {
        var layoutMode = '';
        if(jQuery('.bdp-js-masonry').hasClass('masonry')){
            layoutMode = 'masonry';
        } else if(jQuery('.bdp-row.masonry').hasClass('roctangle')) {
            layoutMode = 'masonry';
        }else{
            layoutMode = 'fitRows';
        }
        var filterclass = '';
        var filterfindclass = '';
        if(jQuery('.bdp-js-masonry').hasClass('masonry')){
            filterclass = '.bdp_filter_class .bdp-js-masonry';
            filterfindclass = '.bdp-js-masonry';
        } else if(jQuery('.blog_template').hasClass('boxy-clean')){
            filterfindclass = '.blog_template ul';
            filterclass = 'blog_template ul';
        } else if(jQuery('.bdp-row.masonry').hasClass('roctangle')){
            filterfindclass = '.bdp-row.masonry';
            filterclass = '.bdp-row.masonry';
        } else if(jQuery('.brit_co').hasClass('bdp_brit_co')){
            filterfindclass = '.bdp_post_list .bdp_brit_co';
            filterclass = '.bdp_post_list .bdp_brit_co';
        } else {
            filterclass = '.bdp_filter_class .bdp_post_list';
            filterfindclass = '.bdp_post_list';
        }
        jQuery(filterclass).isotope({
            itemSelector: '.bdp_blog_single_post_wrapp',
            layoutMode: layoutMode,
            containerStyle: {
                position: 'relative',
                overflow: 'visible'
            },
            getSortData: {
                category: '[data-slug]'
            },
        });
        jQuery('.bdp_filter_post_ul').on('click', 'li', function () {
            jQuery('.bdp_filter_post_ul li a').removeClass('bdp_post_selected');
            jQuery(this).children('a').addClass('bdp_post_selected');
            var filterValue = jQuery(this).attr('data-filter');
            jQuery(this).closest('.bdp_filter_class').find(filterfindclass).isotope({filter: filterValue});
        });
    }
});

function bdp_explore_content_center() {
    if(jQuery('.bdp-grid-row .explore').length > 0) {
        var $explorerDiv = jQuery('.bdp-grid-row .explore');

        $explorerDiv.removeClass('explorer-clear');
        if(jQuery(window).width() < 641) {
            $explorerDiv.each(function() {
                jQuery(this).addClass('explorer-clear');
            });
        } else if(jQuery(window).width() < 981) {

            if($explorerDiv.hasClass('default')) {
                jQuery('.bdp-grid-row .explore:odd').addClass('explorer-clear');
            }
            if($explorerDiv.hasClass('repeat') && $explorerDiv.hasClass('reverse')) {
                $explorerDiv.each(function () {
                    var $alert = jQuery(this).data('alter');
                    if($alert%5 == 2 || $alert%5 == 4) {
                        jQuery(this).addClass('explorer-clear');
                    }
                });
            }
        } else {
            if($explorerDiv.hasClass('default')) {
                jQuery('.bdp-grid-row .explore:nth-child(3n)').addClass('explorer-clear');
            }
            if($explorerDiv.hasClass('repeat') || $explorerDiv.hasClass('reverse')) {
                $explorerDiv.each(function () {
                    var $alert = jQuery(this).data('alter');
                    if($alert%10 == 1 || $alert%10 == 3 || $alert%10 == 6 || $alert%10 == 8) {
                        jQuery(this).addClass('explorer-clear');
                    }
                });
            }
        }
    }
}
function bdp_get_brit_co_height() {
    var heights = jQuery(".image_wrapper").map(function () {
        return jQuery(this).height();
    }).get(),
            maxHeight = Math.max.apply(null, heights);
    var content_heights = jQuery(".content_wrapper").map(function () {
        return jQuery(this).height();
    }).get(),
    content_height = Math.max.apply(null, content_heights);
    jQuery('.britco').each(function () {
        var total_height = maxHeight + content_height + 5;
        jQuery(this).find('.bdp_blog_wraper').height(total_height);
    });
}
// function bdp_get_famous_height() {
//     var famous_content_heights = jQuery(".famous-grid .post-body-div").map(function () {
//         return jQuery(this).height();
//     }).get(),
//         famous_content_heights = Math.max.apply(null, famous_content_heights);
//     jQuery('.bdp-row.famous').each(function () {
//         var famous_total_height = famous_content_heights + 25;
//         jQuery(this).find('.famous-grid').height(famous_total_height);
//     });
// }
function bdp_get_wise_block_height() {
    var wise_block_heights = jQuery(".wise_block_blog").map(function () {
        return jQuery(this).height();
    }).get(),
    wise_block_heights = Math.max.apply(null, wise_block_heights);
    jQuery('.wise_block_wrapper').each(function () {
        var wise_block_total_height = wise_block_heights;
    
        // jQuery(this).find('.wise_block_blog').height(wise_block_total_height);
    });
}
function bdp_get_tabbed_height() {
    jQuery('.left-side.bdp-tabbed-all-post-content').imagesLoaded(function () {
        var leftContent = jQuery('.left-side.bdp-tabbed-all-post-content').outerHeight();
        var rightContent = jQuery('.right-side.bdp-tabbed-all-post-content').outerHeight();
        if(rightContent > leftContent ) {
             jQuery('.right-side.bdp-tabbed-all-post-content').css('overflow-y',  'scroll');
             jQuery('.right-side.bdp-tabbed-all-post-content').css('max-height', leftContent);
        } else {
            jQuery('.right-side.bdp-tabbed-all-post-content').css('overflow-y',  '');
           }
    });
}

jQuery(document).ready(function() {
    var icon_element = jQuery('.slides.design2 .mauthor .author');
    var date_element = jQuery('.slides.design2 .post-date > a');
    var timeline_date = jQuery('.timeline .date_wrap .datetime');
    icon_element.each(function() {
        if(jQuery(this).find('i')) {
            jQuery(this).find('i').remove();
            jQuery(this).prepend('By');
        }
    });
    date_element.each(function() {
        if(jQuery(this).find('i')) {
            jQuery(this).find('i').remove();
            jQuery(this).prepend('/ ');
        }
    });
    timeline_date.each(function(){
        if(jQuery(this).find('a').length == 0) {
            jQuery('.bdp_post_list.timeline .timeline.blog-wrap .datetime, .blog_template.timeline_cover .timeline.blog-wrap .datetime').css('position','absolute');
        }
    });
    jQuery('.blog_template.blog_carousel .slides li').each(function() {
        if(jQuery(this).find('.bdp-post-image a').length == 0 ) {
            jQuery(this).find('.blog_header').css('margin-top', '0px');
        }
    });
    jQuery('.blog_template.threed_carousel .slides li').each(function() {
        if(jQuery(this).find('.bdp-post-image a').length == 0 ) {
            jQuery(this).find('.blog_header').css('margin-top', '0px');
        }
    });
    jQuery('.blog_template.flip_book_3d .slides li').each(function() {
        if(jQuery(this).find('.bdp-post-image a').length == 0 ) {
            jQuery(this).find('.blog_header').css('margin-top', '0px');
        }
    });
    

    if(jQuery('.bdp_single.invert-grid .post-body-div').find('.bdp-post-image').length == 0 ) {
        jQuery('.bdp_single.invert-grid').find('.metadatabox').css('padding-top', '40px');
    }

    jQuery('.sharpen.bdp_blog_single_post_wrapp,.bdp_archive.sharpen').each(function() {
        if(jQuery(this).find('.bdp-post-image').length > 0 ) {
            jQuery(this).find('.blog_header.blog_header_img, .blog_header_img').css('margin-top', '-50px');
        }else{ 
            jQuery(this).find('.blog_header, .triangle_style').css('margin-top', '0px');
        }
        if(jQuery(this).find('.bdp-post-image.post-video').length > 0 ) {
            jQuery(this).find('.blog_header, .blog_header_img').css('margin-top', '-50px');
        }
    });
    if( jQuery('.bdp_sortby_wrap').length > 0 ) {
        jQuery('.bdp_sortby_wrap').parent().css("display", "grid");
    }
    if( jQuery('.bdp_single.media-grid.bottom .bdp_blog_template.media-grid .bdp-post-image').find('img').length == 0 ) {
        jQuery('.bdp_blog_template.media-grid .content-container').css('margin', '0');
    }
    jQuery('.bdp_archive_product_template.bdp_archive .bdp_blog_template .bdp-post-image figure').each(function() {
        if(jQuery(this).find('.bdp_woocommerce_sale_wrap.left-top > span').length > 0 ) {
            jQuery(this).find('.bdp-pinterest-share-image').css({'top':'50px','left':'5px'});
        }
    });

    if ( jQuery(window).width() < 768) {
        jQuery('.bdp_single .related_post_div.three_post .relatedthumb').css('width','50%');
    }
    // jQuery('.bdp_single.schedule > .comments-area').addClass('related_post_wrap');
    jQuery('.bdp_single.steps > .comments-area').addClass('related_post_wrap');

    if( jQuery('.banner_cover .bdp_post_content .bdp-post-categories a').length > 10 ) {
        jQuery('.banner_cover .bdp_post_content .bdp-post-categories').css("max-width","70%");
        jQuery('.banner_cover .bdp_post_content .date-meta.bdp-no-links').css("max-width","30%");
    }

    jQuery('.quci.bdp_blog_single_post_wrapp,.bdp_archive.quci').each(function() {
        if(jQuery(this).find('.post_content-inner p').length == 0 ) {
            jQuery(this).find('.blog_header').css('margin-bottom', '40px');
        }
    });
});

jQuery(function() {
    jQuery('.bdp_filter_layout .wl_pagination_box li').on('click', 'a', function (e) {
        e.preventDefault();
        var page = parseInt(jQuery(this).attr("href").replace(/\D/g, ""));
        jQuery('#paged').val(page);
        isotop_filter_pagination();
    });
});

function isotop_filter_pagination() {
    var data = jQuery('.bdp_wrapper').find('form#bdp-paged-hidden').serialize();
    jQuery.ajax({
        url: ajaxurl,
        data: 'action=ajax_filter_posts&' + data + '&nonce=' + ajax_object.ajax_nonce,
        type: "POST",
        cache: false,
        success: function (t) {
            var filterID = jQuery('#filter_category, #filter_tag').val();
            var filterCount = jQuery('#cat_tag_count').val();
            var filter_terms = jQuery('#terms').val();
            jQuery('div.bdp_wrapper').html(t);
            jQuery('#cat_tag_count').val(filterCount);
            if( jQuery('#filter_type').val() == 'category' || jQuery('#filter_type').val() == 'product_cat' ) {
                jQuery('#filter_category').val(filterID);
                jQuery('#terms').val(filter_terms);
            } else if( jQuery('#filter_type').val() == 'post_tag' ) {
                jQuery('#filter_tag').val(filterID);
                jQuery('#terms').val(filter_terms);
            }
            jQuery('.bdp_filter_layout .wl_pagination_box li').on('click', 'a', function (e) {
                e.preventDefault();
                var page = parseInt(jQuery(this).attr("href").replace(/\D/g, ""));
                if(isNaN(page)) {
                    var page = parseInt(jQuery(this).text());
                }
                jQuery('#paged').val(page);
                isotop_filter_pagination();
            });
            bdp_get_brit_co_height();
            jQuery('.bdp_wrapper.bdp_post_list.offer_blog, .bdp_wrapper.bdp_post_list.winter, .bdp_wrapper.bdp_post_list.spektrum, .bdp_wrapper.bdp_post_list.sharpen, .bdp_wrapper.bdp_post_list.schedule, .bdp_wrapper.bdp_post_list.region, .bdp_wrapper.bdp_post_list.quci, .bdp_wrapper.bdp_post_list.pretty, .bdp_wrapper.bdp_post_list.navia, .bdp_wrapper.bdp_post_list.masonry_timeline, .bdp_wrapper.bdp_post_list.glamour, .bdp_wrapper.bdp_post_list.my_diary, .bdp_wrapper.bdp_post_list.lightbreeze, .bdp_wrapper.bdp_post_list.foodbox, .bdp_wrapper.bdp_post_list.minimal, .bdp_wrapper.bdp_post_list.invert-grid, .bdp_wrapper.bdp_post_list.famous, .bdp_wrapper.bdp_post_list.media-grid, .bdp_wrapper.bdp_post_list.wise_block').css('height', 'auto');
        },
    });
}
