$( document ).ready(function($){
	$(".button-collapse").sideNav();
	$('.modal-trigger').leanModal();
	$('.tooltipped').tooltip({delay: 50});
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