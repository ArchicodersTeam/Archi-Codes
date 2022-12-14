function fla_shortcode() {
    ob_start();
    ?>
            <div class="fla">
            <?php
    
            $args = array(  
                'orderby'   => 'term_id',
                'order'     => 'ASC',
                'hide_empty' => 'true',
            );
            $fla_categories = get_terms( 'web_portal_categories', $args );  
            $count = count($fla_categories);
    
            
            if ( $count > 0 ) {
                foreach ( $fla_categories as $fla_category ) {
					$termChild = get_term_children( $fla_category->term_id, 'web_portal_categories');

					?>
					<script>
						console.log(`array(0) {
					</script>
					
					<?php
                    // WP_Query arguments
                    $args = array(
                        'post_type'             => 'family_law_act',
                        'posts_per_page'        => '-1',
                        'order'                 => 'ASC',
                        'orderby'               => 'title',
						'post_parent' => 0,
                        'tax_query' => array(
                            'relation' => 'AND',  
                            array(
                                'taxonomy' => 'web_portal_categories',
                                'field' => 'slug',
                                'terms' => $fla_category->slug  
                            )
                        ),
                    );
                    
                    // The Query
                    $query = new WP_Query( $args );
	                    echo '<div id="'.$fla_category->slug.'" class="fla-category-item elementor-element elementor-element-'.get_the_ID().' elementor-widget elementor-widget-accordion" data-id="'.get_the_ID().'" data-element_type="widget" data-widget_type="accordion.default">'; 
	                    	echo '<div class="elementor-widget-container">';
	                    		echo '<div class="elementor-accordion" role="tablist">';
	                    			echo '<div class="elementor-accordion-item">';
										$numbering = get_field('category_numbering', 'web_portal_categories_' . $fla_category->term_id);
	                    				//Title
						                echo '<div id="elementor-tab-title-'.get_the_ID().'" class="elementor-tab-title" data-tab="'.get_the_ID().'" role="tab" aria-controls="elementor-tab-content-'.get_the_ID().'" aria-expanded="true" tabindex="0" aria-selected="true" data-numbering="'.$numbering.'">
						                    <a class="elementor-accordion-title" href="">'.$fla_category->name.'</a>
						                    <span class="elementor-accordion-icon elementor-accordion-icon-right" aria-hidden="true">
															<span class="elementor-accordion-icon-closed"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="9" viewBox="0 0 18 9"><path id="thin_down" data-name="thin down" d="M21.492,10.852a.766.766,0,0,1,1.025,0,.635.635,0,0,1,0,.948l-8.276,7.659a.766.766,0,0,1-1.025,0L4.941,11.8a.636.636,0,0,1,0-.948.766.766,0,0,1,1.025,0l7.764,6.985Z" transform="translate(-4.729 -10.655)" fill="#7f8a97"></path></svg></span>
								<span class="elementor-accordion-icon-opened"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="9" viewBox="0 0 18 9"><path id="thin_up" data-name="thin up" d="M21.492,19.458a.766.766,0,0,0,1.025,0,.635.635,0,0,0,0-.948l-8.276-7.659a.766.766,0,0,0-1.025,0L4.941,18.511a.636.636,0,0,0,0,.948.766.766,0,0,0,1.025,0l7.764-6.985Z" transform="translate(-4.729 -10.655)" fill="#7f8a97"></path></svg></span>
											</span>											
						                </div>';

				                        //echo '<h4><a href="' . get_term_link( $fla_category ) . '">' . $fla_category->name . '</a></h4>';

				                        //Content
				                        echo '<div id="elementor-tab-content-'.get_the_ID().'" class="fla-item-wrap elementor-tab-content elementor-clearfix elementor" data-tab="'.get_the_ID().'" role="tabpanel" aria-labelledby="elementor-tab-title-'.get_the_ID().'" style="display: none;">';
				                            // The Loop
				                            if ( $query->have_posts() ) {
				                                while ( $query->have_posts() ) {
				                                    $query->the_post(); 
				                                    ?>
													<div class="fla-item-link-parent">
														<a href="<?php get_field('linked_post') ? the_field('linked_post') : the_permalink(); ?>"><?php the_title(); ?></a>
					                                    <?php
					                                    $parent_id = get_the_ID();

											            $args_child = array(  
					                                    	'post_type' => 'family_law_act', 
					                                    	'post_parent' => $parent_id,
											                'orderby'   => 'title',
											                'order'     => 'ASC',
											                'hide_empty' => 'true',
											            );

					                                    $child_query = new WP_Query( $args_child );
											       		if ( $child_query->have_posts() ) :
															echo '<div class="fla-item-link-child">';
												       			while ( $child_query->have_posts() ) : $child_query->the_post(); ?>
												       				<div class="fla-item-link-child-item">
																		<a href="<?php get_field('linked_post') ? the_field('linked_post') : the_permalink(); ?>"><?php the_title(); ?></a>
																	</div>
												       			<?php endwhile;
											       			echo '</div>';
											       		endif; ?>
				                                    </div>
				                                    <?php
				                                }
				                            } else {
				                                //no post found
				                                echo 'No Result Found';
				                            }
				                        echo '</div>'; //fla-item-wrap
				                        // Restore original Post Data
				                        wp_reset_postdata();
			                        echo '</div>';
		                        echo '</div>';
	                        echo '</div>'; //elementor-widget-container
	                    echo '</div>'; //fla-category-item
                    }
                }
                ?>
            </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'fla', 'fla_shortcode' );