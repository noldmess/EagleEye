$(document).ready(function() {
	$('#duplicate').hide();
	$("span.right select").append('<option value="duplicates">Duplicates</option>');
	$('option[value="duplicates"]').click(function(e){
		Duplicatits.load();
	});
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
			this.get();
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
						$("#duplicate table #data").append('<tr><td>'+data.prozent+'</td><td>'+img1.path+'</td><td><table><tr><td>'+img1.height+'</td><td>Height</td></tr><tr><td>'+img1.width+'</td><td>Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img1.filesize)+'</td><td>Size</td></tr></table></td><td><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img1.path+'" alt="'+img1.photo_id+'"></td><td><a><i class="icon-info-sign"></i></a></td><td><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img2.path+'" alt="'+img2.photo_id+'"></td> <td>'+img2.path+'</td><td><table><tr><td>'+img2.height+'</td><td>Height</td></tr><tr><td>'+img2.width+'</td><td>Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img2.filesize)+'</td><td>Size</td></tr></table></td></tr>');
						//.append('<table><tbody><tr><td>'+img1.height+'</td><td>'+img1.width+'</td><td>'+img1.filesize+'</td></tr></tbody></table></tr>');
					});
					$("#duplicate table tbody i.icon-info-sign").click(function(e){
						Duplicatits.loadPopupBox(e,this);
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