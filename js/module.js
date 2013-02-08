
var Module={
		load:function (event){
			
			//$."Tag".load(event);
			//var you = new Person({ firstName: 'Mike' });
			$.getJSON(OC.linkTo('facefinder', 'ajax/modules.php'), function(data) {
				$.each(data,function(index_year,data){
						var d=buildFromJSON(data);
						d.load(event);
				});
			});
			/*$(someObj).someMethod();
			var d=$.makeclass(Tag);
			;*/
		}
};

function Person() {
    this.loadFromJSON = function(json) {
        this.FirstName = json.FirstName;
    };
}

function buildFromJSON(json) {
    var myObj = new this[json]();
    //alert("sdfsdf");
    return myObj;
}