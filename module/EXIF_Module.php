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

class EXIF_Module implements OC_Module_Interface{
	
		private  $paht;
		private  static $version='0.4.5';
		private $ForingKey=null;
		private $exif;
		private $lang=null;
		public  function __construct($path) {
			$this->lang =new OC_l10n('facefinder');
			$this->paht=$path;
		}
		
		public static function getVersion(){
			return self::$version;
		}
		
		public function  getExifId($key,$valued){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_exif_module` WHERE `name` LIKE ? AND `valued` LIKE ?');
			$result=$stmt->execute(array($key,$valued));
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
		
		public function insertExif($key,$valued){
			$key=($this->lang->t($key));
			$valued=$this->getFormat($key,$valued);
			$exif_id=$this->getExifId($key,$valued);
			if($exif_id!=null){
				return  $exif_id;
			}else{
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_module` ( `name`, `valued`) VALUES ( ?, ?)');
				$result=$stmt->execute(array($key,$valued));
				$exif_id=$this->getExifId($key,$valued);
				if($exif_id!=null)
					return  $exif_id;
			}
				
		}
		
		public function  issetExiPhotoId($photo_id,$exif_id){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_exif_photo_module` WHERE `photo_id`  = ? and `exif_id` = ?');
			$result=$stmt->execute(array($photo_id,$exif_id));
			$rownum=0;
			$id;
			while (($row = $result->fetchRow())!= false) {
				$id=$row['photo_id'];
				$rownum++;
			}
			OCP\Util::writeLog("facefinder",$rownum,OCP\Util::DEBUG);
			return ($rownum==1);
				
		}
		
		public function  getDatePhotoId($photo_id){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_exif_date_module` WHERE `photo_id`  = ?');
			$result=$stmt->execute(array($photo_id));
			while (($row = $result->fetchRow())!= false) {
				return $row['photo_id'];
			}
			return null;
		}
		
		public function  issetDatePhoto($photo_id){
			$id=$this->getDatePhotoId($photo_id);
			if($id!=null){
				return true;
			}else{
				return false;
			}
		}
		
		
		public function insertExifPhoto($id){
			if(!$this->issetExiPhotoId($this->ForingKey,$id)){
				if($id!=null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_photo_module` (`photo_id`, `exif_id`) VALUES ( ?, ?);');
					$result=$stmt->execute(array($this->ForingKey,$id));
				}
			}
		
		
		}
		
		
		public function insertDatePhoto($exifheader){
			if($this->ForingKey!=null ){
					if(!$this->issetDatePhoto($this->ForingKey)){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_date_module` (`date`, `photo_id`) VALUES ( ?, ?)');
					$date=self::getDateOfEXIF($exifheader);
					$stmt->execute(array($date,$this->ForingKey));
					}
				}else{
					OCP\Util::writeLog("facefinder","No id set",OCP\Util::DEBUG);
						$error ='no foringkey set';
						throw new Exception($error);
					
				}
		
		
		}
		
		
		/**
		 * Insert the exif data in the EXIF module Table in the db 
		 * If there is no "DateTimeOriginal" the "FileDateTime" will be  insert
		 * @return null if no exif
		 */
		public function insert(){
			OCP\Util::writeLog("facefinder","No Esdfffffffffffxif Heder:".$this->paht,OCP\Util::DEBUG);
			$exifheader=self::getExitHeader($this->paht);
			if ($exifheader!=null) {
				if(isset($exifheader["EXIF"])){
					foreach ($exifheader["EXIF"] as  $key => $section){
						//if( strlen($section)>0){
							$exif_if=$this->insertExif($key,$section);
							$this->insertExifPhoto($exif_if);
						//}
					}
				}
				$this->insertDatePhoto($exifheader);
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
		
	public static function getExifData($photo_id){
		$stmt = OCP\DB::prepare('SELECT * From *PREFIX*facefinder_exif_date_module inner  join *PREFIX*facefinder on (*PREFIX*facefinder_exif_date_module.photo_id= *PREFIX*facefinder.photo_id)   where uid_owner like ? and *PREFIX*facefinder.photo_id=?;');
		$result=$stmt->execute(array(\OCP\USER::getUser(),$photo_id));
		while (($row = $result->fetchRow())!= false) {
			return array('whitebalance'=>$row['whitebalance'],'iso'=>$row['iso'],'flash'=>$row['flash'],'focallength'=>$row['focallength'],'fnumber'=>$row['fnumber'],'focallengthin35mmfilm'=>$row['focallengthin35mmfilm'],'exposuretime'=>$row['exposuretime'],'make'=>$row['make'],'model'=>$row['model']);
		}
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
			$classname="EXIF_Module";
			$results=array();
			$stmt = OCP\DB::prepare('select * From   *PREFIX*facefinder_exif_module where  valued  like ? or name like ?');
			$result=$stmt->execute(array($query."%",$query."%"));
			while (($row = $result->fetchRow())!= false) {
				$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.urlencode($classname).'&name='.urlencode($row['name']).'&tag='.urlencode($row['valued']);
				$results[]=new OC_Search_Result("Exif",$row['name']."-".$row['valued'],$link,"FaF.");
			}
			return $results;
		}
		
		
		
		
		public static function searchArry($name,$value){
			$results=array();
			$stmt = OCP\DB::prepare(' Select * From  *PREFIX*facefinder_exif_module  inner join  *PREFIX*facefinder_exif_photo_module on(*PREFIX*facefinder_exif_module.id=*PREFIX*facefinder_exif_photo_module.exif_id) inner join *PREFIX*facefinder on  (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_exif_photo_module.photo_id)where`valued` like ? and `name` like ?');
			$result=$stmt->execute(array($value,$name));
			while (($row = $result->fetchRow())!= false) {
				$results[]=$row['path'];
			}
			return $results;
		}
	
		public static function getFormat($name,$value){
			$return=0;
			switch ($name){
				case 'ISOSpeedRatings':
					$return=$value." ISO";
				break;
				
				case 'Flash':
					$return= self::getFlash($value);
					break;
					
				case 'FNumber':
					$return="f/".self::divi($value);
					break;
					
				case 'WhiteBalance':
					$return=self::getWhiteBalance($value)." White Balance";
					break;
					
				case 'FocalLength':
					$return=self::divi($value)." mm";
					break;
					
				case 'FocalLengthIn35mmFilm':
						$return=$value." mm(35mm)";
						break;
						
				case 'ExposureTime':
					$return=self::getExposureTime($value);
					break;
						
				case 'WhiteBalance':
					$return=self::getWhiteBalance($value).": White Balance";
					break;
					
				case 'make':
					$return=self::$value." Mark";
					break;
					
				default:
					$return=$value;
					break;
					
			}
			return $return;
		}
	
		public static function divi($value){
			$first_token  = strtok($value, '/');
			$second_token = strtok('/');
			return $first_token/$second_token;
		}
		
		public static function getExposureTime($value){
			$first_token  = strtok($value, '/');
			$second_token = strtok('/');
			if ($first_token > $second_token) {
				return $first_token."s";
			}else{
				return ($first_token/10).'/'.($second_token/10)."s";
			}
		}
		
		
		public static function getFlash($value){
			//http://bueltge.de/exif-daten-mit-php-aus-bildern-auslesen/486/
			switch($value) {
				case 0:
					$fbexif_flash = 'Flash did not fire';
					break;
				case 1:
					$fbexif_flash = 'Flash fired';
					break;
				case 5:
					$fbexif_flash = 'Strobe return light not detected';
					break;
				case 7:
					$fbexif_flash = 'Strobe return light detected';
					break;
				case 9:
					$fbexif_flash = 'Flash fired, compulsory flash mode';
					break;
				case 13:
					$fbexif_flash = 'Flash fired, compulsory flash mode, return light not detected';
					break;
				case 15:
					$fbexif_flash = 'Flash fired, compulsory flash mode, return light detected';
					break;
				case 16:
					$fbexif_flash = 'Flash did not fire, compulsory flash mode';
					break;
				case 24:
					$fbexif_flash = 'Flash did not fire, auto mode';
					break;
				case 25:
					$fbexif_flash = 'Flash fired, auto mode';
					break;
				case 29:
					$fbexif_flash = 'Flash fired, auto mode, return light not detected';
					break;
				case 31:
					$fbexif_flash = 'Flash fired, auto mode, return light detected';
					break;
				case 32:
					$fbexif_flash = 'No flash function';
					break;
				case 65:
					$fbexif_flash = 'Flash fired, red-eye reduction mode';
					break;
				case 69:
					$fbexif_flash = 'Flash fired, red-eye reduction mode, return light not detected';
					break;
				case 71:
					$fbexif_flash = 'Flash fired, red-eye reduction mode, return light detected';
					break;
				case 73:
					$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode';
					break;
				case 77:
					$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected';
					break;
				case 79:
					$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected';
					break;
				case 89:
					$fbexif_flash = 'Flash fired, auto mode, red-eye reduction mode';
					break;
				case 93:
					$fbexif_flash = 'Flash fired, auto mode, return light not detected, red-eye reduction mode';
					break;
				case 95:
					$fbexif_flash = 'Flash fired, auto mode, return light detected, red-eye reduction mode';
					break;
				default:
					$fbexif_flash = '';
					break;
			}
			return $fbexif_flash;
		}
		
		public static function getWhiteBalance($value){
			switch($value) {
				case 0:
					$fbwhitebalance = "Auto";
					break;
				case 1:
					$fbwhitebalance = "Daylight";
					break;
				case 2:
					$fbwhitebalance = "Fluorescent";
					break;
				case 3:
					$fbwhitebalance = "Incandescent";
					break;
				case 4:
					$fbwhitebalance = "Flash";
					break;
				case 9:
					$fbwhitebalance = "Fine Weather";
					break;
				case 10:
					$fbwhitebalance = "Cloudy";
					break;
				case 11:
					$fbwhitebalance = "Shade";
					break;
				default:
					$fbwhitebalance = '';
					break;
			}
			return $fbwhitebalance;
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
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_date_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_photo_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_module');
			$stmt->execute();
			OCP\Util::writeLog("facefinder","No Esdfffffffffffxif Heder:".$this->paht,OCP\Util::DEBUG);
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			/*$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_date_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_photo_module`  ADD FOREIGN KEY (exif_id) REFERENCES *PREFIX*facefinder_exif_module(id)ON DELETE CASCADE');
			$stmt->execute();*/
		}
		
		
		
		/**
		 * Create the DB of the Module the if the module hase an new Version numper
		 */
		public static  function  initialiseDB(){
			OCP\Util::writeLog("facefinder","EXIF_Module 2",OCP\Util::DEBUG);
			$classname="EXIF_Module";
			if(OC_Appconfig::hasKey('facefinder',$classname)){

				/**
				 * @todo check korektnes
				 */
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
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_module\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			 $table_exif=($rownum==1);
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_date_module\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			$table_date=($rownum==1);
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_photo_module\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			$table=($rownum==1);
			return ($table_exif && $table_date && $table);
		}
		
		public static function checkVersion(){
			$classname="EXIF_Module";
			$appkey=OC_Appconfig::getValue('facefinder',$classname);
			return version_compare($appkey, self::getVersion(), '<');
		}

	}