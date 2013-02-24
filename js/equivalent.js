$(document).ready(function() {

	$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {
		$.each(data,function(index_year,data){
			alert(data.img);
			$("equivalent_test").append('<div class="year"></div>');
		});
	});
});
