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

\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('EagleEye');

\OCP\App::setActiveNavigationEntry( 'EagleEye' );
OCP\Util::addStyle('EagleEye', 'styles');
OCP\Util::addScript('EagleEye', 'facefinder');
OCP\Util::addScript('EagleEye', 'module');
OCP\Util::addScript('EagleEye', 'photoview');
OCP\Util::addStyle('EagleEye', 'photoview');
OCP\Util::addStyle('EagleEye', 'duplicate');
OCP\Util::addScript('EagleEye', 'duplicate');
OCP\Util::addScript('EagleEye', 'singlePageHandler');

//OCP\Util::addStyle('facefinder', 'bootstrap.min');


//Initialise the moduls
$Initialisemodul= OCA\FaceFinder\ModuleManeger::getInstance();
$moduleclasses=$Initialisemodul->getModuleClass();
//inport all Style and Script files
foreach ($moduleclasses as $moduleclass){
	$arrayScript=$moduleclass['Mapper']::getArrayOfScript();
	$arrayStyle=$moduleclass['Mapper']::getArrayOfStyle();
	//inport all Script files
	foreach($arrayScript as $script){
		//OCP\Util::addScript('facefinder/module/', $script);
		OCP\Util::addScript('EagleEye/module/'.$moduleclass['Name'], $script);
	}
	//inport all Style  files
	if($arrayStyle!=null){
		foreach($arrayStyle as $style){
			//OCP\Util::addStyle('facefinder/module/', $style);
			OCP\Util::addStyle('EagleEye/module/'.$moduleclass['Name'], $style);
		}
	}
}
//test if all modules initialised
foreach ($moduleclasses as $moduleclass){
		$class=$moduleclass['Mapper']::initialiseDB();
}
$pathArray=OC_FaceFinder_Scanner::scan("");
OCP\Util::writeLog("facefinder",json_encode($pathArray),OCP\Util::DEBUG);
foreach ($pathArray as $path){
	OCP\Util::writeLog("facefinder",$path,OCP\Util::DEBUG);
	if(!OCA\FaceFinder\FaceFinderPhoto::issetPhotoId($path)){
		$photoOpject=OCA\FaceFinder\PhotoClass::getInstanceByPaht($path);
		OCA\FaceFinder\FaceFinderPhoto::insert($photoOpject);
		$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClassPath($path);
		foreach ($moduleclasses as $moduleclass){
			if(!is_null($photo)){
				$class=$moduleclass['Class']::getInstanceByPath($path,$photo->getID());
				$moduleclass['Mapper']::insert($class);
			}
		}

	}
}

if(isset($_GET['search'])){
	OCP\Util::addStyle('EagleEye', 'search');
	//OCP\Util::addScript('facefinder', 'search');
	$tmpl = new OCP\Template( 'EagleEye', 'search', 'user' );
	OCP\Util::addScript('EagleEye', 'photoview');
	$tmpl->printPage();	
}else{
	$tmpl = new OC_Template( 'EagleEye', 'index', 'user' );
	//uset to get a start diferent folders
	$path=array();
	if(isset( $params['dir'])){
		$dir=str_replace(OCP\User::getUser(), '',  $params['dir']);
		$tok= strtok($dir,"%252F");
		while ($tok !== false) {
			$path[]=$tok;
			$tok = strtok("%252F");
		}
	}else{
		$path="";
	}
	$tmpl->assign('patharray', $path);
	
	$tmpl->printPage();
}
