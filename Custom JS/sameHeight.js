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
['.auto-height-1.c-cta .elementor-cta__content',
 '.auto-height-2',
 '.auto-height-1'].forEach(selector => customResize(selector))
