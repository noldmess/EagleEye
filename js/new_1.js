$(document).ready(function() {
	PhotoView.load();
	FaceFinder.test();
	$('#tool_righte').hide();
	$("span.right").append('<button class="back" style=""><i class="icon-th"></i> Back </button>');
	$('button.back').hide();
	$("button.back").click(function(e){
		goBack(e);
	})
	/*$('div.tool_title').click(function() {
		if ($(this).parent().children("div.tool_items").is(":visible")) {
			$(this).parent().children("div.tool_items").slideUp(500);
		} else {
			$(this).parent().children("div.tool_items").slideDown(500);
		}
	});*/
	$(document).keypress(function(e) {
		if (e.keyCode == 27) {
			goBack(e);
		} else {
			if (e.keyCode == 8) {
				goBack(e);
			}
		}
	});

});

function goBack(e){
	$('span.right button').show();
	$('span.right input').show();
	$('button.back').hide();
	$('#photoview').hide();
	$('#photoOverView').show();
	$('#search').show();
	location.href = "#" + $('#photo img').attr("name");
	e.preventDefault();
}
