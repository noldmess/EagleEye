/*$(document).ready(function() {
	tag.init();
});*/



function tag(){	
		this.load=function(image){
			//remove old tags from view
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			//load tags from the image
			tag.getTag(image);
			$('#photo img').click(function(e){
				tag.maketag(e);
				});
			$('#img_img').hover(function(){
		        // Hover over code
				if($('#photo div.draggable').size()===0){
					$('#img_img').css("cursor"," cell");
			        var title = $(this).attr('alt');
			        $(this).data('tipText', title).removeAttr('title');
			        $('<p class="tooltip"></p>')
			        .text("click to write tag")
			        .appendTo('body')
			        .fadeIn('fast');
				}else{
					$('#img_img').css("cursor","auto");
				}
			}, function() {
			        // Hover out code
			        $(this).attr('title', $(this).data('tipText'));
			        $('.tooltip').remove();
			}).mousemove(function(e) {
			        var mousex = e.pageX + 10; //Get X coordinates
			        var mousey = e.pageY + 10; //Get Y coordinates
			        $('.tooltip')
			        .css({ top: mousey, left: mousex })
			});
			
		};
		this.hideView=function (event){
			$("#tool_righte .tool.Tag .tool_items table *").remove();
			$("#tool_righte .tool.Key .tool_items table *").remove();
		};
		this.showView=function (event){
			$('#photoff').show();
 			$('span.right select').show();
 			$('span.right label').show();
 			$('span.right input').show();
		};
		this.resat=function(){
			$("#moduleFildsinner input[name='query']").text("Set Tag (0)");
			var test=$("#moduleFildsinner input");
			var test=$("#moduleFildsinner input[name='counterTag']");
			$("#moduleFildsinner input[name='counterTag']").attr("value","0");
		};
		this.setEvents=function(){
			 $('#photoOverView input[type="checkbox"]').click(function(event){
					tag.checkboxevent(event,this);
			});
		};
		this.duplicatits=function(element,data){
			var tag=data['img1']['Tag_Module'][0];
			for ( var i = 0; i < $(tag).size(); i++) {
				if(i==0)
					$(element).append('<tr><td rowspan="'+$(tag).size()+'">Tag</td><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-ok"></td><td>'+tag[i].name+' '+tag[i].tag+'</td></tr>');
				else
					$(element).append('<tr>><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-ok"></td><td>'+tag[i].name+' '+tag[i].tag+'</td></tr>');
			}
			var tag1=data['img1']['Tag_Module'][1];
			var tag2=data['img2']['Tag_Module'][1];
			if($(tag1).size()>$(tag2).size()){
				var tag=data['img1']['Tag_Module'][1];
				var tag2=data['img2']['Tag_Module'][1];
			}else{
				var tag=data['img2']['Tag_Module'][1];
				var tag2=data['img1']['Tag_Module'][1];
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
				if(i==0){
					$(element).append('<tr><td rowspan="'+$(tag).size()+'">Not equal Tag</td><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+name+' '+tag_name+'</td></tr>');
				}else{
					$(element).append('<tr><td>'+tag[i].name+' '+tag[i].tag+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+name+' '+tag_name+'</td></tr>');
				}
			}
			//alert("sdfsdf");
		};
		
		this.init=function(){
			//Tool Box Tag
			$("#tool_righte").append('<div class="tool Tag"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>IPTC</div><div class="tool_items">'
			+'<table class="table"></table></div></div>');
			//evants for add tag 
			$("#tool_righte div.tool.Key input[type='submit']").click(function(e){
				tag.key();
			});
			//evants for add tag
			$("#tool_righte div.tool.Key input[type='text']").keyup(function(e){
				if ( e.keyCode== 13){
					tag.key();
				}
			});
			//Tool Box Tag
			
			//Tool Box Key 
			$("#tool_righte").append('<div class="tool Key"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>Tags</div><div class="tool_items fix"><input   type="text"  value="" name="query"  placeholder="Write Tag"></input><input type="submit" value=" Set Tag "></input><p><input   type="checkbox"  value="" name="query" ><label for="male"> All tags Visible</label></input></p></div><div class="tool_items">'
					+'<table class="table"></table></div></div>');
			//Tool Box Key 
			
			//Add menu
			$('span.right select[title="Select view"]').append('<option value="tag">Sort by Tag</option>');
			//Event for menu
			$('option[value="tag"]').click(function(e){
				$('button.remove').hide();
				window.history.pushState({path:"tag"},"","#tag");
			});
			//Add menu
			
			//Add form to add Tags in Photo Over view#
			$("#moduleFildsinner").append('<div id="tag"><input   type="text"  value="" name="query"  placeholder="add Tag"></input><button class="submitTag">add Tag</button><input type="hidden" value="0" name="counterTag"></input></div>');
			//event 
			$("button.submitTag").click(function(e){
				tag.checkbox();
			});
			//Add form to add Tags in Photo Over view#
			
			
			

			
			$("#module input[name='query']").keyup(function(e){
				if ( e.keyCode== 13){
					tag.checkbox();
				}
			});
			
			$("#tool_righte .tool.Key .tool_items input[type='checkbox']").click(function(e){
				if($("#photo div.tag_in_photo").is(':visible')){
					$("#photo div.tag_in_photo").hide();
				}else{
					$("#photo div.tag_in_photo").show();
				}
			});
		},
		//cange to tag Photo Over View
		this.click=function(e){
			
			$('#duplicate').hide();
			$('#photoff').show();
			var path=tag.getPath();
			 $.getJSON(OC.linkTo('facefinder', 'ajax/allImagesTags.php')+'?dir='+path, function(data) {
				 $('#photoOverView').addClass('loading');
				 $('#photoOverView * ').remove();
				 $('#tool_right ul li').remove();
				 if (data!==null && data.status == 'success'){
							tag.sidebar(data.tag);
							tag.photoOverView(data.photo);	
				     }
				 $('#photoOverView').removeClass('loading');
				 
			});
			 Module.resateView();
			 setTimeout(function() {$('span.right select[title="Select view"]').val('tag')},1000);
		}
};




tag.maketag=function(e){
	if($('#photo div.draggable').size()===0){
	  var PosX = 0;
	  var PosY = 0;
	  var posX = 0;
	  var posY = 0;
	  Eltern = document.getElementById("img_img")
	  while (Eltern) {
		  posX += Eltern.offsetLeft;
	      posY += Eltern.offsetTop;
		  Eltern = Eltern.offsetParent;
		}
	  if (e.clientX || e.clientY)
	    {
	      PosX = e.clientX + document.body.scrollLeft
	        + document.documentElement.scrollLeft;
	      PosY = e.clientY + document.body.scrollTop
	        + document.documentElement.scrollTop;
	    }
	 PosX = PosX - posX;
	 PosY = PosY - posY;
	 PosX+=document.getElementById("img_img").offsetLeft;
	 PosY+=document.getElementById("img_img").offsetTop;//
	 
	 $("#photo").append('<div class="draggable" style="position: absolute; top: '+(PosY-50)+'px; left: '+(PosX-50)+'px;"><a id="fancybox-close" style="display: inline;"></a><div class="draggable2"></div><div class="addTag"><input   type="text"  value="" name="query" placeholder="add Tag" ></input><input type="button" value=" Set Tag "></input></div></div>');
//	 $("#photo").append('<div class="draggable" style="position: absolute; top: '+(PosY-50)+'px; left: '+(PosX-50)+'px;"><input   type="text"  value="" name="query" placeholder="add Tag" ></input></div>');
	 $('#photo .addTag  input[type="button"]').click(function(e){
		 var pos=findPosition(this.parentNode.parentNode);
		 var image=$('#photoview img').attr("alt");
		 var tag_name=$(this).parent().find('input[type="text"]').val();
		 var x1=(pos[0]/document.getElementById("img_img").offsetWidth);
 		 var y1=(pos[1]/document.getElementById("img_img").offsetHeight);
 		 var sdfgsdf=$(this).parent().parent().find(".draggable2");
 		 var x2=($(this).parent().parent().find(".draggable2").width()/document.getElementById("img_img").offsetWidth);
 		 var y2=($(this).parent().parent().find(".draggable2").height()/document.getElementById("img_img").offsetHeight);
 		$("#tool_righte .tool.Tag .tool_items tbody").remove();
		$("#tool_righte .tool.Tag .tool_items thead").remove();
		$("#tool_righte .tool.Key .tool_items tbody").remove();
		$("#tool_righte .tool.Key .tool_items thead").remove();
		$("#photo div").remove(".draggable");
			$.getJSON(OC.linkTo('facefinder', 'ajax/inserttagposition.php')+"?image="+image+"&tag="+tag_name+"&x1="+x1+"&x2="+x2+"&y1="+y1+"&y2="+y2, function(data) {
				var image=$('#photoview img').attr("alt");
				tag.getTag(image);
			});
		$(this).parent().remove(".draggable_fix");
		
		});

	 $("#photo .addTag  input").keyup(function(e){
		 if ( e.keyCode== 13){
			 var pos=findPosition(this.parentNode.parentNode);
			 var image=$('#photoview img').attr("alt");
			 var tag_name=$(this).val();
			 var sdfdsf=$(this).parent();
			 var x1=(pos[0]/document.getElementById("img_img").offsetWidth);
	 		 var y1=(pos[1]/document.getElementById("img_img").offsetHeight);
	 		 var x2=($(this).parent().parent().width()/document.getElementById("img_img").offsetWidth);
	 		 var y2=($(this).parent().parent().height()/document.getElementById("img_img").offsetHeight);
	 		$("#tool_righte .tool.Tag .tool_items tbody").remove();
			$("#tool_righte .tool.Tag .tool_items thead").remove();
			$("#tool_righte .tool.Key .tool_items tbody").remove();
			$("#tool_righte .tool.Key .tool_items thead").remove();
			$("#photo div").remove(".draggable");
				tag.getTag(image);
			$(this).parent().remove(".draggable_fix");
			
		 }
		});
	 
	 $("#fancybox-close").click(function(){
			$(this).parent().remove();
		 });
	
	 $('#photo .draggable').draggable({
		    cursor: 'move',
		    containment: '#img_img'
		  } );
  $('#photo .draggable2').resizable({
			  minHeight:100,
			  minWidth:100,
			  containment: '#img_img'
			
		  });
//	 $('#photo .draggable').draggable({
//		    cursor: 'move',
//		    containment: '#img_img'
//		  } ).resizable();
	}
	
}


tag.getTag=function(img){
	$('#tool_righte .tool.Key .tool_items.fix input[type="checkbox"]').attr('checked', false);
	$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+img, function(data) {
		$('#tool_righte .tool.Tag .tool_items.fix input[type="checkbox"]').attr('checked', false);
		var key_count=0;
		var tag_count=0;
		if (data.status == 'success'){

			if(data.data.length>0){
				$('#tool_righte .tool.Tag .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>Name</th>'
					    +'  <th>Value</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
				$('#tool_righte .tool.Key .tool_items table').append('<thead>'
						+'<tr>'
					    +'  <th>Value</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
				$.each(data.data,function(index_tag,data){
					var extra="";
					if(data.x1>0 || data.x2>0 || data.y1>0 || data.y2>0){
						 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
				 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
				 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth);
				 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight);
						 var y1=(document.getElementById("img_img").offsetTop+y);
						 var x1=(document.getElementById("img_img").offsetLeft+x);
						$("#photo").append('<div id="'+data.name+' '+data.tag+'" class="tag_in_photo"style="position: absolute; top: '+y1+'px; left: '+x1+'px;"><div class="draggable_fix" style="width: '+x2+'px; height:'+y2+'px;"></div><div class="draggable_tag"><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'"></i>'+data.tag+'</div></div>');
						var dsfsdf=$('div.draggable_tag i');
						$('div.draggable_tag i').click(function(){
							var tag_name=$(this).attr("name");
							$('#tool_righte .tool.Key .tool_items tbody i.icon-remove-sign[name="'+tag_name+'"]').parent().parent().remove();
							tag.removeTag(this);
							$(this).parent().parent().remove();
							
							
						});
						$('#photo div.tag_in_photo td i').click(function(){
							tag.removeTag(this);
						});
						//extra='<i class="icon-search"></i>';
					}
					
					if(data.name=='KEYWORDS'){
						key_count++;
						$('#tool_righte .tool.Key .tool_items tbody').append('<tr><td><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'"></i>'+data.tag+extra+"<td></tr>");
					}else{
						tag_count++;
						$('#tool_righte .tool.Tag .tool_items tbody').append('<tr><td>'+data.name+' </td><td>'+data.tag+extra+"<td></tr>");
					}
						
				});
			}
				if(tag_count===0){
					$('#tool_righte .tool.Tag .tool_items table').append('<thead>'
							+'<tr>'
							+'	<th>No Tags </th>'
						    +'</tr>'
						    +'</thead>'
						    +' <tbody></tbody>');
				}
				
				if(key_count===0){
					$('#tool_righte .tool.Key .tool_items table').append('<thead>'
							+'<tr>'
							+'	<th>No Tags </th>'
						    +'</tr>'
						    +'</thead>'
						    +' <tbody></tbody>');
				}
			
			$("#photo div.tag_in_photo").hide();
			$('#tool_righte .tool.Key .tool_items tbody tr i.icon-remove-sign').click(function(){
				tag.removeTag(this);
			});
			$('#tool_righte .tool.Key .tool_items tbody tr').mouseenter(function(){
				var test=$('#tool_righte .tool.Key .tool_items.fix input[type="checkbox"]').attr('checked');
				if(test===undefined){
					var tag=$(this).children("td").children("i").attr("name");
					$("div[id='"+tag+"']").show();
				}
			}).mouseleave(function(){
				var test=$('#tool_righte .tool.Key .tool_items.fix input[type="checkbox"]').attr('checked');
				if(test===undefined){
					var tag=$(this).children("td").children("i").attr("name");
					$("div[id^='"+tag+"']").hide();
				}
			});
	}
		
	});
};

tag.removeTag=function(tagDiv){
	var image=$('#photo img').attr("alt");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().parent().remove();
	 if($("div[id='"+tag+"']")){
		 $("div[id^='"+tag+"']").remove();
	 }
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
};



tag.removeTagDiv=function(tagDiv){
	var image=$('#photo img').attr("alt");
	var imagepaht=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 var sdfsdf=$(tagDiv).parent().parent().parent();
	 $(tagDiv).parent().parent().parent().remove(".tag_in_photo");
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag+"&imagepaht="+imagepaht, function(data) {
		 tag.getTag();
	 });
};





tag.getPath=function(){
	var list=$('.crumb');
	var path;
	$.each(list,function(index_tag,elemet){
		path=elemet.title;
	});
	return path
}

//load tags for Photo Over View
tag.loadImagesTag=function(tagelement){
	var path=tag.getPath();
	 $('#tool_right *').removeClass('active');
	 var dsdf=$('#tool_right ul li[id="'+tagelement.id+'"]');
	 $('#tool_right ul li[id="'+tagelement.id+'"]').addClass('active');
	 $.getJSON(OC.linkTo('facefinder', 'ajax/allImagesTags.php')+'?dir='+path+'&tag='+tagelement.id, function(data) {
		 $('#photoOverView').addClass('loading');
		 $('#photoOverView * ').remove();
		 if (data!==null && data.status == 'success'){
			 	tag.photoOverView(data.photo);	
	     }
		 $('#photoOverView').removeClass('loading');
	 });
}

tag.sidebar=function(tags){
	$.each(tags,function(index_tag,elemet){
		 $('#tool_right ul').append('<li id="'+index_tag+'" class="">'+index_tag+' ('+elemet+')</li>');
		 $('#tool_right ul li[id^="'+index_tag+'"]').click(function(){
			 tag.loadImagesTag(this)
	 	});
	});
}

tag.photoOverView=function(photos){
	$.each(photos,function(index_tag,image){
		 $('#photoOverView').append('<div class="image" ><div class="test"><a name="'+image.path+'" href="#photoview/'+image.id+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.path+'"  alt="'+image.id+'"></a><input type="checkbox" original-title="" alt="'+image.id+'" ></input></div></div>');
		 $('#photoOverView a[name^="'+image.path+'"] img').click(function(){
	 			PhotoView.ClickImg(this)
	 	});
		 
  });
	Module.setEvents();
}



tag.key=function(){
		var tag_value=$("#tool_righte div.tool.Key  input[type='text']").val();
		$("#tool_righte div.tool.Key  input[type='text']").val('');
		var image=$('#photoview img').attr("alt");
		
		$.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+image+"&tag="+tag_value, function(data) {
			$("#tool_righte .tool.Key .tool_items table *").remove();
			$("#tool_righte .tool.Tag .tool_items table *").remove();
				$("#photo div").remove();
				tag.getTag(image);
		});
		OC.Notification.show(tag_value+" Tags set");
		setTimeout(function(){
			OC.Notification.hide();
		}, 3000);
		
 
};

