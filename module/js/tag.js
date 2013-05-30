$(document).ready(function() {
	tag.init();
});



function tag(){	
		this.load=function(image){
			//remove old tags from view
			$("#tool_righte .tool.Tag .tool_items ul *").remove();
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			//load tags from the image
			tag.getTag(image);
			$('#photo img').click(function(e){
				tag.maketag(e);
				});
		};
};


tag.init=function(){
	$("#tool_righte").append('<div class="tool Tag"><div class="tool_title"><i class="icon-info-sign"></i>Tag</div><div class="tool_items fix"><input   type="text"  value="" name="query"  placeholder="Write Tag"></input><input type="submit" value=" Set Tag "></input><p><label for="male">All tags Visible</label><input   type="checkbox"  value="" name="query" ></input></p></div><div class="tool_items"><ul></ul></div></div>');
	$("span.right").append('<button class="tag" style=""> Tag </button><input   type="text"  value="" name="query"  placeholder="Write Tag"></input><input type="submit" value=" Set Tag "></input>');
	$("button.tag").click(function(e){
		tag.click();
	});
	
	$("#tool_righte .tool.Tag .tool_items input[type='submit']").click(function(e){
		tag.key();
	});
	$("#tool_righte .tool.Tag .tool_items input[type='text']").keyup(function(e){
		if ( e.keyCode== 13){
			tag.key();
		}
	});
	
	$("span.right input[type='submit']").click(function(e){
		tag.checkbox();
	});
	$("span.right input[type='text']").keyup(function(e){
		if ( e.keyCode== 13){
			tag.checkbox();
		}
	});
	
	$("#tool_righte .tool.Tag .tool_items input[type='checkbox']").click(function(e){
		if($("#photo div.tag_in_photo").is(':visible')){
			$("#photo div.tag_in_photo").hide();
		}else{
			$("#photo div.tag_in_photo").show();
		}
	});
}

tag.maketag=function(e){
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
	// alert(PosX+' '+PosY);
	 //for ckorekt  possiton in the photoview div 
	 PosX+=document.getElementById("img_img").offsetLeft;
	 PosY+=document.getElementById("img_img").offsetTop;//
	 $("#photo").append('<div class="draggable" style="position: absolute; top: '+(PosY-50)+'px; left: '+(PosX-50)+'px;"><input   type="text"  value="" name="query" ></input></div>');
	 $("#photo .draggable input").keyup(function(e){
			//Tag.key(e);
		 
		 if ( e.keyCode== 13){
			 var pos=findPosition(this.parentNode);
			 var image=$('#photoview img').attr("alt");
			 var tag_name=$(this).val();
			 var x1=(pos[0]/document.getElementById("img_img").offsetWidth);
	 		 var y1=(pos[1]/document.getElementById("img_img").offsetHeight);
	 		 var x2=($(this).parent().width()/document.getElementById("img_img").offsetWidth);
	 		 var y2=($(this).parent().height()/document.getElementById("img_img").offsetHeight);
				$.getJSON(OC.linkTo('facefinder', 'ajax/inserttagposition.php')+"?image="+image+"&tag="+tag_name+"&x1="+x1+"&x2="+x2+"&y1="+y1+"&y2="+y2, function(data) {
					$("#tool_righte .tool.Tag .tool_items ul *").remove();
					$("#photo div").remove(".draggable");
					tag.getTag(image);
				});
			
				/*$(this).parent().append('<div class="draggable_tag">'+tag+'</div>');
				$(this).parent().draggable({ disabled: true });
				$(this).parent().attr('class', 'draggable_fix');
				$("#photo .draggable_fix").click(function (e){
				$(this).draggable({ disabled: false });
				$(this).attr('class', 'draggable');
			});*/
			
			$(this).parent().remove(".draggable_fix");
			
		 }
		});
	
	 $('#photo .draggable').draggable({
		    cursor: 'move',
		    containment: '#img_img'
		  } ).resizable();
	
}


tag.getTag=function(img){
	$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+img, function(data) {
		if (data.status == 'success'){
			$.each(data.data,function(index_tag,data){
				var extra="";
				if(data.x1>0 || data.x2>0 || data.y1>0 || data.y2>0){
					 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
			 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
			 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth);
			 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight);
					 var y1=(document.getElementById("img_img").offsetTop+y);
					 var x1=(document.getElementById("img_img").offsetLeft+x);
					$("#photo").append('<div id="'+data.name+' '+data.tag+'" class="tag_in_photo"style="position: absolute; top: '+y1+'px; left: '+x1+'px;"><div class="draggable_fix" style="width: '+x2+'px; height:'+y2+'px;"></div><div class="draggable_tag">'+data.tag+'</div></div>');
					$('#photo div.tag_in_photo i').click(function(){
						tag.removeTagDiv(this);
					});
					extra='<i class="icon-search"></i>';
				}
				$('#tool_righte .tool.Tag .tool_items ul').append('<li><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'"></i>'+data.name+' '+data.tag+extra+"</li>");
					
			});
			$("#photo div.tag_in_photo").hide();
			$('#tool_righte .tool.Tag .tool_items ul li i').click(function(){
				tag.removeTag(this);
			});
			$('#tool_righte .tool.Tag .tool_items ul li').mouseenter(function(){
				var tag=$(this).children("i").attr("name");
				var dsfsdf=$("div[id='"+tag+"']");
				dsfsdf.show();
			}).mouseleave(function(){
				var tag=$(this).children("i").attr("name");
				var dsfsdf=$("div[id^='"+tag+"']");
				dsfsdf.hide();
			});
	}
		
	});
};

tag.removeTag=function(tagDiv){
	var image=$('#photo img').attr("alt");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().remove();
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
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag+"&imagepaht="+imagepaht, function(data) {});
};


//cange to tag Photo Over View
tag.click=function(e){
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
		 $('#tool_right ul').append('<li id="'+index_tag+'" class="">'+index_tag+'('+elemet+')</li>');
		 $('#tool_right ul li[id^="'+index_tag+'"]').click(function(){
			 tag.loadImagesTag(this)
	 	});
	});
}

tag.photoOverView=function(photos){
	$.each(photos,function(index_tag,image){
		//$("#photoOverView").append('<div class="image" ><a name="'+image.imagsname+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div>');
		 $('#photoOverView').append('<div class="image" ><a name="'+image.path+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.path+'"  alt="'+image.id+'"></a><input type="checkbox" original-title="" alt="'+image.id+'" ></input></div>');
		 $('#photoOverView a[name^="'+image.path+'"] img').click(function(){
	 			PhotoView.ClickImg(this)
	 	});
  });
}



tag.key=function(){
		var tag_value=$("#tool_righte .tool.Tag .tool_items input[type='text']").val();
		$("#tool_righte .tool.Tag .tool_items input[type='text']").val('');
		var image=$('#photoview img').attr("alt");
		$.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+image+"&tag="+tag_value, function(data) {
				$("#tool_righte .tool.Tag .tool_items ul *").remove();
				$("#photo div").remove();
				tag.getTag(image);
		});
 
};

tag.checkbox=function(){
	var list=$('#photoOverView input[type="checkbox"]');
	var tag_value=$("span.right input[type='text']").val();
	if(tag_value.length>0){
		$("span.right input[type='text']").val('');
		var image=$('#photoview img').attr("alt");
		$.each(list,function(index_tag,input){
			if($(input).attr('checked')==='checked'){
				var asdfdsaf=$(input).attr("alt");
				$.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+$(input).attr("alt")+"&tag="+tag_value, function(data) {});
			}
		});
	}

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



