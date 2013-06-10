

function exif() {
	this.load=function(image){
		if(!$('#tool_righte').is(":visible")){
			$('#tool_righte').show();
		}
		$("#tool_righte .tool.Exif .tool_items").addClass('load');
		$("#tool_righte .tool.Exif .tool_items table *").remove();
		$.getJSON(OC.linkTo('facefinder', 'ajax/EXIF.php')+'?image='+image, function(data) {
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
		/*var sdfsdf=data['img1']['ff'];
		$(element).append('<tr><td>Image</td><td><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img2']['ff']['path']+'"></td><td>1</td><td><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img1']['ff']['path']+'"></td></tr>');
		$(element).append('<tr><td>Path</td><td>'+data['img2']['ff']['path']+'</td><td>1</td><td>'+data['img1']['ff']['path']+'</td></tr>');
		$(element).append('<tr><td>Date</td><td>'+data['img2']['ff']['date_photo']+'</td><td>1</td><td>'+data['img1']['ff']['date_photo']+'</td></tr>');
		$(element).append('<tr><td>Path</td><td>'+data['img2']['ff']['path']+'</td><td>1</td><td>'+data['img1']['ff']['path']+'</td></tr>');
		$(element).append('<tr><td>Width</td><td>'+data['img2']['ff']['width']+'</td><td>1</td><td>'+data['img1']['ff']['width']+'</td></tr>');
		$(element).append('<tr><td>Height</td><td>'+data['img2']['ff']['height']+'</td><td>1</td><td>'+data['img1']['ff']['height']+'</td></tr>');
		$(element).append('<tr><td>Size</td><td>'+FaceFinder.getReadableFileSizeString(data['img2']['ff']['filesize'])+'</td><td>1</td><td>'+FaceFinder.getReadableFileSizeString(data['img1']['ff']['filesize'])+'</td></tr>');*/
	};
	
}




$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Exif"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>Exif</div><div class="tool_items"><table class="table"></table></div></div>');
});
