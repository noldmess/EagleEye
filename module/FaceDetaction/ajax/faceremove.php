<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	/*$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($_GET['image']);
	$class=Tag_Module::getClass($photo->getID());*/
	$class=FaceDetaction_ModuleMapper::removeFace($id);
	echo OCP\JSON::success();
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>