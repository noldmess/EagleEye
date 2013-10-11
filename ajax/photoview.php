<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['id'];
if($id>0){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($_GET['id']);
	echo OCP\JSON::success(array('data'=>$photo->getJSON(),OC_App::getAppPath('EagleEye')."/module/FaceDetaction/config/install"));
}else{
		OCP\JSON::error(array("message"=>"get image must be an intager"));
}