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
	private  static $version='0.1.0';

	/**
	 * 
	 * @param Insert the $paht in the facefinder tabel in the db
	 */
	public  function __construct() {
	}
	
	
	/**
	 * the function returns all images order by their tags
	 * @param Path $dir
	 * @return unknown
	 */
	public static function getJSON($dir){
		$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder where  uid_owner like ? and path like ? order by path,hash');
		$result=$stmt->execute(array(\OCP\USER::getUser(),$dir));
		$array=array();
		$count=0;
		//create  a array where the ''path' is the key and ther hasch is the value 
		while (($row = $result->fetchRow())!= false) {
			return $row;
			$array+=array(($count++)=>$d);
		}
	}
	
	
	/**
	 * Insert the Photo in the SQL Database
	 */
	public static  function insert($photo){
			$existolrady=false;
			if(self::getPhotoClassPath($photo->getPath())===null){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder` ( `uid_owner`, `path`,`hash`,`date_photo`,`height`,`width`,`filesize`)   VALUES (?, ?,?,?,?,?,?)');
				$stmt->execute(array(\OCP\USER::getUser(),$photo->getPath(),$photo->getHash(),$photo->getDate(),$photo->getHeight(),$photo->getWidth(),$photo->getFilesize()));
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
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` = ? AND `path` = ?');
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
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` = ? AND `path` = ?');
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

	public static function  chronologically($dir){
		$stmt = \OCP\DB::prepare('SELECT DISTINCT  YEAR(date_photo) as year FROM `*PREFIX*facefinder` Where uid_owner LIKE ? and  path like ? order by date_photo  DESC');
		$resultyear = $stmt->execute(array(\OCP\USER::getUser(),$dir."%"));
		$yeararray=array();
		$countyear=0;
		while (($rowyear = $resultyear->fetchRow())!= false) {
				$stmta = \OCP\DB::prepare('SELECT DISTINCT  MONTH(date_photo) as month ,MONTHNAME(date_photo) as monthname FROM `*PREFIX*facefinder` Where uid_owner LIKE ? AND YEAR(date_photo) = ? And  path like ?  order by   date_photo DESC');
				$resultmonth = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year'],$dir."%"));
				$montharray=array();
				$countmonth=0;
				while (($rowmonth = $resultmonth->fetchRow())!= false) {
						$days=array();
						$day=0;
						$images=array();
						$rows=false;
						$stmta = \OCP\DB::prepare('SELECT  Day(date_photo) as day,path,photo_id,width,height FROM`*PREFIX*facefinder`Where uid_owner LIKE ? AND YEAR(date_photo) = ? AND MONTH(date_photo) = ?  And  path like ? order by date_photo ASC');
						$resultday = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year'],$rowmonth['month'],$dir."%"));
						$countday=0;
						while (($rowday = $resultday->fetchRow())!= false) {
							
								if($day==$rowday['day']){
									$images[]=array("id"=>$rowday['photo_id'],"path"=>$rowday['path'],"Height"=>$rowday['height'],"width"=>$rowday['width']);
								}else{
									if($day!=0){
										$days[]=array('day'=>$day,"number"=>$countday,"imags"=>$images);
										$countmonth+=$countday;
										$countday=0;
									}
									$images=array();
									$day=$rowday['day'];
									$images[]=array("id"=>$rowday['photo_id'],"path"=>$rowday['path'],"Height"=>$rowday['height'],"width"=>$rowday['width']);
								}
								$countday++;
						}
						$countmonth+=$countday;
						$days[]=array('day'=>$day,"number"=>$countday,"imags"=>$images);
						$montharray[]=array('monthnumber'=>$rowmonth['month'],'month'=>$rowmonth['monthname'],"number"=>$countmonth,"days"=>$days);
						$countyear+=$countmonth;
						$countmonth=0;
		}
		$yeararray[]=array('year'=>$rowyear['year'],"number"=>$countyear,"month"=>$montharray);
		$countyear=0;
		
	}
	return $yeararray;
	}
	
	/**
	 * 
	 * @return OC_Equal
	 */
	
	public function equivalent($dir){
		//hard coded value for each module and and the value of the eqaletti between 1 and 100
		//$value=100;
		$eq=new OC_Equal(100);
		$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder where  uid_owner like ? and path like ? order by path,hash');
		$result=$stmt->execute(array(\OCP\USER::getUser(),$dir.'%'));
		$array=array();
		$count=0;
		//create  a array where the ''path' is the key and ther hasch is the value 
		while (($row = $result->fetchRow())!= false) {
			$d=$row;
			$array+=array(($count++)=>$d);
		}
		$help1=sizeof($array);
		$help2=sizeof($array);
		$array_duplicatits=array();
		for ($i=0;$i<$help1;$i++){
			for ($j=($i+1);$j<$help2;$j++){
				$proz=0;
				if($array[$i]['hash']===$array[$j]['hash']){
					$proz=1;
				}else{
					if($array[$i]['filesize']===$array[$j]['filesize'])
						$proz+=0.3;
					if($array[$i]['date_photo']===$array[$j]['date_photo'])
						$proz+=0.5;
				}
				if($proz>=0.5)
						$array_duplicatits[]=array($array[$i],$array[$j],"prozent"=>$proz);
			}

		}
		//uasort($array_duplicatits, array($this, "test"));
		
		return $array_duplicatits;
	}
	
	public function test($a, $b)
	{
	 if ($a['prozent'] == $b['prozent']) {
        return 0;
    }
  	  return ($a['prozent'] > $b['prozent']) ? -1 : 1;
	}
	

	public static 	function test_print($item2, $key)
		{
		    echo "$key. $item2<br />\n";
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
