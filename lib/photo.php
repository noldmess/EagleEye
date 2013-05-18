<?php
namespace OCA\FaceFinder;
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
use OCP;
class FaceFinderPhoto{// implements OC_Module_Interface{
	private  $paht;
	private  static $version='0.0.1';

	/**
	 * 
	 * @param Insert the $paht in the facefinder tabel in the db
	 */
	public  function __construct() {
	}
	
	
	public static function getAllPhotosJSON($dir,$tag=null){
		$sqlvar=array();
		$sql='select DISTINCT f.photo_id as id, path from *PREFIX*facefinder as f left outer join *PREFIX*facefinder_tag_photo_module as ftpm on (ftpm.photo_id=f.photo_id) left outer  join *PREFIX*facefinder_tag_module as ftm on (ftpm.tag_id=ftm.id)';
		if(strlen($dir)==!0 || strlen($tag)==!0){
			$sql.=' where';
		}
		if(strlen($dir)==!0){
			$sql.=' f.path like ? ';
			$sqlvar[]=$dir."%";
		}
		if(strlen($tag)==!0){
			if($tag==='No Tag'){
				$sql.=' and  tag is null  ';
			}else{
				$sql.=' and  tag like ? ';
				$sqlvar[]=$tag;
			}
				
	
		}
		$sql.='order by ftm.tag ASC;';
		//echo json_encode($sql);
		$stmt = OCP\DB::prepare($sql);
		$result=$stmt->execute($sqlvar);
		;
		$arrayPhotos=array();
		while (($row = $result->fetchRow())!= false) {
			$arrayPhotos[]=array('id'=>$row['id'],'path'=>$row['path']);
		}
		return $arrayPhotos;
	}
	/**
	 * the function returns all images order by their tags
	 * @param Path $dir
	 * @return unknown
	 */
	public static function getJSON($dir){
		if(strlen($dir)===0){
			$stmt = OCP\DB::prepare("select f.photo_id as id ,path,tag from *PREFIX*facefinder as f left outer join *PREFIX*facefinder_tag_photo_module as ftpm on (ftpm.photo_id=f.photo_id) left outer  join *PREFIX*facefinder_tag_module as ftm on (ftpm.tag_id=ftm.id)  order by ftm.tag ASC;");
			$result=$stmt->execute();
		}else{
			$stmt = OCP\DB::prepare("select f.photo_id as id ,path,tag from *PREFIX*facefinder as f left outer join *PREFIX*facefinder_tag_photo_module as ftpm on (ftpm.photo_id=f.photo_id) left outer  join *PREFIX*facefinder_tag_module as ftm on (ftpm.tag_id=ftm.id) where f.path like ? order by ftm.tag ASC;");
			$result=$stmt->execute(array($dir.'%'));
		}
	
		$resuldt=array();
		$d=0;
		$tagtest='';
		$count=0;
		while (($row = $result->fetchRow())!= false) {
			$count++;
			$tag=$row['tag'];
			if($tagtest==='' && $tag===NULL){
				$tagtest='';
				$tag='';
			}
			if(strcmp($tag,$tagtest)==!0){
				if($d>0){
					if($tagtest==='')
						$tagtest='No Tag';
					$resuldt[$tagtest]=$d;
				}
				$d=0;
				//echo $tag."<br>";
					
				$tagtest=$tag;
				//$resuldt[]=$row['path'];
			}
			$d++;
		}
		$resuldt[$tagtest]=$d;
		return $resuldt;
	}
	
	
	public  function text() {
		OCP\Util::writeLog("facefinder","write text",OCP\Util::ERROR);
	}
	/**
	 * Insert the Photo in the SQL Database
	 */
	public static  function insert($photo){
			$existolrady=false;
			if(self::getPhotoClassPath($photo->getPath())===null){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder` ( `uid_owner`, `path`,`hash`,`date_photo`)   VALUES (?, ?,?,?)');
				$stmt->execute(array(\OCP\USER::getUser(),$photo->getPath(),$photo->getHash(),$photo->getDate()));
				$existolrady=true;
			}
		return $existolrady;
	}

	/**
	 * @return return the primary key sql table 
	 */
	public static function getPhotoClass($id){ 
			$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND photo_id = ?');
			$result = $stmt->execute(array(\OCP\USER::getUser(), $id));
			$class=null;
			if($result->numRows()==1){
				while (($row = $result->fetchRow())!= false) {
					$class= PhotoClass::getInstanceBySQL($row['photo_id'],$row['path'],$row['hash'],$row['date_photo']); 
				}
			}
				return $class;
			}
	
			
	public static function getPhotoClassDir($paht){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
		$result = $stmt->execute(array(\OCP\USER::getUser(), $paht."%"));
		OCP\Util::writeLog("facefinder",$result->numRows(),OCP\Util::ERROR);
		$class=array();
		OCP\Util::writeLog("facefindddddddddddddddder",$result->numRows(),OCP\Util::DEBUG);
			while (($row = $result->fetchRow())!= false) {
				$class[]= PhotoClass::getInstanceBySQL($row['photo_id'],$row['path'],$row['hash'],$row['date_photo']);
				OCP\Util::writeLog("facefinder",$row['path']." ".$row['hash'],OCP\Util::ERROR);
			}
		return $class;
	}
	
	public static function getPhotoClassPath($paht){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
		$result = $stmt->execute(array(\OCP\USER::getUser(), $paht));
		$class=null;
		while (($row = $result->fetchRow())!= false) {
			$class= PhotoClass::getInstanceBySQL($row['photo_id'],$row['path'],$row['hash'],$row['date_photo']);
		}
		return $class;
	}
	/**
	 * The function check if the ExiPhoto already exist
	 * @param int  $exif_id
	 * @return boolean
	 */
	public static  function  issetPhotoId($paht){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
		$result = $stmt->execute(array(\OCP\USER::getUser(), $paht));
		return ($result->numRows()==1);
	
	}

			/**
			 * Temove the Photo from the SQL Database
			 */
	public  static function remove($photo){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `photo_id` = ?');
			$stmt->execute(array(\OCP\USER::getUser(),$photo->getID()));
	}
	/**
	 * Update the Photo in the SQL Database
	 */
	public  static function  update($photo){
		OCP\Util::writeLog("facefinder","to update".$photo->getID()."fdsfsdf",OCP\Util::DEBUG);
			$stmt = OCP\DB::prepare('UPDATE `*PREFIX*facefinder` SET `path` = ? WHERE `uid_owner` LIKE ? AND `photo_id` = ? ');
			$stmt->execute(array($photo->getPath(),\OCP\USER::getUser(),$photo->getID()));
	}
	
	public static function  search($query){
		/**
		 * @todo
		 */
	}

	/**
	 * 
	 * @return OC_Equal
	 */
	
	public function equivalent(){
		//hard coded value for each module and and the value of the eqaletti between 1 and 100
		//$value=100;
		$eq=new OC_Equal(100);
		$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder where  uid_owner like ?order by path,hash');
		$result=$stmt->execute(array(\OCP\USER::getUser()));
		$array=array();
		//create  a array where the ''path' is the key and ther hasch is the value 
		while (($row = $result->fetchRow())!= false) {
			$array+=array($row['path']=>$row['hash']);
		}
		//go thru the array and search the keys with equal and hashes
		while ($hash = current($array)) {
			$name=key($array);
			$equal_images=array_keys($array, $hash);
			//create the Equal obiekt
			foreach($equal_images as $imges){
				if($name!=$imges){
					$eq->addSubFileName($imges);
					unset($array[$imges]);
				}
			}
			$eq->addFileName($name);
			next($array);
		}

		return $eq;
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
