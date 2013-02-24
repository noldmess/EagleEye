$(document).ready(function() {

	$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {
		$.each(data,function(index_year,data){
			alert(data.img);
			$.each(data.array,function(index_year,data){
				alert(data.img_eq+"-"+data.value);
			});
		});
	});
});
