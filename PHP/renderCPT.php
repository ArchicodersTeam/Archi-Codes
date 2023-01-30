<?php

$args = array(
    'post_type' => 'customposttype',
    'posts_per_page' => -1
);

$query = new WP_Query($args);

if ( $query->have_posts() ) : 
  while ( $query->have_posts() ) : $query->the_post();
      // Your template code here
  endwhile; 
endif;

?>
