<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
$id=(int)$_GET['image'];
if($id>0){
	$writemodul =OC_Module_Maneger::getInstance();//$image=$file = str_replace(array('/', '\\'), '',  $_GET['file']);
	$photo=OC_FaceFinder_Photo::getPhotoClass($_GET['image']);
	$class=EXIF_Module::getClass($photo->getID());
	echo OCP\JSON::success(array('data'=>$class->getJSON()));
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>