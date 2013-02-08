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



OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('facefinder');
OCP\App::setActiveNavigationEntry( 'facefinder' );
OCP\Util::addStyle('facefinder', 'styles');
OCP\Util::addScript('facefinder', 'new_1');
OCP\Util::addScript('facefinder', 'facefinder');
OCP\Util::addScript('facefinder', 'module');
OCP\Util::addScript('facefinder', 'photoview');
OCP\Util::addStyle('facefinder', 'photoview');

// auslagern in modlue maneger
OCP\Util::addScript('facefinder', 'tag');
OCP\Util::addScript('facefinder', 'exif');



//Initialise the moduls
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();
/**
 * @todo hier scanner
*/
//OC_FilesystemView('dsf');
foreach ($moduleclasses as $moduleclass){
	if($moduleclass::initialiseDB()){
		OCP\Util::writeLog("facefinder",$moduleclass."1",OCP\Util::DEBUG);
	$phat=OC_FaceFinder_Scanner::scan("df");
	foreach ($phat as $img){
		$tmp=new OC_FaceFinder_Photo($img);
		$id=$tmp->getID();
		$class=new $moduleclass($img);
		$class->setForingKey($id);
		$class->insert();
		OCP\Util::writeLog("facefinder",$moduleclass."->".$img." ".$id,OCP\Util::DEBUG);
	}

	}
}

if(isset($_GET['search'])){
	OCP\Util::addStyle('facefinder', 'search');
	$tmpl = new OCP\Template( 'facefinder', 'search', 'user' );
	OCP\Util::addScript('facefinder', 'photoview');
	$tmpl->printPage();	
}else{
	$tmpl = new OC_Template( 'facefinder', 'index', 'user' );
	$tmpl->printPage();
}
