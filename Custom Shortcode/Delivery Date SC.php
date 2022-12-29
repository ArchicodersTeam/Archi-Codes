//This shortcode will get the closest delivery date of the customer

<?php 
    // Create the shortcode function
    function my_custom_shortcode() {
      // Get the current user's ID
      $customer_id = get_current_user_id();
    
      // Set up the query arguments
      $args = array(
          'customer_id' => $customer_id,
          'limit' => -1,
          'status' => 'processing'
      );
    
      // Create a new query
      $orders = new WC_Order_Query( $args );
    
      // Get the orders
      $customer_orders = $orders->get_orders();
    
      // Loop through the orders
      $dates = [];
      foreach ( $customer_orders as $customer_order ) {
          // Access order data
          $order_id = $customer_order->get_id();
          $order_date = $customer_order->get_date_created();
          $order_total = $customer_order->get_total();
          $order_status = $customer_order->get_status();
          // Do something with the data
          $data = get_post_meta( $order_id );
          $r = get_option( 'orddd_lite_delivery_date_field_label' );
          
          if($data['Delivery Date'][0] != null) {
             $dates[] = $data['Delivery Date'][0];
          }
          
          
      }
      
        if(count($dates) > 0) {
            usort($dates, function($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            
            echo $dates[0];
        } else  {
            echo 'No Delivery';
        }

    }
    
    // Register the shortcode
    add_shortcode( 'my_custom_shortcode', 'my_custom_shortcode' );
