jQuery(document).ready(function($) {
    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();

    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');
        
        if (!$item.hasClass('disabled')) {
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
        }
    });
    
    $('ul.setup-panel li.active a').trigger('click');

    $(document).on('click', ".cl-migration", function(e) {
      $(this).hide();
      $('.migration-loader').show();
        $.ajax({
        url : wpadmin_ajax.ajax_url,
        type : 'post',
        data : {
          action : 'cl_migration',
        },
        success : function( response ) {
            if( response == 1 ){
              $('.cl-notif').text( wpadmin_ajax.texdomain[0] );
              $('.cl-migration').remove();
              window.location.href = wpadmin_ajax.admin_url + 'admin.php?page=cepatlakoo&tab=0';
            }else{
              $('.cl-notif').text( wpadmin_ajax.texdomain[1] );
              var str = window.location.href;
              if (str.toLowerCase().indexOf("tgmpa") < 0){
                window.location.href = wpadmin_ajax.admin_url + 'themes.php?page=tgmpa-install-plugins';
              }
            }
            $('.migration-loader').hide();
            $(this).show();
          }
        });
    });

    $(document).on('click', ".cl-active-redux", function(e) {
      $(this).hide();
      $('.migration-loader').show();
        $.ajax({
        url : wpadmin_ajax.ajax_url,
        type : 'post',
        data : {
          action : 'cl_active_redux',
        },
        success : function( response ) {
            if( response == 1 ){
              $('.cl-notif').text( wpadmin_ajax.texdomain[2] );
              $('.cl-active-redux').remove();
              window.location.href = wpadmin_ajax.admin_url + 'options-general.php?page=cepatlakoo_migration_menu';
              $('.migration-loader').hide();
            }else{
              $('.cl-notif').text( wpadmin_ajax.texdomain[1] );
              var str = window.location.href;
              if (str.toLowerCase().indexOf("tgmpa") < 3){
                window.location.href = wpadmin_ajax.admin_url + 'themes.php?page=tgmpa-install-plugins';
                $('.migration-loader').hide();
              }
            }
          }
        });
    });
    
    $( ".cl_marketplace_parent_button" ).sortable({
      axis: "y"
    });

    jQuery(document).on('click', '.cl_marketplace_plus', function(e){
      e.preventDefault();
      jQuery( ".cl_marketplace_item:last" ).clone().appendTo( ".cl_marketplace_parent_button" ).find("input[type='text']").val("");
    });

    jQuery(document).on('click', '.cl_marketplace_min', function(e){
      e.preventDefault();
      if( jQuery('.cl_marketplace_item').length > 1 )
        jQuery( this ).closest( ".cl_marketplace_item" ).remove();
    });

  jQuery(document).on('click', '.cl_marketplace_item_check', function () {
    jQuery( this ).siblings('.cl_marketplace_tab').val( jQuery(this).is(':checked') ? 'true' : 'false' );
  });

  cl_trigger_color_change();

  jQuery(document).on('click', '.redux-group-tab-link-li', function () {
    cl_trigger_color_change();
  });

  function cl_trigger_color_change(){
    setTimeout(function(){
      if( $('body').find('.wp-color-picker').length > 0 ){
        $('body').find('.wp-color-picker').wpColorPicker({ 
            change: function(){
              $('#cepatlakoo_color_schemes-select').val("custom").trigger("change");
            } 
        });
      }
    },1000);
  }
  
  jQuery(document).on('change', '.acf-field-5ebbfd1bb0394 input:not(#acf-field_5ebbfd1bb0394-all)', function () {
    if( $('.acf-field-5ebbfd1bb0394 input:checkbox:checked:not(#acf-field_5ebbfd1bb0394-all)').length === ($('.acf-field-5ebbfd1bb0394 input:checkbox:not(#acf-field_5ebbfd1bb0394-all)').length) ){
      jQuery('#acf-field_5ebbfd1bb0394-all').prop( "checked", true );
    }
    else{
      jQuery('#acf-field_5ebbfd1bb0394-all').prop( "checked", false );
    }
  });

  jQuery(document).on('change', '#acf-field_5ebbfd1bb0394-all', function () {
    if ( jQuery('#acf-field_5ebbfd1bb0394-all').is(':checked') ) {
      jQuery('.acf-field-5ebbfd1bb0394 input').prop( "checked", true );
    }
    else{
      jQuery('.acf-field-5ebbfd1bb0394 input').prop( "checked", false );
    }
  });
});
