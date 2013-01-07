<?php

$currentVersion=OC_Appconfig::getValue('facefinder', 'installed_version');
if (version_compare($currentVersion, '0.0.1', '>')) {
	$stmt = OCP\DB::prepare('DROP TABLE  IF EXISTS `*PREFIX*facefinder`');
	$stmt->execute();
	OCP\Util::writeLog("facefinder---------------___>",OC_App::getAppPath($appid).'/appinfo/database.xml',OCP\Util::DEBUG);
	\OC_DB::createDbFromStructure(OC_App::getAppPath($appid).'/appinfo/database.xml');
	/**@todo
	 * scanner dunktion to get all info nedet
	 * 
	 */
}

