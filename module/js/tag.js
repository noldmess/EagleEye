$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Tag"><div class="tool_title"><i class="icon-info-sign"></i>Tag<input   type="checkbox"  value="" name="query" ></input></div><div class="tool_items"><ul></ul></div><div class="tool_items fix"><input   type="text"  value="" name="query" ></input><input type="submit" value=" Set Tag "></input></div></div>');
	$("span.right").append('<button class="tag" style=""> Tag </button>');
	$("button.tag").click(function(e){
		tag.click();
	});
	
	$("#tool_righte .tool.Tag .tool_items input[type='submit']").click(function(e){
		alert("Sdfsdf");
	});
	$("#tool_righte .tool.Tag .tool_items input[type='text']").keyup(function(e){
		tag.key(e,this);
	});
});
function tag(){	
		this.load=function(image){
			$("#tool_righte .tool.Tag .tool_items ul *").remove();
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			tag.getTag(image);
			$('#photo img').click(function(e){
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
				
				});
		};
};

tag.getTag=function(img){
	$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+img, function(data) {
		if (data.status == 'success'){
			$.each(data.data,function(index_tag,data){
				var extra="";
				if(data.x1>0 || data.x2>0 || data.y1>0 || data.y2>0){
					 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
			 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
			 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth-x);
			 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight-y);
					 var y1=(document.getElementById("img_img").offsetTop+y);
					 var x1=(document.getElementById("img_img").offsetLeft+x);
					$("#photo").append('<div id="'+data.name+' '+data.tag+'" class="tag_in_photo"style="position: absolute; top: '+y1+'px; left: '+x1+'px;"><div class="draggable_fix" style="width: '+x2+'px; height:'+y2+'px;"></div><div class="draggable_tag">'+data.tag+'</div></div>');
					$('#photo div.tag_in_photo i').click(function(){
						tag.removeTagDiv(this);
					});
					$('#photo div.draggable_fix i').click(function(){
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


tag.loadImagesTag=function(tagelement){
	var path=tag.getPath();
	tagelement=tagelement.id;
	 $('#tool_right *').removeClass('active');
	 $('#tool_right ul li[id^="'+tagelement+'"]').addClass('active');
	 $.getJSON(OC.linkTo('facefinder', 'ajax/allImagesTags.php')+'?dir='+path+'&tag='+tagelement, function(data) {
		 $('#photoOverView').addClass('loading');
		 $('#photoOverView * ').remove();
		 if (data!==null && data.status == 'success'){
			 	var t= data.photo
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
		 $('#photoOverView').append('<a name="'+image.path+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.path+'"  alt="'+image.id+'"></a><input type="checkbox" original-title=""></input>');
		 $('#photoOverView a[name^="'+image.path+'"] img').click(function(){
	 			PhotoView.ClickImg(this)
	 	});
  });
}



tag.key=function(e,t){
	if ( e.keyCode== 13){
		var tag_value=$(t).val();
		$(t).val('');
		var image=$('#photoview img').attr("alt");
		$.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+image+"&tag="+tag_value, function(data) {
				$("#tool_righte .tool.Tag .tool_items ul *").remove();
				$("#photo div").remove();
				tag.getTag(image);
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



