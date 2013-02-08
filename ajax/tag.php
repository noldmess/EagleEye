<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');


if(isset($_GET['image'])){
	$writemodul=new OC_Module_Maneger();
	$moduleclasses=$writemodul->getModuleClass();
	$tag_module =new Tag_Module($_GET['image']);
	echo json_encode($tag_module->getTag());
}
?>