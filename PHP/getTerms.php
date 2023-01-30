<?php

$terms = get_terms( array(
    'taxonomy' => 'cpt-cat',
    'hide_empty' => false
) );
foreach ($terms as $term) {
    echo '<button type="submit" name="cpt-filter" value="'.$term->slug.'" >'.$term->name.'</button>';
}

?>
