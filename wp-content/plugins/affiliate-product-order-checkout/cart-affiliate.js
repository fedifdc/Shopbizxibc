jQuery(document).ready(function($) { 
	// Tangkap event klik pada tombol "Update Cart"
        $('button[name="update_cart"]').on('click', function(e) {
            // Tunggu hingga proses update cart selesai
            $(document).ajaxComplete(function() {
                // Seleksi elemen-elemen
                var rewardCartMessage = $('#yith-par-message-reward-cart'); // Elemen yang ingin dipindahkan
                var noticeWrapper = $('#woocommerce-notices-wrapper'); // Elemen parent tempat pengecekan
                var stepsElement = $('.ui.ordered.steps'); // Elemen tujuan

                // Cek apakah #yith-par-message-reward-cart ada di dalam #woocommerce-notices-wrapper
                if (rewardCartMessage.length && noticeWrapper.length && $.contains(noticeWrapper[0], rewardCartMessage[0])) {
                    // Jika ditemukan, pindahkan setelah .ui.ordered.steps
                    rewardCartMessage.insertAfter(stepsElement);
                }
            });
        });
    // Untuk affiliate checkout
$('#affiliate-checkout').click(function(e) {
    e.preventDefault();

    var $this = $(this); // Simpan referensi tombol

    // Cek apakah tombol sudah dinonaktifkan
    if ($this.prop('disabled')) {
        return; // Jika sudah dinonaktifkan, jangan lanjutkan aksi
    }

    // Nonaktifkan tombol setelah klik pertama
    $this.prop('disabled', true);
    console.log('klik');

    $.ajax({
        url: wc_cart_params.ajax_url,
        type: 'POST',
        data: {
            action: 'handle_affiliate_checkout',
            nonce: wc_cart_params.nonce // Tambahkan nonce untuk keamanan
        },
        success: function(response) {
            if(response.success) {
                window.location.href = response.data.redirect_url; 
            } else {
                console.log('Error: ' + response.data);
                // Aktifkan kembali tombol jika terjadi kesalahan
                $this.prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error: ' + error);
            // Aktifkan kembali tombol jika terjadi error
            $this.prop('disabled', false);
        }
    });
});


    // Untuk non-affiliate checkout
    $('#non-affiliate-checkout').click(function(e) {
        e.preventDefault();
		var $this = $(this); // Simpan referensi tombol

		// Cek apakah tombol sudah dinonaktifkan
		if ($this.prop('disabled')) {
			return; // Jika sudah dinonaktifkan, jangan lanjutkan aksi
		}

		// Nonaktifkan tombol setelah klik pertama
		$this.prop('disabled', true);
		
        $.ajax({
            url: wc_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'handle_non_affiliate_checkout',
                nonce: wc_cart_params.nonce // Tambahkan nonce untuk keamanan
            },
            success: function(response) {
                if(response.success) {
                    console.log('berhasil-non-affiliate');
                    window.location.href = $('#non-affiliate-checkout').attr('href');
                } else {
                    console.table('Error: ' + response);
					 $this.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + status);
				console.table(status);
				 $this.prop('disabled', false);
            }
        });
    });
});
