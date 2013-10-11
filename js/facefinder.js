/*$(document).ready(function() {
	
});*/
var FaceFinder={
		init:function(){
			$('option[value="time"]').click(function(e){
				window.history.pushState({path:"facefinder"},"","#facefinder");
			});
		},
		hideView:function (){
			$('#photoff').hide();
 			$('span.right select').hide();
 			$('span.right label').hide();
 			$('span.right input').hide();
		},
		showView:function (){
			$('#photoff').show();
 			$('span.right select').show();
 			$('span.right label').show();
 			$('span.right input').show();
		},
		load:function(data){
			//chech if the page is  olrady loadet!
			if($('#tool_right ul li.year2').size()===0){
				//remove old images and sitebare
				$('#photoOverView div.image ').remove();
				$('#tool_right ul * ').remove();
				//loading image
				$('#photoOverView').addClass('loading');
				//get information
				$.getJSON(OC.linkTo('EagleEye', 'ajax/new_1.php')+"?dir="+FaceFinder.getPath(), function(data) {
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
			   					Module.resateView();
			   					FaceFinder.loadData(e,this);
					   		});
					   		
					   	  $('#tool_right ul.start  i ').click(function (e) {
					   		$(this).parent().parent().find('ul').slideToggle("slow");
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
			     }).always(function() {
			    	 //var sdafsdf=$("#"+data[1]).offset().top;
			    	 //$('#photoOverView').scrollTop( $("#"+help[1]).offset().top );  
			     });
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
			   Module.addImagePhotoOverView(image);
			   //$("#photoOverView").append('<div class="image" ><div class="test"><a name="'+image.imagsname+'" href="#photoview/'+image.imagsid+'"><img name="'+image.imagsname+'" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.imagsname+'"  alt="'+image.imagsid+'"></a><input type="checkbox" original-title="" alt="'+image.imagsid+'" ></input></div></div>');
			 /*	$('#photoOverView  img[alt="'+image.imagsid+'"]').click(function(){
			 			//window.history.pushState({path:"photoview"},"","#photoview");
			 			//PhotoView.ClickImg(this)});*/
		   });
		
		});

	},
	addDataPhotoOverView:function(data){
		$.each(data,function(index_day,image){
			Module.addImagePhotoOverView(image);
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
			$.getJSON(OC.linkTo('EagleEye', 'ajax/PhotoByData.php')+"?dir="+FaceFinder.getPath()+"&year="+yearnumder+"&month="+monthnumder+"&day="+daynumder, function(data) {
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
		var icon_hash="";
		if(data['img1']['ff']['hash']==data['img2']['ff']['hash']){
			icon_hash='<i class="icon-equal icon-equal-ok">';
		}else{
			icon_hash='<i class="icon-equal icon-equal-not">';
		}
		$(element).append('<tr><td>Image</td><td><i class="icon-zoom-in"></i><img  class="small" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img1']['ff']['path']+'" title="'+data['img1']['ff']['path']+'" alt="'+data['img1']['ff']['photo_id']+'"></td><td>'+icon_hash+'</i></td><td><i class="icon-zoom-in"></i><img class="small" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+data['img2']['ff']['path']+'"   title="'+data['img2']['ff']['path']+'" alt="'+data['img2']['ff']['photo_id']+'"></td></tr>');
		$('i.icon-zoom-in').hover(function(){
	        // Hover over code
			var title=$(this).parent().find('img').attr('title');
	        $('<p class="tooltip"></p>')
	        .html('<p>Click to remove</p> <img   src="'+OC.linkTo('gallery', 'ajax/image.php')+'?file='+oc_current_user+''+title+'" >')
	        .appendTo('#content')
	        .fadeIn('fast');
	        $('.tooltip').css({ top: 1000, left: 1000 });
		}, function() {
		        // Hover out code
			$('.tooltip').css({ top: 1000, left: 1000 });
		        $('.tooltip').remove();
		        
		}).mousemove(function(e) {
		        var mousex = e.pageX-400; //Get X coordinates
		        var mousey = e.pageY; //Get Y coordinates
		        $('.tooltip').css({ top: mousey, left: mousex });
		});
		$('img.small').hover(function(){
	        // Hover over code
	        var title = $(this).attr('title');
	       //$(this).data('tipText', title).removeAttr('title');
	       var dfsdfsdf=$('body');
	        $('<p class="tooltip"></p>')
	        .html('<p>Click to remove</p> <img   src="'+OC.linkTo('gallery', 'ajax/image.php')+'?file='+oc_current_user+''+title+'" >')
	        .appendTo('#content')
	        .fadeIn('fast');
	        $('.tooltip').css({ top: 1000, left: 1000 });
		}, function() {
		        // Hover out code
			$('.tooltip').css({ top: 1000, left: 1000 });
		        $('.tooltip').remove();
		}).mousemove(function(e) {
		        var mousex = e.pageX-400; //Get X coordinates
		        var mousey = e.pageY; //Get Y coordinates
		        $('.tooltip')
		        .css({ top: mousey, left: mousex })
		});
		$(element).append('<tr><td>Path</td><td>'+data['img1']['ff']['path']+'</td><td><i class="icon-equal icon-equal-not"></td><td>'+data['img2']['ff']['path']+'</td></tr>');
		var icon_data="";
		if(data['img1']['ff']['date_photo']==data['img2']['ff']['date_photo']){
			icon_data='<i class="icon-equal icon-equal-ok">';
		}else{
			icon_data='<i class="icon-equal icon-equal-not">';
		}
		$(element).append('<tr><td>Date</td><td>'+data['img1']['ff']['date_photo']+'</td><td>'+icon_data+'</td><td>'+data['img2']['ff']['date_photo']+'</td></tr>');
		var icon_width="";
		if(data['img1']['ff']['width']==data['img2']['ff']['width']){
			icon_width='<i class="icon-equal icon-equal-ok">';
		}else{
			icon_width='<i class="icon-equal icon-equal-not">';
		}
		$(element).append('<tr><td>Width</td><td>'+data['img1']['ff']['width']+'</td><td>'+icon_width+'</td><td>'+data['img2']['ff']['width']+'</td></tr>');
		var icon_height="";
		if(data['img1']['ff']['width']==data['img2']['ff']['width']){
			icon_height='<i class="icon-equal icon-equal-ok">';
		}else{
			icon_height='<i class="icon-equal icon-equal-not">';
		}
		$(element).append('<tr><td>Height</td><td>'+data['img1']['ff']['height']+'</td><td>'+icon_height+'</td><td>'+data['img2']['ff']['height']+'</td></tr>');
		var icon_size="";
		if(data['img1']['ff']['filesize']==data['img2']['ff']['filesize']){
			icon_size='<i class="icon-equal icon-equal-ok">';
		}else{
			icon_size='<i class="icon-equal icon-equal-not">';
		}
		$(element).append('<tr><td>Size</td><td>'+FaceFinder.getReadableFileSizeString(data['img1']['ff']['filesize'])+'</td><td>'+icon_size+'</td><td>'+FaceFinder.getReadableFileSizeString(data['img2']['ff']['filesize'])+'</td></tr>');
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

