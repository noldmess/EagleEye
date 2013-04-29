<?php

/**
* ownCloud - gallery application
*
* @author Aaron Messner
* @copyright 2012 Bartek Przybylski bartek@alefzero.eu
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

use OCA\FaceFinder;

OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('facefinder');
OCP\App::setActiveNavigationEntry( 'facefinder' );
OCP\Util::addStyle('facefinder', 'styles');
OCP\Util::addScript('facefinder', 'new_1');
OCP\Util::addScript('facefinder', 'facefinder');
OCP\Util::addScript('facefinder', 'module');
OCP\Util::addScript('facefinder', 'photoview');
OCP\Util::addStyle('facefinder', 'photoview');


//Initialise the moduls
$Initialisemodul= OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$Initialisemodul->getModuleClass();
//inport all Style and Script files
foreach ($moduleclasses as $moduleclass){
	$arrayScript=$moduleclass['Mapper']::getArrayOfScript();
	$arrayStyle=$moduleclass['Mapper']::getArrayOfStyle();
	//inport all Script files
	foreach($arrayScript as $script){
		OCP\Util::addScript('facefinder/module', $script);
	}
	//inport all Style  files
	if($arrayStyle!=null){
		foreach($arrayStyle as $style){
			OCP\Util::addStyle('facefinder/module', $style);
		}
	}
}
$pathArray=OC_FaceFinder_Scanner::scan("");
foreach ($moduleclasses as $moduleclass){
		$class=$moduleclass['Mapper']::initialiseDB();//($path,$photo->getID());
}
	
foreach ($pathArray as $path){
	if(!OCA\FaceFinder\FaceFinderPhoto::issetPhotoId($path)){
		$photoOpject=OCA\FaceFinder\PhotoClass::getInstanceByPaht($path);
		OCA\FaceFinder\FaceFinderPhoto::insert($photoOpject);
		$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($path);
		foreach ($moduleclasses as $moduleclass){
					if(!is_null($photo)){
						$class=$moduleclass['Class']::getInstanceByPath($path,$photo->getID());
						$moduleclass['Mapper']::insert($class);
						OCP\Util::writeLog("facefinder",$moduleclass."->".$img." ".$id,OCP\Util::DEBUG);
					}
				}
		
			}
}

if(isset($_GET['search'])){
	OCP\Util::addStyle('facefinder', 'search');
	OCP\Util::addScript('facefinder', 'search');
	$tmpl = new OCP\Template( 'facefinder', 'search', 'user' );
	OCP\Util::addScript('facefinder', 'photoview');
	$tmpl->printPage();	
}else{

	$tmpl = new OC_Template( 'facefinder', 'index', 'user' );
	$tmpl->printPage();
}
