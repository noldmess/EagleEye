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
//OCP\Util::addStyle( 'gallery', 'supersized' );
OCP\Util::addScript('facefinder', 'photoview');
OCP\Util::addStyle('facefinder', 'photoview');
/*if (!OCP\App::isEnabled('files_imageviewer')) {
	OCP\Template::printUserPage('facefinder', 'no-image-app');
	exit;
}*/

$root = !empty($_GET['root']) ? $_GET['root'] : '/';
$files = \OC_Files::getDirectoryContent($root, 'image');

/*$tl = new \OC\Pictures\TilesLine();
$ts = new \OC\Pictures\TileStack(array(), '');*/


$tmpl = new OCP\Template( 'facefinder', 'index', 'user' );
//$tmpl->assign('root', $root, false);
//$tmpl->assign('tl', $tl, false);
$tmpl->printPage();
