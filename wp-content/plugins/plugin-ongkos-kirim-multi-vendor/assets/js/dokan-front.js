(function($) {
	$.validator.setDefaults({ ignore: ':hidden' });

	var validatorError = function(error, element) {
		var form_group = $(element).closest('.dokan-form-group');
		form_group.addClass('has-error').append(error);
	};

	var validatorSuccess = function(label, element) {
		$(element)
			.closest('.dokan-form-group')
			.removeClass('has-error');
	};

	var api = wp.customize;

	var POKMV_Dokan_Settings = {
		init: function() {
			var self = this;

			this.validateForm(self);

			return false;
		},

		submitSettings: function(form_id) {
			var self = $('form#' + form_id),
				form_data =
					self.serialize() + '&action=pokmv_dokan_settings&form_id=' + form_id;

			self.find('.ajax_prev').append('<span class="dokan-loading"> </span>');
			$('.dokan-update-setting-top-button span.dokan-loading').remove();
			$('.dokan-update-setting-top-button').append('<span class="dokan-loading"> </span>');
			$.post(pokmv.ajaxurl, form_data, function(resp) {
				self.find('span.dokan-loading').remove();
				$('.dokan-update-setting-top-button span.dokan-loading').remove();
				$('html,body').animate({ scrollTop: 100 });

				if (resp.success) {
					// Harcoded Customization for template-settings function
					$('.dokan-ajax-response').html(
						$('<div/>', {
							class: 'dokan-alert dokan-alert-success',
							html: '<p>' + resp.data.msg + '</p>'
						})
					);

					$('.dokan-ajax-response').append(resp.data.progress);
				} else {
					$('.dokan-ajax-response').html(
						$('<div/>', {
							class: 'dokan-alert dokan-alert-danger',
							html: '<p>' + resp.data + '</p>'
						})
					);
				}
			});
		},

		validateForm: function(self) {
			$(
				'form#shipping-form'
			).validate({
				//errorLabelContainer: '#errors'
				submitHandler: function(form) {
					self.submitSettings(form.getAttribute('id'));
				},
				errorElement: 'span',
				errorClass: 'error',
				errorPlacement: validatorError,
				success: validatorSuccess,
				ignore:
					'.select2-search__field, :hidden, .mapboxgl-ctrl-geocoder--input'
			});
		},
	};

	$(function() {
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
		POKMV_Dokan_Settings.init();
	});
})(jQuery);