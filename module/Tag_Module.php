<?php
class Tag_Module implements OC_Module_Interface{
	
	private static $classname='Tag_Module';
	private  static $version='0.2.5';
	private $ForingKey=null;
	private $lang=null;
		/**
		 * for the construction of the class you need the path
		 * @param unknown_type $paht
		 */
		public function __construct($path){
			$this->lang =new OC_l10n('facefinder');
			$this->paht=$path;
		}
		/**
		 * Return the id or null id the tag and the name are not in the DB
		 * @param String $key
		 * @param String $tag
		 * @return id  |NULL 
		 */
		public function  getTagId($key,$tag){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_module` WHERE `name` LIKE ? AND `tag` LIKE ?');
			$result=$stmt->execute(array($key,$tag));
			while (($row = $result->fetchRow())!= false) {
				return$row['id'];
			}

			return null;
		}
		/**
		 * Checks if the "" are in the DB 
		 * @param int $photo_id
		 * @param int $tag_id
		 * @return boolean
		 */
		public function  issetTagPhotoId($photo_id,$tag_id){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id`  = ? and `tag_id` = ?');
			$result=$stmt->execute(array($photo_id,$tag_id));
			return ($result->numRows()==1);
		}
		
		/**
		 * insert a TagPhoto realtiene in the DB 
		 * @param id $id
		 * @param int $x1
		 * @param int $x2
		 * @param int $y1
		 * @param int $y2
		 */
		public function insertTagPhoto($id,$x1=0,$x2=0,$y1=0,$y2=0){
			if(!$this->issetTagPhotoId($this->ForingKey,$id)){
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_photo_module` (`photo_id`, `tag_id`,x1,x2,y1,y2) VALUES ( ?, ?,?,?,?,?);');
				$result=$stmt->execute(array($this->ForingKey,$id,$x1,$x2,$y1,$y2));
			}
		
		
		}
		
		/**
		 * To issier create a table insert tis funktion nead the foreign key for the table
		 * @param int $key
		 */
		public function  setForingKey($key){
			$this->ForingKey=$key;
		}
		
		/**
		 * Insert kay and tag in the DB and return the id of the new element 
		 * @param String $key
		 * @param String $tag
		 * @return id
		 */
		public function insertTag($key,$tag){
				//convert IPTC Code to a string  
				$key=$this->IPTCCodeToString($key);
				//language change 
				$key=($this->lang->t($key));
				$tag_id=$this->getTagId($key,$tag);
				if($tag_id==null){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_module` ( `name`, `tag`) VALUES ( ?, ?)');
					$result=$stmt->execute(array($key,$tag));
					$tag_id=$this->getTagId($key,$tag);
				}
			return  $tag_id;
			
		}
	
		
	
		
		public function removeTagPhoto($id){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id` LIKE ? AND `tag_id` LIKE ?;');
			$result=$stmt->execute(array($this->ForingKey,$id));		
		}
		
		/**
		 * Insert the data in the module DB
		*/
		public function insert(){
			//check if the file exist
			if (\OC_Filesystem::file_exists($this->paht)) {
				getimagesize(OC_Filesystem::getLocalFile($this->paht),$info);
				if(isset($info['APP13'])){
					$iptc = iptcparse($info['APP13']);
					foreach ($iptc as $key => $section) {
						foreach ($section as $name => $val){
							$id=$this->insertTag($key,$val);
							$this->insertTagPhoto($id);
						}
					}
				}
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
		
		
		/**@todo refachter 
		 * Write the tags from the DB in the IPTC header of the image 
		 */
		public function writeTag(){
			if (\OC_Filesystem::file_exists($this->paht)) {
				$help=$this->getTag();
				$iptc = array();
				$i=1;
				foreach ($help as $s){
					$iptc=$iptc+array("2#".$this->StringToIPTCCode($s['name']).$i=>$s['tag']);
					$i++;
				}
				$data = '';
			foreach($iptc as $tag => $string){
    				$tag = str_replace("2#", "", $tag);
    				$tag = substr($tag, 0, 3);
    				$data .= $this->iptc_make_tag(2, $tag, $string);
				}
				$content = iptcembed($data,OC_Filesystem::getLocalFile($this->paht));
				$fp=OC_Filesystem::fopen($this->paht,"wb");
				if (!$fp) {
					OCP\Util::writeLog("facefinder",OC_Filesystem:: getLocalFile("/")." sdfsdf",OCP\Util::ERROR);
				}else{
					fwrite($fp, $content);
					fclose($fp);
				}
			}
		}
		
			private function iptc_make_tag($rec, $data, $value)
			{
			    $length = strlen($value);
			    $retval = chr(0x1C) . chr($rec) . chr($data);
			    if($length < 0x8000){
			   			        $retval .= chr($length >> 8) .  chr($length & 0xFF);
			    }
			    else{
			        $retval .= chr(0x80).chr(0x04).chr(($length >> 24) & 0xFF).chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF).chr($length & 0xFF);
			    }
			    return $retval . $value;
			}
			
		/**
		 * Remove the data in the module DB
		*/
		public function remove(){}
		/**
		 * Update the data in the module DB
		*/
		
		public function update($newpaht){}

		
		
		/**
		 * The function receive a String and returns OC Search_Result
		 * @param String $query
		 * @return multitype:OC_Search_Result
		 */
		public static function search($query){
			$classname="Tag_Module";
			$results=array();
			$stmt = OCP\DB::prepare('select DISTINCT`tag`,name From   *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) where tag like ? or name like ?');
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
		public function equivalent(){
			//hard coded value for each module and and the value of the eqaletti between 1 and 100
			$value=1;
			$s=new OC_Equal(0.5);
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
			//echo json_encode($array);
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
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_tag_module\'');
			$result=$stmt->execute();
			$table_exif=($result->numRows()==1);
			
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_tag_photo_module\'');
			$result=$stmt->execute();
			$table_kamera=($result->numRows()==1);
			
			return ($table_exif && $table_kamera);
		}
	
		private static function createDBtabels($classname){
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
			return null;
		}
			
			
		public static function getArrayOfScript(){
			return array("tag");
		}
		
		
		public static  function IPTCCodeToString($ipct){
			$ipct_tmp = substr($ipct, 2);
			switch($ipct_tmp){
				case '005':return 'OBJECT_NAME';
				case '007':return 'EDIT_STATUS';
				case '010':return 'PRIORITY';
				case '015':return 'CATEGORY';
				case '020':return 'SUPPLEMENTAL_CATEGORY';
				case '022':return 'FIXTURE_IDENTIFIER';
				case '025':return 'KEYWORDS';
				case '030':return 'RELEASE_DATE';
				case '035':return 'RELEASE_TIME';
				case '040':return 'SPECIAL_INSTRUCTIONS';
				case '045':return 'REFERENCE_SERVICE';
				case '047':return 'REFERENCE_DATE';
				case '050':return 'REFERENCE_NUMBER';
				case '055':return 'CREATED_DATE';
				case '060':return 'RELEASE_TIME';
				case '062':return 'DigitalCreationDate';
				case '063':return 'DigitalCreationTime';
				case '065':return 'ORIGINATING_PROGRAM';
				case '070':return 'PROGRAM_VERSION';
				case '075':return 'OBJECT_CYCLE';
				case '080':return 'BYLINE';
				case '085':return 'BYLINE_TITLE';
				case '090':return 'CITY';
				case '095':return 'PROVINCE_STATE';
				case '100':return 'COUNTRY_CODE';
				case '101':return 'COUNTRY';
				case '103':return 'ORIGINAL_TRANSMISSION_REFERENCE';
				case '105':return 'HEADLINE';
				case '110':return 'CREDIT';
				case '115':return 'SOURCE';
				case '116':return 'COPYRIGHT_STRING';
				case '120':return 'CAPTION';
				case '121':return 'LOCAL_CAPTION';
				default:return $ipct;
			}
		}
		/**
		 * @ todo
		 * @param unknown_type $ipct
		 * @return string|unknown
		 */
		public static  function StringToIPTCCode($ipct){
			switch($ipct){
				case 'OBJECT_NAME':return '005';
				case 'EDIT_STATUS':return '007';
				case 'PRIORITY':return '010';
				case 'CATEGORY':return '015';
				case 'SUPPLEMENTAL_CATEGORY':return '020';
				case 'FIXTURE_IDENTIFIER':return 'FIXTURE_IDENTIFIER';
				case 'KEYWORDS':return '025';
				case 'RELEASE_DATE030':return '030';
				case 'RELEASE_TIME':return '035';
				case 'SPECIAL_INSTRUCTIONS':return '040';
				case 'REFERENCE_SERVICE':return '045';
				case 'REFERENCE_DATE':return '047';
				case 'REFERENCE_NUMBER':return '050';
				case 'CREATED_DATE':return '055';
				case 'RELEASE_TIME':return '060';
				case 'DigitalCreationDate':return '062';
				case 'DigitalCreationTime':return '063';
				case 'ORIGINATING_PROGRAM':return '065';
				case 'PROGRAM_VERSION':return '070';
				case 'OBJECT_CYCLE':return '075';
				case 'BYLINE':return '080';
				case 'BYLINE_TITLE':return '085';
				case 'CITY':return '090';
				case 'PROVINCE_STATE':return '095';
				case 'COUNTRY_CODE':return '100';
				case 'COUNTRY':return '101';
				case 'ORIGINAL_TRANSMISSION_REFERENCE':return '103';
				case 'HEADLINE':return '105';
				case 'CREDIT':return '110';
				case 'SOURCE':return '115';
				case 'COPYRIGHT_STRING':return '116';
				case 'CAPTION':return '120';
				case 'LOCAL_CAPTION':return '121';
				default:return $ipct;
			}
		}
		
}
