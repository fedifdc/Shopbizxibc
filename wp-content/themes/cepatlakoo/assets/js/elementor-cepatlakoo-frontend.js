(function($) {

    'use strict';

    $(document).ready(function($) {
        var wasClick = [];


        var prevBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M12.5918 2.43164L5.02344 10L12.5918 17.5684L10.2383 19.9316L0.306641 10L10.2383 0.0683594L12.5918 2.43164Z" fill="#C0C0C0"/></svg>';
        var nextBtnIcon = '<svg width="13" height="20" viewBox="0 0 13 20" fill="none"><path d="M2.76172 0.0683594L12.6934 10L2.76172 19.9316L0.408203 17.5684L7.97656 10L0.408203 2.43164L2.76172 0.0683594Z" fill="#C0C0C0"/></svg>';


        // $(".quick-contact-info a").on('click', function() {
        //     var FBAction = $(this).attr('fb-pixel');
        //     if ( typeof fbq !== 'undefined' && FBAction ) {
        //         if ($.inArray(FBAction, wasClick) == -1) {
        //             if( FBAction != 'noevent' ){
        //                 fbq('track', FBAction, {});
        //                 wasClick.push(FBAction);
        //             }
        //         }
        //     }
        // });

        $('.gallery-thumbnail').each(function(index, element) {
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
                    thumbsPrerendered: false,
                    autoHeight: false,
                    navText: [prevBtnIcon, nextBtnIcon]
                });
                $('.flex-control-thumbs').hide();
                
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
        });

    });
})(jQuery);
