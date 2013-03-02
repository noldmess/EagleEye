<?php
//require_once dirname(__FILE__) .'/../../../lib/base.php';

//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//$_COOKIE["oc_username"]='test';
OC_App::loadApp('facefinder');
//s
class TestOfPhoto extends PHPUnit_Framework_TestCase {

	

	private $photoclass;
	private $path;
	private $id;
	private $user="sss";

	 function  getNumnberResult($path){
		$stmt = \OCP\DB::prepare('SELECT * FROM `*PREFIX*facefinder` WHERE `uid_owner` LIKE ? AND `path` LIKE ?');
		$result = $stmt->execute(array($this->user, $path));
		$rownum=0;
		while (($row = $result->fetchRow())!= false) {
			$rownum++;
			$this->id=$row['photo_id'];
		}
		return  $rownum;
	}
	
	function __construct() {
		
		$testUser=new OC_User_Dummy();
		$testUser->createUser ($this->user,$this->user );
		OC_User::login ($this->user,$this->user );
		OC_Filesystem::init($this->user,'/' . $this->user . '/files'  );
		$this->path="DSC_0317.jpg";
		$this->photoclass=new OC_FaceFinder_Photo($this->path);
		
	}
	
	function __destruct(){
			$testUser=new OC_User_Dummy();
			$testUser->deleteUser ("test");
			$this->photoclass->remove();
	}
	//check if the photo that name is already inserted in the DB
	function  testissetPhotoId(){
		$this->assertFalse($this->photoclass->issetPhotoId());
		$this->photoclass->insert();
		$this->assertTrue($this->photoclass->issetPhotoId());
		$this->photoclass->remove();
		$this->assertFalse($this->photoclass->issetPhotoId());
	}
	
	// test if the insert works correct  
	function testinsert() {
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($rownum,0);
		$this->photoclass->insert();
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($rownum,1);
		$this->photoclass->insert();
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($rownum,1);
	}
	
	function testgetid(){
		$id_tmp=$this->photoclass->getID();
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($id_tmp,$this->id);
	}
	
	function testupdate(){
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($rownum,1);
		$newpath=dirname(__FILE__)."/testphoto/DSC01930.jpg_test";
		$this->photoclass->update($newpath);
		$this->path=$newpath;
		$rownum=$this->getNumnberResult($this->path);
		$this->assertEquals($rownum,1);
		$this->photoclass->remove();
	}
	
	function testremove() {
			$this->photoclass->remove();
			$this->photoclass->insert();
			$rownum=$this->getNumnberResult($this->path);
			$this->assertEquals($rownum,1);
			$this->photoclass->remove();
			$rownum=$this->getNumnberResult($this->path);
			$this->assertEquals($rownum,0);
	}
		
	public function testequivalent(){
		$img1array=array("/DSC_0317_test.jpg","/DSC_0317.jpg");
		asort($img1array);
		$photoClass1=new OC_FaceFinder_Photo("$img1array[0]");
		$photoClass2=new OC_FaceFinder_Photo("$img1array[1]");
		$photoClass1->insert();
		$photoClass2->insert();
		$equalarray=$photoClass2->equivalent();
		$this->assertEquals($equalarray[$img1array[1]][$img1array[0]],100);
		$photoClass1->remove();
		$photoClass2->remove();
	}
		


	 
}


?>
