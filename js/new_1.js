$(document).ready(function() {
	PhotoView.load();
	FaceFinder.test();
	$('#tool_righte').hide();
	$('div.tool_title').click(function() {
		if ($(this).parent().children("div.tool_items").is(":visible")) {
			$(this).parent().children("div.tool_items").slideUp(500);
		} else {
			$(this).parent().children("div.tool_items").slideDown(500);
		}
	});

	$(document).keypress(function(e) {
		if (e.keyCode == 27) {
			// location.hash = "#/DSC_0010.JPG";//;
			$('#photoview').hide();
			$('#photoOverView').show();
			$('#search').show();
			location.href = "#" + $('#photo img').attr("name");
		} else {
			if (e.keyCode == 8) {
				$('#photoview').hide();
				$('#photoOverView').show();
				$('#search').show();
				location.href = "#" + $('#photo img').attr("name");
				e.preventDefault();
			}
		}
	});

});
