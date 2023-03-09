function notNull($value){
	return $value !== null && $value !== '';
}

function setTermIfExist($category_name, $post_id){
	$category = get_term_by('name', $category_name, 'hp_listing_category');
	if ($category) {
	   $category_id = $category->term_id;
		wp_set_post_terms( $post_id, $category->term_id, 'hp_listing_category', true );
		
		return true;
	}
	
	return false;
}

function createTermIfNotExist($category_name){
	
	// Check if category exists
	$category_exists = term_exists($category_name, 'hp_listing_category');
	// Create category if it doesn't exist
	if (!$category_exists) {
		$category = array(
			'slug' => slugify($category_name),
			'parent' => 0
		);
		wp_insert_term($category_name, 'hp_listing_category', $category);
		
		return true;
	}
	
	return false;
}

function slugify($text){
  // replace non letter or digits with -
  $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

  // trim
  $text = trim($text, '-');

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // lowercase
  $text = strtolower($text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}