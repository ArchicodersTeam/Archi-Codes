<?php

function create_listing_taxonomy() {
    register_taxonomy(
        'cpt-cat',
        array(
            'label' => __( 'CPT Categories' ),
            'rewrite' => array( 'slug' => 'cpt-cat' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_cpt_taxonomy' );

?>
