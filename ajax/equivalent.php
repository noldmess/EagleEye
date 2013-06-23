<?php
 function test($a, $b)
{
	if ($a['prozent'] == $b['prozent']) {
		return 0;
	}
	return ($a['prozent'] > $b['prozent']) ? -1 : 1;
}
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$dir=$_GET['dir'];
$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$writemodul->getModuleClass();
$photo= new OCA\FaceFinder\FaceFinderPhoto("");
$photoArray=$photo->equivalent($dir);
$moduleArray=array();
foreach ($moduleclasses as $moduleclass){
  $moduleopject=new $moduleclass['Mapper']("");
   $moduleArray+=array($moduleclass['Mapper']=>$moduleopject->equivalent($dir));	
}
for($i=0;$i<sizeof($photoArray);$i++){
	foreach($moduleArray as $sss=>$module){
		foreach($module as $image){
			if($photoArray[$i][0]["photo_id"]===$image[0]["photo_id"] && $photoArray[$i][1]["photo_id"]===$image[1]["photo_id"] ||$photoArray[$i][1]["photo_id"]===$image[0]["photo_id"] && $photoArray[$i][0]["photo_id"]===$image[1]["photo_id"]){
				$photoArray[$i]['prozent']*=0.80;
				$photoArray[$i]['prozent']+=$image['prozent']*0.2;
			//	echo $photoArray[$i]['prozent']." ".($photoArray[$i]['prozent']*0.99)." $sss ".$image['prozent']."\n";
				break;
			}
		}
		
	}
}
usort($photoArray,  "test");
$photoArray=array_slice($photoArray,0,20);
echo OCP\JSON::success(array("data"=>$photoArray));
?>