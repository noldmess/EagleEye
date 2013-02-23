$(document).ready(function() {
	alert("dsfsdfsfsd");
$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {
			   	    alert(data);
		        });
});
