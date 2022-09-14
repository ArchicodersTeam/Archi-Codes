add_filter( 'woocommerce_add_to_cart_validation', function ( $passed, $added_product_id ) {
   wc_empty_cart();
   return $passed;
}, 9999, 2 );

add_filter ('woocommerce_add_to_cart_redirect', function( $url, $adding_to_cart ) {
    return wc_get_checkout_url();
}, 10, 2 );