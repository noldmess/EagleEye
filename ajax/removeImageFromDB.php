<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['img'];
if($id>0){
	$user = \OCP\USER::getUser();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($_GET['img']);
	if(!isset($photo)){
		OCA\FaceFinder\FaceFinderPhoto::remove($photo);
		echo OCP\JSON::success();
	}else{
		OCP\JSON::error(array("message"=>"image Isset"));
	}
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>
