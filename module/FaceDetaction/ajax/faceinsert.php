<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$sepp=FaceDetaction_ModuleMapper::getClass($id);
	
	
	$id_tag=Tag_ModuleMapper::getTagId("KEYWORDS",$_GET['tag']);
	if($id_tag===null){
		$id_tag=Tag_ModuleMapper::insertTag("KEYWORDS",$_GET['tag']);
		$faceclass=FaceDetaction_ModuleMapper::getNewFaceClass();
	}else{
		$faceclass=FaceDetaction_ModuleMapper::getFaceClassByTag($id_tag);
		if($faceclass===null){
			$faceclass=FaceDetaction_ModuleMapper::getNewFaceClass();
		}
	}
	OCP\Util::writeLog("facefinder FaceClass ajax",$faceclass,OCP\Util::DEBUG);
	$sepp->addFaceToTrainingsset($_GET['face_id'],$faceclass,$id_tag);
	FaceDetaction_ModuleMapper::update($sepp);
	$sepp=FaceDetaction_ModuleMapper::getClass($id);
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id);
	$class=Tag_ModuleMapper::getClass($photo->getID());
	$face=preg_split(  "/-/" ,  $_GET['pos']  );
	$class->addTag("2#025",$_GET['tag'],$face[0],$face[2],$face[1],$face[3]);
	Tag_ModuleMapper::update($class);
	$class->writeTag($photo->getPath());
	//faster learn slover work!
	if(FaceDetaction_ModuleClass::countNewFaces()>2)
		FaceDetaction_ModuleClass::updateLearnFaceRec();
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>