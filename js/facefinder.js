$(document).ready(function() {
	$("button.time").click(function(e){
		FaceFinder.test("");
	});
});
var FaceFinder={
		/*tag.sidebar=function(tags){
			$.each(tags,function(index_tag,elemet){
				 $('#tool_right ul').append('<li id="'+index_tag+'" class="">'+index_tag+'('+elemet+')</li>');
				 $('#tool_right ul li[id^="'+index_tag+'"]').click(function(){
					 tag.loadImagesTag(this)
			 	});
			});
		}*/
		test:function(data){
			 $('#photoOverView * ').remove();
			$('#tool_right ul * ').remove();
			$('#photoOverView').addClass('loading');
			   $.getJSON(OC.linkTo('facefinder', 'ajax/new_1.php')+"?dir="+FaceFinder.getPath(), function(data) {
				   if (data.status == 'success'){
					   FaceFinder.addYearSidebar(data.data);
					   FaceFinder.addYearPhotoOverView(data.data);
				   		$('#photoOverView').removeClass('loading');
				   		var test=$('#tool_right li');
				   				test.click(function(e) {
				   					FaceFinder.loadData(e,this);
				   				});
				   }
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
	},
	getPath:function(){
		var list=$('.crumb');
		var path;
		$.each(list,function(index_tag,elemet){
			path=elemet.title;
		});
		return path
	},
	
	
	addYearSidebar:function(data){
		$.each(data,function(index_year,data){
	   		$('#tool_right ul.start').append('<li id="'+data.year+'" class="year2">'+data.year+'('+data.number+')<ul></ul></li>');
	   		FaceFinder.addMonthSidebar(data.month,index_year);
		});
	},
	addMonthSidebar:function(data,index_year){
		$.each(data,function(index_month,data){
				$('#tool_right ul li.year2:eq('+index_year+') ul[class!="month2"]').append('<li  class="month2" id="'+data.monthnumber+'">'+data.month+'('+data.number+')<ul class="month2"></ul></li>');
    	  		FaceFinder.addDaySidebar(data.days,index_year,index_month);
			});
	},
	addDaySidebar:function(data,index_year,index_month){
		$.each(data,function(index_day,days){
				$('#tool_right ul li.year2:eq('+index_year+') ul li.month2:eq('+index_month+') ul').append('<li id="'+days.day+'" class="day">'+days.day+'('+days.number+')</li>');
		});
	},
	addYearPhotoOverView:function(data){
		$.each(data,function(index_year,data){
	   		FaceFinder.addMonthPhotoOverView(data.month);
   		});
	},
	addMonthPhotoOverView:function(data){
		$.each(data,function(index_month,data){
    	  		FaceFinder.addDayPhotoOverView(data.days);
			});
	},
	addDayPhotoOverView:function(data){
		$.each(data,function(index_day,days){
		   //$("#photoOverView div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="day"><h1>'+days.day+'</h1></div>');
		   $.each(days.imags,function(index,image){
			   //$("#photoOverView div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+")").append('<a name="'+image.imagsname+'"></a><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"><input type="checkbox" original-title=""></input>');
			   $("#photoOverView").append('<div class="image" ><a name="'+image.imagsname+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"  name="'+image.imagsname+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div>');
			 	$('#photoOverView  img[alt="'+image.imagsid+'"]').click(function(){
			 			PhotoView.ClickImg(this)});
		   });
		});
	},
	addDataPhotoOverView:function(data){
		$.each(data,function(index_day,image){
		   //$("#photoOverView div.year:eq("+index_year+") div.month:eq("+index_month+")").append('<div class="day"><h1>'+days.day+'</h1></div>');
			   //$("#photoOverView div.year:eq("+index_year+") div.month:eq("+index_month+") div.day:eq("+index_day+")").append('<a name="'+image.imagsname+'"></a><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"><input type="checkbox" original-title=""></input>');
			   $("#photoOverView").append('<div class="image" ><a name="'+image.imagsname+'"><img name="'+image.imagsname+'" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div>');
			 	$('#photoOverView  img[alt="'+image.imagsid+'"]').click(function(){
			 			PhotoView.ClickImg(this)});
		});
	},
	loadData:function(e,t){
			var daynumder=null;
			var monthnumder=null;
			var yearnumder=null;
			var day=$(t);
			if(day.hasClass('day')){
				daynumder=day.attr('id');
				
				var month =day.parent().parent();
			}else{
				month=day;
			}
			if(month.hasClass('month2')){
				monthnumder=month.attr('id');
				var year =month.parent().parent();
			}else{
				year=month;
			}
			if(year.hasClass('year2'))
				yearnumder=year.attr('id');
			$('#tool_right *').removeClass('active');
			$(t).addClass('active');
			$.getJSON(OC.linkTo('facefinder', 'ajax/PhotoByData.php')+"?dir="+FaceFinder.getPath()+"&year="+yearnumder+"&month="+monthnumder+"&day="+daynumder, function(data) {
				   if (data.status == 'success'){
				   		//Create the year divs 
					   $('#photoOverView * ').remove();
					   FaceFinder.addDataPhotoOverView(data.data);
				   		$('#photoOverView').removeClass('loading');
				   		
				   }
		        });
			e.stopPropagation();
	}
	
}

