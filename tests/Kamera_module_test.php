<<?php
require_once('/var/www/owncloud/apps/facefinder/lib/moduleinterface.php');
require_once('/var/www/owncloud/apps/facefinder/module/Kamera_Module.php');
OC_App::loadApp('facefinder');

class TestOfKamera_modul extends PHPUnit_Framework_TestCase{

	private $photoclass;
	private $path;
	private $id;
	private $Kamera_Moduleclass;
	private $user='sss';

	function __construct() {
		$testUser=new OC_User_Dummy();
		$testUser->createUser ($this->user,$this->user );
		OC_User::login ($this->user,$this->user);
		//remove all DB tables
		$this->removDB();
			
		//initial the FileSystem to use Owncloud funktions
		OC_Filesystem::init($this->user,'/' . $this->user . '/files'  );
		$this->path="DSC_0528.JPG";
		$this->photoclass=new OC_FaceFinder_Photo($this->path);
		$this->photoclass->insert();
		$this->Kamera_Moduleclass=new Kamera_Module($this->path);
		Kamera_Module::initialiseDB();
	}
	
	function __destruct(){
		$this->photoclass->remove();
	}
	

	//check construction of the module
	public  function  testinitialiseDB(){
			
			//remove DB module Function
			Kamera_Module::removeDBtabels();
			$this->assertFalse(Kamera_Module::AllTableExist());
			Kamera_Module::initialiseDB();
			$this->assertTrue(Kamera_Module::AllTableExist());
			
			//check if initialiseDB() works correct
			$this->assertFalse(Kamera_Module::initialiseDB());
			$this->removDB();
			$this->assertTrue(Kamera_Module::initialiseDB());
			//change version number
			OC_Appconfig::setValue 	('facefinder','Kamera_Module','0.0.1');
			$version=OC_Appconfig::getValue('facefinder','Kamera_Module');
			$this->assertTrue(Kamera_Module::initialiseDB());
			$version=OC_Appconfig::getValue('facefinder','Kamera_Module');
			//check if initialiseDB change version number 
			$this->assertTrue(version_compare($version,Kamera_Module::getVersion() , '=='));
		}

	
	//checks insert of a kamera in the db
	public function testinsertKamera(){
		$make="test";
		$model="test";
		$this->assertFalse($this->issetKamera($make, $model));
		$this->Kamera_Moduleclass->insertKamera($make, $model);
		$this->assertTrue($this->issetKamera($make, $model));
		$this->removeKamera($make, $model);
	}
	
	public function testgetKameraId(){
		$make="test";
		$model="test";
		$this->assertNULL($this->Kamera_Moduleclass->getKameraId($make, $model));
		$this->Kamera_Moduleclass->insertKamera($make, $model);
		$this->assertNotNULL($this->Kamera_Moduleclass->getKameraId($make, $model));
		$this->removeKamera($make, $model);
	}
	
	//
	public function testinsertKameraPhotoIdAndissetKameraPhotoId(){
		$make="test";
		$model="test";
		//initial configuration
		$this->Kamera_Moduleclass->insertKamera($make, $model);
		$id=$this->photoclass->getID();
		$this->Kamera_Moduleclass->setForingKey($id);
		$kamera_id=$this->Kamera_Moduleclass->getKameraId($make, $model);
		$this->removeKameraPhoto($id,$kamera_id);
		//chech if befor the insert ther is no sucht combination
		$this->assertFalse($this->Kamera_Moduleclass->issetKameraPhotoId($kamera_id));
		$this->Kamera_Moduleclass->insertKameraPhoto($kamera_id);
		$this->assertTrue($this->Kamera_Moduleclass->issetKameraPhotoId($kamera_id));
		//removes also KameraPhoto (delete cascade)
		$this->removeKameraPhoto($id,$kamera_id);
		
	}
	


	public  function testsearch(){
		$make="test";
		$model="test";
		$this->Kamera_Moduleclass->insertKamera($make, $model);
		$result=Kamera_Module::search('te');
		$this->assertEquals($result[0]->text,'test-test');
		$this->assertEquals(count($result),1);
	}





	public  function testsearchArry(){
				$id=$this->photoclass->getID();
				$this->Kamera_Moduleclass->setForingKey($id);
				$this->Kamera_Moduleclass->insert();
				$result=Kamera_Module::searchArry('NIKON D3000','NIKON CORPORATION');
				$this->assertEquals($result[0],$this->path);
				$this->assertEquals(count($result),1);
	}




	public function testinsert(){
		$id=$this->photoclass->getID();
		$this->Kamera_Moduleclass->setForingKey($id);
		$this->Kamera_Moduleclass->insert();
		$id_kamer=$this->Kamera_Moduleclass->getKameraId('NIKON CORPORATION','NIKON D3000');
		$this->assertTrue($this->issetKamera('NIKON CORPORATION','NIKON D3000'));
		$this->Kamera_Moduleclass->issetKameraPhotoId($id_kamer);
		$this->removeKamera('NIKON CORPORATION','NIKON D3000');
	}
	



		
	public function removeKameraPhoto($id_photo, $id_kamera){
		$stmt = OCP\DB::prepare('DELETE FROM *PREFIX*facefinder_kamera_photo_module  WHERE photo_id = ? and kamera_id = ?');
		$result=$stmt->execute(array($id_photo, $id_kamera));
	}
	
	public function removeKamera($make, $model){
		$stmt = OCP\DB::prepare('DELETE FROM *PREFIX*facefinder_kamera_module  WHERE make like ? and model like ?');
		$result=$stmt->execute(array($make, $model));
	}
	
	public function issetKamera($make, $model){
		$stmt = OCP\DB::prepare('Select * FROM oc_facefinder_kamera_module  WHERE make like ? and model like ?');
		$result=$stmt->execute(array($make, $model));
		return ($result->numRows()==1);
	}

	public function removDB(){
		OC_DB::dropTable('oc_facefinder_kamera_module');
		OC_DB::dropTable('oc_facefinder_kamera_photo_module');
	}
	
	public function testequivalent(){
		$img1array=array("/DSC_0528.JPG","/DSC_0135.JPG");
		asort($img1array);
		//insert photo
		$photoClass1=new OC_FaceFinder_Photo($img1array[0]);
		$photoClass2=new OC_FaceFinder_Photo($img1array[1]);
		$photoClass1->insert();
		$photoClass2->insert();
	
		//ge the foreign keys
		$id1=$photoClass1->getID();
		$id2=$photoClass2->getID();
		//insert exif
		$kameraClass1=new Kamera_Module($img1array[0]);
		$kameraClass2=new Kamera_Module($img1array[1]);
		$kameraClass1->setForingKey($id1);
		$kameraClass2->setForingKey($id2);
		$kameraClass1->insert();
		$kameraClass2->insert();
			
		$equalarray=$kameraClass2->equivalent()->getEqualArray();
		$this->assertEquals($equalarray[$img1array[1]][$img1array[0]],10);
		$photoClass1->remove();
		$photoClass2->remove();
	}

}
?>