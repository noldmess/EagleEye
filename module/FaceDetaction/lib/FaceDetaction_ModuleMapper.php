<?php


use OCA\FaceFinder;
class FaceDetaction_ModuleMapper implements OCA\FaceFinder\MapperInterface{

	private static $classname='FaceDetaction_Module';
	private  static $version='0.2.0';
	static private $count;
	/**
	 * for the construction of the class you need the path
	 * @param unknown_type $paht
	 */
	public function __construct($path){
		$this->lang =new OC_l10n('facefinder');
		$this->paht=$path;
		self::$count=0;
	}
	/**
	 * Return the id or null id the tag and the name are not in the DB
	 * @param String $key
	 * @param String $tag
	 * @return id  |NULL
	 */
	public static  function  getTagByFaceClass($faceclass){
		$stmt = OCP\DB::prepare('select  tag_id,tag from *PREFIX*facefinder_facedetaction_face_photo_module as face left outer join *PREFIX*facefinder_tag_module as tag on (face.tag_id=tag.id)  where   faceclass=?;');
		$result=$stmt->execute(array($faceclass));
		while (($row = $result->fetchRow())!= false) {
			return array("tag_id"=>$row['tag_id'],"tag"=>$row['tag']);
		}
		return null;
	}
	
	public static  function  getFaceClass(){
		$stmt = OCP\DB::prepare('select  * From  *PREFIX*facefinder_facedetaction_face_photo_module');
		$result=$stmt->execute(array($faceclass));
		$facelist=array();
		while (($row = $result->fetchRow())!= false) {
			if($row['faceclass']==!null){
				$facelist[]=array("facePhotoPath"=>$row['facePhotoPath'],"faceclass"=>$row['faceclass']);
			}
		}
		return $facelist;
	}
	
	
	
	
	
	/**
	 * Return the id or null id the tag and the name are not in the DB
	 * @param String $key
	 * @param String $tag
	 * @return id  |NULL
	 */
	public static  function  getNewFaceClass(){
		$stmt = OCP\DB::prepare('SELECT max(faceclass) as max  FROM `*PREFIX*facefinder_facedetaction_face_photo_module');
		$result=$stmt->execute(array());
		$max=0;
		while (($row = $result->fetchRow())!= false) {
			$max=$row['max'];
		}
		$max++;
		return ($max<42)?42:$max;
	}
	
	public static  function  getFaceClassByTag($id){
		$stmt = OCP\DB::prepare('SELECT faceclass FROM *PREFIX*facefinder_facedetaction_face_photo_module WHERE tag_id=?');
		$result=$stmt->execute(array($id));
		$max=null;
		while (($row = $result->fetchRow())!= false) {
			echo $max=$row['faceclass'];
		}
		return $max;
	}




