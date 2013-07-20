<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClassPath("/".$_GET['image']);
if($photo!==null){
	echo OCP\JSON::success(array('data'=>$photo->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>