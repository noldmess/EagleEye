<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['image'];
if($id>0){
		$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id);
	$class=Tag_ModuleMapper::getClass($photo->getID());
	$tag_module =new Tag_ModuleMapper($id);
	$name = strtok($_GET['tag'], " ");
	$tag = strtok(" ");
	$tagid=$tag_module->getTagId($name,$tag);
	$tag_module->removeTagPhoto($tagid,$class);
	$class->writeTag($photo->getPath());
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>