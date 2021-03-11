jQuery(document).ready(function($) {
    $('div').on("click",".ajax_add_to_cart",function(event){
        event.preventDefault();
        var in_cart_page = $(this).parent().parent().attr('class');
        var in_cart_page_att =  $(this).parent().parent();
         var $thisbutton = $(this),
            product_id = $(this).data('product_id'), 
            product_qty = $(this).data('quantity');
            
        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            quantity: product_qty,
            product_sku: '',
            variation_id: 0,
        };
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function () {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function () {
                $thisbutton.addClass('added').removeClass('loading');
            }, 
            success: function (response) {
                //console.log(response);
                if (response.error ) {
                    $("#modal-cont,#modal-background, #thrive-header, #thrive-footer").toggleClass("active");
                    $(".wc-custom-msg-error").css("display", "block");
                    $(".wc-custom-msg").css("display", "none");
                } else {
                    $("#modal-cont,#modal-background, #thrive-header, #thrive-footer").toggleClass("active");
                    $(".wc-custom-msg").css("display", "block");
                    $(".wc-custom-msg-error").css("display", "none");
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
                if(in_cart_page == 'woocommerce-cart-form__cart-item cart_item'){
                    in_cart_page_att.remove();
                }
            },          
        });  
        return false;
    });
});