function Tag(){
		this.load=function(image){
			$("#taggs div").remove();
			$("#photo div").remove();
			$("#tool_taggs textarea").val("");
			Tag.getTag(image);
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
						 var image=$('#photoview img').attr("name");
						 var tag=$(this).val();
						 var x1=(pos[0]/document.getElementById("img_img").offsetWidth);
				 		 var y1=(pos[1]/document.getElementById("img_img").offsetHeight);
				 		 var x2=($(this).parent().width()/document.getElementById("img_img").offsetWidth);
				 		 var y2=($(this).parent().height()/document.getElementById("img_img").offsetHeight);
							$.getJSON(OC.linkTo('facefinder', 'ajax/inserttagposition.php')+"?image="+image+"&tag="+tag+"&x1="+x1+"&x2="+x2+"&y1="+y1+"&y2="+y2, function(data) {
								$("#taggs div").remove();
								$("#photo div").remove();
								Tag.getTag(image);
							});
							$(this).parent().append('<div class="draggable_tag">'+tag+'</div>');
							$(this).parent().draggable({ disabled: true });
							$(this).parent().attr('class', 'draggable_fix');
							$("#photo .draggable_fix").click(function (e){
							$(this).draggable({ disabled: false });
							$(this).attr('class', 'draggable');
						});
						
						$(this).parent().remove();
						
					 }
					});
				
				 $('#photo .draggable').draggable({
					    cursor: 'move',
					    containment: '#img_img'
					  } ).resizable();
				
				});
		};
}
Tag.getTag=function(img){
	$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+img, function(data) {
		$.each(data,function(index_tag,data){
			 
			if(data.x1==0 && data.x2==0 && data.y1==0 && data.y2==0){
			$('#taggs').append('<div class="tag"><img alt="" src="'+OC.linkTo('facefinder', 'img/delete.png')+'" name="'+data.name+' '+data.tag+'">'+data.name+' '+data.tag+"</div>");
			$('#taggs div.tag img').click(function(){
				Tag.removeTag(this);
			});
			}else{
				 var x=(parseFloat(data.x1)*document.getElementById("img_img").offsetWidth);
		 		 var y=(parseFloat(data.y1)*document.getElementById("img_img").offsetHeight);
		 		 var x2=(parseFloat(data.x2)*document.getElementById("img_img").offsetWidth);
		 		 var y2=(parseFloat(data.y2)*document.getElementById("img_img").offsetHeight);
				 var y1=(document.getElementById("img_img").offsetTop+y);
				 var x1=(document.getElementById("img_img").offsetLeft+x);
				$("#photo").append('<div class="draggable_fix" style="position: absolute; top: '+y1+'px; left: '+x1+'px; width: '+x2+'px; height:'+y2+'px;"><div class="draggable_tag"><img alt="" src="'+OC.linkTo('facefinder', 'img/delete.png')+'" name="'+data.name+' '+data.tag+'">'+data.tag+'</div></input></div>');
				$('#photo div.draggable_fix img').click(function(){
					Tag.removeTagDiv(this);
					 
				});
			}
		});
	});
};

Tag.removeTag=function(tagDiv){
	var image=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().remove();
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
};

Tag.removeTagDiv=function(tagDiv){
	var image=$('#photo img').attr("name");
	var tag=$(tagDiv).attr("name");
	 $(tagDiv).parent().parent().remove();
	 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
};

Tag.key=function(e){
	if ( e.keyCode== 13){
		var tag=$("#tool_taggs textarea").val();
		$("#tool_taggs textarea"). val("");
		var image=$('#photoview img').attr("name");
		$.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+image+"&tag="+tag, function(data) {
				$("#taggs div").remove();
				$("#photo div").remove();
				Tag.getTag(image);
		});
 
	}
};

$(document).ready(function() {
	$("#tool_taggs textarea").keyup(function(e){
		Tag.key(e);
	});
	
	
	

});

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





