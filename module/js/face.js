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
	$.getJSON(OC.linkTo('facefinder', 'ajax/face.php')+'?image='+img, function(data) {
		if (data.status == 'success'){
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
						// $("#photo").append('<div class="face outer "style="position: absolute; top: '+(y1+(y2-y1))+'px; left: '+x1+'px;" id="'+index_tag+'"><div class="face inner" style=" width: '+(y2-y1)+'px; height:'+(y2-y1)+'px;  position: absolute; top: '+y1+'px; left: '+x1+'px;""></div><div class="fram"><i class="icon-remove-sign" alt="'+data.face_id+'" src="'+OC.linkTo('facefinder', 'img/delete.png')+'" name="'+data.name+' '+data.tag+'"></i><input   type="text"  value="" id="'+data.face_id+'" alt="'+data.x1+'-'+data.y1+'-'+(data.y2-data.y1)+'-'+(data.x2-data.x1)+'"></input></div></div>');
						 $("#photo").append('<div class="draggable" style="position: absolute; top: '+(PosY+(y2-y1))+'px; left: '+(PosX+(y2-y1))+'px;"><a id="fancybox-close" style="display: inline;"></a><div class="draggable2"></div><div class="addTag"><input   type="text"  value="" name="query" placeholder="add Tag" ></input><input type="button" value=" Set Tag "></input></div></div>');
							 $('#photo input[id="'+data.face_id+'"]').keyup(function(e){
							 if ( e.keyCode== 13){
								 //e.delegateTarget.alt
								var test=$(this).parent().parent();
								 var tag_name=$(this).val();
								 var image=$('#photoview img').attr("alt");
								 var face_id=$(this).attr("id");
								 var pos=$(this).attr("alt");
								 $.getJSON(OC.linkTo('facefinder', 'ajax/faceinsert.php')+"?image="+image+"&tag="+tag_name+"&face_id="+face_id+"&pos="+pos, function(data) {
									 $("#tool_righte .tool.Face .tool_items table *").remove();
									 $("#tool_righte .tool.Key .tool_items table *").remove();
									 $("#tool_righte .tool.Tag .tool_items table *").remove();
									 setTimeout(function(){
										 face.getTag(image);
										 tag.getTag(image);
										 $(test).remove();
											  }, 300);

									
								 });
							 }
						 });
				
					$('#tool_righte .tool.Face .tool_items tbody').append('<tr><td>Not Set Face</td></tr>');
				}
			});
			}else{
				$('#tool_righte .tool.Face .tool_items table').append('<thead>'
						+'<tr>'
						+'	<th>No Faces Found</th>'
					    +'</tr>'
					    +'</thead>'
					    +' <tbody></tbody>');
			}
	}
		 //remove Event
		 var dsfdf=$('#img_img ');
			$('#tool_righte .tool.Face .tool_items tbody tr i.icon-remove-sign').click(function(){
				face.removeTagDiv(this);
				var name=$(this).attr("name");
				 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
				 removeTagDiv(tagDiv);
				 $("#tool_righte .tool.Face .tool_items table *").remove();
				 $("#tool_righte .tool.Tag .tool_items table *").remove();
				var image=$('#photoview img').attr("alt");
				setTimeout(function(){
					 face.getTag(image);
					 tag.getTag(image);
					
						  }, 300);
			});
			
			$('#tool_righte .tool.Face .tool_items tbody tr i.icon-edit').click(function(){
//					alert("remove Face ");
				 var face_id=$(this).attr("id");
				 var name=$(this).attr("name");
				 var image=$('#photoview img').attr("alt");
				 var tagDiv=$('i[name^="KEYWORDS '+name+'"]');
				 removeTagDiv(tagDiv);
				 $.getJSON(OC.linkTo('facefinder', 'ajax/faceupdate.php')+"?face_id="+face_id, function(data) {});
				 $("#tool_righte .tool.Face .tool_items table *").remove();
				 $("#tool_righte .tool.Key .tool_items table *").remove();
				 face.getTag(image);
				 tag.getTag(image);
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
	});

};

face.removeTag=function(tagDiv){
	var image=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().remove();
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
};

face.removeTagDiv=function(tagDiv){
	var asdfasd=$(tagDiv).attr("id");
	 $.getJSON(OC.linkTo('facefinder', 'ajax/faceremove.php')+"?image="+$(tagDiv).attr("id"), function(data) {});
	 $(tagDiv).parent().remove();
};


face.create=function(face_id,tag,x1,y1,x2,y2){
	//$("#photo").append('<div class="draggable_face_option" style="position: absolute; top: '+(y1+y2-20)+'px; left: '+(x1+x2-20)+'px; width: '+20+'px; height:'+20+'px;"><a><i class="icon-edit" id="'+face_id+'"></i></a></div>');
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



