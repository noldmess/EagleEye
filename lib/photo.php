<?php
//namespace OC\FaceFinder;
/**
 * The class OC_FaceFinder_Photo is the basic functionality for the module concept 
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
include_once 'moduleinterface.php';
class OC_FaceFinder_Photo implements OC_Module_Interface{
	
	private  $paht;
	private  static $version='0.0.1';
	private $user;

	/**
	 * 
	 * @param Insert the $paht in the facefinder tabel in the db
	 */
	public  function __construct($paht) {
		$this->paht=$paht;
	}
	/**
	 * Insert the Photo in the SQL Database
	 */
	public function insert(){
			$id=$this->getID();
			if($id==-1){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder` ( `uid_owner`, `path`) VALUES (?, ?)');
				$stmt->execute(array($this->getUser(),$this->paht));
				$this->existe=true;
			}
	}
	
	/**
	 * @return return the primary key sql table 
	 */
	public function getID(){
			$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
			$result = $stmt->execute(array($this->getUser(), $this->paht));
			$id=-1;
				while (($row = $result->fetchRow())!= false) {
					 $id=$row['photo_id'];
				}
				return  $id;
			}
		
	
			/**
			 * Temove the Photo from the SQL Database
			 */
	public function remove(){
		$id=$this->getID();
		if($id!=-1){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `photo_id` = ?');
			$stmt->execute(array($this->getUser(),$id));
		}else{
			/**
			 * @todo use translation funktion
			 */
			OCP\Util::writeLog("facefinder","No such file".$id,OCP\Util::ERROR);
		}
	}
	/**
	 * Update the Photo in the SQL Database
	 */
	public function  update($newpaht){
		$id=$this->getID();
		if($id!=-1){
			$stmt = OCP\DB::prepare('UPDATE `*PREFIX*facefinder` SET `path` = ? WHERE `uid_owner` LIKE ? AND `photo_id` = ? ');
			$stmt->execute(array($newpaht,$this->getUser(),$id));
			$this->paht=$newpaht;
		}
	}
	
	public function search($query){
		/**
		 * @todo
		 */
	}

	
	public function equivalent(){
		/**
		 * @todo
		 */
	}
	
	public function  setForingKey($key){
		/**
		 * @todo
		 */
	}
	
	public static function initialiseDB(){
		/**
		 * @todo
		 */
	}
	
	public  function  setUser($user){
		$this->user=$user;
	}
	
	public function  getUser(){
		if($this->user!=null){
			$user_tmp=$this->user;
		}else{
			$user_tmp=\OCP\USER::getUser();
		}
		return  $user_tmp;
	}
	
	/**
	 * check if all tables for module exist
	 * @return boolean
	 */
	public static function AllTableExist(){
			
	}
	
}