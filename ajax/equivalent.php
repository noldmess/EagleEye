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
$page=(int)$_GET['page'];
$pagesize=20;//(int)$_GET['page'];
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
	foreach($moduleArray as $module){
		foreach($module as $image){
			if(($photoArray[$i][0]["photo_id"]===$image[0]["photo_id"] && $photoArray[$i][1]["photo_id"]===$image[1]["photo_id"] )||($photoArray[$i][1]["photo_id"]===$image[0]["photo_id"] && $photoArray[$i][0]["photo_id"]===$image[1]["photo_id"])){
				$photoArray[$i]['prozent']*=0.80;
				$photoArray[$i]['prozent']+=$image['prozent']*0.2;
				break;
			}
		}
		
	}
}
usort($photoArray,  "test");
$sizeArray=sizeof($photoArray);

$photoArray=array_slice($photoArray,$page*$pagesize,$pagesize);
echo OCP\JSON::success(array("data"=>$photoArray,"size"=>$sizeArray));
?>