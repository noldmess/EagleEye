$(document).ready(function(){
	if(typeof Gallery!=='undefined'){
		$('.right').append('<button class="facefinder" style="display: none;">facefinder</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.facefinder').click(function(){
		 		window.location = OC.linkTo('facefinder', 'index.php')+'?dir='+encodeURIComponent(Gallery.currentAlbum).replace(/%2F/g, '/');
	   });
		 $('button.facefinder').show();
	}
	
	if (typeof FileActions !== 'undefined') {
		FileActions.register('dir', 'Facefinder', OC.PERMISSION_DELETE, function () {
			//image for action 
		return OC.imagePath('facefinder', 'lcg.png');
		}, function (filename) {
			//action 
			window.location = OC.linkTo('facefinder', 'index.php')+'?dir='+ encodeURIComponent($('#dir').val()).replace(/%2F/g, '/')+ '/' + encodeURIComponent(filename);
		});
		FileActions.register('image/jpeg', 'Facefinder', OC.PERMISSION_DELETE, function () {
			//image for action 
			return OC.imagePath('facefinder', 'lcg.png');
		}, function (filename) {
			//action 
			var text=encodeURIComponent($('#dir').val()).replace(/%2F/g, '/');
			if(text.length<=1)
				text="";
			//
			$.getJSON(OC.linkTo('facefinder', 'ajax/loadphotoview.php')+'?image='+text+'/'+filename, function(data) {
				window.location = OC.linkTo('facefinder', 'index.php')+'?dir='+ encodeURIComponent($('#dir').val()).replace(/%2F/g, '/')+ '/#photoview/'+data.data.id;
			});
		});
			
	}
	
});