tag.checkbox=function(){
	var list=$('#photoOverView input[type="checkbox"]');
	var tag_value=$("#module input[type='text']").val();
	
	if(tag_value.length>0){
		$("#module input[type='text']").val("");
		var imagelist=new Array();
		$.each(list,function(index_tag,input){
			if($(input).attr('checked')==='checked'){
				 imagelist.push($(input).attr("alt"));
			}
		});
	
		var counter=$("input[name='counterTag']").attr("value");
		if(counter>0){
			$.post(OC.linkTo('facefinder', 'ajax/inserttagList.php'), { 'imagelist[]':imagelist ,'tag' :tag_value })
			.always(function(data) { 
				OC.Notification.show("Set \""+tag_value+"\" to "+data.Correct+" Images."+data.Wrong+" were olrady ");
				setTimeout(function(){
					OC.Notification.hide();
				},3000);
			});
		}else{
			OC.Notification.show("No Images selectet");
			setTimeout(function(){
				OC.Notification.hide();
			},3000);
		}
	}else{
		OC.Notification.show("No Text to Tag");
		setTimeout(function(){
			OC.Notification.hide();
		},3000);
	}

};
tag.checkboxevent=function(event,element){

	var counter=$("input[name='counterTag']").attr("value");
	if($(element).attr('checked')==='checked'){
		$("input[name='counterTag']").attr("value",++counter);
		$("button.submitTag").addClass("btn btn-warning");
	}else{
		$("input[name='counterTag']").attr("value",--counter);
		if(counter===0)
			$("button.submitTag").removeClass("btn btn-warning");
	}
	$("button.submitTag").text("Set Tag ("+counter+")");
};


