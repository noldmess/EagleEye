<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=new OC_Module_Maneger();
$moduleclasses=$writemodul->getModuleClass();
$tmp=new OC_FaceFinder_Photo($_GET['image']);
$id=$tmp->getID();
$kamera=new Kamera_Module($_GET['image']);
$kamera->setForingKey($id);
echo json_encode($kamera->getKameraData());
?>