<?php

function create_listing_post_type() {
    register_post_type('customposttype',
        array(
            'labels' => array(
                'name' => __('customposttypes'),
                'singular_name' => __('customposttype')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail')
        )
    );
}
add_action('init', 'create_customposttype');


?>
