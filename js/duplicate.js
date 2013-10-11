var Duplicatits={
		init:function(){
			$('#duplicate').hide();
			$('span.right select[title="Select view"]').append('<option value="duplicates">Finde Duplicates</option>');
			$('option[value="duplicates"]').click(function(e){
				window.history.pushState({path:"duplicatits"},"","#duplicatits");
			});
			
			$("span.right ").append('<button class="remove" title="Remove">Remove (0)</button><input type="hidden" name="removecount">');
			//TODO add size regulator
			//$("span.right ").append('<select title="duplicatitsNumber" style="display: inline-block;"></select>');
			$('button[title="Remove"]').click(function(e){
				Duplicatits.remove();
			});
			$('button[title="Remove"]').hide();
			  $('#fancybox-tmp').append('<a id="popupBoxClose"></a>');
		   	  $('#popupBoxClose').click( function() {           
			    	Duplicatits.unloadPopupBox();
			    });
		},
		hideView:function (event){
        	$('#duplicate').hide();
        	$("span.right button.remove").hide();
		},
		showView:function (event){
			$('#photoff').show();
 			$('span.right select').show();
 			$('span.right label').show();
 			$('span.right input').show();
		},
		load:function(data){
    		$("button[title='Remove']").text("Remove (0)");
    		$("button[title='Remove']").removeClass("btn btn-warning");
			$('span.right select[title="Select view"]').val('duplicates');
			$('#photoff').hide();
			$('#photoview').hide();
			$('#duplicate').show();
			$('button[title="Remove"]').show();
			this.get(data);
		},
		remove:function(){
			$.each($('#duplicate table input[name="remove"]'),function(imgID, value){
				var dasd=imgID;
				if($(value).attr('value')!==undefined){
					$.getJSON(OC.linkTo('EagleEye', 'ajax/removeequivalent.php')+"?img="+$(value).attr('value'), function(data) {
						if (data.status == 'success'){
							$(value).parent().parent().remove();
							$.each($('#duplicate table img[alt="'+$(value).attr('value')+'"]'),function(imgID, value){
								$(value).parent().parent().parent().remove();
							});
							}
						
					});
				}
			});
			var counter=$("input[name='removecount']").attr("value",0);
			$("button[title='Remove']").text("Remove (0)");
			$("button[title='Remove']").removeClass("btn btn-warning");
		},
		removeCounterAdd:function(){
			var counter=$("input[name='removecount']").attr("value");
				$("input[name='removecount']").attr("value",++counter);
				$("button[title='Remove']").text("Remove ("+counter+")");
				$("button[title='Remove']").addClass("btn btn-warning");
		},
		removeCounterRemove:function(){
			var counter=$("input[name='removecount']").attr("value");
				$("input[name='removecount']").attr("value",--counter);
				$("button[title='Remove']").text("Remove ("+counter+")");
				if(counter===0)
				$("button[title='Remove']").removeClass("btn btn-warning");
				
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
		get:function(data){
			$("#duplicate table tbody").children().remove();
			$("#duplicate").addClass('loading');
			var page=parseInt(data[1]);
			if(isNaN(page))
				page=0;
			$.getJSON(OC.linkTo('EagleEye', 'ajax/equivalent.php')+"?dir="+Duplicatits.getPath()+"&page="+page,function(data) {
				if (data.status == 'success'){
					if(data.data.length!==0){
							$.each(data.data,function(index_year,data){
								var img1=data[0];
								var img2=data[1];
								var path1_length=img1.path.length-30;
								if(path1_length<0)
									path1_length=0;
								var path2_length=img2.path.length-30;
								if(path2_length<0)
									path2_length=0;
								$("#duplicate table #data").append('<tr class="line"><td><p class="path" alt="'+img1.path+'">'+img1.path.substr(path1_length)+'</p></td><td><table><tr><td>'+img1.height+' px Height</td></tr><tr><td>'+img1.width+' px Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img1.filesize)+'</td><td></td></tr></table></td><td><div class="image"><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img1.path+'" alt="'+img1.photo_id+'"></div></td><td><a><button><img checked="checked" src="'+OC.linkTo('EagleEye', 'img/scale.gif')+'"><br>'+Math.round(data.prozent*100)+'%</button><br/></a><input type="hidden" name="remove"></td><td><div class="image"><img checked="checked" src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+img2.path+'" alt="'+img2.photo_id+'"></div></td> <td><p class="path" alt="'+img2.path+'">'+img2.path.substr(path2_length)+'</td><td><table><tr><td>'+img2.height+' px Height</td></tr><tr><td>'+img2.width+' px  Width</td><tr><td>'+Duplicatits.getReadableFileSizeString(img2.filesize)+'</p></td><td></td></tr></table></td></tr>');
								//.append('<table><tbody><tr><td>'+img1.height+'</td><td>'+img1.width+'</td><td>'+img1.filesize+'</td></tr></tbody></table></tr>');
							});
							//show entire paht
							$('p.path').hover(function(){
						        // Hover over code
						        var title = $(this).attr('alt');
						        $(this).data('tipText', title).removeAttr('title');
						        $('<p class="tooltip"></p>')
						        .text(title)
						        .appendTo('body')
						        .fadeIn('fast');
							}, function() {
							        // Hover out code
							        $(this).attr('title', $(this).data('tipText'));
							        $('.tooltip').remove();
							}).mousemove(function(e) {
							        var mousex = e.pageX + 20; //Get X coordinates
							        var mousey = e.pageY + 10; //Get Y coordinates
							        $('.tooltip')
							        .css({ top: mousey, left: mousex })
							});
							//show entire paht
							//show Box
							$("#duplicate table tbody button").click(function(e){
								Duplicatits.loadPopupBox(e,this);
							});
							//remove Img
				    		$('#duplicate table img').click(function(e){
								var id=$(this).attr('alt')
								$(this).parent().parent().parent().find('img').css({ // this is just for style
						            "opacity": "1" 
						        });
								$(this).parent().parent().parent().find('img').parent().removeClass("inTrasch");
								var sdfsdfds=$(this).parent().parent().parent().find('img').parent();
								$(this).css({ // this is just for style
						            "opacity": "0.3" 
						        });
								
									var ds=$($($(this).parent().parent().parent()).children()[3]);
									var sdfd=$(ds).children('input');
									var input_id=$($($(this).parent().parent().parent()).children()[3]).children('input').attr('value');
									$($($(this).parent().parent().parent()).children()[3]).children('input').attr('value',id);
									if(input_id==id){
										
									$(this).css({ 
							            "opacity": "1"       	
							        });
									Duplicatits.removeCounterRemove();
									$($($(this).parent().parent().parent()).children()[3]).children('input').attr('value','');
								}else{
									$(this).parent().addClass("inTrasch");
									if(input_id==='')
									Duplicatits.removeCounterAdd();
								}
						        });
					}else{
						$("#duplicate table thead").remove();
					
						$("#duplicate table tfoot").html("<tr><th>No duplicates images</th></tr>");
					}
					
				}
				$("#duplicate").removeClass('loading');
				//number of image pro page 
				var imgProPage=20;
				var numperPages=Math.ceil(data.size/imgProPage);
				$("#duplicate table tfoot tr td div.pagination ul *").remove();
				for ( var page = 0; page <numperPages ; page++) {
					$("#duplicate table tfoot tr td div.pagination ul").append('<li><a href="#duplicatits/'+page+'">'+page+'</a></li>');
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
	    		var img_alt1 =$($(test).children()[2]).find('img').attr('alt');
	    		var img_alt2 =$($(test).children()[4]).find('img').attr('alt');
	    		var line=$(t).parent().parent().parent().index();
	    		$("#fancybox-tmp input").attr('value',line);
	    		$.getJSON(OC.linkTo('EagleEye', 'ajax/pairduplicates.php')+'?image1='+img_alt1+'&image2='+img_alt2, function(data) {
	    			$("#fancybox-tmp div").append('<table class="table table-hover"><thead><tr><th>Name</th><th>Image 1</th><th>Similarity </th><th>Image 2</th></tr></thead> <tbody></tbody></table>');
	    			Module.duplicatits($("#fancybox-tmp tbody"),data);
	    			$('#fancybox-tmp').fadeIn("fast");
	    			$('#fancybox-tmp table img').click(function(e){
						var id=$(this).attr('alt')
							var start=$("#data tr.line:eq("+$("#fancybox-tmp input").attr('value')+")");
//							$($(start).children()[2]).find('img').css({ // this is just for style
//					            "opacity": "1" 
//					        });*/
							$(start).find('img').css({ // this is just for style
					            "opacity": "1" 
					        });
							$(start).find('img').parent().removeClass("inTrasch");
							$(start).find('img[alt="'+id+'"]').parent().addClass("inTrasch");
							$(start).find('img[alt="'+id+'"]').css({ // this is just for style
					            "opacity": "0.3" 
					        });
							var input_id=$($(start).children()[3]).find('input').attr('value');
							$($(start).children()[3]).find('input').attr('value',id);
							if(input_id===''){
								Duplicatits.removeCounterAdd();
							}
							Duplicatits.unloadPopupBox();
				        });
				        
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
	        
	    }       
}