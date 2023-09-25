// We listen every time a grid/template/content is initialized.
window.WP_Grid_Builder && WP_Grid_Builder.on( 'init', function( wpgb ) {
	var once = true
	wpgb.facets.on( 'render', function( element, facet ) {
		if(wpgb.facets.hasFacet( 'category' )) {
			const url = new URL(window.location.href)
			const params = new URLSearchParams(url.search)
			const category = params.get('wpgb-category')
			
			if(category && once) {
				wpgb.facets.setParams( 'category' , [category])
				wpgb.facets.refresh()
				once = false
			}
		}
	});
});
