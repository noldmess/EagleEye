function Kamera() {
	this.load=function(image){
		$("#tool_righte .tool.Kamera .tool_items p").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/kamera.php')+'?image='+image, function(data) {
			if(data!=null){
				$("#tool_righte .tool.Kamera .tool_items").append('<p>'+data.make+':'+data.model+'</p>');
			}
		});
	};
	
}