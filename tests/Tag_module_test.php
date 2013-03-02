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
	private $user='sss';

	function __construct() {
		//echo $path;
	
		$this->path="DSC_0317.jpg";
		$this->photoclass=new OC_FaceFinder_Photo($this->path);
		$testUser=new OC_User_Dummy();
		$testUser->createUser ($this->user,$this->user);
		OC_User::login ($this->user,$this->user);
		OC_Filesystem::init($this->user,'/' . $this->user . '/files'  );
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
		

		
		
		public function testwriteTag(){
			$paht="testPhoto_tag.jpg";
			OC_Filesystem::copy( "testPhoto.jpg",$paht);
			$photoClass1=new OC_FaceFinder_Photo($paht);
			$photoClass1->insert();
			$id=$photoClass1->getID();
			$Tag_Module =new Tag_Module($paht);
			$Tag_Module->setForingKey($id);
			$Tag_Module->insert();
			$tagid=$Tag_Module->insertTag("KEYWORDS","test");
			$tagid2=$Tag_Module->insertTag("COUNTRY","test");
			$Tag_Module->insertTagPhoto($tagid);
			$Tag_Module->insertTagPhoto($tagid2);
			$Tag_Module->writeTag();
			$size = getimagesize(OC_Filesystem::getLocalFile($paht),$info);
			$iptc = iptcparse($info['APP13']);
			$this->assertEquals($iptc["2#025"][0],"test");
			$this->assertEquals($iptc["2#101"][0],"test");
			$photoClass1->remove();
			OC_Filesystem::unlink( $paht);
			$photoClass1->remove();
		}
		

		public  function testsearch(){
				$id=$this->photoclass->getID();
				$result=Tag_Module::search('Ven');
				$this->assertEquals(count($result),1);
				$this->assertEquals($result[0]->text,"Venedig");
				$this->photoclass->remove();
			}
		
		
		
		
			public  function testsearchArry(){
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$this->Tag_Module->setForingKey($id);
				$this->Tag_Module->insert();
				$result=Tag_Module::searchArry("KEYWORDS","Venedig");
				
				$this->assertEquals($result[0],$this->path);
				
				$this->photoclass->remove();
			}
		
		

	



		
	

		public function testequivalent(){
			$img1array=array("/DSC_0317_test.jpg","/DSC_0317.jpg");
			asort($img1array);
			$photoClass1=new OC_FaceFinder_Photo($img1array[0]);
			$photoClass2=new OC_FaceFinder_Photo($img1array[1]);
			$photoClass1->insert();
			$photoClass2->insert();
			$id1=$photoClass1->getID();
			$id2=$photoClass2->getID();
			$tagClass1=new Tag_Module($img1array[0]);
			$tagClass2=new Tag_Module($img1array[1]);
			$tagClass1->setForingKey($id1);
			$tagClass2->setForingKey($id2);
			$tagClass1->insert();
			$tagClass2->insert();
			$equalarray=$tagClass1->equivalent()->getEqualArray();		
			$this->assertEquals($equalarray[$img1array[1]][$img1array[0]],0.5);
			$photoClass1->remove();
			$photoClass2->remove();
		}
		


}
?>