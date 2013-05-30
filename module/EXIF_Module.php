<?php
use OCA\FaceFinder;
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

class EXIF_Module implements OCA\FaceFinder\MapperInterface{
	
		private static $classname="EXIF_Module";
		private  static $version='0.4.5';
		public  function __construct() {
			
		}
		
		/**
		 * The Funktion returne the id or NULL if ther is no result 
		 * @param String $name
		 * @param String $valued
		 * @return ID|NULL
		 */
		public static  function  getExifId($key,$valued){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_exif_module` WHERE `name` LIKE ? AND `valued` LIKE ?');
			$result=$stmt->execute(array($key,$valued));
			while (($row = $result->fetchRow())!= false) {
				//return id if ther is a resolt
				return $row['id'];
			}
			//null if no result 
			return null;

		}
		/**
		 * The function insert the name and the value in the DB and returns the Id
		 * @param String $key
		 * @param String $valued
		 * @return Ambigous <ID, NULL>
		 */
		public static function insertExif($name,$valued){
			$lang =new OC_l10n('facefinder');
			//Translate the Name
			$name=($lang->t($name));
			//return a value that is easier to read
			$valued=EXIF_ModuleClass::getFormat($name,$valued);
			//check if name and the value is already inserted
			$exif_id=self::getExifId($name,$valued);
			if($exif_id!=null){
				return  $exif_id;
			}else{
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_module` ( `name`, `valued`) VALUES ( ?, ?)');
				$result=$stmt->execute(array($name,$valued));
				$exif_id=self::getExifId($name,$valued);
				//insert the name and the value in the DB
				if($exif_id!=null)
					return  $exif_id;
			}
				
		}
		
		/**
		 * The function check if the ExiPhoto already exist 
		 * @param int  $exif_id
		 * @return boolean
		 */
		public static function  issetExiPhotoId($exif_id,$class){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_exif_photo_module` WHERE `photo_id`  = ? and `exif_id` = ?');
			$result=$stmt->execute(array($class->getForingkey(),$exif_id));
			//check if the result is a single row
			return ($result->numRows()==1);
				
		}
		
		
		/**
		 * The function insert the ExifPhoto in the DB 
		 * @param unknown_type $id
		 */
		public static  function insertExifPhoto($id,$class){
			if($id!=null){
				//check if id is NULL if NULL nothing to insert
				if(!self::issetExiPhotoId($id,$class)){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_exif_photo_module` (`photo_id`, `exif_id`) VALUES ( ?, ?);');
					$result=$stmt->execute(array($class->getForingkey(),$id));
				}
			}
		}
		

		
		
		/**
		 * Insert the exif data in the EXIF module Table in the DB
		 * @return null if no exif
		 */
		public static function insert($class){
			//for image without exif data
				if($exifarray=$class->getExitHeader()==!null){
					foreach ($class->getExitHeader() as  $key => $section){
							$exif_id=self::insertExif($key,$section);
							self::insertExifPhoto($exif_id,$class);
					}
				}
				
		}
	
		/**
		 * @return return the primary key sql table
		 */
		public function getID(){
				/**
			 * @todo
			 */
		}

	
		public static function remove(){
				/**
			 * @todo
			 */
		}
	
		public static function  update($newpaht){
			/**
			 * @todo
			 */
			
		}
		
		
		public static function getClass($foringkey){
			$stmt = \OCP\DB::prepare('select * from oc_facefinder as base  inner join oc_facefinder_exif_photo_module as exifphoto on (base.photo_id=exifphoto.photo_id) inner join oc_facefinder_exif_module as exif on (exifphoto.exif_id=exif.id) where exifphoto.photo_id=?');
			$result = $stmt->execute(array($foringkey));
			$tagarray=array();
			while (($row = $result->fetchRow())!= false) {
				$tagarray+=array($row['name']=>$row['valued']);
			}
			//OCP\Util::writeLog("facefinder",json_encode($tagarray),OCP\Util::ERROR);
			$class=EXIF_ModuleClass::getInstanceBySQL(1,$tagarray,$foringkey);
			
			return $class;
		}
		

		
		/**
		 * The function receive a String and returns OC Search_Result
		 * @param String $query
		 * @return multitype:OC_Search_Result
		 */
		public static function search($query){
			$results=array();
			//search over value  and  name 
			$stmt = OCP\DB::prepare('select name,valued From   *PREFIX*facefinder_exif_module inner join  *PREFIX*facefinder_exif_photo_module on(*PREFIX*facefinder_exif_module.id=*PREFIX*facefinder_exif_photo_module.exif_id) inner join *PREFIX*facefinder on  (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_exif_photo_module.photo_id)  where (valued  like ? or name like ?) and uid_owner like ? GROUP BY valued');
			$result=$stmt->execute(array($query."%",$query."%",\OCP\USER::getUser()));
			while (($row = $result->fetchRow())!= false) {
				//create a link for the search 
				$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.urlencode(self::$classname).'&name='.urlencode($row['name']).'&tag='.urlencode($row['valued']);
				$results[]=new OC_Search_Result("Exif",$row['name']."-".$row['valued'],$link,"FaF.");
			}
			return $results;
		}
		
		
		
		/**
		 * The function return the path for the search request
		 * @param String $name
		 * @param String $value
		 * @return multitype:photo_phate
		 */
		public static function searchArry($name,$value){
			$results=array();
			$stmt = OCP\DB::prepare(' Select * From  *PREFIX*facefinder_exif_module  inner join  *PREFIX*facefinder_exif_photo_module on(*PREFIX*facefinder_exif_module.id=*PREFIX*facefinder_exif_photo_module.exif_id) inner join *PREFIX*facefinder on  (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_exif_photo_module.photo_id) where`valued` like ? and `name` like ? and uid_owner like ? ');
			$result=$stmt->execute(array($value,$name,\OCP\USER::getUser()));
			while (($row = $result->fetchRow())!= false) {
				$results[]=array($row['path'],$row['photo_id']);
			}
			return $results;
		}
	
		
		/**
		 * The funktion compares all exif if 95% are equal add to the OC Equal object
		 * @return OC_Equal
		 */
		public function equivalent(){
			//hard coded value for each module and and the value of the eqaletti between 1 and 100
			$value=1;
			$s=new OCA\FaceFinder\OC_Equal(1);
			//get all information of a Photo from the DB
			$stmt = OCP\DB::prepare('select path,name,valued    from *PREFIX*facefinder as base  inner join *PREFIX*facefinder_exif_photo_module as exifphoto on (base.photo_id=exifphoto.photo_id) inner join *PREFIX*facefinder_exif_module as exif on (exifphoto.exif_id=exif.id) where uid_owner like ?  order by path,name');
			$result=$stmt->execute(array(\OCP\USER::getUser()));
			$array=array();
			$path=null;
			//build a new  array of all information for each Photo 
			while (($row = $result->fetchRow())!= false) {
				if($path!=$row['path']){
					if($path!=null) {
						$array+=array($path=>$help);
					}
					$help=array();
					$help=$help+array($row['name']=>$row['valued']);
					$path=$row['path'];
				}else{
					$help=$help+array($row['name']=>$row['valued']);
				}
			}
			$array+=array($path=>$help);
			
			//array whit the equivalent elements  
			$array_eq=array();
			$name=null;
			//check all element
			while ($array_tag1 = current($array)) {
					//if there is no equal photo we dont net photo
     			   if($name!=null ){
     			   		$s->addFileName($name);
     			   }
     			   $eq=array();
     			   $name=key($array);
     			   $arrays=$array;
     			   foreach($arrays as $helpNameCheach=>$array_tag2){

     			   	//not check if it has the same name
     			   	if($name!=$helpNameCheach){
     			   		$array_exif_elements=count($array_tag1);
     			   		if($array_exif_elements>0){
     			   			//return the equal elements in both arrays
     			   			$equal_elment=array_intersect($array_tag1, $array_tag2);
     			   			if(count($equal_elment)/$array_exif_elements>0.95) {
     			   				$s->addSubFileName($helpNameCheach);
     			   				unset($array[$helpNameCheach]);	
     			   			}
     			   		}
     			   	}
     			   }
   				next($array);
			}
			//if there is no equal photo we dont net photo
			$s->addFileName($name);	
			return $s;
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
		public  static function createDBtabels($classname){
			self::removeDBtabels();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.self::$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE ON UPDATE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_exif_photo_module`  ADD FOREIGN KEY (exif_id) REFERENCES *PREFIX*facefinder_exif_module(id)ON DELETE CASCADE ON UPDATE CASCADE');
			$stmt->execute();
		}
		
		/**
		 * Drop all Tables that are in contact with the Module
		 */
		public static function  removeDBtabels(){
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_photo_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_exif_module');
			$stmt->execute();
		}
		
		
		/**
		 * Create the DB of the Module the if the module hase an new Version numper
		 * @return boolean
		 */
		public static  function  initialiseDB(){
			//check if module is already installed
			if(OC_Appconfig::hasKey('facefinder',self::$classname)){
				//check if the module is in the correct version and all Tables exist
				if (self::checkVersion() || !self::AllTableExist()){
					//create all tables and update version number
						self::createDBtabels(self::$classname);
						OC_Appconfig::setValue('facefinder',self::$classname,self::getVersion());
						return true;
				}else{
					return false;
				}
			}else{
				//create all tables and update version number
				self::createDBtabels(self::$classname);
				OC_Appconfig::setValue('facefinder',self::$classname,self::getVersion());
				return true;
			}
		}
		
		/**
		 * check if all tables for module exist
		 * @return boolean 
		 */
		public static function AllTableExist(){
			//check if facefinder_exif_module exist
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_module\'');
			$result=$stmt->execute();
			$table_exif=($result->numRows()==1);
			//check if facefinder_exif_photo_module exist
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_exif_photo_module\'');
			$result=$stmt->execute();
			$table_exif_photo=($result->numRows()==1);
			
			return ($table_exif && $table_exif_photo);
		}
		
		public static function checkVersion(){
			$appkey=OC_Appconfig::getValue('facefinder',self::$classname);
			return version_compare($appkey, self::getVersion(), '<');
		}

		
		public static function getArrayOfStyle(){
			return null;
		}
		
		
		public static function getArrayOfScript(){
			return array("exif");
		}
		
		
		public static function getVersion(){
			return self::$version;
		}
		
	}
