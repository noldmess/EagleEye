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
	$class->addTag("2#025",$_GET['tag'],$_GET['x1'],$_GET['x2'],$_GET['y1'],$_GET['y2']);
	Tag_Module::update($class);
	$class->writeTag($photo->getPath());
	echo OCP\JSON::success(array('data'=>$class->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>