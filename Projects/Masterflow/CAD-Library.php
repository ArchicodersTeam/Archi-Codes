<?php

function cad_library_shortcode()
{
    ob_start();
?>
    <div class="cad">
        <div class="cad-filter">
            <div class="cad-container">
                <form method="GET" action="/cad-library/" class="filter-form" oninput='this.submit()' id="cad-library-filter">
                    <?php
                    _renderSelect('Category', 'product_cat', true);

                    if (in_array(
                        $_GET['product_cat'],
                        ['Pumpsets', '']
                    )) {
                        _renderSelect('Pump Type', 'pa_select-type');
                        _renderSelect('Pump Model', 'pa_model');
                        _renderSelect('Motor Size', 'pa_motor-size');
                        _renderSelect('Base Type', 'pa_base-type');
                        _renderSelect('Mount Type', 'pa_mount-type');
                    }

                    if (in_array(
                        $_GET['product_cat'],
                        ['Pressure Expansion Tanks', '']
                    )) {
                        _renderSelect('Capacity', 'pa_litre');
                        _renderSelect('Bar', 'pa_bar');
                    }

                    if (in_array(
                        $_GET['product_cat'],
                        ['CAD Library', 'Pressure Expansion Tanks', '']
                    )) {
                        _renderSelect('Flow Range', 'pa_sidestream-flow-range');
                    }
                    ?>
                </form>
                <script>
                    function _resetSelect() {
                        const form = document.querySelector('#cad-library-filter.filter-form ')
                        const selected = [...form.querySelectorAll('select:not([name="product_cat"]) option[selected]')]

                        selected.forEach(s => s.removeAttribute('selected'))

                        form.submit()
                    }
                </script>
            </div>
        </div>
        <div class="cad-container">
            <div class="cad-loop">
                <?php
                $args = _getCurrentQuery();
                // The Query
                $query = new WP_Query($args);

                // The Loop
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                ?>
                        <div class="cad-item">
                            <div class="cad-item-inner">
                                <div class="cad-img"><?php the_post_thumbnail('full') ?></div>
                                <h3><?php the_title(); ?></h3>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    // no posts found
                    echo 'No Result Found';
                }

                $total_pages = $query->max_num_pages;
                $big = 999999999;

                if ($total_pages > 1) {

                    $current_page = max(1, get_query_var('paged'));

                    echo '<div class="pagination-wrap">';
                    echo paginate_links(array(
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '/page/%#%',
                        'current' => $current_page,
                        'total' => $total_pages,
                        'prev_text'    => __('<i class="fa fa-chevron-left"></i>'),
                        'next_text'    => __('<i class="fa fa-chevron-right"></i>'),
                    ));
                    echo '</div>';
                }

                // Restore original Post Data
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}

function _renderSelect($title, $term, $isCat = false)
{
    $argss = _getCurrentQuery(-1);
?>
    <div class="post-filter">
        <select name="<?= $term ?>" oninput="<?= $isCat ? '_resetSelect()' : '' ?>">
            <?php
            $att = $isCat ?
                get_categories(array(
                    'taxonomy' => $term,
                    'orderby' => 'name',
                    'order'   => 'ASC',
                    'hide_empty' => true,
                    'parent' => 0,
                )) : _getTerms($term, $argss);
            ?>
            <option value="">Select <?= $title ?></option>
            <?php
            foreach ($att as $a) :
                $selected = $_GET[$term] == $a->name ? 'selected' : '';
            ?>
                <option <?= $selected ?> value='<?= $a->name ?>'>
                    <?= $a->name ?>
                </option>
            <?php
            endforeach;
            ?>
        </select>
    </div>
<?php


}

function _getTerms($key, $args)
{
    $query = new WP_Query($args);
    // The Loop
    while ($query->have_posts()) {
        $query->the_post();
        $get_terms = get_the_terms(get_the_ID(), $key);
        foreach ($get_terms as $term) {
            $terms[] = $term->name;
        }
    }

    $unique = array_unique($terms);
    foreach ($unique as $u) {
        $result[] = (object) ['name' => $u];
    }

    return $result;
}

function _getCurrentQuery($numofposts = 3)
{
    $taxs = ['pa_select-type', 'pa_model', 'pa_motor-size', 'pa_base-type', 'pa_mount-type', 'pa_bar', 'pa_litre', 'pa_sidestream-flow-range'];
    $cat = $_GET['product_cat'] ? $_GET['product_cat'] : '';
    $taxQuery = array('relation' => 'AND');

    foreach ($taxs as $tax) {
        if ($_GET[$tax] != '') {
            array_push($taxQuery, array(
                'taxonomy' => $tax,
                'field'    => 'slug',
                'terms'    => $_GET[$tax],
            ));
        }
    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    // WP_Query arguments
    $args = array(
        'post_type'              => 'product',
        'posts_per_page'         => $numofposts,
        'order'                  => 'DESC',
        'orderby'                  => 'date',
        'product_cat'             => $cat,
        's'                      => $search,
        'tax_query'             => $taxQuery,
    );

    return $args;
}

add_shortcode('cad_library', 'cad_library_shortcode');

?>