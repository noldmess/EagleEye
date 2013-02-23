<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$photo= new OC_FaceFinder_Photo("");
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();
$photoArray=$photo->equivalent();
foreach ($moduleclasses as $moduleclass){
  $moduleopject=new $moduleclass("");
  $moduleArray[]=$moduleopject->equivalent();
}
$photo=OC_Equivalent_Result::Ajaxequalety($photoArray,$moduleArray);
echo json_encode($photo);
?>
