<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['image'];
if($id>0){
		$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($_GET['image']);
	$class=Tag_ModuleMapper::getClass($photo->getID());
	$class->addTag("2#025",$_GET['tag']);
	Tag_ModuleMapper::update($class);
	$class->writeTag($photo->getPath());
	echo OCP\JSON::success(array('data'=>$class->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>