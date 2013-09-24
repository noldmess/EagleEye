<?php
use OCA\FaceFinder;
$currentVersion=OC_Appconfig::getValue('facefinder', 'installed_version');
if (version_compare($currentVersion, '0.0.1', '>')) {
	$Initialisemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$Initialisemodul->getModuleClass();
	//remove alle modue Tables from DB
	foreach ($moduleclasses as $moduleclass){
		$moduleclass['Mapper']::removeDBtabels();
	}
	$stmt = OCP\DB::prepare('DROP TABLE  IF EXISTS `*PREFIX*facefinder`');
	$stmt->execute();
	OCP\Util::writeLog("facefinder--------------->",OC_App::getAppPath($appid).'/appinfo/database.xml',OCP\Util::DEBUG);
	\OC_DB::createDbFromStructure(OC_App::getAppPath($appid).'/appinfo/database.xml');
	//Create alle modue Tables from DB
	foreach ($moduleclasses as $moduleclass){
		echo $moduleclass['Mapper'];
		$moduleclass['Mapper']::initialiseDB();
	}
}

