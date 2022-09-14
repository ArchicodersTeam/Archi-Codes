function customResize(_t){
	window.addEventListener('resize', handleHeightChanges)
	window.addEventListener('load', handleHeightChanges)

	function handleHeightChanges(){
			var highest = 0
			const targets = [...document.querySelectorAll(_t)]

			targets.forEach(e => {
				e.removeAttribute('style')
				highest = highest < e.getBoundingClientRect().height ? e.getBoundingClientRect().height : highest
			})
			targets.forEach(e => e.style.height = highest + 'px')
	}
}


/* Add your selectors here */
['.multiple-selector-1', '.multiple-selector-2'].forEach(selector => customResize(selector))
