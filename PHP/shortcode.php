<?php

    add_shortcode('my_first_shortcode', function ($attributes) {
        ob_start();

        echo "Hello, World";

        return ob_get_clean();
    });

?>