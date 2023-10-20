<?php
    // Define a shortcode called 'c_dynamic_list' with a callback function.
    add_shortcode('c_dynamic_list', function ($atts) {
        // Set default values for attributes if not provided.
        $atts = shortcode_atts(array(
            'acf' => 'services',
            'selector' => '.services_list',
        ), $atts);

        // Extract attribute values.
        $selector = $atts['selector'];
        $contents = get_field($atts['acf']);
        $encoded = json_encode($contents);

        // Start output buffering to capture the script content.
        ob_start();
    ?>
        <script>
            // Define an anonymous function that takes 'content' as a parameter.
            (($content) => {
                try {
                    const content = $content;
                    const main_element = document.querySelector('<?= $selector ?> .elementor-icon-list-item');
                    const parent = main_element.parentElement;
                    const clones = content.map(c => create_clone(main_element, c.text));

                    // Remove the original main element.
                    main_element.remove();

                    // Append the cloned elements to the parent.
                    parent.append(...clones);
                } catch (e) {
                    console.error(e);
                } finally {
                    // Remove the current script element.
                    document.currentScript.remove();
                }

                // Function to create a clone of the main element with updated text.
                function create_clone(main_element, text) {
                    const clone = main_element.cloneNode(true);
                    const textNode = clone.querySelector('.elementor-icon-list-text');
                    textNode.textContent = text;

                    return clone;
                }
            })(<?= $encoded ?>)
        </script>
    <?php
        // Return the captured script content as the shortcode output.
        return ob_get_clean();
    });
?>
