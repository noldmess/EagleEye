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
interface OC_Module_Interface {
	/**
	 * for the construction of the class you need the path
	 * @param unknown_type $paht
	 */
	public  function __construct($path);
	/**
	 * Insert the data in the module DB 
	 */
	public function insert();
	/**
	 * Remove the data in the module DB
	 */
	public function remove();
	/**
	* Update the data in the module DB
	*/
	public function update($newpaht);
	/**
	 * To search for in the module tables store information
	 * @param String  $query
	 */
	public static function search($query);
	/**
	 * The function returns the id of the last stored information
	 * @return int Id
	 */
	public function getID();
	/**
	 * To search for equivalents the function return a Array of the Ids and percent of equivalents
	 * @return array of id and percent of equivalents
	 */
	public function equivalent();
	/**
	 * To issier create a table insert tis funktion nead the foreign key for the table
	 * @param int $key
	 */
	public function setForingKey($key);
	/**
	 * Create the DB of the Module the if the module hase an new Version numper
	 */
	public static function initialiseDB();
	
	
	/**
	 * check if all tables for module exist
	 * @return boolean
	 */
	public static function AllTableExist();
	
	/**
	 * Drop all Tables that are in contact with the Module
	 */
	public static function  removeDBtabels();
	/**
	 +check if the Version is korekt
	 * @return boolean
	 */
	public static function checkVersion();
}