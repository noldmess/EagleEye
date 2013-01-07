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

		$path = $params[OC_Filesystem::signal_param_path];
		if(self::isPhoto($path)){
		$tmp=new OC_FaceFinder_Photo($path);
		$tmp->insert();
		$id=$tmp->getID();
		
		/*
		* 	Implemetation of the module system 
		 */
		$writemodul=new OC_Module_Maneger();
		$moduleclasses=$writemodul->getModuleClass();
			foreach ($moduleclasses as $moduleclass){
				$class=new $moduleclass($path);
				$class->setForingKey($id);
				$class->insert();
			}
			//OCP\Util::writeLog("facefinder","Nfsdfsdfsdfo id set".$path,OCP\Util::DEBUG);
		}	
	}
	/**
	 * The function is used in case of the delete of a file
	 * The patamrter includes the name of the file;
	 * @param  $params
	 */
	public static function delete($params){
		$path = $params[OC_Filesystem::signal_param_path];
		if($path!=''&& self::isPhoto($path)){
		OCP\Util::writeLog("facefinder","to delite".$path,OCP\Util::DEBUG);
		$tmp=new OC_FaceFinder_Photo($path);
		$tmp->remove();
		}
	}
	
	/**
	 * The function is used in case of the update of a file
	 * The patamrter includes the name of the file and the new fielname;
	 * @param  $params
	 */
	public static function update($params){
		$path = $params[OC_Filesystem::signal_param_oldpath];
		$newpath = $params[OC_Filesystem::signal_param_newpath];
		if($path!='' && $newpath!='' && self::isPhoto($path) && self::isPhoto($newpath)){
			OCP\Util::writeLog("facefinder","to update".$path,OCP\Util::DEBUG);
			$tmp=new OC_FaceFinder_Photo($path);
			$tmp->update($newpath);
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
