<?php

function update_hp_listing_post( $request ) {
    $id = $request->get_param( 'id' );
    $post_type = get_post_field( 'post_type', $id );
  
    if ( $post_type !== 'hp_listing' ) {
      return new WP_Error( 'invalid_post_type', 'Post not found or invalid post type', array( 'status' => 404 ) );
    }
  
    $post = get_post( $id );
    if ( !$post ) {
      return new WP_Error( 'invalid_post', 'Post not found', array( 'status' => 404 ) );
    }
  
    $data = json_decode( $request->get_body(), true );
  
    $updated_post = array(
      'ID'           => $id,
      'post_title'   => isset( $data['title'] ) ? $data['title'] : $post->post_title,
      'post_content' => isset( $data['content'] ) ? $data['content'] : $post->post_content,
      'post_status'  => isset( $data['status'] ) ? $data['status'] : $post->post_status,
    );
  
    $result = wp_update_post( $updated_post );
  
    if ( $result === false ) {
      return new WP_Error( 'update_error', 'Error updating post', array( 'status' => 500 ) );
    }
  
    $updated_post = get_post( $id );
  
    return rest_ensure_response( array(
      'success'      => true,
      'id'           => $updated_post->ID,
      'title'        => $updated_post->post_title,
      'content'      => $updated_post->post_content,
      'status'       => $updated_post->post_status,
      'last_updated' => $updated_post->post_modified,
    ) );
  }
  
  function register_update_hp_listing_endpoint() {
    register_rest_route( 'job_listings/v1', '/post_update', array(
      'methods'             => 'PUT',
      'callback'            => 'update_hp_listing_post',
      'permission_callback' => function ( $request ) {
        return $request -> get_param( 'auth_token' ) === 'SECRET*****';
      }
    ) );
  }
  
  add_action( 'rest_api_init', 'register_update_hp_listing_endpoint' );
  

?>