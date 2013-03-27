<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul=OC_Module_Maneger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$photo=OC_FaceFinder_Photo::getPhotoClass($id);
	$class=Tag_Module::getClass($photo->getID());
	$tag_module =new Tag_Module($id);
	$name = strtok($_GET['tag'], " ");
	$tag = strtok(" ");
	$tagid=$tag_module->getTagId($name,$tag);
	$tag_module->removeTagPhoto($tagid,$class);
	$tag_module->writeTag();
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>