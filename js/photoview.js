
var PhotoView={
		load:function(){
			$('#photoview').hide();
		},
		ClickImg:function(event){
			
			//set PhotoView visible
	 
	 		$('#photo').addClass('loading');
	 		$('#photo img').remove()
			var img = new Image();
			img.src=OC.linkTo('gallery', 'ajax/image.php')+'?file='+oc_current_user+'/'+event.alt;
			img.name=event.alt;
			img.id="img_img";
			$(img).load(function(){
				$(this).hide();
				Module.load(event.alt);
				$('#photo').removeClass('loading').append(this);
				 $(this).fadeIn();
			})
			.error(function () {
					alert("dsfsdf")    });
			
			/**
			 * @todo hier modul js für eingehöngte js
			 */
			
			
			$('#photoview img').ready(function(){
				$('#photoview img').show();
			});
			$('#new_1').hide();
			$('#search').hide();
			$('#photoview').show();
		}
};
