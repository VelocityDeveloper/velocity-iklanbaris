jQuery(document).ready(function($) {
    
    $(document).on('click', '.publishpost', function() {
	    $(this).html('<i class="fa fa-spinner fa-pulse"></i>');
	    var id      = $(this).attr('data-id');
		jQuery.ajax({
            type    : "POST",
            url     : ajaxurl,
            context : this,
            data    : {action:'updatepost', dataid:id },  
            success :function(data) {
                $(this).html('Berhasil');
        },
        });
	});
	
	$(document).on('click', '.unpublishpost', function() {
	    $(this).html('<i class="fa fa-spinner fa-pulse"></i>');
	    var id      = $(this).attr('data-id');
		jQuery.ajax({
            type    : "POST",
            url     : ajaxurl,
            context : this,
            data    : {action:'oldpost', dataid:id },  
            success :function(data) {
                $(this).html('Berhasil');
        },
        });
	});
    
    // konfirmasi produk premium
    $(document).on('click','.konfirmasi-premium', function() {
        var idp = $(this).attr('id');
        var aksi = $(this).html();
        if(idp){
            $(this).html('<div class="spinner-grow spinner-grow-sm"><span class="visually-hidden">Loading...</span></div>');
            jQuery.ajax({
                type    : "POST",
                url     : velocityiklan.ajaxurl,
                data    : {action:'konfirmasipremium',id:idp,confirm:aksi},
                success :function(data) {
					if(aksi == 'Hapus'){
                    	$('.tr-'+idp).remove();
					} else {
                    	$('.aksi-'+idp).html('Diterima');
						$('.tr-'+idp).css('background', '#d6f3d6');
					}
                },
            }); 
        }
    });
});