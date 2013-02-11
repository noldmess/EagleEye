function Exif() {
	this.load=function(image){
		$("#tool_righte .tool.Exif .tool_items p").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/EXIF.php')+'?image='+image, function(data) {
			$.each(data,function(index_tag,data){
				$("#tool_righte .tool.Exif .tool_items").append('<p>'+data.name+':'+data.tag+'</p>');
			});
		});
	};
	
}
