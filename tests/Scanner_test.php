<?php
//require_once dirname(_FILE__) .'/../lib/modulemaneger.php';
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once (dirname(__FILE__) .'/../../../lib/base.php');
OC_App::loadApp('facefinder');
//echo dirname(_FILE__);
class TestOfScanner extends PHPUnit_Framework_TestCase {


	function __construct() {
		$testUser=new OC_User_Dummy();
		$testUser->createUser ("test","test" );
		OC_User::login ("test","test" );
		OC_Filesystem::init( "test",'/test/files/test'  );
	}
	
	function testScan(){
		$this->unlinkfile();
		$arry=OC_FaceFinder_Scanner::scan();

		//$this->assertEquals(count($arry),0);
		$this->file();
		$arry=OC_FaceFinder_Scanner::scan();
		$this->assertEquals(count($arry),8);
 		$this->unlinkfile();
	}
	
	function file(){
		for($i=0;$i<10;$i++){
		OC_Filesystem::mkdir( 'test'.$i );
		//$this->makeJPG( 'test'.$i );
		OC_Filesystem::mkdir( 'test'.$i.'/test'.$i);
		$this->makeJPG( 'test'.$i.'/test'.$i );
		}
	}
	
	function  makeJPG($dir){
		$file=OC_Filesystem::fopen( $dir."/test.jpg","w" );
		//fopen($file,"w");
	}
	

	function unlinkfile(){
		for($i=0;$i<10;$i++){
			OC_Filesystem::rmdir( 'test'.$i );
			OC_Filesystem::rmdir( 'test'.$i.'/test'.$i);
		}
	}
	
	function  removeJPG($dir){
		$file=OC_Filesystem::fopen( $dir."/test.jpg","w" );
		//fopen($file,"w");
	}
	


	 
}