function getCoordinates(e){
	 var PosX = 0;
	  var PosY = 0;
	  var posX = 0;
	  var posY = 0;
	  Eltern = document.getElementById("img_img")
	  while (Eltern) {
		  //alert(Eltern.tagName  +" "+Eltern.offsetLeft+" ");
		  posX += Eltern.offsetLeft;
	      posY += Eltern.offsetTop;
		  Eltern = Eltern.offsetParent;
		}
	  if (e.clientX || e.clientY)
	    {
	      PosX = e.clientX + document.body.scrollLeft
	        + document.documentElement.scrollLeft;
	      PosY = e.clientY + document.body.scrollTop
	        + document.documentElement.scrollTop;
	    }
	 PosX = PosX - posX;
	 PosY = PosY - posY;
	// alert(PosX+' '+PosY);
	 //for ckorekt  possiton in the photoview div 
	 PosX+=document.getElementById("img_img").offsetLeft;
	 PosY+=document.getElementById("img_img").offsetTop;
	 return [PosX,PosY]; 
}

function findPosition(oElement)
{
  if(typeof( oElement.offsetParent ) != "undefined")
  {
	  var posX = 0, posY = 0;
    /*for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent)
    {
      posX += oElement.offsetLeft;
      posY += oElement.offsetTop;
    }*/
    posX += oElement.offsetLeft;
    posY += oElement.offsetTop;
    posX-=document.getElementById("img_img").offsetLeft;
    posY-=document.getElementById("img_img").offsetTop;
      return [ posX, posY ];
    }
    else
    {
      return [ oElement.x, oElement.y ];
    }
}



