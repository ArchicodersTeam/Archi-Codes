<?php

add_action( 'elementor/widget/posts/skins_init', function( $widget ) {
  
    class issue6480_skin extends \ElementorPro\Modules\Posts\Skins\Skin_Cards {
	    protected function render_badge() {
		    $taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		    if ( empty( $taxonomy ) ) {
			    return;
		    }
		    $terms = get_the_terms( get_the_ID(), $taxonomy );
		    if ( ! is_array( $terms ) ) {
			    return;
		    }
		    ?>
			<div class="elementor-post__badges">
				<div class="elementor-post__badge">
				<?php
				foreach( $terms as $key=>$term ) : ?>
					<?php 
						echo $term->name; 
						echo count($terms) > $key + 1 ? ',' : '';
					?>
				<?php endforeach; ?>
			</div>
            </div>
            <?php
	    }

	    public function get_id() {
		    return 'cards_multi_badge';
	    }

	    public function get_title() {
		    return __( 'Cards Multi Badge', 'elementor-pro' );
	    }
    }

	// register the skin to the posts widget
	$widget->add_skin( new issue6480_skin( $widget ) );
} );

?>