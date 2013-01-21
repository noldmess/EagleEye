$(document).ready(function() {
	$('#photoview').hide();
   $.getJSON(OC.linkTo('facefinder', 'ajax/new_1.php'), function(data) {
	   		//Create the year divs 
	   		$.each(data,function(index_year,data){
	   		$("#new_1").append('<div class="year"></div>');
	   		$("div.year:eq("+index_year+")").append('<div class="head_line"><div class="title_head">'+data.year+'</div><div class="ico_inline"></div></div>');
	   		$("#new_1 div.year:eq("+index_year+")  div.head_line").click(function() {
	  			if( $(this).parent().children("div.month").is(":visible")){
	  				$(this).children("div.ico_inline").removeClass().addClass("ico_down");
	  				$(this).parent().children("div.month").slideUp(500);
	  			}else{
	  				$(this).children("div.ico_down").removeClass().addClass("ico_inline");
	  				$(this).parent().children("div.month").slideDown(500);
	  			}
	   		});
	   		$.each(data.month,function(index_month,data){
	   			$("#new_1 div.year:eq("+index_year+")").append('<div class="month"></div>');
	   			$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="head_line"><div class="title_head">'+data.month+'</div><div class="ico_inline"></div></div>');
        	  	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.head_line").click(function() {
        	  			if( $(this).parent().children("div.day").is(":visible")){
        	  				$(this).children("div.ico_inline").removeClass().addClass("ico_down");
        	  				$(this).parent().children("div.day").slideUp(500);
        	  			}else{
        	  				$(this).children("div.ico_down").removeClass().addClass("ico_inline");
        	  				$(this).parent().children("div.day").slideDown(500);
        	  			}
        	  	});
	      $.each(data.days,function(index_day,days){
	    	   $("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="day"><h1>'+days.day+'</h1></div>');
	    	   $.each(days.imags,function(index,image){//<a  ><a href="/owncloud/?app=gallery&getfile=ajax%2FviewImage.php?img=%252F'+image+'" title="'+image+'" rel="images"></a>
	    		   															
	    		   	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+")").append('<a><img src="'+image.imagstmp+'"  alt="'+image.imagsname+'"></a>');
	    		 	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+") a img:eq("+index+")").click(function(event){
	    		 		$('#photoview_load').show();//owncloud/?app=gallery&getfile=ajax%2FviewImage.php?img=
	    				$('#photoview img').attr("src", OC.linkTo('gallery', 'ajax/viewImage.php')+'?img=/'+this.alt);
	    				$('#photoview img').attr("name", this.alt);
	    				/*$('#photoview img').mousemove(function(e) {
	    					//$('#photoview img').(e.pageX +', '+ e.pageY);
	    					alert($('#photoview img').position().left+"-"+$('#photoview img').position().top);
	    				});*/
	    				$('#photoview img').load(function(){
	    					$('#photoview_load').hide();
	    				});
	    				$("#taggs div").remove();
	    				$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+this.alt, function(data) {
	    					$.each(data,function(index_tag,data){
	    						$('#taggs').append('<div class="tag"><img alt="" src="/owncloud/apps/facefinder/img/delete.png" name="'+data.tag+'">'+data.name+' '+data.tag+"</div>");
	    						$('#taggs div.tag img').click(function(){
	    							var image=$('#photoview img').attr("name");
	    							var tag=$(this).attr("name");
	    							 $(this).parent().remove();
	    							 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag, function(data) {});
	    						
	    						});
	    					});
	    				});
	    				$("#tool_taggs textarea").val("");
	    				$('#photoview img').ready(function(){
	    					$('#photoview img').show();
	    				});
	    				$('#new_1').hide();
	    				$('#photoview').show();
	    		 	});
	    	   });
	       });
	        });
	   		});;
        });

	$('div.tool_title').click(function(){
		if( $(this).parent().children("div.tool_items").is(":visible")){
			$(this).parent().children("div.tool_items").slideUp(500);
		}else{
			$(this).parent().children("div.tool_items").slideDown(500);
		}
	});

	$(document).keypress(function(e) {
	   if ( e.keyCode== 27){
	      $('#photoview').hide();
	      $('#new_1').show();
	   }
	});
	
	  $("#tool_taggs textarea").keyup(function(e){
		  if ( e.keyCode== 13){
		   var tag=$("#tool_taggs textarea").val();
		   $("#tool_taggs textarea"). val("");
			   var image=$('#photoview img').attr("name");
			   $.getJSON(OC.linkTo('facefinder', 'ajax/inserttag.php')+"?image="+image+"&tag="+tag, function(data) {
				   $("#taggs div").remove();
					$.getJSON(OC.linkTo('facefinder', 'ajax/tag.php')+'?image='+image, function(data) {
						$.each(data,function(index_tag,data){
							$('#taggs').append('<div class="tag"><img alt="" src="/owncloud/apps/facefinder/img/delete.png" name="'+data.tag+'">'+data.name+' '+data.tag+"</div>");
							
							$('#taggs div.tag img').click(function(){
								var image=$('#photoview img').attr("name");
								var tag=$(this).attr("name");
								 $(this).parent().remove();
								 $.getJSON(OC.linkTo('facefinder', 'ajax/removetag.php')+"?image="+image+"&tag="+tag+"&name="+data.name, function(data) {});
							});
						});
					});
			   });
			 
		  }
			   });


	});


	