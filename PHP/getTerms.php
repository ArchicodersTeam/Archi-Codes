<?php

$terms = get_terms( array(
    'taxonomy' => 'awards-cat',
    'hide_empty' => false
) );
foreach ($terms as $term) {
    $selected = isset($_GET['awards-cat']) && $_GET['awards-cat'] == $term->slug ? 'selected' : '';
    echo '<button type="submit" name="awards-filter" value="'.$term->slug.'" '.$selected.'>'.$term->name.'</button>';
}

?>
