$(document).ready(function() {
	PhotoView.load();
	FaceFinder.test();

	$('div.tool_title').click(function(){
		if( $(this).parent().children("div.tool_items").is(":visible")){
			$(this).parent().children("div.tool_items").slideUp(500);
		}else{
			$(this).parent().children("div.tool_items").slideDown(500);
		}
	});

	$(document).keypress(function(e) {
	   if ( e.keyCode== 27){
	      $('#photoview').hide();
	      $('#new_1').show();
	   }
	});
	

	});








	