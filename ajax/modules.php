<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$scriptArray=array();
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();

foreach ($moduleclasses as $moduleclass){
	$arrayScript=$moduleclass::getArrayOfScript();
	foreach($arrayScript as $script){
		$scriptArray[]=$script;
	}
}
echo json_encode($scriptArray);
?>