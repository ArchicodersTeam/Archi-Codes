// Wait for the DOM to be fully loaded before executing the code
document.addEventListener('DOMContentLoaded', function () {
	// Find the element with the class 'grid-wrap'
	const gridWrap = document.querySelector('.grid-wrap');
	
	// If no element with the class 'grid-wrap' is found, return 0 and exit the function
	if (!gridWrap) return 0;
	
	// Create a new instance of Masonry with the 'gridWrap' element as the container
	// and provide configuration options
	const masonry = new Masonry(gridWrap, {
	  'itemSelector': '.grid-item', // Selector for the grid items
	  'gutter': 33 // Spacing between grid items in pixels
	});
  });
  