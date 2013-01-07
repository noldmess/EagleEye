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
require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
class EXIF_Module implements OC_Module_Interface{
	
		private  $paht;
		private  static $version='0.0.9';
		private $ForingKey=null;
		private $exif;
		public  function __construct($path) {
			$this->paht=$path;
		}
		
		public static function getVersion(){
			return self::$version;
		}
		/**
		 * Insert the exif data in the EXIF module Table in the db 
		 * If there is no "DateTimeOriginal" the "FileDateTime" will be  insert
		 * @return null if no exif
		 */
		
		public function insert(){
			$exifheader=self::getExitHeader($this->paht);
			$kamera_id=$this->insertKamera($exifheader);
			if ($exifheader!=null) {
				if($this->ForingKey!=null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_module` (`date`, `photo_id`,`kamera_id`,`fnumber`,`focallength`,`focallengthin35mmfilm`,`iso`,`whitebalance`,`flash`,`exposuretime`) VALUES ( ?, ?,?,?,?,?,?,?,?,?)');
					$date=self::getDateOfEXIF($exifheader);
					$photo_info=self::getPhotoData($exifheader);
					$stmt->execute(array($date,$this->ForingKey,$kamera_id,$photo_info['FNumber'],$photo_info['FocalLength'],$photo_info['FocalLengthIn35mmFilm'],$photo_info['ISOSpeedRatings'],$photo_info['WhiteBalance'],$photo_info['Flash'],$photo_info['ExposureTime']));
				}else{
					OCP\Util::writeLog("facefinder","No id set",OCP\Util::ERROR);
					
						$error ='no foringkey set';
						throw new Exception($error);
					
				}
			}else{
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
				//@todo
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
	 * @return unknown|NULL
	 */
	public function  getKameraId($kamera){
		$stmt = OCP\DB::prepare('SELECT *FROM `*PREFIX*facefinder_exif_module_kamera` WHERE  `make` LIKE ? AND `model` LIKE ?');
		$result=$stmt->execute($kamera);
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
	 * The funktion insert the date of the camera in the database a
	 * @param $exifheader
	 * @return $id of table or null if no camera information
	 */
	public function insertKamera($exifheader){
		$kamera=$this->getKamera($exifheader);
		if($kamera!=null){
			$kamera_id=$this->getKameraId($kamera);
			if($kamera_id!=null)
				return  $kamera_id;
			else{
				//INSERT INTO `*PREFIX*facefinder_exif_module_kamera` ( `make`, `model`) VALUES (?,?);
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_module_kamera` ( `make`, `model`) VALUES (?,?)');
				$result=$stmt->execute($kamera);
				$kamera_id=$this->getKameraId($kamera);
				if($kamera_id!=null)
					return  $kamera_id;
			}
		}
		return null;
	} 
	
	
	/**
	 * The function returns the exif data from the photo
	 * @param $exifheader
	 * @return array of exif data
	 */
	public  static function getPhotoData($exifheader){
		
		/*return array('FNumber'=>$exifheader["EXIF"]["FNumber"],'FocalLength'=>$exifheader["EXIF"]["FocalLength"],'FocalLengthIn35mmFilm'=>$exifheader["EXIF"]["FocalLengthIn35mmFilm"],'ISOSpeedRatings'=>$exifheader["EXIF"]["ISOSpeedRatings"],'WhiteBalance'=>$exifheader["EXIF"]["WhiteBalance"],'Flash'=>$exifheader["EXIF"]["Flash"],'ExposureTime'=>$exifheader["EXIF"]["ISOSpeedRatings"]);*/
		if(isset($exifheader["EXIF"]["FNumber"])) {
			$Photo['FNumber']=$exifheader["EXIF"]["FNumber"];
		}else{
			$Photo['FNumber']=null;
		}
		if(isset($exifheader["EXIF"]["FocalLength"])) {
			$Photo['FocalLength']=$exifheader["EXIF"]["FocalLength"];
		}else{
			$Photo['FocalLength']=null;
		}
		
		if(isset($exifheader["EXIF"]["FocalLengthIn35mmFilm"])) {
			$Photo['FocalLengthIn35mmFilm']=$exifheader["EXIF"]["FocalLengthIn35mmFilm"];
		}else{
			$Photo['FocalLengthIn35mmFilm']=null;
		}
		if(isset($exifheader["EXIF"]["ISOSpeedRatings"])) {
			$Photo['ISOSpeedRatings']=$exifheader["EXIF"]["ISOSpeedRatings"];
		}else{
			$Photo['ISOSpeedRatings']=null;
		}
		if(isset($exifheader["EXIF"]["WhiteBalance"])) {
			$Photo['WhiteBalance']=$exifheader["EXIF"]["WhiteBalance"];
		}else{
			$Photo['WhiteBalance']=null;
		}
		if(isset($exifheader["EXIF"]["Flash"])) {
			$Photo['Flash']=$exifheader["EXIF"]["Flash"];
		}else{
			$Photo['Flash']=null;
		}
		if(isset($exifheader["EXIF"]["ExposureTime"])) {
			$Photo['ExposureTime']=$exifheader["EXIF"]["ExposureTime"];
		}else{
			$Photo['ExposureTime']=null;
		}
		return $Photo;
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
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_module_kamera`');
			$stmt->execute();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_module`  ADD FOREIGN KEY (kamera_id) REFERENCES *PREFIX*facefinder_exif_module_kamera(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
		}
		
		/**
		 * Create the DB of the Module the if the module hase an new Version numper
		 */
		public static  function  initialiseDB(){
			$classname="EXIF_Module";
			if(OC_Appconfig::hasKey('facefinder',$classname)){
				$appkey=OC_Appconfig::getValue('facefinder',$classname);

				/**
				 * @todo check korektnes
				 */
				if (version_compare($appkey, self::getVersion(), '<') || !self::AllTableExist()){
						OCP\Util::writeLog("facefinder","need update",OCP\Util::DEBUG);
						OC_Appconfig::setValue('facefinder',$classname,self::getVersion());
						self::createDBtabels($classname);
				}
			}else{
				/**
				 * @todo 
				 */
				OCP\Util::writeLog("facefinder","not jet used ".self::getVersion(),OCP\Util::INFO);
				OC_Appconfig::setValue('facefinder',$classname,self::getVersion());
				self::createDBtabels($classname);
			}
		}
		
		/**
		 * check if all tables for module exist
		 * @return boolean 
		 */
		public static function AllTableExist(){
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_module\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			 $table_exif=($rownum==1);
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_module_kamera\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			$table_kamera=($rownum==1);
			return ($table_exif && $table_kamera);
		}
		
		

	}