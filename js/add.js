$(document).ready(function(){
	if(typeof Gallery!=='undefined'){
		$('.right').append('<button class="facefinder" style="display: none;">facefinder</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.facefinder').click(function(){
		 		window.location = OC.linkTo('facefinder', 'index.php')+'?dir='+Gallery.currentAlbum;
	   });
		 $('button.facefinder').show();
	}
	
	if (typeof FileActions !== 'undefined') {
		FileActions.register('dir', 'facefinder', OC.PERMISSION_DELETE, function () {
			//image for action 
			return OC.imagePath('core', 'actions/rename');
		}, function (filename) {
			//action 
			window.location = OC.linkTo('facefinder', 'index.php')+'?dir='+ encodeURIComponent($('#dir').val()).replace(/%2F/g, '/')+ '/' + encodeURIComponent(filename);
		});
			
	}
});