var PhotoView={
		init:function(){
			$('#photoview').hide();
			$('#tool_righte').hide();
			$("span.right").append('<a href=""><button class="back" style=""><i class="icon-th"></i> Back</button></a>');
			$('button.back').hide();
			/*$("button.back").click(function(e){
				goBack(e);
			})*/
			$(document).keypress(function(e) {
				if (e.keyCode == 27) {
					goBack(e);
				}
			});
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
	 		$.getJSON(OC.linkTo('facefinder', 'ajax/photoview.php')+'?id='+parseInt(event[1]), function(data) {
				if (data.status == 'success'){
					var img = new Image();
					img.src=OC.linkTo('gallery', 'ajax/image.php')+'?file='+oc_current_user+''+data.data.path;
					img.name=data.data.path;
					img.alt=data.data.id;
					img.id="img_img";
					$(img).load(function(){
						$(this).hide();
						Module.load(data.data.id);
						$('#photo').append(this);
						 $(this).fadeIn();
							$('#photo').removeClass('loading');
					})
					.error(function () {
							alert("Error")   
					});
					
					$('#photoview img').ready(function(){
						$('#photoview img').show();
					});
				}
				//TODO problem!!!!!!!
				//nicht in funktion!
				if(helpold2[0]===""){
					helpold2[0]="facefinder";
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
	//location.href = "#facefinder" + ;
	window.history.pushState({path:"#facefinder" + $('#photo img').attr("name")},"","#facefinder" + $('#photo img').attr("name"));
	e.preventDefault();
}
