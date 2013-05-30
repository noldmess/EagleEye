$(document).ready(function() {
	$("#tool_righte").append('<div class="tool Face"><div class="tool_title"><i class="icon-info-sign"></i>Face</div><div class="tool_items"><ul></ul></div></div>');
});


function face(){
		this.load=function(image){
			$("#tool_righte .tool.Face .tool_items ul *").remove();
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			setTimeout(function(){
				face.getTag(image);
				  }, 300);
		};
};

face.getTag=function(img){
	$.getJSON(OC.linkTo('facefinder', 'ajax/face.php')+'?image='+img, function(data) {
		if (data.status == 'success'){
			$.each(data.data,function(index_tag,data){
					 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
			 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
			 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth);
			 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight);
					 var y1=(document.getElementById("img_img").offsetTop+y);
					 var x1=(document.getElementById("img_img").offsetLeft+x);
					 var extra="";
					 if(data.tag!=null && data.tag_id!=null ){
						 extra= face.create(data.face_id,data.tag,x1,y1,x2,y2);
						 $('#tool_righte .tool.Face .tool_items ul').append('<li><i class="icon-remove-sign" name="'+data.face_id+'"></i>'+data.name+' '+data.tag+"</li>");
					 }else{
						 $("#photo").append('<div class="face outer "style="position: absolute; top: '+y1+'px; left: '+x1+'px;"><div class="face inner" style=" width: '+x2+'px; height:'+y2+'px;"></div><div class="face outer fram"><i class="icon-remove-sign" alt="'+data.face_id+'" src="'+OC.linkTo('facefinder', 'img/delete.png')+'" name="'+data.name+' '+data.tag+'"></i><input   type="text"  value="" id="'+data.face_id+'" alt="'+data.x1+'-'+data.y1+'-'+data.x2+'-'+data.y2+'"></input></div></div>');
						 $("input#"+data.face_id).keyup(function(e){ 
							 if ( e.keyCode== 13){
								 //e.delegateTarget.alt
								 var tag_name=$(this).val();
								 var image=$('#photoview img').attr("alt");
								 var face_id=$(this).attr("id");
								 var pos=$(this).attr("alt");
								 $.getJSON(OC.linkTo('facefinder', 'ajax/faceinsert.php')+"?image="+image+"&tag="+tag_name+"&face_id="+face_id+"&pos="+pos, function(data) {
									 $(this).parent().remove();
									 tag.getTag(image);
								 });
							 }
						 });
					$('#photo div.draggable_face i').click(function(){
						face.removeTagDiv(this);
					});
					$('#tool_righte .tool.Face .tool_items ul').append('<li><i class="icon-remove-sign" name="'+data.name+' '+data.tag+'"></i>Not Set Face</li>');
				}
			});
	}
	});
};

face.removeTag=function(tagDiv){
	var image=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().remove();
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
};

face.removeTagDiv=function(tagDiv){
	 $.getJSON(OC.linkTo('facefinder', 'ajax/faceremove.php')+"?image="+$(tagDiv).attr("alt"), function(data) {});
	 $(tagDiv).parent().remove();
};


face.create=function(face_id,tag,x1,y1,x2,y2){
	$("#photo").append('<div class="draggable_face_option" style="position: absolute; top: '+(y1+y2-20)+'px; left: '+(x1+x2-20)+'px; width: '+20+'px; height:'+20+'px;"><a><i class="icon-edit" id="'+face_id+'"></i></a></div>');
	$("#photo .draggable_face_option #"+face_id).click(function(){
		 var face_id=$(this).attr("id");
		 var name=$(this).attr("name");
		 var image=$('#photoview img').attr("alt");
		 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
		 removeTagDiv(tagDiv);
		 $.getJSON(OC.linkTo('facefinder', 'ajax/faceupdate.php')+"?face_id="+face_id, function(data) {});
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
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag+"&imagepaht="+imagepaht, function(data) {});
};



