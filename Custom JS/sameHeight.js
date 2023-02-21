function customResize(_t){
	window.addEventListener('resize', handleHeightChanges)
	document.addEventListener('DOMContentLoaded', handleHeightChanges)

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


(() => {
	
	/* Add your selectors here */
	['#flip-box-wrap .elementor-flip-box__layer__description'].forEach(selector => customResize(selector))
})()
