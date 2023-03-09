<?php

    function get_hp_listing_posts( $request ) {
      $posts_per_page = $request->get_param( 'posts_per_page' ) !== null && $request->get_param( 'posts_per_page' ) !== '' ? $request->get_param( 'posts_per_page' ) : 10;
      $page = $request->get_param( 'page' ) !== null && $request->get_param( 'page' ) !== '' ? $request->get_param( 'page' ) : 1;

      $offset = ( $page - 1 ) * $posts_per_page;

      $args = array(
        'post_type' => 'hp_listing',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset
      );

      $query = new WP_Query( $args );
      $posts = $query->get_posts();

      if ( empty( $posts ) ) {
        return new WP_Error( 'empty_posts', 'No posts found', array( 'status' => 404 ) );
      }

      $total_posts = $query->found_posts;
      $total_pages = ceil( $total_posts / $posts_per_page );

      $data = array(
        'total_posts' => $total_posts,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'posts' => array()
      );


      foreach ( $posts as $post ) {
        $post_terms = wp_get_post_terms( $post->ID, 'hp_listing_category' ); // replace 'hp_listing_category' with the actual taxonomy name
        $terms_array = array();
        foreach ( $post_terms as $term ) {
          $terms_array[] = array(
            'term_id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug
          );
        }
        $data['posts'][] = array(
          'id' => $post->ID,
          'title' => $post->post_title,
          'content' => $post->post_content,
          'excerpt' => $post->post_excerpt,
          'permalink' => get_permalink( $post->ID ),
          'terms' => $terms_array
        );
      }

      return rest_ensure_response( $data );
    }


    function register_hp_listing_endpoint() {
        register_rest_route( 'job_listings/v1', '/posts', array(
            'methods' => 'GET',
            'callback' => 'get_hp_listing_posts'
        ));
    }

    add_action( 'rest_api_init', 'register_hp_listing_endpoint' );


?>
