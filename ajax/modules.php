<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$scriptArray=array();
$Initialisemodul=OC_Module_Maneger::getInstance();
$moduleclasses=$Initialisemodul->getModuleClass();

foreach ($moduleclasses as $moduleclass){
	$arrayScript=$moduleclass['Mapper']::getArrayOfScript();
	foreach($arrayScript as $script){
		$scriptArray[]=$script;
	}
}
echo OCP\JSON::success(array('data'=>$scriptArray));
?>