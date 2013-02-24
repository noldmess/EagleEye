$(document).ready(function() {

$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {
			   	    alert(data);
		        });
});
