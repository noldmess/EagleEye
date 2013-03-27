<?php
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

class OC_FaceFinder_Hooks_Handlers {

	/**
	 * The function is used in case of the upladet of a file
	 * The patamrter includes the name of the file;
	 * @param  $params
	 */
	public static function write($params) {

		$path = $params['path'];
		if(self::isPhoto($path)){
		$photoOpject=PhotoClass::getInstanceByPaht($path);
		OC_FaceFinder_Photo::insert($photoOpject);
		$photo=OC_FaceFinder_Photo::getPhotoClassPath($path);
		if(!is_null($photo)){
			OCP\Util::writeLog("facefinder","<<<<<<".$path,OCP\Util::DEBUG);
			/*
			* 	Implemetation of the module system 
			 */
			$writemodul=OC_Module_Maneger::getInstance();
			$moduleclasses=$writemodul->getModuleClass();
				foreach ($moduleclasses as $moduleclass){
					$class=$moduleclass['Class']::getInstanceByPath($path,$photo->getID());
					$moduleclass['Mapper']::insert($class);
				}
			}
		}
	}
	/**
	 * The function is used in case of the delete of a file
	 * The patamrter includes the name of the file;
	 * @param  $params
	 */
	public static function delete($params){
		$path = $params['path'];
		if($path!=''&& self::isPhoto($path)){
			OCP\Util::writeLog("facefinder","to delite".$path,OCP\Util::ERROR);
			$photo=OC_FaceFinder_Photo::getPhotoClass($path);
			if(!is_null($photo)){
				OC_FaceFinder_Photo::remove($photo);
			}
		}else{
			OCP\Util::writeLog("facefinder1a",$path,OCP\Util::ERROR);
			$photoarray=OC_FaceFinder_Photo::getPhotoClassDir($path);
			foreach($photoarray as $photo){
				OCP\Util::writeLog("facefinder2",$photo->getID(),OCP\Util::ERROR);
				if(!is_null($photo)){
					OC_FaceFinder_Photo::remove($photo);
				}
			}
			OCP\Util::writeLog("facefinder2",$path,OCP\Util::ERROR);
		}
	}
	

	
	
	/**
	 * The function is used in case of the update of a file
	 * The patamrter includes the name of the file and the new fielname;
	 * @param  $params
	 */
	public static function update($params){
		$path = $params['oldpath'];
		$newpath = $params['newpath'];
		if($path!='' && $newpath!='' && self::isPhoto($path) && self::isPhoto($newpath)){
			OCP\Util::writeLog("facefinder","to update".$path,OCP\Util::DEBUG);
			$photo=OC_FaceFinder_Photo::getPhotoClass($path);
			if(!is_null($photo)){
				$photo->setPath($newpath);
				OC_FaceFinder_Photo::update($photo);
			}
		}
	}
	/**
	 * Help funktien to check the file path no the type 
	 * @param  $path
	 * @return boolean true if is a 'jpg'
	 */
	private static function isPhoto ($path) {
		$ext = strtolower(substr($path, strrpos($path, '.')+1));
		return  $ext=='jpeg' || $ext=='jpg';
	}
	


}
