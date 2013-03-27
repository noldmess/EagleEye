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
class Kamera_Module extends OC_Module_Interface{

	private  static $version='0.0.2';
	private static $classname="Kamera_Module";
	

	
	public  function __construct($path) {
		$this->paht=$path;
	}

	public static function getVersion(){
		return self::$version;
	}

	/**
	 * The function check if KameraPhoto is already inserted in the DB 
	 * @param int  $kamera_id
	 * @return boolean 
	 */
	public static function  issetKameraPhotoId($kamera_id,$class){
		$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_kamera_photo_module` WHERE `photo_id`  = ? and  `kamera_id` = ?');
		$result=$stmt->execute(array($class->getForingkey(),$kamera_id));
		return ($result->numRows()==1);
		
	}

	/** 
	 * The function insert  KameraPhoto in the DB
	 * @param id  $id
	 */
	public static function insertKameraPhoto($id,$class){
		if(!self::issetKameraPhotoId($id,$class)){
			if($id!=null){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_kamera_photo_module` (`photo_id`, `kamera_id`) VALUES ( ?, ?);');
				$result=$stmt->execute(array($class->getForingkey(),$id));
			}
		}


	}



	/**
	 * Insert the exif data in the EXIF module Table in the DB
	 */
	public  function insert($class){
			$kamera_id=self::insertKamera($class->getMark(),$class->getModel());
			self::insertKameraPhoto($kamera_id,$class);
		}
	
	/**
	 * @return return the primary key sql table
	 */
	public function getID(){
		/**
		 * @todo
		 */
	}
	
	public static function getClass($foringkey){
		$stmt = \OCP\DB::prepare('select * from oc_facefinder as base  inner join oc_facefinder_kamera_photo_module as kameraphoto on (base.photo_id=kameraphoto.photo_id) inner join oc_facefinder_kamera_module as kamera on (kameraphoto.kamera_id=kamera.id) where kameraphoto.photo_id=?');
		$result = $stmt->execute(array($foringkey));
		$tagarray=array();
		while (($row = $result->fetchRow())!= false) {
			$class=Kamera_ModuleClass::getInstanceBySQL(1,$row['model'],$row['make'],$foringkey);
		}
		return $class;
	}
	
	/**
	 * The funktion extract  the exif data of an image
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
	public  function getKameraData(){
		// 
		$stmt = OCP\DB::prepare('Select * From  *PREFIX*facefinder_kamera_module  inner join  *PREFIX*facefinder_kamera_photo_module on(*PREFIX*facefinder_kamera_module.id=*PREFIX*facefinder_kamera_photo_module.kamera_id) where *PREFIX*facefinder_kamera_photo_module.photo_id =?');
		$result=$stmt->execute(array($this->ForingKey));
		while (($row = $result->fetchRow())!= false) {
			return array('make'=>$row['make'],"model"=>$row['model']);
		}
		return null;
	}
	

	/**
	 *
	 * @param unknown_type $kamera
	 * @return $is|NULL
	 */
	public static function  getKameraId($make, $model){
		$stmt = OCP\DB::prepare('SELECT *FROM `*PREFIX*facefinder_kamera_module` WHERE  `make` LIKE ? AND `model` LIKE ?');
		$result=$stmt->execute(array($make, $model));
		while (($row = $result->fetchRow())!= false) {
			return$row['id'];
		}
		return null;
	}

	/**
	 * The Funktioen insert the kamera model and marke in the DB and Retund the ID
	 * @param String $make name
	 * @param String $model name
	 * @return Ambigous <$is, NULL, unknown>|NULL
	 */
	public static function insertKamera($make, $model){
			//checks if the camera  and the model is already inserted in the DB and returns the id
			$kamera_id=self::getKameraId($make, $model);
			if($kamera_id==null){
				if($make!=null && $model!=null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_kamera_module` ( `make`, `model`) VALUES (?,?)');
					$result=$stmt->execute(array($make, $model));
					$kamera_id=self::getKameraId($make, $model);
				}
			}
		return $kamera_id;
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
		$results=array();
		$stmt = OCP\DB::prepare('select * From   *PREFIX*facefinder_kamera_module where  model  like ? or make like ?');
		$result=$stmt->execute(array($query."%",$query."%"));
		while (($row = $result->fetchRow())!= false) {
				$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.urlencode(self::$classname).'&name='.urlencode($row['model']).'&tag='.urlencode($row['make']);
				$results[]=new OC_Search_Result("Kamera",$row['model']."-".$row['make'],$link,"FaF.");
		}
				return $results;
	}




	public static function searchArry($name,$value){
		$results=array();
		$stmt = OCP\DB::prepare(' Select * From  *PREFIX*facefinder_kamera_module  inner join  *PREFIX*facefinder_kamera_photo_module on(*PREFIX*facefinder_kamera_module.id=*PREFIX*facefinder_kamera_photo_module.kamera_id) inner join *PREFIX*facefinder on  (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_kamera_photo_module.photo_id)where `make` like ? and `model` like ?');
			$result=$stmt->execute(array($value,$name));
			while (($row = $result->fetchRow())!= false) {
			$results[]=$row['path'];
			OCP\Util::writeLog("facefinder",$row['path'],OCP\Util::DEBUG);
		}
		return $results;
	}





			/**
			 * The funktion compares all kamera if 100% are equal add to the OC Equal object
			 * @return OC_Equal
			 */
			public  function equivalent(){
			//get all information of a Photo from the DB
			$stmt = OCP\DB::prepare('select path,make,model  from *PREFIX*facefinder as base  inner join *PREFIX*facefinder_kamera_photo_module as kameraphoto on (base.photo_id=kameraphoto.photo_id) inner join *PREFIX*facefinder_kamera_module as kamera on (kameraphoto.kamera_id=kamera.id) where uid_owner like ?  order by path,make');
			$result=$stmt->execute(array(\OCP\USER::getUser()));
			$s=new OC_Equal(10);
			$array=array();
			$path=null;
			//build a new  array of all information for each Photo 
			while (($row = $result->fetchRow())!= false) {
						$array+=array($row['path']=>array($row['make']=>$row['model']));
			}
				//array whit the equivalent elements  
				$array_eq=array();
				$name=null;
				//check all element
				while ($array_tag1 = current($array)) {
     			   if($name!=null ){
						$s->addFileName($name);
     			   }
     			   $eq=array();
     				$name=key($array);
     			   //echo $name." ".$array_tag1[1] ."<br>";
     			   $arrays=$array;
     			   foreach($arrays as $helpNameCheach=>$array_tag2){
     			   	if($name!=$helpNameCheach){
     			   			if($array_tag1==$array_tag2) {
     			   				$s->addSubFileName($helpNameCheach);
     			   				unset($array[$helpNameCheach]);	
     			   			}
     			   	}
     			   }
     			   
   				next($array);
			}
			
				$s->addFileName($name);
			return $s;
}

			/**
		 * Help Funktioen to create  module DB Tables
		 * @param String of $classname
		 */
		 public  static function createDBtabels($classname){
		 	
			self::removeDBtabels();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_kamera_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_kamera_photo_module`  ADD FOREIGN KEY (kamera_id) REFERENCES *PREFIX*facefinder_kamera_module(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
			OCP\Util::writeLog("facefinder","Kamera_Module 4",OCP\Util::DEBUG);
			}

			/**
			 * Drop all Tables that are in contact with the Module
			 */
			public static function  removeDBtabels(){
				$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_kamera_photo_module');
				$stmt->execute();
				$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_kamera_module`');
				$stmt->execute();
			}

			
			public static function getArrayOfStyle(){
				return null;
			}
			
			
			public static function getArrayOfScript(){
				return array("kamera");
			}


		  /**
		   * check if all tables for module exist
		   * @return boolean
		   */
		   public static function AllTableExist(){
			   $stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_kamera_photo_module\'');
			   $result=$stmt->execute();
			   $table_kamera=($result->numRows()==1);
			   
			   $stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_kamera_module\'');
			   $result=$stmt->execute();
				$table_kamera_photo=($result->numRows()==1);
				return ($table_kamera_photo && $table_kamera);
			}

		
		

	}
