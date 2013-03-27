function kamera() {
	this.load=function(image){
		if($('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Kamera .tool_items p").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/kamera.php')+'?image='+image, function(data) {
			if (data.status == 'success'){
				if (data.data!=null)
				$("#tool_righte .tool.Kamera .tool_items").append('<p>'+data.data.make+':'+data.data.model+'</p>');
			}
		});
	};
	
}


$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Kamera"><div class="tool_title"><div class="tool_ico"></div><h1>Kamera</h1></div><div class="tool_items"></div></div>');
});
