

function exif() {
	this.init=function(){
		$("#tool_righte").append('<div class="tool Exif"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>Exif</div><div class="tool_items"><table class="table"></table></div></div>');
		
	 	
	},
	this.hideView=function (event){

	};
	this.showView=function (event){

	};
	this.load=function(image){
		if(!$('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Exif .tool_items").addClass('load');
		$("#tool_righte .tool.Exif .tool_items table *").remove();
		$.getJSON(OC.linkTo('EagleEye', 'module/Exif/ajax/EXIF.php')+'?image='+image, function(data) {
			if (data.status == 'success'){
				if(data.data.length>0){
					$('#tool_righte .tool.Exif .tool_items table').append('<thead>'
							+'<tr>'
							+'	<th>Name</th>'
						    +'  <th>Value</th>'
						    +'</tr>'
						    +'</thead>'
						    +' <tbody></tbody>');
					$.each(data.data,function(index_tag,data){
						//.append('<tr><td><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'"></i>'+data.name+' </td><td>'+data.tag+extra+"<td></tr>");
						$("#tool_righte .tool.Exif .tool_items tbody").append('<tr><td>'+data.name+' </td><td>'+data.tag+'<td></tr>');
					});
				}else{
					$('#tool_righte .tool.Exif .tool_items table').append('<thead>'
							+'<tr>'
							+'	<th>NO Exif</th>'
						    +'</tr>'
						    +'</thead>'
						    +' <tbody></tbody>');
				}
			}
			$("#tool_righte .tool.Exif .tool_items").removeClass('load');
		});
	};
	this.resat=function(){
	};
	this.setEvents=function(){
	};
	this.duplicatits=function(element,data){
		var tag=data['img1']['Exif_ModuleMapper'][0];
		for ( var i = 0; i < $(tag).size(); i++) {
			if(i==0)
				$(element).append('<tr><td rowspan="'+$(tag).size()+'">EXIF</td><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-ok"></td><td>'+tag[i].name+' '+tag[i].tag+'</td></tr>');
			else
				$(element).append('<tr>><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-ok"></td><td>'+tag[i].name+' '+tag[i].tag+'</td></tr>');
		}
		var tag1=data['img1']['Exif_ModuleMapper'][1];
		var tag2=data['img2']['Exif_ModuleMapper'][1];
		if($(tag1).size()>$(tag2).size()){
			var tag=data['img1']['Exif_ModuleMapper'][1];
			var tag2=data['img2']['EXIF_ModuleMapper'][1];
		}else{
			var tag=data['img2']['Exif_ModuleMapper'][1];
			var tag2=data['img1']['Exif_ModuleMapper'][1];
		}
		for ( var i = 0; i < $(tag).size(); i++) {
			var name;
			var tag_name;
			if(tag2[i]===undefined){
				name='';
				tag_name='';
			}else{
				name=tag2[i].name;
				tag_name=tag2[i].tag;
			}
			if(i==0)
				$(element).append('<tr><td rowspan="'+$(tag).size()+'">Not equal EXIF</td><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+name+' '+tag_name+'</td></tr>');
			else
				$(element).append('<tr>><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+name+' '+tag_name+'</td></tr>');
		}
	};
	
}




