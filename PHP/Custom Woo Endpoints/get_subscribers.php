<?php

/**
 * Register a custom REST API route to fetch subscribers' information.
 */
add_action('rest_api_init', function () {
	register_rest_route('order_report/v1', '/subscribers', array(
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
function get_order_report_subscribers($request)
{

	$subscribers = array();
	$type = strtolower($request->get_param('type'));
	$date_start = $request->get_param('date_start'); // yyyy-mm-dd
	$date_end = $request->get_param('date_end');     // yyyy-mm-dd
	$types = array('b2b', 'b2c');
	$date_created = "";

	if ($type && !in_array($type, $types)) {
		return new WP_Error('input_error', "incorrect type: b2b & b2c only", array('status' => 403));
	}

	if (($date_start && !date_format_checker($date_start)) || ($date_end && !date_format_checker($date_end))) {
		return new WP_Error('input_error', "incorrect date format, please use: yyyy-mm-dd ", array('status' => 403));
	}

	$subscriber_users = get_subscribers($type ? $type : "b2c");
	// Handle date range filtering.
	if ($date_start || $date_end) {
		if ($date_start != '') {
			$date_created = "$date_start...";
			if (!$date_end) {
				$currentDate = date("Y-m-d");
				$date_created .= $currentDate;
			}
		}

		if ($date_start != '' && $date_end != '') {
			$date_created .= $date_end;
		} else if ($date_end != '') {
			$currentDate = date("Y-m-d");
			$date_created = "1970-01-01...$date_end";
		}
	}


	// Loop through each subscriber user to gather required information.
	foreach ($subscriber_users as $user) {
		$last_order = get_user_last_order($user->ID);
		$subscribers[] = array(
			"id" => $user->ID,
			"name" => $user->first_name . " " . $user->last_name,
			"subscription" => get_user_subscriptions($user->ID),
			"last_order_date" => $last_order['order_date'],
			"last_order_details" => $last_order['order_details'],
			"creation_date" => $user->user_registered,
			"customer_address" => get_subscriber_address($user->ID),
			"list_of_orders" => array(
				"date_range" => $date_created ? $date_created : 'All Date',
				"result" => get_subscriber_orders($user->ID, $date_created)
			)
		);
	}

	// Prepare the response data.
	$data = array(
		'subscribers' => $subscribers,
	);

	// Return a REST API response with the subscribers' data.
	return rest_ensure_response($data);
}

function get_subscriber_orders($user_id, $date_created)
{
	// Prepare order arguments.
	$order_args = array(
		'customer_id' => $user_id,
		'date_created' => $date_created
	);

	$orders = array();
	$order_posts = wc_get_orders($order_args);

	// Loop through each order.
	foreach ($order_posts as $order) {
		global $wpdb;

		$order_items = $order->get_items(); // Get items in the order
		$order_id = $order->get_id();
		$order_notes = $wpdb->get_results("
			SELECT * 
			FROM {$wpdb->prefix}comments
			WHERE comment_post_ID = $order_id
			AND comment_type = 'order_note'
			ORDER BY comment_ID DESC
		");
		$order_notes_cleaned = array_map(function($note){
			return array(
				"author_name" => $note -> comment_author,
				'date' => $note -> comment_date,
				'content' => $note -> comment_content
			);
		}, $order_notes);

		$product_details = array();
		// Loop through each item in the order.
		foreach ($order_items as $item_id => $item) {
			$product = $item->get_product();
			$categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names'));

			$product_details[] = array(
				'product_id' => $product->get_id(),
				'name' => $product->get_name(),
				'price' => $product->get_price(),
				'categories' => $categories
			);
		}

		$orders[] = array(
			'creation_date' => $order->get_date_created()->date('Y-m-d H:i:s'),
			'total' => $order->get_total(),
			'products' => $product_details, // Add product details to the order
			'order_notes' => $order_notes_cleaned
		);
	}

	return $orders;
}

function get_subscribers($type = "B2C")
{
	// Query arguments to retrieve subscribers with the 'subscriber' role, ordered by ID in ascending order.
	$subscriber_args = array(
		'role'      => 'subscriber',
		'orderby'   => 'ID',
		'order'     => 'ASC',
	);

	$subscribers = array();
	$subscriber_users = get_users($subscriber_args);

	// Loop through each subscriber user to gather required information.
	foreach ($subscriber_users as $user) {
		$user_id = $user->ID;
		$subscription = get_user_subscriptions($user_id);

		if (strtolower($type) == strtolower($subscription))
			$subscribers[] = $user;
	}

	return $subscribers;
}

function get_user_subscriptions($user_id)
{
	$subscriptions = wcs_get_users_subscriptions($user_id);
	if ($subscriptions) {
		foreach ($subscriptions as $subscription) {
			if ($subscription->get_status() === 'active') {
				// You've found the active subscription
				$subscription_id = $subscription->get_id();
				// Do something with the subscription ID
				return get_subscription_data($subscription_id);
			}
		}
	}

	return false;
}

function get_subscription_data($subscription_id)
{
	$subscription = wcs_get_subscription($subscription_id); // Replace $subscription_id with the actual subscription ID
	$data = null;
	if ($subscription) {
		$subscription_items = $subscription->get_items();

		foreach ($subscription_items as $subscription_item) {
			$item_data = $subscription_item->get_name();

			if (strpos(strtolower($item_data), 'for home') !== false)
				$data = "B2C";
			else if (strpos(strtolower($item_data), 'commercial') !== false)
				$data = "B2B";
			else $item_data;

			break;
		}
	}

	return $data;
}

function get_user_last_order($user_id)
{
	// Query arguments to retrieve completed orders of the current user
	$args = array(
		'post_status' => 'wc-completed',
		'customer_id'  => $user_id,
		'numberposts' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
	);

	// Get completed orders of the current user
	$completed_orders = wc_get_orders($args);
	$order_date = null;
	$order_details = array();

	foreach ($completed_orders as $order) {
		$order_date = $order->get_date_created()->format('Y-m-d H:i:s');
		$order_notes = $order->get_customer_order_notes();
		$items = array();
		foreach ($order->get_items() as $item_id => $item) {
			$product = $item->get_product();
			$categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names'));

			$items[] = array(
				'product_id' => $product->get_id(),
				'name' => $product->get_name(),
				'price' => $product->get_price(),
				'categories' => $categories
			);
		}
		$order_details = array(
			'order_id' => $order->get_id(),
			'order_status' => $order->get_status(),
			'total_amount' => $order->get_total(),
			'items' => $items,
			'order_note' => $order_notes
		);
	}

	return array(
		"order_date" => $order_date,
		"order_details" => $order_details
	);
}


function get_subscriber_address($user_id)
{
	return array(
		'company' => get_user_meta($user_id, 'shipping_company', true),
		'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
		'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
		'city' => get_user_meta($user_id, 'shipping_city', true),
		'state' => get_user_meta($user_id, 'shipping_state', true),
		'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
		'country' => get_user_meta($user_id, 'shipping_country', true)
	);
}
