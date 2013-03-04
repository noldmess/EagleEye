<?php
//require_once dirname(_FILE__) .'/../lib/modulemaneger.php';
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once (dirname(__FILE__) .'/../../../lib/base.php');
OC_App::loadApp('facefinder');
//echo dirname(_FILE__);
class TestOfScanner extends PHPUnit_Framework_TestCase {
	private $user='sss';

	function __construct() {
		
		$testUser=new OC_User_Dummy();
		$testUser->createUser ($this->user ,$this->user  );
		OC_User::login ($this->user , $this->user );
		OC_Filesystem::init( $this->user,''  );
		
	}
	
	function testScan(){
		$arry=OC_FaceFinder_Scanner::scan();
		$this->assertEquals(count($arry),12);
		$paht="testPhoto_tag.jpg";
		OC_Filesystem::copy( "testPhoto.jpg",$paht);
		$arry=OC_FaceFinder_Scanner::scan();
		$this->assertEquals(count($arry),13);
		OC_Filesystem::unlink( $paht);
		$arry=OC_FaceFinder_Scanner::scan();
		$this->assertEquals(count($arry),12);
	}

	


	 
}