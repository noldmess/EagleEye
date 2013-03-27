<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul =OC_Module_Maneger::getInstance();
	$photo=OC_FaceFinder_Photo::getPhotoClass($id);
	$class=Kamera_Module::getClass($photo->getID());
	if($class!=null){
		echo OCP\JSON::success(array('data'=>$class->getJSON()));
	}else{
		echo OCP\JSON::success();
	}
	}else{
		OCP\JSON::error(array("message"=>"get image must be an intager"));
	}
?>