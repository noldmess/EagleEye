$(document).ready(function(){
	if(typeof Gallery!=='undefined'){
		$('.right').append('<button class="EagleEye" style="display: none;">EagleEye</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.EagleEye').click(function(){
		 		var params = {dir: encodeURIComponent(Gallery.currentAlbum).replace(oc_current_user, '')};
					url= OC.Router.generate('EagleEyeView',params);
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
			if($('#dir').val().length<=1){
				text="";
			}else{
				text="%2F"
			}
			var params = {dir: encodeURIComponent($('#dir').val())+text+encodeURIComponent(filename)};
			url= OC.Router.generate('EagleEyeView',params);
			window.location = url;
		});
		FileActions.register('image/jpeg', 'EagleEyeView', OC.PERMISSION_DELETE, function () {
			//image for action 
			return OC.imagePath('EagleEye', 'EagleEye.png');
		}, function (filename) {
			//action 
			var text=encodeURIComponent($('#dir').val()).replace(/%2F/g, '/');
			if(text.length<=1)
				text="";
			//
			$.getJSON(OC.linkTo('EagleEye', 'ajax/loadphotoview.php')+'?image='+text+'/'+filename, function(data) {
				if($('#dir').val().length<=1){
					text="";
				}else{
					text="%2F"
				}
				var params = {dir: encodeURIComponent($('#dir').val())+text};
				url= OC.Router.generate('EagleEyeView',params);
				window.location = url+ '#photoview/'+data.data.id;
			});
		});
			
	}
	
});