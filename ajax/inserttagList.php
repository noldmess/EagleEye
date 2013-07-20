<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
if(is_array($_POST['imagelist'])){
	$writemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$writemodul->getModuleClass();
	$countCorrect=0;
	$countWrong=0;
	foreach ($_POST['imagelist'] as $img){
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($img);
	$class=Tag_Module::getClass($photo->getID());
	
		if($class->addTag("2#025",$_POST['tag'])){
			Tag_Module::update($class);
			$class->writeTag($photo->getPath());
			$countCorrect++;
		}else{
			$countWrong++;
		}
	}
	echo OCP\JSON::success(array('Wrong'=>$countWrong,'Correct'=>$countCorrect));
	
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>