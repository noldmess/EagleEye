<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
		$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id);
	//test
	if(FaceDetaction_Module::issetInCacheImages($photo->getID())){
		FaceDetaction_Module::getInfo($photo->getID());
		echo "sfdfdsf";
		OCP\Util::writeLog("facefinder","test".$photo->getPath(),OCP\Util::DEBUG);
	}
	$class=FaceDetaction_Module::getClass($photo->getID());
	echo OCP\JSON::success(array('data'=>$class->getJSON()));
}else{
		OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>