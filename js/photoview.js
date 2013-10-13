var PhotoView={
		init:function(){
			$('#photoview').hide();
			$('#tool_righte').hide();
			$("span.right").append('<a href=""><button class="back" style=""><i class="icon-th"></i> Back</button></a>');
			$('button.back').hide();
		},
		hideView:function (event){
			$('button.back').hide();
        	$('#photoview').hide();
		},
		showView:function (event){
			$('#photoff').show();
			$('button.back').show();
        	$('#photoview').show();
		},
		ClickImg:function(event,helpold){
			$('#photoview').hide();
			$('#tool_righte').hide();
			$('#tool_righte .tool_items').slideDown(1000);
			//set PhotoView visible
	 		$('#photo').addClass('loading');
	 		$('#photo img').remove();
	 		helpold2=helpold;
	 		$.getJSON(OC.linkTo('EagleEye', 'ajax/photoview.php')+'?id='+parseInt(event[1]), function(data) {
				if (data.status == 'success'){
					var img = new Image();
					img.src=OC.linkTo('gallery', 'ajax/image.php')+'?file='+oc_current_user+''+data.data.path;
					img.name=data.data.path;
					img.alt=data.data.id;
					img.id="img_img";
					$(img).load(function(){
						$(this).hide();
						$('#photo').append(this);
						 $(this).fadeIn();
							$('#photo').removeClass('loading');
							//
							Module.load(parseInt(event[1]));
					})
					.error(function () {
							OC.Notification.show("Image not found");
							alert();
							 $.getJSON(OC.linkTo('facefinder', 'ajax/removeImageFromDB.php')+"?img="+parseInt(event[1]), function(data) {
								window.history.pushState({path:"#EagleEye"},"","#EagleEye");
								setTimeout(OC.Notification.hide(), 1000);
							 }); 
					});
					
					$('#photoview img').ready(function(){
						$('#photoview img').show();
					});
					
				}
				//TODO problem!!!!!!!
				//nicht in funktion!
				if(helpold2[0]===""){
					helpold2[0]="EagleEye";
				}
				$('span.right a ').attr('href','#'+helpold2[0]+data.data.path);
				
	 		});
			$('#new_1').hide();
			$('#search').hide();
			$('#photoview').show();
		}
};

function goBack(e){
	$('span.right select').show();
	$('button.back').hide();
	$('#photoview').hide();
	$('#photoOverView').show();
	$('#search').show();
	Module.resateView();
	//location.href = "#facefinder" + ;
	window.history.pushState({path:"#EagleEye" + $('#photo img').attr("name")},"","#EagleEye" + $('#photo img').attr("name"));
	e.preventDefault();
}
