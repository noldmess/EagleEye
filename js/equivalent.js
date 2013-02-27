$(document).ready(function() {
	$('#equivalent').addClass('loading');
	$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {

		$.each(data,function(index_year,data){

			$("#equivalent").append('<div class="equival"><div class="equival_value">Equal</div><a><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data.img+'" alt="'+data.img+'"></a></div>');
			//alert(data.img);
			var tmp_value=1000;
			$.each(data.array,function(index_yeaffr,data){
				
				  if(tmp_value>data.value){
					  tmp_value=data.value;
					  $("#equivalent div.equival:eq("+index_year+")").append('<div class="equival_value">'+tmp_value+'</div><div>');
				  }
			  		
				  $("#equivalent div.equival:eq("+index_year+")").append('<a><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data.img_eq+'" alt="'+data.img_eq+'"></a>').click(function(){
	    		 		alert("asfdsdfd");    		 	
	    	   });
				//alert(data.img_eq+"-"+data.value);
			});
			$('#equivalent').removeClass('loading');
		});
	});
});
