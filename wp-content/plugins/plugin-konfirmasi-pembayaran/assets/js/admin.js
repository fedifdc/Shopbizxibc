// Setting Page.
jQuery(function($) {
	var active_tab;
	var wrapper = "#tpc-setting";

	if (window.location.hash) {
		var current = getHash();
		if (current.indexOf('tab-') === 0) {
			set_active_tab(current.replace('tab-',''));
		} else {
			set_active_tab_first();
		}
	} else {
		set_active_tab_first();
	}

	$(window).on('hashchange', function(){
		var current = getHash();
		if (current.indexOf('tab-') === 0) {
			set_active_tab(current.replace('tab-',''));
		}
	});

	function getHash() {
		var hash = window.location.hash;
		if (!hash) return;
		if (hash.indexOf('#') === 0) {
			return hash.replace('#','');
		}
	}

	function set_active_tab(section) {
		$( wrapper + ' .nav-tab-wrapper .nav-tab').each(function() {
			$(this).removeClass('nav-tab-active');
		});
		$( wrapper + ' .nav-tab-wrapper .nav-tab-'+section).addClass('nav-tab-active');
		$( wrapper + ' .tpc-tab-contents .tpc-tab-content').each(function() {
			$(this).removeClass('active');
		});
		$(wrapper + ' .tpc-tab-contents #tab-'+section).addClass('active');
	}

	function set_active_tab_first() {
		var first_menu = $(wrapper + ' .nav-tab-wrapper .nav-tab:first-child').attr('href');
		if (first_menu) {
			if(window.history.pushState) {
				window.history.pushState(null, null, first_menu);
			} else {
				window.location.hash = first_menu;
			}
			var current = getHash();
			if (current.indexOf('tab-') === 0) {
				set_active_tab(current.replace('tab-',''));
			}
		}
	}

	$('#tpc-generate-page').on( 'click', function() {
		$(this).text( 'Generating...' );
		$(this).prop('disabled', true);
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'tpc_generate_confirmation_page',
				tpc_action: loc.nonce_generate_page
			},
			success: function( res ) {
				$('#tpc-generate-page').parents('.value').html( res );
			},
			error: function( err ) {
				$(this).text('Generate Page');
				$(this).prop('disabled', false);
				console.log(err);
			}
		})
	} );

	$('#tpc-setting-form .buttons .button').on('click', function() {
		var cursorPos = $('#form-editor').prop('selectionStart');
		var v = $('#form-editor').val();
		if ( 0 == cursorPos ) {
			cursorPos = v.length;
		}
		var textBefore = v.substring(0,  cursorPos);
		var textAfter  = v.substring(cursorPos, v.length);
		var text =  $(this).data('field');

		$('#form-editor').val(textBefore + '[' + text + ']' + textAfter);
		$('#form-editor').trigger('change').focus();
		$('#form-editor').selectRange( cursorPos + text.length + 2 );
		$('#field-setting h4.accr-' + text ).click();
	});
	$('#form-editor').on( 'change keyup click', function() {
		var text = $(this).val();
		for ( var i = 0; i < loc.fields.length; i++ ) {
			if ( text.indexOf( '[' + loc.fields[i] + ']' ) > 0 ) {
				$('#tpc-setting-form .buttons .button[data-field="' + loc.fields[i] + '"]').prop( 'disabled', true );
				$('#field-setting h4.accr-' + loc.fields[i] ).css( 'display', 'block' );
			} else {
				$('#tpc-setting-form .buttons .button[data-field="' + loc.fields[i] + '"]').prop( 'disabled', false );
				$('#field-setting .accr-' + loc.fields[i] ).css( 'display', 'none' );
				$('#field-setting .accr-' + loc.fields[i] ).removeClass( 'ui-state-active' );
			}
		}
	} );

	$('#field-setting').accordion({
		collapsible: true,
		heightStyle: "content"
	});

	$('#tpc-setting-form').on( 'submit', function() {
		var html = $('#form-editor').val();
		if ( html.indexOf( '[order_id]' ) < 0 || html.indexOf( '[customer_email]' ) < 0 ) {
			alert( loc.error_required_field );
			return false;
		}
	} );

	$.fn.selectRange = function(start, end) {
		if(end === undefined) {
			end = start;
		}
		return this.each(function() {
			if('selectionStart' in this) {
				this.selectionStart = start;
				this.selectionEnd = end;
			} else if(this.setSelectionRange) {
				this.setSelectionRange(start, end);
			} else if(this.createTextRange) {
				var range = this.createTextRange();
				range.collapse(true);
				range.moveEnd('character', end);
				range.moveStart('character', start);
				range.select();
			}
		});
	};

});

// Order Page.
jQuery(function($) {
	// toggle edit.
	$('#tpc-data').on('click', '.toggle-edit', function() {
		$('#tpc-data').toggleClass('editing');
	});

	$(".tpc-datepicker").datepicker({ dateFormat: 'dd-mm-yy' });

	// upload image.
	$('#tpc-data').on('click', '.tpc-upload-image', function(e){
		e.preventDefault();

		var button = $(this);
		var custom_uploader = wp.media({
			title: 'Attach image',
			library : {
				// uncomment the next line if you want to attach image to the current post
				uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false // for multiple image selection set to true
		}).on('select', function() { // it also has "open" and "close" events 
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#tpc-data').find('[name="tpc_field[file]"]').val( attachment.id );
			$('#tpc-data').find('.tpc-upload-image').val( attachment.filename );
		})
		.open();
	});

	// update data.
	$('#tpc-data').on('click', '.tpc-update', function() {
		$('#tpc-data').parent().block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {};
		$('#tpc-data [name^="tpc_field"]').each(function() {
			var key = $(this).attr('name');
			key = key.replace( 'tpc_field[', '' ).replace( ']', '' );
			data[ key ] = $(this).val();
		});
		data.action = 'tpc_update_data';
		data.tpc_action = loc.nonce_update_data;
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: data,
			success: function( res ) {
				if ( ! res.error ) {
					$('#tpc-data .view').html( res.html );
					$('#tpc-data').removeClass('editing');
				} else {
					alert( res.message );
				}
				$('#tpc-data').parent().unblock();
			},
			error: function( err ) {
				$('#tpc-data').parent().unblock();
				console.log(err);
			}
		})
	});

	// confirm data.
	$('#tpc-data').on('click','.tpc-confirm', function() {
		if ( confirm( loc.confirm ) ) {
			var order_id = $(this).data('id');
			$('#tpc-data').parent().block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'tpc_confirm_payment',
					tpc_action: loc.nonce_respond_payment,
					order_id: order_id
				},
				success: function( res ) {
					if ( ! res.error ) {
						window.location.reload(true);
					} else {
						alert( res.message );
						$('#tpc-data').parent().unblock();
					}
				},
				error: function( err ) {
					$('#tpc-data').parent().unblock();
					console.log(err);
				}
			});
		}
	});

	// reject data.
	$('#tpc-data').on('click','.tpc-reject', function() {
		var reason = prompt( loc.ask_reason );
		if ( null !== reason ) {
			var order_id = $(this).data('id');
			$('#tpc-data').parent().block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'tpc_reject_payment',
					tpc_action: loc.nonce_respond_payment,
					order_id: order_id,
					reason: reason
				},
				success: function( res ) {
					if ( ! res.error ) {
						window.location.reload(true);
					} else {
						alert( res.message );
						$('#tpc-data').parent().unblock();
					}
				},
				error: function( err ) {
					$('#tpc-data').parent().unblock();
					console.log(err);
				}
			});
		}
	});
});