<?php
    function enqueue_gsap_scripts() {
        // Enqueue GSAP main library
        wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
        // Enqueue ScrollTrigger plugin
        wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', true);
    }

    add_action('wp_enqueue_scripts', 'enqueue_gsap_scripts');
