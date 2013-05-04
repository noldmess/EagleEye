<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['face_id'];
if($id>0){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$class=FaceDetaction_Module::unsetFace($id);
	OCP\Util::writeLog("facefinder",json_encode($array)."sdfsdf",OCP\Util::DEBUG);
	$facelist=FaceDetaction_Module::getFaceClass();
	FaceDetaction_ModuleClass::classLearnFaceRec();
	foreach($facelist as $face){
		echo $face['facePhotoPath']." ".$face['faceclass'];
		FaceDetaction_ModuleClass::addFaceToLearn($face['facePhotoPath'],$face['faceclass']);
	}
	FaceDetaction_ModuleClass::updateLearnFaceRec();
	echo OCP\JSON::success();
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>