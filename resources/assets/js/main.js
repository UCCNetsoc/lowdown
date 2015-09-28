$( document ).ready(function($){
	$(".button-collapse").sideNav();
	$('.modal-trigger').leanModal();
	$('.tooltipped').tooltip({delay: 50});
	$('.parallax').parallax();

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
});

/*
Tracks changes made to checkboxes on subscription page
 */
function addToList( id ){

	var currentText = $('input[name=allSubscriptions]').val();
	if( currentText.search(new RegExp(" ?" + id + " ?")) >= 0 ){
		// If the number's already in the string, remove it
		
		$('input[name=allSubscriptions]').val(
			currentText.replace( new RegExp(" ?" + id + " ?"), " ")
		);
	} else {
		// If the checkbox has been clicked for the first time, add it to the input
		$('input[name=allSubscriptions]').val( currentText + " " + id );
	}
}