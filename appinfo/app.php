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
OC::$CLASSPATH['OC_Module_Maneger'] = 'facefinder/lib/modulemaneger.php';
OC::$CLASSPATH['OC_Module_Interface'] = 'facefinder/lib/moduleinterface.php';
OC::$CLASSPATH['OC_Gallery_Hooks_Handlers'] = 'facefinder/lib/hooks_handlers.php';
OC::$CLASSPATH['OC_FaceFinder_Photo'] = 'facefinder/lib/photo.php';
OC::$CLASSPATH['OC_FaceFinder_Hooks_Handlers'] = 'facefinder/lib/hooks_handlers.php';
OC::$CLASSPATH['OC_Search_Provider_FaceFinder'] = 'facefinder/lib/search.php';
OC::$CLASSPATH['OC_FaceFinder_Scanner'] = 'facefinder/lib/scanner.php';
//$l = OC_L10N::get('facefinder');
//new OC_Module_Maneger();
OCP\App::addNavigationEntry( array(
 'id' => 'facefinder',
 'order' => 20,
 'href' => OCP\Util::linkTo('facefinder', 'index.php'),
 'icon' => OCP\Util::imagePath('core', 'places/picture.svg'),
 'name' => "FaceFinder"
));

//OCP\Util::addscript('facefinder', 'facefindersearch');
OC_Search::registerProvider('OC_Search_Provider_FaceFinder');
OCP\Util::connectHook('OC_Filesystem', 'post_write','OC_FaceFinder_Hooks_Handlers','write');
OCP\Util::connectHook('OC_Filesystem', 'post_delete','OC_FaceFinder_Hooks_Handlers','delete');
OCP\Util::connectHook('OC_Filesystem', 'post_rename,'OC_FaceFinder_Hooks_Handlers','update');
