$(document).ready(function(){
	if(typeof Gallery!=='undefined'){
		$('.right').append('<button class="facefinder" style="display: none;">facefinder</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.facefinder').click(function(){
		 		var test="index.php"+OC.linkTo('facefinder', '/')+'?dir='+Gallery.currentAlbum;
		 		window.location = test;
	   });
		 $('button.facefinder').show();
	}
	
	/*if(typeof FileList!=='undefined'){
		$('.actions ').append('<button class="facefinder" style="display: none;">facefinder</button><a class="share" data-possible-permissions="31" title="Teilen" data-item="" data-item-type="gallery"></a>');
		 	$('button.facefinder').click(function(){
		 		var test=$('div.crumb last svg a');
		 		alert(test);
		 		//window.location = OC.linkTo('facefinder', '/')+'?dir='+Gallery.currentAlbum;
	   });
		 $('button.facefinder').show();
	}*/
});