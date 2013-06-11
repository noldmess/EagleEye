$(document).ready(function() {
	$('#duplicate').hide();
	$("span.right select").append('<option value="duplicates">Duplicates</option>');
	$('option[value="duplicates"]').click(function(e){
		Duplicatits.load();
	});
	$("span.right ").append('<button title="Remove">Remove</button>');
	$('button[title="Remove"]').click(function(e){
		Duplicatits.remove();
	});
	$('button[title="Remove"]').hide();
	  $('#fancybox-tmp').append('<a id="popupBoxClose">Close</a>');
   	  $('#popupBoxClose').click( function() {           
	    	Duplicatits.unloadPopupBox();
	    });
});


var Duplicatits={
		load:function(){
			$('#photoff').hide();
			$('#photoview').hide();
			$('#duplicate').show();
			$('button[title="Remove"]').show();
			this.get();
		},
		remove:function(){
			$.each($('#duplicate table input[name="remove"]'),function(imgID, value){
				var dasd=imgID;
				if($(value).attr('value')!==undefined){
					$.getJSON(OC.linkTo('facefinder', 'ajax/removeequivalent.php')+"?img="+$(value).attr('value'), function(data) {
						if (data.status == 'success'){
							$(value).parent().parent().remove();
							$.each($('#duplicate table img[alt="'+$(value).attr('value')+'"]'),function(imgID, value){
								$(value).parent().parent().parent().remove();
							});
							}
						
					});
				}
			});
			
		},
		getReadableFileSizeString:function (fileSizeInBytes) {

		    var i = -1;
		    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
		    do {
		        fileSizeInBytes = fileSizeInBytes / 1024;
		        i++;
		    } while (fileSizeInBytes > 1024);

		    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
		},
		getPath:function(){
			var list=$('.crumb');
			var path;
			$.each(list,function(index_tag,elemet){
				path=elemet.title;
			});
			return path
		},
		get:function(){
			$("#duplicate table tbody").children().remove();
			$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php')+"?dir="+Duplicatits.getPath(), function(data) {
				if (data.status == 'success'){
					$.each(data.data,function(index_year,data){
						var img1=data[0];
						var img2=data[1];
						$("#duplicate table #data").append('<tr><td>'+data.prozent+'<input type="hidden" name="remove"></td><td>'+img1.path+'</td><td><table><tr><td>'+img1.height+'</td><td>Height</td></tr><tr><td>'+img1.width+'</td><td>Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img1.filesize)+'</td><td>Size</td></tr></table></td><td><div class="image"><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img1.path+'" alt="'+img1.photo_id+'"></div></td><td><a><i class="icon-info-sign"></i></a></td><td><div class="image"><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img2.path+'" alt="'+img2.photo_id+'"></div></td> <td>'+img2.path+'</td><td><table><tr><td>'+img2.height+'</td><td>Height</td></tr><tr><td>'+img2.width+'</td><td>Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img2.filesize)+'</td><td>Size</td></tr></table></td></tr>');
						//.append('<table><tbody><tr><td>'+img1.height+'</td><td>'+img1.width+'</td><td>'+img1.filesize+'</td></tr></tbody></table></tr>');
					});
					$("#duplicate table tbody i.icon-info-sign").click(function(e){
						Duplicatits.loadPopupBox(e,this);
					});
		    		$('#duplicate table img').click(function(e){
						var id=$(this).attr('alt');
						var dfsdf=$(this).parent().parent().parent().find('img').css({ // this is just for style
				            "opacity": "1" 
				        });
						$(this).css({ // this is just for style
				            "opacity": "0.3" 
				        });
							var ds=$($($(this).parent().parent().parent()).children()[0]).children('input');
							$($($(this).parent().parent().parent()).children()[0]).children('input').attr('value',id);
					});
				}
			});
		},
		unloadPopupBox:function () {    // TO Unload the Popupbox
			 $("nav").css({ // this is just for style
		            "opacity": "1" 
		        });
	    	  $("header").css({ // this is just for style
		            "opacity": "1" 
		        });
		        $("#controls").css({ // this is just for style
		            "opacity": "1" 
		        })
		        $("#photoview").css({ // this is just for style
		            "opacity": "1" 
		        })
		        $("#duplicate").css({ // this is just for style
		            "opacity": "1" 
		        })
	        $('#fancybox-tmp').fadeOut("fast");

	       
	      
	    },
	    loadPopupBox:function (e,t) {// To Load the Popupbox
	    	$("#fancybox-tmp table").remove()
	    		var test =$(t).parent().parent().parent();
	    		var img_alt1 =$($(test).children()[3]).children().attr('alt');
	    		var img_alt2 =$($(test).children()[5]).children().attr('alt');
	    		$.getJSON(OC.linkTo('facefinder', 'ajax/pairduplicates.php')+'?image1='+img_alt1+'&image2='+img_alt2, function(data) {
	    			$("#fancybox-tmp").append('<table class="table table-hover"><thead><tr><th>Name</th><th>Image 1</th><th>Similarity </th><th>Image 2</th></tr></thead> <tbody></tbody></table>');
	    			Module.duplicatits($("#fancybox-tmp tbody"),data);
	    		});
	    	  $("nav").css({ // this is just for style
		            "opacity": "0.3" 
		        });
	    	  $("header").css({ // this is just for style
		            "opacity": "0.3" 
		        });
	    	  $("#controls").css({ // this is just for style
		            "opacity": "0.3" 
		        })
		        $("#photoview").css({ // this is just for style
		            "opacity": "0.3" 
		        })
		        $("#duplicate").css({ // this is just for style
		            "opacity": "0.3" 
		        })
	        $('#fancybox-tmp').fadeIn("fast");
	    	
	 
	        
	    }       
}