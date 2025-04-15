jQuery(document).ready(function($) {
    
    // Perform AJAX login on form submit
    $('form#mylogin').on('submit', function (e) {
        var redirect        = $('form#mylogin #redirect').val();
        var grecaptchares   = $('form#mylogin textarea[name="g-recaptcha-response"]').val();

        if ($('form#mylogin textarea[name="g-recaptcha-response"]').length && grecaptchares == '') {
            alert('Captcha Harus Diisi');
            return false;
        }

        $('form#mylogin p.status').show().html('<div class="spinner-grow spinner-grow-sm" role="status"> <span class="visually-hidden">Loading...</span></div> Sending user info, please wait...');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: velocityiklan.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#mylogin #username').val(),
                'password': $('form#mylogin #password').val(),
                'security': $('form#mylogin #security').val(),
                'g-recaptcha-response': grecaptchares
            },
            success: function (data) {
                $('form#mylogin p.status').html(data.message);
                if (data.loggedin == true) {
                    document.location.href = redirect;
                } else {
                    reloadCaptcha();
                }
            }
        });

        e.preventDefault();
    });
    
    $("#city-destination").chained("#prov-destination");    
    $('#city-destination').on('change', function () {
        if($("#city-destination").val()) {
            jQuery.ajax({
                type: "POST",
                url: velocityiklan.ajaxurl,
                data: { action: 'kecamatan', city_destination: $("#city-destination").val() },
                success: function (data) {
                    $('#subdistrict-destination').html(data);
                },
            });
        }
    });
    
    // pengajuan product
    $(document).on('click','.velocity-premium-button', function() {
        var idp = $(this).attr('id');
        if(idp){
            $(this).html('<div class="spinner-grow spinner-grow-sm"><span class="visually-hidden">Loading...</span></div>');
            jQuery.ajax({
                type    : "POST",
                url     : velocityiklan.ajaxurl,
                data    : {action:'iklanpremium',id:idp},
                success :function(data) {
                    $('.card-product-'+idp).addClass('bg-light border-dark');
                    $('#btn-'+idp).remove();
                },
            }); 
        }
    });
    
    // Remove product
    $(document).on('click','.btn-product-delete', function() {
        var idp = $(this).attr('id');
        if(idp){
            if (confirm("Hapus produk ini?") == true) {
                $(this).html('<div class="spinner-grow spinner-grow-sm"><span class="visually-hidden">Loading...</span></div>');
                jQuery.ajax({
                    type    : "POST",
                    url     : velocityiklan.ajaxurl,
                    data    : {action:'deleteproduct',id:idp},
                    success :function(data) {
                        $('.card-product-'+idp).addClass('border border-danger');   
                        setTimeout(function() {
                            $('.product-'+idp).remove();
                        }, 1700);
                    },
                }); 
            }
        }
    });
});