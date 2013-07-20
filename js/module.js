/*$(document).ready(function() {
	setTimeout(function(){
		Module.toolSlide();
		  }, 500);
	
});*/
var Module={
		load:function (event){
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
				if (data.status == 'success'){
					$.each(data.data,function(index_year,data){
							var classload=buildFromJSON(data);
							classload.load(event);
					});
				}
				
			});
		},
		init:function (event){
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			
			//FaceFinder initialise 
			FaceFinder.init();
			//PhotoView initialise 
			PhotoView.init();
			//Dublikates initialise
			Duplicatits.init();
			$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
				if (data.status == 'success'){
					$.each(data.data,function(index_year,data){
							var classload=buildFromJSON(data);
							//classload.load(event);
							classload.init();
					});
				}
				
			});
		},
		hideView:function (event){
			FaceFinder.hideView();
			PhotoView.hideView();
			Duplicatits.hideView();
			$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
				if (data.status == 'success'){
					$.each(data.data,function(index_year,data){
							var classload=buildFromJSON(data);
							classload.hideView();
					});
				}
				
			});
		},
		viewLoader:function (name){
			var classload=buildFromJSON(name);
			classload.click();
			classload.showView();
		},
		allInfo:function (img1,img2){
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
				if (data.status == 'success'){
					$.each(data.data,function(index_year,data){
							var classload=buildFromJSON(data);
							classload.load(event);
					});
				}
				
			});
		},
	toolSlide:function(){
		$('.tool div.tool_title').click(function(){	
			if($(this).parent().children(".tool_items").is(":visible")){
				$(this).parent().children(".tool_items").slideUp(500);
			}else{
				$(this).parent().children(".tool_items").slideDown(500);
			}
		
	        if($(this).children('i').hasClass('icon-arrow-up')){
	   			$(this).children('i').removeClass('icon-arrow-up');
	   			$(this).children('i').addClass('icon-arrow-down');
	   	  }else{
	   		$(this).children('i').addClass('icon-arrow-up');
   			$(this).children('i').removeClass('icon-arrow-down');
	   	  }
		})
	},	
	resateView:function(){
		$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
			if (data.status == 'success'){
				$.each(data.data,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.resat();
				});
			}
			
		});
	},
	setEvents:function(){
		$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
			if (data.status == 'success'){
				$.each(data.data,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.setEvents();
				});
			}
			
		});
	},
	duplicatits:function(element,info){
		FaceFinder.duplicatits(element,info);
		$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
			if (data.status == 'success'){
				$.each(data.data,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.duplicatits(element,info);
				});
			}
			
		});
	}
};




function buildFromJSON(json) {
    var myObj = new this[json]();
    return myObj;
}