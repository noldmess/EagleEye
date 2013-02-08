
<div id="controls">
<div id="slideshow">
	</div>
</div>

 <div id="new_1">
</div>
<div id="photoview">

	<div id="tool_left">
		<div id="photo">
			<div id="photoview_load"></div>
			<img id="img_img"alt="" src="">
		</div>
		<div id="tool_taggs">
		<div id="taggs"></div>
		<textarea></textarea></div>
	</div>
	<div id="tool_righte">
			<div class="tool Exif">
				<div class="tool_title">
					<div class="tool_ico"></div>
					<h1>Exif</h1>
				</div>
				<div class="tool_items">
				</div>
		</div>
		<div class="tool">
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
		</div>
		</div>
	</div>
</div>