	/**
	 * Insert the data in the module DB
		*/
	public static  function insert($class){
		self::insertCache($class);
		OCA\FaceFinder\BackgroundJob::addBackgroundJob(array("class"=>"FaceDetaction_ModuleMapper","id"=>$class->getForingkey(),"paht"=>$class->getPaht()));
	}
	
	
	private static function  removeBackgroundJob(){
		//get all BackgroundJob
		$listBackground=OCP\BackgroundJob::allQueuedTasks();
		foreach($listBackground as $BackgroundJop){
			$parameterarray=json_decode($BackgroundJop['parameters'],TRUE);
			//remove all BackgroundJob 
			if($parameterarray['class']==="FaceDetaction_Module"){
				OCP\BackgroundJob::deleteQueuedTask($BackgroundJop['id']);
			}
		}
	}
	
public static function doBackgroundJob($array){
		/*$cachetImages=self::getCacheImages();
		//remove unnecessary BackgroundJob
		if(sizeof($cachetImages)===0){
			self::removeBackgroundJob();
		}
		
		foreach($cachetImages as $image){
			//neue Funkion mit id übertgane
			self::getInfo($image['photo_id']);
			/*$id_photo=$image['photo_id'];
			$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id_photo);
			if($photo==!null){
				$imag=OC_Filesystem::getLocalFile($photo->getPath());
				OCP\Util::writeLog("facefinder","got from cache ".$photo->getPath(),OCP\Util::DEBUG);
				$newClass=FaceDetaction_ModuleClass::getInstanceByPath($photo->getPath(),$id_photo);
				$newClass->getFaces($image);
				$newClass->makeFaceImage();
				$path_parts =pathinfo($photo->getPath());
				$imgToSava=$path_parts['filename'];
				$facecount=0;
				foreach ($newClass->makeFaceTagArray() as  $section) {
					$face=$newClass->classFaceRec($facecount);
					//if the face class is biger then 41 then the image in new
					OCP\Util::writeLog("facefinderddddddddddd",$face['threshold']." x1:".$section['x1']." x2:".$section['x2']." y1:".$section['y1']." y2:".$section['y2'],OCP\Util::DEBUG);
					if($face['class']>41 && $face['threshold']<80){
						$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id_photo);
						$tag=self::getTagByFaceClass($face['class']);
						if($tag['tag_id']!==null){
							OCP\Util::writeLog("facefinder",json_encode($tag),OCP\Util::DEBUG);
							$class=Tag_Module::getClass($photo->getID());
							$class->addTag("2#025",$tag['tag'],$section['x1'],($section['x2']-$section['x1']),$section['y1'],($section['y2']-$section['y1']));
							Tag_Module::update($class);
							OCP\Util::writeLog("facefinder","found",OCP\Util::DEBUG);
							self::insertFacePhoto($tag['tag_id'],$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],($section['x2']-$section['x1']),$section['y1'],($section['y2']-$section['y1']));
						}else{
							OCP\Util::writeLog("facefinder","not found",OCP\Util::DEBUG);
							self::insertFacePhoto(null,$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],$section['x2'],$section['y1'],$section['y2']);
						}
						
					}else{
						OCP\Util::writeLog("facefinder","not found",OCP\Util::DEBUG);
						self::insertFacePhoto(null,$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],$section['x2'],$section['y1'],$section['y2']);
					}
					$facecount++;
				}
				
				self::removeCache($id_photo);
				OCP\Util::writeLog("facefinder","removed from cache ".$id_photo." ".$photo->getPath(),OCP\Util::DEBUG);
			}
		}*/
	}
	
	
	public  static function  getInfo($id_photo){
		$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id_photo);
		if($photo==!null){
			$imag=OC_Filesystem::getLocalFile($photo->getPath());
			OCP\Util::writeLog("facefinder","got test from cache ".$photo->getPath(),OCP\Util::DEBUG);
			$newClass=FaceDetaction_ModuleClass::getInstanceByPath($photo->getPath(),$id_photo);
			$newClass->getFaces($image);
			$newClass->makeFaceImage();
			$path_parts =pathinfo($photo->getPath());
			$imgToSava=$path_parts['filename'];
			$facecount=0;
			foreach ($newClass->makeFaceTagArray() as  $section) {
				$face=$newClass->classFaceRec($facecount);
				//if the face class is biger then 41 then the image in new
				OCP\Util::writeLog("facefinderddddddddddd",$face['threshold']." x1:".$section['x1']." x2:".$section['x2']." y1:".$section['y1']." y2:".$section['y2'],OCP\Util::DEBUG);
				if($face['class']>41 && $face['threshold']<80){
					$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id_photo);
					$tag=self::getTagByFaceClass($face['class']);
					if($tag['tag_id']!==null){
						OCP\Util::writeLog("facefinder",json_encode($tag),OCP\Util::DEBUG);
						$class=Tag_ModuleMapper::getClass($photo->getID());
						$class->addTag("2#025",$tag['tag'],$section['x1'],($section['x2']-$section['x1']),$section['y1'],($section['y2']-$section['y1']));
						Tag_Module::update($class);
						OCP\Util::writeLog("facefinder","found",OCP\Util::DEBUG);
						self::insertFacePhoto($tag['tag_id'],$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],($section['x2']-$section['x1']),$section['y1'],($section['y2']-$section['y1']));
					}else{
						OCP\Util::writeLog("facefinder","not found",OCP\Util::DEBUG);
						self::insertFacePhoto(null,$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],$section['x2'],$section['y1'],$section['y2']);
					}
		
				}else{
					OCP\Util::writeLog("facefinder","not found",OCP\Util::DEBUG);
					self::insertFacePhoto(null,$newClass,$imgToSava."-".$facecount.".png",null,$section['x1'],$section['x2'],$section['y1'],$section['y2']);
				}
				$facecount++;
			}
		
			self::removeCache($id_photo);
			OCP\Util::writeLog("facefinder","removed from cache ".$id_photo." ".$photo->getPath(),OCP\Util::DEBUG);
		}
	}
	
	
	/**
	 * 
	 * @return multitype:multitype:unknown
	 */
	public static function getCacheImages(){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder_facedetaction_module_cache` LIMIT 0,1');
		$result = $stmt->execute();
		$tagarray=array();
		while (($row = $result->fetchRow())!= false) {
			$tagarray[]=array('photo_id'=>$row['photo_id'],'id'=>$row['id']);	
		}
		return $tagarray;
	}
	
	public static  function insertCache($class){
		$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_facedetaction_module_cache` (`photo_id`) VALUES (?);');
		$result=$stmt->execute(array($class->getForingkey()));
	}
	
	public static  function removeCache($id){
		$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder_facedetaction_module_cache` WHERE photo_id=?;');
		$result=$stmt->execute(array($id));
	}
	
	public static function issetInCacheImages($id){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder_facedetaction_module_cache` WHERE photo_id=?;');
		$result = $stmt->execute(array($id));
		$tagarray=array();
		$count=0;
		while (($row = $result->fetchRow())!= false) {
			$tagarray[]=array('photo_id'=>$row['photo_id'],'id'=>$row['id']);
			$count++;
		}
		return ($count>0);
	}


	/**
	 * insert a TagPhoto realtiene in the DB
	 * @param id $id
	 * @param int $x1
	 * @param int $x2
	 * @param int $y1
	 * @param int $y2
	 */
	public static  function insertFacePhoto($id,$class,$facePhotoPath,$faceclass,$x1=0,$x2=0,$y1=0,$y2=0){
		//if(!self::issetTagPhotoId($class->getForingkey(),$id)){
			$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_facedetaction_face_photo_module` (`photo_id`, `tag_id`,`facePhotoPath`,`faceclass`,x1,x2,y1,y2) VALUES(?,?,?,?,?,?,?,?);');
			$result=$stmt->execute(array($class->getForingkey(),$id,$facePhotoPath,$faceclass,$x1,$x2,$y1,$y2));
			
		//}
	}

	
	
	

	/*public  function getTag(){
		$stmt = \OCP\DB::prepare('SELECT * FROM *PREFIX*facefinder_facedetaction_face_photo_module left outer  inner  join PREFIX*facefinder_tag_module on(*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) inner join *PREFIX*facefinder on(*PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id) where uid_owner LIKE ? And *PREFIX*facefinder.path=?');
		$result = $stmt->execute(array(\OCP\USER::getUser(),$this->paht));
		$tagarray=array();
		while (($row = $result->fetchRow())!= false) {
			$tagarray[]=array('name'=>$row['name'],"tag"=>$row['tag'],"x1"=>$row['x1'],"x2"=>$row['x2'],"y1"=>$row['y1'],"y2"=>$row['y2']);
		}
		return $tagarray;
	}*/


	public static function getClass($foringkey){
		$stmt = \OCP\DB::prepare('select  *,face.id as face_id from *PREFIX*facefinder_facedetaction_face_photo_module as face left outer join *PREFIX*facefinder_tag_module as tag on (face.tag_id=tag.id)  where   face.photo_id=?');
		$result = $stmt->execute(array($foringkey));
		$tagarray=array();
		while (($row = $result->fetchRow())!= false) {
			$tagarray[]=array('face_id'=>$row['face_id'],'name'=>$row['name'],"tag"=>$row['tag'],"tag_id"=>$row['tag_id'],"x1"=>$row['x1'],"x2"=>$row['x2'],"y1"=>$row['y1'],"y2"=>$row['y2'],"facePath"=>$row['facePhotoPath'],"faceclass"=>$row['faceclass']);
			OCP\Util::writeLog("facefinder",'name'.$row['name']." tag".$row['tag'],OCP\Util::ERROR);
		}
		//OCP\Util::writeLog("facefinder",json_encode($tagarray),OCP\Util::ERROR);
		$class=FaceDetaction_ModuleClass::getInstanceBySQL(1,$tagarray,$foringkey);

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
		foreach($class->tagArray as $face){
				self::updateFacePhoto($face['face_id'],$face['faceclass'],$face['tag_id']);
		}
	}

	public static  function removeFace($id){
		$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder_facedetaction_face_photo_module` WHERE id=?;');
		$result=$stmt->execute(array($id));
	}
	
	public static  function unsetFace($id){
	
		$stmt = OCP\DB::prepare('UPDATE `*PREFIX*facefinder_facedetaction_face_photo_module` SET faceclass=null ,tag_id=null WHERE id=?');
		$result=$stmt->execute(array($id));
	}

	public static  function updateFacePhoto($face_id,$faceClass,$tag_id){
		$stmt = OCP\DB::prepare('UPDATE `*PREFIX*facefinder_facedetaction_face_photo_module` SET faceclass=? ,tag_id=? WHERE id=?');
		$result=$stmt->execute(array($faceClass,$tag_id,$face_id));
	}
	

	/**
	 * The function receive a String and returns OC Search_Result
	 * @param String $query
	 * @return multitype:OC_Search_Result
	 */
	public static function search($query){
		$classname="Face_Module";
		$results=array();
		/**
		 * TODO
		 */
		/*$stmt = OCP\DB::prepare('select DISTINCT`tag`,name From   *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) where tag like ? or name like ?');
		$result=$stmt->execute(array($query."%",$query."%"));
		while (($row = $result->fetchRow())!= false) {
			$link = OCP\Util::linkTo('facefinder', 'index.php').'?search='.$classname.'&name='.urlencode($row['name']).'&tag='.$row['tag'];
			$results[]=new OC_Search_Result("Face",$row['tag'],$link,"FaF.");
		}*/
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
			$results[]=$row['path'];
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
	public function equivalent($dir){
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
		$user=\OCP\USER::getUser();
		$facefinderDir = \OC_User::getHome($user) . '/facefinder/';
		if (!is_dir($facefinderDir)) {
			mkdir($facefinderDir , 0755, true);
			OCP\Util::writeLog("asdasdasdas",$facefinderDir,OCP\Util::ERROR);
			$handle = fopen($facefinderDir."/eigenfaces_at.yml", "x");
		}
		//check if module is already installed
		if(OC_Appconfig::hasKey('facefinder',self::$classname)){
			//check if the module is in the correct version and all Tables exist
			if (self::checkVersion() || !self::AllTableExist()){
				//create all tables and update version number
				self::createDBtabels(self::$classname);
				OC_Appconfig::setValue('facefinder',self::$classname,self::getVersion());
				FaceDetaction_ModuleClass::classLearnFaceRec();
				return true;
			}else{
				return false;
			}
		}else{
			//create all tables and update version number
			self::createDBtabels(self::$classname);
			OC_Appconfig::setValue('facefinder',self::$classname,self::getVersion());
			FaceDetaction_ModuleClass::classLearnFaceRec();
			return true;
		}
	}

	public static function checkVersion(){
		$appkey=OC_Appconfig::getValue('facefinder',self::$classname);
		return version_compare($appkey, self::getVersion(), '<');
	}

	public static function getVersion(){
		return self::$version;
	}

	public function getTagArray($path){
		/**
		 * SELECT * FROM `oc_facefinder_tag_module` inner join oc_facefinder_tag_photo_module on oc_facefinder_tag_module.id =oc_facefinder_tag_photo_module.tag_id inner join oc_facefinder on oc_facefinder.photo_id=oc_facefinder_tag_photo_module.photo_id
		 *
		 *
		 */
	}

	/**
	 * check if all tables for module exist
	 * @return boolean
		*/
	public static function AllTableExist(){
		$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_facedetaction_module_cache\'');
		$result=$stmt->execute();
		$table_cache=($result->numRows()==1);
		$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_facedetaction_face_photo_module\'');
		$result=$stmt->execute();
		$table_face=($result->numRows()==1);
			
		return ( $table_cache&& $table_face);
	}

	public  static function createDBtabels($classname){
		self::removeDBtabels();
		OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/FaceDetaction/config/'.$classname.'.xml');
		$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_facedetaction_module_cache`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
		$stmt->execute();
		$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_facedetaction_face_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE SET NULL ON UPDATE CASCADE');
		$stmt->execute();
	}


	/**
	 * Drop all Tables that are in contact with the Module
	 */
	public static function  removeDBtabels(){
		$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS `*PREFIX*facefinder_facedetaction_module_cache`');
		$stmt->execute();
		$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS `*PREFIX*facefinder_facedetaction_face_photo_module`');
		$stmt->execute();
	}


	public static function getArrayOfStyle(){
		return array("face");
	}
		
		
	public static function getArrayOfScript(){
		return array("face");
	}


	
	

}
