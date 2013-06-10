$(document).ready(function() {
	PhotoView.load();
	FaceFinder.load();
	$('#tool_righte').hide();
	$("span.right").append('<button class="back" style=""><i class="icon-th"></i> Back</button>');
	$('button.back').hide();
	$("button.back").click(function(e){
		goBack(e);
	})
	$(document).keypress(function(e) {
		if (e.keyCode == 27) {
			goBack(e);
		}
	});

});




var PhotoView={
		load:function(){
			$('#photoview').hide();
		},
		ClickImg:function(event){
 			$('span.right select').hide();
 			$('span.right input').hide();
			$('button.back').show();
			$('#tool_righte .tool_items').slideDown(1000);
			//set PhotoView visible
	 		$('#photo').addClass('loading');
	 		$('#photo img').remove()
			
	 		$.getJSON(OC.linkTo('facefinder', 'ajax/photoview.php')+'?id='+event.alt, function(data) {
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
	location.href = "#" + $('#photo img').attr("name");
	e.preventDefault();
}
