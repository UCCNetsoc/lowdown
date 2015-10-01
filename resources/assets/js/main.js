$( document ).ready(function($){

	// Materialize Setup
	$(".button-collapse").sideNav();
	$('.modal-trigger').leanModal();
	$('.tooltipped').tooltip({delay: 50});
	$('.parallax').parallax();

	// Smooth Scrolling
    $(document.body).on('click', 'a[href*=#]:not([href=#])', function (e) {
        e.preventDefault();
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                var offset = 0;
                if (target[0].id.indexOf("feature-") == 0) {
                    offset = 100;
                }
                $('html,body').animate({
                    scrollTop: target.offset().top - offset
                }, 1000);
                return false;
            }
        }
    });

    // Webcal Link Rewrite
    if( $(".calendar-button").length ){

    	$(".calendar-button").each(function(){
	    	var link = $(this).attr('href');

			var link = link.replace(window.location.protocol + "//", "webcal://");
			
			if(navigator.platform.toUpperCase().indexOf('LINUX')>=0 || navigator.platform.toUpperCase().indexOf('WIN')>=0){
				link = "https://www.google.com/calendar/render?cid=" + link;
			}

			$(this).attr('href', link);
    	});
    }
});

/*
Tracks changes made to checkboxes on subscription page
 */
function updateList(){

	var societies = [];

	$(':checked').each(function(){
		societies.push( $(this).attr('id') );
	});

 	var json = JSON.stringify(societies);

	$('input[name=allSubscriptions]').val( json );
}