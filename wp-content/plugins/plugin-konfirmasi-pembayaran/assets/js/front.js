jQuery(function($){

	$(document).ready(function(){
		// Date Picker
		$( ".tpc-datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
	});

	$('#tpc-confirmation-form').on( 'submit', function(e) {
		e.preventDefault();
		var form 	= $(this);
		var button 	= $('#submit-confirmation');
		var message = $('.message-wrap');
		var data 	= new FormData();

		message.html( '<div class="loading">Submitting...</div>' );
		button.prop( 'disabled', true );

		data.append( 'action', 'tpc_submit_confirmation' );
		$.each( form.serializeArray(), function(i, field) {
			data.append( field.name, field.value );
		});
		if ( $('.tpc-file').length ) {
			if ( $('.tpc-file')[0].files[0] ) {
				data.append( 'file', $('.tpc-file')[0].files[0] );
			}
		}

		$.ajax({
			url: tpc.ajaxurl,
			type: 'POST',
			data: data,
			dataType: 'json',
			processData: false,
    		contentType: false,
			success: function( res ) {
				message.html( res.message );
				button.prop( 'disabled', false );
				if ( !res.error && tpc.redirect ) {
					tpc.redirect = tpc.redirect.replace( '[order_id]', form.find('[name=order_id]').val() );
					setTimeout(function() {
				// 		window.location.href = tpc.redirect;
				window.location.reload();
					}, 2000);
				}
			},
			error: function( err ) {
				message.html( tpc.submit_error );
				console.log( err );
				button.prop( 'disabled', false );
			}
		});

	});
});