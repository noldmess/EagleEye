<<?php
//require_once dirname(__FILE__) .'/../../../lib/base.php';

//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) .'/../../lib/photo_test.php');
//require_once(dirname(__FILE__) .'/../');


require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
require_once('/var/www/owncloud/apps/facefinder/module/Tag_Module.php');
OC_App::loadApp('facefinder');

class TestOfTag_modul extends PHPUnit_Framework_TestCase{

	private $photoclass;
	private $path;
	private $id;
	private $Tag_Modul;
	private $user='test';

	function __construct() {
		//echo $path;
	
		$this->path="DSC_0317.jpg";
		$this->photoclass=new OC_FaceFinder_Photo($this->path);
		$testUser=new OC_User_Dummy();
		$testUser->createUser ("test","test" );
		OC_User::login ("test","test" );
		OC_Filesystem::init("test",'/' . $this->user . '/files'  );
		$this->Tag_Module=new Tag_Module($this->path);
		
	}
	
	public function removDB(){ 
		OC_DB::dropTable('oc_facefinder_tag_module');
		OC_DB::dropTable('oc_facefinder_tag_photo_module');
	}

	
	public function removeTag($key,$tag){
		$stmt = OCP\DB::prepare('DELETE FROM *PREFIX*facefinder_tag_module WHERE `name` LIKE ? AND `tag` LIKE ?');
		$result=$stmt->execute(array($key,$tag));
	}
	
	
		public  function  testinitialiseDB(){
			//remove DB "not necessary"
			$this->removDB();
			$this->assertFalse(Tag_Module::AllTableExist());
			Tag_Module::initialiseDB();
			$this->assertTrue(Tag_Module::AllTableExist());
			//remove DB module Function
			Tag_Module::removeDBtabels();
			$this->assertFalse(Tag_Module::AllTableExist());
			Tag_Module::initialiseDB();
			$this->assertTrue(Tag_Module::AllTableExist());
			//check if initialiseDB() works correct
			$this->assertFalse(Tag_Module::initialiseDB());
			$this->removDB();
			$this->assertTrue(Tag_Module::initialiseDB());
			//change version number
			OC_Appconfig::setValue 	('facefinder','Tag_Module','0.0.1');
			$version=OC_Appconfig::getValue('facefinder','Tag_Module');
			$this->assertTrue(Tag_Module::initialiseDB());
			$version=OC_Appconfig::getValue('facefinder','Tag_Module');
			//check if initialiseDB change version number 
			$this->assertTrue(version_compare($version,Tag_Module::getVersion() , '=='));
		}
	
		//checks insert of a tag in the db
		public function testTag(){
			$key="test";
			$tag="test";
			$this->removeTag($key,$tag);
			$this->Tag_Module=new Tag_Module($this->path);
			$this->assertNULL($this->Tag_Module->getTagId($key,$tag));
			$this->Tag_Module->insertTag($key,$tag);
			$this->assertNotNULL($this->Tag_Module->getTagId($key,$tag));
			$this->removeTag($key,$tag);
			$this->assertNULL($this->Tag_Module->getTagId($key,$tag));
		}
		
	public function testTagPhoto(){
		$this->photoclass=new OC_FaceFinder_Photo($this->path);
	
		$this->photoclass->insert();
		$id=$this->photoclass->getID();
		
		$this->Tag_Module=new Tag_Module($this->path);
		$key="test";
		$tag="test";
		$tagid=$this->Tag_Module->insertTag($key,$tag);
		$this->Tag_Module->setForingKey($id);
		$this->assertFalse($this->Tag_Module->issetTagPhotoId($id,$tagid));
		
		$this->Tag_Module->insertTagPhoto($tagid,0,0,0,0);
		$this->Tag_Module->insertTagPhoto($tagid);
		$this->assertTrue($this->Tag_Module->issetTagPhotoId($id,$tagid));
		$this->Tag_Module->removeTagPhoto($tagid);
		$this->assertFalse($this->Tag_Module->issetTagPhotoId($id,$tagid));
		
		$this->removeTag($key,$tag);
		$this->photoclass->remove();
	}
		
	function testinsert(){
		try {
			$this->Tag_Module->insert();
	
		}catch (Exception $e){
			$this->assertNotNULL($e->getMessage());
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
		
/*
		public  function testsearch(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
			
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$this->Tag_Moduleclass->setForingKey($id);
				$this->Tag_Moduleclass->insert();
				$result=Tag_Module::search('FNumber');
				$this->assertEquals($result[0]->text,'FNumber-f/5.6');
				$this->photoclass->remove();
			}
		*/
		
		/*
		
			public  function testsearchArry(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
			
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$this->Tag_Moduleclass->setForingKey($id);
				$this->Tag_Moduleclass->insert();
				$result=Tag_Module::searchArry('FNumber','f/5.6');
				$this->assertEquals($result[0],$this->path);
				$this->photoclass->remove();
			}
		
		

	*/



		
	

		public function testequivalent(){
			$photoClass1=new OC_FaceFinder_Photo("/DSC_0317_test.jpg");
			$photoClass2=new OC_FaceFinder_Photo("/DSC_0317.jpg");
			$photoClass1->insert();
			$photoClass2->insert();
			$id1=$photoClass1->getID();
			$id2=$photoClass2->getID();
			$tagClass1=new Tag_Module("/DSC_0317_test.jpg");
			$tagClass2=new Tag_Module("/DSC_0317.jpg");
			$tagClass1->setForingKey($id1);
			$tagClass2->setForingKey($id2);
			$tagClass1->insert();
			$tagClass2->insert();
			$equalarray=$tagClass1->equivalent();		
			$this->assertTrue($equalarray["/DSC_0317.jpg"][0]=="/DSC_0317_test.jpg");
			$photoClass1->remove();
			$photoClass2->remove();
		}
		


}
?>