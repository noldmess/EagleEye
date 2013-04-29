<?php
/**
* ownCloud - bookmarks plugin
*
* @author David Iwanowitsch
* @copyright 2012 David Iwanowitsch <david at unclouded dot de>
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library. If not, see <http://www.gnu.org/licenses/>.
*
*/
use OCA\FaceFinder;
class OC_Search_Provider_FaceFinder extends  OC_Search_Provider{
function search($query) {
	$searchResultarray=array();
	$Initialisemodul=OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$Initialisemodul->getModuleClass();
	foreach ($moduleclasses as $moduleclass){
		$searchResultarray=array_merge($searchResultarray,$moduleclass['Mapper']::search($query));
		}
		return $searchResultarray;
	}
}