

function exif() {
	this.load=function(image){
		if(!$('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Exif .tool_items p").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/EXIF.php')+'?image='+image, function(data) {
			if (data.status == 'success'){
					$.each(data.data,function(index_tag,data){
						$("#tool_righte .tool.Exif .tool_items").append('<p>'+data.name+':'+data.tag+'</p>');
					});
			}
		});
	};
	
}




$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Exif"><div class="tool_title"><i class="icon-info-sign"></i>Exif</div><div class="tool_items"></div></div>');
});
