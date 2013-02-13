<?php

$currentVersion=OC_Appconfig::getValue('facefinder', 'installed_version');
if (version_compare($currentVersion, '0.0.1', '>')) {
	$Initialisemodul=new OC_Module_Maneger();
	$moduleclasses=$Initialisemodul->getModuleClass();
	//remove alle modue Tables from DB
	foreach ($moduleclasses as $moduleclass){
		$moduleclass::removeDBtabels();
	}
	$stmt = OCP\DB::prepare('DROP TABLE  IF EXISTS `*PREFIX*facefinder`');
	$stmt->execute();
	OCP\Util::writeLog("facefinder---------------___>",OC_App::getAppPath($appid).'/appinfo/database.xml',OCP\Util::DEBUG);
	\OC_DB::createDbFromStructure(OC_App::getAppPath($appid).'/appinfo/database.xml');
	//Create alle modue Tables from DB
	foreach ($moduleclasses as $moduleclass){
		$moduleclass::initialiseDB();
	}
	
	$phat=OC_FaceFinder_Scanner::scan("");
			foreach ($phat as $img){
				$tmp=new OC_FaceFinder_Photo($img);
				$tmp->insert();
				$id=$tmp->getID();
				foreach ($moduleclasses as $moduleclass){
					$class=new $moduleclass($img);
					$class->setForingKey($id);
					$class->insert();
				}
			}
	 
}

