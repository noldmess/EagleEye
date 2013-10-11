<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('EagleEye');
$scriptArray=array();
$Initialisemodul=OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$Initialisemodul->getModuleClass();

foreach ($moduleclasses as $moduleclass){
	$arrayScript=$moduleclass['Mapper']::getArrayOfScript();
	foreach($arrayScript as $script){
		$scriptArray[]=$script;
	}
}
echo OCP\JSON::success(array('data'=>$scriptArray));
?>