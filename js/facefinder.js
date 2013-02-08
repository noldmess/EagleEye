var FaceFinder={
		
		test:function(){
			   $.getJSON(OC.linkTo('facefinder', 'ajax/new_1.php'), function(data) {
			   		//Create the year divs 
			   		$.each(data,function(index_year,data){
			   		$("#new_1").append('<div class="year"></div>');
			   		$("div.year:eq("+index_year+")").append('<div class="head_line"><div class="title_head">'+data.year+'</div><div class="ico_inline"></div></div>');
			   		$("#new_1 div.year:eq("+index_year+")  div.head_line").click(function() {
			   			FaceFinder.slide_month(this);
		
			   		});
			   		$.each(data.month,function(index_month,data){
			   			$("#new_1 div.year:eq("+index_year+")").append('<div class="month"></div>');
			   			$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="head_line"><div class="title_head">'+data.month+'</div><div class="ico_inline"></div></div>');
		        	  	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.head_line").click(function() {
		        	  		FaceFinder.slide_day(this);
		        	  	});
			      $.each(data.days,function(index_day,days){
			    	   $("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="day"><h1>'+days.day+'</h1></div>');
			    	   $.each(days.imags,function(index,image){//<a  ><a href="/owncloud/?app=gallery&getfile=ajax%2FviewImage.php?img=%252F'+image+'" title="'+image+'" rel="images"></a>
			    		   															
			    		   	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+")").append('<a><img src="'+image.imagstmp+'"  alt="'+image.imagsname+'"></a>');
			    		 	$("#new_1 div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+") a img:eq("+index+")").click(function(){
			    		 		PhotoView.ClickImg(this)});
			    		 	
			    	   });
			       });
			        });
			   		});
		        });
		},

	slide_day:function(div){
		if( $(div).parent().children("div.day").is(":visible")){
				$(div).children("div.ico_inline").removeClass().addClass("ico_down");
				$(div).parent().children("div.day").slideUp(500);
			}else{
				$(div).children("div.ico_down").removeClass().addClass("ico_inline");
				$(div).parent().children("div.day").slideDown(500);
			}
	},
	slide_month:function(div){
		if( $(div).parent().children("div.month").is(":visible")){
				$(div).children("div.ico_inline").removeClass().addClass("ico_down");
				$(div).parent().children("div.month").slideUp(500);
			}else{
				$(div).children("div.ico_down").removeClass().addClass("ico_inline");
				$(div).parent().children("div.month").slideDown(500);
			}
	}
		
}