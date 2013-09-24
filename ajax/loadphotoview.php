<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
if(OC_Filesystem::file_exists($_GET['image'])){
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClassPath($_GET['image']);
	echo OCP\JSON::success(array('data'=>$photo->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>