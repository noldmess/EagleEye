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
		FaceDetaction_ModuleMapper::getInfo($photo->getID());
		OCP\Util::writeLog("facefinder","test".$photo->getPath(),OCP\Util::DEBUG);
		$class=FaceDetaction_ModuleMapper::getClass($photo->getID());
		echo OCP\JSON::success(array('type'=>"new",'data'=>$class->getJSON()));
	
}else{
		OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>