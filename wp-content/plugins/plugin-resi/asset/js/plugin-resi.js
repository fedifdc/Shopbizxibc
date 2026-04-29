jQuery(document).ready(function($) {
	$('#post').submit(function() {
		if( jQuery('select[name=wc_order_action]').val() == 'plugin_resi_send_tracking_number' || jQuery('#order_status').val() == 'wc-shipped' ) 
		{
			if( $('input[name=plugin_resi_nomor_resi]').val() == '') {
				alert(plugin_resi_localization.empty_tracking_number);

				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}		
	});
});