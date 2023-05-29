<?php

    add_shortcode('custom_breadcrumbs', function ($atts) {
        $atts = shortcode_atts(
            array(
                'dark-mode' => false,
            ),
            $atts
        );

        // Access the attribute values
        $isDarkMode = $atts['dark-mode'];

        // Get the current post object
        $post = get_post();

        // Get the post type
        $post_type = $post->post_type;

        // Set Taxonomy
        $taxonomy = 'resources-category';

        // Check if the post has the $taxonomy assigned
        if (taxonomy_exists($taxonomy) && has_term('', $taxonomy, $post)) {
            // Get the terms of the $taxonomy assigned to the post
            $terms = get_the_terms($post, $taxonomy);

            // Sort the terms based on their hierarchical structure
            $sorted_terms = array();
            foreach ($terms as $term) {
                if ($term->parent == 0) {
                    $sorted_terms[] = $term;
                    $sorted_terms = array_merge($sorted_terms, get_sorted_term_children($term, $terms));
                }
            }

            // Start building the breadcrumbs HTML
            $breadcrumbs = '<div id="custom-breadcrumbs">';

            // Add the home link
            $divider = '<span class="breadcrumbs-divider"></span>';
            $breadcrumbs .= '<a href="' . home_url() . '">Home</a>';
            $breadcrumbs .= $divider;
            // Iterate through the sorted terms and add their links
            foreach ($sorted_terms as $term) {
                $term_link = get_term_link($term);

                if (is_wp_error($term_link)) {
                    continue;
                }

                $breadcrumbs .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                $breadcrumbs .= $divider;
            }

            // Add the current post title as the last item
            $breadcrumbs .= '<span>' . get_the_title() . '</span>';

            // Close the breadcrumbs HTML
            $breadcrumbs .= '</div>';

            // Output the breadcrumbs
            echo breadcrumbs_css($isDarkMode);
            echo $breadcrumbs;
        }
    });

    add_shortcode('custom_archive_breadcrumbs', function ($atts) {
        $atts = shortcode_atts(
            array(
                'dark-mode' => 'false',
            ),
            $atts
        );

        // Access the attribute values
        $isDarkMode = $atts['dark-mode'];

        // Check if it's an archive page
        if (is_tax()) {
            // Set Taxonomy
            $taxonomy = 'resources-category';

            // Get the current term object
            $term = get_queried_object();
            $title = $term->name;

            // Check if the current term belongs to the specified taxonomy
            if ($term instanceof WP_Term && $term->taxonomy === $taxonomy) {
                // Get the term hierarchy in the correct order
                $term_hierarchy = get_ancestors($term->term_id, $taxonomy, 'taxonomy');
                $term_hierarchy = array_reverse($term_hierarchy);

                // Start building the breadcrumbs HTML
                $breadcrumbs = '<div id="custom-breadcrumbs">';
                $divider = '<span class="breadcrumbs-divider"></span>';

                // Add the home link
                $breadcrumbs .= '<a href="' . home_url() . '">Home</a>';
                $breadcrumbs .= $divider;

                // Iterate through the term hierarchy and add their links
                foreach ($term_hierarchy as $term_id) {
                    $term = get_term($term_id, $taxonomy);
                    $term_link = get_term_link($term);

                    if (is_wp_error($term_link)) {
                        continue;
                    }

                    $breadcrumbs .= '<a href="' . $term_link . '">' . $term->name . '</a>';
                    $breadcrumbs .= $divider;
                }

                // Add the current term name as the last item
                $breadcrumbs .= '<span>' . $title . '</span>';

                // Close the breadcrumbs HTML
                $breadcrumbs .= '</div>';

                // Output the breadcrumbs
                echo breadcrumbs_css($isDarkMode);
                echo $breadcrumbs;
            }
        }
    });

    function breadcrumbs_css($isDarkMode)
    {
        ob_start();
    ?>
        <style>
            #custom-breadcrumbs .breadcrumbs-divider {
                display: inline-block;
                width: 1em;
                height: 1em;
                position: relative;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath fill='%23<?= $isDarkMode ? '000' : 'fff' ?>' d='M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z'/%3E%3C/svg%3E");
                background-size: 100% 100%;
                background-repeat: no-repeat;
            }

            #custom-breadcrumbs a {
                display: inline-block;
                width: auto;
                color: <?= $isDarkMode ? '#000' : '#fff' ?>;
            }

            #custom-breadcrumbs {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 5px;
            }
        </style>
    <?php
        return ob_get_clean();
    }

    function get_sorted_term_children($term, $terms)
    {
        $sorted_children = array();

        foreach ($terms as $child_term) {
            if ($child_term->parent == $term->term_id) {
                $sorted_children[] = $child_term;
                $sorted_children = array_merge($sorted_children, get_sorted_term_children($child_term, $terms));
            }
        }

        return $sorted_children;
    }

?>