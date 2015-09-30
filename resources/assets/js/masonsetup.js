$(document).ready(function(){
	setTimeout(function(){
		// On Day Views, use Masonry
		$('.events .row').masonry({
			// options...
			itemSelector: '.col'
		});
		
		// On Homepage, use MatchHeight
		$(".event-cards.z-depth-2 .card").matchHeight({
	        byRow: true,
	        property: 'height'
	    });
	}, 1000);
});

