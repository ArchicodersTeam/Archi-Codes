<?php

add_shortcode('faq_listing', function () {

    if (!get_field('enable_faq_section')) return;

    $contents = get_field('faq_list');
    $encoded = json_encode($contents);

    ob_start();
?>
    <script>
        (($content) => {
            const mainCard = document.querySelector('#highnote-acrdn .elementor-accordion-item')
            const card_parent = mainCard.parentElement
            const clones = $content.map((acf, index) => {
                const cloned = mainCard.cloneNode(true)
                const title_id = `elementor-tab-title-1011${index}`
                const content_id = `elementor-tab-content-1011${index}`
                const $title = cloned.querySelector('.elementor-tab-title')
                const $content = cloned.querySelector('.elementor-tab-content')

                $title.id = title_id
                $content.id = content_id
                $title.setAttribute('tabindex', index)
                $title.setAttribute('data-tab', index + 1)

                $title.setAttribute('aria-controls', content_id)
                $content.setAttribute('aria-controls', title_id)
                $content.setAttribute('data-tab', index + 1)


                $title.querySelector('.elementor-accordion-title').textContent = acf.title
                $content.innerHTML = acf.description

                return cloned
            })

            card_parent.append(...clones)
            mainCard.remove()
            document.currentScript.remove()
        })(<?= $encoded ?>)
    </script>
<?php
    return ob_get_clean();
});

add_shortcode('acf_disabled', function ($atts) {
    $atts = shortcode_atts(array(
        'field' => '',
    ), $atts);

    $field = $atts['field'];
    $isActive = get_field($field) ? "true" : "false";

    return "data-acf-active|$isActive";
});

?>