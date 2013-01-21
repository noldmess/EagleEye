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
class Kamera_Module implements OC_Module_Interface{

	private  $paht;
	private  static $version='0.0.2';
	private $ForingKey=null;
	private $exif;
	public  function __construct($path) {
		$this->paht=$path;
	}

	public static function getVersion(){
		return self::$version;
	}


	
	public function  getKameraPhotoId($photo_id,$kamera_id){
		$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_kamera_photo_module` WHERE `photo_id`  = ? = `kamera_id` = ?');
		$result=$stmt->execute(array($photo_id,$kamera_id));
		while (($row = $result->fetchRow())!= false) {
			return $row['photo_id'];
		}
		return null;
	
	}

	public function  issetKameraPhotoId($photo_id,$kamera_id){
		return ($this->getKameraPhotoId($photo_id,$kamera_id)!=null);
		
	}

	public function insertKameraPhoto($id){
		if(!$this->issetKameraPhotoId($this->ForingKey,$id)){
			if($id!=null){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_kamera_photo_module` (`photo_id`, `kamera_id`) VALUES ( ?, ?);');
				$result=$stmt->execute(array($this->ForingKey,$id));
			}
		}


	}



	/**
	 * Insert the exif data in the EXIF module Table in the db
	 * If there is no "DateTimeOriginal" the "FileDateTime" will be  insert
	 * @return null if no exif
	 */
	public function insert(){
		OCP\Util::writeLog("facefinder","insert_ Kamera",OCP\Util::DEBUG);
		$exifheader=self::getExitHeader($this->paht);
		$kamera=$this->getKamera($exifheader);
		$kamera_id=$this->insertKamera($kamera[0],$kamera[1]);
		if ($exifheader!=null) {
			if($this->ForingKey!=null ){
				$this->insertKameraPhoto($kamera_id);
			}else{
				OCP\Util::writeLog("facefinder","No id set",OCP\Util::ERROR);
			}
		}else{
			OCP\Util::writeLog("facefinder","No Exif Heder:".$this->paht,OCP\Util::DEBUG);
			return null;
		}
		$this->existe=true;
	}

	/**
	 * @return return the primary key sql table
	 */
	public function getID(){
		/**
		 * @todo
		 */
	}
	/**
	 * the funktion extract  the exif data of an image
	 * @param Path to the image $paht
	 * @return EXIF array or NULL if no EXIF header
	 */
	public static   function getExitHeader($path){
		if (OC_Filesystem::file_exists($path)) {
			return exif_read_data(OC_Filesystem::getLocalFile($path),'FILE', true);
		}else{
			return  null;
		}
	}

	/**
	 * the function returns the camera Make and the module and nall un not exist
	 * @param $exifheader
	 * @return Ambigous <multitype:, multitype:NULL , NULL>
	 */
	public  static function getKamera($exifheader){
		$kamera=array();
		if(isset($exifheader["IFD0"]["Make"]) && isset($exifheader["IFD0"]["Model"])){
			$kamera=array($exifheader["IFD0"]["Make"],$exifheader["IFD0"]["Model"]);
		}else{
			$kamera=null;
		}
		return $kamera;
	}
	/**
	 *
	 * @param unknown_type $kamera
	 * @return $is|NULL
	 */
	public function  getKameraId($make, $model){
		$stmt = OCP\DB::prepare('SELECT *FROM `*PREFIX*facefinder_kamera_module` WHERE  `make` LIKE ? AND `model` LIKE ?');
		$result=$stmt->execute(array($make, $model));
		$rownum=0;
		$id;
		while (($row = $result->fetchRow())!= false) {
			$id=$row['id'];
			$rownum++;
		}
		if($rownum==1){
			return  $id;
		}else{
			return null;
		}
	}

	/**
	 * The Funktioen insert the kamera model and marke in the DB and Retund the ID
	 * @param String $make name
	 * @param String $model name
	 * @return Ambigous <$is, NULL, unknown>|NULL
	 */
	public function insertKamera($make, $model){
			$kamera_id=$this->getKameraId($make, $model);
			if($kamera_id!=null)
				return  $kamera_id;
			else{
				if($make!=null && $model!=null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_kamera_module` ( `make`, `model`) VALUES (?,?)');
					$result=$stmt->execute(array($make, $model));
					$kamera_id=$this->getKameraId($make, $model);
					if($kamera_id!=null)
						return  $kamera_id;
				}
			}
		return null;
	}



	public function remove(){
		/**
		 * @todo
		 */
	}

	public function  update($newpaht){
		/**
		 * @todo
		 */
			
	}




	public static function search($query){
		$classname="Kamera_Module";
		$results=array();
		$stmt = OCP\DB::prepare('select * From   *PREFIX*facefinder_kamera_module where  model  like ? or make like ?');
		$result=$stmt->execute(array($query."%",$query."%"));
		while (($row = $result->fetchRow())!= false) {
		$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.urlencode($classname).'&name='.urlencode($row['model']).'&tag='.urlencode($row['make']);
				$results[]=new OC_Search_Result("Kamera",$row['model']."-".$row['make'],$link,"FaF.");
		}
				return $results;
	}




	public static function searchArry($name,$value){
	// select * from *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) inner join *PREFIX*facefinder on (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id);
		$results=array();
		$stmt = OCP\DB::prepare(' Select * From  *PREFIX*facefinder_kamera_module  inner join  *PREFIX*facefinder_kamera_photo_module on(*PREFIX*facefinder_kamera_module.id=*PREFIX*facefinder_kamera_photo_module.kamera_id) inner join *PREFIX*facefinder on  (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_kamera_photo_module.photo_id)where `make` like ? and `model` like ?');
			$result=$stmt->execute(array($value,$name));
			while (($row = $result->fetchRow())!= false) {
			$results[]=$row['path'];
			OCP\Util::writeLog("facefinder",$row['path'],OCP\Util::DEBUG);
		}
		return $results;
	}






			public function equivalent(){
			/**
			* @todo
			 */
			}

			/**
		 * Uset to set the ForingKey for the module tables
		 * @param ForingKey
		 */
		 public function  setForingKey($key){
			$this->ForingKey=$key;
			}
			/**
		 * Help Funktioen to create  module DB Tables
		 * @param String of $classname
		 */
		 private static function createDBtabels($classname){
		 	
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_kamera_photo_module');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_kamera_module`');
			$stmt->execute();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_kamera_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_kamera_photo_module`  ADD FOREIGN KEY (kamera_id) REFERENCES *PREFIX*facefinder_kamera_module(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
			OCP\Util::writeLog("facefinder","Kamera_Module 4",OCP\Util::DEBUG);
			}



			/**
		 * Create the DB of the Module the if the module hase an new Version numper
		  */
			public static  function  initialiseDB(){
					OCP\Util::writeLog("facefinder","Kamera_Module 2",OCP\Util::DEBUG);
			  $classname="Kamera_Module";
				if(OC_Appconfig::hasKey('facefinder',$classname)){
					 if (self::checkVersion() || !self::AllTableExist()){
			  			OCP\Util::writeLog("facefinder","need update",OCP\Util::DEBUG);
			  			OC_Appconfig::setValue('facefinder',$classname,self::getVersion());
			  			self::createDBtabels($classname);
			  			return true;
			  		}else{
						return false;
					}
				}else{
						/**
					 * @todo
					 	*/
					 	OCP\Util::writeLog("facefinder","not jet used ".self::getVersion(),OCP\Util::INFO);
					 	OC_Appconfig::setValue('facefinder',$classname,self::getVersion());
					 	self::createDBtabels($classname);
					 	return true;
				}
			}

		  /**
		   * check if all tables for module exist
		   * @return boolean
		   */
		   public static function AllTableExist(){
			   $stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_kamera_photo_module\'');
			   $result=$stmt->execute();
			   $rownum=0;
			   while (($row = $result->fetchRow())!= false) {
			   	$rownum++;
			   }
			   $table_kamera=($rownum==1);
			   
			   $stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_kamera_module\'');
			   $result=$stmt->execute();
			   $rownum=0;
			   while (($row = $result->fetchRow())!= false) {
					$rownum++;
				}
				$table_kamera_photo=($rownum==1);
				return ($table_kamera_photo && $table_kamera);
			}

			public static function checkVersion(){
				$classname="Kamera_Module";
				$appkey=OC_Appconfig::getValue('facefinder',$classname);
				return version_compare($appkey, self::getVersion(), '<');
		}

	}