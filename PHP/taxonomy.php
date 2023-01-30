<?php

function create_cpt_taxonomy() {
    register_taxonomy(
		'cpt-cat',
        'customposttype',
        array(
            'label' => __( 'CPT Categories' ),
            'rewrite' => array( 'slug' => 'cpt-cat' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_cpt_taxonomy' );


?>
