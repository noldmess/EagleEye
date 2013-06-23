$(document).ready(function() {
	$('option[value="time"]').click(function(e){
		$('#duplicate').hide();
		$('button.remove').hide();
		$('#photoff').show();
		FaceFinder.load("");
		Module.resateView();
		
	});
});
var FaceFinder={
		load:function(data){
			//remove old images and sitebare
			$('#photoOverView div.image ').remove();
			$('#tool_right ul * ').remove();
			//loading image
			$('#photoOverView').addClass('loading');
			//get information
			$.getJSON(OC.linkTo('facefinder', 'ajax/new_1.php')+"?dir="+FaceFinder.getPath(), function(data) {
				   if (data.status == 'success'){
					   FaceFinder.addYearSidebar(data.data);
//					   only show first year
					  $('#tool_right ul.start').find('ul').slideToggle();
					 $($( '#tool_right ul.start ul')[0]).find('ul').slideToggle();
					 $($( '#tool_right ul.start ul')[0]).slideToggle();
					 $($( '#tool_right ul.start li i')[0]).removeClass('icon-arrow-down');
					 $($( '#tool_right ul.start li i')[0]).addClass('icon-arrow-up');
//					  only show first year
					   FaceFinder.addYearPhotoOverView(data.data);
				   		$('#tool_right li').click(function(e) {
				   					FaceFinder.loadData(e,this);
				   				});
				   	  $('#tool_right ul.start  i ').click(function (e) {
				   		  var dfssdf=	$(this).parent().parent().find('ul');
				   		$(this).parent().parent().find('ul').slideToggle("slow");
				   		//$(this).parent().parent().find('li').slideToggle("slow");
					        e.stopPropagation();
					        if($(this).hasClass('icon-arrow-up')){
				   			$(this).removeClass('icon-arrow-up');
				   			$(this).addClass('icon-arrow-down');
				   	  }else{
				   		$(this).addClass('icon-arrow-up');
			   			$(this).removeClass('icon-arrow-down');
				   	  }
					    });
				   }
				   $('#photoOverView').removeClass('loading');
		     });
				 
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
	   		$('#tool_right ul.start').append('<li id="'+data.year+'" class="year2">'+data.year+' ('+data.number+')<a><i class="icon-white icon-arrow-down"></i></a><ul></ul></li>');
	   		FaceFinder.addMonthSidebar(data.month,index_year);
		});
	},
	addMonthSidebar:function(data,index_year){
		$.each(data,function(index_month,data){
				$('#tool_right ul li.year2:eq('+index_year+') ul[class!="month2"]').append('<li  class="month2" id="'+data.monthnumber+'">'+data.month+' ('+data.number+')<ul class="month2"></ul></li>');
    	  		FaceFinder.addDaySidebar(data.days,index_year,index_month);
			});
	},
	addDaySidebar:function(data,index_year,index_month){
		$.each(data,function(index_day,days){
				$('#tool_right ul li.year2:eq('+index_year+') ul li.month2:eq('+index_month+') ul').append('<li id="'+days.day+'" class="day">'+days.day+' ('+days.number+')</li>');
		});
	},
	addYearPhotoOverView:function(data){
		$.each(data,function(index_year,data){
	   		FaceFinder.addMonthPhotoOverView(data.month);
   		});
		Module.setEvents();
	},
	addMonthPhotoOverView:function(data){
		$.each(data,function(index_month,data){
    	  		FaceFinder.addDayPhotoOverView(data.days);
			});
	},
	addDayPhotoOverView:function(data){
		$.each(data,function(index_day,days){
		   $.each(days.imags,function(index,image){
			   $("#photoOverView").append('<div class="image" ><div class="test"><a name="'+image.imagsname+'"><img name="'+image.imagsname+'" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div></div>');
			 	$('#photoOverView  img[alt="'+image.imagsid+'"]').click(function(){
			 			PhotoView.ClickImg(this)});
		   });
		
		});

	},
	addDataPhotoOverView:function(data){
		$.each(data,function(index_day,image){
			 $("#photoOverView").append('<div class="image" ><div class="test"><a name="'+image.imagsname+'"><img name="'+image.imagsname+'" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div></div>');
			 $('#photoOverView  img[alt="'+image.imagsid+'"]').click(function(){
				 PhotoView.ClickImg(this)});
			});
		Module.setEvents();
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
	},
	duplicatits:function(element,data){
		var sdfsdf=data['img1']['ff'];
		$(element).append('<tr><td>Image</td><td><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img2']['ff']['path']+'"  alt="'+data['img2']['ff']['photo_id']+'"></td><td>1</td><td><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img1']['ff']['path']+'" alt="'+data['img1']['ff']['photo_id']+'"></td></tr>');
		$(element).append('<tr><td>Path</td><td>'+data['img2']['ff']['path']+'</td><td>1</td><td>'+data['img1']['ff']['path']+'</td></tr>');
		$(element).append('<tr><td>Date</td><td>'+data['img2']['ff']['date_photo']+'</td><td>1</td><td>'+data['img1']['ff']['date_photo']+'</td></tr>');
		$(element).append('<tr><td>Width</td><td>'+data['img2']['ff']['width']+'</td><td>1</td><td>'+data['img1']['ff']['width']+'</td></tr>');
		$(element).append('<tr><td>Height</td><td>'+data['img2']['ff']['height']+'</td><td>1</td><td>'+data['img1']['ff']['height']+'</td></tr>');
		$(element).append('<tr><td>Size</td><td>'+FaceFinder.getReadableFileSizeString(data['img2']['ff']['filesize'])+'</td><td>1</td><td>'+FaceFinder.getReadableFileSizeString(data['img1']['ff']['filesize'])+'</td></tr>');
	},
	getReadableFileSizeString:function (fileSizeInBytes) {

	    var i = -1;
	    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
	    do {
	        fileSizeInBytes = fileSizeInBytes / 1024;
	        i++;
	    } while (fileSizeInBytes > 1024);

	    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
	}
	
}

