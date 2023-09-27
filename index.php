// Add automatically a free product in Woocommerce cart 
// Specific Category Add automatically a free Specific product in Woocommerce cart

add_action( 'woocommerce_before_calculate_totals', 'auto_add_item_based_on_product_category', 10, 1 );
function auto_add_item_based_on_product_category( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    $required_categories = array('1-taka'); // Required product category(ies)
    $added_product_id = 509; // Specific product to be added automatically
    $matched_category = false;

    // Loop through cart items
    foreach ( $cart->get_cart() as $item_key => $item ) {
        // Check for product category
        if( has_term( $required_categories, 'product_cat', $item['product_id'] ) ) {
            $matched_category = true;
        }
        // Check if specific product is already auto added
        if( $item['data']->get_id() == $added_product_id ) {
            $saved_item_key = $item_key; // keep cart item key
        }
    }

    // If specific product is already auto added but without items from product category
    if ( isset($saved_item_key) && ! $matched_category ) {
        $cart->remove_cart_item( $saved_item_key ); // Remove specific product
    }
    // If there is an item from defined product category and specific product is not in cart
    elseif ( ! isset($saved_item_key) && $matched_category ) {
        $cart->add_to_cart( $added_product_id ); // Add specific product
    }
}
