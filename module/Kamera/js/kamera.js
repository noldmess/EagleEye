$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Camera"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>Camera</div><div class="tool_items"><table class="table"></table></div>');
});

function kamera() {
	this.init=function(){

	},
	this.hideView=function (event){

	};
	this.showView=function (event){

	};
	this.load=function(image){
		if($('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Camera .tool_items table *").remove();
		$.getJSON(OC.linkTo('EagleEye', '/module/Kamera/ajax/kamera.php')+'?image='+image, function(data) {
			if (data.status == 'success'){
				if (data.data!=null){
				$('#tool_righte .tool.Camera .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>Mark</th>'
					    +'  <th>Model</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
				$("#tool_righte .tool.Camera .tool_items tbody").append('<tr><td>'+data.data.model+'</td><td>'+data.data.make+'</td></tr>');
			}else{
				$('#tool_righte .tool.Camera .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>NO Camera Data</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
			
			}
			}
		});
		
	};
	this.resat=function(){
	};
	this.setEvents=function(){
	};
	this.duplicatits=function(element,data){
		var camera1=data['img1']['Kamera_ModuleMapper'][0];
		var camera2=data['img2']['Kamera_ModuleMapper'][0];
		if(camera2.length!=0 && camera1.length!=0){
			if(camera1.make === camera2.make && camera1.model===camera2.model){
				$(element).append('<tr><td>Camera</td><td>'+camera1.make+' '+camera1.model+'</td><td><i class="icon-equal icon-equal-ok"></td><td>'+camera2.make+' '+camera2.model+'</td></tr>');
			}else{
				$(element).appends('<tr><td>Camera</td><td>'+camera1.make+' '+camera1.model+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+camera2.make+' '+camera2.model+'</td></tr>');
			}
		}
	};
	
}

