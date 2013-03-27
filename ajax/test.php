<?php
echo "sdfsdfsdf";
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=OC_Module_Maneger::getInstance();
echo json_encode($writemodul->getModuleClass());
$d=EXIF_ModuleClass::getInstanceBySQL(1,array("s"=>2,"d"=>2),4);
echo $d;
?>