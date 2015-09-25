
$(document).ready(function(){
	setTimeout(function(){
		$('.events .row').masonry({
		  // options...
		  itemSelector: '.col'
		});
	}, 1000);
});