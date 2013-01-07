<?php
/**
* ownCloud
*
* @author Michael Gapczynski
* @copyright 2012 Michael Gapczynski mtgap@owncloud.com
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
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*/

abstract class OC_Share_Photo_Backend implements OCP\Share_Backend {

	public $dependsOn = 'file';
	public $supportedFileExtensions = array('jpg', 'png', 'gif');

	public function getSource($item, $uid) {
		return array('item' => 'blah.jpg', 'file' => $item);
	}

	public function generateTarget($item, $uid, $exclude = null) {
		// TODO Make sure target path doesn't exist already
		return $item;
	}

	public function formatItems($items, $format, $parameters = null) {

	}

}
