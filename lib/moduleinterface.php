<?php
//namespace OC\FaceFinder;
/**
 * The interface is needed to implement modules for FaceFinder the modules have to be stored in the module folder
 * ownCloud - facefinder
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
abstract class OC_Module_Interface {
	 private static $classname;
	private static $version;
	/**
	 * Insert the data in the module DB 
	 */
	 abstract public  function insert($class);
	/**
	 * Remove the data in the module DB
	 */
	 abstract protected function remove();
	/**
	* Update the data in the module DB
	*/
	 abstract public static  function update($newpaht);
	/**
	 * To search for in the module tables store information
	 * @param String  $query
	 */
	 abstract protected static function search($query);
	/**
	 * To search for equivalents the function return a Array of the Ids and percent of equivalents
	 * @return array of id and percent of equivalents
	 */
	 abstract protected function equivalent();
	/**
	 * Create the DB of the Module the if the module hase an new Version numper
	 */
	 abstract public static  function createDBtabels($classname);
	 
	abstract public static function getClass($foringkey);
		/**
		 * Create the DB of the Module the if the module hase an new Version numper
		 * @return boolean
		 */
		 static public function  initialiseDB(){
			//check if module is already installed
			if(OC_Appconfig::hasKey('facefinder',self::$classname)){
				//check if the module is in the correct version and all Tables exist
				if (self::checkVersion() || !static::AllTableExist()){
					//create all tables and update version number
						static::createDBtabels(static::$classname);
						OC_Appconfig::setValue('facefinder',static::$classname,self::getVersion());
						return true;
				}else{
					return false;
				}
			}else{
				//create all tables and update version number
				static::createDBtabels(self::$classname);
				OC_Appconfig::setValue('facefinder',self::$classname,self::getVersion());
				return true;
			}
		}
	
	
	/**
	 * check if all tables for module exist
	 * @return boolean
	 */
	 abstract protected static function AllTableExist();
	
	/**
	 * Drop all Tables that are in contact with the Module
	 */
	 abstract public static function  removeDBtabels();
	/**
	 +check if the Version is korekt
	 * @return boolean
	 */
		public static function checkVersion(){
			$appkey=OC_Appconfig::getValue('facefinder',self::$classname);
			return version_compare($appkey, self::getVersion(), '<');
		}
		

		public static function getVersion(){
			return self::$version;
		}
		
	 abstract public static function getArrayOfStyle();
	
	
	 abstract public static function getArrayOfScript();
}