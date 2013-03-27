
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
		}
};


function buildFromJSON(json) {
    var myObj = new this[json]();
    return myObj;
}