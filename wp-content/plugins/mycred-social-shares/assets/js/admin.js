jQuery(document).ready(function ($) {

    /********************************************/
    /* AJAX SAVE FORM */
    /********************************************/
    $('#theme-options-form').submit(function () {
        $(this).ajaxSubmit({
            onLoading: $('.loader').show(),
            success: function () {
                $('.loader').hide();
                $('#save-result').fadeIn();
                setTimeout(function () {
                    $('#save-result').fadeOut('fast');
                }, 2000);
            },
            timeout: 5000
        });
        return false;
    });

    /********************************************/
    /* SORTABLE FILTER FIELDS */
    /********************************************/

    $('.filter-fields-list').sortable({
//            axis: 'y',
//            curosr: 'move'
    });
	
	
		/********************************************/
		/* tabs active js */
		/********************************************/
		
		jQuery( ".mycred-tab-content .mycred-tab-block" ).hide();
		jQuery( ".mycred-tab-content #mycred-settings-share" ).show();
		jQuery( ".mycred-tab-link a" ).click(function() {
		jQuery( ".mycred-tab-link a" ).removeClass('current');
		active_tab = $(this).attr('data-tab');
		jQuery(this).addClass('current');
		jQuery( ".mycred-tab-content .mycred-tab-block" ).hide();
		jQuery( ".mycred-tab-content "+" #"+active_tab ).show();
		});

});
