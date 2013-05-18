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
	OCP\Util::writeLog("facefinder---------------___>",OC_App::getAppPath($appid).'/appinfo/database.xml',OCP\Util::DEBUG);
	\OC_DB::createDbFromStructure(OC_App::getAppPath($appid).'/appinfo/database.xml');
	//Create alle modue Tables from DB
	foreach ($moduleclasses as $moduleclass){
		echo $moduleclass['Mapper'];
		$moduleclass['Mapper']::initialiseDB();
	}
	//$pathArray=OC_FaceFinder_Scanner::scan("");
	OCP\Util::writeLog("facefinder",json_encode($pathArray),OCP\Util::DEBUG);
	foreach ($pathArray as $path){
		OCP\Util::writeLog("facefinder",$path,OCP\Util::DEBUG);
		if(!OCA\FaceFinder\FaceFinderPhoto::issetPhotoId($path)){
			$photoOpject=OCA\FaceFinder\PhotoClass::getInstanceByPaht($path);
			OCA\FaceFinder\FaceFinderPhoto::insert($photoOpject);
			$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClassPath($path);
			foreach ($moduleclasses as $moduleclass){
				//OCP\Util::writeLog("facefinder",$moduleclass['Class']."->".$photo."afsdsdf ",OCP\Util::DEBUG);
				if(!is_null($photo)){
					$class=$moduleclass['Class']::getInstanceByPath($path,$photo->getID());
					$moduleclass['Mapper']::insert($class);
					//OCP\Util::writeLog("facefinder",$moduleclass."->".$img." ".$id,OCP\Util::DEBUG);
				}
			}
	
		}
	}

	 
}

