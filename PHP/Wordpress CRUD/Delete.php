<?php

function delete_hp_listing_post( $request ) {
    $id = $request->get_param( 'id' );
    $post_type = get_post_field( 'post_type', $id );
  
    if ( $post_type !== 'hp_listing' ) {
      return new WP_Error( 'invalid_post_type', 'Post not found or invalid post type', array( 'status' => 404 ) );
    }
  
    $post = get_post( $id );
    if ( !$post ) {
      return new WP_Error( 'invalid_post', 'Post not found', array( 'status' => 404 ) );
    }
  
    $result = wp_trash_post( $id );
  
    if ( $result === false ) {
      return new WP_Error( 'delete_error', 'Error deleting post', array( 'status' => 500 ) );
    }
  
    return rest_ensure_response( array( 'success' => true, 'title' => $post->post_title, 'description' => 'Moved to trash' ) );
  }
  
  
  function register_delete_hp_listing_endpoint() {
    register_rest_route( 'job_listings/v1', '/post_delete', array(
      'methods' => 'DELETE',
      'callback' => 'delete_hp_listing_post',
      'permission_callback' => function ( $request ) {
        return $request -> get_param( 'auth_token' ) === 'SECRET*****';
      }
    ) );
  }
  
  add_action( 'rest_api_init', 'register_delete_hp_listing_endpoint' );

?>