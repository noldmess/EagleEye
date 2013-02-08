function Exif() {
	this.load=function(image){
		$("#tool_righte .tool.Exif .tool_items p").remove();
		//$("#tool_righte").append('<div class="tool"></div>');
		//$("#tool_righte .tool").append('<div class="tool_title"><div class="tool_ico"></div><h1>sdfdsfsfdsdf A</h1></div>');
		//<div class="tool_title"><div class="tool_ico"></div><h1>sdfdsfsfdsdf A</h1></div><div class="tool_items"></div></div>');
		$.getJSON(OC.linkTo('facefinder', 'ajax/EXIF.php')+'?image='+image, function(data) {
			$.each(data,function(index_tag,data){
				$("#tool_righte .tool.Exif .tool_items").append('<p>'+data.name+':'+data.tag+'</p>');
			});
		});
		//$("#tool_righte .toolsExif .tool_items")
	};
	
}
$(document).ready(function() {

});
	