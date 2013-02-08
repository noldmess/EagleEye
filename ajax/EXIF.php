<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=new OC_Module_Maneger();
$moduleclasses=$writemodul->getModuleClass();
$tmp=new OC_FaceFinder_Photo($_GET['image']);
$id=$tmp->getID();
$exif=new EXIF_Module($_GET['image']);
$exif->setForingKey($id);
echo json_encode($exif->getExif());
?>