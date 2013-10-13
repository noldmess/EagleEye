<?php

/**
* ownCloud - EagleEye
*
* @author Aaron Messner
* @copyright 2012 Aaron Messner aaron.messner@stuudent.uibk.ac.at
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


// OCP\Share::registerBackend('photo', new OC_Share_Backend_Photo());
//
//$l = OC_L10N::get('gallery');
//OC::$CLASSPATH['OC_Share_Backend_Photo'] = 'gallery/lib/share.php';
//test 
//OCP\Util::addscript( 'EagleEye', 'test' );
//Module Includs
OC::$CLASSPATH['OCA\FaceFinder\ModuleManeger'] = 'EagleEye/lib/modulemaneger.php';
OC::$CLASSPATH['OCA\FaceFinder\ClassInterface'] = 'EagleEye/lib/moduleClassInterface.php';
OC::$CLASSPATH['OCA\FaceFinder\MapperInterface'] = 'EagleEye/lib/moduleinterface.php';

OC::$CLASSPATH['OCA\FaceFinder\PhotoClass'] = 'EagleEye/lib/photoclass.php';
OC::$CLASSPATH['OCA\FaceFinder\FaceFinderPhoto'] = 'EagleEye/lib/photo.php';
//OC::$CLASSPATH['OC_Gallery_Hooks_Handlers'] = 'EagleEye/lib/hooks_handlers.php';

OC::$CLASSPATH['OCA\FaceFinder\HooksHandlers'] = 'EagleEye/lib/hooks_handlers.php';
OC::$CLASSPATH['OCA\FaceFinder\BackgroundJob'] = 'EagleEye/lib/backgroundJob.php';

OC::$CLASSPATH['OC_Search_Provider_FaceFinder'] = 'EagleEye/lib/search.php';
OC::$CLASSPATH['OC_FaceFinder_Scanner'] = 'EagleEye/lib/scanner.php';
OC::$CLASSPATH['OCA\FaceFinder\EquivalentResult'] = 'EagleEye/lib/equivalent.php';
OC::$CLASSPATH['OCA\FaceFinder\OC_Equal'] = 'EagleEye/lib/equivalent.php';
//$l = OC_L10N::get('EagleEye');
OCP\Util::addscript( 'EagleEye', 'add' );
OCP\App::addNavigationEntry( array(
 'id' => 'EagleEye',
 'order' => 1,
 'href' =>  \OCP\Util::linkToRoute( 'EagleEyeView',  array("type"=>'View','dir'=>"%252F")),
 'icon' =>  \OCP\Util::imagePath('EagleEye', 'EagleEye.png'),
 'name' => "Eagle Eye"
));

//$array=OCP\BackgroundJob::allQueuedTasks ();
//OCP\Util::writeLog("EagleEye",json_encode($array)."sdfsdf",OCP\Util::DEBUG);
OC_Search::registerProvider('OC_Search_Provider_FaceFinder');
OCP\Util::connectHook('OC_Filesystem', 'post_write','OCA\FaceFinder\HooksHandlers','write');
OCP\Util::connectHook('OC_Filesystem', 'post_delete','OCA\FaceFinder\HooksHandlers','delete');
OCP\Util::connectHook('OC_Filesystem', 'post_rename','OCA\FaceFinder\HooksHandlers','update');
