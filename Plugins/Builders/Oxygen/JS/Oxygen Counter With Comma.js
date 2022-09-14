  var url_string = window.location.href;
  var url = new URL(url_string);
  var param = url.searchParams.get("ct_builder");
  var delay = 1000;
function startCounting( target ) {
  
  	var number = jQuery(target);
  
    if( !param && !number.data('counter-started') ) {
    
    var counterStart = 0;
    var counterEnd = number.html();
    var counterSpeed = 25;
    var currentCount = counterStart;
    var hasDecimal = counterEnd.indexOf(".") >= 0;
    number.text('0');
    number.attr('data-counter-started', 'true');
    setTimeout(function(){
    
      var numberCounterInterval = setInterval( function() {
      if( currentCount < counterEnd ) {
        currentCount += counterEnd / 100;
        number.text(Math.floor(currentCount).toLocaleString());
        if(hasDecimal) number.text(currentCount.toFixed(1).toLocaleString());
      } else { 
        number.text(Number(counterEnd).toLocaleString());
        clearInterval(numberCounterInterval); 
      }
      
    }, counterSpeed);
      
    }, delay += 250)
    
    
    }
}

/** Intersection Observer for Triggering On Visible **/
// First we grab all Number Counter numbers by the selector .oxel_number_counter__number.
var numberCounters = document.querySelectorAll('.counter-box-ctr');

// Now we define the options of our intersection observer.
// Root should remain null, this means the "window" is the viewport.
// Threshold is the percentage of the section that should be in the viewport before
// the intersection observer fires.
// Root margin is a margin based offset, we won't use it here.
var numberCounterOptions = { 
      root: null,
      threshold: 0,
      rootMargin: "0px"
};

// Now we create our new intersection observer.
var numberCounterObserver = new IntersectionObserver( 
  // Callback function.
  function(entries, observer) { 

    // For each entry, we'll run our code.
      entries.forEach( entry => {
      
      // Boolean to tell us if target is intersecting or not.
      var targetIsIntersecting = entry.isIntersecting; 

      // If it is intersecting, start counting.
      if( targetIsIntersecting ) {

      	startCounting( entry.target );

      } else if( !targetIsIntersecting ) {

        //If it's not intersecting, this code will run.

      }

    });
    
  }
  // Pass in our options variable.
  , numberCounterOptions );

// Finally, we tell the observer to go ahead and observe all of our numbers.
numberCounters.forEach( section => {
     numberCounterObserver.observe(section); 
})