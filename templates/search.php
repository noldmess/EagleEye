
<?php
use OCA\FaceFinder;
if(isset($_GET['search'])&&isset($_GET['tag'])&&(isset($_GET['name']))){
	$Initialisemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	if($Initialisemodul->isModuleClass($_GET['search'])){
		//$tag=htmlspecialchars(str_replace(array('/', '\\'), '',  $_GET['tag']));
		
		//$name=htmlspecialchars(str_replace(array('/', '\\'), '',  ));
		$seachResult=$_GET['search']::searchArry($_GET['name'],$_GET['tag']);
		$result=count($seachResult);
		$searchbool=true;
	}
}
//lse{
//	$searchbool=false;
//}
?>
<div id="controls" >
	<span class="right">
<a  href="facefinder/" data-item="" title="<?php echo $l->t("FaceFinder"); ?>"><button class="share"><?php echo $l->t("FaceFinder"); ?></button></a>
</span>
</div>
<div id="search" class="hascontrols">
<?php
//<a><img src="'+image.imagstmp+'"  alt="'+image.imagsname+'"></a>


foreach ($seachResult as $img){
	echo '<a href="#photoview/'.$img[1].'" name="'.\OCP\USER::getUser().$img[0].'"><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$img[0].'" alt='.$img[1].'></a>';
	
}
?>
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
