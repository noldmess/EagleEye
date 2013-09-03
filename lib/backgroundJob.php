<?php
namespace OCA\FaceFinder;
/**
* ownCloud - facefinder
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
use OCP;
class BackgroundJob {

	public static function startBackgroundJob($array){
		$array=json_decode($array,true);
		$writemodul=ModuleManeger::getInstance();
		$moduleclasses=$writemodul->getModuleClass();
			$class=$array['class']::doBackgroundJob($array);
	}
	
	
	public static function addBackgroundJob($array){
	OCP\Util::writeLog("facefinder",json_encode($array)."sdfsdf",OCP\Util::DEBUG);
	OCP\BackgroundJob::addQueuedTask('facefinder','OCA\FaceFinder\BackgroundJob','startBackgroundJob',json_encode($array));
	}
	


}
