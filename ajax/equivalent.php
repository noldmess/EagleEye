<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$photo= new OC_FaceFinder_Photo("");
$Initialisemodul==OC_Module_Maneger::getInstance();
$moduleclasses=$Initialisemodul->getModuleClass();
$photoArray=$photo->equivalent();
foreach ($moduleclasses as $moduleclass){
  $moduleopject=new $moduleclass("");
  $moduleArray[]=$moduleopject->equivalent();
}
$photo=OC_Equivalent_Result::Ajaxequalety($photoArray,$moduleArray);
echo OCP\JSON::success($photo);
?>
