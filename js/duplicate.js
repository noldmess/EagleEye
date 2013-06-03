$(document).ready(function() {
	$('#duplicate').hide();
	$("span.right").append('<button class="duplicatits" style="">  Defect duplicatits  </button>');
	$("button.duplicatits").click(function(e){
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
							$("#duplicate table tbody").append('<tr><td><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img1.path+'" alt="'+img1.photo_id+'"></td> <td>'+img1.path+'</td><td>'+img1.height+'x'+img1.width+' '+img1.filesize+'</td><td>'+data.prozent+'</td><td><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img2.path+'" alt="'+img2.photo_id+'"></td> <td>'+img2.path+'</td><td>'+img2.height+'x'+img2.width+' '+img2.filesize+'</td><td><a><i class="icon-info-sign"></i></a></td> </tr>');
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
	    	  $("#content-wrapper").css({ // this is just for style
		            "opacity": "1" 
		        })
	        $('#fancybox-tmp').fadeOut("fast");

	       
	      
	    },
	    loadPopupBox:function (e,t) {// To Load the Popupbox
	    		var test =$(t).parent().parent().parent();
	    		var img_alt1 =$($(test).children()[0]).children().attr('alt');
	    		var img_alt2 =$($(test).children()[4]).children().attr('alt');

	    	  $("nav").css({ // this is just for style
		            "opacity": "0.3" 
		        });
	    	  $("header").css({ // this is just for style
		            "opacity": "0.3" 
		        });
	    	  $("#content-wrapper").css({ // this is just for style
		            "opacity": "0.3" 
		        })
	        $('#fancybox-tmp').fadeIn("fast");
	    	
	 
	        
	    }       
}