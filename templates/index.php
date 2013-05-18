<div id="controls">
<div class="crumb ui-droppable" title='/'>
	<a href="<?php echo \OCP\Util::linkTo('facefinder', '/')."?dir=/"?>"><img class="svg" src="/owncloud/core/img/places/home.svg"></img></a>
</div>
<?php 
$size=sizeof($_['patharray']);
$path;
if($size>1)
	$count=0;
	foreach ($_['patharray'] as $a){
	$count++;
	$path.="/".$a;
	if($size-$count===0){
?>
<div class="crumb last svg" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkTo('facefinder', '/')."?dir=".$path?>"><?php echo $a?></a>
</div>
<?php }else{?>
<div class="crumb  svg ui-droppable" title="<?php echo $path?>">
	<a href="<?php echo \OCP\Util::linkTo('facefinder', '/')."?dir=".$path?>""><?php echo $a?></a>
</div>
<?php
}

}
?>
	<span class="right">
		<button class="time" style=""> Time </button>
		<button class="time" style=""> Defect duplicatits </button>
	</span>
</div>
 
 <div id="photoff">
	 <div id="tool_right">
	 <ul  class="start">
		 <li>2013
			 <ul>
			 <li>Januar</li>
			 <li>Februar</li>
			 <li>April</li>
			 <li>Juni</li>
			 <li>Juli
				 <ul>
				 <li>Juli.1</li>
				 <li>Juli.2</li>
				 <li>Juli.3</li>
				 <li>Juli.4</li>
				 <li>Juli.5</li>
				 </ul>
			 </li>
			 </ul>
		 </li>
	 </ul>
	 </div>
	 <div id="photoOverView"></div>
</div>

<div id="photoview">

	<div id="tool_left">
		<div id="photo" >
			<!-- <img id="img_img"alt="" src=""> -->
		</div>
		<div id="tool_taggs">
			
		</div>
	</div>
	<div id="tool_righte">
			
	
	<!--	<div class="tool">
			<div class="tool_title">
				<div class="tool_ico"></div>
				<h1>GPS</h1>
			</div>
				<div class="tool_items">
					  <div id="demoMap" style="height:200px;width:auto;"></div>
					<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
					<script>
						 var lat            = 47.35387;
					    var lon            = 8.43609;
					    var zoom           = 18;
					 
					    var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
					    var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
					    var position       = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
					 
					    map = new OpenLayers.Map("demoMap");
					    var mapnik         = new OpenLayers.Layer.OSM();
					    map.addLayer(mapnik);
					 
					    var markers = new OpenLayers.Layer.Markers( "Markers" );
					    map.addLayer(markers);
					    markers.addMarker(new OpenLayers.Marker(position));
					 
					    map.setCenter(position, zoom);
					</script>
				</div>
			</div>
		</div>-->
		</div>
	</div>
</div>


