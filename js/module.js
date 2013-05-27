$(document).ready(function() {
	setTimeout(function(){
		Module.toolSlide();
		  }, 500);
	
});
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
	toolSlide:function(){
		$('.tool div.tool_title').click(function(){	
			if($(this).parent().children(".tool_items").is(":visible")){
				$(this).parent().children(".tool_items").slideUp(500);
			}else{
				$(this).parent().children(".tool_items").slideDown(500);
			}
		})
	}
};


function buildFromJSON(json) {
    var myObj = new this[json]();
    return myObj;
}