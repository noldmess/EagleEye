

function exif() {
	this.load=function(image){
		if(!$('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Exif .tool_items p").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/EXIF.php')+'?image='+image, function(data) {
			if(data!=null){
				$.each(data,function(index_tag,data){
					$("#tool_righte .tool.Exif .tool_items").append('<p>'+data.name+':'+data.tag+'</p>');
				});
			}
		});
	};
	
}




$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Exif"><div class="tool_title"><div class="tool_ico"></div><h1>Exif</h1></div><div class="tool_items"></div></div>');
});
