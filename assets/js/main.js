jQuery(document).ready(function($) {

    var ajaxurl = cartajax.ajaxurl;
    var nonce = cartajax.nonce;

    $(".woocommerce").on("click", ".product-remove a.remove", function(){//trigger every removal of product from cart
        $('#add-on-custom').block({ //make the table unclickable while processing the removal
            message: null,
            overlayCSS: { backgroundColor: '#ffffff' }
         }); 
        var id = $(this).attr("data-product_id");//get the product ID
        jQuery.post( //Call the function for appending new row to the product addons
            ajaxurl, 
            {
                'action': 'add_oncart',
                'prodid': id,
                'nonce' : nonce
            }, 
            function(response){
                if(response!='false'){//if response is not false
                    if($("#purchased_all").length){
                        $("#purchased_all").replaceWith('<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0" id="add-on-custom"><thead>'+
                        '<tr>'+
                          '<th class="product-thumbnail"></th>'+
                                  '<th class="product-name">Product</th>'+
                                  '<th class="product-price">Price</th>'+
                                  '<th class="product-subtotal">Action</th>'+
                             '</tr>'+
                              '</thead>'+
                        '<tbody>'+
                        response+
                        '</tbody>'+
                        '</table>'
                        );
                    }else{
                        $('#add-on-custom').append(response);//append to the table
                    }
                    
                }
                $('#add-on-custom').unblock(); 
                
            }

        );
    });
    $(".woocommerce-notices-wrapper").on("click", "a.restore-item", function(){//trigger when removal from cart is undone
        $('#add-on-custom').block({ //make the table unclickable while processing the removal
            message: null,
            overlayCSS: { backgroundColor: '#ffffff' },
            timeout:   3000, //added timeout so the table would be unclickable while the undoing process is not finished
            onBlock: function() { 
                $('#add-on-custom ul:last').remove(); //remove the last entry on the product table after product removal on cart
            } 
         }); 
    });

     
});
