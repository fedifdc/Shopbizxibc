jQuery(function ($) {
    $('.social-share').click(function () {
		
        var mycredlink = $(this);
        var linkdestination = mycredlink.attr('href');
        var target = mycredlink.attr('target');
        if (typeof target === 'undefined') {
            target = 'self';
        }
        // $.ajax({ 
            // type: "POST", 
            // data: {
                // action: 'mycred-socialshare-link-points',
                // url: linkdestination,
                // token: myCREDsocial.token,
                // socialtype: mycredlink.attr('data-social')
            // },
            // dataType: "JSON",
            // url: myCREDsocial.ajaxurl,
            // success: function (response) {
                // if (target == 'self' || target == '_self')
                    // window.location.href = linkdestination;
            // }
        // });

        if (target == 'self' || target == '_self')
            return false;

    });
 

});


