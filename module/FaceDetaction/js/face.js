$(document).ready(function() {
	
});


function face(){
		this.load=function(image){
			$("#tool_righte .tool.Face .tool_items table *").remove();
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			setTimeout(function(){
				face.getTag(image);
				  }, 300);
		},
		this.hideView=function (event){
			$("#photo div.draggable_face").remove();
		};
		this.showView=function (event){

		};
		this.resat=function(){
			 
		},
		this.setEvents=function(){
		},
		this.duplicatits=function(element,data){
		},
		this.init=function(){
			$("#tool_righte").append('<div class="tool Face"><div class="tool_title"><i class="icon-white icon-arrow-up"></i>Face</div><div class="tool_items"><table class="table"></table></div></div>');
		}
};

face.getTag=function(img){
	$('#tool_righte .tool.Face .tool_items').addClass('loading');
	$.getJSON(OC.linkTo('facefinder', 'module/FaceDetaction/ajax/face.php')+'?image='+img, function(data) {
		if (data.status == 'success' || data.status == 'new'){
			if(data.data.type == 'new'){
				 tag.getTag(image);
				 setTimeout(function(){
					 face.getTag(image);
						  }, 300);
			}else{
			if(data.data.length>0){
				$('#tool_righte .tool.Face .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>Name</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
			$.each(data.data,function(index_tag,data){
					 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
			 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
			 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth);
			 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight);
					 var PosY=(document.getElementById("img_img").offsetTop+y);
					 var PosX=(document.getElementById("img_img").offsetLeft+x);
					 var extra="";
					 
					 if(data.tag!=null && data.tag_id!=null ){
						 $('#tool_righte .tool.Face .tool_items tbody').append('<tr><td><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'" id="'+data.face_id+'"></i>'+data.tag+'<i class="icon-edit" id="'+data.face_id+'" name="'+data.tag+'" id="'+data.face_id+'"></td></tr>');
					 }else{
						// $("#photo").append('<div class="face outer "style="position: absolute; top: '+(y1+(y2-y1))+'px; left: '+x1+'px;" id="'+index_tag+'"><div class="face inner" style=" width: '+(y2-y1)+'px; height:'+(y2-y1)+'px;  position: absolute; top: '+y1+'px; left: '+x1+'px;""></div><div class="fram"><i class="icon-remove-sign" alt="'+data.face_id+'" src="'+OC.linkTo('facefinder', 'img/delete.png')+'" name="'+data.name+' '+data.tag+'"></i><input   type="text"  value=""></input></div></div>');
						//$("#photo").append('<div class="draggable_face" style="position: absolute; top: '+(PosY)+'px; left: '+(PosX-115+((y2-PosY)/2))+'px;  width:230px;"><div class="draggable_face_2" style=" width: '+(y2-PosY)+'px; height:'+(y2-PosY)+'px;"><a id="fancybox-close" style="display: inline;"><i class="icon-remove"></i></a></div><div class="addTag"><input id="'+data.face_id+'"  alt="'+data.x1+'-'+data.y1+'-'+(data.x2-data.x1)+'-'+(data.y2-data.y1)+'" type="text"  value="" name="query" placeholder="add Face Name" ></input><input type="button" value=" Set Tag "></input></div  ></div>');
						$("#photo").append('<div class="draggable_face" style="position: absolute; top: '+(PosY)+'px; left: '+Math.abs(PosX-115+((y2-PosY)/2))+'px;" ><div class="draggable_face_2" style="position: absolute;  left: '+Math.abs(115-(y2-PosY)/2)+'px;   width: '+Math.abs(y2-PosY)+'px; height:'+Math.abs(y2-PosY)+'px;"><a id="fancybox-close" ><i class="icon-remove"></i></a></div><div class="addTag" style=" position: absolute;  top:'+Math.abs(y2-PosY)+'px;"><input id="'+data.face_id+'"  alt="'+data.x1+'-'+data.y1+'-'+(data.x2-data.x1)+'-'+(data.y2-data.y1)+'" type="text"  value="" name="query" placeholder="add Face Name" ></input><input type="button" value=" Set Face "></input></div  ></div>');
						$('#tool_righte .tool.Face .tool_items tbody').append('<tr><td>Not Set Face</td></tr>');
					 }
			 });
					$('#photo .draggable_face .draggable_face_2 a').click(function(e){
						var sdfsdf=$(this).parent();
						var sdfsdf=$(this).parent().parent().children('.addTag');
						var sdfsdf=$(this).parent().parent().children('.addTag').children('input[name="query"]');
						face.removeTagDiv(sdfsdf);
						$(this).parent().remove();
					 });
					
					$('#photo .draggable_face .addTag input[type="text"]').keyup(function(e){
						if ( e.keyCode== 13){
							face.setFaceInImage(this,$(this).val(),$(this).attr("id"),$(this).attr("alt"));
						 }
					 });
					
					$('#photo .draggable_face .addTag input[type="button"]').click(function(e){
						var inputDiv=$(this).parent().children('input[name="query"]');
						var tag_name=$(inputDiv).val()
						var face_id=$(inputDiv).attr("id");
						var pos=$(inputDiv).attr("alt");
						face.setFaceInImage(this,tag_name,face_id,pos);		
					 });
		
			}else{
				$('#tool_righte .tool.Face .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>No Faces Found</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
			}
	
		$('#photo .draggable_face_2').mouseenter(function(){
				var addTagDiv=$(this).parent().children('.addTag');
				if($(addTagDiv).is(":visible") !== true){
					$(addTagDiv).show();
				}
			});
		$('#photo .draggable_face').mouseleave(function() {
				var addTagDiv=$(this).children('.addTag');
				if($(addTagDiv).is(":visible") === true){
					$(addTagDiv).hide();
				}
		});
		 //remove Event
		 var dsfdf=$('#img_img ');
			$('#tool_righte .tool.Face .tool_items tbody tr i.icon-remove-sign').click(function(){
				face.removeTagDiv(this);
				var name=$(this).attr("name");
				 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
				 removeTagDiv(tagDiv);
				 $("#photo div.draggable_face").remove();
				 $("#photo div.tag_in_photo").remove();
				 $("#tool_righte .tool.Face .tool_items table *").remove();
				 $("#tool_righte .tool.Tag .tool_items table *").remove();
				var image=$('#photoview img').attr("alt");
				setTimeout(function(){
					 face.getTag(image);
					 tag.getTag(image);
					
						  }, 300);
			});
			
			$('#tool_righte .tool.Face .tool_items tbody tr i.icon-edit').click(function(){
				 var face_id=$(this).attr("id");
				 var name=$(this).attr("name");
				 var image=$('#photoview img').attr("alt");
				 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
				 removeTagDiv(tagDiv);
				 OC.Notification.show("Updating face Data Set");
				 $('#content').css( 'cursor', 'wait' );
				 $.getJSON(OC.linkTo('facefinder', 'module/FaceDetaction/ajax/faceupdate.php')+"?face_id="+face_id, function(data) {
					 var image=$('#photoview img').attr("alt");
					 $("#photo div.draggable_face").remove();
					 $("#photo div.tag_in_photo").remove();
					 $("#tool_righte .tool.Face .tool_items table *").remove();
					 $("#tool_righte .tool.Key .tool_items table *").remove();
					 face.getTag(image);
					 tag.getTag(image);
					 OC.Notification.hide();
					 $('#content').css( 'cursor', 'auto' );
				 });	 
			});
			//mous hover set visible;
			$('#tool_righte .tool.Face .tool_items tbody tr').mouseenter(function(){
				var test=$('#tool_righte .tool.Key .tool_items.fix input[type="checkbox"]').attr('checked');
				if(test===undefined){
				var tag=$(this).children("td").children("i").attr("name");
				var sdfsdf=$("div[id='"+tag+"']");
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
		}
		$('#tool_righte .tool.Face .tool_items').removeClass('loading');
	});

};

face.setFaceInImage=function(div,tag_name,face_id,pos){
		 //e.delegateTarget.alt
	var helpToRemove=$(div).parent().parent();
	var image=$('#photoview img').attr("alt");
	OC.Notification.show("Updating face Data Set");
	$.getJSON(OC.linkTo('facefinder', 'module/FaceDetaction/ajax/faceinsert.php')+"?image="+image+"&tag="+tag_name+"&face_id="+face_id+"&pos="+pos, function(data) {
		 $("#photo div.draggable_face").remove();
		 $("#photo div.tag_in_photo").remove();
		 $("#tool_righte .tool.Face .tool_items table *").remove();
		 $("#tool_righte .tool.Key .tool_items table *").remove();
		 $("#tool_righte .tool.Tag .tool_items table *").remove();
		 setTimeout(function(){
			OC.Notification.hide();
			face.getTag(image);
			tag.getTag(image);
			$(helpToRemove).remove();
		}, 300);
		

	 });
}

face.removeTag=function(tagDiv){
	var image=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().remove();
	 var image=$('#photoview img').attr("alt");
	 $.getJSON(OC.linkTo('facefinder', 'module/Tag/ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {
		 $("#photo div.draggable_face").remove();
		 $("#photo div.tag_in_photo").remove();
		 $("#tool_righte .tool.Face .tool_items table *").remove();
		 $("#tool_righte .tool.Key .tool_items table *").remove();
		 $("#tool_righte .tool.Tag .tool_items table *").remove();
		 face.getTag(image);
		 tag.getTag(image);
	 });

};

face.removeTagDiv=function(tagDiv){
	/*var asdfasd=$(tagDiv).attr("id");*/
	 $.getJSON(OC.linkTo('facefinder', 'module/FaceDetaction/ajax/faceremove.php')+"?image="+$(tagDiv).attr("id"), function(data) {
		 var image=$('#photoview img').attr("alt");
		 $("#photo div.draggable_face").remove();
		 $("#photo div.tag_in_photo").remove();
		 $("#tool_righte .tool.Face .tool_items table *").remove();
		 $("#tool_righte .tool.Key .tool_items table *").remove();
		 $("#tool_righte .tool.Tag .tool_items table *").remove();
		 face.getTag(image);
		 tag.getTag(image);
	 });

};


face.create=function(face_id,tag,x1,y1,x2,y2){
	//$("#photo").append('<div class="draggable_face_option" style="position: absolute; top: '+(y1+y2-20)+'px; left: '+(x1+x2-20)+'px; width: '+20+'px; height:'+20+'px;"><a><i class="icon-edit" id="'+face_id+'"></i></a></div>');
	$("#photo .draggable_face_option #"+face_id).click(function(){
		 var face_id=$(this).attr("id");
		 var name=$(this).attr("name");
		 var image=$('#photoview img').attr("alt");
		 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
		 removeTagDiv(tagDiv);
		 $.getJSON(OC.linkTo('facefinder', 'module/FaceDetaction/ajax/faceupdate.php')+"?face_id="+face_id, function(data) {});
		 face.getTag(image);
		 return '<i id="" class="icon-edit"></i>';
	 });
	/*hover(function(){
		alert("sdffd");
	 });*/
}

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



function removeTagDiv(tagDiv){
	var image=$('#photo img').attr("alt");
	var imagepaht=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().parent().remove(".draggable_fix");
	 $.getJSON(OC.linkTo('facefinder', 'module/Tag/ajax/removetag.php')+"?image="+image+"&tag="+tag+"&imagepaht="+imagepaht, function(data) {});
};



