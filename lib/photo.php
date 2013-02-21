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
			if(!$this->issetPhotoId()){
				$hash=hash_file("sha256",OC_Filesystem::getLocalFile($this->paht));
				$exifheader=self::getExitHeader($this->paht);
				$date=self::getDateOfEXIF($exifheader);
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder` ( `uid_owner`, `path`,`hash`,`date_photo`)   VALUES (?, ?,?,?)');
				$stmt->execute(array(\OCP\USER::getUser(),$this->paht,$hash,$date));
			}
	}
	
		/**
		 * The funktion extract  the exif data of an image 
		 * @param String  $paht
		 * @return NULL | EXIFheader
		 */
	public static   function getExitHeader($path){
		if (OC_Filesystem::file_exists($path)) {
			return exif_read_data(OC_Filesystem::getLocalFile($path),'FILE', true);
		}else{
			return  null;
		}
	}
	
	
	/**
	 * Function return date of image
	 * @param EXIF array $exifheader
	 * @return Date of Image If there is no "DateTimeOriginal" the "FileDateTime" will be  used
	 */
	public static function getDateOfEXIF($exifheader){
		if(!is_array($exifheader)|| !isset($exifheader['FILE'])){
			return null;
		}
		if(isset($exifheader['EXIF'])){
			$date=$exifheader['EXIF']['DateTimeOriginal'];
		}else{
			if(isset($exifheader['FILE'])){
				$date=date('Y:m:d H:i:s',$exifheader['FILE']['FileDateTime']);
			}else{
				$date=date('Y:m:d H:i:s');
					
			}
		}
		return $date;
	}
	
	/**
	 * @return return the primary key sql table 
	 */
	public function getID(){
			$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
			$result = $stmt->execute(array(\OCP\USER::getUser(), $this->paht));
			$id=-1;
				while (($row = $result->fetchRow())!= false) {
					 $id=$row['photo_id'];
				}
				return  $id;
			}
	
	/**
	 * The function check if the ExiPhoto already exist
	 * @param int  $exif_id
	 * @return boolean
	 */
	public function  issetPhotoId(){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
		$result = $stmt->execute(array(\OCP\USER::getUser(), $this->paht));
		return ($result->numRows()==1);
	
	}
	

	
			/**
			 * Temove the Photo from the SQL Database
			 */
	public function remove(){
		$id=$this->getID();
		if($id!=-1){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `photo_id` = ?');
			$stmt->execute(array(\OCP\USER::getUser(),$id));
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
			$stmt->execute(array($newpaht,\OCP\USER::getUser(),$id));
			$this->paht=$newpaht;
		}
	}
	
	public static function  search($query){
		/**
		 * @todo
		 */
	}

	
	public function equivalent(){
		//hard coded value for each module and and the value of the eqaletti between 1 and 100
		//$value=100;
		$eq=new OC_Equal(100);
		$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder where  uid_owner like ?order by hash,date_photo');
		$result=$stmt->execute(array(\OCP\USER::getUser()));
		$hash=null;
		$date="";
		$array=array();
		$path="";
		$help=array();
		while (($row = $result->fetchRow())!= false) {
			if($hash!=$row['hash']){
				if($hash!=null) {
						//$array+=array($path=>array("value"=>$value,"equival"=>$help));
						$eq->addFileName($path);
				}
				$help=array();
				$hash=$row['hash'];
				$path=$row['path'];
			}else{
				$eq->addSubFileName($row['path']);
			}
				
		}
	//	if(count($help)>0){
			//$array+=array($path=>array("value"=>$value,"equival"=>$help));
			$eq->addFileName($path);
	//	}
		return $eq->getEqualArray();
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
	
	/**
	 * Drop all Tables that are in contact with the Module
	 */
	public static function  removeDBtabels(){}
	
	/**
	 * check if all tables for module exist
	 * @return boolean
	 */
	public static function AllTableExist(){
			
	}
	
	
	public static function checkVersion(){
		
	}
	
	public static function getArrayOfStyle(){}
	
	
	public static function getArrayOfScript(){}
	
}
