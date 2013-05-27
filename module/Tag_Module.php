<?php
use OCA\FaceFinder;
class Tag_Module implements OCA\FaceFinder\MapperInterface{
	
	private static $classname='Tag_Module';
	private  static $version='0.2.5';
		/**
		 * for the construction of the class you need the path
		 * @param unknown_type $paht
		 */
		public function __construct($path){
			$this->lang =new OC_l10n('facefinder');
			$this->paht=$path;
		}
		
		/**
		 * the function returns all Photos order by their Tags
		 */
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
		
		/**
		 * Return the id or null id the tag and the name are not in the DB
		 * @param String $key
		 * @param String $tag
		 * @return id  |NULL 
		 */
		public static  function  getTagId($key,$tag){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_module` WHERE `name` LIKE ? AND `tag` LIKE ?');
			$result=$stmt->execute(array($key,$tag));
			while (($row = $result->fetchRow())!= false) {
				return $row['id'];
			}
			return null;
		}
		
		public static  function  getIDTag($tag){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_module` WHERE `tag` LIKE ?');
			$result=$stmt->execute(array($tag));
			while (($row = $result->fetchRow())!= false) {
				return $row['id'];
			}
			return null;
		}
		/**
		 * Checks if the "" are in the DB 
		 * @param int $photo_id
		 * @param int $tag_id
		 * @return boolean
		 */
		public static function  issetTagPhotoId($photo_id,$tag_id){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id`  = ? and `tag_id` = ?');
			$result=$stmt->execute(array($photo_id,$tag_id));
			return ($result->numRows()==1);
		}
		

		
		

		/**
		 * Insert kay and tag in the DB and return the id of the new element 
		 * @param String $key
		 * @param String $tag
		 * @return id
		 */
		public static  function insertTag($key,$tag){
				$lang =new OC_l10n('facefinder');
				//convert IPTC Code to a string
				
				$key=Tag_ModuleClass::IPTCCodeToString($key);
				//language change 
				$key=($lang->t($key));
				$tag_id=self::getTagId($key,$tag);
				if($tag_id==null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_module` ( `name`, `tag`) VALUES ( ?, ?)');
					$result=$stmt->execute(array($key,$tag));
					$tag_id=self::getTagId($key,$tag);
				}
			return  $tag_id;
				
			
		}
	
		
	
		
		public static  function removeTagPhoto($id,$class){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id` LIKE ? AND `tag_id` LIKE ?;');
			$result=$stmt->execute(array($class->getForingkey(),$id));		
		}
		
		/**
		 * Insert the data in the module DB
		*/
		public static function insert($class){
			foreach ($class->getTagArray() as $key => $section) {
				foreach ($section as $name => $val){
					if(strlen ($val)>2){
						OCP\Util::writeLog("facefinder",$key."-".$val."<-" ,OCP\Util::ERROR);
						$id=self::insertTag($key,$val);
						self::insertTagPhoto($id,$class);
					}
				}
			}
		}
		
		/**
		 * insert a TagPhoto realtiene in the DB
		 * @param id $id
		 * @param int $x1
		 * @param int $x2
		 * @param int $y1
		 * @param int $y2
		 */
		public static  function insertTagPhoto($id,$class,$x1=0,$x2=0,$y1=0,$y2=0){
			if(!self::issetTagPhotoId($class->getForingkey(),$id)){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_photo_module` (`photo_id`, `tag_id`,x1,x2,y1,y2) VALUES ( ?, ?,?,?,?,?);');
				$result=$stmt->execute(array($class->getForingkey(),$id,$x1,$x2,$y1,$y2));
			}
		}
		
		public  function getTag(){
			$stmt = \OCP\DB::prepare('SELECT * FROM *PREFIX*facefinder_tag_module  inner  join *PREFIX*facefinder_tag_photo_module on(*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) inner join *PREFIX*facefinder on(*PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id) where uid_owner LIKE ? And *PREFIX*facefinder.path=?');
			$result = $stmt->execute(array(\OCP\USER::getUser(),$this->paht));
			$tagarray=array();
			while (($row = $result->fetchRow())!= false) {
				$tagarray[]=array('name'=>$row['name'],"tag"=>$row['tag'],"x1"=>$row['x1'],"x2"=>$row['x2'],"y1"=>$row['y1'],"y2"=>$row['y2']);
			}
			return $tagarray;
		}
		
		
		public static function getClass($foringkey){
			$stmt = \OCP\DB::prepare('SELECT * FROM *PREFIX*facefinder_tag_module as tag inner  join *PREFIX*facefinder_tag_photo_module as tagphoto on(tag.id=tagphoto.tag_id)   where   tagphoto.photo_id=?');
			$result = $stmt->execute(array($foringkey));
			$tagarray=array();
			while (($row = $result->fetchRow())!= false) {
				$tagarray[]=array('name'=>$row['name'],"tag"=>$row['tag'],"x1"=>$row['x1'],"x2"=>$row['x2'],"y1"=>$row['y1'],"y2"=>$row['y2']);
				//OCP\Util::writeLog("facefinder",'name'.$row['name']." tag".$row['tag'],OCP\Util::ERROR);
			}
			//OCP\Util::writeLog("facefinder",json_encode($tagarray),OCP\Util::ERROR);
			$class=Tag_ModuleClass::getInstanceBySQL(1,$tagarray,$foringkey);
				
			return $class;
		}
		
			
		/**
		 * Remove the data in the module DB
		*/
		public static function remove(){}
		/**
		 * Update the data in the module DB
		*/
		
		public static function update($class){
			foreach ($class->getTagArray() as $section) {
						OCP\Util::writeLog("facefinder",$section['name']." ".$section['tag']."<-",OCP\Util::ERROR);
						if(($id=self::getTagId($section['name'],$section['tag']))===null){
							$id=self::insertTag($section['name'],$section['tag']);
						}
							self::insertTagPhoto($id,$class,$section['x1'],$section['x2'],$section['y1'],$section['y2']);
				}
			}
		

		
		
		/**
		 * The function receive a String and returns OC Search_Result
		 * @param String $query
		 * @return multitype:OC_Search_Result
		 */
		public static function search($query){
			$classname="Tag_Module";
			$results=array();
			$stmt = OCP\DB::prepare('select tag,name From   *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) where tag like ? or name like ? GROUP BY name');
			$result=$stmt->execute(array($query."%",$query."%"));
			while (($row = $result->fetchRow())!= false) {
				$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.$classname.'&name='.urlencode($row['name']).'&tag='.$row['tag'];
				$results[]=new OC_Search_Result("Tag",$row['tag'],$link,"FaF.");
			}			
			return $results;
		}
		

		/**
		 * The function return the path for the search request
		 * @param String $name
		 * @param String $tag
		 * @return multitype:photo_phate
		 */
		public static function searchArry($name,$tag){
			$results=array();
			$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) inner join *PREFIX*facefinder on (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id) where`tag` like ? and `name` like ? and uid_owner like ?');
			$result=$stmt->execute(array($tag,$name,\OCP\USER::getUser()));
			while (($row = $result->fetchRow())!= false) {
				$results[]=array($row['path'],$row['photo_id']);
			}
			return $results;
		}
		
		
		/**
		 * The function returns the id of the last stored information
		 * @return int Id
		*/
		public function getID(){}

		
		/**
		 * The funktion compares all taggs if 95% are equal add to the OC Equal object
		 * @return OC_Equal
		 */
		public function equivalent(){
			//hard coded value for each module and and the value of the eqaletti between 1 and 100
			$value=1;
			$s=new OCA\FaceFinder\OC_Equal(0.5);
			//get all information of a Photo from the DB
			$stmt = OCP\DB::prepare('select path,name,tag    from *PREFIX*facefinder as base  inner join *PREFIX*facefinder_tag_photo_module as tagphoto on (base.photo_id=tagphoto.photo_id) inner join *PREFIX*facefinder_tag_module as tag on (tagphoto.tag_id=tag.id)where uid_owner like ?   order by path,name');
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
					$help=$help+array($row['name']=>$row['tag']);
					$path=$row['path'];
				}else{
					$help=$help+array($row['name']=>$row['tag']);
				}
			}
			$array+=array($path=>$help);
			//array whit the equivalent elements;
			$array_eq=array();
			$name=null;
			while ($array_tag1 = current($array)) {
     			   if($name!=null){
						$array_eq+=array($name=>array("value"=>$value,"equival"=>$eq));
						$s->addFileName($name);
     			   }
     			   $eq=array();
     			   $name=key($array);
     			  // echo $name;
     			   $arrays=$array;
     			   foreach($arrays as $helpNameCheach=>$array_tag2){
     			   	//not check if it has the same name
     			   	
     			   //echo key($array);
     			   	if($name!=$helpNameCheach){
     			   		//echo $helpNameCheach;
     			   		$array_exif_elements=count($array_tag1);
     			   		if($array_exif_elements>0){
     			   			//return the equal elements in both arrays
     			   			$equal_elment=array_intersect($array_tag1, $array_tag2);
     			   			if(count($equal_elment)/$array_exif_elements==1) {
     			   				$eq[]=$helpNameCheach;
     			   				$s->addSubFileName($helpNameCheach);
     			   				unset($array[$helpNameCheach]);
     			   				
     			   					
     			   			}
     			   		}
     			   	}
     			   }
     			   
   				next($array);
			}
		if(count($eq)>0){
				$array_eq+=array($name=>array("value"=>$value,"equival"=>$eq));
			}
			$s->addFileName($name);
			
			return $s;
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
		
		public static function checkVersion(){
			$classname="Tag_Module";
			$appkey=OC_Appconfig::getValue('facefinder',$classname);
			return version_compare($appkey, self::getVersion(), '<');
		}
	
	
		public function getTagArray($path){
			/**
			 * SELECT * FROM `*PREFIX*facefinder_tag_module` inner join *PREFIX*facefinder_tag_photo_module on *PREFIX*facefinder_tag_module.id =*PREFIX*facefinder_tag_photo_module.tag_id inner join *PREFIX*facefinder on *PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id
			 * 
			 * 
			 */
		}
		
		/**
		 * check if all tables for module exist
		 * @return boolean
		*/
		public static function AllTableExist(){
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_tag_module\'');
			$result=$stmt->execute();
			$table_exif=($result->numRows()==1);
			
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_tag_photo_module\'');
			$result=$stmt->execute();
			$table_kamera=($result->numRows()==1);
			
			return ($table_exif && $table_kamera);
		}
	
		public  static function createDBtabels($classname){
			self::removeDBtabels();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (tag_id) REFERENCES *PREFIX*facefinder_tag_module(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
		}
		
		
		/**
		 * Drop all Tables that are in contact with the Module
		 */
		public static function  removeDBtabels(){
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS `*PREFIX*facefinder_tag_photo_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_tag_module`');
			$stmt->execute();
		}
		
		
		public static function getArrayOfStyle(){
			return array("tag");
		}
			
			
		public static function getArrayOfScript(){
			return array("tag");
		}
		
		public static function getVersion(){
			return self::$version;
		}
		
}
