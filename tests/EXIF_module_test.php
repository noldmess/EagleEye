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
			$this->EXIF_Moduleclass=new EXIF_Module($this->path);
			OC_Filesystem::init( '/' . $this->user . '/files'  );
			EXIF_Module::initialiseDB();
		}
		

		function  isInsertDate($id){
			$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder_exif_date_module` WHERE `photo_id` = ? ');
			$result = $stmt->execute(array($id));
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
				 $this->id=$row['photo_id'];
			}
			return $rownum;
		}

	

		  function testgetExitHeader(){
		  	$exif=EXIF_Module::getExitHeader($this->path);
		  	$this->assertNotNull($exif);
		  	$this->assertEquals($exif['FILE']['FileName'],"DSC_0528.JPG");
		  	$this->assertNotEquals($exif['FILE']['FileName'],"DSC019f30.jpg");
		  	$exif=EXIF_Module::getExitHeader('DSC_0292.jpg');
		  	$this->assertNotNull($exif);
		  	$this->assertEquals($exif['FILE']['FileName'],"DSC_0292.jpg");
		  	$this->assertNotEquals($exif['FILE']['FileName'],"DSC_0ff292.jpg");
		  }
		
		
		

		 function testgetDateOfEXIF(){
		 	$exif=EXIF_Module::getExitHeader($this->path);
		 	$help=	EXIF_Module::getDateOfEXIF(array('s'));
		 	$this->assertNull($help);
		 	
		 		$help=EXIF_Module::getDateOfEXIF('test');
		 		$this->assertNull($help);
		 
		 	//Date of uplaod 
		 	$exif=EXIF_Module::getExitHeader('DSC_0292.jpg');
		 	$date=EXIF_Module::getDateOfEXIF($exif);
		 	$this->assertNotEquals($date,'2012:12:05 20:26:06');
		 	$exif=EXIF_Module::getExitHeader('DSCN0127.JPG');
	 		$date=EXIF_Module::getDateOfEXIF($exif);
	 		$this->assertEquals($date,'2005:05:31 17:01:00');
		 	
		}
		
		public function removDB(){
			OC_DB::dropTable('oc_facefinder_exif_module');
			OC_DB::dropTable('oc_facefinder_exif_photo_module');
			OC_DB::dropTable('oc_facefinder_exif_date_module');
		}
		
		
		public  function  testinitialiseDB(){
			$this->removDB();
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
			$this->assertNull($class->getExifId($key,$valued));
			$class->insertExif($key,$valued);
			echo $class->getExifId($key,$valued);
			$this->assertNotNull($class->getExifId($key,$valued));
			$this->removeExif($key,$valued);
			}
			
			public function removeExif($key,$valued){
				$stmt = OCP\DB::prepare("DELETE FROM *PREFIX*facefinder_exif_module WHERE `name` LIKE ? AND `valued` LIKE ?");
				$result = $stmt->execute(array($key,$valued));
			} 
		

		
			
			public function  testissetExiPhotoIdAndinsertExifPhoto(){
				//'INSERT INTO `*PREFIX*facefinder_exif_photo_module` (`photo_id`, `exif_id`) VALUES ( ?, ?);'
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
				$this->photoclass->setUser($this->user);
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$class=new EXIF_Module("test");
				$class->setForingKey($id);
				$key="test";
				$valued="test";
				$this->removeExif($key,$valued);
				
				$help_id=$class->insertExif($key,$valued);
				$boolExeption=false;
				try {
					$class->insertExifPhoto(-1);
				}catch (Exception $e){
					$this->assertNotNULL($e->getMessage());
					$boolExeption=true;
				}
				$this->assertTrue($boolExeption);
				$class->insertExifPhoto($help_id);
				$this->assertTrue($class->issetExiPhotoId($id,$help_id));
				$this->removeExif($key,$valued);
				$this->assertFalse($class->issetExiPhotoId($id,$help_id));
				$this->photoclass->remove();
			}
		
		

			public function  testgetDatePhotoId(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
				$this->photoclass->setUser($this->user);
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$class=new EXIF_Module("test");
				$class->setForingKey($id);
				$exif=EXIF_Module::getExitHeader($this->path);
				$this->assertNUll($class->getDatePhotoId($id));
				$this->assertFalse($class->issetDatePhoto($id));
				$class->insertDatePhoto($exif);
				$this->assertNotNUll($class->getDatePhotoId($id));
				$this->assertTrue($class->issetDatePhoto($id));
				$this->photoclass->remove();
			}
			
	
		
			function testinsert(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
				$this->photoclass->setUser($this->user);
				$this->photoclass->insert();
				try {
					$this->EXIF_Moduleclass->insert();
					//	$this->expectException(new Exception('no foringkey set'));
				}catch (Exception $e){
					$this->assertNotNULL($e->getMessage());
				}
				$id=$this->photoclass->getID();
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$this->assertTrue($this->EXIF_Moduleclass->issetDatePhoto($id));
				$this->assertEquals($this->isInsertDate($id),1);
				$this->photoclass->remove();
				$this->assertEquals($this->isInsertDate($id),0);
			}
		
			public  function testsearch(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
				$this->photoclass->setUser($this->user);
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$result=EXIF_Module::search('FNumber');
				$this->assertEquals($result[0]->text,'FNumber-f/5.6');
				$this->photoclass->remove();
			}
		
		
		
		
			public  function testsearchArry(){
				$this->photoclass=new OC_FaceFinder_Photo($this->path);
				$this->photoclass->setUser($this->user);
				$this->photoclass->insert();
				$id=$this->photoclass->getID();
				$this->EXIF_Moduleclass->setForingKey($id);
				$this->EXIF_Moduleclass->insert();
				$result=EXIF_Module::searchArry('FNumber','f/5.6');
				$this->assertEquals($result[0],$this->path);
				$this->photoclass->remove();
			}
			
		
		
		}
		?>