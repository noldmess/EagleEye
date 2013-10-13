$(document).ready(function(){
	if(typeof Gallery!=='undefined'){
		$('.right').append('<button class="EagleEye" style="display: none;">EagleEye</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.EagleEye').click(function(){
		 		var params = {dir: +encodeURIComponent(Gallery.currentAlbum).replace(/%2F/g, '/')};
				url= OC.Router.generate('EagleEye',params);
				window.location = url;
	   });
		 $('button.EagleEye').show();
	}
	
	if (typeof FileActions !== 'undefined') {
		FileActions.register('dir', 'EagleEye', OC.PERMISSION_DELETE, function () {
			//image for action 
		return OC.imagePath('EagleEye', 'EagleEye.png');
		}, function (filename) {
			//action 
			var params = {dir: encodeURIComponent($('#dir').val()).replace(/%2F/g, '/').substr(1)+encodeURIComponent(filename)};
			url= OC.Router.generate('EagleEye',params);
			window.location = url;
		});
		FileActions.register('image/jpeg', 'EagleEye', OC.PERMISSION_DELETE, function () {
			//image for action 
			return OC.imagePath('EagleEye', 'EagleEye.png');
		}, function (filename) {
			//action 
			var text=encodeURIComponent($('#dir').val()).replace(/%2F/g, '/');
			if(text.length<=1)
				text="";
			//
			$.getJSON(OC.linkTo('EagleEye', 'ajax/loadphotoview.php')+'?image='+text+'/'+filename, function(data) {
				var params = {dir: encodeURIComponent($('#dir').val()).replace(/%2F/g, '/').substr(1)};
				url= OC.Router.generate('EagleEye',params);
				window.location = url+'#photoview/'+data.data.id;
			});
		});
			
	}
	
});