




var PhotoView={
		load:function(){
			$('#photoview').hide();
		},
		ClickImg:function(event){
			
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
						$('#photo').removeClass('loading').append(this);
						 $(this).fadeIn();
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
