<?php
//require_once dirname(__FILE__) .'/../../../lib/base.php';

//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) .'/../../lib/photo_test.php');
//require_once(dirname(__FILE__) .'/../');
require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
require_once('/var/www/owncloud/apps/facefinder/module/EXIF_Module.php');
OC_App::loadApp('facefinder');
class TestOfEXIF_modul extends PHPUnit_Framework_TestCase{

	private $photoclass;
	private $path;
	private $id;
	private $user='test';
	
		  function __construct() {
			$this->path="DSC_0528.JPG";
			OC_User_Dummy::createUser ("test","test" );
			OC_User::login ("test","test" );
			$this->EXIF_Moduleclass=new EXIF_Module($this->path);
			OC_Filesystem::init( '/' . $this->user . '/files'  );
			$this->photoclass=new OC_FaceFinder_Photo($this->path);
			$this->photoclass->insert();
			$this->removDB();
			EXIF_Module::initialiseDB();
		}
		
		function __destruct(){
			OC_User_Dummy::createUser ("test");
			$this->photoclass->remove();
		}
		
		public function removDB(){
			OC_DB::dropTable('oc_facefinder_exif_module');
			OC_DB::dropTable('oc_facefinder_exif_photo_module');
		}
		
		//help funktion renove exif from DB
		public function removeExif($key,$valued){
			$stmt = OCP\DB::prepare("DELETE FROM *PREFIX*facefinder_exif_module WHERE `name` LIKE ? AND `valued` LIKE ?");
			$result = $stmt->execute(array($key,$valued));
		}
		
		public  function  testinitialiseDB(){
			$this->removDB();
			$this->assertFalse(EXIF_Module::AllTableExist());
			EXIF_Module::initialiseDB();
			$this->assertTrue(EXIF_Module::AllTableExist());
			
			EXIF_Module::removeDBtabels();
			$this->assertFalse(EXIF_Module::AllTableExist());
			EXIF_Module::initialiseDB();
			$this->assertTrue(EXIF_Module::AllTableExist());
			
			OC_Appconfig::setValue 	('facefinder','EXIF_Module','0.0.1');
			$version=OC_Appconfig::getValue('facefinder','EXIF_Module');
			$this->assertTrue(version_compare($version,'0.0.1' , '=='));
			EXIF_Module::initialiseDB();
			$version=OC_Appconfig::getValue('facefinder','EXIF_Module');
			$this->assertTrue(version_compare($version,EXIF_Module::getVersion() , '=='));
		}
		
		
			public function testinsertExifandgetExifId(){
				$key="test";
				$valued="test";
				$this->removeExif($key,$valued);
				$class=new EXIF_Module("test");
				$this->assertNull($this->EXIF_Moduleclass->getExifId($key,$valued));
				$this->EXIF_Moduleclass->insertExif($key,$valued);
				$this->assertNotNull($this->EXIF_Moduleclass->getExifId($key,$valued));
				$this->removeExif($key,$valued);
			}
			
			
		

		
		
			public function  testissetExiPhotoIdAndinsertExifPhoto(){
				//inset a Photo in the DB 
				
				$id=$this->photoclass->getID();
				//create the class 
				$class=new EXIF_Module("test");
				$this->EXIF_Moduleclass->setForingKey($id);
				$key="test";
				$valued="test";
				$this->removeExif($key,$valued);
				//insert the test exif in the DB 
				$help_id=$this->EXIF_Moduleclass->insertExif($key,$valued);
				//test if not correct id can be inserted
				$boolExeption=false;
				try {
					$this->EXIF_Moduleclass->insertExifPhoto(-1);
				}catch (Exception $e){
					$this->assertNotNULL($e);
					$boolExeption=true;
				}
				$this->assertTrue($boolExeption);
				//insert the exifPhoto in the DB
				$this->EXIF_Moduleclass->insertExifPhoto($help_id);
				//test if it is insert 
				$this->assertTrue($this->EXIF_Moduleclass->issetExiPhotoId($help_id));
				//remove the Exif 
				$this->removeExif($key,$valued);
				//now also the exif Photo must be removed
				$this->assertFalse($this->EXIF_Moduleclass->issetExiPhotoId($id,$help_id));
				
			}
		
		


			
	
		
			function testinsert(){
				$id=$this->photoclass->getID();
				try {
					$this->EXIF_Moduleclass->insert();

				}catch (Exception $e){
					$this->assertNotNULL($e->getMessage());
				}
				$id=$this->photoclass->getID();
				$help_id=$this->EXIF_Moduleclass->insertExif("ExposureTime","1/125s");
				$this->assertFalse($this->EXIF_Moduleclass->issetExiPhotoId($help_id));
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$this->assertFalse($this->EXIF_Moduleclass->issetExiPhotoId($help_id));
			}
	
			public  function testsearch(){
				$id=$this->photoclass->getID();
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$result=EXIF_Module::search('FNu');
				$this->assertEquals(count($result),1);
				$this->assertEquals($result[0]->text,'FNumber-f/5.6');
				
			}
		
		
		
		
			public  function testsearchArry(){
				$id=$this->photoclass->getID();
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$result=EXIF_Module::searchArry('FNumber','f/5.6');
				$this->assertEquals(count($result),1);
				$this->assertEquals($result[0],$this->path);
				
			}
			
		
		
		}
		?>