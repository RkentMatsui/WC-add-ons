<?php
/**
 * Shortcode for the cart addons table
 */
function custom_add_ons(){
	
	$home_url = get_home_url();
	ob_start();
	$output =  '<ul class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0" id="add-on-custom">';
  
	$products = wc_get_products();
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
  $productscount = 0;
	foreach($products as $product){//loop through all products
    $prodid= $product->get_id();
    $product_short_description = get_post($prodid)->post_excerpt;//get product short description
    $show_prod = $product->get_attribute( 'show_related' );//get RCP membership ID
    $price = $product->get_price();
      if(WC()->cart!=null){
        $product_cart_id = WC()->cart->generate_cart_id( $prodid );
        $in_cart = WC()->cart->find_product_in_cart( $product_cart_id );
        if($in_cart){//check if product is already in cart //dont show if already on cart
          continue;
        } 
      }
    if(empty($show_prod) || strtolower($show_prod) != 'yes'){
      continue;
    }
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $prodid ), 'single-post-thumbnail' );
    if(is_bool($image)){
      $image[0] = '';
    }
    //loop through if product is not on cart and not bought
    $output .= '
		<li class="woocommerce-cart-form__cart-item cart_item">
          <div class="product-thumbnail">
            <div class="tooltip"> 
              <img width="188" height="300" src="'.$image[0].'" 
              class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
              srcset="'.$image[0].' 188w,
              '.$image[0].' 94w,
              '.$image[0].' 31w,
              '.$image[0].' 156w,
              '.$image[0].' 63w,
              '.$image[0].' 125w,
              '.$image[0].' 250w"
              sizes="(max-width: 188px) 100vw, 188px">
              <span class="tooltiptext">'.$product_short_description.'</span>
            </div>
          </div>
          <div class="addoncustom-details">
  					<div class="product-name" data-title="Product">
  						<b>'.$product->get_title().'</b>
  					</div>
  					<div class="product-price" data-title="Price">
  						<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>'.$price.'</span>
            </div>
          </div>
					<div class="product-add-to-cart" data-title="Add to Cart">
					  <a href="?add-to-cart='.$prodid.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$prodid.'" data-product_sku="" aria-label="Add “'.$product->get_title().'” to your cart" rel="nofollow">Add to cart</a>
          </div>
		</li>
	';
	$productscount++;
  }
 
	$output .='</ul>';
  if($productscount==0){
    $output = '<h3 id="purchased_all" style="text-align: center;">You already have access to all additional products</h3>';
  }
  echo $output;
	return ob_get_clean();
}
add_shortcode( 'cart_add_on', 'custom_add_ons' );

/**
 * Called after a product is removed on cart
 */
function refreshed_addons(){
	$newprod = $_POST['prodid'];
	$product = wc_get_product($newprod);
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
  $show_prod = $product->get_attribute( 'show_related' );//get RCP membership ID
  $prodid= $product->get_id();
  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $newprod ), 'single-post-thumbnail' );
  $price = $product->price;
  $product_short_description = get_post($prodid)->post_excerpt;//get product short description
	//output a new row
  $table = '
  <li class="woocommerce-cart-form__cart-item cart_item">
          <div class="product-thumbnail">
            <div class="tooltip"> 
              <img width="188" height="300" src="'.$image[0].'" 
              class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt=""
              srcset="'.$image[0].' 188w,
              '.$image[0].' 94w,
              '.$image[0].' 31w,
              '.$image[0].' 156w,
              '.$image[0].' 63w,
              '.$image[0].' 125w,
              '.$image[0].' 250w"
              sizes="(max-width: 188px) 100vw, 188px">
              <span class="tooltiptext">'.$product_short_description.'</span>
            </div>
          </div>
          <div class="addoncustom-details">
  					<div class="product-name" data-title="Product">
  						<b>'.$product->get_title().'</b>
  					</div>
  					<div class="product-price" data-title="Price">
  						<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">R</span>'.$price.'</span>
            </div>
          </div>
					<div class="product-add-to-cart" data-title="Add to Cart">
					  <a href="?add-to-cart='.$prodid.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.$prodid.'" data-product_sku="" aria-label="Add “'.$product->get_title().'” to your cart" rel="nofollow">Add to cart</a>
          </div>
	</li>';
	die($table);

}
add_action( 'wp_ajax_add_oncart', 'refreshed_addons' );
add_action( 'wp_ajax_nopriv_add_oncart', 'refreshed_addons' );