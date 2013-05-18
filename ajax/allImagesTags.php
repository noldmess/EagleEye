

<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$writemodul->getModuleClass();
$tag=null;
if( isset($_GET['dir']) && OC\Files\Filesystem::is_dir($_GET['dir'])){
	if(isset($_GET['tag'])){
		$tag=$_GET['tag'];
	}
	echo OCP\JSON::success(array("tag"=>Tag_Module::getJSON($_GET['dir']),"photo"=>Tag_Module::getAllPhotosJSON($_GET['dir'],$tag)));
}else{
	OCP\JSON::error();
}
?>