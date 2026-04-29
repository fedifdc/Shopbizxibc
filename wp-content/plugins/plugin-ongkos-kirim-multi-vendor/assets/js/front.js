(function($) {

	$('.select2-ajax').each(function() {
		var action 	= $(this).data('action');
		var phrase	= $(this).val();
		var nonce 	= $(this).data('nonce');
		$(this).select2({
			ajax: {
				url: pokmv.ajaxurl,
				dataType: 'json',
				delay: 250,
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

})(jQuery);