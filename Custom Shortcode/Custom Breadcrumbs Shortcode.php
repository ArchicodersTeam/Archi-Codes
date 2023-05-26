<?php 
add_shortcode('custom_breadcrumbs', function () {
	// Define the separator between breadcrumbs
	$separator = '<span class="separator"> / </span>';

	// Get the breadcrumbs array
	$breadcrumbs = array();

	// Add the Home link
	$breadcrumbs[] = '<a href="' . get_home_url() . '">' . esc_html__('Home', 'text-domain') . '</a>';

	// Check if we're on a singular post/page
	if (is_singular()) {
		global $post;

		// Get the post type
		$post_type = get_post_type();

		// Get the post type archive link
		$post_type_archive_link = get_post_type_archive_link($post_type);

		// Check if the post type has an archive link
		if ($post_type_archive_link) {
			$breadcrumbs[] = '<a href="' . $post_type_archive_link . '">' . esc_html__(ucfirst($post_type), 'text-domain') . '</a>';
		}

		// Get the post parents (if any)
		$parents = array_reverse(get_post_ancestors($post->ID));

		// Loop through the parents
		foreach ($parents as $parent) {
			$breadcrumbs[] = '<a href="' . get_permalink($parent) . '">' . get_the_title($parent) . '</a>';
		}

		// Add the current post/page title
		$breadcrumbs[] = '<span>' . get_the_title() . '</span>';
	} else {
		// Add the current archive title
		$breadcrumbs[] = '<span>' . get_the_archive_title() . '</span>';
	}

	// Output the breadcrumbs
	echo '<div class="breadcrumbs">' . implode($separator, $breadcrumbs) . '</div>';
});