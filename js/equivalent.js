$(document).ready(function() {

	$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {
		$.each(data,function(index_year,data){
			$("equivalent_test").append('<div class="year"></div>');
		});
	});
});
