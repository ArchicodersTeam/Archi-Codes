<?php

/**
 * Register a custom REST API route to retrieve user's order information.
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'order_report/v1', '/orders/user/(?P<user_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'get_user_orders',
		'permission_callback' => function ( $request ) {
          return $request->get_param( 'auth_token' ) === 'test_password';
        }
	));
});

/**
 * Callback function to retrieve user's order information.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response|array Returns the response containing user's order data.
 */
function get_user_orders( $request ) {
	$user_id = $request->get_param( 'user_id' );
	$date_start = $request->get_param( 'date_start' ); // yyyy-mm-dd
	$date_end = $request->get_param( 'date_end' );     // yyyy-mm-dd
	$date_created = "";
	$total_seedlings = 0;
	$user_data = get_userdata( $user_id );

	// Return an error if user data is not found.
	if ( ! $user_data ) {
		 return new WP_Error( 'get_error', "Error getting user #$user_id", array( 'status' => 404 ) );
	}
	
	if(($date_start && !date_format_checker($date_start)) || ($date_end && !date_format_checker($date_end))){
		return new WP_Error( 'input_error', "incorrect date format, please use: yyyy-mm-dd ", array( 'status' => 403 ) );
	}

	// Handle date range filtering.
	if ( $date_start || $date_end ) {
		if ( $date_start != '' ) {
			$date_created = "$date_start...";
			if ( ! $date_end ) {
				$currentDate = date( "Y-m-d" );
				$date_created .= $currentDate;
			}
		}

		if ( $date_start != '' && $date_end != '' ) {
			$date_created .= $date_end;
		} else if ( $date_end != '' ) {
			$currentDate = date( "Y-m-d" );
			$date_created = "1970-01-01...$date_end";
		}
	}

	// Prepare order arguments.
	$order_args = array(
		'customer_id' => $user_id,
		'date_created' => $date_created
	);

	$orders = array();
	$order_posts = wc_get_orders( $order_args );

	// Loop through each order.
	foreach ( $order_posts as $order ) {
		$order_items = $order->get_items(); // Get items in the order
		$currentOrderSeedlings = 0;
		
		$product_details = array();
		// Loop through each item in the order.
		foreach ( $order_items as $item_id => $item ) {
			$product = $item->get_product();
			$categories = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
			$containsSeedlings = in_array( 'Seedlings', $categories );
			if ( $containsSeedlings ) {
				$total_seedlings++;
				$currentOrderSeedlings++;
			}
			
			$product_details[] = array(
				'product_id' => $product->get_id(),
				'name' => $product->get_name(),
				'price' => $product->get_price(),
				'categories' => $categories
			);
		}

		$orders[] = array(
			'order_id' => $order->get_id(),
			'order_date' => $order->get_date_created()->date( 'Y-m-d H:i:s' ),
			'total_seedlings' => $currentOrderSeedlings,
			'total' => $order->get_total(),
			'products' => $product_details, // Add product details to the order
		);
	}
	
	$data = array(
		'user_id' => $user_id,
		'user_display_name' => $user_data->display_name,
		'user_email' => $user_data->user_email,
		'total_seedlings' => $total_seedlings,
		'orders' => $orders,
	);

	return rest_ensure_response( $data );
}

function date_format_checker($dateString = ""){
	$format = "Y-m-d";
	$date = DateTime::createFromFormat($format, $dateString);
	
	return $date && $date->format($format) === $dateString;
}

?>