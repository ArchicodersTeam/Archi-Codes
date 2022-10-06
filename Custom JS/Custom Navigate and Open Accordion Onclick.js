<script>
//Close accordion by default
	jQuery(document).ready(function($) { 
	var delay = 100; setTimeout(function() { 
	$('.elementor-tab-title').removeClass('elementor-active');
	 $('.elementor-tab-content').css('display', 'none'); }, delay); 
	});
	
//Click and Scroll Function of Link
	window.addEventListener('load', function(){
    const accordion = [...document.querySelectorAll('#post-acrdn .elementor-accordion-title')]
    const nav = [...document.querySelectorAll('#acrdn-nav .elementor-icon-list-text')]
    
    accordion.forEach((t,i) => {
        nav[i].onclick = () => {
            t.click()
					setTimeout(() => t.scrollIntoView({'behavior': 'smooth'}) , 400)
            
        }
    })
})
</script>