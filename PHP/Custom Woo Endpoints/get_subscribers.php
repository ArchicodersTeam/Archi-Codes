<?php

/**
 * Register a custom REST API route to fetch subscribers' information.
 */
add_action( 'rest_api_init', function () {
	register_rest_route( 'order_report/v1', '/subscribers', array(
		'methods' => 'GET',
		'callback' => 'get_order_report_subscribers',
	));
});

/**
 * Callback function to retrieve and return subscribers' information.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return WP_REST_Response The REST API response with subscribers' data.
 */
function get_order_report_subscribers( $request ) {
	// Query arguments to retrieve subscribers with the 'subscriber' role, ordered by ID in ascending order.
	$subscriber_args = array(
		'role'      => 'subscriber',
		'orderby'   => 'ID',
		'order'     => 'ASC',
	);

	$subscribers = array();
	$subscriber_users = get_users( $subscriber_args );

	// Loop through each subscriber user to gather required information.
	foreach ( $subscriber_users as $user ) {
		$subscribers[] = array(
			"id" => $user->ID,
			"username" => $user->user_login,
			"email" => $user->user_email,
			'user_registered' => $user -> user_registered
		);
	}
	
	// Prepare the response data.
	$data = array(
	  'subscribers' => $subscribers,
	);

	// Return a REST API response with the subscribers' data.
	return rest_ensure_response( $data );
}


?>