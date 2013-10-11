<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('EagleEye');
$dir=$_GET['dir'];
if(OC_Filesystem::is_dir($dir)){
	echo OCP\JSON::success(array('data'=>OCA\FaceFinder\FaceFinderPhoto::chronologically($dir)));
}else{
	OCP\JSON::error(array("message"=>$dir." must be a dir"));
}
?>