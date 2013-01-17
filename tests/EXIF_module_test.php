<?php
//require_once dirname(__FILE__) .'/../../../lib/base.php';

//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once(dirname(__FILE__) .'/../../lib/photo_test.php');
//require_once(dirname(__FILE__) .'/../');
require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
require_once('/var/www/owncloud/apps/facefinder/module/EXIF_Module.php');
OC_App::loadApp('facefinder');
require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
class TestOfEXIF_modul extends PHPUnit_Framework_TestCase{

	private $photoclass;
	private $path;
	private $id;
	private $user='test';
	
		  function __construct() {
			$this->path="DSC_0528.JPG";
			$this->EXIF_Moduleclass=new EXIF_Module($this->path);
			OC_Filesystem::init( '/' . $this->user . '/files'  );
		}
		

		function  isInsert($id){
			$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder_exif_module` WHERE `photo_id` = ? ');
			$result = $stmt->execute(array($id));
			$rownum=0;
			while (($row = $result->fetchRow())!= false) {
				$rownum++;
				$this->id=$row['photo_id'];
			}
			return $rownum;
		}
	
		 function testinsert(){
		 	$this->user='test';
		 	$this->photoclass=new OC_FaceFinder_Photo($this->path);
		 	$this->photoclass->setUser($this->user);
		 	$this->photoclass->insert();
		 	
		 	try {
		 		$this->EXIF_Moduleclass->insert();
		 	//	$this->expectException(new Exception('no foringkey set'));
		 	}catch (Exception $e){
		 		$this->assertEquals($e->getMessage(),'no foringkey set');
		 	}
		 	$id=$this->photoclass->getID();
		 	$this->EXIF_Moduleclass->setForingKey($id);
		 	$this->EXIF_Moduleclass->insert();
		 	$this->assertEquals($this->isInsert($id),1);
		 	$this->photoclass->remove();
		 	$this->assertEquals($this->isInsert($id),0);
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
		
		
		
		public  function  testinitialiseDB(){
			OC_DB::dropTable('oc_facefinder_exif_module');
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
		

}
	?>