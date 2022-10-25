<?php	
add_shortcode('getStoreTime', function($att){
	ob_start();
	$acfData = get_field('store_schedule', $att->id);
  //store_schedule = repeater field
	
	echo '<div>';
	foreach($acfData as $val) {
		?>
		<div>
			<?= $val['day'] ?>: <?= $val['store_open'] ?>-<?= $val['store_close'] ?> <!--day,store_open,store_close = sub field -->
		</div>
		<?php		
	}
	
	echo '</div>';
	
	return ob_get_clean();
});