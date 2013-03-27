<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul=OC_Module_Maneger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$photo=OC_FaceFinder_Photo::getPhotoClass($_GET['image']);
	$class=Tag_Module::getClass($photo->getID());
	$class->addTag("2#025",$_GET['tag']);
	Tag_Module::update($class);
	$class->writeTag($photo->getPath());
	echo OCP\JSON::success(array('data'=>$class->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>