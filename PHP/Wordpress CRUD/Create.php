<?php

function create_hp_listing_post( $request ) {
    $data = json_decode( $request->get_body(), true );
  
    $new_post = array(
      'post_title'   => isset( $data['title'] ) ? $data['title'] : '',
      'post_content' => isset( $data['content'] ) ? $data['content'] : '',
      'post_status'  => isset( $data['status'] ) ? $data['status'] : 'publish',
      'post_type'    => 'hp_listing',
    );
  
    $result = wp_insert_post( $new_post );
  
    if ( $result === false ) {
      return new WP_Error( 'create_error', 'Error creating post', array( 'status' => 500 ) );
    }
  
    $new_post = get_post( $result );
  
    return rest_ensure_response( array(
      'success'      => true,
      'id'           => $new_post->ID,
      'title'        => $new_post->post_title,
      'content'      => $new_post->post_content,
      'status'       => $new_post->post_status,
      'last_updated' => $new_post->post_modified,
    ) );
  }
  
  function register_create_hp_listing_endpoint() {
    register_rest_route( 'job_listings/v1', '/post_create', array(
      'methods'             => 'POST',
      'callback'            => 'create_hp_listing_post',
      'permission_callback' => function ( $request ) {
        return $request->get_param( 'auth_token' ) === 'SECRET*****';
      }
    ) );
  }
  
  add_action( 'rest_api_init', 'register_create_hp_listing_endpoint' );
  
  ?>