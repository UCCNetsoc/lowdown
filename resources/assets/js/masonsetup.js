$(document).ready(function(){
	// On Day Views, use Masonry
	setTimeout(function(){
		$('.events .row').masonry({
			// options...
			itemSelector: '.col'
		});
	}, 1000);

	// On Homepage, use MatchHeight
	
	$(".event-cards.z-depth-2 .card").matchHeight({
        byRow: true,
        property: 'height'
    });
});

