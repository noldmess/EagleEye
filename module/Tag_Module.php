<?php
class Tag_Module implements OC_Module_Interface{
	
	
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
		
		public function  getTagId($key,$tag){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_module` WHERE `name` LIKE ? AND `tag` LIKE ?');
			$result=$stmt->execute(array($key,$tag));
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
		
		public function  issetTagPhotoId($photo_id,$tag_id){
			$stmt = OCP\DB::prepare('SELECT *  FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id`  = ? = `tag_id` = ?');
			$result=$stmt->execute(array($photo_id,$tag_id));
			$rownum=0;
			$id;
			while (($row = $result->fetchRow())!= false) {
				$id=$row['photo_id'];
				$rownum++;
			}
			OCP\Util::writeLog("facefinder",$rownum,OCP\Util::DEBUG);
			return ($rownum==1);
			
		}
		/**
		 * To issier create a table insert tis funktion nead the foreign key for the table
		 * @param int $key
		 */
		public function  setForingKey($key){
			$this->ForingKey=$key;
		}
		
		public function insertTag($key,$tag){
				$key=$this->IPTCCodeToString($key);
				$key=($this->lang->t($key));
				$tag_id=$this->getTagId($key,$tag);
				if($tag_id!=null){
					return  $tag_id;
				}else{
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_module` ( `name`, `tag`) VALUES ( ?, ?)');
					$result=$stmt->execute(array($key,$tag));
					$tag_id=$this->getTagId($key,$tag);
					if($tag_id!=null)
							return  $tag_id;
				}
			
		}
		/**
		 * @param unknown_type $id
		 * @return Ambigous <NULL, unknown>
		 */
		public function insertTagPhoto($id,$x1=0,$x2=0,$y1=0,$y2=0){
				if(!$this->issetTagPhotoId($this->ForingKey,$id)){
					$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_photo_module` (`photo_id`, `tag_id`,x1,x2,y1,y2) VALUES ( ?, ?,?,?,?,?);');
					$result=$stmt->execute(array($this->ForingKey,$id,$x1,$x2,$y1,$y2));
				}
		
				
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
		
		public function removeTagPhoto($id){
			$stmt = OCP\DB::prepare('DELETE FROM `*PREFIX*facefinder_tag_photo_module` WHERE `photo_id` LIKE ? AND `tag_id` LIKE ?;');
			$result=$stmt->execute(array($this->ForingKey,$id));		
		}
		
		/**
		 * Insert the data in the module DB
		*/
		public function insert(){
			if (\OC_Filesystem::file_exists($this->paht)) {
				$size = getimagesize(OC_Filesystem::getLocalFile($this->paht),$info);
				if(isset($info['APP13']))
				{
					$iptc = iptcparse($info['APP13']);
					foreach ($iptc as $key => $section) {
						foreach ($section as $name => $val){
							$id=$this->insertTag($key,$val);
							$this->insertTagPhoto($id);
							OCP\Util::writeLog("facefinder",$this->paht."-".$key.$name.': '.$val,OCP\Util::ERROR);
						}
					}
				}
			}
		}
		
		public  function getTagPaht(){
			$stmt = \OCP\DB::prepare('SELECT * FROM oc_facefinder_tag_module  inner  join oc_facefinder_tag_photo_module on(oc_facefinder_tag_module.id=oc_facefinder_tag_photo_module.tag_id) inner join oc_facefinder on(oc_facefinder.photo_id=oc_facefinder_tag_photo_module.photo_id) where uid_owner LIKE ? And oc_facefinder.path=?');
			$result = $stmt->execute(array(\OCP\USER::getUser(),$this->paht));
			$tagarray=array();
			while (($row = $result->fetchRow())!= false) {
				$tagarray[]=array('name'=>$row['name'],"tag"=>$row['tag']);
			}
			return $tagarray;
		}
		
		public function writeTag(){
			if (\OC_Filesystem::file_exists($this->paht)) {
				$help=$this->getTagPaht();
				$iptc = array();
				$i=1;
				foreach ($help as $s){
					$iptc=$iptc+array('2#025'.$i=>$s['tag']);
					$i++;
				}
				$data = '';
			foreach($iptc as $tag => $string){
					OCP\Util::writeLog("facefinder","$tag $string",OCP\Util::ERROR);
    				$tag = str_replace("2#", "", $tag);
    				$tag = substr($tag, 0, 3);
    				OCP\Util::writeLog("facefinder","$tag $string",OCP\Util::ERROR);
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
		
			public function iptc_make_tag($rec, $data, $value)
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
		/*
		 * To search for in the module tables store information
		 * @param String  $query
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
		

		
		public static function searchArry($name,$tag){
		 // select * from oc_facefinder_tag_module inner join oc_facefinder_tag_photo_module  on  (oc_facefinder_tag_module.id=oc_facefinder_tag_photo_module.tag_id) inner join oc_facefinder on (oc_facefinder.photo_id=oc_facefinder_tag_photo_module.photo_id);
		$results=array();
		$stmt = OCP\DB::prepare('select * from *PREFIX*facefinder_tag_module inner join *PREFIX*facefinder_tag_photo_module  on  (*PREFIX*facefinder_tag_module.id=*PREFIX*facefinder_tag_photo_module.tag_id) inner join *PREFIX*facefinder on (*PREFIX*facefinder.photo_id=*PREFIX*facefinder_tag_photo_module.photo_id) where`tag` like ? and `name` like ?');
		$result=$stmt->execute(array($tag,$name));
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
		 * To search for equivalents the function return a Array of the Ids and percent of equivalents
		 * @return array of id and percent of equivalents
		*/
		public function equivalent(){}
		
		/**
		 * Create the DB of the Module the if the module hase an new Version numper
		*/
		public static function initialiseDB(){
			$classname="Tag_Module";
			if(OC_Appconfig::hasKey('facefinder',$classname)){
			
				/**
				 * @todo check korektnes
				*/
				if (self::checkVersion()|| !self::AllTableExist()){
					OCP\Util::writeLog("facefinder",$classname." need update",OCP\Util::DEBUG);
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
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			$table_exif=($rownum==1);
			$stmt = OCP\DB::prepare('SHOW TABLES LIKE \'*PREFIX*facefinder_tag_photo_module\'');
			$result=$stmt->execute();
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
			}
			$table_kamera=($rownum==1);
			return ($table_exif && $table_kamera);
		}
	
		private static function createDBtabels($classname){
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS `*PREFIX*facefinder_tag_photo_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`*PREFIX*facefinder_tag_module`');
			$stmt->execute();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (tag_id) REFERENCES *PREFIX*facefinder_tag_module(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
		}
}