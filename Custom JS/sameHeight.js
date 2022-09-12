function customResize(_t){
	window.addEventListener('resize', handleHeightChanges)
	window.addEventListener('load', handleHeightChanges)

	function handleHeightChanges(){
			var highest = 0
			const targets = [...document.querySelectorAll(_t)]

			targets.forEach(e => e.removeAttribute('style'))
			targets.forEach(e => highest = highest < e.getBoundingClientRect().height ? e.getBoundingClientRect().height : highest)
			targets.forEach(e => e.style.height = highest + 'px')
	}
}


/* Add your selectors here */
['.your-selector-here', '.multiple-selector'].forEach(selector => customResize(selector))

