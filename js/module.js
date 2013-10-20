var Module={
		ModuleArray:{},
		
		loadAll:function(callback){
			if(typeof this.ModuleArray.length==="undefined"){
				$.getJSON(OC.linkTo('EagleEye', 'ajax/modules.php'), function(data) {
					Module.ModuleArray=data.data;
					if(callback != undefined && typeof callback == 'function') callback();
				});
			}else{
				if(callback != undefined && typeof callback == 'function') callback();
			}
		},
		load:function (event){
			if($('#tool_righte').is(":visible")){
				$('#tool_righte').show();
			}
			this.loadAll(function(){
				$.each(data,function(index_year,molude){
					var classload=buildFromJSON(molude);
					classload.load(event);
				});
			});
			
		},
		
		init:function (event){
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			this.loadAll(function(){
				//FaceFinder initialise 
				FaceFinder.init();
				//PhotoView initialise 
				PhotoView.init();
				//Dublikates initialise
				Duplicatits.init();
				//$.getJSON(OC.linkTo('EagleEye', 'ajax/modules.php'), function(data) {
				//	if (data.status == 'success'){
						$.each(Module.ModuleArray,function(index_year,data){
								var classload=buildFromJSON(data);
								//classload.load(event);
								classload.init();
					//	});
						Module.toolSlide();
					
					
				});
			});
		},
		hideView:function (callback){
			
			this.loadAll(function(){
				FaceFinder.hideView();
				PhotoView.hideView();
				Duplicatits.hideView();
					$.each(Module.ModuleArray,function(index_year,data){
							var classload=buildFromJSON(data);
							classload.hideView();
					});
				
			
				if(callback != undefined && typeof callback == 'function') callback();
			});
			 
		},
		viewLoader:function (name){
			var classload=buildFromJSON(name);
			classload.viewLoader();
			classload.showView();
		},
		allInfo:function (img1,img2){
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			this.loadAll(function(){
					$.each(Module.ModuleArray,function(index_year,data){
							var classload=buildFromJSON(data);
							classload.load(event);
					});
				
				
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
		this.loadAll(function(){
				$.each(Module.ModuleArray,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.resat();
				});
			
		});
	},
	setEvents:function(){
		this.loadAll(function(){
				$.each(Module.ModuleArray,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.setEvents();
				});
			
			
		});
	},
	duplicatits:function(element,info){
		FaceFinder.duplicatits(element,info);
		$.getJSON(OC.linkTo('EagleEye', 'ajax/modules.php'), function(data) {
			if (data.status == 'success'){
				$.each(data.data,function(index_year,data){
						var classload=buildFromJSON(data);
						classload.duplicatits(element,info);
				});
			}
			
		});
	},
	addImagePhotoOverView:function(image){
		 $('#photoOverView').append('<div class="image" ><div class="test"><a name="'+image.path+'" href="#photoview/'+image.id+'"><img src="'+OC.linkTo('gallery', 'ajax/thumbnail.php')+'?file='+oc_current_user+'/'+image.path+'"  alt="'+image.id+'"></a><input type="checkbox" original-title="" alt="'+image.id+'" ></input></div></div>');
	}
};




function buildFromJSON(json) {
    var myObj = new this[json]();
    return myObj;
}