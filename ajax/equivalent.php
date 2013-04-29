<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$photo= new OCA\FaceFinder\FaceFinderPhoto("");
$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$writemodul->getModuleClass();
$photoArray=$photo->equivalent();
foreach ($moduleclasses as $moduleclass){
  $moduleopject=new $moduleclass['Mapper']("");
  $moduleArray[]=$moduleopject->equivalent();
}
$photo=OCA\FaceFinder\EquivalentResult::Ajaxequalety($photoArray,$moduleArray);
echo OCP\JSON::success(array("data"=>$photo));
?>
