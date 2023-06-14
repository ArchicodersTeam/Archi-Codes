<?php
    // Hook the function to the 'wp_enqueue_scripts' action
    add_action( 'wp_enqueue_scripts', function () {
        // Enqueue the Masonry script using the wp_enqueue_script function
        wp_enqueue_script( 'masonry', 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js', array( 'jquery' ), '4.0.0', true );
    });
?>
