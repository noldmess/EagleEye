<?php

class Tag_Module implements OC_Module_Interface{
	
	
	private  static $version='0.0.1';
	private $ForingKey=null;
		/**
		 * for the construction of the class you need the path
		 * @param unknown_type $paht
		 */
		public function __construct($path){
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
		/**
		 * To issier create a table insert tis funktion nead the foreign key for the table
		 * @param int $key
		 */
		public function  setForingKey($key){
			$this->ForingKey=$key;
		}
		
		public function insertTag($key,$tag){
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
		
		public function insertTagPhoto($id){
			$tag_id=$this->getTagId($key,$tag);
			if($tag_id!=null){
				return  $tag_id;
			}else{
				$stmt = OCP\DB::prepare('INSERT INTO `*PREFIX*facefinder_tag_photo_module` (`photo_id`, `tag_id`) VALUES ( ?, ?);');
				$result=$stmt->execute(array($this->ForingKey,$id));
				$tag_id=$this->getTagId($key,$tag);
				if($tag_id!=null)
					return  $tag_id;
			}
				
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
						foreach ($section as $name => $val)
							$id=$this->insertTag($key,$val);
							$this->insertTagPhoto($id);
							OCP\Util::writeLog("facefinder","sdfdf".$key.$name.': '.$val,OCP\Util::ERROR);
					}
				}
			}
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
		 * To search for in the module tables store information
		 * @param String  $query
		*/
		public function search($query){}
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
				$appkey=OC_Appconfig::getValue('facefinder',$classname);
			
				/**
				 * @todo check korektnes
				*/
				if (version_compare($appkey, self::getVersion(), '<') || !self::AllTableExist()){
					OCP\Util::writeLog("facefinder",$classname." need update",OCP\Util::DEBUG);
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
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS`facefinder_tag_module`');
			$stmt->execute();
			$stmt = OCP\DB::prepare('DROP TABLE IF EXISTS `PREFIX*facefinder_tag_photo_module`');
			$stmt->execute();
			OC_DB::createDbFromStructure(OC_App::getAppPath('facefinder').'/module/'.$classname.'.xml');
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (photo_id) REFERENCES *PREFIX*facefinder(photo_id) ON DELETE CASCADE');
			$stmt->execute();
			$stmt = OCP\DB::prepare('ALTER TABLE`*PREFIX*facefinder_tag_photo_module`  ADD FOREIGN KEY (tag_id) REFERENCES *PREFIX*facefinder_tag_module(id) ON UPDATE CASCADE ON DELETE SET NULL');
			$stmt->execute();
		}
}