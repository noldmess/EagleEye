$(document).ready(function() {
	$("#controls").append('<span class="right"><a title="Remove" ><button class="remove">Remove</button></a></span>');
	$("button.remove").click(function(){
		var s=$("img[checked]");
		$.each(s,function(index_yeaffr,data){
			  // $.getJSON(OC.linkTo('facefinder', 'ajax/removeequivalent.php')+"?img="+data.img_eq, function(data) {}); 
			if($(data).val()==="1"){
				
			//alert($(data).attr("alt"));
			 var div_equal=$(data).parent().parent();
			  var equival=$(div_equal).parent();
			if($(div_equal).find("a ").length==1){
				$(div_equal).remove();
			}
			if($(equival).find("div.equival_photos").length==0){
			 $(equival).remove();
			}
			
			$(data).parent().remove();
			//  $( this ).dialog( "close" );
			}
		});
		});
	$('#equivalent').addClass('loading');
	$.getJSON(OC.linkTo('facefinder', 'ajax/equivalent.php'), function(data) {

		$.each(data,function(index_year,data){

			$("#equivalent").append('<div class="equival"><div class="equival_value">Equal</div><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data.img+'" alt="'+data.img+'"></div>');
			var tmp_value=1000;
			var help=0;
			
			$.each(data.array,function(index_yeaffr,data){
				  if(tmp_value>data.value){
					  help++;
					  tmp_value=data.value;
					  $("#equivalent div.equival:eq("+index_year+")").append('<div class="equival_photos"><div class="equival_value">'+tmp_value+'</div></div>');
				  }
				  
				  $("#equivalent div.equival:eq("+index_year+") div.equival_photos:eq("+(help-1)+") ").append('<a><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data.img_eq+'" alt="'+data.img_eq+'"></a>');
					  $("#equivalent div.equival:eq("+index_year+") div.equival_photos a img:eq("+index_yeaffr+")").click(function(){;
							 if($(this).val()!=="1"){
							// $(this).hide();
							 $(this).addClass('remove');
							 $(this).val("1")
							 }else{
								 $(this).removeClass('remove');
								 $(this).val("0") 
							 }
						  /*var img=this;
						  $( "#dialog-confirm" ).attr('title', "Remove Photo"+data.img_eq);
						  Dialog(img);*/
					  });

			});
			
		});
		if(data.length==0){
			$("#equivalent").append('<div class="equival_value">NO Equal Photos</div>');
		}
		$('#equivalent').removeClass('loading');
	});
});

 function Dialog(img){
	  $( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:100,
		  width: 400,
		  modal: true,
		  buttons: {
			  "Delete  Photo": function() {
				  // $.getJSON(OC.linkTo('facefinder', 'ajax/removeequivalent.php')+"?img="+data.img_eq, function(data) {}); 
				  var div_equal=$(img).parent().parent();
				  var equival=$(div_equal).parent();
				if($(div_equal).find("a ").length==1){
					$(div_equal).remove();
				}
				if($(equival).find("div.equival_photos").length==0){
				 $(equival).remove();
				}
				
				  $(img).parent().remove();
				  $( this ).dialog( "close" );
			  },
			  Cancel: function() {
				  $( this ).dialog( "close" );
			  }
		  }
	 });
}
