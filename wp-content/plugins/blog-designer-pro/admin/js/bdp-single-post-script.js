"use strict";
var bdpspt={
    init:function(){
        $=jQuery;
        this.set_icon_open();
        this.set_icon_close();
    },
    set_icon_open:function(){
        var ww ='.bdp_single_icon_wrap_open ';
        $(ww+" div.dialogbox_open").dialog({autoOpen:false,maxWidth:600,maxHeight:500,width:600,height:500,dialogClass:'bdp_si_wrap'});
        $("#icon_search_open").on('keyup',function(){
            var mc=new RegExp($(this).val(),'gi');$('.as-element-icon').show().not(function(){return mc.test($(this).find('span').text())}).hide();
        });
        $(ww+" .open").on('click',function(e){
            e.preventDefault();
            var hide=$(this).prev().attr('id');
            $('.hidden_input_val').val(hide);
            var cid='.dialogbox_open';
            bdpspt.getIconSet(cid);
            $(cid).dialog("open");$('body').prepend("<div id='pageflip'> </div>");
        });
        $(document).on('click','.bdp_single_icon_div_open .as-element-icon',function(e){
            e.preventDefault();
            var iconclass=$(this).find('i').attr('class');
            var hide_div=$('.hidden_input_val').val();
            window.top.$(ww+' #' + hide_div).val(iconclass);
            $('.ui-dialog-titlebar-close').trigger('click');
            $('#pageflip').remove();
        });
        $(".ui-dialog-titlebar-close").on('click', function(e){e.preventDefault();$('#pageflip').remove()});
    },
    set_icon_close:function(){
        var ww ='.bdp_single_icon_wrap_close ';
        $(ww+" div.dialogbox_close").dialog({autoOpen:false,maxWidth:600,maxHeight:500,width:600,height:500,dialogClass:'bdp_si_wrap'});
        $("#icon_search_close").on('keyup',function(){
            var mc=new RegExp($(this).val(),'gi');$('.as-element-icon').show().not(function(){return mc.test($(this).find('span').text())}).hide();
        });
        $(ww+" .open").on('click',function(e){
            e.preventDefault();
            var hide=$(this).prev().attr('id');
            $('.hidden_input_val').val(hide);
            var cid='.dialogbox_close';
            bdpspt.getIconSet(cid);
            $(cid).dialog("open");$('body').prepend("<div id='pageflip'> </div>");
        });
        $(document).on('click','.bdp_single_icon_div_close .as-element-icon',function(e){
            e.preventDefault();
            var iconclass=$(this).find('i').attr('class');
            var hide_div=$('.hidden_input_val').val();
            window.top.$(ww+' #' + hide_div).val(iconclass);
            $('.ui-dialog-titlebar-close').trigger('click');
            $('#pageflip').remove();
        });
        $(".ui-dialog-titlebar-close").on('click', function(e){e.preventDefault();$('#pageflip').remove()});
    },
    getIconSet:function(e){
        $=jQuery
        var fb=["fa-500px","fa-accessible-icon","fa-accusoft","fa-adn","fa-adversal","fa-affiliatetheme","fa-algolia","fa-amazon","fa-amazon-pay","fa-amilia","fa-android","fa-angellist","fa-angrycreative","fa-angular","fa-app-store","fa-app-store-ios","fa-apper","fa-apple","fa-apple-pay","fa-asymmetrik","fa-audible","fa-autoprefixer","fa-avianex","fa-aviato","fa-aws","fa-bandcamp","fa-behance","fa-behance-square","fa-bimobject","fa-bitbucket","fa-bitcoin","fa-bity","fa-black-tie","fa-blackberry","fa-blogger","fa-blogger-b","fa-bluetooth","fa-bluetooth-b","fa-btc","fa-buromobelexperte","fa-buysellads","fa-cc-amazon-pay","fa-cc-amex","fa-cc-apple-pay","fa-cc-diners-club","fa-cc-discover","fa-cc-jcb","fa-cc-mastercard","fa-cc-paypal","fa-cc-stripe","fa-cc-visa","fa-centercode","fa-chrome","fa-cloudscale","fa-cloudsmith","fa-cloudversify","fa-codepen","fa-codiepie","fa-connectdevelop","fa-contao","fa-cpanel","fa-creative-commons","fa-css3","fa-css3-alt","fa-cuttlefish","fa-d-and-d","fa-dashcube","fa-delicious","fa-deploydog","fa-deskpro","fa-deviantart","fa-digg","fa-digital-ocean","fa-discord","fa-discourse","fa-dochub","fa-docker","fa-draft2digital","fa-dribbble","fa-dribbble-square","fa-dropbox","fa-drupal","fa-dyalog","fa-earlybirds","fa-edge","fa-elementor","fa-ember","fa-empire","fa-envira","fa-erlang","fa-ethereum","fa-etsy","fa-expeditedssl","fa-facebook","fa-facebook-f","fa-facebook-messenger","fa-facebook-square","fa-firefox","fa-first-order","fa-firstdraft","fa-flickr","fa-flipboard","fa-fly","fa-font-awesome","fa-font-awesome-alt","fa-font-awesome-flag","fa-fonticons","fa-fonticons-fi","fa-fort-awesome","fa-fort-awesome-alt","fa-forumbee","fa-foursquare","fa-free-code-camp","fa-freebsd","fa-get-pocket","fa-gg","fa-gg-circle","fa-git","fa-git-square","fa-github","fa-github-alt","fa-github-square","fa-gitkraken","fa-gitlab","fa-gitter","fa-glide","fa-glide-g","fa-gofore","fa-goodreads","fa-goodreads-g","fa-google","fa-google-drive","fa-google-play","fa-google-plus","fa-google-plus-g","fa-google-plus-square","fa-google-wallet","fa-gratipay","fa-grav","fa-gripfire","fa-grunt","fa-gulp","fa-hacker-news","fa-hacker-news-square","fa-hips","fa-hire-a-helper","fa-hooli","fa-hotjar","fa-houzz","fa-html5","fa-hubspot","fa-imdb","fa-instagram","fa-internet-explorer","fa-ioxhost","fa-itunes","fa-itunes-note","fa-jenkins","fa-joget","fa-joomla","fa-js","fa-js-square","fa-jsfiddle","fa-keycdn","fa-kickstarter","fa-kickstarter-k","fa-korvue","fa-laravel","fa-lastfm","fa-lastfm-square","fa-leanpub","fa-less","fa-line","fa-linkedin","fa-linkedin-in","fa-linode","fa-linux","fa-lyft","fa-magento","fa-maxcdn","fa-medapps","fa-medium","fa-medium-m","fa-medrt","fa-meetup","fa-microsoft","fa-mix","fa-mixcloud","fa-mizuni","fa-modx","fa-monero","fa-napster","fa-nintendo-switch","fa-node","fa-node-js","fa-npm","fa-ns8","fa-nutritionix","fa-odnoklassniki","fa-odnoklassniki-square","fa-opencart","fa-openid","fa-opera","fa-optin-monster","fa-osi","fa-page4","fa-pagelines","fa-palfed","fa-patreon","fa-paypal","fa-periscope","fa-phabricator","fa-phoenix-framework","fa-php","fa-pied-piper","fa-pied-piper-alt","fa-pied-piper-pp","fa-pinterest","fa-pinterest-p","fa-pinterest-square","fa-playstation","fa-product-hunt","fa-pushed","fa-python","fa-qq","fa-quinscape","fa-quora","fa-ravelry","fa-react","fa-rebel","fa-red-river","fa-reddit","fa-reddit-alien","fa-reddit-square","fa-rendact","fa-renren","fa-replyd","fa-resolving","fa-rocketchat","fa-rockrms","fa-safari","fa-sass","fa-schlix","fa-scribd","fa-searchengin","fa-sellcast","fa-sellsy","fa-servicestack","fa-shirtsinbulk","fa-simplybuilt","fa-sistrix","fa-skyatlas","fa-skype","fa-slack","fa-slack-hash","fa-slideshare","fa-snapchat","fa-snapchat-ghost","fa-snapchat-square","fa-soundcloud","fa-speakap","fa-spotify","fa-stack-exchange","fa-stack-overflow","fa-staylinked","fa-steam","fa-steam-square","fa-steam-symbol","fa-sticker-mule","fa-strava","fa-stripe","fa-stripe-s","fa-studiovinari","fa-stumbleupon","fa-stumbleupon-circle","fa-superpowers","fa-supple","fa-telegram","fa-telegram-plane","fa-tencent-weibo","fa-themeisle","fa-trello","fa-tripadvisor","fa-tumblr","fa-tumblr-square","fa-twitch","fa-x-twitter","fa-twitter-square","fa-typo3","fa-uber","fa-uikit","fa-uniregistry","fa-untappd","fa-usb","fa-ussunnah","fa-vaadin","fa-viacoin","fa-viadeo","fa-viadeo-square","fa-viber","fa-vimeo","fa-vimeo-square","fa-vimeo-v","fa-vine","fa-vk","fa-vnv","fa-vuejs","fa-weibo","fa-weixin","fa-whatsapp","fa-whatsapp-square","fa-whmcs","fa-wikipedia-w","fa-windows","fa-wordpress","fa-wordpress-simple","fa-wpbeginner","fa-wpexplorer","fa-wpforms","fa-xbox","fa-xing","fa-xing-square","fa-y-combinator","fa-yahoo","fa-yandex","fa-yandex-international","fa-yelp","fa-yoast","fa-youtube","fa-youtube-square"];
        var fr=["fa-address-book","fa-address-card","fa-arrow-alt-circle-down","fa-arrow-alt-circle-left","fa-arrow-alt-circle-right","fa-arrow-alt-circle-up","fa-bell","fa-bell-slash","fa-bookmark","fa-building","fa-calendar","fa-calendar-alt","fa-calendar-check","fa-calendar-minus","fa-calendar-plus","fa-calendar-times","fa-caret-square-down","fa-caret-square-left","fa-caret-square-right","fa-caret-square-up","fa-chart-bar","fa-check-circle","fa-check-square","fa-circle","fa-clipboard","fa-clock","fa-clone","fa-closed-captioning","fa-comment","fa-comment-alt","fa-comments","fa-compass","fa-copy","fa-copyright","fa-credit-card","fa-dot-circle","fa-edit","fa-envelope","fa-envelope-open","fa-eye-slash","fa-file","fa-file-alt","fa-file-archive","fa-file-audio","fa-file-code","fa-file-excel","fa-file-image","fa-file-pdf","fa-file-powerpoint","fa-file-video","fa-file-word","fa-flag","fa-folder","fa-folder-open","fa-frown","fa-futbol","fa-gem","fa-hand-lizard","fa-hand-paper","fa-hand-peace","fa-hand-point-down","fa-hand-point-left","fa-hand-point-right","fa-hand-point-up","fa-hand-pointer","fa-hand-rock","fa-hand-scissors","fa-hand-spock","fa-handshake","fa-hdd","fa-heart","fa-hospital","fa-hourglass","fa-id-badge","fa-id-card","fa-image","fa-images","fa-keyboard","fa-lemon","fa-life-ring","fa-lightbulb","fa-list-alt","fa-map","fa-meh","fa-minus-square","fa-money-bill-alt","fa-moon","fa-newspaper","fa-object-group","fa-object-ungroup","fa-paper-plane","fa-pause-circle","fa-play-circle","fa-plus-square","fa-question-circle","fa-registered","fa-save","fa-share-square","fa-smile","fa-snowflake","fa-square","fa-star","fa-star-half","fa-sticky-note","fa-stop-circle","fa-sun","fa-thumbs-down","fa-thumbs-up","fa-times-circle","fa-trash-alt","fa-user","fa-user-circle","fa-window-close","fa-window-maximize","fa-window-minimize","fa-window-restore"];
        var fs=["fa-address-book","fa-address-card","fa-adjust","fa-align-center","fa-align-justify","fa-align-left","fa-align-right","fa-ambulance","fa-american-sign-language-interpreting","fa-anchor","fa-angle-double-down","fa-angle-double-left","fa-angle-double-right","fa-angle-double-up","fa-angle-down","fa-angle-left","fa-angle-right","fa-angle-up","fa-archive","fa-arrow-alt-circle-down","fa-arrow-alt-circle-left","fa-arrow-alt-circle-right","fa-arrow-alt-circle-up","fa-arrow-circle-down","fa-arrow-circle-left","fa-arrow-circle-right","fa-arrow-circle-up","fa-arrow-down","fa-arrow-left","fa-arrow-right","fa-arrow-up","fa-arrows-alt","fa-arrows-alt-h","fa-arrows-alt-v","fa-assistive-listening-systems","fa-asterisk","fa-at","fa-audio-description","fa-backward","fa-balance-scale","fa-ban","fa-band-aid","fa-barcode","fa-bars","fa-baseball-ball","fa-basketball-ball","fa-bath","fa-battery-empty","fa-battery-full","fa-battery-half","fa-battery-quarter","fa-battery-three-quarters","fa-bed","fa-beer","fa-bell","fa-bell-slash","fa-bicycle","fa-binoculars","fa-birthday-cake","fa-blind","fa-bold","fa-bolt","fa-bomb","fa-book","fa-bookmark","fa-bowling-ball","fa-box","fa-boxes","fa-braille","fa-briefcase","fa-bug","fa-building","fa-bullhorn","fa-bullseye","fa-bus","fa-calculator","fa-calendar","fa-calendar-alt","fa-calendar-check","fa-calendar-minus","fa-calendar-plus","fa-calendar-times","fa-camera","fa-camera-retro","fa-car","fa-caret-down","fa-caret-left","fa-caret-right","fa-caret-square-down","fa-caret-square-left","fa-caret-square-right","fa-caret-square-up","fa-caret-up","fa-cart-arrow-down","fa-cart-plus","fa-certificate","fa-chart-area","fa-chart-bar","fa-chart-line","fa-chart-pie","fa-check","fa-check-circle","fa-check-square","fa-chess","fa-chess-bishop","fa-chess-board","fa-chess-king","fa-chess-knight","fa-chess-pawn","fa-chess-queen","fa-chess-rook","fa-chevron-circle-down","fa-chevron-circle-left","fa-chevron-circle-right","fa-chevron-circle-up","fa-chevron-down","fa-chevron-left","fa-chevron-right","fa-chevron-up","fa-child","fa-circle","fa-circle-notch","fa-clipboard","fa-clipboard-check","fa-clipboard-list","fa-clock","fa-clone","fa-closed-captioning","fa-cloud","fa-cloud-download-alt","fa-cloud-upload-alt","fa-code","fa-code-branch","fa-coffee","fa-cog","fa-cogs","fa-columns","fa-comment","fa-comment-alt","fa-comments","fa-compass","fa-compress","fa-copy","fa-copyright","fa-credit-card","fa-crop","fa-crosshairs","fa-cube","fa-cubes","fa-cut","fa-database","fa-deaf","fa-desktop","fa-dna","fa-dollar-sign","fa-dolly","fa-dolly-flatbed","fa-dot-circle","fa-download","fa-edit","fa-eject","fa-ellipsis-h","fa-ellipsis-v","fa-envelope","fa-envelope-open","fa-envelope-square","fa-eraser","fa-euro-sign","fa-exchange-alt","fa-exclamation","fa-exclamation-circle","fa-exclamation-triangle","fa-expand","fa-expand-arrows-alt","fa-external-link-alt","fa-external-link-square-alt","fa-eye","fa-eye-dropper","fa-eye-slash","fa-fast-backward","fa-fast-forward","fa-fax","fa-female","fa-fighter-jet","fa-file","fa-file-alt","fa-file-archive","fa-file-audio","fa-file-code","fa-file-excel","fa-file-image","fa-file-pdf","fa-file-powerpoint","fa-file-video","fa-file-word","fa-film","fa-filter","fa-fire","fa-fire-extinguisher","fa-first-aid","fa-flag","fa-flag-checkered","fa-flask","fa-folder","fa-folder-open","fa-font","fa-football-ball","fa-forward","fa-frown","fa-futbol","fa-gamepad","fa-gavel","fa-gem","fa-genderless","fa-gift","fa-glass-martini","fa-globe","fa-golf-ball","fa-graduation-cap","fa-h-square","fa-hand-lizard","fa-hand-paper","fa-hand-peace","fa-hand-point-down","fa-hand-point-left","fa-hand-point-right","fa-hand-point-up","fa-hand-pointer","fa-hand-rock","fa-hand-scissors","fa-hand-spock","fa-handshake","fa-hashtag","fa-hdd","fa-heading","fa-headphones","fa-heart","fa-heartbeat","fa-history","fa-hockey-puck","fa-home","fa-hospital","fa-hospital-symbol","fa-hourglass","fa-hourglass-end","fa-hourglass-half","fa-hourglass-start","fa-i-cursor","fa-id-badge","fa-id-card","fa-image","fa-images","fa-inbox","fa-indent","fa-industry","fa-info","fa-info-circle","fa-italic","fa-key","fa-keyboard","fa-language","fa-laptop","fa-leaf","fa-lemon","fa-level-down-alt","fa-level-up-alt","fa-life-ring","fa-lightbulb","fa-link","fa-lira-sign","fa-list","fa-list-alt","fa-list-ol","fa-list-ul","fa-location-arrow","fa-lock","fa-lock-open","fa-long-arrow-alt-down","fa-long-arrow-alt-left","fa-long-arrow-alt-right","fa-long-arrow-alt-up","fa-low-vision","fa-magic","fa-magnet","fa-male","fa-map","fa-map-marker","fa-map-marker-alt","fa-map-pin","fa-map-signs","fa-mars","fa-mars-double","fa-mars-stroke","fa-mars-stroke-h","fa-mars-stroke-v","fa-medkit","fa-meh","fa-mercury","fa-microchip","fa-microphone","fa-microphone-slash","fa-minus","fa-minus-circle","fa-minus-square","fa-mobile","fa-mobile-alt","fa-money-bill-alt","fa-moon","fa-motorcycle","fa-mouse-pointer","fa-music","fa-neuter","fa-newspaper","fa-object-group","fa-object-ungroup","fa-outdent","fa-paint-brush","fa-pallet","fa-paper-plane","fa-paperclip","fa-paragraph","fa-paste","fa-pause","fa-pause-circle","fa-paw","fa-pen-square","fa-pencil-alt","fa-percent","fa-phone","fa-phone-square","fa-phone-volume","fa-pills","fa-plane","fa-play","fa-play-circle","fa-plug","fa-plus","fa-plus-circle","fa-plus-square","fa-podcast","fa-pound-sign","fa-power-off","fa-print","fa-puzzle-piece","fa-qrcode","fa-question","fa-question-circle","fa-quidditch","fa-quote-left","fa-quote-right","fa-random","fa-recycle","fa-redo","fa-redo-alt","fa-registered","fa-reply","fa-reply-all","fa-retweet","fa-road","fa-rocket","fa-rss","fa-rss-square","fa-ruble-sign","fa-rupee-sign","fa-save","fa-search","fa-search-minus","fa-search-plus","fa-server","fa-share","fa-share-alt","fa-share-alt-square","fa-share-square","fa-shekel-sign","fa-shield-alt","fa-ship","fa-shipping-fast","fa-shopping-bag","fa-shopping-basket","fa-shopping-cart","fa-shower","fa-sign-in-alt","fa-sign-language","fa-sign-out-alt","fa-signal","fa-sitemap","fa-sliders-h","fa-smile","fa-snowflake","fa-sort","fa-sort-alpha-down","fa-sort-alpha-up","fa-sort-amount-down","fa-sort-amount-up","fa-sort-down","fa-sort-numeric-down","fa-sort-numeric-up","fa-sort-up","fa-space-shuttle","fa-spinner","fa-square","fa-square-full","fa-star","fa-star-half","fa-step-backward","fa-step-forward","fa-stethoscope","fa-sticky-note","fa-stop","fa-stop-circle","fa-stopwatch","fa-street-view","fa-strikethrough","fa-subscript","fa-subway","fa-suitcase","fa-sun","fa-superscript","fa-sync","fa-sync-alt","fa-syringe","fa-table","fa-table-tennis","fa-tablet","fa-tablet-alt","fa-tachometer-alt","fa-tag","fa-tags","fa-tasks","fa-taxi","fa-terminal","fa-text-height","fa-text-width","fa-th","fa-th-large","fa-th-list","fa-thermometer","fa-thermometer-empty","fa-thermometer-full","fa-thermometer-half","fa-thermometer-quarter","fa-thermometer-three-quarters","fa-thumbs-down","fa-thumbs-up","fa-thumbtack","fa-ticket-alt","fa-times","fa-times-circle","fa-tint","fa-toggle-off","fa-toggle-on","fa-trademark","fa-train","fa-transgender","fa-transgender-alt","fa-trash","fa-trash-alt","fa-tree","fa-trophy","fa-truck","fa-tty","fa-tv","fa-umbrella","fa-underline","fa-undo","fa-undo-alt","fa-universal-access","fa-university","fa-unlink","fa-unlock","fa-unlock-alt","fa-upload","fa-user","fa-user-circle","fa-user-md","fa-user-plus","fa-user-secret","fa-user-times","fa-users","fa-utensil-spoon","fa-utensils","fa-venus","fa-venus-double","fa-venus-mars","fa-video","fa-volleyball-ball","fa-volume-down","fa-volume-off","fa-volume-up","fa-warehouse","fa-weight","fa-wheelchair","fa-wifi","fa-window-close","fa-window-maximize","fa-window-minimize","fa-window-restore","fa-won-sign","fa-wrench","fa-yen-sign"];
        var gi=false,is="",h1='<div class="as-element-icon" title="',h2='"></i><span style="display:none">',h3='</span></div>',h4='"><i class="';
        $.each(fb,function(i,v){gi=true;is=is+h1+v+h4+'fab '+v+h2+v+h3});
        $.each(fr,function(i,v){gi=true;is=is+h1+v+h4+'far '+v+h2+v+h3});
        $.each(fs,function(i,v){gi=true;is=is+h1+v+h4+'fas '+v+h2+v+h3});
        if(gi){$(e).find('.bdp_single_icon_div_open').html(is)}
        if(gi){$(e).find('.bdp_single_icon_div_close').html(is)}
    }
};
jQuery(document).ready(function(){
    (function($){
        bdpspt.init();
        $('#bdp_single_template_titlebackcolor').wpColorPicker();
        $('.singleposts_post_open_icon').hide();
        $('.singleposts_post_close_icon').hide();
        $('.singleposts_post_bg').hide();
        $('#single_selecttemplate_field :selected').each(function(i, sel){ 
            var $val = $(sel).val();
            if($val  == 'accordion' ) {
                $('.singleposts_post_open_icon').show();
                $('.singleposts_post_close_icon').show();
                $('.singleposts_post_bg').show();
            }
        });
        $('#single_selecttemplate_field').on('change', function() {
            $('.singleposts_post_open_icon').hide();
            $('.singleposts_post_close_icon').hide();
            $('.singleposts_post_bg').hide();
            $('#single_selecttemplate_field :selected').each(function(i, sel){ 
                var $val = $(sel).val();
                if($val  == 'accordion' ) {
                    $('.singleposts_post_open_icon').show();
                    $('.singleposts_post_close_icon').show();
                    $('.singleposts_post_bg').show();
                }
            });
        })
    }(jQuery))
});

