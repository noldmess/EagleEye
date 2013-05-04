<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$sepp=FaceDetaction_Module::getClass($id);
	
	
	$id_tag=Tag_Module::getTagId("KEYWORDS",$_GET['tag']);
	if($id_tag===null){
		$id_tag=Tag_Module::insertTag("KEYWORDS",$_GET['tag']);
		$faceclass=FaceDetaction_Module::getNewFaceClass();
	}else{
		$faceclass=FaceDetaction_Module::getFaceClassByTag($id_tag);
		if($faceclass===null){
			$faceclass=FaceDetaction_Module::getNewFaceClass();
		}
	}
	
	$sepp->addFaceToTrainingsset($_GET['face_id'],$faceclass,$id_tag);
	FaceDetaction_Module::update($sepp);
	$sepp=FaceDetaction_Module::getClass($id);
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id);
	$class=Tag_Module::getClass($photo->getID());
	$face=preg_split(  "/-/" ,  $_GET['pos']  );
	$class->addTag("2#025",$_GET['tag'],$face[0],$face[2],$face[1],$face[3]);
	Tag_Module::update($class);
	$class->writeTag($photo->getPath());
	if(FaceDetaction_ModuleClass::countNewFaces()>2)
		FaceDetaction_ModuleClass::updateLearnFaceRec();
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>