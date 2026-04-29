(function($) {

	$('.select2-ajax').each(function() {
		var action 	= $(this).data('action');
		var phrase	= $(this).val();
		var nonce 	= $(this).data('nonce');
		$(this).select2({
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				data: function( params ) {
					return {
						pok_action: nonce,
						action: action,
						q: params.term
					}
				},
				processResults: function (data, params) {
					return {
						results: data
					};
				},
				cache: true
			},
			minimumInputLength: 3,
			placeholder: $(this).attr('placeholder')
		});
	});

	$('.init-select2').each(function() {
		$(this).select2();
	});

	// moving Product Vendors order detail element
	$('.pokmv-pv-insertion p').appendTo('.order_data_column:nth-child(3) .address');
	$('.pokmv-pv-insertion').remove();

	$(document).on('ready', function() {

		// select courier
		function pokmv_get_selected_courier() {
			var couriers = [];
			$('.options-specific-vendor-service > div').hide();
			$('[name="pokmv_vendor[courier][]"]:checked').each(function() {
				$('.options-specific-vendor-service .options-specific-vendor-service-'+$(this).val()).show();
			});
		}
		pokmv_get_selected_courier();

		$('[name="pokmv_vendor[courier][]"]').on('click', function() {
			pokmv_get_selected_courier();
		});

		// toggle filter specific courier
		$('input[name="pokmv_vendor[specific_service]"]').on('click', function() {
			var value = $(this).val();
			if ( 'yes' === value ) {
				$( '.options-specific-vendor-service' ).addClass( 'show' );
			} else {
				$( '.options-specific-vendor-service' ).removeClass( 'show' );
			}
		});
		var specific_service = $('input[name="pokmv_vendor[specific_service]"]:checked').val();
		if ( 'yes' === specific_service ) {
			$( '.options-specific-vendor-service' ).addClass( 'show' );
		} else {
			$( '.options-specific-vendor-service' ).removeClass( 'show' );
		}

		// accordion filter specific courier
		$('.options-specific-vendor-service').on( 'click', '.service-options h5, .service-options p', function() {
			var parent = $(this).parent();
			parent.toggleClass('expand');
			$('.options-specific-vendor-service .service-options').not(parent).removeClass('expand');
		} );
		$('.options-specific-vendor-service input[type=checkbox]').on( 'change', function() {
			$('.options-specific-vendor-service .service-options').each( function() {
				var checked = [];
				var courier = $(this);
				courier.find('input[type=checkbox]').each(function() {
					if ( $(this).is(':checked') ) {
						checked.push($(this).data('short'));
					}
				});
				if ( checked.length ) {
					courier.find('p').html(checked.join(', '));
				} else {
					courier.find('p').html(pok_translations.no_service_selected);
				}
			});
		});
		
	});

})(jQuery);