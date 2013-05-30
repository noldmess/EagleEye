<?php

/**
* ownCloud - facefinder
*
* @author Aaron Messner
* @copyright 2012 Aaron Messner aaron.messner@stuudent.uibk.ac.at
*a
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
//OCP\Util::addscript( 'facefinder', 'test' );
OC::$CLASSPATH['OCA\FaceFinder\ModuleManeger'] = 'facefinder/lib/modulemaneger.php';
OC::$CLASSPATH['OCA\FaceFinder\ClassInterface'] = 'facefinder/lib/moduleClassInterface.php';
OC::$CLASSPATH['OCA\FaceFinder\MapperInterface'] = 'facefinder/lib/moduleinterface.php';

OC::$CLASSPATH['OCA\FaceFinder\PhotoClass'] = 'facefinder/lib/photoclass.php';

OC::$CLASSPATH['OC_Gallery_Hooks_Handlers'] = 'facefinder/lib/hooks_handlers.php';
OC::$CLASSPATH['OCA\FaceFinder\FaceFinderPhoto'] = 'facefinder/lib/photo.php';
OC::$CLASSPATH['OCA\FaceFinder\HooksHandlers'] = 'facefinder/lib/hooks_handlers.php';
OC::$CLASSPATH['OC_Search_Provider_FaceFinder'] = 'facefinder/lib/search.php';
OC::$CLASSPATH['OC_FaceFinder_Scanner'] = 'facefinder/lib/scanner.php';
OC::$CLASSPATH['OCA\FaceFinder\EquivalentResult'] = 'facefinder/lib/equivalent.php';
OC::$CLASSPATH['OCA\FaceFinder\OC_Equal'] = 'facefinder/lib/equivalent.php';
//$l = OC_L10N::get('facefinder');
OCP\Util::addscript( 'facefinder', 'add' );
OCP\App::addNavigationEntry( array(
 'id' => 'facefinder',
 'order' => 20,
 'href' => OCP\Util::linkTo('facefinder', 'index.php'),
 'icon' => OCP\Util::imagePath('core', 'places/picture.svg'),
 'name' => "FaceFinder"
));

//$array=OCP\BackgroundJob::allQueuedTasks ();
//OCP\Util::writeLog("facefinder",json_encode($array)."sdfsdf",OCP\Util::DEBUG);
OC_Search::registerProvider('OC_Search_Provider_FaceFinder');
OCP\Util::connectHook('OC_Filesystem', 'post_write','OCA\FaceFinder\HooksHandlers','write');
OCP\Util::connectHook('OC_Filesystem', 'post_delete','OCA\FaceFinder\HooksHandlers','delete');
OCP\Util::connectHook('OC_Filesystem', 'post_rename','OCA\FaceFinder\HooksHandlers','update');
